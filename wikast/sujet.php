<?php 
session_start();

if(empty($_GET['id']))
	{
	print('<meta http-equiv="refresh" content="0 ; url=index.php"> ');
	exit();
	}

$db = mysql_connect('localhost', 'CENSURE', 'CENSURE') or die("Erreur de connexion au serveur des messages.");
mysql_select_db('CENSURE',$db) or die("Erreur de connexion a la base de donnees des messages.");

$sql = 'SELECT * FROM wikast_adaptation_tbl WHERE id_sujet = ' . intval($_GET['id']);
$req = mysql_query($sql);

if (mysql_num_rows($req)) {
	header('Status: 301 Moved Permanently');
	header('Location: http://www.dreadcast.net/Forum/' . mysql_result($req, 0, 'url'));
	exit;
	echo '<div style="display:none;">'.mysql_result($req, 0, 'url').'</div>';
}

header('Status: 301 Moved Permanently');
header('Location: http://www.dreadcast.net/Forum');
exit;

mysql_close();
	

$page = ($_GET['page']=="")?1:$_GET['page'];

if($_GET['a']=='out' && isset($_GET['id'])) {
	$db = mysql_connect('localhost', 'CENSURE', 'CENSURE') or die("Erreur de connexion au serveur des messages.");
	mysql_select_db('CENSURE',$db) or die("Erreur de connexion a la base de donnees des messages.");
	
	$sql = 'DELETE FROM wikast_forum_permissions_tbl WHERE sujet = "'.$_GET['id'].'" AND pseudo = "'.$_SESSION['pseudo'].'"' ;
	$req = mysql_query($sql);
	
	mysql_close();
	
	print('<meta http-equiv="refresh" content="0 ; url=index.php"> ');
	exit();
}

if(isset($_GET['verrou']))
	{
	$db = mysql_connect('localhost', 'CENSURE', 'CENSURE') or die("Erreur de connexion au serveur des messages.");
	mysql_select_db('CENSURE',$db) or die("Erreur de connexion a la base de donnees des messages.");
	
	$sql = 'SELECT categorie,auteur FROM wikast_forum_sujets_tbl WHERE id="'.$_GET['id'].'"' ;
	$req = mysql_query($sql);
	$res = mysql_num_rows($req);
	
	if($res==0)
		{
		print('<meta http-equiv="refresh" content="0 ; url=index.php"> ');
		exit();
		}
	else
		{
		$sql2 = 'SELECT type,admin FROM wikast_forum_structure_tbl WHERE id="'.mysql_result($req,0,categorie).'"' ;
		$req2 = mysql_query($sql2);
		$modoPart = mysql_result($req2,0,admin);
		$sql2 = 'SELECT admin FROM wikast_forum_structure_tbl WHERE id="'.mysql_result($req2,0,type).'"' ;
		$req2 = mysql_query($sql2);
		$modoGlob = mysql_result($req2,0,admin);
		}
	
	if($_SESSION['statut']!="Administrateur" AND $_SESSION['pseudo']!=$modoGlob AND $_SESSION['pseudo']!=$modoPart)
		{
		print('<meta http-equiv="refresh" content="0 ; url=sujet.php?id='.$_GET['id'].'"> ');
		exit();
		}
	else
		{
		if($_GET['verrou']=="oui")
			{
			$db = mysql_connect('localhost', 'CENSURE', 'CENSURE') or die("Erreur de connexion au serveur des messages.");
			mysql_select_db('CENSURE',$db) or die("Erreur de connexion a la base de donnees des messages.");
		
			$sql = 'SELECT nom FROM wikast_forum_sujets_tbl WHERE id= "'.$_GET['id'].'"' ;
			$req = mysql_query($sql);
			$res = mysql_num_rows($req);
		
			if($res==0)
				{
				print('<meta http-equiv="refresh" content="0 ; url=index.php"> ');
				exit();
				}
			elseif(ereg("\[Verrouill&eacute;\]",mysql_result($req,0,nom)))
				{
				print('<meta http-equiv="refresh" content="0 ; url=sujet.php?id='.$_GET['id'].'"> ');
				exit();
				}
			else
				{
				$sql = 'UPDATE wikast_forum_sujets_tbl SET nom="[Verrouill&eacute;] '.mysql_result($req,0,nom).'" WHERE id= "'.$_GET['id'].'"' ;
				mysql_query($sql);
				}
		
			mysql_close($db);
			}
		elseif($_GET['verrou']=="non")
			{
			$db = mysql_connect('localhost', 'CENSURE', 'CENSURE') or die("Erreur de connexion au serveur des messages.");
			mysql_select_db('CENSURE',$db) or die("Erreur de connexion a la base de donnees des messages.");
		
			$sql = 'SELECT nom FROM wikast_forum_sujets_tbl WHERE id= "'.$_GET['id'].'"' ;
			$req = mysql_query($sql);
			$res = mysql_num_rows($req);
		
			if($res==0)
				{
				print('<meta http-equiv="refresh" content="0 ; url=index.php"> ');
				exit();
				}
			elseif(!ereg("\[Verrouill&eacute;\]",mysql_result($req,0,nom)))
				{
				print('<meta http-equiv="refresh" content="0 ; url=sujet.php?id='.$_GET['id'].'"> ');
				exit();
				}
			else
				{
				$nouveau = trim(str_replace("[Verrouill&eacute;]","",mysql_result($req,0,nom)));
				
				$sql = 'UPDATE wikast_forum_sujets_tbl SET nom="'.$nouveau.'" WHERE id= "'.$_GET['id'].'"' ;
				mysql_query($sql);
				}
		
			mysql_close($db);
			}
		}
	}

