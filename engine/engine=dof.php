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
			Donner un objet
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

$sql = 'SELECT id,rue,num FROM principal_tbl WHERE pseudo= "'.$_SESSION['pseudo'].'"' ;
$req = mysql_query($sql);
$_SESSION['id'] = mysql_result($req,0,id);
$_SESSION['lieu'] = mysql_result($req,0,rue);
$_SESSION['num'] = mysql_result($req,0,num);

forme_retirer($_SESSION['id'],1);
$_SESSION['fatigue'] -= 1;

$_SESSION['cible'] = str_replace("%20"," ",''.$_GET['cible'].'');

$sql = 'SELECT id,rue,num,SMSdj,evenementsSMS,connec,telephone,SMS FROM principal_tbl WHERE pseudo= "'.$_SESSION['cible'].'"' ;
$req = mysql_query($sql);
$_SESSION['idc'] = mysql_result($req,0,id);
$_SESSION['lieuc'] = mysql_result($req,0,rue);
$_SESSION['numc'] = mysql_result($req,0,num);
$_SESSION['SMSdjc'] = mysql_result($req,0,SMSdj);
$_SESSION['evenementsSMSc'] = mysql_result($req,0,evenementsSMS);
$connecc = mysql_result($req,0,connec);
$_SESSION['telephonec'] = mysql_result($req,0,telephone);
$_SESSION['SMSc'] = mysql_result($req,0,SMS);

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

if(!estVisible($_SESSION['cible'],25))
	{
	print('<meta http-equiv="refresh" content="0 ; url=http://v2.dreadcast.net/ingame/engine=liste.php"> ');
	exit();
	}
else
	{
	
	$sql = 'SELECT case1,case2,case3,case4,case5,case6 FROM principal_tbl WHERE id= "'.$_SESSION['idc'].'"' ;
	$req = mysql_query($sql);
	$_SESSION['case1c'] = mysql_result($req,0,case1); 
	$_SESSION['case2c'] = mysql_result($req,0,case2); 
	$_SESSION['case3c'] = mysql_result($req,0,case3); 
	$_SESSION['case4c'] = mysql_result($req,0,case4); 
	$_SESSION['case5c'] = mysql_result($req,0,case5); 
	$_SESSION['case6c'] = mysql_result($req,0,case6); 

	$sql = 'SELECT case'.$_GET['case'].' FROM principal_tbl WHERE id= "'.$_SESSION['id'].'"' ;
	$req = mysql_query($sql);
	$ouin = 'case'.$_GET['case'].'';
	$objet = mysql_result($req,0,$ouin); 

	$trans = 0;
	for($i=1; $i != 7 ; $i++) 
		{
		if(($_SESSION['case'.$i.'c']=="Vide") && ($trans==0))
			{
			$sql = 'UPDATE principal_tbl SET case'.$i.'="'.$objet.'" WHERE id="'.$_SESSION['idc'].'"';
			$req = mysql_query($sql);
			$sql = 'UPDATE principal_tbl SET case'.$_GET['case'].'="Vide" WHERE id="'.$_SESSION['id'].'"';
			$req = mysql_query($sql);
			$sql = 'INSERT INTO transferts_tbl(donneur,receveur,time,operation,valeur) VALUES("'.$_SESSION['pseudo'].'","'.$_SESSION['cible'].'","'.time().'","Don objet","'.$objet.'")' ;
			mysql_query($sql);
			print("<p align='center'><strong>Transaction termin&eacute;e.</strong></p>");
			$sql = 'INSERT INTO messages_tbl(id,auteur,cible,message,objet,moment) VALUES("","Dreadcast","'.$_SESSION['cible'].'","'.$_SESSION['pseudo'].' vous &agrave; offert un objet: '.$objet.'.","Merci !","'.time().'")' ;
			$req = mysql_query($sql);
			$trans = 1;
			if(ereg("objet",$_SESSION['evenementsSMSc']))
				{
				$SMSok = 1;
				}
			
			if(ereg("objet",$_SESSION['SMSdjc']))
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
				$retour = sendSMS("06".$_SESSION['telephonec'], "- Dreadcast Infos - Quelqu\'un vous donne un objet ! Connectez-vous pour avoir plus de détails.", 'Dreadcast');
				$sql = 'UPDATE principal_tbl SET SMS= "'.$_SESSION['SMSc'].'" , SMSdj= "'.$_SESSION['SMSdjc'].' objet" WHERE id= "'.$_SESSION['idc'].'"';
				$req = mysql_query($sql);
				}
			}
		}
	
	if($trans!=1)
		{
		print("<p align='center'><strong>Il n'y a pas d'emplacement vide dans son inventaire personnel.</strong></p>");
		}
	}

mysql_close($db);

?>


</p>
</div>
<?php if($chat=="oui") 	{ if ((date("H")>=21) || (date("H")<=7)) { include("inc_bas_n_c.php"); } else { include("inc_bas_j_c.php"); } } else {	if ((date("H")>=21) || (date("H")<=7)) { include("inc_bas_n.php"); } else { include("inc_bas_j.php"); }	} ?>
<?php include("inc_bas_de_page.php"); ?>
