<?php

function supprimer_personnage($pseudo,$raison,$blocage_ip="") {

	$sql = 'SELECT id,statut,ipdc FROM principal_tbl WHERE pseudo= "'.$pseudo.'"' ;
	$req = mysql_query($sql);
	$res = mysql_num_rows($req);
	if($res != 0 && mysql_result($req,0,statut) != "Administrateur")
		{
		$id = mysql_result($req,0,id);
		$ip = mysql_result($req,0,ipdc);
		$sql1 = 'DELETE FROM adresses_tbl WHERE pseudo= "'.$pseudo.'"' ;
		$req1 = mysql_query($sql1);
		$sql1 = 'DELETE FROM candidatures_tbl WHERE nom= "'.$pseudo.'"' ;
		$req1 = mysql_query($sql1);
		$sql1 = 'DELETE FROM carnets_tbl WHERE pseudo= "'.$pseudo.'"' ;
		$req1 = mysql_query($sql1);
		
		$sql1 = 'SELECT pseudo,credits FROM principal_tbl WHERE id= "'.$id.'"' ;
		$req1 = mysql_query($sql1);
		$sqlspe = 'INSERT INTO transferts_tbl(donneur,receveur,time,operation,valeur) VALUES("'.mysql_result($req1,0,pseudo).'","Dreadcast","'.time().'","Suppression joueur","'.mysql_result($req1,0,credits).'")';
		$reqspe = mysql_query($sqlspe);
		
		$sql1 = 'DELETE FROM principal_tbl WHERE id= "'.$id.'"' ;
		$req1 = mysql_query($sql1);
		$sql = 'SELECT num,rue FROM proprietaire_tbl WHERE pseudo= "'.$pseudo.'"' ;
		$req = mysql_query($sql);
		if(mysql_num_rows($req)){
			$sql1 = 'UPDATE principal_tbl SET ruel="Aucune",numl="0" WHERE ruel="'.mysql_result($req,0,rue).'",numl="'.mysql_result($req,0,num).'"' ;
			$req1 = mysql_query($sql1);
		}
		$sql = 'DELETE FROM proprietaire_tbl WHERE pseudo= "'.$pseudo.'"' ;
		$req = mysql_query($sql);
		$sql = 'DELETE FROM crimes_tbl WHERE pseudo= "'.$pseudo.'"' ;
		$req = mysql_query($sql);
		$sql1 = 'DELETE FROM messagesadmin_tbl WHERE auteur= "'.$pseudo.'"' ;
		$req1 = mysql_query($sql1);
		$sql1 = 'DELETE FROM messages_tbl WHERE cible= "'.$pseudo.'"' ;
		$req1 = mysql_query($sql1);
		$sql1 = 'DELETE FROM principal_tbl WHERE pseudo= "'.$pseudo.'"' ;
		$req1 = mysql_query($sql1);
		$sql1 = 'DELETE FROM pleintes_tbl WHERE pseudo= "'.$pseudo.'"' ;
		$req1 = mysql_query($sql1);
		$sql1 = 'DELETE FROM petitesannonces_tbl WHERE auteur= "'.$pseudo.'"' ;
		$req1 = mysql_query($sql1);
		$sql1 = 'DELETE FROM cercles_tbl WHERE pseudo= "'.$pseudo.'"' ;
		$req1 = mysql_query($sql1);
		$sql1 = 'DELETE FROM titres_tbl WHERE pseudo= "'.$pseudo.'"' ;
		$req1 = mysql_query($sql1);
		$sql1 = 'DELETE FROM enregistreur_tbl WHERE pseudo= "'.$pseudo.'"' ;
		$req1 = mysql_query($sql1);
		$sql1 = 'INSERT INTO abandons_tbl(id,pseudo,ip,raison) VALUES("","'.$pseudo.'","'.$ip.'","'.$raison.'")' ;
		$req1 = mysql_query($sql1);
		if($blocage_ip!="")
			{
			$sql1 = 'INSERT INTO blocage_ip_tbl(ip) VALUES("'.$ip.'")' ;
			$req1 = mysql_query($sql1);
			}
		}
}
	
