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


<?php

$db = mysql_connect('localhost', 'CENSURE', 'CENSURE') or die("Erreur de connexion au serveur des messages.");
mysql_select_db('CENSURE',$db) or die("Erreur de connexion a la base de donnees des messages.");

$sql = 'SELECT id FROM cercles_tbl WHERE pseudo= "'.$_SESSION['pseudo'].'"' ;
$req = mysql_query($sql);
$resc = mysql_num_rows($req);
if($resc>0)
	{
	print('<meta http-equiv="refresh" content="0 ; url=http://v2.dreadcast.net/ingame/engine=cercle.php"> ');
	exit();
	}

for($i=1;$i!=7;$i++)
	{
	$sql = 'SELECT case'.$i.' FROM principal_tbl WHERE case'.$i.' LIKE "%Recueil%" AND id= "'.$_SESSION['id'].'"' ;
	$req = mysql_query($sql);
	$res = mysql_num_rows($req);
	if($res>0)
		{
		$recueil = mysql_result($req,0,'case'.$i.'');
		$sqlsign = 'SELECT * FROM signatures_tbl WHERE numero= "'.substr($recueil,7,strlen($recueil)).'"' ;
		$reqsign = mysql_query($sqlsign);
		if((mysql_result($reqsign,0,sign1)==$_SESSION['pseudo']) && (mysql_result($reqsign,0,sign2)!="") && (mysql_result($reqsign,0,sign3)!="") && (mysql_result($reqsign,0,sign4)!="") && (mysql_result($reqsign,0,sign5)!=""))
			{
			$caseinv = $i;
			for($u=1;$u!=6;$u++)
				{
				$sqlc2 = 'SELECT id FROM cercles_tbl WHERE pseudo= "'.mysql_result($reqsign,0,'sign'.$u.'').'"' ;
				$reqc2 = mysql_query($sqlc2);
				$resc2 = mysql_num_rows($reqc2);
				if($resc2>0)
					{
					print('<meta http-equiv="refresh" content="0 ; url=http://v2.dreadcast.net/ingame/engine.php"> ');
					exit();
					}
				}
			}
		}
	}

$sql = 'SELECT credits FROM principal_tbl WHERE id= "'.$_SESSION['id'].'"' ;
$req = mysql_query($sql);
$credits = mysql_result($req,0,credits);

$sql = 'SELECT id FROM cerclesliste_tbl WHERE nom= "'.$_POST['nomducercle'].'"' ;
$req = mysql_query($sql);
$res = mysql_num_rows($req);

$cerclen = $_POST['nomducercle'];

for($i=0;$i!=strlen($cerclen);$i++)
	{
	$p = $cerclen{$i};
	if (preg_match("#[a-zA-Z0-9]#",$p))
		{
		}
	else
		{
		$pasok = 3;
		}
	} 

if($credits<1500)
	{
	print('<meta http-equiv="refresh" content="0 ; url=http://v2.dreadcast.net/ingame/engine.php"> ');
	exit();
	}
elseif(($_POST['nomducercle']=="") || ($_POST['postediri']=="") || ($_POST['postebe']==""))
	{
	$pasok = 1;
	}
elseif($res>0)
	{
	$pasok = 2;
	}
