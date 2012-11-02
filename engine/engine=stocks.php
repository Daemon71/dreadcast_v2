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

$sql = 'SELECT bdd FROM `e_'.str_replace(" ","_",''.$_SESSION['entreprise'].'').'_tbl` WHERE poste= "'.$_SESSION['poste'].'"' ;
$req = mysql_query($sql);
$_SESSION['bdd'] = mysql_result($req,0,bdd); 

$sql = 'SELECT type,budget FROM entreprises_tbl WHERE nom= "'.$_SESSION['entreprise'].'"' ;
$req = mysql_query($sql);
$type = mysql_result($req,0,type); 
$budget = mysql_result($req,0,budget); 

if($_SESSION['bdd']=="") { print('<meta http-equiv="refresh" content="0 ; url=http://v2.dreadcast.net/ingame/engine.php"> '); exit(); }

mysql_close($db);
?>
<?php if ((date("H")>=21) || (date("H")<=7)) { include("inc_haut_n.php"); } else { include("inc_haut_j.php");} ?>

<div id="haut">
	<?php include("inc_entete.php"); ?>
	<div class="haut_aligngauche_haut">
		<p>
		<div class="titrepage">
			Stocks de l'entreprise<br />
			<strong>Capital :</strong><em> <?php print(''.$budget.''); ?> Crédits</em>
		</div>
		<b class="module4ie"><a href="engine=redirt.php" class="module4"><img src="im_objets/icon_retour.gif" alt="Retour" />Retour</a></b>
		</p>
	</div>
</div>
<div id="centre">
<p>

<?php 

$db = mysql_connect('localhost', 'CENSURE', 'CENSURE') or die("Erreur de connexion au serveur des messages.");
mysql_select_db('CENSURE',$db) or die("Erreur de connexion a la base de donnees des messages.");

$sql = 'SELECT * FROM stocks_tbl WHERE entreprise="'.$_SESSION['entreprise'].'"' ;
$req = mysql_query($sql);
$res = mysql_num_rows($req);

print('<div class="messagesvip">');

for($i=0; $i != $res ; $i++) 
	{
	$idi = mysql_result($req,$i,id); 
	$objet = mysql_result($req,$i,objet); 
	$nombre = mysql_result($req,$i,nombre); 
	
	if($objet!="Soins")
		{
		print('<div align="center"><a href="engine=stocksconsult.php?'.ucwords($objet).'">'.ucwords($objet).'</a> ('.$nombre.')</div>');
		}
	else
		{
		print('<div align="center"><a href="engine=stocksconsult.php?'.ucwords($objet).'">Soigner un point de vie</a></div>');
		}
	}

mysql_close($db);

?></p>
		<?php 
if($type=="usine de production")
	{
	print('<p align="center"><a href="engine=production.php">Consulter la liste des objets qu\'il est possible de fabriquer</a></p>');
	}
elseif($type=="agence immobiliaire")
	{
	print('<p align="center"><a href="engine=production.php">Consulter la liste des logements qu\'il est possible de construire</a></p>');
	}
elseif($type=="bar cafe")
	{
	print('<p align="center"><a href="engine=production.php">Consulter la liste des consomations qu\'il est possible de produire</a></p>');
	}
elseif(($type=="boutique armes") || ($type=="boutique spécialisee"))
	{
	print('<p align="center"><a href="engine=recherche.php?usine%20de%20production">Chercher une usine de production</a></p>');
	}

print('</div>');

?>

</p>
</div>
<?php if($chat=="oui") 	{ if ((date("H")>=21) || (date("H")<=7)) { include("inc_bas_n_c.php"); } else { include("inc_bas_j_c.php"); } } else {	if ((date("H")>=21) || (date("H")<=7)) { include("inc_bas_n.php"); } else { include("inc_bas_j.php"); }	} ?>
<?php include("inc_bas_de_page.php"); ?>
