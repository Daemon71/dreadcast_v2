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
			Mentor
		</div>
		<b class="module4ie"><a href="engine=experience.php" class="module4"><img src="im_objets/icon_retour.gif" alt="Retour" />Retour</a></b>
	</p>
	</div>
</div>
<div id="centre">
<p>

<?php
$db = mysql_connect('localhost', 'CENSURE', 'CENSURE') or die("Erreur de connexion au serveur des messages.");
mysql_select_db('CENSURE',$db) or die("Erreur de connexion a la base de donnees des messages.");

if(possede_talent("Mentor",$_SESSION['pseudo']))
	{
	}
else
	{
	print('<meta http-equiv="refresh" content="0 ; url=http://v2.dreadcast.net/ingame/engine.php"> ');
	exit();
	}

$sql = 'SELECT xp FROM talent_mentor_tbl WHERE pseudo= "'.$_SESSION['pseudo'].'"' ;
$req = mysql_query($sql);
$res = mysql_num_rows($req);
if($res>0) $xp = mysql_result($req,0,xp);
else
    {
    $sql = 'INSERT INTO talent_mentor_tbl(id,pseudo,xp) VALUES("","'.$_SESSION['pseudo'].'","5000")' ;
    $req = mysql_query($sql);
    $xp = 5000;
    }

mysql_close($db);

if($_POST['xp']>0)
	{
	if($xp>=$_POST['xp'])
		{
		$db = mysql_connect('localhost', 'CENSURE', 'CENSURE') or die("Erreur de connexion au serveur des messages.");
        mysql_select_db('CENSURE',$db) or die("Erreur de connexion a la base de donnees des messages.");
        $xp = enregistre($_SESSION['pseudo'],'mentor',valeur($_SESSION['pseudo'],'mentor')+$_POST['xp']);
        $sql = 'UPDATE principal_tbl SET total=total+'.$_POST['xp'].' WHERE pseudo= "'.$_POST['cible'].'"' ;
        $req = mysql_query($sql);
        $sql = 'UPDATE talent_mentor_tbl SET xp=xp-'.$_POST['xp'].' WHERE pseudo= "'.$_SESSION['pseudo'].'"' ;
        $req = mysql_query($sql);
        $sql = 'INSERT INTO messages_tbl(id,auteur,cible,message,objet,moment,nouveau,idrep) VALUES("","Dreadcast","'.$_POST['cible'].'","'.$_SESSION['pseudo'].' vous remercie pour vos efforts.<br />Vous gagnez '.$_POST['xp'].' Pts d\'expérience.","Expérience","'.time().'","oui","")' ;
        $req = mysql_query($sql);
        mysql_close($db);
        print('Vous venez de donner <strong>'.$_POST['xp'].'</strong> Pts d\'expérience à <strong>'.$_POST['cible'].'</strong>.<br />Il en a été informé par message privé.');
        }
	else
		{
		print('Vous n\'avez pas assez de points à donner.');
		}
	}
else
	{
	print('Vous vous apprétez à donner de l\'expérience à quelqu\'un.<br /><br /><strong>Il vous reste:</strong> '.$xp.' Pts d\'expérience à donner aujourd\'hui<br /><br />
	<form method="post" action="#">
        A qui désirez-vous donner de l\'expérience : <input name="cible" type="text" /><br />
		Combien de points désirez-vous donner : <select name="xp">');
	for($i=10;$i<=$xp;$i=$i+10) print('<option value="'.$i.'">'.$i.'</option>');
	print('</select> <input type="submit" value="Donner" />
	</form>');
	}
?>

</p>
</div>
<?php if($chat=="oui") 	{ if ((date("H")>=21) || (date("H")<=7)) { include("inc_bas_n_c.php"); } else { include("inc_bas_j_c.php"); } } else {	if ((date("H")>=21) || (date("H")<=7)) { include("inc_bas_n.php"); } else { include("inc_bas_j.php"); }	} ?>
<?php include("inc_bas_de_page.php"); ?>
