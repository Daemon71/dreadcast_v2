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
			Services
		</div>
		<b class="module4ie"><a href="<?php
		if($_GET['lieu'] == "dcn" && $_GET['action']!="") print('engine=services.php?lieu=dcn');
		elseif($_GET['lieu'] == "dcn") print('engine=services.php');
		elseif($_GET['lieu'] == "dctv" && $_GET['action']!="") print('engine=services.php?lieu=dctv');
		elseif($_GET['lieu'] == "dctv") print('engine=services.php');
		else print('engine=gestion.php');
		?>" class="module4"><img src="im_objets/icon_retour.gif" alt="Retour" />Retour</a></b>
</p>
	</div>
</div>
<div id="centre">
<div class="messagesvip">

<?php 

$db = mysql_connect('localhost', 'CENSURE', 'CENSURE') or die("Erreur de connexion au serveur des messages.");
mysql_select_db('CENSURE',$db) or die("Erreur de connexion a la base de donnees des messages.");

$sql = 'SELECT type,entreprise,points FROM principal_tbl WHERE id= "'.$_SESSION['id'].'"' ;
$req = mysql_query($sql);
$_SESSION['poste'] = mysql_result($req,0,type); 
$_SESSION['entreprise'] = mysql_result($req,0,entreprise); 
$_SESSION['points'] = mysql_result($req,0,points); 

$sql = 'SELECT bdd FROM `e_'.str_replace(" ","_",''.$_SESSION['entreprise'].'').'_tbl` WHERE poste= "'.$_SESSION['poste'].'"' ;
$req = mysql_query($sql);
$bdd = mysql_result($req,0,bdd); 

$sql = 'SELECT type,num,rue FROM entreprises_tbl WHERE nom= "'.$_SESSION['entreprise'].'"' ;
$req = mysql_query($sql);
$type = mysql_result($req,0,type);
$rueent = mysql_result($req,0,rue); 
$nument = mysql_result($req,0,num); 

if($bdd=="") { print('<meta http-equiv="refresh" content="0 ; url=http://v2.dreadcast.net/ingame/engine.php"> '); exit(); } if(($type!="vente de services") && ($type!="CIE") && ($type!="proprete") && ($type!="CIPE") && ($type!="banque") && ($type!="DOI") && ($type!="conseil") && ($type!="chambre") && ($type!="prison") && ($type!="ventes aux encheres") && ($type!="police") && ($type!="di2rco") && ($type!="centre de recherche") && ($type!="dcn")) { print('<meta http-equiv="refresh" content="0 ; url=http://v2.dreadcast.net/ingame/engine.php"> '); exit(); }

if((ucwords($_SESSION['rue'])!=ucwords($rueent)) || ($_SESSION['num']!=$nument))
	{
	print('<p align="center"><strong>Vous devez &ecirc;tre sur place pour pouvoir acc&eacute;der &agrave; cette rubrique.</strong></p>'); 
	$l = 1;
	}

$action = $_GET['nomment'];
$tyy = $_GET['type'];

if(($type=="banque") && ($l!=1))
	{
	$sql = 'SELECT nombre FROM stocks_tbl WHERE entreprise= "'.$_SESSION['entreprise'].'"' ;
	$req = mysql_query($sql);
	$pourc = mysql_result($req,0,nombre);
	print('<form name="form1" method="post" action="engine=pourcf.php"><div align="center"><p><strong>Fixer le pourcentage de prise sur les transactions à :</strong> <input value="'.$pourc.'" name="pourc" type="text" id="pourc" size="1" maxlength="1"> % </p><p><input type="submit" name="Submit" value="Valider"></p></div></form>');
	}
elseif(($type=="ventes aux encheres") && ($l!=1))
	{
	$sql = 'SELECT nombre FROM stocks_tbl WHERE entreprise= "'.$_SESSION['entreprise'].'"' ;
	$req = mysql_query($sql);
	$pourc = mysql_result($req,0,nombre);
	print('<form name="form1" method="post" action="engine=pourcf.php"><div align="center"><p><strong>Fixer le pourcentage retenu sur les ventes à :</strong> <input value="'.$pourc.'" name="pourc" type="text" id="pourc" size="1" maxlength="1"> % </p><p><input type="submit" name="Submit" value="Valider"></p></div></form>');
	}
