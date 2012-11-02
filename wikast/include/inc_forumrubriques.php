				<div id="forum-rubriques">
					<!-- AFFICHAGE DES RUBRIQUES -->
					<a href="forum=accueil.php" class="titre" style="display:block;">Voir les forums</a>
					<div id="texte-rubriques">
						<p><a href="forum.php?partie=G&eacute;n&eacute;ral">Forum G&eacute;n&eacute;ral</a></p>
						<p><a href="forum.php?partie=Hors%20Sujet">Forum Hors Sujet</a></p>
						<p><a href="forum.php?partie=Role%20Play">Forum Role Play</a></p>
						<p><a href="forum.php?partie=Politique">Forum Politique</a></p>
						<?php
						
						if($_SESSION['id']!="")
							{
							$db = mysql_connect('localhost', 'CENSURE', 'CENSURE') or die("Erreur de connexion au serveur des messages.");
							mysql_select_db('CENSURE',$db) or die("Erreur de connexion a la base de donnees des messages.");

							$sqlc = 'SELECT cercle FROM cercles_tbl WHERE pseudo="'.$_SESSION['pseudo'].'"' ;
							$reqc = mysql_query($sqlc);
							$resc = mysql_num_rows($reqc);
							
							if($resc!=0)
								{
								$cercle = mysql_result($reqc,0,cercle);
								print('<p><a href="forum.php?partie=Cercle '.$cercle.'">Forum '.$cercle.'</a></p>');
								}
							
							mysql_close($db);
							}
						
						?>
					</div>
				</div>
