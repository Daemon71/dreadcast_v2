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
$db = mysql_connect('localhost', 'CENSURE', 'CENSURE') or die("Erreur de connexion au serveur des messages.");
mysql_select_db('CENSURE',$db) or die("Erreur de connexion a la base de donnees des messages.");

$sql = 'SELECT * FROM principal_tbl WHERE pseudo= "'.$_SESSION['pseudo'].'"' ;
$req = mysql_query($sql);
$_SESSION['id'] = mysql_result($req,0,id);
$_SESSION['rue'] = mysql_result($req,0,rue);
$_SESSION['num'] = mysql_result($req,0,num);
$_SESSION['action'] = mysql_result($req,0,action);
$_SESSION['age'] = mysql_result($req,0,age);
$_SESSION['alim'] = mysql_result($req,0,alim);
$_SESSION['faim'] = mysql_result($req,0,faim);
$_SESSION['sante'] = mysql_result($req,0,sante);
$_SESSION['drogue'] = mysql_result($req,0,drogue);
$_SESSION['fatigue'] = mysql_result($req,0,fatigue);
$_SESSION['avatar'] = mysql_result($req,0,avatar);
$_SESSION['lieu'] = strtolower($_SESSION['rue']);
$_SESSION['lieu'] = ucwords($_SESSION['lieu']);
$_SESSION['combat'] = mysql_result($req,0,combat); 
$_SESSION['observation'] = mysql_result($req,0,observation); 
$_SESSION['gestion'] = mysql_result($req,0,gestion); 
$_SESSION['maintenance'] = mysql_result($req,0,maintenance); 
$_SESSION['mecanique'] = mysql_result($req,0,mecanique); 
$_SESSION['service'] = mysql_result($req,0,service); 
$_SESSION['discretion'] = mysql_result($req,0,discretion); 
$_SESSION['economie'] = mysql_result($req,0,economie); 
$_SESSION['resistance'] = mysql_result($req,0,resistance); 
$_SESSION['tir'] = mysql_result($req,0,tir); 
$_SESSION['vol'] = mysql_result($req,0,vol); 
$_SESSION['medecine'] = mysql_result($req,0,medecine); 
$_SESSION['informatique'] = mysql_result($req,0,informatique); 
$_SESSION['recherche'] = mysql_result($req,0,recherche);
$_SESSION['fidelite'] = mysql_result($req,0,fidelite); 
$_SESSION['arme'] = mysql_result($req,0,arme); 
$_SESSION['objet'] = mysql_result($req,0,objet); 
$_SESSION['vetements'] = mysql_result($req,0,vetements); 
$_SESSION['case1'] = mysql_result($req,0,case1); 
$_SESSION['case2'] = mysql_result($req,0,case2); 
$_SESSION['case3'] = mysql_result($req,0,case3); 
$_SESSION['case4'] = mysql_result($req,0,case4); 
$_SESSION['case5'] = mysql_result($req,0,case5); 
$_SESSION['case6'] = mysql_result($req,0,case6);
$creation = mysql_result($req,0,creation);
$_SESSION['malade'] = mysql_result($req,0,maladie);
$event = mysql_result($req, 0, event);

$sql = 'SELECT Police FROM principal_tbl WHERE id= "'.$_SESSION['id'].'"' ;
$req = mysql_query($sql);
$_SESSION['Police'] = mysql_result($req,0,Police);

$sql = 'SELECT entreprise FROM principal_tbl WHERE id= "'.$_SESSION['id'].'"' ;
$req = mysql_query($sql);
$_SESSION['entreprise'] = mysql_result($req,0,entreprise);

$sql = 'SELECT id FROM principal_tbl ORDER BY total DESC' ;
$req = mysql_query($sql);
$res = mysql_num_rows($req);

