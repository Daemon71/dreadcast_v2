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
			Transfert
		</div>
		<b class="module4ie"><a href="engine=inventaire.php" class="module4"><img src="im_objets/icon_retour.gif" alt="Retour" />Retour</a></b>
</p>
	</div>
</div>
<div id="centre">
<p>

<img src="im_objets/loader.gif" alt="..." /><br /><br />
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
	if(($_SESSION['case'.$i.'']==str_replace("%20"," ",$_GET['ido'])) && ($l!=1))
		{
		$l = 1;
		if($_SESSION['case'.$_GET['idc'].'']!="Vide")
			{
			$t = 1;
			$sql = 'SELECT type FROM objets_tbl WHERE nom= "'.$_SESSION['case'.$_GET['idc'].''].'"' ;
			$req = mysql_query($sql);
			$type = mysql_result($req,0,type);
			if($type!="sac")
				{
				$q = 1;
				$sql = 'SELECT puissance FROM objets_tbl WHERE nom= "'.$_SESSION['case'.$i.''].'"' ;
				$req = mysql_query($sql);
				$puissance = mysql_result($req,0,puissance);
				$sql = 'SELECT * FROM sacs_tbl WHERE ido= "'.strstr($_SESSION['case'.$i.''],"-").'"' ;
				$req = mysql_query($sql);
				$res = mysql_num_rows($req);
				if($res<$puissance)
					{
					$sql = 'INSERT INTO sacs_tbl(id,ido,emplacement) VALUES("","'.strstr($_SESSION['case'.$i.''],"-").'","'.$_SESSION['case'.$_GET['idc'].''].'")' ;
					$req = mysql_query($sql);
					$sql = 'UPDATE principal_tbl SET case'.$_GET['idc'].'= "Vide" WHERE id= "'.$_SESSION['id'].'"' ;
					$req = mysql_query($sql);
					}
				}
			}
		}
	}

if($l==0)
	{
	print('<strong>Ce conteneur n\'est pas sur vous.</strong>');
	}
elseif($t==0)
	{
	print('<strong>Vous ne pouvez pas mettre d\'emplacement vide dans votre conteneur.</strong>');
	}
elseif($q==0)
	{
	print('<strong>Vous ne pouvez pas mettre de conteneur dans votre conteneur.</strong>');
	}
elseif($res==$puissance)
	{
	print('<strong>Il n\'y a plus de place dans votre conteneur.</strong>');
	}
else
	{
	print('<meta http-equiv="refresh" content="0 ; url=http://v2.dreadcast.net/ingame/engine=inventaire.php"> ');
	}

mysql_close($db);

?>

</p>
</div>
<?php if($chat=="oui") 	{ if ((date("H")>=21) || (date("H")<=7)) { include("inc_bas_n_c.php"); } else { include("inc_bas_j_c.php"); } } else {	if ((date("H")>=21) || (date("H")<=7)) { include("inc_bas_n.php"); } else { include("inc_bas_j.php"); }	} ?>
<?php include("inc_bas_de_page.php"); ?>
