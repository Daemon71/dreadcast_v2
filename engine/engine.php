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
$_SESSION['distance'] = "";
$_SESSION['personnes'] = "";

$db = mysql_connect('localhost', 'CENSURE', 'CENSURE') or die("Erreur de connexion au serveur des messages.");
mysql_select_db('CENSURE',$db) or die("Erreur de connexion a la base de donnees des messages.");

if($_SESSION['statut']=="Debutant")
	{
	$_SESSION['case2'] = "Carte";
	$_SESSION['didac'] = 11;
	$sql = 'UPDATE principal_tbl SET case2="'.$_SESSION['case2'].'",quetedepart="'.$_SESSION['didac'].'" WHERE id= "'.$_SESSION['id'].'"' ;
	mysql_query($sql);
	}

$sql = 'SELECT * FROM principal_tbl WHERE id= "'.$_SESSION['id'].'"' ;
$req = mysql_query($sql);
$_SESSION['pseudo'] = mysql_result($req,0,pseudo);
$_SESSION['rue'] = mysql_result($req,0,rue);
$_SESSION['num'] = mysql_result($req,0,num);
$_SESSION['action'] = mysql_result($req,0,action);
$_SESSION['alim'] = mysql_result($req,0,alim);
$_SESSION['faim'] = mysql_result($req,0,faim);
$_SESSION['soif'] = mysql_result($req,0,soif);
$_SESSION['sante'] = mysql_result($req,0,sante);
$_SESSION['santemax'] = mysql_result($req,0,sante_max);
$_SESSION['fatigue'] = mysql_result($req,0,fatigue);
$_SESSION['fatiguemax'] = mysql_result($req,0,fatigue_max);
$_SESSION['drogue'] = mysql_result($req,0,drogue);
$_SESSION['malade'] = mysql_result($req,0,maladie);
$_SESSION['planete'] = mysql_result($req,0,planete);
$_SESSION['lieu'] = ucwords(strtolower($_SESSION['rue']));
$combat = mysql_result($req,0,combat); 
$observation = mysql_result($req,0,observation); 
$gestion = mysql_result($req,0,gestion); 
$maintenance = mysql_result($req,0,maintenance); 
$mecanique = mysql_result($req,0,mecanique); 
$service = mysql_result($req,0,service); 
$informatique = mysql_result($req,0,informatique); 
$recherche = mysql_result($req,0,recherche); 
$discretion = mysql_result($req,0,discretion); 
$economie = mysql_result($req,0,economie); 
$resistance = mysql_result($req,0,resistance); 
$tir = mysql_result($req,0,tir); 
$vol = mysql_result($req,0,vol); 
$medecine = mysql_result($req,0,medecine); 
$_SESSION['credits'] = mysql_result($req,0,credits);
$_SESSION['arme'] = mysql_result($req,0,arme);
$_SESSION['objet'] = mysql_result($req,0,objet);
$_SESSION['case1'] = mysql_result($req,0,case1);
$_SESSION['case2'] = mysql_result($req,0,case2);
$_SESSION['case3'] = mysql_result($req,0,case3);
$_SESSION['case4'] = mysql_result($req,0,case4);
$_SESSION['case5'] = mysql_result($req,0,case5);
$_SESSION['case6'] = mysql_result($req,0,case6);
$type = mysql_result($req,0,type);
$_SESSION['entreprise'] = mysql_result($req,0,entreprise);
$_SESSION['poste'] = mysql_result($req,0,type);
$_SESSION['event'] = mysql_result($req,0,event);

$sql = 'SELECT code,nom FROM lieu_tbl WHERE num= "'.$_SESSION['num'].'" AND rue= "'.$_SESSION['rue'].'"' ;
$req = mysql_query($sql);
$res = mysql_num_rows($req);

if($res>0)
	{
	$_SESSION['digicode'] = mysql_result($req,0,code);
	$nomrue = mysql_result($req,0,nom);
	}
elseif($_SESSION['num']<0)
	{
	$nomrue = $_SESSION['rue'];
	}
else
	{
	if($_SESSION['planete']==0)
		{
		$sql = 'UPDATE principal_tbl SET num= "0" , rue= "Rue" WHERE pseudo="'.$_SESSION['pseudo'].'"' ;
		$req = mysql_query($sql);
		$_SESSION['num'] = 0;
		$_SESSION['rue'] = "Rue";
		}
	else
		{
		$sql = 'SELECT num FROM lieu_tbl WHERE planete="'.$_SESSION['planete'].'" AND nom= "Poste de Commandement"' ;
		$req = mysql_query($sql);
		$numpc = mysql_result($req,0,num);
		$sql = 'UPDATE principal_tbl SET num= "'.$numpc.'" , rue= "Secteur" WHERE pseudo="'.$_SESSION['pseudo'].'"' ;
		$req = mysql_query($sql);
		$_SESSION['num'] = $numpc;
		$_SESSION['rue'] = "Secteur";
		}
	}

$sql = 'SELECT num,rue FROM entreprises_tbl WHERE nom= "'.$_SESSION['entreprise'].'"' ;
$req = mysql_query($sql);
$res = mysql_num_rows($req);
if($res>0)
	{
	$lieutrav = mysql_result($req,0,rue);
	$numtrav = mysql_result($req,0,num);
	}

$sql = 'SELECT poste,cercle FROM cercles_tbl WHERE pseudo= "'.$_SESSION['pseudo'].'"' ;
$req = mysql_query($sql);
$res = mysql_num_rows($req);
if($res>0)
	{
	$postecercle = mysql_result($req,0,poste);
	$_SESSION['cercle'] = mysql_result($req,0,cercle);
	$sql = 'SELECT num,rue FROM cerclesliste_tbl WHERE nom= "'.$_SESSION['cercle'].'"' ;
	$req = mysql_query($sql);
	$res = mysql_num_rows($req);
	if($res>0)
		{
		$lieucercle = mysql_result($req,0,rue);
		$numcercle = mysql_result($req,0,num);
		}
	}
else
	{
	$_SESSION['cercle'] = "";
	}

mysql_close($db);
?>
<?php
	if((date("H")>=21) || (date("H")<=7)) { include("inc_haut_n.php"); } else { include("inc_haut_j.php");} 

$event = event() && estDroide() ? $_SESSION['event'] : 0;
$_SESSION['santemax'] = $event == 1 || adm() ? $_SESSION['santemax']+200 : $_SESSION['santemax'];
	
?>

<div id="haut">
	<?php include("inc_entete.php"); ?>
	<div class="haut_aligngauche">
		<p>
		<div class="titrepage">
			Situation Actuelle
		</div>
		<?php if($_SESSION['lieu'] != "Rue" && $_SESSION['lieu']!="Ruelle" && $_SESSION['planete']==0 && $_SESSION['action']!="prison" && $_SESSION['num']>=0)
			print('<b class="module6ie"><a href="engine=go.php?num=0&rue=Rue" class="module6"><img src="im_objets/icon_rue.gif" alt="Retour" />Revenir dans la rue</a></b>');
			?>
		</p>
	</div>
</div>

<?php
$db = mysql_connect('localhost', 'CENSURE', 'CENSURE') or die("Erreur de connexion au serveur des messages.");
mysql_select_db('CENSURE',$db) or die("Erreur de connexion a la base de donnees des messages.");

if($_SESSION['action']=="travail" || $_SESSION['action']=="repos")
	{
	$_SESSION['code'] = $_SESSION['digicode'];
	}

if((ucwords($lieutrav)==ucwords($_SESSION['lieu'])) && ($numtrav==$_SESSION['num']))
	{
	if($_SESSION['entreprise']!="Aucune")
		{
		$sql = 'SELECT type,bdd FROM `e_'.str_replace(" ","_",''.$_SESSION['entreprise'].'').'_tbl` WHERE poste= "'.$type.'"' ;
		$req = mysql_query($sql);
		$type = mysql_result($req,0,type);
		$bdd = mysql_result($req,0,bdd);
		}
	else
		{
		$bdd = "";
		}
	$tyy = $type;
	
	if(($type=="maintenance") || ($type=="securite") || ($type=="vendeur") || ($type=="banquier") || ($type=="serveur") || ($type=="medecin") || ($type=="hote") || ($type=="technicien"))
		{
		$passif = 1;
		}
	
	if($bdd!="")
		{
		$sql = 'SELECT type FROM entreprises_tbl WHERE nom= "'.$_SESSION['entreprise'].'"' ;
		$req = mysql_query($sql);
		$type = mysql_result($req,0,type);
		}
	else
		{
		$type = "";
		}
	}

