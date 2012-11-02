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
			Courrier administrateur
		</div>
		<b class="module4ie"><a href="engine=panneau.php" class="module4"><img src="im_objets/icon_retour.gif" alt="Retour" />Retour</a></b>
</p>
	</div>
</div>
<div id="centre">
<p>

<?php  
	$db = mysql_connect('localhost', 'CENSURE', 'CENSURE') or die("Erreur de connexion au serveur des messages.");
	mysql_select_db('CENSURE',$db) or die("Erreur de connexion a la base de donnees des messages.");

    if($_POST['Platinium']==1)
        {
        $sqlb = 'SELECT pseudo FROM principal_tbl WHERE statut="Platinium"' ;
        $reqb = mysql_query($sqlb);
        $resb = mysql_num_rows($reqb);
        for($i=0;$i!=$resb;$i++)
            {
            $pseudo = mysql_result($reqb,$i,pseudo);
            $sql = 'INSERT INTO messages_tbl(id,auteur,cible,message,objet,moment) VALUES("","Dreadcast","'.$pseudo.'","<br />'.str_replace("\n","<br />",''.htmlentities($_POST["message"],ENT_QUOTES).'').'<br /><br />--------<br /><br />Cordialement,<br /><br />L\'équipe de DreadCast","'.$_POST['cible2'].'","'.time().'")' ;
            $req = mysql_query($sql);
            }
        }
    else
        {
        $sqlb = 'SELECT id FROM principal_tbl WHERE pseudo="'.$_POST["cible"].'"' ;
        $reqb = mysql_query($sqlb);
        $resb = mysql_num_rows($reqb);
        if($resb!=0)
            {
            
            $sql = 'INSERT INTO messages_tbl(id,auteur,cible,message,objet,moment) VALUES("","Dreadcast","'.$_POST["cible"].'","<br />'.str_replace("\n","<br />",''.htmlentities($_POST["message"],ENT_QUOTES).'').'<br /><br />--------<br /><br />Cordialement,<br /><br />L\'équipe de DreadCast","'.$_POST['cible2'].'","'.time().'")' ;
            $req = mysql_query($sql);

            $sql = 'INSERT INTO messagesadmin_archives_tbl(id,auteur,message,objet,moment) VALUES("","'.$_POST["cible"].'","<br />'.str_replace("\n","<br />",''.htmlentities($_POST["message"],ENT_QUOTES).'').'<br /><br />--------<br /><br />Cordialement,<br /><br />L\'équipe de DreadCast","'.$_POST['cible2'].'","'.time().'")' ;
            $req = mysql_query($sql);

            $sql = 'DELETE FROM messagesadmin_tbl WHERE id='.$_GET['id'].'' ;
            $req = mysql_query($sql);

            print('Votre message a correctement été envoy&eacute; &agrave; '.$_POST["cible"].'.<br> ');
            }
        else
            {
            print('Personne ne s\'appelle <i>'.$_POST["cible"].'</i>.<br> ');
            }
        }
	
	mysql_close($db);

print('<meta http-equiv="refresh" content="0 ; url=http://v2.dreadcast.net/ingame/engine=panneau.php"> ');

?>

</p>
</div>
<?php if($chat=="oui") 	{ if ((date("H")>=21) || (date("H")<=7)) { include("inc_bas_n_c.php"); } else { include("inc_bas_j_c.php"); } } else {	if ((date("H")>=21) || (date("H")<=7)) { include("inc_bas_n.php"); } else { include("inc_bas_j.php"); }	} ?>
<?php include("inc_bas_de_page.php"); ?>
