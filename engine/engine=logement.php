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
$_SESSION['alim'] = mysql_result($req,0,alim);
$_SESSION['sante'] = mysql_result($req,0,sante);
$_SESSION['drogue'] = mysql_result($req,0,drogue);
$_SESSION['fatigue'] = mysql_result($req,0,fatigue);
$_SESSION['lieu'] = strtolower($_SESSION['rue']);
$_SESSION['lieu'] = ucwords($_SESSION['lieu']);

$sql = 'SELECT ruel,numl FROM principal_tbl WHERE id= "'.$_SESSION['id'].'"' ;
$req = mysql_query($sql);
$_SESSION['ruea'] = mysql_result($req,0,ruel); 
$_SESSION['numa'] = mysql_result($req,0,numl); 

if(($_SESSION['numa']!=0) && ($_SESSION['ruea']!="Aucune"))
	{
	$sql = 'SELECT * FROM lieu_tbl WHERE rue= "'.$_SESSION['ruea'].'" AND num="'.$_SESSION['numa'].'"' ;
	$req = mysql_query($sql);
	$_SESSION['nom'] = mysql_result($req,0,nom); 
	$_SESSION['digi'] = mysql_result($req,0,code); 
	$_SESSION['frigo'] = mysql_result($req,0,frigo); 
	$_SESSION['camera'] = mysql_result($req,0,camera); 
	$_SESSION['prix'] = mysql_result($req,0,prix); 
	$_SESSION['repos'] = mysql_result($req,0,repos); 
	$_SESSION['chat'] = mysql_result($req,0,chat); 
	$sql = 'SELECT image FROM objets_tbl WHERE nom="'.$_SESSION['nom'].'"' ;
	$req = mysql_query($sql);
	$image = mysql_result($req,0,image); 
	}

mysql_close($db);
?>
<?php if ((date("H")>=21) || (date("H")<=7)) { include("inc_haut_n.php"); } else { include("inc_haut_j.php");} ?>

<div id="haut">
	<?php include("inc_entete.php"); ?>
	<div class="haut_aligngauche">
		<p>
		<div class="titrepage">
			Votre logement
		</div>
		</p>
	</div>
</div>
<div id="centre">
<p>

<?php 
$db = mysql_connect('localhost', 'CENSURE', 'CENSURE') or die("Erreur de connexion au serveur des messages.");
mysql_select_db('CENSURE',$db) or die("Erreur de connexion a la base de donnees des messages.");

$sqll2 = 'SELECT * FROM proprietaire_tbl WHERE pseudo= "'.$_SESSION['pseudo'].'" AND rue= "'.$_SESSION['ruea'].'" AND num= "'.$_SESSION['numa'].'"' ;
$reql2 = mysql_query($sqll2);
$resl2 = mysql_num_rows($reql2);

if($resl2==0)
	{
	$sqll = 'SELECT location FROM proprietaire_tbl WHERE rue= "'.$_SESSION['ruea'].'" AND num= "'.$_SESSION['numa'].'"' ;
	$reql = mysql_query($sqll);
	$resl = mysql_num_rows($reql);
	if($resl!=0)
		{
		$location = mysql_result($reql,0,location);
		}
	}

$sqll = 'SELECT * FROM proprietaire_tbl WHERE pseudo= "'.$_SESSION['pseudo'].'"' ;
$reql = mysql_query($sqll);
$resl = mysql_num_rows($reql);

