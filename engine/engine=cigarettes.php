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

$sql = 'SELECT id,rue,num,action,alim,sante,sante_max,fatigue,fatigue_max,drogue,soins FROM principal_tbl WHERE pseudo= "'.$_SESSION['pseudo'].'"' ;
$req = mysql_query($sql);
$_SESSION['id'] = mysql_result($req,0,id);
$_SESSION['rue'] = mysql_result($req,0,rue);
$_SESSION['num'] = mysql_result($req,0,num);
$_SESSION['action'] = mysql_result($req,0,action);
$_SESSION['alim'] = mysql_result($req,0,alim);
$_SESSION['sante'] = mysql_result($req,0,sante);
$santemax = mysql_result($req,0,sante_max);
$_SESSION['fatigue'] = mysql_result($req,0,fatigue);
$_SESSION['drogue'] = mysql_result($req,0,drogue);
$fatiguemax = mysql_result($req,0,fatigue_max);
$_SESSION['soins'] = mysql_result($req,0,soins);
$_SESSION['lieu'] = strtolower($_SESSION['rue']);
$_SESSION['lieu'] = ucwords($_SESSION['lieu']);

mysql_close($db);
?>
<?php if ((date("H")>=21) || (date("H")<=7)) { include("inc_haut_n.php"); } else { include("inc_haut_j.php");} ?>

<div id="haut">
	<?php include("inc_entete.php"); ?>
	<div class="haut_aligngauche">
		<p>
		<div class="titrepage">
			Paquet de cigarettes
		</div>
		<b class="module4ie"><a href="engine=inventaire.php" class="module4"><img src="im_objets/icon_retour.gif" alt="Retour" />Retour</a></b>
</p>
	</div>
</div>
<div id="centre">
<p>

<?php 

$db = mysql_connect('localhost', 'CENSURE', 'CENSURE') or die("Erreur de connexion au serveur des messages.");
mysql_select_db('CENSURE',$db) or die("Erreur de connexion a la base de donnees des messages.");

if($_SESSION['drogue']>0) { $fatiguemax = drogue($_SESSION['pseudo'],$fatiguemax); $santemax = drogue($_SESSION['pseudo'],$santemax); }

$sql = 'SELECT case1,case2,case3,case4,case5,case6 FROM principal_tbl WHERE id= "'.$_SESSION['id'].'"' ;
$req = mysql_query($sql);
$_SESSION['case1'] = mysql_result($req,0,case1);
$_SESSION['case2'] = mysql_result($req,0,case2);
$_SESSION['case3'] = mysql_result($req,0,case3);
$_SESSION['case4'] = mysql_result($req,0,case4);
$_SESSION['case5'] = mysql_result($req,0,case5);
$_SESSION['case6'] = mysql_result($req,0,case6);

for($i=1; $i != 7; $i++)
	{
	if(($_SESSION['case'.$i.'']=="Paquet de cigarettes") && ($l!=1))
		{
		if($_SESSION['fatigue']<$fatiguemax)
			{
			$l = 1;
			$cod = rand(0,20);
			$_SESSION['fatigue'] = $_SESSION['fatigue'] + 40 + $cod;
			if($_SESSION['fatigue']>$fatiguemax)
				{
				$_SESSION['fatigue'] = $fatiguemax;
				}
			$_SESSION['sante'] = $_SESSION['sante'] - 9;
			if($_SESSION['sante']<0)
				{
				$_SESSION['sante'] = 0;
				}
			$sql = 'UPDATE principal_tbl SET fatigue= "'.$_SESSION['fatigue'].'" , sante="'.$_SESSION['sante'].'" WHERE pseudo= "'.$_SESSION['pseudo'].'" AND num= "'.$_SESSION['num'].'"' ;
			$req = mysql_query($sql);
			$sql = 'UPDATE principal_tbl SET case'.$i.' ="Vide" WHERE id= "'.$_SESSION['id'].'"' ;
			$req = mysql_query($sql);
			print('<p align="center"><strong>Vous venez de fumer un paquet de cigarettes.</strong><br><br>');
			print('Votre nouvel &eacute;tat de sant&eacute; est <i>'.$_SESSION['sante'].'/'.$santemax.'</i>.<br />Votre nouvelle forme est <i>'.$_SESSION['fatigue'].'/'.$fatiguemax.'</i>.<br><br>');
			}
		else
			{
			$l = 1;
			print("<center><strong>Vous &ecirc;tes en bonne forme.</strong></center><br>");
			}
		}
	}
	
mysql_close($db);

?>

</p>
</div>
<?php if($chat=="oui") 	{ if ((date("H")>=21) || (date("H")<=7)) { include("inc_bas_n_c.php"); } else { include("inc_bas_j_c.php"); } } else {	if ((date("H")>=21) || (date("H")<=7)) { include("inc_bas_n.php"); } else { include("inc_bas_j.php"); }	} ?>
<?php include("inc_bas_de_page.php"); ?>
