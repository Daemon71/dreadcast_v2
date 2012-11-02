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

$sql = 'SELECT type,nom,budget,benefices FROM entreprises_tbl WHERE rue= "'.$_SESSION['rue'].'" AND num= "'.$_SESSION['num'].'"' ;
$req = mysql_query($sql);
$type = mysql_result($req,0,type);
$noment = mysql_result($req,0,nom);
$budget = mysql_result($req,0,budget);
$benefices = mysql_result($req,0,benefices);

if($type!="banque")
	{
	print('<meta http-equiv="refresh" content="0 ; url=http://v2.dreadcast.net/ingame/engine.php"> ');
	exit();
	}

$sql = 'SELECT nombre FROM stocks_tbl WHERE entreprise= "'.$noment.'"' ;
$req = mysql_query($sql);
$pourc = mysql_result($req,0,nombre);

$code = $_SERVER['QUERY_STRING'];

$sql = 'SELECT credits FROM principal_tbl WHERE id= "'.$_SESSION['id'].'"' ;
$req = mysql_query($sql);
$_SESSION['credits'] = mysql_result($req,0,credits);

$sql = 'SELECT credits,mdp FROM comptes_tbl WHERE code= "'.$code.'"' ;
$req = mysql_query($sql);
$solde = mysql_result($req,0,credits);

if(mysql_result($req,0,mdp)!=$_SESSION['mdpcompte'])
	{
	print('<meta http-equiv="refresh" content="0 ; url=http://v2.dreadcast.net/ingame/engine=mdpcompte.php?'.$code.'"> ');
	exit();
	}

$dep = $_POST['montant'];

if(($_SESSION['credits']<$dep) || ($dep<=0))
	{
	print('<meta http-equiv="refresh" content="0 ; url=http://v2.dreadcast.net/ingame/engine=depotc.php?'.$code.'"> ');
	exit();
	}

$gain = floor( ($pourc/100) * $dep );
$solde = $solde + floor( $dep - (($pourc/100) * $dep) );
$depo = floor( $dep - (($pourc/100) * $dep) );

$benefices = $benefices + $gain;
$budget = $budget + $gain;

$_SESSION['credits'] = $_SESSION['credits'] - $dep;

$sql = 'UPDATE principal_tbl SET credits= "'.$_SESSION['credits'].'" WHERE id= "'.$_SESSION['id'].'"' ;
$req = mysql_query($sql);
$sql = 'UPDATE entreprises_tbl SET budget= "'.$budget.'" , benefices= "'.$benefices.'" WHERE nom= "'.$noment.'"' ;
$req = mysql_query($sql);
$sql = 'UPDATE comptes_tbl SET credits= "'.$solde.'" WHERE code= "'.$code.'"' ;
$req = mysql_query($sql);

$sql = 'INSERT INTO comptes_acces_tbl(id,pseudo,compte,time,operation,valeur) VALUES("","'.$_SESSION['pseudo'].'","'.$code.'","'.time().'","Depot argent","'.$depo.'")' ;
mysql_query($sql);

mysql_close($db);
?>
<?php if ((date("H")>=21) || (date("H")<=7)) { include("inc_haut_n.php"); } else { include("inc_haut_j.php");} ?>

<div id="haut">
	<?php include("inc_entete.php"); ?>
	<div class="haut_aligngauche">
		<p>
		<div class="titrepage">
			Banque
		</div>
		<b class="module4ie"><a href="engine=compte.php?<?php print(''.$code.''); ?>" class="module4"><img src="im_objets/icon_retour.gif" alt="Retour" />Retour</a></b>
		</p>
	</div>
</div>
<div id="centre_banque">

	<p id="location">Cr&eacute;dits en poche : <span><?php print($_SESSION['credits']); ?></span></p>

	<br /><br /><br />
	
	<p id="textelse">Vous avez d&eacute;pos&eacute; <b><?php print(''.$dep.''); ?></b> Cr&eacute;dits.<br /><br />
	La banque pr&eacute;l&egrave;ve <b><?php print(''.$gain.''); ?></b> Cr&eacute;dits<br /><br />
	Votre compte est accr&eacute;dit&eacute; de <b><?php print(''.$depo.''); ?></b> Cr&eacute;dits<br /><br />
	Votre nouveau solde est de <b><?php print(''.$solde.''); ?></b> Cr&eacute;dits.</p>
	
</div>
<?php if($chat=="oui") 	{ if ((date("H")>=21) || (date("H")<=7)) { include("inc_bas_n_c.php"); } else { include("inc_bas_j_c.php"); } } else {	if ((date("H")>=21) || (date("H")<=7)) { include("inc_bas_n.php"); } else { include("inc_bas_j.php"); }	} ?>
<?php include("inc_bas_de_page.php"); ?>
