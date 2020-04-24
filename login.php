<?php
session_start();

require('config.php');

$message = '';

if(isset($_POST['btLogin'])) {
	if(!empty($_POST['login']) && !empty($_POST['pwd'])) {
		//Connexion à la DB
		$mysql = mysqli_connect(HOSTNAME, USERNAME, PASSWORD, DATABASE);
		
		$login = mysqli_real_escape_string($mysql,$_POST['login']);
		$pwd = $_POST['pwd'];
		
		$query = "SELECT login, password, statut FROM `users` WHERE login='$login'";
		
		$result = mysqli_query($mysql, $query);
		
		if($result) {
			$user = mysqli_fetch_assoc($result);
			
			mysqli_free_result($result);
		}
		
		mysqli_close($mysql);

		if(password_verify($pwd, $user['password'])) {			
			//Authentification
			$_SESSION['login'] = $login;
			
			//Rediriger vers l'administration
			if($user['statut']=='admin') {
				header('Location: '.SITE_URL.'/admin/index.php');
			} else {
				header('Location: '.SITE_URL.'/index.php');
			}
			
			header('Status: 302 Temporary');
			exit;
		} else {
			$message = 'Login/mot de passe incorrect!';
		}
		
	} else {
		$message = 'Veuillez remplir tous les champs!';
	}
} elseif(isset($_POST['btLogout'])) {	//Déconnexion
	unset($_SESSION['login']);
	session_destroy();
	
	header('Location: '.SITE_URL.'/index.php');
	header('Status: 302 Temporary');
	exit;
} else {	//Je viens d'arriver
	$message = 'Bienvenue';
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
<meta charset="utf-8">
<title>Connexion</title>
</head>
<body>
<nav>
	<ul>
		<li><a href="<?= SITE_URL ?>/admin/index.php">Accueil</a></li>
		<li><a href="<?= SITE_URL ?>/admin/index.php">Administration</a></li>
	</ul>
</nav>
<div><?= $message; ?></div>
<?php if(!isset($_SESSION['login'])) { ?>
<form id="frmLogin" action="<?= SITE_URL ?>/login.php" method="post">
	<div>
		<label>Login</label>
		<input type="text" name="login" value="<?php 
		echo (isset($_POST['keepLogin']) ? $login : '');
		?>" required>
	</div>
	<div>
		<label>Password</label> <input type="password" name="pwd" required>
	</div>
	<div>
		<input type="checkbox" name="keepLogin">
		<label>Retenir mon login</label>
	</div>
	<button name="btLogin">Se connecter</button>
</form>
<?php } else { ?>
<form id="frmLogout" action="<?= SITE_URL ?>/login.php" method="post">
	<button name="btLogout">Se déconnecter</button>
</form>
<?php } ?>
</body>
</html>