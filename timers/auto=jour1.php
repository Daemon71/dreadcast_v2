#!/usr/bin/php
<?php 

	if(date('H')==0 && (date('i')>=6 || date('i')<=14)){}
	else
	{
	exit();
	}
	
include('CENSURE');

$db = mysql_connect('localhost', 'CENSURE', 'CENSURE') or die("Erreur de connexion au serveur.");
mysql_select_db('CENSURE',$db) or die("Erreur de connexion a la base de donnees.");

$sql = 'SELECT benefices,nom FROM entreprises_tbl' ;
$req = mysql_query($sql);
$res = mysql_num_rows($req);

for($i=0; $i != $res; $i++)
	{
	$ntime = time() - 1000;
	$sql1 = 'INSERT INTO chiffre_affaire_tbl(id,entreprise,chiffre,datea) VALUES("","'.mysql_result($req,$i,nom).'","'.mysql_result($req,$i,benefices).'","'.$ntime.'")' ;
	$req1 = mysql_query($sql1);	
	}

// CRYO
$sql = 'UPDATE principal_tbl SET num=num+1 WHERE action= "Vacances"' ;
$req = mysql_query($sql);

// MORT
$sql = 'UPDATE principal_tbl SET num = num+1 WHERE action = "mort"' ;
mysql_query($sql);
$sql = 'SELECT valeur FROM config_tbl WHERE objet = "tpsuppr"' ;
$req = mysql_query($sql);
$tpsuppr = mysql_result($req,0,valeur);
$sql = 'SELECT pseudo FROM principal_tbl WHERE action = "mort" AND num > '.$tpsuppr ;
$req = mysql_query($sql);
$res = mysql_num_rows($req);
for($i=0; $i != $res; $i++)
	{
	$pseudo = mysql_result($req,$i,pseudo);
	$sql1 = 'SELECT credits FROM principal_tbl WHERE pseudo = "'.$pseudo.'" AND statut != "Administrateur" AND statut != "Compte VIP" AND statut != "Platinium" AND statut != "Gold"' ;
	$req1 = mysql_query($sql1);
	$res1 = mysql_num_rows($req1);
	if($res1 != 0)
		{
		ajouter_argent_imperium(mysql_result($req1,0,credits));
		supprimer_personnage($pseudo,'Mort depuis plus de '.$tpsuppr.' jours');
		}
	}

$sql = 'SELECT id,type,entreprise FROM principal_tbl WHERE entreprise != "Aucune"' ;
$req = mysql_query($sql);
$res = mysql_num_rows($req);

for($i=0; $i != $res; $i++)
	{
	$id = mysql_result($req,$i,id);
	$type = mysql_result($req,$i,type);
	$entreprise = mysql_result($req,$i,entreprise); 
	$sql1 = 'SELECT type FROM `e_'.str_replace(" ","_",''.$entreprise.'').'_tbl` WHERE poste= "'.$type.'"' ;
	$req1 = mysql_query($sql1);
	$res1 = mysql_num_rows($req1);
	if($res1>0)
		{
		$typer = mysql_result($req1,0,type);
		if(($typer=="directeur" || $typer=="chef") && ($entreprise!="Police") && ($entreprise!="DI2RCO"))
			{
			$sql1 = 'SELECT gestion,gestion_max FROM principal_tbl WHERE id= "'.$id.'"' ;
			$req1 = mysql_query($sql1);
			if(mysql_result($req1,0,gestion)<mysql_result($req1,0,gestion_max))
				{
				$nouveau = mysql_result($req1,0,gestion) + 1;
				if($nouveau>mysql_result($req1,0,gestion_max))
					{
					$nouveau = mysql_result($req1,0,gestion_max);
					}
				$sql1 = 'UPDATE principal_tbl SET gestion= "'.$nouveau.'" WHERE id= "'.$id.'"' ;
				$req1 = mysql_query($sql1);
				}
			}
		elseif(($typer=="directeur" || $typer=="chef") && ($entreprise=="Police" || $entreprise=="DI2RCO"))
			{
			$sql1 = 'SELECT observation,observation_max FROM principal_tbl WHERE id= "'.$id.'"' ;
			$req1 = mysql_query($sql1);
			if(mysql_result($req1,0,observation)<mysql_result($req1,0,observation_max))
				{
				$nouveau = mysql_result($req1,0,observation) + 1;
				if($nouveau>mysql_result($req1,0,observation_max)) $nouveau = mysql_result($req1,0,observation_max);
				$sql1 = 'UPDATE principal_tbl SET observation= "'.$nouveau.'" WHERE id= "'.$id.'"' ;
				$req1 = mysql_query($sql1);
				}
			}
		}
	}

