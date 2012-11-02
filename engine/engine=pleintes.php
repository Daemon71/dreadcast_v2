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
			Plaintes
		</div>
		<b class="module4ie"><a href="engine=services.php" class="module4"><img src="im_objets/icon_retour.gif" alt="Retour" />Retour</a></b>
	</p>
	</div>
</div>
<div id="centre_imperium">

<p id="location">R&eacute;clamations des citoyens</p>

<?php

$db = mysql_connect('localhost', 'CENSURE', 'CENSURE') or die("Erreur de connexion au serveur des messages.");
mysql_select_db('CENSURE',$db) or die("Erreur de connexion a la base de donnees des messages.");

$sql = 'SELECT type,entreprise,points FROM principal_tbl WHERE id= "'.$_SESSION['id'].'"' ;
$req = mysql_query($sql);
$_SESSION['poste'] = mysql_result($req,0,type); 
$_SESSION['entreprise'] = mysql_result($req,0,entreprise); 
$_SESSION['points'] = mysql_result($req,0,points); 

$sql = 'SELECT bdd FROM `e_'.str_replace(" ","_",''.$_SESSION['entreprise'].'').'_tbl` WHERE poste= "'.$_SESSION['poste'].'"' ;
$req = mysql_query($sql);
$bdd = mysql_result($req,0,bdd); 

$sql = 'SELECT type,num,rue FROM entreprises_tbl WHERE nom= "'.$_SESSION['entreprise'].'"' ;
$req = mysql_query($sql);
$type = mysql_result($req,0,type); 
$rueent = mysql_result($req,0,rue); 
$nument = mysql_result($req,0,num); 

$sql = 'SELECT type FROM entreprises_tbl WHERE nom= "'.$_SESSION['entreprise'].'"' ;
$req = mysql_query($sql);
$type = mysql_result($req,0,type); 

if(($type!="police") && ($type!="di2rco")) { print('<meta http-equiv="refresh" content="0 ; url=http://v2.dreadcast.net/ingame/engine.php"> '); exit(); }

if($bdd=="") { print('<meta http-equiv="refresh" content="0 ; url=http://v2.dreadcast.net/ingame/engine.php"> '); exit(); } if(($type!="police") && ($type!="di2rco")) { print('<meta http-equiv="refresh" content="0 ; url=http://v2.dreadcast.net/ingame/engine.php"> '); exit(); }

if((ucwords($_SESSION['rue'])!=ucwords($rueent)) || ($_SESSION['num']!=$nument))
	{
	print('<meta http-equiv="refresh" content="0 ; url=http://v2.dreadcast.net/ingame/engine.php">'); 
	}

$sql = 'SELECT * FROM pleintes_tbl ORDER BY moment DESC' ;
$req = mysql_query($sql);
$res = mysql_num_rows($req);

print('<div id="textelsescroll">');

if($res!=0)
	{
	print('<table width="98%" border="1" cellpadding="0" cellspacing="0">
				<tr bgcolor="#B6B6B6">
				  <th scope="col"><div align="center">De</div></th>
				  <th scope="col"><div align="center">Message</div></th>
				  <th scope="col"><div align="center">Vu</div></th>
				  <th scope="col"><div align="center">Responsable</div></th>
				</tr>');
	
	for($i=0; $i != $res ; $i++) 
		{ 
		$idm = mysql_result($req,$i,id);
		$auteur = mysql_result($req,$i,pseudo);
		$vu = mysql_result($req,$i,vu);
		if(mysql_result($req,$i,policier)!="") $policier = mysql_result($req,$i,policier);
		else $policier = "Aucun";
		$message = substr(''.mysql_result($req,$i,msg).'', 0, 30);
		print('<tr>
				  <td><div align="center"><a href="engine=consultpleinte.php?id='.$idm.'">'.$auteur.'</a></div></td>
				  <td><div align="center">'.$message.' ...</div></td>
				  <td><div align="center">'.$vu.'</div></td>
				  <td><div align="center">'.$policier.'</div></td>
				</tr>');
		}
	print('</table>');
	}
else
	{
	print('<strong>Il n\'y a aucune plainte.</strong>');
	}
print('</div>');

mysql_close($db);

?>

</div>
<?php if($chat=="oui") 	{ if ((date("H")>=21) || (date("H")<=7)) { include("inc_bas_n_c.php"); } else { include("inc_bas_j_c.php"); } } else {	if ((date("H")>=21) || (date("H")<=7)) { include("inc_bas_n.php"); } else { include("inc_bas_j.php"); }	} ?>
<?php include("inc_bas_de_page.php"); ?>
