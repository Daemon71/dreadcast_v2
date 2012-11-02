<?php 
session_start();

if (false) {
	header('Status: 301 Moved Permanently');
	header('Location: http://www.dreadcast.net/Forum');
	exit;
}

if(empty($_SESSION['id']))
	{
	print('<meta http-equiv="refresh" content="0 ; url=sujet.php?id='.$_GET['id'].'"> ');
	exit();
	}
	
if(!empty($_GET['mess']))
	{
	$quote = $_GET['mess'];
	
	$db = mysql_connect('localhost', 'CENSURE', 'CENSURE') or die("Erreur de connexion au serveur des messages.");
	mysql_select_db('CENSURE',$db) or die("Erreur de connexion a la base de donnees des messages.");
	
	if($quote==-1)
		{
		$sql = 'SELECT auteur,contenu FROM wikast_forum_sujets_tbl WHERE id="'.$_GET['id'].'"';
		$req = mysql_query($sql);
		$res = mysql_num_rows($req);
		
		if($res!=0)
			{
			$quote='[quote]'.mysql_result($req,0,auteur).' a &eacute;crit :
'.mysql_result($req,0,contenu).'[/quote]
';
			}
		}
	else
		{
		$sql = 'SELECT auteur,contenu FROM wikast_forum_posts_tbl WHERE id="'.$quote.'"';
		$req = mysql_query($sql);
		$res = mysql_num_rows($req);
		
		if($res!=0)
			{
			$quote='[quote]'.mysql_result($req,0,auteur).' a &eacute;crit :
'.mysql_result($req,0,contenu).'[/quote]
';
			}
		}
	
	mysql_close($db);
	}
	else $quote = "";
	
	$quote = str_replace("<div class=\"text-quote\">", "[quote]",$quote);
	$quote = str_replace("<!--quote--></div>", "[/quote]",$quote);
	$quote = str_replace("<br />", "",$quote);

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
			
			if (!isset($_GET['id'])) {
				$sql = 'INSERT INTO triche_tbl VALUES (NULL, "'.$_SESSION['pseudo'].'", "'.time().'", "0", "'.$_GET['mess'].'", "Acces non autorise a un message")';
				echo '<' .$sql. '>';
				mysql_query($sql);
				//print('<meta http-equiv="refresh" content="0 ; url=forum=accueil.php"> ');
				//exit();
			}
			
			$sql = 'SELECT categorie,nom,contenu,auteur,date FROM wikast_forum_sujets_tbl WHERE id= "'.$_GET['id'].'"' ;
			$req = mysql_query($sql);
			$res = mysql_num_rows($req);
			
			if($res!=0)
				{
				$titresujet = mysql_result($req,0,"nom");
				$textesujet = mysql_result($req,0,"contenu");
				$auteursujet = mysql_result($req,0,"auteur");
				$datesujet = mysql_result($req,0,"date");
				
				if(ereg("\[Verrouill&eacute;\]",$titresujet))
					{
					print('<meta http-equiv="refresh" content="0 ; url=forum=accueil.php"> ');
					exit();
					}
				if(mysql_result($req,0,"categorie") == 65)
					{
					print('<meta http-equiv="refresh" content="0 ; url=forum=accueil.php"> ');
					exit();
					}
				if(mysql_result($req,0,"categorie") == 5 && statut($_SESSION['statut'])<7) {
                    print('<meta http-equiv="refresh" content="0 ; url=forum=accueil.php"> ');
                	exit();
                }
				
				// ENREGISTRE LE MESSAGE A CITER
				
				$textesujet = transforme_texte($textesujet);
				
				$sqla = 'SELECT id,statut,avatar,race,sexe,connec,total FROM principal_tbl WHERE pseudo= "'.$auteursujet.'"' ;
				$reqa = mysql_query($sqla);
				
				$idauteur = mysql_result($reqa,0,"id");
				$statutauteur = mysql_result($reqa,0,"statut");
				if($statutauteur == "Compte VIP") $statutauteur = "VIP";
				$avatarauteur = mysql_result($reqa,0,"avatar");
				$raceauteur = mysql_result($reqa,0,"race");
				$sexeauteur = mysql_result($reqa,0,"sexe");
				$connecauteur = mysql_result($reqa,0,"connec");
				
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
				
				$sql2 = 'SELECT MAX(numero) FROM wikast_forum_posts_tbl WHERE sujet= "'.$_GET['id'].'"' ;
				$req2 = mysql_query($sql2);
				$res2 = mysql_num_rows($req2);

				if($res2 != 0)
					{
					$numax = mysql_result($req2,0,'MAX(numero)');
					$sql2 = 'SELECT * FROM wikast_forum_posts_tbl WHERE sujet= "'.$_GET['id'].'" AND numero > '.($numax-5).' ORDER BY date ASC' ;
					$req2 = mysql_query($sql2);
					$res2 = mysql_num_rows($req2);
					}
				}
			else $res2 = 0;
			
			if(mysql_result($req,0,"categorie") != -1)
			{
			$sqltmp = 'SELECT nom,type,admin FROM wikast_forum_structure_tbl WHERE id= "'.mysql_result($req,0,"categorie").'"' ;
			$reqtmp = mysql_query($sqltmp);
			$ssforumid = mysql_result($req,0,"categorie");
			$ssforum = mysql_result($reqtmp,0,"nom");
			$modoPart = mysql_result($reqtmp,0,"admin");
			$sqltmp = 'SELECT nom,admin FROM wikast_forum_structure_tbl WHERE id= "'.mysql_result($reqtmp,0,"type").'"' ;
			$reqtmp = mysql_query($sqltmp);
			$forum = mysql_result($reqtmp,0,"nom");
			$modoGlob = mysql_result($reqtmp,0,"admin");
			}
			else
				{
				$ssforumid = -1;
				
				$sqltmp3 = 'SELECT statut FROM wikast_forum_permissions_tbl WHERE pseudo="'.$_SESSION['pseudo'].'" AND sujet="'.$_GET['id'].'"' ;
				$reqtmp3 = mysql_query($sqltmp3);
				$restmp3 = mysql_num_rows($reqtmp3);
				
				if($restmp3 == 0 OR mysql_result($reqtmp3,"admin") == "l")
					{
					print('<meta http-equiv="refresh" content="0 ; url=forum=perso.php"> ');
					exit();
					}
				}
			
			print('<div id="forum-entete">');
			
			include('include/inc_barreliens1.php');	
			
			print('
					
				<div id="forum-info2">
					<p class="gauche">
					</p>
						
					<p class="droite">
						<a href="sujet=repondre.php?id='.$_GET['id'].'#bas" title="R&eacute;pondre">R&eacute;pondre</a><br />
						<a href="sujet=nouveau.php?ssfid='.mysql_result($req,0,categorie).'" title="Nouveau sujet">Nouveau sujet</a><br />
						<a href="#bas" title="Descendre en bas de page">Bas de page</a>
					</p>
				</div>
			</div>
				
			<div id="mainpage-forum">
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
						<p class="infos">Post&eacute; par <span class="style1">'.$auteursujet.'</span> le '.date('d/m/Y',$datesujet).' &agrave '.date('H:i',$datesujet).'</p>
					</div>');
					
					if($res2<5)
						{
					
					print('<div class="premierpost">
						<div class="boutons">
							<a href="sujet=repondre.php?id='.$_GET['id'].'&mess=-1#bas" class="quote" title="Citer ce message"></a>');
							if($_SESSION['id']==$idauteur OR $_SESSION['statut']=="Administrateur" OR $_SESSION['pseudo']==$modoGlob OR $_SESSION['pseudo']==$modoPart)
							print('
							<a href="javascript:ConfirmSuppr(\''.$_GET['id'].'\',\'-1\');" class="suppr" title="Supprimer ce message"></a>
							<a href="sujet=editer.php?id='.$_GET['id'].'&mess=-1#-1" class="edit" title="Editer ce message"></a>');
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
						}

					for($i=0 ; $i<$res2 ; $i++)
						{
						
						$idmess = mysql_result($req2,$i,"id");
						$textemess = mysql_result($req2,$i,"contenu");
						$auteurmess = mysql_result($req2,$i,"auteur");
						$datemess = mysql_result($req2,$i,"date");
						
						$textemess = transforme_texte($textemess);
						
						$sqla = 'SELECT id,statut,avatar,race,sexe,connec,total FROM principal_tbl WHERE pseudo= "'.$auteurmess.'"' ;
						$reqa = mysql_query($sqla);
						$resa = mysql_num_rows($reqa);
						if($resa!=0)
							{
							$idauteur = mysql_result($reqa,0,"id");
							$statutauteur = mysql_result($reqa,0,"statut");
							if($statutauteur == "Compte VIP") $statutauteur = "VIP";
							$avatarauteur = mysql_result($reqa,0,"avatar");
							$raceauteur = mysql_result($reqa,0,"race");
							$sexeauteur = mysql_result($reqa,0,"sexe");
							$connecauteur = mysql_result($reqa,0,"connec");
							}
						
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
						
					print('<div class="post"'.($auteurmess == 'Dreadcast' ? ' style="min-height:70px;height:70px;"' : '').'>
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
							
							if ($auteurmess == 'Dreadcast') {
								$avatarauteur = '';
								$raceauteur = '';
								$sexeauteur = '';
								$connecauteur = '';
								$statutauteur = '';
								$classementauteur = 0;
							}
								
							if($avatarauteur != "")
							{
							if((ereg("http",$avatarauteur)) OR (ereg("ftp",$avatarauteur))) print($avatarauteur);
							else print('../ingame/avatars/'.$avatarauteur);
							}
							elseif ($auteurmess == 'Dreadcast') print('../ingame/avatars/wikast.jpg');
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
									$titre = ucfirst(preg_replace("#^-talentchoisi:(.+)-#isU","$1",mysql_result($reqhop,0,infoperso))).'<br />';
									if(ereg("talentchoisi",$titre)) $titre = ucfirst(preg_replace("#(.+)-talentchoisi:(.+)-#isU","$2",mysql_result($reqhop,0,infoperso))).'<br />';
									}
								else $titre = "";
								
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
				
					$monsexe = ($_SESSION['sexe']=="Homme" OR $_SESSION['sexe']=="Masculin")? "Homme":"Femme";
					
					print('
					<div class="post">
						<div class="barre-infos">
							<p class="infos">Post&eacute; par <span class="style1">'.$_SESSION['pseudo'].'</span> le ... &agrave ...</p>
						</div>
						<div class="boutons">
						</div>
						<div class="info-perso">
							<div class="titre">'.$_SESSION['pseudo'].'</div>
							<div class="avatar"><img src="');
							
							if((ereg("http",$_SESSION['avatar'])) OR (ereg("ftp",$_SESSION['avatar']))) print($_SESSION['avatar']);
							else print('../ingame/avatars/'.$_SESSION['avatar']);
							
							print('" alt="Avatar" width="68" height="68" /></div>
							<div class="indicateurs">
								<img src="design/avatar_'.$_SESSION['race'].'.gif" alt="'.$_SESSION['race'].'" title="'.$_SESSION['race'].'" /><br />
								<img src="design/icon_'.$monsexe.'.gif" alt="'.$monsexe.'" alt="'.$monsexe.'" /><br />
								<img src="design/icon_connec-oui.gif" alt="En ligne" title="En ligne" /><br />
							</div>
							<p>
								'.$_SESSION['statut'].'<br />
								<!--Classement : '.$_SESSION['classement'].'<br />-->
								<a href="edc=visio.php?auteur='.$_SESSION['pseudo'].'" title="Voir son Espace DreadCast">Voir son EDC</a>
							</p>
						</div>
						<div class="texte">
							<form action="sujet.php?id='.$_GET['id'].'&page=max#bas" method="post" name="poster" id="champ">
								<textarea name="message" id="textarea"  onmouseover="this.focus();">'.$quote.'</textarea><br />
								<div id="DCcode">
									<a href="javascript:AddText(\'[g]\',\'\',\'[/g]\');" title="Mettre le texte en gras" class="style1"><strong>G</strong></a>
									<a href="javascript:AddText(\'[i]\',\'\',\'[/i]\');" title="Mettre le texte en italique" class="style1"><em>I</em></a>
									<a href="javascript:AddText(\'[img url=<URL DE VOTRE IMAGE> /]\',\'\',\'\');" title="Afficher une image">IMG</a>
									<a href="javascript:AddText(\'[lien url=<URL DE VOTRE LIEN>]\',\'\',\'[/lien]\');" title="Cr&eacute;er un lien">LIEN</a>
									<a href="javascript:AddText(\'[quote]\',\'\',\'[/quote]\');" title="Citer un texte">QUOTE</a>
									<a href="javascript:AddText(\'[centrer]\',\'\',\'[/centrer]\');" title="Centrer un texte">CENTRER</a>
									<span class="colors">
										<a href="javascript:AddText(\'[couleur type=<blanc>]\',\'\',\'[/couleur]\');" title="Mettre le texte en blanc" style="color:#eee;">B</a>
										<a href="javascript:AddText(\'[couleur type=<noir>]\',\'\',\'[/couleur]\');" title="Mettre le texte en noir" style="color:#111;">N</a>
										<a href="javascript:AddText(\'[couleur type=<rouge>]\',\'\',\'[/couleur]\');" title="Mettre le texte en rouge" style="color:#d32929;">R</a>
										<a href="javascript:AddText(\'[couleur type=<bleu>]\',\'\',\'[/couleur]\');" title="Mettre le texte en bleu" style="color:#40719A;">B</a>
										<a href="javascript:AddText(\'[couleur type=<vert>]\',\'\',\'[/couleur]\');" title="Mettre le texte en vert" style="color:#168f16;">V</a>
										<a href="javascript:AddText(\'[couleur type=<jaune>]\',\'\',\'[/couleur]\');" title="Mettre le texte en jaune" style="color:#d59713;">J</a>
									</span>
								</div>
								<input name="submit" type="submit" value="Envoyer" id="ok" />
								<input name="type" type="hidden" value="reponse" />
							</form>
						</div>
					</div>	
					
				</div>
					
				<div id="bas">
				</div>
			</div>');
			
			mysql_close($db);
			?>
			
		</div>
	
	</body>
	
</html>
