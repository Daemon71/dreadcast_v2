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

$sql = 'SELECT nom,type FROM entreprises_tbl WHERE rue= "'.$_SESSION['lieu'].'" AND num= "'.$_SESSION['num'].'"' ;
$req = mysql_query($sql);
$type = mysql_result($req,0,type);
$noment = mysql_result($req,0,nom);

if($type!="banque")
	{
	print('<meta http-equiv="refresh" content="0 ; url=http://v2.dreadcast.net/ingame/engine.php"> ');
	exit();
	}

$sql = 'SELECT nombre FROM stocks_tbl WHERE entreprise= "'.$noment.'"' ;
$req = mysql_query($sql);
$pourc = mysql_result($req,0,nombre);

$_SESSION['mdpcompte'] = "";

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
		<b class="module4ie"><a href="engine.php" class="module4"><img src="im_objets/icon_retour.gif" alt="Retour" />Retour</a></b>
		</p>
	</div>
</div>
<div id="centre_banque">

	<form  id="location" name="form1" method="post" action="engine=compte.php">
			<p id="champ">Acc&eacute;der au compte&nbsp;&nbsp;&nbsp;<input tabindex="1" name="code" type="password" id="code" size="8" maxlength="8" /></p> <p id="valid"><input tabindex="2" type="submit" name="Submit" value="Valider" /></p>
	</form>

	<br /><br /><br />
	<?php 
	if($_GET['delais']==1)
		{
		print('<p id="textelse"><strong>Vous devez attendre.</strong><br /><br />L\'intervalle minimum entre deux essais est de 10 secondes.</p>');
		}
	else
		{
		print('<p id="textelse">Rappels :<br /><br />La banque <strong>'.$noment.'</strong> retient <strong>'.$pourc.'%</strong> du montant par d&eacute;p&ocirc;t.<br /><br />
	Ouvrir un compte co&ucirc;te <em>50 Cr&eacute;dits</em>.<br />
	D&eacute;poser un objet dans votre compte co&ucirc;te <em>15 Cr&eacute;dits</em>.<br />
	Acheter un nouvel emplacement dans votre compte co&ucirc;te <em>60 Cr&eacute;dits</em>.<br /><br />
	La somme d&eacute;pos&eacute;e en banque augmente de 2% chaque semaine.</p>');
		}
	?>
</div>
<?php if($chat=="oui") 	{ if ((date("H")>=21) || (date("H")<=7)) { include("inc_bas_n_c.php"); } else { include("inc_bas_j_c.php"); } } else {	if ((date("H")>=21) || (date("H")<=7)) { include("inc_bas_n.php"); } else { include("inc_bas_j.php"); }	} ?>
<?php include("inc_bas_de_page.php"); ?>
