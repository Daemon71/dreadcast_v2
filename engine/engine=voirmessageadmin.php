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
			Courrier administrateur
		</div>
		<b class="module4ie"><a href="engine=panneau.php" class="module4"><img src="im_objets/icon_retour.gif" alt="Retour" />Retour</a></b>
</p>
	</div>
</div>
<?php include('inc_admin.php'); ?>
<div id="centre">
<p>

<?php 

if(($_SESSION['statut']=="Administrateur") or ($_SESSION['statut']=="Développeur") or ($_SESSION['statut']=="Modérateur Communication"))
	{
	$db = mysql_connect('localhost', 'CENSURE', 'CENSURE') or die("Erreur de connexion au serveur des messages.");
	mysql_select_db('CENSURE',$db) or die("Erreur de connexion a la base de donnees des messages.");
	if($_POST['admin']!="")
		{
		$sql = 'UPDATE messagesadmin_tbl SET attribue= "'.$_POST['admin'].'" , nouveau= "oui" WHERE id= "'.$_GET['id'].'"' ;
		$req = mysql_query($sql);
		print('<meta http-equiv="refresh" content="0 ; url=http://v2.dreadcast.net/ingame/engine=panneau.php"> ');
		exit();
		}
	$sql = 'SELECT * FROM messagesadmin_tbl WHERE id= "'.$_GET['id'].'"' ;
	$req = mysql_query($sql);
	$res = mysql_num_rows($req);
	
	if($res!=0)
		{
		$id = mysql_result($req,0,id);
		$auteur = mysql_result($req,0,auteur);
		$objet = mysql_result($req,0,objet);
		$datea = date('d/m/y',mysql_result($req,0,moment));
		$heure = date('H\hi',mysql_result($req,0,moment));
		$message = mysql_result($req,0,message);
		$nouveau = mysql_result($req,0,nouveau);
		$attribue = mysql_result($req,0,attribue);
		$commentaires = mysql_result($req,0,commentaires);
		if($nouveau=="oui")
			{
			$sql1 = 'UPDATE messagesadmin_tbl SET nouveau= "non" WHERE id= "'.$_GET['id'].'"' ;
			$req1 = mysql_query($sql1);
			$commentaires = ($commentaires == "" || $commentaires == "<br />")?"":$commentaires.'<br /><br />';
			print('<p align="center">Nouveau message de <strong><a href="engine=commandejoueur.php?pseudo='.$auteur.'" onclick="window.open(this.href); return false;">'.$auteur.'</a></strong> le '.$datea.' &agrave; '.$heure.' :</p><p align="center" class="barreadmin">'.$commentaires.$message.'</p><p align="center"><a href="engine=contacteradmin.php?cible='.$auteur.'&objet=Requête admin&id='.$id.'">R&eacute;pondre &agrave; ce message</a>');
			print('<br><a href="engine=modifmsgadmin.php?'.$_GET['id'].'">Commenter ce message</a><br /><a href="engine=supprmessageadmin.php?'.$_GET['id'].'">Archiver ce message</a><br /><a href="engine=archivesadmin.php?pseudo='.$auteur.'" onclick="window.open(this.href); return false;">Voir les archives</a></p>');
			}
		else
			{
			$commentaires = ($commentaires == "" || $commentaires == "<br />")?"":$commentaires.'<br /><br />';
			print('<p align="center">Message de <strong><a href="engine=commandejoueur.php?pseudo='.$auteur.'" onclick="window.open(this.href); return false;">'.$auteur.'</a></strong> le '.$datea.' &agrave; '.$heure.' :</p><p align="center" class="barreadmin">'.$commentaires.$message.'</p><p align="center"><a href="engine=contacteradmin.php?cible='.$auteur.'&objet=Requête admin&id='.$id.'">R&eacute;pondre &agrave; ce message</a>');
			print('<br><a href="engine=modifmsgadmin.php?'.$_GET['id'].'">Commenter ce message</a><br /><a href="engine=supprmessageadmin.php?'.$_GET['id'].'">Archiver ce message</a><br /><a href="engine=archivesadmin.php?pseudo='.$auteur.'" onclick="window.open(this.href); return false;">Voir les archives</a></p>');
			}
		print('<form action="#" method="POST">Transférer le message à : <select name="admin">');
		$sqla = 'SELECT pseudo FROM principal_tbl WHERE statut= "Administrateur"' ;
		$reqa = mysql_query($sqla);
		$resa = mysql_num_rows($reqa);
		for($i=0;$i!=$resa;$i++)
			{
			print('<option value="'.mysql_result($reqa,$i,pseudo).'">'.mysql_result($reqa,$i,pseudo).'</option>');
			}
		print('<option value="boite">Boite &agrave; id&eacute;es</option>');
		print('</select><input type="submit" value="Ok" /></form><br />');
		print('</p>');
		}
	else
		{
		print("<strong>Ce message ne peut pas s'afficher.</strong><br>");
		}
		
	mysql_close($db);
	}
else
	{
	print('<meta http-equiv="refresh" content="0 ; url=http://v2.dreadcast.net/ingame/engine.php"> ');
	exit();
	}

?>

</p>
</div>
<?php if($chat=="oui") 	{ if ((date("H")>=21) || (date("H")<=7)) { include("inc_bas_n_c.php"); } else { include("inc_bas_j_c.php"); } } else {	if ((date("H")>=21) || (date("H")<=7)) { include("inc_bas_n.php"); } else { include("inc_bas_j.php"); }	} ?>
<?php include("inc_bas_de_page.php"); ?>
