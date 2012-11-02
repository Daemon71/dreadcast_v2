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

$sql = 'SELECT arme,case1,case2,case3,case4,case5,case6 FROM principal_tbl WHERE id= "'.$_SESSION['id'].'"' ;
$req = mysql_query($sql);
$_SESSION['arme'] = mysql_result($req,0,arme); 
$_SESSION['case1'] = mysql_result($req,0,case1); 
$_SESSION['case2'] = mysql_result($req,0,case2); 
$_SESSION['case3'] = mysql_result($req,0,case3); 
$_SESSION['case4'] = mysql_result($req,0,case4); 
$_SESSION['case5'] = mysql_result($req,0,case5); 
$_SESSION['case6'] = mysql_result($req,0,case6); 

$sql = 'SELECT code FROM lieu_tbl WHERE num= "'.$_SESSION['num'].'" AND rue= "'.$_SESSION['rue'].'"' ;
$req = mysql_query($sql);
$boncode = mysql_result($req,0,code); 

if($_SERVER['QUERY_STRING']!="")
	{
	$emplacement = $_SERVER['QUERY_STRING'];
	}
else
	{
	print('<meta http-equiv="refresh" content="0 ; url=http://v2.dreadcast.net/ingame/engine=invlieu.php"> ');
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
			Transfert
		</div>
		</p>
	</div>
</div>
<div id="centre">
<p>


<img src="im_objets/loader.gif" alt="..." /><br /><br />
<?php 
if($_SESSION['code']==$boncode)
	{
	$db = mysql_connect('localhost', 'CENSURE', 'CENSURE') or die("Erreur de connexion au serveur des messages.");
	mysql_select_db('CENSURE',$db) or die("Erreur de connexion a la base de donnees des messages.");
	
	$sqlt = 'SELECT id,nom FROM invlieu_tbl WHERE num= "'.$_SESSION['num'].'" AND rue= "'.$_SESSION['rue'].'"' ;
	$reqt = mysql_query($sqlt);
	$rest = mysql_num_rows($reqt);
	$ido = mysql_result($reqt,$emplacement-1,id); 
	$objet = mysql_result($reqt,$emplacement-1,nom);
		
	for($i=1; $i != 7 ; $i++) 
		{
		if($objet!="" && ($_SESSION['case'.$i.'']=="Vide") && ($ok!=1))
			{
			$sql = 'UPDATE principal_tbl SET case'.$i.'="'.$objet.'" WHERE id="'.$_SESSION['id'].'"';
			$req = mysql_query($sql);
			$sql = 'DELETE FROM invlieu_tbl WHERE id="'.$ido.'"' ;
			$req = mysql_query($sql);
			print('<meta http-equiv="refresh" content="0 ; url=http://v2.dreadcast.net/ingame/engine=invlieu.php"> ');
			$ok = 1;
			}
		}
	if($ok!=1)
		{
		print("<p align='center'><strong>Vous n'avez pas d'emplacement vide dans votre inventaire personnel.</strong></p>");
		}
	if($objet=="")
		{
		print("<p align='center'><strong>Une erreur s'est produite. Veuillez contacter un administrateur, en précisant l'adresse du lieu actuel et le nom de l'objet que vous souhaitiez retirer.</strong></p>");
		}
	
	mysql_close($db);
	}
else
	{
	print('<meta http-equiv="refresh" content="0 ; url=http://v2.dreadcast.net/ingame/engine=invlieu.php"> ');
	}
?>

</p>
</div>
<?php if($chat=="oui") 	{ if ((date("H")>=21) || (date("H")<=7)) { include("inc_bas_n_c.php"); } else { include("inc_bas_j_c.php"); } } else {	if ((date("H")>=21) || (date("H")<=7)) { include("inc_bas_n.php"); } else { include("inc_bas_j.php"); }	} ?>
<?php include("inc_bas_de_page.php"); ?>
