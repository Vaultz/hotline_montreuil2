<!-- Page d'administration uniquement accessible aux administrateurs (rank 2) -->

<?php
	if(session_id() == '') session_start(); 
	ini_set('display_errors', 1);
?>
<div class=post>
<h2>PANNEAU ADMIN</h2>
<?php
// Vérification des droits de l'utilisateur si connecté, pour afficher les panneaux correspondants
if ((isset($_SESSION['rank'])) && ($_SESSION['rank'])) {	// On vérifie si rank est bien initialisé et qu'il n'est pas nul : accès modo)
		echo "	<b>
					<a href=index.php?module=posts&action=list&visibility=0>Posts en attente</a></br>
				</b>";

	if ($_SESSION['rank'] == 2) {		// Si le rank est à 2 : accès admin
		echo "	<b>
					<a href=index.php?module=users&action=form_addUser>Créer un compte</a></br>
					<a href=index.php?module=users&action=list>Gestion des utilisateurs</a></br>
				</b>
			";
	}

}
else echo "<img src=ressources/img/tiger.png class=mini /><br/>Accès interdit.<hr> <br/><a href=index.php> Retour au site... </a>";			
				
?>
</div>