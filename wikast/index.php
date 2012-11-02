<?php 
session_start();


$db = mysql_connect('localhost', 'CENSURE', 'CENSURE') or die("Erreur de connexion au serveur des messages.");
mysql_select_db('CENSURE',$db) or die("Erreur de connexion a la base de donnees des messages.");
	
	
if(empty($_SESSION['id'])) $_SESSION['statut'] = "visiteur";
else
	{
	$sql = 'SELECT id FROM wikast_joueurs_tbl WHERE pseudo="'.$_SESSION['pseudo'].'"' ;
	$req = mysql_query($sql);
	$res = mysql_num_rows($req);
	
	if($res == 0)
		{
		$sql = 'INSERT INTO wikast_joueurs_tbl(id,pseudo,infoperso,sujets_vu,commentaire,edc_vu) VALUES("","'.$_SESSION['pseudo'].'","-","-","","")' ;
		mysql_query($sql);
		}
	}
	
$contenu_sondages = '<p class="texte"><br />Il n\'y a actuellement aucun nouveau sondage officiel.</p>
                    <p class="sondages"><a href="sondages=accueil.php"> Voir tous</a></p>';

if($_GET['type']=="" OR $_GET['id']=="")
	{
	$sql ='SELECT * FROM wikast_sondages_tbl WHERE auteur="Administrateur" AND moment > '.(time()-(15*24*3600)).' AND electeurs NOT LIKE "%'.$_SESSION['pseudo'].'%"';
	$req = mysql_query($sql);
	$res = mysql_num_rows($req);
	if($res==0)
	    {
	    $sql ='SELECT * FROM wikast_sondages_tbl WHERE auteur="Administrateur" AND moment > '.(time()-(15*24*3600)).' AND electeurs LIKE "%'.$_SESSION['pseudo'].'%"';
	    $req = mysql_query($sql);
    	$res = mysql_num_rows($req);
	    }
	}
else
	{
	$sql ='SELECT * FROM wikast_sondages_tbl WHERE auteur="Administrateur" AND moment > '.(time()-(15*24*3600)).' AND id="'.$_GET['id'].'"';
	$req = mysql_query($sql);
	$res = mysql_num_rows($req);
	}

