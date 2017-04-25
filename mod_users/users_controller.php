<?php
if (!defined('CONST_INCLUDE')) die('ACCES DIRECT INTERDIT');

class Users_Controller {
	private $model;
	private $view;

	function __construct() {
		$this->model = new Users_Model();
		$this->view = new Users_View();
	}


	function userList() {
		if (isset($_SESSION['rank']) && ($_SESSION['rank'] ==2)) {
			$list = $this->model->getUserList();
			$this->view->viewUserList($list);
		}
		else echo "<div class=post><img src=ressources/img/tiger.png class=mini /><br/> Accès refusé. <br/> <a href=index.php> Retour au site... </a></div>";
	}

	function userdetails() {
		if (isset($_GET['id'])) $user = $this->model->getUser($_GET['id']);
		else if (isset($_SESSION['login'])) $user = $this->model->getUserByLogin($_SESSION['login']);
		else {
			echo "<div class=post><img src=ressources/img/tiger.png class=mini /><br/> Accès refusé. <br/> <hr> <br/> <a href=index.php> Retour au site... </a></div>";
			return null;
		}
		$this->view->viewDetails($user);
	}

	function addUser() {
		if (isset($_SESSION['rank'])) $rank = $_SESSION['rank'] ;
		if (!isset($_SESSION['login']) || $rank == 2) {
			$this->view->viewAddUser();
		}
		else echo "<div class=post><img src=ressources/img/andy.png class=mini /><br/> Vous êtes déjà connecté... <br/> <hr> <br/><a href=index.php> Retour au site... </a></div>";
	}

	function updateUser() {
		if (isset($_SESSION['login'])) {
			$this->view->viewUpdateUser();
		}
		else echo "<div class=post><img src=ressources/img/tiger.png class=mini /><br/> Accès refusé. <br/> <hr> <br/> <a href=index.php> Retour au site... </a></div>";
	}

	function updateRank() {
		$newrank = htmlspecialchars($_POST['newrank']);
		$this->model->updateRank($newrank);
	}

	function op_addUser() {
		if (isset($_POST['lastname']) && isset($_POST['firstname']) && isset($_POST['login']) && isset($_POST['password']))  {
			$this->model->addUser($_POST);
			echo "<div class=post><img src=ressources/img/jacket.png class=mini /> <br/>Compte créé. <br/><hr> <br/><a href=index.php> Retour au site... </a></div>";

		}
		else echo "<div class=post>//WRONG NUMBER// <br/> Une erreur est survenue dans la création du compte... <br/><hr> <br/><a href=index.php> Retour au site... </a></div>";
	}

	function op_updateUser() {
		$firstname = htmlspecialchars($_POST['firstname']);
		$lastname = htmlspecialchars($_POST['lastname']);
		$login = htmlspecialchars($_POST['login']);
		$this->model->updateUser($_SESSION['idupdate'], $firstname, $lastname, $login);
		unset($_SESSION['idupdate']);
		echo "<div class=post><img src=ressources/img/biker.png class=mini /><br/> Les données de $login ont été mises à jour. <br/> <hr> <br/><a href=index.php> Retour au site... </a></div>";
	}

	function op_deleteUser($id) {
		$nbUsers = $this->model->nbUsers();

		$this->model->deleteUser($id);

		if ($nbUsers > $this->model->nbUsers()) {
			echo "<div class=post><img src=ressources/img/biker.png class=mini /><br/>Le compte a bien été supprimé. <hr> <br/><a href=index.php> Retour au site... </a></div";
		}
		else {
			echo "<div class=post><img src=ressources/img/tiger.png class=mini /><br/>Echec de la suppression <hr> <br/><a href=index.php> Retour au site... </a></div";
		}
	}

	function tryConnection() {
		$this->view->viewConnection();
	}

	function connection() {
		$hashedpassword = hash('sha256', $_POST['password']);		// On crypte l'entrée utilisateur
		if (!$userCaught = $this->model->user_exists($_POST['login'], $hashedpassword)) {		// On vérifie l'existence de l'utilisateur et du mot de passe entré
			echo "<div class=post><img src=ressources/img/tiger.png class=mini /><br/> Connexion refusée. <br/> <hr> <br/><a href=index.php> Retour au site... </a></div>";
		}
		else {
			$_SESSION['iduser'] = $userCaught->id;
			$_SESSION['login'] = $userCaught->login;
			$_SESSION['rank'] = $userCaught->rank;
			echo "<div class=post><img src=ressources/img/earl.png class=mini /><br/>Bon retour parmi nous, $_SESSION[login] !<br/><hr> <br/><a href=index.php> Retour au site... </a></div>";
		}
	}

	function disconnection() {
		if (isset($_SESSION['login'])) {
			session_unset();
			echo "<div class=post><img src=ressources/img/earl.png class=mini /><br/> N'hésitez pas à revenir ! <br/> <hr> <br/><a href=index.php> Retour au site... </a></div>";
		}
	}

}

?>
