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
$_SESSION['drogue'] = mysql_result($req,0,drogue);
$_SESSION['sante'] = mysql_result($req,0,sante);
$santemax = mysql_result($req,0,sante_max);
$_SESSION['fatigue'] = mysql_result($req,0,fatigue);
$_SESSION['fatigue_max'] = mysql_result($req,0,fatigue_max);
$_SESSION['combat'] = mysql_result($req,0,combat);
$_SESSION['observation'] = mysql_result($req,0,observation); 
$_SESSION['gestion'] = mysql_result($req,0,gestion); 
$_SESSION['maintenance'] = mysql_result($req,0,maintenance); 
$_SESSION['mecanique'] = mysql_result($req,0,mecanique); 
$_SESSION['service'] = mysql_result($req,0,service); 
$_SESSION['discretion'] = mysql_result($req,0,discretion); 
$_SESSION['economie'] = mysql_result($req,0,economie); 
$_SESSION['resistance'] = mysql_result($req,0,resistance); 
$_SESSION['tir'] = mysql_result($req,0,tir); 
$_SESSION['vol'] = mysql_result($req,0,vol); 
$_SESSION['medecine'] = mysql_result($req,0,medecine); 
$_SESSION['informatique'] = mysql_result($req,0,informatique); 
$_SESSION['recherche'] = mysql_result($req,0,recherche);
$_SESSION['fidelite'] = mysql_result($req,0,fidelite); 
$_SESSION['combat_max'] = mysql_result($req,0,combat_max);
$_SESSION['observation_max'] = mysql_result($req,0,observation_max); 
$_SESSION['gestion_max'] = mysql_result($req,0,gestion_max); 
$_SESSION['maintenance_max'] = mysql_result($req,0,maintenance_max); 
$_SESSION['mecanique_max'] = mysql_result($req,0,mecanique_max); 
$_SESSION['service_max'] = mysql_result($req,0,service_max); 
$_SESSION['discretion_max'] = mysql_result($req,0,discretion_max); 
$_SESSION['economie_max'] = mysql_result($req,0,economie_max); 
$_SESSION['resistance_max'] = mysql_result($req,0,resistance_max); 
$_SESSION['tir_max'] = mysql_result($req,0,tir_max); 
$_SESSION['vol_max'] = mysql_result($req,0,vol_max); 
$_SESSION['medecine_max'] = mysql_result($req,0,medecine_max); 
$_SESSION['informatique_max'] = mysql_result($req,0,informatique_max); 
$_SESSION['recherche_max'] = mysql_result($req,0,recherche_max);
$_SESSION['fidelite_max'] = mysql_result($req,0,fidelite_max); 
$_SESSION['lieu'] = strtolower($_SESSION['rue']);
$_SESSION['lieu'] = ucwords($_SESSION['lieu']);

mysql_close($db);
?>
<?php if ((date("H")>=21) || (date("H")<=7)) { include("inc_haut_n.php"); } else { include("inc_haut_j.php");} ?>

<div id="haut">
	<?php include("inc_entete.php"); ?>
	<div class="haut_aligngauche">
		<p>
		<div class="titrepage">
			Consommation
		</div>
		<b class="module4ie"><a href="engine=stats.php" class="module4"><img src="im_objets/icon_retour.gif" alt="Retour" />Retour</a></b>
</p>
	</div>
</div>
<div id="centre">
<p>

<?php 

$db = mysql_connect('localhost', 'CENSURE', 'CENSURE') or die("Erreur de connexion au serveur des messages.");
mysql_select_db('CENSURE',$db) or die("Erreur de connexion a la base de donnees des messages.");

$sql = 'SELECT case1,case2,case3,case4,case5,case6 FROM principal_tbl WHERE id= "'.$_SESSION['id'].'"' ;
$req = mysql_query($sql);
$_SESSION['case1'] = mysql_result($req,0,case1);
$_SESSION['case2'] = mysql_result($req,0,case2);
$_SESSION['case3'] = mysql_result($req,0,case3);
$_SESSION['case4'] = mysql_result($req,0,case4);
$_SESSION['case5'] = mysql_result($req,0,case5);
$_SESSION['case6'] = mysql_result($req,0,case6);

$objet_v = str_replace("%20"," ",$_SERVER['QUERY_STRING']);

if($_SESSION['drogue']>0) $sante_max = drogue($_SESSION['pseudo'],$_SESSION['santemax']);
else $sante_max = $_SESSION['santemax'];

for($i=1; $i != 7; $i++)
	{
	if(($_SESSION['case'.$i.'']==$objet_v) && ($l!=1))
		{
		$use = 0;
		print('<p align="center"><strong>Vous venez d\'utiliser l\'objet : '.$objet_v.'</strong><br /><br />');
		$sqla = 'SELECT nature,bonus FROM recherche_effets_tbl WHERE ido=(SELECT id FROM objets_tbl WHERE nom= "'.$objet_v.'")';
		$reqa = mysql_query($sqla);
		$resa = mysql_num_rows($reqa);
		if($resa==0) $use = 1;
		for($p=0;$p!=$resa;$p++)
			{
			$nature = mysql_result($reqa,$p,nature);
			$bonus = mysql_result($reqa,$p,bonus);
			if($nature=="sante")
				{
				if($_SESSION['sante']<$sante_max)
					{
					$use = 1;
					$l = 1;
					$_SESSION['sante'] += $bonus;
					if($_SESSION['sante']>$sante_max) $_SESSION['sante'] = $sante_max;
					$_SESSION['soinsf'] = $_SESSION['soins'] + $bonus;
					$sql = 'UPDATE principal_tbl SET soins= "'.$_SESSION['soinsf'].'" , sante="'.$_SESSION['sante'].'" WHERE pseudo= "'.$_SESSION['pseudo'].'"';
					$req = mysql_query($sql);
					print('Votre nouvel &eacute;tat de sant&eacute; est <i>'.$_SESSION['sante'].'/'.$sante_max.'</i>.<br />');
					}
				else { $l = 1; print("Vous &ecirc;tes en bonne sant&eacute;.<br />"); }
				}
			else
				{
				if($_SESSION[$nature]<$_SESSION[$nature.'_max'])
					{
					$use = 1;
					$l = 1;
					$_SESSION[$nature] += $bonus;
					if($_SESSION[$nature]>$_SESSION[$nature.'_max']) $_SESSION[$nature] = $_SESSION[$nature.'_max'];
					$sql = 'UPDATE principal_tbl SET '.$nature.'="'.$_SESSION[$nature].'" WHERE pseudo= "'.$_SESSION['pseudo'].'"';
					$req = mysql_query($sql);
					print('Votre '.$nature.' est maintenant de <i>'.$_SESSION[$nature].'/'.$_SESSION[$nature.'_max'].'</i>.<br />');
					}
				else { $l = 1; print('Votre '.$nature.' est déjà au maximum.<br />'); }
				}
			}
		if($use==1)
			{
			$sql = 'UPDATE principal_tbl SET case'.$i.'="Vide" WHERE id="'.$_SESSION['id'].'"';
			$req = mysql_query($sql);
			}
		}
	}
	
mysql_close($db);

?>

</p>
</div>
<?php if($chat=="oui") 	{ if ((date("H")>=21) || (date("H")<=7)) { include("inc_bas_n_c.php"); } else { include("inc_bas_j_c.php"); } } else {	if ((date("H")>=21) || (date("H")<=7)) { include("inc_bas_n.php"); } else { include("inc_bas_j.php"); }	} ?>
<?php include("inc_bas_de_page.php"); ?>
