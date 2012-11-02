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
			Carnet d'adresses
		</div>
		<div align="center">(<a href="engine.php">Retour &agrave; la Situation Actuelle</a>)<br />
			(<a href="engine=carnet.php">Retour au Carnet d'adresses</a>)
		</div>
		</p>
	</div>
</div>
<div id="centre">
<p>


<strong>Ajouter une adresse : </strong>
<form name="allera" id="allera" method="post" action="engine=ajoutadressefinished.php">
	<br />
	Nom : <input name="nom" type="text" id="nom" <? $cible = str_replace("%20"," ",''.$_GET['nom'].''); if($cible!="") { print('value="'.$cible.'"'); }?> size="10" />
	<br /><br />
	n&deg; <input name="num" type="text" id="num" size="3" maxlength="3" <? $cible = str_replace("%20"," ",''.$_GET['num'].''); if($cible!="") { print('value="'.$cible.'"'); } ?> />
	Rue : <input name="rue" type="text" id="rue" <? $cible = str_replace("%20"," ",''.$_GET['rue'].''); if($cible!="") { print('value="'.$cible.'"'); } ?> size="20" />
	<br /><br />
	<input type="submit" name="Submit" value="Ajouter" />
</form>


</p>
</div>
<?php if($chat=="oui") 	{ if ((date("H")>=21) || (date("H")<=7)) { include("inc_bas_n_c.php"); } else { include("inc_bas_j_c.php"); } } else {	if ((date("H")>=21) || (date("H")<=7)) { include("inc_bas_n.php"); } else { include("inc_bas_j.php"); }	} ?>
<?php include("inc_bas_de_page.php"); ?>
