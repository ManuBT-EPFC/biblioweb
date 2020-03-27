<?php
session_start();

$message = '';

require 'config.php';

function validerPassword($mdp) {
	//Règles de validation: longueur 8, 1 majuscules, 1 chiffre
	if(strlen($mdp)>=8 && preg_match('/.*[0-9]{1,}.*/',$mdp)
		&& preg_match('/.*[A-Z]{1,}.*/',$mdp)) {
		return true;
	}
	
	return false;
}

if(isset($_POST['btSignin'])) {
	if(!empty($_POST['login']) && !empty($_POST['pwd']) && !empty($_POST['confPwd'])) {
		//Récupérer les données envoyées
		$login = $_POST['login'];
		$pwd = $_POST['pwd'];
		$confPwd = $_POST['confPwd'];
		
		//Validation des données
		if(validerPassword($pwd)) {
			if($pwd===$confPwd) {
				//Inscrire le membre
				//Se connecter à la db
				$mysql = mysqli_connect(HOSTNAME, USERNAME, PASSWORD, DATABASE);
				
				$login = mysqli_real_escape_string($mysql,$login);
				$pwd = password_hash($_POST['pwd'],PASSWORD_BCRYPT);
				
				$query = "INSERT INTO `membres` (`id`, `nom`, `dateInscr`, `statut`, `password`) VALUES (NULL, '$login', NOW(), 'novice', '$pwd');";
				
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
<!DOCTYPE html>
<html>
<div><?= $message; ?></div>
<form action="<?= $_SERVER['PHP_SELF'] ?>" method="post">
	<fieldset><legend>Inscription</legend>
		<div>
			<label>Login</label>
			<input type="text" name="login" value="" required>
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
</html>