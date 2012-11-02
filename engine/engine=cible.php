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
if(ereg($_SERVER['QUERY_STRING'],$_SESSION['personnes']))
	{
	}
else
	{
	$_SESSION['personnes'] = "";
	print('<meta http-equiv="refresh" content="0 ; url=http://v2.dreadcast.net/ingame/engine.php"> ');
	exit();
	}

$db = mysql_connect('localhost', 'CENSURE', 'CENSURE') or die("Erreur de connexion au serveur des messages.");
mysql_select_db('CENSURE',$db) or die("Erreur de connexion a la base de donnees des messages.");

if($_SESSION['num'] <= 0)
	{
	$num = 0;
	$lieu = "Rue";
	}
else
	{
	$num = $_SESSION['num'];
	$lieu = $_SESSION['lieu'];
	}
$sql = 'SELECT code FROM lieu_tbl WHERE rue= "'.$lieu.'" AND num= "'.$num.'"' ;
$req = mysql_query($sql);
$codounet = mysql_result($req,0,code);

if($_SESSION['code']!=$codounet)
	{
	mysql_close($db);
	print('<meta http-equiv="refresh" content="0 ; url=http://v2.dreadcast.net/ingame/engine.php"> ');
	exit();
	}

mysql_close($db);

?>
<?php if ((date("H")>=21) || (date("H")<=7)) { include("inc_haut_n.php"); } else { include("inc_haut_j.php");} ?>

<div id="haut">
	<?php include("inc_entete.php"); ?>
	<div class="haut_aligngauche">
		<p>
		<div class="titrepage">
			Individu cibl&eacute;
		</div>
		<b class="module4ie"><a href="engine=liste.php" class="module4"><img src="im_objets/icon_retour.gif" alt="Retour" />Retour</a></b>
</p>
	</div>
</div>
<div id="centre">
<p>

<?php	

$db = mysql_connect('localhost', 'CENSURE', 'CENSURE') or die("Erreur de connexion au serveur.");
mysql_select_db('CENSURE',$db) or die("Erreur de connexion a la base de donnees.");

$sql = 'SELECT id,rue,num,action,medecine,vol,entreprise,type,arme,objet,vetements,case1,case2,case3,case4,case5,case6,maladie,observation FROM principal_tbl WHERE pseudo= "'.$_SESSION['pseudo'].'"' ;
$req = mysql_query($sql);
$_SESSION['id'] = mysql_result($req,0,id);
$_SESSION['lieu'] = ucwords(mysql_result($req,0,rue));
$_SESSION['num'] = mysql_result($req,0,num);
$_SESSION['action'] = mysql_result($req,0,action);
$_SESSION['medecine'] = mysql_result($req,0,medecine);
$_SESSION['vol'] = mysql_result($req,0,vol);
$_SESSION['entreprise'] = mysql_result($req,0,entreprise);
$_SESSION['type'] = mysql_result($req,0,type);
$_SESSION['arme'] = mysql_result($req,0,arme);
$_SESSION['objet'] = mysql_result($req,0,objet);
$_SESSION['vetements'] = mysql_result($req,0,vetements);
$_SESSION['case1'] = mysql_result($req,0,case1);
$_SESSION['case2'] = mysql_result($req,0,case2);
$_SESSION['case3'] = mysql_result($req,0,case3);
$_SESSION['case4'] = mysql_result($req,0,case4);
$_SESSION['case5'] = mysql_result($req,0,case5);
$_SESSION['case6'] = mysql_result($req,0,case6);
$_SESSION['malade'] = mysql_result($req,0,maladie);
$observationa = mysql_result($req,0,observation);

forme_retirer($_SESSION['id'],1);
$_SESSION['fatigue'] -= 1;

