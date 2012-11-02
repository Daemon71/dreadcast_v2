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
			Equiper
		</div>
		<b class="module4ie"><a href="engine=inventaire.php" class="module4"><img src="im_objets/icon_retour.gif" alt="Retour" />Retour</a></b>
</p>
	</div>
</div>
<div id="centre">
<p>

<img src="im_objets/loader.gif" alt="..." /><br /><br />
<?php 

$db = mysql_connect('localhost', 'CENSURE', 'CENSURE') or die("Erreur de connexion au serveur des messages.");
mysql_select_db('CENSURE',$db) or die("Erreur de connexion a la base de donnees des messages.");

$sql = 'SELECT * FROM principal_tbl WHERE id= "'.$_SESSION['id'].'"' ;
$req = mysql_query($sql);
$_SESSION['objet'] = mysql_result($req,0,objet); 
$_SESSION['action'] = mysql_result($req,0,action); 
$_SESSION['case1'] = mysql_result($req,0,case1); 
$_SESSION['case2'] = mysql_result($req,0,case2); 
$_SESSION['case3'] = mysql_result($req,0,case3); 
$_SESSION['case4'] = mysql_result($req,0,case4); 
$_SESSION['case5'] = mysql_result($req,0,case5); 
$_SESSION['case6'] = mysql_result($req,0,case6); 

$equip = str_replace("%20"," ",''.$_SERVER['QUERY_STRING'].'');

for($i=1; $i != 7 ; $i++)
	{
	if((strtolower($_SESSION['case'.$i.''])==strtolower($equip)) && ($dslinv!=1))
		{
		if($_SESSION['objet']!="Aucun")
			{
			if($_SESSION['action'] == "Recherche de cristaux") $temp = ', action="aucune"';
			$sql = 'UPDATE principal_tbl SET case'.$i.'="'.$_SESSION['objet'].'" , objet="'.$_SESSION['case'.$i.''].'" '.$temp.' WHERE id= "'.$_SESSION['id'].'"' ;
			$req = mysql_query($sql);
			}
		else
			{
			if($_SESSION['action'] == "Recherche de cristaux") $temp = ', action="aucune"';
			$sql = 'UPDATE principal_tbl SET case'.$i.'="Vide" , objet="'.$_SESSION['case'.$i.''].'" '.$temp.' WHERE id= "'.$_SESSION['id'].'"' ;
			$req = mysql_query($sql);
			}
		print('<meta http-equiv="refresh" content="0 ; url=http://v2.dreadcast.net/ingame/engine=inventaire.php"> ');
		$dslinv = 1;
		}
	}

if($dslinv!=1)
	{
	print('<strong><i>Vous ne possedez pas cet objet dans votre inventaire.</strong></i>');
	}

mysql_close($db);

?>


</p>
</div>
<?php if($chat=="oui") 	{ if ((date("H")>=21) || (date("H")<=7)) { include("inc_bas_n_c.php"); } else { include("inc_bas_j_c.php"); } } else {	if ((date("H")>=21) || (date("H")<=7)) { include("inc_bas_n.php"); } else { include("inc_bas_j.php"); }	} ?>
<?php include("inc_bas_de_page.php"); ?>
