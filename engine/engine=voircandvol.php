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
			Candidature
		</div>
		<b class="module4ie"><a href="engine=activite.php" class="module4"><img src="im_objets/icon_retour.gif" alt="Retour" />Retour</a></b>
</p>
	</div>
</div>
<div id="centre">
<p>

<?php 

$db = mysql_connect('localhost', 'CENSURE', 'CENSURE') or die("Erreur de connexion au serveur des messages.");
mysql_select_db('CENSURE',$db) or die("Erreur de connexion a la base de donnees des messages.");

$sql = 'SELECT * FROM candidatures_tbl WHERE nom= "'.$_SESSION['pseudo'].'" AND id= "'.$_SERVER['QUERY_STRING'].'"' ;
$req = mysql_query($sql);
$res = mysql_num_rows($req);

if($res!=0)
	{
	$ido = mysql_result($req,0,id);
	$message = mysql_result($req,0,msg);
	$poste = mysql_result($req,0,poste);
	$centreprise = mysql_result($req,0,entreprise);
	print('<p align="center"><strong>Candidature au poste de :</strong> '.$poste.'<br><strong>Entreprise :</strong> '.$centreprise.'</p><p align="center" class="barre">'.$message.'</p><p align="center"><a href="engine=supprcandvol.php?'.$ido.'">Supprimer</a></p>');
	}
else
	{
	print("<strong>Cette candidature ne peut pas s'afficher.</strong><br>");
	}
	
mysql_close($db);

?>

</p>
</div>
<?php if($chat=="oui") 	{ if ((date("H")>=21) || (date("H")<=7)) { include("inc_bas_n_c.php"); } else { include("inc_bas_j_c.php"); } } else {	if ((date("H")>=21) || (date("H")<=7)) { include("inc_bas_n.php"); } else { include("inc_bas_j.php"); }	} ?>
<?php include("inc_bas_de_page.php"); ?>
