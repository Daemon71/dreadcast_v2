<?php 
session_start(); 
if($_SESSION['id']=="")
	{
	print('<meta http-equiv="refresh" content="0 ; url=http://v2.dreadcast.net/"> ');
	exit();
	}
if($_SESSION['statut']=="Administrateur")
	{
	//print('<meta http-equiv="refresh" content="0 ; url=http://v2.dreadcast.net/ingame/engine.php"> ');
	//exit();
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
if(empty($_SESSION['distance']))
	{
	print('<meta http-equiv="refresh" content="0 ; url=http://v2.dreadcast.net/ingame/engine.php"> ');
	exit();
	}

//FLASH
$_SESSION['j1'] = "";
$_SESSION['j1max'] = "";
$_SESSION['j2'] = "";
$_SESSION['j2max'] = "";
$_SESSION['j3'] = "";
$_SESSION['j3max'] = "";
$_SESSION['j4'] = "";
$_SESSION['j4max'] = "";
$_SESSION['j5'] = "";
$_SESSION['j5max'] = "";
$_SESSION['j6'] = "";
$_SESSION['j6max'] = "";
$_SESSION['j7'] = "";
$_SESSION['j7max'] = "";
$_SESSION['j8'] = "";
$_SESSION['j8max'] = "";
$_SESSION['j9'] = "";
$_SESSION['j9max'] = "";
$_SESSION['j10'] = "";
$_SESSION['j10max'] = "";
$_SESSION['j11'] = "";
$_SESSION['j11max'] = "";
$_SESSION['j12'] = "";
$_SESSION['j12max'] = "";
$_SESSION['j13'] = "";
$_SESSION['j13max'] = "";
$_SESSION['j14'] = "";
$_SESSION['j14max'] = "";
$_SESSION['j15'] = "";
$_SESSION['j15max'] = "";
$_SESSION['j16'] = "";
$_SESSION['j16max'] = "";
$_SESSION['j17'] = "";
$_SESSION['j17max'] = "";
$_SESSION['j18'] = "";
$_SESSION['j18max'] = "";

if ((date("H")>=21) || (date("H")<=7)) { include("inc_haut_n.php"); } else { include("inc_haut_j.php");}

// CIBLE
$cible = $_GET['cible'];

// NE PAS S'AUTO-CIBLER
if($cible==$_SESSION['pseudo'])
	{
	print('<meta http-equiv="refresh" content="0 ; url=http://v2.dreadcast.net/ingame/engine.php"> ');
	exit();
	}	

if(!ereg($cible,$_SESSION['personnes']))
	{
	$_SESSION['personnes'] = "";
	print('<meta http-equiv="refresh" content="0 ; url=http://v2.dreadcast.net/ingame/engine.php"> ');
	exit();
	}

$db = mysql_connect('localhost', 'CENSURE', 'CENSURE') or die("Erreur de connexion au serveur des messages.");
mysql_select_db('CENSURE',$db) or die("Erreur de connexion a la base de donnees des messages.");

// SI CIBLE MORTE

if(est_mort($cible))
	{
	print('<meta http-equiv="refresh" content="0 ; url=http://v2.dreadcast.net/ingame/engine.php"> ');
	exit();
	}
	
// RECUPERATION INFORMATIONS PERSONNELLES
$sql = 'SELECT * FROM principal_tbl WHERE pseudo= "'.$_SESSION['pseudo'].'"' ;
$req = mysql_query($sql);
$_SESSION['id'] = mysql_result($req,0,id);
$_SESSION['lieu'] = mysql_result($req,0,rue);
$_SESSION['num'] = mysql_result($req,0,num);
$_SESSION['action'] = mysql_result($req,0,action);
$_SESSION['sante'] = mysql_result($req,0,sante);
$_SESSION['santemax'] = mysql_result($req,0,sante_max);
$_SESSION['fatigue'] = mysql_result($req,0,fatigue);
$_SESSION['arme'] = mysql_result($req,0,arme);
$_SESSION['objet'] = mysql_result($req,0,objet);
$_SESSION['vetements'] = mysql_result($req,0,vetements);
$_SESSION['case1'] = mysql_result($req,0,case1);
$_SESSION['case2'] = mysql_result($req,0,case2);
$_SESSION['case3'] = mysql_result($req,0,case3);
$_SESSION['case4'] = mysql_result($req,0,case4);
$_SESSION['case5'] = mysql_result($req,0,case5);
$_SESSION['case6'] = mysql_result($req,0,case6);
$_SESSION['combat'] = dcstat($_SESSION['pseudo'],mysql_result($req,0,combat),0);
$_SESSION['vol'] = dcstat($_SESSION['pseudo'],mysql_result($req,0,vol),0);
$_SESSION['tir'] = dcstat($_SESSION['pseudo'],mysql_result($req,0,tir),0);
$_SESSION['resistance'] = dcstat($_SESSION['pseudo'],mysql_result($req,0,resistance),0);
$_SESSION['discretion'] = dcstat($_SESSION['pseudo'],mysql_result($req,0,discretion),0);
$_SESSION['observation'] = dcstat($_SESSION['pseudo'],mysql_result($req,0,observation),0);
$_SESSION['entreprise'] = mysql_result($req,0,entreprise);
$typeamoi = mysql_result($req,0,type);
$monEvent = event() && estDroide() ? mysql_result($req,0,event) : 0;
$_SESSION['combat'] = $monEvent ? 300 : $_SESSION['combat'];
$_SESSION['santemax'] = $monEvent ? $_SESSION['santemax']+200 : $_SESSION['santemax'];

if($_SESSION['entreprise']=="Police" || $_SESSION['entreprise']=="DI2RCO")
	{
	$sql = 'SELECT bdd FROM `e_'.str_replace(" ","_",$_SESSION['entreprise']).'_tbl` WHERE  poste= "'.$typeamoi.'"' ;
	$req = mysql_query($sql);
	$bddp = mysql_result($req,0,bdd);
	}

$depmax = 6;

// PROTECTION

$sqltmp = 'SELECT num,rue FROM principal_tbl WHERE pseudo = "'.$cible.'"' ;
$reqtmp = mysql_query($sqltmp);

$sqltmp = 'SELECT pseudo FROM principal_tbl WHERE action="Protection '.$cible.'" AND num = "'.mysql_result($reqtmp,0,num).'" AND rue = "'.mysql_result($reqtmp,0,rue).'"' ;
$reqtmp = mysql_query($sqltmp);
if(mysql_num_rows($reqtmp) != 0)
	{
	if(mysql_result($reqtmp,0,pseudo) == $_SESSION['pseudo'])
		{
		print('<meta http-equiv="refresh" content="0 ; url=http://v2.dreadcast.net/ingame/engine.php"> ');
		exit();
		}
	$protege = $cible;
	$cible = mysql_result($reqtmp,0,pseudo);
	}

//condition prison
if($_SESSION['action']=="prison")
	{
	mysql_close($db);
	print('<meta http-equiv="refresh" content="0 ; url=http://v2.dreadcast.net/ingame/engine.php"> ');
	exit();
	}


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

//récupération de la camera du lieu
$sqlc = 'SELECT camera FROM lieu_tbl WHERE num= "'.$num.'" AND rue= "'.$lieu.'"' ;
$reqc = mysql_query($sqlc);
$camerac = mysql_result($reqc,0,camera);

//condition DIGICODE
$sql = 'SELECT code FROM lieu_tbl WHERE rue= "'.$lieu.'" AND num= "'.$num.'"' ;
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


//récuperation des infos de la cible
$sql = 'SELECT * FROM principal_tbl WHERE pseudo= "'.$cible.'"' ;
$req = mysql_query($sql);
$idc = mysql_result($req,0,id);
$lieuc = mysql_result($req,0,rue);
$numc = mysql_result($req,0,num);
$lieulc = mysql_result($req,0,ruel);
$numlc = mysql_result($req,0,numl);
$santec = mysql_result($req,0,sante);
$santecmax = mysql_result($req,0,sante_max);
$fatiguec = mysql_result($req,0,fatigue);
$actionc = mysql_result($req,0,action);
$telephonec = mysql_result($req,0,telephone);
$SMSc = mysql_result($req,0,SMS);
$statutc = mysql_result($req,0,statut);
$combatc = dcstat($cible,mysql_result($req,0,combat),0);
$tirc = dcstat($cible,mysql_result($req,0,tir),0);
$resistancec = dcstat($cible,mysql_result($req,0,resistance),0);
$discretionc = dcstat($cible,mysql_result($req,0,discretion),0);
$observationc = dcstat($cible,mysql_result($req,0,observation),0);
$SMSdjc = mysql_result($req,0,SMSdj);
$evenementsSMSc = mysql_result($req,0,evenementsSMS);
$armec = mysql_result($req,0,arme);
$objetc = mysql_result($req,0,objet);
$vetementsc = mysql_result($req,0,vetements);
$connecc = mysql_result($req,0,connec);
$sonEvent = event() && estDroide(mysql_result($req,0,race)) ? mysql_result($req,0,event) : 0;
$combatc = $sonEvent ? 300 : $combatc;
$santecmax = $sonEvent ? $santecmax+100 : $santecmax;

if(mysql_result($req,0,statut)=="Debutant" || mysql_result($req,0,pseudo)=="Themista"||mysql_result($req,0,pseudo)=="Tiburce"||mysql_result($req,0,pseudo)=="Vinci")
	{
	mysql_close($db);
	print('<meta http-equiv="refresh" content="0 ; url=http://v2.dreadcast.net/ingame/engine.php"> ');
	exit();
	}

//condition Police / DI2RCO / Agent de sécurité
$sqlr = 'SELECT nom FROM entreprises_tbl WHERE num= "'.$_SESSION['num'].'" AND rue= "'.$_SESSION['lieu'].'"' ;
$reqr = mysql_query($sqlr);
$resr = mysql_num_rows($reqr);

if($resr>0)
	{
	$entrepriser = mysql_result($reqr,0,nom);
	if($entrepriser=="Police")
		{
		if(($_SESSION['entreprise']=="DI2RCO" || $_SESSION['entreprise']=="Police") && ($_GET['action']=="arrestation"))
			{
			$_SESSION['r'.$idc.''] = 1;
			print('<meta http-equiv="refresh" content="0 ; url=http://v2.dreadcast.net/ingame/engine=arrestation.php?'.$cible.'"> ');
			exit();
			}
		}
	if($entrepriser=="DI2RCO")
		{
		if(($_SESSION['entreprise']=="DI2RCO" || $_SESSION['entreprise']=="Police") && ($_GET['action']=="arrestation"))
			{
			$_SESSION['r'.$idc.''] = 1;
			print('<meta http-equiv="refresh" content="0 ; url=http://v2.dreadcast.net/ingame/engine=arrestation.php?'.$cible.'"> ');
			exit();
			}
		}
	$sql = 'SELECT id FROM principal_tbl WHERE Num= "'.$_SESSION['num'].'" AND rue= "'.$_SESSION['lieu'].'" AND action= "travail"' ;
	$req = mysql_query($sql);
	$res = mysql_num_rows($req);
	
	for($a=0;$a!=$res;$a++)
		{
		$sql1 = 'SELECT type FROM principal_tbl WHERE  id= "'.mysql_result($req,$a,id).'"' ;
		$req1 = mysql_query($sql1);
		$sql1 = 'SELECT type FROM `e_'.str_replace(" ","_",''.$entrepriser.'').'_tbl` WHERE  poste= "'.mysql_result($req1,0,type).'"' ;
		$req1 = mysql_query($sql1);
		$res1 = mysql_num_rows($req1);
		if($res1 && mysql_result($req1,0,type)=="securite")
			{
			if($_SESSION['entreprise']!="DI2RCO" && $_SESSION['entreprise']!="Police")
				{
				$action1 = "securite";
				$imp = 1;
				}
			elseif($bddp=="")
				{
				$action1 = "securite";
				$imp = 1;
				}
			}
		}
	}

//concernant les armes et les réactions de la cible
$sql = 'SELECT arme FROM principal_tbl WHERE pseudo= "'.$_SESSION['pseudo'].'"' ;
$req = mysql_query($sql);
if(ereg("-",mysql_result($req,0,arme)))
	{
	$idarme1 = strstr(mysql_result($req,0,arme),"-");
	$arme1 = substr(mysql_result($req,0,arme),0,strpos(mysql_result($req,0,arme),"-"));
	}
else
	{
	$arme1 = mysql_result($req,0,arme);
	}

$sql2 = 'SELECT rattaque,rvol,rpolice,rapproche,rintrusion,avatar,arme FROM principal_tbl WHERE pseudo= "'.$cible.'"';
$req2 = mysql_query($sql2);
if(ereg("-",mysql_result($req2,0,arme)))
	{
	$idarme2 = strstr(mysql_result($req2,0,arme),"-");
	$arme2 = substr(mysql_result($req2,0,arme),0,strpos(mysql_result($req2,0,arme),"-"));
	}
else
	{
	$arme2 = mysql_result($req2,0,arme);
	}
$avatar2 = mysql_result($req2,0,avatar);
$rattaquec = mysql_result($req2,0,rattaque);
$rvolc = mysql_result($req2,0,rvol);
$rpolicec = mysql_result($req2,0,rpolice);
$rapprochec = mysql_result($req2,0,rapproche);
$rintrusionc = mysql_result($req2,0,rintrusion);

$sql = 'SELECT type,puissance,ecart,distance FROM objets_tbl WHERE nom= "'.$arme1.'"';
$req = mysql_query($sql);
$type1 = mysql_result($req,0,type);
$puissance1 = mysql_result($req,0,puissance);
$ecart1 = mysql_result($req,0,ecart);
$distance1 = mysql_result($req,0,distance);

$sql = 'SELECT * FROM armes_tbl WHERE idarme= "'.$idarme1.'"';
$req = mysql_query($sql);
$res = mysql_num_rows($req);
if($res>0)
	{
	$armeunique1 = 1;
	$usure1 = mysql_result($req,0,usure);
	$chargeur1 = mysql_result($req,0,chargeur);
	$mode1 = mysql_result($req,0,mode);
	$modif11 = mysql_result($req,0,modif1);
	$modif12 = mysql_result($req,0,modif2);
	$modif13 = mysql_result($req,0,modif3);
	if($modif11==1 || $modif12==1 || $modif13==1) { $_SESSION['tir'] = $_SESSION['tir'] + 15; }
	if($modif11==2 || $modif12==2 || $modif13==2) { $_SESSION['tir'] = $_SESSION['tir'] + 5; }
	if($modif11==3 || $modif12==3 || $modif13==3) { $distance1 = $distance1 + 10; }
	if($modif11==5 || $modif12==5 || $modif13==5) { $_SESSION['discretion'] = $_SESSION['discretion'] + 10; }
	if($modif11==7 || $modif12==7 || $modif13==7) { $_SESSION['observation'] = $_SESSION['observation'] + 5; }
	}
else
	{
	$mode1 = "s";
	$chargeur1 = 1;
	}

$sql2 = 'SELECT type,puissance,ecart,distance FROM objets_tbl WHERE nom= "'.$arme2.'"' ;
$req2 = mysql_query($sql2);
$type2 = mysql_result($req2,0,type);
$puissance2 = mysql_result($req2,0,puissance);
$ecart2 = mysql_result($req2,0,ecart);
$distance2 = mysql_result($req2,0,distance);

$sql2 = 'SELECT * FROM armes_tbl WHERE idarme= "'.$idarme2.'"' ;
$req2 = mysql_query($sql2);
$res2 = mysql_num_rows($req2);
if($res2>0)
	{
	$armeunique2 = 1;
	$usure2 = mysql_result($req2,0,usure);
	$mode2 = mysql_result($req2,0,mode);
	$chargeur2 = mysql_result($req2,0,chargeur);
	$modif21 = mysql_result($req2,0,modif1);
	$modif22 = mysql_result($req2,0,modif2);
	$modif23 = mysql_result($req2,0,modif3);
	if($modif21==1 || $modif22==1 || $modif23==1) { $tirc = $tirc + 15; }
	if($modif21==2 || $modif22==2 || $modif23==2) { $tirc = $tirc + 5; }
	if($modif21==3 || $modif22==3 || $modif23==3) { $distance2 = $distance2 + 10; }
	if($modif21==5 || $modif22==5 || $modif23==5) { $discretionc = $discretionc + 10; }
	if($modif21==7 || $modif22==7 || $modif23==7) { $observationc = $observationc + 5; }
	}
else
	{
	$mode2 = "s";
	$chargeur2 = 1;
	}

$depmaxc = 6;

//condition intrusion
if((ucwords($lieulc)==ucwords($lieuc)) && ($numc==$numlc))
	{
	$rattaquec = $rintrusionc;
	}

//condition distance arme
if(($_SESSION['distance']>$distance1) && ($_GET['action']=="attaquer"))
	{
	$action1 = "distance";
	$imp = 1;
	}

//condition prison ou protection de la cible
if(($actionc=="prison") || ($actionc=="protection"))
	{
	mysql_close($db);
	print('<meta http-equiv="refresh" content="0 ; url=http://v2.dreadcast.net/ingame/engine.php"> ');
	exit();
	}

//condition chargeur vide
if($chargeur1==0 && $armeunique1==1 && $_GET['action']=="attaquer" && $arme1!="Lance flammes" && $arme1!="Lance roquette")
	{
	$action1 = "chargeur";
	$imp = 1;
	}

//condition cible administrateur
if($statutc=="Administrateur")
	{
	$action1 = "admin";
	$imp = 1;
	}

//autres conditions
if(!estVisible($cible,25))
	{
	$action1 = "lieu";
	$imp = 1;
	$_SESSION['distance'] = "??";
	}
elseif($_SESSION['fatigue']<=0)
	{
	$action1 = "fatigue";
	$imp = 1;
	}
elseif($_GET['action']!="approcher" && $_GET['action']!="eloigner" && $_GET['action']!="voler")
	{
	forme_retirer($_SESSION['id'],10);
	$_SESSION['fatigue'] -= 10;
	}
else
	{
	forme_retirer($_SESSION['id'],1);
	$_SESSION['fatigue'] -= 1;
	}

//mise en place des bonus
$sql2 = 'SELECT id FROM objets_tbl WHERE nom= "'.$_SESSION['vetements'].'" OR nom= "'.$_SESSION['arme'].'" OR nom= "'.$_SESSION['objet'].'"' ;
$req2 = mysql_query($sql2);
$res2 = mysql_num_rows($req2);
for($p=0;$p!=$res2;$p++)
	{
	$sql3 = 'SELECT * FROM recherche_effets_tbl WHERE ido= "'.mysql_result($req2,$p,id).'"' ;
	$req3 = mysql_query($sql3);
	$res3 = mysql_num_rows($req3);
	for($i=0;$i!=$res3;$i++)
		{
		if(mysql_result($req3,$i,nature)=="resistance") { $_SESSION['resistance'] = $_SESSION['resistance'] + mysql_result($req3,$i,bonus); }
		if(mysql_result($req3,$i,nature)=="discretion") { $_SESSION['discretion'] = $_SESSION['discretion'] + mysql_result($req3,$i,bonus); }
		if(mysql_result($req3,$i,nature)=="tir") { $_SESSION['tir'] = $_SESSION['tir'] + mysql_result($req3,$i,bonus); }
		if(mysql_result($req3,$i,nature)=="combat") { $_SESSION['combat'] = $_SESSION['combat'] + mysql_result($req3,$i,bonus); }
		if(mysql_result($req3,$i,nature)=="observation") { $_SESSION['observation'] = $_SESSION['observation'] + mysql_result($req3,$i,bonus); }
		}
	}
$sql2 = 'SELECT id FROM objets_tbl WHERE nom= "'.$vetementsc.'" OR nom= "'.$armec.'" OR nom= "'.$objetc.'"' ;
$req2 = mysql_query($sql2);
$res2 = mysql_num_rows($req2);
for($p=0;$p!=$res2;$p++)
	{
	$sql3 = 'SELECT * FROM recherche_effets_tbl WHERE ido= "'.mysql_result($req2,$p,id).'"' ;
	$req3 = mysql_query($sql3);
	$res3 = mysql_num_rows($req3);
	for($i=0;$i!=$res3;$i++)
		{
		if(mysql_result($req3,$i,nature)=="resistance") { $resistancec = $resistancec + mysql_result($req3,$i,bonus); }
		if(mysql_result($req3,$i,nature)=="discretion") { $discretionc = $discretionc + mysql_result($req3,$i,bonus); }
		if(mysql_result($req3,$i,nature)=="tir") { $tirc = $tirc + mysql_result($req3,$i,bonus); }
		if(mysql_result($req3,$i,nature)=="combat") { $combatc = $combatc + mysql_result($req3,$i,bonus); }
		if(mysql_result($req3,$i,nature)=="observation") { $observationc = $observationc + mysql_result($req3,$i,bonus); }
		}
	}

// actions : attaque1 - attaque2 - deplacement - fuite - rien - fatigue - police - admin - securite - distance - lieu - chargeur - detruit
$action2 = "rien";
if($imp==1)
	{
	}
elseif($_GET['action']=="attaquer")
	{
	//caméra de police
	if($_SESSION['num']<=0 || $camerac=="Pol")
		{
		if (!$monEvent)
			{
			augmente_recherche($_SESSION['id'], "citoyen");
			$sql = 'INSERT INTO crimes_tbl(pseudo,date,type,valeur) VALUES("'.$_SESSION['pseudo'].'","'.time().'","Attaque","'.$cible.'")' ;
			$req = mysql_query($sql);
			}
		}
	
	//envoi d'un SMS à la cible
	if(ereg("attaque",$evenementsSMSc))
		{
		$SMSok = 1;
		}
	if(ereg("attaque",$SMSdjc))
		{
		$SMSok = 0;
		}
	if(strlen($telephonec)!=8)
		{
		$SMSok = 0;
		}
	if($connecc=="oui")
		{
		$SMSok = 0;
		}
	if(($SMSc>0) && ($SMSok==1))
		{
		$smsbox_user = 'club21'; // Votre identifiant SMSBOX
		$smsbox_pass = 'mspixel01'; // Votre mot de passe SMSBOX
		$api_type = 'php'; // Ne pas changer
		$api_path = "https://api.smsbox.fr/api.$api_type"; // Ne pas changer
		function sendSMS($to, $message, $from, $mode='expert'){
			global $smsbox_user, $smsbox_pass, $api_path;
			return @file_get_contents("$api_path?login=$smsbox_user&pass=$smsbox_pass&msg=".rawurlencode($message)."&dest=$to&mode=$mode&origine=".rawurlencode($from));
		}
		$SMSc = $SMSc - 1;
		$retour = sendSMS("06".$telephonec, "- Dreadcast Infos - Vous êtes attaqué ! Connectez-vous pour connaître les détails du combat.", 'Dreadcast');
		$sql = 'UPDATE principal_tbl SET SMS= "'.$SMSc.'" , SMSdj= "'.$SMSdjc.' attaque" WHERE id= "'.$idc.'"';
		$req = mysql_query($sql);
		}
	
	//attaque
	if($type1=="armesav" || $type1=="armestir" || $type1=="armtu")
		{
		$action1="attaque1";
		$comp1 = $_SESSION['tir'];
		$bonus1 = $bonus1."tir";
		}
	else
		{
		$action1="attaque2";
		$comp1 = $_SESSION['combat'];
		$bonus1 = $bonus1."combat";
		$ecart1 = $ecart1 + ceil($_SESSION['combat']/4);
		}
	
	//MODES
	if($mode1=="s")
		{
		$n_tir = 1;
		}
	elseif($mode1=="b")
		{
		$n_tir = 3;
		}
	elseif($mode1=="a")
		{
		$n_tir = 9;
		}
	if($n_tir>$chargeur1 && $arme1!="Lance flammes" && $arme1!="Lance roquette")
		{
		$n_tir = $chargeur1;
		}
	for($c=1;$c!=$n_tir*2+1;$c=$c+2)
		{
		//touche
		if($distance1<3)
			{
			$touche = 1;
			}
		else
			{
			$test = $comp1 - $_SESSION['distance'] + rand(-20,20);
			if($test>0)
				{
				$touche = 1;
				}
			else
				{
				$_SESSION['j'.$c.''] = "-1";
				$_SESSION['j'.$c.'max'] = "-1";
				}
			}
		
		if($touche==1)
			{
			//degats
			$_SESSION['j'.$c.''] = ceil(rand($puissance1,$puissance1+$ecart1) - ceil($resistancec/10));
			if($_SESSION['j'.$c.'']<0) { $_SESSION['j'.$c.''] = 0; }
			$_SESSION['j'.$c.'max'] = ceil($puissance1 + $ecart1 - ceil($resistancec/10));
			if($_SESSION['j'.$c.'max']<0) { $_SESSION['j'.$c.'max'] = 0; }
			$bonus2 = $bonus2."resistance";
			if ($_SESSION['pseudo'] == 'Paracelse')
				{
				$deg = ceil($_GET['degats'] + mt_rand(-10, 10));
				$degats += $deg;
				$_SESSION['j'.$c.''] = $deg;
				$_SESSION['j'.$c.'max'] = $deg;
				}
			else
				{
				$degats += $_SESSION['j'.$c.''];
				}
			}
		else
			{
			$_SESSION['j'.$c.''] = -1;
			$_SESSION['j'.$c.'max'] = -1;
			}
		
		//durabilite et chargeur
		if($armeunique1==1)
			{
			if($modif11==6 || $modif12==6 || $modif13==6) { $usure1 = $usure1 - rand(0,1); }
			else { $usure1 = $usure1 - rand(0,2); }
			if($arme1!="Lance flammes")
				{
				$chargeur1 = $chargeur1 - 1;
				$sql2 = 'UPDATE armes_tbl SET chargeur= "'.$chargeur1.'" WHERE idarme= "'.$idarme1.'"' ;
				$req2 = mysql_query($sql2);
				}
			if($usure1>0)
				{
				$sql2 = 'UPDATE armes_tbl SET usure= "'.$usure1.'" WHERE idarme= "'.$idarme1.'"' ;
				$req2 = mysql_query($sql2);
				}
			else
				{
				$sql2 = 'DELETE FROM armes_tbl WHERE idarme= "'.$idarme1.'"' ;
				$req2 = mysql_query($sql2);
				$sql2 = 'UPDATE principal_tbl SET arme= "Aucune" WHERE pseudo= "'.$_SESSION['pseudo'].'"' ;
				$req2 = mysql_query($sql2);
				$action1 = "detruit";
				}
			if($arme1=="Lance roquette")
				{
				$sql2 = 'DELETE FROM armes_tbl WHERE idarme= "'.$idarme1.'"' ;
				$req2 = mysql_query($sql2);
				$sql2 = 'UPDATE principal_tbl SET arme= "Aucune" WHERE pseudo= "'.$_SESSION['pseudo'].'"' ;
				$req2 = mysql_query($sql2);
				}
			}
		}
	
	//retrait des points des vie
	$santec = $santec - $degats;
	if($santec<=0)
		{
		$santec = 0;
		if($protege != "") $update = ', action = "Aucune"';
		$sql = 'INSERT INTO messages_tbl(auteur,cible,objet,message,moment) VALUES("Dreadcast","'.$protege.'","Fin de protection","<br />Votre garde du corps a été mis KO.",'.time().')' ;
		mysql_query($sql);
		}
	
	$sql2 = 'UPDATE principal_tbl SET sante= "'.$santec.'" '.$update.' WHERE id= "'.$idc.'"' ;
	$req2 = mysql_query($sql2);
	
	if($protege != "") enregistre($cible,"protection","+1");
	enregistre($cible,"acc_degats_recus","+".$degats);
	if ($cible == "Paracelse")
		enregistre($_SESSION['pseudo'],"acc_degats_paracelse","+".$degats);
	enregistre($_SESSION['pseudo'],"acc_degats_donnes","+".$degats);
	if ($sonEvent) enregistre($_SESSION['pseudo'],"attaque droide fou","+1");
	
	$degats = 0;

	//différentes réactions
	if($rattaquec=="Attaquer" || $rattaquec=="Aucune")
		{
		//RIPOSTE
		$riposte = 1;
		}
	elseif($rattaquec=="Fuir" || $rattaquec=="Police")
		{
		//FUITE
		$fuite = 1;
		}
	}
elseif($_GET['action']=="approcher")
	{
	//approche
	$action1 = "deplacement";
	$_SESSION['j1'] = rand(1,$depmax);
	$_SESSION['j1max'] = $depmax;
	$_SESSION['distance'] = $_SESSION['distance'] - $_SESSION['j1'];
	if($_SESSION['distance']<1) { $_SESSION['distance'] = 1; }
	
	//detection de l'approche
	$test = $_SESSION['distance']*10+$_SESSION['discretion']-$observationc-rand(10,100);
	if($test<0)
		{
		//différentes réactions
		if($rapprochec=="Attaquer" || $rapprochec=="Aucune")
			{
			//RIPOSTE
			$riposte = 1;
			}
		elseif($rapprochec=="Fuir")
			{
			//FUITE
			$fuite = 1;
			}
		}
	elseif($_SESSION['a'.$idc.'']!="")
		{
		//ATTAQUE EN COURS
		//différentes réactions
		if($rapprochec=="Attaquer" || $rapprochec=="Aucune")
			{
			//RIPOSTE
			$riposte = 1;
			}
		elseif($rapprochec=="Fuir")
			{
			//FUITE
			$fuite = 1;
			}
		}
	}
elseif($_GET['action']=="eloigner")
	{
	//eloignement
	$action1 = "deplacement";
	$_SESSION['j1'] = rand(-1,-$depmax);
	$_SESSION['j1max'] = -$depmax;
	$_SESSION['distance'] = $_SESSION['distance'] - $_SESSION['j1'];
	
	//perte de vue
	$test = $_SESSION['observation'] + rand(10,30) - $discretionc - $_SESSION['distance'];
	if($test<0)
		{
		$action2 = "fuite";
		$_SESSION['personnes'] = "";
		}
	}
elseif($_GET['action']=="voler")
	{
	if(empty($_SESSION['a'.$idc.'']))
		{
		//envoi d'un SMS à la cible
		if(ereg("vol",$evenementsSMSc))
			{
			$SMSok = 1;
			}
		if(ereg("vol",$SMSdjc))
			{
			$SMSok = 0;
			}
		if(strlen($telephonec)!=8)
			{
			$SMSok = 0;
			}
		if($connecc=="oui")
			{
			$SMSok = 0;
			}
		if(($SMSc>0) && ($SMSok==1))
			{
			$smsbox_user = 'club21'; // Votre identifiant SMSBOX
			$smsbox_pass = 'mspixel01'; // Votre mot de passe SMSBOX
			$api_type = 'php'; // Ne pas changer
			$api_path = "https://api.smsbox.fr/api.$api_type"; // Ne pas changer
			function sendSMS($to, $message, $from, $mode='expert'){
				global $smsbox_user, $smsbox_pass, $api_path;
				return @file_get_contents("$api_path?login=$smsbox_user&pass=$smsbox_pass&msg=".rawurlencode($message)."&dest=$to&mode=$mode&origine=".rawurlencode($from));
			}
			$SMSc = $SMSc - 1;
			$retour = sendSMS("06".$telephonec, "- Dreadcast Infos - Quelqu\'un tente de vous voler ! Connectez-vous pour avoir plus de détails.", 'Dreadcast');
			$sql = 'UPDATE principal_tbl SET SMS= "'.$SMSc.'" , SMSdj= "'.$SMSdjc.' vol" WHERE id= "'.$idc.'"';
			$req = mysql_query($sql);
			}

		//vol
		$action1 = "vol";
		$test = ($_SESSION['discretion'] + $_SESSION['vol']) / 2 - $observationc + rand(-20,20);
		$bonus1 = $bonus1."vol";
		$bonus2 = $bonus2."observation";
		if($test<0)
			{
			//différentes réactions au vol
			if($rvolc=="Aucune" || $rvolc=="Attaquer")
				{
				//RIPOSTE
				$riposte = 1;
				}
			elseif($rvolc=="Police")
				{
				//FUITE
				$rattaquec = "Police";
				$fuite = 1;
				}
			}
		else
			{
			//le vol n'est pas repéré
			$_SESSION['v'.$idc.''] = 1;
			print('<meta http-equiv="refresh" content="0 ; url=http://v2.dreadcast.net/ingame/engine=vol.php?cible='.$idc.'"> ');
			exit();
			}
		}
	}
elseif($_GET['action']=="arrestation")
	{
	//envoi d'un SMS à la cible
	if(ereg("arrestation",$evenementsSMSc))
		{
		$SMSok = 1;
		}
	if(ereg("arrestation",$SMSdjc))
		{
		$SMSok = 0;
		}
	if(strlen($telephonec)!=8)
		{
		$SMSok = 0;
		}
	if($connecc=="oui")
		{
		$SMSok = 0;
		}
	if(($SMSc>0) && ($SMSok==1))
		{
		$smsbox_user = 'club21'; // Votre identifiant SMSBOX
		$smsbox_pass = 'mspixel01'; // Votre mot de passe SMSBOX
		$api_type = 'php'; // Ne pas changer
		$api_path = "https://api.smsbox.fr/api.$api_type"; // Ne pas changer
		function sendSMS($to, $message, $from, $mode='expert'){
			global $smsbox_user, $smsbox_pass, $api_path;
			return @file_get_contents("$api_path?login=$smsbox_user&pass=$smsbox_pass&msg=".rawurlencode($message)."&dest=$to&mode=$mode&origine=".rawurlencode($from));
		}
		$SMSc = $SMSc - 1;
		$retour = sendSMS("06".$telephonec, "- Dreadcast Infos - Un agent de police vous place en état d\'arrestation ! Connectez-vous pour avoir plus de détails.", 'Dreadcast');
		$sql = 'UPDATE principal_tbl SET SMS= "'.$SMSc.'" , SMSdj= "'.$SMSdjc.' arrestation" WHERE id= "'.$idc.'"';
		$req = mysql_query($sql);
		}

	//arrestation
	$action1 = "arrestation";
	//différentes réactions a l'arrestation
	print('Santé: '.$santec);
	if($santec>0)
		{
		if($rpolicec=="Aucune" || $rpolicec=="Accepter")
			{
			//ACCEPTE
			$_SESSION['r'.$idc.''] = 1;
			print('<meta http-equiv="refresh" content="0 ; url=http://v2.dreadcast.net/ingame/engine=arrestation.php?'.$cible.'"> ');
			exit();
			}
		elseif($rpolicec=="Attaquer")
			{
			//RIPOSTE
			$riposte = 1;
			if (!$monEvent)
				{
				augmente_recherche($idc, "police");
				$sqlp = 'INSERT INTO crimes_tbl(pseudo,date,type,valeur) VALUES("'.$cible.'","'.time().'","Riposte sur agent","'.$_SESSION['pseudo'].'")' ;
				$reqp = mysql_query($sqlp);
				}
			}
		elseif($rpolicec=="Fuir")
			{
			//FUITE
			$rattaquec = "Fuir";
			$fuite = 1;
			}
		}
	else
		{
		//ACCEPTE
		$_SESSION['r'.$idc.''] = 1;
		print('<meta http-equiv="refresh" content="0 ; url=http://v2.dreadcast.net/ingame/engine=arrestation.php?'.$cible.'"> ');
		exit();
		}
	}
elseif($_GET['action']=="equipement")
	{
	}

//FUITE
if($fuite==1 || ($_SESSION['a'.$idc.'']=="fuite" && $riposte!=1))
	{
	$_SESSION['a'.$idc.''] = "fuite";
	$action2 = "deplacement";
	$_SESSION['j2'] = rand(-2,-$depmaxc*2);
	$_SESSION['j2max'] = -$depmaxc*2;
	$_SESSION['distance'] = $_SESSION['distance'] - $_SESSION['j2'];
	
	//perte de vue
	$test = $_SESSION['observation'] + rand(10,30) - $discretionc - $_SESSION['distance'];
	if($test<0)
		{
		$bonus2 = $bonus2."discretion";
		$action2 = "fuite";
		$_SESSION['personnes'] = "";
		//fuit là ou il veut fuire
		if($rattaquec=="Fuir")
			{
			$sql = 'UPDATE principal_tbl SET action= "aucune" WHERE id= "'.$idc.'"';
			$req = mysql_query($sql);
			$sql = 'SELECT numl,ruel,entreprise FROM principal_tbl WHERE id= "'.$idc.'"' ;
			$req = mysql_query($sql);
			$ruelc = mysql_result($req,0,ruel);
			$numlc = mysql_result($req,0,numl);
			if($actionc=="travail" && mysql_result($req,0,entreprise) != "Aucune") verification_ouverture_entreprise(mysql_result($req,0,entreprise));
			
			
			if($ruelc == "Aucune")
				{
				// Si on est en entreprise, on sort dans la rue, si on est dans la rue, on choisir une autre case rue adjacente
				$numrue = coordonnees_sortie_rue($_SESSION['num'],$_SESSION['rue']);
				$numlc = $numrue['num'];
				$ruelc = $numrue['rue'];
				}
			
			deplacement($cible,$numc,$lieuc,$numlc,$ruelc);
			}
		elseif($rattaquec=="Police")
			{
			$sql = 'SELECT num,rue FROM entreprises_tbl WHERE type= "police"' ;
			$req = mysql_query($sql);
			$ruep = mysql_result($req,0,rue);
			$nump = mysql_result($req,0,num);
			if(deplacement($cible,$_SESSION['num'],$_SESSION['rue'],$nump,$ruep) == 'ok')
				{
				$sql = 'SELECT entreprise FROM principal_tbl WHERE id= "'.$idc.'"';
				$req = mysql_query($sql);
				if($actionc=="travail" && mysql_result($req,0,entreprise) != "Aucune") verification_ouverture_entreprise(mysql_result($req,0,entreprise));
				}
			}
		
		}
	}

//RIPOSTE
if(($riposte==1 && ($armeunique2==0 || ($chargeur2>0 || $arme2=="Lance roquette" || $arme2=="Lance flammes"))) || ($_SESSION['a'.$idc.'']=="riposte" && $fuite!=1))
	{
	$_SESSION['a'.$idc.''] = "riposte";
	if($distance2<$_SESSION['distance'])
		{
		//approche
		$action2 = "deplacement";
		$_SESSION['j2'] = rand(1,$depmaxc);
		$_SESSION['j2max'] = $depmaxc;
		$_SESSION['distance'] = $_SESSION['distance'] - $_SESSION['j2'];
		if($_SESSION['distance']<1) { $_SESSION['distance'] = 1; }
		}
	else
		{
		//attaque
		if($type2=="armesav" || $type2=="armestir")
			{
			$action2="attaque1";
			$comp2 = $tirc;
			$bonus2 = $bonus2."tir";
			}
		else
			{
			$action2="attaque2";
			$comp2 = $combatc;
			$bonus2 = $bonus2."combat";
			$ecart2 = $ecart2 + ceil($combatc/4);
			}
		
		//MODES
		if($mode2=="s")
			{
			$n_tir = 1;
			}
		elseif($mode2=="b")
			{
			$n_tir = 3;
			}
		elseif($mode2=="a")
			{
			$n_tir = 9;
			}
		if($n_tir>$chargeur2 && $arme2!="Lance roquette" && $arme2!="Lance flammes")
			{
			$n_tir = $chargeur2;
			}
		for($c=2;$c!=$n_tir*2+2;$c=$c+2)
			{
			//touche
			if($distance2<3)
				{
				$touche = 1;
				}
			else
				{
				$test = $comp2 - $_SESSION['distance'] + rand(-20,20);
				if($test>0)
					{
					$touche = 1;
					}
				else
					{
					$touche = 0;
					$_SESSION['j'.$c.''] = "-1";
					$_SESSION['j'.$c.'max'] = "-1";
					}
				}
			
			if($touche==1)
				{
				//degats
				$_SESSION['j'.$c.''] = ceil(rand($puissance2,$puissance2+$ecart2) - ceil($_SESSION['resistance']/10));
				if($_SESSION['j'.$c.'']<0) { $_SESSION['j'.$c.''] = 0; }
				$_SESSION['j'.$c.'max'] = ceil($puissance2 + $ecart2 - ceil($_SESSION['resistance']/10));
				if($_SESSION['j'.$c.'max']<0) { $_SESSION['j'.$c.'max'] = 0; }
				$bonus1 = $bonus1."resistance";
				
				if ($cible == 'Paracelse')
					{
					$deg = ceil(100 + mt_rand(-10, 10));
					$degats += $deg;
					$_SESSION['j'.$c.''] = $deg;
					$_SESSION['j'.$c.'max'] = $deg;
					}
				else {
					$degats += $_SESSION['j'.$c.''];
					}
				}
			else
				{
				$_SESSION['j'.$c.''] = -1;
				$_SESSION['j'.$c.'max'] = -1;
				}
			
			//durabilite et chargeur
			if($armeunique2==1)
				{
				if($modif21==6 || $modif22==6 || $modif23==6) { $usure2 = $usure2 - rand(0,1); }
				else { $usure2 = $usure2 - rand(0,2); }
				if($arme2!="Lance flammes")
					{
					$chargeur2 = $chargeur2 - 1;
					$sql2 = 'UPDATE armes_tbl SET chargeur= "'.$chargeur2.'" WHERE idarme= "'.$idarme2.'"' ;
					$req2 = mysql_query($sql2);
					}
				if($usure2>0)
					{
					$sql2 = 'UPDATE armes_tbl SET usure= "'.$usure2.'" WHERE idarme= "'.$idarme2.'"' ;
					$req2 = mysql_query($sql2);
					}
				else
					{
					$sql2 = 'DELETE FROM armes_tbl WHERE idarme= "'.$idarme2.'"' ;
					$req2 = mysql_query($sql2);
					$sql2 = 'UPDATE principal_tbl SET arme= "Aucune" WHERE pseudo= "'.$cible.'"' ;
					$req2 = mysql_query($sql2);
					$action2 = "detruit";
					}
				}
			if($arme2=="Lance roquette")
				{
				$sql2 = 'DELETE FROM armes_tbl WHERE idarme= "'.$idarme2.'"' ;
				$req2 = mysql_query($sql2);
				$sql2 = 'UPDATE principal_tbl SET arme= "Aucune" WHERE pseudo= "'.$cible.'"' ;
				$req2 = mysql_query($sql2);
				}
			}
		//retrait des points de vie
		$_SESSION['sante'] = $_SESSION['sante'] - $degats;
		
		if($_SESSION['sante']<0) $_SESSION['sante'] = 0;
		
		$sql2 = 'UPDATE principal_tbl SET sante= "'.$_SESSION['sante'].'" WHERE id= "'.$_SESSION['id'].'"' ;
		$req2 = mysql_query($sql2);
		
		enregistre($cible,"acc_degats_donnes","+".$degats);
		enregistre($_SESSION['pseudo'],"acc_degats_recus","+".$degats);
		}
	}
elseif($riposte==1 && $armeunique2==1 && $chargeur2==0)
	{
	$action2 = "chargeur";
	}

//FLASH
$j1 = $_SESSION['j1'];
$j1max = $_SESSION['j1max'];
$j2 = $_SESSION['j2'];
$j2max = $_SESSION['j2max'];
$j3 = $_SESSION['j3'];
$j3max = $_SESSION['j3max'];
$j4 = $_SESSION['j4'];
$j4max = $_SESSION['j4max'];
$j5 = $_SESSION['j5'];
$j5max = $_SESSION['j5max'];
$j6 = $_SESSION['j6'];
$j6max = $_SESSION['j6max'];
$j7 = $_SESSION['j7'];
$j7max = $_SESSION['j7max'];
$j8 = $_SESSION['j8'];
$j8max = $_SESSION['j8max'];
$j9 = $_SESSION['j9'];
$j9max = $_SESSION['j9max'];
$j10 = $_SESSION['j10'];
$j10max = $_SESSION['j10max'];
$j11 = $_SESSION['j11'];
$j11max = $_SESSION['j11max'];
$j12 = $_SESSION['j12'];
$j12max = $_SESSION['j12max'];
$j13 = $_SESSION['j13'];
$j13max = $_SESSION['j13max'];
$j14 = $_SESSION['j14'];
$j14max = $_SESSION['j14max'];
$j15 = $_SESSION['j15'];
$j15max = $_SESSION['j15max'];
$j16 = $_SESSION['j16'];
$j16max = $_SESSION['j16max'];
$j17 = $_SESSION['j17'];
$j17max = $_SESSION['j17max'];
$j18 = $_SESSION['j18'];
$j18max = $_SESSION['j18max'];

unset($_SESSION['j1'],$_SESSION['j1max'],$_SESSION['j2'],$_SESSION['j2max'],$_SESSION['j3'],$_SESSION['j3max'],$_SESSION['j4'],$_SESSION['j4max'],$_SESSION['j5'],$_SESSION['j5max'],$_SESSION['j6'],$_SESSION['j6max'],$_SESSION['j7'],$_SESSION['j7max'],$_SESSION['j8'],$_SESSION['j8max'],$_SESSION['j9'],$_SESSION['j9max'],$_SESSION['j10'],$_SESSION['j10max'],$_SESSION['j11'],$_SESSION['j11max'],$_SESSION['j12'],$_SESSION['j12max'],$_SESSION['j13'],$_SESSION['j13max'],$_SESSION['j14'],$_SESSION['j14max'],$_SESSION['j15'],$_SESSION['j15max'],$_SESSION['j16'],$_SESSION['j16max'],$_SESSION['j17'],$_SESSION['j17max'],$_SESSION['j18'],$_SESSION['j18max']);

$j1tot = $j1 + $j3 + $j5 + $j7 + $j9 + $j11 + $j13 + $j15 + $j17;
$j2tot = $j2 + $j4 + $j6 + $j8 + $j10 + $j12 + $j14 + $j16 + $j18;

//envoi des messages
$savoir = $_SESSION['discretion'] - $observationc + rand(-20,20);
if($action1=="attaque1" || $action1=="attaque2")
	{
	if($j1>=0)
		{
		if($action2=="attaque1" || $action2=="attaque2")
			{
			if($j2>=0)
				{
				if($savoir<=0)
					{
					$sqls = 'INSERT INTO messages_tbl(id,auteur,cible,message,objet,moment,nouveau) VALUES("","Dreadcast","'.$cible.'","<br /><br /><br /><strong>Quelqu\'un vous attaque !</strong><br>En regardant plus pr&egrave;s, vous le reconnaissez. Il s\'agit de <i>'.$_SESSION['pseudo'].'</i>.<br /><br />Vous vous situez à '.$_SESSION['distance'].'m de lui.<br /><br />Il vous inflige <i>'.$j1tot.'</i> pts de d&eacute;g&acirc;ts.<br />Vous ripostez et lui infligez <i>'.$j2tot.'</i> pts de d&eacute;g&acirc;ts.","Vous êtes attaqué !","'.time().'","oui")';
					$reqs = mysql_query($sqls);
					}
				else
					{
					$sqls = 'INSERT INTO messages_tbl(id,auteur,cible,message,objet,moment,nouveau) VALUES("","Dreadcast","'.$cible.'","<br /><br /><br /><strong>Quelqu\'un vous attaque !</strong><br /><br />Vous vous situez à '.$_SESSION['distance'].'m de lui.<br /><br />Il vous inflige <i>'.$j1tot.'</i> pts de d&eacute;g&acirc;ts.<br />Vous ripostez et lui infligez <i>'.$j2tot.'</i> pts de d&eacute;g&acirc;ts.","Vous êtes attaqué !","'.time().'","oui")';
					$reqs = mysql_query($sqls);
					}
				}
			else
				{
				if($savoir<=0)
					{
					$sqls = 'INSERT INTO messages_tbl(id,auteur,cible,message,objet,moment,nouveau) VALUES("","Dreadcast","'.$cible.'","<br /><br /><br /><strong>Quelqu\'un vous attaque !</strong><br>En regardant plus pr&egrave;s, vous le reconnaissez. Il s\'agit de <i>'.$_SESSION['pseudo'].'</i>.<br /><br />Vous vous situez à '.$_SESSION['distance'].'m de lui.<br /><br />Il vous inflige <i>'.$j1tot.'</i> pts de d&eacute;g&acirc;ts.<br />Vous ripostez mais vous le ratez.","Vous êtes attaqué !","'.time().'","oui")';
					$reqs = mysql_query($sqls);
					}
				else
					{
					$sqls = 'INSERT INTO messages_tbl(id,auteur,cible,message,objet,moment,nouveau) VALUES("","Dreadcast","'.$cible.'","<br /><br /><br /><strong>Quelqu\'un vous attaque !</strong><br /><br />Vous vous situez à '.$_SESSION['distance'].'m de lui.<br /><br />Il vous inflige <i>'.$j1tot.'</i> pts de d&eacute;g&acirc;ts.<br />Vous ripostez mais vous le ratez.","Vous êtes attaqué !","'.time().'","oui")';
					$reqs = mysql_query($sqls);
					}
				}
			}
		elseif($action2=="deplacement" && $j2>0)
			{
			if($savoir<=0)
				{
				$sqls = 'INSERT INTO messages_tbl(id,auteur,cible,message,objet,moment,nouveau) VALUES("","Dreadcast","'.$cible.'","<br /><br /><br /><strong>Quelqu\'un vous attaque !</strong><br>En regardant plus pr&egrave;s, vous le reconnaissez. Il s\'agit de <i>'.$_SESSION['pseudo'].'</i>.<br /><br />Vous vous situez à '.$_SESSION['distance'].'m de lui.<br /><br />Il vous inflige <i>'.$j1tot.'</i> pts de d&eacute;g&acirc;ts.<br />Vous vous approchez de lui de '.$j2tot.'m.","Vous êtes attaqué !","'.time().'","oui")';
				$reqs = mysql_query($sqls);
				}
			else
				{
				$sqls = 'INSERT INTO messages_tbl(id,auteur,cible,message,objet,moment,nouveau) VALUES("","Dreadcast","'.$cible.'","<br /><br /><br /><strong>Quelqu\'un vous attaque !</strong><br /><br />Vous vous situez à '.$_SESSION['distance'].'m de lui.<br /><br />Il vous inflige <i>'.$j1tot.'</i> pts de d&eacute;g&acirc;ts.<br />Vous vous approchez de lui de '.$j2tot.'m.","Vous êtes attaqué !","'.time().'","oui")';
				$reqs = mysql_query($sqls);
				}
			}
		elseif($action2=="deplacement" && $j2<0)
			{
			if($savoir<=0)
				{
				$sqls = 'INSERT INTO messages_tbl(id,auteur,cible,message,objet,moment,nouveau) VALUES("","Dreadcast","'.$cible.'","<br /><br /><br /><strong>Quelqu\'un vous attaque !</strong><br>En regardant plus pr&egrave;s, vous le reconnaissez. Il s\'agit de <i>'.$_SESSION['pseudo'].'</i>.<br /><br />Vous vous situez à '.$_SESSION['distance'].'m de lui.<br /><br />Il vous inflige <i>'.$j1tot.'</i> pts de d&eacute;g&acirc;ts.<br />Vous vous éloignez de lui de '.$j2tot.'m.","Vous êtes attaqué !","'.time().'","oui")';
				$reqs = mysql_query($sqls);
				}
			else
				{
				$sqls = 'INSERT INTO messages_tbl(id,auteur,cible,message,objet,moment,nouveau) VALUES("","Dreadcast","'.$cible.'","<br /><br /><br /><strong>Quelqu\'un vous attaque !</strong><br /><br />Vous vous situez à '.$_SESSION['distance'].'m de lui.<br /><br />Il vous inflige <i>'.$j1tot.'</i> pts de d&eacute;g&acirc;ts.<br />Vous vous éloignez de lui de '.$j2tot.'m.","Vous êtes attaqué !","'.time().'","oui")';
				$reqs = mysql_query($sqls);
				}
			}
		elseif($action2=="fuite")
			{
			if($savoir<=0)
				{
				$sqls = 'INSERT INTO messages_tbl(id,auteur,cible,message,objet,moment,nouveau) VALUES("","Dreadcast","'.$cible.'","<br /><br /><br /><strong>Quelqu\'un vous attaque !</strong><br>En regardant plus pr&egrave;s, vous le reconnaissez. Il s\'agit de <i>'.$_SESSION['pseudo'].'</i>.<br /><br />Vous vous situez à '.$_SESSION['distance'].'m de lui.<br /><br />Il vous inflige <i>'.$j1tot.'</i> pts de d&eacute;g&acirc;ts.<br />Vous parvenez à fuir !","Vous êtes attaqué !","'.time().'","oui")';
				$reqs = mysql_query($sqls);
				}
			else
				{
				$sqls = 'INSERT INTO messages_tbl(id,auteur,cible,message,objet,moment,nouveau) VALUES("","Dreadcast","'.$cible.'","<br /><br /><br /><strong>Quelqu\'un vous attaque !</strong><br /><br />Vous vous situez à '.$_SESSION['distance'].'m de lui.<br /><br />Il vous inflige <i>'.$j1tot.'</i> pts de d&eacute;g&acirc;ts.<br />Vous parvenez à fuir !","Vous êtes attaqué !","'.time().'","oui")';
				$reqs = mysql_query($sqls);
				}
			}
		elseif($action2=="chargeur")
			{
			if($savoir<=0)
				{
				$sqls = 'INSERT INTO messages_tbl(id,auteur,cible,message,objet,moment,nouveau) VALUES("","Dreadcast","'.$cible.'","<br /><br /><br /><strong>Quelqu\'un vous attaque !</strong><br>En regardant plus pr&egrave;s, vous le reconnaissez. Il s\'agit de <i>'.$_SESSION['pseudo'].'</i>.<br /><br />Vous vous situez à '.$_SESSION['distance'].'m de lui.<br /><br />Il vous inflige <i>'.$j1tot.'</i> pts de d&eacute;g&acirc;ts.<br />Votre chargeur est vide !","Vous êtes attaqué !","'.time().'","oui")';
				$reqs = mysql_query($sqls);
				}
			else
				{
				$sqls = 'INSERT INTO messages_tbl(id,auteur,cible,message,objet,moment,nouveau) VALUES("","Dreadcast","'.$cible.'","<br /><br /><br /><strong>Quelqu\'un vous attaque !</strong><br /><br />Vous vous situez à '.$_SESSION['distance'].'m de lui.<br /><br />Il vous inflige <i>'.$j1tot.'</i> pts de d&eacute;g&acirc;ts.<br />Votre chargeur est vide !","Vous êtes attaqué !","'.time().'","oui")';
				$reqs = mysql_query($sqls);
				}
			}
		}
	else
		{
		if($action2=="attaque1" || $action2=="attaque2")
			{
			if($j2>=0)
				{
				if($savoir<=0)
					{
					$sqls = 'INSERT INTO messages_tbl(id,auteur,cible,message,objet,moment,nouveau) VALUES("","Dreadcast","'.$cible.'","<br /><br /><br /><strong>Quelqu\'un vous attaque !</strong><br>En regardant plus pr&egrave;s, vous le reconnaissez. Il s\'agit de <i>'.$_SESSION['pseudo'].'</i>.<br /><br />Vous vous situez à '.$_SESSION['distance'].'m de lui.<br /><br />Il vous rate.<br />Vous ripostez et lui infligez <i>'.$j2tot.'</i> pts de d&eacute;g&acirc;ts.","Vous êtes attaqué !","'.time().'","oui")';
					$reqs = mysql_query($sqls);
					}
				else
					{
					$sqls = 'INSERT INTO messages_tbl(id,auteur,cible,message,objet,moment,nouveau) VALUES("","Dreadcast","'.$cible.'","<br /><br /><br /><strong>Quelqu\'un vous attaque !</strong><br /><br />Vous vous situez à '.$_SESSION['distance'].'m de lui.<br /><br />Il vous rate.<br />Vous ripostez et lui infligez <i>'.$j2tot.'</i> pts de d&eacute;g&acirc;ts.","Vous êtes attaqué !","'.time().'","oui")';
					$reqs = mysql_query($sqls);
					}
				}
			else
				{
				if($savoir<=0)
					{
					$sqls = 'INSERT INTO messages_tbl(id,auteur,cible,message,objet,moment,nouveau) VALUES("","Dreadcast","'.$cible.'","<br /><br /><br /><strong>Quelqu\'un vous attaque !</strong><br>En regardant plus pr&egrave;s, vous le reconnaissez. Il s\'agit de <i>'.$_SESSION['pseudo'].'</i>.<br /><br />Vous vous situez à '.$_SESSION['distance'].'m de lui.<br /><br />Il vous rate.<br />Vous ripostez mais vous le ratez.","Vous êtes attaqué !","'.time().'","oui")';
					$reqs = mysql_query($sqls);
					}
				else
					{
					$sqls = 'INSERT INTO messages_tbl(id,auteur,cible,message,objet,moment,nouveau) VALUES("","Dreadcast","'.$cible.'","<br /><br /><br /><strong>Quelqu\'un vous attaque !</strong><br /><br />Vous vous situez à '.$_SESSION['distance'].'m de lui.<br /><br />Il vous rate.<br />Vous ripostez mais vous le ratez.","Vous êtes attaqué !","'.time().'","oui")';
					$reqs = mysql_query($sqls);
					}
				}
			}
		elseif($action2=="deplacement" && $j2>0)
			{
			if($savoir<=0)
				{
				$sqls = 'INSERT INTO messages_tbl(id,auteur,cible,message,objet,moment,nouveau) VALUES("","Dreadcast","'.$cible.'","<br /><br /><br /><strong>Quelqu\'un vous attaque !</strong><br>En regardant plus pr&egrave;s, vous le reconnaissez. Il s\'agit de <i>'.$_SESSION['pseudo'].'</i>.<br /><br />Vous vous situez à '.$_SESSION['distance'].'m de lui.<br /><br />Il vous rate.<br />Vous vous approchez de lui de '.$j2tot.'m.","Vous êtes attaqué !","'.time().'","oui")';
				$reqs = mysql_query($sqls);
				}
			else
				{
				$sqls = 'INSERT INTO messages_tbl(id,auteur,cible,message,objet,moment,nouveau) VALUES("","Dreadcast","'.$cible.'","<br /><br /><br /><strong>Quelqu\'un vous attaque !</strong><br /><br />Vous vous situez à '.$_SESSION['distance'].'m de lui.<br /><br />Il vous rate.<br />Vous vous approchez de lui de '.$j2tot.'m.","Vous êtes attaqué !","'.time().'","oui")';
				$reqs = mysql_query($sqls);
				}
			}
		elseif($action2=="deplacement" && $j2<0)
			{
			if($savoir<=0)
				{
				$sqls = 'INSERT INTO messages_tbl(id,auteur,cible,message,objet,moment,nouveau) VALUES("","Dreadcast","'.$cible.'","<br /><br /><br /><strong>Quelqu\'un vous attaque !</strong><br>En regardant plus pr&egrave;s, vous le reconnaissez. Il s\'agit de <i>'.$_SESSION['pseudo'].'</i>.<br /><br />Vous vous situez à '.$_SESSION['distance'].'m de lui.<br /><br />Il vous rate.<br />Vous vous éloignez de lui de '.$j2tot.'m.","Vous êtes attaqué !","'.time().'","oui")';
				$reqs = mysql_query($sqls);
				}
			else
				{
				$sqls = 'INSERT INTO messages_tbl(id,auteur,cible,message,objet,moment,nouveau) VALUES("","Dreadcast","'.$cible.'","<br /><br /><br /><strong>Quelqu\'un vous attaque !</strong><br /><br />Vous vous situez à '.$_SESSION['distance'].'m de lui.<br /><br />Il vous rate.<br />Vous vous éloignez de lui de '.$j2tot.'m.","Vous êtes attaqué !","'.time().'","oui")';
				$reqs = mysql_query($sqls);
				}
			}
		elseif($action2=="fuite")
			{
			if($savoir<=0)
				{
				$sqls = 'INSERT INTO messages_tbl(id,auteur,cible,message,objet,moment,nouveau) VALUES("","Dreadcast","'.$cible.'","<br /><br /><br /><strong>Quelqu\'un vous attaque !</strong><br>En regardant plus pr&egrave;s, vous le reconnaissez. Il s\'agit de <i>'.$_SESSION['pseudo'].'</i>.<br /><br />Vous vous situez à '.$_SESSION['distance'].'m de lui.<br /><br />Il vous rate.<br />Vous parvenez à fuir !","Vous êtes attaqué !","'.time().'","oui")';
				$reqs = mysql_query($sqls);
				}
			else
				{
				$sqls = 'INSERT INTO messages_tbl(id,auteur,cible,message,objet,moment,nouveau) VALUES("","Dreadcast","'.$cible.'","<br /><br /><br /><strong>Quelqu\'un vous attaque !</strong><br /><br />Vous vous situez à '.$_SESSION['distance'].'m de lui.<br /><br />Il vous rate.<br />Vous parvenez à fuir !","Vous êtes attaqué !","'.time().'","oui")';
				$reqs = mysql_query($sqls);
				}
			}
		elseif($action2=="chargeur")
			{
			if($savoir<=0)
				{
				$sqls = 'INSERT INTO messages_tbl(id,auteur,cible,message,objet,moment,nouveau) VALUES("","Dreadcast","'.$cible.'","<br /><br /><br /><strong>Quelqu\'un vous attaque !</strong><br>En regardant plus pr&egrave;s, vous le reconnaissez. Il s\'agit de <i>'.$_SESSION['pseudo'].'</i>.<br /><br />Vous vous situez à '.$_SESSION['distance'].'m de lui.<br /><br />Il vous rate.<br />Votre chargeur est vide !","Vous êtes attaqué !","'.time().'","oui")';
				$reqs = mysql_query($sqls);
				}
			else
				{
				$sqls = 'INSERT INTO messages_tbl(id,auteur,cible,message,objet,moment,nouveau) VALUES("","Dreadcast","'.$cible.'","<br /><br /><br /><strong>Quelqu\'un vous attaque !</strong><br /><br />Vous vous situez à '.$_SESSION['distance'].'m de lui.<br /><br />Il vous rate.<br />Votre chargeur est vide !","Vous êtes attaqué !","'.time().'","oui")';
				$reqs = mysql_query($sqls);
				}
			}
		}
	}
elseif($action1=="deplacement")
	{
	if($action2=="attaque1" || $action2=="attaque2")
		{
		if($j2>=0)
			{
			if($savoir<=0)
				{
				$sqls = 'INSERT INTO messages_tbl(id,auteur,cible,message,objet,moment,nouveau) VALUES("","Dreadcast","'.$cible.'","<br /><br /><br /><strong>Quelqu\'un s\'approche de vous !</strong><br>En regardant plus pr&egrave;s, vous le reconnaissez. Il s\'agit de <i>'.$_SESSION['pseudo'].'</i>.<br /><br />Vous vous situez à '.$_SESSION['distance'].'m de lui.<br /><br />Vous ripostez et lui infligez <i>'.$j2tot.'</i> pts de d&eacute;g&acirc;ts.","Quelqu\'un vient !","'.time().'","oui")';
				$reqs = mysql_query($sqls);
				}
			else
				{
				$sqls = 'INSERT INTO messages_tbl(id,auteur,cible,message,objet,moment,nouveau) VALUES("","Dreadcast","'.$cible.'","<br /><br /><br /><strong>Quelqu\'un s\'approche de vous !</strong><br /><br />Vous vous situez à '.$_SESSION['distance'].'m de lui.<br /><br />Vous ripostez et lui infligez <i>'.$j2tot.'</i> pts de d&eacute;g&acirc;ts.","Quelqu\'un vient !","'.time().'","oui")';
				$reqs = mysql_query($sqls);
				}
			}
		else
			{
			if($savoir<=0)
				{
				$sqls = 'INSERT INTO messages_tbl(id,auteur,cible,message,objet,moment,nouveau) VALUES("","Dreadcast","'.$cible.'","<br /><br /><br /><strong>Quelqu\'un s\'approche de vous !</strong><br>En regardant plus pr&egrave;s, vous le reconnaissez. Il s\'agit de <i>'.$_SESSION['pseudo'].'</i>.<br /><br />Vous vous situez à '.$_SESSION['distance'].'m de lui.<br /><br />Vous ripostez mais vous le ratez.","Quelqu\'un vient !","'.time().'","oui")';
				$reqs = mysql_query($sqls);
				}
			else
				{
				$sqls = 'INSERT INTO messages_tbl(id,auteur,cible,message,objet,moment,nouveau) VALUES("","Dreadcast","'.$cible.'","<br /><br /><br /><strong>Quelqu\'un s\'approche de vous !</strong><br /><br />Vous vous situez à '.$_SESSION['distance'].'m de lui.<br /><br />Vous ripostez mais vous le ratez.","Quelqu\'un vient !","'.time().'","oui")';
				$reqs = mysql_query($sqls);
				}
			}
		}
	elseif($action2=="deplacement" && $j2>0)
		{
		if($savoir<=0)
			{
			$sqls = 'INSERT INTO messages_tbl(id,auteur,cible,message,objet,moment,nouveau) VALUES("","Dreadcast","'.$cible.'","<br /><br /><br /><strong>Quelqu\'un s\'approche de vous !</strong><br>En regardant plus pr&egrave;s, vous le reconnaissez. Il s\'agit de <i>'.$_SESSION['pseudo'].'</i>.<br /><br />Vous vous situez à '.$_SESSION['distance'].'m de lui.<br /><br />Vous vous approchez de lui de '.$j2tot.'m.","Quelqu\'un vient !","'.time().'","oui")';
			$reqs = mysql_query($sqls);
			}
		else
			{
			$sqls = 'INSERT INTO messages_tbl(id,auteur,cible,message,objet,moment,nouveau) VALUES("","Dreadcast","'.$cible.'","<br /><br /><br /><strong>Quelqu\'un s\'approche de vous !</strong><br /><br />Vous vous situez à '.$_SESSION['distance'].'m de lui.<br /><br />Vous vous approchez de lui de '.$j2tot.'m.","Quelqu\'un vient !","'.time().'","oui")';
			$reqs = mysql_query($sqls);
			}
		}
	elseif($action2=="deplacement" && $j2<0)
		{
		if($savoir<=0)
			{
			$sqls = 'INSERT INTO messages_tbl(id,auteur,cible,message,objet,moment,nouveau) VALUES("","Dreadcast","'.$cible.'","<br /><br /><br /><strong>Quelqu\'un s\'approche de vous !</strong><br>En regardant plus pr&egrave;s, vous le reconnaissez. Il s\'agit de <i>'.$_SESSION['pseudo'].'</i>.<br /><br />Vous vous situez à '.$_SESSION['distance'].'m de lui.<br /><br />Vous vous éloignez de lui de '.$j2tot.'m.","Quelqu\'un vient !","'.time().'","oui")';
			$reqs = mysql_query($sqls);
			}
		else
			{
			$sqls = 'INSERT INTO messages_tbl(id,auteur,cible,message,objet,moment,nouveau) VALUES("","Dreadcast","'.$cible.'","<br /><br /><br /><strong>Quelqu\'un s\'approche de vous !</strong><br /><br />Vous vous situez à '.$_SESSION['distance'].'m de lui.<br /><br />Vous vous éloignez de lui de '.$j2tot.'m.","Quelqu\'un vient !","'.time().'","oui")';
			$reqs = mysql_query($sqls);
			}
		}
	elseif($action2=="fuite")
		{
		if($savoir<=0)
			{
			$sqls = 'INSERT INTO messages_tbl(id,auteur,cible,message,objet,moment,nouveau) VALUES("","Dreadcast","'.$cible.'","<br /><br /><br /><strong>Quelqu\'un s\'approche de vous !</strong><br>En regardant plus pr&egrave;s, vous le reconnaissez. Il s\'agit de <i>'.$_SESSION['pseudo'].'</i>.<br /><br />Vous vous situez à '.$_SESSION['distance'].'m de lui.<br /><br />Vous parvenez à fuir !","Quelqu\'un vient !","'.time().'","oui")';
			$reqs = mysql_query($sqls);
			}
		else
			{
			$sqls = 'INSERT INTO messages_tbl(id,auteur,cible,message,objet,moment,nouveau) VALUES("","Dreadcast","'.$cible.'","<br /><br /><br /><strong>Quelqu\'un s\'approche de vous !</strong><br /><br />Vous vous situez à '.$_SESSION['distance'].'m de lui.<br /><br />Vous parvenez à fuir !","Quelqu\'un vient !","'.time().'","oui")';
			$reqs = mysql_query($sqls);
			}
		}
	elseif($action2=="chargeur")
		{
		if($savoir<=0)
			{
			$sqls = 'INSERT INTO messages_tbl(id,auteur,cible,message,objet,moment,nouveau) VALUES("","Dreadcast","'.$cible.'","<br /><br /><br /><strong>Quelqu\'un s\'approche de vous !</strong><br>En regardant plus pr&egrave;s, vous le reconnaissez. Il s\'agit de <i>'.$_SESSION['pseudo'].'</i>.<br /><br />Vous vous situez à '.$_SESSION['distance'].'m de lui.<br /><br />Votre chargeur est vide !","Quelqu\'un vient !","'.time().'","oui")';
			$reqs = mysql_query($sqls);
			}
		else
			{
			$sqls = 'INSERT INTO messages_tbl(id,auteur,cible,message,objet,moment,nouveau) VALUES("","Dreadcast","'.$cible.'","<br /><br /><br /><strong>Quelqu\'un s\'approche de vous !</strong><br /><br />Vous vous situez à '.$_SESSION['distance'].'m de lui.<br /><br />Votre chargeur est vide !","Quelqu\'un vient !","'.time().'","oui")';
			$reqs = mysql_query($sqls);
			}
		}
	}
elseif($action1=="vol")
	{
	if($action2=="attaque1" || $action2=="attaque2")
		{
		if($j2>=0)
			{
			if($savoir<=0)
				{
				$sqls = 'INSERT INTO messages_tbl(id,auteur,cible,message,objet,moment,nouveau) VALUES("","Dreadcast","'.$cible.'","<br /><br /><br /><strong>Quelqu\'un tente de vous voler !</strong><br>En regardant plus pr&egrave;s, vous le reconnaissez. Il s\'agit de <i>'.$_SESSION['pseudo'].'</i>.<br /><br />Vous vous situez à '.$_SESSION['distance'].'m de lui.<br /><br />Vous ripostez et lui infligez <i>'.$j2tot.'</i> pts de d&eacute;g&acirc;ts.","Au voleur !","'.time().'","oui")';
				$reqs = mysql_query($sqls);
				}
			}
		else
			{
			if($savoir<=0)
				{
				$sqls = 'INSERT INTO messages_tbl(id,auteur,cible,message,objet,moment,nouveau) VALUES("","Dreadcast","'.$cible.'","<br /><br /><br /><strong>Quelqu\'un tente de vous voler !</strong><br>En regardant plus pr&egrave;s, vous le reconnaissez. Il s\'agit de <i>'.$_SESSION['pseudo'].'</i>.<br /><br />Vous vous situez à '.$_SESSION['distance'].'m de lui.<br /><br />Vous ripostez mais vous le ratez.","Au voleur !","'.time().'","oui")';
				$reqs = mysql_query($sqls);
				}
			}
		}
	elseif($action2=="deplacement" && $j2>0)
		{
		if($savoir<=0)
			{
			$sqls = 'INSERT INTO messages_tbl(id,auteur,cible,message,objet,moment,nouveau) VALUES("","Dreadcast","'.$cible.'","<br /><br /><br /><strong>Quelqu\'un tente de vous voler !</strong><br>En regardant plus pr&egrave;s, vous le reconnaissez. Il s\'agit de <i>'.$_SESSION['pseudo'].'</i>.<br /><br />Vous vous situez à '.$_SESSION['distance'].'m de lui.<br /><br />Vous vous approchez de lui de '.$j2tot.'m.","Au voleur !","'.time().'","oui")';
			$reqs = mysql_query($sqls);
			}
		}
	elseif($action2=="deplacement" && $j2<0)
		{
		if($savoir<=0)
			{
			$sqls = 'INSERT INTO messages_tbl(id,auteur,cible,message,objet,moment,nouveau) VALUES("","Dreadcast","'.$cible.'","<br /><br /><br /><strong>Quelqu\'un tente de vous voler !</strong><br>En regardant plus pr&egrave;s, vous le reconnaissez. Il s\'agit de <i>'.$_SESSION['pseudo'].'</i>.<br /><br />Vous vous situez à '.$_SESSION['distance'].'m de lui.<br /><br />Vous vous éloignez de lui de '.$j2tot.'m.","Au voleur !","'.time().'","oui")';
			$reqs = mysql_query($sqls);
			}
		}
	elseif($action2=="fuite")
		{
		if($savoir<=0)
			{
			$sqls = 'INSERT INTO messages_tbl(id,auteur,cible,message,objet,moment,nouveau) VALUES("","Dreadcast","'.$cible.'","<br /><br /><br /><strong>Quelqu\'un tente de vous voler !</strong><br>En regardant plus pr&egrave;s, vous le reconnaissez. Il s\'agit de <i>'.$_SESSION['pseudo'].'</i>.<br /><br />Vous vous situez à '.$_SESSION['distance'].'m de lui.<br /><br />Vous parvenez à fuir !","Au voleur !","'.time().'","oui")';
			$reqs = mysql_query($sqls);
			}
		}
	elseif($action2=="chargeur")
		{
		if($savoir<=0)
			{
			$sqls = 'INSERT INTO messages_tbl(id,auteur,cible,message,objet,moment,nouveau) VALUES("","Dreadcast","'.$cible.'","<br /><br /><br /><strong>Quelqu\'un tente de vous voler !</strong><br>En regardant plus pr&egrave;s, vous le reconnaissez. Il s\'agit de <i>'.$_SESSION['pseudo'].'</i>.<br /><br />Vous vous situez à '.$_SESSION['distance'].'m de lui.<br /><br />Votre chargeur est vide !","Au voleur !","'.time().'","oui")';
			$reqs = mysql_query($sqls);
			}
		}
	}
elseif($action1=="arrestation")
	{
	if($action2=="attaque1" || $action2=="attaque2")
		{
		if($j2>=0)
			{
			if($savoir<=0)
				{
				$sqls = 'INSERT INTO messages_tbl(id,auteur,cible,message,objet,moment,nouveau) VALUES("","Dreadcast","'.$cible.'","<br /><br /><br /><strong>Un agent de police vous met en &eacute;tat d\'arrestation !</strong><br>En regardant plus pr&egrave;s, vous le reconnaissez. Il s\'agit de <i>'.$_SESSION['pseudo'].'</i>.<br /><br />Vous vous situez à '.$_SESSION['distance'].'m de lui.<br /><br />Vous ripostez et lui infligez <i>'.$j2tot.'</i> pts de d&eacute;g&acirc;ts.","Au nom de la loi !","'.time().'","oui")';
				$reqs = mysql_query($sqls);
				}
			else
				{
				$sqls = 'INSERT INTO messages_tbl(id,auteur,cible,message,objet,moment,nouveau) VALUES("","Dreadcast","'.$cible.'","<br /><br /><br /><strong>Un agent de police vous met en &eacute;tat d\'arrestation !</strong><br /><br />Vous vous situez à '.$_SESSION['distance'].'m de lui.<br /><br />Vous ripostez et lui infligez <i>'.$j2tot.'</i> pts de d&eacute;g&acirc;ts.","Au nom de la loi !","'.time().'")';
				$reqs = mysql_query($sqls);
				}
			}
		else
			{
			if($savoir<=0)
				{
				$sqls = 'INSERT INTO messages_tbl(id,auteur,cible,message,objet,moment,nouveau) VALUES("","Dreadcast","'.$cible.'","<br /><br /><br /><strong>Un agent de police vous met en &eacute;tat d\'arrestation !</strong><br>En regardant plus pr&egrave;s, vous le reconnaissez. Il s\'agit de <i>'.$_SESSION['pseudo'].'</i>.<br /><br />Vous vous situez à '.$_SESSION['distance'].'m de lui.<br /><br />Vous ripostez mais vous le ratez.","Au nom de la loi !","'.time().'","oui")';
				$reqs = mysql_query($sqls);
				}
			else
				{
				$sqls = 'INSERT INTO messages_tbl(id,auteur,cible,message,objet,moment,nouveau) VALUES("","Dreadcast","'.$cible.'","<br /><br /><br /><strong>Un agent de police vous met en &eacute;tat d\'arrestation !</strong><br /><br />Vous vous situez à '.$_SESSION['distance'].'m de lui.<br /><br />Vous ripostez mais vous le ratez.","Au nom de la loi !","'.time().'","oui")';
				$reqs = mysql_query($sqls);
				}
			}
		}
	elseif($action2=="deplacement" && $j2>0)
		{
		if($savoir<=0)
			{
			$sqls = 'INSERT INTO messages_tbl(id,auteur,cible,message,objet,moment,nouveau) VALUES("","Dreadcast","'.$cible.'","<br /><br /><br /><strong>Un agent de police vous met en &eacute;tat d\'arrestation !</strong><br>En regardant plus pr&egrave;s, vous le reconnaissez. Il s\'agit de <i>'.$_SESSION['pseudo'].'</i>.<br /><br />Vous vous situez à '.$_SESSION['distance'].'m de lui.<br /><br />Vous vous approchez de lui de '.$j2tot.'m.","Au nom de la loi !","'.time().'","oui")';
			$reqs = mysql_query($sqls);
			}
		else
			{
			$sqls = 'INSERT INTO messages_tbl(id,auteur,cible,message,objet,moment,nouveau) VALUES("","Dreadcast","'.$cible.'","<br /><br /><br /><strong>Un agent de police vous met en &eacute;tat d\'arrestation !</strong><br /><br />Vous vous situez à '.$_SESSION['distance'].'m de lui.<br /><br />Vous vous approchez de lui de '.$j2tot.'m.","Au nom de la loi !","'.time().'","oui")';
			$reqs = mysql_query($sqls);
			}
		}
	elseif($action2=="deplacement" && $j2<0)
		{
		if($savoir<=0)
			{
			$sqls = 'INSERT INTO messages_tbl(id,auteur,cible,message,objet,moment,nouveau) VALUES("","Dreadcast","'.$cible.'","<br /><br /><br /><strong>Un agent de police vous met en &eacute;tat d\'arrestation !</strong><br>En regardant plus pr&egrave;s, vous le reconnaissez. Il s\'agit de <i>'.$_SESSION['pseudo'].'</i>.<br /><br />Vous vous situez à '.$_SESSION['distance'].'m de lui.<br /><br />Vous vous éloignez de lui de '.$j2tot.'m.","Au nom de la loi !","'.time().'","oui")';
			$reqs = mysql_query($sqls);
			}
		else
			{
			$sqls = 'INSERT INTO messages_tbl(id,auteur,cible,message,objet,moment,nouveau) VALUES("","Dreadcast","'.$cible.'","<br /><br /><br /><strong>Un agent de police vous met en &eacute;tat d\'arrestation !</strong><br /><br />Vous vous situez à '.$_SESSION['distance'].'m de lui.<br /><br />Vous vous éloignez de lui de '.$j2tot.'m.","Au nom de la loi !","'.time().'","oui")';
			$reqs = mysql_query($sqls);
			}
		}
	elseif($action2=="fuite")
		{
		if($savoir<=0)
			{
			$sqls = 'INSERT INTO messages_tbl(id,auteur,cible,message,objet,moment,nouveau) VALUES("","Dreadcast","'.$cible.'","<br /><br /><br /><strong>Un agent de police vous met en &eacute;tat d\'arrestation !</strong><br>En regardant plus pr&egrave;s, vous le reconnaissez. Il s\'agit de <i>'.$_SESSION['pseudo'].'</i>.<br /><br />Vous vous situez à '.$_SESSION['distance'].'m de lui.<br /><br />Vous parvenez à fuir !","Au nom de la loi !","'.time().'","oui")';
			$reqs = mysql_query($sqls);
			}
		else
			{
			$sqls = 'INSERT INTO messages_tbl(id,auteur,cible,message,objet,moment,nouveau) VALUES("","Dreadcast","'.$cible.'","<br /><br /><br /><strong>Un agent de police vous met en &eacute;tat d\'arrestation !</strong><br /><br />Vous vous situez à '.$_SESSION['distance'].'m de lui.<br /><br />Vous parvenez à fuir !","Au nom de la loi !","'.time().'","oui")';
			$reqs = mysql_query($sqls);
			}
		}
	elseif($action2=="chargeur")
		{
		if($savoir<=0)
			{
			$sqls = 'INSERT INTO messages_tbl(id,auteur,cible,message,objet,moment,nouveau) VALUES("","Dreadcast","'.$cible.'","<br /><br /><br /><strong>Un agent de police vous met en &eacute;tat d\'arrestation !</strong><br>En regardant plus pr&egrave;s, vous le reconnaissez. Il s\'agit de <i>'.$_SESSION['pseudo'].'</i>.<br /><br />Vous vous situez à '.$_SESSION['distance'].'m de lui.<br /><br />Votre chargeur est vide !","Au nom de la loi !","'.time().'","oui")';
			$reqs = mysql_query($sqls);
			}
		else
			{
			$sqls = 'INSERT INTO messages_tbl(id,auteur,cible,message,objet,moment,nouveau) VALUES("","Dreadcast","'.$cible.'","<br /><br /><br /><strong>Un agent de police vous met en &eacute;tat d\'arrestation !</strong><br /><br />Vous vous situez à '.$_SESSION['distance'].'m de lui.<br /><br />Votre chargeur est vide !","Au nom de la loi !","'.time().'","oui")';
			$reqs = mysql_query($sqls);
			}
		}
	}

//retrait des bonus
$sql = 'SELECT * FROM principal_tbl WHERE pseudo= "'.$_SESSION['pseudo'].'"' ;
$req = mysql_query($sql);
$_SESSION['combat'] = mysql_result($req,0,combat);
$_SESSION['vol'] = mysql_result($req,0,vol);
$_SESSION['tir'] = mysql_result($req,0,tir);
$_SESSION['resistance'] = mysql_result($req,0,resistance);
$_SESSION['discretion'] = mysql_result($req,0,discretion);
$_SESSION['observation'] = mysql_result($req,0,observation);

$sql = 'SELECT * FROM principal_tbl WHERE pseudo= "'.$cible.'"' ;
$req = mysql_query($sql);
$combatc = mysql_result($req,0,combat);
$tirc = mysql_result($req,0,tir);
$resistancec = mysql_result($req,0,resistance);
$discretionc = mysql_result($req,0,discretion);
$observationc = mysql_result($req,0,observation);


//gain de compétences
if(ereg("tir",$bonus1)) augmenter_statistique($_SESSION['id'],"tir",$_SESSION['tir']);
if(ereg("combat",$bonus1)) augmenter_statistique($_SESSION['id'],"combat",$_SESSION['combat']);
if(ereg("observation",$bonus1)) augmenter_statistique($_SESSION['id'],"observation",$_SESSION['observation']);
if(ereg("resistance",$bonus1)) augmenter_statistique($_SESSION['id'],"resistance",$_SESSION['resistance']);
if(ereg("vol",$bonus1)) augmenter_statistique($_SESSION['id'],"vol",$_SESSION['vol']);
if(ereg("tir",$bonus2)) augmenter_statistique($idc,"tir",$tirc);
if(ereg("combat",$bonus2)) augmenter_statistique($idc,"combat",$combatc);
if(ereg("resistance",$bonus2)) augmenter_statistique($idc,"resistance",$resistancec);
if(ereg("discretion",$bonus2)) augmenter_statistique($idc,"discretion",$discretionc);

//mort du personnage
if($_SESSION['sante']==0)
	{
		if (event() && estDroide()) {
		} else {
			$_SESSION['action'] = "mort";
			mourir($_SESSION['pseudo'],'Riposte',$cible);
		}
	}

//commentaires
//print($j1.'<br />'.$j2.'<br />'.$j3.'<br />'.$j4);

mysql_close($db);
?>

<div id="haut">
	<?php include("inc_entete.php"); ?>
	<div class="haut_aligngauche">
		<p>
		<div class="titrepage">
			Interaction
		</div>
		<b class="module4ie"><a href="engine.php" class="module4"><img src="im_objets/icon_fuite.gif" alt="Retour" />Fuir</a></b>
</p>
	</div>
</div>
<div id="centre">
	
	<div id="joueur1">
		<p class="cavatar"><?php 
			if(empty($_SESSION['avatar']))$_SESSION['avatar']="";
			
			if((ereg("http",$_SESSION['avatar'])) OR (ereg("ftp",$_SESSION['avatar'])))
				{
				print('<img src="'.$_SESSION['avatar'].'" id="avatar" alt="Mon avatar" border ="0" width="70" height="70" />');
				}
			else
				{
				print('<img src="avatars/'.$_SESSION['avatar'].'" id="avatar" alt="Mon avatar" border ="0" width="70" height="70" />');
				}
			?>
		</p>
		<p class="cpseudo"><?php print $_SESSION['pseudo']; ?></p>
		<p class="cetat"><strong><?php print(sante_etat($_SESSION['sante'],$_SESSION['santemax'])); ?></strong></p>
		<div class="cchoix">
			<p>Actions :</p>
			<?php 
			if($action2!="fuite")
				{
				if($santec>0 && $_SESSION['sante']>0)
					{
					if($distance1>=$_SESSION['distance']) { print('<p class="clien"><a href="engine=attaquer.php?cible='.$cible.'&action=attaquer">Attaquer</a></p>'); }
					if($_SESSION['distance']<="1" && $_SESSION['vol']>0 && $_SESSION['action']!="prison" && $actionc!="prison" && $santec!=0 && empty($_SESSION['a'.$idc.''])) { print('<p class="clien"><a href="engine=attaquer.php?cible='.$cible.'&action=voler">Voler</a></p>'); }
					if($_SESSION['distance']>1) { print('<p class="clien"><a href="engine=attaquer.php?cible='.$cible.'&action=approcher">Se rapprocher</a></p>'); }
					if($_SESSION['case1']=="Menottes" || $_SESSION['case2']=="Menottes" || $_SESSION['case3']=="Menottes" || $_SESSION['case4']=="Menottes" || $_SESSION['case5']=="Menottes" || $_SESSION['case6']=="Menottes") { print('<p class="clien"><a href="engine=attaquer.php?cible='.$cible.'&action=arrestation">Arreter</a></p>'); }
					print('<p class="clien"><a href="engine=attaquer.php?cible='.$cible.'&action=eloigner">S\'éloigner</a></p>');
//					print('<p class="clien"><a href="engine=attaquer.php?cible='.$cible.'&action=equipement">Equipement</a></p>');
					}
				elseif($santec==0 && $_SESSION['sante']>0)
					{
					if($_SESSION['case1']=="Menottes" || $_SESSION['case2']=="Menottes" || $_SESSION['case3']=="Menottes" || $_SESSION['case4']=="Menottes" || $_SESSION['case5']=="Menottes" || $_SESSION['case6']=="Menottes") { print('<p class="clien"><a href="engine=attaquer.php?cible='.$cible.'&action=arrestation">Arreter</a></p>'); }
					print('<p class="clien"><a href="engine=fouiller.php?'.$cible.'">Fouiller</a></p>');
					print('<p class="clien"><a href="engine=achever.php?'.$cible.'">Achever</a></p>');
					}
				}
			else
				{
				print('<p class="clien"><a href="engine.php">Retour</a></p>');
				}
			?>
		</div>
		<p class="carme"><img src="im_objets/icon_<?php ($type1=="armestir" || $type1=="armesav")?print('pistolet'):print('epee'); ?>.gif" alt="Mon arme" /><?php print($arme1) ?></p>
	</div>
	
	<div id="joueur2">
		<p class="cavatar"><?php 
			if(empty($avatar2))$avatar2="";
			
			if((ereg("http",$avatar2)) OR (ereg("ftp",$avatar2)))
				{
				print('<img src="'.$avatar2.'" id="avatar" alt="Son avatar" border ="0" width="70" height="70" />');
				}
			else
				{
				print('<img src="avatars/'.$avatar2.'" id="avatar" alt="Son avatar" border ="0" width="70" height="70" />');
				}
			?>
		</p>
		<p class="cpseudo"><?php print($cible); ?></p>
		<p class="cetat"><strong><?php print(sante_etat($santec,$santecmax)); ?></strong></p>
		<p class="carme"><img src="im_objets/icon_<?php ($type2=="armestir" || $type2=="armesav")?print('pistolet'):print('epee'); ?>.gif" alt="Son arme" /><?php print($arme2) ?></p>
		<p class="cdistance">Distance : <?php print($_SESSION['distance']); ?>m</p>
		<?php
		print('<p class="ccommentaire">');
		if($action1=="attaque1") { print('Vous effectuez une attaque &agrave; distance.<br /><br />'); }
		elseif($action1=="attaque2") { print('Vous effectuez une attaque rapproch&eacute;e.<br /><br />'); }
		elseif($action1=="deplacement") { 
			if($j1>0) print('Vous vous rapprochez de votre cible.<br /><br />'); else print('Vous vous &eacute;loignez de votre cible.<br /><br />'); }
		if($action2=="attaque1") { print('<strong>'.$cible.'</strong> effectue une attaque &agrave; distance.'); }
		elseif($action2=="attaque2") { print('<strong>'.$cible.'</strong> effectue une attaque rapproch&eacute;e.'); }
		elseif($action2=="deplacement") {
			if($j2>0) print('<strong>'.$cible.'</strong> se rapproche de vous.</p>'); else print('<strong>'.$cible.'</strong> s\'éloigne de vous.</p>'); }
		elseif($action2=="fuite") { print('<strong>'.$cible.'</strong> s\'est enfui.'); }
		?>
	</div>
	
	<?php
	print('<div id="combat">
			<object classid="clsid:d27cdb6e-ae6d-11cf-96b8-444553540000" codebase="http://fpdownload.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version=7,0,0,0" width="296" height="283" align="middle">
				<param name="allowScriptAccess" value="sameDomain" />
				<param name="movie" value="Combat.swf?action1='.$action1.'&j1='.$j1.'&j1max='.$j1max.'&action2='.$action2.'&j2='.$j2.'&j2max='.$j2max.'&j3='.$j3.'&j3max='.$j3max.'&j4='.$j4.'&j4max='.$j4max.'&j5='.$j5.'&j5max='.$j5max.'&j6='.$j6.'&j6max='.$j6max.'&j7='.$j7.'&j7max='.$j7max.'&j8='.$j8.'&j8max='.$j8max.'&j9='.$j9.'&j9max='.$j9max.'&j10='.$j10.'&j10max='.$j10max.'&j11='.$j11.'&j11max='.$j11max.'&j12='.$j12.'&j12max='.$j12max.'&j13='.$j13.'&j13max='.$j13max.'&j14='.$j14.'&j14max='.$j14max.'&j15='.$j15.'&j15max='.$j15max.'&j16='.$j16.'&j16max='.$j16max.'&j17='.$j17.'&j17max='.$j17max.'&j18='.$j18.'&j18max='.$j18max.'" />
				<param name="quality" value="high" />
				<param name="bgcolor" value="#ffffff" />
				<embed src="Combat.swf?action1='.$action1.'&j1='.$j1.'&j1max='.$j1max.'&action2='.$action2.'&j2='.$j2.'&j2max='.$j2max.'&j3='.$j3.'&j3max='.$j3max.'&j4='.$j4.'&j4max='.$j4max.'&j5='.$j5.'&j5max='.$j5max.'&j6='.$j6.'&j6max='.$j6max.'&j7='.$j7.'&j7max='.$j7max.'&j8='.$j8.'&j8max='.$j8max.'&j9='.$j9.'&j9max='.$j9max.'&j10='.$j10.'&j10max='.$j10max.'&j11='.$j11.'&j11max='.$j11max.'&j12='.$j12.'&j12max='.$j12max.'&j13='.$j13.'&j13max='.$j13max.'&j14='.$j14.'&j14max='.$j14max.'&j15='.$j15.'&j15max='.$j15max.'&j16='.$j16.'&j16max='.$j16max.'&j17='.$j17.'&j17max='.$j17max.'&j18='.$j18.'&j18max='.$j18max.'"" quality="high" bgcolor="#ffffff" width="296" height="283" name="combat" align="middle" allowScriptAccess="sameDomain" type="application/x-shockwave-flash" pluginspage="http://www.macromedia.com/go/getflashplayer" />
			</object>
	</div>');
	?>
	
</div>
<?php if($chat=="oui") 	{ if ((date("H")>=21) || (date("H")<=7)) { include("inc_bas_n_c.php"); } else { include("inc_bas_j_c.php"); } } else {	if ((date("H")>=21) || (date("H")<=7)) { include("inc_bas_n.php"); } else { include("inc_bas_j.php"); }	} ?>
<?php include("inc_bas_de_page.php");

function augmente_recherche($idc, $cible) {
	$secteur = secteur();
	if ($secteur != 7 && $secteur != 9) {
		$sqlp = 'SELECT police FROM principal_tbl WHERE id= "'.$idc.'"';
		$reqp = mysql_query($sqlp);
		$a_police = mysql_result($reqp,0,police);
		if ($cible == "citoyen") {
			$n_police = $a_police + 10;
			$_SESSION['police'] = $n_police;
		} elseif ($cible == "police") {
			if($a_police >= 200) $n_police = $a_police;
			else $n_police = 200;
		}
		$sqlp = 'UPDATE principal_tbl SET police="'.$n_police.'" WHERE id= "'.$idc.'"';
		$reqp = mysql_query($sqlp);
	}
}

?>
