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

print('<meta http-equiv="refresh" content="0 ; url=http://v2.dreadcast.net/ingame/engine=cryo.php"> ');
exit();

$db = mysql_connect('localhost', 'CENSURE', 'CENSURE') or die("Erreur de connexion au serveur des messages.");
mysql_select_db('CENSURE',$db) or die("Erreur de connexion a la base de donnees des messages.");

$sql = 'SELECT medecine FROM principal_tbl WHERE id= "'.$_SESSION['id'].'"';
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
	if(($_SESSION['case'.$i.'']=="Kronatium") && ($okKro!=1))
		{
		$sql = 'UPDATE principal_tbl SET case'.$i.'= "Vide" WHERE id= "'.$_SESSION['id'].'"' ;
		$req = mysql_query($sql);
		$okKro = 1;
		$o = $i;
		}
	if(($_SESSION['case'.$i.'']=="Injection") && ($okInj!=1))
		{
		$sql = 'UPDATE principal_tbl SET case'.$i.'= "Vide" WHERE id= "'.$_SESSION['id'].'"' ;
		$req = mysql_query($sql);
		$okInj = 1;
		}
	if(($_SESSION['case'.$i.'']=="Medpack") && ($okmed!=1))
		{
		$sql = 'UPDATE principal_tbl SET case'.$i.'= "Vide" WHERE id= "'.$_SESSION['id'].'"' ;
		$req = mysql_query($sql);
		$okmed = 1;
		}
	if(($_SESSION['case'.$i.'']=="Fiole de jeunesse") && ($okFiole!=1))
		{
		$sql = 'UPDATE principal_tbl SET case'.$i.'= "Vide" WHERE id= "'.$_SESSION['id'].'"' ;
		$req = mysql_query($sql);
		$okFiole = 1;
		}
	if(($_SESSION['case'.$i.'']=="Caisse") && ($okCaisse!=1))
		{
		$sql = 'UPDATE principal_tbl SET case'.$i.'= "Vide" WHERE id= "'.$_SESSION['id'].'"' ;
		$req = mysql_query($sql);
		$okCaisse = 1;
		}
	}

if($_SESSION['medecine']>=120)
	{
	$okmedecine = 1;
	}

if(($okmedecine==1) && ($okmed==1) && ($okInj==1) && ($okKro==1) && ($okCaisse==1) && ($okFiole==1))
	{
	$sql = 'UPDATE principal_tbl SET case'.$o.'= "Virus" WHERE id= "'.$_SESSION['id'].'"' ;
	$req = mysql_query($sql);
	}

mysql_close($db);
?>
<?php if ((date("H")>=21) || (date("H")<=7)) { include("inc_haut_n.php"); } else { include("inc_haut_j.php");} ?>

<div id="haut">
	<?php include("inc_entete.php"); ?>
	<div class="haut_aligngauche">
		<p>
		<div class="titrepage">
			Fabriquer du Virus
		</div>
		<b class="module4ie"><a href="engine=inventaire.php" class="module4"><img src="im_objets/icon_retour.gif" alt="Retour" />Retour</a></b>
</p>
	</div>
</div>
<div id="centre">
<p>

<?php
if(($okmedecine==1) && ($okmed==1) && ($okInj==1) && ($okKro==1) && ($okCaisse==1) && ($okFiole==1))
	{
	print('Vous avez réussi à fabriquer une dose de Virus.<br /><br /><img src="im_objets/virus.jpg" border="0"><br /><br />L\'utiliser permet de vous injecter le virus mortel "Le Faucheur".');
	}
else
	{
	print('Vous n\'avez pas les composants necessaires pour fabriquer du Virus.');
	}
?>

</p>
</div>
<?php if($chat=="oui") 	{ if ((date("H")>=21) || (date("H")<=7)) { include("inc_basm_n_c.php"); } else { include("inc_basm_j_c.php"); } } else {	if ((date("H")>=21) || (date("H")<=7)) { include("inc_basm_n.php"); } else { include("inc_basm_j.php"); }	} ?>
<?php include("inc_bas_de_page.php"); ?>
