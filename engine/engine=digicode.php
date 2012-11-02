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
			Poser un digicode
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
	if(($_SESSION['case'.$i.'']=="Digicode") && ($deja!=1))
		{
		$sql = 'SELECT id FROM proprietaire_tbl WHERE rue= "'.$_SESSION['lieu'].'" AND num= "'.$_SESSION['num'].'" AND pseudo= "'.$_SESSION['pseudo'].'"' ;
		$req = mysql_query($sql);
		$res = mysql_num_rows($req);
		if($res>0)
			{
			$sql = 'SELECT code FROM lieu_tbl WHERE num= "'.$_SESSION['num'].'" AND rue= "'.$_SESSION['lieu'].'"' ;
			$req = mysql_query($sql);
			$dejacode = mysql_result($req,0,code);
			if($dejacode==0)
				{
				$deja = 1;
				$cod = rand(10,99);
				$sql = 'UPDATE lieu_tbl SET code="'.$cod.'" WHERE rue= "'.$_SESSION['lieu'].'" AND num= "'.$_SESSION['num'].'"' ;
				$req = mysql_query($sql);
				$sql = 'UPDATE principal_tbl SET case'.$i.' ="Vide" WHERE id= "'.$_SESSION['id'].'"' ;
				$req = mysql_query($sql);
				print("<center><strong>Vous venez de poser un digicode au</strong><br><br>");
				print('<i>'.$_SESSION['num'].' '.$_SESSION['rue'].'.</i><br><br><br>');
				print('Le digicode par d&eacute;faut est <i>'.$cod.'</i>. <br>Vous pouvez le consulter et le changer depuis la section <a href="engine=logement.php">logement</a>.<br><br>');
				}
			else
				{
				print("<center><strong>Il y a d&eacute;j&agrave; un digicode ici. Vous pouvez le consulter depuis la section <i>logement</i></strong></center><br>");
				}
			}
		else
			{
			print("<center><strong>Vous ne pouvez poser un digicode que chez vous.</strong><br><br>Pour vous rendre chez vous, vous devez cliquer sur 'Logement' puis 'Rentrer chez vous'.</center>");
			}
		}
	}
	
mysql_close($db);

?>

</p>
</div>
<?php if($chat=="oui") 	{ if ((date("H")>=21) || (date("H")<=7)) { include("inc_bas_n_c.php"); } else { include("inc_bas_j_c.php"); } } else {	if ((date("H")>=21) || (date("H")<=7)) { include("inc_bas_n.php"); } else { include("inc_bas_j.php"); }	} ?>
<?php include("inc_bas_de_page.php"); ?>
