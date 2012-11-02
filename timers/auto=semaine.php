#!/usr/bin/php
<?php 

include('CENSURE');

if(date('H')!="19" || date('i')>15)
	{
	exit();
	}

$db = mysql_connect('localhost', 'CENSURE', 'CENSURE') or die("Erreur de connexion au serveur.");
mysql_select_db('CENSURE',$db) or die("Erreur de connexion a la base de donnees.");

$sql = 'DELETE FROM partenaires_entrant_tbl' ;
$req = mysql_query($sql);
$sql = 'DELETE FROM partenaires_visites_tbl' ;
$req = mysql_query($sql);

$sql = 'SELECT id,credits,code FROM comptes_tbl' ;
$req = mysql_query($sql);
$res = mysql_num_rows($req);

for($i=0; $i != $res; $i++)
	{
	$id = mysql_result($req,$i,id);
	$interet = floor( 0.02 * mysql_result($req,$i,credits) );
	$credits = mysql_result($req,$i,credits) + $interet;
	$sql1 = 'UPDATE comptes_tbl SET credits= "'.$credits.'" WHERE id = "'.$id.'"' ;
	$req1 = mysql_query($sql1);
	if($interet>0){$sqlspe = 'INSERT INTO transferts_tbl(donneur,receveur,time,operation,valeur) VALUES("Dreadcast","CB '.mysql_result($req,$i,code).'","'.time().'","Interet compte","'.$interet.'")';
	$reqspe = mysql_query($sqlspe);}
	}


// ARGENT DOI
$sql = 'SELECT budget FROM entreprises_tbl WHERE nom= "DOI"' ;
$req = mysql_query($sql);
$budget = mysql_result($req,0,budget) + 15000;
$sql = 'UPDATE entreprises_tbl SET budget= "'.$budget.'" WHERE nom= "DOI"' ;
$req = mysql_query($sql);
$sql = 'INSERT INTO transferts_tbl(donneur,receveur,time,operation,valeur) VALUES("Dreadcast","Organisations Imperiales","'.time().'","Don hebdomadaire","15000")';
$req = mysql_query($sql);

// ARGENT CONSEIL
$sql = 'SELECT budget FROM entreprises_tbl WHERE type = "conseil"' ;
$req = mysql_query($sql);
$budget = mysql_result($req,0,budget) + 15000;
$sql = 'UPDATE entreprises_tbl SET budget= "'.$budget.'" WHERE nom= "Conseil Imperial"' ;
$req = mysql_query($sql);
$sql = 'INSERT INTO transferts_tbl(donneur,receveur,time,operation,valeur) VALUES("Dreadcast","Conseil Imperial","'.time().'","Don hebdomadaire","15000")';
$req = mysql_query($sql);

// SUIVI FINANCIER
$sql = 'SELECT * FROM finance_tbl' ;
$req = mysql_query($sql);
$res = mysql_num_rows($req);

$police = 0;
$proprete = 0;
$prison = 0;
$di2rco = 0;
$cipe = 0;
$cie = 0;
$chambre = 0;
$dcn = 0;
for( $i=0 ; $i != $res ; $i++ )
	{
	$police = $police + mysql_result($req,$i,police);
	$proprete = $proprete + mysql_result($req,$i,proprete);
	$prison = $prison + mysql_result($req,$i,prison);
	$di2rco = $di2rco + mysql_result($req,$i,di2rco);
	$cipe = $cipe + mysql_result($req,$i,cipe);
	$cie = $cie + mysql_result($req,$i,cie);
	$chambre = $chambre + mysql_result($req,$i,chambre);
	$dcn= $dcn + mysql_result($req,$i,dcn);
	}
if($res!=0)
	{
	$police = ceil($police / $res);
	$proprete = ceil($proprete / $res);
	$prison = ceil($prison / $res);
	$di2rco = ceil($di2rco / $res);
	$cipe = ceil($cipe / $res);
	$cie = ceil($cie / $res);
	$chambre = ceil($chambre / $res);
	$dcn = ceil($dcn / $res);
	}

