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
if($_SESSION['statut']!="Administrateur")
	{
	print('<meta http-equiv="refresh" content="0 ; url=http://v2.dreadcast.net/ingame/engine.php"> ');
	exit();
	}
?>
<?php if ((date("H")>=21) || (date("H")<=7)) { include("inc_haut_n.php"); } else { include("inc_haut_j.php");} ?>

<div id="haut">
	<?php include("inc_entete.php"); ?>
	<div class="haut_aligngauche">
		<p>
		<div class="titrepage">
			Modifier un message admin
		</div>
		<b class="module4ie"><a href="engine=voirmessageadmin.php?id=<?php print($_SERVER['QUERY_STRING']); ?>" class="module4"><img src="im_objets/icon_retour.gif" alt="Retour" />Retour</a></b>
		</p>
	</div>
</div>
<?php
if(($_SESSION['statut']=="Administrateur") OR ($_SESSION['statut']=="Modérateur communication") OR ($_SESSION['statut']=="Développeur"))
	{
	print('<div style="position:absolute;left:-210px;top:110px;width:200px;min-height:100px;background:#bbb;border:1px solid #656565;">');
	
	$db = mysql_connect('localhost', 'CENSURE', 'CENSURE') or die("Erreur de connexion au serveur des messages.");
	mysql_select_db('CENSURE',$db) or die("Erreur de connexion a la base de donnees des messages.");
	
	print('<p style="text-align:center;"><span style="font-weight:bold;">WIKAST</span><br />');
	
	$sql3 = 'SELECT id FROM wikast_wiki_articles_tbl WHERE etat = 1' ;
	$req3 = mysql_query($sql3);
	$res3 = mysql_num_rows($req3);
	
	if($res3!=0) print('<a href="engine=wikiliste.php">Articles Wiki en attente</a> ('.$res3.')');
	else print('Aucun article Wiki en attente');
	print('<br /><a href="../wikast/wiki=recherche.php">Voir un article Wiki complet</a>');
	
	print('<br /><br /><span style="font-weight:bold;">OBJETS UNIQUES</span><br />');
	$sql3 = 'SELECT id FROM objets_tbl WHERE prod = -1' ;
	$req3 = mysql_query($sql3);
	$res3 = mysql_num_rows($req3);
	
	if($res3!=0) print('<a href="engine=planadmin.php">Plans d\'objets en attente</a> ('.$res3.')');
	else print('Aucun plan en attente');
	
	print('<br /><br /><span style="font-weight:bold;">ADMINISTRATION</span><br /><a href="engine=contacteradmin.php">Envoyer un message admin</a>');
	if(($_SESSION['statut']=="Administrateur") or ($_SESSION['statut']=="Modérateur RPG") or ($_SESSION['statut']=="Développeur"))
		{
		print('<br /><a href="engine=nommer.php">Nommer un nouveau politique</a>');
		}
	if(($_SESSION['statut']=="Administrateur") or ($_SESSION['statut']=="Développeur"))
		{
		print('<br /><a href="engine=ajoutnews.php">Ajouter une news</a><br /><a href="engine=commande.php">Contr&ocirc;le</a>');
		}
	
	print('<br /><br /><span style="font-weight:bold;">UTILITAIRES</span><br />');
	print('<a href="engine=panneauboiteaidee.php">Boites &agrave; id&eacute;es</a><br />
			<a href="stats=toutes.php" target="_blank">Statistiques g&eacute;n&eacute;rales</a><br />
			<a href="stats=partenaires.php" target="_blank">Statistiques partenaires</a><br />
			<a href="stats=graphs.php" target="_blank">Statistiques graphiques</a><br />
			<a href="stats=blacklist.php" target="_blank">Liste noire</a>');
	print('</div>');
    mysql_close($db);
}
?>
<div id="centre">
<p>

<?php 
$message = $_SERVER['QUERY_STRING'];

$db = mysql_connect('localhost', 'CENSURE', 'CENSURE') or die("Erreur de connexion au serveur des messages.");
mysql_select_db('CENSURE',$db) or die("Erreur de connexion a la base de donnees des messages.");

if($_POST['message']!="")
	{
	$sql = 'UPDATE messagesadmin_tbl SET commentaires= "'.str_replace("\n","<br />",$_POST['message']).'" WHERE id='.$message.'' ;
	$req = mysql_query($sql);
	
	print('<meta http-equiv="refresh" content="0 ; url=engine=voirmessageadmin.php?id='.$_SERVER['QUERY_STRING'].'"> ');
	exit();
	}
$sql = 'SELECT commentaires FROM messagesadmin_tbl WHERE id='.$message.'' ;
$req = mysql_query($sql);
$contenu = mysql_result($req,0,commentaires);

mysql_close($db);

?>

<form action="#" method="post">
	Commentaires sur ce message:<br />
	<textarea name="message" cols="50" rows="15"><?php print('['.date('d-m-Y').']['.date('H:i').']['.$_SESSION['pseudo'].'][MESSAGE]
'); print(str_replace("<br />","",$contenu)); ?></textarea><br />
	<input type="submit" value="Valider" />
</form>

</p>
</div>
<?php if($chat=="oui") 	{ if ((date("H")>=21) || (date("H")<=7)) { include("inc_bas_n_c.php"); } else { include("inc_bas_j_c.php"); } } else {	if ((date("H")>=21) || (date("H")<=7)) { include("inc_bas_n.php"); } else { include("inc_bas_j.php"); }	} ?>
<?php include("inc_bas_de_page.php"); ?>
