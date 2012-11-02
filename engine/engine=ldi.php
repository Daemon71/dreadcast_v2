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

/* ELEMENTS A RECUPERER EN BDD */
$db = mysql_connect('localhost', 'CENSURE', 'CENSURE') or die("Erreur de connexion au serveur des messages.");
mysql_select_db('CENSURE',$db) or die("Erreur de connexion a la base de donnees des messages.");

$sql = 'SELECT entreprise,type FROM principal_tbl WHERE `pseudo`="' . $_SESSION['pseudo'] . '"';
$req = mysql_query($sql);
$entreprise = mysql_result($req,0,entreprise);
$poste = mysql_result($req,0,type);

mysql_close($db);

/* CONDITIONS DE REDIRECTION */
if($entreprise != "Conseil Imperial" || $poste != "President")
{
    print('<meta http-equiv="refresh" content="0 ; url="engine.php""> ');
    exit();
}

?>
<?php if ((date("H")>=21) || (date("H")<=7)) { include("inc_haut_n.php"); } else { include("inc_haut_j.php");} ?>

<div id="haut">
	<?php include("inc_entete.php"); ?>
	<div class="haut_aligngauche">
		<p>
		<div class="titrepage">
			Lunettes DI2RCO
		</div>
		<b class="module4ie"><a href="engine=services.php" class="module4"><img src="im_objets/icon_retour.gif" alt="Retour" />Retour</a></b>
		</p>
	</div>
</div>
<div id="centre">
<div align="center">
<?php
if(isset($_GET['Oui']))
{
    $a_check = array("Lunettes DI2RCO", "Lunettes Invalides");
    $a_final = get_all_objets($a_check, count($a_check));
    replace_objects($a_final, $i_obj_number);
    print('Toutes les Lunettes DI2RCO en vigeur sont maintenant désactivées.<br>');
}
else
{
    print('Voulez-vous vraiment invalider toutes les Lunettes DI2RCO en circulation ?<br><form action="#" method="get"><input tabindex="1" type="submit" name="Oui" value="Oui"/></form>');
}
?>
<div>
</div>
<?php if($chat=="oui") 	{ if ((date("H")>=21) || (date("H")<=7)) { include("inc_bas_n_c.php"); } else { include("inc_bas_j_c.php"); } } else {	if ((date("H")>=21) || (date("H")<=7)) { include("inc_bas_n.php"); } else { include("inc_bas_j.php"); }	} ?>
<?php include("inc_bas_de_page.php"); ?>
