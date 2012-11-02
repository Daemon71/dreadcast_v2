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
// CIBLE & AC
$cible = str_replace("_"," ",''.$_GET['cible'].'');
$action = str_replace("_"," ",''.$_GET['action'].'');

// NE PAS S'AUTO-CIBLER
if($cible==$_SESSION['pseudo'])
{
	print('<meta http-equiv="refresh" content="0 ; url=http://v2.dreadcast.net/ingame/engine.php"> ');
	exit();
}

// BDD
$db = mysql_connect('localhost', 'CENSURE', 'CENSURE') or die("Erreur de connexion au serveur des messages.");
mysql_select_db('CENSURE',$db) or die("Erreur de connexion a la base de donnees des messages.");

// RECUPERATION INFOS PERSOS
$sql = 'SELECT * FROM principal_tbl WHERE pseudo= "'.$_SESSION['pseudo'].'"' ;
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
$_SESSION['arme'] = mysql_result($req,0,arme);
$_SESSION['objet'] = mysql_result($req,0,objet);
$_SESSION['vetements'] = mysql_result($req,0,vetements);
$_SESSION['case1'] = mysql_result($req,0,case1);
$_SESSION['case2'] = mysql_result($req,0,case2);
$_SESSION['case3'] = mysql_result($req,0,case3);
$_SESSION['case4'] = mysql_result($req,0,case4);
$_SESSION['case5'] = mysql_result($req,0,case5);
$_SESSION['case6'] = mysql_result($req,0,case6);
$_SESSION['chargeur'] = mysql_result($req,0,chargeur);
$_SESSION['combat'] = mysql_result($req,0,combat);
$_SESSION['tir'] = mysql_result($req,0,tir);
$_SESSION['resistance'] = mysql_result($req,0,resistance);
$_SESSION['discretion'] = mysql_result($req,0,discretion);
$_SESSION['observation'] = mysql_result($req,0,observation);

if($_SESSION['vetements']=="Kevlar") $_SESSION['resistance'] = $_SESSION['resistance'] + 30;
if($_SESSION['objet']=="Casque") $_SESSION['resistance'] = $_SESSION['resistance'] + 10;
if($_SESSION['objet']=="Lunettes de soleil") $_SESSION['discretion'] = $_SESSION['discretion'] + 10;

// RECUPERATION INFOS CIBLE

$sqlinfo = 'SELECT * FROM principal_tbl WHERE pseudo= "'.$cible.'"' ;
$reqinfo = mysql_query($sqlinfo);

$_SESSION['idc'] = mysql_result($reqinfo,0,id);
$_SESSION['pseudoc'] = mysql_result($reqinfo,0,pseudo);
$_SESSION['lieuc'] = mysql_result($reqinfo,0,rue);
$_SESSION['numc'] = mysql_result($reqinfo,0,num);
$_SESSION['santec'] = mysql_result($reqinfo,0,sante);
$_SESSION['fatiguec'] = mysql_result($reqinfo,0,fatigue);
$_SESSION['actionc'] = mysql_result($reqinfo,0,action);
$_SESSION['telephonec'] = mysql_result($reqinfo,0,telephone);
$_SESSION['statutc'] = mysql_result($reqinfo,0,statut);
$_SESSION['rattaquec'] = mysql_result($reqinfo,0,rattaque);
$_SESSION['rvolc'] = mysql_result($reqinfo,0,rvol);
$ruec = mysql_result($reqinfo,0,ruel);
$nuc = mysql_result($reqinfo,0,numl);
$_SESSION['combatc'] = mysql_result($reqinfo,0,combat);
$_SESSION['tirc'] = mysql_result($reqinfo,0,tir);
$_SESSION['resistancec'] = mysql_result($reqinfo,0,resistance);
$_SESSION['discretionc'] = mysql_result($reqinfo,0,discretion);
$_SESSION['observationc'] = mysql_result($reqinfo,0,observation);
$_SESSION['SMSc'] = mysql_result($reqinfo,0,SMS);
$_SESSION['SMSdjc'] = mysql_result($reqinfo,0,SMSdj);
$_SESSION['evenementsSMSc'] = mysql_result($reqinfo,0,evenementsSMS);
$connecc = mysql_result($reqinfo,0,connec);
?>
<?php if ((date("H")>=21) || (date("H")<=7)) { include("inc_haut_n.php"); } else { include("inc_haut_j.php");} ?>

<div id="haut">
	<?php include("inc_entete.php"); ?>
	<div class="haut_aligngauche">
		<p>
		<div class="titrepage">
			<?php if($action="attaquer") print('Attaquer');if($action="vol") print('Voler');print(' '.$cible); ?>
		</div>
		<b class="module4ie"><a href="engine=engine.php" class="module4"><img src="im_objets/icon_fuite.gif" alt="Retour" />Fuir</a></b>
