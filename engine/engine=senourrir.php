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

$sql = 'SELECT faim,soif,fatigue,fatigue_max,drogue FROM principal_tbl WHERE pseudo= "'.$_SESSION['pseudo'].'"' ;
$req = mysql_query($sql);
$_SESSION['faim'] = mysql_result($req,0,faim);
$_SESSION['fatigue'] = mysql_result($req,0,fatigue);
$fatiguemax = mysql_result($req,0,fatigue_max);
$_SESSION['drogue'] = mysql_result($req,0,drogue);
$_SESSION['soif'] = mysql_result($req,0,soif);

$sql = 'SELECT code,niveaufrigo FROM lieu_tbl WHERE num= "'.$_SESSION['num'].'" AND rue= "'.$_SESSION['lieu'].'"' ;
$req = mysql_query($sql);
$boncode = mysql_result($req,0,code);
$niveaufrigo = mysql_result($req,0,niveaufrigo);

mysql_close($db);
?>
<?php if ((date("H")>=21) || (date("H")<=7)) { include("inc_haut_n.php"); } else { include("inc_haut_j.php");} ?>

<div id="haut">
	<?php include("inc_entete.php"); ?>
	<div class="haut_aligngauche">
		<p>
		<div class="titrepage">
			Se nourrir
		</div>
		<b class="module4ie"><a href="engine=invlieu.php" class="module4"><img src="im_objets/icon_retour.gif" alt="Retour" />Retour</a></b>
		</p>
	</div>
</div>
<div id="centre">
<p>

<?php 

$db = mysql_connect('localhost', 'CENSURE', 'CENSURE') or die("Erreur de connexion au serveur des messages.");
mysql_select_db('CENSURE',$db) or die("Erreur de connexion a la base de donnees des messages.");

if($_SESSION['drogue']>0) $fatiguemax = drogue($_SESSION['pseudo'],$fatiguemax);

if($_SESSION['num']<=0 || ($_SESSION['lieu']=="Ruelle"))
	{
	}
elseif($_SESSION['code']==$boncode)
	{
	$niveau = $niveaufrigo;
	if($niveau>0)
		{
		for($i=0;$i!=$niveaufrigo;$i++)
			{
			if($_SESSION['faim']<100)
				{
				$_SESSION['fatigue'] = $_SESSION['fatigue'] + 10;
				if($_SESSION['fatigue']>$fatiguemax)
					{
					$_SESSION['fatigue'] = $fatiguemax;
					}
				$_SESSION['faim'] = $_SESSION['faim'] + 5;
				if($_SESSION['faim']>100)
					{
					$_SESSION['faim'] = 100;
					}
				$niveau = $niveau - 1;
				}
			elseif($_SESSION['soif']<100)
				{
				$_SESSION['fatigue'] = $_SESSION['fatigue'] + 10;
				if($_SESSION['fatigue']>$fatiguemax)
					{
					$_SESSION['fatigue'] = $fatiguemax;
					}
				$_SESSION['soif'] = $_SESSION['soif'] + 15;
				if($_SESSION['soif']>100)
					{
					$_SESSION['soif'] = 100;
					}
				$niveau = $niveau - 1;
				}
			}
		$sql = 'UPDATE lieu_tbl SET niveaufrigo= "'.$niveau.'" WHERE num= "'.$_SESSION['num'].'" AND rue= "'.$_SESSION['lieu'].'"' ;
		$req = mysql_query($sql);
		$sql2 = 'UPDATE principal_tbl SET fatigue= "'.$_SESSION['fatigue'].'" , faim= "'.$_SESSION['faim'].'", soif= "'.$_SESSION['soif'].'" WHERE pseudo= "'.$_SESSION['pseudo'].'"' ;
		$req2 = mysql_query($sql2);
		print('<br /><strong>Vous venez de vous nourrir.</strong>');
		}
	else
		{
		print('<br /><strong>Votre réfrigerateur est vide !</strong>');
		}
	}
elseif($_SESSION['code']!=$boncode)
	{
	print('<div align="center"><form action="engine=di.php" name="form1"><p align="center">'); print("<em>Vous devez entrer un digicode valide pour pouvoir accéder à l'inventaire du lieu.</em>"); print(' </p><p align="center">Digicode : <input name="codetest" type="password" id="codetest" size="'.strlen($boncode).'" maxlength="'.strlen($boncode).'"></p><p align="center"><input type="submit" name="Submit" value="Valider"></p></form></div>');
	}

mysql_close($db);
?>

</p>
</div>
<?php if($chat=="oui") 	{ if ((date("H")>=21) || (date("H")<=7)) { include("inc_bas_n_c.php"); } else { include("inc_bas_j_c.php"); } } else {	if ((date("H")>=21) || (date("H")<=7)) { include("inc_bas_n.php"); } else { include("inc_bas_j.php"); }	} ?>
<?php include("inc_bas_de_page.php"); ?>
