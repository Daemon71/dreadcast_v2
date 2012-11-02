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
?>
<?php if ((date("H")>=21) || (date("H")<=7)) { include("inc_haut_n.php"); } else { include("inc_haut_j.php");} ?>

<div id="haut">
	<?php include("inc_entete.php"); ?>
	<div class="haut_aligngauche">
		<p>
		<div class="titrepage">
			Services
		</div>
		<b class="module4ie"><a href="engine=consultsubv.php" class="module4"><img src="im_objets/icon_retour.gif" alt="Retour" />Retour</a></b>
</p>
	</div>
</div>
<div id="centre">
<p>

<?php 

$db = mysql_connect('localhost', 'CENSURE', 'CENSURE') or die("Erreur de connexion au serveur des messages.");
mysql_select_db('CENSURE',$db) or die("Erreur de connexion a la base de donnees des messages.");

if($_SESSION['statut']!="Administrateur")
{

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

if($bdd=="" && $_SESSION['statut']!="Administrateur") { print('<meta http-equiv="refresh" content="0 ; url=http://v2.dreadcast.net/ingame/engine.php"> '); exit(); } if(($type!="vente de services") && ($type!="banque") && ($type!="DOI") && ($type!="conseil") && ($type!="chambre") && ($type!="prison") && ($type!="police") && ($type!="di2rco")) { print('<meta http-equiv="refresh" content="0 ; url=http://v2.dreadcast.net/ingame/engine.php"> '); exit(); }

if((ucwords($_SESSION['rue'])!=ucwords($rueent)) || ($_SESSION['num']!=$nument))
	{
	print('<p align="center"><strong>Vous devez &ecirc;tre sur place pour pouvoir acc&eacute;der &agrave; cette rubrique.</strong></p>'); 
	$l = 1;
	}
}

if((($type=="DOI") && ($l!=1)) OR $_SESSION['statut']=="Administrateur")
	{
	$sql = 'SELECT PDG,entreprise,message FROM financepridem_tbl WHERE id= "'.$_SERVER['QUERY_STRING'].'"' ;
	$req = mysql_query($sql);
	$res = mysql_num_rows($req);
	if($res>0)
		{
		print('<div class="evae">');
		$vPDG = mysql_result($req,0,PDG);
		$vmessage = mysql_result($req,0,message);
		$ventreprise = mysql_result($req,0,entreprise);
		print('<p align="center">Demande de subvention de <a href="engine=contacter.php?cible='.$vPDG.'">'.$vPDG.'</a> pour l\'entreprise <strong>'.$ventreprise.'</strong> :</p>');
		print('<p align="center">'.$vmessage.'</p>');
		$sql1 = 'SELECT id FROM financepri_tbl WHERE entreprise= "'.$ventreprise.'" AND membre= "'.$_SESSION['pseudo'].'"' ;
		$req1 = mysql_query($sql1);
		$res1 = mysql_num_rows($req1);
		if($res1>0)
			{
			print('<hr><p align="center">Vous avez déjà voté.</p>');
			print('</div>');
			}
		else
			{
			print('<hr><p align="center">Vous pouvez voter une subvention de 1000 Crédits maximum pour cette entreprise cette semaine.</p>');
			print('</div>');
			print('<form style="position:relative;top:8px;" name="form1" method="post" action="engine=consultsubvterm.php?'.$_SERVER['QUERY_STRING'].'"><div align="center"><strong>Votre vote pour cette subvention :</strong> <input name="subv" type="text" size="4" maxlength="4">Cr&eacute;dits  <input type="submit" name="Submit" value="Valider le vote"></div></form>');
			}
		}
	}


mysql_close($db);

?>        

</p>
</div>
<?php if($chat=="oui") 	{ if ((date("H")>=21) || (date("H")<=7)) { include("inc_bas_n_c.php"); } else { include("inc_bas_j_c.php"); } } else {	if ((date("H")>=21) || (date("H")<=7)) { include("inc_bas_n.php"); } else { include("inc_bas_j.php"); }	} ?>
<?php include("inc_bas_de_page.php"); ?>
