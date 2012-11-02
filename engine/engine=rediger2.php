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
<div id="centreaitl2">
	
	<div id="menuaitl2">
		<a class="selectionne" href="engine=aitl2.php?type=tel">Canaux Impériaux</a>
		<a href="engine=aitl2.php?type=dcn">DC News</a>
		<a href="engine=aitl2.php?type=dctv">DC TV</a>
	</div>
	
	<div id="actionsaitl2">
	</div>
	
	<div id="contenuaitl2">
		<div class="scroll-pane">
			<div class="titre">Soumettre un article</div>
			<div class="texte">


          <?php 

$db = mysql_connect('localhost', 'CENSURE', 'CENSURE') or die("Erreur de connexion au serveur des messages.");
mysql_select_db('CENSURE',$db) or die("Erreur de connexion a la base de donnees des messages.");


if(statut($_SESSION['statut'])>=2 || est_dans_inventaire("AITL 2.0"))
	{
	if(empty($_POST['contenu'])) $_POST['contenu']="";
	
	if($_POST['contenu']!="")
		{
		$sql = 'INSERT INTO articlesprop_tbl(id,auteur,titre,contenu) VALUES("","'.$_SESSION['pseudo'].'","'.$_POST['titre'].'","'.str_replace("\n","<br />",''.htmlentities($_POST["contenu"],ENT_QUOTES).'').'")';
		$req = mysql_query($sql);
		print('<strong>Votre article à été envoyé.</strong><br />Vous aurez une réponse par courrier dans peu de temps.
			<br /><br />Si votre article est choisi, il fera la une de l\'AITL d\'ici quelques jours.');
		}
	else
		{
		print('<form method="post" action="engine=rediger2.php">
		Titre: <input type="text" name="titre" size="20" maxlenght="30" /><br /><br />
		Contenu:<br /><textarea cols="40" rows="10" name="contenu">Aucune faute d\'orthographe ou de syntaxe ne sera tolérée. Pour ne pas perdre votre contenu, n\'oubliez pas de faire une sauvegarde sur votre ordinateur. Un motif vous sera fourni si votre article n\'est pas retenu, n\'ayez donc pas peur de vous lancer !</textarea>
		<br /><br /><input type="submit" value="Valider" /></form>');
		}
	}


mysql_close($db);

?>

			</div>
		</div>
	</div>
</div>
<?php if($chat=="oui") 	{ if ((date("H")>=21) || (date("H")<=7)) { include("inc_bas_n_c.php"); } else { include("inc_bas_j_c.php"); } } else {	if ((date("H")>=21) || (date("H")<=7)) { include("inc_bas_n.php"); } else { include("inc_bas_j.php"); }	} ?>
<?php include("inc_bas_de_page.php"); ?>
