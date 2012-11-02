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

if($_SESSION['type'] == "Directeur du DI2RCO" OR $_SESSION['type'] == "Directeur du CIPE" OR $_SESSION['type'] == "Directeur du CIE" OR $_SESSION['type'] == "Directeur des services" OR $_SESSION['type'] == "Directeur des Organisations" OR $_SESSION['type'] == "Directeur de la Prison" OR $_SESSION['type'] == "Chef de la Police" OR $_SESSION['type'] == "Premier consul" OR $_SESSION['type'] == "Directeur du DC Network")
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
			Assurance
		</div>
		<b class="module4ie"><a href="engine=activite.php" class="module4"><img src="im_objets/icon_retour.gif" alt="Retour" />Retour</a></b>
</p>
	</div>
</div>
<div id="centre">
<p>

<em>Une assurance permet &agrave; un PDG de garder son poste malgr&egrave;s un avis de dec&egrave;s. <br />
Souscrire une assurance co&ucirc;te 200 Cr&eacute;dits par jour.<br />
Il est impossible de souscrire plus de 5 jours d'assurance &agrave; la fois.</em>
<br /><br />
<strong><?php if($_SESSION['assurance']>0) { print('Vous avez actuellement une assurance de '.$_SESSION['assurance'].' jours.'); } else { print('Vous n\'avez pas d\'assurance.'); }  ?></strong><br /><br /><hr><br />
<form name="form1" method="post" action="engine=assurancef.php">
	Souscrire <input name="assurance" type="text" id="assurance" size="1" maxlength="1"> jour(s) d'assurance.<br /><br />
	<input type="submit" name="Submit" value="Valider">
	</p>
</form>

</p>
</div>
<?php if($chat=="oui") 	{ if ((date("H")>=21) || (date("H")<=7)) { include("inc_bas_n_c.php"); } else { include("inc_bas_j_c.php"); } } else {	if ((date("H")>=21) || (date("H")<=7)) { include("inc_bas_n.php"); } else { include("inc_bas_j.php"); }	} ?>
<?php include("inc_bas_de_page.php"); ?>
