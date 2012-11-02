<?php
session_start();

if($_SESSION['id']=="")
	{
	print('<meta http-equiv="refresh" content="0 ; url=index.php"> ');
	exit();
	}
	
include('include/inc_head.php');
include('../ingame/fonctions/fonctions_enregistrement.php');
include('../ingame/fonctions/fonctions_generales.php');

if($_POST['enregistre']!="")
	{
	$db = mysql_connect('localhost', 'CENSURE', 'CENSURE') or die("Erreur de connexion au serveur des messages.");
	mysql_select_db('CENSURE',$db) or die("Erreur de connexion a la base de donnees des messages.");
	
	$message = stripslashes(htmlentities($_POST['message'],ENT_QUOTES));
	$message = str_replace("\n", "<br />",$message);
		
	$sql = 'UPDATE wikast_joueurs_tbl SET commentaire="'.$message.'" WHERE pseudo="'.$_SESSION['pseudo'].'"';
	mysql_query($sql);
		
	
	$mail=($_POST['mail']=="")?"":"mail-";
	$age=($_POST['age']=="")?"":"age-";
	$taille=($_POST['taille']=="")?"":"taille-";
	$metier=($_POST['metier']=="")?"":"metier-";
	$cercle=($_POST['cercle']=="")?"":"cercle-";
	$equipement=($_POST['equipement']=="")?"":"equipement-";
	$stats=($_POST['stats']=="")?"":"stats-";
	$logement=($_POST['logement']=="")?"":"logement-";
	$sante=($_POST['sante']=="")?"":"sante-";
	$fatigue=($_POST['fatigue']=="")?"":"fatigue-";
	$nutrition=($_POST['nutrition']=="")?"":"nutrition-";
	//$alcool=($_POST['alcool']=="")?"":"alcool-";
	$casier=($_POST['casier']=="")?"":"casier-";
	$action=($_POST['action']=="")?"":"action-";
	$indice=($_POST['indice']=="")?"":"indice-";
	$reaction=($_POST['reaction']=="")?"":"reaction-";
	$signature=($_POST['signature']=="")?"":"signature-";
	$titres=($_POST['titres']=="")?"":"titres-";
	$exp=($_POST['exp']=="")?"":"exp-";
	$statjeu=($_POST['statjeu']=="")?"":"statjeu-";
	$titre_choisi = ($_POST['affiche_titre']=="" && possede_talent($_POST['affiche_titre']))?"":"talentchoisi:".strtolower($_POST['affiche_titre'])."-";
	
	$sql = 'UPDATE wikast_joueurs_tbl SET infoperso="-'.$mail.$age.$taille.$metier.$cercle.$equipement.$stats.$logement.$sante.$fatigue.$nutrition.$alcool.$casier.$action.$indice.$reaction.$signature.$titres.$exp.$statjeu.$titre_choisi.'" WHERE pseudo="'.$_SESSION['pseudo'].'"';
	mysql_query($sql);
		
	mysql_close($db);
	}
	
