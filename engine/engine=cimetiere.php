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
			Cimetière
		</div>
		<b class="module4ie"><a href="engine.php" class="module4"><img src="im_objets/icon_retour.gif" alt="Retour" />Retour</a></b>
</p>
	</div>
</div>
<div id="centre">
<p class="messagesvip">

<?php	

$db = mysql_connect('localhost', 'CENSURE', 'CENSURE') or die("Erreur de connexion au serveur.");
mysql_select_db('CENSURE',$db) or die("Erreur de connexion a la base de donnees.");

$sql = 'SELECT num,rue FROM lieux_speciaux_tbl WHERE type="cimetiere"';
$req = mysql_query($sql);
$res = mysql_num_rows($req);
if($res)
	{
	if($_SESSION['num'] != mysql_result($req,0,num) || strtolower($_SESSION['lieu']) != strtolower(mysql_result($req,0,rue)))
		{
		print('<meta http-equiv="refresh" content="0 ; url=http://v2.dreadcast.net/ingame/engine.php"> ');
		exit();
		}
	}

if(!empty($_GET['l'])) $sql = 'SELECT DISTINCT pseudo FROM abandons_tbl WHERE pseudo LIKE "'.$_GET['l'].'%" ORDER BY pseudo';
else $sql = 'SELECT id FROM abandons_tbl ORDER BY pseudo';
$req = mysql_query($sql);
$res = mysql_num_rows($req);

if(empty($_GET['l']))
	{
	print('<br /><br /><br /><strong>Vous êtes au cimetière de la ville.</strong><br />
	Vous trouverez ici les noms des '.$res.' citoyens morts ou disparus.<br /><br />Le classement tombal est fait par ordre alphabétique.<br />Cliquez sur une lettre pour débuter la recherche.<br /><br />');
	for($i="A";$i!="Z";$i++) print('<a href="engine=cimetiere.php?l='.$i.'">'.$i.'</a> ');
	print('<a href="engine=cimetiere.php?l=Z">Z</a> ');
	}
else
	{
	print('Il y a '.$res.' résultats pour la lettre '.$_GET['l'].' - <a href="engine=cimetiere.php">Retour</a><br /><br />');
	for($i=0;$i!=$res;$i++)	{
		$pseudo = mysql_result($req,$i,pseudo);
		$sql2 = 'SELECT id FROM wikast_edc_articles_tbl WHERE auteur="'.$pseudo.'" AND contenu NOT LIKE "[centrer]Bienvenue sur votre Espace DreadCast, citoyen [g]'.$pseudo.'[/g].[/centrer]%"';
		$req2 = mysql_query($sql2);
		if(mysql_num_rows($req2)) print('<a href="http://v2.dreadcast.net/wikast/edc=visio.php?auteur='.$pseudo.'" onclick="window.open(this.href); return false;">'.$pseudo.'</a><br />');
		else print($pseudo.'<br />');
	}
	print('<br /><a href="engine=cimetiere.php">Retour</a>');
	}

mysql_close($db);

?>

</p>
</div>
<?php if($chat=="oui") 	{ if ((date("H")>=21) || (date("H")<=7)) { include("inc_bas_n_c.php"); } else { include("inc_bas_j_c.php"); } } else {	if ((date("H")>=21) || (date("H")<=7)) { include("inc_bas_n.php"); } else { include("inc_bas_j.php"); }	} ?>
<?php include("inc_bas_de_page.php"); ?>
