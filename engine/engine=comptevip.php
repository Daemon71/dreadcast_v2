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

$sql = 'SELECT entreprise,points FROM principal_tbl WHERE id= "'.$_SESSION['id'].'"' ;
$req = mysql_query($sql);
$_SESSION['entreprise'] = mysql_result($req,0,entreprise);
$_SESSION['points'] = mysql_result($req,0,points);

if($_SESSION['points']==999)
	{
	$sql = 'SELECT chiffre,datea FROM chiffre_affaire_tbl WHERE entreprise= "'.$_SESSION['entreprise'].'" ORDER BY datea DESC' ;
	$req = mysql_query($sql);
	$res = mysql_num_rows($req);
	for($i=16;$i!=16-$res;$i--)
		{
		$chiffre[''.$i.''] = mysql_result($req,16-$i,chiffre);
		$datea[''.$i.''] = mysql_result($req,16-$i,datea);
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
			Statut privilégié
		</div>
		<b class="module4ie"><a href="engine=infosperso.php" class="module4"><img src="im_objets/icon_retour.gif" alt="Retour" />Retour</a></b>
</p>
	</div>
</div>
<div id="centre">
<p>

<?php

if(statut($_SESSION['statut'])<2)
	{
	mysql_close($db);
	print('<meta http-equiv="refresh" content="0 ; url=http://v2.dreadcast.net/ingame/engine=infosperso.php"> ');
	exit();
	}

?>
<p align="center"><strong>Voici vos avantages actuel :</strong></p>
		<table width="460" border="0" align="center">
          <tr>
            <td><p align="left">- Pas de publicit&eacute; dans le jeu<br />
  - <a href="engine=messages.php">Messagerie illimit&eacute;e</a><br />
  - <a href="engine=contact.php">Priorit&eacute; du traitement des demandes par les administrateurs</a> <br />
  - Protection contre la suppression de compte en cas de mort prolong&eacute;e<br />
  - <a href="engine=aitl.php">AITL miniature</a> (gratuit et ne prennant pas d'emplacement dans l'inventaire)<br />
  - <a href="engine=aitl2.php">AITL 2.0 miniature</a> (gratuit et ne prennant pas d'emplacement dans l'inventaire)<br />
  - <a href="engine=messagesenv.php">Boite des messages envoy&eacute;s</a> <br />
  - <a href="engine=signatureperso.php">Signature personnalisable des messages</a><br />
  - <a href="engine=cercle.php">Possibilité de créer son propre cercle</a><br />
  - <a href="engine=parrainage.php">Possibilité d'acheter un code de parrainage</a><br />
<?php 
if($_SESSION['points']==999) 
	{ 
	print('- <a href="engine=courbe.php?titre=Chiffres de votre entreprise');
	for($i=16;$i!=0;$i--)
		{
		if($chiffre[''.$i.'']!=0)
			{
			print('&y'.$i.'='.$chiffre[''.$i.''].'');
			}
		if($datea[''.$i.'']!=0)
			{
			print('&date'.$i.'='.$datea[''.$i.''].'');
			}
		}
	print('">Courbe du chiffre d\'affaire de votre entreprise</a> <br>');
	print('- <a href="engine=listeacheteurs.php">Liste des derni&egrave;res ventes de l\'entreprise</a>'); 
	} 
?>
            </p>              </td>
          </tr>
        </table>		

</p>
</div>
<?php if($chat=="oui") 	{ if ((date("H")>=21) || (date("H")<=7)) { include("inc_bas_n_c.php"); } else { include("inc_bas_j_c.php"); } } else {	if ((date("H")>=21) || (date("H")<=7)) { include("inc_bas_n.php"); } else { include("inc_bas_j.php"); }	} ?>
<?php include("inc_bas_de_page.php"); ?>
