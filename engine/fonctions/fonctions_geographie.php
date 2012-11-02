<?php

function deplacement($pseudo,$num1,$rue1,$num2,$rue2,$coeff_fatigue=1){
	
	$rue1 = ucfirst(strtolower($rue1));
	$rue1 = str_replace("é","e",$rue1);
	$rue1 = str_replace("è","e",$rue1);
	$rue2 = ucfirst(strtolower($rue2));
	$rue2 = str_replace("é","e",$rue2);
	$rue2 = str_replace("è","e",$rue2);

// Verification du lieu
	$sql = 'SELECT id FROM lieu_tbl WHERE num = '.htmlentities($num2).' AND rue="'.htmlentities($rue2).'"' ;
	$req = mysql_query($sql);
	$res = mysql_num_rows($req);
	if(!$res && !($num2 < 0)) return 'inexistant';
	
// Informations pseudo
	if($_SESSION['pseudo'] == $pseudo) { $id = $_SESSION['id']; $sante = $_SESSION['sante']; }
	else
		{
		$sql = 'SELECT id,sante FROM principal_tbl WHERE pseudo="'.htmlentities($pseudo).'"' ;
		$req = mysql_query($sql);
		$res = mysql_num_rows($req);
		if($res) { $id = mysql_result($req,0,id); $sante = mysql_result($req,0,sante); }
		else return "personne";
		}
	
	if($_SESSION['statut'] == "Debutant")
		{
// Changement de mon statut
		$_SESSION['statut'] = "Joueur";
		$sql = 'UPDATE principal_tbl SET statut="Joueur" WHERE id= "'.$_SESSION['id'].'"' ;
		mysql_query($sql);
	
// Informations partenaires
		$sql = 'SELECT idpartenaire FROM principal_tbl WHERE id= "'.$_SESSION['id'].'"' ;
		$req = mysql_query($sql);
		if(mysql_result($req,0,idpartenaire) != 0)
			{
			$sql1 = 'SELECT inscriptions FROM partenaires_tbl WHERE id= "'.mysql_result($req,0,idpartenaire).'"' ;
			$req1 = mysql_query($sql1);
			$inscrnew = mysql_result($req1,0,inscriptions) + 1;
			$sql2 = 'UPDATE partenaires_tbl SET inscriptions= "'.$inscrnew.'" WHERE id= "'.mysql_result($req,0,idpartenaire).'"' ;
			$req2 = mysql_query($sql2);
			$sql3 = 'SELECT inscrits FROM partenaires_inscriptions_tbl WHERE idpartenaire= "'.mysql_result($req,0,idpartenaire).'" AND tsmois= "'.mktime(0,0,0,date("m"),1,date("Y")).'"' ;
			$req3 = mysql_query($sql3);
			$res3 = mysql_num_rows($req3);
			if($res3>0)
				{
				$inscrnew = mysql_result($req3,0,inscrits) + 1;
				$sql4 = 'UPDATE partenaires_inscriptions_tbl SET inscrits= "'.$inscrnew.'" WHERE idpartenaire= "'.mysql_result($req,0,idpartenaire).'" AND tsmois= "'.mktime(0,0,0,date("m"),1,date("Y")).'"' ;
				$req4 = mysql_query($sql4);
				}
			else
				{
				if(date("m")==1) { $mois = "Janvier"; }
				elseif(date("m")==2) { $mois = "Fevrier"; }
				elseif(date("m")==3) { $mois = "Mars"; }
				elseif(date("m")==4) { $mois = "Avril"; }
				elseif(date("m")==5) { $mois = "Mai"; }
				elseif(date("m")==6) { $mois = "Juin"; }
				elseif(date("m")==7) { $mois = "Juillet"; }
				elseif(date("m")==8) { $mois = "Aout"; }
				elseif(date("m")==9) { $mois = "Septembre"; }
				elseif(date("m")==10) { $mois = "Octobre"; }
				elseif(date("m")==11) { $mois = "Novembre"; }
				elseif(date("m")==12) { $mois = "Decembre"; }
				$sql4 = 'INSERT INTO partenaires_inscriptions_tbl(id,idpartenaire,tsmois,inscrits,taux,mois) VALUES("","'.mysql_result($req,0,idpartenaire).'","'.mktime(0,0,0,date("m"),1,date("Y")).'","1","0.08","'.$mois.'")';
				$req4 = mysql_query($sql4);
				}
			}
		}
	
// Si lieu inaccessible
	$sql1 = 'SELECT type, acces FROM lieux_speciaux_tbl WHERE num = '.htmlentities($num2).' AND rue="'.htmlentities($rue2).'"' ;
	$req1 = mysql_query($sql1);
	$res1 = mysql_num_rows($req1);
	if($res1 && !mysql_result($req1,0,acces)) return 'inaccessible';
	$sql1 = 'SELECT id FROM entreprises_tbl WHERE nom = "di2rco" AND num = '.htmlentities($num2).' AND rue="'.htmlentities($rue2).'"' ;
	$req1 = mysql_query($sql1);
	$res1 = mysql_num_rows($req1);
	if($res1 && !est_dans_inventaire('Passe DI2RCO')) return 'inaccessible';
	
// Remise à zero du code
	if($_SESSION['pseudo'] == $pseudo) $_SESSION['code'] = 0;

// Si rue, sélection du numéro
	if($rue2 == "Rue" || $rue2 == "Ruelle")
		{
		$sql1 = 'SELECT x,y,idrue FROM carte_tbl WHERE num = '.htmlentities($num1).' AND idrue = (SELECT id FROM rues_tbl WHERE nom="'.htmlentities($rue1).'")' ;
		$req1 = mysql_query($sql1);
		$res1 = mysql_num_rows($req1);
		
		$x = mysql_result($req1,0,x);
		$y = mysql_result($req1,0,y);
		$idrue1 = mysql_result($req1,0,idrue);
		
		$sql1 = 'SELECT x,y FROM carte_tbl WHERE idrue = '.$idrue1.' AND type = 0 AND (
		(x = '.($x-1).' AND y = '.$y.') OR
		(x = '.($x+1).' AND y = '.$y.') OR
		(x = '.$x.' AND y = '.($y-1).') OR
		(x = '.$x.' AND y = '.($y+1).')
		)' ;
		$req1 = mysql_query($sql1);
		$res1 = mysql_num_rows($req1);
		if($res1)
			{
			$sql1 = 'SELECT C.num,R.nom FROM carte_tbl C, rues_tbl R WHERE C.x = '.mysql_result($req1,0,x).' AND C.y = '.mysql_result($req1,0,y).' AND C.idrue = R.id';
			$req1 = mysql_query($sql1);
			$num2 = mysql_result($req1,0,num);
			$rue2 = mysql_result($req1,0,nom);
			}
		else
			{
			$sql1 = 'SELECT x,y FROM carte_tbl WHERE idrue = '.$idrue1.' AND type = 0 AND (
			(x = '.($x-1).' AND y = '.($y-1).') OR
			(x = '.($x+1).' AND y = '.($y-1).') OR
			(x = '.($x-1).' AND y = '.($y+1).') OR
			(x = '.($x+1).' AND y = '.($y+1).')
			)' ;
			$req1 = mysql_query($sql1);
			$res1 = mysql_num_rows($req1);
			if($res1)
				{
				$sql1 = 'SELECT C.num,R.nom FROM carte_tbl C, rues_tbl R WHERE C.x = '.mysql_result($req1,0,x).'  AND C.y = '.mysql_result($req1,0,y).' AND C.idrue = R.id';
				$req1 = mysql_query($sql1);
				$num2 = mysql_result($req1,0,num);
				$rue2 = mysql_result($req1,0,nom);
				}
			}
		}

