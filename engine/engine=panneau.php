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
if(($_SESSION['statut']!="Administrateur") && ($_SESSION['statut']!="Développeur") && ($_SESSION['statut']!="Modérateur RPIG") && ($_SESSION['statut']!="Modérateur communication"))
	{
	print('<meta http-equiv="refresh" content="0 ; url=http://v2.dreadcast.net/ingame/engine.php"> ');
	exit();
	}

$db = mysql_connect('localhost', 'CENSURE', 'CENSURE') or die("Erreur de connexion au serveur des messages.");
mysql_select_db('CENSURE',$db) or die("Erreur de connexion a la base de donnees des messages.");

if($_GET['triche']=="bouffe")
	{
	$sql = 'UPDATE principal_tbl SET faim="100", soif="100" WHERE pseudo= "'.$_SESSION['pseudo'].'"' ;
	mysql_query($sql);
	}
if($_GET['triche']=="argent")
	{
	$sql = 'SELECT credits FROM principal_tbl WHERE pseudo= "'.$_SESSION['pseudo'].'"' ;
	$req = mysql_query($sql);
	
	$sql = 'UPDATE principal_tbl SET credits = "'.(mysql_result($req,0,credits)+5000).'" WHERE pseudo= "'.$_SESSION['pseudo'].'"' ;
	mysql_query($sql);
	}
	
$sql = 'SELECT id,rue,num,action,alim,sante,fatigue,faim,soif,credits,statut FROM principal_tbl WHERE pseudo= "'.$_SESSION['pseudo'].'"' ;
$req = mysql_query($sql);
$_SESSION['id'] = mysql_result($req,0,id);
$_SESSION['statut'] = mysql_result($req,0,statut);
$_SESSION['rue'] = mysql_result($req,0,rue);
$_SESSION['num'] = mysql_result($req,0,num);
$_SESSION['action'] = mysql_result($req,0,action);
$_SESSION['alim'] = mysql_result($req,0,alim);
$_SESSION['faim'] = mysql_result($req,0,faim);
$_SESSION['soif'] = mysql_result($req,0,soif);
$_SESSION['sante'] = mysql_result($req,0,sante);
$_SESSION['fatigue'] = mysql_result($req,0,fatigue);
$_SESSION['credits'] = mysql_result($req,0,credits);
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
		</p>
	</div>
</div>
<?php include('inc_admin.php') ?>
<div id="centre">
<p>
<div class="messagesvip">
<?php 

