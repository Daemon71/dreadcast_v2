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
			Recherche de Police
		</div>
		<b class="module4ie"><a href="engine=services.php" class="module4"><img src="im_objets/icon_retour.gif" alt="Retour" />Retour</a></b>
</p>
	</div>
</div>
<div id="centre">
<p>
<div class="messagesvip">

<?php
$db = mysql_connect('localhost', 'CENSURE', 'CENSURE') or die("Erreur de connexion au serveur des messages.");
mysql_select_db('CENSURE',$db) or die("Erreur de connexion a la base de donnees des messages.");

$sql = 'SELECT type,entreprise,points FROM principal_tbl WHERE id= "'.$_SESSION['id'].'"' ;
$req = mysql_query($sql);
$_SESSION['poste'] = mysql_result($req,0,type); 
$_SESSION['entreprise'] = mysql_result($req,0,entreprise); 
$_SESSION['points'] = mysql_result($req,0,points); 

$sql = 'SELECT bdd FROM `e_'.str_replace(" ","_",''.$_SESSION['entreprise'].'').'_tbl` WHERE poste= "'.$_SESSION['poste'].'"' ;
$req = mysql_query($sql);
$bdd = mysql_result($req,0,bdd); 

$sql = 'SELECT type,num,rue FROM entreprises_tbl WHERE nom= "'.$_SESSION['entreprise'].'"' ;
$req = mysql_query($sql);
$type = mysql_result($req,0,type); 
$rueent = mysql_result($req,0,rue); 
$nument = mysql_result($req,0,num); 

if($bdd=="") { print('<meta http-equiv="refresh" content="0 ; url=http://v2.dreadcast.net/ingame/engine.php"> '); exit(); } if(($type!="police") && ($type!="di2rco") && ($type!="prison")) { print('<meta http-equiv="refresh" content="0 ; url=http://v2.dreadcast.net/ingame/engine.php"> '); exit(); }

if($_SERVER['QUERY_STRING']!="")
	{
	$_SESSION['nomrech'] = str_replace("%20"," ",''.$_SERVER['QUERY_STRING'].'');
	}

if((ucwords($_SESSION['rue'])!=ucwords($rueent)) || ($_SESSION['num']!=$nument))
	{
	print('<strong>Vous devez &ecirc;tre sur place pour pouvoir acc&eacute;der &agrave; cette rubrique.</center>'); 
	$l = 1;
	}

$sql = 'SELECT id,race,sexe,age,taille,action FROM principal_tbl WHERE pseudo= "'.$_SESSION['nomrech'].'"' ;
$req = mysql_query($sql);
$res = mysql_num_rows($req); 

if($res>0)
	{
	if((mysql_result($req,0,action)=="mort") && ($l!=1))
		{
		print('<strong><i>Citoyen d&eacute;c&eacute;d&eacute;.</i></strong></p><p align="center">');
		$agerech = "?";
		$taillerech = "?";
		$sexerech = "?";
		$racerech = "?";
		$numrech = 0;
		$typerech = "?";
		$die = "?";
		$entrepriserech = "?";
		$l = 1;
		}
	}
?>
    Casier judiciaire du citoyen <strong><?php print(''.$_SESSION['nomrech'].''); ?></strong> :
        <p align="center">
<?php
$db = mysql_connect('localhost', 'CENSURE', 'CENSURE') or die("Erreur de connexion au serveur des messages.");
mysql_select_db('CENSURE',$db) or die("Erreur de connexion a la base de donnees des messages.");

$sql = 'SELECT * FROM casiers_tbl WHERE pseudo= "'.$_SESSION['nomrech'].'"ORDER BY datea DESC' ;
$req = mysql_query($sql);
$res = mysql_num_rows($req);

if($res>0)
	{
	print('<table width="90%" bgcolor="#FFFFFF" border="1" align="center" cellpadding="0" cellspacing="0">
				<tr bgcolor="#B6B6B6">
				  <th scope="col"><div align="center">Date</div></th>
				  <th scope="col"><div align="center">Arreté par</div></th>
				  <th scope="col"><div align="center">Motif</div></th>
				</tr>');
	for($i=0;$i!=$res;$i++)
		{
		$idc = mysql_result($req,$i,id);
		$datea = mysql_result($req,$i,datea);
		$policier = mysql_result($req,$i,policier);
		$motif = mysql_result($req,$i,raison);
		print('<tr>
				  <td><div align="center">'.date('d/m/Y à H\hi', $datea).'</div></td>
				  <td><div align="center">'.$policier.'</div></td>
				  <td><div align="center">'.$motif.'</div></td>
				</tr>');
		}
	print('</table>');
	}
else
	{
	print('<i>Casier judiciaire vierge</i>');
	}

mysql_close($db);
?>

</div>
</p>
</div>
<?php if($chat=="oui") 	{ if ((date("H")>=21) || (date("H")<=7)) { include("inc_bas_n_c.php"); } else { include("inc_bas_j_c.php"); } } else {	if ((date("H")>=21) || (date("H")<=7)) { include("inc_bas_n.php"); } else { include("inc_bas_j.php"); }	} ?>
<?php include("inc_bas_de_page.php"); ?>
