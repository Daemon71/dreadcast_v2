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

$sql = 'SELECT type,entreprise,points FROM principal_tbl WHERE id= "'.$_SESSION['id'].'"' ;
$req = mysql_query($sql);
$_SESSION['poste'] = mysql_result($req,0,type); 
$_SESSION['entreprise'] = mysql_result($req,0,entreprise); 
$_SESSION['points'] = mysql_result($req,0,points); 

mysql_close($db);

if($_SESSION['entreprise']!="Conseil Imperial") { print('<meta http-equiv="refresh" content="0 ; url=http://v2.dreadcast.net/ingame/engine.php"> '); exit(); } 
?>
<?php if ((date("H")>=21) || (date("H")<=7)) { include("inc_haut_n.php"); } else { include("inc_haut_j.php");} ?>

<div id="haut">
	<?php include("inc_entete.php"); ?>
	<div class="haut_aligngauche">
		<p>
		<div class="titrepage">
			Services
		</div>
		<b class="module4ie"><a href="engine=services.php" class="module4"><img src="im_objets/icon_retour.gif" alt="Retour" />Retour</a></b>
</p>
	</div>
</div>
<div id="centre">
<p>

<?php 

$db = mysql_connect('localhost', 'CENSURE', 'CENSURE') or die("Erreur de connexion au serveur des messages.");
mysql_select_db('CENSURE',$db) or die("Erreur de connexion a la base de donnees des messages.");

$sql = 'SELECT * FROM candidatures_tbl WHERE id= "'.$_GET['id'].'"' ;
$req = mysql_query($sql);
$res = mysql_num_rows($req);

if($res!=0)
	{
	$ido = mysql_result($req,0,id);
	$auteur = mysql_result($req,0,nom);
	$poste = mysql_result($req,0,poste);
	$sqla = 'SELECT * FROM votesDI_tbl WHERE voteur= "'.$_SESSION['pseudo'].'" AND poste= "'.$poste.'" AND candidat= "'.$auteur.'"' ;
	$reqa = mysql_query($sqla);
	$resa = mysql_num_rows($reqa);
	if($resa==0)
		{
		$sqla = 'INSERT INTO votesDI_tbl(id,voteur,poste,candidat,vote) VALUES("","'.$_SESSION['pseudo'].'","'.$poste.'","'.$auteur.'","non")' ;
		$reqa = mysql_query($sqla);
		$sqla = 'INSERT INTO messages_tbl(id,auteur,cible,message,objet,moment) VALUES("","Conseil Impérial","'.$auteur.'","Vous obtenez un vote défavorable pour votre candidature de '.$poste.'.<br />Il suffit de 2 votes défavorables pour voir votre candidature refusée.","Vote défavorable","'.time().'")' ;
		$reqa = mysql_query($sqla);
		print('Vote pris correctement en compte.<br />');
		$sqla = 'SELECT * FROM votesDI_tbl WHERE candidat= "'.$auteur.'" AND poste= "'.$poste.'" AND vote= "non"' ;
		$reqa = mysql_query($sqla);
		$resa = mysql_num_rows($reqa);
		if($resa==2)
			{
			$sqla = 'DELETE FROM votesDI_tbl WHERE candidat= "'.$auteur.'" AND poste= "'.$poste.'"' ;
			$reqa = mysql_query($sqla);
			$sqla = 'DELETE FROM candidatures_tbl WHERE nom= "'.$auteur.'" AND poste= "'.$poste.'"' ;
			$reqa = mysql_query($sqla);
			print('<br /><strong>La candidature du citoyen '.$auteur.' vient d\'être refusée.</strong>');
			}
		}
	}
else
	{
	print("<strong>Cette candidature ne peut pas s'afficher.</strong><br>");
	}
	
mysql_close($db);

?>

</p>
</div>
<?php if($chat=="oui") 	{ if ((date("H")>=21) || (date("H")<=7)) { include("inc_bas_n_c.php"); } else { include("inc_bas_j_c.php"); } } else {	if ((date("H")>=21) || (date("H")<=7)) { include("inc_bas_n.php"); } else { include("inc_bas_j.php"); }	} ?>
<?php include("inc_bas_de_page.php"); ?>
