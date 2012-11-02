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

$sql = 'SELECT credits FROM principal_tbl WHERE id= "'.$_SESSION['id'].'"' ;
$req = mysql_query($sql);
$_SESSION['credits'] = mysql_result($req,0,credits); 

mysql_close($db);
?>
<?php if ((date("H")>=21) || (date("H")<=7)) { include("inc_haut_n.php"); } else { include("inc_haut_j.php");} ?>

<div id="haut">
	<?php include("inc_entete.php"); ?>
	<div class="haut_aligngauche">
		<p>
		<div class="titrepage">
			Cr&eacute;ation d'entreprise
		</div>
		<b class="module4ie"><a href="engine=activite.php" class="module4"><img src="im_objets/icon_retour.gif" alt="Retour" />Retour</a></b>
</p>
	</div>
</div>
<div id="centre">
<p>

<?php
if($_SESSION['credits']<3500)
	{
	print('<p align="center"><strong>Cr&eacute;er une entreprise co&ucirc;te 3500 Cr&eacute;dits. </strong>');
	exit();
	}
?></span><strong>
          <?php 
$_SESSION['pmd'] = $_POST['pmd'];
$_SESSION['gad'] = $_POST['gad'];
$_SESSION['gmd'] = $_POST['gmd'];
$_SESSION['vd'] = $_POST['vd'];
$_SESSION['pharmacie'] = $_POST['pharmacie'];
$_SESSION['chambresl'] = $_POST['chambresl'];
$_SESSION['armesprim'] = $_POST['armesprim'];
$_SESSION['armestir'] = $_POST['armestir'];
$_SESSION['armesav'] = $_POST['armesav'];
$_SESSION['voitures'] = $_POST['voitures'];
$_SESSION['autrev'] = $_POST['autrev'];
$_SESSION['oa'] = $_POST['oa'];
$_SESSION['om'] = $_POST['om'];
$_SESSION['soie'] = $_POST['soie'];
$_SESSION['cristal'] = $_POST['cristal'];
$_SESSION['gcuisine'] = $_POST['gcuisine'];
$_SESSION['races'] = $_POST['races'];
$_SESSION['ages'] = $_POST['ages'];
$_SESSION['travails'] = $_POST['travails'];
$_SESSION['adresses'] = $_POST['adresses'];
$_SESSION['messages'] = $_POST['messages'];
$_SESSION['reactions'] = $_POST['reactions'];
$_SESSION['prodvet'] = $_POST['prodvet'];
$_SESSION['prodarmesc'] = $_POST['prodarmesc'];
$_SESSION['prodarmest'] = $_POST['prodarmest'];
$_SESSION['prodouu'] = $_POST['prodouu'];

?>
          <em>Quatri&egrave;me &eacute;tape : Locaux </em></strong>
		<form name="allera" id="allera" method="post" action="engine=creerent5.php">
          <div align="center">
            <p>Type de locaux d&eacute;sir&eacute; :
                <select name="aent" id="select3">
                  <option value="25" selected="selected">Local 25m&sup2; (2 personnes) - Offert</option>
                  <option value="50">Local 50m&sup2; (4 personnes) - 400 Cr&eacute;dits</option>
                  <option value="100">Local 100m&sup2; (6 personnes) - 1000 Cr&eacute;dits</option>
                  <option value="200">Local 200m&sup2; (8 personnes) - 2000 Cr&eacute;dits</option>
                </select>
                <strong><span class="Style1">*</span></strong></p>
            <p>
            
