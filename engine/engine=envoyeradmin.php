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
			Contacter un admin
		</div>
		</p>
	</div>
</div>
<div id="centre">
<p>

<?php  
	$db = mysql_connect('localhost', 'CENSURE', 'CENSURE') or die("Erreur de connexion au serveur des messages.");
	mysql_select_db('CENSURE',$db) or die("Erreur de connexion a la base de donnees des messages.");
	
	$_POST["objet"]="[".$_POST["typemess"]."] ".$_POST["objet"];
	
	$sql = 'INSERT INTO messagesadmin_tbl(id,auteur,message,objet,moment) VALUES("","'.$_SESSION['pseudo'].'","'.str_replace("\n","<br />",htmlentities($_POST["message"],ENT_QUOTES)).'<br /><br /><span style=\"color:#777;\">'.$_SERVER['HTTP_USER_AGENT'].'</span>","'.$_POST["objet"].'","'.time().'")' ;
	$req = mysql_query($sql);
	print('Votre message a correctement été envoy&eacute; &agrave; un administrateur.<br> ');
	
	mysql_close($db);
	
?>


</p>
</div>
<?php if($chat=="oui") 	{ if ((date("H")>=21) || (date("H")<=7)) { include("inc_bas_n_c.php"); } else { include("inc_bas_j_c.php"); } } else {	if ((date("H")>=21) || (date("H")<=7)) { include("inc_bas_n.php"); } else { include("inc_bas_j.php"); }	} ?>
<?php include("inc_bas_de_page.php"); ?>
