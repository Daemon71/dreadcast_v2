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
			Achat d'un chiffre de plus
		</div>
		<b class="module4ie"><a href="engine=logement.php" class="module4"><img src="im_objets/icon_retour.gif" alt="Retour" />Retour</a></b>
</p>
	</div>
</div>
<div id="centre">
<p>

<?php  
$db = mysql_connect('localhost', 'CENSURE', 'CENSURE') or die("Erreur de connexion au serveur des messages.");
mysql_select_db('CENSURE',$db) or die("Erreur de connexion a la base de donnees des messages.");

$sql = 'SELECT credits FROM principal_tbl WHERE id= "'.$_SESSION['id'].'"' ;
$req = mysql_query($sql);
$_SESSION['credits'] = mysql_result($req,0,credits); 

$sql = 'SELECT code FROM lieu_tbl WHERE rue= "'.$_SESSION['autrelor'].'" AND num= "'.$_SESSION['autrelon'].'"' ;
$req = mysql_query($sql);
$digic = mysql_result($req,0,code); 

if(strlen($digic)<6)
	{
	if($_SESSION['credits']>179)
		{
		$_SESSION['credits'] = $_SESSION['credits'] - 180;
		$sql = 'UPDATE principal_tbl SET credits="'.$_SESSION['credits'].'" WHERE id= "'.$_SESSION['id'].'"' ;
		$req = mysql_query($sql);
		$sql = 'UPDATE lieu_tbl SET code="'.$digic.'0" WHERE rue= "'.$_SESSION['autrelor'].'" AND num= "'.$_SESSION['autrelon'].'"' ;
		$req = mysql_query($sql);
		$nbrec = strlen($digic) + 1;
		print('<strong>Votre digicode contient maintenant '.$nbrec.' chiffres. Il devient : '.$digic.'0</strong>');
		mysql_close($db);
		}
	else
		{
		print('<strong>Vous n\'avez pas assez d\'argent.</strong>');
		}
	}
	
?>

</p>
</div>
<?php if($chat=="oui") 	{ if ((date("H")>=21) || (date("H")<=7)) { include("inc_bas_n_c.php"); } else { include("inc_bas_j_c.php"); } } else {	if ((date("H")>=21) || (date("H")<=7)) { include("inc_bas_n.php"); } else { include("inc_bas_j.php"); }	} ?>
<?php include("inc_bas_de_page.php"); ?>
