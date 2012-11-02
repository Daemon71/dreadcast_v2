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
			Super Slot !
		</div>
		<b class="module4ie"><a href="engine=machine.php" class="module4"><img src="im_objets/icon_retour.gif" alt="Retour" />Retour</a></b>
</p>
	</div>
</div>
<div id="centre">
<p><br />
<?php
echo'Vous avez récupéré '.$_POST['varia'].' crédits<br /><br />';

$_SESSION['randss']=ceil(sqrt($_SESSION['randss']));
$_SESSION['randss']=ceil(($_SESSION['randss']/3+2)/9);


if(($_POST['coucou']==$_SESSION['randss'])&&($_POST['varia']<10000)){


$db = mysql_connect('localhost', 'CENSURE', 'CENSURE') or die("Erreur de connexion au serveur des messages.");
	mysql_select_db('CENSURE',$db) or die("Erreur de connexion a la base de donnees des messages.");
	$sql = 'SELECT credits FROM principal_tbl WHERE id= "'.$_SESSION['id'].'"' ;
	$req = mysql_query($sql);
	$_SESSION['credits'] = mysql_result($req,0,credits);
	
	$_SESSION['credits'] = $_SESSION['credits'] + $_POST['varia'];
	
	$sql3 = 'SELECT valeur FROM donnees_tbl WHERE objet= "sortieSS"';
	$req3 = mysql_query($sql3);
	$sortie = mysql_result($req3,0,valeur);
	$sortie = $sortie + $_POST['varia'];
	$sql4 = 'UPDATE donnees_tbl SET valeur="'.$sortie.'" WHERE objet= "sortieSS"' ;
	$req4 = mysql_query($sql4);
	
	$sqls = 'SELECT fois FROM donneesidj_tbl WHERE pseudo= "'.$_SESSION['pseudo'].'" ORDER BY id DESC LIMIT 1';
	$reqs = mysql_query($sqls);
	$ress = mysql_num_rows($reqs);
	if($ress==0)
		{
		$fois = 1;
		}
	else
		{
		$fois = mysql_result($reqs,0,fois)+1;	
		}
		
	$sql5 = 'INSERT INTO donneesidj_tbl(id,pseudo,mise,gain,fois) VALUES("","'.$_SESSION[pseudo].'","'.$_SESSION['misess'].'","'.$_POST['varia'].'","'.$fois.'")' ;
	$req5 = mysql_query($sql5);
	
	$_SESSION['misess'] = '';
	
	
	
	
	$sql2 = 'UPDATE principal_tbl SET credits="'.$_SESSION['credits'].'" WHERE id= "'.$_SESSION['id'].'"' ;
	$req2 = mysql_query($sql2);
echo'Ce qui vous fait un total de '.$_SESSION['credits'].' crédits';
	mysql_close($db);


}
?>


</p>

</div>
<?php if($chat=="oui") 	{ if ((date("H")>=21) || (date("H")<=7)) { include("inc_bas_n_c.php"); } else { include("inc_bas_j_c.php"); } } else {	if ((date("H")>=21) || (date("H")<=7)) { include("inc_bas_n.php"); } else { include("inc_bas_j.php"); }	} ?>
<?php include("inc_bas_de_page.php"); ?>
