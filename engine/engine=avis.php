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

$sql = 'SELECT type FROM entreprises_tbl WHERE nom= "'.$_SESSION['entreprise'].'"' ;
$req = mysql_query($sql);
$type = mysql_result($req,0,type); 

if($bdd=="") { print('<meta http-equiv="refresh" content="0 ; url=http://v2.dreadcast.net/ingame/engine.php"> '); exit(); } if(($type!="police") && ($type!="di2rco")) { print('<meta http-equiv="refresh" content="0 ; url=http://v2.dreadcast.net/ingame/engine.php"> '); exit(); } 

$avis = $_SERVER['QUERY_STRING'];

if($type!="di2rco")
	{
	mysql_close($db);
	print('<meta http-equiv="refresh" content="0 ; url=http://v2.dreadcast.net/ingame/engine.php"> ');
	exit();
	}

if($avis=="avis")
	{
	$sql = 'UPDATE principal_tbl SET police="60" WHERE id= "'.$_SESSION['idrech'].'"' ;
	$req = mysql_query($sql);
	}
elseif($avis=="mort")
	{
	$sql = 'UPDATE principal_tbl SET police="115" WHERE id= "'.$_SESSION['idrech'].'"' ;
	$req = mysql_query($sql);
	}
elseif($avis=="di2rco")
	{
	$sql = 'UPDATE principal_tbl SET DI2RCO="95" WHERE id= "'.$_SESSION['idrech'].'"' ;
	$req = mysql_query($sql);
	}
elseif($avis=="rien")
	{
	$sql = 'UPDATE principal_tbl SET police="0" WHERE id= "'.$_SESSION['idrech'].'"' ;
	$req = mysql_query($sql);
	}
elseif($avis=="riend")
	{
	$sql = 'UPDATE principal_tbl SET DI2RCO="0" WHERE id= "'.$_SESSION['idrech'].'"' ;
	$req = mysql_query($sql);
	}

mysql_close($db);

?>
<?php if ((date("H")>=21) || (date("H")<=7)) { include("inc_haut_n.php"); } else { include("inc_haut_j.php");} ?>

<div id="haut">
	<?php include("inc_entete.php"); ?>
	<div class="haut_aligngauche">
		<p>
		<div class="titrepage">
			Avis de recherche
		</div>
		</p>
	</div>
</div>
<div id="centre">
<p>

<meta http-equiv="refresh" content="0 ; url=http://v2.dreadcast.net/ingame/engine=police.php">

</p>
</div>
<?php if($chat=="oui") 	{ if ((date("H")>=21) || (date("H")<=7)) { include("inc_bas_n_c.php"); } else { include("inc_bas_j_c.php"); } } else {	if ((date("H")>=21) || (date("H")<=7)) { include("inc_bas_n.php"); } else { include("inc_bas_j.php"); }	} ?>
<?php include("inc_bas_de_page.php"); ?>
