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
?>
<?php if ((date("H")>=21) || (date("H")<=7)) { include("inc_haut_n.php"); } else { include("inc_haut_j.php");} ?>

<div id="haut">
	<?php include("inc_entete.php"); ?>
	<div class="haut_aligngauche">
		<p>
		<div class="titrepage">
			AITL
		</div>
		<?php
		if(empty($_GET['type']))$_GET['type']="";
		
		if($_GET['type']=="pa") {
			print('<b class="module4ie"><a href="engine=aitl.php?type=tel" class="module4"><img src="im_objets/icon_retour.gif" alt="Retour" />Retour</a></b>');
		}
		elseif($_GET['type']=="adj") {
			print('<b class="module4ie"><a href="engine=aitl.php?type=tel" class="module4"><img src="im_objets/icon_retour.gif" alt="Retour" />Retour</a></b>');
		}
		elseif($_GET['type']=="art") {
			print('<b class="module4ie"><a href="engine=aitl.php?type=arc" class="module4"><img src="im_objets/icon_retour.gif" alt="Retour" />Retour</a></b>');
		}
		elseif($_GET['type']=="arc") {
			print('<b class="module4ie"><a href="engine=aitl.php?type=tel" class="module4"><img src="im_objets/icon_retour.gif" alt="Retour" />Retour</a></b>');
		}
		elseif($_GET['type']=="annonce") {
			print('<b class="module4ie"><a href="engine=aitl.php?type=pa" class="module4"><img src="im_objets/icon_retour.gif" alt="Retour" />Retour</a></b>');
		}
		elseif(($_GET['type']!="tel")&&($_GET['type']!="")) {
			print('<b class="module4ie"><a href="engine=aitl.php?type=tel" class="module4"><img src="im_objets/icon_retour.gif" alt="Retour" />Retour</a></b>');
		}
		else {
			print('<b class="module4ie"><a href="engine=inventaire.php" class="module4"><img src="im_objets/icon_retour.gif" alt="Retour" />Retour</a></b>');
		} ?>
		</p>
	</div>
