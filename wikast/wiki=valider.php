<?php 
session_start();

if(empty($_SESSION['id'])) $_SESSION['statut'] = "visiteur";
else
	{
	
	$db = mysql_connect('localhost', 'CENSURE', 'CENSURE') or die("Erreur de connexion au serveur des messages.");
	mysql_select_db('CENSURE',$db) or die("Erreur de connexion a la base de donnees des messages.");
	
	$sql = 'SELECT id FROM wikast_joueurs_tbl WHERE pseudo="'.$_SESSION['pseudo'].'"' ;
	$req = mysql_query($sql);
	$res = mysql_num_rows($req);
	
	if($res == 0)
		{
		$sql = 'INSERT INTO wikast_joueurs_tbl(id,pseudo,infoperso,sujets_vu,commentaire) VALUES("","'.$_SESSION['pseudo'].'","-","-","")' ;
		mysql_query($sql);
		}
	
	if($_POST['titre']=="" OR $_POST['categorie']=="" OR $_POST['introduction']=="")
		{
		$titre = $_POST['titre'];
		$createur = $_SESSION['pseudo'];
		$moment = time();
		$categorie = $_POST['categorie'];
		
		$messerreur = "Erreur(s) :<br />";
		if($_POST['titre']=="") $messerreur .= "Vous avez oubli&eacute; de sp&eacute;cifier un titre.<br />";
		if($_POST['categorie']=="") $messerreur .= "Vous n'avez pas pr&eacute;cis&eacute; de cat&eacute;gorie.<br />";
		if($_POST['introduction']=="") $messerreur .= "Une introduction est n&eacute;cessaire.<br />";
		$messerreur .= "<br />";
		}
	else
		{
		if($_POST['id']=="") // SI PAS MODIFICATION
			{
			$sql = 'SELECT id FROM wikast_wiki_articles_tbl WHERE titre="'.htmlentities($_POST['titre']).'"';
			$req = mysql_query($sql);
			$res = mysql_num_rows($req);
		
			if($res!=0) $messerreur = "Un article existe d&eacute;j&agrave; sur ce sujet.<br /><br />";
			}
		else
			{
			if($_POST['commentaire']=="") $messerreur = "Vous devez justifier votre modification pour que les administrateurs puissent statuer plus rapidement sur le cas.<br /><br />";
			}
		
		$titre = $_POST['titre'];
		$createur = $_SESSION['pseudo'];
		$moment = time();
		$categorie = $_POST['categorie'];
		$contenu = str_replace("\n","<br />",$_POST['introduction']);
		
		
		if(ereg("Veuillez .{1}crire entre les balises \[paragraphe\]\[/paragraphe\]",$_POST['article'])) $article = "";
		else $article = $_POST['article'];
		
		
		if($article!="")
			{
			
			$tab = explode("[paragraphe]",$article);
			$temp = $tab[0];
			
			for($i=1;$i<count($tab);$i++)
				{
				$tab2 = explode("[/paragraphe]",$tab[$i]);
				$retour = str_replace("\n","<br />",$tab2[0]);
				$retour .= "[/paragraphe]".$tab2[1];
				$temp .= "[paragraphe]".$retour;
				}
				
			$article = $temp;
			
			$tab = explode("[quote]",$article);
			$temp = $tab[0];
			
			for($i=1;$i<count($tab);$i++)
				{
				$tab2 = explode("[/quote]",$tab[$i]);
				$retour = str_replace("\n","<br />",$tab2[0]);
				$retour .= "[/quote]".$tab2[1];
				$temp .= "[quote]".$retour;
				}
				
			$article = $temp;
		
			$contenu .= "[LIMITE-INTRODUCTION]".$article;
			}
			
		// ON DECRYPTE LE MESSAGE //
		
		if(ereg("\[g\]",$contenu) && ereg("\[/g\]",$contenu))
			{
			$contenu = str_replace("[g]", "<strong>",$contenu);
			$contenu = str_replace("[/g]", "</strong>",$contenu);
			}
		if(ereg("\[i\]",$contenu) && ereg("\[/i\]",$contenu))
			{
			$contenu = str_replace("[i]", "<em>",$contenu);
			$contenu = str_replace("[/i]", "</em>",$contenu);
			}
		
		if(ereg("\[fiche image=<",$contenu) && ereg("\[/fiche\]",$contenu))
			{
			
			$tab = explode("[fiche image=<",$contenu);
			$temp = $tab[0];
		
			$tailletab = count($tab);
			
			for($i=1;$i<$tailletab;$i++)
				{
				$temp .= '<div class="fiche"><img src="';
				$tab2 = explode(">",$tab[$i]);
				$temp .= $tab2[0].'" />';
				
				if(ereg("titre=<",$tab2[1]))
					{
					$hop = explode("titre=<",$tab2[1]);
					$temp .= '<h4>';
					$tab3 = explode(">",$hop[1]);
					$temp .= $tab3[0].'</h4>';
					}

				$tab3 = explode(">]",$tab[$i]);
				$machin = "";
				$j=1;
				for($j;$j<count($tab3)-1;$j++) $machin .= $tab3[$j].">]";
				$machin .= $tab3[$j];
				$tab4 = explode("[/fiche]",$machin);

				$temp .= '<div class="texte">'.str_replace('
','<br />',$tab4[0]).'</div></div>'.$tab4[1];
				
				}
				
			$contenu = $temp;
			}
			
		if(ereg("\[lien url=<",$contenu) && ereg("\[/lien\]",$contenu))
			{
			$contenu = str_replace("[lien url=<", "<a href=\"",$contenu);
			$contenu = str_replace(">]", "\">",$contenu);
			$contenu = str_replace("[/lien]", "</a>",$contenu);
			}
		
		if(ereg("[LIMITE-INTRODUCTION]",$contenu))
			{
			list($intro,$article) = explode("[LIMITE-INTRODUCTION]", $contenu);
			}
		else
			{
			$intro = $contenu;
			$article = "";
			}
			
		if(ereg("\[quote\]",$article) && ereg("\[/quote\]",$article))
				{
				$article = str_replace("[quote]", "<div class=\"text-quote\">",$article);
				$article = str_replace("[/quote]", "<!--quote--></div>",$article);
				}
		if(ereg("\[img url=<",$article) && ereg("> /\]",$article))
			{
			$tmp = explode("[img url=<",$article);
			$temp3 = $tmp[0];
			for($k=1;$k<count($tmp);$k++)
				{
				$tmp2 = explode("> /]",$tmp[$k]);
				if(ereg("\.jpg",$tmp2[0]) OR ereg("\.bmp",$tmp2[0]) OR ereg("\.jpeg",$tmp2[0]) OR ereg("\.png",$tmp2[0]) OR ereg("\.gif",$tmp2[0]))
					{
					$temp3 .= '[img url=<';
					$temp3 .= $tmp2[0];
					$temp3 .= '> /]'.$tmp2[1];
					}
				else 
					{
					$temp3 .= "Format d'image incorrect<br />".$tmp2[1];
					}
				}
				print('<div style="display:none;">'.$temp3.'</div>');
			$article = $temp3;
			$article = str_replace("[img url=<", "<img src=\"",$article);
			$article = str_replace("> /]", "\" />",$article);
			}
		}
			
		$intro = '<div class="wiki_p">'.$intro.'</div>';
		
		$nb1 = count(explode("[paragraphe]",$article));
		$nb2 = count(explode("[/paragraphe]",$article));
		
		if($nb1 == $nb2)
			{
			$article = str_replace("[paragraphe]", "<div class=\"wiki_p\">",$article);
			$article = str_replace("[/paragraphe]", "</div>",$article);
			}
		
		if(!ereg("\[grand titre\]",$article) AND !ereg("\[petit titre\]",$article))
			{
			$intro .= $article;
			}
		else
			{
			
			$nb1 = count(explode("[grand titre]",$article));
			$nb2 = count(explode("[/grand titre]",$article));
			$nb3 = count(explode("[petit titre]",$article));
			$nb4 = count(explode("[/petit titre]",$article));
			
			if(($nb1 == $nb2) AND ($nb3 == $nb4))
				{
				
				$sommaire = '<div id="sommaire">
					Sommaire
					<ol>
						';
				
				$g = explode("[grand titre]",trim($article));
				$artemp = $g[0];

				for($i=1 ; $i<count($g) ; $i++)
					{
					$temp = explode("[/grand titre]",$g[$i]);
					$sommaire .= '<li><a href="#g'.$i.'">'.$temp[0].'</a>';
					$artemp .= '<h3 id="g'.$i.'">'.$g[$i];
					
					$p = explode("[petit titre]",$artemp);
					
					if(count($p)!=1)
						{
						$artemp2 = $p[0];
						$sommaire .= "<ul>
						";
						
						for($j=1 ; $j<count($p) ; $j++)
							{
							$temp2 = explode("[/petit titre]",$p[$j]);
							$sommaire .= '<li><a href="#p'.$i.$j.'">'.$temp2[0].'</a></li>
							';
							
							$artemp2 .= '<h4 id="p'.$i.$j.'">'.$p[$j];
							}
							
						$artemp = $artemp2;
						$sommaire .= "</ul>
						";
						}
					
					$sommaire .= "</li>
						";
					}
				$article = $artemp;
					
				$article = str_replace("[/grand titre]", "</h3>",$article);
			
				$article = str_replace("[/petit titre]", "</h4>",$article);
				}
				
				$sommaire .= '
					</ol>
				</div>';
			
			}
			
			$titre = stripslashes($titre);
			$sommaire = stripslashes($sommaire);
			$intro = stripslashes(stripslashes(stripslashes(str_replace("&lt;br /&gt;", "",$intro))));
			$article = stripslashes(stripslashes(stripslashes(str_replace("&lt;br /&gt;", "",$article))));

	mysql_close($db);
	
	}

include('include/inc_head.php'); ?>

		<div id="page2">
			
			<?php include('include/inc_barre1.php'); ?>
		
			<a href="forum=accueil.php" id="lien-forum"></a>
			<a href="wiki=accueil.php" id="lien-wiki"></a>
			<?php if($_SESSION['id']!="") print('<a href="edc.php" id="lien-edc"></a>'); ?>
			
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
			
			<div id="forum-entete">
				
				<?php include('include/inc_barreliens1.php'); ?>
				
				<div id="forum-info2">
					<p class="gauche"><br /><br /><br /><?php if(statut($_SESSION['statut'])>=2) { print('<a href="wiki=ecrire.php">Ecrire un article</a>'); } ?></p>
				</div>
			</div>
			
			<div id="mainpage-forum">
				<div id="haut">
					<p class="titre"><?php print($titre); ?></p>
				</div>
					
				<div id="contenu">
					<div class="infos-article">
						<p class="situation">Cat&eacute;gorie <?php print($categorie); ?></p>
						<p class="infos">Derni&egrave;re modification le <?php print(date('d/m/Y',$moment).' &agrave '.date('H:i',$moment)); ?></p>
					</div>
					
					<div class="wiki_p">
						<?php
						
						if($messerreur=="")
						print('<form action="wiki=valider-ok.php" method="post" name="poster">
							<input name="submit" type="submit" value="Envoyer &agrave; un admin pour validation" class="ok3" />
							<input name="titre" type="hidden" value="'.htmlentities($_POST['titre']).'" />
							<input name="categorie" type="hidden" value="'.htmlentities($_POST['categorie']).'" />
							<input name="introduction" type="hidden" value="'.htmlentities($_POST['introduction']).'" />
							<input name="article" type="hidden" value="'.htmlentities($_POST['article']).'" />
							<input name="commentaire" type="hidden" value="'.htmlentities($_POST['commentaire']).'" />
							<input name="mots" type="hidden" value="'.htmlentities($_POST['mots']).'" />
						</form>');
						else print($messerreur);
						
						print('
						<form action="');if($_POST['id']=="") print('wiki=ecrire.php'); else print('wiki=modifier.php?id='.$_POST['id']); print('" method="post" name="poster">
							<input name="submit" type="submit" value="Retour" class="ok3" />
							<input name="titre" type="hidden" value="'.htmlentities($_POST['titre']).'" />
							<input name="categorie" type="hidden" value="'.htmlentities($_POST['categorie']).'" />
							<input name="introduction" type="hidden" value="'.htmlentities($_POST['introduction']).'" />
							<input name="article" type="hidden" value="'.htmlentities($_POST['article']).'" />
							<input name="commentaire" type="hidden" value="'.htmlentities($_POST['commentaire']).'" />
							<input name="mots" type="hidden" value="'.htmlentities($_POST['mots']).'" />
						</form>');
						
						?>
					</div>
					
					<?php
					
					print($intro);
					if(ereg("<h3",$article) OR ereg("<h4",$article)) 
						{
						if(ereg("<h3",$article)) print($sommaire.$article);
						else print($article);
						}
					
					?>
				</div>
			</div>
			
			<div id="wiki">
				<div id="menus">
				</div>
				<!-- PARTIE DU BAS : WIKI -->
				
				<?php include('include/inc_wikiderniers.php') ?>
				
				<?php include('include/inc_searcharticle.php'); ?>
				
				<div id="edc-random">
					<!-- AFFICHAGE D'UNE FICHE ALEATOIRE -->
					<?php include('include/inc_randomedc.php'); ?>
				</div>
				
				<div id="edc-monespace">
					<!-- ACCES A MON ESPACE PERSO -->
					<?php
					if(empty($_SESSION['id']))
						{
						print('<div id="lien-EDC2">
							<p>Connectez-vous pour acc&eacute;der &agrave; votre EDC</p>
						</div>');
						}
					else
						{
						print('<a href="edc.php" id="lien-EDC">
							<p>Acc&eacute;der &agrave; mon espace DC</p>
						</a>');
						}
					?>
				</div>
				
				<?php include('include/inc_searchedc.php'); ?>
			</div>	
		</div>
	
	</body>
	
</html>
