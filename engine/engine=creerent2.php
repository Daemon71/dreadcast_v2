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

$noment = $_POST['noment'];

$sql = 'SELECT id FROM entreprises_tbl WHERE nom= "'.$noment.'"' ;
$req = mysql_query($sql);
$res = mysql_num_rows($req);

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
		<b class="module4ie"><a href="engine=creerent.php" class="module4"><img src="im_objets/icon_retour.gif" alt="Retour" />Retour</a></b>
</p>
	</div>
</div>
<div id="centre">
<p>

		  <?php
if($_SESSION['credits']<3500)
	{
	print('<p align="center"><strong>Cr&eacute;er une entreprise co&ucirc;te 3500 Cr&eacute;dits. </strong>');
	$l = 1;
	}
elseif($res!=0)
	{
	print('<p align="center"><strong>Votre nom d\'entreprise éxiste déjà.</strong>');
	$l = 1;
	}

for($i=0;$i!=strlen($noment);$i++)
	{
	$p = $noment{$i};
	if((preg_match("#[a-zA-Z0-9]#",$p)) || ($p==" ") || ($l==1))
		{
		}
	else
		{
		echo "Vous ne pouvez pas utiliser de caractères spéciaux dans le nom d'entreprise !";
		$l = 1;
		}
	} 

$_SESSION['noment'] = trim(''.ucfirst($_POST['noment']).'');
$_SESSION['domaine'] = $_POST['domaine'];
	
if($_SESSION['noment']=="Aucune")
	{
	print('<p align="center"><strong>Votre nom d\'entreprise éxiste déjà. </strong>');
	$l = 1;
	}
if(ereg("&",''.$_SESSION['noment'].''))
	{
	print('<p align="center"><strong>Le nom ne doit pas comporter de caractères spéciaux. </strong>');
	$l = 1;
	}
if(ereg("'",$_POST['noment']))
	{
	print('<p align="center"><strong>Le nom ne doit pas comporter de caractères spéciaux. </strong>');
	$l = 1;
	} 
if(strlen($_POST['noment'])<5)
	{
	print('<p align="center"><strong>Le nom de votre entreprise doit faire minimum 5 caractères.</strong>');
	$l = 1;
	}

