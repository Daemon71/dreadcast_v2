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
			Descriptif du poste
		</div>
		<b class="module4ie"><a href="engine=emplois.php<?php print('?typejob='.$_GET['typejob'].'&lieujob='.$_GET['lieujob']); ?>" class="module4"><img src="im_objets/icon_retour.gif" alt="Retour" />Retour</a></b>
	</p>
	</div>
</div>
<div id="centre_imperium">

<p id="location">Centre Imp&eacute;rial Pour l'Emploi</p>

<p id="textelse">

<?php 
$idtrav = $_GET['id'];

if($_GET['idpage']!="")
	{
	$idpage = $_GET['idpage'];
	}
else
	{
	$idpage = 0;
	}

$db = mysql_connect('localhost', 'CENSURE', 'CENSURE') or die("Erreur de connexion au serveur.");
mysql_select_db('CENSURE',$db) or die("Erreur de connexion a la base de donnees.");

$nomr = $_GET['entreprise'];
$poster = $_GET['poste'];
$typer = $_GET['type'];

$sql1 = 'SELECT type,salaire,bdd,nbrepostes,nbreactuel,mincomp,candidature,mintrav,sinon,bonus FROM `e_'.str_replace(" ","_",''.$nomr.'').'_tbl` WHERE poste= "'.$poster.'" AND type="'.$typer.'"' ;
$req1 = mysql_query($sql1);
$res1 = mysql_num_rows($req1);

