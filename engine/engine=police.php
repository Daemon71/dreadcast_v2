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

$sql = 'SELECT bdd FROM `e_'.str_replace(" ","_",''.$_SESSION['entreprise'].'').'_tbl` WHERE poste= "'.$_SESSION['poste'].'"' ;
$req = mysql_query($sql);
$bdd = mysql_result($req,0,bdd); 

$sql = 'SELECT type,num,rue FROM entreprises_tbl WHERE nom= "'.$_SESSION['entreprise'].'"' ;
$req = mysql_query($sql);
$type = mysql_result($req,0,type); 
$rueent = mysql_result($req,0,rue); 
$nument = mysql_result($req,0,num); 

$sql = 'SELECT type FROM entreprises_tbl WHERE nom= "'.$_SESSION['entreprise'].'"' ;
$req = mysql_query($sql);
$type = mysql_result($req,0,type); 

if($_SESSION['statut'] != "Administrateur" AND $bdd=="") { print('<meta http-equiv="refresh" content="0 ; url=http://v2.dreadcast.net/ingame/engine.php"> '); exit(); } if($_SESSION['statut'] != "Administrateur" AND ($type!="police") && ($type!="di2rco")) { print('<meta http-equiv="refresh" content="0 ; url=http://v2.dreadcast.net/ingame/engine.php"> '); exit(); }

mysql_close($db);
?>
<?php if ((date("H")>=21) || (date("H")<=7)) { include("inc_haut_n.php"); } else { include("inc_haut_j.php");} ?>

<div id="haut">
	<?php include("inc_entete.php"); ?>
	<div class="haut_aligngauche">
		<p>
		<div class="titrepage">
			Recherche de Police
		</div>
		<b class="module4ie"><a href="engine=services.php" class="module4"><img src="im_objets/icon_retour.gif" alt="Retour" />Retour</a></b>
</p>
	</div>
</div>
<div id="centre">
<p>
<div class="messagesvip">

<?php

$db = mysql_connect('localhost', 'CENSURE', 'CENSURE') or die("Erreur de connexion au serveur des messages.");
mysql_select_db('CENSURE',$db) or die("Erreur de connexion a la base de donnees des messages.");

if($_SERVER['QUERY_STRING']!="")
	{
	$_SESSION['nomrech'] = str_replace("%20"," ",''.$_SERVER['QUERY_STRING'].'');
	}

if($_SESSION['nomrech']=="")
	{
	$_SESSION['nomrech'] = $_POST['nomrech'];
	}

if($_SESSION['statut'] != "Administrateur" AND (ucwords($_SESSION['rue'])!=ucwords($rueent)) || ($_SESSION['num']!=$nument))
	{
	print('<p align="center"><strong>Vous devez &ecirc;tre sur place pour pouvoir acc&eacute;der &agrave; cette rubrique.</center></p>'); 
	print('<p align="center"><a href="engine=gestion.php">Retour"</a></p>');
	$l = 1;
	}

$sql = 'SELECT id,race,sexe,age,taille,action FROM principal_tbl WHERE pseudo= "'.$_SESSION['nomrech'].'"' ;
$req = mysql_query($sql);
$res = mysql_num_rows($req); 

if($res>0)
	{
	if((mysql_result($req,0,action)=="mort") && ($l!=1))
		{
		print('<strong><i>Citoyen d&eacute;c&eacute;d&eacute;.</i></strong></p><p align="center">');
		$agerech = "?";
		$taillerech = "?";
		$sexerech = "?";
		$racerech = "?";
		$numrech = 0;
		$typerech = "?";
		$die = "?";
		$entrepriserech = "?";
		$l = 1;
		}
	}