?>

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

				$sql = 'SELECT infoperso,commentaire,edc_vu FROM wikast_joueurs_tbl WHERE pseudo="'.$_SESSION['pseudo'].'"';
				$req = mysql_query($sql);
				$res = mysql_num_rows($req);
				
				if($res != 0)
					{
					$infos = mysql_result($req,0,infoperso);
					$comm = mysql_result($req,0,commentaire);
					$comm = str_replace("<br />", "",$comm);
					$edcvu = mysql_result($req,0,edc_vu);
					}
				else
					{
					$infos = "";
					$comm = "";
					$edcvu = 0;
					}
					
				$sql = 'SELECT id FROM wikast_forum_sujets_tbl WHERE auteur="'.$_SESSION['pseudo'].'"';
				$req = mysql_query($sql);
				$ressujets = mysql_num_rows($req);
				$sql = 'SELECT id FROM wikast_forum_posts_tbl WHERE auteur="'.$_SESSION['pseudo'].'"';
				$req = mysql_query($sql);
				$resposts = mysql_num_rows($req);
				$sql = 'SELECT id FROM wikast_edc_commentaires_tbl WHERE auteur="'.$_SESSION['pseudo'].'"';
				$req = mysql_query($sql);
				$rescomms = mysql_num_rows($req);
				$sql = 'SELECT id FROM wikast_edc_articles_tbl WHERE auteur="'.$_SESSION['pseudo'].'"';
				$req = mysql_query($sql);
				$resart = mysql_num_rows($req);
				$sql = 'SELECT id FROM wikast_edc_etoiles_tbl WHERE auteur="'.$_SESSION['pseudo'].'"';
				$req = mysql_query($sql);
				$reset1 = mysql_num_rows($req);
				$sql = 'SELECT id FROM wikast_edc_etoiles_tbl WHERE cible="'.$_SESSION['pseudo'].'"';
				$req = mysql_query($sql);
				$reset2 = mysql_num_rows($req);
				
				print('
				<div id="zone-centre">
					<p class="titre">Mes informations personnelles</p>
					<p class="image">');if($_POST['enregistre']=="true") print('<span class="commentaires5">Donn&eacute;es enregistr&eacute;es</span>');print('</p>
					<div class="texte">
						<div class="style1">
							Informations g&eacute;n&eacute;rales :<br /><br />
							J\'ai post&eacute; <span class="couleur1">'.$ressujets.'</span> sujet'); if($ressujets!=1) print('s'); print(' sur le forum<br />
							J\'ai post&eacute; <span class="couleur1">'.$resposts.'</span> message'); if($resposts!=1) print('s'); print(' sur le forum<br />
							J\'ai &eacute;crit <span class="couleur1">'.$resart.'</span> article'); if($resart!=1) print('s'); print('<br />
							J\'ai &eacute;crit <span class="couleur1">'.$rescomms.'</span> commentaire'); if($rescomms!=1) print('s'); print('<br />
							J\'ai donn&eacute; <span class="couleur1">'.$reset1.'</span> &eacute;toile'); if($reset1!=1) print('s'); print('<br />
							J\'ai re&ccedil;u <span class="couleur1">'.$reset2.'</span> &eacute;toile'); if($reset1!=1) print('s'); print('<br />
							Mon EDC a &eacute;t&eacute; visionn&eacute; <span class="couleur1">'.$edcvu.'</span> fois<br /><br />
							Informations du jeu que je souhaite rendre visible :<br /><br />
							<form action="edc=infos.php?actuel='.$actuel.'&page1='.$pagearticles.'&page2='.$pageetoiles.'" method="post" id="champ" name="poster">
								<input type="hidden" name="enregistre" value="true" />
								<div class="zone1">
									<input type="checkbox" name="mail" '); if(ereg('-mail-',$infos)) print('checked="checked" '); print('/> <label for="mail">Mon mail</label><br />
									<input type="checkbox" name="age" '); if(ereg('-age-',$infos)) print('checked="checked" '); print('/> <label for="age">Mon &acirc;ge</label><br />
									<input type="checkbox" name="taille" '); if(ereg('-taille-',$infos)) print('checked="checked" '); print('/> <label for="taille">Ma taille</label><br />
									<input type="checkbox" name="sante" '); if(ereg('-sante-',$infos)) print('checked="checked" '); print('/> <label for="sante">Ma sant&eacute;</label><br />
									<input type="checkbox" name="fatigue" '); if(ereg('-fatigue-',$infos)) print('checked="checked" '); print('/> <label for="fatigue">Mon &eacute;tat</label><br />
									<input type="checkbox" name="nutrition" '); if(ereg('-nutrition-',$infos)) print('checked="checked" '); print('/> <label for="nutrition">Ma nutrition</label><br />
									<!--<input type="checkbox" name="alcool" '); if(ereg('-alcool-',$infos)) print('checked="checked" '); print('/> <label for="alcool">Mon taux d\'alcool&eacute;mie</label><br />-->
									<input type="checkbox" name="action" '); if(ereg('-action-',$infos)) print('checked="checked" '); print('/> <label for="action">Mon action actuelle</label><br />
									<input type="checkbox" name="titres" '); if(ereg('-titres-',$infos)) print('checked="checked" '); print('/> <label for="titres">Mes titres</label><br />
									<input type="checkbox" name="exp" '); if(ereg('-exp-',$infos)) print('checked="checked" '); print('/> <label for="exp">Mon exp&eacute;rience</label>
								</div>
								<div class="zone2">
									<input type="checkbox" name="metier" '); if(ereg('-metier-',$infos)) print('checked="checked" '); print('/> <label for="metier">Mon m&eacute;tier</label><br />
									<input type="checkbox" name="cercle" '); if(ereg('-cercle-',$infos)) print('checked="checked" '); print('/> <label for="cercle">Mon cercle</label><br />
									<input type="checkbox" name="equipement" '); if(ereg('-equipement-',$infos)) print('checked="checked" '); print('/> <label for="equipement">Mon &eacute;quipement</label><br />
									<input type="checkbox" name="stats" '); if(ereg('-stats-',$infos)) print('checked="checked" '); print('/> <label for="stats">Mes statistiques</label><br />
									<input type="checkbox" name="logement" '); if(ereg('-logement-',$infos)) print('checked="checked" '); print('/> <label for="logement">Mon logement</label><br />
									<input type="checkbox" name="casier" '); if(ereg('-casier-',$infos)) print('checked="checked" '); print('/> <label for="casier">Mon casier judiciaire</label><br />
									<input type="checkbox" name="indice" '); if(ereg('-indice-',$infos)) print('checked="checked" '); print('/> <label for="indice">Mon indice de recherche</label><br />
									<input type="checkbox" name="reaction" '); if(ereg('-reaction-',$infos)) print('checked="checked" '); print('/> <label for="reaction">Mes r&eacute;actions</label><br/>
									<input type="checkbox" name="statjeu" '); if(ereg('-statjeu-',$infos)) print('checked="checked" '); print('/> <label for="statjeu">Mes stats de jeu</label>
								</div>');
								
								if(ereg('-talentchoisi:',$infos)) $talent_choisi = preg_replace("#(.+)-talentchoisi:(.+)-#isU","$2",$infos);
								else $talent_choisi="";
								
								$sql = 'SELECT titre,type FROM titres_tbl WHERE pseudo="'.$_SESSION['pseudo'].'" ORDER BY type,titre';
								$req = mysql_query($sql);
								$res = mysql_num_rows($req);
								
								print('<br /><br /><div>
									Afficher mon titre favoris sur le Wikast (uniquement ceux dont les crit&egrave;res sont encore valides) :<br />
									<select name="affiche_titre">
										<option '.(($talent_choisi=="")?'selected':'').' value="">Aucun</option>');
										for($k=0;$k<$res;$k++) {
											$tmp = verif_accomplissement(strtolower(mysql_result($req,$k,titre)),$_SESSION['pseudo']);
											if($tmp[0] || mysql_result($req,$k,type)=="Niveau") print('<option '.(($talent_choisi==strtolower(mysql_result($req,$k,titre)))?'selected':'').' value="'.strtolower(mysql_result($req,$k,titre)).'">'.ucfirst(mysql_result($req,$k,titre)).'</option>');
										}
									print('</select>
								</div><br />');
								
								print('<div class="zone3">
									Commentaire : <br />(<label for="signature">Utiliser comme signature sur le forum</label> <input type="checkbox" name="signature" '); if(ereg('-signature-',$infos)) print('checked="checked" '); print('/>)<br />
									<textarea name="message" id="textarea">'.$comm.'</textarea>
									<div id="DCcode">
										<a href="javascript:AddText(\'[g]\',\'\',\'[/g]\');" title="Mettre le texte en gras" class="type1"><strong>G</strong></a>
										<a href="javascript:AddText(\'[i]\',\'\',\'[/i]\');" title="Mettre le texte en italique" class="type1"><em>I</em></a>
										<a href="javascript:AddText(\'[img url=<URL DE VOTRE IMAGE> /]\',\'\',\'\');" title="Afficher une image">IMG</a>
										<a href="javascript:AddText(\'[lien url=<URL DE VOTRE LIEN>]\',\'\',\'[/lien]\');" title="Cr&eacute;er un lien">LIEN</a>
										<a href="javascript:AddText(\'[quote]\',\'\',\'[/quote]\');" title="Citer un texte">QUOTE</a>
										<a href="javascript:AddText(\'[centrer]\',\'\',\'[/centrer]\');" title="Centrer un texte">CENTRER</a>
									</div>
									<input name="submit" type="submit" value="Envoyer" id="ok2" />
								</div>
							</form>
						</div>
					</div>
				</div>');
				
				
				///////////////////////////////////////////////////////// DROITE /////////////////////////////////////////////////////////
				
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
								print('<a href="edc=infos.php?page1='.$pagem1.'&page2='.$pageetoiles.'&feuille='.$_GET['feuille'].'" class="lien1"><</a> ');
								}
							else
								{
								print('<span class="lien1bis"><</span> ');
								}
								
							if($pagearticles > 4)
								{
								print('<a href="edc=infos.php?page1=1&page2='.$pageetoiles.'&feuille='.$_GET['feuille'].'" class="lien2">1</a> ... ');
								}
							elseif($pagearticles==4)
								{
								print('<a href="edc=infos.php?page1=1&page2='.$pageetoiles.'&feuille='.$_GET['feuille'].'" class="lien2">1</a> ');
								}
							if($pagem2 > 0)
								{
								print('<a href="edc=infos.php?page1='.$pagem2.'&page2='.$pageetoiles.'&feuille='.$_GET['feuille'].'" class="lien2">'.$pagem2.'</a> ');
								}
							if($pagem1 > 0)
								{
								print('<a href="edc=infos.php?page1='.$pagem1.'&page2='.$pageetoiles.'&feuille='.$_GET['feuille'].'" class="lien2">'.$pagem1.'</a> ');
								}
								
							print('<span class="lien2bis">'.$pagearticles.'</span> ');

							if($pagep1 <= $pagemax)
								{
								print('<a href="edc=infos.php?page1='.$pagep1.'&page2='.$pageetoiles.'&feuille='.$_GET['feuille'].'" class="lien2">'.$pagep1.'</a> ');
								}
							if($pagep2 <= $pagemax)
								{
								print('<a href="edc=infos.php?page1='.$pagep2.'&page2='.$pageetoiles.'&feuille='.$_GET['feuille'].'" class="lien2">'.$pagep2.'</a> ');
								}
							if($pagemax > $pagep2)
								{
								print('... <a href="edc=infos.php?page1='.$pagemax.'&page2='.$pageetoiles.'&feuille='.$_GET['feuille'].'" class="lien2">'.$pagemax.'</a> <a href="edc.php?page1='.$pagearticles.'&page2='.$pagep1.'&feuille='.$_GET['feuille'].'" class="lien3">></a>');
								}
								
							if($pagearticles < $pagemax)
								{
								print('<a href="edc=infos.php?page1='.$pagep1.'&page2='.$pageetoiles.'&feuille='.$_GET['feuille'].'" class="lien3">></a> ');
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
						<a href="edc=editer.php?article=Présentation" class="lien3">Modifier ma pr&eacute;sentation</a><br />
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
					<a href="edc=infos.php?actuel=etoiles&feuille='.$_GET['feuille'].'" class="lienimage2"></a>
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
								print('<a href="edc=infos.php?actuel='.$actuel.'&page1='.$pagem1.'&page2='.$pageetoiles.'&feuille='.$_GET['feuille'].'" class="lien1"><</a> ');
								}
							else
								{
								print('<span class="lien1bis"><</span> ');
								}
								
							if($pagearticles > 4)
								{
								print('<a href="edc=infos.php?actuel='.$actuel.'&page1=1&page2='.$pageetoiles.'&feuille='.$_GET['feuille'].'" class="lien2">1</a> ... ');
								}
							elseif($pagearticles==4)
								{
								print('<a href="edc=infos.php?actuel='.$actuel.'&page1=1&page2='.$pageetoiles.'&feuille='.$_GET['feuille'].'" class="lien2">1</a> ');
								}
							if($pagem2 > 0)
								{
								print('<a href="edc=infos.php?actuel='.$actuel.'&page1='.$pagem2.'&page2='.$pageetoiles.'&feuille='.$_GET['feuille'].'" class="lien2">'.$pagem2.'</a> ');
								}
							if($pagem1 > 0)
								{
								print('<a href="edc=infos.php?actuel='.$actuel.'&page1='.$pagem1.'&page2='.$pageetoiles.'&feuille='.$_GET['feuille'].'" class="lien2">'.$pagem1.'</a> ');
								}
								
							print('<span class="lien2bis">'.$pagearticles.'</span> ');

							if($pagep1 <= $pagemax)
								{
								print('<a href="edc=infos.php?actuel='.$actuel.'&page1='.$pagep1.'&page2='.$pageetoiles.'&feuille='.$_GET['feuille'].'" class="lien2">'.$pagep1.'</a> ');
								}
							if($pagep2 <= $pagemax)
								{
								print('<a href="edc=infos.php?actuel='.$actuel.'&page1='.$pagep2.'&page2='.$pageetoiles.'&feuille='.$_GET['feuille'].'" class="lien2">'.$pagep2.'</a> ');
								}
							if($pagemax > $pagep2)
								{
								print('... <a href="edc=infos.php?actuel='.$actuel.'&page1='.$pagemax.'&page2='.$pageetoiles.'&feuille='.$_GET['feuille'].'" class="lien2">'.$pagemax.'</a> <a href="edc.php?page1='.$pagearticles.'&page2='.$pagep1.'&feuille='.$_GET['feuille'].'" class="lien3">></a>');
								}
								
							if($pagearticles < $pagemax)
								{
								print('<a href="edc=infos.php?actuel='.$actuel.'&page1='.$pagep1.'&page2='.$pageetoiles.'&feuille='.$_GET['feuille'].'" class="lien3">></a> ');
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
						<a href="edc=editer.php?article=Présentation&page1='.$pagearticles.'&page2='.$pageetoiles.'&actuel='.$actuel.'" class="lien3">Modifier ma pr&eacute;sentation</a><br />
						<a href="edc=infos.php?page1='.$pagearticles.'&page2='.$pageetoiles.'&actuel='.$actuel.'" class="lien4">Changer mes infos perso</a><br />
					</p>
						
						');
						}
					elseif($actuel=="etoiles")		//SI ON AFFICHE LES ETOILES
						{
						
						print('
					<p class="titre">Mes derni&egrave;res &eacute;toiles</p>
					<p class="image"></p>
					<a href="edc=infos.php?actuel=articles&feuille='.$_GET['feuille'].'" class="lienimage1"></a>
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
								print('<a href="edc=infos.php?actuel='.$actuel.'&page1='.$pagearticles.'&page2='.$pagem1.'&feuille='.$_GET['feuille'].'" class="lien1"><</a> ');
								}
							else
								{
								print('<span class="lien1bis"><</span> ');
								}
								
							if($pageetoiles > 4)
								{
								print('<a href="edc=infos.php?actuel='.$actuel.'&page1='.$pagearticles.'&page2=1&feuille='.$_GET['feuille'].'" class="lien2">1</a> ... ');
								}
							elseif($pageetoiles==4)
								{
								print('<a href="edc=infos.php?actuel='.$actuel.'&page1='.$pagearticles.'&page2=1&feuille='.$_GET['feuille'].'" class="lien2">1</a> ');
								}
							if($pagem2 > 0)
								{
								print('<a href="edc=infos.php?actuel='.$actuel.'&page1='.$pagearticles.'&page2='.$pagem2.'&feuille='.$_GET['feuille'].'" class="lien2">'.$pagem2.'</a> ');
								}
							if($pagem1 > 0)
								{
								print('<a href="edc=infos.php?actuel='.$actuel.'&page1='.$pagearticles.'&page2='.$pagem1.'&feuille='.$_GET['feuille'].'" class="lien2">'.$pagem1.'</a> ');
								}
								
							print('<span class="lien2bis">'.$pageetoiles.'</span> ');

							if($pagep1 <= $pagemax)
								{
								print('<a href="edc=infos.php?actuel='.$actuel.'&page1='.$pagearticles.'&page2='.$pagep1.'&feuille='.$_GET['feuille'].'" class="lien2">'.$pagep1.'</a> ');
								}
							if($pagep2 <= $pagemax)
								{
								print('<a href="edc=infos.php?actuel='.$actuel.'&page1='.$pagearticles.'&page2='.$pagep2.'&feuille='.$_GET['feuille'].'" class="lien2">'.$pagep2.'</a> ');
								}
							if($pagemax > $pagep2)
								{
								print('... <a href="edc=infos.php?actuel='.$actuel.'&page1='.$pagearticles.'&page2='.$pagemax.'&feuille='.$_GET['feuille'].'" class="lien2">'.$pagemax.'</a> <a href="edc=infos.php?page1='.$pagearticles.'&page2='.$pagep1.'&feuille='.$_GET['feuille'].'" class="lien3">></a>');
								}
								
							if($pageetoiles < $pagemax)
								{
								print('<a href="edc=infos.php?actuel='.$actuel.'&page1='.$pagearticles.'&page2='.$pagep1.'&feuille='.$_GET['feuille'].'" class="lien3">></a> ');
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
