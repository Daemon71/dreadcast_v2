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
			Tracte
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

if($_SERVER['QUERY_STRING'] != "" && !est_dans_inventaire('Tracte '.$_SERVER['QUERY_STRING']))
	{
	print('<meta http-equiv="refresh" content="0 ; url=http://v2.dreadcast.net/ingame/engine.php"> ');
	exit();
	}

$sql = 'SELECT id,rue,num,action FROM principal_tbl WHERE pseudo= "'.$_SESSION['pseudo'].'"' ;
$req = mysql_query($sql);
$_SESSION['id'] = mysql_result($req,0,id);
$_SESSION['lieu'] = mysql_result($req,0,rue);
$_SESSION['num'] = mysql_result($req,0,num);
$_SESSION['action'] = mysql_result($req,0,action);
$_SESSION['lieu'] = strtolower($_SESSION['lieu']);
$_SESSION['lieu'] = ucwords($_SESSION['lieu']);

if($_SERVER['QUERY_STRING']!="")
	{
	$sql = 'SELECT num,rue,tracte,logo FROM cerclesliste_tbl WHERE nom= "'.$_SERVER['QUERY_STRING'].'"' ;
	$req = mysql_query($sql);
	$res = mysql_num_rows($req);
	if($res>0)
		{
		print('<table width="90%"  border="0" align="center"><tr><td><p align="center">');
		print('<img src="'.mysql_result($req,0,logo).'" border="1px" width="100px" height="100px" />');
		print('</p></td><td>');
		print('<p align="center">Tracte du cercle: <strong>'.$_SERVER['QUERY_STRING'].'</strong><br />');
		print('Adresse: <a href="engine=go.php?rue='.mysql_result($req,0,rue).'&num='.mysql_result($req,0,num).'">'.mysql_result($req,0,num).' '.ucwords(mysql_result($req,0,rue)).'</a>');
		print('</p></td></tr></table>');
		print('<p align="center" id="tracte">'.mysql_result($req,0,tracte).'</p>');
		}
	else
		{
		print('<br />Malheureusement, ce cercle n\'existe plus.');
		}
	}
else
	{
	$sql = 'SELECT nom,tractes FROM cerclesliste_tbl WHERE rue= "'.$_SESSION['lieu'].'" AND num= "'.$_SESSION['num'].'"' ;
	$req = mysql_query($sql);
	$res = mysql_num_rows($req);
	
	if($res>0)
		{
		$nomcercle = mysql_result($req,0,nom);
		$tractes = mysql_result($req,0,tractes);
		if($tractes>0)
			{
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
				if(($_SESSION['case'.$i.'']=="Vide") && ($l!=1))
					{
					$l = 1;
					$tractes = $tractes - 1;
					$sql = 'UPDATE cerclesliste_tbl SET tractes= "'.$tractes.'" WHERE nom= "'.$nomcercle.'"' ;
					$req = mysql_query($sql);
					$sql = 'UPDATE principal_tbl SET case'.$i.' ="Tracte '.$nomcercle.'" WHERE id= "'.$_SESSION['id'].'"' ;
					$req = mysql_query($sql);
					print('<br />Vous venez de prendre un tracte.');
					}
				}
			if($l==0)
				{
				print('<br />Vous n\'avez pas d\'emplacement vide dans l\'inventaire.');
				}
			}
		}
	else
		{
		print('<meta http-equiv="refresh" content="0 ; url=http://v2.dreadcast.net/ingame/engine.php"> ');
		exit();
		}
	}

mysql_close($db);

?>

</p>
</div>
<?php if($chat=="oui") 	{ if ((date("H")>=21) || (date("H")<=7)) { include("inc_bas_n_c.php"); } else { include("inc_bas_j_c.php"); } } else {	if ((date("H")>=21) || (date("H")<=7)) { include("inc_bas_n.php"); } else { include("inc_bas_j.php"); }	} ?>
<?php include("inc_bas_de_page.php"); ?>
