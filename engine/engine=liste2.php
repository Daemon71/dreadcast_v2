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

$db = mysql_connect('localhost', 'CENSURE', 'CENSURE') or die("Erreur de connexion au serveur.");
mysql_select_db('CENSURE',$db) or die("Erreur de connexion a la base de donnees.");

$sql1 = 'SELECT num,rue FROM principal_tbl WHERE id= '.$_SESSION['id'] ;
$req1 = mysql_query($sql1);

$_SESSION['rue'] = mysql_result($req1,0,rue);
$_SESSION['num'] = mysql_result($req1,0,num);
/*
if($_SESSION['rue']=="Rue"||$_SESSION['rue']=="Ruelle"||$_SESSION['num']<0) $typetemp="banque";
else
	{
	$sql1 = 'SELECT nom FROM entreprises_tbl WHERE rue= "'.$_SESSION['rue'].'" AND num= "'.$_SESSION['num'].'"' ;
	$req1 = mysql_query($sql1);
	$resentreprise = mysql_num_rows($req1);
	$sql = 'SELECT * FROM cerclesliste_tbl WHERE rue= "'.$_SESSION['rue'].'" AND num= "'.$_SESSION['num'].'"' ;
	$req = mysql_query($sql);
	$res = mysql_num_rows($req);
	}
*/		
mysql_close($db);
			
?>
<?php if ((date("H")>=21) || (date("H")<=7)) { include("inc_haut_n.php"); } else { include("inc_haut_j.php");} ?>

<div id="haut">
	<?php include("inc_entete.php"); ?>
	<div class="haut_aligngauche">
		<p>
		<div class="titrepage">
			Personnes visibles
		</div>
		<b class="module4ie"><a href="engine.php" class="module4"><img src="im_objets/icon_retour.gif" alt="Retour" />Retour</a></b>
</p>
	</div>
</div>

<?php

$_SESSION['distance'] = "";
$_SESSION['personnes'] = "";

$db = mysql_connect('localhost', 'CENSURE', 'CENSURE') or die("Erreur de connexion au serveur.");
mysql_select_db('CENSURE',$db) or die("Erreur de connexion a la base de donnees.");

/*$sql2 = 'SELECT arme,vetements,objet FROM principal_tbl WHERE id= "'.$_SESSION['id'].'"' ;
$req2 = mysql_query($sql2);
$vetementso = mysql_result($req2,0,vetements);
$armeo = mysql_result($req2,0,arme);
$objeto = mysql_result($req2,0,objet);
$sql2 = 'SELECT id FROM objets_tbl WHERE nom= "'.$vetementso.'" OR nom= "'.substr($armeo,0,strpos($armeo,"-")).'" OR nom= "'.$objeto.'"' ;
$req2 = mysql_query($sql2);
$res2 = mysql_num_rows($req2);
$detect = 0;
for($p=0;$p!=$res2;$p++)
	{
	$sql3 = 'SELECT id FROM recherche_effets_tbl WHERE ido= "'.mysql_result($req2,$p,id).'" AND nature= "detect"' ;
	$req3 = mysql_query($sql3);
	if(mysql_num_rows($req3)>0) { $detect = 1; break; }
	}*/

$detect = bonus($_SESSION['pseudo'],"detect");

$sql = 'SELECT code FROM lieu_tbl WHERE num= "'.$_SESSION['num'].'" AND rue= "'.$_SESSION['rue'].'"' ;
$req = mysql_query($sql);
$res = mysql_num_rows($req);

if($res>0) $_SESSION['digicode'] = mysql_result($req,0,code);

if($_SESSION['action']=="travail" || $_SESSION['action']=="repos") $_SESSION['code'] = $_SESSION['digicode'];

