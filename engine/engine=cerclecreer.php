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

$sql = 'SELECT id FROM cercles_tbl WHERE pseudo= "'.$_SESSION['pseudo'].'"' ;
$req = mysql_query($sql);
$resc = mysql_num_rows($req);
if($resc>0)
	{
	print('<meta http-equiv="refresh" content="0 ; url=http://v2.dreadcast.net/ingame/engine=cercle.php"> ');
	exit();
	}

if($_SESSION['statut']!="Administrateur"){
for($i=1;$i!=7;$i++)
	{
	$sql = 'SELECT case'.$i.' FROM principal_tbl WHERE case'.$i.' LIKE "%Recueil%" AND id= "'.$_SESSION['id'].'"' ;
	$req = mysql_query($sql);
	$res = mysql_num_rows($req);
	if($res>0)
		{
		$recueil = mysql_result($req,0,'case'.$i.'');
		$sql1 = 'SELECT * FROM signatures_tbl WHERE numero= "'.substr($recueil,7,strlen($recueil)).'"' ;
		$req1 = mysql_query($sql1);
		if((mysql_result($req1,0,sign1)==$_SESSION['pseudo']) && (mysql_result($req1,0,sign2)!="") && (mysql_result($req1,0,sign3)!="") && (mysql_result($req1,0,sign4)!="") && (mysql_result($req1,0,sign5)!=""))
			{
			for($u=1;$u!=6;$u++)
				{
				$sqlc2 = 'SELECT id FROM cercles_tbl WHERE pseudo= "'.mysql_result($req1,0,'sign'.$u.'').'"' ;
				$reqc2 = mysql_query($sqlc2);
				$resc2 = mysql_num_rows($reqc2);
				if($resc2>0)
					{
					print('<meta http-equiv="refresh" content="0 ; url=http://v2.dreadcast.net/ingame/engine.php"> ');
					exit();
					}
				}
			}
		}
	}
}

$sql = 'SELECT credits,statut FROM principal_tbl WHERE id= "'.$_SESSION['id'].'"' ;
$req = mysql_query($sql);
$credits = mysql_result($req,0,credits);
$_SESSION['statut'] = mysql_result($req,0,statut);

mysql_close($db);
?>
<?php if ((date("H")>=21) || (date("H")<=7)) { include("inc_haut_n.php"); } else { include("inc_haut_j.php");} ?>
<script language="JavaScript" type="text/JavaScript">
<!--
function MM_jumpMenu(targ,selObj,restore){ //v3.0
  eval(targ+".location='"+selObj.options[selObj.selectedIndex].value+"'");
  if (restore) selObj.selectedIndex=0;
}
//-->
</script>


<div id="haut">
	<?php include("inc_entete.php"); ?>
	<div class="haut_aligngauche">
		<p>
		<div class="titrepage">
			Votre cercle
		</div>
		<b class="module4ie"><a href="engine=cerclecreation.php" class="module4"><img src="im_objets/icon_retour.gif" alt="Retour" />Retour</a></b>
</p>
	</div>
</div>
<div id="centre">
<p>

<?php
if(statut($_SESSION['statut'])<2)
	{
	print('<meta http-equiv="refresh" content="0 ; url=http://v2.dreadcast.net/ingame/engine.php"> ');
	exit();
	}
if($credits<1500)
	{
	print('<meta http-equiv="refresh" content="0 ; url=http://v2.dreadcast.net/ingame/engine.php"> ');
	exit();
	}
?>
<table width="150" height="25" bgcolor="#FFFFFF" border="1" align="center" cellpadding="0" cellspacing="0">
	<tr>
		<td><div align="center"><strong>Créer un cercle</strong></div></td>
	</tr>
</table>
<table width="450" height="250" bgcolor="#FFFFFF" border="1" align="center" cellpadding="0" cellspacing="0">
	<tr>
	  <td><div align="center">
        <p><strong>Vous &ecirc;tes sur le point de cr&eacute;er un cercle.<br>
  Veuillez remplir les champs suivants pour valider la cr&eacute;ation :<br>
  </strong><em>(tous les champs sont obligatoires)</em> </p>
        <form name="form1" method="post" action="engine=cerclecreerfinished.php">
          <p>Nom du cercle : 
            <input name="nomducercle" type="text" id="nomducercle" size="15" maxlength="20">
            <br />
          <?php
          
          $db = mysql_connect('localhost', 'CENSURE', 'CENSURE') or die("Erreur de connexion au serveur des messages.");
		  mysql_select_db('CENSURE',$db) or die("Erreur de connexion a la base de donnees des messages.");
		  
          $ruessec = "";
          print('<script type="text/javascript" language="JavaScript">
				<!--
				actuel=\'\';
				//-->
				</script>
				Location du cercle : <select name="secteur">');
          for($i=1;$i<=9;$i++){
          	print('<option onclick="$(\'#ruesec'.$i.'\').show();$(\'#\'+actuel).hide();actuel=\'ruesec'.$i.'\';" value="'.$i.'">Secteur '.$i.'</option>');
          	$ruessec .= ' <select style="display:none;" name="rue" id="ruesec'.$i.'"><option value="">Peu importe la rue</option>';
          	$rues = ruesDuSecteur($i);
		  	foreach($rues as $newrue){
          		$ruessec .= '<option value="'.$newrue.'">'.ucwords($newrue).'</option>';
          	}
          	$ruessec .= '</select>';
          }
          print('</select>'.$ruessec.'<br />');
          
          ?>
          Type de cercle : 
          <select name="typedecercle" id="typedecercle">
            <option value="Associatif" selected>Associatif</option>
              <option value="Politique">Politique</option>
            </select>
          <br />
          <input type="checkbox" name="public" value="1" /> Ce cercle apparaîtra dans la liste des cercles (cercle public)
          </p>
          <p>Nom du poste de dirigeant (vous) : 
            <input name="postediri" type="text" id="postediri" size="10" maxlength="20"> 
            <em> (possède tous les droits) </em><br>
            Nom du poste le plus bas de l'&eacute;chelle : 
            <input name="postebe" type="text" id="postebe" size="10" maxlength="20"> 
            <em>(aucun droit)</em></p>
          <p>
            <input type="submit" name="Submit" value="Valider la cr&eacute;ation"> 
          </p>
        </form>        <p><strong> </strong></p></div></td>
	</tr>
</table>


</p>
</div>
<?php if($chat=="oui") 	{ if ((date("H")>=21) || (date("H")<=7)) { include("inc_bas_n_c.php"); } else { include("inc_bas_j_c.php"); } } else {	if ((date("H")>=21) || (date("H")<=7)) { include("inc_bas_n.php"); } else { include("inc_bas_j.php"); }	} ?>
<?php include("inc_bas_de_page.php"); ?>
