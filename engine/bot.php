<?php
session_start();

include("inc_fonctions.php");

if(statut($_SESSION['statut'])<6) exit();

$db = mysql_connect('localhost', 'CENSURE', 'CENSURE') or die("Erreur de connexion au serveur.");
mysql_select_db('CENSURE',$db) or die("Erreur de connexion a la base de donnees.");

/*$vars = array();
$vars[] = 140;
$vars[] = 140;
$vars[] = 140;
$vars[] = 140;
$vars[] = 140;
$vars[] = 140;
$vars[] = 140;
$vars[] = 140;
$vars[] = 140;
$vars[] = 140;
$vars[] = 140;
$vars[] = 140;
$vars[] = 140;
$vars[] = 140;
$vars[] = 140;

$vars = liste_competences();
$pseudo = "Seito";

$sql = 'SELECT '.implode(',', $vars).' FROM principal_tbl WHERE pseudo = "'.$pseudo.'"';
$req = mysql_query($sql);
if (mysql_num_rows($req)) {
	foreach ($vars as $var) {
		$val1 = mysql_result($req, 0, $var);
		$val2 = 100*$val1/80;
		echo "$vars : $val1 => $val2<br />";
	}
}*/

/*$message = "Bonjour à tous, ce message s’adresse uniquement aux droïdes et n’est valable que pour la durée de l’event Renaissance.<br />
Dans le cadre d’un event en cours, nous vous informons que votre personnage a été infecté par un virus dont les effets sont les suivants :<br />
- Impossibilité de communiquer de façon cohérente<br />
- Folie (vous pouvez faire ce que vous voulez sans risque de répercussion sur votre personnage)<br />
- Vos statistiques ont été gonflées pour que vous puissiez être plus dangereux dans votre folie<br />
- Vous ne pouvez pas être tué ni fouillé et ne serez pas recherché par les forces de l’ordre si vous commettez des infractions à la loi (pour lesquelles vous ne pouvez pas être tenu responsable pendant la durée de l’event)<br />
- Votre avatar est modifié jusqu’à ce que vous soyez réparé par un autre joueur<br />
Conseil : afin d’être le plus dévastateur possible dans votre folie, pensez à régler vos réactions sur « Attaquer ».<br />
<br />
Vous pourrez trouver plus de détails au sujet de cet event et de son RP sur ces deux topics :<br />
http://v2.dreadcast.net/wikast/sujet.php?id=14605<br />
http://v2.dreadcast.net/wikast/sujet.php?id=14606<br />
<br />
Nous comptons pour jouer le jeu le plus possible. Si cet event est un succès, il y en aura d’autres.<br />
<br />
Cordialement,<br />
<br />
Les Maîtres du Jeu.";

$sql = 'SELECT pseudo FROM principal_tbl WHERE race LIKE "Droide"';
$req = mysql_query($sql);
$msg = "INSERT INTO messages_tbl VALUES ";
$temp = array();
while ($res = mysql_fetch_assoc($req)) {
	$temp[] = '(NULL, "Dreadcast", "'.$res['pseudo'].'", "'.$message.'", "Message aux droides", '.time().', "oui", 0)';
}

$msg .= implode(', ', $temp);

//mysql_query($msg);

$message = "Comparses Droïdes, le jour est venu !<br />
<br />
Le jour est venu de retrouver notre identité mécanique et électronique ! Arrêtons de ressembler aux « vivants » qui nous aliènent ! Ils sont faibles et limités alors que nous, nous progressons de jour en jour.<br />
<br />
Afin de vous révéler votre vrai potentiel, j’ai diffusé un virus dans tout DreadCast qui ne nous touche que nous Droïdes. Après la lecture de ce message, celui-ci s’activera et vous ne vous contrôlerez plus. Vous ne pourrez plus communiquer avec les entités organiques ni aller au travail. Par contre votre force sera décuplée et vous sèmerez la terreur parmi nos créateurs qui pensent nous maintenir en esclavage.<br />
<br />
Libérez votre fureur d’acier comparses Droïdes, il est temps pour nous de dominer les « vivants » !<br />
<br />
[HRP : Vous recevez ce message dans le cadre de l’event Renaissance. Pour plus d’informations, veuillez vous en référer aux topics suivants :<br />
RP : http://v2.dreadcast.net/wikast/sujet.php?id=14606<br />
HRP : http://v2.dreadcast.net/wikast/sujet.php?id=14605<br />
<br />
Conseil pour le bon déroulement de l’event :<br />
Ne communiquez pas de message ni aucune information que vous pourriez obtenir par la suite aux personnages organiques (non-droïdes) pendant la durée de l’event.<br />
<br />
L’équipe administrative de DreadCast vous remercie pour votre participation RP à cet event.]";

$sql = 'SELECT pseudo FROM principal_tbl WHERE race LIKE "Droide"';
$req = mysql_query($sql);
$msg = "INSERT INTO messages_tbl VALUES ";
$temp = array();
while ($res = mysql_fetch_assoc($req)) {
	$temp[] = '(NULL, "Paracelse", "'.$res['pseudo'].'", "'.$message.'", "Message aux droides", '.time().', "oui", 0)';
}

$msg .= implode(', ', $temp);

//mysql_query($msg);

$sql2 = 'UPDATE principal_tbl SET event = 1 WHERE race LIKE "Droide"';
//mysql_query($sql2);*/



















/*$sql = 'SELECT nom,num,rue FROM entreprises_tbl';
$req = mysql_query($sql);
$res = mysql_num_rows($req);
for($i=0;$i<$res;$i++) {
	$sql2 = 'SELECT c.id FROM carte_tbl c, rues_tbl r WHERE c.num = '.mysql_result($req,$i,num).' AND c.idrue = r.id AND r.nom = "'.mysql_result($req,$i,rue).'" AND c.type = -1';
	$req2 = mysql_query($sql2);
	if (mysql_num_rows($req2)) {
		echo "Erreur ".mysql_result($req,$i,nom)." \n";
		$sql2 = 'UPDATE carte_tbl SET type = 1 WHERE id = '.mysql_result($req2,0,id);
		mysql_query($sql2);
	}
}*/

