<?php
session_start();

$message = '';

require 'config.php';
require 'utils/validators.php';

$title = "Biblioweb - Inscription";

$extra_css = '<link rel="stylesheet" href="register_style.css">';
$extra_js = '<script src="register.js"></script>';

if(isset($_POST['btSignin'])) {
	if(!empty($_POST['login']) 
		&& !empty($_POST['pwd']) 
		&& !empty($_POST['confPwd']) 
		&& !empty($_POST['email'])) {
		//Récupérer les données envoyées
		$login = $_POST['login'];
		$pwd = $_POST['pwd'];
		$confPwd = $_POST['confPwd'];
		$email = $_POST['email'];
		
		//Validation des données
		if(validerPassword($pwd) 
			&& filter_var($email,FILTER_VALIDATE_EMAIL)) {
			if($pwd===$confPwd) {
				//Inscrire le membre
				//Se connecter à la db
				$mysql = mysqli_connect(HOSTNAME, USERNAME, PASSWORD, DATABASE);
				
				$login = mysqli_real_escape_string($mysql,$login);
				$email = mysqli_real_escape_string($mysql,$email);
				$pwd = password_hash($_POST['pwd'],PASSWORD_BCRYPT);
				
				$query = "INSERT INTO `users` (`id`, `login`, `created_at`, `statut`, `password`,`email`) VALUES (NULL, '$login', NOW(), 'novice', '$pwd', '$email');";
				
				$result = mysqli_query($mysql, $query);
				
				if($result && mysqli_affected_rows($mysql)==1) {
					//Rediriger vers le formulaire de connexion
				/*	header('Location: login.php');
					header('Status: 301 Temporary');
					exit;
				*/	
					
					//Authentifier le nouveau membre
					$_SESSION['login'] = $login;
					
					//Rediriger vers le formulaire de connexion
					header('Location: '.SITE_URL.'/index.php');
					header('Status: 302 Temporary');
					exit;
				} else {
					$message = 'Erreur lors de l\'inscription!';
				}
				
				mysqli_close($mysql);
			} else {
				$message = 'La confirmation du mot de passe ne correspond pas!';
			}
		} else {
			$message = 'Votre mot de passe n\'est pas valide (lg 8, chiffre, majuscules)!';
		}
	} else {
		$message = 'Veuillez remplir tous les champs obligatoires!';
	}
}
?>
<?php include "inc/header.inc.php" ?>
<div><?= $message; ?></div>
<form action="<?= $_SERVER['PHP_SELF'] ?>" method="post">
	<fieldset><legend>Inscription</legend>
		<div>
			<label>Login</label>
			<input type="text" name="login" value="" required>
		</div>
		<div>
			<label>Email</label>
			<input type="email" name="email" value="" required>
		</div>
		<div>
			<label>Password</label>
			<input type="password" name="pwd" required>
		</div>
		<div>
			<label>Confirm Password</label>
			<input type="password" name="confPwd" required>
		</div>
		<button name="btSignin">S'inscrire</button>
	</fieldset>
</form>
<?php include "inc/footer.inc.php" ?>