function supprimer_logement($id){

	$sql = 'SELECT pseudo,num,rue FROM proprietaire_tbl WHERE id= "'.$id.'"' ;
	$req = mysql_query($sql);
	$numlog = mysql_result($req,0,num);
	$ruelog = mysql_result($req,0,rue);
	
	$sql = 'SELECT id FROM rues_tbl WHERE nom LIKE "'.$ruelog.'"' ;
	$req = mysql_query($sql);
	$idrue = mysql_result($req,0,id);
	$sql = 'UPDATE carte_tbl SET type="-1" WHERE idrue='.$idrue.' AND num='.$numlog ;
	$req = mysql_query($sql);
	
	$sql = 'DELETE FROM lieu_tbl WHERE num="'.$numlog.'" AND rue="'.$ruelog.'"' ;
	$req = mysql_query($sql);
	$sql = 'DELETE FROM invlieu_tbl WHERE num="'.$numlog.'" AND rue="'.$ruelog.'"' ;
	$req = mysql_query($sql);
	
	$sql =  'UPDATE principal_tbl SET ruel="Aucune",numl="0" WHERE ruel="'.$ruelog.'" AND numl="'.$numlog.'"';
	$req = mysql_query($sql);
}

function supprimer_entreprise($id, $perte_argent = true){
	
	$sql = 'SELECT nom,num,rue FROM entreprises_tbl WHERE id= "'.$id.'"' ;
	$req = mysql_query($sql);
	$ent = mysql_result($req,0,nom);
	$nument = mysql_result($req,0,num);
	$rueent = mysql_result($req,0,rue);
	
	if(($ent=="Conseil Imperial") || ($ent=="CIPE") || ($ent=="CIE") || ($ent=="DOI") || ($ent=="Prison") || ($ent=="Police") || ($ent=="Services techniques de la ville") || ($ent=="Chambre des lois") || ($ent=="DI2RCO") || ($ent=="DC Network"))
		{
		$sql = 'UPDATE `e_'.str_replace(" ","_",''.$ent.'').'_tbl` SET nbreactuel="0" WHERE type= "chef"' ;
		$req = mysql_query($sql);
		}
	else
		{
		$sql = 'SELECT id FROM rues_tbl WHERE nom LIKE "'.$rueent.'"' ;
		$req = mysql_query($sql);
		$idrue = mysql_result($req,0,id);
		$sql = 'UPDATE carte_tbl SET type="-1" WHERE idrue='.$idrue.' AND num='.$nument ;
		$req = mysql_query($sql);
		
		$sql = 'DELETE FROM lieu_tbl WHERE num="'.$nument.'" AND rue="'.$rueent.'"' ;
		$req = mysql_query($sql);
		$sql = 'DELETE FROM invlieu_tbl WHERE num="'.$nument.'" AND rue="'.$rueent.'"' ;
		$req = mysql_query($sql);
		
		$new = coordonnees_sortie_rue($nument, $rueent);
		$sql = 'UPDATE principal_tbl SET num="'.$new['num'].'" , rue="'.$new['rue'].'" WHERE num="'.$nument.'" AND rue="'.$rueent.'"' ;
		$req = mysql_query($sql);
		
		$sql = 'DELETE FROM pubs_tbl WHERE entreprise="'.$ent.'"' ;
		$req = mysql_query($sql);
		$sql = 'DELETE FROM stocks_tbl WHERE entreprise="'.$ent.'"' ;
		$req = mysql_query($sql);
		
		$sql = 'SELECT budget FROM entreprises_tbl WHERE nom LIKE "'.$ent.'"' ;
		$req = mysql_query($sql);
		if(mysql_num_rows($req) != 0 && $perte_argent) {
			$sqlspe = 'INSERT INTO transferts_tbl(donneur,receveur,time,operation,valeur) VALUES("'.$ent.'","Dreadcast","'.time().'","Suppression entreprise","'.mysql_result($req,0,budget).'")';
			$reqspe = mysql_query($sqlspe);
			ajouter_argent_imperium(mysql_result($req,0,budget));
		}
				
		$sql = 'DELETE FROM entreprises_tbl WHERE nom="'.$ent.'"' ;
		$req = mysql_query($sql);
		$sql = 'SELECT pseudo FROM principal_tbl WHERE entreprise= "'.$ent.'"' ;
		$req = mysql_query($sql);
		$res = mysql_num_rows($req);
		for($i=0;$i<$res;$i++)
			{
			$sql2 = 'INSERT INTO messages_tbl(id,auteur,cible,message,moment) VALUES("","'.$ent.'","'.mysql_result($req,$i,pseudo).'","L\'entreprise à fait faillite. Vous êtes licencié.","'.time().'")' ;
			$req2 = mysql_query($sql2);
			}
		$sql = 'UPDATE principal_tbl SET type="Aucun" , entreprise="Aucune" , salaire="0" , difficulte="0" , points="0" WHERE entreprise="'.$ent.'"' ;
		$req = mysql_query($sql);
		$sql = 'DROP TABLE `e_'.str_replace(" ","_",''.$ent.'').'_tbl`' ;
		$req = mysql_query($sql);
	}
}
	
