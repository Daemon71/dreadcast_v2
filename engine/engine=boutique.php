<?php 
session_start();

$tracage = true;

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

$sql = 'SELECT nom,type,ouvert FROM entreprises_tbl WHERE rue= "'.$_SESSION['lieu'].'" AND num= "'.$_SESSION['num'].'"' ;
$req = mysql_query($sql);
$type = mysql_result($req,0,type);
$noment = mysql_result($req,0,nom);
$ouvert = mysql_result($req,0,ouvert);

if(($type!="boutique armes") && ($type!="boutique vetements") && ($type!="hopital") && ($type!="boutique spécialisee") && ($type!="centre de recherche") && ($type!="agence immobiliaire") && ($type!="bar cafe") && ($type!="garage") && ($type!="restaurant") && ($type!="ventes aux encheres"))
	{
	print('<meta http-equiv="refresh" content="0 ; url=http://v2.dreadcast.net/ingame/engine.php"> ');
	exit();
	}
if($ouvert!="oui")
	{
	print('<meta http-equiv="refresh" content="0 ; url=http://v2.dreadcast.net/ingame/engine.php"> ');
	exit();
	}

mysql_close($db);

$typeobj=(empty($_POST['typeobj']))?"tous":$_POST['typeobj'];
if($type=="boutique armes") $typeobj=(empty($_POST['typeobj']))?"choisir":$_POST['typeobj'];

if(isset($_GET['typeobj'])) $typeobj=$_GET['typeobj'];

?>
<?php if ((date("H")>=21) || (date("H")<=7)) { include("inc_haut_n.php"); } else { include("inc_haut_j.php");} ?>

<div id="haut">
	<?php include("inc_entete.php"); ?>
	<div class="haut_aligngauche">
		<p>
		<div class="titrepage">
			Boutique
		</div>
		<b class="module4ie"><a href="engine.php" class="module4"><img src="im_objets/icon_retour.gif" alt="Retour" />Retour</a></b>
		</p>
	</div>
</div>
<div id="centre_<?php if($type=="agence immobiliaire")print('agence');elseif($type=="bar cafe")print('bar');if($type=="boutique armes")print('armurerie');elseif($type=="boutique spécialisee")print('bazar');elseif($type=="centre de recherche")print('centre'); ?>">
<a id="boutonAllop" href="engine=allopass.php"></a>
<p id="selectionner">
	<form action="engine=boutique.php" method="post" name="btype" id="leform">
		<select name="typeobj" id="leselect" onchange="submit();">
			<?php if($type=="boutique armes") { print('<option value="choisir"'); if($typeobj=="choisir")print(' selected="selected"'); print(' style="display:none;">Choisissez le type d\'objet recherch&eacute;</option>'); }
			print('<option value="tous"'); if($typeobj=="tous") print(' selected="selected"'); print('>Tous</option>'); ?>
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
				elseif($tab[$i]=="modif")$tab[$i]="Matériel";
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
				elseif($tab[$i]=="armtu")$tab[$i]="Armes de tir";
				elseif($tab[$i]=="armcu")$tab[$i]="Armes de corps &agrave; corps";
				elseif($tab[$i]=="vetu")$tab[$i]="Vêtements";
				elseif($tab[$i]=="obju")$tab[$i]="Objets";
				elseif($tab[$i]=="deck")$tab[$i]="Decks";
				elseif($tab[$i]=="sac")$tab[$i]="Conteneurs";
				elseif($tab[$i]=="ouu")$tab[$i]="Objet à usage unique";
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

$sql = 'SELECT S.id,S.objet,S.nombre,S.pvente FROM stocks_tbl S, objets_tbl O WHERE O.prod="1" AND O.nom = S.objet AND S.entreprise= "'.$noment.'" ORDER BY S.id' ;
$req = mysql_query($sql);
$res = mysql_num_rows($req);
			
if($type=="boutique armes" && $typeobj=="choisir")
	{
	print('<p id="textelse">Les armes suivantes ne peuvent &ecirc;tre port&eacute;es sur la voie publique sans un permis de port d\'arme sp&eacute;cifique distribué par les forces de police.</p>');
	}

