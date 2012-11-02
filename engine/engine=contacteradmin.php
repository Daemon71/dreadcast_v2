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

$sql = 'SELECT message FROM messagesadmin_tbl WHERE id= "'.$_GET['id'].'"' ;
$req = mysql_query($sql);
$res = mysql_num_rows($req);
if($res>0) $msg = mysql_result($req,0,message);

mysql_close($db);
?>
<?php if ((date("H")>=21) || (date("H")<=7)) { include("inc_haut_n.php"); } else { include("inc_haut_j.php");} ?>

<div id="haut">
	<?php include("inc_entete.php"); ?>
	<div class="haut_aligngauche">
		<p>
		<div class="titrepage">
			Courrier administrateur
		</div>
		<b class="module4ie"><a href="engine=panneau.php" class="module4"><img src="im_objets/icon_retour.gif" alt="Retour" />Retour</a></b>
</p>
	</div>
</div>
<div id="centre">
<p>

<strong>Envoyer un message <?php if($_GET['p']==1) print('aux Platiniums'); else print('priv&eacute;'); ?> :</strong>
<form name="allera" id="allera" method="post" action="engine=envoyerad.php?id=<?php print($_GET['id']); ?>">
<? 
if($_GET['p']==1) print('<input type="hidden" value="1" name="Platinium" />');
else
    {
    print('Cible :
            <input name="cible" type="text" id="cible2" ');
    $cible = str_replace("%20"," ",''.$_GET['cible'].'');
    if($cible!="")
        {
        print('value="'.$cible.'"');
        }
     print('size="10" />');
    }
?>
<br />
Objet :
<input name="cible2" type="text" id="cible" size="20" maxlength="20" <? 
$cible = str_replace("%20"," ",''.$_GET['objet'].'');
if($cible!="")
{
print('value="RE:'.$cible.'"'); 
}
?> />
<br /><br />
Message :<br /><br />
<textarea name="message" cols="55" rows="6" id="message"><?php if($_GET['p']==1) print('Message de l\'équipe de Dreadcast
A l\'attention des Platiniums');
else print('REPONSE DE L\'EQUIPE DE DREADCAST');
?>

--------
Inscrire le message ici.
--------
<?php
if($_GET['p']==1) print('Très bonne journée à toutes et à tous !');
else  print('MESSAGE D\'ORIGINE
--------
'.preg_replace("#<span (.+)</span>#isU","",str_replace("<br />","",$msg)).'

--------');
?></textarea>
<br />
<input type="submit" name="Submit" value="Envoyer" />
</form>


</p>
</div>
<?php if($chat=="oui") 	{ if ((date("H")>=21) || (date("H")<=7)) { include("inc_bas_n_c.php"); } else { include("inc_bas_j_c.php"); } } else {	if ((date("H")>=21) || (date("H")<=7)) { include("inc_bas_n.php"); } else { include("inc_bas_j.php"); }	} ?>
<?php include("inc_bas_de_page.php"); ?>
