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

$db = mysql_connect('localhost', 'CENSURE', 'CENSURE') or die("Erreur de connexion au serveur des messages.");
mysql_select_db('CENSURE',$db) or die("Erreur de connexion a la base de donnees des messages.");

$sql = 'SELECT action FROM principal_tbl WHERE id= "'.$_SESSION['id'].'"' ;
$req = mysql_query($sql);
$_SESSION['action'] = mysql_result($req,0,action); 

if($_SESSION['action']=="mort")
	{
	print('<meta http-equiv="refresh" content="0 ; url=http://v2.dreadcast.net/ingame/engine=mort.php"> ');
	exit();
	}

mysql_close($db);

?>
<?php if ((date("H")>=21) || (date("H")<=7)) { include("inc_haut_n.php"); } else { include("inc_haut_j.php");} ?>

<div id="haut">
	<?php include("inc_entete.php"); ?>
	<div class="haut_aligngauche">
		<p>
		<div class="titrepage">
			Mouvement
		</div>
		<b class="module4ie"><a href="engine.php" class="module4"><img src="im_objets/icon_retour.gif" alt="Retour" />Retour</a></b>
</p>
	</div>
</div>
<?php  

$db = mysql_connect('localhost', 'CENSURE', 'CENSURE') or die("Erreur de connexion au serveur des messages.");
mysql_select_db('CENSURE',$db) or die("Erreur de connexion a la base de donnees des messages.");

if($_GET['num']!="")
	{
	$numgo = $_GET['num'];
	$ruego = trim(str_replace("%20"," ",''.$_GET['rue'].''));
	$ruego = str_replace('&eacute;','e',htmlentities($ruego));
	$ruego = ucwords(strtolower(str_replace('&egrave;','e',$ruego)));
	}
else
	{
	$numgo = $_POST['num'];
	$ruego = trim($_POST['rue']);
	$ruego = str_replace('&eacute;','e',htmlentities($ruego));
	$ruego = ucwords(strtolower(str_replace('&egrave;','e',$ruego)));
	}

if($numgo == "" && $ruego == "")
	{
	$numgo = 0;
	$ruego = "Rue";
	}
elseif($numgo == "" && strtolower($ruego) != "rue")
	{
	$numgo = -1;
	}

$retour = deplacement($_SESSION['pseudo'],$_SESSION['num'],$_SESSION['rue'],$numgo,$ruego);

