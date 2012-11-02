<?php 
session_start();

if($_GET['id']=="")
	{
	print('<meta http-equiv="refresh" content="0 ; url=sondages=accueil.php"> ');
	exit();
	}
	
$db = mysql_connect('localhost', 'CENSURE', 'CENSURE') or die("Erreur de connexion au serveur des messages.");
mysql_select_db('CENSURE',$db) or die("Erreur de connexion a la base de donnees des messages.");

$sql = 'SELECT * FROM wikast_sondages_tbl WHERE id="'.$_GET['id'].'"';
$req = mysql_query($sql);
$res = mysql_num_rows($req);

$id = $_GET['id'];

if($res!=0)
	{
	$titre = mysql_result($req,0,titre);
	$explication = mysql_result($req,0,explication);
	$auteur = mysql_result($req,0,auteur);
	$moment = mysql_result($req,0,moment);
	$datefin = $moment+(15*24*3600);
	$type = mysql_result($req,0,type);
	$p1 = mysql_result($req,0,p1);
	$p2 = mysql_result($req,0,p2);
	$p3 = mysql_result($req,0,p3);
	$p4 = mysql_result($req,0,p4);
	$p5 = mysql_result($req,0,p5);
	$p6 = mysql_result($req,0,p6);
	$v1n = mysql_result($req,0,v1);
	$v2n = mysql_result($req,0,v2);
	$v3n = (mysql_result($req,0,v3)==-1)?"":mysql_result($req,0,v3);
	$v4n = (mysql_result($req,0,v4)==-1)?"":mysql_result($req,0,v4);
	$v5n = (mysql_result($req,0,v5)==-1)?"":mysql_result($req,0,v5);
	$v6n = (mysql_result($req,0,v6)==-1)?"":mysql_result($req,0,v6);
	$total = $v1n+$v2n+$v3n+$v4n+$v5n+$v6n;
	$electeurs = mysql_result($req,0,electeurs);
	$nbelecteurs = count(explode('-',$electeurs))-2;
	
	if ($type) {
		$v1 = ($nbelecteurs==0)?0:round($v1n*100/$nbelecteurs,2);
		$v2 = ($nbelecteurs==0)?0:round($v2n*100/$nbelecteurs,2);
		$v3 = ($nbelecteurs==0)?0:round($v3n*100/$nbelecteurs,2);
		$v4 = ($nbelecteurs==0)?0:round($v4n*100/$nbelecteurs,2);
		$v5 = ($nbelecteurs==0)?0:round($v5n*100/$nbelecteurs,2);
		$v6 = ($nbelecteurs==0)?0:round($v6n*100/$nbelecteurs,2);
	} else {
		$v1 = ($total==0)?0:round($v1n*100/$total,2);
		$v2 = ($total==0)?0:round($v2n*100/$total,2);
		$v3 = ($total==0)?0:round($v3n*100/$total,2);
		$v4 = ($total==0)?0:round($v4n*100/$total,2);
		$v5 = ($total==0)?0:round($v5n*100/$total,2);
		$v6 = ($total==0)?0:round($v6n*100/$total,2);
	}
	
	$contenu_sondages = $p1.' : '.$v1n.' voix - '.$v1.'%<div style="position:relative;top:4px;height:15px;width:300px;border:1px solid #666;overflow:hidden;"><div style="position:absolute;top:0;left:-'.floor(3*(100-$v1)).'px;height:15px;width:300px;background:#AAA;"></div></div><br />
	'.$p2.' : '.$v2n.' voix - '.$v2.'%<div style="position:relative;top:4px;height:15px;width:300px;border:1px solid #666;overflow:hidden;"><div style="position:absolute;top:0;left:-'.floor(3*(100-$v2)).'px;height:15px;width:300px;background:#AAA;"></div></div>';
	if($p3 != "-")
		{
		$contenu_sondages .= '<br />
		'.$p3.' : '.$v3n.' voix - '.$v3.'%<div style="position:relative;top:4px;height:15px;width:300px;border:1px solid #666;overflow:hidden;"><div style="position:absolute;top:0;left:-'.floor(3*(100-$v3)).'px;height:15px;width:300px;background:#AAA;"></div></div>';
		}
	if($p4 != "-")
		{
		$contenu_sondages .= '<br />
		'.$p4.' : '.$v4n.' voix - '.$v4.'%<div style="position:relative;top:4px;height:15px;width:300px;border:1px solid #666;overflow:hidden;"><div style="position:absolute;top:0;left:-'.floor(3*(100-$v4)).'px;height:15px;width:300px;background:#AAA;"></div></div>';
		}
	if($p5 != "-")
		{
		$contenu_sondages .= '<br />
		'.$p5.' : '.$v5n.' voix - '.$v5.'%<div style="position:relative;top:4px;height:15px;width:300px;border:1px solid #666;overflow:hidden;"><div style="position:absolute;top:0;left:-'.floor(3*(100-$v5)).'px;height:15px;width:300px;background:#AAA;"></div></div>';
		}
	if($p6 != "-")
		{
		$contenu_sondages .= '<br />
		'.$p6.' : '.$v6n.' voix - '.$v6.'%<div style="position:relative;top:4px;height:15px;width:300px;border:1px solid #666;overflow:hidden;"><div style="position:absolute;top:0;left:-'.floor(3*(100-$v6)).'px;height:15px;width:300px;background:#AAA;"></div></div>';
		}
	$contenu_sondages .= '<br />';
	}

mysql_close($db);

