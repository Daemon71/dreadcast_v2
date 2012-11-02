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
			Hopital
		</div>
		</p>
	</div>
</div>
<div id="centre">
<p>

<?php	

$db = mysql_connect('localhost', 'CENSURE', 'CENSURE') or die("Erreur de connexion au serveur.");
mysql_select_db('CENSURE',$db) or die("Erreur de connexion a la base de donnees.");

$sql = 'SELECT id,rue,num,action,alim,sante,sante_max,fatigue,soins FROM principal_tbl WHERE pseudo= "'.$_SESSION['pseudo'].'"' ;
$req = mysql_query($sql);
$_SESSION['id'] = mysql_result($req,0,id);
$_SESSION['rue'] = mysql_result($req,0,rue);
$_SESSION['num'] = mysql_result($req,0,num);
$_SESSION['action'] = mysql_result($req,0,action);
$_SESSION['alim'] = mysql_result($req,0,alim);
$_SESSION['sante'] = mysql_result($req,0,sante);
$_SESSION['santemax'] = mysql_result($req,0,sante_max);
$_SESSION['fatigue'] = mysql_result($req,0,fatigue);
$soins = mysql_result($req,0,soins);

$sql = 'SELECT credits FROM principal_tbl WHERE id= "'.$_SESSION['id'].'"' ;
$req = mysql_query($sql);
$_SESSION['credits'] = mysql_result($req,0,credits);

$sql = 'SELECT nom,type FROM entreprises_tbl WHERE rue= "'.$_SESSION['rue'].'" AND num= "'.$_SESSION['num'].'"' ;
$req = mysql_query($sql);
$type = mysql_result($req,0,type);
$noment = mysql_result($req,0,nom);

if($type!="hopital")
	{
	print('<meta http-equiv="refresh" content="0 ; url=http://v2.dreadcast.net/ingame/engine.php"> ');
	exit();
	}

$sql = 'SELECT pvente FROM stocks_tbl WHERE objet= "Soins" AND entreprise= "'.$noment.'"' ;
$req = mysql_query($sql);
$res = mysql_num_rows($req);
if($res==0)
	{
	print('<meta http-equiv="refresh" content="0 ; url=http://v2.dreadcast.net/ingame/engine=hopital.php"> ');
	exit();
	}

$pvente = mysql_result($req,0,pvente);

$sql = 'SELECT budget,benefices FROM entreprises_tbl WHERE nom= "'.$noment.'"' ;
$req = mysql_query($sql);
$budget = mysql_result($req,0,budget);
$benef = mysql_result($req,0,benefices);

$nombre = $_POST['npts']/10;

for($i=0;$i!=$nombre;$i++)
	{
	if($_SESSION['credits']>=$pvente)
		{
		$_SESSION['credits'] -= $pvente;
		$plus += 10;
		$budget = $budget + $pvente;
		$benef = $benef + $pvente;
		}
	}

sante_ajouter($_SESSION['id'],$plus);

$sql = 'UPDATE entreprises_tbl SET budget= "'.$budget.'" , benefices= "'.$benef.'" WHERE nom= "'.$noment.'"' ;
$req = mysql_query($sql);
$sql = 'UPDATE principal_tbl SET credits= "'.$_SESSION['credits'].'" WHERE id= "'.$_SESSION['id'].'"' ;
$req = mysql_query($sql);

mysql_close($db);

print('<meta http-equiv="refresh" content="0 ; url=http://v2.dreadcast.net/ingame/engine=stats.php"> ');

?> 

</p>
</div>
<?php if($chat=="oui") 	{ if ((date("H")>=21) || (date("H")<=7)) { include("inc_bas_n_c.php"); } else { include("inc_bas_j_c.php"); } } else {	if ((date("H")>=21) || (date("H")<=7)) { include("inc_bas_n.php"); } else { include("inc_bas_j.php"); }	} ?>
<?php include("inc_bas_de_page.php"); ?>
