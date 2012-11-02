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

$sql = 'SELECT type,budget FROM entreprises_tbl WHERE nom= "'.$_SESSION['entreprise'].'"' ;
$req = mysql_query($sql);
$type = mysql_result($req,0,type); 
$budget = mysql_result($req,0,budget); 

if($_SESSION['bdd']=="") { print('<meta http-equiv="refresh" content="0 ; url=http://v2.dreadcast.net/ingame/engine.php"> '); exit(); }

mysql_close($db);
?>
<?php if ((date("H")>=21) || (date("H")<=7)) { include("inc_haut_n.php"); } else { include("inc_haut_j.php");} ?>

<div id="haut">
	<?php include("inc_entete.php"); ?>
	<div class="haut_aligngauche_haut">
		<p>
		<div class="titrepage">
			Stocks de l'entreprise<br />
			<strong>Capital :</strong><em> <?php print(''.$budget.''); ?> Crédits</em>
		</div>
		<b class="module4ie"><a href="engine=stocks.php" class="module4"><img src="im_objets/icon_retour.gif" alt="Retour" />Retour</a></b>
		</p>
	</div>
</div>
<div id="centre">
<p>

<?php 

$db = mysql_connect('localhost', 'CENSURE', 'CENSURE') or die("Erreur de connexion au serveur des messages.");
mysql_select_db('CENSURE',$db) or die("Erreur de connexion a la base de donnees des messages.");

$objet = str_replace("%20"," ",''.$_SERVER['QUERY_STRING'].'');

$sql = 'SELECT * FROM stocks_tbl WHERE entreprise="'.$_SESSION['entreprise'].'" AND objet= "'.$objet.'"' ;
$req = mysql_query($sql);
$res = mysql_num_rows($req);

