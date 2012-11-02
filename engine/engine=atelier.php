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

$sql = 'SELECT nom,type,ouvert FROM entreprises_tbl WHERE rue= "'.$_SESSION['lieu'].'" AND num= "'.$_SESSION['num'].'"' ;
$req = mysql_query($sql);
$type = mysql_result($req,0,type);
$noment = mysql_result($req,0,nom);
$ouvert = mysql_result($req,0,ouvert);

if(($type!="boutique armes") || ($ouvert=="non"))
	{
	print('<meta http-equiv="refresh" content="0 ; url=http://v2.dreadcast.net/ingame/engine.php"> ');
	exit();
	}

$sql = 'SELECT arme FROM principal_tbl WHERE id= "'.$_SESSION['id'].'"' ;
$req = mysql_query($sql);
$_SESSION['arme'] = mysql_result($req,0,arme);

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

$cout = 100 - $usure;

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

	<br />
	
	<p id="textelse">Vous entrez dans l'atelier de la boutique <b><?php print($noment); ?>.</b><br /><br />
	Le prix de r&eacute;paration d'une arme est de <b>1 Crédit</b> par point de durabilité.<br /><br />

	Votre arme (<i><?php print($_SESSION['arme']); ?></i>) est à <b><?php print($usure); ?>%</b> de durabilité.<br />
	Cela coûtera donc <b><?php print($cout); ?> Crédits</b> de la faire réparer.<br /><br />

	Si vous ne possédez pas la totalité de cette somme,<br />
	Cliquez tout de même sur le lien suivant.<br /><br />

	<a href="engine=atelierf.php">Faire réparer votre arme</a></p>


</div>
<?php if($chat=="oui") 	{ if ((date("H")>=21) || (date("H")<=7)) { include("inc_bas_n_c.php"); } else { include("inc_bas_j_c.php"); } } else {	if ((date("H")>=21) || (date("H")<=7)) { include("inc_bas_n.php"); } else { include("inc_bas_j.php"); }	} ?>
<?php include("inc_bas_de_page.php"); ?>
