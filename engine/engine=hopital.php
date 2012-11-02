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

if(($type!="hopital") || ($ouvert=="non"))
	{
	print('<meta http-equiv="refresh" content="0 ; url=http://v2.dreadcast.net/ingame/engine.php"> ');
	exit();
	}

$sql = 'SELECT pvente FROM stocks_tbl WHERE entreprise= "'.$noment.'" AND objet= "Soins"' ;
$req = mysql_query($sql);
$pvente = mysql_result($req,0,pvente);

mysql_close($db);
?>
<?php if ((date("H")>=21) || (date("H")<=7)) { include("inc_haut_n.php"); } else { include("inc_haut_j.php");} ?>

<div id="haut">
	<?php include("inc_entete.php"); ?>
	<div class="haut_aligngauche">
		<p>
		<div class="titrepage">
			Hopital
		</div>
		<b class="module4ie"><a href="engine.php" class="module4"><img src="im_objets/icon_retour.gif" alt="Retour" />Retour</a></b>
		</p>
	</div>
</div>
<div id="centre_hopital">
	
<?php 		

$db = mysql_connect('localhost', 'CENSURE', 'CENSURE') or die("Erreur de connexion au serveur des messages.");
mysql_select_db('CENSURE',$db) or die("Erreur de connexion a la base de donnees des messages.");

if($_SESSION['drogue']>0)
	{
	if($_SESSION['sante']<drogue($_SESSION['pseudo'],$_SESSION['santemax']))
		{
		print('<form id="location" name="achat" method="post" action="engine=hopitalf.php">
			<p id="champ">Remonter <select id="leselect" name="npts">');
		$p = drogue($_SESSION['pseudo'],$_SESSION['santemax']) - $_SESSION['sante'];
		for($i=10;$i<$p;$i+=10)
			{
			print('<option value="'.$i.'" selected>'.$i.'</option>');
			}
		print('
		</select> point(s) de sant&eacute;. (<span style="font-size:12px;">'.$pvente.'</span>Cr/10pts)</p> <p id="valid"><input type="submit" name="Submit" value="Soin"></p>
		</form>');
		}
	else
		{
		print('<p id="location">Vous &ecirc;tes <span>en pleine forme</span>.</p>');
		}
	}
if($_SESSION['drogue']==0)
	{
	if($_SESSION['sante']<$_SESSION['santemax'])
		{
		print('<form id="location" name="achat" method="post" action="engine=hopitalf.php">
			<p id="champ">Remonter <select id="leselect" name="npts">');
		$p = $_SESSION['santemax'] - $_SESSION['sante'];
		for($i=10;$i<$p;$i+=10)
			{
			print('<option value="'.$i.'" selected>'.$i.'</option>');
			}
		print('
		</select> point(s) de sant&eacute;. (<span style="font-size:12px;">'.$pvente.'</span>Cr/10pts)</p> <p id="valid"><input type="submit" name="Submit" value="Soin"></p>
		</form>');
		}
	else
		{
		print('<p id="location">Vous &ecirc;tes <span>en pleine forme</span>.</p>');
		}
	}

$_SESSION['objet'] = "Medpack";

$sql = 'SELECT nombre,pvente FROM stocks_tbl WHERE objet= "Medpack" AND entreprise= "'.$noment.'"' ;
$req = mysql_query($sql);
$res = mysql_num_rows($req);
if($res!=0)
	{
	$nombre = mysql_result($req,0,nombre);
	$pvente = mysql_result($req,0,pvente);	
	if($nombre==0)
		{
		print('<br /><br /><br /><br /><br /><br /><br /><p id="textelse">Il n\'y a plus de Medpack en boutique !</p>');
		$l = 1;
		}
	else
		{
		$sql = 'SELECT image,infos,type FROM objets_tbl WHERE nom= "'.$_SESSION['objet'].'"' ;
		$req = mysql_query($sql);
		$image = mysql_result($req,0,image);
		$infos = mysql_result($req,0,infos);
		$typeo = mysql_result($req,0,type);
		
		print('<br /><br /><p id="textelse2"><em>L\'achat de cet objet n&eacute;cessite un emplacement vide dans l\'inventaire personnel.</em><br /><br />
		<table width="97%" cellpadding="0" cellspacing="0">
			<tr>
				<td><img src="im_objets/'.$image.'" border="0"><br /><br />
					<strong>Nom du produit :</strong> <em>Medpack</em></td>
				<td><strong>Type de produit :</strong> <i>Pharmacie</i><br />
					<strong>Nombre en stock :</strong> <em>'.$nombre.'</em><br />
					<strong>Prix de vente :</strong> <em>'.$pvente.' Crédits</em><br />
					<form name="hop" method="post" action="engine=achat.php?Medpack">
						<br /><input type="submit" name="Submit3" id="valid2" value="Acheter">
					</form>
				</td>
			</tr>
		</table>
		</p>');
		}
	}
else
	{
	print('<br /><br /><br /><br /><br /><br /><p id="textelse">Il n\'y a pas de Medpack en boutique !</p>');
	}

mysql_close($db);
?>

</p>
</div>
<?php if($chat=="oui") 	{ if ((date("H")>=21) || (date("H")<=7)) { include("inc_bas_n_c.php"); } else { include("inc_bas_j_c.php"); } } else {	if ((date("H")>=21) || (date("H")<=7)) { include("inc_bas_n.php"); } else { include("inc_bas_j.php"); }	} ?>
<?php include("inc_bas_de_page.php"); ?>
