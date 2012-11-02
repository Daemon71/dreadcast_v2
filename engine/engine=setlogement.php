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

$sql = 'SELECT rue,num FROM principal_tbl WHERE id= "'.$_SESSION['id'].'"' ;
$req = mysql_query($sql);
$_SESSION['ruea'] = mysql_result($req,0,rue); 
$_SESSION['numa'] = mysql_result($req,0,num); 

if(($_SESSION['numa']!=0) && ($_SESSION['ruea']!="Aucune"))
	{
	$sql = 'SELECT nom,code,camera,prix,repos,chat FROM lieu_tbl WHERE rue= "'.$_SESSION['ruea'].'" AND num="'.$_SESSION['numa'].'"' ;
	$req = mysql_query($sql);
	$_SESSION['nom'] = mysql_result($req,0,nom); 
	$_SESSION['digi'] = mysql_result($req,0,code); 
	$_SESSION['camera'] = mysql_result($req,0,camera); 
	$_SESSION['prix'] = mysql_result($req,0,prix); 
	$_SESSION['repos'] = mysql_result($req,0,repos); 
	$_SESSION['chat'] = mysql_result($req,0,chat); 
	$sql = 'SELECT image FROM objets_tbl WHERE nom="'.$_SESSION['nom'].'"' ;
	$req = mysql_query($sql);
	$image = mysql_result($req,0,image); 
	}

mysql_close($db);
?>
<?php if ((date("H")>=21) || (date("H")<=7)) { include("inc_haut_n.php"); } else { include("inc_haut_j.php");} ?>

<div id="haut">
	<?php include("inc_entete.php"); ?>
	<div class="haut_aligngauche">
		<p>
		<div class="titrepage">
			Votre logement
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

$sql = 'SELECT location FROM proprietaire_tbl WHERE rue= "'.$_GET['rue'].'" AND num= "'.$_GET['num'].'" AND pseudo= "'.$_SESSION['pseudo'].'"' ;
$req = mysql_query($sql);
$res = mysql_num_rows($req);

if($res>0)
	{
	if(mysql_result($req,0,location)==0)
		{
		$sql = 'SELECT id FROM principal_tbl WHERE pseudo= "'.$_SESSION['pseudo'].'"' ;
		$req = mysql_query($sql);
		$idp = mysql_result($req,0,id);
		$sql = 'UPDATE principal_tbl SET ruel= "'.$_GET['rue'].'" , numl= "'.$_GET['num'].'" WHERE id= "'.$idp.'"' ;
		$req = mysql_query($sql);
		}
	}

mysql_close($db);
?>

<meta http-equiv="refresh" content="0 ; url=http://v2.dreadcast.net/ingame/engine=logement.php">


</p>
</div>
<?php if($chat=="oui") 	{ if ((date("H")>=21) || (date("H")<=7)) { include("inc_bas_n_c.php"); } else { include("inc_bas_j_c.php"); } } else {	if ((date("H")>=21) || (date("H")<=7)) { include("inc_bas_n.php"); } else { include("inc_bas_j.php"); }	} ?>
<?php include("inc_bas_de_page.php"); ?>
