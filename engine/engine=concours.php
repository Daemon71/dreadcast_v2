<?php
session_start();

if($_SESSION['pseudo']=="")
	{
	print('<meta http-equiv="refresh" content="0 ; url=engine.php"> ');
	exit();
	}

if($_POST['accord']=="non")
	{
	$db = mysql_connect('localhost', 'CENSURE', 'CENSURE') or die("Erreur de connexion au serveur des messages.");
	mysql_select_db('CENSURE',$db) or die("Erreur de connexion a la base de donnees des messages.");
	
	$sql = 'SELECT id FROM jeu_concours_tbl WHERE pseudo="'.$_SESSION['pseudo'].'"';
	$req = mysql_query($sql);
	$res = mysql_num_rows($req);
		
	if($res==0)
		{
		$sql = 'INSERT INTO jeu_concours_tbl(id,pseudo,age,nom,prenom,adresse,code_postal,ville,pays) VALUES("","'.$_SESSION['pseudo'].'","","","","","","","")';
		mysql_query($sql);
		}
		
	mysql_close($db);
	
	if($_SESSION['quetedepart']==0)
			{
			$_SESSION['didacticiel'] = 1;
			
			$db = mysql_connect('localhost', 'CENSURE', 'CENSURE') or die("Erreur de connexion au serveur des messages.");
			mysql_select_db('CENSURE',$db) or die("Erreur de connexion a la base de donnees des messages.");
	
			$sql = 'UPDATE principal_tbl SET statut="Debutant",num="0",rue="basseville",quetedepart="1",case1="Vide" WHERE pseudo="'.$_SESSION['pseudo'].'"' ;
			mysql_query($sql);
	
			mysql_close($db);
			
			print('<meta http-equiv="refresh" content="0 ; url=didacticiel.php"> ');
			exit();
			}
		else
			{
			print('<meta http-equiv="refresh" content="0 ; url=engine.php"> ');
			exit();
			}
	
	}
elseif($_POST['accord']=="oui")
	{
	if($_POST['nom'] != "" AND $_POST['prenom'] != "" AND $_POST['age'] != "" AND $_POST['adresse'] != "" AND $_POST['cpostal'] != "" AND $_POST['ville'] != "" AND $_POST['pays'] != "")
		{
		$db = mysql_connect('localhost', 'CENSURE', 'CENSURE') or die("Erreur de connexion au serveur des messages.");
		mysql_select_db('CENSURE',$db) or die("Erreur de connexion a la base de donnees des messages.");
		
		$sql = 'SELECT id FROM jeu_concours_tbl WHERE pseudo="'.$_SESSION['pseudo'].'"';
		$req = mysql_query($sql);
		$res = mysql_num_rows($req);
		
		if($res==0)
			{
			$sql = 'INSERT INTO jeu_concours_tbl(id,pseudo,age,nom,prenom,adresse,code_postal,ville,pays) VALUES("","'.$_SESSION['pseudo'].'","'.htmlentities(stripslashes($_POST['age'])).'","'.htmlentities(stripslashes($_POST['nom'])).'","'.htmlentities(stripslashes($_POST['prenom'])).'","'.htmlentities(stripslashes($_POST['adresse'])).'","'.htmlentities(stripslashes($_POST['cpostal'])).'","'.htmlentities(stripslashes($_POST['ville'])).'","'.htmlentities(stripslashes($_POST['pays'])).'")';
			mysql_query($sql);
			}
		
		mysql_close($db);
		
		if($_SESSION['quetedepart']==0)
			{
			$_SESSION['didacticiel'] = 1;
			
			$db = mysql_connect('localhost', 'CENSURE', 'CENSURE') or die("Erreur de connexion au serveur des messages.");
			mysql_select_db('CENSURE',$db) or die("Erreur de connexion a la base de donnees des messages.");
	
			$sql = 'UPDATE principal_tbl SET statut="Debutant",num="0",rue="basseville",quetedepart="1",case1="Vide" WHERE pseudo="'.$_SESSION['pseudo'].'"' ;
			mysql_query($sql);
	
			mysql_close($db);
			
			print('<meta http-equiv="refresh" content="0 ; url=didacticiel.php"> ');
			exit();
			}
		else
			{
			print('<meta http-equiv="refresh" content="0 ; url=engine.php"> ');
			exit();
			}
		}
	}


?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">

