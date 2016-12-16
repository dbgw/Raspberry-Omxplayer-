<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<?php
	if(file_exists('config/config_reseau.php'))
	{
		//si le fichier de configuration de réseau existe le charger
		include('config/config_reseau.php');
	}
	
	if (file_exists('config/config_affichage.php'))
	{
		//si déjà configuré chargement des paramètres
		include('config/config_affichage.php');
	}
	if (ROTATION_AFFICHAGE!=0)
	{
		$rotation='transform:rotate('.ROTATION_AFFICHAGE.'deg);';
		$largeur=HAUTEUR_AFFICHAGE;
		$hauteur=LARGEUR_AFFICHAGE;
		$left=(LARGEUR_AFFICHAGE-HAUTEUR_AFFICHAGE)/2;
		$top=$left*-1;
	}
	else
	{
		$rotation='';
		$largeur=LARGEUR_AFFICHAGE;
		$hauteur=HAUTEUR_AFFICHAGE;
		$left=0;
		$top=0;
	}
	
?>
<title><?php echo NOM_PLAYER; ?></title>
<style type="text/css">
<!--
body {
	background-color: #000000;
	margin-left: 0px;
	margin-top: 0px;
	margin-right: 0px;
	margin-bottom: 0px;
	overflow-x: hidden;
	overflow-y: hidden;
}
body,td,th {
	color: #FFFFFF;
}
-->
</style></head>

<body>
<script type="text/javascript">
<?php
if(!isset($_GET["index"]))
{
	echo 'var index=-1;';
}
else
{
	echo 'var index='.$_GET["index"];
}
?>



function checkOmxplayer()
{
	var xhrUp=getXhr();
	
		xhrUp.onreadystatechange = function(){
		
			if(xhrUp.readyState == 4 && xhrUp.status == 200){
			    console 
				var str=xhrUp.responseText;
					if(str=='oui')
					{
					 return true;
					}
			}
		}
		console.log ( "checkOmxplayer.php");
	xhrUp.open("POST","checkOmxplayer.php",true);
	xhrUp.setRequestHeader('Content-Type','application/x-www-form-urlencoded');
	xhrUp.send();
}


function nextPage()
{
	
	 var adresse='player.php?index='+index;
	 document.location.href=adresse;
}

function next()
	{
		console.log("check update before next");
		checkUpdate();
		 console.log("checking omxplayer");
	 if  (checkOmxplayer() == true)  {
		 alert ('omxplayer is running');
		 return;
	 }	 	
		index++;
			if(index < obj.ELEMENT.length)
			{
			
					if(obj.ELEMENT[index].TYPE=='VID'||obj.ELEMENT[index].TYPE=='AUT_VID')
					{
						logElement(obj.ELEMENT[index]);
						playVid(obj.ELEMENT[index]);
					}
					else if(obj.ELEMENT[index].TYPE=='IMA'||obj.ELEMENT[index].TYPE=='AUT_IMA')
					{
						logElement(obj.ELEMENT[index]);
						playIma(obj.ELEMENT[index]);
					}
					else if(obj.ELEMENT[index].TYPE=='HTML')
					{
						playHTML(obj.ELEMENT[index]);
					}
					else
					{
					next();
					}
			}
			else
			{
				index=-1;
				next();
			}
	}

function checkUpdate()
{
	var xhrUp=getXhr();
	
		xhrUp.onreadystatechange = function(){
		
		
			if(xhrUp.readyState == 4 && xhrUp.status == 200){
				
				var str=xhrUp.responseText;
				console.log ("onreadystatechange"+xhrUp.readyState+" "+str );
					if(str=='oui')
					{
					document.location.href="player_final.php";
					}
			}
		}
	xhrUp.open("POST","checkUpdates.php",true);
	xhrUp.setRequestHeader('Content-Type','application/x-www-form-urlencoded');
	xhrUp.send();
}


function logElement(element)
	{
		var elementPost =JSON.stringify(element);
		var xhrLog=getXhr();
		xhrLog.onreadystatechange = function(){
		
			if(xhrLog.readyState == 4 && xhrLog.status == 200){
			
				var str=xhrLog.responseText;
			}
		}
	xhrLog.open("POST","log.php",true);
	xhrLog.setRequestHeader('Content-Type','application/x-www-form-urlencoded');
	xhrLog.send("element="+elementPost);
		
	}	
	
