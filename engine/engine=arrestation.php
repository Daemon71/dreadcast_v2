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

$sql = 'SELECT id,rue,num,action,alim,sante,fatigue,discretion,type FROM principal_tbl WHERE pseudo= "'.$_SESSION['pseudo'].'"' ;
$req = mysql_query($sql);
$_SESSION['id'] = mysql_result($req,0,id);
$_SESSION['rue'] = mysql_result($req,0,rue);
$_SESSION['num'] = mysql_result($req,0,num);
$_SESSION['action'] = mysql_result($req,0,action);
$_SESSION['alim'] = mysql_result($req,0,alim);
$_SESSION['sante'] = mysql_result($req,0,sante);
$_SESSION['fatigue'] = mysql_result($req,0,fatigue);
$_SESSION['discretion'] = mysql_result($req,0,discretion);
$type = mysql_result($req,0,type);
$_SESSION['lieu'] = strtolower($_SESSION['rue']);
$_SESSION['lieu'] = ucwords($_SESSION['lieu']);

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

if($_SESSION['code']!=$codounet)
	{
	print('<meta http-equiv="refresh" content="0 ; url=http://v2.dreadcast.net/ingame/engine.php"> ');
	exit();
	}

if($_SERVER['QUERY_STRING']!="")
	{
	$_SESSION['cible'] = str_replace("%20"," ",''.$_SERVER['QUERY_STRING'].'');
	}

$sql = 'SELECT id,rue,num,action,sante,fatigue,observation,case1,case2,case3,case4,case5,case6,Police FROM principal_tbl WHERE pseudo= "'.$_SESSION['cible'].'"' ;
$req = mysql_query($sql);
$idc = mysql_result($req,0,id);
$_SESSION['lieuc'] = mysql_result($req,0,rue);
$_SESSION['numc'] = mysql_result($req,0,num);
$_SESSION['santec'] = mysql_result($req,0,sante);
$_SESSION['fatiguec'] = mysql_result($req,0,fatigue);
$observationc = mysql_result($req,0,observation);
$case1c = mysql_result($req,0,case1);
$case2c = mysql_result($req,0,case2);
$case3c = mysql_result($req,0,case3);
$case4c = mysql_result($req,0,case4);
$case5c = mysql_result($req,0,case5);
$case6c = mysql_result($req,0,case6);
$policec = mysql_result($req,0,Police);

if($_SESSION['r'.$idc.'']!=1)
	{
	print('<meta http-equiv="refresh" content="0 ; url=http://v2.dreadcast.net/ingame/engine.php"> ');
	exit();
	}

mysql_close($db);

?>
<?php if ((date("H")>=21) || (date("H")<=7)) { include("inc_haut_n.php"); } else { include("inc_haut_j.php");} ?>

<div id="haut">
	<?php include("inc_entete.php"); ?>
	<div class="haut_aligngauche">
		<p>
		<div class="titrepage">
			Arrestation
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

$sql = 'SELECT entreprise FROM principal_tbl WHERE id= "'.$_SESSION['id'].'"' ;
$req = mysql_query($sql);
$_SESSION['entreprise'] = mysql_result($req,0,entreprise);

$sql = 'SELECT bdd FROM `e_'.str_replace(" ","_",''.$_SESSION['entreprise'].'').'_tbl` WHERE poste="'.$type.'"' ;
$req = mysql_query($sql);
$bdd = mysql_result($req,0,bdd);

if(($_SESSION['entreprise']!="Police" && $_SESSION['entreprise']!="DI2RCO") || ($bdd==""))
	{
	print('<p align="center"><strong>Il est impossible d\'arreter <i>'.$_SESSION['cible'].'</i> car vous n\'&ecirc;tes pas dans les registres des autorités.</strong></p>');
	$l = 1;
	}

