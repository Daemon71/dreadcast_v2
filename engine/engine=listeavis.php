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

$sql = 'SELECT type FROM entreprises_tbl WHERE nom= "'.$_SESSION['entreprise'].'"' ;
$req = mysql_query($sql);
$type = mysql_result($req,0,type); 

if($_SESSION['statut'] != "Administrateur"){
if($bdd=="") { print('<meta http-equiv="refresh" content="0 ; url=http://v2.dreadcast.net/ingame/engine.php"> '); exit(); } if(($type!="police") && ($type!="di2rco")) { print('<meta http-equiv="refresh" content="0 ; url=http://v2.dreadcast.net/ingame/engine.php"> '); exit(); }
}

mysql_close($db);
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

<?php 
$db = mysql_connect('localhost', 'CENSURE', 'CENSURE') or die("Erreur de connexion au serveur des messages.");
mysql_select_db('CENSURE',$db) or die("Erreur de connexion a la base de donnees des messages.");
	
if($type=="di2rco")
	{
	print('<strong>Avis de recherches imp&eacute;riaux :<br />');

	$sql = 'SELECT id FROM principal_tbl WHERE di2rco>= "50"' ;
	$req = mysql_query($sql);
	$res = mysql_num_rows($req); 
	
	for($i=0; $i != $res; $i++)
		{
		$ideo = mysql_result($req,$i,id);
		$sql1 = 'SELECT connec,pseudo FROM principal_tbl WHERE id= "'.$ideo.'"' ;
		$req1 = mysql_query($sql1);
		$pseudoo = mysql_result($req1,0,pseudo);
		if(mysql_result($req1,0,connec) == "oui") $connec = '<img src="im_objets/eclair1.gif" border="0" title="Connect&eacute;"> ';
		else $connec = "";
		print($connec.'<a href="engine=police.php?'.$pseudoo.'"> '.$pseudoo.'</a><br />');
		}
	}
	
print('<table width="90%"  border="0" align="center">
<tr>
	<th scope="row"><strong>Avis de recherches prioritaires</strong></th>
	<th scope="row">Avis de recherches</th>
</tr>
<tr>
	<td>');

$sql = 'SELECT id FROM principal_tbl WHERE Police>= "110"' ;
$req = mysql_query($sql);
$res = mysql_num_rows($req); 

for($i=0; $i != $res; $i++)
	{
	$ideo = mysql_result($req,$i,id);
	$sql1 = 'SELECT entreprise FROM principal_tbl WHERE id= "'.$ideo.'"' ;
	$req1 = mysql_query($sql1);
	$travail = mysql_result($req1,0,entreprise);
	if(($travail!="Police") && ($travail!="DI2RCO") && ($travail!="Conseil Imperial"))
		{
		$sql1 = 'SELECT connec,pseudo FROM principal_tbl WHERE id= "'.$ideo.'"' ;
		$req1 = mysql_query($sql1);
		$pseudoo = mysql_result($req1,0,pseudo);
		if(mysql_result($req1,0,connec) == "oui") $connec = '<img src="im_objets/eclair1.gif" border="0" title="Connect&eacute;"> ';
		else $connec = "";
		print($connec.'<strong><a href="engine=police.php?'.$pseudoo.'"> '.$pseudoo.'</a></strong><br />');
		}
	}

print('</td>
	<td>');

$sql = 'SELECT id FROM principal_tbl WHERE Police>= "55" AND police< "110"' ;
$req = mysql_query($sql);
$res = mysql_num_rows($req); 

for($i=0; $i != $res; $i++)
	{
	$ideo = mysql_result($req,$i,id);
	$sql1 = 'SELECT entreprise FROM principal_tbl WHERE id= "'.$ideo.'"' ;
	$req1 = mysql_query($sql1);
	$travail = mysql_result($req1,0,entreprise);
	if(($travail!="Police") && ($travail!="DI2RCO") && ($travail!="Conseil Imperial"))
		{
		$sql1 = 'SELECT connec,pseudo FROM principal_tbl WHERE id= "'.$ideo.'"' ;
		$req1 = mysql_query($sql1);
		$pseudoo = mysql_result($req1,0,pseudo);
		if(mysql_result($req1,0,connec) == "oui") $connec = '<img src="im_objets/eclair1.gif" border="0" title="Connect&eacute;"> ';
		else $connec = "";
		print($connec.'<a href="engine=police.php?'.$pseudoo.'"> '.$pseudoo.'</a><br />');
		}
	}

print('</td>
</tr>
</table>');

mysql_close($db);

?>

</p>
</div>
<?php if($chat=="oui") 	{ if ((date("H")>=21) || (date("H")<=7)) { include("inc_bas_n_c.php"); } else { include("inc_bas_j_c.php"); } } else {	if ((date("H")>=21) || (date("H")<=7)) { include("inc_bas_n.php"); } else { include("inc_bas_j.php"); }	} ?>
<?php include("inc_bas_de_page.php"); ?>
