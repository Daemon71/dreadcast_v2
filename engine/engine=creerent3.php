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
	exit();
	}
?></span><strong>
          <em>Troisi&egrave;me &eacute;tape : Bases de donn&eacute;es</em></strong>
		<form name="allera" id="allera" method="post" action="engine=creerent4.php">
          <div align="center">            <p>Vous pouvez acc&eacute;der &agrave; ces bases de donn&eacute;es moyennant finance : </p>
            <?php 

if($_SESSION['domaine']=="agence immobiliaire")
{
print('<p>Petits appartements disponibles : <i>Offert</i><br> ');
print('<input type="checkbox" name="gad" value="checkbox" /> Grands appartements disponibles : 300 Cr&eacute;dits<br> ');
print('<input type="checkbox" name="pmd" value="checkbox" /> Petites maisons disponibles : 500 Cr&eacute;dits<br> ');
print('<input type="checkbox" name="gmd" value="checkbox" /> Grandes maisons disponibles : 800 Cr&eacute;dits<br> ');
print('<input type="checkbox" name="vd" value="checkbox" /> Villas disponibles : 2000 Cr&eacute;dits<br> ');
}
elseif($_SESSION['domaine']=="banque")
{
print('<p>Comptes bancaires : <i>Offert</i></p> ');
}
elseif($_SESSION['domaine']=="bar cafe")
{
print('<p>Boissons : <i>Offert</i></p> ');
}
elseif($_SESSION['domaine']=="boutique armes")
{
print('<p>Usines de production : <i>Offert</i></p> ');
}
elseif($_SESSION['domaine']=="boutique spécialisee")
{
print('<p>Usines de production : <i>Offert</i></p> ');
}
elseif($_SESSION['domaine']=="ventes aux encheres")
{
print('<p><i>Aucune base de donn&eacute;e &agrave; disposition.</i></p> ');
}
elseif($_SESSION['domaine']=="hopital")
{
print('<p><input type="checkbox" name="pharmacie" value="checkbox" /> Pharmacie : 600 Cr&eacute;dits</p> ');
}
elseif($_SESSION['domaine']=="usine de production")
{
print('<p>Armes de corps &agrave; corps : <i>Offert</i><br> ');
print('<input type="checkbox" name="armesprim" value="checkbox" /> Armes de corps &agrave; corps avanc&eacute;es : 450 Cr&eacute;dits<br> ');
print('<input type="checkbox" name="armestir" value="checkbox" /> Armes de tir : 600 Cr&eacute;dits<br> ');
print('<input type="checkbox" name="armesav" value="checkbox" /> Armes avanc&eacute;es : 2000 Cr&eacute;dits<br> ');
print('Objets de base : <i>Offert</i><br> ');
print('<input type="checkbox" name="oa" value="checkbox" /> Objets avanc&eacute;s : 500 Cr&eacute;dits<br> ');
print('<input type="checkbox" name="om" value="checkbox" /> Objets magiques : 2000 Cr&eacute;dits<br> ');
print('V&ecirc;tements de tissu : <i>Offert</i><br> ');
print('<input type="checkbox" name="soie" value="checkbox" /> V&ecirc;tements de soie : 300 Cr&eacute;dits<br> ');
print('<input type="checkbox" name="cristal" value="checkbox" /> V&ecirc;tements de cristal : 1000 Cr&eacute;dits<br> ');
}
elseif($_SESSION['domaine']=="centre de recherche")
{
print('<p>Production d\'objets : <i>Offert</i><br> ');
print('<input type="checkbox" name="prodvet" value="checkbox" /> Production de vetements : 10000 Cr&eacute;dits<br> ');
print('<input type="checkbox" name="prodarmesc" value="checkbox" /> Production d\'armes corps à corps : 10000 Cr&eacute;dits<br> ');
print('<input type="checkbox" name="prodarmest" value="checkbox" /> Production d\'armes de tir : 10000 Cr&eacute;dits<br> ');
print('<input type="checkbox" name="prodouu" value="checkbox" /> Production d\'objets à usage unique : 20000 Cr&eacute;dits<br> ');
}
?>
            <p>
              <input type="submit" name="Submit2" value="Valider" />
            </div>
		  </form>
</p>
</div>
<?php if($chat=="oui") 	{ if ((date("H")>=21) || (date("H")<=7)) { include("inc_bas_n_c.php"); } else { include("inc_bas_j_c.php"); } } else {	if ((date("H")>=21) || (date("H")<=7)) { include("inc_bas_n.php"); } else { include("inc_bas_j.php"); }	} ?>
<?php include("inc_bas_de_page.php"); ?>