if(isset($_POST['message']) && $_SESSION['id']!="")
	{
	$message = stripslashes(htmlentities($_POST['message'],ENT_QUOTES));
	
	$message = str_replace("\n", "<br />",$message);
	
	$db = mysql_connect('localhost', 'CENSURE', 'CENSURE') or die("Erreur de connexion au serveur des messages.");
	mysql_select_db('CENSURE',$db) or die("Erreur de connexion a la base de donnees des messages.");
	
	if($_POST['type']=="reponse")
		{
		
		// MISE A ZERO DES COMPTEURS DE VISITE DU SUJET

		$sql = 'SELECT id,sujets_vu FROM wikast_joueurs_tbl WHERE sujets_vu LIKE "%-'.$_GET['id'].'-%"' ;
		$req = mysql_query($sql);
		
		while($result = mysql_fetch_array($req))
			{
			$hop = explode($_GET['id'].'-',$result['sujets_vu'],2);
	
			$sql = 'UPDATE wikast_joueurs_tbl SET sujets_vu="'.$hop[0].$hop[1].'" WHERE id="'.$result['id'].'"' ;
			mysql_query($sql);
			}

		// REMONTE LE SUJET DANS LA LISTE
		
		$sql = 'UPDATE wikast_forum_sujets_tbl SET datemodif="'.time().'" WHERE id="'.$_GET['id'].'"' ;
		mysql_query($sql);
		
		// AJOUTE LE MESSAGE
		
		$sql = 'SELECT MAX(numero) AS maxnum FROM wikast_forum_posts_tbl WHERE sujet="'.$_GET['id'].'"' ;
		$req = mysql_query($sql);
		$res = mysql_num_rows($req);
		
		if($res == 0) $lenum = 0;
		else
			{
			$row = mysql_fetch_array($req);
			$lenum = $row[maxnum];
			}
		
		$lenum++;
		
		if(preg_match('/^\[D([1-9][0-9]{0,3})\]$/', $message, $catch)) {
			$message = '[quote][centrer]'.$_SESSION['pseudo'] . ' a lancé un dé de [G]'.$catch[1].'[/G]. Il a fait [G]'.rand(1, $catch[1]).'[/G].[/centrer][/quote]';
			$sql = 'INSERT INTO wikast_forum_posts_tbl(id,sujet,auteur,date,contenu,numero) VALUES("","'.$_GET['id'].'","Dreadcast","'.time().'","'.$message.'","'.$lenum.'")' ;
		}
		else {
			$sql = 'INSERT INTO wikast_forum_posts_tbl(id,sujet,auteur,date,contenu,numero) VALUES("","'.$_GET['id'].'","'.$_SESSION['pseudo'].'","'.time().'","'.$message.'","'.$lenum.'")' ;
			}
		}
	elseif($_POST['type']=="edit" && $_POST['idmess']!=-1) $sql = 'UPDATE wikast_forum_posts_tbl SET contenu="'.$message.'" WHERE id="'.$_POST['idmess'].'"' ;
	elseif($_POST['type']=="edit" && $_POST['idmess']==-1) $sql = 'UPDATE wikast_forum_sujets_tbl SET contenu="'.$message.'" WHERE id="'.$_GET['id'].'"' ;
	$req = mysql_query($sql);
	
	$sql = 'SELECT nom FROM wikast_forum_sujets_tbl WHERE id="'.$_GET['id'].'"' ;
	$req = mysql_query($sql);
	$titresujet = (mysql_num_rows($req) != 0)?mysql_result($req,0,nom):'';
	
	if($_POST['type']=="edit" && $_POST['idmess']==-1)
		{
		$lid = $_GET['id'];
		$sqltmp = 'SELECT auteur FROM wikast_forum_sujets_tbl WHERE id="'.$_GET['id'].'"' ;
		$reqtmp = mysql_query($sqltmp);
		$restmp = mysql_num_rows($reqtmp);
		if($restmp != 0) $lauteur = mysql_result($reqtmp,0,auteur);
		
		$lecteurs = explode(",",$_POST['editl']);
		$participants = explode(",",$_POST['edite']);
		$moderateurs = explode(",",$_POST['edita']);
		
		$sql3 = 'SELECT pseudo FROM wikast_forum_permissions_tbl WHERE sujet="'.$lid.'" AND pseudo != "'.$lauteur.'"';
		$req3 = mysql_query($sql3);
		$res3 = mysql_num_rows($req3);
		
		$raccrocheauxbranches = "-";
		
		for($j=0;$j<$res3;$j++) $raccrocheauxbranches .= mysql_result($req3,$j,pseudo)."-";
		
		$sql2 = 'DELETE FROM wikast_forum_permissions_tbl WHERE sujet="'.$lid.'"';
		mysql_query($sql2);
		
		$sql2 = 'INSERT INTO wikast_forum_permissions_tbl(id,sujet,pseudo,statut) VALUES("","'.$lid.'","'.$lauteur.'","lea")';
		mysql_query($sql2);
			
		if($_POST['editl'] OR $_POST['edite'] OR $_POST['edita'])
			{
			if($moderateurs[0] != "")
				{
				for($i=0;$i<count($moderateurs);$i++)
					{
					if(trim($moderateurs[$i]) != $lauteur)
						{
						$sql3 = 'SELECT id FROM wikast_forum_permissions_tbl WHERE pseudo="'.trim($moderateurs[$i]).'" AND sujet="'.$lid.'" ';
						$req3 = mysql_query($sql3);
						$res3 = mysql_num_rows($req3);
							
						if($res3 == 0)
							{
							if(!ereg("-".trim($moderateurs[$i])."-",$raccrocheauxbranches))
								{
								$texte = "<br /><br /><br />".$_SESSION['pseudo']." vous a invit&eacute; au sujet priv&eacute; &quot;".$titresujet."&quot; en tant que mod&eacute;rateur.";
								$texte .= "<br /><br />Rendez-vous sur votre forum personnel pour en apprendre plus.";
								$sql2 = 'INSERT INTO wikast_forum_permissions_tbl(id,sujet,pseudo,statut) VALUES("","'.$lid.'","'.trim($moderateurs[$i]).'","lea")';
								mysql_query($sql2);
								$sql2 = 'INSERT INTO messages_tbl(id,auteur,cible,message,objet,moment) VALUES("","Dreadcast","'.trim($moderateurs[$i]).'","'.$texte.'","Sujet priv&eacute;","'.time().'")';
								mysql_query($sql2);
								}
							else
								{
								$sql2 = 'DELETE FROM wikast_forum_permissions_tbl WHERE sujet="'.$lid.'" AND pseudo="'.trim($moderateurs[$i]).'"';
								mysql_query($sql2);
								$sql2 = 'INSERT INTO wikast_forum_permissions_tbl(id,sujet,pseudo,statut) VALUES("","'.$lid.'","'.trim($moderateurs[$i]).'","lea")';
								mysql_query($sql2);
								}
							}
						}
					}
				}
			if($participants[0] != "")
				{
				for($i=0;$i<count($participants);$i++)
					{
					$sql3 = 'SELECT id FROM wikast_forum_permissions_tbl WHERE pseudo="'.trim($participants[$i]).'" AND sujet="'.$lid.'" ';
					$req3 = mysql_query($sql3);
					$res3 = mysql_num_rows($req3);
						
					if($res3 == 0)
						{
						if(!ereg("-".trim($participants[$i])."-",$raccrocheauxbranches))
							{
							$texte = "<br /><br /><br />".$_SESSION['pseudo']." vous a invit&eacute; au sujet priv&eacute; &quot;".$titresujet."&quot; en tant que participant.";
							$texte .= "<br /><br />Rendez-vous sur votre forum personnel pour en apprendre plus.";
							$sql2 = 'INSERT INTO wikast_forum_permissions_tbl(id,sujet,pseudo,statut) VALUES("","'.$lid.'","'.trim($participants[$i]).'","le")';
							mysql_query($sql2);
							$sql2 = 'INSERT INTO messages_tbl(id,auteur,cible,message,objet,moment) VALUES("","Dreadcast","'.trim($participants[$i]).'","'.$texte.'","Sujet priv&eacute;","'.time().'")';
							mysql_query($sql2);
							}
						else
							{
							$sql2 = 'DELETE FROM wikast_forum_permissions_tbl WHERE sujet="'.$lid.'" AND pseudo="'.trim($participants[$i]).'"';
							mysql_query($sql2);
							$sql2 = 'INSERT INTO wikast_forum_permissions_tbl(id,sujet,pseudo,statut) VALUES("","'.$lid.'","'.trim($participants[$i]).'","le")';
							mysql_query($sql2);
							}
						}

					}
				}
			if($lecteurs[0] != "")
				{
				for($i=0;$i<count($lecteurs);$i++)
					{
					$sql3 = 'SELECT id FROM wikast_forum_permissions_tbl WHERE pseudo="'.trim($lecteurs[$i]).'" AND sujet="'.$lid.'" ';
					$req3 = mysql_query($sql3);
					$res3 = mysql_num_rows($req3);
						
					if($res3 == 0)
						{
						if(!ereg("-".trim($lecteurs[$i])."-",$raccrocheauxbranches))
							{
							$texte = "<br /><br /><br />".$_SESSION['pseudo']." vous a invit&eacute; au sujet priv&eacute; &quot;".$titresujet."&quot; en tant que lecteur.";
							$texte .= "<br /><br />Rendez-vous sur votre forum personnel pour en apprendre plus.";
							$sql2 = 'INSERT INTO wikast_forum_permissions_tbl(id,sujet,pseudo,statut) VALUES("","'.$lid.'","'.trim($lecteurs[$i]).'","l")';
							mysql_query($sql2);
							$sql2 = 'INSERT INTO messages_tbl(id,auteur,cible,message,objet,moment) VALUES("","Dreadcast","'.trim($lecteurs[$i]).'","'.$texte.'","Sujet priv&eacute;","'.time().'")';
							mysql_query($sql2);
							}
						else
							{
							$sql2 = 'DELETE FROM wikast_forum_permissions_tbl WHERE sujet="'.$lid.'" AND pseudo="'.trim($lecteurs[$i]).'"';
							mysql_query($sql2);
							$sql2 = 'INSERT INTO wikast_forum_permissions_tbl(id,sujet,pseudo,statut) VALUES("","'.$lid.'","'.trim($lecteurs[$i]).'","l")';
							mysql_query($sql2);
							}
						}
					}
				}
			}
		}
	
	mysql_close($db);
	}
	
