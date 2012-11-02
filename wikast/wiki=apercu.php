<?php 
session_start();

if($_SESSION['statut'] != "Administrateur")
	{
	print('<meta http-equiv="refresh" content="0 ; url=wiki=accueil.php">');
	exit();
	}
else
	{
	
	$db = mysql_connect('localhost', 'CENSURE', 'CENSURE') or die("Erreur de connexion au serveur des messages.");
	mysql_select_db('CENSURE',$db) or die("Erreur de connexion a la base de donnees des messages.");
	
	$sql = 'SELECT * FROM wikast_wiki_articles_tbl WHERE id="'.$_GET['article'].'"' ;
	$req = mysql_query($sql);
	$res = mysql_num_rows($req);
	
	if($res>0)
		{
		$titre = mysql_result($req,0,titre);
		$createur = mysql_result($req,0,createur);
		$moment = mysql_result($req,0,moment);
		$categorie = mysql_result($req,0,categorie);
		$contenu =mysql_result($req,0,contenu);
		
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
		if(ereg("\[lien url=&lt;",$contenu) && ereg("\[/lien\]",$contenu))
			{
			$contenu = str_replace("[lien url=&lt;", "<a href=\"",$contenu);
			$contenu = str_replace("&gt;]", "\">",$contenu);
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
		
		if(ereg("Veuillez &eacute;crire entre les balises \[paragraphe\]\[/paragraphe\]",$article)) $article = "";
		
		if(ereg("\[quote\]",$article) && ereg("\[/quote\]",$article))
			{
			$article = str_replace("[quote]", "<div class=\"text-quote\">",$article);
			$article = str_replace("[/quote]", "<!--quote--></div>",$article);
			}
		if(ereg("\[img url=&lt;",$article) && ereg("&gt; /\]",$article))
			{
			$tmp = explode("[img url=&lt;",$article);
			$temp3 = $tmp[0];
			for($k=1;$k<count($tmp);$k++)
				{
				$tmp2 = explode("&gt; /]",$tmp[$k]);
				if(ereg("\.jpg",$tmp2[0]) OR ereg("\.bmp",$tmp2[0]) OR ereg("\.jpeg",$tmp2[0]) OR ereg("\.png",$tmp2[0]) OR ereg("\.gif",$tmp2[0]))
					{
					$temp3 .= '[img url=&lt;';
					$temp3 .= $tmp2[0];
					$temp3 .= '&gt; /]'.$tmp2[1];
					}
				else 
					{
					$temp3 .= "Format d'image incorrect<br />".$tmp2[1];
					}
				}
				print('<div style="display:none;">'.$temp3.'</div>');
			$article = $temp3;
			$article = str_replace("[img url=&lt;", "<img src=\"",$article);
			$article = str_replace("&gt; /]", "\" />",$article);
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
			
			$intro = stripslashes(stripslashes(stripslashes(str_replace("&lt;br /&gt;", "",$intro))));
			$article = stripslashes(stripslashes(stripslashes(str_replace("&lt;br /&gt;", "",$article))));
		}
	else
		{
		print('<meta http-equiv="refresh" content="0 ; url=wiki=accueil.php">');
		exit();
		}

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
				
				<div id="wiki-recherche">
					<!-- MOTEUR DE RECHERCHE D'ARTICLES -->
					<form method="post" action="wiki=recherche.php?article" id="">
						Rechercher <input type="text" name="recherche" class="champ" /> <input type="submit" value="" class="valid" />
					</form>
				</div>
				
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
				
				<div id="edc-recherche">
					<!-- MOTEUR DE RECHERCHE DE JOUEURS -->
					<form method="post" action="wiki=recherche.php?joueur" id="">
						Rechercher <input type="text" name="recherche" class="champ" /> <input type="submit" value="" class="valid" />
					</form>
				</div>
			</div>	
		</div>
	
	</body>
	
</html>
