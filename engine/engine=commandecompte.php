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
			Contr&ocirc;le d'un compte en banque
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
		print('<br />Êtes-vous sûr de vouloir supprimer le compte en banque n°'.$_GET['id'].' ?<br /><br /><a href="engine=commandecompte.php?suppr=2&id='.$_GET['id'].'">Oui</a> - <a href="engine=commande.php">Non</a>');
		}
	elseif($_GET['suppr']==2)
		{
		$id = $_GET['id'];
		$sql = "SELECT code FROM comptes_tbl WHERE id = $id" ;
		$req = mysql_query($sql);
		$res = mysql_num_rows($req);
		if ($res) {
			$compte = mysql_result($req,0,code);
			$time = time();
			$sql = "INSERT INTO comptes_acces_tbl VALUES (NULL, 'Dreadcast', '$compte', '$time', 'Fermeture', '')";
			mysql_query($sql);
		}
		$sql1 = "DELETE FROM comptes_tbl WHERE id = $id" ;
		$req1 = mysql_query($sql1);
		print('<br />Compte supprimé.');
		}
	elseif($_GET['modif']==1)
		{
		$sql1 = 'UPDATE comptes_tbl SET credits="'.$_POST['credits'].'" , code="'.$_POST['code'].'", emp1="'.$_POST['emp1'].'", emp2="'.$_POST['emp2'].'", emp3="'.$_POST['emp3'].'", emp4="'.$_POST['emp4'].'", emp5="'.$_POST['emp5'].'", emp6="'.$_POST['emp6'].'", emp7="'.$_POST['emp7'].'", emp8="'.$_POST['emp8'].'", mdp="'.$_POST['mdp'].'" WHERE id = '.$_POST['id'] ;
		$req1 = mysql_query($sql1);
		print('<br />Modifications effectuées.');
		}
	else
		{
		$sql = 'SELECT * FROM comptes_tbl WHERE code LIKE "'.trim($_POST['compte']).'"' ;
		$req = mysql_query($sql);
		$res = mysql_num_rows($req);
		if($res>0)
			{
			/*$entreprise = mysql_result($req,0,nom);
			$sql2 = 'SELECT code FROM lieu_tbl WHERE num= "'.mysql_result($req,0,num).'" AND rue= "'.mysql_result($req,0,rue).'"' ;
			$req2 = mysql_query($sql2);
			$sql3 = 'SELECT poste FROM e_'.str_replace(" ","_",$entreprise).'_tbl WHERE type="chef"' ;
			$req3 = mysql_query($sql3);
			$sql3 = 'SELECT pseudo FROM principal_tbl WHERE type="'.mysql_result($req3,0,poste).'" AND entreprise="'.$entreprise.'"' ;
			$req3 = mysql_query($sql3);
			$res3 = mysql_num_rows($req3);*/
			
			print('<form action="engine=commandecompte.php?modif=1" method="post"><div class="ebarreliste">
			ID: <input type="hidden" name="id" size="10" value="'.mysql_result($req,0,id).'" />'.mysql_result($req,0,id).' (<a href="engine=commandecompte.php?suppr=1&id='.mysql_result($req,0,id).'">Supprimer le compte</a>)<br />
			Code: <input type="text" name="code" size="10" value="'.mysql_result($req,0,code).'" /><br />
			Mdp: <input type="text" name="mdp" size="10" value="'.mysql_result($req,0,mdp).'" /><br />
			Crédits: <input type="text" name="credits" size="10" value="'.mysql_result($req,0,credits).'" /><br />
			Objet1: <input type="text" name="emp1" size="20" value="'.mysql_result($req,0,emp1).'" /><br />
			Objet2: <input type="text" name="emp2" size="20" value="'.mysql_result($req,0,emp2).'" /><br />
			Objet3: <input type="text" name="emp3" size="20" value="'.mysql_result($req,0,emp3).'" /><br />
			Objet4: <input type="text" name="emp4" size="20" value="'.mysql_result($req,0,emp4).'" /><br />
			Objet5: <input type="text" name="emp5" size="20" value="'.mysql_result($req,0,emp5).'" /><br />
			Objet6: <input type="text" name="emp6" size="20" value="'.mysql_result($req,0,emp6).'" /><br />
			Objet7: <input type="text" name="emp7" size="20" value="'.mysql_result($req,0,emp7).'" /><br />
			Objet8: <input type="text" name="emp8" size="20" value="'.mysql_result($req,0,emp8).'" /><br />
			<input type="submit" value="Appliquer les modifications" />
			</div></form>');
			}
		else
			{
			print('<br />Ce compte en banque n\'existe pas.');
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