$sqla = 'SELECT nature,bonus FROM recherche_effets_tbl WHERE ido=(SELECT id FROM objets_tbl WHERE nom= "'.$_SESSION['objet'].'") OR ido=(SELECT id FROM objets_tbl WHERE nom= "'.$_SESSION['vetements'].'") OR ido=(SELECT id FROM objets_tbl WHERE nom= "'.substr($_SESSION['arme'],0,strpos($_SESSION['arme'],"-")).'")';
$reqa = mysql_query($sqla);
$resa = mysql_num_rows($reqa);
for($i=0;$i!=$resa;$i++)
	{
	if(mysql_result($reqa,$i,nature)=="focus") { $bonus = "focus"; }
	}

$sql = 'SELECT distance FROM objets_tbl WHERE nom= "'.$_SESSION['arme'].'"' ;
$req = mysql_query($sql);
$distarme = mysql_result($req,0,distance);

$sql = 'SELECT * FROM armes_tbl WHERE idarme= "'.strstr($_SESSION['arme'],"-").'"' ;
$req = mysql_query($sql);
$res = mysql_num_rows($req);
if($res>0)
	{
	$modif11 = mysql_result($req,0,modif1);
	$modif12 = mysql_result($req,0,modif2);
	$modif13 = mysql_result($req,0,modif3);
	if($modif11==3 || $modif12==3 || $modif13==3) { $distarme = $distarme + 10; }
	}

$cible = str_replace("%20"," ",''.$_SERVER['QUERY_STRING'].'');

if($cible==$_SESSION['pseudo'])
	{
	print('<meta http-equiv="refresh" content="0 ; url=http://v2.dreadcast.net/ingame/engine.php"> ');
	exit();	
	}

if(est_mort($cible))
	{
	print('<meta http-equiv="refresh" content="0 ; url=http://v2.dreadcast.net/ingame/engine.php"> ');
	exit();	
	}

$sql = 'SELECT id,statut,rue,num,action,sante,race,avatar,connec,sexe,sante_max,objet,arme,vetements,chargeur,maladie,case1,case2,case3,case4,case5,case6,event FROM principal_tbl WHERE pseudo= "'.$cible.'"' ;
$req = mysql_query($sql);
$res = mysql_num_rows($req);

if($res>0)
	{
	$_SESSION['idc'] = mysql_result($req,0,id);
	$racec = ucwords(mysql_result($req,0,race));
	$_SESSION['lieuc'] = ucwords(mysql_result($req,0,rue));
	$_SESSION['numc'] = mysql_result($req,0,num);
	$sexec = mysql_result($req,0,sexe);
	$actionc = mysql_result($req,0,action);
	$santec = mysql_result($req,0,sante);
	$santecmax = mysql_result($req,0,sante_max);
	$avatarc = mysql_result($req,0,avatar);
	$connec = mysql_result($req,0,connec);
	$_SESSION['objetc'] = mysql_result($req,0,objet);
	$_SESSION['armec'] = mysql_result($req,0,arme);
	$_SESSION['vetementc'] = mysql_result($req,0,vetements);
	$_SESSION['chargeurc'] = mysql_result($req,0,chargeur);
	$maladiec = mysql_result($req,0,maladie);
	$statutc = mysql_result($req,0,statut);
	$case1c = mysql_result($req,0,case1);
	$case2c = mysql_result($req,0,case2);
	$case3c = mysql_result($req,0,case3);
	$case4c = mysql_result($req,0,case4);
	$case5c = mysql_result($req,0,case5);
	$case6c = mysql_result($req,0,case6);
	$event = mysql_result($req,0,event);
	$event = event() && estDroide(mysql_result($req,0,race)) ? $event : 0;
	$virusc = $event == 1 || adm() ? true : false;

	if (event(3)) {
		$sql2 = 'SELECT id FROM objets_repares_tbl WHERE id_cible = '.$_SESSION['idc'];
		$req2 = mysql_query($sql2);
		if (!mysql_num_rows($req2)) {
			$noWay = true;
		}
	}
	
	}
