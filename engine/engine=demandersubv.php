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
			Demande de subvention
		</div>
		<b class="module4ie"><a href="engine=gestion.php" class="module4"><img src="im_objets/icon_retour.gif" alt="Retour" />Retour</a></b>
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

if($_SESSION['points']!=999) { print('<meta http-equiv="refresh" content="0 ; url=http://v2.dreadcast.net/ingame/engine.php"> '); exit(); }

$sql = 'SELECT id FROM financepridem_tbl WHERE entreprise= "'.$_SESSION['entreprise'].'"' ;
$req = mysql_query($sql);
$res = mysql_num_rows($req);

if($res==0)
	{
	print('<p align="center">Toutes les entreprises ont droit &agrave; une subvention par semaine pouvant aller jusqu\'&agrave; 1000 Cr&eacute;dits. Indiquez dans la zone de texte ci-dessous vos motivations. Ce texte sera examin&eacute; par des sp&eacute;cialistes et le vote aura lieu vendredi soir. <form name="allera" id="allera" method="post" action="engine=demandersubvterm.php"><div align="center"><p><textarea name="nmsg" cols="50" rows="7" id="nmsg"></textarea><br /><br /><input type="submit" name="Submit" value="Envoyer la demande" /></p></div></form>');
	}
else
	{
	$sql = 'SELECT vote FROM financepri_tbl WHERE entreprise= "'.$_SESSION['entreprise'].'"' ;
	$req = mysql_query($sql);
	$res = mysql_num_rows($req);
	print('Vous avez demandé une subvention Impériale.<br>Voici les détails du dossier envoyé à la Direction des Organisations Impériales :</p>');
	$vote = 0;
	for($f=0;$f!=$res;$f++)
		{
		$vote = $vote + mysql_result($req,$f,vote);
		}
	if($res>0)
		{
		$vote = ceil($vote / $res);
		}
	print('<hr><p align="center"><strong>Nombre de votes à l\'heure actuelle :</strong> '.$res.'</p><p align="center"><strong>Etat actuel de la subvention :</strong> '.$vote.' Crédits</p>');
	print('<hr><p align="center"><i>N\'oubliez pas que la Subvention Impériale qui vous sera attribuée est une moyenne de tous les votes.<br>Il s\'agit d\'une somme comprise entre 0 et 1000 Crédits.</i></p><p align="center"><i>Les votes seront clos vendredi soir.</i></p>');
	}


mysql_close($db);
?>


</p>
</div>
<?php if($chat=="oui") 	{ if ((date("H")>=21) || (date("H")<=7)) { include("inc_bas_n_c.php"); } else { include("inc_bas_j_c.php"); } } else {	if ((date("H")>=21) || (date("H")<=7)) { include("inc_bas_n.php"); } else { include("inc_bas_j.php"); }	} ?>
<?php include("inc_bas_de_page.php"); ?>
