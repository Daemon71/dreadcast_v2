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
if($_SESSION['action']!="prison")
	{
	print('<meta http-equiv="refresh" content="0 ; url=http://v2.dreadcast.net/ingame/engine.php"> ');
	exit();
	}
$_SESSION['distance'] = "";

$db = mysql_connect('localhost', 'CENSURE', 'CENSURE') or die("Erreur de connexion au serveur des messages.");
mysql_select_db('CENSURE',$db) or die("Erreur de connexion a la base de donnees des messages.");

$sql = 'SELECT objet FROM principal_tbl WHERE id= "'.$_SESSION['id'].'"' ;
$req = mysql_query($sql);
$_SESSION['objet'] = mysql_result($req,0,objet);

if($_POST['message']!="")
	{
	$message = htmlentities(''.$_POST['message'].'');
	$sql = 'SELECT * FROM chat WHERE rue= "prison" AND num="0" ORDER BY ids';
	$req = mysql_query($sql);
	for($i=1 ; $i!=15 ; $i++)
		{
		$sql = 'UPDATE chat SET moment= "'.mysql_result($req,$i,moment).'" , posteur= "'.mysql_result($req,$i,posteur).'" , message= "'.mysql_result($req,$i,message).'" WHERE ids= "'.$i.'" AND num= "0" AND rue= "prison"';
		mysql_query($sql);
		}
	$sql = 'UPDATE chat SET moment= "'.date("H:i").'" , posteur= "'.$_SESSION['pseudo'].'" , message= "'.$message.'" WHERE ids= "15" AND num= "0" AND rue= "prison"';
	mysql_query($sql);
	}

function afficher()
{
        $reponse = new xajaxResponse();//Cr�ation d'une instance de xajaxResponse pour traiter les r�ponses serveur
        $chat = "";
        $sql = 'SELECT * FROM chat WHERE rue= "prison" AND num="0" ORDER BY ids ASC';
        $query = mysql_query($sql);
        $sql1 = 'UPDATE principal_tbl SET connec= "oui" , dhc= "'.time().'" WHERE id="'.$_SESSION['id'].'"';
        $req1 = mysql_query($sql1);
        while($array = mysql_fetch_array($query))
			{
			if($array['ids']==15) $chat .= '<span id="fin">';
			if($array['message']!="")
				{
				if(substr(''.$array['message'].'',0,4)=="/me ")
					{
					$chat .= "<strong>[".$array['moment']."]</strong> ".$array['posteur']." ".substr(''.$array['message'].'',4,strlen($array['message']))."<br />";
					}
				else
					{
					$chat .= "<strong>[".$array['moment']."] ".$array['posteur'].":</strong> ".$array['message']."<br />";
					}
				}
			if($array['ids']==15) $chat .= '</span>';
			}       
        $reponse->addAssign("block", "innerHTML", $chat);//Enfin on change le contenu du div block par le contenu de $chat
        return $reponse->getXML();
}

require("xajax.inc.php");
$xajax = new xajax(); //On initialise l'objet xajax
$xajax->registerFunction("afficher");//on enregistre nos fonctions
$xajax->processRequests();//Fonction qui va se charger de faire les requetes APRES AVOIR DECLARER NOS FONCTIONS

mysql_close($db);
?>

<?php if ((date("H")>=21) || (date("H")<=7)) { include("inc_haut_n.php"); } else { include("inc_haut_j.php");} ?>

<?php $xajax->printJavascript(); /* Affiche le Javascript */?>
<script>
function refresh()//script javascript qui va appeler le fonction afficher toutes les 5 secondes
{
	xajax_afficher();
	setTimeout("refresh()", 5000);
}
</script>

<div id="haut">
	<?php include("inc_entete.php"); ?>
	<div class="haut_aligngauche">
		<p>
		<div class="titrepage">
			Salon
		</div>
		<b class="module4ie"><a href="engine.php" class="module4"><img src="im_objets/icon_retour.gif" alt="Retour" />Retour</a></b>
</p>
	</div>
</div>
<div id="centre">
<p>

