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
			Suppression de messages
		</div>
		</p>
	</div>
</div>
<div id="centre">
<p>

<?php
$db = mysql_connect('localhost', 'CENSURE', 'CENSURE') or die("Erreur de connexion au serveur des messages.");
mysql_select_db('CENSURE',$db) or die("Erreur de connexion a la base de donnees des messages.");

if($_POST['messagesde']=="" || $_POST['messagesde']=="tous")
	{
	$sql = 'SELECT id FROM messages_tbl WHERE cible = "'.$_SESSION['pseudo'].'"' ;
	$req = mysql_query($sql);
	$res = mysql_num_rows($req);

	$nb = 0;

	print('<form action="engine=supprmessage.php" method="post">');

	for($i=0;$i<$res;$i++)
		{
		if($_POST[mysql_result($req,$i,id)] == "on")
			{
			$idunique = mysql_result($req,$i,id);
			print('<input type="hidden" name="n'.$nb.'" value="'.$idunique.'" />');
			$nb += 1;
			}
		elseif($_POST['messagesde']=="tous")
			{
			$idunique = mysql_result($req,$i,id);
			print('<input type="hidden" name="n'.$nb.'" value="'.$idunique.'" />');
			$nb += 1;
			}
		}
	
	if($nb == 0)
		{
		//print('<meta http-equiv="refresh" content="0 ; url=http://v2.dreadcast.net/ingame/engine=messages.php"> ');
		//exit();
		print('&Ecirc;tes-vous s&ucirc;r de vouloir supprimer <strong style="color:red;font-size:16px;">tous</strong> vos messages ?</p>
		<p align="center"><a href="engine=supprmessage.php?tout" style="color:#40719A;">Oui</a> - <a href="engine=messages.php" style="color:#40719A;">Non</a>
		<input type="hidden" name="nb" value="'.$nb.'" />
		</form>');
		}
	elseif($nb == 1)
		{
		print('<img src="im_objets/loader.gif" alt="..." />');
		$sql = 'SELECT id FROM messages_tbl WHERE id='.$idunique.' AND cible="'.$_SESSION['pseudo'].'"' ;
		$req = mysql_query($sql);
		$res = mysql_num_rows($req);
		if($res>0)
			{	
			$sql = 'DELETE FROM messages_tbl WHERE id='.$idunique.'' ;
			$req = mysql_query($sql);
			}
			
		print('<meta http-equiv="refresh" content="0 ; url=http://v2.dreadcast.net/ingame/engine=messages.php"> ');
		exit();
		}
	else
		{
	print('&Ecirc;tes-vous s&ucirc;r de vouloir supprimer ces '.$nb.' messages ?</p>
	<p align="center"><input type="submit" name="submit" value="Oui" style="border:0;background:none;font-size:12px;color:#40719A;" class="pointeur" /> - <a href="engine=messages.php" style="color:#40719A;">Non</a>
	<input type="hidden" name="nb" value="'.$nb.'" />
	</form>');
		}
	}
else
	{
	$sql = 'SELECT id FROM messages_tbl WHERE auteur="'.$_POST['messagesde'].'" AND cible = "'.$_SESSION['pseudo'].'"' ;
	$req = mysql_query($sql);
	$nb = mysql_num_rows($req);
	
	print('<form action="engine=supprmessage.php" method="post">');
	
	if($nb == 0)
		{
		print('<meta http-equiv="refresh" content="0 ; url=http://v2.dreadcast.net/ingame/engine=messages.php"> ');
		exit();
		}
	
	if($nb==1)
		{
		print('<img src="im_objets/loader.gif" alt="..." />');
		$sql = 'SELECT id FROM messages_tbl WHERE auteur="'.$_POST['messagesde'].'" AND cible="'.$_SESSION['pseudo'].'"' ;
		$req = mysql_query($sql);
		$res = mysql_num_rows($req);
		if($res>0)
			{	
			$sql = 'DELETE FROM messages_tbl WHERE auteur="'.$_POST['messagesde'].'" AND cible="'.$_SESSION['pseudo'].'"' ;
			$req = mysql_query($sql);
			}
			
		print('<meta http-equiv="refresh" content="0 ; url=http://v2.dreadcast.net/ingame/engine=messages.php"> ');
		exit();
		}
	
	$auteurmess = ($_POST['messagesde']=="Dreadcast")?"officiels":"de ".$_POST['messagesde'];
	
	print('&Ecirc;tes-vous s&ucirc;r de vouloir supprimer les '.$nb.' messages '.$auteurmess.' ?</p>
	<p align="center"><input type="submit" name="submit" value="Oui" style="border:0;background:none;font-size:12px;color:#40719A;" class="pointeur" /> - <a href="engine=messages.php" style="color:#40719A;">Non</a>
	<input type="hidden" name="messde" value="'.$_POST['messagesde'].'" />
	</form>');
	}



mysql_close($db);
?>


</p>
</div>
<?php if($chat=="oui") 	{ if ((date("H")>=21) || (date("H")<=7)) { include("inc_bas_n_c.php"); } else { include("inc_bas_j_c.php"); } } else {	if ((date("H")>=21) || (date("H")<=7)) { include("inc_bas_n.php"); } else { include("inc_bas_j.php"); }	} ?>
<?php include("inc_bas_de_page.php"); ?>
