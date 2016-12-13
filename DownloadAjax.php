<head>
<script type="text/javascript">
function addToListe(ftp, origine,destination)
	{
		listeElements.push(Array(ftp,origine,destination));
		if(contListe==-1)
		{
			nextElem();
		}
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
function nextElem()
	{
		contListe++;
		if(contListe<listeElements.length)
		{
			telechargeElem(listeElements[contListe][0],listeElements[contListe][1],listeElements[contListe][2]);
		}
		else
		{
			document.location.href="player.php";
		}
	
	}

function telechargeElem(ftp,fichier,destination)
	{
	
		var xhrTel=getXhr();
		xhrTel.onreadystatechange = function(){
		
			if(xhrTel.readyState == 4 && xhrTel.status == 200){
			
				var str=xhrTel.responseText;
					if(str.substring(0,3)== 'tel')
					{
						var ret=str.split(";");
						addToListe(ret[1],ret[3],ret[2]);
					}
					else
					{
						document.getElementById("telech").innerHTML=document.getElementById("telech").innerHTML + str;
						nextElem();
					}
					
			}
		}
	xhrTel.open("POST","downListElement.php",true);
	xhrTel.setRequestHeader('Content-Type','application/x-www-form-urlencoded');
	xhrTel.send("FTP="+ftp+"&urlDist="+fichier+"&urlLocale="+destination);
		}



function telecharge(ftp,fichier,destination)
	{
		var xhrDown=getXhr();
		xhrDown.onreadystatechange = function(){
		
			if(xhrDown.readyState == 4 && xhrDown.status == 200){
			countCheck++;
				var str=xhrDown.responseText;
					if(str.substring(0,3)== 'tel')
					{
						var ret=str.split(";");
						addToListe(ret[1],ret[3],ret[2]);
					}
					else
					{
						document.getElementById("suivi").innerHTML=document.getElementById("suivi").innerHTML + str;
					}
					if((countCheck==nbCheck)&&(listeElements.length==0))
					{
						document.location.href="player.php";
					}
					
			}
		}
	xhrDown.open("POST","downElement.php",true);
	xhrDown.setRequestHeader('Content-Type','application/x-www-form-urlencoded');
	xhrDown.send("FTP="+ftp+"&urlDist="+fichier+"&urlLocale="+destination);
		
	}
</script>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8"><style type="text/css">
<!--
body,td,th {
	color: #FFFFFF;
}
body {
	background-color: #000000;
}
-->
</style></head>
<body>
<script type="text/javascript">
var listeElements =[];
var contListe=-1;
var countCheck=1;
</script>
<p id="telech"></p>
<hr>

<p id="suivi"></p>
</body>
<?php

//suppression anciens films et images
$startTime = mktime(0, 0, 0, date('m')-1  , 1 , date('Y'));
//videos

$listeVid=scandir('VIDEOS');
	foreach($listeVid as $filmVid)
	{
		if($filmVid!='.' && $filmVid!='..')
		{
			if(filemtime('VIDEOS/'.$filmVid)<$startTime)
			{
				unlink('VIDEOS/'.$filmVid);
			}
		}
	}

//images

$listeIm=scandir('IMAGES/FILMS/');
	foreach($listeIm as $filmIm)
	{
		if($filmIm!='.' && $filmIm!='..')
		{
			if(filemtime('IMAGES/FILMS/'.$filmIm)<$startTime)
			{
				unlink('IMAGES/FILMS/'.$filmIm);
			}
		}
	}

$listeIm=scandir('img/');
	foreach($listeIm as $filmIm)
	{
		if($filmIm!='.' && $filmIm!='..')
		{
			if(filemtime('img/'.$filmIm)<$startTime)
			{
				unlink('img/'.$filmIm);
			}
		}
	}


$xmlFilms = simplexml_load_file('films_a_recuperer.xml');
echo '<script type"text/javascript">var nbCheck='.count($xmlFilms).';</script>';
			foreach($xmlFilms->ELEMENT as $v)
			{
				switch(substr($v->REPERTOIRE,0,3))
				{
					case 'VID':
					$dest='VIDEOS/';
					$serv='FTP2';
					$urlDist ='PLAYER CÔTÉ CINÉ/'.$v->REPERTOIRE.$v->FICHIER;
					break;
					case 'IMA':
					$dest='IMAGES/FILMS/';
					$serv='FTP2';
					$urlDist ='PLAYER CÔTÉ CINÉ/'.$v->REPERTOIRE.$v->FICHIER;
					break;
					case 'AUT':
					$serv='FTP1';
					if($v->REPERTOIRE=='AUTRES/IMAGES/')
					{
						$dest='img/';
					}
					else
					{
						$dest='VIDEOS/';
					}
					$urlDist =$v->REPERTOIRE.$v->FICHIER;
					break;
					default:
					$dest='';
					break;
				}
				
				$locale = $dest.$v->FICHIER;
				
				echo '<script type"text/javascript">telecharge("'.$serv.'","'.$urlDist.'","'.$locale.'");</script>';
			}


	
?>