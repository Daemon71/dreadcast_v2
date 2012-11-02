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
$db = mysql_connect('localhost', 'CENSURE', 'CENSURE') or die("Erreur de connexion au serveur des messages.");
mysql_select_db('CENSURE',$db) or die("Erreur de connexion a la base de donnees des messages.");

$sql = 'SELECT medecine FROM principal_tbl WHERE id= "'.$_SESSION['id'].'"' ;
$req = mysql_query($sql);
$_SESSION['medecine'] = mysql_result($req,0,medecine); 

$sql = 'SELECT * FROM principal_tbl WHERE id= "'.$_SESSION['id'].'"' ;
$req = mysql_query($sql);
$_SESSION['case1'] = mysql_result($req,0,case1); 
$_SESSION['case2'] = mysql_result($req,0,case2); 
$_SESSION['case3'] = mysql_result($req,0,case3); 
$_SESSION['case4'] = mysql_result($req,0,case4); 
$_SESSION['case5'] = mysql_result($req,0,case5); 
$_SESSION['case6'] = mysql_result($req,0,case6); 

for($i=1;$i!=7;$i++)
	{
	if(($_SESSION['case'.$i.'']=="Caisse") && ($okCaisse!=1))
		{
		$sql = 'UPDATE principal_tbl SET case'.$i.'= "Vide" WHERE id= "'.$_SESSION['id'].'"' ;
		$req = mysql_query($sql);
		$okCaisse = 1;
		$o = $i;
		}
	if(($_SESSION['case'.$i.'']=="Feuille de papier libre") && ($okfeuille!=1))
		{
		$sql = 'UPDATE principal_tbl SET case'.$i.'= "Vide" WHERE id= "'.$_SESSION['id'].'"' ;
		$req = mysql_query($sql);
		$okfeuille = 1;
		}
	if(($_SESSION['case'.$i.'']=="Chargeur") && ($okchargeur!=1))
		{
		$sql = 'UPDATE principal_tbl SET case'.$i.'= "Vide" WHERE id= "'.$_SESSION['id'].'"' ;
		$req = mysql_query($sql);
		$okchargeur = 1;
		}
	if(($_SESSION['case'.$i.'']=="Medpack") && ($okmed!=1))
		{
		$sql = 'UPDATE principal_tbl SET case'.$i.'= "Vide" WHERE id= "'.$_SESSION['id'].'"' ;
		$req = mysql_query($sql);
		$okmed = 1;
		}
	}

if($_SESSION['medecine']>=40)
	{
	$okmedecine = 1;
	}

if(($okmedecine==1) && ($okmed==1) && ($okchargeur==1) && ($okfeuille==1) && ($okCaisse==1))
	{
	$okdrogue = rand(1,4);
	if(($okdrogue==1) || ($okdrogue==2) || ($okdrogue==3))
		{
		$sql = 'UPDATE principal_tbl SET case'.$o.'= "Kronatium" WHERE id= "'.$_SESSION['id'].'"' ;
		$req = mysql_query($sql);
		}
	elseif($okdrogue==4)
		{
		$sql = 'UPDATE principal_tbl SET case'.$o.'= "Machine" WHERE id= "'.$_SESSION['id'].'"' ;
		$req = mysql_query($sql);
		}
	}

mysql_close($db);
?>
<?php if ((date("H")>=21) || (date("H")<=7)) { include("inc_haut_n.php"); } else { include("inc_haut_j.php");} ?>

<div id="haut">
	<?php include("inc_entete.php"); ?>
	<div class="haut_aligngauche">
		<p>
		<div class="titrepage">
			Fabriquer du Kronatium
		</div>
		<b class="module4ie"><a href="engine=inventaire.php" class="module4"><img src="im_objets/icon_retour.gif" alt="Retour" />Retour</a></b>
</p>
	</div>
</div>
<div id="centre">
<p>

<?php
if(($okdrogue==1) || ($okdrogue==2) || ($okdrogue==3))
	{
	print('Vous avez réussi à fabriquer une dose de Kronatium.<br /><br /><img src="im_objets/Kronatium.gif" border="0"><br /><br />L\'utiliser décuple les forces mais attention aux effets secondaires !');
	}
elseif($okdrogue==4)
	{
	print('Vous avez réussi à fabriquer une machine de Kronatium.<br /><br /><img src="im_objets/machine.gif" border="0"><br /><br />Le mettre dans son logement crée une doses chaque nuit.');
	}
else
	{
	print('Vous n\'avez pas les composants necessaires pour fabriquer du Kronatium.');
	}
?>

</p>
</div>
<?php if($chat=="oui") 	{ if ((date("H")>=21) || (date("H")<=7)) { include("inc_bas_n_c.php"); } else { include("inc_bas_j_c.php"); } } else {	if ((date("H")>=21) || (date("H")<=7)) { include("inc_bas_n.php"); } else { include("inc_bas_j.php"); }	} ?>
<?php include("inc_bas_de_page.php"); ?>
