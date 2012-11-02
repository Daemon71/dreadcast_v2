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
$cible = str_replace("%20"," ",''.$_SERVER['QUERY_STRING'].'');

?>
<?php if ((date("H")>=21) || (date("H")<=7)) { include("inc_haut_n.php"); } else { include("inc_haut_j.php");} ?>

<div id="haut">
	<?php include("inc_entete.php"); ?>
	<div class="haut_aligngauche">
		<p>
		<div class="titrepage">
			Inspecter
		</div>
		<b class="module4ie"><a href="engine=cible.php?<?php print($cible); ?>" class="module4"><img src="im_objets/icon_retour.gif" alt="Retour" />Retour</a></b>
</p>
	</div>
</div>
<div id="centre">
<p>

<?php	

$db = mysql_connect('localhost', 'CENSURE', 'CENSURE') or die("Erreur de connexion au serveur.");
mysql_select_db('CENSURE',$db) or die("Erreur de connexion a la base de donnees.");

if($_SESSION['num'] <= 0) {
	$num = 0;
	$lieu = "Rue";
}
else {
	$num = $_SESSION['num'];
	$lieu = $_SESSION['lieu'];
}

$sql = 'SELECT code FROM lieu_tbl WHERE rue= "'.$lieu.'" AND num= "'.$num.'"' ;
$req = mysql_query($sql);
$codounet = mysql_result($req,0,code);

if($_SESSION['code']!=$codounet) {
	mysql_close($db);
	print('<meta http-equiv="refresh" content="0 ; url=http://v2.dreadcast.net/ingame/engine.php"> ');
	exit();
}

$sql = 'SELECT id,rue,num FROM principal_tbl WHERE pseudo= "'.$_SESSION['pseudo'].'"' ;
$req = mysql_query($sql);
$_SESSION['id'] = mysql_result($req,0,id);
$_SESSION['lieu'] = mysql_result($req,0,rue);
$_SESSION['num'] = mysql_result($req,0,num);

$sql = 'SELECT case1,case2,case3,case4,case5,case6,entreprise FROM principal_tbl WHERE id= "'.$_SESSION['id'].'"' ;
$req = mysql_query($sql);
$_SESSION['case1'] = mysql_result($req,0,case1);
$_SESSION['case2'] = mysql_result($req,0,case2);
$_SESSION['case3'] = mysql_result($req,0,case3);
$_SESSION['case4'] = mysql_result($req,0,case4);
$_SESSION['case5'] = mysql_result($req,0,case5);
$_SESSION['case6'] = mysql_result($req,0,case6);
$_SESSION['entreprise'] = mysql_result($req,0,entreprise);

if(est_dans_inventaire("Menottes")) { }
else {
	print('<p align="center"><strong>Il est impossible d\'insp&eacute;cter <i>'.$cible.'</i> sans Menottes.</strong></p>');
	$l = 1;
}

$sql = 'SELECT id,rue,num FROM principal_tbl WHERE pseudo= "'.$cible.'"' ;
$req = mysql_query($sql);
$_SESSION['idc'] = mysql_result($req,0,id);
$_SESSION['lieuc'] = mysql_result($req,0,rue);
$_SESSION['numc'] = mysql_result($req,0,num);

$sql = 'SELECT Police,DI2RCO FROM principal_tbl WHERE id= "'.$_SESSION['idc'].'"' ;
$req = mysql_query($sql);
$_SESSION['policec'] = mysql_result($req,0,Police);
$_SESSION['di2rcoc'] = mysql_result($req,0,DI2RCO);

$sql = 'SELECT entreprise, type FROM principal_tbl WHERE id= "'.$_SESSION['idc'].'"' ;
$req = mysql_query($sql);
$trav = mysql_result($req,0,entreprise);
$nomposte = mysql_result($req,0,type);


