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

$sql = 'SELECT nom,type,ouvert FROM entreprises_tbl WHERE rue= "'.$_SESSION['lieu'].'" AND num= "'.$_SESSION['num'].'"' ;
$req = mysql_query($sql);
$type = mysql_result($req,0,type);
$noment = mysql_result($req,0,nom);
$ouvert = mysql_result($req,0,ouvert);

if($type!="agence immobiliaire" || $ouvert!="oui" || $_GET['id']=="")
	{
	print('<meta http-equiv="refresh" content="0 ; url=http://v2.dreadcast.net/ingame/engine.php"> ');
	exit();
	}

$id_obj = htmlentities($_GET['id']);

$sql = 'SELECT S.nombre FROM stocks_tbl S, objets_tbl O WHERE O.id= "'.$id_obj.'" AND O.nom = S.objet AND S.entreprise = "'.$noment.'"' ;
$req = mysql_query($sql);
if(!mysql_num_rows($req) || mysql_result($req,0,nombre) == 0)
	{
	print('<meta http-equiv="refresh" content="0 ; url=http://v2.dreadcast.net/ingame/engine.php"> ');
	exit();
	}

$typeobj=(empty($_POST['typeobj']))?"tous":$_POST['typeobj'];
if(isset($_GET['typeobj'])) $typeobj=$_GET['typeobj'];

mysql_close($db);

?>
<?php if ((date("H")>=21) || (date("H")<=7)) { include("inc_haut_n.php"); } else { include("inc_haut_j.php");} ?>

<div id="haut">
	<?php include("inc_entete.php"); ?>
	<div class="haut_aligngauche">
		<p>
		<div class="titrepage">
			Boutique
		</div>
		<b class="module4ie"><a href="engine.php" class="module4"><img src="im_objets/icon_retour.gif" alt="Retour" />Retour</a></b>
		</p>
	</div>
