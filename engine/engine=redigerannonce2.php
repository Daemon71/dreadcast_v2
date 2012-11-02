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
		<b class="module4ie"><a href="engine=aitl2.php?type=pa" class="module4"><img src="im_objets/icon_retour.gif" alt="Retour" />Retour</a></b>
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
			<div class="titre">Ajouter une annonce</div>
			<div class="texte">
          <?php 

$db = mysql_connect('localhost', 'CENSURE', 'CENSURE') or die("Erreur de connexion au serveur des messages.");
mysql_select_db('CENSURE',$db) or die("Erreur de connexion a la base de donnees des messages.");

$sql = 'SELECT * FROM principal_tbl WHERE id= "'.$_SESSION['id'].'"' ;
$req = mysql_query($sql);
$_SESSION['credits'] = mysql_result($req,0,'credits');
$_SESSION['case1'] = mysql_result($req,0,'case1');
$_SESSION['case2'] = mysql_result($req,0,'case2');
$_SESSION['case3'] = mysql_result($req,0,'case3');
$_SESSION['case4'] = mysql_result($req,0,'case4');
$_SESSION['case5'] = mysql_result($req,0,'case5');
$_SESSION['case6'] = mysql_result($req,0,'case6');
$_SESSION['objet'] = mysql_result($req,0,objet);
$_SESSION['sexe'] = mysql_result($req,0,sexe);
$_SESSION['age'] = mysql_result($req,0,age);
$_SESSION['taille'] = mysql_result($req,0,taille);
$_SESSION['resistance'] = mysql_result($req,0,resistance);
$_SESSION['alcool'] = mysql_result($req,0,alcool);

if(alcootest($_SESSION['pseudo'], $_SESSION['alcool'], $_SESSION['sexe'], $_SESSION['age'], $_SESSION['taille'], $_SESSION['resistance'])<1.5)
	{
	if((statut($_SESSION['statut'])>=2) || ($_SESSION['case1']=="AITL 2.0") || ($_SESSION['case2']=="AITL 2.0") || ($_SESSION['case3']=="AITL 2.0") || ($_SESSION['case4']=="AITL 2.0") || ($_SESSION['case5']=="AITL 2.0") || ($_SESSION['case6']=="AITL 2.0"))
		{
		if(empty($_POST['annonce']))$_POST['annonce']="";
		if($_POST['annonce']!="")
			{
			if($_SESSION['credits']>=50)
				{
				$_SESSION['credits'] = $_SESSION['credits'] - 50;
				$sql = 'UPDATE principal_tbl SET credits= "'.$_SESSION['credits'].'" WHERE id= "'.$_SESSION['id'].'"' ;
				$req = mysql_query($sql);
				$sql = 'INSERT INTO petitesannonces_tbl(id,auteur,titre,annonce,moment) VALUES("","'.$_SESSION['pseudo'].'","'.$_POST['titre'].'","'.str_replace("\n","<br />",htmlentities(alcohol_speak($_POST['annonce'],alcootest($_SESSION['pseudo'], $_SESSION['alcool'], $_SESSION['sexe'], $_SESSION['age'], $_SESSION['taille'], $_SESSION['resistance'])*10))).'","'.time().'")';
				$req = mysql_query($sql);
				print('<strong>Votre annonce est parue.</strong><br />Vous aurez une réponse par courrier si quelqu\'un est interressé.');
				}
			else
				{
				print('Vous n\'avez pas assez de Crédits en poche pour déposer cette annonce.');
				}
			}
		else
			{
			print('			Poster une petite annonce coûte 50 Crédits. Chaque annonce reste en place 3 jours avant de disparaître.<br /><br />
					<form method="post" action="engine=redigerannonce2.php">
						Titre: <input type="text" name="titre" size="20" maxlenght="30" /><br /><br />
						Contenu:<br />
						<textarea cols="20" rows="9" name="annonce"></textarea><br /><br />
						<input type="submit" value="Valider" />
					</form>');
			}
		}
	}
else
	{
	print('Vous êtes trop saoul pour pouvoir poster une annonce.');
	}


mysql_close($db);

?>

			</div>
		</div>
	</div>
</div>
<?php if($chat=="oui") 	{ if ((date("H")>=21) || (date("H")<=7)) { include("inc_bas_n_c.php"); } else { include("inc_bas_j_c.php"); } } else {	if ((date("H")>=21) || (date("H")<=7)) { include("inc_bas_n.php"); } else { include("inc_bas_j.php"); }	} ?>
<?php include("inc_bas_de_page.php"); ?>
