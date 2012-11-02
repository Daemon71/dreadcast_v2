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
$_SESSION['cible'] = str_replace("%20"," ",''.$_SERVER['QUERY_STRING'].'');

?>
<?php if ((date("H")>=21) || (date("H")<=7)) { include("inc_haut_n.php"); } else { include("inc_haut_j.php");} ?>

<div id="haut">
	<?php include("inc_entete.php"); ?>
	<div class="haut_aligngauche">
		<p>
		<div class="titrepage">
			Gu&eacute;rir
		</div>
		<b class="module4ie"><a href="engine=cible.php?<?php print(''.$_SESSION['cible'].''); ?>" class="module4"><img src="im_objets/icon_retour.gif" alt="Retour" />Retour</a></b>
</p>
	</div>
</div>
<div id="centre">
<p>

<?php	

$db = mysql_connect('localhost', 'CENSURE', 'CENSURE') or die("Erreur de connexion au serveur.");
mysql_select_db('CENSURE',$db) or die("Erreur de connexion a la base de donnees.");

$sql = 'SELECT id,rue,num,credits FROM principal_tbl WHERE pseudo= "'.$_SESSION['pseudo'].'"' ;
$req = mysql_query($sql);
$_SESSION['id'] = mysql_result($req,0,id);
$_SESSION['lieu'] = mysql_result($req,0,rue);
$_SESSION['num'] = mysql_result($req,0,num);
$_SESSION['credits'] = mysql_result($req,0,credits);

$sql = 'SELECT id,rue,num,sante,sante_max,soins,drogue FROM principal_tbl WHERE pseudo= "'.$_SESSION['cible'].'"' ;
$req = mysql_query($sql);
$_SESSION['idc'] = mysql_result($req,0,id);
$lieuc = mysql_result($req,0,rue);
$numc = mysql_result($req,0,num);
$santec = mysql_result($req,0,sante);
$santecmax = mysql_result($req,0,sante_max);
$droguec = mysql_result($req,0,drogue);
$soins = mysql_result($req,0,soins);
$santec2 = $santec;

if(!estVisible($_SESSION['cible'],25))
	{
	print('<meta http-equiv="refresh" content="0 ; url=http://v2.dreadcast.net/ingame/engine=liste.php">');
	exit();
	}

$santec = sante_etat($santec,$santecmax);

//condition DIGICODE
if($_SESSION['cible']!=$_SESSION['pseudo'])
	{
	$sql = 'SELECT code FROM lieu_tbl WHERE rue= "'.$_SESSION['lieu'].'" AND num= "'.$_SESSION['num'].'"' ;
	$req = mysql_query($sql);
	$res = mysql_num_rows($req);
	if($res>0)
		{
		$codounet = mysql_result($req,0,code);
		if($_SESSION['code']!=$codounet)
			{
			mysql_close($db);
			print('<meta http-equiv="refresh" content="0 ; url=http://v2.dreadcast.net/ingame/engine.php"> ');
			exit();
			}
		}
	}

mysql_close($db);

if($soins>=40)
	{
	print('<em>Vous avez cibl&eacute; <strong>'.$_SESSION['cible'].'</strong> pour le soigner mais il à déjà trop de bandages sur lui pour aujourd\'hui.</em>');
	}
else
	{
	if($_SESSION['cible']==$_SESSION['pseudo'])
		{
		print('<em>Vous êtes sur le point de vous soigner...</em>
		<form name="form1" method="post" action="engine=soinsf.php">
		<div align="center">Choisissez combien de points de sant&eacute; vous souhaitez vous ajouter: <select name="soins">');
		for($p=100;$p!=0;$p=$p-10)
			{
			print('<option value="'.$p.'">'.$p.'</option>');
			}
		print('</select>  <input type="submit" value="Valider"></div></form>');
		}
	else
		{
		print('<em>Vous avez cibl&eacute; <strong>'.$_SESSION['cible'].'</strong> pour le gu&eacute;rir...</em>
		<p align="center"><strong>Son état de sant&eacute; actuel :</strong> <em>'.$santec.'</em></p>
		<form name="form1" method="post" action="engine=soinsf.php">
		<div align="center">Choisissez combien de points de sant&eacute; vous souhaitez lui ajouter: <select name="soins">');
		for($p=100;$p!=0;$p=$p-10)
			{
			print('<option value="'.$p.'">'.$p.'</option>');
			}
		print('</select>  <input type="submit" value="Valider"></div></form>');
		}
	}

?>



</p>
</div>
<?php if($chat=="oui") 	{ if ((date("H")>=21) || (date("H")<=7)) { include("inc_bas_n_c.php"); } else { include("inc_bas_j_c.php"); } } else {	if ((date("H")>=21) || (date("H")<=7)) { include("inc_bas_n.php"); } else { include("inc_bas_j.php"); }	} ?>
<?php include("inc_bas_de_page.php"); ?>
