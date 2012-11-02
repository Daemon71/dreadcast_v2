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

$sql = 'SELECT nom,type FROM entreprises_tbl WHERE rue= "'.$_SESSION['lieu'].'" AND num= "'.$_SESSION['num'].'"' ;
$req = mysql_query($sql);
$type = mysql_result($req,0,type);
$noment = mysql_result($req,0,nom);

if($type!="police")
	{
	print('<meta http-equiv="refresh" content="0 ; url=http://v2.dreadcast.net/ingame/engine.php"> ');
	exit();
	}

mysql_close($db);
?>
<?php if ((date("H")>=21) || (date("H")<=7)) { include("inc_haut_n.php"); } else { include("inc_haut_j.php");} ?>

<div id="haut">
	<?php include("inc_entete.php"); ?>
	<div class="haut_aligngauche">
		<p>
		<div class="titrepage">
			Porter plainte
		</div>
		<b class="module4ie"><a href="engine.php" class="module4"><img src="im_objets/icon_retour.gif" alt="Retour" />Retour</a></b>
</p>
	</div>
</div>
<div id="centre_imperium">

<p id="location">Police de la cit&eacute;</p>

<p id="textelse">

	<strong>Entrez votre message :</strong><br />
	<em>(Attention : si ce que vous avez &agrave; dire n'est pas pertinent, vous pouvez &ecirc;tre passible d'une amende ou d'un s&eacute;jour en prison.) </em><br />
	<form id="maforme" name="form1" method="post" action="engine=pleintef.php">
		<em><textarea name="pleinte" cols="40" rows="4" id="pleinte"></textarea></em><br />
		Joindre un message: <select name="msg" id="selectpleinte">
		<option value="0">Ne pas joindre de message</option>
		<?php 
		$db = mysql_connect('localhost', 'CENSURE', 'CENSURE') or die("Erreur de connexion au serveur des messages.");
		mysql_select_db('CENSURE',$db) or die("Erreur de connexion a la base de donnees des messages.");
		
		$sql = 'SELECT id,objet,auteur FROM messages_tbl WHERE cible= "'.$_SESSION['pseudo'].'" ORDER BY moment DESC ' ;
		$req = mysql_query($sql);
		$res = mysql_num_rows($req);

		for($i=0;$i!=$res;$i++)
			{
			$objet = mysql_result($req,$i,objet);
			$auteur = mysql_result($req,$i,auteur);
			if(ereg("[MASQUE]",mysql_result($req,$i,objet))) { $objet = trim(str_replace("[MASQUE]","",mysql_result($req,$i,objet))); $auteur = "-Anonyme-"; }
			if(mysql_result($req,$i,objet)=="") $objet = "Aucun objet";
			print('<option value="'.mysql_result($req,$i,id).'">'.$objet.' de '.$auteur.'</option>');
			}
		
		mysql_close($db);
		?>
		</select>
        <input id="validpleinte" type="submit" name="Submit" value="Envoyer" />
	</form>

</p>

</div>
<?php if($chat=="oui") 	{ if ((date("H")>=21) || (date("H")<=7)) { include("inc_bas_n_c.php"); } else { include("inc_bas_j_c.php"); } } else {	if ((date("H")>=21) || (date("H")<=7)) { include("inc_bas_n.php"); } else { include("inc_bas_j.php"); }	} ?>
<?php include("inc_bas_de_page.php"); ?>
