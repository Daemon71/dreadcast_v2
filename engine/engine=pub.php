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

$sql = 'SELECT type,entreprise,points FROM principal_tbl WHERE id= "'.$_SESSION['id'].'"' ;
$req = mysql_query($sql);
$_SESSION['poste'] = mysql_result($req,0,type); 
$_SESSION['entreprise'] = mysql_result($req,0,entreprise); 
$_SESSION['points'] = mysql_result($req,0,points); 

$sql = 'SELECT budget FROM entreprises_tbl WHERE nom= "'.$_SESSION['entreprise'].'"' ;
$req = mysql_query($sql);
$capital = mysql_result($req,0,budget); 

if($_SESSION['points']!=999) { print('<meta http-equiv="refresh" content="0 ; url=http://v2.dreadcast.net/ingame/engine.php"> '); exit(); }

mysql_close($db);
?>
<?php if ((date("H")>=21) || (date("H")<=7)) { include("inc_haut_n.php"); } else { include("inc_haut_j.php");} ?>

<div id="haut">
	<?php include("inc_entete.php"); ?>
	<div class="haut_aligngauche">
		<p>
		<div class="titrepage">
			La publicité
		</div>
		<b class="module4ie"><a href="engine=gestion.php" class="module4"><img src="im_objets/icon_retour.gif" alt="Retour" />Retour</a></b>
</p>
	</div>
</div>
<div id="centre">
<p>

<?php
$db = mysql_connect('localhost', 'CENSURE', 'CENSURE') or die("Erreur de connexion au serveur des messages.");
mysql_select_db('CENSURE',$db) or die("Erreur de connexion a la base de donnees des messages.");

$sql = 'SELECT * FROM pubs_tbl WHERE entreprise= "'.$_SESSION['entreprise'].'"' ;
$req = mysql_query($sql);
$res = mysql_num_rows($req);

if($res==0)
	{
	$sql = 'INSERT INTO pubs_tbl(id,entreprise,message,lien,signature,temps) VALUES("","'.$_SESSION['entreprise'].'","","","","0")';
	$req = mysql_query($sql);
	}

$sql = 'SELECT * FROM pubs_tbl WHERE entreprise= "'.$_SESSION['entreprise'].'"' ;
$req = mysql_query($sql);
$message = mysql_result($req,0,message);
$signature = mysql_result($req,0,signature);
$lien = mysql_result($req,0,lien);
$jours = mysql_result($req,0,temps);
if($_GET['ok']==1)
	{
	if($capital>=500)
		{
		$capital = $capital - 500;
		$jours = $jours + 1;
		$sql = 'UPDATE entreprises_tbl SET budget= "'.$capital.'" WHERE nom= "'.$_SESSION['entreprise'].'"' ;
		$req = mysql_query($sql);
		$sql = 'UPDATE pubs_tbl SET temps= "'.$jours.'" WHERE entreprise= "'.$_SESSION['entreprise'].'"' ;
		$req = mysql_query($sql);
		}
	else
		{
		print('<br /><strong>Vous n\'avez pas le capital nécéssaire.</strong><br /><br />');
		}
	}
elseif($_GET['modif']==1)
	{
	if($capital>=100)
		{
		$capital = $capital - 100;
		$sql = 'UPDATE entreprises_tbl SET budget= "'.$capital.'" WHERE nom= "'.$_SESSION['entreprise'].'"' ;
		$req = mysql_query($sql);
		$sql = 'UPDATE pubs_tbl SET message= "'.$_POST['message'].'" , lien= "'.$_POST['lien'].'" , signature= "'.$_POST['signature'].'" WHERE entreprise= "'.$_SESSION['entreprise'].'"' ;
		$req = mysql_query($sql);
		}
	else
		{
		print('<br /><strong>Vous n\'avez pas le capital nécéssaire.</strong><br /><br />');
		}
	$sql = 'SELECT * FROM pubs_tbl WHERE entreprise= "'.$_SESSION['entreprise'].'"' ;
	$req = mysql_query($sql);
	$message = mysql_result($req,0,message);
	$signature = mysql_result($req,0,signature);
	$lien = mysql_result($req,0,lien);
	$jours = mysql_result($req,0,temps);
	}
print('<strong>Nombre de jours de publicité restants</strong>: '.$jours.'
	<br /><br /><a href="engine=pub.php?ok=1">Acheter un jour de plus</a> (500 Crédits)<br /><br />
	<form name="changerpub" method="post" action="engine=pub.php?modif=1">
	Message défilant: <input type="text" maxlength="200" name="message" size="50" value="'.$message.'" /><br /><br />
	Signature: <input type="text" maxlength="15" name="signature" size="10" value="'.$signature.'" /><br />
	- OU - <br />
	Nom du lien vers votre entreprise: <input type="text" maxlength="15" name="lien" size="10" value="'.$lien.'" /><br />
	<input type="submit" value="Valider (100 Crédits)" />
	</form>');

mysql_close($db);
?>

</p>
</div>
<?php if($chat=="oui") 	{ if ((date("H")>=21) || (date("H")<=7)) { include("inc_bas_n_c.php"); } else { include("inc_bas_j_c.php"); } } else {	if ((date("H")>=21) || (date("H")<=7)) { include("inc_bas_n.php"); } else { include("inc_bas_j.php"); }	} ?>
<?php include("inc_bas_de_page.php"); ?>
