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
			Detruire un objet
		</div>
		</p>
	</div>
</div>
<div id="centre">
<p>

<?php
if($_GET['ok']!=1)
	{
	print('Êtes-vous sûr de vouloir détruire cet objet ?<br /><br />
	<a href="engine=det.php?arme='.$_SERVER['QUERY_STRING'].'&ok=1">Oui</a> - <a href="engine=inventaire.php">Non</a>');
	}
else
	{
	print('<img src="im_objets/loader.gif" alt="..." />');
	$db = mysql_connect('localhost', 'CENSURE', 'CENSURE') or die("Erreur de connexion au serveur des messages.");
	mysql_select_db('CENSURE',$db) or die("Erreur de connexion a la base de donnees des messages.");
	$equip = str_replace("%20"," ",''.$_GET['arme'].'');
	
	if($equip=="arme")
		{
		$sql = 'SELECT arme FROM principal_tbl WHERE id= "'.$_SESSION['id'].'"' ;
		$req = mysql_query($sql);
		$item = mysql_result($req,0,arme);
		$gen = strstr($item,"-");
		$sql2 = 'SELECT id FROM armes_tbl WHERE idarme= "'.$gen.'"';
		$req2 = mysql_query($sql2);
		$nbre = mysql_num_rows($req2);
		if($nbre>0)
			{
			$sql2 = 'DELETE FROM armes_tbl WHERE idarme= "'.$gen.'"';
			$req2 = mysql_query($sql2);
			$sql2 = 'DELETE FROM objets_tbl WHERE nom= "'.$item.'"' ;
			$req2 = mysql_query($sql2);
			}
		$sql = 'UPDATE principal_tbl SET arme="Aucune" WHERE id= "'.$_SESSION['id'].'"' ;
		$req = mysql_query($sql);
		print('<meta http-equiv="refresh" content="0 ; url=http://v2.dreadcast.net/ingame/engine=inventaire.php"> ');
		}
	elseif($equip=="objet")
		{
		$sql = 'UPDATE principal_tbl SET objet="Aucun" WHERE id= "'.$_SESSION['id'].'"' ;
		$req = mysql_query($sql);
		print('<meta http-equiv="refresh" content="0 ; url=http://v2.dreadcast.net/ingame/engine=inventaire.php"> ');
		}
	else
		{
		$case = 'case'.$equip;
		$sql = 'SELECT case'.$equip.' FROM principal_tbl WHERE id= "'.$_SESSION['id'].'"' ;
		$req = mysql_query($sql);
		$item = mysql_result($req,0,$case);
		$gen = strstr($item,"-");
		$sql2 = 'SELECT id FROM armes_tbl WHERE idarme= "'.$gen.'"';
		$req2 = mysql_query($sql2);
		$nbre = mysql_num_rows($req2);
		if($nbre>0)
			{
			$sql2 = 'DELETE FROM armes_tbl WHERE idarme= "'.$gen.'"';
			$req2 = mysql_query($sql2);
			$sql2 = 'DELETE FROM objets_tbl WHERE nom= "'.$item.'"' ;
			$req2 = mysql_query($sql2);
			}
		$sql = 'UPDATE principal_tbl SET case'.$equip.'="Vide" WHERE id= "'.$_SESSION['id'].'"' ;
		$req = mysql_query($sql);
		print('<meta http-equiv="refresh" content="0 ; url=http://v2.dreadcast.net/ingame/engine=inventaire.php"> ');
		}
	mysql_close($db);
	}
?>

</p>
</div>
<?php if($chat=="oui") 	{ if ((date("H")>=21) || (date("H")<=7)) { include("inc_bas_n_c.php"); } else { include("inc_bas_j_c.php"); } } else {	if ((date("H")>=21) || (date("H")<=7)) { include("inc_bas_n.php"); } else { include("inc_bas_j.php"); }	} ?>
<?php include("inc_bas_de_page.php"); ?>
