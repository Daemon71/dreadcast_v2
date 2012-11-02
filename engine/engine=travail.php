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
			Se mettre au travail
		</div>
		</p>
	</div>
</div>
<div id="centre">
<p>

<img src="im_objets/loader.gif" alt="..." />

<?php	

$db = mysql_connect('localhost', 'CENSURE', 'CENSURE') or die("Erreur de connexion au serveur.");
mysql_select_db('CENSURE',$db) or die("Erreur de connexion a la base de donnees.");

$sql = 'SELECT id,action,rue,num FROM principal_tbl WHERE pseudo= "'.$_SESSION['pseudo'].'"' ;
$req = mysql_query($sql);
$_SESSION['id'] = mysql_result($req,0,id);
$_SESSION['action'] = mysql_result($req,0,action);
$_SESSION['lieu'] = ucwords(mysql_result($req,0,rue));
$_SESSION['num'] = mysql_result($req,0,num);

$sql = 'SELECT type,entreprise FROM principal_tbl WHERE id= "'.$_SESSION['id'].'"' ;
$req = mysql_query($sql);
$_SESSION['poste'] = mysql_result($req,0,type); 
$_SESSION['entreprise'] = mysql_result($req,0,entreprise); 

$sql = 'SELECT type FROM `e_'.str_replace(" ","_",''.$_SESSION['entreprise'].'').'_tbl` WHERE poste= "'.$_SESSION['poste'].'"' ;
$req = mysql_query($sql);
$typeposte = mysql_result($req,0,type); 

$sql = 'SELECT type,ouvert,rue,num FROM entreprises_tbl WHERE nom= "'.$_SESSION['entreprise'].'"' ;
$req = mysql_query($sql);
$type = mysql_result($req,0,type); 
$ouvert = mysql_result($req,0,ouvert); 
$ruent = ucwords(mysql_result($req,0,rue)); 
$nument = mysql_result($req,0,num);

if(($ruent!=$_SESSION['lieu']) || ($nument!=$_SESSION['num']) || ($_SESSION['code']!=$_SESSION['digicode']))
	{
	print('<meta http-equiv="refresh" content="0 ; url=http://v2.dreadcast.net/ingame/engine.php"> ');
	exit();
	}

if($_SESSION['action']!="travail")
	{
	$sql = 'UPDATE principal_tbl SET action="travail" WHERE pseudo= "'.$_SESSION['pseudo'].'"' ;
	$req = mysql_query($sql);
	}

if($ouvert=="non")
	{
	if(($type=="agence immobiliaire") || ($type=="boutique armes") || ($type=="boutique spécialisee") || ($type=="ventes aux encheres") || ($type=="centre de recherche") || ($type=="usine de production"))
		{
		if($typeposte=="vendeur")
			{
			$sql = 'UPDATE entreprises_tbl SET ouvert="oui" WHERE nom= "'.$_SESSION['entreprise'].'"' ;
			$req = mysql_query($sql);
			}
		}
	elseif($type=="banque")
		{
		if($typeposte=="banquier")
			{
			$sql = 'UPDATE entreprises_tbl SET ouvert="oui" WHERE nom= "'.$_SESSION['entreprise'].'"' ;
			$req = mysql_query($sql);
			}
		}
	elseif($type=="bar cafe")
		{
		if($typeposte=="serveur")
			{
			$sql = 'UPDATE entreprises_tbl SET ouvert="oui" WHERE nom= "'.$_SESSION['entreprise'].'"' ;
			$req = mysql_query($sql);
			}
		}
	elseif($type=="hopital")
		{
		if($typeposte=="medecin")
			{
			$sql = 'UPDATE entreprises_tbl SET ouvert="oui" WHERE nom= "'.$_SESSION['entreprise'].'"' ;
			$req = mysql_query($sql);
			}
		}
	}

print('<meta http-equiv="refresh" content="0 ; url=http://v2.dreadcast.net/ingame/engine.php"> ');

mysql_close($db);

?>

</p>
</div>
<?php if($chat=="oui") 	{ if ((date("H")>=21) || (date("H")<=7)) { include("inc_bas_n_c.php"); } else { include("inc_bas_j_c.php"); } } else {	if ((date("H")>=21) || (date("H")<=7)) { include("inc_bas_n.php"); } else { include("inc_bas_j.php"); }	} ?>
<?php include("inc_bas_de_page.php"); ?>
