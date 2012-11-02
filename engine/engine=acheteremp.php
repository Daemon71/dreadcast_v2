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

$sql = 'SELECT type,nom,budget,benefices FROM entreprises_tbl WHERE rue= "'.$_SESSION['rue'].'" AND num= "'.$_SESSION['num'].'"' ;
$req = mysql_query($sql);
$type = mysql_result($req,0,type);
$noment = mysql_result($req,0,nom);
$budget = mysql_result($req,0,budget);
$benefices = mysql_result($req,0,benefices);

if($type!="banque")
	{
	print('<meta http-equiv="refresh" content="0 ; url=http://v2.dreadcast.net/ingame/engine.php"> ');
	exit();
	}

$code = $_SERVER['QUERY_STRING'];

$sql = 'SELECT credits,emp2,emp3,emp4,emp5,emp6,emp7,emp8 FROM comptes_tbl WHERE code= "'.$code.'"' ;
$req = mysql_query($sql);
$solde = mysql_result($req,0,credits);
$_SESSION['emp2'] = mysql_result($req,0,emp2);
$_SESSION['emp3'] = mysql_result($req,0,emp3);
$_SESSION['emp4'] = mysql_result($req,0,emp4);
$_SESSION['emp5'] = mysql_result($req,0,emp5);
$_SESSION['emp6'] = mysql_result($req,0,emp6);
$_SESSION['emp7'] = mysql_result($req,0,emp7);
$_SESSION['emp8'] = mysql_result($req,0,emp8);

if($_SESSION['credits']<60)
	{
	print('<meta http-equiv="refresh" content="0 ; url=http://v2.dreadcast.net/ingame/engine=compte.php?'.$code.'"> ');
	exit();
	}

$l = 0;

for($i=2; $i!=9; $i++)
	{
	if(($_SESSION['emp'.$i.'']=="0") && ($l!=1))
		{
		$benefices = $benefices + 60;
		$budget = $budget + 60;
		$_SESSION['credits'] = $_SESSION['credits'] - 60;
		$sql = 'UPDATE principal_tbl SET credits= "'.$_SESSION['credits'].'" WHERE id= "'.$_SESSION['id'].'"' ;
		$req = mysql_query($sql);
		$sql = 'UPDATE entreprises_tbl SET budget= "'.$budget.'" , benefices= "'.$benefices.'" WHERE nom= "'.$noment.'"' ;
		$req = mysql_query($sql);
		$sql = 'UPDATE comptes_tbl SET emp'.$i.'= "Vide" WHERE code= "'.$code.'"' ;
		$req = mysql_query($sql);
		$l = 1;
		}
	}

mysql_close($db);

?>
<?php if ((date("H")>=21) || (date("H")<=7)) { include("inc_haut_n.php"); } else { include("inc_haut_j.php");} ?>

<div id="haut">
	<?php include("inc_entete.php"); ?>
	<div class="haut_aligngauche_haut">
		<p>
		<div class="titrepage">
			Banque
		</div>
		<b class="module4ie"><a href="engine=compte.php?<?php print(''.$code.''); ?>" class="module4"><img src="im_objets/icon_retour.gif" alt="Retour" />Retour</b></a>
		</p>
	</div>
</div>
<div id="centre">
<p>


<em>Nouvel emplacement achet&eacute;. </em>


</p>
</div>
<?php if($chat=="oui") 	{ if ((date("H")>=21) || (date("H")<=7)) { include("inc_bas_n_c.php"); } else { include("inc_bas_j_c.php"); } } else {	if ((date("H")>=21) || (date("H")<=7)) { include("inc_bas_n.php"); } else { include("inc_bas_j.php"); }	} ?>
<?php include("inc_bas_de_page.php"); ?>
