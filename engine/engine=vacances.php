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
<br />

<?php if ((date("H")>=21) || (date("H")<=7)) { include("inc_haut_n.php"); } else { include("inc_haut_j.php");} ?>

<div id="haut">
	<?php include("inc_entete.php"); ?>
	<div class="haut_aligngauche">
		<p>
		<div class="titrepage">
			Cryogénisation
		</div>
		<b class="module4ie"><a href="engine=infosperso.php" class="module4"><img src="im_objets/icon_retour.gif" alt="Retour" />Retour</a></b>
</p>
	</div>
</div>
<div id="centre">
<p>

<?php
if(ereg("engine=vacances.php",$_SERVER['PHP_SELF']) AND $_POST['ok']==1)
	{
	$db = mysql_connect('localhost', 'CENSURE', 'CENSURE') or die("Erreur de connexion au serveur des messages.");
	mysql_select_db('CENSURE',$db) or die("Erreur de connexion a la base de donnees des messages.");
	$sql = 'UPDATE principal_tbl SET numl= "0" , ruel= "Aucune" , entreprise= "Aucune" , type= "Aucun" , points= "0" , nouveau= "non" , Police= "0" , DI2RCO= "0" , difficulte= "0" , action= "Vacances" , num= "1" , rue= "Vacances" , sante=sante_max , fatigue=fatigue_max , soif= "100" , faim= "100" WHERE id= "'.$_SESSION['id'].'"';
	$req = mysql_query($sql);
	$sql = 'DELETE FROM cercles_tbl WHERE pseudo= "'.$_SESSION['pseudo'].'"';
	$req = mysql_query($sql);
	$_SESSION['action'] = "Vacances";
	$_SESSION['lieu'] = "Vacances";
	$_SESSION['num'] = "1";
	print('<meta http-equiv="refresh" content="0 ; url=http://v2.dreadcast.net/ingame/engine=cryo.php"> ');
	exit();
	mysql_close($db);
	}
else
	{
	print('<br /><strong>Vous pouvez placer votre personnage dans une cellule cryogénique.</strong><br />
	Cela ne coute rien et permet à votre personnage de rester en vie durant une periode de 7 à 100 jours.<br /><br />Faites cependant attention ! La cryogénisation de votre personnage entrainera la perte du travail, du cercle et la remise à zéro de vos indices de recherche.<br /><br />
	<form action="engine=vacances.php" method="post"><input type="submit" value="Valider la cryogénisation" /><input type="hidden" name="ok" value="1" /></form>');
	}
?>

</p>
</div>
<?php if($chat=="oui") 	{ if ((date("H")>=21) || (date("H")<=7)) { include("inc_bas_n_c.php"); } else { include("inc_bas_j_c.php"); } } else {	if ((date("H")>=21) || (date("H")<=7)) { include("inc_bas_n.php"); } else { include("inc_bas_j.php"); }	} ?>
<?php include("inc_bas_de_page.php"); ?>
