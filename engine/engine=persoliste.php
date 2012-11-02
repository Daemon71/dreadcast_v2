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
$idi = $_GET['id'];
$_SESSION['entreprise'] = str_replace("%20"," ",''.$_GET['entreprise'].'');

?>
<?php if ((date("H")>=21) || (date("H")<=7)) { include("inc_haut_n.php"); } else { include("inc_haut_j.php");} ?>

<div id="haut">
	<?php include("inc_entete.php"); ?>
	<div class="haut_aligngauche">
		<p>
		<div class="titrepage">
			G&eacute;rer votre personnel
		</div>
		<b class="module4ie"><a href="engine=persodetail.php?id=<?php print(''.$idi.''); ?>&ent=<?php print(''.$_SESSION['entreprise'].''); ?>" class="module4"><img src="im_objets/icon_retour.gif" alt="Retour" />Retour</a></b>
</p>
	</div>
</div>
<div id="centre">
<p>

<?php 

$db = mysql_connect('localhost', 'CENSURE', 'CENSURE') or die("Erreur de connexion au serveur des messages.");
mysql_select_db('CENSURE',$db) or die("Erreur de connexion a la base de donnees des messages.");

$sql = 'SELECT type,entreprise,points FROM principal_tbl WHERE id= "'.$_SESSION['id'].'"' ;
$req = mysql_query($sql);
$_SESSION['poste'] = mysql_result($req,0,type); 
$_SESSION['entreprise'] = mysql_result($req,0,entreprise); 
$_SESSION['points'] = mysql_result($req,0,points); 

if($_SESSION['points']!=999) { print('<meta http-equiv="refresh" content="0 ; url=http://v2.dreadcast.net/ingame/engine.php"> '); exit(); }

$sql = 'SELECT poste,type,salaire,bdd,nbrepostes,nbreactuel,competence,mincomp,candidature,mintrav,sinon,bonus FROM `e_'.str_replace(" ","_",''.$_SESSION['entreprise'].'').'_tbl` WHERE id= "'.$idi.'"' ;
$req = mysql_query($sql);
$res = mysql_num_rows($req);

for($i=0; $i != $res ; $i++) 
	{
	$postei = mysql_result($req,$i,poste); 
	$typei = mysql_result($req,$i,type); 
	$salairei = mysql_result($req,$i,salaire);
	$bddi = mysql_result($req,$i,bdd); 
	$nbrepostesi = mysql_result($req,$i,nbrepostes); 
	$nbreactueli = mysql_result($req,$i,nbreactuel); 
	$mincompi = mysql_result($req,$i,mincomp); 
	$candidaturei = mysql_result($req,$i,candidature); 
	$mintravi = mysql_result($req,$i,mintrav); 
	$sinoni = mysql_result($req,$i,sinon); 
	$bonusi = mysql_result($req,$i,bonus);  
	print('<em><strong>Occupant(s) du poste '.ucwords($postei).' : </strong></em>');
	$sql1 = 'SELECT id,points FROM principal_tbl WHERE entreprise="'.$_SESSION['entreprise'].'" AND type="'.$postei.'"' ;
	$req1 = mysql_query($sql1);
	$res1 = mysql_num_rows($req1);
	if($res1==0)
		{
		print('<i>Aucun</i>');
		}
	print('<div class="barrepostes"><table align="center" cellpadding="1" cellspacing="0" bordercolor="#000000" bgcolor="#FBFBFB" width="455"  border="1"><tr>');
	$n = 0;
	for($t=0; $t != $res1 ; $t++) 
		{
		print('<td>');
		$n = $n + 1;
		$idt = mysql_result($req1,$t,id); 
		$bonust = mysql_result($req1,$t,points); 
		$sql2 = 'SELECT pseudo,action FROM principal_tbl WHERE id="'.$idt.'"' ;
		$req2 = mysql_query($sql2);
		$res2 = mysql_num_rows($req2);
		$pseudot = mysql_result($req2,0,pseudo);
		$actiont = mysql_result($req2,0,action);
		print('<div align="center"><a href="engine=contacter.php?cible='.$pseudot.'"><strong>'.$pseudot.'</strong></a>');
		if($actiont=="travail")
			{
			print('<i> est au travail</i>');
			}
		if($bonust!=999)
			{
			print(' (<a href="engine=licencierconf.php?id1='.$idi.'&id2='.$idt.'">Licencier</a>)');
			}
		if(($bonust!=999) && ($mintravi!=0))
			{
			print('<br>Il a fait <i>'.$bonust.' Heures</i> aujourd\'hui.');
			print('</div>');
			}
		print('<form name="prime'.$idi.''.$idt.'" method="post" action="engine=prime.php?id1='.$idi.'&id2='.$idt.'"><div align="center">Accorder une prime : <input name="prime'.$idi.''.$idt.'" type="text" id="prime'.$idi.''.$idt.'" value="" size="3" maxlength="4"><input type="submit" name="Sub'.$idi.'" value="Ok"></div></form>');
		print('</td>');
		if(($n==2) || ($n==4) || ($n==6) || ($n==8) || ($n==10) || ($n==12) || ($n==14) || ($n==16) || ($n==18))
			{
			print('</tr>');
			}
		}
	if(($n==1) || ($n==3) || ($n==5) || ($n==7) || ($n==9) || ($n==11) || ($n==13) || ($n==15) || ($n==17))
		{
		print('</tr>');	
		}
	print('</table></div>');
	}

mysql_close($db);

?>

</p>
</div>
<?php if($chat=="oui") 	{ if ((date("H")>=21) || (date("H")<=7)) { include("inc_bas_n_c.php"); } else { include("inc_bas_j_c.php"); } } else {	if ((date("H")>=21) || (date("H")<=7)) { include("inc_bas_n.php"); } else { include("inc_bas_j.php"); }	} ?>
<?php include("inc_bas_de_page.php"); ?>