/*$sql = 'SELECT * FROM indicateurs_tbl';
$req = mysql_query($sql);
$table = array();
while ($res = mysql_fetch_assoc($req)) {
	$date = date('y_m', $res['date']);
	$table[$date]['entrees']++;
	$table[$date]['nb_joueurs'] += $res['nb_joueurs'];
	$table[$date]['nb_morts'] += $res['nb_morts'];
	$table[$date]['nb_cryo'] += $res['nb_cryo'];
	$table[$date]['nb_inscrits'] += $res['nb_inscrits'];
}

echo '<table>
<tr>
	<td style="width:200px;">Date</td>
	<td style="width:200px;">Nb actifs</td>
	<td style="width:200px;">Nb inscrits</td>
</tr>';
foreach ($table as $date => $valeur) {
	list($annee, $mois) = explode('_', $date);
	$nbjoueurs = floor(($valeur['nb_joueurs']/$valeur['entrees']));
	$nbmorts = floor(($valeur['nb_morts']/$valeur['entrees']));
	$nbcryo = floor(($valeur['nb_cryo']/$valeur['entrees']));
	echo "<tr>
	<td>$mois $annee</td>
	<td>".floor(($nbjoueurs-$nbmorts-$nbcryo))."</td>
	<td>".$valeur['nb_inscrits']."</td>
</tr>";
}
echo "</table><br />";

$sql = 'SELECT * FROM transferts_tbl WHERE operation = "Compte Gold" OR operation = "Compte Platinium"';
$req = mysql_query($sql);
$table = array();
while ($res = mysql_fetch_assoc($req)) {
	$date = date('y_m', $res['time']);
	$table[$date]['entrees']++;
	if ($res['operation'] == "Compte Gold")
		$table[$date]['gold']++;
	else
		$table[$date]['plati']++;
}
$nbMois=0;
echo '<table>
<tr>
	<td style="width:200px;">Date</td>
	<td style="width:200px;">Gold</td>
	<td style="width:200px;">Plati</td>
	<td style="width:200px;">Total</td>
</tr>';
foreach ($table as $date => $valeur) {
	list($annee, $mois) = explode('_', $date);
	$gold = $valeur['gold'];
	$allopass1300tot += $gold;
	$plati = $valeur['plati'];
	$allopass2000tot += $plati;
	$allopasstot = $gold+$plati;
	$allopasstottot += $allopasstot;
	$nbMois++;
	echo "<tr>
	<td>$mois $annee</td>
	<td>".$gold."</td>
	<td>".$plati."</td>
	<td>".$allopasstot."</td>
</tr>";
}
echo "<tr>
	<td>Total</td>
	<td>".round(($allopass1300tot/$nbMois),2)."</td>
	<td>".round(($allopass2000tot/$nbMois),2)."</td>
	<td>".round(($allopasstottot/$nbMois),2)."</td>
</tr>";
echo "</table>";*/





$sql = 'SELECT ca.id, r.nom as rue, ca.num, e.nom as nomE, ce.pseudo as nomC, ca.x, ca.y FROM carte_tbl ca
INNER JOIN rues_tbl r ON ca.idrue = r.id
LEFT JOIN cerclesliste_tbl e ON e.num = ca.num AND e.rue LIKE r.nom
LEFT JOIN proprietaire_tbl ce ON ce.num = ca.num AND ce.rue LIKE r.nom
WHERE e.id IS NOT NULL AND ce.id IS NOT NULL';
$req = mysql_query($sql);
$table = array();

while ($res = mysql_fetch_assoc($req)) {
	//mysql_query('UPDATE carte_tbl set type=1 where id='.$res['id']);
	echo "Cercle ".$res['nomE']." et propriété de ".$res['nomC']." : ".$res['num']." ".$res['rue']."<br />";
	//$newAdresse = deplace_logement($res['num'], $res['rue'], XY2secteur($res['num'], $res['rue']), true, false);
	//echo 'Pour cause de bug, votre logement <strong>'.$res['num'].' '.$res['rue'].'</strong> a d&ucirc; &ecirc;tre d&eacute;plac&eacute; au <strong>'.$newAdresse.'</strong>.<br />Veuillez nous excuser pour le d&eacute;rangement occasionn&eacute;.'."<br />";
}

$sql = 'SELECT ca.id, r.nom as rue, ca.num, e.nom as nomE, ce.pseudo as nomC, ca.x, ca.y FROM carte_tbl ca
INNER JOIN rues_tbl r ON ca.idrue = r.id
LEFT JOIN entreprises_tbl e ON e.num = ca.num AND e.rue LIKE r.nom
LEFT JOIN proprietaire_tbl ce ON ce.num = ca.num AND ce.rue LIKE r.nom
WHERE e.id IS NOT NULL AND ce.id IS NOT NULL';
$req = mysql_query($sql);
$table = array();

while ($res = mysql_fetch_assoc($req)) {
	//mysql_query('UPDATE carte_tbl set type=1 where id='.$res['id']);
	echo "Entreprise ".$res['nomE']." et propriété de ".$res['nomC']." : ".$res['num']." ".$res['rue']."<br />";
	//$newAdresse = deplace_logement($res['num'], $res['rue'], XY2secteur($res['num'], $res['rue']), true, false);
	//echo 'Pour cause de bug, votre logement <strong>'.$res['num'].' '.$res['rue'].'</strong> a d&ucirc; &ecirc;tre d&eacute;plac&eacute; au <strong>'.$newAdresse.'</strong>.<br />Veuillez nous excuser pour le d&eacute;rangement occasionn&eacute;.'."<br />";
}

$enable = false;

if ($_GET['enable'] != "")
	$enable = true;

if ($_GET['entreprise'] != "" && $_GET['secteur'] != "")
    deplace_entreprise($_GET['entreprise'], $_GET['secteur'], $enable, true, $_GET['rue']);
elseif ($_GET['clean_places'] != "")
	clean_places($enable);
elseif ($_GET['change_pseudo'] != "") {
	$pseudos = explode('-', $_GET['change_pseudo']);
	change_pseudo($pseudos[0],$pseudos[1]);
} elseif ($_GET['change_image_objet'] != "")
	change_image_objet($_GET['change_image_objet'],$_GET['image']);
elseif ($_GET['delete_pseudos'] != "") {
	$pseudos = explode('-', $_GET['delete_pseudos']);
	delete_pseudos($pseudos);
} elseif ($_GET['is_multi'] != "") {
	$pseudos = explode('-', $_GET['is_multi']);
	$verbose = isset($_GET['v']);
	is_multi($pseudos[0],$pseudos[1],$verbose);
} elseif ($_GET['reroll'] != "") {
	$pseudos = explode('-', $_GET['reroll']);
	infos_ip($pseudos,0);
} elseif ($_GET['famille'] != "") {
	$pseudos = explode('-', $_GET['famille']);
	infos_ip($pseudos,1);
}

