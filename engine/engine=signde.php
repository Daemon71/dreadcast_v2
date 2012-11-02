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
			Recueil
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

$sql = 'SELECT case1,case2,case3,case4,case5,case6 FROM principal_tbl WHERE id= "'.$_SESSION['id'].'"' ;
$req = mysql_query($sql);
$_SESSION['case1'] = mysql_result($req,0,case1);
$_SESSION['case2'] = mysql_result($req,0,case2);
$_SESSION['case3'] = mysql_result($req,0,case3);
$_SESSION['case4'] = mysql_result($req,0,case4);
$_SESSION['case5'] = mysql_result($req,0,case5);
$_SESSION['case6'] = mysql_result($req,0,case6);

$hypo = $_SERVER['QUERY_STRING'];
$l = 0;

for($i=1; $i != 7; $i++)
	{
	$l = $l + 1;
	if($_SESSION['case'.$i.'']=='Recueil'.$hypo.'')
		{
		$l = $l - 1;
		$sql = 'SELECT * FROM signatures_tbl WHERE numero= "'.$_SERVER['QUERY_STRING'].'"' ;
		$req = mysql_query($sql);
		$sign1 = mysql_result($req,0,sign1);
		$sign2 = mysql_result($req,0,sign2);
		$sign3 = mysql_result($req,0,sign3);
		$sign4 = mysql_result($req,0,sign4);
		$sign5 = mysql_result($req,0,sign5);
		print('<br /><strong>Voici le recueil :</strong>');
		if(($sign1!=$_SESSION['pseudo']) && ($sign2!=$_SESSION['pseudo']) && ($sign3!=$_SESSION['pseudo']) && ($sign4!=$_SESSION['pseudo']) && ($sign5==""))
			{
			print(' (<a href="engine=signerrecueil.php?'.$hypo.'">Signer</a>)');
			}
		print('<br /><br />');
		if($sign2=="")
			{
			print('<img src="im_objets/recueil.php?sign1='.$sign1.'" border="0" />');
			}
		elseif($sign3=="")
			{
			print('<img src="im_objets/recueil.php?sign1='.$sign1.'&sign2='.$sign2.'" border="0" />');
			}
		elseif($sign4=="")
			{
			print('<img src="im_objets/recueil.php?sign1='.$sign1.'&sign2='.$sign2.'&sign3='.$sign3.'" border="0" />');
			}
		elseif($sign5=="")
			{
			print('<img src="im_objets/recueil.php?sign1='.$sign1.'&sign2='.$sign2.'&sign3='.$sign3.'&sign4='.$sign4.'" border="0" />');
			}
		else
			{
			print('<img src="im_objets/recueil.php?sign1='.$sign1.'&sign2='.$sign2.'&sign3='.$sign3.'&sign4='.$sign4.'&sign5='.$sign5.'" border="0" />');
			}
		}
	}

if($l==6)
	{
	print('<meta http-equiv="refresh" content="0 ; url=http://v2.dreadcast.net/ingame/engine=inventaire.php"> ');
	}

mysql_close($db);

?>

</p>
</div>
<?php if($chat=="oui") 	{ if ((date("H")>=21) || (date("H")<=7)) { include("inc_bas_n_c.php"); } else { include("inc_bas_j_c.php"); } } else {	if ((date("H")>=21) || (date("H")<=7)) { include("inc_bas_n.php"); } else { include("inc_bas_j.php"); }	} ?>
<?php include("inc_bas_de_page.php"); ?>
