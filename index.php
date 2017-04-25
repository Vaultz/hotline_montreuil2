<?php
/* Ce "super index" est la page sur laquelle naviguent les utilisateurs. 
	Son rôle est d'initialiser et d'appeler les indexes des différents modules en fonction des actions des utilisateurs. 
	Il peut donc appeler : 
		- l'affichage des posts (par défaut), 	-> DONE
		- le commentaires de posts (si l'user est connecté)
		- la création de post (si l'user est connecté),
		- l'inscription (si l'user n'est pas connecté),
		- la connexion (si l'user n'est pas connecté),
		- la fermeture de session (si l'user est connecté),
		- la modification/suppression de posts (si l'user est connecté en tant qu'admin),
		- la modification/suppression d'un user de son propre compte (s'il est connecé),
		- la modification/suppression de tous les comptes (si l'user est connecté en tant qu'admin)
 */

if(session_id() == '') session_start();
define('CONST_INCLUDE', NULL);
ini_set("display_errors", true);

include_once('params_connexion.php');
include_once('mod_posts/posts_index.php');
include_once('mod_users/users_index.php');
include_once('mod_comments/comments_index.php');
include_once('ressources/html/template.php');
			
class Index  {
	private $postsIndex;
	private $usersIndex;
	private $commentsIndex;
				
	function __construct() {
		$this->postsIndex = new Posts_Index();
		$this->usersIndex = new Users_Index();
		$this->commentsIndex = new Comments_Index();
		$this->indexAction();
	}
				
	/* Le template.php envoie un module et une action en fonction de la fonctionnalité sélectionnée */
	function indexAction() {
		if (isset($_GET['module'])) {
			
			switch($_GET['module']) {
				case "posts":
				$this->postsIndex->actionPosts($_GET['action']);
				break;
				
				case "users":
				$this->usersIndex->actionUsers($_GET['action']);
				break;
				
				case "comments":
				$this->commentsIndex->actionComments($_GET['action']);
				break;
				
			}
				
		}
		else if (isset($_GET['page'])) {
			switch($_GET['page']) {
				// Si l'on fait appel au module admin et que l'on possède des droits : affichage du panneau admin
				// TODO : trouver une solution plus élégante
				case "admin":
				if ((isset($_SESSION['rank'])) && ($_SESSION['rank'] > 0)) {
					include_once('ressources/html/admin.php');			
				}
				else echo "<div class=post><img src=ressources/img/tiger.png class=mini /><br/>Index : Accès réfusé.<hr> <br/><a href=index.php> Retour au site... </a></div>";
				break;
			}
		}
		
		else $this->postsIndex->actionPosts();
		
	}
	
}
new Index();
?>