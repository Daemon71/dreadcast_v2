<?php 
session_start();

$db = mysql_connect('localhost', 'CENSURE', 'CENSURE') or die("Erreur de connexion au serveur des messages.");
mysql_select_db('CENSURE',$db) or die("Erreur de connexion a la base de donnees des messages.");
	
$id=$_GET['id'];

if(empty($_SESSION['id'])) $_SESSION['statut'] = "visiteur";
else
	{
	
	$sql = 'SELECT id FROM wikast_joueurs_tbl WHERE pseudo="'.$_SESSION['pseudo'].'"' ;
	$req = mysql_query($sql);
	$res = mysql_num_rows($req);
	
	if($res == 0)
		{
		$sql = 'INSERT INTO wikast_joueurs_tbl(id,pseudo,infoperso,sujets_vu,commentaire) VALUES("","'.$_SESSION['pseudo'].'","-","-","")' ;
		mysql_query($sql);
		}
	}
	
	$sql = 'SELECT titre FROM wikast_wiki_articles_tbl WHERE id="'.$_GET['id'].'"' ;
	$req = mysql_query($sql);
	$res = mysql_num_rows($req);
	
	if($res>0)
		{
		$sql = 'SELECT * FROM wikast_wiki_articles_tbl WHERE titre="'.mysql_result($req,0,titre).'" AND etat = 2' ;
		$req = mysql_query($sql);
		$res = mysql_num_rows($req);
		}
	
	if($res>0)
		{
		$id = mysql_result($req,0,id);
		$titre = mysql_result($req,0,titre);
		$createur = mysql_result($req,0,createur);
		$moment = mysql_result($req,0,moment);
		$categorie = mysql_result($req,0,categorie);
		$contenu = mysql_result($req,0,contenu);
		
		if($categorie == "Histoire de la ville") $categoid = "1";
		elseif($categorie == "Les commerces") $categoid = "2";
		elseif($categorie == "Politique de Dreadcast") $categoid = "3";
		elseif($categorie == "Trucs & astuces") $categoid = "4";
		elseif($categorie == "Informations utiles") $categoid = "5";
		
		if(preg_match("#\[g\]#",$contenu) && preg_match("#\[/g\]#",$contenu))
			{
			$contenu = str_replace("[g]", "<strong>#",$contenu);
			$contenu = str_replace("[/g]", "</strong>#",$contenu);
			}
		if(preg_match("#\[i\]#",$contenu) && preg_match("#\[/i\]#",$contenu))
			{
			$contenu = str_replace("[i]", "<em>#",$contenu);
			$contenu = str_replace("[/i]", "</em>#",$contenu);
			}
		if(preg_match("#\[fiche image=&lt;#",$contenu) && preg_match("#\[/fiche\]#",$contenu))
			{
			
			$tab = explode("[fiche image=&lt;#",$contenu);
			$temp = $tab[0];
		
			$tailletab = count($tab);
			
			for($i=1;$i<$tailletab;$i++)
				{
				$temp .= '<div class="fiche"><img src="';
				$tab2 = explode("&gt;",$tab[$i]);
				$temp .= $tab2[0].'" />';
				
				if(preg_match("#titre=&lt;#",$tab2[1]))
					{
					$hop = explode("titre=&lt;",$tab2[1]);
					$temp .= '<h4>';
					$tab3 = explode("&gt;",$hop[1]);
					$temp .= $tab3[0].'</h4>';
					}

				$tab3 = explode("&gt;]",$tab[$i]);
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
			
		if(preg_match("#\[lien url=&lt;#",$contenu) && preg_match("#\[/lien\]#",$contenu))
			{
			$contenu = str_replace("[lien url=&lt;", "<a href=\"#",$contenu);
			$contenu = str_replace("[lien url=<", "<a href=\"#",$contenu);
			$contenu = str_replace("&gt;]", "\">#",$contenu);
			$contenu = str_replace(">]", "\">#",$contenu);
			$contenu = str_replace("[/lien]", "</a>#",$contenu);
			}
		
		if(preg_match("#[LIMITE-INTRODUCTION]#",$contenu))
			{
			list($intro,$article) = explode("[LIMITE-INTRODUCTION]", $contenu);
			}
		else
			{
			$intro = $contenu;
			$article = "";
			}
			
		if(preg_match("#\[quote\]#",$article) && preg_match("#\[/quote\]#",$article))
			{
			$article = str_replace("[quote]", "<div class=\"text-quote\">",$article);
			$article = str_replace("[/quote]", "<!--quote--></div>",$article);
			}
		if(preg_match("#\[img url=&lt;#",$article) && preg_match("#&gt; /\]#",$article))
			{
			$tmp = explode("[img url=&lt;",$article);
			$temp3 = $tmp[0];
			for($k=1;$k<count($tmp);$k++)
				{
				$tmp2 = explode("&gt; /]",$tmp[$k]);
				if(preg_match("#\.jpg#",$tmp2[0]) OR preg_match("#\.bmp#",$tmp2[0]) OR preg_match("#\.jpeg#",$tmp2[0]) OR preg_match("#\.png#",$tmp2[0]) OR preg_match("#\.gif#",$tmp2[0]))
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
		
		if(!preg_match("#\[grand titre\]#",$article) AND !preg_match("#\[petit titre\]#",$article))
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
			
			$intro = str_replace("&lt;br /&gt;", "",$intro);
			$article = str_replace("&lt;br /&gt;", "",$article);
		}
	else
		{
		print('<meta http-equiv="refresh" content="0 ; url=wiki=accueil.php">');
		exit();
		}
	
mysql_close($db);

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
					<p class="gauche"><br /><br /><?php if(statut($_SESSION['statut'])>=2) { print('<a href="wiki=accueil.php">Retour &agrave; l\'accueil</a><br /><a href="wiki=ecrire.php">Ecrire un article</a>'); } else print('<br /><a href="wiki=accueil.php">Retour &agrave; l\'accueil</a>');?></p>
				</div>
			</div>
			
			<div id="mainpage-forum">
				<div id="haut">
					<p class="titre"><?php print($titre); ?></p>
				</div>
					
				<div id="contenu">
					<div class="infos-article">
						<p class="situation"><a href="wiki=accueil.php?id=<?php print($categoid); ?>">Cat&eacute;gorie <?php print($categorie); ?></a></p>
						<p class="infos">Derni&egrave;re modification le <?php print(date('d/m/Y',$moment).' &agrave '.date('H:i',$moment)); ?></p>
					</div>
					<?php
					
					print($intro);
					if(preg_match("#<h3#",$article) OR preg_match("#<h4#",$article)) 
						{
						if(preg_match("#<h3#",$article)) print($sommaire.$article);
						else print($article);
						}
					
					print('<div id="baswiki">');
					
					if(statut($_SESSION['statut'])>=2)
					{
					print('Des pr&eacute;cisions &agrave; apporter ? Des erreurs &agrave; corriger ? N\'h&eacute;sitez pas &agrave; <a href="wiki=modifier.php?id='.$id.'">modifier cet article</a>');
					if($_SESSION['statut']=="Administrateur") print(' | <a href="../ingame/engine=wikiaffiche.php?article='.$_GET['id'].'" onclick="window.open(this.href); return false;">Voir toutes les versions de l\'article</a>');
					print('<br />');
					}
					
					print('R&eacute;dacteur(s) :<br />');
					
					$db = mysql_connect('localhost', 'CENSURE', 'CENSURE') or die("Erreur de connexion au serveur des messages.");
					mysql_select_db('CENSURE',$db) or die("Erreur de connexion a la base de donnees des messages.");
					
					$sql = 'SELECT DISTINCT createur FROM wikast_wiki_articles_tbl WHERE titre="'.$titre.'"' ;
					$req = mysql_query($sql);
					$res = mysql_num_rows($req);
					
					$i=0;
					for($i;$i<$res-1;$i++)
						{
						print(mysql_result($req,$i,createur).', ');
						}
					if($res>O) print(mysql_result($req,$i,createur));
					
					mysql_close($db);
					
					?>
					</div>
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
