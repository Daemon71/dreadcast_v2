#!/usr/bin/php
<?php 

	if(date('i')>=55 || date('i')<=5){}
	else
	{
	exit();
	}
	
include('CENSURE');

$db = mysql_connect('localhost', 'CENSURE', 'CENSURE') or die("Erreur de connexion au serveur.");
mysql_select_db('CENSURE',$db) or die("Erreur de connexion a la base de donnees.");

$sql = 'SELECT * FROM principal_tbl';
$req = mysql_query($sql);
$res = mysql_num_rows($req);
for($i=0;$i!=$res;$i++)
	{
	$id = mysql_result($req,$i,id);
	$faim = mysql_result($req,$i,faim);
	$soif = mysql_result($req,$i,soif);
	$action = mysql_result($req,$i,action);
	if($action=="Vacances")
		{
		}
	elseif($action=="travail" || $action=="Recherche de cristaux")
		{
		$faim = $faim - 1;
		$soif = $soif - 3;
		}
	elseif($action=="repos")
		{
		$num = mysql_result($req,$i,num);
		$rue = mysql_result($req,$i,rue);
		if($num<=0) { $num = 0; $rue = "Rue"; }
		$sql1 = 'SELECT niveaufrigo FROM lieu_tbl WHERE num="'.$num.'" AND rue= "'.$rue.'"';
		$req1 = mysql_query($sql1);
		$niveaufrigo = mysql_result($req1,0,niveaufrigo);
		if($niveaufrigo>0)
			{
			$niveaufrigo = $niveaufrigo - 1;
			$sql1 = 'UPDATE lieu_tbl SET niveaufrigo= "'.$niveaufrigo.'" WHERE num="'.$num.'" AND rue= "'.$rue.'"';
			$req1 = mysql_query($sql1);
			}
		else
			{
			$faim = $faim - 1;
			$soif = $soif - 2;
			}
		}
	elseif($action!="mort" && $action!="prison")
		{
		$faim = $faim - 1;
		$soif = $soif - 1;
		}
	$neg = 0;
	if($soif<0) { $neg = $soif; $soif = 0; }
	if($faim<0) { $neg = $neg + $faim; $faim = 0; }
	if($neg<0)
		{
		$fatigue = mysql_result($req,$i,fatigue);
		$sante = mysql_result($req,$i,sante);
		for($p=$fatigue;$p>0;$p--)
			{
			if($neg<0)
				{
				$neg = $neg + 1;
				$fatigue = $fatigue - 15;
				}
			}
		for($p=$sante;$p>0;$p--)
			{
			if($neg<0)
				{
				$neg = $neg + 1;
				$sante = $sante - 10;
				}
			}
		if($sante<0) $sante = 0;
		if($fatigue<0) $fatigue = 0;
		$sql1 = 'UPDATE principal_tbl SET sante= "'.$sante.'" , fatigue="'.$fatigue.'" WHERE id= "'.$id.'"' ;
		$req1 = mysql_query($sql1);
		if($sante==0)
			{
			$pseudo = mysql_result($req,$i,pseudo);
			mourir($pseudo,'Faim','');
			$sql1 = 'INSERT INTO messages_tbl(id,auteur,cible,message,objet,moment,nouveau) VALUES("","Dreadcast","'.$pseudo.'","Vous avez trop faim.<br /><br />Vous êtes mort.","Vous êtes mort !","'.time().'","oui")' ;
			$req1 = mysql_query($sql1);
			}
		}
	$sql1 = 'UPDATE principal_tbl SET soif= "'.$soif.'" , faim="'.$faim.'" WHERE id= "'.$id.'"' ;
	$req1 = mysql_query($sql1);
	}
