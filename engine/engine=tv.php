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

$sql = 'SELECT nom,type FROM entreprises_tbl WHERE rue= "'.$_SESSION['lieu'].'" AND num= "'.$_SESSION['num'].'"' ;
$req = mysql_query($sql);
$res = mysql_num_rows($req);

if($res == 0 || mysql_result($req,0,type) != "dcn")
	{
	print('<meta http-equiv="refresh" content="0 ; url=http://v2.dreadcast.net/ingame/engine.php"> ');
	exit();
	}
	
$type = mysql_result($req,0,type);
$noment = mysql_result($req,0,nom);

mysql_close($db);

?>
<?php if ((date("H")>=21) || (date("H")<=7)) { include("inc_haut_n.php"); } else { include("inc_haut_j.php");} ?>

<div id="haut">
	<?php include("inc_entete.php"); ?>
	<div class="haut_aligngauche">
		<p>
		<div class="titrepage">
			DC TV
		</div>
		<b class="module4ie">
			<?php
			if($_GET['lieu'] == "") print('<a href="engine.php" class="module4"><img src="im_objets/icon_retour.gif" alt="Retour" />Retour</a>');
			elseif($_GET['lieu'] == "programmation" OR $_GET['lieu'] == "abonnement") print('<a href="engine=tv.php" class="module4"><img src="im_objets/icon_retour.gif" alt="Retour" />Retour</a>');
			?>
		</b>
		</p>
	</div>
</div>
<div id="centre_imperium">
	
	<?php
	$db = mysql_connect('localhost', 'CENSURE', 'CENSURE') or die("Erreur de connexion au serveur des messages.");
	mysql_select_db('CENSURE',$db) or die("Erreur de connexion a la base de donnees des messages.");
	
	if($_GET['lieu'] == "")
		print('<p id="location">Locaux de la DC TV</p>
		
		<br /><br />
		<p id="textelse">Bienvenue dans les locaux de la DreadCast TeleVision.<br /><br />
		
		Vous pouvez accéder à la programmation de la semaine.<br /><br />
		<a href="engine=tv.php?lieu=programmation">Regarder la programmation de la semaine</a><br /><br /><br />
		
		Vous pouvez également vous abonner à la DC TV.<br /><br />
		<a href="engine=tv.php?lieu=guichet">Se rendre au service des abonnements</a><br /><br />
		</p>');
	elseif($_GET['lieu'] == "programmation" && $_GET['action'] == "")
		{
		print('<p id="location">Programme de la semaine</p>
		
		<br /><br />
		<p id="textelse">
		
		<div id="programme_publique">'.affiche_DCTV_programme(time()).'</div>
		
		</p>');
		}
	elseif($_GET['lieu'] == "guichet" && $_GET['action'] == "")
		{
		print('<p id="location">Guichet de la DCTV</p>
		
		<br /><br /><br />
		<p id="textelse">');
		
		$sql = 'SELECT nombre FROM DCN_abonnes_tbl WHERE abonne = "'.$_SESSION['pseudo'].'" AND medium = "DCTV"';
		$req = mysql_query($sql);
		
		if(mysql_num_rows($req) == 0)
			{
			$sql = 'SELECT abonne,nombre FROM DCN_abonnes_tbl WHERE abonne LIKE "DCTV %"';
			$req = mysql_query($sql);
			
			print('Un abonnement à la DCTV coûte <strong>'.mysql_result($req,0,nombre).'</strong> crédits pour <strong>'.str_replace("DCTV ","",mysql_result($req,0,abonne)).'</strong> ans.<br />
			Il nécessite également de posséder un AITL 2.0.<br /><br />
			<a href="engine=tv.php?lieu=guichet&action=abonner">Valider mon abonnement</a>');
			}
		else print('Vous êtes déjà abonné à la DCTV.<br />
		Votre abonnement se terminera dans <strong>'.mysql_result($req,0,nombre).'</strong> ans.<br />
			<a href="engine=tv.php">Retour</a>');
			
		print('</p>');
		}
	elseif($_GET['lieu'] == "guichet" && $_GET['action'] == "abonner")
		{
		print('<p id="location">Guichet de la DCTV</p>
		
		<br /><br /><br />
		<p id="textelse">');
		
		$sql = 'SELECT credits FROM principal_tbl WHERE pseudo="'.$_SESSION['pseudo'].'"';
		$req = mysql_query($sql);
		
		$_SESSION['credits'] = mysql_result($req,0,credits);
		
		if(!est_dans_inventaire("AITL 2.0") && statut($_SESSION['statut'])<2)
			print('Vous n\'avez pas d\'AITL 2.0 dans votre inventaire.<br />
			<a href="engine=tv.php?lieu=guichet">Retour</a>');
		else
			{
			$sql = 'SELECT abonne,nombre FROM DCN_abonnes_tbl WHERE abonne LIKE "DCTV %"';
			$req = mysql_query($sql);
			
			$nombre_abonnement = str_replace("DCTV ","",mysql_result($req,0,abonne));
			$prix_abonnement = mysql_result($req,0,nombre);
			
			if($_SESSION['credits'] >= $prix_abonnement)
				{
				$_SESSION['credits'] -= $prix_abonnement;
				
				$sql = 'UPDATE principal_tbl SET credits = "'.$_SESSION['credits'].'" WHERE pseudo="'.$_SESSION['pseudo'].'"';
				mysql_query($sql);
				
				$sql = 'INSERT INTO DCN_abonnes_tbl(id,abonne,nombre,medium) VALUES("","'.$_SESSION['pseudo'].'","'.$nombre_abonnement.'","DCTV")';
				mysql_query($sql);
				
				$sql = 'INSERT INTO achats_tbl (acheteur,vendeur,objet,prix,moment) VALUES("'.$_SESSION['pseudo'].'","DC Network","Abonnement DCTV","'.$prix_abonnement.'","'.time().'")';
				mysql_query($sql);
				
				$sql = 'SELECT budget,benefices FROM entreprises_tbl WHERE nom= "DC Network"' ;
				$req = mysql_query($sql);
				$budget = mysql_result($req,0,budget);
				$benef = mysql_result($req,0,benefices);
				$pvente = $prix_abonnement;
				$budget = $budget + $pvente;
				$benef = $benef + $pvente;
				$sql = 'UPDATE entreprises_tbl SET budget= "'.$budget.'" , benefices= "'.$benef.'" WHERE nom= "DC Network"' ;
				mysql_query($sql);
				
				print('<meta http-equiv="refresh" content="0 ; url=engine=tv.php?lieu=guichet"> ');
				exit();
				}
			else
				print('Vous n\'avez pas assez d\'argent sur vous.<br />
				<a href="engine=tv.php?lieu=guichet">Retour</a>');
			}
		
		print('</p>');
		}
		
	mysql_close($db);
	?>
	
</div>
<?php if($chat=="oui") 	{ if ((date("H")>=21) || (date("H")<=7)) { include("inc_bas_n_c.php"); } else { include("inc_bas_j_c.php"); } } else {	if ((date("H")>=21) || (date("H")<=7)) { include("inc_bas_n.php"); } else { include("inc_bas_j.php"); }	} ?>
<?php include("inc_bas_de_page.php"); ?>
