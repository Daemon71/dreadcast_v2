<?php

function estDroide ($race = "") {
	if (empty($race))
		$race = $_SESSION['race'];
    return $race == "Droide";
}

function event ($i = 0) {
	if ($i == 2)
		return false;
	if ($i == 3)
		return false;
	if ($i == 4)
		return false;
    return 0;
}

function adm () {
    return $_SESSION['pseudo'] == "Overflow";
}

function estCasse ($id, $type = 1) {
	if ($type == 1) {
		$sql2 = 'SELECT id FROM objets_repares_tbl WHERE id_cible = '.$id;
		$req2 = mysql_query($sql2);
		if (!mysql_num_rows($req2))
			return true;
		return false;
	} elseif ($type == 2) {
		$sql2 = 'SELECT id FROM lieux_repares_tbl WHERE id_lieu = '.$id;
		$req2 = mysql_query($sql2);
		if (!mysql_num_rows($req2))
			return true;
		return false;
	}
	return false;
}

function repare ($id, $type = 1) {
	if ($type == 1) {
		if (!isset($db)) {
			$db = mysql_connect('localhost', 'CENSURE', 'CENSURE') or die("Erreur de connexion au serveur.");
		    mysql_select_db('CENSURE',$db) or die("Erreur de connexion a la base de donnees.");
		}
		
		$sql = 'INSERT INTO objets_repares_tbl VALUES (NULL, '.$id.')';
		mysql_query($sql);
	} elseif ($type == 2) {
		if (!isset($db)) {
			$db = mysql_connect('localhost', 'CENSURE', 'CENSURE') or die("Erreur de connexion au serveur.");
		    mysql_select_db('CENSURE',$db) or die("Erreur de connexion a la base de donnees.");
		}
		
		$sql = 'INSERT INTO lieux_repares_tbl VALUES (NULL, '.$id.')';
		mysql_query($sql);
	}
}

?>
