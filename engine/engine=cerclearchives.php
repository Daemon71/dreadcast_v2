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
		<b class="module4ie"><a href="engine=cercleactu.php" class="module4"><img src="im_objets/icon_retour.gif" alt="Retour" />Retour</a></b>
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
	  <td><div align="center">
<?php
$db = mysql_connect('localhost', 'CENSURE', 'CENSURE') or die("Erreur de connexion au serveur des messages.");
mysql_select_db('CENSURE',$db) or die("Erreur de connexion a la base de donnees des messages.");

$sql = 'SELECT * FROM cerclesnews_tbl WHERE cercle= "'.$_SESSION['cercle'].'" ORDER BY moment DESC' ;
$req = mysql_query($sql);
$resc = mysql_num_rows($req);

if($resc>0)
	{
	print('Archives des nouvelles du cercle <strong>'.$_SESSION['cercle'].'</strong>:<br /><br /><div class="cercleactu"><table width="95%" bgcolor="#FFFFFF" border="1" align="center" cellpadding="0" cellspacing="0">
	<tr bgcolor="#B6B6B6">
	<th scope="col"><div align="center">De</div></th>
	<th scope="col"><div align="center">Titre</div></th>
	<th scope="col"><div align="center">Date</div></th>
	<th scope="col"><div align="center">Heure</div></th>
	<th scope="col"><div align="center">Commentaires</div></th>
	</tr>');
		
	for($i=0; $i != $resc ; $i++) 
		{ 
		$idm = mysql_result($req,$i,id);
		$auteur = mysql_result($req,$i,posteur);
		$titre = mysql_result($req,$i,titre);
		$moment = mysql_result($req,$i,moment);
		if($titre=="")
			{
			$titre = "Aucun";
			}
		$sqlcom = 'SELECT * FROM cerclescom_tbl WHERE nouvelle= "'.$idm.'"' ;
		$reqcom= mysql_query($sqlcom);
		$rescom = mysql_num_rows($reqcom);
		print('<tr>
		<td><div align="center">'.$auteur.'</strong></div></td>
		<td><div align="center"><a href="engine=cercleactu.php?id='.$idm.'">'.$titre.'</a></div></td>
		<td><div align="center">'.date('d/m/Y', $moment).'</div></td>
		<td><div align="center">'.date('H\hi', $moment).'</div></td>
		<td><div align="center">'.$rescom.'</div></td>
		</tr>');
		}
	print('</table></div>');
	}
else
	{
	print('<div align="center"><strong>Aucune nouvelle dans ce cercle.</strong></div>');
	}

mysql_close($db);
?>
	 </div></td>
	</tr>
</table>


</p>
</div>
<?php if($chat=="oui") 	{ if ((date("H")>=21) || (date("H")<=7)) { include("inc_bas_n_c.php"); } else { include("inc_bas_j_c.php"); } } else {	if ((date("H")>=21) || (date("H")<=7)) { include("inc_bas_n.php"); } else { include("inc_bas_j.php"); }	} ?>
<?php include("inc_bas_de_page.php"); ?>
