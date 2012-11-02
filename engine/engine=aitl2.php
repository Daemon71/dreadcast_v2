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
	
	$_GET['type'] = ($_GET['type'] == "")?"tel":$_GET['type'];
?>
<?php if ((date("H")>=21) || (date("H")<=7)) { include("inc_haut_n.php"); } else { include("inc_haut_j.php");} ?>

<div id="haut">
	<?php include("inc_entete.php"); ?>
	<div class="haut_aligngauche">
		<p>
		<div class="titrepage">
			AITL 2.0
		</div>
		<?php
		if(empty($_GET['type']))$_GET['type']="";
		
		if($_GET['type']=="pa") {
			print('<b class="module4ie"><a href="engine=aitl2.php?type=tel" class="module4"><img src="im_objets/icon_retour.gif" alt="Retour" />Retour</a></b>');
		}
		elseif($_GET['type']=="adj") {
			print('<b class="module4ie"><a href="engine=aitl2.php?type=tel" class="module4"><img src="im_objets/icon_retour.gif" alt="Retour" />Retour</a></b>');
		}
		elseif($_GET['type']=="art") {
			print('<b class="module4ie"><a href="engine=aitl2.php?type=arc" class="module4"><img src="im_objets/icon_retour.gif" alt="Retour" />Retour</a></b>');
		}
		elseif($_GET['type']=="arc") {
			print('<b class="module4ie"><a href="engine=aitl2.php?type=tel" class="module4"><img src="im_objets/icon_retour.gif" alt="Retour" />Retour</a></b>');
		}
		elseif($_GET['type']=="annonce") {
			print('<b class="module4ie"><a href="engine=aitl2.php?type=pa" class="module4"><img src="im_objets/icon_retour.gif" alt="Retour" />Retour</a></b>');
		}
		elseif(($_GET['type']!="tel")&&($_GET['type']!="")) {
			print('<b class="module4ie"><a href="engine=aitl2.php?type=tel" class="module4"><img src="im_objets/icon_retour.gif" alt="Retour" />Retour</a></b>');
		}
		else {
			print('<b class="module4ie"><a href="engine=inventaire.php" class="module4"><img src="im_objets/icon_retour.gif" alt="Retour" />Retour</a></b>');
		}
		
		$db = mysql_connect('localhost', 'CENSURE', 'CENSURE') or die("Erreur de connexion au serveur des messages.");
		mysql_select_db('CENSURE',$db) or die("Erreur de connexion a la base de donnees des messages.");
		
		?>
		</p>
	</div>
