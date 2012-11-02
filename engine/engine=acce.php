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

$sql = 'SELECT type,entreprise,points FROM principal_tbl WHERE id= "'.$_SESSION['id'].'"' ;
$req = mysql_query($sql);
$_SESSION['poste'] = mysql_result($req,0,type); 
$_SESSION['entreprise'] = mysql_result($req,0,entreprise); 
$_SESSION['points'] = mysql_result($req,0,points); 

if($_SESSION['points']!=999) { print('<meta http-equiv="refresh" content="0 ; url=http://v2.dreadcast.net/ingame/engine.php"> '); exit(); }

$sql = 'SELECT num,rue,budget FROM entreprises_tbl WHERE nom= "'.$_SESSION['entreprise'].'"' ;
$req = mysql_query($sql);
$nument = mysql_result($req,0,num); 
$rueent = mysql_result($req,0,rue); 
$budget = mysql_result($req,0,budget); 

$sql = 'SELECT code FROM lieu_tbl WHERE rue= "'.$rueent.'" AND num= "'.$nument.'"' ;
$req = mysql_query($sql);
$code = mysql_result($req,0,code); 

mysql_close($db);

?>
<?php if ((date("H")>=21) || (date("H")<=7)) { include("inc_haut_n.php"); } else { include("inc_haut_j.php");} ?>

<div id="haut">
	<?php include("inc_entete.php"); ?>
	<div class="haut_aligngauche">
		<p>
		<div class="titrepage">
			Gerer votre local
		</div>
		</p>
	</div>
</div>
<div id="centre">
<p>

<?php  

if($budget>180 && strlen($code)<6)
	{
	$db = mysql_connect('localhost', 'CENSURE', 'CENSURE') or die("Erreur de connexion au serveur des messages.");
	mysql_select_db('CENSURE',$db) or die("Erreur de connexion a la base de donnees des messages.");
	
	$sql = 'UPDATE lieu_tbl SET code="'.$code.'0" WHERE rue= "'.$rueent.'" AND num= "'.$nument.'"' ;
	$req = mysql_query($sql);
	
	$budget = $budget - 180;
	$sql = 'UPDATE entreprises_tbl SET budget="'.$budget.'" WHERE nom= "'.$_SESSION['entreprise'].'"' ;
	$req = mysql_query($sql);

	print('<meta http-equiv="refresh" content="0 ; url=http://v2.dreadcast.net/ingame/engine=local.php"> ');
	
	mysql_close($db);
	}
else
	{
	print("<strong>Vous n'avez pas le budget pour acheter un chiffre de plus.<strong>");
	}

?>


</p>
</div>
<?php if($chat=="oui") 	{ if ((date("H")>=21) || (date("H")<=7)) { include("inc_bas_n_c.php"); } else { include("inc_bas_j_c.php"); } } else {	if ((date("H")>=21) || (date("H")<=7)) { include("inc_bas_n.php"); } else { include("inc_bas_j.php"); }	} ?>
<?php include("inc_bas_de_page.php"); ?>
