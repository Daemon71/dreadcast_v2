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

$sql = 'SELECT type,entreprise,points FROM principal_tbl WHERE id= "'.$_SESSION['id'].'"' ;
$req = mysql_query($sql);
$_SESSION['poste'] = mysql_result($req,0,type); 
$_SESSION['entreprise'] = mysql_result($req,0,entreprise); 
$_SESSION['points'] = mysql_result($req,0,points); 

$sql = 'SELECT bdd FROM `e_'.str_replace(" ","_",''.$_SESSION['entreprise'].'').'_tbl` WHERE poste= "'.$_SESSION['poste'].'"' ;
$req = mysql_query($sql);
$_SESSION['bdd'] = mysql_result($req,0,bdd); 

$sql = 'SELECT type,budget FROM entreprises_tbl WHERE nom= "'.$_SESSION['entreprise'].'"' ;
$req = mysql_query($sql);
$type = mysql_result($req,0,type); 
$budget = mysql_result($req,0,budget); 

if($_SESSION['bdd']=="") { print('<meta http-equiv="refresh" content="0 ; url=http://v2.dreadcast.net/ingame/engine.php"> '); exit(); }
 
$objet = str_replace("%20"," ",''.$_SERVER['QUERY_STRING'].'');

mysql_close($db);
?>
<?php if ((date("H")>=21) || (date("H")<=7)) { include("inc_haut_n.php"); } else { include("inc_haut_j.php");} ?>

<div id="haut">
	<?php include("inc_entete.php"); ?>
	<div class="haut_aligngauche_haut">
		<p>
		<div class="titrepage">
			Stocks de l'entreprise 
		</div>
		<b class="module4ie"><a href="engine=stocksconsult.php?<?php print(''.$objet.''); ?>" class="module4"><img src="im_objets/icon_retour.gif" alt="Retour" />Retour</a></b>
<div align="center"><br /><strong>Capital :</strong><em> <?php print(''.$budget.''); ?> Crédits</em></div>
		</p>
	</div>
</div>
<div id="centre">
<p>

<img src="im_objets/loader.gif" alt="..." />
<?php 

$db = mysql_connect('localhost', 'CENSURE', 'CENSURE') or die("Erreur de connexion au serveur des messages.");
mysql_select_db('CENSURE',$db) or die("Erreur de connexion a la base de donnees des messages.");

$sql = 'SELECT * FROM stocks_tbl WHERE entreprise="'.$_SESSION['entreprise'].'" AND objet= "'.$objet.'"' ;
$req = mysql_query($sql);
$res = mysql_num_rows($req);

if($res!=0)
	{
	$idi = mysql_result($req,0,id); 
	$objet = mysql_result($req,0,objet); 
	$nombre = mysql_result($req,0,nombre);
	$pvente = mysql_result($req,0,pvente);	
	$sql1 = 'SELECT image,prix FROM objets_tbl WHERE nom="'.$objet.'"' ;
	$req1 = mysql_query($sql1);
	$res1 = mysql_num_rows($req1);
	if($res1!=0)
		{
		$image = mysql_result($req1,0,image); 
		$prix = mysql_result($req1,0,prix); 
		if($type=="usine de production")
			{
			$nombres = $nombre / 10;
			if($nombres>=1)
				{
				$budget = $budget + 10 * $prix;
				$nombre = $nombre - 10;
				$sql1 = 'UPDATE stocks_tbl SET nombre= "'.$nombre.'" WHERE objet="'.$objet.'" AND entreprise= "'.$_SESSION['entreprise'].'"' ;
				$req1 = mysql_query($sql1);
				$sql1 = 'UPDATE entreprises_tbl SET budget= "'.$budget.'" WHERE nom= "'.$_SESSION['entreprise'].'"' ;
				$req1 = mysql_query($sql1);
				$sql1 = 'INSERT INTO transferts_tbl(donneur,receveur,time,operation,valeur) VALUES("Dreadcast","'.$_SESSION['entreprise'].'","'.time().'","Revente stock","'.(10*$prix).'")';
				$req1 = mysql_query($sql1);
				print('<meta http-equiv="refresh" content="0 ; url=http://v2.dreadcast.net/ingame/engine=stocks.php"> ');
				exit();
				}
			else
				{
				print('<meta http-equiv="refresh" content="0 ; url=http://v2.dreadcast.net/ingame/engine=stocks.php"> ');
				exit();
				}
			}
		elseif($type=="agence immobiliaire")
			{
			if($nombre>=1)
				{
				$budget = $budget + $prix;
				$nombre = $nombre - 1;
				$sql1 = 'UPDATE stocks_tbl SET nombre= "'.$nombre.'" WHERE objet="'.$objet.'" AND entreprise= "'.$_SESSION['entreprise'].'"' ;
				$req1 = mysql_query($sql1);
				$sql1 = 'UPDATE entreprises_tbl SET budget= "'.$budget.'" WHERE nom= "'.$_SESSION['entreprise'].'"' ;
				$req1 = mysql_query($sql1);
				$sql1 = 'INSERT INTO transferts_tbl(donneur,receveur,time,operation,valeur) VALUES("Dreadcast","'.$_SESSION['entreprise'].'","'.time().'","Revente stock","'.($prix).'")';
				$req1 = mysql_query($sql1);
				print('<meta http-equiv="refresh" content="0 ; url=http://v2.dreadcast.net/ingame/engine=stocks.php"> ');
				exit();
				}
			else
				{
				print('<meta http-equiv="refresh" content="0 ; url=http://v2.dreadcast.net/ingame/engine=stocks.php"> ');
				exit();
				}
			}
		}
	else
		{
		print('<meta http-equiv="refresh" content="0 ; url=http://v2.dreadcast.net/ingame/engine=stocks.php"> ');
		exit();
		}
	}
else
	{
	print('<meta http-equiv="refresh" content="0 ; url=http://v2.dreadcast.net/ingame/engine=stocks.php"> ');
	exit();
	}

mysql_close($db);

?>

</p>
</div>
<?php if($chat=="oui") 	{ if ((date("H")>=21) || (date("H")<=7)) { include("inc_bas_n_c.php"); } else { include("inc_bas_j_c.php"); } } else {	if ((date("H")>=21) || (date("H")<=7)) { include("inc_bas_n.php"); } else { include("inc_bas_j.php"); }	} ?>
<?php include("inc_bas_de_page.php"); ?>