if($retour == 'ok')
	{
	print('<div id="centre">
	<p>
	<img src="im_objets/loader.gif" alt="Veuillez patienter..." />');
	
	print('<meta http-equiv="refresh" content="0 ; url=http://v2.dreadcast.net/ingame/engine.php">');
	}
else
	{
	if($retour == 'prison')
		{
		print('<div id="centre_imperium">
		<p id="location"></p>
		<p id="textelse">Vous ne pouvez rien faire tant que vous etes en prison.');
		//exit();
		}
	elseif($retour == 'cours')
		{
		print('<div id="centre_imperium">
		<p id="location"></p>
		<p id="textelse">Vous ne pouvez rien faire tant que vous etes en cours.');
		}
	elseif($retour == 'inaccessible') 
		{
		print('<div id="centre">
		<p>Ce lieu est inaccessible.');
		}
	elseif($retour == 'inexistant')
		{
		print('<div id="centre">
		<p>Il n\'y a rien au <i>'.$numgo.' '.$ruego.'</i>.');
		}
	elseif($retour == 'sante')
		{
		print('<div id="centre">
		<p>Vous êtes trop mal en point pour pouvoir vous déplacer aussi loin.');
		}
	}

/*if($_SESSION['statut'] == "Debutant")
	{
	$_SESSION['statut'] = "Joueur";
	$sql = 'UPDATE principal_tbl SET statut="Joueur" WHERE id= "'.$_SESSION['id'].'"' ;
	mysql_query($sql);
	
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

$_SESSION['code'] = 0;

forme_retirer($_SESSION['id'],1);
$_SESSION['fatigue'] -= 1;

$sql = 'SELECT combat,observation,gestion,maintenance,mecanique,service,informatique,recherche,discretion,economie,resistance,tir,vol,medecine FROM principal_tbl WHERE id= "'.$_SESSION['id'].'"' ;
$req = mysql_query($sql);
$_SESSION['combat'] = mysql_result($req,0,combat); 
$_SESSION['observation'] = mysql_result($req,0,observation); 
$_SESSION['gestion'] = mysql_result($req,0,gestion); 
$_SESSION['maintenance'] = mysql_result($req,0,maintenance); 
$_SESSION['mecanique'] = mysql_result($req,0,mecanique); 
$_SESSION['service'] = mysql_result($req,0,service); 
$_SESSION['informatique'] = mysql_result($req,0,informatique); 
$_SESSION['recherche'] = mysql_result($req,0,recherche); 
$_SESSION['discretion'] = mysql_result($req,0,discretion); 
$_SESSION['economie'] = mysql_result($req,0,economie); 
$_SESSION['resistance'] = mysql_result($req,0,resistance); 
$_SESSION['tir'] = mysql_result($req,0,tir); 
$_SESSION['vol'] = mysql_result($req,0,vol); 
$_SESSION['medecine'] = mysql_result($req,0,medecine); 

if($_SESSION['action']=="prison"||($_SESSION['action']=="En cours de Combat (1Heure)") || ($_SESSION['action']=="En cours de Discretion (1Heure)") || ($_SESSION['action']=="En cours de Médecine (1Heure)") || ($_SESSION['action']=="En cours de Tir (1Heure)") || ($_SESSION['action']=="En cours de Eco-Gestion (1Heure)") || ($_SESSION['action']=="En cours de Méca (1Heure)") || ($_SESSION['action']=="En cours de Combat (2Heures)") || ($_SESSION['action']=="En cours de Discretion (2Heures)") || ($_SESSION['action']=="En cours de Médecine (2Heures)") || ($_SESSION['action']=="En cours de Tir (2Heures)") || ($_SESSION['action']=="En cours de Eco-Gestion (2Heures)") || ($_SESSION['action']=="En cours de Méca (2Heures)") || ($_SESSION['action']=="En cours de Combat (3Heures)") || ($_SESSION['action']=="En cours de Discretion (3Heures)") || ($_SESSION['action']=="En cours de Médecine (3Heures)") || ($_SESSION['action']=="En cours de Tir (3Heures)") || ($_SESSION['action']=="En cours de Eco-Gestion (3Heures)") || ($_SESSION['action']=="En cours de Méca (3Heures)") || ($_SESSION['action']=="En cours de Combat (4Heures)") || ($_SESSION['action']=="En cours de Discretion (4Heures)") || ($_SESSION['action']=="En cours de Médecine (4Heures)") || ($_SESSION['action']=="En cours de Tir (4Heures)") || ($_SESSION['action']=="En cours de Eco-Gestion (4Heures)") || ($_SESSION['action']=="En cours de Méca (4Heures)"))
print('<div id="centre_imperium">
	<p id="location"></p>
	<p id="textelse">');
else print('<div id="centre">
	<p>');

if($_SESSION['action']=="prison")
	{
	print('Vous ne pouvez rien faire tant que vous etes en prison.');
	exit();
	}
elseif(($_SESSION['action']=="En cours de Combat (1Heure)") || ($_SESSION['action']=="En cours de Discretion (1Heure)") || ($_SESSION['action']=="En cours de Médecine (1Heure)") || ($_SESSION['action']=="En cours de Tir (1Heure)") || ($_SESSION['action']=="En cours de Eco-Gestion (1Heure)") || ($_SESSION['action']=="En cours de Méca (1Heure)") || ($_SESSION['action']=="En cours de Combat (2Heures)") || ($_SESSION['action']=="En cours de Discretion (2Heures)") || ($_SESSION['action']=="En cours de Médecine (2Heures)") || ($_SESSION['action']=="En cours de Tir (2Heures)") || ($_SESSION['action']=="En cours de Eco-Gestion (2Heures)") || ($_SESSION['action']=="En cours de Méca (2Heures)") || ($_SESSION['action']=="En cours de Combat (3Heures)") || ($_SESSION['action']=="En cours de Discretion (3Heures)") || ($_SESSION['action']=="En cours de Médecine (3Heures)") || ($_SESSION['action']=="En cours de Tir (3Heures)") || ($_SESSION['action']=="En cours de Eco-Gestion (3Heures)") || ($_SESSION['action']=="En cours de Méca (3Heures)") || ($_SESSION['action']=="En cours de Combat (4Heures)") || ($_SESSION['action']=="En cours de Discretion (4Heures)") || ($_SESSION['action']=="En cours de Médecine (4Heures)") || ($_SESSION['action']=="En cours de Tir (4Heures)") || ($_SESSION['action']=="En cours de Eco-Gestion (4Heures)") || ($_SESSION['action']=="En cours de Méca (4Heures)"))
	{
	print('Vous ne pouvez rien faire tant que vous etes en cours.');
	exit();
	}
elseif($_SESSION['action']=="travail")
	{
	print('<div id="centre">
	<p>
	
	<img src="im_objets/loader.gif" alt="..." /><br /><br />');
	$sql = 'SELECT type,entreprise FROM principal_tbl WHERE id= "'.$_SESSION['id'].'"' ;
	$req = mysql_query($sql);
	$_SESSION['poste'] = mysql_result($req,0,type); 
	$_SESSION['entreprise'] = mysql_result($req,0,entreprise); 
	
	$sql = 'SELECT type FROM `e_'.str_replace(" ","_",''.$_SESSION['entreprise'].'').'_tbl` WHERE poste= "'.$_SESSION['poste'].'"' ;
	$req = mysql_query($sql);
	$_SESSION['type'] = mysql_result($req,0,type);
	
	$sql = 'SELECT type FROM entreprises_tbl WHERE nom= "'.$_SESSION['entreprise'].'"' ;
	$req = mysql_query($sql);
	$type = mysql_result($req,0,type); 

	if(($type=="agence immobiliaire") || ($type=="boutique armes") || ($type=="centre de recherche") || ($type=="boutique spécialisee") || ($type=="ventes aux encheres") || ($type=="usine de production"))
		{
		$typevoulu = "vendeur";
		}
	elseif($type=="banque")
		{
		$typevoulu = "banquier";
		}
	elseif(($type=="bar cafe") || ($type=="restaurant"))
		{
		$typevoulu = "serveur";
		}
	elseif($type=="hopital")
		{
		$typevoulu = "medecin";
		}
	$sql1 = 'SELECT poste FROM `e_'.str_replace(" ","_",''.$_SESSION['entreprise'].'').'_tbl` WHERE type= "'.$typevoulu.'"' ;
	$req1 = mysql_query($sql1);
	$res1 = mysql_num_rows($req1);
	$restot = 0;
	for($r=0;$r!=$res1;$r++)
		{
		$poster = mysql_result($req1,$r,poste);
		$sql2 = 'SELECT id FROM principal_tbl WHERE entreprise= "'.$_SESSION['entreprise'].'" AND type= "'.$poster.'"' ;
		$req2 = mysql_query($sql2);
		$res2 = mysql_num_rows($req2);
		for($i=0; $i != $res2; $i++)
			{
			$idipa = mysql_result($req2,$i,id); 
			$sql3 = 'SELECT action FROM principal_tbl WHERE id= "'.$idipa.'"' ;
			$req3 = mysql_query($sql3);
			$act = mysql_result($req3,0,action); 
			if($act=="travail")
				{
				$restot = $restot + 1;
				}
			}
		}
	
	$sql = 'SELECT poste FROM `e_'.str_replace(" ","_",''.$_SESSION['entreprise'].'').'_tbl` WHERE type= "chef"' ;
	$req = mysql_query($sql);
	$_SESSION['postechef'] = mysql_result($req,0,poste); 
	$sql = 'SELECT id FROM principal_tbl WHERE entreprise= "'.$_SESSION['entreprise'].'" AND type= "'.$_SESSION['postechef'].'"' ;
	$req = mysql_query($sql);
	$res = mysql_num_rows($req);
	
	for($i=0; $i != $res; $i++)
		{
		$idipa = mysql_result($req,$i,id); 
		$sql1 = 'SELECT action FROM principal_tbl WHERE id= "'.$idipa.'"' ;
		$req1 = mysql_query($sql1);
		$act = mysql_result($req1,0,action); 
		if($act=="travail")
			{
			$restot = $restot + 1;
			}
		}	
	if($_SESSION['entreprise']!="Aucune")
		{
		$sql = 'SELECT type FROM `e_'.str_replace(" ","_",''.$_SESSION['entreprise'].'').'_tbl` WHERE poste= "'.$_SESSION['poste'].'"' ;
		$req = mysql_query($sql);
		$typeposte = mysql_result($req,0,type); 
		
		$sql = 'SELECT type,ouvert FROM entreprises_tbl WHERE nom= "'.$_SESSION['entreprise'].'"' ;
		$req = mysql_query($sql);
		$type = mysql_result($req,0,type); 
		}
	if(($_SESSION['action']=="travail") && ($restot==1))
		{
		if(($type=="agence immobiliaire") || ($type=="boutique armes") || ($type=="centre de recherche") || ($type=="boutique spécialisee") || ($type=="ventes aux encheres") || ($type=="centre de recherches") || ($type=="usine de production"))
			{
			if(($typeposte=="vendeur") || ($typeposte=="chef"))
				{
				$sql = 'UPDATE entreprises_tbl SET ouvert="non" WHERE nom= "'.$_SESSION['entreprise'].'"' ;
				$req = mysql_query($sql);
				}
			}
		elseif($type=="banque")
			{
			if(($typeposte=="banquier") || ($typeposte=="chef"))
				{
				$sql = 'UPDATE entreprises_tbl SET ouvert="non" WHERE nom= "'.$_SESSION['entreprise'].'"' ;
				$req = mysql_query($sql);
				}
			}
		elseif($type=="bar cafe")
			{
			if(($typeposte=="serveur") || ($typeposte=="chef"))
				{
				$sql = 'UPDATE entreprises_tbl SET ouvert="non" WHERE nom= "'.$_SESSION['entreprise'].'"' ;
				$req = mysql_query($sql);
				}
			}
		elseif($type=="hopital")
			{
			if(($typeposte=="medecin") || ($typeposte=="chef"))
				{
				$sql = 'UPDATE entreprises_tbl SET ouvert="non" WHERE nom= "'.$_SESSION['entreprise'].'"' ;
				$req = mysql_query($sql);
				}
			}
		}
	}

if(!ereg("Protection ",$_SESSION['action']))
	{
	$sql = 'UPDATE principal_tbl SET action="aucune" WHERE pseudo= "'.$_SESSION['pseudo'].'"' ;
	mysql_query($sql);
	}

if(($_SERVER['QUERY_STRING']!="rue") && (strtolower($_SERVER['QUERY_STRING'])!="ruelle") && (strtolower($_GET['rue'])!="ruelle") && (strtolower($_POST['rue'])!="ruelle"))
	{
	if($_GET['num']!="")
		{
		$numgo = $_GET['num'];
		$ruego = trim(str_replace("%20"," ",''.$_GET['rue'].''));
		$ruego = str_replace('&eacute;','e',htmlentities($ruego));
		$ruego = ucwords(strtolower(str_replace('&egrave;','e',$ruego)));
		}
	else
		{
		$numgo = $_POST['num'];
		$ruego = trim($_POST['rue']);
		$ruego = str_replace('&eacute;','e',htmlentities($ruego));
		$ruego = ucwords(strtolower(str_replace('&egrave;','e',$ruego)));
		}
	$sqlb = 'SELECT id,code,planete FROM lieu_tbl WHERE rue="'.$ruego.'" AND num="'.$numgo.'"' ;
	$reqb = mysql_query($sqlb);
	$resb = mysql_num_rows($reqb);
	if($resb!=0)
		{
		if(mysql_result($reqb,0,planete)==$_SESSION['planete'])
			{
			print('<img src="im_objets/loader.gif" alt="..." /><br /><br />');
			if(!ereg("Protection ",$_SESSION['action'])) $update = 'action="aucune" ,';
			$sql = 'UPDATE principal_tbl SET '.$update.' rue="'.ucwords(strtolower($ruego)).'", Num="'.$numgo.'" WHERE pseudo="'.$_SESSION['pseudo'].'"';
			$req = mysql_query($sql);
			$vu = rand(0,10);
			$sqlr = 'SELECT id FROM enquete_tbl WHERE num= "'.$numgo.'" AND rue= "'.$ruego.'" AND pseudo= "'.$_SESSION['pseudo'].'"' ;
			$reqr = mysql_query($sqlr);
			$resr = mysql_num_rows($reqr);
			if(($vu==2) && ($resr==0))
				{
				$sql = 'INSERT INTO enquete_tbl(id,pseudo,num,rue) VALUES("","'.$_SESSION['pseudo'].'","'.$numgo.'","'.ucwords($ruego).'") ';
				$req = mysql_query($sql);
				}
			print('<meta http-equiv="refresh" content="0 ; url=http://v2.dreadcast.net/ingame/engine.php"> ');
			}
		else
			{
			print('Cet endroit n\'existe pas sur cette planète.');
			}
		}
	else
		{
		print('Il n\'y a rien au <i>'.$numgo.' '.$ruego.'</i>.<br> ');
		}
	}
elseif($_SERVER['QUERY_STRING']=="rue")
	{
	$sql = 'UPDATE principal_tbl SET rue="Rue", Num="0" WHERE pseudo="'.$_SESSION['pseudo'].'"';
	$req = mysql_query($sql);
	print('<meta http-equiv="refresh" content="0 ; url=http://v2.dreadcast.net/ingame/engine.php"> ');
	}
elseif($_SERVER['QUERY_STRING']=="ruelle" || strtolower($_GET['rue'])=="ruelle" || strtolower($_POST['rue'])=="ruelle")
	{
	if(($_SESSION['conduite']>40) || ($_SESSION['technologie']>40) || ($_SESSION['combat']>40) || ($_SESSION['observation']>40) || ($_SESSION['gestion']>40) || ($_SESSION['maintenance']>40) || ($_SESSION['mecanique']>40) || ($_SESSION['service']>40) || ($_SESSION['recherche']>40) || ($_SESSION['discretion']>40) || ($_SESSION['economie']>40) || ($_SESSION['resistance']>40) || ($_SESSION['tir']>40) || ($_SESSION['vol']>40) || ($_SESSION['medecine']>40))
		{
		
		}
	else
		{
		$sql = 'UPDATE principal_tbl SET rue="Ruelle", Num="1" WHERE pseudo="'.$_SESSION['pseudo'].'"';
		$req = mysql_query($sql);
		}
	print('<meta http-equiv="refresh" content="0 ; url=http://v2.dreadcast.net/ingame/engine.php"> ');
	}*/

mysql_close($db);
	
?>

</p>
</div>
<?php if ((date("H")>=21) || (date("H")<=7)) { include("inc_bas_n.php"); } else { include("inc_bas_j.php"); }?>
<?php include("inc_bas_de_page.php"); ?>
