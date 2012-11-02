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

$sql = 'SELECT nom,type FROM entreprises_tbl WHERE rue= "'.$_SESSION['lieu'].'" AND num= "'.$_SESSION['num'].'"' ;
$req = mysql_query($sql);
$type = mysql_result($req,0,type);
$noment = mysql_result($req,0,nom);

if($type!="banque")
	{
	print('<meta http-equiv="refresh" content="0 ; url=http://v2.dreadcast.net/ingame/engine.php"> ');
	exit();
	}

if($_SERVER['QUERY_STRING']!="")
	{
	$code = $_SERVER['QUERY_STRING'];
	}
else
	{
	$code = $_POST['code'];
	}

$sql = 'SELECT mdp FROM comptes_tbl WHERE code= "'.$code.'"' ;
$req = mysql_query($sql);
$res = mysql_num_rows($req);

if($_POST['mdpcomptepost']==mysql_result($req,0,mdp))
	{
	$_SESSION['mdpcompte'] = $_POST['mdpcomptepost'];
	print('<meta http-equiv="refresh" content="0 ; url=http://v2.dreadcast.net/ingame/engine=compte.php?'.$code.'"> ');
	exit();
	}
else
	{
	$mdpc = mysql_result($req,0,mdp);
	}

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
		<b class="module4ie"><a href="engine=banque.php" class="module4"><img src="im_objets/icon_retour.gif" alt="Retour" />Retour</a></b>
		</p>
	</div>
</div>
<div id="centre_banque">

	<p id="location">Cr&eacute;dits en poche : <span><?php print($_SESSION['credits']); ?></span></p>

	<br /><br /><br /><br />
	
	<p id="textelse"><b>Le compte n&deg;<?php print(''.$code.''); ?> est un compte &agrave; acc&eacute;s limit&eacute;.</b><br /><br />
	Vous devez entrer un mot de passe pour pouvoir y acceder.<br /><br />
	<form id="maforme" action="engine=mdpcompte.php?<?php print(''.$code.''); ?>" method="post" name="postage" id="postage">Mot de passe : 
		<input id="champ" tabindex="1" name="mdpcomptepost" type="password" size="15" maxlength="15">
		<input id="valid" tabindex="2" type="submit" name="Submit" value="Valider">
	</form>
	</p>


</div>
<?php if($chat=="oui") 	{ if ((date("H")>=21) || (date("H")<=7)) { include("inc_bas_n_c.php"); } else { include("inc_bas_j_c.php"); } } else {	if ((date("H")>=21) || (date("H")<=7)) { include("inc_bas_n.php"); } else { include("inc_bas_j.php"); }	} ?>
<?php include("inc_bas_de_page.php"); ?>
