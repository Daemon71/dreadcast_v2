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
			Courrier
		</div>
		<b class="module4ie"><a href="engine=messages.php" class="module4"><img src="im_objets/icon_retour.gif" alt="Retour" />Retour</a></b>
</p>
	</div>
</div>
<div id="centre">
<div class="messagesvip">

<?php  
$db = mysql_connect('localhost', 'CENSURE', 'CENSURE') or die("Erreur de connexion au serveur des messages.");
mysql_select_db('CENSURE',$db) or die("Erreur de connexion a la base de donnees des messages.");

$sql = 'SELECT alcool,sexe,age,taille,resistance,objet FROM principal_tbl WHERE id= "'.$_SESSION['id'].'"' ;
$req = mysql_query($sql);
$_SESSION['objet'] = mysql_result($req,0,objet);
$_SESSION['sexe'] = mysql_result($req,0,sexe);
$_SESSION['age'] = mysql_result($req,0,age);
$_SESSION['taille'] = mysql_result($req,0,taille);
$_SESSION['resistance'] = mysql_result($req,0,resistance);
$_SESSION['alcool'] = mysql_result($req,0,alcool);

if(alcootest($_SESSION['pseudo'], $_SESSION['alcool'], $_SESSION['sexe'], $_SESSION['age'], $_SESSION['taille'], $_SESSION['resistance'])>1.5)
	{
	print('Vous êtes trop saoul pour pouvoir envoyer un message.');
	}
