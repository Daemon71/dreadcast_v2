<?php 
session_start();

if($_POST['titre']!="") $erreurtitre = stripslashes(stripslashes(stripslashes($_POST['titre'])));
if($_POST['categorie']!="") $erreurcategorie = stripslashes(stripslashes(stripslashes($_POST['categorie'])));
if($_POST['introduction']!="") $erreurintro = stripslashes(stripslashes(stripslashes($_POST['introduction'])));
if($_POST['article']=="") $erreurarticle = "Veuillez &eacute;crire entre les balises [paragraphe][/paragraphe]";
else $erreurarticle = stripslashes(stripslashes(stripslashes($_POST['article'])));
if($_POST['mots']!="") $erreurmots = stripslashes(stripslashes(stripslashes($_POST['mots'])));

if(empty($_SESSION['id'])) $_SESSION['statut'] = "visiteur";
else
	{
	
	$db = mysql_connect('localhost', 'CENSURE', 'CENSURE') or die("Erreur de connexion au serveur des messages.");
	mysql_select_db('CENSURE',$db) or die("Erreur de connexion a la base de donnees des messages.");
	
	$sql = 'SELECT id FROM wikast_joueurs_tbl WHERE pseudo="'.$_SESSION['pseudo'].'"' ;
	$req = mysql_query($sql);
	$res = mysql_num_rows($req);
	
	if($res == 0)
		{
		$sql = 'INSERT INTO wikast_joueurs_tbl(id,pseudo,infoperso,sujets_vu,commentaire) VALUES("","'.$_SESSION['pseudo'].'","-","-","")' ;
		mysql_query($sql);
		}
	
	if($_SESSION['statut']!="Compte VIP" && $_SESSION['statut']!="Silver" && $_SESSION['statut']!="Gold" && $_SESSION['statut']!="Platinium" && $_SESSION['statut']!="Modérateur" && $_SESSION['statut']!="Administrateur")
		{
		print('<meta http-equiv="refresh" content="0 ; url=wiki=accueil.php">');
		exit();
		}

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
					<p class="gauche"><br /><br /><br /><?php if(statut($_SESSION['statut'])>=2) { print('<a href="wiki=accueil.php">Retour &agrave; l\'accueil</a>'); } ?></p>
				</div>
			</div>
			
			<form id="mainpage-forum" action="wiki=valider.php" method="post" name="poster">
				<div id="haut">
					<p class="titre"><?php print('Titre <input type="text" class="titre" name="titre" onmouseover="focus();" value="'.$erreurtitre.'" width="60" />'); ?></p>
				</div>
					
				<div id="contenu">
					<div class="infos-article" style="height:30px;">
						<div class="situation">Cat&eacute;gorie <select name="categorie">
							<?php
							
							print('<option value=""'); if($erreurcategorie=="") print(' selected="selected"'); print('>S&eacute;lectionnez une section</option>');
							print('<option value="Histoire de la ville"'); if($erreurcategorie=="Histoire de la ville") print(' selected="selected"'); print('>Histoire de la ville</option>');
							print('<option value="Les commerces"'); if($erreurcategorie=="Les commerces") print(' selected="selected"'); print('>Les commerces</option>');
							print('<option value="Politique de Dreadcast"'); if($erreurcategorie=="Politique de Dreadcast") print(' selected="selected"'); print('>Politique de Dreadcast</option>');
							print('<option value="Personnages"'); if($erreurcategorie=="Personnages") print(' selected="selected"'); print('>Personnages</option>');
							print('<option value="Autour de Dreadcast"'); if($erreurcategorie=="Autour de Dreadcast") print(' selected="selected"'); print('>Autour de Dreadcast</option>');
							print('<option value="Trucs & astuces"'); if($erreurcategorie=="Trucs & astuces") print(' selected="selected"'); print('>Trucs & astuces</option>');
							print('<option value="Informations utiles"'); if($erreurcategorie=="Informations utiles") print(' selected="selected"'); print('>Informations utiles</option>');
							
							?>
						</select></div>
					</div>
					
					<div class="wiki_p"><em>Vous vous appr&ecirc;tez &agrave; &eacute;crire un article sur le WiKast. Il devra &ecirc;tre valid&eacute; par les administrateurs avant de para&icirc;tre en ligne.<br /><br />
					Veuillez prendre rapidement connaissance de l'utilisation du DCcode li&eacute; au wiki sur <a href="wiki.php?id=1#p22"  onclick="window.open(this.href); return false;">cette page</a> avant de poster.<br /><br />
					Bonne r&eacute;daction.</em></div>
					<?php
					print('<div class="wiki_p"><strong>Introduction</strong></div>
					<textarea name="introduction" id="textarea1">'.$erreurintro.'</textarea>
					<div id="DCcode">
						<a href="javascript:AddText1(\'[g]\',\'\',\'[/g]\');" title="Mettre le texte en gras" class="style1"><strong>G</strong></a>
						<a href="javascript:AddText1(\'[i]\',\'\',\'[/i]\');" title="Mettre le texte en italique" class="style1"><em>I</em></a>
						<a href="javascript:AddText1(\'[lien url=<URL DE VOTRE LIEN>]\',\'\',\'[/lien]\');" title="Cr&eacute;er un lien">LIEN</a>
					</div>
					<br />
					<div class="wiki_p"><strong>Article</strong></div>
					<textarea name="article" id="textarea2">'.$erreurarticle.'</textarea>
					<div id="DCcode">
						<a href="javascript:AddText2(\'[g]\',\'\',\'[/g]\');" title="Mettre le texte en gras" class="style1"><strong>G</strong></a>
						<a href="javascript:AddText2(\'[i]\',\'\',\'[/i]\');" title="Mettre le texte en italique" class="style1"><em>I</em></a>
						<a href="javascript:AddText2(\'[img url=<URL DE VOTRE IMAGE> /]\',\'\',\'\');" title="Afficher une image">IMG</a>
						<a href="javascript:AddText2(\'[lien url=<URL DE VOTRE LIEN>]\',\'\',\'[/lien]\');" title="Cr&eacute;er un lien">LIEN</a>
						
						<a href="javascript:AddText2(\'[grand titre]\',\'\',\'[/grand titre]\');" title="Cr&eacute;er un grand titre" class="d110">Grand titre</a>
						<a href="javascript:AddText2(\'[petit titre]\',\'\',\'[/petit titre]\');" title="Cr&eacute;er un petit titre" class="d110">Petit titre</a>
						<a href="javascript:AddText2(\'[paragraphe]\',\'\',\'[/paragraphe]\');" title="Ajouter un paragraphe" class="d110">Paragraphe</a>
						<br /><br /><a href="javascript:AddText2(\'[quote]\',\'\',\'[/quote]\');" title="Citer un texte" class="d340">Citer</a>
						<a href="javascript:AddText2(\'[fiche image=<IMAGE> titre=<TITRE>]\',\'\',\'[/fiche]\');" title="Cr&eacute;er une fiche" class="d340">Fiche</a>
					</div>
					<br />
					<div class="wiki_p"><a href="wiki.php?id=1#p26" onclick="window.open(this.href); return false;">Mots-cl&eacute;</a> (S&eacute;par&eacute;s par une virgule)
					<input type="text" name="mots" style="position:relarive;left:10px;width:270px;border-color:#444;background-color:#222;" value="'.$erreurmots.'"/>
					</div>
					<br />
					<input name="submit" type="submit" value="Aper&ccedil;u avant validation" class="ok2" />');
					?>
				</div>
			</form>
			
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