// Si on part de la rue (temporaire)
	if($rue1 == "Rue" || $rue1 == "Ruelle")
		{
		$rue1 = $rue2;
		$num1 = $num2;
		}
	
// Définition du rayon
	$sql1 = 'SELECT x,y FROM carte_tbl WHERE num = '.htmlentities($num1).' AND idrue = (SELECT id FROM rues_tbl WHERE nom="'.htmlentities($rue1).'")' ;
	$req1 = mysql_query($sql1);
	$res1 = mysql_num_rows($req1);
	
	$sql2 = 'SELECT x,y FROM carte_tbl WHERE num = '.htmlentities($num2).' AND idrue = (SELECT id FROM rues_tbl WHERE nom="'.htmlentities($rue2).'")' ;
	$req2 = mysql_query($sql2);
	$res2 = mysql_num_rows($req2);
	
	if(!$res1 || !$res2) return 'inexistant';
	
	$x2 = (mysql_result($req1,0,x)-mysql_result($req2,0,x))*(mysql_result($req1,0,x)-mysql_result($req2,0,x));
	$y2 = (mysql_result($req1,0,y)-mysql_result($req2,0,y))*(mysql_result($req1,0,y)-mysql_result($req2,0,y));
	$rayon = sqrt($x2+$y2);

// Retrait de la forme
	$retrait = ($rayon < 10)?0:(
			   ($rayon < 50)?1*$coeff_fatigue:(
			   ($rayon < 100)?2*$coeff_fatigue:3*$coeff_fatigue
	));
	
	if($retrait > 0 && $sante == 0) return 'sante';
	
	if($retrait > 0)
		{
		forme_retirer($_SESSION['id'],$retrait);
		if($_SESSION['pseudo'] == $pseudo) $_SESSION['fatigue'] -= $retrait;
		}
	
