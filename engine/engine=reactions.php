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

$sql = 'SELECT * FROM principal_tbl WHERE pseudo= "'.$_SESSION['pseudo'].'"' ;
$req = mysql_query($sql);
$_SESSION['id'] = mysql_result($req,0,id);
$_SESSION['rue'] = mysql_result($req,0,rue);
$_SESSION['num'] = mysql_result($req,0,num);
$_SESSION['action'] = mysql_result($req,0,action);
$_SESSION['alim'] = mysql_result($req,0,alim);
$_SESSION['sante'] = mysql_result($req,0,sante);
$_SESSION['drogue'] = mysql_result($req,0,drogue);
$_SESSION['fatigue'] = mysql_result($req,0,fatigue);
$_SESSION['lieu'] = strtolower($_SESSION['rue']);
$_SESSION['lieu'] = ucwords($_SESSION['lieu']);

$sql = 'SELECT id,rattaque,rpolice,rintrusion,rvol,rapproche FROM principal_tbl WHERE id= "'.$_SESSION['id'].'"' ;
$req = mysql_query($sql);
$reactionattaque = mysql_result($req,0,rattaque); 
$reactionpolice = mysql_result($req,0,rpolice); 
$reactionintrus = mysql_result($req,0,rintrusion); 
$reactionvol = mysql_result($req,0,rvol); 
$reactionapproche = mysql_result($req,0,rapproche); 

$sql = 'SELECT * FROM principal_tbl WHERE id= "'.$_SESSION['id'].'"' ;
$req = mysql_query($sql);
$ruee = mysql_result($req,0,ruel); 

if($ruee=="Aucune")
	{
	if(reactionattaque=="Fuir") 
		{
		$sql = 'UPDATE principal_tbl SET rattaque= "Aucune" WHERE id= "'.$_SESSION['id'].'"' ;
		$req = mysql_query($sql);
		$reactionattaque = "Aucune";
		} 
	if($reactionpolice=="Fuir") 
		{
		$sql = 'UPDATE principal_tbl SET rpolice= "Aucune" WHERE id= "'.$_SESSION['id'].'"' ;
		$req = mysql_query($sql);
		$reactionpolice = "Aucune";
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
			R&eacute;actions
		</div>
		</p>
	</div>
</div>
<div id="centre">
<p>

Voici les r&eacute;actions que vous avez face aux &eacute;v&egrave;nements particuliers :<br>
  (que vous soyez connect&eacute; ou non)
		<form name="form1" method="post" action="">
          <p align="center">Quelqu'un vous attaque : 
            <select name="menu1" onChange="MM_jumpMenu('parent',this,0)">
                  <? if($reactionattaque=="Aucune") { print ('<option selected>Aucune</option>'); } ?>
                  <option value="engine=reactionattaque.php?Attaquer" <? if($reactionattaque=="Attaquer") { print ('selected'); } ?>>Attaquer</option>
<? 
if($ruee!="Aucune")
	{
	print('<option value="engine=reactionattaque.php?Fuir" ');
	if($reactionattaque=="Fuir") { print ('selected'); } 
	print('>Fuir chez vous</option>');
	}

?>
              </select>
             </p>
          <p align="center">Un agent de  police vous met en &eacute;tat d'arrestation : 
            <select name="select" onChange="MM_jumpMenu('parent',this,0)">
                  <? if($reactionpolice=="Aucune") { print ('<option selected>Aucune</option>'); } ?>
                  <option value="engine=reactionarret.php?Attaquer" <? if($reactionpolice=="Attaquer") { print ('selected'); } ?>>Attaquer</option>
                  <option value="engine=reactionarret.php?Accepter" <? if($reactionpolice=="Accepter") { print ('selected'); } ?>>Vous laissez faire</option>
<? 
if($ruee!="Aucune")
	{
	print('<option value="engine=reactionarret.php?Fuir" ');
	if($reactionpolice=="Fuir") { print ('selected'); } 
	print('>Fuir chez vous</option>');
	}

?>
              </select>
            </p>
          <p align="center"> Quelqu'un p&eacute;n&egrave;tre chez vous :
            <select name="select2" onChange="MM_jumpMenu('parent',this,0)">
                  <? if($reactionintrus=="Aucune") { print ('<option selected>Aucune</option>'); } ?>
                  <option value="engine=reactionintrus.php?Attaquer" <? if($reactionintrus=="Attaquer") { print ('selected'); } ?>>Attaquer</option>
                  <option value="engine=reactionintrus.php?Police" <? if($reactionintrus=="Police") { print ('selected'); } ?>>Fuir vers un central de police</option>
              </select>
            </p>
          <p align="center">Quelqu'un tente de vous voler :
            <select name="select3" onChange="MM_jumpMenu('parent',this,0)">
                  <? if($reactionvol=="Aucune") { print ('<option selected>Aucune</option>'); } ?>
                  <option value="engine=reactionvol.php?Attaquer" <? if($reactionvol=="Attaquer") { print ('selected'); } ?>>Attaquer</option>
                  <option value="engine=reactionvol.php?Police" <? if($reactionvol=="Police") { print ('selected'); } ?>>Fuir vers un central de police</option>
              </select>
		  </form>
          <p align="center">Quelqu'un s'approche dangereusement de vous :
            <select name="select3" onChange="MM_jumpMenu('parent',this,0)">
                  <? if($reactionapproche=="Aucune") { print ('<option selected>Aucune</option>'); } ?>
                  <option value="engine=reactionapproche.php?Attaquer" <? if($reactionapproche=="Attaquer") { print ('selected'); } ?>>Attaquer</option>
<? 
if($ruee!="Aucune")
	{
	print('<option value="engine=reactionapproche.php?Fuir" ');
	if($reactionapproche=="Fuir") { print ('selected'); } 
	print('>Fuir chez vous</option>');
	}

?>
              </select>
		  </form>


</p>
</div>
<?php if($chat=="oui") 	{ if ((date("H")>=21) || (date("H")<=7)) { include("inc_bas_n_c.php"); } else { include("inc_bas_j_c.php"); } } else {	if ((date("H")>=21) || (date("H")<=7)) { include("inc_bas_n.php"); } else { include("inc_bas_j.php"); }	} ?>
<?php include("inc_bas_de_page.php"); ?>
