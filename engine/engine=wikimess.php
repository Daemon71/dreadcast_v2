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
if(($_SESSION['statut']!="Administrateur") && ($_SESSION['statut']!="DÈveloppeur") && ($_SESSION['statut']!="ModÈrateur RPIG") && ($_SESSION['statut']!="ModÈrateur communication"))
	{
	print('<meta http-equiv="refresh" content="0 ; url=http://v2.dreadcast.net/ingame/engine.php"> ');
	exit();
	}
	
$db = mysql_connect('localhost', 'CENSURE', 'CENSURE') or die("Erreur de connexion au serveur des messages.");
mysql_select_db('CENSURE',$db) or die("Erreur de connexion a la base de donnees des messages.");
	
$article = $_GET['article'];

if($_POST['valider']!="" OR $_POST['refuser']!="")
	{
	if($_POST['refuser']!="" AND $_POST['raison']=="")
		{
		$article = $_POST['article'];
		$erreurraison = "IL FAUT SP&Eacute;CIFIER UNE RAISON POUR UN REFUS";
		}
	else
		{
		$article="";
		
		$titre = ($_POST['valider']!="")?"Wiki : Validation":"Wiki : Refus";
		$message = ($_POST['valider']!="")?"Votre message a &eacute;t&eacute; ajout&eacute; aux articles du Wiki.":"Votre message a &eacute;t&eacute; refus&eacute; par les administrateurs.<br /><br />Raisons :<br />".$_POST['raison'];
		$sql = 'INSERT INTO messages_tbl(id,auteur,cible,message,objet,moment,nouveau) VALUES("","Dreadcast","'.$_POST['auteur'].'","'.$message.'","'.$titre.'","'.time().'","oui")';
		mysql_query($sql);
		
		//ACTIONS RELATIVES AUX REFUS/VALIDATIONS
		if($_POST['valider']!="")
			{
			$sql2 = 'SELECT titre FROM wikast_wiki_articles_tbl WHERE id="'.$_POST['article'].'"';
			$req2 = mysql_query($sql2);
			$res2 = mysql_num_rows($req2);
			$sql2 = 'SELECT id FROM wikast_wiki_articles_tbl WHERE titre="'.mysql_result($req2,0,titre).'" AND etat=2';
			$req2 = mysql_query($sql2);
			$res2 = mysql_num_rows($req2);
			
			if($res2!="")
				{
				$sql = 'UPDATE wikast_wiki_articles_tbl SET etat = 0 WHERE id="'.mysql_result($req2,0,id).'"';
				mysql_query($sql);
				}
			
			$sql = 'UPDATE wikast_wiki_articles_tbl SET etat = 2 WHERE id="'.$_POST['article'].'"';
			mysql_query($sql);
			}
		else
			{
			$sql = 'UPDATE wikast_wiki_articles_tbl SET etat = "-1" WHERE id="'.$_POST['article'].'"';
			mysql_query($sql);
			}
		}
	}

if($article=="")
	{
	print('<meta http-equiv="refresh" content="0 ; url=http://v2.dreadcast.net/ingame/engine=wikiliste.php"> ');
	exit();
	}

$sql = 'SELECT id,rue,num,action,alim,sante,fatigue FROM principal_tbl WHERE pseudo= "'.$_SESSION['pseudo'].'"' ;
$req = mysql_query($sql);
$_SESSION['id'] = mysql_result($req,0,id);
$_SESSION['rue'] = mysql_result($req,0,rue);
$_SESSION['num'] = mysql_result($req,0,num);
$_SESSION['action'] = mysql_result($req,0,action);
$_SESSION['alim'] = mysql_result($req,0,alim);
$_SESSION['sante'] = mysql_result($req,0,sante);
$_SESSION['fatigue'] = mysql_result($req,0,fatigue);
$_SESSION['lieu'] = strtolower($_SESSION['rue']);
$_SESSION['lieu'] = ucwords($_SESSION['lieu']);

$sql = 'SELECT id FROM articlesprop_tbl' ;
$req = mysql_query($sql);
$resa = mysql_num_rows($req);

mysql_close($db);
?>
<?php if ((date("H")>=21) || (date("H")<=7)) { include("inc_haut_n.php"); } else { include("inc_haut_j.php");} ?>

