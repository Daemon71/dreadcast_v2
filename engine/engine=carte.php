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

<div id="centremap">
	
	<?php
		
	$db = mysql_connect('localhost', 'CENSURE', 'CENSURE') or die("Erreur de connexion au serveur des messages.");
	mysql_select_db('CENSURE',$db) or die("Erreur de connexion a la base de donnees des messages.");
	
	$sql = 'SELECT num,rue FROM principal_tbl WHERE id='.$_SESSION['id'];
	$req = mysql_query($sql);
	$_SESSION['num'] = mysql_result($req,0,num);
	$_SESSION['rue'] = mysql_result($req,0,rue);
	
	
	if($_SESSION['rue'] != "Rue" && $_SESSION['rue'] != "Ruelle")
		{
		$sql = 'SELECT idrue,x,y FROM carte_tbl WHERE idrue=(SELECT id FROM rues_tbl WHERE nom LIKE "'.$_SESSION['rue'].'") AND num="'.$_SESSION['num'].'"' ;
		$req = mysql_query($sql);
		
		$monidrue = mysql_result($req,0,idrue);
		$monx = mysql_result($req,0,x);
		$mony = mysql_result($req,0,y);
		
		$x = ($_GET['x']!="")?$_GET['x']:mysql_result($req,0,x);
		$y = ($_GET['y']!="")?$_GET['y']:mysql_result($req,0,y);
		$zoom = ($_GET['zoom']!="" && $_GET['zoom']<=3 && $_GET['zoom']>=1)?$_GET['zoom']:'1';
		}
	else
		{
		$sql = 'UPDATE principal_tbl SET num=-1, rue="Avenue Imperiale" WHERE id = '.$_SESSION['id'] ;
		$req = mysql_query($sql);
		$_SESSION['rue'] = "Avenue Imperiale";
		$_SESSION['num'] = -1;
		print('<meta http-equiv="refresh" content="0 ; url=http://v2.dreadcast.net/ingame/engine=carte.php"> ');
		exit();
		}
	
	$infos = "";
	
// DEPLACEMENT
	if($_GET['go']=="ok" && $_GET['x']!="" && $_GET['y']!="")
		{
		$sql = 'SELECT C.num,R.nom FROM carte_tbl C, rues_tbl R WHERE C.x='.htmlentities($_GET['x']).' AND C.y='.htmlentities($_GET['y']).' AND C.idrue=R.id' ;
		$req = mysql_query($sql);
		$res = mysql_num_rows($req);
		if($res)
			{
			$retour = deplacement($_SESSION['pseudo'],$_SESSION['num'],$_SESSION['rue'],mysql_result($req,0,num),mysql_result($req,0,nom));
			if($retour == 'ok')
				{
				$monx = $x;
				$mony = $y;
				}
			else
				{
				$x = $monx;
				$y = $mony;
				
				if($retour == 'prison') $infos = "Vous êtes en prison et ne pouvez pas vous déplacer.";
				elseif($retour == 'cours') $infos = "Vous ne pouvez pas sortir du cours.";
				elseif($retour == 'inaccessible') $infos = "Ce lieu est inaccessible.";
				elseif($retour == 'sante') $infos = "Vous êtes trop mal en point pour pouvoir vous déplacer aussi loin.";
				}
			}
		else $infos = "Il n'y a rien à cet endroit.";
		}
	
