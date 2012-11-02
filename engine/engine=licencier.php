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

$idi = $_GET['id1'];
$idt = $_GET['id2'];

$sql = 'SELECT type,entreprise,points FROM principal_tbl WHERE id= "'.$_SESSION['id'].'"' ;
$req = mysql_query($sql);
$_SESSION['poste'] = mysql_result($req,0,type); 
$_SESSION['entreprise'] = mysql_result($req,0,entreprise); 
$_SESSION['points'] = mysql_result($req,0,points); 

if($_SESSION['points']!=999) { print('<meta http-equiv="refresh" content="0 ; url=http://v2.dreadcast.net/ingame/engine.php"> '); exit(); }

$sql = 'SELECT pseudo,action FROM principal_tbl WHERE id= "'.$idt.'"' ;
$req = mysql_query($sql);
$ps = mysql_result($req,0,pseudo); 
$act = mysql_result($req,0,action); 

$sql = 'INSERT INTO messages_tbl(id,auteur,cible,message,objet,moment) VALUES("","'.$_SESSION['entreprise'].'","'.$ps.'","Vous &ecirc;tes licenci&eacute;.","Vous êtes licencié","'.time().'")' ;
$req = mysql_query($sql);

$sql = 'SELECT type,SMSdj,evenementsSMS,connec,telephone,SMS FROM principal_tbl WHERE id= "'.$idt.'"' ;
$req = mysql_query($sql);
$type = mysql_result($req,0,type); 
$_SESSION['SMSdjc'] = mysql_result($req,0,SMSdj);
$_SESSION['evenementsSMSc'] = mysql_result($req,0,evenementsSMS);
$connecc = mysql_result($req,0,connec);
$_SESSION['telephonec'] = mysql_result($req,0,telephone);
$_SESSION['SMSc'] = mysql_result($req,0,SMS);

$sql = 'SELECT type,nbreactuel FROM `e_'.str_replace(" ","_",''.$_SESSION['entreprise'].'').'_tbl` WHERE poste= "'.$type.'"' ;
$req = mysql_query($sql);
$nombre = mysql_result($req,0,nbreactuel); 
$tyy = mysql_result($req,0,type); 
$nombre = $nombre - 1;

$sql = 'UPDATE principal_tbl SET entreprise="Aucune" , salaire="0" , type="Aucun" , difficulte="0" , points="0" WHERE id= "'.$idt.'"' ;
$req = mysql_query($sql);

