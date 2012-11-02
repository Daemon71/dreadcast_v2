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


$_SESSION['page1']="";

if($type!="jeux")
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
			Impériale des jeux
		</div>
		<b class="module4ie"><a href="engine.php" class="module4"><img src="im_objets/icon_retour.gif" alt="Retour" />Retour</a></b>
		</p>
	</div>
</div>
<div id="centre_imperium">

<p id="location">Bienvenue au <span>Casino de la ville</span></p>


<p id="textelse">
	Voici la liste des jeux disponibles en ce moment:<br /><br /><br /><br />
	<table style="border:1px solid #6E6E6E;" cellpadding="5px">
	<tr style="background:#666;border:1px solid #6E6E6E;">
		<th>Nom du jeu</th>
		<th>Description</th>
	</tr>
	<tr style="border:1px solid #6E6E6E;">
		<td><a href="engine=machine2.php">Machine à sous</a></td>
		<td>Possibilité de gagner jusqu'à 1 000 Cr&eacute;dits<br /> par combinaison.<br /> 
		La partie coûte 20 Cr&eacute;dits.</td>
	</tr>
	<tr style="border:1px solid #6E6E6E;">
		<td><a href="engine=videopoker.php">Video poker</a></td>
		<td>Possibilité de gagner jusqu'à 5 000 Cr&eacute;dits<br /> par combinaison.<br /> 
		La partie coûte 20 Cr&eacute;dits.</td>
	</tr>
	
</table>

</p>
</div>
<?php if($chat=="oui") 	{ if ((date("H")>=21) || (date("H")<=7)) { include("inc_bas_n_c.php"); } else { include("inc_bas_j_c.php"); } } else {	if ((date("H")>=21) || (date("H")<=7)) { include("inc_bas_n.php"); } else { include("inc_bas_j.php"); }	} ?>
<?php include("inc_bas_de_page.php"); ?>
