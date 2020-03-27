<?php
//Récupérer ou démarrer une session
session_start();

require '../config.php';

if(!isset($_SESSION['login'])) {
	//Rediriger vers le formulaire de connexion
	header('Location: '.SITE_URL.'/login.php');
	header('Status: 302 Temporary');
	exit;
}
?>
<!DOCTYPE html>
<form id="frmLogout" action="<?= SITE_URL ?>/login.php" method="post">
	<button name="btLogout">Se déconnecter</button>
</form>
<h1>Administration</h1>