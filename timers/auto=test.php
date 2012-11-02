#!/usr/bin/php
<?php 
include('CENSURE');

$db = mysql_connect('localhost', 'CENSURE', 'CENSURE') or die("Erreur de connexion au serveur.");
mysql_select_db('CENSURE',$db) or die("Erreur de connexion a la base de donnees.");

message("Overflow","Ok","Ca marche");

mysql_close($db);

?>