else
	{
	$l = 1;
	print('<p align="center"><strong>Ce pseudo n\'existe pas.</strong></p>');
	}

if($sexec=="Homme")
	{
	$sexec = "Masculin";
	}
elseif($sexec=="Femme")
	{
	$sexec = "Feminin";	
	}

if($sexec=="Masculin")
	{
	$imagesexe = "masculin";
	}
elseif($sexec=="Feminin")
	{
	$imagesexe = "feminin";
	}

if($_SESSION['malade']==1 && $maladiec==0 && $statutc != 'Debutant')
	{
	$sql = 'UPDATE principal_tbl SET maladie= "1" WHERE id= "'.$_SESSION['idc'].'"' ;
	$req = mysql_query($sql);
	}
elseif($_SESSION['malade']==0 && $maladiec==1 && $statutc != 'Debutant')
	{
	$sql = 'UPDATE principal_tbl SET maladie= "1" WHERE id= "'.$_SESSION['id'].'"' ;
	$req = mysql_query($sql);
	}

$sql = 'SELECT image FROM objets_tbl WHERE nom= "'.$_SESSION['objetc'].'"' ;
$req = mysql_query($sql);
if(mysql_num_rows($req) != 0) $ioc = mysql_result($req,0,image);

$sql = 'SELECT image FROM objets_tbl WHERE nom= "'.$_SESSION['armec'].'"' ;
$req = mysql_query($sql);
if(mysql_num_rows($req) != 0) $iac = mysql_result($req,0,image);

$sql = 'SELECT image FROM objets_tbl WHERE nom= "'.$_SESSION['vetementc'].'"' ;
$req = mysql_query($sql);
if(mysql_num_rows($req) != 0) $ivc = mysql_result($req,0,image);

if(!estVisible($cible,25))
	{
	$l = 1;
	print('<p align="center">Il est impossible de cibler <i>'.$cible.'</i> car il n\'est pas au même endroit que vous.</p>');
	}
