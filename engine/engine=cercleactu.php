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

$sql = 'SELECT * FROM cercles_tbl WHERE pseudo= "'.$_SESSION['pseudo'].'"' ;
$req = mysql_query($sql);
$resc = mysql_num_rows($req);
if($resc>0)
	{
	$_SESSION['cercle'] = mysql_result($req,0,cercle);
	$_SESSION['postec'] = mysql_result($req,0,poste);
	$sql4 = 'SELECT * FROM `c_'.str_replace(" ","_",''.$_SESSION['cercle'].'').'_tbl` WHERE poste= "'.$_SESSION['postec'].'"' ;
	$req4 = mysql_query($sql4);
	$bddc = mysql_result($req4,0,bdd);
	}
else
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
			Actualité du cercle
		</div>
		</p>
	</div>
</div>
<div id="centre">
<p>

<table width="450" height="25" bgcolor="#FFFFFF" border="1" align="center" cellpadding="0" cellspacing="0">
	<tr>
	  <td bgcolor="#CACACA"><div align="center"><a href="engine=cercle.php">Votre cercle</a></div></td>
	  <td bgcolor="#CACACA"><div align="center"><a href="engine=cerclestatut.php">Votre statut</a></div></td>
	  <td bgcolor="#CACACA"><div align="center"><a href="engine=cerclemembres.php">Membres du cercle</a></div></td>
	  <td><div align="center"><strong>Actualité du cercle</strong></div></td>
	</tr>
</table>
<table width="450" height="250" bgcolor="#FFFFFF" border="1" align="center" cellpadding="0" cellspacing="0">
	<tr>
	  <td>
<?php
$db = mysql_connect('localhost', 'CENSURE', 'CENSURE') or die("Erreur de connexion au serveur des messages.");
mysql_select_db('CENSURE',$db) or die("Erreur de connexion a la base de donnees des messages.");

if($_GET['id']=="")
	{
	$sql = 'SELECT * FROM cerclesnews_tbl WHERE cercle= "'.$_SESSION['cercle'].'" ORDER BY moment DESC LIMIT 1' ;
	$req = mysql_query($sql);
	$resc = mysql_num_rows($req);

	if($resc>0)
		{
		$ida = mysql_result($req,0,id);
		$sqlcom = 'SELECT * FROM cerclescom_tbl WHERE nouvelle= "'.$ida.'"' ;
		$reqcom = mysql_query($sqlcom);
		$rescom = mysql_num_rows($reqcom);
		$posteur = mysql_result($req,0,posteur);
		$sql1 = 'SELECT avatar FROM principal_tbl WHERE pseudo= "'.$posteur.'"' ;
		$req1 = mysql_query($sql1);
		$avatarposteur = mysql_result($req1,0,avatar);
		
		if(!(ereg("http",$avatarposteur) or ereg("ftp",$avatarposteur))) $avatarposteur = 'avatars/'.$avatarposteur;
		
		print('<br /><table width="90%" cellpadding="0" cellspacing="0"><tr><td><div align="center"><img src="'.$avatarposteur.'" border="0" width="70" height="70" /></div></td><td><div align="center"><i>Nouvelle de '.$posteur.' le '.date('d/m/Y à H\hi', mysql_result($req,0,moment)).'</i><br /><br /><strong>'.mysql_result($req,0,titre).'</strong>');
		if((ereg("tout",$bddc)) || (ereg("actualite",$bddc)))
			{
			print(' (<a href="engine=cerclesupprimera.php?id='.mysql_result($req,0,id).'">Supprimer</a>)<br />');
			}
		print('</div></td></tr></table><p class="cercleactu2"><br />'.mysql_result($req,0,contenu).'<br /><br /><a href="engine=cerclecom.php?id='.mysql_result($req,0,id).'">Voir les commentaires de cette nouvelle</a> ('.$rescom.')<br /><a href="engine=cerclearchives.php">Consulter les archives</a>');
		if((ereg("tout",$bddc)) || (ereg("actualite",$bddc)))
			{
			print('<br />(<a href="engine=cerclemodifiera.php?id='.mysql_result($req,0,id).'">Modifier cette nouvelle</a>)');
			print('<br />(<a href="engine=cercleajoutera.php">Ajouter une nouvelle</a>)<br />');
			}
		print('</p>');
		}
	else
		{
		print('<center><strong>Aucune nouvelle dans ce cercle.</strong>');
		if((ereg("tout",$bddc)) || (ereg("actualite",$bddc)))
			{
			print('<br />(<a href="engine=cercleajoutera.php">Ajouter une nouvelle</a>)<br />');
			}
		print('</center>');
		}
	}
else
	{
	$sql = 'SELECT * FROM cerclesnews_tbl WHERE cercle= "'.$_SESSION['cercle'].'" AND id="'.$_GET['id'].'"' ;
	$req = mysql_query($sql);
	$resc = mysql_num_rows($req);
	$sqlcom = 'SELECT * FROM cerclescom_tbl WHERE nouvelle= "'.$_GET['id'].'"' ;
	$reqcom = mysql_query($sqlcom);
	$rescom = mysql_num_rows($reqcom);

	if($resc>0)
		{
		$posteur = mysql_result($req,0,posteur);
		$sql1 = 'SELECT avatar FROM principal_tbl WHERE pseudo= "'.$posteur.'"' ;
		$req1 = mysql_query($sql1);
		$avatarposteur = mysql_result($req1,0,avatar);
		print('<br /><table width="90%" cellpadding="0" cellspacing="0"><tr><td><div align="center"><img src="avatars/'.$avatarposteur.'" border="0" /></div></td><td><div align="center"><i>Nouvelle de '.$posteur.' le '.date('d/m/Y à H\hi', mysql_result($req,0,moment)).'</i><br /><br /><strong>'.mysql_result($req,0,titre).'</strong>');
		if((ereg("tout",$bddc)) || (ereg("actualite",$bddc)))
			{
			print(' (<a href="engine=cerclesupprimera.php?id='.mysql_result($req,0,id).'">Supprimer</a>)<br />');
			}
		print('</div></td></tr></table><p class="cercleactu2"><br />'.mysql_result($req,0,contenu).'<br /><br /><a href="engine=cerclecom.php?id='.mysql_result($req,0,id).'">Voir les commentaires de cette nouvelle</a> ('.$rescom.')<br /><a href="engine=cerclearchives.php">Consulter les archives</a>');
		if((ereg("tout",$bddc)) || (ereg("actualite",$bddc)))
			{
			print('<br />(<a href="engine=cerclemodifiera.php?id='.mysql_result($req,0,id).'">Modifier cette nouvelle</a>)');
			print('<br />(<a href="engine=cercleajoutera.php">Ajouter une nouvelle</a>)<br />');
			}
		print('</p>');
		}
	else
		{
		print('<meta http-equiv="refresh" content="0 ; url=http://v2.dreadcast.net/ingame/engine=cercle.php"> ');
		exit();
		}
	}

mysql_close($db);
?>
	 </td>
	</tr>
</table>


</p>
</div>
<?php if($chat=="oui") 	{ if ((date("H")>=21) || (date("H")<=7)) { include("inc_bas_n_c.php"); } else { include("inc_bas_j_c.php"); } } else {	if ((date("H")>=21) || (date("H")<=7)) { include("inc_bas_n.php"); } else { include("inc_bas_j.php"); }	} ?>
<?php include("inc_bas_de_page.php"); ?>
