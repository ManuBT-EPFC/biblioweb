<?php
session_start();

require 'utils/validators.php';

//Sécurité
if(empty($_SESSION['login'])) {
	header('Location: index.php', null, 302);
	exit;
}

require 'config.php';

$message = '';
$showPasswordFields = false;

$link = mysqli_connect(HOSTNAME,USERNAME,PASSWORD,DATABASE);

if($link) {
	$login = $_SESSION['login'];
	$query = "SELECT * FROM users WHERE login='$login'";
	
	$result = mysqli_query($link,$query);

	if($result) {
		$user = mysqli_fetch_assoc($result);
		
		mysqli_free_result($result²);
	}

	mysqli_close($link);
	
	//Formatage des données
	$user['login'] = strtoupper($user['login']);
	$user['created_at'] = substr($user['created_at'],0,10);
}

//Traitement des commandes
if(isset($_POST['btUpdate'])) {
	//die("mise à jour");
	//Afficher les champs
	$showPasswordFields = true;
	
	if(!empty($_POST['new_pass']) && !empty($_POST['conf_pass'])) {	//Modifier dans la base de données
		//die('mise à jour');
		if($_POST['new_pass'] == $_POST['conf_pass']) {
			if(validerPassword($_POST['new_pass'])) {
				//die('mise à jour');
				$link = mysqli_connect(HOSTNAME,USERNAME,PASSWORD,DATABASE);

				if($link) {
					$login = $_SESSION['login'];
					
					$pass = password_hash($_POST['new_pass'], PASSWORD_BCRYPT);

					$query = "UPDATE users SET password='$pass', updated_at=NOW() WHERE login='$login'";
				
					$result = mysqli_query($link,$query);

					if(mysqli_affected_rows($link)==1) {
						$message = "Mot de passe modifié";
						$alertClass = 'alert-success';
						
						$showPasswordFields = false;
					} else {
						$message = "Erreur lors de la modification!";
						$alertClass = 'alert-danger';
					}

					mysqli_close($link);
				}
			} else {
				$message = "Mot de passe invalide!";
				$alertClass = 'alert-danger';
			}		
		} else {
			$message = "Mots de passe différents!";
			$alertClass = 'alert-danger';
		}
	}
	
} elseif(isset($_POST['btDelete'])) {
	//die("suppression");
	$link = mysqli_connect(HOSTNAME,USERNAME,PASSWORD,DATABASE);

	if($link) {
		$login = $_SESSION['login'];
		$query = "DELETE FROM users WHERE login='$login'";
		
		$result = mysqli_query($link,$query);

		if(mysqli_affected_rows($link)==1) {
			$message = "Compte supprimé";
			$alertClass = 'alert-success';
			
			//Déconnexion
			session_destroy();
			
			//Redirection
			header('Location: index.php', null, 302);
			exit;
		} else {
			$message = "Erreur lors de la suppression!";
		}

		mysqli_close($link);
	}
}
/* MOCK
$user = [
	'nom' => 'ced',
	'statut' => 'admin',
	'dateInscr' => '22/09/1945',
	'email' => 'ceruth@epfc.eu',
];*/
?>
<?php include 'inc/header.inc.php'; ?>

<div class="alert <?= $alertClass ?? 'alert-primary' ?>" role="alert">
  <?= $message ?>
</div>

<div class="card" style="width: 18rem;">
 <!-- <img class="card-img-top" src="https://picsum.photos/150/150" alt=""> -->
  <img src="https://via.placeholder.com/150" class="card-img-top" alt="picture">
  <div class="card-body">
    <h5 class="card-title"><?= $user['login']; ?> <em>(<?= $user['statut']; ?>)</em></h5>
    <p class="card-text">
		<p><span>Email:</span> <?= $user['email']; ?></p>
		<p><span>Inscrit le:</span> <?= $user['created_at']; ?></p>
	</p>
    <form action="<?= $_SERVER['PHP_SELF']; ?>" method="post">
	
	<?php if($showPasswordFields) { ?>
		<label>Nouveau mot de passe</label>
		<input type="password" name="new_pass">
		<label>Confirmer mot de passe</label>
		<input type="password" name="conf_pass">
	<?php } ?>
	
		<button name="btUpdate" class="btn btn-info mb-2 mt-2">Changer mot de passe</button>
		<button name="btDelete" class="btn btn-danger" onclick="return confirm('Etes-vous sûr de vouloir supprimer votre compte?')">Supprimer le compte</button>
	</form>
  </div>
</div>

<?php include 'inc/footer.inc.php'; ?>