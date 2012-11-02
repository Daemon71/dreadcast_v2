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

$sql = 'SELECT type FROM entreprises_tbl WHERE nom= "'.$_SESSION['entreprise'].'"' ;
$req = mysql_query($sql);
$_SESSION['domaine'] =  mysql_result($req,0,type); 

mysql_close($db);
?>
<?php if ((date("H")>=21) || (date("H")<=7)) { include("inc_haut_n.php"); } else { include("inc_haut_j.php");} ?>

<div id="haut">
	<?php include("inc_entete.php"); ?>
	<div class="haut_aligngauche">
		<p>
		<div class="titrepage">
			G&eacute;rer votre personnel
		</div>
		</p>
	</div>
</div>
<div id="centre">
<p>

<?php 

if($_SESSION['points']!=999) { print('<meta http-equiv="refresh" content="0 ; url=http://v2.dreadcast.net/ingame/engine.php"> '); exit(); }

$_SESSION['poste'] = ucfirst($_POST['np']);
$poste = $_POST['np'];
if($_SESSION['poste']=="")
	{
	print('Vous devez entrer un nom pour le poste.');
	$l = 1;
	}
for($i=0;$i!=strlen($poste);$i++)
	{
	$p = $poste{$i};
	if((preg_match("#[a-zA-Z0-9]#",$p)) || ($p==" "))
		{
		}
	else
		{
		echo "Vous ne pouvez pas utiliser de caractères spéciaux dans le nom d'un poste !";
		$l = 1;
		}
	} 

$_SESSION['tposte'] = $_SERVER['QUERY_STRING'];
$_SESSION['nbrep'] = $_POST['nbrep'];
if($_SESSION['nbrep']=="")
	{
	print('Vous devez entrer un nombre de postes pour le poste.');
	$l = 1;
	}
$_SESSION['salaire'] = $_POST['salaire'];
if($_SESSION['salaire']=="")
	{
	print('Vous devez entrer un salaire pour le poste.');
	$l = 1;
	}
$_SESSION['mincomp'] = $_POST['mincomp'];
$_SESSION['mintrav'] = $_POST['mintrav'];
$_SESSION['sinon'] = $_POST['sinon'];
$_SESSION['cand'] = $_POST['cand'];
$_SESSION['droit'] = $_POST['droit'];
$_SESSION['hs'] = $_POST['hs'];

if($l != 1)
	{
	$db = mysql_connect('localhost', 'CENSURE', 'CENSURE') or die("Erreur de connexion au serveur des messages.");
	mysql_select_db('CENSURE',$db) or die("Erreur de connexion a la base de donnees des messages.");
	
	$sql1 = 'SELECT bdd FROM `e_'.str_replace(" ","_",''.$_SESSION['entreprise'].'').'_tbl` WHERE type= "chef"' ;
	$req1 = mysql_query($sql1);
	
	if($_SESSION['droit']!="")
		{
		$sql = 'INSERT INTO `e_'.str_replace(" ","_",''.$_SESSION['entreprise'].'').'_tbl` (id,poste,type,salaire,bdd,nbrepostes,nbreactuel,competence,mincomp,candidature,mintrav,sinon,bonus) VALUES("","'.$_SESSION['poste'].'","'.$_SESSION['tposte'].'","'.$_SESSION['salaire'].'","'.$_SESSION['bdd'].'","'.$_SESSION['nbrep'].'","","'.$_SESSION['tposte'].'","'.$_SESSION['mincomp'].'","'.$_SESSION['cand'].'","'.$_SESSION['mintrav'].'","'.$_SESSION['sinon'].'","'.$_SESSION['hs'].'")';
		mysql_query($sql);
		}
	else
		{
		$sql = 'INSERT INTO `e_'.str_replace(" ","_",''.$_SESSION['entreprise'].'').'_tbl` (id,poste,type,salaire,bdd,nbrepostes,nbreactuel,competence,mincomp,candidature,mintrav,sinon,bonus) VALUES("","'.$_SESSION['poste'].'","'.$_SESSION['tposte'].'","'.$_SESSION['salaire'].'","","'.$_SESSION['nbrep'].'","","'.$_SESSION['tposte'].'","'.$_SESSION['mincomp'].'","'.$_SESSION['cand'].'","'.$_SESSION['mintrav'].'","'.$_SESSION['sinon'].'","'.$_SESSION['hs'].'")';
		mysql_query($sql);
		}
	
	mysql_close($db);
	
	print('<center><strong>Le poste est créé.</strong></center>');
	}
?>	        


</p>
</div>
<?php if($chat=="oui") 	{ if ((date("H")>=21) || (date("H")<=7)) { include("inc_bas_n_c.php"); } else { include("inc_bas_j_c.php"); } } else {	if ((date("H")>=21) || (date("H")<=7)) { include("inc_bas_n.php"); } else { include("inc_bas_j.php"); }	} ?>
<?php include("inc_bas_de_page.php"); ?>
