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

if($bdd=="") { print('<meta http-equiv="refresh" content="0 ; url=http://v2.dreadcast.net/ingame/engine.php"> '); exit(); } if($type!="dcn") { print('<meta http-equiv="refresh" content="0 ; url=http://v2.dreadcast.net/ingame/engine.php"> '); exit(); }
if((ucwords($_SESSION['rue'])!=ucwords($rueent)) || ($_SESSION['num']!=$nument))
	{
	print('<p align="center"><strong>Vous devez &ecirc;tre sur place pour pouvoir acc&eacute;der &agrave; cette rubrique.</strong></p>'); 
	$l = 1;
	}

mysql_close($db);

?>
<?php if ((date("H")>=21) || (date("H")<=7)) { include("inc_haut_n.php"); } else { include("inc_haut_j.php");} ?>

<div id="haut">
	<?php include("inc_entete.php"); ?>
	<div class="haut_aligngauche">
		<p>
		<div class="titrepage">
			Refuser un article
		</div>
		</p>
	</div>
</div>
<div id="centre">
<p>

<?php 

if($_SESSION['statut']=="Administrateur" OR $l!=1)
	{
	$db = mysql_connect('localhost', 'CENSURE', 'CENSURE') or die("Erreur de connexion au serveur des messages.");
	mysql_select_db('CENSURE',$db) or die("Erreur de connexion a la base de donnees des messages.");
	
	$sql = 'SELECT auteur,titre FROM articlesprop_tbl WHERE id='.$_SERVER['QUERY_STRING'].'' ;
	$req = mysql_query($sql);
	$res = mysql_num_rows($req);
	
	if($res>0)
		{
		if($_POST['raison']!="")
			{
			$sql = 'INSERT INTO messages_tbl(id,auteur,cible,message,objet,moment) VALUES("","Dreadcast","'.mysql_result($req,0,auteur).'","Votre article intitul� '.mysql_result($req,0,titre).' � �t� refus� par le DreadCast Network.<br /><br /><strong>Motif:</strong> '.htmlentities(stripslashes($_POST['raison'])).'","Article refus�","'.time().'")' ;
			$req = mysql_query($sql);
			$sql = 'DELETE FROM articlesprop_tbl WHERE id='.htmlentities($_SERVER['QUERY_STRING']).'' ;
			$req = mysql_query($sql);
			print('<meta http-equiv="refresh" content="0 ; url=http://v2.dreadcast.net/ingame/engine=panneaua.php"> ');
			}
		else
			{
			print('<form method="post" action="engine=supprarticle.php?'.$_SERVER['QUERY_STRING'].'">
			<p align="center">Explication du refus :<br />
			<textarea rows="5" cols="40" name="raison"></textarea><br /><br /><input type="submit" value="Refuser" /></p></form>');
			}
		}
	
	mysql_close($db);

	}

?>

</p>
</div>
<?php if($chat=="oui") 	{ if ((date("H")>=21) || (date("H")<=7)) { include("inc_bas_n_c.php"); } else { include("inc_bas_j_c.php"); } } else {	if ((date("H")>=21) || (date("H")<=7)) { include("inc_bas_n.php"); } else { include("inc_bas_j.php"); }	} ?>
<?php include("inc_bas_de_page.php"); ?>