if(($res!=0) && ($l!=1))
	{
	$_SESSION['idrech'] = mysql_result($req,0,id);
	$racerech = mysql_result($req,0,race);
	$sexerech = mysql_result($req,0,sexe);
	$agerech = ''.mysql_result($req,0,age).' ans';
	$actionrech = mysql_result($req,0,action);
	$taillerech = '1m'.mysql_result($req,0,taille).'';
	$sql = 'SELECT type,entreprise FROM principal_tbl WHERE id= "'.$_SESSION['idrech'].'"' ;
	$req = mysql_query($sql);
	$typerech = mysql_result($req,0,type);
	$entrepriserech = mysql_result($req,0,entreprise);
	if($typerech!="Aucun")
		{
		$sql = 'SELECT num,rue FROM entreprises_tbl WHERE nom= "'.$entrepriserech.'"' ;
		$req = mysql_query($sql);
		$rueerech = mysql_result($req,0,rue);
		$numerech = mysql_result($req,0,num);
		$sql = 'SELECT code FROM lieu_tbl WHERE rue= "'.$rueerech.'" AND num= "'.$numerech.'"' ;
		$req = mysql_query($sql);
		$die = mysql_result($req,0,code);
		}

	$sql = 'SELECT DI2RCO,Police FROM principal_tbl WHERE id= "'.$_SESSION['idrech'].'"' ;
	$req = mysql_query($sql);
	$policerech = mysql_result($req,0,Police);
	$di2rcorech = mysql_result($req,0,DI2RCO);
	$sql = 'SELECT numl,ruel FROM principal_tbl WHERE id= "'.$_SESSION['idrech'].'"' ;
	$req = mysql_query($sql);
	$numrech = mysql_result($req,0,numl);
	$ruerech = mysql_result($req,0,ruel);
	if($ruerech!="Aucune")
		{
		if($numrech<0) { $numrech2 = 0; $ruerech2 = "Rue"; }
		else { $numrech2 = $numrech; $ruerech2 = $ruerech; }
		$sql = 'SELECT code FROM lieu_tbl WHERE rue= "'.$ruerech2.'" AND num= "'.$numrech2.'"' ;
		$req = mysql_query($sql);
		if(mysql_num_rows($req))
			{
			$dil = mysql_result($req,0,code);
			}
		else $dil = 0;
		$sqldqs = 'SELECT id FROM lieux_speciaux_tbl WHERE type="chateau" AND rue= "'.$ruerech2.'" AND num= "'.$numrech2.'"' ;
		$reqdqs = mysql_query($sqldqs);
		if (mysql_num_rows($reqdqs))
			$dil = 0;
		}
	else
		{
		$dil = 0;
		}
	if(($entrepriserech=="Conseil Imperial") || ($entrepriserech=="DI2RCO"))
		{
		$policerech = "c";
		$agerech = "Information class&eacute;e confidentielle";
		$taillerech = "Information class&eacute;e confidentielle";
		$sexerech = "Information class&eacute;e confidentielle";
		$racerech = "Information class&eacute;e confidentielle";
		$numrech = 0;
		$typerech = "Information class&eacute;e confidentielle";
		$die = "Information class&eacute;e confidentielle";
		$entrepriserech = "Information class&eacute;e confidentielle";
		}
	elseif($entrepriserech=="Police")
		{
		if($_SESSION['entreprise']!="DI2RCO")
			{
			$policerech = "c";
			$agerech = "Information class&eacute;e confidentielle";
			$taillerech = "Information class&eacute;e confidentielle";
			$sexerech = "Information class&eacute;e confidentielle";
			$racerech = "Information class&eacute;e confidentielle";
			$numrech = 0;
			$typerech = "Information class&eacute;e confidentielle";
			$die = "Information class&eacute;e confidentielle";
			$entrepriserech = "Information class&eacute;e confidentielle";
			}
		else
			{
			$policerech = "c";
			}
		}
	}
elseif($res==0)
	{
	print('<p align="center"><strong><i>Aucun citoyen ne se pr&eacute;nomme ainsi.</i></strong><br>');
	$agerech = "?";
	$taillerech = "?";
	$sexerech = "?";
	$racerech = "?";
	$numrech = 0;
	$typerech = "?";
	$die = "?";
	$entrepriserech = "?";
	$l = 1;
	}

mysql_close($db);

?>
            <?php 
if($policerech>=120)
	{
	print('<span class="color3">/ / !! \ \</span>'); 
	}
elseif($policerech>=55)
	{
	print('<span class="color2">/ / !! \ \</span>'); 
	}
?>
    Recherches sur un(e) d&eacute;nomm&eacute;(e) <strong><?php print(''.$_SESSION['nomrech'].''); ?></strong>. </em>
              <?php 
if($policerech>=120)
	{
	print('<span class="color3">/ / !! \ \</span>'); 
	}
elseif($policerech>=55)
	{
	print('<span class="color2">/ / !! \ \</span>'); 
	}
?> <br />
              &nbsp;
<?php 
if($actionrech=="prison")
	{
	print('Cette personne est actuellement en prison.<br />');
	}
?>
              <?php 
if($type=="di2rco")
	{
	if($di2rcorech>50)
		{
		print('<br /><span class="color3"><em>Cet individu doit &ecirc;tre &eacute;limin&eacute; &agrave; tout prix par les forces imp&eacute;riales. </em></span><br />'); 
		}
	}
if($policerech>=120)
	{
	print('<br /><span class="color3"><em>Cet individu est arm&eacute; et dangereux, il est activement recherch&eacute; par les forces de police. </em></span><br />'); 
	}