// RECHERCHE POLICE
$sql1 = 'UPDATE principal_tbl SET Police = Police-15 WHERE Police > "0"' ;
mysql_query($sql1);
$sql1 = 'UPDATE principal_tbl SET Police = "0" WHERE Police < "0"' ;
mysql_query($sql1);

// ASSURANCES
$sql1 = 'UPDATE principal_tbl SET assurance = assurance-1 WHERE assurance > 0' ;
mysql_query($sql1);

// PUBLICITES
$sql1 = 'UPDATE pubs_tbl SET temps = temps-1' ;
mysql_query($sql1);
$sql1 = 'DELETE FROM pubs_tbl WHERE temps < "0"' ;
mysql_query($sql1);

// NB JOURS DE PRISON
$sql1 = 'UPDATE principal_tbl SET alim = alim-1 WHERE action = "prison"' ;
mysql_query($sql1);
$sql1 = 'UPDATE principal_tbl SET action = "aucune", alim = "7" WHERE action = "prison" AND alim <= "0"' ;
mysql_query($sql1);

// TRAVAIL
$sql = 'SELECT id,points,entreprise,type,salaire FROM principal_tbl WHERE nouveau= "oui" AND entreprise!= "Aucune"' ;
$req = mysql_query($sql);
$res = mysql_num_rows($req);
for($u=0; $u != $res; $u++)
	{
	$id = mysql_result($req,$u,id);
	$points = mysql_result($req,$u,points);
	$entreprise = mysql_result($req,$u,entreprise);
	$nom = $entreprise;
	$poste = mysql_result($req,$u,type);
	$salaire = mysql_result($req,$u,salaire);
	$sql1 = 'SELECT mintrav FROM `e_'.str_replace(" ","_",''.$entreprise.'').'_tbl` WHERE poste= "'.$poste.'"' ;
	$req1 = mysql_query($sql1);
	$res1 = mysql_num_rows($req1);
	if($res1>0)
		{
		$mintrav = mysql_result($req1,0,mintrav);
		if($points>=$mintrav)
			{
			$sql2 = 'UPDATE principal_tbl SET nouveau= "non" WHERE id= "'.$id.'"' ;
			$req2 = mysql_query($sql2);
			}
		elseif(($mintrav>0) && ($points>0))
			{
			$salaireheure = ceil($salaire / $mintrav);
			$nouveausalaire = $salaireheure * $points;
			$sql2 = 'SELECT id,budget FROM entreprises_tbl WHERE nom= "'.$entreprise.'"' ;
			$req2 = mysql_query($sql2);
			if(mysql_result($req2,0,budget)>$nouveausalaire)
				{
				$budget = mysql_result($req2,0,budget) - $nouveausalaire;
				$sql3 = 'UPDATE entreprises_tbl SET budget= "'.$budget.'" WHERE nom= "'.$entreprise.'"' ;
				$req3 = mysql_query($sql3);
				$sql3 = 'SELECT credits FROM principal_tbl WHERE id= "'.$id.'"' ;
				$req3 = mysql_query($sql3);
				$credits = mysql_result($req3,0,credits) + $nouveausalaire;
				$sql3 = 'UPDATE principal_tbl SET credits= "'.$credits.'" WHERE id= "'.$id.'"' ;
				$req3 = mysql_query($sql3);
				$sql3 = 'SELECT pseudo FROM principal_tbl WHERE id= "'.$id.'"' ;
				$req3 = mysql_query($sql3);
				$pseudo = mysql_result($req3,0,pseudo);
				$sql3 = 'INSERT INTO messages_tbl(id,auteur,cible,message,objet,moment,nouveau) VALUES("","'.$entreprise.'","'.$pseudo.'","Pour votre premier jour, vous avez travaillé '.$points.' heures et il vous est demandé de travailler '.$mintrav.' heures.<br>Vous touchez tout de même un salaire de '.$nouveausalaire.' Crédits.","Premier salaire","'.time().'","oui")';
				$req3 = mysql_query($sql3);
				}
			elseif($nom!="CIE" && $nom!="CIPE" && $nom!="Police" && $nom!="DI2RCO" && $nom!="Conseil Imperial" && $nom!="Prison" && $nom!="Services techniques de la ville" && $nom!="DOI" && $nom!="Chambre des Lois" && $nom!="DC Network")
				{
				supprimer_entreprise(mysql_result($req2,0,id));
				}
			elseif($nom!="Aucune")
				{
				$sqlv = 'SELECT id FROM principal_tbl WHERE entreprise="'.$nom.'"' ;
				$reqv = mysql_query($sqlv);
				$resv = mysql_num_rows($reqv);
				for($i=0; $i != $resv ; $i++)
					{
					$idv = mysql_result($reqv,$i,id);
					$sqls = 'SELECT pseudo FROM principal_tbl WHERE id="'.$idv.'"' ;
					$reqs = mysql_query($sqls);
					$nick = mysql_result($reqs,0,pseudo); 
					$sqls = 'INSERT INTO messages_tbl(id,auteur,cible,message,objet,moment,nouveau) VALUES("","'.$nom.'","'.$nick.'","L\'organisation à fait faillite. Vous êtes licencié.","Faillite !","'.time().'","oui")' ;
					$reqs = mysql_query($sqls);
					}
				$sqls = 'UPDATE principal_tbl SET type="Aucun" , entreprise="Aucune" , salaire="0" , difficulte="0" , points="0" WHERE entreprise="'.$nom.'"' ;
				$reqs = mysql_query($sqls);
				$sqls = 'DELETE FROM `e_'.str_replace(" ","_",''.$nom.'').'_tbl` WHERE type!= "chef"' ;
				$reqs = mysql_query($sqls);
				}
			$sql2 = 'UPDATE principal_tbl SET points= "0" WHERE id= "'.$id.'"' ;
			$req2 = mysql_query($sql2);
			}
		}
	}
	
			
