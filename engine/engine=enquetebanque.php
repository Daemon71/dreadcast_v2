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
			Transactions
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
	print('<p align="center"><strong>Vous devez &ecirc;tre sur place pour pouvoir acc&eacute;der &agrave; cette rubrique.</center></p>'); 
	print('<p align="center"><a href="engine=gestion.php">Retour"</a></p>');
	$l = 1;
	}

$sql = 'SELECT * FROM comptes_acces_tbl WHERE pseudo= "'.$_SESSION['nomrech'].'" ORDER BY id DESC LIMIT 20';
$req = mysql_query($sql);
$res = mysql_num_rows($req); 

if(($res!=0) && ($l!=1))
	{
	$sql1 = 'SELECT type,entreprise FROM principal_tbl WHERE id= "'.$_SESSION['idrech'].'"' ;
	$req1 = mysql_query($sql1);
	$entrepriserech = mysql_result($req1,0,entreprise);
	if(($entrepriserech=="Conseil Imperial") || ($entrepriserech=="DI2RCO"))
		{
		$l = 2;
		}
	elseif($entrepriserech=="Police")
		{
		if($_SESSION['entreprise']!="DI2RCO")
			{
			$l = 2;
			}
		}
	}

mysql_close($db);

?>
    Localisation d'un<?php if($_SESSION['sexerech']=="Femme") print('e'); ?> d&eacute;nomm&eacute;<?php if($_SESSION['sexerech']=="Femme") print('e'); ?> <strong><?php print($_SESSION['nomrech']); ?></strong>. </em>
		<table width="450" border="0" align="center" cellpadding="0" cellspacing="0">
          <tr>
            <td><p align="center">
<? 
if(($l != 1) && ($l != 2))
	{
	if($res==1) 
		{ 
		if($_SESSION['sexerech']=="Femme") print('Elle a effectué dernièrement une unique transaction :</p>');
		else print('Il a effectué dernièrement une unique transaction :</p>'); 
		print('</td></tr><tr><td><p align="left"><strong>'.date('d/m/Y à H:i',mysql_result($req,0,'time')).':</strong> '.mysql_result($req,0,operation).' (<i>'.mysql_result($req,0,valeur).'</i>)');
		}
	elseif($res>1) 
		{
		if($res>10)
			{
			$res = 10;
			}
		if($_SESSION['sexerech']=="Femme") print('Elle a effectué dernièrement '.$res.' transactions :</p>');
		else print('Il a effectué dernièrement '.$res.' transactions :</p>'); 
		for($i=0; $i!=$res; $i++)
			{
			print('</td></tr><tr><td><div align="left"><strong>'.date('d/m/Y à H:i',mysql_result($req,$i,'time')).':</strong> '.mysql_result($req,$i,operation).' (<i>'.mysql_result($req,$i,valeur).'</i>)</div>');
			}
		print('<p align="center">');
		}
	else 
		{
		if($_SESSION['sexerech']=="Femme") print('Les serveurs n\'ont aucune information la concernant.'); 
		else print('Les serveurs n\'ont aucune information le concernant.'); 
		}
	}
elseif($l == 2)
	{
	print('Informations class&eacute;es confidentielles'); 
	}
	
?>

</p></td></tr></table>
</div>
<?php if($chat=="oui") 	{ if ((date("H")>=21) || (date("H")<=7)) { include("inc_bas_n_c.php"); } else { include("inc_bas_j_c.php"); } } else {	if ((date("H")>=21) || (date("H")<=7)) { include("inc_bas_n.php"); } else { include("inc_bas_j.php"); }	} ?>
<?php include("inc_bas_de_page.php"); ?>