function supprimer_cercle($id){
	
	$sql = 'SELECT nom,num,rue,capital FROM cerclesliste_tbl WHERE id= "'.$id.'"' ;
	$req = mysql_query($sql);
	$cercle = mysql_result($req,0,nom);
	$numcer = mysql_result($req,0,num);
	$ruecer = mysql_result($req,0,rue);
	$capcer = mysql_result($req,0,capital);
	
	$sql = 'SELECT id FROM rues_tbl WHERE nom LIKE "'.$ruecer.'"' ;
	$req = mysql_query($sql);
	$idrue = mysql_result($req,0,id);
	$sql = 'UPDATE carte_tbl SET type="-1" WHERE idrue='.$idrue.' AND num='.$numcer ;
	$req = mysql_query($sql);
	
	$sql = 'DELETE FROM lieu_tbl WHERE num="'.$numcer.'" AND rue="'.$ruecer.'"' ;
	$req = mysql_query($sql);
	$sql = 'DELETE FROM invlieu_tbl WHERE num="'.$numcer.'" AND rue="'.$ruecer.'"' ;
	$req = mysql_query($sql);

	$sql = 'DELETE FROM cercles_tbl WHERE cercle="'.$cercle.'"' ;
	$req = mysql_query($sql);
	$sql = 'DELETE FROM cerclesdon_tbl WHERE cercle="'.$cercle.'"' ;
	$req = mysql_query($sql);
	$sql = 'SELECT id FROM cerclesnews_tbl WHERE cercle= "'.$cercle.'"' ;
	$req = mysql_query($sql);
	$res = mysql_num_rows($req);
	for($i=0;$i<$res;$i++)
		{
		$sql = 'DELETE FROM cerclescom_tbl WHERE nouvelle="'.mysql_result($req,$i,id).'"' ;
		$req = mysql_query($sql);
		}
	$sql = 'DELETE FROM cerclesnews_tbl WHERE cercle="'.$cercle.'"' ;
	$req = mysql_query($sql);
	$sql = 'SELECT id FROM cerclesvotes_tbl WHERE cercle= "'.$cercle.'"' ;
	$req = mysql_query($sql);
	$res = mysql_num_rows($req);
	for($i=0;$i<$res;$i++)
		{
		$sql = 'DELETE FROM cerclesvotesperso_tbl WHERE idvote="'.mysql_result($req,$i,id).'"' ;
		$req = mysql_query($sql);
		}
	$sql = 'DELETE FROM cerclesvotes_tbl WHERE cercle="'.$cercle.'"' ;
	$req = mysql_query($sql);
	
	$sqlspe = 'INSERT INTO transferts_tbl(donneur,receveur,time,operation,valeur) VALUES("'.$cercle.'","Dreadcast","'.time().'","Suppression cercle","'.$capcer.'")';
	$reqspe = mysql_query($sqlspe);
	
	$sql = 'DELETE FROM cerclesliste_tbl WHERE nom="'.$cercle.'"' ;
	$req = mysql_query($sql);
	
	$sql = 'SELECT id FROM wikast_forum_structure_tbl WHERE type = "-1" AND nom LIKE "Cercle '.$cercle.'"' ;
	$req = mysql_query($sql);
	$idcercle = mysql_result($req,0,id);
	$sql = 'DELETE FROM wikast_forum_structure_tbl WHERE id="'.$idcercle.'"' ;
	$req = mysql_query($sql);
	$sql = 'SELECT id FROM wikast_forum_structure_tbl WHERE type = "'.$idcercle.'"' ;
	$req = mysql_query($sql);
	$res = mysql_num_rows($req);
	for($i=0;$i<$res;$i++)
		{
		$idcercle = mysql_result($req,$i,id);
		
		$sql2 = 'SELECT id FROM wikast_forum_sujets_tbl WHERE categorie = "'.$idcercle.'"' ;
		$req2 = mysql_query($sql2);
		$res2 = mysql_num_rows($req2);
		for($j=0;$j<$res2;$j++)
			{
			$idsujet = mysql_result($req2,$j,id);
			
			$sql3 = 'DELETE FROM wikast_forum_posts_tbl WHERE sujet="'.$idsujet.'"' ;
			$req3 = mysql_query($sql3);
			
			$sql3 = 'DELETE FROM wikast_forum_sujets_tbl WHERE id="'.$idsujet.'"' ;
			$req3 = mysql_query($sql3);
			}
		
		$sql2 = 'DELETE FROM wikast_forum_structure_tbl WHERE id="'.$idcercle.'"' ;
		$req2 = mysql_query($sql2);
		}	
	
	$sql = 'DROP TABLE `e_'.str_replace(" ","_",''.$cercle.'').'_tbl`' ;
	$req = mysql_query($sql);
}

