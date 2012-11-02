<?php
session_start(); 

print('<meta http-equiv="refresh" content="0 ; url=http://v2.dreadcast.net/"> ');
exit();

if(isset($_COOKIE['panneau_planete'])) $panneau_planete = $_COOKIE['panneau_planete'];
else { setcookie('panneau_planete', 'on', (time() + 365*24*3600));$panneau_planete = "on"; }

if(isset($_COOKIE['panneau_outils'])) $panneau_outils = $_COOKIE['panneau_outils'];
else { setcookie('panneau_outils', 'on', (time() + 365*24*3600));$panneau_outils = "on"; }

if(isset($_COOKIE['panneau_pub_ext'])) $panneau_pub_ext = $_COOKIE['panneau_pub_ext'];
else { setcookie('panneau_pub_ext', 'on', (time() + 365*24*3600));$panneau_pub_ext = "on"; }

if(isset($_COOKIE['panneau_pub'])) $panneau_pub = $_COOKIE['panneau_pub'];
else { setcookie('panneau_pub', 'on', (time() + 365*24*3600));$panneau_pub = "on"; }

if(isset($_COOKIE['panneau_info'])) $panneau_info = $_COOKIE['panneau_info'];
else { setcookie('panneau_info', 'on', (time() + 365*24*3600));$panneau_info = "on"; }

if(isset($_COOKIE['panneau_chat'])) $panneau_chat = $_COOKIE['panneau_chat'];
else { setcookie('panneau_chat', 'on', (time() + 365*24*3600));$panneau_chat = "on"; }

include('inc_publicite.php');
	
include('inc_fonctions.php');

$db = mysql_connect('localhost', 'CENSURE', 'CENSURE') or die("Erreur de connexion au serveur.");
mysql_select_db('CENSURE',$db) or die("Erreur de connexion a la base de donnees.");

$sql = 'SELECT id,fatigue,fatigue_max,sante,sante_max,SMS,total FROM principal_tbl WHERE id="'.$_SESSION['id'].'"' ;
$req = mysql_query($sql);
$nbre = mysql_num_rows($req);
$sms = mysql_result($req,0,SMS);

$_SESSION['fatigue'] = mysql_result($req,0,fatigue);
$_SESSION['fatiguemax'] = mysql_result($req,0,fatigue_max);
$_SESSION['sante'] = mysql_result($req,0,sante);
$_SESSION['santemax'] = mysql_result($req,0,sante_max);
$_SESSION['exp'] = mysql_result($req,0,total);
$_SESSION['expmax'] = pow(10,strlen($_SESSION['exp']));

$sql1 = 'SELECT id FROM messages_tbl WHERE cible= "'.$_SESSION['pseudo'].'" AND nouveau="oui"' ;
$req1 = mysql_query($sql1);
$a_nouveau_message = mysql_num_rows($req1);

