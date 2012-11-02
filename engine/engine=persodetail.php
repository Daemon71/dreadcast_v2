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
			G&eacute;rer votre personnel
		</div>
		<b class="module4ie"><a href="engine=personnel.php" class="module4"><img src="im_objets/icon_retour.gif" alt="Retour" />Retour</a></b>
</p>
	</div>
</div>
<div id="centre">
<p>

<?php 

$db = mysql_connect('localhost', 'CENSURE', 'CENSURE') or die("Erreur de connexion au serveur des messages.");
mysql_select_db('CENSURE',$db) or die("Erreur de connexion a la base de donnees des messages.");

$sql = 'SELECT type,entreprise,points FROM principal_tbl WHERE id= "'.$_SESSION['id'].'"' ;
$req = mysql_query($sql);
$_SESSION['poste'] = mysql_result($req,0,type); 
$_SESSION['entreprise'] = mysql_result($req,0,entreprise); 
$_SESSION['points'] = mysql_result($req,0,points); 

if($_SESSION['points']!=999) { print('<meta http-equiv="refresh" content="0 ; url=http://v2.dreadcast.net/ingame/engine.php"> '); exit(); }

$idi = $_GET['id'];
$_SESSION['entreprise'] = str_replace("%20"," ",''.$_GET['ent'].'');

$sql = 'SELECT bdd FROM `e_'.str_replace(" ","_",''.$_SESSION['entreprise'].'').'_tbl` WHERE type= "chef"' ;
$req = mysql_query($sql);
$bddp = mysql_result($req,0,bdd); 

$sql = 'SELECT * FROM `e_'.str_replace(" ","_",''.$_SESSION['entreprise'].'').'_tbl` WHERE id= "'.$idi.'"' ;
$req = mysql_query($sql);
$res = mysql_num_rows($req);