function verification_ouverture_entreprise($entreprise){
	
	//Type de l'entreprise
	$sql = 'SELECT type FROM entreprises_tbl WHERE nom= "'.$entreprise.'"' ;
	$req = mysql_query($sql);
	$type = mysql_result($req,0,type);
	
	$gensauboulot = 0;
	
	//Type des postes qui ouvrent l'entreprise
	if($type=="agence immobiliaire" || $type=="boutique armes" || $type=="centre de recherche" || $type=="boutique spécialisee" || $type=="ventes aux encheres" || $type=="usine de production") $typevoulu = "vendeur";
	elseif($type=="banque") $typevoulu = "banquier";
	elseif($type=="bar cafe") $typevoulu = "serveur";
	elseif($type=="hopital") $typevoulu = "medecin";
	else $gensauboulot = 1;
	
	//Séléction des postes qui ouvrent l'entreprise
	$sql1 = 'SELECT poste FROM `e_'.str_replace(" ","_",$entreprise).'_tbl` WHERE type= "'.$typevoulu.'" OR type="chef"' ;
	$req1 = mysql_query($sql1);
	$res1 = mysql_num_rows($req1);
	for($r=0;$r!=$res1;$r++)
		{
		$poster = mysql_result($req1,$r,poste);
		//Comptage des personnes qui travaillent actuellement aux postes qui ouvrent l'entreprise
		$sql2 = 'SELECT id FROM principal_tbl WHERE entreprise= "'.$entreprise.'" AND type= "'.$poster.'" AND action= "travail"' ;
		$req2 = mysql_query($sql2);
		$gensauboulot += mysql_num_rows($req2);
		}
	//-> Si personne ne bosse
	if($gensauboulot==0)
		{
		$sql = 'UPDATE entreprises_tbl SET ouvert="non" WHERE nom= "'.$entreprise.'"' ;
		$req = mysql_query($sql);
		}
}

function verification_suppression_entreprise($entreprise){
	
	//Séléction des postes de chef
	$sql1 = 'SELECT poste FROM `e_'.str_replace(" ","_",$entreprise).'_tbl` WHERE type="chef"' ;
	$req1 = mysql_query($sql1);
	$res1 = mysql_num_rows($req1);
	$chefsvivants = 0;
	for($r=0;$r!=$res1;$r++)
		{
		$poster = mysql_result($req1,$r,poste);
		//Comptage des chefs encore en vie
		$sql2 = 'SELECT id FROM principal_tbl WHERE entreprise= "'.$entreprise.'" AND type= "'.$poster.'"' ;
		$req2 = mysql_query($sql2);
		$chefsvivants += mysql_num_rows($req2);
		}
	//-> Si tous les chefs sont mort
	if($chefsvivants==0)
		{
		$sql2 = 'SELECT id FROM entreprises_tbl WHERE nom= "'.$entreprise.'"';
		$req2 = mysql_query($sql2);
		supprimer_entreprise(mysql_result($req2,0,id));
		}
}

