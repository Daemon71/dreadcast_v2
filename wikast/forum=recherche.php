<?php 
session_start();

if($_POST['recherche']=="")
	$texte = "Veuillez sp&eacute;cifier une cible &agrave; votre recherche.";
elseif(strlen($_POST['recherche'])<3)
	$texte = "Veuillez sp&eacute;cifier un mot d'au moins 3 lettres.";
else
	{
	
	$rechercheencours = stripslashes($_POST['recherche']);
	
	$res=0;
	
	$db = mysql_connect('localhost', 'CENSURE', 'CENSURE') or die("Erreur de connexion au serveur des messages.");
	mysql_select_db('CENSURE',$db) or die("Erreur de connexion a la base de donnees des messages.");
	
	if($_SESSION['id']!="")
		{
		$sql = 'SELECT cercle FROM cercles_tbl WHERE pseudo="'.$_SESSION['pseudo'].'"';
		$req = mysql_query($sql);
		$res = mysql_num_rows($req);
		
		if($res!=0)
			{
			$sql = 'SELECT id FROM wikast_forum_structure_tbl WHERE type=(SELECT id FROM wikast_forum_structure_tbl WHERE nom="Cercle '.mysql_result($req,0,cercle).'")';
			$req = mysql_query($sql);
			$res = mysql_num_rows($req);
			}
		}
	
	$categories = 'categorie IN ("5","44","45","46","47","50","51","52","53","54","55","56","57","58","59","60"';
	for($i=0;$i<$res;$i++) $categories .= ',"'.mysql_result($req,$i,id).'"';
	$categories .= ')';
	
	$sql = 'SELECT * FROM wikast_forum_sujets_tbl WHERE nom LIKE "%'.htmlentities($rechercheencours).'%" AND '.$categories.' OR auteur LIKE "%'.$rechercheencours.'%" AND '.$categories.' ORDER BY date DESC' ;
	$req = mysql_query($sql);
	$res = mysql_num_rows($req);
	
	if($res == 0) $texte = "Il n'y a pas de r&eacute;ponse pour la recherche \"<span class=\"couleur2\">".$rechercheencours."</span>\".";
	else 
		{
		$texte = "Il y a <span class=\"couleur1\">".$res."</span> r&eacute;ponse"; if($res!=1) $texte .= "s"; $texte .= " pour la recherche \"<span class=\"couleur2\">".$rechercheencours."</span>\".";
		
		$texte .= '<div class="sujets">';
					
		for($i=0 ; $i < $res ; $i++)
			{
			
			$idsujet = mysql_result($req,$i,"id");
			$categorie = mysql_result($req,$i,"categorie");
			$nomsujet = mysql_result($req,$i,"nom");
			$auteur = mysql_result($req,$i,"auteur");
			$datemodif = date('d/m/y',mysql_result($req,$i,"date"));
							
			$sqltmp = 'SELECT auteur FROM wikast_forum_posts_tbl WHERE sujet="'.$idsujet.'" AND date = (SELECT MAX(date) FROM wikast_forum_posts_tbl WHERE sujet="'.$idsujet.'")' ;
			$reqtmp = mysql_query($sqltmp);
			$restmp = mysql_num_rows($reqtmp);
			
			$sqlhop = 'SELECT nom FROM wikast_forum_structure_tbl WHERE id="'.$categorie.'"' ;
			$reqhop = mysql_query($sqlhop);
			$reshop = mysql_num_rows($reqhop);
			if($reshop != 0) $forum = mysql_result($reqhop,0,nom);
							
			if($_SESSION['id']!="")					// VERIFICATION DES ARTICLES DEJA VUS
				{
				$sqltmp2 = 'SELECT sujets_vu FROM wikast_joueurs_tbl WHERE pseudo="'.$_SESSION['pseudo'].'"' ;
				$reqtmp2 = mysql_query($sqltmp2);
								
				$vu = (ereg('-'.$idsujet.'-',mysql_result($reqtmp2,0,sujets_vu)))?"oui":"non";
				}
			else $vu = "oui";
							
			$auteurmodif = ($restmp>0)? mysql_result($reqtmp,0,"auteur"):mysql_result($req,$i,"auteur");
							
			$texte .= '<div class="sujet">
				';
				if($vu=="non") $texte .= '<div class="pasvu"></div>';
				elseif($vu=="oui") $texte .= '<div class="dejavu"></div>';
				elseif($vu=="verrou") $texte .= '<div class="cadenas"></div>';
				$texte .= '
				<a href="sujet.php?id='.$idsujet.'&page=max" class="bof"></a>
				<div style="display:block;color:#999;" href="sujet.php?id='.$idsujet.'&page=max" class="titre">'.$nomsujet.'<br /><span class="auteur">Par '.$auteur.' - '.$forum.'</span></div>
				<div class="datemodif"><span class="style1">Modifi&eacute; le</span> '.$datemodif.'</div>
				<div class="auteurmodif"><span class="style1">Par</span> '.$auteurmodif.'</div>
			</div>';
			}
					
		$texte .= '</div>';
					
		}
	
	mysql_close($db);
	}

include('include/inc_head.php'); ?>

		<div id="page">
			
			<?php include('include/inc_barre1.php'); ?>
			
			<a href="forum=accueil.php" id="lien-forum"></a>
			
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
				</div>
			</div>
			
			<div id="mainpage-forum">
				<div id="haut">
					<a href="forum=accueil.php" title="Retour" id="btn_retour"></a>
					<p>Recherche</p>
				</div>
				
				<div id="contenu">
					<p class="simpletexte">
					<?php
					
					print($texte);
					
					?>
					</p>
					<br /><br /><br /><br /><br />
					<a href="forum=accueil.php" title="Retour" class="retour">Retour</a>
				</div>
				
				<div id="bas">
				</div>
			</div>			
		</div>
	
	</body>
	
</html>
