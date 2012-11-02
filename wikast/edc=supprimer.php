<?php
session_start();

if($_SESSION['id']=="")
	{
	print('<meta http-equiv="refresh" content="0 ; url=index.php"> ');
	exit();
	}

if($_GET['article']=="")
	{
	print('<meta http-equiv="refresh" content="0 ; url=edc.php"> ');
	exit();
	}

$db = mysql_connect('localhost', 'CENSURE', 'CENSURE') or die("Erreur de connexion au serveur des messages.");
mysql_select_db('CENSURE',$db) or die("Erreur de connexion a la base de donnees des messages.");

$sql = 'SELECT * FROM wikast_edc_articles_tbl WHERE auteur="'.$_SESSION['pseudo'].'" AND id="'.$_GET['article'].'"';
$req = mysql_query($sql);
$res = mysql_num_rows($req);
					
if($res == 0 AND $_SESSION['statut'] != "Administrateur")
	{
	print('<meta http-equiv="refresh" content="0 ; url=edc.php"> ');
	exit();
	}

$sql = 'DELETE FROM wikast_edc_articles_tbl WHERE id="'.$_GET['article'].'"';
mysql_query($sql);

$sql = 'DELETE FROM wikast_edc_commentaires_tbl WHERE article="'.$_GET['article'].'"';
mysql_query($sql);

print('<meta http-equiv="refresh" content="0 ; url=edc.php"> ');
exit();

mysql_close($db);

?>
