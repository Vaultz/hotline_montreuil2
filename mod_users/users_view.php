<?php
if(session_id() == '') session_start();
if (!defined('CONST_INCLUDE')) die ('ACCES DIRECT INTERDIT');

class Users_View {
	
	function __construct() {
		
	}

	function viewConnection() {
		
	
		if (isset($_SESSION['login'])) {
			echo "<div class=post><img src=ressources/img/andy.png class=mini /><br/> Erreur : Vous êtes déjà connecté... <br/> <hr> <br/><a href=index.php> Retour au site... </a></div>";
		}
	
		else { 
			echo '
				<div class="post">
					<img src=ressources/img/pig.png class=mini /><br/>
					<h2> CONNEXION </h2>
					<form method = "post" action = "index.php?module=users&action=connection" id="formCo">
						LOGIN <input type ="text" name ="login"/></br><br/>
						MOT DE PASSE <input type ="password" name ="password"/><br/><br/>
						<input type ="submit" name="valider" class="sub" value="MAKE YOUR CALL"/>
					</form>
				<div>';
		}
	}
		

	function viewAddUser() {
		echo '	<div class="post">
					<img src=ressources/img/pig.png class=mini /><br/>
					<div class="cadre_post" >
						<h2> CREER UN COMPTE </h2>
						<form method = "post" action = "index.php?module=users&action=add"><hr>
							Prenom <input type ="text" name ="firstname" /></br></br>
							Nom <input type ="text" name ="lastname"/></br></br>
							Login <input type ="text" name ="login"/></br></br>
							Mot de passe <input type ="password" name ="password"/></br></br>
							<input type ="submit" name="valider" value="JOIN US" class="sub">
						</form>
					</div>
				</div>';	
	}

	function viewUpdateUser() {
		$_SESSION['idupdate'] = $_GET['id'];
		$firstname = htmlspecialchars($_GET['firstname']);
		$lastname = htmlspecialchars($_GET['lastname']);
		$login = htmlspecialchars($_GET['login']);
		$memberrank = htmlspecialchars($_GET['memberrank']);
	
		echo '	<div class="post"><h2> MODIFIER UN COMPTE </h2>
				<form method = "post" action = "index.php?module=users&action=update">
					Prenom <input type ="text" name ="firstname" value='.$firstname.'></br>
					Nom <input type ="text" name ="lastname" value='.$lastname.'></br>
					Login <input type ="text" name ="login" value='.$login.'></br>
					<input type ="submit" name="valider"/>
				</form></br>';
		
		if (isset($_SESSION['rank']) && ($_SESSION['rank'] == 2))  {
			echo '	<form method=post action=index.php?module=users&action=updateRank>
						Rang actuel : '.$memberrank.'</br> 
						Nouveaux droits : <input type="number" name="newrank" value="0" min="0" max="2">
						<input type="submit" name="valider"/>
					</form></div>
				';
			}
	}
	
	function viewUserList($users) {
		foreach($users as $var) {
			$id = $var['id'];
			$lastname = $var['lastname'];
			$firstname = $var['firstname'];
			$login = $var['login'];
			$rank = $var['rank'];
			//$password = $var['password'];
			
			
			echo ("<div class=post> N°$id. Login : $login
				| <a href = index.php?module=users&action=details&id=$id> Détails </a> 
				| <a href = index.php?module=users&action=form_updateUser&id=$id&firstname=$firstname&lastname=$lastname&login=$login&memberrank=$rank> Modifier </a> 
				| <a href = index.php?module=users&action=deleteUser&id=$id> Supprimer </a> </br> </div>");	
			
		}
	}
	
	function viewdetails($user) {


		echo ("<div id=info_user><img src=ressources/img/50blessings.jpg class=mini /> Login : $user->login</br>
			Prénom : $user->firstname</br> 
			Nom : $user->lastname </br>
			Nombre de posts validés : <b><a href=index.php?module=posts&action=userList&iduser=$user->id> $user->postnumber</a></b></br>
			");
		if ((isset($_SESSION['rank'])) && ($_SESSION['rank']!=0) && (($_SESSION['rank'] == 2) && ($user->rank == 2))) {
			echo "<a href=index.php?module=users&action=form_updateUser&id=$user->id&firstname=$user->firstname&lastname=$user->lastname&login=$user->login&memberrank=$user->rank>Modifier les données</a> | Changer de mot de passe </div>";
		}
		
	}
}
?>