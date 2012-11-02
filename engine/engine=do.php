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
			Donner un objet
		</div>
		<b class="module4ie"><a href="engine=cible.php?<?php print(''.str_replace("%20"," ",''.$_SERVER['QUERY_STRING'].'').''); ?>" class="module4"><img src="im_objets/icon_retour.gif" alt="Retour" />Retour</a></b>
</p>
	</div>
</div>
<div id="centre">
<p>

<?php	

$db = mysql_connect('localhost', 'CENSURE', 'CENSURE') or die("Erreur de connexion au serveur.");
mysql_select_db('CENSURE',$db) or die("Erreur de connexion a la base de donnees.");

$sql = 'SELECT id,rue,num FROM principal_tbl WHERE pseudo= "'.$_SESSION['pseudo'].'"' ;
$req = mysql_query($sql);
$_SESSION['id'] = mysql_result($req,0,id);
$_SESSION['lieu'] = mysql_result($req,0,rue);
$_SESSION['num'] = mysql_result($req,0,num);

$sql = 'SELECT credits FROM principal_tbl WHERE id= "'.$_SESSION['id'].'"' ;
$req = mysql_query($sql);
$_SESSION['credits'] = mysql_result($req,0,credits);

$_SESSION['cible'] = str_replace("%20"," ",''.$_SERVER['QUERY_STRING'].'');

$sql = 'SELECT id,rue,num FROM principal_tbl WHERE pseudo= "'.$_SESSION['cible'].'"' ;
$req = mysql_query($sql);
$_SESSION['idc'] = mysql_result($req,0,id);
$_SESSION['lieuc'] = mysql_result($req,0,rue);
$_SESSION['numc'] = mysql_result($req,0,num);

//condition DIGICODE
$sql = 'SELECT code FROM lieu_tbl WHERE rue= "'.$_SESSION['lieu'].'" AND num= "'.$_SESSION['num'].'"' ;
$req = mysql_query($sql);
$res = mysql_num_rows($req);
if($res>0)
	{
	$codounet = mysql_result($req,0,code);
	if($_SESSION['code']!=$codounet)
		{
		mysql_close($db);
		print('<meta http-equiv="refresh" content="0 ; url=http://v2.dreadcast.net/ingame/engine.php"> ');
		exit();
		}
	}

if(!estVisible($_SESSION['cible'],25))
	{
	print('<meta http-equiv="refresh" content="0 ; url=http://v2.dreadcast.net/ingame/engine=liste.php"> ');
	exit();
	}

print('<em>Vous avez cibl&eacute; <strong>'.$_SESSION['cible'].'</strong> pour lui donner un objet...<br>
		</em><em>Cliquez sur l\'objet pour lui transmettre. </em>
		<p align="center">
		<table width="80%"  border="0" align="center" cellpadding="3" cellspacing="5">
		<tr>');

$sql = 'SELECT case1,case2,case3,case4,case5,case6 FROM principal_tbl WHERE id= "'.$_SESSION['id'].'"' ;
$req = mysql_query($sql);
$_SESSION['case1'] = mysql_result($req,0,case1); 
$_SESSION['case2'] = mysql_result($req,0,case2); 
$_SESSION['case3'] = mysql_result($req,0,case3); 
$_SESSION['case4'] = mysql_result($req,0,case4); 
$_SESSION['case5'] = mysql_result($req,0,case5); 
$_SESSION['case6'] = mysql_result($req,0,case6); 

for($i=1; $i != 7 ; $i++) 
	{
	if($i==4)
		{
		print('</tr><tr>');
		}
	print('<td>');
	
	$sql = 'SELECT image,url FROM objets_tbl WHERE nom= "'.$_SESSION['case'.$i.''].'"' ;
	$req = mysql_query($sql);
	$image = mysql_result($req,0,image); 
	if($_SESSION['case'.$i.'']!="Vide")
		{
		$url = mysql_result($req,0,url); 
		print('<p align="center"><a href="engine=dof.php?cible='.$_SESSION['cible'].'&case='.$i.'"><img src="http://v2.dreadcast.net/ingame/im_objets/'.$image.'" border="0"></a></p>');
		}
	else
		{
		print('<p align="center"><img src="http://v2.dreadcast.net/ingame/im_objets/'.$image.'" border="0"></p>');
		}
	print('</td>');
	}

mysql_close($db);
?>
</tr></table>

</p>
</div>
<?php if($chat=="oui") 	{ if ((date("H")>=21) || (date("H")<=7)) { include("inc_bas_n_c.php"); } else { include("inc_bas_j_c.php"); } } else {	if ((date("H")>=21) || (date("H")<=7)) { include("inc_bas_n.php"); } else { include("inc_bas_j.php"); }	} ?>
<?php include("inc_bas_de_page.php"); ?>