elseif($pasok!=3)
	{
	$credits = $credits - 1500;
	
	$sql = 'UPDATE principal_tbl SET credits= "'.$credits.'" WHERE id= "'.$_SESSION['id'].'"' ;
	$req = mysql_query($sql);
	
	
	
	if($_POST['secteur']!="") $secteur = htmlentities($_POST['secteur']);
	else {
		print('<meta http-equiv="refresh" content="0 ; url=http://v2.dreadcast.net/ingame/engine=cerclecreer.php"> ');
		exit();
	}
	$ruechoix = $_POST['rue'];
	while(!($infos = recupereEmplacement($secteur,htmlentities($ruechoix))));
	
	$nrue = $infos['rue'];
	$nnum = $infos['num'];
	
	$sql = 'UPDATE carte_tbl SET type= "1" WHERE num="'.$nnum.'" AND idrue=(SELECT id FROM rues_tbl WHERE nom LIKE "'.$nrue.'")';
	$req = mysql_query($sql);
	
	
	
	$sql = 'INSERT INTO objets_tbl(id,nom,puissance,image,url,infos,prix,type) VALUES("","Tracte '.$_POST['nomducercle'].'","0","tracte.gif","engine=tracte.php?'.$_POST['nomducercle'].'","Ceci est un tracte pour le cercle '.$_POST['nomducercle'].'.","0","tracte")';
	$req = mysql_query($sql);
	
	$sql = 'INSERT INTO cerclesliste_tbl(id,nom,type,num,rue,capital,logo,description,public) VALUES("","'.$_POST['nomducercle'].'","'.$_POST['typedecercle'].'","'.$nnum.'","'.$nrue.'","500","im_objets/logocercle.gif","Aucune description.","'.$_POST['public'].'")';
	mysql_query($sql);
	$sql = 'INSERT INTO lieu_tbl(id,rue,num,nom,code,camera,prix,repos,reposactuel) VALUES("","'.$nrue.'","'.$nnum.'","Local 20m²","1","Non","","2","2")';
	mysql_query($sql);
	
	// POUR LE FORUM
	$sql = 'INSERT INTO wikast_forum_structure_tbl(id,type,nom,admin) VALUES("","-1","Cercle '.$_POST['nomducercle'].'","'.$_SESSION['pseudo'].'")';
	mysql_query($sql);
	$sql = 'SELECT id FROM wikast_forum_structure_tbl WHERE nom="Cercle '.$_POST['nomducercle'].'" ORDER BY id DESC';
	$req = mysql_query($sql);
	$fidcercle = mysql_result($req,0,id);
	$sql = 'INSERT INTO wikast_forum_structure_tbl(id,type,nom,admin) VALUES("","'.$fidcercle.'","Actualit&eacute; du cercle","'.$_SESSION['pseudo'].'")';
	mysql_query($sql);
	$ssfidcercle = $fidcercle + 1;
	$texteaccueil = 'Ce forum est enti&egrave;rement d&eacute;di&eacute; aux membres du cercle '.$_POST['nomducercle'].'.
<br />Le mod&eacute;rateur actuel du forum [i]'.$_POST['nomducercle'].'[/i] et du sous-forum [i]Actualit&eacute; du cercle[/i] est actuellement [g]'.$_SESSION['pseudo'].'[/g].
<br />Vous pouvez bien entendu changer les droits et modifier la structure du forum en vous rendant sur cette page : [lien url=&lt;forum=moderation.php?forum='.$fidcercle.'&gt;]Modifier le forum[/lien]
<br />
<br />Vous pouvez &eacute;diter ce message si vous le souhaitez.';
	$sql = 'INSERT INTO wikast_forum_sujets_tbl(id,categorie,nom,date,auteur,contenu) VALUES("","'.$ssfidcercle.'","Bienvenue sur le forum '.$_POST['nomducercle'].'","'.time().'","'.$_SESSION['pseudo'].'","'.$texteaccueil.'")';
	mysql_query($sql);

	$sql = 'CREATE TABLE `c_'.str_replace(" ","_",''.$_POST['nomducercle'].'').'_tbl` ('
			. ' id int NOT NULL auto_increment,'
			. ' poste varchar(30) NOT NULL,'
			. ' bdd varchar(200) NOT NULL,'
			. ' description text NOT NULL,'
			. ' PRIMARY KEY (id))';
	mysql_query($sql);
	
	$sql = 'INSERT INTO `c_'.str_replace(" ","_",''.$_POST['nomducercle'].'').'_tbl` (id,poste,bdd) VALUES("1","'.$_POST['postediri'].'","tout")';
	mysql_query($sql);
	$sql = 'INSERT INTO `c_'.str_replace(" ","_",''.$_POST['nomducercle'].'').'_tbl` (id,poste,bdd) VALUES("2","'.$_POST['postebe'].'","")';
	mysql_query($sql);
	
	$sql = 'INSERT INTO cercles_tbl(id,pseudo,cercle,poste) VALUES("","'.$_SESSION['pseudo'].'","'.$_POST['nomducercle'].'","'.$_POST['postediri'].'")';
	mysql_query($sql);
	$sql = 'INSERT INTO cercles_tbl(id,pseudo,cercle,poste) VALUES("","'.mysql_result($reqsign,0,sign2).'","'.$_POST['nomducercle'].'","'.$_POST['postebe'].'")';
	mysql_query($sql);
	$sql = 'INSERT INTO cercles_tbl(id,pseudo,cercle,poste) VALUES("","'.mysql_result($reqsign,0,sign3).'","'.$_POST['nomducercle'].'","'.$_POST['postebe'].'")';
	mysql_query($sql);
	$sql = 'INSERT INTO cercles_tbl(id,pseudo,cercle,poste) VALUES("","'.mysql_result($reqsign,0,sign4).'","'.$_POST['nomducercle'].'","'.$_POST['postebe'].'")';
	mysql_query($sql);
	$sql = 'INSERT INTO cercles_tbl(id,pseudo,cercle,poste) VALUES("","'.mysql_result($reqsign,0,sign5).'","'.$_POST['nomducercle'].'","'.$_POST['postebe'].'")';
	mysql_query($sql);

	$sql = 'UPDATE principal_tbl SET case'.$caseinv.'= "Vide" WHERE id= "'.$_SESSION['id'].'"';
	mysql_query($sql);
	}


