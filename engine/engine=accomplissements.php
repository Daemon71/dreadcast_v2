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
				F�licitation, vous avez obtenu le titre "<strong>'.$_GET['acc'].'</strong>".<br />
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
							'.(($actuel[0]!=0 && strtolower($actuel[1])!="citoyen privil�gi�")?'<img style="position:absolute;right:-20px;top:'.((count($actuel[0])==1)?'13px':'4px').';" src="im_objets/fleche_acc_1'.count($actuel[0]).'.gif" alt="" />':''));
							
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
				//if($j==2 && !$titre['citoyen privil�gi�']) break;
				
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
			$acc[0][0] = "Citoyennet�";
			$acc[0][1] = array(2,'Nouvel Arrivant','Vous venez d\'arriver dans la cit�, et commencez tout juste � prendre vos marques.<br />Quelques actions de base vous aideront grandement � survivre dans cet univers inhospitalier.','Trouvez un emploi','Trouvez un logement','Envoyez au moins un message priv�');
			$acc[0][2] = array(3,'Citoyen Privil�gi�','A votre arriv�e dans la ville, un compte am�lior� vous est propos� gratuitement : le compte Silver.<br /><br />Les comptes am�lior�s permettent de d�couvrir le jeu d\'une mani�re plus confortable et approfondie, et permettent au site de survivre.','Prenez un compte am�lior�');
			if(possede_talent("citoyen d'honneur")) $acc[0][3] = array(0,'Citoyen d\'Honneur','Vous contribuez � am�liorer le jeu en aidant les administrateurs.','Accomplissement re�u d\'un administrateur');
			else $acc[0][3] = array(0,'?','?','?');
			
			$acc[1][0] = "Travail";
			$acc[1][1] = array(array(2,3),'Bourreau de travail','Dans cette ville, avoir une activit� professionnelle est signe de sant� financi�re. Attention toutefois de ne pas se tuer � la t�che.','Atteindre le niveau 3','100 heures de travail standard');
			$acc[1][2] = array(4,'Fid�le � son poste','Votre entreprise, c\'est sacr�. Vous avez un objectif de carri�re et vous vous y tenez !<br /><br /><span style="font-weight:bold;color:#60A9ED;">ATTENTION</span> : En validant ce titre, vous ne pourrez plus obtenir le titre "<strong style="display:inline;color:#fff;font-size:12px;">Polyvalent du travail</strong>".','Atteindre le niveau 5','200 heures de travail standard','Maximum 2 emplois diff�rents');
			$acc[1][3] = array(4,'Polyvalent du travail','Touche � tout, vous aimez diversifier vos exp�riences professionnelles. Vous �tes pr�t � tout !<br /><br /><span style="font-weight:bold;color:#60A9ED;">ATTENTION</span> : En validant ce titre, vous ne pourrez plus obtenir le titre "<strong style="display:inline;color:#fff;font-size:12px;">Fid�le � son poste</strong>".','Atteindre le niveau 5','200 heures de travail standard','Minimum 5 emplois diff�rents');
			$acc[1][4] = array(0,'Fanatique ambitieux','Vous �tes un acharn� du travail. Pr�s de vous, le temps s\'�coule en toute relativit�.','Etre pr�sent en ville depuis 1 mois','500 heures supp\'');
			$acc[1][5] = array(6,'Bosseur actif','Vous aimez agir, quoi qu\'il en co�te : la ville a besoin de vous.','Atteindre le niveau 3','10 jours de travail actif');
			$acc[1][6] = array(7,'Actif de la ville','Vous bougez constamment, mais vous savez aussi vous entourer. La communication, �a vous conna�t !','Atteindre le niveau 5','50 MPs professionnels envoy�s');
			$acc[1][7] = array(0,'Ciment de la cit�','Vous �tes ancr� dans la ville, qui vit en partie de votre activit� et du mouvement que vous g�n�rez.','Etre pr�sent en ville depuis 1 mois','Pr�sent depuis 10 ans');
			$acc[1][8] = array(9,'G�rant �m�rite','L\'entrepreneuriat vous a toujours tent�. Ca y est, vous avez d�cid� de franchir le pas !','Atteindre le niveau 3','1 entreprise cr��e','10 jours de travail actif');
			$acc[1][9] = array(10,'Gros bonnet','Vous commencez s�rieusement � vous faire une place parmi les bonnes entreprises qui font fonctionner l\'�conomie.','Atteindre le niveau 5','25000 Cr de CA cumul�');
			$acc[1][10] = array(0,'G�nie economiste','Vous �tes un visionnaire de la gestion d\'entreprise. Vos actions influencent � haut niveau l\'�conomie de la cit�.','Etre pr�sent en ville depuis 1 mois','10 salari�s','5000 Cr de CA journalier','1 poste de directeur occup�');
			
			$acc[2][0] = "Alignement";
			$acc[2][1] = array(2,'Petite frappe','Vous faites vos armes sur les passants qui vous regardent de travers. Le respect est une valeur qu\'on vous doit, vous l\'imposez par vos poings.<br /><br /><span style="font-weight:bold;color:#60A9ED;">ATTENTION</span> : En validant ce titre, vous ne pourrez plus obtenir le titre "<strong style="display:inline;color:#fff;font-size:12px;">Espoir de la cit�</strong>".','Atteindre le niveau 3','Premiers d�g�ts donn�s');
			$acc[2][2] = array(3,'Danger public','Vous vous hissez dans le grand banditisme en marchant sur le cadavre des autres. Ils n\'avaient pas qu\'� �tre sur votre chemin.','Atteindre le niveau 5','5 meurtres');
			$acc[2][3] = array(0,'Ennemi de l\'Imp�rium','L\'Imp�rium est une menace pour votre activit� : vous savez tailler dans le vif lorsque n�cessaire. Votre nom impose la crainte et le respect.','Etre pr�sent en ville depuis 1 mois','50 meurtres');
			$acc[2][4] = array(5,'Espoir de la cit�','Votre volont� d\'aider l\'Imp�rium est palpable. Vous d�gagez une �nergie positive qui motive les autres.<br /><br /><span style="font-weight:bold;color:#60A9ED;">ATTENTION</span> : En validant ce titre, vous ne pourrez plus obtenir le titre "<strong style="display:inline;color:#fff;font-size:12px;">Petite frappe</strong>".','Atteindre le niveau 3','10 jours de travail actif en OI');
			$acc[2][5] = array(array(6,7),'Fervent Imp�rialiste','Vous vous faites une place dans la hierarchie, et votre dynamisme est reconnu de tous.','Atteindre le niveau 5','50 jours de travail actif en OI');
			$acc[2][6] = array(0,'Haut fonctionnaire','Vous avez des projets, une vision de l\'Imp�rium, et savez la partager. Imposer vos id�es vous m�ne haut et les gens vous respectent pour �a. Gr�ce � vous, c\'est toute la cit� qui �volue.<br /><br /><span style="font-weight:bold;color:#60A9ED;">ATTENTION</span> : En validant ce titre, vous ne pourrez plus obtenir le titre "<strong style="display:inline;color:#fff;font-size:12px;">Arme de l\'Imp�rium</strong>".','Etre pr�sent en ville depuis 1 mois','Membre du Conseil ou Directeur Imp�rial');
			$acc[2][7] = array(0,'Arme de l\'Imp�rium','"Servir et agir", "Pour le bien de l\'Imp�rium", telles sont vos r�pliques favorites. Vous �tes redout�s des criminels et votre loyaut� vous fait honneur.<br /><br /><span style="font-weight:bold;color:#60A9ED;">ATTENTION</span> : En validant ce titre, vous ne pourrez plus obtenir le titre "<strong style="display:inline;color:#fff;font-size:12px;">Haut fonctionnaire</strong>".','Etre pr�sent en ville depuis 1 mois','100 arrestations de personnes recherch�es');
			
			$acc[3][0] = "�me";
			$acc[3][1] = array(2,'Bonne �me','L\'�me g�n�reuse de nature, vous aidez ceux qui sont dans le besoin en leur apportant r�confort et secours.<br /><br /><span style="font-weight:bold;color:#60A9ED;">ATTENTION</span> : En validant ce titre, vous ne pourrez plus obtenir le titre "<strong style="display:inline;color:#fff;font-size:12px;">Apprenti picpocket</strong>"','Atteindre le niveau 3','Premier don','Premiers soins');
			$acc[3][2] = array(3,'Altruiste','Vous �tes constamment tourn�s vers les autres, et ils vous en sont reconnaissants.','Atteindre le niveau 5','15 dons','15 soins','Prot�ger quelqu\'un');
			$acc[3][3] = array(0,'Philanthrope','Vous avez d�di� votre vie � aider les habitants de la cit�, et repr�sentez une ic�ne de bienveillance pour tous les citoyens.','Etre pr�sent en ville depuis 1 mois','100 dons','100 soins','30 protections');
			$acc[3][4] = array(5,'Apprenti picpocket','La survie, �a vous conna�t. Et s\'il faut choisir entre vous et les autres, vous n\'avez aucun scrupule � vous remplir les poches.<br /><br /><span style="font-weight:bold;color:#60A9ED;">ATTENTION</span> : En validant ce titre, vous ne pourrez plus obtenir le titre "<strong style="display:inline;color:#fff;font-size:12px;">Bonne �me</strong>"','Atteindre le niveau 3','1 en vol');
			$acc[3][5] = array(6,'Escroc','Votre petit business marche pas mal, vos mains de f�e commencent � �tre connues dans le milieu.','Atteindre le niveau 5','20 vols r�ussis');
			$acc[3][6] = array(0,'Arnaqueur','Vous avez �cum� toutes les poches de la ville, et votre fortune s\'est faite sur le dos des autres. Ceux qui vous connaissent bien changent de trottoir en vous croisant.','Etre pr�sent en ville depuis 1 mois','100 vols r�ussis','Casier non vierge');
			
			$acc[4][0] = "Cercles";
			$acc[4][1] = array(2,'Militant','Vous avez trouv� un cercle associatif ou politique qui vous convient. Vous avez fait de son combat le votre.','Atteindre le niveau 3','Pr�sent dans un cercle');
			$acc[4][2] = array(3,'Activiste','Vous soutenez une cause qui vous est ch�re et aidez les dirigeants de votre cercle.','Atteindre le niveau 5','1000 Cr de dons � un cercle','5 commentaires sur l\'actualit�');
			$acc[4][3] = array(0,'Leader charismatique','Vous avez cr�� votre propre cause et la d�fendez hardiment. Vous rayonnez, et de nombreuses personnes vous ont d�j� rejoint.','Etre pr�sent en ville depuis 1 mois','Dirigeant de cercle','5 actualit� dans le cercle','15 adh�rents');

			$acc[5][0] = "Logements";
			$acc[5][1] = array(2,'Propri�taire terrien','Ca y est, c\'est le grand jour ! Vous devenez propri�taire de votre propre logement ! Ni trop petit, ni trop grand, il est p-a-r-f-a-i-t.','Atteindre le niveau 3','Un logement achet�');
			$acc[5][2] = array(3,'Bailleur','Vos quelques logements font d�j� de vous une personne influente et respect�e.','Atteindre le niveau 5','5 logement achet�');
			$acc[5][3] = array(0,'Fortune immobili�re','Le temps des petites affaires est loin derri�re vous. Votre parc immobilier en dit long sur votre fortune personnelle.','Etre pr�sent en ville depuis 1 mois','20 locataires');

			$acc[6][0] = "Statistiques";
			$acc[6][1] = array(2,'Initi�','Vous n\'�tes plus un d�butant. Votre talent dans un domaine vous conduit chaque jour � agir efficacement.','Atteindre le niveau 3','Initi� dans n\'importe quelle comp�tence');
			$acc[6][2] = array(3,'Expert','C\'est en connaissance de cause que vous agissez. Un domaine vous passionne, �a se voit !','Atteindre le niveau 5','Expert dans n\'importe quelle comp�tence');
			$acc[6][3] = array(0,'Sp�cialiste','Votre professionnalisme dans un domaine vous permet de toucher l\'excellence.','Etre pr�sent en ville depuis 1 mois','Sp�cialiste dans n\'importe quelle comp�tence');
			
			$acc[7][0] = "Recrutement";
			$acc[7][1] = array(2,'Promeneur bavard','Vous aimez bien, en vous aventurant hors de la cit�, discuter avec les autochtones pour leurs conter les magnificences de DreadCast.','Parrainer 3 citoyens');
			$acc[7][2] = array(3,'Crieur de rue','Vous avez une mission : propager la lumi�re aux �mes �gar�es, et leur ouvrir les yeux sur notre monde. Et vous vous d�brouillez plut�t bien !','Parrainer 10 citoyens', '...dont 5 ont atteint le niveau 3', '...et 1 a un compte am�lior� (sauf Silver)');
			$acc[7][3] = array(0,'Recruteur Imp�rial','Convaincre, c\'est votre cr�do ! Une l�gende circule comme quoi vous pouvez faire faire n\'importe quoi au gens rien qu\'en croisant leur regard. He bah !','Parrainer 30 citoyens', '...dont 10 ont atteint le niveau 6', '...et 3 ont un compte am�lior� (sauf Silver)');
			
			$acc[8][0] = "Events";
			$acc[8][1] = array(0,'F�tard des 2 ans','Toujours partant pour faire la f�te ! En tout cas, pour les 2 ans... vous �tiez l� !','Aller faire la f�te pour les 2 bougies de Dreadcast');
			$acc[8][2] = array(3,'M�canicien SAV','Un tas de boulon vous a trop tap� sur le syst�me...','Infliger des d�g�ts � un dro�de fou');
			$acc[8][3] = array(4,'R�parateur','Vous savez remonter des pi�ces d�tach�es.','R�parer un dro�de fou');
			$acc[8][4] = array(0,'Sauveur de dro�de','Vous n\'avez pas foi en l\'avenir organique.','R�parer cinq dro�des fous');
			$acc[8][5] = array(6,'Machine rutilante','Tout beau tout neuf !','Avoir �t� sauv� du virus D');
			$acc[8][6] = array(0,'A venir','A venir','-');
			$acc[8][7] = array(8,'Chair � canon','Vous n\'avez peur de rien.','Infliger des d�g�ts � Paracelse');
			$acc[8][8] = array(9,'Revendeur de composants','Quand vous frappez, les puces �lectroniques giclent.','Infliger 2000 d�g�ts � Paracelse');
			$acc[8][9] = array(0,'L\'anti-dro�de','Vous avez tu� le super-dro�de Paracelse !','Achever Paracelse');
			
			return $acc;
		}
		
		function affiche_accomplissement($i,$j,$actuel,$descriptions,$titre) {
			$possede_titre = $titre[strtolower($actuel[1])];
			
			$infos = verif_accomplissement(strtolower($actuel[1]));
			
			print($actuel[1]);
			
			$descriptions .= '<div id="d'.$i.'d'.$j.'" class="description" style="display:none;position:relative;">
					'.$actuel[2].'<br />
					<strong>Actions n�cessaires</strong>
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
				if(possede_talent('G�nie')) $xp += floor(0.1*$xp);
				$sql = 'UPDATE principal_tbl SET total = total + '.$xp.' WHERE pseudo="'.$pseudo.'"';
				$req = mysql_query($sql);
				return $xp." points d'exp�rience";
			} elseif($acc=="citoyen privil�gi�") {
				$xp = 10;
				if(possede_talent('G�nie')) $xp += floor(0.1*$xp);
				$sql = 'UPDATE principal_tbl SET total = total + '.$xp.' WHERE pseudo="'.$pseudo.'"';
				$req = mysql_query($sql);
				return $xp." points d'exp�rience";
			} elseif($acc=="bourreau de travail") {
				$xp = 100;
				if(possede_talent('G�nie')) $xp += 	0.1*$xp;
				$sql = 'UPDATE principal_tbl SET total = total + '.$xp.' WHERE pseudo="'.$pseudo.'"';
				$req = mysql_query($sql);
				return $xp." points d'exp�rience";
			} elseif($acc=="fid�le � son poste") {
				$xp = 100000;
				if(possede_talent('G�nie')) $xp += floor(0.1*$xp);
				$sql = 'UPDATE principal_tbl SET total = total + '.$xp.' WHERE pseudo="'.$pseudo.'"';
				$req = mysql_query($sql);
				return $xp." points d'exp�rience";
			} elseif($acc=="polyvalent du travail") {
				$xp = 100000;
				if(possede_talent('G�nie')) $xp += floor(0.1*$xp);
				$sql = 'UPDATE principal_tbl SET total = total + '.$xp.' WHERE pseudo="'.$pseudo.'"';
				$req = mysql_query($sql);
				return $xp." points d'exp�rience";
			} elseif($acc=="fanatique ambitieux") {
				$xp = 100000000;
				$bonus = 20;
				if(possede_talent('G�nie')) $xp += floor(0.1*$xp);
				$sql = 'UPDATE principal_tbl SET total = total + '.$xp.', sante_max = sante_max + '.$bonus.', fatigue_max = fatigue_max + '.$bonus.', sante = sante_max, fatigue = fatigue_max WHERE pseudo="'.$pseudo.'"';
				$req = mysql_query($sql);
				return $xp." points d'exp�rience<br />+".$bonus." en sant� et forme";
			} elseif($acc=="bosseur actif") {
				$xp = 100;
				if(possede_talent('G�nie')) $xp += floor(0.1*$xp);
				$sql = 'UPDATE principal_tbl SET total = total + '.$xp.' WHERE pseudo="'.$pseudo.'"';
				$req = mysql_query($sql);
				return $xp." points d'exp�rience";
			} elseif($acc=="actif de la ville") {
				$xp = 100000;
				if(possede_talent('G�nie')) $xp += floor(0.1*$xp);
				$sql = 'UPDATE principal_tbl SET total = total + '.$xp.' WHERE pseudo="'.$pseudo.'"';
				$req = mysql_query($sql);
				return $xp." points d'exp�rience";
			} elseif($acc=="ciment de la cit�") {
				$xp = 100000000;
				$bonus = 20;
				if(possede_talent('G�nie')) $xp += floor(0.1*$xp);
				$sql = 'UPDATE principal_tbl SET total = total + '.$xp.', sante_max = sante_max + '.$bonus.', fatigue_max = fatigue_max + '.$bonus.', sante = sante_max, fatigue = fatigue_max WHERE pseudo="'.$pseudo.'"';
				$req = mysql_query($sql);
				return $xp." points d'exp�rience<br />+".$bonus." en sant� et forme";
			} elseif($acc=="g�rant �m�rite") {
				$xp = 100;
				if(possede_talent('G�nie')) $xp += floor(0.1*$xp);
				$sql = 'UPDATE principal_tbl SET total = total + '.$xp.' WHERE pseudo="'.$pseudo.'"';
				$req = mysql_query($sql);
				return $xp." points d'exp�rience";
			} elseif($acc=="gros bonnet") {
				$xp = 100000;
				if(possede_talent('G�nie')) $xp += floor(0.1*$xp);
				$sql = 'UPDATE principal_tbl SET total = total + '.$xp.' WHERE pseudo="'.$pseudo.'"';
				$req = mysql_query($sql);
				return $xp." points d'exp�rience";
			} elseif($acc=="g�nie economiste") {
				$xp = 100000000;
				$bonus = 20;
				if(possede_talent('G�nie')) $xp += floor(0.1*$xp);
				$sql = 'UPDATE principal_tbl SET total = total + '.$xp.', sante_max = sante_max + '.$bonus.', fatigue_max = fatigue_max + '.$bonus.', sante = sante_max, fatigue = fatigue_max WHERE pseudo="'.$pseudo.'"';
				$req = mysql_query($sql);
				return $xp." points d'exp�rience<br />+".$bonus." en sant� et forme";
			} elseif($acc=="petite frappe") {
				$xp = 100;
				if(possede_talent('G�nie')) $xp += floor(0.1*$xp);
				$sql = 'UPDATE principal_tbl SET total = total + '.$xp.' WHERE pseudo="'.$pseudo.'"';
				$req = mysql_query($sql);
				return $xp." points d'exp�rience";
			} elseif($acc=="danger public") {
				$xp = 100000;
				if(possede_talent('G�nie')) $xp += floor(0.1*$xp);
				$sql = 'UPDATE principal_tbl SET total = total + '.$xp.' WHERE pseudo="'.$pseudo.'"';
				$req = mysql_query($sql);
				return $xp." points d'exp�rience";
			} elseif($acc=="ennemi de l'imp�rium") {
				$xp = 100000000;
				$bonus = 20;
				if(possede_talent('G�nie')) $xp += floor(0.1*$xp);
				$sql = 'UPDATE principal_tbl SET total = total + '.$xp.', sante_max = sante_max + '.$bonus.', fatigue_max = fatigue_max + '.$bonus.', sante = sante_max, fatigue = fatigue_max WHERE pseudo="'.$pseudo.'"';
				$req = mysql_query($sql);
				return $xp." points d'exp�rience<br />+".$bonus." en sant� et forme";
			} elseif($acc=="espoir de la cit�") {
				$xp = 100;
				if(possede_talent('G�nie')) $xp += floor(0.1*$xp);
				$sql = 'UPDATE principal_tbl SET total = total + '.$xp.' WHERE pseudo="'.$pseudo.'"';
				$req = mysql_query($sql);
				return $xp." points d'exp�rience";
			} elseif($acc=="fervent imp�rialiste") {
				$xp = 100000;
				if(possede_talent('G�nie')) $xp += floor(0.1*$xp);
				$sql = 'UPDATE principal_tbl SET total = total + '.$xp.' WHERE pseudo="'.$pseudo.'"';
				$req = mysql_query($sql);
				return $xp." points d'exp�rience";
			} elseif($acc=="haut fonctionnaire") {
				$xp = 100000000;
				$bonus = 20;
				if(possede_talent('G�nie')) $xp += floor(0.1*$xp);
				$sql = 'UPDATE principal_tbl SET total = total + '.$xp.', sante_max = sante_max + '.$bonus.', fatigue_max = fatigue_max + '.$bonus.', sante = sante_max, fatigue = fatigue_max WHERE pseudo="'.$pseudo.'"';
				$req = mysql_query($sql);
				return $xp." points d'exp�rience<br />+".$bonus." en sant� et forme";
			} elseif($acc=="arme de l'imp�rium") {
				$xp = 100000000;
				$bonus = 20;
				if(possede_talent('G�nie')) $xp += floor(0.1*$xp);
				$sql = 'UPDATE principal_tbl SET total = total + '.$xp.', sante_max = sante_max + '.$bonus.', fatigue_max = fatigue_max + '.$bonus.', sante = sante_max, fatigue = fatigue_max WHERE pseudo="'.$pseudo.'"';
				$req = mysql_query($sql);
				return $xp." points d'exp�rience<br />+".$bonus." en sant� et forme";
			} elseif($acc=="bonne �me") {
				$xp = 100;
				if(possede_talent('G�nie')) $xp += floor(0.1*$xp);
				$sql = 'UPDATE principal_tbl SET total = total + '.$xp.' WHERE pseudo="'.$pseudo.'"';
				$req = mysql_query($sql);
				return $xp." points d'exp�rience";
			} elseif($acc=="altruiste") {
				$xp = 100000;
				if(possede_talent('G�nie')) $xp += floor(0.1*$xp);
				$sql = 'UPDATE principal_tbl SET total = total + '.$xp.' WHERE pseudo="'.$pseudo.'"';
				$req = mysql_query($sql);
				return $xp." points d'exp�rience";
			} elseif($acc=="philanthrope") {
				$xp = 100000000;
				$bonus = 20;
				if(possede_talent('G�nie')) $xp += floor(0.1*$xp);
				$sql = 'UPDATE principal_tbl SET total = total + '.$xp.', sante_max = sante_max + '.$bonus.', fatigue_max = fatigue_max + '.$bonus.', sante = sante_max, fatigue = fatigue_max WHERE pseudo="'.$pseudo.'"';
				$req = mysql_query($sql);
				return $xp." points d'exp�rience<br />+".$bonus." en sant� et forme";
			} elseif($acc=="apprenti picpocket") {
				$xp = 100;
				if(possede_talent('G�nie')) $xp += floor(0.1*$xp);
				$sql = 'UPDATE principal_tbl SET total = total + '.$xp.' WHERE pseudo="'.$pseudo.'"';
				$req = mysql_query($sql);
				return $xp." points d'exp�rience";
			} elseif($acc=="escroc") {
				$xp = 100000;
				if(possede_talent('G�nie')) $xp += floor(0.1*$xp);
				$sql = 'UPDATE principal_tbl SET total = total + '.$xp.' WHERE pseudo="'.$pseudo.'"';
				$req = mysql_query($sql);
				return $xp." points d'exp�rience";
			} elseif($acc=="arnaqueur") {
				$xp = 100000000;
				$bonus = 20;
				if(possede_talent('G�nie')) $xp += floor(0.1*$xp);
				$sql = 'UPDATE principal_tbl SET total = total + '.$xp.', sante_max = sante_max + '.$bonus.', fatigue_max = fatigue_max + '.$bonus.', sante = sante_max, fatigue = fatigue_max WHERE pseudo="'.$pseudo.'"';
				$req = mysql_query($sql);
				return $xp." points d'exp�rience<br />+".$bonus." en sant� et forme";
			} elseif($acc=="militant") {
				$xp = 100;
				if(possede_talent('G�nie')) $xp += floor(0.1*$xp);
				$sql = 'UPDATE principal_tbl SET total = total + '.$xp.' WHERE pseudo="'.$pseudo.'"';
				$req = mysql_query($sql);
				return $xp." points d'exp�rience";
			} elseif($acc=="activiste") {
				$xp = 100000;
				if(possede_talent('G�nie')) $xp += floor(0.1*$xp);
				$sql = 'UPDATE principal_tbl SET total = total + '.$xp.' WHERE pseudo="'.$pseudo.'"';
				$req = mysql_query($sql);
				return $xp." points d'exp�rience";
			} elseif($acc=="leader charismatique") {
				$xp = 100000000;
				$bonus = 20;
				if(possede_talent('G�nie')) $xp += floor(0.1*$xp);
				$sql = 'UPDATE principal_tbl SET total = total + '.$xp.', sante_max = sante_max + '.$bonus.', fatigue_max = fatigue_max + '.$bonus.', sante = sante_max, fatigue = fatigue_max WHERE pseudo="'.$pseudo.'"';
				$req = mysql_query($sql);
				return $xp." points d'exp�rience<br />+".$bonus." en sant� et forme";
			} elseif($acc=="propri�taire terrien") {
				$xp = 100;
				if(possede_talent('G�nie')) $xp += floor(0.1*$xp);
				$sql = 'UPDATE principal_tbl SET total = total + '.$xp.' WHERE pseudo="'.$pseudo.'"';
				$req = mysql_query($sql);
				return $xp." points d'exp�rience";
			} elseif($acc=="bailleur") {
				$xp = 100000;
				if(possede_talent('G�nie')) $xp += floor(0.1*$xp);
				$sql = 'UPDATE principal_tbl SET total = total + '.$xp.' WHERE pseudo="'.$pseudo.'"';
				$req = mysql_query($sql);
				return $xp." points d'exp�rience";
			} elseif($acc=="fortune immobili�re") {
				$xp = 100000000;
				$bonus = 20;
				if(possede_talent('G�nie')) $xp += floor(0.1*$xp);
				$sql = 'UPDATE principal_tbl SET total = total + '.$xp.', sante_max = sante_max + '.$bonus.', fatigue_max = fatigue_max + '.$bonus.', sante = sante_max, fatigue = fatigue_max WHERE pseudo="'.$pseudo.'"';
				$req = mysql_query($sql);
				return $xp." points d'exp�rience<br />+".$bonus." en sant� et forme";
			} elseif($acc=="initi�") {
				$xp = 100;
				if(possede_talent('G�nie')) $xp += floor(0.1*$xp);
				$sql = 'UPDATE principal_tbl SET total = total + '.$xp.' WHERE pseudo="'.$pseudo.'"';
				$req = mysql_query($sql);
				return $xp." points d'exp�rience";
			} elseif($acc=="expert") {
				$xp = 100000;
				if(possede_talent('G�nie')) $xp += floor(0.1*$xp);
				$sql = 'UPDATE principal_tbl SET total = total + '.$xp.' WHERE pseudo="'.$pseudo.'"';
				$req = mysql_query($sql);
				return $xp." points d'exp�rience";
			} elseif($acc=="sp�cialiste") {
				$xp = 100000000;
				$bonus = 20;
				if(possede_talent('G�nie')) $xp += floor(0.1*$xp);
				$sql = 'UPDATE principal_tbl SET total = total + '.$xp.', sante_max = sante_max + '.$bonus.', fatigue_max = fatigue_max + '.$bonus.', sante = sante_max, fatigue = fatigue_max WHERE pseudo="'.$pseudo.'"';
				$req = mysql_query($sql);
				return $xp." points d'exp�rience<br />+".$bonus." en sant� et forme";
			} elseif($acc=="promeneur bavard") {
				$xp = 100;
				if(possede_talent('G�nie')) $xp += floor(0.1*$xp);
				$sql = 'UPDATE principal_tbl SET total = total + '.$xp.' WHERE pseudo="'.$pseudo.'"';
				$req = mysql_query($sql);
				return $xp." points d'exp�rience";
			} elseif($acc=="crieur de rue") {
				$xp = 100000;
				if(possede_talent('G�nie')) $xp += floor(0.1*$xp);
				$sql = 'UPDATE principal_tbl SET total = total + '.$xp.' WHERE pseudo="'.$pseudo.'"';
				$req = mysql_query($sql);
				return $xp." points d'exp�rience";
			} elseif($acc=="recruteur imp�rial") {
				$xp = 100000000;
				$bonus = 20;
				if(possede_talent('G�nie')) $xp += floor(0.1*$xp);
				$sql = 'UPDATE principal_tbl SET total = total + '.$xp.', sante_max = sante_max + '.$bonus.', fatigue_max = fatigue_max + '.$bonus.', sante = sante_max, fatigue = fatigue_max WHERE pseudo="'.$pseudo.'"';
				$req = mysql_query($sql);
				return $xp." points d'exp�rience<br />+".$bonus." en sant� et forme";
			} elseif($acc=="f�tard des 2 ans") {
				$xp = 10000;
				if(possede_talent('G�nie')) $xp += floor(0.1*$xp);
				$sql = 'UPDATE principal_tbl SET total = total + '.$xp.' WHERE pseudo="'.$pseudo.'"';
				$req = mysql_query($sql);
				return $xp." points d'exp�rience";
			} elseif($acc=="m�canicien sav") {
				$xp = 1000;
				if(possede_talent('G�nie')) $xp += floor(0.1*$xp);
				$sql = 'UPDATE principal_tbl SET total = total + '.$xp.' WHERE pseudo="'.$pseudo.'"';
				$req = mysql_query($sql);
				return $xp." points d'exp�rience";
			} elseif($acc=="r�parateur") {
				$xp = 1000;
				if(possede_talent('G�nie')) $xp += floor(0.1*$xp);
				$sql = 'UPDATE principal_tbl SET total = total + '.$xp.' WHERE pseudo="'.$pseudo.'"';
				$req = mysql_query($sql);
				return $xp." points d'exp�rience";
			} elseif($acc=="sauveur de dro�de") {
				$xp = 1000;
				if(possede_talent('G�nie')) $xp += floor(0.1*$xp);
				$sql = 'UPDATE principal_tbl SET total = total + '.$xp.' WHERE pseudo="'.$pseudo.'"';
				$req = mysql_query($sql);
				return $xp." points d'exp�rience";
			} elseif($acc=="machine rutilante") {
				$xp = 1000;
				if(possede_talent('G�nie')) $xp += floor(0.1*$xp);
				$sql = 'UPDATE principal_tbl SET total = total + '.$xp.' WHERE pseudo="'.$pseudo.'"';
				$req = mysql_query($sql);
				return $xp." points d'exp�rience";
			} elseif($acc=="chair � canon") {
				$xp = 1000;
				if(possede_talent('G�nie')) $xp += floor(0.1*$xp);
				$sql = 'UPDATE principal_tbl SET total = total + '.$xp.' WHERE pseudo="'.$pseudo.'"';
				$req = mysql_query($sql);
				return $xp." points d'exp�rience";
			} elseif($acc=="revendeur de composants") {
				$xp = 1000;
				if(possede_talent('G�nie')) $xp += floor(0.1*$xp);
				$sql = 'UPDATE principal_tbl SET total = total + '.$xp.' WHERE pseudo="'.$pseudo.'"';
				$req = mysql_query($sql);
				return $xp." points d'exp�rience";
			} elseif($acc=="l'anti-dro�de") {
				$xp = 1000;
				if(possede_talent('G�nie')) $xp += floor(0.1*$xp);
				$sql = 'UPDATE principal_tbl SET total = total + '.$xp.' WHERE pseudo="'.$pseudo.'"';
				$req = mysql_query($sql);
				return $xp." points d'exp�rience";
			}
			
		}
	?>
	</div>
</div>

<?php if($chat=="oui") 	{ if ((date("H")>=21) || (date("H")<=7)) { include("inc_bas_n_c.php"); } else { include("inc_bas_j_c.php"); } } else {	if ((date("H")>=21) || (date("H")<=7)) { include("inc_bas_n.php"); } else { include("inc_bas_j.php"); }	} ?>
<?php include("inc_bas_de_page.php"); ?>	
