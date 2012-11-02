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
			Libérer
		</div>
		<b class="module4ie"><a href="engine=liste.php" class="module4"><img src="im_objets/icon_retour.gif" alt="Retour" />Retour</a></b>
</p>
	</div>
</div>
<div id="centre">
<p>

<?php	

$db = mysql_connect('localhost', 'CENSURE', 'CENSURE') or die("Erreur de connexion au serveur.");
mysql_select_db('CENSURE',$db) or die("Erreur de connexion a la base de donnees.");

$sql = 'SELECT id,rue,num FROM principal_tbl WHERE pseudo= "'.$_SESSION['pseudo'].'"' ;
$req = mysql_query($sql);
$_SESSION['id'] = mysql_result($req,0,id);
$_SESSION['lieu'] = mysql_result($req,0,rue);
$_SESSION['num'] = mysql_result($req,0,num);

$sql = 'SELECT entreprise,type FROM principal_tbl WHERE id= "'.$_SESSION['id'].'"' ;
$req = mysql_query($sql);
$_SESSION['entreprise'] = mysql_result($req,0,entreprise);
$_SESSION['poste'] = mysql_result($req,0,type);

$sql = 'SELECT bdd FROM `e_'.str_replace(" ","_",''.$_SESSION['entreprise'].'').'_tbl` WHERE poste= "'.$_SESSION['poste'].'"' ;
$req = mysql_query($sql);
$_SESSION['bdd'] = mysql_result($req,0,bdd);

$cible = str_replace("%20"," ",''.$_SERVER['QUERY_STRING'].'');

$sql = 'SELECT id,rue,num,action,alim FROM principal_tbl WHERE pseudo= "'.$cible.'"' ;
$req = mysql_query($sql);
$_SESSION['idc'] = mysql_result($req,0,id);
$_SESSION['lieuc'] = mysql_result($req,0,rue);
$_SESSION['numc'] = mysql_result($req,0,num);
$_SESSION['actionc'] = mysql_result($req,0,action);
$_SESSION['alimc'] = mysql_result($req,0,alim);

if((($_SESSION['entreprise']!="Police") && ($_SESSION['entreprise']!="DI2RCO")) || ($_SESSION['bdd']==""))
	{
	print('<p align="center"><strong>Il est impossible de libérer <i>'.$cible.'</i> car vous n\'êtes pas dans les registres de la police.</strong></p>');
	$i = 1;
	}

if((ucwords($_SESSION['lieuc'])==ucwords($ruec)) && (ucwords($_SESSION['numc'])==ucwords($nuc)))
	{
	$reaction = "Accepter";
	}

if(($_SESSION['lieu']!=$_SESSION['lieuc']) || ($_SESSION['num']!=$_SESSION['numc']))
	{
	print('<p align="center"><strong>Il est impossible de libérer <i>'.$cible.'</i> car il n\'est pas dans la même prison que vous.</strong></p>');
	exit();
	}
elseif($i!=1)
	{
	$sql = 'UPDATE principal_tbl SET action= "aucune" , alim="7" WHERE id= "'.$_SESSION['idc'].'"';
	$req = mysql_query($sql);
	$sql = 'INSERT INTO messages_tbl(id,auteur,cible,message,objet,moment) VALUES("","Prison","'.$cible.'","L\'officier de police '.$_SESSION['pseudo'].' vous à libéré de prison.<br>Il vous restait '.$_SESSION['alimc'].' jours d\'incarceration.","Vous êtes libéré !","'.time().'")' ;
	$req = mysql_query($sql);
	$sql = 'INSERT INTO casiers_tbl(id,pseudo,datea,raison,policier) VALUES("","'.$cible.'","'.time().'","Libéré de prison par '.$_SESSION['pseudo'].'.<br />Il restait '.$_SESSION['alimc'].' jour(s) d\'incarceration.","'.$_SESSION['pseudo'].'") ';
	$req = mysql_query($sql);
	print('Vous avez libéré <strong>'.$cible.'</strong> de prison.');
	enregistre_evt($cible,'Libération de prison',$_SESSION['alimc'].' jours restant');
	}

mysql_close($db);

?>

</p>
</div>
<?php if($chat=="oui") 	{ if ((date("H")>=21) || (date("H")<=7)) { include("inc_bas_n_c.php"); } else { include("inc_bas_j_c.php"); } } else {	if ((date("H")>=21) || (date("H")<=7)) { include("inc_bas_n.php"); } else { include("inc_bas_j.php"); }	} ?>
<?php include("inc_bas_de_page.php"); ?>
