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

$sql = 'SELECT credits FROM principal_tbl WHERE id= "'.$_SESSION['id'].'"' ;
$req = mysql_query($sql);
$_SESSION['credits'] = mysql_result($req,0,credits);

$sql = 'SELECT nom,type,budget,benefices FROM entreprises_tbl WHERE rue= "'.$_SESSION['lieu'].'" AND num= "'.$_SESSION['num'].'"' ;
$req = mysql_query($sql);
$type = mysql_result($req,0,type);
$noment = mysql_result($req,0,nom);
$budget = mysql_result($req,0,budget);
$benefices = mysql_result($req,0,benefices);

if($type!="banque")
	{
	print('<meta http-equiv="refresh" content="0 ; url=http://v2.dreadcast.net/ingame/engine.php"> ');
	exit();
	}

if($_SESSION['credits']>50)
	{
	$ok = 1;
	$res = 1;
	while($res!=0)
		{
		$code = rand(10000000,99999999);
		$sql = 'SELECT id FROM comptes_tbl WHERE code= "'.$code.'"' ;
		$req = mysql_query($sql);
		$res = mysql_num_rows($req);
		}
	$sql = 'INSERT INTO messages_tbl(id,auteur,cible,message,objet,moment) VALUES("","Banque","'.$_SESSION['pseudo'].'","Vous venez d\'ouvrir un compte bancaire.<br />Le numéro de compte est le: <strong>'.$code.'</strong>","Ouverture de compte","'.time().'")' ;
	$req = mysql_query($sql);
	$sql = 'INSERT INTO comptes_tbl(id,code,credits) VALUES("","'.$code.'","0")' ;
	$req = mysql_query($sql);
	$sql = 'INSERT INTO comptes_acces_tbl(pseudo,compte,time,operation) VALUES("'.$_SESSION['pseudo'].'","'.$code.'","'.time().'","Creation")' ;
	$req = mysql_query($sql);
	$_SESSION['credits'] = $_SESSION['credits'] - 50;
	$sql = 'UPDATE principal_tbl SET credits= "'.$_SESSION['credits'].'" WHERE id= "'.$_SESSION['id'].'"' ;
	$req = mysql_query($sql);
	$budget = $budget + 50;
	$benefices = $benefices + 50;
	$sql = 'UPDATE entreprises_tbl SET budget= "'.$budget.'" , benefices= "'.$benefices.'" WHERE nom= "'.$noment.'"' ;
	$req = mysql_query($sql);
	}
else
	{
	$ok = 0;
	}

mysql_close($db);
?>
<?php if ((date("H")>=21) || (date("H")<=7)) { include("inc_haut_n.php"); } else { include("inc_haut_j.php");} ?>

<div id="haut">
	<?php include("inc_entete.php"); ?>
	<div class="haut_aligngauche">
		<p>
		<div class="titrepage">
			Banque
		</div>
		<b class="module4ie"><a href="engine.php" class="module4"><img src="im_objets/icon_retour.gif" alt="Retour" />Retour</a></b>
		</p>
	</div>
</div>
<div id="centre_banque">

	<p id="location">Nouveau compte chez <span><?php print($noment); ?></span></p>
	<br /><br /><br />

<?php 

if($ok=1)
	{
	print('<p id="textelse">La banque <b>'.$noment.'</b> est heureuse de vous accueillir parmi ses clients !<br /><br />Vous pourrez consulter votre compte depuis toutes les banques &agrave; l\'aide de votre code d\'identification.<br />Notez bien ce code, et ne le donnez &agrave; personne !<br />Le personnel bancaire ne vous le demandera jamais.<br /><br />VOTRE CODE CLIENT : <b>'.$code.'</b><br /><a href="engine=compte.php?'.$code.'">Acc&eacute;der &agrave; votre compte</a></p>');
	}
else
	{
	print('<p id="textelse">Vous n\'avez pas assez de Cr&eacute;dits pour ouvrir un compte.</p>');
	}

?>

</div>
<?php if($chat=="oui") 	{ if ((date("H")>=21) || (date("H")<=7)) { include("inc_bas_n_c.php"); } else { include("inc_bas_j_c.php"); } } else {	if ((date("H")>=21) || (date("H")<=7)) { include("inc_bas_n.php"); } else { include("inc_bas_j.php"); }	} ?>
<?php include("inc_bas_de_page.php"); ?>