function mourir($pseudo,$raison,$responsable){

	//INFOS SUR $PSEUDO
	$sql = 'SELECT id,action,entreprise,assurance,ruel,numl FROM principal_tbl WHERE pseudo= "'.$pseudo.'"' ;
	$req = mysql_query($sql);
	$id = mysql_result($req,0,id);
	$action = mysql_result($req,0,action);
	$entreprise = mysql_result($req,0,entreprise);
	$assurance = mysql_result($req,0,assurance);
	$ruel = mysql_result($req,0,ruel);
	$numl = mysql_result($req,0,numl);
	//----------------------------------------------TRAVAIL----------------------------------------------//
	if($entreprise != "Aucune"){
		
		//Nom du poste de $pseudo
		$sql = 'SELECT type FROM principal_tbl WHERE id= "'.$id.'"' ;
		$req = mysql_query($sql);
		$postec = mysql_result($req,0,type);
		
		//Type de poste de $pseudo
		$sql = 'SELECT type FROM `e_'.str_replace(" ","_",$entreprise).'_tbl` WHERE poste= "'.$postec.'"' ;
		$req = mysql_query($sql);
		$typec = mysql_result($req,0,type);
		
		//-> Si $pseudo n'a pas d'assurance, on le vire
		if($assurance==0){
			$sql2 = 'UPDATE principal_tbl SET action= "aucune" , type= "Aucun" , entreprise= "Aucune" , points= "0" , salaire= "0" , difficulte= "0" WHERE id= "'.$id.'"';
			mysql_query($sql2);
			$sql2 = 'UPDATE `e_'.str_replace(" ","_",$entreprise).'_tbl` SET nbreactuel=nbreactuel-1 WHERE poste= "'.$postec.'"';
			mysql_query($sql2);
		}
		
		//-> Si $pseudo était en train de travailler, on vérifie l'ouverture de son entreprise
		if($action=="travail") verification_ouverture_entreprise($entreprise);
		
		//-> Si $pseudo était pdg (et qu'il a été viré), on vérifie la suppression de son entreprise
		if($typec=="chef" && assurance==0) verification_suppression_entreprise($entreprise);
	}
	
	//----------------------------------------------LOGEMENT----------------------------------------------//
	if($ruel != "Aucune"){
		
		$sql = 'SELECT id,pseudo FROM proprietaire_tbl WHERE num= "'.$numl.'" AND rue= "'.$ruel.'"' ;
		$req = mysql_query($sql);
		$idlog = mysql_result($req,0,id);
		$proprio = mysql_result($req,0,pseudo);
		
		//-> $pseudo perd son logement
		$sql2 = 'UPDATE principal_tbl SET ruel= "Aucune" , numl= "0" WHERE id= "'.$id.'"';
		mysql_query($sql2);
		
		//-> Si $pseudo était proprietaire de son logement
		if($pseudo == $proprio) {
			supprimer_logement($idlog);
		
			//-> On déplace tous les gens qui etaient a cet endroit
			$newcoord = coordonnees_sortie_rue($numl,$ruel);
			
			$sql2 = 'UPDATE principal_tbl SET action="aucune", num = "'.$newcoord['num'].'", rue = "'.$newcoord['rue'].'" WHERE num = "'.$numl.'" AND rue = "'.$ruel.'"';
    		mysql_query($sql2);
    		
    		$sql2 = 'DELETE FROM proprietaire_tbl WHERE num = "'.$numl.'" AND rue = "'.$ruel.'"';
    		mysql_query($sql2);
		}
	}
	
	
	//----------------------------------------------MORT----------------------------------------------//
	$sql = 'UPDATE principal_tbl SET Police= "0" , DI2RCO= "0" , action= "mort" , rue= "'.$raison.'" , alim= "0" , fatigue= "0" , num= "0" , drogue= "0" WHERE id= "'.$id.'"';
	$req = mysql_query($sql);
	$sql = 'UPDATE principal_tbl SET action="aucune" WHERE action="Protection '.$pseudo.'"';
	$req = mysql_query($sql);
	$sql = 'DELETE FROM crimes_tbl WHERE pseudo= "'.$pseudo.'"';
	$req = mysql_query($sql);
	$sql = 'DELETE FROM candidatures_tbl WHERE nom= "'.$pseudo.'"';
	$req = mysql_query($sql);
	$sql = 'DELETE FROM casiers_tbl WHERE pseudo= "'.$pseudo.'"' ;
	$req = mysql_query($sql);
	$sql = 'INSERT INTO deces_tbl(id,victime,heure,raison,responsable) VALUES("","'.$pseudo.'","'.time().'","'.$raison.'","'.$responsable.'")' ;
	$req = mysql_query($sql);
	enregistre($pseudo,'Mort '.$raison,"+1");
	if(possede_talent("Sain et sauf",$pseudo)) enregistre($pseudo,'survie',0);
	
}