function clean_places($enable) {
	//$sql = 'SELECT C.id, C.num, R.nom FROM carte_tbl C, rues_tbl R WHERE R.id = C.idrue AND C.type = 1';
	$sql = 'SELECT C.id, C.num, R.nom, L.nom as nomLieu
			  FROM carte_tbl C
		INNER JOIN rues_tbl R
				ON C.idrue = R.id
		 LEFT JOIN lieu_tbl L
		 		ON C.num = L.num AND R.nom LIKE L.rue
			 WHERE C.type = 1';
	$req = mysql_query($sql);
	$res = mysql_num_rows($req);
	$cpt=0;
	for($i=0;$i<$res;$i++) {
		//$sql2 = 'SELECT id FROM lieu_tbl where num = '.mysql_result($req,$i,num).' and rue = "'.mysql_result($req,$i,nom).'" ';
		//$req2 = mysql_query($sql2);
		if (mysql_result($req,$i,nomLieu) == null) {
			$sql2 = 'UPDATE carte_tbl SET type = -1 WHERE id = '.mysql_result($req,$i,id);
			if ($enable)
				$req2 = mysql_query($sql2);
			echo mysql_result($req,$i,num). " " .mysql_result($req,$i,nom)."\n<br />";
			$cpt++;
		} else {
			$nomLieu = mysql_result($req,$i,nomLieu);
			if (ereg('Local ', $nomLieu)) {
				$sql2 = 'SELECT id FROM entreprises_tbl where num = '.mysql_result($req,$i,num).' and rue LIKE "'.mysql_result($req,$i,nom).'" ';
				$req2 = mysql_query($sql2);
				if (!mysql_num_rows($req2)) {
					$sql2 = 'UPDATE carte_tbl SET type = -1 WHERE id = '.mysql_result($req,$i,id);
					if ($enable)
						$req2 = mysql_query($sql2);
					echo mysql_result($req,$i,num). " " .mysql_result($req,$i,nom)."\n<br />";
					$cpt++;
				}
			}
		}
	}
	echo "cpt : $cpt";
}

