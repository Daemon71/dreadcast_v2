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
if ((date("H")>=21) || (date("H")<=7)) { include("inc_haut_n.php"); } else { include("inc_haut_j.php");}

$db = mysql_connect('localhost', 'CENSURE', 'CENSURE') or die("Erreur de connexion au serveur des messages.");
mysql_select_db('CENSURE',$db) or die("Erreur de connexion a la base de donnees des messages.");

$sql = 'SELECT id,rue,num,fatigue,drogue,fatigue_max,medecine,vetements,objet,arme FROM principal_tbl WHERE pseudo= "'.$_SESSION['pseudo'].'"' ;
$req = mysql_query($sql);
$_SESSION['id'] = mysql_result($req,0,id);
$_SESSION['lieu'] = mysql_result($req,0,rue);
$_SESSION['num'] = mysql_result($req,0,num);
$_SESSION['fatigue'] = mysql_result($req,0,fatigue);
$_SESSION['fatiguemax'] = mysql_result($req,0,fatigue_max);
$_SESSION['drogue'] = mysql_result($req,0,drogue);
$_SESSION['vetements'] = mysql_result($req,0,vetements);
$_SESSION['objet'] = mysql_result($req,0,objet);
$_SESSION['arme'] = mysql_result($req,0,arme);
$medecine = mysql_result($req,0,medecine);
$_SESSION['medecine'] = $medecine;

//condition DIGICODE
if($_SESSION['cible']!=$_SESSION['pseudo'])
	{
	$sql = 'SELECT code FROM lieu_tbl WHERE rue= "'.$_SESSION['lieu'].'" AND num= "'.$_SESSION['num'].'"' ;
	$req = mysql_query($sql);
	$res = mysql_num_rows($req);
	if($res>0)
		{
		$codounet = mysql_result($req,0,code);
		if($_SESSION['code']!=$codounet)
			{
			mysql_close($db);
			print('<meta http-equiv="refresh" content="0 ; url=http://v2.dreadcast.net/ingame/engine.php"> ');
			exit();
			}
		}
	}


mysql_close($db);

$stat = dcstat($_SESSION['pseudo'],$_SESSION['medecine']);

$db = mysql_connect('localhost', 'CENSURE', 'CENSURE') or die("Erreur de connexion au serveur des messages.");
mysql_select_db('CENSURE',$db) or die("Erreur de connexion a la base de donnees des messages.");

$sqla = 'SELECT nature,bonus FROM recherche_effets_tbl WHERE ido=(SELECT id FROM objets_tbl WHERE nom= "'.$_SESSION['objet'].'") OR ido=(SELECT id FROM objets_tbl WHERE nom= "'.$_SESSION['vetements'].'") OR ido=(SELECT id FROM objets_tbl WHERE nom= "'.preg_replace("#(.+)\-(.+)#","$1",$_SESSION['arme']).'")';
$reqa = mysql_query($sqla);
$resa = mysql_num_rows($reqa);
for($i=0;$i!=$resa;$i++)
	{
	$_SESSION['medecine'] += mysql_result($reqa,$i,bonus);
	}


$sql = 'SELECT id,rue,num,sante,soins,drogue,sante_max,event FROM principal_tbl WHERE pseudo= "'.$_SESSION['cible'].'"' ;
$req = mysql_query($sql);
$_SESSION['idc'] = mysql_result($req,0,id);
$lieuc = mysql_result($req,0,rue);
$numc = mysql_result($req,0,num);
$santec = mysql_result($req,0,sante);
$droguec = mysql_result($req,0,drogue);
$santecmax = mysql_result($req,0,sante_max);
$soin = mysql_result($req,0,soins);

$event = mysql_result($req,0,event);
$event = event() && estDroide() ? $event : 0;
$santecmax = $event == 1 || adm() ? $santecmax+200 : $santecmax;

$trans = $_POST['soins']/10;

if($trans<0)
	{
	$trans = 0;
	}

if($numc!=$_SESSION['num'] || ucwords(strtolower($lieuc))!=ucwords(strtolower($_SESSION['lieu'])))
	{
	$l = 1;
	}
elseif($_SESSION['medecine']==0)
	{
	$l = 2;
	}
elseif($soin>=40)
	{
	$l = 3;
	}
