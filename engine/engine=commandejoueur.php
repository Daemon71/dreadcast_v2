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
			Contr&ocirc;le d'un joueur
		</div>
		<b class="module4ie"><a href="engine=commande.php" class="module4"><img src="im_objets/icon_retour.gif" alt="Retour" />Retour</a></b>
</p>
	</div>
</div>
<?php include('inc_admin.php'); ?>
<div id="centre">
<p>

<?php 

if($_SESSION['statut']=="Administrateur")
	{
	$db = mysql_connect('localhost', 'CENSURE', 'CENSURE') or die("Erreur de connexion au serveur des messages.");
	mysql_select_db('CENSURE',$db) or die("Erreur de connexion a la base de donnees des messages.");
		
	if($_GET['suppr']==1)
		{
		print('<br />Êtes-vous sûr de vouloir supprimer le compte n°'.$_GET['id'].' ?<br /><br />
		<form method="post" action="engine=commandejoueur.php?suppr=2&id='.$_GET['id'].'">
			<input type="checkbox" name="bloque_ip" id="bloque_ip" value="1" /> <label for="bloque_ip">Bloquer également l\'IP</label><br />
			<input type="submit" name="valider" value="Valider" /> - <a href="engine=commande.php">Retour</a>
		</form>');
		}
	elseif($_GET['suppr']==2)
		{
		$id = $_GET['id'];
		$sql1 = 'SELECT * FROM principal_tbl WHERE id= "'.$id.'"' ;
		$req1 = mysql_query($sql1);
		$statut = mysql_result($req1,0,statut); 
		$sql1 = 'SELECT * FROM principal_tbl WHERE id= "'.$id.'"' ;
		$req1 = mysql_query($sql1);
		$pseudo = mysql_result($req1,0,pseudo); 
		
		if($statut!="Administrateur")
			{
			if($_POST['bloque_ip']!="") supprimer_personnage($pseudo,"Compte supprimé par l'administration",true);
			else supprimer_personnage($pseudo,"Compte supprimé par l'administration");
			print('<br />Compte supprimé.
			<meta http-equiv="refresh" content="1 ; url=http://v2.dreadcast.net/ingame/engine=commande.php">');
			}
		else
			{
			print('<br />Impossible de supprimer le compte d\'un administrateur.');
			}
		}
	elseif($_GET['modif']==1)
		{
		$sql1 = 'UPDATE principal_tbl SET login="'.$_POST['login'].'" , password="'.$_POST['password'].'" , ip="'.$_POST['ip'].'" , ipdc="'.$_POST['ipdc'].'" , statut="'.$_POST['statut'].'" , adresse="'.$_POST['adresse'].'" , sante="'.$_POST['sante'].'" , sante_max="'.$_POST['sante_max'].'" , fatigue="'.$_POST['fatigue'].'" , fatigue_max="'.$_POST['fatigue_max'].'" , faim="'.$_POST['faim'].'" , soif="'.$_POST['soif'].'" , sexe="'.$_POST['sexe'].'" , race="'.$_POST['race'].'" , age="'.$_POST['age'].'" , taille="'.$_POST['taille'].'" , action="'.$_POST['action'].'" , avatar="'.$_POST['avatar'].'" , credits="'.$_POST['credits'].'" , case1="'.$_POST['case1'].'" , case2="'.$_POST['case2'].'" , case3="'.$_POST['case3'].'" , case4="'.$_POST['case4'].'" , case5="'.$_POST['case5'].'" , case6="'.$_POST['case6'].'" , arme="'.$_POST['arme'].'" , vetements="'.$_POST['vetements'].'" , objet="'.$_POST['objet'].'" , chargeur="'.$_POST['chargeur'].'" , num="'.$_POST['num'].'" , rue="'.$_POST['rue'].'" , combat="'.$_POST['combat'].'" , observation="'.$_POST['observation'].'" , gestion="'.$_POST['gestion'].'" , maintenance="'.$_POST['maintenance'].'" , mecanique="'.$_POST['mecanique'].'" , service="'.$_POST['service'].'" , discretion="'.$_POST['discretion'].'" , economie="'.$_POST['economie'].'" , resistance="'.$_POST['resistance'].'" , recherche="'.$_POST['recherche'].'" , tir="'.$_POST['tir'].'" , vol="'.$_POST['vol'].'" , medecine="'.$_POST['medecine'].'" , informatique="'.$_POST['informatique'].'" , fidelite="'.$_POST['fidelite'].'" , combat_max ="'.$_POST['combat_max'].'" , observation_max ="'.$_POST['observation_max'].'" , gestion_max ="'.$_POST['gestion_max'].'" , maintenance_max ="'.$_POST['maintenance_max'].'" , mecanique_max ="'.$_POST['mecanique_max'].'" , service_max ="'.$_POST['service_max'].'" , discretion_max ="'.$_POST['discretion_max'].'" , economie_max ="'.$_POST['economie_max'].'" , resistance_max ="'.$_POST['resistance_max'].'" , recherche_max ="'.$_POST['recherche_max'].'" , tir_max ="'.$_POST['tir_max'].'" , vol_max ="'.$_POST['vol_max'].'" , medecine_max ="'.$_POST['medecine_max'].'" , informatique_max ="'.$_POST['informatique_max'].'" , fidelite_max ="'.$_POST['fidelite_max'].'" , total ="'.$_POST['total'].'" , type="'.$_POST['type'].'" , entreprise="'.$_POST['entreprise'].'" , salaire="'.$_POST['salaire'].'" , Police="'.$_POST['Police'].'" , DI2RCO="'.$_POST['DI2RCO'].'" , ruel="'.$_POST['ruel'].'" , numl="'.$_POST['numl'].'" , alcool="'.$_POST['alcool'].'", maladie = "'.$_POST['maladie'].'" WHERE id= "'.$_POST['id'].'"' ;
		$req1 = mysql_query($sql1);
		print('<br />Modifications effectuées.');
		}
	elseif($_GET['type']=="talent")
		{
		$pseudo = ($_POST['pseudo']=="")?$_GET['pseudo']:$_POST['pseudo'];
		print('<div class="ebarreliste">Talents de <strong>'.$pseudo.'</strong><br /><br />');
		$sql = 'SELECT * FROM titres_tbl WHERE pseudo= "'.$pseudo.'" ORDER BY type,titre' ;
		$req = mysql_query($sql);
		$res = mysql_num_rows($req);
		
		for($i=0;$i<$res;$i++) {
			print('['.mysql_result($req,$i,type).'] '.mysql_result($req,$i,titre).'<br />');
		}
		
		print('<br /><a href="engine=commandejoueur.php?pseudo='.$pseudo.'">Retour</a></div>');
		}
	elseif($_GET['type']=="enregistrement")
		{
		$pseudo = ($_POST['pseudo']=="")?$_GET['pseudo']:$_POST['pseudo'];
		print('<div class="ebarreliste">Enregistrements de <strong>'.$pseudo.'</strong><br /><br />');
		$sql = 'SELECT * FROM enregistreur_tbl WHERE pseudo= "'.$pseudo.'" ORDER BY donnee' ;
		$req = mysql_query($sql);
		$res = mysql_num_rows($req);
		
		for($i=0;$i<$res;$i++) {
			print(mysql_result($req,$i,donnee).' : '.mysql_result($req,$i,valeur).'<br />');
		}
		
		print('<br /><a href="engine=commandejoueur.php?pseudo='.$pseudo.'">Retour</a></div>');
		}
	else
		{
		if ($_POST['pseudo']) {
			$type = trim($_POST['type']);
			if ($type == 'adresse') {
				if (preg_match('/([^ ]) (.*)$/', $_POST['pseudo'], $catch))
					$where = 'num = '.$catch[1].' AND rue LIKE "'.$catch[2].'"';
				else
					exit();
			} else
				$where = $type.' LIKE "%'.$_POST['pseudo'].'%"';
			$sql = "SELECT * FROM principal_tbl WHERE $where ORDER BY pseudo" ;
			$req = mysql_query($sql);
			$res = mysql_num_rows($req);
		} elseif ($_GET['pseudo']) {
			$sql = 'SELECT * FROM principal_tbl WHERE pseudo= "'.$_GET['pseudo'].'"' ;
			$req = mysql_query($sql);
			$res = mysql_num_rows($req);
		}
		
		if ($res>1) {
			echo '<div class="ebarreliste">
				'.$res.' personne(s):<br />';
			for($i=0;$i<$res;$i++)
				echo '<a href="engine=commandejoueur.php?pseudo='.mysql_result($req,$i,pseudo).'">'.mysql_result($req,$i,pseudo).'</a><br />';
			echo '</div>';
		} elseif($res==1)
			{
			$all=0;
			$alltot=0;
			if($_GET['allopass']!="")
				{
				$all = ((mysql_result($req,0,allopass)+$_GET['allopass'])>5)?0:$_GET['allopass'];
				$alltot = $_GET['allopass'];
				$sql1 = 'UPDATE principal_tbl SET allopass="'.(mysql_result($req,0,allopass)+$all).'" , allopasstot="'.(mysql_result($req,0,allopasstot)+$_GET['allopass']).'" WHERE id= "'.mysql_result($req,0,id).'"' ;
				$req1 = mysql_query($sql1);
				}
			
			$pseudo = mysql_result($req,0,pseudo);
			
			print('<form action="engine=commandejoueur.php?modif=1" method="post"><div class="ebarreliste">
			'.mysql_result($req,0,pseudo).'<br />
			ID: <input type="hidden" name="id" size="10" value="'.mysql_result($req,0,id).'" />'.mysql_result($req,0,id).' (<a href="engine=commandejoueur.php?suppr=1&id='.mysql_result($req,0,id).'">Supprimer le compte</a>)<br />
			'.mysql_result($req,0,jours_de_jeu).' jour(s) de jeu<br />
			Dernière connexion le '.date('d/m/Y', mysql_result($req,0,dhc)).' à '.date('H:i', mysql_result($req,0,dhc)).'<br />
			'.((mysql_result($req,0,parrain)) ? 'Parrain: <a href="engine=commandejoueur.php?pseudo='.mysql_result($req,0,parrain).'" onclick="window.open(this.href); return false;">'.mysql_result($req,0,parrain).'</a><br />':'').'
			Allopass: '.(mysql_result($req,0,allopass)+$all).'-'.(mysql_result($req,0,allopasstot)+$alltot).' <a href="engine=commandejoueur.php?pseudo='.$pseudo.'&allopass=1">Augmenter</a><br />
			Login: <input type="text" name="login" size="10" value="'.mysql_result($req,0,login).'" /><br />
			Password: <input type="text" name="password" size="10" value="'.mysql_result($req,0,password).'" /><br />
			Statut: <input type="text" name="statut" size="10" value="'.mysql_result($req,0,statut).'" /><br />
			Adresse mail: <input type="text" name="adresse" size="10" value="'.mysql_result($req,0,adresse).'" /><br />
			Santé: <input type="text" name="sante" size="10" value="'.mysql_result($req,0,sante).'" /><br />
			Santé MAX: <input type="text" name="sante_max" size="10" value="'.mysql_result($req,0,sante_max).'" /><br />
			Fatigue: <input type="text" name="fatigue" size="10" value="'.mysql_result($req,0,fatigue).'" /><br />
			Fatigue MAX: <input type="text" name="fatigue_max" size="10" value="'.mysql_result($req,0,fatigue_max).'" /><br />
			Faim: <input type="text" name="faim" size="10" value="'.mysql_result($req,0,faim).'" /><br />
			Soif: <input type="text" name="soif" size="10" value="'.mysql_result($req,0,soif).'" /><br />
			Alcool: <input type="text" name="alcool" size="10" value="'.mysql_result($req,0,alcool).'" /><br />
			Sexe: <input type="text" name="sexe" size="10" value="'.mysql_result($req,0,sexe).'" /><br />
			Race: <input type="text" name="race" size="10" value="'.mysql_result($req,0,race).'" /><br />
			Age: <input type="text" name="age" size="10" value="'.mysql_result($req,0,age).'" /><br />
			Taille: <input type="text" name="taille" size="10" value="'.mysql_result($req,0,taille).'" /><br />
			Action: <input type="text" name="action" size="10" value="'.mysql_result($req,0,action).'" /><br />
			Avatar: <input type="text" name="avatar" size="10" value="'.mysql_result($req,0,avatar).'" /><br />
			Crédits: <input type="text" name="credits" size="10" value="'.mysql_result($req,0,credits).'" /><br />
			Case1: <input type="text" name="case1" size="10" value="'.mysql_result($req,0,case1).'" /><br />
			Case2: <input type="text" name="case2" size="10" value="'.mysql_result($req,0,case2).'" /><br />
			Case3: <input type="text" name="case3" size="10" value="'.mysql_result($req,0,case3).'" /><br />
			Case4: <input type="text" name="case4" size="10" value="'.mysql_result($req,0,case4).'" /><br />
			Case5: <input type="text" name="case5" size="10" value="'.mysql_result($req,0,case5).'" /><br />
			Case6: <input type="text" name="case6" size="10" value="'.mysql_result($req,0,case6).'" /><br />
			Arme: <input type="text" name="arme" size="10" value="'.mysql_result($req,0,arme).'" /><br />
			Vetements: <input type="text" name="vetements" size="10" value="'.mysql_result($req,0,vetements).'" /><br />
			Objet: <input type="text" name="objet" size="10" value="'.mysql_result($req,0,objet).'" /><br />
			Chargeur: <input type="text" name="chargeur" size="10" value="'.mysql_result($req,0,chargeur).'" /><br />
			Présent au: <input type="text" name="num" size="3" value="'.mysql_result($req,0,num).'" />
			<input type="text" name="rue" size="10" value="'.mysql_result($req,0,rue).'" /> - <a href="engine=go.php?num='.mysql_result($req,0,num).'&rue='.mysql_result($req,0,rue).'">S\'y rendre</a><br />
			Stat Combat: <input type="text" name="combat" size="10" value="'.mysql_result($req,0,combat).'" /> - Max : <input type="text" name="combat_max" style="width:40px;" size="10" value="'.mysql_result($req,0,combat_max).'" /><br />
			Stat Observation: <input type="text" name="observation" size="10" value="'.mysql_result($req,0,observation).'" /> - Max : <input type="text" name="observation_max" style="width:40px;" size="10" value="'.mysql_result($req,0,observation_max).'" /><br />
			Stat Gestion: <input type="text" name="gestion" size="10" value="'.mysql_result($req,0,gestion).'" /> - Max : <input type="text" name="gestion_max" style="width:40px;" size="10" value="'.mysql_result($req,0,gestion_max).'" /><br />
			Stat Maintenance: <input type="text" name="maintenance" size="10" value="'.mysql_result($req,0,maintenance).'" /> - Max : <input type="text" name="maintenance_max" style="width:40px;" size="10" value="'.mysql_result($req,0,maintenance_max).'" /><br />
			Stat Mécanique: <input type="text" name="mecanique" size="10" value="'.mysql_result($req,0,mecanique).'" /> - Max : <input type="text" name="mecanique_max" style="width:40px;" size="10" value="'.mysql_result($req,0,mecanique_max).'" /><br />
			Stat Service: <input type="text" name="service" size="10" value="'.mysql_result($req,0,service).'" /> - Max : <input type="text" name="service_max" style="width:40px;" size="10" value="'.mysql_result($req,0,service_max).'" /><br />
			Stat Discretion: <input type="text" name="discretion" size="10" value="'.mysql_result($req,0,discretion).'" /> - Max : <input type="text" name="discretion_max" style="width:40px;" size="10" value="'.mysql_result($req,0,discretion_max).'" /><br />
			Stat Economie: <input type="text" name="economie" size="10" value="'.mysql_result($req,0,economie).'" /> - Max : <input type="text" name="economie_max" style="width:40px;" size="10" value="'.mysql_result($req,0,economie_max).'" /><br />
			Stat Résistance: <input type="text" name="resistance" size="10" value="'.mysql_result($req,0,resistance).'" /> - Max : <input type="text" name="resistance_max" style="width:40px;" size="10" value="'.mysql_result($req,0,resistance_max).'" /><br />
			Stat Recherche: <input type="text" name="recherche" size="10" value="'.mysql_result($req,0,recherche).'" /> - Max : <input type="text" name="recherche_max" style="width:40px;" size="10" value="'.mysql_result($req,0,recherche_max).'" /><br />
			Stat Tir: <input type="text" name="tir" size="10" value="'.mysql_result($req,0,tir).'" /> - Max : <input type="text" name="tir_max" style="width:40px;" size="10" value="'.mysql_result($req,0,tir_max).'" /><br />
			Stat Vol: <input type="text" name="vol" size="10" value="'.mysql_result($req,0,vol).'" /> - Max : <input type="text" name="vol_max" style="width:40px;" size="10" value="'.mysql_result($req,0,vol_max).'" /><br />
			Stat Médecine: <input type="text" name="medecine" size="10" value="'.mysql_result($req,0,medecine).'" /> - Max : <input type="text" name="medecine_max" style="width:40px;" size="10" value="'.mysql_result($req,0,medecine_max).'" /><br />
			Stat Informatique: <input type="text" name="informatique" size="10" value="'.mysql_result($req,0,informatique).'" /> - Max : <input type="text" name="informatique_max" style="width:40px;" size="10" value="'.mysql_result($req,0,informatique_max).'" /><br />
			Stat Fidelité: <input type="text" name="fidelite" size="10" value="'.mysql_result($req,0,fidelite).'" /> - Max : <input type="text" name="fidelite_max" style="width:40px;" size="10" value="'.mysql_result($req,0,fidelite_max).'" /><br />
			Expérience: <input type="text" name="total" size="10" value="'.mysql_result($req,0,total).'" /><br />
			Poste: <input type="text" name="type" size="10" value="'.mysql_result($req,0,type).'" /><br />
			Entreprise: <input type="text" name="entreprise" size="10" value="'.mysql_result($req,0,entreprise).'" /><br />
			Salaire: <input type="text" name="salaire" size="10" value="'.mysql_result($req,0,salaire).'" /><br />
			Indice Police: <input type="text" name="Police" size="10" value="'.mysql_result($req,0,Police).'" /><br />
			Indice DI2RCO: <input type="text" name="DI2RCO" size="10" value="'.mysql_result($req,0,DI2RCO).'" /><br />
			Logement: <input type="text" name="numl" size="3" value="'.mysql_result($req,0,numl).'" /> 
			<input type="text" name="ruel" size="10" value="'.mysql_result($req,0,ruel).'" /> - <a href="engine=go.php?num='.mysql_result($req,0,numl).'&rue='.mysql_result($req,0,ruel).'">S\'y rendre</a><br />
			Maladie: <input type="text" name="maladie" size="3" value="'.mysql_result($req,0,maladie).'" /><br />
			IP: <input type="text" name="ip" size="10" value="'.mysql_result($req,0,ip).'" /><br />
			IPDC: <input type="text" name="ipdc" size="10" value="'.mysql_result($req,0,ipdc).'" /><br />
			
			<input type="submit" value="Appliquer les modifications" />
			<br /><br />
			<a href="engine=commandejoueur.php?pseudo='.$pseudo.'&type=talent">Ses talents</a><br />
			<a href="engine=commandejoueur.php?pseudo='.$pseudo.'&type=enregistrement">Ses enregistrements</a>
			</div></form>');
			}
		else
			{
			print('<br />Personne ne s\'appelle ainsi.
			<meta http-equiv="refresh" content="1 ; url=http://v2.dreadcast.net/ingame/engine=commande.php">');
			}
		}
	mysql_close($db);
	}
else
	{
	print('<meta http-equiv="refresh" content="0 ; url=http://v2.dreadcast.net/ingame/engine.php"> ');
	exit();
	}

?>

</p>
</div>
<?php if($chat=="oui") 	{ if ((date("H")>=21) || (date("H")<=7)) { include("inc_bas_n_c.php"); } else { include("inc_bas_j_c.php"); } } else {	if ((date("H")>=21) || (date("H")<=7)) { include("inc_bas_n.php"); } else { include("inc_bas_j.php"); }	} ?>
<?php include("inc_bas_de_page.php"); ?>
