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
			Statistiques
		</div>
		<b class="module4ie"><a href="engine=panneau.php" class="module4"><img src="im_objets/icon_retour.gif" alt="Retour" />Retour</a></b>
</p>
	</div>
</div>
<div id="centre">
<p>

<?php 

if(($_SESSION['statut']=="Administrateur") or ($_SESSION['statut']=="Développeur"))
	{
	$db = mysql_connect('localhost', 'CENSURE', 'CENSURE') or die("Erreur de connexion au serveur des messages.");
	mysql_select_db('CENSURE',$db) or die("Erreur de connexion a la base de donnees des messages.");

	$sql1 = 'SELECT id FROM principal_tbl' ;
	$req1 = mysql_query($sql1);
	$res1 = mysql_num_rows($req1);
	$sql4 = 'SELECT id FROM principal_tbl WHERE action= "mort"' ;
	$req4 = mysql_query($sql4);
	$res4 = mysql_num_rows($req4);
	$sql5 = 'SELECT id FROM entreprises_tbl' ;
	$req5 = mysql_query($sql5);
	$res5 = mysql_num_rows($req5);
	$sql6 = 'SELECT id FROM cerclesliste_tbl' ;
	$req6 = mysql_query($sql6);
	$res6 = mysql_num_rows($req6);
	$sql7 = 'SELECT allopasstot FROM principal_tbl WHERE allopasstot>0' ;
	$req7 = mysql_query($sql7);
	$res7 = mysql_num_rows($req7);
	for($i=0;$i!=$res7;$i++) { $allopass+=mysql_result($req7,$i,allopasstot); }
	$sql8 = 'SELECT id FROM principal_tbl WHERE statut= "Compte VIP"' ;
	$req8 = mysql_query($sql8);
	$vip = mysql_num_rows($req8);
	
	print('<hr><p align="center"><strong>Nombre de joueurs : </strong><i>'.$res1.'</i><br /><strong>Nombre de joueurs actifs: </strong><i>'.($res1-$res4).' ('.substr((($res1-$res4)*100)/$res1, 0, 4).'%)</i><br /><strong>Nombre de morts : </strong><i>'.$res4.'</i><br /><strong>Nombre d\'entreprises/organisations : </strong><i>'.$res5.'</i><br /><strong>Nombre de cercles : </strong><i>'.$res6.'</i><br /><br /><strong>Nombre de Comptes VIP:</strong> '.$vip.' ('.substr($vip*100/$res1,0,4).'%)<br /><strong>Nombre de personnes qui ont fait allopass:</strong> '.$res7.' ('.substr($res7*100/$res1,0,4).'%)<br /><strong>Nombre de Allopass total:</strong> '.$allopass.'</p>');

	mysql_close($db);
	}
else
	{
	print('<meta http-equiv="refresh" content="0 ; url=http://v2.dreadcast.net/ingame/engine.php"> ');
	exit();
	}

?>

</p>
</div>
<?php if($chat=="oui") 	{ if ((date("H")>=21) || (date("H")<=7)) { include("inc_bas_n_c.php"); } else { include("inc_bas_j_c.php"); } } else {	if ((date("H")>=21) || (date("H")<=7)) { include("inc_bas_n.php"); } else { include("inc_bas_j.php"); }	} ?>
<?php include("inc_bas_de_page.php"); ?>
