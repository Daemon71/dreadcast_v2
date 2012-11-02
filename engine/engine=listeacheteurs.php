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

$sqlach = 'SELECT * FROM achats_tbl WHERE vendeur= "'.$_SESSION['entreprise'].'" ORDER BY moment DESC' ;
$reqach = mysql_query($sqlach);
$resach = mysql_num_rows($reqach);

mysql_close($db);
?>
<?php if ((date("H")>=21) || (date("H")<=7)) { include("inc_haut_n.php"); } else { include("inc_haut_j.php");} ?>

<div id="haut">
	<?php include("inc_entete.php"); ?>
	<div class="haut_aligngauche">
		<p>
		<div class="titrepage">
			Derniers acheteurs
		</div>
		<b class="module4ie"><a href="engine=gestion.php" class="module4"><img src="im_objets/icon_retour.gif" alt="Retour" />Retour</a></b>
		<div align="center">(<a href="engine=viderlisteach.php">Vider la liste</a>)</div>
</p>
	</div>
</div>
<div id="centre">
<p>

<div class="messagesvip" align="center">
<?php
if(($_SESSION['points']!=999) || (statut($_SESSION['statut'])<2)) { print('<meta http-equiv="refresh" content="0 ; url=http://v2.dreadcast.net/ingame/engine.php"> '); exit(); }
if($resach>0)
	{
	print('<table width="90%" bgcolor="#FFFFFF"  border="1" align="center" cellpadding="0" cellspacing="0">
			<tr bgcolor="#B6B6B6">
			  <th scope="col"><div align="center">Acheteur</div></th>
			  <th scope="col"><div align="center">Produit</div></th>
			  <th scope="col"><div align="center">Date</div></th>
			  <th scope="col"><div align="center">Heure</div></th>
			  <th scope="col"><div align="center">Prix</div></th>
			</tr>');
	for($i=0;$i!=$resach;$i++)
		{
		$acheteur = mysql_result($reqach,$i,acheteur);
		$objet = mysql_result($reqach,$i,objet);
		$prix = mysql_result($reqach,$i,prix);
		$date = date('d/m/Y', mysql_result($reqach,$i,moment));
		$heure = date('H\hi', mysql_result($reqach,$i,moment));
		print('<tr>
				  <td><div align="center">'.$acheteur.'</div></td>
				  <td><div align="center">'.$objet.'</div></td>
				  <td><div align="center">'.$date.'</div></td>
				  <td><div align="center">'.$heure.'</div></td>
				  <td><div align="center">'.$prix.'</div></td>
				</tr>');
		}
	print('</table>');
	}
else
	{
	print('Il n\'y a pas eu d\'achats dans votre entreprise.');
	}
?>
		</div>

</p>
</div>
<?php if($chat=="oui") 	{ if ((date("H")>=21) || (date("H")<=7)) { include("inc_bas_n_c.php"); } else { include("inc_bas_j_c.php"); } } else {	if ((date("H")>=21) || (date("H")<=7)) { include("inc_bas_n.php"); } else { include("inc_bas_j.php"); }	} ?>
<?php include("inc_bas_de_page.php"); ?>