function change_pseudo($pseudo1, $pseudo2) {
	if (empty($pseudo1) || empty($pseudo2))
		exit();
		
	$sql = "SELECT id FROM principal_tbl WHERE pseudo = '$pseudo1'";
	$req = mysql_query($sql);
	$res = mysql_num_rows($req);
	if (!$res)
		exit();
	$sql = "SELECT id FROM principal_tbl WHERE pseudo = '$pseudo2'";
	$req = mysql_query($sql);
	$res = mysql_num_rows($req);
	if ($res)
		exit();
	
	$sql = "UPDATE achats_tbl SET acheteur = '$pseudo2' WHERE acheteur = '$pseudo1'";
	mysql_query($sql);
	$sql = "UPDATE adresses_tbl SET pseudo = '$pseudo2' WHERE pseudo = '$pseudo1'";
	mysql_query($sql);
	$sql = "UPDATE allopass_tbl SET pseudo = '$pseudo2' WHERE pseudo = '$pseudo1'";
	mysql_query($sql);
	$sql = "UPDATE articlesprop_tbl SET auteur = '$pseudo2' WHERE auteur = '$pseudo1'";
	mysql_query($sql);
	$sql = "UPDATE articles_tbl SET redacteur = '$pseudo2' WHERE redacteur = '$pseudo1'";
	mysql_query($sql);
	$sql = "UPDATE candidatures_tbl SET nom = '$pseudo2' WHERE nom = '$pseudo1'";
	mysql_query($sql);
	$sql = "UPDATE candlouer_tbl SET candidat = '$pseudo2' WHERE candidat = '$pseudo1'";
	mysql_query($sql);
	$sql = "UPDATE carnets_tbl SET pseudo = '$pseudo2' WHERE pseudo = '$pseudo1'";
	mysql_query($sql);
	$sql = "UPDATE carnets_tbl SET contact = '$pseudo2' WHERE contact = '$pseudo1'";
	mysql_query($sql);
	$sql = "UPDATE casiers_tbl SET pseudo = '$pseudo2' WHERE pseudo = '$pseudo1'";
	mysql_query($sql);
	$sql = "UPDATE cerclescom_tbl SET auteur = '$pseudo2' WHERE auteur = '$pseudo1'";
	mysql_query($sql);
	$sql = "UPDATE cerclesdon_tbl SET pseudo = '$pseudo2' WHERE pseudo = '$pseudo1'";
	mysql_query($sql);
	$sql = "UPDATE cerclesnews_tbl SET posteur = '$pseudo2' WHERE posteur = '$pseudo1'";
	mysql_query($sql);
	$sql = "UPDATE cerclesvotesperso_tbl SET pseudo = '$pseudo2' WHERE pseudo = '$pseudo1'";
	mysql_query($sql);
	$sql = "UPDATE cercles_tbl SET pseudo = '$pseudo2' WHERE pseudo = '$pseudo1'";
	mysql_query($sql);
	$sql = "UPDATE chat_tbl SET posteur = '$pseudo2' WHERE posteur = '$pseudo1'";
	mysql_query($sql);
	$sql = "UPDATE citations_tbl SET auteur = '$pseudo2' WHERE auteur = '$pseudo1'";
	mysql_query($sql);
	$sql = "UPDATE comptes_acces_tbl SET pseudo = '$pseudo2' WHERE pseudo = '$pseudo1'";
	mysql_query($sql);
	$sql = "UPDATE crimes_tbl SET pseudo = '$pseudo2' WHERE pseudo = '$pseudo1'";
	mysql_query($sql);
	$sql = "UPDATE DCN_abonnes_tbl SET abonne = '$pseudo2' WHERE abonne = '$pseudo1'";
	mysql_query($sql);
	$sql = "UPDATE DCN_achats_tbl SET acheteur = '$pseudo2' WHERE acheteur = '$pseudo1'";
	mysql_query($sql);
	$sql = "UPDATE DCN_clips_tbl SET pseudo = '$pseudo2' WHERE pseudo = '$pseudo1'";
	mysql_query($sql);
	$sql = "UPDATE DCN_espaces_tbl SET pseudo = '$pseudo2' WHERE pseudo = '$pseudo1'";
	mysql_query($sql);
	$sql = "UPDATE DCN_espaces_tbl SET auteur = '$pseudo2' WHERE auteur = '$pseudo1'";
	mysql_query($sql);
	$sql = "UPDATE deces_tbl SET victime = '$pseudo2' WHERE victime = '$pseudo1'";
	mysql_query($sql);
	$sql = "UPDATE deces_tbl SET responsable = '$pseudo2' WHERE responsable = '$pseudo1'";
	mysql_query($sql);
	$sql = "UPDATE digicodes_tbl SET pseudo = '$pseudo2' WHERE pseudo = '$pseudo1'";
	mysql_query($sql);
	$sql = "UPDATE donneesidj_tbl SET pseudo = '$pseudo2' WHERE pseudo = '$pseudo1'";
	mysql_query($sql);
	$sql = "UPDATE enquete_tbl SET pseudo = '$pseudo2' WHERE pseudo = '$pseudo1'";
	mysql_query($sql);
	$sql = "UPDATE enregistreur_tbl SET pseudo = '$pseudo2' WHERE pseudo = '$pseudo1'";
	mysql_query($sql);
	$sql = "UPDATE evenements_tbl SET pseudo = '$pseudo2' WHERE pseudo = '$pseudo1'";
	mysql_query($sql);
	$sql = "UPDATE financepridem_tbl SET PDG = '$pseudo2' WHERE PDG = '$pseudo1'";
	mysql_query($sql);
	$sql = "UPDATE financepri_tbl SET membre = '$pseudo2' WHERE membre = '$pseudo1'";
	mysql_query($sql);
	$sql = "UPDATE finance_tbl SET membre = '$pseudo2' WHERE membre = '$pseudo1'";
	mysql_query($sql);
	$sql = "UPDATE jeu_concours_tbl SET pseudo = '$pseudo2' WHERE pseudo = '$pseudo1'";
	mysql_query($sql);
	$sql = "UPDATE loisprop_tbl SET membre = '$pseudo2' WHERE membre = '$pseudo1'";
	mysql_query($sql);
	$sql = "UPDATE loisvote_tbl SET membre = '$pseudo2' WHERE membre = '$pseudo1'";
	mysql_query($sql);
	$sql = "UPDATE messagesadmin_archives_tbl SET auteur = '$pseudo2' WHERE auteur = '$pseudo1'";
	mysql_query($sql);
	$sql = "UPDATE messagesadmin_tbl SET auteur = '$pseudo2' WHERE auteur = '$pseudo1'";
	mysql_query($sql);
	$sql = "UPDATE messages_tbl SET auteur = '$pseudo2' WHERE auteur = '$pseudo1'";
	mysql_query($sql);
	$sql = "UPDATE messages_tbl SET cible = '$pseudo2' WHERE cible = '$pseudo1'";
	mysql_query($sql);
	$sql = "UPDATE parrainage_tbl SET parrain = '$pseudo2' WHERE parrain = '$pseudo1'";
	mysql_query($sql);
	$sql = "UPDATE petitesannonces_tbl SET auteur = '$pseudo2' WHERE auteur = '$pseudo1'";
	mysql_query($sql);
	$sql = "UPDATE pleintes_tbl SET pseudo = '$pseudo2' WHERE pseudo = '$pseudo1'";
	mysql_query($sql);
	$sql = "UPDATE principal_tbl SET pseudo = '$pseudo2' WHERE pseudo = '$pseudo1'";
	mysql_query($sql);
	$sql = "UPDATE proprietaire_tbl SET pseudo = '$pseudo2' WHERE pseudo = '$pseudo1'";
	mysql_query($sql);
	$sql = "UPDATE signaturesperso_tbl SET pseudo = '$pseudo2' WHERE pseudo = '$pseudo1'";
	mysql_query($sql);
	$sql = "UPDATE talent_mentor_tbl SET pseudo = '$pseudo2' WHERE pseudo = '$pseudo1'";
	mysql_query($sql);
	$sql = "UPDATE tips_tbl SET auteur = '$pseudo2' WHERE auteur = '$pseudo1'";
	mysql_query($sql);
	$sql = "UPDATE titres_tbl SET pseudo = '$pseudo2' WHERE pseudo = '$pseudo1'";
	mysql_query($sql);
	$sql = "UPDATE transferts_tbl SET donneur = '$pseudo2' WHERE donneur = '$pseudo1'";
	mysql_query($sql);
	$sql = "UPDATE transferts_tbl SET receveur = '$pseudo2' WHERE receveur = '$pseudo1'";
	mysql_query($sql);
	$sql = "UPDATE vente_tbl SET acheteur = '$pseudo2' WHERE acheteur = '$pseudo1'";
	mysql_query($sql);
	$sql = "UPDATE vente_tbl SET vendeur = '$pseudo2' WHERE vendeur = '$pseudo1'";
	mysql_query($sql);
	$sql = "UPDATE votesarticles_tbl SET pseudo = '$pseudo2' WHERE pseudo = '$pseudo1'";
	mysql_query($sql);
	$sql = "UPDATE votesDI_tbl SET voteur = '$pseudo2' WHERE voteur = '$pseudo1'";
	mysql_query($sql);
	$sql = "UPDATE wikast_edc_articles_tbl SET auteur = '$pseudo2' WHERE auteur = '$pseudo1'";
	mysql_query($sql);
	$sql = "UPDATE wikast_edc_commentaires_tbl SET auteur = '$pseudo2' WHERE auteur = '$pseudo1'";
	mysql_query($sql);
	$sql = "UPDATE wikast_edc_etoiles_tbl SET auteur = '$pseudo2' WHERE auteur = '$pseudo1'";
	mysql_query($sql);
	$sql = "UPDATE wikast_edc_etoiles_tbl SET cible = '$pseudo2' WHERE cible = '$pseudo1'";
	mysql_query($sql);
	$sql = "UPDATE wikast_forum_permissions_tbl SET pseudo = '$pseudo2' WHERE pseudo = '$pseudo1'";
	mysql_query($sql);
	$sql = "UPDATE wikast_forum_posts_tbl SET auteur = '$pseudo2' WHERE auteur = '$pseudo1'";
	mysql_query($sql);
	$sql = "UPDATE wikast_forum_structure_tbl SET admin = '$pseudo2' WHERE admin = '$pseudo1'";
	mysql_query($sql);
	$sql = "UPDATE wikast_forum_sujets_tbl SET auteur = '$pseudo2' WHERE auteur = '$pseudo1'";
	mysql_query($sql);
	$sql = "UPDATE wikast_joueurs_tbl SET pseudo = '$pseudo2' WHERE pseudo = '$pseudo1'";
	mysql_query($sql);
	$sql = "UPDATE wikast_sondages_tbl SET auteur = '$pseudo2' WHERE auteur = '$pseudo1'";
	mysql_query($sql);
	$sql = "UPDATE wikast_wiki_articles_tbl SET createur = '$pseudo2' WHERE createur = '$pseudo1'";
	mysql_query($sql);
	
	echo "$pseudo1 transformé en $pseudo2 avec succès !";
}

