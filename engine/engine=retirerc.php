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

$sql = 'SELECT type,nom FROM entreprises_tbl WHERE rue= "'.$_SESSION['rue'].'" AND num= "'.$_SESSION['num'].'"' ;
$req = mysql_query($sql);
$type = mysql_result($req,0,type);
$noment = mysql_result($req,0,nom);

if($type!="banque")
	{
	print('<meta http-equiv="refresh" content="0 ; url=http://v2.dreadcast.net/ingame/engine.php"> ');
	exit();
	}

$code = $_SERVER['QUERY_STRING'];

$sql = 'SELECT credits,mdp FROM comptes_tbl WHERE code= "'.$code.'"' ;
$req = mysql_query($sql);
$solde = mysql_result($req,0,credits);

if($solde<=0)
	{
	print('<meta http-equiv="refresh" content="0 ; url=http://v2.dreadcast.net/ingame/engine=compte.php?'.$code.'"> ');
	exit();
	}

if(mysql_result($req,0,mdp)!=$_SESSION['mdpcompte'])
	{
	print('<meta http-equiv="refresh" content="0 ; url=http://v2.dreadcast.net/ingame/engine=mdpcompte.php?'.$code.'"> ');
	exit();
	}

$sql = 'SELECT credits FROM principal_tbl WHERE id= "'.$_SESSION['id'].'"' ;
$req = mysql_query($sql);
$_SESSION['credits'] = mysql_result($req,0,credits);

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
	
	<br /><br /><br /><br /><br />
	
	<p id="textelse">Vous disposez de <b><?php print(''.$solde.''); ?></b> <em>Cr&eacute;dits</em> sur ce compte.<br />
	<em>Combien voulez-vous retirer ? </em><br /><br />
	<form id="maforme" name="form1" method="post" action="engine=retirercf.php?<?php print(''.$code.''); ?>">
		<input id="champ" tabindex="1" name="montant" type="text" id="montant" size="4" maxlength="4"> Cr&eacute;dits<br />
		<input id="valid" tabindex="2" type="submit" name="Submit" value="Valider">
	</form>
	</p>


</div>
<?php if($chat=="oui") 	{ if ((date("H")>=21) || (date("H")<=7)) { include("inc_bas_n_c.php"); } else { include("inc_bas_j_c.php"); } } else {	if ((date("H")>=21) || (date("H")<=7)) { include("inc_bas_n.php"); } else { include("inc_bas_j.php"); }	} ?>
<?php include("inc_bas_de_page.php"); ?>