$sql = 'SELECT budget FROM entreprises_tbl WHERE nom= "Police"' ;
$req = mysql_query($sql);
$budget = mysql_result($req,0,budget) + $police;
$sql = 'UPDATE entreprises_tbl SET budget= "'.$budget.'" WHERE nom= "Police"' ;
$req = mysql_query($sql);
$sql = 'SELECT budget FROM entreprises_tbl WHERE nom= "Prison"' ;
$req = mysql_query($sql);
$budget = mysql_result($req,0,budget) + $prison;
$sql = 'UPDATE entreprises_tbl SET budget= "'.$budget.'" WHERE nom= "Prison"' ;
$req = mysql_query($sql);
$sql = 'SELECT budget FROM entreprises_tbl WHERE nom= "Chambre des lois"' ;
$req = mysql_query($sql);
$budget = mysql_result($req,0,budget) + $chambre;
$sql = 'UPDATE entreprises_tbl SET budget= "'.$budget.'" WHERE nom= "Chambre des lois"' ;
$req = mysql_query($sql);
$sql = 'SELECT budget FROM entreprises_tbl WHERE nom= "DI2RCO"' ;
$req = mysql_query($sql);
$budget = mysql_result($req,0,budget) + $di2rco;
$sql = 'UPDATE entreprises_tbl SET budget= "'.$budget.'" WHERE nom= "DI2RCO"' ;
$req = mysql_query($sql);
$sql = 'SELECT budget FROM entreprises_tbl WHERE nom= "CIE"' ;
$req = mysql_query($sql);
$budget = mysql_result($req,0,budget) + $cie;
$sql = 'UPDATE entreprises_tbl SET budget= "'.$budget.'" WHERE nom= "CIE"' ;
$req = mysql_query($sql);
$sql = 'SELECT budget FROM entreprises_tbl WHERE nom= "CIPE"' ;
$req = mysql_query($sql);
$budget = mysql_result($req,0,budget) + $cipe;
$sql = 'UPDATE entreprises_tbl SET budget= "'.$budget.'" WHERE nom= "CIPE"' ;
$req = mysql_query($sql);
$sql = 'SELECT budget FROM entreprises_tbl WHERE nom= "Services techniques de la ville"' ;
$req = mysql_query($sql);
$budget = mysql_result($req,0,budget) + $proprete;
$sql = 'UPDATE entreprises_tbl SET budget= "'.$budget.'" WHERE nom= "Services techniques de la ville"' ;
$req = mysql_query($sql);
$sql = 'SELECT budget FROM entreprises_tbl WHERE nom= "DC Network"' ;
$req = mysql_query($sql);
$budget = mysql_result($req,0,budget) + $dcn;
$sql = 'UPDATE entreprises_tbl SET budget= "'.$budget.'" WHERE nom= "DC Network"' ;
$req = mysql_query($sql);
$sql = 'DELETE FROM finance_tbl' ;
$req = mysql_query($sql);

$sql = 'SELECT * FROM financepridem_tbl' ;
$req = mysql_query($sql);
$res = mysql_num_rows($req);

for( $i=0 ; $i != $res ; $i++ )
	{
	$PDG = mysql_result($req,$i,PDG);
	$entreprise = mysql_result($req,$i,entreprise);
	
	$sql1 = 'SELECT id FROM entreprises_tbl WHERE nom LIKE "'.$entreprise.'"' ;
	$req1 = mysql_query($sql1);
	$res1 = mysql_num_rows($req1);
	if($res1 != 0)
		{
		$sql1 = 'SELECT vote FROM financepri_tbl WHERE entreprise= "'.$entreprise.'"' ;
		$req1 = mysql_query($sql1);
		$res1 = mysql_num_rows($req1);
		$vote = 0;
		for( $f=0 ; $f != $res1 ; $f++ )
			{
			$vote = $vote + mysql_result($req1,$f,vote);
			}
		if($res1!=0)
			{
			$vote = ceil($vote / $res1);
			$sql2 = 'SELECT budget FROM entreprises_tbl WHERE nom= "'.$entreprise.'"' ;
			$req2 = mysql_query($sql2);
			$budget = mysql_result($req2,0,budget) + $vote;
			$sql2 = 'UPDATE entreprises_tbl SET budget= "'.$budget.'" WHERE nom= "'.$entreprise.'"' ;
			$req2 = mysql_query($sql2);
			$sql2 = 'INSERT INTO messages_tbl(id,auteur,cible,message,objet,moment,nouveau) VALUES("","Direction Impériale","'.$PDG.'","Vous avez obtenu une Subvention Impériale de '.$vote.' Crédits pour votre entreprise '.$entreprise.'.<br />Il y a eu un total de '.$res1.' votes.<br /><br />Vous pouvez dès maintenant demander une subvention pour la semaine prochaine.","Subvention","'.time().'","oui")' ;
			$req2 = mysql_query($sql2);
			}
		}
	}
$sql = 'DELETE FROM financepridem_tbl' ;
$req = mysql_query($sql);
$sql = 'DELETE FROM financepri_tbl' ;
$req = mysql_query($sql);

