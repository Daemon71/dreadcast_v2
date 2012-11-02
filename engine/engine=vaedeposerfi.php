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

$sql = 'SELECT case1,case2,case3,case4,case5,case6 FROM principal_tbl WHERE id= "'.$_SESSION['id'].'"' ;
$req = mysql_query($sql);
$_SESSION['case1'] = mysql_result($req,0,case1);
$_SESSION['case2'] = mysql_result($req,0,case2);
$_SESSION['case3'] = mysql_result($req,0,case3);
$_SESSION['case4'] = mysql_result($req,0,case4);
$_SESSION['case5'] = mysql_result($req,0,case5);
$_SESSION['case6'] = mysql_result($req,0,case6);

$sql = 'SELECT nom,type,budget,benefices FROM entreprises_tbl WHERE rue= "'.$_SESSION['lieu'].'" AND num= "'.$_SESSION['num'].'"' ;
$req = mysql_query($sql);
$type = mysql_result($req,0,type);
$noment = mysql_result($req,0,nom);
$budget = mysql_result($req,0,budget);
$benefices = mysql_result($req,0,benefices);

if($type!="ventes aux encheres")
	{
	print('<meta http-equiv="refresh" content="0 ; url=http://v2.dreadcast.net/ingame/engine.php"> ');
	exit();
	}
elseif($_POST['enchere']==0)
	{
	print('<meta http-equiv="refresh" content="0 ; url=http://v2.dreadcast.net/ingame/engine=vaedeposer.php"> ');
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
			Vente aux enchères
		</div>
		</p>
	</div>
</div>
<div id="centre">
<p>

<img src="im_objets/loader.gif" alt="..." />
<?php
for($i=1;$i!=7;$i++)
	{
	if(($_SESSION['case'.$i.'']==$_POST['item']) && ($_SESSION['credits']>=$_POST['temps']) && ($okvae!=1))
		{
		$okvae = 1;
		$db = mysql_connect('localhost', 'CENSURE', 'CENSURE') or die("Erreur de connexion au serveur des messages.");
		mysql_select_db('CENSURE',$db) or die("Erreur de connexion a la base de donnees des messages.");
		
		if($_POST['temps']==10)
			{
			$tps = 24;
			}
		elseif($_POST['temps']==40)
			{
			$tps = 120;
			}
		elseif($_POST['temps']==70)
			{
			$tps = 240;
			}
		
		$budget = $budget + $_POST['temps'];
		$benefices = $benefices + $_POST['temps'];
		
		$sql = 'UPDATE entreprises_tbl SET benefices= "'.$benefices.'" , budget= "'.$budget.'" WHERE rue= "'.$_SESSION['lieu'].'" AND num= "'.$_SESSION['num'].'"' ;
		$req = mysql_query($sql);

		$_SESSION['credits'] = $_SESSION['credits'] - $_POST['temps'];
		$sql1 = 'UPDATE principal_tbl SET credits= "'.$_SESSION['credits'].'" , case'.$i.'= "Vide" WHERE id= "'.$_SESSION['id'].'"' ;
		$req1 = mysql_query($sql1);

		$sql1 = 'INSERT INTO vente_tbl(id,vendeur,objet,enchere,buyout,fin) VALUES("","'.$_SESSION['pseudo'].'","'.$_POST['item'].'","'.$_POST['enchere'].'","'.$_POST['achat'].'","'.$tps.'")' ;
		$req1 = mysql_query($sql1);
		
		$sqls = 'INSERT INTO messages_tbl(id,auteur,cible,message,objet,moment) VALUES("","Dreadcast","'.$_SESSION['pseudo'].'","Vous venez de mettre le produit <strong>'.$_POST['item'].'</strong> en vente pour <strong>'.$tps.' heures</strong>.<br />Vous pouvez gérer vos vente en direct depuis un Hall des ventes.","Objet mis en vente","'.time().'")';
		$reqs = mysql_query($sqls);

		print('<meta http-equiv="refresh" content="0 ; url=http://v2.dreadcast.net/ingame/engine=vaeges.php"> ');

		mysql_close($db);
		}
	}

?>

</p>
</div>
<?php if($chat=="oui") 	{ if ((date("H")>=21) || (date("H")<=7)) { include("inc_bas_n_c.php"); } else { include("inc_bas_j_c.php"); } } else {	if ((date("H")>=21) || (date("H")<=7)) { include("inc_bas_n.php"); } else { include("inc_bas_j.php"); }	} ?>
<?php include("inc_bas_de_page.php"); ?>
