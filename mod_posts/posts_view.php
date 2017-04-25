<?php
if (!defined('CONST_INCLUDE')) die('ACCES DIRECT INTERDIT');

class Posts_View {
	
	function __construct() {}

	function viewAddPost() {
		echo '	
			<div class="post_left">
				<h2> POSTER SUR LA HOTLINE ! </h2>
				Vous êtes libre de poster ici tout gif accompagné d\'un commentaire.</body><br> Le but est que l\'ensemble ait un trait d\'humour, et soit lié au quotidien des étudiants de l\'IUT de Montreuil.</br><img src="ressources/img/beware.png" id="beware"/> La suppression de posts et de commentaires se fait à l\'entière discrétion des modérateurs.</br> Veuillez consulter la charte pour en savoir plus.
				<form enctype="multipart/form-data" method = "post" action = "index.php?module=posts&action=add">
					Ajoutez un petit texte <input type ="text" name ="text" placeholder="64 caractères max..."/></br>
					Image <input type ="file" name ="userfile"/></br>
					<input type ="submit" name="valider"/>
				</form>
			</div> ';
	}

	function viewUpdatePost() {	
		if ($_SESSION['rank'] > 0) {
			echo '	<div class="post">
					<h2> MODIFIER UN POST </h2>
						<form method="post" action="index.php?module=posts&action=update">
							<textarea name="text" rows="6" cols="40" placeholder="Entrez le nouveau texte ici..."></textarea></br>
							<input type="submit" name="valider"/>
						</form>
					</div>';
		}
		else echo '<div class="post"><img src="ressources/img/tiger.png" class="mini" /><br/> Accès refusé.<hr> <br/><a href="index.php"> Retour au site... </a></div>';
	
	}

	function viewPost($post, $poster, $score) {
		$displayer = '	<div class="post"></br>
							<div id="cadre_img_post">
								<img src="'.$post['image'].'" alt="'.$post['text'].'" width="400" id="img_post"><br/>
							</div>
							'.$post['text'].'<br/> 
							<hr>
							Score : '.$score.'</br></br>
							<i>'.$post['date'].'<br/>
							Posté par <a href="index.php?module=users&action=details&id='.$post['author_id'].'">'.$poster.'</i></a><br/>
		';

		if (isset($_SESSION['rank'])) {
			$displayer .= '	<form method=post action=index.php?module=comments&action=viewComments&idpost='.$post['id'].'>
								<input type=submit value=Commentaires class=sub>
							</form>
			'; 
			if (($_SESSION['rank']) >= 1) {
				$_SESSION['idupdate'] = $post['id'];
				$displayer .= '	<form method="post" action="index.php?module=posts&action=form_updatePost">
									<input type="submit" value="Modifier" class="sub">
								</form>
								<form method="post" action="index.php?module=posts&action=deletePost&id='.$post['id'].'">
									<input type="submit" value="Supprimer" class="sub">
								</form>
				';
				if (!$post['visibility']) {
					$_SESSION['post'] = $post;
					$displayer .= '	<form method="post" action="index.php?module=posts&action=setVisible">	
										<input type="submit" name="valider" value="Activer" class="sub">
									</form>
					';
				}
			}
			$displayer .= '	<div style="float:right; margin-top: -120px;">
								<form method="post" action="index.php?module=posts&action=vote">
									<input type="hidden" value="'.$post['id'].'" name="idpost">
									<input type="submit" class="btn btn-info btn-md" role="button" value="+1" name="vote">
								</form>

								<form method="post" action="index.php?module=posts&action=vote">
									<input type="hidden" value="'.$post['id'].'" name="idpost">	
									<input type="submit" class="btn btn-warning btn-md" role="button" value="-1" name="vote">
								</form>
							</div>
			';
		}
		echo $displayer .= '</div>';
	}
		
	
	function viewdetails($post) {
		echo "N°$post->id. Text : $post->text";
		?><img src="$image" alt="$post->text"><?php
	}

	function sortPostsByRankButton() {
		echo '	 <div class="btn-group col-xs-offset-4 col-md-offset-5 col-lg-offset-5" role="group" aria-label="...">
  					<button type="button" class="btn btn-primary">Par date</button>  
  					<a href = "index.php?module=posts&action=sortByVote"><button type="button" class="btn btn-success">Par popularité</button></a>
  				</div>
		';
	}
}


?>
