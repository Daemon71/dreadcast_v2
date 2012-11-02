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
			Acheter de la publicité
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

$sql = 'SELECT * FROM pubs_tbl WHERE entreprise= "'.$_SESSION['cercle'].'"' ;
$req = mysql_query($sql);
$res = mysql_num_rows($req);

if($res==0)
	{
	$sql = 'INSERT INTO pubs_tbl(id,entreprise,message,lien,signature,temps) VALUES("","'.$_SESSION['cercle'].'","","","","0")';
	$req = mysql_query($sql);
	}

$sql = 'SELECT * FROM pubs_tbl WHERE entreprise= "'.$_SESSION['cercle'].'"' ;
$req = mysql_query($sql);
$message = mysql_result($req,0,message);
$signature = mysql_result($req,0,signature);
$lien = mysql_result($req,0,lien);
$jours = mysql_result($req,0,temps);
if($_GET['ok']==1)
	{
	if((ereg("tout",$bddc)) || (ereg("pub",$bddc)))
		{
		$sql = 'SELECT capital FROM cerclesliste_tbl WHERE nom= "'.$_SESSION['cercle'].'"' ;
		$req = mysql_query($sql);
		$capital = mysql_result($req,0,capital);
		
		if($capital>=500)
			{
			$capital = $capital - 500;
			$jours = $jours + 1;
			$sql = 'UPDATE cerclesliste_tbl SET capital= "'.$capital.'" WHERE nom= "'.$_SESSION['cercle'].'"' ;
			$req = mysql_query($sql);
			$sql = 'UPDATE pubs_tbl SET temps= "'.$jours.'" WHERE entreprise= "'.$_SESSION['cercle'].'"' ;
			$req = mysql_query($sql);
			}
		else
			{
			print('<br /><strong>Vous n\'avez pas le capital nécéssaire dans votre cercle.</strong><br /><br />');
			}
		}
	else
		{
		print('<meta http-equiv="refresh" content="0 ; url=http://v2.dreadcast.net/ingame/engine=cercle.php"> ');
		exit();
		}
	}
elseif($_GET['modif']==1)
	{
	if((ereg("tout",$bddc)) || (ereg("pub",$bddc)))
		{
		$sql = 'SELECT capital FROM cerclesliste_tbl WHERE nom= "'.$_SESSION['cercle'].'"' ;
		$req = mysql_query($sql);
		$capital = mysql_result($req,0,capital);
		
		if($capital>=100)
			{
			$capital = $capital - 100;
			$sql = 'UPDATE cerclesliste_tbl SET capital= "'.$capital.'" WHERE nom= "'.$_SESSION['cercle'].'"' ;
			$req = mysql_query($sql);
			$sql = 'UPDATE pubs_tbl SET message= "'.$_POST['message'].'" , lien= "'.$_POST['lien'].'" , signature= "'.$_POST['signature'].'" WHERE entreprise= "'.$_SESSION['cercle'].'"' ;
			$req = mysql_query($sql);
			}
		else
			{
			print('<br /><strong>Vous n\'avez pas le capital nécéssaire dans votre cercle.</strong><br /><br />');
			}
		$sql = 'SELECT * FROM pubs_tbl WHERE entreprise= "'.$_SESSION['cercle'].'"' ;
		$req = mysql_query($sql);
		$message = mysql_result($req,0,message);
		$signature = mysql_result($req,0,signature);
		$lien = mysql_result($req,0,lien);
		$jours = mysql_result($req,0,temps);
		}
	else
		{
		print('<meta http-equiv="refresh" content="0 ; url=http://v2.dreadcast.net/ingame/engine=cercle.php"> ');
		exit();
		}
	}
print('<strong>Nombre de jours de publicité restants</strong>: '.$jours.'
	<br /><br /><a href="engine=cerclepub.php?ok=1">Acheter un jour de plus</a> (500 Crédits)<br /><br />
	<form name="changerpub" method="post" action="engine=cerclepub.php?modif=1">
	Message défilant: <input type="text" maxlength="200" name="message" size="50" value="'.$message.'" /><br /><br />
	Signature: <input type="text" maxlength="15" name="signature" size="10" value="'.$signature.'" /><br />
	- OU - <br />
	Nom du lien vers votre cercle: <input type="text" maxlength="15" name="lien" size="10" value="'.$lien.'" /><br />
	<input type="submit" value="Valider (100 Crédits)" />
	</form>');

mysql_close($db);
?>
	 </div></td>
	</tr>
</table>


</p>
</div>
<?php if($chat=="oui") 	{ if ((date("H")>=21) || (date("H")<=7)) { include("inc_bas_n_c.php"); } else { include("inc_bas_j_c.php"); } } else {	if ((date("H")>=21) || (date("H")<=7)) { include("inc_bas_n.php"); } else { include("inc_bas_j.php"); }	} ?>
<?php include("inc_bas_de_page.php"); ?>
