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

$sql = 'SELECT * FROM principal_tbl WHERE pseudo= "'.$_SESSION['pseudo'].'"' ;
$req = mysql_query($sql);
$_SESSION['id'] = mysql_result($req,0,id);
$_SESSION['rue'] = mysql_result($req,0,rue);
$_SESSION['num'] = mysql_result($req,0,num);
$_SESSION['action'] = mysql_result($req,0,action);
$_SESSION['alim'] = mysql_result($req,0,alim);
$_SESSION['sante'] = mysql_result($req,0,sante);
$_SESSION['drogue'] = mysql_result($req,0,drogue);
$_SESSION['fatigue'] = mysql_result($req,0,fatigue);
$_SESSION['lieu'] = strtolower($_SESSION['rue']);
$_SESSION['lieu'] = ucwords($_SESSION['lieu']);

$sql = 'SELECT adresse,statut,allopasstot FROM principal_tbl WHERE id= "'.$_SESSION['id'].'"' ;
$req = mysql_query($sql);
$_SESSION['email'] = mysql_result($req,0,adresse); 
$_SESSION['statut'] = mysql_result($req,0,statut); 
$_SESSION['allopasstot'] = mysql_result($req,0,allopasstot); 

mysql_close($db);
?>
<?php if ((date("H")>=21) || (date("H")<=7)) { include("inc_haut_n.php"); } else { include("inc_haut_j.php");} ?>

<div id="haut">
	<?php include("inc_entete.php"); ?>
	<div class="haut_aligngauche">
		<p>
		<div class="titrepage">
			Infos personnelles
		</div>
		</p>
	</div>
</div>
<div id="centre_infosperso" style="background:url(im_objets/fondcarte2.gif);">

	<div id="cpage1"><a href="engine=stats.php"> <strong>&laquo; Retourner la carte</strong></a></div>
	
	<div id="ccodebarre"><?php print(substr($_SESSION['id'].$_SESSION['id'].$_SESSION['id'].$_SESSION['id'].$_SESSION['id'].$_SESSION['id'].$_SESSION['id'].$_SESSION['id'].$_SESSION['id'].$_SESSION['id'].$_SESSION['id'].$_SESSION['id'],0,12)); ?></div>
	
	<!--<div id="ctitre1">Informations WiKast</div>-->
	
	<div id="cinfos1">
	
	<?php
	$db = mysql_connect('localhost', 'CENSURE', 'CENSURE') or die("Erreur de connexion au serveur des messages.");
	mysql_select_db('CENSURE',$db) or die("Erreur de connexion a la base de donnees des messages.");

	$sql = 'SELECT id FROM wikast_forum_sujets_tbl WHERE auteur="'.$_SESSION['pseudo'].'"';
	$req = mysql_query($sql);
	$ressujets = mysql_num_rows($req);
	$sql = 'SELECT id FROM wikast_forum_posts_tbl WHERE auteur="'.$_SESSION['pseudo'].'"';
	$req = mysql_query($sql);
	$resposts = mysql_num_rows($req);
	$sql = 'SELECT id FROM wikast_edc_commentaires_tbl WHERE auteur="'.$_SESSION['pseudo'].'"';
	$req = mysql_query($sql);
	$rescomms = mysql_num_rows($req);
	$sql = 'SELECT DISTINCT titre FROM wikast_wiki_articles_tbl WHERE createur="'.$_SESSION['pseudo'].'"';
	$req = mysql_query($sql);
	$resartw = mysql_num_rows($req);
	$sql = 'SELECT id FROM wikast_edc_articles_tbl WHERE auteur="'.$_SESSION['pseudo'].'"';
	$req = mysql_query($sql);
	$resart = mysql_num_rows($req);
	$sql = 'SELECT id FROM wikast_edc_etoiles_tbl WHERE auteur="'.$_SESSION['pseudo'].'"';
	$req = mysql_query($sql);
	$reset1 = mysql_num_rows($req);
	$sql = 'SELECT id FROM wikast_edc_etoiles_tbl WHERE cible="'.$_SESSION['pseudo'].'"';
	$req = mysql_query($sql);
	$reset2 = mysql_num_rows($req);
	$sql = 'SELECT edc_vu FROM wikast_joueurs_tbl WHERE pseudo="'.$_SESSION['pseudo'].'"';
	$reqvu = mysql_query($sql);
	$resvu = mysql_num_rows($reqvu);
	
	if($resvu!=0) $resvu=mysql_result($reqvu,0,edc_vu);
	
	print('<h4>Informations Forum</h4>');
	
	print($_SESSION['pseudo'].' a écrit <strong>'.$ressujets.'</strong> sujet'); if($ressujets != 1) print('s'); print('<br />');
	print($_SESSION['pseudo'].' a écrit <strong>'.$resposts.'</strong> message'); if($resposts != 1) print('s'); print('<br />');
	
	print('<h4>Informations Wiki</h4>');
	
	print($_SESSION['pseudo'].' a contribué à la rédaction de <strong>'.$resartw.'</strong> article'); if($resartw != 1) print('s'); print('<br />');
	
	print('<h4>Informations EDC</h4>');
	
	print($_SESSION['pseudo'].' a écrit <strong>'.$resart.'</strong> article'); if($resart != 1) print('s'); print('<br />');
	print($_SESSION['pseudo'].' a écrit <strong>'.$rescomms.'</strong> commentaire'); if($rescomms != 1) print('s'); print('<br />');
	print($_SESSION['pseudo'].' a donné <strong>'.$reset1.'</strong> étoile'); if($reset1 != 1) print('s'); print('<br />');
	print($_SESSION['pseudo'].' a reçu <strong>'.$reset2.'</strong> étoile'); if($reset2 != 1) print('s'); print('<br />');
	print('L\'EDC de '.$_SESSION['pseudo'].' a été vu <strong>'.$resvu.'</strong> fois<br />');
	
	$sql = 'SELECT SMS FROM principal_tbl WHERE pseudo="'.$_SESSION['pseudo'].'"';
	$req = mysql_query($sql);
	$res = mysql_num_rows($req);
	
	if($res!=0) $sms=mysql_result($req,0,SMS);
	
	mysql_close($db);
	?>
	
	</div>
	
	<div id="ctitre2">Informations Compte</div>
	
	<div id="cinfos2">
	
	<?php
	
	print('Adresse email de contact <br /> <i>'.str_replace('@','(at)',$_SESSION['email']).'</i><br />');
	print('Statut : <i>'.$_SESSION['statut'].'</i><br />');
	if(statut($_SESSION['statut'])>=2)
		{
		print('&nbsp;&nbsp;&raquo; <a href="engine=comptevip.php">Vos privilèges</a><br />'); 
		print('&nbsp;&nbsp;&raquo; <a href="engine=signatureperso.php">Modifier votre signature</a><br />'); 
		}
	else print('<br />&nbsp;&nbsp;&raquo; <a href="http://v2.dreadcast.net/comptes.php" target="_blank">En savoir plus sur les Comptes +</a><br /><br />'); 
	//print('&nbsp;&nbsp;&raquo; <a href="engine=parrainage.php">Parrainer un joueur</a><br />');
	print('&nbsp;&nbsp;&raquo; <a href="engine=changermdp.php">Changer votre mot de passe</a><br />');
	print('&nbsp;&nbsp;&raquo; <a href="engine=avatars.php">Changer votre avatar</a><br />');
	print('&nbsp;&nbsp;&raquo; <a href="engine=smsflash.php">'.$sms.' SMS Flash restant');if($sms!=1)print('s');print('</a><br />');
	print('&nbsp;&nbsp;&raquo; <a href="engine=vacances.php">Cryogénisez votre personnage</a>');
	
	?>
	
	</div>
	
	
	
	
	
	
	
	
	
	
	
	
	
