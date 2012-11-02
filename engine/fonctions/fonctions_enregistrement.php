<?php
function valeur ($pseudo,$donnee) {
	$sql = 'SELECT valeur FROM enregistreur_tbl WHERE pseudo="'.$pseudo.'" AND donnee="'.$donnee.'"' ;
	$req = mysql_query($sql);
	$res = mysql_num_rows($req);
	if($res==0) return 0;
	else return mysql_result($req,0,valeur);
}
function enregistre($pseudo,$donnee,$valeur) {
	$sql = 'SELECT valeur FROM enregistreur_tbl WHERE pseudo LIKE "'.$pseudo.'" AND donnee="'.$donnee.'"' ;
	$req = mysql_query($sql);
	$res = mysql_num_rows($req);
	if($res==0)
		{
		if($valeur[0] == "+") { $valeur = str_replace("+","",$valeur); if($valeur == "0") return; }
		$sql2 = 'INSERT INTO enregistreur_tbl(pseudo,donnee,valeur) VALUES("'.$pseudo.'","'.$donnee.'","'.$valeur.'")';
		mysql_query($sql2);
		$anciennevaleur = 0;
		}
	else
		{
		if($valeur[0] == "+") { $valeur = str_replace("+","",$valeur) + mysql_result($req,0,valeur); if($valeur == "0") return; }
		$sql2 = 'UPDATE enregistreur_tbl SET valeur="'.$valeur.'" WHERE pseudo="'.$pseudo.'" AND donnee="'.$donnee.'"';
		mysql_query($sql2);
		$anciennevaleur = mysql_result($req,0,valeur);
		}
	if($valeur-$anciennevaleur>0)
		{
		$sql2 = 'SELECT SUM(experience) FROM experiences_tbl WHERE objet="'.$donnee.'" AND valeur<="'.$valeur.'" AND valeur>"'.$anciennevaleur.'"';
		$req2 = mysql_query($sql2);
		$res2 = mysql_num_rows($req2);
		$sql3 = 'SELECT titre FROM titres_tbl WHERE pseudo LIKE "'.$pseudo.'"';
		$req3 = mysql_query($sql3);
		$niveau = mysql_num_rows($req3) + 1;
		$sql4 = 'SELECT total FROM principal_tbl WHERE pseudo LIKE "'.$pseudo.'"';
		$req4 = mysql_query($sql4);
		if (!mysql_num_rows($req4)) {
			echo "Erreur enregistrement $pseudo<br />";
			return false;
		}
		$ancienniveau = strlen(mysql_result($req4,0,total));
		for($i=0;$i!=$niveau-1;$i++) $titres .= mysql_result($req3,$i,titre);
		if($res2>0)
			{
			$gain = floor(mysql_result($req2,0,'SUM(experience)')*$niveau);
			if(ereg("Boureau de travail",$titres) && $donnee=="travail") $gain = $gain*2;
			if(ereg("Génie",$titres)) $gain += floor($gain*(10/100));
			$sql2 = 'UPDATE principal_tbl SET total=total+'.$gain.' WHERE pseudo="'.$pseudo.'"';
			mysql_query($sql2);
			}
		$sql4 = 'SELECT total FROM principal_tbl WHERE pseudo="'.$pseudo.'"';
		$req4 = mysql_query($sql4);
		if(mysql_num_rows($req4) != 0)
			{
			$total = mysql_result($req4,0,total);
			if($total<0)
				{
				$sql4 = 'UPDATE principal_tbl SET total="0" WHERE pseudo="'.$pseudo.'"';
				$req4 = mysql_query($sql4);
				}
			$nouveauniveau = strlen($total);
		
			if($nouveauniveau>$ancienniveau && isset($_SESSION['pseudo']) && $pseudo == $_SESSION['pseudo'])
				{
				affiche_box('Niveau supérieur !','Bravo !<br />Vous évoluez vite.<br /><br />Vous venez de passer au niveau supérieur.<br /><br />Vous êtes maintenant <strong>niveau '.$nouveauniveau.'</strong> !<br /><br />Rendez-vous dans l\'onglet Stats->Expérience ou <a href="engine=lvlup.php">ici</a> afin de choisir un nouveau talent et d\'augmenter votre santé et votre forme de façon permanente !<br /><br />Les niveaux 2, 6 et 10 sont des niveaux paliers : une fois atteint, ils débloquent de nouveaux talents. Attention cependant, car les talents du palier d\'avant ne seront plus disponibles ! Faites donc bien attention à ce que vous choisissez. Vous ne pourrez pas tout prendre !');
				}
			}
		}
	else $gain = 0;
	return $gain;
}

function enregistre_evt($cible,$donnee,$valeur) {
	$sql = 'INSERT INTO evenements_tbl VALUES (NULL,"'.$_SESSION['pseudo'].'","'.$cible.'","'.time().'","'.$donnee.'","'.$valeur.'")';
	mysql_query($sql);
}