if($_SESSION['rue']=="Ruelle")
	{
	print('<div id="centre_rue">

	<p id="location2" style="color:#111;"><br />Il fait trop sombre, vous ne voyez personne.</p>');
	}
elseif($_SESSION['num']>0)
	{
	$sql = 'SELECT type FROM entreprises_tbl WHERE rue= "'.$_SESSION['rue'].'" AND num= "'.$_SESSION['num'].'"' ;
	$req = mysql_query($sql);
	$res = mysql_num_rows($req);
	if($res!=0)
		{
		if($_SESSION['code']!=$_SESSION['digicode'])
			{
			print('<meta http-equiv="refresh" content="0 ; url=http://v2.dreadcast.net/ingame/engine.php"> ');
			}
		else
			{
			$typent=mysql_result($req,0,type);
			if($typent=="di2rco"||$typent=="CIE"||$typent=="CIPE"||$typent=="chambre"||$typent=="DOI"||$typent=="police"||$typent=="prison"||$typent=="jeux"||$typent=="proprete"||$typent=="conseil"||$typent=="dcn")$typetemp="imperium";
			elseif($typent=="agence immobiliaire") $typetemp="agence";
			elseif($typent=="banque") $typetemp="banque";
			elseif($typent=="bar cafe") $typetemp="bar";
			elseif($typent=="banque") $typetemp="banque";
			elseif($typent=="boutique armes") $typetemp="armurerie";
			elseif($typent=="boutique sp&eacute;cialisee") $typetemp="bazar";
			elseif($typent=="hopital") $typetemp="hopital";
			elseif($typent=="usine de production") $typetemp="usine";
			elseif($typent=="ventes aux encheres") $typetemp="encheres";
			elseif($typent=="autre") $typetemp="autre";
			elseif($typent=="centre de recherche") $typetemp="centre";
			
			$sql1 = 'SELECT id,pseudo,action,actif,race FROM principal_tbl WHERE rue= "'.$_SESSION['rue'].'" AND Num= "'.$_SESSION['num'].'" AND action!="mort" AND actif="oui" AND statut!="Debutant" AND pseudo!= "'.$_SESSION['pseudo'].'" ORDER BY RAND() LIMIT '.rand(15,35).'' ;
			$req1 = mysql_query($sql1);
			$res1 = mysql_num_rows($req1);
			if($res1==0)
				{
				print('<div id="centre_'.$typetemp.'">
				
				<p id="location">Liste des personnes autour de vous.</p>

				<p id="textelse"><br />Il semblerait qu\'il n\'y ait personne.</p>');
				}
			else
			{
			print('<div id="centre_'.$typetemp.'">
				
				<p id="location">Liste des personnes autour de vous.</p>
				
				<p id="textelse"><table id="tabview" align="center" border="0" width="90%"><tr><td>');
			$t = 0;
			for($l=0; $l != $res1; $l++)
				{
				$ido = mysql_result($req1,$l,id);
				$pseudoo = mysql_result($req1,$l,pseudo);
				if(est_mort($pseudoo)) continue;
				$_SESSION['personnes'] .= $pseudoo.'-';
				$raceo = mysql_result($req1,$l,race);
				$actiono = mysql_result($req1,$l,action);
				$actif = mysql_result($req1,$l,actif);
				$sql2 = 'SELECT arme,vetements,objet FROM principal_tbl WHERE id= "'.$ido.'"' ;
				$req2 = mysql_query($sql2);
				$vetementso = mysql_result($req2,0,vetements);
				$armeo = mysql_result($req2,0,arme);
				$objeto = mysql_result($req2,0,objet);
				$sql2 = 'SELECT id FROM objets_tbl WHERE nom= "'.$vetementso.'" OR nom= "'.substr($armeo,0,strpos($armeo,"-")).'" OR nom= "'.$objeto.'"' ;
				$req2 = mysql_query($sql2);
				$res2 = mysql_num_rows($req2);
				$vet = 0;
				for($p=0;$p!=$res2;$p++)
					{
					$sql3 = 'SELECT id FROM recherche_effets_tbl WHERE ido= "'.mysql_result($req2,$p,id).'" AND nature= "invisibilite"' ;
					$req3 = mysql_query($sql3);
					if(mysql_num_rows($req3)>0) { $vet = 1; }
					}
				if(($vet!=1) || ($detect==1))
					{
					$t = $t + 1;
					if((($t==ceil($res1/3)+1) || ($t==ceil(($res1*2)/3)+1)) && ($res1>10))
						  {
						  print('</td><td>');
						  }
					$p = 0;
					$sql = 'SELECT connec FROM principal_tbl WHERE id= "'.$ido.'"' ;
					$req = mysql_query($sql);
					$connec = mysql_result($req,0,connec);
					$sql = 'SELECT combat,observation,gestion,maintenance,mecanique,service,discretion,economie,resistance,tir,vol,medecine,informatique,recherche FROM principal_tbl WHERE id= "'.$ido.'"' ;
					$req = mysql_query($sql);
					
					$statc['combat'] = mysql_result($req,0,combat);
					$statc['observation'] = mysql_result($req,0,observation);
					$statc['gestion'] = mysql_result($req,0,gestion);
					$statc['maintenance'] = mysql_result($req,0,maintenance);
					$statc['mecanique'] = mysql_result($req,0,mecanique);
					$statc['service'] = mysql_result($req,0,service);
					$statc['discretion'] = mysql_result($req,0,discretion);
					$statc['economie'] = mysql_result($req,0,economie);
					$statc['resistance'] = mysql_result($req,0,resistance);
					$statc['tir'] = mysql_result($req,0,tir);
					$statc['vol'] = mysql_result($req,0,vol);
					$statc['medecine'] = mysql_result($req,0,medecine);
					$statc['recherche'] = mysql_result($req,0,recherche);
					$statc['informatique'] = mysql_result($req,0,informatique);
					
					arsort($statc);
					
					foreach($statc as $stat => $valeur) {
						if($p<5) $p += affiche_etoile($stat,$valeur);
					}
					
					if($connec=="oui") print(' <img src="im_objets/peclair1.gif" border="0" title="Connect&eacute;">');

					print(' <img src="im_objets/avatar_'.ucwords($raceo).'.gif" border="0" title="'.ucwords($raceo).'">');
					print(' <a href="engine=cible.php?'.$pseudoo.'">'.$pseudoo.'</a><br>');
					}
				}
			print('</td></tr></table></p>');
			}
			}
		}
	else
		{
		if($_SESSION['code']!=$_SESSION['digicode'])
			{
			print('<meta http-equiv="refresh" content="0 ; url=http://v2.dreadcast.net/ingame/engine.php"> ');
			}
		else
			{
			$sql1 = 'SELECT id,pseudo,action,actif,race FROM principal_tbl WHERE rue= "'.$_SESSION['rue'].'" AND Num= "'.$_SESSION['num'].'" AND action!= "mort" AND actif= "oui" AND pseudo!= "'.$_SESSION['pseudo'].'" ORDER BY RAND() LIMIT '.rand(15,35).'' ;
			$req1 = mysql_query($sql1);
			$res1 = mysql_num_rows($req1);
			if($res1==0)
				{
				print('<div id="centre_logement">
				
				<p id="location">Liste des personnes autour de vous.</p>
				
				<p id="textelse" style="color:#111;">Il semblerait qu\'il n\'y ait personne.</p>.');
				}
			else
			  {
				print('<div id="centre_logement">
				
				<!--<p id="location">Liste des personnes autour de vous.</p>-->
				
				<p id="textelse" style="color:#111;">Liste des personnes autour de vous.<table style="text-align:center;" border="0" width="90%"><tr><td>');
				$t = 0;
				for($l=0; $l != $res1; $l++)
				{
				$ido = mysql_result($req1,$l,id);
				$pseudoo = mysql_result($req1,$l,pseudo);
				$_SESSION['personnes'] .= $pseudoo.'-';
				$raceo = mysql_result($req1,$l,race);
				$actiono = mysql_result($req1,$l,action);
				$actif = mysql_result($req1,$l,actif);
				
				$detect = bonus($_SESSION['pseudo'],"detect");
				$inv = bonus($pseudoo,"invisibilite");
				
				if(!$inv || $detect)
					{
					$t = $t + 1;
					if((($t==ceil($res1/3)+1) || ($t==ceil(($res1*2)/3)+1)) && ($res1>10))
						  {
						  print('</td><td>');
						  }
					$p = 0;
					$sql = 'SELECT connec FROM principal_tbl WHERE id= "'.$ido.'"' ;
					$req = mysql_query($sql);
					$connec = mysql_result($req,0,connec);
					$sql = 'SELECT combat,observation,gestion,maintenance,mecanique,service,discretion,economie,resistance,tir,vol,medecine,informatique,recherche FROM principal_tbl WHERE id= "'.$ido.'"' ;
					$req = mysql_query($sql);
					
					$statc['combat'] = mysql_result($req,0,combat);
					$statc['observation'] = mysql_result($req,0,observation);
					$statc['gestion'] = mysql_result($req,0,gestion);
					$statc['maintenance'] = mysql_result($req,0,maintenance);
					$statc['mecanique'] = mysql_result($req,0,mecanique);
					$statc['service'] = mysql_result($req,0,service);
					$statc['discretion'] = mysql_result($req,0,discretion);
					$statc['economie'] = mysql_result($req,0,economie);
					$statc['resistance'] = mysql_result($req,0,resistance);
					$statc['tir'] = mysql_result($req,0,tir);
					$statc['vol'] = mysql_result($req,0,vol);
					$statc['medecine'] = mysql_result($req,0,medecine);
					$statc['recherche'] = mysql_result($req,0,recherche);
					$statc['informatique'] = mysql_result($req,0,informatique);
					
					arsort($statc);
					
					foreach($statc as $stat => $valeur) {
						if($p<5) $p += affiche_etoile($stat,$valeur);
					}
					
					if($connec=="oui") print(' <img src="im_objets/eclair1.gif" border="0" title="Connect&eacute;">');
					
					print(' <img src="im_objets/avatar_'.ucwords($raceo).'.gif" border="0" title="'.ucwords($raceo).'">');
					print(' <a href="engine=cible.php?'.$pseudoo.'">'.$pseudoo.'</a><br>');
					}
				}
			print('</td></tr></table></p>');	
			}
			}
		}
	}
else
	{
	//$sql1 = 'SELECT id,pseudo,action,actif,race FROM principal_tbl WHERE rue= "Rue" AND (num>'.($_SESSION['num']-50).' AND num<'.($_SESSION['num']+50).' AND num <= 0) AND action!= "mort" AND statut!="Debutant" AND actif= "oui" AND pseudo!= "'.$_SESSION['pseudo'].'" ' ;
	$req1 = mysql_query($sql1);
	$req1 = reqListeRue($_SESSION['num'],$_SESSION['rue'],25);
	$res1 = mysql_num_rows($req1);
	$tmp = rand(15,35);
	$res1 = ($res1 > $tmp)?$tmp:$res1;
	if($res1==0)
		{
		print('<div id="centre_rue">
				
				<p id="location" style="color:#111;">Liste des personnes autour de vous.<br />
				<strong>Il semblerait qu\'il n\'y ait personne.</strong></p>.');
		}
	else
	{
	print('<div id="centre_rue">
				
				<p id="location2">Vous jetez un oeil dans la rue.<br />Une foule de personnes s\'affaire autour de vous, circulant sans vous porter attention.</p>
				
				<table id="tabview" border="0"><tr><td>');
	$t = 0;
	for($l=0; $l != $res1; $l++)
		{
		$ido = mysql_result($req1,$l,id);
		$pseudoo = mysql_result($req1,$l,pseudo);
		$_SESSION['personnes'] .= $pseudoo.'-';
		$raceo = mysql_result($req1,$l,race);
		$actiono = mysql_result($req1,$l,action);
		$actif = mysql_result($req1,$l,actif);
		/*
		$sql2 = 'SELECT arme,vetements,objet FROM principal_tbl WHERE id= "'.$ido.'"' ;
		$req2 = mysql_query($sql2);
		$vetementso = mysql_result($req2,0,vetements);
		$armeo = mysql_result($req2,0,arme);
		$objeto = mysql_result($req2,0,objet);
		$sql2 = 'SELECT id FROM objets_tbl WHERE nom= "'.$vetementso.'" OR nom= "'.substr($armeo,0,strpos($armeo,"-")).'" OR nom= "'.$objeto.'"' ;
		$req2 = mysql_query($sql2);
		$res2 = mysql_num_rows($req2);
		$vet = 0;
		for($p=0;$p!=$res2;$p++)
			{
			$sql3 = 'SELECT id FROM recherche_effets_tbl WHERE ido= "'.mysql_result($req2,$p,id).'" AND nature= "invisibilite"' ;
			$req3 = mysql_query($sql3);
			if(mysql_num_rows($req3)>0) { $vet = 1; }
			}
		*/
		if(estVisible($pseudoo,25))
			{
			$t = $t + 1;
			if((($t==ceil($res1/3)+1) || ($t==ceil(($res1*2)/3)+1)) && ($res1>10))
				{
				print('</td><td>');
				}
			$p = 0;
			$sql = 'SELECT connec FROM principal_tbl WHERE id= "'.$ido.'"' ;
			$req = mysql_query($sql);
			$connec = mysql_result($req,0,connec);
			$sql = 'SELECT combat,observation,gestion,maintenance,mecanique,service,discretion,economie,resistance,tir,vol,medecine,informatique,recherche FROM principal_tbl WHERE id= "'.$ido.'"' ;
			$req = mysql_query($sql);
			
			$statc['combat'] = mysql_result($req,0,combat);
					$statc['observation'] = mysql_result($req,0,observation);
					$statc['gestion'] = mysql_result($req,0,gestion);
					$statc['maintenance'] = mysql_result($req,0,maintenance);
					$statc['mecanique'] = mysql_result($req,0,mecanique);
					$statc['service'] = mysql_result($req,0,service);
					$statc['discretion'] = mysql_result($req,0,discretion);
					$statc['economie'] = mysql_result($req,0,economie);
					$statc['resistance'] = mysql_result($req,0,resistance);
					$statc['tir'] = mysql_result($req,0,tir);
					$statc['vol'] = mysql_result($req,0,vol);
					$statc['medecine'] = mysql_result($req,0,medecine);
					$statc['recherche'] = mysql_result($req,0,recherche);
					$statc['informatique'] = mysql_result($req,0,informatique);
					
					arsort($statc);
					
					foreach($statc as $stat => $valeur) {
						if($p<5) $p += affiche_etoile($stat,$valeur);
					}
					
			if($connec=="oui") print(' <img src="im_objets/eclair1.gif" border="0" title="Connect&eacute;">');
			
			print(' <img src="im_objets/avatar_'.ucwords($raceo).'.gif" border="0" title="'.ucwords($raceo).'">');
			print(' <a href="engine=cible.php?'.$pseudoo.'">'.$pseudoo.'</a><br>');
			}
		}
	print('</td></tr></table>');	
	}
	}
mysql_close($db);

function affiche_etoile($stat,$valeur,$tout = false) {
	if($valeur == 0) return 0;
	if($valeur < 40) {
		if($tout) print('<img src="im_objets/etoile1.png" title="Novice'.((statut($_SESSION["statut"]) > 7)?" ($valeur en $stat)":"").'" />');
		return 0;
	}
	if($valeur < 100) {
		print('<img src="im_objets/etoile2.png" title="Initi&eacute;'.((statut($_SESSION["statut"]) > 7)?" ($valeur en $stat)":"").'" />');
		return 1;
	}
	if($valeur == 100) {
		print('<img src="im_objets/etoile3.png" title="Expert'.((statut($_SESSION["statut"]) > 7)?" ($valeur en $stat)":"").'" />');
		return 1;
	}
	if($valeur > 100) {
		print('<img src="im_objets/etoile4.png" title="Sp&eacute;cialiste'.((statut($_SESSION["statut"]) > 7)?" ($valeur en $stat)":"").'" />');
		return 1;
	}
}

?>


</p>
</div>
<?php if($chat=="oui") 	{ if ((date("H")>=21) || (date("H")<=7)) { include("inc_bas_n_c.php"); } else { include("inc_bas_j_c.php"); } } else {	if ((date("H")>=21) || (date("H")<=7)) { include("inc_bas_n.php"); } else { include("inc_bas_j.php"); }	} ?>
<?php include("inc_bas_de_page.php"); ?>
