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
if ((date("H")>=21) || (date("H")<=7)) { include("inc_haut_n.php"); } else { include("inc_haut_j.php");}
$db = mysql_connect('localhost', 'CENSURE', 'CENSURE') or die("Erreur de connexion au serveur des messages.");
mysql_select_db('CENSURE',$db) or die("Erreur de connexion a la base de donnees des messages.");

$sql = 'SELECT case1,case2,case3,case4,case5,case6,sante_max,fatigue_max FROM principal_tbl WHERE id= "'.$_SESSION['id'].'"' ;
$req = mysql_query($sql);
$_SESSION['fatiguemax'] = mysql_result($req,0,fatigue_max);
$_SESSION['santemax'] = mysql_result($req,0,sante_max);
$_SESSION['case1'] = mysql_result($req,0,case1);
$_SESSION['case2'] = mysql_result($req,0,case2);
$_SESSION['case3'] = mysql_result($req,0,case3);
$_SESSION['case4'] = mysql_result($req,0,case4);
$_SESSION['case5'] = mysql_result($req,0,case5);
$_SESSION['case6'] = mysql_result($req,0,case6);

for($i=1; $i != 7; $i++)
	{
	if(($_SESSION['case'.$i.'']=="Kronatium") && ($l!=1))
		{
		$sql = 'UPDATE principal_tbl SET fatigue="'.drogue($_SESSION['pseudo'],$_SESSION['fatiguemax']).'" , sante="'.drogue($_SESSION['pseudo'],$_SESSION['santemax']).'" , drogue= "'.time().'" WHERE pseudo= "'.$_SESSION['pseudo'].'"' ;
		$req = mysql_query($sql);
		$sql = 'UPDATE principal_tbl SET case'.$i.'= "Vide" WHERE id= "'.$_SESSION['id'].'"' ;
		$req = mysql_query($sql);
		$l = 1;
		$okkro = 1;
		$_SESSION['drogue'] = time();
		$_SESSION['sante'] = drogue($_SESSION['pseudo'],$_SESSION['santemax']);
		$_SESSION['fatigue'] = drogue($_SESSION['pseudo'],$_SESSION['fatiguemax']);
		}
	}

mysql_close($db);
?>

<div id="haut">
	<?php include("inc_entete.php"); ?>
	<div class="haut_aligngauche">
		<p>
		<div class="titrepage">
			Kronatium appauvri
		</div>
		<b class="module4ie"><a href="engine=inventaire.php" class="module4"><img src="im_objets/icon_retour.gif" alt="Retour" />Retour</a></b>
</p>
	</div>
</div>
<div id="centre">
<p>

<?php 

if($okkro==1)
	{
	print('<br /><b>Vous venez de vous injecter une dose de Kronatium appauvri.<br />Vous vous sentez bien plus fort et repos&eacute;.</b><br /><br />Soyez vigilant cela n\'est que provisoire !');
	}

?>

</p>
</div>
<?php if($chat=="oui") 	{ if ((date("H")>=21) || (date("H")<=7)) { include("inc_bas_n_c.php"); } else { include("inc_bas_j_c.php"); } } else {	if ((date("H")>=21) || (date("H")<=7)) { include("inc_bas_n.php"); } else { include("inc_bas_j.php"); }	} ?>
<?php include("inc_bas_de_page.php"); ?>
