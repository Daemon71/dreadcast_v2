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
			Contr&ocirc;le
		</div>
		<b class="module4ie"><a href="engine=panneau.php" class="module4"><img src="im_objets/icon_retour.gif" alt="Retour" />Retour</a></b>
</p>
	</div>
</div>
<?php include('inc_admin.php'); ?>
<div id="centre">
<p>

<?php 

if($_SESSION['statut']=="Administrateur")
	{
	$db = mysql_connect('localhost', 'CENSURE', 'CENSURE') or die("Erreur de connexion au serveur des messages.");
	mysql_select_db('CENSURE',$db) or die("Erreur de connexion a la base de donnees des messages.");

	$sql1 = 'SELECT * FROM principal_tbl WHERE id= "'.$_SERVER['QUERY_STRING'].'"' ;
	$req1 = mysql_query($sql1);
	$statut = mysql_result($req1,0,statut); 
	$id = $_SERVER['QUERY_STRING'];
	$sql1 = 'SELECT * FROM principal_tbl WHERE id= "'.$_SERVER['QUERY_STRING'].'"' ;
	$req1 = mysql_query($sql1);
	$pseudo = mysql_result($req1,0,pseudo); 
	
	if($statut!="Administrateur")
		{
		$sql1 = 'DELETE FROM adresses_tbl WHERE pseudo= "'.$pseudo.'"' ;
		$req1 = mysql_query($sql1);
		$sql1 = 'DELETE FROM bonus_tbl WHERE id= "'.$id.'"' ;
		$req1 = mysql_query($sql1);
		$sql1 = 'DELETE FROM candidatures_tbl WHERE nom= "'.$pseudo.'"' ;
		$req1 = mysql_query($sql1);
		$sql1 = 'DELETE FROM carnets_tbl WHERE pseudo= "'.$pseudo.'"' ;
		$req1 = mysql_query($sql1);
		$sql1 = 'DELETE FROM principal_tbl WHERE id= "'.$id.'"' ;
		$req1 = mysql_query($sql1);
		$sql1 = 'DELETE FROM principal_tbl WHERE id= "'.$id.'"' ;
		$req1 = mysql_query($sql1);
		$sql1 = 'DELETE FROM principal_tbl WHERE id= "'.$id.'"' ;
		$req1 = mysql_query($sql1);
		$sql = 'DELETE FROM proprietaire_tbl WHERE pseudo= "'.$pseudo.'"' ;
		$req = mysql_query($sql);
		$sql1 = 'DELETE FROM messagesadmin_tbl WHERE auteur= "'.$pseudo.'"' ;
		$req1 = mysql_query($sql1);
		$sql1 = 'DELETE FROM messages_tbl WHERE cible= "'.$pseudo.'"' ;
		$req1 = mysql_query($sql1);
		$sql1 = 'DELETE FROM principal_tbl WHERE pseudo= "'.$pseudo.'"' ;
		$req1 = mysql_query($sql1);
		$sql1 = 'DELETE FROM pleintes_tbl WHERE pseudo= "'.$pseudo.'"' ;
		$req1 = mysql_query($sql1);
		$sql1 = 'DELETE FROM petitesannonces_tbl WHERE auteur= "'.$pseudo.'"' ;
		$req1 = mysql_query($sql1);
		$sql1 = 'DELETE FROM principal_tbl WHERE id= "'.$id.'"' ;
		$req1 = mysql_query($sql1);
		$sql1 = 'DELETE FROM principal_tbl WHERE id= "'.$id.'"' ;
		$req1 = mysql_query($sql1);
		$sql1 = 'DELETE FROM principal_tbl WHERE id= "'.$id.'"' ;
		$req1 = mysql_query($sql1);
		$sql1 = 'DELETE FROM principal_tbl WHERE id= "'.$id.'"' ;
		$req1 = mysql_query($sql1);
		$sql1 = 'INSERT INTO abandons_tbl(id,pseudo,raison) VALUES("","'.$pseudo.'","Compte supprimé par l\'administration")' ;
		$req1 = mysql_query($sql1);
		print('Compte supprimé.');
		}


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
