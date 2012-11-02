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
			Choisissez votre avatar
		</div>
		<b class="module4ie"><a href="engine=stats.php" class="module4"><img src="im_objets/icon_retour.gif" alt="Retour" />Retour</a></b>
</p>
	</div>
</div>
<div id="centre">
<p>


<?php
$db = mysql_connect('localhost', 'CENSURE', 'CENSURE') or die("Erreur de connexion au serveur des messages.");
mysql_select_db('CENSURE',$db) or die("Erreur de connexion a la base de donnees des messages.");

$sql = 'SELECT avatar FROM principal_tbl WHERE pseudo= "'.$_SESSION['pseudo'].'"' ;
$req = mysql_query($sql);
$_SESSION['avatar'] = mysql_result($req,0,avatar);

if($_SESSION['sexe']=="Masculin")
	{
	$sexea = "m";
	}
elseif($_SESSION['sexe']=="Feminin")
	{
	$sexea = "f";	
	}

$sql = 'SELECT id,image FROM avatars_tbl ORDER BY RAND() LIMIT 120' ;
$req = mysql_query($sql);
$res = mysql_num_rows($req);

print('Afin d\'améliorer votre RP, il est conseillé de ne pas changer d\'avatar trop régulièrement.');
print('<div class="avatars"><!--<table width="100%"  border="0" align="center"><tr><td><div align="center">');
$p = 0;

for($a=0;$a!=$res;$a++)
	{
	$sql1 = 'SELECT id FROM principal_tbl WHERE avatar= "'.mysql_result($req,$a,image).'"' ;
	$req1 = mysql_query($sql1);
	$res1 = mysql_num_rows($req1);
	if($res1==0)
		{
		print('<a href="engine=avatar.php?'.mysql_result($req,$a,image).'"><img src="avatars/'.mysql_result($req,$a,image).'" border="0" /></a> ');
		$p = $p + 1;
		}
	if(($p==6) || ($p==12) || ($p==18) || ($p==24) || ($p==30) || ($p==36) || ($p==42) || ($p==48) || ($p==54) || ($p==60) || ($p==66) || ($p==72) || ($p==78) || ($p==84) || ($p==90) || ($p==96) || ($p==102) || ($p==108) || ($p==114) || ($p==120))
		{
		print('</div></td></tr><tr><td><div align="center">');
		}
	}
print('</td></tr></table>-->');

print('<form name="form1" method="post" action="engine=sendavatar.php"><div align="center">Indiquez l\'adresse de votre avatar dans la zone de saisie.<br />Il est n&eacute;cessaire de choisir un avatar carr&eacute;, de dimension id&eacute;ale 70x70.<br /><br />
		  Lien vers l\'image: 
		  <input name="avatarsend" type="text" size="30">
          <input type="submit" name="Submit" value="Envoyer"></div>
        </form>
        <br />
        Plus d\'informations sur <a href="http://v2.dreadcast.net/wikast/wiki.php?id=67" onclick="window.open(this.href); return false;">le Wiki</a>.');

print('</div');

mysql_close($db);
?>


</p>
</div>
<?php if($chat=="oui") 	{ if ((date("H")>=21) || (date("H")<=7)) { include("inc_bas_n_c.php"); } else { include("inc_bas_j_c.php"); } } else {	if ((date("H")>=21) || (date("H")<=7)) { include("inc_bas_n.php"); } else { include("inc_bas_j.php"); }	} ?>
<?php include("inc_bas_de_page.php"); ?>
