<?php 
session_start();

 if($_POST['id']=="")
	{
	print('<meta http-equiv="refresh" content="0 ; url=sondages=accueil.php"> ');
	exit();
	}
else
	{
	$id = $_POST['id'];
	}

if($_SESSION['id']=="")
	{
	print('<meta http-equiv="refresh" content="0 ; url=sondages.php?id='.$id.'"> ');
	exit();
	}

if($_POST[$id]=="" AND ($_POST['p1']=="" AND $_POST['p2']=="" AND $_POST['p3']=="" AND $_POST['p4']=="" AND $_POST['p5']=="" AND $_POST['p6']==""))
	{
	print('<meta http-equiv="refresh" content="0 ; url=sondages.php?id='.$id.'"> ');
	exit();
	}
	
$db = mysql_connect('localhost', 'CENSURE', 'CENSURE') or die("Erreur de connexion au serveur des messages.");
mysql_select_db('CENSURE',$db) or die("Erreur de connexion a la base de donnees des messages.");

$sql = 'SELECT moment,electeurs,v1,v2,v3,v4,v5,v6 FROM wikast_sondages_tbl WHERE id='.$id.'';
$req = mysql_query($sql);
$res = mysql_num_rows($req);

if($res==0)
	{
	$vote = 'Ce sondage n\'existe pas.';
	}
elseif(ereg('-'.$_SESSION['pseudo'].'-',mysql_result($req,0,electeurs)))
	{
	$vote = 'Vous avez d&eacute;j&agrave; vot&eacute; pour ce sondage.';
	}
elseif(mysql_result($req,0,moment)+15*24*3600 < time())
	{
	$vote = 'Les votes pour ce sondage sont clos.';
	}
else
	{
	
	if($_POST[$id]!="")
		{
		$valeur = $_POST[$id];
		$var = 'v'.$valeur[1];
		$sql = 'UPDATE wikast_sondages_tbl SET '.$var.'="'.(mysql_result($req,0,$var)+1).'" WHERE id='.$id.'';
		mysql_query($sql);
		}
	else
		{
		print('<'.$_POST['p1'].'>');
		if($_POST['p1']=="on")
			{
			$sql = 'UPDATE wikast_sondages_tbl SET v1="'.(mysql_result($req,0,v1)+1).'" WHERE id='.$id.'';
			mysql_query($sql);
			}
		if($_POST['p2']=="on")
			{
			$sql = 'UPDATE wikast_sondages_tbl SET v2="'.(mysql_result($req,0,v2)+1).'" WHERE id='.$id.'';
			mysql_query($sql);
			}
		if($_POST['p3']=="on")
			{
			$sql = 'UPDATE wikast_sondages_tbl SET v3="'.(mysql_result($req,0,v3)+1).'" WHERE id='.$id.'';
			mysql_query($sql);
			}
		if($_POST['p4']=="on")
			{
			$sql = 'UPDATE wikast_sondages_tbl SET v4="'.(mysql_result($req,0,v4)+1).'" WHERE id='.$id.'';
			mysql_query($sql);
			}
		if($_POST['p5']=="on")
			{
			$sql = 'UPDATE wikast_sondages_tbl SET v5="'.(mysql_result($req,0,v5)+1).'" WHERE id='.$id.'';
			mysql_query($sql);
			}
		if($_POST['p6']=="on")
			{
			$sql = 'UPDATE wikast_sondages_tbl SET v6="'.(mysql_result($req,0,v6)+1).'" WHERE id='.$id.'';
			mysql_query($sql);
			}
		}
	
	$sql = 'UPDATE wikast_sondages_tbl SET electeurs="'.mysql_result($req,0,electeurs).$_SESSION['pseudo'].'-" WHERE id='.$id.'';
	mysql_query($sql);
	
	$vote = 'Le vote a &eacute;t&eacute; enregistr&eacute;.';
	
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
					<p class="gauche"><br /><br /><?php if(statut($_SESSION['statut'])>=2) print('<a href="sondages=nouveau.php">Cr&eacute;er un sondage</a>'); ?><br /><a href="sondages=accueil.php">Retour &agrave; l'accueil</a></p>
				</div>
			</div>
			
			<div id="mainpage-forum">
				<div id="haut">
					<a href="sondages.php?id=<?php print($id); ?>" title="Retour" id="btn_retour"></a>
					<p>Sondage</p>
				</div>
				
				<div id="contenu">
					<?php
					
					print('<div class="wiki_p" style="position:relative;top:40px;text-align:center;">'.$vote.'<br /><br /><a href="sondages.php?id='.$id.'">Retour au sondage</a></div>');
					
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