function masse_monetaire(){
	$sqlcb = 'SELECT credits FROM comptes_tbl WHERE credits>0' ;
	$reqcb = mysql_query($sqlcb);
	$rescb = mysql_num_rows($reqcb);
	for($i=0;$i!=$rescb;$i++) { $cb+=mysql_result($reqcb,$i,credits); }
	$sqlci = 'SELECT credits FROM principal_tbl WHERE credits>0' ;
	$reqci = mysql_query($sqlci);
	$resci = mysql_num_rows($reqci);
	for($i=0;$i!=$resci;$i++) { $ci+=mysql_result($reqci,$i,credits); }
	$sqlce = 'SELECT budget FROM entreprises_tbl WHERE budget>0' ;
	$reqce = mysql_query($sqlce);
	$resce = mysql_num_rows($reqce);
	for($i=0;$i!=$resce;$i++) { $ce+=mysql_result($reqce,$i,budget); }
	$sqlcc = 'SELECT capital FROM cerclesliste_tbl WHERE capital>0' ;
	$reqcc = mysql_query($sqlcc);
	$rescc = mysql_num_rows($reqcc);
	for($i=0;$i!=$rescc;$i++) { $cc+=mysql_result($reqcc,$i,capital); }
	$ctotal = $cb+$ci+$ce+$cc;
	return $ctotal;
}

function nb_joueurs(){
	$sql4 = 'SELECT id FROM principal_tbl' ;
	$req4 = mysql_query($sql4);
	return mysql_num_rows($req4);
}
function nb_morts(){
	$sql4 = 'SELECT id FROM principal_tbl WHERE action = "mort"' ;
	$req4 = mysql_query($sql4);
	return mysql_num_rows($req4);
}
function nb_cryo(){
	$sql4 = 'SELECT id FROM principal_tbl WHERE action = "Vacances"' ;
	$req4 = mysql_query($sql4);
	return mysql_num_rows($req4);
}
function nb_inscrits(){
	$sql4 = 'SELECT id FROM principal_tbl WHERE jours_de_jeu = 1' ;
	$req4 = mysql_query($sql4);
	return mysql_num_rows($req4);
}

function liste_OI(){
	return array("conseil imperial","cipe","cie","doi","prison","police","services techniques de la ville","chambre des lois","di2rco","dc network");
}

$competences_a_filtrer = array();

function liste_competences($sans = null) {
	$competences = array("combat","observation","gestion","maintenance","mecanique","service","discretion","economie","resistance","recherche","tir","vol","medecine","informatique","fidelite");
	if (!$sans)
		return $competences;
	else {
		global $competences_a_filtrer;
		$competences_a_filtrer = $sans;
		return array_values(array_filter($competences, "filtre_competence"));
	}
}

function filtre_competence($var) {
	global $competences_a_filtrer;
	return !in_array($var, $competences_a_filtrer);
}

?>