$sql = 'SELECT id,points,entreprise,type,salaire FROM principal_tbl WHERE nouveau= "non" AND entreprise!= "Aucune"' ;
$req = mysql_query($sql);
$res = mysql_num_rows($req);
for($u=0; $u != $res; $u++)
	{
	$id = mysql_result($req,$u,id);
	$points = mysql_result($req,$u,points);
	$entreprise = mysql_result($req,$u,entreprise);
	$nom = $entreprise;
	$poste = mysql_result($req,$u,type);
	$salaire = mysql_result($req,$u,salaire);
	$sqli = 'SELECT id FROM entreprises_tbl WHERE nom= "'.$entreprise.'"' ;
	$reqi = mysql_query($sqli);
	$resi = mysql_num_rows($reqi);
	if($resi>0)
		{
		$sql1 = 'SELECT mintrav,bonus,nbreactuel,sinon FROM `e_'.str_replace(" ","_",''.$entreprise.'').'_tbl` WHERE poste= "'.$poste.'"' ;
		$req1 = mysql_query($sql1);
		$res1 = mysql_num_rows($req1);
		if($res1>0)
			{
			$bonuss = mysql_result($req1,0,bonus);
			$sinon = mysql_result($req1,0,sinon);
			$mintrav = mysql_result($req1,0,mintrav);
			$sql2 = 'SELECT id,budget FROM entreprises_tbl WHERE nom= "'.$entreprise.'"' ;
			$req2 = mysql_query($sql2);
			$budget = mysql_result($req2,0,budget);
			if($points>=$mintrav)
				{
				$sql3 = 'SELECT credits FROM principal_tbl WHERE id= "'.$id.'"' ;
				$req3 = mysql_query($sql3);
				$credits = mysql_result($req3,0,credits);
				$sql3 = 'SELECT pseudo,actif FROM principal_tbl WHERE id= "'.$id.'"' ;
				$req3 = mysql_query($sql3);
				$pseudo = mysql_result($req3,0,pseudo);
				$actif = mysql_result($req3,0,actif);
				if($budget>=$salaire)
					{
					$credits = $credits + $salaire;
					$budget = $budget - $salaire;
					$sql3 = 'UPDATE entreprises_tbl SET budget= "'.$budget.'" WHERE nom= "'.$nom.'"' ;
					$req3 = mysql_query($sql3);
					$sql3 = 'UPDATE principal_tbl SET credits= "'.$credits.'" WHERE id= "'.$id.'"' ;
					$req3 = mysql_query($sql3);
					if(($points!=999) && ($actif=="oui"))
						{
						$hs = ( $points - $mintrav ) * $bonuss;
						if($hs>0)
							{
							$sqls = 'INSERT INTO messages_tbl(id,auteur,cible,message,objet,moment,nouveau) VALUES("","'.$nom.'","'.$pseudo.'","Vous avez rempli votre contrat de travail aujourd`hui.<br><br>Vous avez travaillé '.$points.'heures et il vous est demandé de travailler '.$mintrav.'heures.<br>Vous touchez donc votre salaire de '.$salaire.' Crédits.<br>Vous avez également touché '.$hs.' Crédits en heures sup durant la journée.","Salaire","'.time().'","oui")';
							$reqs = mysql_query($sqls);
							}
						else
							{
							$sqls = 'INSERT INTO messages_tbl(id,auteur,cible,message,objet,moment,nouveau) VALUES("","'.$nom.'","'.$pseudo.'","Vous avez rempli votre contrat de travail aujourd`hui.<br><br>Vous avez travaillé '.$points.'heures et il vous est demandé de travailler '.$mintrav.'heures.<br>Vous touchez donc votre salaire de '.$salaire.'","Salaire","'.time().'","oui")';
							$reqs = mysql_query($sqls);
							}
						}
					}
				elseif($nom!="CIE" && $nom!="CIPE" && $nom!="Police" && $nom!="DI2RCO" && $nom!="Conseil Imperial" && $nom!="Prison" && $nom!="Services techniques de la ville" && $nom!="DOI" && $nom!="Chambre des Lois" && $nom!="DC Network")
					{
					supprimer_entreprise(mysql_result($req2,0,id));
					}
				elseif($nom!="Aucune")
					{
					$sqlv = 'SELECT id FROM principal_tbl WHERE entreprise="'.$nom.'"' ;
					$reqv = mysql_query($sqlv);
					$resv = mysql_num_rows($reqv);
					for($i=0; $i != $resv ; $i++)
						{
						$idv = mysql_result($reqv,$i,id);
						$sqls = 'SELECT pseudo FROM principal_tbl WHERE id="'.$idv.'"' ;
						$reqs = mysql_query($sqls);
						$nick = mysql_result($reqs,0,pseudo); 
						$sqls = 'INSERT INTO messages_tbl(id,auteur,cible,message,objet,moment,nouveau) VALUES("","'.$nom.'","'.$nick.'","L\'organisation à fait faillite. Vous êtes licencié.","Faillite !","'.time().'","oui")' ;
						$reqs = mysql_query($sqls);
						}
					$sqls = 'UPDATE principal_tbl SET type="Aucun" , entreprise="Aucune" , salaire="0" , difficulte="0" , points="0" WHERE entreprise="'.$nom.'"' ;
					$reqs = mysql_query($sqls);
					$sqls = 'DELETE FROM `e_'.str_replace(" ","_",''.$nom.'').'_tbl` WHERE type!= "chef"' ;
					$reqs = mysql_query($sqls);
					}
				}
			else
				{
				$sql3 = 'SELECT pseudo,actif FROM principal_tbl WHERE id= "'.$id.'"' ;
				$req3 = mysql_query($sql3);
				$pseudo = mysql_result($req3,0,pseudo);
				$actif = mysql_result($req3,0,actif);
				if($actif=="oui")
					{
					if($sinon=="pp")
						{
						$sqls = 'INSERT INTO messages_tbl(id,auteur,cible,message,objet,moment,nouveau) VALUES("","'.$nom.'","'.$pseudo.'","Vous êtes hors de votre contrat de travail !<br><br>Vous avez travaillé '.$points.'heures et il vous est demandé de travailler '.$mintrav.'heures.<br>Vous ne touchez donc rien.","Pas de salaire !","'.time().'","oui")';
						$reqs = mysql_query($sqls);
						}
					elseif(($sinon=="ls") || ($sinon=="lj"))
						{
						$sqls = 'UPDATE principal_tbl SET type= "Aucun" , entreprise= "Aucune" , points= "0" , salaire= "0" , difficulte= "0" WHERE id= "'.$id.'"' ;
						$reqs = mysql_query($sqls);
						$nbreactuel = mysql_result($req1,0,nbreactuel);
						$nbreactuel = $nbreactuel - 1;
						if($nbreactuel<0) { $nbreactuel = 0; }
						$sqls = 'UPDATE `e_'.str_replace(" ","_",''.$nom.'').'_tbl` SET nbreactuel= "'.$nbreactuel.'" WHERE poste= "'.$poste.'"' ;
						$reqs = mysql_query($sqls);
						$sqls = 'INSERT INTO messages_tbl(id,auteur,cible,message,objet,moment,nouveau) VALUES("","'.$nom.'","'.$pseudo.'","Vous êtes hors de votre contrat de travail !<br><br>Vous avez travaillé '.$points.'heures et il vous est demandé de travailler '.$mintrav.'heures.<br>Vous êtes donc licencié.","Licencié !","'.time().'","oui")';
						$reqs = mysql_query($sqls);
						}
					elseif($sinon=="ma")
						{
						$sqls = 'INSERT INTO messages_tbl(id,auteur,cible,message,objet,moment,nouveau) VALUES("","'.$nom.'","'.$pseudo.'","Vous êtes hors de votre contrat de travail !<br><br>Vous avez travaillé '.$points.'heures et il vous est demandé de travailler '.$mintrav.'heures.<br>Vous ne touchez donc rien.<br><br>Votre supérieur en est informé par un message privé.","Pas de salaire !","'.time().'","oui")';
						$reqs = mysql_query($sqls);
						$sqls = 'SELECT poste FROM `e_'.str_replace(" ","_",''.$nom.'').'_tbl` WHERE type= "chef"' ;
						$reqs = mysql_query($sqls);
						$postechef = mysql_result($reqs,0,poste);
						$sqls = 'SELECT id FROM principal_tbl WHERE type= "'.$postechef.'" AND entreprise= "'.$nom.'"' ;
						$reqs = mysql_query($sqls);
						$ress = mysql_num_rows($reqs);
						if($ress>0)
							{
							$idchef = mysql_result($reqs,0,id);
							$sqls = 'SELECT pseudo FROM principal_tbl WHERE id= "'.$idchef.'"' ;
							$reqs = mysql_query($sqls);
							$chef = mysql_result($reqs,0,pseudo);
							$sqls = 'INSERT INTO messages_tbl(id,auteur,cible,message,objet,moment,nouveau) VALUES("","'.$nom.'","'.$chef.'","'.$pseudo.' est hors de son contrat de travail !<br><br>Il a travaillé '.$points.'heures et il lui est demandé de travailler '.$mintrav.'heures.<br>Il ne touche donc rien.","CF: '.$pseudo.'","'.time().'","oui")';
							$reqs = mysql_query($sqls);
							}
						}
					}
				}
			}
		}
	}

