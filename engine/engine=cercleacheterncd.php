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
			Digicode du cercle
		</div>
		<b class="module4ie"><a href="engine=cercle.php" class="module4"><img src="im_objets/icon_retour.gif" alt="Retour" />Retour</a></b>
</p>
	</div>
</div>
<div id="centre">
<p>

<table width="450" height="25" bgcolor="#FFFFFF" border="1" align="center" cellpadding="0" cellspacing="0">
	<tr>
	  <td><div align="center"><strong>Votre cercle</strong></div></td>
	  <td bgcolor="#CACACA"><div align="center"><a href="engine=cerclestatut.php">Votre statut</a></div></td>
	  <td bgcolor="#CACACA"><div align="center"><a href="engine=cerclemembres.php">Membres du cercle</a></div></td>
	  <td bgcolor="#CACACA"><div align="center"><a href="engine=cercleactu.php">Actualit� du cercle</a></div></td>
	</tr>
</table>
<table width="450" height="250" bgcolor="#FFFFFF" border="1" align="center" cellpadding="0" cellspacing="0">
	<tr>
	  <td><div align="center">
<?php
$db = mysql_connect('localhost', 'CENSURE', 'CENSURE') or die("Erreur de connexion au serveur des messages.");
mysql_select_db('CENSURE',$db) or die("Erreur de connexion a la base de donnees des messages.");

$sql = 'SELECT num,rue FROM cerclesliste_tbl WHERE nom= "'.$_SESSION['cercle'].'"' ;
$req = mysql_query($sql);
$ruec = mysql_result($req,0,rue);
$numc = mysql_result($req,0,num);

$sql = 'SELECT code FROM lieu_tbl WHERE num= "'.$numc.'" AND rue= "'.$ruec.'"' ;
$req = mysql_query($sql);
$res = mysql_num_rows($req);

if($res>0)
	{
	if((ereg("tout",$bddc)) || (ereg("digicode",$bddc)))
		{
		$sqlc = 'SELECT capital FROM cerclesliste_tbl WHERE nom= "'.$_SESSION['cercle'].'"' ;
		$reqc = mysql_query($sqlc);
		if(strlen(mysql_result($req,0,code))>=6)
			{
			print("<strong>Il est impossible d'acheter plus de 6 chiffres.</strong>");
			}
		elseif(mysql_result($reqc,0,capital)>=180)
			{
			$capital = mysql_result($reqc,0,capital) - 180;
			$sqlc = 'UPDATE cerclesliste_tbl SET capital= "'.$capital.'" WHERE nom= "'.$_SESSION['cercle'].'"' ;
			$reqc = mysql_query($sqlc);
			$sqlc = 'UPDATE lieu_tbl SET code= "'.mysql_result($req,0,code).'0" WHERE num= "'.$numc.'" AND rue= "'.$ruec.'"' ;
			$reqc = mysql_query($sqlc);
			print('<strong>Un nouveau chiffre est maintenant disponible.</strong><br />(<a href="engine=cerclestatut.php">Retour � la page des statuts</a>)');
			}
		else
			{
			print('<strong>Il faut plus de Cr�dits dans le capital du cercle pour pouvoir acheter une nouveau chiffre.</strong>');
			}
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
	 </div></td>
	</tr>
</table>


</p>
</div>
<?php if($chat=="oui") 	{ if ((date("H")>=21) || (date("H")<=7)) { include("inc_bas_n_c.php"); } else { include("inc_bas_j_c.php"); } } else {	if ((date("H")>=21) || (date("H")<=7)) { include("inc_bas_n.php"); } else { include("inc_bas_j.php"); }	} ?>
<?php include("inc_bas_de_page.php"); ?>
