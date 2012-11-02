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
if($_SESSION['creationent']!="ok")
	{
	print('<meta http-equiv="refresh" content="0 ; url=http://v2.dreadcast.net/ingame/engine.php"> ');
	exit();
	}
$_SESSION['creationent'] = "";
?>
<?php if ((date("H")>=21) || (date("H")<=7)) { include("inc_haut_n.php"); } else { include("inc_haut_j.php");} ?>

<div id="haut">
	<?php include("inc_entete.php"); ?>
	<div class="haut_aligngauche">
		<p>
		<div class="titrepage">
			Cr&eacute;ation d'entreprise
		</div>
		<b class="module4ie"><a href="engine=activite.php" class="module4"><img src="im_objets/icon_retour.gif" alt="Retour" />Retour</a></b>
</p>
	</div>
</div>
<div id="centre">
<p>

<?php


$db = mysql_connect('localhost', 'CENSURE', 'CENSURE') or die("Erreur de connexion au serveur.");
mysql_select_db('CENSURE',$db) or die("Erreur de connexion a la base de donnees.");

$sql = 'SELECT credits FROM principal_tbl WHERE id= "'.$_SESSION['id'].'"' ;
$req = mysql_query($sql);
$_SESSION['credits'] = mysql_result($req,0,credits); 

if($_SESSION['credits']<$_SESSION['totalent'])
	{
	print('<p align="center"><strong>Vous n\'avez pas assez de Cr&eacute;dits. </strong>');
	exit();
	}

// RECUPERATION DE LIEU
if($_SESSION['sectcent']!="") $secteur = htmlentities($_GET['sec']);
else {
	print('<meta http-equiv="refresh" content="0 ; url=http://v2.dreadcast.net/ingame/engine=creerent.php"> ');
	exit();
}
while(!($infos = recupereEmplacement(htmlentities($_SESSION['sectcent']),htmlentities($_SESSION['ruecent']))));
$nrue = $infos['rue'];
$nnum = $infos['num'];
$sql = 'UPDATE carte_tbl SET type= "1" WHERE num="'.$nnum.'" AND idrue=(SELECT id FROM rues_tbl WHERE nom LIKE "'.$nrue.'")';
$req = mysql_query($sql);

$_SESSION['credits'] = $_SESSION['credits'] - $_SESSION['totalent'];
$sql = 'UPDATE principal_tbl SET credits="'.$_SESSION['credits'].'" WHERE id= "'.$_SESSION['id'].'"' ;
$req = mysql_query($sql);

$sql = 'UPDATE principal_tbl SET type="PDG" , entreprise="'.$_SESSION['noment'].'" , salaire="0" , difficulte="0" , points="999" WHERE id= "'.$_SESSION['id'].'"' ;
$req = mysql_query($sql);

if($_SESSION['domaine']=="hopital")
	{
	$logo = "http://v2.dreadcast.net/ingame/im_objets/logo.jpg";
	$sql = 'INSERT INTO stocks_tbl(id,entreprise,objet,nombre,pvente) VALUES("","'.$_SESSION['noment'].'","Soins","1","5")';
	mysql_query($sql);
	if(ereg("pharmacie",''.$_SESSION['bdd'].''))
		{
		$sql = 'INSERT INTO stocks_tbl(id,entreprise,objet,nombre,pvente) VALUES("","'.$_SESSION['noment'].'","Medpack","0","155")';
		mysql_query($sql);
		}
	}
elseif($_SESSION['domaine']=="banque")
	{
	$logo = "http://v2.dreadcast.net/ingame/im_objets/logo.jpg";
	$sql = 'INSERT INTO stocks_tbl(id,entreprise,objet,nombre,pvente) VALUES("","'.$_SESSION['noment'].'","Pourc","9","0")';
	mysql_query($sql);
	}
elseif($_SESSION['domaine']=="ventes aux encheres")
	{
	$logo = "http://v2.dreadcast.net/ingame/im_objets/logo.jpg";
	$sql = 'INSERT INTO stocks_tbl(id,entreprise,objet,nombre,pvente) VALUES("","'.$_SESSION['noment'].'","Pourc","9","0")';
	mysql_query($sql);
	}
elseif($_SESSION['domaine']=="boutique armes")
	{
	$logo = "http://v2.dreadcast.net/ingame/im_objets/logo.jpg";
	$sql = 'INSERT INTO stocks_tbl(id,entreprise,objet,nombre,pvente) VALUES("","'.$_SESSION['noment'].'","Epee courte","5","210")';
	mysql_query($sql);
	}
elseif($_SESSION['domaine']=="agence immobiliaire")
	{
	$logo = "http://v2.dreadcast.net/ingame/im_objets/logo.jpg";
	$sql = 'INSERT INTO stocks_tbl(id,entreprise,objet,nombre,pvente) VALUES("","'.$_SESSION['noment'].'","Appartement 17m","1","150")';
	mysql_query($sql);
	}
elseif($_SESSION['domaine']=="usine de production")
	{
	$logo = "http://v2.dreadcast.net/ingame/im_objets/logo.jpg";
	$sql = 'INSERT INTO stocks_tbl(id,entreprise,objet,nombre,pvente) VALUES("","'.$_SESSION['noment'].'","Lugher","10","140")';
	mysql_query($sql);
	}
