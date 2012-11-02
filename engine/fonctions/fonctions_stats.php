<?php

function statut($statut = "") {
	if ($statut == "")
		$statut = $_SESSION["statut"];
	if($statut=="Administrateur") $result = 7;
	elseif($statut=="Modérateur") $result = 6;
	elseif($statut=="Platinium") $result = 5;
	elseif($statut=="Gold") $result = 4;
	elseif($statut=="Silver") $result = 3;
	elseif($statut=="Compte VIP") $result = 2;
	elseif($statut=="Joueur") $result = 1;
	else $result = 0;
	return $result;
}

function difficulte($stat) {
	if($stat<10) $difficulte = 6;
	elseif($stat<20) $difficulte = 5;
	elseif($stat<40) $difficulte = 4;
	elseif($stat<80) $difficulte = 3;
	elseif($stat<100) $difficulte = 2;
	elseif($stat<150) $difficulte = 1;
	return $difficulte;
}

function drogue($pseudo,$nombre) {
	$sql = 'SELECT id FROM titres_tbl WHERE pseudo="'.$pseudo.'" AND titre= "Hyperactif"' ;
	$req = mysql_query($sql);
	$res = mysql_num_rows($req);
	if($res>0) return $nombre*2;
	else return ceil(($nombre*50/100)+$nombre);
}

function forme_etat($fatigue,$fatiguemax) {
	if($fatigue>$fatiguemax) $etat = "Sous stupefiants";
	elseif($fatigue==$fatiguemax) $etat = "Compl&egrave;tement repos&eacute;";
	elseif($fatigue>$fatiguemax*70/100) $etat = "Fatigu&eacute;";
	elseif($fatigue>$fatiguemax*30/100) $etat = "En forme";
	else $etat = "Epuis&eacute;";
	return $etat;
}

function forme_retirer($id,$moins) {
	$sql = 'SELECT fatigue FROM principal_tbl WHERE id= '.$id ;
	$req = mysql_query($sql);
	$res = mysql_num_rows($req);
	if($res>0)
		{
		$fatigue = mysql_result($req,0,fatigue)-$moins;
		if($fatigue<0) $fatigue = 0;
		$sql = 'UPDATE principal_tbl SET fatigue= "'.$fatigue.'" WHERE id= "'.$id.'"' ;
		mysql_query($sql);
		}
}

function sante_etat($sante,$santemax) {
	if($sante>$santemax) $etat = "Sous stupefiants";
	elseif($sante==$santemax) $etat = "Pleine forme";
	elseif($sante>$santemax*70/100) $etat = "Blessure l&eacute;g&egrave;re";
	elseif($sante>$santemax*30/100) $etat = "Blessure grave";
	elseif($sante==0) $etat = "Inconscient";
	else $etat = "Agonisant";
	return $etat;
}

function sante_ajouter($id,$plus) {
	$sql = 'SELECT pseudo,drogue,sante,sante_max,soins FROM principal_tbl WHERE id= "'.$id.'"' ;
	$req = mysql_query($sql);
	$res = mysql_num_rows($req);
	if($res>0)
		{
		$pseudo = mysql_result($req,0,pseudo);
		$drogue = mysql_result($req,0,drogue);
		$sante = mysql_result($req,0,sante) + $plus;
		$santemax = mysql_result($req,0,sante_max);
		if($drogue>0 && $sante>drogue($pseudo,$santemax)) $sante = drogue($pseudo,$santemax);
		elseif($drogue==0 && $sante>$santemax) $sante = $santemax;
		$sql = 'UPDATE principal_tbl SET soins=soins+'.$plus.' , sante= "'.$sante.'" WHERE id= "'.$id.'"' ;
		mysql_query($sql);
		}
}

function valeurmax_stat($pseudo,$stat) {
	$sql = 'SELECT '.$stat.'_max FROM principal_tbl WHERE pseudo= "'.$pseudo.'"' ;
	$req = mysql_query($sql);
	$res = mysql_num_rows($req);
	if($res>0) return mysql_result($req,0,$stat.'_max');
	else return 0;
}

function niveau($pseudo) {
	$sql = 'SELECT id FROM titres_tbl WHERE pseudo= "'.$pseudo.'" AND type="Niveau"';
	$req = mysql_query($sql);
	$res = mysql_num_rows($req);
	return $res+1;
}

function augmenter_statistique($id,$competence,$valeur) {
	$sql = 'SELECT pseudo FROM principal_tbl WHERE id= "'.$id.'"';
	$req = mysql_query($sql);
	if(mysql_num_rows($req) != 0)
	{
		$pseudo = mysql_result($req,0,pseudo);
		if($valeur<valeurmax_stat($pseudo,$competence))
			{
			$valeur += sqrt( 1 / ( $valeur + 1 ) );
			if($valeur>valeurmax_stat($pseudo,$competence)) $valeur = valeurmax_stat($pseudo,$competence);
			}
		else $valeur = valeurmax_stat($pseudo,$competence);
		$sql = 'UPDATE principal_tbl SET '.$competence.'= "'.$valeur.'" WHERE id= "'.$id.'"';
		mysql_query($sql);
		enregistre($pseudo,$competence,$valeur);
	}
}

