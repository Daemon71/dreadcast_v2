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

$sql = 'SELECT credits FROM principal_tbl WHERE id= "'.$_SESSION['id'].'"' ;
$req = mysql_query($sql);
$_SESSION['credits'] = mysql_result($req,0,credits);

$sql = 'SELECT nom,type FROM entreprises_tbl WHERE rue= "'.$_SESSION['lieu'].'" AND num= "'.$_SESSION['num'].'"' ;
$req = mysql_query($sql);
$type = mysql_result($req,0,type);
$noment = mysql_result($req,0,nom);


$_SESSION['page1']="";

if($type!="jeux")
	{
	mysql_close($db);
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
			Imp�riale des jeux
		</div>
		<b class="module4ie"><a href="engine=idj.php" class="module4"><img src="im_objets/icon_retour.gif" alt="Retour" />Retour</a></b>
		</p>
	</div>
</div>
<div id="centre_imperium">

<p id="location">Bienvenue au <span>Casino de la ville</span></p>


<p id="textelse">
Bienvenue au stand !<br />

Nous organisons une animation sp�ciale qui vous permettra de vous d�marquer dans le <a href="http://v2.dreadcast.net/classements.php"  onclick="window.open(this.href); return false;">classement</a> des meilleurs joueurs.
Nous �lisons chaque mois les 10 joueurs les plus fid�les au jeu.
Pour cela rien de plus simple, votez pour notre belle cit� toutes les 2 heures sur le bouton suivant:<br />
<!-- Debut bouton de vote - Root-top.com -->
<a href="http://www.root-top.com/topsite/mmorpg/in.php?IDmark=928"  onclick="window.open(this.href); return false;" >
<img src="http://img.root-top.com/topsite/mmorpg/banner.gif" border="0">
<br />Votez en renseignant votre pseudo !</a>
<!-- Fin bouton de vote - Root-top.com --><br /><br />

Le classement des 10 joueurs les plus fid�les est mis � jours tous les matins � 9 heures !
� la fin de chaque mois le premier du classement re�oit 30 points dans la comp�tence "Fid�lit�", le deuxi�me 20 points et le troisi�me 10 points.
Ce mois-ci 5 000 cr�dits seront mis un jeu, un tirage au sort sera effectu� pour d�terminer le gagnant.
N'h�sitez plus Votez !

</p>
</div>
<?php if($chat=="oui") 	{ if ((date("H")>=21) || (date("H")<=7)) { include("inc_bas_n_c.php"); } else { include("inc_bas_j_c.php"); } } else {	if ((date("H")>=21) || (date("H")<=7)) { include("inc_bas_n.php"); } else { include("inc_bas_j.php"); }	} ?>
<?php include("inc_bas_de_page.php"); ?>
