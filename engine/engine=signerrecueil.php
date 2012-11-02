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

$sql1 = 'SELECT id FROM cercles_tbl WHERE pseudo= "'.$_SESSION['pseudo'].'"' ;
$req1 = mysql_query($sql1);
$res1 = mysql_num_rows($req1);

if($res1==0)
	{
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
			$sql2 = 'SELECT * FROM signatures_tbl WHERE numero= "'.$hypo.'"' ;
			$req2 = mysql_query($sql2);
			$sign2 = mysql_result($req2,0,sign2);
			$sign3 = mysql_result($req2,0,sign3);
			$sign4 = mysql_result($req2,0,sign4);
			$sign5 = mysql_result($req2,0,sign5);
			if(($sign1==$_SESSION['pseudo']) || ($sign2==$_SESSION['pseudo']) || ($sign3==$_SESSION['pseudo']) || ($sign4==$_SESSION['pseudo']) || ($sign5==$_SESSION['pseudo']))
				{
				print('<meta http-equiv="refresh" content="0 ; url=http://v2.dreadcast.net/ingame/engine=signde.php?'.$hypo.'"> ');
				exit();
				}
			if($sign2=="")
				{
				$sql = 'UPDATE signatures_tbl SET sign2= "'.$_SESSION['pseudo'].'" WHERE numero= "'.$hypo.'"';
				$req = mysql_query($sql);
				}
			elseif($sign3=="")
				{
				$sql = 'UPDATE signatures_tbl SET sign3= "'.$_SESSION['pseudo'].'" WHERE numero= "'.$hypo.'"';
				$req = mysql_query($sql);
				}
			elseif($sign4=="")
				{
				$sql = 'UPDATE signatures_tbl SET sign4= "'.$_SESSION['pseudo'].'" WHERE numero= "'.$hypo.'"';
				$req = mysql_query($sql);
				}
			elseif($sign5=="")
				{
				$sql = 'UPDATE signatures_tbl SET sign5= "'.$_SESSION['pseudo'].'" WHERE numero= "'.$hypo.'"';
				$req = mysql_query($sql);
				}
			print('<meta http-equiv="refresh" content="0 ; url=http://v2.dreadcast.net/ingame/engine=signde.php?'.$hypo.'"> ');
			exit();
			}
		}
		
	if($l==6)
		{
		print('<meta http-equiv="refresh" content="0 ; url=http://v2.dreadcast.net/ingame/engine=inventaire.php"> ');
		}
	}
else
	{
	print('Vous avez déjà un cercle.<br />Si vous désirez signer pour ce cercle il faut sortir de celui dans lequel vous êtes actuellement.');
	}
	
mysql_close($db);

?>

</p>
</div>
<?php if($chat=="oui") 	{ if ((date("H")>=21) || (date("H")<=7)) { include("inc_bas_n_c.php"); } else { include("inc_bas_j_c.php"); } } else {	if ((date("H")>=21) || (date("H")<=7)) { include("inc_bas_n.php"); } else { include("inc_bas_j.php"); }	} ?>
<?php include("inc_bas_de_page.php"); ?>
