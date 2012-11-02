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

$sql = 'SELECT credits FROM principal_tbl WHERE id= "'.$_SESSION['id'].'"' ;
$req = mysql_query($sql);
$_SESSION['credits'] = mysql_result($req,0,credits);

$sql = 'SELECT nom,type FROM entreprises_tbl WHERE rue= "'.$_SESSION['lieu'].'" AND num= "'.$_SESSION['num'].'"' ;
$req = mysql_query($sql);
$type = mysql_result($req,0,type);
$noment = mysql_result($req,0,nom);

if($type!="agence immobiliaire")
	{
	print('<meta http-equiv="refresh" content="0 ; url=http://v2.dreadcast.net/ingame/engine.php"> ');
	exit();
	}

$critere=(empty($_POST['critere']))?"choisir":$_POST['critere'];
mysql_close($db);
?>
<?php if ((date("H")>=21) || (date("H")<=7)) { include("inc_haut_n.php"); } else { include("inc_haut_j.php");} ?>

<div id="haut">
	<?php include("inc_entete.php"); ?>
	<div class="haut_aligngauche">
		<p>
		<div class="titrepage">
			Annonces de locations
		</div>
		<b class="module4ie"><a href="engine.php" class="module4"><img src="im_objets/icon_retour.gif" alt="Retour" />Retour</a></b>
		</p>
	</div>
</div>
<div id="centre_agence">
	<p id="selectionner">
		<form action="#" method="post" name="btype" id="leform">
			<select name="critere" id="leselect" onchange="submit();">
				<option value="choisir"<?php if($critere=="choisir")print(' selected="selected"'); ?>>S&eacute;l&eacute;ctionnez un crit&egrave;re</option>
				<option value="prix"<?php if($critere=="prix")print(' selected="selected"'); ?>>Le prix</option>
				<option value="confort"<?php if($critere=="confort")print(' selected="selected"'); ?>>Le confort</option>
				<option value="options"<?php if($critere=="options")print(' selected="selected"'); ?>>Les options</option>
				<option value="proprietaire"<?php if($critere=="proprietaire")print(' selected="selected"'); ?>>Le propri&eacute;taire</option>
				<option value="lieu"<?php if($critere=="lieu")print(' selected="selected"'); ?>>Le lieu</option>
				<option value="candidature"<?php if($critere=="candidature")print(' selected="selected"'); ?>>Ne n&eacute;c&eacute;ssite pas de candidature</option>
			</select>
		</form>
	</p>

	<br /><br /><br /><div id="boutique">
	
<?php                  

print('<table cellpadding="0" cellspacing="0">');

$db = mysql_connect('localhost', 'CENSURE', 'CENSURE') or die("Erreur de connexion au serveur des messages.");
mysql_select_db('CENSURE',$db) or die("Erreur de connexion a la base de donnees des messages.");

$k=0;

