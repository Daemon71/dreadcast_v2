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
if ((date("H")>=21) || (date("H")<=7)) { include("inc_haut_n.php"); } else { include("inc_haut_j.php");}

$db = mysql_connect('localhost', 'CENSURE', 'CENSURE') or die("Erreur de connexion au serveur des messages.");
mysql_select_db('CENSURE',$db) or die("Erreur de connexion a la base de donnees des messages.");

$postec = str_replace("%20"," ",''.$_GET['poste'].'');
$ido = $_GET['id2'];

$sql = 'SELECT type,entreprise,points FROM principal_tbl WHERE id= "'.$_SESSION['id'].'"' ;
$req = mysql_query($sql);
$_SESSION['poste'] = mysql_result($req,0,type); 
$_SESSION['entreprise'] = mysql_result($req,0,entreprise); 
$_SESSION['points'] = mysql_result($req,0,points); 

if($_SESSION['points']!=999) { print('<meta http-equiv="refresh" content="0 ; url=http://v2.dreadcast.net/ingame/engine.php"> '); exit(); } 

$sql = 'SELECT id,type,salaire,nbrepostes,nbreactuel FROM `e_'.str_replace(" ","_",''.$_SESSION['entreprise'].'').'_tbl` WHERE poste= "'.$postec.'"' ;
$req = mysql_query($sql);
$idi = mysql_result($req,0,id);
$typ = mysql_result($req,0,type);
$sal = mysql_result($req,0,salaire);
$nbrepostes = mysql_result($req,0,nbrepostes);
$nbreactuel = mysql_result($req,0,nbreactuel);

