<?php 

if(session_id() == '') session_start();

if (!defined('CONST_INCLUDE')) die('ACCES DIRECT INTERDIT');

include_once('comments_model.php');
include_once('comments_controller.php');
include_once('comments_view.php');
			
class Comments_Index  {
	private $model;
	private $controller;
	private $view;
				
	function __construct() {
	
		$this->model = new Comments_Model();
		$this->controller = new Comments_Controller();
		$this->view = new Comments_View();
		
	}
				
	function actionComments() {
	
		if (isset($_GET['action'])) {
					
			switch($_GET['action']) {
				
				case "list" :
				$this->model->getCommentList();
				break;
				
				case "details" :
				$this->controller->commentDetails($_GET);
				break;
				
				case "form_updateComment" :
				//include('form_updateComment.php');
				break;
				
				case "update" :
				//$this->model->updateComment($_GET['id'], $_POST['firstname'], $_POST['lastname'], $_POST['login'], $_POST['password']);	
				break;
				
				case "viewComments" :
				$this->controller->commentList($_GET['idpost']);
				break;
						
				case "add" : 
				$this->controller->op_addComment($_POST['text'], $_GET['idpost']);
				break;
					
				case "delete" :
				$this->controller->op_deleteComment($_GET['id']);
				break;			
							
			}
				
		}
	} 
}

?>