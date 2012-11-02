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
if(ereg($_SERVER['QUERY_STRING'],$_SESSION['personnes']))
	{
	}
else
	{
	$_SESSION['personnes'] = "";
	print('<meta http-equiv="refresh" content="0 ; url=http://v2.dreadcast.net/ingame/engine.php"> ');
	exit();
	}

$db = mysql_connect('localhost', 'CENSURE', 'CENSURE') or die("Erreur de connexion au serveur des messages.");
mysql_select_db('CENSURE',$db) or die("Erreur de connexion a la base de donnees des messages.");

$sql = 'SELECT code FROM lieu_tbl WHERE rue= "'.$_SESSION['lieu'].'" AND num= "'.$_SESSION['num'].'"' ;
$req = mysql_query($sql);
$codounet = mysql_result($req,0,code);

if($_SESSION['code']!=$codounet)
	{
	mysql_close($db);
	print('<meta http-equiv="refresh" content="0 ; url=http://v2.dreadcast.net/ingame/engine.php"> ');
	exit();
	}

mysql_close($db);

?>
<?php if ((date("H")>=21) || (date("H")<=7)) { include("inc_haut_n.php"); } else { include("inc_haut_j.php");} ?>

<div id="haut">
	<?php include("inc_entete.php"); ?>
	<div class="haut_aligngauche">
		<p>
		<div class="titrepage">
			Désactivation Passe
		</div>
		<b class="module4ie"><a href="engine=cible.php?<?php print($_SERVER['QUERY_STRING']); ?>" class="module4"><img src="im_objets/icon_retour.gif" alt="Retour" />Retour</a></b>
</p>
	</div>
</div>
<div id="centre">
<p>

<?php	

$db = mysql_connect('localhost', 'CENSURE', 'CENSURE') or die("Erreur de connexion au serveur.");
mysql_select_db('CENSURE',$db) or die("Erreur de connexion a la base de donnees.");

$sql = 'SELECT entreprise,type FROM principal_tbl WHERE id= "'.$_SESSION['id'].'"' ;
$req = mysql_query($sql);
$_SESSION['entreprise'] = mysql_result($req,0,entreprise);
$_SESSION['type'] = mysql_result($req,0,type);

$cible = str_replace("%20"," ",''.$_SERVER['QUERY_STRING'].'');

$sql = 'SELECT case1,case2,case3,case4,case5,case6 FROM principal_tbl WHERE pseudo= "'.$cible.'"' ;
$req = mysql_query($sql);
$casec[1] = mysql_result($req,0,case1);
$casec[2] = mysql_result($req,0,case2);
$casec[3] = mysql_result($req,0,case3);
$casec[4] = mysql_result($req,0,case4);
$casec[5] = mysql_result($req,0,case5);
$casec[6] = mysql_result($req,0,case6);

if($_SESSION['entreprise']=="DI2RCO" && $_SESSION['type']=="Directeur du DI2RCO")
	{
	for($i=1;$i!=7;$i++)
		{
		if($casec[$i]=="Passe DI2RCO")
			{
			$sql = 'UPDATE principal_tbl SET case'.$i.'= "Vide" WHERE pseudo= "'.$cible.'"';
			$req = mysql_query($sql);
			$l = 1;
			}
		}
	if($l!=1)
		{
		print('Cette personne ne possède pas de Passe DI2RCO.');
		}
	else
		{
		$sql = 'INSERT INTO messages_tbl(id,auteur,cible,message,objet,moment,nouveau) VALUES("","Dreadcast","'.$cible.'","Un agent du DI2RCO vient de désactiver votre Passe DI2RCO.","Désactivation Passe","'.time().'","oui")' ;
		$req = mysql_query($sql);
		print('Le Passe DI2RCO a été correctement désactivé.');
		}
	}
else
	{
	print('Seul le Directeur du DI2RCO peut ordonner la désactivation d\'un Passe DI2RCO.');
	}

mysql_close();
?>

</p>
</div>
<?php if($chat=="oui") 	{ if ((date("H")>=21) || (date("H")<=7)) { include("inc_bas_n_c.php"); } else { include("inc_bas_j_c.php"); } } else {	if ((date("H")>=21) || (date("H")<=7)) { include("inc_bas_n.php"); } else { include("inc_bas_j.php"); }	} ?>
<?php include("inc_bas_de_page.php"); ?>