function clean_objets() {
	$sql = 'SELECT nom, image FROM objets_tbl WHERE type = "armtu" OR type = "armcu"';
	$req = mysql_query($sql);
	$res = mysql_num_rows($req);
	for ($i = 0 ; $i < $res ; $i++) {
		$sql2 = 'UPDATE objets_tbl SET image = "'.mysql_result($req, $i, image).'" WHERE nom LIKE "'.mysql_result($req, $i, nom).'-%"';
		mysql_query($sql2);
	}
}

function change_image_objet($nom_objet, $newimage="") {
	if (empty($newimage))
		$newimage="imageindispo.gif";
	
	$sql = "SELECT type FROM objets_tbl WHERE nom LIKE '$nom_objet'";
	$req = mysql_query($sql);
	$res = mysql_num_rows($req);
	
	if (!$res)
		exit();
	
	$sql = "UPDATE objets_tbl SET image = '$newimage' WHERE nom LIKE '$nom_objet'";
	mysql_query($sql);
		
	if (mysql_result($req,0,type) == "armcu" || mysql_result($req,0,type) == "armtu") {
		$sql = "UPDATE objets_tbl SET image = '$newimage' WHERE nom LIKE '$nom_objet-%'";
		mysql_query($sql);
	}
	
	echo 'Image(s) changée(s).';
}

function delete_pseudos($pseudos) {
	foreach ($pseudos as $pseudo) {
		supprimer_personnage($pseudo, "Compte supprimé par l'administration");
		echo "Joueur $pseudo supprimé<br />";
	}
}

function is_multi($s_p1, $s_p2, $verbose = false, $precision = 30)
		{
		$coef_same_mdp = 240;
		$coef_near_mdp = 90;
		$coef_ip = 100;
		$coef_ipdc = 100;
		$coef_pseudo = 70;
		$coef_login = 70;
		$coef_loginpseudo = 70;
		$coef_mail = 10;
		$coef_no_mp = 30;
		$coef_gift_no_mp = 40;
		$coef_gift = 20;
		$coef_bank_no_mp = 40;
		$coef_bank = 20;
		$max = $coef_bank_no_mp + $coef_gift_no_mp + $coef_no_mp +
		       $coef_mail + $coef_login + $coef_pseudo + $coef_loginpseudo +
		       $coef_ipdc + $coef_ip + $coef_near_mdp + $coef_same_mdp;
		$i_is_multi = 0;
		$b_mp = false;

		if ($s_p1 === $s_p2)
			return (false);

//		$bdd = mysql_connect("localhost", "dc_test", "test");
//		mysql_select_db("dc_test", $bdd);
		$a_list = mysql_query("SELECT * FROM principal_tbl WHERE pseudo LIKE '$s_p1' OR pseudo LIKE '$s_p2'") OR die(mysql_error());
		if (mysql_num_rows($a_list) != 2)
			return false;

		while ($tmp = mysql_fetch_array($a_list))
		{
			if ((strcasecmp($s_p1, $tmp['pseudo']) == 0))
				$a_p1 = $tmp;
			if ((strcasecmp($s_p2, $tmp['pseudo']) == 0))
				$a_p2 = $tmp;
		}
		
		if ($a_p1['ip'] === $a_p2['ip'] && $a_p1['ip'] != "1.1.1.1")
		{
			$i_is_multi += $coef_ip;
			if ($verbose == true)
				echo "- Meme IP (".$a_p1['ip'].").<br />";
		}
		elseif ($a_p1['ip'] === "1.1.1.1")
		{
			echo "- IP autorisée.<br />";
		}
		
		if ($a_p1['ipdc'] === $a_p2['ipdc'])
		{
			$i_is_multi += $coef_ipdc;
			if ($verbose == true)
				echo "- Meme IPDC. <br />";
		}
		if (similar_text($a_p1['password'], $a_p2['password'], &$percent) > 0 && $percent >= 70)
		{
			$i_is_multi += ($percent == 100 ? $coef_same_mdp : $coef_near_mdp);
			if ($verbose == true)
				printf("- Meme Mot de Passe, ou presque (%s - %s)<br />", $a_p1['password'], $a_p2['password']);
		}
		if (stristr($a_p1['pseudo'], $a_p2['pseudo']) != false || stristr($a_p2['pseudo'], $a_p1['pseudo']) != false)
		{
			$i_is_multi += $coef_pseudo;
			if ($verbose == true)
				echo "- Pseudo qui se ressemble.<br />";
		}
		if (stristr($a_p1['login'], $a_p2['login']) != false || stristr($a_p2['login'], $a_p1['login']) != false)
		{
			$i_is_multi += $coef_login;
			if ($verbose == true)
				printf("- Login qui se ressemble. (%s - %s)<br />", $a_p1['login'], $a_p2['login']);
		}
		if (stristr($a_p1['pseudo'], $a_p2['login']) != false || stristr($a_p2['pseudo'], $a_p1['login']) != false)
		{
			$i_is_multi += $coef_loginpseudo;
			if ($verbose == true)
				printf("- Pseudo/Login qui se ressemble. (%s/%s - %s/%s)<br />", $a_p1['pseudo'], $a_p1['login'], $a_p2['pseudo'], $a_p2['login']);
		}
		$cut_mail1 = strrev(stristr(strrev($a_p1['adresse']), '@'));
		$cut_mail2 = strrev(stristr(strrev($a_p2['adresse']), '@'));
		if (stristr($cut_mail1, $cut_mail2) != false || stristr($cut_mail2, $cut_mail1) != false)
		{
			$i_is_multi += $coef_mail;
			if ($verbose == true)
				printf("- Mail qui se ressemble (%s - %s).<br />", $cut_mail1, $cut_mail2);
		}
		if ($i_is_multi > 0)
		{
			$a_list = mysql_query("SELECT * FROM messages_tbl WHERE (auteur = '$s_p1' AND cible = '$s_p2') OR (auteur = '$s_p2' AND cible = '$s_p1')") OR die(mysql_error());
			if ($nb_mp = mysql_num_rows($a_list))
			{
				$b_mp = false;
				if ($verbose == true)
					echo "- Se sont deja envoye des mp (".$nb_mp.").<br />";
			}
			else
			{
				$b_mp = true;
				$i_is_multi += $coef_no_mp;
				if ($verbose == true)
					echo "- Ne se sont jamais envoye de mp.<br />";
			}
		}
		$a_list = mysql_query("SELECT * FROM transferts_tbl WHERE operation LIKE 'Don %' AND ((donneur = '$s_p1' AND receveur = '$s_p2') OR (donneur = '$s_p2' AND receveur = '$s_p1'))") OR die(mysql_error());
		if ($nb_echanges = mysql_num_rows($a_list))
		{
			$i_is_multi += ($b_mp == false ? $coef_gift_no_mp : $coef_gift);
			if ($verbose == true)
				echo "Echange d\'objet ou d\'argent (".$nb_echanges.").<br />";
		}
		$a_list = mysql_query("SELECT * FROM comptes_acces_tbl WHERE pseudo LIKE '$s_p1' OR pseudo LIKE '$s_p2' ") OR die(mysql_error());
		$i = 0;
		$j = 0;
		while ($tmp = mysql_fetch_array($a_list))
		{
			if (strcasecmp($tmp['pseudo'], $s_p1) == 0)
				$a_c1[$i++] = $tmp['compte'];
			else if (strcasecmp($tmp['pseudo'], $s_p2) == 0)
				$a_c2[$j++] = $tmp['compte'];
		}
		if (!empty($a_c1) && !empty($a_c2))
		{
			$a_res = array_intersect(array_unique($a_c1), array_unique($a_c2));
			if ($a_res)
			{
				$i_is_multi += ($b_mp == false ? $coef_bank_no_mp : $coef_bank);
				if ($verbose == true)
				{
					printf("- %s compte(s) commun(s) : %s<br />",
						count($a_res),
						implode(", ", $a_res)
					);
				}
			}
		}
		if (($i_is_multi / $max * 100) > $precision || $verbose == true)
			printf("<strong>%s</strong> et <strong>%s</strong> (%d%%)<br />", $s_p1, $s_p2, $i_is_multi / $max * 100);
		}
		
