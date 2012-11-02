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
			Production de l'entreprise
		</div>
		<b class="module4ie"><a href="engine=production.php" class="module4"><img src="im_objets/icon_retour.gif" alt="Retour" />Retour</a></b>
<div align="center"><br /><strong>Capital :</strong><em> <?php print(''.$budget.''); ?> Crédits</em></div>
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

if($res==0)
	{
	$sql1 = 'SELECT image,prix,prod FROM objets_tbl WHERE nom="'.$objet.'"' ;
	$req1 = mysql_query($sql1);
	$res1 = mysql_num_rows($req1);
	if($res1!=0)
		{
		$image = mysql_result($req1,0,image); 
		$prix = mysql_result($req1,0,prix); 
		$prod = mysql_result($req1,0,prod); 
		if($type=="usine de production")
			{
			$prixs = $prix * 10;
			$nombres = $nombre / 10;
			print('<p align="center"><strong>'.$objet.'</strong><br>(Cliquez sur l\'objet pour obtenir des informations)</p> <p align="center"><a href="../info=objet.php?'.strtolower($objet).'" target="_blank"><img border="0" src="http://v2.dreadcast.net/im_objets/'.$image.'"></a></p><p align="center"><strong>Prix de fabrication :</strong> <em>'.$prixs.' Cr&eacute;dits / Stock de 10</em> </p>');
			if($prod==1)
				{
				print('<hr><p align="center"><a href="engine=prodfinished.php?'.$objet.'">Fabriquer un stock de 10 unités</a></p>');
				}
			else
				{
				print('<hr><p align="center">Impossible de produire ce type d\'objet.</p>');
				}
			}
		elseif($type=="agence immobiliaire")
			{
			print('<p align="center"><strong>'.$objet.'</strong><br>(Cliquez sur le logement pour obtenir des informations)</p> <p align="center"><a href="../info=objet.php?'.strtolower($objet).'" target="_blank"><img border="0" src="http://v2.dreadcast.net/im_objets/'.$image.'"></a></p><p align="center"><strong>Prix de construction :</strong> <em>'.$prix.' Cr&eacute;dits / Logement</em> </p>');
			if($prod==1)
				{
				print('<hr><p align="center"><a href="engine=prodfinished.php?'.$objet.'">Construire un '.$objet.'</a></p>');
				}
			else
				{
				print('<hr><p align="center">Impossible de produire ce type de bâtiment.</p>');
				}
			}
		elseif($type=="bar cafe")
			{
			$prixs = $prix * 10;
			print('<p align="center"><strong>'.$objet.'</strong><br>(Cliquez sur l\'objet pour obtenir des informations)</p> <p align="center"><a href="../info=objet.php?'.strtolower($objet).'" target="_blank"><img border="0" src="http://v2.dreadcast.net/im_objets/'.$image.'"></a></p><p align="center"><strong>Prix des ingrédients :</strong> <em>'.$prixs.' Cr&eacute;dits / 10 Consomations</em> </p>');
			if($prod==1)
				{
				print('<hr><p align="center"><a href="engine=prodfinished.php?'.$objet.'">Acheter de quoi faire 10 consomations</a></p>');
				}
			else
				{
				print('<hr><p align="center">Impossible de produire ce type de consomation.</p>');
				}
			}
		}
	else
		{
		print('<meta http-equiv="refresh" content="0 ; url=http://v2.dreadcast.net/ingame/engine=stocks.php"> ');
		exit();
		}
	}
else
	{
	print('<meta http-equiv="refresh" content="0 ; url=http://v2.dreadcast.net/ingame/engine=stocks.php"> ');
	exit();
	}

mysql_close($db);

?>

</p>
</div>
<?php if($chat=="oui") 	{ if ((date("H")>=21) || (date("H")<=7)) { include("inc_bas_n_c.php"); } else { include("inc_bas_j_c.php"); } } else {	if ((date("H")>=21) || (date("H")<=7)) { include("inc_bas_n.php"); } else { include("inc_bas_j.php"); }	} ?>
<?php include("inc_bas_de_page.php"); ?>