<script type="text/javascript" language="JavaScript">
<!--
	actuel='Choisissez un secteur';
	actuelrues='';
	rues='';
	leclic='';
	<?php
	
	$db = mysql_connect('localhost', 'CENSURE', 'CENSURE') or die("Erreur de connexion au serveur des messages.");
	mysql_select_db('CENSURE',$db) or die("Erreur de connexion a la base de donnees des messages.");
	
	$texte = "";
	$texte .= "vartext1='<strong>Secteur 1</strong>';
	rues1='
		<input type=\"hidden\" name=\"sec\" value=\"1\" />
		Choisissez une rue :<br /><select style=\"width:200px;\" name=\"rue\"><option value=\"\">Peu importe</option>";
		$rues = ruesDuSecteur(1);
		foreach($rues as $newrue){
			$texte .= '<option value="'.$newrue.'">'.ucwords($newrue).'</option>';
		}
		$texte .= "</select>
		<input type=\"submit\" name=\"Submit2\" value=\"Valider\" />';
	vartext2='<strong>Secteur 2</strong>';
	rues2='
		<input type=\"hidden\" name=\"sec\" value=\"2\" />
		Choisissez une rue :<br /><select style=\"width:200px;\" name=\"rue\"><option value=\"\">Peu importe</option>";
		$rues = ruesDuSecteur(2);
		foreach($rues as $newrue){
			$texte .= '<option value="'.$newrue.'">'.ucwords($newrue).'</option>';
		}
		$texte .= "</select>
		<input type=\"submit\" name=\"Submit2\" value=\"Valider\" />';
	vartext3='<strong>Secteur 3</strong>';
	rues3='
		<input type=\"hidden\" name=\"sec\" value=\"3\" />
		Choisissez une rue :<br /><select style=\"width:200px;\" name=\"rue\"><option value=\"\">Peu importe</option>";
		$rues = ruesDuSecteur(3);
		foreach($rues as $newrue){
			$texte .= '<option value="'.$newrue.'">'.ucwords($newrue).'</option>';
		}
		$texte .= "</select>
		<input type=\"submit\" name=\"Submit2\" value=\"Valider\" />';
	vartext4='<strong>Secteur 4</strong>';
	rues4='
		<input type=\"hidden\" name=\"sec\" value=\"4\" />
		Choisissez une rue :<br /><select style=\"width:200px;\" name=\"rue\"><option value=\"\">Peu importe</option>";
		$rues = ruesDuSecteur(4);
		foreach($rues as $newrue){
			$texte .= '<option value="'.$newrue.'">'.ucwords($newrue).'</option>';
		}
		$texte .= "</select>
		<input type=\"submit\" name=\"Submit2\" value=\"Valider\" />';
	vartext5='<strong>Secteur 5</strong>';
	rues5='
		<input type=\"hidden\" name=\"sec\" value=\"5\" />
		Choisissez une rue :<br /><select style=\"width:200px;\" name=\"rue\"><option value=\"\">Peu importe</option>";
		$rues = ruesDuSecteur(5);
		foreach($rues as $newrue){
			$texte .= '<option value="'.$newrue.'">'.ucwords($newrue).'</option>';
		}
		$texte .= "</select>
		<input type=\"submit\" name=\"Submit2\" value=\"Valider\" />';
	vartext6='<strong>Secteur 6</strong>';
	rues6='
		<input type=\"hidden\" name=\"sec\" value=\"6\" />
		Choisissez une rue :<br /><select style=\"width:200px;\" name=\"rue\"><option value=\"\">Peu importe</option>";
		$rues = ruesDuSecteur(6);
		foreach($rues as $newrue){
			$texte .= '<option value="'.$newrue.'">'.ucwords($newrue).'</option>';
		}
		$texte .= "</select>
		<input type=\"submit\" name=\"Submit2\" value=\"Valider\" />';
	vartext7='<strong>Secteur 7</strong>';
	rues7='
		<input type=\"hidden\" name=\"sec\" value=\"7\" />
		Choisissez une rue :<br /><select style=\"width:200px;\" name=\"rue\"><option value=\"\">Peu importe</option>";
		$rues = ruesDuSecteur(7);
		foreach($rues as $newrue){
			$texte .= '<option value="'.$newrue.'">'.ucwords($newrue).'</option>';
		}
		$texte .= "</select>
		<input type=\"submit\" name=\"Submit2\" value=\"Valider\" />';
	vartext8='<strong>Secteur 8</strong>';
	rues8='
		<input type=\"hidden\" name=\"sec\" value=\"8\" />
		Choisissez une rue :<br /><select style=\"width:200px;\" name=\"rue\"><option value=\"\">Peu importe</option>";
		$rues = ruesDuSecteur(8);
		foreach($rues as $newrue){
			$texte .= '<option value="'.$newrue.'">'.ucwords($newrue).'</option>';
		}
		$texte .= "</select>
		<input type=\"submit\" name=\"Submit2\" value=\"Valider\" />';
	vartext9='<strong>Secteur 9</strong>';
	rues9='
		<input type=\"hidden\" name=\"sec\" value=\"9\" />
		Choisissez une rue :<br /><select style=\"width:200px;\" name=\"rue\"><option value=\"\">Peu importe</option>";
		$rues = ruesDuSecteur(9);
		foreach($rues as $newrue){
			$texte .= '<option value="'.$newrue.'">'.ucwords($newrue).'</option>';
		}
		$texte .= "</select>
		<input type=\"submit\" name=\"Submit2\" value=\"Valider\" />';";
	
	echo str_replace("\t","",str_replace("\n","",$texte));
	?>
