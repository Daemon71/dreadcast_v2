<?php 
session_start();

$page = ($_GET['page']=="")?1:$_GET['page'];

if(empty($_SESSION['id'])) $_SESSION['statut'] = "visiteur";
else
	{
	$db = mysql_connect('localhost', 'CENSURE', 'CENSURE') or die("Erreur de connexion au serveur des messages.");
	mysql_select_db('CENSURE',$db) or die("Erreur de connexion a la base de donnees des messages.");
	
	$sql = 'SELECT sujets_vu FROM wikast_joueurs_tbl WHERE pseudo="'.$_SESSION['pseudo'].'"' ;
	$req = mysql_query($sql);
	
	if(!ereg('-'.$_GET['id'].'-',mysql_result($req,0,sujets_vu)))
		{
		$sql = 'UPDATE wikast_joueurs_tbl SET sujets_vu="'.mysql_result($req,0,sujets_vu).$_GET['id'].'-" WHERE pseudo="'.$_SESSION['pseudo'].'"' ;
		mysql_query($sql);
		}
	
	mysql_close($db);
	}

if(isset($_GET['verrou']))
	{
	
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
	
	if($_SESSION['statut']!="Administrateur" OR $_SESSION['pseudo']!=$modoGlob OR $_SESSION['pseudo']!=$modoPart)
		{
		print('<meta http-equiv="refresh" content="0 ; url=forum=sujet.php?id='.$_GET['id'].'"> ');
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
		$sql = 'UPDATE wikast_forum_sujets_tbl SET date="'.time().'" WHERE id="'.$_GET['id'].'"' ;
		mysql_query($sql);
		
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
		
		$sql = 'INSERT INTO wikast_forum_posts_tbl(id,sujet,auteur,date,contenu,numero) VALUES("","'.$_GET['id'].'","'.$_SESSION['pseudo'].'","'.time().'","'.$message.'","'.$lenum.'")' ;
		}
	elseif($_POST['type']=="edit" && $_POST['idmess']!=-1) $sql = 'UPDATE wikast_forum_posts_tbl SET contenu="'.$message.'" WHERE id="'.$_POST['idmess'].'"' ;
	elseif($_POST['type']=="edit" && $_POST['idmess']==-1) $sql = 'UPDATE wikast_forum_sujets_tbl SET contenu="'.$message.'" WHERE id="'.$_GET['id'].'"' ;
	$req = mysql_query($sql);
	
	mysql_close($db);
	}
	