if($_SESSION['action']=="prison")
	{
	print('<div id="centre_imperium">
	
	<p id="location">Prison de la ville</p>

	<p id="textelse"><img src="im_objets/Prison.bmp"><br /><br /> <b>Temps restant en prison :</b> <i>'.$_SESSION['alim'].' Jour(s) </i><br />
	
	<a href="engine=prisoncour.php">Acceder à la cour des prisonniers</a></p>');
	}
elseif($_SESSION['action']=="En cours de Combat (1Heure)")
	{
	print('<div id="centre_imperium">
	
	<p id="location">Centre Imp&eacute;rial d\'Entrainement</p>
	
	<p id="textelse"><img src="im_objets/Cours.jpg" width="170" height="150"><br /><br /> <b>Temps restant avant la fin du cours :</b> <i>1 Heure </i><br /><br />
	<a href="engine=stopc.php">Sortir du cours</a> (Toute sortie est définitive)</p>');
	}
elseif($_SESSION['action']=="En cours de Combat (2Heures)")
	{
	print('<div id="centre_imperium">
	
	<p id="location">Centre Imp&eacute;rial d\'Entrainement</p>
	
	<p id="textelse"><img src="im_objets/Cours.jpg" width="170" height="150"><br /><br /> <b>Temps restant avant la fin du cours :</b> <i>2 Heure </i><br /><br />
	<a href="engine=stopc.php">Sortir du cours</a> (Toute sortie est définitive)</p>');
	}
elseif($_SESSION['action']=="En cours de Combat (3Heures)")
	{
	print('<div id="centre_imperium">
	
	<p id="location">Centre Imp&eacute;rial d\'Entrainement</p>
	
	<p id="textelse"><img src="im_objets/Cours.jpg" width="170" height="150"><br /><br /> <b>Temps restant avant la fin du cours :</b> <i>3 Heure </i><br /><br />
	<a href="engine=stopc.php">Sortir du cours</a> (Toute sortie est définitive)</p>');
	}
elseif($_SESSION['action']=="En cours de Combat (4Heures)")
	{
	print('<div id="centre_imperium">
	
	<p id="location">Centre Imp&eacute;rial d\'Entrainement</p>
	
	<p id="textelse"><img src="im_objets/Cours.jpg" width="170" height="150"><br /><br /> <b>Temps restant avant la fin du cours :</b> <i>4 Heure </i><br /><br />
	<a href="engine=stopc.php">Sortir du cours</a> (Toute sortie est définitive)</p>');
	}
elseif($_SESSION['action']=="En cours de Tir (1Heure)")
	{
	print('<div id="centre_imperium">
	
	<p id="location">Centre Imp&eacute;rial d\'Entrainement</p>
	
	<p id="textelse"><img src="im_objets/Cours.jpg" width="170" height="150"><br /><br/> <b>Temps restant avant la fin du cours :</b> <i>1 Heure </i><br /><br />
	<a href="engine=stopc.php">Sortir du cours</a> (Toute sortie est définitive)</p>');
	}
elseif($_SESSION['action']=="En cours de Tir (2Heures)")
	{
	print('<div id="centre_imperium">
	
	<p id="location">Centre Imp&eacute;rial d\'Entrainement</p>
	
	<p id="textelse"><img src="im_objets/Cours.jpg" width="170" height="150"><br /><br/> <b>Temps restant avant la fin du cours :</b> <i>2 Heure </i><br /><br />
	<a href="engine=stopc.php">Sortir du cours</a> (Toute sortie est définitive)</p>');
	}
elseif($_SESSION['action']=="En cours de Tir (3Heures)")
	{
	print('<div id="centre_imperium">
	
	<p id="location">Centre Imp&eacute;rial d\'Entrainement</p>
	
	<p id="textelse"><img src="im_objets/Cours.jpg" width="170" height="150"><br /><br/> <b>Temps restant avant la fin du cours :</b> <i>3 Heure </i><br /><br />
	<a href="engine=stopc.php">Sortir du cours</a> (Toute sortie est définitive)</p>');
	}
elseif($_SESSION['action']=="En cours de Tir (4Heures)")
	{
	print('<div id="centre_imperium">
	
	<p id="location">Centre Imp&eacute;rial d\'Entrainement</p>
	
	<p id="textelse"><img src="im_objets/Cours.jpg" width="170" height="150"><br /><br/> <b>Temps restant avant la fin du cours :</b> <i>4 Heure </i><br /><br />
	<a href="engine=stopc.php">Sortir du cours</a> (Toute sortie est définitive)</p>');
	}
elseif($_SESSION['lieu']!="Rue" && $_SESSION['lieu']!="Ruelle" && $_SESSION['num'] >= 0)
	{
	$sql1 = 'SELECT nom,type,ouvert,logo,message,num,rue FROM entreprises_tbl WHERE rue= "'.$_SESSION['lieu'].'" AND num= "'.$_SESSION['num'].'"' ;
	$req1 = mysql_query($sql1);
	$resentreprise = mysql_num_rows($req1);
	$sql = 'SELECT * FROM cerclesliste_tbl WHERE rue= "'.$_SESSION['lieu'].'" AND num= "'.$_SESSION['num'].'"' ;
	$req = mysql_query($sql);
	$res = mysql_num_rows($req);
	if($res!=0)
		{
		$nomcercle = mysql_result($req,0,nom);
		$typecercle = mysql_result($req,0,type);
		$logo = mysql_result($req,0,logo);
		$messagecercle = mysql_result($req,0,description);
		$tractes = mysql_result($req,0,tractes);
		if($_SESSION['code']!=$_SESSION['digicode'])
			{
			print('<div id="centre">
			<p>
			<div align="center"><table width="100%"  border="0">');
			print('<tr><td><div align="center"><img src="'.$logo.'" border="1px" height="100px" width="100px" /></div></td>');
			print('<td><div align="center" class="Style6"><h3>'.$nomcercle.'</h3></div><div align="center" class="emessage">'.$messagecercle.'</div><br>');
			print('<div align="center" class="Style6">');
			if($tractes>0)
				{
				print('<a href="engine=tracte.php">Entrer et prendre un tracte</a><br />');
				}
			print('<a href="engine=doncercle.php">Entrer et faire un don</a><br />');
			if(empty($_SESSION['cercle']))
				{
				print('<a href="engine=adherer.php">Entrer et adhérer au cercle</a></div>');
				}
			if(($_SESSION['case1']=="Carnet") || ($_SESSION['case2']=="Carnet") || ($_SESSION['case3']=="Carnet") || ($_SESSION['case4']=="Carnet") || ($_SESSION['case5']=="Carnet") || ($_SESSION['case6']=="Carnet"))
				{
				$sql = 'SELECT id FROM adresses_tbl WHERE pseudo= "'.$_SESSION['pseudo'].'" AND num= "'.$_SESSION['num'].'" AND rue= "'.$_SESSION['lieu'].'"' ;
				$req = mysql_query($sql);
				$p = mysql_num_rows($req);
				if($p!=1)
					{
					print('<div align="center"><a href="engine=carnet.php?affiche=adresses&ajoutnom='.$nomcercle.'&ajoutnum='.$_SESSION['num'].'&ajoutrue='.$_SESSION['rue'].'">Ajouter au Carnet d\'adresses</a></div>');
					}
				}
			print('<div align="center"><a href="engine=go.php?rue">Revenir dans la rue</a></div>');
			print('<form name="entrer" id="entrer" method="post" action="engine=entrer.php">');
			print('<div align="right" style="margin-top:10px;margin-right:20px;" class="Style6">Acc&egrave;s par digicode : <input name="entrer" tabindex="1" type="password" id="entrer" size="'.strlen($_SESSION['digicode']).'" maxlength="'.strlen($_SESSION['digicode']).'"> <input tabindex="2" type="submit" name="Submit" value="Ok" />');
			
			if(statut() == 7 || ($_SESSION['objet'] == 'Lunettes DI2RCO' && $_SESSION['entreprise'] == 'DI2RCO')) {
				print('<br /><div id="analyseDigicode" class="'.$_SESSION['digicode'].'" onclick="javascript:analyseDigicode(this);">-Lancer l\'analyse-</div><br />');
				
				echo "<script type=\"text/javascript\"> 
				<!--
				function analyseDigicode (div) {
					$(div).attr('onclick','');
					$(div).html('Digicode : ' + $(div).attr('class'));
				}
			//-->
			</script>";
			}
			
			print('</div></form>');
			print('</td></tr></table></div>');
			}
		else
			{
			$budget = mysql_result($req,0,capital);
			
			print('<div id="centre">
			<p>
			<div align="center"><table width="100%"  border="0">');
			print('<tr><td><div align="center"><img src="'.$logo.'" border="1px" height="100px" width="100px" /></div></td>');
			print('<td><h3 align="center">'.$nomcercle.'</h3><br>');
			print('<div align="center" class="Style6">');
			print('<div align="center"><strong>Vous &ecirc;tes &agrave; l\'intérieur du b&acirc;timent.</strong><br><br><i>Voici la liste des actions disponibles :</i><br><br></div><div align="center">');
			if($tractes>0)
				{
				print('<a href="engine=tracte.php">Prendre un tracte</a><br />');
				}
			print('<a href="engine=doncercle.php">Faire un don</a></div>');
			print('<div align="center"><a href="engine=liste.php">Voir la liste des personnes visibles</a></div>');
			print('<div align="center"><a href="engine=invlieu.php">Consulter les objets stoqu&eacute;s ici</a></div>');
			if(($_SESSION['case1']=="Carnet") || ($_SESSION['case2']=="Carnet") || ($_SESSION['case3']=="Carnet") || ($_SESSION['case4']=="Carnet") || ($_SESSION['case5']=="Carnet") || ($_SESSION['case6']=="Carnet"))
				{
				$sql = 'SELECT id FROM adresses_tbl WHERE pseudo= "'.$_SESSION['pseudo'].'" AND num= "'.$_SESSION['num'].'" AND rue= "'.$_SESSION['lieu'].'"' ;
				$req = mysql_query($sql);
				$p = mysql_num_rows($req);
				if($p!=1)
					{
					print('<div align="center"><a href="engine=carnet.php?affiche=adresses&ajoutnom='.$nomcercle.'&ajoutnum='.$_SESSION['num'].'&ajoutrue='.$_SESSION['rue'].'">Ajouter au Carnet d\'adresses</a></div>');
					}
				}
			print('<div align="center"><a href="engine=go.php?rue">Revenir dans la rue</a></div>');
			print('<p align="center">'.$nomcercle.', au capital de '.$budget.' Crédits.</p>');
			print('</td></tr></table></div>');
			}
		}
	elseif($resentreprise!=0)
		{
		$nomment = mysql_result($req1,0,nom);
		$typent = mysql_result($req1,0,type);
		$ouvert = mysql_result($req1,0,ouvert );
		$logo = mysql_result($req1,0,logo);
		$messageent = mysql_result($req1,0,message);
		if($_SESSION['code']!=$_SESSION['digicode'])
			{
			if($typent=="CIE"||$typent=="CIPE"||$typent=="conseil"||$typent=="chambre"||$typent=="DOI"||$typent=="police"||$typent=="prison"||$typent=="jeux"||$typent=="proprete"||$typent=="di2rco"||$typent=="dcn")$typetemp="imperium";
			elseif($typent=="agence immobiliaire") $typetemp="agence";
			elseif($typent=="banque") $typetemp="banque";
			elseif($typent=="bar cafe") $typetemp="bar";
			elseif($typent=="banque") $typetemp="banque";
			elseif($typent=="boutique armes") $typetemp="armurerie";
			elseif($typent=="boutique spécialisee") $typetemp="bazar";
			elseif($typent=="hopital") $typetemp="hopital";
			elseif($typent=="usine de production") $typetemp="usine";
			elseif($typent=="ventes aux encheres") $typetemp="encheres";
			elseif($typent=="centre de recherche") $typetemp="centre";
			elseif($typent=="aucun") $typetemp="autre";
			print('
			<div id="centre_'.$typetemp.'">
	
			<p id="location">Bienvenue ');
			
			if($nomment=="Services techniques de la ville") $nomment="Services de la ville";		// Sinon, ca rentre pas dans le cadre
			
			if($typent=="CIE"||$typent=="CIPE"||$typent=="conseil"||$typent=="dcn")
				print('au ');
			elseif($typent=="proprete")
				print('aux ');
			elseif($typent=="chambre"||$typent=="DOI"||$typent=="police"||$typent=="prison")
				print('&agrave; la ');
			elseif($typent=="jeux")
				print('&agrave; l\' ');
			else
				print('chez ');
			
			print('<span>'.$nomment.'</span></p>
			
			<div id="imglieu"><img src="'.$logo.'" alt="Image du lieu" width="100" height="100" /></div>
			
			<div id="txtlieu">'.preg_replace('#http://[a-z0-9._/-?&=]+#i', '<a href="$0" onclick="window.open(this.href); return false;">$0</a>',$messageent).'</div>');
			if($ouvert=="oui")
				{
				if($typent=="police")
					{
					print('<div id="actions">
						<a href="engine=pleinte.php" class="type1">D&eacute;poser une plainte</a>
						<a href="engine=ppa.php" class="type2">Demander un document officiel</a>
					</div>');
					}
				elseif($typent=="boutique armes")
					{
					print('<div id="actions">
						<a href="engine=boutique.php" class="type1">Regarder les armes propos&eacute;es</a>');
					if(ereg("-",$_SESSION['arme']))
						{
						$sqla = 'SELECT usure FROM armes_tbl WHERE idarme= "'.strstr($_SESSION['arme'],"-").'"';
						$reqa = mysql_query($sqla);
						$resa = mysql_num_rows($reqa);
						if($resa>0)
							{
							if(mysql_result($reqa,0,usure)<100)
								{
								print('<br />
								<a href="engine=atelier.php" class="type2">R&eacute;parer mon arme</a>');
								}
							}
						}
					print('</div>');
					}
				elseif($typent=="proprete")
					{
					$sqlprop = 'SELECT objet FROM principal_tbl WHERE pseudo= "'.$_SESSION['pseudo'].'"' ;
					$reqprop = mysql_query($sqlprop);
					$_SESSION['objet'] = mysql_result($reqprop,0,objet );
					
					print('<div id="actions">
						<a href="engine=proprete.php" class="type1">Regarder l\'&eacute;tat de la ville</a>');
					
					if(($_SESSION['objet']=="Neuvopack1") or ($_SESSION['objet']=="Neuvopack2") or ($_SESSION['objet']=="Neuvopack3") or ($_SESSION['objet']=="Neuvopack4") or ($_SESSION['objet']=="Neuvopack5") or ($_SESSION['objet']=="Neuvopack6") or ($_SESSION['objet']=="Neuvopack7") or ($_SESSION['objet']=="Neuvopack8") or ($_SESSION['objet']=="Neuvopack9") or ($_SESSION['objet']=="Neuvopack10"))
						{
						print('<br />
							<a href="engine=energie.php" class="type2">Entrer et vendre votre &eacute;nergie</a>');
						}
					print('</div>');
					}
				elseif($typent=="boutique spécialisee")
					{
					print('<div id="actions">
						<a href="engine=boutique.php" class="type1">Regarder les objets propos&eacute;s</a>
					</div>');
					}
				elseif($typent=="centre de recherche")
					{
					print('<div id="actions">
						<a href="engine=boutique.php" class="type1">Regarder les objets en vente</a>
					</div>');
					}
				elseif($typent=="agence immobiliaire")
					{
					print('<div id="actions">
						<a href="engine=boutique.php" class="type1">Regarder les offres de ventes</a>
						<a href="engine=listelocations.php" class="type2">Regarder les annonces de location</a>
					</div>');
					}
				elseif($typent=="chambre")
					{
					print('<div id="actions">
						<a href="engine=lois.php" class="type1">Consulter les Lois en vigueur</a>
						<a href="engine=casierperso.php" class="type2">Consulter votre casier judiciaire</a>
					</div>');
					}
				elseif($typent=="CIPE")
					{
					print('<div id="actions">
						<a href="engine=emplois.php" class="type1">Consulter les emplois disponibles</a>
					</div>');
					}
				elseif($typent=="banque")
					{
					print('<div id="actions">
						<a href="engine=banque.php" class="type1">Consulter les comptes</a>
						<a href="engine=ouvrircompte.php" class="type2">Ouvrir un compte</a>
					</div>');
					}
				elseif($typent=="bar cafe")
					{
					print('<div id="actions">
						<a href="engine=boutique.php" class="type1">Aller au comptoir</a>
					</div>');
					}
				elseif($typent=="CIE")
					{
					print('<div id="actions">
						<a href="engine=cie.php" class="type1">Consulter les enseignements</a>
					</div>');
					}
				elseif($typent=="hopital")
					{
					print('<div id="actions">
						<a href="engine=hopital.php" class="type1">Demander un m&eacute;decin</a>
					</div>');
					}
				elseif($typent=="prison")
					{
					print('<div id="actions">
						<a href="engine=prison.php" class="type1">Voir la liste des prisonniers</a>
					</div>');
					}
				elseif($typent=="usine de production")
					{
					print('<div id="actions">
						<a href="engine=usine.php" class="type1">Regarder les stocks</a>
					</div>');
					}
				elseif($typent=="jeux")
					{
					print('<div id="actions">
						<a href="engine=idj.php" class="type1">Entrer dans le casino</a>
					</div>');
					}
				elseif($typent=="conseil")
					{
					print('<div id="actions">
						<a href="engine=archives.php" class="type1">Accéder aux archives</a>
					</div>');
					}
				elseif($typent=="dcn")
					{
					print('<div id="actions">
						<a href="engine=journal.php" class="type1">Accéder au département DC News</a>
						<a href="engine=tv.php" class="type2">Accéder au département DC TV</a>
					</div>');
					}
				else
					{
					print('');
					}
				}
			if($typent=="ventes aux encheres")
				{
				$sqlv = 'SELECT * FROM vente_tbl WHERE enchere= "0" AND buyout= "0" AND acheteur= "'.$_SESSION['pseudo'].'"' ;
				$reqv = mysql_query($sqlv);
				$resv = mysql_num_rows($reqv);
				
				print('<div id="actions">
					<a href="engine=vae.php" class="type1">Regarder les objets entrepos&eacute;s</a>
					<a href="engine=vaedeposer.php" class="type2">D&eacute;poser un objet</a>');
				if($resv>0)
					{
					print('
					<a href="engine=vaerecup.php" class="type3">Voir les objets remport&eacute;s</a>');
					}
				print('</div>');
				}
			if(($_SESSION['case1']=="Carnet") || ($_SESSION['case2']=="Carnet") || ($_SESSION['case3']=="Carnet") || ($_SESSION['case4']=="Carnet") || ($_SESSION['case5']=="Carnet") || ($_SESSION['case6']=="Carnet"))
				{
				$sql = 'SELECT id FROM adresses_tbl WHERE pseudo= "'.$_SESSION['pseudo'].'" AND num= "'.$_SESSION['num'].'" AND rue= "'.$_SESSION['lieu'].'"' ;
				$req = mysql_query($sql);
				$p = mysql_num_rows($req);
				if($p!=1)
					{
					print('
					<a id="ajcarnet2" href="engine=carnet.php?affiche=adresses&ajoutnom='.$nomment.'&ajoutnum='.$_SESSION['num'].'&ajoutrue='.$_SESSION['rue'].'"></a>');
					}
				}
			
			print('
			<div id="acceslieu">
				<p>Acc&egrave;s digicode</p>
				<form name="entrer" id="entrer" method="post" action="engine=entrer.php">
					<p id="champ3"><input tabindex="1" name="entrer" type="password" id="entrer" maxlength="'.strlen($_SESSION['digicode']).'" /></p>
					<p id="valid3"><input tabindex="2" type="submit" name="Submit" value="Entrer" /></p>
				</form>
			</div>');
			}
		else
			{
			$sql = 'SELECT budget FROM entreprises_tbl WHERE nom= "'.$nomment.'"' ;
			$req = mysql_query($sql);
			$res = mysql_num_rows($req);
			if($res!=0)
				{
				$budget = mysql_result($req,0,budget);
				}
			if($typent=="CIE"||$typent=="CIPE"||$typent=="conseil"||$typent=="chambre"||$typent=="DOI"||$typent=="police"||$typent=="prison"||$typent=="jeux"||$typent=="proprete"||$typent=="di2rco"||$typent=="dcn")$typetemp="imperium";
			elseif($typent=="agence immobiliaire") $typetemp="agence";
			elseif($typent=="banque") $typetemp="banque";
			elseif($typent=="bar cafe") $typetemp="bar";
			elseif($typent=="banque") $typetemp="banque";
			elseif($typent=="boutique armes") $typetemp="armurerie";
			elseif($typent=="boutique spécialisee") $typetemp="bazar";
			elseif($typent=="hopital") $typetemp="hopital";
			elseif($typent=="usine de production") $typetemp="usine";
			elseif($typent=="ventes aux encheres") $typetemp="encheres";
			elseif($typent=="centre de recherche") $typetemp="centre";
			elseif($typent=="aucun") $typetemp="autre";
			print('
			<div id="centre_'.$typetemp.'">
	
			<p id="location"><span>'.$nomment.'</span></p>
			
			<div id="imglieu"><img src="'.$logo.'" alt="Image du lieu" width="100" height="100" /></div>
			
			<div id="txtlieu2">Vous &ecirc;tes &agrave; l\'int&eacute;rieur du batiment. Voici les actions disponibles :</div>
			
			<div id="txtlieu"><ul id="actionsdispos">
				<li><a href="engine=liste.php" class="lienactions">Voir la liste des personnes visibles</a></li>');
			if(($_SESSION['action']!="travail") && (ucwords($lieutrav)==ucwords($_SESSION['lieu'])) && ($numtrav==$_SESSION['num']) && ($passif==1))
				{
				print('
				<li><a href="engine=travail.php">Vous mettre au travail</a></li>');
				}
			if(($bdd!="") && (ucwords($lieutrav)==ucwords($_SESSION['lieu'])) && ($numtrav==$_SESSION['num']))
				{
				if($type=="police")
					{
					print('<li><a href="engine=services.php" class="lienactions">Acc&eacute;der &agrave; un ordinateur</a></li>');
					print('<li><a href="engine=pleinte.php" class="lienactions">D&eacute;poser une plainte</a></li>');
					print('<li><a href="engine=ppa.php" class="lienactions">Demander un document officiel</a></li>');
					}
				elseif(($type=="proprete") || ($type=="banque") || ($type=="ventes aux encheres") || ($type=="vente de services") || ($type=="conseil") || ($type=="DOI") || ($type=="prison") || ($type=="chambre") || ($type=="dcn"))
					{
					print('<li><a href="engine=services.php" class="lienactions">Acc&eacute;der &agrave; un ordinateur</a></li>');
					}
				elseif($type=="di2rco")
				    {
				    print('<li><a href="engine=services.php" class="lienactions">Acc&eacute;der &agrave; un ordinateur</a></li>
				    <li><a href="engine=di2rcocour.php" class="lienactions">Acc&eacute;der au chat priv&eacute;</a></li>');
				    }
				elseif(($type=="agence immobiliaire") || ($type=="bar cafe") || ($type=="boutique armes") || ($type=="boutique spécialisee") || ($type=="usine de production"))
					{
					print('<li><a href="engine=stocks.php" class="lienactions">Consulter les stocks</a></li>');
					}
				elseif($type=="centre de recherche")
					{
					print('<li><a href="engine=services.php" class="lienactions">Acc&eacute;der &agrave; un ordinateur</a></li>');
					print('<li><a href="engine=stocks.php" class="lienactions">Consulter les stocks</a></li>');
					}
				elseif($type=="hopital")
					{
					print('<li><a href="engine=stocks.php" class="lienactions">Consulter les prix et stocks</a></li>');
					}
				}
			if($tyy!="chef")
				{
				$sql = 'SELECT poste FROM `e_'.str_replace(" ","_",''.$nomment.'').'_tbl` WHERE type= "chef"' ;
				$req = mysql_query($sql);
				$postechef = mysql_result($req,0,poste);
				$sql = 'SELECT id FROM principal_tbl WHERE type= "'.$postechef.'" AND entreprise= "'.$nomment.'"' ;
				$req = mysql_query($sql);
				$res = mysql_num_rows($req);
				if($res!=0 && $nomment != 'DI2RCO')
					{
					$idchef = mysql_result($req,0,id);
					$sql = 'SELECT pseudo FROM principal_tbl WHERE id= "'.$idchef.'"' ;
					$req = mysql_query($sql);
					$nomchef = mysql_result($req,0,pseudo);
					print('<li><a href="engine=contacter.php?cible='.$nomchef.'" class="lienactions">Envoyer un message &agrave; la direction</a></li>');
					}
				}
			elseif(($_SESSION['action']!="travail") && (ucwords($lieutrav)==ucwords($_SESSION['lieu'])) && ($numtrav==$_SESSION['num']))
				{
				if($typent=="hopital")
					{
					print('<li><a href="engine=travailchef.php" class="lienactions">Travailler en tant que M&eacute;decin</a></li>');
					}
				elseif($typent=="banque")
					{
					print('<li><a href="engine=travailchef.php" class="lienactions">Travailler en tant que Banquier</a></li>');
					}
				elseif(($typent=="usine de production") || ($typent=="ventes aux encheres") || ($typent=="centre de recherche") || ($typent=="boutique armes") || ($typent=="boutique spécialisee") || ($typent=="agence immobiliaire"))
					{
					print('<li><a href="engine=travailchef.php" class="lienactions">Travailler en tant que Vendeur</a></li>');
					}
				elseif($typent=="bar cafe")
					{
					print('<li><a href="engine=travailchef.php" class="lienactions">Travailler en tant que Serveur</a></li>');
					}
				}
			if($ouvert=="oui")
				{
				if($typent=="boutique armes")
					{
					print('<li><a href="engine=boutique.php" class="lienactions">Regarder les armes propos&eacute;es</a></li>');
					if(ereg("-",$_SESSION['arme']))
						{
						$sqla = 'SELECT usure FROM armes_tbl WHERE idarme= "'.strstr($_SESSION['arme'],"-").'"';
						$reqa = mysql_query($sqla);
						$resa = mysql_num_rows($reqa);
						if($resa>0)
							{
							if(mysql_result($reqa,0,usure)<100)
								{
								print('<li><a href="engine=atelier.php" class="lienactions">Faire r&eacute;parer votre arme</a></li>');
								}
							}
						}
					}
				elseif($typent=="proprete")
					{
					print('<li><a href="engine=proprete.php" class="lienactions">Regarder l\'&eacute;tat de la ville</a></li>');
					if(($_SESSION['objet']=="Neuvopack1") or ($_SESSION['objet']=="Neuvopack2") or ($_SESSION['objet']=="Neuvopack3") or ($_SESSION['objet']=="Neuvopack4") or ($_SESSION['objet']=="Neuvopack5") or ($_SESSION['objet']=="Neuvopack6") or ($_SESSION['objet']=="Neuvopack7") or ($_SESSION['objet']=="Neuvopack8") or ($_SESSION['objet']=="Neuvopack9") or ($_SESSION['objet']=="Neuvopack10"))
						{
						print('<li><a href="engine=energie.php" class="lienactions">Vendre votre &eacute;nergie</a></li>');
						}
					}
				elseif($typent=="boutique spécialisee")
					{
					print('<li><a href="engine=boutique.php" class="lienactions">Regarder les objets propos&eacute;s</a></li>');
					}
				elseif($typent=="agence immobiliaire")
					{
					print('<li><a href="engine=boutique.php" class="lienactions">Regarder les logements disponibles</a></li>');
					}
				elseif($typent=="CIPE")
					{
					print('<li><a href="engine=emplois.php" class="lienactions">Consulter les emplois disponibles</a></li>');
					}
				elseif($typent=="centre de recherche")
					{
					print('<li><a href="engine=boutique.php" class="lienactions">Consulter des objets en vente</a></li>');
					}
				elseif($typent=="banque")
					{
					print('<li><a href="engine=banque.php" class="lienactions">Consulter les comptes</a></li>
					<li><a href="engine=ouvrircompte.php" class="lienactions">Cr&eacute;er un compte</a></li>');
					}
				elseif($typent=="bar cafe")
					{
					print('<li><a href="engine=boutique.php" class="lienactions">Aller au comptoir</a></li>');
					}
				elseif($typent=="ventes aux encheres")
					{
					$sqlv = 'SELECT * FROM vente_tbl WHERE enchere= "0" AND buyout= "0" AND acheteur= "'.$_SESSION['pseudo'].'"' ;
					$reqv = mysql_query($sqlv);
					$resv = mysql_num_rows($reqv);
					print('<li><a href="engine=vae.php" class="lienactions">Regarder les objets entrepos&eacute;s</a></li>');
					if($resv>0)
						{
						print('<li><a href="engine=vaerecup.php" class="lienactions">Voir les objets que vous avez remport&eacute;</a></li>');
						}
					}
				elseif($typent=="hopital")
					{
					print('<li><a href="engine=hopital.php" class="lienactions">Demander un m&eacute;decin</a></li>');
					}
				elseif($typent=="prison")
					{
					print('<li><a href="engine=prison.php" class="lienactions">Voir la liste des prisonniers</a></li>');
					}
				elseif($typent=="usine de production")
					{
					print('<li><a href="engine=usine.php" class="lienactions">Regarder les stocks</a></li>');
					}
				elseif($typent=="jeux")
					{
					print('<li><a href="engine=idj.php" class="lienactions">Entrer dans le casino</a></li>');
					}
				else
					{
					print('');
					}
				}
				
			print('<li><a href="engine=invlieu.php" class="lienactions">Consulter les objets stoqu&eacute;s ici</a></li>');
			
			print('</ul>
			<br /><br /><br /><br />'.$nomment.', au capital de '.$budget.' Crédits.</div>');
			
			if(($_SESSION['case1']=="Carnet") || ($_SESSION['case2']=="Carnet") || ($_SESSION['case3']=="Carnet") || ($_SESSION['case4']=="Carnet") || ($_SESSION['case5']=="Carnet") || ($_SESSION['case6']=="Carnet"))
				{
				$sql = 'SELECT id FROM adresses_tbl WHERE pseudo= "'.$_SESSION['pseudo'].'" AND num= "'.$_SESSION['num'].'" AND rue= "'.$_SESSION['lieu'].'"' ;
				$req = mysql_query($sql);
				$p = mysql_num_rows($req);
				if($p!=1)
					{
					print('<a id="ajcarnet2" href="engine=carnet.php?affiche=adresses&ajoutnom='.$nomment.'&ajoutnum='.$_SESSION['num'].'&ajoutrue='.$_SESSION['rue'].'"></a>');
					}
				}
			if((($_SESSION['objet']=="Neuvopack") or ($_SESSION['objet']=="Neuvopack1") or ($_SESSION['objet']=="Neuvopack2") or ($_SESSION['objet']=="Neuvopack3") or ($_SESSION['objet']=="Neuvopack4") or ($_SESSION['objet']=="Neuvopack5") or ($_SESSION['objet']=="Neuvopack6") or ($_SESSION['objet']=="Neuvopack7") or ($_SESSION['objet']=="Neuvopack8") or ($_SESSION['objet']=="Neuvopack9")) && ($_SESSION['action']!="Recherche de cristaux"))
				{
				print('
				<a id="collecte" href="engine=recherchec.php"></a>');
				}
			}
		}
	elseif($nomrue=='special')
		{
			
		$sql = 'SELECT nom,logo,message,type FROM lieux_speciaux_tbl WHERE num= "'.$_SESSION['num'].'" AND rue= "'.$_SESSION['lieu'].'"' ;
		$req = mysql_query($sql);
		$res = mysql_num_rows($req);
		
		if(mysql_result($req,0,type)=="recreation")
			{
			print('<div id="centre">
				<div style="position:relative;float:left;margin-left:20px;"><img src="'.mysql_result($req,0,logo).'" /></div>
				<h3 style="margin-top:20px;margin-bottom:5px;">'.mysql_result($req,0,nom).'</h3>
				<div>'.mysql_result($req,0,message).'</div>
				<br /><br /><br />
				<div align="center"><a href="engine=liste.php">Voir la liste des personnes visibles</a></div>
				<div align="center"><a href="engine=go.php?rue">Revenir dans la rue</a></div>
				');
			}
		elseif(mysql_result($req,0,type)=="cryo")
			{
			print('<div id="centre">
				<div style="position:relative;float:left;margin-left:20px;"><img src="'.mysql_result($req,0,logo).'" /></div>
				<h3 style="margin-top:20px;margin-bottom:5px;">'.mysql_result($req,0,nom).'</h3>
				<div>'.mysql_result($req,0,message).'</div>
				<br /><br /><br />
				<div align="center"><a href="engine=vacances.php">Me cryogéniser</a></div>
				<div align="center"><a href="engine=liste.php">Voir la liste des personnes visibles</a></div>
				<div align="center"><a href="engine=go.php?rue">Revenir dans la rue</a></div>
				');
			}
		elseif(mysql_result($req,0,type)=="repos")
			{
			print('<div id="centre">
				<div style="position:relative;float:left;margin-left:20px;margin-right:10px;"><img style="border:1px solid #333;" src="'.mysql_result($req,0,logo).'" /></div>
				<h3 style="position:relative;margin-top:20px;margin-bottom:5px;">'.mysql_result($req,0,nom).'</h3>
				<div style="position:relative;padding:0 10px;">'.mysql_result($req,0,message).'</div>
				<br /><br /><br />
				'.(($_SESSION["action"]=="repos")?'':'<div align="center"><a href="engine=sereposer.php">Se reposer</a></div>').'
				<div align="center"><a href="engine=liste.php">Voir la liste des personnes visibles</a></div>
				<div align="center"><a href="engine=go.php?rue">Revenir dans la rue</a></div>
				');
			}
		elseif(mysql_result($req,0,type)=="cimetiere")
			{
			print('<div id="centre">
				<div style="position:relative;float:left;margin-left:25px;"><img style="border:1px solid #333;" src="'.mysql_result($req,0,logo).'" /></div>
				<h3 style="margin-top:20px;margin-bottom:5px;">'.mysql_result($req,0,nom).'</h3>
				<div style="padding:0 15px 0 15px;">'.mysql_result($req,0,message).'</div>
				<br /><br /><br /><br /><br />
				<div align="center"><a href="engine=cimetiere.php">Se promener parmi les tombes</a></div>
				<div align="center"><a href="engine=liste.php">Voir la liste des personnes visibles</a></div>
				<div align="center"><a href="engine=go.php?rue">Revenir dans la rue</a></div>
				');
			}
		elseif(mysql_result($req,0,type)=="decapaciteur")
			{
			print('<div id="centre">
				<div style="position:relative;float:left;margin-left:20px;"><img src="'.mysql_result($req,0,logo).'" /></div>
				<h3 style="margin-top:20px;margin-bottom:5px;">'.mysql_result($req,0,nom).'</h3>
				<div>'.mysql_result($req,0,message).'</div>
				<br /><br /><br />
				<div align="center"><a href="engine=dome.php">Entrer dans le Dôme</a></div>
				<div align="center"><a href="engine=liste.php">Voir la liste des personnes visibles</a></div>
				<div align="center"><a href="engine=go.php?rue">Revenir dans la rue</a></div>
				');
			}
		elseif(mysql_result($req,0,type)=="stats")
			{
			print('<div id="centre">
				<div style="position:relative;float:left;margin-left:20px;"><img src="'.mysql_result($req,0,logo).'" /></div>
				<h3 style="margin-top:20px;margin-bottom:5px;">'.mysql_result($req,0,nom).'</h3>
				<div>'.mysql_result($req,0,message).'<br />
				<div style="text-align:left;margin-top:5px;">
					&nbsp;&nbsp;&nbsp;&raquo; <a href="engine=stats-publiques.php?a=1">Statistiques sur les nouveaux arrivants</a><br />
				</div>
				</div>
				<br /><br /><br />
				<div align="center"><a href="engine=liste.php">Voir la liste des personnes visibles</a></div>
				<div align="center"><a href="engine=go.php?rue">Revenir dans la rue</a></div>
				');
			}
		elseif(mysql_result($req,0,type)=="chateau")
			{
			print('<div id="centre">
				<div style="position:relative;float:left;margin-left:20px;"><img src="'.mysql_result($req,0,logo).'" /></div>
				<h3 style="margin-top:20px;margin-bottom:5px;">'.mysql_result($req,0,nom).'</h3>
				<div>'.mysql_result($req,0,message).'</div>
				<br /><br /><br />');
				
				if($_SESSION['code']!=$_SESSION['digicode'])
					{
					print('<form name="entrer" id="entrer" method="post" action="engine=entrer.php">');
					print('<div align="center" class="Style6">Acc&egrave;s par digicode : <input tabindex="1" name="entrer" type="password" id="entrer" size="'.strlen($_SESSION['digicode']).'" maxlength="'.strlen($_SESSION['digicode']).'"> <input tabindex="2" type="submit" name="Submit" value="Ok" />');					
					print('</div></form>');
					}
				else
					{
					print('<div align="center"><a href="engine=liste.php">Voir la liste des personnes visibles</a></div>');
					}
				
				
				print('
				<div align="center"><a href="engine=go.php?rue">Revenir dans la rue</a></div>
				');
			}
		elseif(mysql_result($req,0,type)=="annonce")
			{
			print('<div id="centre" style="position:relative;bottom:5px;width:490px;height:285px;overflow:auto;">
				<h3 style="position:relative;margin-top:20px;margin-bottom:5px;">'.mysql_result($req,0,nom).'</h3>');
				
				if (est_un_DI($_SESSION['entreprise'],$_SESSION['poste'])) {
				
				    if ($_POST['oi_annonce'] != "") {
				        $sqlx = 'INSERT INTO annonces_officielles_tbl(titre,oi,date,texte) VALUES ("'.htmlentities($_POST['titre_annonce']).'","'.$_POST['oi_annonce'].'",'.time().',"'.stripslashes(htmlentities($_POST['contenu_annonce'])).'")';
        				mysql_query($sqlx);
				    }
				
				    print('<a href="#" onclick="$(\'#nouvelle_annonce\').show();">Placarder une annonce</a><br /><br />
				    <form action="#" method="post" id="nouvelle_annonce" style="display:none;">
				        <input type="hidden" name="oi_annonce" value="'.((statut($_SESSION['statut']) == 7)?'Haut Conseil de Dreadcast':$_SESSION['entreprise']).'" />
				        Titre : <input type="text" name="titre_annonce" value="" /><br />
				        Contenu :<br />
				        <textarea name="contenu_annonce" style="width:400px;height:150px;"></textarea><br />
				        <input type="submit" name="submit_annonce" value="Valider" />
				    </form>');
				}
				
				print('<div style="position:relative;margin-left:0px;margin-top:10px;margin-bottom:10px;"><img style="border:1px solid #333;" src="'.mysql_result($req,0,logo).'" /></div>
				<div style="position:relative;padding:0 10px;">'.mysql_result($req,0,message).'</div>
				<br />');
				
				$where = ($_GET['id'] != "")?'WHERE id='.$_GET['id']:'';
				
				$sql = 'SELECT * FROM annonces_officielles_tbl '.$where.' ORDER BY id DESC LIMIT 1';
				$req = mysql_query($sql);
				$res = mysql_num_rows($req);
				
				if($res) {
					$id = mysql_result($req,0,id);
					$titre = mysql_result($req,0,titre);
					$oi = mysql_result($req,0,oi);
					$date = mysql_result($req,0,date);
					$texte = nl2br(mysql_result($req,0,texte));
					
					$oi = ($oi=='Haut Conseil de Dreadcast' || $oi=='Conseil Imperial' || $oi=='CIPE' || $oi=='CIE' || $oi=='Chambre des lois' || $oi=='DI2RCO')?'du '.$oi:(
						  ($oi=='DOI' || $oi=='DOI' || $oi=='Prison' || $oi=='Police' || $oi=='DC Network')?'de la '.$oi:'des '.$oi
					);
					
					print('<div style="border:1px solid #777;background:#bbb;margin-left:10px;margin-right:10px;margin-bottom:10px;padding:10px;"><strong>Annonce officielle '.$oi.'</strong><br />
					<strong style="font-size:1.2em;">'.$titre.'</strong><br /><br />
					'.$texte.'</div>');
					
					$sql = 'SELECT id,titre,oi FROM annonces_officielles_tbl WHERE id != '.$id.' ORDER BY id DESC';
					$req = mysql_query($sql);
					$res = mysql_num_rows($req);
					
					for($i=0;$i<$res;$i++) print('<a href="engine.php?id='.mysql_result($req,$i,id).'">['.mysql_result($req,$i,oi).'] '.mysql_result($req,$i,titre).'</a><br />');
					print('<br /><br /><br />');
				}
				
				print((($_SESSION["action"]=="repos")?'':'<div align="center"><a href="engine=sereposer.php">Se reposer</a></div>').'
				<div align="center"><a href="engine=liste.php">Voir la liste des personnes visibles</a></div>
				<div align="center"><a href="engine=go.php?rue">Revenir dans la rue</a></div><br />
				');
			}
		elseif(mysql_result($req,0,type)=="accueil")
			{
			print('<div id="centre">
				<div style="position:relative;float:left;margin-left:20px;margin-right:10px;"><img src="'.mysql_result($req,0,logo).'" style="border:2px solid #222;"/></div>
				<h3 style="margin-top:20px;margin-bottom:5px;">'.mysql_result($req,0,nom).'</h3>
				<div style="padding: 0 10px; 0 20px">
					'.mysql_result($req,0,message).'
				</div>
				<br /><br />
				<div align="center"><a href="engine=liste.php">Voir la liste des personnes visibles</a></div>
				<div align="center"><a href="engine=go.php?rue">Revenir dans la rue</a></div>
				');
			}
		elseif(mysql_result($req,0,type)=="fête")
			{
			print('<div id="centre">
				<div style="position:relative;float:left;margin-left:20px;margin-right:10px;"><img src="'.mysql_result($req,0,logo).'" style="border:2px solid #222;"/></div>
				<h3 style="margin-top:20px;margin-bottom:5px;">'.mysql_result($req,0,nom).'</h3>
				<div style="padding: 0 10px; 0 20px">
					'.mysql_result($req,0,message).'
				</div>
				<br /><br />
				<div align="center"><a href="engine=liste.php">Voir la liste des personnes visibles</a></div>
				<div align="center"><a href="engine=go.php?rue">Revenir dans la rue</a></div>
				');
			}
		
		
		if(($_SESSION['case1']=="Carnet") || ($_SESSION['case2']=="Carnet") || ($_SESSION['case3']=="Carnet") || ($_SESSION['case4']=="Carnet") || ($_SESSION['case5']=="Carnet") || ($_SESSION['case6']=="Carnet"))
			{
			$sql = 'SELECT id FROM adresses_tbl WHERE pseudo= "'.$_SESSION['pseudo'].'" AND num= "'.$_SESSION['num'].'" AND rue= "'.$_SESSION['lieu'].'"' ;
			$req = mysql_query($sql);
			$p = mysql_num_rows($req);
			if($p!=1)
				{
				print('<div align="center"><a href="engine=carnet.php?affiche=adresses&ajoutnom='.$nomcercle.'&ajoutnum='.$_SESSION['num'].'&ajoutrue='.$_SESSION['rue'].'">Ajouter au Carnet d\'adresses</a></div>');
				}
			}
		}
	else
		{
		print('<div id="centre">
		<table width="100%"><tr>');
		if($_SESSION['code']!=$_SESSION['digicode'])
			{
			if($nomrue=="Appartement 17m")
				{
				print('<div align="center">Vous vous trouvez devant un grand immeuble délabré probablement habité par des ouvriers de notre société. <br>A l\'intérieur, les appartements semblent être très petits.</div>');
				}
			elseif($nomrue=="Appartement 32m")
				{
				print('<div align="center">Vous vous trouvez devant un immeuble nouvellement construit. <br>Il semblerait qu\'il soit principalement habité par de riches étudiants.</div>');
				}
			elseif($nomrue=="Appartement 56m")
				{
				print('<div align="center">Vous vous trouvez devant un très grand immeuble flambant neuf. <br>Les populations les occupant sont très différentes...</div>');
				}
			elseif($nomrue=="Appartement 140m")
				{
				print('<div align="center">Vous êtes au pied d\'une tour gigantesque. La ville en est recouverte mais seuls les nouveaux riches peuvent se permettre un tel luxe.</div>');
				}
			elseif($nomrue=="Maison 90m")
				{
				print('<div align="center">Vous vous trouvez devant une petite maison avec jardin.<br> L\'intérieur semble être confortable.</div>');
				}
			elseif($nomrue=="Maison 140m")
				{
				print('<div align="center">Vous vous situez devant une habitation peu courante car de plus en plus difficile à trouver. <br>Tout son charme réside dans sa longue histoire.</div>');
				}
			elseif($nomrue=="Maison 210m")
				{
				print('<div align="center">Vous vous trouvez devant une grande maison nouvellement bâtie.<br>Les murs sont bas et le design général fait penser à un ancien chalet.</div>');
				}
			elseif($nomrue=="Maison 335m")
				{
				print('<div align="center">Cette immense habitation fait plus penser à un chateau.<br> On ose pas immaginer combien de chambres elle peut bien contenir...</div>');
				}
			elseif($nomrue=="Maison 405m")
				{
				print('<div align="center">Vous vous trouvez devant une grande maison de maître.<br> Le jardin est gigantesque et l\'intérieur semble être richement aménagé.</div>');
				}
			elseif($nomrue=="Villa et piscine")
				{
				print('<div align="center">Vous vous trouvez devant une villa magnifique possédant une piscine, un terrain de tennis, un golf et plusieurs hectares de jardin.</div>');
				}
			}
		print('<br><br></tr><td width="30%">');
		if($nomrue=="Appartement 17m")
			{
			print('<div align="center"><img src="im_objets/a17m_small.jpg"></div>');
			}
		elseif($nomrue=="Appartement 32m")
			{
			print('<div align="center"><img src="im_objets/a32m_small.jpg"></div>');
			}
		elseif($nomrue=="Appartement 56m")
			{
			print('<div align="center"><img src="im_objets/a56m_small.jpg"></div>');
			}
		elseif($nomrue=="Appartement 140m")
			{
			print('<div align="center"><img src="im_objets/a140m_small.jpg"></div>');
			}
		elseif($nomrue=="Maison 90m")
			{
			print('<div align="center"><img src="im_objets/m90m_small.jpg"></div>');
			}
		elseif($nomrue=="Maison 140m")
			{
			print('<div align="center"><img src="im_objets/m100m_small.jpg"></div>');
			}
		elseif($nomrue=="Maison 210m")
			{
			print('<div align="center"><img src="im_objets/m210m_small.jpg"></div>');
			}
		elseif($nomrue=="Maison 335m")
			{
			print('<div align="center"><img src="im_objets/m335m_small.jpg"></div>');
			}
		elseif($nomrue=="Maison 405m")
			{
			print('<div align="center"><img src="im_objets/m405m_small.jpg"></div>');
			}
		elseif($nomrue=="Villa et piscine")
			{
			print('<div align="center"><img src="im_objets/vp_small.jpg"></div>');
			}
		print('</td><td width="70%">');
		if($_SESSION['code']!=$_SESSION['digicode'])
			{
			print('<form name="entrer" id="entrer" method="post" action="engine=entrer.php">');
			print('<div align="center" class="Style6">Acc&egrave;s par digicode : <input tabindex="1" name="entrer" type="password" id="entrer" size="'.strlen($_SESSION['digicode']).'" maxlength="'.strlen($_SESSION['digicode']).'"> <input tabindex="2" type="submit" name="Submit" value="Ok" />');
			
			if(statut() == 7 || ($_SESSION['objet'] == 'Lunettes DI2RCO' && $_SESSION['entreprise'] == 'DI2RCO')) {
				print('<br /><div id="analyseDigicode" class="'.$_SESSION['digicode'].'" onclick="javascript:analyseDigicode(this);">-Lancer l\'analyse-</div><br />');
				
				echo "<script type=\"text/javascript\"> 
				<!--
				function analyseDigicode (div) {
					$(div).html('Digicode : ' + $(div).attr('class'));
					$(div).attr('onclick','');
				}
			//-->
			</script>";
			}
			
			print('</div></form>');
			if(($_SESSION['case1']=="Carnet") || ($_SESSION['case2']=="Carnet") || ($_SESSION['case3']=="Carnet") || ($_SESSION['case4']=="Carnet") || ($_SESSION['case5']=="Carnet") || ($_SESSION['case6']=="Carnet"))
				{
				$sql = 'SELECT id FROM adresses_tbl WHERE pseudo= "'.$_SESSION['pseudo'].'" AND num= "'.$_SESSION['num'].'" AND rue= "'.$_SESSION['lieu'].'"' ;
				$req = mysql_query($sql);
				$p = mysql_num_rows($req);
				if($p!=1)
					{
					print('<div align="center"><a href="engine=carnet.php?affiche=adresses&ajoutnum='.$_SESSION['num'].'&ajoutrue='.$_SESSION['rue'].'">Ajouter au Carnet d\'adresses</a></div>');
					}
				}
			print('<div align="center"><a href="engine=go.php?rue">Revenir dans la rue</a></div>');
			}
		else
			{
			print('<div align="center"><strong>Vous &ecirc;tes &agrave; l\'intérieur du b&acirc;timent.</strong><br><br><i>Voici la liste des actions disponibles :</i><br><br></div>');
			if($_SESSION['action']!="repos")
				{
				print('<div align="center"><a href="engine=sereposer.php">Vous reposer</a></div>');
				}
			print('<div align="center"><a href="engine=liste.php">Voir la liste des personnes visibles</a></div>');
			if((($_SESSION['objet']=="Neuvopack") or ($_SESSION['objet']=="Neuvopack1") or ($_SESSION['objet']=="Neuvopack2") or ($_SESSION['objet']=="Neuvopack3") or ($_SESSION['objet']=="Neuvopack4") or ($_SESSION['objet']=="Neuvopack5") or ($_SESSION['objet']=="Neuvopack6") or ($_SESSION['objet']=="Neuvopack7") or ($_SESSION['objet']=="Neuvopack8") or ($_SESSION['objet']=="Neuvopack9")) && ($_SESSION['action']!="Recherche de cristaux"))
				{
				print('<div align="center"><a href="engine=recherchec.php">Rechercher des cristaux</a></div>');
				}
			print('<div align="center"><a href="engine=invlieu.php">Consulter les objets stoqu&eacute;s ici</a></div>');
			if(($_SESSION['case1']=="Carnet") || ($_SESSION['case2']=="Carnet") || ($_SESSION['case3']=="Carnet") || ($_SESSION['case4']=="Carnet") || ($_SESSION['case5']=="Carnet") || ($_SESSION['case6']=="Carnet"))
				{
				$sql = 'SELECT id FROM adresses_tbl WHERE pseudo= "'.$_SESSION['pseudo'].'" AND num= "'.$_SESSION['num'].'" AND rue= "'.$_SESSION['lieu'].'"' ;
				$req = mysql_query($sql);
				$p = mysql_num_rows($req);
				if($p!=1)
					{
					print('<div align="center"><a href="engine=carnet.php?affiche=adresses&ajoutnum='.$_SESSION['num'].'&ajoutrue='.$_SESSION['rue'].'">Ajouter au Carnet d\'adresses</a></div>');
					}
				}
			print('<div align="center"><a href="engine=go.php?rue">Revenir dans la rue</a></div>');
			}
		print('</td></tr></table>');
		}
	}
elseif($_SESSION['lieu']=="Rue" || $_SESSION['num']<0)
	{
	
	print('
	<div id="centre_rue">
	
	<p id="location">Vous marchez le long des rues de la cit&eacute;.<br />Faites attention aux voleurs et aux assassins qui r&ocirc;dent par ici.</p>');
	
	if((($_SESSION['objet']=="Neuvopack") or ($_SESSION['objet']=="Neuvopack1") or ($_SESSION['objet']=="Neuvopack2") or ($_SESSION['objet']=="Neuvopack3") or ($_SESSION['objet']=="Neuvopack4") or ($_SESSION['objet']=="Neuvopack5") or ($_SESSION['objet']=="Neuvopack6") or ($_SESSION['objet']=="Neuvopack7") or ($_SESSION['objet']=="Neuvopack8") or ($_SESSION['objet']=="Neuvopack9")) && ($_SESSION['action']!="Recherche de cristaux"))
		{
		print('
		<a href="engine=recherchec.php" id="collecte"></a>');
		}
	
	print('
	<div id="actions">
		');
		if($_SESSION['action']!="repos")
			{
			print('<p class="type1"><a href="engine=sereposer.php">Se reposer</a></p>
			<p class="type2"><a href="engine=liste.php">Regarder autour</a></p>
			<p class="type3"><a href="engine=carte.php">Regarder la carte de Dreadcast</a></p>');
			}
		else
			{
			print('<p class="type1"><a href="engine=liste.php">Regarder autour</a></p>
			<p class="type2"><a href="engine=carte.php">Regarder la carte de Dreadcast</a></p>');
			
			}
		print('
		
		
		
		<form name="form1" method="post" action="" class="type4">
			<select onChange="MM_jumpMenu('); print("'parent'"); print(',this,0)" name="recherche" id="select2">
				<option value="#" select="selected" class="hop2"> </option>
				<option value="#" class="hope">Entreprises</option>
				<option value="engine=recherche.php?agence immobiliaire" class="rechagence">&#149; Agence immobilière</option>
				<option value="engine=recherche.php?banque" class="rechbanque">&#149; Banque</option>
				<option value="engine=recherche.php?bar cafe" class="rechbar">&#149; Bar - Caf&eacute;</option>
				<option value="engine=recherche.php?boutique armes" class="recharmurerie">&#149; Boutique d\'armes</option>
				<option value="engine=recherche.php?boutique spécialisee" class="rechbazar">&#149; Boutique sp&eacute;cialis&eacute;e</option>
				<option value="engine=recherche.php?centre de recherche" class="rechbazar">&#149; Centre de recherche</option>
				<option value="engine=recherche.php?hopital" class="rechhopital">&#149; Hôpital - Clinique</option>
				<option value="engine=recherche.php?usine de production" class="rechusine">&#149; Usine de production</option>
				<option value="engine=recherche.php?ventes aux encheres" class="rechencheres">&#149; Ventes aux ench&egrave;res</option>
				<option value="#" class="hopo">Organisations Impériales</option>
				<option value="engine=recherche.php?CIE">&#149; Centre Impérial d\'Enseignement</option>
				<option value="engine=recherche.php?CIPE">&#149; Centre d\'Information Pour l\'Emploi</option>
				<option value="engine=recherche.php?chambre">&#149; Chambre des lois</option>
				<option value="engine=recherche.php?conseil">&#149; Conseil Impérial</option>
				<option value="engine=recherche.php?DOI">&#149; Direction des Organisations Imp&eacute;riales</option>
				<option value="engine=recherche.php?police">&#149; Police</option>
				<option value="engine=recherche.php?prison">&#149; Prison de la ville</option>
				<option value="engine=recherche.php?dcn">&#149; DreadCast Network</option>
				<option value="engine=recherche.php?jeux">&#149; Impériale des jeux</option>
				<option value="engine=recherche.php?proprete">&#149; Services techniques de la ville</option>
				<!--<option value="engine=recherche.php?transports">Transports publics</option>-->
			</select>
			<span>O&ugrave; souhaitez-vous<br />aller ?</span>
		</form>
	</div>');
	}
elseif($_SESSION['lieu']=="Ruelle")
	{
	print('
	<div id="centre_rue">
	
	<p id="location2">Vous êtes caché dans une petite ruelle de la ville.<br />Vous êtes ici à l\'abri des voleurs et assassins.<br />Ce n\'est cependant pas un endroit très confortable.</p>');
	
	if((($_SESSION['objet']=="Neuvopack") or ($_SESSION['objet']=="Neuvopack1") or ($_SESSION['objet']=="Neuvopack2") or ($_SESSION['objet']=="Neuvopack3") or ($_SESSION['objet']=="Neuvopack4") or ($_SESSION['objet']=="Neuvopack5") or ($_SESSION['objet']=="Neuvopack6") or ($_SESSION['objet']=="Neuvopack7") or ($_SESSION['objet']=="Neuvopack8") or ($_SESSION['objet']=="Neuvopack9")) && ($_SESSION['action']!="Recherche de cristaux"))
		{
		print('<a href="engine=recherchec.php" id="collecte"></a>');
		}
	
	print('<div id="actions">
		');
		if($_SESSION['action']!="repos")
			{
			print('<p class="type2"><a href="engine=sereposer.php">Se reposer</a></p>
			<p class="type3"><a href="engine=go.php?rue">Revenir dans les rues principales</a></p>
			</div>');
		}
		else
			{
			print('<p class="type2"><a href="engine=go.php?rue">Revenir dans les rues principales</a></p>
			</div>');
			}
			
	}

if($_SESSION['statut'] == "Debutant" && $_SESSION['tutorial']!="vu")
	{
	affiche_box_tuto();
	$_SESSION['tutorial']="vu";
	}

mysql_close($db);
?> 



</div>

<?php if($chat=="oui" || $_SESSION['num']<0) 	{ if ((date("H")>=21) || (date("H")<=7)) { include("inc_bas_n_c.php"); } else { include("inc_bas_j_c.php"); } } else {	if ((date("H")>=21) || (date("H")<=7)) { include("inc_bas_n.php"); } else { include("inc_bas_j.php"); }	} ?>
<?php include("inc_bas_de_page.php"); ?>
