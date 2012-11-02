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

$sql = 'SELECT * FROM cercles_tbl WHERE pseudo= "'.$_SESSION['pseudo'].'"' ;
$req = mysql_query($sql);
$resc = mysql_num_rows($req);
if($resc>0)
	{
	$_SESSION['cercle'] = mysql_result($req,0,cercle);
	$_SESSION['postec'] = mysql_result($req,0,poste);
	$sql4 = 'SELECT bdd FROM `c_'.str_replace(" ","_",''.$_SESSION['cercle'].'').'_tbl` WHERE poste= "'.$_SESSION['postec'].'"' ;
	$req4 = mysql_query($sql4);
	$bddc = mysql_result($req4,0,bdd);
	if((ereg("tout",$bddc)) || (ereg("postes",$bddc)))
		{
		}
	else
		{
		print('<meta http-equiv="refresh" content="0 ; url=http://v2.dreadcast.net/ingame/engine=cercle.php"> ');
		exit();
		}
	}
else
	{
	print('<meta http-equiv="refresh" content="0 ; url=http://v2.dreadcast.net/ingame/engine=cercle.php"> ');
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
			Hierarchie
		</div>
		<b class="module4ie"><a href="engine=cerclepostes.php" class="module4"><img src="im_objets/icon_retour.gif" alt="Retour" />Retour</a></b>
</p>
	</div>
</div>
<div id="centre">
<p>

<table width="450" height="25" bgcolor="#FFFFFF" border="1" align="center" cellpadding="0" cellspacing="0">
	<tr>
	  <td bgcolor="#CACACA"><div align="center"><a href="engine=cercle.php">Votre cercle</a></div></td>
	  <td bgcolor="#CACACA"><div align="center"><a href="engine=cerclestatut.php">Votre statut</a></div></td>
	  <td bgcolor="#CACACA"><div align="center"><a href="engine=cerclemembres.php">Membres du cercle</a></div></td>
	  <td bgcolor="#CACACA"><div align="center"><a href="engine=cercleactu.php">Actualité du cercle</a></div></td>
	</tr>
</table>
<table width="450" height="250" bgcolor="#FFFFFF" border="1" align="center" cellpadding="0" cellspacing="0">
	<tr>
	  <td>
	   <div align="center">
<?php

$db = mysql_connect('localhost', 'CENSURE', 'CENSURE') or die("Erreur de connexion au serveur des messages.");
mysql_select_db('CENSURE',$db) or die("Erreur de connexion a la base de donnees des messages.");

if($_GET['suppr']=="ok")
	{
	if($_GET['id']>2)
		{
		$sql = 'SELECT poste FROM `c_'.str_replace(" ","_",''.$_SESSION['cercle'].'').'_tbl` WHERE id= "'.$_GET['id'].'"' ;
		$req = mysql_query($sql);
		$sql4 = 'DELETE FROM `c_'.str_replace(" ","_",''.$_SESSION['cercle'].'').'_tbl` WHERE id= "'.$_GET['id'].'"' ;
		$req4 = mysql_query($sql4);
		$sql4 = 'SELECT * FROM cercles_tbl WHERE poste= "'.mysql_result($req,0,poste).'" AND cercle= "'.$_SESSION['cercle'].'"' ;
		$req4 = mysql_query($sql4);
		$res4 = mysql_num_rows($req4);
		for($i=0;$i!=$res4;$i++)
			{
			$sql = 'INSERT INTO messages_tbl(id,auteur,cible,message,objet,moment,nouveau) VALUES("","'.$_SESSION['cercle'].'","'.mysql_result($req4,$i,pseudo).'","Vous êtes renvoyé du cercle.","Info Cercle","'.time().'","oui")' ;
			}
		$sql4 = 'DELETE FROM cercles_tbl WHERE poste= "'.mysql_result($req,0,poste).'" AND cercle= "'.$_SESSION['cercle'].'"' ;
		$req4 = mysql_query($sql4);
		print('<strong>Poste supprimé.</strong>');
		}
	else
		{
		print('<strong>Il est impossible de supprimer ce poste</strong>');
		}
	}
else
	{
	print('<strong>Êtes-vous vraiment sûr de vouloir supprimer ce poste ?</strong><br />Si c\'est le cas toutes les éventuelles personnes occupant le poste seront renvoyées.<br /><br /><a href="engine=cercleps.php?id='.$_SERVER['QUERY_STRING'].'&suppr=ok">Oui</a> - <a href="engine=cercle.php">Non</a>');
	}

mysql_close($db);

?>
	  </div>
	 </td>
	</tr>
</table>


</p>
</div>
<?php if($chat=="oui") 	{ if ((date("H")>=21) || (date("H")<=7)) { include("inc_bas_n_c.php"); } else { include("inc_bas_j_c.php"); } } else {	if ((date("H")>=21) || (date("H")<=7)) { include("inc_bas_n.php"); } else { include("inc_bas_j.php"); }	} ?>
<?php include("inc_bas_de_page.php"); ?>
