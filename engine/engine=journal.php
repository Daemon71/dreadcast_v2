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
	
	
$db = mysql_connect('localhost', 'CENSURE', 'CENSURE') or die("Erreur de connexion au serveur des messages.");
mysql_select_db('CENSURE',$db) or die("Erreur de connexion a la base de donnees des messages.");

$sql = 'SELECT nom,type FROM entreprises_tbl WHERE rue= "'.$_SESSION['lieu'].'" AND num= "'.$_SESSION['num'].'"' ;
$req = mysql_query($sql);
$res = mysql_num_rows($req);

if($res == 0 || mysql_result($req,0,type) != "dcn")
	{
	print('<meta http-equiv="refresh" content="0 ; url=http://v2.dreadcast.net/ingame/engine.php"> ');
	exit();
	}
	
$type = mysql_result($req,0,type);
$noment = mysql_result($req,0,nom);

mysql_close($db);

?>
<?php if ((date("H")>=21) || (date("H")<=7)) { include("inc_haut_n.php"); } else { include("inc_haut_j.php");} ?>

<div id="haut">
	<?php include("inc_entete.php"); ?>
	<div class="haut_aligngauche">
		<p>
		<div class="titrepage">
			Journal
		</div>
		<b class="module4ie">
			<?php
			if($_GET['lieu'] == "") print('<a href="engine.php" class="module4"><img src="im_objets/icon_retour.gif" alt="Retour" />Retour</a>');
			elseif($_GET['lieu'] == "guichet" OR $_GET['lieu'] == "archives") print('<a href="engine=journal.php" class="module4"><img src="im_objets/icon_retour.gif" alt="Retour" />Retour</a>');
			?>
		</b>
		</p>
	</div>
