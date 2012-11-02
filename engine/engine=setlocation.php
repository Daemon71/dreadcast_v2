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

$sql = 'SELECT * FROM proprietaire_tbl WHERE pseudo= "'.$_SESSION['pseudo'].'" AND rue= "'.$_GET['rue'].'" AND num= "'.$_GET['num'].'"' ;
$req = mysql_query($sql);
$res = mysql_num_rows($req);

if($res!=0)
	{
	$_SESSION['autrelor'] = $_GET['rue'];
	$_SESSION['autrelon'] = $_GET['num'];
	}
else
	{
	print('<meta http-equiv="refresh" content="0 ; url=http://v2.dreadcast.net/ingame/engine=logement.php"> ');
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
			Mettre en location
		</div>
		<b class="module4ie"><a href="engine=logement.php?rue=<?php print(''.$_SESSION['autrelor'].''); ?>&num=<?php print(''.$_SESSION['autrelon'].''); ?>" class="module4"><img src="im_objets/icon_retour.gif" alt="Retour" />Retour</a></b>
</p>
	</div>
</div>
<div id="centre">
<p>

<form name="allera" id="allera" method="post" action="engine=setlocationfi.php?<?php print('num='.$_SESSION['autrelon'].'&rue='.$_SESSION['autrelor'].''); ?>">
          <p align="center"><strong>Vous désirez placer le logement du <i><?php print(''.$_SESSION['autrelon'].' '.$_SESSION['autrelor'].''); ?></i> en location.</strong><br /><br />
          <i>Notez que si un client ne paye pas son loyer à minuit, un mandat d'arret sera automatiquement emis contre lui et il sera expulsé.</i><br />
          </p><hr><p align="center">Veuillez entrer le prix journalier de cette habitation :<br />
              <br /><br /><strong>Prix :</strong> <input name="nouveau" type="text" id="nouveau" size="3" maxlength="3" /> Crédits / Jour<br>
              <input name="cand" type="checkbox" id="cand" value="cand">
              Il faut une d&eacute;poser une candidature pour pouvoir louer ce logement <br /><br />
            <input type="submit" name="Submit" value="Mettre en location" />
          </p>
		  </form>

</p>
</div>
<?php if($chat=="oui") 	{ if ((date("H")>=21) || (date("H")<=7)) { include("inc_bas_n_c.php"); } else { include("inc_bas_j_c.php"); } } else {	if ((date("H")>=21) || (date("H")<=7)) { include("inc_bas_n.php"); } else { include("inc_bas_j.php"); }	} ?>
<?php include("inc_bas_de_page.php"); ?>
