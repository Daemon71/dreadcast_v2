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
		<b class="module4ie"><a href="engine=panneau.php" class="module4"><img src="im_objets/icon_retour.gif" alt="Retour" />Retour</b></a>
		</p>
	</div>
</div>
<?php include('inc_admin.php'); ?>
<div id="centre">
<p>
<div class="messagesvip">
<?php 

if(($_SESSION['statut']=="Administrateur") OR ($_SESSION['statut']=="ModÈrateur communication") OR ($_SESSION['statut']=="DÈveloppeur"))
	{
	$db = mysql_connect('localhost', 'CENSURE', 'CENSURE') or die("Erreur de connexion au serveur des messages.");
	mysql_select_db('CENSURE',$db) or die("Erreur de connexion a la base de donnees des messages.");
	
	$sql = 'SELECT * FROM wikast_wiki_articles_tbl WHERE etat = 1';
	$req = mysql_query($sql);
	$res = mysql_num_rows($req);
	
	if($res!=0)
		{
		print('<table width="90%"  border="1" align="center" cellpadding="0" cellspacing="0">
					<tr bgcolor="#B6B6B6">
					  <th scope="col"><div align="center">Section</div></th>
					  <th scope="col"><div align="center">Titre</div></th>
					  <th scope="col"><div align="center">Auteur</div></th>
					  <th scope="col"><div align="center">Date</div></th>
					  <th scope="col"><div align="center">Type</div></th>
					</tr>');
		
		for($i=0; $i != $res ; $i++) 
			{ 
			$sql2 = 'SELECT id FROM wikast_wiki_articles_tbl WHERE titre LIKE "'.mysql_result($req,$i,titre).'" AND etat = 2';
			$req2 = mysql_query($sql2);
			$res2 = mysql_num_rows($req2);
			if($res2==0) $type = "Cr&eacute;ation";
			else $type = "Modification";
			
			print('
				<tr>
					<td>'.mysql_result($req,$i,categorie).'</td>
					<td><a href="engine=wikimess.php?article='.mysql_result($req,$i,id).'">'.mysql_result($req,$i,titre).'</a></td>
					<td>'.mysql_result($req,$i,createur).'</td>
					<td>'.date('d/m/y',mysql_result($req,$i,moment)).'</td>
					<td>'.$type.'</td>
				</tr>');
			
			}
		print('</table>');
		}
	else
		{
		print("<strong>Il n'y a aucun article Wiki en attente.</strong><br />");
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
</p>
</div>
<?php if($chat=="oui") 	{ if ((date("H")>=21) || (date("H")<=7)) { include("inc_bas_n_c.php"); } else { include("inc_bas_j_c.php"); } } else {	if ((date("H")>=21) || (date("H")<=7)) { include("inc_bas_n.php"); } else { include("inc_bas_j.php"); }	} ?>
<?php include("inc_bas_de_page.php"); ?>
