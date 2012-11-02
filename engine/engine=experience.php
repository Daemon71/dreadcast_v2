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
			Expérience
		</div>
		<b class="module4ie"><a href="engine.php" class="module4"><img src="im_objets/icon_retour.gif" alt="Retour" />Retour</a></b>
		</p>
	</div>
</div>
<div id="centre_exp">
	<?php
	
	if($_GET['partie']=="" || $_GET['partie']=="exp")
		{
		$db = mysql_connect('localhost', 'CENSURE', 'CENSURE') or die("Erreur de connexion au serveur des messages.");
		mysql_select_db('CENSURE',$db) or die("Erreur de connexion a la base de donnees des messages.");
						
		$mon_niveau = niveau($_SESSION['pseudo']);
		
		print('<span id="lien1" class="selected">Expérience</span>
		<a href="engine=experience.php?partie=talents" id="lien2">Talents</a>
		<div id="principal">
			<div id="niveau">
				<h3>Niveau '.(($mon_niveau < 10)?'0'.$mon_niveau:$mon_niveau).'</h3>
				<h2>Niv. '.(($mon_niveau < 10)?'0'.$mon_niveau:$mon_niveau).'</h2>
			</div>
			<div id="info_exp">
				Expérience totale
				<div class="type1"><strong>'.$_SESSION['exp'].'</strong>xp</div>
				Niveau suivant
				<div class="type1"><strong>'.(($_SESSION['exp']>=1000000000)?'- ':$_SESSION['expmax']).'</strong>xp</div>
				Restant
				<div class="type1"><strong>'.(($_SESSION['exp']>=1000000000)?'- ':($_SESSION['expmax']-$_SESSION['exp'])).'</strong>xp</div>
			</div>
			<div id="info_enregistreur">
				<div class="type1">Statistiques d\'expérience</div>
				<div class="type2">
					<table>
						');
						
						$sql = 'SELECT donnee,valeur FROM enregistreur_tbl WHERE pseudo="'.$_SESSION['pseudo'].'" AND donnee != "combat" AND donnee != "tir" AND donnee != "resistance" AND donnee != "observation" AND donnee != "discretion" AND donnee != "vol" AND donnee != "medecine" AND donnee != "maintenance" AND donnee != "mecanique" AND donnee != "gestion" AND donnee != "economie" AND donnee != "service" AND donnee != "informatique" AND donnee != "recherche" AND donnee != "fidelite"' ;
						$req = mysql_query($sql);
						$res = mysql_num_rows($req);
						
						if($res == 0)
							{
							print('<tr>
								<td>Aucune<td>
								<td class="valeur">-<td>
							</tr>');
							}
						for($i=0;$i<$res;$i++)
							{
							$donnee = mysql_result($req,$i,donnee);
							
							if($donnee=="acc_meurtre_donne") $donnee = "Meurtres commis";
							elseif($donnee=="acc_heures_supp") $donnee = "Heures supp' effectuées";
							elseif($donnee=="acc_travail_oi") $donnee = "Jours de travail en O.I.";
							elseif($donnee=="acc_vols_donnes") $donnee = "Vols réussis";
							elseif($donnee == "acc_alcool_achetes") $donnee = "Boissons alcoolisées bues";
							elseif($donnee == "acc_boissons_achetes") $donnee = "Boissons non-alcoolisées bues";
							
							if(ereg("acc_",$donnee)) continue;
							
							$donnee = ($donnee == "acheve")?"Innocents tués":(
									  ($donnee == "logement")?"Logements achetés":(
									  ($donnee == "justice")?"Justice rendue":(
									  ($donnee == "credivore")?"Crédits dévorés":(
									  ($donnee == "survie")?"Jours de survie":(
									  ($donnee == "travail")?"Heures de travail":(
									  ($donnee == "arrestation")?"Personnes arrêtées":(
									  ($donnee == "arrêté")?"Séjours en prison":(
									  ($donnee == "emploi")?"Emplois exercés":(
									  ($donnee == "cristaux")?"Recherche de cristaux":(
									  ($donnee == "travail actif")?"Jours de travail actif":(
									  ($donnee == "semaines")?"Années à Dreadcast":(
									  ($donnee == "entreprise")?"Entreprises créées":(
									  ($donnee == "anniversaire2ans")?"Champagne débouché pour les 2 ans":(
							ucfirst($donnee)))))))))))))));
							print('<tr>
								<td>'.$donnee.'<td>
								<td class="valeur" style="font-weight:bold;'.(($donnee == "Innocents tués" || $donnee == "Séjours en prison")?'color:#d32929;':((ereg("Mort",$donnee) || $donnee == "Meurtres commis" || $donnee == "Boissons alcoolisées bues" || $donnee == "Boissons non-alcoolisées bues")?'':'color:#33FF33;')).'">'.floor(mysql_result($req,$i,valeur)).substr(strstr(mysql_result($req,$i,valeur),"."),0,3).'<td>
							</tr>');
							}
						
						print('
					</table>
				</div>
			</div>
			'.((niveau($_SESSION['pseudo']) < strlen($_SESSION['exp']))?'<div id="niveau_suivant">
				<a href="engine=lvlup.php"></a>
			</div>':'').'
		</div>');
		
		mysql_close($db);
		}
	elseif($_GET['partie']=="talents")
		{
		print('<a href="engine=experience.php?partie=exp" id="lien1">Expérience</a>
		<span id="lien2" class="selected">Talents</span>
		<div id="principal">
			<div id="comps">');
				$db = mysql_connect('localhost', 'CENSURE', 'CENSURE') or die("Erreur de connexion au serveur des messages.");
				mysql_select_db('CENSURE',$db) or die("Erreur de connexion a la base de donnees des messages.");
		
				$sql = 'SELECT titre FROM titres_tbl WHERE pseudo="'.$_SESSION['pseudo'].'" AND type LIKE "Niveau" ORDER BY id' ;
				$req = mysql_query($sql);
				$res = mysql_num_rows($req);
				
				$num_comp = ($_GET['talent'] != "")?$_GET['talent']:0;
				
				if($res == 0) print('<div class="comp1">Aucun</div>');
				
				for($i=0;$i<$res;$i++)
					{
					if($i == $num_comp)
						{
						print('<div class="comp1">'.ucfirst(mysql_result($req,$i,titre)).'</div>');
						$comp_en_cours = mysql_result($req,$i,titre);
						}
					else print('<a href="engine=experience.php?partie=talents&talent='.$i.'" class="comp2">'.mysql_result($req,$i,titre).'</a>');
					}
			
			print('</div>
			<div id="info_comp">');
			
			if($comp_en_cours == "")
				{
				print('<div style="position:relative;top:80px;text-align:center;">Vous n\'avez pour l\'instant aucun talent.</div>');
				}
			else
				{
				//print('<div id="image"></div>');
				print('<h3 style="position:relative;margin-left:40px;left:0;"><div id="cartouche"><div class="gcartouche"></div>'.$comp_en_cours.'<div class="dcartouche"></div></div></h3>');
				print(description_talent($comp_en_cours));
				}
			
			mysql_close($db);
			
			print('</div>
		</div>');
		}
	
	?>
</div>

<?php if($chat=="oui") 	{ if ((date("H")>=21) || (date("H")<=7)) { include("inc_bas_n_c.php"); } else { include("inc_bas_j_c.php"); } } else {	if ((date("H")>=21) || (date("H")<=7)) { include("inc_bas_n.php"); } else { include("inc_bas_j.php"); }	} ?>
<?php include("inc_bas_de_page.php"); ?>
