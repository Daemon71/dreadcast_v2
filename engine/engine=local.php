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

$sql = 'SELECT type,entreprise,points FROM principal_tbl WHERE id= "'.$_SESSION['id'].'"' ;
$req = mysql_query($sql);
$_SESSION['poste'] = mysql_result($req,0,type); 
$_SESSION['entreprise'] = mysql_result($req,0,entreprise); 
$_SESSION['points'] = mysql_result($req,0,points); 

if($_SESSION['points']!=999) { print('<meta http-equiv="refresh" content="0 ; url=http://v2.dreadcast.net/ingame/engine.php"> '); exit(); }

mysql_close($db);
?>
<?php if ((date("H")>=21) || (date("H")<=7)) { include("inc_haut_n.php"); } else { include("inc_haut_j.php");} ?>

<div id="haut">
	<?php include("inc_entete.php"); ?>
	<div class="haut_aligngauche">
		<p>
		<div class="titrepage">
			Local d'entreprise
		</div>
		<b class="module4ie"><a href="engine=gestion.php" class="module4"><img src="im_objets/icon_retour.gif" alt="Retour" />Retour</a></b>
</p>
	</div>
</div>
<div id="centre">
<p>

<strong>Vous pouvez agrandir votre local :         </strong>
          <form name="bdd" id="bdd" method="post" action="engine=localfinished.php">
          <?php 

$db = mysql_connect('localhost', 'CENSURE', 'CENSURE') or die("Erreur de connexion au serveur des messages.");
mysql_select_db('CENSURE',$db) or die("Erreur de connexion a la base de donnees des messages.");

$sql = 'SELECT num,rue,type FROM entreprises_tbl WHERE nom="'.$_SESSION['entreprise'].'"' ;
$req = mysql_query($sql);
$num = mysql_result($req,0,num); 
$rue = mysql_result($req,0,rue); 
$type = mysql_result($req,0,type); 

$sql = 'SELECT nom,repos,code,chat,camera FROM lieu_tbl WHERE num="'.$num.'" AND rue="'.$rue.'"' ;
$req = mysql_query($sql);
$nom = mysql_result($req,0,nom); 
$repos = mysql_result($req,0,repos); 
$code = mysql_result($req,0,code); 
$chat = mysql_result($req,0,chat); 
$camera = mysql_result($req,0,camera); 

print('<strong>Actuel :</strong> <i>'.$nom.'</i><br>');
print('<strong>Adresse :</strong> <i>'.$num.' '.ucwords($rue).'</i></p><p>');

if($repos==1)
	{
	print('<select name="aent" id="aent">');
	print('<option value="50" selected="selected">Local 50m&sup2; (4 personnes) - 400 Cr&eacute;dits</option>');
	print('<option value="100">Local 100m&sup2; (6 personnes) - 1000 Cr&eacute;dits</option>');
	print('<option value="200">Local 200m&sup2; (8 personnes) - 2000 Cr&eacute;dits</option>');
	print('</select>');
	}
elseif($repos==2)
	{
	print('<select name="aent" id="aent">');
	print('<option value="100" selected="selected">Local 100m&sup2; (6 personnes) - 1000 Cr&eacute;dits</option>');
	print('<option value="200">Local 200m&sup2; (8 personnes) - 2000 Cr&eacute;dits</option>');
	print('</select>');
	}
elseif($repos==3)
	{
	print('<select name="aent" id="aent">');
	print('<option value="200">Local 200m&sup2; (8 personnes) - 2000 Cr&eacute;dits</option>');
	print('</select>');
	}
elseif($repos==4)
	{
	print('Vous avez le plus grand local disponible en ce moment.');
	}

mysql_close($db);

if($repos!=4)
	{
	print('<input type="submit" name="Submit2" value="Acheter" /></p></form> ');
	}
else
	{
	print('</form>');
	}

print('<p align="center"><strong>Options :</strong></p>');

if($code!=0)
	{
	print('<form name="form1" method="post" action="engine=changercodeent.php"><div align="center">Digicode : <input name="digicode" type="text" id="digicode" value="'.$code.'" size="'.strlen($code).'" maxlength="'.strlen($code).'"><input type="submit" name="Submit" value="Changer"></div></form>');
	if(strlen($code)<6)
		{
		print('<div align="center"><a href="engine=acce.php">Acheter un chiffre de plus pour 180 Cr&eacute;dits</a></div>');
		}
	}
else
	{
	print("<center><i>Il n'y a pas de digicode</i></center>");
	}

print('</form>');

if($type!="bar cafe") 
	{ 
	if($chat!="oui")
		{
		print('<p align="center"><a href="engine=acheterchatent.php">Acheter un salon de discussion pour votre local d\'entreprise</a> (700 Cr&eacute;dits)<br>	<em>Les clients et le personnel ont acc&egrave;s au salon de discussion.</em>'); 
		}
	else
		{
		print('<p align="center">Vous possedez un salon de discussion pour votre local d\'entreprise<br><em>Les clients et le personnel ont acc&egrave;s au salon de discussion.</em>'); 
		}
	} 
if($camera=="Non")
	{
	print('<p align="center"><a href="engine=achetercamera.php">Acheter une camera de police pour votre local d\'entreprise</a> (100 Cr&eacute;dits)<br>	<em>La camera de police permet une plus grande sécurité des employés.</em>'); 
	}
else
	{
	print('<p align="center">Vous possedez une camera de police pour votre local d\'entreprise<br>	<em>La camera de police permet une plus grande sécurité des employés.</em>'); 
	}
?> 

</p>
</div>
<?php if($chat=="oui") 	{ if ((date("H")>=21) || (date("H")<=7)) { include("inc_bas_n_c.php"); } else { include("inc_bas_j_c.php"); } } else {	if ((date("H")>=21) || (date("H")<=7)) { include("inc_bas_n.php"); } else { include("inc_bas_j.php"); }	} ?>
<?php include("inc_bas_de_page.php"); ?>
