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
?>
<?php if ((date("H")>=21) || (date("H")<=7)) { include("inc_haut_n.php"); } else { include("inc_haut_j.php");} ?>

<div id="haut">
	<?php include("inc_entete.php"); ?>
	<div class="haut_aligngauche">
		<p>
		<div class="titrepage">
			Porter plainte
		</div>
		<b class="module4ie"><a href="engine.php" class="module4"><img src="im_objets/icon_retour.gif" alt="Retour" />Retour</a></b>
	</p>
	</div>
</div>
<div id="centre_imperium">

<p id="location">Police de la cit&eacute;</p>

<p id="textelse">

<?php	

$db = mysql_connect('localhost', 'CENSURE', 'CENSURE') or die("Erreur de connexion au serveur.");
mysql_select_db('CENSURE',$db) or die("Erreur de connexion a la base de donnees.");

$sql = 'SELECT * FROM principal_tbl WHERE pseudo= "'.$_SESSION['pseudo'].'"' ;
$req = mysql_query($sql);
$_SESSION['id'] = mysql_result($req,0,id);
$_SESSION['lieu'] = mysql_result($req,0,rue);
$_SESSION['num'] = mysql_result($req,0,num);
$_SESSION['action'] = mysql_result($req,0,action);
$_SESSION['lieu'] = strtolower($_SESSION['lieu']);
$_SESSION['lieu'] = ucwords($_SESSION['lieu']);
$_SESSION['objet'] = mysql_result($req,0,objet);
$_SESSION['sexe'] = mysql_result($req,0,sexe);
$_SESSION['age'] = mysql_result($req,0,age);
$_SESSION['taille'] = mysql_result($req,0,taille);
$_SESSION['resistance'] = mysql_result($req,0,resistance);
$_SESSION['alcool'] = mysql_result($req,0,alcool);

if(alcootest($_SESSION['pseudo'], $_SESSION['alcool'], $_SESSION['sexe'], $_SESSION['age'], $_SESSION['taille'], $_SESSION['resistance'])<1.5)
	{
	$sql = 'SELECT nom,type FROM entreprises_tbl WHERE rue= "'.$_SESSION['lieu'].'" AND num= "'.$_SESSION['num'].'"' ;
	$req = mysql_query($sql);
	$type = mysql_result($req,0,type);
	$noment = mysql_result($req,0,nom);
	
	if($type!="police")
		{
		print('<meta http-equiv="refresh" content="0 ; url=http://v2.dreadcast.net/ingame/engine.php"> ');
		exit();
		}
	
	$sql = 'INSERT INTO pleintes_tbl(id,pseudo,msg,moment,joindre) VALUES("","'.$_SESSION['pseudo'].'","'.str_replace("\n","<br />",htmlentities(alcohol_speak($_POST['pleinte'],alcootest($_SESSION['pseudo'], $_SESSION['alcool'], $_SESSION['sexe'], $_SESSION['age'], $_SESSION['taille'], $_SESSION['resistance'])*10))).'","'.time().'","'.$_POST['msg'].'")' ;
	$req = mysql_query($sql);
	
	print('Votre plainte a bien été prise en compte et sera traitée dans les plus brefs délais.<br />Vous serez tenu informé par un agent de Police de l\'avancée de votre dossier.');
	}
else
	{
	print('Vous êtes trop saoul pour pouvoir porter plainte !');
	}


mysql_close($db);

?>

</p>
</div>
<?php if($chat=="oui") 	{ if ((date("H")>=21) || (date("H")<=7)) { include("inc_bas_n_c.php"); } else { include("inc_bas_j_c.php"); } } else {	if ((date("H")>=21) || (date("H")<=7)) { include("inc_bas_n.php"); } else { include("inc_bas_j.php"); }	} ?>
<?php include("inc_bas_de_page.php"); ?>