//$randcombat = rand(0,3);
//$sql = 'UPDATE principal_tbl SET combat=combat-combat*'.$randcombat.'/100 WHERE combat>40' ;
//$req = mysql_query($sql);
//$randobservation = rand(0,3);
//$sql = 'UPDATE principal_tbl SET observation=observation-observation*'.$randobservation.'/100 WHERE observation>40' ;
//$req = mysql_query($sql);
//$randgestion = rand(0,3);
//$sql = 'UPDATE principal_tbl SET gestion=gestion-gestion*'.$randgestion.'/100 WHERE gestion>40' ;
//$req = mysql_query($sql);
//$randmaintenance = rand(0,3);
//$sql = 'UPDATE principal_tbl SET maintenance=maintenance-maintenance*'.$randmaintenance.'/100 WHERE maintenance>40' ;
//$req = mysql_query($sql);
//$randmecanique = rand(0,3);
//$sql = 'UPDATE principal_tbl SET mecanique=mecanique-mecanique*'.$randmecanique.'/100 WHERE mecanique>40' ;
//$req = mysql_query($sql);
//$randservice = rand(0,3);
//$sql = 'UPDATE principal_tbl SET service=service-service*'.$randservice.'/100 WHERE service>40' ;
//$req = mysql_query($sql);
//$randdiscretion = rand(0,3);
//$sql = 'UPDATE principal_tbl SET discretion=discretion-discretion*'.$randdiscretion.'/100 WHERE discretion>40' ;
//$req = mysql_query($sql);
//$randeconomie = rand(0,3);
//$sql = 'UPDATE principal_tbl SET economie=economie-economie*'.$randeconomie.'/100 WHERE economie>40' ;
//$req = mysql_query($sql);
//$randresistance = rand(0,3);
//$sql = 'UPDATE principal_tbl SET resistance=resistance-resistance*'.$randresistance.'/100 WHERE resistance>40' ;
//$req = mysql_query($sql);
//$randrecherche = rand(0,3);
//$sql = 'UPDATE principal_tbl SET recherche=recherche-recherche*'.$randrecherche.'/100 WHERE recherche>40' ;
//$req = mysql_query($sql);
//$randtir = rand(0,3);
//$sql = 'UPDATE principal_tbl SET tir=tir-tir*'.$randtir.'/100 WHERE tir>40' ;
//$req = mysql_query($sql);
//$randvol = rand(0,3);
//$sql = 'UPDATE principal_tbl SET vol=vol-vol*'.$randvol.'/100 WHERE vol>40' ;
//$req = mysql_query($sql);
//$randmedecine = rand(0,3);
//$sql = 'UPDATE principal_tbl SET medecine=medecine-medecine*'.$randmedecine.'/100 WHERE medecine>40' ;
//$req = mysql_query($sql);
//$randinformatique = rand(0,3);
//$sql = 'UPDATE principal_tbl SET informatique=informatique-informatique*'.$randinformatique.'/100 WHERE informatique>40' ;
//$req = mysql_query($sql);
//$randfidelite = rand(0,3);
//$sql = 'UPDATE principal_tbl SET fidelite=fidelite-fidelite*'.$randfidelite.'/100 WHERE fidelite>40' ;
//$req = mysql_query($sql);

$sql = 'SELECT id,pseudo,age,creation FROM principal_tbl' ;
$req = mysql_query($sql);
$res = mysql_num_rows($req);

for($i=0; $i != $res; $i++)
	{
	$id = mysql_result($req,$i,id); 
	$age = mysql_result($req,$i,age) + 1; 
	$pseudo = mysql_result($req,$i,pseudo); 
	$creation = mysql_result($req,$i,creation) + 1;
	$sql1 = 'UPDATE principal_tbl SET  creation= "'.$creation.'" , age= "'.$age.'" WHERE id= "'.$id.'"' ;
	$req1 = mysql_query($sql1);
	$sqls = 'INSERT INTO messages_tbl(id,auteur,cible,message,objet,moment,nouveau) VALUES("","Dreadcast","'.$pseudo.'","'.$pseudo.' à maintenant '.$age.' ans.<br>S\'il devient trop vieux à votre goût, nous vous conseillons d\'acheter une Fiole de Jeunesse disponible en boutique spécialisée.<br />","Informations","'.time().'","oui")';
	$reqs = mysql_query($sqls);
	enregistre($pseudo,"semaines","+1");
	}

$sql1 = 'UPDATE donnees_tbl SET valeur="0" WHERE objet="argent imperium"' ;
$req1 = mysql_query($sql1);

echo 'OK';

mysql_close($db);
?>