</div>
<div id="centre_imperium">
	
	<?php
	$db = mysql_connect('localhost', 'CENSURE', 'CENSURE') or die("Erreur de connexion au serveur des messages.");
	mysql_select_db('CENSURE',$db) or die("Erreur de connexion a la base de donnees des messages.");
	
	if($_GET['lieu'] == "")
		print('<p id="location">Locaux du DC News</p>
		
		<br /><br />
		<p id="textelse">Bienvenue dans les locaux du DreadCast News.<br /><br />
		
		Vous pouvez vous rendre au guichet pour vous procurer le dernier numéro du DC News.<br /><br />
		<a href="engine=journal.php?lieu=guichet">Se rendre au guichet</a><br /><br />
		
		Vous pouvez également vous rendre aux archives pour consulter les anciens numéros.<br /><br />
		<a href="engine=journal.php?lieu=archives">Se rendre aux archives</a><br /><br />
		</p>');
	elseif($_GET['lieu'] == "guichet" && $_GET['action'] == "")
		{
		print('<p id="location">Guichet du DC News</p>
		
		<br /><br />
		<p id="textelse">');
		
		$sql2 = 'SELECT numero,prix FROM DCN_numeros_tbl WHERE paru=1 ORDER BY numero DESC' ;
		$req2 = mysql_query($sql2);
		$sql3 = 'SELECT id FROM DCN_achats_tbl WHERE numero='.mysql_result($req2,0,numero).' AND acheteur="'.$_SESSION['pseudo'].'"' ;
		$req3 = mysql_query($sql3);
		$res3 = mysql_num_rows($req3);
		
		if($res3==0)
			print('<strong>Le DreadCast News n°'.mysql_result($req2,0,numero).' est sorti !</strong><br /><br />
			Vous pouvez l\'acheter, ou le télécharger sur votre AITL 2.0. Son prix est de <strong>'.mysql_result($req2,0,prix).'</strong> crédits.<br />
			<a href="engine=journal.php?lieu=guichet&action=acheter">Acheter</a> - <a href="engine=journal.php?lieu=guichet&action=telecharger">Télécharger sur mon AITL 2.0</a>');
		else
			print('Vous avez téléchargé le DreadCast News n°'.mysql_result($req2,0,numero).'.<br />
			Vous pouvez le lire en vous rendant sur la partie "DC News" de votre AITL 2.0.');
		
		$sql = 'SELECT nombre FROM DCN_abonnes_tbl WHERE abonne="'.$_SESSION['pseudo'].'" AND medium="DCN"' ;
		$req = mysql_query($sql);
		$res = mysql_num_rows($req);
		
		$sql3 = 'SELECT abonne,nombre FROM DCN_abonnes_tbl WHERE abonne LIKE "DCN %" AND medium="DCN"';
		$req3 = mysql_query($sql3);
		
		$nombre_abonnement = preg_replace("#DCN #i","",mysql_result($req3,0,abonne));
		$prix_abonnement = mysql_result($req3,0,nombre);
		
		if($res==0)
			print('<br /><br />Si vous possédez un AITL 2.0, vous pouvez vous abonner à '.$nombre_abonnement.' numéros pour '.$prix_abonnement.' crédits. Vous les recevrez automatiquement sur votre AITL 2.0 lorsqu\'ils paraîtront.<br />
			<a href="engine=journal.php?lieu=guichet&action=abonner">S\'abonner au DC News</a>');
		else
			{
			print('<br /><br />Vous êtes abonnés au');if(mysql_result($req,0,nombre) != 1)print('x <strong>'.mysql_result($req,0,nombre).'</strong>');print(' prochain');if(mysql_result($req,0,nombre) != 1)print('s');print(' numéro');if(mysql_result($req,0,nombre) != 1)print('s');print(' du DreadCast News.');
			}
		
		print('</p>');
		}
	elseif($_GET['lieu'] == "guichet" && $_GET['action'] == "acheter")
		{
		print('<p id="location">Guichet du DC News</p>
		
		<br /><br /><br />
		<p id="textelse">');
		
		$sql = 'SELECT credits,case1,case2,case3,case4,case5,case6 FROM principal_tbl WHERE pseudo="'.$_SESSION['pseudo'].'"';
		$req = mysql_query($sql);
		
		$_SESSION['case1'] = mysql_result($req,0,case1);
		$_SESSION['case2'] = mysql_result($req,0,case2);
		$_SESSION['case3'] = mysql_result($req,0,case3);
		$_SESSION['case4'] = mysql_result($req,0,case4);
		$_SESSION['case5'] = mysql_result($req,0,case5);
		$_SESSION['case6'] = mysql_result($req,0,case6);
		$_SESSION['credits'] = mysql_result($req,0,credits);
		
		$i=1;
		for($i;$i<=6;$i++)if($_SESSION['case'.$i] == "Vide") break;
		if($i == 7)
			print('Vous n\'avez plus de place dans votre inventaire.<br />
			<a href="engine=journal.php?lieu=guichet">Retour</a>');
		else
			{
			$sql2 = 'SELECT numero,prix FROM DCN_numeros_tbl WHERE paru=1 ORDER BY numero DESC' ;
			$req2 = mysql_query($sql2);
			
			if($_SESSION['credits'] >= mysql_result($req2,0,prix))
				{
				$_SESSION['case'.$i] = "DreadCast News ".mysql_result($req2,0,numero);
				$_SESSION['credits'] -= mysql_result($req2,0,prix);
				
				$sql = 'UPDATE principal_tbl SET case'.$i.' = "'.$_SESSION['case'.$i].'", credits = "'.$_SESSION['credits'].'" WHERE pseudo="'.$_SESSION['pseudo'].'"';
				$req = mysql_query($sql);
				
				$sql = 'SELECT budget,benefices FROM entreprises_tbl WHERE nom= "DC Network"' ;
				$req = mysql_query($sql);
				$budget = mysql_result($req,0,budget);
				$benef = mysql_result($req,0,benefices);
				$pvente = mysql_result($req2,0,prix);
				$budget = $budget + $pvente;
				$benef = $benef + $pvente;
				$sql = 'UPDATE entreprises_tbl SET budget= "'.$budget.'" , benefices= "'.$benef.'" WHERE nom= "DC Network"' ;
				mysql_query($sql);
			
				print('<meta http-equiv="refresh" content="0 ; url=engine=inventaire.php"> ');
				exit();
				}
			else
				print('Vous n\'avez pas assez d\'argent sur vous.<br />
				<a href="engine=journal.php?lieu=guichet">Retour</a>');
			}
		
		print('</p>');
		}
	elseif($_GET['lieu'] == "guichet" && $_GET['action'] == "telecharger")
		{
		print('<p id="location">Guichet du DC News</p>
		
		<br /><br /><br />
		<p id="textelse">');
		
		$sql = 'SELECT credits,case1,case2,case3,case4,case5,case6 FROM principal_tbl WHERE pseudo="'.$_SESSION['pseudo'].'"';
		$req = mysql_query($sql);
		
		$_SESSION['case1'] = mysql_result($req,0,case1);
		$_SESSION['case2'] = mysql_result($req,0,case2);
		$_SESSION['case3'] = mysql_result($req,0,case3);
		$_SESSION['case4'] = mysql_result($req,0,case4);
		$_SESSION['case5'] = mysql_result($req,0,case5);
		$_SESSION['case6'] = mysql_result($req,0,case6);
		$_SESSION['credits'] = mysql_result($req,0,credits);
		
		$i=1;
		for($i;$i<=6;$i++) if($_SESSION['case'.$i] == "AITL 2.0") break;
		
		if(statut($_SESSION['statut'])>=2) $i = 1;
		
		if($i == 7)
			print('Vous n\'avez pas d\'AITL 2.0 dans votre inventaire.<br />
			<a href="engine=journal.php?lieu=guichet">Retour</a>');
		else
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
				
				$sql = 'INSERT INTO achats_tbl (acheteur,vendeur,objet,prix,moment) VALUES("'.$_SESSION['pseudo'].'","DC Network","DC News '.mysql_result($req2,0,numero).'","'.mysql_result($req2,0,prix).'","'.time().'")';
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
				
				print('<div style="color:#ccc;text-align:center;"><img src="im_objets/barre_telechargement.gif" /><br />
				Téléchargement en cours...</div>
				
				<meta http-equiv="refresh" content="3 ; url=engine=aitl2.php?type=dcn"> ');
				exit();
				}
			else
				print('Vous n\'avez pas assez d\'argent sur vous.<br />
				<a href="engine=journal.php?lieu=guichet">Retour</a>');
			}
		
		print('</p>');
		}
	elseif($_GET['lieu'] == "guichet" && $_GET['action'] == "abonner")
		{
		print('<p id="location">Guichet du DC News</p>
		
		<br /><br /><br />
		<p id="textelse">');
		
		$sql = 'SELECT credits,case1,case2,case3,case4,case5,case6,credits FROM principal_tbl WHERE pseudo="'.$_SESSION['pseudo'].'"';
		$req = mysql_query($sql);
		
		$_SESSION['case1'] = mysql_result($req,0,case1);
		$_SESSION['case2'] = mysql_result($req,0,case2);
		$_SESSION['case3'] = mysql_result($req,0,case3);
		$_SESSION['case4'] = mysql_result($req,0,case4);
		$_SESSION['case5'] = mysql_result($req,0,case5);
		$_SESSION['case6'] = mysql_result($req,0,case6);
		$_SESSION['credits'] = mysql_result($req,0,credits);
		
		$i=1;
		for($i;$i<=6;$i++) if($_SESSION['case'.$i] == "AITL 2.0") break;
		
		if(statut($_SESSION['statut'])>=2) $i = 1;
		
		if($i == 7)
			print('Vous n\'avez pas d\'AITL 2.0 dans votre inventaire.<br />
			<a href="engine=journal.php?lieu=guichet">Retour</a>');
		else
			{
			$sql = 'SELECT abonne,nombre FROM DCN_abonnes_tbl WHERE abonne LIKE "DCN %" AND medium="DCN"';
			$req = mysql_query($sql);
			
			$nombre_abonnement = preg_replace("#DCN #i","",mysql_result($req,0,abonne));
			$prix_abonnement = mysql_result($req,0,nombre);
			
			if($_SESSION['credits'] >= $prix_abonnement)
				{
				$_SESSION['credits'] -= $prix_abonnement;
				
				$sql = 'UPDATE principal_tbl SET credits = "'.$_SESSION['credits'].'" WHERE pseudo="'.$_SESSION['pseudo'].'"';
				mysql_query($sql);
				
				$sql = 'INSERT INTO DCN_abonnes_tbl(id,abonne,nombre,medium) VALUES("","'.$_SESSION['pseudo'].'","'.$nombre_abonnement.'","DCN")';
				mysql_query($sql);
				
				$sql = 'INSERT INTO achats_tbl (acheteur,vendeur,objet,prix,moment) VALUES("'.$_SESSION['pseudo'].'","DC Network","Abonnement DC News","'.$prix_abonnement.'","'.time().'")';
				mysql_query($sql);
				
				$sql = 'SELECT budget,benefices FROM entreprises_tbl WHERE nom= "DC Network"' ;
				$req = mysql_query($sql);
				$budget = mysql_result($req,0,budget);
				$benef = mysql_result($req,0,benefices);
				$pvente = $prix_abonnement;
				$budget = $budget + $pvente;
				$benef = $benef + $pvente;
				$sql = 'UPDATE entreprises_tbl SET budget= "'.$budget.'" , benefices= "'.$benef.'" WHERE nom= "DC Network"' ;
				mysql_query($sql);
				
				print('<meta http-equiv="refresh" content="0 ; url=engine=journal.php?lieu=guichet"> ');
				exit();
				}
			else
				print('Vous n\'avez pas assez d\'argent sur vous.<br />
				<a href="engine=journal.php?lieu=guichet">Retour</a>');
			}
		
		print('</p>');
		}
	elseif($_GET['lieu'] == "archives" && $_GET['action'] == "")
		{
		print('<p id="location">Archives du DC News</p>
		
		<br /><br />
		<p id="textelse">');
		
		$sql2 = 'SELECT numero,prix FROM DCN_numeros_tbl WHERE paru=1 ORDER BY numero DESC' ;
		$req2 = mysql_query($sql2);
		$res2 = mysql_num_rows($req2);
		
		print('Voici la liste des différents numéros du DC News parus à ce jour :<br /><br />
		<div style="position:relative;left:50px;width:400px;height:150px;overflow:auto;">
		<table>');
		
		for($i=0;$i<$res2;$i++)
			{
			$sql3 = 'SELECT id FROM DCN_achats_tbl WHERE numero='.mysql_result($req2,$i,numero).' AND acheteur="'.$_SESSION['pseudo'].'"' ;
			$req3 = mysql_query($sql3);
			$res3 = mysql_num_rows($req3);
			print('<tr>
				<td>DreadCast News n°'.mysql_result($req2,$i,numero).'</td>
				<td>'.mysql_result($req2,$i,prix).'cr</td>
				<td><a href="engine=journal.php?lieu=archives&action=acheter&id='.mysql_result($req2,$i,numero).'">Acheter</a></td>
				<td>');
				if($res3 == 0)
					print('<a href="engine=journal.php?lieu=archives&action=telecharger&id='.mysql_result($req2,$i,numero).'">Télécharger</a>');
				else
					print('Déjà téléchargé');
				print('</td>
			</tr>');
			}
		print('</table>
		</div>
		</p>');
		}
	elseif($_GET['lieu'] == "archives" && $_GET['action'] == "acheter" && $_GET['id'] != "")
		{
		print('<p id="location">Archives du DC News</p>
		
		<br /><br /><br />
		<p id="textelse">');
		
		$sql = 'SELECT credits,case1,case2,case3,case4,case5,case6 FROM principal_tbl WHERE pseudo="'.$_SESSION['pseudo'].'"';
		$req = mysql_query($sql);
		
		$_SESSION['case1'] = mysql_result($req,0,case1);
		$_SESSION['case2'] = mysql_result($req,0,case2);
		$_SESSION['case3'] = mysql_result($req,0,case3);
		$_SESSION['case4'] = mysql_result($req,0,case4);
		$_SESSION['case5'] = mysql_result($req,0,case5);
		$_SESSION['case6'] = mysql_result($req,0,case6);
		$_SESSION['credits'] = mysql_result($req,0,credits);
		
		$i=1;
		for($i;$i<=6;$i++)if($_SESSION['case'.$i] == "Vide") break;
		if($i == 7)
			print('Vous n\'avez plus de place dans votre inventaire.<br />
			<a href="engine=journal.php?lieu=archives">Retour</a>');
		else
			{
			$sql2 = 'SELECT numero,prix FROM DCN_numeros_tbl WHERE paru=1 AND numero = "'.htmlentities($_GET['id']).'"' ;
			$req2 = mysql_query($sql2);
			
			if($_SESSION['credits'] >= mysql_result($req2,0,prix))
				{
				$_SESSION['case'.$i] = "DreadCast News ".mysql_result($req2,0,numero);
				$_SESSION['credits'] -= mysql_result($req2,0,prix);
				
				$sql = 'UPDATE principal_tbl SET case'.$i.' = "'.$_SESSION['case'.$i].'", credits = "'.$_SESSION['credits'].'" WHERE pseudo="'.$_SESSION['pseudo'].'"';
				$req = mysql_query($sql);
				
				$sql = 'SELECT budget,benefices FROM entreprises_tbl WHERE nom= "DC Network"' ;
				$req = mysql_query($sql);
				$budget = mysql_result($req,0,budget);
				$benef = mysql_result($req,0,benefices);
				$pvente = mysql_result($req2,0,prix);
				$budget = $budget + $pvente;
				$benef = $benef + $pvente;
				$sql = 'UPDATE entreprises_tbl SET budget= "'.$budget.'" , benefices= "'.$benef.'" WHERE nom= "DC Network"' ;
				mysql_query($sql);
			
				print('<meta http-equiv="refresh" content="0 ; url=engine=inventaire.php"> ');
				exit();
				}
			else
				print('Vous n\'avez pas assez d\'argent sur vous.<br />
				<a href="engine=journal.php?lieu=archives">Retour</a>');
			}
		
		print('</p>');
		}
	elseif($_GET['lieu'] == "archives" && $_GET['action'] == "telecharger" && $_GET['id'] != "")
		{
		print('<p id="location">Archives du DC News</p>
		
		<br /><br /><br />
		<p id="textelse">');
		
		$sql = 'SELECT credits,case1,case2,case3,case4,case5,case6 FROM principal_tbl WHERE pseudo="'.$_SESSION['pseudo'].'"';
		$req = mysql_query($sql);
		
		$_SESSION['case1'] = mysql_result($req,0,case1);
		$_SESSION['case2'] = mysql_result($req,0,case2);
		$_SESSION['case3'] = mysql_result($req,0,case3);
		$_SESSION['case4'] = mysql_result($req,0,case4);
		$_SESSION['case5'] = mysql_result($req,0,case5);
		$_SESSION['case6'] = mysql_result($req,0,case6);
		$_SESSION['credits'] = mysql_result($req,0,credits);
		
		$i=1;
		for($i;$i<=6;$i++) if($_SESSION['case'.$i] == "AITL 2.0") break;
		
		if(statut($_SESSION['statut'])>=2) $i = 1;
		
		if($i == 7)
			print('Vous n\'avez pas d\'AITL 2.0 dans votre inventaire.<br />
			<a href="engine=journal.php?lieu=archives">Retour</a>');
		else
			{
			$sql2 = 'SELECT numero,prix FROM DCN_numeros_tbl WHERE paru=1 AND numero = "'.htmlentities($_GET['id']).'"' ;
			$req2 = mysql_query($sql2);
			
			if($_SESSION['credits'] >= mysql_result($req2,0,prix))
				{
				$_SESSION['credits'] -= mysql_result($req2,0,prix);
				
				$sql = 'UPDATE principal_tbl SET credits = "'.$_SESSION['credits'].'" WHERE pseudo="'.$_SESSION['pseudo'].'"';
				mysql_query($sql);
				
				$sql = 'INSERT INTO DCN_achats_tbl(id,acheteur,numero) VALUES("","'.$_SESSION['pseudo'].'","'.mysql_result($req2,0,numero).'")';
				mysql_query($sql);
				
				$sql = 'INSERT INTO achats_tbl (acheteur,vendeur,objet,prix,moment) VALUES("'.$_SESSION['pseudo'].'","DC Network","DC News '.mysql_result($req2,0,numero).'","'.mysql_result($req2,0,prix).'","'.time().'")';
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
				
				print('<div style="color:#ccc;text-align:center;"><img src="im_objets/barre_telechargement.gif" /><br />
				Téléchargement en cours...</div>
				
				<meta http-equiv="refresh" content="3 ; url=engine=aitl2.php?type=dcn"> ');
				exit();
				}
			else
				print('Vous n\'avez pas assez d\'argent sur vous.<br />
				<a href="engine=journal.php?lieu=archives">Retour</a>');
			}
		
		print('</p>');
		}
		
	mysql_close($db);
	?>
	
</div>
<?php if($chat=="oui") 	{ if ((date("H")>=21) || (date("H")<=7)) { include("inc_bas_n_c.php"); } else { include("inc_bas_j_c.php"); } } else {	if ((date("H")>=21) || (date("H")<=7)) { include("inc_bas_n.php"); } else { include("inc_bas_j.php"); }	} ?>
<?php include("inc_bas_de_page.php"); ?>
