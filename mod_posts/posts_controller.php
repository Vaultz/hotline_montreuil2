<?php
if (!defined('CONST_INCLUDE')) die('ACCES DIRECT INTERDIT');
ini_set("display_errors", true);
include_once('posts_model.php');
include_once('posts_view.php');
	
	
class Posts_Controller {
	private $model;
	private $view;
	
	function __construct() {
		$this->model = new Posts_Model();
		$this->view = new Posts_View();
	}
	
	function postList() {
		$this->view->sortPostsByRankButton();
		if (isset($_GET['visibility'])) {
			$visibility = $_GET['visibility'];
		}
		else $visibility = 1;

		$list = $this->model->getPostList($visibility);
		foreach ($list as $post) {
			$poster = $this->model->getAuthorByPost($post['author_id']);
			$this->setViewPost($post, $poster);
		}	
	}

	function sortedPostList($mode) {
		$this->view->sortPostsByRankButton();
		$list = $this->model->getSortedPostList($mode);
		foreach ($list as $post) {
			$poster = $this->model->getAuthorByPost($post['author_id']);
			$this->setViewPost($post, $poster);
		}
	}
		
	function postDetails() {
		$post = $this->model->getPost($_GET['id']);
		$this->view->viewDetails($post);
	}	

	function userPosts() {
		$poster = $this->model->getAuthorByPost($_GET['iduser']);
		$postList = $this->model->getPostListByAuthor($_GET['iduser']);
		foreach ($postList as $post) {
			$this->setViewPost($post, $poster);
		}
	}

	function setViewPost($post, $poster) {
		$score = $this->model->getPostScore($post);
		$this->view->viewPost($post, $poster, $score);
	}

	function getViewUpdatePost() {		
		$this->view->viewUpdatePost();

	}

	function getViewAddPost() {
		if (isset($_SESSION['login'])) {
			$this->view->viewAddPost();
		}
		else echo "<div class=post><img src=ressources/img/earl.png class=mini /><br/> Vous devez être connecté pour pouvoir poster.<hr> <br/><a href=index.php> Retour au site... </a></div>";
				
	}

	function addPost() {
		$text = htmlspecialchars($_POST['text']);
		$image = "mod_posts/images/".$_FILES['userfile']['name'];
		if(!$this->model->check_post($text, $text, $_FILES['userfile'])) {} 
		else $this->model->addPost($text, $image, $_FILES['userfile']);
		unset($_FILES);
	}
			
	function op_deletePost($idpost) {
		if (isset($_SESSION['rank']) && ($_SESSION['rank'] > 0)) {
			$nbPosts = $this->model->nbPosts();
			
			$this->model->deletePost($idpost);
			
			if ($nbPosts > $this->model->nbPosts()) {
				echo "<div class=post><img src=ressources/img/biker.png class=mini /><br/>Le post a bien été supprimé.<hr> <br/><a href=index.php> Retour au site... </a></div>";
			}
			else {
				echo "<div class=post><img src=ressources/img/tiger.png class=mini /><br/>Echec de la suppression.<hr> <br/><a href=index.php> Retour au site... </a></div>";
			}
		}
		else echo "<div class=post><img src=ressources/img/tiger.png class=mini /><br/> Accès refusé.<hr> <br/><a href=index.php> Retour au site... </a></div>";
	}

	function updatePost() {
		$this->model->updatePost($_POST['text']);	
	}

	function setVisible() {
		$this->model->setVisible($_SESSION['post']);
		unset($_SESSION['visibility']);
	}

	function vote() {
		if (isset($_SESSION['login'])) {
			$this->model->applyVote($_POST);
		}
		else echo "Vous devez être connecté pour pouvoir voter.";
	}
}	
?>
