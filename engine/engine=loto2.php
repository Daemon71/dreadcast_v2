<?php
session_start();

$db = mysql_connect('localhost', 'CENSURE', 'CENSURE') or die("Erreur de connexion au serveur des messages.");
mysql_select_db('CENSURE',$db) or die("Erreur de connexion a la base de donnees des messages.");

if($_POST['alors'] != "")
	{
	if($_POST['alors'] == "oui" && $_POST['nom'] != "" && $_POST['prenom'] != "" && $_POST['age'] != "" && $_POST['adresse'] != "" && $_POST['code_postal'] != "" && $_POST['ville'] != "" && $_POST['pays'] != "")
		{
		$sql = 'INSERT INTO jeu_concours_tbl(pseudo,nom,prenom,age,adresse,code_postal,ville,pays) VALUES("'.$_SESSION['pseudo'].'","'.htmlentities(stripslashes($_POST['nom'])).'","'.htmlentities(stripslashes($_POST['prenom'])).'","'.htmlentities(stripslashes($_POST['age'])).'","'.htmlentities(stripslashes($_POST['adresse'])).'","'.htmlentities(stripslashes($_POST['code_postal'])).'","'.htmlentities(stripslashes($_POST['ville'])).'","'.htmlentities(stripslashes($_POST['pays'])).'")' ;
		mysql_query($sql);
		}
	elseif($_POST['alors'] == "non")
		{
		$sql = 'INSERT INTO jeu_concours_tbl(pseudo) VALUES("'.$_SESSION['pseudo'].'")' ;
		mysql_query($sql);
		
		print('<meta http-equiv="refresh" content="0 ; url=http://v2.dreadcast.net/ingame/engine.php"> ');
		exit();
		}
	}

$sql = 'SELECT id FROM jeu_concours_tbl WHERE pseudo= "'.$_SESSION['pseudo'].'"' ;
$req = mysql_query($sql);
$res = mysql_num_rows($req);

if($res != 0)
	{
	print('<meta http-equiv="refresh" content="0 ; url=http://v2.dreadcast.net/ingame/engine.php"> ');
	exit();
	}

mysql_close();	

	
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
		
		<div id="page_loto">
		
			<div id="contenu">
				Bonjour &agrave; tous les citoyens de DreadCast !<br /><br />
				Ce mois de D�cembre, l'Imp�riale des jeux organise un tirage au sort, avec � la cl� <strong style="font-size:16px;">2 iPods Nano chromatiques 8Go</strong> !</br >
				Pour vous inscrire au concours, veuillez renseigner les informations demand�es.<br /><br />
				<form action="#" method="post">
				<h3 style="border-bottom:1px solid #AAA;margin:10px 0;">Je souhaite participer au concours</h3>
				<table style="line-height:20px;">
					<tr>
						<td>Nom</td>
						<td><input type="text" name="nom" value="<?php echo $_POST['nom']; ?>" style="width:120px;background:#555;border:1px solid #AAA;color:#AAA;" />&nbsp;&nbsp;&nbsp;</td>
						<td>Pr�nom</td>
						<td><input type="text" name="prenom" value="<?php echo $_POST['prenom']; ?>" style="width:120px;background:#555;border:1px solid #AAA;color:#AAA;" /></td>
					</tr>
					<tr>
						<td>Age</td>
						<td><input type="text" name="age" value="<?php echo $_POST['age']; ?>" style="width:30px;background:#555;border:1px solid #AAA;color:#AAA;" /> ans</td>
						<td colspan="2"></td>
					</tr>
					<tr>
						<td>Adresse</td>
						<td colspan="3"><input type="text" name="adresse" value="<?php echo $_POST['adresse']; ?>" style="width:200px;background:#555;border:1px solid #AAA;color:#AAA;" /></td>
					</tr>
					<tr>
						<td>Code postal&nbsp;&nbsp;</td>
						<td><input type="text" name="code_postal" value="<?php echo $_POST['code_postal']; ?>" style="width:40px;background:#555;border:1px solid #AAA;color:#AAA;" /></td>
						<td>Ville</td>
						<td><input type="text" name="ville" value="<?php echo $_POST['ville']; ?>" style="width:90px;background:#555;border:1px solid #AAA;color:#AAA;" /></td>
					</tr>
					<tr>
						<td>Pays</td>
						<td><input type="text" name="pays" value="<?php echo $_POST['pays']; ?>" style="width:90px;background:#555;border:1px solid #AAA;color:#AAA;" /></td>
						<td colspan="2"></td>
					</tr>
				</table>
				<br />
				<div style="text-align:center;"><input type="submit" name="valider" value="Valider" style="width:90px;background:#555;border:1px solid #AAA;color:#AAA;cursor:pointer;" /></div>
				<input type="hidden" name="alors" value="oui" />
				</form>
				<br />
				<form action="#" method="post">
				<h3 style="border-bottom:1px solid #AAA;margin:10px 0;">Je ne souhaite pas participer au concours</h3>
				<br />
				<div style="text-align:center;"><input type="submit" name="valider" value="Retourner � Dreadcast" style="width:150px;background:#555;border:1px solid #AAA;color:#AAA;cursor:pointer;" /></div>
				<input type="hidden" name="alors" value="non" />
				</form>
			</div>
			
		</div>
		
	</body>

</html>
