<?php 
session_start(); 
if($_SESSION['id']=="")
	{
	print('<meta http-equiv="refresh" content="0 ; url=http://v2.dreadcast.net/"> ');
	exit();
	}

$db = mysql_connect('localhost', 'CENSURE', 'CENSURE') or die("Erreur de connexion au serveur des messages.");
mysql_select_db('CENSURE',$db) or die("Erreur de connexion a la base de donnees des messages.");
$sql = 'SELECT allopass FROM principal_tbl WHERE pseudo= "'.$_SESSION['pseudo'].'"' ;
$req = mysql_query($sql);
$_SESSION['allopass'] = mysql_result($req,0,allopass);
mysql_close($db);
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
if($_SESSION['allopass']<5)
	{
	print('<meta http-equiv="refresh" content="0 ; url=http://v2.dreadcast.net/ingame/engine=inventaire.php"> ');
	exit();
	}
?>
<?php if ((date("H")>=21) || (date("H")<=7)) { include("inc_haut_n.php"); } else { include("inc_haut_j.php");} ?>

<div id="haut">
	<?php include("inc_entete.php"); ?>
	<div class="haut_aligngauche">
		<p>
		<div class="titrepage">
			Cadeau
		</div>
		<b class="module4ie"><a href="engine=allopass.php" class="module4"><img src="im_objets/icon_retour.gif" alt="Retour" />Retour</a></b>
		</p>
	</div>
</div>
<div id="centre">
<p>

<?php
$db = mysql_connect('localhost', 'CENSURE', 'CENSURE') or die("Erreur de connexion au serveur.");
mysql_select_db('CENSURE',$db) or die("Erreur de connexion a la base de donnees.");
$i = rand(1,100);
$l = 0;
$_SESSION['allopass'] = 0;
if($i==1)
	{
	$id = 1;
	for($p=1;$p!=7;$p++)
		{
		if($_SESSION['case'.$p.'']=="Vide" && $l!=1)
			{
			$l = 1;
			$_SESSION['case'.$p.''] = "Armure de combat";
			$sql = 'UPDATE principal_tbl SET case'.$p.'= "Armure de combat" WHERE pseudo= "'.$_SESSION['pseudo'].'"' ;
			$req = mysql_query($sql);	
			}
		}
	if($l==0) { $i = 51; }
	}
if($i>=2 && $i<=5)
	{
	$id = 2;
	for($p=1;$p!=7;$p++)
		{
		if($_SESSION['case'.$p.'']=="Vide" && $l!=1)
			{
			$l = 1;
			$_SESSION['case'.$p.''] = "Cyber Deck";
			$sql = 'UPDATE principal_tbl SET case'.$p.'= "Cyber Deck" WHERE pseudo= "'.$_SESSION['pseudo'].'"' ;
			$req = mysql_query($sql);	
			}
		}
	if($l==0) { $i = 51; }
	}
if($i>=6 && $i<=20)
	{
	$id = 3;
	for($p=1;$p!=7;$p++)
		{
		if($_SESSION['case'.$p.'']=="Vide" && $l!=1)
			{
			$l = 1;
			$_SESSION['case'.$p.''] = "Masse energetique";
			$sql = 'UPDATE principal_tbl SET case'.$p.'= "Masse energetique" WHERE pseudo= "'.$_SESSION['pseudo'].'"' ;
			$req = mysql_query($sql);	
			}
		}
	if($l==0) { $i = 51; }
	}
if($i>=21 && $i<=50)
	{
	$id = 4;
	$_SESSION['credits'] = $_SESSION['credits'] + 1000;
	$sql = 'UPDATE principal_tbl SET credits= "'.$_SESSION['credits'].'" WHERE pseudo= "'.$_SESSION['pseudo'].'"' ;
	$req = mysql_query($sql);	
	}
if($i>=51)
	{
	$id = 5;
	$_SESSION['credits'] = $_SESSION['credits'] + 2000;
	$sql = 'UPDATE principal_tbl SET credits= "'.$_SESSION['credits'].'" WHERE pseudo= "'.$_SESSION['pseudo'].'"' ;
	$req = mysql_query($sql);	
	}
$sql = 'UPDATE principal_tbl SET allopass=0 WHERE pseudo= "'.$_SESSION['pseudo'].'"' ;
$req = mysql_query($sql);	
mysql_close($db);


print('<div style="position:absolute;left:25px;top:113px;width:450px;height:270px;">
<script type="text/javascript" src="swfobject.js"></script>
<span id="flashcontent">Veuillez patienter...</span>
<script type="text/javascript">
	var so = new SWFObject("roulette.swf", "roulette", "450", "270", "8", "#FFFFFF");
	so.addParam("AllowScriptAccess","always");
	so.addVariable("id", "'.$id.'");
	so.write("flashcontent");
</script></div>');
?>

</p>
</div>
<?php if($chat=="oui") 	{ if ((date("H")>=21) || (date("H")<=7)) { include("inc_bas_n_c.php"); } else { include("inc_bas_j_c.php"); } } else {	if ((date("H")>=21) || (date("H")<=7)) { include("inc_bas_n.php"); } else { include("inc_bas_j.php"); }	} ?>
<?php include("inc_bas_de_page.php"); ?>
