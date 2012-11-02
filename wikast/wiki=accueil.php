<?php 
session_start();

$db = mysql_connect('localhost', 'CENSURE', 'CENSURE') or die("Erreur de connexion au serveur des messages.");
mysql_select_db('CENSURE',$db) or die("Erreur de connexion a la base de donnees des messages.");

if(empty($_SESSION['id'])) $_SESSION['statut'] = "visiteur";
else
	{
	
	$sql = 'SELECT id FROM wikast_joueurs_tbl WHERE pseudo="'.$_SESSION['pseudo'].'"' ;
	$req = mysql_query($sql);
	$res = mysql_num_rows($req);
	
	if($res == 0)
		{
		$sql = 'INSERT INTO wikast_joueurs_tbl(id,pseudo,infoperso,sujets_vu,commentaire) VALUES("","'.$_SESSION['pseudo'].'","-","-","")' ;
		mysql_query($sql);
		}
	}
	
$section = $_GET['section'];

if($section == 1)
	{
	$sql = 'SELECT id,titre FROM wikast_wiki_articles_tbl WHERE categorie="Histoire de la ville" AND etat = 2 ORDER BY titre' ;
	$req = mysql_query($sql);
	$res = mysql_num_rows($req);
	
	$liste = "";
	
	for($i=0;$i<$res;$i++)
		{
		$liste .= '<a href="wiki.php?id='.mysql_result($req,$i,id).'" style="position:relative;left:20px;">'.mysql_result($req,$i,titre).'</a><br />';
		}
		
	$section2 = "Histoire de la ville";
	}
elseif($section == 2)
	{
	$sql = 'SELECT id,titre FROM wikast_wiki_articles_tbl WHERE categorie="Les commerces" AND etat = 2 ORDER BY titre' ;
	$req = mysql_query($sql);
	$res = mysql_num_rows($req);
	
	$liste = "";
	
	for($i=0;$i<$res;$i++)
		{
		$liste .= '<a href="wiki.php?id='.mysql_result($req,$i,id).'" style="position:relative;left:20px;">'.mysql_result($req,$i,titre).'</a><br />';
		}
		
	$section2 = "Les commerces";
	}
elseif($section == 3)
	{
	$sql = 'SELECT id,titre FROM wikast_wiki_articles_tbl WHERE categorie="Politique de Dreadcast" AND etat = 2 ORDER BY titre' ;
	$req = mysql_query($sql);
	$res = mysql_num_rows($req);
	
	$liste = "";
	
	for($i=0;$i<$res;$i++)
		{
		$liste .= '<a href="wiki.php?id='.mysql_result($req,$i,id).'" style="position:relative;left:20px;">'.mysql_result($req,$i,titre).'</a><br />';
		}
	$section2 = "Politique de Dreadcast";
	}
elseif($section == 4)
	{
	$sql = 'SELECT id,titre FROM wikast_wiki_articles_tbl WHERE categorie="Trucs & astuces" AND etat = 2 ORDER BY titre' ;
	$req = mysql_query($sql);
	$res = mysql_num_rows($req);
	
	$liste = "";
	
	for($i=0;$i<$res;$i++)
		{
		$liste .= '<a href="wiki.php?id='.mysql_result($req,$i,id).'" style="position:relative;left:20px;">'.mysql_result($req,$i,titre).'</a><br />';
		}
	$section2 = "Trucs & astuces";
	}
elseif($section == 5)
	{
	$sql = 'SELECT id,titre FROM wikast_wiki_articles_tbl WHERE categorie="Informations utiles" AND etat = 2 ORDER BY titre' ;
	$req = mysql_query($sql);
	$res = mysql_num_rows($req);
	
	$liste = "";
	
	for($i=0;$i<$res;$i++)
		{
		$liste .= '<a href="wiki.php?id='.mysql_result($req,$i,id).'" style="position:relative;left:20px;">'.mysql_result($req,$i,titre).'</a><br />';
		}
	$section2 = "Informations utiles";
	}