</p>
	</div>
</div>
<div id="centre">

<?php

// NE RIEN POUVOIR FAIRE EN PRISON
if($_SESSION['action']=="prison")
{
	mysql_close($db);
	print('<meta http-equiv="refresh" content="0 ; url=http://v2.dreadcast.net/ingame/engine.php"> ');
	exit();
}

// CODOUNET ? ROMAIN, TU CRAQUAIS LA JE CROIS
$sql = 'SELECT code FROM lieu_tbl WHERE rue= "'.$_SESSION['lieu'].'" AND num= "'.$_SESSION['num'].'"' ;
$req = mysql_query($sql);
$codounet = mysql_result($req,0,code);

if($_SESSION['code']!=$codounet)
{
	mysql_close($db);
	print('<meta http-equiv="refresh" content="0 ; url=http://v2.dreadcast.net/ingame/engine.php"> ');
	exit();
}


// CAMERA
$sqlc = 'SELECT camera FROM lieu_tbl WHERE num= "'.$_SESSION['num'].'" AND rue= "'.$_SESSION['lieu'].'"' ;
$reqc = mysql_query($sqlc);
$_SESSION['camerac'] = mysql_result($reqc,0,camera);

// ENTREPRISE
$sqlr = 'SELECT nom FROM entreprises_tbl WHERE num= "'.$_SESSION['num'].'" AND rue= "'.$_SESSION['lieu'].'"' ;
$reqr = mysql_query($sqlr);
$resr = mysql_num_rows($reqr);

	// SI ATTAQUE
if($resr>0 && $action="attaquer")
{
	$entrepriser = mysql_result($reqr,0,nom);
	if(($entrepriser=="Police") || ($entrepriser=="DI2RCO"))
	{
		$sql = 'SELECT entreprise FROM principal_tbl WHERE id= "'.$_SESSION['id'].'"' ;
		$req = mysql_query($sql);
		$_SESSION['entreprise'] = mysql_result($req,0,entreprise);
		if(($_SESSION['entreprise']!="DI2RCO") && ($_SESSION['entreprise']!="Police"))
		{
			$l = 22;
		}
	}
	$sql = 'SELECT id FROM principal_tbl WHERE Num= "'.$_SESSION['num'].'" AND rue= "'.$_SESSION['lieu'].'" AND action= "travail"' ;
	$req = mysql_query($sql);
	$res = mysql_num_rows($req);
	
	for($a=0;$a!=$res;$a++)
	{
		$sql1 = 'SELECT type FROM principal_tbl WHERE  id= "'.mysql_result($req,$a,id).'"' ;
		$req1 = mysql_query($sql1);
		$sql1 = 'SELECT type FROM `e_'.str_replace(" ","_",''.$entrepriser.'').'_tbl` WHERE  poste= "'.mysql_result($req1,0,type).'"' ;
		$req1 = mysql_query($sql1);
		if(mysql_result($req1,0,type)=="securite")
		{
			$sql3 = 'SELECT entreprise FROM principal_tbl WHERE id= "'.$_SESSION['id'].'"' ;
			$req3 = mysql_query($sql3);
			$_SESSION['entreprise'] = mysql_result($req3,0,entreprise);
			if(($_SESSION['entreprise']!="DI2RCO") && ($_SESSION['entreprise']!="Police"))
			{
				$l = 8;
			}
		}
	}
}
	// SI VOL
if($resr>0 && $action="vol")
{
	$entrepriser = mysql_result($reqr,0,nom);
	if(($entrepriser=="Police") || ($entrepriser=="DI2RCO"))
	{
		$l = 1;
		print('<p align="center"><strong>Il est impossible de voler quelqu\'un dans un central de police.</strong></p>');
	}
	$sql = 'SELECT id FROM principal_tbl WHERE Num= "'.$_SESSION['num'].'" AND rue= "'.$_SESSION['lieu'].'" AND action= "travail"' ;
	$req = mysql_query($sql);
	$res = mysql_num_rows($req);
	
	for($a=0;$a!=$res;$a++)
	{
		$sql1 = 'SELECT type FROM principal_tbl WHERE  id= "'.mysql_result($req,$a,id).'"' ;
		$req1 = mysql_query($sql1);
		$sql1 = 'SELECT type FROM `e_'.str_replace(" ","_",''.$entrepriser.'').'_tbl` WHERE  poste= "'.mysql_result($req1,0,type).'"' ;
		$req1 = mysql_query($sql1);
		if((mysql_result($req1,0,type)=="securite") && ($l!=1))
		{
			$l = 1;
			print('<p align="center"><strong>Il est impossible de voler car un agent de s&eacute;curit&eacute; est sur place.</strong></p>');
		}
	}
}

