<?php
if (!defined('CONST_INCLUDE')) die('ACCES DIRECT INTERDIT');
include_once('comments_model.php');
include_once('comments_view.php');
	
	
class Comments_Controller {
	private $model;
	private $view;
	
	function __construct() {
		$this->model = new Comments_Model();
		$this->view = new Comments_View();
	}
	
	
	function commentList() {
		$list = $this->model->getCommentsByPost($_GET['idpost']);
		foreach($list as $comment) {
			$post = $_GET['idpost'];
			$author = $this->model->getAuthorByComment($comment['author_id']);
			$this->view->viewComment($comment, $post, $author);
		}
		$this->view->viewAddComment();
			
	}
		
	function commentDetails() {
		if (isset($_POST['id'])) {
			$comment = $this->model->getComment($_GET['id']);
			$this->view->viewDetails($comment);
		}
	}	

		
	function op_addComment() {
		$text = htmlspecialchars($_POST['text']);
		$idpost = $_GET['idpost'];
		if (isset($_SESSION['login']))  {
			$text = htmlspecialchars($text);
			$this->model->addComment($text, $idpost);
			echo "
				<div class=post>Commentaire posté. </br> <a href=index.php?module=comments&action=viewComments&idpost=$idpost>Retour au commentaire.</a></br><a href=index.php>Retour aux posts.</a></div>

			";
		
		}
		else echo "comments_controller : Erreur </br>"; 
	}
	
	function op_deleteComment() {
		if ($_SESSION['rank'] > 0) {
			$nbComments = $this->model->nbComments();	
			$this->model->deleteComment($_GET['id']);
			$idpost = $_SESSION['post'];
			$_SESSION['post'];
			if ($nbComments > $this->model->nbComments()) {
				echo "<div class=post><img src=ressources/img/biker.png class=mini /><br/>Le commentaire a bien été supprimé.<hr> <br/><a href=index.php?module=comments&action=viewComments&idpost=$idpost> Retour aux commentaires... </a></div>";
			}
			else {
				echo "<div class=post><img src=ressources/img/tiger.png class=mini /><br/>Echec de la suppression.<hr> <br/><a href=index.php> Retour au site... </a></div>";
			}
		}
		else echo "<div class=post><img src=ressources/img/tiger.png class=mini /><br/> Accès refusé.<hr> <br/><a href=index.php> Retour au site... </a></div>";
	}
}

		
?>
