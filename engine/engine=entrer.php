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

$sqlb = 'SELECT * FROM digicodes_tbl WHERE pseudo="'.$_SESSION['pseudo'].'"' ;
$reqb = mysql_query($sqlb);
$resb = mysql_num_rows($reqb);

if($resb>0)
	{
	$moment = mysql_result($reqb,0,moment);
	if(time()-$moment<5)
		{
		$imp = 1;
		}
	else
		{
		$sql = 'UPDATE digicodes_tbl SET moment= "'.time().'" WHERE pseudo="'.$_SESSION['pseudo'].'"' ;
		$req = mysql_query($sql);
		}
	}
else
	{
	$sql = 'INSERT INTO digicodes_tbl(id,pseudo,moment) VALUES("","'.$_SESSION['pseudo'].'","'.time().'")' ;
	$req = mysql_query($sql);
	}	

if($imp!=1)
	{
	
	$_SESSION['code'] = trim($_POST['entrer']);
	$sql = 'SELECT id, camera FROM lieu_tbl WHERE num="'.$_SESSION['num'].'" AND rue="'.$_SESSION['rue'].'"' ;
	$req = mysql_query($sql);
	
	$id_lieu = mysql_result($req,0,id);
	
	if(mysql_result($req,0,camera)=="Oui")
		{
		$sql = 'SELECT discretion FROM principal_tbl WHERE pseudo="'.$_SESSION['pseudo'].'"' ;
		$req = mysql_query($sql);
		$discretion = mysql_result($req,0,discretion);
		$sql = 'SELECT pseudo FROM principal_tbl WHERE numl="'.$_SESSION['num'].'" AND ruel="'.$_SESSION['rue'].'"' ;
		$req = mysql_query($sql);
		$res = mysql_num_rows($req);
		if($res>0)
			{
			$pl = mysql_result($req,0,pseudo);
			if($_SESSION['pseudo']!=$pl)
				{
				if($discretion<rand(0,105))
					{
					$sql = 'INSERT INTO messages_tbl(id,auteur,cible,message,objet,moment) VALUES("","Dreadcast","'.$pl.'","<br />Votre camera de surveillance du '.$_SESSION['num'].' '.$_SESSION['rue'].' vous informe que  quelqu\'un tente de s\'introduire dans votre logement !<br /><br />En regardant la video de plus près, vous le reconnaissez, il s\'agit de '.$_SESSION['pseudo'].' !","Intrusion !","'.time().'")';
					$req = mysql_query($sql);
					}
				else
					{
					$sql = 'INSERT INTO messages_tbl(id,auteur,cible,message,objet,moment) VALUES("","Dreadcast","'.$pl.'","<br />Votre camera de surveillance du '.$_SESSION['num'].' '.$_SESSION['rue'].' vous informe que  quelqu\'un tente de s\'introduire dans votre logement !<br /><br />Vous ne remarquez pas de qui il s\'agit.","Intrusion !","'.time().'")';
					$req = mysql_query($sql);
					}
				}
			}
		}
	if($_SERVER['QUERY_STRING']!="")
		{
		$_SESSION['code'] = 0;
		}
	}

mysql_close($db);

?>
<?php if ((date("H")>=21) || (date("H")<=7)) { include("inc_haut_n.php"); } else { include("inc_haut_j.php");} ?>

<div id="haut">
	<?php include("inc_entete.php"); ?>
	<div class="haut_aligngauche">
		<p>
		<div class="titrepage">
			Entrer ...
		</div>
		<b class="module4ie"><a href="engine.php" class="module4"><img src="im_objets/icon_retour.gif" alt="Retour" />Retour</a></b>
</p>
	</div>
</div>
<div id="centre">
<p>

<?php

$db = mysql_connect('localhost', 'CENSURE', 'CENSURE') or die("Erreur de connexion au serveur des messages.");
mysql_select_db('CENSURE',$db) or die("Erreur de connexion a la base de donnees des messages.");

if (event(4) && estCasse($id_lieu, 2)) {
	$noWay = true;
} else {
	$noWay = false;
	echo '<img src="im_objets/loader.gif" alt="..." />';
}
	
if($imp==1)
	{
	print('<br /><br />Le digicode ne répond pas.<br />Attendez un peu avant de recommencer.');
	}
elseif($noWay) {
	echo '<div style="margin-top:50px;color:#c81111;text-align:center;">ERREUR CRITIQUE<br /><br />Le digicode est brouillé par une sorte de virus et ne fonctionne plus !</div>';
	if (estDroide())
		echo '<div style="margin-top:50px;text-align:center;">Cependant, vous êtes dans la possibilité de le réparer.<br /><a href="engine=reparer3.php?'.$id_lieu.'">Réparer le digicode</a></div>';
	$_SESSION['code'] = 0;
}
else
	{
	print('<meta http-equiv="refresh" content="0 ; url=http://v2.dreadcast.net/ingame/engine.php">');
	}
?>


</p>
</div>
<?php if($chat=="oui") 	{ if ((date("H")>=21) || (date("H")<=7)) { include("inc_bas_n_c.php"); } else { include("inc_bas_j_c.php"); } } else {	if ((date("H")>=21) || (date("H")<=7)) { include("inc_bas_n.php"); } else { include("inc_bas_j.php"); }	} ?>
<?php include("inc_bas_de_page.php"); ?>
