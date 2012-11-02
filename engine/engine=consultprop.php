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
			Services
		</div>
		<b class="module4ie"><a href="engine=services.php" class="module4"><img src="im_objets/icon_retour.gif" alt="Retour" />Retour</a></b>
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

$sql = 'SELECT bdd,type FROM `e_'.str_replace(" ","_",''.$_SESSION['entreprise'].'').'_tbl` WHERE poste= "'.$_SESSION['poste'].'"' ;
$req = mysql_query($sql);
$bdd = mysql_result($req,0,bdd); 
$typep = mysql_result($req,0,type); 

$sql = 'SELECT type,num,rue FROM entreprises_tbl WHERE nom= "'.$_SESSION['entreprise'].'"' ;
$req = mysql_query($sql);
$type = mysql_result($req,0,type); 
$rueent = mysql_result($req,0,rue); 
$nument = mysql_result($req,0,num); 

if($bdd=="") { print('<meta http-equiv="refresh" content="0 ; url=http://v2.dreadcast.net/ingame/engine.php"> '); exit(); } if(($type!="vente de services") && ($type!="banque") && ($type!="DOI") && ($type!="conseil") && ($type!="chambre") && ($type!="prison") && ($type!="police") && ($type!="di2rco")) { print('<meta http-equiv="refresh" content="0 ; url=http://v2.dreadcast.net/ingame/engine.php"> '); exit(); }

if((ucwords($_SESSION['rue'])!=ucwords($rueent)) || ($_SESSION['num']!=$nument))
	{
	print('<p align="center"><strong>Vous devez &ecirc;tre sur place pour pouvoir acc&eacute;der &agrave; cette rubrique.</strong></p>'); 
	$l = 1;
	}

if(($type=="chambre") && ($l!=1))
	{
	$sql = 'SELECT id,intitule FROM loisprop_tbl WHERE vote= "oui"' ;
	$req = mysql_query($sql);
	$res = mysql_num_rows($req);
	if($res>0)
		{
		print('<p align="center">Voici les propositions de Lois :</p>');
		print('<table width="480" border="1" align="center" cellpadding="0" cellspacing="0"><tr bgcolor="#B6B6B6"><th height="13" scope="col">Loi</th><th scope="col">Nombre de votes pour </th><th scope="col">Nombre de votes contre</th><th scope="col">Votre vote </th></tr>');
		for($f=0;$f!=$res;$f++)
			{
			$vid = mysql_result($req,$f,id);
			$vintitule = mysql_result($req,$f,intitule);
			$voteo = 0;
			$voten = 0;
			$sql1 = 'SELECT vote FROM loisvotes_tbl WHERE intitule= "'.$vintitule.'"' ;
			$req1 = mysql_query($sql1);
			$res1 = mysql_num_rows($req1);
			for($ff=0;$ff!=$res1;$ff++)
				{
				if(mysql_result($req1,$ff,vote)=="Oui")
					{
					$voteo = $voteo + 1;
					}
				elseif(mysql_result($req1,$ff,vote)=="Non")
					{
					$voten = $voten + 1;
					}
				}
			$sql2 = 'SELECT vote FROM loisvotes_tbl WHERE intitule= "'.$vintitule.'" AND membre= "'.$_SESSION['pseudo'].'"' ;
			$req2 = mysql_query($sql2);
			$res2 = mysql_num_rows($req2);
			if($res2>0)
				{
				$vvote = mysql_result($req2,0,vote);
				print('<tr>
				<td><div align="center"><a href="engine=consultpropconsult.php?'.$vid.'">'.$vintitule.'</a></div></td>
				<td><div align="center">'.$voteo.'</div></td>
				<td><div align="center">'.$voten.'</div></td>
				<td><div align="center">'.$vvote.'</div></td></tr>');
				}
			else
				{
				print('<tr>
				<td><div align="center"><a href="engine=consultpropconsult.php?'.$vid.'">'.$vintitule.'</a></div></td>
				<td><div align="center">'.$voteo.'</div></td>
				<td><div align="center">'.$voten.'</div></td>
				<td><div align="center">-</div></td></tr>');
				}
			}
		print('</table>');
		}
	else
		{
		print('<p align="center">Il n\'y a aucune proposition de Loi.</p>');
		}
	}

if(($type=="chambre") && ($l!=1) && ($typep=="chef"))
	{
	print('<br><hr>');
	$sql = 'SELECT id,intitule,membre FROM loisprop_tbl WHERE vote= "non"' ;
	$req = mysql_query($sql);
	$res = mysql_num_rows($req);
	if($res>0)
		{
		print('<p align="center">Voici les propositions de Lois non validées :</p>');
		print('<table width="480" border="1" align="center" cellpadding="0" cellspacing="0"><tr bgcolor="#B6B6B6"><th height="13" scope="col">Loi</th><th scope="col">Membre</th></tr>');
		for($f=0;$f!=$res;$f++)
			{
			$vid = mysql_result($req,$f,id);
			$vintitule = mysql_result($req,$f,intitule);
			$vmembre = mysql_result($req,$f,membre);
			print('<tr>
			<td><div align="center"><a href="engine=consultpropconsultprop.php?'.$vid.'">'.$vintitule.'</a></div></td>
			<td><div align="center">'.$vmembre.'</div></td></tr>');
			}
		print('</table>');
		}
	else
		{
		print('<p align="center">Il n\'y a aucune proposition de Loi non validée.</p>');
		}
	}

mysql_close($db);

?>


</p>
</div>
<?php if($chat=="oui") 	{ if ((date("H")>=21) || (date("H")<=7)) { include("inc_bas_n_c.php"); } else { include("inc_bas_j_c.php"); } } else {	if ((date("H")>=21) || (date("H")<=7)) { include("inc_bas_n.php"); } else { include("inc_bas_j.php"); }	} ?>
<?php include("inc_bas_de_page.php"); ?>
