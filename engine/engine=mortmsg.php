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

include('inc_fonctions.php');

$db = mysql_connect('localhost', 'CENSURE', 'CENSURE') or die("Erreur de connexion au serveur des messages.");
mysql_select_db('CENSURE',$db) or die("Erreur de connexion a la base de donnees des messages.");

$sql1 = 'SELECT id FROM messages_tbl WHERE cible= "'.$_SESSION['pseudo'].'" AND nouveau="oui"' ;
$req1 = mysql_query($sql1);
$resn = mysql_num_rows($req1);

$sql = 'SELECT id,action,rue,taille,num,statut,alim FROM principal_tbl WHERE pseudo= "'.$_SESSION['pseudo'].'"' ;
$req = mysql_query($sql);
$_SESSION['id'] = mysql_result($req,0,id);
$_SESSION['action'] = mysql_result($req,0,action);
$_SESSION['taille'] = mysql_result($req,0,taille);
$_SESSION['Num'] = mysql_result($req,0,num);
$_SESSION['statut'] = mysql_result($req,0,statut);
$_SESSION['alim'] = mysql_result($req,0,alim);
$raison = mysql_result($req,0,rue);

$sql = 'SELECT valeur FROM config_tbl WHERE objet= "tpsuppr"' ;
$req = mysql_query($sql);
$valeur = mysql_result($req,0,valeur);

$sql = 'SELECT valeur FROM config_tbl WHERE objet= "pourcperte"' ;
$req = mysql_query($sql);
$pourc = mysql_result($req,0,valeur);
$sql = 'SELECT valeur FROM config_tbl WHERE objet= "pourcsperte"' ;
$req = mysql_query($sql);
$pourcs = mysql_result($req,0,valeur);

$sql = 'SELECT * FROM pubs_tbl WHERE temps>"0" ORDER BY RAND() LIMIT 1' ;
$req = mysql_query($sql);
$res2 = mysql_num_rows($req);

if($res2>0)
	{
	$entpub = mysql_result($req,0,entreprise);
	$messagepub = mysql_result($req,0,message);
	
	if(mysql_result($req,0,lien)!="")
		{
		$lienpub = mysql_result($req,0,lien);
		$sql1 = 'SELECT rue,num FROM entreprises_tbl WHERE nom= "'.$entpub.'"' ;
		$req1 = mysql_query($sql1);
		$res1 = mysql_num_rows($req1);
		if($res1>0)
			{
			$ruepub = mysql_result($req1,0,rue);
			$numpub = mysql_result($req1,0,num);
			}
		else
			{
			$sql1 = 'SELECT rue,num FROM cerclesliste_tbl WHERE nom= "'.$entpub.'"' ;
			$req1 = mysql_query($sql1);
			$ruepub = mysql_result($req1,0,rue);
			$numpub = mysql_result($req1,0,num);
			}
		}
	else
		{
		$signpub = mysql_result($req,0,signature);
		}
	}
else
	{
	$entpub = " ";
	$messagepub = " ";
	$signpub = " ";
	}

mysql_close($db);
?>



<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">