// Conditions action
	$sql = 'SELECT action,entreprise FROM principal_tbl WHERE id='.$id ;
	$req = mysql_query($sql);
	$entreprise = mysql_result($req,0,entreprise);
	$action = mysql_result($req,0,action);
	if($action=="prison") return 'prison';
	if($action=="En cours de Combat 1Heure" || $action=="En cours de Discretion 1Heure" || $action=="En cours de Médecine 1Heure" || $action=="En cours de Tir 1Heure" || $action=="En cours de Eco-Gestion 1Heure" || $action=="En cours de Méca 1Heure" || $action=="En cours de Combat 2Heures" || $action=="En cours de Discretion 2Heures" || $action=="En cours de Médecine 2Heures" || $action=="En cours de Tir 2Heures" || $action=="En cours de Eco-Gestion 2Heures" || $action=="En cours de Méca 2Heures" || $action=="En cours de Combat 3Heures" || $action=="En cours de Discretion 3Heures" || $action=="En cours de Médecine 3Heures" || $action=="En cours de Tir 3Heures" || $action=="En cours de Eco-Gestion 3Heures" || $action=="En cours de Méca 3Heures" || $action=="En cours de Combat 4Heures" || $action=="En cours de Discretion 4Heures" || $action=="En cours de Médecine 4Heures" || $action=="En cours de Tir 4Heures" || $action=="En cours de Eco-Gestion 4Heures" || $action=="En cours de Méca 4Heures") return 'cours';
	
	if(!ereg("Protection ",$action))
		{
// Changement d'état
		$sql = 'UPDATE principal_tbl SET action="aucune" WHERE id='.$id ;
		mysql_query($sql);
		if($_SESSION['pseudo'] == $pseudo) $_SESSION['action'] = 'aucune';
// Verification travail
		if($action=="travail" && $entreprise != "Aucune") verification_ouverture_entreprise($entreprise);
		}
// Enquête
	$vu = rand(0,10);
	$sqlr = 'SELECT id FROM enquete_tbl WHERE num= "'.htmlentities($num2).'" AND rue= "'.htmlentities($rue2).'" AND pseudo= "'.$pseudo.'"' ;
	$reqr = mysql_query($sqlr);
	$resr = mysql_num_rows($reqr);
	if($vu==2 && $resr==0)
		{
		$sql = 'INSERT INTO enquete_tbl(id,pseudo,num,rue) VALUES("","'.$pseudo.'","'.htmlentities($num2).'","'.ucwords(htmlentities($rue2)).'") ';
		$req = mysql_query($sql);
		}
