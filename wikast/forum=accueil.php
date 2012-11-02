<?php 
session_start();

if(empty($_SESSION['id'])) $_SESSION['statut'] = "visiteur";

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
				
				<div id="forum-info">
					<p>Bienvenue sur le forum de DreadCast.<br />
					Veuillez respecter les r&egrave;gles de conduites d&eacute;crites dans la charte ici-pr&eacute;sente : <a href="forum=charte.php">Charte des forums et EDC</a>.<br /><br />

					Pour poster, il est n&eacute;cessaire d'&ecirc;tre connect&eacute; sur son compte.<br />
					Votre login est le pseudo que vous avez en v3, et le mot de passe est le m&ecirc;me.</p>
				</div>
			</div>
				
			<div id="forum-categories">
				<?php
				
				print('<div id="partie1">
					<div class="image"></div>
					<p class="titre"><a href="forum.php?partie=G&eacute;n&eacute;ral">Forum G&eacute;n&eacute;ral</a></p>
					<div class="sous-forums">');
						
						$db = mysql_connect('localhost', 'CENSURE', 'CENSURE') or die("Erreur de connexion au serveur des messages.");
						mysql_select_db('CENSURE',$db) or die("Erreur de connexion a la base de donnees des messages.");
			
						$sql = 'SELECT * FROM wikast_forum_structure_tbl WHERE id= "1"' ;
						$req = mysql_query($sql);
						$res = mysql_num_rows($req);
						
						if($res != 0)
							{
							$sql = 'SELECT * FROM wikast_forum_structure_tbl WHERE type= "'.mysql_result($req,0,id).'"' ;
							$req = mysql_query($sql);
							$res = mysql_num_rows($req);
							}
					
						for($i=0 ; $i<$res ; $i++)
							print('
							<a href="forum.php?partie=G&eacute;n&eacute;ral&sous-partie='.mysql_result($req,$i,id).'">'.mysql_result($req,$i,nom).'</a>
							');
						
					print('</div>
				</div>
				<div id="partie2">
					<div class="image"></div>
					<p class="titre"><a href="forum.php?partie=Hors%20Sujet">Forum Hors Sujet</a></p>
					<div class="sous-forums">');
						
						$sql = 'SELECT * FROM wikast_forum_structure_tbl WHERE id="2"' ;
						$req = mysql_query($sql);
						$res = mysql_num_rows($req);
				
						if($res != 0)
							{
							$sql = 'SELECT * FROM wikast_forum_structure_tbl WHERE type= "'.mysql_result($req,0,id).'"' ;
							$req = mysql_query($sql);
							$res = mysql_num_rows($req);
							}
					
						for($i=0 ; $i<$res ; $i++)
							print('
							<a href="forum.php?partie=Hors%20Sujet&sous-partie='.mysql_result($req,$i,id).'">'.mysql_result($req,$i,nom).'</a>
							');
						
					print('</div>
				</div>
				<div id="partie3">
					<div class="image"></div>
					<p class="titre"><a href="forum.php?partie=Role%20Play">Forum Role Play</a></p>
					<div class="sous-forums">');
						
						$sql = 'SELECT * FROM wikast_forum_structure_tbl WHERE id="3"' ;
						$req = mysql_query($sql);
						$res = mysql_num_rows($req);
				
						if($res != 0)
							{
							$sql = 'SELECT * FROM wikast_forum_structure_tbl WHERE type= "'.mysql_result($req,0,id).'"' ;
							$req = mysql_query($sql);
							$res = mysql_num_rows($req);
							}
					
						for($i=0 ; $i<$res ; $i++)
							print('
							<a href="forum.php?partie=Role%20Play&sous-partie='.mysql_result($req,$i,id).'">'.mysql_result($req,$i,nom).'</a>
							');
						
					print('</div>
				</div>
				<div id="partie4">
					<div class="image"></div>
					<p class="titre"><a href="forum.php?partie=Politique">Forum Politique</a></p>
					<div class="sous-forums">');
						
						$sql = 'SELECT * FROM wikast_forum_structure_tbl WHERE id="4"' ;
						$req = mysql_query($sql);
						$res = mysql_num_rows($req);
				
						if($res != 0)
							{
							$sql = 'SELECT * FROM wikast_forum_structure_tbl WHERE type= "'.mysql_result($req,0,id).'"' ;
							$req = mysql_query($sql);
							$res = mysql_num_rows($req);
							}
					
						for($i=0 ; $i<$res ; $i++)
							print('
							<a href="forum.php?partie=Politique&sous-partie='.mysql_result($req,$i,id).'">'.mysql_result($req,$i,nom).'</a>
							');
						
					print('</div>
				</div>');
				
				if($_SESSION['id']!="")
				{
				$sqlc = 'SELECT cercle FROM cercles_tbl WHERE pseudo="'.$_SESSION['pseudo'].'"' ;
				$reqc = mysql_query($sqlc);
				$resc = mysql_num_rows($reqc);
				
				if($resc!=0)
					{
					$cercle = mysql_result($reqc,0,cercle);
					
					print('<div id="partie5">
						<div class="image"></div>
						<p class="titre"><a href="forum.php?partie=Cercle%20'.$cercle.'">Forum '.$cercle.'</a></p>
						<div class="sous-forums">');
							
							$sql = 'SELECT * FROM wikast_forum_structure_tbl WHERE nom="Cercle '.$cercle.'"' ;
							$req = mysql_query($sql);
							$res = mysql_num_rows($req);
				
							if($res != 0)
								{
								$sql = 'SELECT * FROM wikast_forum_structure_tbl WHERE type= "'.mysql_result($req,0,id).'"' ;
								$req = mysql_query($sql);
								$res = mysql_num_rows($req);
								}
					
							for($i=0 ; $i<$res ; $i++)
								print('
								<a href="forum.php?partie=Cercle%20'.$cercle.'&sous-partie='.mysql_result($req,$i,id).'">'.mysql_result($req,$i,nom).'</a>
								');
							
						print('</div>
					</div>');
					}
				}
					
				mysql_close($db);
				
				?>
			</div>
			
		</div>
	
	</body>
	
</html>
