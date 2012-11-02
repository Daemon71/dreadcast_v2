<?php
if(($_SESSION['statut']=="Administrateur") OR ($_SESSION['statut']=="ModÈrateur communication") OR ($_SESSION['statut']=="DÈveloppeur"))
	{
	print('<div style="position:absolute;left:-210px;top:80px;width:200px;min-height:100px;background:#bbb;border:1px solid #656565;">');
	
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
	
	print('<br /><br /><span style="font-weight:bold;">ADMINISTRATION</span><br /><a href="engine=contacteradmin.php">Envoyer un message admin</a><br /><a href="engine=contacteradmin.php?p=1">Envoyer un message platinium</a>');
	if(($_SESSION['statut']=="Administrateur") or ($_SESSION['statut']=="ModÈrateur RPG") or ($_SESSION['statut']=="DÈveloppeur"))
		{
		print('<br /><a href="engine=nommer.php">Nommer un nouveau politique</a>');
		}
	if(($_SESSION['statut']=="Administrateur") or ($_SESSION['statut']=="DÈveloppeur"))
		{
		print('<br /><a href="engine=ajoutnews.php">Ajouter une news</a><br /><a href="engine=commande.php">Contr&ocirc;le</a>');
		}
	
	print('<br /><br /><span style="font-weight:bold;">UTILITAIRES</span><br />');
	print('<a href="engine=panneauboiteaidee.php">Boites &agrave; id&eacute;es</a><br />');
	print('<a href="stats=graphs.php">Statistiques graphiques</a>');
			
	print('<br /><br /><span style="font-weight:bold;">Triche</span><br />');
	print('<a href="engine=panneau.php?triche=bouffe">Remonter sa nourriture</a><br />');
	print('<a href="engine=panneau.php?triche=argent">S\'octroyer 5000 cr&eacute;dits :D</a>');
	print('</div>');
    mysql_close($db);
}
?>
