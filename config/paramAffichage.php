<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Document sans titre</title>
</head>
<body>
<?php

if(isset($_POST['enr']))
{

echo 'test';
}
	if (file_exists('config_affichage.php'))
	{
		//si déjà configuré chargement des paramètres
		include('config_affichage.php');
	}
echo HAUTEUR_ECRAN ;
?>
<form id="form1" name="form1" method="post" action="">
  <label>
  Hauteur Ecran
  <input type="text" name="hauteurEcran" id="hauteurEcran" value="<?php echo HAUTEUR_ECRAN ; ?>" />
  <br />
  Largeur Ecran
  <input type="text" name="largeurEcran" id="largeurEcran" />
  <br />
  Hauteur Affichage
  <input type="text" name="hauteurAffichage" id="hauteurAffichage" />
  <br />
  Largeur Affichage
  <input type="text" name="largeurAffichage" id="largeurAffichage" />
  <br />
  Rotation Affichage
  <input type="text" name="rotationAffichage" id="rotationAffichage" />
  <br />
  <input type="submit" name="enr" id="enr" value="Enregistrer" />
  </label>
</form>
</body>
</html>