$sql = 'SELECT case1,case2,case3,case4,case5,case6 FROM principal_tbl WHERE id= "'.$_SESSION['id'].'"' ;
$req = mysql_query($sql);
$_SESSION['case1'] = mysql_result($req,0,case1);
$_SESSION['case2'] = mysql_result($req,0,case2);
$_SESSION['case3'] = mysql_result($req,0,case3);
$_SESSION['case4'] = mysql_result($req,0,case4);
$_SESSION['case5'] = mysql_result($req,0,case5);
$_SESSION['case6'] = mysql_result($req,0,case6);

if($_SESSION['case1']!="Menottes" && $_SESSION['case2']!="Menottes" && $_SESSION['case3']!="Menottes" && $_SESSION['case4']!="Menottes" && $_SESSION['case5']!="Menottes" && $_SESSION['case6']!="Menottes")
	{
	print('<p align="center"><strong>Il est impossible d\'arreter <i>'.$_SESSION['cible'].'</i> car vous n\'avez pas de menottes.</strong></p>');
	$l = 1;
	}
elseif(!estVisible($_SESSION['cible'],25))
	{
	print('<p align="center"><strong>Il est impossible d\'arreter <i>'.$_SESSION['cible'].'</i> car il n\'est pas au m&ecirc;me endroit que vous.</strong></p>');
	$l = 1;
	}
elseif(($case1c=="Laissez-passer") || ($case2c=="Laissez-passer") || ($case3c=="Laissez-passer") || ($case4c=="Laissez-passer") || ($case5c=="Laissez-passer") || ($case6c=="Laissez-passer"))
	{
	print('<p align="center">Il est impossible d\'arreter <strong>'.$_SESSION['cible'].'</strong> car il poss&egrave;de un <i>Laissez passer Imp&eacute;rial</i>.<br>Ce dernier le place hors d\'atteinte des services de Police.</p>');
	$l = 1;
	}
