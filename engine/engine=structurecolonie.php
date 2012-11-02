<?php 
session_start(); 
if($_SESSION['id']=="")
	{
	print('<meta http-equiv="refresh" content="0 ; url=http://v2.dreadcast.net/"> ');
	exit();
	}
if($_SESSION['action']=="Vacances")
	{
	print('<meta http-equiv="refresh" content="0 ; url=http://v2.dreadcast.net/ingame/engine=cryo.php"> ');
	exit();
	}
if($_SESSION['action']=="mort")
	{
	print('<meta http-equiv="refresh" content="0 ; url=http://v2.dreadcast.net/ingame/engine=mort.php"> ');
	exit();
	}
	
if($_GET['id']=="")
	{
	print('<meta http-equiv="refresh" content="0 ; url=http://v2.dreadcast.net/ingame/engine.php"> ');
	exit();
	}

?>
<?php if ((date("H")>=21) || (date("H")<=7)) { include("inc_haut_n.php"); } else { include("inc_haut_j.php");} ?>

<div id="haut">
	<?php include("inc_entete.php"); ?>
	<div class="haut_aligngauche">
		<p>
		<div class="titrepage">
			Structure de la colonie
		</div>
		<b class="module4ie"><a href="engine.php" class="module4"><img src="im_objets/icon_retour.gif" alt="Retour" />Retour</a></b>
		</p>
	</div>
</div>
<div id="centre">
	<?php
	
		$db = mysql_connect('localhost', 'CENSURE', 'CENSURE') or die("Erreur de connexion au serveur des messages.");
		mysql_select_db('CENSURE',$db) or die("Erreur de connexion a la base de donnees des messages.");
		
		$sql = 'SELECT * FROM colonies_planetes_tbl WHERE id="'.$_GET['id'].'"';
		$req = mysql_query($sql);
		$res = mysqL_num_rows($req);
		
		if($req == 0)
			{
			print('<meta http-equiv="refresh" content="0 ; url=http://v2.dreadcast.net/ingame/engine.php"> ');
			exit();
			}
		elseif(mysql_result($req,0,proprietaire) != $_SESSION['pseudo'] && $_SESSION['statut'] != "Administrateur")
			{
			print('<meta http-equiv="refresh" content="0 ; url=http://v2.dreadcast.net/ingame/engine.php"> ');
			exit();
			}
		else
			{
			$planete = mysql_result($req,0,nom);
			$positionx = mysql_result($req,0,x);
			$positiony = mysql_result($req,0,y);
			$taille = mysql_result($req,0,cases);
			$nb_lignes = $taille/mysql_result($req,0,nb_colonnes);
			$nb_colonnes = mysql_result($req,0,nb_colonnes);
			$colonie = mysql_result($req,0,colonie);
			$description = mysql_result($req,0,description_rp);
			$energie = mysql_result($req,0,energie);
			//$image = 'http://v2.dreadcast.net/ingame/im_objets/planete_'.strtolower($planete).'_colonie.gif';
			$image = 'http://v2.dreadcast.net/ingame/im_objets/planete_terre.gif';
			}
		
		if(empty($colonie))
			{
			if($_POST['etape']=="" || $_POST['etape']=="Etape 1")
				{
				print('<h3 style="margin-bottom:20px;">Création d\'une colonie sur la planète '.$planete.'</h3>
			
				<div style="margin:0 20px;">
					<form action="#" method="post" style="text-align:justify;">
						');
				print('<h4 style="position:relative;margin:10px 0 20px 0;border-bottom:1px solid black;text-align:right;"><div style="position:absolute;left:0;top:0;">Etape 2</div><div><a href="engine.php" style="font-weight:normal;">Retour</a> - <input type="submit" name="etape" value="Etape 2" style="border:0;background:0;" class="commelien" /></div></h4>');
				print('<div style="margin:5px 0;"><strong>Nom de la colonie</strong> : <input name="nom_nouvelle_colonie" value="'.(($_SESSION['nom_nouvelle_colonie'] == "")?"":$_SESSION['nom_nouvelle_colonie']).'"/> <em style="font-size:10px;">Une fois choisi, le nom est définitif</em></div>
				</form>
			
			</div>');
				
				}
			elseif($_POST['etape']=="Etape 2")
				{
				$_SESSION['nom_nouvelle_colonie'] = htmlentities($_POST['nom_nouvelle_colonie']);
				print('<div style="margin:0 20px;">
					<form action="#" method="post" style="text-align:justify;">
						');
				print('<h4 style="position:relative;margin:10px 0 20px 0;border-bottom:1px solid black;text-align:right;"><div style="position:absolute;left:0;top:0;">Etape 2 <span style="font-weight:normal;">- Colonie '.$_SESSION['nom_nouvelle_colonie'].'</span></div><div><input type="submit" name="etape" value="Etape 1" style="border:0;background:0;" class="commelien" /> - <input type="submit" name="etape" value="Etape 3" style="border:0;background:0;" class="commelien" /></div></h4>');
				print('<div style="margin:5px 0;"><strong>Position du poste de commandement</strong> : <input style="border:none;background:none;width:100px;" nom="position_pc" value="'.$colonie.'"/></div>
				<div style="position:relative;margin:5px 0;">');
				for($i=0;$i<$nb_lignes;$i++)
					{
					for($j=0;$j<$nb_colonnes;$j++)
						{
						print('<a href="#" style="display:block;position:absolute;left:'.(3*$j+$j).'px;top:'.(3*$i+$i).'px;width:3px;height:3px;background:grey;"></a>');
						}
					}
					print('</div>
					</form>
			
			</div>');
				}
			}
		else
			{
			print('<h3 style="margin-bottom:20px;">Colonie de la planète '.$planete.'</h3>
			
			<div style="margin:0 20px;">
				<img src="'.$image.'" alt="Image de la planete" style="float:left;margin-right:10px;" />
				<div style="text-align:justify;">
					<div style="margin:5px 0;"><strong>Nom de la colonie</strong> : '.$colonie.'</div>
					<div style="margin:5px 0;"><strong>Surface exploitable</strong> : '.$taille.' hectares</div>
					<div style="margin:5px 0;"><strong>Coordonnées spaciales de la planète</strong> : ('.$positionx.';'.$positiony.')</div>
					<div style="margin:5px 0;"><strong>Description de la planete</strong> : '.$description.'</div>
					<div style="margin:5px 0;"><strong>Blocs construits</strong> : <br />');
					
			$types_blocs = liste_types_blocs();
			$nb_types_blocs = count($types_blocs);
			for($i=0;$i<$nb_types_blocs;$i++)
				{
				$sql = 'SELECT type FROM colonies_cases_tbl WHERE planete="'.$_GET['id'].'" AND type = "'.$types_blocs[$i].'"';
				$req = mysql_query($sql);
				$res = mysqL_num_rows($req);
				if($res > 0) print(mysql_result($req,$i,type).' : '.$res.' unité'.(($res>1)?"s":"").'<br />');
				}
					
			print('</div>
				</div>
			</div>');
			}
			
		mysql_close($db);
	?>
</div>

<?php if($chat=="oui") 	{ if ((date("H")>=21) || (date("H")<=7)) { include("inc_bas_n_c.php"); } else { include("inc_bas_j_c.php"); } } else {	if ((date("H")>=21) || (date("H")<=7)) { include("inc_bas_n.php"); } else { include("inc_bas_j.php"); }	} ?>
<?php include("inc_bas_de_page.php"); ?>
