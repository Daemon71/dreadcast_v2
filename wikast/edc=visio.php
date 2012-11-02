<?php
session_start();

if($_SESSION['id']=="") $_SESSION['statut']=="visiteur";

if($_POST['article']!="")
	{
	$auteur = ($_SESSION['id']=="")? "":$_SESSION['pseudo'];
	
	$message = stripslashes(htmlentities($_POST['message'],ENT_QUOTES));
	$message = str_replace("\n", "<br />",$message);
	
	$db = mysql_connect('localhost', 'CENSURE', 'CENSURE') or die("Erreur de connexion au serveur des messages.");
	mysql_select_db('CENSURE',$db) or die("Erreur de connexion a la base de donnees des messages.");
	
	$sql = 'INSERT INTO wikast_edc_commentaires_tbl(id,article,auteur,date,contenu,nouveau) VALUES("","'.$_POST['article'].'","'.$auteur.'","'.time().'","'.$message.'","oui")' ;
	$req = mysql_query($sql);
	
	mysql_close($db);
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
				
				$sqlinfos = 'SELECT id,race,sexe,avatar,statut FROM principal_tbl WHERE pseudo="'.$_GET['auteur'].'"';
				$reqinfos = mysql_query($sqlinfos);
				$resinfos = mysql_num_rows($reqinfos);
				
				if($resinfos!="")
					{
					$sqlbof = 'SELECT id FROM principal_tbl ORDER BY total DESC' ;
					$reqbof = mysql_query($sqlbof);
					$resbof = mysql_num_rows($reqbof);
					
					$idauteur = mysql_result($reqinfos,0,id);
					
					$classementauteur=0;
					$bof=0;
					
					while($bof<$resbof)
						{
						if(mysql_result($reqbof,$bof,id)==$idauteur)
							{
							$classementauteur = $bof + 1;
							break;
							}
						$bof++;
						}
					}
				
				$sql1 = 'SELECT * FROM wikast_edc_articles_tbl WHERE auteur="'.$_GET['auteur'].'" ORDER BY date DESC';
				$req1 = mysql_query($sql1);
				$res1 = mysql_num_rows($req1);
				
				if($res1 == 0)
					{
					$pasEDC = 1;
					$res2 = 0;
					$res3 = 0;
					$debut1 = 0;
					$fin1 = 0;
					$debut2 = 0;
					$fin2 = 0;
					}
				else
					{
					$pasEDC = 0;
					
					$sql2 = 'SELECT * FROM wikast_edc_etoiles_tbl WHERE auteur="'.$_GET['auteur'].'" ORDER BY id DESC';
					$req2 = mysql_query($sql2);
					$res2 = mysql_num_rows($req2);
					
					$sql3 = 'SELECT * FROM wikast_edc_etoiles_tbl WHERE cible="'.$_GET['auteur'].'" ORDER BY id DESC';
					$req3 = mysql_query($sql3);
					$res3 = mysql_num_rows($req3);
					
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
					}
				
				$sql2m = 'SELECT * FROM wikast_edc_etoiles_tbl WHERE auteur="'.$_SESSION['pseudo'].'" ORDER BY id DESC';
				$req2m = mysql_query($sql2m);
				$res2m = mysql_num_rows($req2m);
						
				$sql3m = 'SELECT * FROM wikast_edc_etoiles_tbl WHERE cible="'.$_SESSION['pseudo'].'" ORDER BY id DESC';
				$req3m = mysql_query($sql3m);
				$res3m = mysql_num_rows($req3m);
				
				if(statut($_SESSION['statut'])>=2)
					{
					
					print('
				<div id="zone-etoiles">
					<p class="titre">Ses derni&egrave;res &eacute;toiles</p>
					<p class="image"></p>
					<ul>
						');
																	//AFFICHAGE DES ETOILES
					for($i = $debut2 ; $i < $fin2 ; $i++)
						{
						print('<li><a href="edc=visio.php?auteur='.mysql_result($req2,$i,cible).'">'.mysql_result($req2,$i,cible).'</a></li>');
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
								print('<a href="edc=visio.php?auteur='.$_GET['auteur'].'&page1='.$pagearticles.'&page2='.$pagem1.'&feuille='.$_GET['feuille'].'" class="lien1"><</a> ');
								}
							else
								{
								print('<span class="lien1bis"><</span> ');
								}
								
							if($pageetoiles > 4)
								{
								print('<a href="edc=visio.php?auteur='.$_GET['auteur'].'&page1='.$pagearticles.'&page2=1&feuille='.$_GET['feuille'].'" class="lien2">1</a> ... ');
								}
							elseif($pageetoiles==4)
								{
								print('<a href="edc=visio.php?auteur='.$_GET['auteur'].'&page1='.$pagearticles.'&page2=1&feuille='.$_GET['feuille'].'" class="lien2">1</a> ');
								}
							if($pagem2 > 0)
								{
								print('<a href="edc=visio.php?auteur='.$_GET['auteur'].'&page1='.$pagearticles.'&page2='.$pagem2.'&feuille='.$_GET['feuille'].'" class="lien2">'.$pagem2.'</a> ');
								}
							if($pagem1 > 0)
								{
								print('<a href="edc=visio.php?auteur='.$_GET['auteur'].'&page1='.$pagearticles.'&page2='.$pagem1.'&feuille='.$_GET['feuille'].'" class="lien2">'.$pagem1.'</a> ');
								}
								
							print('<span class="lien2bis">'.$pageetoiles.'</span> ');

							if($pagep1 <= $pagemax)
								{
								print('<a href="edc=visio.php?auteur='.$_GET['auteur'].'&page1='.$pagearticles.'&page2='.$pagep1.'&feuille='.$_GET['feuille'].'" class="lien2">'.$pagep1.'</a> ');
								}
							if($pagep2 <= $pagemax)
								{
								print('<a href="edc=visio.php?auteur='.$_GET['auteur'].'&page1='.$pagearticles.'&page2='.$pagep2.'&feuille='.$_GET['feuille'].'" class="lien2">'.$pagep2.'</a> ');
								}
							if($pagemax > $pagep2)
								{
								print('... <a href="edc=visio.php?auteur='.$_GET['auteur'].'&page1='.$pagearticles.'&page2='.$pagemax.'&feuille='.$_GET['feuille'].'" class="lien2">'.$pagemax.'</a> <a href="edc=visio.php?auteur='.$_GET['auteur'].'&page1='.$pagearticles.'&page2='.$pagep1.'&feuille='.$_GET['feuille'].'" class="lien3">></a>');
								}
								
							if($pageetoiles < $pagemax)
								{
								print('<a href="edc=visio.php?auteur='.$_GET['auteur'].'&page1='.$pagearticles.'&page2='.$pagep1.'&feuille='.$_GET['feuille'].'" class="lien3">></a> ');
								}
							else
								{
								print('<span class="lien3bis">></span> ');
								}
									
						
					print('</p></div>
					<div class="infos">
						<div class="pseudo">'.$_GET['auteur'].'</div>');
						if($resinfos!="")
						{
						print('<div class="info1">Race : '.mysql_result($reqinfos,0,race).'</div>
						<div class="info2">Sexe : '.mysql_result($reqinfos,0,sexe).'</div>
						<!--<div class="info3">Class. : '.$classementauteur.'</div>-->
						<div class="avatar"><img src="');
						
						if((preg_match("/http/",mysql_result($reqinfos,0,avatar))) OR (preg_match("/ftp/",mysql_result($reqinfos,0,avatar)))) print(mysql_result($reqinfos,0,avatar));
						else print('../ingame/avatars/'.mysql_result($reqinfos,0,avatar));
						
						print('" alt="Son avatar" width="68" height="68" /></div>
						<a href="edc=visio.php?auteur='.$_GET['auteur'].'&visio=infos&actuel='.$_GET['actuel'].'&page1='.$pagearticles.'&page2='.$pageetoiles.'" class="lien1">Plus d\'informations</a>');
						}
					print('</div>
				</div>
					');
					}
				else
					{
					print('
				<div id="zone-gauche">
					<p class="image"></p>
					<div class="google">
						<script type="text/javascript">
							<!--
							google_ad_client = "pub-9377415436429871";
							/* 200x200, Wikast 09/03/10 */
							google_ad_slot = "6287427597";
							google_ad_width = 200;
							google_ad_height = 200;
							//-->
						</script>
						<script type="text/javascript" src="http://pagead2.googlesyndication.com/pagead/show_ads.js">
						</script>
					</div>
					
					<div class="infos">
						<a href="../ingame/engine=contacter.php?cible='.$_GET['auteur'].'" class="pseudo">'.$_GET['auteur'].'</a>');
						if($resinfos!="")
						{
						print('<div class="info1">Race : '.mysql_result($reqinfos,0,race).'</div>
						<div class="info2">Sexe : '.mysql_result($reqinfos,0,sexe).'</div>
						<!--<div class="info3">Class. : '.$classementauteur.'</div>-->
						<div class="avatar"><img src="');
						
						if((preg_match("/http/",mysql_result($reqinfos,0,avatar))) OR (preg_match("/ftp/",mysql_result($reqinfos,0,avatar)))) print(mysql_result($reqinfos,0,avatar));
						else print('../ingame/avatars/'.mysql_result($reqinfos,0,avatar));
						
						print('" alt="Son avatar" width="68" height="68" /></div>
						<a href="edc=visio.php?auteur='.$_GET['auteur'].'&visio=infos&actuel='.$_GET['actuel'].'&page1='.$pagearticles.'&page2='.$pageetoiles.'" class="lien1">Plus d\'informations</a>');
						}
					print('</div>
				</div>
					');
					}
				
				///////////////////////////////////////////////////////// CENTRE /////////////////////////////////////////////////////////
				
			if($_GET['visio']=="" OR $pasEDC==1)
				{
				if($_GET['feuille']=="" && $pasEDC==0)
					{
					$sql = 'SELECT id,date,contenu FROM wikast_edc_articles_tbl WHERE auteur="'.$_GET['auteur'].'" AND titre="Pr&eacute;sentation"';
					$req = mysql_query($sql);
					$res = mysql_num_rows($req);
					
					$dernierid = mysql_result($req,0,"id");
					$derniertitre = "Pr&eacute;sentation";
					$dernierdate = mysql_result($req,0,"date");
					$derniercontenu = mysql_result($req,0,"contenu");
					}
				elseif($pasEDC==0)
					{
					$sql = 'SELECT * FROM wikast_edc_articles_tbl WHERE auteur="'.$_GET['auteur'].'" AND id="'.$_GET['feuille'].'"';
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
						}
					}
				else $derniercontenu ="<br /><br />[centrer]Cette personne n'a pas encore activ&eacute; son EDC.[/centrer]";
				
				
				$derniercontenu = transforme_texte($derniercontenu);
				
				
				if($derniertitre == "Pr&eacute;sentation") $dernierid = "Pr&eacute;sentation";
				
				if($pasEDC==0)
					{
					
					// AJOUT AU COMPTEUR D'EDC
					if($_SESSION[$_GET['auteur']]!="vu" AND $_SESSION['pseudo']!=$_GET['auteur'])
						{
						$_SESSION[$_GET['auteur']]="vu";
						
						$sql = 'SELECT edc_vu FROM wikast_joueurs_tbl WHERE pseudo = "'.$_GET['auteur'].'"';
						$req = mysql_query($sql);
						$res = mysql_num_rows($req);
						if($res != 0)
							{
							$sql = 'UPDATE wikast_joueurs_tbl SET edc_vu = "'.(mysql_result($req,0,edc_vu)+1).'" WHERE pseudo = "'.$_GET['auteur'].'"';
							mysql_query($sql);
							}
						}
					
				print('<div id="zone-centre">
					<p class="titre">'.$derniertitre.'</p>
					<p class="image">');
					
					if($dernierid != "Pr&eacute;sentation")
						{
						print('<a href="edc=visio.php?auteur='.$_GET['auteur'].'&article='.$dernierid.'&visio=commentaires&actuel='.$actuel.'&page1='.$pagearticles.'&page2='.$pageetoiles.'" class="commentaires3">Voir les commentaires ('.$res4.')</a>
							<span>'.date('d/m/y',$dernierdate).' &agrave; '.date('H:i',$dernierdate).'</span>');
						}
					else
						{
							print('<a href="edc=visio.php?auteur='.$_GET['auteur'].'&visio=articles&actuel='.$actuel.'&page1='.$pagearticles.'&page2='.$pageetoiles.'" class="commentaires3">Voir ses articles</a>
							<a href="edc=visio.php?auteur='.$_GET['auteur'].'&visio=etoiles&actuel='.$actuel.'&page1='.$pagearticles.'&page2='.$pageetoiles.'" class="commentaires4">Voir ses &eacute;toiles</a>');
						}
					
					print('
						</p>
					<div class="texte">
						<div class="style1">');
						if(preg_match('/\<center\>Bienvenue sur votre Espace DreadCast\, citoyen \<strong\>'.$_GET['auteur'].'\<\/strong\>\.\<\/center\>/',$derniercontenu)) print('<br /><br /><center>Cette personne n\'a pas encore d&eacute;fini de pr&eacute;sentation.</center>');
						else print($derniercontenu);
						print('</div>
					</div>
				</div>');
					}
				else
					{
				print('<div id="zone-centre">
					<p class="titre">Aucune information trouv&eacute;e</p>
					<p class="image"></p>
					<div class="texte">
						<div class="style1">'.$derniercontenu.'</div>
					</div>
				</div>');
					}
				}
			elseif($_GET['visio']=="articles")
				{
				print('
				<div id="zone-centre">
					<p class="titre">Vos diff&eacute;rents articles</p>
					<p class="image"></p>
					<div class="texte">
						<div class="style1">');
							print($_GET['auteur'].' a &eacute;crit <span class="couleur2">'.$res1.'</span> article'); if($res1 != 1) print('s');print(' :');
							print('<br /><br />');
						
					for($i=0 ; $i<$res1 ; $i++)
						{
						print('<a href="edc=visio.php?auteur='.$_GET['auteur'].'&page1='.$pagearticles.'&page2='.$pageetoiles.'&feuille='.mysql_result($req1,$i,id).'" class="bloc"><span class="style11">'.mysql_result($req1,$i,titre).'</span><span class="style12">Le '.date('d/m/y',mysql_result($req1,$i,"date")).' &agrave; '.date('H:i',mysql_result($req1,$i,"date")).'</span></a>
						');
						}
				
						print('</div>
					</div>
				</div>');
				}
			elseif($_GET['visio']=="etoiles")
				{
				
				print('
				<div id="zone-centre">
					<p class="titre">Ses diff&eacute;rentes &eacute;toiles</p>
					<p class="image">');
					if($resinfos && mysql_result($reqinfos,0,statut)=="Joueur")
						{
						print('<span class="commentaires5">');
						if(mysql_result($reqinfos,0,sexe)=="Homme") print('Il');
						else print('Elle');
						print(' dispose de <span class="couleur1">'.(5-$res2+$res3).'</span>/<span class="couleur1">'.(5+$res3).'</span> &eacute;toile');
						if((5-$res2+$res3)!=1) print('s');
						print('</span>');
						}
					else { print('<span class="commentaires5">Etoiles illimit&eacute;es</span>'); }
					print('</p>
					<div class="texte">
						<div class="style1">');
							if($res3 != 0)
								{
								print($_GET['auteur'].' a re&ccedil;u <span class="couleur2">'.$res3.'</span> &eacute;toile'); if($res3 != 1) print('s');print(' :');
								}
							else print($_GET['auteur'].' n\'a pas encore re&ccedil;u d\'&eacute;toiles.');
							print('<br /><br />');
						
					for($i=0 ; $i<$res3 ; $i++)
						{
						print('<a href="edc=visio.php?auteur='.mysql_result($req3,$i,auteur).'" class="bloc"><span class="style13">Etoile de '.mysql_result($req3,$i,auteur).'</span></a>
						');
						}
						print('<br />');
							if($res2 != 0)
								{
								print($_GET['auteur'].' a donn&eacute; <span class="couleur2">'.$res2.'</span> &eacute;toile'); if($res2 != 1) print('s');print(' :');
								}
							else print($_GET['auteur'].' n\'a pas encore donn&eacute; d\'&eacute;toiles.');
							print('<br /><br />');
						
					for($i=0 ; $i<$res2 ; $i++)
						{
						print('<a href="edc=visio.php?auteur='.mysql_result($req2,$i,cible).'" class="bloc"><span class="style13">Etoile donn&eacute;e &agrave; '.mysql_result($req2,$i,cible).'</span></a>
						');
						}
				
						print('</div>
					</div>
				</div>');
				}
			elseif($_GET['visio']=="infos")
				{
				$sql = 'SELECT infoperso,commentaire FROM wikast_joueurs_tbl WHERE pseudo="'.$_GET['auteur'].'"';
				$req = mysql_query($sql);
				$resexiste = mysql_num_rows($req);
				
				if($resexiste != 0)
				{
				$infos = mysql_result($req,0,infoperso);
				$comm = mysql_result($req,0,commentaire);				
				}
				
				print('<div id="zone-centre">
					<p class="titre">Informations personnelles de '.$_GET['auteur'].'</p>
					<p class="image"></p>
					<div class="texte">
						<div class="style1">');
						
				//INFOS GENERALES
				print('&nbsp;&nbsp;&nbsp;<span class="couleur1">Informations g&eacute;n&eacute;rales</span><br /><br />');
				
				$sql = 'SELECT id FROM wikast_forum_sujets_tbl WHERE auteur="'.$_GET['auteur'].'"';
				$req = mysql_query($sql);
				$ressujets = mysql_num_rows($req);
				$sql = 'SELECT id FROM wikast_forum_posts_tbl WHERE auteur="'.$_GET['auteur'].'"';
				$req = mysql_query($sql);
				$resposts = mysql_num_rows($req);
				$sql = 'SELECT id FROM wikast_edc_commentaires_tbl WHERE auteur="'.$_GET['auteur'].'"';
				$req = mysql_query($sql);
				$rescomms = mysql_num_rows($req);
				$sql = 'SELECT id FROM wikast_edc_articles_tbl WHERE auteur="'.$_GET['auteur'].'"';
				$req = mysql_query($sql);
				$resart = mysql_num_rows($req);
				$sql = 'SELECT id FROM wikast_edc_etoiles_tbl WHERE auteur="'.$_GET['auteur'].'"';
				$req = mysql_query($sql);
				$reset1 = mysql_num_rows($req);
				$sql = 'SELECT id FROM wikast_edc_etoiles_tbl WHERE cible="'.$_GET['auteur'].'"';
				$req = mysql_query($sql);
				$reset2 = mysql_num_rows($req);
				$sql = 'SELECT edc_vu FROM wikast_joueurs_tbl WHERE pseudo="'.$_GET['auteur'].'"';
				$reqvu = mysql_query($sql);
				
				print($_GET['auteur'].' a post&eacute; <span class="couleur1">'.$ressujets.'</span> sujet'); if($ressujets!=1) print('s'); print(' sur le forum<br />');
				print($_GET['auteur'].' a post&eacute; <span class="couleur1">'.$resposts.'</span> message'); if($resposts!=1) print('s'); print(' sur le forum<br />');
				print($_GET['auteur'].' a &eacute;crit <span class="couleur1">'.$resart.'</span> article'); if($resart!=1) print('s'); if($resart!=0) print(' (<a href="edc=visio.php?auteur='.$_GET['auteur'].'&visio=articles&page1='.$pagearticles.'&page2='.$pageetoiles.'">Voir</a>)'); print('<br />');
				print($_GET['auteur'].' a &eacute;crit <span class="couleur1">'.$rescomms.'</span> commentaire'); if($rescomms!=1) print('s'); if($rescomms!=0) print(' (<a href="edc=visio.php?auteur='.$_GET['auteur'].'&visio=sescommentaires&page1='.$pagearticles.'&page2='.$pageetoiles.'">Voir</a>)'); print('<br />');
				print($_GET['auteur'].' a donn&eacute; <span class="couleur1">'.$reset1.'</span> &eacute;toile'); if($reset1!=1) print('s'); if($reset1!=0) print(' (<a href="edc=visio.php?auteur='.$_GET['auteur'].'&visio=etoiles&page1='.$pagearticles.'&page2='.$pageetoiles.'">Voir</a>)'); print('<br />');
				print($_GET['auteur'].' a re&ccedil;u <span class="couleur1">'.$reset2.'</span> &eacute;toile'); if($reset1!=1) print('s'); if($reset2!=0) print(' (<a href="edc=visio.php?auteur='.$_GET['auteur'].'&visio=etoiles&page1='.$pagearticles.'&page2='.$pageetoiles.'">Voir</a>)'); print('<br />');
				if($resexiste != 0) print('L\'EDC de '.$_GET['auteur'].' a &eacute;t&eacute; vu <span class="couleur1">'.mysql_result($reqvu,0,edc_vu).'</span> fois<br />');
				
				//INFOS PERSOS
				print('<br />&nbsp;&nbsp;&nbsp;<span class="couleur1">Informations personnelles</span><br /><br />');
				if($infos=="-" OR $infos=="-signature-") print('Aucune information personnelle n\'est disponible &agrave; propos de '.$_GET['auteur'].'.<br />');
				else
					{
					print('Voici les informations personnelles que '.$_GET['auteur'].' a rendu visibles :<br /><br />');
					
					$demande="";
					if(preg_match('/\-mail\-/',$infos)) $demande.="adresse,";
					if(preg_match('/\-exp\-/',$infos)) $demande.="total,";
					if(preg_match('/\-age\-/',$infos)) $demande.="age,";
					if(preg_match('/\-taille\-/',$infos)) $demande.="taille,";
					if(preg_match('/\-metier\-/',$infos)) $demande.="type,entreprise,";
					if(preg_match('/\-cercle\-/',$infos))
						{
						$sql = 'SELECT cercle FROM cercles_tbl WHERE pseudo="'.$_GET['auteur'].'"';
						$req = mysql_query($sql);
						$res = mysql_num_rows($req);
						
						if($res == 0) $cercle = "";
						else $cercle = mysql_result($req,0,cercle);
						}
				if(preg_match('/\-equipement\-/',$infos)) $demande.="arme,vetements,objet,";
					if(preg_match('/\-stats\-/',$infos)) $demande.="combat,observation,gestion,maintenance,mecanique,service,discretion,economie,resistance,recherche,tir,vol,medecine,informatique,";
					if(preg_match('/\-logement\-/',$infos)) $demande.="ruel,numl,";
					if(preg_match('/\-sante\-/',$infos)) $demande.="sante,sante_max,";
					if(preg_match('/\-fatigue\-/',$infos)) $demande.="fatigue,fatigue_max,";
					if(preg_match('/\-nutrition\-/',$infos)) $demande.="faim,soif,";
					if(preg_match('/\-alcool\-/',$infos)) $demande.="alcool,";
					if(preg_match('/\-casier\-/',$infos))
						{
						$sql = 'SELECT id FROM casiers_tbl WHERE pseudo="'.$_GET['auteur'].'"' ;
						$req = mysql_query($sql);
						$rescasier = mysql_num_rows($req);
						}
					if(preg_match('/\-action\-/',$infos)) $demande.="action,";
					if(preg_match('/\-indice\-/',$infos)) $demande.="police,di2rco,";
					if(preg_match('/\-reaction\-/',$infos)) $demande.="rattaque,rpolice,rintrusion,rvol,rapproche,";
					$demande.="sexe";
					
					$sql = 'SELECT '.$demande.' FROM principal_tbl WHERE pseudo="'.$_GET['auteur'].'"';
					$req = mysql_query($sql);
					$res = mysql_num_rows($req);
					// AFFICHAGE
					
					if($res && preg_match('/\-mail\-/',$infos)) print('<span class="couleur1">Adresse mail :</span> '.str_replace("@", "(at)",mysql_result($req,0,adresse)).'<br />');
					if($res && preg_match('/\-age\-/',$infos)) print('<span class="couleur1">Age :</span> '.mysql_result($req,0,age).' ans<br />');
					if($res && preg_match('/\-taille\-/',$infos)) print('<span class="couleur1">Taille :</span> 1m'.mysql_result($req,0,taille).'<br />');
					if($res && preg_match('/\-sante\-/',$infos)) print('<span class="couleur1">Sant&eacute; :</span> '.mysql_result($req,0,sante).'/'.mysql_result($req,0,sante_max).'<br />');
					if($res && preg_match('/\-fatigue\-/',$infos)) print('<span class="couleur1">Forme :</span> '.mysql_result($req,0,fatigue).'/'.mysql_result($req,0,fatigue_max).'<br />');
					if($res && preg_match('/\-nutrition\-/',$infos))
						{
						print('<span class="couleur1">Faim :</span> '.mysql_result($req,0,faim).'/100<br />
						<span class="couleur1">Soif :</span> '.mysql_result($req,0,soif).'/100<br />');
						}
					if($res && preg_match('/\-alcool\-/',$infos)) print('<span class="couleur1">Taux d\'alcool&eacute;mie :</span> '.mysql_result($req,0,alcool).'g/l<br />');
					if($res && preg_match('/\-action\-/',$infos)) print('<span class="couleur1">Action actuelle :</span> '.mysql_result($req,0,action).'<br />');
					if($res && preg_match('/\-metier\-/',$infos))
						{
						if(mysql_result($req,0,type)=="Aucun") print('<span class="couleur1">M&eacute;tier :</span> Actuellement sans emploi<br />');
						else print('<span class="couleur1">M&eacute;tier :</span> '.mysql_result($req,0,type).' chez '.mysql_result($req,0,entreprise).'<br />');
						}
					if(preg_match('/\-cercle\-/',$infos))
						{
						if($cercle=="") print('<span class="couleur1">Cercle :</span> '.$_GET['auteur'].' ne fait partie d\'aucun cercle<br />');
						else print('<span class="couleur1">Cercle :</span> '.$_GET['auteur'].' appartient au cercle '.$cercle.'<br />');
						}
					if($res && preg_match('/\-logement\-/',$infos))
						{
						if(mysql_result($req,0,ruel)=="Aucune") print('<span class="couleur1">Logement :</span> Aucun logement connu &agrave; ce jour<br />');
						else print('<span class="couleur1">Logement :</span> '.$_GET['auteur'].' habite au '.mysql_result($req,0,numl).' '.strtolower(mysql_result($req,0,ruel)).'<br />');
						}
					if(preg_match('/\-casier\-/',$infos))
						{
						if($rescasier==0) print('<span class="couleur1">Casier judiciaire :</span> '.$_GET['auteur'].' poss&egrave;de un casier vierge<br />');
						else { print('<span class="couleur1">Casier judiciaire :</span> '.$rescasier.' infraction');if($rescasier!=1)print('s');print(' aux lois de la ville<br />'); }
						}
					if($res && preg_match('/\-indice\-/',$infos))
						{
						if(mysql_result($req,0,police) >= 55 OR mysql_result($req,0,di2rco) != 0) { print('<span class="couleur1">Indice de recherche :</span> '.$_GET['auteur'].' est actuellement recherch&eacute;');if(mysql_result($req,0,sexe)=="Femme")print('e');print('<br />'); }
						else { print('<span class="couleur1">Indice de recherche : '.$_GET['auteur'].' n\'est pas recherch&eacute;');if(mysql_result($req,0,sexe)=="Femme")print('e');print('<br />'); }
						}
					if($res && preg_match('/\-exp\-/',$infos)) print('<span class="couleur1">Exp&eacute;rience :</span> '.mysql_result($req,0,total).'<br />');
					if($res && preg_match('/\-equipement\-/',$infos))
						{
						print('<br /><span class="couleur1"><strong>Equipement</strong></span><br />');
						if(mysql_result($req,0,arme)=="Aucune") print('<span class="couleur1">Arme :</span> Ne poss&egrave;de pas d\'arme &eacute;quip&eacute;e<br />');
						else print('<span class="couleur1">Arme :</span> '.mysql_result($req,0,arme).'<br />');
						print('<span class="couleur1">V&ecirc;tement :</span> '.mysql_result($req,0,vetements).'<br />');
						if(mysql_result($req,0,objet)=="Aucun") print('<span class="couleur1">Objet :</span> Ne poss&egrave;de pas d\'objet &eacute;quip&eacute;<br />');
						else print('<span class="couleur1">Objet :</span> '.mysql_result($req,0,objet).'<br />');
						}
					if($res && preg_match('/\-stats\-/',$infos))
						{
						print('<br /><span class="couleur1"><strong>Statistiques</strong></span><br />');
						if(mysql_result($req,0,combat)!=0) print('<span class="couleur1">Stat. combat :</span> '.substr(mysql_result($req,0,combat),0,5).'<br />');
						if(mysql_result($req,0,tir)!=0) print('<span class="couleur1">Stat. tir :</span> '.substr(mysql_result($req,0,tir),0,5).'<br />');
						if(mysql_result($req,0,resistance)!=0) print('<span class="couleur1">Stat. r&eacute;sistance :</span> '.substr(mysql_result($req,0,resistance),0,5).'<br />');
						if(mysql_result($req,0,vol)!=0) print('<span class="couleur1">Stat. vol :</span> '.substr(mysql_result($req,0,vol),0,5).'<br />');
						if(mysql_result($req,0,maintenance)!=0) print('<span class="couleur1">Stat. maintenance :</span> '.substr(mysql_result($req,0,maintenance),0,5).'<br />');
						if(mysql_result($req,0,mecanique)!=0) print('<span class="couleur1">Stat. m&eacute;canique :</span> '.substr(mysql_result($req,0,mecanique),0,5).'<br />');
						if(mysql_result($req,0,observation)!=0) print('<span class="couleur1">Stat. observation :</span> '.substr(mysql_result($req,0,observation),0,5).'<br />');
						if(mysql_result($req,0,discretion)!=0) print('<span class="couleur1">Stat. discretion :</span> '.substr(mysql_result($req,0,discretion),0,5).'<br />');
						if(mysql_result($req,0,gestion)!=0) print('<span class="couleur1">Stat. g&eacute;stion :</span> '.substr(mysql_result($req,0,gestion),0,5).'<br />');
						if(mysql_result($req,0,economie)!=0) print('<span class="couleur1">Stat. &eacute;conomie :</span> '.substr(mysql_result($req,0,economie),0,5).'<br />');
						if(mysql_result($req,0,service)!=0) print('<span class="couleur1">Stat. service :</span> '.substr(mysql_result($req,0,service),0,5).'<br />');
						if(mysql_result($req,0,medecine)!=0) print('<span class="couleur1">Stat. m&eacute;decine :</span> '.substr(mysql_result($req,0,medecine),0,5).'<br />');
						if(mysql_result($req,0,informatique)!=0) print('<span class="couleur1">Stat. informatique :</span> '.substr(mysql_result($req,0,informatique),0,5).'<br />');
						if(mysql_result($req,0,recherche)!=0) print('<span class="couleur1">Stat. recherche :</span> '.substr(mysql_result($req,0,recherche),0,5).'<br />');
						}
					if($res && preg_match('/\-reaction\-/',$infos))
						{
						print('<br /><span class="couleur1"><strong>R&eacute;actions</strong></span><br />
						<span class="couleur1">R&eacute;action &agrave l\'attaque :</span> '.mysql_result($req,0,rattaque).'<br />
						<span class="couleur1">R&eacute;action &agrave l\'arrestation :</span> '.mysql_result($req,0,rpolice).'<br />
						<span class="couleur1">R&eacute;action &agrave une intrusion :</span> '.mysql_result($req,0,rintrusion).'<br />
						<span class="couleur1">R&eacute;action &agrave un vol :</span> '.mysql_result($req,0,rvol).'<br />
						<span class="couleur1">R&eacute;action &agrave l\'approche :</span> '.mysql_result($req,0,rapproche).'<br />');
						}
					if(preg_match('/\-titres\-/',$infos))
						{
						$sql = 'SELECT titre,type FROM titres_tbl WHERE pseudo="'.$_GET['auteur'].'" ORDER BY type,titre';
						$req = mysql_query($sql);
						$res = mysql_num_rows($req);
						
						print('<br /><span class="couleur1"><strong>Titres</strong></span><br />');
						for($k=0;$k<$res;$k++)
							{
							if(mysql_result($req,$k,type) == "Niveau") print('<span class="couleur1">'.ucfirst(mysql_result($req,$k,titre)).'</span> (Titre de niveau)<br />');
							else print('<span class="couleur1">'.ucfirst(mysql_result($req,$k,titre)).' </span>(Accomplissement)<br />');
							}
						}
					if(preg_match('/\-statjeu\-/',$infos))
						{
						print('<br /><span class="couleur1"><strong>Statistiques de jeu</strong></span><br />');
						
						$sql = 'SELECT donnee,valeur FROM enregistreur_tbl WHERE pseudo="'.$_GET['auteur'].'" AND donnee != "combat" AND donnee != "tir" AND donnee != "resistance" AND donnee != "observation" AND donnee != "discretion" AND donnee != "vol" AND donnee != "medecine" AND donnee != "maintenance" AND donnee != "mecanique" AND donnee != "gestion" AND donnee != "economie" AND donnee != "service" AND donnee != "informatique" AND donnee != "recherche" AND donnee != "fidelite"' ;
						$req = mysql_query($sql);
						$res = mysql_num_rows($req);
						
						if($res == 0)
							print('Aucune<br />');
						
						for($i=0;$i<$res;$i++)
							{
							$donnee = mysql_result($req,$i,donnee);
							
							if($donnee=="acc_meurtre_donne") $donnee = "Meurtres commis";
							elseif($donnee=="acc_heures_supp") $donnee = "Heures supp' effectuées";
							elseif($donnee=="acc_travail_oi") $donnee = "Jours de travail en O.I.";
							elseif($donnee=="acc_vols_donnes") $donnee = "Vols réussis";
							elseif($donnee == "acc_alcool_achetes") $donnee = "Boissons alcoolisées bues";
							elseif($donnee == "acc_boissons_achetes") $donnee = "Boissons non-alcoolisées bues";
							
							if(preg_match("/acc\_/",$donnee)) continue;
							
							$donnee = ($donnee == "acheve")?"Innocents tués":(
									  ($donnee == "logement")?"Logements achetés":(
									  ($donnee == "justice")?"Justice rendue":(
									  ($donnee == "credivore")?"Crédits dévorés":(
									  ($donnee == "survie")?"Jours de survie":(
									  ($donnee == "travail")?"Heures de travail":(
									  ($donnee == "arrestation")?"Personnes arrêtées":(
									  ($donnee == "arrêté")?"Séjours en prison":(
									  ($donnee == "emploi")?"Emplois exercés":(
									  ($donnee == "cristaux")?"Recherche de cristaux":(
									  ($donnee == "travail actif")?"Jours de travail actif":(
									  ($donnee == "semaines")?"Années à Dreadcast":(
									  ($donnee == "entreprise")?"Entreprises créées":(
									  ($donnee == "anniversaire2ans")?"Champagne débouché pour les 2 ans":(
							ucfirst($donnee)))))))))))))));
							print('<span class="couleur1">'.utf8_decode($donnee).' :</span> '.floor(mysql_result($req,$i,valeur)).substr(strstr(mysql_result($req,$i,valeur),"."),0,3).'<br />');
							}
						}
					}
					
				if($comm!="")
					{
					
					$comm = transforme_texte($comm);
					
					print('<br /><span class="couleur1">Commentaire</span><br />
					<div class="zone4">'.$comm.'</div>');
					}
				
				print('</div>
					</div>
				</div>');
				}
			elseif($_GET['visio']=="commentaires")
				{
				$sql = 'SELECT titre FROM wikast_edc_articles_tbl WHERE id="'.$_GET['article'].'"';
				$req = mysql_query($sql);
				$res = mysql_num_rows($req);
				
				if($res!=0) $titre = mysql_result($req,0,titre);
				
				$sql = 'SELECT * FROM wikast_edc_commentaires_tbl WHERE article="'.$_GET['article'].'" ORDER BY date ASC';
				$req = mysql_query($sql);
				$res = mysql_num_rows($req);
				
				print('<div id="zone-centre">
					<p class="titre">'.$titre.'</p>
					<p class="image"><span class="commentaires6">'); if($res==0) print('Aucun'); else print($res); print(' commentaire'); if($res>1) print('s'); print('</span></p>
					<div class="texte">
						<div class="style1">');
						
				for($i=0 ; $i<$res ; $i++)
					{
					
					$contenu = transforme_texte(mysql_result($req,$i,contenu));

					print('<div class="commentaire"><p class="infos">Ecrit par ');
					if(mysql_result($req,$i,auteur)=="") print('<span class="style2">Anonyme</span>');
					else print('<a href="edc=visio.php?auteur='.mysql_result($req,$i,auteur).'" title="EDC de '.mysql_result($req,$i,auteur).'" class="style2">'.mysql_result($req,$i,auteur).'</a>');
					print(' le '.date('d/m/Y',mysql_result($req,$i,"date")).' &agrave '.date('H:i',mysql_result($req,$i,"date")).'</p>
						<p class="contenu">'.$contenu.'</p>
					</div>');
					}
					
					print('<form action="edc=visio.php?auteur='.$_GET['auteur'].'&visio=commentaires&article='.$_GET['article'].'&actuel='.$actuel.'&page1='.$pagearticles.'&page2='.$pageetoiles.'" method="post" id="champ" name="poster">
								<input type="hidden" name="article" value="'.$_GET['article'].'" />
								<div class="zone3" style="position:relative;top:10px;">
									Ajouter un commentaire : <br />
									<textarea name="message" id="textarea"></textarea>
									<div id="DCcode" style="padding:0 0 5px 0;">
										<a href="javascript:AddText(\'[g]\',\'\',\'[/g]\');" title="Mettre le texte en gras" class="type1"><strong>G</strong></a>
										<a href="javascript:AddText(\'[i]\',\'\',\'[/i]\');" title="Mettre le texte en italique" class="type1"><em>I</em></a>
										<a href="javascript:AddText(\'[img url=<URL DE VOTRE IMAGE> /]\',\'\',\'\');" title="Afficher une image">IMG</a>
										<a href="javascript:AddText(\'[lien url=<URL DE VOTRE LIEN>]\',\'\',\'[/lien]\');" title="Cr&eacute;er un lien">LIEN</a>
										<a href="javascript:AddText(\'[quote]\',\'\',\'[/quote]\');" title="Citer un texte">QUOTE</a>
										<a href="javascript:AddText(\'[centrer]\',\'\',\'[/centrer]\');" title="Centrer un texte">CENTRER</a>
									</div>
									<input name="submit" type="submit" value="Envoyer" id="ok2" />
								</div>
							</form>');
						
						print('</div>
					</div>
				</div>');
				
				}
			elseif($_GET['visio']=="sescommentaires")
				{
				$sql = 'SELECT A.auteur,A.titre,C.article,C.date,C.contenu FROM wikast_edc_articles_tbl A, wikast_edc_commentaires_tbl C WHERE C.auteur="'.$_GET['auteur'].'" AND A.id=C.article';
				$req = mysql_query($sql);
				$res = mysql_num_rows($req);
				
				print('<div id="zone-centre">
					<p class="titre">Commentaires &eacute;crits par '.$_GET['auteur'].'</p>
					<p class="image"><span class="commentaires6">'); if($res==0) print('Aucun'); else print($res); print(' commentaire'); if($res>1) print('s'); print('</span></p>
					<div class="texte">
						<div class="style1">');
				
				for($i=0 ; $i<$res ; $i++)
					{
					
					$contenu = transforme_texte(mysql_result($req,$i,contenu));
					
					print('<div class="commentaire"><p class="infos">Sujet : <a href="edc=visio.php?auteur='.mysql_result($req,$i,auteur).'&page1=1&page2=1&feuille='.mysql_result($req,$i,article).'" title="'.mysql_result($req,$i,titre).'" class="style2">'.mysql_result($req,$i,titre).'</a> par <a href="edc=visio.php?auteur='.mysql_result($req,$i,auteur).'" title="EDC de '.mysql_result($req,$i,auteur).'" class="style2">'.mysql_result($req,$i,auteur).'</a></p>');
					print('<p class="contenu">
						Post&eacute; le '.date('d/m/Y',mysql_result($req,$i,"date")).' &agrave '.date('H:i',mysql_result($req,$i,"date")).'<br /><br />
						'.$contenu.'</p>
					</div>');
					}
				
				print('</div>
					</div>
				</div>');
				}
			elseif($_GET['visio']=="don")
				{
				
				if(!preg_match("/edc\=visio\.php/",$_SERVER['HTTP_REFERER']))
					{
					print('<meta http-equiv="refresh" content="0 ; url=edc=visio.php?auteur='.$_GET['auteur'].'&actuel='.$actuel.'&page1='.$pagearticles.'&page2='.$pageetoiles.'"> ');
					exit();
					}
				
				if($_SESSION['id']=="" OR $res1==0 OR $_SESSION['pseudo'] == $_GET['auteur'])
					{
					print('<meta http-equiv="refresh" content="0 ; url=edc=visio.php?auteur='.$_GET['auteur'].'&actuel='.$actuel.'&page1='.$pagearticles.'&page2='.$pageetoiles.'"> ');
					exit();
					}
				
				$sql = 'SELECT id FROM wikast_edc_etoiles_tbl WHERE auteur="'.$_SESSION['pseudo'].'" AND cible="'.$_GET['auteur'].'"';
				$req = mysql_query($sql);
				$res = mysql_num_rows($req);
				
				if($res != 0)
					{
				print('<div id="zone-centre">
					<p class="titre">Don d\'&eacute;toile</p>
					<p class="image"></p>
					<div class="texte">
						<div class="style1">Vous avez d&eacute;j&agrave; &eacute;toil&eacute; cette personne.</div>
					</div>
				</div>');
					}
				else
					{
					if($_SESSION['statut']=="Joueur" && (5-$res2m+$res3m)==0)
						{
				print('<div id="zone-centre">
					<p class="titre">Don d\'&eacute;toile</p>
					<p class="image"></p>
					<div class="texte">
						<div class="style1">Vous n\'avez plus d\'&eacute;toile &agrave; donner.</div>
					</div>
				</div>');
						}
					else
						{
						$sql = 'INSERT INTO wikast_edc_etoiles_tbl(id,auteur,cible) VALUES("","'.$_SESSION['pseudo'].'","'.$_GET['auteur'].'")';
						mysql_query($sql);
						
						$texte = "<br /><br /><br /><br />".$_SESSION['pseudo']." vous a &eacute;toil&eacute;.";
						if($resinfos && mysql_result($reqinfos,0,statut)=="Joueur") $texte .= "<br />Votre nombre total d\'&eacute;toiles s\'&eacute;l&egrave;ve maintenant &agrave; ".(5+$res3+1).".<br />Comme vous en avez donn&eacute; ".$res2.", vous disposez donc de ".(5-$res2+$res3+1)."/".(5+$res3+1)." &eacute;toiles.";
						$texte .= "<br /><br />Acc&eacute;der &agrave; votre EDC pour en savoir plus sur ".$_SESSION['pseudo'];
						
						$titre = "Etoile re&ccedil;ue";
						
						$sql = 'INSERT INTO messages_tbl(id,auteur,cible,message,objet,moment) VALUES("","Dreadcast","'.$_GET['auteur'].'","'.$texte.'","'.$titre.'","'.time().'")';
						mysql_query($sql);
						
						$sql2m = 'SELECT * FROM wikast_edc_etoiles_tbl WHERE auteur="'.$_SESSION['pseudo'].'" ORDER BY id DESC';
						$req2m = mysql_query($sql2m);
						$res2m = mysql_num_rows($req2m);
						
						$sql3m = 'SELECT * FROM wikast_edc_etoiles_tbl WHERE cible="'.$_SESSION['pseudo'].'" ORDER BY id DESC';
						$req3m = mysql_query($sql3m);
						$res3m = mysql_num_rows($req3m);
						
				print('<div id="zone-centre">
					<p class="titre">Don d\'&eacute;toile</p>
					<p class="image"></p>
					<div class="texte">
						<div class="style1"><center>Vous avez &eacute;toil&eacute; '.$_GET['auteur'].'</center>');
						if($_SESSION['statut']=="Joueur" && (5-$res2m+$res3m)==0) print('<br /><br />Il ne vous reste plus d\'&eacute;toile &agrave; donner.');
						elseif($_SESSION['statut']=="Joueur") print('<br /><br />Il vous reste <span class="couleur2">'.(5-$res2m+$res3m).'</span> &eacute;toiles disponibles.');
						print('</div>
					</div>
				</div>');
						}
					}
				
				}
				
				///////////////////////////////////////////////////////// DROITE /////////////////////////////////////////////////////////
				
				if(statut($_SESSION['statut'])>=2)
					{
					print('
				<div id="zone-articles">
					<p class="titre">Ses derniers articles</p>
					<p class="image"></p>
					<ul>
						');
						
					for($i = $debut1 ; $i < $fin1 ; $i++)
						{
						print('<li><a href="edc=visio.php?auteur='.$_GET['auteur'].'&page1='.$pagearticles.'&page2='.$pageetoiles.'&feuille='.mysql_result($req1,$i,id).'">'.mysql_result($req1,$i,titre).'</a></li>');
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
								print('<a href="edc=visio.php?auteur='.$_GET['auteur'].'&visio='.$_GET['visio'].'&page1='.$pagem1.'&page2='.$pageetoiles.'&feuille='.$_GET['feuille'].'" class="lien1"><</a> ');
								}
							else
								{
								print('<span class="lien1bis"><</span> ');
								}
								
							if($pagearticles > 4)
								{
								print('<a href="edc=visio.php?auteur='.$_GET['auteur'].'&visio='.$_GET['visio'].'&page1=1&page2='.$pageetoiles.'&feuille='.$_GET['feuille'].'" class="lien2">1</a> ... ');
								}
							elseif($pagearticles==4)
								{
								print('<a href="edc=visio.php?auteur='.$_GET['auteur'].'&visio='.$_GET['visio'].'&page1=1&page2='.$pageetoiles.'&feuille='.$_GET['feuille'].'" class="lien2">1</a> ');
								}
							if($pagem2 > 0)
								{
								print('<a href="edc=visio.php?auteur='.$_GET['auteur'].'&visio='.$_GET['visio'].'&page1='.$pagem2.'&page2='.$pageetoiles.'&feuille='.$_GET['feuille'].'" class="lien2">'.$pagem2.'</a> ');
								}
							if($pagem1 > 0)
								{
								print('<a href="edc=visio.php?auteur='.$_GET['auteur'].'&visio='.$_GET['visio'].'&page1='.$pagem1.'&page2='.$pageetoiles.'&feuille='.$_GET['feuille'].'" class="lien2">'.$pagem1.'</a> ');
								}
								
							print('<span class="lien2bis">'.$pagearticles.'</span> ');

							if($pagep1 <= $pagemax)
								{
								print('<a href="edc=visio.php?auteur='.$_GET['auteur'].'&visio='.$_GET['visio'].'&page1='.$pagep1.'&page2='.$pageetoiles.'&feuille='.$_GET['feuille'].'" class="lien2">'.$pagep1.'</a> ');
								}
							if($pagep2 <= $pagemax)
								{
								print('<a href="edc=visio.php?auteur='.$_GET['auteur'].'&visio='.$_GET['visio'].'&page1='.$pagep2.'&page2='.$pageetoiles.'&feuille='.$_GET['feuille'].'" class="lien2">'.$pagep2.'</a> ');
								}
							if($pagemax > $pagep2)
								{
								print('... <a href="edc=visio.php?auteur='.$_GET['auteur'].'&visio='.$_GET['visio'].'&page1='.$pagemax.'&page2='.$pageetoiles.'&feuille='.$_GET['feuille'].'" class="lien2">'.$pagemax.'</a> <a href="edc=visio.php?auteur='.$_GET['auteur'].'&page1='.$pagearticles.'&page2='.$pagep1.'&feuille='.$_GET['feuille'].'" class="lien3">></a>');
								}
								
							if($pagearticles < $pagemax)
								{
								print('<a href="edc=visio.php?auteur='.$_GET['auteur'].'&visio='.$_GET['visio'].'&page1='.$pagep1.'&page2='.$pageetoiles.'&feuille='.$_GET['feuille'].'" class="lien3">></a> ');
								}
							else
								{
								print('<span class="lien3bis">></span> ');
								}
						
						print('</p>
					</div>
					<p class="actions">
						<a href="edc=visio.php?auteur='.$_GET['auteur'].'&visio=articles&actuel='.$actuel.'&page1='.$pagearticles.'&page2='.$pageetoiles.'" class="lien1">Voir ses articles</a><br />
						<a href="edc=visio.php?auteur='.$_GET['auteur'].'&visio=etoiles&actuel='.$actuel.'&page1='.$pagearticles.'&page2='.$pageetoiles.'" class="lien2">Voir ses &eacute;toiles</a><br />');
						$bla=3;
						if($_SESSION['pseudo']!=$_GET['auteur'])
							{
						if(statut($_SESSION['statut'])>=2) print('<a href="edc=visio.php?auteur='.$_GET['auteur'].'&visio=don&actuel='.$actuel.'&page1='.$pagearticles.'&page2='.$pageetoiles.'" class="lien'.($bla++).'">Donner une &eacute;toile</a><br />');
						elseif($_SESSION['statut']=="Joueur" && (5-$res2m+$res3m)!=0) print('<a href="edc=visio.php?auteur='.$_GET['auteur'].'&visio=don&actuel='.$actuel.'&page1='.$pagearticles.'&page2='.$pageetoiles.'" class="lien'.($bla++).'">Donner une &eacute;toile ('.(5-$res2m+$res3m).'/'.(5+$res3m).')</a><br />');
							}
						if($_SESSION['case1']=="Carnet" OR $_SESSION['case2']=="Carnet" OR $_SESSION['case3']=="Carnet" OR $_SESSION['case4']=="Carnet" OR $_SESSION['case5']=="Carnet" OR $_SESSION['case6']=="Carnet")
							{
							$sqltmp = 'SELECT id FROM carnets_tbl WHERE pseudo="'.$_SESSION['pseudo'].'" AND contact="'.$_GET['auteur'].'"';
							$reqtmp = mysql_query($sqltmp);
							$restmp = mysql_num_rows($reqtmp);
							
							if($restmp == 0 AND $_GET['auteur'] != $_SESSION['pseudo']) print('<a href="../ingame/engine=carnet.php?affiche=contacts&ajoutco='.$_GET['auteur'].'" class="lien'.$bla.'">Ajouter &agrave; mes contacts</a>');
							}
						print('
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
					<p class="titre">Ses derniers articles</p>
					<p class="image"></p>
					<a href="edc=visio.php?auteur='.$_GET['auteur'].'&visio='.$_GET['visio'].'&actuel=etoiles&feuille='.$_GET['feuille'].'" class="lienimage2"></a>
					<ul>
						');
						
					for($i = $debut1 ; $i < $fin1 ; $i++)
						{
						print('<li><a href="edc=visio.php?auteur='.$_GET['auteur'].'&page1='.$pagearticles.'&page2='.$pageetoiles.'&feuille='.mysql_result($req1,$i,id).'">'.mysql_result($req1,$i,titre).'</a></li>');
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
								print('<a href="edc=visio.php?auteur='.$_GET['auteur'].'&visio='.$_GET['visio'].'&actuel='.$actuel.'&page1='.$pagem1.'&page2='.$pageetoiles.'&feuille='.$_GET['feuille'].'" class="lien1"><</a> ');
								}
							else
								{
								print('<span class="lien1bis"><</span> ');
								}
								
							if($pagearticles > 4)
								{
								print('<a href="edc=visio.php?auteur='.$_GET['auteur'].'&visio='.$_GET['visio'].'&actuel='.$actuel.'&page1=1&page2='.$pageetoiles.'&feuille='.$_GET['feuille'].'" class="lien2">1</a> ... ');
								}
							elseif($pagearticles==4)
								{
								print('<a href="edc=visio.php?auteur='.$_GET['auteur'].'&visio='.$_GET['visio'].'&actuel='.$actuel.'&page1=1&page2='.$pageetoiles.'&feuille='.$_GET['feuille'].'" class="lien2">1</a> ');
								}
							if($pagem2 > 0)
								{
								print('<a href="edc=visio.php?auteur='.$_GET['auteur'].'&visio='.$_GET['visio'].'&actuel='.$actuel.'&page1='.$pagem2.'&page2='.$pageetoiles.'&feuille='.$_GET['feuille'].'" class="lien2">'.$pagem2.'</a> ');
								}
							if($pagem1 > 0)
								{
								print('<a href="edc=visio.php?auteur='.$_GET['auteur'].'&visio='.$_GET['visio'].'&actuel='.$actuel.'&page1='.$pagem1.'&page2='.$pageetoiles.'&feuille='.$_GET['feuille'].'" class="lien2">'.$pagem1.'</a> ');
								}
								
							print('<span class="lien2bis">'.$pagearticles.'</span> ');

							if($pagep1 <= $pagemax)
								{
								print('<a href="edc=visio.php?auteur='.$_GET['auteur'].'&visio='.$_GET['visio'].'&actuel='.$actuel.'&page1='.$pagep1.'&page2='.$pageetoiles.'&feuille='.$_GET['feuille'].'" class="lien2">'.$pagep1.'</a> ');
								}
							if($pagep2 <= $pagemax)
								{
								print('<a href="edc=visio.php?auteur='.$_GET['auteur'].'&visio='.$_GET['visio'].'&actuel='.$actuel.'&page1='.$pagep2.'&page2='.$pageetoiles.'&feuille='.$_GET['feuille'].'" class="lien2">'.$pagep2.'</a> ');
								}
							if($pagemax > $pagep2)
								{
								print('... <a href="edc=visio.php?auteur='.$_GET['auteur'].'&visio='.$_GET['visio'].'&actuel='.$actuel.'&page1='.$pagemax.'&page2='.$pageetoiles.'&feuille='.$_GET['feuille'].'" class="lien2">'.$pagemax.'</a> <a href="edc=visio.php?auteur='.$_GET['auteur'].'&visio='.$_GET['visio'].'&actuel='.$actuel.'&page1='.$pagearticles.'&page2='.$pagep1.'&feuille='.$_GET['feuille'].'" class="lien3">></a>');
								}
								
							if($pagearticles < $pagemax)
								{
								print('<a href="edc=visio.php?auteur='.$_GET['auteur'].'&visio='.$_GET['visio'].'&actuel='.$actuel.'&page1='.$pagep1.'&page2='.$pageetoiles.'&feuille='.$_GET['feuille'].'" class="lien3">></a> ');
								}
							else
								{
								print('<span class="lien3bis">></span> ');
								}
						
						print('</p>
					</div>
					<p class="actions">
						<a href="edc=visio.php?auteur='.$_GET['auteur'].'&visio=articles&actuel='.$actuel.'&page1='.$pagearticles.'&page2='.$pageetoiles.'" class="lien1">Voir ses articles</a><br />
						<a href="edc=visio.php?auteur='.$_GET['auteur'].'&visio=etoiles&actuel='.$actuel.'&page1='.$pagearticles.'&page2='.$pageetoiles.'" class="lien2">Voir ses &eacute;toiles</a><br />');
						$bla=3;
						if($_SESSION['pseudo']!=$_GET['auteur'])
							{
						if(statut($_SESSION['statut'])>=2) print('<a href="edc=visio.php?auteur='.$_GET['auteur'].'&visio=don&actuel='.$actuel.'&page1='.$pagearticles.'&page2='.$pageetoiles.'" class="lien'.($bla++).'">Donner une &eacute;toile</a><br />');
						elseif($_SESSION['statut']=="Joueur" && (5-$res2m+$res3m)!=0) print('<a href="edc=visio.php?auteur='.$_GET['auteur'].'&visio=don&actuel='.$actuel.'&page1='.$pagearticles.'&page2='.$pageetoiles.'" class="lien'.($bla++).'">Donner une &eacute;toile ('.(5-$res2m+$res3m).'/'.(5+$res3m).')</a><br />');
							}
						if($_SESSION['case1']=="Carnet" OR $_SESSION['case2']=="Carnet" OR $_SESSION['case3']=="Carnet" OR $_SESSION['case4']=="Carnet" OR $_SESSION['case5']=="Carnet" OR $_SESSION['case6']=="Carnet")
							{
							$sqltmp = 'SELECT id FROM carnets_tbl WHERE pseudo="'.$_SESSION['pseudo'].'" AND contact="'.$_GET['auteur'].'"';
							$reqtmp = mysql_query($sqltmp);
							$restmp = mysql_num_rows($reqtmp);
							
							if($restmp == 0 AND $_GET['auteur'] != $_SESSION['pseudo']) print('<a href="../ingame/engine=carnet.php?affiche=contacts&ajoutco='.$_GET['auteur'].'" class="lien'.$bla.'">Ajouter &agrave; mes contacts</a>');
							}
						print('
					</p>
						
						');
						}
					elseif($actuel=="etoiles")		//SI ON AFFICHE LES ETOILES
						{
						
						print('
					<p class="titre">Ses derni&egrave;res &eacute;toiles</p>
					<p class="image"></p>
					<a href="edc=visio.php?auteur='.$_GET['auteur'].'&visio='.$_GET['visio'].'&actuel=articles&feuille='.$_GET['feuille'].'" class="lienimage1"></a>
					<ul>
						');
																	//AFFICHAGE DES ETOILES
					for($i = $debut2 ; $i < $fin2 ; $i++)
						{
						print('<li><a href="edc=visio.php?auteur='.mysql_result($req2,$i,cible).'">'.mysql_result($req2,$i,cible).'</a></li>');
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
								print('<a href="edc=visio.php?auteur='.$_GET['auteur'].'&visio='.$_GET['visio'].'&actuel='.$actuel.'&page1='.$pagearticles.'&page2='.$pagem1.'&feuille='.$_GET['feuille'].'" class="lien1"><</a> ');
								}
							else
								{
								print('<span class="lien1bis"><</span> ');
								}
								
							if($pageetoiles > 4)
								{
								print('<a href="edc=visio.php?auteur='.$_GET['auteur'].'&visio='.$_GET['visio'].'&actuel='.$actuel.'&page1='.$pagearticles.'&page2=1&feuille='.$_GET['feuille'].'" class="lien2">1</a> ... ');
								}
							elseif($pageetoiles==4)
								{
								print('<a href="edc=visio.php?auteur='.$_GET['auteur'].'&visio='.$_GET['visio'].'&actuel='.$actuel.'&page1='.$pagearticles.'&page2=1&feuille='.$_GET['feuille'].'" class="lien2">1</a> ');
								}
							if($pagem2 > 0)
								{
								print('<a href="edc=visio.php?auteur='.$_GET['auteur'].'&visio='.$_GET['visio'].'&actuel='.$actuel.'&page1='.$pagearticles.'&page2='.$pagem2.'&feuille='.$_GET['feuille'].'" class="lien2">'.$pagem2.'</a> ');
								}
							if($pagem1 > 0)
								{
								print('<a href="edc=visio.php?auteur='.$_GET['auteur'].'&visio='.$_GET['visio'].'&actuel='.$actuel.'&page1='.$pagearticles.'&page2='.$pagem1.'&feuille='.$_GET['feuille'].'" class="lien2">'.$pagem1.'</a> ');
								}
								
							print('<span class="lien2bis">'.$pageetoiles.'</span> ');

							if($pagep1 <= $pagemax)
								{
								print('<a href="edc=visio.php?auteur='.$_GET['auteur'].'&visio='.$_GET['visio'].'&actuel='.$actuel.'&page1='.$pagearticles.'&page2='.$pagep1.'&feuille='.$_GET['feuille'].'" class="lien2">'.$pagep1.'</a> ');
								}
							if($pagep2 <= $pagemax)
								{
								print('<a href="edc=visio.php?auteur='.$_GET['auteur'].'&visio='.$_GET['visio'].'&actuel='.$actuel.'&page1='.$pagearticles.'&page2='.$pagep2.'&feuille='.$_GET['feuille'].'" class="lien2">'.$pagep2.'</a> ');
								}
							if($pagemax > $pagep2)
								{
								print('... <a href="edc=visio.php?auteur='.$_GET['auteur'].'&visio='.$_GET['visio'].'&actuel='.$actuel.'&page1='.$pagearticles.'&page2='.$pagemax.'&feuille='.$_GET['feuille'].'" class="lien2">'.$pagemax.'</a> <a href="edc=visio.php?auteur='.$_GET['auteur'].'&visio='.$_GET['visio'].'&actuel='.$actuel.'&page1='.$pagearticles.'&page2='.$pagep1.'&feuille='.$_GET['feuille'].'" class="lien3">></a>');
								}
								
							if($pageetoiles < $pagemax)
								{
								print('<a href="edc=visio.php?auteur='.$_GET['auteur'].'&visio='.$_GET['visio'].'&actuel='.$actuel.'&page1='.$pagearticles.'&page2='.$pagep1.'&feuille='.$_GET['feuille'].'" class="lien3">></a> ');
								}
							else
								{
								print('<span class="lien3bis">></span> ');
								}
						
						print('</p>
					</div>
					<p class="actions">
						<a href="edc=visio.php?auteur='.$_GET['auteur'].'&visio=articles&actuel='.$actuel.'&page1='.$pagearticles.'&page2='.$pageetoiles.'" class="lien1">Voir ses articles</a><br />
						<a href="edc=visio.php?auteur='.$_GET['auteur'].'&visio=etoiles&actuel='.$actuel.'&page1='.$pagearticles.'&page2='.$pageetoiles.'" class="lien2">Voir ses &eacute;toiles</a><br />');
						$bla=3;
						if($_SESSION['pseudo']!=$_GET['auteur'])
							{
						if(statut($_SESSION['statut'])>=2) print('<a href="edc=visio.php?auteur='.$_GET['auteur'].'&visio=don&actuel='.$actuel.'&page1='.$pagearticles.'&page2='.$pageetoiles.'" class="lien'.($bla++).'">Donner une &eacute;toile</a><br />');
						elseif($_SESSION['statut']=="Joueur" && (5-$res2m+$res3m)!=0) print('<a href="edc=visio.php?auteur='.$_GET['auteur'].'&visio=don&actuel='.$actuel.'&page1='.$pagearticles.'&page2='.$pageetoiles.'" class="lien'.($bla++).'">Donner une &eacute;toile ('.(5-$res2m+$res3m).'/'.(5+$res3m).')</a><br />');
							}
						if($_SESSION['case1']=="Carnet" OR $_SESSION['case2']=="Carnet" OR $_SESSION['case3']=="Carnet" OR $_SESSION['case4']=="Carnet" OR $_SESSION['case5']=="Carnet" OR $_SESSION['case6']=="Carnet")
							print('<a href="../ingame/engine=ajoutcontact.php?'.$_GET['auteur'].'" class="lien'.$bla.'">Ajouter &agrave; mes contacts</a>');
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
				
				<?php
				
				if($_SESSION['id']!="")
					{
					include('include/inc_infoedc.php');
				
					print('<div id="edc-infopersonnage">
						<!-- INFOS CONCERNANT MON  PERSONNAGE -->');
					include('include/inc_situation.php');
					print('</div>');
					}
				else
					{
				print('<div id="edc-random">
					<!-- AFFICHAGE D\'UNE FICHE ALEATOIRE -->');
					
					include('include/inc_randomedc.php');
					
				print('</div>
				
				<div id="edc-monespace">
					<!-- ACCES A MON ESPACE PERSO -->
					
					<div id="lien-EDC2">
							<p>Connectez-vous pour acc&eacute;der &agrave; votre EDC</p>
					</div>
				</div>');
					}
				?>
				
				<?php include('include/inc_searchedc.php'); ?>
			</div>	
		</div>
	
	</body>
	
</html>
