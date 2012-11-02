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

$sql = 'SELECT credits,fatigue FROM principal_tbl WHERE id= "'.$_SESSION['id'].'"' ;
$req = mysql_query($sql);
$_SESSION['credits'] = mysql_result($req,0,credits);
$_SESSION['fatigue'] = mysql_result($req,0,fatigue);

$sql = 'SELECT nom,type FROM entreprises_tbl WHERE rue= "'.$_SESSION['lieu'].'" AND num= "'.$_SESSION['num'].'"' ;
$req = mysql_query($sql);
$type = mysql_result($req,0,type);
$noment = mysql_result($req,0,nom);

if($type!="CIE")
	{
	mysql_close($db);
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
			CIE
		</div>
		<b class="module4ie"><a href="engine.php" class="module4"><img src="im_objets/icon_retour.gif" alt="Retour" />Retour</a></b>
		</p>
	</div>
</div>
<div id="centre_imperium">

<p id="location">Centre Imperial d'Entra&icirc;nement</p>

<br /><br />

<p id="textelse">
	<em>Prendre <strong>une heure</strong> de cours augmente une comp&eacute;tence de <strong>4</strong> &agrave; <strong>6</strong> <strong>points</strong>.<br />
	Une fois l'apprentissage d&eacute;but&eacute;, il est impossible de sortir du cours. </em><br /><br /><br />
	<form id="maforme" name="achat" method="post" action="engine=cief.php">
		Comp&eacute;tence &agrave; apprendre
		<select name="compet" id="compet">
			<option value="Combat" selected>Combat</option>
			<option value="Tir">Tir</option>
		</select><br />
		Dur&eacute;e d'apprentissage
		<select name="duree" id="duree">
			<option value="1" selected>1 heure (100Cr&eacute;dits)</option>
<?php   
if($_SESSION['fatigue']>=36)
	{         
	print('<option value="2">2 heures (190Cr&eacute;dits)</option><option value="3">3 heures (265Cr&eacute;dits)</option><option value="4">4 heures (350Cr&eacute;dits)</option>');
	}
elseif($_SESSION['fatigue']>=27)
	{         
	print('<option value="2">2 heures (190Cr&eacute;dits)</option><option value="3">3 heures (265Cr&eacute;dits)</option>');
	}
elseif($_SESSION['fatigue']>=9)
	{         
	print('<option value="2">2 heures (190Cr&eacute;dits)</option>');
	}
?>               
		</select><br /><br />
		<input type="submit" name="Submit" value="Prendre un cours" id="valid">                
	</form>
</p>

</div>
<?php if($chat=="oui") 	{ if ((date("H")>=21) || (date("H")<=7)) { include("inc_bas_n_c.php"); } else { include("inc_bas_j_c.php"); } } else {	if ((date("H")>=21) || (date("H")<=7)) { include("inc_bas_n.php"); } else { include("inc_bas_j.php"); }	} ?>
<?php include("inc_bas_de_page.php"); ?>