if ($trav != "Aucune") {
	$sql = 'SELECT type FROM e_'.str_replace(" ", "_", $trav).'_tbl WHERE poste= "'.$nomposte.'"' ;
	$req = mysql_query($sql);
	$typeposte = mysql_result($req,0,type);
}

$sql = 'SELECT * FROM principal_tbl WHERE id= "'.$_SESSION['idc'].'"' ;
$req = mysql_query($sql);
$_SESSION['armec'] = mysql_result($req,0,arme);
$_SESSION['case1c'] = mysql_result($req,0,case1);
$_SESSION['case2c'] = mysql_result($req,0,case2);
$_SESSION['case3c'] = mysql_result($req,0,case3);
$_SESSION['case4c'] = mysql_result($req,0,case4);
$_SESSION['case5c'] = mysql_result($req,0,case5);
$_SESSION['case6c'] = mysql_result($req,0,case6);
$sexec = mysql_result($req,0,sexe);
$agec = mysql_result($req,0,age);
$taillec = mysql_result($req,0,taille);
$resistancec = mysql_result($req,0,resistance);
$alcoolc = mysql_result($req,0,alcool);


if(!estVisible($cible,25))
	{
	print('<p align="center"><strong>Il est impossible d\'insp&eacute;cter <i>'.$cible.'</i> car il n\'est pas au m&ecirc;me endroit que vous.</strong></p>');
	$l = 1;
	}
elseif(($_SESSION['entreprise']!="Police") && ($_SESSION['entreprise']!="DI2RCO"))
	{
	print('<p align="center"><strong>Il est impossible d\'insp&eacute;cter <i>'.$cible.'</i> car vous n\'appartenez pas aux forces de l\'ordre.</strong></p>');
	$l = 1;
	}

if($l!=1)
	{
	print('<i>Vous inspectez '.$cible.'</i>.</p><p align="center">'); 
			
	if(est_dans_inventaire("Laissez-passer",$cible))
		{
		print("<i>Cet individu poss&egrave;de un Laissez passer Imp&eacute;rial.</i>");
		}
	elseif(($trav=="Police") || ($trav=="DI2RCO"))
		{
		print("<i>Cet individu est un agent Impérial.</i>");
		}
	elseif(in_array(strtolower($trav), liste_OI()) && ($typeposte == 'directeur' || $typeposte == 'chef'))
		{
		print("<i>Cet individu est un Officier Impérial.</i>");
		}
	elseif(!est_dans_inventaire("Carte", $cible) && !est_dans_inventaire("Passe DI2RCO", $cible))
		{
		print("<strong>Cet individu ne porte pas sa Carte d'identit&eacute; !</strong>");
		}
	elseif(est_dans_inventaire("Carte", $cible) || est_dans_inventaire("Passe DI2RCO", $cible))
		{
		if(($_SESSION['policec']>=120) || ($_SESSION['di2rcoc']>=50))
			{
			print("<strong>Cet individu est arm&eacute; et dangereux, il est activement recherch&eacute; par les forces de Police !</strong>");
			}
		elseif($_SESSION['policec']>=55)
			{
			print("<strong>Cet individu est recherch&eacute; par les forces de Police !</strong>");
			}
		else
			{
			print("La carte d'identit&eacute; de cet individu est en regle.");
			}
		}
	
	print('</p><p align="center">');
	
	if(est_dans_inventaire("Alcootest"))
		{
		print('Votre alcootest indique que cet individu est à '.substr(alcootest($cible, $alcoolc, $sexec, $agec, $taillec, $resistancec),0,4).'g/l d\'alcool.');
		}
	}
print('</p>');

mysql_close($db);

?>

</p>
</div>
<?php if($chat=="oui") 	{ if ((date("H")>=21) || (date("H")<=7)) { include("inc_bas_n_c.php"); } else { include("inc_bas_j_c.php"); } } else {	if ((date("H")>=21) || (date("H")<=7)) { include("inc_bas_n.php"); } else { include("inc_bas_j.php"); }	} ?>
<?php include("inc_bas_de_page.php"); ?>
