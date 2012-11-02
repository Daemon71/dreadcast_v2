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
	$sql4 = 'SELECT * FROM `c_'.str_replace(" ","_",''.$_SESSION['cercle'].'').'_tbl` WHERE poste= "'.$_SESSION['postec'].'"' ;
	$req4 = mysql_query($sql4);
	$bddc = mysql_result($req4,0,bdd);
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
			Personnaliser le tracte
		</div>
		<b class="module4ie"><a href="engine=cerclestatut.php" class="module4"><img src="im_objets/icon_retour.gif" alt="Retour" />Retour</a></b>
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
	  <td><div align="center">
<?php
$db = mysql_connect('localhost', 'CENSURE', 'CENSURE') or die("Erreur de connexion au serveur des messages.");
mysql_select_db('CENSURE',$db) or die("Erreur de connexion a la base de donnees des messages.");

$sql = 'SELECT tracte FROM cerclesliste_tbl WHERE nom= "'.$_SESSION['cercle'].'"' ;
$req = mysql_query($sql);
$tracte = mysql_result($req,0,tracte);

if($_GET['ok']==1)
	{
	if((ereg("tout",$bddc)) || (ereg("tractes",$bddc)))
		{
		$sql = 'SELECT capital FROM cerclesliste_tbl WHERE nom= "'.$_SESSION['cercle'].'"' ;
		$req = mysql_query($sql);
		$capital = mysql_result($req,0,capital);
		
		if($capital>=20)
			{
			$capital = $capital - 20;
			$sql = 'UPDATE cerclesliste_tbl SET capital= "'.$capital.'" , tracte= "'.str_replace("\n","<br />",''.htmlentities($_POST["tracte"],ENT_QUOTES).'').'" WHERE nom= "'.$_SESSION['cercle'].'"' ;
			$req = mysql_query($sql);
			}
		}
	else
		{
		print('<meta http-equiv="refresh" content="0 ; url=http://v2.dreadcast.net/ingame/engine=cercle.php"> ');
		exit();
		}
	}
$sql = 'SELECT tracte FROM cerclesliste_tbl WHERE nom= "'.$_SESSION['cercle'].'"' ;
$req = mysql_query($sql);
$tracte = mysql_result($req,0,tracte);

print('<form name="modiftracte" method="post" action="engine=cercletracte.php?ok=1">
<strong>Texte du tracte</strong>: <br />
<textarea cols="30" rows="6" name="tracte">'.str_replace("<br />","",$tracte).'</textarea>
<br /><br /><input value="Modifier (20 Crédits)" type="submit"></form>');

mysql_close($db);
?>
	 </div></td>
	</tr>
</table>


</p>
</div>
<?php if($chat=="oui") 	{ if ((date("H")>=21) || (date("H")<=7)) { include("inc_bas_n_c.php"); } else { include("inc_bas_j_c.php"); } } else {	if ((date("H")>=21) || (date("H")<=7)) { include("inc_bas_n.php"); } else { include("inc_bas_j.php"); }	} ?>
<?php include("inc_bas_de_page.php"); ?>
