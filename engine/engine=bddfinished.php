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

$sql = 'SELECT type,budget FROM entreprises_tbl WHERE nom="'.$_SESSION['entreprise'].'"' ;
$req = mysql_query($sql);
$type = mysql_result($req,0,type); 
$budget = mysql_result($req,0,budget); 

$sql = 'SELECT bdd FROM `e_'.str_replace(" ","_",''.$_SESSION['entreprise'].'').'_tbl` WHERE type="chef"' ;
$req = mysql_query($sql);
$bdd = mysql_result($req,0,bdd); 

$_SESSION['alimentation'] = $_POST['alimentation'];
$_SESSION['deck'] = $_POST['deck'];
$_SESSION['jag'] = $_POST['jag'];
$_SESSION['modif'] = $_POST['modif'];
$_SESSION['pmd'] = $_POST['pmd'];
$_SESSION['gad'] = $_POST['gad'];
$_SESSION['gmd'] = $_POST['gmd'];
$_SESSION['vd'] = $_POST['vd'];
$_SESSION['pharmacie'] = $_POST['pharmacie'];
$_SESSION['chambresl'] = $_POST['chambresl'];
$_SESSION['armesprim'] = $_POST['armesprim'];
$_SESSION['armestir'] = $_POST['armestir'];
$_SESSION['armesav'] = $_POST['armesav'];
$_SESSION['voitures'] = $_POST['voitures'];
$_SESSION['autrev'] = $_POST['autrev'];
$_SESSION['oa'] = $_POST['oa'];
$_SESSION['om'] = $_POST['om'];
$_SESSION['soie'] = $_POST['soie'];
$_SESSION['cristal'] = $_POST['cristal'];
$_SESSION['gcuisine'] = $_POST['gcuisine'];
$_SESSION['races'] = $_POST['races'];
$_SESSION['ages'] = $_POST['ages'];
$_SESSION['travails'] = $_POST['travails'];
$_SESSION['adresses'] = $_POST['adresses'];
$_SESSION['messages'] = $_POST['messages'];
$_SESSION['reactions'] = $_POST['reactions'];
$_SESSION['prodvet'] = $_POST['prodvet'];
$_SESSION['prodarmesc'] = $_POST['prodarmesc'];
$_SESSION['prodarmest'] = $_POST['prodarmest'];
$_SESSION['prodouu'] = $_POST['prodouu'];
$_SESSION['sac'] = $_POST['sac'];

if($_SESSION['domaine']=="agence immobiliaire")
	{
	if(($_SESSION['pmd']!="") && ($budget>500))
		{
		$budget = $budget - 500;
		$bdd = ''.$bdd.' pmd';
		}
	if(($_SESSION['gad']!="") && ($budget>300))
		{
		$budget = $budget - 300;
		$bdd = ''.$bdd.' gad';
		}
	if(($_SESSION['gmd']!="") && ($budget>800))
		{
		$budget = $budget - 800;
		$bdd = ''.$bdd.' gmd';
		}
	if(($_SESSION['vd']!="") && ($budget>2000))
		{
		$budget = $budget - 2000;
		$bdd = ''.$bdd.' vd';
		}
	}
elseif($_SESSION['domaine']=="hopital")
	{
	if(($_SESSION['pharmacie']!="") && ($budget>600))
		{
		$budget = $budget - 600;
		$bdd = ''.$bdd.' pharmacie';
		$sql = 'INSERT INTO stocks_tbl(id,entreprise,objet,nombre,pvente) VALUES("","'.$_SESSION['entreprise'].'","Medpack","0","155")';
		mysql_query($sql);
		}
	}
elseif($_SESSION['domaine']=="centre de recherche")
	{
	if(($_SESSION['prodvet']!="") && ($budget>10000))
		{
		$budget = $budget - 10000;
		$bdd = ''.$bdd.' prodvet';
		}
	if(($_SESSION['prodarmesc']!="") && ($budget>10000))
		{
		$budget = $budget - 10000;
		$bdd = ''.$bdd.' prodarmesc';
		}
	if(($_SESSION['prodarmest']!="") && ($budget>10000))
		{
		$budget = $budget - 10000;
		$bdd = ''.$bdd.' prodarmest';
		}
	if(($_SESSION['prodouu']!="") && ($budget>20000))
		{
		$budget = $budget - 20000;
		$bdd = ''.$bdd.' prodouu';
		}
	}