//-->
</script>
<p style="position:relative;top:20px;z-index:200;">
	Choisissez le secteur et la rue dans laquelle vous souhaitez créer votre entreprise.<br /><br />
	<table style="position:relative;top:10px;right:120px;">
		<tr>
			<td id="var1" style="border:1px solid #444;width:50px;height:50px;background-color:#777;" onclick="$('#'+leclic).css('background-color','#777');leclic='var1';actuel=vartext1;actuelrues=rues1;$('#lechoixrue').html(rues1);$(this).css('background-color','#eee');" onmouseover="$('#sectnum').html(vartext1);$(this).css('background-color','#999');if(leclic=='var1'){$(this).css('background-color','#eee');}" onmouseout="$('#sectnum').html(actuel);$('#lechoixrue').html(actuelrues);$(this).css('background-color','#777');if(leclic=='var1'){$(this).css('background-color','#eee');}"></td>
			<td id="var2" style="border:1px solid #444;width:50px;height:50px;background-color:#777;" onclick="$('#'+leclic).css('background-color','#777');leclic='var2';actuel=vartext2;actuelrues=rues2;$('#lechoixrue').html(rues2);$(this).css('background-color','#eee');" onmouseover="$('#sectnum').html(vartext2);$(this).css('background-color','#999');if(leclic=='var2'){$(this).css('background-color','#eee');}" onmouseout="$('#sectnum').html(actuel);$('#lechoixrue').html(actuelrues);$(this).css('background-color','#777');if(leclic=='var2'){$(this).css('background-color','#eee');}"></td>
			<td id="var3" style="border:1px solid #444;width:50px;height:50px;background-color:#777;" onclick="$('#'+leclic).css('background-color','#777');leclic='var3';actuel=vartext3;actuelrues=rues3;$('#lechoixrue').html(rues3);$(this).css('background-color','#eee');" onmouseover="$('#sectnum').html(vartext3);$(this).css('background-color','#999');if(leclic=='var3'){$(this).css('background-color','#eee');}" onmouseout="$('#sectnum').html(actuel);$('#lechoixrue').html(actuelrues);$(this).css('background-color','#777');if(leclic=='var3'){$(this).css('background-color','#eee');}"></td>
		</tr>
		<tr>
			<td id="var4" style="border:1px solid #444;width:50px;height:50px;background-color:#777;" onclick="$('#'+leclic).css('background-color','#777');leclic='var4';actuel=vartext4;actuelrues=rues4;$('#lechoixrue').html(rues4);$(this).css('background-color','#eee');" onmouseover="$('#sectnum').html(vartext4);$(this).css('background-color','#999');if(leclic=='var4'){$(this).css('background-color','#eee');}" onmouseout="$('#sectnum').html(actuel);$('#lechoixrue').html(actuelrues);$(this).css('background-color','#777');if(leclic=='var4'){$(this).css('background-color','#eee');}"></td>
			<td id="var5" style="border:1px solid #444;width:50px;height:50px;background-color:#777;" onclick="$('#'+leclic).css('background-color','#777');leclic='var5';actuel=vartext5;actuelrues=rues5;$('#lechoixrue').html(rues5);$(this).css('background-color','#eee');" onmouseover="$('#sectnum').html(vartext5);$(this).css('background-color','#999');if(leclic=='var5'){$(this).css('background-color','#eee');}" onmouseout="$('#sectnum').html(actuel);$('#lechoixrue').html(actuelrues);$(this).css('background-color','#777');if(leclic=='var5'){$(this).css('background-color','#eee');}"></td>
			<td id="var6" style="border:1px solid #444;width:50px;height:50px;background-color:#777;" onclick="$('#'+leclic).css('background-color','#777');leclic='var6';actuel=vartext6;actuelrues=rues6;$('#lechoixrue').html(rues6);$(this).css('background-color','#eee');" onmouseover="$('#sectnum').html(vartext6);$(this).css('background-color','#999');if(leclic=='var6'){$(this).css('background-color','#eee');}" onmouseout="$('#sectnum').html(actuel);$('#lechoixrue').html(actuelrues);$(this).css('background-color','#777');if(leclic=='var6'){$(this).css('background-color','#eee');}"></td>
		</tr>
		<tr>
			<td id="var7" style="border:1px solid #444;width:50px;height:50px;background-color:#777;" onclick="$('#'+leclic).css('background-color','#777');leclic='var7';actuel=vartext7;actuelrues=rues7;$('#lechoixrue').html(rues7);$(this).css('background-color','#eee');" onmouseover="$('#sectnum').html(vartext7);$(this).css('background-color','#999');if(leclic=='var7'){$(this).css('background-color','#eee');}" onmouseout="$('#sectnum').html(actuel);$('#lechoixrue').html(actuelrues);$(this).css('background-color','#777');if(leclic=='var7'){$(this).css('background-color','#eee');}"></td>
			<td id="var8" style="border:1px solid #444;width:50px;height:50px;background-color:#777;" onclick="$('#'+leclic).css('background-color','#777');leclic='var8';actuel=vartext8;actuelrues=rues8;$('#lechoixrue').html(rues8);$(this).css('background-color','#eee');" onmouseover="$('#sectnum').html(vartext8);$(this).css('background-color','#999');if(leclic=='var8'){$(this).css('background-color','#eee');}" onmouseout="$('#sectnum').html(actuel);$('#lechoixrue').html(actuelrues);$(this).css('background-color','#777');if(leclic=='var8'){$(this).css('background-color','#eee');}"></td>
			<td id="var9" style="border:1px solid #444;width:50px;height:50px;background-color:#777;" onclick="$('#'+leclic).css('background-color','#777');leclic='var9';actuel=vartext9;actuelrues=rues9;$('#lechoixrue').html(rues9);$(this).css('background-color','#eee');" onmouseover="$('#sectnum').html(vartext9);$(this).css('background-color','#999');if(leclic=='var9'){$(this).css('background-color','#eee');}" onmouseout="$('#sectnum').html(actuel);$('#lechoixrue').html(actuelrues);$(this).css('background-color','#777');if(leclic=='var9'){$(this).css('background-color','#eee');}"></td>
		</tr>
	</table>
	<p style="position:absolute;top:220px;left:210px;text-align:left;">
		<span id="sectnum">Choisissez un secteur</span><br /><br />
		<span id="lechoixrue"></span>
	</p>
</p>
            
            </p>
          </div>
		  </form>
		  

</p>
</div>
<?php if($chat=="oui") 	{ if ((date("H")>=21) || (date("H")<=7)) { include("inc_bas_n_c.php"); } else { include("inc_bas_j_c.php"); } } else {	if ((date("H")>=21) || (date("H")<=7)) { include("inc_bas_n.php"); } else { include("inc_bas_j.php"); }	} ?>
<?php include("inc_bas_de_page.php"); ?>
