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
if ((date("H")>=21) || (date("H")<=7)) { include("inc_haut_n.php"); } else { include("inc_haut_j.php");} 

$db = mysql_connect('localhost', 'CENSURE', 'CENSURE') or die("Erreur de connexion au serveur des messages.");
mysql_select_db('CENSURE',$db) or die("Erreur de connexion a la base de donnees des messages.");

$sql = 'SELECT id,rue,num,action,alim,sante,fatigue,entreprise FROM principal_tbl WHERE pseudo= "'.$_SESSION['pseudo'].'"' ;
$req = mysql_query($sql);
$_SESSION['id'] = mysql_result($req,0,id);
$_SESSION['rue'] = mysql_result($req,0,rue);
$_SESSION['num'] = mysql_result($req,0,num);
$_SESSION['action'] = mysql_result($req,0,action);
$_SESSION['alim'] = mysql_result($req,0,alim);
$_SESSION['sante'] = mysql_result($req,0,sante);
$_SESSION['fatigue'] = mysql_result($req,0,fatigue);
$_SESSION['entreprise'] = mysql_result($req,0,entreprise);
$_SESSION['lieu'] = strtolower($_SESSION['rue']);
$_SESSION['lieu'] = ucwords($_SESSION['lieu']);

if($_SESSION['num'] <= 0)
	{
	$num = 0;
	$lieu = "Rue";
	}
else
	{
	$num = $_SESSION['num'];
	$lieu = $_SESSION['lieu'];
	}
$sql = 'SELECT code FROM lieu_tbl WHERE rue= "'.$lieu.'" AND num= "'.$num.'"' ;
$req = mysql_query($sql);
$codounet = mysql_result($req,0,code);

if($_SESSION['code']!=$codounet)
	{
	mysql_close($db);
	print('<meta http-equiv="refresh" content="0 ; url=http://v2.dreadcast.net/ingame/engine.php"> ');
	exit();
	}

$cible = str_replace("%20"," ",''.$_SERVER['QUERY_STRING'].'');

$sql = 'SELECT num,rue FROM entreprises_tbl WHERE type= "police"' ;
$req = mysql_query($sql);
$ruep = mysql_result($req,0,rue);
$nump = mysql_result($req,0,num);

//POLICE
if((ucwords($_SESSION['lieu'])==ucwords($ruep)) && ($_SESSION['num']==$nump))
	{
	$pachever = 1;
	}

$sql = 'SELECT * FROM principal_tbl WHERE pseudo= "'.$cible.'"' ;
$req = mysql_query($sql);
$_SESSION['actionc'] = mysql_result($req,0,action);

if(($_SESSION['actionc']=="prison") || ($_SESSION['actionc']=="protection") || mysql_result($req,0,event) == 1)
	{
	mysql_close($db);
	print('<meta http-equiv="refresh" content="0 ; url=http://v2.dreadcast.net/ingame/engine.php"> ');
	exit();
	}

if($_SESSION['fatigue']>80)
	{
	$okachever = 1;
	}

$estVisible = estVisible($cible,25);

$sql = 'SELECT Police FROM principal_tbl WHERE id= "'.$_SESSION['id'].'"' ;
$req = mysql_query($sql);
$_SESSION['police'] = mysql_result($req,0,Police);