//mysql_query('INSERT INTO messages_tbl(auteur,cible,message,objet,moment) VALUES("Dreadcast","Overflow","1 Faim et soif ok","Verif","'.time().'")');
$sql = 'SELECT * FROM vente_tbl' ;
$req = mysql_query($sql);
$res = mysql_num_rows($req);
for($i=0;$i!=$res;$i++)
	{
	$idv = mysql_result($req,$i,id);
	$temps = mysql_result($req,$i,fin);
	if($temps>1)
		{
		$temps = $temps - 1;
		$sql1 = 'UPDATE vente_tbl SET fin= "'.$temps.'" WHERE id= "'.$idv.'"' ;
		$req1 = mysql_query($sql1);
		}
	elseif($temps==1)
		{
		$acheteur = mysql_result($req,$i,acheteur);
		$vendeur = mysql_result($req,$i,vendeur);
		if($acheteur!="")
			{
			$enchere = mysql_result($req,$i,enchere);
			$objetv = mysql_result($req,$i,objet);
			$sql1 = 'SELECT id FROM principal_tbl WHERE pseudo= "'.$vendeur.'"' ;
			$req1 = mysql_query($sql1);
			$id = mysql_result($req1,0,id);
			$sql1 = 'SELECT credits FROM principal_tbl WHERE id= "'.$id.'"' ;
			$req1 = mysql_query($sql1);
			$creditsv = mysql_result($req1,0,credits) + $enchere;
			$sql1 = 'UPDATE principal_tbl SET credits= "'.$creditsv.'" WHERE id= "'.$id.'"' ;
			$req1 = mysql_query($sql1);
			$sqls = 'INSERT INTO messages_tbl(id,auteur,cible,message,objet,moment,nouveau) VALUES("","Hall des enchères","'.$vendeur.'","Un acheteur vient d`acheter votre produit <strong>'.$objetv.'</strong>.<br />Vous gagnez donc <strong>'.$enchere.' Crédits</strong>.","Objet vendu !","'.time().'","oui")';
			$reqs = mysql_query($sqls);
			
			$sqls = 'INSERT INTO messages_tbl(id,auteur,cible,message,objet,moment,nouveau) VALUES("","Hall des enchères","'.$acheteur.'","Vous vennez de gagner l`enchère sur le produit <strong>'.$objetv.'</strong>.<br />Vous pouvez venir le récupérer au Hall des enchères.","Enchère gagnée !","'.time().'","oui")';
			$reqs = mysql_query($sqls);
			
			$sql1 = 'UPDATE principal_tbl SET credits= "'.$creditsa.'" WHERE id= "'.$ida.'"' ;
			$req1 = mysql_query($sql1);
			$sql1 = 'UPDATE vente_tbl SET fin= "0" , enchere= "0" , buyout= "0" , vendeur= "Aucun" WHERE id= "'.$idv.'"' ;
			$req1 = mysql_query($sql1);
			}
		else
			{
			$sqls = 'INSERT INTO messages_tbl(id,auteur,cible,message,objet,moment,nouveau) VALUES("","Hall des enchères","'.$vendeur.'","Votre objet <strong>'.$objetv.'</strong> ne s`est pas vendu.<br />Vous pouvez venir le récupérer au Hall des enchères.","Enchère terminée !","'.time().'","oui")';
			$reqs = mysql_query($sqls);
			$sql1 = 'UPDATE vente_tbl SET fin= "0" , enchere= "0" , buyout= "0" WHERE id= "'.$idv.'"' ;
			$req1 = mysql_query($sql1);
			}
		}
	}
//mysql_query('INSERT INTO messages_tbl(auteur,cible,message,objet,moment) VALUES("Dreadcast","Overflow","2 Objets vendus ok","Verif","'.time().'")');

$sql = 'SELECT id,action,fatigue,pseudo FROM principal_tbl WHERE action!= "aucune" AND action!= "prison" AND action!= "repos" AND action!= "Recherche de cristaux" AND action!= "travail" AND action!= "mort"' ;
$req = mysql_query($sql);
$res = mysql_num_rows($req);

