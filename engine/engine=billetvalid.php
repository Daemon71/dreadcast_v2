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
			Billet Shanghai !
		</div>
		<b class="module4ie"><a href="engine=inventaire.php" class="module4"><img src="im_objets/icon_retour.gif" alt="Retour" />Retour</a></b>
</p>
	</div>
</div>
<div id="centre">
<p><br />

<?php 

if($_SESSION['cashvalue']==50)
	{
	print('<strong>Bravo !</strong><br /><br />Vous avez gagné <strong>50 Crédits</strong> !<br />Cliquez sur le lien suivant pour encaisser les gains!');
	}
elseif($_SESSION['cashvalue']==60)
	{
	print('<strong>Bravo !</strong><br /><br />Vous avez gagné <strong>60 Crédits</strong> !<br />Cliquez sur le lien suivant pour encaisser les gains!');
	}
elseif($_SESSION['cashvalue']==70)
	{
	print('<strong>Bravo !</strong><br /><br />Vous avez gagné <strong>70 Crédits</strong> !<br />Cliquez sur le lien suivant pour encaisser les gains!');
	}
elseif($_SESSION['cashvalue']==80)
	{
	print('<strong>Bravo !</strong><br /><br />Vous avez gagné <strong>80 Crédits</strong> !<br />Cliquez sur le lien suivant pour encaisser les gains!');
	}
elseif($_SESSION['cashvalue']==90)
	{
	print('<strong>Bravo !</strong><br /><br />Vous avez gagné <strong>90 Crédits</strong> !<br />Cliquez sur le lien suivant pour encaisser les gains!');
	}
elseif($_SESSION['cashvalue']==100)
	{
	print('<strong>Bravo !</strong><br /><br />Vous avez gagné <strong>100 Crédits</strong> !<br />Cliquez sur le lien suivant pour encaisser les gains!');
	}
elseif($_SESSION['cashvalue']==150)
	{
	print('<strong>Bravo !</strong><br /><br />Vous avez gagné <strong>150 Crédits</strong> !<br />Cliquez sur le lien suivant pour encaisser les gains!');
	}
elseif($_SESSION['cashvalue']==300)
	{
	print('<strong>Bravo !</strong><br /><br />Vous avez gagné <strong>300 Crédits</strong> !<br />Cliquez sur le lien suivant pour encaisser les gains!');
	}
elseif($_SESSION['cashvalue']==600)
	{
	print('<strong>Bravo !</strong><br /><br />Vous avez gagné <strong>600 Crédits</strong> !<br />Cliquez sur le lien suivant pour encaisser les gains!');
	}
elseif($_SESSION['cashvalue']==1000)
	{
	print('<strong>Bravo !</strong><br /><br />Vous avez gagné <strong>1000 Crédits</strong> !<br />Cliquez sur le lien suivant pour encaisser les gains!');
	}
else
	{
	print('<strong>Quel dommage !</strong><br /><br />Vous avez perdu, mais n\'hésitez pas à retenter votre chance avec un autre billet!');
	}

?>

</p><br />
<a href="engine=inventaire.php">Retour à l'inventaire</a>
</div>
<?php if($chat=="oui") 	{ if ((date("H")>=21) || (date("H")<=7)) { include("inc_bas_n_c.php"); } else { include("inc_bas_j_c.php"); } } else {	if ((date("H")>=21) || (date("H")<=7)) { include("inc_bas_n.php"); } else { include("inc_bas_j.php"); }	} ?>
<?php include("inc_bas_de_page.php"); ?>