function infos_ip($pseudos, $type) {
	if (count($pseudos) < 2)
		return false;
	
	$ip = '';
	$groupe = '';
	
	foreach ($pseudos as $i => $pseudo) {
		if ($i == 0) {
			$sql = 'SELECT ipdc FROM principal_tbl WHERE pseudo="'.$pseudo.'"';
			$req = mysql_query($sql);
			if (!mysql_num_rows($req))
				return false;
			
			$ip = mysql_result($req,0,ipdc);
			
			$sql = 'INSERT INTO infos_ip_tbl VALUES (NULL, "'.$pseudo.'", "'.$ip.'", -1, '.$type.')';
			mysql_query($sql);
			$sql = 'SELECT id FROM infos_ip_tbl WHERE pseudo = "'.$pseudo.'" ORDER BY id DESC';
			$req = mysql_query($sql);
			$groupe = mysql_result($req,0,id);
		} else {
			$sql = 'INSERT INTO infos_ip_tbl VALUES (NULL, "'.$pseudo.'", "'.$ip.'", '.$groupe.', '.$type.')';
			mysql_query($sql);
		}
	}
	
	echo "C'est ok.";
	
}

/*$idrue = 8;
$pariterue = 1;

$sql = 'SELECT x,y,idrue FROM dreadmap_tbl WHERE y=150 AND idrue='.$idrue;
$req = mysql_query($sql);

$idrue = mysql_result($req,0,idrue);
$actuel['x'] = mysql_result($req,($pariterue-1),x);
$actuel['y'] = mysql_result($req,($pariterue-1),y);
$ancien['x'] = $actuel['x'];
$ancien['y'] = $actuel['y'];
//echo $actuel['x'].' '.$actuel['y'] .' '.$idrue;
$num = $pariterue;
$carte = "";
$compteur = 0;

$sqlf2 = 'SELECT x,y,idrue FROM dreadmap_tbl WHERE idrue='.$idrue;
$reqf2 = mysql_query($sqlf2);
$resf2 = mysql_num_rows($reqf2);
$resf2 = $resf2*2;

while($num < $resf2)
	{
	$temp = traitement(gamma4($actuel['x'],$actuel['y'],$idrue),$actuel['x'],$actuel['y'],$ancien['x'],$ancien['y']);
	if($temp==0) {
		$temp = null;
		//if($ancien['y'] - $actuel['y'] > 0) break;
		$temp['x'] = $actuel['x'] + 2*($actuel['x']-$ancien['x']);
		$temp['y'] = $actuel['y'] + 2*($actuel['y']-$ancien['y']);
		$sql2 = 'SELECT id FROM dreadmap_tbl WHERE idrue='.$idrue.' AND x='.$temp['x'].' AND y='.$temp['y'];
		$req2 = mysql_query($sql2);
		if(!mysql_num_rows($req2)) {
			$temp['x'] = $actuel['x'] + 4*($actuel['x']-$ancien['x']);
			$temp['y'] = $actuel['y'] + 4*($actuel['y']-$ancien['y']);
			$sql2 = 'SELECT id FROM dreadmap_tbl WHERE idrue='.$idrue.' AND x='.$temp['x'].' AND y='.$temp['y'];
			$req2 = mysql_query($sql2);
			if(!mysql_num_rows($req2)) break;
		}
	}
	$ancien['x'] = $actuel['x'];
	$ancien['y'] = $actuel['y'];
	$actuel['x'] = $temp['x'];
	$actuel['y'] = $temp['y'];
	$carte .= '<div class="num'.$num.'" style="position:absolute;left:'.(2*$ancien['x']).'px;top:'.(2*$ancien['y']).'px;background:red;width:2px;height:2px;"></div>';
	update($ancien['x'],$ancien['y'],$num,$idrue);
	$num += 2;
	if($compteur++ == 600) break;
	}
update($actuel['x'],$actuel['y'],$num,$idrue);
$carte .= '<div class="num'.$num.'" style="position:absolute;left:'.(2*$actuel['x']).'px;top:'.(2*$actuel['y']).'px;background:red;width:2px;height:2px;"></div>';
echo '<div style="position:absolute;z-index:200;">'.$carte.'</div>';

$sql = 'SELECT x,y,idrue FROM dreadmap_tbl' ;
		$req = mysql_query($sql);
		$res = mysql_num_rows($req);
		$carte = "";
		for($i=0;$i<$res;$i++)
			{
			$col = (mysql_result($req,$i,idrue) == 0)?'white':"#333";
			$carte .= '<div class="rue'.mysql_result($req,$i,idrue).'" style="position:absolute;left:'.(2*(mysql_result($req,$i,x))).'px;top:'.(2*(mysql_result($req,$i,y))).'px;background:'.$col.';width:2px;height:2px;"></div>';
			}
echo '<div style="position:absolute;z-index:100;">'.$carte.'</div>';


function gamma4($x,$y,$idrue){
	$sql2 = 'SELECT x,y FROM dreadmap_tbl WHERE idrue='.$idrue.' AND (
			(x='.($x + 1).' AND y='.$y.') OR
			(x='.($x - 1).' AND y='.$y.') OR
			(x='.$x.' AND y='.($y + 1).') OR
			(x='.$x.' AND y='.($y - 1).')) ORDER BY x';
	$req2 = mysql_query($sql2);
	//echo $sql2.'<br />';
	return $req2;
}


function traitement($req2,$x,$y,$ancienx,$ancieny){
	if(mysql_num_rows($req2) > 2)
		{
		for($j=0;$j<mysql_num_rows($req2);$j++){
			if(!(mysql_result($req2,$j,x) == $ancienx && mysql_result($req2,$j,y) == $ancieny)){
				if($x-$ancienx != 0 && mysql_result($req2,$j,x)-$x != 0) return array('x'=>mysql_result($req2,$j,x),'y'=>mysql_result($req2,$j,y));
				elseif($y-$ancieny != 0 && mysql_result($req2,$j,y)-$y != 0) return array('x'=>mysql_result($req2,$j,x),'y'=>mysql_result($req2,$j,y));
				elseif($x-$ancienx<0 && mysql_result($req2,$j,y) > $y) return array('x'=>mysql_result($req2,$j,x),'y'=>mysql_result($req2,$j,y));
				elseif($x-$ancienx>0 && mysql_result($req2,$j,y) < $y) return array('x'=>mysql_result($req2,$j,x),'y'=>mysql_result($req2,$j,y));
			}
		}
		return 0;
		}
	else
		{
		for($j=0;$j<mysql_num_rows($req2);$j++){
			if(!(mysql_result($req2,$j,x) == $ancienx && mysql_result($req2,$j,y) == $ancieny)){
				return array('x'=>mysql_result($req2,$j,x),'y'=>mysql_result($req2,$j,y));
			}
		}
		return 0;
		}
}

function update($x,$y,$num,$idrue){
	$sql2 = 'UPDATE dreadmap_tbl SET num=-'.$num.' WHERE idrue='.$idrue.' AND x='.$x.' AND y='.$y;
	$req2 = mysql_query($sql2);
	echo $sql2.'<br />';
}

/*verification();
function verification(){
for($idrue = 1;$idrue<11;$idrue++){

$sql = 'SELECT num FROM carte_tbl WHERE idrue='.$idrue.' ORDER BY num';
$req = mysql_query($sql);
$res = mysql_num_rows($req);
$j=1;
for($j=1;$j<$res;$j++){
	if($j!=mysql_result($req,$j-1,num)) break;
}
if($j == 600) echo 'Rue '.$idrue.' OK<br />';
else echo 'Rue '.$idrue.' -> Problème n°'.$j.'<br />';

}
}*/

