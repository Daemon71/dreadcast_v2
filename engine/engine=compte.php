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

$sql = 'SELECT credits FROM principal_tbl WHERE id= "'.$_SESSION['id'].'"' ;
$req = mysql_query($sql);
$_SESSION['credits'] = mysql_result($req,0,credits);

$sql = 'SELECT nom,type FROM entreprises_tbl WHERE rue= "'.$_SESSION['lieu'].'" AND num= "'.$_SESSION['num'].'"' ;
$req = mysql_query($sql);
$type = mysql_result($req,0,type);
$noment = mysql_result($req,0,nom);

if($type!="banque")
	{
	print('<meta http-equiv="refresh" content="0 ; url=http://v2.dreadcast.net/ingame/engine.php"> ');
	exit();
	}

$sqlb = 'SELECT * FROM digicodes_tbl WHERE pseudo="'.$_SESSION['pseudo'].'"' ;
$reqb = mysql_query($sqlb);
$resb = mysql_num_rows($reqb);

if($resb>0)
	{
	$moment = mysql_result($reqb,0,moment);
	if(time()-$moment<2)
		{
		$imp = 1;
		}
	else
		{
		$sql = 'UPDATE digicodes_tbl SET moment= "'.time().'" WHERE pseudo="'.$_SESSION['pseudo'].'"' ;
		$req = mysql_query($sql);
		}
	}
else
	{
	$sql = 'INSERT INTO digicodes_tbl(id,pseudo,moment) VALUES("","'.$_SESSION['pseudo'].'","'.time().'")' ;
	$req = mysql_query($sql);
	}

if($imp!=1)
	{
	if($_SERVER['QUERY_STRING']!="")
		{
		$code = $_SERVER['QUERY_STRING'];
		}
	else
		{
		$code = $_POST['code'];
		}
	
	$sql = 'SELECT * FROM comptes_tbl WHERE code= "'.$code.'"' ;
	$req = mysql_query($sql);
	$res = mysql_num_rows($req);
	
	if($res!=0)
		{
		$solde = mysql_result($req,0,credits);
		$mdp = mysql_result($req,0,mdp);
		$_SESSION['emp1'] = mysql_result($req,0,emp1);
		$_SESSION['emp2'] = mysql_result($req,0,emp2);
		$_SESSION['emp3'] = mysql_result($req,0,emp3);
		$_SESSION['emp4'] = mysql_result($req,0,emp4);
		$_SESSION['emp5'] = mysql_result($req,0,emp5);
		$_SESSION['emp6'] = mysql_result($req,0,emp6);
		$_SESSION['emp7'] = mysql_result($req,0,emp7);
		$_SESSION['emp8'] = mysql_result($req,0,emp8);
		
		}
	else
		{
		print('<meta http-equiv="refresh" content="0 ; url=http://v2.dreadcast.net/ingame/engine=banque.php"> ');
		exit();
		}
	
	if(mysql_result($req,0,mdp)!=$_SESSION['mdpcompte'])
		{
		print('<meta http-equiv="refresh" content="0 ; url=http://v2.dreadcast.net/ingame/engine=mdpcompte.php?'.$code.'"> ');
		exit();
		}
	
	$sql = 'SELECT image FROM objets_tbl WHERE nom= "'.$_SESSION['emp1'].'"' ;
	$req = mysql_query($sql);
	$_SESSION['image1'] = mysql_result($req,0,image);
	
	for($i=2; $i != 9; $i++)
		{
		if($_SESSION['emp'.$i.'']!="0")
			{
			$sql = 'SELECT image FROM objets_tbl WHERE nom= "'.$_SESSION['emp'.$i.''].'"' ;
			$req = mysql_query($sql);
			$_SESSION['image'.$i.''] = mysql_result($req,0,image);
			}
		}
	}