<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="fr" >

	<head>
		<title>Dreadcast</title>
       	<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
		<meta http-equiv="Content-Style-Type" content="text/css" />
		<meta http-equiv="Content-Language" content="fr" />
		<meta name="description" content="Dreadcast est un jeu de role en ligne futuriste gratuit (jdr): simulation d'un jeu en ligne de strat&eacute;gie, jouez au jeu et choisissez votre role." />
		<meta name="keywords" lang="fr" content="Dreadcast, futuriste, jeu video, jeu en ligne, communauté, simulation, jeux gratuits, monde virtuel, RPG, cyber-punk, jeux, rôles, mmo, mmorpg, php, futur, empire, communautaire, gratuit, multijoueur, jeu de gestion, jeu de stratégie, jeu online, Action-RPG, rôle, Science-Fiction, Fantastique, heroic fantasy , jouer, gratui, gratuits, jeux en ligne, internet, net, web" />
		<meta name="author" content="MSpixel" />
		<meta name="reply-to" content="dreadcast@mspixel.fr" />
		<meta name="revisit-after" content="1 day" />
		<meta name="robots" content="all" />
		<!--[if IE]><link rel="stylesheet" media="screen" type="text/css" title="Dreadcast" href="mise_en_page_ie_loto.css" /><![endif]-->
		<!--[if !IE]><--><link rel="stylesheet" media="screen" type="text/css" title="Dreadcast" href="mise_en_page_ff_loto.css" /><!--><![endif]-->
		<link rel="shortcut icon" type="image/x-icon" href="im_objets/favicon.ico" />
		<script type="text/javascript" src="js/pcurseur.js"></script>
		<script type="text/javascript">
			<!--
			window.onload=montre;
			function montre(id) {
			var d = document.getElementById(id);
			        for (var i = 1; i<=10; i++) {
			                if (document.getElementById('smenu'+i)) {document.getElementById('smenu'+i).style.display='none';}
			        }
			if (d) {d.style.display='block';}
			}
			
			function affiche_art(id,boole) {
				if (document.getElementById)
					{
					if(boole) document.getElementById(id).style.display="block";
					else document.getElementById(id).style.display="none";
					}
				if (document.all && !document.getElementById)
					{
					if(boole) document.all[id].style.display="block";
					else document.all[id].style.display="none";
					}
				if (document.layers)
					{
					if(boole) document.layers[id].display="block";
					else document.layers[id].display="none";
					}
			}
			
			function MM_jumpMenu(targ,selObj,restore){ //v3.0
			  eval(targ+".location='"+selObj.options[selObj.selectedIndex].value+"'");
			  if (restore) selObj.selectedIndex=0;
			}

			function cacher_montrer()
			{ 
				if(document.getElementById('clignote').style.visibility = 'hidden') 
				{ 
					setTimeout("document.getElementById('clignote').style.visibility = 'visible';",700); 
				} 
				else 
				{ 
				setTimeout("document.getElementById('clignote').style.visibility = 'hidden';",700); 
				} 
			} 
			setInterval("cacher_montrer();",1400)
			//-->
		</script>
	</head>
   
	<body>
		
		<div id="page_loto2">
		
			<div id="contenu">
				&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Bonjour &agrave; tous les citoyens de DreadCast !<br /><br />
				Comme vous le savez, le site Dreadcast.net organise pour la premi&egrave;re fois un grand jeu concours o&ugrave; de nombreux lots sont &agrave; gagner.<br /><br />
				<strong>A gagner :</strong> un iPod Nano, deux iPod Shuffle,...<br />
				<strong>Son susceptibles de remporter un prix :</strong> tout joueur ayant au moins une demi-&eacute;toile (40 points) dans une comp&eacute;tence.<br /><br />
				
				<form action="engine=concours.php" method="POST">
					<h3 style="font-size:16px;font-weight:bold;">Informations n&eacute;cessaires</h3>
					<input type="radio" style="position:relative;top:6px;margin:5px 0;" name="accord" value="non" id="1" /> <label for="1">Je ne souhaite pas participer au concours.</label><br />
					<p style="text-align:center;margin:8px 0 0 0;">ou</p>
					<input type="radio" style="position:relative;top:6px;margin:5px 0;" name="accord" value="oui" id="2" /> <label for="2">Je souhaite participer au concours, et accepte </label><a href="../concours=reglement.php"  onclick="window.open(this.href); return false;">son r&egrave;glement</a>.<br />
					Les informations receuillies ne seront en aucun cas divulgu&eacute;es &agrave; aucun tiers ou organisme que ce soit.<br /><br />
					
					<table>
						<tr>
							<td style="padding:2px;">Nom</td>
							<td style="padding:2px;"><input type="text" name="nom" style="width:100px;height:16px;background-color:#252525;border:1px solid #3A3A3A;color:#aaa;font-size:11px;padding:2px 2px 0 2px;" /></td>
							<td style="padding:2px;">Pr&eacute;nom</td>
							<td style="padding:2px;"><input type="text" name="prenom" style="width:100px;height:16px;background-color:#252525;border:1px solid #3A3A3A;color:#aaa;font-size:11px;padding:2px 2px 0 2px;" /></td>
						</tr>
						<tr>
							<td style="padding:2px;">&Acirc;ge</td>
							<td style="padding:2px;"><input type="text" name="age" style="width:20px;height:16px;background-color:#252525;border:1px solid #3A3A3A;color:#aaa;font-size:11px;padding:2px 2px 0 2px;" /> ans</td>
							<td style="padding:2px;"></td>
							<td style="padding:2px;"></td>
						</tr>
						<tr>
							<td style="padding:2px;">Adresse</td>
							<td style="padding:2px;" colspan="3"><input type="text" name="adresse" style="width:300px;height:16px;background-color:#252525;border:1px solid #3A3A3A;color:#aaa;font-size:11px;padding:2px 2px 0 2px;" /></td>
						</tr>
						<tr>
							<td style="padding:2px;">Code postal</td>
							<td style="padding:2px;"><input type="text" name="cpostal" style="width:50px;height:16px;background-color:#252525;border:1px solid #3A3A3A;color:#aaa;font-size:11px;padding:2px 2px 0 2px;" /></td>
							<td style="padding:2px;">Ville</td>
							<td style="padding:2px;"><input type="text" name="ville"  style="width:100px;height:16px;background-color:#252525;border:1px solid #3A3A3A;color:#aaa;font-size:11px;padding:2px 2px 0 2px;"/></td>
						</tr>
						<tr>
							<td style="padding:2px;">Pays</td>
							<td style="padding:2px;"><input value="France" type="text" name="pays" style="width:100px;height:16px;background-color:#252525;border:1px solid #3A3A3A;color:#aaa;font-size:11px;padding:2px 2px 0 2px;" /></td>
							<td style="padding:2px;"></td>
							<td style="padding:2px;"><input type="submit" name="valider" value="Valider" style="width:104px;height:20px;background-color:#3A3A3A;border:1px solid #3A3A3A;color:#aaa;font-size:11px;padding:0 4px 3px 4px;"/></td>
						</tr>
					</table>
				</form>
			</div>
		</div>
		
	</body>

</html>
