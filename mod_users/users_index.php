<?php 
if(session_id() == '') session_start(); 

if (!defined('CONST_INCLUDE')) die('ACCES DIRECT INTERDIT');

include_once('users_model.php');
include_once('users_controller.php');
include_once('users_view.php');

			
class Users_Index  {
	private $model;
	private $controller;
	private $view;
				
	function __construct() {
	
		$this->model = new Users_Model();
		$this->controller = new Users_Controller();
		$this->view = new Users_View();
		
	}
				
	function actionUsers() {
	
		if (isset($_GET['action'])) {
			
			switch($_GET['action']) {
				
				
				case "list" :
				$this->controller->userList();
				break;
				
				case "details" :
				$this->controller->userDetails($_GET);
				break;
				
				case "form_updateUser" :
				$this->controller->updateUser();
				break;
				
				case "update" :
				$this->controller->op_updateUser();
				break;

				case "updateRank":
				$this->controller->updateRank($_POST['newrank']);
				break;
				
				case "form_addUser" :
				$this->controller->addUser();
				break;
						
				case "add" : 
				$this->controller->op_addUser($_POST);
				break;
					
				case "deleteUser" :
				$this->controller->op_deleteUser($_GET['id']);
				break;	

				case "tryConnection":
				$this->controller->tryConnection();
				break;

				case "connection":
				$this->controller->connection($_POST);				
				break;
				
				case "disconnection":
				$this->controller->disconnection();		
				break;				
				
			}
		}
	} 
}

?>