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

$sql = 'SELECT * FROM principal_tbl WHERE id= "'.$_SESSION['id'].'"' ;
$req = mysql_query($sql);
$_SESSION['type'] = mysql_result($req,0,type); 
$_SESSION['entreprise'] = mysql_result($req,0,entreprise); 
$_SESSION['salaire'] = mysql_result($req,0,salaire); 
$_SESSION['difficulte'] = mysql_result($req,0,difficulte); 
$_SESSION['points'] = mysql_result($req,0,points); 
$_SESSION['assurance'] = mysql_result($req,0,assurance); 
$assu = $_SESSION['assurance'];

if(($_POST['assurance']>1 && $_POST['assurance']+$_SESSION['assurance']<=5) && ($_SESSION['credits']>200*$_POST['assurance']))
	{
	$_SESSION['credits'] = $_SESSION['credits'] - ( 200 * $_POST['assurance'] );
	$sql = 'UPDATE principal_tbl SET credits= "'.$_SESSION['credits'].'" WHERE id= "'.$_SESSION['id'].'"' ;
	$req = mysql_query($sql);
	$_SESSION['assurance'] = $_SESSION['assurance'] + $_POST['assurance'];
	$sql = 'UPDATE principal_tbl SET assurance= "'.$_SESSION['assurance'].'" WHERE id= "'.$_SESSION['id'].'"' ;
	$req = mysql_query($sql);
	$passe = 1;
	}

mysql_close($db);

?>
<?php if ((date("H")>=21) || (date("H")<=7)) { include("inc_haut_n.php"); } else { include("inc_haut_j.php");} ?>

<div id="haut">
	<?php include("inc_entete.php"); ?>
	<div class="haut_aligngauche">
		<p>
		<div class="titrepage">
			Assurance
		</div>
		<b class="module4ie"><a href="engine=assurance.php" class="module4"><img src="im_objets/icon_retour.gif" alt="Retour" />Retour</a></b>
</p>
	</div>
</div>
<div id="centre">
<p>


<?php
if($_POST['assurance']<0)
	{
	print('Action impossible.');
	}
elseif($_POST['assurance']+$assu>5)
	{
	print('Vous ne pouvez souscrire à plus de 5 jours d\'assurance à la fois.');
	}
elseif($passe==1)
	{
	print('Vous venez de souscrire une assurance de '.$_POST['assurance'].' jour(s).');
	}
else
	{
	print('Vous n\'avez pas assez de crédits sur vous pour souscrire l\'assurance souhaitée.');
	}
?>


</p>
</div>
<?php if($chat=="oui") 	{ if ((date("H")>=21) || (date("H")<=7)) { include("inc_bas_n_c.php"); } else { include("inc_bas_j_c.php"); } } else {	if ((date("H")>=21) || (date("H")<=7)) { include("inc_bas_n.php"); } else { include("inc_bas_j.php"); }	} ?>
<?php include("inc_bas_de_page.php"); ?>
