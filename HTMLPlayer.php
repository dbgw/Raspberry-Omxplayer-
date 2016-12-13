<?php
//$donnees=json_decode ($_POST['element'],true);
	if (file_exists('config/config_affichage.php'))
	{
		//si déjà configuré chargement des paramètres
		include('config/config_affichage.php');
	}
	if (ROTATION_AFFICHAGE!=0)
	{
		$largeur=HAUTEUR_AFFICHAGE;
		$hauteur=LARGEUR_AFFICHAGE;
	}
	else
	{
		$largeur=LARGEUR_AFFICHAGE;
		$hauteur=HAUTEUR_AFFICHAGE;
	}

echo '<iframe src="FICHIERS HTML/'.$_POST['url'].'" width="'.$largeur.'px" height="'.$hauteur.'px" frameborder="0"></iframe>';

?>