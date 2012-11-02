<?php
session_start();

/*if($_SESSION['statut'] != "Administrateur")
	{
	print('<meta http-equiv="refresh" content="0 ; url=http://v2.dreadcast.net/ingame/engine.php"> ');
	exit();
	}*/
	
if($_SESSION['statut'] == "Administrateur")
	{
	if($_GET['edit'] != "")
		{
		$db = mysql_connect('localhost', 'CENSURE', 'CENSURE') or die("Erreur de connexion au serveur des messages.");
		mysql_select_db('CENSURE',$db) or die("Erreur de connexion a la base de donnees des messages.");
		
		$sql = 'SELECT * FROM FAQ_tbl WHERE id='.htmlentities($_GET['edit']) ;
		$req = mysql_query($sql);
		mysql_close($db);
		
		$question = mysql_result($req,0,question);
		$reponse = mysql_result($req,0,reponse);
		}
	if($_GET['edit2'] != "" && $_POST['question2'] != "" && $_POST['reponse2'] != "")
		{
		$db = mysql_connect('localhost', 'CENSURE', 'CENSURE') or die("Erreur de connexion au serveur des messages.");
		mysql_select_db('CENSURE',$db) or die("Erreur de connexion a la base de donnees des messages.");
		
		$question = nl2br(htmlentities(stripslashes($_POST['question2'])));
		$reponse = nl2br(htmlentities(stripslashes($_POST['reponse2'])));
		
		$sql = 'UPDATE FAQ_tbl SET question="'.$question.'", reponse="'.$reponse.'" WHERE id='.htmlentities($_GET['edit2']) ;
		$req = mysql_query($sql);
		mysql_close($db);
		}
	if($_GET['del'] != "")
		{
		$db = mysql_connect('localhost', 'CENSURE', 'CENSURE') or die("Erreur de connexion au serveur des messages.");
		mysql_select_db('CENSURE',$db) or die("Erreur de connexion a la base de donnees des messages.");
		
		$sql = 'DELETE FROM FAQ_tbl WHERE id='.htmlentities($_GET['del']) ;
		mysql_query($sql);
		mysql_close($db);
		}
	if($_POST['categorie'] != "" && $_POST['question'] != "" && $_POST['reponse'] != "")
		{
		$db = mysql_connect('localhost', 'CENSURE', 'CENSURE') or die("Erreur de connexion au serveur des messages.");
		mysql_select_db('CENSURE',$db) or die("Erreur de connexion a la base de donnees des messages.");
		$question = nl2br(htmlentities(stripslashes($_POST['question'])));
		$reponse = nl2br(htmlentities(stripslashes($_POST['reponse'])));
		$categorie = ($_POST['categorieAutre'] != "")?nl2br(htmlentities(stripslashes($_POST['categorieAutre']))):nl2br(htmlentities(stripslashes($_POST['categorie'])));
		$sql = 'INSERT INTO FAQ_tbl(id,question,reponse,categorie) VALUES("","'.$question.'","'.$reponse.'","'.$categorie.'")' ;
		mysql_query($sql);
		mysql_close($db);
		}
	}

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">