mysql_close($db);
?>

<script language="JavaScript" type="text/JavaScript">
<!--
function MM_jumpMenu(targ,selObj,restore){ //v3.0
  eval(targ+".location='"+selObj.options[selObj.selectedIndex].value+"'");
  if (restore) selObj.selectedIndex=0;
}
//-->
</script>


<div id="haut">
	<?php include("inc_entete.php"); ?>
	<div class="haut_aligngauche">
		<p>
		<div class="titrepage">
			Votre cercle
		</div>
		<b class="module4ie"><a href="engine=cerclecreer.php" class="module4"><img src="im_objets/icon_retour.gif" alt="Retour" />Retour</a></b>
</p>
	</div>
</div>
<div id="centre">
<p>

<table width="150" height="25" bgcolor="#FFFFFF" border="1" align="center" cellpadding="0" cellspacing="0">
	<tr>
		<td><div align="center"><strong>Créer un cercle</strong></div></td>
	</tr>
</table>
<table width="450" height="250" bgcolor="#FFFFFF" border="1" align="center" cellpadding="0" cellspacing="0">
	<tr>
	  <td><div align="center">
        <p><strong>
<?php
if($pasok==1)
	{
	print('Un champ est vide !');
	}
elseif($pasok==2)
	{
	print('Un cercle porte déjà ce nom !');
	}
elseif($pasok==3)
	{
	print('Vous ne pouvez pas utiliser de caractères spéciaux pour le nom du cercle !');
	}
else
	{
	print('Votre cercle est maintenant créé.</strong><br /><br />Il vous reste cependant encore beaucoup de choses à configurer.<br />Vous trouverez toutes les informations relatives à votre nouveau cercle en retournant dans la rubrique "<a href="engine=cercle.php">Votre cercle</a>".<br />De plus, n\'oubliez pas d\'aller faire un tour sur le Wikast, vous disposez maintenant d\'un forum sp&eacute;cifique &agrave; votre cercle.');
	}
?>
	  </p></div></td>
	</tr>
</table>


</p>
</div>
<?php if($chat=="oui") 	{ if ((date("H")>=21) || (date("H")<=7)) { include("inc_bas_n_c.php"); } else { include("inc_bas_j_c.php"); } } else {	if ((date("H")>=21) || (date("H")<=7)) { include("inc_bas_n.php"); } else { include("inc_bas_j.php"); }	} ?>
<?php include("inc_bas_de_page.php"); ?>
