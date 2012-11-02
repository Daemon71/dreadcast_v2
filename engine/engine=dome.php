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
			Dôme
		</div>
		<b class="module4ie"><a href="engine.php" class="module4"><img src="im_objets/icon_retour.gif" alt="Retour" />Retour</a></b>
</p>
	</div>
</div>
<div id="centre" style="padding:0 20px;width:457px;">

<?php	

$db = mysql_connect('localhost', 'CENSURE', 'CENSURE') or die("Erreur de connexion au serveur.");
mysql_select_db('CENSURE',$db) or die("Erreur de connexion a la base de donnees.");

$sql = 'SELECT num,rue FROM lieux_speciaux_tbl WHERE type="decapaciteur"';
$req = mysql_query($sql);
$res = mysql_num_rows($req);
if($res)
	{
	if($_SESSION['num'] != mysql_result($req,0,num) || strtolower($_SESSION['lieu']) != strtolower(mysql_result($req,0,rue)))
		{
		print('<meta http-equiv="refresh" content="0 ; url=http://v2.dreadcast.net/ingame/engine.php"> ');
		exit();
		}
	}

if(empty($_POST['v']))
	{
	print('<strong>Bienvenue dans le Dôme</strong><br /><br />
	Notre équipe va prendre soin de vous. Dès que vous aurez validé l\'opération, nos experts vous placeront en stase où vous seront effectuées les transformations suivantes :<br /><br />
	<span style="display:block;margin-left:20px;text-align:left;">
		&bull; Remise à 0 de l\'expérience, malgré l\'argent investi en Crédivore<br />
		&bull; Perte des informations d\'expérience<br />
		&bull; Perte des talents<br />
		&bull; Perte des accomplissements<br />
		&bull; Remise à 100 du maximum de chaque compétence, santé et forme comprises, et malgré toute substance chimique restructurante ingurgitée (Meds H)
	</span><br />
	<strong>Attention</strong> : aucun retour en arrière ne sera possible.<br /><br />
	<form action="#" method="post">
		<input type="submit" name="v" value="Se mettre en stase" />
	</form>
	');
	}
else {

	print('<img src="im_objets/loader.gif" alt="Veuillez patienter..." /><br />
	Modifications en cours...');
	$retour = reset_personnage();
	
	if($retour == "")
		{
		print('<meta http-equiv="refresh" content="0 ; url=http://v2.dreadcast.net/ingame/engine.php"> ');
		exit();
		}
	else print('Une erreur s\'est produite. Veuillez contacter un administrateur en précisant "Reset '.$retour.'"');
}


mysql_close($db);

function reset_personnage($pseudo=""){
	$pseudo = ($pseudo=="")?$_SESSION['pseudo']:$pseudo;
	
	$competences = liste_competences();
	
	for($i=0;$i<count($competences);$i++) {
	   if ($competences[$i] == "fidelite")
	       $competences[$i] .= " = fidelite";
	   $competences[$i] .= '_max = 100';
	}
	
	$sql = 'UPDATE principal_tbl SET sante = 100, sante_max = 100, fatigue = 100, fatigue_max = 100, '.join(", ", $competences).', total = 0 WHERE pseudo = "'.$pseudo.'"';
	if(!mysql_query($sql)) return 'ERREUR 1';
	echo $sql;
	$competences = liste_competences(array('fidelite'));
	
	foreach($competences as $competence) {
	   $sql = 'UPDATE principal_tbl SET '.$competence.' = 100 WHERE pseudo = "'.$pseudo.'" AND '.$competence.' > 100';
	   mysql_query($sql);
	}
	
	$sql = 'DELETE FROM enregistreur_tbl WHERE pseudo = "'.$pseudo.'"';
	if(!mysql_query($sql)) return 'ERREUR 2';
	
	$sql = 'DELETE FROM titres_tbl WHERE pseudo = "'.$pseudo.'" AND titre != "Citoyen d\'Honneur"';
	if(!mysql_query($sql)) return 'ERREUR 3';
	
	return "";
}

?>
</div>
<?php if($chat=="oui") 	{ if ((date("H")>=21) || (date("H")<=7)) { include("inc_bas_n_c.php"); } else { include("inc_bas_j_c.php"); } } else {	if ((date("H")>=21) || (date("H")<=7)) { include("inc_bas_n.php"); } else { include("inc_bas_j.php"); }	} ?>
<?php include("inc_bas_de_page.php"); ?>
