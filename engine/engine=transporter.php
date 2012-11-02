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
	
$db = mysql_connect('localhost', 'CENSURE', 'CENSURE') or die("Erreur de connexion au serveur.");
mysql_select_db('CENSURE',$db) or die("Erreur de connexion a la base de donnees.");

if($_SESSION['num'] <= 0)
	{
	$num = 0;
	$lieu = "Rue";
	}
else
	{
	$num = $_SESSION['num'];
	$lieu = $_SESSION['lieu'];
	}
$sql = 'SELECT code FROM lieu_tbl WHERE rue= "'.$lieu.'" AND num= "'.$num.'"' ;
$req = mysql_query($sql);
$codounet = mysql_result($req,0,code);

if($_SESSION['code']!=$codounet)
	{
	mysql_close($db);
	print('<meta http-equiv="refresh" content="0 ; url=http://v2.dreadcast.net/ingame/engine.php"> ');
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
			Transporter
		</div>
		<b class="module4ie"><a href="engine.php" class="module4"><img src="im_objets/icon_retour.gif" alt="Retour" />Retour</a></b>
</p>
	</div>
</div>
<div id="centre" style="line-height:22px;">

<?php	

$db = mysql_connect('localhost', 'CENSURE', 'CENSURE') or die("Erreur de connexion au serveur.");
mysql_select_db('CENSURE',$db) or die("Erreur de connexion a la base de donnees.");

$cible = $_GET['c'];

if($cible == "")
	{
	print('<meta http-equiv="refresh" content="0 ; url=http://v2.dreadcast.net/ingame/engine.php"> ');
	exit();
	}

if(!estVisible($cible,25) && $_SESSION['statut'] != 'Administrateur')
	{
	print('<strong>'.ucfirst($cible).'</strong> n\'est pas ou plus au même endroit que vous.<br /><a href="engine.php">Retour</a>');
	}
elseif($_GET['a']=="")
	{
	$sql = 'SELECT sante FROM principal_tbl WHERE pseudo = "'.$cible.'"';
	$req = mysql_query($sql);
	$res = mysql_num_rows($req);
	
	if(!$res || ($res && mysql_result($req,0,sante) != 0)  && $_SESSION['statut'] != 'Administrateur')
		{
		print('<meta http-equiv="refresh" content="0 ; url=http://v2.dreadcast.net/ingame/engine.php"> ');
		exit();
		}
	
	print('Où souhaitez-vous transporter <strong>'.ucfirst($cible).'</strong> ?<br /><br />
	<a href="engine=transporter.php?c='.$cible.'&a=1">À l\'hôpital le plus proche</a><br />
	<a href="engine=transporter.php?c='.$cible.'&a=2">Au commissariat de police</a><br />
	<a href="#" onclick="$(\'#a3\').show();">À une autre adresse</a><br />
	<form id="a3" style="display:none;" action="engine=transporter.php" method="get">
		<input type="hidden" name="c" value="'.$cible.'" />
		<input type="hidden" name="a" value="3" />
		À l\'adresse suivante : 
		<input type="text" name="d" value="" /> <input type="submit" name="submit" value="Valider" /></form>');
	
	if(est_dans_inventaire('carnet') || $_SESSION['statut'] == 'Administrateur')
		{
		print('<a href="#" onclick="$(\'#a4\').show();">À une adresse enregistrée</a><br />
		<form id="a4" style="display:none;" action="engine=transporter.php" method="get">
			<input type="hidden" name="c" value="'.$cible.'" />
			<input type="hidden" name="a" value="4" />
			À l\'adresse enregistrée suivante : 
			<select name="d">
				<option value="" selected style="display:none;">Destination</option>');
			
			$sql = 'SELECT nom,num,rue FROM adresses_tbl WHERE pseudo = "'.$_SESSION['pseudo'].'"';
			$req = mysql_query($sql);
			$res = mysql_num_rows($req);
			for($i=0;$i<$res;$i++) print('<option value="'.mysql_result($req,$i,num).' '.mysql_result($req,$i,rue).'">'.mysql_result($req,$i,nom).'</option>');
			
			print('</select> 
			<input type="submit" name="submit" value="Valider" /></form>');
		}
	}
elseif($_GET['a']==1)
	{
	$hopital = trouver_hopital($_SESSION['num'],$_SESSION['rue']);
	if($hopital['num']=="" && $hopital['rue']=="") print('Aucun hôpital n\'est ouvert en ce moment.<br /><a href="engine.php">Retour</a>');
	else{
		$retour1 = deplacement($_SESSION['pseudo'],$_SESSION['num'],$_SESSION['rue'],$hopital['num'],$hopital['rue'],2);
		$retour2 = deplacement($cible,$_SESSION['num'],$_SESSION['rue'],$hopital['num'],$hopital['rue'],0);
		
		if($retour1 == "ok" && $retour2 == "ok")
			{
			enregistre_evt($cible,'Transport',$hopital['num'].' '.$hopital['rue']);
			print('Vous transportez <strong>'.ucfirst($cible).'</strong> à l\'hôpital "'.$hopital['nom'].'".<br /><br /><img src="im_objets/loader.gif" alt="Veuillez patienter..." />');
			print('<meta http-equiv="refresh" content="1 ; url=http://v2.dreadcast.net/ingame/engine.php"> ');
			}
		else print('<a href="engine.php">Retour</a>');
		}
	}
elseif($_GET['a']==2)
	{
	$sql = 'SELECT num,rue FROM entreprises_tbl WHERE nom="Police"';
	$req = mysql_query($sql);
	$numdest = mysql_result($req,0,num);
	$ruedest = mysql_result($req,0,rue);
	
	$retour1 = deplacement($_SESSION['pseudo'],$_SESSION['num'],$_SESSION['rue'],$numdest,$ruedest,2);
	$retour2 = deplacement($cible,$_SESSION['num'],$_SESSION['rue'],$numdest,$ruedest,0);
	
	if($retour1 == "ok" && $retour2 == "ok")
		{
		enregistre_evt($cible,'Transport',$numdest.' '.$ruedest);
		print('Vous transportez <strong>'.ucfirst($cible).'</strong> au commissariat de Police.<br /><br /><img src="im_objets/loader.gif" alt="Veuillez patienter..." />');
		print('<meta http-equiv="refresh" content="1 ; url=http://v2.dreadcast.net/ingame/engine.php"> ');
		}
	else print('<a href="engine.php">Retour</a>');
	}
elseif($_GET['a']==3 || $_GET['a']==4)
	{
	$numrue = analyse_numrue($_GET['d']);
	if(!is_array($numrue)) print('Adresse non valide.<br /><a href="engine=transporter.php?c='.$cible.'">Retour</a>');
	else
		{
		$retour1 = deplacement($_SESSION['pseudo'],$_SESSION['num'],$_SESSION['rue'],$numrue['num'],$numrue['rue'],2);
		$retour2 = deplacement($cible,$_SESSION['num'],$_SESSION['rue'],$numrue['num'],$numrue['rue'],0);
		
		if($retour1 == "ok" && $retour2 == "ok")
			{
			enregistre_evt($cible,'Transport',$numrue['num'].' '.$numrue['rue']);
			print('Vous transportez <strong>'.ucfirst($cible).'</strong> au '.$numrue['num'].' '.$numrue['rue'].'.<br /><br /><img src="im_objets/loader.gif" alt="Veuillez patienter..." />');
			print('<meta http-equiv="refresh" content="1 ; url=http://v2.dreadcast.net/ingame/engine.php"> ');
			}
		elseif($retour1 == 'inaccessible')  print('Ce lieu est inaccessible.<br /><a href="engine=transporter.php?c='.$cible.'">Retour</a>');
		elseif($retour1 == 'inexistant') print('Il n\'y a rien au <i>'.$numrue['num'].' '.$numrue['rue'].'</i>.<br /><a href="engine=transporter.php?c='.$cible.'">Retour</a>');
		else print('<a href="engine.php">Retour</a>');
		}
	}
	
mysql_close($db);

function trouver_hopital($monnum,$marue) {
	$infos['rayon'] = 10000;
	$sql = 'SELECT x,y FROM carte_tbl WHERE num="'.$monnum.'" AND idrue=(SELECT id FROM rues_tbl WHERE nom="'.$marue.'")';
	$req = mysql_query($sql);
	$monx = mysql_result($req,0,x);
	$mony = mysql_result($req,0,y);
	$sql = 'SELECT nom,num,rue FROM entreprises_tbl WHERE type = "hopital" AND ouvert="oui"';
	$req = mysql_query($sql);
	$res = mysql_num_rows($req);
	for($i=0;$i<$res;$i++)
		{
		$sql2 = 'SELECT x,y FROM carte_tbl WHERE num="'.mysql_result($req,$i,num).'" AND idrue=(SELECT id FROM rues_tbl WHERE nom="'.mysql_result($req,$i,rue).'")';
		$req2 = mysql_query($sql2);
		$xtmp = mysql_result($req2,0,x);
		$ytmp = mysql_result($req2,0,y);
		$rayon = sqrt(($monx-$xtmp)*($monx-$xtmp) + ($mony-$ytmp)*($mony-$ytmp));
		if($rayon<$infos['rayon'])
			{
			$infos['rayon'] = $rayon;
			$infos['x'] = $xtmp;
			$infos['y'] = $ytmp;
			$infos['nom'] = mysql_result($req,$i,nom);
			$infos['num'] = mysql_result($req,$i,num);
			$infos['rue'] = mysql_result($req,$i,rue);
			}
		}
	return $infos;
	}

function analyse_numrue($chaine) {
	$chaine = trim(htmlentities($chaine));
	if(ereg("^[0-9]{1,3} [a-zA-Z ]+$",$chaine))
		{
		$num = trim(preg_replace("#[a-zA-Z ]+$#","",$chaine));
		$rue = trim(preg_replace("#^[0-9]{1,3}#","",$chaine));
		
		return array('num' => $num, 'rue' => $rue);
		}
	}

?>

</div>
<?php if($chat=="oui") 	{ if ((date("H")>=21) || (date("H")<=7)) { include("inc_bas_n_c.php"); } else { include("inc_bas_j_c.php"); } } else {	if ((date("H")>=21) || (date("H")<=7)) { include("inc_bas_n.php"); } else { include("inc_bas_j.php"); }	} ?>
<?php include("inc_bas_de_page.php"); ?>
