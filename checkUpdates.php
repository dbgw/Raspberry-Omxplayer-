<?php
	//vérification des paramètres réseau
	if(file_exists('config/config_reseau.php'))
	{
		//si le fichier de configuration de réseau existe le charger
		include('config/config_reseau.php');
	}

	//vérification des paramètres serveurs
	if(file_exists('config/config_serveur.php'))
	{
		include('config/config_serveur.php');
	}

	$urlDist='MAJ '.NOM_PLAYER;
	$url='ftp://'.LOGIN_FTP1.':'.PASS_FTP1.'@'.ADRESSE_FTP1.'/'.$urlDist;
	
	if(file_exists($url))
	{
		$conn=ftp_connect(ADRESSE_FTP1);
		$login_result=ftp_login($conn,LOGIN_FTP1,PASS_FTP1);
		if(ftp_delete($conn,$urlDist))
		{
			echo 'oui';
		}
		else
		{
			echo 'non';
		}
		
		ftp_close($conn);
	}
	else
	{
		echo 'non';
	}

?>