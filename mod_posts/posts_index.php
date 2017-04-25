<?php 
if(session_id() == '') session_start();

if (!defined('CONST_INCLUDE')) die('ACCES DIRECT INTERDIT'); 

ini_set("display_errors", true);
include_once('posts_controller.php');


class Posts_Index  {
	private $controller;
				
	function __construct() {
		$this->controller = new Posts_Controller();
	}
				
	function actionPosts() {
		
		if (isset($_GET['action'])) {
			switch($_GET['action']) {
			
				case "list" :
				$this->controller->postList();
				break;

				case "sortByVote":
				$this->controller->sortedPostList("sortedByVote");
				break;

				case "userList":
				$this->controller->userPosts($_GET['iduser']);
				break;

				case "setVisible":
				$this->controller->setVisible();
				break;
				
				case "details" :
				$ths->controller->postDetails($_GET['id']);
				break;
				
				case "form_updatePost" :
				$this->controller->getViewUpdatePost();
				break;
				
				case "update" :
				$this->controller->updatePost($_POST['text']);
				break;
				
				case "form_addPost" :
				$this->controller->getViewAddPost();
				break;
						
						
				case "add" : 
				$this->controller->addPost($_POST['text']);
				break;
					
				case "deletePost" :
				$this->controller->op_deletePost($_GET['id']);
				break;

				case "vote":
				$this->controller->vote($_POST);
				break;
			}

		}
		
		else $this->controller->postList(1);
		
	} 
}

?>
