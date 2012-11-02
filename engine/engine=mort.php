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

include('inc_fonctions.php');

$db = mysql_connect('localhost', 'CENSURE', 'CENSURE') or die("Erreur de connexion au serveur des messages.");
mysql_select_db('CENSURE',$db) or die("Erreur de connexion a la base de donnees des messages.");

$sql1 = 'SELECT id FROM messages_tbl WHERE cible= "'.$_SESSION['pseudo'].'" AND nouveau="oui"' ;
$req1 = mysql_query($sql1);
$resn = mysql_num_rows($req1);

$sql = 'SELECT id,action,rue,taille,num,statut,alim FROM principal_tbl WHERE pseudo= "'.$_SESSION['pseudo'].'"' ;
$req = mysql_query($sql);
$_SESSION['id'] = mysql_result($req,0,id);
$_SESSION['action'] = mysql_result($req,0,action);
$_SESSION['taille'] = mysql_result($req,0,taille);
$_SESSION['Num'] = mysql_result($req,0,num);
$_SESSION['statut'] = mysql_result($req,0,statut);
$_SESSION['alim'] = mysql_result($req,0,alim);
$raison = mysql_result($req,0,rue);
if($raison != "Assassiné" && $raison!="Faim" && $raison!="Riposte")
	{
	$sql = 'SELECT raison FROM deces_tbl WHERE victime="'.$_SESSION['pseudo'].'"' ;
	$req = mysql_query($sql);
	if(mysql_num_rows($req) != 0) 
		{
		$raison = mysql_result($req,0,raison);
		$sql = 'UPDATE principal_tbl SET num="0",rue="'.$raison.'" WHERE pseudo="'.$_SESSION['pseudo'].'"' ;
		$req = mysql_query($sql);
		}
	}

$sql = 'SELECT valeur FROM config_tbl WHERE objet= "tpsuppr"' ;
$req = mysql_query($sql);
$valeur = mysql_result($req,0,valeur);

$sql = 'SELECT valeur FROM config_tbl WHERE objet= "pourcperte"' ;
$req = mysql_query($sql);
$pourc = mysql_result($req,0,valeur);
$sql = 'SELECT valeur FROM config_tbl WHERE objet= "pourcsperte"' ;
$req = mysql_query($sql);
$pourcs = mysql_result($req,0,valeur);

