<?php
session_start();

if($_SESSION['id']=="")
	{
	print('<meta http-equiv="refresh" content="0 ; url=index.php"> ');
	exit();
	}

$db = mysql_connect('localhost', 'CENSURE', 'CENSURE') or die("Erreur de connexion au serveur des messages.");
mysql_select_db('CENSURE',$db) or die("Erreur de connexion a la base de donnees des messages.");
	
$sql = 'SELECT * FROM wikast_edc_articles_tbl WHERE auteur="'.$_SESSION['pseudo'].'" ORDER BY date DESC';
$req = mysql_query($sql);
$res = mysql_num_rows($req);

if($res==0)
	{
	$textedebienvenue = htmlentities("[centrer]Bienvenue sur votre Espace DreadCast, citoyen [g]".$_SESSION['pseudo']."[/g].[/centrer]
<br />Cet espace est enti�rement d�di� � votre expression. Vous pouvez y �crire les articles que vous souhaitez faire partager � d'autres citoyens. Vous pouvez �galement \"�toiler\" une autre personne, c'est-�-dire la compter parmis vos favoris.
<br />");
if($_SESSION['statut']=="Joueur") $textedebienvenue .= htmlentities("Votre statut de joueur vous permet de disposer de 5 �toiles. Vous pouvez en donner et en recevoir.");
else $textedebienvenue .= htmlentities("Votre statut de VIP vous permet de disposer d'autant d'�toiles que vous le souhaitez.");
$textedebienvenue .= htmlentities("
<br />
<br />Cet article \"Pr�sentation\" est votre carte de visite, le premier � �tre lu par les citoyens. Modifiez-le en cliquant sur le lien suivant : [lien url=<edc=editer.php?article=Pr�sentation&page1=".$_GET['page1']."&page2=".$_GET['page2']."&feuille=".$_GET['feuille'].">]Modifier ma pr�sentation[/lien].
<br />
<br />Vous pouvez �galement changer vos informations personnelles en cliquant ici : [lien url=<edc=infos.php#champ>]Changer mes infos perso[/lien].
<br />
<br />Pour plus d'informations sur l'utilisation de votre EDC, visitez [lien url=<edc=aide.php>]la page d'aide[/lien].");
	$textedebienvenue = str_replace("&lt;br /&gt;","<br />",$textedebienvenue);
	$sql = 'INSERT INTO wikast_edc_articles_tbl(id,auteur,titre,date,contenu) VALUES("","'.$_SESSION['pseudo'].'","Pr&eacute;sentation","'.time().'","'.$textedebienvenue.'")' ;
	mysql_query($sql);
	}

mysql_close($db);

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
					$debut2 = ($debut2>0)?$debut2:0;												//AFFICHAGE DES ETOILES
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
						<a href="edc=afficher.php?type=21&actuel='.$actuel.'page1='.$pagearticles.'&page2='.$pagep1.'" class="lien1">Voir mes &eacute;toiles donn&eacute;es</a><br />
						<a href="edc=afficher.php?type=22&actuel='.$actuel.'page1='.$pagearticles.'&page2='.$pagep1.'" class="lien2">Voir mes &eacute;toiles re&ccedil;ues</a><br />
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
				
				$derniercontenu = transforme_texte($derniercontenu);
				
				if($derniertitre == "Pr&eacute;sentation") $dernierid = "Pr&eacute;sentation";
				
				print('<div id="zone-centre">
					<p class="titre">'.$derniertitre.'</p>
					<p class="image"><a href="edc=editer.php?article='.$dernierid.'&page1='.$_GET['page1'].'&page2='.$_GET['page2'].'&feuille='.$_GET['feuille'].'" class="modifier"></a>');
					
					if($dernierid != "Pr&eacute;sentation")
						{
						print(' <a href="javascript:ConfirmSupprArticle(\''.$dernierid.'\');" class="supprimer"></a> <a href="edc=commentaires.php?article='.$dernierid.'" class="commentaires'); if($nouveaucommentaire=="oui") print('2'); print('">'); print($res4); print(' commentaire'); if($res4!=1) print('s'); print('</a>');
						}
					
					print('
						<span>'.date('d/m/y',$dernierdate).' &agrave; '.date('H:i',$dernierdate).'</span></p>
					<div class="texte">
						<div class="style1">'.$derniercontenu.'</div>
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
						<a href="edc=afficher.php?type=1&actuel='.$actuel.'page1='.$pagearticles.'&page2='.$pagep1.'" class="lien2">Voir tous mes articles</a><br />
						<a href="edc=editer.php?article=Pr�sentation&page1='.$_GET['page1'].'&page2='.$_GET['page2'].'&feuille='.$_GET['feuille'].'" class="lien3">Modifier ma pr&eacute;sentation</a><br />
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
					<a href="edc.php?actuel=etoiles&feuille='.$_GET['feuille'].'" class="lienimage2"></a>
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
								print('<a href="edc.php?actuel='.$actuel.'&page1='.$pagem1.'&page2='.$pageetoiles.'&feuille='.$_GET['feuille'].'" class="lien1"><</a> ');
								}
							else
								{
								print('<span class="lien1bis"><</span> ');
								}
								
							if($pagearticles > 4)
								{
								print('<a href="edc.php?actuel='.$actuel.'&page1=1&page2='.$pageetoiles.'&feuille='.$_GET['feuille'].'" class="lien2">1</a> ... ');
								}
							elseif($pagearticles==4)
								{
								print('<a href="edc.php?actuel='.$actuel.'&page1=1&page2='.$pageetoiles.'&feuille='.$_GET['feuille'].'" class="lien2">1</a> ');
								}
							if($pagem2 > 0)
								{
								print('<a href="edc.php?actuel='.$actuel.'&page1='.$pagem2.'&page2='.$pageetoiles.'&feuille='.$_GET['feuille'].'" class="lien2">'.$pagem2.'</a> ');
								}
							if($pagem1 > 0)
								{
								print('<a href="edc.php?actuel='.$actuel.'&page1='.$pagem1.'&page2='.$pageetoiles.'&feuille='.$_GET['feuille'].'" class="lien2">'.$pagem1.'</a> ');
								}
								
							print('<span class="lien2bis">'.$pagearticles.'</span> ');

							if($pagep1 <= $pagemax)
								{
								print('<a href="edc.php?actuel='.$actuel.'&page1='.$pagep1.'&page2='.$pageetoiles.'&feuille='.$_GET['feuille'].'" class="lien2">'.$pagep1.'</a> ');
								}
							if($pagep2 <= $pagemax)
								{
								print('<a href="edc.php?actuel='.$actuel.'&page1='.$pagep2.'&page2='.$pageetoiles.'&feuille='.$_GET['feuille'].'" class="lien2">'.$pagep2.'</a> ');
								}
							if($pagemax > $pagep2)
								{
								print('... <a href="edc.php?actuel='.$actuel.'&page1='.$pagemax.'&page2='.$pageetoiles.'&feuille='.$_GET['feuille'].'" class="lien2">'.$pagemax.'</a> <a href="edc.php?page1='.$pagearticles.'&page2='.$pagep1.'&feuille='.$_GET['feuille'].'" class="lien3">></a>');
								}
								
							if($pagearticles < $pagemax)
								{
								print('<a href="edc.php?actuel='.$actuel.'&page1='.$pagep1.'&page2='.$pageetoiles.'&feuille='.$_GET['feuille'].'" class="lien3">></a> ');
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
						<a href="edc=editer.php?article=Pr�sentation&page1='.$_GET['page1'].'&page2='.$_GET['page2'].'&feuille='.$_GET['feuille'].'&actuel='.$actuel.'" class="lien3">Modifier ma pr&eacute;sentation</a><br />
						<a href="edc=infos.php?page1='.$pagearticles.'&page2='.$pageetoiles.'&actuel='.$actuel.'" class="lien4">Changer mes infos perso</a><br />
					</p>
						
						');
						}
					elseif($actuel=="etoiles")		//SI ON AFFICHE LES ETOILES
						{
						
						print('
					<p class="titre">Mes derni&egrave;res &eacute;toiles</p>
					<p class="image"></p>
					<a href="edc.php?actuel=articles&feuille='.$_GET['feuille'].'" class="lienimage1"></a>
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
								print('<a href="edc.php?actuel='.$actuel.'&page1='.$pagearticles.'&page2='.$pagem1.'&feuille='.$_GET['feuille'].'" class="lien1"><</a> ');
								}
							else
								{
								print('<span class="lien1bis"><</span> ');
								}
								
							if($pageetoiles > 4)
								{
								print('<a href="edc.php?actuel='.$actuel.'&page1='.$pagearticles.'&page2=1&feuille='.$_GET['feuille'].'" class="lien2">1</a> ... ');
								}
							elseif($pageetoiles==4)
								{
								print('<a href="edc.php?actuel='.$actuel.'&page1='.$pagearticles.'&page2=1&feuille='.$_GET['feuille'].'" class="lien2">1</a> ');
								}
							if($pagem2 > 0)
								{
								print('<a href="edc.php?actuel='.$actuel.'&page1='.$pagearticles.'&page2='.$pagem2.'&feuille='.$_GET['feuille'].'" class="lien2">'.$pagem2.'</a> ');
								}
							if($pagem1 > 0)
								{
								print('<a href="edc.php?actuel='.$actuel.'&page1='.$pagearticles.'&page2='.$pagem1.'&feuille='.$_GET['feuille'].'" class="lien2">'.$pagem1.'</a> ');
								}
								
							print('<span class="lien2bis">'.$pageetoiles.'</span> ');

							if($pagep1 <= $pagemax)
								{
								print('<a href="edc.php?actuel='.$actuel.'&page1='.$pagearticles.'&page2='.$pagep1.'&feuille='.$_GET['feuille'].'" class="lien2">'.$pagep1.'</a> ');
								}
							if($pagep2 <= $pagemax)
								{
								print('<a href="edc.php?actuel='.$actuel.'&page1='.$pagearticles.'&page2='.$pagep2.'&feuille='.$_GET['feuille'].'" class="lien2">'.$pagep2.'</a> ');
								}
							if($pagemax > $pagep2)
								{
								print('... <a href="edc.php?actuel='.$actuel.'&page1='.$pagearticles.'&page2='.$pagemax.'&feuille='.$_GET['feuille'].'" class="lien2">'.$pagemax.'</a> <a href="edc.php?page1='.$pagearticles.'&page2='.$pagep1.'&feuille='.$_GET['feuille'].'" class="lien3">></a>');
								}
								
							if($pageetoiles < $pagemax)
								{
								print('<a href="edc.php?actuel='.$actuel.'&page1='.$pagearticles.'&page2='.$pagep1.'&feuille='.$_GET['feuille'].'" class="lien3">></a> ');
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