if($res!=0)
	{
	$random = rand(0,$res-1);
	$id = mysql_result($req,$random,id);
	$titre = mysql_result($req,$random,titre);
	$explication = mysql_result($req,$random,explication);
	$moment = mysql_result($req,$random,moment);
	$type = mysql_result($req,$random,type);
	$p1 = mysql_result($req,$random,p1);
	$p2 = mysql_result($req,$random,p2);
	$p3 = mysql_result($req,$random,p3);
	$p4 = mysql_result($req,$random,p4);
	$p5 = mysql_result($req,$random,p5);
	$p6 = mysql_result($req,$random,p6);
	$v1 = mysql_result($req,$random,v1);
	$v2 = mysql_result($req,$random,v2);
	$v3 = (mysql_result($req,$random,v3)==-1)?"":mysql_result($req,$random,v3);
	$v4 = (mysql_result($req,$random,v4)==-1)?"":mysql_result($req,$random,v4);
	$v5 = (mysql_result($req,$random,v5)==-1)?"":mysql_result($req,$random,v5);
	$v6 = (mysql_result($req,$random,v6)==-1)?"":mysql_result($req,$random,v6);
	$total = $v1+$v2+$v3+$v4+$v5+$v6;
	$electeurs = mysql_result($req,$random,electeurs);
	
	$v1 = ($total==0)?0:round($v1*100/$total);
	$v2 = ($total==0)?0:round($v2*100/$total);
	$v3 = ($total==0)?0:round($v3*100/$total);
	$v4 = ($total==0)?0:round($v4*100/$total);
	$v5 = ($total==0)?0:round($v5*100/$total);
	$v6 = ($total==0)?0:round($v6*100/$total);
	
	
	if($_GET['type']=="resultats" OR $_SESSION['pseudo'] == "" OR ereg('-'.$_SESSION['pseudo'].'-',$electeurs))
		{
		$contenu_sondages = '<p class="texte">'.$titre; if($explication != "") $contenu_sondages .= ' - <a href="sondages.php?id='.$id.'">Explication</a>'; $contenu_sondages .= '<br /><br />
		'.$p1.' : <span style="color:#888;">'.$v1.'%</span><br />
		'.$p2.' : <span style="color:#888;">'.$v2.'%</span>';
		if($p3 != "-")
			{
			$contenu_sondages .= '<br />
			'.$p3.' : <span style="color:#888;">'.$v3.'%</span>';
			}
		if($p4 != "-")
			{
			$contenu_sondages .= '<br />
			'.$p4.' : <span style="color:#888;">'.$v4.'%</span>';
			}
		if($p5 != "-")
			{
			$contenu_sondages .= '<br />
			'.$p5.' : <span style="color:#888;">'.$v5.'%</span>';
			}
		if($p6 != "-")
			{
			$contenu_sondages .= '<br />
			'.$p6.' : <span style="color:#888;">'.$v6.'%</span>';
			}
			
		$contenu_sondages .= '</p>
		<p class="sondages"><a href="sondages=accueil.php"> Voir tous</a></p>';
		}
	else
		{
		if($type == 0)
			{
			$contenu_sondages = '<form action="sondages=vote.php" method="POST" class="texte"><input type="hidden" name="id" value="'.$id.'" />'.$titre; if($explication != "") $contenu_sondages .= ' - <a href="sondages.php?id='.$id.'">Explication</a>'; $contenu_sondages .= '<br />
			<input class="radio" type="radio" name="'.$id.'" value="p1"/> '.$p1.'<br />
			<input class="radio" type="radio" name="'.$id.'" value="p2"/> '.$p2;
			
			if($p3 != "-")
				{
				$contenu_sondages .= '<br />
				<input class="radio" type="radio" name="'.$id.'" value="p3"/> '.$p3;
				}
			if($p4 != "-")
				{
				$contenu_sondages .= '<br />
				<input class="radio" type="radio" name="'.$id.'" value="p4"/> '.$p4;
				}
			if($p5 != "-")
				{
				$contenu_sondages .= '<br />
				<input class="radio" type="radio" name="'.$id.'" value="p5"/> '.$p5;
				}
			if($p6 != "-")
				{
				$contenu_sondages .= '<br />
				<input class="radio" type="radio" name="'.$id.'" value="p6"/> '.$p6;
				}
			
			$contenu_sondages .= '<br />
			<input name="submit" type="submit" value="Voter" class="ok" /><a style="position:relative;left:0;top:4px;" href="index.php?type=resultats&id='.$id.'"> - R&eacute;sultats</a>
			</form>
			<p class="sondages"><a href="sondages=accueil.php"> Voir tous</a></p>';
			}
		else
			{
			$contenu_sondages = '<form action="sondages=vote.php" method="POST" class="texte"><input type="hidden" name="id" value="'.$id.'" />'.$titre; if($explication != "") $contenu_sondages .= ' - <a href="sondages.php?id='.$id.'">Explication</a>'; $contenu_sondages .= '<br />
			<input class="checkbox" type="checkbox" name="p1"/> '.$p1.'<br />
			<input class="checkbox" type="checkbox" name="p2"/> '.$p2;
			
			if($p3 != "-")
				{
				$contenu_sondages .= '<br />
				<input class="checkbox" type="checkbox" name="p3"/> '.$p3;
				}
			if($p4 != "-")
				{
				$contenu_sondages .= '<br />
				<input class="checkbox" type="checkbox" name="p4"/> '.$p4;
				}
			if($p5 != "-")
				{
				$contenu_sondages .= '<br />
				<input class="checkbox" type="checkbox" name="p5"/> '.$p5;
				}
			if($p6 != "-")
				{
				$contenu_sondages .= '<br />
				<input class="checkbox" type="checkbox" name="p6"/> '.$p6;
				}
			
			$contenu_sondages .= '<br />
			<input name="submit" type="submit" value="Voter" class="ok" /><a style="position:relative;left:0;top:4px;" href="index.php?type=resultats&id='.$id.'"> - R&eacute;sultats</a>
			</form>
			<p class="sondages"><a href="sondages=accueil.php"> Voir tous</a></p>';
			}
		}
	}

mysql_close($db);

include('include/inc_head.php'); ?>

		<div id="page">
			
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
			
			<div id="logo">
				<!-- PARTIE DU MILIEU : LOGO WiKast -->
				<div id="logo-news">
					<!-- ACCES AUX NEWS -->
					<p class="titre">Navigation</p>
					<p class="texte">Bienvenue sur le WiKast, l'interface communautaire du jeu !<br />
					Ici, vous trouverez :<br /> - <a href="http://v2.dreadcast.net/wikast/forum=accueil.php">Le forum officiel</a><br /> - <a href="http://v2.dreadcast.net/wikast/wiki=accueil.php">Le Wiki</a><br /> - <a href="http://v2.dreadcast.net/wikast/edc.php">Les EDC</a><br /> - <a href="http://v2.dreadcast.net/wikast/sondages=accueil.php">Les sondages</a></p>
				</div>
				<div id="logo-retour">
					<!-- BOUTON RETOUR VERS LE SITE -->
				</div>
				<div id="logo-sondages">
					<!-- ACCES AUX SONDAGES -->
					<p class="titre">Sondage</p>
					<?php print($contenu_sondages); ?>
				</div>
			</div>
			
			<div id="wiki">
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
