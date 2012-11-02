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

$sql = 'SELECT nom,type,ouvert,budget,benefices FROM entreprises_tbl WHERE rue= "'.$_SESSION['lieu'].'" AND num= "'.$_SESSION['num'].'"' ;
$req = mysql_query($sql);
$type = mysql_result($req,0,type);
$noment = mysql_result($req,0,nom);
$ouvert = mysql_result($req,0,ouvert);
$budget = mysql_result($req,0,budget);
$benefices = mysql_result($req,0,benefices);

if(($type!="boutique armes") || ($ouvert=="non"))
	{
	print('<meta http-equiv="refresh" content="0 ; url=http://v2.dreadcast.net/ingame/engine.php"> ');
	exit();
	}

$sql = 'SELECT arme,credits FROM principal_tbl WHERE id= "'.$_SESSION['id'].'"' ;
$req = mysql_query($sql);
$_SESSION['arme'] = mysql_result($req,0,arme);
$_SESSION['credits'] = mysql_result($req,0,credits);

if(ereg("-",$_SESSION['arme']))
	{
	$sqla = 'SELECT usure FROM armes_tbl WHERE idarme= "'.strstr($_SESSION['arme'],"-").'"';
	$reqa = mysql_query($sqla);
	$resa = mysql_num_rows($reqa);
	if($resa>0)
		{
		$usure = mysql_result($reqa,0,usure);
		}
	else
		{
		print('<meta http-equiv="refresh" content="0 ; url=http://v2.dreadcast.net/ingame/engine.php"> ');
		exit();
		}
	}
else
	{
	print('<meta http-equiv="refresh" content="0 ; url=http://v2.dreadcast.net/ingame/engine.php"> ');
	exit();
	}

$p = 0;
for($i=$usure;$i!=100;$i++)
	{
	if($_SESSION['credits']>0)
		{
		$usure = $usure + 1;
		$_SESSION['credits'] = $_SESSION['credits'] - 1;
		$p++;
		}
	}

$budget = $budget + $p;
$benefices = $benefices + $p;

$sql = 'UPDATE entreprises_tbl SET budget= "'.$budget.'" , benefices= "'.$benefices.'" WHERE rue= "'.$_SESSION['lieu'].'" AND num= "'.$_SESSION['num'].'"' ;
$req = mysql_query($sql);

$sql = 'UPDATE principal_tbl SET credits= "'.$_SESSION['credits'].'" WHERE id= "'.$_SESSION['id'].'"' ;
$req = mysql_query($sql);

$sqla = 'UPDATE armes_tbl SET usure= "'.$usure.'" WHERE idarme= "'.strstr($_SESSION['arme'],"-").'"';
$reqa = mysql_query($sqla);

mysql_close($db);
?>
<?php if ((date("H")>=21) || (date("H")<=7)) { include("inc_haut_n.php"); } else { include("inc_haut_j.php");} ?>

<div id="haut">
	<?php include("inc_entete.php"); ?>
	<div class="haut_aligngauche">
		<p>
		<div class="titrepage">
			Boutique d'armes
		</div>
		<b class="module4ie"><a href="engine.php" class="module4"><img src="im_objets/icon_retour.gif" alt="Retour" />Retour</a></b>
	</p>
	</div>
</div>
<div id="centre_armurerie">

	<p id="location">Atelier <span><?php print($noment); ?></span></p>

	<br /><br /><br />
	
	<p id="textelse">Vous venez de r&eacute;parer votre arme (<i><?php print($_SESSION['arme']); ?></i>) de <b><?php print($p); ?>%</b>.<br />
	Cela vous &agrave; donc co&ucirc;t&eacute; <b><?php print($p); ?> Cr&eacute;dits</b>.<br /><br />

	Votre arme est pr&eacute;sent &agrave; <strong><?php print($usure) ?>%</strong> de durabilit&eacute;.</p>


</div>
<?php if($chat=="oui") 	{ if ((date("H")>=21) || (date("H")<=7)) { include("inc_bas_n_c.php"); } else { include("inc_bas_j_c.php"); } } else {	if ((date("H")>=21) || (date("H")<=7)) { include("inc_bas_n.php"); } else { include("inc_bas_j.php"); }	} ?>
<?php include("inc_bas_de_page.php"); ?>
