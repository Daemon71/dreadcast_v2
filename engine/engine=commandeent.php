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
			Contr&ocirc;le d'une entreprise
		</div>
		<b class="module4ie"><a href="engine=commande.php" class="module4"><img src="im_objets/icon_retour.gif" alt="Retour" />Retour</a></b>
</p>
	</div>
</div>
<?php include('inc_admin.php'); ?>
<div id="centre">
<p>

<?php 

if(($_SESSION['statut']=="Administrateur") or ($_SESSION['statut']=="Développeur"))
	{
	$db = mysql_connect('localhost', 'CENSURE', 'CENSURE') or die("Erreur de connexion au serveur des messages.");
	mysql_select_db('CENSURE',$db) or die("Erreur de connexion a la base de donnees des messages.");

	if($_GET['suppr']==1)
		{
		print('<br />Êtes-vous sûr de vouloir supprimer l\'entreprise n°'.$_GET['id'].' ?<br /><br /><a href="engine=commandeent.php?suppr=2&id='.$_GET['id'].'">Oui</a> - <a href="engine=commande.php">Non</a>');
		}
	elseif($_GET['suppr']==2)
		{
		$id = $_GET['id'];
		supprimer_entreprise($id);
		print('<br />Entreprise supprimée.');
		}
	elseif($_GET['modif']==1)
		{
		$sql1 = 'UPDATE entreprises_tbl SET type="'.$_POST['type'].'" , num="'.$_POST['num'].'" , rue="'.$_POST['rue'].'" , logo="'.$_POST['logo'].'" , budget="'.$_POST['budget'].'" WHERE id= "'.$_POST['id'].'"' ;
		$req1 = mysql_query($sql1);
		print('<br />Modifications effectuées.');
		}
	else
		{
		$sql = 'SELECT * FROM entreprises_tbl WHERE nom LIKE "'.trim($_POST['entreprise']).'"' ;
		$req = mysql_query($sql);
		$res = mysql_num_rows($req);
		if($res>0)
			{
			$entreprise = mysql_result($req,0,nom);
			$sql2 = 'SELECT code FROM lieu_tbl WHERE num= "'.mysql_result($req,0,num).'" AND rue= "'.mysql_result($req,0,rue).'"' ;
			$req2 = mysql_query($sql2);
			$sql3 = 'SELECT poste FROM e_'.str_replace(" ","_",$entreprise).'_tbl WHERE type="chef"' ;
			$req3 = mysql_query($sql3);
			$sql3 = 'SELECT pseudo FROM principal_tbl WHERE type="'.mysql_result($req3,0,poste).'" AND entreprise="'.$entreprise.'"' ;
			$req3 = mysql_query($sql3);
			$res3 = mysql_num_rows($req3);
			
			print('<form action="engine=commandeent.php?modif=1" method="post"><div class="ebarreliste">
			ID: <input type="hidden" name="id" size="10" value="'.mysql_result($req,0,id).'" />'.mysql_result($req,0,id).' (<a href="engine=commandeent.php?suppr=1&id='.mysql_result($req,0,id).'">Supprimer l\'entreprise</a>)<br />
			<img src="'.mysql_result($req,0,logo).'" width="100" height="100" /><br />
			Logo: <input type="text" name="logo" size="10" value="'.mysql_result($req,0,logo).'" /><br />
			Type: <input type="text" name="type" size="10" value="'.mysql_result($req,0,type).'" /><br />
			PDG: '.(($res3==0)?"Aucun":mysql_result($req3,0,pseudo)).'<br />
			Capital: <input type="text" name="budget" size="10" value="'.mysql_result($req,0,budget).'" /><br />
			Présent au: <input type="text" name="num" size="3" value="'.mysql_result($req,0,num).'" /> 
			<input type="text" name="rue" size="10" value="'.mysql_result($req,0,rue).'" /> - <a href="engine=go.php?num='.mysql_result($req,0,num).'&rue='.mysql_result($req,0,rue).'">S\'y rendre</a><br />
			Digicode: '.mysql_result($req2,0,code).'<br />
			<input type="submit" value="Appliquer les modifications" />
			</div></form>');
			}
		else
			{
			print('<br />Aucune entreprise ne s\'appelle ainsi.');
			}
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