if($res!=0)
	{
	$idi = mysql_result($req,0,id); 
	$objet = mysql_result($req,0,objet); 
	$nombre = mysql_result($req,0,nombre);
	$pvente = mysql_result($req,0,pvente);	
	$sql1 = 'SELECT image,prix,prod FROM objets_tbl WHERE nom="'.$objet.'"' ;
	$req1 = mysql_query($sql1);
	$res1 = mysql_num_rows($req1);
	if($objet=="Soins")
		{
		print('<p align="center"><strong>Soigner un point de vie</strong></p>');
		}
	elseif($res1!=0)
		{
		$image = mysql_result($req1,0,image); 
		$prix = mysql_result($req1,0,prix); 
		$prod = mysql_result($req1,0,prod); 
		if($type=="usine de production")
			{
			$prixs = $prix * 10;
			$nombres = $nombre / 10;
			print('<p align="center"><strong>'.$objet.'</strong><br>(Cliquez sur l\'objet pour obtenir des informations)</p> <p align="center"><a href="http://v2.dreadcast.net/info=objet.php?'.strtolower($objet).'" target="_blank"><img border="0" src="http://v2.dreadcast.net/ingame/im_objets/'.$image.'"></a></p><p align="center"><strong>Prix de fabrication :</strong> <em>'.$prixs.' Cr&eacute;dits / Stock de 10</em> <br><strong>Nombre de stocks :</strong> <em>'.$nombres.'</em></p>');
			if($prod==1)
				{
				print('<hr><p align="center"><a href="engine=stocksfinished.php?'.$objet.'">Fabriquer un stock de 10 unités</a><br />');
				}
			if($nombres>=1)
				{
				print('<a href="engine=vendreconf.php?'.$objet.'">Vendre un stock au prix de production</a></p>');
				}
			}
		elseif($type=="centre de recherche")
			{
			print('<p align="center"><strong>'.$objet.'</strong><br>(Cliquez sur l\'objet pour obtenir des informations)</p> <p align="center"><a href="http://v2.dreadcast.net/info=objet.php?'.strtolower($objet).'" target="_blank"><img border="0" src="http://v2.dreadcast.net/ingame/im_objets/'.$image.'"></a></p><p align="center"><strong>Prix de fabrication :</strong> <em>'.$prix.' Cr&eacute;dits / Production</em> <br><strong>Nombre d\'objets en vente :</strong> <em>'.$nombre.'</em></p>');
			if($prod==1)
				{
				print('<hr><p align="center"><a href="engine=stocksfinished.php?'.$objet.'">Fabriquer un objet</a><br />');
				}
			}
		elseif($type=="agence immobiliaire")
			{
			print('<p align="center"><strong>'.$objet.'</strong><br>(Cliquez sur le logement pour obtenir des informations)</p> <p align="center"><a href="http://v2.dreadcast.net/info=objet.php?'.strtolower($objet).'" target="_blank"><img border="0" src="http://v2.dreadcast.net/ingame/im_objets/'.$image.'"></a></p><p align="center"><strong>Prix de construction :</strong> <em>'.$prix.' Cr&eacute;dits / Logement</em> <br><strong>Nombre en stocks :</strong> <em>'.$nombre.'</em></p>');
			if($prod==1)
				{
				print('<hr><p align="center"><a href="engine=stocksfinished.php?'.$objet.'">Construire un '.$objet.'</a><br />');
				}
			if($nombre>=1)
				{
				print('<a href="engine=vendreconf.php?'.$objet.'">Vendre un logement au prix de production</a></p>');
				}
			}
		elseif($type=="bar cafe")
			{
			$prixs = $prix * 10;
			print('<p align="center"><strong>'.$objet.'</strong><br>(Cliquez sur l\'objet pour obtenir des informations)</p> <p align="center"><a href="http://v2.dreadcast.net/info=objet.php?'.strtolower($objet).'" target="_blank"><img border="0" src="http://v2.dreadcast.net/ingame/im_objets/'.$image.'"></a></p><p align="center"><strong>Prix des ingrédients :</strong> <em>'.$prixs.' Cr&eacute;dits / 10 Consomations</em> <br><strong>Assez d\'ingrédients en stocks pour faire :</strong> <em>'.$nombre.' Consomations</em></p>');
			print('<hr><p align="center"><a href="engine=stocksfinished.php?'.$objet.'">Acheter de quoi faire 10 consomations</a></p>');
			}
		elseif($type=="hopital")
			{
			$prixs = $prix * 5;
			$nombres = $nombre / 5;
			print('<p align="center"><strong>'.$objet.'</strong><br>(Cliquez sur l\'objet pour obtenir des informations)</p> <p align="center"><a href="http://v2.dreadcast.net/info=objet.php?'.strtolower($objet).'" target="_blank"><img border="0" src="http://v2.dreadcast.net/ingame/im_objets/'.$image.'"></a></p><p align="center"><strong>Prix de fabrication :</strong> <em>'.$prixs.' Cr&eacute;dits / Stock de 5</em> <br><strong>Nombre de stocks :</strong> <em>'.$nombres.'</em></p>');
			print('<hr><p align="center"><a href="engine=stocksfinished.php?'.$objet.'">Fabriquer un stock de 5 unités</a></p>');
			}
		else
			{
			print('<p align="center"><strong>'.$objet.'</strong><br>(Cliquez sur l\'objet pour obtenir des informations)</p> <p align="center"><a href="http://v2.dreadcast.net/info=objet.php?'.strtolower($objet).'" target="_blank"><img border="0" src="http://v2.dreadcast.net/ingame/im_objets/'.$image.'"></a></p><p align="center"><strong>Prix de fabrication en usine :</strong> <em>'.$prix.' Cr&eacute;dits / Pi&egrave;ce</em> <br><strong>Nombre en stocks :</strong> <em>'.$nombre.'</em></p>');
			}
		}
	else
		{
		print('<meta http-equiv="refresh" content="0 ; url=http://v2.dreadcast.net/ingame/engine=stocks.php"> ');
		exit();
		}
	if($type=="usine de production")
		{
		$pventes = $pvente * 10;
		print('<hr><form name="form1" method="post" action="engine=prixfinished.php?'.$objet.'">
			  <p align="center"><strong>Votre prix de vente aux boutiques :</strong>
				<input name="pvente" type="text" value="'.$pventes.'" size="6"> 
				<em>Cr&eacute;dits / Stock</em> <br>
				<input type="submit" name="Submit" value="Valider">
			  </p>
			</form>');
		}
	elseif($type=="bar cafe")
		{
		print('<hr><form name="form1" method="post" action="engine=prixfinished.php?'.$objet.'">
			  <p align="center"><strong>Votre prix de vente au public :</strong>
				<input name="pvente" type="text" value="'.$pvente.'" size="6"> 
				<em>Cr&eacute;dits / Consomation</em> <br>
				<input type="submit" name="Submit" value="Valider">
			  </p>
			</form>');
		}
	else
		{
		print('<hr><form name="form1" method="post" action="engine=prixfinished.php?'.$objet.'">
			  <p align="center"><strong>Votre prix de vente au public :</strong>
				<input name="pvente" type="text" value="'.$pvente.'" size="6"> 
				<em>Cr&eacute;dits / Pi&egrave;ce</em> <br>
				<input type="submit" name="Submit" value="Valider">
			  </p>
			</form>');
		}
	}
else
	{
	print('<meta http-equiv="refresh" content="0 ; url=http://v2.dreadcast.net/ingame/engine=stocks.php"> ');
	}

mysql_close($db);

?>

</p>
</div>
<?php if($chat=="oui") 	{ if ((date("H")>=21) || (date("H")<=7)) { include("inc_bas_n_c.php"); } else { include("inc_bas_j_c.php"); } } else {	if ((date("H")>=21) || (date("H")<=7)) { include("inc_bas_n.php"); } else { include("inc_bas_j.php"); }	} ?>
<?php include("inc_bas_de_page.php"); ?>