elseif($_SESSION['domaine']=="boutique spécialisee")
	{
	$logo = "http://v2.dreadcast.net/ingame/im_objets/logo.jpg";
	$sql = 'INSERT INTO stocks_tbl(id,entreprise,objet,nombre,pvente) VALUES("","'.$_SESSION['noment'].'","Digicode","10","150")';
	mysql_query($sql);
	}
elseif($_SESSION['domaine']=="bar cafe")
	{
	$logo = "http://v2.dreadcast.net/ingame/im_objets/logo.jpg";
	$sql = 'INSERT INTO stocks_tbl(id,entreprise,objet,nombre,pvente) VALUES("","'.$_SESSION['noment'].'","Cafe","100","10")';
	mysql_query($sql);
	}
else
	{
	$logo = "http://v2.dreadcast.net/ingame/im_objets/logo.jpg";
	}

$sql = 'INSERT INTO entreprises_tbl(id,nom,type,num,rue,ouvert,budget,logo) VALUES("","'.$_SESSION['noment'].'","'.$_SESSION['domaine'].'","'.$nnum.'","'.$nrue.'","non","2000","'.$logo.'")';
mysql_query($sql);

if($_SESSION['aent']==25)
{
$repos = 1;
}
elseif($_SESSION['aent']==50)
{
$repos = 2;
}
elseif($_SESSION['aent']==100)
{
$repos = 3;
}
elseif($_SESSION['aent']==200)
{
$repos = 4;
}

$sql = 'INSERT INTO lieu_tbl(id,rue,num,nom,code,camera,prix,repos,reposactuel) VALUES("","'.$nrue.'","'.$nnum.'","Local '.$_SESSION['aent'].'m²","1","Non","","'.$repos.'","'.$repos.'")';
mysql_query($sql);

$sql = 'CREATE TABLE `e_'.str_replace(" ","_",''.$_SESSION['noment'].'').'_tbl` ('
        . ' id int NOT NULL auto_increment,'
        . ' poste varchar(30) NOT NULL,'
        . ' type varchar(30) NOT NULL,'
        . ' salaire int(4) NOT NULL,'
        . ' bdd varchar(100) NOT NULL,'
        . ' nbrepostes int(3) NOT NULL,'
        . ' nbreactuel int(3) NOT NULL,'
        . ' competence varchar(20) NOT NULL,'
        . ' mincomp int(3) NOT NULL,'
        . ' candidature varchar(3) NOT NULL,'
        . ' mintrav int(3) NOT NULL,'
        . ' sinon varchar(3) NOT NULL,'
        . ' bonus int(4) NOT NULL,'
		. ' PRIMARY KEY (id))';
mysql_query($sql);

if($_SESSION['bdd']=="")
	{
	$_SESSION['bdd'] = "t";
	}

$sql = 'INSERT INTO `e_'.str_replace(" ","_",''.$_SESSION['noment'].'').'_tbl` (id,poste,type,salaire,bdd,nbrepostes,nbreactuel,competence,mincomp,candidature,mintrav,sinon,bonus) VALUES("","PDG","chef","","'.$_SESSION['bdd'].'","1","1","gestion","0","can","","","")';
mysql_query($sql);

if($_SESSION['domaine']=="bar cafe")
	{
	for($m=1;$m!=15;$m++)
		{
		$sql = 'INSERT INTO chat(id,posteur,message,rue,num,moment,ids) VALUES(""," "," ","'.$nrue.'","'.$nnum.'","'.date("H:i").'","'.$m.'")' ;
		$req = mysql_query($sql);
		}
	$sql = 'INSERT INTO chat(id,posteur,message,rue,num,moment,ids) VALUES("","Dreadcast","Bienvenue dans le salon de '.$_SESSION['noment'].'.","'.$nrue.'","'.$nnum.'","'.date("H:i").'","15")' ;
	$req = mysql_query($sql);
	}

enregistre($_SESSION['pseudo'],"entreprise","+1");

mysql_close($db);

?>
<strong>Votre entreprise est cr&eacute;e !</strong> </p>
		<p align="center">Vous pouvez acc&eacute;der au panneau de gestion &agrave; tout instant.
        </p>
		<div align="center"><strong>Informations secondaires :</strong> </div>
		<p align="center"><strong>Adresse du local : </strong><i><?php print(''.$nnum.' '.ucwords($nrue).''); ?></i></p>
        <p align="center">&nbsp;
            <?php 
	 if($_SESSION['domaine']!="autre")
	 {
	 print('<strong>Votre entreprise &agrave; &eacute;t&eacute; ajout&eacute;e au moteur de recherche.<br> Elle n\'y figurera comme ouverte que si un employ&eacute; est au travail.</strong>');
	 } 
	 ?>
        </p>
        <p align="center"> 
<a href="engine=gestion.php">Acc&eacute;der au panneau de gestion </a>

</p>
</div>
<?php if($chat=="oui") 	{ if ((date("H")>=21) || (date("H")<=7)) { include("inc_bas_n_c.php"); } else { include("inc_bas_j_c.php"); } } else {	if ((date("H")>=21) || (date("H")<=7)) { include("inc_bas_n.php"); } else { include("inc_bas_j.php"); }	} ?>
<?php include("inc_bas_de_page.php"); ?>
