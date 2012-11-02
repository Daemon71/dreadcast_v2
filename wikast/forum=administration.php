<?php
session_start();

if($_SESSION['statut'] != "Administrateur")
	{
	print('<meta http-equiv="refresh" content="0 ; url=forum=accueil.php"> ');
	exit();
	}

$db = mysql_connect('localhost', 'CENSURE', 'CENSURE') or die("Erreur de connexion au serveur des messages.");
mysql_select_db('CENSURE',$db) or die("Erreur de connexion a la base de donnees des messages.");

if($_POST['modifier_forums']!="" && $_SESSION['statut'] == "Administrateur")
	{
	for($i=0 ; $i<4 ; $i++)
		{
		if($_POST['fnom'.$i]!="" && $_POST['fmodo'.$i]!="")
			{
			$sql2 = 'UPDATE wikast_forum_structure_tbl SET nom="'.$_POST['fnom'.$i].'", admin="'.htmlentities($_POST['fmodo'.$i]).'" WHERE id="'.($i+1).'"' ;
			$req2 = mysql_query($sql2);
			}
		}
	}
if($_POST['suppr_forum'] != "" && $_SESSION['statut']=="Administrateur")
	{
	$sql4 = 'SELECT id FROM wikast_forum_structure_tbl WHERE type = "'.$_POST['suppr_forum'].'"';
	$req4 = mysql_query($sql4);
	$res4 = mysql_num_rows($req4);
	
	if($res == 0)
		{
		$sql = 'UPDATE wikast_forum_sujets_tbl SET categorie="65" WHERE categorie= "'.$_POST['suppr_forum'].'"' ;
		mysql_query($sql);
		$sql5 = 'DELETE FROM wikast_forum_structure_tbl WHERE id="'.$_POST['suppr_forum'].'"';
		mysql_query($sql5);
		}
	else
		{
		for($j=0;$j<$res4;$j++)
			{
			$sql5 = 'UPDATE wikast_forum_sujets_tbl SET categorie="65" WHERE categorie= "'.mysql_result($req4,$j,id).'"' ;
			mysql_query($sql5);
			$sql5 = 'DELETE FROM wikast_forum_structure_tbl WHERE id= "'.mysql_result($req4,$j,id).'"' ;
			mysql_query($sql5);
			}
		$sql5 = 'DELETE FROM wikast_forum_structure_tbl WHERE id="'.$_POST['suppr_forum'].'"';
		mysql_query($sql5);
		}
	}
if($_POST['vide_forum'] != "" && $_SESSION['statut']=="Administrateur")
	{
	$sql = 'SELECT id FROM wikast_forum_structure_tbl WHERE type = "'.$_POST['vide_forum'].'"';
	$req = mysql_query($sql);
	$res = mysql_num_rows($req);
	
	if($res == 0)
		{
		$sql = 'UPDATE wikast_forum_sujets_tbl SET categorie="65" WHERE categorie= "'.$_POST['vide_forum'].'"' ;
		mysql_query($sql);
		}
	else
		{
		for($i=0;$i<$res;$i++)
			{
			$sql = 'UPDATE wikast_forum_sujets_tbl SET categorie="65" WHERE categorie= "'.mysql_result($req,$i,id).'"' ;
			mysql_query($sql);
			}
		}
	}
if($_POST['change_modo_forum']!="" && $_POST['change_modo_nom']!="" && $_SESSION['statut'] == "Administrateur")
	{
	$sql2 = 'UPDATE wikast_forum_structure_tbl SET admin="'.$_POST['change_modo_nom'].'" WHERE id="'.$_POST['change_modo_forum'].'"' ;
	$req2 = mysql_query($sql2);
	}
if($_POST['ban_nom']!="" && $_POST['ban_duree']!="" && $_POST['ban_raison']!="" && $_SESSION['statut'] == "Administrateur")
	{
	if(preg_match("#[0-9]+#",$_POST['ban_duree']))
		{
		$time = time() + $_POST['ban_duree']*(60*60*24);
		$sql = 'INSERT INTO wikast_forum_permissions_tbl(sujet,pseudo,statut) VALUES("0","'.$_POST['ban_nom'].'","'.$time.'")' ;
		mysql_query($sql);
		$sql = 'INSERT INTO messages_tbl(auteur,cible,message,objet,moment) VALUES("Dreadcast","'.$_POST['ban_nom'].'","Vous avez été banni des forums du Wikast pour une durée de '.$_POST['ban_duree'].' jours.<br /><br />\"'.stripslashes($_POST['ban_raison']).'\"","Bannissement","'.time().'")' ;
		mysql_query($sql);
		}
	}