if($typeobj!="choisir")
{
print('<table cellpadding="0" cellspacing="0">');
$k=0;
for($i=0 ; $i != $res ; $i++)
	{
	$sqlo = 'SELECT * FROM objets_tbl WHERE nom= "'.mysql_result($req,$i,objet).'"' ;
	$reqo = mysql_query($sqlo);
	$reso = mysql_num_rows($reqo);
	if($reso!=0)
		{
		$idobj = mysql_result($reqo,0,id);
		$image = mysql_result($reqo,0,image);
		$typeo = mysql_result($reqo,0,type);
		$puissancemin = mysql_result($reqo,0,puissance);
		$ecart=mysql_result($reqo,0,ecart);
		$puissancemax = $puissancemin + $ecart;
		$distance = mysql_result($reqo,0,distance);
		$texte = mysql_result($reqo,0,infos);
		$mode = mysql_result($reqo,0,modes);
		}
	
	if($typeo=="acac")$temp="Armes de corps &agrave; corps";
	elseif($typeo=="armestir"&&mysql_result($req,$i,objet)=="Chargeur")$temp="Matériel";
	elseif($typeo=="modif")$temp="Matériel";
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
	elseif($typeo=="armtu")$temp="Armes de tir";
	elseif($typeo=="armcu")$temp="Armes de corps &agrave; corps";
	elseif($typeo=="vetu")$temp="Vêtements";
	elseif($typeo=="obju")$temp="Objets";
	elseif($typeo=="deck")$temp="Decks";
	elseif($typeo=="sac")$temp="Conteneurs";
	elseif($typeo=="ouu")$temp="Objet à usage unique";
	else;

	if(str_replace(" ","_",$temp)==$typeobj OR $typeobj=="tous")
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
		<div id="article_'.$i.'" style="z-index:400;display:none;position:absolute;left:-300px;top:1px;width:311px;padding:0;text-align:justify;color:white;"  onmouseover="affiche_art(\'article_'.$i.'\',true);" onmouseout="affiche_art(\'article_'.$i.'\',false);">');
		print('<div id="article_haut"></div>');
		print('<div id="article_milieu"><p>');
		($texte=="")?print('Texte à venir'):print($texte);
		
		if(($typeo=="armestir"&&mysql_result($req,$i,objet)!="Chargeur")||$typeo=="armesav"||$typeo=="armesprim"||$typeo=="acac"||$typeo=="armtu"||$typeo=="armcu")
		{
		print('<br /><br />Dégats : <em>'.$puissancemin.' - '.$puissancemax.'</em><br />Portée : '.$distance.'m');
		if(($typeo=="armestir"&&mysql_result($req,$i,objet)!="Chargeur")||$typeo=="armesav"||$typeo=="armtu"||$typeo=="armcu")
			{
			print('<br />Mode(s) : ');
			if(ereg("s",$mode)) { print('<em>Semi-Auto</em>'); 
				if(ereg("b",$mode)) { print(', <em>Rafales</em>'); }  
				if(ereg("a",$mode)) { print(', <em>Automatique</em>'); }
				}  
			else if(ereg("b",$mode)) { print('<em>Rafales</em>'); 
				if(ereg("a",$mode)) { print(', <em>Automatique</em>'); }
				}
			else{ print('<em>Automatique</em>'); }
			}
		
		if($typeo=="armtu"||$typeo=="armcu"||$typeo=="vetu"||$typeo=="obju")
			{
			print('<br /><br />Bonus :<br />');
			$sqlz = 'SELECT nature,bonus FROM recherche_effets_tbl WHERE ido= "'.$idobj.'"' ;
	            $reqz = mysql_query($sqlz);
      	      $resz = mysql_num_rows($reqz);
            	
            	if($resz==0) print('Aucun<br />');
            
			for($z=0;$z<$resz;$z++)
				{			
	            	if(mysql_result($reqz,$z,bonus) !=0 )
					print(ucwords(mysql_result($reqz,$z,nature)).' +'.mysql_result($reqz,$z,bonus).'<br />');
				else
					print(ucwords(mysql_result($reqz,$z,nature)).'<br />');
				}
		}
		}
		elseif($typeo=="alimentation")
		{
			print('<br /><br />Faim +'.$puissancemin.'%<br />Forme +'.$puissancemin);
		}
		elseif($typeo=="boissons")
		{
			if($ecart==0) print('<br /><br />Soif +'.$puissancemin.'%<br />Forme +'.$puissancemin);
			else print('<br /><br />Soif +'.$puissancemin.'%<br />Forme +'.$puissancemin.'<br />Alcool +'.$ecart);
		}
		elseif($typeo=="sac")
		{
			print('<br /><br />Contenance : '.$puissancemin.' objet');if($puissancemin!=1)print('s');
			if($ecart==1)print('<br />Sécurisé par mot de passe');
		}
		
		$sqlz = 'SELECT nature,bonus FROM recherche_effets_tbl WHERE ido= "'.$idobj.'"' ;
		$reqz = mysql_query($sqlz);
		$resz = mysql_num_rows($reqz);
		
		if($resz!=0) print('<br /><br />Bonus :<br />');
		
		for($z=0;$z<$resz;$z++)
			{
			
			$nature = mysql_result($reqz,$z,nature);
			if($nature=="invisibilite") $nature="invisibilit&eacute;";
			elseif($nature=="medecine") $nature="m&eacute;decine";
			elseif($nature=="discretion") $nature="discr&eacute;tion";
			elseif($nature=="resistance") $nature="r&eacute;sistance";
			
			if(mysql_result($reqz,$z,bonus)!=0) print(ucwords($nature).' +'.mysql_result($reqz,$z,bonus).'<br />');
			else print(ucwords($nature).'<br />');
			}
		
		print('</div>
		<div id="article_bas"></div></div>
		<td><div align="center"><a href="http://v2.dreadcast.net/info=objet.php?'.mysql_result($req,$i,objet).'" target="_blank"><img src="im_objets/'.$image.'" '); if(ereg("centre/",$image)) print('width="73" height="73" style="border:1px solid black;"'); else print('border="0"'); print('></a></td></div>');
	print('<td><div align="center"><strong>'.mysql_result($req,$i,objet).'</strong><br />');
	if($typeo=="objet")
		{
		print('<i>Objet</i>');
		}
	elseif($typeo=="oa")
		{
		print('<i>Objet avanc&eacute;</i>');
		}
	elseif($typeo=="alimentation")
		{
		print('<i>Alimentation rapide</i>');
		}
	elseif($typeo=="jag")
		{
		print('<i>Jeux à gratter</i>');
		}
	elseif($typeo=="om")
		{
		print('<i>Objet high tech</i>');
		}
	elseif($typeo=="armestir"&&mysql_result($req,$i,objet)!="Chargeur")
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
		print('<i>Arme de corps &agrave; corps avanc&eacute;e</i><!--<br />Dégats : <em>'.$puissancemin.' - '.$puissancemax.'</em><br />Portée : '.$distance.'m-->');
		}
	elseif($typeo=="acac")
		{
		print('<i>Arme de corps &agrave; corps</i><!--<br />Dégats : <em>'.$puissancemin.' - '.$puissancemax.'</em><br />Portée : '.$distance.'m-->');
		}
	elseif($typeo=="armesav")
		{
		print('<i>Arme avanc&eacute;e</i>');
		}
	elseif($typeo=="modif")
		{
		print('<i>Modification d\'arme</i>');
		}
	elseif($typeo=="armcu")
		{
		print('<i>Arme unique de corps &agrave; corps</i>');
		}
	elseif($typeo=="armtu")
		{
		print('<i>Arme unique de tir</i>');
		}
	elseif($typeo=="vetu")
		{
		print('<i>Vêtements unique</i>');
		}
	elseif($typeo=="obju")
		{
		print('<i>Objet unique</i>');
		}
	elseif($typeo=="sac")
		{
		print('<i>Conteneur</i>');
		}
	elseif($typeo=="ouu")
		{
		print('<i>Objet à usage unique</i>');
		}
	
	if($typeo == "armcu" || $typeo == "armtu" || $typeo == "vetu" || $typeo == "obju")
		{
		$sql6 = 'SELECT etoiles FROM recherche_plans_tbl WHERE ido= "'.$idobj.'"' ;
		$req6 = mysql_query($sql6);
		if(mysql_num_rows($req6) && mysql_result($req6,0,etoiles) != 0)
			{
			print('<br />');
			for($kk=0;$kk<mysql_result($req6,0,etoiles);$kk++) print('<img src="im_objets/petoile.gif" border="0" title="'.((mysql_result($req6,0,etoiles)==1)?"Objet de qualité":((mysql_result($req6,0,etoiles)==2)?"Objet recommandé":"A ne pas manquer")).'">');
			}
		}
	
	print('</div></td>');
	print('<td><div align="center"><strong>Prix : </strong><em>'.mysql_result($req,$i,pvente).' Cr </em><br />');
	if(mysql_result($req,$i,nombre)>0)
		{
		$sql1 = 'SELECT id FROM objets_tbl WHERE nom= "'.mysql_result($req,$i,objet).'"' ;
		$req1 = mysql_query($sql1);
		print('<a href="engine=achat'.(($type=="agence immobiliaire")?'maison':'').'.php?id='.mysql_result($req1,0,id)); if($type=="bar cafe") print('&typeobj='.$typeobj); print('" class="lien">Acheter</a></div></td>');
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
