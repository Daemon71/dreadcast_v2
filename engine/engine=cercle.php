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
	$sql5 = 'SELECT * FROM cercles_tbl WHERE cercle= "'.$_SESSION['cercle'].'"' ;
	$req5 = mysql_query($sql5);
	$nbread = mysql_num_rows($req5);
	$sql1 = 'SELECT * FROM cerclesliste_tbl WHERE nom= "'.$_SESSION['cercle'].'"' ;
	$req1 = mysql_query($sql1);
	$res1 = mysql_num_rows($req1);
	if($res1>0)
		{
		$typec = mysql_result($req1,0,type);
		$numc = mysql_result($req1,0,num);
		$ruec = mysql_result($req1,0,rue);
		$capitalc = mysql_result($req1,0,capital);
		$logoc = mysql_result($req1,0,logo);
		$descriptionc = mysql_result($req1,0,description);
		$sql4 = 'SELECT bdd FROM `c_'.str_replace(" ","_",''.$_SESSION['cercle'].'').'_tbl` WHERE poste= "'.$_SESSION['postec'].'"' ;
		$req4 = mysql_query($sql4);
		$bddc = mysql_result($req4,0,bdd);
		}
	else
		{
		$sql2 = 'DELETE FROM cercles_tbl WHERE pseudo= "'.$_SESSION['pseudo'].'"' ;
		$req2 = mysql_query($sql2);
		$sql = 'INSERT INTO messages_tbl(id,auteur,cible,message,objet,moment) VALUES("","'.$_SESSION['cercle'].'","'.$_SESSION['pseudo'].'","Le cercle a &eacute;t&eacute; dissoud par un de ses fondateur.<br />Merci de votre confiance et à bientôt !","Cercle d&eacute;truit !","'.time().'")' ;
		$req = mysql_query($sql);
		print('<meta http-equiv="refresh" content="0 ; url=http://v2.dreadcast.net/ingame/engine=cercle.php"> ');
		exit();
		}
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
		</p>
	</div>
</div>
<div id="centre">
<p>

<?php
if($resc>0)
	{
	print('
			<table width="450" height="25" bgcolor="#FFFFFF" border="1" align="center" cellpadding="0" cellspacing="0">
				<tr>
					<td><div align="center"><strong>Votre cercle</strong></td>
					<td bgcolor="#CACACA"><div align="center"><a href="engine=cerclestatut.php">Votre statut</a></div></td>
					<td bgcolor="#CACACA"><div align="center"><a href="engine=cerclemembres.php">Membres du cercle</div></a></td>
					<td bgcolor="#CACACA"><div align="center"><a href="engine=cercleactu.php">Actualit&eacute; du cercle</div></a></td>
				</tr>
			</table>
			<table width="450" height="250" bgcolor="#FFFFFF" border="1" align="center" cellpadding="0" cellspacing="0">
				<tr>
				<td><div id="cerclemembres">');
					print('<table width="400" height="120" border="0" align="center" cellpadding="0" cellspacing="0">
						<tr>
							<td><div align="center"><img src="'.$logoc.'" border= "1px" height="100px" width="100px" />');
								if((ereg("tout",$bddc)) || (ereg("logo",$bddc)))
									{
									print('<br />(<a href="engine=cerclelogo.php">Changer</a>)');
									}
							print('</div></td>
							<td><div align="center">
								<strong>'.$_SESSION['cercle'].'</strong><br />Cercle '.$typec.' <br /><a href="engine=go.php?num='.$numc.'&rue='.$ruec.'">'.$numc.' '.$ruec.'</a><br /><br />
								<strong>Capital : </strong>'.$capitalc.' Cr&eacute;dits');
								if((ereg("tout",$bddc)) || (ereg("capital",$bddc)))
									{
									print(' (<a href="engine=cerclecapital.php">Retirer</a>)');
									}
								print('<br /><strong>Nombre d\'adh&eacute;rents :</strong> '.$nbread.'
							</div></td>
						</tr>
					</table>
					<table width="450" height="130" border="0" align="center" cellpadding="0" cellspacing="0">
						<tr>
							<td><div align="center">
								'.$descriptionc.'');
								if((ereg("tout",$bddc)) || (ereg("description",$bddc)))
									{
									print('<br />(<a href="engine=cercledescription.php">Modifier</a>)');
									}
							print('</div></td>
						</tr>
					</table>');
	}
else
	{
	print('
		<table width="150" height="25" bgcolor="#FFFFFF" border="1" align="center" cellpadding="0" cellspacing="0">
			<tr>
				<td><div align="center"><strong>Choisir un cercle</strong></div></td>
			</tr>
		</table>
		<table width="450" height="250" bgcolor="#FFFFFF" border="1" align="center" cellpadding="0" cellspacing="0">
			<tr>
			  <td><div align="center">Un cercle est un rassemblement de joueurs (il est également connu sous le nom de guilde). Il peut être de divers types : associatif ou politique. L\'objectif est de r&eacute;unir les joueurs autour de centres d\'interets. Il est fortement conseill&eacute; d\'appartenir à un cercle.<br /><br /><br />
			  <strong>Vous n\'appartenez actuellement à aucun cercle.<br />Vous pouvez cependant en choisir un dans la <a href="engine=cercleliste.php">liste</a>.<br /><br />Vous pouvez &eacute;galement <a href="engine=cerclecreation.php">cr&eacute;er votre propre cercle</a>.</strong>');
	}
?>
</div></td>
	</tr>
</table>


</p>
</div>
<?php if($chat=="oui") 	{ if ((date("H")>=21) || (date("H")<=7)) { include("inc_bas_n_c.php"); } else { include("inc_bas_j_c.php"); } } else {	if ((date("H")>=21) || (date("H")<=7)) { include("inc_bas_n.php"); } else { include("inc_bas_j.php"); }	} ?>
<?php include("inc_bas_de_page.php"); ?>
