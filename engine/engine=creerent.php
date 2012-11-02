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

$sql = 'SELECT credits FROM principal_tbl WHERE id= "'.$_SESSION['id'].'"' ;
$req = mysql_query($sql);
$_SESSION['credits'] = mysql_result($req,0,credits); 

if($_SESSION['entreprise']!="Aucune")
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
			Cr&eacute;ation d'entreprise
		</div>
		<b class="module4ie"><a href="engine=activite.php" class="module4"><img src="im_objets/icon_retour.gif" alt="Retour" />Retour</a></b>
</p>
	</div>
</div>
<div id="centre">
<p>

<?php
if($_SESSION['credits']<3500)
	{
	print('<p align="center"><strong>Cr&eacute;er une entreprise co&ucirc;te 3500 Cr&eacute;dits. </strong>');
	}
else
	{
	print('
	<strong>Cr&eacute;er une entreprise co&ucirc;te 3500 Cr&eacute;dits. </strong>
	<br />
	<strong>La cr&eacute;ation se fait en 5 &eacute;tapes.<br />
	Les champs suivis d\'un <span class="Style1">*</span> sont obligatoires. </strong>
	<p align="center"><em><strong>Premi&egrave;re &eacute;tape : G&eacute;n&eacute;ralit&eacute;s </strong></em></p>
	<form name="allera" id="allera" method="post" action="engine=creerent2.php">
	<div align="center">
	<p>Nom de l\'entreprise :
	<input name="noment" type="text" id="noment" size="20" maxlength="20" />
	<span class="Style1">*</span> </p>
	<p>Domaine de l\'entreprise :
	<select name="domaine" id="domaine">
	<option value="aucun" selected="selected">Aucun</option>
	<option value="agence immobiliaire">Agence immobiliaire</option>
	<option value="banque">Banque</option>
	<option value="bar cafe">Bar - Caf&eacute;</option>
	<option value="boutique armes">Boutique d\'armes</option>
	<option value="boutique spécialisee">Boutique sp&eacute;cialis&eacute;e</option>
	<option value="centre de recherche">Centre de recherche</option>
	<option value="hopital">Hopital - Clinique</option>
	<option value="usine de production">Usine de production</option>
	<option value="ventes aux encheres">Ventes aux ench&egrave;res</option>
	</select>
	</p>
	<p>Attention, si vous ne souscrivez pas d\'assurance après avoir créé votre entreprise, elle sera complêtement détruite si vous venez à mourir (quelle que soit la raison du décès) !</p>
	<p>
	<input type="submit" name="Submit" value="Envoyer" />
	</p>
	</div>
	</form>
	');
	}
?>

</p>
</div>
<?php if($chat=="oui") 	{ if ((date("H")>=21) || (date("H")<=7)) { include("inc_bas_n_c.php"); } else { include("inc_bas_j_c.php"); } } else {	if ((date("H")>=21) || (date("H")<=7)) { include("inc_bas_n.php"); } else { include("inc_bas_j.php"); }	} ?>
<?php include("inc_bas_de_page.php"); ?>
