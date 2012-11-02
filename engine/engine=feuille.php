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
			Feuille
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
	if($_SESSION['case'.$i.'']=="Feuille de papier libre")
		{
		$hypo = ''.rand(1,10000000).'-'.rand(1,10000000).'';
		$sql = 'UPDATE principal_tbl SET case'.$i.' ="Feuille'.$hypo.'" WHERE id= "'.$_SESSION['id'].'"' ;
		$req = mysql_query($sql);
		$sql = 'INSERT INTO objets_tbl(id,nom,puissance,image,url,infos,prix,type) VALUES("","Feuille'.$hypo.'","0","papier_util.jpg","engine=feuillede.php?'.$hypo.'","Ceci est une feuille de papier déjà raturée. Vous pouvez cependant y noter ce que vous désirez (150caractères maximum).","0","feuille")';
		$req = mysql_query($sql);
		$sql = 'INSERT INTO feuilles_tbl(id,numero,message) VALUES("","'.$hypo.'","")';
		$req = mysql_query($sql);
		print('<meta http-equiv="refresh" content="0 ; url=http://v2.dreadcast.net/ingame/engine=feuillede.php?'.$hypo.'"> ');
		exit();
		}
	}
	
print('<meta http-equiv="refresh" content="0 ; url=http://v2.dreadcast.net/ingame/engine=inventaire.php"> ');
	
mysql_close($db);

?>

</p>
</div>
<?php if($chat=="oui") 	{ if ((date("H")>=21) || (date("H")<=7)) { include("inc_bas_n_c.php"); } else { include("inc_bas_j_c.php"); } } else {	if ((date("H")>=21) || (date("H")<=7)) { include("inc_bas_n.php"); } else { include("inc_bas_j.php"); }	} ?>
<?php include("inc_bas_de_page.php"); ?>