if(isset($_POST['deplacement']) && isset($_GET['id']))
	{
	$db = mysql_connect('localhost', 'CENSURE', 'CENSURE') or die("Erreur de connexion au serveur des messages.");
	mysql_select_db('CENSURE',$db) or die("Erreur de connexion a la base de donnees des messages.");
		
	
	$sql = 'SELECT categorie FROM wikast_forum_sujets_tbl WHERE id="'.$_GET['id'].'"';
	$req = mysql_query($sql);
	$res = mysql_num_rows($req);
	if($res!=0) 
		{
		$sql = 'SELECT admin,type FROM wikast_forum_structure_tbl WHERE id="'.mysql_result($req,0,categorie).'"';
		$req = mysql_query($sql);
		$res = mysql_num_rows($req);
		if($res!=0)
			{
			$modoPart = mysql_result($req,0,admin);
			$sql = 'SELECT admin FROM wikast_forum_structure_tbl WHERE id="'.mysql_result($req,0,type).'"';
			$req = mysql_query($sql);
			$res = mysql_num_rows($req);
			if($res!=0) $modoGlob = mysql_result($req,0,admin);
			
			if($_SESSION['pseudo']==$modoGlob OR $_SESSION['pseudo']==$modoPart OR $_SESSION['statut']=="Administrateur")
				{		
				$sql = 'UPDATE wikast_forum_sujets_tbl SET categorie="'.$_POST['deplacement'].'" WHERE id="'.$_GET['id'].'"';
				mysql_query($sql);
				}
			}
		}

	mysql_close($db);
	}
	
if(empty($_SESSION['id'])) $_SESSION['statut'] = "visiteur";
else
	{
	$db = mysql_connect('localhost', 'CENSURE', 'CENSURE') or die("Erreur de connexion au serveur des messages.");
	mysql_select_db('CENSURE',$db) or die("Erreur de connexion a la base de donnees des messages.");
	
	$sql = 'SELECT sujets_vu FROM wikast_joueurs_tbl WHERE pseudo="'.$_SESSION['pseudo'].'"' ;
	$req = mysql_query($sql);
	
	if(mysql_num_rows($req)>0 && !ereg('-'.$_GET['id'].'-',mysql_result($req,0,sujets_vu)))
		{
		$sql = 'UPDATE wikast_joueurs_tbl SET sujets_vu="'.mysql_result($req,0,sujets_vu).$_GET['id'].'-" WHERE pseudo="'.$_SESSION['pseudo'].'"' ;
		mysql_query($sql);
		}
	
	mysql_close($db);
	}

