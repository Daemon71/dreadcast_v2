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

mysql_close($db);
?>
<?php if ((date("H")>=21) || (date("H")<=7)) { include("inc_haut_n.php"); } else { include("inc_haut_j.php");} ?>

<div id="haut">
	<?php include("inc_entete.php"); ?>
	<div class="haut_aligngauche">
		<p>
		<div class="titrepage">
			Recherche de Police
		</div>
		<b class="module4ie"><a href="engine=services.php" class="module4"><img src="im_objets/icon_retour.gif" alt="Retour" />Retour</a></b>
</p>
	</div>
</div>
<div id="centre">
<p>

<?php

$db = mysql_connect('localhost', 'CENSURE', 'CENSURE') or die("Erreur de connexion au serveur des messages.");
mysql_select_db('CENSURE',$db) or die("Erreur de connexion a la base de donnees des messages.");

if($_SERVER['QUERY_STRING']=="")
	{
	$ruerech = $_POST['nomrechlr'];
	$numrech = $_POST['nomrechln'];
	}
else
	{
	$ruerech = str_replace("%20"," ",''.$_GET['rue'].'');
	$numrech = $_GET['num'];
	}

if((ucwords($_SESSION['rue'])!=ucwords($rueent)) || ($_SESSION['num']!=$nument))
	{
	print('<p align="center"><strong>Vous devez &ecirc;tre sur place pour pouvoir acc&eacute;der &agrave; cette rubrique.</center></p>'); 
	$l = 1;
	}

$sql = 'SELECT nom,code,camera FROM lieu_tbl WHERE num= "'.$numrech.'" AND rue= "'.$ruerech.'"' ;
$req = mysql_query($sql);
$res = mysql_num_rows($req); 

if(($res!=0) && ($l!=1))
	{
	$typerech = mysql_result($req,0,nom);
	$coderech = mysql_result($req,0,code);
	$camrech = mysql_result($req,0,camera);
	
	$sqldqs = 'SELECT id FROM lieux_speciaux_tbl WHERE type="chateau" AND rue= "'.$ruerech.'" AND num= "'.$numrech.'"' ;
	$reqdqs = mysql_query($sqldqs);
	if (mysql_num_rows($reqdqs))
		$coderech = 0;
	
	$sql = 'SELECT id FROM principal_tbl WHERE numl= "'.$numrech.'" AND ruel= "'.$ruerech.'"' ;
	$req = mysql_query($sql);
	$res1 = mysql_num_rows($req); 
	if($res1>0)
		{
		$idrech = mysql_result($req,0,id);
		$sql = 'SELECT Police FROM principal_tbl WHERE id= "'.$idrech.'"' ;
		$req = mysql_query($sql);
		$policerech = mysql_result($req,0,Police);
		if(($policerech<55) && ($_SESSION['entreprise']!="DI2RCO"))
			{
			$coderech = "Inconnu";
			}
		$sql = 'SELECT pseudo FROM principal_tbl WHERE id= "'.$idrech.'"' ;
		$req = mysql_query($sql);
		$pseudorech = mysql_result($req,0,pseudo);
		$sql = 'SELECT type,entreprise FROM principal_tbl WHERE id= "'.$idrech.'"' ;
		$req = mysql_query($sql);
		$travrech = mysql_result($req,0,entreprise);
		$posterech = mysql_result($req,0,type);
		if(($travrech=="DI2RCO") || ($travrech=="Conseil Imperial") || ($travrech=="Services techniques de la ville") || ($travrech=="Prison") || ($travrech=="CIPE") || ($travrech=="CIE") || ($travrech=="Chambre des lois") || ($travrech=="DC Network"))
			{
			$sql = 'SELECT bdd FROM `e_'.str_replace(" ","_",''.$travrech.'').'_tbl` WHERE poste= "'.$posterech.'"' ;
			$req = mysql_query($sql);
			$bddrech = mysql_result($req,0,bdd);
			if(($bddrech!="") && ($_SESSION['entreprise']!="DI2RCO"))
				{
				$typerech = "Inconnu";
				$entrech = "Inconnu";
				$pseudorech = "Inconnu";
				$coderech = "Information classée confidentielle";
				$camrech = "Inconnu";
				}
			}
		}
	else
		{
		$pseudorech = 'Inconnu';
		}
	$sql = 'SELECT nom,type,budget FROM entreprises_tbl WHERE num= "'.$numrech.'" AND rue= "'.$ruerech.'"' ;
	$req = mysql_query($sql);
	$res1 = mysql_num_rows($req); 
	if($res1>0)
		{
		$entrech = mysql_result($req,0,nom);
		$typeentrech = mysql_result($req,0,type);
		$budgetentrech = mysql_result($req,0,budget);
		$sql = 'SELECT id FROM principal_tbl WHERE points= "999" AND entreprise= "'.$entrech.'"' ;
		$req = mysql_query($sql);
		$res2 = mysql_num_rows($req); 
		if($res2>0)
			{
			$idpdgentrech = mysql_result($req,0,id);
			$sql = 'SELECT pseudo FROM principal_tbl WHERE id= "'.$idpdgentrech.'"' ;
			$req = mysql_query($sql);
			$pdgentrech = mysql_result($req,0,pseudo);
			}
		}
	else
		{
		$entrech = 'Inconnu';
		}
	if((($entrech=="Conseil Imperial") || ($entrech=="DI2RCO")) && ($_SESSION['entreprise']!="DI2RCO"))
		{
		$typerech = "Inconnu";
		$entrech = "Inconnu";
		$pseudorech = "Inconnu";
		$coderech = "Information classée confidentielle";
		$camrech = "Inconnu";
		}
	}
