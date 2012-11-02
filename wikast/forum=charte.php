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
					<p>Charte des forums et EDC</p>
				</div>
				
				<div id="contenu">
					<h3>1. Responsabilit&eacute; l&eacute;gale</h3>
					<div class="paragraphes">
						<p>L'auteur d'un message est seul responsable du contenu qu'il publie. En aucun cas les administrateurs du site DreadCast.net ne pourront &ecirc;tre tenus responsables d'&eacute;ventuels pr&eacute;judices occasionn&eacute;s par l'un de ses membres.</p>
						<p>Le non respect de cette charte et de la l&eacute;gislation fran&ccedil;aise en vigueur entra&icirc;nera syst&eacute;matiquement l'application de sanctions ad&eacute;quates.</p>
					</div>
					<h3>2. R&egrave;gles &eacute;l&eacute;mentaires de biens&eacute;ance</h3>
					<div class="paragraphes">
						<p>Afin que le WiKast demeure un lieu vivable pour chacun d'entre nous, il est n&eacute;cessaire que tous les joueurs respectent un certain nombre de r&egrave;gles morales. Ces r&egrave;gles sont bas&eacute;es sur la <a href="http://tools.ietf.org/html/rfc1855" title="Document original">Netiquette</a>, et sont obligatoires sur les diff&eacute;rents forums ainsi que sur les EDC.</p>
						<p>Ne pas les respecter, c'est s'exposer &agrave; d'&eacute;ventuelles sanctions, allant du simple avertissement &agrave; la suppression du compte.</p>
					</div>
					<h4>2. 1. Comportement des joueurs</h4>
					<div class="paragraphes">
						<p>Seront sanctionn&eacute;s tous comportements agressifs, provocateurs ou d&eacute;plac&eacute;s, dans la mesure o&ugrave; ils d&eacute;passent le cadre RP dans lesquels ils sont susceptibles de s'inscrire.</p>
						<p>Tout joueur est invit&eacute; &agrave; contacter un administrateur s'il est victime ou t&eacute;moin de tels comportements.</p>
					</div>
					<h4>2. 2. Contenu des messages</h4>
					<div class="paragraphes">
						<p>Le flood (messages inutiles et/ou en grand nombre) n'est pas tol&eacute;r&eacute; sur les forums autre que le forum Hors Sujet, o&ugrave; il est soumis &agrave; la responsabilit&eacute; des mod&eacute;rateurs.</p>
						<p>Les propos &agrave; caract&egrave;re ordurier, violent, raciste, pornographique, p&eacute;dophile, diffamatoire, et portant de mani&egrave;re g&eacute;n&eacute;rale atteinte &agrave; l'int&eacute;grit&eacute; d'une personne ou d'un groupe de personnes, sont prohib&eacute;s et leurs auteurs s'exposent aux plus lourdes sanctions.</p>
						<p>Le RP est soumis aux m&ecirc;mes r&egrave;gles : tout RP doit recevoir l'approbation des personnes explicitement ou implicitement cit&eacute;s avant d'&ecirc;tre publi&eacute;s.</p>
						<p>Il est interdit de promovoir d'une quelconque façon un autre jeu en ligne sur la structure du Wikast.</p>
						<p>Le langage SMS n'est pas permis sur les forums, dans la mesure o&ugrave; aucun effort orthographique n'est visible.</p>
					</div>
					<h3>3. R&egrave;gles de mod&eacute;ration</h3>
					<div class="paragraphes">
						<p>L'&eacute;quipe des mod&eacute;rateurs ainsi que les administrateurs se r&eacute;servent le droit d'&eacute;dition et de suppression des messages critiques. Leur auteur recevra un avertissement pour chaque non respect des r&egrave;gles pr&eacute;c&eacute;dement dict&eacute;es.</p>
						<p>Trois avertissements successifs non pris en compte par le joueur pourra conduire &agrave; son banissement d&eacute;finitif des forums et EDC.</p>
					</div>
					<br />
					<a href="forum=accueil.php" title="Retour" class="retour">Retour</a>
				</div>
				
				<div id="bas">
				</div>
			</div>			
		</div>
	
	</body>
	
</html>