if(isset($_POST['deplacement']) && isset($_GET['id']))
	{
	if($_SESSION['statut']=="Administrateur")
		{
		$db = mysql_connect('localhost', 'CENSURE', 'CENSURE') or die("Erreur de connexion au serveur des messages.");
		mysql_select_db('CENSURE',$db) or die("Erreur de connexion a la base de donnees des messages.");
	
		$sql = 'UPDATE wikast_forum_sujets_tbl SET categorie="'.$_POST['deplacement'].'" WHERE id="'.$_GET['id'].'"';
		mysql_query($sql);
	
		mysql_close($db);
		}
	else
		{
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
				if($res!=0) $modoGlob = mysql_result($req,0,nom);
			
				if($_SESSION['pseudo']==$modoGlob OR $_SESSION['pseudo']==$modoPart)
					{
					$db = mysql_connect('localhost', 'CENSURE', 'CENSURE') or die("Erreur de connexion au serveur des messages.");
					mysql_select_db('CENSURE',$db) or die("Erreur de connexion a la base de donnees des messages.");
		
					$sql = 'UPDATE wikast_forum_sujets_tbl SET categorie="'.$_POST['deplacement'].'" WHERE id="'.$_GET['id'].'"';
					mysql_query($sql);
	
					mysql_close($db);
					}
				}
			}
		}
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
				
				if(ereg("\[quote\]",$textesujet) && ereg("\[/quote\]",$textesujet))
					{
					$textesujet = str_replace("[quote]", "<div class=\"text-quote\">",$textesujet);
					$textesujet = str_replace("[/quote]", "<!--quote--></div>",$textesujet);
					}
				if(ereg("\[couleur type=&lt;",$textesujet) && ereg("&gt;\]",$textesujet) && ereg("\[/couleur\]",$textesujet))
					{
					$textesujet = str_replace("[couleur type=&lt;", "<span class=\"",$textesujet);
					$textesujet = str_replace("&gt;]", "\">",$textesujet);
					$textesujet = str_replace("[/couleur]", "<!--couleur--></span>",$textesujet);
					}
				if(ereg("\[g\]",$textesujet) && ereg("\[/g\]",$textesujet))
					{
					$textesujet = str_replace("[g]", "<strong>",$textesujet);
					$textesujet = str_replace("[/g]", "</strong>",$textesujet);
					}
				if(ereg("\[i\]",$textesujet) && ereg("\[/i\]",$textesujet))
					{
					$textesujet = str_replace("[i]", "<em>",$textesujet);
					$textesujet = str_replace("[/i]", "</em>",$textesujet);
					}
				if(ereg("\[img url=&lt;",$textesujet) && ereg("&gt; /\]",$textesujet))
					{
					$textesujet = str_replace("[img url=&lt;", "<img src=\"",$textesujet);
					$textesujet = str_replace("&gt; /]", "\" />",$textesujet);
					}
				if(ereg("\[lien url=&lt;",$textesujet) && ereg("&gt;\]",$textesujet) && ereg("\[/lien\]",$textesujet))
					{
					$textesujet = str_replace("[lien url=&lt;", "<a href=\"",$textesujet);
					$textesujet = str_replace("&gt;]", "\">",$textesujet);
					$textesujet = str_replace("[/lien]", "</a>",$textesujet);
					}
				if(ereg("\[centrer\]",$textesujet) && ereg("\[/centrer\]",$textesujet))
					{
					$textesujet = str_replace("[centrer]", "<center>",$textesujet);
					$textesujet = str_replace("[/centrer]", "</center>",$textesujet);
					}
				$textesujet = str_replace("&lt;br /&gt;", "",$textesujet);
				$textesujet = str_replace(":)", "<img src=\"smileys/smiley1.gif\" alt=\"smiley\" class=\"smiley\">",$textesujet);
				$textesujet = str_replace(":-)", "<img src=\"smileys/smiley1.gif\" alt=\"smiley\" class=\"smiley\">",$textesujet);
				$textesujet = str_replace(":D", "<img src=\"smileys/smiley2.gif\" alt=\"smiley\" class=\"smiley\">",$textesujet);
				$textesujet = str_replace(":-D", "<img src=\"smileys/smiley2.gif\" alt=\"smiley\" class=\"smiley\">",$textesujet);
				$textesujet = str_replace(":p", "<img src=\"smileys/smiley3.gif\" alt=\"smiley\" class=\"smiley\">",$textesujet);
				$textesujet = str_replace(":-p", "<img src=\"smileys/smiley3.gif\" alt=\"smiley\" class=\"smiley\">",$textesujet);
				$textesujet = str_replace(":P", "<img src=\"smileys/smiley3.gif\" alt=\"smiley\" class=\"smiley\">",$textesujet);
				$textesujet = str_replace(":-P", "<img src=\"smileys/smiley3.gif\" alt=\"smiley\" class=\"smiley\">",$textesujet);
				$textesujet = str_replace("XD", "<img src=\"smileys/smiley4.gif\" alt=\"smiley\" class=\"smiley\">",$textesujet);
				
