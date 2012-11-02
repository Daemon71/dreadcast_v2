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

$sql = 'SELECT nom,type FROM entreprises_tbl WHERE rue= "'.$_SESSION['lieu'].'" AND num= "'.$_SESSION['num'].'"' ;
$req = mysql_query($sql);
$type = mysql_result($req,0,type);
$noment = mysql_result($req,0,nom);

if($type!="usine de production")
	{
	print('<meta http-equiv="refresh" content="0 ; url=http://v2.dreadcast.net/ingame/engine.php"> ');
	exit();
	}


$sql = 'SELECT entreprise FROM principal_tbl WHERE id= "'.$_SESSION['id'].'"' ;
$req = mysql_query($sql);
$_SESSION['entreprise'] = mysql_result($req,0,entreprise);

if($_SESSION['entreprise']!="Aucune")
	{
	$sql = 'SELECT type,budget FROM entreprises_tbl WHERE nom= "'.$_SESSION['entreprise'].'"' ;
	$req = mysql_query($sql);
	$typeent = mysql_result($req,0,type);
	$capital = mysql_result($req,0,budget);
	}

mysql_close($db);

$typeobj=(empty($_POST['typeobj']))?"choisir":$_POST['typeobj'];

?>
<?php if ((date("H")>=21) || (date("H")<=7)) { include("inc_haut_n.php"); } else { include("inc_haut_j.php");} ?>

<div id="haut">
	<?php include("inc_entete.php"); ?>
	<div class="haut_aligngauche<?php if($_SESSION['entreprise']!="Aucune") { print('_haut'); } ?>">
		<p>
		<div class="titrepage">
			Usine de production
			<?php if($_SESSION['entreprise']!="Aucune") { print('<br /><b>Capital:</b> '.$capital.' Crédits'); } ?>
		</div>
		<b class="module4ie"><a href="engine.php" class="module4"><img src="im_objets/icon_retour.gif" alt="Retour" />Retour</a></b>
		</p>
	</div>
