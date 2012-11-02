<?php 
session_start();

if(empty($_SESSION['id']))
	{
	print('<meta http-equiv="refresh" content="0 ; url=sujet.php?id='.$idsujet.'&page=max"> ');
	exit();
	}

$db = mysql_connect('localhost', 'CENSURE', 'CENSURE') or die("Erreur de connexion au serveur des messages.");
mysql_select_db('CENSURE',$db) or die("Erreur de connexion a la base de donnees des messages.");

$sql = 'SELECT categorie FROM wikast_forum_sujets_tbl WHERE id= "'.$idsujet.'"' ;
$req = mysql_query($sql);
$res = mysql_num_rows($req);
if($res != 0 AND mysql_result($req,0,categorie) == -1) $perso = true;
else $perso = false;

$idsujet = $_GET['id'];
$idmess = $_GET['mess'];

if($_POST['supprmass'] == "ok")
	{
	$idmess = 1;
	$idsujet = $_POST['sujet'];
	$debut = $_POST['debut'];
	$fin = $_POST['fin'];
	}

if($idmess==-1)
	{
	
	$sql = 'SELECT categorie,auteur FROM wikast_forum_sujets_tbl WHERE id="'.$idsujet.'"' ;
	$req = mysql_query($sql);
	$res = mysql_num_rows($req);
	
	if($res==0)
		{
		print('<meta http-equiv="refresh" content="0 ; url=sujet.php?id='.$idsujet.'&page=max"> ');
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
		
		if(!(mysql_result($req,0,auteur)==$_SESSION['pseudo'] OR $_SESSION['statut']=="Administrateur" OR $_SESSION['pseudo']==$modoGlob OR $_SESSION['pseudo']==$modoPart))
			{
			print('<meta http-equiv="refresh" content="0 ; url=sujet.php?id='.$idsujet.'&page=max"> ');
			exit();
			}
		else
			{
			
			// MISE A ZERO DES COMPTEURS DE VISITE DU SUJET
			
			$sql = 'SELECT id,sujets_vu FROM wikast_joueurs_tbl WHERE sujets_vu LIKE "%-'.$idsujet.'-%"' ;
			$req = mysql_query($sql);
		
			while($result = mysql_fetch_array($req))
				{
				$hop = explode($idsujet.'-',$result['sujets_vu'],2);
	
				$sql = 'UPDATE wikast_joueurs_tbl SET sujets_vu="'.$hop[0].$hop[1].'" WHERE id="'.$result['id'].'"' ;
				mysql_query($sql);
				}
			
			$sql = 'UPDATE wikast_forum_sujets_tbl SET categorie="65" WHERE id="'.$idsujet.'"'; //DEPLACEMENT DANS LA POUBELLE
			mysql_query($sql);
			
			$sql = 'DELETE FROM wikast_forum_permissions_tbl WHERE sujet="'.$idsujet.'"'; //SUPRESSION DES PERMISSIONS
			mysql_query($sql);
			
			if($perso)
				{
				print('<meta http-equiv="refresh" content="0 ; url=forum=perso.php"> ');
				exit();
				}
			else
				{
				print('<meta http-equiv="refresh" content="0 ; url=forum=accueil.php"> ');
				exit();
				}
			}
		}
	}