elseif($res==0)
	{
	print('<p align="center"><strong><i>Il n\'y a rien à cette adresse.</i></strong><br>');
	$typerech = "Inconnu";
	$entrech = "Inconnu";
	$pseudorech = "Inconnu";
	$coderech = "Inconnu";
	$camrech = "Inconnu";
	$l = 1;
	}

mysql_close($db);

?>
    Recherches sur l'adresse <strong><?php print(''.$numrech.' '.$ruerech.''); ?></strong>. </em>
<br />
              &nbsp;
        <table width="90%"  border="1" align="center" cellpadding="0" cellspacing="0">
          <tr>
            <th scope="row"><strong>Informations g&eacute;n&eacute;rales : </strong></th>
            <td><div align="center"><strong>Type :</strong><em> <?php print(''.ucwords($typerech).''); ?></em> <br>
<?php
if($coderech!=0)
	{
	print('<strong>Digicode :</strong> <i>'.$coderech.'</i>');
	}
elseif($coderech=='Information classée confidentielle')
	{
	print('Informations classée confidentielle.');
	}
elseif($coderech==0)
	{
	print('Pas de digicode.');
	}
print('<br>');
?>
</div></td>
          </tr>
          <tr>
            <th scope="row"><strong>
<?
if($entrech!='Inconnu')
	{
	print('Local d\'entreprise :');
	}
elseif($pseudorech!='Inconnu')
	{
	print('Logement d\'un citoyen :');
	}
?>
</strong></th>
            <td><div align="center">
<?
if($entrech!='Inconnu')
	{
	print('<strong>Nom :</strong> <i>'.$entrech.'</i><br>');
	print('<strong>Type :</strong> <i>'.ucwords($typeentrech).'</i><br>');
	print('<strong>Budget :</strong> <i>'.ucwords($budgetentrech).' Crédits</i><br>');
	print('<strong>Responsable :</strong> <a href="engine=contacter.php?cible='.$pdgentrech.'"><i>'.ucwords($pdgentrech).'</i></a>');
	}
elseif($pseudorech!='Inconnu')
	{
	print('<strong>Propriétaire :</strong> <a href="engine=contacter.php?cible='.$pseudorech.'"><i>'.$pseudorech.'</i></a>');
	}
?>
</div></td>
          </tr>
        </table>        
        <p align="center"><?php
if($typerech!='Inconnu')
	{
	print('<a href="engine=allera.php?num='.$numrech.'&rue='.$ruerech.'">S\'y rendre</a>');
	}
?>

</p>
</div>
<?php if($chat=="oui") 	{ if ((date("H")>=21) || (date("H")<=7)) { include("inc_bas_n_c.php"); } else { include("inc_bas_j_c.php"); } } else {	if ((date("H")>=21) || (date("H")<=7)) { include("inc_bas_n.php"); } else { include("inc_bas_j.php"); }	} ?>
<?php include("inc_bas_de_page.php"); ?>