for($i=0; $i != $res; $i++)
	{
	$id = mysql_result($req,$i,id); 
	$pseudo = mysql_result($req,$i,pseudo); 
	$action = mysql_result($req,$i,action); 
	$fatigue = mysql_result($req,$i,fatigue)-10; 
	if($action=="En cours de Combat (1Heure)")
		{
		$sql1 = 'SELECT combat,combat_max FROM principal_tbl WHERE id= "'.$id.'"' ;
		$req1 = mysql_query($sql1);
		$comp = mysql_result($req1,0,combat) + rand(4,6); 
		$comp_max = mysql_result($req1,0,combat_max);
		if($comp>$comp_max) $comp = $comp_max;
		$sql1 = 'UPDATE principal_tbl SET combat= "'.$comp.'" WHERE id= "'.$id.'"' ;
		$req1 = mysql_query($sql1);
		enregistre($pseudo,"combat",$comp);
		$sql1 = 'UPDATE principal_tbl SET action= "aucune" WHERE id= "'.$id.'"' ;
		$req1 = mysql_query($sql1);
		enregistre($pseudo,'cours de combat',"+1");
		}
	elseif($action=="En cours de Combat (2Heures)")
		{
		$sql1 = 'SELECT combat,combat_max FROM principal_tbl WHERE id= "'.$id.'"' ;
		$req1 = mysql_query($sql1);
		$comp = mysql_result($req1,0,combat) + rand(4,6); 
		$comp_max = mysql_result($req1,0,combat_max);
		if($comp>$comp_max) $comp = $comp_max;
		$sql1 = 'UPDATE principal_tbl SET combat= "'.$comp.'" WHERE id= "'.$id.'"' ;
		$req1 = mysql_query($sql1);
		enregistre($pseudo,"combat",$comp);
		$sql1 = 'UPDATE principal_tbl SET action= "En cours de Combat (1Heure)" WHERE id= "'.$id.'"' ;
		$req1 = mysql_query($sql1);
		enregistre($pseudo,'cours de combat',"+1");
		}
	elseif($action=="En cours de Combat (3Heures)")
		{
		$sql1 = 'SELECT combat,combat_max FROM principal_tbl WHERE id= "'.$id.'"' ;
		$req1 = mysql_query($sql1);
		$comp = mysql_result($req1,0,combat) + rand(4,6); 
		$comp_max = mysql_result($req1,0,combat_max);
		if($comp>$comp_max) $comp = $comp_max;
		$sql1 = 'UPDATE principal_tbl SET combat= "'.$comp.'" WHERE id= "'.$id.'"' ;
		$req1 = mysql_query($sql1);
		enregistre($pseudo,"combat",$comp);
		$sql1 = 'UPDATE principal_tbl SET action= "En cours de Combat (2Heures)" WHERE id= "'.$id.'"' ;
		$req1 = mysql_query($sql1);
		enregistre($pseudo,'cours de combat',"+1");
		}
	elseif($action=="En cours de Combat (4Heures)")
		{
		$sql1 = 'SELECT combat,combat_max FROM principal_tbl WHERE id= "'.$id.'"' ;
		$req1 = mysql_query($sql1);
		$comp = mysql_result($req1,0,combat) + rand(4,6); 
		$comp_max = mysql_result($req1,0,combat_max);
		if($comp>$comp_max) $comp = $comp_max;
		$sql1 = 'UPDATE principal_tbl SET combat= "'.$comp.'" WHERE id= "'.$id.'"' ;
		$req1 = mysql_query($sql1);
		enregistre($pseudo,"combat",$comp);
		$sql1 = 'UPDATE principal_tbl SET action= "En cours de Combat (3Heures)" WHERE id= "'.$id.'"' ;
		$req1 = mysql_query($sql1);
		enregistre($pseudo,'cours de combat',"+1");
		}
	elseif($action=="En cours de Tir (1Heure)")
		{
		$sql1 = 'SELECT tir,tir_max FROM principal_tbl WHERE id= "'.$id.'"' ;
		$req1 = mysql_query($sql1);
		$comp = mysql_result($req1,0,tir) + rand(4,6); 
		$comp_max = mysql_result($req1,0,tir_max);
		if($comp>$comp_max) $comp = $comp_max;
		$sql1 = 'UPDATE principal_tbl SET tir= "'.$comp.'" WHERE id= "'.$id.'"' ;
		$req1 = mysql_query($sql1);
		enregistre($pseudo,"tir",$comp);
		$sql1 = 'UPDATE principal_tbl SET action= "aucune" WHERE id= "'.$id.'"' ;
		$req1 = mysql_query($sql1);
		enregistre($pseudo,'cours de tir',"+1");
		}
	elseif($action=="En cours de Tir (2Heures)")
		{
		$sql1 = 'SELECT tir,tir_max FROM principal_tbl WHERE id= "'.$id.'"' ;
		$req1 = mysql_query($sql1);
		$comp = mysql_result($req1,0,tir) + rand(4,6); 
		$comp_max = mysql_result($req1,0,tir_max);
		if($comp>$comp_max) $comp = $comp_max;
		$sql1 = 'UPDATE principal_tbl SET tir= "'.$comp.'" WHERE id= "'.$id.'"' ;
		$req1 = mysql_query($sql1);
		enregistre($pseudo,"tir",$comp);
		$sql1 = 'UPDATE principal_tbl SET action= "En cours de Tir (1Heure)" WHERE id= "'.$id.'"' ;
		$req1 = mysql_query($sql1);
		enregistre($pseudo,'cours de tir',"+1");
		}
	elseif($action=="En cours de Tir (3Heures)")
		{
		$sql1 = 'SELECT tir,tir_max FROM principal_tbl WHERE id= "'.$id.'"' ;
		$req1 = mysql_query($sql1);
		$comp = mysql_result($req1,0,tir) + rand(4,6); 
		$comp_max = mysql_result($req1,0,tir_max);
		if($comp>$comp_max) $comp = $comp_max;
		$sql1 = 'UPDATE principal_tbl SET tir= "'.$comp.'" WHERE id= "'.$id.'"' ;
		$req1 = mysql_query($sql1);
		enregistre($pseudo,"tir",$comp);
		$sql1 = 'UPDATE principal_tbl SET action= "En cours de Tir (2Heures)" WHERE id= "'.$id.'"' ;
		$req1 = mysql_query($sql1);
		enregistre($pseudo,'cours de tir',"+1");
		}
	elseif($action=="En cours de Tir (4Heures)")
		{
		$sql1 = 'SELECT tir,tir_max FROM principal_tbl WHERE id= "'.$id.'"' ;
		$req1 = mysql_query($sql1);
		$comp = mysql_result($req1,0,tir) + rand(4,6); 
		$comp_max = mysql_result($req1,0,tir_max);
		if($comp>$comp_max) $comp = $comp_max;
		$sql1 = 'UPDATE principal_tbl SET tir= "'.$comp.'" WHERE id= "'.$id.'"' ;
		$req1 = mysql_query($sql1);
		enregistre($pseudo,"tir",$comp);
		$sql1 = 'UPDATE principal_tbl SET action= "En cours de Tir (3Heures)" WHERE id= "'.$id.'"' ;
		$req1 = mysql_query($sql1);
		enregistre($pseudo,'cours de tir',"+1");
		}
	}
