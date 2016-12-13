<?php
//Fonctions php
	function downloadDir($dir,$conn)
	{
		set_time_limit(30) ;
		ftp_chdir($conn,$dir);
		$liste = ftp_rawlist($conn,'.');
			
			if(!is_dir(substr(ftp_pwd($conn),1)))
			{
				mkdir(substr(ftp_pwd($conn),1),0777);
			}
			foreach($liste as $var)
			{
				$val = explode(" ",$var);
				
				if(substr($var[0],0,1)=='d')
				{
					downloadDir($val[count($val)-1],$conn);
				
				}
				else
				{
					$remoteFile = $val[count($val)-1];
					$localeFile =  substr(ftp_pwd($conn),1)."/".$val[count($val)-1];
					ftp_get($conn,$localeFile,$remoteFile, FTP_BINARY);
				}
			}
		ftp_chdir($conn,'..');
	}
	

	function rrmdir($src) {
		$dir = opendir($src);
		while(false !== ( $file = readdir($dir)) ) {
			if (( $file != '.' ) && ( $file != '..' )) {
				$full = $src . '/' . $file;
				if ( is_dir($full) ) {
					rrmdir($full);
				}
				else {
					@unlink($full);
				}
			}
		}
		closedir($dir);
		rmdir($src);
	}

// check configuration

	//verification de l'affichage
	if (file_exists('config/config_affichage.php'))
	{
		//si déjà configuré chargement des paramètres
		include('config/config_affichage.php');
	}
	else
	{
		//si non renvoi vers la page de configuration
		file_put_contents('config/config_affichage.php',"
		<?php
        define('HAUTEUR_ECRAN',540); //1080
        define('LARGEUR_ECRAN',960);//1920
        define('HAUTEUR_AFFICHAGE',530);//1060
        define('LARGEUR_AFFICHAGE',950);//1900
        define('ROTATION_AFFICHAGE',0);
        define('PLAYER_ID','play_578397bcad69b');
        ?>");
		include('config/config_affichage.php');
		echo 'Fichier affichage creer avec les valeurs par défaut.<br>';	
	}

	//vérification des paramètres réseau
	if(file_exists('config/config_reseau.php'))
	{
		//si le fichier de configuration de réseau existe le charger
		include('config/config_reseau.php');
	}
	else
	{
		//si non renvoi vers la page de configuration réseau
		echo 'Le fichier réseau existe pas.<br>';
		echo getHostByName(getHostName()).'<br>';
		echo getHostName().'<br>';
		//echo utf8_encode(shell_exec('ipconfig')).'<br>';
		
	}

	//vérification des paramètres serveurs
	if(file_exists('config/config_serveur.php'))
	{
		include('config/config_serveur.php');
	}
	else
	{
		echo 'Le fichier serveur n\'existe pas<br>';
	}

//recuperation des elements FTP1
	$conn_FTP1 = ftp_connect(ADRESSE_FTP1);
	
	if($conn_FTP1!=FALSE)
	{
		if(@ftp_login($conn_FTP1,LOGIN_FTP1,PASS_FTP1))
		{
			echo 'connecté<br>';
			ftp_pasv($conn_FTP1, true);

				if(is_dir('FICHIERS HTML'))
				{
					rrmdir('FICHIERS HTML');
				}
			
			downloadDir('FICHIERS HTML',$conn_FTP1);
			$buff = ftp_nlist($conn_FTP1,"BOUCLES");
			$dateBoucle=date('dmY');
			foreach ($buff as $nomBoucle)
			{
				if(strrpos($nomBoucle,'BOUCLE_'.NOM_PLAYER.'_'.$dateBoucle)!==false)
				{
					ftp_get($conn_FTP1,'BOUCLES/'.$nomBoucle,'BOUCLES/'.$nomBoucle, FTP_BINARY);
					$boucleTelechargee=$nomBoucle;
					$listeBoucles=scandir('BOUCLES');
						foreach ($listeBoucles as $nomFile)
						{
							if($nomFile!=$nomBoucle && $nomFile!='.' && $nomFile!='..')
							{
								unlink('BOUCLES/'.$nomFile);
							}
						}
				}
			}
			
			ftp_get($conn_FTP1,'LISTE_FICHIERS.XML','LISTE_FICHIERS.XML',FTP_BINARY);
			ftp_get($conn_FTP1,'films_a_recuperer.xml','films_a_recuperer.xml',FTP_BINARY);
		}
		else
		{
			echo 'login pass refusé';
		}
	}
	else
	{
		echo 'echec de la connexion';
	}
	
	ftp_close($conn_FTP1);
	
	header('location: DownloadAjax.php');

?>
