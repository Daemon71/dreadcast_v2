<?php 
session_start();

if(empty($_SESSION['id'])) $_SESSION['statut'] = "visiteur";
if($_SESSION['statut']=="Compte VIP" || $_SESSION['statut']=="Gold" || $_SESSION['statut']=="Platinium" || $_SESSION['statut']=="Modérateur" || $_SESSION['statut']=="Administrateur")
	{
	$db = mysql_connect('localhost', 'CENSURE', 'CENSURE') or die("Erreur de connexion au serveur des messages.");
	mysql_select_db('CENSURE',$db) or die("Erreur de connexion a la base de donnees des messages.");
	
	$sql ='SELECT id FROM wikast_sondages_tbl WHERE auteur="'.$_SESSION['pseudo'].'" AND moment > '.(time()-(15*24*3600)).'';
	$req = mysql_query($sql);
	$res = mysql_num_rows($req);
	
	if($res != 0) $sondage="non";
	else $sondage="ok";
	
	mysql_close($db);
	}

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
					<p class="gauche"><br /><br /><?php if((statut($_SESSION['statut'])>=2 && $sondage=="ok") || statut($_SESSION['statut'])>=6) { print('<a href="sondages=nouveau.php">Cr&eacute;er un<br />nouveau sondage</a>'); } ?></p>
					<p class="droite"><br /><br /><br /><?php if($_GET['section']!="") { print('<a href="sondages=accueil.php">Retour</a>'); } ?></p>
				</div>
			</div>
			
			<div id="mainpage-forum">
				<div id="haut">
					<p class="titre">Sondages<?php if($_GET['section']=="officiel") print(' officiels'); elseif($_GET['section']=="joueurs") print(' propos&eacute;s par les joueurs'); ?></p>
				</div>
					
				<div id="contenu">
					<?php
					
					$db = mysql_connect('localhost', 'CENSURE', 'CENSURE') or die("Erreur de connexion au serveur des messages.");
					mysql_select_db('CENSURE',$db) or die("Erreur de connexion a la base de donnees des messages.");
					
					if($_GET['section']=="" OR $_GET['section']=="officiel")
						{
						
						if($_GET['section']=="") print('<a href="sondages=accueil.php?section=officiel" class="ss-forum">
						<span class="style2"> + Afficher tous les sondages de cette section</span> <span class="style1">Sondages officiels</span>
					</a>');
					
					print('<div class="sujets">');
						
						if($_GET['section']=="officiel") $limite = "";
						else $limite = " LIMIT 5";
						
						$sql ='SELECT id,titre,moment,electeurs FROM wikast_sondages_tbl WHERE auteur="Administrateur" ORDER BY moment DESC'.$limite;
						$req = mysql_query($sql);
						$res = mysql_num_rows($req);
						
						for($i=0;$i<$res;$i++)
							{
							$id = mysql_result($req,$i,id);
							$nomsujet = mysql_result($req,$i,titre);
							$moment = mysql_result($req,$i,moment);
							$datefin = $moment+(15*24*3600);
							$electeurs = mysql_result($req,$i,electeurs);
						
							$etat = ((time()-(15*24*3600))<$moment)?"En cours":"Termin&eacute;";
							$avote = (preg_match('#\-'.$_SESSION['pseudo'].'\-#',$electeurs))?"oui":"non";
					
							print('
							<div class="sujet">
								');
								if($avote=="non") print('<div class="pasvu"></div>');
								elseif($avote=="oui") print('<div class="dejavu"></div>');
								print('
								<a href="sondages.php?id='.$id.'" class="bof"></a>
								<a style="display:block;color:#999;" href="sondages.php?id='.$id.'" class="titre">'.$nomsujet.'<br /><span class="auteur">Sondage officiel</span></a>
								<div class="datemodif" style="width:180px;"><span class="style1">Prend fin le</span> '.date('d/m/y',$datefin).' <span class="style1">&agrave;</span> '.date('H:i',$datefin).'</div>
								<div class="auteurmodif">'.$etat.'</div>
							</div>');
							}
					
						if($_GET['section']=="") print('<a href="sondages=accueil.php?section=officiel" title="Voir les sondages officiels" class="sujet">Voir tous les sondages officiels</a>');
					
					print('</div>');
						}
						
					if($_GET['section']=="" OR $_GET['section']=="joueurs")
						{
						if($_GET['section']=="") print('<a href="sondages=accueil.php?section=joueurs" class="ss-forum">
						<span class="style2"> + Afficher tous les sondages de cette section</span> <span class="style1">Sondages joueurs</span>
					</a>');
					
					print('<div class="sujets">');
					
						if($_GET['section']=="joueurs") $limite = "";
						else $limite = " LIMIT 5";
					
						$sql ='SELECT id,titre,auteur,moment,electeurs FROM wikast_sondages_tbl WHERE auteur!="Administrateur" ORDER BY moment DESC'.$limite;
						$req = mysql_query($sql);
						$res = mysql_num_rows($req);
					
						for($i=0;$i<$res;$i++)
							{
							$id = mysql_result($req,$i,id);
							$auteur = mysql_result($req,$i,auteur);
							$nomsujet = mysql_result($req,$i,titre);
							$moment = mysql_result($req,$i,moment);
							$datefin = $moment+(15*24*3600);
							$electeurs = mysql_result($req,$i,electeurs);
						
							$etat = ((time()-(15*24*3600))<$moment)?"En cours":"Termin&eacute;";
							$avote = (preg_match('#\-'.$_SESSION['pseudo'].'\-#',$electeurs))?"oui":"non";

							print('
							<div class="sujet">
								');
								if($avote=="non") print('<div class="pasvu"></div>');
								elseif($avote=="oui") print('<div class="dejavu"></div>');
								print('
								<a href="sondages.php?id='.$id.'" class="bof"></a>
								<a href="sondages.php?id='.$id.'" style="display:block;color:#999;" class="titre">'.$nomsujet.'<br /><span class="auteur">Par '.$auteur.'</span></a>
								<div class="datemodif" style="width:180px;"><span class="style1">Prend fin le</span> '.date('d/m/y',$datefin).' <span class="style1">&agrave;</span> '.date('H:i',$datefin).'</div>
								<div class="auteurmodif">'.$etat.'</div>
							</div>');
							
							}
						
						if($_GET['section']=="") print('<a href="sondages=accueil.php?section=joueurs" title="Voir les sondages joueurs" class="sujet">Voir tous les sondages joueurs</a>');
						
						print('</div>');
					}
					
					mysql_close($db);
					
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
