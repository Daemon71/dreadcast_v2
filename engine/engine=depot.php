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

$sql = 'SELECT type FROM entreprises_tbl WHERE rue= "'.$_SESSION['rue'].'" AND num= "'.$_SESSION['num'].'"' ;
$req = mysql_query($sql);
$type = mysql_result($req,0,type);

if($type!="banque")
	{
	print('<meta http-equiv="refresh" content="0 ; url=http://v2.dreadcast.net/ingame/engine.php"> ');
	exit();
	}

$code = $_SERVER['QUERY_STRING'];

$sql = 'SELECT * FROM principal_tbl WHERE id= "'.$_SESSION['id'].'"' ;
$req = mysql_query($sql);
$_SESSION['credits'] = mysql_result($req,0,credits);
$_SESSION['case1'] = mysql_result($req,0,case1); 
$_SESSION['case2'] = mysql_result($req,0,case2); 
$_SESSION['case3'] = mysql_result($req,0,case3); 
$_SESSION['case4'] = mysql_result($req,0,case4); 
$_SESSION['case5'] = mysql_result($req,0,case5); 
$_SESSION['case6'] = mysql_result($req,0,case6); 

$sql = 'SELECT mdp FROM comptes_tbl WHERE code= "'.$code.'"' ;
$req = mysql_query($sql);

if(mysql_result($req,0,mdp)!=$_SESSION['mdpcompte'])
	{
	print('<meta http-equiv="refresh" content="0 ; url=http://v2.dreadcast.net/ingame/engine=mdpcompte.php?'.$code.'"> ');
	exit();
	}

mysql_close($db);
?>
<?php if ((date("H")>=21) || (date("H")<=7)) { include("inc_haut_n.php"); } else { include("inc_haut_j.php");} ?>

<div id="haut">
	<?php include("inc_entete.php"); ?>
	<div class="haut_aligngauche">
		<p>
		<div class="titrepage">
			Banque
		</div>
		<b class="module4ie"><a href="engine=compte.php?<?php print(''.$code.''); ?>" class="module4"><img src="im_objets/icon_retour.gif" alt="Retour" />Retour</a></b>
		</p>
	</div>
</div>
<div id="centre_banque">

	<p id="location">Cr&eacute;dits en poche : <span><?php print($_SESSION['credits']); ?></span></p>

	<br /><br />
	
	<p id="textelse2"><em>D&eacute;poser un objet dans votre compte co&ucirc;te 15 Cr&eacute;dits.<br />
          Cliquez sur l'objet pour le d&eacute;poser.</em></p>
		
        <table width="80%"  border="0" align="center" cellpadding="3" cellspacing="5">
          <tr>
            <?php 
$db = mysql_connect('localhost', 'CENSURE', 'CENSURE') or die("Erreur de connexion au serveur des messages.");
mysql_select_db('CENSURE',$db) or die("Erreur de connexion a la base de donnees des messages.");

for($i=1; $i != 7 ; $i++) 
	{
	if($i==4)
		{
		print('</tr><tr>');
		}
	if($_SESSION['case'.$i.'']!="Vide")
		{
		print('<td><div align="center">');
		
		$sql = 'SELECT image,url FROM objets_tbl WHERE nom= "'.$_SESSION['case'.$i.''].'"' ;
		$req = mysql_query($sql);
		$image = mysql_result($req,0,image); 
		$url = mysql_result($req,0,url); 
		print('<p align="center"><a href="engine=depotf.php?code='.$code.'&case='.$i.'"><img src="http://v2.dreadcast.net/ingame/im_objets/'.$image.'" border="0"></a></p>');
		print('</div></td>');
		}
	else
		{
		print('<td><div align="center">');
		
		$sql = 'SELECT image,url FROM objets_tbl WHERE nom= "'.$_SESSION['case'.$i.''].'"' ;
		$req = mysql_query($sql);
		$image = mysql_result($req,0,image); 
		$url = mysql_result($req,0,url); 
		print('<p align="center"><img src="http://v2.dreadcast.net/ingame/im_objets/'.$image.'" border="0"></p>');
		print('</div></td>');
		}
	}

mysql_close($db);
?>
		</tr>
        </table>
</div>
<?php if($chat=="oui") 	{ if ((date("H")>=21) || (date("H")<=7)) { include("inc_bas_n_c.php"); } else { include("inc_bas_j_c.php"); } } else {	if ((date("H")>=21) || (date("H")<=7)) { include("inc_bas_n.php"); } else { include("inc_bas_j.php"); }	} ?>
<?php include("inc_bas_de_page.php"); ?>