elseif($l!=1)
	{
	print('<table width="90%"  border="0" align="center"><tr><td><p align="center">');
	if((ereg("http",$avatarc)) OR (ereg("ftp",$avatarc)))
		{
		if($maladiec==1) print('<img src="avatars/faucheur.jpg" border="1px" width="70" height="70" />');
		elseif($virusc) print('<img src="avatars/virusD.jpg" border="1px" width="70" height="70" />');
		else print('<img src="'.$avatarc.'" border="1px" width="70" height="70" />');
		}
	else
		{
		if($maladiec==1) print('<img src="avatars/faucheur.php?'.$avatarc.'" border="0" />');
		elseif($virusc) print('<img src="avatars/faucheur.php?img=virusD.jpg&url='.$avatarc.'" border="0" />');
		else print('<img src="avatars/'.$avatarc.'" border="0" />');
		}
	print('</p></td><td>');
	print('<p align="center">Vous avez cibl&eacute; <strong><em>'.$cible.'</em></strong> ');
	if(($_SESSION['case1']=="Carnet") || ($_SESSION['case2']=="Carnet") || ($_SESSION['case3']=="Carnet") || ($_SESSION['case4']=="Carnet") || ($_SESSION['case5']=="Carnet") || ($_SESSION['case6']=="Carnet"))
		{
		$sql = 'SELECT statut FROM carnets_tbl WHERE pseudo= "'.$_SESSION['pseudo'].'" AND contact= "'.$cible.'"' ;
		$req = mysql_query($sql);
		$p = mysql_num_rows($req);
		if($p!=1)
			{
			print('<a id="ajcarnet3" href="engine=carnet.php?affiche=contacts&ajoutco='.$cible.'"></a>');
			}
		else
			{
			print('('.mysql_result($req,0,statut).') ');
			}
		}
	$sql = 'SELECT combat,observation,gestion,maintenance,mecanique,service,discretion,economie,resistance,tir,vol,medecine,recherche,informatique FROM principal_tbl WHERE id= "'.$_SESSION['idc'].'"' ;
	$req = mysql_query($sql);
	$statc['combat'] = mysql_result($req,0,combat);
	$statc['observation'] = mysql_result($req,0,observation);
	$statc['gestion'] = mysql_result($req,0,gestion);
	$statc['maintenance'] = mysql_result($req,0,maintenance);
	$statc['mecanique'] = mysql_result($req,0,mecanique);
	$statc['service'] = mysql_result($req,0,service);
	$statc['discretion'] = mysql_result($req,0,discretion);
	$statc['economie'] = mysql_result($req,0,economie);
	$statc['resistance'] = mysql_result($req,0,resistance);
	$statc['tir'] = mysql_result($req,0,tir);
	$statc['vol'] = mysql_result($req,0,vol);
	$statc['medecine'] = mysql_result($req,0,medecine);
	$statc['informatique'] = mysql_result($req,0,informatique);
	$statc['recherche'] = mysql_result($req,0,recherche);
	
	if($connec=="oui")
		{
		print(' <img src="im_objets/eclair1.gif" border="0" title="Connect&eacute;"> ');
		}
	print(' <img src="im_objets/avatar_'.ucwords($racec).'.gif" border="0" title="'.ucwords($racec).'"> ');
	print('<img border="0" title="'.$sexec.'" src="im/'.$imagesexe.'.gif" /><br />');
	
	
	
	arsort($statc);
					
	foreach($statc as $stat => $valeur) {
		affiche_etoile($stat,$valeur);
	}
		
		
		
	print('</p>');
	if($sexec=="Masculin")
		{
		print('<p align="center"><strong>Il a l\'air  :</strong> ');
		}
	else
		{
		print('<p align="center"><strong>Elle a l\'air  :</strong> ');
		}
	print(sante_etat($santec,$santecmax).'<br />');
	if($_SESSION['distance']=="")
		{
		$_SESSION['distance'] = rand(10,25);
		}
	print('<strong>Distance :</strong> '.$_SESSION['distance'].' mètres <em>[Secteur '.secteur($_SESSION['numc'], $_SESSION['lieuc']).']</em>');
	
	$sql = 'SELECT pseudo FROM principal_tbl WHERE action = "Protection '.$cible.'" AND num = "'.$_SESSION['numc'].'" AND rue = "'.$_SESSION['lieuc'].'"' ;
	$req = mysql_query($sql);
	$res = mysql_num_rows($req);
	if($res != 0)
		{
		$protecteur = mysql_result($req,0,pseudo);
		
		$sql = 'SELECT discretion FROM principal_tbl WHERE pseudo = "'.$protecteur.'"' ;
		$req = mysql_query($sql);
		$discretionp = mysql_result($req,0,discretion) + bonus($protecteur,"discretion");
		$invisiblep = bonus($protecteur,"invisibilite");
		$observationa += bonus($_SESSION['pseudo'],"observation");
		$detecta = bonus($_SESSION['pseudo'],"detect");
		
		$peut_voir = $discretionp - $observationa + rand(-10,10);

		if($invisiblep && !$detecta)
			{
			}
		else
			{
			if($peut_voir > 0) 
				{
				}
			else
				{
				if($peut_voir < -30) print('<br />On dirait que <strong>'.$protecteur.'</strong> '.(($sexec == "Masculin")?'le':'la').' protège.');
				else print('<br />On dirait qu\''.(($sexec == "Masculin")?'il':'elle').' est protégé'.(($sexec == "Masculin")?'':'e').'.');
				}
			}
		}
	
	print('</p></td></tr></table>');
	if($bonus=="focus")
		{
		print('<table width="50%"  border="0" align="center"><tr><td><p align="center"><strong>'.$_SESSION['armec'].'</strong><br /><img src="im_objets/'.$iac.'" '); if(ereg("centre/",$iac)) print('width="73" height="73" style="border:1px solid black;"'); else print('border="0"'); print('></p></td>');
		print('<td><p align="center"><strong>'.$_SESSION['vetementc'].'</strong><br /><img src="im_objets/'.$ivc.'" '); if(ereg("centre/",$ivc)) print('width="73" height="73" style="border:1px solid black;"'); else print('border="0"'); print('></p></td>');
		print('<td><p align="center"><strong>'.$_SESSION['objetc'].'</strong><br /><img src="im_objets/'.$ioc.'" '); if(ereg("centre/",$ioc)) print('width="73" height="73" style="border:1px solid black;"'); else print('border="0"'); print('></p></td></tr></table>');
		}
	else
		{
		print('<table width="50%"  border="0" align="center"><tr><td><p align="center"><img src="im_objets/'.$iac.'" '); if(ereg("centre/",$iac)) print('width="73" height="73" style="border:1px solid black;"'); else print('border="0"'); print('></p></td>');
		print('<td><p align="center"><img src="im_objets/'.$ivc.'" '); if(ereg("centre/",$ivc)) print('width="73" height="73" style="border:1px solid black;"'); else print('border="0"'); print('></p></td>');
		print('<td><p align="center"><img src="im_objets/'.$ioc.'" '); if(ereg("centre/",$ioc)) print('width="73" height="73" style="border:1px solid black;"'); else print('border="0"'); print('></p></td></tr></table>');
		}
	}