<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="fr" >

	<head>
		<title>Dreadcast</title>
       	<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
		<meta http-equiv="Content-Style-Type" content="text/css" />
		<meta http-equiv="Content-Language" content="fr" />
		<meta name="description" content="Dreadcast est un jeu de role en ligne futuriste gratuit (jdr): simulation d'un jeu en ligne de strat&eacute;gie, jouez au jeu et choisissez votre role." />
		<meta name="keywords" lang="fr" content="Dreadcast, futuriste, jeu video, jeu en ligne, communauté, simulation, jeux gratuits, monde virtuel, RPG, cyber-punk, jeux, rôles, mmo, mmorpg, php, futur, empire, communautaire, gratuit, multijoueur, jeu de gestion, jeu de stratégie, jeu online, Action-RPG, rôle, Science-Fiction, Fantastique, heroic fantasy , jouer, gratui, gratuits, jeux en ligne, internet, net, web" />
		<meta name="author" content="MSpixel" />
		<meta name="reply-to" content="dreadcast@mspixel.fr" />
		<meta name="revisit-after" content="1 day" />
		<meta name="robots" content="all" />
		<link rel="shortcut icon" type="image/x-icon" href="im_objets/favicon.ico" />
		<script type="text/javascript" src="javascript/jQuery.js"></script>
		<style>
			*{margin:0;padding:0;outline:0;text-decoration:none;}
			body{background:#191919;font-family:'Georgia';}
			a img{border:0;}
			#titre{position:absolute;top:40px;margin-left:-400px;left:50%;width:800px;font-size:13px;}
			#titre a{color:#AAA;}
			#titre h1 a{color:#FFF;}
			#page{position:absolute;top:110px;margin-left:-400px;left:50%;width:800px;padding-bottom:20px;}
			.categorie{font-family:Verdana;font-variant:small-caps;color:#555;border-bottom:2px solid #333;margin-bottom:10px;}			
			.question{margin:0 10px 8px 10px;font-weight:normal;color:#FFF;text-align:justify;}
			.up{position:relative;top:4px;margin-left:10px;margin-right:8px;display:block;width:20px;height:20px;background:url(im/lienHaut.png) 0 0 no-repeat;float:left;}
			.edit{position:relative;top:4px;margin-right:8px;display:block;width:20px;height:20px;background:url(im/lienEdit.png) 0 0 no-repeat;float:left;}
			.del{position:relative;top:4px;margin-left:10px;margin-right:8px;display:block;width:20px;height:20px;background:url(im/lienDel.png) 0 0 no-repeat;float:left;}
			.reponse{margin:0 10px 15px 10px;font-size:13px;color:#AAA;text-align:justify;}
			.reponse p{margin-bottom:8px;}
			#nouveau{padding-top:10px;color:#FFF;}
			#nouveau a{color:#2B569A;}
			#nouveau a:hover{border-bottom:1px solid #2B569A;}
			form #champAutre{font-family:Arial;color:#FFF;font-size:15px;border:1px solid #AAA;background:#191919;padding:5px;}
			form #champAutre:hover{border:1px solid #FFF;}
			form .question{font-family:Arial;color:#FFF;font-size:15px;margin-bottom:8px;text-align:justify;border:1px solid #AAA;background:#191919;width:770px;padding:5px;}
			form .question:hover{border:1px solid #FFF;}
			form .reponse{font-family:Arial;color:#FFF;font-size:13px;margin-bottom:15px;text-align:justify;border:1px solid #AAA;background:#191919;width:770px;height:100px;padding:5px;}
			form .reponse:hover{border:1px solid #FFF;}


		</style>
	</head>
   
	<body>
		<div id="haut" style="position:absolute;"></div>
		<div id="titre"><a href="engine.php"><img src="im/faq.png" /></a></div>
		<div id="page">
		
			<?php
			$db = mysql_connect('localhost', 'CENSURE', 'CENSURE') or die("Erreur de connexion au serveur des messages.");
			mysql_select_db('CENSURE',$db) or die("Erreur de connexion a la base de donnees des messages.");
			$sql2 = 'SELECT DISTINCT categorie FROM FAQ_tbl ORDER BY categorie' ;
			$req2 = mysql_query($sql2);
			$res2 = mysql_num_rows($req2);
			
			for($j=0;$j<$res2;$j++)
				{
				print('<h1 class="categorie">'.mysql_result($req2,$j,categorie).'</h1>');
				$sql = 'SELECT * FROM FAQ_tbl WHERE categorie = "'.mysql_result($req2,$j,categorie).'" ORDER BY id' ;
				$req = mysql_query($sql);
				$res = mysql_num_rows($req);
				
				for($i=0;$i<$res;$i++)
					{
					if($_GET['edit'] == mysql_result($req,$i,id))
						{	
						print('<form id="a'.mysql_result($req,$i,id).'" action="engine=FAQ.php?edit2='.mysql_result($req,$i,id).'" method="post">
							<input onmouseover="focus();" name="question2" type="text" class="question" value="'.mysql_result($req,$i,question).'" /><br />
							<textarea onmouseover="focus();" name="reponse2" class="reponse">'.str_replace('<br />','',mysql_result($req,$i,reponse)).'</textarea><br />
							<input type="submit" name="submit" value="valider" />
						</form>');
						}
					else
						{
						print('<h2 id="a'.mysql_result($req,$i,id).'" class="question">'.($i+1).'. '.mysql_result($req,$i,question).'</h2>'.(($_SESSION['statut']!="Administrateur")?'':'<a href="engine=FAQ.php?del='.mysql_result($req,$i,id).'#a'.mysql_result($req,$i,id).'" class="del"></a><a href="engine=FAQ.php?edit='.mysql_result($req,$i,id).'#a'.mysql_result($req,$i,id).'" class="edit"></a>').'<a href="#haut" class="up"></a>
						<div class="reponse">
							'.str_replace('</p><br />','</p>',str_replace('&lt;/p&gt;','</p>',str_replace('&lt;p&gt;','<p>',mysql_result($req,$i,reponse)))).'
						</div>');
						}
					}
				}
			
			if($_SESSION['statut']=="Administrateur")
				{
				print('<div id="nouveau">
					<a href="#" onclick="$(\'#nouveau form\').toggle();$(\'#nouveau a\').toggle();">Nouvelle question</a>
					<form action="engine=FAQ.php" method="post" style="display:none;">
						Categorie :<br />');
						$sql = 'SELECT DISTINCT categorie FROM FAQ_tbl ORDER BY categorie' ;
						$req = mysql_query($sql);
						$res = mysql_num_rows($req);
						for($i=0;$i<$res;$i++) print('&nbsp;&nbsp;<input type="radio" name="categorie" value="'.mysql_result($req,$i,categorie).'" /> '.mysql_result($req,$i,categorie).'<br />');
						print('&nbsp;&nbsp;<input type="radio" select="none" onfocus="$(\'#champAutre\').show();" name="categorie" value="autre" /> Autre <input style="display:none;" id="champAutre" onmouseover="focus();" name="categorieAutre" type="text" /><br /><br />');
						print('<input onmouseover="focus();" name="question" type="text" class="question" /><br />
						<textarea onmouseover="focus();" name="reponse" class="reponse"></textarea><br />
						<input type="submit" name="submit" value="valider" />
					</form>
				</div>');
				}
			else print('<div id="nouveau">Votre réponse ne se trouve pas parmi cette FAQ ?<br />Alors <a href="engine=contact.php">posez-là aux administrateurs.</a></div>');
				
			mysql_close($db);
			?>
			
		</div>
		
	</body>

</html>
