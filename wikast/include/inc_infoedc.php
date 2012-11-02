<?php
				$db = mysql_connect('localhost', 'CENSURE', 'CENSURE') or die("Erreur de connexion au serveur des messages.");
				mysql_select_db('CENSURE',$db) or die("Erreur de connexion a la base de donnees des messages.");

				$sql = 'SELECT * FROM messages_tbl WHERE cible="'.$_SESSION['pseudo'].'" AND nouveau="oui"' ;
				$req = mysql_query($sql);
				$res1 = mysql_num_rows($req);
				
				$sql = 'SELECT * FROM wikast_edc_articles_tbl WHERE auteur="'.$_SESSION['pseudo'].'"' ;
				$req = mysql_query($sql);
				$res2 = mysql_num_rows($req);
				
				$sql = 'SELECT * FROM wikast_edc_etoiles_tbl WHERE cible="'.$_SESSION['pseudo'].'"' ;
				$req = mysql_query($sql);
				$res3 = mysql_num_rows($req);
				
				mysql_close($db);
				
				print('<div id="edc-perso">		
					<!-- AFFICHAGE DE VOTRE FICHE -->
					<a href="edc.php" style="display:block;" title="Aller sur mon EDC" class="titre">Mon Espace DC</a>
					<div class="fiche">
					
						<p>'.$_SESSION['pseudo'].'<br />
						<!--<a href="../ingame/engine=messages.php">Messagerie'); if($res1>0) print(' <span class="style1">('.$res1.')</span>'); print('</a>--><br />
						<span class="style2">'.$res2.'</span> article');if($res2!=1) print('s'); print(' &eacute;crit');if($res2!=1) print('s'); print('<br />
						<span class="style2">'.$res3.'</span> &eacute;toile');if($res3!=1) print('s'); print(' re&ccedil;ue');if($res3!=1) print('s'); print('
						</p>
					</div>
					<div class="image"><img src="');
					
					if((ereg("http",$_SESSION['avatar'])) OR (ereg("ftp",$_SESSION['avatar']))) print($_SESSION['avatar']);
					else print('../ingame/avatars/'.$_SESSION['avatar']);
					
					print('" alt="Votre avatar" width="68" height="68" /></div>
				</div>');
?>
