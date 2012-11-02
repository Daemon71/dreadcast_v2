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
			Logo d'entreprise
		</div>
		<b class="module4ie"><a href="engine=gestion.php" class="module4"><img src="im_objets/icon_retour.gif" alt="Retour" />Retour</a></b>
</p>
	</div>
</div>
<div id="centre">
<p>

<?

$db = mysql_connect('localhost', 'CENSURE', 'CENSURE') or die("Erreur de connexion au serveur des messages.");
mysql_select_db('CENSURE',$db) or die("Erreur de connexion a la base de donnees des messages.");

$sql = 'SELECT type,entreprise,points FROM principal_tbl WHERE id= "'.$_SESSION['id'].'"' ;
$req = mysql_query($sql);
$_SESSION['poste'] = mysql_result($req,0,type); 
$_SESSION['entreprise'] = mysql_result($req,0,entreprise); 
$_SESSION['points'] = mysql_result($req,0,points); 

$sql = 'SELECT type FROM entreprises_tbl WHERE nom= "'.$_SESSION['entreprise'].'"' ;
$req = mysql_query($sql);
$_SESSION['typeent'] = mysql_result($req,0,type); 

if($_SESSION['points']!=999) { print('<meta http-equiv="refresh" content="0 ; url=http://v2.dreadcast.net/ingame/engine.php"> '); exit(); }

$idi = $_GET['id'];

if($idi!="")
	{
	$sql = 'SELECT type,url FROM logo_tbl WHERE id= "'.$idi.'"' ;
	$req = mysql_query($sql);
	$res = mysql_num_rows($req);
	
	if(($res>0) && ($idi!="logo"))
		{
		$typeentd = mysql_result($req,0,type);
		$url = mysql_result($req,0,url);
		if($_SESSION['typeent']==$typeentd)
			{
			$sql = 'UPDATE entreprises_tbl SET logo= "http://v2.dreadcast.net/'.$url.'" WHERE nom= "'.$_SESSION['entreprise'].'"' ;
			$req = mysql_query($sql);
			print('Le logo de votre entreprise est changé.');
			}
		else
			{
			print('Le logo que vous avez choisi est inaccessible pour votre entreprise.');
			}
		}
	elseif($idi=="logo")
		{
		$sql = 'UPDATE entreprises_tbl SET logo= "http://v2.dreadcast.net/ingame/im_objets/logo.jpg" WHERE nom= "'.$_SESSION['entreprise'].'"' ;
		$req = mysql_query($sql);
		print('Le logo de votre entreprise est changé.');
		}
	else
		{
		print('Le logo que vous avez choisi n\'existe plus.');
		}
	}
else
	{
	$sql = 'UPDATE entreprises_tbl SET logo= "'.$_POST['logo'].'" WHERE nom= "'.$_SESSION['entreprise'].'"' ;
	$req = mysql_query($sql);
	print('Le logo de votre entreprise est changé.');
	print('<br /><br /><br />Le voici:<br /><br /><img src="'.$_POST['logo'].'" width="100" height="100" border="1px" />');
	}

mysql_close($db);

?>

</p>
</div>
<?php if($chat=="oui") 	{ if ((date("H")>=21) || (date("H")<=7)) { include("inc_bas_n_c.php"); } else { include("inc_bas_j_c.php"); } } else {	if ((date("H")>=21) || (date("H")<=7)) { include("inc_bas_n.php"); } else { include("inc_bas_j.php"); }	} ?>
<?php include("inc_bas_de_page.php"); ?>
