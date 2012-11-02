<?php 
session_start();

$db = mysql_connect('localhost', 'CENSURE', 'CENSURE') or die("Erreur de connexion au serveur des messages.");
mysql_select_db('CENSURE',$db) or die("Erreur de connexion a la base de donnees des messages.");

$liste = '';

$sql = 'SELECT DISTINCT titre FROM wikast_wiki_articles_tbl WHERE etat = 2 OR etat = 0 ORDER BY moment DESC' ;
$req = mysql_query($sql);
$res = mysql_num_rows($req);

$max = ($res<20)?$res:20;

for($i=0;$i<$max;$i++)
    {

    $sql2 = 'SELECT id,categorie FROM wikast_wiki_articles_tbl WHERE titre = "'.mysql_result($req,$i,titre).'"' ;
    $req2 = mysql_query($sql2);
    
    $sql3 = 'SELECT MAX(moment) FROM wikast_wiki_articles_tbl WHERE titre = "'.mysql_result($req,$i,titre).'"' ;
    $req3 = mysql_query($sql3);
    
    $date = 'Derni&egrave;re modif. le '.date('d/m/y',mysql_result($req3,0,"MAX(moment)")).' &agrave; '.date('H\hi',mysql_result($req3,0,"MAX(moment)"));
    
    $liste .= '<div class="sujet">
				<div class="titre">'.mysql_result($req,$i,titre).'<br /><span class="auteur">'.$date.'</span></div>
				<div class="auteurmodif" style="width:350px;">'.mysql_result($req2,0,categorie).'</div>
		    	<a href="wiki.php?id='.mysql_result($req2,0,id).'" class="bof"></a>
			</div>';
	
								
    }

mysql_close($db);

include('include/inc_head.php'); ?>

		<div id="page2">
			
			<?php include('include/inc_barre1.php'); ?>
		
			<a href="forum=accueil.php" id="lien-forum"></a>
			<a href="wiki=accueil.php" id="lien-wiki"></a>
			<?php if($_SESSION['id']!="") print('<a href="edc.php" id="lien-edc"></a>'); ?>
			
			<!-- -------------------------------------------------------------------------- MISE EN PAGE GENERALE -------------------------------------------------------------------------- -->
			<div id="forum">
				
				<!-- PARTIE DU HAUT : FORUM -->
				
				<?php include('include/inc_connexion.php');
				
				include('include/inc_forumrubriques.php');
				
				include('include/inc_forumderniers.php'); ?>
				
				<div id="forum-recherche">
					<!-- RECHERCHE DANS LE FORUM -->
					<form method="post" action="forum=recherche.php">
						Rechercher <input type="text" name="recherche" class="champ" /> <input type="submit" value="" class="valid" />
					</form>
				</div>
			</div>
			
			<div id="forum-entete">
				
				<?php include('include/inc_barreliens1.php'); ?>
				
				<div id="forum-info2">
					<p class="gauche"><br /><br /><?php print('<a href="wiki=accueil.php">Retour &agrave; l\'accueil</a><br />'); if(statut($_SESSION['statut'])>=2) { print('<a href="wiki=ecrire.php">Ecrire un article</a>'); } ?></p>
				</div>
			</div>
			
			<div id="mainpage-forum">
				<div id="haut">
					<p class="titre">Les 20 derniers articles</p>
				</div>
					
				<div id="contenu">
					<?php
					print('<div class="sujets">'.$liste.'</div>');
					?>
				</div>
			</div>
			
			<div id="wiki">
				<div id="menus">
				</div>
				<!-- PARTIE DU BAS : WIKI -->
				
				<?php include('include/inc_wikiderniers.php') ?>
				
				<?php include('include/inc_searcharticle.php'); ?>
				
				<div id="edc-random">
					<!-- AFFICHAGE D'UNE FICHE ALEATOIRE -->
					<?php include('include/inc_randomedc.php'); ?>
				</div>
				
				<div id="edc-monespace">
					<!-- ACCES A MON ESPACE PERSO -->
					<?php
					if(empty($_SESSION['id']))
						{
						print('<div id="lien-EDC2">
							<p>Connectez-vous pour acc&eacute;der &agrave; votre EDC</p>
						</div>');
						}
					else
						{
						print('<a href="edc.php" id="lien-EDC">
							<p>Acc&eacute;der &agrave; mon espace DC</p>
						</a>');
						}
					?>
				</div>
				
				<?php include('include/inc_searchedc.php'); ?>
			</div>	
		</div>
	
	</body>
	
</html>
