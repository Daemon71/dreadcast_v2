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
			Parrainer un joueur
		</div>
		<b class="module4ie"><a href="engine=infosperso.php" class="module4"><img src="im_objets/icon_retour.gif" alt="Retour" />Retour</a></b>
</p>
	</div>
</div>
<div id="centre">
<h3>Comment parrainer un futur joueur ?</h3>
<p style="text-align: justify;padding-top:20px;">

<?php

	print('Pour parrainer une connaissance, il y a 2 façons :<br /><br />
	• La personne se rend sur le site Dreadcast.net, et dans la rubrique <strong>Inscription</strong>, choisit l\'option <strong>Inscription parrainée</strong>. En précisant alors votre pseudo, elle sera alors reconnue comme votre filleule et pourra poursuivre l\'inscription.<br /><br />
	• Ou bien, encore plus simple, elle pourra se rendre à l\'adresse spéciale <strong>http://v2.dreadcast.net/?p='.$_SESSION['pseudo'].'</strong> et s\'inscrire normalement pour devenir votre filleule !');
	
?>

</p>
</div>
<?php if($chat=="oui") 	{ if ((date("H")>=21) || (date("H")<=7)) { include("inc_bas_n_c.php"); } else { include("inc_bas_j_c.php"); } } else {	if ((date("H")>=21) || (date("H")<=7)) { include("inc_bas_n.php"); } else { include("inc_bas_j.php"); }	} ?>
<?php include("inc_bas_de_page.php"); ?>
