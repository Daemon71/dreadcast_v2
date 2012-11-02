<?php 
session_start(); 
if($_SESSION['id']=="")
	{
	print('<meta http-equiv="refresh" content="0 ; url=http://v2.dreadcast.net/"> ');
	exit();
	}
if($_SESSION['action']=="Vacances")
	{
	print('<meta http-equiv="refresh" content="0 ; url=http://v2.dreadcast.net/ingame/engine=cryo.php"> ');
	exit();
	}
if($_SESSION['action']=="mort")
	{
	print('<meta http-equiv="refresh" content="0 ; url=http://v2.dreadcast.net/ingame/engine=mort.php"> ');
	exit();
	}

?>
<?php if ((date("H")>=21) || (date("H")<=7)) { include("inc_haut_n.php"); } else { include("inc_haut_j.php");} ?>

<div id="haut">
	<?php include("inc_entete.php"); ?>
	<div class="haut_aligngauche">
		<p>
		<div class="titrepage">
			Accomplissements
		</div>
		<b class="module4ie"><a href="engine.php" class="module4"><img src="im_objets/icon_retour.gif" alt="Retour" />Retour</a></b>
		</p>
	</div>
</div>
<div id="centre_accomplissements">
	<div id="tmp">
	<?php 
	
		if($_GET['p']!="" && statut($_SESSION['statut']) >= 7) $pseudo = $_GET['p'];
		else $pseudo = $_SESSION['pseudo'];
		
		$db = mysql_connect('localhost', 'CENSURE', 'CENSURE') or die("Erreur de connexion au serveur des messages.");
		mysql_select_db('CENSURE',$db) or die("Erreur de connexion a la base de donnees des messages.");

		$sql = 'SELECT titre FROM titres_tbl WHERE pseudo= "'.$pseudo.'" AND type="Accomplissement"' ;
		$req = mysql_query($sql);
		$res = mysql_num_rows($req);
		for($i=0;$i<$res;$i++) $titre[strtolower(mysql_result($req,$i,titre))] = true;
		
		$acc = get_accomplissements();
		
		if($_GET['acc'])
			{
			$_GET['acc'] = stripslashes($_GET['acc']);
			$verif = verif_accomplissement(strtolower($_GET['acc']));
			if($verif[0] && !possede_talent(strtolower($_GET['acc']))) {
				$bonus = donner_accomplissement(strtolower($_GET['acc']));
				print('<div style="background:#333;border:1px solid #777;font-size:13px;padding:10px;margin:0 40px 10px 40px;color:#fff;">
				Félicitation, vous avez obtenu le titre "<strong>'.$_GET['acc'].'</strong>".<br />
				Vous venez d\'obtenir le bonus suivant :<br /><br /><strong>'.$bonus.'</strong><br /><br />
				Les modifications seront effectives au prochain chargement de la page.
				</div>');
				
				$acc = get_accomplissements();
				$titre[strtolower($_GET['acc'])] = true;
				}
			else print('<div style="background:#333;border:1px solid #777;font-size:13px;padding:10px;margin:0 40px 10px 40px;color:#fff;">
				N\'essayez pas de tricher...
			</div>');
			}
		
		for($i=0;$i<count($acc);$i++)
			{
			print('<div id="c'.$i.'" class="categorie" onclick="if($(\'#c'.$i.' .accomplissement\').css(\'display\')==\'none\') { $(\'.accomplissement\').slideUp(\'fast\');$(\'.description\').slideUp(\'fast\');$(\'.nav1\').css(\'background-position\',\'0 0px\');$(\'#c'.$i.' .accomplissement\').slideDown(\'fast\');$(\'#c'.$i.' .nav1\').css(\'background-position\',\'-17px 0\'); }">
					<h2 class="titre"><a href="#" class="nav1" onclick="$(\'#c'.$i.' .accomplissement\').slideUp(\'fast\');$(\'#c'.$i.' .accomplissement .description\').slideUp(\'fast\');$(\'.description\').slideUp(\'fast\');$(\'#c'.$i.' .nav1\').css(\'background-position\',\'0 0\');$(\'#c'.$i.' .accomplissement .nav2\').css(\'background-position\',\'0 -17px\');"></a> '.$acc[$i][0].'</h2>');
				
			print('<table class="accomplissement" style="display:none;">');
			
			$newline = 0;
			$nbr_arr = 0;
			
			$descriptions = "";
			$ancien_acc = "";
			
			$j = 1;
			
			while($j)
				{
				$actuel = $acc[$i][$j];
				
				if($newline++ == 0) print('<tr>');
				
				if(!$nbr_arr)
					{
					
					$obt="";
					if($ancien_acc!="" && !$titre[strtolower($ancien_acc)]) $obt = 'style="display:none;"';
					elseif(!$titre[strtolower($actuel[1])]) $obt = 'class="non_obt"';
					
					//if(strtolower($actuel[1])=="citoyen d'honneur" && !$titre[strtolower($actuel[1])]) $obt = 'style="display:none;"';
					
					print('<td>
						<div '.$obt.' onclick="if($(\'#d'.$i.'d'.$j.'\').css(\'display\') == \'none\') { $(\'#c'.$i.' .description\').slideUp(\'fast\');$(\'#d'.$i.'d'.$j.'\').slideDown(\'fast\');}else $(\'#c'.$i.' .description\').slideUp(\'fast\');" style="position:relative;padding:10px;">
							'.(($actuel[0]!=0 && strtolower($actuel[1])!="citoyen privilégié")?'<img style="position:absolute;right:-20px;top:'.((count($actuel[0])==1)?'13px':'4px').';" src="im_objets/fleche_acc_1'.count($actuel[0]).'.gif" alt="" />':''));
							
							$descriptions = affiche_accomplissement($i,$j,$actuel,$descriptions,$titre);

					print('</div>
						</td>');
					}
				else
					{
					print('<td><table>');
					for($k=0;$k<$nbr_arr;$k++)
						{
						$l = $j+$k;
						
						$obt="";
						if($ancien_acc!="" && !$titre[strtolower($ancien_acc)]) $obt = 'style="display:none;"';
						elseif(!$titre[strtolower($acc[$i][$l][1])]) $obt = 'class="non_obt"';
						else {
							$ancien_acc = $acc[$i][$l][1];
							$saut = true;
							}
						
						print('<tr>
							<td>
								<div '.$obt.' onclick="if($(\'#d'.$i.'d'.$l.'\').css(\'display\') == \'none\') { $(\'#c'.$i.' .description\').slideUp(\'fast\');$(\'#d'.$i.'d'.$l.'\').slideDown(\'fast\');}else $(\'#c'.$i.' .description\').slideUp(\'fast\');" style="position:relative;padding:10px;">
								'.(($k==$nbr_arr-1 && $actuel[0]!=0)?'<img style="position:absolute;right:-20px;top:-32px;" src="im_objets/fleche_acc_2'.count($actuel[0]).'.gif" alt="" />':''));
							
							$descriptions = affiche_accomplissement($i,$l,$acc[$i][$l],$descriptions,$titre);
							
							print('</div>
							</td>
						</tr>');
						}
					print('</table></td>');
					}
				
				if(!is_array($actuel[0]) && $actuel[0] == 0)
					{
					print('</tr>');
					$newline = 0;
					}
				
				if($j==1 && !$titre['nouvel arrivant']) break;
				//if($j==2 && !$titre['citoyen privilégié']) break;
				
				if(!$saut) $ancien_acc = $actuel[1];
				$saut = false;
				
				if(is_array($actuel[0]))
					{
					$nbr_arr = count($actuel[0]);
					$j = $actuel[0][0];
					}
				else
					{
					$nbr_arr = 0;
					if(!$actuel[0] && $acc[$i][($j+1)] != "" && $acc[$i][($j+1)][0]) {
						$j++;
						$ancien_acc = "";
					}
					else $j = $actuel[0];
					}
				
				}
			print('</table>
			
				'.$descriptions.'
			
			</div>');
			
			if(!$titre['nouvel arrivant']) break;
			}
		
			
		mysql_close($db);
		
		function get_accomplissements() {
			$acc[0][0] = "Citoyenneté";
			$acc[0][1] = array(2,'Nouvel Arrivant','Vous venez d\'arriver dans la cité, et commencez tout juste à prendre vos marques.<br />Quelques actions de base vous aideront grandement à survivre dans cet univers inhospitalier.','Trouvez un emploi','Trouvez un logement','Envoyez au moins un message privé');
			$acc[0][2] = array(3,'Citoyen Privilégié','A votre arrivée dans la ville, un compte amélioré vous est proposé gratuitement : le compte Silver.<br /><br />Les comptes améliorés permettent de découvrir le jeu d\'une manière plus confortable et approfondie, et permettent au site de survivre.','Prenez un compte amélioré');
			if(possede_talent("citoyen d'honneur")) $acc[0][3] = array(0,'Citoyen d\'Honneur','Vous contribuez à améliorer le jeu en aidant les administrateurs.','Accomplissement reçu d\'un administrateur');
			else $acc[0][3] = array(0,'?','?','?');
			
			$acc[1][0] = "Travail";
			$acc[1][1] = array(array(2,3),'Bourreau de travail','Dans cette ville, avoir une activité professionnelle est signe de santé financière. Attention toutefois de ne pas se tuer à la tâche.','Atteindre le niveau 3','100 heures de travail standard');
			$acc[1][2] = array(4,'Fidèle à son poste','Votre entreprise, c\'est sacré. Vous avez un objectif de carrière et vous vous y tenez !<br /><br /><span style="font-weight:bold;color:#60A9ED;">ATTENTION</span> : En validant ce titre, vous ne pourrez plus obtenir le titre "<strong style="display:inline;color:#fff;font-size:12px;">Polyvalent du travail</strong>".','Atteindre le niveau 5','200 heures de travail standard','Maximum 2 emplois différents');
			$acc[1][3] = array(4,'Polyvalent du travail','Touche à tout, vous aimez diversifier vos expériences professionnelles. Vous êtes prêt à tout !<br /><br /><span style="font-weight:bold;color:#60A9ED;">ATTENTION</span> : En validant ce titre, vous ne pourrez plus obtenir le titre "<strong style="display:inline;color:#fff;font-size:12px;">Fidèle à son poste</strong>".','Atteindre le niveau 5','200 heures de travail standard','Minimum 5 emplois différents');
			$acc[1][4] = array(0,'Fanatique ambitieux','Vous êtes un acharné du travail. Près de vous, le temps s\'écoule en toute relativité.','Etre présent en ville depuis 1 mois','500 heures supp\'');
			$acc[1][5] = array(6,'Bosseur actif','Vous aimez agir, quoi qu\'il en coûte : la ville a besoin de vous.','Atteindre le niveau 3','10 jours de travail actif');
			$acc[1][6] = array(7,'Actif de la ville','Vous bougez constamment, mais vous savez aussi vous entourer. La communication, ça vous connaît !','Atteindre le niveau 5','50 MPs professionnels envoyés');
			$acc[1][7] = array(0,'Ciment de la cité','Vous êtes ancré dans la ville, qui vit en partie de votre activité et du mouvement que vous générez.','Etre présent en ville depuis 1 mois','Présent depuis 10 ans');
			$acc[1][8] = array(9,'Gérant émérite','L\'entrepreneuriat vous a toujours tenté. Ca y est, vous avez décidé de franchir le pas !','Atteindre le niveau 3','1 entreprise créée','10 jours de travail actif');
			$acc[1][9] = array(10,'Gros bonnet','Vous commencez sérieusement à vous faire une place parmi les bonnes entreprises qui font fonctionner l\'économie.','Atteindre le niveau 5','25000 Cr de CA cumulé');
			$acc[1][10] = array(0,'Génie economiste','Vous êtes un visionnaire de la gestion d\'entreprise. Vos actions influencent à haut niveau l\'économie de la cité.','Etre présent en ville depuis 1 mois','10 salariés','5000 Cr de CA journalier','1 poste de directeur occupé');
			
			$acc[2][0] = "Alignement";
			$acc[2][1] = array(2,'Petite frappe','Vous faites vos armes sur les passants qui vous regardent de travers. Le respect est une valeur qu\'on vous doit, vous l\'imposez par vos poings.<br /><br /><span style="font-weight:bold;color:#60A9ED;">ATTENTION</span> : En validant ce titre, vous ne pourrez plus obtenir le titre "<strong style="display:inline;color:#fff;font-size:12px;">Espoir de la cité</strong>".','Atteindre le niveau 3','Premiers dégâts donnés');
			$acc[2][2] = array(3,'Danger public','Vous vous hissez dans le grand banditisme en marchant sur le cadavre des autres. Ils n\'avaient pas qu\'à être sur votre chemin.','Atteindre le niveau 5','5 meurtres');
			$acc[2][3] = array(0,'Ennemi de l\'Impérium','L\'Impérium est une menace pour votre activité : vous savez tailler dans le vif lorsque nécessaire. Votre nom impose la crainte et le respect.','Etre présent en ville depuis 1 mois','50 meurtres');
			$acc[2][4] = array(5,'Espoir de la cité','Votre volonté d\'aider l\'Impérium est palpable. Vous dégagez une énergie positive qui motive les autres.<br /><br /><span style="font-weight:bold;color:#60A9ED;">ATTENTION</span> : En validant ce titre, vous ne pourrez plus obtenir le titre "<strong style="display:inline;color:#fff;font-size:12px;">Petite frappe</strong>".','Atteindre le niveau 3','10 jours de travail actif en OI');
			$acc[2][5] = array(array(6,7),'Fervent Impérialiste','Vous vous faites une place dans la hierarchie, et votre dynamisme est reconnu de tous.','Atteindre le niveau 5','50 jours de travail actif en OI');
			$acc[2][6] = array(0,'Haut fonctionnaire','Vous avez des projets, une vision de l\'Impérium, et savez la partager. Imposer vos idées vous mène haut et les gens vous respectent pour ça. Grâce à vous, c\'est toute la cité qui évolue.<br /><br /><span style="font-weight:bold;color:#60A9ED;">ATTENTION</span> : En validant ce titre, vous ne pourrez plus obtenir le titre "<strong style="display:inline;color:#fff;font-size:12px;">Arme de l\'Impérium</strong>".','Etre présent en ville depuis 1 mois','Membre du Conseil ou Directeur Impérial');
			$acc[2][7] = array(0,'Arme de l\'Impérium','"Servir et agir", "Pour le bien de l\'Impérium", telles sont vos répliques favorites. Vous êtes redoutés des criminels et votre loyauté vous fait honneur.<br /><br /><span style="font-weight:bold;color:#60A9ED;">ATTENTION</span> : En validant ce titre, vous ne pourrez plus obtenir le titre "<strong style="display:inline;color:#fff;font-size:12px;">Haut fonctionnaire</strong>".','Etre présent en ville depuis 1 mois','100 arrestations de personnes recherchées');
			
			$acc[3][0] = "Âme";
			$acc[3][1] = array(2,'Bonne âme','L\'âme généreuse de nature, vous aidez ceux qui sont dans le besoin en leur apportant réconfort et secours.<br /><br /><span style="font-weight:bold;color:#60A9ED;">ATTENTION</span> : En validant ce titre, vous ne pourrez plus obtenir le titre "<strong style="display:inline;color:#fff;font-size:12px;">Apprenti picpocket</strong>"','Atteindre le niveau 3','Premier don','Premiers soins');
			$acc[3][2] = array(3,'Altruiste','Vous êtes constamment tournés vers les autres, et ils vous en sont reconnaissants.','Atteindre le niveau 5','15 dons','15 soins','Protéger quelqu\'un');
			$acc[3][3] = array(0,'Philanthrope','Vous avez dédié votre vie à aider les habitants de la cité, et représentez une icône de bienveillance pour tous les citoyens.','Etre présent en ville depuis 1 mois','100 dons','100 soins','30 protections');
			$acc[3][4] = array(5,'Apprenti picpocket','La survie, ça vous connaît. Et s\'il faut choisir entre vous et les autres, vous n\'avez aucun scrupule à vous remplir les poches.<br /><br /><span style="font-weight:bold;color:#60A9ED;">ATTENTION</span> : En validant ce titre, vous ne pourrez plus obtenir le titre "<strong style="display:inline;color:#fff;font-size:12px;">Bonne âme</strong>"','Atteindre le niveau 3','1 en vol');
			$acc[3][5] = array(6,'Escroc','Votre petit business marche pas mal, vos mains de fée commencent à être connues dans le milieu.','Atteindre le niveau 5','20 vols réussis');
			$acc[3][6] = array(0,'Arnaqueur','Vous avez écumé toutes les poches de la ville, et votre fortune s\'est faite sur le dos des autres. Ceux qui vous connaissent bien changent de trottoir en vous croisant.','Etre présent en ville depuis 1 mois','100 vols réussis','Casier non vierge');
			
			$acc[4][0] = "Cercles";
			$acc[4][1] = array(2,'Militant','Vous avez trouvé un cercle associatif ou politique qui vous convient. Vous avez fait de son combat le votre.','Atteindre le niveau 3','Présent dans un cercle');
			$acc[4][2] = array(3,'Activiste','Vous soutenez une cause qui vous est chère et aidez les dirigeants de votre cercle.','Atteindre le niveau 5','1000 Cr de dons à un cercle','5 commentaires sur l\'actualité');
			$acc[4][3] = array(0,'Leader charismatique','Vous avez créé votre propre cause et la défendez hardiment. Vous rayonnez, et de nombreuses personnes vous ont déjà rejoint.','Etre présent en ville depuis 1 mois','Dirigeant de cercle','5 actualité dans le cercle','15 adhérents');

			$acc[5][0] = "Logements";
			$acc[5][1] = array(2,'Propriétaire terrien','Ca y est, c\'est le grand jour ! Vous devenez propriétaire de votre propre logement ! Ni trop petit, ni trop grand, il est p-a-r-f-a-i-t.','Atteindre le niveau 3','Un logement acheté');
			$acc[5][2] = array(3,'Bailleur','Vos quelques logements font déjà de vous une personne influente et respectée.','Atteindre le niveau 5','5 logement acheté');
			$acc[5][3] = array(0,'Fortune immobilière','Le temps des petites affaires est loin derrière vous. Votre parc immobilier en dit long sur votre fortune personnelle.','Etre présent en ville depuis 1 mois','20 locataires');

			$acc[6][0] = "Statistiques";
			$acc[6][1] = array(2,'Initié','Vous n\'êtes plus un débutant. Votre talent dans un domaine vous conduit chaque jour à agir efficacement.','Atteindre le niveau 3','Initié dans n\'importe quelle compétence');
			$acc[6][2] = array(3,'Expert','C\'est en connaissance de cause que vous agissez. Un domaine vous passionne, ça se voit !','Atteindre le niveau 5','Expert dans n\'importe quelle compétence');
			$acc[6][3] = array(0,'Spécialiste','Votre professionnalisme dans un domaine vous permet de toucher l\'excellence.','Etre présent en ville depuis 1 mois','Spécialiste dans n\'importe quelle compétence');
			
			$acc[7][0] = "Recrutement";
			$acc[7][1] = array(2,'Promeneur bavard','Vous aimez bien, en vous aventurant hors de la cité, discuter avec les autochtones pour leurs conter les magnificences de DreadCast.','Parrainer 3 citoyens');
			$acc[7][2] = array(3,'Crieur de rue','Vous avez une mission : propager la lumière aux âmes égarées, et leur ouvrir les yeux sur notre monde. Et vous vous débrouillez plutôt bien !','Parrainer 10 citoyens', '...dont 5 ont atteint le niveau 3', '...et 1 a un compte amélioré (sauf Silver)');
			$acc[7][3] = array(0,'Recruteur Impérial','Convaincre, c\'est votre crédo ! Une légende circule comme quoi vous pouvez faire faire n\'importe quoi au gens rien qu\'en croisant leur regard. He bah !','Parrainer 30 citoyens', '...dont 10 ont atteint le niveau 6', '...et 3 ont un compte amélioré (sauf Silver)');
			
			$acc[8][0] = "Events";
			$acc[8][1] = array(0,'Fêtard des 2 ans','Toujours partant pour faire la fête ! En tout cas, pour les 2 ans... vous étiez là !','Aller faire la fête pour les 2 bougies de Dreadcast');
			$acc[8][2] = array(3,'Mécanicien SAV','Un tas de boulon vous a trop tapé sur le système...','Infliger des dégâts à un droïde fou');
			$acc[8][3] = array(4,'Réparateur','Vous savez remonter des pièces détachées.','Réparer un droïde fou');
			$acc[8][4] = array(0,'Sauveur de droïde','Vous n\'avez pas foi en l\'avenir organique.','Réparer cinq droïdes fous');
			$acc[8][5] = array(6,'Machine rutilante','Tout beau tout neuf !','Avoir été sauvé du virus D');
			$acc[8][6] = array(0,'A venir','A venir','-');
			$acc[8][7] = array(8,'Chair à canon','Vous n\'avez peur de rien.','Infliger des dégâts à Paracelse');
			$acc[8][8] = array(9,'Revendeur de composants','Quand vous frappez, les puces électroniques giclent.','Infliger 2000 dégâts à Paracelse');
			$acc[8][9] = array(0,'L\'anti-droïde','Vous avez tué le super-droïde Paracelse !','Achever Paracelse');
			
			return $acc;
		}
		
		function affiche_accomplissement($i,$j,$actuel,$descriptions,$titre) {
			$possede_titre = $titre[strtolower($actuel[1])];
			
			$infos = verif_accomplissement(strtolower($actuel[1]));
			
			print($actuel[1]);
			
			$descriptions .= '<div id="d'.$i.'d'.$j.'" class="description" style="display:none;position:relative;">
					'.$actuel[2].'<br />
					<strong>Actions nécessaires</strong>
					<ul>';
						for($k=3;$k<count($actuel);$k++) $descriptions .= '<li><div style="background:url(im_objets/iconvalid.gif) '.(($infos[($k-2)] || $possede_titre)?-21:0).'px 0 no-repeat;"></div> '.$actuel[$k].'</li>';
					$descriptions .= '</ul>
					'.(($infos[0] && !$possede_titre)?'<a href="engine=accomplissements.php?acc='.$actuel[1].'" class="valider"></a>
					<a href="engine=accomplissements.php?acc='.$actuel[1].'" class="valider2">Valider</a>':'').'
				</div>';
			
			return $descriptions;
		}
		
		function donner_accomplissement($acc,$pseudo="") {
		
			if($pseudo=="") $pseudo = $_SESSION['pseudo'];
			
			$sql = 'INSERT INTO titres_tbl(pseudo,titre,type) VALUES("'.$pseudo.'","'.$acc.'","Accomplissement")';
			mysql_query($sql);
			
			if($acc=="nouvel arrivant") {
				$xp = 10;
				if(possede_talent('Génie')) $xp += floor(0.1*$xp);
				$sql = 'UPDATE principal_tbl SET total = total + '.$xp.' WHERE pseudo="'.$pseudo.'"';
				$req = mysql_query($sql);
				return $xp." points d'expérience";
			} elseif($acc=="citoyen privilégié") {
				$xp = 10;
				if(possede_talent('Génie')) $xp += floor(0.1*$xp);
				$sql = 'UPDATE principal_tbl SET total = total + '.$xp.' WHERE pseudo="'.$pseudo.'"';
				$req = mysql_query($sql);
				return $xp." points d'expérience";
			} elseif($acc=="bourreau de travail") {
				$xp = 100;
				if(possede_talent('Génie')) $xp += 	0.1*$xp;
				$sql = 'UPDATE principal_tbl SET total = total + '.$xp.' WHERE pseudo="'.$pseudo.'"';
				$req = mysql_query($sql);
				return $xp." points d'expérience";
			} elseif($acc=="fidèle à son poste") {
				$xp = 100000;
				if(possede_talent('Génie')) $xp += floor(0.1*$xp);
				$sql = 'UPDATE principal_tbl SET total = total + '.$xp.' WHERE pseudo="'.$pseudo.'"';
				$req = mysql_query($sql);
				return $xp." points d'expérience";
			} elseif($acc=="polyvalent du travail") {
				$xp = 100000;
				if(possede_talent('Génie')) $xp += floor(0.1*$xp);
				$sql = 'UPDATE principal_tbl SET total = total + '.$xp.' WHERE pseudo="'.$pseudo.'"';
				$req = mysql_query($sql);
				return $xp." points d'expérience";
			} elseif($acc=="fanatique ambitieux") {
				$xp = 100000000;
				$bonus = 20;
				if(possede_talent('Génie')) $xp += floor(0.1*$xp);
				$sql = 'UPDATE principal_tbl SET total = total + '.$xp.', sante_max = sante_max + '.$bonus.', fatigue_max = fatigue_max + '.$bonus.', sante = sante_max, fatigue = fatigue_max WHERE pseudo="'.$pseudo.'"';
				$req = mysql_query($sql);
				return $xp." points d'expérience<br />+".$bonus." en santé et forme";
			} elseif($acc=="bosseur actif") {
				$xp = 100;
				if(possede_talent('Génie')) $xp += floor(0.1*$xp);
				$sql = 'UPDATE principal_tbl SET total = total + '.$xp.' WHERE pseudo="'.$pseudo.'"';
				$req = mysql_query($sql);
				return $xp." points d'expérience";
			} elseif($acc=="actif de la ville") {
				$xp = 100000;
				if(possede_talent('Génie')) $xp += floor(0.1*$xp);
				$sql = 'UPDATE principal_tbl SET total = total + '.$xp.' WHERE pseudo="'.$pseudo.'"';
				$req = mysql_query($sql);
				return $xp." points d'expérience";
			} elseif($acc=="ciment de la cité") {
				$xp = 100000000;
				$bonus = 20;
				if(possede_talent('Génie')) $xp += floor(0.1*$xp);
				$sql = 'UPDATE principal_tbl SET total = total + '.$xp.', sante_max = sante_max + '.$bonus.', fatigue_max = fatigue_max + '.$bonus.', sante = sante_max, fatigue = fatigue_max WHERE pseudo="'.$pseudo.'"';
				$req = mysql_query($sql);
				return $xp." points d'expérience<br />+".$bonus." en santé et forme";
			} elseif($acc=="gérant émérite") {
				$xp = 100;
				if(possede_talent('Génie')) $xp += floor(0.1*$xp);
				$sql = 'UPDATE principal_tbl SET total = total + '.$xp.' WHERE pseudo="'.$pseudo.'"';
				$req = mysql_query($sql);
				return $xp." points d'expérience";
			} elseif($acc=="gros bonnet") {
				$xp = 100000;
				if(possede_talent('Génie')) $xp += floor(0.1*$xp);
				$sql = 'UPDATE principal_tbl SET total = total + '.$xp.' WHERE pseudo="'.$pseudo.'"';
				$req = mysql_query($sql);
				return $xp." points d'expérience";
			} elseif($acc=="génie economiste") {
				$xp = 100000000;
				$bonus = 20;
				if(possede_talent('Génie')) $xp += floor(0.1*$xp);
				$sql = 'UPDATE principal_tbl SET total = total + '.$xp.', sante_max = sante_max + '.$bonus.', fatigue_max = fatigue_max + '.$bonus.', sante = sante_max, fatigue = fatigue_max WHERE pseudo="'.$pseudo.'"';
				$req = mysql_query($sql);
				return $xp." points d'expérience<br />+".$bonus." en santé et forme";
			} elseif($acc=="petite frappe") {
				$xp = 100;
				if(possede_talent('Génie')) $xp += floor(0.1*$xp);
				$sql = 'UPDATE principal_tbl SET total = total + '.$xp.' WHERE pseudo="'.$pseudo.'"';
				$req = mysql_query($sql);
				return $xp." points d'expérience";
			} elseif($acc=="danger public") {
				$xp = 100000;
				if(possede_talent('Génie')) $xp += floor(0.1*$xp);
				$sql = 'UPDATE principal_tbl SET total = total + '.$xp.' WHERE pseudo="'.$pseudo.'"';
				$req = mysql_query($sql);
				return $xp." points d'expérience";
			} elseif($acc=="ennemi de l'impérium") {
				$xp = 100000000;
				$bonus = 20;
				if(possede_talent('Génie')) $xp += floor(0.1*$xp);
				$sql = 'UPDATE principal_tbl SET total = total + '.$xp.', sante_max = sante_max + '.$bonus.', fatigue_max = fatigue_max + '.$bonus.', sante = sante_max, fatigue = fatigue_max WHERE pseudo="'.$pseudo.'"';
				$req = mysql_query($sql);
				return $xp." points d'expérience<br />+".$bonus." en santé et forme";
			} elseif($acc=="espoir de la cité") {
				$xp = 100;
				if(possede_talent('Génie')) $xp += floor(0.1*$xp);
				$sql = 'UPDATE principal_tbl SET total = total + '.$xp.' WHERE pseudo="'.$pseudo.'"';
				$req = mysql_query($sql);
				return $xp." points d'expérience";
			} elseif($acc=="fervent impérialiste") {
				$xp = 100000;
				if(possede_talent('Génie')) $xp += floor(0.1*$xp);
				$sql = 'UPDATE principal_tbl SET total = total + '.$xp.' WHERE pseudo="'.$pseudo.'"';
				$req = mysql_query($sql);
				return $xp." points d'expérience";
			} elseif($acc=="haut fonctionnaire") {
				$xp = 100000000;
				$bonus = 20;
				if(possede_talent('Génie')) $xp += floor(0.1*$xp);
				$sql = 'UPDATE principal_tbl SET total = total + '.$xp.', sante_max = sante_max + '.$bonus.', fatigue_max = fatigue_max + '.$bonus.', sante = sante_max, fatigue = fatigue_max WHERE pseudo="'.$pseudo.'"';
				$req = mysql_query($sql);
				return $xp." points d'expérience<br />+".$bonus." en santé et forme";
			} elseif($acc=="arme de l'impérium") {
				$xp = 100000000;
				$bonus = 20;
				if(possede_talent('Génie')) $xp += floor(0.1*$xp);
				$sql = 'UPDATE principal_tbl SET total = total + '.$xp.', sante_max = sante_max + '.$bonus.', fatigue_max = fatigue_max + '.$bonus.', sante = sante_max, fatigue = fatigue_max WHERE pseudo="'.$pseudo.'"';
				$req = mysql_query($sql);
				return $xp." points d'expérience<br />+".$bonus." en santé et forme";
			} elseif($acc=="bonne âme") {
				$xp = 100;
				if(possede_talent('Génie')) $xp += floor(0.1*$xp);
				$sql = 'UPDATE principal_tbl SET total = total + '.$xp.' WHERE pseudo="'.$pseudo.'"';
				$req = mysql_query($sql);
				return $xp." points d'expérience";
			} elseif($acc=="altruiste") {
				$xp = 100000;
				if(possede_talent('Génie')) $xp += floor(0.1*$xp);
				$sql = 'UPDATE principal_tbl SET total = total + '.$xp.' WHERE pseudo="'.$pseudo.'"';
				$req = mysql_query($sql);
				return $xp." points d'expérience";
			} elseif($acc=="philanthrope") {
				$xp = 100000000;
				$bonus = 20;
				if(possede_talent('Génie')) $xp += floor(0.1*$xp);
				$sql = 'UPDATE principal_tbl SET total = total + '.$xp.', sante_max = sante_max + '.$bonus.', fatigue_max = fatigue_max + '.$bonus.', sante = sante_max, fatigue = fatigue_max WHERE pseudo="'.$pseudo.'"';
				$req = mysql_query($sql);
				return $xp." points d'expérience<br />+".$bonus." en santé et forme";
			} elseif($acc=="apprenti picpocket") {
				$xp = 100;
				if(possede_talent('Génie')) $xp += floor(0.1*$xp);
				$sql = 'UPDATE principal_tbl SET total = total + '.$xp.' WHERE pseudo="'.$pseudo.'"';
				$req = mysql_query($sql);
				return $xp." points d'expérience";
			} elseif($acc=="escroc") {
				$xp = 100000;
				if(possede_talent('Génie')) $xp += floor(0.1*$xp);
				$sql = 'UPDATE principal_tbl SET total = total + '.$xp.' WHERE pseudo="'.$pseudo.'"';
				$req = mysql_query($sql);
				return $xp." points d'expérience";
			} elseif($acc=="arnaqueur") {
				$xp = 100000000;
				$bonus = 20;
				if(possede_talent('Génie')) $xp += floor(0.1*$xp);
				$sql = 'UPDATE principal_tbl SET total = total + '.$xp.', sante_max = sante_max + '.$bonus.', fatigue_max = fatigue_max + '.$bonus.', sante = sante_max, fatigue = fatigue_max WHERE pseudo="'.$pseudo.'"';
				$req = mysql_query($sql);
				return $xp." points d'expérience<br />+".$bonus." en santé et forme";
			} elseif($acc=="militant") {
				$xp = 100;
				if(possede_talent('Génie')) $xp += floor(0.1*$xp);
				$sql = 'UPDATE principal_tbl SET total = total + '.$xp.' WHERE pseudo="'.$pseudo.'"';
				$req = mysql_query($sql);
				return $xp." points d'expérience";
			} elseif($acc=="activiste") {
				$xp = 100000;
				if(possede_talent('Génie')) $xp += floor(0.1*$xp);
				$sql = 'UPDATE principal_tbl SET total = total + '.$xp.' WHERE pseudo="'.$pseudo.'"';
				$req = mysql_query($sql);
				return $xp." points d'expérience";
			} elseif($acc=="leader charismatique") {
				$xp = 100000000;
				$bonus = 20;
				if(possede_talent('Génie')) $xp += floor(0.1*$xp);
				$sql = 'UPDATE principal_tbl SET total = total + '.$xp.', sante_max = sante_max + '.$bonus.', fatigue_max = fatigue_max + '.$bonus.', sante = sante_max, fatigue = fatigue_max WHERE pseudo="'.$pseudo.'"';
				$req = mysql_query($sql);
				return $xp." points d'expérience<br />+".$bonus." en santé et forme";
			} elseif($acc=="propriétaire terrien") {
				$xp = 100;
				if(possede_talent('Génie')) $xp += floor(0.1*$xp);
				$sql = 'UPDATE principal_tbl SET total = total + '.$xp.' WHERE pseudo="'.$pseudo.'"';
				$req = mysql_query($sql);
				return $xp." points d'expérience";
			} elseif($acc=="bailleur") {
				$xp = 100000;
				if(possede_talent('Génie')) $xp += floor(0.1*$xp);
				$sql = 'UPDATE principal_tbl SET total = total + '.$xp.' WHERE pseudo="'.$pseudo.'"';
				$req = mysql_query($sql);
				return $xp." points d'expérience";
			} elseif($acc=="fortune immobilière") {
				$xp = 100000000;
				$bonus = 20;
				if(possede_talent('Génie')) $xp += floor(0.1*$xp);
				$sql = 'UPDATE principal_tbl SET total = total + '.$xp.', sante_max = sante_max + '.$bonus.', fatigue_max = fatigue_max + '.$bonus.', sante = sante_max, fatigue = fatigue_max WHERE pseudo="'.$pseudo.'"';
				$req = mysql_query($sql);
				return $xp." points d'expérience<br />+".$bonus." en santé et forme";
			} elseif($acc=="initié") {
				$xp = 100;
				if(possede_talent('Génie')) $xp += floor(0.1*$xp);
				$sql = 'UPDATE principal_tbl SET total = total + '.$xp.' WHERE pseudo="'.$pseudo.'"';
				$req = mysql_query($sql);
				return $xp." points d'expérience";
			} elseif($acc=="expert") {
				$xp = 100000;
				if(possede_talent('Génie')) $xp += floor(0.1*$xp);
				$sql = 'UPDATE principal_tbl SET total = total + '.$xp.' WHERE pseudo="'.$pseudo.'"';
				$req = mysql_query($sql);
				return $xp." points d'expérience";
			} elseif($acc=="spécialiste") {
				$xp = 100000000;
				$bonus = 20;
				if(possede_talent('Génie')) $xp += floor(0.1*$xp);
				$sql = 'UPDATE principal_tbl SET total = total + '.$xp.', sante_max = sante_max + '.$bonus.', fatigue_max = fatigue_max + '.$bonus.', sante = sante_max, fatigue = fatigue_max WHERE pseudo="'.$pseudo.'"';
				$req = mysql_query($sql);
				return $xp." points d'expérience<br />+".$bonus." en santé et forme";
			} elseif($acc=="promeneur bavard") {
				$xp = 100;
				if(possede_talent('Génie')) $xp += floor(0.1*$xp);
				$sql = 'UPDATE principal_tbl SET total = total + '.$xp.' WHERE pseudo="'.$pseudo.'"';
				$req = mysql_query($sql);
				return $xp." points d'expérience";
			} elseif($acc=="crieur de rue") {
				$xp = 100000;
				if(possede_talent('Génie')) $xp += floor(0.1*$xp);
				$sql = 'UPDATE principal_tbl SET total = total + '.$xp.' WHERE pseudo="'.$pseudo.'"';
				$req = mysql_query($sql);
				return $xp." points d'expérience";
			} elseif($acc=="recruteur impérial") {
				$xp = 100000000;
				$bonus = 20;
				if(possede_talent('Génie')) $xp += floor(0.1*$xp);
				$sql = 'UPDATE principal_tbl SET total = total + '.$xp.', sante_max = sante_max + '.$bonus.', fatigue_max = fatigue_max + '.$bonus.', sante = sante_max, fatigue = fatigue_max WHERE pseudo="'.$pseudo.'"';
				$req = mysql_query($sql);
				return $xp." points d'expérience<br />+".$bonus." en santé et forme";
			} elseif($acc=="fêtard des 2 ans") {
				$xp = 10000;
				if(possede_talent('Génie')) $xp += floor(0.1*$xp);
				$sql = 'UPDATE principal_tbl SET total = total + '.$xp.' WHERE pseudo="'.$pseudo.'"';
				$req = mysql_query($sql);
				return $xp." points d'expérience";
			} elseif($acc=="mécanicien sav") {
				$xp = 1000;
				if(possede_talent('Génie')) $xp += floor(0.1*$xp);
				$sql = 'UPDATE principal_tbl SET total = total + '.$xp.' WHERE pseudo="'.$pseudo.'"';
				$req = mysql_query($sql);
				return $xp." points d'expérience";
			} elseif($acc=="réparateur") {
				$xp = 1000;
				if(possede_talent('Génie')) $xp += floor(0.1*$xp);
				$sql = 'UPDATE principal_tbl SET total = total + '.$xp.' WHERE pseudo="'.$pseudo.'"';
				$req = mysql_query($sql);
				return $xp." points d'expérience";
			} elseif($acc=="sauveur de droïde") {
				$xp = 1000;
				if(possede_talent('Génie')) $xp += floor(0.1*$xp);
				$sql = 'UPDATE principal_tbl SET total = total + '.$xp.' WHERE pseudo="'.$pseudo.'"';
				$req = mysql_query($sql);
				return $xp." points d'expérience";
			} elseif($acc=="machine rutilante") {
				$xp = 1000;
				if(possede_talent('Génie')) $xp += floor(0.1*$xp);
				$sql = 'UPDATE principal_tbl SET total = total + '.$xp.' WHERE pseudo="'.$pseudo.'"';
				$req = mysql_query($sql);
				return $xp." points d'expérience";
			} elseif($acc=="chair à canon") {
				$xp = 1000;
				if(possede_talent('Génie')) $xp += floor(0.1*$xp);
				$sql = 'UPDATE principal_tbl SET total = total + '.$xp.' WHERE pseudo="'.$pseudo.'"';
				$req = mysql_query($sql);
				return $xp." points d'expérience";
			} elseif($acc=="revendeur de composants") {
				$xp = 1000;
				if(possede_talent('Génie')) $xp += floor(0.1*$xp);
				$sql = 'UPDATE principal_tbl SET total = total + '.$xp.' WHERE pseudo="'.$pseudo.'"';
				$req = mysql_query($sql);
				return $xp." points d'expérience";
			} elseif($acc=="l'anti-droïde") {
				$xp = 1000;
				if(possede_talent('Génie')) $xp += floor(0.1*$xp);
				$sql = 'UPDATE principal_tbl SET total = total + '.$xp.' WHERE pseudo="'.$pseudo.'"';
				$req = mysql_query($sql);
				return $xp." points d'expérience";
			}
			
		}
	?>
	</div>
</div>

<?php if($chat=="oui") 	{ if ((date("H")>=21) || (date("H")<=7)) { include("inc_bas_n_c.php"); } else { include("inc_bas_j_c.php"); } } else {	if ((date("H")>=21) || (date("H")<=7)) { include("inc_bas_n.php"); } else { include("inc_bas_j.php"); }	} ?>
<?php include("inc_bas_de_page.php"); ?>	