if($critere=="prix")
{
	$sql = 'SELECT * FROM proprietaire_tbl WHERE location!= "0" ORDER BY location ASC' ; //Locations
	$req = mysql_query($sql);
	$res = mysql_num_rows($req);
}
elseif($critere=="confort")
{
	$puissance = array(array());
	
	$sql = 'SELECT id,num,rue FROM proprietaire_tbl WHERE location!= "0"' ; //Locations
	$req = mysql_query($sql);
	$res = mysql_num_rows($req);
	
	for($i=0 ; $i != $res ; $i++)
	{
		$id = mysql_result($req,$i,id);
		$numl = mysql_result($req,$i,num);
		$ruel = mysql_result($req,$i,rue);
		$sql1 = 'SELECT id FROM principal_tbl WHERE ruel= "'.$ruel.'" AND numl= "'.$numl.'"' ;
		$req1 = mysql_query($sql1);
		$res1 = mysql_num_rows($req1);
				
		if($res1==0)						//Si pas déjà loué
		{
			$sql2 = 'SELECT nom FROM lieu_tbl WHERE num= "'.$numl.'" AND rue= "'.$ruel.'"' ;
			$req2 = mysql_query($sql2);
			$nom = mysql_result($req2,0,nom);
			if($nom=="Appartement 17m")$puissance[0][]=$id;
			if($nom=="Appartement 32m")$puissance[1][]=$id;
			if($nom=="Appartement 56m")$puissance[2][]=$id;
			if($nom=="Appartement 140m"||$nom=="Maison 90m")$puissance[3][]=$id;
			if($nom=="Maison 210m")$puissance[4][]=$id;
			if($nom=="Maison 335m")$puissance[5][]=$id;
			if($nom=="Maison 405m")$puissance[6][]=$id;
			if($nom=="Villa et piscine")$puissance[7][]=$id;
		}
	}
}
elseif($critere=="proprietaire")
{
	$sql = 'SELECT * FROM proprietaire_tbl WHERE location!= "0" ORDER BY pseudo ASC' ; //Locations
	$req = mysql_query($sql);
	$res = mysql_num_rows($req);
}
elseif($critere=="options")
{
	$puissance = array(array());
	
	$sql = 'SELECT id,num,rue FROM proprietaire_tbl WHERE location!= "0"' ; //Locations
	$req = mysql_query($sql);
	$res = mysql_num_rows($req);
	
	for($i=0 ; $i != $res ; $i++)
	{
		$id = mysql_result($req,$i,id);
		$numl = mysql_result($req,$i,num);
		$ruel = mysql_result($req,$i,rue);
		$sql1 = 'SELECT id FROM principal_tbl WHERE ruel= "'.$ruel.'" AND numl= "'.$numl.'"' ;
		$req1 = mysql_query($sql1);
		$res1 = mysql_num_rows($req1);
				
		if($res1==0)						//Si pas déjà loué
		{
			$sql2 = 'SELECT * FROM lieu_tbl WHERE num= "'.$numl.'" AND rue= "'.$ruel.'"' ;
			$req2 = mysql_query($sql2);
			if(!mysql_num_rows($req)) continue;
			$nom = mysql_result($req2,0,nom);
			$codel = mysql_result($req2,0,code);
			$cameral = mysql_result($req2,0,camera);
			$chatl = mysql_result($req2,0,chat);
			$frigol = mysql_result($req2,0,frigo);
			$points=0;
			if($codel!=0)$points+=strlen($codel);
			if($cameral=="Oui"||$cameral=="Pol")$points+=1;
			if($chat=="oui")$points+=1;
			if($frigol=="Oui")$points+=1;
			if($frigol=="Ame")$points+=2;
			$puissance[$points][]=$id;
		}
	}
}
if($critere=="lieu"&&!empty($_POST['rue']))
{
	$sql = 'SELECT * FROM proprietaire_tbl WHERE location!= "0" AND rue="'.$_POST['rue'].'"' ; //Locations
	$req = mysql_query($sql);
	$res = mysql_num_rows($req);
}
if(($critere=="lieu"&&empty($_POST['rue']))||($critere=="lieu"&&!empty($_POST['rue'])&&$res==0)) //Si arrive sur lieu ou rien pour cette rue
{
	if(empty($_POST['rue']))
	{
		print('<p id="textelse">Dans quelle rue cherchez-vous un appartement ?<br />
		<form action="#" method="post" name="btype" id="leform">
			<input name="critere" type="hidden" value="lieu" />
			<input name="rue" type="text" id="rue" value="" size="20" maxlength="30" /> <input type="submit" name="submit" value="Valider" />
		</form></p>');
		}
	else
	{
		print('<p id="textelse">Il n\'y a pas de logement en location dans cette rue.<br />Chercher dans une autre rue ?<br />
		<form action="#" method="post" name="btype" id="leform">
			<input name="critere" type="hidden" value="lieu" />
			<input name="rue" type="text" id="rue" value="" size="20" maxlength="30" /> <input type="submit" name="submit" value="Valider" />
		</form></p>');
	}
}
elseif($critere=="candidature")
{
	$sql = 'SELECT * FROM proprietaire_tbl WHERE location!= "0" AND cand= "non" ORDER BY cand ASC' ; //Locations
	$req = mysql_query($sql);
	$res = mysql_num_rows($req);
}



if($critere!="confort"&&$critere!="options"&&$critere!="choisir"&&!($critere=="lieu"&&empty($_POST['rue']))&&!($critere=="lieu"&&!empty($_POST['rue'])&&$res==0))
{
	for($i=0 ; $i != $res ; $i++)
	{
		$numl = mysql_result($req,$i,num);
		$ruel = mysql_result($req,$i,rue);
		$location = mysql_result($req,$i,location);
		$propriol = mysql_result($req,$i,pseudo);
		$candl = mysql_result($req,$i,cand);
		$sql1 = 'SELECT id FROM principal_tbl WHERE ruel= "'.$ruel.'" AND numl= "'.$numl.'"' ;
		$req1 = mysql_query($sql1);
		$res1 = mysql_num_rows($req1);
		if($res1==0)						//Si pas déjà loué
		{
			$sql2 = 'SELECT nom,code,camera,chat,frigo FROM lieu_tbl WHERE num= "'.$numl.'" AND rue= "'.$ruel.'"' ;
			$req2 = mysql_query($sql2);
			$res2 = mysql_num_rows($req2);
			
			if($res2 != 0)
			{
			$noml = mysql_result($req2,0,nom);
			$codel = mysql_result($req2,0,code);
			$cameral = mysql_result($req2,0,camera);
			$chatl = mysql_result($req2,0,chat);
			$frigol = mysql_result($req2,0,frigo);
			}
			$sql2 = 'SELECT image,infos,type,puissance FROM objets_tbl WHERE nom= "'.$noml.'"' ;
			$req2 = mysql_query($sql2);
			$res2 = mysql_num_rows($req2);
			
			if($res2 != 0)
			{
			$imagel = mysql_result($req2,0,image);
			$infosl = mysql_result($req2,0,infos);
			$typel = mysql_result($req2,0,type);
			$confortl = mysql_result($req2,0,puissance);
			}
			if($typel=="pad")
			{
				$surfacel=($noml=="Appartement 17m")?"17m²":"32m²";
				$typel="Petit appartement";
			}
			elseif($typel=="gad")
			{
				$surfacel=($noml=="Appartement 56m")?"56m²":"140m²";
				$typel="Grand appartement";
			}
			elseif($typel=="pmd")
			{
				$surfacel=($noml=="Maison 90m")?"90m²":"210m²";
				$typel="Petite maison";
			}
			elseif($typel=="gmd")
			{
				$surfacel=($noml=="Maison 335m")?"335m²":"405m²";
				$typel="Grande maison";
			}
			elseif($typel=="vd")
			{
				$typel="Villa avec piscine";
			}
		
			if($k/2 == round($k/2))
			{
				print('<tr class="color1" onmouseover="affiche_art(\'article_'.$i.'\',true);" onmouseout="affiche_art(\'article_'.$i.'\',false);">');
			}
			else
			{
				print('<tr class="color2" onmouseover="affiche_art(\'article_'.$i.'\',true);" onmouseout="affiche_art(\'article_'.$i.'\',false);">');
			}
			$k++;
			
			print('
			<div id="article_'.$i.'" style="display:none;position:absolute;left:-300px;top:1px;width:311px;padding:0;text-align:justify;color:white;"  onmouseover="affiche_art(\'article_'.$i.'\',true);" onmouseout="affiche_art(\'article_'.$i.'\',false);">');
			print('<div id="article_haut"></div>');
			print('<div id="article_milieu"><p>');
			print('<strong>Type de produit :</strong> '.$typel.'<br/><strong>Situation :</strong> '.$numl.' '.$ruel.'<br/><strong>Confort :</strong> '.$confortl.'/10<br />');if($noml!="Villa et piscine")print('<strong>Surface :</strong> '.$surfacel.'<br />');print('<strong>Propri&eacute;taire :</strong> '.$propriol.'<br /><strong>Options :</strong> ');
			if($codel!=0)
			{	
				print('Digicode ('.strlen($codel).' chiffres)');
				if($chatl=="oui") print(', salon de discussion');
				if($cameral=="Pol")	print(', camera de police');
				if($cameral=="Oui") print(', camera de surveillance');
				if($frigol=="Oui") print(', frigo standard');
				if($frigol=="Ame") print(', frigo am&eacute;ricain');
			}
			elseif($chatl=="oui")
			{
				print('Salon de discussion');
				if($cameral=="Pol")	print(', camera de police');
				if($cameral=="Oui") print(', camera de surveillance');
				if($frigol=="Oui") print(', frigo standard');
				if($frigol=="Ame") print(', frigo am&eacute;ricain');
			}
			elseif($cameral=="Pol")
			{
				print('Camera de police');
				if($cameral=="Oui") print(', camera de surveillance');
				if($frigol=="Oui") print(', frigo standard');
				if($frigol=="Ame") print(', frigo am&eacute;ricain');
			}
			elseif($cameral=="Oui")
			{
				print('Camera de surveillance');
				if($frigol=="Oui") print(', frigo standard');
				if($frigol=="Ame") print(', frigo am&eacute;ricain');
			}
			elseif($frigol!="Non")
			{
				if($frigol=="Oui") print('Frigo standard');
				if($frigol=="Ame") print('Frigo am&eacute;ricain');
			}
			else print('Aucune');
			if($infosl!="") print('<br /><br />'.$infosl);
			print('</div>
			<div id="article_bas"></div></div>
			<td><div align="center"><img src="im_objets/'.$imagel.'" style="width:20px;height:20px;" border="0"></td></div>');
			print('<td><div align="center"><strong>'.$noml.'</strong>');
			print('</div></td>');
			print('<td><div align="center" style="margin:2px 0 4px 0;"><em>'.$location.' Crédits/Jour</em><br />');
			if($candl=="oui")
			{
				print('<a href="engine=candlouer.php?rue='.$ruel.'&num='.$numl.'" class="lien">Demander la location</a></div></td>');
			}
			else
			{
				print('<a href="engine=louer.php?rue='.$ruel.'&num='.$numl.'" class="lien">Louer</a></div></td>');
			}
			print('</tr>');
		}
	}
}
if($critere=="confort"||$critere=="options")
{
	$k=0;
	for($j=0 ; $j != sizeof($puissance) ; $j++)
	{
		for($i=0 ; $i != sizeof($puissance[$j]) ; $i++)
		{
			$sql = 'SELECT num,rue,location,pseudo,cand FROM proprietaire_tbl WHERE id="'.$puissance[$j][$i].'"' ;
			$req = mysql_query($sql);
			$numl = mysql_result($req,0,num);
			$ruel = mysql_result($req,0,rue);
			$location = mysql_result($req,0,location);
			$propriol = mysql_result($req,0,pseudo);
			$candl = mysql_result($req,0,cand);
			$sql2 = 'SELECT nom,code,camera,chat,frigo FROM lieu_tbl WHERE num= "'.$numl.'" AND rue= "'.$ruel.'"' ;
			$req2 = mysql_query($sql2);
			$noml = mysql_result($req2,0,nom);
			$codel = mysql_result($req2,0,code);
			$cameral = mysql_result($req2,0,camera);
			$chatl = mysql_result($req2,0,chat);
			$frigol = mysql_result($req2,0,frigo);
			$sql2 = 'SELECT image,infos,type,puissance FROM objets_tbl WHERE nom= "'.$noml.'"' ;
			$req2 = mysql_query($sql2);
			$imagel = mysql_result($req2,0,image);
			$infosl = mysql_result($req2,0,infos);
			$typel = mysql_result($req2,0,type);
			$confortl = mysql_result($req2,0,puissance);
			if($typel=="pad")
			{
				$surfacel=($noml=="Appartement 17m")?"17m²":"32m²";
				$typel="Petit appartement";
			}
			elseif($typel=="gad")
			{
				$surfacel=($noml=="Appartement 56m")?"56m²":"140m²";
				$typel="Grand appartement";
			}
			elseif($typel=="pmd")
			{
				$surfacel=($noml=="Maison 90m")?"90m²":"210m²";
				$typel="Petite maison";
			}
			elseif($typel=="gmd")
			{
				$surfacel=($noml=="Maison 335m")?"335m²":"405m²";
				$typel="Grande maison";
			}
			elseif($typel=="vd")
			{
				$typel="Villa avec piscine";
			}
			
			if($k/2 == round($k/2))
			{
				print('<tr class="color1" onmouseover="affiche_art(\'article_'.$k.'\',true);" onmouseout="affiche_art(\'article_'.$k.'\',false);">');
			}
			else
			{
				print('<tr class="color2" onmouseover="affiche_art(\'article_'.$k.'\',true);" onmouseout="affiche_art(\'article_'.$k.'\',false);">');
			}
			print('
			<div id="article_'.$k.'" style="display:none;position:absolute;left:-300px;top:1px;width:311px;padding:0;text-align:justify;color:white;"  onmouseover="affiche_art(\'article_'.$i.'\',true);" onmouseout="affiche_art(\'article_'.$i.'\',false);">');
			print('<div id="article_haut"></div>');
			print('<div id="article_milieu"><p>');
			print('<strong>Type de produit :</strong> '.$typel.'<br/><strong>Situation :</strong> '.$numl.' '.$ruel.'<br/><strong>Confort :</strong> '.$confortl.'/10<br />');if($noml!="Villa et piscine")print('<strong>Surface :</strong> '.$surfacel.'<br />');print('<strong>Propri&eacute;taire :</strong> '.$propriol.'<br /><strong>Options :</strong> ');
			if($codel!=0)
			{	
				print('Digicode ('.strlen($codel).' chiffres)');
				if($chatl=="oui") print(', salon de discussion');
				if($cameral=="Pol")	print(', camera de police');
				if($cameral=="Oui") print(', camera de surveillance');
				if($frigol=="Oui") print(', frigo standard');
				if($frigol=="Ame") print(', frigo am&eacute;ricain');
			}
			elseif($chatl=="oui")
			{
				print('Salon de discussion');
				if($cameral=="Pol")	print(', camera de police');
				if($cameral=="Oui") print(', camera de surveillance');
				if($frigol=="Oui") print(', frigo standard');
				if($frigol=="Ame") print(', frigo am&eacute;ricain');
			}
			elseif($cameral=="Pol")
			{
				print('Camera de police');
				if($cameral=="Oui") print(', camera de surveillance');
				if($frigol=="Oui") print(', frigo standard');
				if($frigol=="Ame") print(', frigo am&eacute;ricain');
			}
			elseif($cameral=="Oui")
			{
				print('Camera de surveillance');
				if($frigol=="Oui") print(', frigo standard');
				if($frigol=="Ame") print(', frigo am&eacute;ricain');
			}
			elseif($frigol!="Non")
			{
				if($frigol=="Oui") print('Frigo standard');
				if($frigol=="Ame") print('Frigo am&eacute;ricain');
			}
			else print('Aucune');
			if($infosl!="") print('<br /><br />'.$infosl);
			print('</div>
			<div id="article_bas"></div></div>
			<td><div align="center"><img src="im_objets/'.$imagel.'" style="width:20px;height:20px;" border="0"></td></div>');
			print('<td><div align="center"><strong>'.$noml.'</strong>');
			print('</div></td>');
			print('<td><div align="center" style="margin:2px 0 4px 0;"><em>'.$location.' Crédits/Jour</em><br />');
			if($candl=="oui")
			{
				print('<a href="engine=candlouer.php?rue='.$ruel.'&num='.$numl.'" class="lien">Demander la location</a></div></td>');
			}
			else
			{
				print('<a href="engine=louer.php?rue='.$ruel.'&num='.$numl.'" class="lien">Louer</a></div></td>');
			}
			print('</tr>');
			$k++;
		}
	}
}
print('</table>');

mysql_close($db);

?>
</div>
</div>
<?php if($chat=="oui") 	{ if ((date("H")>=21) || (date("H")<=7)) { include("inc_bas_n_c.php"); } else { include("inc_bas_j_c.php"); } } else {	if ((date("H")>=21) || (date("H")<=7)) { include("inc_bas_n.php"); } else { include("inc_bas_j.php"); }	} ?>
<?php include("inc_bas_de_page.php"); ?>
