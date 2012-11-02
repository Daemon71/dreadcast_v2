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
			Supprimer une annonce
		</div>
		</p>
	</div>
</div>

<div id="centreaitl2">
	
	<div id="menuaitl2">
		<a class="selectionne" href="engine=aitl2.php?type=tel">Canaux Impériaux</a>
		<a href="engine=aitl2.php?type=dcn">DC News</a>
		<a href="engine=aitl2.php?type=dctv">DC TV</a>
	</div>
	
	<div id="actionsaitl2">
	</div>
	
	<div id="contenuaitl2">
		<div class="scroll-pane">
			<div class="titre"><img src="im_objets/loader.gif" alt="..." /><br />Veuillez patienter</div>
<?php 

$db = mysql_connect('localhost', 'CENSURE', 'CENSURE') or die("Erreur de connexion au serveur des messages.");
mysql_select_db('CENSURE',$db) or die("Erreur de connexion a la base de donnees des messages.");

$sql = 'SELECT * FROM petitesannonces_tbl WHERE id='.$_GET['id'].'' ;
$req = mysql_query($sql);
$res = mysql_num_rows($req);
if($res>0)
	{
	if((mysql_result($req,0,auteur)==$_SESSION['pseudo']) or ($_SESSION['statut']=="Administrateur") or ($_SESSION['statut']=="Modérateur RPIG"))
		{
		$sql = 'DELETE FROM petitesannonces_tbl WHERE id='.$_GET['id'].'' ;
		$req = mysql_query($sql);
		}
	}

mysql_close($db);

print('<meta http-equiv="refresh" content="0 ; url=http://v2.dreadcast.net/ingame/engine=aitl2.php?type=pa"> ');
?>

		</div>
	</div>
</div>
<?php if($chat=="oui") 	{ if ((date("H")>=21) || (date("H")<=7)) { include("inc_bas_n_c.php"); } else { include("inc_bas_j_c.php"); } } else {	if ((date("H")>=21) || (date("H")<=7)) { include("inc_bas_n.php"); } else { include("inc_bas_j.php"); }	} ?>
<?php include("inc_bas_de_page.php"); ?>
