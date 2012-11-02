<?php
session_start();

if($_SESSION['id']=="")
	{
	print('<meta http-equiv="refresh" content="0 ; url=index.php"> ');
	exit();
	}

if($_GET['article']=="")
	{
	print('<meta http-equiv="refresh" content="0 ; url=edc.php"> ');
	exit();
	}
	
$idarticle = $_GET['article'];

if($_POST['titre']=="" AND $_POST['message']=="")
	{
	$db = mysql_connect('localhost', 'CENSURE', 'CENSURE') or die("Erreur de connexion au serveur des messages.");
	mysql_select_db('CENSURE',$db) or die("Erreur de connexion a la base de donnees des messages.");
	
	if(!ereg('[0-9]',$_GET['article']))
		{
		$sql = 'SELECT * FROM wikast_edc_articles_tbl WHERE auteur="'.$_SESSION['pseudo'].'" AND titre="Pr&eacute;sentation"';
		$req = mysql_query($sql);
		$res = mysql_num_rows($req);
		}
	else
		{
		$sql = 'SELECT * FROM wikast_edc_articles_tbl WHERE auteur="'.$_SESSION['pseudo'].'" AND id="'.$_GET['article'].'"';
		$req = mysql_query($sql);
		$res = mysql_num_rows($req);
		}
					
	if($res == 0 AND $_SESSION['statut'] != "Administrateur")
		{
		print('<meta http-equiv="refresh" content="0 ; url=edc.php"> ');
		exit();
		}
	
	$erreurtitre = mysql_result($req,0,titre);
	$erreurtexte = mysql_result($req,0,contenu);
	
	$erreurtexte = str_replace("<br />", "",$erreurtexte);
	
	mysql_close($db);
	}
elseif($_POST['titre']=="" OR $_POST['message']=="")
	{
	$erreurtitre = $_POST['titre'];
	$erreurtexte = $_POST['message'];
	$idarticle = $_GET['article'];
	}
else
	{
	$titrearticle = stripslashes(htmlentities($_POST['titre'],ENT_QUOTES));
	
	$message = stripslashes(htmlentities($_POST['message'],ENT_QUOTES));
	$message = str_replace("\n", "<br />",$message);
	
	$db = mysql_connect('localhost', 'CENSURE', 'CENSURE') or die("Erreur de connexion au serveur des messages.");
	mysql_select_db('CENSURE',$db) or die("Erreur de connexion a la base de donnees des messages.");
	
	if($_POST['titre']=="Présentation") $sql = 'UPDATE wikast_edc_articles_tbl SET contenu="'.$message.'" WHERE auteur="'.$_SESSION['pseudo'].'" AND titre="Pr&eacute;sentation"';
	else $sql = 'UPDATE wikast_edc_articles_tbl SET titre="'.$titrearticle.'", contenu="'.$message.'" WHERE id="'.$idarticle.'"';
	mysql_query($sql);
	
	mysql_close($db);
	
	print('<meta http-equiv="refresh" content="0 ; url=edc.php?page1='.$_GET['page1'].'&page2='.$_GET['page2'].'&feuille='.$_GET['feuille'].'"> ');
	exit();
	}

