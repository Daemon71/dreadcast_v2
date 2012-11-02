#!/usr/bin/php
<?php 
$db = mysql_connect('localhost', 'CENSURE', 'CENSURE') or die("Erreur de connexion au serveur.");
mysql_select_db('CENSURE',$db) or die("Erreur de connexion a la base de donnees.");

$sql = 'SELECT * FROM principal_tbl WHERE maladie= "1" AND action!="mort"';
$req = mysql_query($sql);
$res = mysql_num_rows($req);
for($i=0;$i!=$res;$i++)
	{
	$id = mysql_result($req,$i,id);
	$rand = rand(1,3);
	if($rand==1)
		{
		$sql = 'UPDATE principal_tbl SET fatigue=fatigue-20 WHERE id= "'.$id.'" AND fatigue>"20"';
		$req = mysql_query($sql);
		}
	elseif($rand==2)
		{
		$sql = 'UPDATE principal_tbl SET sante=sante-20 WHERE id= "'.$id.'" AND sante>"20"';
		$req = mysql_query($sql);
		}
	else
		{
		$sql = 'UPDATE principal_tbl SET resistance=resistance-1 WHERE id= "'.$id.'" AND resistance>"0"';
		$req = mysql_query($sql);
		}
	}

$sql = 'UPDATE principal_tbl SET maladie= "0" WHERE action="mort"';
$req = mysql_query($sql);

mysql_close($db);
?>
