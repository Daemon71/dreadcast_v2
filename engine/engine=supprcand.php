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
			Refuser une candidature
		</div>
		</p>
	</div>
</div>
<div id="centre">
<p>

<img src="im_objets/loader.gif" alt="..." />
<?php 

$db = mysql_connect('localhost', 'CENSURE', 'CENSURE') or die("Erreur de connexion au serveur des messages.");
mysql_select_db('CENSURE',$db) or die("Erreur de connexion a la base de donnees des messages.");

if($_SERVER['QUERY_STRING']!="")
	{
	$_SESSION['ido'] = $_SERVER['QUERY_STRING'];
	}

$sql = 'SELECT type,entreprise,points FROM principal_tbl WHERE id= "'.$_SESSION['id'].'"' ;
$req = mysql_query($sql);
$_SESSION['poste'] = mysql_result($req,0,type); 
$_SESSION['entreprise'] = mysql_result($req,0,entreprise); 
$_SESSION['points'] = mysql_result($req,0,points); 

if($_SESSION['points']!=999) { print('<meta http-equiv="refresh" content="0 ; url=http://v2.dreadcast.net/ingame/engine.php"> '); exit(); }

$sql = 'SELECT poste FROM candidatures_tbl WHERE id= "'.$_SESSION['ido'].'"' ;
$req = mysql_query($sql);
$pos = mysql_result($req,0,poste);

if($_POST['raison']=="")
	{
	print('<meta http-equiv="refresh" content="0 ; url=http://v2.dreadcast.net/ingame/engine=raisonsc.php"> ');
	}
else
	{
	$sql = 'SELECT nom FROM candidatures_tbl WHERE id="'.$_SESSION['ido'].'"' ;
	$req = mysql_query($sql);
	$pseu = mysql_result($req,0,nom);
	$sql = 'INSERT INTO messages_tbl(id,auteur,cible,message,moment,objet) VALUES("","'.$_SESSION['entreprise'].'","'.$pseu.'","Vous avez &eacute;t&eacute; refus&eacute; au poste de '.$pos.'. <br><br> <strong>Motif :</strong> '.str_replace("\n","<br />",''.htmlentities($_POST["raison"],ENT_QUOTES).'').'","'.time().'","Vous êtes refusé !")';
	$req = mysql_query($sql);
	$sql = 'DELETE FROM candidatures_tbl WHERE id="'.$_SESSION['ido'].'"' ;
	$req = mysql_query($sql);
	print('<meta http-equiv="refresh" content="0 ; url=http://v2.dreadcast.net/ingame/engine=personnel.php"> ');
	}

mysql_close($db);
?>


</p>
</div>
<?php if($chat=="oui") 	{ if ((date("H")>=21) || (date("H")<=7)) { include("inc_bas_n_c.php"); } else { include("inc_bas_j_c.php"); } } else {	if ((date("H")>=21) || (date("H")<=7)) { include("inc_bas_n.php"); } else { include("inc_bas_j.php"); }	} ?>
<?php include("inc_bas_de_page.php"); ?>
