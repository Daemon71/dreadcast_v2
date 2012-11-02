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
$_SESSION['drogue'] = mysql_result($req,0,drogue);
$_SESSION['fatigue'] = mysql_result($req,0,fatigue);
$_SESSION['lieu'] = strtolower($_SESSION['rue']);
$_SESSION['lieu'] = ucwords($_SESSION['lieu']);

$sql = 'SELECT statut FROM principal_tbl WHERE id= "'.$_SESSION['id'].'"' ;
$req = mysql_query($sql);
$_SESSION['statut'] = mysql_result($req,0,statut);

$sql = 'SELECT type,entreprise,points FROM principal_tbl WHERE id= "'.$_SESSION['id'].'"' ;
$req = mysql_query($sql);
$_SESSION['poste'] = mysql_result($req,0,type); 
$_SESSION['entreprise'] = mysql_result($req,0,entreprise); 
$_SESSION['points'] = mysql_result($req,0,points); 

if($_SESSION['points']!=999) { print('<meta http-equiv="refresh" content="0 ; url=http://v2.dreadcast.net/ingame/engine.php"> '); exit(); }

$sql = 'SELECT type FROM `e_'.str_replace(" ","_",''.$_SESSION['entreprise'].'').'_tbl` WHERE poste= "'.$_SESSION['poste'].'"' ;
$req = mysql_query($sql);
$_SESSION['type'] = mysql_result($req,0,type); 

$sql = 'SELECT num,type,rue,budget,benefices,logo FROM entreprises_tbl WHERE nom= "'.$_SESSION['entreprise'].'"' ;
$req = mysql_query($sql);
$_SESSION['nument'] = mysql_result($req,0,num); 
$_SESSION['rue'] = mysql_result($req,0,rue); 
$_SESSION['budget'] = mysql_result($req,0,budget); 
$type = mysql_result($req,0,type); 
$bene = mysql_result($req,0,benefices); 
$logo = mysql_result($req,0,logo); 

$sql = 'SELECT id FROM bourse_tbl WHERE entreprise= "'.$_SESSION['entreprise'].'"' ;
$req = mysql_query($sql);
$bourse = mysql_num_rows($req);

$sql = 'SELECT salaire FROM principal_tbl WHERE entreprise= "'.$_SESSION['entreprise'].'"' ;
$req = mysql_query($sql);
$res = mysql_num_rows($req);

$tot=0;
for($i=0; $i != $res ; $i++) 
	{
	$tot = $tot + mysql_result($req,$i,salaire); 
	}

$sql = 'SELECT chiffre,datea FROM chiffre_affaire_tbl WHERE entreprise= "'.$_SESSION['entreprise'].'" ORDER BY datea DESC' ;
$req = mysql_query($sql);
$res = mysql_num_rows($req);
for($i=16;$i!=16-$res;$i--)
	{
	$chiffre[''.$i.''] = mysql_result($req,16-$i,chiffre);
	$datea[''.$i.''] = mysql_result($req,16-$i,datea);
	}

mysql_close($db);
?>
<?php if ((date("H")>=21) || (date("H")<=7)) { include("inc_haut_n.php"); } else { include("inc_haut_j.php");} ?>

<div id="haut">
	<?php include("inc_entete.php"); ?>
	<div class="haut_aligngauche">
		<p>
		<div class="titrepage">
			Gestion d'entreprise
		</div>
		<b class="module4ie"><a href="engine=activite.php" class="module4"><img src="im_objets/icon_retour.gif" alt="Retour" />Retour</a></b>
</p>
	</div>
</div>
<div id="centre">
<p>

<table width="95%"  border="0" align="center">
          <tr><td>
            <div align="right"><strong>Capital :</strong> <?php print('<i>'.$_SESSION['budget'].'</i>'); ?> Cr&eacute;dits <a href="http://v2.dreadcast.net/info=capital.php" target="_blank">/!\</a> <br>
                  <a href="engine=tfonds.php">Transf&eacute;rer des fonds depuis votre inventaire</a><br>
<?php 
if(($type!="DOI") && ($type!="proprete") && ($type!="prison") && ($type!="chambre") && ($type!="conseil") && ($type!="di2rco") && ($type!="CIE") && ($type!="CIPE") && ($type!="police") && ($type!="dcn"))
	{
	$db = mysql_connect('localhost', 'CENSURE', 'CENSURE') or die("Erreur de connexion au serveur des messages.");
	mysql_select_db('CENSURE',$db) or die("Erreur de connexion a la base de donnees des messages.");
	$sql = 'SELECT id FROM financepridem_tbl WHERE entreprise= "'.$_SESSION['entreprise'].'"' ;
	$req = mysql_query($sql);
	$res = mysql_num_rows($req);
	if($res==0) 
		{ 
		print('<a href="engine=demandersubv.php">Demander une subvention à l\'Imperium</a>'); 
		} 
	else 
		{ 
		print('<a href="engine=demandersubv.php">Suivre la demande de subvention</a>'); 
		} 
	mysql_close($db);
	}
elseif($type=="conseil")
	{
	print('Votre prochain budget est de 10000 Crédits (vendredi soir)'); 
	}
