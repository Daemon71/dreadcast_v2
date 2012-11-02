<?php
session_start();

if($_SESSION['statut']!="Administrateur")
	{
	print('<meta http-equiv="refresh" content="0 ; url=http://v2.dreadcast.net/ingame/engine.php"> ');
	exit();
	}

$db = mysql_connect('localhost', 'CENSURE', 'CENSURE') or die("Erreur de connexion au serveur des messages.");
mysql_select_db('CENSURE',$db) or die("Erreur de connexion a la base de donnees des messages.");

$sql = 'SELECT * FROM messagesadmin_archives_tbl WHERE auteur="'.$_GET['pseudo'].'" ORDER BY id DESC';
$req = mysql_query($sql);
$res = mysql_num_rows($req);

print('<h3>'.$_GET['pseudo'].'</h3>');

for($i=0;$i<$res;$i++)
	{
	print('<br /><hr />
	'.mysql_result($req,$i,objet).' - le '.date('d/m/y',mysql_result($req,$i,moment)).' à '.date('H\hi',mysql_result($req,$i,moment)).'<br />
	'.mysql_result($req,$i,message)
	);
	}

mysql_close($db);
?>
