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
			Acheter une paire de menottes
		</div>
		<b class="module4ie"><a href="engine=services.php" class="module4"><img src="im_objets/icon_retour.gif" alt="Retour" />Retour</a></b>
</p>
	</div>
</div>
<div id="centre">
<p>

<?php
if($_GET['ok']==1)
	{
	$db = mysql_connect('localhost', 'CENSURE', 'CENSURE') or die("Erreur de connexion au serveur des messages.");
	mysql_select_db('CENSURE',$db) or die("Erreur de connexion a la base de donnees des messages.");
	$sql = 'SELECT entreprise FROM principal_tbl WHERE id= "'.$_SESSION['id'].'"';
	$req = mysql_query($sql);
	if(mysql_result($req,0,entreprise)=="Chambre des lois")
		{
		$sql = 'SELECT credits,case1,case2,case3,case4,case5,case6 FROM principal_tbl WHERE id= "'.$_SESSION['id'].'"';
		$req = mysql_query($sql);
		$_SESSION['credits'] = mysql_result($req,0,credits);
		$_SESSION['case1'] = mysql_result($req,0,case1);
		$_SESSION['case2'] = mysql_result($req,0,case2);
		$_SESSION['case3'] = mysql_result($req,0,case3);
		$_SESSION['case4'] = mysql_result($req,0,case4);
		$_SESSION['case5'] = mysql_result($req,0,case5);
		$_SESSION['case6'] = mysql_result($req,0,case6);
		if($_SESSION['credits']>=100)
			{
			$l = 0;
			for($i=1; $i != 7; $i++)
				{
				if(($_SESSION['case'.$i.'']=="Vide") && ($l!=1))
					{
					$_SESSION['credits'] = $_SESSION['credits'] - 100;
					$sql = 'UPDATE principal_tbl SET credits= "'.$_SESSION['credits'].'" , case'.$i.'= "Alliance" WHERE id= "'.$_SESSION['id'].'"';
					$req = mysql_query($sql);
					$l = 1;			
					}
				}
			if($l==0)
				{
				print('<br />Vous n\'avez pas d\'emplacement vide dans votre inventaire.');
				}
			else
				{
				print('<br />Vous venez d\'acheter une Alliance.<br />Elle vient d\'�tre plac�e dans votre inventaire.');
				}	
			}
		else
			{
			print('<br />Vous n\'avez pas assez de Cr�dits dans votre inventaire.');
			}
		}
		
	else
		{
		print('<br />Il faut �tre de la Chambre des Lois pour pouvoir obtenir une Alliance.');
		}
	mysql_close($db);
	}
else
	{
	print('<br /><strong>Cette page est reserv�e aux membres de la Chambre des Lois.</strong><br />
	Cet objet permet de lier deux �mes.<br />
	Une Alliance co�te 100 Cr�dits. Obteniez-les des interress�s.<br /><br />
	<a href="engine=alliance.php?ok=1">Obtenir une Alliance</a>');
	}
?>

</p>
</div>
<?php if($chat=="oui") 	{ if ((date("H")>=21) || (date("H")<=7)) { include("inc_bas_n_c.php"); } else { include("inc_bas_j_c.php"); } } else {	if ((date("H")>=21) || (date("H")<=7)) { include("inc_bas_n.php"); } else { include("inc_bas_j.php"); }	} ?>
<?php include("inc_bas_de_page.php"); ?>