for($i=0; $i != $res ; $i++) 
	{
	$postei = mysql_result($req,$i,poste); 
	$typei = mysql_result($req,$i,type); 
	if(($typei=="hote") || ($typei=="medecin") || ($typei=="maintenance") || ($typei=="securite") || ($typei=="banquier") || ($typei=="serveur") || ($typei=="chercheur") || ($typei=="vendeur") || ($typei=="technicien"))
		{
		$salairei = mysql_result($req,$i,salaire);
		$bddi = mysql_result($req,$i,bdd); 
		$nbrepostesi = mysql_result($req,$i,nbrepostes); 
		$nbreactueli = mysql_result($req,$i,nbreactuel); 
		$mincompi = mysql_result($req,$i,mincomp); 
		$candidaturei = mysql_result($req,$i,candidature); 
		$mintravi = mysql_result($req,$i,mintrav); 
		$sinoni = mysql_result($req,$i,sinon); 
		$bonusi = mysql_result($req,$i,bonus);  
		print('<div class="ebarreliste">');
		print('<div align="center"><strong>'.ucwords($postei).' : </strong>');
		print(' <a href="engine=modif.php?modif=suppr&id='.$idi.'">Supprimer le poste</a>');
		print('</div><p align="center"><strong>Type de poste :</strong> '.ucwords($typei).' (Poste <a href="../info=passif.php" target="_blank">Passif</a>)</p>');
		print('<form name="sal'.$idi.'" method="post" action="engine=modif.php?modif=salaire&id='.$idi.'"><div align="center"><strong>Salaire : </strong><input name="salaire'.$idi.'" type="text" id="salaire'.$idi.'" value="'.$salairei.'" size="3" maxlength="3"> Cr&eacute;dits / Jour <input type="submit" name="Submit'.$idi.'" value="Ok"></div></form>');
		print('<form name="tps'.$idi.'" method="post" action="engine=modif.php?modif=temps&id='.$idi.'"><div align="center"><strong>Temps minimum : </strong><input name="tps'.$idi.'" type="text" id="tps'.$idi.'" value="'.$mintravi.'" size="3" maxlength="3"> Heure(s) / Jour <input type="submit" name="Submi'.$idi.'" value="Ok"></div></form>');
		if(($sinoni=="lj") || ($sinoni=="ls"))
			{
			print('<form name="sin'.$idi.'" method="post" action="engine=modif.php?modif=sinon&id='.$idi.'"><div align="center"><strong>Sinon : </strong><select name="select'.$idi.'"><option value="lj" selected>Licenci&eacute; en fin de journ&eacute;e</option><option value="pp">N\'est pas pay&eacute;</option><option value="ma">Envoi un message automatique</option></select><input type="submit" name="Submi'.$idi.'" value="Ok"></div></form>');
			}
		elseif($sinoni=="pp")
			{
			print('<form name="sin'.$idi.'" method="post" action="engine=modif.php?modif=sinon&id='.$idi.'"><div align="center"><strong>Sinon : </strong><select name="select'.$idi.'"><option value="lj">Licenci&eacute; en fin de journ&eacute;e</option><option value="pp" selected>N\'est pas pay&eacute;</option><option value="ma">Envoi un message automatique</option></select><input type="submit" name="Submi'.$idi.'" value="Ok"></div></form>');
			}
		elseif($sinoni=="ma")
			{
			print('<form name="sin'.$idi.'" method="post" action="engine=modif.php?modif=sinon&id='.$idi.'"><div align="center"><strong>Sinon : </strong><select name="select'.$idi.'"><option value="lj">Licenci&eacute; en fin de journ&eacute;e</option><option value="pp">N\'est pas pay&eacute;</option><option value="ma" selected>Envoi un message automatique</option></select><input type="submit" name="Submi'.$idi.'" value="Ok"></div></form>');
			}
		print('<form name="hs'.$idi.'" method="post" action="engine=modif.php?modif=hs&id='.$idi.'"><div align="center"><strong>Les heures sup sont pay&eacute;es : </strong><input name="hs'.$idi.'" type="text" id="hs'.$idi.'" value="'.$bonusi.'" size="3" maxlength="3"> Cr&eacute;dits / Heure <input type="submit" name="Sub'.$idi.'" value="Ok"></div></form>');
		print('<form name="np'.$idi.'" method="post" action="engine=modif.php?modif=np&id='.$idi.'"><div align="center"><strong>Nombre de places pour le poste : </strong><input name="np'.$idi.'" type="text" id="np'.$idi.'" value="'.$nbrepostesi.'" size="2" maxlength="5"><input type="submit" name="Sub'.$idi.'" value="Ok"></div></form>');
		print('<strong>Nombre de places occup&eacute;es :</strong> '.$nbreactueli.'');
		print('<form name="comp'.$idi.'" method="post" action="engine=modif.php?modif=comp&id='.$idi.'"><strong>');
		if($typei=="technicien")
			{
			print('Minimum requis en Mécanique : ');
			}
		elseif($typei=="hote")
			{
			print('Minimum requis en Service : ');
			}
		elseif($typei=="medecin")
			{
			print('Minimum requis en Médecine : ');
			}
		elseif($typei=="maintenance")
			{
			print('Minimum requis en Maintenance : ');
			}
		elseif($typei=="securite")
			{
			print('Minimum requis en Observation : ');
			}
		elseif($typei=="vendeur")
			{
			print('Minimum requis en Economie : ');
			}
		elseif($typei=="banquier")
			{
			print('Minimum requis en Economie : ');
			}
		elseif($typei=="serveur")
			{
			print('Minimum requis en Service : ');
			}
		print('</strong> <input name="comp'.$idi.'" type="text" id="comp'.$idi.'" value="'.$mincompi.'" size="3" maxlength="3"><input type="submit" name="Sub'.$idi.'" value="Ok"></form>');
		if($candidaturei=="can")
			{
			print('<form name="cand'.$idi.'" method="post" action="engine=modif.php?modif=cand&id='.$idi.'"><div align="center"><input type="checkbox" name="cand'.$idi.'" value="can" checked>Acc&eacute;der &agrave; ce poste n&eacute;c&eacute;ssite de d&eacute;poser une candidature.<input type="submit" name="Sub'.$idi.'" value="Ok"></div></form>');
			}
		else
			{
			print('<form name="cand'.$idi.'" method="post" action="engine=modif.php?modif=cand&id='.$idi.'"><div align="center"><input type="checkbox" name="cand'.$idi.'" value="can">Acc&eacute;der &agrave; ce poste n&eacute;c&eacute;ssite de d&eacute;poser une candidature.<input type="submit" name="Sub'.$idi.'" value="Ok"></div></form>');
			}
		}
	else
		{
		$salairei = mysql_result($req,$i,salaire);
		$bddi = mysql_result($req,$i,bdd); 
		$nbrepostesi = mysql_result($req,$i,nbrepostes); 
		$nbreactueli = mysql_result($req,$i,nbreactuel); 
		$mincompi = mysql_result($req,$i,mincomp); 
		$candidaturei = mysql_result($req,$i,candidature); 
		print('<div class="ebarreliste">');
		print('<div align="center"><strong>'.ucwords($postei).' : </strong>');
		if($typei!="chef")
			{
			print(' <a href="engine=modif.php?modif=suppr&id='.$idi.'">Supprimer le poste</a>');
			}
		print('</div><p align="center"><strong>Type de poste :</strong> '.ucwords($typei).' (Poste <a href="../info=actif.php" target="_blank">actif</a>)</p>');
		print('<form name="np'.$idi.'" method="post" action="engine=modif.php?modif=np&id='.$idi.'"><div align="center"><strong>Nombre de places pour le poste : </strong><input name="np'.$idi.'" type="text" id="np'.$idi.'" value="'.$nbrepostesi.'" size="1" maxlength="1"><input type="submit" name="Sub'.$idi.'" value="Ok"></div></form>');
		print('<strong>Nombre de places occup&eacute;es :</strong> '.$nbreactueli.'');
		if($typei!="chef")
			{
			print('<form name="sal'.$idi.'" method="post" action="engine=modif.php?modif=salaire&id='.$idi.'"><div align="center"><strong>Salaire : </strong><input name="salaire'.$idi.'" type="text" id="salaire'.$idi.'" value="'.$salairei.'" size="3" maxlength="3"> Cr&eacute;dits / Jour <input type="submit" name="Submit'.$idi.'" value="Ok"></div></form>');
			if($bddi!="")
				{
				print('<form name="bdd'.$idi.'" method="post" action="engine=modif.php?modif=bdd&id='.$idi.'"><div align="center"><input type="checkbox" name="bdd'.$idi.'" value="" checked>Ce poste &agrave; acc&egrave;s aux bases de donn&eacute;es.<input type="submit" name="Sub'.$idi.'" value="Ok"></div></form>');
				}
			else
				{
				print('<form name="bdd'.$idi.'" method="post" action="engine=modif.php?modif=bdd&id='.$idi.'"><div align="center"><input type="checkbox" name="bdd'.$idi.'" value="'.$bddp.'">Ce poste &agrave; acc&egrave;s aux bases de donn&eacute;es.<input type="submit" name="Sub'.$idi.'" value="Ok"></div></form>');
				}
			if($candidaturei=="can")
				{
				print('<form name="cand'.$idi.'" method="post" action="engine=modif.php?modif=cand&id='.$idi.'"><div align="center"><input type="checkbox" name="cand'.$idi.'" value="can" checked>Acc&eacute;der &agrave; ce poste n&eacute;c&eacute;ssite de d&eacute;poser une candidature.<input type="submit" name="Sub'.$idi.'" value="Ok"></div></form>');
				}
			else
				{
				print('<form name="cand'.$idi.'" method="post" action="engine=modif.php?modif=cand&id='.$idi.'"><div align="center"><input type="checkbox" name="cand'.$idi.'" value="can">Acc&eacute;der &agrave; ce poste n&eacute;c&eacute;ssite de d&eacute;poser une candidature.<input type="submit" name="Sub'.$idi.'" value="Ok"></div></form>');
				}
			}
		}

	$sql1 = 'SELECT id,points FROM principal_tbl WHERE entreprise="'.$_SESSION['entreprise'].'" AND type="'.$postei.'"' ;
	$req1 = mysql_query($sql1);
	$res1 = mysql_num_rows($req1);
	print('<br /><div align="center"><a href="engine=persoliste.php?id='.$idi.'&entreprise='.$_SESSION['entreprise'].'">Liste des personnes occupant le poste</a></div></div>');
	}

mysql_close($db);

?>

</p>
</div>
<?php if($chat=="oui") 	{ if ((date("H")>=21) || (date("H")<=7)) { include("inc_bas_n_c.php"); } else { include("inc_bas_j_c.php"); } } else {	if ((date("H")>=21) || (date("H")<=7)) { include("inc_bas_n.php"); } else { include("inc_bas_j.php"); }	} ?>
<?php include("inc_bas_de_page.php"); ?>
