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
?>
<?php if ((date("H")>=21) || (date("H")<=7)) { include("inc_haut_n.php"); } else { include("inc_haut_j.php");} ?>

<div id="haut">
	<div class="haut_aligngauche">
		<p>
		<div class="titrepage">
			Recréation d'un personnage
		</div>
		</p>
	</div>
</div>
<div id="centre">
<p>

<?php	
$db = mysql_connect('localhost', 'CENSURE', 'CENSURE') or die("Erreur de connexion au serveur.");
mysql_select_db('CENSURE',$db) or die("Erreur de connexion a la base de donnees.");

$sql = 'SELECT id,action,race,taille,age,Num FROM principal_tbl WHERE pseudo= "'.$_SESSION['pseudo'].'"' ;
$req = mysql_query($sql);
$_SESSION['id'] = mysql_result($req,0,id);
$_SESSION['action'] = mysql_result($req,0,action);
$race = mysql_result($req,0,race);
$_SESSION['taille'] = mysql_result($req,0,taille);
$_SESSION['Num'] = mysql_result($req,0,num);
$age = mysql_result($req,0,age);

//$sqlmort1 = 'SELECT raison FROM deces_tbl WHERE victime="'.$_SESSION['pseudo'].'"';
//$reqmort1 = mysql_query($sqlmort1);
//$resmort1 = mysql_num_rows($reqmort1);

