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
	if((ereg("tout",$bddc)) || (ereg("vote",$bddc)))
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
			Ajoût d'un vote
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
	  <td>
	  <br />
	    <div id="cerclemembres">
		<table width="430" bgcolor="#FFFFFF" border="0" align="center" cellpadding="0" cellspacing="0">
<?php

if($_GET['ok']=="1")
	{
	if(empty($_POST['vote']))
		{
		print('<tr><td><br /><center>Le vote est vide.</center></td></tr>');
		}
	elseif(empty($_POST['reponse1']))
		{
		print('<tr><td><br /><center>La réponse 1 est vide.</center></td></tr>');
		}
	elseif(empty($_POST['reponse2']))
		{
		print('<tr><td><br /><center>La réponse 2 est vide.</center></td></tr>');
		}
	else
		{
		$db = mysql_connect('localhost', 'CENSURE', 'CENSURE') or die("Erreur de connexion au serveur des messages.");
		mysql_select_db('CENSURE',$db) or die("Erreur de connexion a la base de donnees des messages.");
	
		$sql5 = 'INSERT INTO cerclesvotes_tbl(id,cercle,vote,reponse1,reponse2,reponse3,reponse4,reponse5) VALUES("","'.$_SESSION['cercle'].'","'.str_replace("\n","<br />",''.htmlentities($_POST["vote"],ENT_QUOTES).'').'","'.$_POST['reponse1'].'","'.$_POST['reponse2'].'","'.$_POST['reponse3'].'","'.$_POST['reponse4'].'","'.$_POST['reponse5'].'")' ;
		$req5 = mysql_query($sql5);
		
		$sql4 = 'SELECT * FROM cercles_tbl WHERE cercle= "'.$_SESSION['cercle'].'"' ;
		$req4 = mysql_query($sql4);
		$res4 = mysql_num_rows($req4);
		for($i=0;$i!=$res4;$i++)
			{
			$sql = 'INSERT INTO messages_tbl(id,auteur,cible,message,objet,moment,nouveau) VALUES("","'.$_SESSION['cercle'].'","'.mysql_result($req4,$i,pseudo).'","Un nouveau vote vient d\'être lancé dans le cercle.<br />Pour le consulter et participer, rendez-vous dans votre rubrique Cercle/Statut/Voter une décision.<br /><br />Bonne journée !","Nouveau vote","'.time().'","oui")' ;
			$req = mysql_query($sql);
			}
		
		mysql_close($db);
		
		print('<tr><td><br /><center><strong>Votre vote est créé.</strong></center></td></tr>');
		}
	}
else
	{
	print('<tr><td><form action="engine=cerclevote.php?ok=1" method="post" name="ajoutvote">
	<center><strong>Vote:</strong><br /><textarea name="vote" cols="40" rows="4" /></textarea></center></td></tr>');
	print('<tr><td><center><br /><strong>Réponse 1:</strong> <input size="20" name="reponse1"><br />
	<strong>Réponse 2:</strong> <input size="20" name="reponse2"><br />
	<strong>Réponse 3:</strong> <input size="20" name="reponse3"><br />
	<strong>Réponse 4:</strong> <input size="20" name="reponse4"><br />
	<strong>Réponse 5:</strong> <input size="20" name="reponse5"><br />
	<input type="submit" name="Submit" value="Lancer le vote" /></form></center></td></tr>');
	}


?>
		</table>
		</div>
	  </td>
	</tr>
</table>


</p>
</div>
<?php if($chat=="oui") 	{ if ((date("H")>=21) || (date("H")<=7)) { include("inc_bas_n_c.php"); } else { include("inc_bas_j_c.php"); } } else {	if ((date("H")>=21) || (date("H")<=7)) { include("inc_bas_n.php"); } else { include("inc_bas_j.php"); }	} ?>
<?php include("inc_bas_de_page.php"); ?>
