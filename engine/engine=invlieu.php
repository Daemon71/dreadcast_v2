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
$_SESSION['faim'] = mysql_result($req,0,faim);
$_SESSION['soif'] = mysql_result($req,0,soif);

$sql = 'SELECT nom,code,camera,repos,frigo,niveaufrigo FROM lieu_tbl WHERE num= "'.$_SESSION['num'].'" AND rue= "'.$_SESSION['lieu'].'"' ;
$req = mysql_query($sql);
$res = mysql_num_rows($req);
if($res)
	{
	$camera = mysql_result($req,0,camera); 
	$boncode = mysql_result($req,0,code); 
	$repos = mysql_result($req,0,repos); 
	$type = mysql_result($req,0,nom);
	$frigo = mysql_result($req,0,frigo);
	$niveaufrigo = mysql_result($req,0,niveaufrigo);
	
	if(ereg("Local",''.$type.''))
		{
		$sql = 'SELECT logo FROM entreprises_tbl WHERE num= "'.$_SESSION['num'].'" AND rue= "'.$_SESSION['lieu'].'"' ;
		$req = mysql_query($sql);
		$res = mysql_num_rows($req);
		if($res>0)
			{
			$logo = mysql_result($req,0,logo); 
			$iop = "0";
			}
		else
			{
			$sql = 'SELECT logo FROM cerclesliste_tbl WHERE num= "'.$_SESSION['num'].'" AND rue= "'.$_SESSION['lieu'].'"' ;
			$req = mysql_query($sql);
			$logo = mysql_result($req,0,logo); 
			$iop = "0";
			}
		}
	elseif($type == "special")
		{
		$sql = 'SELECT logo FROM lieux_speciaux_tbl WHERE num= "'.$_SESSION['num'].'" AND rue= "'.$_SESSION['lieu'].'"' ;
		$req = mysql_query($sql);
		$res = mysql_num_rows($req);
		if($res>0)
			{
			$logo = mysql_result($req,0,logo); 
			$iop = "0";
			}
		}
	elseif($type!="Rue" && $type!="Ruelle")
		{
		$sql = 'SELECT image FROM objets_tbl WHERE nom= "'.$type.'"' ;
		$req = mysql_query($sql);
		$logo = mysql_result($req,0,image); 
		$iop = "1";
		}
	
	$act = "trans";
	}

mysql_close($db);
?>
<?php if ((date("H")>=21) || (date("H")<=7)) { include("inc_haut_n.php"); } else { include("inc_haut_j.php");} ?>

<div id="haut">
	<?php include("inc_entete.php"); ?>
	<div class="haut_aligngauche">
		<p>
		<div class="titrepage">
			Inventaire du lieu
		</div>
		</p>
	</div>
</div>
<div id="centre">
<p>

<?php 
if($_SESSION['lieu']=="Rue" || $_SESSION['lieu']=="Ruelle" || $_SESSION['num'] < 0) 
	{ 
	print("Il n'y a pas d'inventaire dans la rue."); 
	}  
?>
    <div id="colonnegauche">
<?php

if($_SESSION['num']>0)
	{
	if($iop=="1")
		{
		print('					<img id="imgfrigo" border="0" src="http://v2.dreadcast.net/ingame/im_objets/'.$logo.'" />
		');
		}
	else
		{
		print('					<img id="imgfrigo" border="1px" width="100px" height="100px" src="'.$logo.'" />
		');
		}
	}

?>
				<div <?php
		
				if($_SESSION['code']==$boncode && $frigo!="Non" && $_SESSION['num']>0)
				{
					$typefrigo=($frigo=="Oui")?1:2;
					print('id="frigo'.$typefrigo.'">
							<div id="barrevert" style="background:white url(im_objets/barrevert.gif) 0  '.(100-(2/$typefrigo)*$niveaufrigo).'px no-repeat;"></div>');
					if($niveaufrigo>0&&($_SESSION['faim']<100||$_SESSION['soif']<100))print('<a href="engine=senourrir.php">Se nourrir</a>');
				}
				else print('>')?>
				</div>
	</div>
	<div id="colonnedroite">
				<table width="100%"  border="0" cellpadding="3" cellspacing="5">
					<tr>
<?php 

$db = mysql_connect('localhost', 'CENSURE', 'CENSURE') or die("Erreur de connexion au serveur des messages.");
mysql_select_db('CENSURE',$db) or die("Erreur de connexion a la base de donnees des messages.");

if(($_SESSION['lieu']=="Rue") || ($_SESSION['lieu']=="Ruelle") || $_SESSION['num'] < 0)
	{
		print('<td></td>');
	}
elseif($_SESSION['code']==$boncode)
	{
	$sqlt = 'SELECT nom FROM invlieu_tbl WHERE num= "'.$_SESSION['num'].'" AND rue= "'.$_SESSION['lieu'].'"' ;
	$reqt = mysql_query($sqlt);
	$rest = mysql_num_rows($reqt);
	if($repos==10)
		{
		$repos = 12;
		}
	for($i=1; $i != ceil($repos/2) + 1 ; $i++) 
		{
		print('
						<td>
							<div align="center">');
		
		if($rest>$i-1)
			{
			$objet = mysql_result($reqt,$i-1,nom); 
			$sql = 'SELECT image,url FROM objets_tbl WHERE nom= "'.$objet.'"' ;
			$req = mysql_query($sql);
			$image = mysql_result($req,0,image); 
			$url = mysql_result($req,0,url); 
			}
		else
			{
			$objet = "vide"; 
			$image = "vide.jpg"; 
			$url = ".php"; 
			}
		
		if($act=="trans")
			{
			if($objet=="Machine")
				{
				print('
								<p align="center">
									<a href="engine=lieu-inv.php?'.$i.'"><img border="0" src="http://v2.dreadcast.net/ingame/im_objets/machine.gif" /></a>
								</p>');
				}
			elseif($objet!="vide")
				{
				print('
								<p align="center">
									<a href="engine=lieu-inv.php?'.$i.'"><img border="0" src="http://v2.dreadcast.net/ingame/im_objets/'.$image.'" /></a>
								</p>');
				}
			else
				{
				print('
								<p align="center"><img border="0" src="http://v2.dreadcast.net/ingame/im_objets/'.$image.'" /></p>');
				}
			}
		print('
							</div>
						</td>');
		
		if($i==3)
			{
			print('
					</tr>
					<tr>');
			}	
		}
	}
elseif($_SESSION['code']!=$boncode)
	{
	print('<div align="center"><form action="engine=di.php" name="form1"><p align="center">'); print("<em>Vous devez entrer un digicode valide pour pouvoir accéder à l'inventaire du lieu.</em>"); print(' </p><p align="center">Digicode : <input name="codetest" type="password" id="codetest" size="'.strlen($boncode).'" maxlength="'.strlen($boncode).'"></p><p align="center"><input type="submit" name="Submit" value="Valider"></p></form></div>');
	}

mysql_close($db);
?>
					</tr>
				</table>
				<div align="center">
				<?php 
				if(($_SESSION['code']==$boncode) && $_SESSION['num'] > 0)
					{
					print('
									Cliquez sur un objet pour le transférer vers votre inventaire personnel.
								');
					}
				?>
				</div>
    </div>
</p>
</div>
<?php if($chat=="oui") 	{ if ((date("H")>=21) || (date("H")<=7)) { include("inc_bas_n_c.php"); } else { include("inc_bas_j_c.php"); } } else {	if ((date("H")>=21) || (date("H")<=7)) { include("inc_bas_n.php"); } else { include("inc_bas_j.php"); }	} ?>
<?php include("inc_bas_de_page.php"); ?>