elseif($_SESSION['domaine']=="bar cafe")
	{
	if(($_SESSION['jag']!="") && ($budget>600))
		{
		$budget = $budget - 600;
		$bdd = ''.$bdd.' jag';
		}
	if(($_SESSION['alimentation']!="") && ($budget>200))
		{
		$budget = $budget - 200;
		$bdd = ''.$bdd.' alimentation';
		}
	}
elseif($_SESSION['domaine']=="usine de production")
	{
	if(($_SESSION['deck']!="") && ($budget>600))
		{
		$budget = $budget - 600;
		$bdd = ''.$bdd.' deck';
		}
	if(($_SESSION['modif']!="") && ($budget>1050))
		{
		$budget = $budget - 1050;
		$bdd = ''.$bdd.' modif';
		}
	if(($_SESSION['sac']!="") && ($budget>650))
		{
		$budget = $budget - 650;
		$bdd = ''.$bdd.' sac';
		}
	if(($_SESSION['armesprim']!="") && ($budget>450))
		{
		$budget = $budget - 450;
		$bdd = ''.$bdd.' armesprim';
		}
	if(($_SESSION['armestir']!="") && ($budget>600))
		{
		$budget = $budget - 600;
		$bdd = ''.$bdd.' armestir';
		}
	if(($_SESSION['armesav']!="") && ($budget>2000))
		{
		$budget = $budget - 2000;
		$bdd = ''.$bdd.' armesav';
		}
	if(($_SESSION['voitures']!="") && ($budget>400))
		{
		$budget = $budget - 400;
		$bdd = ''.$bdd.' voitures';
		}
	if(($_SESSION['autrev']!="") && ($budget>900))
		{
		$budget = $budget - 900;
		$bdd = ''.$bdd.' autrev';
		}
	if(($_SESSION['oa']!="") && ($budget>500))
		{
		$budget = $budget - 500;
		$bdd = ''.$bdd.' oa';
		}
	if(($_SESSION['om']!="") && ($budget>2000))
		{
		$budget = $budget - 2000;
		$bdd = ''.$bdd.' om';
		}
	if(($_SESSION['soie']!="") && ($budget>300))
		{
		$budget = $budget - 300;
		$bdd = ''.$bdd.' soie';
		}
	if(($_SESSION['cristal']!="") && ($budget>1000))
		{
		$budget = $budget - 1000;
		$bdd = ''.$bdd.' cristal';
		}
	}

$sql = 'UPDATE entreprises_tbl SET budget="'.$budget.'" WHERE nom="'.$_SESSION['entreprise'].'"' ;
$req = mysql_query($sql);

$sql = 'UPDATE `e_'.str_replace(" ","_",''.$_SESSION['entreprise'].'').'_tbl` SET bdd="'.$bdd.'" WHERE bdd NOT IN ("")' ;
$req = mysql_query($sql);


mysql_close($db);

?>
<?php if ((date("H")>=21) || (date("H")<=7)) { include("inc_haut_n.php"); } else { include("inc_haut_j.php");} ?>

<div id="haut">
	<?php include("inc_entete.php"); ?>
	<div class="haut_aligngauche">
		<p>
		<div class="titrepage">
			Base de données
		</div>
		</p>
	</div>
</div>
<div id="centre">
<p>

<meta http-equiv="refresh" content="0 ; url=http://v2.dreadcast.net/ingame/engine=bdd.php">

</p>
</div>
<?php if($chat=="oui") 	{ if ((date("H")>=21) || (date("H")<=7)) { include("inc_bas_n_c.php"); } else { include("inc_bas_j_c.php"); } } else {	if ((date("H")>=21) || (date("H")<=7)) { include("inc_bas_n.php"); } else { include("inc_bas_j.php"); }	} ?>
<?php include("inc_bas_de_page.php"); ?>
