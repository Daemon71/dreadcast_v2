				<?php
				$db = mysql_connect('localhost', 'CENSURE', 'CENSURE') or die("Erreur de connexion au serveur des messages.");
				mysql_select_db('CENSURE',$db) or die("Erreur de connexion a la base de donnees des messages.");
	
				$sql = 'SELECT id,titre FROM wikast_wiki_articles_tbl WHERE etat = 2 ORDER BY moment DESC' ;
				$req = mysql_query($sql);
				$res = mysql_num_rows($req);
				
				print('<div id="wiki-derniers">
					<!-- AFFICHAGE DES RUBRIQUES -->
					<a href="wiki=derniers.php" style="display:block;" class="titre">Derniers articles</a>
					<ul>
						');
						
						$res = ($res<4)?$res:4;
						
						for($i=0 ; $i<$res ; $i++)
							{
							print('<li><a href="wiki.php?id='.mysql_result($req,$i,id).'">'.mysql_result($req,$i,titre).'</a></li>
						');
							}
							
					print('</ul>
				</div>');
				
				mysql_close($db);
				?>