?> 
<?php 
if($l!=1)
	{
	print('<form name="form2"><div align="center"><p>Voici la liste des interactions disponibles avec cet individu : </p><p><select name="menu1" onChange="MM_jumpMenu(\'parent\',this,0)"> 
<option value="engine=cibler.php?'.$cible.'" selected>Choisissez ici</option>');
	print('<option value="engine=attaquer.php?cible='.$cible.'&action=approcher">Vous approcher</option>');
	print('<option value="engine=attaquer.php?cible='.$cible.'&action=eloigner">Vous &eacute;loigner</option>');
	if(!$virusc && ($santec==0) && ($_SESSION['action']!="prison"))
		{
		print('<option value="engine=fouiller.php?'.$cible.'">Fouiller</option>');
		}
	if(!estDroide() && $virusc && ($santec==0) && ($_SESSION['action']!="prison"))
		{
		print('<option value="engine=reparer.php?'.$cible.'">R&eacute;parer</option>');
		}
	elseif(estDroide() && $noWay && ($_SESSION['action']!="prison"))
		{
		print('<option value="engine=reparer2.php?'.$cible.'">D&eacute;sinfecter</option>');
		}
	elseif(!$virusc && ($santec==0) && ($_SESSION['action']!="prison"))
		{
		print('<option value="engine=achever.php?'.$cible.'">Achever</option>');
		}
	if(($actionc!="prison") && ($santec!=0) && ($_SESSION['action']!="prison") && ($_SESSION['distance']<=$distarme))
		{
		print('<option value="engine=attaquer.php?cible='.$cible.'&action=attaquer">Attaquer</option>');
		}
	print('<option value="engine=contacter.php?cible='.$cible.'">Envoyer un message</option><option value="engine=do.php?'.$cible.'">Donner un objet</option><option value="engine=don.php?'.$cible.'">Donner du cr&eacute;dit</option>');
	if(($_SESSION['medecine']>0) && (($droguec==0 && $santec<$santecmax) || ($droguec>0 && $santec<drogue($cible,$santecmax))))
		{
		print('<option value="engine=soins.php?'.$cible.'">Gu&eacute;rir</option>');
		}
	if($_SESSION['case1']=="Menottes"||$_SESSION['case2']=="Menottes"||$_SESSION['case3']=="Menottes"||$_SESSION['case4']=="Menottes"||$_SESSION['case5']=="Menottes"||$_SESSION['case6']=="Menottes")
		{
		if($actionc!="prison")
			{
			print('<option value="engine=inspecter.php?'.$cible.'">Inspecter</option>');
			print('<option value="engine=attaquer.php?cible='.$cible.'&action=arrestation">Placer en &eacute;tat d\'arrestation</option>');
			}
		}
	if(($_SESSION['entreprise']=="Police") || ($_SESSION['entreprise']=="DI2RCO"))
		{
		if($actionc=="prison")
			{
			print('<option value="engine=liberer.php?'.$cible.'">Lib&eacute;rer de prison</option>');
			}
		}
	if(($_SESSION['entreprise']=="DI2RCO") && ($_SESSION['type']=="Directeur du DI2RCO") && ($case1c=="Laissez-passer" || $case2c=="Laissez-passer" || $case3c=="Laissez-passer" || $case4c=="Laissez-passer" || $case5c=="Laissez-passer" || $case6c=="Laissez-passer"))
		{
		print('<option value="engine=supprimerlpi.php?'.$cible.'">Confisquer le Laissez-passer</option>');
		}
	if(($_SESSION['entreprise']=="DI2RCO") && ($_SESSION['type']=="Directeur du DI2RCO") && est_dans_inventaire('Passe DI2RCO'))
		{
		print('<option value="engine=supprimerpassedi2rco.php?'.$cible.'">Confisquer le passe DI2RCO</option>');
		}

	if($_SESSION['action'] == "Protection ".$cible)
		{
		print('<option value="engine=protecstop.php">Arrêter la protection</option>');
		}
	elseif(!ereg("Protection ",$_SESSION['action']))
		{
		$sql = 'SELECT pseudo FROM principal_tbl WHERE action = "Protection '.$_SESSION['pseudo'].'"' ;
		$req = mysql_query($sql);
		$res = mysql_num_rows($req);
		
		if($res != 0 && mysql_result($req,0,pseudo) == $cible) print('<option value="engine=protecstop.php">Arrêter la protection</option>');
		else print('<option value="engine=protection.php?'.$cible.'">Protéger</option>');
		}
	
	if($santec == 0)
		{
		print('<option value="engine=transporter.php?c='.$cible.'">Transporter le corps</option>');
		}
	
	print('<option value="../wikast/edc=visio.php?auteur='.$cible.'">Visionner son EDC</option>');
	print('</select></p></div></form>');
	}
	
	
function affiche_etoile($stat,$valeur,$tout = false) {
	if($valeur == 0) return 0;
	if($valeur < 40) {
		if($tout) print('<img src="im_objets/etoile1.png" title="Novice'.((statut($_SESSION["statut"]) >= 7)?" ($valeur en $stat)":"").'" />');
		return 0;
	}
	if($valeur < 100) {
		print('<img src="im_objets/etoile2.png" title="Initi&eacute;'.((statut($_SESSION["statut"]) >= 7)?" ($valeur en $stat)":"").'" />');
		return 1;
	}
	if($valeur == 100) {
		print('<img src="im_objets/etoile3.png" title="Expert'.((statut($_SESSION["statut"]) >= 7)?" ($valeur en $stat)":"").'" />');
		return 1;
	}
	if($valeur > 100) {
		print('<img src="im_objets/etoile4.png" title="Sp&eacute;cialiste'.((statut($_SESSION["statut"]) >= 7)?" ($valeur en $stat)":"").'" />');
		return 1;
	}
}
	
	
?>

</p>
</div>
<?php if($chat=="oui") 	{ if ((date("H")>=21) || (date("H")<=7)) { include("inc_bas_n_c.php"); } else { include("inc_bas_j_c.php"); } } else {	if ((date("H")>=21) || (date("H")<=7)) { include("inc_bas_n.php"); } else { include("inc_bas_j.php"); }	} ?>
<?php include("inc_bas_de_page.php"); ?>
