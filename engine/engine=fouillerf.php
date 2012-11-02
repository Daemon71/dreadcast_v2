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
			Fouille d'un cadavre
		</div>
		<b class="module4ie"><a href="engine.php" class="module4"><img src="im_objets/icon_retour.gif" alt="Retour" />Retour</a></b>
</p>
	</div>
</div>
<div id="centre">
<p>

<?php	

$db = mysql_connect('localhost', 'CENSURE', 'CENSURE') or die("Erreur de connexion au serveur.");
mysql_select_db('CENSURE',$db) or die("Erreur de connexion a la base de donnees.");

$sql = 'SELECT id,rue,num FROM principal_tbl WHERE pseudo= "'.$_SESSION['pseudo'].'"' ;
$req = mysql_query($sql);
$_SESSION['id'] = mysql_result($req,0,id);
$_SESSION['lieu'] = mysql_result($req,0,rue);
$_SESSION['num'] = mysql_result($req,0,num);

if($_SESSION['num'] <= 0)
	{
	$num = 0;
	$lieu = "Rue";
	}
else
	{
	$num = $_SESSION['num'];
	$lieu = $_SESSION['lieu'];
	}
$sql = 'SELECT code FROM lieu_tbl WHERE rue= "'.$lieu.'" AND num= "'.$num.'"' ;
$req = mysql_query($sql);
$codounet = mysql_result($req,0,code);

if($_SESSION['code']!=$codounet)
	{
	mysql_close($db);
	print('<meta http-equiv="refresh" content="0 ; url=http://v2.dreadcast.net/ingame/engine.php"> ');
	exit();
	}

$_SESSION['cible'] = str_replace("%20"," ",''.$_GET['cible'].'');

$sql = 'SELECT id,rue,num,sante,action,event FROM principal_tbl WHERE pseudo= "'.$_SESSION['cible'].'"' ;
$req = mysql_query($sql);
$_SESSION['idc'] = mysql_result($req,0,id);
$_SESSION['numc'] = mysql_result($req,0,num);
$_SESSION['santec'] = mysql_result($req,0,sante);
$_SESSION['actionc'] = mysql_result($req,0,action);

if(!estVisible($_SESSION['cible'],25) || ($_SESSION['santec']!="0") || ($_SESSION['actionc']=="mort") || mysql_result($req,0,event) == 1)
	{
	print('<meta http-equiv="refresh" content="0 ; url=http://v2.dreadcast.net/ingame/engine=liste.php"> ');
	exit();
	}
else
	{
	
	$sql = 'SELECT case1,case2,case3,case4,case5,case6 FROM principal_tbl WHERE id= "'.$_SESSION['id'].'"' ;
	$req = mysql_query($sql);
	$_SESSION['case1'] = mysql_result($req,0,case1); 
	$_SESSION['case2'] = mysql_result($req,0,case2); 
	$_SESSION['case3'] = mysql_result($req,0,case3); 
	$_SESSION['case4'] = mysql_result($req,0,case4); 
	$_SESSION['case5'] = mysql_result($req,0,case5); 
	$_SESSION['case6'] = mysql_result($req,0,case6); 
	
	if($_GET['case']!="")
		{
		$sql = 'SELECT case'.$_GET['case'].' FROM principal_tbl WHERE id= "'.$_SESSION['idc'].'"' ;
		$req = mysql_query($sql);
		$ouin = 'case'.$_GET['case'].'';
		$objet = mysql_result($req,0,$ouin); 
		}
	elseif($_GET['arme']!="")
		{
		$sql = 'SELECT arme FROM principal_tbl WHERE id= "'.$_SESSION['idc'].'"' ;
		$req = mysql_query($sql);
		$objet = mysql_result($req,0,arme); 
		}
	elseif($_GET['vetements']!="")
		{
		$sql = 'SELECT vetements FROM principal_tbl WHERE id= "'.$_SESSION['idc'].'"' ;
		$req = mysql_query($sql);
		$objet = mysql_result($req,0,vetements); 
		}
	elseif($_GET['objet']!="")
		{
		$sql = 'SELECT objet FROM principal_tbl WHERE id= "'.$_SESSION['idc'].'"' ;
		$req = mysql_query($sql);
		$objet = mysql_result($req,0,objet); 
		}
	$sql = 'SELECT type FROM objets_tbl WHERE nom= "'.$objet.'"' ;
	$req = mysql_query($sql);
	$type = mysql_result($req,0,type); 
	if($type!="imp")
		{
		$trans = 0;
		for($i=1; $i != 7 ; $i++) 
			{
			if(($_SESSION['case'.$i.'']=="Vide") && ($trans==0))
				{
				$sql = 'UPDATE principal_tbl SET case'.$i.'="'.$objet.'" WHERE id="'.$_SESSION['id'].'"';
				$req = mysql_query($sql);
				if($_GET['case']!="")
					{
					$sql = 'UPDATE principal_tbl SET case'.$_GET['case'].'="Vide" WHERE id="'.$_SESSION['idc'].'"';
					$req = mysql_query($sql);
					}
				elseif($_GET['arme']!="")
					{
					$sql = 'UPDATE principal_tbl SET arme="Aucune" WHERE id="'.$_SESSION['idc'].'"';
					$req = mysql_query($sql);
					}
				elseif($_GET['vetements']!="")
					{
					$sql = 'UPDATE principal_tbl SET vetements="Veste" WHERE id="'.$_SESSION['idc'].'"';
					$req = mysql_query($sql);
					}
				elseif($_GET['objet']!="")
					{
					$sql = 'UPDATE principal_tbl SET objet="Aucun" WHERE id="'.$_SESSION['idc'].'"';
					$req = mysql_query($sql);
					}
				print("<p align='center'><strong>Objet acquis.</strong></p>");
				$trans = 1;
				$sql = 'INSERT INTO messages_tbl(id,auteur,cible,message,objet,moment) VALUES("","Dreadcast","'.$_SESSION["cible"].'","Presque inconscient, vous venez de vous faire dépouiller par quelqu\'un.<br />Il s\'agit de l\'objet '.$objet.'.","On vous dépouille !","'.time().'")' ;
				$req = mysql_query($sql);
				}
			}
		
		if($trans!=1)
			{
			print("<p align='center'><strong>Il n'y a pas d'emplacement vide dans votre inventaire personnel.</strong></p>");
			}
		}
	}

mysql_close($db);

?>
</p>
<p align="center"><a href="engine=fouiller.php?<?php print(''.$_SESSION['cible'].''); ?>">Continuer la fouille</a>

</p>
</div>
<?php if($chat=="oui") 	{ if ((date("H")>=21) || (date("H")<=7)) { include("inc_bas_n_c.php"); } else { include("inc_bas_j_c.php"); } } else {	if ((date("H")>=21) || (date("H")<=7)) { include("inc_bas_n.php"); } else { include("inc_bas_j.php"); }	} ?>
<?php include("inc_bas_de_page.php"); ?>
