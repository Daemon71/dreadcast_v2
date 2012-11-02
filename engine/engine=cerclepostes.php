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
			Hierarchie
		</div>
		<b class="module4ie"><a href="engine=cerclestatut.php" class="module4"><img src="im_objets/icon_retour.gif" alt="Retour" />Retour</a></b>
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

$db = mysql_connect('localhost', 'CENSURE', 'CENSURE') or die("Erreur de connexion au serveur des messages.");
mysql_select_db('CENSURE',$db) or die("Erreur de connexion a la base de donnees des messages.");

if($_GET['id']!="")
	{
	$sql4 = 'SELECT * FROM `c_'.str_replace(" ","_",''.$_SESSION['cercle'].'').'_tbl` WHERE id= "'.$_GET['id'].'"' ;
	$req4 = mysql_query($sql4);
	$res4 = mysql_num_rows($req4);
	
	if($res4>0)
		{
		if($_GET['modif']!="")
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
			$sql5 = 'UPDATE `c_'.str_replace(" ","_",''.$_SESSION['cercle'].'').'_tbl` SET bdd= "'.$bddc.'" WHERE id= "'.$_GET['id'].'"' ;
			$req5 = mysql_query($sql5);
			}
		else
			{
			$bddc = mysql_result($req4,0,bdd);
			}

		print('<tr><td><center><strong><br />'.mysql_result($req4,0,poste).'</strong></center></td></tr>');
		print('<tr><td><center><br /><strong>Description:</strong> '.mysql_result($req4,0,description).'<br />(<a href="engine=cerclemdp.php?id='.$_GET['id'].'">Modifier</a>)</center></td></tr>');
		print('<tr><td><center><br /><strong>Droits:</strong><form action="engine=cerclepostes.php?id='.$_GET['id'].'&modif=1" method="post" name="modif">');
		if(ereg("tout",$bddc))
			{
			print('Tous les droits');
			}
		else
			{
			if(ereg("commentaires",$bddc))
				{
				print('<input name="commentaires" type="checkbox" value="ok"checked /> Poster un commentaire sur l\'actualité<br />');
				}
			else
				{
				print('<input name="commentaires" type="checkbox" value="ok"/> Poster un commentaire sur l\'actualité<br />');
				}
			if(ereg("digicode",$bddc))
				{
				print('<input name="digicode" type="checkbox" value="ok"checked /> Changer le digicode<br />');
				}
			else
				{
				print('<input name="digicode" type="checkbox" value="ok" /> Changer le digicode<br />');
				}
			if(ereg("tractes",$bddc))
				{
				print('<input name="tractes" type="checkbox" value="ok"checked /> Personnaliser et acheter des tractes</a><br />');
				}
			else
				{
				print('<input name="tractes" type="checkbox" value="ok" /> Acheter des tractes</a><br />');
				}
			if(ereg("actualite",$bddc))
				{
				print('<input name="actualite" type="checkbox" value="ok"checked /> Poster/Modifier l\'actualité<br />');
				}
			else
				{
				print('<input name="actualite" type="checkbox" value="ok" /> Poster/Modifier l\'actualité<br />');
				}
			if(ereg("eumatlm",$bddc))
				{
				print('<input name="eumatlm" type="checkbox" value="ok"checked /> Envoyer un message à tous les membres<br />');
				}
			else
				{
				print('<input name="eumatlm" type="checkbox" value="ok" /> Envoyer un message à tous les membres<br />');
				}
			if(ereg("logo",$bddc))
				{
				print('<input name="logo" type="checkbox" value="ok"checked /> Changer le logo<br />');
				}
			else
				{
				print('<input name="logo" type="checkbox" value="ok" /> Changer le logo<br />');
				}
			if(ereg("promovoir",$bddc))
				{
				print('<input name="promovoir" type="checkbox" value="ok"checked /> Promovoir/Retrograder<br />');
				}
			else
				{
				print('<input name="promovoir" type="checkbox" value="ok" /> Promovoir/Retrograder<br />');
				}
			if(ereg("equipement",$bddc))
				{
				print('<input name="equipement" type="checkbox" value="ok"checked /> Acheter de l\'équipement<br />');
				}
			else
				{
				print('<input name="equipement" type="checkbox" value="ok" /> Acheter de l\'équipement<br />');
				}
			if(ereg("dissoudre",$bddc))
				{
				print('<input name="dissoudre" type="checkbox" value="ok"checked /> Dissoudre le cercle<br />');
				}
			else
				{
				print('<input name="dissoudre" type="checkbox" value="ok" /> Dissoudre le cercle<br />');
				}
			if(ereg("description",$bddc))
				{
				print('<input name="description" type="checkbox" value="ok"checked /> Changer le descriptif du cercle<br />');
				}
			else
				{
				print('<input name="description" type="checkbox" value="ok" /> Changer le descriptif du cercle<br />');
				}
			if(ereg("postes",$bddc))
				{
				print('<input name="postes" type="checkbox" value="ok"checked /> Modifier les postes<br />');
				}
			else
				{
				print('<input name="postes" type="checkbox" value="ok" /> Modifier les postes<br />');
				}
			if(ereg("cotiz",$bddc))
				{
				print('<input name="cotiz" type="checkbox" value="ok"checked /> Fixer la cotisation<br />');
				}
			else
				{
				print('<input name="cotiz" type="checkbox" value="ok" /> Fixer la cotisation<br />');
				}
			if(ereg("vote",$bddc))
				{
				print('<input name="vote" type="checkbox" value="ok"checked /> Lancer/supprimer un vote dans le cercle<br />');
				}
			else
				{
				print('<input name="vote" type="checkbox" value="ok" /> Lancer un vote dans le cercle<br />');
				}
			if(ereg("renvoyernq",$bddc))
				{
				print('<input name="renvoyernq" type="checkbox" value="ok"checked /> Renvoyer un membre<br />');
				}
			else
				{
				print('<input name="renvoyernq" type="checkbox" value="ok" /> Renvoyer un membre<br />');
				}
			if(ereg("dons",$bddc))
				{
				print('<input name="dons" type="checkbox" value="ok"checked /> Accéder à la liste des dons<br />');
				}
			else
				{
				print('<input name="dons" type="checkbox" value="ok" /> Accéder à la liste des dons<br />');
				}
			if(ereg("capital",$bddc))
				{
				print('<input name="capital" type="checkbox" value="ok"checked /> Prendre de l\'argent dans le capital<br />');
				}
			else
				{
				print('<input name="capital" type="checkbox" value="ok" /> Prendre de l\'argent dans le capital<br />');
				}
			if(ereg("pub",$bddc))
				{
				print('<input name="pub" type="checkbox" value="ok"checked /> Acheter de la publicité<br />');
				}
			else
				{
				print('<input name="pub" type="checkbox" value="ok" /> Acheter de la publicité<br />');
				}
			print('<input type="submit" name="Submit" value="Valider" /></form></center></td></tr>');
			}
		}
	else
		{
		print('<meta http-equiv="refresh" content="0 ; url=http://v2.dreadcast.net/ingame/engine=cercle.php"> ');
		exit();
		}
	}
else
	{
	$sql4 = 'SELECT * FROM `c_'.str_replace(" ","_",''.$_SESSION['cercle'].'').'_tbl`' ;
	$req4 = mysql_query($sql4);
	$res4 = mysql_num_rows($req4);
	
	for($i=0;$i!=$res4;$i++)
		{
		print('<tr><td><center><br /><strong>'.mysql_result($req4,$i,poste).'</strong> (<a href="engine=cerclepostes.php?id='.mysql_result($req4,$i,id).'">Modifier</a>)');
		if(mysql_result($req4,$i,id)<=2)
			{
			}
		else
			{
			print('(<a href="engine=cercleps.php?'.mysql_result($req4,$i,id).'">Supprimer</a>)');
			}
		print('</center></td></tr>');
		}
	
	print('<tr><td><br /><br /><center>(<a href="engine=cerclepa.php">Ajouter un poste)</center></td></tr>');
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
