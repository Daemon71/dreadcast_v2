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
			Vol &agrave; la tire
		</div>
		<b class="module4ie"><a href="engine.php" class="module4"><img src="im_objets/icon_retour.gif" alt="Retour" />Retour</a></b>
</p>
	</div>
</div>
<div id="centre">
<p>

<?php
$db = mysql_connect('localhost', 'CENSURE', 'CENSURE') or die("Erreur de connexion au serveur.");
mysql_select_db('CENSURE',$db) or die("Erreur de connexion a la base de donnees.");

$sql = 'SELECT id,rue,num,action,fatigue,vol FROM principal_tbl WHERE pseudo= "'.$_SESSION['pseudo'].'"' ;
$req = mysql_query($sql);
$_SESSION['id'] = mysql_result($req,0,id);
$_SESSION['lieu'] = mysql_result($req,0,rue);
$_SESSION['num'] = mysql_result($req,0,num);
$_SESSION['action'] = mysql_result($req,0,action);
$_SESSION['fatigue'] = mysql_result($req,0,fatigue);
$_SESSION['vol'] = mysql_result($req,0,vol);

if($_SESSION['num'] <= 0)
	{
	$num = 0;
	$lieu = "Rue";
	}
else
	{
	$num = $_SESSION['num'];
	$lieu = $_SESSION['lieu'];
	}
$sql = 'SELECT code FROM lieu_tbl WHERE rue= "'.$lieu.'" AND num= "'.$num.'"' ;
$req = mysql_query($sql);
$codounet = mysql_result($req,0,code);

if($_SESSION['code']!=$codounet)
	{
	mysql_close($db);
	print('<meta http-equiv="refresh" content="0 ; url=http://v2.dreadcast.net/ingame/engine.php"> ');
	exit();
	}

if($_SESSION['v'.$_GET['cible'].'']!=1 || $_SESSION['distance']!=1)
	{
	mysql_close($db);
	print('<meta http-equiv="refresh" content="0 ; url=http://v2.dreadcast.net/ingame/engine.php"> ');
	exit();
	}
	
$sql = 'SELECT pseudo,case1,case2,case3,case4,case5,case6,num,rue FROM principal_tbl WHERE id= "'.$_GET['cible'].'"' ;
$req = mysql_query($sql);
$cible = mysql_result($req,0,pseudo);
$ruec = mysql_result($req,0,rue);
$numc = mysql_result($req,0,num);
$_SESSION['case1c'] = mysql_result($req,0,case1);
$_SESSION['case2c'] = mysql_result($req,0,case2);
$_SESSION['case3c'] = mysql_result($req,0,case3);
$_SESSION['case4c'] = mysql_result($req,0,case4);
$_SESSION['case5c'] = mysql_result($req,0,case5);
$_SESSION['case6c'] = mysql_result($req,0,case6);

if($_SESSION['fatigue']<10)
	{
	print('<br />Il est impossible de voler <strong>'.$cible.'</strong> car vous êtes trop fatigué.');
	}
elseif(!estVisible($cible,25))
	{
	print('<br />Il est impossible de voler <strong>'.$cible.'</strong> car il n\'est plus au même endroit que vous.');
	}
else
	{
	$_SESSION['fatigue'] = $_SESSION['fatigue'] - 5;
	augmenter_statistique($_SESSION['id'],"vol",$_SESSION['vol']);
	$sql = 'UPDATE principal_tbl SET fatigue= "'.$_SESSION['fatigue'].'" WHERE id= "'.$_SESSION['id'].'"' ;
	$req = mysql_query($sql);
	print('<br />Vous parvenez à voler <strong>'.$cible.'</strong>.');
	print('<br />Il ne se rend compte de rien.<br />');
	print('<table width="50%" align="center" border="0" cellpadding="3" cellspacing="5"><tr>');
	for($i=1; $i != 7 ; $i++) 
		{
		if($i==4)
			{
			print('</tr><tr>');
			}
		print('<td>');
		
		if($_SESSION['case'.$i.'c']!="Vide")
			{
			$sql = 'SELECT image,url,type FROM objets_tbl WHERE nom= "'.$_SESSION['case'.$i.'c'].'"' ;
			$req = mysql_query($sql);
			$res = mysql_num_rows($req);
			if($res!=0)
				{
				$image = mysql_result($req,0,image); 
				$url = mysql_result($req,0,url); 
				$type = mysql_result($req,0,type); 
				}
			if($type!="imp")
				{
				print('<p align="center"><a href="engine=volf.php?cible='.$cible.'&case='.$i.'"><img src="http://v2.dreadcast.net/ingame/im_objets/'.$image.'" border="0"></a></p>');
				}
			else
				{
				print('<p align="center"><img src="http://v2.dreadcast.net/ingame/im_objets/imp/'.$image.'" border="0"></p>');
				}
			}
		else
			{
			print('<p align="center"><img src="http://v2.dreadcast.net/ingame/im_objets/vide.jpg" border="0"></p>');
			}
		print('</center></td>');
		}
	print('</tr></table>');
	}

mysql_close($db);
?>        

</p>
</div>
<?php if($chat=="oui") 	{ if ((date("H")>=21) || (date("H")<=7)) { include("inc_bas_n_c.php"); } else { include("inc_bas_j_c.php"); } } else {	if ((date("H")>=21) || (date("H")<=7)) { include("inc_bas_n.php"); } else { include("inc_bas_j.php"); }	} ?>
<?php include("inc_bas_de_page.php"); ?>