// CONDITIONS

	// SI ATTAQUE
if($action="attaquer")
{
	if(($_SESSION['actionc']=="prison") || ($_SESSION['actionc']=="protection"))
	{
		mysql_close($db);
		print('<meta http-equiv="refresh" content="0 ; url=http://v2.dreadcast.net/ingame/engine.php"> ');
		exit();
	}
	
	if($statutc=="Administrateur")
	{
		$l = 9;
	}
	
	if((ucwords($_SESSION['lieuc'])==ucwords($ruec)) && ($_SESSION['numc']==$nuc))
	{
		$_SESSION['rintrusionc'] = mysql_result($reqinfo,0,rintrusion);
	}
	if((ucwords($_SESSION['lieu'])!=ucwords($_SESSION['lieuc'])) || ($_SESSION['num']!=$_SESSION['numc']))
	{
		print('<p align="center">Il est impossible d\'attaquer <strong>'.$cible.'</strong> car il n\'est pas au m&ecirc;me endroit que vous.</p>');
		$imp = 1;
	}
	elseif($_SESSION['fatigue']==10)
	{
		print('<p align="center">Il est impossible d\'attaquer car vous &ecirc;tes trop fatigu&eacute;.</p>');
		$imp = 1;
	}
	elseif($l==9)
	{
		print('<p align="center">Il est impossible d\'attaquer un Administrateur.</p>');
		$imp = 1;
	}
	elseif($l==22)
	{
		print('<p align="center">Il est impossible d\'attaquer dans un central de Police.</p>');
		$imp = 1;
	}
	elseif($l==8)
	{
		print('<p align="center">Il est impossible d\'attaquer car un agent de s&eacute;curit&eacute; est sur place.</p>');
		$imp = 1;
	}
}

	// SI VOL
if($action="vol")
{
	if($_SESSION['vol']=="0")
	{
		print('<p align="center"><strong>Il est impossible de voler <i>'.$cible.'</i> car vous ne savez pas comment faire.</strong></p>');
		$jimp = 1;
	}
	if($statutc=="Administrateur")
	{
		print('<p align="center"><strong>Il est impossible de voler un Administrateur.</strong></p>');
		$jimp = 1;
	}
	if((($_SESSION['fatigue']=="15") && ($_SESSION['drogue']>0)) || ($_SESSION['fatigue']=="10"))
	{
		print('<p align="center"><strong>Il est impossible de voler <i>'.$cible.'</i> car vous êtes trop fatigu&eacute;.</strong></p>');
		$jimp = 1;
	}
	if((ucwords($_SESSION['lieu'])!=ucwords($_SESSION['lieuc'])) || ($_SESSION['num']!=$_SESSION['numc']))
	{
		print('<p align="center"><strong>Il est impossible de voler <i>'.$cible.'</i> car il n\'est pas au m&ecirc;me endroit que vous.</strong></p>');
		$jimp = 1;
	}
}

// LA C'EST DU COSTAUD

	//SI ATTAQUE
if($action="attaque" && $imp!=1)
{
		// FATIGUE
	$_SESSION['fatigue'] = $_SESSION['fatigue'] + 1;
	$sql33 = 'UPDATE principal_tbl SET fatigue= "'.$_SESSION['fatigue'].'" WHERE id= "'.$_SESSION['id'].'"';
	$req33 = mysql_query($sql33);
		// DONNEES ADVERSAIRE
	$sql = 'SELECT arme,vetements,objet FROM principal_tbl WHERE id= "'.$_SESSION['idc'].'"' ;
	$req = mysql_query($sql);
	$_SESSION['armec'] = mysql_result($req,0,arme);
	$_SESSION['objetc'] = mysql_result($req,0,objet);
	$_SESSION['vetementsc'] = mysql_result($req,0,vetements);
	if($_SESSION['vetementsc']=="Kevlar") $_SESSION['resistancec'] = $_SESSION['resistancec'] + 30;
	if($_SESSION['objetc']=="Casque") $_SESSION['resistancec'] = $_SESSION['resistancec'] + 10;
	if($_SESSION['objetc']=="Lunettes de soleil") $_SESSION['discretionc'] = $_SESSION['discretionc'] + 10;
		// ARMES
	$sql = 'SELECT puissance,image,type FROM objets_tbl WHERE nom= "'.$_SESSION['arme'].'"' ;
	$req = mysql_query($sql);
	$puissance = mysql_result($req,0,puissance);
	$type = mysql_result($req,0,type);
	$image = mysql_result($req,0,image);
	$sql = 'SELECT puissance,image,type FROM objets_tbl WHERE nom= "'.$_SESSION['armec'].'"' ;
	$req = mysql_query($sql);
	$puissancec = mysql_result($req,0,puissance);
	$typec = mysql_result($req,0,type);
	$imagec = mysql_result($req,0,image);
}

