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
if(($_SESSION['statut']!="Administrateur") && ($_SESSION['statut']!="DÈveloppeur") && ($_SESSION['statut']!="ModÈrateur RPIG") && ($_SESSION['statut']!="ModÈrateur communication"))
	{
	print('<meta http-equiv="refresh" content="0 ; url=http://v2.dreadcast.net/ingame/engine.php"> ');
	exit();
	}

$db = mysql_connect('localhost', 'CENSURE', 'CENSURE') or die("Erreur de connexion au serveur des messages.");
mysql_select_db('CENSURE',$db) or die("Erreur de connexion a la base de donnees des messages.");

$sql = 'SELECT id,rue,num,action,alim,sante,fatigue FROM principal_tbl WHERE pseudo= "'.$_SESSION['pseudo'].'"' ;
$req = mysql_query($sql);
$_SESSION['id'] = mysql_result($req,0,id);
$_SESSION['rue'] = mysql_result($req,0,rue);
$_SESSION['num'] = mysql_result($req,0,num);
$_SESSION['action'] = mysql_result($req,0,action);
$_SESSION['alim'] = mysql_result($req,0,alim);
$_SESSION['sante'] = mysql_result($req,0,sante);
$_SESSION['fatigue'] = mysql_result($req,0,fatigue);
$_SESSION['lieu'] = strtolower($_SESSION['rue']);
$_SESSION['lieu'] = ucwords($_SESSION['lieu']);

$sql = 'SELECT id FROM articlesprop_tbl' ;
$req = mysql_query($sql);
$resa = mysql_num_rows($req);

mysql_close($db);
?>
<?php if ((date("H")>=21) || (date("H")<=7)) { include("inc_haut_n.php"); } else { include("inc_haut_j.php");} ?>

<div id="haut">
	<?php include("inc_entete.php"); ?>
	<div class="haut_aligngauche">
		<p>
		<div class="titrepage">
			Panneau d'administration
		</div>
		<?php
		if($resa>0 && $_SESSION['statut']=="Administrateur")
			{
			print('<div align="center">(<a href="engine=panneaua.php">Liste d\'articles</a>)</div>');
			}
		?>
		<div align="center">(<a href="engine=ajoutertip.php">Ajouter un tip'</a>)</div>
		<div align="center">(<a href="engine=ajoutercit.php">Ajouter une citation</a>)</div>
		</p>
	</div>
</div>
<div id="centre">
<p>
<div class="messagesvip">
<?php 

if(($_SESSION['statut']=="Administrateur") OR ($_SESSION['statut']=="ModÈrateur communication") OR ($_SESSION['statut']=="DÈveloppeur"))
	{
	$db = mysql_connect('localhost', 'CENSURE', 'CENSURE') or die("Erreur de connexion au serveur des messages.");
	mysql_select_db('CENSURE',$db) or die("Erreur de connexion a la base de donnees des messages.");
	
	$sql = 'SELECT * FROM messagesadmin_tbl WHERE nouveau!="oui" AND attribue="boite" ORDER BY moment DESC';
	$req = mysql_query($sql);
	$res = mysql_num_rows($req);
	$sql1 = 'SELECT * FROM messagesadmin_tbl WHERE nouveau="oui" AND attribue="boite" ORDER BY moment DESC' ;
	$req1 = mysql_query($sql1);
	$res1 = mysql_num_rows($req1);
	
	if(($res!=0) || ($res1!=0))
		{
		print('<table width="90%"  border="1" align="center" cellpadding="0" cellspacing="0">
					<tr bgcolor="#B6B6B6">
					  <th scope="col"><div align="center">De</div></th>
					  <th scope="col"><div align="center">Objet</div></th>
					  <th scope="col"><div align="center">Date</div></th>
					  <th scope="col"><div align="center">Heure</div></th>
					  <th scope="col"><div align="center">&nbsp;</div></th>
					</tr>');
		
		for($i=0; $i != $res1 ; $i++) 
			{ 
			$idm = mysql_result($req1,$i,id);
			$auteur = mysql_result($req1,$i,auteur);
			$objet = mysql_result($req1,$i,objet);
			$date = date('d/m/y',mysql_result($req1,$i,moment));
			$heure = date('H\hi',mysql_result($req1,$i,moment));
			if($objet=="")
				{
				$objet = "Aucun";
				}
			print('<tr>
					  <td><div align="center"><strong>'.$auteur.'</strong></div></td>
					  <td><div align="center"><strong><a href="engine=voirmessageadmin.php?id='.$idm.'">'.$objet.'</a></strong></div></td>
					  <td><div align="center"><strong>'.$date.'</strong></div></td>
					  <td><div align="center"><strong>'.$heure.'</strong></div></td>
					  <td><div align="center"><a href="engine=supprmessageadmin.php?'.$idm.'"><img src="im_objets/poubelle.gif" border="0"></a></div></td>
					</tr>');
			}
		for($i=0; $i != $res ; $i++) 
			{ 
			$idm = mysql_result($req,$i,id);
			$auteur = mysql_result($req,$i,auteur);
			$objet = mysql_result($req,$i,objet);
			$date = date('d/m/y',mysql_result($req,$i,moment));
			$heure = date('H\hi',mysql_result($req,$i,moment));
			if($objet=="")
				{
				$objet = "Aucun";
				}
			print('<tr>
					  <td><div align="center">'.$auteur.'</div></td>
					  <td><div align="center"><a href="engine=voirmessageadmin.php?id='.$idm.'">'.$objet.'</a></div></td>
					  <td><div align="center">'.$date.'</div></td>
					  <td><div align="center">'.$heure.'</div></td>
					  <td><div align="center"><a href="engine=supprmessageadmin.php?'.$idm.'"><img src="im_objets/poubelle.gif" border="0"></a></div></td>
					</tr>');
			}
		print('</table>');
		}
	else
		{
		print("<strong>Il n'y a aucun message dans la boite &agrave; id&eacute;es</strong><br />");
		}
	print('<p align="center"><a href="engine=contacteradmin.php">Envoyer un message admin</a>');
	if(($_SESSION['statut']=="Administrateur") or ($_SESSION['statut']=="ModÈrateur RPIG") or ($_SESSION['statut']=="DÈveloppeur"))
		{
		print('<br /><a href="engine=nommer.php">Nommer un nouveau politique</a>');
		}
	if(($_SESSION['statut']=="Administrateur") or ($_SESSION['statut']=="DÈveloppeur"))
		{
		print('<br /><a href="engine=ajoutnews.php">Ajouter une news</a><br /><a href="engine=commande.php">Contr&ocirc;le</a>');
		}
	print('<p align="center"><a href="engine=panneau.php">Panneau d\'administration</a><br />
			<a href="stats=toutes.php" target="_blank">Statistiques</a><br />
			<a href="stats=blacklist.php" target="_blank">Liste noire</a>');
	mysql_close($db);
	}
else
	{
	print('<meta http-equiv="refresh" content="0 ; url=http://v2.dreadcast.net/ingame/engine.php"> ');
	exit();
	}

?>
</div>
</p>
</div>
<?php if($chat=="oui") 	{ if ((date("H")>=21) || (date("H")<=7)) { include("inc_bas_n_c.php"); } else { include("inc_bas_j_c.php"); } } else {	if ((date("H")>=21) || (date("H")<=7)) { include("inc_bas_n.php"); } else { include("inc_bas_j.php"); }	} ?>
<?php include("inc_bas_de_page.php"); ?>
