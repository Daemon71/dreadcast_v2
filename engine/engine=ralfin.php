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

$idc = $_GET['id'];
$alim = $_GET['alim'];

$sql = 'SELECT action FROM principal_tbl WHERE id= "'.$idc.'"' ;
$req = mysql_query($sql);
$actionc = mysql_result($req,0,action);

if(($actionc=="prison") && ($l!=1))
	{
	if($alim==1)
		{
		print('<p align="center"><strong>Tout est en règle.</strong> <br><br>L\'individu vient d\'être placé en prison pour 1 Jour.</p>');
		$sql = 'UPDATE principal_tbl SET alim="1" WHERE id= "'.$idc.'"' ;
		$req = mysql_query($sql);
		}
	elseif($alim<=4)
		{
		print('<p align="center"><strong>Tout est en règle.</strong> <br><br>L\'individu vient d\'être placé en prison pour '.$alim.' Jours.</p>');
		$sql = 'UPDATE principal_tbl SET alim="'.$alim.'" WHERE id= "'.$idc.'"' ;
		$req = mysql_query($sql);
		}
	}
else	
	{
	print('<p align="center">Action impossible !</p>');
	}

mysql_close($db);

?>

</p>
</div>
<?php if($chat=="oui") 	{ if ((date("H")>=21) || (date("H")<=7)) { include("inc_bas_n_c.php"); } else { include("inc_bas_j_c.php"); } } else {	if ((date("H")>=21) || (date("H")<=7)) { include("inc_bas_n.php"); } else { include("inc_bas_j.php"); }	} ?>
<?php include("inc_bas_de_page.php"); ?>