function talent($num,$niveau) {
	if($niveau <= 5) // Vers niveaux 2,3,4,5
		{
		if($num==1) return 'Peau d\'acier';
		elseif($num==2) return 'Inépuisable';
		elseif($num==3) return 'Justicier';
		elseif($num==4) return 'Gros bosseur';
		elseif($num==5) return 'Sain et sauf';
		elseif($num==6) return 'Crédivore';
		elseif($num==7) return 'Sans foi ni loi';
		elseif($num==8) return 'Génie';
		else return 'Erreur';
		}
	elseif($niveau <= 9) // Vers niveaux 6,7,8,9
		{
		if($num==1) return 'Impatient';
		elseif($num==2) return 'Hyperactif';
		elseif($num==3) return 'Geek';
		elseif($num==4) return 'Urgentiste';
		elseif($num==5) return 'Peau d\'argent';
		elseif($num==6) return 'Brute';
		elseif($num==7) return 'Rôdeur';
		elseif($num==8) return 'Pickpocket';
		else return 'Erreur';
		}
	elseif($niveau == 10)  // Vers niveaux 10
		{
		if($num==1) return 'Polyvalent';
		elseif($num==2) return 'Peau d\'adaman';
		elseif($num==3) return 'Mentor';
		elseif($num==4) return 'Pilier de bar';
		elseif($num==5) return '';
		elseif($num==6) return '';
		elseif($num==7) return '';
		elseif($num==8) return '';
		else return 'Erreur';
		}
	else return 'Erreur';
}

function description_talent($talent) {
	if($talent=='Peau d\'acier')
		return 'Ce talent consolide de façon considérable votre enveloppe corporelle.<br />En effet, cet entraînement avancé vous fait gagner 10% de santé à chaque niveau au lieu de 5% !<div class="effet"><div class="type1">Effet</div><div class="type2">+<strong>10%</strong> de santé à chaque niveau</div></div>';
	elseif($talent=='Inépuisable')
		return 'Avec ce talent, votre endurance fera un bon de géant.<br />Oubliez vos anciennes habitudes, vous pouvez désormais bénéficier d\'un bonus de 10% en forme à chaque niveau au lieu de 5% !<div class="effet"><div class="type1">Effet</div><div class="type2">+<strong>10%</strong> de forme à chaque niveau</div></div>';
	elseif($talent=='Justicier')
		return 'Vous avez décidé de faire votre propre loi ?<br />Le talent de <strong>'.$talent.'</strong> vous permet de gagner de l\'Expérience en assassinant les malfrats recherchés par la police.<div class="effet"><div class="type1">Effet</div><div class="type2">+<strong>'.((ereg("engine=lvlup.php",$_SERVER['PHP_SELF']))?niveau($_SESSION['pseudo'])*50+1:niveau($_SESSION['pseudo'])).'</strong> xp à chaque recherché tué</div></div>';
	elseif($talent=='Gros bosseur')
		return 'Acharné au boulot, vous aimez que tout soit bien fait.<br />Grâce au talent de <strong>'.$talent.'</strong>, vous doublez l\'Expérience gagnée en travaillant ! (travail passif uniquement)<div class="effet"><div class="type1">Effet</div><div class="type2">+<strong>'.((ereg("engine=lvlup.php",$_SERVER['PHP_SELF']))?niveau($_SESSION['pseudo'])+1:niveau($_SESSION['pseudo'])).'</strong> xp à chaque heure de travail</div></div>';
	elseif($talent=='Sain et sauf')
		return 'L\'As de la survie, c\'est vous !<br />Ce talent vous sera d\'un grand renfort, puisque chaque jour passé sans mourrir vous rapporte de plus en plus d\'Expérience !<div class="effet"><div class="type1">Effet</div><div class="type2">+<strong>'.((ereg("engine=lvlup.php",$_SERVER['PHP_SELF']))?niveau($_SESSION['pseudo'])+1:niveau($_SESSION['pseudo'])).'</strong> xp à chaque nouvelle journée de survie</div></div>';
	elseif($talent=='Crédivore')
		return 'Insatiable et prince de la démesure ?<br />Qui a dit que l\'argent n\'a pas d\'odeur ? Le talent <strong>'.$talent.'</strong> vous permet d\'échanger vos crédits contre de l\'Expérience !<div class="effet"><div class="type1">Effet</div><div class="type2">+<strong>'.((ereg("engine=lvlup.php",$_SERVER['PHP_SELF']))?niveau($_SESSION['pseudo'])+1:niveau($_SESSION['pseudo'])).'</strong> xp pour 10cr dévorés</div></div>'.((ereg("engine=experience.php",$_SERVER['PHP_SELF']))?'<div style="text-align:center;margin-top:5px;"><a style="color:#fff;border-bottom:1px solid #fff;font-weight:bold;" href="engine=credivore.php">Dévorer des crédits</a></div>':'');
	elseif($talent=='Sans foi ni loi')
		return 'Il ne vaut mieux pas rigoler avec vous.<br />Ce talent vous permet d\'achever quiconque sans jamais perdre un brin d\'Expérience. Vous ne compterez plus les cadavres.<div class="effet"><div class="type1">Effet</div><div class="type2">-<strong>0</strong> xp perdu pour chaque meurtre</div></div>';
	elseif($talent=='Génie')
		return 'Une révélation ?<br />Vous parvenez enfin à comprendre comment tout cela fonctionne. Grâce au talent <strong>'.$talent.'</strong>, tous vos gains d\'Expérience sont augmentés de 10% !<div class="effet"><div class="type1">Effet</div><div class="type2">+<strong>10%</strong> d\'xp en plus pour chaque gain d\'xp</div></div>';
	elseif($talent=='Impatient')
		return 'Avec vous, la gloire n\'attends pas !<br />Grâce au talent <strong>'.$talent.'</strong>, votre empressement va vous permettre d\'atteindre directement le prochain niveau.<div class="effet"><div class="type1">Effet</div><div class="type2">+<strong>1</strong> niveau immédiatement</div></div>';
	elseif($talent=='Hyperactif')
		return 'La drogue ne vous fait pas planner : elle vous explose !<br />Le talent hyperactif vous permet de tirer pleinement partie des substances contenues dans le Kronatium : +100% en santé et forme au lieu de 50% !<div class="effet"><div class="type1">Effet</div><div class="type2">Kronatium <strong>2x</strong> plus efficace</div></div>';
	elseif($talent=='Geek')
		return 'La matrice n\'a plus de secret pour vous.<br />Le talent <strong>'.$talent.'</strong> vous procure un savoir et une maîtrise en informatique jusque-là insoupçonnés. Plus aucun firewall ne vous résistera !<div class="effet"><div class="type1">Effet</div><div class="type2">+<strong>50</strong> au maximum d\'informatique</div></div>';
	elseif($talent=='Urgentiste')
		return 'Vous maîtrisez l\'art du Soin<br />Grâce au talent <strong>'.$talent.'</strong>, votre science de la guérison atteint son paroxysme. Vous pourrez développer votre âme altruiste sans limite.<div class="effet"><div class="type1">Effet</div><div class="type2">+<strong>50</strong> au maximum de médecine</div></div>';
	elseif($talent=='Peau d\'argent')
		return 'Aussi résistant qu\'un roc !<br />Plus solide que vous, ça n\'existe pas. Avec le talent <strong>'.$talent.'</strong>, vous n\'aurez plus rien à craindre des petites frappes !<div class="effet"><div class="type1">Effet</div><div class="type2">+<strong>50</strong> au maximum de résistance</div></div>';
	elseif($talent=='Brute')
		return 'Les armes de corps à corps, c\'est votre truc.<br /> Vous êtes une vraie <strong>'.$talent.'</strong>.<div class="effet"><div class="type1">Effet</div><div class="type2">+<strong>50</strong> au maximum de combat</div></div>';
	elseif($talent=='Rôdeur')
		return 'Vous aimez rester tapis dans l\'ombre.<br />Votre kiff, c\'est de voir sans être vu !<div class="effet"><div class="type1">Effet</div><div class="type2">+<strong>50</strong> au maximum de discrétion</div></div>';
	elseif($talent=='Pickpocket')
		return 'Aucun passant ne vous résiste.<br />Vos mains sont toujours dans leurs poches !<div class="effet"><div class="type1">Effet</div><div class="type2">+<strong>50</strong> au maximum de vol</div></div>';
    elseif($talent=='Polyvalent')
		return 'Tous les domaines vous passionnent.<br />Vous désirez toujours aller plus loin, plus haut, plus vite !<div class="effet"><div class="type1">Effet</div><div class="type2">+<strong>25</strong> au maximum de toutes les compétences</div><div class="type2">+<strong>50</strong> en santé et forme</div></div>';
    elseif($talent=='Peau d\'adaman')
		return 'Votre corps subit des mutations.<br />Votre peau et votre système nerveux se transforment, vous devenez... autre chose !<div class="effet"><div class="type1">Effet</div><div class="type2">+<strong>50</strong> au maximum de resistance et de combat</div><div class="type2">+<strong>100</strong> en santé et forme</div></div>';
    elseif($talent=='Mentor')
		return 'Votre parole est d\'or.<br />Les débutants se plient à vos volontés car vous avez quelque chose à leur apprendre.<div class="effet"><div class="type1">Effet</div><div class="type2">Confère la possibilité de distribuer <strong>5000</strong>pts d\'expérience par jour</div></div>'.((ereg("engine=experience.php",$_SERVER['PHP_SELF']))?'<div style="text-align:center;margin-top:5px;"><a style="color:#fff;border-bottom:1px solid #fff;font-weight:bold;" href="engine=donxp.php">Donner de l\'expérience</a></div>':'');
    elseif($talent=='Pilier de bar')
		return 'Nul besoin de souffler.<br />Deux minutes dans un bistrot vous suffisent pour récupérer toutes vos forces !<div class="effet"><div class="type1">Effet</div><div class="type2">Manger ou boire quelque chose remonte toute votre santé, forme, faim et soif.</div></div>';
}

