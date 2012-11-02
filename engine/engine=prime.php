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
			G&eacute;rer votre personnel
		</div>
		<b class="module4ie"><a href="engine=personnel.php" class="module4"><img src="im_objets/icon_retour.gif" alt="Retour" />Retour</a></b>
</p>
	</div>
</div>
<div id="centre">
<p>

<img src="im_objets/loader.gif" alt="..." /><br /><br />
<?php 

$db = mysql_connect('localhost', 'CENSURE', 'CENSURE') or die("Erreur de connexion au serveur des messages.");
mysql_select_db('CENSURE',$db) or die("Erreur de connexion a la base de donnees des messages.");

$idi = $_GET['id1'];
$idt = $_GET['id2'];

$sql = 'SELECT type,entreprise,points FROM principal_tbl WHERE id= "'.$_SESSION['id'].'"' ;
$req = mysql_query($sql);
$_SESSION['poste'] = mysql_result($req,0,type); 
$_SESSION['entreprise'] = mysql_result($req,0,entreprise); 
$_SESSION['points'] = mysql_result($req,0,points); 

$sql = 'SELECT budget FROM entreprises_tbl WHERE nom= "'.$_SESSION['entreprise'].'"' ;
$req = mysql_query($sql);
$_SESSION['budget'] = mysql_result($req,0,budget); 

if($_SESSION['points']!=999) { print('<meta http-equiv="refresh" content="0 ; url=http://v2.dreadcast.net/ingame/engine.php"> '); exit(); }

if(($_SESSION['budget']>$_POST['prime'.$idi.''.$idt.'']) && ($_POST['prime'.$idi.''.$idt.'']>0))
	{
	$sql = 'SELECT credits FROM principal_tbl WHERE id= "'.$idt.'"' ;
	$req = mysql_query($sql);
	$cred = mysql_result($req,0,credits); 
	$cred = $cred + $_POST['prime'.$idi.''.$idt.''];
	$sql = 'UPDATE principal_tbl SET credits="'.$cred.'" WHERE id= "'.$idt.'"' ;
	$req = mysql_query($sql);
	$nouveau = $_SESSION['budget'] - $_POST['prime'.$idi.''.$idt.''];
	$sql = 'UPDATE entreprises_tbl SET budget="'.$nouveau.'" WHERE nom= "'.$_SESSION['entreprise'].'"' ;
	$req = mysql_query($sql);
	$sql = 'SELECT pseudo FROM principal_tbl WHERE id= "'.$idt.'"' ;
	$req = mysql_query($sql);
	$ps = mysql_result($req,0,pseudo); 
	$sql = 'INSERT INTO messages_tbl(id,auteur,cible,message,objet,moment,nouveau) VALUES("","'.$_SESSION['entreprise'].'","'.$ps.'","Une prime de <i>'.$_POST['prime'.$idi.''.$idt.''].' Cr&eacute;dits</i> vous est accord&eacute; par l`entreprise.","Vous avez une prime","'.time().'","oui")' ;
	$req = mysql_query($sql);
	print('<meta http-equiv="refresh" content="0 ; url=http://v2.dreadcast.net/ingame/engine=personnel.php">');
	}
else
	{
	print('<i>L\'entreprise n\'a pas un capital n&eacute;c&eacute;ssaire pour accorder cette prime.</i>');
	}

$sql = 'SELECT credits FROM principal_tbl WHERE id= "'.$_SESSION['id'].'"' ;
$req = mysql_query($sql);
$_SESSION['credits'] = mysql_result($req,0,credits); 

mysql_close($db);


?>

</p>
</div>
<?php if($chat=="oui") 	{ if ((date("H")>=21) || (date("H")<=7)) { include("inc_bas_n_c.php"); } else { include("inc_bas_j_c.php"); } } else {	if ((date("H")>=21) || (date("H")<=7)) { include("inc_bas_n.php"); } else { include("inc_bas_j.php"); }	} ?>
<?php include("inc_bas_de_page.php"); ?>
