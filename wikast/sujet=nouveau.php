<?php 
session_start();

if (false) {
	header('Status: 301 Moved Permanently');
	header('Location: http://www.dreadcast.net/Forum');
	exit;
}

if($_GET['ssfid'] == -1) $_GET['prive'] = "ok";

if(empty($_SESSION['id']))
	{
	print('<meta http-equiv="refresh" content="0 ; url=forum=accueil.php"> ');
	exit();
	}

if($_POST['titre']=="" AND $_POST['message']=="")
	{
	$erreurtitre = "";
	$erreurtexte = "";
	$erreurl = $_POST['l'];
	$erreure = $_POST['e'];
	$erreura = $_POST['a'];
	}
elseif($_POST['titre']=="" OR $_POST['message']=="")
	{
	$erreurtitre = $_POST['titre'];
	$erreurtexte = $_POST['message'];
	$erreurl = $_POST['l'];
	$erreure = $_POST['e'];
	$erreura = $_POST['a'];
	}
else
	{
	$titresujet = stripslashes(htmlentities($_POST['titre'],ENT_QUOTES));
	
	$message = stripslashes(htmlentities($_POST['message'],ENT_QUOTES));
	$message = str_replace("\n", "<br />",$message);
	
	$datesujet = time();
	
	$db = mysql_connect('localhost', 'CENSURE', 'CENSURE') or die("Erreur de connexion au serveur des messages.");
	mysql_select_db('CENSURE',$db) or die("Erreur de connexion a la base de donnees des messages.");
	
	$ssfid = ($_GET['ssfid'] == "")?"-1":$_GET['ssfid'];
	
	$sql = 'INSERT INTO wikast_forum_sujets_tbl(id,categorie,nom,date,datemodif,auteur,contenu) VALUES("","'.$ssfid.'","'.$titresujet.'","'.$datesujet.'","'.$datesujet.'","'.$_SESSION['pseudo'].'","'.$message.'")';
	$req = mysql_query($sql);
	
	$sql = 'SELECT id FROM wikast_forum_sujets_tbl WHERE nom="'.$titresujet.'" AND date="'.$datesujet.'"';
	$req = mysql_query($sql);
	
	$lid = mysql_result($req,0,id);
	
	if($ssfid == -1)
		{
		$sql2 = 'INSERT INTO wikast_forum_permissions_tbl(id,sujet,pseudo,statut) VALUES("","'.$lid.'","'.$_SESSION['pseudo'].'","lea")';
		$req2 = mysql_query($sql2);
		
		$lecteurs = explode(",",$_POST['l']);
		$participants = explode(",",$_POST['e']);
		$moderateurs = explode(",",$_POST['a']);
		if(count($moderateurs) != 1)
			{
			for($i=0;$i<count($moderateurs);$i++)
				{
				$sql = 'SELECT id FROM wikast_forum_permissions_tbl WHERE pseudo="'.trim($moderateurs[$i]).'" AND sujet="'.$lid.'"';
				$req = mysql_query($sql);
				$res = mysql_num_rows($req);
				
				if($res == 0 AND $moderateurs[$i] != "")
					{
					$texte = "<br /><br /><br />".$_SESSION['pseudo']." vous a invit&eacute; au sujet priv&eacute; &quot;".$titresujet."&quot; en tant que mod&eacute;rateur.";
					$texte .= "<br /><br />Rendez-vous sur votre forum personnel pour en apprendre plus.";
					$sql2 = 'INSERT INTO wikast_forum_permissions_tbl(id,sujet,pseudo,statut) VALUES("","'.$lid.'","'.trim($moderateurs[$i]).'","lea")';
					mysql_query($sql2);
					$sql2 = 'INSERT INTO messages_tbl(id,auteur,cible,message,objet,moment) VALUES("","Dreadcast","'.trim($moderateurs[$i]).'","'.$texte.'","Sujet priv&eacute;","'.time().'")';
					mysql_query($sql2);
					}
				}
			}
		if(count($participants) != 1)
			{
			for($i=0;$i<count($participants);$i++)
				{
				$sql = 'SELECT id FROM wikast_forum_permissions_tbl WHERE pseudo="'.trim($participants[$i]).'" AND sujet="'.$lid.'"';
				$req = mysql_query($sql);
				$res = mysql_num_rows($req);
				
				if($res == 0 AND $participants[$i] != "")
					{
					$texte = "<br /><br /><br />".$_SESSION['pseudo']." vous a invit&eacute; au sujet priv&eacute; &quot;".$titresujet."&quot; en tant que participant.";
					$texte .= "<br /><br />Rendez-vous sur votre forum personnel pour en apprendre plus.";
					$sql2 = 'INSERT INTO wikast_forum_permissions_tbl(id,sujet,pseudo,statut) VALUES("","'.$lid.'","'.trim($participants[$i]).'","le")';
					mysql_query($sql2);
					$sql2 = 'INSERT INTO messages_tbl(id,auteur,cible,message,objet,moment) VALUES("","Dreadcast","'.trim($participants[$i]).'","'.$texte.'","Sujet priv&eacute;","'.time().'")';
					mysql_query($sql2);
					}
				}
			}
		if(count($lecteurs) != 1)
			{
			for($i=0;$i<count($lecteurs);$i++)
				{
				$sql = 'SELECT id FROM wikast_forum_permissions_tbl WHERE pseudo="'.trim($lecteurs[$i]).'" AND sujet="'.$lid.'"';
				$req = mysql_query($sql);
				$res = mysql_num_rows($req);
				
				if($res == 0 AND $lecteurs[$i] != "")
					{
					$texte = "<br /><br /><br />".$_SESSION['pseudo']." vous a invit&eacute; au sujet priv&eacute; &quot;".$titresujet."&quot; en tant que lecteur.";
					$texte .= "<br /><br />Rendez-vous sur votre forum personnel pour en apprendre plus.";
					$sql2 = 'INSERT INTO wikast_forum_permissions_tbl(id,sujet,pseudo,statut) VALUES("","'.$lid.'","'.trim($lecteurs[$i]).'","l")';
					mysql_query($sql2);
					$sql2 = 'INSERT INTO messages_tbl(id,auteur,cible,message,objet,moment) VALUES("","Dreadcast","'.trim($lecteurs[$i]).'","'.$texte.'","Sujet priv&eacute;","'.time().'")';
					mysql_query($sql2);
					}
				}
			}
		}
		
	
	mysql_close($db);
	
	print('<meta http-equiv="refresh" content="0 ; url=sujet.php?id='.mysql_result($req,0,id).'&page=max"> ');
	exit();
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
			
			<?
			
			$db = mysql_connect('localhost', 'CENSURE', 'CENSURE') or die("Erreur de connexion au serveur des messages.");
			mysql_select_db('CENSURE',$db) or die("Erreur de connexion a la base de donnees des messages.");
			
			if($_GET['ssfid'] == 5 && statut($_SESSION['statut'])<7) {
                print('<meta http-equiv="refresh" content="0 ; url=forum=accueil.php"> ');
            	exit();
            }
			
			if($_GET['ssfid'] != "")
			{
			$sqltmp = 'SELECT nom,type,admin FROM wikast_forum_structure_tbl WHERE id= "'.$_GET['ssfid'].'"' ;
			$reqtmp = mysql_query($sqltmp);
			$ssforum = mysql_result($reqtmp,0,"nom");
			$ssforumid = $_GET['ssfid'];
			$modoPart = mysql_result($reqtmp,0,"admin");
			$sqltmp = 'SELECT nom FROM wikast_forum_structure_tbl WHERE id= "'.mysql_result($reqtmp,0,"type").'"' ;
			$reqtmp = mysql_query($sqltmp);
			$forum = mysql_result($reqtmp,0,"nom");
			}
			
			$monsexe = ($_SESSION['sexe']=="Homme" OR $_SESSION['sexe']=="Masculin")? "Homme":"Femme";
			
			print('<div id="forum-entete">');
			
			include('include/inc_barreliens1.php');	
			
			print('
			
				<div id="forum-info2">
				</div>
			</div>
				
			<div id="mainpage-forum">
				<div id="haut">');
					if($_GET['prive'] != "ok") print('<a href="forum.php?partie='.$forum.'&sous-partie='.$ssforumid.'" title="Retour" id="btn_retour"></a>
					<p><a href="forum.php?partie='.$forum.'" title="Vers le forum '.$forum.'">Forum '.$forum.'</a> > <a href="forum.php?partie='.$forum.'&sous-partie='.$ssforumid.'" title="Vers le sous-forum '.$ssforum.'">'.$ssforum.'</a></p>');
					else print('<a href="forum=perso.php" title="Retour" id="btn_retour"></a>
					<p><a href="forum=perso.php" title="Vers le forum personnel">Forum personnel</a></p>');
				print('</div>
					
				<div id="contenu">
					<form action="sujet=nouveau.php?ssfid='.$_GET['ssfid'].'" method="post" id="champ" name="poster">');
					
					if($_GET['prive'] != "ok")
					print('<div class="titre-sujet">
						<input name="titre" type="text" value="'.$erreurtitre.'" class="titre" />
						<p class="infos">Post&eacute; par <span class="style1">'.$_SESSION['pseudo'].'</span> le ... &agrave ...</p>
					</div>');
					
					print('<div class="premierpost" id="-1">
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
								<img src="design/icon_'.$monsexe.'.gif" alt="'.$monsexe.'" title="'.$monsexe.'" /><br />
								<img src="design/icon_connec-oui.gif" alt="En ligne" /><br />
							</div>
							<p>
								'.$_SESSION['statut'].'<br />
								Classement : ...<br />
								<a href="edc=visio.php?auteur=" title="Voir son Espace DreadCast">Voir son EDC</a>
							</p>
						</div>
						<div class="texte">');
							if($_GET['prive'] == "ok")
								{
								$sqlhop = 'SELECT id FROM wikast_forum_sujets_tbl WHERE auteur = "'.$_SESSION['pseudo'].'" AND categorie = -1' ;
								$reqhop = mysql_query($sqlhop);
								$reshop = mysql_num_rows($reqhop);
								
								if($reshop < 30 || statut($_SESSION['statut'])>=2)
								print('<h3>Titre</h3>
								<input name="titre" type="text" value="'.$erreurtitre.'" style="padding:3px 0 0 2px;height:17px;width:298px;background:#202020;border:1px solid #404040;color:#808080;" />
								<h3>D&eacute;finition des statuts</h3>
								Vous pouvez sp&eacute;cifier ici qui aura acc&egrave;s &agrave; ce sujet et quels seront ses droits.<br />
								Il n\'est pas n&eacute;cessaire de remplir tous les champs.<br /><br />
								<table>
									<tr>
										<td>Nom des lecteurs (s&eacute;par&eacute;s par une virgule)</td>
										<td><input type="text" name="l" value="'.$erreurl.'" style="padding:3px 0 0 2px;height:17px;width:198px;background:#202020;border:1px solid #404040;color:#808080;" /></td>
									</tr>
									<tr>
										<td>Nom des participants (s&eacute;par&eacute;s par une virgule)</td>
										<td><input type="text" name="e" value="'.$erreure.'" style="padding:3px 0 0 2px;height:17px;width:198px;background:#202020;border:1px solid #404040;color:#808080;" /></td>
									</tr>
									<tr>
										<td>Nom des mod&eacute;rateurs (s&eacute;par&eacute;s par une virgule)</td>
										<td><input type="text" name="a" value="'.$erreura.'" style="padding:3px 0 0 2px;height:17px;width:198px;background:#202020;border:1px solid #404040;color:#808080;" /></td>
									</tr>
								</table>
								<h3>Message</h3>
								<textarea name="message" id="textarea" style="height:250px;">'.$erreurtexte.'</textarea><br />
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
								<input name="type" type="hidden" value="reponse" />');
								else print('<br /><br /><br />Vous avez d&eacute;j&agrave; cr&eacute;&eacute; 30 sujets priv&eacute;s. Vous ne pouvez pas en poss&eacute;der plus.<br />Vous pouvez supprimer ceux qui ne vous int&eacute;ressent plus ou bien prendre un Compte VIP pour pouvoir en recr&eacute;er d\'autres.');
								}
							else
								print('<textarea name="message" id="textarea" style="height:250px;">'.$erreurtexte.'</textarea><br />
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
								<input name="type" type="hidden" value="reponse" />');
							print('</form>
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
