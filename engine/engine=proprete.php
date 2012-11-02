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

$sql = 'SELECT num,rue FROM entreprises_tbl WHERE nom= "Services techniques de la ville"' ;
$req = mysql_query($sql);
$rueent = mysql_result($req,0,rue); 
$nument = mysql_result($req,0,num); 

$sql = 'SELECT valeur FROM donnees_tbl WHERE objet= "etat de la ville"' ;
$req = mysql_query($sql);
$etat = mysql_result($req,0,valeur);

$sql = 'SELECT energie FROM colonies_planetes_tbl WHERE id= "0"' ;
$req = mysql_query($sql);
$energie = mysql_result($req,0,energie);

mysql_close($db);
?>
<?php if ((date("H")>=21) || (date("H")<=7)) { include("inc_haut_n.php"); } else { include("inc_haut_j.php");} ?>

<div id="haut">
	<?php include("inc_entete.php"); ?>
	<div class="haut_aligngauche">
		<p>
		<div class="titrepage">
			Etat de la ville
		</div>
		<b class="module4ie"><a href="engine.php" class="module4"><img src="im_objets/icon_retour.gif" alt="Retour" />Retour</a></b>
</p>
	</div>
</div>
<div id="centre">
<p>

<?php 

if((ucwords($_SESSION['rue'])!=ucwords($rueent)) || ($_SESSION['num']!=$nument))
	{
	print('<p align="center"><strong>Vous devez &ecirc;tre sur place pour pouvoir acc&eacute;der &agrave; cette rubrique.</strong></p>'); 
	$l = 1;
	}

if($l!=1)
	{
	print('<hr><p align="center"><strong>Etat actuel de la cité :</strong> '.$etat.'<br />');
	print('<strong>Niveau d\'énergie actuel :</strong> '.$energie.' UI</p><hr>');
	if($etat=="Très propre")
		{
		print('<p align="center">La ville est magnifique. <br />Toute sa beauté ressort à chaque coin de rue.<br /><br />Se reposer dans la rue remonte 3 points de forme par heure.</p>');
		}
	elseif($etat=="Propre")
		{
		print('<p align="center">La ville est assez propre. <br />Il n\'y a rien à redire sur le travail des agents de propreté.<br /><br />Se reposer dans la rue remonte 2 points de forme par heure.</p>');
		}
	elseif($etat=="Vivable")
		{
		print('<p align="center">L\'état de la ville est médiocre. <br />Il est cependant agréable de se promener.<br /><br />Se reposer dans la rue remonte 1 points de forme par heure.</p>');
		}
	elseif($etat=="Sale")
		{
		print('<p align="center">La ville est sale. <br />Il n\'est pas agréable de se promener.<br /><br />Se reposer dans la rue remonte 1 points de forme par heure.</p>');
		}
	elseif($etat=="Très sale")
		{
		print('<p align="center">La ville est invivable. <br />Il n\'est presque plus possible de mettre un pied devant l\'autre.<br /><br />Se reposer dans la rue ne remonte pas de points de forme.</p>');
		}
	}

?>

</p>
</div>
<?php if($chat=="oui") 	{ if ((date("H")>=21) || (date("H")<=7)) { include("inc_bas_n_c.php"); } else { include("inc_bas_j_c.php"); } } else {	if ((date("H")>=21) || (date("H")<=7)) { include("inc_bas_n.php"); } else { include("inc_bas_j.php"); }	} ?>
<?php include("inc_bas_de_page.php"); ?>