$typer = mysql_result($req1,0,type);
$salairer = mysql_result($req1,0,salaire);
$bddr = mysql_result($req1,0,bdd);
$nbrepostesr = mysql_result($req1,0,nbrepostes);
$nbreactuelr = mysql_result($req1,0,nbreactuel);
$mincompr = mysql_result($req1,0,mincomp);
$candidaturer = mysql_result($req1,0,candidature);
$mintravr = mysql_result($req1,0,mintrav);
$sinonr = mysql_result($req1,0,sinon);
$bonusr = mysql_result($req1,0,bonus);
$pd = $nbrepostesr - $nbreactuelr;
if($pd>0)
	{
	print('<strong>Descriptif du poste</strong><br /><br />
	<strong>Nom de l\'entreprise : </strong><em>'.$nomr.' </em><br /><br />
	<strong>Nom du poste :</strong> <em>'.$poster.' </em><br />
	<strong>Type de poste : </strong><em>'.ucwords($typer).' ');
	
	if(($typer=="maintenance") || ($typer=="securite") || ($typer=="vendeur") || ($typer=="banquier") || ($typer=="serveur") || ($typer=="profmeca") || ($typer=="proftechno") || ($typer=="chauffeur") || ($typer=="profgestion") || ($typer=="profeco") || ($typer=="chercheur") || ($typer=="profcombat") || ($typer=="profconduite") || ($typer=="profmed") || ($typer=="proftir") || ($typer=="reparateur") || ($typer=="medecin") || ($typer=="hote") || ($typer=="technicien"))
		{
		print('(travail <a href="http://v2.dreadcast.net/info=passif.php" target="_blank">passif</a>)</em><br /><br />');
		}
	else
		{
		print('(travail <a href="http://v2.dreadcast.net/info=actif.php" target="_blank">actif</a>)</em><br /><br />');
		}
	
	if($mintravr>0)
		{
		print('<strong>Minimum de travail :</strong><em> '.$mintravr.' Heures / Jour</em><br />');
		if($sinonr=="lj")
			{
			print('<strong>Sinon :</strong><em> Licenci&eacute; &agrave; la fin de la journ&eacute;e</em><br />');
			}
		elseif($sinonr=="ls")
			{
			print('<strong>Sinon :</strong><em> Licenci&eacute; &agrave; la fin de la semaine</em><br />');
			}
		elseif($sinonr=="pp")
			{
			print('<strong>Sinon :</strong><em> N\'est pas pay&eacute;</em><br />');
			}
		elseif($sinonr=="ma")
			{
			print('<strong>Sinon :</strong><em> Message automatique au directeur</em><br />');
			}
		}
	if($mincompr>0)
		{
		if(($typer=="vendeur") || ($typer=="vendeur") || ($typer=="banquier") || ($typer=="profeco"))
			{
			print('<strong>Minimum requis en &eacute;conomie :</strong><em> '.$mincompr.'</em><br />');
			}
		elseif($typer=="maintenance")
			{
			print('<strong>Minimum requis en maintenance :</strong><em> '.$mincompr.'</em><br />');
			}
		elseif($typer=="securite")
			{
			print('<strong>Minimum requis en observation :</strong><em> '.$mincompr.'</em><br />');
			}
		elseif(($typer=="directeur") || ($typer=="profgestion") || ($typer=="chef"))
			{
			print('<strong>Minimum requis en gestion :</strong><em> '.$mincompr.'</em><br />');
			}
		elseif(($typer=="serveur") || ($typer=="hote"))
			{
			print('<strong>Minimum requis en service :</strong><em> '.$mincompr.'</em><br />');
			}
		elseif(($typer=="chauffeur") || ($typer=="profconduite"))
			{
			print('<strong>Minimum requis en conduite :</strong><em> '.$mincompr.'</em><br />');
			}
		elseif($typer=="chercheur")
			{
			print('<strong>Minimum requis en recherche :</strong><em> '.$mincompr.'</em><br />');
			}
		elseif($typer=="profcombat")
			{
			print('<strong>Minimum requis en combat :</strong><em> '.$mincompr.'</em><br />');
			}
		elseif(($typer=="profmed") || ($typer=="medecin"))
			{
			print('<strong>Minimum requis en m&eacute;decine :</strong><em> '.$mincompr.'</em><br />');
			}
		elseif($typer=="proftir")
			{
			print('<strong>Minimum requis en tir :</strong><em> '.$mincompr.'</em><br />');
			}
		elseif(($typer=="reparateur") || ($typer=="technicien"))
			{
			print('<strong>Minimum requis en m&eacute;canique :</strong><em> '.$mincompr.'</em><br />');
			}
		}
	if($salairer>0)
		{
		print('<strong>Salaire :</strong> <em> '.$salairer.' Cr&eacute;dits / Jour </em><br />');
		}
	else
		{
		print('<strong>Salaire :</strong> <em> Pas de salaire</em><br />');
		}
	if(($bonusr>0) && ($mintravr>0))
		{
		print('<strong>Heures sup :</strong><em> '.$bonusr.' Cr&eacute;dits / Heure</em><br />');
		}
	print('<strong>Poste(s) disponible(s) :</strong><em> ');
	if($pd<15)
		{
		print(''.$pd.' ');
		}
	else
		{
		print('Illimit&eacute;');
		}
	if($candidaturer!="")
		{
		$sql = 'SELECT id FROM candidatures_tbl WHERE poste= "'.$poster.'" AND entreprise= "'.$nomr.'"' ;
		$req = mysql_query($sql);
		$res = mysql_num_rows($req);
		print('<br />('.$res.' candidatures actuellement)');
		}
	print('</em><br /><br />
	<form id="maforme" name="form1" method="post" action="engine=emploif.php?ent='.$nomr.'&poste='.$poster.'&type='.$typer.'">');
	if($candidaturer!="")
		{
		print('<input id="valid" type="submit" name="Submit2" value="Poser une candidature">
		</form>');
		}
	else
		{
		print('<input id="valid" type="submit" name="Submit2" value="Prendre le poste">
		</form>');
		}
	}

mysql_close($db);

?>
</p>


</div>
<?php if($chat=="oui") 	{ if ((date("H")>=21) || (date("H")<=7)) { include("inc_bas_n_c.php"); } else { include("inc_bas_j_c.php"); } } else {	if ((date("H")>=21) || (date("H")<=7)) { include("inc_bas_n.php"); } else { include("inc_bas_j.php"); }	} ?>
<?php include("inc_bas_de_page.php"); ?>
