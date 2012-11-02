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
			Local d'entreprise
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

if($_SESSION['points']!=999) { print('<meta http-equiv="refresh" content="0 ; url=http://v2.dreadcast.net/ingame/engine.php"> '); exit(); }

$sql = 'SELECT num,rue,budget FROM entreprises_tbl WHERE nom="'.$_SESSION['entreprise'].'"' ;
$req = mysql_query($sql);
$num = mysql_result($req,0,num); 
$rue = mysql_result($req,0,rue); 
$budget = mysql_result($req,0,budget); 

if(($_POST['aent']==50) && ($budget>400))
	{
	$budget = $budget - 400;
	$sql = 'UPDATE entreprises_tbl SET budget="'.$budget.'" WHERE nom="'.$_SESSION['entreprise'].'"' ;
	$req = mysql_query($sql);
	$sql = 'UPDATE lieu_tbl SET repos="2" , reposactuel="2" , nom="Local 50m²" WHERE rue="'.$rue.'" AND num="'.$num.'"' ;
	$req = mysql_query($sql);
	}
elseif(($_POST['aent']==100) && ($budget>1000))
	{
	$budget = $budget - 1000;
	$sql = 'UPDATE entreprises_tbl SET budget="'.$budget.'" WHERE nom="'.$_SESSION['entreprise'].'"' ;
	$req = mysql_query($sql);
	$sql = 'UPDATE lieu_tbl SET repos="3" , reposactuel="3" , nom="Local 100m²" WHERE rue="'.$rue.'" AND num="'.$num.'"' ;
	$req = mysql_query($sql);
	}
elseif(($_POST['aent']==200) && ($budget>1000))
	{
	$budget = $budget - 2000;
	$sql = 'UPDATE entreprises_tbl SET budget="'.$budget.'" WHERE nom="'.$_SESSION['entreprise'].'"' ;
	$req = mysql_query($sql);
	$sql = 'UPDATE lieu_tbl SET repos="4" , reposactuel="4" , nom="Local 200m²" WHERE rue="'.$rue.'" AND num="'.$num.'"' ;
	$req = mysql_query($sql);
	}

print('<meta http-equiv="refresh" content="0 ; url=http://v2.dreadcast.net/ingame/engine=local.php"> ');

mysql_close($db);

?>

</p>
</div>
<?php if($chat=="oui") 	{ if ((date("H")>=21) || (date("H")<=7)) { include("inc_bas_n_c.php"); } else { include("inc_bas_j_c.php"); } } else {	if ((date("H")>=21) || (date("H")<=7)) { include("inc_bas_n.php"); } else { include("inc_bas_j.php"); }	} ?>
<?php include("inc_bas_de_page.php"); ?>