<div id="haut">
	<?php include("inc_entete.php"); ?>
	<div class="haut_aligngauche">
		<p>
		<div class="titrepage">
			Panneau d'administration
		</div>
		<b class="module4ie"><a href="engine=wikiliste.php" class="module4"><img src="im_objets/icon_retour.gif" alt="Retour" />Retour</b></a>
		</p>
	</div>
</div>
<?php include('inc_admin.php'); ?>
<div id="centre">
<div class="vae" style="text-align:left;margin-left:60px;height:270px;width:400px;overflow:auto;">
<?php 

if(($_SESSION['statut']=="Administrateur") OR ($_SESSION['statut']=="ModÈrateur communication") OR ($_SESSION['statut']=="DÈveloppeur"))
	{
	$db = mysql_connect('localhost', 'CENSURE', 'CENSURE') or die("Erreur de connexion au serveur des messages.");
	mysql_select_db('CENSURE',$db) or die("Erreur de connexion a la base de donnees des messages.");
	
	$sql = 'SELECT * FROM wikast_wiki_articles_tbl WHERE id = '.$_GET['article'].'';
	$req = mysql_query($sql);
	$res = mysql_num_rows($req);
	
	if($res!=0)
		{
		$sql2 = 'SELECT id FROM wikast_wiki_articles_tbl WHERE titre LIKE "'.mysql_result($req,0,titre).'" AND etat = 2';
		$req2 = mysql_query($sql2);
		$res2 = mysql_num_rows($req2);
		
		if($res2==0)
			{
			$type = "Oui";
			}
		else
			{
			$type = 'Non - <a href="../wikast/wiki.php?id='.mysql_result($req2,0,id).'" onclick="window.open(this.href); return false;">Version actuelle<a/> | <a href="engine=wikiaffiche.php?article='.mysql_result($req2,0,id).'" onclick="window.open(this.href); return false;">Toutes les versions du sujet</a><br /><strong>Justification</strong> :<br />'.mysql_result($req,0,commentaire);
			}
			
		print('<strong>Titre</strong> : '.mysql_result($req,0,titre).'<br />
		<strong>Auteur</strong> : '.mysql_result($req,0,createur).'<br />
		<strong>Date</strong> : '.date('d/m/y',mysql_result($req,0,moment)).' &agrave; '.date('H\hi',mysql_result($req,0,moment)).'<br />
		<strong>Cat&eacute;gorie</strong> : '.mysql_result($req,0,categorie).'<br />
		<strong>Nouveau</strong> : '.$type.'<br />
		<strong>Mots-cl&eacute;(s)</strong> : '.mysql_result($req,0,mots).'<br /><br />
		<strong><a href="../wikast/wiki=apercu.php?article='.mysql_result($req,0,id).'" onclick="window.open(this.href); return false;">Aper&ccedil;u</a></strong><br /><br />
		<form action="#" method="post" name="poster">
			<input name="valider" type="submit" value="Valider" class="ok2" /> <input name="refuser" type="submit" value="Refuser" class="ok2" />
			<br /><br />Raison du refus<br />
			<textarea name="raison" id="textarea" style="width:350px;height:100px;">'.$erreurraison.'</textarea>
			<input name="article" value="'.$_GET['article'].'" type="hidden"/>
			<input name="auteur" value="'.mysql_result($req,0,createur).'" type="hidden"/>
		</form>');
		}
	else
		{
		print("<strong>Ce message n'existe pas.</strong><br />");
		}
		
	mysql_close($db);
	}
else
	{
	print('<meta http-equiv="refresh" content="0 ; url=http://v2.dreadcast.net/ingame/engine.php"> ');
	exit();
	}

?>
</div>
</div>
<?php if($chat=="oui") 	{ if ((date("H")>=21) || (date("H")<=7)) { include("inc_bas_n_c.php"); } else { include("inc_bas_j_c.php"); } } else {	if ((date("H")>=21) || (date("H")<=7)) { include("inc_bas_n.php"); } else { include("inc_bas_j.php"); }	} ?>
<?php include("inc_bas_de_page.php"); ?>