if($act=="travail")
	{
	$sql = 'SELECT type,entreprise FROM principal_tbl WHERE id= "'.$_SESSION['idc'].'"' ;
	$req = mysql_query($sql);
	$postec = mysql_result($req,0,type); 
	$entreprisec = mysql_result($req,0,entreprise); 
	
	$sql = 'SELECT type FROM `e_'.str_replace(" ","_",''.$entreprisec.'').'_tbl` WHERE poste= "'.$postec.'"' ;
	$req = mysql_query($sql);
	$typeposte = mysql_result($req,0,type);
	
	$sql1 = 'SELECT poste FROM `e_'.str_replace(" ","_",''.$entreprisec.'').'_tbl` WHERE type= "'.$typeposte.'"' ;
	$req1 = mysql_query($sql1);
	$res1 = mysql_num_rows($req1);
	for($r=0;$r!=$res1;$r++)
		{
		$poster = mysql_result($req1,$r,poste);
		$sql = 'SELECT id FROM principal_tbl WHERE entreprise= "'.$entreprisec.'" AND type= "'.$poster.'"' ;
		$req = mysql_query($sql);
		$res = mysql_num_rows($req);
		
		$restot = 0;
		for($i=0; $i != $res; $i++)
			{
			$idipa = mysql_result($req,$i,id); 
			$sql1 = 'SELECT action FROM principal_tbl WHERE id= "'.$idipa.'"' ;
			$req1 = mysql_query($sql1);
			$act = mysql_result($req1,0,action); 
			if($act=="travail")
				{
				$restot = $restot + 1;
				}
			}
		}
	if($entreprisec!="Aucune")
		{
		$sql = 'SELECT type FROM `e_'.str_replace(" ","_",''.$entreprisec.'').'_tbl` WHERE poste= "'.$postec.'"' ;
		$req = mysql_query($sql);
		$typeposte = mysql_result($req,0,type); 
		$sql = 'SELECT type,ouvert FROM entreprises_tbl WHERE nom= "'.$entreprisec.'"' ;
		$req = mysql_query($sql);
		$type = mysql_result($req,0,type); 
		}
	if(($actc=="travail") && ($restot==1))
		{
		if(($type=="agence immobiliaire") || ($type=="boutique armes") || ($type=="boutique vetements") || ($type=="boutiques spécialisee") || ($type=="ventes aux encheres") || ($type=="centre de recherche") || ($type=="usine de production"))
			{
			if($typeposte=="vendeur")
				{
				$sql = 'UPDATE entreprises_tbl SET ouvert="non" WHERE nom= "'.$entreprisec.'"' ;
				$req = mysql_query($sql);
				}
			}
		elseif($type=="banque")
			{
			if($typeposte=="banquier")
				{
				$sql = 'UPDATE entreprises_tbl SET ouvert="non" WHERE nom= "'.$entreprisec.'"' ;
				$req = mysql_query($sql);
				}
			}
		elseif(($type=="bar cafe") || ($type=="restaurant"))
			{
			if($typeposte=="serveur")
				{
				$sql = 'UPDATE entreprises_tbl SET ouvert="non" WHERE nom= "'.$entreprisec.'"' ;
				$req = mysql_query($sql);
				}
			}
		elseif($type=="ecole ingenieur")
			{
			if(($typeposte=="profmeca") || ($typeposte=="proftechno"))
				{
				$sql = 'UPDATE entreprises_tbl SET ouvert="non" WHERE nom= "'.$entreprisec.'"' ;
				$req = mysql_query($sql);
				}
			}
		elseif($type=="ecole de commerce")
			{
			if(($typeposte=="profgestion") || ($typeposte=="profeco"))
				{
				$sql = 'UPDATE entreprises_tbl SET ouvert="non" WHERE nom= "'.$entreprisec.'"' ;
				$req = mysql_query($sql);
				}
			}
		elseif($type=="centre apprentissage")
			{
			if(($typeposte=="profcombat") || ($typeposte=="profconduite") || ($typeposte=="profmed") || ($typeposte=="proftir"))
				{
				$sql = 'UPDATE entreprises_tbl SET ouvert="non" WHERE nom= "'.$entreprisec.'"' ;
				$req = mysql_query($sql);
				}
			}
		elseif($type=="societe de transports")
			{
			if($typeposte=="chauffeur")
				{
				$sql = 'UPDATE entreprises_tbl SET ouvert="non" WHERE nom= "'.$entreprisec.'"' ;
				$req = mysql_query($sql);
				}
			}
		elseif($type=="garage")
			{
			if($typeposte=="reparateur")
				{
				$sql = 'UPDATE entreprises_tbl SET ouvert="non" WHERE nom= "'.$entreprisec.'"' ;
				$req = mysql_query($sql);
				}
			}
		elseif($type=="hopital")
			{
			if($typeposte=="medecin")
				{
				$sql = 'UPDATE entreprises_tbl SET ouvert="non" WHERE nom= "'.$entreprisec.'"' ;
				$req = mysql_query($sql);
				}
			}
		elseif(($type=="hotel") || ($type=="vente de services"))
			{
			if($typeposte=="hote")
				{
				$sql = 'UPDATE entreprises_tbl SET ouvert="non" WHERE nom= "'.$entreprisec.'"' ;
				$req = mysql_query($sql);
				}
			}
		}
	$sql = 'UPDATE principal_tbl SET action="aucune" WHERE id= "'.$idt.'"' ;
	$req = mysql_query($sql);
	}

$sql = 'UPDATE `e_'.str_replace(" ","_",''.$_SESSION['entreprise'].'').'_tbl` SET nbreactuel="'.$nombre.'" WHERE poste= "'.$type.'"' ;
$req = mysql_query($sql);

if(ereg("licencie",$_SESSION['evenementsSMSc']))
	{
	$SMSok = 1;
	}

if(ereg("licencie",$_SESSION['SMSdjc']))
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
	$retour = sendSMS("06".$_SESSION['telephonec'], "- Dreadcast Infos - Vous êtes licencié !", 'Dreadcast');
	$sql = 'UPDATE principal_tbl SET SMS= "'.$_SESSION['SMSc'].'" , SMSdj= "'.$_SESSION['SMSdjc'].' licencie" WHERE id= "'.$idt.'"';
	$req = mysql_query($sql);
	}

print('<meta http-equiv="refresh" content="0 ; url=http://v2.dreadcast.net/ingame/engine=personnel.php"> ');

mysql_close($db);
?>
<?php if ((date("H")>=21) || (date("H")<=7)) { include("inc_haut_n.php"); } else { include("inc_haut_j.php");} ?>

<div id="haut">
	<?php include("inc_entete.php"); ?>
	<div class="haut_aligngauche">
		<p>
		<div class="titrepage">
			Licencier
		</div>
		</p>
	</div>
</div>
<div id="centre">
<p>



</p>
</div>
<?php if($chat=="oui") 	{ if ((date("H")>=21) || (date("H")<=7)) { include("inc_bas_n_c.php"); } else { include("inc_bas_j_c.php"); } } else {	if ((date("H")>=21) || (date("H")<=7)) { include("inc_bas_n.php"); } else { include("inc_bas_j.php"); }	} ?>
<?php include("inc_bas_de_page.php"); ?>
