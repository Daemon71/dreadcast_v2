<?php 

$db = mysql_connect('localhost', 'CENSURE', 'CENSURE') or die("Erreur de connexion au serveur des messages.");
mysql_select_db('CENSURE',$db) or die("Erreur de connexion a la base de donnees des messages.");

$sql = 'SELECT id FROM messages_tbl WHERE idrep!="0"';
$req = mysql_query($sql);
$res = mysql_num_rows($req);

for($i=0;$i<$res;$i++)
	{
	$sql = 'UPDATE messages_tbl SET idrep="0" WHERE id='.mysql_result($req,$i,id);
	mysql_query($sql);
	}

mysql_close($db);

?>