// HEURES DE TRAVAIL
$sql1 = 'UPDATE principal_tbl SET points= "0" WHERE points!= "999"' ;
mysql_query($sql1);

// NOUVEAUX JOUEURS
$sql = 'UPDATE principal_tbl SET nouveau= "non" WHERE nouveau= "oui"' ;
mysql_query($sql);

// BENEFICES
$sql1 = 'UPDATE entreprises_tbl SET benefices= "0"' ;
mysql_query($sql1);

// DECES
$sql1 = 'TRUNCATE TABLE deces_tbl' ;
mysql_query($sql1);

// BANDAGES
$sql1 = 'UPDATE principal_tbl SET soins= "0" WHERE soins > "0"' ;
mysql_query($sql1);

$sql = 'SELECT pseudo,num,rue,location FROM proprietaire_tbl WHERE location!= "0"' ;
$req = mysql_query($sql);
$res = mysql_num_rows($req);

for($i=0; $i != $res; $i++)
	{
	$pseudol = mysql_result($req,$i,pseudo); 
	$num = mysql_result($req,$i,num); 
	$rue = mysql_result($req,$i,rue); 
	$prix = mysql_result($req,$i,location); 
	$sql1 = 'SELECT id FROM principal_tbl WHERE numl= "'.$num.'" AND ruel= "'.$rue.'"' ;
	$req1 = mysql_query($sql1);
	$res1 = mysql_num_rows($req1);
	if($res1!=0)
		{
		$id = mysql_result($req1,0,id); 
		$sql1 = 'SELECT credits FROM principal_tbl WHERE id= "'.$id.'"' ;
		$req1 = mysql_query($sql1);
		$credits = mysql_result($req1,0,credits); 
		$sql1 = 'SELECT id FROM principal_tbl WHERE pseudo= "'.$pseudol.'"' ;
		$req1 = mysql_query($sql1);
		$idl = mysql_result($req1,0,id);
		$sql1 = 'SELECT credits FROM principal_tbl WHERE id= "'.$idl.'"' ;
		$req1 = mysql_query($sql1);
		$creditsl = mysql_result($req1,0,credits); 
		$sql1 = 'SELECT pseudo,actif,action FROM principal_tbl WHERE id= "'.$id.'"' ;
		$req1 = mysql_query($sql1);
		$pseudo = mysql_result($req1,0,pseudo);
		$actif = mysql_result($req1,0,actif);
		$action= mysql_result($req1,0,action);
		if($credits>=$prix && $actif=="oui")
			{
			$credits = $credits - $prix;
			$creditsl = $creditsl + $prix;
			$sql1 = 'UPDATE principal_tbl SET credits= "'.$credits.'" WHERE id= "'.$id.'"' ;
			$req1 = mysql_query($sql1);
			$sql1 = 'UPDATE principal_tbl SET credits= "'.$creditsl.'" WHERE id= "'.$idl.'"' ;
			$req1 = mysql_query($sql1);
			$sqls = 'INSERT INTO messages_tbl(id,auteur,cible,messageobjet,moment) VALUES("","Services du logement","'.$pseudol.'","Votre locataire "'.$pseudo.'" paye son loyer de '.$prix.' Crédits du '.$num.' '.$rue.'.<br /><br />Votre compte a été accredité.","Loyer payé","'.time().'")';
			$reqs = mysql_query($sqls);
			}
		else
			{
			if($actif=="oui" && $action!="prison")
				{
				$sql1 = 'UPDATE principal_tbl SET credits="7" WHERE id= "'.$id.'"' ;
				$req1 = mysql_query($sql1);
				$sql1 = 'UPDATE principal_tbl SET police="0" WHERE id= "'.$id.'"';
				$req1 = mysql_query($sql1);
				$sql1 = 'DELETE FROM crimes_tbl WHERE pseudo= "'.$pseudo.'"';
				$req1 = mysql_query($sql1);
				$sql1 = 'UPDATE principal_tbl SET ruel= "Aucune" , numl= "0" WHERE id= "'.$id.'"';
				$req1 = mysql_query($sql1);
				$sql1 = 'SELECT num,rue FROM entreprises_tbl WHERE type= "prison"' ;
				$req1 = mysql_query($sql1);
				$ruep = mysql_result($req1,0,rue);
				$nump = mysql_result($req1,0,num);
				$sql1 = 'UPDATE principal_tbl SET rue= "'.ucwords($ruep).'" , num= "'.$nump.'" , action= "prison", alim= "1" WHERE id= "'.$id.'"';
				$req1 = mysql_query($sql1);
				$sqlca = 'INSERT INTO casiers_tbl(id,pseudo,datea,raison,policier) VALUES("","'.$pseudo.'","'.time().'","Non payement de loyer au propriétaire Mr. '.$pseudol.'.<br />Un jour de détention.","Services du logement") ';
				$reqca = mysql_query($sqlca);
				$sqls = 'INSERT INTO messages_tbl(id,auteur,cible,message,objet,moment) VALUES("","Services du logement","'.$pseudo.'","Un agent de police vous met en état d\'arrestation pour non paiement de loyer !<br>Vous vous laissez faire.","Loyer non payé","'.time().'")';
				$reqs = mysql_query($sqls);
				$sqls = 'INSERT INTO messages_tbl(id,auteur,cible,message,objet,moment) VALUES("","Services du logement","'.$pseudol.'","Un agent de police a mis en état d\'arrestation votre locataire "'.$pseudo.'" pour non paiement de loyer !<br />Votre logement est de nouveau libre pour accueillir un nouveau locataire.","Locataire expulsé","'.time().'")';
				$reqs = mysql_query($sqls);
				$actc = $action;
				if($actc=="travail")
					{
					$sql = 'SELECT type,entreprise FROM principal_tbl WHERE id= "'.$id.'"' ;
					$req = mysql_query($sql);
					$postec = mysql_result($req,0,type); 
					$entreprisec = mysql_result($req,0,entreprise); 
					$sql2 = 'SELECT id FROM principal_tbl WHERE entreprise= "'.$entreprisec.'" AND type= "'.$postec.'"' ;
					$req2 = mysql_query($sql2);
					$res2 = mysql_num_rows($req2);
					$restot = 0;
					for($u=0; $u != $res2; $u++)
						{
						$idipa = mysql_result($req2,$u,id); 
						$sql1 = 'SELECT action FROM principal_tbl WHERE id= "'.$idipa.'"' ;
						$req1 = mysql_query($sql1);
						$actionc = mysql_result($req1,0,action); 
						if($actionc=="travail")
							{
							$restot = $restot + 1;
							}
						}
					if($entreprisec!="Aucune")
						{
						$sql = 'SELECT type FROM `e_'.str_replace(" ","_",''.$entreprisec.'').'_tbl` WHERE poste= "'.$postec.'"' ;
						$req = mysql_query($sql);
						$typeposte = mysql_result($req,0,type); 
						$sql = 'SELECT type,ouvert FROM entreprises_tbl WHERE nom= "'.$entreprisec.'"' ;
						$req = mysql_query($sql);
						$type = mysql_result($req,0,type); 
						}
					if(($actc=="travail") && ($restot==1))
						{
						if(($type=="agence immobiliaire") || ($type=="boutique armes") || ($type=="boutiques spécialisee") || ($type=="ventes aux encheres") || ($type=="usine de production") )
							{
							if($typeposte=="vendeur")
								{
								$sql = 'UPDATE entreprises_tbl SET ouvert="non" WHERE nom= "'.$entreprisec.'"' ;
								$req = mysql_query($sql);
								}
							}
						elseif($type=="banque")
							{
							if($typeposte=="banquier")
								{
								$sql = 'UPDATE entreprises_tbl SET ouvert="non" WHERE nom= "'.$entreprisec.'"' ;
								$req = mysql_query($sql);
								}
							}
						elseif($type=="bar cafe")
							{
							if($typeposte=="serveur")
								{
								$sql = 'UPDATE entreprises_tbl SET ouvert="non" WHERE nom= "'.$entreprisec.'"' ;
								$req = mysql_query($sql);
								}
							}
						elseif($type=="hopital")
							{
							if($typeposte=="medecin")
								{
								$sql = 'UPDATE entreprises_tbl SET ouvert="non" WHERE nom= "'.$entreprisec.'"' ;
								$req = mysql_query($sql);
								}
							}
						}
					}
				}
			}
		}
	}

