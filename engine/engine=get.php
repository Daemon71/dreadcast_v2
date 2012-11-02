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
?>
<?php if ((date("H")>=21) || (date("H")<=7)) { include("inc_haut_n.php"); } else { include("inc_haut_j.php");} ?>

<div id="haut">
	<?php include("inc_entete.php"); ?>
	<div class="haut_aligngauche">
		<p>
		<div class="titrepage">
			Obtenir un permis
		</div>
		<b class="module4ie"><a href="engine.php" class="module4"><img src="im_objets/icon_retour.gif" alt="Retour" />Retour</a></b>
</p>
	</div>
</div>
<div id="centre">
<p>

<?php	

$db = mysql_connect('localhost', 'CENSURE', 'CENSURE') or die("Erreur de connexion au serveur.");
mysql_select_db('CENSURE',$db) or die("Erreur de connexion a la base de donnees.");

$sql = 'SELECT tir,combat FROM principal_tbl WHERE id= "'.$_SESSION['id'].'"' ;
$req = mysql_query($sql);
$_SESSION['tir'] = mysql_result($req,0,tir);
$_SESSION['combat'] = mysql_result($req,0,combat);

$sql = 'SELECT nom,type FROM entreprises_tbl WHERE rue= "'.$_SESSION['lieu'].'" AND num= "'.$_SESSION['num'].'"' ;
$req = mysql_query($sql);
$type = mysql_result($req,0,type);
$noment = mysql_result($req,0,nom);

if($type!="police")
	{
	print('<meta http-equiv="refresh" content="0 ; url=http://v2.dreadcast.net/ingame/engine.php"> ');
	exit();
	}

$sql = 'SELECT case1,case2,case3,case4,case5,case6 FROM principal_tbl WHERE id= "'.$_SESSION['id'].'"' ;
$req = mysql_query($sql);
$_SESSION['case1'] = mysql_result($req,0,case1);
$_SESSION['case2'] = mysql_result($req,0,case2);
$_SESSION['case3'] = mysql_result($req,0,case3);
$_SESSION['case4'] = mysql_result($req,0,case4);
$_SESSION['case5'] = mysql_result($req,0,case5);
$_SESSION['case6'] = mysql_result($req,0,case6);

$permis = str_replace("%20"," ",''.$_SERVER['QUERY_STRING'].'');
$permis = str_replace("%60","`",''.$permis.'');

if($permis=="carte")
	{
	if(($_SESSION['case1']!="Vide") && ($_SESSION['case2']!="Vide") && ($_SESSION['case3']!="Vide") && ($_SESSION['case4']!="Vide") && ($_SESSION['case5']!="Vide") && ($_SESSION['case6']!="Vide"))
		{
		print("<center><strong>Vous n'avez pas d'emplacement vide dans votre inventaire.</strong></center>");
		$l = 1;
		}
	for($i=1; $i != 7; $i++)
		{
		if(($_SESSION['case'.$i.'']=="Vide") && ($l!=1))
			{
			$sql = 'UPDATE principal_tbl SET case'.$i.'= "Carte" WHERE id= "'.$_SESSION['id'].'"' ;
			$req = mysql_query($sql);
			print('<meta http-equiv="refresh" content="0 ; url=http://v2.dreadcast.net/ingame/engine=inventaire.php"> ');
			exit();			
			}
		}	
	}

$sql = 'SELECT puissance,type FROM objets_tbl WHERE nom= "'.$permis.'"' ;
$req = mysql_query($sql);
$typep = mysql_result($req,0,type);
$puissancep = mysql_result($req,0,puissance);

if($typep=="permist")
	{
	if($_SESSION['tir']>=$puissancep)
		{
		if(($_SESSION['case1']!="Vide") && ($_SESSION['case2']!="Vide") && ($_SESSION['case3']!="Vide") && ($_SESSION['case4']!="Vide") && ($_SESSION['case5']!="Vide") && ($_SESSION['case6']!="Vide"))
			{
			print("<center><strong>Vous n'avez pas d'emplacement vide dans votre inventaire.</strong></center>");
			$l = 1;
			}
		for($i=1; $i != 7; $i++)
			{
			if(($_SESSION['case'.$i.'']=="Vide") && ($l!=1))
				{
				$sql = 'UPDATE principal_tbl SET case'.$i.'= "'.$permis.'" WHERE id= "'.$_SESSION['id'].'"' ;
				$req = mysql_query($sql);
				print('<meta http-equiv="refresh" content="0 ; url=http://v2.dreadcast.net/ingame/engine=inventaire.php"> ');
				exit();			
				}
			}	
		}
	else
		{
		print('<strong>Vous n\'avez pas assez dans la comp&eacute;tence Tir.</strong>');
		$l = 1;
		}
	}
elseif($typep=="permisc")
	{
	if($_SESSION['combat']>=$puissancep)
		{
		if(($_SESSION['case1']!="Vide") && ($_SESSION['case2']!="Vide") && ($_SESSION['case3']!="Vide") && ($_SESSION['case4']!="Vide") && ($_SESSION['case5']!="Vide") && ($_SESSION['case6']!="Vide"))
			{
			print("<center><strong>Vous n'avez pas d'emplacement vide dans votre inventaire.</strong></center>");
			$l = 1;
			}
		for($i=1; $i != 7; $i++)
			{
			if(($_SESSION['case'.$i.'']=="Vide") && ($l != 1))
				{
				$sql = 'UPDATE principal_tbl SET case'.$i.'= "'.$permis.'" WHERE id= "'.$_SESSION['id'].'"' ;
				$req = mysql_query($sql);
				print('<meta http-equiv="refresh" content="0 ; url=http://v2.dreadcast.net/ingame/engine=inventaire.php"> ');
				exit();			
				}
			}	
		}
	else
		{
		print('<strong>Vous n\'avez pas assez dans la comp&eacute;tence Combat.</strong>');
		$l = 1;
		}
	}

mysql_close($db);

?>

</p>
</div>
<?php if($chat=="oui") 	{ if ((date("H")>=21) || (date("H")<=7)) { include("inc_bas_n_c.php"); } else { include("inc_bas_j_c.php"); } } else {	if ((date("H")>=21) || (date("H")<=7)) { include("inc_bas_n.php"); } else { include("inc_bas_j.php"); }	} ?>
<?php include("inc_bas_de_page.php"); ?>
