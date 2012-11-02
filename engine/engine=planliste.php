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

$sql = 'SELECT type,entreprise,points FROM principal_tbl WHERE id= "'.$_SESSION['id'].'"' ;
$req = mysql_query($sql);
$_SESSION['poste'] = mysql_result($req,0,type); 
$_SESSION['entreprise'] = mysql_result($req,0,entreprise); 
$_SESSION['points'] = mysql_result($req,0,points); 

$sql = 'SELECT bdd FROM `e_'.str_replace(" ","_",''.$_SESSION['entreprise'].'').'_tbl` WHERE poste= "'.$_SESSION['poste'].'"' ;
$req = mysql_query($sql);
$_SESSION['bdd'] = mysql_result($req,0,bdd); 

$sql = 'SELECT id,type,budget FROM entreprises_tbl WHERE nom= "'.$_SESSION['entreprise'].'"' ;
$req = mysql_query($sql);
$ide = mysql_result($req,0,id); 
$type = mysql_result($req,0,type); 
$budget = mysql_result($req,0,budget); 

if($_SESSION['bdd']=="") { print('<meta http-equiv="refresh" content="0 ; url=http://v2.dreadcast.net/ingame/engine.php"> '); exit(); }
if($type!="centre de recherche") { print('<meta http-equiv="refresh" content="0 ; url=http://v2.dreadcast.net/ingame/engine.php"> '); exit(); }

mysql_close($db);
?>
<?php if ((date("H")>=21) || (date("H")<=7)) { include("inc_haut_n.php"); } else { include("inc_haut_j.php");} ?>

<div id="haut">
	<?php include("inc_entete.php"); ?>
	<div class="haut_aligngauche_haut">
		<p>
		<div class="titrepage">
			Liste des plans<br />
			<strong>Capital :</strong><em> <?php print(''.$budget.''); ?> Crédits</em>
		</div>
		<b class="module4ie"><a href="engine=services.php" class="module4"><img src="im_objets/icon_retour.gif" alt="Retour" />Retour</a></b>
		</p>
	</div>
</div>
<div id="centre">
<p>

<?php 

$db = mysql_connect('localhost', 'CENSURE', 'CENSURE') or die("Erreur de connexion au serveur des messages.");
mysql_select_db('CENSURE',$db) or die("Erreur de connexion a la base de donnees des messages.");

if($_GET['id']!="")
	{
	$sql = 'SELECT * FROM recherche_plans_tbl WHERE ide="'.$ide.'" AND ido="'.$_GET['id'].'"';
	$req = mysql_query($sql);
	$res = mysql_num_rows($req);
	if($res>0)
		{
		$sql = 'UPDATE objets_tbl SET prod= "-1" WHERE id="'.$_GET['id'].'"';
		$req = mysql_query($sql);
		}
	}

$sql = 'SELECT * FROM recherche_plans_tbl WHERE ide="'.$ide.'"';
$req = mysql_query($sql);
$res = mysql_num_rows($req);

print('<div class="messagesvip"><table width="90%" border="1px">');

print('<tr bgcolor="#B6B6B6">
		<td><div align="center">Image</div></td>
		<td><div align="center">Objet</div></td>
		<td><div align="center">Etat</div></td>
		<td><div align="center">Action</div></td>
	   </tr>');

for($i=0; $i != $res ; $i++) 
	{
	$ido = mysql_result($req,$i,ido);
	$sql1 = 'SELECT * FROM objets_tbl WHERE id="'.$ido.'"';
	$req1 = mysql_query($sql1);
	$res1 = mysql_num_rows($req1);
	if ($res1) {
    	$nom = mysql_result($req1,0,nom);
    	$image = mysql_result($req1,0,image);
    	$prod = mysql_result($req1,0,prod);
    	$type = mysql_result($req1,0,type);
	}
	if($type=="armtu") { $type = "Arme de tir"; }
	elseif($type=="armcu") { $type = "Arme de combat"; }
	elseif($type=="obju") { $type = "Objet"; }
	elseif($type=="vetu") { $type = "Vetements"; }
	elseif($type=="ouu") { $type = "Objet à usage unique"; }
	if($prod==-1) { $prod = '<font color="blue">En validation</font>'; $action = '-'; }
	elseif($prod==0) { $prod = '<font color="red">Non validé</font>'; $action = '<a href="engine=planliste.php?id='.$ido.'">Valider</a>'; }
	elseif($prod==1) { $prod = '<font color="green">Accepté</font>'; $action = '-'; }
	print('<tr>
			<td><div align="center"><img src="im_objets/'.$image.'" border="0" /></div></td>
			<td><div align="center"><a href="../info=objet.php?'.$nom.'" target="_blank">'.$nom.'</a><br />'.$type.'</div></td>
			<td><div align="center">'.$prod.'</div></td>
			<td><div align="center">'.$action.'</div></td>
		   </tr>');
	}

print('</table></div>');

mysql_close($db);

?></p>

</p>
</div>
<?php if($chat=="oui") 	{ if ((date("H")>=21) || (date("H")<=7)) { include("inc_bas_n_c.php"); } else { include("inc_bas_j_c.php"); } } else {	if ((date("H")>=21) || (date("H")<=7)) { include("inc_bas_n.php"); } else { include("inc_bas_j.php"); }	} ?>
<?php include("inc_bas_de_page.php"); ?>
