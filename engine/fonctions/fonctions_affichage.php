<?php

function affiche_etoiles($pseudo) {
	$retour = '';
	
	$sql = 'SELECT combat,observation,gestion,maintenance,mecanique,service,discretion,economie,resistance,tir,vol,medecine,recherche,informatique FROM principal_tbl WHERE pseudo= "'.$pseudo.'"' ;
	$req = mysql_query($sql);
	$res = mysql_num_rows($req);
	
	if($res > 0)
		{
		$combatc = mysql_result($req,0,combat);
		$observationc = mysql_result($req,0,observation);
		$gestionc = mysql_result($req,0,gestion);
		$maintenancec = mysql_result($req,0,maintenance);
		$mecaniquec = mysql_result($req,0,mecanique);
		$servicec = mysql_result($req,0,service);
		$discretionc = mysql_result($req,0,discretion);
		$economiec = mysql_result($req,0,economie);
		$resistancec = mysql_result($req,0,resistance);
		$tirc = mysql_result($req,0,tir);
		$volc = mysql_result($req,0,vol);
		$medecinec = mysql_result($req,0,medecine);
		$informatiquec = mysql_result($req,0,informatique);
		$recherchec = mysql_result($req,0,recherche);
		}
		
	if(($observationc>=100) && ($p<5))
		{
		$retour .= '<img src="im_objets/etoile.gif" border="0" title="Expert">';
		}
	if(($recherchec>=100) && ($p<5))
		{
		$retour .= '<img src="im_objets/etoile.gif" border="0" title="Expert">';
		}
	if(($informatiquec>=100) && ($p<5))
		{
		$retour .= '<img src="im_objets/etoile.gif" border="0" title="Expert">';
		}
	if(($maintenancec>=100) && ($p<5))
		{
		$retour .= '<img src="im_objets/etoile.gif" border="0" title="Expert">';
		}
	if(($gestionc>=100) && ($p<5))
		{
		$retour .= '<img src="im_objets/etoile.gif" border="0" title="Expert">';
		}
	if(($mecaniquec>=100) && ($p<5))
		{
		$retour .= '<img src="im_objets/etoile.gif" border="0" title="Expert">';
		}
	if(($discretionc>=100) && ($p<5))
		{
		$retour .= '<img src="im_objets/etoile.gif" border="0" title="Expert">';
		}
	if(($servicec>=100) && ($p<5))
		{
		$retour .= '<img src="im_objets/etoile.gif" border="0" title="Expert">';
		}
	if(($economiec>=100) && ($p<5))
		{
		$retour .= '<img src="im_objets/etoile.gif" border="0" title="Expert">';
		}
	if(($resistancec>=100) && ($p<5))
		{
		$retour .= '<img src="im_objets/etoile.gif" border="0" title="Expert">';
		}
	if(($tirc>=100) && ($p<5))
		{
		$retour .= '<img src="im_objets/etoile.gif" border="0" title="Expert">';
		}
	if(($volc>=100) && ($p<5))
		{
		$retour .= '<img src="im_objets/etoile.gif" border="0" title="Expert">';
		}
	if(($combatc>=100) && ($p<5))
		{
		$retour .= '<img src="im_objets/etoile.gif" border="0" title="Expert">';
		}
	if(($medecinec>=100) && ($p<5))
		{
		$retour .= '<img src="im_objets/etoile.gif" border="0" title="Expert">';
		}
	if(($combatc>=40) && ($p<5) && ($combatc<100))
		{
		$retour .= '<img src="im_objets/etoilebleu.gif" border="0" title="Initi&eacute;">';
		}
	if(($observationc>=40) && ($p<5) && ($observationc<100))
		{
		$retour .= '<img src="im_objets/etoilebleu.gif" border="0" title="Initi&eacute;">';
		}
	if(($gestionc>=40) && ($p<5) && ($gestionc<100))
		{
		$retour .= '<img src="im_objets/etoilebleu.gif" border="0" title="Initi&eacute;">';
		}
	if(($maintenancec>=40) && ($p<5) && ($maintenancec<100))
		{
		$retour .= '<img src="im_objets/etoilebleu.gif" border="0" title="Initi&eacute;">';
		}
	if(($servicec>=40) && ($p<5) && ($servicec<100))
		{
		$retour .= '<img src="im_objets/etoilebleu.gif" border="0" title="Initi&eacute;">';
		}
	if(($discretionc>=40) && ($p<5) && ($discretionc<100))
		{
		$retour .= '<img src="im_objets/etoilebleu.gif" border="0" title="Initi&eacute;">';
		}
	if(($economiec>=40) && ($p<5) && ($economiec<100))
		{
		$retour .= '<img src="im_objets/etoilebleu.gif" border="0" title="Initi&eacute;">';
		}
	if(($resistancec>=40) && ($p<5) && ($resistancec<100))
		{
		$retour .= '<img src="im_objets/etoilebleu.gif" border="0" title="Initi&eacute;">';
		}
	if(($tirc>=40) && ($p<5) && ($tirc<100))
		{
		$retour .= '<img src="im_objets/etoilebleu.gif" border="0" title="Initi&eacute;">';
		}
	if(($volc>=40) && ($p<5) && ($volc<100))
		{
		$retour .= '<img src="im_objets/etoilebleu.gif" border="0" title="Initi&eacute;">';
		}
	if(($medecinec>=40) && ($p<5) && ($medecinec<100))
		{
		$retour .= '<img src="im_objets/etoilebleu.gif" border="0" title="Initi&eacute;">';
		}
	if(($recherchec>=40) && ($p<5) && ($recherchec<100))
		{
		$retour .= '<img src="im_objets/etoilebleu.gif" border="0" title="Initi&eacute;">';
		}
	if(($informatiquec>=40) && ($p<5) && ($informatiquec<100))
		{
		$retour .= '<img src="im_objets/etoilebleu.gif" border="0" title="Initi&eacute;">';
		}
	if(($mecaniquec>=40) && ($p<5) && ($mecaniquec<100))
		{
		$retour .= '<img src="im_objets/etoilebleu.gif" border="0" title="Initi&eacute;">';
		}
	
	return $retour;
}