if($nbrepostes>$nbreactuel)
	{
	$sql = 'SELECT id,nom FROM candidatures_tbl WHERE id="'.$ido.'"' ;
	$req = mysql_query($sql);
	$nom = mysql_result($req,0,nom);
	
	$sql = 'SELECT id,entreprise FROM principal_tbl WHERE pseudo="'.$nom.'"' ;
	$req = mysql_query($sql);
	$idp = mysql_result($req,0,id);
	$entop = mysql_result($req,0,entreprise);
	
	$sql = 'SELECT combat,observation,gestion,maintenance,mecanique,service,recherche,economie,tir,medecine FROM principal_tbl WHERE id="'.$idp.'"' ;
	$req = mysql_query($sql);
	$combat = mysql_result($req,0,combat);
	$observation = mysql_result($req,0,observation);
	$gestion = mysql_result($req,0,gestion);
	$maintenance = mysql_result($req,0,maintenance);
	$mecanique = mysql_result($req,0,mecanique);
	$service = mysql_result($req,0,service);
	$recherche = mysql_result($req,0,recherche);
	$economie = mysql_result($req,0,economie);
	$tir = mysql_result($req,0,tir);
	$medecine = mysql_result($req,0,medecine);
	
	if($typ=="maintenance")
		{
		if($maintenance<15)
			{
			$diff = 8;
			}
		elseif($maintenance<30)
			{
			$diff = 6;
			}
		elseif($maintenance<50)
			{
			$diff = 4;
			}
		elseif($maintenance<100)
			{
			$diff = 3;
			}
		elseif($maintenance==100)
			{
			$diff = 2;
			}
		}
	elseif($typ=="securite")
		{
		if($observation<25)
			{
			$diff = 6;
			}
		elseif($observation<100)
			{
			$diff = 4;
			}
		elseif($observation==100)
			{
			$diff = 2;
			}
		}
	elseif(($typ=="directeur") || ($typ=="autre") || ($typ=="chef"))
		{
		$diff = 0;
		}
	elseif($typ=="vendeur")
		{
		if($economie<25)
			{
			$diff = 6;
			}
		elseif($economie<60)
			{
			$diff = 4;
			}
		elseif($economie<100)
			{
			$diff = 3;
			}
		elseif($economie==100)
			{
			$diff = 2;
			}
		}
	elseif($typ=="banquier")
		{
		if($economie<25)
			{
			$diff = 6;
			}
		elseif($economie<60)
			{
			$diff = 4;
			}
		elseif($economie<100)
			{
			$diff = 3;
			}
		elseif($economie==100)
			{
			$diff = 2;
			}
		}
	elseif($typ=="medecin")
		{
		if($medecine<55)
			{
			$diff = 8;
			}
		elseif($medecine<100)
			{
			$diff = 5;
			}
		elseif($medecine==100)
			{
			$diff = 3;
			}
		}
	elseif($typ=="hote")
		{
		if($service<25)
			{
			$diff = 7;
			}
		elseif($service<50)
			{
			$diff = 5;
			}
		elseif($service<100)
			{
			$diff = 4;
			}
		elseif($service==100)
			{
			$diff = 2;
			}
		}
	elseif($typ=="technicien")
		{
		if($mecanique<25)
			{
			$diff = 6;
			}
		elseif($mecanique<100)
			{
			$diff = 4;
			}
		elseif($mecanique==100)
			{
			$diff = 2;
			}
		}
	
	$nbreactuel = $nbreactuel + 1;
	if($typ=="chef")
		{
		$points = 999;
		}
	else
		{
		$points = 0;
		}
	$sql = 'UPDATE principal_tbl SET type="'.$postec.'" , entreprise="'.$_SESSION['entreprise'].'" , salaire="'.$sal.'" , difficulte="'.$diff.'" , points="'.$points.'" , nouveau= "oui" WHERE id="'.$idp.'"' ;
	$req = mysql_query($sql);
	$sql = 'UPDATE `e_'.str_replace(" ","_",''.$_SESSION['entreprise'].'').'_tbl` SET nbreactuel="'.$nbreactuel.'" WHERE id="'.$idi.'"' ;
	$req = mysql_query($sql);
	$sql = 'DELETE FROM candidatures_tbl WHERE nom="'.$nom.'"' ;
	$req = mysql_query($sql);
	enregistre($nom,'emploi',valeur($nom,'emploi')+1);
	$sql = 'INSERT INTO messages_tbl(id,auteur,cible,message,moment,objet) VALUES("","'.$_SESSION['entreprise'].'","'.$nom.'","Vous avez été accepté au poste de '.$postec.'. Pour consulter vos conditions de travail, rendez vous dans la rubrique \'Travail->Créer/Gérer\'.","'.time().'","Nouvel emploi")' ;
	$req = mysql_query($sql);
	
	$pris = 1;
	}

mysql_close($db);
?>

<div id="haut">
	<?php include("inc_entete.php"); ?>
	<div class="haut_aligngauche">
		<p>
		<div class="titrepage">
			Accepter une candidature
		</div>
		<b class="module4ie"><a href="engine=personnel.php" class="module4"><img src="im_objets/icon_retour.gif" alt="Retour" />Retour</b></a>
		</p>
	</div>
</div>
<div id="centre">
<p>

<?php
$db = mysql_connect('localhost', 'CENSURE', 'CENSURE') or die("Erreur de connexion au serveur des messages.");
mysql_select_db('CENSURE',$db) or die("Erreur de connexion a la base de donnees des messages.");

if($pris!=1)
	{
	print('<strong>Impossible, le nombre de postes maximum est atteint.</center>');
	}
else
	{
	print('<meta http-equiv="refresh" content="0 ; url=http://v2.dreadcast.net/ingame/engine=personnel.php"> ');
	}
	
mysql_close($db);
?>

</p>
</div>
<?php if($chat=="oui") 	{ if ((date("H")>=21) || (date("H")<=7)) { include("inc_bas_n_c.php"); } else { include("inc_bas_j_c.php"); } } else {	if ((date("H")>=21) || (date("H")<=7)) { include("inc_bas_n.php"); } else { include("inc_bas_j.php"); }	} ?>
<?php include("inc_bas_de_page.php"); ?>
