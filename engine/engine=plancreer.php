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

$sql = 'SELECT type,entreprise,points FROM principal_tbl WHERE id= "'.$_SESSION['id'].'"' ;
$req = mysql_query($sql);
$_SESSION['poste'] = mysql_result($req,0,type); 
$_SESSION['entreprise'] = mysql_result($req,0,entreprise); 
$_SESSION['points'] = mysql_result($req,0,points); 

$sql = 'SELECT bdd FROM `e_'.str_replace(" ","_",''.$_SESSION['entreprise'].'').'_tbl` WHERE poste= "'.$_SESSION['poste'].'"' ;
$req = mysql_query($sql);
$_SESSION['bdd'] = mysql_result($req,0,bdd); 

$sql = 'SELECT id,type,budget FROM entreprises_tbl WHERE nom= "'.$_SESSION['entreprise'].'"' ;
$req = mysql_query($sql);
$ide = mysql_result($req,0,id); 
$type = mysql_result($req,0,type); 
$budget = mysql_result($req,0,budget); 

if($_SESSION['bdd']=="") { print('<meta http-equiv="refresh" content="0 ; url=http://v2.dreadcast.net/ingame/engine.php"> '); exit(); }
if($type!="centre de recherche") { print('<meta http-equiv="refresh" content="0 ; url=http://v2.dreadcast.net/ingame/engine.php"> '); exit(); }

mysql_close($db);
?>
<?php if ((date("H")>=21) || (date("H")<=7)) { include("inc_haut_n.php"); } else { include("inc_haut_j.php");} ?>

<div id="haut">
	<?php include("inc_entete.php"); ?>
	<div class="haut_aligngauche_haut">
		<p>
		<div class="titrepage">
			Créer un plan<br />
			<strong>Capital :</strong><em> <?php print(''.$budget.''); ?> Crédits</em>
		</div>
		<b class="module4ie"><a href="engine=services.php" class="module4"><img src="im_objets/icon_retour.gif" alt="Retour" />Retour</a></b>
		</p>
	</div>
</div>
<div id="centre">
<p>

<?php 

