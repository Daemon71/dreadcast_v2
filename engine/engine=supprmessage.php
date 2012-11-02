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
			Suppression de messages
		</div>
		</p>
	</div>
</div>
<div id="centre">
<p>

<img src="im_objets/loader.gif" alt="..." />
<?php 
$message = $_SERVER['QUERY_STRING'];

$db = mysql_connect('localhost', 'CENSURE', 'CENSURE') or die("Erreur de connexion au serveur des messages.");
mysql_select_db('CENSURE',$db) or die("Erreur de connexion a la base de donnees des messages.");

if($message=="tout")
	{
	$sql = 'DELETE FROM messages_tbl WHERE cible="'.$_SESSION['pseudo'].'"' ;
	$req = mysql_query($sql);
	}
elseif($message!="")
	{
	$sql = 'SELECT id FROM messages_tbl WHERE id='.$message.' AND cible="'.$_SESSION['pseudo'].'"' ;
	$req = mysql_query($sql);
	$res = mysql_num_rows($req);
	if($res>0)
		{	
		$sql = 'DELETE FROM messages_tbl WHERE id='.$message.'' ;
		$req = mysql_query($sql);
		}
	}
elseif($_POST['nb']!="")
	{
	for($i=0;$i<$_POST['nb'];$i++)
		{
		$sql = 'SELECT id FROM messages_tbl WHERE id='.$_POST['n'.$i].' AND cible="'.$_SESSION['pseudo'].'"' ;
		$req = mysql_query($sql);
		$res = mysql_num_rows($req);
		if($res>0)
			{	
			$sql2 = 'DELETE FROM messages_tbl WHERE id='.$_POST['n'.$i].'' ;
			$req2 = mysql_query($sql2);
			}
		}
	}
elseif($_POST['messde']!="")
	{
	$sql2 = 'DELETE FROM messages_tbl WHERE auteur="'.$_POST['messde'].'" AND cible="'.$_SESSION['pseudo'].'"' ;
	mysql_query($sql2);
	}
else
	{
	print('<meta http-equiv="refresh" content="0 ; url=http://v2.dreadcast.net/ingame/engine=messages.php"> ');
	exit();
	}
mysql_close($db);

print('<meta http-equiv="refresh" content="0 ; url=http://v2.dreadcast.net/ingame/engine=messages.php"> ');
?>


</p>
</div>
<?php if($chat=="oui") 	{ if ((date("H")>=21) || (date("H")<=7)) { include("inc_bas_n_c.php"); } else { include("inc_bas_j_c.php"); } } else {	if ((date("H")>=21) || (date("H")<=7)) { include("inc_bas_n.php"); } else { include("inc_bas_j.php"); }	} ?>
<?php include("inc_bas_de_page.php"); ?>
