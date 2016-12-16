<?php
$donnees=json_decode ($_POST['element'],true);

	if (file_exists('config/config_affichage.php'))
	{
		//si d�j� configur� chargement des param�tres
		include('config/config_affichage.php');
	}
	if (ROTATION_AFFICHAGE!=0)
	{
		$largeur=HAUTEUR_AFFICHAGE;
		$hauteur=LARGEUR_AFFICHAGE;
		$ratio=HAUTEUR_AFFICHAGE/LARGEUR_AFFICHAGE;
	}
	else
	{
		$largeur=LARGEUR_AFFICHAGE;
		$hauteur=HAUTEUR_AFFICHAGE;
		$ratio=1;
	}
$taillePolice=round($ratio*60);

if(file_exists('logo/logo.png'))
	{
		echo '<div style="display:block; z-index:2; width:150px; position:absolute; top:10px; left:10px; text-align=center;"><img src="logo/logo.png" style="width:150px"></div>';
	}


if(isset($donnees["BANDEAU_HAUT"]))
{
	echo '<div id="bandeau_haut" style="z-index:1; width:'.$largeur.'px; position:absolute; top:0px; left:0px; height:150px; background-color:rgba(0,0,0,0.5); vertical-align:top" >
<p id="titre" style="width:100%; font-size:'.$taillePolice.'px; color:#FFFFFF; text-align:center; font-weight:bolder; font-family:Verdana, Arial, Helvetica, sans-serif; b">'.$donnees["TEXTE_HAUT"].'</p>
</div>';
}

echo '<video id="videoPlayer" src="VIDEOS/'.$donnees["FICHIER"].'" autoplay="true"  style="display:block; z-index:0; width:'.$largeur.'px; height:'.$hauteur.'px; vertical-align:middle;">
</video>';

/*
echo '<video id="videoPlayer" autoplay="true"  style="display:block; z-index:0; width:'.$largeur.'px; height:'.$hauteur.'px; vertical-align:middle;">
</video>';
*/
        require_once 'juggler/omxplayer-web-controls-php/cfg.php';
        $file = "VIDEOS/".$donnees['FICHIER'];
    	exec('pgrep omxplayer', $pids);  //omxplayer	
		//if ( empty($pids) ) {
		  @unlink (FIFO);
		  posix_mkfifo(FIFO, 0777);
		  chmod(FIFO, 0777);
		  //$cmd = getcwd().'/omx_php.sh '.escapeshellarg($file)." "."'754 588 1024 748'";
		   $cmd = "juggler/omxplayer-web-controls-php/omx_php.sh ".escapeshellarg($file)." "."'".((HAUTEUR_ECRAN - HAUTEUR_AFFICHAGE )/2)." ".((LARGEUR_ECRAN - LARGEUR_AFFICHAGE )/2). " ".LARGEUR_AFFICHAGE." ".HAUTEUR_AFFICHAGE."'";
	      //shell_exec( $cmd );
		  //$out = $cmd; 
	    // } else {
	     	$err = 'omxplayer is already runnning';
	   //  }
		$out = 'playing '.basename($file);
	    echo $out;
	    
	    
	    
	    
	    
if(isset($donnees["BANDEAU_BAS"]))
{
	echo'<div id="bandeau_bas" style="z-index:1; width:'.$largeur.'px; position:absolute; bottom:0px; left:0px; height:150px; background-color:rgba(0,0,0,0.5); vertical-align:middle" >
<p id="texte_bas" style="width:100%; font-size:'.$taillePolice.'px; color:#FFFFFF; text-align:center; font-weight:bolder; font-family:Verdana, Arial, Helvetica, sans-serif; b">'.$donnees["TEXTE_BAS"].'</p>
</div>';
}
echo '<script type="text/javascript">

document.getElementById("videoPlayer").addEventListener("ended",next);
</script>';
?>