$sql = 'SELECT nom FROM entreprises_tbl WHERE type!= "CIE" AND type!= "CIPE" AND type!= "proprete" AND type!= "prison" AND type!= "police" AND type!= "chambre" AND type!= "conseil" AND type!= "DOI" AND type!= "di2rco" AND type!= "dcn"' ;
$req = mysql_query($sql);
$res = mysql_num_rows($req);
for( $m = 0 ; $m != $res ; $m++ )
	{
	$corporation = mysql_result($req,$m,nom);
	$sql1 = 'SELECT chiffre FROM chiffre_affaire_tbl WHERE entreprise= "'.$corporation.'"' ;
	$req1 = mysql_query($sql1);
	$res1 = mysql_num_rows($req1);
	$chiffre = 0;
	for($c=0;$c!=$res1;$c++)
		{
		$chiffre = $chiffre + mysql_result($req1,$c,chiffre);
		}
	if($res1>0)
		{
		$chiffre = ceil( $chiffre / $res1 );
		}
	$sql1 = 'UPDATE entreprises_tbl SET chiffremoyen= "'.$chiffre.'" WHERE nom= "'.$corporation.'"' ;
	$req1 = mysql_query($sql1);
	}

echo 'OK';

mysql_close($db);
?> 
</body>
</html>
