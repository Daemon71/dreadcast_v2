<?php 
session_start();

if (false) {
	header('Status: 301 Moved Permanently');
	header('Location: http://www.dreadcast.net/Forum');
	exit;
}

if(preg_match('#Cercle#',$_GET['partie']) && empty($_SESSION['id']))
	{
	print('<meta http-equiv="refresh" content="0 ; url=forum=accueil.php"> ');
	exit();
	}
elseif(preg_match('#Cercle#',$_GET['partie']) && $_SESSION['statut']!="Administrateur")
	{
	$nomcercle = explode(' ', $_GET['partie'], 2);
	
	$db = mysql_connect('localhost', 'CENSURE', 'CENSURE') or die("Erreur de connexion au serveur des messages.");
	mysql_select_db('CENSURE',$db) or die("Erreur de connexion a la base de donnees des messages.");
	
	$sql = 'SELECT id FROM cercles_tbl WHERE pseudo= "'.$_SESSION['pseudo'].'" AND cercle="'.$nomcercle[1].'"' ;
	$req = mysql_query($sql);
	$res = mysql_num_rows($req);
	
	mysql_close($db);
	
	if($res==0)
		{
		print('<meta http-equiv="refresh" content="0 ; url=forum=accueil.php"> ');
		exit();
		}
	}
	
