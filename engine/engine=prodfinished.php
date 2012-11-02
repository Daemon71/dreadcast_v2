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
			Production de l'entreprise
		</div>
		<b class="module4ie"><a href="engine=production.php" class="module4"><img src="im_objets/icon_retour.gif" alt="Retour" />Retour</a></b>
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
$_SESSION['bdd'] = mysql_result($req,0,bdd); 

$sql = 'SELECT type,budget FROM entreprises_tbl WHERE nom= "'.$_SESSION['entreprise'].'"' ;
$req = mysql_query($sql);
$type = mysql_result($req,0,type); 
$budget = mysql_result($req,0,budget); 

if($_SESSION['bdd']=="") { print('<meta http-equiv="refresh" content="0 ; url=http://v2.dreadcast.net/ingame/engine.php"> '); exit(); }

$objet = str_replace("%20"," ",''.$_SERVER['QUERY_STRING'].'');

$sql = 'SELECT poste FROM `e_'.str_replace(" ","_",''.$_SESSION['entreprise'].'').'_tbl` WHERE type= "technicien"' ;
$req = mysql_query($sql);
$res = mysql_num_rows($req);

$nbretech = 0;
for($i=0;$i!=$res;$i++)
	{
	$postetech = mysql_result($req,$i,poste); 
	$sql1 = 'SELECT action FROM principal_tbl WHERE type= "'.$postetech.'"' ;
	$req1 = mysql_query($sql1);
	$res1 = mysql_num_rows($req1);
	for($p=0;$p!=$res1;$p++)
		{
		if(mysql_result($req1,$p,action)=="travail")
			{
			$nbretech = $nbretech + 1;
			}
		}
	}

if(($type=="usine de production") && ($nbretech==0))
	{
	print('Il vous faut un technicien au travail pour pouvoir produire cet objet !');
	}
else
	{
	$sql = 'SELECT * FROM stocks_tbl WHERE entreprise="'.$_SESSION['entreprise'].'" AND objet= "'.$objet.'"' ;
	$req = mysql_query($sql);
	$res = mysql_num_rows($req);
	
	if($res==0)
		{
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
					$sql1 = 'UPDATE entreprises_tbl SET budget= "'.$budget.'" WHERE nom= "'.$_SESSION['entreprise'].'"' ;
					$req1 = mysql_query($sql1);
					$sql1 = 'INSERT INTO stocks_tbl(id,entreprise,objet,nombre,pvente) VALUES("","'.$_SESSION['entreprise'].'","'.$objet.'","1","'.$prix.'")' ;
					$req1 = mysql_query($sql1);
					$sqlspe = 'INSERT INTO transferts_tbl(donneur,receveur,time,operation,valeur) VALUES("'.$_SESSION['entreprise'].'","Dreadcast","'.time().'","Production stock","'.$prix.'")';
					$reqspe = mysql_query($sqlspe);
					}
				}
			elseif(($type=="usine de production") || ($type=="bar cafe"))
				{
				if($budget>$prix*10)
					{
					$budget = $budget - ( $prix * 10 );
					$sql1 = 'UPDATE entreprises_tbl SET budget= "'.$budget.'" WHERE nom= "'.$_SESSION['entreprise'].'"' ;
					$req1 = mysql_query($sql1);
					$sql1 = 'INSERT INTO stocks_tbl(id,entreprise,objet,nombre,pvente) VALUES("","'.$_SESSION['entreprise'].'","'.$objet.'","10","'.$prix.'")' ;
					$req1 = mysql_query($sql1);
					$sqlspe = 'INSERT INTO transferts_tbl(donneur,receveur,time,operation,valeur) VALUES("'.$_SESSION['entreprise'].'","Dreadcast","'.time().'","Production stock","'.($prix*10).'")';
					$reqspe = mysql_query($sqlspe);
					}
				}
			}
		print('<meta http-equiv="refresh" content="0 ; url=http://v2.dreadcast.net/ingame/engine=stocksconsult.php?'.$objet.'"> ');
		}
	else
		{
		print('<meta http-equiv="refresh" content="0 ; url=http://v2.dreadcast.net/ingame/engine=production.php"> ');
		}
	}

mysql_close($db);

?>

</p>
</div>
<?php if($chat=="oui") 	{ if ((date("H")>=21) || (date("H")<=7)) { include("inc_bas_n_c.php"); } else { include("inc_bas_j_c.php"); } } else {	if ((date("H")>=21) || (date("H")<=7)) { include("inc_bas_n.php"); } else { include("inc_bas_j.php"); }	} ?>
<?php include("inc_bas_de_page.php"); ?>
