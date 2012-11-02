<?php

function deplace_logement($num, $rue , $secteur, $enabled = false, $print = false, $nrue = null) {
	$xy = trouve_coordonnees_libres($secteur, $nrue);
	$xylogement = trouve_coordonnees_logement($num, $rue);
    if (empty($xylogement)) {
        echo 'Logement introuvable.<br />';
        return null;
    }
    $ancienne_adresse = XY2numrue($xylogement['x'], $xylogement['y']);
    $nouvelle_adresse = XY2numrue($xy['x'], $xy['y']);
    
    if ($print) {
	    echo "Ancienne adresse du logement :<br />".$ancienne_adresse['num']." ".$ancienne_adresse['rue']." (".$xylogement['x'].",".$xylogement['y'].")<br />";
	    echo "Nouvelle adresse :<br />".$nouvelle_adresse['num']." ".$nouvelle_adresse['rue']." (".$xy['x'].",".$xy['y'].") (secteur ".secteur($nouvelle_adresse['num'], $nouvelle_adresse['rue']).", $secteur demandé)<br /><br />";
    }
    
    $sql = "UPDATE lieu_tbl SET num = ".$nouvelle_adresse['num'].", rue = '".$nouvelle_adresse['rue']."' WHERE id = ".$xylogement['id'];
	if ($enabled)
		$req = mysql_query($sql);
	
	$sql = "UPDATE proprietaire_tbl SET num = ".$nouvelle_adresse['num'].", rue = '".$nouvelle_adresse['rue']."' WHERE num = ".$ancienne_adresse['num']." AND rue LIKE '".$ancienne_adresse['rue']."'";
	if ($enabled)
		$req = mysql_query($sql);
	
	$sql = 'UPDATE carte_tbl SET type = 1 WHERE x = '.$xy['x'].' AND y = '.$xy['y'];
	if ($enabled)
		$req = mysql_query($sql);
//    echo "$sql<br />";
        
    $sql = 'UPDATE carte_tbl SET type = -1 WHERE x = '.$xylogement['x'].' AND y = '.$xylogement['y'];
	if ($enabled)
		$req = mysql_query($sql);
//    echo "$sql<br />";
	
	 if ($enabled && $print)
	    echo "<br />Le logement a été déplacée au ".$nouvelle_adresse['num']." ".$nouvelle_adresse['rue']." [S$secteur] avec succès !";
	   
	return $nouvelle_adresse['num']." ".$nouvelle_adresse['rue'];
}

function deplace_cercle($entreprise, $secteur, $enabled = false, $print = false, $nrue = null) {
	
}

