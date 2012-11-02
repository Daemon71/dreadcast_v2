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

if($_SESSION['points']!=999) { print('<meta http-equiv="refresh" content="0 ; url=http://v2.dreadcast.net/ingame/engine.php"> '); exit(); }

mysql_close($db);

?>
<?php if ((date("H")>=21) || (date("H")<=7)) { include("inc_haut_n.php"); } else { include("inc_haut_j.php");} ?>

<div id="haut">
	<?php include("inc_entete.php"); ?>
	<div class="haut_aligngauche">
		<p>
		<div class="titrepage">
			Bases de donn&eacute;es
		</div>
		<b class="module4ie"><a href="engine=gestion.php" class="module4"><img src="im_objets/icon_retour.gif" alt="Retour" />Retour</a></b>
</p>
	</div>
</div>
<div id="centre">
<p>

<strong>Vous pouvez acc&eacute;der aux bases de donn&eacute;es suivantes : </strong>
<br /><br />
<form name="bdd" id="bdd" method="post" action="engine=bddfinished.php">
                  <?php 

$db = mysql_connect('localhost', 'CENSURE', 'CENSURE') or die("Erreur de connexion au serveur des messages.");
mysql_select_db('CENSURE',$db) or die("Erreur de connexion a la base de donnees des messages.");

$sql = 'SELECT type FROM entreprises_tbl WHERE nom="'.$_SESSION['entreprise'].'"' ;
$req = mysql_query($sql);
$_SESSION['domaine'] = mysql_result($req,0,type); 

$sql = 'SELECT bdd FROM `e_'.str_replace(" ","_",''.$_SESSION['entreprise'].'').'_tbl` WHERE type="chef"' ;
$req = mysql_query($sql);
$bdd = mysql_result($req,0,bdd); 

if($_SESSION['domaine']=="agence immobiliaire")
	{
	print('Petits appartements disponibles : <i>D&eacute;j&agrave; achet&eacute;</i><br> ');
	if(ereg("pmd",''.$bdd.'')) print('Petites maisons disponibles : <i>D&eacute;j&agrave; achet&eacute;</i><br> ');
	else print('<input type="checkbox" name="pmd" value="checkbox" /> Petites maisons disponibles : 500 Cr&eacute;dits<br> ');
	if(ereg("gad",''.$bdd.'')) print('Grands appartements disponibles : <i>D&eacute;j&agrave; achet&eacute;</i><br> ');
	else print('<input type="checkbox" name="gad" value="checkbox" /> Grands appartements disponibles : 300 Cr&eacute;dits<br> ');
	if(ereg("gmd",''.$bdd.'')) print('Grandes maisons disponibles : <i>D&eacute;j&agrave; achet&eacute;</i><br> ');
	else print('<input type="checkbox" name="gmd" value="checkbox" /> Grandes maisons disponibles : 800 Cr&eacute;dits<br> ');
	if(ereg("vd",''.$bdd.'')) print('Villas disponibles : <i>D&eacute;j&agrave; achet&eacute;</i>');
	else print('<input type="checkbox" name="vd" value="checkbox" /> Villas disponibles : 2000 Cr&eacute;dits ');
	}
elseif($_SESSION['domaine']=="hopital")
	{
	if(ereg("pharmacie",''.$bdd.'')) print('Pharmacie : <i>D&eacute;j&agrave; achet&eacute;</i><br> ');
	else print('<input type="checkbox" name="pharmacie" value="checkbox" /> Pharmacie : 600 Cr&eacute;dits ');
	}
elseif($_SESSION['domaine']=="centre de recherche")
	{
	print('Production d\'objets : <i>D&eacute;j&agrave; achet&eacute;</i><br />');
	if(ereg("prodvet",''.$bdd.'')) print('Production de vetements : <i>D&eacute;j&agrave; achet&eacute;</i><br /> ');
	else print('<input type="checkbox" name="prodvet" value="checkbox" /> Production de vetements : 10000 Cr&eacute;dits<br />');
	if(ereg("prodarmesc",''.$bdd.'')) print('Production d\'armes corps à corps : <i>D&eacute;j&agrave; achet&eacute;</i><br /> ');
	else print('<input type="checkbox" name="prodarmesc" value="checkbox" /> Production d\'armes corps à corps : 10000 Cr&eacute;dits<br />');
	if(ereg("prodarmest",''.$bdd.'')) print('Production d\'armes de tir : <i>D&eacute;j&agrave; achet&eacute;</i><br /> ');
	else print('<input type="checkbox" name="prodarmest" value="checkbox" /> Production d\'armes de tir : 10000 Cr&eacute;dits<br />');
	if(ereg("prodouu",''.$bdd.'')) print('Production d\'objets à usage unique : <i>D&eacute;j&agrave; achet&eacute;</i><br /> ');
	else print('<input type="checkbox" name="prodouu" value="checkbox" /> Production d\'objets à usage unique : 20000 Cr&eacute;dits<br />');
	}
