<?php 
session_start();

if($_SESSION['id']=="")
	{
	print('<meta http-equiv="refresh" content="0 ; url=sondages=accueil.php"> ');
	exit();
	}

if($_SESSION['statut']!="Compte VIP" && $_SESSION['statut']!="Gold" && $_SESSION['statut']!="Platinium" && $_SESSION['statut']!="Modérateur" && $_SESSION['statut']!="Administrateur")
	{
	print('<meta http-equiv="refresh" content="0 ; url=sondages=accueil.php"> ');
	exit();
	}
	
$db = mysql_connect('localhost', 'CENSURE', 'CENSURE') or die("Erreur de connexion au serveur des messages.");
mysql_select_db('CENSURE',$db) or die("Erreur de connexion a la base de donnees des messages.");

$sql = 'SELECT moment FROM wikast_sondages_tbl WHERE auteur="'.$_SESSION['pseudo'].'" AND moment > '.(time()-(15*24*3600)).'';
$req = mysql_query($sql);
$res = mysql_num_rows($req);

if($res!=0)
	{
	$datefin = mysql_result($req,0,moment)+(15*24*3600);
	$contenu = '<div class="wiki_p" style="position:relative;top:40px;text-align:center;">Vous avez d&eacute;j&agrave; un sondage en cours. Il se terminera le '.date('d/m/y',$datefin).' &agrave; '.date('H\hi',$datefin).'.<br /><br /><a href="sondages=accueil.php">Retour &agrave; l\'accueil</a></div>';
	}
