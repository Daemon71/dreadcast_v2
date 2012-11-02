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
			Rechercher des cristaux
		</div>
		</p>
	</div>
</div>
<div id="centre<?php if($_SESSION['num']<=0)print('_rue'); ?>">

<p<?php if($_SESSION['num']<=0)print(' id="location"'); ?>>

<img src="im_objets/loader.gif" alt="..." />

<?php	

if($_SESSION['num']<=0)print('<br />Veuillez patienter');

$db = mysql_connect('localhost', 'CENSURE', 'CENSURE') or die("Erreur de connexion au serveur.");
mysql_select_db('CENSURE',$db) or die("Erreur de connexion a la base de donnees.");

$sql = 'SELECT id,action,rue,num,objet,entreprise FROM principal_tbl WHERE pseudo= "'.$_SESSION['pseudo'].'"' ;
$req = mysql_query($sql);
$_SESSION['id'] = mysql_result($req,0,id);
$_SESSION['action'] = mysql_result($req,0,action);
$_SESSION['lieu'] = ucwords(mysql_result($req,0,rue));
$_SESSION['num'] = mysql_result($req,0,num);
$_SESSION['objet'] = mysql_result($req,0,objet);
$monentreprise = mysql_result($req,0,entreprise);

if($_SESSION['action']=="prison")
	{
	print('<meta http-equiv="refresh" content="0 ; url=http://v2.dreadcast.net/ingame/engine.php"> ');
	exit();
	}

if(($_SESSION['objet']!="Neuvopack") && ($_SESSION['objet']!="Neuvopack1") && ($_SESSION['objet']!="Neuvopack2") && ($_SESSION['objet']!="Neuvopack3") && ($_SESSION['objet']!="Neuvopack4") && ($_SESSION['objet']!="Neuvopack5") && ($_SESSION['objet']!="Neuvopack6") && ($_SESSION['objet']!="Neuvopack7") && ($_SESSION['objet']!="Neuvopack8") && ($_SESSION['objet']!="Neuvopack9"))
	{
	print('<br />Il vous faut un Neuvopack pour stocker les cristaux trouvés !');
	}
elseif($_SESSION['objet']=="Neuvopack10")
	{
	print('<br />Votre Neuvopack est plein.<br />Allez le videz aux services techniques de la ville.');
	}
elseif($_SESSION['action']!="Recherche de cristaux")
	{
	$sql = 'UPDATE principal_tbl SET action="Recherche de cristaux" WHERE pseudo= "'.$_SESSION['pseudo'].'"' ;
	$req = mysql_query($sql);
	if($monentreprise != "Aucune") verification_ouverture_entreprise($monentreprise);
	$_SESSION['action']="Recherche de cristaux";
	}

print('<meta http-equiv="refresh" content="0 ; url=http://v2.dreadcast.net/ingame/engine.php"> ');

mysql_close($db);

?>

</p>
</div>
<?php if($chat=="oui") 	{ if ((date("H")>=21) || (date("H")<=7)) { include("inc_bas_n_c.php"); } else { include("inc_bas_j_c.php"); } } else {	if ((date("H")>=21) || (date("H")<=7)) { include("inc_bas_n.php"); } else { include("inc_bas_j.php"); }	} ?>
<?php include("inc_bas_de_page.php"); ?>
