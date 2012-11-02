<?php 
session_start(); 
if($_SESSION['id']=="")
	{
	print('<meta http-equiv="refresh" content="0 ; url=http://v2.dreadcast.net/"> ');
	exit();
	}
if($_SESSION['action']=="Vacances")
	{
	print('<meta http-equiv="refresh" content="0 ; url=http://v2.dreadcast.net/ingame/engine=cryo.php"> ');
	exit();
	}
if($_SESSION['action']=="mort")
	{
	print('<meta http-equiv="refresh" content="0 ; url=http://v2.dreadcast.net/ingame/engine=mort.php"> ');
	exit();
	}
?>
<?php if ((date("H")>=21) || (date("H")<=7)) { include("inc_haut_n.php"); } else { include("inc_haut_j.php");} ?>

<div id="haut">
	<?php include("inc_entete.php"); ?>
	<div class="haut_aligngauche">
		<p>
		<div class="titrepage">
			Plaintes
		</div>
		<b class="module4ie"><a href="engine=pleintes.php" class="module4"><img src="im_objets/icon_retour.gif" alt="Retour" />Retour</a></b>
</p>
	</div>
</div>
<div id="centre">
<p>

            <?php

$db = mysql_connect('localhost', 'CENSURE', 'CENSURE') or die("Erreur de connexion au serveur des messages.");
mysql_select_db('CENSURE',$db) or die("Erreur de connexion a la base de donnees des messages.");

$sql = 'SELECT type,entreprise,points FROM principal_tbl WHERE id= "'.$_SESSION['id'].'"' ;
$req = mysql_query($sql);
$_SESSION['poste'] = mysql_result($req,0,type); 
$_SESSION['entreprise'] = mysql_result($req,0,entreprise); 
$_SESSION['points'] = mysql_result($req,0,points); 

$sql = 'SELECT bdd FROM `e_'.str_replace(" ","_",''.$_SESSION['entreprise'].'').'_tbl` WHERE poste= "'.$_SESSION['poste'].'"' ;
$req = mysql_query($sql);
$bdd = mysql_result($req,0,bdd); 

$sql = 'SELECT type,num,rue FROM entreprises_tbl WHERE nom= "'.$_SESSION['entreprise'].'"' ;
$req = mysql_query($sql);
$type = mysql_result($req,0,type); 
$rueent = mysql_result($req,0,rue); 
$nument = mysql_result($req,0,num); 

$sql = 'SELECT type FROM entreprises_tbl WHERE nom= "'.$_SESSION['entreprise'].'"' ;
$req = mysql_query($sql);
$type = mysql_result($req,0,type); 

if($bdd=="") { print('<meta http-equiv="refresh" content="0 ; url=http://v2.dreadcast.net/ingame/engine.php"> '); exit(); } if(($type!="police") && ($type!="di2rco")) { print('<meta http-equiv="refresh" content="0 ; url=http://v2.dreadcast.net/ingame/engine.php"> '); exit(); }

if((ucwords($_SESSION['rue'])!=ucwords($rueent)) || ($_SESSION['num']!=$nument))
	{
	print('<strong>Vous devez &ecirc;tre sur place pour pouvoir acc&eacute;der &agrave; cette rubrique.</center>'); 
	$l = 1;
	}


if($_GET['pec']==1)
	{
	$sql1 = 'UPDATE pleintes_tbl SET policier= "'.$_SESSION['pseudo'].'" WHERE id= "'.$_GET['id'].'"' ;
	$req1 = mysql_query($sql1);
	}
		
$sql = 'SELECT * FROM pleintes_tbl WHERE id= "'.$_GET['id'].'"' ;
$req = mysql_query($sql);
$res = mysql_num_rows($req);

if(($res!=0) && ($l!=1))
	{
	$auteur = mysql_result($req,0,pseudo);
	$vu = mysql_result($req,0,vu);
	$message = mysql_result($req,0,msg);
	$repondu = mysql_result($req,0,repondu);
	$datea = date('d/m/y',mysql_result($req,0,moment));
	$heure = date('H\hi',mysql_result($req,0,moment));
	if($_GET['msg']==1)
		{
		$sql1 = 'SELECT * FROM messages_tbl WHERE id= "'.mysql_result($req,0,joindre).'"' ;
		$req1 = mysql_query($sql1);
		$res1 = mysql_num_rows($req1);
		if($res1 != 0) print('<p align="center">Message joint à la plainte de <strong>'.$auteur.'</strong> le '.$datea.' &agrave; '.$heure.' :</p><p align="center" class="barre">Message de '.mysql_result($req1,0,auteur).' le '.date('d/m/y',mysql_result($req1,0,moment)).' à '.date('H\hi',mysql_result($req1,0,moment)).' : <br /><br />'.mysql_result($req1,0,message).'</p><p align="center"><a href="engine=consultpleinte.php?id='.$_GET['id'].'">Revenir à la plainte</a></p>');
		else print('<p align="center">Message joint à la plainte de <strong>'.$auteur.'</strong> le '.$datea.' &agrave; '.$heure.' :</p><p align="center" class="barre">Message supprimé par l\'auteur.</p>');
		}
	else
		{
		if($repondu=="oui")
			{
			print('<p align="center">Plainte de <strong>'.$auteur.'</strong> le '.$datea.' à '.$heure.' :</p><p align="center" class="barre">'.$message.'');
			}
		elseif($vu=="oui")
			{
			print('<p align="center">Plainte de <strong>'.$auteur.'</strong> le '.$datea.' à '.$heure.' :</p><p align="center" class="barre">'.$message.'</p><p align="center">');
			}
		else
			{
			$sql1 = 'UPDATE pleintes_tbl SET vu= "oui" WHERE id= "'.$_GET['id'].'"' ;
			$req1 = mysql_query($sql1);
			print('<p align="center">Plainte de <strong>'.$auteur.'</strong> le '.$datea.' &agrave; '.$heure.' :</p><p align="center" class="barre">'.$message.'</p><p align="center">');
			}
		if(mysql_result($req,0,policier)=="") print('<a href="engine=consultpleinte.php?id='.$_GET['id'].'&pec=1">Prendre en charge cette plainte</a>');
		elseif(mysql_result($req,0,policier)==$_SESSION['pseudo'] || $_SESSION['points']==999) print('<a href="engine=contacter.php?cible='.$auteur.'&objet=Plainte">R&eacute;pondre &agrave; cette plainte</a><br /><a href="engine=supprpleinte.php?id='.$_GET['id'].'">Archiver cette plainte</a>');
		if(mysql_result($req,0,joindre)!=0) print('<br />Ce message possède une pièce jointe : <a href="engine=consultpleinte.php?id='.$_GET['id'].'&msg=1">Consulter</a>');
		print('</p>');
		}
	}
else
	{
	print("<strong>Ce message ne peut pas s'afficher.</strong><br>");
	}

mysql_close($db);

?>

</p>
</div>
<?php if($chat=="oui") 	{ if ((date("H")>=21) || (date("H")<=7)) { include("inc_bas_n_c.php"); } else { include("inc_bas_j_c.php"); } } else {	if ((date("H")>=21) || (date("H")<=7)) { include("inc_bas_n.php"); } else { include("inc_bas_j.php"); }	} ?>
<?php include("inc_bas_de_page.php"); ?>
