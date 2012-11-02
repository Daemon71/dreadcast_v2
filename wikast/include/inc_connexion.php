			<?php
			if($_POST['envoi']!="true")
				{
				if(empty($_SESSION['id']))
					{
					print('
					<div id="forum-acces">
					<form action="#" method="post" id="connexion">
						<p><strong>Connexion</strong><br />
						Login <input name="login" type="text" id="log" /><br />
						Mdp <input name="passw" id="mdp" type="password" /><input name="submit" type="submit" value="Ok " id="ok" />
						<input type="hidden" name="envoi" value="true" />
						</p>
					</form>
					<p id="acces-direct">
						<a href="forum=accueil.php">> Acc&egrave;s direct au forum</a>
					</p>
				</div>
				');
					}
				else
					{
					
					print('
					<!-- INFOS CONCERNANT MON  PERSONNAGE -->
					<div id="forum-infopersonnage">
					');
					
					include('include/inc_situation.php');
					
					print('
					</div>
					');
					
					}
				}
			else
				{

$db = mysql_connect('localhost', 'CENSURE', 'CENSURE') or die("Erreur de connexion au serveur des messages.");
mysql_select_db('CENSURE',$db) or die("Erreur de connexion a la base de donnees des messages.");

$sql = 'SELECT id,dhc FROM principal_tbl WHERE connec="oui"';
$req = mysql_query($sql);
$res2 = mysql_num_rows($req);

for($i=0 ; $i!=$res2 ; $i++)
	{
	$dhc = time() - mysql_result($req,$i,dhc);
	if($dhc>5000)
		{
		$iddhc = mysql_result($req,$i,id);
		$sql2 = 'UPDATE principal_tbl SET connec= "non" WHERE id= "'.$iddhc.'"' ;
		$req2 = mysql_query($sql2);
		}
	}
if($res2>700)
	{
	$nbremax = 1;
	}

$login = $_POST['login'];
$password = sha1($_POST['passw']);

$sql = 'SELECT password,statut FROM principal_tbl WHERE login= "'.$login.'"' ;
$req = mysql_query($sql);
$res = mysql_num_rows($req);

if($res!=0)
	{
	$passverif = mysql_result($req,0,password); 
	$_SESSION['statut'] = mysql_result($req,0,statut); 
	}
else
	{
	$passverif="";
	$_SESSION['statut']="";
	}

if($password!=$passverif) 
	{
	$st = 1;
	}
elseif($password=="") 
	{
	$st = 1;
	}

if(($nbremax==1) && (($_SESSION['statut']=="Compte VIP") or ($_SESSION['statut']=="Administrateur") or ($_SESSION['statut']=="DÈveloppeur")))
	{
	$nbremax = 0;
	}

if(($st!=1) && ($login!="") && ($nbremax!=1))
	{	
	$sql = 'SELECT id,ip FROM principal_tbl WHERE login= "'.$login.'"' ;
	$req = mysql_query($sql);
	$idt = mysql_result($req,0,id); 
	
	if(mysql_result($req,0,ip)!="1.1.1.1")
		{
		$sqlt = 'SELECT dhc FROM principal_tbl WHERE ipdc= "'.$_SERVER['REMOTE_ADDR'].'" AND id!= "'.$idt.'"' ;
		$reqt = mysql_query($sqlt);
		$rest = mysql_num_rows($reqt);
		if($rest>=1)
			{
			if(time()-mysql_result($reqt,0,dhc)<600)
				{
				$multi = 1;
				}
			}
		}
		
	if($multi!=1)
		{
		$_SESSION['id'] = $idt;
		$sql = 'UPDATE principal_tbl SET ipdc= "'.$_SERVER['REMOTE_ADDR'].'" WHERE id= "'.$_SESSION['id'].'"' ;
		$req = mysql_query($sql);
		$sql = 'SELECT avatar,pseudo,race,sexe,age,taille,faim,soif,rue,action,actif,quetedepart,credits,avatar,case1,case2,case3,case4,case5,case6,total,planete FROM principal_tbl WHERE id= "'.$_SESSION['id'].'"' ;
		$req = mysql_query($sql);
		$pseudo = mysql_result($req,0,pseudo); 
		$race = mysql_result($req,0,race); 
		$sexe = mysql_result($req,0,sexe); 
		$age = mysql_result($req,0,age); 
		$taille = mysql_result($req,0,taille); 
		$actif = mysql_result($req,0,actif); 
		$_SESSION['quetedepart'] = mysql_result($req,0,quetedepart); 
		
		if(($actif=="non") && ($st!=2))
			{
			$sql1 = 'UPDATE principal_tbl SET actif= "oui" WHERE id= "'.$_SESSION['id'].'"' ;
			$req1 = mysql_query($sql1);
			}
		
		$_SESSION['lieu'] = mysql_result($req,0,rue);
		$_SESSION['action'] = mysql_result($req,0,action);
		
		$_SESSION['avatar'] = mysql_result($req,0,avatar);
		$_SESSION['faim'] = mysql_result($req,0,faim);
		$_SESSION['soif'] = mysql_result($req,0,soif);
		$_SESSION['credits'] = mysql_result($req,0,credits);
		$_SESSION['case1'] = mysql_result($req,0,case1);
		$_SESSION['case2'] = mysql_result($req,0,case2);
		$_SESSION['case3'] = mysql_result($req,0,case3);
		$_SESSION['case4'] = mysql_result($req,0,case4);
		$_SESSION['case5'] = mysql_result($req,0,case5);
		$_SESSION['case6'] = mysql_result($req,0,case6);
		$_SESSION['planete'] = mysql_result($req,0,planete);
		$_SESSION['pseudo'] = $pseudo ;
		$_SESSION['race'] = $race ;
		$_SESSION['sexe'] = $sexe ;
		$_SESSION['age'] = $age ;
		$_SESSION['taille'] = $taille ;
		$_SESSION['code'] = 0;
		
		$sql = 'SELECT id FROM principal_tbl ORDER BY total DESC' ;
		$req = mysql_query($sql);
		$res = mysql_num_rows($req);

		$t = 1;
		for($i=0;$t!=$res+1;$i++)
			{
			$ids = mysql_result($req,$i,id);
			$sql1 = 'SELECT pseudo FROM principal_tbl WHERE id= "'.$ids.'"' ;
			$req1 = mysql_query($sql1);
			if(mysql_result($req1,0,pseudo)==$_SESSION['pseudo'])
				{
				$_SESSION['classement'] = $t;
				}
			$t = $t + 1;
			}
	
		$sql1 = 'SELECT type,entreprise FROM principal_tbl WHERE id= "'.$_SESSION['id'].'"' ;
		$req1 = mysql_query($sql1);
		$nomr = mysql_result($req1,0,entreprise);
		$poster = mysql_result($req1,0,type);
		
		if(!empty($nomr) && $nomr!="Aucune")
			{
			$sql1 = 'SELECT type FROM `e_'.str_replace(" ","_",''.$nomr.'').'_tbl` WHERE poste= "'.$poster.'"' ;
			$req1 = mysql_query($sql1);
			$typ = mysql_result($req1,0,type);
			}
	
		$sql = 'SELECT informatique,recherche,combat,observation,gestion,maintenance,mecanique,service,economie,tir,medecine FROM principal_tbl WHERE id="'.$_SESSION['id'].'"' ;
		$req = mysql_query($sql);
		$combat = mysql_result($req,0,combat);
		$observation = mysql_result($req,0,observation);
		$gestion = mysql_result($req,0,gestion);
		$maintenance = mysql_result($req,0,maintenance);
		$mecanique = mysql_result($req,0,mecanique);
		$service = mysql_result($req,0,service);
		$informatique = mysql_result($req,0,informatique);
		$recherche = mysql_result($req,0,recherche);
		$economie = mysql_result($req,0,economie);
		$tir = mysql_result($req,0,tir);
		$medecine = mysql_result($req,0,medecine);
		
		if($typ=="maintenance")
			{
			if($maintenance<15)
				{
				$diff = 8;
				}
			elseif($maintenance<30)
				{
				$diff = 6;
				}
			elseif($maintenance<50)
				{
				$diff = 4;
				}
			elseif($maintenance<100)
				{
				$diff = 3;
				}
			elseif($maintenance==100)
				{
				$diff = 2;
				}
			}
		elseif($typ=="securite")
			{
			if($observation<25)
				{
				$diff = 6;
				}
			elseif($observation<100)
				{
				$diff = 4;
				}
			elseif($observation==100)
				{
				$diff = 2;
				}
			}
		elseif($typ=="vendeur")
			{
			if($economie<25)
				{
				$diff = 6;
				}
			elseif($economie<60)
				{
				$diff = 4;
				}
			elseif($economie<100)
				{
				$diff = 3;
				}
			elseif($economie==100)
				{
				$diff = 2;
				}
			}
		elseif($typ=="banquier")
			{
			if($economie<25)
				{
				$diff = 6;
				}
			elseif($economie<60)
				{
				$diff = 4;
				}
			elseif($economie<100)
				{
				$diff = 3;
				}
			elseif($economie==100)
				{
				$diff = 2;
				}
			}
		elseif($typ=="serveur")
			{
			if($service<30)
				{
				$diff = 6;
				}
			elseif($service<100)
				{
				$diff = 4;
				}
			elseif($service==100)
				{
				$diff = 2;
				}
			}
		elseif($typ=="medecin")
			{
			if($medecine<55)
				{
				$diff = 8;
				}
			elseif($medecine<100)
				{
				$diff = 5;
				}
			elseif($medecine==100)
				{
				$diff = 3;
				}
			}
		elseif($typ=="hote")
			{
			if($service<25)
				{
				$diff = 7;
				}
			elseif($service<50)
				{
				$diff = 5;
				}
			elseif($service<100)
				{
				$diff = 4;
				}
			elseif($service==100)
				{
				$diff = 2;
				}
			}
		elseif($typ=="technicien")
			{
			if($mecanique<25)
				{
				$diff = 6;
				}
			elseif($mecanique<100)
				{
				$diff = 4;
				}
			elseif($mecanique==100)
				{
				$diff = 2;
				}
			}
		$sql = 'UPDATE principal_tbl SET difficulte= "'.$diff.'" , SMSdj= " " WHERE id= "'.$_SESSION['id'].'"' ;
		$req = mysql_query($sql);
		}
	}

$sql = 'SELECT id,moment FROM petitesannonces_tbl' ;
$req = mysql_query($sql);
$res2 = mysql_num_rows($req);

for($i=0 ; $i!=$res2 ; $i++)
	{
	$moment = time() - mysql_result($req,$i,moment);
	if($moment>259200)
		{
		$idmoment = mysql_result($req,$i,id);
		$sql2 = 'DELETE FROM petitesannonces_tbl WHERE id= "'.$idmoment.'"' ;
		$req2 = mysql_query($sql2);
		}
	}

mysql_close($db);

				print('');

				if($nbremax==1)
					{
					print('<div id="forum-acces">
					<form action="#" method="post" id="connexion">
						<p><strong>Connexion</strong><br />
						Login <input name="login" type="text" id="log" /><br />
						Mdp <input name="passw" id="mdp" type="password" /><input name="submit" type="submit" value="Ok " id="ok"/>
						</p>
					</form>
					<p id="acces-direct">
					<span class="style1">Le serveur est actuellement saturé.<br />Veuillez réessayer ultérieurement.</span>');
					}
				elseif(($st==1) || ($login==""))
					{
					print('<div id="forum-acces">
					<form action="#" method="post" id="connexion">
						<p><strong>Connexion</strong><br />
						Login <input name="login" type="text" id="log" /><br />
						Mdp <input name="passw" id="mdp" type="password" /><input name="submit" type="submit" value="Ok " id="ok"/>
						</p>
					</form>
					<p id="acces-direct">
					<span class="style1">Mauvais login ou mot de passe.</span>');
					}
				elseif($multi==1)
					{
					print('<div id="forum-acces">
					<form action="#" method="post" id="connexion">
						<p><strong>Connexion</strong><br />
						Login <input name="login" type="text" id="log" /><br />
						Mdp <input name="passw" id="mdp" type="password" /><input name="submit" type="submit" value="Ok " id="ok"/>
						</p>
					</form>
					<p id="acces-direct">
					<span class="style1">Multi-compte d&eacute;tect&eacute;.<br />Veuillez retenter dans 10min.</span>');
					}
				else
					{
					print('<div id="forum-acces">
					<p id="acces-direct">
					Login correct.');
					}
				print('<br />
						<a href="forum=accueil.php">> Acc&egrave;s direct au forum</a>
					</p>
				</div>');
				
				if($nbremax!=1 && !($st==1 || $login=="") && $multi!=1)
					{
					print('<meta http-equiv="refresh" content="0 ; url=#"> ');
					}

				}
			?>
