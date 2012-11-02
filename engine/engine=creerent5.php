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
?>
</span><em><strong>Cinqui&egrave;me &eacute;tape : R&eacute;capitulatif</strong></em>
		<p align="center">
          <?php 
print('<strong>Nom de l\'entreprise :</strong> <i>'.ucwords($_SESSION['noment']).'</i><br>');
print('<strong>Type d\'entreprise :</strong> <i>'.ucwords($_SESSION['domaine']).'</i></p>');

print('<p align="center"><strong>Poste 1 : </strong> PDG (Vous)');
	
$totalent = 3500;
$_SESSION['aent'] = $_POST['aent'];
$_SESSION['sectcent'] = $_POST['sec'];
$_SESSION['ruecent'] = $_POST['rue'];

if($_SESSION['aent']==50)
	{
	$totalent = $totalent + 400;
	}
elseif($_SESSION['aent']==100)
	{
	$totalent = $totalent + 1000;
	}
elseif($_SESSION['aent']==200)
{
$totalent = $totalent + 2000;
}

print('<p align="center"><strong>Type de locaux pour l\'entreprise :</strong> <i>Local de '.$_SESSION['aent'].'m&sup2;</i><br />');
print('<strong>Localisation des locaux de l\'entreprise :</strong> <i>Secteur '.$_SESSION['sectcent'].'</i>'.(($_SESSION['ruecent']!="")?' ('.$_SESSION['ruecent'].')':'').'<br /><br />');

print('<strong>Base(s) de donn&eacute;e(s) accessible(s) :</strong> ');
$bdd = "";
if($_SESSION['domaine']=="agence immobiliaire")
	{
	print('<i>Petits appartements disponibles</i>');
	$bdd = ''.$bdd.' pad';
	if($_SESSION['pmd']!="")
		{
		print('<i>, Petites maisons disponibles</i>');
		$totalent = $totalent + 500;
		$bdd = ''.$bdd.' pmd';
		}
	if($_SESSION['gad']!="")
		{
		print('<i>, Grands appartements disponibles</i>');
		$totalent = $totalent + 300;
		$bdd = ''.$bdd.' gad';
		}
	if($_SESSION['gmd']!="")
		{
		print('<i>, Grandes maisons disponibles</i>');
		$totalent = $totalent + 800;
		$bdd = ''.$bdd.' gmd';
		}
	if($_SESSION['vd']!="")
		{
		print('<i>, Villas disponibles</i>');
		$totalent = $totalent + 2000;
		$bdd = ''.$bdd.' vd';
		}
	}
elseif($_SESSION['domaine']=="banque")
	{
	print('<i>Comptes bancaires</i>');
	$bdd = ''.$bdd.' banque';
	}
elseif($_SESSION['domaine']=="bar cafe")
	{
	print('<i>Boissons</i>');
	$bdd = ''.$bdd.' boissons';
	}
elseif($_SESSION['domaine']=="boutique armes")
	{
	print('<i>Usines de production</i>');
	$bdd = ''.$bdd.' usines';
	}
elseif($_SESSION['domaine']=="boutique spécialisee")
	{
	print('<i>Usines de production</i>');
	$bdd = ''.$bdd.' usines';
	}
elseif(($_SESSION['domaine']=="centre apprentissage") || ($_SESSION['domaine']=="centre de recherches") || ($_SESSION['domaine']=="ecole ingenieur") || ($_SESSION['domaine']=="ecole de commerce") || ($_SESSION['domaine']=="societe de transports") || ($_SESSION['domaine']=="ventes aux encheres"))
	{
	print('<i>Aucune base de donn&eacute;e &agrave; disposition</i>');
	}
elseif($_SESSION['domaine']=="garage")
	{
	print('<i>Usines de production</i>');
	$bdd = ''.$bdd.' usines';
	}
elseif($_SESSION['domaine']=="hopital")
	{
	if($_SESSION['pharmacie']!="")
		{
		print('<i>Pharmacie</i>');
		$totalent = $totalent + 600;
		$bdd = ''.$bdd.' pharmacie';
		}
	else
		{
		print('<i>Aucune</i>');
		}
	}