$t = 1;
for($i=0;$t!=$res+1;$i++)
	{
	$ids = mysql_result($req,$i,id);
	$sql1 = 'SELECT pseudo FROM principal_tbl WHERE id= "'.$ids.'"' ;
	$req1 = mysql_query($sql1);
	if(mysql_result($req1,0,pseudo)==$_SESSION['pseudo'])
		{
		$p = $t;
		}
	$t = $t + 1;
	}

$sql = 'SELECT id FROM principal_tbl' ;
$req = mysql_query($sql);
$nbreviv = mysql_num_rows($req);

$_SESSION['autre'] = "";
	
if($_SESSION['sexe']=="Homme")
	{
	$_SESSION['sexe'] = "Masculin";
	}
elseif($_SESSION['sexe']=="Femme")
	{
	$_SESSION['sexe'] = "Feminin";	
	}

if($_SESSION['sexe']=="Masculin")
	{
	$imagesexe = "masculin";
	}
elseif($_SESSION['sexe']=="Feminin")
	{
	$imagesexe = "feminin";
	}

if(ereg("-",$_SESSION['arme']))
	{
	$sqla = 'SELECT modif1,modif2,modif3 FROM armes_tbl WHERE idarme= "'.strstr($_SESSION['arme'],"-").'"';
	$reqa = mysql_query($sqla);
	$resa = mysql_num_rows($reqa);
	if($resa>0)
		{
		$modif1 = mysql_result($reqa,0,modif1);
		$modif2 = mysql_result($reqa,0,modif2);
		$modif3 = mysql_result($reqa,0,modif3);
		}
	}

if($modif1==1 || $modif2==1 || $modif3==1)
	{
	$bonus_tir = $bonus_tir + 15;
	}
if($modif1==2 || $modif2==2 || $modif3==2)
	{
	$bonus_tir = $bonus_tir + 5;
	}
if($modif1==7 || $modif2==7 || $modif3==7)
	{
	$bonus_observation = $bonus_observation + 5;
	}
if($modif1==5 || $modif2==5 || $modif3==5)
	{
	$bonus_discretion = $bonus_discretion + 10;
	}

$sqla = 'SELECT nature,bonus FROM recherche_effets_tbl WHERE ido=(SELECT id FROM objets_tbl WHERE nom= "'.$_SESSION['objet'].'") OR ido=(SELECT id FROM objets_tbl WHERE nom= "'.$_SESSION['vetements'].'") OR ido=(SELECT id FROM objets_tbl WHERE nom= "'.preg_replace("#(.+)\-(.+)#","$1",$_SESSION['arme']).'")';
$reqa = mysql_query($sqla);
$resa = mysql_num_rows($reqa);
for($i=0;$i!=$resa;$i++)
	{
	if(mysql_result($reqa,$i,nature)=="combat") { $bonus_combat += mysql_result($reqa,$i,bonus); }
	if(mysql_result($reqa,$i,nature)=="tir") { $bonus_tir += mysql_result($reqa,$i,bonus); }
	if(mysql_result($reqa,$i,nature)=="resistance") { $bonus_resistance += mysql_result($reqa,$i,bonus); }
	if(mysql_result($reqa,$i,nature)=="vol") { $bonus_vol += mysql_result($reqa,$i,bonus); }
	if(mysql_result($reqa,$i,nature)=="maintenance") { $bonus_maintenance += mysql_result($reqa,$i,bonus); }
	if(mysql_result($reqa,$i,nature)=="mecanique") { $bonus_mecanique += mysql_result($reqa,$i,bonus); }
	if(mysql_result($reqa,$i,nature)=="observation") { $bonus_observation += mysql_result($reqa,$i,bonus); }
	if(mysql_result($reqa,$i,nature)=="discretion") { $bonus_discretion += mysql_result($reqa,$i,bonus); }
	if(mysql_result($reqa,$i,nature)=="gestion") { $bonus_gestion += mysql_result($reqa,$i,bonus); }
	if(mysql_result($reqa,$i,nature)=="economie") { $bonus_economie += mysql_result($reqa,$i,bonus); }
	if(mysql_result($reqa,$i,nature)=="service") { $bonus_service += mysql_result($reqa,$i,bonus); }
	if(mysql_result($reqa,$i,nature)=="medecine") { $bonus_medecine += mysql_result($reqa,$i,bonus); }
	if(mysql_result($reqa,$i,nature)=="informatique") { $bonus_informatique += mysql_result($reqa,$i,bonus); }
	if(mysql_result($reqa,$i,nature)=="recherche") { $bonus_recherche += mysql_result($reqa,$i,bonus); }
	}
