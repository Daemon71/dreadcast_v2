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

$sql = 'SELECT nom,type,ouvert FROM entreprises_tbl WHERE rue= "'.$_SESSION['lieu'].'" AND num= "'.$_SESSION['num'].'"' ;
$req = mysql_query($sql);
$type = mysql_result($req,0,type);
$noment = mysql_result($req,0,nom);
$ouvert = mysql_result($req,0,ouvert);

if($type!="ventes aux encheres")
	{
	print('<meta http-equiv="refresh" content="0 ; url=http://v2.dreadcast.net/ingame/engine.php"> ');
	exit();
	}

$sql = 'SELECT nombre FROM stocks_tbl WHERE entreprise= "'.$noment.'"' ;
$req = mysql_query($sql);
$pourc = mysql_result($req,0,nombre);

$sqlv = 'SELECT id FROM vente_tbl WHERE vendeur= "'.$_SESSION['pseudo'].'"' ;
$reqv = mysql_query($sqlv);
$resv = mysql_num_rows($reqv);

$sql = 'SELECT credits FROM principal_tbl WHERE id= "'.$_SESSION['id'].'"' ;
$req = mysql_query($sql);
$_SESSION['credits'] = mysql_result($req,0,credits);

mysql_close($db);

if(empty($_GET['typeobj']))
{
	if(empty($_POST['typeobj'])) $typeobj="tous";
	else $typeobj=$_POST['typeobj'];;
}
else $typeobj=htmlentities($_GET['typeobj']);
?>
<?php if ((date("H")>=21) || (date("H")<=7)) { include("inc_haut_n.php"); } else { include("inc_haut_j.php");} ?>

<div id="haut">
	<?php include("inc_entete.php"); ?>
	<div class="haut_aligngauche">
		<p>
		<div class="titrepage">
			Hall des ench&egrave;res 
		</div>
		<b class="module4ie"><a href="engine.php" class="module4"><img src="im_objets/icon_retour.gif" alt="Retour" />Retour</a></b>
		</p>
	</div>
