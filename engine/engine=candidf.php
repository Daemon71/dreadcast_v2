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
<div id="centre">
<p>


          <?php 

$db = mysql_connect('localhost', 'CENSURE', 'CENSURE') or die("Erreur de connexion au serveur.");
mysql_select_db('CENSURE',$db) or die("Erreur de connexion a la base de donnees.");

if($_SESSION['entreprise']!="Aucune")
	{
	print('<p align="center"><strong>Vous avez d&eacute;j&agrave; un travail. Pour en changez vous devez d&eacute;missionner.</strong></p>');
	print('<form name="form2" method="post" action="engine=emplois.php"><div align="center"><input type="submit" name="Submit" value="Retour"></div></form>');
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

if($pd<1)
	{
	print('<strong>Il n\'y a plus de place pour ce travail.</strong>');
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

if(($candidaturer!="") && ($pd>=1))
	{
	if($typ=="maintenance")
		{
		if($maintenance<$mincompr)
			{
			mysql_close($db);
			print('<meta http-equiv="refresh" content="0 ; url=http://v2.dreadcast.net/ingame/engine=emplois.php"> ');
			exit();
			}
		}
	elseif($typ=="securite")
		{
		if($observation<$mincompr)
			{
			mysql_close($db);
			print('<meta http-equiv="refresh" content="0 ; url=http://v2.dreadcast.net/ingame/engine=emplois.php"> ');
			exit();
			}
		}
	elseif(($typ=="directeur") || ($typ=="chef"))
		{
		if($gestion<$mincompr)
			{
			mysql_close($db);
			print('<meta http-equiv="refresh" content="0 ; url=http://v2.dreadcast.net/ingame/engine=emplois.php"> ');
			exit();
			}
		}
	elseif($typ=="vendeur")
		{
		if($economie<$mincompr)
			{
			mysql_close($db);
			print('<meta http-equiv="refresh" content="0 ; url=http://v2.dreadcast.net/ingame/engine=emplois.php"> ');
			exit();
			}
		}
	elseif($typ=="banquier")
		{
		if($economie<$mincompr)
			{
			mysql_close($db);
			print('<meta http-equiv="refresh" content="0 ; url=http://v2.dreadcast.net/ingame/engine=emplois.php"> ');
			exit();
			}
		}
	elseif($typ=="serveur")
		{
		if($service<$mincompr)
			{
			mysql_close($db);
			print('<meta http-equiv="refresh" content="0 ; url=http://v2.dreadcast.net/ingame/engine=emplois.php"> ');
			exit();
			}
		}
	elseif($typ=="medecin")
		{
		if($medecine<$mincompr)
			{
			mysql_close($db);
			print('<meta http-equiv="refresh" content="0 ; url=http://v2.dreadcast.net/ingame/engine=emplois.php"> ');
			exit();
			}
		}
	elseif($typ=="hote")
		{
		if($service<$mincompr)
			{
			mysql_close($db);
			print('<meta http-equiv="refresh" content="0 ; url=http://v2.dreadcast.net/ingame/engine=emplois.php"> ');
			exit();
			}
		}
	elseif($typ=="technicien")
		{
		if($mecanique<$mincompr)
			{
			mysql_close($db);
			print('<meta http-equiv="refresh" content="0 ; url=http://v2.dreadcast.net/ingame/engine=emplois.php"> ');
			exit();
			}
		}
	$sql = 'INSERT INTO candidatures_tbl(id,nom,entreprise,poste,msg,moment) VALUES("","'.$_SESSION['pseudo'].'","'.$nomr.'","'.$poster.'","'.str_replace("\n","<br />",''.htmlentities($_POST["candid"],ENT_QUOTES).'').'","'.time().'")';
    mysql_query($sql);
	print('<strong>Votre candidature est enregistr&eacute;e. </strong>        
			<br /><br />
			Les dirigeants de l\'entreprise ou de l\'organisation examineront d\'ici peu votre demande. 
			<br />
			Si vous trouvez un autre travail avant la consultation de cette candidature, elle sera effac&eacute;e.
			');
	}

mysql_close($db);

?>



</p>
</div>
<?php if($chat=="oui") 	{ if ((date("H")>=21) || (date("H")<=7)) { include("inc_bas_n_c.php"); } else { include("inc_bas_j_c.php"); } } else {	if ((date("H")>=21) || (date("H")<=7)) { include("inc_bas_n.php"); } else { include("inc_bas_j.php"); }	} ?>
<?php include("inc_bas_de_page.php"); ?>