function deplace_entreprise($entreprise, $secteur, $enabled = false, $print = false, $nrue = null) {

    $xy = trouve_coordonnees_libres($secteur, $nrue);
    $xyentreprise = trouve_coordonnees_entreprise($entreprise);
    if (empty($xyentreprise)) {
        echo 'Entreprise introuvable.<br />';
        return null;
    }
    $ancienne_adresse = XY2numrue($xyentreprise['x'], $xyentreprise['y']);
    $nouvelle_adresse = XY2numrue($xy['x'], $xy['y']);
    
    if ($print) {
	    echo "Ancienne adresse de l'entreprise <strong>$entreprise</strong> :<br />".$ancienne_adresse['num']." ".$ancienne_adresse['rue']." (".$xyentreprise['x'].",".$xyentreprise['y'].")<br />";
	    echo "Nouvelle adresse :<br />".$nouvelle_adresse['num']." ".$nouvelle_adresse['rue']." (".$xy['x'].",".$xy['y'].") (secteur ".secteur($nouvelle_adresse['num'], $nouvelle_adresse['rue']).", $secteur demandé)<br /><br />";
    }
    
    $sql = "UPDATE lieu_tbl SET num = ".$nouvelle_adresse['num'].", rue = '".$nouvelle_adresse['rue']."' WHERE num = ".$ancienne_adresse['num']." AND rue LIKE '".$ancienne_adresse['rue']."'";
	if ($enabled)
		$req = mysql_query($sql);
//    echo "$sql<br />";
    
    $sql = "UPDATE entreprises_tbl SET num = ".$nouvelle_adresse['num'].", rue = '".$nouvelle_adresse['rue']."' WHERE num = ".$ancienne_adresse['num']." AND rue LIKE '".$ancienne_adresse['rue']."'";
	if ($enabled)
		$req = mysql_query($sql);
//    echo "$sql<br />";
    
    $sql = 'UPDATE carte_tbl SET type = 1 WHERE x = '.$xy['x'].' AND y = '.$xy['y'];
	if ($enabled)
		$req = mysql_query($sql);
//    echo "$sql<br />";
        
    $sql = 'UPDATE carte_tbl SET type = -1 WHERE x = '.$xyentreprise['x'].' AND y = '.$xyentreprise['y'];
	if ($enabled)
		$req = mysql_query($sql);
//    echo "$sql<br />";
    
    $sql = "UPDATE principal_tbl SET num = ".$nouvelle_adresse['num'].", rue = '".$nouvelle_adresse['rue']."' WHERE num = ".$ancienne_adresse['num']." AND rue LIKE '".$ancienne_adresse['rue']."'";
	if ($enabled)
		$req = mysql_query($sql);
//    echo "$sql<br />";
    
    deplace_chat($ancienne_adresse, $nouvelle_adresse, $enabled);
    deplace_inventaire($ancienne_adresse, $nouvelle_adresse, $enabled);
    
    if ($enabled && $print)
	    echo "<br />L'entreprise $entreprise a été déplacée au ".$nouvelle_adresse['num']." ".$nouvelle_adresse['rue']." [S$secteur] avec succès !";
	   
	return $nouvelle_adresse['num']." ".$nouvelle_adresse['rue'];
}

function deplace_chat($ancienne_adresse, $nouvelle_adresse, $enabled = false) {
    $sql = "DELETE FROM chat WHERE num = ".$nouvelle_adresse['num']." AND rue = '".$nouvelle_adresse['rue']."'";
	if ($enabled)
		$req = mysql_query($sql);
//    echo "$sql<br />";
    
    $sql = "UPDATE chat SET num = ".$nouvelle_adresse['num'].", rue = '".$nouvelle_adresse['rue']."' WHERE num = ".$ancienne_adresse['num']." AND rue LIKE '".$ancienne_adresse['rue']."'";
	if ($enabled)
		$req = mysql_query($sql);
//    echo "$sql<br />";
}

function deplace_inventaire($ancienne_adresse, $nouvelle_adresse, $enabled = false) {
    $sql = "DELETE FROM invlieu_tbl WHERE num = ".$nouvelle_adresse['num']." AND rue = '".$nouvelle_adresse['rue']."'";
	if ($enabled)
		$req = mysql_query($sql);
//    echo "$sql<br />";
    
    $sql = "UPDATE invlieu_tbl SET num = ".$nouvelle_adresse['num'].", rue = '".$nouvelle_adresse['rue']."' WHERE num = ".$ancienne_adresse['num']." AND rue LIKE '".$ancienne_adresse['rue']."'";
	if ($enabled)
		$req = mysql_query($sql);
//    echo "$sql<br />";
}

function trouve_coordonnees_libres($secteur = null, $rue = null) {
    if ($secteur) {
        $coord = secteur2XY($secteur);
        $xmin = $coord['x_min'];
        $xmax = $coord['x_max'];
        $ymin = $coord['y_min'];
        $ymax = $coord['y_max'];
    } else {
        $xmin = 1;
        $xmax = 150;
        $ymin = 1;
        $ymax = 150;
    }
    
    if ($rue) {
	    $sql = 'SELECT c.x, c.y
	    		  FROM carte_tbl c
	    	INNER JOIN rues_tbl r
	    			ON c.idrue = r.id
	    		 WHERE
	    ( c.x >= '.$xmin.' AND c.x <= '.$xmax.' ) AND
	    ( c.y >= '.$ymin.' AND c.y <= '.$ymax.' ) AND
	    c.type = -1
	    AND r.nom LIKE "%'.$rue.'%"
	    ORDER BY rand() LIMIT 1';
    } else {
    	$sql = 'SELECT x,y FROM carte_tbl WHERE
	    ( x >= '.$xmin.' AND x <= '.$xmax.' ) AND
	    ( y >= '.$ymin.' AND y <= '.$ymax.' ) AND
	    type = -1
	    ORDER BY rand() LIMIT 1';
    }
    $req = mysql_query($sql);
    
    if (mysql_num_rows($req))
        return mysql_fetch_assoc($req);
    
    return null;
}

