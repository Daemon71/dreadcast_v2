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

$sql = 'SELECT nom FROM lieu_tbl WHERE rue= "'.$_GET['rue'].'" AND num= "'.$_GET['num'].'"' ;
$req = mysql_query($sql);
$noml = mysql_result($req,0,nom);

$sql = 'SELECT image FROM objets_tbl WHERE nom= "'.$noml.'"' ;
$req = mysql_query($sql);
$image = mysql_result($req,0,image);

$sql = 'SELECT * FROM proprietaire_tbl WHERE pseudo= "'.$_SESSION['pseudo'].'" AND rue= "'.$_GET['rue'].'" AND num= "'.$_GET['num'].'"' ;
$req = mysql_query($sql);
$res = mysql_num_rows($req);

if($res!=0)
	{
	$_SESSION['autrelor'] = $_GET['rue'];
	$_SESSION['autrelon'] = $_GET['num'];
	$prix = mysql_result($req,0,location);
	$cand = mysql_result($req,0,cand);
	}
else
	{
	print('<meta http-equiv="refresh" content="0 ; url=http://v2.dreadcast.net/ingame/engine=logement.php"> ');
	exit();
	}

$sql = 'SELECT id FROM principal_tbl WHERE ruel= "'.$_GET['rue'].'" AND numl= "'.$_GET['num'].'"' ;
$req = mysql_query($sql);
$resl = mysql_num_rows($req);

if($resl!=0)
	{
	$sql = 'SELECT pseudo FROM principal_tbl WHERE id= "'.mysql_result($req,0,id).'"' ;
	$req = mysql_query($sql);
	$pseudol = mysql_result($req,0,pseudo);
	}

$sqlc = 'SELECT id,candidat FROM candlouer_tbl WHERE num= "'.$_GET['num'].'" AND rue= "'.$_GET['rue'].'"' ;
$reqc = mysql_query($sqlc);
$resc = mysql_num_rows($reqc);

mysql_close($db);

?>
<?php if ((date("H")>=21) || (date("H")<=7)) { include("inc_haut_n.php"); } else { include("inc_haut_j.php");} ?>

<div id="haut">
	<?php include("inc_entete.php"); ?>
	<div class="haut_aligngauche">
		<p>
		<div class="titrepage">
			Suivis de location
		</div>
		<b class="module4ie"><a href="engine=logement.php?rue=<?php print(''.$_SESSION['autrelor'].''); ?>&num=<?php print(''.$_SESSION['autrelon'].''); ?>" class="module4"><img src="im_objets/icon_retour.gif" alt="Retour" />Retour</a></b>
</p>
	</div>
</div>
<div id="centre">
<p>

<?php
print('<strong>Adresse du logement en location :</strong> <i>'.$_SESSION['autrelon'].' '.$_SESSION['autrelor'].'</i>');
print('<table width="85%"  border="0" align="center"><tr><td>');
print('<p align="center"><img src="im_objets/'.$image.'" border="0"></p></td><td>');
if($resl==0)
	{
	print('<p align="center"><i>Il n\'y a pas de locataire.</i></p>');
	print('<p align="center"><strong>Prix :</strong> '.$prix.' Crédits / Jour</p>');
	print('</td></tr></table>');
	print('<p align="center"><a href="engine=desetlocation.php">Retirer ce logement de la location</a></p>');
	}
else
	{
	print('<p align="center"><strong>Locataire actuel :</strong> <a href="engine=contacter.php?cible='.$pseudol.'">'.$pseudol.'</a></p>');
	print('<p align="center"><strong>Prix :</strong> '.$prix.' Crédits / Jour<br>');
	print('</td></tr></table>');
	}
	
if(($cand=="oui") && ($resl==0))
	{
	if($resc>0)
		{
		print('<hr><p align="center"><strong>Voici les personnes qui ont fait une demande pour ce logement :</strong></p>');
		print('<p align="center">');
    	for($f=0;$f!=$resc;$f++)
			{
			$idc = mysql_result($reqc,$f,id);
			$pseudoc = mysql_result($reqc,$f,candidat);
			print('<a href="engine=consultdemlouer.php?id='.$idc.'">'.$pseudoc.'</a><br />');
			}
		print('</p>');
		}
	else
		{
		print('<hr><p align="center"><i>Il n\'y a aucune demande pour ce logement</i></p>');
		}
	}
?>


</p>
</div>
<?php if($chat=="oui") 	{ if ((date("H")>=21) || (date("H")<=7)) { include("inc_bas_n_c.php"); } else { include("inc_bas_j_c.php"); } } else {	if ((date("H")>=21) || (date("H")<=7)) { include("inc_bas_n.php"); } else { include("inc_bas_j.php"); }	} ?>
<?php include("inc_bas_de_page.php"); ?>