include('include/inc_head.php'); ?>

		<div id="page">
			
			<?php include('include/inc_barre1.php'); ?>
		
			<a href="forum=accueil.php" id="lien-forum"></a>
			
			<!-- -------------------------------------------------------------------------- MISE EN PAGE GENERALE -------------------------------------------------------------------------- -->
			<div id="forum">
			
				<!-- PARTIE DU HAUT : FORUM -->
				
				<?php include('include/inc_connexion.php');
				
				include('include/inc_forumrubriques.php');
				
				include('include/inc_forumderniers.php'); ?>
				
				<div id="forum-recherche">
					<!-- RECHERCHE DANS LE FORUM -->
					<form method="post" action="forum=recherche.php">
						Rechercher <input type="text" name="recherche" class="champ" /> <input type="submit" value="" class="valid" />
					</form>
				</div>
			</div>
			
			<?php
			
			$db = mysql_connect('localhost', 'CENSURE', 'CENSURE') or die("Erreur de connexion au serveur des messages.");
			mysql_select_db('CENSURE',$db) or die("Erreur de connexion a la base de donnees des messages.");
			
			$sql = 'SELECT categorie,nom,contenu,auteur,date FROM wikast_forum_sujets_tbl WHERE id= "'.$_GET['id'].'"' ;
			$req = mysql_query($sql);
			$res = mysql_num_rows($req);
			
			if($res!=0)
				{
				$titresujet = mysql_result($req,0,"nom");
				$textesujet = mysql_result($req,0,"contenu");
				$auteursujet = mysql_result($req,0,"auteur");
				$datesujet = mysql_result($req,0,"date");
				
				$sqlhop = 'SELECT commentaire FROM wikast_joueurs_tbl WHERE pseudo= "'.$auteursujet.'" AND infoperso LIKE "%-signature-%"' ;
				$reqhop = mysql_query($sqlhop);
				$reshop = mysql_num_rows($reqhop);
				
				if($reshop!=0) $textesujet .= '<br /><br /><span class="couleur1">________________________________________________________________<br />'.mysql_result($reqhop,0,"commentaire").'</span>';
				
				$textesujet = transforme_texte($textesujet);
				
///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
					if(${$auteursujet}=="")
						{
						$sqla = 'SELECT id,statut,avatar,race,sexe,connec FROM principal_tbl WHERE pseudo= "'.$auteursujet.'"' ;
						$reqa = mysql_query($sqla);
						$resa = mysql_num_rows($reqa);
							
						$idauteur = ($resa!=0)? mysql_result($reqa,0,"id"):"";
						$statutauteur = ($resa!=0)? mysql_result($reqa,0,"statut"):"";
						if($statutauteur == "Compte VIP") $statutauteur = "VIP";
						if($auteursujet == 'Themista' || $auteursujet == 'Vinci') $statutauteur = 'MdJ';
						$avatarauteur = ($resa!=0)? mysql_result($reqa,0,"avatar"):"";
						$raceauteur = ($resa!=0)? mysql_result($reqa,0,"race"):"";
						$sexeauteur = ($resa!=0)? mysql_result($reqa,0,"sexe"):"";
						$connecauteur = ($resa!=0)? mysql_result($reqa,0,"connec"):"";
							
						${$auteursujet} = $idauteur.";".$statutauteur.';'.$avatarauteur.';'.$raceauteur.';'.$sexeauteur;
						}
					else
						{
						list($idauteur,$statutauteur,$avatarauteur,$raceauteur,$sexeauteur) = explode(";", ${$auteursujet});
						}
///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
				
				$sqlbof = 'SELECT id FROM principal_tbl ORDER BY total DESC' ;
				$reqbof = mysql_query($sqlbof);
				$resbof = mysql_num_rows($reqbof);
				
				$classementauteur=0;
				$bof=0;
				
				while($bof<$resbof)
					{
					if(mysql_result($reqbof,$bof,id)==$idauteur)
						{
						$classementauteur = $bof + 1;
						break;
						}
					$bof++;
					}
				
				$sql2 = 'SELECT MAX(numero) AS maxnum FROM wikast_forum_posts_tbl WHERE sujet="'.$_GET['id'].'"' ;
				$req2 = mysql_query($sql2);
				$res2 = mysql_num_rows($req2);
		
				if($res2 == 0) $res2 = 1;
				else
					{
					$row = mysql_fetch_array($req2);
					$res2 = $row[maxnum];
					}
					
				$pagemax = ceil(($res2+1) / 20);
				
				if($page=="max") $page = $pagemax;
				if($page>$pagemax) $page = $pagemax;
									
				$max = 20*$page-1;
				$min = 20*$page-20;
				if($min==0) $min = 1;
				
				$sql2 = 'SELECT * FROM wikast_forum_posts_tbl WHERE sujet="'.$_GET['id'].'" AND numero <= '.$max.' AND numero >= '.$min.' ORDER BY date ASC' ;
				$req2 = mysql_query($sql2);
				$res2 = mysql_num_rows($req2);
				
				}
			else $res2 = 0;

			if(mysql_result($req,0,"categorie") != -1)
			{
				if(mysql_result($req,0,"categorie") == "65" AND $_SESSION['statut']!="Administrateur") // Poubelle
					{
					print('<meta http-equiv="refresh" content="0 ; url=index.php"> ');
					exit();
					}
				elseif(mysql_result($req,0,"categorie") == "126") // Modos
					{
					$sqlaz = 'SELECT id FROM wikast_forum_structure_tbl WHERE admin="'.$_SESSION['pseudo'].'" AND (id="1" OR id="2" OR id="3" OR id="4")' ;
					$reqaz = mysql_query($sqlaz);
					$resaz = mysql_num_rows($reqaz);
					if($resaz==0 AND $_SESSION['statut']!="Administrateur")
						{
						print('<meta http-equiv="refresh" content="0 ; url=index.php"> ');
						exit();
						}
					}
				elseif(mysql_result($req,0,"categorie") == "118") // CI
					{
					$sqlaz = 'SELECT id FROM principal_tbl WHERE pseudo="'.$_SESSION['pseudo'].'" AND (entreprise="Conseil Imperial" AND (type="President" OR type="Premier Conseiller" OR type="Conseiller Imperial"))';
					$reqaz = mysql_query($sqlaz);
					$resaz = mysql_num_rows($reqaz);
					if($resaz==0 AND $_SESSION['statut']!="Administrateur")
						{
						print('<meta http-equiv="refresh" content="0 ; url=index.php"> ');
						exit();
						}
					}
				elseif(mysql_result($req,0,"categorie") == "144") // Directoire
					{
					$sqlaz = 'SELECT id FROM principal_tbl WHERE pseudo="'.$_SESSION['pseudo'].'" AND ((type="President" AND entreprise="Conseil Imperial") OR (type="Directeur des Organisations" AND entreprise="DOI") OR (type="Premier consul" AND entreprise="Chambre des lois") OR (type="Directeur du DI2RCO" AND entreprise="DI2RCO") OR (type="Directeur des services" AND entreprise="Services techniques de la ville") OR (type="Directeur du CIPE" AND entreprise="CIPE") OR (type="Directeur du CIE" AND entreprise="CIE") OR (type="Chef de la Police" AND entreprise="Police") OR (type="Directeur de la Prison" AND entreprise="Prison") OR (type="Directeur du DC Network" AND entreprise="DC Network"))' ;
					$reqaz = mysql_query($sqlaz);
					$resaz = mysql_num_rows($reqaz);
					if($resaz==0 AND $_SESSION['statut']!="Administrateur")
						{
						print('<meta http-equiv="refresh" content="0 ; url=index.php"> ');
						exit();
						}
					}
				elseif(mysql_result($req,0,"categorie") == "145") // CdL
					{
					$sqlaz = 'SELECT id FROM principal_tbl WHERE pseudo="'.$_SESSION['pseudo'].'" AND ((type="Premier consul" OR type="Consul" OR type="Proconsul") AND entreprise="Chambre des lois")' ;
					$reqaz = mysql_query($sqlaz);
					$resaz = mysql_num_rows($reqaz);
					if($resaz==0 AND $_SESSION['statut']!="Administrateur")
						{
						print('<meta http-equiv="refresh" content="0 ; url=index.php"> ');
						exit();
						}
					}
				else // Cercle
					{
					$sqlaz = 'SELECT id,nom FROM wikast_forum_structure_tbl WHERE id=(SELECT type FROM wikast_forum_structure_tbl WHERE id="'.mysql_result($req,0,"categorie").'")' ;
					$reqaz = mysql_query($sqlaz);
					$resaz = mysql_num_rows($reqaz);
					if($resaz!=0 && $_SESSION['statut']!="Administrateur" && mysql_result($reqaz,0,id) != "1" && mysql_result($reqaz,0,id) != "2" && mysql_result($reqaz,0,id) != "3" && mysql_result($reqaz,0,id) != "4" && str_replace("Cercle ","",mysql_result($reqaz,0,nom)) != $nomcercle)
						{
						print('<meta http-equiv="refresh" content="0 ; url=index.php"> ');
						exit();
						}
					}
			$sqltmp = 'SELECT nom,type,admin FROM wikast_forum_structure_tbl WHERE id= "'.mysql_result($req,0,"categorie").'"' ;
			$reqtmp = mysql_query($sqltmp);
			$forumid = mysql_result($reqtmp,0,"type");
			$ssforum = mysql_result($reqtmp,0,"nom");
			$ssforumid = mysql_result($req,0,"categorie");
			$modoPart = mysql_result($reqtmp,0,"admin");
			$sqltmp = 'SELECT nom,admin FROM wikast_forum_structure_tbl WHERE id= "'.mysql_result($reqtmp,0,"type").'"' ;
			$reqtmp = mysql_query($sqltmp);
			$forum = mysql_result($reqtmp,0,"nom");
			$modoGlob = mysql_result($reqtmp,0,"admin");
			}
			else $ssforumid = -1;
			
			$statutauteur = ($statutauteur != "Administrateur" AND ( $auteursujet == $modoGlob OR $auteursujet == $modoPart ))?"Mod&eacute;rateur":$statutauteur;
			
			if($ssforumid == -1)
				{
				$sqltmp2 = 'SELECT statut FROM wikast_forum_permissions_tbl WHERE pseudo="'.$auteursujet.'" AND sujet="'.$_GET['id'].'"' ;
				$reqtmp2 = mysql_query($sqltmp2);
				$restmp2 = mysql_num_rows($reqtmp2);
				
				$statutauteur = mysql_result($reqtmp2,0,"statut");
				$statutauteur = (ereg("a",$statutauteur))?"Mod&eacute;rateur":((ereg("e",$statutauteur))?"Participant":"Lecteur");
				
				$sqltmp3 = 'SELECT statut FROM wikast_forum_permissions_tbl WHERE pseudo="'.$_SESSION['pseudo'].'" AND sujet="'.$_GET['id'].'"' ;
				$reqtmp3 = mysql_query($sqltmp3);
				$restmp3 = mysql_num_rows($reqtmp3);
				
				if($restmp3 == 0 AND $_SESSION['statut'] != "Administrateur")
					{
					print('<meta http-equiv="refresh" content="0 ; url=forum=perso.php"> ');
					exit();
					}
				else
					{
					$monstatut = mysql_result($reqtmp3,0,"statut");
					$monstatut = (ereg("a",$monstatut))?"Mod&eacute;rateur":((ereg("e",$monstatut))?"Participant":"Lecteur");
					}
				}
			
			print('<div id="forum-entete">');
			
			include('include/inc_barreliens1.php');	
				
			print('
					
				<div id="forum-info2">
					<p class="gauche">');
					if($_SESSION['id']!="" && $ssforumid != -1) print('<a href="../ingame/engine=contacter.php?cible='.$modoPart.'&forum='.$ssforumid.'" onclick="window.open(this.href); return false;">Contacter<br />un mod&eacute;rateur</a>');
					print('</p>
					
					<p class="droite">');
						if($_SESSION['statut']!="visiteur" AND (!ereg("\[Verrouill&eacute;\]",$titresujet) OR (ereg("\[Verrouill&eacute;\]",$titresujet) AND $_SESSION['statut']=="Administrateur")) AND (htmlentities($ssforum) != "Annonces officielles" OR (htmlentities($ssforum) == "Annonces officielles" AND $_SESSION['statut']=="Administrateur")))
						{
						if($monstatut == "" OR ($monstatut == "Participant" OR $monstatut == "Mod&eacute;rateur")) print('<a href="sujet=repondre.php?id='.$_GET['id'].'#bas" title="R&eacute;pondre">R&eacute;pondre</a><br />');
						}
						if($_SESSION['statut']!="visiteur" AND (!ereg("\[Verrouill&eacute;\]",$titresujet) OR (ereg("\[Verrouill&eacute;\]",$titresujet) AND $_SESSION['statut']=="Administrateur")) AND (htmlentities($ssforum) != "Annonces officielles" OR (htmlentities($ssforum) == "Annonces officielles" AND $_SESSION['statut']=="Administrateur")))
						{
						if($monstatut == "") print('<a href="sujet=nouveau.php?ssfid='.mysql_result($req,0,categorie).'" title="Nouveau sujet">Nouveau sujet</a><br />');
						else print('<a href="sujet=nouveau.php?prive=ok" title="Nouveau sujet">Nouveau sujet</a><br />');
						}
						if($_SESSION['statut']=="visiteur") print('<br /><br />');
						print('<a href="#bas" title="Descendre en bas de page">Bas de page</a><br />
						<span class="pages">');
						
						if($page!=1) print('<a href="sujet.php?id='.$_GET['id'].'&page=1" title="Premi&egrave;re page" style="font-size:14px;">&laquo;</a><a href="sujet.php?id='.$_GET['id'].'&page='.($page-1).'" title="Page précédente" style="position:relative;bottom:1px;font-size:11px;"><</a>');
						else print('<span class="couleur1" style="font-size:13px;">&laquo;<span style="position:relative;bottom:1px;font-size:11px;"><</span></span>');
						print(' <a href="#bas">Page <span class="couleur2">'.$page.'</span> de <span class="couleur2">'.$pagemax.'</span></a> ');
						if($page!=$pagemax) print('<a href="sujet.php?id='.$_GET['id'].'&page='.($page+1).'" title="Page suivante" style="position:relative;bottom:1px;font-size:11px;">></a><a href="sujet.php?id='.$_GET['id'].'&page='.$pagemax.'" title="Derni&egrave;re page" style="font-size:14px;">&raquo;</a>');
						else print('<span class="couleur1" style="font-size:13px;"><span style="position:relative;bottom:1px;font-size:11px;">></span>&raquo;</span>');
						
				print('</span>
					</p>
				</div>
			</div>');
			
			/* if($_SESSION['statut']!="Compte VIP") include('include/inc_pub.php'); */
			
			print('<div id="mainpage-forum">
				<div id="haut">
					');
					if($ssforumid != -1) print('<a href="forum.php?partie='.$forum.'&sous-partie='.$ssforumid.'" title="Retour" id="btn_retour"></a>');
					else print('<a href="forum=perso.php" title="Retour" id="btn_retour"></a>');
					if($ssforumid != -1) print('<p><a href="forum.php?partie='.$forum.'" title="Vers le forum '.$forum.'">Forum '.$forum.'</a> > <a href="forum.php?partie='.$forum.'&sous-partie='.$ssforumid.'" title="Vers le sous-forum '.$ssforum.'">'.$ssforum.'</a></p>');
					else print('<p><a href="forum=perso.php" title="Vers le forum personnel">Forum personnel</a></p>');
					print('
				</div>
					
				<div id="contenu">
					
					<div class="titre-sujet">
						<p class="titre">'.$titresujet.'</p>
						<p class="infos">Sujet post&eacute; par <span class="style1">'.$auteursujet.'</span> le '.date('d/m/Y',$datesujet).' &agrave '.date('H:i',$datesujet).'</p>
					</div>
					');
			
					if($page==1)
						{
					
					print('
					<div class="premierpost" id="-1">
						<div class="boutons">');
							if($_SESSION['statut']!="visiteur" AND (htmlentities($ssforum) != "Annonces officielles" OR (htmlentities($ssforum) == "Annonces officielles" AND $_SESSION['statut']=="Administrateur")) AND (!ereg("\[Verrouill&eacute;\]",$titresujet) OR (ereg("\[Verrouill&eacute;\]",$titresujet) AND $_SESSION['statut']=="Administrateur")))
								{
								if($monstatut == "" OR ($monstatut == "Participant" OR $monstatut == "Mod&eacute;rateur")) print('<a href="sujet=repondre.php?id='.$_GET['id'].'&mess=-1#bas" class="quote" title="Citer ce message"></a>');
								if($monstatut == "Mod&eacute;rateur" OR $_SESSION['id']==$idauteur OR $_SESSION['statut']=="Administrateur" OR $_SESSION['pseudo']==$modoGlob OR $_SESSION['pseudo']==$modoPart)
								print('
								<a href="javascript:ConfirmSuppr(\''.$_GET['id'].'\',\'-1\');" class="suppr" title="Supprimer ce message"></a>
								<a href="sujet=editer.php?id='.$_GET['id'].'&mess=-1" class="edit" title="Editer ce message"></a>');
								}
						print('
						</div>
						<div class="info-perso">
							<a href="../ingame/engine=contacter.php?cible='.$auteursujet.'" class="titre">'.$auteursujet.'</a>
							<div class="avatar"><img src="');
							
							if($avatarauteur != "")
							{
							if((ereg("http",$avatarauteur)) OR (ereg("ftp",$avatarauteur))) print($avatarauteur);
							else print('../ingame/avatars/'.$avatarauteur);
							}
							else print('../ingame/avatars/interogation.jpg');
							
							print('" alt="Avatar" width="68" height="68" /></div>
							<div class="indicateurs">
								<img src="design/avatar_'.$raceauteur.'.gif" alt="'.$raceauteur.'" title="'.$raceauteur.'" /><br />
								<img src="design/icon_'.$sexeauteur.'.gif" alt="'.$sexeauteur.'" title="'.$sexeauteur.'" /><br />
								<img src="design/icon_connec-'.$connecauteur.'.gif" alt="');if($connecauteur=="oui")print('En ligne');elseif($connecauteur=="non") print('Absent');print('" title="');if($connecauteur=="oui")print('En ligne');else print('Absent');print('" /><br />
							</div>
							<p>');
								
								$sqlhop = 'SELECT infoperso FROM wikast_joueurs_tbl WHERE pseudo= "'.$auteursujet.'" AND infoperso LIKE "%-talentchoisi%"' ;
								$reqhop = mysql_query($sqlhop);
								$reshop = mysql_num_rows($reqhop);
								if($reshop)
									{
									$titre = ucfirst(preg_replace("#^-talentchoisi:(.+)-#isU","$1",mysql_result($reqhop,0,infoperso))).'<br />';
									if(ereg("talentchoisi",$titre)) $titre = ucfirst(preg_replace("#(.+)-talentchoisi:(.+)-#isU","$2",mysql_result($reqhop,0,infoperso))).'<br />';
									}
								else $titre = "";
								
								if($classementauteur != 0) print($titre.$statutauteur.'<br />
								<!--Classement : '.$classementauteur.'<br />-->
								<a href="edc=visio.php?auteur='.$auteursujet.'" title="Voir son Espace DreadCast">Voir son EDC</a>');
								else print('Compte<br />supprim&eacute;');
							print('</p>
						</div>
						<div class="texte">');
						
						if($ssforumid == -1)
						{
						$sqltmp2 = 'SELECT pseudo FROM wikast_forum_permissions_tbl WHERE statut="l" AND sujet="'.$_GET['id'].'"' ;
						$reqtmp2 = mysql_query($sqltmp2);
						$restmp2 = mysql_num_rows($reqtmp2);
						if($restmp2 != 0) $lecteurs2 = mysql_result($reqtmp2,0,"pseudo");
						for($i=1;$i<$restmp2;$i++) $lecteurs2 .= ", ".strtolower(ucfirst(mysql_result($reqtmp2,$i,"pseudo")));
						$lecteurs2 = ($lecteurs2 == "")?"Aucun":$lecteurs2;
						
						$sqltmp2 = 'SELECT pseudo FROM wikast_forum_permissions_tbl WHERE statut="le" AND sujet="'.$_GET['id'].'"' ;
						$reqtmp2 = mysql_query($sqltmp2);
						$restmp2 = mysql_num_rows($reqtmp2);
						if($restmp2 != 0) $participants2 = mysql_result($reqtmp2,0,"pseudo");
						for($i=1;$i<$restmp2;$i++) $participants2 .= ", ".strtolower(ucfirst(mysql_result($reqtmp2,$i,"pseudo")));
						$participants2 = ($participants2 == "")?"Aucun":$participants2;
						
						$sqltmp2 = 'SELECT pseudo FROM wikast_forum_permissions_tbl WHERE statut="lea" AND sujet="'.$_GET['id'].'"' ;
						$reqtmp2 = mysql_query($sqltmp2);
						$restmp2 = mysql_num_rows($reqtmp2);
						if($restmp2 != 0) $moderateurs2 = mysql_result($reqtmp2,0,"pseudo");
						for($i=1;$i<$restmp2;$i++) $moderateurs2 .= ", ".strtolower(ucfirst(mysql_result($reqtmp2,$i,"pseudo")));				
				
						print('<div class="text-quote"><strong>Statuts</strong>');
						if($monstatut == "Mod&eacute;rateur" || $_SESSION['statut']=="Administrateur") print(' - <a href="sujet=editer.php?id='.$_GET['id'].'&mess=-1">Modifier</a>');
						$fixurl = ' (<a href="sujet.php?id='.$_GET['id'].'&a=out">partir</a>)';
						print('<br /><br />
						Lecteur(s) : '.str_replace(strtolower(ucfirst($_SESSION["pseudo"])),$_SESSION["pseudo"].$fixurl,$lecteurs2).'<br />
						Participant(s) : '.str_replace(strtolower(ucfirst($_SESSION["pseudo"])),$_SESSION["pseudo"].$fixurl,$participants2).'<br />
						Mod&eacute;rateur(s) : '.str_replace(strtolower(ucfirst($_SESSION["pseudo"])),$_SESSION["pseudo"].$fixurl,$moderateurs2).'</div><br />');
						}
						
						print($textesujet.'</div>
					</div>');
						}
					
					if($_SESSION['statut']=="Administrateur" OR $_SESSION['pseudo']==$modoGlob OR $_SESSION['pseudo']==$modoPart) print('<form method="post" action="sujet=supprimer.php">
					<input type="hidden" name="supprmass" value="ok" />
					<input type="hidden" name="sujet" value="'.$_GET['id'].'" />
					<input type="hidden" name="debut" value="'.$min.'" />
					<input type="hidden" name="fin" value="'.$max.'" />
					');
					
					for($i=0 ; $i<$res2 ; $i++)
						{
						
						$idmess = mysql_result($req2,$i,"id");
						$textemess = mysql_result($req2,$i,"contenu");
						$auteurmess = mysql_result($req2,$i,"auteur");
						$datemess = mysql_result($req2,$i,"date");
						$numeromess = mysql_result($req2,$i,"numero");
						
						$sqlhop = 'SELECT commentaire FROM wikast_joueurs_tbl WHERE pseudo= "'.$auteurmess.'" AND infoperso LIKE "%-signature-%"' ;
						$reqhop = mysql_query($sqlhop);
						$reshop = mysql_num_rows($reqhop);
					
						if($reshop!=0) $textemess .= '<br /><br /><div class="couleur1" style="text-align:center;">________________________________________________________________</div><span class="couleur1">'.mysql_result($reqhop,0,"commentaire").'</span>';
						
						$textemess = transforme_texte($textemess);
						
///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
						if(${$auteurmess}=="")
							{
							$sqla = 'SELECT id,statut,avatar,race,sexe,connec FROM principal_tbl WHERE pseudo= "'.$auteurmess.'"' ;
							$reqa = mysql_query($sqla);
							$resa = mysql_num_rows($reqa);
							
							$idauteur = ($resa!=0)? mysql_result($reqa,0,"id"):"";
							$statutauteur = ($resa!=0)? mysql_result($reqa,0,"statut"):"";
							if($statutauteur == "Compte VIP") $statutauteur = "VIP";
							if($auteurmess == 'Themista' || $auteurmess == 'Vinci') $statutauteur = 'MdJ';
							$avatarauteur = ($resa!=0)? mysql_result($reqa,0,"avatar"):"";
							$raceauteur = ($resa!=0)? mysql_result($reqa,0,"race"):"";
							$sexeauteur = ($resa!=0)? mysql_result($reqa,0,"sexe"):"";
							$connecauteur = ($resa!=0)? mysql_result($reqa,0,"connec"):"";
							
							${$auteurmess} = $idauteur.";".$statutauteur.';'.$avatarauteur.';'.$raceauteur.';'.$sexeauteur;
							}
						else
							{
							list($idauteur,$statutauteur,$avatarauteur,$raceauteur,$sexeauteur) = explode(";", ${$auteurmess});
							}
///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
						$sqlbof = 'SELECT id FROM principal_tbl ORDER BY total DESC' ;
						$reqbof = mysql_query($sqlbof);
						$resbof = mysql_num_rows($reqbof);
				
						$classementauteur=0;
						$bof=0;
				
						while($bof<$resbof)
							{
							if(mysql_result($reqbof,$bof,id)==$idauteur)
								{
								$classementauteur = $bof + 1;
								break;
								}
							$bof++;
							}
							
						$statutauteur = ($statutauteur != "Administrateur" AND ( $auteurmess == $modoGlob OR $auteurmess == $modoPart ))?"Mod&eacute;rateur":$statutauteur;
						
					if($ssforumid == -1)
						{
						$sqltmp2 = 'SELECT statut FROM wikast_forum_permissions_tbl WHERE pseudo="'.$auteurmess.'" AND sujet="'.$_GET['id'].'"' ;
						$reqtmp2 = mysql_query($sqltmp2);
						$restmp2 = mysql_num_rows($reqtmp2);
				
						if($restmp2 = mysql_num_rows($reqtmp2))
							{
							$statutauteur = mysql_result($reqtmp2,0,"statut");
							$statutauteur = (ereg("a",$statutauteur))?"Mod&eacute;rateur":((ereg("e",$statutauteur))?"Participant":"Lecteur");
							}
						else
							{
							$statutauteur = "Ancien membre";
							}
						}
						
					print('<div class="post"  id="'.$idmess.'"'.($auteurmess == 'Dreadcast' ? ' style="min-height:70px;height:70px;"' : '').'>
						<div class="barre-infos">
							<p class="infos">Post&eacute; par <span class="style1">'.$auteurmess.'</span> le '.date('d/m/Y',$datemess).' &agrave '.date('H:i',$datemess).'</p>
						</div>
						<div class="boutons">');
						if($_SESSION['statut']!="visiteur" AND (htmlentities($ssforum) != "Annonces officielles" OR (htmlentities($ssforum) == "Annonces officielles" AND $_SESSION['statut']=="Administrateur")) AND (!ereg("\[Verrouill&eacute;\]",$titresujet) OR (ereg("\[Verrouill&eacute;\]",$titresujet) AND $_SESSION['statut']=="Administrateur")))
								{
								if($monstatut == "" OR ($monstatut == "Participant" OR $monstatut == "Mod&eacute;rateur") || $_SESSION['statut']=="Administrateur") print('<a href="sujet=repondre.php?id='.$_GET['id'].'&mess='.$idmess.'#bas" class="quote" title="Citer ce message"></a>');
								if($monstatut == "Mod&eacute;rateur" OR $_SESSION['id']==$idauteur OR $_SESSION['statut']=="Administrateur" OR $_SESSION['pseudo']==$modoGlob OR $_SESSION['pseudo']==$modoPart)
									{
									//if($monstatut == "" OR $monstatut == "Mod&eacute;rateur")
									print('<a href="javascript:ConfirmSuppr(\''.$_GET['id'].'\',\''.$idmess.'\');" class="suppr" title="Supprimer ce message"></a>');
									if ($auteurmess != 'Dreadcast') print('<a href="sujet=editer.php?id='.$_GET['id'].'&mess='.$idmess.'#'.$idmess.'" class="edit" title="Editer ce message"></a>');
									if($_SESSION['statut']=="Administrateur" OR $_SESSION['pseudo']==$modoGlob OR $_SESSION['pseudo']==$modoPart) print('<span class="supprmass"><input type="checkbox" name="supprimer_'.$numeromess.'" value="ok" /></span>');
									}
								}
						print('</div>');
						if ($auteurmess != 'Dreadcast') {
						print('<div class="info-perso">
							<a href="../ingame/engine=contacter.php?cible='.$auteurmess.'" class="titre">'.$auteurmess.'</a>
							<div class="avatar"'.($auteurmess == 'Dreadcast' ? ' style="margin-left:17px;margin-top:13px;"' : '').'><img src="');
							
							if($avatarauteur != "")
							{
							if((ereg("http",$avatarauteur)) OR (ereg("ftp",$avatarauteur))) print($avatarauteur);
							else print('../ingame/avatars/'.$avatarauteur);
							}
							elseif ($auteurmess == 'Dreadcast')  {
								$raceauteur = '';
								$sexeauteur = '';
								$connecauteur = '';
								print('../ingame/avatars/wikast.jpg');
							}
							else print('../ingame/avatars/interogation.jpg');
							
							print('" alt="Avatar" width="68" height="68" /></div>
							<div class="indicateurs">
								<img src="design/avatar_'.$raceauteur.'.gif" alt="'.$raceauteur.'" title="'.$raceauteur.'" /><br />
								<img src="design/icon_'.$sexeauteur.'.gif" alt="'.$sexeauteur.'" title="'.$sexeauteur.'" /><br />
								<img src="design/icon_connec-'.$connecauteur.'.gif" alt="');if($connecauteur=="oui")print('En ligne');elseif($connecauteur=="non") print('Absent');print('" title="');if($connecauteur=="oui")print('En ligne');else print('Absent');print('" /><br />
							</div>
							<p>');
							
								$sqlhop = 'SELECT infoperso FROM wikast_joueurs_tbl WHERE pseudo= "'.$auteurmess.'" AND infoperso LIKE "%-talentchoisi%"' ;
								$reqhop = mysql_query($sqlhop);
								$reshop = mysql_num_rows($reqhop);
								if($reshop)
									{
									$titre = ucfirst(preg_replace('/(.*)-talentchoisi:([^-]*)-(.*)/',"$2",mysql_result($reqhop,0,infoperso))).'<br />';
									//if(ereg("talentchoisi",$titre)) $titre = ucfirst(preg_replace("#(.*)-talentchoisi:([^-]+)-#isU","$2",mysql_result($reqhop,0,infoperso))).'<br />';
									}
								else $titre = "";
								if ($titre == '<br />')$titre = "";
								
								if($classementauteur != 0) print($titre.$statutauteur.'<br />
								<!--Classement : '.$classementauteur.'<br />-->
								<a href="edc=visio.php?auteur='.$auteurmess.'" title="Voir son Espace DreadCast">Voir son EDC</a>');
								elseif ($auteurmess != 'Dreadcast') print('Compte<br />supprim&eacute;');
							print('</p>
						</div>');
						}
						print('<div class="texte">'.$textemess.'</div>
					</div>');
						}
					
					print('	
					
				</div>
					
				<div id="bas">
					
					<div class="remonter"><a href="#forum" title="Remonter en haut de la page">Remonter</a></div>');
					
					if($pagemax!=1)
						{
						if($_SESSION['statut']=="Administrateur" OR $_SESSION['pseudo']==$modoGlob OR $_SESSION['pseudo']==$modoPart)  print('<div class="validsupprmass"><input type="submit" value="Supprimer" name="submit" /></div>
						</form>');
						print('
						<form class="pages" method="post" action="">
							Pages :<br />
							<div class="style1">
							'); if($page!=1) print('<a href="sujet.php?id='.$_GET['id'].'&page=1" title="Premi&egrave;re page" style="font-size:14px;">&laquo;</a><a href="sujet.php?id='.$_GET['id'].'&page='.($page-1).'" title="Page précédente" style="position:relative;bottom:1px;font-size:11px;"><</a>');
							else print('<span class="couleur1" style="font-size:13px;">&laquo;<span style="position:relative;bottom:1px;font-size:11px;"><</span></span>');
							print('
							<select onChange="MM_jumpMenu(\'parent\',this,0)" name="recherche">');
								
							for($i=0 ; $i<$pagemax ; $i++)
								{
								print('
								<option value="sujet.php?id='.$_GET['id'].'&page='.($i+1).'"'); if(($i+1) == $page)print(' selected="selected"'); print('>'.($i+1).'</option>');
								}
								
							print('
							</select>
							'); if($page!=$pagemax) print('<a href="sujet.php?id='.$_GET['id'].'&page='.($page+1).'" title="Page suivante" style="position:relative;bottom:1px;font-size:11px;">></a><a href="sujet.php?id='.$_GET['id'].'&page='.$pagemax.'" title="Derni&egrave;re page" style="font-size:14px;">&raquo;</a>');
							else print('<span class="couleur1" style="font-size:13px;"><span style="position:relative;bottom:1px;font-size:11px;">></span>&raquo;</span>');
							print('
							</div>
						</form>');
						}
					else
						{
						if($_SESSION['statut']=="Administrateur" OR $_SESSION['pseudo']==$modoGlob OR $_SESSION['pseudo']==$modoPart)  print('<div class="validsupprmass2"><input type="submit" value="Supprimer" name="submit" /></div>
						</form>');
						}
					
					if($monstatut == "" AND ( $_SESSION['statut']=="Administrateur" OR $_SESSION['pseudo']==$modoGlob OR $_SESSION['pseudo']==$modoPart) )
						{
						print('
						<div class="deplacement">
							<form method="post" action="sujet.php?id='.$_GET['id'].'">
								<select onChange="submit();" name="deplacement">
									<option style="display:none;" selected="selected">D&eacute;placer le sujet</option>
									<optgroup label="&nbsp;&nbsp;&nbsp;&nbsp;Forum G&eacute;n&eacute;ral&nbsp;">
										');
									
						$sql = 'SELECT id,nom FROM wikast_forum_structure_tbl WHERE type="1" ORDER BY id ASC' ;
						$req = mysql_query($sql);
						$res = mysql_num_rows($req);
						
						for($i=0;$i<$res;$i++) print('<option value="'.mysql_result($req,$i,id).'">'.mysql_result($req,$i,nom).'</option>
									'	);
									
									print('
									</optgroup>
									<optgroup label="&nbsp;&nbsp;&nbsp;&nbsp;Forum Hors Sujet&nbsp;">
										');
									
						$sql = 'SELECT id,nom FROM wikast_forum_structure_tbl WHERE type="2" ORDER BY id ASC' ;
						$req = mysql_query($sql);
						$res = mysql_num_rows($req);
						
						for($i=0;$i<$res;$i++) print('<option value="'.mysql_result($req,$i,id).'">'.mysql_result($req,$i,nom).'</option>
									'	);
									
									print('
									</optgroup>
									<optgroup label="&nbsp;&nbsp;&nbsp;&nbsp;Forum Role Play&nbsp;">
										');
									
						$sql = 'SELECT id,nom FROM wikast_forum_structure_tbl WHERE type="3" ORDER BY id ASC' ;
						$req = mysql_query($sql);
						$res = mysql_num_rows($req);
						
						for($i=0;$i<$res;$i++) print('<option value="'.mysql_result($req,$i,id).'">'.mysql_result($req,$i,nom).'</option>
									'	);
									
									print('
									</optgroup>
									<optgroup label="&nbsp;&nbsp;&nbsp;&nbsp;Forum Politique&nbsp;">
										');
									
						$sql = 'SELECT id,nom FROM wikast_forum_structure_tbl WHERE type="4" ORDER BY id ASC' ;
						$req = mysql_query($sql);
						$res = mysql_num_rows($req);
						
						for($i=0;$i<$res;$i++) print('<option value="'.mysql_result($req,$i,id).'">'.mysql_result($req,$i,nom).'</option>
									'	);
									
									print('
									</optgroup>
									<optgroup label="&nbsp;&nbsp;&nbsp;&nbsp;Divers&nbsp;">
										');
									
						$sql = 'SELECT id,nom FROM wikast_forum_structure_tbl WHERE type="64" ORDER BY id ASC' ;
						$req = mysql_query($sql);
						$res = mysql_num_rows($req);
						
						for($i=0;$i<$res;$i++) print('<option value="'.mysql_result($req,$i,id).'">'.mysql_result($req,$i,nom).'</option>
									'	);
									
									print('
									</optgroup>
								</select>
							</form>
						</div>');
						if(!ereg("\[Verrouill&eacute;\]",$titresujet))
							print('
					<div class="verrou"><a href="sujet.php?id='.$_GET['id'].'&verrou=oui" title="Verrouiller le sujet"></a></div>');
						else
							print('
					<div class="verrou2"><a href="sujet.php?id='.$_GET['id'].'&verrou=non" title="Dev&eacute;rrouiller le sujet"></a></div>');
						}
						
					if($_SESSION['statut']!="visiteur" AND (htmlentities($ssforum) != "Annonces officielles" OR (htmlentities($ssforum) == "Annonces officielles" AND $_SESSION['statut']=="Administrateur")) AND (!ereg("\[Verrouill&eacute;\]",$titresujet) OR (ereg("\[Verrouill&eacute;\]",$titresujet) AND $_SESSION['statut']=="Administrateur")))
					{
					if($monstatut == "") print('<div class="nouveausujet"><a href="sujet=nouveau.php?ssfid='.$ssforumid.'" title="Nouveau sujet">Nouveau sujet</a></div>');
					else print('<div class="nouveausujet"><a href="sujet=nouveau.php?prive=ok" title="Nouveau sujet">Nouveau sujet</a></div>');
					if($monstatut == "" OR ($monstatut == "Participant" OR $monstatut == "Mod&eacute;rateur")) print('<div class="repondre"><a href="sujet=repondre.php?id='.$_GET['id'].'#bas" title="R&eacute;pondre">R&eacute;pondre</a></div>');
					}
					print('
				</div>
			</div>
			');
				
			mysql_close($db);
			?>
			
		</div>
	
	</body>
	
</html>