function ajouter_argent_imperium($argent) {
	$sql = 'SELECT valeur FROM donnees_tbl WHERE objet="argent imperium"';
	$req = mysql_query($sql);
	$sql = 'UPDATE donnees_tbl SET valeur= "'.(mysql_result($req,0,valeur)+$argent).'" WHERE objet="argent imperium"';
	mysql_query($sql);
}

function bonus($pseudo,$effet)
	{
	$bonus1 = "";
	$bonus2 = "";
	
	$sql = 'SELECT SUM(bonus) FROM principal_tbl P, objets_tbl O, recherche_effets_tbl R  WHERE P.pseudo LIKE "'.$pseudo.'" AND
	(O.nom LIKE P.arme OR O.nom LIKE P.vetements OR O.nom LIKE P.objet) AND
	R.ido = O.id AND
	nature LIKE "'.$effet.'"';
	$req = mysql_query($sql);
	$res = mysql_num_rows($req);
	if($res) $bonus1 = mysql_result($req,0,"SUM(bonus)");

	if($_SESSION['pseudo']!=$pseudo)
		{
		$sql2 = 'SELECT arme FROM principal_tbl WHERE pseudo="'.$pseudo.'" AND arme LIKE "%-%"';
		$req2 = mysql_query($sql2);
		$res2 = mysql_num_rows($req2);
		} else $res2 = ereg('-',$_SESSION['arme']);
	if($res2)
		{
		$arme = ($_SESSION['pseudo']==$pseudo)?substr($_SESSION['arme'],0,strpos($_SESSION['arme'],'-')):substr(mysql_result($req2,0,arme),0,strpos(mysql_result($req2,0,arme),'-'));
		$sql = 'SELECT SUM(bonus) FROM principal_tbl P, objets_tbl O, recherche_effets_tbl R  WHERE P.pseudo LIKE "'.$pseudo.'" AND
		O.nom LIKE "'.$arme.'" AND
		R.ido = O.id AND
		nature LIKE "'.$effet.'"';
		$req = mysql_query($sql);
		$res += mysql_num_rows($req);
		if($res) $bonus2 = mysql_result($req,0,"SUM(bonus)");
		}
	
	if($res != 0 && ucfirst($effet) != "Aura" && ucfirst($effet) != "Detect" && ucfirst($effet) != "Focus" && ucfirst($effet) != "Invisibilite") return $bonus1+$bonus2;
	elseif($res != 0 && ($bonus1!="" || $bonus2!="")) return true;
	return false;
	}

/*
** Carac Modification With Alcohol.
**
** Created: 2009-10-06
** Last update: 2009-10-06
**
** Author : Xinjins  <tristan.leguern@mspixel.fr>
** Company : MS Pixel
** Version : 0.2
*/

function	alcootest($s_joueur, $i_alcool, $s_sexe, $i_age, $i_taille, $i_res)
{
    // Definition du coefficient de distribution
      $f_coef_dist = $i_res / 100;
    if ($f_coef_dist < ($s_sexe == "Homme" ? 0.70 : 0.60))
      $f_coef_dist = ($s_sexe == "Homme" ? 0.70 : 0.60);

    // Modificateur d\'age
	$f_coef_age = $i_alcool > 0 ? ($i_age >= 15 && $i_age <= 60 ? -0.5 : 0.5) : 0;
	
	if ($f_coef_dist != 0 && $i_taille != 0)
      $i_malus = ($i_alcool / ($i_taille * $f_coef_dist)) + ($f_coef_age);
    if ($i_malus < 0)
      $i_malus = 0;
    return ($i_malus);
}

function	dcstat($s_joueur, $i_stat, $co=1)
{
    if($co==1)
    	{
    	$bdd = mysql_connect("localhost", "CENSURE", "CENSURE");
	    mysql_select_db("CENSURE", $bdd);
	    }

    $sql_ret = mysql_query("SELECT alcool, sexe, age, taille, resistance FROM principal_tbl WHERE pseudo='$s_joueur'") or die(mysql_error());
    $a_stat = mysql_fetch_array($sql_ret, MYSQL_NUM);
    
    if($co==1) mysql_close($bdd);
    
    if ($a_stat[0] != 0)
      {
          $i_alcoolemie = (int)(alcootest($s_joueur, $a_stat[0], $a_stat[1], $a_stat[2], $a_stat[3], $a_stat[4]) * 10);
          $i_malus = $i_alcoolemie * 7 * $i_stat / 100;
          $i_stat = $i_stat - $i_malus;
      }
    return ($i_stat < 0.0 ? 0 : $i_stat);
}