function trouve_coordonnees_entreprise($entreprise) {
    $sql = 'SELECT C.x,C.y FROM carte_tbl C, rues_tbl R, entreprises_tbl E WHERE
    C.num = E.num AND
    E.rue LIKE R.nom AND
    R.id = C.idrue AND
    E.nom LIKE "'.$entreprise.'"';
    $req = mysql_query($sql);
    
    if (mysql_num_rows($req))
        return mysql_fetch_assoc($req);
    
    return null;
}

function trouve_coordonnees_logement($num, $rue) {
	$sql = "SELECT C.x,C.y,L.id
	FROM carte_tbl C
	INNER JOIN rues_tbl R ON C.idrue = R.id
	INNER JOIN lieu_tbl L ON R.nom LIKE L.rue AND C.num = L.num
	WHERE L.num = $num AND L.rue LIKE '$rue' AND L.nom NOT LIKE 'Local %'";
    $req = mysql_query($sql);
    
    if (mysql_num_rows($req))
        return mysql_fetch_assoc($req);
    
    return null;
}

/*
** get_all_objets
** Recupere tous les objets demandes
**
** Created: 2009-10-29-40-20
** Last update: 2009-10-30-38-53
**
** Author : Xinjins  <tristan.leguern@mspixel.fr>
** Company : MS Pixel
** Version : 1.0
*/

