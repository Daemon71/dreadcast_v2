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

$sql = 'SELECT ecart,distance FROM objets_tbl WHERE nom= "'.str_replace("%20"," ",$_GET['ids']).'"' ;
$req = mysql_query($sql);
$secure = mysql_result($req,0,ecart);
$vraicode = mysql_result($req,0,distance);

if($secure==1)
	{
	if($_POST['code']==$vraicode) $boncode = 1;
	elseif(!empty($_POST['code'])) print('Mauvais code.');
	}
else
	{
	$boncode = 1;
	}

if($boncode==1)
	{
	for($i=1; $i != 7; $i++)
		{
		if(($_SESSION['case'.$i.'']==str_replace("%20"," ",$_GET['ids'])) && ($l!=1))
			{
			$l = 1;
			for($p=1; $p != 7; $p++)
				{
				if(($_SESSION['case'.$p.'']=="Vide") && ($t!=1))
					{
					$t = 1;
					$sql = 'SELECT * FROM sacs_tbl WHERE ido= "'.strstr($_SESSION['case'.$i.''],"-").'" AND id= "'.$_GET['ide'].'"' ;
					$req = mysql_query($sql);
					$res = mysql_num_rows($req);
					if($res>0)
						{
						$objet = mysql_result($req,0,emplacement);
						$sql = 'DELETE FROM sacs_tbl WHERE ido= "'.strstr($_SESSION['case'.$i.''],"-").'" AND id= "'.$_GET['ide'].'"' ;
						$req = mysql_query($sql);
						$sql = 'UPDATE principal_tbl SET case'.$p.'= "'.$objet.'" WHERE id= "'.$_SESSION['id'].'"' ;
						$req = mysql_query($sql);
						}
					}
				}
			}
		}
	}

if($secure==1 && $boncode==0) print('<form action="#" method="post"><strong>Code de déverrouillage :</strong> <input name="code" type="text" size="3" maxlength="3" /> <input type="submit" value="Valider" /></form>');
elseif($l==0) print('<strong>Ce conteneur n\'est pas sur vous.</strong>');
elseif($t==0) print('<strong>Vous n\'avez pas d\'emplacement vide dans votre inventaire.</strong>');
elseif($res==0) print('<strong>L\'objet que vous tentez de transférer n\'est pas dans votre conteneur.</strong>');
else print('<meta http-equiv="refresh" content="0 ; url=http://v2.dreadcast.net/ingame/engine=inventaire.php"> ');

mysql_close($db);

?>

</p>
</div>
<?php if($chat=="oui") 	{ if ((date("H")>=21) || (date("H")<=7)) { include("inc_bas_n_c.php"); } else { include("inc_bas_j_c.php"); } } else {	if ((date("H")>=21) || (date("H")<=7)) { include("inc_bas_n.php"); } else { include("inc_bas_j.php"); }	} ?>
<?php include("inc_bas_de_page.php"); ?>