elseif($policerech>=55)
	{
	print('<br /><span class="color2"><em>Cet individu est recherch&eacute; par les forces de police.</em></span><br />'); 
	}
?><br />
        <table width="90%"  border="1" align="center" cellpadding="0" cellspacing="0">
          <tr>
            <th scope="row"><strong>Informations g&eacute;n&eacute;rales : </strong></th>
            <td><div align="center"><strong>Race :</strong><em> <?php print(''.ucwords($racerech).''); ?></em> <br>
                  <strong>Sexe : </strong><em><?php print(''.ucwords($sexerech).''); ?></em><br>
                  <strong>Age :</strong> <em><?php print(''.$agerech.''); ?></em> <strong><br>
  Taille : </strong><em><?php print(''.$taillerech.''); ?></em></div></td>
          </tr>
          <tr>
            <th scope="row"><strong>Lieu de r&eacute;sidence :</strong></th>
            <td><div align="center"><em>
            <?php
if($numrech!=0)
	{
	print(''.$numrech.' '.ucwords($ruerech).'');
	if(($dil!=0) && ($policerech>=55))
		{
		print('<br>Digicode d\'entr&eacute;e : <i>'.$dil.'</i>');
		}
	elseif($dil!=0)
		{
		print('<br>Pas de digicode.');
		}
	else
		{
		print('<br>Digicode inconnu.');
		}
	}
elseif($numrech==0)
	{
	print('<i>Aucun logement connu.</i>');
	}
?>
            </em> <br />
            <?php
if($numrech!=0)
	{
	print('<a href="engine=allera.php?num='.$numrech.'&rue='.$ruerech.'">S\'y rendre</a>');
	}
?>
</div></td>
          </tr>
          <tr>
            <th scope="row"><strong>Travail actuel :</strong></th>
            <td><div align="center">
              <?php 
if(($typerech!="Aucun") && ($typerech!="?"))
	{
	print('<p><strong>Poste : </strong><em>'.$typerech.'<br></em><strong>Entreprise : </strong><em>'.$entrepriserech.'</em>');
	if($die!=0)
		{
		print('<br>Digicode d\'entr&eacute;e : <i>'.$die.'</i><br />');
		}
	else
		{
		print('<br>Pas de digicode.<br />');
		}
	}
else
	{
	print('<p><i>Aucun travail connu.</i><br />');
	}
?>
              <?php
if(($typerech!="Aucun") && ($typerech!="Information class&eacute;e confidentielle") && ($typerech!="?"))
	{
	print('<a href="engine=allera.php?num='.$numerech.'&rue='.$rueerech.'">S\'y rendre</a></p>');
	}
?>
</div></td>
          </tr>
        </table>        
        <p align="center">
          <?php 	

if(($type=="di2rco") && ($l!=1))
	{
	if($policerech<55)
		{
		print('<a href="engine=avis.php?avis">Mettre un avis de recherche sur cet individu</a> <br />');
		}
	elseif($policerech<120)
		{
		print('<a href="engine=avis.php?rien">Arr&ecirc;ter de rechercher cet individu</a> <br />');
		print('<a href="engine=avis.php?mort">Mettre un avis de recherche prioritaire sur cet individu</a> <br />');
		}
	elseif($policerech>=120)
		{
		print('<a href="engine=avis.php?avis">D&eacute;scendre le niveau de recherche de cet individu</a> <br />');
		}
	if($di2rcorech<50)
		{
		print('<a href="engine=avis.php?di2rco">Ordonner aux services secrets la mort de cet individu</a><br />');
		}
	elseif($di2rcorech>=50)
		{
		print('<a href="engine=avis.php?riend">Ordonner aux services secrets de stopper les recherches sur cet individu</a><br />');
		}
	print('<a href="engine=enquete.php">Localiser par satellite</a><br />');
	print('<a href="engine=enquetebanque.php">Suivre les dernières transactions bancaires de cet individu</a><br />');
	}

?>
          <a href="engine=casier.php?<?php print(''.$_SESSION['nomrech'].''); ?>">Acceder au casier judiciaire</a>


</div>
</p>
</div>
<?php if($chat=="oui") 	{ if ((date("H")>=21) || (date("H")<=7)) { include("inc_bas_n_c.php"); } else { include("inc_bas_j_c.php"); } } else {	if ((date("H")>=21) || (date("H")<=7)) { include("inc_bas_n.php"); } else { include("inc_bas_j.php"); }	} ?>
<?php include("inc_bas_de_page.php"); ?>