// Deplacement	
	$sql = 'UPDATE principal_tbl SET rue="'.ucwords(strtolower(htmlentities($rue2))).'", num="'.htmlentities($num2).'" WHERE pseudo="'.$pseudo.'"';
	$req = mysql_query($sql);
	if($_SESSION['pseudo'] == $pseudo) { $_SESSION['num'] = $num2; $_SESSION['rue'] = $rue2; }
	
	return 'ok';
}


function coordonnees_sortie_rue($num,$rue){
	
	$sql1 = 'SELECT x,y,idrue FROM carte_tbl WHERE num = '.htmlentities($num).' AND idrue = (SELECT id FROM rues_tbl WHERE nom="'.htmlentities($rue).'")' ;
	$req1 = mysql_query($sql1);
	$res1 = mysql_num_rows($req1);
	if($res1)
		{
		$x = mysql_result($req1,0,x);
		$y = mysql_result($req1,0,y);
		$idrue1 = mysql_result($req1,0,idrue);
		}
	
	$sql1 = 'SELECT x,y FROM carte_tbl WHERE idrue = '.$idrue1.' AND type = 0 AND (
	(x = '.($x-1).' AND y = '.$y.') OR
	(x = '.($x+1).' AND y = '.$y.') OR
	(x = '.$x.' AND y = '.($y-1).') OR
	(x = '.$x.' AND y = '.($y+1).')
	)' ;
	$req1 = mysql_query($sql1);
	$res1 = mysql_num_rows($req1);
	if($res1)
		{
		$sql1 = 'SELECT C.num,R.nom FROM carte_tbl C, rues_tbl R WHERE C.x = '.mysql_result($req1,0,x).' AND C.y = '.mysql_result($req1,0,y).' AND C.idrue = R.id';
		$req1 = mysql_query($sql1);
		$retour['num'] = mysql_result($req1,0,num);
		$retour['rue'] = mysql_result($req1,0,nom);
		}
	else
		{
		$sql1 = 'SELECT x,y FROM carte_tbl WHERE idrue = '.$idrue1.' AND type = 0 AND (
		(x = '.($x-1).' AND y = '.($y-1).') OR
		(x = '.($x+1).' AND y = '.($y-1).') OR
		(x = '.($x-1).' AND y = '.($y+1).') OR
		(x = '.($x+1).' AND y = '.($y+1).')
		)' ;
		$req1 = mysql_query($sql1);
		$res1 = mysql_num_rows($req1);
		if($res1)
			{
			$sql1 = 'SELECT C.num,R.nom FROM carte_tbl C, rues_tbl R WHERE C.x = '.mysql_result($req1,0,x).'  AND X.y = '.mysql_result($req1,0,y).' AND C.idrue = R.id';
			$req1 = mysql_query($sql1);
			$retour['num'] = mysql_result($req1,0,num);
			$retour['rue'] = mysql_result($req1,0,nom);
			}
		}
	
	return $retour;
}



function secteur($num = null, $rue = null) {
	if (!$num && !$rue) {
		$num = $_SESSION['num'];
		$rue = $_SESSION['rue'];
	}
	
	if($num==0 && $rue="Rue") return 0;
	if($num==1 && $rue="Ruelle") return 0;
	$sqlsec = 'SELECT x,y FROM carte_tbl WHERE num="'.$num.'" AND idrue=(SELECT id FROM rues_tbl WHERE nom="'.$rue.'")' ;

	$reqsec = mysql_query($sqlsec);
	$ressec = mysql_num_rows($reqsec);
	if($ressec){
		$mx = mysql_result($reqsec,0,x);
		$my = mysql_result($reqsec,0,y);
		return XY2secteur($mx,$my);
	}
}

function secteurDe($pseudo) {
	$sqlsec = 'SELECT num,rue FROM principal_tbl WHERE pseudo="'.$pseudo.'"' ;
	$reqsec = mysql_query($sqlsec);
	$ressec = mysql_num_rows($reqsec);
	if($ressec){
		$numsec = mysql_result($reqsec,0,num);
		$ruesec = mysql_result($reqsec,0,rue);
	}
	if($numsec==0 && $ruesec="Rue") return 0;
	if($numsec < 0) $numsec = -$numsec; // Si on est dans la rue
	$sqlsec = 'SELECT x,y FROM carte_tbl WHERE num="'.$numsec.'" AND idrue=(SELECT id FROM rues_tbl WHERE nom="'.$ruesec.'")' ;
	$reqsec = mysql_query($sqlsec);
	$ressec = mysql_num_rows($reqsec);
	if($ressec){
		$mx = mysql_result($reqsec,0,x);
		$my = mysql_result($reqsec,0,y);
		return XY2secteur($mx,$my);
	}
}

