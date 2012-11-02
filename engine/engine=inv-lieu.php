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

$sql = 'SELECT objet,arme,case1,case2,case3,case4,case5,case6 FROM principal_tbl WHERE id= "'.$_SESSION['id'].'"' ;
$req = mysql_query($sql);
$_SESSION['objet'] = mysql_result($req,0,objet); 
$_SESSION['arme'] = mysql_result($req,0,arme); 
$_SESSION['case1'] = mysql_result($req,0,case1); 
$_SESSION['case2'] = mysql_result($req,0,case2); 
$_SESSION['case3'] = mysql_result($req,0,case3); 
$_SESSION['case4'] = mysql_result($req,0,case4); 
$_SESSION['case5'] = mysql_result($req,0,case5); 
$_SESSION['case6'] = mysql_result($req,0,case6); 

mysql_close($db);

if($_SERVER['QUERY_STRING']=="arme")
	{
	$emplacement = "arme";
	}
elseif($_SERVER['QUERY_STRING']=="objet")
	{
	$emplacement = "objet";
	}
elseif($_SERVER['QUERY_STRING']!="")
	{
	$emplacement = $_SERVER['QUERY_STRING'];
	}
else
	{
	print('<meta http-equiv="refresh" content="0 ; url=http://v2.dreadcast.net/ingame/engine=invlieu.php"> ');
	exit();
	}
?>
<?php if ((date("H")>=21) || (date("H")<=7)) { include("inc_haut_n.php"); } else { include("inc_haut_j.php");} ?>

<div id="haut">
	<?php include("inc_entete.php"); ?>
	<div class="haut_aligngauche">
		<p>
		<div class="titrepage">
			Transfert
		</div>
		</p>
	</div>
</div>
<div id="centre">
<p>
<img src="im_objets/loader.gif" alt="..." /><br /><br />
<?php 

$db = mysql_connect('localhost', 'CENSURE', 'CENSURE') or die("Erreur de connexion au serveur des messages.");
mysql_select_db('CENSURE',$db) or die("Erreur de connexion a la base de donnees des messages.");

$sql = 'SELECT repos FROM lieu_tbl WHERE num= "'.$_SESSION['num'].'" AND rue= "'.$_SESSION['rue'].'"' ;
$req = mysql_query($sql);
$repos = mysql_result($req,0,repos); 

$sqlt = 'SELECT id FROM invlieu_tbl WHERE num= "'.$_SESSION['num'].'" AND rue= "'.$_SESSION['rue'].'"' ;
$reqt = mysql_query($sqlt);
$rest = mysql_num_rows($reqt);

if($repos==10)
	{
	$repos = 12;
	}

if($rest<ceil($repos / 2))
	{
	if($emplacement=="arme")
		{
		$sql = 'INSERT INTO invlieu_tbl(id,rue,num,nom) VALUES("","'.$_SESSION['rue'].'","'.$_SESSION['num'].'","'.$_SESSION['arme'].'")';
		mysql_query($sql);
		$sql = 'UPDATE principal_tbl SET arme="Aucune" WHERE id="'.$_SESSION['id'].'"';
		$req = mysql_query($sql);
		}
	elseif($emplacement=="objet")
		{
		$sql = 'INSERT INTO invlieu_tbl(id,rue,num,nom) VALUES("","'.$_SESSION['rue'].'","'.$_SESSION['num'].'","'.$_SESSION['objet'].'")';
		mysql_query($sql);
		$sql = 'UPDATE principal_tbl SET objet="Aucun" WHERE id="'.$_SESSION['id'].'"';
		$req = mysql_query($sql);
		}
	else
		{
		$sql = 'SELECT case'.$emplacement.' FROM principal_tbl WHERE id="'.$_SESSION['id'].'"';
		$req = mysql_query($sql);
		
		if (mysql_result($req, 0, 'case'.$emplacement) == "Vide" || mysql_result($req, 0, 'case'.$emplacement) == "Bague de Haut Dignitaire") {
			print('<meta http-equiv="refresh" content="0 ; url=http://v2.dreadcast.net/ingame/engine=inventaire.php?envoyer"> ');
			exit;
		}
		
		$sql = 'INSERT INTO invlieu_tbl(id,rue,num,nom) VALUES("","'.$_SESSION['rue'].'","'.$_SESSION['num'].'","'.$_SESSION['case'.$emplacement.''].'")';
		mysql_query($sql);
		$sql = 'UPDATE principal_tbl SET case'.$emplacement.'="Vide" WHERE id="'.$_SESSION['id'].'"';
		$req = mysql_query($sql);
		}
	print('<meta http-equiv="refresh" content="0 ; url=http://v2.dreadcast.net/ingame/engine=inventaire.php?envoyer"> ');
	}
else
	{
	print('<strong>Il n\'y a pas d\'emplacement vide au '.$_SESSION['num'].' '.ucwords($_SESSION['rue']).'.</strong>');
	}

mysql_close($db);

?>


</p>
</div>
<?php if($chat=="oui") 	{ if ((date("H")>=21) || (date("H")<=7)) { include("inc_bas_n_c.php"); } else { include("inc_bas_j_c.php"); } } else {	if ((date("H")>=21) || (date("H")<=7)) { include("inc_bas_n.php"); } else { include("inc_bas_j.php"); }	} ?>
<?php include("inc_bas_de_page.php"); ?>
