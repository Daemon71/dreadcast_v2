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
if($_SESSION['statut']!="Administrateur")
	{
	print('<meta http-equiv="refresh" content="0 ; url=http://v2.dreadcast.net/ingame/engine.php"> ');
	exit();
	}
?>
<?php if ((date("H")>=21) || (date("H")<=7)) { include("inc_haut_n.php"); } else { include("inc_haut_j.php");} ?>

<div id="haut">
	<?php include("inc_entete.php"); ?>
	<div class="haut_aligngauche">
		<p>
		<div class="titrepage">
			Modifier la carte
		</div>
		<b class="module4ie"><a href="engine=carte.php" class="module4"><img src="im_objets/icon_retour.gif" alt="Retour" />Retour</a></b>
</p>
	</div>
</div>
<div id="centre">
<p>

<form name="form1" method="post" action="engine=modifcartef.php?x=<?php print(''.$_GET['x'].''); ?>&y=<?php print(''.$_GET['y'].''); ?>">
		  <div align="center">
		    <p>En x=<?php print(''.$_GET['x'].''); ?> et y=<?php print(''.$_GET['y'].''); ?> </p>
		    <p>Num: 
		          <input name="num" type="text" id="num" size="4">
                  <br>
                  Rue: 
                  <input name="rue" type="text" id="rue" size="15">
</p>
		    <p>Image: 
		      <input name="image" type="text" id="image" size="2">
</p>
		    <p>
		      <input type="submit" name="Submit" value="Envoyer">
</p>
		  </div>
		</form>

</p>
</div>
<?php if($chat=="oui") 	{ if ((date("H")>=21) || (date("H")<=7)) { include("inc_bas_n_c.php"); } else { include("inc_bas_j_c.php"); } } else {	if ((date("H")>=21) || (date("H")<=7)) { include("inc_bas_n.php"); } else { include("inc_bas_j.php"); }	} ?>
<?php include("inc_bas_de_page.php"); ?>
