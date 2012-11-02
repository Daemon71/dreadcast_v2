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
			Contr&ocirc;le
		</div>
		<b class="module4ie"><a href="engine=panneau.php" class="module4"><img src="im_objets/icon_retour.gif" alt="Retour" />Retour</a></b>
</p>
	</div>
</div>
<?php include('inc_admin.php'); ?>
<div id="centre">
<p>

<?php 

if($_SESSION['statut']=="Administrateur")
	{
	print('<form method="post" action="engine=commandejoueur.php"><p align="center"><i>Joueur : </i><select name="type"><option value="pseudo" selected="selected">Pseudo</option><option value="adresse">E-Mail</option><option value="ip">IP (Création)</option><option value="ipdc">IP (Dernière co.)</option><option value="login">Login</option><option value="password">Mot de passe</option><option value="parrain">Parrain</option><option value="adresse">Adresse</option></select> <input type="text" name="pseudo" size="15" /> <input type="submit" name="Submit" value="Acceder au dossier"></p></form>');
	print('<form method="post" action="engine=commandeent.php"><p align="center"><i>Entreprise : </i><input type="text" name="entreprise" size="15" /> <input type="submit" name="Submit" value="Acceder au dossier"></p></form>');
	print('<form method="post" action="engine=commandecercle.php"><p align="center"><i>Cercle : </i><input type="text" name="cercle" size="15" /> <input type="submit" name="Submit" value="Acceder au dossier"></p></form>');
	print('<form method="post" action="engine=commandeobjet.php"><p align="center"><i>Objet : </i><input type="text" name="objet" size="15" /> <input type="submit" name="Submit" value="Acceder au dossier"></p></form>');
	print('<form method="post" action="engine=commandecompte.php"><p align="center"><i>Compte : </i><input type="text" name="compte" size="15" /> <input type="submit" name="Submit" value="Acceder au dossier"></p></form>');
	}
else
	{
	print('<meta http-equiv="refresh" content="0 ; url=http://v2.dreadcast.net/ingame/engine.php"> ');
	exit();
	}

?>

</p>
</div>
<?php if($chat=="oui") 	{ if ((date("H")>=21) || (date("H")<=7)) { include("inc_bas_n_c.php"); } else { include("inc_bas_j_c.php"); } } else {	if ((date("H")>=21) || (date("H")<=7)) { include("inc_bas_n.php"); } else { include("inc_bas_j.php"); }	} ?>
<?php include("inc_bas_de_page.php"); ?>
