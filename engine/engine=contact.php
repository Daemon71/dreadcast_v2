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

$sql = 'SELECT id,rue,num,action,alim,sante,fatigue FROM principal_tbl WHERE pseudo= "'.$_SESSION['pseudo'].'"' ;
$req = mysql_query($sql);
$_SESSION['id'] = mysql_result($req,0,id);
$_SESSION['rue'] = mysql_result($req,0,rue);
$_SESSION['num'] = mysql_result($req,0,num);
$_SESSION['action'] = mysql_result($req,0,action);
$_SESSION['alim'] = mysql_result($req,0,alim);
$_SESSION['sante'] = mysql_result($req,0,sante);
$_SESSION['fatigue'] = mysql_result($req,0,fatigue);
$_SESSION['lieu'] = strtolower($_SESSION['rue']);
$_SESSION['lieu'] = ucwords($_SESSION['lieu']);

mysql_close($db);

?>
<?php if ((date("H")>=21) || (date("H")<=7)) { include("inc_haut_n.php"); } else { include("inc_haut_j.php");} ?>

<div id="haut">
	<?php include("inc_entete.php"); ?>
	<div class="haut_aligngauche">
		<p>
		<div class="titrepage">
			Contacter un admin
		</div>
		</p>
	</div>
</div>
<div id="centre">

<strong>Envoyer un message &agrave; un administrateur</strong><br />
<div class="color1" style="font-weight:bold;">Merci de soigner l'orthographe de vos messages.<br />
N'envoyez pas de message si ça n'en vaut pas la peine !<br />
Avant de poser une question, renseignez-vous sur le Wikast.</div>
<br />
<form name="allera" id="allera" method="post" action="engine=envoyeradmin.php">
	Objet <input type="text" name="objet" maxlength="25" size="20" value="<?php if($_GET['annonce']!=0) print('Signal AITL #'.$_GET['annonce']); ?>" /><br />
	Type de requ&ecirc;te <select name="typemess" style="width:150px;">
		<option value="BUG" select="selected">Rapport de bug</option>
		<option value="IDEE">Suggestion</option>
		<option value="MISC">Autre</option>
	</select><br /><br /><textarea name="message" cols="50" rows="7" id="message"></textarea>
	<br />
	<br />
	<input type="submit" name="Submit" value="Envoyer" />
</form>


</div>
<?php if($chat=="oui") 	{ if ((date("H")>=21) || (date("H")<=7)) { include("inc_bas_n_c.php"); } else { include("inc_bas_j_c.php"); } } else {	if ((date("H")>=21) || (date("H")<=7)) { include("inc_bas_n.php"); } else { include("inc_bas_j.php"); }	} ?>
<?php include("inc_bas_de_page.php"); ?>
