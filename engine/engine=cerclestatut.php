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
	$descriptioncp = mysql_result($req4,0,description);
	$sql = 'SELECT * FROM cerclesliste_tbl WHERE nom= "'.$_SESSION['cercle'].'"' ;
	$req = mysql_query($sql);
	$ruec = mysql_result($req,0,rue);
	$numc = mysql_result($req,0,num);
	$sql = 'SELECT code FROM lieu_tbl WHERE num= "'.$numc.'" AND rue= "'.$ruec.'"' ;
	$req = mysql_query($sql);
	$digicercle = mysql_result($req,0,code);
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
			Votre statut
		</div>
		</p>
	</div>
</div>
<div id="centre">
<p>

<table width="450" height="25" bgcolor="#FFFFFF" border="1" align="center" cellpadding="0" cellspacing="0">
	<tr>
	  <td bgcolor="#CACACA"><div align="center"><a href="engine=cercle.php">Votre cercle</a></div></td>
	  <td><div align="center"><strong>Votre statut</strong></div></td>
	  <td bgcolor="#CACACA"><div align="center"><a href="engine=cerclemembres.php">Membres du cercle</a></div></td>
	  <td bgcolor="#CACACA"><div align="center"><a href="engine=cercleactu.php">Actualité du cercle</a></div></td>
	</tr>
</table>
<table width="450" height="250" bgcolor="#FFFFFF" border="1" align="center" cellpadding="0" cellspacing="0">
	<tr>
		<td><div id="cerclemembres">
			<?php
			print('<strong>'.$_SESSION['cercle'].'</strong><br />
				<strong>Votre poste:</strong> '.$_SESSION['postec'].' (<a href="engine=cercledem.php">Démissionner</a>)<br /><br />
				<strong>Descriptif du poste:</strong> '.$descriptioncp.'<br /><br />
				<strong>Vos droits:</strong><br /><div class="barrecercle"><br /><a href="engine=cerclevotes.php">Voter une décision</a><br />Accès par digicode: '.$digicercle.'<br />Liste des membres<br />Accès à l\'actualité<br />');
			if((ereg("tout",$bddc)) || (ereg("commentaires",$bddc)))
				{
				print('Poster un commentaire sur l\'actualité<br />');
				}
			if((ereg("tout",$bddc)) || (ereg("digicode",$bddc)))
				{
				print('<a href="engine=cercledigicode.php">Changer le digicode</a><br />');
				}
			if((ereg("tout",$bddc)) || (ereg("tractes",$bddc)))
				{
				print('<a href="engine=cercletractes.php">Acheter des tractes</a><br />');
				print('<a href="engine=cercletracte.php">Personnaliser le tracte du cercle</a><br />');
				}
			if((ereg("tout",$bddc)) || (ereg("actualite",$bddc)))
				{
				print('Poster/Modifier l\'actualité<br />');
				}
			if((ereg("tout",$bddc)) || (ereg("eumatlm",$bddc)))
				{
				print('<a href="engine=cercleeumatlm.php">Envoyer un message à tous les membres</a><br />');
				}
			if((ereg("tout",$bddc)) || (ereg("logo",$bddc)))
				{
				print('Changer le logo<br />');
				}
			if((ereg("tout",$bddc)) || (ereg("promovoir",$bddc)))
				{
				print('Promovoir/Retrograder<br />');
				}
			if((ereg("tout",$bddc)) || (ereg("equipement",$bddc)))
				{
				print('<a href="engine=cercleequipement.php">Acheter de l\'équipement</a><br />');
				}
			if((ereg("tout",$bddc)) || (ereg("dissoudre",$bddc)))
				{
				print('<a href="engine=cercledissoudre.php">Dissoudre le cercle</a><br />');
				}
			if((ereg("tout",$bddc)) || (ereg("description",$bddc)))
				{
				print('Changer le descriptif du cercle<br />');
				}
			if((ereg("tout",$bddc)) || (ereg("postes",$bddc)))
				{
				print('<a href="engine=cerclepostes.php">Modifier les postes</a><br />');
				}
			if((ereg("tout",$bddc)) || (ereg("cotiz",$bddc)))
				{
				print('<a href="engine=cerclecotiz.php">Fixer la cotisation</a><br />');
				}
			if((ereg("tout",$bddc)) || (ereg("vote",$bddc)))
				{
				print('<a href="engine=cerclevote.php">Lancer un vote dans le cercle</a><br />');
				}
			if((ereg("tout",$bddc)) || (ereg("renvoyernq",$bddc)))
				{
				print('Renvoyer un membre<br />');
				}
			if((ereg("tout",$bddc)) || (ereg("dons",$bddc)))
				{
				print('<a href="engine=cercledons.php">Accéder à la liste des dons</a><br />');
				}
			if((ereg("tout",$bddc)) || (ereg("capital",$bddc)))
				{
				print('Prendre de l\'argent dans le capital<br />');
				}
			if((ereg("tout",$bddc)) || (ereg("pub",$bddc)))
				{
				print('<a href="engine=cerclepub.php">Acheter de la publicité</a><br />');
				}
			print('</div>');
			?>
		</div></td>
	</tr>
</table>


</p>
</div>
<?php if($chat=="oui") 	{ if ((date("H")>=21) || (date("H")<=7)) { include("inc_bas_n_c.php"); } else { include("inc_bas_j_c.php"); } } else {	if ((date("H")>=21) || (date("H")<=7)) { include("inc_bas_n.php"); } else { include("inc_bas_j.php"); }	} ?>
<?php include("inc_bas_de_page.php"); ?>
