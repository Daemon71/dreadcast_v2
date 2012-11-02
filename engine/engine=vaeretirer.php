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

$sql = 'SELECT nom,type FROM entreprises_tbl WHERE rue= "'.$_SESSION['lieu'].'" AND num= "'.$_SESSION['num'].'"' ;
$req = mysql_query($sql);
$type = mysql_result($req,0,type);
$noment = mysql_result($req,0,nom);

if($type!="ventes aux encheres")
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
			Ench&egrave;res gagn&eacute;es 
		</div>
		<b class="module4ie"><a href="engine.php" class="module4"><img src="im_objets/icon_retour.gif" alt="Retour" />Retour</a></b>
		</p>
	</div>
</div>
<div id="centre_encheres">

<p id="location"></p>

<p id="textelse">
Veuillez patienter...
<?php                  
$db = mysql_connect('localhost', 'CENSURE', 'CENSURE') or die("Erreur de connexion au serveur des messages.");
mysql_select_db('CENSURE',$db) or die("Erreur de connexion a la base de donnees des messages.");

$sql = 'SELECT case1,case2,case3,case4,case5,case6,credits FROM principal_tbl WHERE id= "'.$_SESSION['id'].'"' ;
$req = mysql_query($sql);
$_SESSION['credits'] = mysql_result($req,0,credits);
$_SESSION['case1'] = mysql_result($req,0,case1);
$_SESSION['case2'] = mysql_result($req,0,case2);
$_SESSION['case3'] = mysql_result($req,0,case3);
$_SESSION['case4'] = mysql_result($req,0,case4);
$_SESSION['case5'] = mysql_result($req,0,case5);
$_SESSION['case6'] = mysql_result($req,0,case6);

$sql = 'SELECT objet FROM vente_tbl WHERE enchere= "0" AND buyout= "0" AND acheteur= "'.$_SESSION['pseudo'].'" AND id= "'.$_GET['id'].'"' ;
$req = mysql_query($sql);
$res = mysql_num_rows($req);

if($res>0)
	{
	if(($_SESSION['case1']!="Vide") && ($_SESSION['case2']!="Vide") && ($_SESSION['case3']!="Vide") && ($_SESSION['case4']!="Vide") && ($_SESSION['case5']!="Vide") && ($_SESSION['case6']!="Vide"))
		{
		print("<center><strong>Vous n'avez pas d'emplacement vide dans votre inventaire.</strong></center>");
		exit();
		}
	for($i=1; $i != 7; $i++)
		{
		if(($_SESSION['case'.$i.'']=="Vide") && ($l!=1))
			{
			$sql = 'UPDATE principal_tbl SET case'.$i.'= "'.mysql_result($req,0,objet).'" WHERE id= "'.$_SESSION['id'].'"' ;
			$req = mysql_query($sql);
			$sql = 'DELETE FROM vente_tbl WHERE id= "'.$_GET['id'].'"' ;
			$req = mysql_query($sql);
			$l = 1;
			}
		}
	}

print('<meta http-equiv="refresh" content="0 ; url=http://v2.dreadcast.net/ingame/engine=inventaire.php">');

mysql_close($db);

?>

</p>
</div>
<?php if($chat=="oui") 	{ if ((date("H")>=21) || (date("H")<=7)) { include("inc_bas_n_c.php"); } else { include("inc_bas_j_c.php"); } } else {	if ((date("H")>=21) || (date("H")<=7)) { include("inc_bas_n.php"); } else { include("inc_bas_j.php"); }	} ?>
<?php include("inc_bas_de_page.php"); ?>
