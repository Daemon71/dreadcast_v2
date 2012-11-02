<?php

if($_GET['article']!="")
	{
	$db = mysql_connect('localhost', 'CENSURE', 'CENSURE') or die("Erreur de connexion au serveur des messages.");
	mysql_select_db('CENSURE',$db) or die("Erreur de connexion a la base de donnees des messages.");
	
	$sql = 'SELECT titre FROM wikast_wiki_articles_tbl WHERE id="'.$_GET['article'].'"' ;
	$req = mysql_query($sql);
	$res = mysql_num_rows($req);
	
	if($res!=0)
		{
		print('<strong>Titre</strong> : '.mysql_result($req,0,titre).'<br /><br /><hr />');
		
		$sql = 'SELECT * FROM wikast_wiki_articles_tbl WHERE titre="'.mysql_result($req,0,titre).'" ORDER BY moment DESC' ;
		$req = mysql_query($sql);
		$res = mysql_num_rows($req);
		
		
		for($i=0;$i<$res;$i++)
			{
			
			if(mysql_result($req,$i,etat) == -1) $etat = "Refus&eacute;";
			elseif(mysql_result($req,$i,etat) == 0) $etat = "Ancienne";
			elseif(mysql_result($req,$i,etat) == 1) $etat = "En attente";
			elseif(mysql_result($req,$i,etat) == 2) $etat = "Actuelle";
			
			if(ereg("[LIMITE-INTRODUCTION]",mysql_result($req,$i,contenu)))
				{
				list($intro,$article) = explode("[LIMITE-INTRODUCTION]",mysql_result($req,$i,contenu));
				}
			else
				{
				$intro = mysql_result($req,$i,contenu);
				$article = "";
				}
			print('<strong>Auteur</strong> : '.mysql_result($req,$i,createur).'<br /><strong>Date</strong> : '.date('d/m/y',mysql_result($req,$i,moment)).' &agrave; '.date('H\hi',mysql_result($req,$i,moment)).'<br /><strong>Version</strong> : '.$etat.'<br /><br /><strong>Introduction</strong> : <br /><br />'.$intro.'<br /><br /><strong>Article</strong> : <br /><br />'.$article);
			
			print('<hr />');
			}
		}
	
	mysql_close($db);
	}
?>