elseif(($type=="conseil") && ($l!=1))
	{
	$sql1 = 'SELECT * FROM candidatures_tbl WHERE ( entreprise= "DI2RCO" AND poste="Directeur du DI2RCO" ) OR ( entreprise= "Services techniques de la ville" AND poste="Directeur des services" ) OR ( entreprise= "Police" AND poste="Chef de la police" ) OR ( entreprise= "Prison" AND poste="Directeur de la prison" ) OR ( entreprise= "DOI" AND poste="Directeur des Organisations" ) OR ( entreprise= "Chambre des lois" AND poste="Premier Consul" ) OR ( entreprise= "CIE" AND poste="Directeur du CIE" ) OR ( entreprise= "CIPE" AND poste="Directeur du CIPE" ) OR ( entreprise= "DC Network" AND poste="Directeur du DC Network" ) ORDER BY poste' ;
	$req1 = mysql_query($sql1);
	$res1 = mysql_num_rows($req1);
	
	if($res1!=0)
		{
		print('<table width="90%"  border="1" align="center" cellpadding="0" cellspacing="0">
					<tr bgcolor="#B6B6B6">
					  <th scope="col"><div align="center">Candidature de</div></th>
					  <th scope="col"><div align="center">Poste désiré</div></th>
					  <th scope="col"><div align="center">Institution</div></th>
					  <th scope="col"><div align="center">Compétences</div></th>
					</tr>');
		
		for($i=0; $i != $res1 ; $i++) 
			{ 
			$idm = mysql_result($req1,$i,id);
			$nom = mysql_result($req1,$i,nom);
			$poste = mysql_result($req1,$i,poste);
			$institution = mysql_result($req1,$i,entreprise);
			print('<tr>
					  <td><div align="center">'.$nom.'</div></td>
					  <td><div align="center"><a href="engine=voircanddir.php?id='.$idm.'">'.$poste.'</a></div></td>
					  <td><div align="center">'.$institution.'</td>
					  <td><div align="center">');
						$sql = 'SELECT id FROM principal_tbl WHERE pseudo= "'.$nom.'"' ;
						$req = mysql_query($sql);
						$idc = mysql_result($req,0,id);
						$sql = 'SELECT combat,observation,gestion,maintenance,mecanique,service,discretion,economie,resistance,tir,vol,medecine FROM principal_tbl WHERE id= "'.$idc.'"' ;
						$req = mysql_query($sql);
						$combatc = mysql_result($req,0,combat);
						$observationc = mysql_result($req,0,observation);
						$gestionc = mysql_result($req,0,gestion);
						$maintenancec = mysql_result($req,0,maintenance);
						$mecaniquec = mysql_result($req,0,mecanique);
						$servicec = mysql_result($req,0,service);
						$discretionc = mysql_result($req,0,discretion);
						$economiec = mysql_result($req,0,economie);
						$resistancec = mysql_result($req,0,resistance);
						$tirc = mysql_result($req,0,tir);
						$volc = mysql_result($req,0,vol);
						$medecinec = mysql_result($req,0,medecine);
						if($observationc==100)
							{
							print('<img src="im_objets/etoile.gif" border="0" title="Expert en observation">');
							}
						if($maintenancec==100)
							{
							print('<img src="im_objets/etoile.gif" border="0" title="Expert en maintenance">');
							}
						if($gestionc==100)
							{
							print('<img src="im_objets/etoile.gif" border="0" title="Expert en gestion">');
							}
						if($mecaniquec==100)
							{
							print('<img src="im_objets/etoile.gif" border="0" title="Expert en mécanique">');
							}
						if($discretionc==100)
							{
							print('<img src="im_objets/etoile.gif" border="0" title="Expert en discretion">');
							}
						if($servicec==100)
							{
							print('<img src="im_objets/etoile.gif" border="0" title="Expert en service">');
							}
						if($economiec==100)
							{
							print('<img src="im_objets/etoile.gif" border="0" title="Expert en economie">');
							}
						if($resistancec==100)
							{
							print('<img src="im_objets/etoile.gif" border="0" title="Expert en resistance">');
							}
						if($tirc==100)
							{
							print('<img src="im_objets/etoile.gif" border="0" title="Expert en tir">');
							}
						if($volc==100)
							{
							print('<img src="im_objets/etoile.gif" border="0" title="Expert en vol">');
							}
						if($combatc==100)
							{
							print('<img src="im_objets/etoile.gif" border="0" title="Expert en combat">');
							}
						if($medecinec==100)
							{
							print('<img src="im_objets/etoile.gif" border="0" title="Expert en médecine">');
							}
						if(($combatc>=40) && ($combatc!=100))
							{
							print('<img src="im_objets/etoilebleu.gif" border="0" title="Initié en combat">');
							}
						if(($observationc>=40) && ($observationc!=100))
							{
							print('<img src="im_objets/etoilebleu.gif" border="0" title="Initié en observation">');
							}
						if(($gestionc>=40) && ($gestionc!=100))
							{
							print('<img src="im_objets/etoilebleu.gif" border="0" title="Initié en gestion">');
							}
						if(($maintenancec>=40) && ($maintenancec!=100))
							{
							print('<img src="im_objets/etoilebleu.gif" border="0" title="Initié en maintenance">');
							}
						if(($servicec>=40) && ($servicec!=100))
							{
							print('<img src="im_objets/etoilebleu.gif" border="0" title="Initié en service">');
							}
						if(($discretionc>=40) && ($discretionc!=100))
							{
							print('<img src="im_objets/etoilebleu.gif" border="0" title="Initié en discretion">');
							}
						if(($economiec>=40) && ($economiec!=100))
							{
							print('<img src="im_objets/etoilebleu.gif" border="0" title="Initié en economie">');
							}
						if(($resistancec>=40) && ($resistancec!=100))
							{
							print('<img src="im_objets/etoilebleu.gif" border="0" title="Initié en resistance">');
							}
						if(($tirc>=40) && ($tirc!=100))
							{
							print('<img src="im_objets/etoilebleu.gif" border="0" title="Initié en tir">');
							}
						if(($volc>=40) && ($volc!=100))
							{
							print('<img src="im_objets/etoilebleu.gif" border="0" title="Initié en vol">');
							}
						if(($medecinec>=40) && ($medecinec!=100))
							{
							print('<img src="im_objets/etoilebleu.gif" border="0" title="Initié en médecine">');
							}
						if(($mecaniquec>=40) && ($mecaniquec!=100))
							{
							print('<img src="im_objets/etoilebleu.gif" border="0" title="Initié en mécanique">');
							}
					  print('</div></td>
					</tr>');
			}
		print('</table>');
		}
	else
		{
		print("<i>Il n'y a aucune candidature.</i>");
		}
	if($_SESSION['poste']=="President") print('<u>Hauts pouvoirs du Président:</u><br /><a href="engine=lpc.php">Créer un LPI</a><br /><a href="engine=lpi.php">Invalider tous les LPI</a><br /><a href="engine=ldi.php">Invalider toutes les Lunettes DI2RCO</a><br /><a href="engine=pdi.php">Invalider tous les Passes DI2RCO</a>');
	}
elseif(($type=="proprete") && ($l!=1))
	{
	$sql = 'SELECT valeur FROM donnees_tbl WHERE objet= "etat de la ville"' ;
	$req = mysql_query($sql);
	print('<hr><p align="center"><strong>Etat actuel de la cité : '.mysql_result($req,0,valeur).'</strong></p><hr />');
	print('<p align="center"><i>Vous serez jugé par le Conseil Impérial en fonction de la propreté réelle de la cité.<br>Le facteur propreté est re-calculé toutes les 10 heures et est fonction de la population totale.</i></p><hr>');
	}
elseif(($type=="prison") && ($l!=1))
	{
	$sql1 = 'SELECT * FROM principal_tbl WHERE action= "prison"' ;
	$req1 = mysql_query($sql1);
	$res1 = mysql_num_rows($req1);
	print('<hr><p align="center"><strong>Nombre de détenus : </strong><i>'.$res1.'</i>');
	$sql = 'SELECT nombre,datea FROM prisonniers_tbl ORDER BY datea DESC' ;
	$req = mysql_query($sql);
	$res = mysql_num_rows($req);
	for($i=16;$i!=16-$res;$i--)
		{
		$chiffre[''.$i.''] = mysql_result($req,16-$i,nombre);
		$datea[''.$i.''] = mysql_result($req,16-$i,datea);
		}
	print(' - <a href="engine=courbe.php?titre=Evolution du nombre de detenus');
	for($i=16;$i!=0;$i--)
		{
		if($chiffre[''.$i.'']!=0)
			{
			print('&y'.$i.'='.$chiffre[''.$i.''].'');
			}
		if($datea[''.$i.'']!=0)
			{
			print('&date'.$i.'='.$datea[''.$i.''].'');
			}
		}
	print('">Courbe de l\'évolution</a></p>');
	print('<hr><form name="form1" method="post" action="#"><p align="center"><i>Obtenir des informations sur un détenu : </i><select name="detenu" id="detenu">');
	for($d=0;$d!=$res1;$d++)
		{
		if($_POST['detenu']==mysql_result($req1,$d,pseudo))
			{
			$bd = $d;
			print('<option value="'.mysql_result($req1,$d,pseudo).'" selected>'.mysql_result($req1,$d,pseudo).'</option>');
			}
		else
			{
			print('<option value="'.mysql_result($req1,$d,pseudo).'">'.mysql_result($req1,$d,pseudo).'</option>');
			}
		}
	print('</select><input type="submit" name="Submit" value="Acceder au dossier"></p></form>');
	if($_POST['detenu']!="")
		{
		$sql2 = 'SELECT * FROM principal_tbl WHERE id= "'.mysql_result($req1,$bd,id).'"' ;
		$req2 = mysql_query($sql2);
		print('<hr></p><p align="center"><strong>Dossier du détenu :</strong> <a href="engine=contacter.php?cible='.mysql_result($req1,$bd,pseudo).'&objet=Détenu '.mysql_result($req1,$bd,pseudo).'">'.mysql_result($req1,$bd,pseudo).'</a></p>');
		if(mysql_result($req1,$bd,alim)==1)
			{
			print('<p align="center"><strong>Durée d\'emprisonnement restante : </strong><i>'.mysql_result($req1,$bd,alim).'</i> Jour (<a href="engine=detjours.php?act=plus&p='.mysql_result($req1,$bd,pseudo).'">+</a>) (<a href="engine=detjours.php?act=lib&p='.mysql_result($req1,$bd,pseudo).'">Liberer</a>)</p>');
			}
		else
			{
			print('<p align="center"><strong>Durée d\'emprisonnement restante : </strong><i>'.mysql_result($req1,$bd,alim).'</i> Jours (<a href="engine=detjours.php?act=plus&p='.mysql_result($req1,$bd,pseudo).'">+</a>) (<a href="engine=detjours.php?act=moins&p='.mysql_result($req1,$bd,pseudo).'">-</a>)</p>');
			}
		if(mysql_result($req2,0,type)=="Aucun")
			{
			print('<div align="center"><strong>Ce détenu ne possède actuellement aucun travail.</strong></div>');
			}
		else
			{
			print('<div align="center"><strong>Activité : </strong>'.mysql_result($req2,0,type).' ('.mysql_result($req2,0,entreprise).')</div>');
			}
		print('<p align="center"><a href="engine=casier.php?'.mysql_result($req1,$bd,pseudo).'">Acceder au casier judiciaire du détenu</a></p>');
		}
	}
elseif(($type=="DOI") && ($l!=1))
	{
	print('<hr><p align="center">Chaque membre de la Direction des Organisations Impériales vote chaque semaine les subventions attribuées aux entreprises en faisant la demande.<br> Les subventions en question peuvent aller jusqu\'à 1000 Crédits / Entreprise / Semaine.<br><br><a href="engine=consultsubv.php">Consulter les demandes de subvention privées</a></p>');
	print('<hr><p align="center">Chaque membre de la Direction des Organisations Impériales vote chaque semaine le budget des Organisations Impériales. <br>Le total doit faire exactement 100.000 Crédits.<br><br><a href="engine=consultsubvimp.php">Consulter les Budgets Impériaux</a></p><hr><p align="center"><i>Les moyennes des votes sont faites en même temps que la distribution de Crédits aux entreprises et Organisations Impériales.<br>Vous avez jusqu\'au vendredi soir pour faire vos votes.</i></p>');
	}
elseif(($type=="centre de recherche") && ($l!=1))
	{
	print('<hr><p align="center">Un centre de recherche peut inventer puis produire n\'importe quel type d\'objet.<br /><br />Voici la démarche:</p>');
	print('<hr><p align="left">1. <a href="engine=plancreer.php">Créer un plan</a><br />
	2. <a href="engine=planliste.php">Envoyer un plan en validation</a><br />
	3. <a href="engine=planliste.php">Attendre la validation</a><br />
	4. <a href="engine=stocks.php">Créer des stocks</a></p><hr />');
	}
elseif(($type=="chambre") && ($l!=1))
	{
	print('<hr><p align="center">Les membres de la Chambre doivent voter chaque semaine les propositions de Lois. Une proposition de Loi devient Loi avec l\'unanimité des voix.<br /><a href="engine=consultprop.php">Consulter les propositions de Lois</a></p>');
	print('<hr><p align="center">Chaque membre de la Chambre des Lois peut proposer une Loi.<br>Toute proposition de Loi doit préalablement être validée par le Premier Consul.<br /><a href="engine=proploi.php">Proposer une Loi</a></p><hr><p align="center"><i>Les Lois sont des textes rédigés par les membres de la chambre. <br>Une Loi doit être appliquée au pied de la lettre.<br>Les forces de Police veillent à ce que toutes les Lois soient appliquées par la totalité des citoyens.</i></p>');
	print('<hr><p align="center">En tant que membre de la Chambre des Lois, vous avez la possibilité de produire des alliances afin d\'officialiser un mariage.<br /><a href="engine=alliance.php">Acheter une alliance</a></p>');
	}
elseif((($type=="police") || ($type=="di2rco")) && ($l!=1))
	{
	$_SESSION['nomrech'] = "";
	print('<hr><form name="form1" method="post" action="engine=police.php"><div align="center"><p><strong>Faire une recherche sur :</strong></p><p>Un nom: <input name="nomrech" type="text" id="nomrech"> <input type="submit" name="Submit" value="Valider"></p></div></form>');
	print('<form name="form2" method="post" action="engine=policel.php"><div align="center"><p>Une adresse: <input name="nomrechln" type="text" id="nomrechln" size="3" maxlength="3"> <input name="nomrechlr" type="text" id="nomrechlr"> <input type="submit" name="Submit" value="Valider"></p></div></form>');
	print('<hr><p align="center"><a href="engine=pleintes.php">Consulter les plaintes</a><br>');
	print('<a href="engine=listeavis.php">Consulter les avis de recherches</a></p>');
	print('<p align="center"><a href="engine=menottes.php">Acheter une paire de menottes</a></p>');
	
	if ($type=="di2rco" && $_SESSION['poste']=="Directeur du DI2RCO") {
		print('<br />');
		
		if ($_GET['deplacementDI2RCO'] == "")
			print('<p align="center">Déménager les locaux de la DI2RCO. Choisissez un secteur :<br />
			<a href="engine=services.php?deplacementDI2RCO=1">Secteur 1</a> - 
			<a href="engine=services.php?deplacementDI2RCO=2">Secteur 2</a> - 
			<a href="engine=services.php?deplacementDI2RCO=3">Secteur 3</a> - 
			<a href="engine=services.php?deplacementDI2RCO=4">Secteur 4</a> - 
			<a href="engine=services.php?deplacementDI2RCO=5">Secteur 5</a> - 
			<a href="engine=services.php?deplacementDI2RCO=6">Secteur 6</a> - 
			<a href="engine=services.php?deplacementDI2RCO=7">Secteur 7</a> - 
			<a href="engine=services.php?deplacementDI2RCO=8">Secteur 8</a> - 
			<a href="engine=services.php?deplacementDI2RCO=9">Secteur 9</a></p>');
		elseif (preg_match('/[0-9]/', $_GET['deplacementDI2RCO'])) {
			$adresse = deplace_entreprise("DI2RCO", $_GET['deplacementDI2RCO'], true, false);
			print('<p align="center"><strong>La DI2RCO a été déplacée au '.$adresse.' [S'.$_GET['deplacementDI2RCO'].'] avec succès.</strong></p>');
		}
		
		if ($_GET['passeDI2RCO'] == "")
			print('<p align="center"><a href="engine=services.php?passeDI2RCO=1">Obtenir un passe DI2RCO</a></p>');
		else {
			$emplacement = inventaire_libre();
			if (!count($emplacement))
				print('<p align="center"><strong>Il vous faut un emplacement libre dans l\'inventaire</strong></p>');
			else {
				$sql = 'UPDATE principal_tbl SET case'.$emplacement[0].' = "Passe DI2RCO" WHERE id = '.$_SESSION['id'];
				mysql_query($sql);
				print('<p align="center"><strong>Un passe DI2RCO a été placé dans votre inventaire</strong></p>');
			}
		}
		
		if ($_GET['lunettesDI2RCO'] == "")
			print('<p align="center"><a href="engine=services.php?lunettesDI2RCO=1">Obtenir des lunettes DI2RCO</a></p>');
		else {
			$emplacement = inventaire_libre();
			if (!count($emplacement))
				print('<p align="center"><strong>Il vous faut un emplacement libre dans l\'inventaire</strong></p>');
			else {
				$sql = 'UPDATE principal_tbl SET case'.$emplacement[0].' = "Lunettes DI2RCO" WHERE id = '.$_SESSION['id'];
				mysql_query($sql);
				print('<p align="center"><strong>Des lunettes DI2RCO ont été placées dans votre inventaire</strong></p>');
			}
		}
	}
	}
elseif(($type=="dcn") && ($l!=1))
	{
	print('<div style="text-align:center;">');
	if($_GET['lieu']=="")
		{
		print('<p><br /><br /><br />
		<a href="engine=services.php?lieu=dcn">Département du DreadCast News</a><br /><br />
		<a href="engine=services.php?lieu=dctv">Département de la DreadCast TeleVision</a><br /><br />
		<a href="engine=services.php?lieu=aitl">Département de gestion de l\'AITL</a>
		</p>');
		}
	elseif($_GET['lieu']=="dcn" && $_GET['action']=="")
		{
		$sql = 'SELECT numero FROM DCN_numeros_tbl WHERE paru=0' ;
		$req = mysql_query($sql);
		$res = mysql_num_rows($req);
		if($res!=0) $numero_dcn = mysql_result($req,0,numero);
			
		print('<p>
		Bienvenue au coeur du DreadCast News.<br />
		Le DCN n°'.$numero_dcn.' est en cours de rédaction.
		</p>
		
		<p>
		Vos actions :<br />
		<br />');
		if($_SESSION['poste'] == "Responsable du DC News" || $_SESSION['poste'] == "Directeur du DC Network")
			print('<a href="engine=services.php?lieu=dcn&action=prod">Lancer la commercialisation du DCN n°'.$numero_dcn.'</a><br />
					<a href="engine=services.php?lieu=dcn&action=abonnement">Fixer le prix de l\'abonnement</a><br />
					<a href="engine=services.php?lieu=dcn&action=prix">Fixer le prix de vente</a><br /><br />');
					
		if($_SESSION['poste'] == "Responsable du DC News" || $_SESSION['poste'] == "Directeur du DC Network")
			print('<a href="engine=services.php?lieu=dcn&action=modiftexte">Modifier un article</a><br /><br />');
					
		print('<a href="engine=services.php?lieu=dcn&action=maquette">Accès à la maquette</a><br />
		<a href="engine=services.php?lieu=dcn&action=redac">Accès à mon espace de rédaction</a><br />
		<a href="engine=services.php?lieu=dcn&action=rendu">Prérendu de la maquette</a><br />
		<a href="engine=services.php?lieu=dcn&action=liste">Liste des DCN parus</a>
		</p>');
		}
	elseif($_GET['lieu']=="dcn" && $_GET['action']=="prod")
		{
		if($_SESSION['poste'] != "Responsable du DC News" && $_SESSION['poste'] != "Directeur du DC Network")
			{
			print('<meta http-equiv="refresh" content="0 ; url=engine=services.php?lieu=dcn"> ');
			exit();
			}
		
		$sql = 'SELECT numero,prix FROM DCN_numeros_tbl WHERE paru=0' ;
		$req = mysql_query($sql);
		$res = mysql_num_rows($req);
		if($res!=0) { $numero_dcn = mysql_result($req,0,numero); $prix_dcn = mysql_result($req,0,prix); }
		
		if($prix_dcn == 0)
			print('<p>
			Vous n\'avez pas fixé de prix de vente.<br /><br />
			
			<a href="engine=services.php?lieu=dcn">Retour</a>
			</p>');
		else
			print('<p>
			Etes-vous sûr de vouloir lancer la production du DC News n°'.$numero_dcn.' ?<br />
			Le prix de vente est fixé à <strong>'.$prix_dcn.'</strong> crédits et ne pourra plus être modifié.<br /><br />
			
			<a href="engine=services.php?lieu=dcn&action=lancer_prod">Valider la commercialisation</a> - <a href="engine=services.php?lieu=dcn">Retour</a>
			</p>');
		}
	elseif($_GET['lieu']=="dcn" && $_GET['action']=="lancer_prod")
		{
		if($_SESSION['poste'] != "Responsable du DC News" && $_SESSION['poste'] != "Directeur du DC Network")
			{
			print('<meta http-equiv="refresh" content="0 ; url=engine=services.php?lieu=dcn"> ');
			exit();
			}
		
		$sql = 'SELECT numero,prix FROM DCN_numeros_tbl WHERE paru=0' ;
		$req = mysql_query($sql);
		$res = mysql_num_rows($req);
		if($res!=0) { $numero_dcn = mysql_result($req,0,numero); $prix_dcn = mysql_result($req,0,prix); }
		
		$sql = 'UPDATE DCN_numeros_tbl SET paru=1,date="'.time().'" WHERE paru=0' ; // Produit le DCN actuel
		mysql_query($sql);
		$sql = 'INSERT INTO objets_tbl(nom,image,url,type) VALUES("DreadCast News '.$numero_dcn.'","dcnews.php?numero='.$numero_dcn.'","engine=liredcn.php?numero='.$numero_dcn.'","objet")';
		mysql_query($sql);
		$sql = 'INSERT INTO DCN_numeros_tbl(id,numero,contenu,paru) VALUES("","'.($numero_dcn+1).'","[TITRE]
Titre
[/TITRE]

[INTRODUCTION]
[TEXTE]
Introduction
[/TEXTE]
[/INTRODUCTION]","0")' ; // Crée un nouveau projet de DCN
		mysql_query($sql);
		$sql = 'SELECT nombre,abonne FROM DCN_abonnes_tbl WHERE abonne NOT LIKE "DCN %" AND medium="DCN"' ; // Sélectionne tous les abonnes
		$req = mysql_query($sql);
		$res = mysql_num_rows($req);
		for($i=0;$i<$res;$i++)
			{
			$sql2 = 'INSERT INTO DCN_achats_tbl(id,acheteur,numero) VALUES("","'.mysql_result($req,$i,abonne).'","'.$numero_dcn.'")' ; // Donne le DCN aux abonnes
			mysql_query($sql2);
			
			$nombre = mysql_result($req,$i,nombre)-1;
			
			$tmp = ($nombre == 0)?"<br /><br />Votre abonnement touche à sa fin. Rendez-vous au DC Network pour vous réinscrire.":"";
			
			$sql2 = 'INSERT INTO messages_tbl ( id , auteur , cible , message , objet , moment , nouveau ) VALUES ("" , "Dreadcast", "'.mysql_result($req,$i,abonne).'", "Le DreadCast News n°'.$numero_dcn.' vient de paraître.<br /><br />Grâce à votre abonnement, Vous pouvez le consulter directement depuis votre AITL 2.0.'.$tmp.'", "Nouveau DC News", "'.time().'", "oui")';
			mysql_query($sql2);
			
			if($nombre == 0) $sql2 = 'DELETE FROM DCN_abonnes_tbl WHERE abonne="'.mysql_result($req,$i,abonne).'"' ; // Supprime l'abonné
			else $sql2 = 'UPDATE DCN_abonnes_tbl SET nombre="'.$nombre.'" WHERE abonne="'.mysql_result($req,$i,abonne).'"'; // Actualise le nombre de numéros d'abonnement
			mysql_query($sql2);
			}
		
		print('<p>
		Les turbines sont lancées, la production a commencé.<br /><br />
		
		<a href="engine=services.php?lieu=dcn">Retour</a>
		</p>');
		}
	elseif($_GET['lieu']=="dcn" && $_GET['action']=="abonnement" && $_POST['nv_nombre']=="" && $_POST['nv_prix']=="")
		{
		if($_SESSION['poste'] != "Responsable du DC News" && $_SESSION['poste'] != "Directeur du DC Network")
			{
			print('<meta http-equiv="refresh" content="0 ; url=engine=services.php?lieu=dcn"> ');
			exit();
			}
		
		$sql = 'SELECT abonne,nombre FROM DCN_abonnes_tbl WHERE medium="DCN" AND abonne LIKE "DCN %"' ;
		$req = mysql_query($sql);
		$res = mysql_num_rows($req);
		if($res!=0) { $nb_numeros = str_replace("DCN ","",mysql_result($req,0,abonne)); $prix_numeros = mysql_result($req,0,nombre); }
		
		print('<p>
		L\'abonnement actuel au DreadCast News concerne <strong>'.$nb_numeros.'</strong> numero(s) pour le prix de <strong>'.$prix_numeros.'</strong> crédits.<br />
		Souhaitez-vous le modifier ?<br /><br />
		
		<div id="liens_prix_vente"><a class="commelien" onclick="javascript:$(\'#prix_vente\').show();$(\'#liens_prix_vente\').hide();">Modifier l\'abonnement</a> - <a href="engine=services.php?lieu=dcn">Retour</a></div>
		<form action="engine=services.php?lieu=dcn&action=abonnement" method="post" id="prix_vente" style="display:none;">
			<input type="text" value="'.$nb_numeros.'" style="width:35px;text-align:right;" name="nv_nombre" /> numéros pour <input type="text" value="'.$prix_numeros.'" style="width:35px;text-align:right;" name="nv_prix" /> crédits - <input type="submit" name="submit" value="Valider" />
		</form>
		</p>');
		}
	elseif($_GET['lieu']=="dcn" && $_GET['action']=="abonnement" && $_POST['nv_nombre']>"0" && $_POST['nv_prix']!="")
		{
		if($_SESSION['poste'] != "Responsable du DC News" && $_SESSION['poste'] != "Directeur du DC Network")
			{
			print('<meta http-equiv="refresh" content="0 ; url=engine=services.php?lieu=dcn"> ');
			exit();
			}
		
		$sql = 'UPDATE DCN_abonnes_tbl SET abonne="DCN '.htmlentities($_POST['nv_nombre']).'",nombre="'.htmlentities($_POST['nv_prix']).'" WHERE medium="DCN" AND abonne LIKE "DCN %"' ;
		mysql_query($sql);

		print('<p>
		Modification effectuée.<br /><br />
		
		<a href="engine=services.php?lieu=dcn">Retour</a>
		</p>');
		}
	elseif($_GET['lieu']=="dcn" && $_GET['action']=="prix" && $_POST['nv_prix']=="")
		{
		if($_SESSION['poste'] != "Responsable du DC News" && $_SESSION['poste'] != "Directeur du DC Network")
			{
			print('<meta http-equiv="refresh" content="0 ; url=engine=services.php?lieu=dcn"> ');
			exit();
			}
		
		$sql = 'SELECT numero,prix FROM DCN_numeros_tbl WHERE paru=0' ;
		$req = mysql_query($sql);
		$res = mysql_num_rows($req);
		if($res!=0) { $numero_dcn = mysql_result($req,0,numero); $prix_dcn = mysql_result($req,0,prix); }
		
		print('<p>
		Le prix de vente actuel du DreadCast News n°'.$numero_dcn.' est <strong>'.$prix_dcn.'</strong> crédits.<br />
		Souhaitez-vous le modifier ?<br /><br />
		
		<div id="liens_prix_vente"><a class="commelien" onclick="javascript:$(\'#prix_vente\').show();$(\'#liens_prix_vente\').hide();">Modifier le prix de vente</a> - <a href="engine=services.php?lieu=dcn">Retour</a></div>
		<form action="engine=services.php?lieu=dcn&action=prix" method="post" id="prix_vente" style="display:none;">
			<input type="text" value="'.$prix_dcn.'" style="width:35px;text-align:right;" name="nv_prix" /> crédits - <input type="submit" name="submit" value="Valider" />
		</form>
		</p>');
		}
	
	elseif($_GET['lieu']=="dcn" && $_GET['action']=="prix" && $_POST['nv_prix']!="")
		{
		if($_SESSION['poste'] != "Responsable du DC News" && $_SESSION['poste'] != "Directeur du DC Network")
			{
			print('<meta http-equiv="refresh" content="0 ; url=engine=services.php?lieu=dcn"> ');
			exit();
			}
		$sql = 'UPDATE DCN_numeros_tbl SET prix="'.htmlentities($_POST['nv_prix']).'" WHERE paru=0' ;
		mysql_query($sql);
		
		print('<p>
		Modification effectuée.<br /><br />
		
		<a href="engine=services.php?lieu=dcn">Retour</a>
		</p>');
		}
	elseif($_GET['lieu']=="dcn" && $_GET['action']=="modiftexte" && $_GET['num'] == "")
		{	
		if($_SESSION['poste'] != "Responsable du DC News" && $_SESSION['poste'] != "Directeur du DC Network")
			{
			print('<meta http-equiv="refresh" content="0 ; url=engine=services.php?lieu=dcn"> ');
			exit();
			}
		
		$sql = 'SELECT numero FROM DCN_numeros_tbl WHERE paru=0' ;
		$req = mysql_query($sql);
		$dcn_num = mysql_result($req,0,numero);
		
		$sql = 'SELECT id,pseudo,auteur,titre,contenu,description,image FROM DCN_espaces_tbl WHERE num_dcn="'.$dcn_num.'" ORDER BY id DESC' ;
		$req = mysql_query($sql);
		$res = mysql_num_rows($req);
				
		print('<p>
			<h3>Modification d\'articles en cours</h3>
			<table id="tablemaquette">
				<tr>
					<td colspan="2" class="tab1"><strong>Articles du DreadCast News n°'.$dcn_num.'</strong></td>
				</tr>');
		
			for($i=0;$i<$res;$i++)
				{
				print('<tr>
					<td colspan="2"><div class="article">
					<strong>Article de '.mysql_result($req,$i,pseudo).' - '.mysql_result($req,$i,titre).'</strong> par '.mysql_result($req,$i,auteur).'<br />
					<em>'.mysql_result($req,$i,description).'</em><br />
					<a href="#" onclick="javascript:$(\'#contenu'.$i.'\').show();$(\'#masquer'.$i.'\').show();$(\'#afficher'.$i.'\').hide();" id="afficher'.$i.'">Afficher</a>
					<a href="#" onclick="javascript:$(\'#contenu'.$i.'\').hide();$(\'#masquer'.$i.'\').hide();$(\'#afficher'.$i.'\').show();" id="masquer'.$i.'" style="display:none;">Masquer</a> - <a href="engine=services.php?lieu=dcn&action=modiftexte&num='.mysql_result($req,$i,id).'">Modifier</a>
					<div id="contenu'.$i.'" style="text-align:justify;display:none;padding:5px;">
						'.((mysql_result($req,$i,image) != "")?"<img src=\"".mysql_result($req,$i,image)."\" alt=\"Image\" />":"").'
						'.nl2br(mysql_result($req,$i,contenu)).'
					</div>
					</div>
					</td>
				</tr>');
				}
		
				print('
				<tr>
					<td colspan="2" class="tab1"><a href="engine=services.php?lieu=dcn">Retour</a></td><td>
				</tr>
			</table>
		</p>
		');
		}
	elseif($_GET['lieu']=="dcn" && $_GET['action']=="modiftexte" && $_GET['num'] != "")
		{	
		if($_SESSION['poste'] != "Responsable du DC News" && $_SESSION['poste'] != "Directeur du DC Network")
			{
			print('<meta http-equiv="refresh" content="0 ; url=engine=services.php?lieu=dcn"> ');
			exit();
			}
		
		$sql = 'SELECT numero FROM DCN_numeros_tbl WHERE paru=0' ;
		$req = mysql_query($sql);
		$dcn_num = mysql_result($req,0,numero);
		
		print('<p>
		<h3>Espace personnel de rédaction</h3>');
		
		if($_POST['titre'] != "")
			{
			$sql = 'UPDATE DCN_espaces_tbl SET titre="'.htmlentities($_POST['titre']).'",auteur="'.htmlentities($_POST['auteur']).'",contenu="'.htmlentities(stripslashes($_POST['contenu'])).'",description="'.htmlentities($_POST['description']).'",image="'.htmlentities($_POST['image']).'" WHERE id="'.htmlentities($_GET['num']).'"' ;
			mysql_query($sql);
		
			print('
			<br />Modification effectuée.<br /><br />');
			}
		
		$sql = 'SELECT * FROM DCN_espaces_tbl WHERE id="'.htmlentities($_GET['num']).'"';
		$req = mysql_query($sql);
		
		if(mysql_num_rows($req) != 0)
			{
			$titre = mysql_result($req,0,titre);
			$auteur = (mysql_result($req,0,auteur) == "")?$_SESSION['pseudo']:mysql_result($req,0,auteur);
			$description = mysql_result($req,0,description);
			$image = mysql_result($req,0,image);
			$contenu = mysql_result($req,0,contenu);
			}
		
		print('<form action="engine=services.php?lieu=dcn&action=modiftexte&num='.$_GET['num'].'" method="post">
			<table id="tablemaquette">
				<tr>
					<td colspan="2" class="tab1"><strong>Titre</strong></td>
				</tr>
				<tr>
					<td colspan="2"><input type="text" value="'.$titre.'" name="titre" /></td>
				</tr>
				<tr>
					<td colspan="2" class="tab1"><strong>Auteur</strong></td>
				</tr>
				<tr>
					<td colspan="2"><input type="text" value="'.$auteur.'" name="auteur" /></td>
				</tr>
				<tr>
					<td colspan="2" class="tab1"><strong>Courte description</strong></td>
				</tr>
				<tr>
					<td colspan="2"><input style="width:300px;" type="text" value="'.$description.'" name="description" /></td>
				</tr>
				<tr>
					<td colspan="2" class="tab1"><strong>Contenu</strong></td>
				</tr>
				<tr>
					<td>Image (facultative) : </td>	
					<td>
						'.(($image != "")?"<img src=\"".$image."\" alt=\"Image\" /><br />":"").'
						<input type="text" value="'.$image.'" name="image" />
					</td>
				</tr>
				<tr>
					<td colspan="2"><textarea style="padding:10px;width:400px;height:200px;text-align:justify;" name="contenu">'.$contenu.'</textarea></td>
				</tr>
				<tr>
					<td colspan="2" class="tab1"><input type="submit" name="submit" value="Enregistrer les modifications" /> - <a href="engine=services.php?lieu=dcn&action=redac">Retour</a></td><td>
				</tr>
			</table>
			</form>
		</p>');		
		}
	elseif($_GET['lieu']=="dcn" && $_GET['action']=="maquette")
		{
		$sql = 'SELECT * FROM DCN_numeros_tbl WHERE paru=0' ;
		$req = mysql_query($sql);
		$dcn_num = mysql_result($req,0,numero);
		
		if($_POST['deplacement'] != "")
			{
			list($deplacement,$vers) = explode('-',trim(htmlentities($_POST['deplacement'])));
			if($vers != "0")
				{
				$sqltmp = 'SELECT id FROM DCN_espaces_tbl WHERE num_dcn="'.$dcn_num.'" AND num_article="'.$vers.'"';
				$reqtmp = mysql_query($sqltmp);
				$restmp = mysql_num_rows($reqtmp);
				$sql = 'UPDATE DCN_espaces_tbl SET num_article="'.$vers.'" WHERE num_dcn="'.$dcn_num.'" AND num_article="'.$deplacement.'"';
				mysql_query($sql);
				
				if($restmp!=0)
					{
					$sql = 'UPDATE DCN_espaces_tbl SET num_article="'.$deplacement.'" WHERE id="'.mysql_result($reqtmp,0,id).'"';
					mysql_query($sql);
					}
				}
			}
		if($_POST['ajouter'] != "")
			{
			$valeur = htmlentities(str_replace("ajouter (id ","",$_POST['ajouter']));
			$valeur = str_replace(")","",$valeur);
			
			$sqltmp = 'SELECT MAX(num_article) FROM DCN_espaces_tbl WHERE num_dcn="'.$dcn_num.'"';
			$reqtmp = mysql_query($sqltmp);
			$restmp = mysql_num_rows($reqtmp);
			$sql = 'UPDATE DCN_espaces_tbl SET num_article="'.(mysql_result($reqtmp,0,'MAX(num_article)')+1).'" WHERE id="'.$valeur.'"';
			mysql_query($sql);
			}
		if($_POST['retirer'] != "")
			{
			$valeur = htmlentities(str_replace("retirer (id ","",$_POST['retirer']));
			$valeur = str_replace(") ","",$valeur);
			$sql = 'UPDATE DCN_espaces_tbl SET num_article="0" WHERE id="'.$valeur.'"';
			mysql_query($sql);
			}
		if($_GET['valider']!="")
			{
			$titre = htmlentities(stripslashes($_POST['titre']));
			$image0 = htmlentities(stripslashes($_POST['image0']));
			$contenu0 = htmlentities(stripslashes($_POST['contenu0']));
			
			$dcn = '[TITRE]'.$titre.'[/TITRE]
			
			[INTRODUCTION]
			'.(($image0 != "")?'[IMAGE]'.$image0.'[/IMAGE]':'').'
			[TEXTE]'.$contenu0.'[/TEXTE]
			[/INTRODUCTION]';
			
			$sql = 'SELECT MAX(num_article) FROM DCN_espaces_tbl WHERE num_dcn="'.$dcn_num.'"';
			$req2 = mysql_query($sql);
			$res = mysql_num_rows($req2);
			if($res != 0) $num_max = mysql_result($req2,0,'MAX(num_article)');
	
			for($i=1;$i<=$num_max;$i++)
				{
				$sql2 = 'SELECT * FROM DCN_espaces_tbl WHERE num_dcn="'.$dcn_num.'" AND num_article="'.$i.'"';
				$req2 = mysql_query($sql2);
				$res2 = mysql_num_rows($req2);
	
				if($res2 != 0)
					{
					$dcn .= '
					
					[PARTIE '.$i.']
					[TITRE]'.mysql_result($req2,0,titre).'[/TITRE]
					[AUTEUR]'.mysql_result($req2,0,auteur).'[/AUTEUR]
					[PRESENTATION]'.mysql_result($req2,0,description).'[/PRESENTATION]
					'.((mysql_result($req2,0,image) != "")?'[IMAGE]'.mysql_result($req2,0,image).'[/IMAGE]':'').'
					[TEXTE]'.mysql_result($req2,0,contenu).'[/TEXTE]
					[/PARTIE '.$i.']';
					}
				}
				
			$sql = 'UPDATE DCN_numeros_tbl SET contenu="'.$dcn.'" WHERE paru=0' ;
			mysql_query($sql);
			
			print('<p>Modification effectuée.</p>');
			
			$sql = 'SELECT * FROM DCN_numeros_tbl WHERE paru=0' ;
			$req = mysql_query($sql);
			}
		
		print('<p>
		<h3>DreadCast News n°'.$dcn_num.'</h3>
		<form action="engine=services.php?lieu=dcn&action=maquette&valider=ok" method="post">
			<table id="tablemaquette">
				<tr>
					<td colspan="2" class="tab1"><strong>Titre</strong></td>
				</tr>
				<tr>
					<td colspan="2"><input type="text" value="'.affiche_DCN_titre(mysql_result($req,0,contenu)).'" name="titre" /></td>
				</tr>
				<tr>
					<td colspan="2" class="tab1"><strong>Introduction</strong></td>
				</tr>
				<tr>
					<td>Image (facultative) : </td>	
					<td>
						'.((affiche_DCN_image(mysql_result($req,0,contenu),0) != "")?"<a href=\"".affiche_DCN_image(mysql_result($req,0,contenu),0)."\" onclick=\"window.open(this.href); return false;\"><img src=\"".affiche_DCN_image(mysql_result($req,0,contenu),0)."\" alt=\"Image 0\" /></a><br />":"").'
						<input type="text" value="'.affiche_DCN_image(mysql_result($req,0,contenu),0).'" name="image0" />
					</td>
				</tr>
				<tr>
					<td>Texte : </td>	
					<td>
						<textarea  name="contenu0">'.affiche_DCN_contenu(mysql_result($req,0,contenu),0).'</textarea>
					</td>
				</tr>
				<tr>
					<td colspan="2" class="tab1"><strong>Contenu</strong></td>
				</tr>
				');
				
				$sql2 = 'SELECT MAX(num_article) FROM DCN_espaces_tbl WHERE num_dcn="'.$dcn_num.'"';
				$req2 = mysql_query($sql2);
				$res2 = mysql_num_rows($req2);
				if($res2 != 0) $num_max = mysql_result($req2,0,'MAX(num_article)');

				for($i=1;$i<=$num_max;$i++)
					{
					$sql2 = 'SELECT * FROM DCN_espaces_tbl WHERE num_dcn="'.$dcn_num.'" AND num_article="'.$i.'"';
					$req2 = mysql_query($sql2);
					$res2 = mysql_num_rows($req2);

					if($res2 != 0)
					print('<tr>
						<td colspan="2"><div class="article">
						<div class="numero">'.mysql_result($req2,0,num_article).'</div>
						'.(($i != 1)?"<input style=\"cursor:pointer;border:none;\" type=\"submit\" name=\"deplacement\" value=\"      ".mysql_result($req2,0,num_article)."-".(mysql_result($req2,0,num_article)-1)."\" class=\"fleche1\" />":"").'
						'.(($i != $num_max)?"<input style=\"cursor:pointer;border:none;\" type=\"submit\" name=\"deplacement\" value=\"     ".mysql_result($req2,0,num_article)."-".(mysql_result($req2,0,num_article)+1)."\" class=\"fleche2\" />":"").'
						<strong>'.mysql_result($req2,0,titre).'</strong> par '.mysql_result($req2,0,pseudo).'<br />
						<em>'.mysql_result($req2,0,description).'</em><br />
						<a href="#" onclick="javascript:$(\'#contenu'.$i.'\').show();$(\'#masquer'.$i.'\').show();$(\'#afficher'.$i.'\').hide();" id="afficher'.$i.'">Afficher</a>
						<a href="#" onclick="javascript:$(\'#contenu'.$i.'\').hide();$(\'#masquer'.$i.'\').hide();$(\'#afficher'.$i.'\').show();" id="masquer'.$i.'" style="display:none;">Masquer</a> - <input class="commelien" style="border:none;background:none;" type="submit" name="retirer" value="retirer (id '.mysql_result($req2,0,id).')" />
						<div id="contenu'.$i.'" style="text-align:justify;display:none;padding:5px;">
							'.((mysql_result($req2,0,image) != "")?"<img src=\"".mysql_result($req2,0,image)."\" alt=\"Image\" />":"").'
							'.nl2br(mysql_result($req2,0,contenu)).'
						</div>
						</div>
						</td>
					</tr>');	
					}
					
				print('<tr>
					<td colspan="2">
						<div class="article"><a href="#" onclick="javascript:$(\'#liste_articles\').show();">+ Ajouter un article</a>
						<div id="liste_articles" style="display:none;">');
				
				$sql2 = 'SELECT id,titre,pseudo,description FROM DCN_espaces_tbl WHERE num_dcn="'.$dcn_num.'" AND num_article="0"';
				$req2 = mysql_query($sql2);
				$res2 = mysql_num_rows($req2);
				
				if($res2 == 0) print('Il n\'y a actuellement aucun article disponible.');
				for($i=0;$i<$res2;$i++) print('<br /><strong>'.mysql_result($req2,$i,titre).'</strong> par '.mysql_result($req2,$i,pseudo).' - <input class="commelien" style="border:none;background:none;" type="submit" name="ajouter" value="ajouter (id '.mysql_result($req2,$i,id).')" /><br /><em>'.mysql_result($req2,$i,description).'</em><br />');
				
						print('</div><br />
						</div>
					</td>
				</tr>
				<tr>
					<td colspan="2" class="tab1"><input type="submit" name="submit" value="Enregistrer les modifications" /> - <a href="engine=services.php?lieu=dcn">Retour</a></td><td>
				</tr>');
				
				print('
			</table>
		</form>
		</p>');
		}
	elseif($_GET['lieu']=="dcn" && $_GET['action']=="redac" && $_GET['modif']=="")
		{
		$sql = 'SELECT numero FROM DCN_numeros_tbl WHERE paru=0' ;
		$req = mysql_query($sql);
		$dcn_num = mysql_result($req,0,numero);
		
		if($_GET['suppr'] != "")
			{
			if($_GET['confirm'] == "")
				{
				print('<p>Etes-vous sûr de vouloir supprimer cet article ?<br />
				<a href="engine=services.php?lieu=dcn&action=redac&suppr='.htmlentities($_GET['suppr']).'&confirm=ok">Confirmer</a> - <a href="engine=services.php?lieu=dcn&action=redac">Annuler</a></p>');
				}
			else
				{
				$sql = 'DELETE FROM DCN_espaces_tbl WHERE pseudo="'.$_SESSION['pseudo'].'" AND id="'.htmlentities($_GET['suppr']).'"' ;
				mysql_query($sql);
				}
			}
		
		$sql = 'SELECT * FROM DCN_espaces_tbl WHERE pseudo="'.$_SESSION['pseudo'].'" AND num_dcn="'.$dcn_num.'" ORDER BY id DESC' ;
		$req = mysql_query($sql);
		$res = mysql_num_rows($req);
		
		print('<p>
		<h3>Espace personnel de rédaction</h3>
		<form action="engine=services.php?lieu=dcn&action=maquette&valider=ok" method="post">
			<table id="tablemaquette">
				<tr>
					<td colspan="2" class="tab1"><strong>Articles du DreadCast News n°'.$dcn_num.'</strong></td>
				</tr>');
		
			for($i=0;$i<$res;$i++)
				{
				print('<tr>
					<td colspan="2"><div class="article">
					<strong>'.mysql_result($req,$i,titre).'</strong><br />
					<em>'.mysql_result($req,$i,description).'</em><br />
					<a href="#" onclick="javascript:$(\'#contenu'.$i.'\').show();$(\'#masquer'.$i.'\').show();$(\'#afficher'.$i.'\').hide();" id="afficher'.$i.'">Afficher</a>
					<a href="#" onclick="javascript:$(\'#contenu'.$i.'\').hide();$(\'#masquer'.$i.'\').hide();$(\'#afficher'.$i.'\').show();" id="masquer'.$i.'" style="display:none;">Masquer</a> - <a href="engine=services.php?lieu=dcn&action=redac&modif='.mysql_result($req,$i,id).'">Modifier</a> - <a href="engine=services.php?lieu=dcn&action=redac&suppr='.mysql_result($req,$i,id).'">Supprimer</a>
					<div id="contenu'.$i.'" style="text-align:justify;display:none;padding:5px;">
						'.((mysql_result($req,$i,image) != "")?"<img src=\"".mysql_result($req,$i,image)."\" alt=\"Image\" />":"").'
						'.nl2br(mysql_result($req,$i,contenu)).'
					</div>
					</div>
					</td>
				</tr>');
				}
		
				print('
				<tr>
					<td colspan="2"><div class="article"><a href="engine=services.php?lieu=dcn&action=redac&modif=0">+ Ecrire un nouvel article</a>
					</div>
					</td>
				</tr>
				<tr>
					<td colspan="2" class="tab1"><strong>Autres articles</strong></td>
				</tr>');
				
			$sql = 'SELECT * FROM DCN_espaces_tbl WHERE pseudo="'.$_SESSION['pseudo'].'" AND num_dcn!="'.$dcn_num.'"ORDER BY id DESC' ;
			$req = mysql_query($sql);
			$res = mysql_num_rows($req);
			
			for($i=0;$i<$res;$i++)
				{
				print('<tr>
					<td colspan="2"><div class="article">
					<strong>'.mysql_result($req,$i,titre).'</strong> - DreadCast News n°'.mysql_result($req,$i,num_dcn).'<br />
					<em>'.mysql_result($req,$i,description).'</em><br />
					<a href="#" onclick="javascript:$(\'#contenu'.$i.'2\').show();$(\'#masquer'.$i.'2\').show();$(\'#afficher'.$i.'2\').hide();" id="afficher'.$i.'2">Afficher</a>
					<a href="#" onclick="javascript:$(\'#contenu'.$i.'2\').hide();$(\'#masquer'.$i.'2\').hide();$(\'#afficher'.$i.'2\').show();" id="masquer'.$i.'2" style="display:none;">Masquer</a>
					<div id="contenu'.$i.'2" style="text-align:justify;display:none;padding:5px;">
						'.((mysql_result($req,$i,image) != "")?"<img src=\"".mysql_result($req,$i,image)."\" alt=\"Image\" />":"").'
						'.nl2br(mysql_result($req,$i,contenu)).'
					</div>
					</div>
					</td>
				</tr>');
				}
				
				print('<tr>
					<td colspan="2" class="tab1"><a href="engine=services.php?lieu=dcn">Retour</a></td><td>
				</tr>
			</table>
		</form>
		</p>
		');
		}
	elseif($_GET['lieu']=="dcn" && $_GET['action']=="redac" && $_GET['modif']!="" && $_GET['valider']=="")
		{
		$sql = 'SELECT numero FROM DCN_numeros_tbl WHERE paru=0' ;
		$req = mysql_query($sql);
		$dcn_num = mysql_result($req,0,numero);
		
		$sql = 'SELECT * FROM DCN_espaces_tbl WHERE pseudo="'.$_SESSION['pseudo'].'" AND id="'.htmlentities($_GET['modif']).'"';
		$req = mysql_query($sql);
		
		if(mysql_num_rows($req) != 0 || $_GET['modif'] == "0")
			{
			if(mysql_num_rows($req) != 0)
				{
				$titre = mysql_result($req,0,titre);
				$auteur = (mysql_result($req,0,auteur) == "")?$_SESSION['pseudo']:mysql_result($req,0,auteur);
				$description = mysql_result($req,0,description);
				$image = mysql_result($req,0,image);
				$contenu = mysql_result($req,0,contenu);
				}
				
			print('<p>
			<h3>Espace personnel de rédaction</h3>
			<form action="engine=services.php?lieu=dcn&action=redac&modif='.$_GET['modif'].'&valider=ok" method="post">
				<table id="tablemaquette">
					<tr>
						<td colspan="2" class="tab1"><strong>Titre</strong></td>
					</tr>
					<tr>
						<td colspan="2"><input type="text" value="'.$titre.'" name="titre" /></td>
					</tr>
					<tr>
						<td colspan="2" class="tab1"><strong>Auteur</strong></td>
					</tr>
					<tr>
						<td colspan="2"><input type="text" value="'.$auteur.'" name="auteur" /></td>
					</tr>
					<tr>
						<td colspan="2" class="tab1"><strong>Courte description</strong></td>
					</tr>
					<tr>
						<td colspan="2"><input style="width:300px;" type="text" value="'.$description.'" name="description" /></td>
					</tr>
					<tr>
						<td colspan="2" class="tab1"><strong>Contenu</strong></td>
					</tr>
					<tr>
						<td>Image (facultative) : </td>	
						<td>
							'.(($image != "")?"<img src=\"".$image."\" alt=\"Image\" /><br />":"").'
							<input type="text" value="'.$image.'" name="image" />
						</td>
					</tr>
					<tr>
						<td colspan="2"><textarea style="padding:10px;width:400px;height:200px;text-align:justify;" name="contenu">'.$contenu.'</textarea></td>
					</tr>
					');
				
				if($_GET['modif'] == "0") print('<tr>
					<td colspan="2" class="tab1"><input type="submit" name="submit" value="Sauvegarder le nouvel article" /> - <a href="engine=services.php?lieu=dcn&action=redac">Retour</a></td><td>
				</tr>');
				else print('<tr>
					<td colspan="2" class="tab1"><input type="submit" name="submit" value="Enregistrer les modifications" /> - <a href="engine=services.php?lieu=dcn&action=redac">Retour</a></td><td>
				</tr>');
			
			print('</table>
			</form>
			');
			}
		}
	elseif($_GET['lieu']=="dcn" && $_GET['action']=="redac" && $_GET['modif']!="" && $_GET['valider']!="")
		{
		$sql = 'SELECT numero FROM DCN_numeros_tbl WHERE paru=0' ;
		$req = mysql_query($sql);
		$dcn_num = mysql_result($req,0,numero);
		
		if($_GET['modif'] == 0) $sql = 'INSERT INTO DCN_espaces_tbl(id,pseudo,auteur,titre,contenu,description,image,num_dcn,num_article) VALUES("","'.$_SESSION['pseudo'].'","'.htmlentities($_POST['auteur']).'","'.htmlentities($_POST['titre']).'","'.htmlentities(stripslashes($_POST['contenu'])).'","'.htmlentities($_POST['description']).'","'.htmlentities($_POST['image']).'","'.$dcn_num.'","0")' ;
		else $sql = 'UPDATE DCN_espaces_tbl SET titre="'.htmlentities($_POST['titre']).'",auteur="'.htmlentities($_POST['auteur']).'",contenu="'.htmlentities(stripslashes($_POST['contenu'])).'",description="'.htmlentities($_POST['description']).'",image="'.htmlentities($_POST['image']).'" WHERE id="'.htmlentities($_GET['modif']).'"' ;
		
		mysql_query($sql);
		
		print('<p>
		Modification effectuée.<br /><br />
		
		<a href="engine=services.php?lieu=dcn&action=redac">Retour</a>
		</p>');
		}
	elseif($_GET['lieu']=="dcn" && $_GET['action']=="redac" && $_GET['valider']!="")
		{
		$sql = 'UPDATE DCN_espaces_tbl SET contenu="'.stripslashes(htmlentities($_POST['contenu'])).'" WHERE pseudo="'.$_SESSION['pseudo'].'"' ;
		mysql_query($sql);
		
		print('<p>
		Modification effectuée.<br /><br />
		
		<a href="engine=services.php?lieu=dcn">Retour</a>
		</p>');
		}
	elseif($_GET['lieu']=="dcn" && $_GET['action']=="rendu")
		{
		$sql = 'SELECT numero,contenu FROM DCN_numeros_tbl WHERE paru=0';
		$req = mysql_query($sql);
		
		print('<p>
		<a href="engine=services.php?lieu=dcn">Retour</a>
		<h3>DreadCast News n°'.mysql_result($req,0,numero).'</h3>
		<div id="affiche_DCN_interne">'.affiche_DCN(mysql_result($req,0,contenu)).'</div>
		</p>');
		}
	elseif($_GET['lieu']=="dcn" && $_GET['action']=="liste" && $_GET['numero']=="")
		{
		$sql = 'SELECT numero,date FROM DCN_numeros_tbl WHERE paru=1 ORDER BY id DESC';
		$req = mysql_query($sql);
		$res = mysql_num_rows($req);
		
		print('<p>Liste des DCN déjà parus :<br /><br />');
		
		for($i=0;$i<$res;$i++) print('<a href="engine=services.php?lieu=dcn&action=liste&numero='.mysql_result($req,$i,numero).'">DreadCast News n°'.mysql_result($req,$i,numero).'</a> paru le '.date("d-m-Y",mysql_result($req,$i,date)).'<br />');
		
		print('<br /><a href="engine=services.php?lieu=dcn">Retour</a></p>');
		}
	elseif($_GET['lieu']=="dcn" && $_GET['action']=="liste" && $_GET['numero']!="")
		{
		$sql = 'SELECT numero,contenu FROM DCN_numeros_tbl WHERE numero="'.htmlentities($_GET['numero']).'"';
		$req = mysql_query($sql);
		$res = mysql_num_rows($req);
		if($res != 0)
			{
			print('<p>
			<a href="engine=services.php?lieu=dcn&action=liste">Retour</a>
			<h3>DreadCast News n°'.mysql_result($req,0,numero).'</h3>
			<div id="affiche_DCN_interne">'.affiche_DCN(mysql_result($req,0,contenu)).'</div>
			</p>');
			}
		}
	elseif($_GET['lieu']=="aitl")
		{
			$sql = 'SELECT * FROM articlesprop_tbl';
			$req = mysql_query($sql);
			$nb_articles = mysql_num_rows($req);
			
			print('<p>
			Bienvenue dans l\'outil de gestion de l\'AITL.<br /><br />
			
			<strong>L\'article du jour</strong><br />
			<a href="engine=panneaua.php">Valider les articles en attente</a> ('.$nb_articles.')<br />
			<em>Vous pouvez valider autant d\'articles que souhaité, l\'article du jour changera automatiquement chaque jour.</em><br /><br />
			
			<strong>Le tip\' du jour</strong><br />
			<a href="engine=ajoutertip.php">Ajouter un tip</a><br />
			<em>Vous pouvez ajouter autant de tip\' que souhaité.</em><br /><br />
			
			<strong>La citation du jour</strong><br />
			<a href="engine=ajoutercit.php">Ajouter une citation</a><br />
			<em>Vous pouvez ajouter autant de citations que souhaité.</em><br /><br />
			
			<a href="engine=services.php">Retour</a>
			</p>');
		}
	elseif($_GET['lieu']=="dctv" && $_GET['action']=="")
		{
		print('<p>
		Bienvenue au coeur de la DreadCast TeleVision.
		</p>
		
		<p>
		Vos actions :<br />
		<br />');
		if($_SESSION['poste'] == "Responsable de la DC TV" || $_SESSION["poste"] == "Directeur du DC Network" || $_SESSION["statut"] == "Administrateur")
			print('<a href="engine=services.php?lieu=dctv&action=abonnement">Fixer le prix de l\'abonnement</a><br /><br />');
					
		print('<a href="engine=services.php?lieu=dctv&action=maquette">Accès à la maquette en cours</a><br />
		<a href="engine=services.php?lieu=dctv&action=montage">Accès à mon espace de montage</a><br />
		<a href="engine=services.php?lieu=dctv&action=rendu">Rendu de la programmation</a><br />
		<a href="engine=services.php?lieu=dctv&action=liste">Liste des clips de la DCTV</a>
		</p>');
		}
	elseif($_GET['lieu']=="dctv" && $_GET['action']=="abonnement" && $_POST['nv_nombre']=="" && $_POST['nv_prix']=="")
		{
		if($_SESSION['poste'] != "Responsable de la DC TV" && $_SESSION["poste"] != "Directeur du DC Network" && $_SESSION["statut"] != "Administrateur")
			{
			print('<meta http-equiv="refresh" content="0 ; url=engine=services.php?lieu=dctv"> ');
			exit();
			}
		
		$sql = 'SELECT abonne,nombre FROM DCN_abonnes_tbl WHERE medium="DCTV" AND abonne LIKE "DCTV %"' ;
		$req = mysql_query($sql);
		$res = mysql_num_rows($req);
		if($res!=0) { $duree_abonnement = str_replace("DCTV ","",mysql_result($req,0,abonne)); $prix_abonnement = mysql_result($req,0,nombre); }
		
		print('<p>
		L\'abonnement actuel à la DreadCast TeleVision est de <strong>'.$prix_abonnement.'</strong> crédits pour <strong>'.$duree_abonnement.'</strong> an(s).<br />
		Souhaitez-vous le modifier ?<br /><br />
		
		<div id="liens_prix_vente"><a class="commelien" onclick="javascript:$(\'#prix_vente\').show();$(\'#liens_prix_vente\').hide();">Modifier l\'abonnement</a> - <a href="engine=services.php?lieu=dctv">Retour</a></div>
		<form action="engine=services.php?lieu=dctv&action=abonnement" method="post" id="prix_vente" style="display:none;">
			<input type="text" value="'.$duree_abonnement.'" style="width:35px;text-align:right;" name="nv_nombre" /> an(s) pour <input type="text" value="'.$prix_abonnement.'" style="width:35px;text-align:right;" name="nv_prix" /> crédits - <input type="submit" name="submit" value="Valider" />
		</form>
		</p>');
		}
	elseif($_GET['lieu']=="dctv" && $_GET['action']=="abonnement" && $_POST['nv_nombre']>"0" && $_POST['nv_prix']!="")
		{
		if($_SESSION['poste'] != "Responsable de la DC TV" && $_SESSION["poste"] != "Directeur du DC Network" && $_SESSION["statut"] != "Administrateur")
			{
			print('<meta http-equiv="refresh" content="0 ; url=engine=services.php?lieu=dctv"> ');
			exit();
			}
		
		$sql = 'UPDATE DCN_abonnes_tbl SET abonne="DCTV '.htmlentities($_POST['nv_nombre']).'",nombre="'.htmlentities($_POST['nv_prix']).'" WHERE medium="DCTV" AND abonne LIKE "DCTV %"' ;
		mysql_query($sql);

		print('<p>
		Modification effectuée.<br /><br />
		
		<a href="engine=services.php?lieu=dctv">Retour</a>
		</p>');
		}
	elseif($_GET['lieu']=="dctv" && $_GET['action']=="maquette")
		{
		
		if($_POST['submit'] == "Retirer" && $_GET['id'] != "")
			{
			$sql = 'SELECT clip FROM DCN_programmation_tbl WHERE id='.htmlentities($_GET['id']);
			$req = mysql_query($sql);
			$sql = 'SELECT id FROM DCN_programmation_tbl WHERE clip='.mysql_result($req,0,clip);
			$req = mysql_query($sql);
			$res = mysql_num_rows($req);
			
			if($res == 1)
				{
				$sql = 'UPDATE DCN_programmation_tbl SET date_debut=0 WHERE id='.htmlentities($_GET['id']);
				mysql_query($sql);
				}
			else
				{
				$sql = 'DELETE FROM DCN_programmation_tbl WHERE id='.htmlentities($_GET['id']);
				mysql_query($sql);
				}
			}
			
		if($_POST['submit'] == "Supprimer" && $_GET['id'] != "" && ($_SESSION['poste'] == "Responsable de la DC TV" || $_SESSION["poste"] != "Directeur du DC Network" || $_SESSION['statut'] == "Administrateur"))
			{
			$sql = 'SELECT clip FROM DCN_programmation_tbl WHERE id='.htmlentities($_GET['id']);
			$req = mysql_query($sql);
			$sql = 'DELETE FROM DCN_programmation_tbl WHERE clip='.mysql_result($req,0,clip);
			mysql_query($sql);
			}
		
		$i=0;
		for($i;$i<=24;$i++)
			{
			if($_POST['submit-'.$i] != "")
				{
				if($_POST['heure_fin-'.$i] - $_POST['heure_debut-'.$i] <= 0) break;
				
				$sql = 'SELECT id FROM DCN_programmation_tbl WHERE date_debut = 0 AND clip='.htmlentities($_POST['evt']);
				$req = mysql_query($sql);
				$res = mysql_num_rows($req);
				if($res != 0)
					{
					$sql = 'UPDATE DCN_programmation_tbl SET date_debut="'.mktime($_POST['heure_debut-'.$i],0,0,date(m,timestamp_jour($_POST['jour'])),date(d,timestamp_jour($_POST['jour'])),date(Y,timestamp_jour($_POST['jour']))).'",duree="'.($_POST['heure_fin-'.$i]-$_POST['heure_debut-'.$i]).'" WHERE date_debut = 0 AND clip = '.htmlentities($_POST['evt']);
					mysql_query($sql);
					}
				else
					{
					$sql = 'INSERT INTO DCN_programmation_tbl(date_debut,duree,clip) VALUES("'.mktime($_POST['heure_debut-'.$i],0,0,date(m,timestamp_jour($_POST['jour'])),date(d,timestamp_jour($_POST['jour'])),date(Y,timestamp_jour($_POST['jour']))).'","'.htmlentities($_POST['heure_fin-'.$i]-$_POST['heure_debut-'.$i]).'","'.htmlentities($_POST['evt']).'")';
					mysql_query($sql);
					}
			
				break;
				}
			}
		
		print('<p>
		<h3>Maquette en cours</h3>
			<table id="tablemaquette">
				<tr>
					<td colspan="2" class="tab1"><strong>Samedi</strong></td>
				</tr>
				');
				
				affiche_infos_programmation('Samedi');
				
				print('
				<tr>
					<td colspan="2" class="tab1"><strong>Dimanche</strong></td>
				</tr>
				');
				
				affiche_infos_programmation('Dimanche');
				
				print('
				<tr>
					<td colspan="2" class="tab1"><strong>Lundi</strong></td>
				</tr>
				');
				
				affiche_infos_programmation('Lundi');
				
				print('
				<tr>
					<td colspan="2" class="tab1"><strong>Mardi</strong></td>
				</tr>
				');
				
				affiche_infos_programmation('Mardi');
				
				print('
				<tr>
					<td colspan="2" class="tab1"><strong>Mercredi</strong></td>
				</tr>
				');
				
				affiche_infos_programmation('Mercredi');
				
				print('
				<tr>
					<td colspan="2" class="tab1"><strong>Jeudi</strong></td>
				</tr>
				');
				
				affiche_infos_programmation('Jeudi');
				
				print('
				<tr>
					<td colspan="2" class="tab1"><strong>Vendredi</strong></td>
				</tr>
				');
				
				affiche_infos_programmation('Vendredi');
				
				print('
			</table>
		</p>');
		}
	elseif($_GET['lieu']=="dctv" && $_GET['action']=="montage" && !ereg("modif",$_GET['type']))
		{
		
		if(ereg("retirer-",$_GET['type']))
			{
			$id = htmlentities(str_replace("retirer-","",$_GET['type']));
			$sql = 'SELECT id FROM DCN_clips_tbl WHERE id='.$id.' AND pseudo="'.$_SESSION['pseudo'].'"';
			$req = mysql_query($sql);
			$res = mysql_num_rows($req);
			if($res != 0)
				{
				$sql = 'DELETE FROM DCN_programmation_tbl WHERE clip='.$id.' AND (date_debut > '.timestamp_jour("Samedi").' OR date_debut = 0)';
				mysql_query($sql);
				}
			}
			
		if(ereg("proposer-",$_GET['type']))
			{
			$id = htmlentities(str_replace("proposer-","",$_GET['type']));
			$sql = 'SELECT id FROM DCN_clips_tbl WHERE id='.$id.' AND pseudo="'.$_SESSION['pseudo'].'"';
			$req = mysql_query($sql);
			$res = mysql_num_rows($req);
			if($res != 0)
				{
				$sql = 'SELECT id FROM DCN_programmation_tbl WHERE clip='.$id.' AND (date_debut > '.timestamp_jour("Samedi").' OR date_debut = 0)';
				$req = mysql_query($sql);
				$res = mysql_num_rows($req);
				if($res == 0)
					{
					$sql = 'INSERT INTO DCN_programmation_tbl(date_debut,duree,clip) VALUES("0","0","'.$id.'")';
					mysql_query($sql);
					}
				}
			}
		
		print('<p>
			<h3>Espace personnel de montage</h3>
			<form action="engine=services.php?lieu=dctv&action=montage&type=ajout" method="post">
				<table id="tablemaquette">
					<tr>
						<td colspan="2" class="tab1"><strong>Clips pour l\'ann&eacute;e &agrave; venir</strong></td>
					</tr>');
					
					$sql = 'SELECT id, nom, auteur, synopsis FROM DCN_clips_tbl WHERE pseudo="'.$_SESSION['pseudo'].'" ORDER BY id DESC';
					$req = mysql_query($sql);
					$res = mysql_num_rows($req);
					
					$ids[0][0] = '';
					
					for($i=0;$i<$res;$i++)
						{
						$sql2 = 'SELECT date_debut,duree FROM DCN_programmation_tbl WHERE clip='.mysql_result($req,$i,id).' AND (date_debut > '.timestamp_jour("Samedi").' OR date_debut = 0) ORDER BY date_debut' ;
						$req2 = mysql_query($sql2);
						$res2 = mysql_num_rows($req2);
						
						if($res2 != 0)
							{
							print('<tr>
								<td colspan="2"><div class="article">
								<strong>'.mysql_result($req,$i,nom).'</strong><br />
								<em>Par '.mysql_result($req,$i,auteur).'</em><br />
								');
								
								if(mysql_result($req2,0,date_debut) == 0 && $res2 == 1) print('<strong>Statut</strong> : <span style="colore:red;">En attente de validation</span><br />');
								else
									{
									print('<strong>Statut</strong> : <span style="colore:green;">Valid&eacute pour les dates suivantes<br />');
									for($j=0;$j<$res2;$j++) { if(mysql_result($req2,$j,date_debut) != 0) print(jour(mysql_result($req2,$j,date_debut)).' de '.heure(mysql_result($req2,$j,date_debut)).'h à '.heure(mysql_result($req2,$j,date_debut)+mysql_result($req2,$j,duree)*60*60).'h<br />'); }
									print('</span>');
									}
								
								print('
								<a href="#" onclick="javascript:$(\'#contenu'.$i.'\').show();$(\'#masquer'.$i.'\').show();$(\'#afficher'.$i.'\').hide();" id="afficher'.$i.'">Afficher</a>
								<a href="#" onclick="javascript:$(\'#contenu'.$i.'\').hide();$(\'#masquer'.$i.'\').hide();$(\'#afficher'.$i.'\').show();" id="masquer'.$i.'" style="display:none;">Masquer</a> - <a href="engine=services.php?lieu=dctv&action=montage&type=modif-'.mysql_result($req,$i,id).'">Modifier</a> - <a href="engine=services.php?lieu=dctv&action=montage&type=retirer-'.mysql_result($req,$i,id).'">Retirer</a>
								<div id="contenu'.$i.'" style="text-align:center;display:none;padding:5px;">
									'.nl2br(mysql_result($req,$i,synopsis)).'<br />
									<a href="engine=services.php?lieu=dctv&action=voirclip&clip='.mysql_result($req,$i,id).'">Voir le clip</a>
								</div>
								</div>
								</td>
							</tr>');
							
							$ids[$i][0] = -1;
							}
						else
							{
							$ids[$i][0] = mysql_result($req,$i,id);
							$ids[$i][1] = mysql_result($req,$i,nom);
							$ids[$i][2] = mysql_result($req,$i,auteur);
							$ids[$i][3] = mysql_result($req,$i,synopsis);
							}
						}
					
					print('
					<tr>
						<td colspan="2" class="tab1"><strong>Mes autres clips</strong></td>
					</tr>
					');
					
					for($i=0;$i<count($ids);$i++)
						{
						
						if($ids[$i][0] != -1)
							{
							print('<tr>
								<td colspan="2"><div class="article">
								<strong>'.$ids[$i][1].'</strong><br />
								<em>Par '.$ids[$i][2].'</em><br />
								<a href="#" onclick="javascript:$(\'#contenu'.$i.'\').show();$(\'#masquer'.$i.'\').show();$(\'#afficher'.$i.'\').hide();" id="afficher'.$i.'">Afficher</a>
								<a href="#" onclick="javascript:$(\'#contenu'.$i.'\').hide();$(\'#masquer'.$i.'\').hide();$(\'#afficher'.$i.'\').show();" id="masquer'.$i.'" style="display:none;">Masquer</a> - <a href="engine=services.php?lieu=dctv&action=montage&type=modif-'.$ids[$i][0].'">Modifier</a> - <a href="engine=services.php?lieu=dctv&action=montage&type=proposer-'.$ids[$i][0].'">Proposer</a>
								<div id="contenu'.$i.'" style="text-align:center;display:none;padding:5px;">
									'.nl2br($ids[$i][3]).'<br />
									<a href="engine=services.php?lieu=dctv&action=voirclip&clip='.$ids[$i][0].'">Voir le clip</a>
								</div>
								</div>
								</td>
							</tr>');
							}
						}
					
					print('<tr>
						<td colspan="2"><div class="article"><a href="engine=services.php?lieu=dctv&action=montage&type=modif-0">+ Créer un nouveau clip</a>
						</div>
						</td>
					</tr>
				</table>
			</form>
			');
		}
	elseif($_GET['lieu']=="dctv" && $_GET['action']=="montage" && ereg("modif",$_GET['type']))
		{
		
		$id = htmlentities(str_replace("modif-","",$_GET['type']));
		
		if($_POST['submit'] != '' && $id == 0)
			{
			$titre = htmlentities(stripslashes($_POST['titre']));
			$auteur = htmlentities(stripslashes($_POST['auteur']));
			$synopsis = htmlentities(stripslashes($_POST['synopsis']));
			$code = stripslashes($_POST['code']);
			$lien = preg_replace("#^(.+)name\=\"movie\" value\=\"(.+)\"(.+)$#isU","$2",$code);
			
			$sql = 'INSERT INTO DCN_clips_tbl(nom,pseudo,auteur,synopsis,lien) VALUES("'.$titre.'","'.$_SESSION['pseudo'].'","'.$auteur.'","'.$synopsis.'","'.$lien.'")';
			mysql_query($sql);
			
			$sql = 'SELECT id FROM DCN_clips_tbl WHERE pseudo="'.$_SESSION['pseudo'].'" ORDER BY id DESC LIMIT 1';
			$req = mysql_query($sql);
			$id = mysql_result($req,0,id);
			
			print('<h4>Clip créé<br /><a href="engine=services.php?lieu=dctv&action=voirclip&clip='.$id.'">Voir le clip</a></h4>');
			}
		elseif($_POST['submit'] != '')
			{
			$titre = htmlentities(stripslashes($_POST['titre']));
			$auteur = htmlentities(stripslashes($_POST['auteur']));
			$synopsis = htmlentities(stripslashes($_POST['synopsis']));
			$code = stripslashes($_POST['code']);
			$lien = preg_replace("#^(.+)name\=\"movie\" value\=\"(.+)\"(.+)$#isU","$2",$code);
			
			$sql = 'SELECT nom,auteur,synopsis,lien FROM DCN_clips_tbl WHERE id='.$id.' AND pseudo="'.$_SESSION['pseudo'].'"';
			$req = mysql_query($sql);
			$res = mysql_num_rows($req);
			
			if($res != 0)
				{
				$sql = 'UPDATE DCN_clips_tbl SET nom="'.$titre.'", auteur="'.$auteur.'", synopsis="'.$synopsis.'" '.(($lien != "")?', lien="'.$lien.'"':'').' WHERE id='.$id.' AND pseudo="'.$_SESSION['pseudo'].'"';
				mysql_query($sql);
				
				print('<h4>Modification effectuée<br /><a href="engine=services.php?lieu=dctv&action=voirclip&clip='.$id.'">Voir le clip</a></h4>');
				}
			}
		
		
		if($id != 0)
			{
			$sql = 'SELECT nom,auteur,synopsis,lien FROM DCN_clips_tbl WHERE id='.$id.' AND pseudo="'.$_SESSION['pseudo'].'"';
			$req = mysql_query($sql);
			$res = mysql_num_rows($req);
			
			if($res != 0)
				{
				$titre = mysql_result($req,0,nom);
				$auteur = mysql_result($req,0,auteur);
				$synopsis = mysql_result($req,0,synopsis);
				$lien = mysql_result($req,0,lien);
				}
			}
		
		print('<p>
			<h3>Espace personnel de montage</h3>
			<form action="engine=services.php?lieu=dctv&action=montage&type=modif-'.$id.'" method="post">
				<table id="tablemaquette">
					<tr>
						<td colspan="2" class="tab1"><strong>Titre</strong></td>
					</tr>
					<tr>
						<td colspan="2"><input type="text" value="'.$titre.'" name="titre" /></td>
					</tr>
					<tr>
						<td colspan="2" class="tab1"><strong>Auteur</strong></td>
					</tr>
					<tr>
						<td colspan="2"><input type="text" value="'.$auteur.'" name="auteur" /></td>
					</tr>
					<tr>
						<td colspan="2" class="tab1"><strong>Synopsis</strong></td>
					</tr>
					<tr>
						<td colspan="2"><textarea name="synopsis">'.$synopsis.'</textarea></td>
					</tr>
					<tr>
						<td colspan="2" class="tab1"><strong>Code fourni par l\'hébergeur</strong></td>
					</tr>
					<tr>
						<td colspan="2"><textarea name="code"></textarea></td>
					</tr>
					<tr>
						<td colspan="2" class="tab1"><input type="submit" name="submit" value="'.(($id == 0)?'Sauvegarder le nouveau clip':'Enregistrer les modifications').'" /> - <a href="engine=services.php?lieu=dctv&action=montage">Retour</a></td><td>
					</tr>
				</table>
			</form>
			');
		}
	elseif($_GET['lieu']=="dctv" && $_GET['action']=="rendu")
		{
		print('<p>
		<div id="programme_prive">'.affiche_DCTV_programme(time()+(60*60*24*7)).'</div>
		</p>');
		}
	elseif($_GET['lieu']=="dctv" && $_GET['action']=="liste")
		{
		$sql = 'SELECT * FROM DCN_clips_tbl ORDER BY id DESC';
		$req = mysql_query($sql);
		$res = mysql_num_rows($req);
		
		print('<p>
		<h3 style="margin-bottom:30px;">Liste des clips de la DCTV</h3>
		
		<table>
			');
		
		for($i=0;$i<$res;$i++)
			print('<tr>
				<td><a href="engine=services.php?lieu=dctv&action=voirclip&clip='.mysql_result($req,$i,id).'">'.mysql_result($req,$i,nom).'</a></td>
				<td>&nbsp;&nbsp;Par '.mysql_result($req,$i,auteur).'</td>
			</tr>
			');
		
		print('</table>
		</p>');
		}
	elseif($_GET['lieu']=="dctv" && $_GET['action']=="voirclip" && $_GET['clip']!="")
		{
		$sql = 'SELECT * FROM DCN_clips_tbl WHERE id='.htmlentities($_GET['clip']);
		$req = mysql_query($sql);
		$res = mysql_num_rows($req);
		if($res!=0)
		print('<p>
		<h3>'.mysql_result($req,0,nom).'</h3>
		<h4 style="font-weight:normal;margin-bottom:10px;">Par '.mysql_result($req,0,auteur).' - <a href="engine=services.php?lieu=dctv">Retour</a></h4>
		<div style="margin-bottom:10px;">'.affiche_DCTV_visio(mysql_result($req,0,lien)).'</div>
		<div>'.mysql_result($req,0,synopsis).'</div>
		</p>');
		}
	
	print('</div>');
	}
	
mysql_close($db);

?>
</div>
</div>
<?php if($chat=="oui") 	{ if ((date("H")>=21) || (date("H")<=7)) { include("inc_bas_n_c.php"); } else { include("inc_bas_j_c.php"); } } else {	if ((date("H")>=21) || (date("H")<=7)) { include("inc_bas_n.php"); } else { include("inc_bas_j.php"); }	} ?>
<?php include("inc_bas_de_page.php"); ?>