//mysql_query('INSERT INTO messages_tbl(auteur,cible,message,objet,moment) VALUES("Dreadcast","Overflow","3 Cours ok","Verif","'.time().'")');
$sql = 'SELECT id,pseudo,actif,action,fatigue,fatigue_max,drogue,rue,num FROM principal_tbl' ;
$req = mysql_query($sql);
$res = mysql_num_rows($req);

for($i=0; $i != $res; $i++)
	{
	$actif = mysql_result($req,$i,actif); 
	if($actif=="oui")
		{
		$id = mysql_result($req,$i,id); 
		$pseudo = mysql_result($req,$i,pseudo);
		$fatigue = mysql_result($req,$i,fatigue); 
		$fatiguemax = mysql_result($req,$i,fatigue_max); 
		$drogue = mysql_result($req,$i,drogue); 
		if($drogue>0) $fatiguemax = drogue($pseudo,$fatiguemax);
		$rue = mysql_result($req,$i,rue); 
		$num = mysql_result($req,$i,num); 
		$action = mysql_result($req,$i,action);
		if($action=="travail")
			{
			$sql1 = 'SELECT credits,type,entreprise,difficulte,points FROM principal_tbl WHERE id= "'.$id.'"' ;
			$req1 = mysql_query($sql1);
			$type = mysql_result($req1,0,type); 
			$credits = mysql_result($req1,0,credits); 
			$entreprise = mysql_result($req1,0,entreprise); 
			$difficulte = mysql_result($req1,0,difficulte); 
			$bonus = mysql_result($req1,0,points); 
			if($entreprise=="Aucune")
				{
				$sql1 = 'UPDATE principal_tbl SET action= "aucune"  WHERE id= "'.$id.'"' ;
				$req1 = mysql_query($sql1);
				}
			$sql1 = 'SELECT id FROM entreprises_tbl WHERE nom= "'.$entreprise.'"' ;
			$req1 = mysql_query($sql1);
			$resi = mysql_num_rows($req1);
			if($resi>0)
				{
				$sql1 = 'SELECT mintrav,bonus,type FROM `e_'.str_replace(" ","_",''.$entreprise.'').'_tbl` WHERE poste= "'.$type.'"' ;
				$req1 = mysql_query($sql1);
				$resver = mysql_num_rows($req1);
				if($resver>0)
					{
					$mintrav = mysql_result($req1,0,mintrav); 
					$hs = mysql_result($req1,0,bonus); 
					$typer = mysql_result($req1,0,type); 
					$bonus++;
					if(($typer=="vendeur") || ($typer=="banquier"))
						{
						$sql1 = 'SELECT economie FROM principal_tbl WHERE id= "'.$id.'"' ;
						$req1 = mysql_query($sql1);
						augmenter_statistique($id,"economie",mysql_result($req1,0,economie));
						}
					elseif($typer=="maintenance")
						{
						$sql1 = 'SELECT maintenance FROM principal_tbl WHERE id= "'.$id.'"' ;
						$req1 = mysql_query($sql1);
						augmenter_statistique($id,"maintenance",mysql_result($req1,0,maintenance));
						}
					elseif($typer=="securite")
						{
						$sql1 = 'SELECT observation FROM principal_tbl WHERE id= "'.$id.'"' ;
						$req1 = mysql_query($sql1);
						augmenter_statistique($id,"observation",mysql_result($req1,0,observation));
						}
					elseif(($typer=="directeur") || ($typer=="chef"))
						{
						$sql1 = 'SELECT gestion FROM principal_tbl WHERE id= "'.$id.'"' ;
						$req1 = mysql_query($sql1);
						augmenter_statistique($id,"gestion",mysql_result($req1,0,gestion));
						}
					elseif(($typer=="serveur") || ($typer=="hote"))
						{
						$sql1 = 'SELECT service FROM principal_tbl WHERE id= "'.$id.'"' ;
						$req1 = mysql_query($sql1);
						augmenter_statistique($id,"service",mysql_result($req1,0,service));
						}
					elseif($typer=="medecin")
						{
						$sql1 = 'SELECT medecine FROM principal_tbl WHERE id= "'.$id.'"' ;
						$req1 = mysql_query($sql1);
						augmenter_statistique($id,"medecine",mysql_result($req1,0,medecine));
						}
					elseif($typer=="technicien")
						{
						$sql1 = 'SELECT mecanique FROM principal_tbl WHERE id= "'.$id.'"' ;
						$req1 = mysql_query($sql1);
						augmenter_statistique($id,"mecanique",mysql_result($req1,0,mecanique));
						}
					if($typer!="chef")
						{
						$sql1 = 'UPDATE principal_tbl SET points= "'.$bonus.'" WHERE id= "'.$id.'"' ;
						$req1 = mysql_query($sql1);
						}
					$sql1 = 'SELECT budget,num,rue FROM entreprises_tbl WHERE nom= "'.$entreprise.'"' ;
					$req1 = mysql_query($sql1);
					$budget = mysql_result($req1,0,budget); 
					$num = mysql_result($req1,0,num); 
					$rue = mysql_result($req1,0,rue);
					if($num<=0) { $num = 0; $rue = "Rue"; }
					$sql1 = 'SELECT reposactuel FROM lieu_tbl WHERE num= "'.$num.'" AND rue= "'.$rue.'"' ;
					$req1 = mysql_query($sql1);
					$reposactuel = mysql_result($req1,0,reposactuel); 
					if($typer!="chef")
						{
						$sql1 = 'SELECT vetements FROM principal_tbl WHERE id= "'.$id.'"' ;
						$req1 = mysql_query($sql1);
						$vetements = mysql_result($req1,0,vetements); 
						$sql1 = 'SELECT economie FROM principal_tbl WHERE id= "'.$id.'"' ;
						$req1 = mysql_query($sql1);
						$total = $difficulte - $reposactuel;
						if($total<1)
							{
							$total = 1;
							}
						$fatigue = $fatigue - $total*10;
						if($fatigue<0)
							{
							$fatigue = 0;
							}
						}
					else
						{
						$fatigue = $fatigue - 50;
						if($fatigue<0)
							{
							$fatigue = 0;
							}
						}
					enregistre($pseudo,'travail',"+1");
					$sql1 = 'UPDATE principal_tbl SET fatigue= "'.$fatigue.'" WHERE id= "'.$id.'"' ;
					$req1 = mysql_query($sql1);
					if($bonus>$mintrav && $entreprise!="Aucune")
						{
						enregistre($pseudo,"acc_heures_supp","+1");
						if($budget>=$hs)
							{
							$credits = $credits + $hs;
							$sql1 = 'UPDATE principal_tbl SET credits= "'.$credits.'" WHERE id= "'.$id.'"' ;
							$req1 = mysql_query($sql1);
							$budget = $budget - $hs;
							$sql1 = 'UPDATE entreprises_tbl SET budget= "'.$budget.'" WHERE nom= "'.$entreprise.'"' ;
							$req1 = mysql_query($sql1);
							}
						elseif($entreprise!="CIE" && $entreprise!="CIPE" && $entreprise!="Police" && $entreprise!="DI2RCO" && $entreprise!="Conseil Imperial" && $entreprise!="Prison" && $entreprise!="Services techniques de la ville" && $entreprise!="DOI" && $entreprise!="Chambre des Lois" && $entreprise!="DC Network")
							{
							$sql1 = 'SELECT id FROM `entreprises_tbl` WHERE nom="'.$entreprise.'"' ;
							$req1 = mysql_query($sql1);
							supprimer_entreprise(mysql_result($req1,0,id));
							}
						else
							{
							$sqlv = 'SELECT id FROM principal_tbl WHERE entreprise="'.$entreprise.'"' ;
							$reqv = mysql_query($sqlv);
							$resv = mysql_num_rows($reqv);
							for($f=0; $f != $resv ; $f++)
								{
								$idv = mysql_result($reqv,$f,id);
								$sql1 = 'SELECT pseudo FROM principal_tbl WHERE id="'.$idv.'"' ;
								$req1 = mysql_query($sql1);
								$nick = mysql_result($req1,0,pseudo); 
								$sql1 = 'INSERT INTO messages_tbl(id,auteur,cible,message,objet,moment,nouveau) VALUES("","'.$entreprise.'","'.$nick.'","L`organisation à fait faillite. Vous êtes licencié.","Faillite !","'.time().'","oui")' ;
								$req1 = mysql_query($sql1);
								}
							$sql1 = 'UPDATE principal_tbl SET type="Aucun" , entreprise="Aucune" , salaire="0" , difficulte="0" , points="0" WHERE entreprise="'.$entreprise.'"' ;
							$req1 = mysql_query($sql1);
							$sqls = 'DELETE FROM `e_'.str_replace(" ","_",''.$entreprise.'').'_tbl` WHERE type!= "chef"' ;
							$reqs = mysql_query($sqls);
							}
						}
					
					if($fatigue==0)
						{
						$sql1 = 'SELECT num,rue,entreprise FROM principal_tbl WHERE id= "'.$id.'"' ;
						$req1 = mysql_query($sql1); 
						$entreprise = mysql_result($req1,0,entreprise);
						$num = mysql_result($req1,0,num); 
						$rue = mysql_result($req1,0,rue);
						
						$sql1 = 'SELECT numl,ruel FROM principal_tbl WHERE id= "'.$id.'"' ;
						$req1 = mysql_query($sql1);
						$numl = mysql_result($req1,0,numl); 
						$ruel = mysql_result($req1,0,ruel);
						if($ruel!="Aucune")
							{
							$sql1 = 'UPDATE principal_tbl SET rue= "'.$ruel.'" , num= "'.$numl.'" , action="repos" WHERE id= "'.$id.'"' ;
							$req1 = mysql_query($sql1);
							}
						else
							{
							$numrue = coordonnees_sortie_rue($num,$rue);
							$sql1 = 'UPDATE principal_tbl SET rue= "'.$numrue['rue'].'" , num= "'.$numrue['num'].'" , action="repos" WHERE id= "'.$id.'"' ;
							$req1 = mysql_query($sql1);
							}
						
						// On vérifie l'ouverture de toutes les entreprises à la fin
						}
					}
				else
					{
					$sql1 = 'SELECT pseudo FROM principal_tbl WHERE id="'.$id.'"' ;
					$req1 = mysql_query($sql1);
					$nick = mysql_result($req1,0,pseudo); 
					$sql1 = 'INSERT INTO messages_tbl(id,auteur,cible,message,objet,moment,nouveau) VALUES("","'.$entreprise.'","'.$nick.'","Vous êtes licencié.","Vous êtes licencié !","'.time().'","oui")' ;
					$req1 = mysql_query($sql1);
					$sql1 = 'UPDATE principal_tbl SET type="Aucun" , entreprise="Aucune" , salaire="0" , difficulte="0" , points="0" WHERE id="'.$id.'"' ;
					$req1 = mysql_query($sql1);
					}
				}
			}
		elseif($action=="repos" || $action=="prison")
			{
			if($num<=0) { $num = 0; $rue = "Rue"; }
			$sql1 = 'SELECT reposactuel FROM lieu_tbl WHERE num= "'.$num.'" AND rue= "'.$rue.'"' ;
			$req1 = mysql_query($sql1);
			$reposactuel = mysql_result($req1,0,reposactuel); 
			$fatigue = $fatigue + $reposactuel*10;
			if($fatigue>$fatiguemax)
				{
				$fatigue = $fatiguemax;
				}
			$sql1 = 'UPDATE principal_tbl SET fatigue= "'.$fatigue.'" WHERE id= "'.$id.'"' ;
			$req1 = mysql_query($sql1);
			}
		elseif($action=="Recherche de cristaux")
			{
			if($num<=0) { $num = 0; $rue = "Rue"; }
			$sql1 = 'SELECT cristaux FROM lieu_tbl WHERE num= "'.$num.'" AND rue= "'.$rue.'"' ;
			$req1 = mysql_query($sql1);
			$cristaux = mysql_result($req1,0,cristaux); 
			if($cristaux>0)
				{
				$sql1 = 'SELECT pseudo,recherche,objet,fatigue FROM principal_tbl WHERE id="'.$id.'"' ;
				$req1 = mysql_query($sql1);
				$nick = mysql_result($req1,0,pseudo); 
				$recherche = mysql_result($req1,0,recherche); 
				$objet = mysql_result($req1,0,objet);
				if(($objet=="Neuvopack") or ($objet=="Neuvopack1") or ($objet=="Neuvopack2") or ($objet=="Neuvopack3") or ($objet=="Neuvopack4") or ($objet=="Neuvopack5") or ($objet=="Neuvopack6") or ($objet=="Neuvopack7") or ($objet=="Neuvopack8") or ($objet=="Neuvopack9"))
					{
					if($objet=="Neuvopack")  $niveau = 0;
					elseif($objet=="Neuvopack1")  $niveau = 1; 
					elseif($objet=="Neuvopack2")  $niveau = 2; 
					elseif($objet=="Neuvopack3")  $niveau = 3; 
					elseif($objet=="Neuvopack4")  $niveau = 4; 
					elseif($objet=="Neuvopack5")  $niveau = 5; 
					elseif($objet=="Neuvopack6")  $niveau = 6; 
					elseif($objet=="Neuvopack7")  $niveau = 7; 
					elseif($objet=="Neuvopack8")  $niveau = 8; 
					elseif($objet=="Neuvopack9")  $niveau = 9; 
					augmenter_statistique($id,"recherche",$recherche);
					$recup = ceil($recherche / rand(10,100));
					if($recup>$cristaux) $recup = $cristaux;
					$niveau = $niveau + $recup;
					if($niveau>10) $niveau = 10;
					elseif($niveau==0) $niveau = 1;
					$cristaux = $cristaux - $niveau;
					$fatigue = $fatigue - 10;
					if($fatigue<0) $fatigue = 0;
					if($num<=0) { $num = 0; $rue = "Rue"; }
					$sql1 = 'UPDATE lieu_tbl SET cristaux= "'.$cristaux.'" WHERE num= "'.$num.'" AND rue= "'.$rue.'"' ;
					$req1 = mysql_query($sql1);
					$sql1 = 'UPDATE principal_tbl SET objet= "Neuvopack'.$niveau.'" , fatigue= "'.$fatigue.'" WHERE id= "'.$id.'"' ;
					$req1 = mysql_query($sql1);					
					if(($niveau==10) or ($fatigue==0))
						{
						$sql1 = 'UPDATE principal_tbl SET action= "aucune" WHERE id= "'.$id.'"' ;
						$req1 = mysql_query($sql1);					
						}
					$combien = ($recup==1)?"1 cristal":$recup." cristaux";
					$sql1 = 'INSERT INTO messages_tbl(id,auteur,cible,message,objet,moment,nouveau) VALUES("","Dreadcast","'.$nick.'","Vous avez trouvé '.$combien.'.<br />Vous en avez maintenant '.$niveau.'/10 dans votre neuvopack.<br /><br />Vous pouvez dès maintenant aller revendre l\'énergie produite aux services techniques de la ville.","Cristaux trouvés","'.time().'","oui")' ;
					$req1 = mysql_query($sql1);
					}
				else
					{
					$sql1 = 'INSERT INTO messages_tbl(id,auteur,cible,message,objet,moment,nouveau) VALUES("","Dreadcast","'.$nick.'","Vous avez trouvé des cristaux, cependant vous n\'avez pas de Neuvopack vide pour les accueillir.","Pas de neuvopack !","'.time().'","oui")' ;
					$req1 = mysql_query($sql1);
					}
				}
			else
				{
				$sql1 = 'SELECT pseudo FROM principal_tbl WHERE id="'.$id.'"' ;
				$req1 = mysql_query($sql1);
				$nick = mysql_result($req1,0,pseudo); 
				$sql1 = 'INSERT INTO messages_tbl(id,auteur,cible,message,objet,moment,nouveau) VALUES("","Dreadcast","'.$nick.'","Il n\'y a plus aucun cristaux à l\'endroit où vous faites vos recherches.","Plus de cristaux !","'.time().'","oui")' ;
				$req1 = mysql_query($sql1);
				$sql1 = 'UPDATE principal_tbl SET action="aucune" WHERE id="'.$id.'"' ;
				$req1 = mysql_query($sql1);
				}
			}
		}
	}
