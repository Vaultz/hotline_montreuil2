<?php
if (!defined('CONST_INCLUDE')) die('ACCES DIRECT INTERDIT');
class Comments_View {
	
	function __construct() {		
	}
	
	function viewComment($comment, $post, $author) {
		$text = $comment['text'];
		$date = $comment['date'];
		$_SESSION['post'] = $post;
			
		echo "<div class=post>$text";

		if ($_SESSION['rank'] > 0)	{
			echo "	<form method=post action=index.php?module=comments&action=form_updateComment&id=$comment[id]&text=$comment[text]>
						<input type=submit value=Modifier class=sub>
					</form>
					<form method=post action=index.php?module=comments&action=delete&id=$comment[id]>
						<input type=submit value=Supprimer class=sub>
					</form>
			";
		}

		echo '</br><i>Posté par <a href=index.php?module=users&action=details&id='.$author->id.'>'.$author->login.'</a> le '.$date.'</i></div>';		
		//<a href=index.php?module=users&action=details&id=$post[author_id]>$poster</i></a><br/>	
	}

	function viewAddComment() {
		$idpost = $_GET['idpost'];
		echo "	<div class=post><h2> AJOUTER UN COMMENTAIRE </h2>
					<form method =post action=index.php?module=comments&action=add&idpost=$idpost>
						<input type=textarea name=text /></br>
						<input type=submit name=valider/>
					</form>
				</div>
		";
	}
}

?>
