<?php

if (!defined('CONST_INCLUDE')) die('ACCES DIRECT INTERDIT');

class Users_Model extends ParamsDB {
	private $connection;
	
	function __construct() {
		parent::__construct();
		$this->connection = new PDO($this->dns, $this->user, $this->password);
		$this->connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		
	}
	
	function getUserList() {
		$request = $this->connection->query("select * from hm2_users");
		$result = $request->fetchAll();
		return $result;

	}
	
	function getUser($id) {
		$request = $this->connection->prepare("select * from hm2_users where id = ?");
		$request->execute(array($id));
		return $request->fetch(PDO::FETCH_OBJ);
	}
	
	function getUserByLogin($login) {
		$request = $this->connection->prepare("select * from hm2_users where login = ?");
		$request->execute(array($login));
		return $request->fetch(PDO::FETCH_OBJ);

	}
	
	function user_exists($login, $password) {
		$request = $this->connection->prepare("select * from hm2_users where login = ? and password = ?");
		$request->execute(array($login, $password));
		return $result = $request->fetch(PDO::FETCH_OBJ);		
	}
	
	function addUser() {
		$lastname = htmlspecialchars($_POST['lastname']);
		$firstname = htmlspecialchars($_POST['firstname']);
		$login = htmlspecialchars($_POST['login']);
		$password = htmlspecialchars($_POST['password']);
	
		$hashedpassword = hash('sha256', $password);		
		$request = $this->connection->prepare('insert into hm2_users (id, firstname, lastname, login, password) values(default, ?, ?, ?, ?)');		
		$request->execute(array($firstname, $lastname, $login, $hashedpassword));
		
	}
		
	function deleteUser($id) {
		$request = $this->connection->prepare("delete from hm2_users where id = ?");
		$request->execute(array($id));
	}
	
	function updateUser($id, $firstname, $lastname, $login) {
		$request = $this->connection->prepare("update hm2_users set firstname = ?, lastname = ?, login = ? where id = ?");
		if($request->execute(array($firstname, $lastname, $login, $id))) echo "Données modifiées avec succès."; 
		else echo "Echec de la requête.";
	}

	function updateRank($rank) {
		$request = $this->connection->prepare("update hm2_users set rank = ? where id = ?");
		if($request->execute(array($rank, $_SESSION['idupdate']))) echo "Rank modifié avec succès. </br> <a href=index.php?module=users&action=list>Retour à la liste</a>";
		else echo "Echec de la requête.";

	}
	
	function nbUsers() {
		$nbusers = $this->connection->query("select * from hm2_users");
		return $nbusers->rowCount();
	}
}

?>


