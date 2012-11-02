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
			Ajout d'objet
		</div>
		<b class="module4ie"><a href="engine=panneau.php" class="module4"><img src="im_objets/icon_retour.gif" alt="Retour" />Retour</b></a>
		</p>
	</div>
</div>
<div id="centre">
<p>


<?php 
if($_SESSION['statut']=="Administrateur")
	{
	print('<form name="form1" id="form1" method="post" action="engine=ajoutof.php"><div align="center"><strong>Nom </strong>: <input name="nom" type="text" id="nom" size="20" /></div><div align="center"><strong>Puissance :</strong><input name="puissance" type="text" id="puissance" size="2" maxlength="2" /></div><div align="center"><strong>Image : </strong>http://v2.dreadcast.net/ingame/im_objets/');
	print('<input name="image" type="text" id="image" size="10" /></div><div align="center"><strong>Lien : </strong>http://v2.dreadcast.net/');
	print('<input name="url" type="text" id="url" size="10" /></div><div align="center"><strong>Infos :</strong></div><div align="center"><textarea name="infos" cols="45" rows="4"></textarea> </div><div align="center"><strong>Prix </strong>: <input name="prix" type="text" id="prix" size="5" /></p><p align="center"><strong>Type </strong>: <input name="type" type="text" id="type" size="10" /></p><p align="center"><input type="submit" name="Submit" value="Envoyer" /></div></form>');
	}
?>


</p>
</div>
<?php if($chat=="oui") 	{ if ((date("H")>=21) || (date("H")<=7)) { include("inc_bas_n_c.php"); } else { include("inc_bas_j_c.php"); } } else {	if ((date("H")>=21) || (date("H")<=7)) { include("inc_bas_n.php"); } else { include("inc_bas_j.php"); }	} ?>
<?php include("inc_bas_de_page.php"); ?>
