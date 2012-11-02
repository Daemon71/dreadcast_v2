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

$sql = 'SELECT sante,action FROM principal_tbl WHERE pseudo= "'.htmlentities(str_replace("%20"," ",$_SERVER['QUERY_STRING'])).'"' ;
$req = mysql_query($sql);

mysql_close($db);
if(mysql_num_rows($req) == 0 OR mysql_result($req,0,sante) != 0 OR mysql_result($req,0,action) == "mort")
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
			Achever
		</div>
		<b class="module4ie"><a href="engine.php" class="module4"><img src="im_objets/icon_retour.gif" alt="Retour" />Retour</a></b>
		</p>
	</div>
</div>
<div id="centre">
<p>


<em>Etes-vous s&ucirc;r de vouloir achever</em> <strong><?php print(''.str_replace("%20"," ",''.$_SERVER['QUERY_STRING'].'').''); ?> </strong><em>?</em>
<br /><br />
<a href="engine=acheverconf.php?<?php print(''.str_replace("%20"," ",''.$_SERVER['QUERY_STRING'].'').''); ?>">Oui, je souhaite en terminer avec lui.</a><br />
<a href="engine.php">Non, il ne m&eacute;rite pas de mourir.</a>


</p>
</div>
<?php if($chat=="oui") 	{ if ((date("H")>=21) || (date("H")<=7)) { include("inc_bas_n_c.php"); } else { include("inc_bas_j_c.php"); } } else {	if ((date("H")>=21) || (date("H")<=7)) { include("inc_bas_n.php"); } else { include("inc_bas_j.php"); }	} ?>
<?php include("inc_bas_de_page.php"); ?>
