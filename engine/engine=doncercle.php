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

$sql = 'SELECT nom,capital FROM cerclesliste_tbl WHERE num= "'.$_SESSION['num'].'" AND rue= "'.$_SESSION['rue'].'"' ;
$req = mysql_query($sql);
$res = mysql_num_rows($req);
if($res==0)
	{
	print('<meta http-equiv="refresh" content="0 ; url=http://v2.dreadcast.net/ingame/engine.php"> ');
	exit();
	}

$nomcercle = mysql_result($req,0,nom); 
$capital = mysql_result($req,0,capital); 

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
			Faire un don
		</div>
		<b class="module4ie"><a href="engine.php" class="module4"><img src="im_objets/icon_retour.gif" alt="Retour" />Retour</a></b>
</p>
	</div>
</div>
<div id="centre">
<p>

<?php

if($_POST['cre']!=0)
	{
	if(($_POST['cre']>0) && ($_SESSION['credits']>=$_POST['cre']))
		{
		$db = mysql_connect('localhost', 'CENSURE', 'CENSURE') or die("Erreur de connexion au serveur des messages.");
		mysql_select_db('CENSURE',$db) or die("Erreur de connexion a la base de donnees des messages.");
		
		$capital = $capital + $_POST['cre'];
		$sql = 'UPDATE cerclesliste_tbl SET capital= "'.$capital.'" WHERE num= "'.$_SESSION['num'].'" AND rue= "'.$_SESSION['rue'].'"' ;
		$req = mysql_query($sql);
		
		$_SESSION['credits'] = $_SESSION['credits'] - $_POST['cre'];
		$sql = 'UPDATE principal_tbl SET credits= "'.$_SESSION['credits'].'" WHERE id= "'.$_SESSION['id'].'"' ;
		$req = mysql_query($sql);
		
		if($nomcercle == "TheExchanger") {
			$res = 'INSERT INTO bourse_argent_tbl VALUES("","'.$_SESSION['pseudo'].'","'.$_POST['cre'].'","0")';
			$req = mysql_query($res);
		}
		
		$sql = 'INSERT INTO cerclesdon_tbl(id,pseudo,cercle,don,moment) VALUES("","'.$_SESSION['pseudo'].'","'.$nomcercle.'","'.$_POST['cre'].'","'.time().'")' ;
		$req = mysql_query($sql);
		
		enregistre($_SESSION['pseudo'],'acc_dons_cercle','+'.$_POST['cre']);
		
		mysql_close($db);
		print('<br /><strong>Le don à été correctement pris en compte.</strong><br /><br />Tout le cercle vous présente ses remerciements.<br />Un responsable du cercle prendra peut-être contact avec vous pour remercier en personne.');
		}
	else
		{
		print('Vous n\'avez pas assez de Crédits sur vous pour effectuer ce don.');
		}
	}
else
	{
	print('<form name="form2" method="post" action="engine=doncercle.php">
	<p><br /><em><strong>Faire un don depuis l\'inventaire : </strong></em></p>
	<p><strong>Disponible : </strong><i>'.$_SESSION['credits'].'</i> Cr&eacute;dits</p>
	<p>Donner
	<input name="cre" type="text" id="cre" size="6" maxlength="6">
	Cr&eacute;dits <br />
	</p>
	<p>
	<input type="submit" name="Submit" value="Valider">
	</p>
	</form>');
	}
	
?>

</p>
</div>
<?php if($chat=="oui") 	{ if ((date("H")>=21) || (date("H")<=7)) { include("inc_bas_n_c.php"); } else { include("inc_bas_j_c.php"); } } else {	if ((date("H")>=21) || (date("H")<=7)) { include("inc_bas_n.php"); } else { include("inc_bas_j.php"); }	} ?>
<?php include("inc_bas_de_page.php"); ?>
