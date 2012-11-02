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

$sql = 'SELECT points,entreprise FROM principal_tbl WHERE id= "'.$_SESSION['id'].'"' ;
$req = mysql_query($sql);
$_SESSION['points'] = mysql_result($req,0,points);
$_SESSION['entreprise'] = mysql_result($req,0,entreprise);

if($_SESSION['points']!=999) { print('<meta http-equiv="refresh" content="0 ; url=http://v2.dreadcast.net/ingame/engine.php"> ');	exit();	}

$sql = 'SELECT num,rue,budget FROM entreprises_tbl WHERE nom= "'.$_SESSION['entreprise'].'"' ;
$req = mysql_query($sql);
$_SESSION['ruee'] = mysql_result($req,0,rue);
$_SESSION['nume'] = mysql_result($req,0,num);
$_SESSION['budget'] = mysql_result($req,0,budget); 

$sql = 'SELECT camera,code FROM lieu_tbl WHERE rue= "'.$_SESSION['ruee'].'" AND num= "'.$_SESSION['nume'].'"' ;
$req = mysql_query($sql);
$camera = mysql_result($req,0,camera);

if($camera=="Pol")
	{
	print('<meta http-equiv="refresh" content="0 ; url=http://v2.dreadcast.net/ingame/engine=local.php"> ');
	exit();
	}

if($_SESSION['budget']>=100)
	{
	$_SESSION['budget'] = $_SESSION['budget'] - 100;
	$sql = 'UPDATE entreprises_tbl SET budget="'.$_SESSION['budget'].'" WHERE nom= "'.$_SESSION['entreprise'].'"' ;
	$req = mysql_query($sql);
	$sql = 'UPDATE lieu_tbl SET camera="Pol" WHERE rue= "'.$_SESSION['ruee'].'" AND num= "'.$_SESSION['nume'].'"' ;
	$req = mysql_query($sql);
	$camera = "Pol";
	}

mysql_close($db);

?>
<?php if ((date("H")>=21) || (date("H")<=7)) { include("inc_haut_n.php"); } else { include("inc_haut_j.php");} ?>

<div id="haut">
	<?php include("inc_entete.php"); ?>
	<div class="haut_aligngauche">
		<p>
		<div class="titrepage">
			Local d'entreprise
		</div>
		<b class="module4ie"><a href="engine=gestion.php" class="module4"><img src="im_objets/icon_retour.gif" alt="Retour" />Retour</b></a>
		</p>
	</div>
</div>
<div id="centre">
<p>


<?php
if($camera=="Pol")
	{
	print('Votre local contient maintenant une Camera de police.');
	}
else
	{
	print('<strong>Vous n\'avez pas assez d\'argent.</strong>');
	}

?>


</p>
</div>
<?php if($chat=="oui") 	{ if ((date("H")>=21) || (date("H")<=7)) { include("inc_bas_n_c.php"); } else { include("inc_bas_j_c.php"); } } else {	if ((date("H")>=21) || (date("H")<=7)) { include("inc_bas_n.php"); } else { include("inc_bas_j.php"); }	} ?>
<?php include("inc_bas_de_page.php"); ?>
