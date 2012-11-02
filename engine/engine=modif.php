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
			Modification du personnel
		</div>
		<b class="module4ie"><a href="engine=personnel.php" class="module4"><img src="im_objets/icon_retour.gif" alt="Retour" />Retour</a></b>
</p>
	</div>
</div>
<div id="centre">
<p>

<?php 

$db = mysql_connect('localhost', 'CENSURE', 'CENSURE') or die("Erreur de connexion au serveur des messages.");
mysql_select_db('CENSURE',$db) or die("Erreur de connexion a la base de donnees des messages.");

$modif = $_GET['modif'];
$idi = $_GET['id'];

$sql = 'SELECT type,entreprise,points FROM principal_tbl WHERE id= "'.$_SESSION['id'].'"' ;
$req = mysql_query($sql);
$_SESSION['poste'] = mysql_result($req,0,type); 
$_SESSION['entreprise'] = mysql_result($req,0,entreprise); 
$_SESSION['points'] = mysql_result($req,0,points); 

if($_SESSION['points']!=999) { print('<meta http-equiv="refresh" content="0 ; url=http://v2.dreadcast.net/ingame/engine.php"> '); exit(); }

if(($modif=="salaire") && ($_POST['salaire'.$idi.'']>0))
	{
	$sql = 'SELECT poste FROM `e_'.str_replace(" ","_",''.$_SESSION['entreprise'].'').'_tbl` WHERE id= "'.$idi.'"' ;
	$req = mysql_query($sql);
	$pos = mysql_result($req,0,poste); 
	$sql = 'UPDATE `e_'.str_replace(" ","_",''.$_SESSION['entreprise'].'').'_tbl` SET salaire="'.$_POST['salaire'.$idi.''].'" WHERE id= "'.$idi.'"' ;
	$req = mysql_query($sql);
	}
elseif(($modif=="temps") && ($_POST['tps'.$idi.'']>0))
	{
	$sql = 'UPDATE `e_'.str_replace(" ","_",''.$_SESSION['entreprise'].'').'_tbl` SET mintrav="'.$_POST['tps'.$idi.''].'" WHERE id= "'.$idi.'"' ;
	$req = mysql_query($sql);
	}
elseif($modif=="sinon")
	{
	$sql = 'UPDATE `e_'.str_replace(" ","_",''.$_SESSION['entreprise'].'').'_tbl` SET sinon="'.$_POST['select'.$idi.''].'" WHERE id= "'.$idi.'"' ;
	$req = mysql_query($sql);
	}
elseif($modif=="hs")
	{
	$sql = 'UPDATE `e_'.str_replace(" ","_",''.$_SESSION['entreprise'].'').'_tbl` SET bonus="'.$_POST['hs'.$idi.''].'" WHERE id= "'.$idi.'"' ;
	$req = mysql_query($sql);
	}
elseif($modif=="np")
	{
	$sql = 'UPDATE `e_'.str_replace(" ","_",''.$_SESSION['entreprise'].'').'_tbl` SET nbrepostes="'.$_POST['np'.$idi.''].'" WHERE id= "'.$idi.'"' ;
	$req = mysql_query($sql);
	}
elseif($modif=="comp")
	{
	$sql = 'UPDATE `e_'.str_replace(" ","_",''.$_SESSION['entreprise'].'').'_tbl` SET mincomp="'.$_POST['comp'.$idi.''].'" WHERE id= "'.$idi.'"' ;
	$req = mysql_query($sql);
	}
elseif($modif=="bdd")
	{
	$sql = 'UPDATE `e_'.str_replace(" ","_",''.$_SESSION['entreprise'].'').'_tbl` SET bdd="'.$_POST['bdd'.$idi.''].'" WHERE id= "'.$idi.'"' ;
	$req = mysql_query($sql);
	}
elseif($modif=="cand")
	{
	$sql = 'UPDATE `e_'.str_replace(" ","_",''.$_SESSION['entreprise'].'').'_tbl` SET candidature="'.$_POST['cand'.$idi.''].'" WHERE id= "'.$idi.'"' ;
	$req = mysql_query($sql);
	}
elseif($modif=="suppr")
	{
	$sql = 'SELECT poste FROM `e_'.str_replace(" ","_",''.$_SESSION['entreprise'].'').'_tbl` WHERE id= "'.$idi.'"' ;
	$req = mysql_query($sql);
	$pos = mysql_result($req,0,poste); 
	$sql = 'SELECT id FROM principal_tbl WHERE entreprise = "'.$_SESSION['entreprise'].'" AND type= "'.$pos.'"' ;
	$req = mysql_query($sql);
	$res = mysql_num_rows($req);
	
	for($i=0; $i != $res ; $i++)
		{
		$ideo = mysql_result($req,$i,id); 
		$sql1 = 'SELECT pseudo FROM principal_tbl WHERE id= "'.$ideo.'"' ;
		$req1 = mysql_query($sql1);
		$ps = mysql_result($req1,0,pseudo); 
		
		$sql2 = 'INSERT INTO messages_tbl(id,auteur,cible,message,moment) VALUES("","'.$_SESSION['entreprise'].'","'.$ps.'","Vous êtes licencié.","'.time().'")' ;
		$req2 = mysql_query($sql2);
		$sql2 = 'UPDATE messages_tbl SET entreprise="Aucune" , type="Aucun" , salaire="0" , points="0" , difficulte="0" WHERE id="'.$ideo.'"' ;
		$req2 = mysql_query($sql2);
		}
	$sql = 'DELETE FROM `e_'.str_replace(" ","_",''.$_SESSION['entreprise'].'').'_tbl` WHERE id="'.$idi.'"' ;
	$req = mysql_query($sql);
	print('<meta http-equiv="refresh" content="0 ; url=http://v2.dreadcast.net/ingame/engine=personnel.php">');
	exit();
	}

mysql_close($db);

print('<meta http-equiv="refresh" content="0 ; url=http://v2.dreadcast.net/ingame/engine=persodetail.php?ent='.$_SESSION['entreprise'].'&id='.$idi.'">');

?>

</p>
</div>
<?php if($chat=="oui") 	{ if ((date("H")>=21) || (date("H")<=7)) { include("inc_bas_n_c.php"); } else { include("inc_bas_j_c.php"); } } else {	if ((date("H")>=21) || (date("H")<=7)) { include("inc_bas_n.php"); } else { include("inc_bas_j.php"); }	} ?>
<?php include("inc_bas_de_page.php"); ?>