function        get_all_objets($a_obj_liste, $i_obj_number)
{
    $bdd = mysql_connect("localhost", "CENSURE", "CENSURE");
    mysql_select_db("CENSURE", $bdd);
    for ($i = 0 ; $i < $i_obj_number ; $i++)
      {
          $s_current_obj_name = $a_obj_liste[$i];

          $sql_bank = mysql_query("SELECT id,code,emp1,emp2,emp3,emp4,emp5,emp6,emp7,emp8 FROM comptes_tbl WHERE emp1 LIKE '$s_current_obj_name%'
                                        OR emp2 LIKE '$s_current_obj_name%' OR emp3 LIKE '$s_current_obj_name%' OR emp4 LIKE '$s_current_obj_name%' OR emp5 LIKE '$s_current_obj_name%'
                                        OR emp6 LIKE '$s_current_obj_name%' OR emp7 LIKE '$s_current_obj_name%' OR emp8 LIKE '$s_current_obj_name%'");
          $sql_invlieu = mysql_query("SELECT * FROM invlieu_tbl WHERE nom LIKE '$s_current_obj_name%'");
          $sql_invjoueur = mysql_query("SELECT pseudo,case1,case2,case3,case4,case5,case6,arme,vetements,objet FROM principal_tbl WHERE case1 LIKE '$s_current_obj_name%'
                                        OR case2 LIKE '$s_current_obj_name%' OR case3 LIKE '$s_current_obj_name%' OR case4 LIKE '$s_current_obj_name%' OR case5 LIKE '$s_current_obj_name%'
                                        OR case6 LIKE '$s_current_obj_name%' OR arme LIKE '$s_current_obj_name%' OR vetements LIKE '$s_current_obj_name%' OR objet LIKE '$s_current_obj_name%'")
                          OR exit(mysql_error());
          $sql_vente = mysql_query("SELECT id,acheteur,vendeur,objet FROM vente_tbl WHERE objet LIKE '$s_current_obj_name%'") OR exit(mysql_error());
          $sql_sac = mysql_query("SELECT id,emplacement FROM sacs_tbl WHERE emplacement LIKE '$s_current_obj_name%'") OR exit(mysql_error());

          $a_final[$i]['objet'] = $s_current_obj_name;
          for ($lol = 0 ; $a_final[$i]['bank'][$lol] = mysql_fetch_assoc($sql_bank) ; $lol++);
          for ($lol = 0 ; $a_final[$i]['lieu'][$lol] = mysql_fetch_assoc($sql_invlieu) ; $lol++);
          for ($lol = 0 ; $a_final[$i]['joueur'][$lol] = mysql_fetch_assoc($sql_invjoueur) ; $lol++);
          for ($lol = 0 ; $a_final[$i]['vente'][$lol] = mysql_fetch_assoc($sql_vente) ; $lol++);
          for ($lol = 0 ; $a_final[$i]['sac'][$lol] = mysql_fetch_assoc($sql_sac) ; $lol++);

      }

    mysql_close( $bdd );
    return ($a_final);
}


/*
 ** replace_objects
 ** Remplace tous les objets demandes par un autre
 **
 ** Created: 2009-10-29-40-20
 ** Last update: 2009-10-29-40-20
 **
 ** Author : Xinjins  <tristan.leguern@mspixel.fr>
 ** Company : MS Pixel
 ** Version : 1.0
*/

function        replace_objects($a_obj_liste)
{
    $bdd = mysql_connect("localhost", "CENSURE", "CENSURE");
    mysql_select_db("CENSURE", $bdd);

    if (empty($a_obj_liste[1]) == TRUE)
      return (FALSE);

    $a_obj_liste[1]['objet'] = trim($a_obj_liste[1]['objet']);

    for ($lol = 0 ; $a_obj_liste[0]['bank'][$lol] ; $lol++)
      {
          $sql_query = "UPDATE `comptes_tbl` SET ";

          for ($j = 0 ; $j <= 8 ; $j++, $s_emp = "emp" . $j)
            if (strcasecmp($a_obj_liste[0]['bank'][$lol][$s_emp], $a_obj_liste[0]['objet']) == 0)
              $sql_query = $sql_query . "`$s_emp`='" . $a_obj_liste[1]['objet'] . "', ";

          $sql_query = rtrim( $sql_query, ", " ) . "WHERE `comptes_tbl`.`id`=" . $a_obj_liste[0]['bank'][$lol]['id'] . " LIMIT 1;";
          if (strlen($sql_query) > 62)
            mysql_query($sql_query) OR die(mysql_error());
      }

    for ($j = 0 ; $a_obj_liste[0]['lieu'][$j] ; $j++)
      mysql_query("UPDATE `invlieu_tbl` SET `nom`='" . $a_obj_liste[1]['objet'] . "' WHERE `id`=" . $a_obj_liste[0]['lieu'][$j]['id']) OR die(mysql_error());

    for ($lol = 0 ; $a_obj_liste[0]['joueur'][$lol] ; $lol++)
      {
          $sql_query = "UPDATE `principal_tbl` SET ";

          for ($j = 0 ; $j <= 6 ; $j++,  $s_emp = "case" . $j)
            if (strcasecmp($a_obj_liste[0]['joueur'][$lol][$s_emp], $a_obj_liste[0]['objet']) == 0)
              $sql_query = $sql_query . "`" . $s_emp . "`" . "='" . $a_obj_liste[1]['objet'] . "', ";

          if (strcasecmp($a_obj_liste[0]['joueur'][$lol]['arme'], $a_obj_liste[0]['objet']) == 0)
            $sql_query = $sql_query . "`arme`='" . $a_obj_liste[1]['objet'] . "', ";
          if (strcasecmp($a_obj_liste[0]['joueur'][$lol]['vetements'], $a_obj_liste[0]['objet']) == 0)
            $sql_query = $sql_query . "`vetements`='" . $a_obj_liste[1]['objet'] . "', ";
          if (strcasecmp($a_obj_liste[0]['joueur'][$lol]['objet'], $a_obj_liste[0]['objet']) == 0)
            $sql_query = $sql_query . "`objet`='" . $a_obj_liste[1]['objet'] . "', ";

          if (strlen($sql_query) > 28)
            mysql_query(rtrim( $sql_query, ", " ) . "WHERE `principal_tbl`.`pseudo`='" . $a_obj_liste[0]['joueur'][$lol]['pseudo'] . "';") OR die(mysql_error());
      }

    for ($j = 0 ; $a_obj_liste[0]['vente'][$j] ; $j++)
      mysql_query("UPDATE `vente_tbl` SET `objet`='" . $a_obj_liste[1]['objet'] . "' WHERE `id`=" . $a_obj_liste[0]['vente'][$j]['id']) OR die(mysql_error());

    for ($j = 0 ; $a_obj_liste[0]['sac'][$j] ; $j++)
      mysql_query("UPDATE `sacs_tbl` SET `emplacement`='" . $a_obj_liste[1]['objet'] . "' WHERE `emplacement`='" . $a_obj_liste[0]['sac'][$j]['emplacement'] . "';");

    mysql_close( $bdd );

}

/*
** count_objects
** Detail les emplacements des objets demandes
**
** Created: 2009-10-29-40-20
** Last update: 2009-10-29-40-20
**
** Author : Xinjins  <tristan.leguern@mspixel.fr>
** Company : MS Pixel
** Version : 1.0
*/

function        count_objects($a_obj_liste, $i_obj_number)
{
    for ($i = 0 ; $i < $i_obj_number ; $i++)
      {
          $i_nb_current_obj = 0;
          echo "<h2>" . $a_obj_liste[$i]['objet'] . "</h2>";

          echo "<em>BANK</em> :<br>";
          for ($lol = 0 ; $a_obj_liste[$i]['bank'][$lol] ; $lol++)
            {
                echo "Compte en banque numero ".$a_obj_liste[$i]['bank'][$lol]['code']." (id ".$a_obj_liste[$i]['bank'][$lol]['id'].").<br />";
                for ($j = 1 ; $j <= 8 ; $j++)
                  {
                      $s_emp = "emp" . $j;
                      if (strcasecmp($a_obj_liste[$i]['bank'][$lol][$s_emp], $a_obj_liste[$i]['objet']) == 0)
                        $i_nb_current_obj++;
                  }
            }

          echo "<em>LOGEMENT</em> :<br>";
          for ($j = 0 ; $a_obj_liste[$i]['lieu'][$j] ; $j++)
            {
                echo "Au ".$a_obj_liste[$i]['lieu'][$j]['num']." ".$a_obj_liste[$i]['lieu'][$j]['rue']."<br />";
                $i_nb_current_obj += count($a_obj_liste[$i]['lieu']) - 1;
            }

          echo "<em>PERSO</em> :<br>";
          for ($lol = 0 ; $a_obj_liste[$i]['joueur'][$lol] ; $lol++)
            {
                echo "Sur ".$a_obj_liste[$i]['joueur'][$lol]['pseudo']."<br />";
                for ($j = 1 ; $j <= 6 ; $j++)
                  {
                      $s_emp = "case" . $j;
                      if (strcasecmp($a_obj_liste[$i]['joueur'][$lol][$s_emp], $a_obj_liste[$i]['objet']) == 0)
                        $i_nb_current_obj++;
                  }
                if (strcasecmp($a_obj_liste[$i]['joueur'][$lol]['arme'], $a_obj_liste[$i]['objet']) == 0)
                  $i_nb_current_obj++;
                if (strcasecmp($a_obj_liste[$i]['joueur'][$lol]['vetements'], $a_obj_liste[$i]['objet']) == 0)
                  $i_nb_current_obj++;
                if (strcasecmp($a_obj_liste[$i]['joueur'][$lol]['objet'], $a_obj_liste[$i]['objet']) == 0)
                  $i_nb_current_obj++;
            }

          echo "<em>ENCHERE</em> :<br>";
          for ($j = 0 ; $a_obj_liste[$i]['vente'][$j] ; $j++)
            {
                echo "Au enchere : acheteur=".$a_obj_liste[$i]['vente'][$j]['acheteur']." vendeur=".$a_obj_liste[$i]['vente'][$j]['vendeur']."<br />";
                $i_nb_current_obj += count($a_obj_liste[$i]['vente']) - 1;
            }

          echo "<em>SAC</em> :<br>";
          for ($j = 0 ; $a_obj_liste[$i]['sac'][$j] ; $j++)
            {
                echo "Dans le sac :" . $a_obj_liste[$i]['sac'][$j]['id'] . "<br>";
                $i_nb_current_obj++;
            }

          printf("<br /><strong>Il y a %d %s</strong><br /> <br />", $i_nb_current_obj, $a_obj_liste[$i]['objet']);
      }
}

?>