else
	{
	$transreel = 0;

	for($i=0 ; $i != $trans ; $i++)
		{
		if($droguec==0)
			{
			if(($_SESSION['fatigue']>0) && ($santec<$santecmax))
				{
				augmenter_statistique($_SESSION['id'],"medecine",$medecine);
				if($stat>140)
					{
					$_SESSION['fatigue'] -= 1;
					$santec += 10;
					$transreel += 10;
					}
				elseif($stat>100)
					{
					$_SESSION['fatigue'] -= 5;
					$santec += 10;
					$transreel += 10;
					}
				elseif($stat>70)
					{
					$_SESSION['fatigue'] -= 11;
					$santec += 10;
					$transreel += 10;
					}
				elseif($stat>50)
					{
					$_SESSION['fatigue'] -= 22;
					$santec += 10;
					$transreel += 10;
					}
				elseif($stat>30)
					{
					$_SESSION['fatigue'] -= 33;
					$santec += 10;
					$transreel += 10;
					}
				elseif($stat>10)
					{
					$_SESSION['fatigue'] -= 44;
					$santec += 10;
					$transreel += 10;
					}
				else
					{
					$_SESSION['fatigue'] -= 55;
					$santec += 10;
					$transreel += 10;
					}
				}
			}
		elseif($droguec>0)
			{
			if(($_SESSION['fatigue']>0) && ($santec<drogue($_SESSION['cible'],$santecmax)))
				{
				augmenter_statistique($_SESSION['id'],"medecine",$medecine);
				if($stat>70)
					{
					$_SESSION['fatigue'] -= 11;
					$santec += 10;
					$transreel += 10;
					}
				elseif($stat>50)
					{
					$_SESSION['fatigue'] -= 22;
					$santec += 10;
					$transreel += 10;
					}
				elseif($stat>30)
					{
					$_SESSION['fatigue'] -= 33;
					$santec += 10;
					$transreel += 10;
					}
				elseif($stat>10)
					{
					$_SESSION['fatigue'] -= 44;
					$santec += 10;
					$transreel += 10;
					}
				else
					{
					$_SESSION['fatigue'] -= 55;
					$santec += 10;
					$transreel += 10;
					}
				}
			}
		}
	
	if($_SESSION['fatigue']<0)
		{
		$_SESSION['fatigue'] = 0;
		}

	if($transreel>0)
		{
		$sql = 'UPDATE principal_tbl SET fatigue= "'.$_SESSION['fatigue'].'" WHERE id= "'.$_SESSION['id'].'"' ;
		$req = mysql_query($sql);
		if($_SESSION['pseudo']!=$_SESSION['cible'])
			{
			$sql = 'INSERT INTO messages_tbl(id,auteur,cible,message,objet,moment) VALUES("","Dreadcast","'.$_SESSION['cible'].'","<br /><br /><br /><br /><br /><strong>'.$_SESSION['pseudo'].'</strong> vous &agrave; soign&eacute;.<br />Il vous &agrave; ajout&eacute; '.$transreel.' Points de sant&eacute;.","On vous soigne !","'.time().'")' ;
			$req = mysql_query($sql);
			}
		}
	
	mysql_close($db);
	
	if($_SESSION['cible']==$_SESSION['pseudo'])
		{
		$_SESSION['sante'] = $santec;
		}
	}
?>

<div id="haut">
	<?php include("inc_entete.php"); ?>
	<div class="haut_aligngauche">
		<p>
		<div class="titrepage">
			Gu&eacute;rir
		</div>
		<b class="module4ie"><a href="engine=cible.php?<?php print(''.$_SESSION['cible'].''); ?>" class="module4"><img src="im_objets/icon_retour.gif" alt="Retour" />Retour</a></b>
</p>
	</div>
</div>
<div id="centre">
<p>

<?php
if($l==1)
	{
	print('<p align="center"><strong>Il est impossible de soigner <i>'.$_SESSION['cible'].'</i> car il n\'est pas au m&ecirc;me endroit que vous.</strong></p>');
	}
elseif($l==2)
	{
	print('<p align="center"><strong>Il est impossible de soigner <i>'.$_SESSION['cible'].'</i> car vous ne savez pas comment faire.</strong></p>');
	}
elseif($l==3)
	{
	print('<p align="center"><strong>Il est impossible de soigner <i>'.$_SESSION['cible'].'</i> car il a déjà trop de bandages sur lui.</strong></p>');
	}
else
	{
	if($transreel>0)
		{
		$db = mysql_connect('localhost', 'CENSURE', 'CENSURE') or die("Erreur de connexion au serveur des messages.");
		mysql_select_db('CENSURE',$db) or die("Erreur de connexion a la base de donnees des messages.");
		
		sante_ajouter($_SESSION['idc'],$transreel);
		enregistre($_SESSION['pseudo'],"acc_nb_soins_donnes","+1");
		enregistre($_SESSION['pseudo'],"acc_soins_donnes","+".$transreel);
		enregistre($_SESSION['cible'],"acc_soins_recus","+".$transreel);
		
		mysql_close($db);
		}
	print('<em>Vous avez soign&eacute; <strong>'.$transreel.' </strong> points de sant&eacute; &agrave; '.$_SESSION['cible'].'</em>');
	}
?>

</p>
</div>
<?php if($chat=="oui") 	{ if ((date("H")>=21) || (date("H")<=7)) { include("inc_bas_n_c.php"); } else { include("inc_bas_j_c.php"); } } else {	if ((date("H")>=21) || (date("H")<=7)) { include("inc_bas_n.php"); } else { include("inc_bas_j.php"); }	} ?>
<?php include("inc_bas_de_page.php"); ?>
