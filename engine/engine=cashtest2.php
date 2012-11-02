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
			Cash !
		</div>
		<b class="module4ie"><a href="engine=inventaire.php" class="module4"><img src="im_objets/icon_retour.gif" alt="Retour" />Retour</a></b>
</p>
	</div>
</div>
<div id="centre">
<p><br />
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
	if(($_SESSION['case'.$p.'']=="Cash") && ($l!=1))
		{
		$l = 1;
		$sql = 'UPDATE principal_tbl SET case'.$p.'="Vide" WHERE id= "'.$_SESSION['id'].'"' ;
		$req = mysql_query($sql);
		}
	}
	
mysql_close($db);

if($l==1)
	{
	$i = mt_rand(0,300);
	}
else
	{
	print('<meta http-equiv="refresh" content="0 ; url=http://v2.dreadcast.net/ingame/engine=inventaire.php"> ');
	exit();
	}

?>
<object classid="clsid:d27cdb6e-ae6d-11cf-96b8-444553540000" codebase="http://download.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version=6,0,0,0" width="250" height="250" id="cash" align="middle">
<param name="allowScriptAccess" value="sameDomain" />
<param name="allowFullScreen" value="false" />
<param name="movie" value="cash.swf" />
<param name="FlashVars" value="temp0=<?php echo''.$i.'';?>">
<param name="quality" value="high" />
<param name="bgcolor" value="#ffffff" />	
<embed src="cash.swf" FlashVars="temp0=<?php echo''.$i.'';?>" quality="high" bgcolor="#ffffff" width="250" height="250" name="cash" align="middle" allowScriptAccess="sameDomain" allowFullScreen="false" type="application/x-shockwave-flash" pluginspage="http://www.macromedia.com/go/getflashplayer" />
</object>

<?php 

if($i<50)
	{
	$_SESSION['cashvalue'] = 25;
	$_SESSION['credits'] = $_SESSION['credits'] + 25;
	}
elseif($i<75)
	{
	$_SESSION['cashvalue'] = 50;
	$_SESSION['credits'] = $_SESSION['credits'] + 50;
	}
elseif($i<90)
	{
	$_SESSION['cashvalue'] = 150;
	$_SESSION['credits'] = $_SESSION['credits'] + 150;
	}
elseif($i<97)
	{
	$_SESSION['cashvalue'] = 300;
	$_SESSION['credits'] = $_SESSION['credits'] + 300;
	}
elseif($i<100)
	{
	$_SESSION['cashvalue'] = 500;
	$_SESSION['credits'] = $_SESSION['credits'] + 500;
	}
else
	{
	$_SESSION['cashvalue'] = 0;	
	}
	
$db = mysql_connect('localhost', 'CENSURE', 'CENSURE') or die("Erreur de connexion au serveur des messages.");
mysql_select_db('CENSURE',$db) or die("Erreur de connexion a la base de donnees des messages.");

$sql = 'UPDATE principal_tbl SET credits="'.$_SESSION['credits'].'" WHERE id= "'.$_SESSION['id'].'"' ;
$req = mysql_query($sql);

mysql_close($db);

?> 



</p>
<a href="engine=cashvalid.php">Valider</a>
</div>
<?php if($chat=="oui") 	{ if ((date("H")>=21) || (date("H")<=7)) { include("inc_bas_n_c.php"); } else { include("inc_bas_j_c.php"); } } else {	if ((date("H")>=21) || (date("H")<=7)) { include("inc_bas_n.php"); } else { include("inc_bas_j.php"); }	} ?>
<?php include("inc_bas_de_page.php"); ?>