</div>
<div id="centreaitl2">

	<?php 
		$sql = 'SELECT case1,case2,case3,case4,case5,case6 FROM principal_tbl WHERE id= "'.$_SESSION['id'].'"' ;
		$req = mysql_query($sql);
		$_SESSION['case1'] = mysql_result($req,0,'case1');
		$_SESSION['case2'] = mysql_result($req,0,'case2');
		$_SESSION['case3'] = mysql_result($req,0,'case3');
		$_SESSION['case4'] = mysql_result($req,0,'case4');
		$_SESSION['case5'] = mysql_result($req,0,'case5');
		$_SESSION['case6'] = mysql_result($req,0,'case6');
		
		if ((event(2) || adm())) {
			$sql2 = 'SELECT id FROM objets_repares_tbl WHERE id_cible = '.$_SESSION['id'];
			$req2 = mysql_query($sql2);
			if (!mysql_num_rows($req2)) {
				//echo '<div style="margin-top:50px;color:#c81111;text-align:center;">ERREUR CRITIQUE<br /><br />Votre AITL est brouillé par une sorte de virus et ne fonctionne plus !</div>';
				//if (estDroide())
				//	echo '<div style="margin-top:20px;text-align:center;">Cependant, vous pouvez le réparer<br /><a href="engine=reparerEvent.php?type=1">Réparer</a></div>';
				$noWay = true;
				$l = 1;
			}
		}
	?>

	<div id="menuaitl2">
		<a <?php if(!ereg("dcn",$_GET['type']) && !ereg("dctv",$_GET['type']) && !ereg("upg",$_GET['type'])) print('class="selectionne"'); ?> href="engine=aitl2.php?type=tel">Canaux Impériaux</a>
		<a <?php if(ereg("dcn",$_GET['type'])) print('class="selectionne"'); ?> href="engine=aitl2.php?type=dcn">DC News</a>
		<a <?php if(ereg("dctv",$_GET['type'])) print('class="selectionne"'); ?> href="engine=aitl2.php?type=dctv">DC TV</a>
		<a <?php if(ereg("upg",$_GET['type'])) print('class="selectionne"'); ?> href="engine=aitl2.php?type=upg">Upgrades</a>
	</div>
	<div id="actionsaitl2">
		<?php
		
		if (!$noWay) {
		
		if($_GET['type']=="pa") print('<a href="engine=redigerannonce2.php">Rédiger une annonce</a>');
		if($_GET['type']=="adj")
			{
			print('<a href="engine=rediger2.php">Rédiger un article</a>');
			print('<a href="engine=aitl2.php?type=arc">Voir les archives</a>');
			}
		if($_GET['type']=="annonce")
			{
			$ida = $_GET['id'];
			$sql = 'SELECT auteur FROM petitesannonces_tbl WHERE id= "'.$ida.'"' ;
			$req = mysql_query($sql);
			$res = mysql_num_rows($req);
			if($res>0)
				{
				$auteur = mysql_result($req,0,'auteur');
				}
			if($auteur==$_SESSION['pseudo'] || $_SESSION['statut']=="Administrateur") print('<a href="engine=supprannonce2.php?id='.$ida.'">Supprimer cette annonce</a>');
			else
				{
				print('<a href="engine=contacter.php?cible='.$auteur.'&objet=Annonce n°'.$ida.'">Contacter l\'auteur</a>');
				print('<a href="engine=contact.php?annonce='.$ida.'">Signaler</a>');
				}
			}
			

		}
		?>
	</div>
	
	<div id="contenuaitl2">
		<div class="scroll-pane">

        <?php 

		$sql = 'SELECT case1,case2,case3,case4,case5,case6 FROM principal_tbl WHERE id= "'.$_SESSION['id'].'"' ;
		$req = mysql_query($sql);
		$_SESSION['case1'] = mysql_result($req,0,'case1');
		$_SESSION['case2'] = mysql_result($req,0,'case2');
		$_SESSION['case3'] = mysql_result($req,0,'case3');
		$_SESSION['case4'] = mysql_result($req,0,'case4');
		$_SESSION['case5'] = mysql_result($req,0,'case5');
		$_SESSION['case6'] = mysql_result($req,0,'case6');
		
		if ($noWay) {
			echo '<div style="margin-top:50px;color:#c81111;text-align:center;">ERREUR CRITIQUE<br /><br />Votre AITL 2.0 est brouillé par une sorte de virus et ne fonctionne plus !</div>';
		}
		else {
		
		if(statut($_SESSION['statut'])>=2)
		{
			$case1tmp = $_SESSION['case1'];
			$_SESSION['case1'] = "AITL 2.0";
		}
	
		$l = 0;
	
		for($i=1; $i != 7; $i++)
		{
			if(empty($_SESSION['case'.$i])) $_SESSION['case'.$i] = "";
			if($_SESSION['case'.$i] == "AITL 2.0" && $l != 1)
			{
			
			$l = 1;
			
			
			if($_GET['type'] == "tel")
			{
				$sql = 'SELECT auteur FROM citations_tbl WHERE paru= "oui"' ;
				$req = mysql_query($sql);
				$auteur = mysql_result($req,0,'auteur');
				$sql = 'SELECT auteur FROM tips_tbl WHERE paru= "oui"' ;
				$req = mysql_query($sql);
				$tip = mysql_result($req,0,'auteur');
				$sql = 'SELECT redacteur FROM articles_tbl WHERE paru= "oui"' ;
				$req = mysql_query($sql);
				$redacteur = mysql_result($req,0,'redacteur');
				$sql = 'SELECT victime,heure,raison FROM deces_tbl ORDER BY heure' ;
				$req = mysql_query($sql);
				$nbred = mysql_num_rows($req);
				$sql = 'SELECT id FROM petitesannonces_tbl' ;
				$req = mysql_query($sql);
				$nbrean = mysql_num_rows($req);
				$sql = 'SELECT id FROM entreprises_tbl WHERE type!= "CIE" AND type!= "CIPE" AND type!= "proprete" AND type!= "prison" AND type!= "police" AND type!= "chambre" AND type!= "conseil" AND type!= "jeux" AND type!= "DOI" AND type!= "di2rco" AND type!= "dcn"' ;
				$req = mysql_query($sql);
				$corpo = mysql_num_rows($req);
				
				print('
					<div class="titre">
						Canaux Impériaux
					</div>
					<table id="tel">
						<tr>
							<td class="tab1"><a href="engine=aitl2.php?type=adj">Article du jour</a></td>
							<td class="tab2">Par '.$redacteur.'</td>
						</tr>
						<tr>
							<td class="tab1"><a href="engine=aitl2.php?type=cdj">Citation du jour</a></td>
							<td class="tab2">Par '.$auteur.'</td>
						</tr>
						<tr>
							<td class="tab1"><a href="engine=aitl2.php?type=tdj">Tip\' du jour</a></td>
							<td class="tab2">Par '.$tip.'</td>
						</tr>
						<tr>
							<td>&nbsp;</td>
							<td>&nbsp;</td>
						</tr>
						<tr>
							<td class="tab1"><a href="engine=aitl2.php?type=pa">Petites annonces</a></td>
							<td class="tab2">'.$nbrean.'</td>
						</tr>
						<tr>
							<td class="tab1"><a href="engine=aitl2.php?type=corp">Corporations</a></td>
							<td class="tab2">'.$corpo.'</td>
						</tr>
						<tr>
							<td class="tab1"><a href="engine=aitl2.php?type=dec&page=1">Décès</a></td>
							<td class="tab2">'.$nbred.'</td>
						</tr>
						<tr>
							<td class="tab1"><a href="bourse/" onclick="window.open(this.href); return false;">Bourse</a></td>
							<td class="tab2"></td>
						</tr>
					</table>');
			}
			elseif($_GET['type']=="arc")
			{
				print('
					<table>
						<tr>
							<th>Nom du rédacteur</th>
							<th>Titre de l\'article</th>
							<th>Note</th>
						</tr>');
				
				$sql = 'SELECT * FROM articles_tbl WHERE paru= "arc" OR paru= "oui" ORDER BY id DESC' ;
				$req = mysql_query($sql);
				$res = mysql_num_rows($req);
				for( $m = 0 ; $m != $res ; $m++ )
				{
					$ida = mysql_result($req,$m,'id');
					$article = mysql_result($req,$m,'titre');
					$redacteur = mysql_result($req,$m,'redacteur');
					$sqln = 'SELECT * FROM votesarticles_tbl WHERE article= "'.$article.'"' ;
					$reqn = mysql_query($sqln);
					$resn = mysql_num_rows($reqn);
					$note = 0;
					for($n=0;$n!=$resn;$n++)
					{
						$note = $note + mysql_result($reqn,$n,'note');
					}
					if($resn>0)
					{
						$note = $note/$resn;
						$note = substr("$note", 0, 4);
					}
					print('
						<tr>
							<td>'.$redacteur.'</td>
							<td><a href="engine=aitl2.php?type=art&id='.$ida.'">'.$article.'</a></td>
							<td>'.$note.'/10</td>
						</tr>');
				}
				print('</table>');
			}
			elseif($_GET['type']=="pa")
			{
				$sql = 'SELECT * FROM petitesannonces_tbl ORDER BY moment DESC' ;
				$req = mysql_query($sql);
				$res = mysql_num_rows($req);
				
				if($res>0)
				{
					print('
						<table>
							<tr>
								<th>Auteur</th>
								<th>Titre</th>
								<th>Date</th>
							</tr>');
					for( $m = 0 ; $m != $res ; $m++ )
					{
						$ida = mysql_result($req,$m,'id');
						$titre = mysql_result($req,$m,'titre');
						$auteur = mysql_result($req,$m,'auteur');
						$date = date('d/m/y',mysql_result($req,$m,'moment'));
						$heure = date('H\hi',mysql_result($req,$m,'moment'));
						print('
							<tr>
								<td>'.$auteur.'</td>
								<td><a href="engine=aitl2.php?type=annonce&id='.$ida.'"');if($auteur=="Stan"||$auteur=="Cless"||$auteur=="Overflow")print(' style="font-weight:bold;"');print('>'.$titre.'</a></td>
								<td>'.$date.'<br />'.$heure.'</td>
							</tr>');
					}
					print('</table>');
				}
				else print('<div class="texte">Il n\'y a aucune petite annonce pour l\'instant.</div>');
			}
			elseif($_GET['type']=="dec")
			{
				print('
				<table>
					<tr>
						<th>Nom de la victime</th>
						<th>Heure du décès</th>
						<th>Raison du décès</th>
					</tr>');
					
				$sql = 'SELECT victime,heure,raison FROM deces_tbl' ;
				$req = mysql_query($sql);
				$res = mysql_num_rows($req);
				
				for( $m = 0 ; $m != $res ; $m++ )
				{
					$victime = mysql_result($req,$m,'victime');
					$heure = mysql_result($req,$m,'heure');
					$raison = mysql_result($req,$m,'raison');
					print('
						<tr>
							<td>'.$victime.'</td>
							<td>'.date('H\hi', $heure).'</td>
							<td>'.$raison.'</td>
						</tr>');
				}
				print('</table>');
			}
			elseif($_GET['type']=="adj")
			{
				$sql = 'SELECT * FROM articles_tbl WHERE paru= "oui"';
				$req = mysql_query($sql);
				$ida = mysql_result($req,0,'id');
				$redacteur = mysql_result($req,0,'redacteur');
				$titre = mysql_result($req,0,'titre');
				$contenu = mysql_result($req,0,'contenu');
				
				print('<div class="titre">Article de '.$redacteur.' <span class="small">le '.date("d-m-Y").'</span>');
				
				$sql = 'SELECT * FROM votesarticles_tbl WHERE article= "'.$titre.'" AND pseudo= "'.$_SESSION['pseudo'].'"' ;
				$req = mysql_query($sql);
				$resa = mysql_num_rows($req);
				
				if($resa==0)
				{
					print('<br /><span class="small">Noter sur 10 :');
					for($n=1;$n!=11;$n++) print(' <a href="engine=voterarticle2.php?id='.$ida.'&vote='.$n.'">'.$n.'</a>');
				}
				else
				{
					$sqln = 'SELECT * FROM votesarticles_tbl WHERE article= "'.$titre.'"';
					$reqn = mysql_query($sqln);
					$resn = mysql_num_rows($reqn);
					$note = 0;
					for($n=0;$n!=$resn;$n++) $note = $note + mysql_result($reqn,$n,'note');
					$note = $note/$resn;
					$note = substr("$note", 0, 4);
					print('<br /><span class="small">Note : <strong>'.$note.'</strong>/10 ('.$resn.' vote');if($resn>1)print('s');print(')');
				}
				print('</span></div>');
				
				print('<br /><div class="titre">'.$titre.'</div>');
				
				print('<div class="texte">'.$contenu.'<br /><br />
				<div class="exception">Nous vous rappellons qu\'il est possible de rédiger votre propre article.
				Un article correct peut rapporter jusqu\'à 1000 Crédits !</div></div>');
			}
			elseif($_GET['type']=="art")
				{
				$sql = 'SELECT * FROM articles_tbl WHERE id= "'.$_GET['id'].'"' ;
				$req = mysql_query($sql);
				$res = mysql_num_rows($req);
				
				if($res>0)
					{
					$ida = mysql_result($req,0,'id');
					$redacteur = mysql_result($req,0,'redacteur');
					$titre = mysql_result($req,0,'titre');
					$contenu = mysql_result($req,0,'contenu');
					
					print('<div class="titre">Archives : Article de '.$redacteur);
					
					$sql = 'SELECT * FROM votesarticles_tbl WHERE article= "'.$titre.'" AND pseudo= "'.$_SESSION['pseudo'].'"' ;
					$req = mysql_query($sql);
					$resa = mysql_num_rows($req);
					if($resa==0)
						{
						 print('<br /><span class="small">Noter sur 10 :');
						 for($n=1;$n!=11;$n++) print(' <a href="engine=voterarticle2.php?id='.$ida.'&vote='.$n.'">'.$n.'</a>');
						 }
					else
						{
						$sqln = 'SELECT * FROM votesarticles_tbl WHERE article= "'.$titre.'"' ;
						$reqn = mysql_query($sqln);
						$resn = mysql_num_rows($reqn);
						$note = 0;
						for($n=0;$n!=$resn;$n++)
							{
							$note = $note + mysql_result($reqn,$n,'note');
							}
						$note = $note/$resn;
						$note = substr("$note", 0, 4);
						print('<br /><span class="small"><strong>'.$note.'</strong>/10 ('.$resn.' vote');if($resn>1)print('s');print(')');
						}
					
					print('</span></div>');
					
					print('<br /><div class="titre">'.$titre.'</div>');
					print('<div class="texte">'.$contenu.'</div>');
					}
				}
			elseif($_GET['type']=="annonce")
				{
				$sql = 'SELECT * FROM petitesannonces_tbl WHERE id= "'.$_GET['id'].'"' ;
				$req = mysql_query($sql);
				$res = mysql_num_rows($req);
				if($res>0)
					{
					$ida = mysql_result($req,0,'id');
					$auteur = mysql_result($req,0,'auteur');
					$titre = mysql_result($req,0,'titre');
					$annonce = mysql_result($req,0,'annonce');
					print('<div class="titre">Annonce <strong>n°'.$ida.'</strong> de <strong>'.$auteur.'</strong><br /><br /><strong>'.$titre.'</strong></div>');
					print('<div class="texte">'.$annonce.'</div>');
					}
				}
			elseif($_GET['type']=="corp")
				{
				print('
				<table>
					<tr class="exception">
						<th>Class.</th>
						<th>Corporation</th>
						<th>Chiffre</th>
						<th>Capital</th>
						<th>Employés</th>
					</tr>');
					
				$sql = 'SELECT nom,budget,chiffremoyen FROM entreprises_tbl WHERE type!= "CIE" AND type!= "CIPE" AND type!= "proprete" AND type!= "prison" AND type!= "police" AND type!= "chambre" AND type!= "jeux" AND type!= "conseil" AND type!= "DOI" AND type!= "di2rco" AND type!= "dcn" ORDER BY chiffremoyen DESC' ;
				$req = mysql_query($sql);
				$res = mysql_num_rows($req);
				for( $m = 0 ; $m != $res ; $m++ )
					{
					$idc = $m + 1;
					$corporation = mysql_result($req,$m,'nom');
					$capital = mysql_result($req,$m,'budget');
					$chiffre = mysql_result($req,$m,'chiffremoyen');
					$sql1 = 'SELECT nbreactuel FROM `e_'.str_replace(" ","_",''.$corporation.'').'_tbl`' ;
					$req1 = mysql_query($sql1);
					$res1 = mysql_num_rows($req1);
					$nbreemp = 0;
					for($c=0;$c!=$res1;$c++) $nbreemp = $nbreemp + mysql_result($req1,$c,'nbreactuel');
					if($idc<=3)
						{
						print('
						<tr>
							<td><strong>'.$idc.'</strong></td>
							<td><strong>'.$corporation.'</strong></td>
							<td><strong>'.$chiffre.'</strong></td>
							<td><strong>'.$capital.'</strong></td>
							<td><strong>'.$nbreemp.'</strong></td>
						</tr>');
						}
					else
						{
						print('
						<tr>
							<td>'.$idc.'</td>
							<td>'.$corporation.'</td>
							<td>'.$chiffre.'</td>
							<td>'.$capital.'</td>
						 	<td>'.$nbreemp.'</td>
						</tr>');
						}
					}
				print('</table>');
				}
			elseif($_GET['type']=="tdj")
				{
				$sql = 'SELECT * FROM tips_tbl WHERE paru= "oui"' ;
				$req = mysql_query($sql);
				$redacteur = mysql_result($req,0,'auteur');
				$tips = mysql_result($req,0,'tips');
				print('<div class="titre">Tip\' de <strong>'.$redacteur.'</strong> <span class="small">le '.date("d-m-Y").'</span></div>');
				print('<div class="texte">'.$tips.'</div>');
				}
			elseif($_GET['type']=="cdj")
				{
				$sql = 'SELECT * FROM citations_tbl WHERE paru= "oui"' ;
				$req = mysql_query($sql);
				$auteur = mysql_result($req,0,'auteur');
				$citation = mysql_result($req,0,'citation');
				print('<div class="titre">Citation du jour</div>');
				print('<div class="texte">'.$citation.'<br />
				<em class="signe">'.$auteur.'</em></div>');
				}

			elseif($_GET['type'] == "dcn")
				{
				$sql = 'SELECT * FROM DCN_achats_tbl WHERE acheteur="'.$_SESSION['pseudo'].'"' ;
				$req = mysql_query($sql);
				$res = mysql_num_rows($req);
				
				$sql2 = 'SELECT numero FROM DCN_numeros_tbl WHERE paru=1 ORDER BY numero DESC' ;
				$req2 = mysql_query($sql2);
				$sql3 = 'SELECT id FROM DCN_achats_tbl WHERE numero='.mysql_result($req2,0,numero).' AND acheteur="'.$_SESSION['pseudo'].'"' ;
				$req3 = mysql_query($sql3);
				$res3 = mysql_num_rows($req3);
				print('
					<div class="titre">
						DreadCast News
					</div>
					
					<table id="tel">
						<tr>
							<td class="tab1"><a href="engine=aitl2.php?type=dcn_num">Visionner mes numéros achetés</a></td>
						</tr>
						<tr>
							<td class="tab2">');
							if($res != 0) { print('Vous possédez '.$res.' numéro');if($res!=1)print('s');print(' du DCN'); }
							else print('Vous ne possédez aucun numéro du DCN');
							print('</td>
						</tr>');
						if($res3 == 0)
						print('<tr>
							<td class="tab1"><a href="engine=aitl2.php?type=dcn_ach">Télécharger le dernier DC News paru</a></td>
						</tr>
						<tr>
							<td class="tab2">Vous n\'avez pas encore acheté le n°'.mysql_result($req2,0,numero).'</td>
						</tr>');
						else
						print('<tr>
							<td>&nbsp;</td>
						</tr>
						<tr>
							<td>&nbsp;</td>
						</tr>');
						print('<tr>
							<td class="tab1"><a href="engine=go.php?num=439&rue=boulevard%20Agderon">Accéder aux archives du DC News</a></td>
						</tr>
						<tr>
							<td class="tab2">Rendez-vous au QG du DCN</td>
						</tr>
						
					</table>');
				}
			elseif($_GET['type'] == "dcn_num" && $_GET['numero'] == "")
				{
				print('<div class="titre">Vos numéros de DreadCast News</div>');
				
				$sql = 'SELECT * FROM DCN_achats_tbl WHERE acheteur="'.$_SESSION['pseudo'].'" ORDER BY id DESC' ;
				$req = mysql_query($sql);
				$res = mysql_num_rows($req);
				if($res == 0) print('<div class="texte">Vous n\'avez téléchargé aucun numéro de Dreadcast News.</div>');
				else
					{
					print('<table id="tel">');
					
					for($i=0;$i<$res;$i++)
						{
						$sql2 = 'SELECT * FROM DCN_numeros_tbl WHERE numero="'.mysql_result($req,$i,numero).'"' ;
						$req2 = mysql_query($sql2);
						
						print('<tr>
							<td class="tab1"><a href="engine=aitl2.php?type=dcn_num&numero='.mysql_result($req2,0,numero).'">DreadCast News n°'.mysql_result($req2,0,numero).'</a></td>
							<td class="tab2">Paru le '.date("d-m-Y",mysql_result($req2,0,date)).'</td>
						</tr>');
						}
						
					print('</table>');
					}
				}
			elseif($_GET['type'] == "dcn_num" && $_GET['numero'] != "")
				{
				print('<div class="titre">DreadCast News n°'.$_GET['numero'].'</div>');
				
				$sql = 'SELECT * FROM DCN_achats_tbl WHERE numero="'.$_GET['numero'].'" AND acheteur="'.$_SESSION['pseudo'].'"' ;
				$req = mysql_query($sql);
				$res = mysql_num_rows($req);
				if($res == 0) print('<div class="texte">Vous n\'avez pas acheté ce numéro.</div>');
				else
					{
					$sql = 'SELECT * FROM DCN_numeros_tbl WHERE numero="'.$_GET['numero'].'"' ;
					$req = mysql_query($sql);
					
					$texte = mysql_result($req,0,contenu);
					
					print('<div id="affiche_DCN_aitl">'.affiche_DCN($texte).'</div>');
					
					}
				}
			elseif($_GET['type'] == "dcn_ach")
				{
				$sql2 = 'SELECT numero,prix FROM DCN_numeros_tbl WHERE paru=1 ORDER BY numero DESC' ;
				$req2 = mysql_query($sql2);
				
				if($_SESSION['credits'] >= mysql_result($req2,0,prix))
					{
					$_SESSION['credits'] -= mysql_result($req2,0,prix);
					
					$sql = 'UPDATE principal_tbl SET credits = "'.$_SESSION['credits'].'" WHERE pseudo="'.$_SESSION['pseudo'].'"';
					mysql_query($sql);
					
					$sql = 'INSERT INTO DCN_achats_tbl(id,acheteur,numero) VALUES("","'.$_SESSION['pseudo'].'","'.mysql_result($req2,0,numero).'")';
					mysql_query($sql);
					
					$sql = 'SELECT budget,benefices FROM entreprises_tbl WHERE nom= "DC Network"' ;
					$req = mysql_query($sql);
					$budget = mysql_result($req,0,budget);
					$benef = mysql_result($req,0,benefices);
					$pvente = mysql_result($req2,0,prix);
					$budget = $budget + $pvente;
					$benef = $benef + $pvente;
					$sql = 'UPDATE entreprises_tbl SET budget= "'.$budget.'" , benefices= "'.$benef.'" WHERE nom= "DC Network"' ;
					mysql_query($sql);
					
					print('<div style="text-align:center;"><img src="im_objets/barre_telechargement.gif" /><br />
					Téléchargement en cours...</div>
					
					<meta http-equiv="refresh" content="3 ; url=engine=aitl2.php?type=dcn"> ');
					exit();
					}
				else
					print('Vous n\'avez pas assez d\'argent sur vous.<br />
					<a href="engine=aitl2.php?type=dcn">Retour</a>');
				}
			elseif($_GET['type'] == "dctv")
				{
				print('
					<div class="titre">
						DreadCast TeleVision
					</div>
					
					<table id="tel">
						<tr>
							<td class="tab1"><a href="engine=aitl2.php?type=dctv_prog">Visionner la programmation</a></td>
						</tr>
						<tr>
							<td class="tab2">Voir le programme de la semaine en cours</tr>
						<tr>
							<td>&nbsp;</td>
						</tr>');
						
					$sql = 'SELECT * FROM DCN_abonnes_tbl WHERE abonne="'.$_SESSION['pseudo'].'" AND medium="DCTV"' ;
					$req = mysql_query($sql);
					$res = mysql_num_rows($req);
					
					if($res == 0) print('<tr>
							<td class="tab1"><a href="engine=go.php?num=439&rue=boulevard%20Agderon">S\'abonner à la DreadCast TeleVision</a></td>
						</tr>
						<tr>
							<td class="tab2">Rendez-vous au QG de la DC TV</td>
						</tr>
						
					</table>');
					else print('<tr>
							<td class="tab1"><a href="engine=aitl2.php?type=dctv_visio">Regarder la DC TV</a></td>
						</tr>
						<tr>
							<td class="tab2">Jetez un oeil à la chaîne officielle de DreadCast</td>
						</tr>
						
					</table>');
				}
			elseif($_GET['type'] == "dctv_prog")
				{
				print('<div id="programme_aitl2">'.affiche_DCTV_programme(time()).'</div>');
				}
			elseif($_GET['type'] == "dctv_visio")
				{
				
				$sql = 'SELECT id FROM DCN_abonnes_tbl WHERE abonne = "'.$_SESSION['pseudo'].'" AND medium = "DCTV"';
				$req = mysql_query($sql);
				$res = mysql_num_rows($req);
				if($res == 0)
					{
					print('<meta http-equiv="refresh" content="0 ; url=engine=aitl2.php?type=dctv"> ');
					exit();
					}
				
				$sql = 'SELECT date_debut,duree,clip FROM DCN_programmation_tbl WHERE date_debut <= '.time().' ORDER BY date_debut DESC LIMIT 1' ;
				$req = mysql_query($sql);
				$res = mysql_num_rows($req);

				if($res != 0)
					{
					$time_debut = mysql_result($req,0,date_debut);
					$time_fin = $time_debut + mysql_result($req,0,duree)*3600;
					
					if(time() < $time_fin)
						{
						$sql = 'SELECT nom,auteur,lien FROM DCN_clips_tbl WHERE id='.mysql_result($req,0,clip) ;
						$req = mysql_query($sql);
						$res = mysql_num_rows($req);
						
						print('
						<div class="titre">
							'.mysql_result($req,0,nom).'<br />
							<span class="small">'.date('H:i',$time_debut).'-'.date('H:i',$time_fin).' - Par '.mysql_result($req,0,auteur).'</span>
						</div>
						<div class="video">
							'.affiche_DCTV_visio(mysql_result($req,0,lien)).'
						</div>
						');
						$ok=1;
						}
					}
				if($ok != 1)
					{
					print('<div class="titre">
							Aucun clip en cours
						</div>
						<div class="texte">
							Il n\'y a aucun clip diffusé en ce moment. Veuillez vous référer à la <a href="engine=aitl2.php?type=dctv_prog">programmation</a>.
						</div>');
					}
				}
			elseif($_GET['type'] == "upg" && $_SESSION['statut'] != "Administrateur")
				{
				print('
					<div class="titre">
						Upgrades
					</div>
					<div class="titre">Coming soon...</div>
					');
				}
			elseif($_GET['type'] == "upg")
				{
				
				print('
					<div class="titre">
						Upgrades de l\'AITL 2.0
					</div>
					<div class="texte">Voici la liste des upgrades téléchargeables rendues accessibles par l\'Impérium</div>
					<table id="tel">
						<tr>
							<td class="tab1"><a href="engine=aitl2.php?type=cdj">Statistiques</a></td>
							<td class="tab2">A paraître</td>
						</tr>
						<tr>
							<td class="tab1"><a href="engine=aitl2.php?type=adj">Suivi Bancaire</a></td>
							<td class="tab2">Disponible pour '.upg_prix("suivi bancaire").'Cr</td>
						</tr>
					</table>');
				}
			}
		}
		
}

if(statut($_SESSION['statut'])>=2)
	{
	$_SESSION['case1'] = $case1tmp;
	}

if($l!=1)
	{
	print('<meta http-equiv="refresh" content="0 ; url=http://v2.dreadcast.net/ingame/engine.php"> ');
	exit();
	}

mysql_close($db);

?>

		</div>
	</div>
</div>

<?php 

function upg_prix($nom) {
	if($nom == "suivi bancaire") return 400;
}
function upg_possede($nom,$pseudo){
	return false;
}


if($chat=="oui") 	{ if ((date("H")>=21) || (date("H")<=7)) { include("inc_bas_n_c.php"); } else { include("inc_bas_j_c.php"); } } else {	if ((date("H")>=21) || (date("H")<=7)) { include("inc_bas_n.php"); } else { include("inc_bas_j.php"); }	} ?>
<?php include("inc_bas_de_page.php"); ?>
