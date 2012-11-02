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
			Achat d'un stock
		</div>
		<b class="module4ie"><a href="engine=usine.php" class="module4"><img src="im_objets/icon_retour.gif" alt="Retour" />Retour</a></b>
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

$sql = 'SELECT entreprise,type FROM principal_tbl WHERE id= "'.$_SESSION['id'].'"' ;
$req = mysql_query($sql);
$_SESSION['entreprise'] = mysql_result($req,0,entreprise);
$type = mysql_result($req,0,type);

if($_SESSION['entreprise']!="Aucune")
	{
	$sql = 'SELECT bdd FROM `e_'.str_replace(" ","_",''.$_SESSION['entreprise'].'').'_tbl` WHERE poste= "'.$type.'"' ;
	$req = mysql_query($sql);
	$bdd = mysql_result($req,0,bdd);
	$sql = 'SELECT type,budget FROM entreprises_tbl WHERE nom= "'.$_SESSION['entreprise'].'"' ;
	$req = mysql_query($sql);
	$budgetent = mysql_result($req,0,budget);
	$typent = mysql_result($req,0,type);
	}
else
	{
	$bdd = "";
	}

$sql = 'SELECT nom,type FROM entreprises_tbl WHERE rue= "'.$_SESSION['lieu'].'" AND num= "'.$_SESSION['num'].'"' ;
$req = mysql_query($sql);
$type = mysql_result($req,0,type);
$noment = mysql_result($req,0,nom);

if(($type!="usine de production") || ($bdd==""))
	{
	print('<meta http-equiv="refresh" content="0 ; url=http://v2.dreadcast.net/ingame/engine.php"> ');
	exit();
	}

$sql = 'SELECT nom,type,puissance FROM objets_tbl WHERE id= "'.$_GET['id'].'"' ;
$req = mysql_query($sql);
$typeo = mysql_result($req,0,type);
$puissance = mysql_result($req,0,puissance);
$_SESSION['objet'] = mysql_result($req,0,nom);

$sql = 'SELECT nombre,pvente FROM stocks_tbl WHERE objet= "'.$_SESSION['objet'].'" AND entreprise= "'.$noment.'"' ;
$req = mysql_query($sql);
$res = mysql_num_rows($req);
if($res==0)
	{
	print('<meta http-equiv="refresh" content="0 ; url=http://v2.dreadcast.net/ingame/engine=usine.php"> ');
	exit();
	}

$nombre = mysql_result($req,0,nombre);
if($nombre<10)
	{
	print('<meta http-equiv="refresh" content="0 ; url=http://v2.dreadcast.net/ingame/engine=usine.php"> ');
	exit();
	}
$pvente = mysql_result($req,0,pvente) * 10 ;

$sql = 'SELECT budget,benefices FROM entreprises_tbl WHERE nom= "'.$noment.'"' ;
$req = mysql_query($sql);
$budget = mysql_result($req,0,budget);
$benef = mysql_result($req,0,benefices);

if(($typeo=="objet") || ($typeo=="oa") || ($typeo=="om") || ($typeo=="tissu") || ($typeo=="soie") || ($typeo=="cristal") || ($typeo=="sac"))
	{
	if($typent!="boutique spécialisee")
		{
		print('<p align="center"><em>Il faut &ecirc;tre r&eacute;sponsable d\'une boutique d\'objets pour acheter ce stock.</em></p>');
		$okach = 1;
		}
	}
elseif(($typeo=="armestir") || ($typeo=="armesprim") || ($typeo=="acac") || ($typeo=="armesav") || ($typeo=="modif"))
	{
	if($typent!="boutique armes")
		{
		print('<p align="center"><em>Il faut &ecirc;tre r&eacute;sponsable d\'une boutique d\'armes pour acheter ce stock.</em></p>');
		$okach = 1;
		}
	}

if($budgetent<$pvente)
	{
	print('<i>Vous n\'avez pas assez d\'argent : le prix est de '.$pvente.' Cr&eacute;dits.</i>');
	$okach = 1;
	}

if($okach!=1)
	{
	$budgetent = $budgetent - $pvente;
	
	$nombre = $nombre - 10;
	$budget = $budget + $pvente;
	$benef = $benef + $pvente;
	$sql = 'UPDATE entreprises_tbl SET budget= "'.$budget.'" , benefices= "'.$benef.'" WHERE nom= "'.$noment.'"' ;
	$req = mysql_query($sql);
	$sql = 'UPDATE entreprises_tbl SET budget= "'.$budgetent.'" WHERE nom= "'.$_SESSION['entreprise'].'"' ;
	$req = mysql_query($sql);
	$sql = 'UPDATE stocks_tbl SET nombre= "'.$nombre.'" WHERE entreprise= "'.$noment.'" AND objet= "'.$_SESSION['objet'].'"' ;
	$req = mysql_query($sql);
	$sql = 'INSERT INTO achats_tbl(id,acheteur,vendeur,objet,moment,prix) VALUES("","'.$_SESSION['entreprise'].'","'.$noment.'","'.$_SESSION['objet'].'","'.time().'","'.$pvente.'")' ;
	$req = mysql_query($sql);
	
	
	$sql = 'SELECT nombre FROM stocks_tbl WHERE entreprise= "'.$_SESSION['entreprise'].'" AND objet= "'.$_SESSION['objet'].'"' ;
	$req = mysql_query($sql);
	$res = mysql_num_rows($req);
	
	if($res!=0)
		{
		$nombre = mysql_result($req,0,nombre);
		$nombre = $nombre + 10;
		$sql = 'UPDATE stocks_tbl SET nombre= "'.$nombre.'" WHERE entreprise= "'.$_SESSION['entreprise'].'" AND objet= "'.$_SESSION['objet'].'"' ;
		$req = mysql_query($sql);
		}
	else
		{
		$pvente = $pvente / 10;
		$sql = 'INSERT INTO stocks_tbl(id,entreprise,objet,nombre,pvente) VALUES("","'.$_SESSION['entreprise'].'","'.$_SESSION['objet'].'","10","'.$pvente.'")' ;
		$req = mysql_query($sql);
		}
	
	print('<meta http-equiv="refresh" content="0 ; url=http://v2.dreadcast.net/ingame/engine=stocks.php"> ');
	}

mysql_close($db);

?>

</p>
</div>
<?php if($chat=="oui") 	{ if ((date("H")>=21) || (date("H")<=7)) { include("inc_bas_n_c.php"); } else { include("inc_bas_j_c.php"); } } else {	if ((date("H")>=21) || (date("H")<=7)) { include("inc_bas_n.php"); } else { include("inc_bas_j.php"); }	} ?>
<?php include("inc_bas_de_page.php"); ?>