function ruesDuSecteur($secteur){
	$lim = secteur2XY($secteur);
	$retour = array();
	
	$sql = 'SELECT DISTINCT R.nom FROM carte_tbl C, rues_tbl R WHERE C.idrue = R.id AND C.type = -1 AND 
	(C.x >= '.$lim['x_min'].' AND C.x <= '.$lim['x_max'].') AND 
	(C.y >= '.$lim['y_min'].' AND C.y <= '.$lim['y_max'].')';
	$req = mysql_query($sql);
	$res = mysql_num_rows($req);
	for($i=0;$i<$res;$i++) $retour[] = mysql_result($req,$i,nom);
	
	return $retour;
}

function XY2secteur($mx,$my){
	if($mx >= 1 && $mx <= 50 && $my >= 1 && $my <= 50) return 1;
	if($mx >= 51 && $mx <= 100 && $my >= 1 && $my <= 50) return 2;
	if($mx >= 101 && $mx <= 150 && $my >= 1 && $my <= 50) return 3;
	if($mx >= 1 && $mx <= 50 && $my >= 51 && $my <= 100) return 4;
	if($mx >= 51 && $mx <= 100 && $my >= 51 && $my <= 100) return 5;
	if($mx >= 101 && $mx <= 150 && $my >= 51 && $my <= 100) return 6;
	if($mx >= 1 && $mx <= 50 && $my >= 101 && $my <= 150) return 7;
	if($mx >= 51 && $mx <= 100 && $my >= 101 && $my <= 150) return 8;
	if($mx >= 101 && $mx <= 150 && $my >= 101 && $my <= 150) return 9;
}

function secteur2XY($secteur){
	if($secteur == 1) return array('x_min' => 1, 'x_max' => 50, 'y_min' => 1, 'y_max' => 50);
	if($secteur == 2) return array('x_min' => 51, 'x_max' => 100, 'y_min' => 1, 'y_max' => 50);
	if($secteur == 3) return array('x_min' => 101, 'x_max' => 150, 'y_min' => 1, 'y_max' => 50);
	if($secteur == 4) return array('x_min' => 1, 'x_max' => 50, 'y_min' => 51, 'y_max' => 100);
	if($secteur == 5) return array('x_min' => 51, 'x_max' => 100, 'y_min' => 51, 'y_max' => 100);
	if($secteur == 6) return array('x_min' => 101, 'x_max' => 150, 'y_min' => 51, 'y_max' => 100);
	if($secteur == 7) return array('x_min' => 1, 'x_max' => 50, 'y_min' => 101, 'y_max' => 150);
	if($secteur == 8) return array('x_min' => 51, 'x_max' => 100, 'y_min' => 101, 'y_max' => 150);
	if($secteur == 9) return array('x_min' => 101, 'x_max' => 150, 'y_min' => 101, 'y_max' => 150);
}

function XY2numrue($x,$y){
	$sql = 'SELECT C.num, R.nom FROM carte_tbl C, rues_tbl R WHERE C.idrue = R.id AND C.x = '.$x.' AND C.y = '.$y;
	$req = mysql_query($sql);
	$res = mysql_num_rows($req);
	if($res) return array('num' => mysql_result($req,0,num), 'rue' => mysql_result($req,0,nom));
	else return false;
}

function numrue2XY($num,$rue){
	$sql = 'SELECT x,y FROM carte_tbl WHERE num="'.$num.'" AND idrue=(SELECT id FROM rues_tbl WHERE nom="'.$rue.'")' ;
	$req = mysql_query($sql);
	$res = mysql_num_rows($req);
	if($res) return array('x' => mysql_result($req,0,x), 'y' => mysql_result($req,0,y));
	else return false;
}

