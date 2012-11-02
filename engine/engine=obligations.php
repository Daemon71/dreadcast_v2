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

$sql = 'SELECT id,rue,num,action,alim,sante,fatigue FROM principal_tbl WHERE pseudo= "'.$_SESSION['pseudo'].'"' ;
$req = mysql_query($sql);
$_SESSION['id'] = mysql_result($req,0,id);
$_SESSION['rue'] = mysql_result($req,0,rue);
$_SESSION['num'] = mysql_result($req,0,num);
$_SESSION['action'] = mysql_result($req,0,action);
$_SESSION['alim'] = mysql_result($req,0,alim);
$_SESSION['sante'] = mysql_result($req,0,sante);
$_SESSION['fatigue'] = mysql_result($req,0,fatigue);
$_SESSION['lieu'] = strtolower($_SESSION['rue']);
$_SESSION['lieu'] = ucwords($_SESSION['lieu']);

$sql = 'SELECT type,entreprise,points,nouveau FROM principal_tbl WHERE id= "'.$_SESSION['id'].'"' ;
$req = mysql_query($sql);
$_SESSION['poste'] = mysql_result($req,0,type); 
$_SESSION['entreprise'] = mysql_result($req,0,entreprise); 
$_SESSION['points'] = mysql_result($req,0,points); 
$_SESSION['nouveau'] = mysql_result($req,0,nouveau); 

$sql = 'SELECT mintrav,sinon,bonus FROM `e_'.str_replace(" ","_",''.$_SESSION['entreprise'].'').'_tbl` WHERE poste= "'.$_SESSION['poste'].'"' ;
$req = mysql_query($sql);
$_SESSION['mintrav'] = mysql_result($req,0,mintrav); 
$_SESSION['sinon'] = mysql_result($req,0,sinon); 
$_SESSION['bonus'] = mysql_result($req,0,bonus); 

$sql = 'SELECT num,rue FROM entreprises_tbl WHERE nom= "'.$_SESSION['entreprise'].'"' ;
$req = mysql_query($sql);
$_SESSION['nume'] = mysql_result($req,0,num); 
$_SESSION['ruee'] = mysql_result($req,0,rue); 

mysql_close($db);
?>
<?php if ((date("H")>=21) || (date("H")<=7)) { include("inc_haut_n.php"); } else { include("inc_haut_j.php");} ?>

<div id="haut">
	<?php include("inc_entete.php"); ?>
	<div class="haut_aligngauche">
		<p>
		<div class="titrepage">
			Gestion du temps de travail
		</div>
		<b class="module4ie"><a href="engine=panneau.php" class="module4"><img src="im_objets/icon_retour.gif" alt="Retour" />Retour</a></b>
</p>
	</div>
</div>
<div id="centre">
<p>

<?php 
if($_SESSION['mintrav']!=0)
{
print('<p align="center">Votre responsable vous impose : </p>');
print('<p align="center"><strong>D\'&ecirc;tre au travail <i>'.$_SESSION['mintrav'].'</i> heures par jour.</strong>');

if($_SESSION['nouveau']=="oui")
	{
	print ('<br><br><i>C\'est aujourd\'hui votre premier jour de travail, vous serez donc payé proportionnellement au travail que vous aurez accompli.</i>'); 
	}
elseif($_SESSION['sinon']=="lj")
{
print('<br><strong>Sinon : </strong>');
print ('<i>Vous &ecirc;tes licenci&eacute; &agrave; la fin de la journ&eacute;e.</i>'); 
}
elseif($_SESSION['sinon']=="ma")
{
print('<br><strong>Sinon : </strong>');
print ('<i>Un message automatique est envoy&eacute; au r&eacute;sponsable.</i>'); 
}
elseif($_SESSION['sinon']=="pp")
{
print('<br><strong>Sinon : </strong>');
print ('<i>Vous n\'&ecirc;tes pas pay&eacute;.</i>'); 
}

print('</p><p align="center"><strong>Les heures sup sont pay&eacute;es  : </strong>');
print ('<i>'.$_SESSION['bonus'].' Cr&eacute;dits / Heure </i>'); 
print('</p><p align="center">&nbsp;</p>');
}
else
{
print('<p align="center"><strong>Votre responsable ne vous impose pas de conditions particuli&egrave;res. </strong></p>');
}

if(($_SESSION['mintrav']>0) || ($_SESSION['points']>0))
	{
	if($_SESSION['points']>0)
		{
		print('<p align="center"><strong>Vous avez travaill&eacute; '.$_SESSION['points'].' heure(s) aujourd\'hui.</strong></p>'); 
		}
	else
		{
		print('<p align="center"><strong>Vous n\'&ecirc;tes pas all&eacute; au travail aujourd\'hui.</strong></p>'); 
		}
	}

?>
		</span></p>
<?php

if($_SESSION['action']!="travail")
	{
	print('<p align="center"><a href="engine=go.php?rue='.$_SESSION['ruee'].'&num='.$_SESSION['nume'].'">Vous rendre à votre travail</a></p>');
	}
else
	{
	print('<p align="center"><i>Vous êtes actuellement en train de travailler</i></p>');
	}

?>

</p>
</div>
<?php if($chat=="oui") 	{ if ((date("H")>=21) || (date("H")<=7)) { include("inc_bas_n_c.php"); } else { include("inc_bas_j_c.php"); } } else {	if ((date("H")>=21) || (date("H")<=7)) { include("inc_bas_n.php"); } else { include("inc_bas_j.php"); }	} ?>
<?php include("inc_bas_de_page.php"); ?>
