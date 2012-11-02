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
			Video Poker
		</div>
		<b class="module4ie"><a href="engine=idj.php" class="module4"><img src="im_objets/icon_retour.gif" alt="Retour" />Retour</a></b>
</p>
	</div>
</div>
<div id="centre_imperium">

<p id="location"><span>Video Poker</span></p>

<p id="textelse">
<?php


//sŽcuritŽ du poker
$_SESSION['page']=0;
if($_SESSION['wipoker']>0){
$db = mysql_connect('localhost', 'CENSURE', 'CENSURE') or die("Erreur de connexion au serveur des messages.");
mysql_select_db('CENSURE',$db) or die("Erreur de connexion a la base de donnees des messages.");

		
//Mettre ˆ jours la sortie de la table donnŽe
$sql3 = 'SELECT valeur FROM donnees_tbl WHERE objet= "sortiePoker"';
	$req3 = mysql_query($sql3);
	$sortie = mysql_result($req3,0,valeur);
	$sortie = $sortie + $_SESSION['wipoker'];
	$sql4 = 'UPDATE donnees_tbl SET valeur="'.$sortie.'" WHERE objet= "sortiePoker"' ;
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

//Mettre ˆ jours donneesidj par joueurs
	$sql5 = 'INSERT INTO donneesidj_tbl(id,pseudo,mise,gain,fois) VALUES("","'.$_SESSION[pseudo].'","20","'.$_SESSION['wipoker'].'","'.$fois.'")' ;
	$req5 = mysql_query($sql5);

//Mettre ˆ jours les sous chez le joueur		
	$_SESSION['credits'] = $_SESSION['credits'] + $_SESSION['wipoker'];
	$sql2 = 'UPDATE principal_tbl SET credits="'.$_SESSION['credits'].'" WHERE id= "'.$_SESSION['id'].'"' ;
	$req2 = mysql_query($sql2);

mysql_close($db);
}
	
	
	
	
	$_SESSION['wipoker']=0;	


$db = mysql_connect('localhost', 'CENSURE', 'CENSURE') or die("Erreur de connexion au serveur des messages.");
mysql_select_db('CENSURE',$db) or die("Erreur de connexion a la base de donnees des messages.");

$sql = 'SELECT credits FROM principal_tbl WHERE id= "'.$_SESSION['id'].'"' ;
$req = mysql_query($sql);
$_SESSION['credits'] = mysql_result($req,0,credits);
echo'<table width="400" height="206" border="1">
  <tr>
    <td rowspan="2"><img src="im/poker.png"/></td>
    <td valign="top"><br /><div align="left"><form method="POST" action="engine=videopoker1.php">
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input style="width:50px;color:#EFE1FF;background:#2B2929;border:1px solid #4A4A4A;font-size:1.1em;" type="submit" value="Jouer" name="Jouer">
</form></div></td>
  </tr>
  <tr>
    <td valign="top" style="padding:5px;"><br />&nbsp;&nbsp;Chaque partie coute 20 crédits, vous avez 2 tours pour constituer une combinaison gagnante de 5 cartes.<br />Vous pouvez doubler votre gain. Une carte s\'affiche et vous devez en s&eacute;lectionner une parmi 4 cartes retourn&eacute;es. Si celle-ci est sup&eacute;rieure votre gain est doubl&eacute;, sinon vous perdez tout... <br />Il est possible de gagner jusqu\'à 5 000 crédits en un seul coup!<br />Bonne Chance!</td>
  </tr>
</table>';

?>

</p>

</div>
<?php if($chat=="oui") 	{ if ((date("H")>=21) || (date("H")<=7)) { include("inc_bas_n_c.php"); } else { include("inc_bas_j_c.php"); } } else {	if ((date("H")>=21) || (date("H")<=7)) { include("inc_bas_n.php"); } else { include("inc_bas_j.php"); }	} ?>
<?php include("inc_bas_de_page.php"); ?>
