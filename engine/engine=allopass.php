<?php 
session_start(); 

$tracage = true;

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

$sql = 'SELECT allopass FROM principal_tbl WHERE id= "'.$_SESSION['id'].'"' ;
$req = mysql_query($sql);
$_SESSION['allopass'] = mysql_result($req,0,allopass);

$sql = 'SELECT credits FROM principal_tbl WHERE id= "'.$_SESSION['id'].'"' ;
$req = mysql_query($sql);
$_SESSION['credits'] = mysql_result($req,0,credits);

if($_SESSION['allopass']>=5)
	{
	print('<meta http-equiv="refresh" content="0 ; url=engine=roulette.php"> ');
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
			Allopass
		</div>
		<b class="module4ie"><a href="engine=inventaire.php" class="module4"><img src="im_objets/icon_retour.gif" alt="Retour" />Retour</a></b>
		</p>
	</div>
</div>
<div id="centre"><br />


	<div id="img1allopass">
		<a href="engine=allopass1.php" id="zoneimg1"><p>1300 Cr�dits</p></a><p class="texte">1.34� par appel ou 2� par CB ou W-HA</p>
	</div>

	<div id="img2allopass">
		<a href="engine=allopass2.php" id="zoneimg2"><p>2000 Cr�dits</p></a><p>3� par SMS, 3� par CB ou 3� par W-HA*</p>
	</div>
	
<p id="allopasstexte1"><br>
* Le W-HA est une technologie s�re de payement par facture Internet.<br>
Tous les fournisseurs d'acc�s sont concern�s (sauf Free).</p>

</p>
</div>
<?php if($chat=="oui") 	{ if ((date("H")>=21) || (date("H")<=7)) { include("inc_bas_n_c.php"); } else { include("inc_bas_j_c.php"); } } else {	if ((date("H")>=21) || (date("H")<=7)) { include("inc_bas_n.php"); } else { include("inc_bas_j.php"); }	} ?>
<?php include("inc_bas_de_page.php"); ?>
