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
			Obtenir un permis
		</div>
		<b class="module4ie"><a href="engine.php" class="module4"><img src="im_objets/icon_retour.gif" alt="Retour" />Retour</a></b>
</p>
	</div>
</div>
<div id="centre_imperium">

<p id="location">Police de la cit&eacute;</p>

<p id="textelse">

<?php	

$db = mysql_connect('localhost', 'CENSURE', 'CENSURE') or die("Erreur de connexion au serveur.");
mysql_select_db('CENSURE',$db) or die("Erreur de connexion a la base de donnees.");

$sql = 'SELECT id,rue,num,action FROM principal_tbl WHERE pseudo= "'.$_SESSION['pseudo'].'"' ;
$req = mysql_query($sql);
$_SESSION['id'] = mysql_result($req,0,id);
$_SESSION['lieu'] = mysql_result($req,0,rue);
$_SESSION['num'] = mysql_result($req,0,num);
$_SESSION['action'] = mysql_result($req,0,action);
$_SESSION['lieu'] = strtolower($_SESSION['lieu']);
$_SESSION['lieu'] = ucwords($_SESSION['lieu']);

$sql = 'SELECT tir,combat FROM principal_tbl WHERE id= "'.$_SESSION['id'].'"' ;
$req = mysql_query($sql);
$_SESSION['tir'] = mysql_result($req,0,tir);
$_SESSION['combat'] = mysql_result($req,0,combat);

$sql = 'SELECT nom,type FROM entreprises_tbl WHERE rue= "'.$_SESSION['lieu'].'" AND num= "'.$_SESSION['num'].'"' ;
$req = mysql_query($sql);
$type = mysql_result($req,0,type);
$noment = mysql_result($req,0,nom);

if($type!="police")
	{
	print('<meta http-equiv="refresh" content="0 ; url=http://v2.dreadcast.net/ingame/engine.php"> ');
	exit();
	}

print('<strong>S&eacute;lectionner un permis pour l\'obtenir :</strong><br />
		Un permis de port d\'arme est gratuit mais n&eacute;c&eacute;ssite de savoir utiliser l\'arme en question.<br />
		<em>(Il vous faut un emplacement vide dans l\'inventaire personnel)</em><br /><br />
		<form id="maforme" name="achat" method="post" action="#">
			<select id="leselect4" name="achat" onChange="MM_jumpMenu(\'parent\',this,0)">
				<option value="#" selected>Choisissez ici</option>
				<option value="engine=get.php?carte">Carte d\'identit&eacute;</option>');

$sql = 'SELECT nom,puissance FROM objets_tbl WHERE type= "permisc"' ;
$req = mysql_query($sql);
$res = mysql_num_rows($req);

for($i=0; $i != $res; $i++)
	{
	$puissance = mysql_result($req,$i,puissance);
	$objet = mysql_result($req,$i,nom);
	print('<option value="engine=get.php?'.$objet.'">'.$objet.' ('.$puissance.' en Combat)</option>');
	}

$sql = 'SELECT nom,puissance FROM objets_tbl WHERE type= "permist"' ;
$req = mysql_query($sql);
$res = mysql_num_rows($req);

for($i=0; $i != $res; $i++)
	{
	$puissance = mysql_result($req,$i,puissance);
	$objet = mysql_result($req,$i,nom);
	print('<option value="engine=get.php?'.$objet.'">'.$objet.' ('.$puissance.' en Tir)</option>');
	}

mysql_close($db);

?>
              </select>
		    </form>
</p>

</div>
<?php if($chat=="oui") 	{ if ((date("H")>=21) || (date("H")<=7)) { include("inc_bas_n_c.php"); } else { include("inc_bas_j_c.php"); } } else {	if ((date("H")>=21) || (date("H")<=7)) { include("inc_bas_n.php"); } else { include("inc_bas_j.php"); }	} ?>
<?php include("inc_bas_de_page.php"); ?>