include('include/inc_head.php'); ?>

		<div id="page2">
			
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
			
			<div id="forum-entete">
			
			<?php include('include/inc_barreliens1.php'); ?>

				<div id="forum-info2">
					<p class="gauche"><br /><?php if(statut($_SESSION['statut'])>=6) print('<a href="sondages=supprimer.php?id='.$_GET['id'].'">Supprimer</a><br />'); else print('<br />'); if(statut($_SESSION['statut'])>=2) print('<a href="sondages=nouveau.php">Cr&eacute;er un sondage</a>'); ?><br /><a href="sondages=accueil.php">Retour &agrave; l'accueil</a></p>
				</div>
			</div>
			
			<div id="mainpage-forum">
				<div id="haut">
					<a href="sondages=accueil.php" title="Retour" id="btn_retour"></a>
					<p>Sondage<?php if($auteur=="Administrateur") print(' officiel'); elseif($auteur!="") print(' post&eacute; par '.$auteur); ?></p>
				</div>
				
				<div id="contenu">
					<?php
					$time = time();
					$temps = $datefin-$time;
					$limite = ($temps > 0)?'Ce sondage sera clos dans '.floor($temps/(3600*24)).'j '.floor(($temps%(3600*24))/3600).'h '.floor(($temps%(3600))/60).'min':'Ce sondage est termin&eacute;';
					
					print('<div class="titre-sujet">
						<p class="titre">Post&eacute; le '.date('d/m/y',$moment).' &agrave; '.date('H\hi',$moment).'</p>
						<p class="infos">'.$limite.'</p>
					</div>
					<div class="wiki_p"><strong>Intitul&eacute;</strong> : '.nl2br($titre).'<br />
					'); if($explication != "") print('<strong>Explication</strong> : '.nl2br($explication).'<br />'); print('
					<strong>Nombre de votes</strong> : '.(count(explode('-',$electeurs))-2).'</div>
					<div class="text-quote" style="padding:10px 0 10px 100px;">'.$contenu_sondages.'</div>');
					
					if($temps > 0 AND $_SESSION['pseudo']!="" AND !ereg('-'.$_SESSION['pseudo'].'-',$electeurs))
						{
						
						if($type == 0)
			{
			$contenu_sondages = '<form action="sondages=vote.php" method="POST" class="wiki_p" style="padding-left:120px;"><span style="position:relative;left:-100px;">'.$titre.'</span><br />
			';if($explication != "") $contenu_sondages .= '<em style="position:relative;left:-100px;">'.$explication.'</em><br />'; $contenu_sondages .= '
			<input type="hidden" name="id" value="'.$id.'" />
			<input class="radio" type="radio" name="'.$id.'" value="p1" id="p1" /> <label for="p1">'.$p1.'</label><br />
			<input class="radio" type="radio" name="'.$id.'" value="p2" id="p2" /> <label for="p2">'.$p2.'</label>';
			
			if($p3 != "-")
				{
				$contenu_sondages .= '<br />
				<input class="radio" type="radio" name="'.$id.'" value="p3" id="p3" /> <label for="p3">'.$p3.'</label>';
				}
			if($p4 != "-")
				{
				$contenu_sondages .= '<br />
				<input class="radio" type="radio" name="'.$id.'" value="p4" id="p4" /> <label for="p4">'.$p4.'</label>';
				}
			if($p5 != "-")
				{
				$contenu_sondages .= '<br />
				<input class="radio" type="radio" name="'.$id.'" value="p5" id="p5" /> <label for="p5">'.$p5.'</label>';
				}
			if($p6 != "-")
				{
				$contenu_sondages .= '<br />
				<input class="radio" type="radio" name="'.$id.'" value="p6" id="p6" /> <label for="p6">'.$p6.'</label>';
				}
			
			$contenu_sondages .= '<br /><br />
			<input name="submit" type="submit" value="Voter" class="ok" style="height:22px;padding-bottom:2px;" />
			</form>';
			}
		else
			{
			$contenu_sondages = '<form action="sondages=vote.php" method="POST" class="wiki_p" style="padding-left:120px;"><span style="position:relative;left:-100px;">'.nl2br($titre).'</span><br /><input type="hidden" name="id" value="'.$id.'" />
			';if($explication != "") $contenu_sondages .= '<em style="position:relative;left:-100px;">'.nl2br($explication).'</em><br />'; $contenu_sondages .= '
			<input class="checkbox" type="checkbox" name="p1" id="p1" /> <label for="p1">'.$p1.'</label><br />
			<input class="checkbox" type="checkbox" name="p2" id="p2" /> <label for="p2">'.$p2.'</label>';
			
			if($p3 != "-")
				{
				$contenu_sondages .= '<br />
				<input class="checkbox" type="checkbox" name="p3" id="p3" /> <label for="p3">'.$p3.'</label>';
				}
			if($p4 != "-")
				{
				$contenu_sondages .= '<br />
				<input class="checkbox" type="checkbox" name="p4" id="p4" /> <label for="p4">'.$p4.'</label>';
				}
			if($p5 != "-")
				{
				$contenu_sondages .= '<br />
				<input class="checkbox" type="checkbox" name="p5" id="p5" /> <label for="p5">'.$p5.'</label>';
				}
			if($p6 != "-")
				{
				$contenu_sondages .= '<br />
				<input class="checkbox" type="checkbox" name="p6" id="p6" /> <label for="p6">'.$p6.'</label>';
				}
			
			$contenu_sondages .= '<br /><br />
			<input name="submit" type="submit" value="Voter" class="ok" style="height:22px;padding-bottom:2px;" />
			</form>';
			}
			
			
						print('<h3>Donnez votre avis</h3>'.$contenu_sondages);
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
