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
if($_SESSION['action']=="prison")
	{
	print('<meta http-equiv="refresh" content="0 ; url=http://v2.dreadcast.net/ingame/engine=cryo.php"> ');
	exit();
	}
if($_SESSION['action']=="mort")
	{
	print('<meta http-equiv="refresh" content="0 ; url=http://v2.dreadcast.net/ingame/engine=mort.php"> ');
	exit();
	}
if(ereg("Protection ",$_SESSION['action']))
	{
	print('<meta http-equiv="refresh" content="0 ; url=http://v2.dreadcast.net/ingame/engine.php"> ');
	exit();
	}

$db = mysql_connect('localhost', 'CENSURE', 'CENSURE') or die("Erreur de connexion au serveur des messages.");
mysql_select_db('CENSURE',$db) or die("Erreur de connexion a la base de donnees des messages.");

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

if($_SESSION['code'] != $codounet)
	{
	mysql_close($db);
	print('<meta http-equiv="refresh" content="0 ; url=http://v2.dreadcast.net/ingame/engine.php"> ');
	exit();
	}

if(!ereg($_SERVER['QUERY_STRING'],$_SESSION['personnes']))
	{
	$_SESSION['personnes'] = "";
	print('<meta http-equiv="refresh" content="0 ; url=http://v2.dreadcast.net/ingame/engine.php"> ');
	exit();
	}
else $cible = $_SERVER['QUERY_STRING'];

mysql_close($db);

?>
<?php if ((date("H")>=21) || (date("H")<=7)) { include("inc_haut_n.php"); } else { include("inc_haut_j.php");} ?>

<div id="haut">
	<?php include("inc_entete.php"); ?>
	<div class="haut_aligngauche">
		<p>
		<div class="titrepage">
			Protection
		</div>
		<b class="module4ie"><a href="engine.php" class="module4"><img src="im_objets/icon_retour.gif" alt="Retour" />Retour</a></b>
</p>
	</div>
</div>
<div id="centre">
<p>

<?php  
	
	$db = mysql_connect('localhost', 'CENSURE', 'CENSURE') or die("Erreur de connexion au serveur.");
	mysql_select_db('CENSURE',$db) or die("Erreur de connexion a la base de donnees.");
	
	$sql = 'SELECT action FROM principal_tbl WHERE pseudo = "'.$cible.'"' ;
	$req = mysql_query($sql);
	$res = mysql_num_rows($req);
	
	if(!$res || ($res && (mysql_result($req,0,action) == "mort" || mysql_result($req,0,action) == "prison")))
		{
		print('<meta http-equiv="refresh" content="0 ; url=http://v2.dreadcast.net/ingame/engine.php"> ');
		exit();
		}
	
	$sql = 'SELECT pseudo FROM principal_tbl WHERE action = "Protection '.$_SESSION['pseudo'].'"' ;
	$req = mysql_query($sql);
	$res = mysql_num_rows($req);
	
	if($res == 0)
		{
		$sqlm = 'SELECT id FROM messages_tbl WHERE objet = "Demande de protection" AND auteur = "Dreadcast" AND cible = "'.$cible.'" AND message LIKE "'.$_SESSION['pseudo'].' vous propose sa protection.%"' ;
		$reqm = mysql_query($sqlm);
		$resm = mysql_num_rows($reqm);
		
		if($resm == 0)
			{		
			$sql = 'SELECT pseudo FROM principal_tbl WHERE action = "Protection '.$cible.'"' ;
			$req = mysql_query($sql);
			$res = mysql_num_rows($req);
		
			if($res == 0)
				{
				$sql = 'INSERT INTO messages_tbl(auteur,cible,objet,message,moment) VALUES("Dreadcast","'.$cible.'","Demande de protection","'.$_SESSION['pseudo'].' vous propose sa protection. Si vous acceptez, il s\'interposera à tout assaillant tant qu\'il sera près de vous.<br /><br /><a href=\"engine=protecchoix.php?pseudo='.$_SESSION['pseudo'].'&choix=oui\">Accepter la protection</a> - <a href=\"engine=protecchoix.php?pseudo='.$_SESSION['pseudo'].'&choix=non\">Refuser la protection</a>",'.time().')' ;
				mysql_query($sql);
				}
			else
				{
				$sql = 'INSERT INTO messages_tbl(auteur,cible,objet,message,moment) VALUES("Dreadcast","'.$cible.'","Demande de protection","'.$_SESSION['pseudo'].' vous propose sa protection. Mais vous êtes déjà protégé par '.mysql_result($req,0,pseudo).'. Souhaitez-vous changer de garde du corps ?<br /><br /><a href=\"engine=protecchoix.php?pseudo='.$_SESSION['pseudo'].'&choix=oui\">Accepter la protection</a> - <a href=\"engine=protecchoix.php?pseudo='.$_SESSION['pseudo'].'&choix=non\">Refuser la protection</a>",'.time().')' ;
				mysql_query($sql);
				}
				
			print('Une demande a été envoyée à '.$cible.'.<br />Vous recevrez un message pour confirmer votre statut de garde du corps.');
			}
		else print('Vous lui avez déjà proposé votre protection.');
		}
	else print('Vous êtes vous-même protégé par '.mysql_result($req,0,pseudo).', vous ne pouvez pas protéger quelqu\'un.');

?>

</p>
</div>
<?php if($chat=="oui") 	{ if ((date("H")>=21) || (date("H")<=7)) { include("inc_bas_n_c.php"); } else { include("inc_bas_j_c.php"); } } else {	if ((date("H")>=21) || (date("H")<=7)) { include("inc_bas_n.php"); } else { include("inc_bas_j.php"); }	} ?>
<?php include("inc_bas_de_page.php"); ?>
