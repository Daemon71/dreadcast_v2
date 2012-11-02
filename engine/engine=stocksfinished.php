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

mysql_close($db);
?>
<?php if ((date("H")>=21) || (date("H")<=7)) { include("inc_haut_n.php"); } else { include("inc_haut_j.php");} ?>

<div id="haut">
	<?php include("inc_entete.php"); ?>
	<div class="haut_aligngauche">
		<p>
		<div class="titrepage">
			Stocks de l'entreprise
		</div>
		</p>
	</div>
</div>
<div id="centre">
<p>

<img src="im_objets/loader.gif" alt="..." />
<?php 

$db = mysql_connect('localhost', 'CENSURE', 'CENSURE') or die("Erreur de connexion au serveur des messages.");
mysql_select_db('CENSURE',$db) or die("Erreur de connexion a la base de donnees des messages.");

$objet = str_replace("%20"," ",''.$_SERVER['QUERY_STRING'].'');

$sql = 'SELECT * FROM stocks_tbl WHERE entreprise="'.$_SESSION['entreprise'].'" AND objet= "'.$objet.'"' ;
$req = mysql_query($sql);
$res = mysql_num_rows($req);

if($res!=0)
	{
	$idi = mysql_result($req,0,id); 
	$nombre = mysql_result($req,0,nombre); 
	$sql1 = 'SELECT prix,prod FROM objets_tbl WHERE nom= "'.$objet.'"' ;
	$req1 = mysql_query($sql1);
	$prix = mysql_result($req1,0,prix);
	$prod = mysql_result($req1,0,prod);
	if($prod==1)
		{
		if($type=="agence immobiliaire")
			{
			if($budget>$prix)
				{
				$budget = $budget - $prix;
				$nombre = $nombre + 1;
				$sql1 = 'UPDATE entreprises_tbl SET budget= "'.$budget.'" WHERE nom= "'.$_SESSION['entreprise'].'"' ;
				$req1 = mysql_query($sql1);
				$sql1 = 'UPDATE stocks_tbl SET nombre= "'.$nombre.'" WHERE id= "'.$idi.'"' ;
				$req1 = mysql_query($sql1);
				}
			}
		elseif(($type=="usine de production") || ($type=="bar cafe"))
			{
			if($budget>$prix*10)
				{
				$budget = $budget - ( $prix * 10 );
				$nombre = $nombre + 10;
				$sql1 = 'UPDATE entreprises_tbl SET budget= "'.$budget.'" WHERE nom= "'.$_SESSION['entreprise'].'"' ;
				$req1 = mysql_query($sql1);
				$sql1 = 'UPDATE stocks_tbl SET nombre= "'.$nombre.'" WHERE id= "'.$idi.'"' ;
				$req1 = mysql_query($sql1);
				}
			}
		elseif($type=="centre de recherche")
			{
			if($budget>$prix)
				{
				$budget = $budget - $prix;
				$nombre = $nombre + 1;
				$sql1 = 'UPDATE entreprises_tbl SET budget= "'.$budget.'" WHERE nom= "'.$_SESSION['entreprise'].'"' ;
				$req1 = mysql_query($sql1);
				$sql1 = 'UPDATE stocks_tbl SET nombre= "'.$nombre.'" WHERE id= "'.$idi.'"' ;
				$req1 = mysql_query($sql1);
				}
			}
		elseif($type=="hopital")
			{
			if($budget>$prix*5)
				{
				$budget = $budget - ( $prix * 5 );
				$nombre = $nombre + 5;
				$sql1 = 'UPDATE entreprises_tbl SET budget= "'.$budget.'" WHERE nom= "'.$_SESSION['entreprise'].'"' ;
				$req1 = mysql_query($sql1);
				$sql1 = 'UPDATE stocks_tbl SET nombre= "'.$nombre.'" WHERE id= "'.$idi.'"' ;
				$req1 = mysql_query($sql1);
				}
			}
		}
	print('<meta http-equiv="refresh" content="0 ; url=http://v2.dreadcast.net/ingame/engine=stocksconsult.php?'.$objet.'"> ');
	}
else
	{
	print('<meta http-equiv="refresh" content="0 ; url=http://v2.dreadcast.net/ingame/engine=stocks.php"> ');
	}

mysql_close($db);

?>

</p>
</div>
<?php if($chat=="oui") 	{ if ((date("H")>=21) || (date("H")<=7)) { include("inc_bas_n_c.php"); } else { include("inc_bas_j_c.php"); } } else {	if ((date("H")>=21) || (date("H")<=7)) { include("inc_bas_n.php"); } else { include("inc_bas_j.php"); }	} ?>
<?php include("inc_bas_de_page.php"); ?>
