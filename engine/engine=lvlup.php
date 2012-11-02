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
			Niveau supérieur
		</div>
		<b class="module4ie"><a href="engine=experience.php" class="module4"><img src="im_objets/icon_retour.gif" alt="Retour" />Retour</a></b>
		</p>
	</div>
</div>
<div id="centre_exp">
	<?php
	
	$db = mysql_connect('localhost', 'CENSURE', 'CENSURE') or die("Erreur de connexion au serveur des messages.");
	mysql_select_db('CENSURE',$db) or die("Erreur de connexion a la base de donnees des messages.");
	
	$mon_niveau = niveau($_SESSION['pseudo']);
	$futur_niveau = $mon_niveau+1;
	
	if($_GET['validation']=="1" && $_GET['talent'] != "")
		{
		
		// Si j'ai plus de talents que normalement
		if($mon_niveau >= strlen($_SESSION['exp']))
			{
			print('<meta http-equiv="refresh" content="0 ; url=http://v2.dreadcast.net/ingame/engine.php"> ');
			exit();
			}
		
		$talent_choisi = talent($_GET['talent'],$futur_niveau);
		
		// Si le talent demandé n'existe pas
		if($talent_choisi == 'Erreur')
			{
			print('<meta http-equiv="refresh" content="0 ; url=http://v2.dreadcast.net/ingame/engine.php"> ');
			exit();
			}
		
		$sql = 'SELECT titre FROM titres_tbl WHERE pseudo="'.$_SESSION['pseudo'].'" AND type LIKE "Niveau" ORDER BY id' ;
		$req = mysql_query($sql);
		$res = mysql_num_rows($req);
		
		$mes_talents = '-';
		for($i=0;$i<$res;$i++) $mes_talents .= mysql_result($req,$i,titre).'-';
		
		// Si j'ai déjà le talent
		if(ereg('-'.$talent_choisi.'-',$mes_talents))
			{
			print('<meta http-equiv="refresh" content="0 ; url=http://v2.dreadcast.net/ingame/engine.php"> ');
			exit();
			}
		
		print('<div id="principal">
			<div id="info_comp">
				<div style="position:relative;top:80px;text-align:center;">Augmentation du niveau en cours...</div>
			</div>
		</div>');
		
		$sql = 'INSERT INTO titres_tbl(pseudo,titre,type) VALUES("'.$_SESSION['pseudo'].'","'.$talent_choisi.'","Niveau")' ;
		mysql_query($sql);
		
		$sante_max = $_SESSION['santemax'];
		$fatigue_max = $_SESSION['fatiguemax'];

		if(ereg("Peau d'acier",$mes_talents) || $talent_choisi == "Peau d'acier") $pourcentage_sante = 10*$sante_max/100;
		else $pourcentage_sante = 5*$sante_max/100;
		if(ereg("Inépuisable",$mes_talents) || $talent_choisi == "Inépuisable") $pourcentage_fatigue = 10*$fatigue_max/100;
		else $pourcentage_fatigue = 5*$fatigue_max/100;

		$_SESSION['santemax'] = round($pourcentage_sante+$sante_max);
		$_SESSION['fatiguemax'] = round($pourcentage_fatigue+$fatigue_max);
		
		$autre = '';

		if($talent_choisi == talent(1,6)) // Si talent 1 série 2
			{
			$exp_futur_niveau = $_SESSION['expmax'];
			$autre = ', total="'.$exp_futur_niveau.'"';
			}
		elseif($talent_choisi == talent(3,6)) // Si talent 3 série 2
			{
			$sql2 = 'SELECT informatique_max FROM principal_tbl WHERE pseudo="'.$_SESSION['pseudo'].'"' ;
			$req2 = mysql_query($sql2);
			$autre = ', informatique_max = informatique_max + 50';
			}
		elseif($talent_choisi == talent(4,6)) // Si talent 4 série 2
			{
			$sql2 = 'SELECT medecine_max FROM principal_tbl WHERE pseudo="'.$_SESSION['pseudo'].'"' ;
			$req2 = mysql_query($sql2);
			$autre = ', medecine_max = medecine_max + 50';
			}
		elseif($talent_choisi == talent(5,6)) // Si talent 5 série 2
			{
			$sql2 = 'SELECT resistance_max FROM principal_tbl WHERE pseudo="'.$_SESSION['pseudo'].'"' ;
			$req2 = mysql_query($sql2);
			$autre = ', resistance_max = resistance_max + 50';
			}
		elseif($talent_choisi == talent(6,6)) // Si talent 6 série 2
			{
			$sql2 = 'SELECT combat_max FROM principal_tbl WHERE pseudo="'.$_SESSION['pseudo'].'"' ;
			$req2 = mysql_query($sql2);
			$autre = ', combat_max = combat_max + 50';
			}
		elseif($talent_choisi == talent(7,6)) // Si talent 7 série 2
			{
			$sql2 = 'SELECT discretion_max FROM principal_tbl WHERE pseudo="'.$_SESSION['pseudo'].'"' ;
			$req2 = mysql_query($sql2);
			$autre = ', discretion_max = discretion_max + 50';
			}
		elseif($talent_choisi == talent(8,6)) // Si talent 8 série 2
			{
			$sql2 = 'SELECT vol_max FROM principal_tbl WHERE pseudo="'.$_SESSION['pseudo'].'"' ;
			$req2 = mysql_query($sql2);
			$autre = ', vol_max = vol_max + 50';
			}
		elseif($talent_choisi == talent(1,10)) // Si talent 1 série 3
			{
			$sql2 = 'SELECT * FROM principal_tbl WHERE pseudo="'.$_SESSION['pseudo'].'"' ;
			$req2 = mysql_query($sql2);
			$autre = ', combat_max = combat_max + 25';
			$autre .= ', observation_max = observation_max + 25';
			$autre .= ', gestion_max = gestion_max + 25';
			$autre .= ', maintenance_max = maintenance_max + 25';
			$autre .= ', mecanique_max = mecanique_max + 25';
			$autre .= ', service_max = service_max + 25';
			$autre .= ', discretion_max = discretion_max + 25';
			$autre .= ', economie_max = economie_max + 25';
			$autre .= ', resistance_max = resistance_max + 25';
			$autre .= ', recherche_max = recherche_max + 25';
			$autre .= ', tir_max = tir_max + 25';
			$autre .= ', vol_max = vol_max + 25';
			$autre .= ', medecine_max = medecine_max + 25';
			$autre .= ', informatique_max = informatique_max + 25';
			$autre .= ', fidelite_max = fidelite_max + 25';
			$_SESSION['santemax'] += 50;
			$_SESSION['fatiguemax'] += 50;
			}
       elseif($talent_choisi == talent(2,10)) // Si talent 2 série 3
			{
			$sql2 = 'SELECT combat_max,resistance_max FROM principal_tbl WHERE pseudo="'.$_SESSION['pseudo'].'"' ;
			$req2 = mysql_query($sql2);
			$autre = ', combat_max = combat_max + 50';
			$autre .= ', resistance_max = resistance_max + 50';
			$_SESSION['santemax'] += 100;
			$_SESSION['fatiguemax'] += 100;
			}
		else $autre = '';
		
		$sql = 'UPDATE principal_tbl SET sante="'.$_SESSION['santemax'].'", sante_max="'.$_SESSION['santemax'].'", fatigue="'.$_SESSION['fatiguemax'].'", fatigue_max="'.$_SESSION['fatiguemax'].'" '.$autre.' WHERE pseudo = "'.$_SESSION['pseudo'].'"';
		mysql_query($sql);
		
		print('<meta http-equiv="refresh" content="0 ; url=engine=lvlup.php?validation=2&talent='.$_GET['talent'].'"> ');
		exit();
		
		}
	elseif($_GET['validation']=="2" && $_GET['talent'] != "")
		{
		
		$talent_choisi = talent($_GET['talent'],$mon_niveau);
		
		print('<a href="engine=experience.php?partie=exp" class="selected" id="lien1">Expérience</span>
		<a href="engine=experience.php?partie=talents" class="selected" id="lien2">Talents</a>
		<div id="principal">
			<div id="info_comp">
				<div style="z-index:20;position:relative;top:10px;text-align:center;">
					<h2 style="margin-bottom:10px;">Niveau amélioré !</h2>
					Vous avez choisi le talent <strong style="font-size:13px;">'.$talent_choisi.'</strong>.<br /><br />
					Vous sentez une nouvelle force en vous. Votre santé et votre forme se sont améliorées.<br /><br />
					Santé maximum : <strong style="font-size:13px;">'.$_SESSION['santemax'].'</strong><br />
					Forme maximum : <strong style="font-size:13px;">'.$_SESSION['fatiguemax'].'</strong><br /><br />
					<a href="engine=experience.php" style="color:#fff;border-bottom:1px solid #fff;font-weight:bold;">Retour</a>
				</div>
			</div>
			<div id="comps">');
			
			$sql = 'SELECT titre FROM titres_tbl WHERE pseudo="'.$_SESSION['pseudo'].'" AND type LIKE "Niveau" ORDER BY id' ;
			$req = mysql_query($sql);
			$res = mysql_num_rows($req);
			
			for($i=0;$i<$res;$i++)
				{
				print('<a href="engine=experience.php?partie=talents&talent='.$i.'" class="comp2">'.mysql_result($req,$i,titre).'</a>');
				}
			
		print('</div>
		</div>');
		
		}
	else
		{
		
		if($mon_niveau >= strlen($_SESSION['exp']))
			{
			print('<meta http-equiv="refresh" content="0 ; url=http://v2.dreadcast.net/ingame/engine.php"> ');
			exit();
			}
			
	print('<a href="engine=experience.php?partie=exp" class="selected" id="lien1">Expérience</span>
	<a href="engine=experience.php?partie=talents" class="selected" id="lien2">Talents</a>
	<div id="principal">
		<div id="comps">');
			
			$comp_en_cours = $_GET['talent'];
			
			$sql = 'SELECT titre FROM titres_tbl WHERE pseudo="'.$_SESSION['pseudo'].'" AND type LIKE "Niveau"' ;
			$req = mysql_query($sql);
			$res = mysql_num_rows($req);
			
			$mes_talents = '-';
			for($i=0;$i<$res;$i++) $mes_talents .= mysql_result($req,$i,titre).'-';
			
			if(!ereg('-'.talent(1,$futur_niveau).'-',$mes_talents) && $comp_en_cours == 1) print('<div class="comp1">'.talent(1,$futur_niveau).'</div>');
			elseif(!ereg('-'.talent(1,$futur_niveau).'-',$mes_talents)) print('<a href="engine=lvlup.php?talent=1" class="comp2">'.talent(1,$futur_niveau).'</a>');
			else print('<div class="comp3">'.talent(1,$futur_niveau).'</div>');
			
			if(!ereg('-'.talent(2,$futur_niveau).'-',$mes_talents) && $comp_en_cours == 2) print('<div class="comp1">'.talent(2,$futur_niveau).'</div>');
			elseif(!ereg('-'.talent(2,$futur_niveau).'-',$mes_talents)) print('<a href="engine=lvlup.php?talent=2" class="comp2">'.talent(2,$futur_niveau).'</a>');
			else print('<div class="comp3">'.talent(2,$futur_niveau).'</div>');
			
			if(!ereg('-'.talent(3,$futur_niveau).'-',$mes_talents) && $comp_en_cours == 3) print('<div class="comp1">'.talent(3,$futur_niveau).'</div>');
			elseif(!ereg('-'.talent(3,$futur_niveau).'-',$mes_talents)) print('<a href="engine=lvlup.php?talent=3" class="comp2">'.talent(3,$futur_niveau).'</a>');
			else print('<div class="comp3">'.talent(3,$futur_niveau).'</div>');
			
			if(!ereg('-'.talent(4,$futur_niveau).'-',$mes_talents) && $comp_en_cours == 4) print('<div class="comp1">'.talent(4,$futur_niveau).'</div>');
			elseif(!ereg('-'.talent(4,$futur_niveau).'-',$mes_talents)) print('<a href="engine=lvlup.php?talent=4" class="comp2">'.talent(4,$futur_niveau).'</a>');
			else print('<div class="comp3">'.talent(4,$futur_niveau).'</div>');
			
			if(niveau($_SESSION['pseudo']) != 9)
				{
				if(!ereg('-'.talent(5,$futur_niveau).'-',$mes_talents) && $comp_en_cours == 5) print('<div class="comp1">'.talent(5,$futur_niveau).'</div>');
				elseif(!ereg('-'.talent(5,$futur_niveau).'-',$mes_talents)) print('<a href="engine=lvlup.php?talent=5" class="comp2">'.talent(5,$futur_niveau).'</a>');
				else print('<div class="comp3">'.talent(5,$futur_niveau).'</div>');
				
				if(!ereg('-'.talent(6,$futur_niveau).'-',$mes_talents) && $comp_en_cours == 6) print('<div class="comp1">'.talent(6,$futur_niveau).'</div>');
				elseif(!ereg('-'.talent(6,$futur_niveau).'-',$mes_talents)) print('<a href="engine=lvlup.php?talent=6" class="comp2">'.talent(6,$futur_niveau).'</a>');
				else print('<div class="comp3">'.talent(6,$futur_niveau).'</div>');
				
				if(!ereg('-'.talent(7,$futur_niveau).'-',$mes_talents) && $comp_en_cours == 7) print('<div class="comp1">'.talent(7,$futur_niveau).'</div>');
				elseif(!ereg('-'.talent(7,$futur_niveau).'-',$mes_talents)) print('<a href="engine=lvlup.php?talent=7" class="comp2">'.talent(7,$futur_niveau).'</a>');
				else print('<div class="comp3">'.talent(7,$futur_niveau).'</div>');
				
				if(!ereg('-'.talent(8,$futur_niveau).'-',$mes_talents) && $comp_en_cours == 8) print('<div class="comp1">'.talent(8,$futur_niveau).'</div>');
				elseif(!ereg('-'.talent(8,$futur_niveau).'-',$mes_talents)) print('<a href="engine=lvlup.php?talent=8" class="comp2">'.talent(8,$futur_niveau).'</a>');
				else print('<div class="comp3">'.talent(8,$futur_niveau).'</div>');
				}
			
		print('</div>
		<div id="info_comp">');
		
		if($comp_en_cours == "")
			{
			print('<h4 style="position:relative;margin-top:10px;margin-bottom:15px;text-align:center;font-size:18px;line-height:20px;">Félicitation, vous êtes passé au niveau supérieur !</h4>
			<div style="padding:0 5px;">'.(($futur_niveau == 6 || $futur_niveau == 10)?'De plus, vous venez de <strong>dépasser un palier</strong> ! De nouveaux talents s\'offrent à vous.<br /><br />':'').'Vous allez maintenant devoir choisir le talent que vous souhaitez développer. Lorsque vous aurez validé votre nouveau niveau, le maximum de votre santé et de votre forme augmentera !</div>');
			}
		else
			{
			//print('<div id="image"></div>');
			print('<h3 style="position:relative;margin-left:40px;left:0;"><div id="cartouche"><div class="gcartouche"></div>'.talent($comp_en_cours,$futur_niveau).'<div class="dcartouche"></div></div></h3>');
			print(description_talent(talent($comp_en_cours,$futur_niveau)));
			print('<div style="text-align:center;margin-top:5px;"><a style="color:#fff;border-bottom:1px solid #fff;font-weight:bold;" href="engine=lvlup.php?validation=1&talent='.$comp_en_cours.'">Valider le niveau avec ce talent</a></div>');
			}
		
		mysql_close($db);
		
		print('</div>
	</div>');
		}
	
	?>
</div>

<?php if($chat=="oui") 	{ if ((date("H")>=21) || (date("H")<=7)) { include("inc_bas_n_c.php"); } else { include("inc_bas_j_c.php"); } } else {	if ((date("H")>=21) || (date("H")<=7)) { include("inc_bas_n.php"); } else { include("inc_bas_j.php"); }	} ?>
<?php include("inc_bas_de_page.php"); ?>
