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

$sql = 'SELECT * FROM proprietaire_tbl WHERE pseudo= "'.$_SESSION['pseudo'].'" AND rue= "'.$_GET['rue'].'" AND num= "'.$_GET['num'].'"' ;
$req = mysql_query($sql);
$res = mysql_num_rows($req);

if($res!=0)
	{
	$sql = 'SELECT code FROM lieu_tbl WHERE rue= "'.$_GET['rue'].'" AND num= "'.$_GET['num'].'"' ;
	$req = mysql_query($sql);
	$digic = mysql_result($req,0,code);
	$_SESSION['autrelor'] = $_GET['rue'];
	$_SESSION['autrelon'] = $_GET['num'];
	}
else
	{
	print('<meta http-equiv="refresh" content="0 ; url=http://v2.dreadcast.net/ingame/engine=logement.php"> ');
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
			Changer de digicode
		</div>
		<b class="module4ie"><a href="engine=logement.php" class="module4"><img src="im_objets/icon_retour.gif" alt="Retour" />Retour</a></b>
</p>
	</div>
</div>
<div id="centre">
<p>

<form name="allera" id="allera" method="post" action="engine=changercodeverifautrelo.php">
	<p align="center"><strong>Ancien :</strong> <?php print(''.$digic.''); ?><br /><br />
	<strong>Nouveau :</strong> <input name="nouveau" type="text" id="nouveau" size="10" maxlength="<?php print(''.strlen($digic).''); ?>" /><br /><br />
	<input type="submit" name="Submit" value="Changer" />
	</p>
</form>
<?php 
if(strlen($digic)<6)
	{
	print('<div align="center"><a href="engine=codeundeplusautrelo.php">Acheter un chiffre de plus </strong>( 180 Credits )</a></div>');
	}
?>


</p>
</div>
<?php if($chat=="oui") 	{ if ((date("H")>=21) || (date("H")<=7)) { include("inc_bas_n_c.php"); } else { include("inc_bas_j_c.php"); } } else {	if ((date("H")>=21) || (date("H")<=7)) { include("inc_bas_n.php"); } else { include("inc_bas_j.php"); }	} ?>
<?php include("inc_bas_de_page.php"); ?>
