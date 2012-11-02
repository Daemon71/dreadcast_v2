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
			Billet Shanghai !
		</div>
		<b class="module4ie"><a href="engine=inventaire.php" class="module4"><img src="im_objets/icon_retour.gif" alt="Retour" />Retour</a></b>
</p>
	</div>
</div>
<div id="centre">
<p><br />Grattez le milieux du billet Shanghai et découvrez votre gain.<br /><br /><br />
<?php 

$db = mysql_connect('localhost', 'CENSURE', 'CENSURE') or die("Erreur de connexion au serveur des messages.");
mysql_select_db('CENSURE',$db) or die("Erreur de connexion a la base de donnees des messages.");

$sql = 'SELECT case1,case2,case3,case4,case5,case6,credits FROM principal_tbl WHERE id= "'.$_SESSION['id'].'"' ;
$req = mysql_query($sql);
$_SESSION['case1'] = mysql_result($req,0,case1);
$_SESSION['case2'] = mysql_result($req,0,case2);
$_SESSION['case3'] = mysql_result($req,0,case3);
$_SESSION['case4'] = mysql_result($req,0,case4);
$_SESSION['case5'] = mysql_result($req,0,case5);
$_SESSION['case6'] = mysql_result($req,0,case6);
$_SESSION['credits'] = mysql_result($req,0,credits);

for($p=1; $p != 7; $p++)
	{
	if(($_SESSION['case'.$p.'']=="Shanghai") && ($l!=1))
		{
		$l = 1;
		$sql = 'UPDATE principal_tbl SET case'.$p.'="Vide" WHERE id= "'.$_SESSION['id'].'"' ;
		$req = mysql_query($sql);
		}
	}
	
mysql_close($db);

if($l==1)
	{
	$i = mt_rand(0,500);
	}
else
	{
	print('<meta http-equiv="refresh" content="0 ; url=http://v2.dreadcast.net/ingame/engine=inventaire.php"> ');
	exit();
	}

?>
<object classid="clsid:d27cdb6e-ae6d-11cf-96b8-444553540000" codebase="http://download.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version=6,0,0,0" width="338" height="164" id="billet" align="middle">
<param name="allowScriptAccess" value="sameDomain" />
<param name="allowFullScreen" value="false" />
<param name="movie" value="billet.swf" />
<param name="FlashVars" value="resultat=<?php echo''.$i.'';?>">
<param name="quality" value="high" />
<param name="bgcolor" value="#ffffff" />	
<embed src="billet.swf" FlashVars="resultat=<?php echo''.$i.'';?>" quality="high" bgcolor="#ffffff" width="338" height="164" name="billet" align="middle" allowScriptAccess="sameDomain" allowFullScreen="false" type="application/x-shockwave-flash" pluginspage="http://www.macromedia.com/go/getflashplayer" />
</object>

<?php 

if($i<60)
	{
	$_SESSION['cashvalue'] = 50;
	$_SESSION['credits'] = $_SESSION['credits'] + 50;
	}
elseif($i<85)
	{
	$_SESSION['cashvalue'] = 60;
	$_SESSION['credits'] = $_SESSION['credits'] + 60;
	}
elseif($i<100)
	{
	$_SESSION['cashvalue'] = 70;
	$_SESSION['credits'] = $_SESSION['credits'] + 70;
	}
elseif($i<115)
	{
	$_SESSION['cashvalue'] = 80;
	$_SESSION['credits'] = $_SESSION['credits'] + 80;
	}
elseif($i<130)
	{
	$_SESSION['cashvalue'] = 90;
	$_SESSION['credits'] = $_SESSION['credits'] + 90;
	}
elseif($i<145)
	{
	$_SESSION['cashvalue'] = 100;
	$_SESSION['credits'] = $_SESSION['credits'] + 100;
	}
elseif($i<155)
	{
	$_SESSION['cashvalue'] = 150;
	$_SESSION['credits'] = $_SESSION['credits'] + 150;
	}
elseif($i<163)
	{
	$_SESSION['cashvalue'] = 300;
	$_SESSION['credits'] = $_SESSION['credits'] + 300;
	}
elseif($i<167)
	{
	$_SESSION['cashvalue'] = 600;
	$_SESSION['credits'] = $_SESSION['credits'] + 600;
	}
elseif($i<168)
	{
	$_SESSION['cashvalue'] = 1000;
	$_SESSION['credits'] = $_SESSION['credits'] + 1000;
	}
else
	{
	$_SESSION['cashvalue'] = 0;	
	}
	
$db = mysql_connect('localhost', 'CENSURE', 'CENSURE') or die("Erreur de connexion au serveur des messages.");
mysql_select_db('CENSURE',$db) or die("Erreur de connexion a la base de donnees des messages.");

$sql = 'UPDATE principal_tbl SET credits="'.$_SESSION['credits'].'" WHERE id= "'.$_SESSION['id'].'"' ;
$req = mysql_query($sql);
$sql3 = 'SELECT valeur FROM donnees_tbl WHERE objet= "sortieSHANG"';
$req3 = mysql_query($sql3);
$sortie = mysql_result($req3,0,valeur);
$sortie = $sortie + $_SESSION['cashvalue'];
$sql4 = 'UPDATE donnees_tbl SET valeur="'.$sortie.'" WHERE objet= "sortieSHANG"' ;
$req4 = mysql_query($sql4);
mysql_close($db);

?> 

</p>
<a href="engine=billetvalid.php">Valider</a>
</div>
<?php if($chat=="oui") 	{ if ((date("H")>=21) || (date("H")<=7)) { include("inc_bas_n_c.php"); } else { include("inc_bas_j_c.php"); } } else {	if ((date("H")>=21) || (date("H")<=7)) { include("inc_bas_n.php"); } else { include("inc_bas_j.php"); }	} ?>
<?php include("inc_bas_de_page.php"); ?>
