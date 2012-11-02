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

$sql = 'SELECT id,rue,num FROM principal_tbl WHERE pseudo= "'.$_SESSION['pseudo'].'"' ;
$req = mysql_query($sql);
$_SESSION['id'] = mysql_result($req,0,id);
$_SESSION['lieu'] = mysql_result($req,0,rue);
$_SESSION['num'] = mysql_result($req,0,num);

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

$_SESSION['cible'] = str_replace("%20"," ",''.$_SERVER['QUERY_STRING'].'');

$sql = 'SELECT id,rue,num,sante,action FROM principal_tbl WHERE pseudo= "'.$_SESSION['cible'].'"' ;
$req = mysql_query($sql);
$_SESSION['idc'] = mysql_result($req,0,id);
$_SESSION['numc'] = mysql_result($req,0,num);
$_SESSION['santec'] = mysql_result($req,0,sante);
$_SESSION['actionc'] = mysql_result($req,0,action);

$sql = 'SELECT case1,case2,case3,case4,case5,case6,arme,vetements,objet FROM principal_tbl WHERE id= "'.$_SESSION['idc'].'"' ;
$req = mysql_query($sql);
$_SESSION['case1c'] = mysql_result($req,0,case1); 
$_SESSION['case2c'] = mysql_result($req,0,case2); 
$_SESSION['case3c'] = mysql_result($req,0,case3); 
$_SESSION['case4c'] = mysql_result($req,0,case4); 
$_SESSION['case5c'] = mysql_result($req,0,case5); 
$_SESSION['case6c'] = mysql_result($req,0,case6); 
$_SESSION['objetc'] = mysql_result($req,0,objet); 
$_SESSION['armec'] = mysql_result($req,0,arme); 
$_SESSION['vetementsc'] = mysql_result($req,0,vetements); 

mysql_close($db);
?>
<?php if ((date("H")>=21) || (date("H")<=7)) { include("inc_haut_n.php"); } else { include("inc_haut_j.php");} ?>

<div id="haut">
	<?php include("inc_entete.php"); ?>
	<div class="haut_aligngauche">
		<p>
		<div class="titrepage">
			Fouille d'un cadavre
		</div>
		<b class="module4ie"><a href="engine.php" class="module4"><img src="im_objets/icon_retour.gif" alt="Retour" />Retour</a></b>
</p>
	</div>
</div>
<div id="centre">
<p>

<em>Vous &ecirc;tes en train de fouiller <strong><? print(''.$_SESSION['cible'].'') ?></strong> ...<br>
          </em><em>Cliquez sur un objet pour le lui prendre.<br>
          Il faut un emplacement vide dans votre inventaire pour prendre un objet. </em></p>
		<p align="center">
        <table width="50%"  border="0" align="center" cellpadding="3" cellspacing="5">
          <tr>
            <?php 
$db = mysql_connect('localhost', 'CENSURE', 'CENSURE') or die("Erreur de connexion au serveur des messages.");
mysql_select_db('CENSURE',$db) or die("Erreur de connexion a la base de donnees des messages.");

if(!estVisible($_SESSION['cible'],25) || $_SESSION['santec']!=0 || $_SESSION['actionc']=="mort")
	{
	print('<meta http-equiv="refresh" content="0 ; url=http://v2.dreadcast.net/ingame/engine=liste.php"> ');
	exit();
	}

if($_SESSION['armec']!="Aucune")
	{
	$sql = 'SELECT image,url,type FROM objets_tbl WHERE nom= "'.$_SESSION['armec'].'"' ;
	$req = mysql_query($sql);
	$image = mysql_result($req,0,image); 
	$url = mysql_result($req,0,url);
	$type = mysql_result($req,0,type);
	$p = $p + 1;
	if($type!="imp")
		{
		print('<td><p align="center"><a href="engine=fouillerf.php?cible='.$_SESSION['cible'].'&arme=1"><img src="http://v2.dreadcast.net/ingame/im_objets/'.$image.'" border="0"></a></p></td>');
		}
	else
		{
		print('<td><p align="center"><img src="http://v2.dreadcast.net/ingame/im_objets/imp/'.$image.'" border="0"></p></td>');
		}
	if($p==4)
		{
		print('</tr><tr>');
		}
	}

if($_SESSION['vetementsc']!="Veste")
	{
	$sql = 'SELECT image,url,type FROM objets_tbl WHERE nom= "'.$_SESSION['vetementsc'].'"' ;
	$req = mysql_query($sql);
	$image = mysql_result($req,0,image); 
	$url = mysql_result($req,0,url);
	$type = mysql_result($req,0,type);
	$p = $p + 1;
	if($type!="imp")
		{
		print('<td><p align="center"><a href="engine=fouillerf.php?cible='.$_SESSION['cible'].'&vetements=1"><img src="http://v2.dreadcast.net/ingame/im_objets/'.$image.'" border="0"></a></p></td>');
		}
	else
		{
		print('<td><p align="center"><img src="http://v2.dreadcast.net/ingame/im_objets/imp/'.$image.'" border="0"></p></td>');
		}
	if($p==4)
		{
		print('</tr><tr>');
		}
	}

if($_SESSION['objetc']!="Aucun")
	{
	$sql = 'SELECT image,url,type FROM objets_tbl WHERE nom= "'.$_SESSION['objetc'].'"' ;
	$req = mysql_query($sql);
	$image = mysql_result($req,0,image); 
	$url = mysql_result($req,0,url);
	$type = mysql_result($req,0,type);
	$p = $p + 1;
	if($type!="imp")
		{
		print('<td><p align="center"><a href="engine=fouillerf.php?cible='.$_SESSION['cible'].'&objet=1"><img src="http://v2.dreadcast.net/ingame/im_objets/'.$image.'" border="0"></a></p></td>');
		}
	else
		{
		print('<td><p align="center"><img src="http://v2.dreadcast.net/ingame/im_objets/imp/'.$image.'" border="0"></p></td>');
		}
	if($p==4)
		{
		print('</tr><tr>');
		}
	}


$p = 0;
for($i=1; $i != 7 ; $i++) 
	{
	if($_SESSION['case'.$i.'c']!="Vide")
		{
		$sql = 'SELECT image,url,type FROM objets_tbl WHERE nom= "'.$_SESSION['case'.$i.'c'].'"' ;
		$req = mysql_query($sql);
		$image = mysql_result($req,0,image); 
		$url = mysql_result($req,0,url);
		$type = mysql_result($req,0,type);
		$p = $p + 1;
		if($type!="imp")
			{
			print('<td><p align="center"><a href="engine=fouillerf.php?cible='.$_SESSION['cible'].'&case='.$i.'"><img src="http://v2.dreadcast.net/ingame/im_objets/'.$image.'" border="0"></a></p></td>');
			}
		else
			{
			print('<td><p align="center"><img src="http://v2.dreadcast.net/ingame/im_objets/imp/'.$image.'" border="0"></p></td>');
			}
		if($p==3)
			{
			print('</tr><tr>');
			}
		}
	}
	
mysql_close($db);
?>
</tr>
        </table>
		
		
</p>
</div>
<?php if($chat=="oui") 	{ if ((date("H")>=21) || (date("H")<=7)) { include("inc_bas_n_c.php"); } else { include("inc_bas_j_c.php"); } } else {	if ((date("H")>=21) || (date("H")<=7)) { include("inc_bas_n.php"); } else { include("inc_bas_j.php"); }	} ?>
<?php include("inc_bas_de_page.php"); ?>
