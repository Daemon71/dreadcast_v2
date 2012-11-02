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
			Nommer un politique
		</div>
		<b class="module4ie"><a href="engine=panneau.php" class="module4"><img src="im_objets/icon_retour.gif" alt="Retour" />Retour</a></b>
</p>
	</div>
</div>
<div id="centre">
<p>

<?php 

if($_SESSION['statut']=="Administrateur")
	{
	if($_POST['perso']!="")
		{
		$db = mysql_connect('localhost', 'CENSURE', 'CENSURE') or die("Erreur de connexion au serveur des messages.");
		mysql_select_db('CENSURE',$db) or die("Erreur de connexion a la base de donnees des messages.");
	
		$sql = 'SELECT id FROM principal_tbl WHERE pseudo= "'.$_POST['perso'].'"';
		$req = mysql_query($sql);
		$res = mysql_num_rows($req);
		if($res>0)
			{
			$sql = 'INSERT INTO messages_tbl(id,auteur,cible,message,objet,moment) VALUES("","Dreadcast","'.$_POST['perso'].'","Félicitations, vous venez d\'être nommé <strong>'.$_POST['poste'].'</strong> par l\'administration.<br />Vous avez désormais accès au panneau de contrôle de l\'Organisation.<br /><br />Le fonctionnement est simple: chaque semaine, la Direction des Organisations Impériales attribue un budget à chaque Organisation (à la votre, donc). Vous pouvez consulter où en sont les votes à n\'importe quel moment depuis votre interface.<br /><br />De plus, vous êtes désormais dans la haute administration. De ce fait un Laissez-passer Impérial vous à été remis. Ce dernier vous place hors d\'atteinte des services de Police traditionnels. Si vous avez un quelconque problème, il est maintenant du ressort de la DI2RCO de s\'occuper de vous. Le Directeur de ce département est en permanence à l\'écoute des Directeurs Impériaux et de leurs éventuels problèmes.<br /><br />Pour faciliter votre intégration parmis les DI (Directeurs Impériaux), il est conseillé de prendre contact avec vos collègues avant d\'entammer votre travail. L\'adminitration doit rester solidaire en toutes circonstances.<br /><br />En éspérant constater le bon fonctionnement de votre Organisation, nous vous souhaitons, monsieur, madame, une excellente journée.","Vous êtes élu !","'.time().'")' ;
			$req = mysql_query($sql);
			$sql = 'UPDATE principal_tbl SET type= "Aucun" , entreprise= "Aucune" , points= "0" WHERE type= "'.$_POST['poste'].'"';
			$res = mysql_query($sql);
			if($_POST['poste']=="Directeur des Organisations")
				{
				$orga = "DOI";
				}
			elseif($_POST['poste']=="Directeur du CIPE")
				{
				$orga = "CIPE";
				}
			elseif($_POST['poste']=="President")
				{
				$orga = "Conseil Imperial";
				}
			elseif($_POST['poste']=="Premier Consul")
				{
				$orga = "Chambre des Lois";
				}
			elseif($_POST['poste']=="Directeur du CIE")
				{
				$orga = "CIE";
				}
			elseif($_POST['poste']=="Directeur des Services")
				{
				$orga = "Services techniques de la ville";
				}
			elseif($_POST['poste']=="Directeur de la Prison")
				{
				$orga = "Prison";
				}
			elseif($_POST['poste']=="Chef de la Police")
				{
				$orga = "Police";
				}
			elseif($_POST['poste']=="Directeur du DI2RCO")
				{
				$orga = "DI2RCO";
				}
			elseif($_POST['poste']=="Directeur du DC Network")
				{
				$orga = "DC Network";
				}
			$sql = 'UPDATE principal_tbl SET type= "'.$_POST['poste'].'" , entreprise= "'.$orga.'" , points= "999" , case6= "Laissez-passer" WHERE pseudo= "'.$_POST['perso'].'"';
			$res = mysql_query($sql);
			if($_POST['poste'] == "President") // Changement de modération du forum CI
				{
				$sql = 'UPDATE wikast_forum_structure_tbl SET admin="'.$_POST['perso'].'" WHERE id=118';
				mysql_query($sql);
				}
			print('<br /><strong>Le citoyen '.$_POST['perso'].' vient d\'être nommé au poste de '.$_POST['poste'].'.</strong>');
			}
		else
			{
			print('<br /><strong>Personne ne s\'appelle '.$_POST['perso'].' !</strong>');
			}
		
		mysql_close($db);
		}
	else
		{
		print('<form method="post" action="#">
		<p align="center">
		<i>Nommer le personnage:</i> <input name="perso" type="text" size="15" /><br />
		<i>Au poste de: </i><select name="poste">
		<option value="President">Président du Conseil Impérial</option>
		<option value="Directeur des Organisations">Directeur des Organisations</option>
		<option value="Directeur du CIPE">Directeur du CIPE</option>
		<option value="Directeur du CIE">Directeur du CIE</option>
		<option value="Directeur des Services">Directeur des Services</option>
		<option value="Directeur de la Prison">Directeur de la Prison</option>
		<option value="Chef de la Police">Chef de la Police</option>
		<option value="Directeur du DI2RCO">Directeur du DI2RCO</option>
		<option value="Premier Consul">Premier Consul</option>
		<option value="Directeur du DC Network">Directeur du DC Network</option>
		</select><br />
		<input type="submit" value="Valider"></p></form>');
		}
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
