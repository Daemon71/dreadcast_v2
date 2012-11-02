<?php 
session_start(); 
if($_SESSION['id']=="")
	{
	print('<meta http-equiv="refresh" content="0 ; url=http://v2.dreadcast.net/"> ');
	exit();
	}
if($_SESSION['action']=="mort")
	{
	print('<meta http-equiv="refresh" content="0 ; url=http://v2.dreadcast.net/ingame/engine=mort.php"> ');
	exit();
	}
$_SESSION['sante'] = "100";
$_SESSION['faim'] = "100";
$_SESSION['soif'] = "100";

?>
<?php if ((date("H")>=21) || (date("H")<=7)) { include("inc_haut_n.php"); } else { include("inc_haut_j.php");} ?>

<div id="haut">
	<?php include("inc_entete.php"); ?>
	<div class="haut_aligngauche">
		<p>
		<div class="titrepage">
			Cryogénisation
		</div>
</p>
	</div>
</div>
<div id="centre">
<p>

<?php
$db = mysql_connect('localhost', 'CENSURE', 'CENSURE') or die("Erreur de connexion au serveur des messages.");
mysql_select_db('CENSURE',$db) or die("Erreur de connexion a la base de donnees des messages.");

$sql = 'SELECT action,num FROM principal_tbl WHERE id= "'.$_SESSION['id'].'"';
$req = mysql_query($sql);
$num = mysql_result($req,0,num);
$action = mysql_result($req,0,action);

if($_GET['ok']==1 && $action=="Vacances")
	{
	if($num>=7)
		{
		$_SESSION['action'] = "aucune";
		$sql = 'SELECT num,rue FROM lieux_speciaux_tbl WHERE type="cryo" ORDER BY RAND() LIMIT 1';
		$req = mysql_query($sql);
		$sql = 'UPDATE principal_tbl SET action= "aucune" , num= "'.mysql_result($req,0,num).'" , rue= "'.mysql_result($req,0,rue).'" WHERE id= "'.$_SESSION['id'].'"';
		$req = mysql_query($sql);
		print('<meta http-equiv="refresh" content="0 ; url=http://v2.dreadcast.net/ingame/engine.php"> ');
		exit();
		}
	else
		{
		print('<meta http-equiv="refresh" content="0 ; url=http://v2.dreadcast.net/ingame/engine=cryo.php"> ');
		exit();
		}
	}
elseif($action=="Vacances")
	{
	print('<br /><strong>Votre personnage est cryogénisé.</strong><br />
	Vous êtes dans votre cellule depuis '.$num.' jour(s).<br /><br />');
	if($num<7)
		{
		print('Vous devez attendre 7 jours au minimum pour vous décryogéniser.');
		}
	else
		{
		print('Vous pouvez dès maintenant vous <a href="engine=cryo.php?ok=1">décryogéniser</a>.');
		}
	}

mysql_close($db);
?>

</p>
</div>
<?php if($chat=="oui") 	{ if ((date("H")>=21) || (date("H")<=7)) { include("inc_bas_n_c.php"); } else { include("inc_bas_j_c.php"); } } else {	if ((date("H")>=21) || (date("H")<=7)) { include("inc_bas_n.php"); } else { include("inc_bas_j.php"); }	} ?>
<?php include("inc_bas_de_page.php"); ?>
