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
$db = mysql_connect('localhost', 'CENSURE', 'CENSURE') or die("Erreur de connexion au serveur des messages.");
mysql_select_db('CENSURE',$db) or die("Erreur de connexion a la base de donnees des messages.");

$sql = 'SELECT entreprise FROM principal_tbl WHERE id= "'.$_SESSION['id'].'"' ;
$req = mysql_query($sql);
$_SESSION['entreprise'] = mysql_result($req,0,entreprise);

$sql = 'SELECT type FROM entreprises_tbl WHERE rue= "'.$_SESSION['lieu'].'" AND num= "'.$_SESSION['num'].'"' ;
$req = mysql_query($sql);
$type = mysql_result($req,0,type);

if($type!="CIPE")
	{
	print('<meta http-equiv="refresh" content="0 ; url=http://v2.dreadcast.net/ingame/engine.php"> ');
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
			Recherche d'emploi
		</div>
		<b class="module4ie"><a href="engine=emplois.php" class="module4"><img src="im_objets/icon_retour.gif" alt="Retour" />Retour</a></b>
</p>
	</div>
</div>
<div id="centre_imperium">

<p id="location">Centre Imp&eacute;rial Pour l'Emploi</p>

<p id="textelse">

<?php 

$db = mysql_connect('localhost', 'CENSURE', 'CENSURE') or die("Erreur de connexion au serveur.");
mysql_select_db('CENSURE',$db) or die("Erreur de connexion a la base de donnees.");

if($_SESSION['entreprise']!="Aucune")
	{
	print('<strong>Vous avez d&eacute;j&agrave; un travail. Si vous souhaitez en changer, vous devez d\'abord d&eacute;missionner.</strong><br />
	<form id="maforme" name="form2" method="post" action="engine=emplois.php"><input id="valid" type="submit" name="Submit" value="Retour"></form>');
	exit();
	}

$nomr = str_replace("%20"," ",''.$_GET['ent'].'');
$poster = str_replace("%20"," ",''.$_GET['poste'].'');
$typer = str_replace("%20"," ",''.$_GET['type'].'');

$sql1 = 'SELECT type,salaire,nbrepostes,nbreactuel,mincomp,candidature FROM `e_'.str_replace(" ","_",''.$nomr.'').'_tbl` WHERE poste= "'.$poster.'" AND type="'.$typer.'"' ;
$req1 = mysql_query($sql1);
$typ = mysql_result($req1,0,type);
$salairer = mysql_result($req1,0,salaire);
$nbrepostesr = mysql_result($req1,0,nbrepostes);
$nbreactuelr = mysql_result($req1,0,nbreactuel);
$mincompr = mysql_result($req1,0,mincomp);
$candidaturer = mysql_result($req1,0,candidature);
$pd = $nbrepostesr - $nbreactuelr;

if($typ=="chef")
	{
	$points = 999;
	}
else
	{
	$points = 0;
	}

if($pd<1)
	{
	print('<strong>Il n\'y a plus de place pour ce travail.</strong>');
	exit();
	}

$sql = 'SELECT combat,observation,gestion,maintenance,mecanique,service,informatique,recherche,economie,tir,medecine FROM principal_tbl WHERE id="'.$_SESSION['id'].'"' ;
$req = mysql_query($sql);
$combat = mysql_result($req,0,combat);
$observation = mysql_result($req,0,observation);
$gestion = mysql_result($req,0,gestion);
$maintenance = mysql_result($req,0,maintenance);
$mecanique = mysql_result($req,0,mecanique);
$service = mysql_result($req,0,service);
$informatique = mysql_result($req,0,informatique);
$recherche = mysql_result($req,0,recherche);
$economie = mysql_result($req,0,economie);
$tir = mysql_result($req,0,tir);
$medecine = mysql_result($req,0,medecine);

if($candidaturer=="")
	{
	if($typ=="maintenance")
		{
		if($maintenance<$mincompr)
			{
			print('<strong>Vous n\'avez pas les comp&eacute;tences requises pour ce poste.</strong>');
			exit();
			}
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
		if($observation<$mincompr)
			{
			print('<strong>Vous n\'avez pas les comp&eacute;tences requises pour ce poste.</strong>');
			exit();
			}
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
	elseif(($typ=="directeur") || ($typ=="chef"))
		{
		if($gestion<$mincompr)
			{
			print('<strong>Vous n\'avez pas les comp&eacute;tences requises pour ce poste.</strong>');
			exit();
			}
		$diff = 0;
		}
	elseif($typ=="vendeur")
		{
		if($economie<$mincompr)
			{
			print('<strong>Vous n\'avez pas les comp&eacute;tences requises pour ce poste.</strong>');
			exit();
			}
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
		if($economie<$mincompr)
			{
			print('<strong>Vous n\'avez pas les comp&eacute;tences requises pour ce poste.</strong>');
			exit();
			}
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
	elseif($typ=="serveur")
		{
		if($service<$mincompr)
			{
			print('<strong>Vous n\'avez pas les comp&eacute;tences requises pour ce poste.</strong>');
			exit();
			}
		if($service<30)
			{
			$diff = 6;
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
	elseif($typ=="medecin")
		{
		if($medecine<$mincompr)
			{
			print('<strong>Vous n\'avez pas les comp&eacute;tences requises pour ce poste.</strong>');
			exit();
			}
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
		if($service<$mincompr)
			{
			print('<strong>Vous n\'avez pas les comp&eacute;tences requises pour ce poste.</strong>');
			exit();
			}
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
		if($mecanique<$mincompr)
			{
			print('<strong>Vous n\'avez pas les comp&eacute;tences requises pour ce poste.</strong>');
			exit();
			}
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
	$nbreactuelr = $nbreactuelr + 1;
	$sql = 'UPDATE `e_'.str_replace(" ","_",''.$nomr.'').'_tbl` SET nbreactuel= "'.$nbreactuelr.'" WHERE poste= "'.$poster.'"' ;
	$req = mysql_query($sql);
	$sql = 'UPDATE principal_tbl SET nouveau= "oui" , entreprise= "'.$nomr.'" , salaire= "'.$salairer.'" , type= "'.$poster.'" , difficulte= "'.$diff.'" , points="'.$points.'" WHERE id= "'.$_SESSION['id'].'"' ;
	$req = mysql_query($sql);
	$sql = 'DELETE FROM candidatures_tbl WHERE nom="'.$_SESSION['pseudo'].'"' ;
	$req = mysql_query($sql);
	enregistre($_SESSION['pseudo'],'emploi',valeur($_SESSION['pseudo'],'emploi')+1);
	print('<meta http-equiv="refresh" content="0 ; url=http://v2.dreadcast.net/ingame/engine=activite.php"> ');
	}
else
	{
	print('<form id="maforme" action="engine=candidf.php?ent='.$nomr.'&poste='.$poster.'&type='.$typer.'" method="post" name="cand"><strong>Message de candidature</strong><br /><textarea name="candid" cols="40" rows="5" id="candid"></textarea><br /><input id="valid" type="submit" name="Submit" value="Envoyer"></form>');
	}

mysql_close($db);

?>
</p>


</div>
<?php if($chat=="oui") 	{ if ((date("H")>=21) || (date("H")<=7)) { include("inc_bas_n_c.php"); } else { include("inc_bas_j_c.php"); } } else {	if ((date("H")>=21) || (date("H")<=7)) { include("inc_bas_n.php"); } else { include("inc_bas_j.php"); }	} ?>
<?php include("inc_bas_de_page.php"); ?>
