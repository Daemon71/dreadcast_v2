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

$sql = 'SELECT id,rue,num,action,alim,sante,fatigue FROM principal_tbl WHERE pseudo= "'.$_SESSION['pseudo'].'"' ;
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

if($_GET['idtous']!="")
	{
	$sql2 = 'SELECT auteur,moment FROM messages_tbl WHERE id="'.$_GET['idtous'].'" AND cible="'.$_SESSION['pseudo'].'"' ;
	$req2 = mysql_query($sql2);
	$res2 = mysql_num_rows($req2);
	
	if($res2!=0)
		{
		$sql3 = 'SELECT cible FROM messages_tbl WHERE auteur="'.mysql_result($req2,0,auteur).'" AND cible!="'.$_SESSION['pseudo'].'" AND moment="'.mysql_result($req2,0,moment).'"' ;
		$req3 = mysql_query($sql3);
		$res3 = mysql_num_rows($req3);
		
		for($i=0;$i<$res3;$i++) $cible .= ','.mysql_result($req3,$i,cible);
		}
		
	$cible = mysql_result($req2,0,auteur).$cible;
	
	}

mysql_close($db);

?>
<?php if ((date("H")>=21) || (date("H")<=7)) { include("inc_haut_n.php"); } else { include("inc_haut_j.php");} ?>

<div id="haut">
	<?php include("inc_entete.php"); ?>
	<div class="haut_aligngauche">
		<p>
		<div class="titrepage">
			Courrier
		</div>
		</p>
	</div>
</div>
<div id="centre">
<p>

<?php

$event = event() && estDroide() ? $_SESSION['event'] : 0;
if ($event == 1) {
	echo "<strong>Vous &ecirc;tes contamin&eacute;s et ne pouvez pas envoyer de messages !</strong>";
} else {

?>

<strong>Envoyer un message priv&eacute; :</strong>
<form name="allera" id="allera" method="post" action="engine=envoyer.php<?php if($_GET['id'] != "" AND $_GET['transferer'] == "") print('?id='.$_GET['id']); ?>">
Cible : 

<?php

if($_GET['transferer'] != "")
	{
	$db = mysql_connect('localhost', 'CENSURE', 'CENSURE') or die("Erreur de connexion au serveur des messages.");
	mysql_select_db('CENSURE',$db) or die("Erreur de connexion a la base de donnees des messages.");
	
	$sql = 'SELECT message,objet FROM messages_tbl WHERE id="'.htmlentities($_GET['transferer']).'" AND cible="'.$_SESSION['pseudo'].'"';
	$req = mysql_query($sql);
	$res = mysql_num_rows($req);
	
	if($res != 0)
		{
		$message = mysql_result($req,0,message);
		$objet = mysql_result($req,0,objet);
		}
		
	mysql_close($db);
	}


if($_GET['id'] != "" AND $_GET['transferer'] == "")
	{
	$db = mysql_connect('localhost', 'CENSURE', 'CENSURE') or die("Erreur de connexion au serveur des messages.");
	mysql_select_db('CENSURE',$db) or die("Erreur de connexion a la base de donnees des messages.");
	
	$sql = 'SELECT auteur,objet FROM messages_tbl WHERE id="'.htmlentities($_GET['id']).'" AND cible="'.$_SESSION['pseudo'].'"';
	$req = mysql_query($sql);
	$res = mysql_num_rows($req);
	
	if($res != 0)
		{
		$cible = mysql_result($req,0,auteur);
		$objet = mysql_result($req,0,objet);
		if(preg_match('#^\[MASQUE\]#isU',$objet))
			{
			$objet = trim(str_replace('[MASQUE]','',preg_replace('#^\[MASQUE\](.+)#isU',"$1",$objet)));
			$cible = "-Anonyme-";
			print('<meta http-equiv="refresh" content="0 ; url=http://v2.dreadcast.net/ingame/engine.php"> ');
			exit();
			}
		}
	
	if($res != 0)
		{
		print('<strong>'.$cible.'</strong>');
		print('<input name="cible" type="hidden" value="'.stripslashes($cible).'" />');
		}
	else
		print('<strong>Aucune</strong>');
		
	mysql_close($db);
	}
else
	{
	if($cible=="" AND $_GET['cible']!="") $cible = str_replace("%20"," ",''.$_GET['cible'].'');
	if($cible=="DC-01")
		{
		print('<meta http-equiv="refresh" content="0 ; url=http://v2.dreadcast.net/ingame/engine=DC-01.php"> ');
		exit();
		}
	elseif($cible=="Dreadcast" OR ereg(",Dreadcast",$cible) OR ereg(",Dreadcast,",$cible) OR ereg("Dreadcast,",$cible))
		{
		print('<meta http-equiv="refresh" content="0 ; url=http://v2.dreadcast.net/ingame/engine=dreadcast.php"> ');
		exit();
		}
	else
		print('<input name="cible" type="text" id="cible2" value="'.stripslashes($cible).'" size="10" />');
	}
?>
<br />
Objet :
<input name="cible2" type="text" id="cible" size="20" maxlength="20" <? 

if($objet == "") $objet = str_replace("%20"," ",''.$_GET['objet'].'');
if(preg_match('#^\[MASQUE\]#isU',$objet)) { $objet = trim(str_replace('[MASQUE]','',preg_replace('#^\[MASQUE\](.+)#isU',"$1",$objet))); }
if($objet != "" && $_GET['transferer'] != "") { if(!ereg('FWD:',$objet)) $tmp = 'FWD:'; print('value="'.$tmp.$objet.'"'); }
elseif($objet != "") { if(!ereg('RE:',$objet)) $tmp = 'RE:'; print('value="'.$tmp.$objet.'"'); }
elseif($_GET['forum']!="") print('value="[FORUM] "'); 

?> />
<br /><br />
Message :<br /><br />
<textarea name="message" cols="55" rows="6" id="message"></textarea>
<br />
<?php if($message != "") print('<input type="hidden" name="transfert" value="'.htmlentities($_GET['transferer']).'" />'); ?>
<input type="submit" name="Submit" value="Envoyer" />
</form>

<?php } ?>

</p>
</div>
<?php if($chat=="oui") 	{ if ((date("H")>=21) || (date("H")<=7)) { include("inc_bas_n_c.php"); } else { include("inc_bas_j_c.php"); } } else {	if ((date("H")>=21) || (date("H")<=7)) { include("inc_bas_n.php"); } else { include("inc_bas_j.php"); }	} ?>
<?php include("inc_bas_de_page.php"); ?>
