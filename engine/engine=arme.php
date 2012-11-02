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
?>
<?php if ((date("H")>=21) || (date("H")<=7)) { include("inc_haut_n.php"); } else { include("inc_haut_j.php");} ?>

<div id="haut">
	<?php include("inc_entete.php"); ?>
	<div class="haut_aligngauche">
		<p>
		<div class="titrepage">
			Identité d'une arme
		</div>
		<b class="module4ie"><a href="engine=inventaire.php" class="module4"><img src="im_objets/icon_retour.gif" alt="Retour" />Retour</a></b>
</p>
	</div>
</div>
<div id="centre">
<p>

<?php

$db = mysql_connect('localhost', 'CENSURE', 'CENSURE') or die("Erreur de connexion au serveur des messages.");
mysql_select_db('CENSURE',$db) or die("Erreur de connexion a la base de donnees des messages.");

$sql = 'SELECT case1,case2,case3,case4,case5,case6,arme FROM principal_tbl WHERE id= "'.$_SESSION['id'].'"' ;
$req = mysql_query($sql);
$_SESSION['arme'] = mysql_result($req,0,arme);
$_SESSION['case1'] = mysql_result($req,0,case1);
$_SESSION['case2'] = mysql_result($req,0,case2);
$_SESSION['case3'] = mysql_result($req,0,case3);
$_SESSION['case4'] = mysql_result($req,0,case4);
$_SESSION['case5'] = mysql_result($req,0,case5);
$_SESSION['case6'] = mysql_result($req,0,case6);

$deja = 0;
for($i=1; $i != 7; $i++)
	{
	if(($_SESSION['case'.$i.'']==$_GET['arme'].$_GET['id']) && ($deja!=1))
		{
		$deja = 1;
		}
	}
if($_SESSION['arme']==$_GET['arme'].$_GET['id'])
	{
	$deja = 2;
	}