elseif($l!=1)
	{
	if($_POST['raison']=="")
		{
		mysql_close($db);
		print('<meta http-equiv="refresh" content="0 ; url=http://v2.dreadcast.net/ingame/engine=raisonemp.php"> ');
		exit();
		}

	$savoir = $_SESSION['discretion'] - $observationc + rand(-20,20);
	if($savoir<=0)
		{
		if($_SESSION['armec']!="Aucune")
			{
			$sqls = 'INSERT INTO messages_tbl(id,auteur,cible,message,objet,moment,nouveau) VALUES("","Dreadcast","'.$_SESSION['cible'].'","<strong><br /><br /><br />Un agent de police vous met en &eacute;tat d\'arrestation !</strong><br>En regardant plus pr&egrave;s, vous le reconnaissez. Il s\'agit de <i>'.$_SESSION['pseudo'].'</i>.<br><br>Vous vous laissez faire. Votre arme est détruite sous vos yeux.<br><br><strong>Motif :</strong> '.str_replace("\n","<br />",''.htmlentities($_POST["raison"],ENT_QUOTES).'').'","Au nom de la loi !","'.time().'","oui")';
			$reqs = mysql_query($sqls);
			$sqls = 'UPDATE principal_tbl SET arme= "Aucune" WHERE id= "'.$idc.'"';
			$reqs = mysql_query($sqls);
			}
		else
			{
			$sqls = 'INSERT INTO messages_tbl(id,auteur,cible,message,objet,moment,nouveau) VALUES("","Dreadcast","'.$_SESSION['cible'].'","<strong><br /><br /><br />Un agent de police vous met en &eacute;tat d\'arrestation !</strong><br>En regardant plus pr&egrave;s, vous le reconnaissez. Il s\'agit de <i>'.$_SESSION['pseudo'].'</i>.<br><br>Vous vous laissez faire.<br><br><strong>Motif :</strong> '.str_replace("\n","<br />",''.htmlentities($_POST["raison"],ENT_QUOTES).'').'","Au nom de la loi !","'.time().'","oui")';
			$reqs = mysql_query($sqls);
			}
		}
	else
		{
		if($_SESSION['armec']!="Aucune")
			{
			$sqls = 'INSERT INTO messages_tbl(id,auteur,cible,message,objet,moment,nouveau) VALUES("","Dreadcast","'.$_SESSION['cible'].'","<strong><br /><br /><br />Un agent de police vous met en &eacute;tat d\'arrestation !</strong><br><br>Vous vous laissez faire. Votre arme est détruite sous vos yeux.<br><br><strong>Motif :</strong> '.str_replace("\n","<br />",''.htmlentities($_POST["raison"],ENT_QUOTES).'').'","Au nom de la loi !","'.time().'","oui")';
			$reqs = mysql_query($sqls);
			$sqls = 'UPDATE principal_tbl SET arme= "Aucune" WHERE id= "'.$idc.'"';
			$reqs = mysql_query($sqls);
			}
		else
			{
			$sqls = 'INSERT INTO messages_tbl(id,auteur,cible,message,objet,moment,nouveau) VALUES("","Dreadcast","'.$_SESSION['cible'].'","<strong><br /><br /><br />Un agent de police vous met en &eacute;tat d\'arrestation !</strong><br><br>Vous vous laissez faire.<br><br><strong>Motif :</strong> '.str_replace("\n","<br />",''.htmlentities($_POST["raison"],ENT_QUOTES).'').'","Au nom de la loi !","'.time().'","oui")';
			$reqs = mysql_query($sqls);
			}
		}
	if($policec>=55)
		{
		enregistre($_SESSION['pseudo'],'arrestation','+1');
		enregistre($_SESSION['cible'],'arrêté','+1');
		}
	$sql = 'UPDATE principal_tbl SET Police= "0" WHERE id= "'.$idc.'"';
	$req = mysql_query($sql);
	$sql = 'DELETE FROM crimes_tbl WHERE pseudo= "'.$_SESSION['cible'].'"';
	$req = mysql_query($sql);
	print('Vous placez '.$_SESSION['cible'].' en état d\'arrestation.<br />Il se laisse faire.</p>');
	$sql = 'SELECT num,rue FROM entreprises_tbl WHERE type= "prison"' ;
	$req = mysql_query($sql);
	$ruep = mysql_result($req,0,rue);
	$nump = mysql_result($req,0,num);
	$sql = 'UPDATE principal_tbl SET sante=sante_max ,  rue= "'.ucwords($ruep).'" , num= "'.$nump.'" , action= "prison", alim= "'.$_POST['temps'].'" WHERE id= "'.$idc.'"';
	$req = mysql_query($sql);
	$sql = 'SELECT entreprise FROM principal_tbl WHERE id= "'.$idc.'"';
	$req = mysql_query($sql);
	if(mysql_result($req,0,entreprise) != "Aucune") verification_ouverture_entreprise(mysql_result($req,0,entreprise));
	if($_SESSION['entreprise']=="DI2RCO") $flic = "Agent DI2RCO";
	else $flic = $_SESSION['pseudo'];
	$sql = 'INSERT INTO casiers_tbl(id,pseudo,datea,raison,policier) VALUES("","'.$_SESSION['cible'].'","'.time().'","'.str_replace("\n","<br />",''.htmlentities($_POST["raison"],ENT_QUOTES).'').'","'.$flic.'") ';
	$req = mysql_query($sql);
	print('<p align="center"><b><i>L\'arrestation se d&eacute;roule sans probl&egrave;me !</i></b><br><br>Il vient d\'&ecirc;tre plac&eacute; en prison pour '.$_POST['temps'].' jours.');
	}

mysql_close($db);
?>

</p>
</div>
<?php if($chat=="oui") 	{ if ((date("H")>=21) || (date("H")<=7)) { include("inc_bas_n_c.php"); } else { include("inc_bas_j_c.php"); } } else {	if ((date("H")>=21) || (date("H")<=7)) { include("inc_bas_n.php"); } else { include("inc_bas_j.php"); }	} ?>
<?php include("inc_bas_de_page.php"); ?>
