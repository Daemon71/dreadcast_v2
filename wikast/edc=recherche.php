<?php 
session_start();

if($_POST['recherche']=="")
		{
		$texte1 = 'Veuillez sp&eacute;cifier une cible &agrave; votre recherche.';
		$texte2 = '';
		}
	else
		{
		$db = mysql_connect('localhost', 'CENSURE', 'CENSURE') or die("Erreur de connexion au serveur des messages.");
		mysql_select_db('CENSURE',$db) or die("Erreur de connexion a la base de donnees des messages.");
		
		$sql1 = 'SELECT DISTINCT auteur FROM wikast_edc_articles_tbl WHERE auteur LIKE "%'.$_POST['recherche'].'%" ORDER BY auteur DESC' ;
		$req1 = mysql_query($sql1);
		$res1 = mysql_num_rows($req1);
		
		if($res1 == 0) $texte1 = "Il n'y a aucun EDC correspondant &agrave; la recherche \"<span class=\"couleur2\">".$_POST['recherche']."</span>\".<br />";
		else 
			{
			$texte1 = "Il y a <span class=\"couleur1\">".$res1."</span> EDC trouv&eacute;"; if($res1!=1) $texte1 .= "s"; $texte1 .= " pour la recherche \"<span class=\"couleur2\">".$_POST['recherche']."</span>\" :<br /><br />";
		
			for($i=0 ; $i < $res1 ; $i++)
				{
				$texte1 .= '<a href="edc=visio.php?auteur='.mysql_result($req1,$i,auteur).'" class="bloc"><span class="style13">EDC de '.mysql_result($req1,$i,auteur).'</span></a>
				';
				}
			}
		
		$sql2 = 'SELECT auteur,id,titre FROM wikast_edc_articles_tbl WHERE titre LIKE "%'.$_POST['recherche'].'%" AND titre!="Pr&eacute;sentation" ORDER BY date DESC' ;
		$req2 = mysql_query($sql2);
		$res2 = mysql_num_rows($req2);
		
		if($res2 == 0) $texte2 = "Il n'y a aucun article correspondant &agrave; la recherche \"<span class=\"couleur2\">".$_POST['recherche']."</span>\".";
		else 
			{
			$texte2 = "Il y a <span class=\"couleur1\">".$res2."</span> article"; if($res2!=1) $texte2 .= "s"; $texte2 .= " trouv&eacute;"; if($res2!=1) $texte2 .= "s"; $texte2 .= " pour la recherche \"<span class=\"couleur2\">".$_POST['recherche']."</span>\" :<br /><br />";
		
			for($i=0 ; $i < $res2 ; $i++)
				{
				$texte2 .= '<a href="edc=visio.php?auteur='.mysql_result($req2,$i,auteur).'&feuille='.mysql_result($req2,$i,id).'" class="bloc"><span class="style13">"'.mysql_result($req2,$i,titre).'" par '.mysql_result($req2,$i,auteur).'</span></a>
				';
				}
			}
		
		mysql_close($db);
		}


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
					</p>
				</div>
				
				<div id="zone-centre">
					<p class="titre">Recherche</p>
					<p class="image">'); if($_SESSION['id']!="") print('<a href="edc.php" class="commentaires4">Retour &agrave; mon EDC</a>'); print('</p>
					<div class="texte">
						<div class="style1">
							'.$texte1.'<br />
							'.$texte2.'
						</div>
					</div>
				</div>
				
				<div id="zone-articles">
					<p class="titre"></p>
					<p class="actions">
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
