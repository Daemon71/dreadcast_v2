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

$sql = 'SELECT id,rue,num,action,alim,sante,fatigue,informatique,Police,objet FROM principal_tbl WHERE pseudo= "'.$_SESSION['pseudo'].'"' ;
$req = mysql_query($sql);
$_SESSION['id'] = mysql_result($req,0,id);
$_SESSION['rue'] = mysql_result($req,0,rue);
$_SESSION['num'] = mysql_result($req,0,num);
$_SESSION['action'] = mysql_result($req,0,action);
$_SESSION['objet'] = mysql_result($req,0,objet);
$_SESSION['alim'] = mysql_result($req,0,alim);
$_SESSION['sante'] = mysql_result($req,0,sante);
$_SESSION['fatigue'] = mysql_result($req,0,fatigue);
$_SESSION['informatique'] = mysql_result($req,0,informatique);
$_SESSION['Police'] = mysql_result($req,0,Police);
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
			Cyber Deck
		</div>
		<b class="module4ie"><a href="engine=inventaire.php" class="module4"><img src="im_objets/icon_retour.gif" alt="Retour" />Retour</a></b>
</p>
	</div>
</div>
<div id="centre">
<p>

<?php 

$db = mysql_connect('localhost', 'CENSURE', 'CENSURE') or die("Erreur de connexion au serveur des messages.");
mysql_select_db('CENSURE',$db) or die("Erreur de connexion a la base de donnees des messages.");

if($_SESSION['objet']=="Deck Reist")
	{
	$modif = 20;
	}
elseif($_SESSION['objet']=="Cyber Deck")
	{
	$modif = 50;
	}

if ((event(2) || adm())) {
	$sql2 = 'SELECT id FROM objets_repares_tbl WHERE id_cible = '.$_SESSION['id'];
	$req2 = mysql_query($sql2);
	if (!mysql_num_rows($req2)) {
		echo '<div style="margin-top:50px;color:#c81111;text-align:center;">ERREUR CRITIQUE<br /><br />Votre Deck est brouillé par une sorte de virus et ne fonctionne plus !</div>';
		$noWay = true;
	}
}

