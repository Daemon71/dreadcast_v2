<?php 
session_start();

if(empty($_SESSION['id'])) $_SESSION['statut'] = "visiteur";

if($_POST['recherche']=="")
	{
	$titre = "Recherche d'article Wiki";
	
	$resultat = '<br /><h4>Titre du sujet</h4>
	<form action="#" method="post" class="wiki_p">
	<input type="text" class="titre" name="recherche"/>
	<input name="submit" type="submit" value="Rechercher" class="ok2" />
	</form>';
	}
else
	{
	$recherche = $_POST['recherche'];
	
	if($recherche[2]=="")
		{
		$titre = "Recherche d'article Wiki";
		$resultat = '<br /><h4>Titre du sujet</h4>
		<form action="#" method="post" class="wiki_p">
		Le mot que vous avez sp&eacute;cifi&eacute; est trop court.<br /><br />
		<input type="text" class="titre" name="recherche"/>
		<input name="submit" type="submit" value="Rechercher" class="ok2" />
		</form>';
		}
	else
		{
		$titre = "Recherche pour \"".$recherche."\"";
		$resultat = "<br /><h4>R&eacute;sultat de la recherche</h4>
		<div class=\"wiki_p\">";
		$dejafait = "-";
		$cle = "";
		
		$db = mysql_connect('localhost', 'CENSURE', 'CENSURE') or die("Erreur de connexion au serveur des messages.");
		mysql_select_db('CENSURE',$db) or die("Erreur de connexion a la base de donnees des messages.");

		$sql1 = 'SELECT id,titre FROM wikast_wiki_articles_tbl WHERE titre LIKE "%'.htmlentities($recherche).'%" AND etat = 2 ORDER BY moment DESC';
		$req1 = mysql_query($sql1);
		$res1 = mysql_num_rows($req1);
		
		if($res1!=0)
			{
			$resultat .= 'Resultat'; if($res1 != 1) $resultat .= 's'; $resultat .= ' tr&egrave;s pertinent'; if($res1 != 1) $resultat .= 's'; $resultat .= '<br /><br />';
			for($i=0;$i<$res1;$i++)
				{
				if(!ereg('-'.mysql_result($req1,$i,id).'-',$dejafait))
					{
					$resultat .= '<a href="wiki.php?id='.mysql_result($req1,$i,id).'" style="position:relative;left:30px;">'.mysql_result($req1,$i,titre).'</a><br />';
					$dejafait .= mysql_result($req1,$i,id).'-';
					}
				}
			$resultat .= '<br />';
			}
		
		$tab = explode(" ",$recherche);
		
		$temp4 = '(mots LIKE "'.htmlentities($tab[0]).'" OR mots LIKE "'.htmlentities($tab[0]).',%" OR mots LIKE "'.htmlentities($tab[0]).' ,%" OR mots LIKE "%,'.htmlentities($tab[0]).'" OR mots LIKE "%, '.htmlentities($tab[0]).'" OR mots LIKE "%, '.htmlentities($tab[0]).',%" OR mots LIKE "%,'.htmlentities($tab[0]).' ,%" OR mots LIKE "%, '.htmlentities($tab[0]).' ,%")';
		$temp5 = '(mots LIKE "'.htmlentities($tab[0]).'" OR mots LIKE "'.htmlentities($tab[0]).',%" OR mots LIKE "'.htmlentities($tab[0]).' ,%" OR mots LIKE "%,'.htmlentities($tab[0]).'" OR mots LIKE "%, '.htmlentities($tab[0]).'" OR mots LIKE "%, '.htmlentities($tab[0]).',%" OR mots LIKE "%,'.htmlentities($tab[0]).' ,%" OR mots LIKE "%, '.htmlentities($tab[0]).' ,%")';
		
		for($i=1;$i<count($tab);$i++)
		    {
		    $temp4 .= ' AND (mots LIKE "'.htmlentities($tab[$i]).'" OR mots LIKE "'.htmlentities($tab[$i]).',%" OR mots LIKE "'.htmlentities($tab[$i]).' ,%" OR mots LIKE "%,'.htmlentities($tab[$i]).'" OR mots LIKE "%, '.htmlentities($tab[$i]).'" OR mots LIKE "%, '.htmlentities($tab[$i]).',%" OR mots LIKE "%,'.htmlentities($tab[$i]).' ,%" OR mots LIKE "%, '.htmlentities($tab[$i]).' ,%")';
		    $temp5 .= ' OR (mots LIKE "'.htmlentities($tab[$i]).'" OR mots LIKE "'.htmlentities($tab[$i]).',%" OR mots LIKE "'.htmlentities($tab[$i]).' ,%" OR mots LIKE "%,'.htmlentities($tab[$i]).'" OR mots LIKE "%, '.htmlentities($tab[$i]).'" OR mots LIKE "%, '.htmlentities($tab[$i]).',%" OR mots LIKE "%,'.htmlentities($tab[$i]).' ,%" OR mots LIKE "%, '.htmlentities($tab[$i]).' ,%")';
		    }
		
		$sql4 = 'SELECT id,titre FROM wikast_wiki_articles_tbl WHERE '.$temp4.' AND etat = 2 ORDER BY moment DESC';
		$req4 = mysql_query($sql4);
    	$res4 = mysql_num_rows($req4);
    	
    	$sql5 = 'SELECT id,titre FROM wikast_wiki_articles_tbl WHERE ('.$temp5.') AND etat = 2 ORDER BY moment DESC';
		$req5 = mysql_query($sql5);
    	$res5 = mysql_num_rows($req5);
		
		$temp4 = "";
		$temp5 = "";
		
		for($i=0;$i<$res4;$i++)
			{
			if(!ereg('-'.mysql_result($req4,$i,id).'-',$dejafait))
				{
				$temp4 .= '<a href="wiki.php?id='.mysql_result($req4,$i,id).'" style="position:relative;left:30px;">'.mysql_result($req4,$i,titre).'</a><br />';
				$dejafait .= mysql_result($req4,$i,id).'-';
				}
			}
			
		for($i=0;$i<$res5;$i++)
			{
			if(!ereg('-'.mysql_result($req5,$i,id).'-',$dejafait))
				{
				$temp5 .= '<a href="wiki.php?id='.mysql_result($req5,$i,id).'" style="position:relative;left:50px;">'.mysql_result($req5,$i,titre).'</a><br />';
				$dejafait .= mysql_result($req5,$i,id).'-';
				}
			}
		
		if(count($tab) != 1)
			{
			
			$temp2 = 'titre LIKE "%'.htmlentities($tab[0]).'%"';
			$temp3 = 'titre LIKE "%'.htmlentities($tab[0]).'%"';
			
			for($i=1;$i<count($tab);$i++)
				{
				$temp2 .= ' AND titre LIKE "%'.htmlentities($tab[$i]).'%"';
				$temp3 .= ' OR titre LIKE "%'.htmlentities($tab[$i]).'%"';
				}
			
			$sql2 = 'SELECT id,titre FROM wikast_wiki_articles_tbl WHERE '.$temp2.' AND etat = 2 ORDER BY moment DESC';
			$req2 = mysql_query($sql2);
			$res2 = mysql_num_rows($req2);
			
			$sql3 = 'SELECT id,titre FROM wikast_wiki_articles_tbl WHERE ('.$temp3.') AND etat = 2 ORDER BY moment DESC';
			$req3 = mysql_query($sql3);
			$res3 = mysql_num_rows($req3);
			
			$temp2 = "";
			$temp3 = "";
			
			for($i=0;$i<$res2;$i++)
				{
				if(!ereg('-'.mysql_result($req2,$i,id).'-',$dejafait))
					{
					$temp2 .= '<a href="wiki.php?id='.mysql_result($req2,$i,id).'" style="position:relative;left:30px;">'.mysql_result($req2,$i,titre).'</a><br />';
					$dejafait .= mysql_result($req2,$i,id).'-';
					}
				}
				
			if($temp2 != "" AND $res2 != 0)
				{
				$resultat .= 'Resultat'; if($res2 != 1) $resultat .= 's'; $resultat .= ' pertinent'; if($res2 != 1) $resultat .= 's'; $resultat .= '<br /><br />'.$temp2.'<br />';
				}
			
			for($i=0;$i<$res3;$i++)
				{
				if(!ereg('-'.mysql_result($req3,$i,id).'-',$dejafait))
					{
					$temp3 .= '<a href="wiki.php?id='.mysql_result($req3,$i,id).'" style="position:relative;left:30px;">'.mysql_result($req3,$i,titre).'</a><br />';
					$dejafait .= mysql_result($req3,$i,id).'-';
					}
				}
				
			
			if($temp3 != "" AND $res3 != 0)
				{
				$resultat .= 'Resultat'; if($res3 != 1) $resultat .= 's'; $resultat .= ' peu pertinent'; if($res3 != 1) $resultat .= 's'; $resultat .= '<br /><br />'.$temp3.'<br />';
				}
			
			}
		
		if($temp4!="" OR $temp5!="") $resultat .= 'Article(s) r&eacute;f&eacute;rent(s)<br /><br />'.$temp4.$temp5.'<br />';
		
		if($res1==0 AND $res2==0 AND $res3==0) $resultat .= 'Il n\'y a aucun r&eacute;sultat pour cette recherche.';
		
		$resultat .= '</div>';
		mysql_close($db);
		}
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
					<p class="gauche"><br /><br /><br /><?php if(statut($_SESSION['statut'])>=2) { print('<a href="wiki=accueil.php">Retour &agrave; l\'accueil</a>'); } ?></p>
				</div>
			</div>
			
			<div id="mainpage-forum">
				<div id="haut">
					<p class="titre"><?php print($titre); ?></p>
				</div>
					
				<div id="contenu">
					<?php print($resultat); ?>
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
