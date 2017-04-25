<?php
if (!defined('CONST_INCLUDE')) die('ACCES DIRECT INTERDIT');

class Posts_Model extends ParamsDB {
	private $connection;

	function __construct() {
		parent::__construct();
		$this->connection = new PDO($this->dns, $this->user, $this->password);
		$this->connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
	}

	function getAuthor() {
		$request = $this->connection->pepare("select * from hm2_users where login = '?'");
		$request->execute(array($_SESSION['login']));
		$result = $request->fetch(PDO::FETCH_OBJ);
		return $result->login;
	}

	function getAuthorByPost($id) {
		$request = $this->connection->query("select * from hm2_users where id = '$id'");
		$result = $request->fetch(PDO::FETCH_OBJ);
		return $result->login;
	}

	function getPostList($visibility) {
		if (isset($_SESSION['rank']) && !$_SESSION['rank'] && !$visibility) {
			echo "Accès refusé.";
			return null;
		}
		$request = $this->connection->prepare("select * from hm2_posts where visibility = ?");
		$request->execute(array($visibility));
		$result = array_reverse($request->fetchAll());
		return $result;
	}

	function getPostListByAuthor() {
		if (is_numeric($iduser = $_GET['iduser'])) {
			$request = $this->connection->prepare("select * from hm2_posts where author_id = ?");
			$request->execute(array($iduser));
			return array_reverse($request->fetchAll());
		}
		else echo "<div class=post><img src=ressources/img/tiger.png class=mini /><br/>Erreur.<hr> <br/><a href=index.php> Retour au site... </a></div>";
	}

	function getSortedPostList($mode) {
		switch($mode) {
			case "sortedByVote":
			$request = $this->connection->query("select * from hm2_posts where visibility = 1 order by score desc");
			return $result = $request->fetchAll();
			break;
		}
	}


	function getPost($id) {
		$request = $this->connection->prepare("select * from hm2_posts");
		$request->execute(array($id));
		return $request->fetch(PDO::FETCH_OBJ);
	}


	function check_post() {

		$postOK = false;

		if (strlen($_POST['text']) < 10) {
			echo "<div class=post><img src=ressources/img/tiger.png class=mini /><br/>Texte trop court. Votre texte doit faire au moins dix caractères.<hr> <br/><a href=index.php> Retour au site... </a></div>";
		}

		// Vérifications fichier
		else if (!isset($_FILES['userfile'])){
			echo "<div class=post><img src=ressources/img/tiger.png class=mini /><br/>Erreur : Merci de charger un fichier.<hr> <br/><a href=index.php> Retour au site... </a></div>";
		}

		else if (!isset($_FILES['userfile']['error'])){
			echo "<div class=post><img src=ressources/img/tiger.png class=mini /><br/>Erreur : Paramètres invalides.<hr> <br/><a href=index.php> Retour au site... </a></div>";
		}

		else if ($_FILES['userfile']['size'] > 6*1048576) {
			echo "<div class=post><img src=ressources/img/tiger.png class=mini /><br/>Erreur : Fichier trop lourd. Taille requise : <= 2.5Mo.<hr> <br/><a href=index.php> Retour au site... </a></div>";
		}

		// (pathinfo($_FILES['userfile']['name'])['extension'])
		else if ((pathinfo($_FILES['userfile']['name'], PATHINFO_EXTENSION)) != "gif") {
			echo "<div class=post><img src=ressources/img/tiger.png class=mini /><br/>Erreur : Mauvais format. Format requis : gif.<hr> <br/><a href=index.php> Retour au site... </a></div>";

		}

		//Vérifier que le post n'existe pas déjà
		/*else {
			$request = $this->connection->prepare("select * from hm2_posts where author = ? and image = ?");
			$request->execute(array($_POST['author'], $_FILES['userfile']['name']));
			return $result = $request->fetch(PDO::FETCH_OBJ);
		}*/

		else $postOK = true;

		return $postOK;
	}


