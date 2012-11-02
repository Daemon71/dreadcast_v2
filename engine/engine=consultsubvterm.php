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
			Services
		</div>
		<b class="module4ie"><a href="engine=services.php" class="module4"><img src="im_objets/icon_retour.gif" alt="Retour" />Retour</a></b>
</p>
	</div>
</div>
<div id="centre">
<p>

<?php 

$db = mysql_connect('localhost', 'CENSURE', 'CENSURE') or die("Erreur de connexion au serveur des messages.");
mysql_select_db('CENSURE',$db) or die("Erreur de connexion a la base de donnees des messages.");

$sql = 'SELECT type,entreprise,points FROM principal_tbl WHERE id= "'.$_SESSION['id'].'"' ;
$req = mysql_query($sql);
$_SESSION['poste'] = mysql_result($req,0,type); 
$_SESSION['entreprise'] = mysql_result($req,0,entreprise); 
$_SESSION['points'] = mysql_result($req,0,points); 

$sql = 'SELECT bdd FROM `e_'.str_replace(" ","_",''.$_SESSION['entreprise'].'').'_tbl` WHERE poste= "'.$_SESSION['poste'].'"' ;
$req = mysql_query($sql);
$bdd = mysql_result($req,0,bdd); 

$sql = 'SELECT type,num,rue FROM entreprises_tbl WHERE nom= "'.$_SESSION['entreprise'].'"' ;
$req = mysql_query($sql);
$type = mysql_result($req,0,type); 
$rueent = mysql_result($req,0,rue); 
$nument = mysql_result($req,0,num); 

if($bdd=="") { print('<meta http-equiv="refresh" content="0 ; url=http://v2.dreadcast.net/ingame/engine.php"> '); exit(); } if(($type!="vente de services") && ($type!="banque") && ($type!="DOI") && ($type!="conseil") && ($type!="chambre") && ($type!="prison") && ($type!="police") && ($type!="di2rco")) { print('<meta http-equiv="refresh" content="0 ; url=http://v2.dreadcast.net/ingame/engine.php"> '); exit(); }

if((ucwords($_SESSION['rue'])!=ucwords($rueent)) || ($_SESSION['num']!=$nument))
	{
	print('<p align="center"><strong>Vous devez &ecirc;tre sur place pour pouvoir acc&eacute;der &agrave; cette rubrique.</strong></p>'); 
	$l = 1;
	}

if(($type=="DOI") && ($l!=1))
	{
	$sql = 'SELECT entreprise FROM financepridem_tbl WHERE id= "'.$_SERVER['QUERY_STRING'].'"' ;
	$req = mysql_query($sql);
	$res = mysql_num_rows($req);
	if($res>0)
		{
		$ventreprise = mysql_result($req,0,entreprise);
		$sql1 = 'SELECT id FROM financepri_tbl WHERE entreprise= "'.$ventreprise.'" AND membre= "'.$_SESSION['pseudo'].'"' ;
		$req1 = mysql_query($sql1);
		$res1 = mysql_num_rows($req1);
		if($res1==0)
			{
			if($_POST['subv']<=1000)
				{
				$sql2 = 'INSERT INTO financepri_tbl(id,membre,entreprise,vote) VALUES("","'.$_SESSION['pseudo'].'","'.$ventreprise.'","'.$_POST['subv'].'") ' ;
				$req2 = mysql_query($sql2);
				print('<p align="center">Vous venez de voter une subvention.<br>Vous pouvez à tout instant regarder où en sont les votes sur votre ordinateur.</p>');
				}
			else
				{
				print('<p align="center">La subvention doit faire moins de 1000 Crédits.</p>');
				}
			}
		else
			{
			print('<p align="center">Vous avez déjà voté.</p>');
			}
		}
	else
		{
		print('<p align="center">La demande de subvention n\'existe pas.</p>');
		}
	}


mysql_close($db);

?>

</p>
</div>
<?php if($chat=="oui") 	{ if ((date("H")>=21) || (date("H")<=7)) { include("inc_bas_n_c.php"); } else { include("inc_bas_j_c.php"); } } else {	if ((date("H")>=21) || (date("H")<=7)) { include("inc_bas_n.php"); } else { include("inc_bas_j.php"); }	} ?>
<?php include("inc_bas_de_page.php"); ?>