/*$sql = 'SELECT * FROM pubs_tbl WHERE temps>"0" ORDER BY RAND() LIMIT 1' ;
$req = mysql_query($sql);
$res2 = mysql_num_rows($req);

if($res2>0)
	{
	$entpub = mysql_result($req,0,entreprise);
	$messagepub = mysql_result($req,0,message);
	
	if(mysql_result($req,0,lien)!="")
		{
		$lienpub = mysql_result($req,0,lien);
		$sql1 = 'SELECT rue,num FROM entreprises_tbl WHERE nom= "'.$entpub.'"' ;
		$req1 = mysql_query($sql1);
		$res1 = mysql_num_rows($req1);
		if($res1>0)
			{
			$ruepub = mysql_result($req1,0,rue);
			$numpub = mysql_result($req1,0,num);
			}
		else
			{
			$sql1 = 'SELECT rue,num FROM cerclesliste_tbl WHERE nom= "'.$entpub.'"' ;
			$req1 = mysql_query($sql1);
			$ruepub = mysql_result($req1,0,rue);
			$numpub = mysql_result($req1,0,num);
			}
		}
	else
		{
		$signpub = mysql_result($req,0,signature);
		}
	}
else
	{
	$entpub = " ";
	$messagepub = " ";
	$signpub = " ";
	}

if(($_SESSION['action']=="mort") && ($_SESSION['alim']!=10))
	{
	/*$sql = 'UPDATE principal_tbl SET drogue= "0" , alim= "10" WHERE id= "'.$_SESSION['id'].'"' ;
	$req = mysql_query($sql);
	$sql = 'UPDATE principal_tbl SET rattaque= "Aucune" , rintrusion= "Aucune" , rpolice= "Aucune" , rvol= "Aucune" WHERE id= "'.$_SESSION['id'].'"' ;
	$req = mysql_query($sql);
	$sql = 'UPDATE principal_tbl SET Police= "0" , DI2RCO= "0" WHERE id= "'.$_SESSION['id'].'"' ;
	$req = mysql_query($sql);
	$sql = 'SELECT numl,ruel FROM principal_tbl WHERE id= "'.$_SESSION['id'].'"' ;
	$req = mysql_query($sql);
	$numl = mysql_result($req,0,numl);
	$ruel = mysql_result($req,0,ruel);
	$sql = 'SELECT id FROM proprietaire_tbl WHERE rue= "'.$ruel.'" AND num= "'.$numl.'" AND pseudo= "'.$_SESSION['pseudo'].'"' ;
	$req = mysql_query($sql);
	$res = mysql_num_rows($req);
	if($res>0)
		{
		$sql = 'DELETE FROM lieu_tbl WHERE num= "'.$numl.'" AND rue= "'.$ruel.'"' ;
		$req = mysql_query($sql);
		$sql = 'DELETE FROM invlieu_tbl WHERE num= "'.$numl.'" AND rue= "'.$ruel.'"' ;
		$req = mysql_query($sql);
		$sql = 'DELETE FROM proprietaire_tbl WHERE num= "'.$numl.'" AND rue= "'.$ruel.'" AND pseudo= "'.$_SESSION['pseudo'].'"' ;
		$req = mysql_query($sql);
		}
	$sql = 'DELETE FROM casiers_tbl WHERE pseudo= "'.$_SESSION['pseudo'].'"' ;
	$req = mysql_query($sql);
	$sql = 'UPDATE principal_tbl SET numl= "0" , ruel= "Aucune" WHERE id= "'.$_SESSION['id'].'"' ;
	$req = mysql_query($sql);
	$sql = 'SELECT entreprise FROM principal_tbl WHERE id= "'.$_SESSION['id'].'"' ;
	$req = mysql_query($sql);
	$entreprise = mysql_result($req,0,entreprise); 
	if($entreprise!="Aucune")
		{
		$sql = 'SELECT type FROM entreprises_tbl WHERE nom= "'.$entreprise.'"' ;
		$req = mysql_query($sql);
		$tyy = mysql_result($req,0,type); 
	
		$sql = 'SELECT type,assurance FROM principal_tbl WHERE id= "'.$_SESSION['id'].'"' ;
		$req = mysql_query($sql);
		$poste = mysql_result($req,0,type); 
		$assurance = mysql_result($req,0,assurance); 
		$sql = 'SELECT type FROM `e_'.str_replace(" ","_",''.$entreprise.'').'_tbl` WHERE poste="'.$poste.'"' ;
		$req = mysql_query($sql);
		$type = mysql_result($req,0,type);
		if($assurance==0)
			{
			if($type=="chef")
				{
				if(($entreprise=="Conseil Imperial") || ($entreprise=="CIPE") || ($entreprise=="CIE") || ($entreprise=="Prison") || ($entreprise=="Police") || ($entreprise=="Services techniques de la ville") || ($entreprise=="Chambre des lois") || ($entreprise=="DI2RCO"))
					{
					$sql = 'UPDATE principal_tbl SET entreprise="Aucune" , salaire="0" , type="Aucun" , difficulte="0" , points="0" WHERE id= "'.$_SESSION['id'].'"' ;
					$req = mysql_query($sql);
					$sql = 'UPDATE `e_'.str_replace(" ","_",''.$entreprise.'').'_tbl` SET nbreactuel="0" WHERE type= "chef"' ;
					$req = mysql_query($sql);
					}
				else
					{
					$sql = 'SELECT nbreactuel FROM `e_'.str_replace(" ","_",''.$entreprise.'').'_tbl` WHERE type="chef"' ;
					$req = mysql_query($sql);
					$nombre = mysql_result($req,0,nbreactuel); 
					if($nombre==1)
						{
						$sql = 'UPDATE principal_tbl SET entreprise="Aucune" , salaire="0" , type="Aucun" , difficulte="0" , points="0" WHERE id= "'.$_SESSION['id'].'"' ;
						$req = mysql_query($sql);
						$sql = 'SELECT nbreactuel FROM `e_'.str_replace(" ","_",''.$entreprise.'').'_tbl` WHERE type= "directeur"' ;
						$req = mysql_query($sql);
						$res = mysql_num_rows($req);
						if($res!=0)
							{
							$nombred = mysql_result($req,0,nbreactuel);
							}
						else
							{
							$nombred = 0;
							}
						if($nombred!=0)
							{
							$sql = 'SELECT poste FROM `e_'.str_replace(" ","_",''.$entreprise.'').'_tbl` WHERE type="directeur"' ;
							$req = mysql_query($sql);
							$postounet = mysql_result($req,0,poste);
							$sql = 'SELECT id FROM principal_tbl WHERE type="'.$postounet.'"' ;
							$req = mysql_query($sql);
							$ideo = mysql_result($req,0,id);
							$sql = 'SELECT pseudo FROM principal_tbl WHERE id="'.$ideo.'"' ;
							$req = mysql_query($sql);
							$nick = mysql_result($req,0,pseudo);
							$sql = 'INSERT INTO messages_tbl(id,auteur,cible,message,moment) VALUES("","'.$entreprise.'","'.$nick.'","Le PDG vous confie l\'entreprise. Vous pouvez accéder au panneau de gestion à tout instant.","'.time().'")' ;
							$req = mysql_query($sql);
							$sql = 'UPDATE principal_tbl SET salaire="0" , type="'.$poste.'" , difficulte="0" , points="999" WHERE id= "'.$ideo.'"' ;
							$req = mysql_query($sql);
							$nombred = $nombred - 1;
							$sql = 'UPDATE `e_'.str_replace(" ","_",''.$entreprise.'').'_tbl` SET nbreactuel="'.$nombred.'" WHERE type= "directeur"' ;
							$req = mysql_query($sql);
							}
						elseif($nombred==0)
							{
							$sql = 'SELECT num,rue FROM entreprises_tbl WHERE nom="'.$entreprise.'"' ;
							$req = mysql_query($sql);
							$num = mysql_result($req,0,num); 
							$rue = mysql_result($req,0,rue); 
							$sql = 'DELETE FROM lieu_tbl WHERE num="'.$num.'" AND rue="'.$rue.'"' ;
							$req = mysql_query($sql);
							$sql = 'DELETE FROM invlieu_tbl WHERE num="'.$num.'" AND rue="'.$rue.'"' ;
							$req = mysql_query($sql);
							$sqlv = 'SELECT id,type FROM principal_tbl WHERE entreprise="'.$entreprise.'"' ;
							$reqv = mysql_query($sqlv);
							$resv = mysql_num_rows($reqv);
							for($i=0; $i != $resv ; $i++)
								{
								$idv = mysql_result($reqv,$i,id);
								$typemec = mysql_result($reqv,$i,type);
								$sql = 'SELECT type FROM `e_'.str_replace(" ","_",''.$_SESSION['entreprise'].'').'_tbl` WHERE poste="'.$typemec.'"' ;
								$req = mysql_query($sql);
								$typemec = mysql_result($req,0,type);
								$sql = 'SELECT pseudo FROM principal_tbl WHERE id="'.$idv.'"' ;
								$req = mysql_query($sql);
								$nick = mysql_result($req,0,pseudo); 
								$sql = 'INSERT INTO messages_tbl(id,auteur,cible,message,moment) VALUES("","'.$entreprise.'","'.$nick.'","L\'entreprise à fait faillite. Vous êtes licencié.","'.time().'")' ;
								$req = mysql_query($sql);
								}
							$sql1 = 'SELECT budget FROM entreprises_tbl WHERE nom= "'.$entreprise.'"' ;
							$req1 = mysql_query($sql1);
							$budget = mysql_result($req1,0,budget); 
							$sql1 = 'SELECT credits FROM principal_tbl WHERE id= "'.$_SESSION['id'].'"' ;
							$req1 = mysql_query($sql1);
							$inventaire = mysql_result($req1,0,credits); 
							$inventaire = $budget + $inventaire;
							$sql1 = 'UPDATE principal_tbl SET credits="'.$inventaire.'" WHERE id= "'.$_SESSION['id'].'"' ;
							$req1 = mysql_query($sql1);
									
							$sql = 'DELETE FROM pubs_tbl WHERE entreprise="'.$_SESSION['entreprise'].'"' ;
							$req = mysql_query($sql);
							$sql = 'DELETE FROM stocks_tbl WHERE entreprise="'.$_SESSION['entreprise'].'"' ;
							$req = mysql_query($sql);
							$sql = 'DELETE FROM entreprises_tbl WHERE nom="'.$entreprise.'"' ;
							$req = mysql_query($sql);
							$sql = 'UPDATE principal_tbl SET type="Aucun" , entreprise="Aucune" , salaire="0" , difficulte="0" , points="0" WHERE entreprise="'.$entreprise.'"' ;
							$req = mysql_query($sql);
							$sql = 'DROP TABLE `e_'.str_replace(" ","_",''.$entreprise.'').'_tbl`' ;
							$req = mysql_query($sql);
							}
						}
					elseif($nombre>1)
						{
						$nombre = $nombre - 1;
						$sql = 'UPDATE principal_tbl SET entreprise="Aucune" , salaire="0" , type="Aucun" , difficulte="0" , points="0" WHERE id= "'.$_SESSION['id'].'"' ;
						$req = mysql_query($sql);
						$sql = 'UPDATE `e_'.str_replace(" ","_",''.$entreprise.'').'_tbl` SET nbreactuel="'.$nombre.'" WHERE type= "chef"' ;
						$req = mysql_query($sql);
						}
					}
				}
			else
				{
				$sql = 'SELECT nbreactuel FROM `e_'.str_replace(" ","_",''.$entreprise.'').'_tbl` WHERE type= "'.$type.'"' ;
				$req = mysql_query($sql);
				$nombre = mysql_result($req,0,nbreactuel); 
				$nombre = $nombre - 1;
				
				$sql = 'UPDATE principal_tbl SET entreprise="Aucune" , salaire="0" , type="Aucun" , difficulte="0" , points="0" WHERE id= "'.$_SESSION['id'].'"' ;
				$req = mysql_query($sql);
				$sql = 'UPDATE `e_'.str_replace(" ","_",''.$entreprise.'').'_tbl` SET nbreactuel="'.$nombre.'" WHERE poste= "'.$type.'"' ;
				$req = mysql_query($sql);
				}
			}
		}
	}*/