function getXhr()
	{
		var xhr = null; 
		if(window.XMLHttpRequest) // Firefox et autres
	  	 xhr = new XMLHttpRequest(); 
			else if(window.ActiveXObject){ // Internet Explorer 
			   try {
		              xhr = new ActiveXObject("Msxml2.XMLHTTP");
		            } catch (e) {
		               xhr = new ActiveXObject("Microsoft.XMLHTTP");
					}
		}
		else { // XMLHttpRequest non supporté par le navigateur 
		   alert("Votre navigateur ne supporte pas les objets XMLHTTPRequest..."); 
		   xhr = false; 
		} 
   	return xhr
	}
	
function playIma(element)
	{
		var elementPost =JSON.stringify(element);
		var xhrIma=getXhr();
		xhrIma.onreadystatechange = function(){
		
			if(xhrIma.readyState == 4 && xhrIma.status == 200){
			
				var str=xhrIma.responseText;
				document.getElementById("player").innerHTML = str;
				var duree=element.DUREE*1000;	
				setTimeout(nextPage,duree);

			}
		}
	xhrIma.open("POST","imaPlayer.php",true);
	xhrIma.setRequestHeader('Content-Type','application/x-www-form-urlencoded');
	xhrIma.send("element="+elementPost);
	
	}

function playHTML(element)
	{
		//var elementPost =JSON.stringify(element);
		var url = encodeURIComponent(element.FICHIER);
		var xhrHTML=getXhr();
		xhrHTML.onreadystatechange = function(){
		
			if(xhrHTML.readyState == 4 && xhrHTML.status == 200){
			
				var str=xhrHTML.responseText;
				document.getElementById("player").innerHTML = str;
				var duree=element.DUREE*1000;	
				setTimeout(nextPage,duree);

			}
		}
	xhrHTML.open("POST","HTMLPlayer.php",true);
	xhrHTML.setRequestHeader('Content-Type','application/x-www-form-urlencoded');
	xhrHTML.send("url="+url);
	
	}

function playVid(element)
	{
		
		var elementPost =JSON.stringify(element);
		var xhrVid=getXhr();
		xhrVid.onreadystatechange = function(){
		
			if(xhrVid.readyState == 4 && xhrVid.status == 200){
			
				var str=xhrVid.responseText;
				document.getElementById("player").innerHTML = str;	
				document.getElementById("videoPlayer").addEventListener("ended",nextPage);

			}
		}
	xhrVid.open("POST","vidPlayer.php",true);
	xhrVid.setRequestHeader('Content-Type','application/x-www-form-urlencoded');

	xhrVid.send("element="+elementPost);
	}
</script>
<div id="player" style="position:absolute;top:<?php echo $top; ?>px; left:<?php echo $left; ?>px; width:<?php echo $largeur; ?>px; height:<?php echo $hauteur; ?>px;<?php echo $rotation; ?>"></div>
<?php
	
	//etude du contenu de la boucle
	
	//chargement des medias
	//$xmlFichiers = simplexml_load_file('LISTE_FICHIERS.XML');
	
	
	//definir la boucle en cours
	$boucleNow='';
	$boucles= scandir('BOUCLES');
	$heureNow =date('Hi');
	
	$vids = array();
	$aut_vids = array();
	$ima = array();
	$aut_ima = array();
	
		foreach($boucles as $var)
		{
			if($var!='.' && $var!='..')
			{
				$tab = explode('_',$var);
				if($tab[3]<$heureNow && $tab[4]>$heureNow)
				{
					$boucleNow = $var;
				}
			}
		}
	//charger le XML

	$xml=file_get_contents('BOUCLES/'.$boucleNow);
	$xml = preg_replace('#&(?=[a-z_0-9]+=)#', '&amp;', $xml); //réencodage des & en &amp;
	$xmlBoucle= simplexml_load_string($xml);
	$xmlBoucleJs=json_encode($xmlBoucle);
	if(count($xmlBoucle)==1)
	{
		$xmlBoucleJs=str_replace('"ELEMENT":{','"ELEMENT":[{',$xmlBoucleJs);
		$xmlBoucleJs=str_replace('}}','}]}',$xmlBoucleJs);
	}
	
?>
<script type="text/javascript">
var boucle =' <?php echo $xmlBoucleJs; ?>';
var obj = JSON.parse(boucle);
next();
</script>
</body>
</html>