function        random_drunk_word()
{
    $a_ret = array("courgette",
                   "lapin",
                   "empereur",
                   "echarpe",
                   "maman",
                   "alcool",
                   "guitare",
                   "fourchette",
                   "pot à crayon",
                   "poutre apparente",
                   "femme",
                   "homme",
                   "robinet",
                   "stylo",
                   "écran plat",
                   "bizutage",
                   "placard",
                   "wc",
                   "faux plafond",
                   "chaussettes",
                   "double vitrage",
                   "timbre",
                   "portière",
                   "chameau",
                   "stan",
                   "burp",
                   "sauterelle",
                   "coquillette",
                   "dentifrice",
                   "radiateur",
                   "tobogan",
                   "ralonge electrique",
                   "ficelle",
                   "grillage",
                   "imprimante laser",
                   "carburateur",
                   "poubelle",
                   );

    $i = rand(0, count($a_ret) - 1);
    return($a_ret[$i]);
}

function        add_exclamation_mark($a_tmp, $i_nb_word, $state = 0)
{
    if ($state == 0)                                    // Suppression des caracteres de fin de chaine, dont les smiley.
      {
          for ($i = 0 ; $i < $i_nb_word ; $i++)
            $a_tmp[$i] = trim($a_tmp[$i], ".,?!;:)(^ ");
      }
    else                                                // Ajout des points d\'exclamation.
      {
          $a_tmp[$i_nb_word - 1] = $a_tmp[$i_nb_word - 1] . " !!!";
      }
    return ($a_tmp);
}

function        swap_word($a_tmp, $i_nb_word)
{
    $i = ($i_nb_word / 6 < 1) ? 1 : ($i_nb_word / 6);
    for (; $i > 0 ; $i--)
      {
          $i_rand1 = rand(($a_tmp[0] == "/me") ? 1 : 0, $i_nb_word - 1);
          if ($i_rand1 == $i_nb_word - 1)
            $i_rand2 = $i_rand1 - 1;
          else
            $i_rand2 = $i_rand1 + 1;
          $tmp = $a_tmp[$i_rand1];
          $a_tmp[$i_rand1] = $a_tmp[$i_rand2];
          $a_tmp[$i_rand2] = $tmp;
      }
    return ($a_tmp);
}

function        replace_word($a_tmp, $i_nb_word)       // Le joueur se trompe de mot.
{
    $i_rand = rand(0, 2);
    if ($i_nb_word > 4 && $i_rand > 0)
      {
          $i_rand = rand(($a_tmp[0] == "/me"), $i_nb_word - 1);
          $a_tmp[$i_rand] = random_drunk_word();
      }
    else if ($i_rand == 0)
      {
          $i_rand = rand(($a_tmp[0] == "/me"), $i_nb_word - 1);
          $a_tmp[$i_rand] = "hips !";
      }
    return ($a_tmp);
}

function        shuffle_word($a_tmp, $i_nb_word)       // Le joueur est trop ivre pour etre comprehensible, ou alors il faut s\'accrocher !
{
    for ($i = 0 ; $i < $i_nb_word ; $i++)
      {
          if ($a_tmp[$i] != "hips !")
            $a_tmp[$i] = str_shuffle($a_tmp[$i]);
      }
    return ($a_tmp);
}

function        too_much_alcohol($a_tmp, $i_nb_word)
{
    $a_tmp = explode(' ', "/me est trop ivre pour parler.");
    return ($a_tmp);
}

function        alcohol_speak($s_str, $i_alcoolemie)
{
    if ($i_alcoolemie == 0)
      return ($s_str);

    $a_func = array(3 => "add_exclamation_mark",
                    4 => "replace_word",
                    5 => "replace_word",
                    6 => "replace_word",
                    7 => "swap_word",
                    8 => "swap_word",
                    9 => "swap_word",
                    10 => "swap_word",
                    11 => "swap_word",
                    12 => "shuffle_word",
                    15 => "too_much_alcohol");          // Alcoolemie * 10

    $a_tmp = explode(' ', $s_str);
    $i_nb_word = count($a_tmp);

    if ($i_nb_word <= 1 && $i_alcoolemie >= 5 && $i_alcoolemie < 15)
      return (str_shuffle($s_str));
    else if ($i_alcoolemie >= 15)
      return(implode(" ", too_much_alcohol(NULL, 0)));

    for ($i = 1 ; $i <= 15; $i++)
      {
          if ($i_alcoolemie >= $i && !empty($a_func[$i]))
            {
                $a_tmp = $a_func[$i]($a_tmp, $i_nb_word);
            }
      }
    $a_tmp = add_exclamation_mark($a_tmp, $i_nb_word, 1);
    $s_str = ucfirst(ltrim(strtolower(implode(" ", $a_tmp)), " "));
    return ($s_str);
}

?>