</div>
<div id="centre_usine">
	<p id="selectionner">
	<form action="engine=usine.php" method="post" name="btype" id="leform">
		<select name="typeobj" id="leselect" onchange="submit();">
			<option value="choisir"<?php if($typeobj=="choisir")print(' selected="selected"'); ?>>Choisissez le type d'objet recherch&eacute;</option>
			<?php 
			$db = mysql_connect('localhost', 'CENSURE', 'CENSURE') or die("Erreur de connexion au serveur des messages.");
			mysql_select_db('CENSURE',$db) or die("Erreur de connexion a la base de donnees des messages.");

			$sql = 'SELECT id,objet,nombre,pvente FROM stocks_tbl WHERE entreprise= "'.$noment.'"' ;
			$req = mysql_query($sql);
			$res = mysql_num_rows($req);
			
			$tab=array();
			
			for($i=0 ; $i != $res ; $i++)
				{
				$sqlo = 'SELECT type FROM objets_tbl WHERE nom= "'.mysql_result($req,$i,objet).'"' ;
				$reqo = mysql_query($sqlo);
				$tab[$i]=mysql_result($reqo,0,type);
				
				if($tab[$i]=="acac")$tab[$i]="Armes de corps &agrave; corps";
				elseif($tab[$i]=="armestir"&&mysql_result($req,$i,objet)=="Chargeur")$tab[$i]="Matériel";
				elseif($tab[$i]=="armestir")$tab[$i]="Armes de tir";
				elseif($tab[$i]=="armesprim")$tab[$i]="Armes primitives";
				elseif($tab[$i]=="armesav")$tab[$i]="Armes avanc&eacute;es";
				elseif($tab[$i]=="alimentation")$tab[$i]="Nourriture";
				elseif($tab[$i]=="boissons")$tab[$i]="Boissons";
				elseif($tab[$i]=="jag")$tab[$i]="Jeux &agrave; gratter";
				elseif($tab[$i]=="oa")$tab[$i]="Objets avancés";
				elseif($tab[$i]=="objet")$tab[$i]="Objets courants";
				elseif($tab[$i]=="om")$tab[$i]="Objets High-Tech";
				elseif($tab[$i]=="soie"||$tab[$i]=="cristal"||$tab[$i]=="tissu")$tab[$i]="Vêtements";
				elseif($tab[$i]=="pad")$tab[$i]="Appartements modestes";
				elseif($tab[$i]=="gad"||$tab[$i]=="pmd")$tab[$i]="Appartements standard";
				elseif($tab[$i]=="vd"||$tab[$i]=="gmd")$tab[$i]="Appartements luxueux";
				elseif($tab[$i]=="modif")$tab[$i]="Matériel";
				elseif($tab[$i]=="deck")$tab[$i]="Decks";
				elseif($tab[$i]=="sac")$tab[$i]="Conteneurs";
				else;
				}
				
			sort($tab);
				
			for($i=0 ; $i != sizeof($tab) ; $i++)
				{
				if($i==0||$ancientype!=$tab[$i]) 
					{
					$ancientype=$tab[$i];
					print('<option value="'.htmlentities(str_replace(" ","_",$tab[$i])).'"');if($typeobj==str_replace(" ","_",$tab[$i]))print(' selected="selected"');print('>');
					print($tab[$i]);
					print('</option>
				');
					}
				}
				mysql_close($db);
			?>
		</select>
	</form>
</p>

<br /><br /><br /><div id="boutique">
<?php 
$db = mysql_connect('localhost', 'CENSURE', 'CENSURE') or die("Erreur de connexion au serveur des messages.");
mysql_select_db('CENSURE',$db) or die("Erreur de connexion a la base de donnees des messages.");

$sql = 'SELECT id,objet,nombre,pvente FROM stocks_tbl WHERE entreprise= "'.$noment.'"' ;
$req = mysql_query($sql);
$res = mysql_num_rows($req);

if($typeobj!="choisir")
{

print('<table width="480" bgcolor="#FBFBFB"  border="0" align="center" cellpadding="0" cellspacing="0">');
$k=0;
for($i=0 ; $i != $res ; $i++)
	{
	$sqlo = 'SELECT * FROM objets_tbl WHERE nom= "'.mysql_result($req,$i,objet).'"' ;
	$reqo = mysql_query($sqlo);
	$image = mysql_result($reqo,0,image);
	$typeo = mysql_result($reqo,0,type);
	$texte = mysql_result($reqo,0,infos);
		
	if($typeo=="acac")$temp="Armes de corps &agrave; corps";
	elseif($typeo=="armestir"&&mysql_result($req,$i,objet)=="Chargeur")$temp="Matériel";
	elseif($typeo=="modif")$temp="Matériel";
	elseif($typeo=="sac")$temp="Conteneurs";
	elseif($typeo=="armestir")$temp="Armes de tir";
	elseif($typeo=="armesprim")$temp="Armes primitives";
	elseif($typeo=="armesav")$temp="Armes avanc&eacute;es";
	elseif($typeo=="alimentation")$temp="Nourriture";
	elseif($typeo=="boissons")$temp="Boissons";
	elseif($typeo=="jag")$temp="Jeux &agrave; gratter";
	elseif($typeo=="oa")$temp="Objets avancés";
	elseif($typeo=="objet")$temp="Objets courants";
	elseif($typeo=="om")$temp="Objets High-Tech";
	elseif($typeo=="soie"||$typeo=="cristal"||$typeo=="tissu")$temp="Vêtements";
	elseif($typeo=="pad")$temp="Appartements modestes";
	elseif($typeo=="gad"||$typeo=="pmd")$temp="Appartements standard";
	elseif($typeo=="vd"||$typeo=="gmd")$temp="Appartements luxueux";
	elseif($typeo=="modif")$temp="Matériel";
	elseif($typeo=="deck")$temp="Decks";
	elseif($typeo=="sac")$temp="Conteneurs";
	else;

	if(str_replace(" ","_",$temp)==$typeobj)
	{
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
		($texte=="")?print('Texte à venir'):print($texte);
		print('</div>
		<div id="article_bas"></div></div>
		<td><div align="center"><a href="http://v2.dreadcast.net/info=objet.php?'.mysql_result($req,$i,objet).'" target="_blank"><img src="im_objets/'.$image.'" border="0" height="35" width="35"></a></td></div>');
	print('<td><div align="center"><strong>Stock de 10 '.mysql_result($req,$i,objet).'</strong><br />');
	if($typeo=="objet")
		{
		print('<i>Objet</i>');
		}
	elseif($typeo=="oa")
		{
		print('<i>Objet avanc&eacute;</i>');
		}
	elseif($typeo=="om")
		{
		print('<i>Objet high tech</i>');
		}
	elseif($typeo=="armestir")
		{
		print('<i>Arme de tir</i>');
		}
	elseif($typeo=="boissons")
		{
		print('<i>Boisson</i>');
		}
	elseif(($typeo=="tissu") || ($typeo=="soie") || ($typeo=="cristal"))
		{
		print('<i>V&ecirc;tement</i>');
		}
	elseif($typeo=="armesprim")
		{
		print('<i>Arme de corps &agrave; corps avanc&eacute;e</i>');
		}
	elseif($typeo=="acac")
		{
		print('<i>Arme de corps &agrave; corps</i>');
		}
	elseif($typeo=="armesav")
		{
		print('<i>Arme avanc&eacute;e</i>');
		}
	elseif($typeo=="modif")
		{
		print('<i>Modification d\'arme</i>');
		}
	elseif($typeo=="deck")
		{
		print('<i>Deck</i>');
		}
	elseif($typeo=="sac")
		{
		print('<i>Conteneur</i>');
		}
	
	print('</div></td>');
	$prix = mysql_result($req,$i,pvente) * 10;
	print('<td><div align="center"><strong>Prix : </strong><em>'.$prix.' Cr </em><br />');
	if(mysql_result($req,$i,nombre)>0)
		{
		if((($typeo=="objet") || ($typeo=="oa") || ($typeo=="om") || ($typeo=="tissu") || ($typeo=="soie") || ($typeo=="cristal") || ($typeo=="deck") || ($typeo=="sac")) && ($typeent=="boutique spécialisee"))
			{
			$sql1 = 'SELECT id FROM objets_tbl WHERE nom= "'.mysql_result($req,$i,objet).'"' ;
			$req1 = mysql_query($sql1);
			print('<a href="engine=usinef.php?id='.mysql_result($req1,0,id).'">Acheter</a></div></td>');
			}
		elseif((($typeo=="armestir") || ($typeo=="armesprim") || ($typeo=="acac") || ($typeo=="armesav") || ($typeo=="modif")) && ($typeent=="boutique armes"))
			{
			$sql1 = 'SELECT id FROM objets_tbl WHERE nom= "'.mysql_result($req,$i,objet).'"' ;
			$req1 = mysql_query($sql1);
			print('<a href="engine=usinef.php?id='.mysql_result($req1,0,id).'" class="lien">Acheter</a></div></td>');
			}
		}
	else
		{
		print('<span class="color3">Rupture de stock !</span>');
		}
	print('</tr>');
	}
	}
print('</table>');
}

mysql_close($db);

?>

</div>
</div>
<?php if($chat=="oui") 	{ if ((date("H")>=21) || (date("H")<=7)) { include("inc_bas_n_c.php"); } else { include("inc_bas_j_c.php"); } } else {	if ((date("H")>=21) || (date("H")<=7)) { include("inc_bas_n.php"); } else { include("inc_bas_j.php"); }	} ?>
<?php include("inc_bas_de_page.php"); ?>
