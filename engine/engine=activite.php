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

$sql = 'SELECT * FROM principal_tbl WHERE pseudo= "'.$_SESSION['pseudo'].'"' ;
$req = mysql_query($sql);
$_SESSION['id'] = mysql_result($req,0,id);
$_SESSION['rue'] = mysql_result($req,0,rue);
$_SESSION['num'] = mysql_result($req,0,num);
$_SESSION['action'] = mysql_result($req,0,action);
$_SESSION['alim'] = mysql_result($req,0,alim);
$_SESSION['sante'] = mysql_result($req,0,sante);
$_SESSION['fatigue'] = mysql_result($req,0,fatigue);
$_SESSION['drogue'] = mysql_result($req,0,drogue);
$_SESSION['lieu'] = strtolower($_SESSION['rue']);
$_SESSION['lieu'] = ucwords($_SESSION['lieu']);

$sql = 'SELECT credits FROM principal_tbl WHERE id= "'.$_SESSION['id'].'"' ;
$req = mysql_query($sql);
$_SESSION['credits'] = mysql_result($req,0,credits); 

$sql = 'SELECT * FROM principal_tbl WHERE id= "'.$_SESSION['id'].'"' ;
$req = mysql_query($sql);
$_SESSION['type'] = mysql_result($req,0,type); 
$_SESSION['entreprise'] = mysql_result($req,0,entreprise); 
$_SESSION['salaire'] = mysql_result($req,0,salaire); 
$_SESSION['difficulte'] = mysql_result($req,0,difficulte); 
$_SESSION['points'] = mysql_result($req,0,points); 
$_SESSION['assurance'] = mysql_result($req,0,assurance); 

if($_SESSION['entreprise']!="Aucune")
	{
	$sql = 'SELECT num,rue FROM entreprises_tbl WHERE nom= "'.$_SESSION['entreprise'].'"' ;
	$req = mysql_query($sql);
	$nume = mysql_result($req,0,num); 
	$ruee = mysql_result($req,0,rue); 
	
	$sql = 'SELECT code FROM lieu_tbl WHERE num= "'.$nume.'" AND rue= "'.$ruee.'"' ;
	$req = mysql_query($sql);
	$codeent = mysql_result($req,0,code); 
	}

mysql_close($db);

?>
<?php if ((date("H")>=21) || (date("H")<=7)) { include("inc_haut_n.php"); } else { include("inc_haut_j.php");} ?>

<div id="haut">
	<?php include("inc_entete.php"); ?>
	<div class="haut_aligngauche">
		<p>
		<div class="titrepage">
			Profession
		</div>
		</p>
	</div>
</div>
<div id="centre">
<p>


