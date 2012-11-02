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

if($_SESSION['entreprise']!="Conseil Imperial" && $_SESSION['statut'] != "Administrateur") { print('<meta http-equiv="refresh" content="0 ; url=http://v2.dreadcast.net/ingame/engine.php"> '); exit(); } 
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
		$sqla = 'INSERT INTO votesDI_tbl(id,voteur,poste,candidat,vote) VALUES("","'.$_SESSION['pseudo'].'","'.$poste.'","'.$auteur.'","oui")' ;
		$reqa = mysql_query($sqla);
		$sqla = 'INSERT INTO messages_tbl(id,auteur,cible,message,objet,moment) VALUES("","Conseil Impérial","'.$auteur.'","Vous obtenez un vote favorable pour votre candidature de '.$poste.'.<br />Il vous faut 5 votes favorables pour être élu.","Vote favorable","'.time().'")' ;
		$reqa = mysql_query($sqla);
		print('Vote pris correctement en compte.<br />');
		$sqla = 'SELECT * FROM votesDI_tbl WHERE candidat= "'.$auteur.'" AND poste= "'.$poste.'" AND vote= "oui"' ;
		$reqa = mysql_query($sqla);
		$resa = mysql_num_rows($reqa);
		if($resa==5)
			{
			$sql = 'INSERT INTO messages_tbl(id,auteur,cible,message,objet,moment) VALUES("","Dreadcast","'.$auteur.'","Félicitations, vous venez d\'être nommé <strong>'.$poste.'</strong> par le Conseil.<br />Vous avez désormais accès au panneau de contrôle de l\'Organisation.<br /><br />Le fonctionnement est simple : chaque semaine, la Direction des Organisations Impériales attribue un budget à chaque Organisation (à la votre, donc). Vous pouvez consulter l\'état des votes à n\'importe quel moment depuis votre interface.<br /><br />De plus, vous êtes désormais dans la haute administration. De ce fait un Laissez-passer Impérial vous à été remis. Ce dernier vous place hors d\'atteinte des services de Police traditionnels. Si vous avez un quelconque problème, il est maintenant du ressort de la DI2RCO de s\'occuper de vous. Le Directeur de ce département est en permanence à l\'écoute des Directeurs Impériaux et de leurs éventuels problèmes.<br /><br />Pour faciliter votre intégration parmis les DI (Directeurs Impériaux), il est conseillé de prendre contact avec vos collègues avant d\'entammer votre travail. L\'administration doit rester solidaire en toutes circonstances.<br /><br />En espérant constater le bon fonctionnement de votre Organisation, nous vous souhaitons, monsieur, madame, une excellente journée.","Vous êtes élu !","'.time().'")' ;
			$req = mysql_query($sql);
			$sql = 'UPDATE principal_tbl SET type= "Aucun" , entreprise= "Aucune" , points= "0" WHERE type= "'.$poste.'"';
			$res = mysql_query($sql);
			if($poste=="Directeur des Organisations") $orga = "DOI";
			elseif($poste=="Directeur du CIPE") $orga = "CIPE";
			elseif($poste=="President") $orga = "Conseil Imperial";
			elseif($poste=="Premier consul") $orga = "Chambre des lois";
			elseif($poste=="Directeur du CIE") $orga = "CIE";
			elseif($poste=="Directeur des services") $orga = "Services techniques de la ville";
			elseif($poste=="Directeur de la Prison") $orga = "Prison";
			elseif($poste=="Chef de la Police") $orga = "Police";
			elseif($poste=="Directeur du DI2RCO") $orga = "DI2RCO";
			elseif($poste=="Directeur du DC Network") $orga = "DC Network";
			$sql = 'UPDATE principal_tbl SET type= "'.$poste.'" , entreprise= "'.$orga.'" , points= "999" , case6= "Laissez-passer" WHERE pseudo= "'.$auteur.'"';
			$res = mysql_query($sql);
			$sqla = 'SELECT nom FROM candidatures_tbl WHERE poste= "'.$poste.'" AND nom!="'.$auteur.'"' ;
			$reqa = mysql_query($sqla);
			$resa = mysql_num_rows($reqa);
			for($i=0;$i!=$resa;$i++)
				{
				$sql = 'INSERT INTO messages_tbl(id,auteur,cible,message,objet,moment) VALUES("","Dreadcast","'.mysql_result($reqa,$i,nom).'","<br />Nous sommes désolés mais '.$auteur.' à été élu au poste de '.$poste.'.<br />De ce fait, votre candidature n\'est pas retenue.<br /><br />Ne vous découragez pas ! La prochaine sera peut-être la bonne !","Mauvaise nouvelle","'.time().'")' ;
				$req = mysql_query($sql);
				}
			$sqla = 'DELETE FROM votesDI_tbl WHERE poste= "'.$poste.'"' ;
			$reqa = mysql_query($sqla);
			$sqla = 'DELETE FROM candidatures_tbl WHERE poste= "'.$poste.'"' ;
			$reqa = mysql_query($sqla);
			print('<br /><strong>Le citoyen '.$auteur.' vient d\'être nommé au poste de '.$poste.'.</strong>');
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
