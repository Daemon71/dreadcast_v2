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
			Carnet d'adresses
		</div>
		<div align="center">(<a href="engine.php">Retour &agrave; la Situation Actuelle</a>)<br />
			(<a href="engine=carnet.php">Retour au Carnet d'adresses</a>)
		</div>
		</p>
	</div>
</div>
<div id="centre">
<p>


<?php  
$db = mysql_connect('localhost', 'CENSURE', 'CENSURE') or die("Erreur de connexion au serveur des messages.");
mysql_select_db('CENSURE',$db) or die("Erreur de connexion a la base de donnees des messages.");

$sqlb = 'SELECT id FROM principal_tbl WHERE pseudo="'.$_POST["contact"].'"' ;
$reqb = mysql_query($sqlb);
$resb = mysql_num_rows($reqb);
if($resb!=0)
	{
	$sql = 'INSERT INTO carnets_tbl(id,pseudo,contact,statut,note) VALUES("","'.$_SESSION['pseudo'].'","'.$_POST["contact"].'","'.$_POST["statutc"].'","")' ;
	$req = mysql_query($sql);
	print(''.$_POST["contact"].' a &eacute;t&eacute; correctement ajout&eacute; &agrave; votre carnet d\'adresses.<br> ');
	}
else
	{
	print('Personne ne s\'appelle <i>'.$_POST["contact"].'</i>.<br> ');
	}

mysql_close($db);
	
?>


</p>
</div>
<?php if($chat=="oui") 	{ if ((date("H")>=21) || (date("H")<=7)) { include("inc_bas_n_c.php"); } else { include("inc_bas_j_c.php"); } } else {	if ((date("H")>=21) || (date("H")<=7)) { include("inc_bas_n.php"); } else { include("inc_bas_j.php"); }	} ?>
<?php include("inc_bas_de_page.php"); ?>