if($_SESSION['statut']=="Administrateur")
	{
	print('<div style="position:absolute;left:-250px;top:0;width:200px;min-height:100px;background:#555;border:1px solid #aaa;"></div>');
	
	$db = mysql_connect('localhost', 'CENSURE', 'CENSURE') or die("Erreur de connexion au serveur des messages.");
	mysql_select_db('CENSURE',$db) or die("Erreur de connexion a la base de donnees des messages.");
	$sql = 'SELECT * FROM messagesadmin_tbl WHERE nouveau!="oui" AND attribue="" ORDER BY moment DESC';
	$req = mysql_query($sql);
	$res = mysql_num_rows($req);
	$sql1 = 'SELECT * FROM messagesadmin_tbl WHERE nouveau="oui" AND attribue="" ORDER BY moment DESC' ;
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
			
			$sqlaut = 'SELECT id,statut FROM principal_tbl WHERE pseudo="'.$auteur.'" AND ( statut="Compte VIP" OR statut="Silver" OR statut="Gold" OR statut="Platinium" )';
			$reqaut = mysql_query($sqlaut);
			$resaut = mysql_num_rows($reqaut);
			
			if($objet=="")
				{
				$objet = "Aucun";
				}
			print('<tr>
					  <td><div align="center"><strong>'.$auteur.'</strong></div></td>
					  <td><div align="center"><strong><a '.(($resaut == 1 && mysql_result($reqaut,0,statut)=="Gold")?'style="color:#E88D00;"':(($resaut == 1 && mysql_result($reqaut,0,statut)=="Platinium")?'style="color:#660BDE;"':(($resaut == 1)?'style="color:#b91010;"':''))).' href="engine=voirmessageadmin.php?id='.$idm.'">'.$objet.'</a></strong></div></td>
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
			
			$sqlaut = 'SELECT id,statut FROM principal_tbl WHERE pseudo="'.$auteur.'" AND ( statut="Compte VIP" OR statut="Silver" OR statut="Gold" OR statut="Platinium" )';
			$reqaut = mysql_query($sqlaut);
			$resaut = mysql_num_rows($reqaut);
			
			if($objet=="")
				{
				$objet = "Aucun";
				}
			print('<tr>
					  <td><div align="center">'.$auteur.'</div></td>
					  <td><div align="center"><a '.(($resaut == 1 && mysql_result($reqaut,0,statut)=="Gold")?'style="color:#E88D00;"':(($resaut == 1 && mysql_result($reqaut,0,statut)=="Platinium")?'style="color:#660BDE;"':(($resaut == 1)?'style="color:#b91010;"':''))).' href="engine=voirmessageadmin.php?id='.$idm.'">'.$objet.'</a></div></td>
					  <td><div align="center">'.$date.'</div></td>
					  <td><div align="center">'.$heure.'</div></td>
					  <td><div align="center"><a href="engine=supprmessageadmin.php?'.$idm.'"><img src="im_objets/poubelle.gif" border="0"></a></div></td>
					</tr>');
			}
		print('</table><br /><br />');
		}
	else
		{
		print("<strong>Il n'y a aucun message administrateur.</strong><br /><br /><br />");
		}
	
	$sql = 'SELECT * FROM messagesadmin_tbl WHERE nouveau!="oui" AND attribue="'.$_SESSION['pseudo'].'" ORDER BY moment DESC';
	$req = mysql_query($sql);
	$res = mysql_num_rows($req);
	$sql1 = 'SELECT * FROM messagesadmin_tbl WHERE nouveau="oui" AND attribue="'.$_SESSION['pseudo'].'" ORDER BY moment DESC' ;
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
			
			$sqlaut = 'SELECT id,statut FROM principal_tbl WHERE pseudo="'.$auteur.'" AND ( statut="Compte VIP" OR statut="Silver" OR statut="Gold" OR statut="Platinium" )';
			$reqaut = mysql_query($sqlaut);
			$resaut = mysql_num_rows($reqaut);
			
			if($objet=="")
				{
				$objet = "Aucun";
				}
			print('<tr>
					  <td><div align="center"><strong>'.$auteur.'</strong></div></td>
					  <td><div align="center"><strong><a '.(($resaut == 1 && mysql_result($reqaut,0,statut)=="Gold")?'style="color:#E88D00;"':(($resaut == 1 && mysql_result($reqaut,0,statut)=="Platinium")?'style="color:#660BDE;"':(($resaut == 1)?'style="color:#b91010;"':''))).' href="engine=voirmessageadmin.php?id='.$idm.'">'.$objet.'</a></strong></div></td>
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
			
			$sqlaut = 'SELECT id,statut FROM principal_tbl WHERE pseudo="'.$auteur.'" AND ( statut="Compte VIP" OR statut="Silver" OR statut="Gold" OR statut="Platinium" )';
			$reqaut = mysql_query($sqlaut);
			$resaut = mysql_num_rows($reqaut);
			
			if($objet=="")
				{
				$objet = "Aucun";
				}
			print('<tr>
					  <td><div align="center">'.$auteur.'</div></td>
					  <td><div align="center"><a '.(($resaut == 1 && mysql_result($reqaut,0,statut)=="Gold")?'style="color:#E88D00;"':(($resaut == 1 && mysql_result($reqaut,0,statut)=="Platinium")?'style="color:#660BDE;"':(($resaut == 1)?'style="color:#b91010;"':''))).' href="engine=voirmessageadmin.php?id='.$idm.'">'.$objet.'</a></div></td>
					  <td><div align="center">'.$date.'</div></td>
					  <td><div align="center">'.$heure.'</div></td>
					  <td><div align="center"><a href="engine=supprmessageadmin.php?'.$idm.'"><img src="im_objets/poubelle.gif" border="0"></a></div></td>
					</tr>');
			}
		print('</table>');
		}
	else
		{
		print("<strong>Il n'y a aucun message pour vous.</strong><br />");
		}
		
	
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
