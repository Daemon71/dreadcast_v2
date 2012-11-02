#!/usr/bin/php
<?php 

	if(date('H')==0 && (date('i')>=11 && date('i')<=19)){}
	else
	{
	exit();
	}

include('CENSURE');

$db = mysql_connect('localhost', 'CENSURE', 'CENSURE') or die("Erreur de connexion au serveur.");
mysql_select_db('CENSURE',$db) or die("Erreur de connexion a la base de donnees.");

$sql = 'UPDATE principal_tbl SET assurance= "0" WHERE type!= "PDG"' ;
$req = mysql_query($sql);

$sql = 'SELECT id FROM principal_tbl WHERE action= "prison"' ;
$req = mysql_query($sql);
$res = mysql_num_rows($req);

$ntime = time() - 1000;
$sql = 'INSERT INTO prisonniers_tbl(id,nombre,datea) VALUES("","'.$res.'","'.$ntime.'")' ;
$req = mysql_query($sql);

$sql = 'SELECT pseudo,total,type,entreprise FROM principal_tbl WHERE type!="Aucun" AND action!="mort"' ;
$req = mysql_query($sql);
$res = mysql_num_rows($req);
for($i=0;$i!=$res;$i++)
	{
	$sql2 = 'SELECT id FROM e_'.str_replace(" ","_",mysql_result($req,$i,entreprise)).'_tbl WHERE poste="'.mysql_result($req,$i,type).'" AND (type="chef" OR type="directeur" OR type="autre")' ;
	$req2 = mysql_query($sql2);
	$res2 = mysql_num_rows($req2);
	
	if($res2 != 0)
		{
		$pseudo = mysql_result($req,$i,pseudo);
		$total = mysql_result($req,$i,total);
		$total += strlen($total)*20;
		$sql1 = 'UPDATE principal_tbl SET total="'.$total.'" WHERE pseudo="'.$pseudo.'"' ;
		mysql_query($sql1);
		enregistre($pseudo,"travail actif","+1");
		if(in_array(strtolower(mysql_result($req,$i,entreprise)),liste_OI())) enregistre($pseudo,"acc_travail_oi","+1");
		}
	}

$sql = 'UPDATE principal_tbl SET actif= "oui" WHERE actif= "non"' ;
$req = mysql_query($sql);

$sql = 'SELECT id,fidelite FROM principal_tbl' ;
$req = mysql_query($sql);
$res = mysql_num_rows($req);
for($i=0;$i!=$res;$i++)
	{
	$id = mysql_result($req,$i,id);
	$fidelite = mysql_result($req,$i,fidelite);
	augmenter_statistique($id,'fidelite',$fidelite);
	}

$sql = 'SELECT num,rue FROM invlieu_tbl WHERE nom="Machine"' ;
$req = mysql_query($sql);
$res = mysql_num_rows($req);

for($i=0; $i != $res; $i++)
	{
	$sql1 = 'INSERT INTO invlieu_tbl(id,num,rue,nom) VALUES("","'.mysql_result($req,$i,num).'","'.mysql_result($req,$i,rue).'","Kronatium")' ;
	$req1 = mysql_query($sql1);	
	}

$sql = 'SELECT id FROM articles_tbl WHERE paru= "non" ORDER BY RAND() LIMIT 1' ;
$req = mysql_query($sql);
$res = mysql_num_rows($req);
if($res>0)
	{
	$sql1 = 'UPDATE articles_tbl SET paru= "arc" WHERE paru= "oui"' ;
	$req1 = mysql_query($sql1);
	$sql1 = 'UPDATE articles_tbl SET paru= "oui" WHERE id="'.mysql_result($req,0,id).'"' ;
	$req1 = mysql_query($sql1);
	}

$sql = 'SELECT id FROM tips_tbl WHERE paru= "non" ORDER BY RAND() LIMIT 1' ;
$req = mysql_query($sql);
$res = mysql_num_rows($req);
if($res>0)
	{
	$sql1 = 'UPDATE tips_tbl SET paru= "arc" WHERE paru= "oui"' ;
	$req1 = mysql_query($sql1);
	$sql1 = 'UPDATE tips_tbl SET paru= "oui" WHERE id="'.mysql_result($req,0,id).'"' ;
	$req1 = mysql_query($sql1);
	}

$sql = 'SELECT id FROM citations_tbl WHERE paru= "non" ORDER BY RAND() LIMIT 1' ;
$req = mysql_query($sql);
$res = mysql_num_rows($req);
if($res>0)
	{
	$sql1 = 'UPDATE citations_tbl SET paru= "arc" WHERE paru= "oui"' ;
	$req1 = mysql_query($sql1);
	$sql1 = 'UPDATE citations_tbl SET paru= "oui" WHERE id="'.mysql_result($req,0,id).'"' ;
	$req1 = mysql_query($sql1);
	}

echo 'OK';

mysql_close($db);
?>
