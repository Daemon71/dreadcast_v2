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
if(($_SESSION['objet']!="Neuvopack1") && ($_SESSION['objet']!="Neuvopack2") && ($_SESSION['objet']!="Neuvopack3") && ($_SESSION['objet']!="Neuvopack4") && ($_SESSION['objet']!="Neuvopack5") && ($_SESSION['objet']!="Neuvopack6") && ($_SESSION['objet']!="Neuvopack7") && ($_SESSION['objet']!="Neuvopack8") && ($_SESSION['objet']!="Neuvopack9") && ($_SESSION['objet']!="Neuvopack10"))
	{
	print('<meta http-equiv="refresh" content="0 ; url=http://v2.dreadcast.net/ingame/engine.php"> ');
	exit();
	}

if ((date("H")>=21) || (date("H")<=7)) { include("inc_haut_n.php"); } else { include("inc_haut_j.php");}

$db = mysql_connect('localhost', 'CENSURE', 'CENSURE') or die("Erreur de connexion au serveur des messages.");
mysql_select_db('CENSURE',$db) or die("Erreur de connexion a la base de donnees des messages.");

$sql = 'SELECT num,rue FROM entreprises_tbl WHERE nom= "Services techniques de la ville"' ;
$req = mysql_query($sql);
$rueent = mysql_result($req,0,rue); 
$nument = mysql_result($req,0,num); 

$sql = 'SELECT energie FROM colonies_planetes_tbl WHERE id= "0"' ;
$req = mysql_query($sql);
$valeur = mysql_result($req,0,energie);

if($valeur<=0)
	{
	$prix = 30;
	}
elseif($valeur>=10000)
	{
	$prix = 5;
	}
else
	{
	$prix = ceil( 30 - (0.0025 * $valeur) );
	}

$sql = 'SELECT objet,credits FROM principal_tbl WHERE id= "'.$_SESSION['id'].'"' ;
$req = mysql_query($sql);
$_SESSION['credits'] = mysql_result($req,0,credits); 
$_SESSION['objet'] = mysql_result($req,0,objet); 

if($_GET['ok']==1)
	{
	$niveaucristaux = substr($_SESSION['objet'],9,10);
	$gain = $niveaucristaux * $prix;
	$_SESSION['credits'] = $_SESSION['credits'] + $gain;
	$_SESSION['objet'] = "Neuvopack";
	$sql = 'UPDATE principal_tbl SET credits= "'.$_SESSION['credits'].'" , objet= "'.$_SESSION['objet'].'" WHERE id= "'.$_SESSION['id'].'"' ;
	$req = mysql_query($sql);
	$valeur = $valeur + $niveaucristaux;
	$sql = 'UPDATE colonies_planetes_tbl SET energie= "'.$valeur.'" WHERE id= "0"' ;
	$req = mysql_query($sql);
	$sql = 'INSERT INTO achats_tbl(acheteur,vendeur,objet,prix,moment) VALUES("'.$_SESSION['pseudo'].'","Services techniques de la ville","'.$niveaucristaux.' crist'.(($niveaucristaux==1)?'al':'aux').'","'.$gain.'","'.time().'")' ;
	$req = mysql_query($sql);
	$sql = 'INSERT INTO transferts_tbl(donneur,receveur,time,operation,valeur) VALUES("Dreadcast","'.$_SESSION['pseudo'].'","'.time().'","Revente cristaux","'.$gain.'")';
	$req = mysql_query($sql);
	enregistre($_SESSION['pseudo'],'cristaux',"+1");
	}

mysql_close($db);
?>

<div id="haut">
	<?php include("inc_entete.php"); ?>
	<div class="haut_aligngauche">
		<p>
		<div class="titrepage">
			Vendre de l'&eacute;nergie
		</div>
		<b class="module4ie"><a href="engine.php" class="module4"><img src="im_objets/icon_retour.gif" alt="Retour" />Retour</a></b>
</p>
	</div>
</div>
<div id="centre_imperium">

<p id="location">Services techniques de la ville</p>

<p id="textelse">
<?php 

if((ucwords($_SESSION['rue'])!=ucwords($rueent)) || ($_SESSION['num']!=$nument))
	{
	print('<strong>Vous devez &ecirc;tre sur place pour pouvoir acc&eacute;der &agrave; cette rubrique.</strong>'); 
	$l = 1;
	}

if($l!=1)
	{
	if($_GET['ok']==1)
		{
		print('Vous venez de vendre le contenu de votre neuvopack à la ville et elle vous en remercie !<br />Vous serez heureux d\'apprendre que cette énergie va servir à alimenter un foyer, un local d\'entreprise et bien d\'autres chose encore !<br /><br /><b>Vous vendez votre contenu de votre neuvopack ('.$niveaucristaux.' crista'.(($niveaucristaux==1)?'l':'ux').') pour '.$gain.' Crédits.</b>');
		}
	else
		{
		print('<strong>Niveau d\'énergie actuel de la cité :</strong> '.$valeur.'<br />
		<strong>Revenu approximatif :</strong> '.$prix.' Crédits/Cristal<br /><br /><br /><br />
		Vous pouvez vider votre neuvopack dans les réserves de la ville et encaisser vos Crédits dès maintenant.<br /><br />
		<a href="engine=energie.php?ok=1">Vider votre neuvopack dans les cuves de la ville</a>');
		}
	}

?>

</p>
</div>
<?php if($chat=="oui") 	{ if ((date("H")>=21) || (date("H")<=7)) { include("inc_bas_n_c.php"); } else { include("inc_bas_j_c.php"); } } else {	if ((date("H")>=21) || (date("H")<=7)) { include("inc_bas_n.php"); } else { include("inc_bas_j.php"); }	} ?>
<?php include("inc_bas_de_page.php"); ?>
