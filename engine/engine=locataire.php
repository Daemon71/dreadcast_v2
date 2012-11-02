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

if($_GET['sujet']=="acc")
	{
	$l = 0;
	
	$sqlc = 'SELECT * FROM candlouer_tbl WHERE id= "'.$_GET['id'].'"' ;
	$reqc = mysql_query($sqlc);
	$candidat = mysql_result($reqc,0,candidat);
	$demande = mysql_result($reqc,0,demande);
	$numl = mysql_result($reqc,0,num);
	$ruel = mysql_result($reqc,0,rue);
	
	$sql = 'SELECT id FROM principal_tbl WHERE pseudo= "'.$candidat.'"' ;
	$req = mysql_query($sql);
	$idl = mysql_result($req,0,id);

	$sql = 'SELECT id FROM principal_tbl WHERE ruel= "'.$ruel.'" AND numl= "'.$numl.'"' ;
	$req = mysql_query($sql);
	$resl = mysql_num_rows($req);
	
	if($resl!=0)
		{
		$l = 2;
		}
	
	$sql = 'SELECT * FROM proprietaire_tbl WHERE pseudo= "'.$_SESSION['pseudo'].'" AND rue= "'.$ruel.'" AND num= "'.$numl.'"' ;
	$req = mysql_query($sql);
	$res = mysql_num_rows($req);
	
	if($res==0)
		{
		$l = 3;
		}
	
	if($l==0)
		{
		$enregistre = "ok";
		$sqlc = 'DELETE FROM candlouer_tbl WHERE candidat= "'.$candidat.'"' ;
		$reqc = mysql_query($sqlc);
		$sql = 'UPDATE principal_tbl SET ruel= "'.$ruel.'" , numl= "'.$numl.'" WHERE id= "'.$idl.'"' ;
		$req = mysql_query($sql);
		}
	}
elseif($_GET['sujet']=="ref")
	{
	$l = 5;
	$sqlc = 'DELETE FROM candlouer_tbl WHERE id= "'.$_GET['id'].'"' ;
	$reqc = mysql_query($sqlc);
	}

mysql_close($db);
?>
<?php if ((date("H")>=21) || (date("H")<=7)) { include("inc_haut_n.php"); } else { include("inc_haut_j.php");} ?>

<div id="haut">
	<?php include("inc_entete.php"); ?>
	<div class="haut_aligngauche">
		<p>
		<div class="titrepage">
			Suivis des demandes
		</div>
		<b class="module4ie"><a href="engine=consultlocation.php?rue=<?php print(''.$_SESSION['autrelor'].''); ?>&num=<?php print(''.$_SESSION['autrelon'].''); ?>" class="module4"><img src="im_objets/icon_retour.gif" alt="Retour" />Retour</a></b>
</p>
	</div>
</div>
<div id="centre">
<p>

<?php

$db = mysql_connect('localhost', 'CENSURE', 'CENSURE') or die("Erreur de connexion au serveur des messages.");
mysql_select_db('CENSURE',$db) or die("Erreur de connexion a la base de donnees des messages.");

if($enregistre == "ok") enregistre($candidat,"acc_logement_loue","+1");

mysql_close($db);

if($l==1)
	{
	print('<p align="center"><i>Le candidat à déjà un logement.</i></p>');
	}
elseif($l==2)
	{
	print('<p align="center"><i>Ce logement est déjà occupé.</i></p>');
	}
elseif($l==3)
	{
	print('<p align="center"><i>Ce logement ne vous appartient pas.</i></p>');
	}
elseif($l==0)
	{
	print('<p align="center"><i>Le nouveau locataire est installé.</i></p>');
	}
elseif($l==5)
	{
	print('<p align="center"><i>Le candidat à été refusé.</i></p>');
	}

?>

</p>
</div>
<?php if($chat=="oui") 	{ if ((date("H")>=21) || (date("H")<=7)) { include("inc_bas_n_c.php"); } else { include("inc_bas_j_c.php"); } } else {	if ((date("H")>=21) || (date("H")<=7)) { include("inc_bas_n.php"); } else { include("inc_bas_j.php"); }	} ?>
<?php include("inc_bas_de_page.php"); ?>
