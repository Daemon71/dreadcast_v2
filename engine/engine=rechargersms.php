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

$sql = 'SELECT SMS,telephone FROM principal_tbl WHERE pseudo= "'.$_SESSION['pseudo'].'"' ;
$req = mysql_query($sql);
$_SESSION['SMS'] = mysql_result($req,0,SMS);
$_SESSION['telephone'] = mysql_result($req,0,telephone);

if($_POST['telephone']!="")
	{
	$_SESSION['telephone'] = $_POST['telephone'];
	$sql = 'UPDATE principal_tbl SET telephone= "'.$_SESSION['telephone'].'" WHERE pseudo= "'.$_SESSION['pseudo'].'"' ;
	$req = mysql_query($sql);
	}

mysql_close($db);
?>
<?php if ((date("H")>=21) || (date("H")<=7)) { include("inc_haut_n.php"); } else { include("inc_haut_j.php");} ?>

<div id="haut">
	<?php include("inc_entete.php"); ?>
	<div class="haut_aligngauche">
		<p>
		<div class="titrepage">
			SMS Flash!
		</div>
		<b class="module4ie"><a href="engine=smsflash.php" class="module4"><img src="im_objets/icon_retour.gif" alt="Retour" />Retour</a></b>
</p>
	</div>
</div>
<div id="centre">
<p>

		<p align="center"><br /><strong>Votre réserve: </strong><? if($_SESSION['SMS']<3) { print('<i><span class="color3">'.$_SESSION['SMS'].'</span> SMS Flash!</i>'); }else { print ('<i>'.$_SESSION['SMS'].' SMS Flash!</i>'); } ?></p>
		<p align="center"></p>
		
<table width="90%" bgcolor="#FFFFFF" border="1" align="center" cellpadding="2" cellspacing="0">
	<tr>
		<td>
		<a href="engine=pack5.php">Pack découverte de 5 SMS Flash!</a>
		</td>
		<td>
		3€ par CB, par SMS ou par W-HA*
		</td>
	</tr>
	<tr>
		<td>
		<a href="engine=pack20.php">Pack de 20 SMS Flash!</a>
		</td>
		<td>
		10€ par CB
		</td>
	</tr>
	<tr>
		<td>
		<a href="engine=pack50.php">Pack de 50 SMS Flash!</a>
		</td>
		<td>
		20€ par CB
		</td>
	</tr>
	<tr>
		<td>
		<a href="engine=pack100.php">Pack de 100 SMS Flash!</a>
		</td>
		<td>
		30€ par CB
		</td>
	</tr>
</table>

<p align="center"><br />
* Le W-HA est une technologie sûre de payement par facture Internet.<br>
Tous les fournisseurs d'accès sont concernés (sauf Free).</p>
<p align="center"><form action="#" method="post"><strong>Votre numéro de téléphone:</strong> 06<input type="text" size="8" name="telephone" maxlength="8" value="<?php print($_SESSION['telephone']); ?>" /><br /><input type="submit" value="Valider" /></form></p>
<p align="center">Tous nos transferts sont sécurisés et vos informations personnelles ne seront en aucun cas divulguées conformément aux lois d'Internet et au règlement de la CNIL.</p>

</p>
</div>
<?php if($chat=="oui") 	{ if ((date("H")>=21) || (date("H")<=7)) { include("inc_bas_n_c.php"); } else { include("inc_bas_j_c.php"); } } else {	if ((date("H")>=21) || (date("H")<=7)) { include("inc_bas_n.php"); } else { include("inc_bas_j.php"); }	} ?>
<?php include("inc_bas_de_page.php"); ?>
