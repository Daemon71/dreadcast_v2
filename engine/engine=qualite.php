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

$sql = 'SELECT id,rue,num,action,alim,sante,fatigue FROM principal_tbl WHERE pseudo= "'.$_SESSION['pseudo'].'"' ;
$req = mysql_query($sql);
$_SESSION['id'] = mysql_result($req,0,id);
$_SESSION['rue'] = mysql_result($req,0,rue);
$_SESSION['num'] = mysql_result($req,0,num);
$_SESSION['action'] = mysql_result($req,0,action);
$_SESSION['alim'] = mysql_result($req,0,alim);
$_SESSION['sante'] = mysql_result($req,0,sante);
$_SESSION['fatigue'] = mysql_result($req,0,fatigue);
$_SESSION['lieu'] = strtolower($_SESSION['rue']);
$_SESSION['lieu'] = ucwords($_SESSION['lieu']);

mysql_close($db);
?>
<?php if ((date("H")>=21) || (date("H")<=7)) { include("inc_haut_n.php"); } else { include("inc_haut_j.php");} ?>

<div id="haut">
	<?php include("inc_entete.php"); ?>
	<div class="haut_aligngauche">
		<p>
		<div class="titrepage">
			Qualit&eacute; de vie
		</div>
		</p>
	</div>
</div>
<div id="centre">
<p>

La nourriture est importante car c'est une source s&ucirc;re de repos et de guérison des blessures. Vous pouvez changer votre qualit&eacute; de vie quand vous le d&eacute;sirez. 
		<form name="form1" method="post" action="">
			  <p align="center">Combien souhaitez-vous d&eacute;penser par jour pour votre alimentation ?
			 </p>
			  <p align="center"><br>
		        <select name="menu1" onChange="MM_jumpMenu('parent',this,0)">
<? 
if($action=="prison")
	{
	print('<option value="#" selected>Gratuit (Prison)</option>');
	}
elseif($_SESSION['alim']==7)
{
print('<option value="engine=bouffe.php?7" selected>7 Cr&eacute;dits / Jour (+1 Sant&eacute; +1 Repos)</option>');
print('<option value="engine=bouffe.php?14">14 Cr&eacute;dits / Jour (+2 Sant&eacute; +3 Repos)</option>');
print('<option value="engine=bouffe.php?32">32 Cr&eacute;dits / Jour (+3 Sant&eacute; +4 Repos)</option>');
print('<option value="engine=bouffe.php?60">60 Cr&eacute;dits / Jour (+4 Sant&eacute; +6 Repos)</option>');
}
elseif($_SESSION['alim']==14)
{
print('<option value="engine=bouffe.php?7">7 Cr&eacute;dits / Jour (+1 Sant&eacute; +1 Repos)</option>');
print('<option value="engine=bouffe.php?14" selected>14 Cr&eacute;dits / Jour (+2 Sant&eacute; +3 Repos)</option>');
print('<option value="engine=bouffe.php?32">32 Cr&eacute;dits / Jour (+3 Sant&eacute; +4 Repos)</option>');
print('<option value="engine=bouffe.php?60">60 Cr&eacute;dits / Jour (+4 Sant&eacute; +6 Repos)</option>');
}
elseif($_SESSION['alim']==32)
{
print('<option value="engine=bouffe.php?7">7 Cr&eacute;dits / Jour (+1 Sant&eacute; +1 Repos)</option>');
print('<option value="engine=bouffe.php?14">14 Cr&eacute;dits / Jour (+2 Sant&eacute; +3 Repos)</option>');
print('<option value="engine=bouffe.php?32" selected>32 Cr&eacute;dits / Jour (+3 Sant&eacute; +4 Repos)</option>');
print('<option value="engine=bouffe.php?60">60 Cr&eacute;dits / Jour (+4 Sant&eacute; +6 Repos)</option>');
}
elseif($_SESSION['alim']==60)
{
print('<option value="engine=bouffe.php?7">7 Cr&eacute;dits / Jour (+1 Sant&eacute; +1 Repos)</option>');
print('<option value="engine=bouffe.php?14">14 Cr&eacute;dits / Jour (+2 Sant&eacute; +3 Repos)</option>');
print('<option value="engine=bouffe.php?32">32 Cr&eacute;dits / Jour (+3 Sant&eacute; +4 Repos)</option>');
print('<option value="engine=bouffe.php?60" selected>60 Cr&eacute;dits / Jour (+4 Sant&eacute; +6 Repos)</option>');
}
?>
</select>		
		        (D&eacute;bit&eacute;s tous	les midi)</p>
	  </form>

</p>
</div>
<?php if($chat=="oui") 	{ if ((date("H")>=21) || (date("H")<=7)) { include("inc_bas_n_c.php"); } else { include("inc_bas_j_c.php"); } } else {	if ((date("H")>=21) || (date("H")<=7)) { include("inc_bas_n.php"); } else { include("inc_bas_j.php"); }	} ?>
<?php include("inc_bas_de_page.php"); ?>
