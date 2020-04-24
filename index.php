<?php
session_start();

require 'config.php';

$title = "Accueil - Biblioweb";
?>

<?php include "inc/header.inc.php" ?>
<div class="container-fluid">
  <header class="row">
		<div class="col-4">
			LOGO
		</div>
		<nav class="col-8">
			MENU: Accueil | 
		<?php if(isset($_SESSION['login'])) { ?>	
			<a href="profil.php">Profil</a> |
		<?php } ?>	
			Contact | Admin
		</nav>
  </header>
  <div class="row">
	<div class="col-3">
		<!-- Formulaire de connexion/déconnexion -->
		<?php if(!isset($_SESSION['login'])) { ?>
		<form id="frmLogin" action="<?= SITE_URL ?>/login.php" method="post">
			<div class="form-group">
				<label>Login</label>
				<input class="form-control" type="text" name="login" value="<?php 
				echo (isset($_POST['keepLogin']) ? $login : '');
				?>" required>
			</div>
			<div class="form-group">
				<label>Password</label> <input class="form-control" type="password" name="pwd" required>
			</div>
			<div class="form-group form-check">
				<input class="form-check-input" type="checkbox" name="keepLogin">
				<label class="form-check-label">Retenir mon login</label>
			</div>
			<button class="btn btn-primary" name="btLogin">Se connecter</button>
		</form>
		<p><a href="register.php">S'inscrire</a></p>
		<?php } else { ?>
		<form id="frmLogout" action="<?= SITE_URL ?>/login.php" method="post">
			<button name="btLogout">Se déconnecter</button>
		</form>
		<?php } ?>
	</div>
	<div class="col-9">
	COL2
	</div>
  </div>
  <footer class="row">
	<div class="col">
		&copy; EPFC - Biblioweb 2020
	</div>
  </footer>
</div>
<?php include "inc/footer.inc.php" ?>