if($deja!=0)
	{
	$sql = 'SELECT image,type,modes FROM objets_tbl WHERE nom= "'.$_GET['arme'].'"' ;
	$req = mysql_query($sql);
	$image = mysql_result($req,0,image);
	$type = mysql_result($req,0,type);
	$modes = mysql_result($req,0,modes);
	$sql = 'SELECT * FROM armes_tbl WHERE idarme= "'.$_GET['id'].'"' ;
	$req = mysql_query($sql);
	$chargeur = mysql_result($req,0,chargeur);
	$durabilite = mysql_result($req,0,usure);
	$modif1 = mysql_result($req,0,modif1);
	$modif2 = mysql_result($req,0,modif2);
	$modif3 = mysql_result($req,0,modif3);
	$mode = mysql_result($req,0,mode);
	if($modif1==0) { $tmodif1 = "Aucune"; }
	if($modif2==0) { $tmodif2 = "Aucune"; }
	if($modif3==0) { $tmodif3 = "Aucune"; }
	if($modif1==1) { $tmodif1 = "Viseur laser (+15 tir)"; }
	if($modif2==1) { $tmodif2 = "Viseur laser (+15 tir)"; }
	if($modif3==1) { $tmodif3 = "Viseur laser (+15 tir)"; }
	if($modif1==2) { $tmodif1 = "Crosse (+5 tir)"; }
	if($modif2==2) { $tmodif2 = "Crosse (+5 tir)"; }
	if($modif3==2) { $tmodif3 = "Crosse (+5 tir)"; }
	if($modif1==3) { $tmodif1 = "Lunette (portée +10m)"; }
	if($modif2==3) { $tmodif2 = "Lunette (portée +10m)"; }
	if($modif3==3) { $tmodif3 = "Lunette (portée +10m)"; }
	if($modif1==4) { $tmodif1 = "Chargeur HC (chargeur x2)"; }
	if($modif2==4) { $tmodif2 = "Chargeur HC (chargeur x2)"; }
	if($modif3==4) { $tmodif3 = "Chargeur HC (chargeur x2)"; }
	if($modif1==5) { $tmodif1 = "Silencieux (+10 discrétion)"; }
	if($modif2==5) { $tmodif2 = "Silencieux (+10 discrétion)"; }
	if($modif3==5) { $tmodif3 = "Silencieux (+10 discrétion)"; }
	if($modif1==6) { $tmodif1 = "Alliage titane (durabilité x2)"; }
	if($modif2==6) { $tmodif2 = "Alliage titane (durabilité x2)"; }
	if($modif3==6) { $tmodif3 = "Alliage titane (durabilité x2)"; }
	if($modif1==7) { $tmodif1 = "Vision thermique (+5 observation)"; }
	if($modif2==7) { $tmodif2 = "Vision thermique (+5 observation)"; }
	if($modif3==7) { $tmodif3 = "Vision thermique (+5 observation)"; }
	print('
		<div id="fondarme">
			<a href="../info=objet.php?'.$_GET['arme'].$_GET['id'].'" target="_blank" style="display:block;position:absolute;top:65px;left:22px;width:160px;height:160px;background:url(im_objets/arme_'.str_replace(" ","_",strtolower($_GET['arme'])).'.gif) 0 0 no-repeat;"></a>
			
			<table id="carac_serie">
				<tr>
					<td class="left"><strong>Origine<br />N° de s&eacute;rie<br />Cat&eacute;gorie</strong></td>
					<td class="right">'.$_GET['arme'].'<br />'.str_replace("-","",$_GET['id']).'<br />');if($type="armestir")print('Arme de tir');elseif($type="acac")print('Arme de corps &agrave; corps');elseif($type="armesav" || $type="armesprim")print('Arme avanc&eacute;e');print('</td>
				</tr>
			</table>');
			if($deja==1) { print('<p id="lienarme"><a href="engine=equip.php?'.$_GET['arme'].$_GET['id'].'">Equiper</a></p>'); } else { print('<p id="lienarme"><a href="engine=desequip.php?'.$_GET['arme'].$_GET['id'].'">Retirer</a></p>'); }
		print('</div>
			<table id="carac_arme">
				<tr>
					<td class="left"><strong>Durabilité</strong></td>
					<td class="right"><p style="margin:0;text-align:center;margin:0;position:relative;height:11px;width:100px;border:1px solid #676767;overflow:hidden;background:#606060 url(im_objets/barredure.gif) '.$durabilite.'px ');($durabilite%2==0)?print('-11px'):print('0');print(' no-repeat;"></p></td>
				</tr>
				<tr>
					<td class="left"><strong>Balles</strong></td>
					<td class="right"><img width="20px" height="20px" src="im_objets/');
					if($_GET['arme']=="Lance roquette") $chargeur = 1;
					if($_GET['arme']=="Lance flammes") $chargeur = 999;
					if($chargeur<10) { print('balles_'.$chargeur.'.jpg'); } else { print('balles_i.jpg'); }
					print('" border="0" title="'.$chargeur.' balles" /></td>
				</tr>
				<tr>
					<td class="left"><strong>Mode</strong></td>
					<td class="right">');
						if(ereg("s",$modes)) { if($mode=="s") { print(' <img src="im_objets/ss.jpg" border="0" title="Semi-automatique" />'); } else { print(' <a href="engine=selectmode.php?id='.$_GET['id'].'&mode=s&arme='.$_GET['arme'].'"><img src="im_objets/s.jpg" border="0" title="Semi-automatique" /></a>'); } }
						if(ereg("b",$modes)) { if($mode=="b") { print(' <img src="im_objets/bs.jpg" border="0" title="Rafales" />'); } else { print(' <a href="engine=selectmode.php?id='.$_GET['id'].'&mode=b&arme='.$_GET['arme'].'"><img src="im_objets/b.jpg" border="0" title="Rafales" /></a>'); } }
						if(ereg("a",$modes)) { if($mode=="a") { print(' <img src="im_objets/as.jpg" border="0" title="Automatique" />'); } else { print(' <a href="engine=selectmode.php?id='.$_GET['id'].'&mode=a&arme='.$_GET['arme'].'"><img src="im_objets/a.jpg" border="0" title="Automatique" /></a>'); } }
					print('</td>
				</tr>
				<tr>
					<td class="left"><strong>Modifications</strong></td>
					<td class="right"><img src="im_objets/m_'.$modif1.'.jpg" title="'.$tmodif1.'" border="0" /> <img src="im_objets/m_'.$modif2.'.jpg" border="0" title="'.$tmodif2.'" /> <img src="im_objets/m_'.$modif3.'.jpg" border="0" title="'.$tmodif3.'" /></td>
				</tr>
			</table>');
	}

mysql_close($db);

?>

</p>
</div>
<?php if($chat=="oui") 	{ if ((date("H")>=21) || (date("H")<=7)) { include("inc_bas_n_c.php"); } else { include("inc_bas_j_c.php"); } } else {	if ((date("H")>=21) || (date("H")<=7)) { include("inc_bas_n.php"); } else { include("inc_bas_j.php"); }	} ?>
<?php include("inc_bas_de_page.php"); ?>
