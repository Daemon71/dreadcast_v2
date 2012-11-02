					<?php
					
					$db = mysql_connect('localhost', 'CENSURE', 'CENSURE') or die("Erreur de connexion au serveur des messages.");
					mysql_select_db('CENSURE',$db) or die("Erreur de connexion a la base de donnees des messages.");
					
					$res=0;
					
					while($res==0)
					{
					$sql = 'SELECT W.pseudo, P.avatar, P.statut FROM principal_tbl P, wikast_joueurs_tbl W WHERE W.pseudo = P.pseudo AND P.avatar!="interogation.jpg" ORDER BY RAND()';
					$req1 = mysql_query($sql);
					$res = mysql_num_rows($req1);
					
					if($res!=0)
					{
					$sql = 'SELECT id FROM wikast_edc_articles_tbl WHERE auteur="'.mysql_result($req1,0,pseudo).'" AND contenu NOT LIKE "[centrer]Bienvenue sur votre Espace DreadCast, citoyen [g]'.mysql_result($req1,0,pseudo).'[/g].[/centrer]%"';
					$req = mysql_query($sql);
					$res = mysql_num_rows($req);
					}
					}
					
					print('<p class="titre">Espaces DC</p>
					<p class="style1">Personnage al&eacute;atoire</p>
					<div class="fiche">
						<p>'.mysql_result($req1,0,pseudo).'<br />
						'.mysql_result($req1,0,statut).'<br />
						<a href="edc=visio.php?auteur='.mysql_result($req1,0,pseudo).'">Consulter sa fiche</a>
						</p>
					</div>
					<div class="image"><img src="');
					
					if((preg_match("#http#",mysql_result($req1,0,avatar))) OR (preg_match("#ftp#",mysql_result($req1,0,avatar)))) print(mysql_result($req1,0,avatar));
					else print('../ingame/avatars/'.mysql_result($req1,0,avatar));
					
					print('" alt="Avatar" width="68" height="68" /></div>
					<div class="barrevert"></div>');
					
					mysql_close($db);
					
					?>
