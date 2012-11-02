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

$sql = 'SELECT nom,type,ouvert FROM entreprises_tbl WHERE rue= "'.$_SESSION['lieu'].'" AND num= "'.$_SESSION['num'].'"' ;
$req = mysql_query($sql);
$type = mysql_result($req,0,type);
$noment = mysql_result($req,0,nom);
$ouvert = mysql_result($req,0,ouvert);

if($type!="ventes aux encheres")
	{
	print('<meta http-equiv="refresh" content="0 ; url=http://v2.dreadcast.net/ingame/engine.php"> ');
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
			Ench&egrave;res gagn&eacute;es 
		</div>
		<b class="module4ie"><a href="engine.php" class="module4"><img src="im_objets/icon_retour.gif" alt="Retour" />Retour</a></b>
		</p>
	</div>
</div>
<div id="centre_encheres">

<p id="location2"><a href="engine=vaedeposer.php">D&eacute;poser un objet</a> | <a href="engine=vae.php">Consulter les ventes</a></p>

<br /><br /><br /><div id="boutique">

<?php                  
$db = mysql_connect('localhost', 'CENSURE', 'CENSURE') or die("Erreur de connexion au serveur des messages.");
mysql_select_db('CENSURE',$db) or die("Erreur de connexion a la base de donnees des messages.");

$sql = 'SELECT * FROM vente_tbl WHERE enchere= "0" AND buyout= "0" AND acheteur= "'.$_SESSION['pseudo'].'"' ;
$req = mysql_query($sql);
$res = mysql_num_rows($req);

print('<table cellpadding="0" cellspacing="0">');
for($i=0 ; $i != $res ; $i++)
	{
	if(mysql_result($req,$i,fin)==0)
		{
		$sqlo = 'SELECT * FROM objets_tbl WHERE nom= "'.mysql_result($req,$i,objet).'"' ;
		$reqo = mysql_query($sqlo);
		$image = mysql_result($reqo,0,image);
		$typeo = mysql_result($reqo,0,type);
		if($i/2 == round($i/2))
			{
			print('<tr class="color1">');
			}
		else
			{
			print('<tr class="color2">');
			}
		print('<td><div align="center">N&deg;'.($i+1).'<br /><a href="info=objet.php?'.mysql_result($req,$i,objet).'" target="_blank"><img src="im_objets/'.$image.'" border="0" height="35" width="35"></a></td></div>');
		print('<td><div align="center"><strong>'.mysql_result($req,$i,objet).'</strong><br />');
		if($typeo=="objet")
			{
			print('<i>Objet</i>');
			}
		elseif($typeo=="oa")
			{
			print('<i>Objet avanc&eacute;</i>');
			}
		elseif($typeo=="armestir")
			{
			print('<i>Arme de tir</i>');
			}
		elseif(($typeo=="tissu") || ($typeo=="soie") || ($typeo=="cristal"))
			{
			print('<i>V&ecirc;tement</i>');
			}
		elseif($typeo=="armesprim")
			{
			print('<i>Arme de corps &agrave; corps avanc&eacute;e</i>');
			}
		elseif($typeo=="acac")
			{
			print('<i>Arme de corps &agrave; corps</i>');
			}
		elseif($typeo=="armesav")
			{
			print('<i>Arme avanc&eacute;e</i>');
			}
		
		print('</div></td>');
		print('<td><div align="center"><a href="engine=vaeretirer.php?id='.mysql_result($req,$i,id).'" class="lien">Retirer</a></div></td>');
		print('</tr>');
		}
	}
print('</table>');


mysql_close($db);

?>

</div>
</div>
<?php if($chat=="oui") 	{ if ((date("H")>=21) || (date("H")<=7)) { include("inc_bas_n_c.php"); } else { include("inc_bas_j_c.php"); } } else {	if ((date("H")>=21) || (date("H")<=7)) { include("inc_bas_n.php"); } else { include("inc_bas_j.php"); }	} ?>
<?php include("inc_bas_de_page.php"); ?>