</div>
<div id="centre_encheres">
<p id="selectionner">
	<form action="engine=vae.php" method="post" name="btype" id="leform">
		<select name="typeobj" id="leselect" onchange="submit();">
			<option value="tous"<?php if($typeobj=="tous")print(' selected="selected"'); ?>>Tous type d'objet</option>
			<?php 
			$db = mysql_connect('localhost', 'CENSURE', 'CENSURE') or die("Erreur de connexion au serveur des messages.");
			mysql_select_db('CENSURE',$db) or die("Erreur de connexion a la base de donnees des messages.");

			$sql = 'SELECT * FROM vente_tbl WHERE enchere!= "0"' ;
			$req = mysql_query($sql);
			$res = mysql_num_rows($req);
			
			$tab=array();
			
			for($i=0 ; $i != $res ; $i++)
				{
				if(mysql_result($req,$i,fin)>0)
					{
					$sqlo = 'SELECT type FROM objets_tbl WHERE nom= "'.mysql_result($req,$i,objet).'"' ;
					$reqo = mysql_query($sqlo);
					$tab[$i]=mysql_result($reqo,0,type);
					
					if($tab[$i]=="acac")$tab[$i]="Armes de corps &agrave; corps";
					elseif($tab[$i]=="armestir"&&mysql_result($req,$i,objet)=="Chargeur")$tab[$i]="Mat&eacute;riel";
					elseif($tab[$i]=="modif")$tab[$i]="Mat&eacute;riel";
					elseif($tab[$i]=="armestir")$tab[$i]="Armes de tir";
					elseif($tab[$i]=="armesprim")$tab[$i]="Armes primitives";
					elseif($tab[$i]=="armesav")$tab[$i]="Armes avanc&eacute;es";
					elseif($tab[$i]=="alimentation")$tab[$i]="Nourriture";
					elseif($tab[$i]=="boissons")$tab[$i]="Boissons";
					elseif($tab[$i]=="jag")$tab[$i]="Jeux &agrave; gratter";
					elseif($tab[$i]=="oa"||$tab[$i]=="aucun")$tab[$i]="Objets avanc&eacute;s";
					elseif($tab[$i]=="objet")$tab[$i]="Objets courants";
					elseif($tab[$i]=="om")$tab[$i]="Objets High-Tech";
					elseif($tab[$i]=="soie"||$tab[$i]=="cristal"||$tab[$i]=="tissu")$tab[$i]="V&ecirc;tements";
					elseif($tab[$i]=="oc"||$tab[$i]=="pharmacie")$tab[$i]="Pharmacie";
					elseif($tab[$i]=="permist"||$tab[$i]=="permisc")$tab[$i]="Permis";
					elseif($tab[$i]=="tracte")$tab[$i]="Tracte";
					elseif($tab[$i]=="imp"||$tab[$i]=="feuille")$tab[$i]="Divers";
					elseif($tab[$i]=="deck")$tab[$i]="Decks";
					elseif($tab[$i]=="armtu"||$tab[$i]=="armcu"||$tab[$i]=="vetu"||$tab[$i]=="obju")$tab[$i]="Objets uniques";
					elseif($tab[$i]=="sac")$tab[$i]="Conteneurs";
					elseif($tab[$i]=="ouu")$tab[$i]="Objets &agrave; usage unique";
					else;
					}
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

$sql = 'SELECT * FROM vente_tbl WHERE enchere != "0"' ;
$req = mysql_query($sql);
$res = mysql_num_rows($req);

print('<table cellpadding="0" cellspacing="0">');
$k=0;
for($i=0 ; $i != $res ; $i++)
	{
	if(mysql_result($req,$i,fin)>0 AND mysql_result($req,$i,objet)!="")
		{
		$monobj=mysql_result($req,$i,objet);
		if(ereg("-",$monobj) && $monobj!="Permis 9mm Semi-Auto" && $monobj!="Laissez-passer" && !ereg('Feuille',$monobj) && !ereg('Recueil',$monobj))
		{
			list($monobj,$idobj)=explode("-", $monobj);
		}
		
		$sqlo = 'SELECT * FROM objets_tbl WHERE nom= "'.$monobj.'"' ;
		$reqo = mysql_query($sqlo);
		$reso = mysql_num_rows($reqo);
		if($reso != 0)
			{
			$idobjet = mysql_result($reqo,0,id);
			$image = mysql_result($reqo,0,image);
			$typeo = mysql_result($reqo,0,type);
			$puissancemin = mysql_result($reqo,0,puissance);
			$puissancemax = mysql_result($reqo,0,puissance) + mysql_result($reqo,0,ecart);
			$distance = mysql_result($reqo,0,distance);
			$texte = mysql_result($reqo,0,infos);
			$mode = mysql_result($reqo,0,modes);
			}
	
		if($typeo=="acac")$temp="Armes de corps &agrave; corps";
		elseif($typeo=="armestir"&&mysql_result($req,$i,objet)=="Chargeur")$temp="Mat&eacute;riel";
		elseif($typeo=="modif")$temp="Mat&eacute;riel";
		elseif($typeo=="armestir")$temp="Armes de tir";
		elseif($typeo=="armesprim")$temp="Armes primitives";
		elseif($typeo=="armesav")$temp="Armes avanc&eacute;es";
		elseif($typeo=="alimentation")$temp="Nourriture";
		elseif($typeo=="boissons")$temp="Boissons";
		elseif($typeo=="jag")$temp="Jeux &agrave; gratter";
		elseif($typeo=="oa"||$typeo=="aucun")$temp="Objets avanc&eacute;s";
		elseif($typeo=="objet")$temp="Objets courants";
		elseif($typeo=="om")$temp="Objets High-Tech";
		elseif($typeo=="soie"||$typeo=="cristal"||$typeo=="tissu")$temp="V&ecirc;tements";
		elseif($typeo=="oc"||$typeo=="pharmacie")$temp="Pharmacie";
		elseif($typeo=="permist"||$typeo=="permisc")$temp="Permis";
		elseif($typeo=="tracte")$temp="Tracte";
		elseif($typeo=="imp"||$typeo=="feuille")$temp="Divers";
		elseif($typeo=="deck")$temp="Decks";
		elseif($typeo=="armtu"||$typeo=="armcu"||$typeo=="vetu"||$typeo=="obju")$temp="Objets uniques";
		elseif($typeo=="sac")$temp="Conteneurs";
		elseif($typeo=="ouu")$temp="Objets &agrave; usage unique";
		else;

		if((str_replace(" ","_",$temp)==$typeobj)||$typeobj=="tous")
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
		if(($typeo=="armestir"&&$monobj!="Chargeur")||$typeo=="armesav"||$typeo=="armtu")
		{
			$sqlspe = 'SELECT usure,modif1,modif2,modif3 FROM armes_tbl WHERE idarme= "-'.$idobj.'"' ;
			$reqspe = mysql_query($sqlspe);
			
			$modif1 = mysql_result($reqspe,0,modif1);
			$modif2 = mysql_result($reqspe,0,modif2);
			$modif3 = mysql_result($reqspe,0,modif3);
			
			if($modif1==0) $modif1 = "Aucune";
			elseif($modif1==1) $modif1 = " - Viseur laser (+15 tir)";
			elseif($modif1==2) $modif1 = " - Crosse (+5 tir)";
			elseif($modif1==3) $modif1 = " - Lunette (port&eacute;e +10m)";
			elseif($modif1==4) $modif1 = " - Chargeur HC (chargeur x2)";
			elseif($modif1==5) $modif1 = " - Silencieux (+10 discr&eacute;tion)";
			elseif($modif1==6) $modif1 = " - Alliage titane (durabilit&eacute; x2)";
			elseif($modif1==7) $modif1 = " - Vision thermique (+5 observation)";
			
			if($modif1!="Aucune")
				{
				if($modif2==0) $modif2 = "Aucune";
				elseif($modif2==1) $modif2 = "Viseur laser (+15 tir)";
				elseif($modif2==2) $modif2 = "Crosse (+5 tir)";
				elseif($modif2==3) $modif2 = "Lunette (port&eacute;e +10m)";
				elseif($modif2==4) $modif2 = "Chargeur HC (chargeur x2)";
				elseif($modif2==5) $modif2 = "Silencieux (+10 discr&eacute;tion)";
				elseif($modif2==6) $modif2 = "Alliage titane (durabilit&eacute; x2)";
				elseif($modif2==7) $modif2 = "Vision thermique (+5 observation)";
				
				if($modif2!="Aucune")
					{
					if($modif3==0) $modif3 = "Aucune";
					elseif($modif3==1) $modif3 = "Viseur laser (+15 tir)";
					elseif($modif3==2) $modif3 = "Crosse (+5 tir)";
					elseif($modif3==3) $modif3 = "Lunette (port&eacute;e +10m)";
					elseif($modif3==4) $modif3 = "Chargeur HC (chargeur x2)";
					elseif($modif3==5) $modif3 = "Silencieux (+10 discr&eacute;tion)";
					elseif($modif3==6) $modif3 = "Alliage titane (durabilit&eacute; x2)";
					elseif($modif3==7) $modif3 = "Vision thermique (+5 observation)";
					}
				}
			
			$modifications = $modif1;
			if($modif1!="Aucune" && $modif2!="Aucune")
				{
				$modifications .= '<br /> - '.$modif2;
				if($modif3!="Aucune") $modifications .= '<br /> - '.$modif3;
				}
			
			print('ID de l\'arme : '.$idobj);
			print('<br />Usure de l\'arme : '.mysql_result($reqspe,0,usure).'/100');
			print('<br />Modification(s) de l\'arme :<br />'.$modifications.'<br /><br />');
		}
		($texte=="")?print('Texte à venir'):print($texte);
		if(($typeo=="armestir"&&mysql_result($req,$i,objet)!="Chargeur")||$typeo=="armesav"||$typeo=="armesprim"||$typeo=="acac")
		{
		print('<br /><br />Dégats : <em>'.$puissancemin.' - '.$puissancemax.'</em><br />Portée : '.$distance.'m');
		if(($typeo=="armestir"&&mysql_result($req,$i,objet)!="Chargeur")||$typeo=="armesav")
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
		}
		elseif($typeo=="alimentation")
		{
			print('<br /><br />Faim +'.$puissancemin);
		}
		elseif($typeo=="boissons")
		{
			print('<br /><br />Forme +'.$puissancemin.'<br />Soif +'.$puissancemin*10);
		}
		elseif($typeo=="armcu" OR $typeo=="armtu" OR $typeo=="vetu" OR $typeo=="obju")
		{
			$sqlhop = 'SELECT nature,bonus FROM recherche_effets_tbl WHERE ido='.$idobjet.'' ;
			$reqhop = mysql_query($sqlhop);
			$reshop = mysql_num_rows($reqhop);
			
			if($reshop!=0) print('<br /><br />Bonus :<br />');
			
			for($j=0;$j<$reshop;$j++)
				{
				$nature = mysql_result($reqhop,$j,nature);
				
				if($nature=="invisibilite") $nature="invisibilit&eacute;";
				elseif($nature=="medecine") $nature="m&eacute;decine";
				elseif($nature=="discretion") $nature="discr&eacute;tion";
				elseif($nature=="resistance") $nature="r&eacute;sistance";
				
				if(mysql_result($reqhop,$j,bonus)!=0) print(ucwords($nature).' +'.mysql_result($reqhop,$j,bonus).'<br />');
			    else print(ucwords($nature).'<br />');
				}
		}
		elseif($typeo=="sac")
			{
			$sqlspe = 'SELECT emplacement FROM sacs_tbl WHERE ido= "-'.$idobj.'"' ;
			$reqspe = mysql_query($sqlspe);
			$resspe = mysql_num_rows($reqspe);
			
			if($resspe!=0)
				{
				print('<br /><br />Cet objet contient :<br />');
				for($c=0;$c<$resspe;$c++) print(' - '.mysql_result($reqspe,$c,emplacement).'<br />');
				}
			else print('<br /><br />Cet objet est vide.');
			}
		print('</div>
		<div id="article_bas"></div></div>
		<td style="padding:5px 0 5px 0;"><div align="center"><a href="http://v2.dreadcast.net/info=objet.php?'.mysql_result($req,$i,objet).'" target="_blank"><img src="im_objets/'.$image.'" '); if(ereg("centre/",$iac)) print('width="33" height="33" style="border:1px solid black;"'); else print('border="0"'); print(' height="35" width="35"></a></td></div>');
		print('<td><div align="center"><strong>'.$monobj.'</strong><br />');
		print('</div></td>');
		print('<td><div align="center"><strong>Enchère : </strong><em>'.mysql_result($req,$i,enchere).' Cr </em><br />');
		if(mysql_result($req,$i,buyout)!=0)
			{
			$buy = mysql_result($req,$i,buyout) + ceil(($pourc/100)*mysql_result($req,$i,buyout));
			print('<strong>Achat : </strong><em>'.$buy.' Cr </em><br />');
			}
		print('<em>Il reste '.mysql_result($req,$i,fin).' Heures </em></div></td>');
		$enchere = mysql_result($req,$i,enchere) + ceil(mysql_result($req,$i,enchere) * 0.05);
		if(mysql_result($req,$i,vendeur)==$_SESSION['pseudo'])
			{
			print('<td><div align="center">Vous êtes vendeur<br />');
			}
		elseif(mysql_result($req,$i,acheteur)!=$_SESSION['pseudo'])
			{
			print('<td><div align="center"><a href="engine=vaeenchere.php?id='.mysql_result($req,$i,id).'" class="lien">Encherir à '.$enchere.' Crédits</a><br />');
			}
		else
			{
			print('<td><div align="center">Vous tenez l\'enchère<br />');
			}
		if((mysql_result($req,$i,buyout)!=0) && (mysql_result($req,$i,vendeur)!=$_SESSION['pseudo']))
			{
			print('<a href="engine=vaeachat.php?id='.mysql_result($req,$i,id).'" class="lien">Acheter</a></div></td>');
			}
		print('</tr>');
		}
		}
	}
print('</table>');

print('<meta http-equiv="refresh" content="20 ; url=engine=vae.php?typeobj='.$typeobj.'">');

mysql_close($db);

?>

</div>
</div>
<?php if($chat=="oui") 	{ if ((date("H")>=21) || (date("H")<=7)) { include("inc_bas_n_c.php"); } else { include("inc_bas_j_c.php"); } } else {	if ((date("H")>=21) || (date("H")<=7)) { include("inc_bas_n.php"); } else { include("inc_bas_j.php"); }	} ?>
<?php include("inc_bas_de_page.php"); ?>
