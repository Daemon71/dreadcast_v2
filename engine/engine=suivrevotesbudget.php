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
			Budgets Imp&eacute;riaux
		</div>
		<b class="module4ie"><a href="engine=gestion.php" class="module4"><img src="im_objets/icon_retour.gif" alt="Retour" />Retour</a></b>
</p>
	</div>
</div>
<div id="centre">
<p>

<?php 

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

if($bdd=="") { print('<meta http-equiv="refresh" content="0 ; url=http://v2.dreadcast.net/ingame/engine.php"> '); exit(); }

if((ucwords($_SESSION['rue'])!=ucwords($rueent)) || ($_SESSION['num']!=$nument))
	{
	print('<p align="center"><strong>Vous devez &ecirc;tre sur place pour pouvoir acc&eacute;der &agrave; cette rubrique.</strong></p>'); 
	$l = 1;
	}

if((($type=="prison") || ($type=="proprete") || ($type=="police") || ($type=="chambre")  || ($type=="di2rco") || ($type=="CIE") || ($type=="CIPE") || ($type=="dcn")) && ($l!=1))
	{
	print('<p align="center">Voici les budgets actuels :</p>');
	$sql = 'SELECT prison,proprete,cipe,cie,chambre,di2rco,police,dcn FROM finance_tbl' ;
	$req = mysql_query($sql);
	$res = mysql_num_rows($req);
	$prison = 0;
	$proprete = 0;
	$cipe = 0;
	$cie = 0;
	$chambre = 0;
	$di2rco = 0;
	$police = 0;
	$dcn = 0;
	for($f=0;$f!=$res;$f++)
		{
		$prison = $prison + mysql_result($req,$f,prison);
		$proprete = $proprete + mysql_result($req,$f,proprete);
		$cipe = $cipe + mysql_result($req,$f,cipe);
		$cie = $cie + mysql_result($req,$f,cie);
		$chambre = $chambre + mysql_result($req,$f,chambre);
		$di2rco = $di2rco + mysql_result($req,$f,di2rco);
		$police = $police + mysql_result($req,$f,police);
		$dcn = $dcn + mysql_result($req,$f,dcn);
		}
	if($res!=0)
		{
		$prison = ceil($prison / $res);
		$proprete = ceil($proprete / $res);
		$cipe = ceil($cipe / $res);
		$cie = ceil($cie / $res);
		$chambre = ceil($chambre / $res);
		$di2rco = ceil($di2rco / $res);
		$police = ceil($police / $res);
		$dcn = ceil($dcn / $res);
		}
	print('<table width="480" border="1" align="center" cellpadding="0" cellspacing="0">
	  <tr bgcolor="#B6B6B6">
		<th height="13" scope="col">Organisation Imp&eacute;riale</th>
		<th scope="col">Budgets actuels</th>
		</tr>
	  <tr>
		<td><div align="center">Propret&eacute; de la ville </div></td>
		<td><div align="center">'.$proprete.'</div></td>
		</tr>
	  <tr>
		<td><div align="center">Prisons de la ville </div></td>
		<td><div align="center">'.$prison.'</div></td>
		</tr>
	  <tr>
		<td><div align="center">Centre d\'Information Pour l\'Emploi (CIPE) </div></td>
		<td><div align="center">'.$cipe.'</div></td>
		</tr>
	  <tr>
		<td><div align="center">Centre Imp&eacute;rial d\'Enseignement (CIE) </div></td>
		<td><div align="center">'.$cie.'</div></td>
		</tr>
	  <tr>
		<td><div align="center">Police</div></td>
		<td><div align="center">'.$police.'</div></td>
		</tr>
	  <tr>
		<td><div align="center">D&eacute;partement Imp&eacute;rial de Recherche et R&eacute;pression du Crime Organis&eacute; (DI2RCO) </div></td>
		<td><div align="center">'.$di2rco.'</div></td>
		</tr>
	  <tr>
		<td><div align="center">Chambre des Lois</div></td>
		<td><div align="center">'.$chambre.'</div></td>
		</tr>
	  <tr>
		<td><div align="center">DreadCast Network</div></td>
		<td><div align="center">'.$dcn.'</div></td>
		</tr>
	</table>');
	}


mysql_close($db);

?>


</p>
</div>
<?php if($chat=="oui") 	{ if ((date("H")>=21) || (date("H")<=7)) { include("inc_bas_n_c.php"); } else { include("inc_bas_j_c.php"); } } else {	if ((date("H")>=21) || (date("H")<=7)) { include("inc_bas_n.php"); } else { include("inc_bas_j.php"); }	} ?>
<?php include("inc_bas_de_page.php"); ?>
