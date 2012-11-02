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
			Changer de digicode
		</div>
		<b class="module4ie"><a href="engine=logement.php" class="module4"><img src="im_objets/icon_retour.gif" alt="Retour" />Retour</a></b>
</p>
	</div>
</div>
<div id="centre">
<p><br />

<?php  

$nouveau = $_POST['nouveau'];

for($i=0;$i!=strlen($nouveau);$i++)
	{
	$p = $nouveau{$i};
	if (preg_match("#[1-9]#",$p))
		{
		}
	else
		{
		$exit = 1;
		}
	} 

if(($nouveau!="") && ($exit!=1))
	{
	$db = mysql_connect('localhost', 'CENSURE', 'CENSURE') or die("Erreur de connexion au serveur des messages.");
	mysql_select_db('CENSURE',$db) or die("Erreur de connexion a la base de donnees des messages.");
	
	$sql = 'SELECT code FROM lieu_tbl WHERE rue= "'.$_SESSION['ruea'].'" AND num= "'.$_SESSION['numa'].'"' ;
	$req = mysql_query($sql);
	$code = mysql_result($req,0,code);
	
	if(strlen($nouveau)==strlen($code))
		{
		$sql = 'UPDATE lieu_tbl SET code="'.$nouveau.'" WHERE rue= "'.$_SESSION['ruea'].'" AND num= "'.$_SESSION['numa'].'"' ;
		$req = mysql_query($sql);
		print('<i>Votre digicode a correctement &eacute;t&eacute; chang&eacute;.</i>');
		}
	else
		{
		print('Vous ne pouvez pas changer la longueur de votre digicode.');
		}
	mysql_close($db);
	}
elseif($exit==1)
	{
	echo "Vous ne pouvez utiliser que des chiffres de 1 à 9 !";
	}
else
	{
	print('<i>Vous avez entré un digicode vide !</i>');
	}
?> 

</p>
</div>
<?php if($chat=="oui") 	{ if ((date("H")>=21) || (date("H")<=7)) { include("inc_bas_n_c.php"); } else { include("inc_bas_j_c.php"); } } else {	if ((date("H")>=21) || (date("H")<=7)) { include("inc_bas_n.php"); } else { include("inc_bas_j.php"); }	} ?>
<?php include("inc_bas_de_page.php"); ?>
