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

$sql = 'SELECT type,budget,benefices FROM entreprises_tbl WHERE rue= "'.$_SESSION['rue'].'" AND num= "'.$_SESSION['num'].'"' ;
$req = mysql_query($sql);
$type = mysql_result($req,0,type);
$budget = mysql_result($req,0,budget);
$benefices = mysql_result($req,0,benefices);

if($type!="banque")
	{
	print('<meta http-equiv="refresh" content="0 ; url=http://v2.dreadcast.net/ingame/engine.php"> ');
	exit();
	}

$code = $_GET['code'];
$case = 'case'.$_GET['case'].'';

$sql = 'SELECT credits,'.$case.' FROM principal_tbl WHERE id= "'.$_SESSION['id'].'"' ;
$req = mysql_query($sql);
$_SESSION['credits'] = mysql_result($req,0,credits);
$_SESSION[''.$case.''] = mysql_result($req,0,$case);

if($_SESSION['credits']<15)
	{
	print('<meta http-equiv="refresh" content="0 ; url=http://v2.dreadcast.net/ingame/engine=compte.php?'.$code.'"> ');
	exit();
	}
else
	{
	$sql = 'SELECT * FROM comptes_tbl WHERE code= "'.$code.'"' ;
	$req = mysql_query($sql);
	$_SESSION['emp1'] = mysql_result($req,0,emp1);
	$_SESSION['emp2'] = mysql_result($req,0,emp2);
	$_SESSION['emp3'] = mysql_result($req,0,emp3);
	$_SESSION['emp4'] = mysql_result($req,0,emp4);
	$_SESSION['emp5'] = mysql_result($req,0,emp5);
	$_SESSION['emp6'] = mysql_result($req,0,emp6);
	$_SESSION['emp7'] = mysql_result($req,0,emp7);
	$_SESSION['emp8'] = mysql_result($req,0,emp8);
	if(mysql_result($req,0,mdp)!=$_SESSION['mdpcompte'])
		{
		print('<meta http-equiv="refresh" content="0 ; url=http://v2.dreadcast.net/ingame/engine=mdpcompte.php?'.$code.'"> ');
		exit();
		}
	$l = 0;
	for($i=1; $i!=9; $i++)
		{
		if(($_SESSION['emp'.$i.'']=="Vide") && ($l!=1))
			{
			$sql = 'UPDATE comptes_tbl SET emp'.$i.'= "'.$_SESSION[''.$case.''].'" WHERE code= "'.$code.'"' ;
			$req = mysql_query($sql);
			
			$sql = 'INSERT INTO comptes_acces_tbl(id,pseudo,compte,time,operation,valeur) VALUES("","'.$_SESSION['pseudo'].'","'.$code.'","'.time().'","Depot objet","'.$_SESSION[''.$case.''].'")' ;
			mysql_query($sql);
			
			$l = 1;
			}
		}
	if($l = 0)
		{
		$l = 2;
		}
	}

$_SESSION['credits'] = $_SESSION['credits'] - 15;
$budget = $budget + 15;
$benefices = $benefices + 15;

$sql = 'UPDATE principal_tbl SET credits= "'.$_SESSION['credits'].'" , '.$case.'= "Vide" WHERE id= "'.$_SESSION['id'].'"' ;
$req = mysql_query($sql);
$sql = 'UPDATE entreprises_tbl SET budget= "'.$budget.'" , benefices= "'.$benefices.'" WHERE rue= "'.$_SESSION['rue'].'" AND num= "'.$_SESSION['num'].'"' ;
$req = mysql_query($sql);

mysql_close($db);
?>
<?php if ((date("H")>=21) || (date("H")<=7)) { include("inc_haut_n.php"); } else { include("inc_haut_j.php");} ?>

<div id="haut">
	<?php include("inc_entete.php"); ?>
	<div class="haut_aligngauche">
		<p>
		<div class="titrepage">
			Banque
		</div>
		<b class="module4ie"><a href="engine=compte.php?<?php print(''.$code.''); ?>" class="module4"><img src="im_objets/icon_retour.gif" alt="Retour" />Retour</a></b>
		</p>
	</div>
</div>
<div id="centre_banque">

	<p id="location">Cr&eacute;dits en poche : <span><?php print($_SESSION['credits']); ?></span></p>

	<br /><br /><br /><br />
	
	<p id="textelse"><?php 
if($l = 1)
	{
	print('L\'objet '.$_SESSION[''.$case.''].' a bien &eacute;t&eacute; d&eacute;pos&eacute;.'); 
	}
elseif($l = 2)
	{
	print('Vous n\'avez pas d\'emplacement libre dans votre compte.');
	}

?></p>


</div>
<?php if($chat=="oui") 	{ if ((date("H")>=21) || (date("H")<=7)) { include("inc_bas_n_c.php"); } else { include("inc_bas_j_c.php"); } } else {	if ((date("H")>=21) || (date("H")<=7)) { include("inc_bas_n.php"); } else { include("inc_bas_j.php"); }	} ?>
<?php include("inc_bas_de_page.php"); ?>