function affiche_box_lien($lien,$titre,$contenu) {
	print('<a href="#" id="affiche_au_clic">'.$lien.'</a>
		<div id="boite_affiche_au_clic" style="z-index:10000;display:none;position:relative;top:60px;height:252px;">
			<h1 style="position:absolute;left:0;top:-50px;font-size:30px;font-family:Georgia,Verdana,sans-serif;">'.$titre.'</h1>
			'.$contenu.'
			<br />
			<br /><a href="#" style="position:absolute;bottom:0;right:0;display:block;color:#adadae;border:1px solid #666;padding:0 5px 6px 5px;font-size:18px;" class="simplemodal-close">x</a>
		</div>');
}

function affiche_box($titre,$contenu) {
	print('
		<script type="text/javascript">
			$(document).ready(function(){
				$(\'#boite_affiche\').modal({onOpen: modalOpen, onClose: modalClose});
			});
		</script>
		<div id="boite_affiche" style="z-index:10000;display:none;position:relative;top:60px;height:252px;">
			<h1 style="position:absolute;left:0;top:-50px;font-size:30px;font-family:Georgia,Verdana,sans-serif;">'.$titre.'</h1>
			'.$contenu.'
			<br />
			<br /><a href="#" style="position:absolute;bottom:0;right:0;display:block;color:#adadae;border:1px solid #666;padding:0 5px 6px 5px;font-size:18px;" class="simplemodal-close">x</a>
		</div>');
}

function affiche_box_tuto() {
	print('
		<script type="text/javascript">
			$(document).ready(function(){
				$(\'#boite_affiche\').modal({onOpen: modalOpen, onClose: modalClose});
			});
		</script>
		<div id="boite_affiche" style="z-index:10000;display:none;position:relative;top:60px;height:252px;">
			<h1 style="width:560px;position:absolute;left:0;top:-50px;font-size:30px;font-family:Georgia,Verdana,sans-serif;text-align:center;">Annonce de l\'Impérium</h1>
			<div id="tuto_menu">
				<div id="tuto_1" onclick="$(\'#tuto_contenu div\').each(function(){$(this).fadeOut();});$(\'#contenu_1\').fadeIn();$(\'#contenu_1 div\').fadeIn();">La Ville</div>
				<div id="tuto_2" style="display:none;" onclick="$(\'#tuto_contenu div\').each(function(){$(this).fadeOut();});$(\'#contenu_2\').fadeIn();$(\'#contenu_2 div\').fadeIn();">Expérience</div>
				<div id="tuto_3" style="display:none;" onclick="$(\'#tuto_contenu div\').each(function(){$(this).fadeOut();});$(\'#contenu_3\').fadeIn();$(\'#contenu_3 div\').fadeIn();">Accomplissements</div>
				<div id="tuto_4" style="display:none;" onclick="$(\'#tuto_contenu div\').each(function(){$(this).fadeOut();});$(\'#contenu_4\').fadeIn();$(\'#contenu_4 div\').fadeIn();">Compétences</div>
				<div id="tuto_5" style="display:none;" onclick="$(\'#tuto_contenu div\').each(function(){$(this).fadeOut();});$(\'#contenu_5\').fadeIn();$(\'#contenu_5 div\').fadeIn();">Cercles</div>
				<div id="tuto_6" style="display:none;" onclick="$(\'#tuto_contenu div\').each(function(){$(this).fadeOut();});$(\'#contenu_6\').fadeIn();$(\'#contenu_6 div\').fadeIn();">Objets et crédits</div>
				<div id="tuto_7" style="display:none;" onclick="$(\'#tuto_contenu div\').each(function(){$(this).fadeOut();});$(\'#contenu_7\').fadeIn();$(\'#contenu_7 div\').fadeIn();">Travail</div>
				<div id="tuto_8" style="display:none;" onclick="$(\'#tuto_contenu div\').each(function(){$(this).fadeOut();});$(\'#contenu_8\').fadeIn();$(\'#contenu_8 div\').fadeIn();">Logement</div>
				<div id="tuto_9" style="display:none;" onclick="$(\'#tuto_contenu div\').each(function(){$(this).fadeOut();});$(\'#contenu_9\').fadeIn();$(\'#contenu_9 div\').fadeIn();">Commencement</div>
			</div>
			<div id="tuto_contenu">
				<div id="contenu_1" style="position:absolute;left:0;top:0;">
					<div class="imageTuto"><img src="im_objets/imtuto1.gif" /></div>
					Bienvenue au sein de la puissante cité de Dreadcast.<br /><br />Vous êtes dès à présent <strong>Citoyen de la Ville</strong>. Vous devez allégeance à l\'Impérium, et servir la cité est votre priorité.<br />Vous pouvez circuler librement parmi les boulevards et les ruelles de DreadCast. De nombreux magasins et institutions, animés par les citoyens, sont accessibles depuis la rue.<br />Chacun d\'entre eux possède son utilité, à vous de les découvrir.
				</div>
				<div id="contenu_2" style="display:none;position:absolute;left:0;top:0;">
					<div class="imageTuto"><img src="im_objets/imtuto2.gif" /></div>
					En tant que rouage de notre grande cité, votre développement personnel nous est très important.<br /><br /><strong>L\'expérience</strong> que vous acquerrez tout au long de votre vie vous aidera à atteindre cet épanouissement.<br />Gagner de l\'expérience vous permettra de monter de niveau, et de développer un <strong>Talent</strong>.<br /><br />Il existe de nombreux moyen d\'acquérir de l\'expérience. A vous de les découvrir.
				</div>
				<div id="contenu_3" style="display:none;position:absolute;left:0;top:0;">
					<div class="imageTuto"><img src="im_objets/imtuto3.gif" /></div>
					Dreadcast est une cité où tout est possible. Et tant que vous n\'aurez pas trouvé votre voie propre, une aide à l\'accomplissement vous sera apportée.<br /><br />Les <strong>Accomplissements</strong> sont des courtes missions qui vous guideront à travers les possibilités qu\'offrent la cité, en fonction de ce que vous souhaitez y faire. <br /><br />Ils récompenseront vos efforts en vous octroyant de l\'expérience, et un titre symbolique attestant de l\'épreuve que vous avez passée.
				</div>
				<div id="contenu_4" style="display:none;position:absolute;left:0;top:0;">
					<div class="imageTuto"><img src="im_objets/imtuto4.gif" /></div>
					Les <strong>compétences</strong> mesurent vos capacités. Elles vous indiquent à quel degré vous maîtrisez un domaine.<br /><br />Il existe 14 compétences différentes, allant de Combat à Informatique, et chacune possédant sa spécificité et son utilité particulière.<br /><br />Il n\'y a pas de secret, les compétences se développent par la pratique ! A vous de voir lesquelles vous seront les plus utiles.
				</div>
				<div id="contenu_5" style="display:none;position:absolute;left:0;top:0;">
					<div class="imageTuto"><img src="im_objets/imtuto5.gif" /></div>
					Dreadcast est rempli de citoyens qui, à un instant de leur vie, ont décidé de se rassembler autour d\'un même objectif et d\'une même passion.<br />Ils créèrent alors les <strong>Cercles</strong>, pour se réunir, partager leur expérience et travailler dans un même effort.<br /><br />Cercles politiques ou cercles associatifs, beaucoup pourront vous être utiles tout au long de votre vie à Dreadcast. N\'hésitez donc pas à aller frapper à leur porte, ou, pourquoi pas, à construire le votre.
				</div>
				<div id="contenu_6" style="display:none;position:absolute;left:0;top:0;">
					<div class="imageTuto"><img src="im_objets/imtuto6.gif" /></div>
					Cette cité est régie par l\'argent. Les <strong>Crédits</strong> en sont la monnaie officielle, et s\'obtiennent en travaillant, ou en exerçant d\'autres activités moins licites.<br /><br />Posséder des crédits vous permettra de vous fournir en <strong>Objets</strong> de toute sorte, que ce soient des armes ou armures, de l\'alimentation, des objets de soutien ou tout autre artefact utile à votre bien-être.<br /><br />Toutefois, n\'oubliez pas que la possession attire la convoitise de gens malhonnêtes...
				</div>
				<div id="contenu_7" style="display:none;position:absolute;left:0;top:0;">
					<div class="imageTuto"><img src="im_objets/imtuto7.gif" /></div>
					Comme vous l\'avez sans doute compris, votre survie à Dreadcast dépend de votre capacité à obtenir des crédits. Le <strong>travail</strong> est alors une étape obligatoire à tout nouveau citoyen.<br /><br />Le Centre d\'Information Pour l\'Emploi est le lieu où sont centralisées toutes les offres d\'emploi, qualifiées ou non.<br /><br />C\'est une étape recommandée pour s\'installer dans la cité. N\'hésitez pas à prendre le travail le plus basique, le temps de stabiliser votre situation !
				</div>
				<div id="contenu_8" style="display:none;position:absolute;left:0;top:0;">
					<div class="imageTuto"><img src="im_objets/imtuto8.gif" /></div>
					Cependant, le travail fatigue, et les rues ne sont pas sûres pour se reposer.<br />C\'est pourquoi obtenir rapidement un <strong>logement</strong> est grandement recommandé pour bien débuter l\'aventure.<br /><br />Dans les agences immobilières, de nombreuses offres de location/vente vous seront proposées, de qualité diversifiée à prix diversifiés.<br /><br />Vous pourrez ensuite améliorer votre logement de différentes manières !
				</div>
				<div id="contenu_9" style="display:none;position:absolute;left:0;top:0;">
					<div class="imageTuto"><img src="im_objets/imtuto9.gif" /></div>
					Vous voilà au seuil de la cité.<br />Lors de votre vie à Dreadcast, certains réflexes vous seront souvent salvateurs.<br /><strong>Fiez-vous à la communauté</strong>. Les autres citoyens pourront sûrement répondre à vos questions.<br /><strong>Pensez à définir judicieusement vos réactions.</strong> Elles vous seront souvent d\'une grande aide.<br /><strong>Evitez autant que possible la rue et ses dangers.</strong><br />Vous bénéficiez d\'une immunité jusqu\'à votre premier déplacement. Au-delà, prenez garde.<strong style="display:block;text-align:center;">Bienvenue à Dreadcast.</strong>
				</div>
			</div>
			<br />
			<br />
			<div id="tuto_lien1" onclick="$(\'#tuto_2\').fadeIn();$(\'#contenu_1\').fadeOut();$(\'#contenu_2\').fadeIn();$(\'#tuto_lien1\').hide();$(\'#tuto_lien2\').show();" style="z-index:345;position:absolute;bottom:0;right:0;color:#adadae;padding:0 5px 6px 5px;font-size:12px;cursor:pointer;">Suite</div>
			<div id="tuto_lien2" onclick="$(\'#tuto_3\').fadeIn();$(\'#contenu_2\').fadeOut();$(\'#contenu_3\').fadeIn();$(\'#tuto_lien2\').hide();$(\'#tuto_lien3\').show();" style="z-index:345;position:absolute;bottom:0;right:0;color:#adadae;padding:0 5px 6px 5px;font-size:12px;display:none;cursor:pointer;">Suite</div>
			<div id="tuto_lien3" onclick="$(\'#tuto_4\').fadeIn();$(\'#contenu_3\').fadeOut();$(\'#contenu_4\').fadeIn();$(\'#tuto_lien3\').hide();$(\'#tuto_lien4\').show();" style="z-index:345;position:absolute;bottom:0;right:0;color:#adadae;padding:0 5px 6px 5px;font-size:12px;display:none;cursor:pointer;">Suite</div>
			<div id="tuto_lien4" onclick="$(\'#tuto_5\').fadeIn();$(\'#contenu_4\').fadeOut();$(\'#contenu_5\').fadeIn();$(\'#tuto_lien4\').hide();$(\'#tuto_lien5\').show();" style="z-index:345;position:absolute;bottom:0;right:0;color:#adadae;padding:0 5px 6px 5px;font-size:12px;display:none;cursor:pointer;">Suite</div>
			<div id="tuto_lien5" onclick="$(\'#tuto_6\').fadeIn();$(\'#contenu_5\').fadeOut();$(\'#contenu_6\').fadeIn();$(\'#tuto_lien5\').hide();$(\'#tuto_lien6\').show();" style="z-index:345;position:absolute;bottom:0;right:0;color:#adadae;padding:0 5px 6px 5px;font-size:12px;display:none;cursor:pointer;">Suite</div>
			<div id="tuto_lien6" onclick="$(\'#tuto_7\').fadeIn();$(\'#contenu_6\').fadeOut();$(\'#contenu_7\').fadeIn();$(\'#tuto_lien6\').hide();$(\'#tuto_lien7\').show();" style="z-index:345;position:absolute;bottom:0;right:0;color:#adadae;padding:0 5px 6px 5px;font-size:12px;display:none;cursor:pointer;">Suite</div>
			<div id="tuto_lien7" onclick="$(\'#tuto_8\').fadeIn();$(\'#contenu_7\').fadeOut();$(\'#contenu_8\').fadeIn();$(\'#tuto_lien7\').hide();$(\'#tuto_lien8\').show();" style="z-index:345;position:absolute;bottom:0;right:0;color:#adadae;padding:0 5px 6px 5px;font-size:12px;display:none;cursor:pointer;">Suite</div>
			<div id="tuto_lien8" onclick="$(\'#tuto_9\').fadeIn();$(\'#contenu_8\').fadeOut();$(\'#contenu_9\').fadeIn();$(\'#tuto_lien8\').hide();$(\'#tuto_lien9\').show();" style="z-index:345;position:absolute;bottom:0;right:0;color:#adadae;padding:0 5px 6px 5px;font-size:12px;display:none;cursor:pointer;">Suite</div>
			<div class="simplemodal-close" id="tuto_lien9" style="z-index:345;position:absolute;bottom:0;right:0;color:#adadae;padding:0 5px 6px 5px;font-size:12px;display:none;cursor:pointer;">Vers la cité</div>
			<!--<a href="#" style="position:absolute;bottom:0;right:0;display:block;color:#adadae;padding:0 5px 6px 5px;font-size:12px;" class="simplemodal-close">Fermer le tutorial</a>-->
		</div>');
}

?>