// TRAITEMENT DE LA RECHERCHE
	if($_POST['chercher_lieu']!="")
		{
		$chaine = trim(htmlentities($_POST['chercher_lieu']));
		if(ereg("^[0-9]{1,3} [a-zA-Z ]+$",$chaine))
			{
			$num = trim(preg_replace("#[a-zA-Z ]+$#","",$chaine));
			$rue = trim(preg_replace("#^[0-9]{1,3}#","",$chaine));
			
			$sql = 'SELECT x,y FROM carte_tbl WHERE num='.$num.' AND idrue=(SELECT id FROM rues_tbl WHERE nom LIKE "'.$rue.'")';
			$req = mysql_query($sql);
			$res = mysql_num_rows($req);
			
			if($res)
				{
				$x = mysql_result($req,0,x);
				$y = mysql_result($req,0,y);
				
				$rechx = $x;
				$rechy = $y;
				
				$retour = trouver_lieu($num,$rue);
				$nom = $retour['nom'];
				$type = $retour['type'];
				$ouvert = $retour['ouvert'];
				$image = $retour['image'];
				
				if($nom != "")
					{
					$infos = '<a href=engine=carte.php?x='.$x.'&y='.$y.'&zoom='.$zoom.'>'.$nom.'</a>'.(($ouvert != 'Fermé')?' - <a href=engine=carte.php?x='.$x.'&y='.$y.'&zoom='.$zoom.'&go=ok>Go !</a>':'').'<br />
					<em>'.$type.'</em><br />
					'.$num.' '.ucfirst($rue).'<br />
					<span style=&quot;font-size:10px; &quot;>Secteur '.XY2secteur($x,$y).'</span>';
				
					$infos = str_replace('&quot;','"',$infos);
					}
				else $infos = "Il n'y a rien au ".$_POST['chercher_lieu'];
				}
			}
		elseif(strlen(stripslashes($_POST['chercher_lieu'])) < 3) $infos = "La recherche doit comporter au moins 3 caractères.";
		else
			{
			// AGENCE
			if(strtolower($_POST['chercher_lieu']) == "agence" || strtolower($_POST['chercher_lieu']) == "agence immobiliaire")
				{
				$truc['rayon'] = 10000;
				$sql = 'SELECT nom,logo,num,rue FROM entreprises_tbl WHERE type = "agence immobiliaire" AND ouvert="oui"';
				$req = mysql_query($sql);
				$res = mysql_num_rows($req);
				for($i=0;$i<$res;$i++)
					{
					$sql2 = 'SELECT x,y FROM carte_tbl WHERE num="'.mysql_result($req,$i,num).'" AND idrue=(SELECT id FROM rues_tbl WHERE nom="'.mysql_result($req,$i,rue).'")';
					$req2 = mysql_query($sql2);
					$xtmp = mysql_result($req2,0,x);
					$ytmp = mysql_result($req2,0,y);
					$rayon = sqrt(($monx-$xtmp)*($monx-$xtmp) + ($mony-$ytmp)*($mony-$ytmp));
					if($rayon<$truc['rayon'])
						{
						$truc['rayon'] = $rayon;
						$truc['x'] = $xtmp;
						$truc['y'] = $ytmp;
						$truc['nom'] = mysql_result($req,$i,nom);
						$truc['logo'] = mysql_result($req,$i,logo);
						$truc['num'] = mysql_result($req,$i,num);
						$truc['rue'] = mysql_result($req,$i,rue);
						}
					}
				if($truc['rayon'] != 10000)
					{
					$infos .= 'Agence immobiliaire la plus proche :<br />
					<a href=\'engine=carte.php?x='.$truc['x'].'&y='.$truc['y'].'&zoom='.$zoom.'\'>'.$truc['nom'].'</a> - <a href=\'engine=carte.php?x='.$truc['x'].'&y='.$truc['y'].'&zoom='.$zoom.'&go=ok\'>Go !</a><br />
					<em>Agence immobiliaire</em><br />
					'.$truc['num'].' '.ucfirst($truc['rue']).'<br />Secteur '.XY2secteur($truc['x'],$truc['y']).'<br /><br />';
					}
				}
			// BANQUE
			elseif(strtolower($_POST['chercher_lieu']) == "banque")
				{
				$truc['rayon'] = 10000;
				$sql = 'SELECT nom,logo,num,rue FROM entreprises_tbl WHERE type = "banque" AND ouvert="oui"';
				$req = mysql_query($sql);
				$res = mysql_num_rows($req);
				for($i=0;$i<$res;$i++)
					{
					$sql2 = 'SELECT x,y FROM carte_tbl WHERE num="'.mysql_result($req,$i,num).'" AND idrue=(SELECT id FROM rues_tbl WHERE nom="'.mysql_result($req,$i,rue).'")';
					$req2 = mysql_query($sql2);
					$xtmp = mysql_result($req2,0,x);
					$ytmp = mysql_result($req2,0,y);
					$rayon = sqrt(($monx-$xtmp)*($monx-$xtmp) + ($mony-$ytmp)*($mony-$ytmp));
					if($rayon<$truc['rayon'])
						{
						$truc['rayon'] = $rayon;
						$truc['x'] = $xtmp;
						$truc['y'] = $ytmp;
						$truc['nom'] = mysql_result($req,$i,nom);
						$truc['logo'] = mysql_result($req,$i,logo);
						$truc['num'] = mysql_result($req,$i,num);
						$truc['rue'] = mysql_result($req,$i,rue);
						}
					}
				if($truc['rayon'] != 10000)
					{
					$infos .= 'Banque la plus proche :<br />
					<a href=\'engine=carte.php?x='.$truc['x'].'&y='.$truc['y'].'&zoom='.$zoom.'\'>'.$truc['nom'].'</a> - <a href=\'engine=carte.php?x='.$truc['x'].'&y='.$truc['y'].'&zoom='.$zoom.'&go=ok\'>Go !</a><br />
					<em>Banque</em><br />
					'.$truc['num'].' '.ucfirst($truc['rue']).'<br />Secteur '.XY2secteur($truc['x'],$truc['y']).'<br /><br />';
					}
				}
			// BAR CAFE
			elseif(strtolower($_POST['chercher_lieu']) == "bar" || strtolower($_POST['chercher_lieu']) == "cafe" || strtolower($_POST['chercher_lieu']) == "café" || strtolower($_POST['chercher_lieu']) == "bar cafe" || strtolower($_POST['chercher_lieu']) == "bar café")
				{
				$truc['rayon'] = 10000;
				$sql = 'SELECT nom,logo,num,rue FROM entreprises_tbl WHERE type = "bar cafe" AND ouvert="oui"';
				$req = mysql_query($sql);
				$res = mysql_num_rows($req);
				for($i=0;$i<$res;$i++)
					{
					$sql2 = 'SELECT x,y FROM carte_tbl WHERE num="'.mysql_result($req,$i,num).'" AND idrue=(SELECT id FROM rues_tbl WHERE nom="'.mysql_result($req,$i,rue).'")';
					$req2 = mysql_query($sql2);
					$xtmp = mysql_result($req2,0,x);
					$ytmp = mysql_result($req2,0,y);
					$rayon = sqrt(($monx-$xtmp)*($monx-$xtmp) + ($mony-$ytmp)*($mony-$ytmp));
					if($rayon<$truc['rayon'])
						{
						$truc['rayon'] = $rayon;
						$truc['x'] = $xtmp;
						$truc['y'] = $ytmp;
						$truc['nom'] = mysql_result($req,$i,nom);
						$truc['logo'] = mysql_result($req,$i,logo);
						$truc['num'] = mysql_result($req,$i,num);
						$truc['rue'] = mysql_result($req,$i,rue);
						}
					}
				if($truc['rayon'] != 10000)
					{
					$infos .= 'Bar le plus proche :<br />
					<a href=\'engine=carte.php?x='.$truc['x'].'&y='.$truc['y'].'&zoom='.$zoom.'\'>'.$truc['nom'].'</a> - <a href=\'engine=carte.php?x='.$truc['x'].'&y='.$truc['y'].'&zoom='.$zoom.'&go=ok\'>Go !</a><br />
					<em>Bar café</em><br />
					'.$truc['num'].' '.ucfirst($truc['rue']).'<br />Secteur '.XY2secteur($truc['x'],$truc['y']).'<br /><br />';
					}
				}
			// BOUTIQUE ARMES
			elseif(strtolower($_POST['chercher_lieu']) == "boutique armes" || strtolower($_POST['chercher_lieu']) == "boutique arme" || strtolower($_POST['chercher_lieu']) == "arme" || strtolower($_POST['chercher_lieu']) == "armes")
				{
				$truc['rayon'] = 10000;
				$sql = 'SELECT nom,logo,num,rue FROM entreprises_tbl WHERE type = "boutique armes" AND ouvert="oui"';
				$req = mysql_query($sql);
				$res = mysql_num_rows($req);
				for($i=0;$i<$res;$i++)
					{
					$sql2 = 'SELECT x,y FROM carte_tbl WHERE num="'.mysql_result($req,$i,num).'" AND idrue=(SELECT id FROM rues_tbl WHERE nom="'.mysql_result($req,$i,rue).'")';
					$req2 = mysql_query($sql2);
					$xtmp = mysql_result($req2,0,x);
					$ytmp = mysql_result($req2,0,y);
					$rayon = sqrt(($monx-$xtmp)*($monx-$xtmp) + ($mony-$ytmp)*($mony-$ytmp));
					if($rayon<$truc['rayon'])
						{
						$truc['rayon'] = $rayon;
						$truc['x'] = $xtmp;
						$truc['y'] = $ytmp;
						$truc['nom'] = mysql_result($req,$i,nom);
						$truc['logo'] = mysql_result($req,$i,logo);
						$truc['num'] = mysql_result($req,$i,num);
						$truc['rue'] = mysql_result($req,$i,rue);
						}
					}
				if($truc['rayon'] != 10000)
					{
					$infos .= 'Boutique d\'armes la plus proche :<br />
					<a href=\'engine=carte.php?x='.$truc['x'].'&y='.$truc['y'].'&zoom='.$zoom.'\'>'.$truc['nom'].'</a> - <a href=\'engine=carte.php?x='.$truc['x'].'&y='.$truc['y'].'&zoom='.$zoom.'&go=ok\'>Go !</a><br />
					<em>Boutique d\'armes</em><br />
					'.$truc['num'].' '.ucfirst($truc['rue']).'<br />Secteur '.XY2secteur($truc['x'],$truc['y']).'<br /><br />';
					}
				}
			// BOUTIQUE SPE
			elseif(strtolower($_POST['chercher_lieu']) == "boutique spécialisee" || strtolower($_POST['chercher_lieu']) == "boutique spécialisée" || strtolower($_POST['chercher_lieu']) == "boutique specialisee" || strtolower($_POST['chercher_lieu']) == "boutique spé")
				{
				$truc['rayon'] = 10000;
				$sql = 'SELECT nom,logo,num,rue FROM entreprises_tbl WHERE type = "boutique spécialisee" AND ouvert="oui"';
				$req = mysql_query($sql);
				$res = mysql_num_rows($req);
				for($i=0;$i<$res;$i++)
					{
					$sql2 = 'SELECT x,y FROM carte_tbl WHERE num="'.mysql_result($req,$i,num).'" AND idrue=(SELECT id FROM rues_tbl WHERE nom="'.mysql_result($req,$i,rue).'")';
					$req2 = mysql_query($sql2);
					$xtmp = mysql_result($req2,0,x);
					$ytmp = mysql_result($req2,0,y);
					$rayon = sqrt(($monx-$xtmp)*($monx-$xtmp) + ($mony-$ytmp)*($mony-$ytmp));
					if($rayon<$truc['rayon'])
						{
						$truc['rayon'] = $rayon;
						$truc['x'] = $xtmp;
						$truc['y'] = $ytmp;
						$truc['nom'] = mysql_result($req,$i,nom);
						$truc['logo'] = mysql_result($req,$i,logo);
						$truc['num'] = mysql_result($req,$i,num);
						$truc['rue'] = mysql_result($req,$i,rue);
						}
					}
				if($truc['rayon'] != 10000)
					{
					$infos .= 'Boutique spécialisée la plus proche :<br />
					<a href=\'engine=carte.php?x='.$truc['x'].'&y='.$truc['y'].'&zoom='.$zoom.'\'>'.$truc['nom'].'</a> - <a href=\'engine=carte.php?x='.$truc['x'].'&y='.$truc['y'].'&zoom='.$zoom.'&go=ok\'>Go !</a><br />
					<em>Boutique spécialisée</em><br />
					'.$truc['num'].' '.ucfirst($truc['rue']).'<br />Secteur '.XY2secteur($truc['x'],$truc['y']).'<br /><br />';
					}
				}
			// CENTRE DE RECHERCHE
			elseif(strtolower($_POST['chercher_lieu']) == "centre de recherche")
				{
				$truc['rayon'] = 10000;
				$sql = 'SELECT nom,logo,num,rue FROM entreprises_tbl WHERE type = "centre de recherche" AND ouvert="oui"';
				$req = mysql_query($sql);
				$res = mysql_num_rows($req);
				for($i=0;$i<$res;$i++)
					{
					$sql2 = 'SELECT x,y FROM carte_tbl WHERE num="'.mysql_result($req,$i,num).'" AND idrue=(SELECT id FROM rues_tbl WHERE nom="'.mysql_result($req,$i,rue).'")';
					$req2 = mysql_query($sql2);
					$xtmp = mysql_result($req2,0,x);
					$ytmp = mysql_result($req2,0,y);
					$rayon = sqrt(($monx-$xtmp)*($monx-$xtmp) + ($mony-$ytmp)*($mony-$ytmp));
					if($rayon<$truc['rayon'])
						{
						$truc['rayon'] = $rayon;
						$truc['x'] = $xtmp;
						$truc['y'] = $ytmp;
						$truc['nom'] = mysql_result($req,$i,nom);
						$truc['logo'] = mysql_result($req,$i,logo);
						$truc['num'] = mysql_result($req,$i,num);
						$truc['rue'] = mysql_result($req,$i,rue);
						}
					}
				if($truc['rayon'] != 10000)
					{
					$infos .= 'Centre de recherche le plus proche :<br />
					<a href=\'engine=carte.php?x='.$truc['x'].'&y='.$truc['y'].'&zoom='.$zoom.'\'>'.$truc['nom'].'</a> - <a href=\'engine=carte.php?x='.$truc['x'].'&y='.$truc['y'].'&zoom='.$zoom.'&go=ok\'>Go !</a><br />
					<em>Centre de recherche</em><br />
					'.$truc['num'].' '.ucfirst($truc['rue']).'<br />Secteur '.XY2secteur($truc['x'],$truc['y']).'<br /><br />';
					}
				}
			// HOPITAL
			elseif(strtolower($_POST['chercher_lieu']) == "hôpital" || strtolower($_POST['chercher_lieu']) == "hopital")
				{
				$truc['rayon'] = 10000;
				$sql = 'SELECT nom,logo,num,rue FROM entreprises_tbl WHERE type = "hopital" AND ouvert="oui"';
				$req = mysql_query($sql);
				$res = mysql_num_rows($req);
				for($i=0;$i<$res;$i++)
					{
					$sql2 = 'SELECT x,y FROM carte_tbl WHERE num="'.mysql_result($req,$i,num).'" AND idrue=(SELECT id FROM rues_tbl WHERE nom="'.mysql_result($req,$i,rue).'")';
					$req2 = mysql_query($sql2);
					$xtmp = mysql_result($req2,0,x);
					$ytmp = mysql_result($req2,0,y);
					$rayon = sqrt(($monx-$xtmp)*($monx-$xtmp) + ($mony-$ytmp)*($mony-$ytmp));
					if($rayon<$truc['rayon'])
						{
						$truc['rayon'] = $rayon;
						$truc['x'] = $xtmp;
						$truc['y'] = $ytmp;
						$truc['nom'] = mysql_result($req,$i,nom);
						$truc['logo'] = mysql_result($req,$i,logo);
						$truc['num'] = mysql_result($req,$i,num);
						$truc['rue'] = mysql_result($req,$i,rue);
						}
					}
				if($truc['rayon'] != 10000)
					{
					$infos .= 'Hôpital le plus proche :<br />
					<a href=\'engine=carte.php?x='.$truc['x'].'&y='.$truc['y'].'&zoom='.$zoom.'\'>'.$truc['nom'].'</a> - <a href=\'engine=carte.php?x='.$truc['x'].'&y='.$truc['y'].'&zoom='.$zoom.'&go=ok\'>Go !</a><br />
					<em>Hôpital</em><br />
					'.$truc['num'].' '.ucfirst($truc['rue']).'<br />Secteur '.XY2secteur($truc['x'],$truc['y']).'<br /><br />';
					}
				}
			// USINE
			elseif(strtolower($_POST['chercher_lieu']) == "usine de production" || strtolower($_POST['chercher_lieu']) == "usine" || strtolower($_POST['chercher_lieu']) == "production")
				{
				$truc['rayon'] = 10000;
				$sql = 'SELECT nom,logo,num,rue FROM entreprises_tbl WHERE type = "usine de production" AND ouvert="oui"';
				$req = mysql_query($sql);
				$res = mysql_num_rows($req);
				for($i=0;$i<$res;$i++)
					{
					$sql2 = 'SELECT x,y FROM carte_tbl WHERE num="'.mysql_result($req,$i,num).'" AND idrue=(SELECT id FROM rues_tbl WHERE nom="'.mysql_result($req,$i,rue).'")';
					$req2 = mysql_query($sql2);
					$xtmp = mysql_result($req2,0,x);
					$ytmp = mysql_result($req2,0,y);
					$rayon = sqrt(($monx-$xtmp)*($monx-$xtmp) + ($mony-$ytmp)*($mony-$ytmp));
					if($rayon<$truc['rayon'])
						{
						$truc['rayon'] = $rayon;
						$truc['x'] = $xtmp;
						$truc['y'] = $ytmp;
						$truc['nom'] = mysql_result($req,$i,nom);
						$truc['logo'] = mysql_result($req,$i,logo);
						$truc['num'] = mysql_result($req,$i,num);
						$truc['rue'] = mysql_result($req,$i,rue);
						}
					}
				if($truc['rayon'] != 10000)
					{
					$infos .= 'Usine de production la plus proche :<br />
					<a href=\'engine=carte.php?x='.$truc['x'].'&y='.$truc['y'].'&zoom='.$zoom.'\'>'.$truc['nom'].'</a> - <a href=\'engine=carte.php?x='.$truc['x'].'&y='.$truc['y'].'&zoom='.$zoom.'&go=ok\'>Go !</a><br />
					<em>Usine de production</em><br />
					'.$truc['num'].' '.ucfirst($truc['rue']).'<br />Secteur '.XY2secteur($truc['x'],$truc['y']).'<br /><br />';
					}
				}
			// VENTE AUX ENCHERES
			elseif(strtolower($_POST['chercher_lieu']) == "vente aux encheres" || strtolower($_POST['chercher_lieu']) == "ventes aux encheres" || strtolower($_POST['chercher_lieu']) == "vente aux enchères" || strtolower($_POST['chercher_lieu']) == "ventes aux enchères" || strtolower($_POST['chercher_lieu']) == "enchères" || strtolower($_POST['chercher_lieu']) == "encheres")
				{
				$truc['rayon'] = 10000;
				$sql = 'SELECT nom,logo,num,rue FROM entreprises_tbl WHERE type = "ventes aux encheres" AND ouvert="oui"';
				$req = mysql_query($sql);
				$res = mysql_num_rows($req);
				for($i=0;$i<$res;$i++)
					{
					$sql2 = 'SELECT x,y FROM carte_tbl WHERE num="'.mysql_result($req,$i,num).'" AND idrue=(SELECT id FROM rues_tbl WHERE nom="'.mysql_result($req,$i,rue).'")';
					$req2 = mysql_query($sql2);
					$xtmp = mysql_result($req2,0,x);
					$ytmp = mysql_result($req2,0,y);
					$rayon = sqrt(($monx-$xtmp)*($monx-$xtmp) + ($mony-$ytmp)*($mony-$ytmp));
					if($rayon<$truc['rayon'])
						{
						$truc['rayon'] = $rayon;
						$truc['x'] = $xtmp;
						$truc['y'] = $ytmp;
						$truc['nom'] = mysql_result($req,$i,nom);
						$truc['logo'] = mysql_result($req,$i,logo);
						$truc['num'] = mysql_result($req,$i,num);
						$truc['rue'] = mysql_result($req,$i,rue);
						}
					}
				if($truc['rayon'] != 10000)
					{
					$infos .= 'Ventes aux enchères la plus proche :<br />
					<a href=\'engine=carte.php?x='.$truc['x'].'&y='.$truc['y'].'&zoom='.$zoom.'\'>'.$truc['nom'].'</a> - <a href=\'engine=carte.php?x='.$truc['x'].'&y='.$truc['y'].'&zoom='.$zoom.'&go=ok\'>Go !</a><br />
					<em>Ventes aux enchères</em><br />
					'.$truc['num'].' '.ucfirst($truc['rue']).'<br />Secteur '.XY2secteur($truc['x'],$truc['y']).'<br /><br />';
					}
				}
			// LIEUX PUBLICS
			elseif(strtolower($_POST['chercher_lieu']) == "lieu public" || strtolower($_POST['chercher_lieu']) == "lieux publics" || strtolower($_POST['chercher_lieu']) == "lieux publiques")
				{
				$sql = 'SELECT L.nom, L.logo, L.num, L.rue, C.x, C.y FROM lieux_speciaux_tbl L, carte_tbl C, rues_tbl R WHERE L.num = C.num AND L.rue = R.nom AND R.id = C.idrue';
				$req = mysql_query($sql);
				$res = mysql_num_rows($req);
				
				$infos .= 'Liste de lieux publics :<br />';
				
				for($i=0;$i<$res;$i++)
					{
					$infos .= '<a href=\'engine=carte.php?x='.mysql_result($req,$i,x).'&y='.mysql_result($req,$i,y).'&zoom='.$zoom.'\'>'.mysql_result($req,$i,nom).'</a> - <a href=\'engine=carte.php?x='.mysql_result($req,$i,x).'&y='.mysql_result($req,$i,y).'&zoom='.$zoom.'&go=ok\'>Go !</a><br />
					'.mysql_result($req,$i,num).' '.ucfirst(mysql_result($req,$i,rue)).'<br />Secteur '.XY2secteur(mysql_result($req,$i,x),mysql_result($req,$i,y)).'<br />';
					}
				$infos .= '<br />';
				}
			// OI
			elseif(strtolower($_POST['chercher_lieu']) == "ois" || strtolower($_POST['chercher_lieu']) == "organisations impériales" || strtolower($_POST['chercher_lieu']) == "organisations imperiales" || strtolower($_POST['chercher_lieu']) == "organisation impériale" || strtolower($_POST['chercher_lieu']) == "organisation imperiale")
				{
				$ois = liste_OI();
				$tmp = array();
				foreach ($ois as $oi) {
					if ($oi != 'di2rco')
						$tmp[] = $oi;
				}
				$ois = 'E.nom="' . implode('" OR E.nom="', $tmp) . '"';
				$sql = 'SELECT E.nom, E.logo, E.num, E.rue, C.x, C.y FROM entreprises_tbl E, carte_tbl C, rues_tbl R WHERE E.num = C.num AND E.rue = R.nom AND R.id = C.idrue AND ('.$ois.')';
				$req = mysql_query($sql);
				$res = mysql_num_rows($req);
				
				$infos .= 'Liste des Organisations Impériales :<br />';
				
				for($i=0;$i<$res;$i++)
					{
					$infos .= '<a href=\'engine=carte.php?x='.mysql_result($req,$i,x).'&y='.mysql_result($req,$i,y).'&zoom='.$zoom.'\'>'.mysql_result($req,$i,nom).'</a> - <a href=\'engine=carte.php?x='.mysql_result($req,$i,x).'&y='.mysql_result($req,$i,y).'&zoom='.$zoom.'&go=ok\'>Go !</a><br />
					'.mysql_result($req,$i,num).' '.ucfirst(mysql_result($req,$i,rue)).'<br />Secteur '.XY2secteur(mysql_result($req,$i,x),mysql_result($req,$i,y)).'<br />';
					}
				$infos .= '<br />';
				}
			
			$sql = 'SELECT nom,type,ouvert,logo,num,rue FROM entreprises_tbl WHERE nom LIKE "%'.htmlentities($_POST['chercher_lieu']).'%" ORDER BY nom';
			$req = mysql_query($sql);
			$res = mysql_num_rows($req);
			
			for($i=0;$i<$res;$i++)
				{
				if(mysql_result($req,$i,type) != "di2rco")
					{
					$sql2 = 'SELECT x,y FROM carte_tbl WHERE num="'.mysql_result($req,$i,num).'" AND idrue=(SELECT id FROM rues_tbl WHERE nom="'.mysql_result($req,$i,rue).'")';
					$req2 = mysql_query($sql2);
					
					$num = mysql_result($req,$i,num);
					$rue = mysql_result($req,$i,rue);
					
					$nom = mysql_result($req,$i,nom);
					$type = mysql_result($req,$i,type);
					$type = ($type=='bar cafe')?'Bar café':(
							($type=='boutique armes')?'Boutique d&#146;armes':(
							($type=='boutique spécialisee')?'Boutique spécialisée':(
							($type=='hopital')?'Hôpital':(
							($type=='chambre')?'Organisation Impériale':(
							($type=='CIE')?'Organisation Impériale':(
							($type=='CIPE')?'Organisation Impériale':(
							($type=='conseil')?'Organisation Impériale':(
							($type=='dcn')?'Organisation Impériale':(
							($type=='DOI')?'Organisation Impériale':(
							($type=='jeux')?'Organisation Impériale':(
							($type=='police')?'Organisation Impériale':(
							($type=='prison')?'Organisation Impériale':(
							($type=='proprete')?'Organisation Impériale':ucfirst($type)
					)))))))))))));
					
					$ouvert = mysql_result($req,$i,ouvert);
					$image = mysql_result($req,$i,logo);
					$image = (ereg('ftp://',$image) || ereg('http://',$image))? $image:'im_objets/'.$image;
					
					$infos .= '<a href=\'engine=carte.php?x='.mysql_result($req2,0,x).'&y='.mysql_result($req2,0,y).'&zoom='.$zoom.'\'>'.$nom.'</a>'.(($ouvert != 'non')?' - <a href=\'engine=carte.php?x='.mysql_result($req2,0,x).'&y='.mysql_result($req2,0,y).'&zoom='.$zoom.'&go=ok\'>Go !</a>':'').'<br />
					<em>'.$type.'</em><br />
					'.$num.' '.ucfirst($rue).'<br />
					<span style=&quot;font-size:10px; &quot;>Secteur '.XY2secteur(mysql_result($req2,0,x),mysql_result($req2,0,y)).'</span><br /><br />';
					}
				}
				
			$sql = 'SELECT nom,type,logo,num,rue FROM cerclesliste_tbl WHERE nom LIKE "%'.htmlentities($_POST['chercher_lieu']).'%" AND public = 1 ORDER BY nom';
			$req = mysql_query($sql);
			$res = mysql_num_rows($req);
			
			for($i=0;$i<$res;$i++)
				{
				$sql2 = 'SELECT x,y FROM carte_tbl WHERE num="'.mysql_result($req,$i,num).'" AND idrue=(SELECT id FROM rues_tbl WHERE nom="'.mysql_result($req,$i,rue).'")';
				$req2 = mysql_query($sql2);
				
				$num = mysql_result($req,$i,num);
				$rue = mysql_result($req,$i,rue);
				
				$nom = mysql_result($req,$i,nom);
				$type = 'Cercle '.strtolower(mysql_result($req,$i,type));
				
				$image = mysql_result($req,$i,logo);
				$image = (ereg('ftp://',$image) || ereg('http://',$image))? $image:'im_objets/'.$image;
				
				$infos .= '<a href=\'engine=carte.php?x='.mysql_result($req2,0,x).'&y='.mysql_result($req2,0,y).'&zoom='.$zoom.'\'>'.$nom.'</a> - <a href=\'engine=carte.php?x='.mysql_result($req2,0,x).'&y='.mysql_result($req2,0,y).'&zoom='.$zoom.'&go=ok\'>Go !</a><br />
				<em>'.$type.'</em><br />
				'.$num.' '.ucfirst($rue).'<br />
					<span style=&quot;font-size:10px; &quot;>Secteur '.XY2secteur(mysql_result($req2,0,x),mysql_result($req2,0,y)).'</span><br /><br />';
				}
				
			$sql = 'SELECT nom,logo,num,rue,acces FROM lieux_speciaux_tbl WHERE nom LIKE "%'.htmlentities($_POST['chercher_lieu']).'%" ORDER BY nom';
			$req = mysql_query($sql);
			$res = mysql_num_rows($req);
			
			for($i=0;$i<$res;$i++)
				{
				$sql2 = 'SELECT x,y FROM carte_tbl WHERE num="'.mysql_result($req,$i,num).'" AND idrue=(SELECT id FROM rues_tbl WHERE nom="'.mysql_result($req,$i,rue).'")';
				$req2 = mysql_query($sql2);
				
				$num = mysql_result($req,$i,num);
				$rue = mysql_result($req,$i,rue);
				
				$nom = mysql_result($req,$i,nom);
				$type = 'Lieu public';
				
				$image = mysql_result($req,$i,logo);
				$image = (ereg('ftp://',$image) || ereg('http://',$image))? $image:'im_objets/'.$image;
				
				$infos .= '<a href=\'engine=carte.php?x='.mysql_result($req2,0,x).'&y='.mysql_result($req2,0,y).'&zoom='.$zoom.'\'>'.$nom.'</a>'.((mysql_result($req,$i,acces))?' - <a href=\'engine=carte.php?x='.mysql_result($req2,0,x).'&y='.mysql_result($req2,0,y).'&zoom='.$zoom.'&go=ok\'>Go !</a>':'').'<br />
				<em>'.$type.'</em><br />
				'.$num.' '.ucfirst($rue).'<br />
				<span style=&quot;font-size:10px; &quot;>Secteur '.XY2secteur(mysql_result($req2,0,x),mysql_result($req2,0,y)).'</span><br /><br />';

				}
			}
			if($infos == "") $infos = "Aucun lieu ne correspond à \"".$_POST['chercher_lieu']."\"";
		}
		
	print('<script type="text/javascript"> 
			<!--
			function demandeInfos() {
				return "'.nl2br(str_replace("\t","",str_replace("\n","",str_replace("\"","&quot;",$infos)))).'";
			}
			
			//-->
		</script>');
	
	
	print('<div id="zoomer">
		<a id="plus" href="engine=carte.php?x='.$x.'&y='.$y.'&zoom='.(($zoom<=1)?1:$zoom-1).'"></a>
		<div id="jauge" style="background:url(im_objets/map_jauge.gif) -'.(($zoom-1)*22).'px 0 no-repeat;">
			<a id="jauge1" href="engine=carte.php?x='.$x.'&y='.$y.'&zoom=1" onmouseover="$(\'#jauge\').css(\'background-position\',\'0 0\');" onmouseout="$(\'#jauge\').css(\'background-position\',\'-'.(($zoom-1)*22).'px 0\');" style="position:absolute;left:0;top:26px;"></a>
			<a id="jauge2" href="engine=carte.php?x='.$x.'&y='.$y.'&zoom=2" onmouseover="$(\'#jauge\').css(\'background-position\',\'-22px 0\');" onmouseout="$(\'#jauge\').css(\'background-position\',\'-'.(($zoom-1)*22).'px 0\');" style="position:absolute;left:0;top:56px;"></a>
			<a id="jauge3" href="engine=carte.php?x='.$x.'&y='.$y.'&zoom=3" onmouseover="$(\'#jauge\').css(\'background-position\',\'-44px 0\');" onmouseout="$(\'#jauge\').css(\'background-position\',\'-'.(($zoom-1)*22).'px 0\');" style="position:absolute;left:0;bottom:23px;"></a>
		</div>
		<a id="moins" href="engine=carte.php?x='.$x.'&y='.$y.'&zoom='.(($zoom>=3)?3:$zoom+1).'"></a>
	</div>');
	
	?>
	
	<div id="situation">
		<strong>Situation</strong><br />
		<?php
		if($_SESSION['num']<=0 OR $_SESSION['rue']=="ruelle")
			print(ucfirst(strtolower($_SESSION['rue'])));
		else
			{
			$tmp = $_SESSION['num'].' '.ucfirst(strtolower($_SESSION['rue']));
			$tmp = (strlen($tmp)>21)?substr($tmp,0,21).'.':$tmp;
			print($tmp);
			}
		 ?>
	</div>
	
	<?php
	
	print('<form action="engine=carte.php?x='.$x.'&y='.$y.'&zoom='.$zoom.'" method="post" id="recherche">
		<input type="text" name="chercher_lieu" class="champ_recherche" /><br />
		<input type="submit" name="submit" value="Ok" class="valider" />
	</form>');
	
	?>
	
	<div id="infos1"><?php echo stripslashes($infos); ?></div>
	
	<div id="infos2">
		<?php
		
		if($zoom < 3) print('<a href="engine=carte.php?zoom='.$zoom.'">Afficher ma position</a>');
		//if(est_dans_inventaire("Carnet") OR $_SESSION['statut']=="Compte VIP" OR $_SESSION['statut']=="Administrateur") print('<br /><a href="">Afficher mon carnet</a>');
		
		?>
	</div>
	
	<div id="carte" style="background:none;">
		
	<?php
		// ZOOM MAX ------------------------------------------------------------------------------------------------------------------------
		if($zoom == 1)
			{
			$taille = 30;
			$gauche=$x-5;
			$haut=$y-5;
			
			//CARTE
			$sql = 'SELECT C.x,C.y,C.idrue,C.type,C.num,R.nom FROM carte_tbl C, rues_tbl R WHERE (C.idrue = R.id OR C.idrue = 0) AND
			C.x > '.($x-5).' AND
			C.x < '.($x+6).' AND
			C.y > '.($y-5).' AND
			C.y < '.($y+6);
			$req = mysql_query($sql);
			$res = mysql_num_rows($req);
			$carte = "";
			
			$detect = bonus($_SESSION['pseudo'],"detect");
			
			for($i=0;$i<$res;$i++)
				{
				$idrue = mysql_result($req,$i,idrue);
				$typecase = mysql_result($req,$i,type);
				$num = mysql_result($req,$i,num);
				$rue = mysql_result($req,$i,nom);
				$x1 = mysql_result($req,$i,x);
				$y1 = mysql_result($req,$i,y);
				
				$retour = trouver_lieu($num,$rue);
				$nom = $retour['nom'];
				$type = $retour['type'];
				$ouvert = $retour['ouvert'];
				$image = $retour['image'];
				
				// Si c'est la rue
				if($typecase == 0)
					{
					$sql3 = 'SELECT pseudo FROM principal_tbl WHERE num = '.$num.' AND rue = "'.$rue.'" AND id!='.$_SESSION['id'];
					$req3 = mysql_query($sql3);
					$res3 = mysql_num_rows($req3);
					$rand = rand(0,2);
					$nbperso = 0;
					$persos = "";
					for($k=0;$k<$res3;$k++)
						{
						if(!bonus(mysql_result($req3,$k,pseudo),"invisibilite") || $detect) { $persos .= mysql_result($req3,$k,pseudo).'<br />'; $nbperso++; }
						}
					$nbperso = ($nbperso>3)?2:$nbperso-1;
					$js = 'onmouseout="$(this).css(\'opacity\',\'1\');$(\'#infos1\').html(demandeInfos());" onmouseover="$(this).css(\'opacity\',\'0.7\');$(\'#infos1\').html('.(($persos=="")?"demandeInfos()":"'".$persos."'").');"';
					$bkgd = 'url(im_objets/ptipersos.gif) '.-($rand*30).'px '.-($nbperso*30).'px no-repeat';
					$bkgd2 = 'background:white '.$bkgd.';';
					$carte .= '<a href="engine=carte.php?x='.mysql_result($req,$i,x).'&y='.mysql_result($req,$i,y).'&zoom='.$zoom.'&go=ok" '.$js.' class="mainrue" onmouseover="" onmouseout="" style="z-index:'.(100*$i+100000).';position:absolute;left:'.($taille*(mysql_result($req,$i,x)-1-($x-5))).'px;top:'.($taille*(mysql_result($req,$i,y)-1-($y-5))).'px;width:'.$taille.'px;height:'.$taille.'px;'.$bkgd2.'"></a>';
					}
				// Si c'est rien
				elseif($type == "")
					{
					$bkgd = '#333';
					$carte .= '<div onmouseover="" onmouseout="" style="background:'.$bkgd.';position:absolute;left:'.($taille*(mysql_result($req,$i,x)-1-($x-5))).'px;top:'.($taille*(mysql_result($req,$i,y)-1-($y-5))).'px;width:'.$taille.'px;height:'.$taille.'px;"></div>';;
					}
				else
					{
					$bkgd = '#000';
					if($ouvert != "") $ouvert = ($ouvert == "oui")?'Ouvert':'Fermé';
					$js = 'onmouseout="$(this).css(\'opacity\',\'0.7\');$(\'#infos1\').html(demandeInfos());" onmouseover="$(this).css(\'opacity\',\'1\');$(\'#infos1\').html(\'<strong>'.$nom.'</strong>'.(($ouvert != "")?' - '.$ouvert:'').'<br /><em>'.$type.'</em><br />'.$num.' '.$rue.'<br /><span style=&quot;font-size:10px; &quot;>Secteur '.XY2secteur($x1,$y1).'</span>\');"';

					$carte .= '<a href="engine=carte.php?x='.mysql_result($req,$i,x).'&y='.mysql_result($req,$i,y).'&zoom='.$zoom.'&go=ok" class="rue'.$idrue.'" '.$js.' style="z-index:'.(100*$i+1000).';opacity:0.7;position:absolute;left:'.($taille*(mysql_result($req,$i,x)-1-$gauche)).'px;top:'.($taille*(mysql_result($req,$i,y)-1-$haut)).'px;background:'.$bkgd.';width:'.$taille.'px;height:'.$taille.'px;display:block;"><img src="'.$image.'" style="width:'.$taille.'px;height:'.$taille.'px;"/></a>';
					
					if($monx == mysql_result($req,$i,x) && $mony == mysql_result($req,$i,y)) $pourposition = $js;
					}
				
				if($monx == mysql_result($req,$i,x) && $mony == mysql_result($req,$i,y)) $pos = 1;
				}
			
			//FLECHES
			$fleches = '<a id="flecheno" href="engine=carte.php?x='.($x-2).'&y='.($y-2).'&zoom='.$zoom.'"></a>
			<a id="flechen" href="engine=carte.php?x='.($x).'&y='.($y-2).'&zoom='.$zoom.'"></a>
			<a id="flechene" href="engine=carte.php?x='.($x+2).'&y='.($y-2).'&zoom='.$zoom.'"></a>
			<a id="flecheo" href="engine=carte.php?x='.($x-2).'&y='.($y).'&zoom='.$zoom.'"></a>
			<a id="flechee" href="engine=carte.php?x='.($x+2).'&y='.($y).'&zoom='.$zoom.'"></a>
			<a id="flecheso" href="engine=carte.php?x='.($x-2).'&y='.($y+2).'&zoom='.$zoom.'"></a>
			<a id="fleches" href="engine=carte.php?x='.($x).'&y='.($y+2).'&zoom='.$zoom.'"></a>
			<a id="flechese" href="engine=carte.php?x='.($x+2).'&y='.($y+2).'&zoom='.$zoom.'"></a>';

			//POSITION
			if($pos) $position = '<a href="engine.php" '.$pourposition.' style="z-index:10000000;display:block;position:absolute;left:'.($taille*($monx-$gauche-1)).'px;top:'.($taille*($mony-$haut-1)).'px;width:30px;height:30px;background:url(im_objets/position2.png) 0 0 no-repeat;"></a>';

			print('<div style="position:relative;left:3px;top:2px;">'.$carte.$fleches.$position.'</div>');
			}
		// ZOOM MOYEN ------------------------------------------------------------------------------------------------------------------------
		elseif($zoom == 2)
			{
			$taille=6;
			$gauche=$x-25;
			$haut=$y-25;
			
			//CARTE
			$sql = 'SELECT C.x,C.y,C.idrue,C.type,C.num,R.nom FROM carte_tbl C, rues_tbl R WHERE (C.idrue = R.id OR C.idrue = 0) AND
			C.x > '.($x-25).' AND
			C.x < '.($x+26).' AND
			C.y > '.($y-25).' AND
			C.y < '.($y+26);
			$req = mysql_query($sql);
			$res = mysql_num_rows($req);
			$carte = "";
			for($i=0;$i<$res;$i++)
				{
				$idrue = mysql_result($req,$i,idrue);
				$typecase = mysql_result($req,$i,type);
				$num = mysql_result($req,$i,num);
				$rue = mysql_result($req,$i,nom);
				$x1 = mysql_result($req,$i,x);
				$y1 = mysql_result($req,$i,y);
				$bkgd = "#333";
				
				$js='onmouseout="$(this).css(\'opacity\',\'0.7\');$(\'#infos1\').html(demandeInfos());" onmouseover="$(this).css(\'opacity\',\'1\');$(\'#infos1\').html(\''.$num.' '.$rue.'<br /><span style=&quot;font-size:10px; &quot;>Secteur '.XY2secteur($x1,$y1).'</span><br /><br /><strong>'.$nom.'</strong><br /><em>'.$type.(($ouvert != "")?' ('.$ouvert.')':'').'</em>\');"';
				
				if($typecase == 0) $carte .= '<a class="mainrue" href="engine=carte.php?x='.mysql_result($req,$i,x).'&y='.mysql_result($req,$i,y).'" style="opacity:0.7;position:absolute;left:'.($taille*(mysql_result($req,$i,x)-$gauche-1)).'px;top:'.($taille*(mysql_result($req,$i,y)-$haut-1)).'px;width:'.$taille.'px;height:'.$taille.'px;"></a>';
				else $carte .= '<a href="engine=carte.php?x='.mysql_result($req,$i,x).'&y='.mysql_result($req,$i,y).'" '.$js.' class="rue'.$idrue.'" style="opacity:0.7;position:absolute;left:'.($taille*(mysql_result($req,$i,x)-$gauche-1)).'px;top:'.($taille*(mysql_result($req,$i,y)-$haut-1)).'px;background:'.$bkgd.';width:'.$taille.'px;height:'.$taille.'px;display:block;"></a>';
				
				if($monx == mysql_result($req,$i,x) && $mony == mysql_result($req,$i,y)) $position = 'ok';
				}
			
			//FLECHES
			$fleches = '<a id="flecheno" href="engine=carte.php?x='.($x-10).'&y='.($y-10).'&zoom='.$zoom.'"></a>
			<a id="flechen" href="engine=carte.php?x='.($x).'&y='.($y-10).'&zoom='.$zoom.'"></a>
			<a id="flechene" href="engine=carte.php?x='.($x+10).'&y='.($y-10).'&zoom='.$zoom.'"></a>
			<a id="flecheo" href="engine=carte.php?x='.($x-10).'&y='.($y).'&zoom='.$zoom.'"></a>
			<a id="flechee" href="engine=carte.php?x='.($x+10).'&y='.($y).'&zoom='.$zoom.'"></a>
			<a id="flecheso" href="engine=carte.php?x='.($x-10).'&y='.($y+10).'&zoom='.$zoom.'"></a>
			<a id="fleches" href="engine=carte.php?x='.($x).'&y='.($y+10).'&zoom='.$zoom.'"></a>
			<a id="flechese" href="engine=carte.php?x='.($x+10).'&y='.($y+10).'&zoom='.$zoom.'"></a>';
			
			//POSITION
			if($position == 'ok') $position = '<div style="z-index:10000;position:absolute;left:'.($taille*($monx-$gauche-1)-2).'px;top:'.($taille*($mony-$haut-1)-2).'px;width:10px;height:10px;background:url(im_objets/position.png) 0 0 no-repeat;"></div>';
			
			print('<div style="position:relative;left:3px;top:2px;">'.$carte.$fleches.$position.'</div>');
			}
		// ZOOM MIN ------------------------------------------------------------------------------------------------------------------------
		elseif($zoom == 3)
			{
			$taille=2;
			$gauche=0;
			$haut=0;
			
			$secteurssurf[1]=0;$secteurssurf2[1]=0;
			$secteurssurf[2]=0;$secteurssurf2[2]=0;
			$secteurssurf[3]=0;$secteurssurf2[3]=0;
			$secteurssurf[4]=0;$secteurssurf2[4]=0;
			$secteurssurf[5]=0;$secteurssurf2[5]=0;
			$secteurssurf[6]=0;$secteurssurf2[6]=0;
			$secteurssurf[7]=0;$secteurssurf2[7]=0;
			$secteurssurf[8]=0;$secteurssurf2[8]=0;
			$secteurssurf[9]=0;$secteurssurf2[9]=0;
			
			//POSITION
			$position = '<div style="z-index:10000;position:absolute;left:'.($taille*($monx-1)-3).'px;top:'.($taille*($mony-1)-2).'px;width:10px;height:10px;background:url(im_objets/position.png) 0 0 no-repeat;"></div>';
			if($rechx && $rechy) $recherche = '<div style="z-index:10001;position:absolute;left:'.($taille*($rechx-1)-3).'px;top:'.($taille*($rechy-1)-2).'px;width:10px;height:10px;background:url(im_objets/position3.png) 0 0 no-repeat;"></div>';
			
			//CARTE
			if(isset($_SESSION['carte_dc']))
				{
				/*$carte = $_COOKIE['carte_dc'];
				$carte = str_replace('class="rue'.$monidrue.'" style="background:#666;','class="rue'.$monidrue.'" style="background:#333;',$carte);
				$secteurssurf[1] = $_COOKIE['secteurssurf1'];
				$secteurssurf[2] = $_COOKIE['secteurssurf2'];
				$secteurssurf[3] = $_COOKIE['secteurssurf3'];
				$secteurssurf[4] = $_COOKIE['secteurssurf4'];
				$secteurssurf[5] = $_COOKIE['secteurssurf5'];
				$secteurssurf[6] = $_COOKIE['secteurssurf6'];
				$secteurssurf[7] = $_COOKIE['secteurssurf7'];
				$secteurssurf[8] = $_COOKIE['secteurssurf8'];
				$secteurssurf[9] = $_COOKIE['secteurssurf9'];*/
				$carte = $_SESSION['carte_dc'];
				$carte = str_replace('class="rue'.$monidrue.'" style="background:#666;','class="rue'.$monidrue.'" style="background:#333;',$carte);
				$secteurssurf[1] = $_SESSION['secteurssurf1'];
				$secteurssurf[2] = $_SESSION['secteurssurf2'];
				$secteurssurf[3] = $_SESSION['secteurssurf3'];
				$secteurssurf[4] = $_SESSION['secteurssurf4'];
				$secteurssurf[5] = $_SESSION['secteurssurf5'];
				$secteurssurf[6] = $_SESSION['secteurssurf6'];
				$secteurssurf[7] = $_SESSION['secteurssurf7'];
				$secteurssurf[8] = $_SESSION['secteurssurf8'];
				$secteurssurf[9] = $_SESSION['secteurssurf9'];
				}
			else{
				$sql = 'SELECT x,y,idrue,type FROM carte_tbl' ;
				$req = mysql_query($sql);
				$res = mysql_num_rows($req);
				$carte = "";
				for($i=0;$i<$res;$i++)
					{
					$x = mysql_result($req,$i,x);
					$y = mysql_result($req,$i,y);
					
					$col = (mysql_result($req,$i,type) == 0)?'white':(
						   (mysql_result($req,$i,idrue) == $monidrue)?"#333":"#666"
					);
					if(mysql_result($req,$i,type) != 0)
						{
						if($x >= 1 && $x <= 50 && $y >= 1 && $y <= 50) $secteurssurf[1]++;
						elseif($x >= 51 && $x <= 100 && $y >= 1 && $y <= 50) $secteurssurf[2]++;
						elseif($x >= 101 && $x <= 150 && $y >= 1 && $y <= 50) $secteurssurf[3]++;
						elseif($x >= 1 && $x <= 50 && $y >= 51 && $y <= 100) $secteurssurf[4]++;
						elseif($x >= 51 && $x <= 100 && $y >= 51 && $y <= 100) $secteurssurf[5]++;
						elseif($x >= 101 && $x <= 150 && $y >= 51 && $y <= 100) $secteurssurf[6]++;
						elseif($x >= 1 && $x <= 50 && $y >= 101 && $y <= 150) $secteurssurf[7]++;
						elseif($x >= 51 && $x <= 100 && $y >= 101 && $y <= 150) $secteurssurf[8]++;
						elseif($x >= 101 && $x <= 150 && $y >= 101 && $y <= 150) $secteurssurf[9]++;
						}
					
					$carte .= '<div class="rue'.mysql_result($req,$i,idrue).'" style="background:'.$col.';position:absolute;left:'.($taille*(mysql_result($req,$i,x)-1)).'px;top:'.($taille*(mysql_result($req,$i,y)-1)).'px;background:'.$col.';width:'.$taille.'px;height:'.$taille.'px;"></div>';
					}
				$_SESSION['carte_dc'] = str_replace('style="background:#333;','style="background:#666;',$carte);
				$_SESSION['secteurssurf1'] = $secteurssurf[1];
				$_SESSION['secteurssurf2'] = $secteurssurf[2];
				$_SESSION['secteurssurf3'] = $secteurssurf[3];
				$_SESSION['secteurssurf4'] = $secteurssurf[4];
				$_SESSION['secteurssurf5'] = $secteurssurf[5];
				$_SESSION['secteurssurf6'] = $secteurssurf[6];
				$_SESSION['secteurssurf7'] = $secteurssurf[7];
				$_SESSION['secteurssurf8'] = $secteurssurf[8];
				$_SESSION['secteurssurf9'] = $secteurssurf[9];
				}
				
			//SECTEURS
			$secteurs = "";
			for($i=0;$i<3;$i++){
				for($j=0;$j<3;$j++){
					$secteur = 1+$i+$j*3;
					$description = ($secteur == 1)?'Secteur Nord-Ouest':(
								   ($secteur == 2)?'Secteur Nord':(
								   ($secteur == 3)?'Secteur Nord-Est':(
								   ($secteur == 4)?'Secteur Ouest':(
								   ($secteur == 5)?'Secteur Central':(
								   ($secteur == 6)?'Secteur Est':(
								   ($secteur == 7)?'Secteur Sud-Ouest':(
								   ($secteur == 8)?'Secteur Sud':
								   'Secteur Sud-Est'
					)))))));
					$secteurs .= '<a href="engine=carte.php?x='.((($i+1)*50)/2+$i*25).'&y='.((($j+1)*50)/2+$j*25).'&zoom=2" class="secteur" onload="$(this).css(\'opacity\',\'0\');" onmouseover="$(this).css(\'opacity\',\'0.5\');$(\'#infos1\').html(\'<strong>Secteur '.$secteur.'</strong><br /><em>'.$description.'</em><br /><br />Surface exploitable :<br />'.$secteurssurf[($secteur)].' blocs\');" onmouseout="$(this).css(\'opacity\',\'0\');$(\'#infos1\').html(demandeInfos());" style="z-index:'.(100*$i+100000).';position:absolute;left:'.(100*$i).'px;top:'.(100*$j).'px;">'.$secteur.'</a>';
				}
			}
			
			print('<div style="position:relative;left:3px;top:2px;">'.$secteurs.$carte.$position.$recherche.'</div>');
			
			}
		
		?>
		
	</div>
<?php 

function trouver_lieu($num,$rue){

	$retour;
	$sql2 = 'SELECT nom FROM lieu_tbl WHERE num="'.$num.'" AND rue="'.$rue.'"';
	$req2 = mysql_query($sql2);
	
	$res2 = mysql_num_rows($req2);
	if($res2)
		{
		if(ereg('Local',mysql_result($req2,0,nom)))
			{
			$sql2 = 'SELECT nom,type,ouvert,logo FROM entreprises_tbl WHERE num="'.$num.'" AND rue="'.$rue.'"';
			$req2 = mysql_query($sql2);
			$res2 = mysql_num_rows($req2);
			if($res2)
				{
				if(mysql_result($req2,0,type) != "di2rco")
					{
					$retour['nom'] = mysql_result($req2,0,nom);
					$retour['type'] = mysql_result($req2,0,type);
					$retour['type'] = ($retour['type']=='bar cafe')?'Bar café':(
							($retour['type']=='boutique armes')?'Boutique d&#146;armes':(
							($retour['type']=='boutique spécialisee')?'Boutique spécialisée':(
							($retour['type']=='hopital')?'Hôpital':(
							($retour['type']=='ventes aux encheres')?'Ventes aux enchères':(
							($retour['type']=='chambre')?'Organisation Impériale':(
							($retour['type']=='CIE')?'Organisation Impériale':(
							($retour['type']=='CIPE')?'Organisation Impériale':(
							($retour['type']=='conseil')?'Organisation Impériale':(
							($retour['type']=='dcn')?'Organisation Impériale':(
							($retour['type']=='DOI')?'Organisation Impériale':(
							($retour['type']=='jeux')?'Organisation Impériale':(
							($retour['type']=='police')?'Organisation Impériale':(
							($retour['type']=='prison')?'Organisation Impériale':(
							($retour['type']=='proprete')?'Organisation Impériale':ucfirst($retour['type'])
					))))))))))))));
					
					$retour['ouvert'] = mysql_result($req2,0,ouvert);
					$retour['image'] = mysql_result($req2,0,logo);
					$retour['image'] = (ereg('ftp://',$retour['image']) || ereg('http://',$retour['image']))? $retour['image']:'im_objets/'.$retour['image'];
					}
				}
			else
				{
				$sql2 = 'SELECT nom,type,logo FROM cerclesliste_tbl WHERE num="'.$num.'" AND rue="'.$rue.'" AND public = 1';
				$req2 = mysql_query($sql2);
				$res2 = mysql_num_rows($req2);
				if($res2)
					{
					$retour['nom'] = mysql_result($req2,0,nom);
					$retour['type'] = 'Cercle '.strtolower(mysql_result($req2,0,type));
					$retour['image'] = mysql_result($req2,0,logo);
					$retour['image'] = (ereg('ftp://',$retour['image']) || ereg('http://',$retour['image']))? $retour['image']:'im_objets/'.$retour['image'];
					}
				}
			}
		elseif(mysql_result($req2,0,nom) == 'special')
			{
			$sql2 = 'SELECT nom,logo,type FROM lieux_speciaux_tbl WHERE num= "'.$num.'" AND rue= "'.$rue.'"' ;
			$req2 = mysql_query($sql2);
			$res2 = mysql_num_rows($req2);
			if($res2)
				{
				$retour['nom'] = mysql_result($req2,0,nom);
				$retour['type'] = 'Lieu public';
				$retour['image'] = mysql_result($req2,0,logo);
				}
			}
		else
			{
			$sql2 = 'SELECT nom,type,image FROM objets_tbl WHERE nom="'.mysql_result($req2,0,nom).'"';
			$req2 = mysql_query($sql2);
			$res2 = mysql_num_rows($req2);
			if($res2)
				{
				$retour['nom'] = mysql_result($req2,0,nom).((mysql_result($req2,0,type) == 'vd')?'':'²');
				$retour['type'] = mysql_result($req2,0,type);
				$retour['type'] = ($retour['type']=="pad")?'Petit appartement':(
						($retour['type']=="gad")?'Grand appartement':(
						($retour['type']=="pmd")?'Petite maison':(
						($retour['type']=="gmd")?'Grande maison':'Logement luxueux'
				)));
				$retour['image'] = 'im_objets/'.mysql_result($req2,0,image);
				}
			}
		}
					
		return $retour;
	}

?>

</div>
<?php if($chat=="oui" || $_SESSION['num']<0) 	{ if ((date("H")>=21) || (date("H")<=7)) { include("inc_bas_n_c.php"); } else { include("inc_bas_j_c.php"); } } else {	if ((date("H")>=21) || (date("H")<=7)) { include("inc_bas_n.php"); } else { include("inc_bas_j.php"); }	} ?>
<?php include("inc_bas_de_page.php"); ?>
