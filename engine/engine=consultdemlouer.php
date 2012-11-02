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

$sqlc = 'SELECT * FROM candlouer_tbl WHERE id= "'.$_GET['id'].'"' ;
$reqc = mysql_query($sqlc);
$candidat = mysql_result($reqc,0,candidat);
$demande = mysql_result($reqc,0,demande);
$numl = mysql_result($reqc,0,num);
$ruel = mysql_result($reqc,0,rue);

$sql = 'SELECT id FROM principal_tbl WHERE rue= "'.$ruel.'" AND num= "'.$numl.'"' ;
$req = mysql_query($sql);
$resl = mysql_num_rows($req);

if($resl!=0)
	{
	print('<meta http-equiv="refresh" content="0 ; url=http://v2.dreadcast.net/ingame/engine=logement.php"> ');
	exit();
	}

$sql = 'SELECT * FROM proprietaire_tbl WHERE pseudo= "'.$_SESSION['pseudo'].'" AND rue= "'.$ruel.'" AND num= "'.$numl.'"' ;
$req = mysql_query($sql);
$res = mysql_num_rows($req);

if($res==0)
	{
	print('<meta http-equiv="refresh" content="0 ; url=http://v2.dreadcast.net/ingame/engine=logement.php"> ');
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
			Suivis des demandes
		</div>
		<b class="module4ie"><a href="engine=consultlocation.php?rue=<?php print(''.$_SESSION['autrelor'].''); ?>&num=<?php print(''.$_SESSION['autrelon'].''); ?>" class="module4"><img src="im_objets/icon_retour.gif" alt="Retour" />Retour</a></b>
</p>
	</div>
</div>
<div id="centre">
<p>

<?php

print('<p align="center">Demande de location de <strong>'.$candidat.'</strong> :</p>');
print('<p align="center">'.$demande.'</p>');
print('<p align="center"><a href="engine=locataire.php?id='.$_GET['id'].'&sujet=acc">Accepter le locataire</a><br /><a href="engine=locataire.php?id='.$_GET['id'].'&sujet=ref">Refuser le locataire</a></p>');

?>


</p>
</div>
<?php if($chat=="oui") 	{ if ((date("H")>=21) || (date("H")<=7)) { include("inc_bas_n_c.php"); } else { include("inc_bas_j_c.php"); } } else {	if ((date("H")>=21) || (date("H")<=7)) { include("inc_bas_n.php"); } else { include("inc_bas_j.php"); }	} ?>
<?php include("inc_bas_de_page.php"); ?>
