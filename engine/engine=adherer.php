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

$sql = 'SELECT capital,cotisation,nom FROM cerclesliste_tbl WHERE num= "'.$_SESSION['num'].'" AND rue= "'.$_SESSION['rue'].'"' ;
$req = mysql_query($sql);
$res = mysql_num_rows($req);
if($res==0)
	{
	print('<meta http-equiv="refresh" content="0 ; url=http://v2.dreadcast.net/ingame/engine.php"> ');
	exit();
	}
else
	{
	$nomcercle = mysql_result($req,0,nom); 
	$capital = mysql_result($req,0,capital); 
	$cotisation = mysql_result($req,0,cotisation);
	}

$sql = 'SELECT credits FROM principal_tbl WHERE id= "'.$_SESSION['id'].'"' ;
$req = mysql_query($sql);
$_SESSION['credits'] = mysql_result($req,0,credits); 

$sql = 'SELECT cercle FROM cercles_tbl WHERE pseudo= "'.$_SESSION['pseudo'].'"' ;
$req = mysql_query($sql);
$rescercle = mysql_num_rows($req);
if($rescercle>0)
	{
	print('<meta http-equiv="refresh" content="0 ; url=http://v2.dreadcast.net/ingame/engine.php"> ');
	exit();
	}

mysql_close($db);
?>
<?php if ((date("H")>=21) || (date("H")<=7)) { include("inc_haut_n.php"); } else { include("inc_haut_j.php");} ?>

<div id="haut">
	<?php include("inc_entete.php"); ?>
	<div class="haut_aligngauche">
		<p>
		<div class="titrepage">
			Adhérer à un cercle
		</div>
		<b class="module4ie"><a href="engine.php" class="module4"><img src="im_objets/icon_retour.gif" alt="Retour" />Retour</b></a>
		</p>
	</div>
</div>
<div id="centre">
<p>

<?php

if($_GET['ok']==1)
	{
	if($_SESSION['credits']>=$cotisation)
		{
		$db = mysql_connect('localhost', 'CENSURE', 'CENSURE') or die("Erreur de connexion au serveur des messages.");
		mysql_select_db('CENSURE',$db) or die("Erreur de connexion a la base de donnees des messages.");
		
		$capital = $capital + $cotisation;
		$sql = 'UPDATE cerclesliste_tbl SET capital= "'.$capital.'" WHERE num= "'.$_SESSION['num'].'" AND rue= "'.$_SESSION['rue'].'"' ;
		$req = mysql_query($sql);
		
		$_SESSION['credits'] = $_SESSION['credits'] - $cotisation;
		$sql = 'UPDATE principal_tbl SET credits= "'.$_SESSION['credits'].'" WHERE id= "'.$_SESSION['id'].'"' ;
		$req = mysql_query($sql);

		$sql = 'SELECT poste FROM `c_'.str_replace(" ","_",''.$nomcercle.'').'_tbl` WHERE id= "2"';
		$req = mysql_query($sql);
		$postevoulu = mysql_result($req,0,poste);
		
		$sql = 'INSERT INTO cercles_tbl(id,pseudo,cercle,poste) VALUES("","'.$_SESSION['pseudo'].'","'.$nomcercle.'","'.$postevoulu.'")';
		mysql_query($sql);
		
		mysql_close($db);
		print('<strong>Vous venez d\'adhérer au cercle '.$nomcercle.' au poste de '.$postevoulu.'.</strong><br />Vous pouvez dès à présent accéder à votre interface de cercle via l\'onglet Stats/Cercle.<br /><br />Bonne continuation !');
		}
	else
		{
		print('Vous n\'avez pas assez de Crédits sur vous pour adhérer.');
		}
	}
else
	{
	print('<strong>Vous êtes sur le point d\'adhérer au cercle '.$nomcercle.'.</strong><br /><br />La cotisation d\'entrée est de '.$cotisation.' Crédits.<br />Êtes-vous sûr de vouloir adhérer ?<br /><br /><a href="engine=adherer.php?ok=1">Oui</a> - <a href="engine.php">Non</a>');
	}
	
?>

</p>
</div>
<?php if($chat=="oui") 	{ if ((date("H")>=21) || (date("H")<=7)) { include("inc_bas_n_c.php"); } else { include("inc_bas_j_c.php"); } } else {	if ((date("H")>=21) || (date("H")<=7)) { include("inc_bas_n.php"); } else { include("inc_bas_j.php"); }	} ?>
<?php include("inc_bas_de_page.php"); ?>
