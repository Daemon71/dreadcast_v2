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


mysql_close($db);

?>
<?php if ((date("H")>=21) || (date("H")<=7)) { include("inc_haut_n.php"); } else { include("inc_haut_j.php");} ?>

<div id="haut">
	<?php include("inc_entete.php"); ?>
	<div class="haut_aligngauche">
		<p>
		<div class="titrepage">
			Ajout d'une news
		</div>
		<b class="module4ie"><a href="engine=panneau.php" class="module4"><img src="im_objets/icon_retour.gif" alt="Retour" />Retour</b></a>
		</p>
	</div>
</div>
<div id="centre">
<p>


<?php  
if($_POST['titre']=="")
	{
	print('Le titre est vide !');
	}
elseif($_POST['contenu']=="")
	{
	print("Le corp est vide !");
	}
else
	{
	$db = mysql_connect('localhost', 'CENSURE', 'CENSURE') or die("Erreur de connexion au serveur des messages.");
	mysql_select_db('CENSURE',$db) or die("Erreur de connexion a la base de donnees des messages.");
	
	$sql = 'INSERT INTO news_tbl(id,titre,contenu,pseudo,logonews,timestamp) VALUES("","'.$_POST['titre'].'","'.nl2br($_POST['contenu']).'","'.$_SESSION['pseudo'].'","'.$_POST['image'].'","'.time().'")' ;
	$req = mysql_query($sql);
	
	print('News ajout&eacute;e.');
	
	print('<meta http-equiv="refresh" content="0 ; url=http://v2.dreadcast.net/rss.php"> ');
	exit();
	
	mysql_close($db);
	}

?>


</p>
</div>
<?php if($chat=="oui") 	{ if ((date("H")>=21) || (date("H")<=7)) { include("inc_bas_n_c.php"); } else { include("inc_bas_j_c.php"); } } else {	if ((date("H")>=21) || (date("H")<=7)) { include("inc_bas_n.php"); } else { include("inc_bas_j.php"); }	} ?>
<?php include("inc_bas_de_page.php"); ?>
