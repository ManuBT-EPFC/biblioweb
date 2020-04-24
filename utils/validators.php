<?php
function validerPassword($mdp) {
	//Règles de validation: longueur 8, 1 majuscules, 1 chiffre
	if(strlen($mdp)>=8 && preg_match('/.*[0-9]{1,}.*/',$mdp)
		&& preg_match('/.*[A-Z]{1,}.*/',$mdp)) {
		return true;
	}
	
	return false;
}
?>