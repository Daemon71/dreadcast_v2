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

$sql = 'SELECT type,entreprise,points FROM principal_tbl WHERE id= "'.$_SESSION['id'].'"' ;
$req = mysql_query($sql);
$_SESSION['poste'] = mysql_result($req,0,type); 
$_SESSION['entreprise'] = mysql_result($req,0,entreprise); 
$_SESSION['points'] = mysql_result($req,0,points); 

mysql_close($db);

if($_SESSION['entreprise']!="Conseil Imperial" && $_SESSION['statut'] != "Administrateur") { print('<meta http-equiv="refresh" content="0 ; url=http://v2.dreadcast.net/ingame/engine.php"> '); exit(); } 
?>
<?php if ((date("H")>=21) || (date("H")<=7)) { include("inc_haut_n.php"); } else { include("inc_haut_j.php");} ?>

<div id="haut">
	<?php include("inc_entete.php"); ?>
	<div class="haut_aligngauche">
		<p>
		<div class="titrepage">
			Services
		</div>
		<b class="module4ie"><a href="engine=services.php" class="module4"><img src="im_objets/icon_retour.gif" alt="Retour" />Retour</a></b>
</p>
	</div>
</div>
<div id="centre">
<p>

<?php 

$db = mysql_connect('localhost', 'CENSURE', 'CENSURE') or die("Erreur de connexion au serveur des messages.");
mysql_select_db('CENSURE',$db) or die("Erreur de connexion a la base de donnees des messages.");

$sql = 'SELECT * FROM candidatures_tbl WHERE ( entreprise= "DI2RCO" AND poste="Directeur du DI2RCO" AND id= "'.$_GET['id'].'" ) OR ( entreprise= "Police" AND poste="Chef de la police" AND id= "'.$_GET['id'].'" ) OR ( entreprise= "Services techniques de la ville" AND poste="Directeur des services" AND id= "'.$_GET['id'].'" ) OR ( entreprise= "Prison" AND poste="Directeur de la prison" AND id= "'.$_GET['id'].'" ) OR ( entreprise= "DOI" AND poste="Directeur des Organisations" AND id= "'.$_GET['id'].'" ) OR ( entreprise= "Chambre des lois" AND poste="Premier Consul" AND id= "'.$_GET['id'].'" ) OR ( entreprise= "CIE" AND poste="Directeur du CIE" AND id= "'.$_GET['id'].'" ) OR ( entreprise= "CIPE" AND poste="Directeur du CIPE" AND id= "'.$_GET['id'].'" ) OR ( entreprise= "DC Network" AND poste="Directeur du DC Network" AND id= "'.$_GET['id'].'" )' ;
$req = mysql_query($sql);
$res = mysql_num_rows($req);

if($res!=0)
	{
	$ido = mysql_result($req,0,id);
	$auteur = mysql_result($req,0,nom);
	$institution = mysql_result($req,0,entreprise);
	$message = mysql_result($req,0,msg);
	$poste = mysql_result($req,0,poste);
	print('<p align="center">Candidature de <strong>'.$auteur.'</strong> au poste de : <strong>'.$poste.'</strong> dans <strong>'.$institution.'</strong></p><p align="center" class="barreadmin">'.$message.'</p><p align="center"><a href="engine=contacter.php?cible='.$auteur.'&objet=Candidature">Envoyer un message au candidat</a>');
	$sqla = 'SELECT id FROM votesDI_tbl WHERE voteur="'.$_SESSION['pseudo'].'" AND poste="'.$poste.'" AND candidat="'.$auteur.'"';
	$reqa = mysql_query($sqla);
	$resa = mysql_num_rows($reqa);
	if($resa==0)
		{
		print('<br><br><a href="engine=voteoui.php?id='.$ido.'">Accorder votre voix</a><br><a href="engine=votenon.php?id='.$ido.'">Refuser le candidat</a></p>');
		}
	}
else
	{
	print("<strong>Cette candidature ne peut pas s'afficher.</strong><br>");
	}
	
mysql_close($db);

?>

</p>
</div>
<?php if($chat=="oui") 	{ if ((date("H")>=21) || (date("H")<=7)) { include("inc_bas_n_c.php"); } else { include("inc_bas_j_c.php"); } } else {	if ((date("H")>=21) || (date("H")<=7)) { include("inc_bas_n.php"); } else { include("inc_bas_j.php"); }	} ?>
<?php include("inc_bas_de_page.php"); ?>