if(!$noWay && (($_SESSION['objet']=="Deck Premium") or ($_SESSION['objet']=="Deck 1.1") or ($_SESSION['objet']=="Deck Reist") or ($_SESSION['objet']=="Cyber Deck") or ($_SESSION['objet']=="Deck Transcom")) && ($_SESSION['fatigue']>=5))
	{
	print('<center><div id="cdcontenu">');
	if($_SESSION['action']=="prison")
		{
		print('<br />Vous ne pouvez pas utiliser votre Deck en prison.');
		}
	elseif($_GET['mode']=="console")
		{
		print('<p align="center">Voici la console. Entrez les commandes que vous désirez.</p>');
		print('<form action="engine=cyberdeck.php?mode=console" method="post"><p align="center">
		<b>Commande:</b> <input type="text" name="commande" size="30" value="'.$_GET['commande'].'" /><br />
		<input type="submit" value="Valider la commande" />
		</p></form><hr /><br /><br /><br />');
		if($_GET['commande']!="")
			{
			if(ereg("//infos commande",$_GET['commande']))
				{
				$sql = 'SELECT * FROM commandes_tbl WHERE commande= "'.substr($_GET['commande'],17,strlen($_GET['commande'])).'"' ;
				$req = mysql_query($sql);
				$res = mysql_num_rows($req);
				if($res>0)
					{
					print('<p align="center"><b>Informations sur la commande:</b> '.mysql_result($req,0,action).'</p>');
					$lvl = 0;
					$up = 1;
					}
				else
					{
					print('<p align="center">Aucune commande reconnue.</p>');
					}
				}
			}
		if($_POST['commande']!="")
			{
			if(ereg("//infos commande",$_POST['commande']))
				{
				$sql = 'SELECT * FROM commandes_tbl WHERE commande= "'.substr($_POST['commande'],17,strlen($_POST['commande'])).'"' ;
				$req = mysql_query($sql);
				$res = mysql_num_rows($req);
				if($res>0)
					{
					print('<p align="center"><b>Informations sur la commande:</b> '.mysql_result($req,0,action).'</p>');
					$lvl = 0;
					$up = 1;
					}
				else
					{
					print('<p align="center"><b>Erreur:</b> Commande non reconnue.</p>');
					}
				}
			elseif(ereg("//de",$_POST['commande']))
				{
				if(ereg("//de capital",$_POST['commande']))
					{
					$sql = 'SELECT budget FROM entreprises_tbl WHERE nom= "'.substr($_POST['commande'],13,strlen($_POST['commande'])).'"' ;
					$req = mysql_query($sql);
					$res = mysql_num_rows($req);
					if($res>0)
						{
						print('<p align="center">Le central d\'informations signale que l\'entreprise <b>'.substr($_POST['commande'],13,strlen($_POST['commande'])).'</b> possède un capital de <b>'.mysql_result($req,0,budget).' Crédits</b>.</p>');
						$lvl = 0;
						$up = 1;
						}
					else
						{
						print('<p align="center"><b>Erreur:</b> Entreprise non reconnue.</p>');
						}
					}
				elseif(ereg("//de prix",$_POST['commande']))
					{
					$sql = 'SELECT prix FROM objets_tbl WHERE nom= "'.substr($_POST['commande'],10,strlen($_POST['commande'])).'"' ;
					$req = mysql_query($sql);
					$res = mysql_num_rows($req);
					if($res>0)
						{
						print('<p align="center">Le central d\'informations signale que l\'objet <b>'.substr($_POST['commande'],10,strlen($_POST['commande'])).'</b> coûte <b>'.mysql_result($req,0,prix).' Crédits</b> à la production.</p>');
						$lvl = 1;
						$up = 1;
						}
					else
						{
						print('<p align="center"><b>Erreur:</b> Objet non reconnu.</p>');
						}
					}
				elseif(ereg("//de infosent",$_POST['commande']))
					{
					$sql = 'SELECT num,rue FROM entreprises_tbl WHERE nom= "'.substr($_POST['commande'],14,strlen($_POST['commande'])).'"' ;
					$req = mysql_query($sql);
					$res = mysql_num_rows($req);
					if($res>0)
						{
						if(strtoupper(substr($_POST['commande'],14,strlen($_POST['commande'])))=="d")
							{
							print('<p align="center">La donnée demandée est cryptée.</p>');
							}
						elseif(strtoupper(substr($_POST['commande'],14,strlen($_POST['commande'])))=="DI2RCO")
							{
							print('<p align="center">La donnée demandée est cryptée.</p>');
							}
						else
							{
							print('<p align="center">Le central d\'informations signale que l\'entreprise <b>'.substr($_POST['commande'],14,strlen($_POST['commande'])).'</b> est située au <b>'.mysql_result($req,0,num).' '.mysql_result($req,0,rue).'</b>.</p>');
							}
						$lvl = 1;
						$up = 1;
						}
					else
						{
						print('<p align="center"><b>Erreur:</b> Entreprise non reconnue.</p>');
						}
					}
				elseif(ereg("//de PDG",$_POST['commande']))
					{
					$sql = 'SELECT pseudo FROM principal_tbl WHERE type= "PDG" AND entreprise= "'.substr($_POST['commande'],9,strlen($_POST['commande'])).'"' ;
					$req = mysql_query($sql);
					$res = mysql_num_rows($req);
					if($res>0)
						{
						print('<p align="center">Le central d\'informations signale que le PDG de l\'entreprise <b>'.substr($_POST['commande'],9,strlen($_POST['commande'])).'</b> est <b>'.mysql_result($req,0,pseudo).'</b>.</p>');
						$lvl = 1;
						$up = 1;
						}
					else
						{
						print('<p align="center"><b>Erreur:</b> Entreprise non reconnue.</p>');
						}
					}
				elseif(ereg("//de annonces AITL",$_POST['commande']))
					{
					$sql = 'SELECT id FROM petitesannonces_tbl' ;
					$req = mysql_query($sql);
					$res = mysql_num_rows($req);
					if($res>0)
						{
						print('<p align="center">Le central de téléchargement libre signale qu\'il y a <b>'.$res.'</b> petites annonces actuellement.</p>');
						$lvl = 0;
						$up = 1;
						}
					else
						{
						print('<p align="center">Aucune petite annonce.</p>');
						}
					}
				elseif(ereg("//de vie",$_POST['commande']))
					{
					$sql = 'SELECT action FROM principal_tbl WHERE pseudo= "'.substr($_POST['commande'],9,strlen($_POST['commande'])).'"' ;
					$req = mysql_query($sql);
					$res = mysql_num_rows($req);
					if($res>0)
						{
						if(mysql_result($req,0,action)=="mort" && substr($_POST['commande'],9,strlen($_POST['commande'])) != "Empereur")
							{
							print('<p align="center">Le central d\'informations signale que l\'individu <b>'.substr($_POST['commande'],9,strlen($_POST['commande'])).'</b> est <b>mort</b>.</p>');
							$lvl = 0;
							$up = 1;
							}
						else
							{
							print('<p align="center">Le central d\'informations signale que l\'individu <b>'.substr($_POST['commande'],9,strlen($_POST['commande'])).'</b> est <b>vivant</b>.</p>');
							$lvl = 0;
							$up = 1;
							}
						}
					else
						{
						print('<p align="center"><b>Erreur:</b> Individu non reconnu.</p>');
						}
					}
				elseif(ereg("//de entreprise",$_POST['commande']))
					{
					$sql = 'SELECT entreprise FROM principal_tbl WHERE pseudo= "'.substr($_POST['commande'],16,strlen($_POST['commande'])).'"' ;
					$req = mysql_query($sql);
					$res = mysql_num_rows($req);
					if($res>0)
						{
						if(mysql_result($req,0,entreprise)=="Aucune" || mysql_result($req,0,entreprise)=="DI2RCO")
							{
							print('<p align="center">Le central d\'informations signale que l\'individu <b>'.substr($_POST['commande'],16,strlen($_POST['commande'])).'</b> n\'appartient à aucune entreprise.</p>');
							$lvl = 2;
							$up = 1;
							}
						else
							{
							print('<p align="center">Le central d\'informations signale que l\'individu <b>'.substr($_POST['commande'],16,strlen($_POST['commande'])).'</b> appartient à l\'entreprise <b>'.mysql_result($req,0,entreprise).'</b>.</p>');
							$lvl = 2;
							$up = 1;
							}
						}
					else
						{
						print('<p align="center"><b>Erreur:</b> Individu non reconnu.</p>');
						}
					}
				elseif(ereg("//de assurance",$_POST['commande']))
					{
					$sql = 'SELECT assurance FROM principal_tbl WHERE pseudo= "'.substr($_POST['commande'],15,strlen($_POST['commande'])).'"' ;
					$req = mysql_query($sql);
					$res = mysql_num_rows($req);
					if($res>0)
						{
						if(mysql_result($req,0,assurance)==0)
							{
							print('<p align="center">Le central d\'informations signale que l\'individu <b>'.substr($_POST['commande'],15,strlen($_POST['commande'])).'</b> n\'a pas d\'assurance.</p>');
							$lvl = 3;
							$up = 1;
							}
						else
							{
							print('<p align="center">Le central d\'informations signale que l\'individu <b>'.substr($_POST['commande'],15,strlen($_POST['commande'])).'</b> a une assurance de <b>'.mysql_result($req,0,assurance).' jours</b>.</p>');
							$lvl = 3;
							$up = 1;
							}
						}
					else
						{
						print('<p align="center"><b>Erreur:</b> Individu non reconnu.</p>');
						}
					}
				else
					{
					print('<p align="center"><b>Erreur:</b> Commande non reconnue.</p>');
					}
				}
			else
				{
				if(ereg("//hk rdn",$_POST['commande']))
					{
					$sql = 'SELECT age,race,sexe FROM principal_tbl WHERE pseudo= "'.substr($_POST['commande'],9,strlen($_POST['commande'])).'"' ;
					$req = mysql_query($sql);
					$res = mysql_num_rows($req);
					if($res>0)
						{
						print('<p align="center">Le registre des naissances signale que l\'individu <b>'.substr($_POST['commande'],9,strlen($_POST['commande'])).'</b> est: <b>'.mysql_result($req,0,race).' / '.mysql_result($req,0,sexe).' / '.mysql_result($req,0,age).' ans</b>.</p>');
						$lvl = 0;
						$up = 1;
						$hk = 1;
						$police = $_SESSION['Police'] + ( rand(1,30) * ($lvl+1) ) - dcstat($_SESSION['pseudo'],$_SESSION['informatique'],0)+$modif;
						if($police<$_SESSION['Police'])
							{
							$police = $_SESSION['Police'];
							}
						if($police>$_SESSION['Police'])
							{
							$points = ceil($police - $_SESSION['Police']);
							print('<p align="center">La Police a intercepté votre commande.<br />Vous gagnez '.$points.' points de recherche.</p>');
							}
						}
					else
						{
						print('<p align="center"><b>Erreur:</b> Individu non reconnu.</p>');
						}
					}
				elseif(ereg("//hk sc verres",$_POST['commande']))
					{
					$sql = 'SELECT verres FROM principal_tbl WHERE pseudo= "'.substr($_POST['commande'],15,strlen($_POST['commande'])).'"' ;
					$req = mysql_query($sql);
					$res = mysql_num_rows($req);
					if($res>0)
						{
						print('<p align="center">Le serveur central signale que l\'individu <b>'.substr($_POST['commande'],15,strlen($_POST['commande'])).'</b> a bu <b>'.mysql_result($req,0,verres).' verre(s)</b> dans l\'heure.</p>');
						$lvl = 1;
						$up = 1;
						$hk = 1;
						$police = $_SESSION['Police'] + ( rand(1,30) * ($lvl+1) ) - dcstat($_SESSION['pseudo'],$_SESSION['informatique'],0)+$modif;
						if($police<$_SESSION['Police'])
							{
							$police = $_SESSION['Police'];
							}
						if($police>$_SESSION['Police'])
							{
							$points = ceil($police - $_SESSION['Police']);
							print('<p align="center">La Police a intercepté votre commande.<br />Vous gagnez '.$points.' points de recherche.</p>');
							}
						}
					else
						{
						print('<p align="center"><b>Erreur:</b> Individu non reconnu.</p>');
						}
					}
				elseif(ereg("//hk sc bandages",$_POST['commande']))
					{
					$sql = 'SELECT soins FROM principal_tbl WHERE pseudo= "'.substr($_POST['commande'],17,strlen($_POST['commande'])).'"' ;
					$req = mysql_query($sql);
					$res = mysql_num_rows($req);
					if($res>0)
						{
						print('<p align="center">Le serveur central signale que l\'individu <b>'.substr($_POST['commande'],17,strlen($_POST['commande'])).'</b> a sur lui <b>'.mysql_result($req,0,soins).' bandages(s)</b>.</p>');
						$lvl = 4;
						$up = 1;
						$hk = 1;
						$police = $_SESSION['Police'] + ( rand(1,30) * ($lvl+1) ) - dcstat($_SESSION['pseudo'],$_SESSION['informatique'],0)+$modif;
						if($police<$_SESSION['Police'])
							{
							$police = $_SESSION['Police'];
							}
						if($police>$_SESSION['Police'])
							{
							$points = ceil($police - $_SESSION['Police']);
							print('<p align="center">La Police a intercepté votre commande.<br />Vous gagnez '.$points.' points de recherche.</p>');
							}
						}
					else
						{
						print('<p align="center"><b>Erreur:</b> Individu non reconnu.</p>');
						}
					}
				elseif(ereg("//hk cdl casier",$_POST['commande']))
					{
					$sql = 'SELECT * FROM casiers_tbl WHERE pseudo= "'.substr($_POST['commande'],16,strlen($_POST['commande'])).'"' ;
					$req = mysql_query($sql);
					$res = mysql_num_rows($req);
					if($res>0)
						{
						print('<p align="center">Voici le casier de l\'individu <b>'.substr($_POST['commande'],16,strlen($_POST['commande'])).'</b>:</p>');
						for($i=0;$i!=$res;$i++)
							{
							print('<div align="center">--- Le '.date('d/m/y',mysql_result($req,$i,datea)).' par '.mysql_result($req,$i,policier).': '.mysql_result($req,$i,raison).' ---</div>');
							}
						$lvl = 5;
						$up = 1;
						$hk = 1;
						$police = $_SESSION['Police'] + ( rand(1,30) * ($lvl+1) ) - dcstat($_SESSION['pseudo'],$_SESSION['informatique'],0)+$modif;
						if($police<$_SESSION['Police'])
							{
							$police = $_SESSION['Police'];
							}
						if($police>$_SESSION['Police'])
							{
							$points = ceil($police - $_SESSION['Police']);
							print('<p align="center">La Police a intercepté votre commande.<br />Vous gagnez '.$points.' points de recherche.</p>');
							}
						}
					else
						{
						print('<p align="center">L\'individu <b>'.substr($_POST['commande'],16,strlen($_POST['commande'])).'</b> s\'il existe à un casier vièrge.</p>');
						}
					}
				elseif(ereg("//hk sat DI2RCO",$_POST['commande']))
					{
					$sql = 'SELECT num,rue FROM entreprises_tbl WHERE nom like "di2rco"' ;
					$req = mysql_query($sql);
					$di2rco = array(mysql_result($req,0,num), mysql_result($req,0,rue));

					$sql = 'SELECT * FROM enquete_tbl WHERE pseudo= "'.substr($_POST['commande'],16,strlen($_POST['commande'])).'" ORDER BY id DESC LIMIT 3' ;
					$req = mysql_query($sql);
					$res = mysql_num_rows($req);
					if($res>0)
						{
						print('<p align="center">Voici les derniers lieux où <b>'.substr($_POST['commande'],16,strlen($_POST['commande'])).'</b> à été apperçu par les satellites:</p>');
						for($i=1;$i!=$res;$i++)
							{
							if ($di2rco[0] == mysql_result($req,$i,num) && $di2rco[1] == strtolower(mysql_result($req,$i,rue)))
								continue;
								
							print('<div align="center">--- '.mysql_result($req,$i,num).' '.mysql_result($req,$i,rue).' ---</div>');
							}
						$lvl = 15;
						$up = 1;
						$hk = 1;
						$police = $_SESSION['Police'] + ( rand(1,30) * ($lvl+1) ) - dcstat($_SESSION['pseudo'],$_SESSION['informatique'],0)+$modif;
						if($police<$_SESSION['Police'])
							{
							$police = $_SESSION['Police'];
							}
						if($police>$_SESSION['Police'])
							{
							$points = ceil($police - $_SESSION['Police']);
							print('<p align="center">La Police a intercepté votre commande.<br />Vous gagnez '.$points.' points de recherche.</p>');
							}
						}
					else
						{
						print('<p align="center">L\'individu <b>'.substr($_POST['commande'],16,strlen($_POST['commande'])).'</b> s\'il existe n\'a jamais été apperçu.</p>');
						}
					}
				elseif((ereg("//hk deck sat DI2RCO",$_POST['commande'])) && ($_SESSION['objet']=="Deck Premium"))
					{
					$sql = 'SELECT num,rue FROM entreprises_tbl WHERE nom like "di2rco"' ;
					$req = mysql_query($sql);
					$di2rco = array(mysql_result($req,0,num), mysql_result($req,0,rue));
					
					$sql = 'SELECT * FROM enquete_tbl WHERE pseudo= "'.substr($_POST['commande'],21,strlen($_POST['commande'])).'" ORDER BY id DESC LIMIT 10' ;
					$req = mysql_query($sql);
					$res = mysql_num_rows($req);
					print('<p align="center">Voici les derniers lieux où <b>'.substr($_POST['commande'],21,strlen($_POST['commande'])).'</b> à été apperçu par les satellites:</p>');
					for($i=0;$i!=$res;$i++)
						{
						if ($di2rco[0] == mysql_result($req,$i,num) && $di2rco[1] == strtolower(mysql_result($req,$i,rue)))
								continue;
						
						print('<div align="center">--- '.mysql_result($req,$i,num).' '.mysql_result($req,$i,rue).' ---</div>');
						}
					if($res==0)
						{
						print('<p align="center">L\'individu <b>'.substr($_POST['commande'],21,strlen($_POST['commande'])).'</b> s\'il existe n\'a jamais été apperçu.</p>');
						}
					$lvl = 8;
					$up = 1;
					$hk = 1;
					$police = $_SESSION['Police'] + ( rand(1,30) * ($lvl+1) ) - dcstat($_SESSION['pseudo'],$_SESSION['informatique'],0)+$modif;
					if($police<$_SESSION['Police'])
						{
						$police = $_SESSION['Police'];
						}
					if($police>$_SESSION['Police'])
						{
						$points = ceil($police - $_SESSION['Police']);
						print('<p align="center">La Police a intercepté votre commande.<br />Vous gagnez '.$points.' points de recherche.</p>');
						}
					}
				elseif((ereg("//hk deck transcom",$_POST['commande'])) && ($_SESSION['objet']=="Deck Transcom"))
					{
					$sql = 'SELECT * FROM messages_tbl WHERE cible= "'.substr($_POST['commande'],19,strlen($_POST['commande'])).'" ORDER BY id DESC LIMIT 10' ;
					$req = mysql_query($sql);
					$res = mysql_num_rows($req);
					print('<p align="center">Voici les derniers messages reçus par <b>'.substr($_POST['commande'],19,strlen($_POST['commande'])).'</b>:</p>');
					for($i=0;$i!=$res;$i++)
						{
						if(mysql_result($req,$i,auteur)=="Dreadcast") print('<div align="center">--- Message: '.mysql_result($req,$i,objet).' le '.date("d/m/Y à H:i",mysql_result($req,$i,moment)).' ---</div>');
						else print('<div align="center">--- Message de '.mysql_result($req,$i,auteur).' le '.date("d/m/Y à H:i",mysql_result($req,$i,moment)).' ---</div>');
						}
					if($res==0)
						{
						print('<p align="center">L\'individu <b>'.substr($_POST['commande'],19,strlen($_POST['commande'])).'</b> s\'il existe a une boite de messages vide.</p>');
						}
					$lvl = 8;
					$up = 1;
					$hk = 1;
					$police = $_SESSION['Police'] + ( rand(1,30) * ($lvl+1) ) - dcstat($_SESSION['pseudo'],$_SESSION['informatique'],0)+$modif;
					if($police<$_SESSION['Police'])
						{
						$police = $_SESSION['Police'];
						}
					if($police>$_SESSION['Police'])
						{
						$points = ceil($police - $_SESSION['Police']);
						print('<p align="center">La Police a intercepté votre commande.<br />Vous gagnez '.$points.' points de recherche.</p>');
						}
					}
				elseif(ereg("//hk DOI aides",$_POST['commande']))
					{
					$sql = 'SELECT * FROM financepridem_tbl' ;
					$req = mysql_query($sql);
					$res = mysql_num_rows($req);
					if($res>0)
						{
						print('<p align="center">Voici la liste des entreprise qui ont demandé une aide cette semaine:</p>');
						for($i=1;$i!=$res;$i++)
							{
							print('<div align="center">--- '.mysql_result($req,$i,entreprise).' ---</div>');
							}
						$lvl = 6;
						$up = 1;
						$hk = 1;
						$police = $_SESSION['Police'] + ( rand(1,30) * ($lvl+1) ) - dcstat($_SESSION['pseudo'],$_SESSION['informatique'],0)+$modif;
						if($police<$_SESSION['Police'])
							{
							$police = $_SESSION['Police'];
							}
						if($police>$_SESSION['Police'])
							{
							$points = ceil($police - $_SESSION['Police']);
							print('<p align="center">La Police a intercepté votre commande.<br />Vous gagnez '.$points.' points de recherche.</p>');
							}
						}
					else
						{
						print('<p align="center">Aucune aide n\'est demandée cette semaine.</p>');
						}
					}
				elseif(ereg("//hk DOI subventions",$_POST['commande']))
					{
					$sql = 'SELECT * FROM finance_tbl' ;
					$req = mysql_query($sql);
					$res = mysql_num_rows($req);
					if($res>0)
						{
						print('<p align="center">Voici la liste des votes pour la finance publique:</p>');
						for($i=1;$i!=$res;$i++)
							{
							print('<div align="center"><b>'.mysql_result($req,$i,membre).':</b> Prison('.mysql_result($req,$i,prison).'), STV('.mysql_result($req,$i,proprete).'), CIPE('.mysql_result($req,$i,cipe).'), CIE('.mysql_result($req,$i,cie).'), CdL('.mysql_result($req,$i,chambre).'), DI2RCO('.mysql_result($req,$i,di2rco).'), Police('.mysql_result($req,$i,police).'), DC Network('.mysql_result($req,$i,dcn).')</div>');
							}
						$lvl = 10;
						$up = 1;
						$hk = 1;
						$police = $_SESSION['Police'] + ( rand(1,30) * ($lvl+1) ) - dcstat($_SESSION['pseudo'],$_SESSION['informatique'],0)+$modif;
						if($police<$_SESSION['Police'])
							{
							$police = $_SESSION['Police'];
							}
						if($police>$_SESSION['Police'])
							{
							$points = ceil($police - $_SESSION['Police']);
							print('<p align="center">La Police a intercepté votre commande.<br />Vous gagnez '.$points.' points de recherche.</p>');
							}
						}
					else
						{
						print('<p align="center">Aucune finance publique cette semaine.</p>');
						}
					}
				elseif(ereg("//hk cdl votes",$_POST['commande']))
					{
					$sql = 'SELECT * FROM loisprop_tbl' ;
					$req = mysql_query($sql);
					$res = mysql_num_rows($req);
					if($res>0)
						{
						print('<p align="center">Voici la liste des lois en cours de vote:</p>');
						for($i=1;$i!=$res;$i++)
							{
							print('<div align="center">--- '.mysql_result($req,$i,intitule).' par '.mysql_result($req,$i,membre).' ---</div>');
							}
						$lvl = 8;
						$up = 1;
						$hk = 1;
						$police = $_SESSION['Police'] + ( rand(1,30) * ($lvl+1) ) - dcstat($_SESSION['pseudo'],$_SESSION['informatique'],0)+$modif;
						if($police<$_SESSION['Police'])
							{
							$police = $_SESSION['Police'];
							}
						if($police>$_SESSION['Police'])
							{
							$points = ceil($police - $_SESSION['Police']);
							print('<p align="center">La Police a intercepté votre commande.<br />Vous gagnez '.$points.' points de recherche.</p>');
							}
						}
					else
						{
						print('<p align="center">Aucune loi n\'est votée en ce moment.</p>');
						}
					}
				elseif(ereg("//hk sc cercle",$_POST['commande']))
					{
					$sql = 'SELECT cercle FROM cercles_tbl WHERE pseudo= "'.substr($_POST['commande'],15,strlen($_POST['commande'])).'"' ;
					$req = mysql_query($sql);
					$res = mysql_num_rows($req);
					if($res>0)
						{
						print('<p align="center">Le serveur central signale que l\'individu <b>'.substr($_POST['commande'],15,strlen($_POST['commande'])).'</b> est inscrit dans le cercle <b>'.mysql_result($req,0,cercle).'</b>.</p>');
						$lvl = 3;
						$up = 1;
						$hk = 1;
						$police = $_SESSION['Police'] + ( rand(1,30) * ($lvl+1) ) - dcstat($_SESSION['pseudo'],$_SESSION['informatique'],0)+$modif;
						if($police<$_SESSION['Police'])
							{
							$police = $_SESSION['Police'];
							}
						if($police>$_SESSION['Police'])
							{
							$points = ceil($police - $_SESSION['Police']);
							print('<p align="center">La Police a intercepté votre commande.<br />Vous gagnez '.$points.' points de recherche.</p>');
							}
						}
					else
						{
						print('<p align="center">L\'individu <b>'.substr($_POST['commande'],15,strlen($_POST['commande'])).'</b> s\'il existe n\'est inscrit dans aucun cercle.</p>');
						}
					}
				elseif(ereg("//hk bc salaire",$_POST['commande']))
					{
					$sql = 'SELECT salaire FROM principal_tbl WHERE pseudo= "'.substr($_POST['commande'],16,strlen($_POST['commande'])).'"' ;
					$req = mysql_query($sql);
					$res = mysql_num_rows($req);
					if($res>0)
						{
						print('<p align="center">Le serveur de la banque centrale signale que l\'individu <b>'.substr($_POST['commande'],16,strlen($_POST['commande'])).'</b> déclare un salaire de <b>'.mysql_result($req,0,salaire).' Crédits</b> par jour.</p>');
						$lvl = 3;
						$up = 1;
						$hk = 1;
						$police = $_SESSION['Police'] + ( rand(1,30) * ($lvl+1) ) - dcstat($_SESSION['pseudo'],$_SESSION['informatique'],0)+$modif;
						if($police<$_SESSION['Police'])
							{
							$police = $_SESSION['Police'];
							}
						if($police>$_SESSION['Police'])
							{
							$points = ceil($police - $_SESSION['Police']);
							print('<p align="center">La Police a intercepté votre commande.<br />Vous gagnez '.$points.' points de recherche.</p>');
							}
						}
					else
						{
						print('<p align="center"><b>Erreur:</b> Individu non reconnu.</p>');
						}
					}
				elseif(ereg("//hk ic reaction",$_POST['commande']))
					{
					$sql = 'SELECT rattaque,rvol,rintrusion,rpolice FROM principal_tbl WHERE pseudo= "'.substr($_POST['commande'],17,strlen($_POST['commande'])).'"' ;
					$req = mysql_query($sql);
					$res = mysql_num_rows($req);
					if($res>0)
						{
						print('<p align="center">Le serveur signale que l\'individu <b>'.substr($_POST['commande'],17,strlen($_POST['commande'])).'</b> réagit à l\'attaquer par: '.mysql_result($req,0,rattaque).'.');
						$lvl = 10;
						$up = 1;
						$hk = 1;
						$police = $_SESSION['Police'] + ( rand(1,30) * ($lvl+1) ) - dcstat($_SESSION['pseudo'],$_SESSION['informatique'],0)+$modif;
						if($police<$_SESSION['Police'])
							{
							$police = $_SESSION['Police'];
							}
						if($police>$_SESSION['Police'])
							{
							$points = ceil($police - $_SESSION['Police']);
							print('<p align="center">La Police a intercepté votre commande.<br />Vous gagnez '.$points.' points de recherche.</p>');
							}
						}
					else
						{
						print('<p align="center"><b>Erreur:</b> Individu non reconnu.</p>');
						}
					}
				elseif(ereg("//hk bc credits",$_POST['commande']))
					{
					$sql = 'SELECT credits FROM principal_tbl WHERE pseudo= "'.substr($_POST['commande'],16,strlen($_POST['commande'])).'"' ;
					$req = mysql_query($sql);
					$res = mysql_num_rows($req);
					if($res>0)
						{
						print('<p align="center">Le serveur de la banque centrale signale que l\'individu <b>'.substr($_POST['commande'],16,strlen($_POST['commande'])).'</b> possède une somme de <b>'.mysql_result($req,0,credits).' Crédits</b> sur lui.</p>');
						$lvl = 8;
						$up = 1;
						$hk = 1;
						$police = $_SESSION['Police'] + ( rand(1,30) * ($lvl+1) ) - dcstat($_SESSION['pseudo'],$_SESSION['informatique'],0)+$modif;
						if($police<$_SESSION['Police'])
							{
							$police = $_SESSION['Police'];
							}
						if($police>$_SESSION['Police'])
							{
							$points = ceil($police - $_SESSION['Police']);
							print('<p align="center">La Police a intercepté votre commande.<br />Vous gagnez '.$points.' points de recherche.</p>');
							}
						}
					else
						{
						print('<p align="center"><b>Erreur:</b> Individu non reconnu.</p>');
						}
					}
				elseif(ereg("//hk sc chiffre",$_POST['commande']))
					{
					$sql = 'SELECT benefices FROM entreprises_tbl WHERE nom= "'.substr($_POST['commande'],16,strlen($_POST['commande'])).'"' ;
					$req = mysql_query($sql);
					$res = mysql_num_rows($req);
					if($res>0)
						{
						print('<p align="center">Le serveur central signale que l\'entreprise <b>'.substr($_POST['commande'],16,strlen($_POST['commande'])).'</b> déclare un chiffre de <b>'.mysql_result($req,0,benefices).' Crédits</b> aujourd\'hui.</p>');
						$lvl = 7;
						$up = 1;
						$hk = 1;
						$police = $_SESSION['Police'] + ( rand(1,30) * ($lvl+1) ) - dcstat($_SESSION['pseudo'],$_SESSION['informatique'],0)+$modif;
						if($police<$_SESSION['Police'])
							{
							$police = $_SESSION['Police'];
							}
						if($police>$_SESSION['Police'])
							{
							$points = ceil($police - $_SESSION['Police']);
							print('<p align="center">La Police a intercepté votre commande.<br />Vous gagnez '.$points.' points de recherche.</p>');
							}
						}
					else
						{
						print('<p align="center"><b>Erreur:</b> Entreprise non reconnue.</p>');
						}
					}
				elseif(ereg("//hk sc habitation",$_POST['commande']))
					{
					$sql = 'SELECT numl,ruel FROM principal_tbl WHERE pseudo= "'.substr($_POST['commande'],19,strlen($_POST['commande'])).'"' ;
					$req = mysql_query($sql);
					$res = mysql_num_rows($req);
					if($res>0)
						{
						if(mysql_result($req,0,numl)!=0)
							{
							print('<p align="center">Le serveur central signale que l\'individu <b>'.substr($_POST['commande'],19,strlen($_POST['commande'])).'</b> déclare habiter au <b>'.mysql_result($req,0,numl).' '.mysql_result($req,0,ruel).'</b>.</p>');
							}
						else
							{
							print('<p align="center">Le serveur central signale que l\'individu <b>'.substr($_POST['commande'],19,strlen($_POST['commande'])).'</b> déclare habiter <b>dans la rue</b>.</p>');
							}
						$lvl = 5;
						$up = 1;
						$hk = 1;
						$police = $_SESSION['Police'] + ( rand(1,30) * ($lvl+1) ) - dcstat($_SESSION['pseudo'],$_SESSION['informatique'],0)+$modif;
						if($police<$_SESSION['Police'])
							{
							$police = $_SESSION['Police'];
							}
						if($police>$_SESSION['Police'])
							{
							$points = ceil($police - $_SESSION['Police']);
							print('<p align="center">La Police a intercepté votre commande.<br />Vous gagnez '.$points.' points de recherche.</p>');
							}
						}
					else
						{
						print('<p align="center"><b>Erreur:</b> Individu non reconnu.</p>');
						}
					}
				elseif(ereg("//hk sc drogue",$_POST['commande']))
					{
					$sql = 'SELECT drogue FROM principal_tbl WHERE pseudo= "'.substr($_POST['commande'],15,strlen($_POST['commande'])).'"' ;
					$req = mysql_query($sql);
					$res = mysql_num_rows($req);
					if($res>0)
						{
						if(mysql_result($req,0,drogue)>0)
							{
							print('<p align="center">Le serveur central signale que l\'individu <b>'.substr($_POST['commande'],15,strlen($_POST['commande'])).'</b> est actuellement drogué.</p>');
							}
						else
							{
							print('<p align="center">Le serveur central signale que l\'individu <b>'.substr($_POST['commande'],15,strlen($_POST['commande'])).'</b> n\'est actuellement pas drogué.</p>');
							}
						$lvl = 4;
						$up = 1;
						$hk = 1;
						$police = $_SESSION['Police'] + ( rand(1,30) * ($lvl+1) ) - dcstat($_SESSION['pseudo'],$_SESSION['informatique'],0)+$modif;
						if($police<$_SESSION['Police'])
							{
							$police = $_SESSION['Police'];
							}
						if($police>$_SESSION['Police'])
							{
							$points = ceil($police - $_SESSION['Police']);
							print('<p align="center">La Police a intercepté votre commande.<br />Vous gagnez '.$points.' points de recherche.</p>');
							}
						}
					else
						{
						print('<p align="center"><b>Erreur:</b> Individu non reconnu.</p>');
						}
					}
				elseif(ereg("//hk sc action",$_POST['commande']))
					{
					$sql = 'SELECT action FROM principal_tbl WHERE pseudo= "'.substr($_POST['commande'],15,strlen($_POST['commande'])).'"' ;
					$req = mysql_query($sql);
					$res = mysql_num_rows($req);
					if($res>0)
						{
						if(mysql_result($req,0,action)=="aucune")
							{
							print('<p align="center">Le serveur central signale que l\'individu <b>'.substr($_POST['commande'],15,strlen($_POST['commande'])).'</b> ne fait rien actuellement.</p>');
							}
						elseif(mysql_result($req,0,action)=="travail")
							{
							print('<p align="center">Le serveur central signale que l\'individu <b>'.substr($_POST['commande'],15,strlen($_POST['commande'])).'</b> est actuellement au travail.</p>');
							}
						elseif(mysql_result($req,0,action)=="repos")
							{
							print('<p align="center">Le serveur central signale que l\'individu <b>'.substr($_POST['commande'],15,strlen($_POST['commande'])).'</b> est actuellement en train de se reposer.</p>');
							}
						elseif(mysql_result($req,0,action)=="Recherche de cristaux")
							{
							print('<p align="center">Le serveur central signale que l\'individu <b>'.substr($_POST['commande'],15,strlen($_POST['commande'])).'</b> est actuellement en train de chercher des cristaux.</p>');
							}
						elseif(mysql_result($req,0,action)=="mort")
							{
							print('<p align="center">Le serveur central signale que l\'individu <b>'.substr($_POST['commande'],15,strlen($_POST['commande'])).'</b> est actuellement mort.</p>');
							}
						elseif(mysql_result($req,0,action)=="protection")
							{
							print('<p align="center">Le serveur central signale que l\'individu <b>'.substr($_POST['commande'],15,strlen($_POST['commande'])).'</b> est actuellement protégé par la police.</p>');
							}
						elseif(mysql_result($req,0,action)=="prison")
							{
							print('<p align="center">Le serveur central signale que l\'individu <b>'.substr($_POST['commande'],15,strlen($_POST['commande'])).'</b> est actuellement en prison.</p>');
							}
						elseif(ereg("Protection ",mysql_result($req,0,action)))
							{
							print('<p align="center">Le serveur central signale que l\'individu <b>'.substr($_POST['commande'],15,strlen($_POST['commande'])).'</b> est actuellement en train de protéger quelqu\'un.</p>');
							}
						elseif(mysql_result($req,0,action)=="Vacances")
							{
							print('<p align="center">Le serveur central signale que l\'individu <b>'.substr($_POST['commande'],15,strlen($_POST['commande'])).'</b> est actuellement en stase de cryogénisation.</p>');
							}
						else
							{
							print('<p align="center">Le serveur central signale que l\'individu <b>'.substr($_POST['commande'],15,strlen($_POST['commande'])).'</b> est actuellement en train de prendre un cours au CIE.</p>');
							}
						$lvl = 9;
						$up = 1;
						$hk = 1;
						$police = $_SESSION['Police'] + ( rand(1,30) * ($lvl+1) ) - dcstat($_SESSION['pseudo'],$_SESSION['informatique'],0)+$modif;
						if($police<$_SESSION['Police'])
							{
							$police = $_SESSION['Police'];
							}
						if($police>$_SESSION['Police'])
							{
							$points = ceil($police - $_SESSION['Police']);
							print('<p align="center">La Police a intercepté votre commande.<br />Vous gagnez '.$points.' points de recherche.</p>');
							}
						}
					else
						{
						print('<p align="center"><b>Erreur:</b> Individu non reconnu.</p>');
						}
					}
				elseif(ereg("//hk Police indice",$_POST['commande']))
					{
					$sql = 'SELECT Police FROM principal_tbl WHERE pseudo= "'.substr($_POST['commande'],19,strlen($_POST['commande'])).'"' ;
					$req = mysql_query($sql);
					$res = mysql_num_rows($req);
					if($res>0)
						{
						if(mysql_result($req,0,Police)>0)
							{
							print('<p align="center">Le serveur central signale que l\'individu <b>'.substr($_POST['commande'],19,strlen($_POST['commande'])).'</b> est actuellement recherché.</p>');
							}
						else
							{
							print('<p align="center">Le serveur central signale que l\'individu <b>'.substr($_POST['commande'],19,strlen($_POST['commande'])).'</b> n\'est actuellement pas recherché.</p>');
							}
						$lvl = 7;
						$up = 1;
						$hk = 1;
						$police = $_SESSION['Police'] + ( rand(1,30) * ($lvl+1) ) - dcstat($_SESSION['pseudo'],$_SESSION['informatique'],0)+$modif;
						if($police<$_SESSION['Police'])
							{
							$police = $_SESSION['Police'];
							}
						if($police>$_SESSION['Police'])
							{
							$points = ceil($police - $_SESSION['Police']);
							print('<p align="center">La Police a intercepté votre commande.<br />Vous gagnez '.$points.' points de recherche.</p>');
							}
						}
					else
						{
						print('<p align="center"><b>Erreur:</b> Individu non reconnu.</p>');
						}
					}
				elseif(ereg("//hk DI2RCO mandat",$_POST['commande']))
					{
					$sql = 'SELECT DI2RCO FROM principal_tbl WHERE pseudo= "'.substr($_POST['commande'],19,strlen($_POST['commande'])).'"' ;
					$req = mysql_query($sql);
					$res = mysql_num_rows($req);
					if($res>0)
						{
						if(mysql_result($req,0,DI2RCO)>0)
							{
							print('<p align="center">Le serveur central signale que l\'individu <b>'.substr($_POST['commande'],19,strlen($_POST['commande'])).'</b> est actuellement recherché par le DI2RCO.</p>');
							}
						else
							{
							print('<p align="center">Le serveur central signale que l\'individu <b>'.substr($_POST['commande'],19,strlen($_POST['commande'])).'</b> n\'est actuellement pas recherché par le DI2RCO.</p>');
							}
						$lvl = 10;
						$up = 1;
						$hk = 1;
						$police = $_SESSION['Police'] + ( rand(1,30) * ($lvl+1) ) - dcstat($_SESSION['pseudo'],$_SESSION['informatique'],0)+$modif;
						if($police<$_SESSION['Police'])
							{
							$police = $_SESSION['Police'];
							}
						if($police>$_SESSION['Police'])
							{
							$points = ceil($police - $_SESSION['Police']);
							print('<p align="center">La Police a intercepté votre commande.<br />Vous gagnez '.$points.' points de recherche.</p>');
							}
						}
					else
						{
						print('<p align="center"><b>Erreur:</b> Individu non reconnu.</p>');
						}
					}
				elseif(ereg("//hk sc forme",$_POST['commande']))
					{
					$sql = 'SELECT fatigue,fatigue_max FROM principal_tbl WHERE pseudo= "'.substr($_POST['commande'],14,strlen($_POST['commande'])).'"' ;
					$req = mysql_query($sql);
					$res = mysql_num_rows($req);
					if($res>0)
						{
						if(mysql_result($req,0,fatigue)>mysql_result($req,0,fatigue_max))
							{
							$forme = '<span class="color4">Hyperactif</span>';
							}
						elseif(mysql_result($req,0,fatigue)==mysql_result($req,0,fatigue_max))
							{
							$forme = 'Complêtement reposé';
							}
						elseif(mysql_result($req,0,fatigue)>mysql_result($req,0,fatigue_max)*70/100)
							{
							$forme = '<span class="color1">En forme</span>';
							}
						elseif(mysql_result($req,0,fatigue)>mysql_result($req,0,fatigue_max)*30/100)
							{
							$forme = '<span class="color2">Fatigué</span>';
							}
						else
							{
							$forme = '<span class="color2">Epuisé</span>';
							}
						print('<p align="center">Le serveur central signale que l\'individu <b>'.substr($_POST['commande'],14,strlen($_POST['commande'])).'</b> est <b>'.$forme.'</b>.</p>');
						$lvl = 3;
						$up = 1;
						$hk = 1;
						$police = $_SESSION['Police'] + ( rand(1,30) * ($lvl+1) ) - dcstat($_SESSION['pseudo'],$_SESSION['informatique'],0)+$modif;
						if($police<$_SESSION['Police'])
							{
							$police = $_SESSION['Police'];
							}
						if($police>$_SESSION['Police'])
							{
							$points = ceil($police - $_SESSION['Police']);
							print('<p align="center">La Police a intercepté votre commande.<br />Vous gagnez '.$points.' points de recherche.</p>');
							}
						}
					else
						{
						print('<p align="center"><b>Erreur:</b> Individu non reconnu.</p>');
						}
					}
				elseif(ereg("//hk sc sante",$_POST['commande']))
					{
					$sql = 'SELECT sante FROM principal_tbl WHERE pseudo= "'.substr($_POST['commande'],14,strlen($_POST['commande'])).'"' ;
					$req = mysql_query($sql);
					$res = mysql_num_rows($req);
					if($res>0)
						{
						if(mysql_result($req,0,sante)>10)
							{
							$sante = '<span class="color4">Hyperactif</span>';
							}
						elseif(mysql_result($req,0,sante)==10)
							{
							$sante = 'En pleine forme';
							}
						elseif(mysql_result($req,0,sante)>7)
							{
							$sante = '<span class="color1">Blessé légèrement</span>';
							}
						elseif(mysql_result($req,0,sante)>4)
							{
							$sante = '<span class="color2">Blessé gravement</span>';
							}
						elseif(mysql_result($req,0,sante)==0)
							{
							$sante = '<span class="color3">Inconscient</span>';
							}
						else
							{
							$sante = '<span class="color3">Agonisant</span>';
							}
						print('<p align="center">Le serveur central signale que l\'individu <b>'.substr($_POST['commande'],14,strlen($_POST['commande'])).'</b> est <b>'.$sante.'</b>.</p>');
						$lvl = 7;
						$up = 1;
						$hk = 1;
						$police = $_SESSION['Police'] + ( rand(1,30) * ($lvl+1) ) - dcstat($_SESSION['pseudo'],$_SESSION['informatique'],0)+$modif;
						if($police<$_SESSION['Police'])
							{
							$police = $_SESSION['Police'];
							}
						if($police>$_SESSION['Police'])
							{
							$points = ceil($police - $_SESSION['Police']);
							print('<p align="center">La Police a intercepté votre commande.<br />Vous gagnez '.$points.' points de recherche.</p>');
							}
						}
					else
						{
						print('<p align="center"><b>Erreur:</b> Individu non reconnu.</p>');
						}
					}
				elseif(ereg("//hk Police cam",$_POST['commande']))
					{
					$sql = 'SELECT camera FROM lieu_tbl WHERE num= "'.$_SESSION['num'].'" AND rue="'.$_SESSION['lieu'].'"' ;
					$req = mysql_query($sql);
					$res = mysql_num_rows($req);
					if($res>0)
						{
						if(mysql_result($req,0,camera)=="Pol")
							{
							print('<p align="center">Le serveur de la Police signale que la zone est protégée par une camera de Police.</p>');
							}
						else
							{
							print('<p align="center">Le serveur de la Police signale que la zone n\'est protégée par aucune camera de Police.</p>');
							}
						$lvl = 9;
						$up = 1;
						$hk = 1;
						$police = $_SESSION['Police'] + ( rand(1,30) * ($lvl+1) ) - dcstat($_SESSION['pseudo'],$_SESSION['informatique'],0)+$modif;
						if($police<$_SESSION['Police'])
							{
							$police = $_SESSION['Police'];
							}
						if($police>$_SESSION['Police'])
							{
							$points = ceil($police - $_SESSION['Police']);
							print('<p align="center">La Police a intercepté votre commande.<br />Vous gagnez '.$points.' points de recherche.</p>');
							}
						}
					else
						{
						print('<p align="center"><b>Erreur:</b> Zone non reconnue.</p>');
						}
					}
				elseif(ereg("//hk Surveillance cam",$_POST['commande']))
					{
					$sql = 'SELECT camera FROM lieu_tbl WHERE num= "'.$_SESSION['num'].'" AND rue="'.$_SESSION['lieu'].'"' ;
					$req = mysql_query($sql);
					$res = mysql_num_rows($req);
					if($res>0)
						{
						if(mysql_result($req,0,camera)=="Oui")
							{
							print('<p align="center">Le plot signale que la zone est protégée par une camera de Surveillance.</p>');
							}
						else
							{
							print('<p align="center">Le plot signale que la zone n\'est protégée par aucune camera de Surveillance.</p>');
							}
						$lvl = 11;
						$up = 1;
						$hk = 1;
						$police = $_SESSION['Police'] + ( rand(1,30) * ($lvl+1) ) - dcstat($_SESSION['pseudo'],$_SESSION['informatique'],0)+$modif;
						if($police<$_SESSION['Police'])
							{
							$police = $_SESSION['Police'];
							}
						if($police>$_SESSION['Police'])
							{
							$points = ceil($police - $_SESSION['Police']);
							print('<p align="center">La Police a intercepté votre commande.<br />Vous gagnez '.$points.' points de recherche.</p>');
							}
						}
					else
						{
						print('<p align="center"><b>Erreur:</b> Zone non reconnue.</p>');
						}
					}
				elseif(ereg("//hk digicode",$_POST['commande']))
					{
					$sql = 'SELECT code FROM lieu_tbl WHERE num= "'.$_SESSION['num'].'" AND rue="'.$_SESSION['lieu'].'"' ;
					$req = mysql_query($sql);
					$res = mysql_num_rows($req);
					if($res>0)
						{
						if((substr($_POST['commande'],14,strlen($_POST['commande']))==1) or (substr($_POST['commande'],14,strlen($_POST['commande']))==2) or (substr($_POST['commande'],14,strlen($_POST['commande']))==3) or (substr($_POST['commande'],14,strlen($_POST['commande']))==4) or (substr($_POST['commande'],14,strlen($_POST['commande']))==5) or (substr($_POST['commande'],14,strlen($_POST['commande']))==6))
							{
							if(strlen(mysql_result($req,0,code))==substr($_POST['commande'],14,strlen($_POST['commande'])))
								{
								print('<p align="center">Le serveur principal signale que la zone est protégée par un digicode à <b>'.substr($_POST['commande'],14,strlen($_POST['commande'])).' chiffres</b>. Il commence par <b>'.substr(mysql_result($req,0,code),0,ceil(strlen(mysql_result($req,0,code))/2)).'</b>.</p>');
								$lvl = substr($_POST['commande'],14,strlen($_POST['commande']));
								$up = 1;
								$hk = 1;
								$police = $_SESSION['Police'] + ( rand(1,30) * ($lvl+1) ) - dcstat($_SESSION['pseudo'],$_SESSION['informatique'],0)+$modif;
								if($police<$_SESSION['Police'])
									{
									$police = $_SESSION['Police'];
									}
								if($police>$_SESSION['Police'])
									{
									$points = ceil($police - $_SESSION['Police']);
									print('<p align="center">La Police a intercepté votre commande.<br />Vous gagnez '.$points.' points de recherche.</p>');
									}
								}
							else
								{
								print('<p align="center"><b>Erreur:</b> Mauvaise longueur de digicode.</p>');
								}
							}
						else
							{
							print('<p align="center"><b>Erreur:</b> Commande non reconnue.</p>');
							}
						}
					else
						{
						print('<p align="center"><b>Erreur:</b> Zone non reconnue.</p>');
						}
					}
				}
			}
		}
	elseif($_GET['mode']=="hk")
		{
		print('<p align="center">Voici la liste des commandes actives que vous connaissez.<br />Ces commandes sont illégales. Vous risquez d\'être repéré par les services automatiques de Police en les utilisant.<br />Cliquez sur une commande pour obtenir plus d\'informations.</p>');
		$lvl = (dcstat($_SESSION['pseudo'],$_SESSION['informatique'],0)+$modif) / 10;
		$sql = 'SELECT * FROM commandes_tbl WHERE lvl<= "'.$lvl.'" AND commande LIKE \'%//hk%\' ORDER BY lvl' ;
		$req = mysql_query($sql);
		$res = mysql_num_rows($req);
		for($i=0; $i != $res; $i++)
			{
			print('<a href="engine=cyberdeck.php?mode=console&commande=//infos commande '.mysql_result($req,$i,commande).'">'.mysql_result($req,$i,commande).'</a><br />');
			}
		if($_SESSION['objet']=="Deck Premium")
			{
			print('//hk deck sat DI2RCO <b>individu</b><br />');
			}
		if($_SESSION['objet']=="Deck Transcom")
			{
			print('//hk deck transcom <b>individu</b><br />');
			}
		}
	else
		{
		print('<p align="center">Voici la liste des commandes neutres que vous connaissez.<br />Ces commandes sont parfaitement légales.<br />Cliquez sur une commande pour obtenir plus d\'informations.</p>');
		$lvl = (dcstat($_SESSION['pseudo'],$_SESSION['informatique'],0)+$modif) / 10;
		$sql = 'SELECT * FROM commandes_tbl WHERE lvl<= "'.$lvl.'" AND commande LIKE \'%//de%\' ORDER BY lvl' ;
		$req = mysql_query($sql);
		$res = mysql_num_rows($req);
		for($i=0; $i != $res; $i++)
			{
			print('<a href="engine=cyberdeck.php?mode=console&commande=//infos commande '.mysql_result($req,$i,commande).'">'.mysql_result($req,$i,commande).'</a><br />');
			}
		}
	print('</div>');
	if($_GET['mode']=="console")
		{
		print('<a href="engine=cyberdeck.php"><div id="cdde"></div></a>');
		print('<a href="engine=cyberdeck.php?mode=hk"><div id="cdhk"></div></a>');
		print('<div id="cdc_O"></div>');
		}
	elseif($_GET['mode']=="hk")
		{
		print('<a href="engine=cyberdeck.php"><div id="cdde"></div></a>');
		print('<div id="cdhk_O"></div>');
		print('<a href="engine=cyberdeck.php?mode=console"><div id="cdc"></div></a>');
		}
	else
		{
		print('<div id="cdde_O"></div>');
		print('<a href="engine=cyberdeck.php?mode=hk"><div id="cdhk"></div></a>');
		print('<a href="engine=cyberdeck.php?mode=console"><div id="cdc"></div></a>');
		}
	print('</center>');
	}
elseif($_SESSION['fatigue']<5)
	{
	print('<br />Vous ne sentez pas avoir la force d\'ouvrir le Deck.');
	}
elseif (!$noWay)
	{
	print('<br />Il faut un Deck équipé en objet pour continuer.');
	}

if($up==1)
	{
	if((($lvl==0) && ($_SESSION['informatique']<10)) or (($lvl==1) && ($_SESSION['informatique']<20)) or (($lvl==2) && ($_SESSION['informatique']<30)) or (($lvl==3) && ($_SESSION['informatique']<40)) or (($lvl==4) && ($_SESSION['informatique']<50)) or (($lvl==5) && ($_SESSION['informatique']<60)) or (($lvl==6) && ($_SESSION['informatique']<70)) or (($lvl==7) && ($_SESSION['informatique']<80)) or (($lvl==8) && ($_SESSION['informatique']<90)) or (($lvl==9) && ($_SESSION['informatique']<100)) or (($lvl==10) && ($_SESSION['informatique']<110)) or (($lvl==11) && ($_SESSION['informatique']<120)) or (($lvl==12) && ($_SESSION['informatique']<130)) or (($lvl==13) && ($_SESSION['informatique']<140)) or (($lvl==14) && ($_SESSION['informatique']<150)))
		{
		augmenter_statistique($_SESSION['id'],"informatique",$_SESSION['informatique']);
		$_SESSION['fatigue'] = $_SESSION['fatigue'] - 5;
		$sql = 'UPDATE principal_tbl SET fatigue= "'.$_SESSION['fatigue'].'" WHERE id= "'.$_SESSION['id'].'"' ;
		$req = mysql_query($sql);
		}
	}

if($hk==1)
	{
	$sql = 'UPDATE principal_tbl SET Police= "'.$police.'" WHERE id= "'.$_SESSION['id'].'"' ;
	$req = mysql_query($sql);
	$sql = 'INSERT INTO crimes_tbl(pseudo,date,type,valeur) VALUES("'.$_SESSION['pseudo'].'","'.time().'","Hack","'.htmlentities($_POST['commande']).'")' ;
	$req = mysql_query($sql);
	}

mysql_close($db);

?>

</p>
</div>
<?php if($chat=="oui") 	{ if ((date("H")>=21) || (date("H")<=7)) { include("inc_bas_n_c.php"); } else { include("inc_bas_j_c.php"); } } else {	if ((date("H")>=21) || (date("H")<=7)) { include("inc_bas_n.php"); } else { include("inc_bas_j.php"); }	} ?>
<?php include("inc_bas_de_page.php"); ?>