<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="fr" >

	<head>
		<title>Dreadcast</title>
       	<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
		<meta http-equiv="Content-Style-Type" content="text/css" />
		<meta http-equiv="Content-Language" content="fr" />
		<meta name="description" content="Dreadcast est un jeu de role en ligne futuriste gratuit (jdr): simulation d'un jeu en ligne de strat&eacute;gie, jouez au jeu et choisissez votre role." />

		<meta name="keywords" lang="fr" content="Dreadcast, futuriste, jeu video, jeu en ligne, communaut�, simulation, jeux gratuits, monde virtuel, RPG, cyber-punk, jeux, r�les, mmo, mmorpg, php, futur, empire, communautaire, gratuit, multijoueur, jeu de gestion, jeu de strat�gie, jeu online, Action-RPG, r�le, Science-Fiction, Fantastique, heroic fantasy , jouer, gratui, gratuits, jeux en ligne, internet, net, web" />
		<meta name="author" content="MSpixel" />
		<meta name="reply-to" content="dreadcast@mspixel.fr" />
		<meta name="revisit-after" content="1 day" />
		<meta name="robots" content="all" />
		<style>
			table,tr,td,th{border:1px solid #181818;}
			table a{color:#751313;}
			table strong a{color:#b91010;}
			th{background:#0D0D0D;padding:2px 8px;}
			td{background:#181818;}
			a{color:#b91010;text-decoration:none;font-family:verdana,arial,sans-serif;font-size:12px;}
			a:hover{color:#b91010;border-bottom:1px solid #b91010;}
			#bas a{color:#999;}
			#bas a:hover{border-bottom:1px solid #999;}
		</style>
	</head>
   
	<body style="background:#181818;">
	
	<div style="position:absolute;top:20%;left:50%;margin-left:-265px;width:530px;background:url(im_objets/vousetesmort.gif) 0 0 no-repeat;font-family:Verdana,sans-serif;color:#fff;font-size:12px;line-height:20px;">
		<div style="margin:96px 0 30px 154px;font-size:20px;"><strong>Vos messages</strong>
        </div>
        
		<div style="height:230px;overflow:auto;">
		
		
<?php 

$db = mysql_connect('localhost', 'CENSURE', 'CENSURE') or die("Erreur de connexion au serveur des messages.");
mysql_select_db('CENSURE',$db) or die("Erreur de connexion a la base de donnees des messages.");

$sql = 'SELECT id,auteur,objet,moment FROM messages_tbl WHERE cible= "'.$_SESSION['pseudo'].'" AND nouveau= "non" ORDER BY moment DESC' ;
$req = mysql_query($sql);
$res = mysql_num_rows($req);

$sql1 = 'SELECT id,auteur,objet,moment FROM messages_tbl WHERE cible= "'.$_SESSION['pseudo'].'" AND nouveau="oui" ORDER BY moment DESC' ;
$req1 = mysql_query($sql1);
$res1 = mysql_num_rows($req1);

if(statut($_SESSION['statut'])>=2)
	{
	print('<div class="messagesvip">');
	if(($res!=0) || ($res1!=0))
		{
		print('<table align="center" cellpadding="0" cellspacing="0">
					<tr>
					  <th scope="col"><div align="center">De</div></th>
					  <th scope="col"><div align="center">Objet</div></th>
					  <th scope="col"><div align="center">Date</div></th>
					  <th scope="col"><div align="center">Heure</div></th>
					</tr>');
		
		for($i=0; $i != $res1 ; $i++) 
			{ 
			$idm = mysql_result($req1,$i,id);
			$auteur = mysql_result($req1,$i,auteur);
			$objet = mysql_result($req1,$i,objet);
			if(ereg("\[MASQUE\]",$objet) || ereg("\[masque\]",$objet)) { $auteur = "-Anonyme-"; $objet = str_replace("[MASQUE]","",$objet); $objet = str_replace("[masque]","",$objet); }
			$date = date('d/m/y',mysql_result($req1,$i,moment));
			$heure = date('H\hi',mysql_result($req1,$i,moment));
			if($objet=="")
				{
				$objet = "Aucun";
				}
			print('<tr>
					  <td><div align="center"><strong>'.$auteur.'</strong></div></td>
					  <td><div align="center"><strong><a href="engine=mortvoirmessage.php?id='.$idm.'">'.$objet.'</a></strong></div></td>
					  <td><div align="center"><strong>'.$date.'</strong></div></td>
					  <td><div align="center"><strong>'.$heure.'</strong></div></td>
					</tr>');
			}
		for($i=0; $i != $res ; $i++) 
			{ 
			$idm = mysql_result($req,$i,id);
			$auteur = mysql_result($req,$i,auteur);
			$objet = mysql_result($req,$i,objet);
			if(ereg("\[MASQUE\]",$objet) || ereg("\[masque\]",$objet)) { $auteur = "-Anonyme-"; $objet = str_replace("[MASQUE]","",$objet); $objet = str_replace("[masque]","",$objet); }
			$date = date('d/m/y',mysql_result($req,$i,moment));
			$heure = date('H\hi',mysql_result($req,$i,moment));
			if($objet=="")
				{
				$objet = "Aucun";
				}
			print('<tr>
					  <td><div align="center">'.$auteur.'</div></td>
					  <td><div align="center"><a href="engine=mortvoirmessage.php?id='.$idm.'">'.$objet.'</a></div></td>
					  <td><div align="center">'.$date.'</div></td>
					  <td><div align="center">'.$heure.'</div></td>
					</tr>');
			}
		print('</table>');
		}
	else
		{
		print('<p align="center"><strong>Vous n\'avez aucun message priv&eacute;.</strong></p>');
		}
	print('</div>');
	}
else
	{
	$l = 0;
	print('<p align="center">');
	if(($res!=0) || ($res1!=0))
		{
		print('<table width="90%" bgcolor="#FFFFFF" border="1" align="center" cellpadding="0" cellspacing="0">
					<tr>
					  <th scope="col"><div align="center">De</div></th>
					  <th scope="col"><div align="center">Objet</div></th>
					  <th scope="col"><div align="center">Date</div></th>
					  <th scope="col"><div align="center">Heure</div></th>
					</tr>');
		
		for($i=0; $i != $res1 ; $i++) 
			{ 
			if($l<14)
				{
				$l = $l + 1;
				$idm = mysql_result($req1,$i,id);
				$auteur = mysql_result($req1,$i,auteur);
				$objet = mysql_result($req1,$i,objet);
				if(ereg("\[MASQUE\]",$objet) || ereg("\[masque\]",$objet)) { $auteur = "-Anonyme-"; $objet = str_replace("[MASQUE]","",$objet); $objet = str_replace("[masque]","",$objet); }
				$date = date('d/m/y',mysql_result($req1,$i,moment));
				$heure = date('H\hi',mysql_result($req1,$i,moment));
				if($objet=="")
					{
					$objet = "Aucun";
					}
				print('<tr>
						  <td><div align="center"><strong>'.$auteur.'</strong></div></td>
						  <td><div align="center"><strong><a href="engine=mortvoirmessage.php?id='.$idm.'">'.$objet.'</a></strong></div></td>
						  <td><div align="center"><strong>'.$date.'</strong></div></td>
						  <td><div align="center"><strong>'.$heure.'</strong></div></td>
						</tr>');
				}
			}
		for($i=0; $i != $res ; $i++) 
			{ 
			if($l<15)
				{
				$l = $l + 1;
				$idm = mysql_result($req,$i,id);
				$auteur = mysql_result($req,$i,auteur);
				$objet = mysql_result($req,$i,objet);
				if(ereg("\[MASQUE\]",$objet) || ereg("\[masque\]",$objet)) { $auteur = "-Anonyme-"; $objet = str_replace("[MASQUE]","",$objet); $objet = str_replace("[masque]","",$objet); }
				$date = date('d/m/y',mysql_result($req,$i,moment));
				$heure = date('H\hi',mysql_result($req,$i,moment));
				if($objet=="")
					{
					$objet = "Aucun";
					}
				print('<tr>
						  <td><div align="center">'.$auteur.'</div></td>
						  <td><div align="center"><a href="engine=mortvoirmessage.php?id='.$idm.'">'.$objet.'</a></div></td>
						  <td><div align="center">'.$date.'</div></td>
						  <td><div align="center">'.$heure.'</div></td>
						</tr>');
				}
			}
		print('</table>');
		}
	else
		{
		print("<strong>Vous n'avez aucun message priv&eacute;.</strong><br>");
		}
		
	if($l>=15)
		{
		print('<center><strong>Votre messagerie est pleine !</strong></center>');
		}
	print('</p>');
	}
	
mysql_close($db);

?>

</div>
		

	</div>
	
	<div id="bas" style="position:absolute;bottom:20px;left:50%;margin-left:-200px;width:400px;text-align:center;color:#666;">
		<a href="engine=mort.php">Retour</a> - <a href="engine=mortrbug.php">Aide en ligne</a> - <a href="../wikast/">Acc�der au Wikast</a> - <a href="index.php">D�connexion</a>
	</div>
		
	</body>

</html>
