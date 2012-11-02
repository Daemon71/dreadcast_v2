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

$sql = 'SELECT credits FROM principal_tbl WHERE id= "'.$_SESSION['id'].'"' ;
$req = mysql_query($sql);
$_SESSION['credits'] = mysql_result($req,0,credits);

$sql = 'SELECT nom,type,budget,benefices FROM entreprises_tbl WHERE rue= "'.$_SESSION['lieu'].'" AND num= "'.$_SESSION['num'].'"' ;
$req = mysql_query($sql);
$type = mysql_result($req,0,type);
$noment = mysql_result($req,0,nom);
$capital = mysql_result($req,0,budget);
$benefices = mysql_result($req,0,benefices);

if($type!="CIE")
	{
	mysql_close($db);
	print('<meta http-equiv="refresh" content="0 ; url=http://v2.dreadcast.net/ingame/engine.php"> ');
	exit();
	}
	
$compa = $_POST['compet'];
$dureea = $_POST['duree'];

if(($dureea==1) && ($_SESSION['credits']>=100))
	{
	$ok = 1;
	$_SESSION['credits'] = $_SESSION['credits'] - 100;
	$sql = 'UPDATE principal_tbl SET action= "En cours de '.$compa.' (1Heure)" WHERE id= "'.$_SESSION['id'].'"' ;
	$req = mysql_query($sql);
	$prix = 80;
	$capital = $capital + 80;	
	$benefices = $benefices + 80;	
	}
elseif(($dureea==2) && ($_SESSION['credits']>=190) && $_SESSION['fatigue']>=18)
	{
	$ok = 1;
	$_SESSION['credits'] = $_SESSION['credits'] - 190;
	$sql = 'UPDATE principal_tbl SET action= "En cours de '.$compa.' (2Heures)" WHERE id= "'.$_SESSION['id'].'"' ;
	$req = mysql_query($sql);
	$prix = 150;
	$capital = $capital + 150;	
	$benefices = $benefices + 150;	
	}
elseif(($dureea==3) && ($_SESSION['credits']>=265) && $_SESSION['fatigue']>=27)
	{
	$ok = 1;
	$_SESSION['credits'] = $_SESSION['credits'] - 265;
	$sql = 'UPDATE principal_tbl SET action= "En cours de '.$compa.' (3Heures)" WHERE id= "'.$_SESSION['id'].'"' ;
	$req = mysql_query($sql);
	$prix = 205;
	$capital = $capital + 205;
	$benefices = $benefices + 205;
	}
elseif(($dureea==4) && ($_SESSION['credits']>=350) && $_SESSION['fatigue']>=36)
	{
	$ok = 1;
	$_SESSION['credits'] = $_SESSION['credits'] - 350;
	$sql = 'UPDATE principal_tbl SET action= "En cours de '.$compa.' (4Heures)" WHERE id= "'.$_SESSION['id'].'"' ;
	$req = mysql_query($sql);
	$prix = 270;
	$capital = $capital + 270;	
	$benefices = $benefices + 270;	
	}

if($ok==1)
	{
	$sql = 'UPDATE principal_tbl SET credits= "'.$_SESSION['credits'].'"  WHERE id= "'.$_SESSION['id'].'"';
	$req = mysql_query($sql);
	$sql = 'UPDATE entreprises_tbl SET budget= "'.$capital.'" , benefices= "'.$benefices.'" WHERE type= "CIE"';
	$req = mysql_query($sql);
	$sql = 'INSERT INTO achats_tbl(id,acheteur,vendeur,objet,prix,moment) VALUES("","'.$_SESSION['pseudo'].'","CIE","'.$dureea.'heures de '.$compa.'","'.$prix.'","'.time().'")' ;
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
			CIE
		</div>
		<b class="module4ie"><a href="engine.php" class="module4"><img src="im_objets/icon_retour.gif" alt="Retour" />Retour</a></b>
		</p>
	</div>
</div>
<div id="centre_imperium">

<p id="location">Centre Imperial d'Entra&icirc;nement</p>

<br /><br />

<p id="textelse">

<?php
if($ok==1)
	{
	print('Votre cours de '.$compa.' a commencé.<br /><br />Silence dans la salle !');
	}
else
	{
	print('Vous ne pouvez pas commencer ce cours.');
	}
?>

</p>
</div>
<?php if($chat=="oui") 	{ if ((date("H")>=21) || (date("H")<=7)) { include("inc_bas_n_c.php"); } else { include("inc_bas_j_c.php"); } } else {	if ((date("H")>=21) || (date("H")<=7)) { include("inc_bas_n.php"); } else { include("inc_bas_j.php"); }	} ?>
<?php include("inc_bas_de_page.php"); ?>
