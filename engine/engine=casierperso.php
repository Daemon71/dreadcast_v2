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
			Votre casier judiciaire
		</div>
		<b class="module4ie"><a href="engine.php" class="module4"><img src="im_objets/icon_retour.gif" alt="Retour" />Retour</a></b>
	</p>
	</div>
</div>
<div id="centre_imperium">

<p id="location">Chambre des Lois de la ville</p>

<div id="textelsescroll2">
		  <?php 

$db = mysql_connect('localhost', 'CENSURE', 'CENSURE') or die("Erreur de connexion au serveur.");
mysql_select_db('CENSURE',$db) or die("Erreur de connexion a la base de donnees.");

$sql = 'SELECT type FROM entreprises_tbl WHERE rue= "'.$_SESSION['lieu'].'" AND num= "'.$_SESSION['num'].'"' ;
$req = mysql_query($sql);
$type = mysql_result($req,0,type);

if($type!="chambre")
	{
	mysql_close($db);
	print('<meta http-equiv="refresh" content="0 ; url=http://v2.dreadcast.net/ingame/engine.php"> ');
	exit();
	}

$sql = 'SELECT * FROM casiers_tbl WHERE pseudo= "'.$_SESSION['pseudo'].'" ORDER BY datea DESC' ;
$req = mysql_query($sql);
$res = mysql_num_rows($req);

if($res>0)
	{
	print('<table width="98%" border="1" cellpadding="0" cellspacing="0">
				<tr>
				  <th scope="col">Date</th>
				  <th scope="col">Agent</th>
				  <th scope="col">Motif</th>
				</tr>');
	for($i=0;$i!=$res;$i++)
		{
		$idc = mysql_result($req,$i,id);
		$datea = mysql_result($req,$i,datea);
		$policier = mysql_result($req,$i,policier);
		$motif = mysql_result($req,$i,raison);
		print('<tr>
				  <td>'.date('d/m/Y à H\hi', $datea).'</td>
				  <td>'.$policier.'</td>
				  <td>'.$motif.'</td>
				</tr>');
		}
	print('</table>');
	}
else
	{
	print('<br /><br /><br /><i>Casier judiciaire vierge</i>');
	}

mysql_close($db);

?>

</div>


</div>
<?php if($chat=="oui") 	{ if ((date("H")>=21) || (date("H")<=7)) { include("inc_bas_n_c.php"); } else { include("inc_bas_j_c.php"); } } else {	if ((date("H")>=21) || (date("H")<=7)) { include("inc_bas_n.php"); } else { include("inc_bas_j.php"); }	} ?>
<?php include("inc_bas_de_page.php"); ?>