else
	{
	
	$sql = 'SELECT categorie FROM wikast_forum_sujets_tbl WHERE id="'.$idsujet.'"' ;
	$req = mysql_query($sql);
	$res = mysql_num_rows($req);
	
	$sql2 = 'SELECT auteur FROM wikast_forum_posts_tbl WHERE id="'.$idmess.'"' ;
	$req2 = mysql_query($sql2);
	$res2 = mysql_num_rows($req2);
	
	if($res2 != 0) $auteurdumess = mysql_result($req2,0,auteur);
	
	if($res==0 OR ($res2==0 AND $_POST['supprmass'] != "ok"))
		{
		print('<meta http-equiv="refresh" content="0 ; url=sujet.php?id='.$idsujet.'&page=max"> ');
		exit();
		}
	else
		{
		if(mysql_result($req,0,categorie) == -1)
			{
			$ssforumid = -1;
			
			$sqltmp3 = 'SELECT statut FROM wikast_forum_permissions_tbl WHERE pseudo="'.$_SESSION['pseudo'].'" AND sujet="'.$idsujet.'"' ;
			$reqtmp3 = mysql_query($sqltmp3);
			$restmp3 = mysql_num_rows($reqtmp3);
			
			if($restmp3 == 0)
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
		else
			{
			$sql3 = 'SELECT type,admin FROM wikast_forum_structure_tbl WHERE id="'.mysql_result($req,0,categorie).'"' ;
			$req3 = mysql_query($sql3);
			$modoPart = mysql_result($req3,0,admin);
			$sql3 = 'SELECT admin FROM wikast_forum_structure_tbl WHERE id="'.mysql_result($req3,0,type).'"' ;
			$req3 = mysql_query($sql3);
			$modoGlob = mysql_result($req3,0,admin);
			}
		
		if(!($monstatut == "Mod&eacute;rateur" OR $auteurdumess == $_SESSION['pseudo'] OR $_SESSION['statut']=="Administrateur" OR $_SESSION['pseudo']==$modoGlob OR $_SESSION['pseudo']==$modoPart))
			{
			print('<meta http-equiv="refresh" content="0 ; url=sujet.php?id='.$idsujet.'&page=max"> ');
			exit();
			}
		else
			{
			if($_POST['supprmass'] != "ok")
				{
				$sql = 'DELETE FROM wikast_forum_posts_tbl WHERE sujet="'.$idsujet.'" AND id="'.$idmess.'"' ;
				mysql_query($sql);
				}
			else
				{
				$temp = '';
				
				for($k=$debut;$k<=$fin;$k++)
					{
					if(isset($_POST['supprimer_'.$k])) $temp .= 'OR numero="'.$k.'" ';
					}
				if($temp != '') $temp = substr($temp,3);
				else
					{
					print('<meta http-equiv="refresh" content="0 ; url=sujet.php?id='.$idsujet.'&page=max"> ');
					exit();
					}

				$sql = 'DELETE FROM wikast_forum_posts_tbl WHERE sujet="'.$idsujet.'" AND ('.$temp.')';
				mysql_query($sql);
				}
			
			$sql = 'SELECT id FROM wikast_forum_posts_tbl WHERE sujet="'.$idsujet.'" ORDER BY numero ASC' ;
			$req = mysql_query($sql);
			$res = mysql_num_rows($req);
			
			for($k=0;$k<$res;$k++)
				{
				$sql2 = 'UPDATE wikast_forum_posts_tbl SET numero = "'.($k+1).'" WHERE id="'.mysql_result($req,$k,id).'"' ;
				mysql_query($sql2);
				}
			
			print('<meta http-equiv="refresh" content="0 ; url=sujet.php?id='.$idsujet.'&page=max"> ');
			}
		}
	}

mysql_close($db);

include('include/inc_head.php'); ?>

		<div id="page">
			<a href="forum=accueil.php" id="lien-forum"></a>
			
			<!-- -------------------------------------------------------------------------- MISE EN PAGE GENERALE -------------------------------------------------------------------------- -->
			<div id="forum">
				
				<?php include('include/inc_barre1.php'); ?>
				
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

			$sql = 'SELECT categorie,nom,contenu,auteur,date FROM wikast_forum_sujets_tbl WHERE id= "'.$idsujet.'"' ;
			$req = mysql_query($sql);
			$res = mysql_num_rows($req);
			
			if($res!=0)
				{
				$titresujet = mysql_result($req,0,"nom");
				$textesujet = mysql_result($req,0,"contenu");
				$auteursujet = mysql_result($req,0,"auteur");
				$datesujet = mysql_result($req,0,"date");
				
				if(ereg("\[quote\]",$textesujet) && ereg("\[/quote\]",$textesujet))
					{
					$textesujet = str_replace("[quote]", "<div class=\"text-quote\">",$textesujet);
					$textesujet = str_replace("[/quote]", "<!--quote--></div>",$textesujet);
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
				
				$sqla = 'SELECT id,statut,avatar,race,sexe,connec,total FROM principal_tbl WHERE pseudo= "'.$auteursujet.'"' ;
				$reqa = mysql_query($sqla);
				
				if(mysql_num_rows($reqa))
					{
				
					$idauteur = mysql_result($reqa,0,"id");
					$statutauteur = mysql_result($reqa,0,"statut");
					if($statutauteur == "Compte VIP") $statutauteur = "VIP";
					$avatarauteur = mysql_result($reqa,0,"avatar");
					$raceauteur = mysql_result($reqa,0,"race");
					$sexeauteur = mysql_result($reqa,0,"sexe");
					$connecauteur = mysql_result($reqa,0,"connec");
					$classementauteur = mysql_result($reqa,0,"total");
					
					$sql2 = 'SELECT * FROM wikast_forum_posts_tbl WHERE sujet= "'.mysql_result($reqa,0,"id").'" ORDER BY date ASC' ;
					$req2 = mysql_query($sql2);
					$res2 = mysql_num_rows($req2);
					} else $res2 = 0;
				}
			else $res2 = 0;
			
			if(mysql_result($req,0,"categorie") != -1)
			{
			$sqltmp = 'SELECT nom,type,admin FROM wikast_forum_structure_tbl WHERE id= "'.mysql_result($req,0,"categorie").'"' ;
			$reqtmp = mysql_query($sqltmp);
			$ssforum = mysql_result($reqtmp,0,"nom");
			$ssforumid = mysql_result($req,0,"categorie");
			$modoPart = mysql_result($reqtmp,0,"admin");
			$sqltmp = 'SELECT nom,admin FROM wikast_forum_structure_tbl WHERE id= "'.mysql_result($reqtmp,0,"type").'"' ;
			$reqtmp = mysql_query($sqltmp);
			$forum = mysql_result($reqtmp,0,"nom");
			$modoGlob = mysql_result($reqtmp,0,"admin");
			}
			
			print('<div id="forum-entete">');
			
			include('include/inc_barreliens1.php');	
				
			print('
					
				<div id="forum-info2">
					<p class="gauche">');
						if($_SESSION['id']!="") print('<a href="../ingame/engine=contacter.php?cible='.$modoGlob.'&forum='.$ssforumid.'" onclick="window.open(this.href); return false;">Contacter<br />un mod&eacute;rateur</a>');
						print('</p>
					
					<p class="droite">');
						if($_SESSION['statut']!="visiteur")
						print('<a href="sujet=repondre.php?id='.$idsujet.'#bas" title="R&eacute;pondre">R&eacute;pondre</a><br />
						<a href="sujet=nouveau.php?ssfid='.mysql_result($req,0,categorie).'" title="Nouveau sujet">Nouveau sujet</a><br />');
						print('<a href="#bas" title="Descendre en bas de page">Bas de page</a>
					</p>
				</div>
			</div>
				
			<div id="mainpage-forum">
				<div id="haut">
					<a href="forum.php?partie='.$forum.'&sous-partie='.$ssforumid.'" title="Retour" id="btn_retour"></a>
					<p><a href="forum.php?partie='.$forum.'" title="Vers le forum '.$forum.'">Forum '.$forum.'</a> > <a href="forum.php?partie='.$forum.'&sous-partie='.$ssforumid.'" title="Vers le sous-forum '.$ssforum.'">'.$ssforum.'</a></p>
				</div>
					
				<div id="contenu">
					
					<div class="titre-sujet">
						<p class="titre">'.$titresujet.'</p>
						<p class="infos">Post&eacute; par <span class="style1">'.$auteursujet.'</span> le '.date('d/m/Y',$datesujet).' &agrave '.date('H:i',$datesujet).'</p>
					</div>
					
					<div class="premierpost" id="-1">
						<div class="boutons">');
							if($_SESSION['statut']!="visiteur")
								{
								print('<a href="sujet=repondre.php?id='.$idsujet.'&mess=-1#bas" class="quote" title="Citer ce message"></a>');
								if($_SESSION['id']==$idauteur OR $_SESSION['statut']=="Administrateur" OR $_SESSION['pseudo']==$modoGlob OR $_SESSION['pseudo']==$modoPart)
								print('
								<a href="javascript:ConfirmSuppr(\''.$idsujet.'\',\'-1\');" class="suppr" title="Supprimer ce message"></a>
								<a href="sujet=editer.php?id='.$idsujet.'&mess=-1" class="edit" title="Editer ce message"></a>');
								}
						print('
						</div>
						<div class="info-perso">
							<div class="titre">'.$auteursujet.'</div>
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
								<!--Classement : '.$classementauteur.'<br />-->
								<a href="edc=visio.php?auteur='.$auteursujet.'" title="Voir son Espace DreadCast">Voir son EDC</a>
							</p>
						</div>
						<div class="texte">'.$textesujet.'</div>
					</div>');
					
					for($i=0 ; $i<$res2 ; $i++)
						{
						
						$idmess = mysql_result($req2,$i,"id");
						$textemess = mysql_result($req2,$i,"contenu");
						$auteurmess = mysql_result($req2,$i,"auteur");
						$datemess = mysql_result($req2,$i,"date");
						
						if(ereg("\[quote\]",$textemess) && ereg("\[/quote\]",$textemess))
							{
							$textemess = str_replace("[quote]", "<div class=\"text-quote\">",$textemess);
							$textemess = str_replace("[/quote]", "<!--quote--></div>",$textemess);
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
						
						$sqla = 'SELECT id,statut,avatar,race,sexe,connec,total FROM principal_tbl WHERE pseudo= "'.$auteurmess.'"' ;
						$reqa = mysql_query($sqla);
				
						$idauteur = mysql_result($reqa,0,"id");
						$statutauteur = mysql_result($reqa,0,"statut");
						if($statutauteur == "Compte VIP") $statutauteur = "VIP";
						$avatarauteur = mysql_result($reqa,0,"avatar");
						$raceauteur = mysql_result($reqa,0,"race");
						$sexeauteur = mysql_result($reqa,0,"sexe");
						$connecauteur = mysql_result($reqa,0,"connec");
						$classementauteur = mysql_result($reqa,0,"total");
						
					print('<div class="post"  id="'.$idmess.'">
						<div class="barre-infos">
							<p class="infos">Post&eacute; par <span class="style1">'.$auteurmess.'</span> le '.date('d/m/Y',$datemess).' &agrave '.date('H:i',$datemess).'</p>
						</div>
						<div class="boutons">');
						if($_SESSION['statut']!="visiteur")
								{
								print('<a href="sujet=repondre.php?id='.$idsujet.'&mess='.$idmess.'#bas" class="quote" title="Citer ce message"></a>');
								if($_SESSION['id']==$idauteur OR $_SESSION['statut']=="Administrateur" OR $_SESSION['pseudo']==$modoGlob OR $_SESSION['pseudo']==$modoPart)
								print('
								<a href="javascript:ConfirmSuppr(\''.$idsujet.'\',\''.$idmess.'\');" class="suppr" title="Supprimer ce message"></a>
								<a href="sujet=editer.php?id='.$idsujet.'&mess='.$idmess.'#'.$idmess.'" class="edit" title="Editer ce message"></a>');
								}
						print('</div>
						<div class="info-perso">
							<div class="titre">'.$auteurmess.'</div>
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
								<!--Classement : '.$classementauteur.'<br />-->
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
					if($_SESSION['statut']!="visiteur")
					print('
					<div class="nouveausujet"><a href="sujet=nouveau.php?ssfid='.$ssforumid.'" title="Nouveau sujet">Nouveau sujet</a></div>
					<div class="repondre"><a href="sujet=repondre.php?id='.$idsujet.'#bas" title="R&eacute;pondre">R&eacute;pondre</a></div>');
					print('
				</div>
			</div>
			');
				
			mysql_close($db);
			
			
			?>
			
		</div>
	
	</body>
	
</html>