else
	{
	print('<meta http-equiv="refresh" content="0 ; url=http://v2.dreadcast.net/ingame/engine=banque.php?delais=1"> ');
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
			Banque
		</div>
		<b class="module4ie"><a href="engine=banque.php" class="module4"><img src="im_objets/icon_retour.gif" alt="Retour" />Retour</a></b>
		</p>
	</div>
</div>
<div id="centre_banque">
	<p id="location">Compte n&deg;<?php print(''.$code.''); ?> : <span><?php print(''.$solde.''); ?> Cr&eacute;dits</span></p>
	
	<div id="textelse2">
		<p><?php if($solde==0) { print('<a href="engine=depotc.php?'.$code.'">D&eacute;poser</a>'); } else { print('<a href="engine=depotc.php?'.$code.'">D&eacute;poser</a> - <a href="engine=retirerc.php?'.$code.'">Retirer</a>'); } print(' - <a href="engine=securiser.php?id='.$code.'">Sécuriser ce compte</a>'); ?><br />

		<a href="engine=supprcb.php?<?php print(''.$code.''); ?>">Fermer d&eacute;finitivement le compte</a></p>
		
		<table width="490" cellpadding="0" cellspacing="0">
			<tr>
				<td><?php if($_SESSION['emp1']=="Vide") { print('<div align="center"><img src="im_objets/'.$_SESSION['image1'].'" border="0"></div><div align="center"><a href="engine=depot.php?'.$code.'">D&eacute;poser</a></div>'); } else { print('<div align="center"><img src="im_objets/'.$_SESSION['image1'].'" border="0"></div><div align="center"><a href="engine=retirer.php?code='.$code.'&id=1">Retirer</a></div>'); } ?></td>
				<td><?php if($_SESSION['emp2']=="Vide") { print('<div align="center"><img src="im_objets/'.$_SESSION['image2'].'" border="0"></div><div align="center"><a href="engine=depot.php?'.$code.'">D&eacute;poser</a></div>'); } elseif($_SESSION['emp2']!="0") { print('<div align="center"><img src="im_objets/'.$_SESSION['image2'].'" border="0"></div><div align="center"><a href="engine=retirer.php?code='.$code.'&id=2">Retirer</a></div>'); } else {  print('<div align="center"><a href="engine=acheteremp.php?'.$code.'">Nouvel<br />emplacement</a><br /><em>(60 Cr)</em></div>'); } ?></td>
				<td><?php if($_SESSION['emp3']=="Vide") { print('<div align="center"><img src="im_objets/'.$_SESSION['image3'].'" border="0"></div><div align="center"><a href="engine=depot.php?'.$code.'">D&eacute;poser</a></div>'); } elseif($_SESSION['emp3']!="0") { print('<div align="center"><img src="im_objets/'.$_SESSION['image3'].'" border="0"></div><div align="center"><a href="engine=retirer.php?code='.$code.'&id=3">Retirer</a></div>'); } elseif($_SESSION['emp2']!="0") {  print('<div align="center"><a href="engine=acheteremp.php?'.$code.'">Nouvel<br />emplacement</a><br /><em>(60 Cr)</em></div>'); } ?></td>
				<td><?php if($_SESSION['emp4']=="Vide") { print('<div align="center"><img src="im_objets/'.$_SESSION['image4'].'" border="0"></div><div align="center"><a href="engine=depot.php?'.$code.'">D&eacute;poser</a></div>'); } elseif($_SESSION['emp4']!="0") { print('<div align="center"><img src="im_objets/'.$_SESSION['image4'].'" border="0"></div><div align="center"><a href="engine=retirer.php?code='.$code.'&id=4">Retirer</a></div>'); } elseif($_SESSION['emp3']!="0") {  print('<div align="center"><a href="engine=acheteremp.php?'.$code.'">Nouvel<br />emplacement</a><br /><em>(60 Cr)</em></div>'); } ?></td>
			</tr>
			<tr>
				<td><?php if($_SESSION['emp5']=="Vide") { print('<div align="center"><img src="im_objets/'.$_SESSION['image5'].'" border="0"></div><div align="center"><a href="engine=depot.php?'.$code.'">D&eacute;poser</a></div>'); } elseif($_SESSION['emp5']!="0") { print('<div align="center"><img src="im_objets/'.$_SESSION['image5'].'" border="0"></div><div align="center"><a href="engine=retirer.php?code='.$code.'&id=5">Retirer</a></div>'); } elseif($_SESSION['emp4']!="0") {  print('<div align="center"><a href="engine=acheteremp.php?'.$code.'">Nouvel<br />emplacement</a><br /><em>(60 Cr)</em></div>'); } ?></td>
				<td><?php if($_SESSION['emp6']=="Vide") { print('<div align="center"><img src="im_objets/'.$_SESSION['image6'].'" border="0"></div><div align="center"><a href="engine=depot.php?'.$code.'">D&eacute;poser</a></div>'); } elseif($_SESSION['emp6']!="0") { print('<div align="center"><img src="im_objets/'.$_SESSION['image6'].'" border="0"></div><div align="center"><a href="engine=retirer.php?code='.$code.'&id=6">Retirer</a></div>'); } elseif($_SESSION['emp5']!="0") {  print('<div align="center"><a href="engine=acheteremp.php?'.$code.'">Nouvel<br />emplacement</a><br /><em>(60 Cr)</em></div>'); } ?></td>
				<td><?php if($_SESSION['emp7']=="Vide") { print('<div align="center"><img src="im_objets/'.$_SESSION['image7'].'" border="0"></div><div align="center"><a href="engine=depot.php?'.$code.'">D&eacute;poser</a></div>'); } elseif($_SESSION['emp7']!="0") { print('<div align="center"><img src="im_objets/'.$_SESSION['image7'].'" border="0"></div><div align="center"><a href="engine=retirer.php?code='.$code.'&id=7">Retirer</a></div>'); } elseif($_SESSION['emp6']!="0") {  print('<div align="center"><a href="engine=acheteremp.php?'.$code.'">Nouvel<br />emplacement</a><br /><em>(60 Cr)</em></div>'); } ?></td>
				<td><?php if($_SESSION['emp8']=="Vide") { print('<div align="center"><img src="im_objets/'.$_SESSION['image8'].'" border="0"></div><div align="center"><a href="engine=depot.php?'.$code.'">D&eacute;poser</a></div>'); } elseif($_SESSION['emp8']!="0") { print('<div align="center"><img src="im_objets/'.$_SESSION['image8'].'" border="0"></div><div align="center"><a href="engine=retirer.php?code='.$code.'&id=8">Retirer</a></div>'); } elseif($_SESSION['emp7']!="0") {  print('<div align="center"><a href="engine=acheteremp.php?'.$code.'">Nouvel<br />emplacement</a><br /><em>(60 Cr)</em></div>'); } ?></td>
			</tr>
		</table>	
	</div>

</div>
<?php if($chat=="oui") 	{ if ((date("H")>=21) || (date("H")<=7)) { include("inc_bas_n_c.php"); } else { include("inc_bas_j_c.php"); } } else {	if ((date("H")>=21) || (date("H")<=7)) { include("inc_bas_n.php"); } else { include("inc_bas_j.php"); }	} ?>
<?php include("inc_bas_de_page.php"); ?>