elseif($type=="DOI")
	{
	print('Votre prochain budget est de 15000 Crédits (vendredi soir)'); 
	}
else
	{
	print('<a href="engine=suivrevotesbudget.php">Suivre les votes de votre prochain budget</a>'); 
	}

?>

</div>
              <table width="450" border="0" align="center" cellpadding="0" cellspacing="0">
                <tr>
                  <td><div align="left"><a href="engine=personnel.php">Le personnel</a><br>
                    
                    <?php 
if(($type!="boutique spécialisee") && ($type!="boutique armes") && ($type!="DOI") && ($type!="prison") && ($type!="ventes aux encheres") && ($type!="proprete") && ($type!="chambre") && ($type!="conseil") && ($type!="di2rco") && ($type!="CIE") && ($type!="banque"))
	{
	print('<a href="engine=bdd.php">Les bases de donn&eacute;es</a><br>'); 
	}

if(($type=="proprete") || ($type=="CIPE") || ($type=="ventes aux encheres") || ($type=="banque") || ($type=="DOI") || ($type=="prison") || ($type=="chambre") || ($type=="conseil") || ($type=="police") || ($type=="di2rco") || ($type=="centre de recherche") || ($type=="dcn"))
	{
	print('<a href="engine=services.php">Les services</a><br>'); 
	}
elseif($type!="CIE")
	{
	print('<a href="engine=stocks.php">Les stocks</a><br>'); 
	}
if($type=="centre de recherche")
	{
	print('<a href="engine=stocks.php">Les stocks</a><br>'); 
	}
if((statut($_SESSION['statut'])>=2) && ($type!="police") && ($type!="DOI") && ($type!="prison") && ($type!="proprete") && ($type!="chambre") && ($type!="conseil") && ($type!="di2rco"))
	{
	print('<a href="engine=courbe.php?titre=Chiffres de votre entreprise');
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
	print('">La courbe du chiffre d\'affaire</a> <br>'); 
	}
if((statut($_SESSION['statut'])>=2) && ($type!="DOI") && ($type!="police") && ($type!="prison") && ($type!="banque") && ($type!="proprete") && ($type!="chambre") && ($type!="conseil") && ($type!="di2rco") && ($type!="CIE"))
	{
	print('<a href="engine=listeacheteurs.php">Liste des derniers acheteurs</a><br />');
	}
if($type=="proprete")
	{
	print('<a href="engine=listeacheteurs.php">Liste des derniers acheteurs</a><br />');
	}
?>
                    <a href="engine=local.php">Le local</a><br />
					<a href="engine=pub.php">La publicité</a><br />
                    <a href="engine=message.php">Le message d'accueil </a></div></td>
                  <td align="center"><a href="engine=logo.php"><img src="<?php print($logo); ?>" width="100px" height="100px" border="1px"></a><br>
                    <em><a href="engine=logo.php">(Cliquez pour changer)</a></em></td>
                </tr>
              </table>              
              <p align="left">
                <?php 
	  if(($type!="chambre") && ($type!="police") && ($type!="di2rco") && ($type!="conseil") && ($type!="DOI") && ($type!="proprete") && ($type!="prison") && ($type!="CIPE") && ($type!="CIE") && ($type!="dcn") && ($bourse==0))
		  {
		  //print('<p align="left"><a href="#">Mettre votre entreprise en bourse</a></p>');
		  }
 	  elseif($bourse>0)
	  	{
		//print('<p align="left"><a href="#">Suivre l\'&eacute;volution de votre entreprise en bourse</a></p> ');
		}
	  ?>
              </p>
              <?php if(($type!="chambre") && ($type!="police") && ($type!="di2rco") && ($type!="conseil") && ($type!="DOI") && ($type!="proprete") && ($type!="prison") && ($type!="CIPE") && ($type!="CIE") && ($type!="dcn")) {  print('<p align="right"><strong>Co&ucirc;t total de l\'entreprise : </strong>'.$tot.' Cr&eacute;dits / Jour</p>'); } else {  print('<p align="right"><strong>Co&ucirc;t total de l\'organisation : </strong>'.$tot.' Cr&eacute;dits / Jour</p>'); } ?>
              <?php if(($type!="chambre") && ($type!="police") && ($type!="di2rco") && ($type!="conseil") && ($type!="DOI") && ($type!="proprete") && ($type!="prison") && ($type!="CIPE") && ($type!="CIE") && ($type!="dcn")) {  print('<p align="right"> <strong>Chiffre d\'affaire d\'aujourd\'hui :</strong> '.$bene.'  Cr&eacute;dits</p>'); } ?>
              <p align="center"><a href="engine=go.php?num=<? print(''.$_SESSION['nument'].''); ?>&rue=<? print(''.$_SESSION['rue'].''); ?>">Vous rendre &agrave; votre travail</a></p>
          </td></tr>
        </table>		

</p>
</div>
<?php if($chat=="oui") 	{ if ((date("H")>=21) || (date("H")<=7)) { include("inc_bas_n_c.php"); } else { include("inc_bas_j_c.php"); } } else {	if ((date("H")>=21) || (date("H")<=7)) { include("inc_bas_n.php"); } else { include("inc_bas_j.php"); }	} ?>
<?php include("inc_bas_de_page.php"); ?>