elseif($section == 6)
	{
	$sql = 'SELECT id,titre FROM wikast_wiki_articles_tbl WHERE categorie="Personnages" AND etat = 2 ORDER BY titre' ;
	$req = mysql_query($sql);
	$res = mysql_num_rows($req);
	
	$liste = "";
	
	for($i=0;$i<$res;$i++)
		{
		$liste .= '<a href="wiki.php?id='.mysql_result($req,$i,id).'" style="position:relative;left:20px;">'.mysql_result($req,$i,titre).'</a><br />';
		}
	$section2 = "Personnages";
	}
elseif($section == 7)
	{
	$sql = 'SELECT id,titre FROM wikast_wiki_articles_tbl WHERE categorie="Autour de Dreadcast" AND etat = 2 ORDER BY titre' ;
	$req = mysql_query($sql);
	$res = mysql_num_rows($req);
	
	$liste = "";
	
	for($i=0;$i<$res;$i++)
		{
		$liste .= '<a href="wiki.php?id='.mysql_result($req,$i,id).'" style="position:relative;left:20px;">'.mysql_result($req,$i,titre).'</a><br />';
		}
	$section2 = "Autour de Dreadcast";
	}


if($liste == "") $liste = "Il n'y a aucun article dans cette section pour l'instant.";

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
					<p class="gauche"><br /><br /><?php if($_GET['section']=="") print('<br />'); else print('<a href="wiki=accueil.php">Retour &agrave; l\'accueil</a><br />'); if(statut($_SESSION['statut'])>=2) { print('<a href="wiki=ecrire.php">Ecrire un article</a>'); } ?></p>
				</div>
			</div>
			
			<div id="mainpage-forum">
				<div id="haut">
					<p class="titre">Wiki</p>
				</div>
					
				<div id="contenu">
					<?php
					
					if($section!="")
						{
						print('<div class="infos-article">
						<p class="situation">Cat&eacute;gorie '.$section2.'</p>
					</div>
					<br /><h4>Articles de cette section</h4>
					<div class="wiki_p">'.$liste.'</div>');
						}
					else
						{
						print('
						<h3 style="text-align:center;">Bienvenue sur le Wiki de Dreadcast</h3>
						
						<div class="wiki_p">Vous trouverez ici tout ce que vous cherchez sur l\'univers de Dreadcast, gr&acirc;ce &agrave; la participation de ses citoyens.<br />Vous pouvez chercher un article soit pas cat&eacute;gorie, soit en utilisant la recherche sur <a href="wiki=recherche.php">cette page</a>.</div>
						
						<h4><a href="wiki=accueil.php?section=1">Histoire de la ville</a></h4>
						
						<div class="wiki_p">R&eacute;cits racontant son Histoire avec un grand H</div>
						
						<h4><a href="wiki=accueil.php?section=2">Les commerces</a></h4>
						
						<div class="wiki_p">Explication du fonctionnement des diff&eacute;rents commerces que vous pouvez trouver</div>
						
						<h4><a href="wiki=accueil.php?section=3">Politique de Dreadcast</a></h4>
						
						<div class="wiki_p">Situation politique de la ville</div>
						
						<h4><a href="wiki=accueil.php?section=6">Personnages</a></h4>
						
						<div class="wiki_p">Historique des figures embl&eacute;matiques de la cit&eacute;</div>
						
						<h4><a href="wiki=accueil.php?section=7">Autour de Dreadcast</a></h4>
						
						<div class="wiki_p">Tout ce qui a trait à la cité Impériale et son univers</div>
						
						<h4><a href="wiki=accueil.php?section=4">Trucs & astuces</a></h4>
						
						<div class="wiki_p">Techniques et tours qui facilitent le vie</div>
						
						<h4><a href="wiki=accueil.php?section=5">Informations utiles</a></h4>
						
						<div class="wiki_p">Ce qu\'il faut savoir lorsqu\'on joue &agrave; Dreadcast</div>
						');
						}
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
