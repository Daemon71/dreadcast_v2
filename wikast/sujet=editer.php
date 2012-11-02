<?php 
session_start();

if (false) {
	header('Status: 301 Moved Permanently');
	header('Location: http://www.dreadcast.net/Forum');
	exit;
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
				
				if($_GET['mess'] != -1) $textesujet = transforme_texte($textesujet);
					
				
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
				
				if($_GET['mess']==-1) $res2 = 0;
				else
					{
					$sql2 = 'SELECT numero FROM wikast_forum_posts_tbl WHERE id="'.$_GET['mess'].'" ' ;
					$req2 = mysql_query($sql2);
					$res2 = mysql_num_rows($req2);
				
					if($res2 != 0)
						{
						$numes = mysql_result($req2,0,'numero');
						$sql2 = 'SELECT * FROM wikast_forum_posts_tbl WHERE sujet= "'.$_GET['id'].'" AND numero >= '.($numes-3).' AND numero <= '.($numes+3).' ORDER BY date ASC' ;
						$req2 = mysql_query($sql2);
						$res2 = mysql_num_rows($req2);
						}
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
			else $ssforumid = -1;
			
			if($ssforumid == -1)
				{
				$sqltmp3 = 'SELECT statut FROM wikast_forum_permissions_tbl WHERE pseudo="'.$_SESSION['pseudo'].'" AND sujet="'.$_GET['id'].'"' ;
				$reqtmp3 = mysql_query($sqltmp3);
				$restmp3 = mysql_num_rows($reqtmp3);
				
				if($restmp3 == 0 && $_SESSION['statut']!="Administrateur")
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
					<p class="gauche">
					</p>
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
						print('<a href="#bas" title="Descendre en bas de page">Bas de page</a>
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
					
					if($_GET['mess']==-1)
						{
						
						if($monstatut != "Mod&eacute;rateur" && $auteursujet!=$_SESSION['pseudo'] && $_SESSION['statut']!="Administrateur" && $_SESSION['pseudo']!=$modoGlob && $_SESSION['pseudo']!=$modoPart)
							{
							print('<meta http-equiv="refresh" content="0 ; url=sujet.php?id='.$_GET['id'].'"> ');
							exit();
							}
					
					$textesujet = str_replace("<br />", "",$textesujet);
					
					print('<div class="premierpost" id="-1">
						<div class="boutons">');
							if($_SESSION['statut']!="visiteur")
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
							<div class="titre">'.$auteursujet.'</div>
							<div class="avatar"><img src="');
							
							if((ereg("http",$avatarauteur)) OR (ereg("ftp",$avatarauteur))) print($avatarauteur);
							else print('../ingame/avatars/'.$avatarauteur);
							
							print('" alt="Avatar" width="68" height="68" /></div>
							<div class="indicateurs">
								<img src="design/avatar_'.$raceauteur.'.gif" alt="'.$raceauteur.'" title="'.$raceauteur.'" /><br />
								<img src="design/icon_'.$sexeauteur.'.gif" alt="'.$sexeauteur.'" title="'.$sexeauteur.'" /><br />
								<img src="design/icon_connec-'.$connecauteur.'.gif" alt="');if($connecauteur=="oui")print('En ligne');else print('Absent');print('" /><br />
							</div>
							<p>
								'.$statutauteur.'<br />
								<!--Classement : '.$classementauteur.'<br />-->
								<a href="edc=visio.php?auteur='.$auteursujet.'" title="Voir son Espace DreadCast">Voir son EDC</a>
							</p>
						</div>
						<div class="texte">
							<form action="sujet.php?id='.$_GET['id'].'" method="post" name="poster" id="champ">');
								
								if($ssforumid == -1)
									{
									
									if($monstatut != "Mod&eacute;rateur" && $_SESSION['statut']!="Administrateur")
										{
										print('<meta http-equiv="refresh" content="0 ; url=forum=perso.php"> ');
										exit();
										}
									
									$sqltmp2 = 'SELECT pseudo FROM wikast_forum_permissions_tbl WHERE statut="l" AND sujet="'.$_GET['id'].'"' ;
									$reqtmp2 = mysql_query($sqltmp2);
									$restmp2 = mysql_num_rows($reqtmp2);
									if($restmp2 != 0) $lecteurs = mysql_result($reqtmp2,0,"pseudo");
									for($i=1;$i<$restmp2;$i++) $lecteurs .= ", ".mysql_result($reqtmp2,$i,"pseudo");
									
									$sqltmp2 = 'SELECT pseudo FROM wikast_forum_permissions_tbl WHERE statut="le" AND sujet="'.$_GET['id'].'"' ;
									$reqtmp2 = mysql_query($sqltmp2);
									$restmp2 = mysql_num_rows($reqtmp2);
									if($restmp2 != 0) $participants = mysql_result($reqtmp2,0,"pseudo");
									for($i=1;$i<$restmp2;$i++) $participants .= ", ".mysql_result($reqtmp2,$i,"pseudo");
									
									$sqltmp2 = 'SELECT pseudo FROM wikast_forum_permissions_tbl WHERE statut="lea" AND sujet="'.$_GET['id'].'"' ;
									$reqtmp2 = mysql_query($sqltmp2);
									$restmp2 = mysql_num_rows($reqtmp2);
									if($restmp2 != 0) $moderateurs = mysql_result($reqtmp2,0,"pseudo");
									for($i=1;$i<$restmp2;$i++) $moderateurs .= ", ".mysql_result($reqtmp2,$i,"pseudo");
					
									print('<table>
										<tr>
											<td>Nom des lecteurs (s&eacute;par&eacute;s par une virgule)</td>
											<td><input type="text" name="editl" value="'.$lecteurs.'" style="padding:3px 0 0 2px;height:17px;width:198px;background:#202020;border:1px solid #404040;color:#808080;" /></td>
										</tr>
										<tr>
											<td>Nom des participants (s&eacute;par&eacute;s par une virgule)</td>
											<td><input type="text" name="edite" value="'.$participants.'" style="padding:3px 0 0 2px;height:17px;width:198px;background:#202020;border:1px solid #404040;color:#808080;" /></td>
										</tr>
										<tr>
											<td>Nom des mod&eacute;rateurs (s&eacute;par&eacute;s par une virgule)</td>
											<td><input type="text" name="edita" value="'.$moderateurs.'" style="padding:3px 0 0 2px;height:17px;width:198px;background:#202020;border:1px solid #404040;color:#808080;" /></td>
										</tr>
									</table><br />');
									}
								
								print('<textarea name="message" id="textarea"  onmouseover="this.focus();">'.$textesujet.'</textarea><br />
								<div id="DCcode">
									<a href="javascript:AddText(\'[g]\',\'\',\'[/g]\');" title="Mettre le texte en gras" class="style1"><strong>G</strong></a>
									<a href="javascript:AddText(\'[i]\',\'\',\'[/i]\');" title="Mettre le texte en italique" class="style1"><em>I</em></a>
									<a href="javascript:AddText(\'[img url=<URL DE VOTRE IMAGE> /]\',\'\',\'\');" title="Afficher une image">IMG</a>
									<a href="javascript:AddText(\'[lien url=<URL DE VOTRE LIEN>]\',\'\',\'[/lien]\');" title="Cr&eacute;er un lien">LIEN</a>
									<a href="javascript:AddText(\'[quote]\',\'\',\'[/quote]\');" title="Citer un texte">QUOTE</a>
									<a href="javascript:AddText(\'[centrer]\',\'\',\'[/centrer]\');" title="Centrer un texte">CENTRER</a>
									<span class="colors"><a href="javascript:AddText(\'[couleur type=<blanc>]\',\'\',\'[/couleur]\');" title="Mettre le texte en blanc" style="color:#eee;">B</a>
									<a href="javascript:AddText(\'[couleur type=<noir>]\',\'\',\'[/couleur]\');" title="Mettre le texte en noir" style="color:#111;">N</a>
									<a href="javascript:AddText(\'[couleur type=<rouge>]\',\'\',\'[/couleur]\');" title="Mettre le texte en rouge" style="color:#d32929;">R</a>
									<a href="javascript:AddText(\'[couleur type=<bleu>]\',\'\',\'[/couleur]\');" title="Mettre le texte en bleu" style="color:#40719A;">B</a>
									<a href="javascript:AddText(\'[couleur type=<vert>]\',\'\',\'[/couleur]\');" title="Mettre le texte en vert" style="color:#168f16;">V</a>
									<a href="javascript:AddText(\'[couleur type=<jaune>]\',\'\',\'[/couleur]\');" title="Mettre le texte en jaune" style="color:#d59713;">J</a></span>
								</div>
								<input name="submit" type="submit" value="Envoyer" id="ok" />
								<input name="type" type="hidden" value="edit" />
								<input name="idmess" type="hidden" value="-1" />
							</form>
						</div>
					</div>');
						}
					else
						{
						
						if($numes<=3)
						{
						print('<div class="premierpost" id="-1">
						<div class="boutons">');
							if($_SESSION['statut']!="visiteur")
								{
								print('<a href="sujet=repondre.php?id='.$_GET['id'].'&mess=-1#bas" class="quote" title="Citer ce message"></a>');
								if($_SESSION['id']==$idauteur OR $_SESSION['statut']=="Administrateur" OR $_SESSION['pseudo']==$modoGlob OR $_SESSION['pseudo']==$modoPart)
								print('
								<a href="sujet=supprimer.php?id='.$_GET['id'].'&mess=-1" class="suppr" title="Supprimer ce message"></a>
								<a href="sujet=editer.php?id='.$_GET['id'].'&mess=-1" class="edit" title="Editer ce message"></a>');
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
								<img src="design/icon_connec-'.$connecauteur.'.gif" alt="');if($connecauteur=="oui")print('En ligne');else print('Absent');print('" /><br />
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
						}
					
					for($i=0 ; $i<$res2 ; $i++)
						{
						
						$idmess = mysql_result($req2,$i,"id");
						$textemess = mysql_result($req2,$i,"contenu");
						$auteurmess = mysql_result($req2,$i,"auteur");
						$datemess = mysql_result($req2,$i,"date");
						
						if($idmess != $_GET['mess']) $textemess = transforme_texte($textemess);
							
						
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
						else
							{
							$idauteur = "";
							$statutauteur = "";
							$avatarauteur = "";
							$raceauteur = "";
							$sexeauteur = "";
							$connecauteur = "non";
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
						
					if($idmess==$_GET['mess'])
						{
						
						if($monstatut != "Mod&eacute;rateur" && $auteurmess!=$_SESSION['pseudo'] && $_SESSION['statut']!="Administrateur" && $_SESSION['pseudo']!=$modoGlob && $_SESSION['pseudo']!=$modoPart)
							{
							print('<meta http-equiv="refresh" content="0 ; url=sujet.php?id='.$_GET['id'].'&page=max"> ');
							exit();
							}
						
						$textemess = str_replace("<br />", "",$textemess);
						
						print('<div class="post"  id="'.$idmess.'">
						<div class="barre-infos">
							<p class="infos">Post&eacute; par <span class="style1">'.$auteurmess.'</span> le '.date('d/m/Y',$datemess).' &agrave '.date('H:i',$datemess).'</p>
						</div>
						<div class="boutons">');
						if($_SESSION['statut']!="visiteur")
								{
								print('<a href="sujet=repondre.php?id='.$_GET['id'].'&mess='.$idmess.'#bas" class="quote" title="Citer ce message"></a>');
								if($_SESSION['id']==$idauteur OR $_SESSION['statut']=="Administrateur" OR $_SESSION['pseudo']==$modoGlob OR $_SESSION['pseudo']==$modoPart)
								print('
								<a href="javascript:ConfirmSuppr(\''.$_GET['id'].'\',\''.$idmess.'\');" class="suppr" title="Supprimer ce message"></a>
								<a href="sujet=editer.php?id='.$_GET['id'].'&mess='.$idmess.'" class="edit" title="Editer ce message"></a>');
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
						<div class="texte">
							<form action="sujet.php?id='.$_GET['id'].'&page=max#'.$idmess.'" method="post" name="poster" id="champ">
								<textarea name="message" id="textarea"  onmouseover="this.focus();">'.$textemess.'</textarea><br />
								<div id="DCcode">
									<a href="javascript:AddText(\'[g]\',\'\',\'[/g]\');" title="Mettre le texte en gras" class="style1"><strong>G</strong></a>
									<a href="javascript:AddText(\'[i]\',\'\',\'[/i]\');" title="Mettre le texte en italique" class="style1"><em>I</em></a>
									<a href="javascript:AddText(\'[img url=<URL DE VOTRE IMAGE> /]\',\'\',\'\');" title="Afficher une image">IMG</a>
									<a href="javascript:AddText(\'[lien url=<URL DE VOTRE LIEN>]\',\'\',\'[/lien]\');" title="Cr&eacute;er un lien">LIEN</a>
									<a href="javascript:AddText(\'[quote]\',\'\',\'[/quote]\');" title="Citer un texte">QUOTE</a>
									<a href="javascript:AddText(\'[centrer]\',\'\',\'[/centrer]\');" title="Centrer un texte">CENTRER</a>
									<span class="colors"><a href="javascript:AddText(\'[couleur type=<blanc>]\',\'\',\'[/couleur]\');" title="Mettre le texte en blanc" style="color:#eee;">B</a>
									<a href="javascript:AddText(\'[couleur type=<noir>]\',\'\',\'[/couleur]\');" title="Mettre le texte en noir" style="color:#111;">N</a>
									<a href="javascript:AddText(\'[couleur type=<rouge>]\',\'\',\'[/couleur]\');" title="Mettre le texte en rouge" style="color:#d32929;">R</a>
									<a href="javascript:AddText(\'[couleur type=<bleu>]\',\'\',\'[/couleur]\');" title="Mettre le texte en bleu" style="color:#40719A;">B</a>
									<a href="javascript:AddText(\'[couleur type=<vert>]\',\'\',\'[/couleur]\');" title="Mettre le texte en vert" style="color:#168f16;">V</a>
									<a href="javascript:AddText(\'[couleur type=<jaune>]\',\'\',\'[/couleur]\');" title="Mettre le texte en jaune" style="color:#d59713;">J</a></span>
								</div>
								<input name="submit" type="submit" value="Envoyer" id="ok" />
								<input name="type" type="hidden" value="edit" />
								<input name="idmess" type="hidden" value="'.$idmess.'" />
							</form>
						</div>
					</div>');
						}
					else
						{
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
							elseif ($auteurmess == 'Dreadcast') {
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
						}
					
					print('	
					
				</div>
					
				<div id="bas">
				</div>
			</div>
			');
				
			mysql_close($db);
			?>
			
		</div>
	
	</body>
	
</html>