elseif($_SESSION['domaine']=="bar cafe")
	{
	print('Boissons : <i>D&eacute;j&agrave; achet&eacute;</i><br> ');
	if(ereg("jag",''.$bdd.'')) print('Jeux à gratter : <i>D&eacute;j&agrave; achet&eacute;</i><br> ');
	else print('<input type="checkbox" name="jag" value="checkbox" /> Jeux à gratter : 600 Cr&eacute;dits<br />');
	if(ereg("alimentation",''.$bdd.'')) print('Alimentation rapide : <i>D&eacute;j&agrave; achet&eacute;</i><br> ');
	else print('<input type="checkbox" name="alimentation" value="checkbox" /> Alimentation rapide : 200 Cr&eacute;dits<br />');
	}
elseif($_SESSION['domaine']=="usine de production")
	{
	print('Armes de corps &agrave; corps : <i>D&eacute;j&agrave; achet&eacute;</i><br> ');
	if(ereg("armesprim",''.$bdd.'')) print('Armes de corps &agrave; corps avanc&eacute;es : <i>D&eacute;j&agrave; achet&eacute;</i><br> ');
	else print('<input type="checkbox" name="armesprim" value="checkbox" /> Armes de corps &agrave; corps avanc&eacute;es : 450 Cr&eacute;dits<br> ');
	if(ereg("armestir",''.$bdd.'')) print('Armes de tir : <i>D&eacute;j&agrave; achet&eacute;</i><br> ');
	else print('<input type="checkbox" name="armestir" value="checkbox" /> Armes de tir : 600 Cr&eacute;dits<br> ');
	if(ereg("armesav",''.$bdd.'')) print('Armes avanc&eacute;es : <i>D&eacute;j&agrave; achet&eacute;</i><br> ');
	else print('<input type="checkbox" name="armesav" value="checkbox" /> Armes avanc&eacute;es : 2000 Cr&eacute;dits<br> ');
	if(ereg("modif",''.$bdd.'')) print('Modifications d\'armes : <i>D&eacute;j&agrave; achet&eacute;</i><br> ');
	else print('<input type="checkbox" name="modif" value="checkbox" /> Modifications d\'armes : 1050 Cr&eacute;dits<br> ');
	print('Objets de base : <i>D&eacute;j&agrave; achet&eacute;</i><br> ');
	if(ereg("oa",''.$bdd.'')) print('Objets avanc&eacute;s : <i>D&eacute;j&agrave; achet&eacute;</i><br> ');
	else print('<input type="checkbox" name="oa" value="checkbox" /> Objets avanc&eacute;s : 500 Cr&eacute;dits<br> ');
	if(ereg("om",''.$bdd.'')) print('Objets magiques : <i>D&eacute;j&agrave; achet&eacute;</i><br> ');
	else print('<input type="checkbox" name="om" value="checkbox" /> Objets magiques : 2000 Cr&eacute;dits<br> ');
	if(ereg("sac",''.$bdd.'')) print('Sacs : <i>D&eacute;j&agrave; achet&eacute;</i><br> ');
	else print('<input type="checkbox" name="sac" value="checkbox" /> Sacs : 650 Cr&eacute;dits<br> ');
	print('V&ecirc;tements de tissu : <i>D&eacute;j&agrave; achet&eacute;</i><br> ');
	if(ereg("soie",''.$bdd.'')) print('V&ecirc;tements de soie : <i>D&eacute;j&agrave; achet&eacute;</i><br> ');
	else print('<input type="checkbox" name="soie" value="checkbox" /> V&ecirc;tements de soie : 300 Cr&eacute;dits<br> ');
	if(ereg("cristal",''.$bdd.'')) print('V&ecirc;tements de cristal : <i>D&eacute;j&agrave; achet&eacute;</i><br> ');
	else print('<input type="checkbox" name="cristal" value="checkbox" /> V&ecirc;tements de cristal : 1000 Cr&eacute;dits<br> ');
	if(ereg("deck",''.$bdd.'')) print('Decks : <i>D&eacute;j&agrave; achet&eacute;</i><br> ');
	else print('<input type="checkbox" name="deck" value="checkbox" /> Decks : 600 Cr&eacute;dits<br> ');
	}
elseif($_SESSION['domaine']=="di2rco")
	{
	print('Race, sexe, age et taille des particuliers<br> ');
	print('Travail des particuliers<br> ');
	print('Adresse des particuliers<br> ');
	print('Messagerie des particuliers<br> ');
	print('Pleintes des particuliers<br> ');
	print('Avis de recherche</br ');
	print('Avis de recherche imp&eacute;riaux ');
	print('Satellites');
	}
elseif($_SESSION['domaine']=="police")
	{
	print('Race, sexe, age et taille des particuliers<br> ');
	print('Travail des particuliers<br> ');
	print('Adresse des particuliers<br> ');
	print('Pleintes des particuliers<br> ');
	print('Avis de recherche ');
	}
elseif($_SESSION['domaine']=="Prison")
	{
	print('Liste des prisonniers ');
	}

mysql_close($db);

?>
<br />
<br />
<input type="submit" name="Submit2" value="Acheter" />
</p>
</form>
			  
			  
</p>
</div>
<?php if($chat=="oui") 	{ if ((date("H")>=21) || (date("H")<=7)) { include("inc_bas_n_c.php"); } else { include("inc_bas_j_c.php"); } } else {	if ((date("H")>=21) || (date("H")<=7)) { include("inc_bas_n.php"); } else { include("inc_bas_j.php"); }	} ?>
<?php include("inc_bas_de_page.php"); ?>
