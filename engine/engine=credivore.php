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

$sql = 'SELECT credits FROM principal_tbl WHERE pseudo= "'.$_SESSION['pseudo'].'"' ;
$req = mysql_query($sql);
$_SESSION['credits'] = mysql_result($req,0,credits);

$sql = 'SELECT titre FROM titres_tbl WHERE pseudo= "'.$_SESSION['pseudo'].'"' ;
$req = mysql_query($sql);
$res = mysql_num_rows($req);
$niveau = $res + 1;
for($i=0;$i!=$res;$i++) $titres .= mysql_result($req,$i,titre);

if(ereg("Crédivore",$titres))
	{
	}
else
	{
	print('<meta http-equiv="refresh" content="0 ; url=http://v2.dreadcast.net/ingame/engine=mort.php"> ');
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
			Crédivore
		</div>
		<b class="module4ie"><a href="engine=experience.php" class="module4"><img src="im_objets/icon_retour.gif" alt="Retour" />Retour</a></b>
	</p>
	</div>
</div>
<div id="centre">
<p>

<?php
if($_POST['credits']>0)
	{
	if($_SESSION['credits']>=$_POST['credits'])
		{
		$db = mysql_connect('localhost', 'CENSURE', 'CENSURE') or die("Erreur de connexion au serveur des messages.");
		mysql_select_db('CENSURE',$db) or die("Erreur de connexion a la base de donnees des messages.");
		$xp = enregistre($_SESSION['pseudo'],'credivore',valeur($_SESSION['pseudo'],'credivore')+$_POST['credits']);
		$sql = 'UPDATE principal_tbl SET credits=credits-'.$_POST['credits'].' WHERE pseudo= "'.$_SESSION['pseudo'].'"' ;
		$req = mysql_query($sql);
		$sqlspe = 'INSERT INTO transferts_tbl(donneur,receveur,time,operation,valeur) VALUES("'.$_SESSION['pseudo'].'","Dreadcast","'.time().'","Credivore","'.$_POST['credits'].'")';
		$reqspe = mysql_query($sqlspe);
		mysql_close($db);
		print('Vous venez d\'échanger '.$_POST['credits'].' Crédits contre <strong>'.$xp.' Pts d\'expérience</strong> !');
		$_SESSION['credits'] -= $_POST['credits'];
		}
	else
		{
		print('Vous n\'avez pas assez de Crédits sur vous.');
		}
	}
else
	{
	print('Vous vous apprétez à échanger des Crédits contre de l\'expérience.<br /><br /><strong>Voici le taux de change:</strong> 10 Crédits = '.$niveau.' Pts d\'expérience<br /><br />
	<form method="post" action="#">
		Combien de Crédits désirez-vous échanger : <select name="credits">');
	for($i=10;$i<=$_SESSION['credits'];$i=$i+10) print('<option value="'.$i.'">'.$i.'</option>');
	print('</select> <input type="submit" value="Echanger" />
	</form>');
	}
?>

</p>
</div>
<?php if($chat=="oui") 	{ if ((date("H")>=21) || (date("H")<=7)) { include("inc_bas_n_c.php"); } else { include("inc_bas_j_c.php"); } } else {	if ((date("H")>=21) || (date("H")<=7)) { include("inc_bas_n.php"); } else { include("inc_bas_j.php"); }	} ?>
<?php include("inc_bas_de_page.php"); ?>
