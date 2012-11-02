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

$sql = 'SELECT action FROM principal_tbl WHERE action LIKE "Protection %" AND id='.$_SESSION['id'] ;
$req = mysql_query($sql);
$res = mysql_num_rows($req);

if($res != 0) // SI JE SUIS LE PROTECTEUR
	{
	$cible = str_replace("Protection ","",mysql_result($req,0,action));
	
	$sql = 'INSERT INTO messages_tbl(auteur,cible,objet,message,moment) VALUES("Dreadcast","'.$cible.'","Fin de protection","<br />'.$_SESSION['pseudo'].' a mis fin à votre contrat de protection.",'.time().')' ;
	mysql_query($sql);
	
	$sql = 'UPDATE principal_tbl SET action = "Aucune" WHERE id='.$_SESSION['id'] ;
	mysql_query($sql);
	}
else // SI JE SUIS LE PROTEGE
	{
	$sql = 'SELECT pseudo FROM principal_tbl WHERE action = "Protection '.$_SESSION['pseudo'].'"' ;
	$req = mysql_query($sql);
	$res = mysql_num_rows($req);
	
	if($res != 0)
		{
		$cible = mysql_result($req,0,pseudo);
		
		$sql = 'INSERT INTO messages_tbl(auteur,cible,objet,message,moment) VALUES("Dreadcast","'.$cible.'","Fin de protection","<br />'.$_SESSION['pseudo'].' a mis fin à votre contrat de protection.",'.time().')' ;
		mysql_query($sql);
		
		$sql = 'UPDATE principal_tbl SET action = "Aucune" WHERE pseudo="'.$cible.'"' ;
		mysql_query($sql);
		}
	}

mysql_close($db);

?>
<?php if ((date("H")>=21) || (date("H")<=7)) { include("inc_haut_n.php"); } else { include("inc_haut_j.php");} ?>

<div id="haut">
	<?php include("inc_entete.php"); ?>
	<div class="haut_aligngauche">
		<p>
		<div class="titrepage">
			Protection
		</div>
		<b class="module4ie"><a href="engine.php" class="module4"><img src="im_objets/icon_retour.gif" alt="Retour" />Retour</a></b>
</p>
	</div>
</div>
<div id="centre">
<p>

Vous venez de mettre fin à votre contrat de protection.

</p>
</div>
<?php if($chat=="oui") 	{ if ((date("H")>=21) || (date("H")<=7)) { include("inc_bas_n_c.php"); } else { include("inc_bas_j_c.php"); } } else {	if ((date("H")>=21) || (date("H")<=7)) { include("inc_bas_n.php"); } else { include("inc_bas_j.php"); }	} ?>
<?php include("inc_bas_de_page.php"); ?>
