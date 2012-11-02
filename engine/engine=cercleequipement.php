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
			Acheter de l'équipement
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

$sql = 'SELECT num,rue,capital FROM cerclesliste_tbl WHERE nom= "'.$_SESSION['cercle'].'"' ;
$req = mysql_query($sql);
$num = mysql_result($req,0,num);
$rue = mysql_result($req,0,rue);
$capital = mysql_result($req,0,capital);

$sql = 'SELECT chat,camera FROM lieu_tbl WHERE num="'.$num.'" AND rue="'.$rue.'"' ;
$req = mysql_query($sql);
$chat = mysql_result($req,0,chat); 
$camera = mysql_result($req,0,camera); 

if((ereg("tout",$bddc)) || (ereg("equipement",$bddc)))
	{
	if(($_GET['objet']=="chat") && ($capital>=700))
		{
		$capital = $capital - 700;
		$sql = 'UPDATE cerclesliste_tbl SET capital= "'.$capital.'" WHERE nom= "'.$_SESSION['cercle'].'"' ;
		$req = mysql_query($sql);
		$sql = 'UPDATE lieu_tbl SET chat= "oui" WHERE num="'.$num.'" AND rue="'.$rue.'"' ;
		$req = mysql_query($sql);
		for($i=1;$i!=15;$i++)
			{
			$sql = 'INSERT INTO chat(id,posteur,message,rue,num,moment,ids) VALUES(""," "," ","'.$rue.'","'.$num.'","'.date("H:i").'","'.$i.'")' ;
			$req = mysql_query($sql);
			}
		$sql = 'INSERT INTO chat(id,posteur,message,rue,num,moment,ids) VALUES("","Dreadcast","Bienvenue dans le salon du cercle '.$_SESSION['cercle'].'.","'.$rue.'","'.$num.'","'.date("H:i").'","15")' ;
		$req = mysql_query($sql);
		$chat = "oui";
		}
	elseif(($_GET['objet']=="camera") && ($capital>=100))
		{
		$capital = $capital - 100;
		$sql = 'UPDATE cerclesliste_tbl SET capital= "'.$capital.'" WHERE nom= "'.$_SESSION['cercle'].'"' ;
		$req = mysql_query($sql);
		$sql = 'UPDATE lieu_tbl SET camera= "Pol" WHERE num="'.$num.'" AND rue="'.$rue.'"' ;
		$req = mysql_query($sql);
		$camera = "Pol";
		}
	if($chat!="oui")
		{
		print('<p align="center"><a href="engine=cercleequipement.php?objet=chat">Acheter un salon de discussion pour votre cercle</a> (700 Cr&eacute;dits)<br><em>Toutes les personnes présentes ont acc&egrave;s au salon de discussion.</em>'); 
		}
	else
		{
		print('<p align="center"><strong>Vous possedez un salon de discussion pour votre cercle.</strong><br><em>Toutes les personnes présentes ont acc&egrave;s au salon de discussion.</em>'); 
		}
	if($camera=="Non")
		{
		print('<p align="center"><a href="engine=cercleequipement.php?objet=camera">Acheter une camera de police pour votre cercle</a> (100 Cr&eacute;dits)<br><em>La camera de police permet une plus grande sécurité des personnes présentes.</em>'); 
		}
	else
		{
		print('<p align="center"><strong>Vous possedez une camera de police pour votre cercle.</strong><br><em>La camera de police permet une plus grande sécurité des personnes présentes.</em>'); 
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
