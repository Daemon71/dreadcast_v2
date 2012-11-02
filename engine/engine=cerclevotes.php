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
			Voter une décision
		</div>
		<b class="module4ie"><a href="engine=<?php if($_GET['ok']==1) { print('cerclevotes.php'); } else { print('cerclestatut.php'); } ?>" class="module4"><img src="im_objets/icon_retour.gif" alt="Retour" />Retour</a></b>
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
	  <br />
		<div id="cerclemembres">
<?php

$db = mysql_connect('localhost', 'CENSURE', 'CENSURE') or die("Erreur de connexion au serveur des messages.");
mysql_select_db('CENSURE',$db) or die("Erreur de connexion a la base de donnees des messages.");

if($_GET['ok']=="1")
	{
	if(empty($_POST['reponse']))
		{
		print('<strong>Le vote est vide.</strong>');
		}
	else
		{
		$sql5 = 'INSERT INTO cerclesvotesperso_tbl(id,idvote,pseudo,reponse) VALUES("","'.$_GET['id'].'","'.$_SESSION['pseudo'].'","'.$_POST['reponse'].'")' ;
		$req5 = mysql_query($sql5);
		print('<strong>Votre vote est pris en compte.</strong>');
		}
	}
else
	{
	$sql = 'SELECT * FROM cerclesvotes_tbl WHERE cercle= "'.$_SESSION['cercle'].'"';
	$req = mysql_query($sql);
	$res = mysql_num_rows($req);
	
	if($res>0)
		{
		print('<div id="cercleactu">
		<table width="95%" bgcolor="#FFFFFF" border="1" align="center" cellpadding="0" cellspacing="0">
		<tr bgcolor="#B6B6B6">
		<th scope="col"><div align="center">Vote</div></th>
		<th scope="col"><div align="center">Réponses</div></th>
		<th scope="col"><div align="center"></div></th>
		</tr>');
	
		for($i=0;$i!=$res;$i++)
			{
			$sql1 = 'SELECT * FROM cerclesvotesperso_tbl WHERE pseudo= "'.$_SESSION['pseudo'].'" AND idvote= "'.mysql_result($req,$i,id).'"';
			$req1 = mysql_query($sql1);
			$res1 = mysql_num_rows($req1);
			if($res1==0)
				{
				print('<tr><td><div align="center">'.mysql_result($req,$i,vote).'');
				if((ereg("tout",$bddc)) || (ereg("vote",$bddc)))
					{
					print('<br />(<a href="engine=cerclesupprvote.php?id='.mysql_result($req,$i,id).'">Supprimer le vote</a>)');
					}
				print('</div></td><td><form action="engine=cerclevotes.php?id='.mysql_result($req,$i,id).'&ok=1" method="post" name="voter">
				<div align="left"><input type="radio" name="reponse" value="1" />
				'.mysql_result($req,$i,reponse1).'
				<br />
				<input type="radio" name="reponse" value="2" />
				'.mysql_result($req,$i,reponse2).'
				<br />');
				if(mysql_result($req,$i,reponse3)!="")
					{
					print('<input type="radio" name="reponse" value="3" />
					'.mysql_result($req,$i,reponse3).'
					<br />');
					}
				if(mysql_result($req,$i,reponse4)!="")
					{
					print('<input type="radio" name="reponse" value="4" />
					'.mysql_result($req,$i,reponse4).'
					<br />');
					}
				if(mysql_result($req,$i,reponse5)!="")
					{
					print('<input type="radio" name="reponse" value="5" />
					'.mysql_result($req,$i,reponse5).'
					<br />');
					}
				print('</div></td><td><div align="center"><input type="submit" value="Voter" /></div></form></td></tr>');
				}
			else
				{
				$sqlr = 'SELECT * FROM cerclesvotesperso_tbl WHERE idvote= "'.mysql_result($req,$i,id).'" AND reponse="1"';
				$reqr = mysql_query($sqlr);
				$resr1 = mysql_num_rows($reqr);
				$sqlr = 'SELECT * FROM cerclesvotesperso_tbl WHERE idvote= "'.mysql_result($req,$i,id).'" AND reponse="2"';
				$reqr = mysql_query($sqlr);
				$resr2 = mysql_num_rows($reqr);
				$sqlr = 'SELECT * FROM cerclesvotesperso_tbl WHERE idvote= "'.mysql_result($req,$i,id).'" AND reponse="3"';
				$reqr = mysql_query($sqlr);
				$resr3 = mysql_num_rows($reqr);
				$sqlr = 'SELECT * FROM cerclesvotesperso_tbl WHERE idvote= "'.mysql_result($req,$i,id).'" AND reponse="4"';
				$reqr = mysql_query($sqlr);
				$resr4 = mysql_num_rows($reqr);
				$sqlr = 'SELECT * FROM cerclesvotesperso_tbl WHERE idvote= "'.mysql_result($req,$i,id).'" AND reponse="5"';
				$reqr = mysql_query($sqlr);
				$resr5 = mysql_num_rows($reqr);
				print('<tr><td><div align="center">'.mysql_result($req,$i,vote).'');
				if((ereg("tout",$bddc)) || (ereg("vote",$bddc)))
					{
					print('<br />(<a href="engine=cerclesupprvote.php?id='.mysql_result($req,$i,id).'">Supprimer le vote</a>)');
					}
				print('</div></td><td><div align="left">');
				print('(<strong>'.$resr1.'</strong>) '.mysql_result($req,$i,reponse1).'<br />');
				print('(<strong>'.$resr2.'</strong>) '.mysql_result($req,$i,reponse2).'<br />');
				if(mysql_result($req,$i,reponse3)!="")
					{
					print('(<strong>'.$resr3.'</strong>) '.mysql_result($req,$i,reponse3).'<br />');
					}
				if(mysql_result($req,$i,reponse4)!="")
					{
					print('(<strong>'.$resr4.'</strong>) '.mysql_result($req,$i,reponse4).'<br />');
					}
				if(mysql_result($req,$i,reponse5)!="")
					{
					print('(<strong>'.$resr5.'</strong>) '.mysql_result($req,$i,reponse5).'');
					}
				print('</div></td></tr>');
				}
			}
		print('</table></div>');
		}
	else
		{
		print('<strong>Il n\'y a aucun vote en ce moment.</strong>');
		}
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