<?php
if($_SESSION['type']!="Aucun")
	{
	if($_SESSION['points']!=999)
		{
		print('<p align="center">Voici les informations générales concernant votre emploi.<br>Pour gérer vos heures de travail, rendez vous dans la rubrique <a href="engine=redirt.php">Créer/Gérer</a>.</p>');
		}
	else
		{
		print('<p align="center">Voici les informations générales concernant votre emploi.<br>Pour gérer votre entreprise, rendez vous dans la rubrique <a href="engine=redirt.php">Créer/Gérer</a>.</p>');
		}
	print('<p align="center"><strong>Entreprise : </strong> <i>'.$_SESSION['entreprise'].'</i></p>');
	print('<p align="center"><strong>Poste : </strong> <i>'.$_SESSION['type'].'</i></p>');
	if($_SESSION['salaire']==0)
	{
	print('<p align="center"><strong>Salaire : </strong> <i>Pas de salaire</i><br>');
	}
	else
	{
	print('<p align="center"><strong>Salaire : </strong> <i>'.$_SESSION['salaire'].' Cr&eacute;dits / Jour</i><br>');
	}
	print('<strong>Difficult&eacute; : </strong>');
	if($_SESSION['difficulte']==0)
		{
		print('<i>Personnalis&eacute;e</i></p>');
		}
	elseif($_SESSION['difficulte']<4)
		{
		print('<i>Facile</i></p>');
		}
	elseif($_SESSION['difficulte']<7)
		{
		print('<i>Fatiguant</i></p>');
		}
	elseif($_SESSION['difficulte']<9)
		{
		print('<i>Tr&egrave;s fatiguant</i></p>');
		}
	elseif($_SESSION['difficulte']==9)
		{
		print('<i>Ereintant</i></p>');
		}
	elseif($_SESSION['difficulte']==10)
		{
		print('<i>Invivable</i></p>');
		}
	print('<p align="center"><strong>Digicode du personnel : </strong><i>'.$codeent.'</i></p>');
	if($_SESSION['action']!="travail")
		{
		print('<p align="center"><a href="engine=go.php?rue='.$ruee.'&num='.$nume.'">Vous rendre à votre travail</a></p>');
		}
	else
		{
		print('<p align="center"><i>Vous êtes actuellement en train de travailler</i></p>');
		}
	if($_SESSION['assurance']>0)
		{
		print('<p align="center"><a href="engine=assurance.php">Consulter votre assurance</a></p>');
		}
	elseif(($_SESSION['points']==999) && ($_SESSION['entreprise']!="CIPE") && ($_SESSION['entreprise']!="CIE") && ($_SESSION['entreprise']!="Conseil Imperial") && ($_SESSION['entreprise']!="Police") && ($_SESSION['entreprise']!="Prison") && ($_SESSION['entreprise']!="Services techniques de la ville") && ($_SESSION['entreprise']!="DOI") && ($_SESSION['entreprise']!="Chambre des Lois") && ($_SESSION['entreprise']!="DI2RCO") && ($_SESSION['entreprise']!="DC Network"))
		{
		print('<p align="center"><a href="engine=assurance.php">Souscrire une assurance</a></p>');
		}
	else
		{
		print('<p align="center">&nbsp;</p>');
		}
	print('<p align="center"><a href="engine=demission.php">D&eacute;missionner</a></p>');
	}
else
	{
	print('<p align="center"><br /><strong>Vous n\'avez pas de travail !</strong></p><br />');
	print('<p align="center">Pour en trouver un, rendez vous dans le <a href="engine=recherche.php?CIPE">Centre d\'Information Pour l\'Emploi</a> le plus proche. Si vous d&eacute;sirez faire des &eacute;tudes, cherchez une &eacute;cole dans le domaine de votre choix.</p>');
	print('<p align="center">Si vous êtes majeur, il est &eacute;galement possible de <a href="engine=creerent.php">cr&eacute;er votre propre entreprise</a> avec un capital de d&eacute;part de 3500 Cr&eacute;dits.</p>');
	
	$db = mysql_connect('localhost', 'CENSURE', 'CENSURE') or die("Erreur de connexion au serveur des messages.");
	mysql_select_db('CENSURE',$db) or die("Erreur de connexion a la base de donnees des messages.");
	
	$sqlc = 'SELECT id,entreprise,poste FROM candidatures_tbl WHERE nom= "'.$_SESSION['pseudo'].'"' ;
	$reqc = mysql_query($sqlc);
	$resc = mysql_num_rows($reqc);

	if($resc>0)
		{
		print('<hr><p align="center"><strong>Panneau de gestion de vos candidatures</strong></p><table width="400" border="1" cellspacing="0" cellpadding="0" align="center"><tr bgcolor="#B6B6B6"><td><div align="center">Poste</div></td><td><div align="center">Entreprise</div></td><td>&nbsp;</td></tr>');
    	for($f=0;$f!=$resc;$f++)
			{
			$idc = mysql_result($reqc,$f,id);
			$entreprise = mysql_result($reqc,$f,entreprise);
			$poste = mysql_result($reqc,$f,poste);
			print('<tr>
				<td><div align="center"><a href="engine=voircandvol.php?'.$idc.'">'.$poste.'</a></div></td>
				  <td><div align="center">'.$entreprise.'</div></td>
				  <td><div align="center"><a href="engine=supprcandvol.php?'.$idc.'"><img src="im_objets/poubelle.gif" border="0"></a></div></td>
			  </tr>');
			}
		print('</table>');
		}
	mysql_close($db);
	}
?>


</p>
</div>
<?php if($chat=="oui") 	{ if ((date("H")>=21) || (date("H")<=7)) { include("inc_bas_n_c.php"); } else { include("inc_bas_j_c.php"); } } else {	if ((date("H")>=21) || (date("H")<=7)) { include("inc_bas_n.php"); } else { include("inc_bas_j.php"); }	} ?>
<?php include("inc_bas_de_page.php"); ?>
