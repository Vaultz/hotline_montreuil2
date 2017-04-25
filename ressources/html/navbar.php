<!-- Nav qui contient la plupart des fonctionnalités du site liées à la gestion d'un compte -->

<nav class="navbar navbar-default" id="navbar1">
	<div class="container-fluid">
    	<!-- Brand and toggle get grouped for better mobile display -->
    	<div class="navbar-header">
      <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1" aria-expanded="false">
        	<span class="sr-only">Toggle navigation</span>
        	<span class="icon-bar"></span>
        	<span class="icon-bar"></span>
        	<span class="icon-bar"></span>
      </button>
      
      <a class="navbar-brand" href="index.php"><img src="ressources/img/hotline_montreuil2.png" id="logo"/></a>
    
    	</div>

    	<!-- Collect the nav links, forms, and other content for toggling -->
    	<div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
      	<ul class="nav navbar-nav">
      	</ul>

      
      <ul class="nav navbar-nav navbar-right">
		<?php 
		
			// Si l'utilisateur n'est pas connecté, on lui propose de se connecter ou de s'inscrire.
			if (!isset($_SESSION['login'])) {
				?><li><a href="index.php?module=users&action=tryConnection">Connexion</a></li>
				<li><a href = "index.php?module=users&action=form_addUser">Inscription</a></li><?php
			}
			
			// S'il est connecté, on lui propose de poster, d'accéder à son profil et de se déconnecter.
				// S'il en possède les droit, on lui propose également d'accéder au panneau admin
			else {
				
				if ($_SESSION['rank']) echo "<li><a href=index.php?page=admin>Admin</a></li>"; ?>
				<li><a href="index.php?module=posts&action=form_addPost">Poster</a></li>
				<li><a href="index.php?module=users&action=details">Profil</a></li>	
				<li><a href="index.php?module=users&action=disconnection">Deconnexion</a></li>	
				<?php
			}
			
		?>
		
			<li><a href="index.php?page=faq">F.A.Q.</a></li>			
      </ul>
    	</div><!-- /.navbar-collapse -->
  	</div><!-- /.container-fluid -->
</nav>
