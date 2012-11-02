<?php 
session_start();

include('include/inc_head.php'); ?>

		<div id="page">
			<a href="forum=accueil.php" id="lien-forum"></a>
			
			<!-- -------------------------------------------------------------------------- MISE EN PAGE GENERALE -------------------------------------------------------------------------- -->
			<div id="forum">
			
				<?php include('include/inc_barre1.php'); ?>
			
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
					<p>Statistiques du Wikast</p>
				</div>
				
				<div id="contenu">
				
					<?php
					
					print('<h3>Structure</h3>
					<h4>Forum</h4>
					
					<div class="paragraphes">');
					
						
					$db = mysql_connect('localhost', 'CENSURE', 'CENSURE') or die("Erreur de connexion au serveur des messages.");
					mysql_select_db('CENSURE',$db) or die("Erreur de connexion a la base de donnees des messages.");
					
					$sql = 'SELECT id FROM wikast_forum_structure_tbl WHERE type="-1"' ;
					$req = mysql_query($sql);
					$res = mysql_num_rows($req);
					$sql = 'SELECT id FROM wikast_forum_structure_tbl WHERE type!="-1"' ;
					$req = mysql_query($sql);
					$res2 = mysql_num_rows($req);
					
					print('<p>Le Wikast comporte <span style="color:#eee;">'.$res.'</span> forums et <span style="color:#eee;">'.$res2.'</span> sous-forums.</p>');
					
					$sql = 'SELECT id FROM wikast_forum_posts_tbl' ;
					$req = mysql_query($sql);
					$res31 = mysql_num_rows($req);
					$sql = 'SELECT id FROM wikast_forum_sujets_tbl' ;
					$req = mysql_query($sql);
					$res32 = mysql_num_rows($req);
					$res3 = $res31+$res32;
					
					print('<p>Un total de <span style="color:#eee;">'.$res3.'</span> messages ont été postés.</p>');
					
					$sql = 'SELECT DISTINCT auteur FROM wikast_forum_posts_tbl' ;
					$req = mysql_query($sql);
					$res41 = mysql_num_rows($req);
					$sql = 'SELECT DISTINCT auteur FROM wikast_forum_sujets_tbl' ;
					$req = mysql_query($sql);
					$res42 = mysql_num_rows($req);
					$res4 = $res41+$res42;
					
					print('<p><span style="color:#eee;">'.$res4.'</span> personnes différentes ont posté des messages sur le forum.</p>');
					
					$sql = 'SELECT numero FROM wikast_forum_posts_tbl ORDER BY numero DESC' ;
					$req = mysql_query($sql);
					$sql = 'SELECT sujet FROM wikast_forum_posts_tbl WHERE numero="'.mysql_result($req,0,"numero").'"' ;
					$req = mysql_query($sql);
					$sql = 'SELECT id,nom FROM wikast_forum_sujets_tbl WHERE id="'.mysql_result($req,0,sujet).'"' ;
					$req = mysql_query($sql);
					$res5 = mysql_result($req,0,nom);
					$sql = 'SELECT id FROM wikast_forum_posts_tbl WHERE sujet="'.mysql_result($req,0,id).'"' ;
					$req = mysql_query($sql);
					$res6 = mysql_num_rows($req);
					
					print('<p><span style="color:#eee;">'.$res5.'</span> est le sujet le plus populaire, avec <span style="color:#eee;">'.($res6+1).'</span> messages.</p>');
					
					$sql = 'SELECT DISTINCT auteur FROM wikast_forum_posts_tbl' ;
					$req = mysql_query($sql);
					$restmp = mysql_num_rows($req);
					$res8 = 0;
					for($i=0;$i<$restmp;$i++)
						{
						$sql = 'SELECT id FROM wikast_forum_posts_tbl WHERE auteur="'.mysql_result($req,$i,auteur).'"' ;
						$reqtmp = mysql_query($sql);
						$restmp21 = mysql_num_rows($reqtmp);
						$sql = 'SELECT id FROM wikast_forum_sujets_tbl WHERE auteur="'.mysql_result($req,$i,auteur).'"' ;
						$reqtmp = mysql_query($sql);
						$restmp22 = mysql_num_rows($reqtmp);
						$restmp2 = $restmp21+$restmp22;
						if($restmp2 > $res8)
							{
							$res7 = mysql_result($req,$i,auteur);
							$res8 = $restmp2;
							}
						}
						
					print('<p><span style="color:#eee;">'.$res7.'</span> est le plus grand posteur, avec un total de <span style="color:#eee;">'.$res8.'</span> messages.</p>');
					
					print('</div>');
					
					mysql_close($db);
					
					?>
					
					<br />
					<a href="forum=accueil.php" title="Retour" class="retour">Retour</a>
				</div>
				
				<div id="bas">
				</div>
			</div>			
		</div>
	
	</body>
	
</html>