if($_POST['verif']==3)
	{
	$db = mysql_connect('localhost', 'CENSURE', 'CENSURE') or die("Erreur de connexion au serveur des messages.");
	mysql_select_db('CENSURE',$db) or die("Erreur de connexion a la base de donnees des messages.");

	if($budget>=200)
		{
		$budget = $budget - 200;
		$sql = 'UPDATE entreprises_tbl SET budget= "'.$budget.'" WHERE nom= "'.$_SESSION['entreprise'].'"' ;
		$req = mysql_query($sql);
		if($_SESSION['typeco']=="obju")
			{
			$sql = 'INSERT INTO objets_tbl(id,nom,image,url,type,prod,puissance,ecart,distance,modes,infos,prix) VALUES("","'.trim($_SESSION['nomco']).'","'.$_SESSION['imageco'].'","engine=equipo.php?'.trim($_SESSION['nomco']).'","'.$_SESSION['typeco'].'","0","0","0","0","","'.str_replace("<br />","\n",htmlentities($_SESSION['descriptionco'])).'","'.$_SESSION['prixco'].'")';
			$req = mysql_query($sql);
			}
		elseif($_SESSION['typeco']=="ouu")
			{
			$sql = 'INSERT INTO objets_tbl(id,nom,image,url,type,prod,puissance,ecart,distance,modes,infos,prix) VALUES("","'.trim($_SESSION['nomco']).'","'.$_SESSION['imageco'].'","engine=objetuu.php?'.trim($_SESSION['nomco']).'","'.$_SESSION['typeco'].'","0","0","0","0","","'.str_replace("<br />","\n",htmlentities($_SESSION['descriptionco'])).'","'.$_SESSION['prixco'].'")';
			$req = mysql_query($sql);
			}
		else
			{
			$ecart = $_SESSION['dmax'] - $_SESSION['dmin'];
			$sql = 'INSERT INTO objets_tbl(id,nom,image,url,type,prod,puissance,ecart,distance,modes,infos,prix) VALUES("","'.trim($_SESSION['nomco']).'","'.$_SESSION['imageco'].'","engine=equip.php?'.trim($_SESSION['nomco']).'","'.$_SESSION['typeco'].'","0","'.$_SESSION['dmin'].'","'.$ecart.'","'.$_SESSION['portee'].'","'.$_SESSION['modesco'].'","'.str_replace("<br />","\n",htmlentities($_SESSION['descriptionco'])).'","'.$_SESSION['prixco'].'")';
			$req = mysql_query($sql);
			}
		$sql = 'SELECT id FROM objets_tbl ORDER BY id DESC LIMIT 1';
		$req = mysql_query($sql);
		$ido = mysql_result($req,0,id);
		$sql = 'INSERT INTO recherche_plans_tbl(id,ido,ide) VALUES("","'.$ido.'","'.$ide.'")';
		$req = mysql_query($sql);
		for($i=1;$i!=$_SESSION['nbrebonusco']+1;$i++)
			{
			if($_SESSION['bonus'.$i.'']!="")
				{
				if($_SESSION['bonus'.$i.'']!="invisibilite" AND $_SESSION['bonus'.$i.'']!="focus" AND $_SESSION['bonus'.$i.'']!="detect" AND $_SESSION['bonus'.$i.'']!="aura")
					{
					$sql = 'INSERT INTO recherche_effets_tbl(id,ido,nature,bonus) VALUES("","'.$ido.'","'.$_SESSION['bonus'.$i.''].'","'.$_SESSION['vbonus'.$i.''].'")';
					$req = mysql_query($sql);
					}
				else
					{
					$sql = 'INSERT INTO recherche_effets_tbl(id,ido,nature,bonus) VALUES("","'.$ido.'","'.$_SESSION['bonus'.$i.''].'","0")';
					mysql_query($sql);
					}
				}
			}
		print('<meta http-equiv="refresh" content="0 ; url=http://v2.dreadcast.net/ingame/engine=planliste.php"> ');
		exit();
		}
	else
		{
		print('Vous n\'avez pas assez de Crédits dans le capital.');
		}
	mysql_close($db);
	}
