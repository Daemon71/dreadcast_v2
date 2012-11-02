<?php
	
function est_dans_inventaire($objet, $pseudo = "")
	{
	if(preg_match("#^[0-9]+$#isU",$objet)) // SI L'OBJET NE CONTIENT QUE DES CHIFFRES (id)
		{
		$sql = 'SELECT nom FROM objets_tbl WHERE id="'.$objet.'"';
		$req = mysql_query($sql);
		if(mysql_num_rows($req)) $objet = mysql_result($req,0,nom);
		}
	
	if (empty($pseudo))
		$pseudo = $_SESSION['pseudo'];

	$sql = 'SELECT case1,case2,case3,case4,case5,case6 FROM principal_tbl WHERE pseudo = "'.$pseudo.'"';
	$req = mysql_query($sql);
	
	if ($pseudo == $_SESSION['pseudo']) {
		$_SESSION['case1'] = mysql_result($req,0,case1);
		$_SESSION['case2'] = mysql_result($req,0,case2);
		$_SESSION['case3'] = mysql_result($req,0,case3);
		$_SESSION['case4'] = mysql_result($req,0,case4);
		$_SESSION['case5'] = mysql_result($req,0,case5);
		$_SESSION['case6'] = mysql_result($req,0,case6);
	}
	
	$i=1;
	for($i;$i<=6;$i++) if(mysql_result($req,0,'case'.$i) == $objet) break;
	if($i != 7) return true;
	return false;
	}

function inventaire_libre() {
	$retour = array();
	
	if ($_SESSION['case1'] == "Vide")
		$retour[] = 1;
	if ($_SESSION['case2'] == "Vide")
		$retour[] = 2;
	if ($_SESSION['case3'] == "Vide")
		$retour[] = 3;
	if ($_SESSION['case4'] == "Vide")
		$retour[] = 4;
	if ($_SESSION['case5'] == "Vide")
		$retour[] = 5;
	if ($_SESSION['case6'] == "Vide")
		$retour[] = 6;
		
	return $retour;
}

?>
