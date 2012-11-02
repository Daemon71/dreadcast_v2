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
if($_SESSION['sexe']=="Masculin")
	{
	$sexea = "m";
	}
elseif($_SESSION['sexe']=="Feminin")
	{
	$sexea = "f";	
	}
?>
<?php if ((date("H")>=21) || (date("H")<=7)) { include("inc_haut_n.php"); } else { include("inc_haut_j.php");} ?>

<div id="haut">
	<?php include("inc_entete.php"); ?>
	<div class="haut_aligngauche">
		<p>
		<div class="titrepage">
			Choisissez votre avatar
		</div>
		<b class="module4ie"><a href="engine=stats.php" class="module4"><img src="im_objets/icon_retour.gif" alt="Retour" />Retour</a></b>
</p>
	</div>
</div>
<div id="centre">
<p>

<?php 

if($_GET['ok']==1)
	{
	$db = mysql_connect('localhost', 'CENSURE', 'CENSURE') or die("Erreur de connexion au serveur des messages.");
	mysql_select_db('CENSURE',$db) or die("Erreur de connexion a la base de donnees des messages.");
	
	$sql = 'UPDATE principal_tbl SET avatar= "'.$_SESSION['avatarsend'].'" WHERE pseudo= "'.$_SESSION['pseudo'].'"';
	$req = mysql_query($sql);
	
	mysql_close($db);
	
	print('Votre avatar est maintenant installé.');
	}
elseif($_POST['avatarsend']!="")
	{
	print('Voici l\'avatar que donnerait l\'image que vous avez entré :<br /><br />');
	$_SESSION['avatarsend'] = $_POST['avatarsend'];
	print('<img src="'.$_POST['avatarsend'].'" width="70" border="1px" /><br /><br />');
	print('Le voulez-vous ?<br /><br /><a href="engine=sendavatar.php?ok=1">Oui</a> - <a href="engine=avatars.php">Non</a>');
	}
else
	{
	print('<i>Vous devez entrer un lien internet vers l\'image que vous désirez.</i>');
	}

?>


</p>
</div>
<?php if($chat=="oui") 	{ if ((date("H")>=21) || (date("H")<=7)) { include("inc_bas_n_c.php"); } else { include("inc_bas_j_c.php"); } } else {	if ((date("H")>=21) || (date("H")<=7)) { include("inc_bas_n.php"); } else { include("inc_bas_j.php"); }	} ?>
<?php include("inc_bas_de_page.php"); ?>
