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
			Recueil
		</div>
		<b class="module4ie"><a href="engine=inventaire.php" class="module4"><img src="im_objets/icon_retour.gif" alt="Retour" />Retour</a></b>
</p>
	</div>
</div>
<div id="centre">
<p>

<?php 

$db = mysql_connect('localhost', 'CENSURE', 'CENSURE') or die("Erreur de connexion au serveur des messages.");
mysql_select_db('CENSURE',$db) or die("Erreur de connexion a la base de donnees des messages.");

$sql1 = 'SELECT id FROM cercles_tbl WHERE pseudo= "'.$_SESSION['pseudo'].'"' ;
$req1 = mysql_query($sql1);
$res1 = mysql_num_rows($req1);

if($res1==0)
	{
	$sql = 'SELECT case1,case2,case3,case4,case5,case6 FROM principal_tbl WHERE id= "'.$_SESSION['id'].'"' ;
	$req = mysql_query($sql);
	$_SESSION['case1'] = mysql_result($req,0,case1);
	$_SESSION['case2'] = mysql_result($req,0,case2);
	$_SESSION['case3'] = mysql_result($req,0,case3);
	$_SESSION['case4'] = mysql_result($req,0,case4);
	$_SESSION['case5'] = mysql_result($req,0,case5);
	$_SESSION['case6'] = mysql_result($req,0,case6);
	
	for($i=1; $i != 7; $i++)
		{
		if($_SESSION['case'.$i.'']=="Recueil de signatures")
			{
			$hypo = ''.rand(1,10000000).'-'.rand(1,10000000).'';
			$sql = 'UPDATE principal_tbl SET case'.$i.' ="Recueil'.$hypo.'" WHERE id= "'.$_SESSION['id'].'"' ;
			$req = mysql_query($sql);
			$sql = 'INSERT INTO objets_tbl(id,nom,puissance,image,url,infos,prix,type) VALUES("","Recueil'.$hypo.'","0","recueil.gif","engine=signde.php?'.$hypo.'","Ceci est un recueil de signatures pour un cercle.","0","feuille")';
			$req = mysql_query($sql);
			$sql = 'INSERT INTO signatures_tbl(id,numero,sign1) VALUES("","'.$hypo.'","'.$_SESSION['pseudo'].'")';
			$req = mysql_query($sql);
			print('<meta http-equiv="refresh" content="0 ; url=http://v2.dreadcast.net/ingame/engine=signde.php?'.$hypo.'"> ');
			exit();
			}
		}
		
	print('<meta http-equiv="refresh" content="0 ; url=http://v2.dreadcast.net/ingame/engine=inventaire.php"> ');
	}
else
	{
	print('Vous avez déjà un cercle.<br />Si vous désirez créer votre propre cercle il faut sortir de celui dans lequel vous êtes actuellement.');
	}
	
mysql_close($db);

?>

</p>
</div>
<?php if($chat=="oui") 	{ if ((date("H")>=21) || (date("H")<=7)) { include("inc_bas_n_c.php"); } else { include("inc_bas_j_c.php"); } } else {	if ((date("H")>=21) || (date("H")<=7)) { include("inc_bas_n.php"); } else { include("inc_bas_j.php"); }	} ?>
<?php include("inc_bas_de_page.php"); ?>
