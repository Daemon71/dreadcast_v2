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
		<b class="module4ie"><a href="engine=gestion.php" class="module4"><img src="im_objets/icon_retour.gif" alt="Retour" />Retour</a></b>
</p>
	</div>
</div>
<div id="centre">
<div class="messagesvip">

<?php 

$db = mysql_connect('localhost', 'CENSURE', 'CENSURE') or die("Erreur de connexion au serveur des messages.");
mysql_select_db('CENSURE',$db) or die("Erreur de connexion a la base de donnees des messages.");

$sql = 'SELECT type,entreprise,points FROM principal_tbl WHERE id= "'.$_SESSION['id'].'"' ;
$req = mysql_query($sql);
$_SESSION['poste'] = mysql_result($req,0,type); 
$_SESSION['entreprise'] = mysql_result($req,0,entreprise); 
$_SESSION['points'] = mysql_result($req,0,points); 

if($_SESSION['points']!=999 && $_SESSION['pseudo']!="Overflow") { print('<meta http-equiv="refresh" content="0 ; url=http://v2.dreadcast.net/ingame/engine.php"> '); exit(); }

$sql = 'SELECT id,poste,nbrepostes,nbreactuel FROM `e_'.str_replace(" ","_",''.$_SESSION['entreprise'].'').'_tbl`' ;
$req = mysql_query($sql);
$res = mysql_num_rows($req);

print('<table width="90%"  border="0"><tr><td><br />');

for($i=0; $i != $res ; $i++) 
	{
	$idi = mysql_result($req,$i,id); 
	$postei = mysql_result($req,$i,poste); 
	$nbreactueli = mysql_result($req,$i,nbreactuel); 
	$nbreplaces = mysql_result($req,$i,nbrepostes); 
	print('<div align="center"><a href="engine=persodetail.php?ent='.$_SESSION['entreprise'].'&id='.$idi.'">'.ucwords($postei).'</a> (Places occupées: '.$nbreactueli.'/'.$nbreplaces.')</div>');
	}
	
print('<p align="center"><a href="engine=creerposte.php">Créer un nouveau poste</p>');

print('</td></tr></table>');

$sql1 = 'SELECT * FROM candidatures_tbl WHERE entreprise= "'.$_SESSION['entreprise'].'"' ;
$req1 = mysql_query($sql1);
$res1 = mysql_num_rows($req1);

$nbp = 0;

for($i=0; $i != $res1 ; $i++) 
	{ 
	$idm = mysql_result($req1,$i,id);
	$nom = mysql_result($req1,$i,nom);
	$sql2 = 'SELECT id FROM principal_tbl WHERE pseudo= "'.$nom.'"' ;
	$req2 = mysql_query($sql2);
	$res2 = mysql_num_rows($req2);
	if($res2>0)
		{
		$idcm = mysql_result($req2,0,id);
		$sql2 = 'SELECT entreprise FROM principal_tbl WHERE id= "'.$idcm.'"' ;
		$req2 = mysql_query($sql2);
		$entcm = mysql_result($req2,0,entreprise);
		if($entcm=="Aucune")
			{
			$nbp = $nbp + 1;
			}
		}
	}

