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
			Mot de passe
		</div>
		<b class="module4ie"><a href="engine=infosperso.php" class="module4"><img src="im_objets/icon_retour.gif" alt="Retour" />Retour</a></b>
</p>
	</div>
</div>
<div id="centre">
<p>

<?php  
$ancien = $_POST['ancien'];
$nouveau = $_POST['nouveau'];
$confirm = $_POST['confirm'];

if($nouveau==$confirm)
	{
	$db = mysql_connect('localhost', 'CENSURE', 'CENSURE') or die("Erreur de connexion au serveur des messages.");
	mysql_select_db('CENSURE',$db) or die("Erreur de connexion a la base de donnees des messages.");
	
	$sql = 'SELECT password FROM principal_tbl WHERE id= "'.$_SESSION['id'].'"' ;
	$req = mysql_query($sql);
	$passverif = mysql_result($req,0,password); 

	if(sha1($ancien)==$passverif)
		{
		$sql = 'UPDATE principal_tbl SET password="'.sha1($nouveau).'" WHERE id= "'.$_SESSION['id'].'"' ;
		$req = mysql_query($sql);
		print('<strong>Votre mot de passe &agrave; correctement &eacute;t&eacute; chang&eacute;.</strong><br><br>');
		mysql_close($db);
		}
	else
		{
		print('<strong><center>Mauvais mot de passe.</center></strong>');
		mysql_close($db);
		}
	}
if($nouveau!=$confirm)
	{
	print('<strong>La confirmation du mot de passe ne correspond pas.</strong>');
	}
?>


</p>
</div>
<?php if($chat=="oui") 	{ if ((date("H")>=21) || (date("H")<=7)) { include("inc_bas_n_c.php"); } else { include("inc_bas_j_c.php"); } } else {	if ((date("H")>=21) || (date("H")<=7)) { include("inc_bas_n.php"); } else { include("inc_bas_j.php"); }	} ?>
<?php include("inc_bas_de_page.php"); ?>
