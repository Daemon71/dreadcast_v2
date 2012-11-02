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
			Courbe
		</div>
		<b class="module4ie"><a href="engine.php" class="module4"><img src="im_objets/icon_retour.gif" alt="Retour" />Retour</a></b>
</p>
	</div>
</div>
<div id="centre">
<p>

<?php $titre = str_replace("%20"," ",''.$_GET['titre'].''); print('<strong>'.$titre.'</strong>'); ?></p><p align="center"><img src="imagephp/courbe.php<?php print('?y1='.$_GET['y1'].'&y2='.$_GET['y2'].'&y3='.$_GET['y3'].'&y4='.$_GET['y4'].'&y5='.$_GET['y5'].'&y6='.$_GET['y6'].'&y7='.$_GET['y7'].'&y8='.$_GET['y8'].'&y9='.$_GET['y9'].'&y10='.$_GET['y10'].'&y11='.$_GET['y11'].'&y12='.$_GET['y12'].'&y13='.$_GET['y13'].'&y14='.$_GET['y14'].'&y15='.$_GET['y15'].'&y16='.$_GET['y16'].'&date1='.$_GET['date1'].'&date2='.$_GET['date2'].'&date3='.$_GET['date3'].'&date4='.$_GET['date4'].'&date5='.$_GET['date5'].'&date6='.$_GET['date6'].'&date7='.$_GET['date7'].'&date8='.$_GET['date8'].'&date9='.$_GET['date9'].'&date10='.$_GET['date10'].'&date11='.$_GET['date11'].'&date12='.$_GET['date12'].'&date13='.$_GET['date13'].'&date14='.$_GET['date14'].'&date15='.$_GET['date15'].'&date16='.$_GET['date16'].''); ?>" />&nbsp;

</p>
</div>
<?php if($chat=="oui") 	{ if ((date("H")>=21) || (date("H")<=7)) { include("inc_bas_n_c.php"); } else { include("inc_bas_j_c.php"); } } else {	if ((date("H")>=21) || (date("H")<=7)) { include("inc_bas_n.php"); } else { include("inc_bas_j.php"); }	} ?>
<?php include("inc_bas_de_page.php"); ?>
