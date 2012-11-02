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
			Mettre en location
		</div>
		</p>
	</div>
</div>
<div id="centre">
<p>

<img src="im_objets/loader.gif" alt="..." />
<?php
$db = mysql_connect('localhost', 'CENSURE', 'CENSURE') or die("Erreur de connexion au serveur des messages.");
mysql_select_db('CENSURE',$db) or die("Erreur de connexion a la base de donnees des messages.");

$sql = 'SELECT * FROM proprietaire_tbl WHERE pseudo= "'.$_SESSION['pseudo'].'" AND rue= "'.$_GET['rue'].'" AND num= "'.$_GET['num'].'"' ;
$req = mysql_query($sql);
$res = mysql_num_rows($req);

if($_POST['nouveau']<0)
	{
	$nouveauc = 0;
	}
else
	{
	$nouveauc = $_POST['nouveau'];
	}

if($res!=0)
	{
	if($_POST['cand']=="cand")
		{
		$sql = 'UPDATE proprietaire_tbl SET location= "'.$nouveauc.'" , cand= "oui" WHERE pseudo= "'.$_SESSION['pseudo'].'" AND rue= "'.$_GET['rue'].'" AND num= "'.$_GET['num'].'"' ;
		}
	else
		{
		$sql = 'UPDATE proprietaire_tbl SET location= "'.$nouveauc.'" , cand= "non" WHERE pseudo= "'.$_SESSION['pseudo'].'" AND rue= "'.$_GET['rue'].'" AND num= "'.$_GET['num'].'"' ;
		}
	$req = mysql_query($sql);
	print('<meta http-equiv="refresh" content="0 ; url=http://v2.dreadcast.net/ingame/engine=logement.php?rue='.$_GET['rue'].'&num='.$_GET['num'].'"> ');
	}
else
	{
	print('<meta http-equiv="refresh" content="0 ; url=http://v2.dreadcast.net/ingame/engine=logement.php"> ');
	}

mysql_close($db);
?>

</p>
</div>
<?php if($chat=="oui") 	{ if ((date("H")>=21) || (date("H")<=7)) { include("inc_bas_n_c.php"); } else { include("inc_bas_j_c.php"); } } else {	if ((date("H")>=21) || (date("H")<=7)) { include("inc_bas_n.php"); } else { include("inc_bas_j.php"); }	} ?>
<?php include("inc_bas_de_page.php"); ?>