<table width="480" height="220" style="border:1px solid black;" align="center" cellpadding="1" cellspacing="0" bgcolor="#FBFBFB">
          <tr style="border:1px solid black;">
            <td style="border:1px solid black;" valign="top" >
            	<div id="block2"><div id="block" align="left"></div></div>
            <script>
                refresh();//On appelle la fonction refresh() pour lancer le script
                </script>
           </td>
			<td style="border:1px solid black;" width="120" valign="top"><p align="center">Citoyens en ligne</p>
<p id="scrollautochat">
<?php 
$db = mysql_connect('localhost', 'CENSURE', 'CENSURE') or die("Erreur de connexion au serveur des messages.");
mysql_select_db('CENSURE',$db) or die("Erreur de connexion a la base de donnees des messages.");

$sql2 = 'SELECT arme,vetements,objet FROM principal_tbl WHERE id= "'.$_SESSION['id'].'"' ;
$req2 = mysql_query($sql2);
$vetementso = mysql_result($req2,0,vetements);
$armeo = mysql_result($req2,0,arme);
$objeto = mysql_result($req2,0,objet);
$sql2 = 'SELECT id FROM objets_tbl WHERE nom= "'.$vetementso.'" OR nom= "'.substr($armeo,0,strpos($armeo,"-")).'" OR nom= "'.$objeto.'"' ;
$req2 = mysql_query($sql2);
$res2 = mysql_num_rows($req2);
$detect = 0;
for($p=0;$p!=$res2;$p++)
	{
	$sql3 = 'SELECT id FROM recherche_effets_tbl WHERE ido= "'.mysql_result($req2,$p,id).'" AND nature= "detect"' ;
	$req3 = mysql_query($sql3);
	if(mysql_num_rows($req3)>0) { $detect = 1; }
	}

$sql = 'SELECT id FROM principal_tbl WHERE connec= "oui"';
$req = mysql_query($sql);
$res = mysql_num_rows($req);

for($i=0 ; $i!=$res ; $i++)
	{
	$sql1 = 'SELECT id,pseudo FROM principal_tbl WHERE id= "'.mysql_result($req,$i,id).'" AND action= "prison"';
	$req1 = mysql_query($sql1);
	$res1 =  mysql_num_rows($req1);
	for($l=0 ; $l!=$res1 ; $l++)
		{
		$sql2 = 'SELECT arme,vetements,objet FROM principal_tbl WHERE id= "'.mysql_result($req1,0,id).'"' ;
		$req2 = mysql_query($sql2);
		$vetementso = mysql_result($req2,0,vetements);
		$armeo = mysql_result($req2,0,arme);
		$objeto = mysql_result($req2,0,objet);
		$sql2 = 'SELECT id FROM objets_tbl WHERE nom= "'.$vetementso.'" OR nom= "'.substr($armeo,0,strpos($armeo,"-")).'" OR nom= "'.$objeto.'"' ;
		$req2 = mysql_query($sql2);
		$res2 = mysql_num_rows($req2);
		$vet = 0;
		for($p=0;$p!=$res2;$p++)
			{
			$sql3 = 'SELECT id FROM recherche_effets_tbl WHERE ido= "'.mysql_result($req2,$p,id).'" AND nature= "invisibilite"' ;
			$req3 = mysql_query($sql3);
			if(mysql_num_rows($req3)>0) { $vet = 1; }
			}
		if(($vet==0) || ($detect==1))
			{
			if(mysql_result($req1,0,pseudo)!=$_SESSION['pseudo'])
				{
				print(''.mysql_result($req1,0,pseudo).'<br />');
				}
			}
		}
	}

mysql_close($db);
?>
</div></td></tr></table>
<form method="post" action="#" id="formulaire" name="formulaire">
	  <p align="center">
	  <label>Message: 
	  <input name="message" type="text" id="message" size="50" maxlength="300" autocomplete="off" />
	  </label>
	  <input type="submit" name="Submit" value="Envoyer" />        
	  </p>
<script type="text/javascript" language="JavaScript">document.formulaire.message.focus();</script>
</form>
</p>
</div>
<?php if ((date("H")>=21) || (date("H")<=7)) { include("inc_bas_n.php"); } else { include("inc_bas_j.php"); } ?>
<?php include("inc_bas_de_page.php"); ?>
