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
		<b class="module4ie"><a href="engine=cercleactu.php?id=<?php print(''.$_GET['id'].''); ?>" class="module4"><img src="im_objets/icon_retour.gif" alt="Retour" />Retour</a></b>
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

if($_GET['id']!="")
	{
	$sql = 'SELECT * FROM cerclescom_tbl WHERE nouvelle= "'.$_GET['id'].'" ORDER BY moment DESC' ;
	$req = mysql_query($sql);
	$resc = mysql_num_rows($req);
	
	if($resc>0)
		{
		print('<br /><div class="cercleactu"><table width="90%" cellpadding="1" cellspacing="1">');
		for($i=0;$i!=$resc;$i++)
			{
			$posteur = mysql_result($req,$i,auteur);
			$sql1 = 'SELECT avatar FROM principal_tbl WHERE pseudo= "'.$posteur.'"' ;
			$req1 = mysql_query($sql1);
			$res1 = mysql_num_rows($req1);
			if($res1>0)
				{
				$avatarposteur = mysql_result($req1,0,avatar);
				print('<tr><td>');
				if((ereg("http",$avatarposteur)) or (ereg("ftp",$avatarposteur)))
					{
					print('<img src="'.$avatarposteur.'" border="1px" width="70px" />');
					}
				else
					{
					print('<img src="avatars/'.$avatarposteur.'" border="0" />');
					}
				print('</td><td><i>Commentaire de '.$posteur.' le '.date('d/m/Y à H\hi', mysql_result($req,$i,moment)).'</i><br /><br />'.mysql_result($req,$i,commentaire).'</td></tr>');
				}
			}
		print('</table></div>');
		if((ereg("tout",$bddc)) || (ereg("commentaires",$bddc)))
			{
			print('<div align="center">(<a href="engine=cercleajouterc.php?id='.$_GET['id'].'">Ajouter un commentaire</a>)</div>');
			}
		}
	else
		{
		print('<div align="center"><strong>Aucun commentaire sur cette nouvelle.</strong>');
		if((ereg("tout",$bddc)) || (ereg("commentaires",$bddc)))
			{
			print('<br />(<a href="engine=cercleajouterc.php?id='.$_GET['id'].'">Ajouter un commentaire</a>)<br />');
			}
		print('</div>');
		}
	}
else
	{
	print('<meta http-equiv="refresh" content="0 ; url=http://v2.dreadcast.net/ingame/engine=cercle.php"> ');
	exit();
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
