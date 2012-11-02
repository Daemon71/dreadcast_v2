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
?>
<?php if ((date("H")>=21) || (date("H")<=7)) { include("inc_haut_n.php"); } else { include("inc_haut_j.php");} ?>

<div id="haut">
	<?php include("inc_entete.php"); ?>
	<div class="haut_aligngauche">
		<p>
		<div class="titrepage">
			Donner des crédits
		</div>
		<b class="module4ie"><a href="engine=cible.php?<?php print(''.$_SESSION['cible'].''); ?>" class="module4"><img src="im_objets/icon_retour.gif" alt="Retour" />Retour</a></b>
</p>
	</div>
</div>
<div id="centre">
<p>

<?php	

$db = mysql_connect('localhost', 'CENSURE', 'CENSURE') or die("Erreur de connexion au serveur.");
mysql_select_db('CENSURE',$db) or die("Erreur de connexion a la base de donnees.");

forme_retirer($_SESSION['id'],1);
$_SESSION['fatigue'] -= 1;

$sql = 'SELECT id,rue,num,credits FROM principal_tbl WHERE pseudo= "'.$_SESSION['pseudo'].'"' ;
$req = mysql_query($sql);
$_SESSION['id'] = mysql_result($req,0,id);
$_SESSION['lieu'] = mysql_result($req,0,rue);
$_SESSION['num'] = mysql_result($req,0,num);
$_SESSION['credits'] = mysql_result($req,0,credits);

$sql = 'SELECT id,rue,num,SMSdj,evenementsSMS,connec,telephone,SMS,credits FROM principal_tbl WHERE pseudo= "'.$_SESSION['cible'].'"' ;
$req = mysql_query($sql);
$_SESSION['idc'] = mysql_result($req,0,id);
$_SESSION['lieuc'] = mysql_result($req,0,rue);
$_SESSION['numc'] = mysql_result($req,0,num);
$_SESSION['SMSdjc'] = mysql_result($req,0,SMSdj);
$_SESSION['evenementsSMSc'] = mysql_result($req,0,evenementsSMS);
$connecc = mysql_result($req,0,connec);
$_SESSION['telephonec'] = mysql_result($req,0,telephone);
$_SESSION['SMSc'] = mysql_result($req,0,SMS);
$_SESSION['creditsc'] = mysql_result($req,0,credits);

$trans = $_POST['trans'];

//condition DIGICODE
$sql = 'SELECT code FROM lieu_tbl WHERE rue= "'.$_SESSION['lieu'].'" AND num= "'.$_SESSION['num'].'"' ;
$req = mysql_query($sql);
$res = mysql_num_rows($req);
if($res>0)
	{
	$codounet = mysql_result($req,0,code);
	if($_SESSION['code']!=$codounet)
		{
		mysql_close($db);
		print('<meta http-equiv="refresh" content="0 ; url=http://v2.dreadcast.net/ingame/engine.php"> ');
		exit();
		}
	}

if($trans<0)
	{
	$trans = 0;
	}

if($trans<40)
	{
	print('<meta http-equiv="refresh" content="0 ; url=http://v2.dreadcast.net/ingame/engine.php"> ');
	exit();
	}

if(!estVisible($_SESSION['cible'],25))
	{
	print('<p align="center"><strong>Il est impossible de donner &agrave; <i>'.$cible.'</i> car il n\'est pas au m&ecirc;me endroit que vous.</strong></p>');
	$l = 1;
	}

if($_SESSION['credits']<$trans)
	{
	print('<p align="center"><strong>Il est impossible de donner <i>'.$trans.'</i> Cr&eacute;dits &agrave; <i>'.$_SESSION['cible'].'</i> car vous n\'avez pas assez sur vous.</strong></p>');
	$l = 1;
	}
elseif($l!=1)
	{
	$_SESSION['credits'] = $_SESSION['credits'] - $trans;
	$_SESSION['creditsc'] = $_SESSION['creditsc'] + $trans;
	$sql = 'UPDATE principal_tbl SET credits= "'.$_SESSION['creditsc'].'" WHERE id= "'.$_SESSION['idc'].'"' ;
	$req = mysql_query($sql);
	$sql = 'UPDATE principal_tbl SET credits= "'.$_SESSION['credits'].'" WHERE id= "'.$_SESSION['id'].'"' ;
	$req = mysql_query($sql);
	$sql = 'INSERT INTO transferts_tbl(donneur,receveur,time,operation,valeur) VALUES("'.$_SESSION['pseudo'].'","'.$_SESSION['cible'].'","'.time().'","Don argent","'.$trans.'")' ;
	mysql_query($sql);
	$sql = 'INSERT INTO messages_tbl(id,auteur,cible,message,objet,moment) VALUES("","Dreadcast","'.$_SESSION['cible'].'","'.$_SESSION['pseudo'].' vous &agrave; offert '.$trans.' Cr&eacute;dits.","Merci !","'.time().'")' ;
	$req = mysql_query($sql);
	if(ereg("credits",$_SESSION['evenementsSMSc']))
		{
		$SMSok = 1;
		}
	
	if(ereg("credits",$_SESSION['SMSdjc']))
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
		$retour = sendSMS("06".$_SESSION['telephonec'], "- Dreadcast Infos - Quelqu\'un vous donne des Crédits ! Connectez-vous pour avoir plus de détails.", 'Dreadcast');
		$sql = 'UPDATE principal_tbl SET SMS= "'.$_SESSION['SMSc'].'" , SMSdj= "'.$_SESSION['SMSdjc'].' credits" WHERE id= "'.$_SESSION['idc'].'"';
		$req = mysql_query($sql);
		}
	}

mysql_close($db);

?>

<em>Vous avez donn&eacute; <strong><? print(''.$trans.''); ?> </strong> Cr&eacute;dits &agrave; <? print(''.$_SESSION['cible'].''); ?>...</em>


</p>
</div>
<?php if($chat=="oui") 	{ if ((date("H")>=21) || (date("H")<=7)) { include("inc_bas_n_c.php"); } else { include("inc_bas_j_c.php"); } } else {	if ((date("H")>=21) || (date("H")<=7)) { include("inc_bas_n.php"); } else { include("inc_bas_j.php"); }	} ?>
<?php include("inc_bas_de_page.php"); ?>
