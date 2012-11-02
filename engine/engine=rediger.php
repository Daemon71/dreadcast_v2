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
			AITL - Rédaction
		</div>
		<b class="module4ie"><a href="engine=aitl.php?type=adj" class="module4"><img src="im_objets/icon_retour.gif" alt="Retour" />Retour</a></b>
</p>
	</div>
</div>
<div id="centreaitl">
<div id="contenuaitl2">


          <?php 

$db = mysql_connect('localhost', 'CENSURE', 'CENSURE') or die("Erreur de connexion au serveur des messages.");
mysql_select_db('CENSURE',$db) or die("Erreur de connexion a la base de donnees des messages.");

$sql = 'SELECT case1,case2,case3,case4,case5,case6 FROM principal_tbl WHERE id= "'.$_SESSION['id'].'"' ;
$req = mysql_query($sql);
$_SESSION['case1'] = mysql_result($req,0,'case1');
$_SESSION['case2'] = mysql_result($req,0,'case2');
$_SESSION['case3'] = mysql_result($req,0,'case3');
$_SESSION['case4'] = mysql_result($req,0,'case4');
$_SESSION['case5'] = mysql_result($req,0,'case5');
$_SESSION['case6'] = mysql_result($req,0,'case6');

if((statut($_SESSION['statut'])>=2) || ($_SESSION['case1']=="AITL") || ($_SESSION['case2']=="AITL") || ($_SESSION['case3']=="AITL") || ($_SESSION['case4']=="AITL") || ($_SESSION['case5']=="AITL") || ($_SESSION['case6']=="AITL"))
	{
	if(empty($_POST['contenu']))$_POST['contenu']="";
	if($_POST['contenu']!="")
		{
		$sql = 'INSERT INTO articlesprop_tbl(id,auteur,titre,contenu) VALUES("","'.$_SESSION['pseudo'].'","'.$_POST['titre'].'","'.str_replace("\n","<br />",''.htmlentities($_POST["contenu"],ENT_QUOTES).'').'")';
		$req = mysql_query($sql);
		print('<br /><strong>Votre article à été envoyé à l\'administration.</strong><br />Vous aurez une réponse par courrier dans peu de temps.
			<br />Si votre article est choisi, il fera la une de l\'AITL d\'ici quelques jours.');
		}
	else
		{
		print('<form method="post" action="engine=rediger.php">
		<p align="center"><strong>Aucune faute d\'orthographe ne sera tolérée par les administrateurs.</strong><br /><br />
		Titre: <input type="text" name="titre" size="20" maxlenght="30" /><br /><br />
		Contenu:<br /><textarea cols="40" rows="10" name="contenu">Aucune faute d\'orthographe ou de syntaxe ne sera tolérée par les administrateurs. En cas de refus et pour ne pas perdre votre contenu, n\'oubliez pas de faire une sauvegarde sur votre ordinateur. Un motif vous sera fourni par l\'administration si votre article n\'est pas retenu, n\'ayez donc pas peur de vous lancer !</textarea>
		<br /><br /><input type="submit" value="Valider" /></p></form>');
		}
	}


mysql_close($db);

?>


</div>
</div>
<?php if($chat=="oui") 	{ if ((date("H")>=21) || (date("H")<=7)) { include("inc_bas_n_c.php"); } else { include("inc_bas_j_c.php"); } } else {	if ((date("H")>=21) || (date("H")<=7)) { include("inc_bas_n.php"); } else { include("inc_bas_j.php"); }	} ?>
<?php include("inc_bas_de_page.php"); ?>
