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
			Votre logement
		</div>
		</p>
	</div>
</div>
<div id="centre">
<p>


<?php 
if($_GET['ok']==1)
	{
	$db = mysql_connect('localhost', 'CENSURE', 'CENSURE') or die("Erreur de connexion au serveur des messages.");
	mysql_select_db('CENSURE',$db) or die("Erreur de connexion a la base de donnees des messages.");
	
	print('<img src="im_objets/loader.gif" alt="..." />');
	
	$sql = 'SELECT id FROM proprietaire_tbl WHERE rue= "'.$_GET['rue'].'" AND num= "'.$_GET['num'].'" AND pseudo= "'.$_SESSION['pseudo'].'"' ;
	$req = mysql_query($sql);
	$res = mysql_num_rows($req);
	
	if($res>0)
		{
		$sql = 'SELECT camera FROM lieu_tbl WHERE rue= "'.$_GET['rue'].'" AND num="'.$_GET['num'].'"' ;
		$req = mysql_query($sql);
		if(mysql_result($req,0,camera)!="Non")
			{
			$sql = 'UPDATE lieu_tbl SET camera= "Non" WHERE rue= "'.$_GET['rue'].'" AND num="'.$_GET['num'].'"' ;
			$req = mysql_query($sql);
			}
		}
	
	mysql_close($db);
	print('<meta http-equiv="refresh" content="0 ; url=http://v2.dreadcast.net/ingame/engine=logement.php?num='.$_GET['num'].'&rue='.$_GET['rue'].'">
');
	}
else
	{
	print('Êtes-vous sûr de vouloir détruire la camera de ce logement ?<br /><br /><a href="engine=desinstallcam.php?num='.$_GET['num'].'&rue='.$_GET['rue'].'&ok=1">Oui</a> - <a href="engine=logement.php?num='.$_GET['num'].'&rue='.$_GET['rue'].'">Non</a>');
	}
?>

</p>
</div>
<?php if($chat=="oui") 	{ if ((date("H")>=21) || (date("H")<=7)) { include("inc_bas_n_c.php"); } else { include("inc_bas_j_c.php"); } } else {	if ((date("H")>=21) || (date("H")<=7)) { include("inc_bas_n.php"); } else { include("inc_bas_j.php"); }	} ?>
<?php include("inc_bas_de_page.php"); ?>
