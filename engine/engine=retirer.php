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
			Banque
		</div>
		</p>
	</div>
</div>
<div id="centre_banque">

	<p id="location">Cr&eacute;dits en poche : <span><?php print($_SESSION['credits']); ?></span></p>

	<br /><br /><br />
	
	<p id="textelse"><?php 

$db = mysql_connect('localhost', 'CENSURE', 'CENSURE') or die("Erreur de connexion au serveur des messages.");
mysql_select_db('CENSURE',$db) or die("Erreur de connexion a la base de donnees des messages.");

$sql = 'SELECT nom,type FROM entreprises_tbl WHERE rue= "'.$_SESSION['lieu'].'" AND num= "'.$_SESSION['num'].'"' ;
$req = mysql_query($sql);
$type = mysql_result($req,0,type);
$noment = mysql_result($req,0,nom);

if($type!="banque")
	{
	print('<meta http-equiv="refresh" content="0 ; url=http://v2.dreadcast.net/ingame/engine.php"> ');
	exit();
	}

if($_SERVER['QUERY_STRING']!="")
	{
	$emplacement = $_GET['id'];
	$code = $_GET['code'];
	}
else
	{
	print('<meta http-equiv="refresh" content="0 ; url=http://v2.dreadcast.net/ingame/engine.php"> ');
	exit();
	}

$sql = 'SELECT arme,case1,case2,case3,case4,case5,case6 FROM principal_tbl WHERE id= "'.$_SESSION['id'].'"' ;
$req = mysql_query($sql);
$_SESSION['arme'] = mysql_result($req,0,arme); 
$_SESSION['case1'] = mysql_result($req,0,case1); 
$_SESSION['case2'] = mysql_result($req,0,case2); 
$_SESSION['case3'] = mysql_result($req,0,case3); 
$_SESSION['case4'] = mysql_result($req,0,case4); 
$_SESSION['case5'] = mysql_result($req,0,case5); 
$_SESSION['case6'] = mysql_result($req,0,case6); 


$sql = 'SELECT * FROM comptes_tbl WHERE code= "'.$code.'"' ;
$req = mysql_query($sql);
$res = mysql_num_rows($req);

if(mysql_result($req,0,mdp)!=$_SESSION['mdpcompte'])
	{
	print('<meta http-equiv="refresh" content="0 ; url=http://v2.dreadcast.net/ingame/engine=mdpcompte.php?'.$code.'"> ');
	exit();
	}

if($res>0)
	{
	$emp = 'emp'.$emplacement.'';
	$objet = mysql_result($req,0,$emp);
	
	if(($objet=="Vide") || ($objet=="0") || ($objet==""))
		{
		print('<meta http-equiv="refresh" content="0 ; url=http://v2.dreadcast.net/ingame/engine=compte.php?'.$code.'"> ');
		} 
	for($i=1; $i != 7 ; $i++) 
		{
		if($_SESSION['case'.$i.'']=="Vide")
			{
			$sql = 'UPDATE principal_tbl SET case'.$i.'="'.$objet.'" WHERE id="'.$_SESSION['id'].'"';
			$req = mysql_query($sql);
			$sql = 'UPDATE comptes_tbl SET '.$emp.'="Vide" WHERE code="'.$code.'"' ;
			$req = mysql_query($sql);
			$sql = 'INSERT INTO comptes_acces_tbl(id,pseudo,compte,time,operation,valeur) VALUES("","'.$_SESSION['pseudo'].'","'.$code.'","'.time().'","Retrait objet","'.$objet.'")' ;
			mysql_query($sql);
			print('<meta http-equiv="refresh" content="0 ; url=http://v2.dreadcast.net/ingame/engine=compte.php?'.$code.'"> ');
			mysql_close($db);
			exit();
			}
		}
	
	print("<b>Vous n'avez pas d'emplacement vide dans votre inventaire personnel.</b>");
	}
else
	{
	print('...<meta http-equiv="refresh" content="0 ; url=http://v2.dreadcast.net/ingame/engine=compte.php?'.$code.'"> ');
	}


mysql_close($db);
?>

</div>
<?php if($chat=="oui") 	{ if ((date("H")>=21) || (date("H")<=7)) { include("inc_bas_n_c.php"); } else { include("inc_bas_j_c.php"); } } else {	if ((date("H")>=21) || (date("H")<=7)) { include("inc_bas_n.php"); } else { include("inc_bas_j.php"); }	} ?>
<?php include("inc_bas_de_page.php"); ?>
