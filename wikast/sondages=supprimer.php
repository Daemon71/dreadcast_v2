<?php 
session_start();

if($_SESSION['id']=="")
	{
	print('<meta http-equiv="refresh" content="0 ; url=sondages=accueil.php"> ');
	exit();
	}

if($_SESSION['statut']!="Administrateur")
	{
	print('<meta http-equiv="refresh" content="0 ; url=sondages=accueil.php"> ');
	exit();
	}
	
if($_POST['submit']=="" OR ($_POST['submit']!="" AND $_POST['raison']==""))
	{
	$contenu='<form action="sondages=supprimer.php?id='.$_GET['id'].'" method="POST" class="wiki_p">
	        <em>Vous vous appr&ecirc;ter &agrave; supprimer un sondage.<br />Veuillez donner une raison &agrave; son auteur.</em><br /><br />
	        <table style="position:relative;left:50px;">
	            <tr>
					<td>Raison</td>
				    <td><textarea name="raison" style="position:relative;left:50px;margin:0;height:100px;width:300px;border:1px solid #666;background-color:#2a2a2a;padding:0 2px;color:#989898;" >'.$explication.'</textarea></td>
    		    </tr>
			</table>
			<br />
			<input name="submit" type="submit" value="Soumettre le sondage" class="ok2" style="position:relative;margin-left:150px;" />
		
		</form>';
	}
elseif($_POST['submit']!="" AND $_POST['raison']!="")
    {
    $db = mysql_connect('localhost', 'CENSURE', 'CENSURE') or die("Erreur de connexion au serveur des messages.");
    mysql_select_db('CENSURE',$db) or die("Erreur de connexion a la base de donnees des messages.");
    
    $sql = 'SELECT auteur,titre FROM wikast_sondages_tbl WHERE id='.$_GET['id'].'';
    $req = mysql_query($sql);
    $res = mysql_num_rows($req);
    
    if($res==0) $contenu='<div class="wiki_p" style="position:relative;top:40px;text-align:center;">Ce sondage n\'existe pas.<br /><br /><a href="sondages=accueil.php">Retour &agrave; l\'accueil</a></div>';
    else
        {
        if(mysql_result($req,0,auteur) != "Administrateur")
            {
            $texte = "Votre sondage \"".mysql_result($req,0,titre)."\" a &eacute;t&eacute; supprim&eacute;.<br /><br />Raison : <br />".htmlentities(stripslashes($_POST['raison']));
						
	    	$titre = "Suppression sondage";
						
		    $sql = 'INSERT INTO messages_tbl(id,auteur,cible,message,objet,moment,nouveau) VALUES("","Dreadcast","'.mysql_result($req,0,auteur).'","'.$texte.'","'.$titre.'","'.time().'","oui")';
    		mysql_query($sql);
    		
    		$sql = 'DELETE FROM wikast_sondages_tbl WHERE id='.$_GET['id'].'';
		    mysql_query($sql);
    		}
						
        $contenu = '<div class="wiki_p" style="position:relative;top:40px;text-align:center;">Sondage supprim&eacute;.<br /><br /><a href="sondages=accueil.php">Retour &agrave; l\'accueil</a></div>';
        }
    
    mysql_close($db);
    }

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
					<p>Suppression de sondage</p>
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
