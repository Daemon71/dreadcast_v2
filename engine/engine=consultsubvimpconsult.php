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
			Services
		</div>
		<b class="module4ie"><a href="engine=consultsubvimp.php" class="module4"><img src="im_objets/icon_retour.gif" alt="Retour" />Retour</a></b>
</p>
	</div>
</div>
<div id="centre">
<p>

<?php 

$db = mysql_connect('localhost', 'CENSURE', 'CENSURE') or die("Erreur de connexion au serveur des messages.");
mysql_select_db('CENSURE',$db) or die("Erreur de connexion a la base de donnees des messages.");

$sql = 'SELECT type,entreprise,points FROM principal_tbl WHERE id= "'.$_SESSION['id'].'"' ;
$req = mysql_query($sql);
$_SESSION['poste'] = mysql_result($req,0,type); 
$_SESSION['entreprise'] = mysql_result($req,0,entreprise); 
$_SESSION['points'] = mysql_result($req,0,points); 

$sql = 'SELECT bdd FROM `e_'.str_replace(" ","_",''.$_SESSION['entreprise'].'').'_tbl` WHERE poste= "'.$_SESSION['poste'].'"' ;
$req = mysql_query($sql);
$bdd = mysql_result($req,0,bdd); 

$sql = 'SELECT type,num,rue FROM entreprises_tbl WHERE nom= "'.$_SESSION['entreprise'].'"' ;
$req = mysql_query($sql);
$type = mysql_result($req,0,type); 
$rueent = mysql_result($req,0,rue); 
$nument = mysql_result($req,0,num); 

if($bdd=="") { print('<meta http-equiv="refresh" content="0 ; url=http://v2.dreadcast.net/ingame/engine.php"> '); exit(); } if(($type!="vente de services") && ($type!="banque") && ($type!="DOI") && ($type!="conseil") && ($type!="chambre") && ($type!="prison") && ($type!="police") && ($type!="di2rco")) { print('<meta http-equiv="refresh" content="0 ; url=http://v2.dreadcast.net/ingame/engine.php"> '); exit(); }

if((ucwords($_SESSION['rue'])!=ucwords($rueent)) || ($_SESSION['num']!=$nument))
	{
	print('<p align="center"><strong>Vous devez &ecirc;tre sur place pour pouvoir acc&eacute;der &agrave; cette rubrique.</strong></p>'); 
	$l = 1;
	}

if(($type=="DOI") && ($l!=1))
	{
	print('<p align="center">Voici les informations concernant ');
	$sql = 'SELECT nom,budget FROM entreprises_tbl WHERE type= "'.$_SERVER['QUERY_STRING'].'"' ;
	$req = mysql_query($sql);
	$nome = mysql_result($req,0,nom);
	$budgete = mysql_result($req,0,budget);
	print('l\'organisation <strong>'.$nome.'</strong> :</p>');
	$sql = 'SELECT poste FROM `e_'.str_replace(" ","_",''.$nome.'').'_tbl` WHERE type= "chef"' ;
	$req = mysql_query($sql);
	$postedir = mysql_result($req,0,poste);
	$sql = 'SELECT id FROM principal_tbl WHERE type= "'.$postedir.'"' ;
	$req = mysql_query($sql);
	$res = mysql_num_rows($req);
	if($res>0)
		{
		$iddir = mysql_result($req,0,id);
		$sql = 'SELECT pseudo FROM principal_tbl WHERE id= "'.$iddir.'"' ;
		$req = mysql_query($sql);
		$directeur = mysql_result($req,0,pseudo);
		print('<p align="center"><strong>Directeur Impérial :</strong> <a href="engine=contacter.php?cible='.$directeur.'">'.$directeur.'</a></p>');
		}
	else
		{
		print('<p align="center"><strong>Directeur Impérial :</strong> Aucun</p>');
		}
	print('<p align="center"><strong>Budget actuel :</strong> '.$budgete.' Crédits</p>');
	$sql = 'SELECT * FROM `e_'.str_replace(" ","_",''.$nome.'').'_tbl` WHERE type!= "chef"' ;
	$req = mysql_query($sql);
	$res = mysql_num_rows($req);
	print('<table width="70%"  border="0" align="center"><tr><td><div align="center"><strong>Employés :</strong></td><td><div align="center">');
	$couttot = 0;
	$nbre = 0;
	for($o=0;$o!=$res;$o++)
		{
		if(mysql_result($req,$o,nbreactuel)!=0)	
			{
			$nbre = $nbre + 1;
			print('<i>'.mysql_result($req,$o,poste).' :</i> '.mysql_result($req,$o,nbreactuel).'<br />');
			$couttot = $couttot +  ( mysql_result($req,$o,salaire) * mysql_result($req,$o,nbreactuel) );
			}
		}
	if($nbre==0)
		{
		print('<i>Aucun</i>');
		}
	print('</div></td></tr></table>');
	print('<p align="center"><strong>Coût nominal de l\'Organisation :</strong> '.$couttot.' Crédits / Jour</p>');
	}


mysql_close($db);

?>

</p>
</div>
<?php if($chat=="oui") 	{ if ((date("H")>=21) || (date("H")<=7)) { include("inc_bas_n_c.php"); } else { include("inc_bas_j_c.php"); } } else {	if ((date("H")>=21) || (date("H")<=7)) { include("inc_bas_n.php"); } else { include("inc_bas_j.php"); }	} ?>
<?php include("inc_bas_de_page.php"); ?>
