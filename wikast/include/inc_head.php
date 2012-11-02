<?php 
session_start();

if($_SERVER["HTTP_HOST"] == "v2.dreadcast.net") 
	{
	$uri = $_SERVER["REQUEST_URI"];
	if (preg_match("/^\/wikast\//", $uri)) {
		$uri = substr($uri, 7);
	}
	header("Status: 301 Moved Permanently"); 
	header("Location: http://www.wikast.net".$uri);
	exit();
	}

$url = $_SERVER['SERVER_NAME'];
$url = explode(".", $url);

$nom = $url[0];

if($nom != 'wikast' && $nom != 'www' && !preg_match('/edc\=visio\.php\?auteur\=/' . $nom, $_SERVER['REQUEST_URI'])) {
	header('Status: 301 Moved Permanently');
	header('Location: http://v2.dreadcast.net/wikast/edc=visio.php?auteur=' . $nom);
	exit();
}

// Avec et Sans WWW
if(strstr($_SERVER['HTTP_HOST'], 'www') == FALSE && $url[0] != 'wikast' && $url[1] != 'dreadcast') {
    header('Status: 301 Moved Permanently');
    header('Location: http://www.' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI']);
    exit();
}

include('inc_publicite.php');
include('inc_fonctions.php');

?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
	<head>
		<title>
		<?php
		
		// VERIFICATIONS //
		
		// sujet privé
		if ($_SERVER['PHP_SELF'] == '/forum=perso.php') {
			$forum_perso = true;
		} elseif (preg_match("/sujet\.php/", $_SERVER['PHP_SELF'])) {
			$idSujet = $_GET['id'];
			
			$db = mysql_connect('localhost', 'CENSURE', 'CENSURE') or die("Erreur de connexion au serveur des messages.");
			mysql_select_db('CENSURE',$db) or die("Erreur de connexion a la base de donnees des messages.");
				
			$sql = 'SELECT p.pseudo, s.categorie FROM wikast_forum_permissions_tbl p INNER JOIN wikast_forum_sujets_tbl s ON p.sujet = s.id WHERE p.sujet = "'.$idSujet.'"';
			$req = mysql_query($sql);
			$res = mysql_num_rows($req);
			
			if ($res && mysql_result($req, 0, categorie) == -1) {
				$sujet_perso = true;
			
				$peut_lire_sujet = false;
				for ($i = 0; $i < $res; $i++) {
					if (strtolower(mysql_result($req, $i, pseudo)) == strtolower($_SESSION['pseudo'])) {
						$peut_lire_sujet = true;
						break;
					}
				}
				
				if(!$peut_lire_sujet) {
					$sql = 'INSERT INTO triche_tbl VALUES (NULL, '.$_SESSION['pseudo'].', '.time().', '.$idSujet.', 0, "Tentative d\'acces au FP")';
					mysql_query($sql);
					
					print('<meta http-equiv="refresh" content="0 ; url=index.php?1"> ');
					exit();
				}
			}
			
			mysql_close($db);
		}
		
		if((preg_match("/sujet/", $_SERVER['PHP_SELF']) || preg_match("/forum/", $_SERVER['PHP_SELF'])) && $_SESSION['statut'] != "Administrateur" && !$forum_perso && !$sujet_perso)
			{
			$db = mysql_connect('localhost', 'CENSURE', 'CENSURE') or die("Erreur de connexion au serveur des messages.");
			mysql_select_db('CENSURE',$db) or die("Erreur de connexion a la base de donnees des messages.");
				
			$sql = 'SELECT id FROM wikast_forum_permissions_tbl WHERE sujet="0" AND pseudo="'.$_SESSION['pseudo'].'" AND statut > '.time();
			$req = mysql_query($sql);
			$res = mysql_num_rows($req);
			
			mysql_close($db);
			
			if($res != 0)
				{
				print('<meta http-equiv="refresh" content="0 ; url=index.php"> ');
				exit();
				}
			}
		
		if($_GET['partie']=="Divers" AND $_GET['sous-partie']=="" AND $_SESSION['statut']!="Administrateur")
			{
			print('<meta http-equiv="refresh" content="0 ; url=index.php"> ');
			exit();
			}
			
		if($_GET['partie']=="Divers" AND $_GET['sous-partie']=="65" AND $_SESSION['statut']!="Administrateur")
			{
			print('<meta http-equiv="refresh" content="0 ; url=index.php"> ');
			exit();
			}
		
		if($_GET['partie']=="Divers" AND $_GET['sous-partie']=="126" AND $_SESSION['statut']!="Administrateur")
			{
			$db = mysql_connect('localhost', 'CENSURE', 'CENSURE') or die("Erreur de connexion au serveur des messages.");
			mysql_select_db('CENSURE',$db) or die("Erreur de connexion a la base de donnees des messages.");
				
			$sql = 'SELECT id FROM wikast_forum_structure_tbl WHERE admin="'.$_SESSION['pseudo'].'" AND (id="1" OR id="2" OR id="3" OR id="4")' ;
			$req = mysql_query($sql);
			$res = mysql_num_rows($req);
			
			mysql_close($db);
			
			if($res==0)
				{
				print('<meta http-equiv="refresh" content="0 ; url=index.php"> ');
				exit();
				}
			}
		
		if($_GET['partie']=="Imperium" AND $_GET['sous-partie']=="" AND $_SESSION['statut']!="Administrateur")
			{
			print('<meta http-equiv="refresh" content="0 ; url=index.php"> ');
			exit();
			}
		
		if($_GET['partie']=="Imperium" AND $_GET['sous-partie']=="118" AND $_SESSION['statut']!="Administrateur")
			{
			$db = mysql_connect('localhost', 'CENSURE', 'CENSURE') or die("Erreur de connexion au serveur des messages.");
			mysql_select_db('CENSURE',$db) or die("Erreur de connexion a la base de donnees des messages.");
			
			$sql = 'SELECT id FROM principal_tbl WHERE pseudo="'.$_SESSION['pseudo'].'" AND (entreprise="Conseil Imperial" AND (type="President" OR type="Premier Conseiller" OR type="Conseiller Imperial"))';
			$req = mysql_query($sql);
			$res = mysql_num_rows($req);
			
			mysql_close($db);
			
			if($res==0)
				{
				print('<meta http-equiv="refresh" content="0 ; url=index.php"> ');
				exit();
				}
			}

		if($_GET['partie']=="Imperium" AND $_GET['sous-partie']=="144" AND $_SESSION['statut']!="Administrateur")
			{
			$db = mysql_connect('localhost', 'CENSURE', 'CENSURE') or die("Erreur de connexion au serveur des messages.");
			mysql_select_db('CENSURE',$db) or die("Erreur de connexion a la base de donnees des messages.");
			
			$sql = 'SELECT id FROM principal_tbl WHERE pseudo="'.$_SESSION['pseudo'].'" AND ((type="President" AND entreprise="Conseil Imperial") OR (type="Directeur des Organisations" AND entreprise="DOI") OR (type="Premier consul" AND entreprise="Chambre des lois") OR (type="Directeur du DI2RCO" AND entreprise="DI2RCO") OR (type="Directeur des services" AND entreprise="Services techniques de la ville") OR (type="Directeur du CIPE" AND entreprise="CIPE") OR (type="Directeur du CIE" AND entreprise="CIE") OR (type="Chef de la Police" AND entreprise="Police") OR (type="Directeur de la Prison" AND entreprise="Prison") OR (type="Directeur du DC Network" AND entreprise="DC Network"))' ;
			$req = mysql_query($sql);
			$res = mysql_num_rows($req);
			
			mysql_close($db);
			
			if($res==0)
				{
				print('<meta http-equiv="refresh" content="0 ; url=index.php"> ');
				exit();
				}
			}
			
		if($_GET['partie']=="Imperium" AND $_GET['sous-partie']=="145" AND $_SESSION['statut']!="Administrateur")
			{
			$db = mysql_connect('localhost', 'CENSURE', 'CENSURE') or die("Erreur de connexion au serveur des messages.");
			mysql_select_db('CENSURE',$db) or die("Erreur de connexion a la base de donnees des messages.");
			
			$sql = 'SELECT id FROM principal_tbl WHERE pseudo="'.$_SESSION['pseudo'].'" AND ((type="Premier consul" OR type="Consul" OR type="Proconsul") AND entreprise="Chambre des lois")' ;
			$req = mysql_query($sql);
			$res = mysql_num_rows($req);
			
			mysql_close($db);
			
			if($res==0)
				{
				print('<meta http-equiv="refresh" content="0 ; url=index.php"> ');
				exit();
				}
			}
		
		if(preg_match("/forum\=accueil/",$_SERVER['PHP_SELF']))
			{
			print('Wikast - Forum de Dreadcast');
			}
		elseif(preg_match("/forum/",$_SERVER['PHP_SELF']))
			{
			if($_GET['partie']=="")
				{
				print('Wikast - Forum de Dreadcast');
				}
			elseif($_GET['sous-partie']=="")
				{
				print('Forum de Dreadcast - Forum '.$_GET['partie']);
				}
			else
				{
				$db = mysql_connect('localhost', 'CENSURE', 'CENSURE') or die("Erreur de connexion au serveur des messages.");
				mysql_select_db('CENSURE',$db) or die("Erreur de connexion a la base de donnees des messages.");
	
				$sql = 'SELECT nom FROM wikast_forum_structure_tbl WHERE id= "'.$_GET['sous-partie'].'"' ;
				$req = mysql_query($sql);
				$res = mysql_num_rows($req);
	
				mysql_close($db);
				
				if($res!=0) print('Forum de Dreadcast - Forum '.$_GET['partie'].' > '.mysql_result($req,0,nom));
				}
			}
		elseif(preg_match("/sujet/",$_SERVER['PHP_SELF']))
			{
			if($_GET['id']=="")
				{
				print('Wikast');
				}
			else
				{
				$db = mysql_connect('localhost', 'CENSURE', 'CENSURE') or die("Erreur de connexion au serveur des messages.");
				mysql_select_db('CENSURE',$db) or die("Erreur de connexion a la base de donnees des messages.");
	
				$sql = 'SELECT nom FROM wikast_forum_sujets_tbl WHERE id= "'.$_GET['id'].'"' ;
				$req = mysql_query($sql);
				$res = mysql_num_rows($req);
	
				mysql_close($db);
				
				if($res!=0) print('Forum de Dreadcast - '.mysql_result($req,0,nom));
				}
			}
		elseif(preg_match("/edc\=visio\.php/",$_SERVER['PHP_SELF']))
			{
			print('Wikast - EDC de '.$_GET['auteur']);
			}
		elseif(preg_match("/edc\=aide/",$_SERVER['PHP_SELF']))
			{
			print('Wikast - Aide sur les EDC de Dreadcast');
			}
		elseif(preg_match("/edc/",$_SERVER['PHP_SELF']))
			{
			if($_SESSION['id']!="") print('Wikast - EDC de '.$_SESSION['pseudo']);
			else print('EDC de Dreadcast');
			}
		elseif(preg_match("/edc\=recherche\.php/",$_SERVER['PHP_SELF']))
			{
			print('Wikast - Recherche sur les EDC de Dreadcast');
			}
		elseif(preg_match("/wiki/",$_SERVER['PHP_SELF']))
			{
			print('Wiki de Dreadcast');
			
			$db = mysql_connect('localhost', 'CENSURE', 'CENSURE') or die("Erreur de connexion au serveur des messages.");
			mysql_select_db('CENSURE',$db) or die("Erreur de connexion a la base de donnees des messages.");
	
			$sql = 'SELECT titre FROM wikast_wiki_articles_tbl WHERE id="'.$_GET['id'].'"' ;
			$req = mysql_query($sql);
			$res = mysql_num_rows($req);
	
			mysql_close($db);
				
			if($res!=0) print(' - '.mysql_result($req,0,titre));
			}
		else
			{
			print('Wikast - Plateforme communautaire du jeu Dreadcast');
			}
		?>
		</title>
		<meta name="description" content="<?php
        
        if(preg_match("/sujet/",$_SERVER['PHP_SELF']))
			{
			if($_GET['id']=="")
				{
				print('Wikast - ');
				}
			else
				{
				$db = mysql_connect('localhost', 'CENSURE', 'CENSURE') or die("Erreur de connexion au serveur des messages.");
				mysql_select_db('CENSURE',$db) or die("Erreur de connexion a la base de donnees des messages.");
	
				$sql = 'SELECT nom,contenu FROM wikast_forum_sujets_tbl WHERE id= "'.$_GET['id'].'"' ;
				$req = mysql_query($sql);
				$res = mysql_num_rows($req);
	
				mysql_close($db);
				
				if($res!=0)
					{
					print(mysql_result($req,0,nom).' - ');
					$contenu = str_replace("<br />"," ",mysql_result($req,0,contenu));
					$contenu = str_replace("\n"," ",$contenu);
					$contenu = str_replace("
"," ",$contenu);					
					
					print(substr($contenu,0,125));
					
					print('...');
					}
				}
			}
		else
			{
			print("Dreadcast est un jeu de r&ocirc;le en ligne futuriste gratuit : simulation d'un monde virtuel en ligne, communaut&eacute; cyber-punk, &agrave; vous de jouer sans rien t&eacute;l&eacute;charger.");
			}
        
        ?>" />
		<meta name="keywords" lang="fr" content="<?php
		
		if(preg_match("/wiki\.php/",$_SERVER['PHP_SELF']))
			{
			if($_GET['id']=="")
				{
				print('Dreadcast, futuriste, jeu video, jeu en ligne, communauté, simulation, jeux gratuits, monde virtuel, RPG, cyber-punk, jeux, rôles, mmo, mmorpg, php, futur, empire, communautaire, gratuit, multijoueur, jeu de gestion, jeu de stratégie, jeu online, Action-RPG, rôle, Science-Fiction, Fantastique, heroic fantasy , jouer, gratui, gratuits, jeux en ligne, internet, net, web');
				}
			else
				{
				$db = mysql_connect('localhost', 'CENSURE', 'CENSURE') or die("Erreur de connexion au serveur des messages.");
				mysql_select_db('CENSURE',$db) or die("Erreur de connexion a la base de donnees des messages.");
	
				$sql = 'SELECT titre FROM wikast_wiki_articles_tbl WHERE id="'.$_GET['id'].'"' ;
				$req = mysql_query($sql);
				$res = mysql_num_rows($req);
				
				if($res>0)
					{
					$sql = 'SELECT mots FROM wikast_wiki_articles_tbl WHERE titre="'.mysql_result($req,0,titre).'" AND etat = 2' ;
					$req = mysql_query($sql);
					$res = mysql_num_rows($req);
					
					if($res>0)
						{
						if(mysql_result($req,0,mots)!="") print('Dreadcast, wikast, '.mysql_result($req,0,mots));
						else print('Dreadcast, wikast, futuriste, jeu video, jeu en ligne, communauté, simulation, jeux gratuits, monde virtuel, RPG, cyber-punk, jeux, rôles, mmo, mmorpg, php, futur, empire, communautaire, gratuit, multijoueur, jeu de gestion, jeu de stratégie, jeu online, Action-RPG, rôle, Science-Fiction, Fantastique, heroic fantasy , jouer, gratui, gratuits, jeux en ligne, internet, net, web');
						}
					else print('Dreadcast, wikast, futuriste, jeu video, jeu en ligne, communauté, simulation, jeux gratuits, monde virtuel, RPG, cyber-punk, jeux, rôles, mmo, mmorpg, php, futur, empire, communautaire, gratuit, multijoueur, jeu de gestion, jeu de stratégie, jeu online, Action-RPG, rôle, Science-Fiction, Fantastique, heroic fantasy , jouer, gratui, gratuits, jeux en ligne, internet, net, web');
					}
				else print('Dreadcast, wikast, futuriste, jeu video, jeu en ligne, communauté, simulation, jeux gratuits, monde virtuel, RPG, cyber-punk, jeux, rôles, mmo, mmorpg, php, futur, empire, communautaire, gratuit, multijoueur, jeu de gestion, jeu de stratégie, jeu online, Action-RPG, rôle, Science-Fiction, Fantastique, heroic fantasy , jouer, gratui, gratuits, jeux en ligne, internet, net, web');
				
				mysql_close($db);
				}
			}
		else print('Dreadcast, wikast, futuriste, jeu video, jeu en ligne, communauté, simulation, jeux gratuits, monde virtuel, RPG, cyber-punk, jeux, rôles, mmo, mmorpg, php, futur, empire, communautaire, gratuit, multijoueur, jeu de gestion, jeu de stratégie, jeu online, Action-RPG, rôle, Science-Fiction, Fantastique, heroic fantasy , jouer, gratui, gratuits, jeux en ligne, internet, net, web');
		
		?>" />
		<meta name="robots" content="all" />
		<meta name="revisit-after" content="7 days" />
		<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
		<meta http-equiv="Content-Style-Type" content="text/css" />
		<meta http-equiv="Content-Language" content="fr" />
		<!--[if IE]><link rel="stylesheet" media="screen" type="text/css" title="Test" href="wikistyleie.css" /><![endif]-->
		<!--[if !IE]><--><link rel="stylesheet" media="screen" type="text/css" title="Test" href="wikistyleff.css" /><!--><![endif]-->
		<link rel="shortcut icon" type="image/x-icon" href="design/favicon.ico" />
		
		<script language="JavaScript" type="text/javascript">
		function ConfirmSuppr(id,mess)
		{
       		if(mess==-1)
       		{
       			if(confirm("Voulez-vous vraiment supprimer ce sujet et tout ce qu'il contient ?"))
       			{
    	       	document.location.href='sujet=supprimer.php?id='+id+'&mess='+mess;
	       		}
       		}
       		else
       		{
       			if(confirm("Voulez-vous vraiment supprimer ce message ?"))
       			{
    	       	document.location.href='sujet=supprimer.php?id='+id+'&mess='+mess;
	       		}
       		}
   		}
   		
   		function ConfirmSupprArticle(article)
		{
       		if(confirm("Voulez-vous vraiment supprimer cet article ?"))
       		{
           	document.location.href='edc=supprimer.php?article='+article;
       		}
   		}
   		
   		function MM_jumpMenu(targ,selObj,restore)
   		{ //v3.0
		  eval(targ+".location='"+selObj.options[selObj.selectedIndex].value+"'");
		  if (restore) selObj.selectedIndex=0;
		}
   		
		function AddText(text1,hop,text2)
		{
			var ta = document.getElementById('textarea');
			if (document.selection)
			{
				var str = document.selection.createRange().text;
				ta.focus();
				var sel = document.selection.createRange();
				if (text2!="")
				{
					if (str=="")
					{
						var instances = countInstances(text1,text2);
						if (instances%2 != 0)
						{	
							sel.text = sel.text + text2;
						}
						else
						{
							sel.text = sel.text + text1;
						}
					}
					else
					{
						sel.text = text1 + sel.text + text2;
					}
				}
				else
				{
					sel.text = sel.text + text1;
				}
			}
			else if (ta.selectionStart || ta.selectionStart == 0)
			{
				if (ta.selectionEnd > ta.value.length)
				{
					ta.selectionEnd = ta.value.length;
				}
				var firstPos = ta.selectionStart;
				var secondPos = ta.selectionEnd+text1.length;
				var texteScrollTop = ta.scrollTop;
				ta.value=ta.value.slice(0,firstPos)+text1+ta.value.slice(firstPos);
				ta.value=ta.value.slice(0,secondPos)+text2+ta.value.slice(secondPos);
				ta.selectionStart = firstPos+text1.length;
				ta.selectionEnd = secondPos;
				ta.focus();
				ta.scrollTop = texteScrollTop;
			}
			else
			{ // Opera
				var sel = document.poster.texte;
				var instances = countInstances(text1,text2);
				if (instances%2 != 0 && text2 != "")
				{
					sel.value = sel.value + text2;
				}
				else
				{
					sel.value = sel.value + text1;
				}
			}
		}
		
		function AddText1(text1,hop,text2)
		{
			var ta = document.getElementById('textarea1');
			if (document.selection)
			{
				var str = document.selection.createRange().text;
				ta.focus();
				var sel = document.selection.createRange();
				if (text2!="")
				{
					if (str=="")
					{
						var instances = countInstances(text1,text2);
						if (instances%2 != 0)
						{	
							sel.text = sel.text + text2;
						}
						else
						{
							sel.text = sel.text + text1;
						}
					}
					else
					{
						sel.text = text1 + sel.text + text2;
					}
				}
				else
				{
					sel.text = sel.text + text1;
				}
			}
			else if (ta.selectionStart || ta.selectionStart == 0)
			{
				if (ta.selectionEnd > ta.value.length)
				{
					ta.selectionEnd = ta.value.length;
				}
				var firstPos = ta.selectionStart;
				var secondPos = ta.selectionEnd+text1.length;
				var texteScrollTop = ta.scrollTop;
				ta.value=ta.value.slice(0,firstPos)+text1+ta.value.slice(firstPos);
				ta.value=ta.value.slice(0,secondPos)+text2+ta.value.slice(secondPos);
				ta.selectionStart = firstPos+text1.length;
				ta.selectionEnd = secondPos;
				ta.focus();
				ta.scrollTop = texteScrollTop;
			}
			else
			{ // Opera
				var sel = document.poster.texte;
				var instances = countInstances(text1,text2);
				if (instances%2 != 0 && text2 != "")
				{
					sel.value = sel.value + text2;
				}
				else
				{
					sel.value = sel.value + text1;
				}
			}
		}
		
		function AddText2(text1,hop,text2)
		{
			var ta = document.getElementById('textarea2');
			if (document.selection)
			{
				var str = document.selection.createRange().text;
				ta.focus();
				var sel = document.selection.createRange();
				if (text2!="")
				{
					if (str=="")
					{
						var instances = countInstances(text1,text2);
						if (instances%2 != 0)
						{	
							sel.text = sel.text + text2;
						}
						else
						{
							sel.text = sel.text + text1;
						}
					}
					else
					{
						sel.text = text1 + sel.text + text2;
					}
				}
				else
				{
					sel.text = sel.text + text1;
				}
			}
			else if (ta.selectionStart || ta.selectionStart == 0)
			{
				if (ta.selectionEnd > ta.value.length)
				{
					ta.selectionEnd = ta.value.length;
				}
				var firstPos = ta.selectionStart;
				var secondPos = ta.selectionEnd+text1.length;
				var texteScrollTop = ta.scrollTop;
				ta.value=ta.value.slice(0,firstPos)+text1+ta.value.slice(firstPos);
				ta.value=ta.value.slice(0,secondPos)+text2+ta.value.slice(secondPos);
				ta.selectionStart = firstPos+text1.length;
				ta.selectionEnd = secondPos;
				ta.focus();
				ta.scrollTop = texteScrollTop;
			}
			else
			{ // Opera
				var sel = document.poster.texte;
				var instances = countInstances(text1,text2);
				if (instances%2 != 0 && text2 != "")
				{
					sel.value = sel.value + text2;
				}
				else
				{
					sel.value = sel.value + text1;
				}
			}
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
			
		function affiche_inl(id,boole) {
				if (document.getElementById)
					{
					if(boole) document.getElementById(id).style.display="inline";
					else document.getElementById(id).style.display="none";
					}
				if (document.all && !document.getElementById)
					{
					if(boole) document.all[id].style.display="inline";
					else document.all[id].style.display="none";
					}
				if (document.layers)
					{
					if(boole) document.layers[id].display="inline";
					else document.layers[id].display="none";
					}
			}
			
		function change_valeur(id,valeur) {
				if (document.getElementById) document.getElementById(id).value=valeur;
				if (document.all && !document.getElementById) document.all[id].value=valeur;
				if (document.layers) document.layers[id].value=valeur;
			}
		</script>
	
	</head>

	<body>
	<?php
	
	
	$db = mysql_connect('localhost', 'CENSURE', 'CENSURE') or die("Erreur de connexion au serveur des messages.");
	mysql_select_db('CENSURE',$db) or die("Erreur de connexion a la base de donnees des messages.");
	
	print('<div id="navigateur" onmouseover="javascript:affiche_art(\'manavig\',true);" onmouseout="javascript:affiche_art(\'manavig\',false);affiche_art(\'nsujet\',false);">
	<div id="intitule">Barre de navigation</div>
	<div class="btn"></div>
	</div>');
	print('
	<div id="manavig" style="display:none;" onmouseover="javascript:affiche_art(\'manavig\',true);" onmouseout="javascript:affiche_art(\'manavig\',false);">
	');if($_SESSION['pseudo']!="") print('Bienvenue '.$_SESSION['pseudo'].'<br />');print('
	<h4>Navigation</h4>
	<a href="forum=accueil.php">&raquo; Vers le forum</a>
	<a href="edc.php">&raquo; Vers mon EDC</a>
	<a href="wiki=accueil.php">&raquo; Vers le wiki</a>
	<a href="sondages=accueil.php">&raquo; Vers les sondages</a>
	');if($_SESSION['pseudo']!="") print('<a href="forum=perso.php">&raquo; Vers mon forum Perso</a>');print('
	<!--<a href="http://v2.dreadcast.net/ingame/engine.php" onclick="window.open(this.href); return false;">&raquo; Vers DreadCast</a>-->
	<a href="http://v2.dreadcast.net/chat" onclick="window.open(this.href); return false;">&raquo; Vers l\'IRC de Dreadcast</a>');
		
	if($_SESSION['pseudo']!="")
		{
		print('<h4>Actions</h4>
		<a href="javascript:affiche_art(\'nsujet\',true);">&raquo; Nouveau sujet de forum</a>
		<div onmouseover="javascript:affiche_art(\'manavig\',true);" style="display:none;" id="nsujet">
			<h4>dans le forum G&eacute;n&eacute;ral</h4>');
			$sql = 'SELECT id,nom FROM wikast_forum_structure_tbl WHERE type="1" ORDER BY id ASC' ;
			$req = mysql_query($sql);
			$res = mysql_num_rows($req);
			for($i=0;$i<$res;$i++) print('<a href="sujet=nouveau.php?ssfid='.mysql_result($req,$i,id).'">'.mysql_result($req,$i,nom).'</a>');
			print('<h4>dans le forum Hors Sujet</h4>');
			$sql = 'SELECT id,nom FROM wikast_forum_structure_tbl WHERE type="2" ORDER BY id ASC' ;
			$req = mysql_query($sql);
			$res = mysql_num_rows($req);
			for($i=0;$i<$res;$i++) print('<a href="sujet=nouveau.php?ssfid='.mysql_result($req,$i,id).'">'.mysql_result($req,$i,nom).'</a>');
			print('<h4>dans le forum Role Play</h4>');
			$sql = 'SELECT id,nom FROM wikast_forum_structure_tbl WHERE type="3" ORDER BY id ASC' ;
			$req = mysql_query($sql);
			$res = mysql_num_rows($req);
			for($i=0;$i<$res;$i++) print('<a href="sujet=nouveau.php?ssfid='.mysql_result($req,$i,id).'">'.mysql_result($req,$i,nom).'</a>');
			print('<h4>dans le forum Politique</h4>');
			$sql = 'SELECT id,nom FROM wikast_forum_structure_tbl WHERE type="4" ORDER BY id ASC' ;
			$req = mysql_query($sql);
			$res = mysql_num_rows($req);
			for($i=0;$i<$res;$i++) print('<a href="sujet=nouveau.php?ssfid='.mysql_result($req,$i,id).'">'.mysql_result($req,$i,nom).'</a>');
			$sql = 'SELECT cercle FROM cercles_tbl WHERE pseudo="'.$_SESSION['pseudo'].'" ORDER BY id ASC' ; // CERCLE
			$req = mysql_query($sql);
			$res = mysql_num_rows($req);
			if($res!=0)
				{
				$nomcercle=mysql_result($req,0,cercle);
				print('<h4>dans le forum du cercle '.$nomcercle.'</h4>');
				$sql = 'SELECT id,nom FROM wikast_forum_structure_tbl WHERE type=(SELECT id FROM wikast_forum_structure_tbl WHERE nom="Cercle '.$nomcercle.'") ORDER BY id ASC' ;
				$req = mysql_query($sql);
				$res = mysql_num_rows($req);
				for($i=0;$i<$res;$i++) print('<a href="sujet=nouveau.php?ssfid='.mysql_result($req,$i,id).'">'.mysql_result($req,$i,nom).'</a>');
				
				}
		print('</div>
		<a href="edc=nouveau.php">&raquo; Nouvel article d\'EDC</a>');
		
		if($_SESSION['statut']=="Compte VIP" OR $_SESSION['statut']=="Administrateur")
			{
			print('<a href="wiki=ecrire.php">&raquo; Nouvel article de Wiki</a>
			<a href="sondages=nouveau.php">&raquo; Nouveau sondage</a>
			<a href="sujet=nouveau.php?prive=ok">&raquo; Nouveau sujet priv&eacute;</a>');
			}
		
		$sql = 'SELECT id FROM principal_tbl WHERE pseudo="'.$_SESSION['pseudo'].'" AND (entreprise="Conseil Imperial" AND (type="President" OR type="Premier Conseiller" OR type="Conseiller Imperial"))';
		$req = mysql_query($sql);
		$res = mysql_num_rows($req);
			
		if($res!=0) $conseil="ok";
			
		$sql = 'SELECT id FROM principal_tbl WHERE pseudo="'.$_SESSION['pseudo'].'" AND ((type="President" AND entreprise="Conseil Imperial") OR (type="Directeur des Organisations" AND entreprise="DOI") OR (type="Premier consul" AND entreprise="Chambre des lois") OR (type="Directeur du DI2RCO" AND entreprise="DI2RCO") OR (type="Directeur des services" AND entreprise="Services techniques de la ville") OR (type="Directeur du CIPE" AND entreprise="CIPE") OR (type="Directeur du CIE" AND entreprise="CIE") OR (type="Chef de la Police" AND entreprise="Police") OR (type="Directeur de la Prison" AND entreprise="Prison") OR (type="Directeur du DC Network" AND entreprise="DC Network"))' ;
		$req = mysql_query($sql);
		$res = mysql_num_rows($req);
			
		if($res!=0) $directoire="ok";
			
		$sql = 'SELECT id FROM principal_tbl WHERE pseudo="'.$_SESSION['pseudo'].'" AND ((type="Premier consul" OR type="Consul" OR type="Proconsul") AND entreprise="Chambre des lois")' ;
		$req = mysql_query($sql);
		$res = mysql_num_rows($req);
			
		if($res!=0) $chambre="ok";
		
		$sql = 'SELECT id,nom FROM wikast_forum_structure_tbl WHERE admin="'.$_SESSION['pseudo'].'" AND (id=1 OR id=2 OR id=3 OR id=4) ORDER BY nom' ; // Moderateur
		$req = mysql_query($sql);
		$res = mysql_num_rows($req);
		
		if($res!=0) $modo="ok";
		
		$sql = 'SELECT id,nom FROM wikast_forum_structure_tbl WHERE admin="'.$_SESSION['pseudo'].'" AND type="-1" ORDER BY nom' ; // Moderateur
		$req = mysql_query($sql);
		$res = mysql_num_rows($req);
		
		if($res!=0) $modo2="ok";
		
		$sql2 = 'SELECT cercle FROM cercles_tbl WHERE pseudo="'.$_SESSION['pseudo'].'"'; // Cercle
		$req2 = mysql_query($sql2);
		$res2 = mysql_num_rows($req2);
		
		if($res2!=0) $cercle="ok";
		
		if($modo=="ok" OR $modo2=="ok" OR $conseil=="ok" OR $directoire=="ok" OR $chambre=="ok" OR $_SESSION['statut']=="Administrateur" OR $cercle=="ok")
			{
			print('<h4>Extra</h4>');
			for($i=0;$i<$res;$i++) print('<a href="forum=moderation.php?forum='.mysql_result($req,$i,id).'">&raquo; Forum '.mysql_result($req,$i,nom).'</a>');
			if($cercle=="ok")  print('<a href="forum.php?partie=Cercle '.mysql_result($req2,0,cercle).'">&raquo; Cercle '.mysql_result($req2,0,cercle).'</a>');
			if($modo=="ok" OR $_SESSION['statut']=="Administrateur") print('<a href="forum.php?partie=Divers&sous-partie=126">&raquo; Forum des mod&eacute;rateurs</a>');
			if($conseil=="ok" OR $_SESSION['statut']=="Administrateur") print('<a href="forum.php?partie=Imperium&sous-partie=118">&raquo; Forum du Conseil Imp&eacute;rial</a>');
			if($directoire=="ok" OR $_SESSION['statut']=="Administrateur") print('<a href="forum.php?partie=Imperium&sous-partie=144">&raquo; Forum du Directoire</a>');
			if($chambre=="ok" OR $_SESSION['statut']=="Administrateur") print('<a href="forum.php?partie=Imperium&sous-partie=145">&raquo; Forum de la Chambre des Lois</a>');
			if($_SESSION['statut']=="Administrateur") print('<a href="forum=administration.php">&raquo; Administration du Wikast</a>');
			}
		/*
		print('<h4>Information</h4>');
		
		$sql = 'SELECT id FROM messages_tbl WHERE cible="'.$_SESSION['pseudo'].'" AND nouveau="oui"' ;
		$req = mysql_query($sql);
		$res = mysql_num_rows($req);
		
		if($res==0) print('Pas de nouveau message<br />');
		else print('<a href="http://v2.dreadcast.net/ingame/engine=messages.php" onclick="window.open(this.href); return false;">'.$res.' nouveau(x) message(s)</a>');
		*/
		
		//$sql = 'SELECT id FROM wikast_edc_commentaires_tbl WHERE cible="'.$_SESSION['pseudo'].'" AND nouveau="oui"' ;
		//$req = mysql_query($sql);
		//$res = mysql_num_rows($req);
		
		//if($res==0) print('Pas de nouveau message<br />');
		//else print('<a href="edc.php">'.$res.' nouveau(x) commentaire(s)');
		
		//print('Pas de nouveau commentaire<br />');
		
		}
		
	print('</div>');
		
	mysql_close($db);
	
	?>
