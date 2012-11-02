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
			Votre regrigérateur
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

for($i=1; $i != 7; $i++)
	{
	if(($_SESSION['case'.$i.'']=="Petit panier de courses") && ($deja!=1))
		{
		$sql = 'SELECT frigo,niveaufrigo FROM lieu_tbl WHERE num= "'.$_SESSION['num'].'" AND rue= "'.$_SESSION['lieu'].'"' ;
		$req = mysql_query($sql);
		$frigo = mysql_result($req,0,frigo);
		if($frigo=="Oui")
			{
			$niveau_max = 50;
			}
		elseif($frigo=="Ame")
			{
			$niveau_max = 100;
			}
		else
			{
			$niveau_max = 0;
			}
		$niveau = mysql_result($req,0,niveaufrigo);
		if($niveau<$niveau_max)
			{
			$deja = 1;
			$niveau = $niveau + 10;
			if($niveau>$niveau_max)
				{
				$niveau = $niveau_max;
				}
			$sql = 'UPDATE lieu_tbl SET niveaufrigo="'.$niveau.'" WHERE rue= "'.$_SESSION['lieu'].'" AND num= "'.$_SESSION['num'].'"' ;
			$req = mysql_query($sql);
			$sql = 'UPDATE principal_tbl SET case'.$i.' ="Vide" WHERE id= "'.$_SESSION['id'].'"' ;
			$req = mysql_query($sql);
			print("<center><strong>Vous venez de déposer un petit panier de courses dans le refrigérateur du</strong><br><br>");
			print('<i>'.$_SESSION['num'].' '.$_SESSION['rue'].'.</i><br><br><br>');
			print('Le nouveau niveau est de <i>'.$niveau.'/'.$niveau_max.'</i>. <br>Vous pouvez le consulter depuis la section <a href="engine=invlieu.php">inventaire du lieu</a>.<br><br>');
			}
		else
			{
			if($frigo=="Non")
				{
				print('<center><strong>Vous n\'avez pas de refrigérateur ici pour stocker la nourriture.</strong></center>');
				}
			else
				{
				print("<center><strong>Le refrigérateur est plein ici. Vous pouvez le consulter depuis la section <i>inventaire du lieu</i></strong></center><br>");
				}
			}
		}
	}
	
mysql_close($db);

?>

</p>
</div>
<?php if($chat=="oui") 	{ if ((date("H")>=21) || (date("H")<=7)) { include("inc_bas_n_c.php"); } else { include("inc_bas_j_c.php"); } } else {	if ((date("H")>=21) || (date("H")<=7)) { include("inc_bas_n.php"); } else { include("inc_bas_j.php"); }	} ?>
<?php include("inc_bas_de_page.php"); ?>
