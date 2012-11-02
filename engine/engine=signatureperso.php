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
?>
<?php if ((date("H")>=21) || (date("H")<=7)) { include("inc_haut_n.php"); } else { include("inc_haut_j.php");} ?>

<div id="haut">
	<?php include("inc_entete.php"); ?>
	<div class="haut_aligngauche">
		<p>
		<div class="titrepage">
			Changer de signature
		</div>
		<b class="module4ie"><a href="engine=infosperso.php" class="module4"><img src="im_objets/icon_retour.gif" alt="Retour" />Retour</a></b>
</p>
	</div>
</div>
<div id="centre">
<p>

<?php
$db = mysql_connect('localhost', 'CENSURE', 'CENSURE') or die("Erreur de connexion au serveur des messages.");
mysql_select_db('CENSURE',$db) or die("Erreur de connexion a la base de donnees des messages.");

if(statut($_SESSION['statut'])<2)
	{
	mysql_close($db);
	print('<meta http-equiv="refresh" content="0 ; url=http://v2.dreadcast.net/ingame/engine=infosperso.php"> ');
	exit();
	}

$sql = 'SELECT signature FROM signaturesperso_tbl WHERE pseudo= "'.$_SESSION['pseudo'].'"';
$req = mysql_query($sql);
$res = mysql_num_rows($req);

if($res>0)
	{
	$signature = mysql_result($req,0,signature);
	}
else
	{
	$sql = 'INSERT INTO signaturesperso_tbl(id,pseudo,signature) VALUES ("","'.$_SESSION['pseudo'].'","")';
	$req = mysql_query($sql);
	}

mysql_close($db);
?>
Votre statut vous donne le droit de posséder une signature personnelle.<br />
Elle s'inscrit à la fin de tous vos messages.<br />
<form name="form1" method="post" action="engine=signaturepersof.php">
  <textarea name="signature" cols="40" rows="4"><?php print(''.str_replace("<br />","\n",''.$signature.'').''); ?></textarea>
  <br />
  <input type="submit" name="Submit" value="Valider">
</form>


</p>
</div>
<?php if($chat=="oui") 	{ if ((date("H")>=21) || (date("H")<=7)) { include("inc_bas_n_c.php"); } else { include("inc_bas_j_c.php"); } } else {	if ((date("H")>=21) || (date("H")<=7)) { include("inc_bas_n.php"); } else { include("inc_bas_j.php"); }	} ?>
<?php include("inc_bas_de_page.php"); ?>
