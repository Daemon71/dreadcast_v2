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
			Machine &agrave; sous
		</div>
		<?php ($_SESSION['credits']<20)?print('<b class="module4ie"><a href="engine=machine2.php" class="module4"><img src="im_objets/icon_retour.gif" alt="Retour" />Retour</a></b>'):print('');?>
</p>
	</div>
</div>
<div id="centre_imperium">

<p id="location"><span>Super Slot !</span></p>

<p id="textelse">
<?php



if($_SESSION['credits']<20)
	{
	echo '<b>Vous devez posséder au moins 20 crédits pour pouvoir jouer</b>';
	}

else
    {
	$db = mysql_connect('localhost', 'CENSURE', 'CENSURE') or die("Erreur de connexion au serveur des messages.");
	mysql_select_db('CENSURE',$db) or die("Erreur de connexion a la base de donnees des messages.");
	
	//Mettre à jours les crédits du joueurs -20 crédits
	$sql = 'SELECT credits FROM principal_tbl WHERE id= "'.$_SESSION['id'].'"' ;
	$req = mysql_query($sql);
	$_SESSION['credits'] = mysql_result($req,0,credits);	
	$_SESSION['credits'] = $_SESSION['credits'] - 20;
	$sql2 = 'UPDATE principal_tbl SET credits="'.$_SESSION['credits'].'" WHERE id= "'.$_SESSION['id'].'"' ;
	$req2 = mysql_query($sql2);
	
	
	//Mettre à jours l'entré de la table donnée
	$sql3 = 'SELECT valeur FROM donnees_tbl WHERE objet= "entreeSS"';
	$req3 = mysql_query($sql3);
	$entre = mysql_result($req3,0,valeur);
	$entre = $entre + 20;
	$sql4 = 'UPDATE donnees_tbl SET valeur="'.$entre.'" WHERE objet="entreeSS"' ;
	$req4 = mysql_query($sql4);
	
	
	//code Random pour le flash.
	$choix1=rand(0,74);
	$choix2=rand(0,74);
	$choix3=rand(0,74);
	
	if($choix1<=15){$arret1=3;}
	else if(($choix1<=30)&&($choix1>15)){$arret1=4;}
else if(($choix1<=45)&&($choix1>30)){$arret1=5;}
else if(($choix1<=60)&&($choix1>45)){$arret1=2;}
else if(($choix1<=68)&&($choix1>60)){$arret1=1;}
else{$arret1=0;}
	
	if($choix2<=15){$arret2=3;}
	else if(($choix2<=30)&&($choix2>15)){$arret2=4;}
else if(($choix2<=45)&&($choix2>30)){$arret2=5;}
else if(($choix2<=60)&&($choix2>45)){$arret2=2;}
else if(($choix2<=68)&&($choix2>60)){$arret2=1;}
else{$arret2=0;}
	
	if($choix3<=15){$arret3=3;}
	else if(($choix3<=30)&&($choix3>15)){$arret3=4;}
else if(($choix3<=45)&&($choix3>30)){$arret3=5;}
else if(($choix3<=60)&&($choix3>45)){$arret3=2;}
else if(($choix3<=68)&&($choix3>60)){$arret3=1;}
else{$arret3=0;}

if($arret3 == 0){
	if(($arret1==3)||($arret2==3)){$wi=20;}
	else if(($arret1==0)&&($arret2==0)){$wi=1000;}
	}
else if($arret3 == 1){
	if(($arret1==3)||($arret2==3)){$wi=20;}
	else if(($arret1==1)&&($arret2==1)){$wi=500;}
	}
else if($arret3 == 2){
	if(($arret1==3)||($arret2==3)){$wi=20;}
	else if(($arret1==2)&&($arret2==2)){$wi=200;}
	else if((($arret1==2)||($arret1==4)||($arret1==5))&&(($arret2==2)||($arret2==4)||($arret2==5))){$wi=30;}
	}
else if($arret3 == 3){
	$wi=20;
	}
else if($arret3 == 4){
	if(($arret1==3)||($arret2==3)){$wi=20;}
	else if(($arret1==4)&&($arret2==4)){$wi=50;}
	else if((($arret1==2)||($arret1==4)||($arret1==5))&&(($arret2==2)||($arret2==4)||($arret2==5))){$wi=30;}
	}
else{

if(($arret1==3)||($arret2==3)){$wi=20;}
else if(($arret1==5)&&($arret2==5)){$wi=100;}
else if((($arret1==2)||($arret1==4)||($arret1==5))&&(($arret2==2)||($arret2==4)||($arret2==5))){$wi=30;}
}

//Mettre à jours la sortie de la table donnée
$sql3 = 'SELECT valeur FROM donnees_tbl WHERE objet= "sortieSS"';
	$req3 = mysql_query($sql3);
	$sortie = mysql_result($req3,0,valeur);
	$sortie = $sortie + $wi;
	$sql4 = 'UPDATE donnees_tbl SET valeur="'.$sortie.'" WHERE objet= "sortieSS"' ;
	$req4 = mysql_query($sql4);
	
//	$sqls = 'SELECT fois FROM donneesidj_tbl WHERE pseudo= "'.$_SESSION['pseudo'].'" ORDER BY id DESC LIMIT 1';
	//$reqs = mysql_query($sqls);
	//$ress = mysql_num_rows($reqs);
	//if($ress==0)
		//{
		//$fois = 1;
		//}
	//else
		//{
		//$fois = mysql_result($reqs,0,fois)+1;	
		//}

//Mettre à jours donneesidj par joueurs
	//$sql5 = 'INSERT INTO donneesidj_tbl(id,pseudo,mise,gain,fois) VALUES("","'.$_SESSION[pseudo].'","20","'.$wi.'","'.$fois.'")' ;
	//$req5 = mysql_query($sql5);

//Mettre à jours les sous chez le joueur		
	$_SESSION['credits'] = $_SESSION['credits'] + $wi;
	$sql2 = 'UPDATE principal_tbl SET credits="'.$_SESSION['credits'].'" WHERE id= "'.$_SESSION['id'].'"' ;
	$req2 = mysql_query($sql2);


	mysql_close($db);
	
	echo'<div id="Flash">
	<script type="text/javascript" src="swfobject.js"></script>
	<span id="flashcontent">Veuillez patienter...</span>
	<script type="text/javascript">
		var so = new SWFObject("superslot2.swf", "superslot", "400", "250", "8", "#FFFFFF");
		so.addParam("AllowScriptAccess","always");
		so.addVariable("ce", "20");
		so.addVariable("choix1", "'.$choix1.'");
		so.addVariable("choix2", "'.$choix2.'");
		so.addVariable("choix3", "'.$choix3.'");
		so.write("flashcontent");
	</script></div>';
	
	}

?>



</p>

</div>
<?php if($chat=="oui") 	{ if ((date("H")>=21) || (date("H")<=7)) { include("inc_bas_n_c.php"); } else { include("inc_bas_j_c.php"); } } else {	if ((date("H")>=21) || (date("H")<=7)) { include("inc_bas_n.php"); } else { include("inc_bas_j.php"); }	} ?>
<?php include("inc_bas_de_page.php"); ?>
