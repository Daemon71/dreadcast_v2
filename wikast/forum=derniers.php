<?php 
session_start();

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
				
				
				print('<div id="forum-entete">');
				
				include('include/inc_barreliens1.php');
				
				print('
					
					<div id="forum-info2">
						<p class="gauche">');
						print('</p>

						<p class="droite">');
						print('</p>
					</div>
				</div>
				
				<div id="mainpage-forum">
					<div id="haut">
						<a href="forum=accueil.php" title="Retour" id="btn_retour"></a>
						<p>Les 20 derniers sujets</p>
					</div>
					
					<div id="contenu">
						
						<div class="sujets">');
						
						$maxdate = 3000000000;
						
						$j=0;
						$jmax=20;
						
						while($j<$jmax)
							{
							$sql = 'SELECT MAX(datemodif) FROM wikast_forum_sujets_tbl WHERE datemodif < '.$maxdate.'' ;
							$req2 = mysql_query($sql);
							
							$sql = 'SELECT id,nom,auteur,datemodif,categorie FROM wikast_forum_sujets_tbl WHERE datemodif = '.mysql_result($req2,0,'MAX(datemodif)').'';
							$req2 = mysql_query($sql);
							
							$idsujet = mysql_result($req2,0,"id");
							$nomsujet = mysql_result($req2,0,"nom");
							$auteur = mysql_result($req2,0,"auteur");
							$ts = mysql_result($req2,0,"datemodif");
							$datemodif = date('d/m/y',mysql_result($req2,0,"datemodif"));
							
							$sqlhop = 'SELECT type,nom FROM wikast_forum_structure_tbl WHERE id="'.mysql_result($req2,0,categorie).'"' ;
							$reqhop = mysql_query($sqlhop);
							$reshop = mysql_num_rows($reqhop);
							if($reshop != 0) $forum = mysql_result($reqhop,0,nom);
	
							if($reshop != 0 AND (mysql_result($reqhop,0,type)==1 OR mysql_result($reqhop,0,type)==2 OR mysql_result($reqhop,0,type)==3 OR mysql_result($reqhop,0,type)==4))
								{
								
								$maxdate = $ts;
							
								$sqltmp = 'SELECT auteur FROM wikast_forum_posts_tbl WHERE sujet="'.$idsujet.'" AND date = (SELECT MAX(date) FROM wikast_forum_posts_tbl WHERE sujet="'.$idsujet.'")' ;
								$reqtmp = mysql_query($sqltmp);
								$restmp = mysql_num_rows($reqtmp);
							
								if(preg_match("/\[Verrouill\&eacute\;\]/",$nomsujet))
									{
									$vu = "verrou";
									}
								else
									{
									if($_SESSION['id']!="")					// VERIFICATION DES ARTICLES DEJA VUS
										{
										$sqltmp2 = 'SELECT sujets_vu FROM wikast_joueurs_tbl WHERE pseudo="'.$_SESSION['pseudo'].'"' ;
										$reqtmp2 = mysql_query($sqltmp2);
										$restmp2 = mysql_num_rows($reqtmp2);
									
										if($restmp2) $vu = (preg_match('/\-'.$idsujet.'\-/',mysql_result($reqtmp2,0,sujets_vu)))?"oui":"non";
										else $vu = "oui";
										}
									else $vu = "oui";
									}
								
								$auteurmodif = ($restmp>0)?mysql_result($reqtmp,0,"auteur"):$auteur;
							
								print('
								<div class="sujet">
									');
									if($vu=="non") print('<div class="pasvu"></div>');
									elseif($vu=="oui") print('<div class="dejavu"></div>');
									elseif($vu=="verrou") print('<div class="cadenas"></div>');
									print('
									<a href="sujet.php?id='.$idsujet.'&page=max" class="bof"></a>
									<a style="display:block;color:#999;" href="sujet.php?id='.$idsujet.'&page=max" class="titre">'.$nomsujet.'<br /><span class="auteur">Par '.$auteur.' - '.$forum.'</span></a>
									<div class="datemodif"><span class="style1">Modifi&eacute; le</span> '.$datemodif.'</div>
									<div class="auteurmodif"><span class="style1">Par</span> '.$auteurmodif.'</div>
								</div>');
								
								$j++;
								}
							else
								{
								$j++;
								$jmax++;
								$maxdate = $ts;
								}
							}
							print('</div>
						
						<a href="forum=accueil.php" title="Retour" class="retour">Retour</a>
						
					</div>
					
					<div id="bas">');
					print('</div>
				</div>
				');
				
			mysql_close($db);
			?>
			
		</div>
	
	</body>
	
</html>