include('include/inc_head.php'); ?>

		<div id="page">
		
			<?php include('include/inc_barre2.php'); ?>
		
			<a href="wiki=accueil.php" id="lien-wiki"></a>
			<?php if($_SESSION['id']!="") print('<a href="edc.php" id="lien-edc"></a>'); ?>
			
			<!-- -------------------------------------------------------------------------- MISE EN PAGE GENERALE -------------------------------------------------------------------------- -->
			<div id="page-EDC">
				
				<?php
				
				include('include/inc_barreliens2.php');
				
				$actuel = ($_GET['actuel']!="")? $_GET['actuel'] : "articles";
				$pagearticles = ($_GET['page1']!="")? $_GET['page1'] : "1";
				$pageetoiles = ($_GET['page2']!="")? $_GET['page2'] : "1";
				
				$db = mysql_connect('localhost', 'CENSURE', 'CENSURE') or die("Erreur de connexion au serveur des messages.");
				mysql_select_db('CENSURE',$db) or die("Erreur de connexion a la base de donnees des messages.");
				
				$sql1 = 'SELECT * FROM wikast_edc_articles_tbl WHERE auteur="'.$_SESSION['pseudo'].'" ORDER BY date DESC';
				$req1 = mysql_query($sql1);
				$res1 = mysql_num_rows($req1);
					
				$sql2 = 'SELECT * FROM wikast_edc_etoiles_tbl WHERE auteur="'.$_SESSION['pseudo'].'" ORDER BY id DESC';
				$req2 = mysql_query($sql2);
				$res2 = mysql_num_rows($req2);
					
				// SECURITE
					
				if(($pagearticles-1)*5 > $res1) $pagearticles = 1;
				if(($pageetoiles-1)*5 > $res2) $pageetoiles = 1;
				
				// GESTION DES PAGES
					
				$debut1 = ($pagearticles-1)*5;
				$max1 = ($pagearticles == ceil($res1 / 5))? $res1 % 5 : 5 ; // PAGEARTICLE = DERNIERE PAGE ?
				$max1 = ($res1 == 0)? 0 : $max1;
				$max1 = ($res1 != 0 && $max1 == 0)? 5 : $max1; // SI REPONSES ET MODULO 0 ALORS VAUT 5
				$fin1 = $debut1 + $max1;
				
				$debut2 = ($pageetoiles-1)*5;
				$max2 = ($pageetoiles == ceil($res2 / 5))? $res2 % 5 : 5 ; // PAGEETOILE = DERNIERE PAGE ?
				$max2 = ($res2 == 0)? 0 : $max2;
				$max2 = ($res2 != 0 AND $max2 == 0)? 5 : $max2; // SI REPONSES ET MODULO 0 ALORS VAUT 5
				$fin2 = $debut2 + $max2;
				
				if(statut($_SESSION['statut'])>=2)
					{
					
					print('
				<div id="zone-etoiles">
					<p class="titre">Mes derni&egrave;res &eacute;toiles</p>
					<p class="image"></p>
					<ul>
						');
																	//AFFICHAGE DES ETOILES
					for($i = $debut2 ; $i < $fin2 ; $i++)
						{
						print('<li><a href="edc=visio.php?auteur='.mysql_result($req2,$i,cible).'">EDC de '.mysql_result($req2,$i,cible).'</a></li>');
						}
						
					print('</ul>
					<div class="pages">
						<p>');
						
						  $pagemax = ceil($res2 / 5);

							$pagem2 = $pageetoiles - 2;
							$pagem1 = $pageetoiles - 1;
							$pagep2 = $pageetoiles + 2;
							$pagep1 = $pageetoiles + 1;
							
							if($pageetoiles > 1)
								{
								print('<a href="edc.php?page1='.$pagearticles.'&page2='.$pagem1.'&feuille='.$_GET['feuille'].'" class="lien1"><</a> ');
								}
							else
								{
								print('<span class="lien1bis"><</span> ');
								}
								
							if($pageetoiles > 4)
								{
								print('<a href="edc.php?page1='.$pagearticles.'&page2=1&feuille='.$_GET['feuille'].'" class="lien2">1</a> ... ');
								}
							elseif($pageetoiles==4)
								{
								print('<a href="edc.php?page1='.$pagearticles.'&page2=1&feuille='.$_GET['feuille'].'" class="lien2">1</a> ');
								}
							if($pagem2 > 0)
								{
								print('<a href="edc.php?page1='.$pagearticles.'&page2='.$pagem2.'&feuille='.$_GET['feuille'].'" class="lien2">'.$pagem2.'</a> ');
								}
							if($pagem1 > 0)
								{
								print('<a href="edc.php?page1='.$pagearticles.'&page2='.$pagem1.'&feuille='.$_GET['feuille'].'" class="lien2">'.$pagem1.'</a> ');
								}
								
							print('<span class="lien2bis">'.$pageetoiles.'</span> ');

							if($pagep1 <= $pagemax)
								{
								print('<a href="edc.php?page1='.$pagearticles.'&page2='.$pagep1.'&feuille='.$_GET['feuille'].'" class="lien2">'.$pagep1.'</a> ');
								}
							if($pagep2 <= $pagemax)
								{
								print('<a href="edc.php?page1='.$pagearticles.'&page2='.$pagep2.'&feuille='.$_GET['feuille'].'" class="lien2">'.$pagep2.'</a> ');
								}
							if($pagemax > $pagep2)
								{
								print('... <a href="edc.php?page1='.$pagearticles.'&page2='.$pagemax.'&feuille='.$_GET['feuille'].'" class="lien2">'.$pagemax.'</a> <a href="edc.php?page1='.$pagearticles.'&page2='.$pagep1.'&feuille='.$_GET['feuille'].'" class="lien3">></a>');
								}
								
							if($pageetoiles < $pagemax)
								{
								print('<a href="edc.php?page1='.$pagearticles.'&page2='.$pagep1.'&feuille='.$_GET['feuille'].'" class="lien3">></a> ');
								}
							else
								{
								print('<span class="lien3bis">></span> ');
								}
									
						
					print('</p></div>
					<p class="actions">
						<a href="edc=afficher.php?type=21" class="lien1">Voir mes &eacute;toiles donn&eacute;es</a><br />
						<a href="edc=afficher.php?type=22" class="lien2">Voir mes &eacute;toiles re&ccedil;ues</a><br />
						');
						if($_SESSION['case1']=="Carnet" OR $_SESSION['case2']=="Carnet" OR $_SESSION['case3']=="Carnet" OR $_SESSION['case4']=="Carnet" OR $_SESSION['case5']=="Carnet" OR $_SESSION['case6']=="Carnet")
							print('<a href="../ingame/engine=carnet.php" class="lien3">Voir mes contacts</a>');
						print('
					</p>
				</div>
					');
					}
				else
					{
					print('
					<div id="zone-gauche">
					<p class="image"></p>
					<div class="google">
						<script type="text/javascript"><!--
							google_ad_client = "pub-9377415436429871";
							google_ad_slot = "7651668647";
							google_ad_width = 200;
							google_ad_height = 200;
						//--></script>
						<script type="text/javascript"
							src="http://pagead2.googlesyndication.com/pagead/show_ads.js">
						</script>
					</div>
					');
										
					if($actuel=="articles")			//SI ON AFFICHE LES ARTICLES
						{
						
						print('
					<p class="actions">
						<a href="edc=afficher.php?type=21&page1='.$pagearticles.'&page2='.$pageetoiles.'&actuel=etoiles" class="lien1">Voir mes &eacute;toiles donn&eacute;es</a><br />
						<a href="edc=afficher.php?type=22&page1='.$pagearticles.'&page2='.$pageetoiles.'&actuel='.$actuel.'" class="lien2">Voir mes &eacute;toiles re&ccedil;ues</a><br />
						');
						if($_SESSION['case1']=="Carnet" OR $_SESSION['case2']=="Carnet" OR $_SESSION['case3']=="Carnet" OR $_SESSION['case4']=="Carnet" OR $_SESSION['case5']=="Carnet" OR $_SESSION['case6']=="Carnet")
							print('<a href="../ingame/engine=carnet.php" class="lien3">Voir mes contacts</a>');
						print('
					</p>
						');
						}
					elseif($actuel=="etoiles")		//SI ON AFFICHE LES ETOILES
						{
						
						print('
					<p class="actions">
						<a href="edc=afficher.php?type=1&page1='.$pagearticles.'&page2='.$pageetoiles.'&actuel=articles" class="lien1">Voir mes articles</a><br />
						<a href="edc=nouveau.php?page1='.$pagearticles.'&page2='.$pageetoiles.'&actuel='.$actuel.'" class="lien2">Cr&eacute;er un nouvel article</a>
					</p>
						');
						}
					
					print('
					</div>
					');
					}
				
				///////////////////////////////////////////////////////// CENTRE /////////////////////////////////////////////////////////
				
				if($_GET['feuille']=="")
					{
					$dernierid = mysql_result($req1,0,"id");
					$derniertitre = mysql_result($req1,0,"titre");
					$dernierdate = mysql_result($req1,0,"date");
					$derniercontenu = mysql_result($req1,0,"contenu");

					$sql = 'SELECT * FROM wikast_edc_commentaires_tbl WHERE article="'.$dernierid.'" ORDER BY date DESC';
					$req = mysql_query($sql);
					$res4 = mysql_num_rows($req);

					if($res4!=0) $nouveaucommentaire = mysql_result($req,0,nouveau);
					else $nouveaucommentaire = "non";
					}
				else
					{
					$sql = 'SELECT * FROM wikast_edc_articles_tbl WHERE auteur="'.$_SESSION['pseudo'].'" AND id="'.$_GET['feuille'].'"';
					$req = mysql_query($sql);
					$res = mysql_num_rows($req);
					
					if($res>0)
						{
						$dernierid = mysql_result($req,0,"id");
						$derniertitre = mysql_result($req,0,"titre");
						$dernierdate = mysql_result($req,0,"date");
						$derniercontenu = mysql_result($req,0,"contenu");
						
						$sql = 'SELECT * FROM wikast_edc_commentaires_tbl WHERE article="'.$dernierid.'" ORDER BY date DESC';
						$req = mysql_query($sql);
						$res4 = mysql_num_rows($req);

						if($res4!=0) $nouveaucommentaire = mysql_result($req,0,nouveau);
						else $nouveaucommentaire = "non";
						}
					}
				
				if(ereg("\[quote\]",$derniercontenu) && ereg("\[/quote\]",$derniercontenu))
					{
					$derniercontenu = str_replace("[quote]", "<div class=\"text-quote\">",$derniercontenu);
					$derniercontenu = str_replace("[/quote]", "<!--quote--></div>",$derniercontenu);
					}
				if(ereg("\[g\]",$derniercontenu) && ereg("\[/g\]",$derniercontenu))
					{
					$derniercontenu = str_replace("[g]", "<strong>",$derniercontenu);
					$derniercontenu = str_replace("[/g]", "</strong>",$derniercontenu);
					}
				if(ereg("\[i\]",$derniercontenu) && ereg("\[/i\]",$derniercontenu))
					{
					$derniercontenu = str_replace("[i]", "<em>",$derniercontenu);
					$derniercontenu = str_replace("[/i]", "</em>",$derniercontenu);
					}
				if(ereg("\[img url=&lt;",$derniercontenu) && ereg("&gt; /\]",$derniercontenu))
					{
					$derniercontenu = str_replace("[img url=&lt;", "<img src=\"",$derniercontenu);
					$derniercontenu = str_replace("&gt; /]", "\" />",$derniercontenu);
					}
				if(ereg("\[lien url=&lt;",$derniercontenu) && ereg("&gt;\]",$derniercontenu) && ereg("\[/lien\]",$derniercontenu))
					{
					$derniercontenu = str_replace("[lien url=&lt;", "<a href=\"",$derniercontenu);
					$derniercontenu = str_replace("&gt;]", "\">",$derniercontenu);
					$derniercontenu = str_replace("[/lien]", "</a>",$derniercontenu);
					}
				if(ereg("\[centrer\]",$derniercontenu) && ereg("\[/centrer\]",$derniercontenu))
					{
					$derniercontenu = str_replace("[centrer]", "<center>",$derniercontenu);
					$derniercontenu = str_replace("[/centrer]", "</center>",$derniercontenu);
					}
				$derniercontenu = str_replace("&lt;br /&gt;", "",$derniercontenu);
				
				if($derniertitre == "Pr&eacute;sentation") $dernierid = "Pr&eacute;sentation";
				
				print('<div id="zone-centre">
					<form action="edc=editer.php?article='.$idarticle.'&actuel='.$actuel.'&page1='.$_GET['page1'].'&page2='.$_GET['page2'].'&feuille='.$_GET['feuille'].'" method="post" id="champ" name="poster">
					'); if($erreurtitre != "Pr&eacute;sentation") print('<input name="titre" type="text" value="'.$erreurtitre.'" class="titre" />'); else print('<p class="titre">'.$erreurtitre.'</p><input name="titre" type="hidden" value="Présentation" />'); print('
					<p class="image"></p>
					<div class="texte">
						<div class="style1">
								<textarea name="message" id="textarea">'.$erreurtexte.'</textarea><br />
								<div id="DCcode">
									<a href="javascript:AddText(\'[g]\',\'\',\'[/g]\');" title="Mettre le texte en gras" class="style1"><strong>G</strong></a>
									<a href="javascript:AddText(\'[i]\',\'\',\'[/i]\');" title="Mettre le texte en italique" class="style1"><em>I</em></a>
									<a href="javascript:AddText(\'[img url=<URL DE VOTRE IMAGE> /]\',\'\',\'\');" title="Afficher une image">IMG</a>
									<a href="javascript:AddText(\'[lien url=<URL DE VOTRE LIEN>]\',\'\',\'[/lien]\');" title="Cr&eacute;er un lien">LIEN</a>
									<a href="javascript:AddText(\'[quote]\',\'\',\'[/quote]\');" title="Citer un texte">QUOTE</a>
									<a href="javascript:AddText(\'[centrer]\',\'\',\'[/centrer]\');" title="Centrer un texte">CENTRER</a>
								</div>
								<input name="submit" type="submit" value="Envoyer" id="ok" />
								<input name="type" type="hidden" value="reponse" />
							</form>
						</div>
					</div>
				</div>');
				
				
				if(statut($_SESSION['statut'])>=2)
					{
					print('
				<div id="zone-articles">
					<p class="titre">Mes derniers articles</p>
					<p class="image"></p>
					<ul>
						');
						
					for($i = $debut1 ; $i < $fin1 ; $i++)
						{
						print('<li><a href="edc.php?page1='.$pagearticles.'&page2='.$pageetoiles.'&feuille='.mysql_result($req1,$i,id).'">'.mysql_result($req1,$i,titre).'</a></li>');
						}

					print('</ul>
					<div class="pages">
						<p>');
						
						$pagemax = ceil($res1 / 5);

							$pagem2 = $pagearticles - 2;
							$pagem1 = $pagearticles - 1;
							$pagep2 = $pagearticles + 2;
							$pagep1 = $pagearticles + 1;
							
							if($pagearticles > 1)
								{
								print('<a href="edc.php?page1='.$pagem1.'&page2='.$pageetoiles.'&feuille='.$_GET['feuille'].'" class="lien1"><</a> ');
								}
							else
								{
								print('<span class="lien1bis"><</span> ');
								}
								
							if($pagearticles > 4)
								{
								print('<a href="edc.php?page1=1&page2='.$pageetoiles.'&feuille='.$_GET['feuille'].'" class="lien2">1</a> ... ');
								}
							elseif($pagearticles==4)
								{
								print('<a href="edc.php?page1=1&page2='.$pageetoiles.'&feuille='.$_GET['feuille'].'" class="lien2">1</a> ');
								}
							if($pagem2 > 0)
								{
								print('<a href="edc.php?page1='.$pagem2.'&page2='.$pageetoiles.'&feuille='.$_GET['feuille'].'" class="lien2">'.$pagem2.'</a> ');
								}
							if($pagem1 > 0)
								{
								print('<a href="edc.php?page1='.$pagem1.'&page2='.$pageetoiles.'&feuille='.$_GET['feuille'].'" class="lien2">'.$pagem1.'</a> ');
								}
								
							print('<span class="lien2bis">'.$pagearticles.'</span> ');

							if($pagep1 <= $pagemax)
								{
								print('<a href="edc.php?page1='.$pagep1.'&page2='.$pageetoiles.'&feuille='.$_GET['feuille'].'" class="lien2">'.$pagep1.'</a> ');
								}
							if($pagep2 <= $pagemax)
								{
								print('<a href="edc.php?page1='.$pagep2.'&page2='.$pageetoiles.'&feuille='.$_GET['feuille'].'" class="lien2">'.$pagep2.'</a> ');
								}
							if($pagemax > $pagep2)
								{
								print('... <a href="edc.php?page1='.$pagemax.'&page2='.$pageetoiles.'&feuille='.$_GET['feuille'].'" class="lien2">'.$pagemax.'</a> <a href="edc.php?page1='.$pagearticles.'&page2='.$pagep1.'&feuille='.$_GET['feuille'].'" class="lien3">></a>');
								}
								
							if($pagearticles < $pagemax)
								{
								print('<a href="edc.php?page1='.$pagep1.'&page2='.$pageetoiles.'&feuille='.$_GET['feuille'].'" class="lien3">></a> ');
								}
							else
								{
								print('<span class="lien3bis">></span> ');
								}
						
						print('</p>
					</div>
					<p class="actions">
						<a href="edc=nouveau.php" class="lien1">Nouvel article</a><br />
						<a href="edc=afficher.php?type=1" class="lien2">Voir tous mes articles</a><br />
						<a href="edc=modifier.php?article=Présentation" class="lien3">Modifier ma pr&eacute;sentation</a><br />
						<a href="edc=infos.php" class="lien4">Changer mes infos perso</a><br />
					</p>
				</div>
					');
					}
				else							// SI PAS VIP
					{
					print('
					<div id="zone-articles">
					');
					
					if($actuel=="articles")		//SI ON AFFICHE LES ARTICLES
						{						
						print('
					<p class="titre">Mes derniers articles</p>
					<p class="image"></p>
					<a href="edc=nouveau.php?actuel=etoiles" class="lienimage2"></a>
					<ul>
						');
						
					for($i = $debut1 ; $i < $fin1 ; $i++)
						{
						print('<li><a href="edc.php?page1='.$pagearticles.'&page2='.$pageetoiles.'&feuille='.mysql_result($req1,$i,id).'">'.mysql_result($req1,$i,titre).'</a></li>');
						}

					print('</ul>
					<div class="pages">
						<p>');
						
						$pagemax = ceil($res1 / 5);

							$pagem2 = $pagearticles - 2;
							$pagem1 = $pagearticles - 1;
							$pagep2 = $pagearticles + 2;
							$pagep1 = $pagearticles + 1;
							
							if($pagearticles > 1)
								{
								print('<a href="edc=nouveau.php?actuel='.$actuel.'&page1='.$pagem1.'&page2='.$pageetoiles.'&feuille='.$_GET['feuille'].'" class="lien1"><</a> ');
								}
							else
								{
								print('<span class="lien1bis"><</span> ');
								}
								
							if($pagearticles > 4)
								{
								print('<a href="edc=nouveau.php?actuel='.$actuel.'&page1=1&page2='.$pageetoiles.'&feuille='.$_GET['feuille'].'" class="lien2">1</a> ... ');
								}
							elseif($pagearticles==4)
								{
								print('<a href="edc=nouveau.php?actuel='.$actuel.'&page1=1&page2='.$pageetoiles.'&feuille='.$_GET['feuille'].'" class="lien2">1</a> ');
								}
							if($pagem2 > 0)
								{
								print('<a href="edc=nouveau.php?actuel='.$actuel.'&page1='.$pagem2.'&page2='.$pageetoiles.'&feuille='.$_GET['feuille'].'" class="lien2">'.$pagem2.'</a> ');
								}
							if($pagem1 > 0)
								{
								print('<a href="edc=nouveau.php?actuel='.$actuel.'&page1='.$pagem1.'&page2='.$pageetoiles.'&feuille='.$_GET['feuille'].'" class="lien2">'.$pagem1.'</a> ');
								}
								
							print('<span class="lien2bis">'.$pagearticles.'</span> ');

							if($pagep1 <= $pagemax)
								{
								print('<a href="edc=nouveau.php?actuel='.$actuel.'&page1='.$pagep1.'&page2='.$pageetoiles.'&feuille='.$_GET['feuille'].'" class="lien2">'.$pagep1.'</a> ');
								}
							if($pagep2 <= $pagemax)
								{
								print('<a href="edc=nouveau.php?actuel='.$actuel.'&page1='.$pagep2.'&page2='.$pageetoiles.'&feuille='.$_GET['feuille'].'" class="lien2">'.$pagep2.'</a> ');
								}
							if($pagemax > $pagep2)
								{
								print('... <a href="edc=nouveau.php?actuel='.$actuel.'&page1='.$pagemax.'&page2='.$pageetoiles.'&feuille='.$_GET['feuille'].'" class="lien2">'.$pagemax.'</a> <a href="edc.php?page1='.$pagearticles.'&page2='.$pagep1.'&feuille='.$_GET['feuille'].'" class="lien3">></a>');
								}
								
							if($pagearticles < $pagemax)
								{
								print('<a href="edc=nouveau.php?actuel='.$actuel.'&page1='.$pagep1.'&page2='.$pageetoiles.'&feuille='.$_GET['feuille'].'" class="lien3">></a> ');
								}
							else
								{
								print('<span class="lien3bis">></span> ');
								}
						
						print('</p>
					</div>
					<p class="actions">
						<a href="edc=nouveau.php?page1='.$pagearticles.'&page2='.$pageetoiles.'&actuel='.$actuel.'" class="lien1">Nouvel article</a><br />
						<a href="edc=afficher.php?type=1&page1='.$pagearticles.'&page2='.$pageetoiles.'&actuel=articles" class="lien2">Voir tous mes articles</a><br />
						<a href="edc=modifier.php?article=Présentation&page1='.$pagearticles.'&page2='.$pageetoiles.'&actuel='.$actuel.'" class="lien3">Modifier ma pr&eacute;sentation</a><br />
						<a href="edc=infos.php?page1='.$pagearticles.'&page2='.$pageetoiles.'&actuel='.$actuel.'" class="lien4">Changer mes infos perso</a><br />
					</p>
						
						');
						}
					elseif($actuel=="etoiles")		//SI ON AFFICHE LES ETOILES
						{
						
						print('
					<p class="titre">Mes derni&egrave;res &eacute;toiles</p>
					<p class="image"></p>
					<a href="edc=nouveau.php?actuel=articles" class="lienimage1"></a>
					<ul>
						');
																	//AFFICHAGE DES ETOILES
					for($i = $debut2 ; $i < $fin2 ; $i++)
						{
						print('<li><a href="edc=visio.php?auteur='.mysql_result($req2,$i,cible).'">EDC de '.mysql_result($req2,$i,cible).'</a></li>');
						}
						
					print('</ul>
					<div class="pages">
						<p>');
						
						$pagemax = ceil($res2 / 5);

							$pagem2 = $pageetoiles - 2;
							$pagem1 = $pageetoiles - 1;
							$pagep2 = $pageetoiles + 2;
							$pagep1 = $pageetoiles + 1;
							
							if($pageetoiles > 1)
								{
								print('<a href="edc=nouveau.php?actuel='.$actuel.'&page1='.$pagearticles.'&page2='.$pagem1.'&feuille='.$_GET['feuille'].'" class="lien1"><</a> ');
								}
							else
								{
								print('<span class="lien1bis"><</span> ');
								}
								
							if($pageetoiles > 4)
								{
								print('<a href="edc=nouveau.php?actuel='.$actuel.'&page1='.$pagearticles.'&page2=1&feuille='.$_GET['feuille'].'" class="lien2">1</a> ... ');
								}
							elseif($pageetoiles==4)
								{
								print('<a href="edc=nouveau.php?actuel='.$actuel.'&page1='.$pagearticles.'&page2=1&feuille='.$_GET['feuille'].'" class="lien2">1</a> ');
								}
							if($pagem2 > 0)
								{
								print('<a href="edc=nouveau.php?actuel='.$actuel.'&page1='.$pagearticles.'&page2='.$pagem2.'&feuille='.$_GET['feuille'].'" class="lien2">'.$pagem2.'</a> ');
								}
							if($pagem1 > 0)
								{
								print('<a href="edc=nouveau.php?actuel='.$actuel.'&page1='.$pagearticles.'&page2='.$pagem1.'&feuille='.$_GET['feuille'].'" class="lien2">'.$pagem1.'</a> ');
								}
								
							print('<span class="lien2bis">'.$pageetoiles.'</span> ');

							if($pagep1 <= $pagemax)
								{
								print('<a href="edc=nouveau.php?actuel='.$actuel.'&page1='.$pagearticles.'&page2='.$pagep1.'&feuille='.$_GET['feuille'].'" class="lien2">'.$pagep1.'</a> ');
								}
							if($pagep2 <= $pagemax)
								{
								print('<a href="edc=nouveau.php?actuel='.$actuel.'&page1='.$pagearticles.'&page2='.$pagep2.'&feuille='.$_GET['feuille'].'" class="lien2">'.$pagep2.'</a> ');
								}
							if($pagemax > $pagep2)
								{
								print('... <a href="edc=nouveau.php?actuel='.$actuel.'&page1='.$pagearticles.'&page2='.$pagemax.'&feuille='.$_GET['feuille'].'" class="lien2">'.$pagemax.'</a> <a href="edc.php?page1='.$pagearticles.'&page2='.$pagep1.'&feuille='.$_GET['feuille'].'" class="lien3">></a>');
								}
								
							if($pageetoiles < $pagemax)
								{
								print('<a href="edc=nouveau.php?actuel='.$actuel.'&page1='.$pagearticles.'&page2='.$pagep1.'&feuille='.$_GET['feuille'].'" class="lien3">></a> ');
								}
							else
								{
								print('<span class="lien3bis">></span> ');
								}
						
						print('</p>
					</div>
					<p class="actions">
						<a href="edc=afficher.php?type=21&page1='.$pagearticles.'&page2='.$pageetoiles.'&actuel='.$actuel.'" class="lien1">Voir mes &eacute;toiles donn&eacute;es</a><br />
						<a href="edc=afficher.php?type=22&page1='.$pagearticles.'&page2='.$pageetoiles.'&actuel='.$actuel.'" class="lien2">Voir mes &eacute;toiles re&ccedil;ues</a><br />
						');
						if($_SESSION['case1']=="Carnet" OR $_SESSION['case2']=="Carnet" OR $_SESSION['case3']=="Carnet" OR $_SESSION['case4']=="Carnet" OR $_SESSION['case5']=="Carnet" OR $_SESSION['case6']=="Carnet")
							print('<a href="../ingame/engine=carnet.php" class="lien3">Voir mes contacts</a>');
						print('
					</p>
						
						');
						}
						
					print('
					</div>
					');
					}
				
				mysql_close($db);
				
				?>

			</div>
			
			<div id="wiki">
				<!-- PARTIE DU BAS : WIKI -->
				
				<?php include('include/inc_wikiderniers.php') ?>
				
				<?php include('include/inc_searcharticle.php'); ?>
				
				<?php include('include/inc_infoedc.php') ?>
				
				<?php include('include/inc_searchedc.php'); ?>
				
				<div id="edc-infopersonnage">
					<!-- INFOS CONCERNANT MON  PERSONNAGE -->
					
					<?php include('include/inc_situation.php'); ?>
					
				</div>
			</div>	
		</div>
	
	</body>
	
</html>
