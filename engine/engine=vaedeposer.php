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

$sql = 'SELECT nom,type,ouvert FROM entreprises_tbl WHERE rue= "'.$_SESSION['lieu'].'" AND num= "'.$_SESSION['num'].'"' ;
$req = mysql_query($sql);
$type = mysql_result($req,0,type);
$noment = mysql_result($req,0,nom);
$ouvert = mysql_result($req,0,ouvert);

if(($type!="ventes aux encheres") || ($ouvert=="non"))
	{
	print('<meta http-equiv="refresh" content="0 ; url=http://v2.dreadcast.net/ingame/engine.php"> ');
	exit();
	}

$sql = 'SELECT nombre FROM stocks_tbl WHERE entreprise= "'.$noment.'"' ;
$req = mysql_query($sql);
$pourc = mysql_result($req,0,nombre);

$sql = 'SELECT case1,case2,case3,case4,case5,case6 FROM principal_tbl WHERE id= "'.$_SESSION['id'].'"' ;
$req = mysql_query($sql);
$_SESSION['case1'] = mysql_result($req,0,case1);
$_SESSION['case2'] = mysql_result($req,0,case2);
$_SESSION['case3'] = mysql_result($req,0,case3);
$_SESSION['case4'] = mysql_result($req,0,case4);
$_SESSION['case5'] = mysql_result($req,0,case5);
$_SESSION['case6'] = mysql_result($req,0,case6);

$sqlv = 'SELECT id FROM vente_tbl WHERE vendeur= "'.$_SESSION['pseudo'].'"' ;
$reqv = mysql_query($sqlv);
$resv = mysql_num_rows($reqv);

mysql_close($db);
?>
<?php if ((date("H")>=21) || (date("H")<=7)) { include("inc_haut_n.php"); } else { include("inc_haut_j.php");} ?>

<div id="haut">
	<?php include("inc_entete.php"); ?>
	<div class="haut_aligngauche">
		<p>
		<div class="titrepage">
			Hall des ench&egrave;res 
		</div>
		
		<b class="module4ie"><a href="engine.php" class="module4"><img src="im_objets/icon_retour.gif" alt="Retour" />Retour</a></b>
		</p>
	</div>
</div>
<div id="centre_encheres">

<p id="location2"><a href="engine=vae.php">Consulter les ventes</a><?php if($resv>0) print(' | <a href="engine=vaeges.php">G&eacute;rer vos ventes</a>'); ?></p>

<p id="textelse">	
	<em>Vous &ecirc;tes sur le point de mettre un objet en vente.<br />
	Votre annonce de vente sera mise en place d&egrave;s que vous aurez valid&eacute; ce formulaire.<br />
	Si votre objet n'est pas vendu, il restera dans le hall des ench&egrave;res en attendant que vous veniez le chercher.</em><br /><br />
	<form id="maforme" name="form1" method="post" action="engine=vaedeposerfi.php">
		<strong>Objet &agrave; d&eacute;poser</strong>		        
		        <select name="item" id="item">
				<?php
				for($i=1;$i!=7;$i++)
					{
					if($_SESSION['case'.$i.'']!="Vide")
						{
						print('<option value="'.$_SESSION['case'.$i.''].'">'.$_SESSION['case'.$i.''].'</option>');
						}
					}
				?>
                </select><br />
                <strong>Dur&eacute;e de l'ench&egrave;re</strong>		      
				<select name="temps" id="temps">
					<option value="10" selected>24 Heures (10 Cr&eacute;dits)</option>
					<option value="40">120 heures (40 Cr&eacute;dits)</option>
					<option value="70">240 heures (70 Cr&eacute;dits)</option>
		        </select><br />
				<strong>Premi&egrave;re ench&egrave;re</strong> <input name="enchere" type="text" id="enchere" size="5" maxlength="5" class="champ"><span style="position:absolute;left:255px;">Cr&eacute;dits</span><br />
				<strong>Prix d'achat imm&eacute;diat</strong> <input name="achat" type="text" id="achat" size="5" maxlength="5" class="champ"><span style="position:absolute;left:255px;">Cr&eacute;dits*</span> <br /><em>* (0=impossible d'acheter l'objet)</em><br />
				<input type="submit" name="Submit" value="D&eacute;poser" id="valid">
	</form>
</p>

</div>
<?php if($chat=="oui") 	{ if ((date("H")>=21) || (date("H")<=7)) { include("inc_bas_n_c.php"); } else { include("inc_bas_j_c.php"); } } else {	if ((date("H")>=21) || (date("H")<=7)) { include("inc_bas_n.php"); } else { include("inc_bas_j.php"); }	} ?>
<?php include("inc_bas_de_page.php"); ?>