function possede_talent($talent,$pseudo="") {
	$pseudo = ($pseudo=="")?$_SESSION['pseudo']:$pseudo;
	$sql = 'SELECT id FROM titres_tbl WHERE titre LIKE "'.$talent.'" AND pseudo="'.$pseudo.'"';
	$req = mysql_query($sql);
	return mysql_num_rows($req);
}

function verif_accomplissement($acc,$pseudo="") {
			
			if($pseudo=="") $pseudo = $_SESSION['pseudo'];
			
			if ($acc == "haut dignitaire") {
				$sql = 'SELECT id FROM titres_tbl WHERE pseudo= "'.$pseudo.'" AND titre LIKE "'.$acc.'"';
				$req = mysql_query($sql);
				if (mysql_num_rows($req))
					return array(true);
				return array(false);
			}
			
			$sql = 'SELECT id FROM titres_tbl WHERE pseudo= "'.$pseudo.'" AND type="Niveau"';
			$req = mysql_query($sql);
			$niveau = mysql_num_rows($req)+1;
			
			$sql = 'SELECT jours_de_jeu FROM principal_tbl WHERE pseudo= "'.$pseudo.'"';
			$req = mysql_query($sql);
			if (mysql_num_rows($req))
				$jours = mysql_result($req,0,jours_de_jeu);
			
			if($acc=="nouvel arrivant") {
				$sql = 'SELECT donnee FROM enregistreur_tbl WHERE pseudo="'.$pseudo.'" AND (donnee="acc_logement_loue" OR donnee="emploi" OR donnee="acc_mp_envoye")';
				$req = mysql_query($sql);
				$res = mysql_num_rows($req);
				for($i=0;$i<$res;$i++) {
					if(mysql_result($req,$i,donnee)=="emploi") $retour1 = true;
					elseif(mysql_result($req,$i,donnee)=="acc_logement_loue") $retour2 = true;
					elseif(mysql_result($req,$i,donnee)=="acc_mp_envoye") $retour3 = true;
				}
				if($retour1 != true){
					$sql = 'SELECT id FROM principal_tbl WHERE entreprise!="Aucune" AND pseudo="'.$pseudo.'"';
					$req = mysql_query($sql);
					$res = mysql_num_rows($req);
					if($res) $retour1 = true;
				}
				if($retour2 != true){
					$sql = 'SELECT id FROM principal_tbl WHERE ruel!="Aucune" AND pseudo="'.$pseudo.'"';
					$req = mysql_query($sql);
					$res = mysql_num_rows($req);
					if($res) $retour2 = true;
				}
				return array($retour1&&$retour2&&$retour3,$retour1,$retour2,$retour3);
			} elseif($acc=="citoyen privilégié") {
				$sql = 'SELECT id FROM principal_tbl WHERE pseudo="'.$pseudo.'" AND (statut_tmp!="0" OR statut="Compte VIP")';
				$req = mysql_query($sql);
				$res = mysql_num_rows($req);
				if($res) $retour1 = true;
				return array($retour1,$retour1);
			} elseif($acc=="bourreau de travail") {
				$lvl = ($niveau>=3);
				$sql = 'SELECT donnee FROM enregistreur_tbl WHERE pseudo="'.$pseudo.'" AND donnee="travail" AND valeur >= 100';
				$req = mysql_query($sql);
				$res = mysql_num_rows($req);
				if($res) $retour1 = true;
				return array($retour1&&$lvl,$lvl,$retour1);
			} elseif($acc=="fidèle à son poste") {
				$lvl = ($niveau>=5);
				$sql = 'SELECT donnee FROM enregistreur_tbl WHERE pseudo="'.$pseudo.'" AND donnee="travail" AND valeur >= 200';
				$req = mysql_query($sql);
				$res = mysql_num_rows($req);
				if($res) $retour1 = true;
				$sql = 'SELECT donnee FROM enregistreur_tbl WHERE pseudo="'.$pseudo.'" AND donnee="emploi" AND valeur <= 2';
				$req = mysql_query($sql);
				$res = mysql_num_rows($req);
				if($res) $retour2 = true;
				if(possede_talent("polyvalent du travail")) return array(false,$lvl,$retour1,$retour2);
				return array($retour1&&$retour2&&$lvl,$lvl,$retour1,$retour2);
			} elseif($acc=="polyvalent du travail") {
				$lvl = ($niveau>=5);
				$sql = 'SELECT donnee FROM enregistreur_tbl WHERE pseudo="'.$pseudo.'" AND donnee="travail" AND valeur >= 200';
				$req = mysql_query($sql);
				$res = mysql_num_rows($req);
				if($res) $retour1 = true;
				$sql = 'SELECT donnee FROM enregistreur_tbl WHERE pseudo="'.$pseudo.'" AND donnee="emploi" AND valeur >= 5';
				$req = mysql_query($sql);
				$res = mysql_num_rows($req);
				if($res) $retour2 = true;
				if(possede_talent("fidèle à son poste")) return array(false,$lvl,$retour1,$retour2);
				return array($retour1&&$retour2&&$lvl,$lvl,$retour1,$retour2);
			} elseif($acc=="fanatique ambitieux") {
				$lvl = ($jours>=30);
				$sql = 'SELECT id FROM enregistreur_tbl WHERE pseudo="'.$pseudo.'" AND donnee="acc_heures_supp" AND valeur >= 500';
				$req = mysql_query($sql);
				$res = mysql_num_rows($req);
				if($res) $retour1 = true;
				return array($retour1&&$lvl,$lvl,$retour1);
			} elseif($acc=="bosseur actif") {
				$lvl = ($niveau>=3);
				$sql = 'SELECT id FROM enregistreur_tbl WHERE pseudo="'.$pseudo.'" AND donnee="travail actif" AND valeur >= 10';
				$req = mysql_query($sql);
				$res = mysql_num_rows($req);
				if($res) $retour1 = true;
				return array($retour1&&$lvl,$lvl,$retour1);
			} elseif($acc=="actif de la ville") {
				$lvl = ($niveau>=5);
				$sql = 'SELECT id FROM enregistreur_tbl WHERE pseudo="'.$pseudo.'" AND donnee="acc_mp_envoye" AND valeur >= 50';
				$req = mysql_query($sql);
				$res = mysql_num_rows($req);
				if($res) $retour1 = true;
				return array($retour1&&$lvl,$lvl,$retour1);
			} elseif($acc=="ciment de la cité") {
				$lvl = ($jours>=30);
				$sql = 'SELECT id FROM principal_tbl WHERE pseudo="'.$pseudo.'" AND creation >= 10';
				$req = mysql_query($sql);
				$res = mysql_num_rows($req);
				if($res) $retour1 = true;
				return array($retour1&&$lvl,$lvl,$retour1);
			} elseif($acc=="gérant émérite") {
				$lvl = ($niveau>=3);
				$sql = 'SELECT donnee FROM enregistreur_tbl WHERE pseudo="'.$pseudo.'" AND (donnee="entreprise" OR (donnee="travail actif" AND valeur >= 10))';
				$req = mysql_query($sql);
				$res = mysql_num_rows($req);
				for($i=0;$i<$res;$i++) {
					if(mysql_result($req,$i,donnee)=="entreprise") $retour1 = true;
					elseif(mysql_result($req,$i,donnee)=="travail actif") $retour2 = true;
				}
				return array($retour1&&$retour2&&$lvl,$lvl,$retour1,$retour2);
			} elseif($acc=="gros bonnet") {
				$lvl = ($niveau>=5);
				$sql = 'SELECT entreprise,type FROM principal_tbl WHERE pseudo="'.$pseudo.'" AND entreprise != "Aucune"';
				$req = mysql_query($sql);
				$res = mysql_num_rows($req);
				if($res) {
					$entreprise = mysql_result($req,0,entreprise);
					$sql = 'SELECT id FROM e_'.str_replace(" ","_",$entreprise).'_tbl WHERE poste = "'.mysql_result($req,0,type).'" AND type="chef"';
					$req = mysql_query($sql);
					$res = mysql_num_rows($req);
				}
				if(!$res) return array(false,$lvl,false);
				$sql = 'SELECT SUM(chiffre) AS chiffre_affaire FROM chiffre_affaire_tbl WHERE entreprise="'.$entreprise.'"';
				$req = mysql_query($sql);
				$res = mysql_num_rows($req);
				if($res && mysql_result($req,0,chiffre_affaire) >= 25000) $retour1 = true;
				return array($retour1&&$lvl,$lvl,$retour1);
			} elseif($acc=="génie economiste") {
				$lvl = ($jours>=30);
				$sql = 'SELECT entreprise,type FROM principal_tbl WHERE pseudo="'.$pseudo.'" AND entreprise != "Aucune"';
				$req = mysql_query($sql);
				$res = mysql_num_rows($req);
				if($res) {
					$entreprise = mysql_result($req,0,entreprise);
					$sql = 'SELECT id FROM e_'.str_replace(" ","_",$entreprise).'_tbl WHERE poste = "'.mysql_result($req,0,type).'" AND type="chef"';
					$req = mysql_query($sql);
					$res = mysql_num_rows($req);
				} else return array(false,$lvl,false);
				if(!$res) return array(false,$lvl,false);
				$sql = 'SELECT id FROM principal_tbl WHERE entreprise="'.$entreprise.'" AND id!='.$_SESSION['id'];
				$req = mysql_query($sql);
				$res = mysql_num_rows($req);
				if($res >= 10) $retour1 = true;
				$sql = 'SELECT chiffremoyen FROM entreprises_tbl WHERE nom="'.$entreprise.'"';
				$req = mysql_query($sql);
				$res = mysql_num_rows($req);
				if($res && mysql_result($req,0,chiffremoyen) >= 5000) $retour2 = true;
				$sql = 'SELECT id FROM e_'.str_replace(" ","_",$entreprise).'_tbl WHERE type="directeur" AND nbreactuel > 0';
				$req = mysql_query($sql);
				$res = mysql_num_rows($req);
				if($res) $retour3 = true;
				return array($retour1&&$retour2&&$retour3&&$lvl,$lvl,$retour1,$retour2,$retour3);
			} elseif($acc=="petite frappe") {
				$lvl = ($niveau>=3);
				$sql = 'SELECT donnee FROM enregistreur_tbl WHERE pseudo="'.$pseudo.'" AND donnee="acc_degats_donnes"';
				$req = mysql_query($sql);
				$res = mysql_num_rows($req);
				if($res) $retour1 = true;
				if(possede_talent("espoir de la cité") || possede_talent("fervent imperialiste") || possede_talent("haut fonctionnaire") || possede_talent("arme de l'impérium")) return array(false,$lvl,$retour1);
				return array($retour1&&$lvl,$lvl,$retour1);
			} elseif($acc=="danger public") {
				$lvl = ($niveau>=5);
				$sql = 'SELECT donnee FROM enregistreur_tbl WHERE pseudo="'.$pseudo.'" AND donnee="acc_meurtre_donne" AND valeur >= 5';
				$req = mysql_query($sql);
				$res = mysql_num_rows($req);
				if($res) $retour1 = true;
				if(possede_talent("espoir de la cité") || possede_talent("fervent imperialiste") || possede_talent("haut fonctionnaire") || possede_talent("arme de l'impérium")) return array(false,$lvl,$retour1);
				return array($retour1&&$lvl,$lvl,$retour1);
			} elseif($acc=="ennemi de l'impérium") {
				$lvl = ($jours>=30);
				$sql = 'SELECT donnee FROM enregistreur_tbl WHERE pseudo="'.$pseudo.'" AND donnee="acc_meurtre_donne" AND valeur >= 50';
				$req = mysql_query($sql);
				$res = mysql_num_rows($req);
				if($res) $retour1 = true;
				if(possede_talent("espoir de la cité") || possede_talent("fervent imperialiste") || possede_talent("haut fonctionnaire") || possede_talent("arme de l'impérium")) return array(false,$lvl,$retour1);
				return array($retour1&&$lvl,$lvl,$retour1);
			} elseif($acc=="espoir de la cité") {
				$lvl = ($niveau>=3);
				$sql = 'SELECT donnee FROM enregistreur_tbl WHERE pseudo="'.$pseudo.'" AND donnee="acc_travail_oi" AND valeur >= 10';
				$req = mysql_query($sql);
				$res = mysql_num_rows($req);
				if($res) $retour1 = true;
				if(possede_talent("petite frappe") || possede_talent("danger public") || possede_talent("ennemi de l'impérium")) return array(false,$lvl,$retour1);
				return array($retour1&&$lvl,$lvl,$retour1);
			} elseif($acc=="fervent impérialiste") {
				$lvl = ($niveau>=5);
				$sql = 'SELECT donnee FROM enregistreur_tbl WHERE pseudo="'.$pseudo.'" AND donnee="acc_travail_oi" AND valeur >= 50';
				$req = mysql_query($sql);
				$res = mysql_num_rows($req);
				if($res) $retour1 = true;
				if(possede_talent("petite frappe") || possede_talent("danger public") || possede_talent("ennemi de l'impérium")) return array(false,$lvl,$retour1);
				return array($retour1&&$lvl,$lvl,$retour1);
			} elseif($acc=="haut fonctionnaire") {
				$lvl = ($jours>=30);
				$sql = 'SELECT entreprise,type FROM principal_tbl WHERE id='.$_SESSION['id'];
				$req = mysql_query($sql);
				$res = mysql_num_rows($req);
				if(!$res) return array(false,$lvl,false);
				$entreprise = mysql_result($req,0,entreprise);
				$poste = mysql_result($req,0,type);
				if(!in_array(strtolower($entreprise),liste_OI())) return array(false,$lvl,false);
				$sql = 'SELECT id FROM e_'.str_replace(" ","_",$entreprise).'_tbl WHERE (type="chef" AND poste="'.$poste.'") OR poste="Conseiller Imperial"';
				$req = mysql_query($sql);
				$res = mysql_num_rows($req);
				if($res) $retour1 = true;
				if(possede_talent("arme de l'impérium") || possede_talent("petite frappe") || possede_talent("danger public") || possede_talent("ennemi de l'impérium")) return array(false,$lvl,$retour1);
				return array($retour1&&$lvl,$lvl,$retour1);
			} elseif($acc=="arme de l'impérium") {
				$lvl = ($jours>=30);
				$sql = 'SELECT donnee FROM enregistreur_tbl WHERE pseudo="'.$pseudo.'" AND donnee="arrestation" AND valeur >= 100';
				$req = mysql_query($sql);
				$res = mysql_num_rows($req);
				if($res) $retour1 = true;
				if(possede_talent("haut fonctionnaire") || possede_talent("petite frappe") || possede_talent("danger public") || possede_talent("ennemi de l'impérium")) return array(false,$lvl,$retour1);
				return array($retour1&&$lvl,$lvl,$retour1);
			} elseif($acc=="bonne âme") {
				$lvl = ($niveau>=3);
				$sql = 'SELECT id FROM transferts_tbl WHERE donneur="'.$pseudo.'" AND receveur != "Dreadcast" AND operation LIKE "Don %"';
				$req = mysql_query($sql);
				$res = mysql_num_rows($req);
				if($res) $retour1 = true;
				$sql = 'SELECT donnee FROM enregistreur_tbl WHERE pseudo="'.$pseudo.'" AND donnee="acc_soins_donnes"';
				$req = mysql_query($sql);
				$res = mysql_num_rows($req);
				if($res) $retour2 = true;
				if(possede_talent("apprenti picpocket") || possede_talent("escroc") || possede_talent("arnaqueur")) return array(false,$lvl,$retour1,$retour2);
				return array($retour1&&$retour2&&$lvl,$lvl,$retour1,$retour2);
			} elseif($acc=="altruiste") {
				$lvl = ($niveau>=5);
				$sql = 'SELECT id FROM transferts_tbl WHERE donneur="'.$pseudo.'" AND receveur != "Dreadcast" AND operation LIKE "Don %"';
				$req = mysql_query($sql);
				$res = mysql_num_rows($req);
				if($res >= 15) $retour1 = true;
				$sql = 'SELECT donnee FROM enregistreur_tbl WHERE pseudo="'.$pseudo.'" AND donnee="acc_soins_donnes" AND valeur >= 15';
				$req = mysql_query($sql);
				$res = mysql_num_rows($req);
				if($res) $retour2 = true;
				$sql = 'SELECT donnee FROM enregistreur_tbl WHERE pseudo="'.$pseudo.'" AND donnee="protection"';
				$req = mysql_query($sql);
				$res = mysql_num_rows($req);
				if($res) $retour3 = true;
				if(possede_talent("apprenti picpocket") || possede_talent("escroc") || possede_talent("arnaqueur")) return array(false,$lvl,$retour1,$retour2,$retour3);
				return array($retour1&&$retour2&&$retour3&&$lvl,$lvl,$retour1,$retour2,$retour3);
			} elseif($acc=="philanthrope") {
				$lvl = ($jours>=30);
				$sql = 'SELECT id FROM transferts_tbl WHERE donneur="'.$pseudo.'" AND receveur != "Dreadcast" AND operation LIKE "Don %"';
				$req = mysql_query($sql);
				$res = mysql_num_rows($req);
				if($res >= 100) $retour1 = true;
				$sql = 'SELECT donnee FROM enregistreur_tbl WHERE pseudo="'.$pseudo.'" AND donnee="acc_soins_donnes" AND valeur >= 100';
				$req = mysql_query($sql);
				$res = mysql_num_rows($req);
				if($res) $retour2 = true;
				$sql = 'SELECT donnee FROM enregistreur_tbl WHERE pseudo="'.$pseudo.'" AND donnee="protection" AND valeur >= 30';
				$req = mysql_query($sql);
				$res = mysql_num_rows($req);
				if($res) $retour3 = true;
				if(possede_talent("apprenti picpocket") || possede_talent("escroc") || possede_talent("arnaqueur")) return array(false,$lvl,$retour1,$retour2,$retour3);
				return array($retour1&&$retour2&&$retour3&&$lvl,$lvl,$retour1,$retour2,$retour3);
			} elseif($acc=="apprenti picpocket") {
				$lvl = ($niveau>=3);
				$sql = 'SELECT id FROM principal_tbl WHERE id="'.$_SESSION['id'].'" AND vol > 0';
				$req = mysql_query($sql);
				$res = mysql_num_rows($req);
				if($res) $retour1 = true;
				if(possede_talent("bonne âme") || possede_talent("altruiste") || possede_talent("philanthrope")) return array(false,$lvl,$retour1);
				return array($retour1&&$lvl,$lvl,$retour1);
			} elseif($acc=="escroc") {
				$lvl = ($niveau>=5);
				$sql = 'SELECT donnee FROM enregistreur_tbl WHERE pseudo="'.$pseudo.'" AND donnee="acc_vols_donnes" AND valeur >= 20';
				$req = mysql_query($sql);
				$res = mysql_num_rows($req);
				if($res) $retour1 = true;
				if(possede_talent("bonne âme") || possede_talent("altruiste") || possede_talent("philanthrope")) return array(false,$lvl,$retour1);
				return array($retour1&&$lvl,$lvl,$retour1);
			} elseif($acc=="arnaqueur") {
				$lvl = ($jours>=30);
				$sql = 'SELECT donnee FROM enregistreur_tbl WHERE pseudo="'.$pseudo.'" AND donnee="acc_vols_donnes" AND valeur >= 100';
				$req = mysql_query($sql);
				$res = mysql_num_rows($req);
				if($res) $retour1 = true;
				$sql = 'SELECT id FROM casiers_tbl WHERE pseudo="'.$pseudo.'"';
				$req = mysql_query($sql);
				$res = mysql_num_rows($req);
				if($res) $retour2 = true;
				if(possede_talent("bonne âme") || possede_talent("altruiste") || possede_talent("philanthrope")) return array(false,$lvl,$retour1,$retour2);
				return array($retour1&&$retour2&&$lvl,$lvl,$retour1,$retour2);
			} elseif($acc=="militant") {
				$lvl = ($niveau>=3);
				$sql = 'SELECT id FROM cercles_tbl WHERE pseudo="'.$pseudo.'"';
				$req = mysql_query($sql);
				$res = mysql_num_rows($req);
				if($res) $retour1 = true;
				return array($retour1&&$lvl,$lvl,$retour1);
			} elseif($acc=="activiste") {
				$lvl = ($niveau>=5);
				$sql = 'SELECT donnee FROM enregistreur_tbl WHERE pseudo="'.$pseudo.'" AND donnee="acc_dons_cercle" AND valeur >= 1000';
				$req = mysql_query($sql);
				$res = mysql_num_rows($req);
				if($res) $retour1 = true;
				$sql = 'SELECT donnee FROM enregistreur_tbl WHERE pseudo="'.$pseudo.'" AND donnee="acc_comms_cercle" AND valeur >= 5';
				$req = mysql_query($sql);
				$res = mysql_num_rows($req);
				if($res) $retour2 = true;
				return array($retour1&&$retour2&&$lvl,$lvl,$retour1,$retour2);
			} elseif($acc=="leader charismatique") {
				$lvl = ($jours>=30);
				$sql = 'SELECT cercle,poste FROM cercles_tbl WHERE pseudo="'.$pseudo.'"';
				$req = mysql_query($sql);
				$res = mysql_num_rows($req);
				if(!$res) return array(false,$lvl,false,false,false);
				$cercle = mysql_result($req,0,cercle);
				$poste = mysql_result($req,0,poste);
				$sql = 'SELECT id FROM c_'.str_replace(" ","_",$cercle).'_tbl WHERE poste="'.$poste.'" AND bdd="tout"';
				$req = mysql_query($sql);
				$res = mysql_num_rows($req);
				if($res) $retour1 = true;
				$sql = 'SELECT id FROM cerclesnews_tbl WHERE cercle="'.$cercle.'"';
				$req = mysql_query($sql);
				$res = mysql_num_rows($req);
				if($res>5) $retour2 = true;
				$sql = 'SELECT id FROM cercles_tbl WHERE cercle="'.$cercle.'"';
				$req = mysql_query($sql);
				$res = mysql_num_rows($req);
				if($res>15) $retour3 = true;
				return array($retour1&&$retour2&&$retour3&&$lvl,$lvl,$retour1,$retour2,$retour3);
			} elseif($acc=="propriétaire terrien") {
				$lvl = ($niveau>=3);
				$sql = 'SELECT id FROM proprietaire_tbl WHERE pseudo="'.$pseudo.'"';
				$req = mysql_query($sql);
				$res = mysql_num_rows($req);
				if($res) $retour1 = true;
				return array($retour1&&$lvl,$lvl,$retour1);
			} elseif($acc=="bailleur") {
				$lvl = ($niveau>=5);
				$sql = 'SELECT id FROM proprietaire_tbl WHERE pseudo="'.$pseudo.'"';
				$req = mysql_query($sql);
				$res = mysql_num_rows($req);
				if($res >= 5) $retour1 = true;
				return array($retour1&&$lvl,$lvl,$retour1);
			} elseif($acc=="fortune immobilière") {
				$lvl = ($jours>=30);
				$sql = 'SELECT J.id FROM principal_tbl J, proprietaire_tbl P WHERE P.num = J.numl AND P.rue = J.ruel AND P.pseudo="'.$pseudo.'"';
				$req = mysql_query($sql);
				$res = mysql_num_rows($req);
				if($res >= 20) $retour1 = true;
				return array($retour1&&$lvl,$lvl,$retour1);
			} elseif($acc=="initié") {
				$lvl = ($niveau>=3);
				$comps = liste_competences();
				$where = $comps[0].'>=40';
				for($i=1;$i<count($comps);$i++) $where .= ' OR '.$comps[$i].'>=40';
				$sql = 'SELECT id FROM principal_tbl WHERE pseudo="'.$pseudo.'" AND ('.$where.')';
				$req = mysql_query($sql);
				$res = mysql_num_rows($req);
				if($res) $retour1 = true;
				return array($retour1&&$lvl,$lvl,$retour1);
			} elseif($acc=="expert") {
				$lvl = ($niveau>=5);
				$comps = liste_competences();
				$where = $comps[0].'>=100';
				for($i=1;$i<count($comps);$i++) $where .= ' OR '.$comps[$i].'>=100';
				$sql = 'SELECT id FROM principal_tbl WHERE pseudo="'.$pseudo.'" AND ('.$where.')';
				$req = mysql_query($sql);
				$res = mysql_num_rows($req);
				if($res) $retour1 = true;
				return array($retour1&&$lvl,$lvl,$retour1);
			} elseif($acc=="spécialiste") {
				$lvl = ($jours>=30);
				$comps = liste_competences();
				$where = $comps[0].'>=150';
				for($i=1;$i<count($comps);$i++) $where .= ' OR '.$comps[$i].'>=150';
				$sql = 'SELECT id FROM principal_tbl WHERE pseudo="'.$pseudo.'" AND ('.$where.')';
				$req = mysql_query($sql);
				$res = mysql_num_rows($req);
				if($res) $retour1 = true;
				return array($retour1&&$lvl,$lvl,$retour1);
			} elseif($acc=="promeneur bavard") {
				$sql = 'SELECT id FROM principal_tbl WHERE parrain="'.$pseudo.'"';
				$req = mysql_query($sql);
				$res = mysql_num_rows($req);
				if($res >= 3) $retour1 = true;
				return array($retour1,$retour1);
			} elseif($acc=="crieur de rue") {
				$sql = 'SELECT pseudo, statut FROM principal_tbl WHERE parrain="'.$pseudo.'"';
				$req = mysql_query($sql);
				$res = mysql_num_rows($req);
				if($res >= 10) $retour1 = true;
				$count = 5;
				while ($infos = mysql_fetch_assoc($req)) {
					if (niveau($infos['pseudo']) >= 3) {
						$count--;
						if (!$count) {
							$retour2 = true;
						}
					}
					if ($infos['statut'] == "Gold" || $infos['statut'] == "Platinium")
						$retour3 = true;
				}
				return array($retour1&&$retour2&&$retour3,$retour1,$retour2,$retour3);
			} elseif($acc=="recruteur impérial") {
				$sql = 'SELECT pseudo, statut FROM principal_tbl WHERE parrain="'.$pseudo.'"';
				$req = mysql_query($sql);
				$res = mysql_num_rows($req);
				if($res >= 30) $retour1 = true;
				$count = 10;
				while ($infos = mysql_fetch_assoc($req)) {
					if (niveau($infos['pseudo']) >= 6) {
						$count--;
						if (!$count) {
							$retour2 = true;
						}
					}
					if ($infos['statut'] == "Gold" || $infos['statut'] == "Platinium")
						$retour3 = true;
				}
				return array($retour1&&$retour2&&$retour3,$retour1,$retour2,$retour3);
			} elseif($acc=="fêtard des 2 ans") {
				if (valeur($pseudo, 'anniversaire2ans'))
					$retour1 = true;
				return array($retour1,$retour1);
			} elseif($acc=="mécanicien sav") {
				if (valeur($pseudo, 'attaque droide fou'))
					$retour1 = true;
				return array($retour1,$retour1);
			} elseif($acc=="réparateur") {
				if (valeur($pseudo, 'reparation droide fou'))
					$retour1 = true;
				return array($retour1,$retour1);
			} elseif($acc=="sauveur de droïde") {
				if (valeur($pseudo, 'reparation droide fou') >= 5)
					$retour1 = true;
				return array($retour1,$retour1);
			} elseif($acc=="machine rutilante") {
				if (valeur($pseudo, 'etre repare'))
					$retour1 = true;
				return array($retour1,$retour1);
			} elseif($acc=="chair à canon") {
				if (valeur($pseudo, 'acc_degats_paracelse'))
					$retour1 = true;
				return array($retour1,$retour1);
			} elseif($acc=="revendeur de composants") {
				if (valeur($pseudo, 'acc_degats_paracelse') >= 2000)
					$retour1 = true;
				return array($retour1,$retour1);
			} 
			
			
		}

?>
