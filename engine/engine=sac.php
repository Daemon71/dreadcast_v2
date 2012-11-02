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

$sql = 'SELECT id,rue,num,action,alim,sante,fatigue,soins FROM principal_tbl WHERE pseudo= "'.$_SESSION['pseudo'].'"' ;
$req = mysql_query($sql);
$_SESSION['id'] = mysql_result($req,0,id);
$_SESSION['rue'] = mysql_result($req,0,rue);
$_SESSION['num'] = mysql_result($req,0,num);
$_SESSION['action'] = mysql_result($req,0,action);
$_SESSION['alim'] = mysql_result($req,0,alim);
$_SESSION['sante'] = mysql_result($req,0,sante);
$_SESSION['fatigue'] = mysql_result($req,0,fatigue);
$_SESSION['soins'] = mysql_result($req,0,soins);
$_SESSION['lieu'] = strtolower($_SESSION['rue']);
$_SESSION['lieu'] = ucwords($_SESSION['lieu']);

mysql_close($db);
?>
<?php if ((date("H")>=21) || (date("H")<=7)) { include("inc_haut_n.php"); } else { include("inc_haut_j.php");} ?>

<div id="haut">
	<?php include("inc_entete.php"); ?>
	<div class="haut_aligngauche">
		<p>
		<div class="titrepage">
			Conteneur
		</div>
		<b class="module4ie"><a href="engine=inventaire.php" class="module4"><img src="im_objets/icon_retour.gif" alt="Retour" />Fermer</a></b>
</p>
	</div>
</div>
<div id="centre">
<p>

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
	if(($_SESSION['case'.$i.'']==str_replace("%20"," ",$_SERVER['QUERY_STRING'])) && ($l!=1))
		{
		$l = 1;
		$sql = 'SELECT * FROM objets_tbl WHERE nom= "'.$_SESSION['case'.$i.''].'"' ;
		$req = mysql_query($sql);
		$puissance = mysql_result($req,0,puissance);
		$image = mysql_result($req,0,image);
		$sql = 'SELECT * FROM sacs_tbl WHERE ido= "'.strstr($_SESSION['case'.$i.''],"-").'" ORDER BY id' ;
		$req = mysql_query($sql);
		$res = mysql_num_rows($req);
		print('<strong>'.substr($_SESSION['case'.$i.''],0,strpos($_SESSION['case'.$i.''],"-")).'</strong><br /><img src="im_objets/'.$image.'" border="0" /><br /><hr style="width:490px;margin-left:4px;" /><br />');
		for($i=0;$i!=$puissance;$i++)
			{
			if($i<$res)
				{
				//Il y a un emplacement occupé
				$sql1 = 'SELECT * FROM objets_tbl WHERE nom= "'.mysql_result($req,$i,emplacement).'"' ;
				$req1 = mysql_query($sql1);
				$image = mysql_result($req1,0,image);
				print('<a href="engine=sactsi.php?ids='.$_SERVER['QUERY_STRING'].'&ide='.mysql_result($req,$i,id).'"><img src="im_objets/'.$image.'" border="0" /></a> ');
				}
			else
				{
				//Emplacement vide simulé
				print('<img src="im_objets/vide.jpg" border="0" /> ');
				}
			}
		print('<br /><br /><hr style="width:490px;margin-left:4px;" /><br />Un clic sur un des objets ci-dessus le placera dans votre inventaire personnel.');
		}
	}
	
mysql_close($db);

?>

</p>
</div>
<?php if($chat=="oui") 	{ if ((date("H")>=21) || (date("H")<=7)) { include("inc_bas_n_c.php"); } else { include("inc_bas_j_c.php"); } } else {	if ((date("H")>=21) || (date("H")<=7)) { include("inc_bas_n.php"); } else { include("inc_bas_j.php"); }	} ?>
<?php include("inc_bas_de_page.php"); ?>