if(($_SESSION['action']=="mort") && ($_SESSION['Num']>0))
	{
	$sql = 'SELECT valeur FROM config_tbl WHERE objet= "pourcperte"' ;
	$req = mysql_query($sql);
	$pourcperte = mysql_result($req,0,valeur);
	$sql = 'SELECT valeur FROM config_tbl WHERE objet= "pourcsperte"' ;
	$req = mysql_query($sql);
	$pourcsperte = mysql_result($req,0,valeur);
	$sql = 'SELECT combat,observation,gestion,maintenance,mecanique,service,discretion,economie,resistance,tir,vol,medecine,informatique,recherche FROM principal_tbl WHERE id= "'.$_SESSION['id'].'"' ;
	$req = mysql_query($sql);
	$_SESSION['combat'] = ceil(mysql_result($req,0,combat)*($pourcsperte/100));
	$_SESSION['observation'] = ceil(mysql_result($req,0,observation)*($pourcsperte/100));
	$_SESSION['gestion'] = ceil(mysql_result($req,0,gestion)*($pourcsperte/100));
	$_SESSION['maintenance'] = ceil(mysql_result($req,0,maintenance)*($pourcsperte/100));
	$_SESSION['mecanique'] = ceil(mysql_result($req,0,mecanique)*($pourcsperte/100));
	$_SESSION['service'] = ceil(mysql_result($req,0,service)*($pourcsperte/100));
	$_SESSION['discretion'] = ceil(mysql_result($req,0,discretion)*($pourcsperte/100));
	$_SESSION['economie'] = ceil(mysql_result($req,0,economie)*($pourcsperte/100));
	$_SESSION['resistance'] = ceil(mysql_result($req,0,resistance)*($pourcsperte/100));
	$_SESSION['tir'] = ceil(mysql_result($req,0,tir)*($pourcsperte/100));
	$_SESSION['vol'] = ceil(mysql_result($req,0,vol)*($pourcsperte/100));
	$_SESSION['informatique'] = ceil(mysql_result($req,0,informatique)*($pourcsperte/100));
	$_SESSION['recherche'] = ceil(mysql_result($req,0,recherche)*($pourcsperte/100));
	$_SESSION['medecine'] = ceil(mysql_result($req,0,medecine)*($pourcsperte/100));
	$sql = 'UPDATE principal_tbl SET combat= "'.$_SESSION['combat'].'" , observation= "'.$_SESSION['observation'].'" , gestion= "'.$_SESSION['gestion'].'" , maintenance= "'.$_SESSION['maintenance'].'" , mecanique= "'.$_SESSION['mecanique'].'" , service= "'.$_SESSION['service'].'" , discretion= "'.$_SESSION['discretion'].'" , economie= "'.$_SESSION['economie'].'" , resistance= "'.$_SESSION['resistance'].'" , recherche= "'.$_SESSION['recherche'].'" , tir= "'.$_SESSION['tir'].'" , vol= "'.$_SESSION['vol'].'" , medecine= "'.$_SESSION['medecine'].'" , informatique= "'.$_SESSION['informatique'].'" WHERE id= "'.$_SESSION['id'].'"' ;
	$req = mysql_query($sql);
	$sql = 'SELECT credits FROM principal_tbl WHERE id= "'.$_SESSION['id'].'"' ;
	$req = mysql_query($sql);
	$anciencred = mysql_result($req,0,credits);
	$_SESSION['credits'] = 15 + ceil(($pourcperte/100)*($anciencred));
	$sqlspe = 'INSERT INTO transferts_tbl(donneur,receveur,time,operation,valeur) VALUES("'.$_SESSION['pseudo'].'","Dreadcast","'.time().'","Mort personnage","'.($anciencred-$_SESSION['credits']).'")';
	$reqspe = mysql_query($sqlspe);
	$_SESSION['action'] = "aucune";
// Definition du lieu d'atterissage
	$sql = 'SELECT num,rue FROM lieux_speciaux_tbl WHERE type= "recreation" ORDER BY RAND() LIMIT 1' ;
	$req = mysql_query($sql);
	$sql = 'UPDATE principal_tbl SET action= "aucune" , rue= "'.mysql_result($req,0,rue).'" , num= "'.mysql_result($req,0,num).'" , alim= "7" , faim="50", soif="50" ,  sante=sante_max , fatigue=fatigue_max WHERE pseudo= "'.$_SESSION['pseudo'].'"' ;
	$req = mysql_query($sql);
	$sql = 'SELECT objet FROM principal_tbl WHERE id= "'.$_SESSION['id'].'"' ;
	$req = mysql_query($sql);
	if (possede_talent("Haut Dignitaire"))
		$case2="Bague de Haut Dignitaire";
	else
		$case2="Vide";
	if(mysql_result($req,0,objet)!="Anneau de puissance")
		{
		$sql1 = 'UPDATE principal_tbl SET case1= "Carte" , case2= "'.$case2.'" , case3= "Vide" , case4= "Vide" , case5= "Vide" , case6= "Vide" , credits= "'.$_SESSION['credits'].'" , arme= "Aucune" , vetements= "Veste" , objet= "Aucun" WHERE id= "'.$_SESSION['id'].'"' ;
		$req1 = mysql_query($sql1);
		}
	else
		{
		$sql1 = 'UPDATE principal_tbl SET case1= "Carte" , case2= "'.$case2.'" , case3= "Vide" , case4= "Vide" , case5= "Vide" , case6= "Vide" , credits= "'.$_SESSION['credits'].'" , objet= "Aucun" WHERE id= "'.$_SESSION['id'].'"' ;
		$req1 = mysql_query($sql1);
		}
	$sql = 'DELETE FROM deces_tbl WHERE victime = "'.$_SESSION['pseudo'].'"' ;
	$req = mysql_query($sql);
	//$sql = 'UPDATE principal_tbl SET attaque= "Aucune" , police= "Aucune" , intrusion= "Aucune" , vol= "Aucune" WHERE id= "'.$_SESSION['id'].'"' ;
	//$req = mysql_query($sql);
	print('<meta http-equiv="refresh" content="0 ; url=http://v2.dreadcast.net/ingame/engine.php"');
	}
else
	{
	print('<meta http-equiv="refresh" content="0 ; url=http://v2.dreadcast.net/"');
	}
mysql_close($db);
?>

</p>
</div>
<?php if($chat=="oui") 	{ if ((date("H")>=21) || (date("H")<=7)) { include("inc_bas_n_c.php"); } else { include("inc_bas_j_c.php"); } } else {	if ((date("H")>=21) || (date("H")<=7)) { include("inc_bas_n.php"); } else { include("inc_bas_j.php"); }	} ?>
<?php include("inc_bas_de_page.php"); ?>
