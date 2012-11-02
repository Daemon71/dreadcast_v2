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
			Poser un refrigérateur
		</div>
		<b class="module4ie"><a href="engine=inventaire.php" class="module4"><img src="im_objets/icon_retour.gif" alt="Retour" />Retour</a></b>
</p>
	</div>
</div>
<div id="centre">
<p>

<?php 

$db = mysql_connect('localhost', 'CENSURE', 'CENSURE') or die("Erreur de connexion au serveur des messages.");
mysql_select_db('CENSURE',$db) or die("Erreur de connexion a la base de donnees des messages.");

$sql = 'SELECT id,rue,num FROM principal_tbl WHERE pseudo= "'.$_SESSION['pseudo'].'"' ;
$req = mysql_query($sql);
$_SESSION['id'] = mysql_result($req,0,id);
$_SESSION['lieu'] = ucwords(mysql_result($req,0,rue));
$_SESSION['num'] = mysql_result($req,0,num);

$sql = 'SELECT case1,case2,case3,case4,case5,case6 FROM principal_tbl WHERE id= "'.$_SESSION['id'].'"' ;
$req = mysql_query($sql);
$_SESSION['case1'] = mysql_result($req,0,case1);
$_SESSION['case2'] = mysql_result($req,0,case2);
$_SESSION['case3'] = mysql_result($req,0,case3);
$_SESSION['case4'] = mysql_result($req,0,case4);
$_SESSION['case5'] = mysql_result($req,0,case5);
$_SESSION['case6'] = mysql_result($req,0,case6);

if($_SERVER['QUERY_STRING']=="Refrigerateur")
	{
	for($i=1; $i != 7; $i++)
		{
		if(($_SESSION['case'.$i.'']=="Refrigerateur") && ($deja!=1))
			{
			$sql = 'SELECT id FROM proprietaire_tbl WHERE rue= "'.$_SESSION['lieu'].'" AND num= "'.$_SESSION['num'].'" AND pseudo= "'.$_SESSION['pseudo'].'"' ;
			$req = mysql_query($sql);
			$res = mysql_num_rows($req);
			if($res>0)
				{
				$sql = 'SELECT frigo FROM lieu_tbl WHERE num= "'.$_SESSION['num'].'" AND rue= "'.$_SESSION['lieu'].'"' ;
				$req = mysql_query($sql);
				$frigo = mysql_result($req,0,frigo);
				if($frigo=="Non")
					{
					$deja = 1;
					$sql = 'UPDATE lieu_tbl SET frigo="Oui" WHERE rue= "'.$_SESSION['lieu'].'" AND num= "'.$_SESSION['num'].'"' ;
					$req = mysql_query($sql);
					$sql = 'UPDATE principal_tbl SET case'.$i.' ="Vide" WHERE id= "'.$_SESSION['id'].'"' ;
					$req = mysql_query($sql);
					print("<center><strong>Vous venez de poser un refrigérateur au</strong><br><br>");
					print('<i>'.$_SESSION['num'].' '.$_SESSION['rue'].'.</i><br><br><br>');
					print('Vous pourrez stocker 50 repas à l\'intérieur. Votre personnage pourra ainsi se nourrir manuellement mais également lorsqu\'il se reposera chez lui.<br>Vous pouvez consulter le niveau depuis la section <a href="engine=invlieu.php">inventaire du lieu</a>.<br><br>');
					}
				else
					{
					print("<center><strong>Il y a d&eacute;j&agrave; un refrigérateur ici. Vous pouvez le consulter depuis la section <i>logement</i></strong></center><br>");
					}
				}
			else
				{
				print("<center><strong>Vous ne pouvez poser un refrigérateur que si vous êtes propriétaire du logement.</strong><br />Prenez contact avec votre locataire.</center>");
				}
			}
		}
	}
elseif($_SERVER['QUERY_STRING']=="FR%20Americain")
	{
	for($i=1; $i != 7; $i++)
		{
		if(($_SESSION['case'.$i.'']=="FR Americain") && ($deja!=1))
			{
			$sql = 'SELECT id FROM proprietaire_tbl WHERE rue= "'.$_SESSION['lieu'].'" AND num= "'.$_SESSION['num'].'" AND pseudo= "'.$_SESSION['pseudo'].'"' ;
			$req = mysql_query($sql);
			$res = mysql_num_rows($req);
			if($res>0)
				{
				$sql = 'SELECT frigo FROM lieu_tbl WHERE num= "'.$_SESSION['num'].'" AND rue= "'.$_SESSION['lieu'].'"' ;
				$req = mysql_query($sql);
				$frigo = mysql_result($req,0,frigo);
				if($frigo=="Non")
					{
					$deja = 1;
					$sql = 'UPDATE lieu_tbl SET frigo="Ame" WHERE rue= "'.$_SESSION['lieu'].'" AND num= "'.$_SESSION['num'].'"' ;
					$req = mysql_query($sql);
					$sql = 'UPDATE principal_tbl SET case'.$i.' ="Vide" WHERE id= "'.$_SESSION['id'].'"' ;
					$req = mysql_query($sql);
					print("<center><strong>Vous venez de poser un refrigérateur américain au</strong><br><br>");
					print('<i>'.$_SESSION['num'].' '.$_SESSION['rue'].'.</i><br><br><br>');
					print('Vous pourrez stocker 100 repas à l\'intérieur. Votre personnage pourra ainsi se nourrir manuellement mais également lorsqu\'il se reposera chez lui.<br>Vous pouvez consulter le niveau depuis la section <a href="engine=invlieu.php">inventaire du lieu</a>.<br><br>');
					}
				else
					{
					print("<center><strong>Il y a d&eacute;j&agrave; un refrigérateur ici. Vous pouvez le consulter depuis la section <i>logement</i></strong></center><br>");
					}
				}
			else
				{
				print("<center><strong>Vous ne pouvez poser un refrigérateur que chez vous.</strong><br><br>Pour vous rendre chez vous, vous devez cliquer sur 'Logement' puis 'Rentrer chez vous'.</center>");
				}
			}
		}
	}
else
	{
	print("<center><strong>Erreur.</strong></center>");
	}
	
mysql_close($db);

?>

</p>
</div>
<?php if($chat=="oui") 	{ if ((date("H")>=21) || (date("H")<=7)) { include("inc_bas_n_c.php"); } else { include("inc_bas_j_c.php"); } } else {	if ((date("H")>=21) || (date("H")<=7)) { include("inc_bas_n.php"); } else { include("inc_bas_j.php"); }	} ?>
<?php include("inc_bas_de_page.php"); ?>
