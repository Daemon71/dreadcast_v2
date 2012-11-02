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

$protecteur = htmlentities($_GET['pseudo']);
$choix = htmlentities($_GET['choix']);

$sqlm = 'SELECT id FROM messages_tbl WHERE objet = "Demande de protection" AND auteur = "Dreadcast" AND cible = "'.$_SESSION['pseudo'].'" AND message LIKE "'.$protecteur.' vous propose sa protection.%"' ;
$reqm = mysql_query($sqlm);
$resm = mysql_num_rows($reqm);

if($resm == 0)
	{
	print('<meta http-equiv="refresh" content="0 ; url=http://v2.dreadcast.net/ingame/engine.php"> ');
	exit();
	}

$sql = 'SELECT pseudo FROM principal_tbl WHERE action = "Protection '.$_SESSION['pseudo'].'"' ;
$req = mysql_query($sql);
$res = mysql_num_rows($req);

$texte = "";

if($choix == "oui" && $res == 0)
	{
	$sql = 'SELECT num,rue,sexe FROM principal_tbl WHERE pseudo = "'.$_SESSION['pseudo'].'"' ;
	$req = mysql_query($sql);
	$monnum = mysql_result($req,0,num);
	$marue = mysql_result($req,0,rue);
	$sexe = mysql_result($req,0,sexe);
	$sql = 'SELECT num,rue,action FROM principal_tbl WHERE pseudo = "'.$protecteur.'"' ;
	$req = mysql_query($sql);
	$sonnum = mysql_result($req,0,num);
	$sarue = mysql_result($req,0,rue);
	
	if(mysql_result($req,0,action) == "Prison" || ereg(" cours ",mysql_result($req,0,action)))
		{
		print('Votre protecteur ne peut pas vous protéger actuellement.
		<meta http-equiv="refresh" content="1 ; url=http://v2.dreadcast.net/ingame/engine.php"> ');
		exit();
		}
	
	if($monnum == $sonnum && $marue == $sarue)
		{
		$sql = 'INSERT INTO messages_tbl(auteur,cible,objet,message,moment) VALUES("Dreadcast","'.$protecteur.'","Protection","'.$_SESSION['pseudo'].' a accepté votre offre de protection. Tant que vous resterez près '.(($sexe == "Homme")?'de lui':'d\'elle').', vous '.(($sexe == "Homme")?'le':'la').' défendrez jusqu\'à la mort !",'.time().')' ;
		mysql_query($sql);
		$texte = 'Protection validée. Tant que '.$protecteur.' sera au même endroit que vous, vous serez protégé'.(($_SESSION['sexe'] == "Femme")?'e':'').'.';
		}
	else
		{
		$sql = 'INSERT INTO messages_tbl(auteur,cible,objet,message,moment) VALUES("Dreadcast","'.$protecteur.'","Protection","'.$_SESSION['pseudo'].' a accepté votre offre de protection. Vous pourrez '.(($sexe == "Homme")?'le':'la').' trouver '.(($monnum <= 0)?$marue:(($marue == "Ruelle")?'dans une ruelle':'au '.$monnum.' '.$marue)).'. Tant que vous resterez près '.(($sexe == "Homme")?'de lui':'d\'elle').', vous '.(($sexe == "Homme")?'le':'la').' défendrez jusqu\'à la mort !",'.time().')' ;
		mysql_query($sql);
		$texte = 'Protection validée. Lorsque '.$protecteur.' sera au même endroit que vous, vous serez protégé'.(($_SESSION['sexe'] == "Femme")?'e':'').'.';
		}
	
	$sql = 'UPDATE principal_tbl SET action = "Protection '.$_SESSION['pseudo'].'" WHERE pseudo = "'.$protecteur.'"';
	mysql_query($sql);
	
	$sql = 'DELETE FROM messages_tbl WHERE id = '.mysql_result($reqm,0,id);
	mysql_query($sql);
	}
elseif($choix == "oui")
	{
	$ancienprotec = mysql_result($req,0,pseudo);
	
	$sql = 'INSERT INTO messages_tbl(auteur,cible,objet,message,moment) VALUES("Dreadcast","'.$ancienprotec.'","Fin de protection","<br />'.$_SESSION['pseudo'].' a mis fin à votre contrat de protection.",'.time().')' ;
	mysql_query($sql);
	
	$sql = 'UPDATE principal_tbl SET action = "Aucune" WHERE pseudo = "'.$ancienprotec.'"';
	mysql_query($sql);
	
	$sql = 'SELECT num,rue,sexe FROM principal_tbl WHERE pseudo = "'.$_SESSION['pseudo'].'"' ;
	$req = mysql_query($sql);
	$monnum = mysql_result($req,0,num);
	$marue = mysql_result($req,0,rue);
	$sexe = mysql_result($req,0,sexe);
	$sql = 'SELECT num,rue FROM principal_tbl WHERE pseudo = "'.$protecteur.'"' ;
	$req = mysql_query($sql);
	$sonnum = mysql_result($req,0,num);
	$sarue = mysql_result($req,0,rue);
	
	if($monnum == $sonnum && $marue == $sarue)
		{
		$sql = 'INSERT INTO messages_tbl(auteur,cible,objet,message,moment) VALUES("Dreadcast","'.$protecteur.'","Protection","'.$_SESSION['pseudo'].' a accepté votre offre de protection. Tant que vous resterez près '.(($sexe == "Homme")?'de lui':'d\'elle').', vous '.(($sexe == "Homme")?'le':'la').' défendrez jusqu\'à la mort !",'.time().')' ;
		mysql_query($sql);
		$texte = 'Protection validée. Tant que '.$protecteur.' sera au même endroit que vous, vous serez protégé'.(($_SESSION['sexe'] == "Femme")?'e':'').'.';
		}
	else
		{
		$sql = 'INSERT INTO messages_tbl(auteur,cible,objet,message,moment) VALUES("Dreadcast","'.$protecteur.'","Protection","'.$_SESSION['pseudo'].' a accepté votre offre de protection. Vous pourrez '.(($sexe == "Homme")?'le':'la').' trouver '.(($monnum <= 0)?$marue:(($marue == "Ruelle")?'dans une ruelle':'au '.$monnum.' '.$marue)).'. Tant que vous resterez près '.(($sexe == "Homme")?'de lui':'d\'elle').', vous '.(($sexe == "Homme")?'le':'la').' défendrez jusqu\'à la mort !",'.time().')' ;
		mysql_query($sql);
		$texte = 'Protection validée. Lorsque '.$protecteur.' sera au même endroit que vous, vous serez protégé'.(($_SESSION['sexe'] == "Femme")?'e':'').'.';
		}
	
	$sql = 'UPDATE principal_tbl SET action = "Protection '.$_SESSION['pseudo'].'" WHERE pseudo = "'.$protecteur.'"';
	mysql_query($sql);
	
	$sql = 'DELETE FROM messages_tbl WHERE id = '.mysql_result($reqm,0,id);
	mysql_query($sql);
	}
else
	{
	$sql = 'INSERT INTO messages_tbl(auteur,cible,objet,message,moment) VALUES("Dreadcast","'.$protecteur.'","Refus de protection","<br />'.$_SESSION['pseudo'].' a décliné votre offre.",'.time().')' ;
	mysql_query($sql);
	$sql = 'DELETE FROM messages_tbl WHERE id = '.mysql_result($reqm,0,id);
	mysql_query($sql);
	$texte = 'Demande supprimée.<br /><a href="engine=messages.php"></a>';
	}

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
		<b class="module4ie"><a href="engine=messages.php" class="module4"><img src="im_objets/icon_retour.gif" alt="Retour" />Retour</a></b>
</p>
	</div>
</div>
<div id="centre">
<p>

<?php  

	print($texte);

?>

</p>
</div>
<?php if($chat=="oui") 	{ if ((date("H")>=21) || (date("H")<=7)) { include("inc_bas_n_c.php"); } else { include("inc_bas_j_c.php"); } } else {	if ((date("H")>=21) || (date("H")<=7)) { include("inc_bas_n.php"); } else { include("inc_bas_j.php"); }	} ?>
<?php include("inc_bas_de_page.php"); ?>
