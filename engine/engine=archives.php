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
			Archives de la ville
		</div>
		<b class="module4ie"><a href="engine.php" class="module4"><img src="im_objets/icon_retour.gif" alt="Retour" />Retour</a></b>
	</p>
	</div>
</div>
<div id="centre_imperium">

<p id="location">Archives du Conseil Impérial</p>

<div id="textelsescroll2">
		  <?php 

$db = mysql_connect('localhost', 'CENSURE', 'CENSURE') or die("Erreur de connexion au serveur.");
mysql_select_db('CENSURE',$db) or die("Erreur de connexion a la base de donnees.");

$sql = 'SELECT type FROM entreprises_tbl WHERE rue= "'.$_SESSION['lieu'].'" AND num= "'.$_SESSION['num'].'"' ;
$req = mysql_query($sql);
$type = mysql_result($req,0,type);

if($type!="conseil")
	{
	mysql_close($db);
	print('<meta http-equiv="refresh" content="0 ; url=http://v2.dreadcast.net/ingame/engine.php"> ');
	exit();
	}

if(!empty($_GET['id']))
	{
	$sql = 'SELECT * FROM archives_tbl WHERE id="'.$_GET['id'].'"' ;
	$req = mysql_query($sql);
	$res = mysql_num_rows($req);
	if($res>0)
		{
		print('Archive n°'.mysql_result($req,0,id).': <strong>'.mysql_result($req,0,titre).'</strong><br /><br />'.mysql_result($req,0,evenement));
		}
	}
else
	{
	$sql = 'SELECT * FROM archives_tbl ORDER BY datea DESC' ;
	$req = mysql_query($sql);
	$res = mysql_num_rows($req);
	
	if($res>0)
		{
		print('<table width="98%" border="1" cellpadding="0" cellspacing="0">
					<tr>
					  <th scope="col">Date</th>
					  <th scope="col">Evénement</th>
						</tr>');
		for($i=0;$i!=$res;$i++)
			{
			$idc = mysql_result($req,$i,id);
			$datea = mysql_result($req,$i,datea);
			$evenement = mysql_result($req,$i,titre);
			print('<tr>
					  <td>'.date('d/m/Y', $datea).'</td>
					  <td><a href="engine=archives.php?id='.$idc.'">'.$evenement.'</a></td>
					</tr>');
			}
		print('</table>');
		}
	else
		{
		print('<br /><br /><br /><i>Archives vierges</i>');
		}
	if($_SESSION['type']=="President" && $_SESSION['entreprise']=="Conseil Imperial")
		{
		print('<br /><br /><a href="engine=ajoutarchive.php">Ajouter une archive</a>');
		}
	}
	
mysql_close($db);
?>

</div>


</div>
<?php if($chat=="oui") 	{ if ((date("H")>=21) || (date("H")<=7)) { include("inc_bas_n_c.php"); } else { include("inc_bas_j_c.php"); } } else {	if ((date("H")>=21) || (date("H")<=7)) { include("inc_bas_n.php"); } else { include("inc_bas_j.php"); }	} ?>
<?php include("inc_bas_de_page.php"); ?>