elseif($_POST['verif']==2)
	{
	$_SESSION['prixco'] = 1000;
	print('<form action="#" method="POST"><div class="messagesvip">Voici le récapitulatif de votre plan:<br /><br /><img src="im_objets/'.$_SESSION['imageco'].'" border="0" /><br /><br />'.$_SESSION['descriptionco'].'<br /><br />
			<table border="0" width="80%">
			<tr><td><div align="left">Nom de l\'objet:</div></td><td><div align="left">'.$_SESSION['nomco'].'</div></td></tr>
			<tr><td><div align="left">Type d\'objet:</div></td><td><div align="left">');
	if($_SESSION['typeco']=="armtu") { print('Arme de tir'); }
	elseif($_SESSION['typeco']=="armcu") { print('Arme de combat'); }
	elseif($_SESSION['typeco']=="vetu") { print('Vetements'); }
	elseif($_SESSION['typeco']=="obju") { print('Objet'); }
	print('</div></td></tr>
			<tr><td><div align="left">Nombre de bonus:</div></td><td><div align="left">'.$_SESSION['nbrebonusco'].'</div></td></tr>');
	$_SESSION['prixco'] = $_SESSION['prixco'] + 1000*$_SESSION['nbrebonusco'];
	if($_SESSION['typeco']=="armtu") 
		{ 
		if(ereg("s",$_SESSION['modesco'])) { $modes = "Semi-auto"; $_SESSION['prixco'] = $_SESSION['prixco'] + 50; }
		if(ereg("b",$_SESSION['modesco'])) { $modes .= " Rafales"; $_SESSION['prixco'] = $_SESSION['prixco'] + 500; }
		if(ereg("a",$_SESSION['modesco'])) { $modes .= " Automatique"; $_SESSION['prixco'] = $_SESSION['prixco'] + 2500; }
		$_SESSION['prixco'] = $_SESSION['prixco'] + 100*$_SESSION['portee'] + 150*$_SESSION['dmin'] + 400*$_SESSION['dmax']; 
		print('<tr><td><div align="left">Dégats:</div></td><td><div align="left">'.$_SESSION['dmin'].' - '.$_SESSION['dmax'].'</div></td></tr>
			   <tr><td><div align="left">Portée:</div></td><td><div align="left">'.$_SESSION['portee'].'</div></td></tr>
			   <tr><td><div align="left">Chargeur:</div></td><td><div align="left">9 balles</div></td></tr>
			   <tr><td><div align="left">Modes:</div></td><td><div align="left">'.$modes.'</div></td></tr>'); 
		}
	elseif($_SESSION['typeco']=="armcu") { $_SESSION['prixco'] = $_SESSION['prixco'] + 100 + 150*$_SESSION['dmin'] + 400*$_SESSION['dmax'];print('<tr><td><div align="left">Dégats:</div></td><td><div align="left">'.$_SESSION['dmin'].' - '.$_SESSION['dmax'].'</div></td></tr>'); }
	elseif($_SESSION['typeco']=="vetu") { print('<tr><td><div align="left">Effets:</div></td><td><div align="left">Aucun</div></td></tr>'); }
	elseif($_SESSION['typeco']=="obju") { print('<tr><td><div align="left">Effets:</div></td><td><div align="left">Aucun</div></td></tr>'); }
	for($i=1;$i!=$_SESSION['nbrebonusco']+1;$i++)
		{
		if($_SESSION['bonus'.$i.'']=="observation" || $_SESSION['bonus'.$i.'']=="discretion" || $_SESSION['bonus'.$i.'']=="tir" || $_SESSION['bonus'.$i.'']=="combat" || $_SESSION['bonus'.$i.'']=="vol" || $_SESSION['bonus'.$i.'']=="economie" || $_SESSION['bonus'.$i.'']=="gestion" || $_SESSION['bonus'.$i.'']=="maintenance" || $_SESSION['bonus'.$i.'']=="medecine" || $_SESSION['bonus'.$i.'']=="resistance" || $_SESSION['bonus'.$i.'']=="informatique" || $_SESSION['bonus'.$i.'']=="recherche")
			{
			$_SESSION['vbonus'.$i.''] = $_POST['vbonus'.$i.''];
			if($_SESSION['vbonus'.$i.'']<=10) { $_SESSION['prixco'] = $_SESSION['prixco'] + 20*$_SESSION['vbonus'.$i.'']; }
			elseif($_SESSION['vbonus'.$i.'']<=30) { $_SESSION['prixco'] = $_SESSION['prixco'] + 50*$_SESSION['vbonus'.$i.'']; }
			elseif($_SESSION['vbonus'.$i.'']<=50) { $_SESSION['prixco'] = $_SESSION['prixco'] + 100*$_SESSION['vbonus'.$i.'']; }
			elseif($_SESSION['vbonus'.$i.'']<=70) { $_SESSION['prixco'] = $_SESSION['prixco'] + 250*$_SESSION['vbonus'.$i.'']; }
			elseif($_SESSION['vbonus'.$i.'']<=90) { $_SESSION['prixco'] = $_SESSION['prixco'] + 1000*$_SESSION['vbonus'.$i.'']; }
			else { $_SESSION['prixco'] = $_SESSION['prixco'] + 2000*$_SESSION['vbonus'.$i.'']; }
			print('<tr><td><div align="left">Bonus n°'.$i.':</div></td><td><div align="left">'.ucwords($_SESSION['bonus'.$i.'']).' +'.$_SESSION['vbonus'.$i.''].'</div></td></tr>');
			}
		elseif($_SESSION['bonus'.$i.'']=="sante" || $_SESSION['bonus'.$i.'']=="forme")
			{
			$_SESSION['vbonus'.$i.''] = $_POST['vbonus'.$i.''];
			if($_SESSION['vbonus'.$i.'']<=100) { $_SESSION['prixco'] = $_SESSION['prixco'] + $_SESSION['vbonus'.$i.'']; }
			elseif($_SESSION['vbonus'.$i.'']<=300) { $_SESSION['prixco'] = $_SESSION['prixco'] + 2*$_SESSION['vbonus'.$i.'']; }
			elseif($_SESSION['vbonus'.$i.'']<=500) { $_SESSION['prixco'] = $_SESSION['prixco'] + 4*$_SESSION['vbonus'.$i.'']; }
			elseif($_SESSION['vbonus'.$i.'']<=700) { $_SESSION['prixco'] = $_SESSION['prixco'] + 8*$_SESSION['vbonus'.$i.'']; }
			elseif($_SESSION['vbonus'.$i.'']<=900) { $_SESSION['prixco'] = $_SESSION['prixco'] + 16*$_SESSION['vbonus'.$i.'']; }
			else { $_SESSION['prixco'] = $_SESSION['prixco'] + 20*$_SESSION['vbonus'.$i.'']; }
			print('<tr><td><div align="left">Bonus n°'.$i.':</div></td><td><div align="left">'.ucwords($_SESSION['bonus'.$i.'']).' +'.$_SESSION['vbonus'.$i.''].'</div></td></tr>');
			}
		elseif($_SESSION['bonus'.$i.'']!="")
			{
			if($_SESSION['bonus'.$i.'']=="invisibilite") { $_SESSION['prixco'] = $_SESSION['prixco'] + 5000; }
			elseif($_SESSION['bonus'.$i.'']=="detect") { $_SESSION['prixco'] = $_SESSION['prixco'] + 5000; }
			elseif($_SESSION['bonus'.$i.'']=="aura") { $_SESSION['prixco'] = $_SESSION['prixco'] + 10000; }
			else { $_SESSION['prixco'] = $_SESSION['prixco'] + 1000; }
			print('<tr><td><div align="left">Bonus n°'.$i.':</div></td><td><div align="left">'.ucwords($_SESSION['bonus'.$i.'']).'</div></td></tr>');
			}
		}
	print('<tr><td><div align="left">Prix de production:</div></td><td><div align="left">'.$_SESSION['prixco'].' Crédits</div></td></tr>');
	print('</table><input type="hidden" name="verif" value="3"><br /><br />
			Ce plan coûte 200 Crédits.<br /><br />
			<input value="Acheter le plan" type="submit" />
			</div></form>');
	}
elseif($_POST['verif']==1)
	{
	print('<form action="#" method="POST"><div class="messagesvip">Entrez les valeurs des bonus que vous avez choisi (le cas échéant).<br /><br /><img src="im_objets/'.$_SESSION['imageco'].'" border="0" /><br /><br />
			<table border="0" width="80%">');
	$totbonus = "";
	for($i=1;$i!=$_SESSION['nbrebonusco']+1;$i++)
		{
		$_SESSION['bonus'.$i.''] = $_POST['bonus'.$i.''];
		if(ereg($_SESSION['bonus'.$i.''],$totbonus))
			{
			}
		else
			{
			$totbonus = $totbonus . $_SESSION['bonus'.$i.''];
			if($_SESSION['bonus'.$i.'']=="observation" || $_SESSION['bonus'.$i.'']=="discretion" || $_SESSION['bonus'.$i.'']=="tir" || $_SESSION['bonus'.$i.'']=="combat" || $_SESSION['bonus'.$i.'']=="vol" || $_SESSION['bonus'.$i.'']=="economie" || $_SESSION['bonus'.$i.'']=="gestion" || $_SESSION['bonus'.$i.'']=="maintenance" || $_SESSION['bonus'.$i.'']=="medecine" || $_SESSION['bonus'.$i.'']=="resistance" || $_SESSION['bonus'.$i.'']=="informatique" || $_SESSION['bonus'.$i.'']=="recherche")
				{
				print('<tr><td><div align="left">'.ucwords($_SESSION['bonus'.$i.'']).':</div></td><td><div align="left"><select name="vbonus'.$i.'">');
				for($p=1;$p!=101;$p++) { print('<option value="'.$p.'">+'.$p.'</option>'); }
				print('</select></div></td></tr>');
				}
			elseif($_SESSION['bonus'.$i.'']=="sante" || $_SESSION['bonus'.$i.'']=="forme")
				{
				print('<tr><td><div align="left">'.ucwords($_SESSION['bonus'.$i.'']).':</div></td><td><div align="left"><select name="vbonus'.$i.'">');
				for($p=10;$p!=1000;$p=$p+10) { print('<option value="'.$p.'">+'.$p.'</option>'); }
				print('</select></div></td></tr>');
				}
			}
		}
	print('</table><input type="hidden" name="verif" value="2"><br /><br />
			<input value="Terminer" type="submit" />
			</div></form>');
	}
elseif(!empty($_POST['dmin']))
	{
	$_SESSION['dmin'] = $_POST['dmin'];
	$_SESSION['dmax'] = $_POST['dmax'];
	$_SESSION['portee'] = $_POST['portee'];
	$_SESSION['modesco'] = $_POST['mode1'].$_POST['mode2'].$_POST['mode3'];
	print('<form action="#" method="POST"><div class="messagesvip">Dernière étape du formulaire de création de plan d\'objet.<br />Veuillez définir les bonus conférés à l\'objet.<br /><br /><img src="im_objets/'.$_SESSION['imageco'].'" border="0" /><br /><br />
			<table border="0" width="80%">');
	for($i=1;$i!=$_SESSION['nbrebonusco']+1;$i++)
		{
		print('<tr><td><div align="left">Bonus n°'.$i.':</div></td><td><div align="left"><select name="bonus'.$i.'">');
		if($_SESSION['typeco']!="ouu") print('<option value="invisibilite">Invisibilité</option>');
		if($_SESSION['typeco']!="ouu") print('<option value="detect">Détection de l\'invisible</option>');
		if($_SESSION['typeco']!="ouu") print('<option value="aura">Aura électrique</option>');
		if($_SESSION['typeco']!="ouu") print('<option value="focus">Focus</option>');
		if($_SESSION['typeco']=="ouu") print('<option value="sante">Santé</option>');
		if($_SESSION['typeco']=="ouu") print('<option value="forme">Forme</option>');
		print('<option value="observation">Observation</option>');
		print('<option value="discretion">Discretion</option>');
		print('<option value="tir">Tir</option>');
		print('<option value="combat">Combat</option>');
		print('<option value="vol">Vol</option>');
		print('<option value="economie">Economie</option>');
		print('<option value="gestion">Gestion</option>');
		print('<option value="maintenance">Maintenance</option>');
		print('<option value="medecine">Médecine</option>');
		print('<option value="resistance">Résistance</option>');
		print('<option value="informatique">Informatique</option>');
		print('<option value="recherche">Recherche</option>');
		print('</select></div></td></tr>');
		}
	print('</table><input type="hidden" name="verif" value="1"><br /><br />
			<input value="Etape suivante" type="submit" />
			</div></form>');
	}
elseif(!empty($_POST['nom']))
	{
	$pseudo = $_POST['nom'];
	for($i=0;$i!=strlen($pseudo);$i++)
		{
		$p = $pseudo{$i};
		if(preg_match("#[a-zA-Z0-9]#",$p) || $p==" ")
			{
			}
		else
			{
			print('Vous ne pouvez pas utiliser de caractères spéciaux dans votre le nom de l\'objet.');
			$exit = 1;
			break;
			}
		}
	if($exit!=1)
		{
		$db = mysql_connect('localhost', 'CENSURE', 'CENSURE') or die("Erreur de connexion au serveur des messages.");
		mysql_select_db('CENSURE',$db) or die("Erreur de connexion a la base de donnees des messages.");
		
		$sql = 'SELECT id FROM objets_tbl WHERE nom= "'.$_POST['nom'].'"';
		$req = mysql_query($sql);
		$res = mysql_num_rows($req);
		if($res==0)
			{
			$repertoire = 'im_objets/centre/';
			$repertoire2 = '../im_objets/centre/';
			$fichier_temp = $HTTP_POST_FILES['fichier']['tmp_name'];
			if(is_uploaded_file($HTTP_POST_FILES['fichier']['tmp_name']) && (ereg(".jpg",$HTTP_POST_FILES['fichier']['name']) || ereg(".gif",$HTTP_POST_FILES['fichier']['name']) || ereg(".png",$HTTP_POST_FILES['fichier']['name'])))
				{
				$fichier_temp = $HTTP_POST_FILES['fichier']['tmp_name'];
				$nom_fichier = $HTTP_POST_FILES['fichier']['name'];
				$size = getimagesize($HTTP_POST_FILES['fichier']['tmp_name']);
				if($size[0]=="75" && $size[1]=="75")
					{
					$nom_fichier = str_replace(" ","_",$_POST['nom']) . rand(0,1000000) . strstr($nom_fichier,".");
					copy($HTTP_POST_FILES['fichier']['tmp_name'], $repertoire . $nom_fichier);
					copy($HTTP_POST_FILES['fichier']['tmp_name'], $repertoire2 . $nom_fichier);
					$_SESSION['imageco'] = 'centre/' . $nom_fichier;
					$_SESSION['nomco'] = $_POST['nom'];
					$_SESSION['nbrebonusco'] = $_POST['nbrebonus'];
					$_SESSION['descriptionco'] = $_POST['description'];
					$_SESSION['typeco'] = $_POST['type'];
					print('<form action="#" method="POST"><div class="messagesvip">Deuxième étape du formulaire de création de plan d\'objet.<br />Veuillez définir les caractéristiques de base de l\'objet.<br /><br /><img src="im_objets/centre/'.$nom_fichier.'" border="0" /><br /><br />
							<table border="0" width="80%">
							<tr><td><div align="left">Nom de l\'objet:</div></td><td><div align="left">'.$_SESSION['nomco'].'</div></td></tr>
							<tr><td><div align="left">Type d\'objet:</div></td><td><div align="left">');
					if($_SESSION['typeco']=="armtu") { print('Arme de tir'); }
					elseif($_SESSION['typeco']=="armcu") { print('Arme de combat'); }
					elseif($_SESSION['typeco']=="vetu") { print('Vetements'); }
					elseif($_SESSION['typeco']=="obju") { print('Objet'); }
					elseif($_SESSION['typeco']=="ouu") { print('Objet à usage unique'); }
					print('</div></td></tr>
							<tr><td><div align="left">Nombre de bonus:</div></td><td><div align="left">'.$_SESSION['nbrebonusco'].'</div></td></tr>');
					if($_SESSION['typeco']=="armtu") 
						{
						print('<tr><td><div align="left">Dégats:</div></td><td><div align="left">Min: <input type="text" size="2" maxlength="2" name="dmin"> - Max: <input type="text" size="2" maxlength="2" name="dmax"></div></td></tr>
							   <tr><td><div align="left">Portée:</div></td><td><div align="left"><input type="text" size="2" maxlength="2" name="portee"></div></td></tr>
							   <tr><td><div align="left">Chargeur:</div></td><td><div align="left">9 balles</div></td></tr>
							   <tr><td><div align="left">Modes:</div></td><td><div align="left"><input type="checkbox" name="mode1" value="s">Semi-Auto<br /><input type="checkbox" name="mode2" value="b">Rafales<br /><input type="checkbox" name="mode3" value="a">Automatique<br /></div></td></tr>'); }
					elseif($_SESSION['typeco']=="armcu") { print('<tr><td><div align="left">Dégats:</div></td><td><div align="left">Min: <input type="text" size="2" maxlength="2" name="dmin"> - Max: <input type="text" size="2" maxlength="2" name="dmax"><input type="hidden" value="1" name="portee"></div></td></tr>'); }
					elseif($_SESSION['typeco']=="vetu") { print('<tr><td><div align="left">Effets:</div></td><td><div align="left">Aucun<input type="hidden" value="1" name="portee"><input type="hidden" value="1" name="dmin"><input type="hidden" value="1" name="dmax"></div></td></tr>'); }
					elseif($_SESSION['typeco']=="obju") { print('<tr><td><div align="left">Effets:</div></td><td><div align="left">Aucun<input type="hidden" value="1" name="portee"><input type="hidden" value="1" name="dmin"><input type="hidden" value="1" name="dmax"></div></td></tr>'); }
					elseif($_SESSION['typeco']=="ouu") { print('<tr><td><div align="left">Effets:</div></td><td><div align="left">Aucun<input type="hidden" value="1" name="portee"><input type="hidden" value="1" name="dmin"><input type="hidden" value="1" name="dmax"></div></td></tr>'); }
					print('</table><br /><br />
							<input value="Etape suivante" type="submit" />
							</div></form>');
					}
				else
					{
					print('L\'image doit faire exactement 75x75px.');
					}
				}
			else
				{
				print('Vous ne pouvez envoyer que des fichiers de type .jpg, .gif ou .png.');
				}
			}
		else
			{
			print('<strong>Cet objet existe déjà.</strong>');
			}
		
		mysql_close($db);
		}
	}
else
	{
	print('<form action="#" method="POST" enctype="multipart/form-data"><div class="messagesvip">Voici le formulaire de création de plan d\'objet.<br />Un prix de production sera automatiquement déduit à la fin du formulaire.<br /><br />
	<table border="0" width="80%">
	<tr><td><div align="left">Nom de l\'objet:</div></td><td><div align="left"><input name="nom" type="text" size="15" /></div></td></tr>
	<tr><td><div align="left">Type d\'objet:</div></td><td><div align="left"><select name="type">');
	if(ereg("prodarmest",$_SESSION['bdd'])) print('<option value="armtu">Arme de tir</option>');
	if(ereg("prodarmesc",$_SESSION['bdd'])) print('<option value="armcu">Arme de corps à corps</option>');
	if(ereg("prodvet",$_SESSION['bdd'])) print('<option value="vetu">Vetements</option>');
	if(ereg("prodouu",$_SESSION['bdd'])) print('<option value="ouu">Objet à usage unique</option>');
	print('<option value="obju">Objet</option>');
	print('</select></div></td></tr>
	<tr><td><div align="left">Nombre de bonus:</div></td><td><div align="left"><select name="nbrebonus">');
	for($i=0;$i!=13;$i++) { print('<option value="'.$i.'">'.$i.'</option>'); }
	print('</select></div></td></tr>
			<tr><td><div align="left">Description:</div></td><td><div align="left"><textarea cols="30" rows="3" name="description"></textarea></div></td></tr>
			<tr><td><div align="left">Image:</div></td><td><div align="left"><input type="hidden" name="MAX_FILE_SIZE" value="45000"><input type="file" name="fichier" size="30"></div></td></tr>
			</table><br /><br />
			L\'image doit faire exactement 75x75px et avoir un poids < 35 ko.<br />Les extensions autorisées sont .jpg, .gif et .png.<br /><br />
			<input value="Etape suivante" type="submit" />
			</div></form>');
	}
?></p>

</p>
</div>
<?php if($chat=="oui") 	{ if ((date("H")>=21) || (date("H")<=7)) { include("inc_bas_n_c.php"); } else { include("inc_bas_j_c.php"); } } else {	if ((date("H")>=21) || (date("H")<=7)) { include("inc_bas_n.php"); } else { include("inc_bas_j.php"); }	} ?>
<?php include("inc_bas_de_page.php"); ?>
