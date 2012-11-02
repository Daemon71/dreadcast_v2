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
			Machines à Sous 
		</div>
		<b class="module4ie"><a href="engine=inventaire.php" class="module4"><img src="im_objets/icon_retour.gif" alt="Retour" />Retour</a></b>
</p>
	</div>
</div>
<div id="centre_imperium">

<p id="location"><span>Super Slot !</span></p>

<p id="textelse">
<?php
$db = mysql_connect('localhost', 'CENSURE', 'CENSURE') or die("Erreur de connexion au serveur des messages.");
mysql_select_db('CENSURE',$db) or die("Erreur de connexion a la base de donnees des messages.");

$sql = 'SELECT credits FROM principal_tbl WHERE id= "'.$_SESSION['id'].'"' ;
$req = mysql_query($sql);
$_SESSION['credits'] = mysql_result($req,0,credits);
echo'<table width="400" height="206" border="1">
  <tr>
    <td rowspan="2"><img src="im/machine.jpg"/></td>
    <td valign="top">Choisissez votre mise : <br />de 20  &agrave; 10000 cr&eacute;dits.<br /><br /><div align="left"><form method="POST" action="engine=superslot.php">
&nbsp;&nbsp;Mise :&nbsp;<input style="color:#EFE1FF;background:#2B2929;border:1px solid #4A4A4A;" type="text" name="be" size="6" maxlength="10">
<input style="width:50px;color:#EFE1FF;background:#2B2929;border:1px solid #4A4A4A;font-size:1.1em;" type="submit" value="Bet" name="Bet">
</form></div></td>
  </tr>
  <tr>
    <td valign="top" style="padding:5px;"><br />&nbsp;&nbsp;Chaque partie coute 20 crédits, pour jouer il suffit d\'appuyer sur le bouton "<strong>Spin</strong>". <br />Vous pouvez à tout moment du jeu sortir avec la somme que vous souhaitez en appuyant sur le bouton "<strong>Cash out</strong>". <br />Il est possible de gagner jusqu\'à 1000 crédits en un seul coup!<br />Bonne Chance!</td>
  </tr>
</table>';

?>

</p>

</div>
<?php if($chat=="oui") 	{ if ((date("H")>=21) || (date("H")<=7)) { include("inc_bas_n_c.php"); } else { include("inc_bas_j_c.php"); } } else {	if ((date("H")>=21) || (date("H")<=7)) { include("inc_bas_n.php"); } else { include("inc_bas_j.php"); }	} ?>
<?php include("inc_bas_de_page.php"); ?>
