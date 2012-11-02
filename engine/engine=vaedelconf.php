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
			Hall des ench&egrave;res 
		</div>
		<b class="module4ie"><a href="engine=vaeges.php" class="module4"><img src="im_objets/icon_retour.gif" alt="Retour" />Retour</a></b>
</p>
	</div>
</div>
<div id="centre_encheres">

<p id="location2"><a href="engine=vaedeposer.php">D&eacute;poser un objet</a> | <a href="engine=vae.php">Consulter les ventes</a></p>

<?php

$db = mysql_connect('localhost', 'CENSURE', 'CENSURE') or die("Erreur de connexion au serveur des messages.");
mysql_select_db('CENSURE',$db) or die("Erreur de connexion a la base de donnees des messages.");

$sql = 'SELECT type FROM entreprises_tbl WHERE rue= "'.$_SESSION['lieu'].'" AND num= "'.$_SESSION['num'].'"' ;
$req = mysql_query($sql);
$type = mysql_result($req,0,type);

if($type!="ventes aux encheres")
	{
	print('<meta http-equiv="refresh" content="0 ; url=http://v2.dreadcast.net/ingame/engine.php"> ');
	exit();
	}

$sql = 'SELECT * FROM vente_tbl WHERE vendeur= "'.$_SESSION['pseudo'].'" AND id= "'.$_GET['id'].'"' ;
$req = mysql_query($sql);
$res = mysql_num_rows($req);
if($res==0)
	{
	print('<meta http-equiv="refresh" content="0 ; url=http://v2.dreadcast.net/ingame/engine=vae.php"> ');
	exit();
	}

$vendeur = mysql_result($req,0,vendeur);
$objetv = mysql_result($req,0,objet);
$pvente = mysql_result($req,0,buyout);
$acheteur = mysql_result($req,0,acheteur);
$enchere = mysql_result($req,0,enchere);

$sql = 'SELECT case1,case2,case3,case4,case5,case6 FROM principal_tbl WHERE id= "'.$_SESSION['id'].'"' ;
$req = mysql_query($sql);
$_SESSION['case1'] = mysql_result($req,0,case1);
$_SESSION['case2'] = mysql_result($req,0,case2);
$_SESSION['case3'] = mysql_result($req,0,case3);
$_SESSION['case4'] = mysql_result($req,0,case4);
$_SESSION['case5'] = mysql_result($req,0,case5);
$_SESSION['case6'] = mysql_result($req,0,case6);

if(($_SESSION['case1']!="Vide") && ($_SESSION['case2']!="Vide") && ($_SESSION['case3']!="Vide") && ($_SESSION['case4']!="Vide") && ($_SESSION['case5']!="Vide") && ($_SESSION['case6']!="Vide"))
	{
	print('<p id="textelse"><strong>Vous n\'avez pas d\'emplacement vide dans votre inventaire pour récupérer l\'objet.</strong></p>');
	}
else
	{
	for($i=1; $i != 7; $i++)
		{
		if(($_SESSION['case'.$i.'']=="Vide") && ($l!=1))
			{
			$sql = 'UPDATE principal_tbl SET case'.$i.'= "'.$objetv.'" WHERE id= "'.$_SESSION['id'].'"' ;
			$req = mysql_query($sql);
			print('<p id="textelse"><strong>La vente vient d\'être annulée.</strong></p>');
			$l = 1;
			}
		}
	
	if($acheteur!="")
		{
		$sql = 'SELECT id FROM principal_tbl WHERE pseudo= "'.$acheteur.'"' ;
		$req = mysql_query($sql);
		$ida = mysql_result($req,0,id);
		$sql = 'SELECT credits FROM principal_tbl WHERE id= "'.$ida.'"' ;
		$req = mysql_query($sql);
		$creditsa = mysql_result($req,0,credits) + $enchere;
		$sql = 'UPDATE principal_tbl SET credits= "'.$creditsa.'" WHERE id= "'.$ida.'"' ;
		$req = mysql_query($sql);
		$sqls = 'INSERT INTO messages_tbl(id,auteur,cible,message,objet,moment) VALUES("","Hall des enchères","'.$acheteur.'","La vente a été annulée pour le produit <strong>'.$objetv.'</strong>.<br />Vous récupérez donc votre enchère de <strong>'.$enchere.' Crédits</strong>.","Vente annulée !","'.time().'")';
		$reqs = mysql_query($sqls);
		}
	
	$sql = 'DELETE FROM vente_tbl WHERE id= "'.$_GET['id'].'"' ;
	$req = mysql_query($sql);
	}

mysql_close($db);

?>

</div>
<?php if($chat=="oui") 	{ if ((date("H")>=21) || (date("H")<=7)) { include("inc_bas_n_c.php"); } else { include("inc_bas_j_c.php"); } } else {	if ((date("H")>=21) || (date("H")<=7)) { include("inc_bas_n.php"); } else { include("inc_bas_j.php"); }	} ?>
<?php include("inc_bas_de_page.php"); ?>
