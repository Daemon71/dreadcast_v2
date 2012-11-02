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


<strong>Ajouter un contact : </strong>
<form name="allera" id="allera" method="post" action="engine=ajoutcontactfinished.php">
	<p>Nom : <input name="contact" type="text" id="contact" <? $cible = str_replace("%20"," ",''.$_SERVER['QUERY_STRING'].''); if($cible!="") { print('value="'.$cible.'"'); } ?> size="10" />
	</p>
	<p>Statut : <select name="statutc" id="statutc">
		<option value="Ami">Ami</option>
		<option value="Collegue">Coll&egrave;gue</option>
		<option value="Contact">Contact</option>
		<option value="Ennemi">Ennemi</option>
		<option value="Autre">Autre..</option>
	</select>
	<br />
	<br />
	<input type="submit" name="Submit" value="Ajouter" />
	</p>
</form>


</p>
</div>
<?php if($chat=="oui") 	{ if ((date("H")>=21) || (date("H")<=7)) { include("inc_bas_n_c.php"); } else { include("inc_bas_j_c.php"); } } else {	if ((date("H")>=21) || (date("H")<=7)) { include("inc_bas_n.php"); } else { include("inc_bas_j.php"); }	} ?>
<?php include("inc_bas_de_page.php"); ?>
