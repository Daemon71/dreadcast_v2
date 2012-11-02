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
			Recherche
		</div>
		<b class="module4ie"><a href="engine.php" class="module4"><img src="im_objets/icon_retour.gif" alt="Retour" />Retour</a></b>
</p>
	</div>
</div>
<div id="centre_rue">


<?php 

if($_SERVER['QUERY_STRING']=="")
	{
	$recherche = $_POST['recherche'];
	}
elseif($_SERVER['QUERY_STRING']=="Submit22=Chercher+une+usine+de+production")
	{
	$recherche = "usine de production";
	}
elseif($_SERVER['QUERY_STRING']=="boutique%20sp%E9cialisee")
	{
	$recherche = "boutique spécialisee";
	}
else
	{
	$recherche = str_replace("%20"," ",''.$_SERVER['QUERY_STRING'].'');
	}

$db = mysql_connect('localhost', 'CENSURE', 'CENSURE') or die("Erreur de connexion au serveur des messages.");
mysql_select_db('CENSURE',$db) or die("Erreur de connexion a la base de donnees des messages.");

if($recherche=="ventes aux encheres")
	{
	$sql = 'SELECT id,nom,num,rue,logo FROM entreprises_tbl WHERE type= "'.$recherche.'"' ;
	$req = mysql_query($sql);
	$res = mysql_num_rows($req);
	}
elseif ($recherche != "di2rco")
	{
	$sql = 'SELECT id,nom,num,rue,logo FROM entreprises_tbl WHERE type= "'.$recherche.'" AND ouvert="oui"' ;
	$req = mysql_query($sql);
	$res = mysql_num_rows($req);
	}

if($res==0)
	{
	print('
	<p id="location">Il n\'y a pas d\'entreprise ouverte<br />qui corresponde &agrave; votre recherche.</p>');
	}
elseif($res!=0)
	{
	$i = rand(0,$res-1);
	$nom = mysql_result($req,$i,nom);
	$rue = mysql_result($req,$i,rue);
	$num = mysql_result($req,$i,num);
	$logo = mysql_result($req,$i,logo);
	print('
	<p id="location2"><strong>R&eacute;sultat de la recherche :</strong><br />
	<strong>Adresse : </strong><i>'.$num.' '.ucwords($rue).'</i><br />
	<strong>Nom de l\'entreprise : </strong><i>'.$nom.'</i></p>');
	print('<br /><br /><br /><br /><br /><br /><br /><br /><br /><br /><table width="97%"  border="0" align="center"><tr><td><p align="center">');
	print('<img src="'.$logo.'" border="1px" width="100px" height="100px" /></p></td><td>');
	print('<p align="center"><a href="engine=go.php?num='.$num.'&rue='.$rue.'">S\'y rendre</a><br /><br>');
	print('<a href="engine=recherche.php?'.$recherche.'">En chercher une autre</a></p></td></tr></table>');	
	}

mysql_close($db);

?>


</div>
<?php if($chat=="oui") 	{ if ((date("H")>=21) || (date("H")<=7)) { include("inc_bas_n_c.php"); } else { include("inc_bas_j_c.php"); } } else {	if ((date("H")>=21) || (date("H")<=7)) { include("inc_bas_n.php"); } else { include("inc_bas_j.php"); }	} ?>
<?php include("inc_bas_de_page.php"); ?>