</div>
<div id="centreaitl">

	<div id="contenuaitl">

        <?php 

		$db = mysql_connect('localhost', 'CENSURE', 'CENSURE') or die("Erreur de connexion au serveur des messages.");
		mysql_select_db('CENSURE',$db) or die("Erreur de connexion a la base de donnees des messages.");

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
				echo '<div style="margin-top:50px;color:#c81111;text-align:center;">ERREUR CRITIQUE<br /><br />Votre AITL est brouillé par une sorte de virus et ne fonctionne plus !</div>';
				//if (estDroide())
				//	echo '<div style="margin-top:20px;text-align:center;">Cependant, vous pouvez le réparer<br /><a href="engine=reparerEvent.php?type=1">Réparer</a></div>';
				$noWay = true;
				$l = 1;
			}
		}
		
		if (!$noWay) {
		
		if(statut($_SESSION['statut'])>=2)
		{
			$caes1bu = $_SESSION['case1'];
			$_SESSION['case1'] = "AITL";
		}
	
		$l = 0;
	
		for($i=1; $i != 7; $i++)
		{
			if(empty($_SESSION['case'.$i.'']))$_SESSION['case'.$i.'']="";
			if(($_SESSION['case'.$i.'']=="AITL") && ($l!=1))
			{
			
			$l = 1;
			
			if($_GET['type']=="tel")
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
				
				print('<p align="center">
						<strong>Assistant Imp&eacute;rial de T&eacute;l&eacute;chargement Libre</strong>
					</p>
					<p align="center"><a href="engine=aitl.php?type=adj">Article du jour</a> (par '.$redacteur.')</p>
					<p align="center"><a href="engine=aitl.php?type=cdj">Citation du jour</a> (par '.$auteur.')<br />
					<a href="engine=aitl.php?type=tdj">Tip\' du jour</a> (par '.$tip.')</p>
					<table border="1" bordercolor="#000000" align="center" cellspacing="0" cellpadding="1">
						<tr>
							<td><div align="center"><a href="engine=aitl.php?type=pa">Petites annonces</a></div></td>
							<td><div align="center">'.$nbrean.'</div></td>
							<td><div align="center"><em>Listing des annonces de particuliers &agrave; particuliers</em></div></td>
						</tr>
						<tr>
							<td><div align="center"><a href="engine=aitl.php?type=corp">Corporations</a></div></td>
							<td><div align="center">'.$corpo.'</div></td><td><div align="center"><em>Quelles sont les entreprises les plus riches ?</em></div></td>
						</tr>
						<tr>
							<td><div align="center"><a href="engine=aitl.php?type=dec&page=1">D&eacute;c&egrave;s</a></div></td>
							<td><div align="center">'.$nbred.'</div></td><td><div align="center"><em>Qui n\'a pas eu de chance aujourd\'hui ? </em></div></td>
						</tr>
						'.(($_SESSION['statut']=="Administrateur")?'<tr>
							<td><div align="center"><a href="bourse/" onclick="window.open(this.href); return false;">Bourse</a></div></td><td><div align="center"></div></td>
							<td><div align="center"><em>Suivez l\'&eacute;volution des entreprises c&ocirc;t&eacute;es en bourse</em></div></td>
						</tr>':'').'
					</table><br /><br />
					<div id="aitlforum"><p align="center"><a href="../wikast/forum=accueil.php" target="_blank">Accéder au forum de DreadCast</a></p></div>');
			}
			elseif($_GET['type']=="arc")
			{
				print('<p align="center">
					<strong>Assistant Imp&eacute;rial de T&eacute;l&eacute;chargement Libre</strong>
				</p>
				<div class="aitlbarre2">
					<table border="1" cellspacing="0" cellpadding="1" align="center">
					<tr bgcolor="#5D5F5F">
						<th scope="col">Nom du rédacteur</th>
						<th scope="col">Titre de l\'article</th>
						<th scope="col">Note</th>
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
					print('<tr>
						<td><div align="center">'.$redacteur.'</div></td>
						<td><div align="center"><a href="engine=aitl.php?type=art&id='.$ida.'">'.$article.'</a></div></td>
						<td><div align="center">'.$note.'/10</div></td>
					</tr>');
				}
				print('</table></div>');
			}
			elseif($_GET['type']=="pa")
			{
				$sql = 'SELECT * FROM petitesannonces_tbl ORDER BY moment DESC' ;
				$req = mysql_query($sql);
				$res = mysql_num_rows($req);
				print('<p align="center">
					<strong>Assistant Imp&eacute;rial de T&eacute;l&eacute;chargement Libre</strong>
				</p>');
				if($res>0)
				{
					print('<div class="aitlbarre2">
						<table border="1" bordercolor="#000000" cellspacing="0" cellpadding="1" align="center">
							<tr bgcolor="#5D5F5F">
								<th scope="col">Auteur</th>
								<th scope="col">Titre</th>
								<th scope="col">Heure</th>
								<th scope="col">Date</th>
							</tr>');
					for( $m = 0 ; $m != $res ; $m++ )
					{
						$ida = mysql_result($req,$m,'id');
						$titre = mysql_result($req,$m,'titre');
						$auteur = mysql_result($req,$m,'auteur');
						$date = date('d/m/y',mysql_result($req,$m,'moment'));
						$heure = date('H\hi',mysql_result($req,$m,'moment'));
						print('<tr>
							<td><div align="center">'.$auteur.'</div></td>
							<td><div align="center"><a href="engine=aitl.php?type=annonce&id='.$ida.'"');if($auteur=="Stan"||$auteur=="Cless"||$auteur=="Overflow")print(' style="color:#ddd;"');print('>'.$titre.'</a></div></td>
							<td><div align="center">'.$heure.'</div></td>
							<td><div align="center">'.$date.'</div></td>
						</tr>');
					}
					print('</table></div><div id="aitlforum"><p align="center"><a href="engine=redigerannonce.php">Poster une annonce</a></p></div>');
				}
				else
				{
					print('Il n\'y a aucune petite annonce pour l\'instant.<div id="aitlforum"><p align="center"><a href="engine=redigerannonce.php">Poster une annonce</a></p></div>');
				}
			}
			elseif($_GET['type']=="dec")
			{
				print('<p align="center">
					<strong>Assistant Imp&eacute;rial de T&eacute;l&eacute;chargement Libre</strong>
				</p>
				<div class="aitlbarre2">
				<table border="0" cellspacing="0" cellpadding="0">
					<tr>
						<th scope="col">Nom de la victime</th>
						<th scope="col">Heure du décès</th>
						<th scope="col">Raison du décès</th>
					</tr>');
				$sql = 'SELECT victime,heure,raison FROM deces_tbl' ;
				$req = mysql_query($sql);
				$res = mysql_num_rows($req);
				for( $m = 0 ; $m != $res ; $m++ )
				{
					$victime = mysql_result($req,$m,'victime');
					$heure = mysql_result($req,$m,'heure');
					$raison = mysql_result($req,$m,'raison');
					print('<tr><td><div align="center">'.$victime.'</div></td>
						<td><div align="center">'.date('H\hi', $heure).'</div></td>
						<td><div align="center">'.$raison.'</div></td>
					</tr>');
				}
				print('</table></div>');
			}
			elseif($_GET['type']=="adj")
			{
				$sql = 'SELECT * FROM articles_tbl WHERE paru= "oui"';
				$req = mysql_query($sql);
				$ida = mysql_result($req,0,'id');
				$redacteur = mysql_result($req,0,'redacteur');
				$titre = mysql_result($req,0,'titre');
				$contenu = mysql_result($req,0,'contenu');
				print('<p align="center">Article de <strong>'.$redacteur.'</strong>, le '.date("d-m-Y").' :');
				$sql = 'SELECT * FROM votesarticles_tbl WHERE article= "'.$titre.'" AND pseudo= "'.$_SESSION['pseudo'].'"' ;
				$req = mysql_query($sql);
				$resa = mysql_num_rows($req);
				if($resa==0)
				{
					 print(' (Noter sur 10:');
					 for($n=1;$n!=11;$n++)
					{
						print(' <a href="engine=voterarticle.php?id='.$ida.'&vote='.$n.'">'.$n.'</a>');
					}
					print(')</p><hr>');
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
					print(' (Note: <strong>'.$note.'</strong>/10, Votes: '.$resn.')</p><hr>');
				}
				print('<p align="center"><strong>'.$titre.'</strong></p>');
				print('<p class= "aitlbarre" align="center"><i>'.$contenu.'</i><br /><br />
				Nous vous rappellons qu\'il est possible de rédiger votre propre article.<br />
				Un article correct peut rapporter jusqu\'à 1000 Crédits !<br />
				<a href="engine=rediger.php">Rédiger votre propre article</a></p><div id="aitlforum"><p align="center"><a href="engine=aitl.php?type=arc">Consulter les archives</a></p></div>');
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
					print('<p align="center"><strong>Archives :</strong> Article de <strong>'.$redacteur.'</strong> :');
					$sql = 'SELECT * FROM votesarticles_tbl WHERE article= "'.$titre.'" AND pseudo= "'.$_SESSION['pseudo'].'"' ;
					$req = mysql_query($sql);
					$resa = mysql_num_rows($req);
					if($resa==0)
						{
						 print(' (Noter sur 10:');
						 for($n=1;$n!=11;$n++)
							{
							print(' <a href="engine=voterarticle.php?id='.$ida.'&vote='.$n.'">'.$n.'</a>');
							}
						print(')</p><hr>');
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
						print(' (Note: <strong>'.$note.'</strong>/10, Votes: '.$resn.')</p><hr>');
						}
					print('<p align="center"><strong>'.$titre.'</strong></p>');
					print('<p class= "aitlbarre" align="center"><i>'.$contenu.'</i></p>');
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
					print('<p align="center">Annonce <strong>n°'.$ida.'</strong> de <strong>'.$auteur.'</strong> :</p><hr>');
					print('<p align="center"><strong>'.$titre.'</strong></p>');
					print('<p class= "aitlbarre" align="center"><i>'.$annonce.'</i></p>
							<div id="aitlforum"><p align="center">');
					if(($auteur==$_SESSION['pseudo']) or ($_SESSION['statut']=="Administrateur") or ($_SESSION['statut']=="Modérateur RPIG"))
						{
						print('<a href="engine=supprannonce.php?id='.$ida.'">Supprimer cette annonce</a>');
						}
					else
						{
						print('<a href="engine=contacter.php?cible='.$auteur.'&objet=Annonce n°'.$ida.'">Contacter l\'auteur</a> - <a href="engine=contact.php?annonce='.$ida.'">Signaler</a>');
						}
					print('</p></div>');
					}
				}
			elseif($_GET['type']=="corp")
				{
				print('<p align="center"><strong>Assistant Imp&eacute;rial de T&eacute;l&eacute;chargement Libre</strong></p>
				<div class="aitlbarre2"><table width="96%" border="1" align="center" cellpadding="0" cellspacing="0">
					<tr bgcolor="#5D5F5F">
				  <th scope="col">Classement</th>
				  <th scope="col">Corporation</th>
				  <th scope="col">Chiffre moyen</th>
				  <th scope="col">Capital</th>
				  <th scope="col">Nombre d\'employés</th>
				</tr>');
				$sql = 'SELECT nom,budget,chiffremoyen FROM entreprises_tbl WHERE type!= "CIE" AND type!= "CIPE" AND type!= "proprete" AND type!= "prison" AND type!= "police" AND type!= "chambre" AND type!= "jeux" AND type!= "conseil" AND type!= "DOI" AND type!= "di2rco" AND type!= "aucun" AND type!= "dcn" ORDER BY chiffremoyen DESC' ;
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
					for($c=0;$c!=$res1;$c++)
						{
						$nbreemp = $nbreemp + mysql_result($req1,$c,'nbreactuel');
						}
					if($idc<=3)
						{
						print('<tr>
						  <td><div align="center"><strong>'.$idc.'</strong></div></td>
						  <td><div align="center"><strong>'.$corporation.'</strong></div></td>
						  <td><div align="center"><strong>'.$chiffre.'</strong></div></td>
						  <td><div align="center"><strong>'.$capital.'</strong></div></td>
						  <td><div align="center"><strong>'.$nbreemp.'</strong></div></td>
						</tr>');
						}
					else
						{
						print('<tr>
						  <td><div align="center">'.$idc.'</div></td>
						  <td><div align="center">'.$corporation.'</div></td>
						  <td><div align="center">'.$chiffre.'</div></td>
						  <td><div align="center">'.$capital.'</div></td>
						  <td><div align="center">'.$nbreemp.'</div></td>
						</tr>');
						}
					}
				print('</table></div>');
				}
			elseif($_GET['type']=="tdj")
				{
				$sql = 'SELECT * FROM tips_tbl WHERE paru= "oui"' ;
				$req = mysql_query($sql);
				$redacteur = mysql_result($req,0,'auteur');
				$tips = mysql_result($req,0,'tips');
				print('<hr><p align="center">Tip\' de <strong>'.$redacteur.'</strong>, le '.date("d-m-Y").' :</p><hr>');
				print('<p align="center"><i>'.$tips.'</i></p>');
				}
			elseif($_GET['type']=="cdj")
				{
				$sql = 'SELECT * FROM citations_tbl WHERE paru= "oui"' ;
				$req = mysql_query($sql);
				$auteur = mysql_result($req,0,'auteur');
				$citation = mysql_result($req,0,'citation');
				print('<p align="center"><strong>Citation du jour :</strong></p><hr>');
				print('<p align="center"><i>'.$citation.'</i></p>');
				print('<table width="40%"  border="0" align="center"><tr><td><p align="right">'.$auteur.'</p></td></tr></table>');
				}
			else
				{
				print('<div id="aitlforum"><p align="center"><a href="engine=aitl.php?type=tel">T&eacute;l&eacute;charger les informations</a></p></div>');
				}
			}
		}

}

if(statut($_SESSION['statut'])>=2)
	{
	$_SESSION['case1'] = $caes1bu;
	}

if($l!=1)
	{
	print('<meta http-equiv="refresh" content="0 ; url=http://v2.dreadcast.net/"> ');
	exit();
	}

mysql_close($db);

?>


</div>
</div>
<?php if($chat=="oui") 	{ if ((date("H")>=21) || (date("H")<=7)) { include("inc_bas_n_c.php"); } else { include("inc_bas_j_c.php"); } } else {	if ((date("H")>=21) || (date("H")<=7)) { include("inc_bas_n.php"); } else { include("inc_bas_j.php"); }	} ?>
<?php include("inc_bas_de_page.php"); ?>
