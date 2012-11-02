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
	if((ereg("tout",$bddc)) || (ereg("postes",$bddc)))
		{
		}
	else
		{
		print('<meta http-equiv="refresh" content="0 ; url=http://v2.dreadcast.net/ingame/engine=cercle.php"> ');
		exit();
		}
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
			Ajoût d'un poste
		</div>
		<b class="module4ie"><a href="engine=cerclepostes.php" class="module4"><img src="im_objets/icon_retour.gif" alt="Retour" />Retour</a></b>
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

if($_GET['ok']=="1")
	{
	if(empty($_POST['poste']))
		{
		print('<tr><td><br /><center>Le nom du poste est vide.</center></td></tr>');
		}
	else
		{
		$bddc = "";
		if($_POST['commentaires']=="ok")
			{
			$bddc = ''.$bddc.' commentaires';
			}
		if($_POST['digicode']=="ok")
			{
			$bddc = ''.$bddc.' digicode';
			}
		if($_POST['tractes']=="ok")
			{
			$bddc = ''.$bddc.' tractes';
			}
		if($_POST['actualite']=="ok")
			{
			$bddc = ''.$bddc.' actualite';
			}
		if($_POST['eumatlm']=="ok")
			{
			$bddc = ''.$bddc.' eumatlm';
			}
		if($_POST['logo']=="ok")
			{
			$bddc = ''.$bddc.' logo';
			}
		if($_POST['promovoir']=="ok")
			{
			$bddc = ''.$bddc.' promovoir';
			}
		if($_POST['equipement']=="ok")
			{
			$bddc = ''.$bddc.' equipement';
			}
		if($_POST['dissoudre']=="ok")
			{
			$bddc = ''.$bddc.' dissoudre';
			}
		if($_POST['description']=="ok")
			{
			$bddc = ''.$bddc.' description';
			}
		if($_POST['postes']=="ok")
			{
			$bddc = ''.$bddc.' postes';
			}
		if($_POST['cotiz']=="ok")
			{
			$bddc = ''.$bddc.' cotiz';
			}
		if($_POST['vote']=="ok")
			{
			$bddc = ''.$bddc.' vote';
			}
		if($_POST['renvoyernq']=="ok")
			{
			$bddc = ''.$bddc.' renvoyernq';
			}
		if($_POST['dons']=="ok")
			{
			$bddc = ''.$bddc.' dons';
			}
		if($_POST['capital']=="ok")
			{
			$bddc = ''.$bddc.' capital';
			}
		if($_POST['pub']=="ok")
			{
			$bddc = ''.$bddc.' pub';
			}
		$db = mysql_connect('localhost', 'CENSURE', 'CENSURE') or die("Erreur de connexion au serveur des messages.");
		mysql_select_db('CENSURE',$db) or die("Erreur de connexion a la base de donnees des messages.");
	
		$sql5 = 'INSERT INTO `c_'.str_replace(" ","_",''.$_SESSION['cercle'].'').'_tbl`(id,poste,description,bdd) VALUES("","'.$_POST['poste'].'","'.str_replace("\n","<br />",''.htmlentities($_POST["description"],ENT_QUOTES).'').'","'.$bddc.'")' ;
		$req5 = mysql_query($sql5);
		
		mysql_close($db);
		
		print('<tr><td><br /><center>Poste créé.</center></td></tr>');
		}
	}
else
	{
	print('<tr><td><br /><form action="engine=cerclepa.php?ok=1" method="post" name="ajout">
	<center><strong>Nom du poste:</strong><input type="text" name="poste" /></center></td></tr>');
	print('<tr><td><center><br /><strong>Description:</strong><textarea cols="25" rows="2" name="description"></textarea></center></td></tr>');
	print('<tr><td><center><br /><strong>Droits:</strong><br />
	<input name="commentaires" type="checkbox" value="ok"/> Poster un commentaire sur l\'actualité<br />
	<input name="digicode" type="checkbox" value="ok" /> Changer le digicode<br />
	<input name="tractes" type="checkbox" value="ok" /> Personnaliser et acheter des tractes</a><br />
	<input name="actualite" type="checkbox" value="ok" /> Poster/Modifier l\'actualité<br />
	<input name="eumatlm" type="checkbox" value="ok" /> Envoyer un message à tous les membres<br />
	<input name="logo" type="checkbox" value="ok" /> Changer le logo<br />
	<input name="promovoir" type="checkbox" value="ok" /> Promovoir/Retrograder<br />
	<input name="equipement" type="checkbox" value="ok" /> Acheter de l\'équipement<br />
	<input name="dissoudre" type="checkbox" value="ok" /> Dissoudre le cercle<br />
	<input name="description" type="checkbox" value="ok" /> Changer le descriptif du cercle<br />
	<input name="postes" type="checkbox" value="ok" /> Modifier les postes<br />
	<input name="cotiz" type="checkbox" value="ok" /> Fixer la cotisation<br />
	<input name="vote" type="checkbox" value="ok" /> Lancer/supprimer un vote dans le cercle<br />
	<input name="renvoyernq" type="checkbox" value="ok" /> Renvoyer un membre<br />
	<input name="dons" type="checkbox" value="ok" /> Accéder à la liste des dons<br />
	<input name="capital" type="checkbox" value="ok" /> Prendre de l\'argent dans le capital<br />
	<input name="pub" type="checkbox" value="ok" /> Acheter de la publicité<br />
	<input type="submit" name="Submit" value="Créer le poste" /></form></center></td></tr>');
	}


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
