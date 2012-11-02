<?php 
session_start();

include('include/inc_head.php');

if(empty($_SESSION['id'])) $_SESSION['statut'] = "visiteur";
else
	{
	$db = mysql_connect('localhost', 'CENSURE', 'CENSURE') or die("Erreur de connexion au serveur des messages.");
	mysql_select_db('CENSURE',$db) or die("Erreur de connexion a la base de donnees des messages.");
	
	$sql = 'SELECT id FROM wikast_joueurs_tbl WHERE pseudo="'.$_SESSION['pseudo'].'"' ;
	$req = mysql_query($sql);
	$res = mysql_num_rows($req);
	
	if($res == 0)
		{
		$sql = 'INSERT INTO wikast_joueurs_tbl(id,pseudo,infoperso,sujets_vu,commentaire) VALUES("","'.$_SESSION['pseudo'].'","-","-","")' ;
		mysql_query($sql);
		}
	
	if(statut($_SESSION['statut'])<2)
		{
		print('<meta http-equiv="refresh" content="0 ; url=wiki=accueil.php">');
		exit();
		}

	mysql_close($db);
	
	}

if($_POST['titre']=="" OR $_POST['categorie']=="" OR $_POST['introduction']=="")
	{
	print('<meta http-equiv="refresh" content="0 ; url=wiki=accueil.php">');
	exit();
	}

$db = mysql_connect('localhost', 'CENSURE', 'CENSURE') or die("Erreur de connexion au serveur des messages.");
mysql_select_db('CENSURE',$db) or die("Erreur de connexion a la base de donnees des messages.");
	
$titre = stripslashes(htmlentities($_POST['titre'],ENT_QUOTES));
$createur = $_SESSION['pseudo'];
$categorie = $_POST['categorie'];
$contenu = stripslashes(htmlentities($_POST['introduction'],ENT_QUOTES));
$contenu = str_replace("\n", "<br />",$contenu);
$article = stripslashes(htmlentities($_POST['article'],ENT_QUOTES));

$tab = explode("[paragraphe]",$article);
$temp = $tab[0];
			
for($i=1;$i<count($tab);$i++)
	{
	$tab2 = explode("[/paragraphe]",$tab[$i]);
	$retour = str_replace("\n","<br />",$tab2[0]);
	$retour .= "[/paragraphe]".$tab2[1];
	$temp .= "[paragraphe]".$retour;
	}
				
$article = $temp;

$tab = explode("[quote]",$article);
$temp = $tab[0];
			
for($i=1;$i<count($tab);$i++)
	{
	$tab2 = explode("[/quote]",$tab[$i]);
	$retour = str_replace("\n","<br />",$tab2[0]);
	$retour .= "[/quote]".$tab2[1];
	$temp .= "[quote]".$retour;
	}
				
$article = $temp;
			
if($_POST['article']!="")
	{
	$contenu .= "[LIMITE-INTRODUCTION]".$article;
	}
	
$contenu = stripslashes(stripslashes(stripslashes(str_replace("e]\n\n[","e]\n[",$contenu))));

$etat = ($_SESSION['statut']=="Administrateur")?2:1;
$commentaire = stripslashes(htmlentities($_POST['commentaire']));
$mots = stripslashes(htmlentities($_POST['mots']));

if($etat==2)
	{
	$sql = 'UPDATE wikast_wiki_articles_tbl SET etat = 0 WHERE titre = "'.$titre.'" AND etat = 2' ;
	mysql_query($sql);
	}

$sql = 'INSERT INTO wikast_wiki_articles_tbl(id,categorie,createur,moment,titre,contenu,etat,commentaire,mots) VALUES("","'.$categorie.'","'.$createur.'","'.time().'","'.$titre.'","'.$contenu.'","'.$etat.'","'.$commentaire.'","'.$mots.'")' ;
$req = mysql_query($sql);

mysql_close($db);

 ?>

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
					<p class="gauche"><br /><br /><br /><?php if(statut($_SESSION['statut'])>=2) { print('<a href="wiki=accueil.php">Retour &agrave; l\'accueil</a>'); } ?></p>
				</div>
			</div>
			
			<div id="mainpage-forum">
				<div id="haut">
					<p class="titre">Article envoy&eacute;</p>
				</div>
					
				<div id="contenu">
					<div class="wiki_p"><?php if($_SESSION['statut']=="Administrateur") print('Admin : sujet mis en ligne.'); else print('Votre article vient d\'&ecirc;tre envoy&eacute; aux administrateurs. Vous serez pr&eacute;venu par MP de son statut.<br /><br /><a href="wiki=accueil.php">Retour</a>'); ?></div>
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