if(!empty($_GET['sous-partie']))
	{
	$db = mysql_connect('localhost', 'CENSURE', 'CENSURE') or die("Erreur de connexion au serveur des messages.");
	mysql_select_db('CENSURE',$db) or die("Erreur de connexion a la base de donnees des messages.");
	
	$sql = 'SELECT nom FROM wikast_forum_structure_tbl WHERE id=( SELECT type FROM wikast_forum_structure_tbl WHERE id="'.$_GET['sous-partie'].'")' ;
	$req = mysql_query($sql);
	$res = mysql_num_rows($req);
	
	mysql_close($db);
	
	if($res==0 OR mysql_result($req,0,nom) != $_GET['partie'])
		{
		print('<meta http-equiv="refresh" content="0 ; url=forum=accueil.php"> ');
		exit();
		}
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
			
			<?php
			
			$db = mysql_connect('localhost', 'CENSURE', 'CENSURE') or die("Erreur de connexion au serveur des messages.");
			mysql_select_db('CENSURE',$db) or die("Erreur de connexion a la base de donnees des messages.");
			
			if(empty($_GET['sous-partie']))														// Si forum séléctionné
				{
				
				$sql = 'SELECT * FROM wikast_forum_structure_tbl WHERE nom= "'.$_GET['partie'].'"' ;
				$req = mysql_query($sql);
				$res = mysql_num_rows($req);
				
				$idforum = mysql_result($req,0,"id");
				$modoGlob = mysql_result($req,0,"admin");
				
				if($res != 0)
					{
					$sql = 'SELECT * FROM wikast_forum_structure_tbl WHERE type= "'.mysql_result($req,0,id).'"' ;
					$req = mysql_query($sql);
					$res = mysql_num_rows($req);
					}
				
				print('<div id="forum-entete">');
				
				include('include/inc_barreliens1.php');

				print('
					
					<div id="forum-info2">
						<p class="gauche">');
						if($_SESSION['id']!="") print('<a href="../ingame/engine=contacter.php?cible='.$modoGlob.'&forum='.$idforum.'" onclick="window.open(this.href); return false;">Contacter<br />un mod&eacute;rateur</a>');
						print('</p>
						
						<p class="droite">');
							if($_SESSION['pseudo']==$modoGlob OR $_SESSION['statut']=="Administrateur")
								print('<a href="forum=moderation.php?forum='.$idforum.'" title="Modifier la structure du forum">Gestion du forum</a>');
						print('</p>
					</div>
				</div>
				
				<div id="mainpage-forum">
					<div id="haut">
						<a href="forum=accueil.php" title="Retour" id="btn_retour"></a>
						<p>Forum '.$_GET['partie'].'</p>
					</div>
					
					<div id="contenu">');
						
					for($i=0;$i<$res;$i++)
						{
						
						$ssforum = mysql_result($req,$i,nom);
						$ssforumid = mysql_result($req,$i,id);
						print('<a href="forum.php?partie='.$_GET['partie'].'&sous-partie='.$ssforumid.'" title="Accéder au sous-forum '.$ssforum.'" class="ss-forum">
							<span class="style2"> + Afficher tous les sujets du sous-forum</span> <span class="style1">'.$ssforum.'</span>
						</a>
						
						<div class="sujets">');
						
						$sql2 = 'SELECT * FROM wikast_forum_sujets_tbl WHERE categorie= "'.$ssforumid.'" ORDER BY datemodif DESC' ;
						$req2 = mysql_query($sql2);
						$res2 = mysql_num_rows($req2);
						
						if($res2<5) $max = $res2;
						else $max = 5;
						
							for($j=0 ; $j<$max ; $j++)
							{
							
							$idsujet = mysql_result($req2,$j,"id");
							$nomsujet = mysql_result($req2,$j,"nom");
							$auteur = mysql_result($req2,$j,"auteur");
							$datemodif = date('d/m/y',mysql_result($req2,$j,"datemodif"));
							
							$sqltmp = 'SELECT auteur FROM wikast_forum_posts_tbl WHERE sujet="'.$idsujet.'" AND date = (SELECT MAX(date) FROM wikast_forum_posts_tbl WHERE sujet="'.$idsujet.'")' ;
							$reqtmp = mysql_query($sqltmp);
							$restmp = mysql_num_rows($reqtmp);
							
							if(preg_match("#\[Verrouill&eacute;\]#",$nomsujet))
								{
								$vu = "verrou";
								}
							else
								{
								if($_SESSION['id']!="")					// VERIFICATION DES ARTICLES DEJA VUS
									{
									$sqltmp2 = 'SELECT sujets_vu FROM wikast_joueurs_tbl WHERE pseudo="'.$_SESSION['pseudo'].'"' ;
									$reqtmp2 = mysql_query($sqltmp2);
									
									$vu = (preg_match('#\-'.$idsujet.'\-#',mysql_result($reqtmp2,0,sujets_vu)))?"oui":"non";
									}
								else $vu = "oui";
								}
							
							$auteurmodif = ($restmp>0)? mysql_result($reqtmp,0,"auteur"):$auteurmodif = mysql_result($req2,$j,"auteur");
							
							print('
							<div class="sujet">
								');
								if($vu=="non") print('<div class="pasvu"></div>');
								elseif($vu=="oui") print('<div class="dejavu"></div>');
								elseif($vu=="verrou") print('<div class="cadenas"></div>');
								print('
								<a href="sujet.php?id='.$idsujet.'&page=max" class="bof"></a>
								<a style="display:block;color:#999;" href="sujet.php?id='.$idsujet.'&page=max" class="titre">'.$nomsujet.'<br /><span class="auteur">Par '.$auteur.'</span></a>
								<div class="datemodif"><span class="style1">Modifi&eacute; le</span> '.$datemodif.'</div>
								<div class="auteurmodif"><span class="style1">Par</span> '.$auteurmodif.'</div>
							</div>');
							}
							print('<a href="forum.php?partie='.$_GET['partie'].'&sous-partie='.$ssforumid.'" title="Accéder au sous-forum '.$ssforum.'" class="sujet">Voir tous les sujets</a>
						</div>');
						}
						
						print('<a href="forum=accueil.php" title="Retour" class="retour" style="height:21px !important;height:33px;">Retour</a>
						
					</div>
					
					<div id="bas">
					</div>
				</div>
				');
				}
			else																					// Si sous-forum séléctionné également
				{
				
				$sql = 'SELECT nom,admin FROM wikast_forum_structure_tbl WHERE id= "'.$_GET['sous-partie'].'"' ;
				$req = mysql_query($sql);
				
				$ssforumid = $_GET['sous-partie'];
				$ssforum = mysql_result($req,0,"nom");
				$modoPart = mysql_result($req,0,"admin");
				
				print('<div id="forum-entete">');
				
				include('include/inc_barreliens1.php');
				
				print('
					
					<div id="forum-info2">
						<p class="gauche">');
						if($_SESSION['id']!="") print('<a href="../ingame/engine=contacter.php?cible='.$modoPart.'&forum='.$ssforumid.'" onclick="window.open(this.href); return false;">Contacter<br />un mod&eacute;rateur</a>');
						print('</p>

						<p class="droite">');
							if($_SESSION['statut']!="visiteur" AND (htmlentities($ssforum) != "Annonces officielles" OR (htmlentities($ssforum) == "Annonces officielles" AND $_SESSION['statut']=="Administrateur")))
								print('<a href="sujet=nouveau.php?ssfid='.$ssforumid.'" title="Nouveau sujet">Ajouter un<br />nouveau sujet</a>');
						print('</p>
					</div>
				</div>
				
				<div id="mainpage-forum">
					<div id="haut">
						<a href="forum.php?partie='.$_GET['partie'].'" title="Retour" id="btn_retour"></a>
						<p><a href="forum.php?partie='.$_GET['partie'].'" title="Vers le forum '.$_GET['partie'].'">Forum '.$_GET['partie'].'</a> > '.$ssforum.'</p>
					</div>
					
					<div id="contenu">
						
						<div class="sujets">');
						
						$sql2 = 'SELECT * FROM wikast_forum_sujets_tbl WHERE categorie= "'.$ssforumid.'" ORDER BY datemodif DESC' ;
						$req2 = mysql_query($sql2);
						$res2 = mysql_num_rows($req2);
						
							for($j=0 ; $j<$res2 ; $j++)
							{
							
							$idsujet = mysql_result($req2,$j,"id");
							$nomsujet = mysql_result($req2,$j,"nom");
							$auteur = mysql_result($req2,$j,"auteur");
							$datemodif = date('d/m/y',mysql_result($req2,$j,"datemodif"));
							
							$sqltmp = 'SELECT auteur FROM wikast_forum_posts_tbl WHERE sujet="'.$idsujet.'" AND date = (SELECT MAX(date) FROM wikast_forum_posts_tbl WHERE sujet="'.$idsujet.'")' ;
							$reqtmp = mysql_query($sqltmp);
							$restmp = mysql_num_rows($reqtmp);
							
							if(preg_match("#\[Verrouill&eacute;\]#",$nomsujet))
								{
								$vu = "verrou";
								}
							else
								{
								if($_SESSION['id']!="")					// VERIFICATION DES ARTICLES DEJA VUS
									{
									$sqltmp2 = 'SELECT sujets_vu FROM wikast_joueurs_tbl WHERE pseudo="'.$_SESSION['pseudo'].'"' ;
									$reqtmp2 = mysql_query($sqltmp2);
									
									$vu = (preg_match('#\-'.$idsujet.'\-#',mysql_result($reqtmp2,0,sujets_vu)))?"oui":"non";
									}
								else $vu = "oui";
								}
							
							$auteurmodif = ($restmp>0)? mysql_result($reqtmp,0,"auteur"):mysql_result($req2,$j,"auteur");
							
							print('
							<div class="sujet">
								');
								if($vu=="non") print('<div class="pasvu"></div>');
								elseif($vu=="oui") print('<div class="dejavu"></div>');
								elseif($vu=="verrou") print('<div class="cadenas"></div>');
								print('
								<a href="sujet.php?id='.$idsujet.'&page=max" class="bof"></a>
								<a style="display:block;color:#999;" href="sujet.php?id='.$idsujet.'&page=max" class="titre">'.$nomsujet.'<br /><span class="auteur">Par '.$auteur.'</span></a>
								<div class="datemodif"><span class="style1">Modifi&eacute; le</span> '.$datemodif.'</div>
								<div class="auteurmodif"><span class="style1">Par</span> '.$auteurmodif.'</div>
							</div>');
							}
							print('</div>
						
						<a href="forum.php?partie='.$_GET['partie'].'" title="Retour" class="retour">Retour</a>
						
					</div>
					
					<div id="bas">');
						if($_SESSION['statut']!="visiteur" AND (htmlentities($ssforum) != "Annonces officielles" OR (htmlentities($ssforum) == "Annonces officielles" AND $_SESSION['statut']=="Administrateur")))
						print('
						<div class="nouveausujet2"><a href="sujet=nouveau.php?ssfid='.$ssforumid.'" title="Nouveau sujet">Nouveau sujet</a></div>');
					print('</div>
				</div>
				');
				}
				
			mysql_close($db);
			?>
			
		</div>
	
	</body>
	
</html>