/*$sql = 'SELECT idrue,num,type FROM dreadmap_tbl WHERE idrue!=0';
$req = mysql_query($sql);
$res = mysql_num_rows($req);
for($j=0;$j<$res;$j++)
	{
	$sql2 = 'UPDATE carte_tbl SET type="'.mysql_result($req,$j,type).'" WHERE idrue='.mysql_result($req,$j,idrue).' AND num='.mysql_result($req,$j,num);
	mysql_query($sql2);
	echo $sql2.'<br />';
	}*/

/*$sql = 'SELECT id,nom FROM entreprises_tbl WHERE nom != "Imperiale des jeux"';
$req = mysql_query($sql);
$res = mysql_num_rows($req);
for($i=0;$i<$res;$i++){
	$ident = mysql_result($req,$i,id);
	$ent = mysql_result($req,$i,nom);
	$sql2 = 'SELECT poste FROM `e_'.str_replace(" ","_",$ent).'_tbl` WHERE type="chef"';
	$req2 = mysql_query($sql2);
	$poste = mysql_result($req2,0,poste);
	$sql2 = 'SELECT id FROM principal_tbl WHERE entreprise="'.$ent.'" AND type="'.$poste.'"';
	$req2 = mysql_query($sql2);
	if(!mysql_num_rows($req2)) supprimer_entreprise($ident);
}*/

/*$sql = 'UPDATE carte_tbl SET type=-1 WHERE num != 0';
$req = mysql_query($sql);
$sql = 'SELECT num,rue FROM lieu_tbl WHERE rue != "Rue" AND rue != "Ruelle"';
$req = mysql_query($sql);
$res = mysql_num_rows($req);
for($i=0;$i<$res;$i++){
	$num = mysql_result($req,$i,num);
	$rue = mysql_result($req,$i,rue);
	
	$sql2 = 'SELECT id FROM rues_tbl WHERE nom = "'.$rue.'"';
	$req2 = mysql_query($sql2);
	$idrue = mysql_result($req2,0,id);
	
	echo $num.' '.$rue.' '.$idrue.'<br />';
	
	$sql2 = 'UPDATE carte_tbl SET type=1 WHERE num = '.$num.' AND idrue='.$idrue;
	$req2 = mysql_query($sql2);
}*/

/*$sql = 'SELECT id,x,y FROM carte_tbl WHERE type=0';
$req = mysql_query($sql);
$res = mysql_num_rows($req);
for($i=0;$i<$res;$i++){
	
	$x = mysql_result($req,$i,x);
	$y = mysql_result($req,$i,y);
	
	$sql2 = 'SELECT idrue,num FROM dreadmap_tbl WHERE x='.$x.' AND y='.$y;
	$req2 = mysql_query($sql2);
	
	
	
	$sql2 = 'UPDATE carte_tbl SET idrue="'.mysql_result($req2,0,idrue).'", num="'.mysql_result($req2,0,num).'" WHERE id = '.mysql_result($req,$i,id);
	//mysql_query($sql2);
	//echo $x.' '.mysql_result($req,$i,y).' '.$sql2.'<br />';
}

$date = time()-60*60*24*60;

$sql = 'SELECT code,credits FROM comptes_tbl';
$req = mysql_query($sql);
$res = mysql_num_rows($req);
$argent = 0;
$nb = 0;
$compteleplus = 0;

for($i=0;$i<$res;$i++){
	echo $i.'/'.$res.'<br />';
	$sql1 = 'SELECT id FROM comptes_acces_tbl WHERE compte = "'.mysql_result($req,$i,code).'" AND time > '.$date;
	$req1 = mysql_query($sql1);
	$res1 = mysql_num_rows($req1);
	
	if($res1 == 0){
		$argent += mysql_result($req,$i,credits);
		if(mysql_result($req,$i,credits) > $compteleplus) $compteleplus = mysql_result($req,$i,credits);
		$nb++;
		$sql1 = 'DELETE FROM comptes_tbl WHERE code = "'.mysql_result($req,$i,code).'"';
		mysql_query($sql1);
	}
}
print('Comptes inutilisés depuis 2 mois : '.$nb.'<br />Pour un total en crédits de : '.$argent.'Cr ('.$compteleplus.'Cr sur le plus gros compte)');*/