mysql_close($db);
?>
<?php if ((date("H")>=21) || (date("H")<=7)) { include("inc_haut_n.php"); } else { include("inc_haut_j.php");} ?>

<div id="haut">
	<?php include("inc_entete.php"); ?>
	<div class="haut_aligngauche">
		<p>
		<div class="titrepage">
			Caract&eacute;ristiques
		</div>
		</p>
	</div>
</div>
<div id="centre_stats">
	
	<?php
	
	$event = event() && estDroide() ? $event : 0;
	$_SESSION['combat'] = $event == 1 || adm() ? 300 : $_SESSION['combat'];
	
	print ('
	<div id="cpseudo">
		'.$_SESSION['pseudo']);
		
	if($_SESSION['medecine']>0)
		{ 
		$ratio_sante = $_SESSION['sante']*100/$_SESSION['santemax'];
		if(($ratio_sante<150) && ($_SESSION['drogue']>0))
			{
			print (' <span style="position:relative;bottom:0;">-</span> <span style="position:relative;bottom:0;"><a href="engine=soins.php?'.$_SESSION['pseudo'].'" style="color:#e7e6e5;">Se soigner</a></span>'); 
			}
		elseif($ratio_sante<100)
			{
			print (' <span style="position:relative;bottom:0;">-</span> <span style="position:relative;bottom:0;"><a href="engine=soins.php?'.$_SESSION['pseudo'].'" style="color:#e7e6e5;">Se soigner</a></span>'); 
			}
		} 
	
	print('<br />
		<span>ID n&deg; '.$_SESSION['id'].'</span>'); if(($_SESSION['statut']!="Administrateur") && ($_SESSION['entreprise']!="Police") && ($_SESSION['entreprise']!="DI2RCO")) { if($_SESSION['Police']>=110) { print(' <span>-</span> <span class="color3">Recherch&eacute;</span>'); } elseif($_SESSION['Police']>=55) { print(' <span>-</span> <span class="color2">Recherch&eacute;</span>'); } }
	print('</div>');
	?>
	
	<div id="cpage2"><?php if($_SESSION['statut']!="Debutant") print('<a href="engine=infosperso.php"> <strong>Retourner la carte &raquo;</strong></a>'); ?></div>
	
	<div id="cavatar">
	
	<?php 
if($_SESSION['avatar']=="interogation.jpg") 
	{ 
	print('<a href="engine=avatars.php">'); 
	} 

if((ereg("http",$_SESSION['avatar'])) OR (ereg("ftp",$_SESSION['avatar'])))
	{
	if($_SESSION['malade']==1) print('<a href="engine=faucheur.php"><img src="avatars/faucheur.jpg" border="1px" width="70px" /></a>');
	else print('<img src="'.$_SESSION['avatar'].'" border="1px" width="70px" />');
	}
else
	{
	if($_SESSION['malade']==1) print('<a href="engine=faucheur.php"><img src="avatars/faucheur.php?'.$_SESSION['avatar'].'" border="1px" width="70px" /></a>');
	else print('<img src="avatars/'.$_SESSION['avatar'].'" border="0" />');
	}
 
if ($_SESSION['avatar']=="interogation.jpg") 
	{ 
	print('</a>'); 
	} 
?>
	</div>
	
	<div id="cinfos">
		<strong>Race :</strong> <?php print ($_SESSION['race']); ?><br />
		<strong>Sexe :</strong> <?php print($_SESSION['sexe']); ?><br />
		<strong>Age :</strong> <?php print ($_SESSION['age'].' ans'); ?><br />
		<strong>Taille :</strong> <?php print ('1m'.$_SESSION['taille']); ?><br />
		<strong><a href="http://v2.dreadcast.net/classement.php<?php $page = ceil ( $p / 50 ); print('?page='.$page.'&classe='.$p.''); ?>" target="_blank">Class&eacute;</a> :</strong> <?php print ($p.'e'); ?><br />
		<strong>Arrivée :</strong> <?php print ($creation); if($creation != 1) print(' ans'); else print(' an'); ?>
	</div>
	
	<div id="ctitre">Comp&eacute;tences professionnelles</div>
	
	<div id="competences">
		<?php
		
		if(dcstat($_SESSION['pseudo'],1)<1) { $color1 = "color3"; $color2 = "color3"; }
		else { $color1 = "color5"; $color2 = "color4"; }
		
		print('<div id="colonne1">');
		
		if($_SESSION['combat']!=0 || $bonus_combat>0)
			{
			print('<div id="comp1">');
			
			print(etoile('combat'));
			
			print('<strong>Combat :</strong> <span class="'.$color1.'">'.floor(dcstat($_SESSION['pseudo'],$_SESSION['combat'])).'</span><span class="'.$color2.'">'.substr(strstr(dcstat($_SESSION['pseudo'],$_SESSION['combat']),"."),0,3).'</span>');
			if($bonus_combat>0) print ('<strong><span class="color5"> +'.$bonus_combat.' </span></strong>');
			
			print('</div>'); 
			}
		
		if($_SESSION['tir']!=0 || $bonus_tir>0)
			{
			print('<div id="comp2">');
			
			print(etoile('tir'));
			
			print('<strong>Tir :</strong> <span class="'.$color1.'">'.floor(dcstat($_SESSION['pseudo'],$_SESSION['tir'])).'</span><span class="'.$color2.'">'.substr(strstr(dcstat($_SESSION['pseudo'],$_SESSION['tir']),"."),0,3).'</span>');
			if($bonus_tir>0) print ('<strong><span class="color5"> +'.$bonus_tir.' </span></strong>');
			
			print('</div>'); 
			}
			
		if($_SESSION['resistance']!=0 || $bonus_resistance>0)
			{
			print('<div id="comp3">');
			
			print(etoile('resistance'));
			
			print('<strong>R&eacute;sistance :</strong> <span class="'.$color1.'">'.floor(dcstat($_SESSION['pseudo'],$_SESSION['resistance'])).'</span><span class="'.$color2.'">'.substr(strstr(dcstat($_SESSION['pseudo'],$_SESSION['resistance']),"."),0,3).'</span>');
			if($bonus_resistance>0) print ('<strong><span class="color5"> +'.$bonus_resistance.' </span></strong>');
			
			print('</div>'); 
			}
			
		if($_SESSION['observation']!=0 || $bonus_observation>0)
			{
			print('<div id="comp4">');
			
			print(etoile('observation'));
			
			print('<strong>Observation :</strong> <span class="'.$color1.'">'.floor(dcstat($_SESSION['pseudo'],$_SESSION['observation'])).'</span><span class="'.$color2.'">'.substr(strstr(dcstat($_SESSION['pseudo'],$_SESSION['observation']),"."),0,3).'</span>');
			if($bonus_observation>0) print ('<strong><span class="color5"> +'.$bonus_observation.' </span></strong>');
			
			print('</div>'); 
			}
			
		if($_SESSION['discretion']!=0 || $bonus_discretion>0)
			{
			print('<div id="comp5">');
			
			print(etoile('discretion'));
			
			print('<strong>Discr&eacute;tion :</strong> <span class="'.$color1.'">'.floor(dcstat($_SESSION['pseudo'],$_SESSION['discretion'])).'</span><span class="'.$color2.'">'.substr(strstr(dcstat($_SESSION['pseudo'],$_SESSION['discretion']),"."),0,3).'</span>');
			if($bonus_discretion>0) print ('<strong><span class="color5"> +'.$bonus_discretion.' </span></strong>');
			
			print('</div>'); 
			}
			
		if($_SESSION['vol']!=0 || $bonus_vol>0)
			{
			print('<div id="comp6">');
			
			print(etoile('vol'));
			
			print('<strong>Vol &agrave; la tire :</strong> <span class="'.$color1.'">'.floor(dcstat($_SESSION['pseudo'],$_SESSION['vol'])).'</span><span class="'.$color2.'">'.substr(strstr(dcstat($_SESSION['pseudo'],$_SESSION['vol']),"."),0,3).'</span>');
			if($bonus_vol>0) print ('<strong><span class="color5"> +'.$bonus_vol.' </span></strong>');
			
			print('</div>'); 
			}
			
		if($_SESSION['medecine']!=0 || $bonus_medecine>0)
			{
			print('<div id="comp7">');
			
			print(etoile('medecine'));
			
			print('<strong>M&eacute;decine :</strong> <span class="'.$color1.'">'.floor(dcstat($_SESSION['pseudo'],$_SESSION['medecine'])).'</span><span class="'.$color2.'">'.substr(strstr(dcstat($_SESSION['pseudo'],$_SESSION['medecine']),"."),0,3).'</span>');
			if($bonus_medecine>0) print ('<strong><span class="color5"> +'.$bonus_medecine.' </span></strong>');
			
			print('</div>'); 
			}
			
		print('
		</div>
	
		<div id="colonne2">');
		
		if($_SESSION['maintenance']!=0 || $bonus_maintenance>0)
			{
			print('<div id="comp8">');
			
			print(etoile('maintenance'));
			
			print('<strong>Maintenance :</strong> <span class="'.$color1.'">'.floor(dcstat($_SESSION['pseudo'],$_SESSION['maintenance'])).'</span><span class="'.$color2.'">'.substr(strstr(dcstat($_SESSION['pseudo'],$_SESSION['maintenance']),"."),0,3).'</span>');
			if($bonus_maintenance>0) print ('<strong><span class="color5"> +'.$bonus_maintenance.' </span></strong>');
			
			print('</div>'); 
			}
			
		if($_SESSION['mecanique']!=0 || $bonus_mecanique>0)
			{
			print('<div id="comp9">');
			
			print(etoile('mecanique'));
			
			print('<strong>M&eacute;canique :</strong> <span class="'.$color1.'">'.floor(dcstat($_SESSION['pseudo'],$_SESSION['mecanique'])).'</span><span class="'.$color2.'">'.substr(strstr(dcstat($_SESSION['pseudo'],$_SESSION['mecanique']),"."),0,3).'</span>');
			if($bonus_mecanique>0) print ('<strong><span class="color5"> +'.$bonus_mecanique.' </span></strong>');
			
			print('</div>'); 
			}
			
		if($_SESSION['gestion']!=0 || $bonus_gestion>0)
			{
			print('<div id="comp10">');
			
			print(etoile('gestion'));
			
			print('<strong>Gestion :</strong> <span class="'.$color1.'">'.floor(dcstat($_SESSION['pseudo'],$_SESSION['gestion'])).'</span><span class="'.$color2.'">'.substr(strstr(dcstat($_SESSION['pseudo'],$_SESSION['gestion']),"."),0,3).'</span>');
			if($bonus_gestion>0) print ('<strong><span class="color5"> +'.$bonus_gestion.' </span></strong>');
			
			print('</div>'); 
			}
			
		if($_SESSION['economie']!=0 || $bonus_economie>0)
			{
			print('<div id="comp11">');
			
			print(etoile('economie'));
			
			print('<strong>Economie :</strong> <span class="'.$color1.'">'.floor(dcstat($_SESSION['pseudo'],$_SESSION['economie'])).'</span><span class="'.$color2.'">'.substr(strstr(dcstat($_SESSION['pseudo'],$_SESSION['economie']),"."),0,3).'</span>');
			if($bonus_economie>0) print ('<strong><span class="color5"> +'.$bonus_economie.' </span></strong>');
			
			print('</div>'); 
			}
			
		if($_SESSION['service']!=0 || $bonus_service>0)
			{
			print('<div id="comp12">');
			
			print(etoile('service'));
			
			print('<strong>Service :</strong> <span class="'.$color1.'">'.floor(dcstat($_SESSION['pseudo'],$_SESSION['service'])).'</span><span class="'.$color2.'">'.substr(strstr(dcstat($_SESSION['pseudo'],$_SESSION['service']),"."),0,3).'</span>');
			if($bonus_service>0) print ('<strong><span class="color5"> +'.$bonus_service.' </span></strong>');
			
			print('</div>'); 
			}
			
		if($_SESSION['informatique']!=0 || $bonus_informatique>0)
			{
			print('<div id="comp13">');
			
			print(etoile('informatique'));
			
			print('<strong>Informatique :</strong> <span class="'.$color1.'">'.floor(dcstat($_SESSION['pseudo'],$_SESSION['informatique'])).'</span><span class="'.$color2.'">'.substr(strstr(dcstat($_SESSION['pseudo'],$_SESSION['informatique']),"."),0,3).'</span>');
			if($bonus_informatique>0) print ('<strong><span class="color5"> +'.$bonus_informatique.' </span></strong>');
			
			print('</div>'); 
			}
			
		if($_SESSION['recherche']!=0 || $bonus_recherche>0)
			{
			print('<div id="comp14">');
			
			print(etoile('recherche'));
			
			print('<strong>Recherche :</strong> <span class="'.$color1.'">'.floor(dcstat($_SESSION['pseudo'],$_SESSION['recherche'])).'</span><span class="'.$color2.'">'.substr(strstr(dcstat($_SESSION['pseudo'],$_SESSION['recherche']),"."),0,3).'</span>');
			if($bonus_recherche>0) print ('<strong><span class="color5"> +'.$bonus_recherche.' </span></strong>');
			
			print('</div>'); 
			}
			
		if($_SESSION['fidelite']!=0)
			{
			print('<div id="comp15">');
			
			print(etoile('fidelite'));
			
			print('<strong>Fidelit&eacute; :</strong> <span class="'.$color1.'">'.floor(dcstat($_SESSION['pseudo'],$_SESSION['fidelite'])).'</span><span class="'.$color2.'">'.substr(strstr(dcstat($_SESSION['pseudo'],$_SESSION['fidelite']),"."),0,3).'</span>');
			
			print('</div>'); 
			}
		
			print('</div>');
		
		?>
	</div>
	
</div>

<?php

	function etoile($stat) {
		if($_SESSION[$stat]>100) $retour = '<div class="etoile4" title="Spécialiste en '.$stat.'"></div>';
		elseif($_SESSION[$stat]==100) $retour = '<div class="etoile3" title="Expert en '.$stat.'"></div>';
		elseif($_SESSION[$stat]>=40) $retour = '<div class="etoile2"  title="Initi&eacute; en '.$stat.'"></div>';
		else $retour = '<div class="etoile1"  title="Novice en '.$stat.'"></div>';
		
		return $retour;
	}

?>

<?php if($chat=="oui") 	{ if ((date("H")>=21) || (date("H")<=7)) { include("inc_bas_n_c.php"); } else { include("inc_bas_j_c.php"); } } else {	if ((date("H")>=21) || (date("H")<=7)) { include("inc_bas_n.php"); } else { include("inc_bas_j.php"); }	} ?>
<?php include("inc_bas_de_page.php"); ?>
