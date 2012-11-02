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
			Chargeur
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

$sql = 'SELECT arme,case1,case2,case3,case4,case5,case6 FROM principal_tbl WHERE id= "'.$_SESSION['id'].'"' ;
$req = mysql_query($sql);
$_SESSION['arme'] = mysql_result($req,0,arme);
$_SESSION['case1'] = mysql_result($req,0,case1);
$_SESSION['case2'] = mysql_result($req,0,case2);
$_SESSION['case3'] = mysql_result($req,0,case3);
$_SESSION['case4'] = mysql_result($req,0,case4);
$_SESSION['case5'] = mysql_result($req,0,case5);
$_SESSION['case6'] = mysql_result($req,0,case6);

$gen = strstr($_SESSION['arme'],"-");

if(ereg("Lance roquette",$_SESSION['arme']))
	{
	$max = 0;
	}
elseif(ereg("Lance flammes",$_SESSION['arme']))
	{
	$max = 0;
	}
elseif(ereg("M16",$_SESSION['arme']))
	{
	$max = 30;
	}
elseif(ereg("FAMAS",$_SESSION['arme']))
	{
	$max = 25;
	}
elseif(ereg("FAMAS",$_SESSION['arme']))
	{
	$max = 25;
	}
elseif(ereg("Fusil long",$_SESSION['arme']))
	{
	$max = 25;
	}
elseif(ereg("Minigun",$_SESSION['arme']))
	{
	$max = 50;
	}
elseif(ereg("N91T3",$_SESSION['arme']))
	{
	$max = 30;
	}
elseif(ereg("Runner",$_SESSION['arme']))
	{
	$max = 3;
	}
elseif(ereg("Fusil a lunette",$_SESSION['arme']))
	{
	$max = 5;
	}
elseif(ereg("UZI",$_SESSION['arme']))
	{
	$max = 25;
	}
elseif(ereg("Fusil a pompe",$_SESSION['arme']))
	{
	$max = 8;
	}
elseif(ereg("Peard",$_SESSION['arme']))
	{
	$max = 6;
	}
elseif(ereg("Fusil de chasse",$_SESSION['arme']))
	{
	$max = 8;
	}
elseif(ereg("MP5",$_SESSION['arme']))
	{
	$max = 30;
	}
elseif(ereg("AK47",$_SESSION['arme']))
	{
	$max = 50;
	}
else
	{
	$max = 9;
	}

$sql = 'SELECT chargeur,modif1,modif2,modif3 FROM armes_tbl WHERE idarme= "'.$gen.'"';
$req = mysql_query($sql);
$res = mysql_num_rows($req);
if($res>0)
	{
	$chargeur = mysql_result($req,0,chargeur);
	$modif1 = mysql_result($req,0,modif1);
	$modif2 = mysql_result($req,0,modif2);
	$modif3 = mysql_result($req,0,modif3);
	if($modif1==4 || $modif2==4 || $modif3==4) { $max = $max * 2; }
	for($i=1; $i != 7; $i++)
		{
		if(($_SESSION['case'.$i.'']=="Chargeur") && ($l!=1))
			{
			if($chargeur==$max)
				{
				$l = 1;
				print("<center><strong>Le chargeur de votre arme est déjà plein.</strong></center><br>");
				}
			else
				{
				$l = 1;
				$sql2 = 'UPDATE principal_tbl SET case'.$i.' ="Vide" WHERE id= "'.$_SESSION['id'].'"' ;
				$req2 = mysql_query($sql2);
				$sql2 = 'UPDATE armes_tbl SET chargeur="'.$max.'" WHERE idarme= "'.$gen.'"' ;
				$req2 = mysql_query($sql2);
				print('<p align="center"><strong>Vous venez d\'utiliser un chargeur.</strong><br />');
				print('Votre arme possède de nouveau '.$max.' balles prêtes.');
				}
			}
		}
	}
else
	{
	print("<center><strong>Vous devez avoir une arme à feu entre les mains pour pouvoir la charger.</strong></center><br>");
	}
	
mysql_close($db);

?>

</p>
</div>
<?php if($chat=="oui") 	{ if ((date("H")>=21) || (date("H")<=7)) { include("inc_bas_n_c.php"); } else { include("inc_bas_j_c.php"); } } else {	if ((date("H")>=21) || (date("H")<=7)) { include("inc_bas_n.php"); } else { include("inc_bas_j.php"); }	} ?>
<?php include("inc_bas_de_page.php"); ?>
