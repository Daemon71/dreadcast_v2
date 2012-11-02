<?php 
session_start();

if($_SESSION['id'] == "")
	{
	print('<meta http-equiv="refresh" content="0 ; url=index.php"> ');
	exit();
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
			
			if($_SESSION['statut']=="Administrateur" && $_GET['perso']!="") $bonhomme = $_GET['perso'];
			else $bonhomme = $_SESSION['pseudo'];
			
			$sql = 'SELECT S.id, S.nom, S.auteur, S.datemodif, P.statut FROM wikast_forum_permissions_tbl P, wikast_forum_sujets_tbl S WHERE P.pseudo="'.$bonhomme.'" AND S.id = P.sujet ORDER BY S.datemodif DESC' ;
			$req = mysql_query($sql);
			$res = mysql_num_rows($req);
			
			if($res == 0)
				{
				print('<div id="forum-entete">');
				
				include('include/inc_barreliens1.php');

				print('
					<div id="forum-info2">
						<p class="gauche">
							<br /><br /><br /><a href="sujet=nouveau.php?prive=ok">Nouveau sujet priv&eacute;</a>
						</p>
						
						<p class="droite">
						</p>
					</div>
				</div>
				
				<div id="mainpage-forum">
					<div id="haut">
						<a href="forum=accueil.php" title="Retour" id="btn_retour"></a>
						<p>Forum personnel</p>
					</div>
					
					<div id="contenu">
						<div class="paragraphes">
							<p>Vous n\'avez pas de sujet priv&eacute;.</p>
						</div>
					</div>
					');
				}
			else
				{
				print('<div id="forum-entete">');
				
				include('include/inc_barreliens1.php');

				print('
					<div id="forum-info2">
						<p class="gauche">
							<br /><br /><br /><a href="sujet=nouveau.php?prive=ok">Nouveau sujet priv&eacute;</a>
						</p>
						
						<p class="droite">
						</p>
					</div>
				</div>
				
				<div id="mainpage-forum">
					<div id="haut">
						<a href="forum=accueil.php" title="Retour" id="btn_retour"></a>
						<p>Forum personnel</p>
					</div>
					
					<div id="contenu">
						<div class="sujets">
							');
						
							for($j=0 ; $j<$res ; $j++)
							{
							
							$idsujet = mysql_result($req,$j,"id");
							$nomsujet = mysql_result($req,$j,"nom");
							$auteur = mysql_result($req,$j,"auteur");
							$datemodif = date('d/m/y',mysql_result($req,$j,"datemodif"));
							$statut = mysql_result($req,$j,"statut");
							$statut = (ereg("a",$statut))?"Mod&eacute;rateur":((ereg("e",$statut))?"Participant":"Lecteur");
							
							$sqltmp = 'SELECT auteur FROM wikast_forum_posts_tbl WHERE sujet="'.$idsujet.'" AND date = (SELECT MAX(date) FROM wikast_forum_posts_tbl WHERE sujet="'.$idsujet.'")' ;
							$reqtmp = mysql_query($sqltmp);
							$restmp = mysql_num_rows($reqtmp);
							
							if(ereg("\[Verrouill&eacute;\]",$nomsujet))
								{
								$vu = "verrou";
								}
							else
								{
								if($_SESSION['id']!="")					// VERIFICATION DES ARTICLES DEJA VUS
									{
									$sqltmp2 = 'SELECT sujets_vu FROM wikast_joueurs_tbl WHERE pseudo="'.$_SESSION['pseudo'].'"' ;
									$reqtmp2 = mysql_query($sqltmp2);
									
									$vu = (ereg('-'.$idsujet.'-',mysql_result($reqtmp2,0,sujets_vu)))?"oui":"non";
									}
								else $vu = "oui";
								}
							
							$auteurmodif = ($restmp>0)? mysql_result($reqtmp,0,"auteur"):mysql_result($req,$j,"auteur");
							
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
								<div class="auteurmodif"><span class="style1">Statut</span> '.$statut.'</div>
							</div>');
							}
						
						print('</div>
					</div>
					');

				}
				
				print('
					<div id="bas">
						<div class="nouveausujet2"><a href="sujet=nouveau.php?prive=ok" title="Nouveau sujet">Nouveau sujet</a></div>
					</div>
				</div>');
				
			mysql_close($db);
			
			?>
			
		</div>
	
	</body>
	
</html>
