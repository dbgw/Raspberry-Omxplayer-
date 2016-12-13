<?php
$donnees=json_decode ($_POST['element'],true);
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

echo '	<span style="display:table-cell; width:'.$largeur.'px; height:'.$hauteur.'px; vertical-align:middle; text-align:center">
		<img id="affImage" src="img/'.$donnees["FICHIER"].'" style="height:'.$hauteur.'px; width:'.$largeur.'px; object-fit: contain;">
	</span>';

?>