<!--
Voici le r&eacute;capitulatif de votre compte : </p>
		<p align="center"><strong>Adresse email de contact : </strong><? print ('<i>'.$_SESSION['email'].'</i>'); ?> </p>
		<p align="center"><strong>Statut : </strong><? print ('<i>'.$_SESSION['statut'].'</i>'); ?></p>
<?php 
if(statut($_SESSION['statut'])>=2) 
	{
	print('<p align="center"><a href="engine=comptevip.php">Consulter les privilèges de votre statut</a></p>');
	print('<p align="center"><a href="engine=signatureperso.php">Modifier votre signature</a></p>'); 
	print('<p align="center"><a href="engine=parrainage.php">Acheter un code de parrainage</a></p>'); 
	}
else
	{
	print('<p align="center"><a href="http://v2.dreadcast.net/compteVIP.php" target="_blank">En savoir plus sur le Compte VIP</a></p>'); 
	}
?>
	  <p align="center"><a href="engine=changermdp.php">Changer votre mot de passe</a></p>
	  <p align="center"><a href="engine=smsflash.php">SMS Flash - Suivez votre personnage en direct !</a></p>
	  <p align="center"><a href="engine=vacances.php">Vacances en vue ? Cryogénisez votre personnage !</a></p>
-->
</div>
<?php if($chat=="oui") 	{ if ((date("H")>=21) || (date("H")<=7)) { include("inc_bas_n_c.php"); } else { include("inc_bas_j_c.php"); } } else {	if ((date("H")>=21) || (date("H")<=7)) { include("inc_bas_n.php"); } else { include("inc_bas_j.php"); }	} ?>
<?php include("inc_bas_de_page.php"); ?>