else
	{
	if($_POST['submit']=="" OR ($_POST['submit']!="" AND $_POST['titre']=="") OR ($_POST['submit']!="" AND $_POST['p1']=="") OR ($_POST['submit']!="" AND $_POST['p2']==""))
		{
		
		if($_POST['submit']!="")
			{
			$titre = stripslashes($_POST['titre']);
			$explication = stripslashes($_POST['explication']);
			$p1 = stripslashes($_POST['p1']);
			$p2 = stripslashes($_POST['p2']);
			$p3 = stripslashes($_POST['p3']);
			$p4 = stripslashes($_POST['p4']);
			$p5 = stripslashes($_POST['p5']);
			$p6 = stripslashes($_POST['p6']);
			$type = ($_POST['multi']=="")?'':'checked="checked"';
			}
			
		$erreur = '';
		if($_POST['submit']!="" AND $_POST['titre']=="") $erreur .= 'Vous devez sp&eacute;cifier une question.<br />';
		if(($_POST['submit']!="" AND $_POST['p1']=="") OR ($_POST['submit']!="" AND $_POST['p2']=="")) $erreur .= 'Il faut au moins remplir les deux premi&egrave;res r&eacute;ponses possibles.<br />';
		if($erreur!="") $erreur .= "<br />";
		
		$contenu = '<form action="sondages=nouveau.php" method="POST" class="wiki_p">
			'.$erreur.'
			<em>Vous vous appr&ecirc;tez &agrave; soumettre un sondage &agrave; la communaut&eacute;. Une fois post&eacute;, vous ne pourrez plus le modifier, ni en cr&eacute;er un nouveau pendant deux semaines apr&egrave;s sa parution.</em><br /><br />
			<table style="position:relative;left:50px;">
				<tr>
					<td>Question</td>
					<td><input type="text" name="titre" value="'.$titre.'" /></td>
				</tr>
				<tr>
					<td>Explication (facultatif)</td>
					<td><textarea name="explication" style="position:relative;left:50px;margin:0;height:100px;width:200px;border:1px solid #666;background-color:#2a2a2a;padding:0 2px;color:#989898;" >'.$explication.'</textarea></td>
				</tr>
				<tr>
					<td>R&eacute;ponse 1</td>
					<td><input type="text" name="p1" value="'.$p1.'" /></td>
				</tr>
				<tr>
					<td>R&eacute;ponse 2</td>
					<td><input type="text" name="p2" value="'.$p2.'" /></td>
				</tr>
				<tr>
					<td>R&eacute;ponse 3 (facultatif)</td>
					<td><input type="text" name="p3" value="'.$p3.'" /></td>
				</tr>
				<tr>
					<td>R&eacute;ponse 4 (facultatif)</td>
					<td><input type="text" name="p4" value="'.$p4.'" /></td>
				</tr>
				<tr>
					<td>R&eacute;ponse 5 (facultatif)</td>
					<td><input type="text" name="p5" value="'.$p5.'" /></td>
				</tr>
				<tr>
					<td>R&eacute;ponse 6 (facultatif)</td>
					<td><input type="text" name="p6" value="'.$p6.'" /></td>
				</tr>
				<tr>
					<td colspan="2">Autoriser plusieurs r&eacute;ponses par vote <input class="checkbox" type="checkbox" '.$type.' name="multi" style="position:relative;top:1px;" /></td>
				</tr>
			</table>
			<br />
			<input name="submit" type="submit" value="Soumettre le sondage" class="ok2" style="position:relative;margin-left:100px;" />
		
		</form>';
		}
	else
		{
		$auteur = ($_SESSION['statut']=="Administrateur")?"Administrateur":$_SESSION['pseudo'];
		$titre = htmlentities(stripslashes($_POST['titre']));
		$explication = htmlentities(stripslashes($_POST['explication']));
		$p1 = htmlentities(stripslashes($_POST['p1']));
		$p2 = htmlentities(stripslashes($_POST['p2']));
		$p3 = htmlentities(stripslashes($_POST['p3']));
		$p4 = htmlentities(stripslashes($_POST['p4']));
		$p5 = htmlentities(stripslashes($_POST['p5']));
		$p6 = htmlentities(stripslashes($_POST['p6']));
		$type = ($_POST['multi']=="")?0:1;
		
		$v1 = 0;
		$v2 = 0;
		$v3 = 0;
		$v4 = 0;
		$v5 = 0;
		$v6 = 0;
		
		if($p3=="") { $p3 = '-'; $v3 = -1; }
		if($p4=="") { $p4 = '-'; $v4 = -1; }
		if($p5=="") { $p5 = '-'; $v5 = -1; }
		if($p6=="") { $p6 = '-'; $v6 = -1; }
		
		$sql = 'INSERT INTO wikast_sondages_tbl(id,auteur,titre,explication,moment,type,p1,p2,p3,p4,p5,p6,v1,v2,v3,v4,v5,v6,electeurs) VALUES("","'.$auteur.'","'.$titre.'","'.$explication.'","'.time().'","'.$type.'","'.$p1.'","'.$p2.'","'.$p3.'","'.$p4.'","'.$p5.'","'.$p6.'","'.$v1.'","'.$v2.'","'.$v3.'","'.$v4.'","'.$v5.'","'.$v6.'","-")';
		mysql_query($sql);
		
		$sql = 'SELECT id FROM wikast_sondages_tbl WHERE titre="'.$titre.'"';
		$req = mysql_query($sql);
		$res = mysql_num_rows($req);
		
		if($res==0)
			{
			$contenu = '<div class="wiki_p" style="position:relative;top:40px;text-align:center;">Une erreur est survenue durant l\'enregistrement de votre sondage.<br /><br /><a href="sondages=nouveau.php">Retour</a></div>';
			}
		else
			{
			$contenu = '<div class="wiki_p" style="position:relative;top:40px;text-align:center;">Votre sondage a bien &eacute;t&eacute; ajout&eacute; au Wikast.<br /><br /><a href="sondages.php?id='.mysql_result($req,0,id).'">Voir le sondage</a></div>';
			}
		}
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
					<p class="gauche"><br /><br /><br /><a href="sondages=accueil.php">Retour &agrave; l'accueil</a></p>
				</div>
			</div>
			
			<div id="mainpage-forum">
				<div id="haut">
					<a href="sondages=accueil.php" title="Retour" id="btn_retour"></a>
					<p>Nouveau sondage</p>
				</div>
				
				<div id="contenu">
					<?php
					
					print($contenu);
					
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
