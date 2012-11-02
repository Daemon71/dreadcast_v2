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
			Vol &agrave; la tire
		</div>
		<b class="module4ie"><a href="engine.php" class="module4"><img src="im_objets/icon_retour.gif" alt="Retour" />Retour</a></b>
</p>
	</div>
</div>
<div id="centre">
<p>

<?php	

$db = mysql_connect('localhost', 'CENSURE', 'CENSURE') or die("Erreur de connexion au serveur.");
mysql_select_db('CENSURE',$db) or die("Erreur de connexion a la base de donnees.");

$sql = 'SELECT id,rue,num,fatigue FROM principal_tbl WHERE pseudo= "'.$_SESSION['pseudo'].'"' ;
$req = mysql_query($sql);
$_SESSION['id'] = mysql_result($req,0,id);
$_SESSION['lieu'] = mysql_result($req,0,rue);
$_SESSION['num'] = mysql_result($req,0,num);
$_SESSION['fatigue'] = mysql_result($req,0,fatigue);

if($_SESSION['num'] <= 0)
	{
	$num = 0;
	$lieu = "Rue";
	}
else
	{
	$num = $_SESSION['num'];
	$lieu = $_SESSION['lieu'];
	}
$sql = 'SELECT code FROM lieu_tbl WHERE rue= "'.$lieu.'" AND num= "'.$num.'"' ;
$req = mysql_query($sql);
$codounet = mysql_result($req,0,code);

if($_SESSION['code']!=$codounet)
	{
	mysql_close($db);
	print('<meta http-equiv="refresh" content="0 ; url=http://v2.dreadcast.net/ingame/engine.php"> ');
	exit();
	}

$cible = str_replace("%20"," ",''.$_GET['cible'].'');

$sql = 'SELECT id,rue,num,case1,case2,case3,case4,case5,case6 FROM principal_tbl WHERE pseudo= "'.$cible.'"' ;
$req = mysql_query($sql);
$idc = mysql_result($req,0,id);
$_SESSION['lieuc'] = mysql_result($req,0,rue);
$_SESSION['numc'] = mysql_result($req,0,num);
$_SESSION['case1c'] = mysql_result($req,0,case1);
$_SESSION['case2c'] = mysql_result($req,0,case2);
$_SESSION['case3c'] = mysql_result($req,0,case3);
$_SESSION['case4c'] = mysql_result($req,0,case4);
$_SESSION['case5c'] = mysql_result($req,0,case5);
$_SESSION['case6c'] = mysql_result($req,0,case6);

if($_SESSION['v'.$idc.'']!=1 || $_SESSION['distance']!=1)
	{
	print('<meta http-equiv="refresh" content="0 ; url=http://v2.dreadcast.net/ingame/engine.php"> ');
	exit();
	}

if($_SESSION['fatigue']<5)
	{
	print('<br />Il est impossible de voler <strong>'.$cible.'</strong> car vous êtes trop fatigué.');
	}
if(!estVisible($cible,25))
	{
	print('<p align="center"><strong>Il est impossible de voler <i>'.$cible.'</i> car il n\'est pas au m&ecirc;me endroit que vous.</strong></p>');
	}
else
	{
	$_SESSION['fatigue'] = $_SESSION['fatigue'] - 5;
	$sql = 'UPDATE principal_tbl SET fatigue= "'.$_SESSION['fatigue'].'" WHERE id= "'.$_SESSION['id'].'"' ;
	$req = mysql_query($sql);
	$sql = 'SELECT case'.$_GET['case'].' FROM principal_tbl WHERE id= "'.$_SESSION['idc'].'"' ;
	$req = mysql_query($sql);
	$ouin = 'case'.$_GET['case'].'';
	$objet = mysql_result($req,0,$ouin); 
	$sql = 'SELECT type FROM objets_tbl WHERE nom= "'.$objet.'"' ;
	$req = mysql_query($sql);
	$type = mysql_result($req,0,type); 
	if($type!="imp")
		{
		$trans = 0;
		for($i=1; $i != 7 ; $i++) 
			{
			if(($_SESSION['case'.$i.'']=="Vide") && ($trans==0))
				{
				$sql = 'UPDATE principal_tbl SET case'.$i.'="'.$objet.'" WHERE id="'.$_SESSION['id'].'"';
				$req = mysql_query($sql);
				$sql = 'UPDATE principal_tbl SET case'.$_GET['case'].'="Vide" WHERE id="'.$_SESSION['idc'].'"';
				$req = mysql_query($sql);
				print("<p align='center'><strong>Objet acquis.</strong></p>");
				$_SESSION['v'.$idc.''] = "";
				$trans = 1;
				$sqls = 'INSERT INTO messages_tbl(id,auteur,cible,message,objet,moment,nouveau) VALUES("","Dreadcast","'.$cible.'","Vous semblez avoir perdu un objet : <strong>'.$objet.'</strong>.<br />Vous ne savez pas ce qui a bien pu se passer...<br /><br />Quelqu\'un vous l\'aurai volé ?","Tiens ..!?","'.time().'","oui")';
				$reqs = mysql_query($sqls);
				enregistre($_SESSION['pseudo'],'acc_vols_donnes','+1');
				enregistre($cible,'acc_vols_recus','+1');
				enregistre_evt($cible,'Vol objet',$objet);
				}
			}
		
		if($trans!=1)
			{
			print("<p align='center'><strong>Il n'y a pas d'emplacement vide dans votre inventaire personnel.</strong></p>");
			}
		}
	}

mysql_close($db);

?>

</p>
</div>
<?php if($chat=="oui") 	{ if ((date("H")>=21) || (date("H")<=7)) { include("inc_bas_n_c.php"); } else { include("inc_bas_j_c.php"); } } else {	if ((date("H")>=21) || (date("H")<=7)) { include("inc_bas_n.php"); } else { include("inc_bas_j.php"); }	} ?>
<?php include("inc_bas_de_page.php"); ?>
