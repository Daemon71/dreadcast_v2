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
	
	$_GET['type'] = ($_GET['type'] == "")?"tel":$_GET['type'];
?>
<?php if ((date("H")>=21) || (date("H")<=7)) { include("inc_haut_n.php"); } else { include("inc_haut_j.php");} ?>

<div id="haut">
	<?php include("inc_entete.php"); ?>
	<div class="haut_aligngauche">
		<p>
		<div class="titrepage">
			AITL 2.0
		</div>
		</p>
	</div>
</div>
<div id="centreaitl2">

	<div id="menuaitl2">
		<a <?php if(!ereg("dcn",$_GET['type']) && !ereg("dctv",$_GET['type']) && !ereg("upg",$_GET['type'])) print('class="selectionne"'); ?> href="engine=aitl2.php?type=tel">Canaux Impériaux</a>
		<a <?php if(ereg("dcn",$_GET['type'])) print('class="selectionne"'); ?> href="engine=aitl2.php?type=dcn">DC News</a>
		<a <?php if(ereg("dctv",$_GET['type'])) print('class="selectionne"'); ?> href="engine=aitl2.php?type=dctv">DC TV</a>
		<a <?php if(ereg("upg",$_GET['type'])) print('class="selectionne"'); ?> href="engine=aitl2.php?type=upg">Upgrades</a>
	</div>
	<div id="actionsaitl2">
	</div>
	<div id="contenuaitl2">

<img src="im_objets/loader.gif" alt="..." />
<?php 

$db = mysql_connect('localhost', 'CENSURE', 'CENSURE') or die("Erreur de connexion au serveur des messages.");
mysql_select_db('CENSURE',$db) or die("Erreur de connexion a la base de donnees des messages.");

$sql = 'SELECT case1,case2,case3,case4,case5,case6 FROM principal_tbl WHERE id= "'.$_SESSION['id'].'"' ;
$req = mysql_query($sql);
$_SESSION['case1'] = mysql_result($req,0,case1);
$_SESSION['case2'] = mysql_result($req,0,case2);
$_SESSION['case3'] = mysql_result($req,0,case3);
$_SESSION['case4'] = mysql_result($req,0,case4);
$_SESSION['case5'] = mysql_result($req,0,case5);
$_SESSION['case6'] = mysql_result($req,0,case6);

for($i=1; $i != 7; $i++)
	{
	if($_SESSION['case'.$i.'']=="AITL 2.0" || (statut($_SESSION['statut'])>=2 && $l!=1))
		{
		$l = 1;
		$sql = 'SELECT titre FROM articles_tbl WHERE id= "'.$_GET['id'].'"' ;
		$req = mysql_query($sql);
		$article = mysql_result($req,0,titre);
		$sql = 'SELECT * FROM votesarticles_tbl WHERE article= "'.$article.'" AND pseudo= "'.$_SESSION['pseudo'].'"' ;
		$req = mysql_query($sql);
		$resa = mysql_num_rows($req);
		if($resa==0)
			{
			if(($_GET['vote']>=0) && ($_GET['vote']<=10))
				{
				$sql = 'INSERT INTO votesarticles_tbl(id,article,pseudo,note) VALUES("","'.$article.'","'.$_SESSION['pseudo'].'","'.$_GET['vote'].'")' ;
				$req = mysql_query($sql);
				}
			print('<meta http-equiv="refresh" content="0 ; url=http://v2.dreadcast.net/ingame/engine=aitl2.php?type=art&id='.$_GET['id'].'"> ');
			}
		else
			{
			print('<meta http-equiv="refresh" content="0 ; url=http://v2.dreadcast.net/ingame/engine=aitl2.php?type=adj"> ');
			}
		}
	}
	
mysql_close($db);

?>

</div>
</div>
<?php if($chat=="oui") 	{ if ((date("H")>=21) || (date("H")<=7)) { include("inc_bas_n_c.php"); } else { include("inc_bas_j_c.php"); } } else {	if ((date("H")>=21) || (date("H")<=7)) { include("inc_bas_n.php"); } else { include("inc_bas_j.php"); }	} ?>
<?php include("inc_bas_de_page.php"); ?>
