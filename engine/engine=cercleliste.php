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

$sql = 'SELECT id FROM cercles_tbl WHERE pseudo= "'.$_SESSION['pseudo'].'"' ;
$req = mysql_query($sql);
$resc = mysql_num_rows($req);
if($resc>0)
	{
	print('<meta http-equiv="refresh" content="0 ; url=http://v2.dreadcast.net/ingame/engine=cercle.php"> ');
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
			Votre cercle
		</div>
<?php
if($_SERVER['QUERY_STRING']!="")
	{
	print('<b class="module4ie"><a href="engine=cercleliste.php" class="module4"><img src="im_objets/icon_retour.gif" alt="Retour" />Retour</a></b>
');
	}
else
	{
	print('<b class="module4ie"><a href="engine=cercle.php" class="module4"><img src="im_objets/icon_retour.gif" alt="Retour" />Retour</a></b>
');
	}
?>
		</p>
	</div>
</div>
<div id="centre">
<p>

		<table width="150" height="25" bgcolor="#FFFFFF" border="1" align="center" cellpadding="0" cellspacing="0">
			<tr>
				<td><div align="center"><strong>Choisir un cercle</strong></div></td>
			</tr>
		</table>
		<table width="450" height="250" bgcolor="#FFFFFF" border="1" align="center" cellpadding="0" cellspacing="0">
			<tr><td><div id="cerclemembres">
<?php
$db = mysql_connect('localhost', 'CENSURE', 'CENSURE') or die("Erreur de connexion au serveur des messages.");
mysql_select_db('CENSURE',$db) or die("Erreur de connexion a la base de donnees des messages.");

if($_SERVER['QUERY_STRING']!="")
	{
	$cible = str_replace("%20"," ",''.$_SERVER['QUERY_STRING'].'');
	$sql = 'SELECT * FROM cerclesliste_tbl WHERE nom= "'.$cible.'"' ;
	$req = mysql_query($sql);
	$res = mysql_num_rows($req);
	if($res>0)
		{
		$sql1 = 'SELECT id FROM cercles_tbl WHERE cercle= "'.$cible.'"' ;
		$req1 = mysql_query($sql1);
		$res1 = mysql_num_rows($req1);
		print('<table width="450" height="250" align="center" cellpadding="0" cellspacing="0">
			<tr><td width="150"><div align="center">');
			
		print('<img src="'.mysql_result($req,0,logo).'" border="1px" height="100px" width="100px" /><br /><br />');
		
		if(mysql_result($req,0,type)=="Politique")
			{
			print('<strong>Cercle politique</strong>');
			}
		else
			{
			print('<strong>Cercle associatif</strong>');
			}
		print('<br /><br /><strong>Siège:</strong> <a href="engine=go.php?num='.mysql_result($req,0,num).'&rue='.mysql_result($req,0,rue).'">'.mysql_result($req,0,num).' '.mysql_result($req,0,rue).'</a>');
		print('<br /><br /><strong>Capital:</strong> '.mysql_result($req,0,capital).' Crédits');
		print('<br /><strong>Membres:</strong> '.$res1.'');
		print('</div></td><td><div align="center">[<strong>'.$cible.'</strong>]<br /><br />'.mysql_result($req,0,description).'</div></td></tr></table>');
		}
	}
else
	{
	$sql = 'SELECT nom FROM cerclesliste_tbl WHERE type= "politique" AND public= "1"' ;
	$req = mysql_query($sql);
	$res = mysql_num_rows($req);
	print('<table width="90%"><tr><td><center>');
	print('<strong>Cercles politiques :</strong><br /><br />');
	for($i=0;$i!=$res;$i++)
		{
		print('<a href="engine=cercleliste.php?'.mysql_result($req,$i,nom).'">'.mysql_result($req,$i,nom).'</a><br />');
		}
	$sql = 'SELECT nom FROM cerclesliste_tbl WHERE type= "associatif" AND public= "1"' ;
	$req = mysql_query($sql);
	$res = mysql_num_rows($req);
	print('</center></td><td><center><strong>Cercles associatifs :</strong><br /><br />');
	for($i=0;$i!=$res;$i++)
		{
		print('<a href="engine=cercleliste.php?'.mysql_result($req,$i,nom).'">'.mysql_result($req,$i,nom).'</a><br />');
		}
	print('</center></td></tr></table>');
	}

mysql_close($db);
?>
		</div></td></tr></table>


</p>
</div>
<?php if($chat=="oui") 	{ if ((date("H")>=21) || (date("H")<=7)) { include("inc_bas_n_c.php"); } else { include("inc_bas_j_c.php"); } } else {	if ((date("H")>=21) || (date("H")<=7)) { include("inc_bas_n.php"); } else { include("inc_bas_j.php"); }	} ?>
<?php include("inc_bas_de_page.php"); ?>