else
	{
	$cible = $_POST["cible"];

	// Groupe du carnet
	if(ereg("Groupe ",$cible))
		{
		$tmp = stripslashes(substr($cible,7));
		$ok="ok";
		
		$sql = 'SELECT contact FROM carnets_tbl WHERE pseudo="'.$_SESSION['pseudo'].'" AND statut = "'.$tmp.'" AND contact!="'.$_SESSION['pseudo'].'"';
		$req = mysql_query($sql);
		$res = mysql_num_rows($req);
		
		if($res != 0) $cible = mysql_result($req,0,contact);
		for($i=1;$i<$res;$i++) $cible .= ','.mysql_result($req,$i,contact);
		}
	
	// Plusieurs destinataires
	$cible = explode(",",$cible);
	
	// Envoi a tous
	if($cible[0]=="Tous" && $_SESSION['statut']=="Administrateur")
		{
		$sql = 'SELECT pseudo FROM principal_tbl WHERE action != "mort" OR (action = "mort" AND num < 15)';
		$reqtout = mysql_query($sql);
		$restout = mysql_num_rows($reqtout);
		}
	
	$nbdest = ($restout==0)?count($cible):$restout;
	$time=time();
	
	for($i=0;$i<$nbdest;$i++)
		{
		
		$lacible = ($restout==0)?trim($cible[$i]):mysql_result($reqtout,$i,pseudo);
		
		$sqlb = 'SELECT id,telephone,SMS,SMSdj,evenementsSMS,connec FROM principal_tbl WHERE pseudo="'.$lacible.'"' ;
		$reqb = mysql_query($sqlb);
		$resb = mysql_num_rows($reqb);
		
		if($resb!=0)
			{
			$_SESSION['idc'] = mysql_result($reqb,0,id);
			$_SESSION['telephonec'] = mysql_result($reqb,0,telephone);
			$_SESSION['SMSc'] = mysql_result($reqb,0,SMS);
			$_SESSION['SMSdjc'] = mysql_result($reqb,0,SMSdj);
			$_SESSION['evenementsSMSc'] = mysql_result($reqb,0,evenementsSMS);
			$connecc = mysql_result($reqb,0,connec);
			
			$tmp = $_SESSION['pseudo'];
			if(preg_match('#^\[MASQUE\]#isU',$_POST['cible2'])) $tmp = "";
			$sqls = 'SELECT signature FROM signaturesperso_tbl WHERE pseudo="'.$tmp.'"' ;
			$reqs = mysql_query($sqls);
			$ress = mysql_num_rows($reqs);
			
			$lemessage = htmlentities(alcohol_speak($_POST['message'],alcootest($_SESSION['pseudo'], $_SESSION['alcool'], $_SESSION['sexe'], $_SESSION['age'], $_SESSION['taille'], $_SESSION['resistance'])*10));
			
			if($_GET['id']!="") // SI C'EST UNE REPONSE
				{
				$sql2 = 'SELECT id,objet,auteur,message,moment,idrep FROM messages_tbl WHERE id="'.$_GET['id'].'" AND cible = "'.$_SESSION['pseudo'].'"' ;
				$req2 = mysql_query($sql2);
				$res2 = mysql_num_rows($req2);	
	
				if($res2!=0) // VERIFICATION
					{
					$id = mysql_result($req2,0,id);
					$autremess = mysql_result($req2,0,message);
					$idrep = mysql_result($req2,0,idrep);
					$objet = mysql_result($req2,0,objet);
					
					if(preg_match('#^\[MASQUE\]#isU',$objet))
						{
						print('<meta http-equiv="refresh" content="0 ; url=http://v2.dreadcast.net/ingame/engine.php"> ');
						exit();
						}
				
					if($idrep==0) // SI C'EST LA PREMIERE REPONSE
						{
						$auteur = mysql_result($req2,0,auteur);
						if(preg_match('#^\[MASQUE\]#isU',$objet)) { $auteur = "-Anonyme-"; }
						$convers = "[CONVERSATION]Message de ".$auteur." le ".date('d/m/y',mysql_result($req2,0,moment))." &agrave; ".date('H\hi',mysql_result($req2,0,moment))."<br /><br />".$autremess;
					
						if($ress>0) $signature = '<br /><br />--------<br /><br />'.mysql_result($reqs,0,signature);
					
						$lemessage .= $signature.$convers;
						$sql = 'INSERT INTO messages_tbl(id,auteur,cible,message,objet,moment,nouveau,idrep) VALUES("","'.$_SESSION['pseudo'].'","'.$lacible.'","'.str_replace("\n","<br />",''.$lemessage.'').'","'.$_POST['cible2'].'","'.$time.'","oui","'.$_GET['id'].'")' ;
						$req = mysql_query($sql);
						}
					else // SI CE N'EST PAS LA PREMIERE REPONSE
						{
						$sql = 'SELECT message FROM messages_tbl WHERE idrep="'.$id.'" AND auteur = "'.$_SESSION['pseudo'].'" AND nouveau = "oui" ORDER BY moment DESC LIMIT 1' ;
						$req = mysql_query($sql);
						$res = mysql_num_rows($req);
						if($res)
							{
							$oldmsg = explode("[CONVERSATION]",mysql_result($req,0,message));
							$lemessage = $oldmsg[0].'<br /><br /><strong>=====================================</strong><br /><br />'.$lemessage;
							}
					
						$auteur = mysql_result($req2,0,auteur);
						if(preg_match('#^\[MASQUE\]#isU',$objet)) { $auteur = "-Anonyme-"; }
						$convers = "[CONVERSATION]Message de ".$auteur." le ".date('d/m/y',mysql_result($req2,0,moment))." &agrave; ".date('H\hi',mysql_result($req2,0,moment))."<br /><br />".$autremess;
					
						if($ress>0) $signature = '<br /><br />--------<br /><br />'.mysql_result($reqs,0,signature);
					
						$lemessage .= $signature.$convers;

						$sql = 'UPDATE messages_tbl SET auteur="'.$_SESSION['pseudo'].'", message="'.str_replace("\n","<br />",$lemessage).'", moment="'.$time.'", nouveau="oui", idrep="'.$_GET['id'].'" WHERE id="'.$idrep.'"' ;
						$req = mysql_query($sql);
						}
					}
				else // SI ID MAUVAIS OU PAS A NOUS
					{
					if($ress>0) $signature .= '<br /><br />--------<br /><br />'.mysql_result($reqs,0,signature);
					
					$lemessage .= $signature;
					$sql = 'INSERT INTO messages_tbl(id,auteur,cible,message,objet,moment,nouveau,idrep) VALUES("","'.$_SESSION['pseudo'].'","'.$lacible.'","'.str_replace("\n","<br />",''.$lemessage.'').'","'.$_POST['cible2'].'","'.$time.'","oui","'.$_GET['id'].'")' ;
					$req = mysql_query($sql);
					}
				}
			elseif($_POST['transfert']) // SI TRANSFERT
				{
				$sql2 = 'SELECT auteur,message,objet,moment FROM messages_tbl WHERE id="'.$_POST['transfert'].'" AND cible = "'.$_SESSION['pseudo'].'"' ;
				$req2 = mysql_query($sql2);
				$res2 = mysql_num_rows($req2);
				
				if($res2 != 0)
					{
					$autremess = mysql_result($req2,0,message);
					$auteur = mysql_result($req2,0,auteur);
					$objet = mysql_result($req2,0,objet);
					if(preg_match('#^\[MASQUE\]#isU',$objet)) { $auteur = "-Anonyme-"; }
					$convers = "[CONVERSATION]Message de ".$auteur." le ".date('d/m/y',mysql_result($req2,0,moment))." &agrave; ".date('H\hi',mysql_result($req2,0,moment))."<br /><br />".$autremess;
					}
				
				if($ress>0) $signature = '<br /><br />--------<br /><br />'.mysql_result($reqs,0,signature);
			
				$lemessage .= $signature.$convers;
			
				$sql = 'INSERT INTO messages_tbl(id,auteur,cible,message,objet,moment,nouveau,idrep) VALUES("","'.$_SESSION['pseudo'].'","'.$lacible.'","'.str_replace("\n","<br />",''.$lemessage.'').'","'.htmlentities($_POST['cible2']).'","'.$time.'","oui","0")' ;
				mysql_query($sql);
				}
			else // SI PAS REPONSE
				{
				if($ress>0) $signature = '<br /><br />--------<br /><br />'.mysql_result($reqs,0,signature);
				
				$lemessage .= $signature;
				$sql = 'INSERT INTO messages_tbl(id,auteur,cible,message,objet,moment,nouveau,idrep) VALUES("","'.$_SESSION['pseudo'].'","'.$lacible.'","'.str_replace("\n","<br />",''.$lemessage.'').'","'.$_POST['cible2'].'","'.$time.'","oui","'.$_GET['id'].'")' ;
				mysql_query($sql);
				}
		
			enregistre($_SESSION['pseudo'],"acc_mp_envoye","+1");	
			enregistre($lacible,"acc_mp_recu","+1");	
		
			print('Votre message a correctement &eacute;t&eacute; envoy&eacute; &agrave; '.$lacible.'.<br /> ');
		
		
			if(ereg("message",$_SESSION['evenementsSMSc']))
				{
				$SMSok = 1;
				}
		
			if(ereg("message",$_SESSION['SMSdjc']))
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
				$retour = sendSMS("06".$_SESSION['telephonec'], "- Dreadcast Infos - Vous avez un nouveau message ! Connectez-vous pour pouvoir le consulter.", 'Dreadcast');
				$sql = 'UPDATE principal_tbl SET SMS= "'.$_SESSION['SMSc'].'" , SMSdj= "'.$_SESSION['SMSdjc'].' message" WHERE id= "'.$_SESSION['idc'].'"';
				$req = mysql_query($sql);
				}
			}
		else
			{
			print('Personne ne s\'appelle <i>'.$lacible.'</i>.<br />');
			$erreur = 1;
			}
		}
	}

if($erreur == 1) print('<br />Message original : <br /><br />'.stripslashes('<em>'.$_POST['message'].'</em>'));

mysql_close($db);
	
?>

</div>
</div>
<?php if($chat=="oui") 	{ if ((date("H")>=21) || (date("H")<=7)) { include("inc_bas_n_c.php"); } else { include("inc_bas_j_c.php"); } } else {	if ((date("H")>=21) || (date("H")<=7)) { include("inc_bas_n.php"); } else { include("inc_bas_j.php"); }	} ?>
<?php include("inc_bas_de_page.php"); ?>
