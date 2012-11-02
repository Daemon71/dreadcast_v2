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
			Arrestation
		</div>
		<b class="module4ie"><a href="engine.php" class="module4"><img src="im_objets/icon_retour.gif" alt="Retour" />Retour</a></b>
</p>
	</div>
</div>
<div id="centre">
<p>

<?php	

$db = mysql_connect('localhost', 'CENSURE', 'CENSURE') or die("Erreur de connexion au serveur.");
mysql_select_db('CENSURE',$db) or die("Erreur de connexion a la base de donnees.");

$sql = 'SELECT entreprise FROM principal_tbl WHERE id= "'.$_SESSION['id'].'"' ;
$req = mysql_query($sql);
$_SESSION['entreprise'] = mysql_result($req,0,entreprise);

if(($_SESSION['entreprise']!="Police") && ($_SESSION['entreprise']!="DI2RCO"))
	{
	print('<p align="center"><strong>Il est impossible d\'arreter quelqu\'un car vous n\'&ecirc;tes pas dans les registres de la police.</strong></p>');
	$l = 1;
	}

$idc = $_GET['cible'];

$sql = 'SELECT action FROM principal_tbl WHERE id= "'.$idc.'"' ;
$req = mysql_query($sql);
$actionc = mysql_result($req,0,action);

if(($actionc=="prison") && ($l!=1))
	{
	print('<p align="center">A combien de jours voulez vous fixer la peine de prison ?</p>');
	}
else	
	{
	print('<p align="center">Action impossible !</p>');
	}

mysql_close($db);

?>
</p>
		<p align="center"><form name="form1">
<div align="center">
<select name="menu1" onChange="MM_jumpMenu('parent',this,0)">
<?php 

if(($actionc=="prison") && ($l!=1))
	{
	print('<option selected>Selectionnez ici</option> ');
	print('<option value="engine=ralfin.php?id='.$idc.'&alim=1">1 Jour</option> ');
	print('<option value="engine=ralfin.php?id='.$idc.'&alim=2">2 Jour</option> ');
	print('<option value="engine=ralfin.php?id='.$idc.'&alim=3">3 Jour</option> ');
	print('<option value="engine=ralfin.php?id='.$idc.'&alim=4">4 Jour</option> ');
	}
else
	{
	print('<option selected>Action impossible</option> ');
	}
	
?>
</select>
</div>
</form>

</p>
</div>
<?php if($chat=="oui") 	{ if ((date("H")>=21) || (date("H")<=7)) { include("inc_bas_n_c.php"); } else { include("inc_bas_j_c.php"); } } else {	if ((date("H")>=21) || (date("H")<=7)) { include("inc_bas_n.php"); } else { include("inc_bas_j.php"); }	} ?>
<?php include("inc_bas_de_page.php"); ?>
