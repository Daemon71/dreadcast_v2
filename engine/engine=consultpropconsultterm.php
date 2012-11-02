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
			Services
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

$sql = 'SELECT type,entreprise,points FROM principal_tbl WHERE id= "'.$_SESSION['id'].'"' ;
$req = mysql_query($sql);
$_SESSION['poste'] = mysql_result($req,0,type); 
$_SESSION['entreprise'] = mysql_result($req,0,entreprise); 
$_SESSION['points'] = mysql_result($req,0,points); 

$sql = 'SELECT bdd,type FROM `e_'.str_replace(" ","_",''.$_SESSION['entreprise'].'').'_tbl` WHERE poste= "'.$_SESSION['poste'].'"' ;
$req = mysql_query($sql);
$bdd = mysql_result($req,0,bdd); 
$typep = mysql_result($req,0,type); 

$sql = 'SELECT type,num,rue FROM entreprises_tbl WHERE nom= "'.$_SESSION['entreprise'].'"' ;
$req = mysql_query($sql);
$type = mysql_result($req,0,type); 
$rueent = mysql_result($req,0,rue); 
$nument = mysql_result($req,0,num); 

if($bdd=="") { print('<meta http-equiv="refresh" content="0 ; url=http://v2.dreadcast.net/ingame/engine.php"> '); exit(); } if($type!="chambre") { print('<meta http-equiv="refresh" content="0 ; url=http://v2.dreadcast.net/ingame/engine.php"> '); exit(); }

if((ucwords($_SESSION['rue'])!=ucwords($rueent)) || ($_SESSION['num']!=$nument))
	{
	print('<p align="center"><strong>Vous devez &ecirc;tre sur place pour pouvoir acc&eacute;der &agrave; cette rubrique.</strong></p>'); 
	$l = 1;
	}

if(($typep=="chef") && ($type=="chambre") && ($l!=1))
	{
	$sql = 'SELECT intitule,membre FROM loisprop_tbl WHERE id= "'.$_GET['id'].'" AND vote= "non"' ;
	$req = mysql_query($sql);
	$res = mysql_num_rows($req);
	if($res>0)
		{
		if($_GET['vote']=="Non")
			{
			$vintitule = mysql_result($req,0,intitule);
			$vmembre = mysql_result($req,0,membre);
			$sql2 = 'DELETE FROM loisprop_tbl WHERE id= "'.$_GET['id'].'"' ;
			$req2 = mysql_query($sql2);
			print('<p align="center">Vous venez de refuser une proposition de Loi.</p>');
			$sql = 'INSERT INTO messages_tbl(id,auteur,cible,message,objet,moment) VALUES("","Chambre des Lois","'.$vmembre.'","Votre proposition de Loi intitulée <strong>'.$vintitule.'</strong> a été refusée.","Proposition de Loi","'.time().'")' ;
			$req = mysql_query($sql);
			}
		elseif($_GET['vote']=="Ok")
			{
			$vintitule = mysql_result($req,0,intitule);
			$vmembre = mysql_result($req,0,membre);
			$sql2 = 'UPDATE loisprop_tbl SET vote= "oui" WHERE id= "'.$_GET['id'].'"' ;
			$req2 = mysql_query($sql2);
			print('<p align="center">Vous venez de placer une Loi en vote.<br>Vous pouvez à tout instant regarder où en sont les votes sur votre ordinateur.</p>');
			$sql = 'INSERT INTO messages_tbl(id,auteur,cible,message,objet,moment) VALUES("","Chambre des Lois","'.$vmembre.'","Votre proposition de Loi intitulée <strong>'.$vintitule.'</strong> a été retenue.<br>Vous pouvez dès maintenant voter depuis votre ordinateur.","Proposition de Loi","'.time().'")' ;
			$req = mysql_query($sql);
			}
		}
	else
		{
		print('<p align="center">La proposition de Loi n\'existe pas.</p>');
		}
	}


mysql_close($db);

?>        


</p>
</div>
<?php if($chat=="oui") 	{ if ((date("H")>=21) || (date("H")<=7)) { include("inc_bas_n_c.php"); } else { include("inc_bas_j_c.php"); } } else {	if ((date("H")>=21) || (date("H")<=7)) { include("inc_bas_n.php"); } else { include("inc_bas_j.php"); }	} ?>
<?php include("inc_bas_de_page.php"); ?>
