#!/usr/bin/php
<?php 

	if(date('H')==0 && (date('i')>=16 && date('i')<=24)){}
	else
	{
	exit();
	}

include('CENSURE');

$db = mysql_connect('localhost', 'CENSURE', 'CENSURE') or die("Erreur de connexion au serveur.");
mysql_select_db('CENSURE',$db) or die("Erreur de connexion a la base de donnees.");

$sql = 'SELECT pseudo FROM titres_tbl WHERE titre="Sain et sauf"';
$req = mysql_query($sql);
$res = mysql_num_rows($req);

for($i=0; $i != $res; $i++)
	{
	$pseudo = mysql_result($req,$i,pseudo);
	$sql1 = 'SELECT action FROM principal_tbl WHERE pseudo="'.$pseudo.'"';
	$req1 = mysql_query($sql1);
	if(mysql_num_rows($req1) != 0)
		{
		$action = mysql_result($req1,0,action);
		if($action=="mort" || $action=="Vacances") enregistre($pseudo,"survie",0);
		else enregistre($pseudo,"survie","+1");
		}
	}

$sql = 'SELECT pseudo FROM titres_tbl WHERE titre="Mentor"';
$req = mysql_query($sql);
$res = mysql_num_rows($req);

for($i=0; $i != $res; $i++)
	{
	$pseudo = mysql_result($req,$i,pseudo);
	$sql1 = 'UPDATE talent_mentor_tbl SET xp="5000" WHERE pseudo="'.$pseudo.'"';
	$req1 = mysql_query($sql1);
	}

$sql = 'INSERT INTO indicateurs_tbl(date,nb_joueurs,nb_morts,nb_cryo,masse_monetaire,nb_inscrits) VALUES
("'.mktime(12,0,0,date("m"),date("d"),date("Y")).'","'.nb_joueurs().'","'.nb_morts().'","'.nb_cryo().'","'.masse_monetaire().'","'.nb_inscrits().'");';
mysql_query($sql);

$sql = 'UPDATE principal_tbl SET jours_de_jeu = jours_de_jeu + 1';
$req = mysql_query($sql);

echo 'OK';

mysql_close($db);
?>