elseif($_SESSION['domaine']=="centre de recherche")
	{
	print('<i>Production d\'objets</i>');
	$bdd = ''.$bdd.' prodob';
	if($_SESSION['prodvet']!="")
		{
		print('<i>, Production de vetements</i>');
		$totalent = $totalent + 10000;
		$bdd = ''.$bdd.' prodvet';
		}
	if($_SESSION['prodarmest']!="")
		{
		print('<i>, Production d\'armes de combat</i>');
		$totalent = $totalent + 10000;
		$bdd = ''.$bdd.' prodarmesc';
		}
	if($_SESSION['prodarmesc']!="")
		{
		print('<i>, Production d\'armes de tir</i>');
		$totalent = $totalent + 10000;
		$bdd = ''.$bdd.' prodarmest';
		}
	if($_SESSION['prodouu']!="")
		{
		print('<i>, Production d\'objets à usage unique</i>');
		$totalent = $totalent + 20000;
		$bdd = ''.$bdd.' prodouu';
		}
	}
elseif($_SESSION['domaine']=="usine de production")
	{
	print('<i>Armes de corps &agrave; corps</i>');
	$bdd = ''.$bdd.' acac';
	if($_SESSION['armesprim']!="")
		{
		print('<i>, Armes de corps &agrave; corps avanc&eacute;es</i>');
		$totalent = $totalent + 450;
		$bdd = ''.$bdd.' armesprim';
		}
	if($_SESSION['armestir']!="")
		{
		print('<i>, Armes de tir</i>');
		$totalent = $totalent + 600;
		$bdd = ''.$bdd.' armestir';
		}
	if($_SESSION['armesav']!="")
		{
		print('<i>, Armes avanc&eacute;es</i>');
		$totalent = $totalent + 2000;
		$bdd = ''.$bdd.' armesav';
		}
	if($_SESSION['voitures']!="")
		{
		print('<i>, Voitures</i>');
		$totalent = $totalent + 400;
		$bdd = ''.$bdd.' voitures';
		}
	if($_SESSION['autrev']!="")
		{
		print('<i>, Autres v&eacute;hicules</i>');
		$totalent = $totalent + 900;
		$bdd = ''.$bdd.' autrev';
		}
	print('<br><i>Objets de base</i>');
	$bdd = ''.$bdd.' objets';
	if($_SESSION['oa']!="")
		{
		print('<i>, Objets avanc&eacute;s</i>');
		$totalent = $totalent + 500;
		$bdd = ''.$bdd.' oa';
		}
	if($_SESSION['om']!="")
		{
		print('<i>, Objets magiques</i>');
		$totalent = $totalent + 2000;
		$bdd = ''.$bdd.' om';
		}
	print('<br><i>V&ecirc;tements de tissu</i>');
	$bdd = ''.$bdd.' tissu';
	if($_SESSION['soie']!="")
		{
		print('<i>, V&ecirc;tements de soie</i>');
		$totalent = $totalent + 300;
		$bdd = ''.$bdd.' soie';
		}
	if($_SESSION['cristal']!="")
		{
		print('<i>, V&ecirc;tements de cristal</i>');
		$totalent = $totalent + 1000;
		$bdd = ''.$bdd.' cristal';
		}
	}
$_SESSION['bdd'] = $bdd;	
$_SESSION['totalent'] = $totalent;
$_SESSION['creationent'] = "ok";
print('<p align="center"><strong>Co&ucirc;t total de la cr&eacute;ation :</strong> <i>'.$_SESSION['totalent'].' Cr&eacute;dits</i>');
?>
        </p>
		<form name="form1" id="form1" method="post" action="engine=finishedcreation.php">
          <div align="center">
            <input type="submit" name="Submit" value="Confirmer la cr&eacute;ation" />
          </div>
		  </form>
		<p align="center"><a href="engine.php">Annuler</a></p>		  

</p>
</div>
<?php if($chat=="oui") 	{ if ((date("H")>=21) || (date("H")<=7)) { include("inc_bas_n_c.php"); } else { include("inc_bas_j_c.php"); } } else {	if ((date("H")>=21) || (date("H")<=7)) { include("inc_bas_n.php"); } else { include("inc_bas_j.php"); }	} ?>
<?php include("inc_bas_de_page.php"); ?>