if(($res1!=0) && ($nbp>0))
	{
	print('<div class="barrecand"><table width="90%"  border="1" align="center" cellpadding="0" cellspacing="0">
				<tr bgcolor="#B6B6B6">
				  <th scope="col"><div align="center">Candidature de</div></th>
				  <th scope="col"><div align="center">Poste désiré</div></th>
				  <th scope="col"><div align="center">Compétences</div></th>
				  <th scope="col"><div align="center">&nbsp;</div></th>
				</tr>');
	
	for($i=0; $i != $res1 ; $i++) 
		{ 
		$idm = mysql_result($req1,$i,id);
		$nom = mysql_result($req1,$i,nom);
		$sql2 = 'SELECT id FROM principal_tbl WHERE pseudo= "'.$nom.'"' ;
		$req2 = mysql_query($sql2);
		$idcm = mysql_result($req2,0,id);
		$sql2 = 'SELECT entreprise FROM principal_tbl WHERE id= "'.$idcm.'"' ;
		$req2 = mysql_query($sql2);
		$entcm = mysql_result($req2,0,entreprise);
		if($entcm=="Aucune")
			{
			$poste = mysql_result($req1,$i,poste);
			print('<tr>
					  <td><div align="center">'.$nom.'</div></td>
					  <td><div align="center"><a href="engine=voircandidature.php?id='.$idm.'">'.$poste.'</a></div></td>
					  <td><div align="center">');
						$sql = 'SELECT id FROM principal_tbl WHERE pseudo= "'.$nom.'"' ;
						$req = mysql_query($sql);
						$idc = mysql_result($req,0,id);
						$sql = 'SELECT combat,observation,gestion,maintenance,mecanique,service,discretion,economie,resistance,tir,vol,medecine FROM principal_tbl WHERE id= "'.$idc.'"' ;
						$req = mysql_query($sql);
						$combatc = mysql_result($req,0,combat);
						$observationc = mysql_result($req,0,observation);
						$gestionc = mysql_result($req,0,gestion);
						$maintenancec = mysql_result($req,0,maintenance);
						$mecaniquec = mysql_result($req,0,mecanique);
						$servicec = mysql_result($req,0,service);
						$discretionc = mysql_result($req,0,discretion);
						$economiec = mysql_result($req,0,economie);
						$resistancec = mysql_result($req,0,resistance);
						$tirc = mysql_result($req,0,tir);
						$volc = mysql_result($req,0,vol);
						$medecinec = mysql_result($req,0,medecine);
						if($observationc==100)
							{
							print('<img src="im_objets/etoile.gif" border="0" title="Expert en observation">');
							}
						if($maintenancec==100)
							{
							print('<img src="im_objets/etoile.gif" border="0" title="Expert en maintenance">');
							}
						if($gestionc==100)
							{
							print('<img src="im_objets/etoile.gif" border="0" title="Expert en gestion">');
							}
						if($mecaniquec==100)
							{
							print('<img src="im_objets/etoile.gif" border="0" title="Expert en mécanique">');
							}
						if($discretionc==100)
							{
							print('<img src="im_objets/etoile.gif" border="0" title="Expert en discretion">');
							}
						if($servicec==100)
							{
							print('<img src="im_objets/etoile.gif" border="0" title="Expert en service">');
							}
						if($economiec==100)
							{
							print('<img src="im_objets/etoile.gif" border="0" title="Expert en economie">');
							}
						if($resistancec==100)
							{
							print('<img src="im_objets/etoile.gif" border="0" title="Expert en resistance">');
							}
						if($tirc==100)
							{
							print('<img src="im_objets/etoile.gif" border="0" title="Expert en tir">');
							}
						if($volc==100)
							{
							print('<img src="im_objets/etoile.gif" border="0" title="Expert en vol">');
							}
						if($combatc==100)
							{
							print('<img src="im_objets/etoile.gif" border="0" title="Expert en combat">');
							}
						if($medecinec==100)
							{
							print('<img src="im_objets/etoile.gif" border="0" title="Expert en médecine">');
							}
						if(($combatc>=40) && ($combatc!=100))
							{
							print('<img src="im_objets/etoilebleu.gif" border="0" title="Initié en combat">');
							}
						if(($observationc>=40) && ($observationc!=100))
							{
							print('<img src="im_objets/etoilebleu.gif" border="0" title="Initié en observation">');
							}
						if(($gestionc>=40) && ($gestionc!=100))
							{
							print('<img src="im_objets/etoilebleu.gif" border="0" title="Initié en gestion">');
							}
						if(($maintenancec>=40) && ($maintenancec!=100))
							{
							print('<img src="im_objets/etoilebleu.gif" border="0" title="Initié en maintenance">');
							}
						if(($servicec>=40) && ($servicec!=100))
							{
							print('<img src="im_objets/etoilebleu.gif" border="0" title="Initié en service">');
							}
						if(($discretionc>=40) && ($discretionc!=100))
							{
							print('<img src="im_objets/etoilebleu.gif" border="0" title="Initié en discretion">');
							}
						if(($economiec>=40) && ($economiec!=100))
							{
							print('<img src="im_objets/etoilebleu.gif" border="0" title="Initié en economie">');
							}
						if(($resistancec>=40) && ($resistancec!=100))
							{
							print('<img src="im_objets/etoilebleu.gif" border="0" title="Initié en resistance">');
							}
						if(($tirc>=40) && ($tirc!=100))
							{
							print('<img src="im_objets/etoilebleu.gif" border="0" title="Initié en tir">');
							}
						if(($volc>=40) && ($volc!=100))
							{
							print('<img src="im_objets/etoilebleu.gif" border="0" title="Initié en vol">');
							}
						if(($medecinec>=40) && ($medecinec!=100))
							{
							print('<img src="im_objets/etoilebleu.gif" border="0" title="Initié en médecine">');
							}
						if(($mecaniquec>=40) && ($mecaniquec!=100))
							{
							print('<img src="im_objets/etoilebleu.gif" border="0" title="Initié en mécanique">');
							}
					  print('</div></td>
					  <td><div align="center"><a href="engine=supprcand.php?'.$idm.'"><img src="im_objets/poubelle.gif" border="0"></a></div></td>
					</tr>');
			}
		}
	print('</table></div>');
	}
else
	{
	print("<i>Il n'y a aucune candidature.</i>");
	}

mysql_close($db);

?>
</div>
</div>
<?php if($chat=="oui") 	{ if ((date("H")>=21) || (date("H")<=7)) { include("inc_bas_n_c.php"); } else { include("inc_bas_j_c.php"); } } else {	if ((date("H")>=21) || (date("H")<=7)) { include("inc_bas_n.php"); } else { include("inc_bas_j.php"); }	} ?>
<?php include("inc_bas_de_page.php"); ?>
