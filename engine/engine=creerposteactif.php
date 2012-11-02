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

$sql = 'SELECT type,entreprise,points FROM principal_tbl WHERE id= "'.$_SESSION['id'].'"' ;
$req = mysql_query($sql);
$_SESSION['poste'] = mysql_result($req,0,type); 
$_SESSION['entreprise'] = mysql_result($req,0,entreprise); 
$_SESSION['points'] = mysql_result($req,0,points); 

$sql = 'SELECT type FROM entreprises_tbl WHERE nom= "'.$_SESSION['entreprise'].'"' ;
$req = mysql_query($sql);
$_SESSION['domaine'] =  mysql_result($req,0,type); 

if($_SESSION['points']!=999) { print('<meta http-equiv="refresh" content="0 ; url=http://v2.dreadcast.net/ingame/engine.php"> '); exit(); }

mysql_close($db);
?>
<?php if ((date("H")>=21) || (date("H")<=7)) { include("inc_haut_n.php"); } else { include("inc_haut_j.php");} ?>

<div id="haut">
	<?php include("inc_entete.php"); ?>
	<div class="haut_aligngauche">
		<p>
		<div class="titrepage">
			Créer un poste
		</div>
		<b class="module4ie"><a href="engine=creerposte.php" class="module4"><img src="im_objets/icon_retour.gif" alt="Retour" />Retour</a></b>
</p>
	</div>
</div>
<div id="centre">
<p>

		<form name="form2" method="post" action="engine=cpfa.php?<? print(''.$_SERVER['QUERY_STRING'].''); ?>">
          <?php
print('<center><em><strong>Nouveau poste <a href="info=actif.php" target="_blank">actif</a> : </strong></em>'.ucwords($_SERVER['QUERY_STRING']).'<p>Nom du poste : <input name="np" type="text" id="nomposte" size="20" maxlength="20" /><br>');

print('Nombre de places pour ce poste : <input name="nbrep" type="text" id="nbrep" size="2" maxlength="2" /><br>');
print('Salaire : <input name="salaire" type="text" id="salaire" size="3" maxlength="3" /> Cr&eacute;dits / Jour <br>');
if($_SERVER['QUERY_STRING']=="directeur")
	{
	print('Minimum requis en Gestion : <input name="mincomp" type="text" id="mincomp" size="3" maxlength="3" /> / 100');
	}
elseif($_SERVER['QUERY_STRING']=="autre")
	{
	}

print('<br><input name="cand" type="checkbox" value="candidature" /> Acc&eacute;der &agrave; ce poste demande de poser une candidature. <br> ');
print('<input name="droit" type="checkbox" value="droit" /> Ce poste &agrave; le droit d\'acc&eacute;der aux bases de donn&eacute;es. </p> ');

?>
            <input type="submit" name="Submit" value="Cr&eacute;er le nouveau poste">
		</form>		


</p>
</div>
<?php if($chat=="oui") 	{ if ((date("H")>=21) || (date("H")<=7)) { include("inc_bas_n_c.php"); } else { include("inc_bas_j_c.php"); } } else {	if ((date("H")>=21) || (date("H")<=7)) { include("inc_bas_n.php"); } else { include("inc_bas_j.php"); }	} ?>
<?php include("inc_bas_de_page.php"); ?>
