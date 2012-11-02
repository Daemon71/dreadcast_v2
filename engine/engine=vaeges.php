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

$sqlv = 'SELECT id FROM vente_tbl WHERE vendeur= "'.$_SESSION['pseudo'].'"' ;
$reqv = mysql_query($sqlv);
$resv = mysql_num_rows($reqv);
if($resv==0)
	{
	print('<meta http-equiv="refresh" content="0 ; url=http://v2.dreadcast.net/ingame/engine=vae.php"> ');
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
			Hall des ench&egrave;res 
		</div>
		<b class="module4ie"><a href="engine.php" class="module4"><img src="im_objets/icon_retour.gif" alt="Retour" />Retour</a></b>
		</p>
	</div>
</div>
<div id="centre_encheres">

<p id="location2"><a href="engine=vaedeposer.php">D&eacute;poser un objet</a> | <a href="engine=vae.php">Consulter les ventes</a></p>

<br /><br /><br /><div id="boutique">

<?php                  
$db = mysql_connect('localhost', 'CENSURE', 'CENSURE') or die("Erreur de connexion au serveur des messages.");
mysql_select_db('CENSURE',$db) or die("Erreur de connexion a la base de donnees des messages.");

$sql = 'SELECT * FROM vente_tbl WHERE vendeur= "'.$_SESSION['pseudo'].'"' ;
$req = mysql_query($sql);
$res = mysql_num_rows($req);

print('<table cellpadding="0" cellspacing="0">');
for($i=0 ; $i != $res ; $i++)
	{
		$monobj=mysql_result($req,$i,objet);
		if(ereg("-",$monobj) && $monobj!="Permis 9mm Semi-Auto" && $monobj!="Laissez-passer" && !ereg('Feuille',$monobj) && !ereg('Recueil',$monobj))
		{
			list($monobj,$idobj)=explode("-", $monobj);
		}
		
		if($monobj[0]=="F"&&$monobj[1]=="e"&&$monobj[2]=="u"&&$monobj[3]=="i"&&$monobj[4]=="l"&&$monobj[5]=="l"&&$monobj[6]=="e") $monobj="Feuille de papier libre";
		if($monobj[0]=="R"&&$monobj[1]=="e"&&$monobj[2]=="c"&&$monobj[3]=="u"&&$monobj[4]=="e"&&$monobj[5]=="i"&&$monobj[6]=="l") $monobj="Recueil de signatures";
		
		if($monobj != "")
			{		
			$sqlo = 'SELECT * FROM objets_tbl WHERE nom= "'.$monobj.'"' ;
			$reqo = mysql_query($sqlo);
			$reso = mysql_num_rows($reqo);
			if($reso != 0)
				{
				$image = mysql_result($reqo,0,image);
				$typeo = mysql_result($reqo,0,type);
				$puissancemin = mysql_result($reqo,0,puissance);
				$puissancemax = mysql_result($reqo,0,puissance) + mysql_result($reqo,0,ecart);
				$distance = mysql_result($reqo,0,distance);
				$texte = mysql_result($reqo,0,infos);
				$mode = mysql_result($reqo,0,modes);
				}
			else print('Erreur pour l\'objet "'.$monobj.'". Veuillez contacter un administrateur.');
			}
		
		if($i/2 == round($i/2))
			{
			print('<tr class="color1" onmouseover="affiche_art(\'article_'.$i.'\',true);" onmouseout="affiche_art(\'article_'.$i.'\',false);">');
			}
		else
			{
			print('<tr class="color2" onmouseover="affiche_art(\'article_'.$i.'\',true);" onmouseout="affiche_art(\'article_'.$i.'\',false);">');
			}
		print('
		<div id="article_'.$i.'" style="display:none;position:absolute;left:-300px;top:1px;width:311px;padding:0;text-align:justify;color:white;"  onmouseover="affiche_art(\'article_'.$i.'\',true);" onmouseout="affiche_art(\'article_'.$i.'\',false);">');
		print('<div id="article_haut"></div>');
		print('<div id="article_milieu"><p>');
		if($typeo=="armesav"||($typeo=="armestir"&&$monobj!="Chargeur"))
		{
			$sqlspe = 'SELECT usure FROM armes_tbl WHERE idarme= "-'.$idobj.'"' ;
			$reqspe = mysql_query($sqlspe);
			print('ID de l\'arme : '.$idobj);
			print('<br />Usure de l\'arme : '.mysql_result($reqspe,0,usure).'/100<br /><br />');
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
		print('</div>
		<div id="article_bas"></div></div>
		<td style="padding:5px 0px 5px 0;"><div align="center"><a href="http://v2.dreadcast.net/info=objet.php?'.mysql_result($req,$i,objet).'" target="_blank"><img src="im_objets/'.$image.'" border="0" height="35" width="35"></a></td></div>');
		print('<td><div align="center"><strong>'.$monobj.'</strong><br />');
		print('</div></td>');
		if(mysql_result($req,$i,fin)!=0)
			{
			if(mysql_result($req,$i,acheteur)!="")
				{
				print('<td><div align="center"><strong>Enchère actuelle : </strong><em>'.mysql_result($req,$i,enchere).' Cr </em><br />');
				}
			else
				{
				print('<td><div align="center"><em>Il n\'y a pas d\'enchère pour '.mysql_result($req,$i,enchere).' Cr</em><br />');
				}
			if(mysql_result($req,$i,buyout)!=0)
				{
				print('<strong>Achat : </strong><em>'.mysql_result($req,$i,buyout).' Cr </em><br />');
				}
			print('<em>Il reste '.mysql_result($req,$i,fin).' Heures </em></div></td>');
			print('<td style="padding-right:10px;"><div align="center"><a href="engine=vaedel.php?id='.mysql_result($req,$i,id).'" class="lien">Annuler</a></div></td>');
			}
		else
			{
			print('<td><div align="center"><em>Vous n\'avez pas vendu cet objet</em><br />');
			print('<td style="padding-right:10px;"><div align="center"><a href="engine=vaedel.php?id='.mysql_result($req,$i,id).'" class="lien">Retirer</a></div></td>');
			}
		print('</tr>');
	}
print('</table>');


mysql_close($db);

?>

</div>
</div>
<?php if($chat=="oui") 	{ if ((date("H")>=21) || (date("H")<=7)) { include("inc_bas_n_c.php"); } else { include("inc_bas_j_c.php"); } } else {	if ((date("H")>=21) || (date("H")<=7)) { include("inc_bas_n.php"); } else { include("inc_bas_j.php"); }	} ?>
<?php include("inc_bas_de_page.php"); ?>