if($_POST['retire_ban_nom']!="" && $_SESSION['statut'] == "Administrateur")
	{
	$sql = 'UPDATE wikast_forum_permissions_tbl SET statut = "'.time().'" WHERE sujet = "0" AND pseudo = "'.$_POST['retire_ban_nom'].'" AND statut > "'.time().'"' ;
	mysql_query($sql);
	}

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
						<p>Partie Administration</p>
					</div>
					
					<form action="#" method="post" id="champ-mod" name="modiforum" style="margin-top:0;margin-bottom:0;padding-top:0;padding-bottom:0;">
						<div id="contenu" style="margin-bottom:0;padding-bottom:0;">
							<p class="retour">Administration du wikast</p>
							<h3>Forums principaux</h3>');
							
							$sql = 'SELECT nom,admin FROM wikast_forum_structure_tbl WHERE id = 1 OR id = 2 OR id = 3 OR id = 4' ;
							$req = mysql_query($sql);
							$res = mysql_num_rows($req);
							for($i=0;$i<$res;$i++)
								{
								print('<div class="ss-forum">
									<span class="nom">Nom du<br />forum <input name="fnom'.$i.'" type="text" value="'.mysql_result($req,$i,nom).'" /></span> <span class="modo">Mod&eacute;rateur <input name="fmodo'.$i.'" type="text" value="'.mysql_result($req,$i,admin).'" /></span>
								</div>');
								}
								
							print('<p style="margin-top:0;margin-bottom:0;padding-top:0;padding-bottom:0;"><input name="modifier_forums" type="submit" value="Modifier" id="ok" style="margin-top:0;margin-bottom:0;padding-top:0;padding-bottom:0;" /></p>
						</div>
					</form>
					
					<form action="#" method="post" id="champ-mod" name="modiforum">
						<div id="contenu" style="margin-top:0;margin-bottom:0;padding-top:0;padding-bottom:0;">
							<h3>Supprimer un forum</h3>
							<div class="ss-forum">
								<span class="nom">Sélectionnez<br />le forum <select name="suppr_forum" style="position:absolute;top:3px;left:70px;width:170px;font-size:12px;color:#A8A8A8;background:#494949;border:1px solid #787878;padding:2px;">
									<option style="display:none;" selected="selected" value="#">Aucun</option>');
								
									$sql = 'SELECT id,nom,admin FROM wikast_forum_structure_tbl WHERE type=-1 ORDER BY nom' ;
									$req = mysql_query($sql);
									$res = mysql_num_rows($req);
									for($i=0;$i<$res;$i++)
										{
										print('<option style="padding-left:5px;background:#333;" value="'.mysql_result($req,$i,id).'">'.mysql_result($req,$i,nom).'</option>');
										
										$sql2 = 'SELECT id,nom,admin FROM wikast_forum_structure_tbl WHERE type='.mysql_result($req,$i,id).' ORDER BY id' ;
										$req2 = mysql_query($sql2);
										$res2 = mysql_num_rows($req2);
										for($j=0;$j<$res2;$j++) print('<option style="margin-left:15px;" value="'.mysql_result($req2,$j,id).'">'.mysql_result($req,$i,nom).'</span> &raquo; '.mysql_result($req2,$j,nom).'</option>');
										}
								
									print('</select></span>');
								
								print('
							</div>
							<p style="margin-top:0;margin-bottom:0;padding-top:0;padding-bottom:0;"><input name="submit" type="submit" value="Supprimer" id="ok" style="margin-top:0;margin-bottom:0;padding-top:0;padding-bottom:0;" /></p>
						</div>
					</form>
					
					<form action="#" method="post" id="champ-mod" name="modiforum">
						<div id="contenu" style="margin-top:0;margin-bottom:0;padding-top:0;padding-bottom:0;">	
							<h3>Vider un forum</h3>
							<div class="ss-forum">
								<span class="nom">Sélectionnez<br />le forum <select name="vide_forum" style="position:absolute;top:3px;left:70px;width:170px;font-size:12px;color:#A8A8A8;background:#494949;border:1px solid #787878;padding:2px;">
									<option style="display:none;" selected="selected" value="#">Aucun</option>');
								
									$sql = 'SELECT id,nom,admin FROM wikast_forum_structure_tbl WHERE type=-1 ORDER BY nom' ;
									$req = mysql_query($sql);
									$res = mysql_num_rows($req);
									for($i=0;$i<$res;$i++)
										{
										print('<option style="padding-left:5px;background:#333;" value="'.mysql_result($req,$i,id).'">'.mysql_result($req,$i,nom).'</option>');
										
										$sql2 = 'SELECT id,nom,admin FROM wikast_forum_structure_tbl WHERE type='.mysql_result($req,$i,id).' ORDER BY id' ;
										$req2 = mysql_query($sql2);
										$res2 = mysql_num_rows($req2);
										for($j=0;$j<$res2;$j++) print('<option style="margin-left:15px;" value="'.mysql_result($req2,$j,id).'">'.mysql_result($req,$i,nom).'</span> &raquo; '.mysql_result($req2,$j,nom).'</option>');
										}
								
									print('</select></span>');
								
								print('
							</div>
							<p style="margin-top:0;margin-bottom:0;padding-top:0;padding-bottom:0;"><input name="submit" type="submit" value="Vider" id="ok" style="margin-top:0;margin-bottom:0;padding-top:0;padding-bottom:0;" /></p>
						</div>
					</form>
							
					<form action="#" method="post" id="champ-mod" name="modiforum">
						<div id="contenu" style="margin-top:0;margin-bottom:0;padding-top:0;padding-bottom:0;">	
							<h3>Changer la modération d\'un forum</h3>
							<div class="ss-forum">
								<span class="nom">Sélectionnez<br />le forum <select name="change_modo_forum" style="position:absolute;top:3px;left:70px;width:170px;font-size:12px;color:#A8A8A8;background:#494949;border:1px solid #787878;padding:2px;">
									<option style="display:none;" selected="selected" value="#">Aucun</option>');
								
									$sql = 'SELECT id,nom,admin FROM wikast_forum_structure_tbl WHERE type=-1 ORDER BY nom' ;
									$req = mysql_query($sql);
									$res = mysql_num_rows($req);
									for($i=0;$i<$res;$i++)
										{
										print('<option style="background:#333;" onclick="javascript:change_valeur(\'change_modo_nom\',\''.mysql_result($req,$i,admin).'\');" value="'.mysql_result($req,$i,id).'">&nbsp;'.mysql_result($req,$i,nom).'</option>');
										
										$sql2 = 'SELECT id,nom,admin FROM wikast_forum_structure_tbl WHERE type='.mysql_result($req,$i,id).' ORDER BY id' ;
										$req2 = mysql_query($sql2);
										$res2 = mysql_num_rows($req2);
										for($j=0;$j<$res2;$j++) print('<option style="margin-left:15px;" onclick="javascript:change_valeur(\'change_modo_nom\',\''.mysql_result($req2,$j,admin).'\');" value="'.mysql_result($req2,$j,id).'">'.mysql_result($req,$i,nom).'</span> &raquo; '.mysql_result($req2,$j,nom).'</option>');
										}
								
									print('</select></span> <span class="modo">Mod&eacute;rateur <input id="change_modo_nom" name="change_modo_nom" type="text" value="" /></span>');
									
							print('</div>
							<p style="margin-top:0;margin-bottom:0;padding-top:0;padding-bottom:0;"><input name="submit" type="submit" value="Modifier" id="ok" style="margin-top:0;margin-bottom:0;padding-top:0;padding-bottom:0;" /></p>
						</div>
					</form>
							
							');
							
					print('<div id="contenu"><p class="retour">Administration des joueurs</p></div>');
							
					print('
					<form action="#" method="post" id="champ-mod" name="modiforum">
						<div id="contenu" style="margin-top:0;margin-bottom:0;padding-top:0;padding-bottom:0;">	
							<h3>Bannir un joueur</h3>
							<div class="ss-forum">
								<span class="nom">Pseudo<br />du joueur <input name="ban_nom" type="text" value="" onchange="javascript:affiche_inl(\'ban_raison\',true);" /></span> <span class="modo">Nombre de jours <input name="ban_duree" type="text" value="" /></span><br />
							</div>
							<div id="ban_raison" style="display:none;position:relative;width:527px;background:#282828;line-height:12px;">
								<textarea name="ban_raison" style="position:relative;left:60px;width:400px;height:100px;font-size:12px;color:#A8A8A8;background:#494949;border:1px solid #787878;padding:2px;margin-bottom:5px;">Raison :
</textarea>
							</div>
							<p style="margin-top:0;margin-bottom:0;padding-top:0;padding-bottom:0;"><input name="submit" type="submit" value="Modifier" id="ok" style="margin-top:0;margin-bottom:0;padding-top:0;padding-bottom:0;" /></p>
						</div>
					</form>
					
					<form action="#" method="post" id="champ-mod" name="modiforum">
						<div id="contenu" style="margin-top:0;margin-bottom:0;padding-top:0;padding-bottom:0;">	
							<h3>Retirer un bannissement</h3>
							<div class="ss-forum">
								<span class="nom">Sélectionnez<br />le forum <select name="retire_ban_nom" style="position:absolute;top:3px;left:70px;width:170px;font-size:12px;color:#A8A8A8;background:#494949;border:1px solid #787878;padding:2px;">
									<option style="display:none;" selected="selected" value="#">Aucun</option>');
								
									$sql = 'SELECT pseudo,statut FROM wikast_forum_permissions_tbl WHERE sujet="0" AND statut > "'.time().'" ORDER BY pseudo' ;
									$req = mysql_query($sql);
									$res = mysql_num_rows($req);
									for($i=0;$i<$res;$i++)
										{
										$texte = date("d/m/Y H:i",mysql_result($req,$i,statut));
										print('<option style="background:#333;" onclick="javascript:change_valeur(\'duree_de\',\''.$texte.'\');" value="'.mysql_result($req,$i,pseudo).'">&nbsp;'.mysql_result($req,$i,pseudo).'</option>');
										}
								
									print('</select></span> <span class="modo">Jusq\'au <input id="duree_de" style="border:none;background:none;" name="change_modo_nom" type="text" value="" /></span>');
							print('</div>
							<p style="margin-top:0;margin-bottom:0;padding-top:0;padding-bottom:0;"><input name="submit" type="submit" value="Modifier" id="ok" style="margin-top:0;margin-bottom:0;padding-top:0;padding-bottom:0;" /></p>
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
