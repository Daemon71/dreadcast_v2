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
	$sql4 = 'SELECT bdd FROM `c_'.str_replace(" ","_",''.$_SESSION['cercle'].'').'_tbl` WHERE poste= "'.$_SESSION['postec'].'"' ;
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
			Membres du cercle
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
	  <td><div align="center"><strong>Membres du cercle</strong></div></td>
	  <td bgcolor="#CACACA"><div align="center"><a href="engine=cercleactu.php">Actualité du cercle</a></div></td>
	</tr>
</table>
<table width="450" height="250" bgcolor="#FFFFFF" border="1" align="center" cellpadding="0" cellspacing="0">
	<tr>
	  <td>
	  <br />
	    <div id="cerclemembres">
		<table width="430" bgcolor="#FFFFFF" border="0" align="center" cellpadding="0" cellspacing="0">
<?php

$db = mysql_connect('localhost', 'CENSURE', 'CENSURE') or die("Erreur de connexion au serveur des messages.");
mysql_select_db('CENSURE',$db) or die("Erreur de connexion a la base de donnees des messages.");

$sql = 'SELECT * FROM cercles_tbl WHERE cercle= "'.$_SESSION['cercle'].'"' ;
$req = mysql_query($sql);
$res = mysql_num_rows($req);

for($i=0;$i!=$res;$i++)
	{
	$sql1 = 'SELECT avatar,connec,type,entreprise FROM principal_tbl WHERE pseudo= "'.mysql_result($req,$i,pseudo).'"' ;
	$req1 = mysql_query($sql1);
	print('<tr>
		<td width="100">
		<center>');
		if((ereg("http",mysql_result($req1,0,avatar))) or (ereg("ftp",mysql_result($req1,0,avatar))))
			{
			print('<img src="'.mysql_result($req1,0,avatar).'" border="1px" width="70px" />');
			}
		else
			{
			print('<img src="avatars/'.mysql_result($req1,0,avatar).'" border="0" />');
			}
		print('<br /><br /></center>
		</td>
		<td>
		<center><a href="engine=contacter.php?cible='.mysql_result($req,$i,pseudo).'"><strong>'.mysql_result($req,$i,pseudo).'</strong></a> ');
		if(mysql_result($req1,0,connec)=="oui")
			{
			print('<img src="im_objets/eclair1.gif" border="0" />');
			}
		if((ereg("tout",$bddc)) || (ereg("renvoyernq",$bddc)))
			{
			print(' (<a href="engine=cerclerenvoyer.php?'.mysql_result($req,$i,pseudo).'">Renvoyer</a>)');
			}
		print('<br />'.mysql_result($req,$i,poste).'');
		if((ereg("tout",$bddc)) || (ereg("promovoir",$bddc)))
			{
			print(' (<a href="engine=cerclechangerposte.php?'.mysql_result($req,$i,pseudo).'">Changer</a>)');
			}
		if(mysql_result($req1,0,type)!="Aucun")
			{
			print('<br /><br />'.mysql_result($req1,0,type).', '.mysql_result($req1,0,entreprise).'');
			}
		else
			{
			print('<br /><br />Pas de travail actuellement');
			}
		print('</center>
		</td>
		</tr>');
	}

mysql_close($db);

?>
		</table>
		</div>
	  </td>
	</tr>
</table>


</p>
</div>
<?php if($chat=="oui") 	{ if ((date("H")>=21) || (date("H")<=7)) { include("inc_bas_n_c.php"); } else { include("inc_bas_j_c.php"); } } else {	if ((date("H")>=21) || (date("H")<=7)) { include("inc_bas_n.php"); } else { include("inc_bas_j.php"); }	} ?>
<?php include("inc_bas_de_page.php"); ?>
