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

$db = mysql_connect('localhost', 'CENSURE', 'CENSURE') or die("Erreur de connexion au serveur des messages.");
mysql_select_db('CENSURE',$db) or die("Erreur de connexion a la base de donnees des messages.");

$sql = 'SELECT planete FROM principal_tbl WHERE id="'.$_SESSION['id'].'"';
$req = mysql_query($sql);
$planete = mysql_result($req,0,planete);

$sql = 'SELECT id FROM principal_tbl WHERE planete="'.$planete.'"';
$req = mysql_query($sql);
$habitants = mysql_num_rows($req);

$sql = 'SELECT nom,x,y,cases,colonie,proprietaire,description_rp FROM colonies_planetes_tbl WHERE id="'.$planete.'"';
$req = mysql_query($sql);
$nom = mysql_result($req,0,nom);
$x = mysql_result($req,0,x);
$y = mysql_result($req,0,y);
$cases = mysql_result($req,0,cases);
$colonie = mysql_result($req,0,colonie);
$proprietaire = mysql_result($req,0,proprietaire);
$rp= mysql_result($req,0,description_rp);

mysql_close($db);

?>

<?php if ((date("H")>=21) || (date("H")<=7)) { include("inc_haut_n.php"); } else { include("inc_haut_j.php");} ?>

<div id="haut">
	<?php include("inc_entete.php"); ?>
	<div class="haut_aligngauche">
		<p>
		<div class="titrepage">
			Infos Planète
		</div>
		<b class="module4ie"><a href="engine.php" class="module4"><img src="im_objets/icon_retour.gif" alt="Retour" />Retour</a></b>
</p>
	</div>
</div>
<div id="centre">
<p>
	<div id="planete_bg<?php print($planete); ?>">
		<div id="planete_infos">
			<strong>Colonie :</strong> <?php print($colonie); ?><br />
			<strong>Habitants :</strong> <?php print($habitants); ?><br />
			<strong>Planète de rattachement :</strong> <?php print($nom); ?><br />
			<strong>Surface exploitable :</strong> <?php print($cases); ?> Hectares<br />
			<strong>Propriétaire :</strong> <?php print($proprietaire); ?><br />
			<strong>Coordonnées spatiales :</strong> <?php print("(".$x.";".$y.")"); ?><br />
		</div>
		<div id="planete_description">
			<?php print($rp); ?>
		</div>
<!--		<form target="_self" action="" method="get">
			<select name="destination">
				<option value="police" selected="selected">Poste de police local</option>
			</select>
			<input type="submit" value="S'y rendre !" />	
		</form>
-->
	</div>
	



</p>
</div>
<?php if($chat=="oui") 	{ if ((date("H")>=21) || (date("H")<=7)) { include("inc_bas_n_c.php"); } else { include("inc_bas_j_c.php"); } } else {	if ((date("H")>=21) || (date("H")<=7)) { include("inc_bas_n.php"); } else { include("inc_bas_j.php"); }	} ?>
<?php include("inc_bas_de_page.php"); ?>
