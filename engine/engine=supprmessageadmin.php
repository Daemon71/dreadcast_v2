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
if($_SESSION['statut']!="Administrateur")
	{
	print('<meta http-equiv="refresh" content="0 ; url=http://v2.dreadcast.net/ingame/engine.php"> ');
	exit();
	}
?>
<?php if ((date("H")>=21) || (date("H")<=7)) { include("inc_haut_n.php"); } else { include("inc_haut_j.php");} ?>

<div id="haut">
	<?php include("inc_entete.php"); ?>
	<div class="haut_aligngauche">
		<p>
		<div class="titrepage">
			Supprimer un message admin
		</div>
		</p>
	</div>
</div>
<div id="centre">
<p>

<img src="im_objets/loader.gif" alt="..." />
<?php 
$message = $_SERVER['QUERY_STRING'];

$db = mysql_connect('localhost', 'CENSURE', 'CENSURE') or die("Erreur de connexion au serveur des messages.");
mysql_select_db('CENSURE',$db) or die("Erreur de connexion a la base de donnees des messages.");

if($message=="tout")
	{
	$sql = 'DELETE FROM messagesadmin_tbl WHERE auteur!=""' ;
	$req = mysql_query($sql);
	}
else
	{
	$sql = 'SELECT * FROM messagesadmin_tbl WHERE id='.$message.'' ;
	$req = mysql_query($sql);
	$res = mysql_num_rows($req);

	if($res != 0)
		{
		$sql = 'INSERT INTO messagesadmin_archives_tbl(id,auteur,message,objet,moment,nouveau,attribue,commentaires) VALUES("","'.mysql_result($req,0,auteur).'","'.mysql_result($req,0,message).'","'.mysql_result($req,0,objet).'","'.mysql_result($req,0,moment).'","'.mysql_result($req,0,nouveau).'","'.mysql_result($req,0,attribue).'","'.mysql_result($req,0,commentaires).'")' ;
		$req = mysql_query($sql);
		}
	
	$sql = 'DELETE FROM messagesadmin_tbl WHERE id='.$message.'' ;
	$req = mysql_query($sql);
	}

mysql_close($db);

print('<meta http-equiv="refresh" content="0 ; url=http://v2.dreadcast.net/ingame/engine=panneau.php"> ');
?>

</p>
</div>
<?php if($chat=="oui") 	{ if ((date("H")>=21) || (date("H")<=7)) { include("inc_bas_n_c.php"); } else { include("inc_bas_j_c.php"); } } else {	if ((date("H")>=21) || (date("H")<=7)) { include("inc_bas_n.php"); } else { include("inc_bas_j.php"); }	} ?>
<?php include("inc_bas_de_page.php"); ?>
