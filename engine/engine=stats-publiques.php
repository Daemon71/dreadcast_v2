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
			Statistiques Impériales
		</div>
		<b class="module4ie"><a href="engine.php" class="module4"><img src="im_objets/icon_retour.gif" alt="Retour" />Retour</a></b>
</p>
	</div>
</div>
<div id="centre" style="overflow:auto;padding-bottom:20px;width:490px;height:250px;">

<?php	

$db = mysql_connect('localhost', 'CENSURE', 'CENSURE') or die("Erreur de connexion au serveur.");
mysql_select_db('CENSURE',$db) or die("Erreur de connexion a la base de donnees.");

$sql = 'SELECT num,rue FROM lieux_speciaux_tbl WHERE type="stats"';
$req = mysql_query($sql);
$res = mysql_num_rows($req);
if($res)
	{
	if($_SESSION['num'] != mysql_result($req,0,num) || strtolower($_SESSION['lieu']) != strtolower(mysql_result($req,0,rue)))
		{
		print('<meta http-equiv="refresh" content="0 ; url=http://v2.dreadcast.net/ingame/engine.php"> ');
		exit();
		}
	}
	
if(empty($_GET['a']))
	{
	print('<meta http-equiv="refresh" content="0 ; url=http://v2.dreadcast.net/ingame/engine.php"> ');
	exit();
	}
elseif($_GET['a']>=1)
	{
	$sql = 'SELECT pseudo,sexe,race FROM principal_tbl WHERE jours_de_jeu = '.($_GET['a']-1).' ORDER BY id';
	$req = mysql_query($sql);
	$res = mysql_num_rows($req);
	
	$diff_races = array();
	$pourc_races = "";
	$races = "";
	
	$diff_sexes = array();
	$pourc_sexes = "";
	
	for($i=0;$i<$res;$i++) {
		$pseudos[] = mysql_result($req,$i,pseudo);
		$sexe = mysql_result($req,$i,sexe);
		$race = mysql_result($req,$i,race);
		
		if($diff_races[$race]++ == 0) $races .= $race.',';
		$diff_sexes[$sexe]++;
	}
	
	$races = str_replace(" ",", ",trim(str_replace(","," ",$races)));
	
	foreach($diff_races as $race => $nb) {
		$pourc_races .= '<br /> - '.$race.' ('.substr(($nb*100)/$res,0,4).'%)';
	}
	
	$pourc_sexes = '<br /> - Femmes ('.(($res)?substr(($diff_sexes['Femme']*100)/$res,0,4):0).'%)
					<br /> - Hommes ('.(($res)?substr(($diff_sexes['Homme']*100)/$res,0,4):0).'%)';
	
	print('<h2>Statistiques sur les nouveaux arrivants</h2>
	<p style="margin-bottom:10px;">Ici sont présentes toutes les informations sur les personnes arrivées '.(($_GET['a'] == 2)?'hier':(($_GET['a'] == 1)?'il y a moins d\'une journée':'il y a '.($_GET['a']-1).' jours')).' dans la ville.<br />
	<a href="engine=stats-publiques.php?a='.($_GET['a']+1).'">Jour précédent</a>'.(($_GET['a'] == 1)?'':' - <a href="engine=stats-publiques.php?a='.($_GET['a']-1).'">Jour suivant</a>').'</p>
	<h3>Informations générales</h3>
	<p style="text-align:left;"><strong>Nombres de nouveaux arrivants</strong> : '.$res.'<br />
	<strong>Parité</strong> : '.$pourc_sexes.'</p>
	<h3>Informations raciales</h3>
	<p style="text-align:left;"><strong>Races représentées</strong> : '.$races.'<br />
	<strong>Pourcentage des races</strong> : '.$pourc_races.'</p>
	<h3>Nouveaux arrivants</h3>
	<table>');
	
	if($res <= 10) $imax = 1;
	elseif($res <= 20) $imax = 2;
	elseif($res <= 40) $imax = 3;
	else $imax = 4;
	
	for($i=0;$i<$res;$i++) {
		print('<tr>');
		
		$j=0;
		for($j=0;$j<$imax;$j++) {
			if($pseudos[$i+$j]=="") break;
			print('<td style="width:100px;">'.$pseudos[($i+$j)].'</td>');
		}
		$i += $j-1;
		
		print('</tr>');
	}
	
	print('</table>');
	}
else
	{
	print('En cours de construction');
	}

	
mysql_close($db);

?>

</div>
<?php if($chat=="oui") 	{ if ((date("H")>=21) || (date("H")<=7)) { include("inc_bas_n_c.php"); } else { include("inc_bas_j_c.php"); } } else {	if ((date("H")>=21) || (date("H")<=7)) { include("inc_bas_n.php"); } else { include("inc_bas_j.php"); }	} ?>
<?php include("inc_bas_de_page.php"); ?>
