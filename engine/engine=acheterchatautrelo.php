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

$sql = 'SELECT chat,code FROM lieu_tbl WHERE rue= "'.$_SESSION['autrelor'].'" AND num= "'.$_SESSION['autrelon'].'"' ;
$req = mysql_query($sql);
$chat = mysql_result($req,0,chat);

if($chat=="oui")
	{
	print('<meta http-equiv="refresh" content="0 ; url=http://v2.dreadcast.net/ingame/engine=logement.php"> ');
	exit();
	}

$sql = 'SELECT * FROM proprietaire_tbl WHERE pseudo= "'.$_SESSION['pseudo'].'" AND rue= "'.$_SESSION['autrelor'].'" AND num= "'.$_SESSION['autrelon'].'"' ;
$req = mysql_query($sql);
$res = mysql_num_rows($req);

if($res!=0)
	{
	$sql = 'SELECT credits FROM principal_tbl WHERE id= "'.$_SESSION['id'].'"' ;
	$req = mysql_query($sql);
	$_SESSION['credits'] = mysql_result($req,0,credits); 
	if($_SESSION['credits']>=700)
		{
		$_SESSION['credits'] = $_SESSION['credits'] - 700;
		$sql = 'UPDATE principal_tbl SET credits="'.$_SESSION['credits'].'" WHERE id= "'.$_SESSION['id'].'"' ;
		$req = mysql_query($sql);
		$sql = 'UPDATE lieu_tbl SET chat="oui" WHERE rue= "'.$_SESSION['autrelor'].'" AND num= "'.$_SESSION['autrelon'].'"' ;
		$req = mysql_query($sql);
		for($i=1;$i!=15;$i++)
			{
			$sql = 'INSERT INTO chat(id,posteur,message,rue,num,moment,ids) VALUES(""," "," ","'.$_SESSION['autrelor'].'","'.$_SESSION['autrelon'].'","'.date("H:i").'","'.$i.'")' ;
			$req = mysql_query($sql);
			}
		$sql = 'INSERT INTO chat(id,posteur,message,rue,num,moment,ids) VALUES("","Dreadcast","Bienvenue dans le salon du '.$_SESSION['autrelon'].' '.$_SESSION['autrelor'].'.","'.$_SESSION['autrelor'].'","'.$_SESSION['autrelon'].'","'.date("H:i").'","15")' ;
		$req = mysql_query($sql);
		$chat = "oui";
		}
	}
else
	{
	print('<meta http-equiv="refresh" content="0 ; url=http://v2.dreadcast.net/ingame/engine=logement.php"> ');
	exit();
	}

mysql_close($db);

?>
<?php if ((date("H")>=21) || (date("H")<=7)) { include("inc_haut_n.php"); } else { include("inc_haut_j.php");} ?>

<div id="haut">
	<?php include("inc_entete.php"); ?>
	<div class="haut_aligngauche">
		<p>
		<div class="titrepage">
			Situation Actuelle
		</div>
		</p>
	</div>
</div>
<div id="centre">
<p>


<?php
if($chat=="oui")
	{
	print('Ce logement contient maintenant un Salon de discussion.');
	}
else
	{
	print('<strong>Vous n\'avez pas assez d\'argent.</strong>');
	}
?>


</p>
</div>
<?php if($chat=="oui") 	{ if ((date("H")>=21) || (date("H")<=7)) { include("inc_bas_n_c.php"); } else { include("inc_bas_j_c.php"); } } else {	if ((date("H")>=21) || (date("H")<=7)) { include("inc_bas_n.php"); } else { include("inc_bas_j.php"); }	} ?>
<?php include("inc_bas_de_page.php"); ?>
