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
	<div class="haut_aligngauche_haut">
		<p>
		<div class="titrepage">
			Services
		</div>
		<b class="module4ie"><a href="engine=services.php" class="module4"><img src="im_objets/icon_retour.gif" alt="Retour" />Retour</a></b>
<? if($_SERVER['QUERY_STRING']=="voir") { print('<div align="center">(<a href="engine=consultsubvimp.php">Voter</a>)
		</div>'); } elseif($resz==0) { print('<div align="center">(<a href="engine=consultsubvimp.php?voir">Accéder aux chiffres actuels</a>)
		</div>'); } ?>
		</p>
	</div>
</div>
<div id="centre">
<p>

<?php 

$db = mysql_connect('localhost', 'CENSURE', 'CENSURE') or die("Erreur de connexion au serveur des messages.");
mysql_select_db('CENSURE',$db) or die("Erreur de connexion a la base de donnees des messages.");

$sql = 'SELECT type,entreprise,points FROM principal_tbl WHERE id= "'.$_SESSION['id'].'"' ;
$req = mysql_query($sql);
$_SESSION['poste'] = mysql_result($req,0,type); 
$_SESSION['entreprise'] = mysql_result($req,0,entreprise); 
$_SESSION['points'] = mysql_result($req,0,points); 

$sql = 'SELECT bdd FROM `e_'.str_replace(" ","_",''.$_SESSION['entreprise'].'').'_tbl` WHERE poste= "'.$_SESSION['poste'].'"' ;
$req = mysql_query($sql);
$bdd = mysql_result($req,0,bdd); 

$sql = 'SELECT type,num,rue FROM entreprises_tbl WHERE nom= "'.$_SESSION['entreprise'].'"' ;
$req = mysql_query($sql);
$type = mysql_result($req,0,type); 
$rueent = mysql_result($req,0,rue); 
$nument = mysql_result($req,0,num); 

$sqlz = 'SELECT id FROM finance_tbl WHERE membre= "'.$_SESSION['pseudo'].'"' ;
$reqz = mysql_query($sqlz);
$resz = mysql_num_rows($reqz);

if($bdd=="") { print('<meta http-equiv="refresh" content="0 ; url=http://v2.dreadcast.net/ingame/engine.php"> '); exit(); } if(($type!="vente de services") && ($type!="banque") && ($type!="DOI") && ($type!="conseil") && ($type!="chambre") && ($type!="prison") && ($type!="police") && ($type!="di2rco")) { print('<meta http-equiv="refresh" content="0 ; url=http://v2.dreadcast.net/ingame/engine.php"> '); exit(); }

if((ucwords($_SESSION['rue'])!=ucwords($rueent)) || ($_SESSION['num']!=$nument))
	{
	print('<p align="center"><strong>Vous devez &ecirc;tre sur place pour pouvoir acc&eacute;der &agrave; cette rubrique.</strong></p>'); 
	$l = 1;
	}

if(($type=="DOI") && ($l!=1))
	{
	if($_SERVER['QUERY_STRING']=="voir")
		{
		print('<p align="center">Vous avez déjà voté les Budgets Impériaux.<br>Voici les moyennes actuelles :</p>');
		$sql = 'SELECT prison,proprete,cipe,cie,chambre,di2rco,police,dcn FROM finance_tbl' ;
		$req = mysql_query($sql);
		$res = mysql_num_rows($req);
		$prison = 0;
		$proprete = 0;
		$cipe = 0;
		$cie = 0;
		$chambre = 0;
		$di2rco = 0;
		$police = 0;
		$dcn = 0;
		for($f=0;$f!=$res;$f++)
			{
			$prison = $prison + mysql_result($req,$f,prison);
			$proprete = $proprete + mysql_result($req,$f,proprete);
			$cipe = $cipe + mysql_result($req,$f,cipe);
			$cie = $cie + mysql_result($req,$f,cie);
			$chambre = $chambre + mysql_result($req,$f,chambre);
			$di2rco = $di2rco + mysql_result($req,$f,di2rco);
			$police = $police + mysql_result($req,$f,police);
			$dcn = $dcn + mysql_result($req,$f,dcn);
			}
		if($res!=0)
			{
			$prison = ceil($prison / $res);
			$proprete = ceil($proprete / $res);
			$cipe = ceil($cipe / $res);
			$cie = ceil($cie / $res);
			$chambre = ceil($chambre / $res);
			$di2rco = ceil($di2rco / $res);
			$police = ceil($police / $res);
			$dcn = ceil($dcn / $res);
			}
		print('<table width="480" border="1" align="center" cellpadding="0" cellspacing="0">
          <tr bgcolor="#B6B6B6">
            <th height="13" scope="col">Organisation Imp&eacute;riale</th>
            <th scope="col">Nombre de votes </th>
            <th scope="col">Budgets actuels</th>
            <th scope="col">Votre vote </th>
            </tr>
          <tr>
            <td><div align="center"><a href="engine=consultsubvimpconsult.php?proprete">Propret&eacute; de la ville</a></div></td>
            <td><div align="center">'.$res.'</div></td>
            <td><div align="center">'.$proprete.'</div></td>
            <td><div align="center">/</div></td>
            </tr>
          <tr>
            <td><div align="center"><a href="engine=consultsubvimpconsult.php?prison">Prisons de la ville</a> </div></td>
            <td><div align="center">'.$res.'</div></td>
            <td><div align="center">'.$prison.'</div></td>
            <td><div align="center">/</div></td>
            </tr>
          <tr>
            <td><div align="center"><a href="engine=consultsubvimpconsult.php?CIPE">Centre d\'Information Pour l\'EmpLoi (CIPE)</a> </div></td>
            <td><div align="center">'.$res.'</div></td>
            <td><div align="center">'.$cipe.'</div></td>
            <td><div align="center">/</div></td>
            </tr>
          <tr>
            <td><div align="center"><a href="engine=consultsubvimpconsult.php?CIE">Centre Imp&eacute;rial d\'Enseignement (CIE)</a> </div></td>
            <td><div align="center">'.$res.'</div></td>
            <td><div align="center">'.$cie.'</div></td>
            <td><div align="center">/</div></td>
            </tr>
          <tr>
            <td><div align="center"><a href="engine=consultsubvimpconsult.php?police">Police</div></td>
            <td><div align="center">'.$res.'</div></td>
            <td><div align="center">'.$police.'</div></td>
            <td><div align="center">/</div></td>
            </tr>
          <tr>
            <td><div align="center"><a href="engine=consultsubvimpconsult.php?chambre">Chambre des Lois</div></td>
            <td><div align="center">'.$res.'</div></td>
            <td><div align="center">'.$chambre.'</div></td>
            <td><div align="center">/</div></td>
            </tr>
          <tr>
            <td><div align="center"><a href="engine=consultsubvimpconsult.php?di2rco">D&eacute;partement Imp&eacute;rial de Recherche et R&eacute;pression du Crime Organis&eacute; (DI2RCO)</a> </div></td>
            <td><div align="center">'.$res.'</div></td>
            <td><div align="center">'.$di2rco.'</div></td>
            <td><div align="center">/</div></td>
            </tr>
			<tr>
            <td><div align="center"><a href="engine=consultsubvimpconsult.php?dcn">DreadCast Network (DCN)</a> </div></td>
            <td><div align="center">'.$res.'</div></td>
            <td><div align="center">'.$dcn.'</div></td>
            <td><div align="center">/</div></td>
            </tr>
        </table>');
		}
	elseif($resz==0)
		{
		print('<p align="center">Vous n\'avez pas encore voté les Budgets Impériaux.<br>N\'oubliez pas que le total des budgets doit faire exactement 100.000 Crédits.</p>');
		print('<form name="form1" method="post" action="engine=consultsubvimpterm.php">
		<div align="center"><p><strong>Propret&eacute; de la ville :</strong> <input name="proprete" type="text" id="proprete" size="5" maxlength="6">Cr&eacute;dits	<br>
		<strong>Centre d\'Information Pour l\'Emploi  (CIPE):</strong> <input name="cipe" type="text" id="cipe" size="5" maxlength="6">Cr&eacute;dits<br>
		<strong>Centre Imp&eacute;rial d\'Enseignement (CIE):</strong> <input name="cie" type="text" id="cie" size="5" maxlength="6">Cr&eacute;dits<br>
		<strong>Police :</strong> <input name="police" type="text" id="police" size="5" maxlength="6">Cr&eacute;dits<br>
		<strong>D&eacute;partement Imp&eacute;rial de Recherche et R&eacute;pression du Crime Organis&eacute; (DI2RCO):</strong> <input name="di2rco" type="text" id="di2rco" size="5" maxlength="6">Cr&eacute;dits<br>
		<strong>Chambre des Lois  :</strong> <input name="chambre" type="text" id="chambre" size="5" maxlength="6">Cr&eacute;dits <br>
		<strong>Prison de la ville :</strong> <input name="prison" type="text" id="prison" size="5" maxlength="6">Cr&eacute;dits <br />
		<strong>DreadCast Network :</strong> <input name="dcn" type="text" id="dcn" size="5" maxlength="6">Cr&eacute;dits</p><p> <input type="submit" name="Submit" value="Valider les Bugdets"></p></div></form>');
		}
	else
		{
		print('<p align="center">Vous avez déjà voté les Budgets Impériaux.<br>Voici les moyennes actuelles :</p>');
		$sql = 'SELECT prison,proprete,cipe,cie,chambre,di2rco,police,dcn FROM finance_tbl WHERE membre= "'.$_SESSION['pseudo'].'"' ;
		$req = mysql_query($sql);
		$vprison = mysql_result($req,0,prison);
		$vproprete = mysql_result($req,0,proprete);
		$vcipe = mysql_result($req,0,cipe);
		$vcie = mysql_result($req,0,cie);
		$vchambre = mysql_result($req,0,chambre);
		$vdi2rco = mysql_result($req,0,di2rco);
		$vpolice = mysql_result($req,0,police);
		$vdcn = mysql_result($req,0,dcn);
		$sql = 'SELECT prison,proprete,cipe,cie,chambre,di2rco,police,dcn FROM finance_tbl' ;
		$req = mysql_query($sql);
		$res = mysql_num_rows($req);
		$prison = 0;
		$proprete = 0;
		$cipe = 0;
		$cie = 0;
		$chambre = 0;
		$di2rco = 0;
		$police = 0;
		$dcn = 0;
		for($f=0;$f!=$res;$f++)
			{
			$prison = $prison + mysql_result($req,$f,prison);
			$proprete = $proprete + mysql_result($req,$f,proprete);
			$cipe = $cipe + mysql_result($req,$f,cipe);
			$cie = $cie + mysql_result($req,$f,cie);
			$chambre = $chambre + mysql_result($req,$f,chambre);
			$di2rco = $di2rco + mysql_result($req,$f,di2rco);
			$police = $police + mysql_result($req,$f,police);
			$dcn = $dcn + mysql_result($req,$f,dcn);
			}
		if($res!=0)
			{
			$prison = ceil($prison / $res);
			$proprete = ceil($proprete / $res);
			$cipe = ceil($cipe / $res);
			$cie = ceil($cie / $res);
			$chambre = ceil($chambre / $res);
			$di2rco = ceil($di2rco / $res);
			$police = ceil($police / $res);
			$dcn = ceil($dcn / $res);
			}
		print('<table width="480" border="1" align="center" cellpadding="0" cellspacing="0">
          <tr bgcolor="#B6B6B6">
            <th height="13" scope="col">Organisation Imp&eacute;riale</th>
            <th scope="col">Nombre de votes </th>
            <th scope="col">Budgets actuels</th>
            <th scope="col">Votre vote </th>
            </tr>
          <tr>
            <td><div align="center"><a href="engine=consultsubvimpconsult.php?proprete">Propret&eacute; de la ville</a></div></td>
            <td><div align="center">'.$res.'</div></td>
            <td><div align="center">'.$proprete.'</div></td>
            <td><div align="center">'.$vproprete.'</div></td>
            </tr>
          <tr>
            <td><div align="center"><a href="engine=consultsubvimpconsult.php?prison">Prisons de la ville</a> </div></td>
            <td><div align="center">'.$res.'</div></td>
            <td><div align="center">'.$prison.'</div></td>
            <td><div align="center">'.$vprison.'</div></td>
            </tr>
          <tr>
            <td><div align="center"><a href="engine=consultsubvimpconsult.php?CIPE">Centre d\'Information Pour l\'EmpLoi (CIPE)</a> </div></td>
            <td><div align="center">'.$res.'</div></td>
            <td><div align="center">'.$cipe.'</div></td>
            <td><div align="center">'.$vcipe.'</div></td>
            </tr>
          <tr>
            <td><div align="center"><a href="engine=consultsubvimpconsult.php?CIE">Centre Imp&eacute;rial d\'Enseignement (CIE)</a> </div></td>
            <td><div align="center">'.$res.'</div></td>
            <td><div align="center">'.$cie.'</div></td>
            <td><div align="center">'.$vcie.'</div></td>
            </tr>
          <tr>
            <td><div align="center"><a href="engine=consultsubvimpconsult.php?police">Police</div></td>
            <td><div align="center">'.$res.'</div></td>
            <td><div align="center">'.$police.'</div></td>
            <td><div align="center">'.$vpolice.'</div></td>
            </tr>
          <tr>
            <td><div align="center"><a href="engine=consultsubvimpconsult.php?chambre">Chambre des Lois</div></td>
            <td><div align="center">'.$res.'</div></td>
            <td><div align="center">'.$chambre.'</div></td>
            <td><div align="center">'.$vchambre.'</div></td>
            </tr>
          <tr>
            <td><div align="center"><a href="engine=consultsubvimpconsult.php?di2rco">D&eacute;partement Imp&eacute;rial de Recherche et R&eacute;pression du Crime Organis&eacute; (DI2RCO)</a> </div></td>
            <td><div align="center">'.$res.'</div></td>
            <td><div align="center">'.$di2rco.'</div></td>
            <td><div align="center">'.$vdi2rco.'</div></td>
            </tr>
		  <tr>
            <td><div align="center"><a href="engine=consultsubvimpconsult.php?dcn">DreadCast Network (DCN)</a> </div></td>
            <td><div align="center">'.$res.'</div></td>
            <td><div align="center">'.$dcn.'</div></td>
            <td><div align="center">'.$vdcn.'</div></td>
            </tr>
        </table>');
		}
	}


mysql_close($db);

?>

</p>
</div>
<?php if($chat=="oui") 	{ if ((date("H")>=21) || (date("H")<=7)) { include("inc_bas_n_c.php"); } else { include("inc_bas_j_c.php"); } } else {	if ((date("H")>=21) || (date("H")<=7)) { include("inc_bas_n.php"); } else { include("inc_bas_j.php"); }	} ?>
<?php include("inc_bas_de_page.php"); ?>