mysql_close($db);
?>


<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">

<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="fr" >

	<head>
		<title>Dreadcast</title>
       	<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
		<meta http-equiv="Content-Style-Type" content="text/css" />
		<meta http-equiv="Content-Language" content="fr" />
		<meta name="description" content="Dreadcast est un jeu de role en ligne futuriste gratuit (jdr): simulation d'un jeu en ligne de strat&eacute;gie, jouez au jeu et choisissez votre role." />

		<meta name="keywords" lang="fr" content="Dreadcast, futuriste, jeu video, jeu en ligne, communauté, simulation, jeux gratuits, monde virtuel, RPG, cyber-punk, jeux, rôles, mmo, mmorpg, php, futur, empire, communautaire, gratuit, multijoueur, jeu de gestion, jeu de stratégie, jeu online, Action-RPG, rôle, Science-Fiction, Fantastique, heroic fantasy , jouer, gratui, gratuits, jeux en ligne, internet, net, web" />
		<meta name="author" content="MSpixel" />
		<meta name="reply-to" content="dreadcast@mspixel.fr" />
		<meta name="revisit-after" content="1 day" />
		<meta name="robots" content="all" />
		<style>
			a{color:#b91010;text-decoration:none;font-family:verdana,arial,sans-serif;font-size:12px;}
			a:hover{color:#b91010;border-bottom:1px solid #b91010;}
			#bas a{color:#999;}
			#bas a:hover{border-bottom:1px solid #999;}
		</style>
	</head>
   
	<body style="background:#181818;">
	
	<div style="position:absolute;top:20%;left:50%;margin-left:-265px;width:530px;background:url(im_objets/vousetesmort.gif) 0 0 no-repeat;font-family:Verdana,sans-serif;color:#fff;font-size:12px;line-height:20px;">
		<div style="margin:96px 0 20px 154px;">Voici quelques informations concernant les circonstances de votre mort :
			<div style="margin-top:5px;text-align:center;font-weight:bold;">
			<?php
				if($raison=="Assassiné") print('Vous avez &eacute;t&eacute; assassin&eacute;.');
				elseif($raison=="Faim") print('Vous &ecirc;tes mort de faim.');
				elseif($raison=="Riposte") print('Vous &ecirc;tes mort d\'une riposte.');
				else print('Raison inconnue. Contactez un administrateur. Raison donnée : '.$raison);
			?>
	        </div>
        </div>
        
		<div style="margin:0 0 20px 0;">
			Vous avez cependant la possibilit&eacute; de recr&eacute;er votre personnage avec <?php print(''.$pourc.''); ?>% de vos Cr&eacute;dits et <?php print(''.$pourcs.''); ?>% de vos statistiques.
			<?php
				if($_SESSION['Num']>=1) print('<div style="text-align:center;font-weight:bold;color:#b91010;"><a href="engine=recreer.php">Faire rena&icirc;tre votre personnage</a></div>');
				else print('<div style="margin-top:5px;text-align:center;color:#b91010;">Il faut attendre minuit ce soir.</div>');
			?>
		</div>
		
		<div>
			<?php
				if(statut($_SESSION['statut'])<2)
					{
					$jours = $valeur - $_SESSION['Num'];
					print('Vous avez <strong>'.$jours.'</strong> jours pour recr&eacute;er votre personnage avant suppression de votre compte.');
					}
				else print('Vous possedez un statut privilégié.<br />Votre compte ne peut donc pas se supprimer automatiquement.');
				?>
		</div>

	</div>
	
	<div id="bas" style="position:absolute;bottom:20px;left:50%;margin-left:-200px;width:400px;text-align:center;color:#666;">
		<a href="engine=mortmsg.php">Messagerie<?php if($resn != 0) print(' <span style="color:#b91010;">('.$resn.')</span>'); ?></a> - <a href="engine=mortrbug.php">Aide en ligne</a> - <a href="../wikast/">Accéder au Wikast</a> - <a href="index.php">Déconnexion</a>
	</div>
		
	</body>

</html>