</div>
<div id="centre_agence">
<a id="boutonAllop" href="engine=allopass.php"></a>
<p id="selectionner">
	<form action="engine=boutique.php" method="post" name="btype" id="leform">
		<select name="typeobj" id="leselect" onchange="submit();">
			<?php if($type=="boutique armes") { print('<option value="choisir"'); if($typeobj=="choisir")print(' selected="selected"'); print(' style="display:none;">Choisissez le type d\'objet recherch&eacute;</option>'); }
			print('<option value="tous"'); if($typeobj=="tous") print(' selected="selected"'); print('>Tous</option>');
			
			$db = mysql_connect('localhost', 'CENSURE', 'CENSURE') or die("Erreur de connexion au serveur des messages.");
			mysql_select_db('CENSURE',$db) or die("Erreur de connexion a la base de donnees des messages.");

			$sql = 'SELECT id,objet,nombre,pvente FROM stocks_tbl WHERE entreprise= "'.$noment.'"' ;
			$req = mysql_query($sql);
			$res = mysql_num_rows($req);
			
			$tab=array();
			
			for($i=0 ; $i != $res ; $i++)
				{
				$sqlo = 'SELECT type FROM objets_tbl WHERE nom= "'.mysql_result($req,$i,objet).'"' ;
				$reqo = mysql_query($sqlo);
				$tab[$i]=mysql_result($reqo,0,type);
				
				if($tab[$i]=="acac")$tab[$i]="Armes de corps &agrave; corps";
				elseif($tab[$i]=="armestir"&&mysql_result($req,$i,objet)=="Chargeur")$tab[$i]="Matériel";
				elseif($tab[$i]=="modif")$tab[$i]="Matériel";
				elseif($tab[$i]=="armestir")$tab[$i]="Armes de tir";
				elseif($tab[$i]=="armesprim")$tab[$i]="Armes primitives";
				elseif($tab[$i]=="armesav")$tab[$i]="Armes avanc&eacute;es";
				elseif($tab[$i]=="alimentation")$tab[$i]="Nourriture";
				elseif($tab[$i]=="boissons")$tab[$i]="Boissons";
				elseif($tab[$i]=="jag")$tab[$i]="Jeux &agrave; gratter";
				elseif($tab[$i]=="oa")$tab[$i]="Objets avancés";
				elseif($tab[$i]=="objet")$tab[$i]="Objets courants";
				elseif($tab[$i]=="om")$tab[$i]="Objets High-Tech";
				elseif($tab[$i]=="soie"||$tab[$i]=="cristal"||$tab[$i]=="tissu")$tab[$i]="Vêtements";
				elseif($tab[$i]=="pad")$tab[$i]="Appartements modestes";
				elseif($tab[$i]=="gad"||$tab[$i]=="pmd")$tab[$i]="Appartements standard";
				elseif($tab[$i]=="vd"||$tab[$i]=="gmd")$tab[$i]="Appartements luxueux";
				elseif($tab[$i]=="armtu")$tab[$i]="Armes de tir";
				elseif($tab[$i]=="armcu")$tab[$i]="Armes de corps &agrave; corps";
				elseif($tab[$i]=="vetu")$tab[$i]="Vêtements";
				elseif($tab[$i]=="obju")$tab[$i]="Objets";
				elseif($tab[$i]=="deck")$tab[$i]="Decks";
				elseif($tab[$i]=="sac")$tab[$i]="Conteneurs";
				else;
				}
				
			sort($tab);
				
			for($i=0 ; $i != sizeof($tab) ; $i++)
				{
				if($i==0||$ancientype!=$tab[$i]) 
					{
					$ancientype=$tab[$i];
					print('<option value="'.htmlentities(str_replace(" ","_",$tab[$i])).'"');if($typeobj==str_replace(" ","_",$tab[$i]))print(' selected="selected"');print('>');
					print($tab[$i]);
					print('</option>
				');
					}
				}
				
			?>
		</select>
	</form>
</p>

<script type="text/javascript" language="JavaScript">
<!--
	actuel='Choisissez un secteur';
	actuelrues='';
	rues='';
	leclic='';
	<?php
	$texte = "";
	$texte .= "vartext1='<strong>Secteur 1</strong>';
	rues1='
	<form method=\"post\" id=\"choixrue\" action=\"engine=achat.php?id=".$id_obj."&sec=1\">
		Choisissez une rue :<br /><select style=\"width:200px;\" name=\"rue\"><option value=\"\">Peu importe</option>";
		$rues = ruesDuSecteur(1);
		foreach($rues as $newrue){
			$texte .= '<option value="'.$newrue.'">'.ucwords($newrue).'</option>';
		}
		$texte .= "</select>
		<input type=\"submit\" name=\"submit\" value=\"Valider\" />
	</form>';
	vartext2='<strong>Secteur 2</strong>';
	rues2='
	<form method=\"post\" id=\"choixrue\" action=\"engine=achat.php?id=".$id_obj."&sec=2\">
		Choisissez une rue :<br /><select style=\"width:200px;\" name=\"rue\"><option value=\"\">Peu importe</option>";
		$rues = ruesDuSecteur(2);
		foreach($rues as $newrue){
			$texte .= '<option value="'.$newrue.'">'.ucwords($newrue).'</option>';
		}
		$texte .= "</select>
		<input type=\"submit\" name=\"submit\" value=\"Valider\" />
	</form>';
	vartext3='<strong>Secteur 3</strong>';
	rues3='
	<form method=\"post\" id=\"choixrue\" action=\"engine=achat.php?id=".$id_obj."&sec=3\">
		Choisissez une rue :<br /><select style=\"width:200px;\" name=\"rue\"><option value=\"\">Peu importe</option>";
		$rues = ruesDuSecteur(3);
		foreach($rues as $newrue){
			$texte .= '<option value="'.$newrue.'">'.ucwords($newrue).'</option>';
		}
		$texte .= "</select>
		<input type=\"submit\" name=\"submit\" value=\"Valider\" />
	</form>';
	vartext4='<strong>Secteur 4</strong>';
	rues4='
	<form method=\"post\" id=\"choixrue\" action=\"engine=achat.php?id=".$id_obj."&sec=4\">
		Choisissez une rue :<br /><select style=\"width:200px;\" name=\"rue\"><option value=\"\">Peu importe</option>";
		$rues = ruesDuSecteur(4);
		foreach($rues as $newrue){
			$texte .= '<option value="'.$newrue.'">'.ucwords($newrue).'</option>';
		}
		$texte .= "</select>
		<input type=\"submit\" name=\"submit\" value=\"Valider\" />
	</form>';
	vartext5='<strong>Secteur 5</strong>';
	rues5='
	<form method=\"post\" id=\"choixrue\" action=\"engine=achat.php?id=".$id_obj."&sec=5\">
		Choisissez une rue :<br /><select style=\"width:200px;\" name=\"rue\"><option value=\"\">Peu importe</option>";
		$rues = ruesDuSecteur(5);
		foreach($rues as $newrue){
			$texte .= '<option value="'.$newrue.'">'.ucwords($newrue).'</option>';
		}
		$texte .= "</select>
		<input type=\"submit\" name=\"submit\" value=\"Valider\" />
	</form>';
	vartext6='<strong>Secteur 6</strong>';
	rues6='
	<form method=\"post\" id=\"choixrue\" action=\"engine=achat.php?id=".$id_obj."&sec=6\">
		Choisissez une rue :<br /><select style=\"width:200px;\" name=\"rue\"><option value=\"\">Peu importe</option>";
		$rues = ruesDuSecteur(6);
		foreach($rues as $newrue){
			$texte .= '<option value="'.$newrue.'">'.ucwords($newrue).'</option>';
		}
		$texte .= "</select>
		<input type=\"submit\" name=\"submit\" value=\"Valider\" />
	</form>';
	vartext7='<strong>Secteur 7</strong>';
	rues7='
	<form method=\"post\" id=\"choixrue\" action=\"engine=achat.php?id=".$id_obj."&sec=7\">
		Choisissez une rue :<br /><select style=\"width:200px;\" name=\"rue\"><option value=\"\">Peu importe</option>";
		$rues = ruesDuSecteur(7);
		foreach($rues as $newrue){
			$texte .= '<option value="'.$newrue.'">'.ucwords($newrue).'</option>';
		}
		$texte .= "</select>
		<input type=\"submit\" name=\"submit\" value=\"Valider\" />
	</form>';
	vartext8='<strong>Secteur 8</strong>';
	rues8='
	<form method=\"post\" id=\"choixrue\" action=\"engine=achat.php?id=".$id_obj."&sec=8\">
		Choisissez une rue :<br /><select style=\"width:200px;\" name=\"rue\"><option value=\"\">Peu importe</option>";
		$rues = ruesDuSecteur(8);
		foreach($rues as $newrue){
			$texte .= '<option value="'.$newrue.'">'.ucwords($newrue).'</option>';
		}
		$texte .= "</select>
		<input type=\"submit\" name=\"submit\" value=\"Valider\" />
	</form>';
	vartext9='<strong>Secteur 9</strong>';
	rues9='
	<form method=\"post\" id=\"choixrue\" action=\"engine=achat.php?id=".$id_obj."&sec=9\">
		Choisissez une rue :<br /><select style=\"width:200px;\" name=\"rue\"><option value=\"\">Peu importe</option>";
		$rues = ruesDuSecteur(9);
		foreach($rues as $newrue){
			$texte .= '<option value="'.$newrue.'">'.ucwords($newrue).'</option>';
		}
		$texte .= "</select>
		<input type=\"submit\" name=\"submit\" value=\"Valider\" />
	</form>';";
	
	echo str_replace("\t","",str_replace("\n","",$texte));
	?>
//-->
</script>
<p id="textelse" style="position:relative;top:-30px;">
	<strong>Choix du logement</strong><br />
	Choisissez le secteur et la rue dans laquelle vous souhaitez habiter.<br /><br />
	<table style="position:absolute;top:110px;left:50px;">
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
	<p style="position:absolute;top:120px;left:210px;color:#eee;">
		<span id="sectnum">Choisissez un secteur</span><br /><br />
		<span id="lechoixrue"></span>
	</p>
</p>

</div>
<?php if($chat=="oui") 	{ if ((date("H")>=21) || (date("H")<=7)) { include("inc_bas_n_c.php"); } else { include("inc_bas_j_c.php"); } } else {	if ((date("H")>=21) || (date("H")<=7)) { include("inc_bas_n.php"); } else { include("inc_bas_j.php"); }	} ?>
<?php include("inc_bas_de_page.php"); ?>