if($okachever==1 && $pachever!=1 && $estVisible)
	{
	$sql = 'SELECT id,rue,num,sante,telephone,SMS,SMSdj,evenementsSMS,connec,Police FROM principal_tbl WHERE pseudo= "'.$cible.'"' ;
	$req = mysql_query($sql);
	$_SESSION['idc'] = mysql_result($req,0,id);
	$_SESSION['lieuc'] = mysql_result($req,0,rue);
	$_SESSION['numc'] = mysql_result($req,0,num);
	$_SESSION['santec'] = mysql_result($req,0,sante);
	$_SESSION['telephonec'] = mysql_result($req,0,telephone);
	$_SESSION['SMSc'] = mysql_result($req,0,SMS);
	$_SESSION['SMSdjc'] = mysql_result($req,0,SMSdj);
	$_SESSION['evenementsSMSc'] = mysql_result($req,0,evenementsSMS);
	$connecc = mysql_result($req,0,connec);
	$rpolice = mysql_result($req,0,Police);
	
	if($estVisible)
		{
		if($_SESSION['santec']==0)
			{
			$sql1 = 'SELECT titre FROM titres_tbl WHERE pseudo="'.$_SESSION['pseudo'].'"' ;
			$req1 = mysql_query($sql1);
			$res1 = mysql_num_rows($req1);
			for($i=0;$i!=$res1;$i++) $titres .= mysql_result($req1,$i,titre);
			if(ereg("Justicier",$titres) && $rpolice>=55)
				{
				$sql = 'SELECT valeur FROM enregistreur_tbl WHERE pseudo="'.$_SESSION['pseudo'].'" AND donnee="justice"' ;
				$req = mysql_query($sql);
				$res = mysql_num_rows($req);
				if($res==0) $nombre_j = 1;
				else $nombre_j = mysql_result($req,0,valeur) + 1;
				enregistre($_SESSION['pseudo'],"justice",$nombre_j);
				}
			elseif(ereg("Sans foi ni loi",$titres))
				{
				}
			else
				{
				$sql = 'SELECT valeur FROM enregistreur_tbl WHERE pseudo="'.$_SESSION['pseudo'].'" AND donnee="acheve"' ;
				$req = mysql_query($sql);
				$res = mysql_num_rows($req);
				if($res==0) $nombre_a = 1;
				else $nombre_a = mysql_result($req,0,valeur) + 1;
				enregistre($_SESSION['pseudo'],"acheve",$nombre_a);
				}
			forme_retirer($_SESSION['id'],80);
			
			$_SESSION['fatigue'] -= 80;
			$_SESSION['police'] += 50;
			
			$sql = 'UPDATE principal_tbl SET Police= "'.$_SESSION['police'].'" WHERE id= "'.$_SESSION['id'].'"' ;
			$req = mysql_query($sql);
			$sql = 'INSERT INTO crimes_tbl(pseudo,date,type,valeur) VALUES("'.$_SESSION['pseudo'].'","'.time().'","Meurtre","'.$cible.'")' ;
			$req = mysql_query($sql);
			
			mourir($cible,'Assassiné',$_SESSION['pseudo']);
			enregistre($_SESSION['pseudo'],"acc_meurtre_donne","+1");
			enregistre($cible,"acc_meurtre_recu","+1");
			
			$sql = 'INSERT INTO messages_tbl(id,auteur,cible,message,objet,moment) VALUES("","Dreadcast","'.$cible.'","Presque inconscient, vous discernez une silhouette s\'approcher de vous.<br /><br />Bientôt, vous ne sentez plus rien.<br />Votre vue se brouille.<br /><br />Vous êtes mort.","Vous êtes mort !","'.time().'")' ;
			$req = mysql_query($sql);
			
			if(ereg("mort",$_SESSION['evenementsSMSc']))
				{
				$SMSok = 1;
				}
			
			if(ereg("mort",$_SESSION['SMSdjc']))
				{
				$SMSok = 0;
				}
			
			if(strlen($_SESSION['telephonec'])!=8)
				{
				$SMSok = 0;
				}
			
			if($connecc=="oui")
				{
				$SMSok = 0;
				}
			
			if(($_SESSION['SMSc']>0) && ($SMSok==1))
				{
				$smsbox_user = 'club21'; // Votre identifiant SMSBOX
				$smsbox_pass = 'mspixel01'; // Votre mot de passe SMSBOX
				$api_type = 'php'; // Ne pas changer
				$api_path = "https://api.smsbox.fr/api.$api_type"; // Ne pas changer
				function sendSMS($to, $message, $from, $mode='expert'){
					global $smsbox_user, $smsbox_pass, $api_path;
					return @file_get_contents("$api_path?login=$smsbox_user&pass=$smsbox_pass&msg=".rawurlencode($message)."&dest=$to&mode=$mode&origine=".rawurlencode($from));
				}
				$_SESSION['SMSc'] = $_SESSION['SMSc'] - 1;
				$retour = sendSMS("06".$_SESSION['telephonec'], "- Dreadcast Infos - Vous êtes mort ! Connectez-vous pour en connaître les raisons.", 'Dreadcast');
				$sql = 'UPDATE principal_tbl SET SMS= "'.$_SESSION['SMSc'].'" , SMSdj= "'.$_SESSION['SMSdjc'].' mort" WHERE id= "'.$_SESSION['idc'].'"';
				$req = mysql_query($sql);
				}
			}
		else
			{
			print('<meta http-equiv="refresh" content="0 ; url=http://v2.dreadcast.net/ingame/engine.php"> ');
			exit();
			}
		}
	}

?>

<div id="haut">
	<?php include("inc_entete.php"); ?>
	<div class="haut_aligngauche">
		<p>
		<div class="titrepage">
			Achever
		</div>
		<b class="module4ie"><a href="engine.php" class="module4"><img src="im_objets/icon_retour.gif" alt="Retour" />Retour</b></a>
		</p>
	</div>
</div>
<div id="centre">
<p>


<?php

$db = mysql_connect('localhost', 'CENSURE', 'CENSURE') or die("Erreur de connexion au serveur des messages.");
mysql_select_db('CENSURE',$db) or die("Erreur de connexion a la base de donnees des messages.");

if($pachever==1)
	{
	print('Il est impossible d\'achever dans un central de police.');
	}
elseif($okachever==1)
	{
	if(!$estVisible)
		{
		print('Il est impossible d\'achever <strong>'.$cible.'</strong> car il n\'est pas au m&ecirc;me endroit que vous.');
		$imp = 1;
		}
	elseif($_SESSION['santec']==0)
		{
		print('Vous écrasez le visage de <strong>'.$cible.'</strong> contre le sol.');
		print('<br />Vous le laissez là, mort.');
		}
	}
else
	{
	print('Il est impossible d\'achever <strong>'.$cible.'</strong> car vous êtes trop fatigué.');
	}
	
mysql_close($db);
?>


</p>
</div>
<?php if($chat=="oui") 	{ if ((date("H")>=21) || (date("H")<=7)) { include("inc_bas_n_c.php"); } else { include("inc_bas_j_c.php"); } } else {	if ((date("H")>=21) || (date("H")<=7)) { include("inc_bas_n.php"); } else { include("inc_bas_j.php"); }	} ?>
<?php include("inc_bas_de_page.php"); ?>
