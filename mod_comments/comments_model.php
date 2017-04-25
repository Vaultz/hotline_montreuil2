<?php
if (!defined('CONST_INCLUDE')) die('ACCES DIRECT INTERDIT');

class Comments_Model extends ParamsDB {
	private $connection;
	
	function __construct() {
		parent::__construct();
		$this->connection = new PDO($this->dns, $this->user, $this->password);
		$this->connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		
	}
	
	function getCommentsByPost() {
		$request = $this->connection->prepare("select * from hm2_comments where post_id = ?");
		$request->execute(array($_GET['idpost']));
		return array_reverse($request->fetchAll());
	}

	function getAuthorByComment($id) {
		$request = $this->connection->prepare("select id, login from hm2_users where id = ?");
		$request->execute(array($id));
		$result = $request->fetch(PDO::FETCH_OBJ);
		return $result;
	}
	
	function getComment($id) {
		$request = $this->connection->prepare("select * from hm2_comments where id = ?");
		$request->execute(array($id));
		$comment = $request->fetch(PDO::FETCH_OBJ);
		return $comment;
	}
	
	function addComment($text, $post_id) {
		$request = $this->connection->prepare("select * from hm2_users where login = ?");
		$request->execute(array($_SESSION['login']));
		$author = $request->fetch(PDO::FETCH_OBJ);
		$author_id = $author->id;
		$request = $this->connection->prepare("insert into hm2_comments (text, author_id, post_id) values(?, ?, ?)");	
		$request->execute(array($text, $author_id, $post_id));
	}
		
	function deleteComment($id) {
		$request = $this->connection->prepare("delete from hm2_comments where id = ?");
		$request->execute(array($id));
	}
	
	function nbComments() {
		$nbcomments = $this->connection->query("select * from hm2_comments");
		return $nbcomments->rowCount();
	}	
}
?>


