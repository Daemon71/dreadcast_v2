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

$sql = 'SELECT id FROM cercles_tbl WHERE pseudo= "'.$_SESSION['pseudo'].'"' ;
$req = mysql_query($sql);
$resc = mysql_num_rows($req);
if($resc>0)
	{
	print('<meta http-equiv="refresh" content="0 ; url=http://v2.dreadcast.net/ingame/engine=cercle.php"> ');
	exit();
	}

for($i=1;$i!=7;$i++)
	{
	$sql = 'SELECT case'.$i.' FROM principal_tbl WHERE case'.$i.' LIKE "%Recueil%" AND id= "'.$_SESSION['id'].'"' ;
	$req = mysql_query($sql);
	$res = mysql_num_rows($req);
	if($res>0)
		{
		$recueil = mysql_result($req,0,'case'.$i.'');
		$sql1 = 'SELECT * FROM signatures_tbl WHERE numero= "'.substr($recueil,7,strlen($recueil)).'"' ;
		$req1 = mysql_query($sql1);
		if((mysql_result($req1,0,sign1)==$_SESSION['pseudo']) && (mysql_result($req1,0,sign2)!="") && (mysql_result($req1,0,sign3)!="") && (mysql_result($req1,0,sign4)!="") && (mysql_result($req1,0,sign5)!=""))
			{
			$okcreation = 1;
			for($u=1;$u!=6;$u++)
				{
				$sqlc2 = 'SELECT id FROM cercles_tbl WHERE pseudo= "'.mysql_result($req1,0,'sign'.$u.'').'"' ;
				$reqc2 = mysql_query($sqlc2);
				$resc2 = mysql_num_rows($reqc2);
				if($resc2>0)
					{
					$okcreation = 3;
					}
				}
			}
		}
	}

$sql = 'SELECT credits,statut FROM principal_tbl WHERE id= "'.$_SESSION['id'].'"' ;
$req = mysql_query($sql);
$credits = mysql_result($req,0,credits);
$_SESSION['statut'] = mysql_result($req,0,statut);

if($credits<1500)
	{
	$okcreation = 2;
	}

mysql_close($db);
?>
<?php if ((date("H")>=21) || (date("H")<=7)) { include("inc_haut_n.php"); } else { include("inc_haut_j.php");} ?>

<div id="haut">
	<?php include("inc_entete.php"); ?>
	<div class="haut_aligngauche">
		<p>
		<div class="titrepage">
			Votre cercle
		</div>
		<b class="module4ie"><a href="engine=cercle.php" class="module4"><img src="im_objets/icon_retour.gif" alt="Retour" />Retour</a></b>
</p>
	</div>
</div>
<div id="centre">
<p>

<table width="150" height="25" bgcolor="#FFFFFF" border="1" align="center" cellpadding="0" cellspacing="0">
	<tr>
		<td><div align="center"><strong>Créer un cercle</strong></div></td>
	</tr>
</table>
<table width="450" height="250" bgcolor="#FFFFFF" border="1" align="center" cellpadding="0" cellspacing="0">
	<tr>
		<td><div align="center">
		<strong>Créer un Cercle coûte 1500 Crédits.</strong><br /><br />
		Créer un cercle, c'est créer une communauté de joueurs qui ont les mêmes idées, un même objectif. C'est pour cela que pour créer un nouveau cercle, il vous faut au moins 5 signatures de citoyens. <br /><br />
<?php
if(statut($_SESSION['statut'])<2)
	{
	print('<strong>Il faut posséder un <a href="../compteVIP.php" target="_blank">statut avancé</a> pour pouvoir créer un cercle.</strong>');
	}
elseif($okcreation==2)
	{
	print('<strong>Vous devez posseder 1500 Crédits sur vous pour créer un cercle.</strong>');
	}
elseif($okcreation==3)
	{
	print('<strong>Une des personnes qui a signé votre recueil fait déjà partie d\'un cercle !<br />Votre recueil ne vaut rien tant qu\'il ne démissionne pas de son cercle actuel.</strong>');
	}
elseif($okcreation==1)
	{
	print('<a href="engine=cerclecreer.php">Commencer la création de votre cercle</a>');
	}
else
	{
	print('<strong>Vous devez les posseder pour poursuivre.</strong><br />(Sous forme de l\'objet <a href="http://v2.dreadcast.net/info=objet.php?Recueil de signatures" target="_blank">Recueil de signatures</a>).');
	}
?>
		</div></td>
	</tr>
</table>


</p>
</div>
<?php if($chat=="oui") 	{ if ((date("H")>=21) || (date("H")<=7)) { include("inc_bas_n_c.php"); } else { include("inc_bas_j_c.php"); } } else {	if ((date("H")>=21) || (date("H")<=7)) { include("inc_bas_n.php"); } else { include("inc_bas_j.php"); }	} ?>
<?php include("inc_bas_de_page.php"); ?>
