<?php
session_start();

include('include/inc_head.php'); ?>

		<div id="page">
		
			<?php include('include/inc_barre2.php'); ?>
		
			<a href="wiki=accueil.php" id="lien-wiki"></a>
			<?php if($_SESSION['id']!="") print('<a href="edc.php" id="lien-edc"></a>'); ?>
			
			<!-- -------------------------------------------------------------------------- MISE EN PAGE GENERALE -------------------------------------------------------------------------- -->
			<div id="page-EDC">
				
				<?php
				
				include('include/inc_barreliens2.php');
				
					print('
				<div id="zone-etoiles">
					<p class="actions">
						<a href="#1" class="lien1">Qu\'est-ce qu\'un EDC ?</a><br />
						<a href="#2" class="lien2">Ma "Pr&eacute;sentation" ?</a><br />
						<a href="#3" class="lien3">Vous avez dit une "&eacute;toile" ?</a><br />
						<a href="#4" class="lien4">Mes infos perso ?</a>
					</p>
				</div>
				
				<div id="zone-centre">
					<p class="titre">Aide</p>
					<p class="image">'); if($_SESSION['id']!="") print('<a href="edc.php" class="commentaires4">Retour &agrave; mon EDC</a>'); print('</p>
					<div class="texte">
						<div class="style1">
							<span style="font-size:25px;float:left;clear:left;margin-right:5px;color:#bbb;">?</span>Afin de rendre cette partie du site plus claire, l\'aide &agrave; &eacute;t&eacute; construite sous forme de FAQ. Pour tout renseignement compl&eacute;mentaire, n\'h&eacute;sitez pas &agrave; contacter un mod&eacute;rateur.<br /><br /><br />
							
							<span id="1" class="couleur2" style="font-size:25px;float:left;clear:left;margin-right:5px;">?</span><span style="color:#bbb;font-size:14px;font-size:bold;">Qu\'est-ce qu\'un EDC</span><br />
							Un EDC, lit&eacute;ralement Espace DreadCast, est une zone d&eacute;di&eacute;e &agrave; l\'expression du RP des joueurs de DreadCast. On pourrait comparer &ccedil;a &agrave; un blog autour de l\'univers de DreadCast.<br /><br /><br />
							
							<span id="2" class="couleur2" style="font-size:25px;float:left;clear:left;margin-right:5px;">?</span><span style="color:#bbb;font-size:14px;font-size:bold;">Qu\'est-ce que ma "Pr&eacute;sentation"</span><br />
							Votre pr&eacute;sentation est un article un peu particulier, puisque c\'est celui que verront en premier les visiteurs de votre EDC. C\'est donc sur cet article qu\'il est conseill&eacute; de d&eacute;crire au mieux votre personnage.<br /><br /><br />
							
							<span id="3" class="couleur2" style="font-size:25px;float:left;clear:left;margin-right:5px;">?</span><span style="color:#bbb;font-size:14px;font-size:bold;">Qu\'est-ce qu\'une &eacute;toile</span><br />
							Les &eacute;toiles sont des objets que l\'on peut donner &agrave; un autre joueur, afin de l\'avoir dans sa liste d\'EDC. Recevoir une &eacute;toile est un gage de reconnaissance.<br />Un joueur normal dispose initialement de 5 &eacute;toiles, qu\'il devra distribuer avec parcimonie, car il ne pourra plus "&eacute;toiler" de joueur s\'il n\'a plus d\'&eacute;toile ! Pour en obtenir de nouvelles, il devra simplement attendre que d\'autre joueurs lui en donnent une.<br />Les comptes VIP disposent d\'un nombre d\'&eacute;toiles illimit&eacute;.<br /><br /><br />
							
							<span id="4" class="couleur2" style="font-size:25px;float:left;clear:left;margin-right:5px;">?</span><span style="color:#bbb;font-size:14px;font-size:bold;">A quoi servent mes infos perso</span><br />
							Vos informations personnelles, que vous pouvez modifier en cliquant sur le lien "Modifier vos infos perso" une fois connect&eacute; &agrave; votre EDC, permettent de renseigner les joueurs sur les caract&eacute;ristiques de votre personnage. Vous pouvez ainsi choisir ce que vous souhaitez que les autres apprennent de vous en visitant votre EDC.<br />Vous pouvez &eacute;galement inscrire un commentaire &agrave; leur attention, que vous pourrez utiliser comme signature sur le forum.<br /><br /><br />
							
							<span id="5" class="couleur2" style="font-size:25px;float:left;clear:left;margin-right:5px;">?</span><span style="color:#bbb;font-size:14px;font-size:bold;">Pourquoi je n\'ai jamais vu mon profil dans "Personnage al&eacute;atoire"</span><br />
							Vous avez sans doute remarqu&eacute; qu\'en arrivant sur le WiKast, vous pouvez voir un profil de joueur appara&icirc;tre de mani&egrave;re al&eacute;atoire en bas &agrave; droite de la page.<br />Pour avoir une chance d\'appara&icirc;tre &eacute;galement &agrave; cet endroit, il faut au moins que vous ayez trouv&eacute; un avatar diff&eacute;rent de celui qui vous est fourni initialement.<br />Si ce n\'est pas encore fait, vous pouvez en changer en vous connectant sur DreadCast et en cliquant sur votre avatar dans la rubrique "Stats".<br />Il faut &eacute;galement que vous ayez d&eacute;fini une pr&eacute;sentation personnelle.<br /><br /><br />
							
							N\'h&eacute;sitez pas &agrave; nous contacter pour nous faire part de vos questions.
						</div>
					</div>
				</div>
				
				<div id="zone-articles">
					<p class="titre"></p>
					<p class="actions">
						<a href="#5" class="lien1">Pourquoi pas moi ?</a><br />
					</p>
				</div>
					');
				
				?>

			</div>
			
			<div id="wiki">
				<!-- PARTIE DU BAS : WIKI -->
				
				<?php include('include/inc_wikiderniers.php') ?>
				
				<?php include('include/inc_searcharticle.php'); ?>
				
				<?php
				
				if($_SESSION['id']!="")
					{
					include('include/inc_infoedc.php');
				
					print('<div id="edc-infopersonnage">
						<!-- INFOS CONCERNANT MON  PERSONNAGE -->');
					include('include/inc_situation.php');
					print('</div>');
					}
				else
					{
				print('<div id="edc-random">
					<!-- AFFICHAGE D\'UNE FICHE ALEATOIRE -->');
					
					include('include/inc_randomedc.php');
					
				print('</div>
				
				<div id="edc-monespace">
					<!-- ACCES A MON ESPACE PERSO -->
					
					<div id="lien-EDC2">
							<p>Connectez-vous pour acc&eacute;der &agrave; votre EDC</p>
					</div>
				</div>');
					}
				?>
				
				<?php include('include/inc_searchedc.php'); ?>
			</div>	
		</div>
	
	</body>
	
</html>
