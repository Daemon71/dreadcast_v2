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

$sql = 'SELECT arme,case1,case2,case3,case4,case5,case6 FROM principal_tbl WHERE id= "'.$_SESSION['id'].'"' ;
$req = mysql_query($sql);
$_SESSION['case1'] = mysql_result($req,0,case1);
$_SESSION['case2'] = mysql_result($req,0,case2);
$_SESSION['case3'] = mysql_result($req,0,case3);
$_SESSION['case4'] = mysql_result($req,0,case4);
$_SESSION['case5'] = mysql_result($req,0,case5);
$_SESSION['case6'] = mysql_result($req,0,case6);
$_SESSION['arme'] = mysql_result($req,0,arme);

if(ereg("-",$_SESSION['arme']))
	{
	$sqla = 'SELECT usure,modif1,modif2,modif3 FROM armes_tbl WHERE idarme= "'.strstr($_SESSION['arme'],"-").'"';
	$reqa = mysql_query($sqla);
	$resa = mysql_num_rows($reqa);
	if($resa>0)
		{
		$usure = mysql_result($reqa,0,usure);
		$modif1 = mysql_result($reqa,0,modif1);
		$modif2 = mysql_result($reqa,0,modif2);
		$modif3 = mysql_result($reqa,0,modif3);
		}
	else
		{
		$exit = 1;
		}
	}
else
	{
	$exit = 2;
	}

mysql_close($db);
?>
<?php if ((date("H")>=21) || (date("H")<=7)) { include("inc_haut_n.php"); } else { include("inc_haut_j.php");} ?>

<div id="haut">
	<?php include("inc_entete.php"); ?>
	<div class="haut_aligngauche">
		<p>
		<div class="titrepage">
			Modification d'une arme
		</div>
		<b class="module4ie"><a href="engine=inventaire.php" class="module4"><img src="im_objets/icon_retour.gif" alt="Retour" />Retour</a></b>
	</p>
	</div>
</div>
<div id="centre">
<p>

<?php


if($exit==1 || $exit==2)
	{
	print('<br /><strong>Vous ne pouvez poser un silencieux que sur une arme de tir équipée.</strong>');
	}
elseif($modif1==5 || $modif2==5 || $modif3==5)
	{
	print('<br /><strong>Cette arme possède déjà cette amélioration.</strong>');
	}
elseif($_GET['ok']=="1")
	{
for($i=1; $i != 7; $i++)
	{
	if(($_SESSION['case'.$i.'']=="Silencieux") && ($l!=1))
		{
		if($modif1==0)
			{
			$l = 1;
			$db = mysql_connect('localhost', 'CENSURE', 'CENSURE') or die("Erreur de connexion au serveur des messages.");
			mysql_select_db('CENSURE',$db) or die("Erreur de connexion a la base de donnees des messages.");
			$sqla = 'UPDATE armes_tbl SET modif1="5" WHERE idarme= "'.strstr($_SESSION['arme'],"-").'"';
			$reqa = mysql_query($sqla);
			$sqla = 'UPDATE principal_tbl SET case'.$i.'="Vide" WHERE id= "'.$_SESSION['id'].'"';
			$reqa = mysql_query($sqla);
			mysql_close($db);
			print("<center><strong>Vous venez d'installer un silencieux sur votre arme.</strong><br />Equipée, cette arme confère un bonus de 10 points en discrétion.</center><br>");
			}
		elseif($modif2==0)
			{
			$l = 1;
			$db = mysql_connect('localhost', 'CENSURE', 'CENSURE') or die("Erreur de connexion au serveur des messages.");
			mysql_select_db('CENSURE',$db) or die("Erreur de connexion a la base de donnees des messages.");
			$sqla = 'UPDATE armes_tbl SET modif2="5" WHERE idarme= "'.strstr($_SESSION['arme'],"-").'"';
			$reqa = mysql_query($sqla);
			$sqla = 'UPDATE principal_tbl SET case'.$i.'="Vide" WHERE id= "'.$_SESSION['id'].'"';
			$reqa = mysql_query($sqla);
			mysql_close($db);
			print("<center><strong>Vous venez d'installer un silencieux sur votre arme.</strong><br />Equipée, cette arme confère un bonus de 10 points en discrétion.</center><br>");
			}
		elseif($modif3==0)
			{
			$l = 1;
			$db = mysql_connect('localhost', 'CENSURE', 'CENSURE') or die("Erreur de connexion au serveur des messages.");
			mysql_select_db('CENSURE',$db) or die("Erreur de connexion a la base de donnees des messages.");
			$sqla = 'UPDATE armes_tbl SET modif3="5" WHERE idarme= "'.strstr($_SESSION['arme'],"-").'"';
			$reqa = mysql_query($sqla);
			$sqla = 'UPDATE principal_tbl SET case'.$i.'="Vide" WHERE id= "'.$_SESSION['id'].'"';
			$reqa = mysql_query($sqla);
			mysql_close($db);
			print("<center><strong>Vous venez d'installer un silencieux sur votre arme.</strong><br />Equipée, cette arme confère un bonus de 10 points en discrétion.</center><br>");
			}
		else
			{
			$l = 1;
			print("<center><strong>Vous avez déjà trois améliorations sur cette arme.</strong></center><br>");
			}
		}
	}
	}
else
	{
	print('<br />Voulez-vous poser un silencieux sur votre arme équipée ?<br />(discrétion +10)<br /><br /><strong>Arme:</strong> <i>'.$_SESSION['arme'].'</i><br /><br /><a href="?ok=1">Oui</a> - <a href="engine=inventaire.php">Non</a>');
	}

?>

</p>
</div>
<?php if($chat=="oui") 	{ if ((date("H")>=21) || (date("H")<=7)) { include("inc_bas_n_c.php"); } else { include("inc_bas_j_c.php"); } } else {	if ((date("H")>=21) || (date("H")<=7)) { include("inc_bas_n.php"); } else { include("inc_bas_j.php"); }	} ?>
<?php include("inc_bas_de_page.php"); ?>