if(($_SESSION['drogue']>0) && (time()-$_SESSION['drogue']>14400))
	{
	$sql1 = 'UPDATE principal_tbl SET sante= "1" , fatigue= "0" , drogue= "0" WHERE id= "'.$_SESSION['id'].'"' ;
	$req1 = mysql_query($sql1);
	$sql = 'INSERT INTO messages_tbl(id,auteur,cible,message,objet,moment) VALUES("","Dreadcast","'.$_SESSION['pseudo'].'","Vous venez de rechuter.<br />Votre taux de Kronatium appauvri dans le sang diminue et vous vous sentez de plus en plus faible.","Effet secondaire","'.time().'")' ;
	$req = mysql_query($sql);
	$_SESSION['drogue'] = 0;
	$_SESSION['sante'] = 1;
	$_SESSION['fatigue'] = 0;
	$a_nouveau_message = $a_nouveau_message + 1;
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
		<meta name="keywords" lang="fr" content="Dreadcast, futuriste, jeu video, jeu en ligne, communauté, simulation, jeux gratuits, monde virtuel, RPG, cyber-punk, jeux, rôles, mmo, mmorpg, php, futur, empire, communautaire, gratuit, multijoueur, jeu de gestion, jeu de stratégie, jeu online, Action-RPG, rôle, Science-Fiction, Fantastique, heroic fantasy , jouer, gratui, gratuits, jeux en ligne, internet, net, web" />
		<meta name="author" content="MSpixel" />
		<meta name="reply-to" content="dreadcast@mspixel.fr" />
		<meta name="revisit-after" content="1 day" />
		<meta name="robots" content="all" />
		<?php if($_SESSION['noCSS']=="")
		print('<!--[if IE]><link rel="stylesheet" media="screen" type="text/css" title="Dreadcast" href="mise_en_page_ie_v2.css" /><![endif]-->
		<!--[if !IE]><--><link rel="stylesheet" media="screen" type="text/css" title="Dreadcast" href="mise_en_page_ff_v2'.(($_SESSION['pseudo']=="Overflow")?'_test':'').'.css" /><!--><![endif]-->');
		?>
		<link rel="shortcut icon" type="image/x-icon" href="im_objets/favicon.ico" />
		<?php
		if(ereg("aitl2",$_SERVER['PHP_SELF']) OR ereg("redigerannonce2",$_SERVER['PHP_SELF']) OR ereg("rediger2",$_SERVER['PHP_SELF']))
		print('<script type="text/javascript" src="javascript/jScrollPane/jquery-1.2.3.min.js"></script>
		<script type="text/javascript" src="javascript/jScrollPane/jquery.dimensions.min.js"></script>
		<script type="text/javascript" src="javascript/jScrollPane/jquery.mousewheel.min.js"></script>
		<script type="text/javascript" src="javascript/jScrollPane/jScrollPane.js"></script>
		<link rel="stylesheet" type="text/css" media="all" href="javascript/jScrollPane/jScrollPane.css" />
		<script type="text/javascript">
			
			$(function()
			{
				// this initialises the demo scollpanes on the page.
				$(\'.scroll-pane\').jScrollPane({animateTo:true, animateInterval:50, animateStep:3, showArrows:true, scrollbarWidth: 15, arrowSize: 16});
				
				$(\'a.commelien\').bind(
					\'click\',
					function()
					{
						$(\'.scroll-pane\')[0].scrollTo(0);
						return false;
					}
				);
				
				reinitialiseScrollPane = function()
				{
					$(\'.scroll-pane\').jScrollPane({animateTo:true, animateInterval:50, animateStep:3, showArrows:true, scrollbarWidth: 15, arrowSize: 16});
				}


			});
			
		</script>');
		else
		print('<script type="text/javascript" src="javascript/jQuery.js"></script>');
		
		if($_SESSION['statut'] == "Administrateur")
			{
			print('<script type="text/javascript" src="javascript/jQuery.modal.js"></script>
		<script type="text/javascript">
			$(document).ready(function(){
				$(\'#lien_test\').click(function(){
					$(\'#boite_test\').modal({onOpen: modalOpen, onClose: modalClose});
				});
			});
			
			function modalOpen (dialog) {
				dialog.overlay.fadeIn(\'slow\', function () {
					dialog.container.fadeIn(\'slow\', function () {
						dialog.data.fadeIn(\'slow\');	 
					});
				});
			}
			
			function modalClose (dialog) {
				dialog.data.fadeOut(\'slow\', function () {
					dialog.container.fadeOut(\'slow\');
						dialog.overlay.fadeOut(\'slow\', function () {
							$.modal.close();
						});
				});
			}
		</script>
		<style>
			#simplemodal-overlay {
				background: #000;
			}
			
			#simplemodal-container {
				width: 560px;
				height: 352px;
				top: 20%;
				color:#eee;
				padding: 20px;
				text-align: justify;
				background: url(im_objets/tuto.png) 0 0 no-repeat;
			}
		</style>');
			}
		?>
		
		<script type="text/javascript" src="javascript/fonctions.js"></script>
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
			
			function affiche_tab(id,boole) {
				if (document.getElementById)
					{
					if(boole) document.getElementById(id).style.display="";
					else document.getElementById(id).style.display="none";
					}
				if (document.all && !document.getElementById)
					{
					if(boole) document.all[id].style.display="";
					else document.all[id].style.display="none";
					}
				if (document.layers)
					{
					if(boole) document.layers[id].display="";
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
			setInterval("cacher_montrer();",1400);
			
			//-->
		</script>
	</head>
   
	<body>

	
		<div id="page_principale">
			
			<div id="panneau_planete"<?php if($panneau_planete == "off") print(' style="display:none;"'); ?>>
				<a href="javascript:affiche_art('panneau_planete',false);affiche_art('panneau_planete_off',true);EcrireCookie('panneau_planete','off');" class="onglet_fermant2"></a>
				<?php
				$db = mysql_connect('localhost', 'CENSURE', 'CENSURE') or die("Erreur de connexion au serveur.");
				mysql_select_db('CENSURE',$db) or die("Erreur de connexion a la base de donnees.");
				
				$sql = 'SELECT nom,cases,colonie,proprietaire FROM colonies_planetes_tbl WHERE id='.$_SESSION['planete'] ;
				$req = mysql_query($sql);
				$resaze = mysql_num_rows($req);
				
				if($resaze != 0)
					{
					$colonie = mysql_result($req,0,colonie);
					$planete = mysql_result($req,0,nom);
					$surface = mysql_result($req,0,cases);
					$proprietaire = mysql_result($req,0,proprietaire);
					
					$sql = 'SELECT id FROM principal_tbl WHERE planete="'.$_SESSION['planete'].'"';
					$req = mysql_query($sql);
					$nbre = mysql_num_rows($req);
					}
				
				mysql_close($db);
				?>
				<a href="engine=planete.php"><img src="im_objets/planete_<?php print(strtolower($planete)); ?>.gif" alt="Planète" /></a>
				<table>
					<tr>
						<td colspan="2"><strong><?php print($colonie); ?></strong> (<?php print($planete); ?>)</td>
					</tr>
					<tr>
						<td><strong>Hbts</strong></td>
						<td><?php print($nbre); ?></td>
					</tr>
					<tr>
						<td><strong>Surface</strong></td>
						<td><?php print($surface); ?> ha</td>
					</tr>
					<tr>
						<td><strong>Proprio.</strong></td>
						<td><?php print($proprietaire); ?></td>
					</tr>
				</table>
			</div>
			<div id="panneau_planete_off"<?php if($panneau_planete == "on") print(' style="display:none;"'); ?>>
				<a href="javascript:affiche_art('panneau_planete',true);affiche_art('panneau_planete_off',false);EcrireCookie('panneau_planete','on');" class="onglet_ouvrant2"></a>
			</div>
			
			<div id="panneau_outils"<?php if($panneau_outils == "off") print(' style="display:none;"'); ?>>
				<a href="javascript:affiche_art('panneau_outils',false);affiche_art('panneau_outils_off',true);EcrireCookie('panneau_outils','off');" class="onglet_fermant2"></a>
				<div id="indic_outil"></div>
				<div id="outils">
					<?php
					$aitl = 0;$aitl2 = 0;$carnet = 0;
					for($i=0;$i<6;$i++)
						{
						$aitl = ($_SESSION['case'.$i] == "AITL")?$aitl+1:$aitl;
						$aitl2 = ($_SESSION['case'.$i] == "AITL 2.0")?$aitl2+1:$aitl2;
						$carnet = ($_SESSION['case'.$i] == "Carnet")?$carnet+1:$carnet;
						}
					if($carnet>0 || $_SESSION['statut'] == "Administrateur") print('<a href="engine=carnet.php" id="outil1" style="background-position:-32px 0;" onmouseover="javascript:$(\'#indic_outil\').css(\'background-position\',\'-15px 0\');"></a>');
					else print('<div id="outil1" style="background-position:0 0;" onmouseover="javascript:$(\'#indic_outil\').css(\'background-position\',\'-15px 0\');"></div>');
					if($aitl>0 || statut($_SESSION['statut'])>=2) print('<a href="engine=aitl.php" id="outil2" style="background-position:-96px 0;" onmouseover="javascript:$(\'#indic_outil\').css(\'background-position\',\'-30px 0\');"></a>');
					else print('<div id="outil2" style="background-position:-64px 0;" onmouseover="javascript:$(\'#indic_outil\').css(\'background-position\',\'-30px 0\');"></div>');
					if($aitl2>0 || statut($_SESSION['statut'])>=2) print('<a href="engine=aitl2.php" id="outil3" style="background-position:-160px 0;" onmouseover="javascript:$(\'#indic_outil\').css(\'background-position\',\'-45px 0\');"></a>');
					else print('<div id="outil3" style="background-position:-128px 0;" onmouseover="javascript:$(\'#indic_outil\').css(\'background-position\',\'-45px 0\');"></div>');
					if($_SESSION['objet'] == "Deck Premium" || $_SESSION['objet'] == "Deck 1.1" || $_SESSION['objet'] == "Cyber Deck" || $_SESSION['objet'] == "Deck Reist" || $_SESSION['objet'] == "Deck Transcom") print('<a href="engine=cyberdeck.php" id="outil4" style="background-position:-224px 0;" onmouseover="javascript:$(\'#indic_outil\').css(\'background-position\',\'-60px 0\');"></a>');
					else print('<div id="outil4" style="background-position:-192px 0;" onmouseover="javascript:$(\'#indic_outil\').css(\'background-position\',\'-60px 0\');"></div>');
					if($a_nouveau_message > 0) print('<a href="engine=messages.php" id="outil5" style="background-position:-288px 0;" onmouseover="javascript:$(\'#indic_outil\').css(\'background-position\',\'-75px 0\');">'.$a_nouveau_message.'</a>');
					else print('<a href="engine=messages.php" id="outil5" style="background-position:-256px 0;" onmouseover="javascript:$(\'#indic_outil\').css(\'background-position\',\'-75px 0\');"></a>');
					
					print('<a href="../classements.php" onclick="window.open(this.href); return false;" id="outil6" style="background-position:-320px 0;" onmouseover="javascript:$(\'#indic_outil\').css(\'background-position\',\'-90px 0\');"></a>');
					//print('<a href="../compteVIP.php" id="outil6" style="background-position:-320px 0;" onmouseover="javascript:$(\'#indic_outil\').css(\'background-position\',\'-90px 0\');"></a>');
					
					if($sms != 0) print('<a href="engine=smsflash.php" id="outil7" style="background-position:-384px 0;" onmouseover="javascript:$(\'#indic_outil\').css(\'background-position\',\'-105px 0\');"></a>'); 
					else print('<a href="engine=smsflash.php" id="outil7" style="background-position:-352px 0;" onmouseover="javascript:$(\'#indic_outil\').css(\'background-position\',\'-105px 0\');"></a>');
					
					if(statut($_SESSION['statut'])>=2) print('<div id="outil8" style="background-position:-448px 0;" onmouseover="javascript:$(\'#indic_outil\').css(\'background-position\',\'-120px 0\');"></div>');
					else print('<a href="../compteVIP.php" id="outil8" style="background-position:-416px 0;" onmouseover="javascript:$(\'#indic_outil\').css(\'background-position\',\'-120px 0\');"></a>');
					
					print('<a href="../wikast" onclick="window.open(this.href); return false;" id="outil9" style="background-position:-480px 0;" onmouseover="javascript:$(\'#indic_outil\').css(\'background-position\',\'-135px 0\');"></a>
					<a href="../wikast/edc.php" onclick="window.open(this.href); return false;" id="outil10" style="background-position:-512px 0;" onmouseover="javascript:$(\'#indic_outil\').css(\'background-position\',\'-150px 0\');"></a>
					<a href="engine=rbug.php" id="outil11" style="background-position:-544px 0;" onmouseover="javascript:$(\'#indic_outil\').css(\'background-position\',\'-165px 0\');"></a>
					<a href="../deconnexion.php" id="outil12" style="background-position:-576px 0;" onmouseover="javascript:$(\'#indic_outil\').css(\'background-position\',\'-180px 0\');"></a>');
					?>
				</div>
			</div>
			<div id="panneau_outils_off"<?php if($panneau_outils == "on") print(' style="display:none;"'); ?>>
				<a href="javascript:affiche_art('panneau_outils',true);affiche_art('panneau_outils_off',false);EcrireCookie('panneau_outils','on');" class="onglet_ouvrant2"></a>
			</div>
			
			<div id="panneau_pub_ext"<?php if($panneau_pub_ext == "off") print(' style="display:none;"'); ?>>
				<?php if($_SESSION['statut'] != "joueur") print('<a href="javascript:affiche_art(\'panneau_pub_ext\',false);affiche_art(\'panneau_pub_ext_off\',true);EcrireCookie(\'panneau_pub_ext\',\'off\');" class="onglet_fermant2"></a>'); ?>
				<a href="mailto:contact@mspixel.fr" class="onglet_afficher"></a>
				<div class="pub"><a href="engine=redirectpub.php?id=<?php print($id_pub); ?>" target="_blank"><img src="<?php print($image_pub); ?>" /></a></div>
			</div>
			<div id="panneau_pub_ext_off"<?php if($panneau_pub_ext == "on") print(' style="display:none;"'); ?>>
				<a href="javascript:affiche_art('panneau_pub_ext',true);affiche_art('panneau_pub_ext_off',false);EcrireCookie('panneau_pub_ext','on');" class="onglet_ouvrant2"></a>
			</div>
			
			<!--<div id="panneau_entree">
				<p><b>DREADCAST</b></p>
				<p><b><?php print(''.$nbre.''); ?></b> Hbts.</p>
			</div>-->
			
			<div id="horloge">
				<!--<p><?php print(date('H:i')); ?></p>-->
			</div>
		
			<div id="menu">
				<div class="menus" onmouseover="$('#smenu1').show();" onmouseout="$('#smenu1').hide();">
						<a id="menu1" href="engine.php" title="Situation">Situation</a>
						<ul id="smenu1">
							<li><a href="engine.php">Actuelle</a></li>
							<li><a href="engine=allera.php">Aller à ..</a></li>
						</ul>
				</div>
				<div class="menus" onmouseover="$('#smenu2').show();" onmouseout="$('#smenu2').hide();">
					<a id="menu2" href="engine=stats.php" title="Stats">Stats</a>
					<ul id="smenu2">
						<li><a href="engine=stats.php">Caractéristiques</a></li>
						<li><a href="engine=cercle.php">Cercles</a></li>
						<li><a href="engine=infosperso.php">Infos perso</a></li>
						<li><a href="engine=reactions.php">Réactions</a></li>
					</ul>
				</div>
				<!--<ul class="morceau_menu">	
					<li class="d1" onmouseover="javascript:montre('smenu3');">
						<a id="menu3" href="engine=inventaire.php" title="Inventaire">Inventaire</a>
					</li>
					<li class="d2">
						<ul id="smenu3">
							<li><a href="engine=inventaire.php">Personnel</a></li>
							<li><a href="engine=invlieu.php">Du lieu</a></li>
							<li><a href="engine=contacter.php">Contacter </a></li>
							<li><a href="engine=messages.php">Courrier</a></li>
						</ul>
					</li>
				</ul>
				<ul class="morceau_menu">	
					<li class="d1" onmouseover="javascript:montre('smenu4');">
						<a id="menu4" href="engine=activite.php" title="Travail">Travail</a>
					</li>
					<li class="d2" onmouseout="javascript:montre('');" onmouseover="javascript:montre('smenu4');">
						<ul id="smenu4">
							<li><a href="engine=activite.php">Profession</a></li>
							<li><a href="engine=redirt.php">Créer/Gérer</a></li>   					
						</ul>
					</li>
				</ul>
				<ul class="morceau_menu">
					<li class="d1" onmouseover="javascript:montre('smenu5');">
						<a id="menu5" href="engine=logement.php" title="Logement">Logement</a>
					</li>
				</ul>-->
			</div>
			
			<div id="p1_2_2">