if($l!=1)
	{
	print('</span><em><strong>Deuxi&egrave;me &eacute;tape : Hi&eacute;rarchie </strong></em>        
	<p align="center">Voici la liste des postes qu\'il vous sera possible de cr&eacute;er pour votre entreprise : 
	<form name="allera" id="allera" method="post" action="engine=creerent3.php">
	<div align="center">            <p></p>
	<em><strong>Poste 1 : </strong></em>PDG (Vous)<br>');
            
	if($_SESSION['domaine']=="aucun")
		{
		print('<em><strong>Poste 2 : </strong></em>Agent de maintenance<br>');
		print('<em><strong>Poste 3 : </strong></em>Agent de s&eacute;curit&eacute;<br>');
		print('<em><strong>Poste 4 : </strong></em>Directeur<br>');
		print('<em><strong>Poste 5 : </strong></em>Autre...<br>');
		}
	elseif($_SESSION['domaine']=="agence immobiliaire")
		{
		print('<em><strong>Poste 2 : </strong></em>Vendeur<br>');
		print('<em><strong>Poste 3 : </strong></em>Agent de maintenance<br>');
		print('<em><strong>Poste 4 : </strong></em>Agent de s&eacute;curit&eacute;<br>');
		print('<em><strong>Poste 5 : </strong></em>Directeur<br>');
		print('<em><strong>Poste 6 : </strong></em>Autre...<br>');
		}
	elseif($_SESSION['domaine']=="banque")
		{
		print('<em><strong>Poste 2 : </strong></em>Banquier<br>');
		print('<em><strong>Poste 3 : </strong></em>Agent de maintenance<br>');
		print('<em><strong>Poste 4 : </strong></em>Agent de s&eacute;curit&eacute;<br>');
		print('<em><strong>Poste 5 : </strong></em>Directeur<br>');
		print('<em><strong>Poste 6 : </strong></em>Autre...<br>');
		}
	elseif($_SESSION['domaine']=="bar cafe")
		{
		print('<em><strong>Poste 2 : </strong></em>Serveur<br>');
		print('<em><strong>Poste 3 : </strong></em>Agent de maintenance<br>');
		print('<em><strong>Poste 4 : </strong></em>Agent de s&eacute;curit&eacute;<br>');
		print('<em><strong>Poste 5 : </strong></em>Directeur<br>');
		print('<em><strong>Poste 6 : </strong></em>Autre...<br>');
		}
	elseif(($_SESSION['domaine']=="boutique armes") || ($_SESSION['domaine']=="boutique spécialisee"))
		{
		print('<em><strong>Poste 2 : </strong></em>Vendeur<br>');
		print('<em><strong>Poste 3 : </strong></em>Agent de maintenance<br>');
		print('<em><strong>Poste 4 : </strong></em>Agent de s&eacute;curit&eacute;<br>');
		print('<em><strong>Poste 5 : </strong></em>Directeur<br>');
		print('<em><strong>Poste 6 : </strong></em>Autre...<br>');
		}
	elseif($_SESSION['domaine']=="ecole ingenieur")
		{
		print('<em><strong>Poste 2 : </strong></em>Professeur de m&eacute;canique<br>');
		print('<em><strong>Poste 3 : </strong></em>Professeur de technologie<br>');
		print('<em><strong>Poste 4 : </strong></em>Agent de maintenance<br>');
		print('<em><strong>Poste 5 : </strong></em>Agent de s&eacute;curit&eacute;<br>');
		print('<em><strong>Poste 6 : </strong></em>Directeur<br>');
		print('<em><strong>Poste 7 : </strong></em>Autre...<br>');
		}
	elseif($_SESSION['domaine']=="ventes aux encheres")
		{
		print('<em><strong>Poste 2 : </strong></em>Vendeur<br>');
		print('<em><strong>Poste 3 : </strong></em>Agent de maintenance<br>');
		print('<em><strong>Poste 4 : </strong></em>Agent de s&eacute;curit&eacute;<br>');
		print('<em><strong>Poste 5 : </strong></em>Directeur<br>');
		print('<em><strong>Poste 6 : </strong></em>Autre...<br>');
		}
	elseif($_SESSION['domaine']=="centre de recherche")
		{
		print('<em><strong>Poste 3 : </strong></em>Vendeur<br>');
		print('<em><strong>Poste 3 : </strong></em>Technicien de production<br>');
		print('<em><strong>Poste 4 : </strong></em>Agent de maintenance<br>');
		print('<em><strong>Poste 5 : </strong></em>Agent de s&eacute;curit&eacute;<br>');
		print('<em><strong>Poste 6 : </strong></em>Directeur<br>');
		print('<em><strong>Poste 7 : </strong></em>Autre...<br>');
		}
	elseif($_SESSION['domaine']=="hopital")
		{
		print('<em><strong>Poste 2 : </strong></em>Médecin<br>');
		print('<em><strong>Poste 3 : </strong></em>Agent de maintenance<br>');
		print('<em><strong>Poste 4 : </strong></em>Agent de s&eacute;curit&eacute;<br>');
		print('<em><strong>Poste 5 : </strong></em>Directeur<br>');
		print('<em><strong>Poste 6 : </strong></em>Autre...<br>');
		}
	elseif($_SESSION['domaine']=="usine de production")
		{
		print('<em><strong>Poste 2 : </strong></em>Vendeur<br>');
		print('<em><strong>Poste 3 : </strong></em>Technicien de production<br>');
		print('<em><strong>Poste 4 : </strong></em>Agent de maintenance<br>');
		print('<em><strong>Poste 5 : </strong></em>Agent de s&eacute;curit&eacute;<br>');
		print('<em><strong>Poste 6 : </strong></em>Directeur<br>');
		print('<em><strong>Poste 7 : </strong></em>Autre...<br>');
		}
	print('<input type="submit" name="Submit2" value="Valider" />
            </div>
		  </form>');
	}
?>
<br>
</p>
</div>
<?php if($chat=="oui") 	{ if ((date("H")>=21) || (date("H")<=7)) { include("inc_bas_n_c.php"); } else { include("inc_bas_j_c.php"); } } else {	if ((date("H")>=21) || (date("H")<=7)) { include("inc_bas_n.php"); } else { include("inc_bas_j.php"); }	} ?>
<?php include("inc_bas_de_page.php"); ?>
