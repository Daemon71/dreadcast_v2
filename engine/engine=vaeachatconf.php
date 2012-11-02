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
		</p>
	</div>
</div>
<div id="centre">
<p>

<img src="im_objets/loader.gif" alt="..." /><br /><br />

<?php	

$db = mysql_connect('localhost', 'CENSURE', 'CENSURE') or die("Erreur de connexion au serveur.");
mysql_select_db('CENSURE',$db) or die("Erreur de connexion a la base de donnees.");

$sql = 'SELECT id,rue,num,fatigue FROM principal_tbl WHERE pseudo= "'.$_SESSION['pseudo'].'"' ;
$req = mysql_query($sql);
$_SESSION['id'] = mysql_result($req,0,id);
$_SESSION['lieu'] = mysql_result($req,0,rue);
$_SESSION['num'] = mysql_result($req,0,num);
$_SESSION['fatigue'] = mysql_result($req,0,fatigue);
$_SESSION['lieu'] = strtolower($_SESSION['lieu']);
$_SESSION['lieu'] = ucwords($_SESSION['lieu']);

$sql = 'SELECT nom,type FROM entreprises_tbl WHERE rue= "'.$_SESSION['lieu'].'" AND num= "'.$_SESSION['num'].'"' ;
$req = mysql_query($sql);
$type = mysql_result($req,0,type);
$noment = mysql_result($req,0,nom);

if($type!="ventes aux encheres")
	{
	print('<meta http-equiv="refresh" content="0 ; url=http://v2.dreadcast.net/ingame/engine.php"> ');
	exit();
	}

$sql = 'SELECT * FROM vente_tbl WHERE id= "'.$_GET['id'].'"' ;
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

if($pvente==0)
	{
	print('<meta http-equiv="refresh" content="0 ; url=http://v2.dreadcast.net/ingame/engine=vae.php"> ');
	exit();
	}

$sql = 'SELECT budget,benefices FROM entreprises_tbl WHERE nom= "'.$noment.'"' ;
$req = mysql_query($sql);
$budget = mysql_result($req,0,budget);
$benef = mysql_result($req,0,benefices);

$sql = 'SELECT case1,case2,case3,case4,case5,case6,credits FROM principal_tbl WHERE id= "'.$_SESSION['id'].'"' ;
$req = mysql_query($sql);
$_SESSION['credits'] = mysql_result($req,0,credits);
$_SESSION['case1'] = mysql_result($req,0,case1);
$_SESSION['case2'] = mysql_result($req,0,case2);
$_SESSION['case3'] = mysql_result($req,0,case3);
$_SESSION['case4'] = mysql_result($req,0,case4);
$_SESSION['case5'] = mysql_result($req,0,case5);
$_SESSION['case6'] = mysql_result($req,0,case6);

if($_SESSION['credits']<$pvente)
	{
	print('<center><strong>Vous n\'avez pas assez d\'argent. <br><br>Le prix est de '.$pvente.' Cr&eacute;dits.</strong></center>');
	exit();
	}

if(($_SESSION['case1']!="Vide") && ($_SESSION['case2']!="Vide") && ($_SESSION['case3']!="Vide") && ($_SESSION['case4']!="Vide") && ($_SESSION['case5']!="Vide") && ($_SESSION['case6']!="Vide"))
	{
	print("<center><strong>Vous n'avez pas d'emplacement vide dans votre inventaire.</strong></center>");
	}
else
	{
	for($i=1; $i != 7; $i++)
		{
		if(($_SESSION['case'.$i.'']=="Vide") && ($l!=1))
			{
			$sql = 'UPDATE principal_tbl SET case'.$i.'= "'.$objetv.'" WHERE id= "'.$_SESSION['id'].'"' ;
			$req = mysql_query($sql);
			$l = 1;
			}
		}
	
	$sql = 'SELECT nombre FROM stocks_tbl WHERE entreprise= "'.$noment.'"' ;
	$req = mysql_query($sql);
	$pourc = mysql_result($req,0,nombre);
	
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
		$sqls = 'INSERT INTO messages_tbl(id,auteur,cible,message,objet,moment) VALUES("","Hall des enchères","'.$acheteur.'","Un autre acheteur vient de mettre une enchère pour le produit <strong>'.$objetv.'</strong>.<br />Vous récupérez donc votre ancienne enchère de <strong>'.$enchere.' Crédits</strong>.","Enchère perdue !","'.time().'")';
		$reqs = mysql_query($sqls);
		}
	
	$npv = $pvente;
	$sql = 'UPDATE principal_tbl SET credits=credits+"'.$pvente.'" WHERE pseudo="'.$vendeur.'"' ;
	$req = mysql_query($sql);
	$sqls = 'INSERT INTO messages_tbl(id,auteur,cible,message,objet,moment) VALUES("","Hall des enchères","'.$vendeur.'","Un acheteur vient d\'acheter votre produit <strong>'.$objetv.'</strong>.<br />Vous gagnez donc <strong>'.$npv.' Crédits</strong>.","Objet vendu !","'.time().'")';
	$reqs = mysql_query($sqls);
	
	$sql = 'SELECT type,ecart FROM objets_tbl WHERE nom= "'.$objetv.'"' ;
	$req = mysql_query($sql);
	
	if(mysql_result($req,0,type)=="sac" && mysql_result($req,0,ecart)=="1")
		{
		$sqls = 'INSERT INTO messages_tbl(id,auteur,cible,message,objet,moment) VALUES("","Hall des enchères","'.$_SESSION['pseudo'].'","Vous venez d\'acheter un coffre.<br />Voici la combinaison: <strong>'.mysql_result($req,0,distance).'</strong>","Combinaison","'.time().'")';
		$reqs = mysql_query($sqls);
		}
	
	$sql = 'SELECT credits FROM principal_tbl WHERE id= "'.$_SESSION['id'].'"' ;
	$req = mysql_query($sql);
	$_SESSION['credits'] = mysql_result($req,0,credits) - $pvente - ceil($pourc*$pvente/100);
	
	$marge = ceil(($pourc/100)*$pvente);
	
	$budget = $budget + ceil(($pourc/100)*$pvente);
	$benef = $benef + ceil(($pourc/100)*$pvente);
	$sql = 'UPDATE entreprises_tbl SET budget= "'.$budget.'" , benefices= "'.$benef.'" WHERE nom= "'.$noment.'"' ;
	$req = mysql_query($sql);
	$sql = 'UPDATE principal_tbl SET credits= "'.$_SESSION['credits'].'" WHERE id= "'.$_SESSION['id'].'"' ;
	$req = mysql_query($sql);
	$sql = 'DELETE FROM vente_tbl WHERE id= "'.$_GET['id'].'"' ;
	$req = mysql_query($sql);
	$sql = 'INSERT INTO achats_tbl(id,acheteur,vendeur,objet,moment,prix) VALUES("","'.$_SESSION['pseudo'].'","'.$noment.'","'.$objetv.'","'.time().'","'.$marge.'")' ;
	$req = mysql_query($sql);


	print('<meta http-equiv="refresh" content="0 ; url=http://v2.dreadcast.net/ingame/engine=inventaire.php"> ');
	}

mysql_close($db);

?> 
</p>
</div>
<?php if($chat=="oui") 	{ if ((date("H")>=21) || (date("H")<=7)) { include("inc_bas_n_c.php"); } else { include("inc_bas_j_c.php"); } } else {	if ((date("H")>=21) || (date("H")<=7)) { include("inc_bas_n.php"); } else { include("inc_bas_j.php"); }	} ?>
<?php include("inc_bas_de_page.php"); ?>