//mysql_query('INSERT INTO messages_tbl(auteur,cible,message,objet,moment) VALUES("Dreadcast","Overflow","4 Travail et cristaux et repos ok","Verif","'.time().'")');

$sql = 'SELECT id,cristaux FROM lieu_tbl WHERE cristaux<100' ;
$req = mysql_query($sql);
$res = mysql_num_rows($req);
for($i=0; $i != $res; $i++)
	{
	$total = mysql_result($req,$i,cristaux) + rand(0,2);
	$sql1 = 'UPDATE lieu_tbl SET cristaux= "'.$total.'" WHERE id= "'.mysql_result($req,$i,id).'"' ;
	$req1 = mysql_query($sql1);
	}

//mysql_query('INSERT INTO messages_tbl(auteur,cible,message,objet,moment) VALUES("Dreadcast","Overflow","5 Augmentation cristaux ok","Verif","'.time().'")');

$sql = 'SELECT id,energie FROM colonies_planetes_tbl';
$req = mysql_query($sql);
$res = mysql_num_rows($req);
for($i=0;$i!=$res;$i++)
	{
	$idp = mysql_result($req,$i,id);
	$sql1 = 'SELECT * FROM principal_tbl WHERE connec= "oui" AND planete= "'.$idp.'"';
	$req1 = mysql_query($sql1);
	$joueurs = mysql_num_rows($req1);
	$energie = mysql_result($req,0,energie) - $joueurs;
	if($energie<0)
		{
		$energie = 0;
		}
	$sql1 = 'UPDATE colonies_planetes_tbl SET energie= "'.$energie.'" WHERE id= "'.$idp.'"';
	$req1 = mysql_query($sql1);
	}
	
	