	function addPost($text, $image) {
		$searchAuthor = $this->connection->prepare("select id from hm2_users where login = ?");
		$searchAuthor->execute(array($_SESSION['login']));
		$author = $searchAuthor->fetch(PDO::FETCH_OBJ)->id;


		$request = $this->connection->prepare('insert into hm2_posts (text, image, author_id) values(?, ?, ?)');
		if($request->execute(array("$text", "$image", "$author"))) echo "<div class=post><img src=ressources/img/biker.png class=mini /><br/>Post créé.<hr> <br/><a href=index.php> Retour au site... </a></div>";
		else echo "<div class=post><img src=ressources/img/tiger.png class=mini /><br/>Echec de la requête.<hr> <br/><a href=index.php> Retour au site... </a></div>";
		move_uploaded_file($_FILES['userfile']['tmp_name'], './mod_posts/images/'.$_FILES['userfile']['name']);
	}

	function deletePost($id) {
		$request = $this->connection->prepare("delete from hm2_posts where id = ?");
		$request->execute(array($id));
	}

	function updatePost() {
		$request = $this->connection->prepare("update hm2_posts set text = ? where id = ?");
		if($request->execute(array($_POST['text'], $_SESSION['idupdate']))) {
			echo "<div class=post><img src=ressources/img/biker.png class=mini /><br/>Le post a été correctement édité.<hr> <br/><a href=index.php> Retour au site... </a></div>";
		}
		else
			echo "<div class=post><img src=ressources/img/tiger.png class=mini /><br/>Echec de l'édition.<hr> <br/><a href=index.php> Retour au site... </a></div>";
		unset($_SESSION['idupdate']);
	}

	function setVisible() {
		$request = $this->connection->prepare("update hm2_posts set visibility = 1 where id=?");
		if($request->execute(array($_SESSION['post']['id'])))
			echo "<div class=post><img src=ressources/img/biker.png class=mini /><br/>Le post a bien été activé.</br>
					<a href=index.php?module=posts&action=list&visibility=0>Retour à la liste.</a><br/><a href=index.php> Retour au site... </a></div>";
		$authorid = $_SESSION['post']['author_id'];
		$this->connection->query("update hm2_users set postnumber = postnumber+1 where id='$authorid'");
		unset($_SESSION['post']);
	}

	function nbPosts() {
		$nbposts = $this->connection->query("select * from hm2_posts");
		return $nbposts->rowCount();
	}

	// Méthode qui applique le vote de l'user à la BD
	/* 3 Cas :
		- l'user n'a jamais voté : création d'un nouveau vote et incrémentation d'une des valeurs
		- l'user a déjà fait le même vote : erreur
		- l'user fait un vote différent : modification des deux valeurs du vote
	*/
	function applyVote() {
		$request = $this->connection->query("select * from hm2_votes where post_id = $_POST[idpost] and user_id = $_SESSION[iduser]");

		// Si l'user n'a jamais voté
		if (!$vote = $request->fetch(PDO::FETCH_OBJ)) {
			if ($_POST['vote'] == "+1") $this->connection->query("insert into hm2_votes values ($_POST[idpost], $_SESSION[iduser], 1, 0)");
			else if ($_POST['vote'] == "-1") $this->connection->query("insert into hm2_votes values ($_POST[idpost], $_SESSION[iduser], 0, 1)");
		}

		// L'user a déjà voté
		else {
			// Si son vote est identique : erreur
			if ((($_POST['vote'] == "+1") && $vote->up == "1") || (($_POST['vote'] == "-1") && $vote->down == "1")) {
				// Panneau d'erreur;
			}
			else {
				if ($_POST['vote'] == "+1") {
					$this->connection->query("update hm2_votes set up = 1, down = 0 where post_id = $_POST[idpost] and user_id = $_SESSION[iduser]");
				}
				else if ($_POST['vote'] == "-1") {
					$this->connection->query("update hm2_votes set down = 1, up = 0 where post_id = $_POST[idpost] and user_id = $_SESSION[iduser]");

				}
				// Panneau OK
			}

			// Si son vote est différent : modif
		}
		$score = $this->getPostScore((integer)$_POST['idpost']);
		var_dump($score);
		$request = $this->connection->query("update hm2_posts set score = '$score' where id = '$_POST[idpost]'");
	}


	function getPostScore($post) {
		if (is_int($post)) {
			$request = $this->connection->query("select sum(up-down) from hm2_votes where post_id = '$post'");
		}
		else {
			$request = $this->connection->query("select sum(up-down) from hm2_votes where post_id = '$post[id]'");
		}
		$score = $request->fetchAll();
		return $score[0][0];
	}
}

?>
