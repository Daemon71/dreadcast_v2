<?php
session_start();

if($_SESSION['statut'] != "Administrateur")
	{
	print('<meta http-equiv="refresh" content="0 ; url=http://v2.dreadcast.net/ingame/engine.php"> ');
	exit();
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
				La lotterie est finie ! Voici donc les noms des heureux chanceux qui pourront contacter les admins pour récuperer leur lot dans la semaine à venir.
			</div>
			<div id="tab" style="background:url(im_objets/tabgagnef.gif) 0 0 no-repeat;"> <!-- IMAGE TABGAGNEJP ET TABGAGNEJVIP POUR JOUEUR + ET JOUEUR VIP -->
				<p id="case00">Scotch<br />Filix<br />Mystic<br />Xelse<br />Valane</p><p id="case01">Disklad<br />K444719<br />Barrio<br />Eruilthal<br />Dvd22</p><p id="case02">Maxence<br />Bichnette<br />Valdorian<br />Mateo<br />Pegaze</p>
				<p id="case10"><span class="col1">Alexiis<br />Dominator<br />Rimk<br />Styron<br />Malagasy</span><span class="col2">Shadowboss<br />Lothaire<br />Sweeno<br />Gamaqa<br />Blackberry</span></p><p id="case11"><span class="col1">Tayila<br />Aversiste<br />KillerM4A1<br />Nico31<br />Barbouille</span><span class="col2">Priss<br />Sylvie<br />Nathoven<br />Tocenly<br />Alec</span></p><p id="case12"><span class="col1">Jfcflo2<br />Urkane<br />Mamdo<br />Hield<br />Spomwa</span><span class="col2">Punkeur<br />Adu<br />Charlotte<br />Reppa<br />Nikkon</span></p>
				<p id="case20"><span class="col1">Gwen0609<br />R0ck<br />Luxwei<br />Mornaque<br />Jerome<br />Maradane<br />Kodeco<br />Novo</span><span class="col2">Fud<br />Lotus<br />Pal2<br />Smarty<br />Valaria<br />ArkSer<br />Louk</span></p><p id="case21"><span class="col1">Lamiss<br />Paikan<br />Nagalak<br />Maggeus<br />Clochette<br />Alo<br />Avatar<br />Irobot</span><span class="col2">7Seth7<br />Corsica20<br />StGab<br />Frs<br />Kaivun<br />Joolushka<br />Healy</span></p><p id="case22"><span class="col1">Nazgul<br />Lala<br />Zoliv<br />Genjuro<br />Kaivun<br />Kandar<br />Kirika<br />Artemisfowl</span><span class="col2">Tef<br />ToCA<br />GotGot<br />Soweto<br />Arch<br />Sauju<br />Fud</span></p>
			</div>
			
			<div id="retour">
				<p>Bonne continuation sur DreadCast !<a href="engine.php"></a></p>
			</div>
			
		</div>
		
	</body>

</html>
