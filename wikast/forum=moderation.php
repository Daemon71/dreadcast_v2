<?php
session_start();

if(empty($_GET['forum']))
	{
	print('<meta http-equiv="refresh" content="0 ; url=forum=accueil.php"> ');
	exit();
	}

$db = mysql_connect('localhost', 'CENSURE', 'CENSURE') or die("Erreur de connexion au serveur des messages.");
mysql_select_db('CENSURE',$db) or die("Erreur de connexion a la base de donnees des messages.");
				
$sql = 'SELECT nom,admin FROM wikast_forum_structure_tbl WHERE id="'.$_GET['forum'].'"' ;
$req = mysql_query($sql);
$res = mysql_num_rows($req);

$nomforum = mysql_result($req,0,nom);
$modoGlob = mysql_result($req,0,admin);

if(empty($_SESSION['id']))
	{
	print('<meta http-equiv="refresh" content="0 ; url=forum.php?partie='.$nomforum.'"> ');
	exit();
	}

if($res==0)
	{
	print('<meta http-equiv="refresh" content="0 ; url=forum=accueil.php"> ');
	exit();
	}
	
if($_SESSION['pseudo']!=$modoGlob AND $_SESSION['statut']!="Administrateur")
	{
	print('<meta http-equiv="refresh" content="0 ; url=forum.php?partie='.$nomforum.'"> ');
	exit();
	}
	
if($_POST['envoyer']!="")
	{
	if($_POST['nouveaunom']!="" && $_POST['nouveaumodo']!="")
		{
		$sql = 'SELECT id FROM wikast_forum_structure_tbl WHERE type= "'.htmlentities($_GET['forum']).'" ORDER BY id ASC' ;
		$req = mysql_query($sql);
		$res = mysql_num_rows($req);
		
		if($res<4)
			{
			list($ok,$pasok) = explode(',',$_POST['nouveaumodo']);
			$sql = 'INSERT INTO wikast_forum_structure_tbl(id,type,nom,admin) VALUES("","'.htmlentities($_GET['forum']).'","'.htmlentities($_POST['nouveaunom']).'","'.htmlentities($ok).'")' ;
			$req = mysql_query($sql);
			}
		}
		
		$sql = 'SELECT id FROM wikast_forum_structure_tbl WHERE type= "'.htmlentities($_GET['forum']).'" ORDER BY id ASC' ;
		$req = mysql_query($sql);
		$res = mysql_num_rows($req);
		
	for($i=0 ; $i<$res ; $i++)
		{
		if($_POST['ssfnom'.$i]!="" && $_POST['ssfmodo'.$i]!="")
			{
			list($ok,$pasok) = explode(',',$_POST['ssfmodo'.$i]);
			$sql2 = 'UPDATE wikast_forum_structure_tbl SET nom="'.htmlentities($_POST['ssfnom'.$i]).'", admin="'.htmlentities($ok).'" WHERE id="'.mysql_result($req,$i,id).'"' ;
			$req2 = mysql_query($sql2);
			}
		}
	if($_POST['nouveaumodoprincipal'] != "")
		{
		list($ok,$pasok) = explode(',',$_POST['nouveaumodoprincipal']);
		$sql2 = 'UPDATE wikast_forum_structure_tbl SET admin="'.htmlentities($ok).'" WHERE id="'.htmlentities($_GET['forum']).'"' ;
		mysql_query($sql2);
		}
	}
if($_GET['vider'] != "" && $_SESSION['statut']=="Administrateur")
	{
	$sql = 'UPDATE wikast_forum_sujets_tbl SET categorie="65" WHERE categorie= "'.htmlentities($_GET['vider']).'"' ;
	mysql_query($sql);
	}

$sql = 'SELECT nom,admin FROM wikast_forum_structure_tbl WHERE id="'.$_GET['forum'].'"' ;
$req = mysql_query($sql);
$res = mysql_num_rows($req);

$modoGlob = mysql_result($req,0,admin);

mysql_close($db);

include('include/inc_head.php');
?>


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
			
			print('<div id="forum-entete">');
			
			include('include/inc_barreliens1.php');

				print('
					
					<div id="forum-info2">
					</div>
				</div>
				
				<div id="mainpage-forum">
					<div id="haut">
						<a href="forum=accueil.php" title="Retour" id="btn_retour"></a>
						<p>Mod&eacute;ration : forum '.$nomforum.'</p>
					</div>
					
					<form action="forum=moderation.php?forum='.$_GET['forum'].'" method="post" id="champ-mod" name="modiforum">
						<input name="envoyer" type="hidden" value="true" />
						
						<div id="contenu">
							<p class="retour">Vous pouvez cr&eacute;er jusqu\'&agrave; 4 sous-forums</p>
							<div class="ss-forum">
								<span class="nom">Changer la modération<br />Forum '.$nomforum.'</span> <span class="modo">Mod&eacute;rateur principal <input name="nouveaumodoprincipal" type="text" value="'.$modoGlob.'" /></span>
							</div>');
						
						$sql = 'SELECT id,nom,admin FROM wikast_forum_structure_tbl WHERE type="'.$_GET['forum'].'" ORDER BY id ASC' ;
						$req = mysql_query($sql);
						$res = mysql_num_rows($req);
						
						$i=0;
						
						for($i;$i<$res;$i++)
							{
							
							$ssfid = mysql_result($req,$i,id);
							$ssfnom = mysql_result($req,$i,nom);
							$ssfmodo = mysql_result($req,$i,admin);
							
							print('<div class="ss-forum">
								<span class="nom">Nom du<br />sous-forum <input name="ssfnom'.$i.'" type="text" value="'.$ssfnom.'" /></span> <span class="modo">Mod&eacute;rateur <input name="ssfmodo'.$i.'" type="text" value="'.$ssfmodo.'" /></span>
							</div>');
													
							}
							
							if($i<4)
								print('<div class="ss-forum">
									<span class="nom">Nom du<br />sous-forum <input name="nouveaunom" type="text" value="" /></span> <span class="modo">Mod&eacute;rateur <input name="nouveaumodo" type="text" value="" /></span>
								</div>');
						
							print('<p><input name="submit" type="submit" value="Modifier" id="ok" /></p>
						
						</div>
					
					</form>
					
					<div id="bas">
					</div>
				</div>
				');
			
				
			mysql_close($db);
			?>
			
		</div>
	
	</body>
	
</html>