function reqListeRue($num,$rue,$rayon){
	$sql = 'SELECT x,y FROM carte_tbl WHERE num="'.$num.'" AND idrue=(SELECT id FROM rues_tbl WHERE nom="'.$rue.'")' ;
	$req = mysql_query($sql);
	$res = mysql_num_rows($req);
	if($res)
		{
		$x = mysql_result($req,0,x);
		$y = mysql_result($req,0,y);
		$sqll = 'SELECT P.id,P.pseudo,P.arme,P.vetements,P.objet,P.action,P.actif,P.race FROM principal_tbl P, rues_tbl R, carte_tbl C WHERE C.type = 0 AND C.idrue = R.id AND R.nom = P.rue AND P.num = C.num AND
		C.x > '.($x-$rayon).' AND C.x < '.($x+$rayon).' AND C.y > '.($y-$rayon).' AND C.y < '.($y+$rayon).'
		AND P.id!="'.$_SESSION['id'].'"' ;
		$reql = mysql_query($sqll);
		}
	return $reql;
}

function estVisible($pseudo,$rayon){
	
	if(strtolower($pseudo) == strtolower($_SESSION['pseudo'])) return true;
	
	if(bonus($pseudo,"invisibilite") && !bonus($_SESSION['pseudo'],"detect")) return false;
	
	if($_SESSION['num'] > 0) 
		{
		$sqll = 'SELECT id FROM principal_tbl WHERE num="'.$_SESSION['num'].'" AND rue="'.$_SESSION['rue'].'" AND pseudo LIKE "'.$pseudo.'"' ;
		$reql = mysql_query($sqll);
		if(mysql_num_rows($reql)) return true;
		}
	else
		{
		$sql = 'SELECT x,y,type FROM carte_tbl WHERE num="'.$_SESSION['num'].'" AND idrue=(SELECT id FROM rues_tbl WHERE nom="'.$_SESSION['rue'].'")' ;
		$req = mysql_query($sql);
		$res = mysql_num_rows($req);
		if($res)
			{
			$type = mysql_result($req,0,type);
			$x = mysql_result($req,0,x);
			$y = mysql_result($req,0,y);
			$sqll = 'SELECT P.id FROM principal_tbl P, rues_tbl R, carte_tbl C WHERE C.type = 0 AND C.idrue = R.id AND R.nom = P.rue AND P.num = C.num AND
			C.x > '.($x-$rayon).' AND C.x < '.($x+$rayon).' AND C.y > '.($y-$rayon).' AND C.y < '.($y+$rayon).'
			AND P.pseudo="'.$pseudo.'"' ;
			$reql = mysql_query($sqll);
			if(mysql_num_rows($reql)) return true;
			}
		}
	return false;
}

function recupereEmplacement($secteur,$rue=""){
	$lim = secteur2XY($secteur);
	
	if($rue==""){
		$sql = 'SELECT C.num, R.nom FROM carte_tbl C, rues_tbl R WHERE C.idrue = R.id AND C.type = -1 AND 
		(C.x >= '.$lim['x_min'].' AND C.x <= '.$lim['x_max'].') AND 
		(C.y >= '.$lim['y_min'].' AND C.y <= '.$lim['y_max'].') ORDER BY RAND() LIMIT 1';
	} else {
		$sql = 'SELECT C.num, R.nom FROM carte_tbl C, rues_tbl R WHERE C.idrue = R.id AND C.type = -1 AND 
		(C.x >= '.$lim['x_min'].' AND C.x <= '.$lim['x_max'].') AND 
		(C.y >= '.$lim['y_min'].' AND C.y <= '.$lim['y_max'].') AND 
		R.nom LIKE "'.$rue.'" ORDER BY RAND() LIMIT 1';
	}
//	echo 'Secteur '.$secteur.' '.var_dump($lim).'<br /><br />';
	$req = mysql_query($sql);
	$res = mysql_num_rows($req);
	if($res) return array('num' => mysql_result($req,0,num), 'rue' => mysql_result($req,0,nom));
	return false;
}

?>