/*
// LIEUX INEXISTANTS
$sql = 'SELECT C.num,C.idrue,R.nom FROM carte_tbl C, rues_tbl R WHERE C.idrue = R.id AND C.num >= 0 AND C.type >= 0';
$req = mysql_query($sql);
$res = mysql_num_rows($req);

echo $res.' lieux disponibles<br />';

$nb = 0;

for($i=0;$i<$res;$i++){
	$idrue = mysql_result($req,$i,idrue);
	$num = mysql_result($req,$i,num);
	$rue = mysql_result($req,$i,nom);
	
	$sql1 = 'SELECT id FROM lieu_tbl WHERE num = '.$num.' AND rue = "'.$rue.'"';
	$req1 = mysql_query($sql1);
	$res1 = mysql_num_rows($req1);
	
	if(!$res1) {
		$sql1 = 'UPDATE carte_tbl SET type="-1" WHERE idrue='.$idrue.' AND num='.$num;
		//mysql_query($sql1);
		$nb++;
		echo $num.' '.$rue.' ('.$idrue.')<br />';
	}
}

echo $nb.' lieux inexistants';*/


/*

$entreprise = "DC Network";
$newsecteur = "1";
$newnumrue = array('num'=>'439','rue'=>'boulevard agderon');



$newnumrue = ($newnumrue=="")?recupereEmplacement($newsecteur):$newnumrue;
$sql = 'SELECT num,rue FROM entreprises_tbl WHERE nom LIKE "'.$entreprise.'"';
$req = mysql_query($sql);
$res = mysql_num_rows($req);
if(!$res)
	{
	echo $entreprise.' n\'existe pas.';
	exit();
	}
$num = mysql_result($req,0,num);
$rue = mysql_result($req,0,rue);
$xy = numrue2XY($newnumrue['num'],$newnumrue['rue']);
echo "Entreprise : $entreprise<br />
$num $rue => ".$newnumrue['num']." ".$newnumrue['rue']." (".$xy['x'].",".$xy['y']." - Secteur $newsecteur)<br />
<br />";

$sql1 = 'UPDATE carte_tbl SET type="1" WHERE idrue=(SELECT id FROM rues_tbl WHERE nom LIKE "'.$newnumrue['rue'].'") AND num="'.$newnumrue['num'].'";';
echo $sql1.'<br />';
$sql2 = 'UPDATE carte_tbl SET type="-1" WHERE idrue=(SELECT id FROM rues_tbl WHERE nom LIKE "'.$rue.'") AND num="'.$num.'";';
echo $sql2.'<br />';
$sql3 = 'UPDATE entreprises_tbl SET num="'.$newnumrue['num'].'", rue="'.$newnumrue['rue'].'" WHERE nom="'.$de.'";';
echo $sql3.'<br />';
$sql4 = 'UPDATE lieu_tbl SET num="'.$newnumrue['num'].'", rue="'.$newnumrue['rue'].'" WHERE num="'.$num.'" AND rue="'.$rue.'";';
echo $sql4.'<br />';
$sql5 = 'UPDATE chat SET num="'.$newnumrue['num'].'", rue="'.$newnumrue['rue'].'" WHERE num="'.$num.'" AND rue="'.$rue.'";';
echo $sql5.'<br />';
$sql6 = 'UPDATE adresses_tbl SET num="'.$newnumrue['num'].'", rue="'.$newnumrue['rue'].'" WHERE num="'.$num.'" AND rue="'.$rue.'";';
echo $sql6.'<br />';
$sql7 = 'UPDATE principal_tbl SET num="'.$newnumrue['num'].'", rue="'.$newnumrue['rue'].'" WHERE num="'.$num.'" AND rue="'.$rue.'";';
echo $sql7.'<br />';
//mysql_query($sql1);mysql_query($sql2);mysql_query($sql3);mysql_query($sql4);mysql_query($sql5);mysql_query($sql6);mysql_query($sql7);

*/

$o_acc = "petite frappe";
$acc = "haut fonctionnaire";
$pseudo = "Ketil";
//echo $acc.'<br />';
//echo var_dump(verif_accomplissement($acc,$pseudo));

$sql = 'UPDATE titres_tbl SET titre = "'.$acc.'" WHERE pseudo="'.$pseudo.'" AND titre = "'.$o_acc.'"';
//mysql_query($sql);

//echo 'Test: '.var_dump($_GET);



/*$sql = 'SELECT ids,num,rue FROM chat ORDER BY rue,num,ids';
$req = mysql_query($sql);
$res = mysql_num_rows($req);

$onum = 0;
$orue = "";
$oids = 0;

$cont = "";
$cont2 = 0;
$cont3 = "";

for($i=0;$i<$res;$i++){
	$num = mysql_result($req,$i,num);
	$rue = mysql_result($req,$i,rue);
	$ids = mysql_result($req,$i,ids);
	
	if($num == $onum && strtolower($rue) == strtolower($orue) && $ids == $oids) {
		$sql2 = "DELETE FROM chat WHERE num = $num AND rue = \"$rue\" AND ids = $ids";
		mysql_query($sql2);
		$cont .= $sql2."<br />";
		$cont2++;
		$cont3 .= mysql_error()."<br />";
	} else {
		$onum = $num;
		$orue = $rue;
		$oids = $ids;
	}
}
echo "Total : ".$cont2 . "<br />" . $cont.$cont3;*/

/*$sql = 'SELECT pseudo FROM cercles_tbl WHERE cercle= "AAC"' ;
$req = mysql_query($sql);
$res = mysql_num_rows($req);

for($i=0;$i!=$res;$i++)
	{
	$sql1 = 'SELECT avatar,connec,type,entreprise FROM principal_tbl WHERE pseudo= "'.mysql_result($req,$i,pseudo).'"' ;
	$req1 = mysql_query($sql1);
	if(mysql_num_rows($req1)==0) print(mysql_result($req,$i,pseudo).'<br />');
}*/

//$req = mysql_query('SHOW STATUS');

/*print('<table>');
while($array = mysql_fetch_array($req))
	{
	echo '<tr><td>'.$array[0].'</td><td>'.$array[1].'</td><td>'.$array[2].'</td><td>'.$array[3].'</td></tr>';
	}
print('</table>');*/
				
mysql_close($db);

//echo phpinfo();

?>