///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
					if(${$auteursujet}=="")
						{
						$sqla = 'SELECT id,statut,avatar,race,sexe,connec FROM principal_tbl WHERE pseudo= "'.$auteursujet.'"' ;
						$reqa = mysql_query($sqla);
							
						$idauteur = mysql_result($reqa,0,"id");
						$statutauteur = mysql_result($reqa,0,"statut");
						if($statutauteur == "Compte VIP") $statutauteur = "VIP";
						$avatarauteur = mysql_result($reqa,0,"avatar");
						$raceauteur = mysql_result($reqa,0,"race");
						$sexeauteur = mysql_result($reqa,0,"sexe");
						$connecauteur = mysql_result($reqa,0,"connec");
							
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
									
				$max = 20*$page-1;
				$min = 20*$page-20;
				if($min==0) $min = 1;
				
				$sql2 = 'SELECT * FROM wikast_forum_posts_tbl WHERE sujet="'.$_GET['id'].'" AND numero < '.$max.' AND numero > '.$min.' ORDER BY date ASC' ;
				$req2 = mysql_query($sql2);
				$res2 = mysql_num_rows($req2);
				
				}
			else $res2 = 0;
			
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
			
			print('<div id="forum-entete">');
			
			include('include/inc_barreliens1.php');	
				
			print('
					
				<div id="forum-info2">
					<p class="gauche">');
					if($_SESSION['id']!="") print('<a href="../ingame/engine=contacter.php?cible='.$modoPart.'&forum='.$ssforumid.'" onclick="window.open(this.href); return false;">Contacter<br />un mod&eacute;rateur</a>');
					print('</p>
					
					<p class="droite">');
						if($_SESSION['statut']!="visiteur" AND (!ereg("\[Verrouill&eacute;\]",$titresujet) OR (ereg("\[Verrouill&eacute;\]",$titresujet) AND $_SESSION['statut']=="Administrateur")) AND (htmlentities($ssforum) != "Annonces officielles" OR (htmlentities($ssforum) == "Annonces officielles" AND $_SESSION['statut']=="Administrateur")))
						print('<a href="sujet=repondre.php?id='.$_GET['id'].'#bas" title="R&eacute;pondre">R&eacute;pondre</a><br />
						<a href="sujet=nouveau.php?ssfid='.mysql_result($req,0,categorie).'" title="Nouveau sujet">Nouveau sujet</a><br />');
						print('<a href="#bas" title="Descendre en bas de page">Bas de page</a><br />
						<span class="pages">');
						if($page!=1) print('<a href="sujet.php?id='.$_GET['id'].'&page='.($page-1).'"><</a>'); else print('<span class="couleur1"><</span>'); print(' <a href="#bas">Page '.$page.' de '.$pagemax.'</a> '); if($page!=$pagemax) print('<a href="sujet.php?id='.$_GET['id'].'&page='.($page+1).'">></a>'); else print('<span class="couleur1">></span>');
				print('</span>
				</div>
			</div>');
			
			if(statut($_SESSION['statut'])<2) include('include/inc_pub.php');	
			
			print('<div id="mainpage-forum">
				<div id="haut">
					<a href="forum.php?partie='.$forum.'&sous-partie='.$ssforumid.'" title="Retour" id="btn_retour"></a>
					<p><a href="forum.php?partie='.$forum.'" title="Vers le forum '.$forum.'">Forum '.$forum.'</a> > <a href="forum.php?partie='.$forum.'&sous-partie='.$ssforumid.'" title="Vers le sous-forum '.$ssforum.'">'.$ssforum.'</a></p>
				</div>
					
				<div id="contenu">
					
					<div class="titre-sujet">
						<p class="titre">'.$titresujet.'</p>
						<p class="infos">Post&eacute; par <span class="style1">'.$auteursujet.'</span> le '.date('d/m/Y',$datesujet).' &agrave '.date('H:i',$datesujet).'</p>
					</div>
					');
			
					if($page==1)
						{
					
					print('
					<div class="premierpost" id="-1">
						<div class="boutons">');
							if($_SESSION['statut']!="visiteur" AND (htmlentities($ssforum) != "Annonces officielles" OR (htmlentities($ssforum) == "Annonces officielles" AND $_SESSION['statut']=="Administrateur")) AND (!ereg("\[Verrouill&eacute;\]",$titresujet) OR (ereg("\[Verrouill&eacute;\]",$titresujet) AND $_SESSION['statut']=="Administrateur")))
								{
								print('<a href="sujet=repondre.php?id='.$_GET['id'].'&mess=-1#bas" class="quote" title="Citer ce message"></a>');
								if($_SESSION['id']==$idauteur OR $_SESSION['statut']=="Administrateur" OR $_SESSION['pseudo']==$modoGlob OR $_SESSION['pseudo']==$modoPart)
								print('
								<a href="javascript:ConfirmSuppr(\''.$_GET['id'].'\',\'-1\');" class="suppr" title="Supprimer ce message"></a>
								<a href="sujet=editer.php?id='.$_GET['id'].'&mess=-1" class="edit" title="Editer ce message"></a>');
								}
						print('
						</div>
						<div class="info-perso">
							<a href="../ingame/engine=contacter.php?cible='.$auteursujet.'" class="titre">'.$auteursujet.'</a>
							<div class="avatar"><img src="');
							
							if((ereg("http",$avatarauteur)) OR (ereg("ftp",$avatarauteur))) print($avatarauteur);
							else print('../ingame/avatars/'.$avatarauteur);
							
							print('" alt="Avatar" width="68" height="68" /></div>
							<div class="indicateurs">
								<img src="design/avatar_'.$raceauteur.'.gif" alt="'.$raceauteur.'" title="'.$raceauteur.'" /><br />
								<img src="design/icon_'.$sexeauteur.'.gif" alt="'.$sexeauteur.'" title="'.$sexeauteur.'" /><br />
								<img src="design/icon_connec-'.$connecauteur.'.gif" alt="');if($connecauteur=="oui")print('En ligne');else print('Absent');print('" title="');if($connecauteur=="oui")print('En ligne');else print('Absent');print('" /><br />
							</div>
							<p>
								'.$statutauteur.'<br />
								Classement : '.$classementauteur.'<br />
								<a href="edc=visio.php?auteur='.$auteursujet.'" title="Voir son Espace DreadCast">Voir son EDC</a>
							</p>
						</div>
						<div class="texte">'.$textesujet.'</div>
					</div>');
						}
					
					for($i=0 ; $i<$res2 ; $i++)
						{
						
						$idmess = mysql_result($req2,$i,"id");
						$textemess = mysql_result($req2,$i,"contenu");
						$auteurmess = mysql_result($req2,$i,"auteur");
						$datemess = mysql_result($req2,$i,"date");
						
						$sqlhop = 'SELECT commentaire FROM wikast_joueurs_tbl WHERE pseudo= "'.$auteurmess.'" AND infoperso LIKE "%-signature-%"' ;
						$reqhop = mysql_query($sqlhop);
						$reshop = mysql_num_rows($reqhop);
					
						if($reshop!=0) $textemess .= '<br /><br /><span class="couleur1">________________________________________________________________<br />'.mysql_result($reqhop,0,"commentaire").'</span>';
						
						if(ereg("\[quote\]",$textemess) && ereg("\[/quote\]",$textemess))
							{
							$textemess = str_replace("[quote]", "<div class=\"text-quote\">",$textemess);
							$textemess = str_replace("[/quote]", "<!--quote--></div>",$textemess);
							}
						if(ereg("\[couleur type=&lt;",$textemess) && ereg("&gt;\]",$textemess) && ereg("\[/couleur\]",$textemess))
							{
							$textemess = str_replace("[couleur type=&lt;", "<span class=\"",$textemess);
							$textemess = str_replace("&gt;]", "\">",$textemess);
							$textemess = str_replace("[/couleur]", "<!--couleur--></span>",$textemess);
							}
						if(ereg("\[g\]",$textemess) && ereg("\[/g\]",$textemess))
							{
							$textemess = str_replace("[g]", "<strong>",$textemess);
							$textemess = str_replace("[/g]", "</strong>",$textemess);
							}
						if(ereg("\[i\]",$textemess) && ereg("\[/i\]",$textemess))
							{
							$textemess = str_replace("[i]", "<em>",$textemess);
							$textemess = str_replace("[/i]", "</em>",$textemess);
							}
						if(ereg("\[img url=&lt;",$textemess) && ereg("&gt; /\]",$textemess))
							{
							$textemess = str_replace("[img url=&lt;", "<img src=\"",$textemess);
							$textemess = str_replace("&gt; /]", "\" />",$textemess);
							}
						if(ereg("\[lien url=&lt;",$textemess) && ereg("&gt;\]",$textemess) && ereg("\[/lien\]",$textemess))
							{
							$textemess = str_replace("[lien url=&lt;", "<a href=\"",$textemess);
							$textemess = str_replace("&gt;]", "\">",$textemess);
							$textemess = str_replace("[/lien]", "</a>",$textemess);
							}
						if(ereg("\[centrer\]",$textemess) && ereg("\[/centrer\]",$textemess))
							{
							$textemess = str_replace("[centrer]", "<center>",$textemess);
							$textemess = str_replace("[/centrer]", "</center>",$textemess);
							}
						$textemess = str_replace("&lt;br /&gt;", "",$textemess);
						$textemess = str_replace(":)", "<img src=\"smileys/smiley1.gif\" alt=\"smiley\" class=\"smiley\">",$textemess);
						$textemess = str_replace(":-)", "<img src=\"smileys/smiley1.gif\" alt=\"smiley\" class=\"smiley\">",$textemess);
						$textemess = str_replace(":D", "<img src=\"smileys/smiley2.gif\" alt=\"smiley\" class=\"smiley\">",$textemess);
						$textemess = str_replace(":-D", "<img src=\"smileys/smiley2.gif\" alt=\"smiley\" class=\"smiley\">",$textemess);
						$textemess = str_replace(":p", "<img src=\"smileys/smiley3.gif\" alt=\"smiley\" class=\"smiley\">",$textemess);
						$textemess = str_replace(":-p", "<img src=\"smileys/smiley3.gif\" alt=\"smiley\" class=\"smiley\">",$textemess);
						$textemess = str_replace(":P", "<img src=\"smileys/smiley3.gif\" alt=\"smiley\" class=\"smiley\">",$textemess);
						$textemess = str_replace(":-P", "<img src=\"smileys/smiley3.gif\" alt=\"smiley\" class=\"smiley\">",$textemess);
						$textemess = str_replace("XD", "<img src=\"smileys/smiley4.gif\" alt=\"smiley\" class=\"smiley\">",$textemess);
///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
						if(${$auteurmess}=="")
							{
							$sqla = 'SELECT id,statut,avatar,race,sexe,connec FROM principal_tbl WHERE pseudo= "'.$auteurmess.'"' ;
							$reqa = mysql_query($sqla);
							
							$idauteur = mysql_result($reqa,0,"id");
							$statutauteur = mysql_result($reqa,0,"statut");
							if($statutauteur == "Compte VIP") $statutauteur = "VIP";
							$avatarauteur = mysql_result($reqa,0,"avatar");
							$raceauteur = mysql_result($reqa,0,"race");
							$sexeauteur = mysql_result($reqa,0,"sexe");
							$connecauteur = mysql_result($reqa,0,"connec");
							
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
							
						
						
					print('<div class="post"  id="'.$idmess.'">
						<div class="barre-infos">
							<p class="infos">Post&eacute; par <span class="style1">'.$auteurmess.'</span> le '.date('d/m/Y',$datemess).' &agrave '.date('H:i',$datemess).'</p>
						</div>
						<div class="boutons">');
						if($_SESSION['statut']!="visiteur" AND (htmlentities($ssforum) != "Annonces officielles" OR (htmlentities($ssforum) == "Annonces officielles" AND $_SESSION['statut']=="Administrateur")) AND (!ereg("\[Verrouill&eacute;\]",$titresujet) OR (ereg("\[Verrouill&eacute;\]",$titresujet) AND $_SESSION['statut']=="Administrateur")))
								{
								print('<a href="sujet=repondre.php?id='.$_GET['id'].'&mess='.$idmess.'#bas" class="quote" title="Citer ce message"></a>');
								if($_SESSION['id']==$idauteur OR $_SESSION['statut']=="Administrateur" OR $_SESSION['pseudo']==$modoGlob OR $_SESSION['pseudo']==$modoPart)
								print('
								<a href="javascript:ConfirmSuppr(\''.$_GET['id'].'\',\''.$idmess.'\');" class="suppr" title="Supprimer ce message"></a>
								<a href="sujet=editer.php?id='.$_GET['id'].'&mess='.$idmess.'#'.$idmess.'" class="edit" title="Editer ce message"></a>');
								}
						print('</div>
						<div class="info-perso">
							<a href="../ingame/engine=contacter.php?cible='.$auteurmess.'" class="titre">'.$auteurmess.'</a>
							<div class="avatar"><img src="');
							
							if((ereg("http",$avatarauteur)) OR (ereg("ftp",$avatarauteur))) print($avatarauteur);
							else print('../ingame/avatars/'.$avatarauteur);
							
							print('" alt="Avatar" width="68" height="68" /></div>
							<div class="indicateurs">
								<img src="design/avatar_'.$raceauteur.'.gif" alt="'.$raceauteur.'" title="'.$raceauteur.'" /><br />
								<img src="design/icon_'.$sexeauteur.'.gif" alt="'.$sexeauteur.'" title="'.$sexeauteur.'" /><br />
								<img src="design/icon_connec-'.$connecauteur.'.gif" alt="');if($connecauteur=="oui")print('En ligne');else print('Absent');print('" title="');if($connecauteur=="oui")print('En ligne');else print('Absent');print('" /><br />
							</div>
							<p>
								'.$statutauteur.'<br />
								Classement : '.$classementauteur.'<br />
								<a href="edc=visio.php?auteur='.$auteurmess.'" title="Voir son Espace DreadCast">Voir son EDC</a>
							</p>
						</div>
						<div class="texte">'.$textemess.'</div>
					</div>');
						}
					
					print('	
					
				</div>
					
				<div id="bas">
					
					<div class="remonter"><a href="#forum" title="Remonter en haut de la page">Remonter</a></div>');
					
					if($pagemax!=1)
					{
					print('
					<form class="pages" method="post" action="">
						Pages :<br />
						<div class="style1">
						'); if($page!=1) print('<a href="sujet.php?id='.$_GET['id'].'&page=1" title="Premi&egrave;re page"><<</a>');
						else print('<span class="couleur1"><<</span>');
						print('
						<select onChange="MM_jumpMenu(\'parent\',this,0)" name="recherche">');
							
						for($i=0 ; $i<$pagemax ; $i++)
							print('
							<option value="sujet.php?id='.$_GET['id'].'&page='.($i+1).'">'.($i+1).'</option>');
							
						print('
						</select>
						'); if($page!=$pagemax) print('<a href="sujet.php?id='.$_GET['id'].'&page='.$pagemax.'" title="Derni&egrave;re page">>></a>');
						else print('<span class="couleur1">>></span>');
						print('
						</div>
					</form>');
					}
					
					if($_SESSION['statut']=="Administrateur")
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
					elseif($_SESSION['pseudo']==$modoGlob OR $_SESSION['pseudo']==$modoPart)
						{
						print('
						<div class="deplacement">
							<form method="post" action="sujet.php?id='.$_GET['id'].'">
								<select onChange="submit();" name="deplacement">
									<option style="display:none;" selected="selected">D&eacute;placer le sujet</option>
									<optgroup label="&nbsp;&nbsp;&nbsp;&nbsp;Forum '.$forum.'&nbsp;">
										');
									
						$sql = 'SELECT id,nom FROM wikast_forum_structure_tbl WHERE type="'.$forumid.'" ORDER BY id ASC' ;
						$req = mysql_query($sql);
						$res = mysql_num_rows($req);

						for($i=0;$i<$res;$i++) print('<option value="'.mysql_result($req,$i,id).'">'.mysql_result($req,$i,nom).'</option>
										');
									
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
					print('
					<div class="nouveausujet"><a href="sujet=nouveau.php?ssfid='.$ssforumid.'" title="Nouveau sujet">Nouveau sujet</a></div>
					<div class="repondre"><a href="sujet=repondre.php?id='.$_GET['id'].'#bas" title="R&eacute;pondre">R&eacute;pondre</a></div>');
					print('
				</div>
			</div>
			');
				
			mysql_close($db);
			?>
			
		</div>
	
	</body>
	
</html>