if(($_SESSION['ruea']!="Aucune") || ($resl!=0))
	{
	if($resl>0)
		{
		print('<form name="form1" method="post" action=""><p align="center"><strong>Sélectionner un logement :</strong> <select onChange="MM_jumpMenu('); print("'parent'"); print(',this,0)" name="recherche" id="select2">');
		if($_GET['rue']!="")
			{
			for( $l = 0 ; $l != $resl ; $l++ )
				{
				if((ucwords($_GET['rue'])==ucwords(mysql_result($reql,$l,rue))) && ($_GET['num']==mysql_result($reql,$l,num)))
					{
					print('<option value="#" selected>'.$_GET['num'].' '.$_GET['rue'].'</option>');
					$bonl = 1;
					}
				else
					{
					print('<option value="engine=logement.php?rue='.mysql_result($reql,$l,rue).'&num='.mysql_result($reql,$l,num).'">'.mysql_result($reql,$l,num).' '.mysql_result($reql,$l,rue).'</option>');
					}
				}
			}
		else
			{
			$sqll = 'SELECT * FROM proprietaire_tbl WHERE pseudo= "'.$_SESSION['pseudo'].'"' ;
			$reql = mysql_query($sqll);
			$resl = mysql_num_rows($reql);
			print('<option value="#" selected></option>');
			for($l=0;$l!=$resl;$l++)
				{
				print('<option value="engine=logement.php?rue='.mysql_result($reql,$l,rue).'&num='.mysql_result($reql,$l,num).'">'.mysql_result($reql,$l,num).' '.mysql_result($reql,$l,rue).'</option>');
				}
			}
		print('</select></p></form>');
		}
	if(($_SESSION['ruea']=="Aucune") && ($_GET['rue']==""))
		{
		print('<p align="center"><strong>Vous n\'avez pas de logement principal !<br />Veuillez en définir un parmis ceux de la liste.</strong></p>');
		}
	elseif(($_GET['rue']=="") || ((ucwords($_GET['rue'])==ucwords($_SESSION['ruea'])) && ($_GET['num']==$_SESSION['numa'])))
		{
		print('<table width="97%"  border="0" align="center"><tr><td width="25%"><p align="center"><img src="im_objets/'.$image.'" border="0"></p></td><td width="75%">');
		print('<div align="center"><strong>Informations g&eacute;n&eacute;rales : </strong></div>');
		if($_SESSION['nom']=="Villa et piscine")
			{
			print('<p align="center"><strong>Type de logement  : </strong><i>'.$_SESSION['nom'].'</i><br>');
			}
		else
			{
			print('<p align="center"><strong>Type de logement  : </strong><i>'.$_SESSION['nom'].'²</i><br>');
			}
		print('<strong>Adresse : </strong><i>'.$_SESSION['numa'].' '.ucwords(strtolower($_SESSION['ruea'])).'</i><br>');
		print('<strong>Prix : </strong>');
		if($location==0)
			{
			print('<i>Vous &ecirc;tes propri&eacute;taire</i><br>');
			}
		else
			{
			print('<i>'.$location.' Cr&eacute;dits / Jour</i><br>');
			}
		print('<strong>Confort : </strong>');
		if($_SESSION['repos']<5)
			{
			print('<i>Pr&eacute;caire</i><br>');
			}
		elseif($_SESSION['repos']==5)
			{
			print('<i>Modeste</i><br>');
			}
		elseif($_SESSION['repos']==6)
			{
			print('<i>Agr&eacute;able &agrave; vivre</i><br>');
			}
		elseif($_SESSION['repos']<9)
			{
			print('<i>Spacieux</i><br>');
			}
		elseif($_SESSION['repos']==9)
			{
			print('<i>Luxueux</i><br>');
			}
		elseif($_SESSION['repos']==10)
			{
			print('<i>Maximum</i><br>');
			}
		$emplacements = ceil($_SESSION['repos'] / 2);
		if($_SESSION['nom']=="Villa et piscine") { $emplacements = 6; }
		print('<strong>Emplacements d\'inventaire : </strong><i>'.$emplacements.'</i><br>');
		print('<p align="center"><strong>Options :</strong></p>');
		
		if($_SESSION['digi']!=0)
			{
			print('<p align="center"><strong>Digicode  : </strong><i>'.$_SESSION['digi'].'</i> - <a href="engine=changercode.php">Changer de digicode</a><br>');
			}
		else
			{
			print('<p align="center"><strong><i>Vous n\'avez pas de digicode.</strong></i><br>');
			}
		if($_SESSION['camera']=="Oui")
			{
			print('<strong>Caméra  : </strong> <i>De surveillance</i> (<a href="engine=desinstallcam.php?rue='.$_SESSION['ruea'].'&num='.$_SESSION['numa'].'">Désinstaller</a>)<br>');
			}
		elseif($_SESSION['camera']=="Pol")
			{
			print('<strong>Caméra  : </strong> <i>De police</i> (<a href="engine=desinstallcam.php?rue='.$_SESSION['ruea'].'&num='.$_SESSION['numa'].'">Désinstaller</a>)<br>');
			}
		else
			{
			print('<strong><i>Vous n\'avez pas de caméra.</strong></i><br>');
			}
		if($_SESSION['frigo']=="Oui")
			{
			print('<strong>Refrigerateur  : </strong> <i>Oui</i> (<a href="engine=desinstallfrigo.php?rue='.$_SESSION['ruea'].'&num='.$_SESSION['numa'].'">Désinstaller</a>)<br>');
			}
		elseif($_SESSION['frigo']=="Ame")
			{
			print('<strong>Refrigerateur  : </strong> <i>Américain</i> (<a href="engine=desinstallfrigo.php?rue='.$_SESSION['ruea'].'&num='.$_SESSION['numa'].'">Désinstaller</a>)<br>');
			}
		else
			{
			print('<strong><i>Vous n\'avez pas de refrigerateur.</strong></i><br>');
			}
		if($_SESSION['chat']=="oui")
			{
			print('<strong>Salon de discussion : </strong> <i>Oui</i></p>');
			}
		else
			{
			print('<a href="engine=acheterchatconf.php">Acheter un salon de discussion</a> (700 Crédits)</p>');
			}
		print('</td></tr></table>');
		if((ucwords($_SESSION['rue'])!=ucwords($_SESSION['ruea'])) || ($_SESSION['num']!=$_SESSION['numa']))
			{
			print('<div align="center"><a href="engine=go.php?num='.$_SESSION['numa'].'&rue='.$_SESSION['ruea'].'">Rentrer chez vous</a></div>');	
			}
		else
			{
			print('<div align="center">Vous êtes actuellement chez vous.</div>');	
			}
		print('<div align="center">Ce logement est défini comme votre habitation principale.</div>');	
		}
	elseif($bonl==1)
		{
		$sqll = 'SELECT * FROM lieu_tbl WHERE rue= "'.$_GET['rue'].'" AND num="'.$_GET['num'].'"' ;
		$reql = mysql_query($sqll);
		$noml = mysql_result($reql,0,nom); 
		$digil = mysql_result($reql,0,code); 
		$cameral = mysql_result($reql,0,camera); 
		$frigol = mysql_result($reql,0,frigo); 
		$prixl = mysql_result($reql,0,prix); 
		$reposl = mysql_result($reql,0,repos); 
		$chatl = mysql_result($reql,0,chat); 
		$sqll = 'SELECT image FROM objets_tbl WHERE nom="'.$noml.'"' ;
		$reql = mysql_query($sqll);
		$imagel = mysql_result($reql,0,image); 
		print('<table width="97%"  border="0" align="center"><tr><td width="25%"><p align="center"><img src="im_objets/'.$imagel.'" border="0"></p></td><td width="75%">');
		print('<p align="center"><strong>Informations g&eacute;n&eacute;rales : </strong></p>');
		if($noml=="Villa et piscine")
			{
			print('<p align="center"><strong>Type de logement  : </strong><i>'.$noml.'</i><br>');
			}
		else
			{
			print('<p align="center"><strong>Type de logement  : </strong><i>'.$noml.'²</i><br>');
			}
		print('<strong>Adresse : </strong><i>'.$_GET['num'].' '.ucwords(strtolower($_GET['rue'])).'</i><br>');
		print('<strong>Prix : </strong>');
		if($location==0)
			{
			print('<i>Vous &ecirc;tes propri&eacute;taire</i><br>');
			}
		else
			{
			print('<i>'.$location.' Cr&eacute;dits / Jour</i><br>');
			}
		print('<strong>Confort : </strong>');
		if($reposl<5)
			{
			print('<i>Pr&eacute;caire</i><br>');
			}
		elseif($reposl==5)
			{
			print('<i>Modeste</i><br>');
			}
		elseif($reposl==6)
			{
			print('<i>Agr&eacute;able &agrave; vivre</i><br>');
			}
		elseif($reposl<9)
			{
			print('<i>Spacieux</i><br>');
			}
		elseif($reposl==9)
			{
			print('<i>Luxueux</i><br>');
			}
		elseif($reposl==10)
			{
			print('<i>Maximum</i><br>');
			}
		$emplacements = ceil($reposl / 2);
		if($noml=="Villa et piscine") { $emplacements = 6; }
		print('<strong>Emplacements d\'inventaire : </strong><i>'.$emplacements.'</i><br>');
		print('<p align="center"><strong>Options :</strong></p>');
		
		if($digil!=0)
			{
			print('<p align="center"><strong>Digicode  : </strong><i>'.$digil.'</i> - <a href="engine=changercodeautrelo.php?num='.$_GET['num'].'&rue='.$_GET['rue'].'">Changer de digicode</a><br>');
			}
		else
			{
			print('<p align="center"><strong><i>Vous n\'avez pas de digicode.</strong></i><br>');
			}
		if($cameral=="Oui")
			{
			print('<strong>Caméra  : </strong> <i>De surveillance</i> (<a href="engine=desinstallcam.php?rue='.$_GET['rue'].'&num='.$_GET['num'].'">Désinstaller</a>)<br>');
			}
		elseif($cameral=="Pol")
			{
			print('<strong>Caméra  : </strong> <i>De police</i> (<a href="engine=desinstallcam.php?rue='.$_GET['rue'].'&num='.$_GET['num'].'">Désinstaller</a>)<br>');
			}
		else
			{
			print('<strong><i>Vous n\'avez pas de caméra.</strong></i><br>');
			}
		if($frigol=="Oui")
			{
			print('<strong>Refrigerateur  : </strong> <i>Oui</i> (<a href="engine=desinstallfrigo.php?rue='.$_GET['rue'].'&num='.$_GET['num'].'">Désinstaller</a>)<br>');
			}
		elseif($frigol=="Ame")
			{
			print('<strong>Refrigerateur  : </strong> <i>Américain</i> (<a href="engine=desinstallfrigo.php?rue='.$_GET['rue'].'&num='.$_GET['num'].'">Désinstaller</a>)<br>');
			}
		else
			{
			print('<strong><i>Vous n\'avez pas de refrigerateur.</strong></i><br>');
			}
		if($chatl=="oui")
			{
			print('<strong>Salon de discussion : </strong> <i>Oui</i></p>');
			}
		else
			{
			print('<a href="engine=acheterchatconfautrelo.php?num='.$_GET['num'].'&rue='.$_GET['rue'].'">Acheter un salon de discussion</a> (700 Crédits)</p>');
			}
		print('</td></tr></table>');
		if(($_SESSION['rue']!=$_GET['rue']) || ($_SESSION['num']!=$_GET['num']))
			{
			print('<div align="center"><a href="engine=go.php?num='.$_GET['num'].'&rue='.$_GET['rue'].'">Vous rendre à ce logement</a></div>');	
			}
		else
			{
			print('<div align="center">Vous êtes actuellement à ce logement.</div>');	
			}
		$sqll = 'SELECT * FROM proprietaire_tbl WHERE pseudo= "'.$_SESSION['pseudo'].'" AND rue= "'.$_GET['rue'].'" AND num= "'.$_GET['num'].'"' ;
		$reql = mysql_query($sqll);
		if(mysql_result($reql,0,location)==0)
			{
			print('<div align="center"><a href="engine=setlogement.php?num='.$_GET['num'].'&rue='.$_GET['rue'].'">Faire de ce logement votre habitation principale</a></div>');	
			print('<div align="center"><a href="engine=setlocation.php?num='.$_GET['num'].'&rue='.$_GET['rue'].'">Placer ce logement en location</a></div>');	
			}
		else
			{
			print('<div align="center">Ce logement est en location (<a href="engine=consultlocation.php?num='.$_GET['num'].'&rue='.$_GET['rue'].'">Consulter les informations</a>)</div>');	
			}
		}
	}
elseif($_SESSION['ruea']=="Aucune")
	{
	print('<p align="center"><strong>Vous n\'avez pas de logement.</strong></p>');
	print('<p align="center">Vous avez la possibilité d\'en chercher un dans une <a href="engine=recherche.php?agence immobiliaire">agence immobilière</a>.</p>'); 
	}

mysql_close($db);
?>

</p>
</div>
<?php if($chat=="oui") 	{ if ((date("H")>=21) || (date("H")<=7)) { include("inc_bas_n_c.php"); } else { include("inc_bas_j_c.php"); } } else {	if ((date("H")>=21) || (date("H")<=7)) { include("inc_bas_n.php"); } else { include("inc_bas_j.php"); }	} ?>
<?php include("inc_bas_de_page.php"); ?>
