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

$sql = 'SELECT credits FROM principal_tbl WHERE id= "'.$_SESSION['id'].'"' ;
$req = mysql_query($sql);
$_SESSION['credits'] = mysql_result($req,0,credits);

$sql = 'SELECT nom,type FROM entreprises_tbl WHERE rue= "'.$_SESSION['lieu'].'" AND num= "'.$_SESSION['num'].'"' ;
$req = mysql_query($sql);
$type = mysql_result($req,0,type);
$noment = mysql_result($req,0,nom);

if($type!="ventes aux encheres")
	{
	print('<meta http-equiv="refresh" content="0 ; url=http://v2.dreadcast.net/ingame/engine.php"> ');
	exit();
	}

mysql_close($db);
?>
<?php if ((date("H")>=21) || (date("H")<=7)) { include("inc_haut_n.php"); } else { include("inc_haut_j.php");} ?>

<div id="haut">
	<?php include("inc_entete.php"); ?>
	<div class="haut_aligngauche_haut">
		<p>
		<div class="titrepage">
			Hall des ench&egrave;res 
		</div>
		</p>
	</div>
</div>
<div id="centre">
<p>

<img src="im_objets/loader.gif" alt="..." />
<?php 
$idv = $_GET['id'];

$db = mysql_connect('localhost', 'CENSURE', 'CENSURE') or die("Erreur de connexion au serveur.");
mysql_select_db('CENSURE',$db) or die("Erreur de connexion a la base de donnees.");

$sql = 'SELECT * FROM vente_tbl WHERE id= "'.$idv.'"' ;
$req = mysql_query($sql);
$res = mysql_num_rows($req);
if($res==0)
	{
	print('<meta http-equiv="refresh" content="0 ; url=http://v2.dreadcast.net/ingame/engine=vae.php"> ');
	exit();
	}

$objetv = mysql_result($req,0,objet);
$enchere = mysql_result($req,0,enchere);
$nenchere = $enchere + ceil($enchere*0.05);

$acheteur = mysql_result($req,0,acheteur);

if(($_SESSION['credits']>=$nenchere) && ($acheteur!=$_SESSION['pseudo']))
	{
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
		$sqls = 'INSERT INTO messages_tbl(id,auteur,cible,message,objet,moment) VALUES("","Hall des enchères","'.$acheteur.'","Un autre acheteur vient de mettre une enchère pour la vente <strong>'.$objetv.'</strong>.<br />Vous récupérez donc votre ancienne enchère de <strong>'.$enchere.' Crédits</strong>.","Enchère perdue !","'.time().'")';
		$reqs = mysql_query($sqls);
		}
	$sql = 'UPDATE vente_tbl SET acheteur= "'.$_SESSION['pseudo'].'" , enchere= "'.$nenchere.'" WHERE id= "'.$idv.'"' ;
	$req = mysql_query($sql);
	$_SESSION['credits'] = $_SESSION['credits'] - $nenchere;
	$sql = 'UPDATE principal_tbl SET credits= "'.$_SESSION['credits'].'" WHERE id= "'.$_SESSION['id'].'"' ;
	$req = mysql_query($sql);
	}

print('<meta http-equiv="refresh" content="0 ; url=http://v2.dreadcast.net/ingame/engine=vae.php?'.$idv.'"> ');

mysql_close($db);
?>

</p>
</div>
<?php if($chat=="oui") 	{ if ((date("H")>=21) || (date("H")<=7)) { include("inc_bas_n_c.php"); } else { include("inc_bas_j_c.php"); } } else {	if ((date("H")>=21) || (date("H")<=7)) { include("inc_bas_n.php"); } else { include("inc_bas_j.php"); }	} ?>
<?php include("inc_bas_de_page.php"); ?>