$sql = 'SELECT nom FROM entreprises_tbl' ;
$req = mysql_query($sql);
$res = mysql_num_rows($req);

for($i=0; $i != $res; $i++)
	{
	$nom = mysql_result($req,$i,nom);
	$sql1 = 'SELECT poste,nbreactuel FROM `e_'.str_replace(" ","_",''.$nom.'').'_tbl`' ;
	$req1 = mysql_query($sql1);
	$res1 = mysql_num_rows($req1);
	if($res1==0)
		{
		$sql2 = 'DELETE FROM entreprises_tbl WHERE nom= "'.$nom.'"' ;
		$req2 = mysql_query($sql2);
		}
	else
		{
		for($l=0; $l != $res1; $l++)
			{
			$poste = mysql_result($req1,$l,poste);
			$nbreactuel = mysql_result($req1,$l,nbreactuel);
			$sql2 = 'SELECT id FROM principal_tbl WHERE entreprise= "'.$nom.'" AND type= "'.$poste.'"' ;
			$req2 = mysql_query($sql2);
			$res2 = mysql_num_rows($req2);
			if($res2!=$nbreactuel)
				{
				$sql3 = 'UPDATE `e_'.str_replace(" ","_",''.$nom.'').'_tbl` SET nbreactuel= "'.$res2.'" WHERE poste= "'.$poste.'"' ;
				$req3 = mysql_query($sql3);
				}
			}
		verification_ouverture_entreprise($nom);
		}
	}

$digi = "".rand(1,9);
for($i=0;$i<5;$i++)
	$digi .= rand(1,9);
	
$sql = 'SELECT Lr.id FROM lieu_tbl Lr, lieux_speciaux_tbl Ls WHERE Ls.type="chateau" AND Ls.num = Lr.num AND Ls.rue LIKE Lr.rue';
$req = mysql_query($sql);
$res = mysql_num_rows($req);
if($res) {
	$sql = 'UPDATE lieu_tbl SET code = "'.$digi.'" WHERE id ='.mysql_result($req,0,id);
	mysql_query($sql);
}

$sql = 'UPDATE principal_tbl SET alcool=alcool-'.rand(10,20).' WHERE alcool>0';
mysql_query($sql);

$sql = 'UPDATE principal_tbl SET alcool="0" WHERE alcool<0';
mysql_query($sql);

mysql_close($db);

echo "OK";
?>
