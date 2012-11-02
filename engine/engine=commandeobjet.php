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
<div class="messagesvip">

<?php 

if(($_SESSION['statut']=="Administrateur") or ($_SESSION['statut']=="Développeur"))
	{
	$db = mysql_connect('localhost', 'CENSURE', 'CENSURE') or die("Erreur de connexion au serveur des messages.");
	mysql_select_db('CENSURE',$db) or die("Erreur de connexion a la base de donnees des messages.");

	if($_GET['suppr']==1)
		{
		print('<br />Êtes-vous sûr de vouloir supprimer l\'objet n°'.$_GET['id'].' ?<br /><br /><a href="engine=commandeobjet.php?suppr=2&id='.$_GET['id'].'">Oui</a> - <a href="engine=commande.php">Non</a>');
		}
	elseif($_GET['suppr']==2)
		{
		$id = $_GET['id'];
		$sql = 'DELETE FROM objets_tbl WHERE id='.$id;
		$req = mysql_query($sql);
		print('<br />Objet supprimé.');
		}
	elseif($_GET['modif']==1)
		{
		$sql1 = 'UPDATE objets_tbl SET image="'.$_POST['image'].'", url="'.$_POST['url'].'", type="'.$_POST['type'].'",  prod="'.$_POST['prod'].'",  puissance="'.$_POST['puissance'].'", ecart="'.$_POST['ecart'].'", distance="'.$_POST['distance'].'",  modes="'.$_POST['modes'].'",  description="'.stripslashes($_POST['description']).'",   historique="'.stripslashes($_POST['historique']).'", prix="'.$_POST['prix'].'" WHERE id= "'.$_POST['id'].'"' ;
		$req1 = mysql_query($sql1);
		if($_POST['prod']==0)
			{
			print('>>>'.$_POST['nom']);
			$sql1 = 'DELETE FROM stocks_tbl WHERE nom="'.$_POST['nom'].'" AND nombre="0"' ;
			$req1 = mysql_query($sql1);
			}
		print('<br />Modifications effectuées.');
		}
	else
		{
		if (isset($_GET['idobj'])) {
			$sql = 'SELECT * FROM objets_tbl WHERE id= '.$_GET['idobj'] ;
			$req = mysql_query($sql);
			$res = mysql_num_rows($req);
		} else {
			$sql = 'SELECT * FROM objets_tbl WHERE nom LIKE "%'.$_POST['objet'].'%" ORDER BY nom' ;
			$req = mysql_query($sql);
			$res = mysql_num_rows($req);
		}
		
		if ($res > 1) {
			for($i=0;$i<$res;$i++) {
				if (preg_match('/([^-]+)-[0-9A-F]+/',mysql_result($req,$i,nom)))
					continue;
				
				print("<a href=\"engine=commandeobjet.php?idobj=".mysql_result($req,$i,id)."\">".mysql_result($req,$i,nom)."</a><br />");
			}
		}
		elseif($res==1)
			{
			
			$sql2 = 'SELECT DISTINCT nature,bonus FROM recherche_effets_tbl WHERE ido= "'.mysql_result($req,0,id).'"' ;
			$req2 = mysql_query($sql2);
			$res2 = mysql_num_rows($req2);
			
			print('<form action="engine=commandeobjet.php?modif=1" method="post">
			ID: <input type="hidden" name="id" size="10" value="'.mysql_result($req,0,id).'" />'.mysql_result($req,0,id).' (<a href="engine=commandeobjet.php?suppr=1&id='.mysql_result($req,0,id).'">Supprimer l\'objet</a>)<br />
			<input type="hidden" name="nom" size="10" value="'.mysql_result($req,0,nom).'" />
			Image: <input type="text" name="image" size="10" value="'.mysql_result($req,0,image).'" /><br />
			'.((mysql_result($req,0,url) != "")?'Url: <input type="text" name="url" size="10" value="'.mysql_result($req,0,url).'" /><br />':'').'
			Type: <input type="text" name="type" size="10" value="'.mysql_result($req,0,type).'" /><br />
			Production: <input type="radio" name="prod" value="1" '.((mysql_result($req,0,prod) == "1")?'checked="checked" ':'').'/> Oui / <input type="radio" name="prod" value="0" '.((mysql_result($req,0,prod) == "0")?'checked="checked" ':'').'/> Non<br /> 
			Puissance: <input type="text" name="puissance" size="10" value="'.mysql_result($req,0,puissance).'" /><br />
			Ecart: <input type="text" name="ecart" size="10" value="'.mysql_result($req,0,ecart).'" /><br />
			Distance: <input type="text" name="distance" size="10" value="'.mysql_result($req,0,distance).'" /><br />
			Modes: <input type="text" name="modes" size="10" value="'.mysql_result($req,0,modes).'" /><br />');
			if($res2 != 0) print('Améliorations :<br />');
			for($i=0;$i<$res2;$i++) print(mysql_result($req2,$i,nature).((mysql_result($req2,$i,bonus) != 0)?' +'.mysql_result($req2,$i,bonus):'').'<br />');
			print('Description: <textarea name="description" rows="6" cols="25">'.mysql_result($req,0,description).'</textarea><br />
			Historique: <textarea name="historique" rows="6" cols="25">'.mysql_result($req,0,historique).'</textarea><br />
			Infos: <textarea name="infos" rows="8" cols="50">'.mysql_result($req,0,infos).'</textarea><br />
			Prix: <input type="text" name="prix" size="10" value="'.mysql_result($req,0,prix).'" /><br />
			<input type="submit" value="Appliquer les modifications" />
			</form>');
			}
		else
			{
			print('<br />Aucun objet ne s\'appelle ainsi.');
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

</div>
</p>
</div>
<?php if($chat=="oui") 	{ if ((date("H")>=21) || (date("H")<=7)) { include("inc_bas_n_c.php"); } else { include("inc_bas_j_c.php"); } } else {	if ((date("H")>=21) || (date("H")<=7)) { include("inc_bas_n.php"); } else { include("inc_bas_j.php"); }	} ?>
<?php include("inc_bas_de_page.php"); ?>