// FIN BDD
mysql_close($db);

$action1="attaque";
$action2="deplacement";
$j1=12;
$j1max=16;
$j2=11;
$j2max=14;
?>
	
	<div id="joueur1">
		<p class="cavatar"><?php 
			if(empty($_SESSION['avatar']))$_SESSION['avatar']="";
			
			if((ereg("http",$_SESSION['avatar'])) OR (ereg("ftp",$_SESSION['avatar'])))
				{
				print('<img src="'.$_SESSION['avatar'].'" id="avatar" alt="Mon avatar" border ="0" width="70" height="70" />');
				}
			else
				{
				print('<img src="avatars/'.$_SESSION['avatar'].'" id="avatar" alt="Mon avatar" border ="0" width="70" height="70" />');
				}
			?>
		</p>
		<p class="cpseudo"><?php print $_SESSION['pseudo']; ?></p>
		<p class="cetat">Vous avez l'air <strong>en forme</strong></p>
		<div class="cchoix">
			<p>Actions :</p>
			<p class="clien"><a href="">Attaquer</a></p>
			<p class="clien"><a href="">Voler</a></p>
			<p class="clien"><a href="">Se rapprocher</a></p>
			<p class="clien"><a href="">S'éloigner</a></p>
			<p class="clien"><a href="">Equipement</a></p>
		</div>
		<p class="carme"><img src="im_objets/icon_pistolet.gif" alt="Mon arme" />FA-MAS</p>
	</div>
	
	<div id="joueur2">
		<p class="cavatar"><img src="avatars/as12.gif" id="avatar" alt="Son avatar" border ="0" width="70" height="70" /></p>
		<p class="cpseudo">Cless</p>
		<p class="cetat">Il a l'air <strong>en forme</strong></p>
		<p class="carme"><img src="im_objets/icon_epee.gif" alt="Son arme" />Ep&eacute;e longue</p>
		<p class="cdistance">Distance : 13m</p>
		<?php
		print('<p class="ccommentaire">');
		if($attaque1!=0) {
			print('Vous effectuez une attaque &agrave; distance.<br /><br />');
		}
		else {
			if($deplace1>0)
				print('Vous vous rapprochez de votre cible.<br /><br />');
			else
				print('Vous vous &eacute;loignez de votre cible.<br /><br />');
		}
		if($attaque2!=0) {
			print('<strong>Cless</strong> effectue une attaque &agrave; distance.');
		}
		else {
			if($deplace2>0)
				print('<strong>Cless</strong> se rapproche de vous.</p>');
			else
				print('<strong>Cless</strong> s\'éloigne de vous.</p>');
		}
		?>
	</div>
	
	<?php
	print('<div id="combat">
			<object classid="clsid:d27cdb6e-ae6d-11cf-96b8-444553540000" codebase="http://fpdownload.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version=7,0,0,0" width="296" height="283" align="middle">
				<param name="allowScriptAccess" value="sameDomain" />
				<param name="movie" value="Combat.swf?action1='.$action1.'&action2='.$action2.'&j1='.$j1.'&j1max='.$j1max.'&j2='.$j2.'&j2max='.$j2max.'" />
				<param name="quality" value="high" />
				<param name="bgcolor" value="#ffffff" />
				<embed src="Combat.swf?action1='.$action1.'&action2='.$action2.'&j1='.$j1.'&j1max='.$j1max.'&j2='.$j2.'&j2max='.$j2max.'" quality="high" bgcolor="#ffffff" width="296" height="283" name="combat" align="middle" allowScriptAccess="sameDomain" type="application/x-shockwave-flash" pluginspage="http://www.macromedia.com/go/getflashplayer" />
			</object>
	</div>');
	?>
	
</div>
<?php if($chat=="oui") 	{ if ((date("H")>=21) || (date("H")<=7)) { include("inc_bas_n_c.php"); } else { include("inc_bas_j_c.php"); } } else {	if ((date("H")>=21) || (date("H")<=7)) { include("inc_bas_n.php"); } else { include("inc_bas_j.php"); }	} ?>
<?php include("inc_bas_de_page.php"); ?>
