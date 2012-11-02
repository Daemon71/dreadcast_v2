				<div id="forum-derniers">
					<!-- AFFICHAGE DES RUBRIQUES -->
					<a class="titre" href="forum=derniers.php" style="display:block;">Derniers sujets</a>
					<ul>

<?php  

$db = mysql_connect('localhost', 'CENSURE', 'CENSURE') or die("Erreur de connexion au serveur des messages.");
mysql_select_db('CENSURE',$db) or die("Erreur de connexion a la base de donnees des messages.");

$sql = 'SELECT id, nom, categorie FROM wikast_forum_sujets_tbl ORDER BY datemodif DESC' ;
$req = mysql_query($sql);
if ($req)
$res = mysql_num_rows($req);
else $res = 0;

if($_SESSION['pseudo']!="")
	{
	$sqlhop = 'SELECT sujets_vu FROM wikast_joueurs_tbl WHERE pseudo="'.$_SESSION['pseudo'].'"' ;
	$reqhop = mysql_query($sqlhop);
	$reshop = mysql_num_rows($reqhop);
	
	if($reshop!=0) $sujets_vu = mysql_result($reqhop,0,sujets_vu);
	else $sujets_vu = "";
	}
else $sujets_vu = "";

$i=0;
$goon=0;

while($goon<4 AND $i<$res)
	{
	$sql2 = 'SELECT type FROM wikast_forum_structure_tbl WHERE id="'.mysql_result($req,$i,categorie).'"' ;
	$req2 = mysql_query($sql2);
	$res2 = mysql_num_rows($req2);
	
	if($res2 != 0 AND (mysql_result($req2,0,type)==1 OR mysql_result($req2,0,type)==2 OR mysql_result($req2,0,type)==3 OR mysql_result($req2,0,type)==4))
		{
		print('<li'); if(preg_match('/\-'.mysql_result($req,$i,id).'\-/',$sujets_vu)) print(' style="background-color:#303030;"'); print('><a href="sujet.php?id='.mysql_result($req,$i,id).'&page=max">'.mysql_result($req,$i,nom).'</a></li>
		');
		$goon++;
		}
	
	$i++;
	}

mysql_close($db);

?>			
					</ul>
				</div>
