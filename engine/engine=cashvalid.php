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
			Cash !
		</div>
		<b class="module4ie"><a href="engine=inventaire.php" class="module4"><img src="im_objets/icon_retour.gif" alt="Retour" />Retour</a></b>
</p>
	</div>
</div>
<div id="centre">
<p><br />

<?php 

if($_SESSION['cashvalue']==25)
	{
	print('<strong>Bravo !</strong><br /><br />Vous avez gagné <strong>25 Crédits</strong> !<br />Cliquez sur le lien suivant pour encaisser les gains cash !');
	}
elseif($_SESSION['cashvalue']==50)
	{
	print('<strong>Bravo !</strong><br /><br />Vous avez gagné <strong>50 Crédits</strong> !<br />Cliquez sur le lien suivant pour encaisser les gains cash !');
	}
elseif($_SESSION['cashvalue']==150)
	{
	print('<strong>Bravo !</strong><br /><br />Vous avez gagné <strong>150 Crédits</strong> !<br />Cliquez sur le lien suivant pour encaisser les gains cash !');
	}
elseif($_SESSION['cashvalue']==300)
	{
	print('<strong>Bravo !</strong><br /><br />Vous avez gagné <strong>300 Crédits</strong> !<br />Cliquez sur le lien suivant pour encaisser les gains cash !');
	}
elseif($_SESSION['cashvalue']==500)
	{
	print('<strong>Bravo !</strong><br /><br />Vous avez gagné <strong>500 Crédits</strong> !<br />Cliquez sur le lien suivant pour encaisser les gains cash !');
	}
else
	{
	print('<strong>Quel domage !</strong><br /><br />Vous avez perdu, mais n\'hésitez pas à retenter votre chance avec un autre ticket Cash!');
	}

?>

</p><br />
<a href="engine=inventaire.php">Retour à l'inventaire</a>
</div>
<?php if($chat=="oui") 	{ if ((date("H")>=21) || (date("H")<=7)) { include("inc_bas_n_c.php"); } else { include("inc_bas_j_c.php"); } } else {	if ((date("H")>=21) || (date("H")<=7)) { include("inc_bas_n.php"); } else { include("inc_bas_j.php"); }	} ?>
<?php include("inc_bas_de_page.php"); ?>
