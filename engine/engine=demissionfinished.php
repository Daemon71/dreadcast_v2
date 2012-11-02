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
			Demission
		</div>
		<b class="module4ie"><a href="engine=activite.php" class="module4"><img src="im_objets/icon_retour.gif" alt="Retour" />Retour</a></b>
</p>
	</div>
</div>
<div id="centre">
<p>

<?php  

$db = mysql_connect('localhost', 'CENSURE', 'CENSURE') or die("Erreur de connexion au serveur des messages.");
mysql_select_db('CENSURE',$db) or die("Erreur de connexion a la base de donnees des messages.");

$sql = 'SELECT id,action FROM principal_tbl WHERE pseudo= "'.$_SESSION['pseudo'].'"' ;
$req = mysql_query($sql);
$_SESSION['id'] = mysql_result($req,0,id); 
$_SESSION['action'] = mysql_result($req,0,action); 

$sql = 'SELECT type,entreprise,points FROM principal_tbl WHERE id= "'.$_SESSION['id'].'"' ;
$req = mysql_query($sql);
$_SESSION['poste'] = mysql_result($req,0,type); 
$_SESSION['entreprise'] = mysql_result($req,0,entreprise);
$_SESSION['points'] = mysql_result($req,0,points);
 
if((($_SESSION['entreprise']=="DI2RCO") || ($_SESSION['entreprise']=="Police") || ($_SESSION['entreprise']=="Prison") || ($_SESSION['entreprise']=="Services techniques de la ville") || ($_SESSION['entreprise']=="DOI") || ($_SESSION['entreprise']=="CIE") || ($_SESSION['entreprise']=="CIPE") || ($_SESSION['entreprise']=="Chambre des lois") || ($_SESSION['entreprise']=="DC Network")) && ($_SESSION['points']==999))
	{
	$sql = 'UPDATE principal_tbl SET entreprise= "Aucune" , salaire= "0" , type= "Aucun" , difficulte= "0" , points= "0" WHERE id= "'.$_SESSION['id'].'"' ;
	$req = mysql_query($sql);
	$sql = 'UPDATE `e_'.str_replace(" ","_",''.$_SESSION['entreprise'].'').'_tbl` SET nbreactuel= "0" WHERE poste= "'.$_SESSION['poste'].'"' ;
	$req = mysql_query($sql);
	print('<meta http-equiv="refresh" content="0 ; url=http://v2.dreadcast.net/ingame/engine=activite.php"> ');
	exit();
	}

if($_SESSION['action']=="travail")
	{
	$sql = 'SELECT type FROM `e_'.str_replace(" ","_",''.$_SESSION['entreprise'].'').'_tbl` WHERE poste= "'.$_SESSION['poste'].'"' ;
	$req = mysql_query($sql);
	$_SESSION['type'] = mysql_result($req,0,type);
	
	$sql1 = 'SELECT poste FROM `e_'.str_replace(" ","_",''.$_SESSION['entreprise'].'').'_tbl` WHERE type= "'.$_SESSION['type'].'"' ;
	$req1 = mysql_query($sql1);
	$res1 = mysql_num_rows($req1);
	for($r=0;$r!=$res1;$r++)
		{
		$poster = mysql_result($req1,$r,poste);
		$sql = 'SELECT id FROM principal_tbl WHERE entreprise= "'.$_SESSION['entreprise'].'" AND type= "'.$poster.'"' ;
		$req = mysql_query($sql);
		$res = mysql_num_rows($req);
		
		$restot = 0;
		for($i=0; $i != $res; $i++)
			{
			$idipa = mysql_result($req,$i,id); 
			$sql2 = 'SELECT action FROM principal_tbl WHERE id= "'.$idipa.'"' ;
			$req2 = mysql_query($sql2);
			$act = mysql_result($req2,0,action); 
			if($act=="travail")
				{
				$restot = $restot + 1;
				}
			}
		}
	
	$sql = 'SELECT id FROM principal_tbl WHERE entreprise= "'.$_SESSION['entreprise'].'" AND type= "chef"' ;
	$req = mysql_query($sql);
	$res = mysql_num_rows($req);
	
	for($i=0; $i != $res; $i++)
		{
		$idipa = mysql_result($req,$i,id); 
		$sql1 = 'SELECT action FROM principal_tbl WHERE id= "'.$idipa.'"' ;
		$req1 = mysql_query($sql1);
		$act = mysql_result($req1,0,action); 
		if($act=="travail")
			{
			$restot = $restot + 1;
			}
		}

	if($_SESSION['entreprise']!="Aucune")
		{
		$sql = 'SELECT type FROM `e_'.str_replace(" ","_",''.$_SESSION['entreprise'].'').'_tbl` WHERE poste= "'.$_SESSION['poste'].'"' ;
		$req = mysql_query($sql);
		$typeposte = mysql_result($req,0,type); 
		
		$sql = 'SELECT type,ouvert FROM entreprises_tbl WHERE nom= "'.$_SESSION['entreprise'].'"' ;
		$req = mysql_query($sql);
		$type = mysql_result($req,0,type); 
		}
	
	if(($_SESSION['action']=="travail") && ($restot==1))
		{
		if(($type=="agence immobiliaire") || ($type=="boutique armes") || ($type=="centre de recherche") || ($type=="boutiques spécialisee") || ($type=="ventes aux encheres") || ($type=="usine de production"))
			{
			if(($typeposte=="vendeur") || ($typeposte=="chef"))
				{
				$sql = 'UPDATE entreprises_tbl SET ouvert="non" WHERE nom= "'.$_SESSION['entreprise'].'"' ;
				$req = mysql_query($sql);
				}
			}
		elseif($type=="banque")
			{
			if(($typeposte=="banquier") || ($typeposte=="chef"))
				{
				$sql = 'UPDATE entreprises_tbl SET ouvert="non" WHERE nom= "'.$_SESSION['entreprise'].'"' ;
				$req = mysql_query($sql);
				}
			}
		elseif($type=="bar cafe")
			{
			if(($typeposte=="serveur") || ($typeposte=="chef"))
				{
				$sql = 'UPDATE entreprises_tbl SET ouvert="non" WHERE nom= "'.$_SESSION['entreprise'].'"' ;
				$req = mysql_query($sql);
				}
			}
		elseif($type=="hopital")
			{
			if(($typeposte=="medecin") || ($typeposte=="chef"))
				{
				$sql = 'UPDATE entreprises_tbl SET ouvert="non" WHERE nom= "'.$_SESSION['entreprise'].'"' ;
				$req = mysql_query($sql);
				}
			}
		}
	}

$sql = 'SELECT entreprise FROM principal_tbl WHERE id= "'.$_SESSION['id'].'"' ;
$req = mysql_query($sql);
$entreprise = mysql_result($req,0,entreprise); 
	
if($entreprise!="Aucune")
	{
	$sql = 'SELECT type FROM entreprises_tbl WHERE nom= "'.$entreprise.'"' ;
	$req = mysql_query($sql);
	$tyy = mysql_result($req,0,type); 

	$sql = 'SELECT type FROM principal_tbl WHERE id= "'.$_SESSION['id'].'"' ;
	$req = mysql_query($sql);
	$poste = mysql_result($req,0,type); 
	$sql = 'SELECT type FROM `e_'.str_replace(" ","_",''.$entreprise.'').'_tbl` WHERE poste="'.$poste.'"' ;
	$req = mysql_query($sql);
	$type = mysql_result($req,0,type);
	if($type=="chef")
		{
		$sql = 'SELECT nbreactuel FROM `e_'.str_replace(" ","_",''.$entreprise.'').'_tbl` WHERE type="chef"' ;
		$req = mysql_query($sql);
		$nombre = mysql_result($req,0,nbreactuel); 
		if($nombre==1)
			{
			$sql = 'UPDATE principal_tbl SET entreprise="Aucune" , salaire="0" , type="Aucun" , difficulte="0" , points="0" WHERE id= "'.$_SESSION['id'].'"' ;
			$req = mysql_query($sql);
			$sql = 'SELECT nbreactuel FROM `e_'.str_replace(" ","_",''.$entreprise.'').'_tbl` WHERE type= "directeur"' ;
			$req = mysql_query($sql);
			$res = mysql_num_rows($req);
			if($res!=0)
				{
				$nombred = mysql_result($req,0,nbreactuel);
				}
			else
				{
				$nombred = 0;
				}
			if($nombred!=0)
				{
				$sql = 'SELECT poste FROM `e_'.str_replace(" ","_",''.$_SESSION['entreprise'].'').'_tbl` WHERE type="directeur"' ;
				$req = mysql_query($sql);
				$postounet = mysql_result($req,0,poste);
				$sql = 'SELECT id FROM principal_tbl WHERE type="'.$postounet.'"' ;
				$req = mysql_query($sql);
				$ideo = mysql_result($req,0,id);
				$sql = 'SELECT pseudo FROM principal_tbl WHERE id="'.$ideo.'"' ;
				$req = mysql_query($sql);
				$nick = mysql_result($req,0,pseudo);
				$sql = 'INSERT INTO messages_tbl(id,auteur,cible,message,moment) VALUES("","'.$entreprise.'","'.$nick.'","Le PDG vous confie l\'entreprise. Vous pouvez acc&eacute;der au panneau de gestion &agrave; tout instant.","'.time().'")' ;
				$req = mysql_query($sql);
				$sql = 'UPDATE principal_tbl SET salaire="0" , type="'.$poste.'" , difficulte="0" , points="999" WHERE id= "'.$ideo.'"' ;
				$req = mysql_query($sql);
				$nombred = $nombred - 1;
				$sql = 'UPDATE `e_'.str_replace(" ","_",''.$entreprise.'').'_tbl` SET nbreactuel="'.$nombred.'" WHERE type= "directeur"' ;
				$req = mysql_query($sql);
				}
			elseif($nombred==0)
				{
				$sql1 = 'SELECT budget FROM entreprises_tbl WHERE nom= "'.$_SESSION['entreprise'].'"' ;
				$req1 = mysql_query($sql1);
				$budget = mysql_result($req1,0,budget);
				$sql1 = 'UPDATE principal_tbl SET credits = credits + "'.$budget.'" WHERE id= "'.$_SESSION['id'].'"' ;
				$req1 = mysql_query($sql1);
				
				$sql = 'SELECT id FROM `entreprises_tbl` WHERE nom="'.$entreprise.'"' ;
				$req = mysql_query($sql);
				supprimer_entreprise(mysql_result($req,0,id), false);
				}
			}
		elseif($nombre>1)
			{
			$nombre = $nombre - 1;
			$sql = 'UPDATE principal_tbl SET entreprise="Aucune" , salaire="0" , type="Aucun" , difficulte="0" , points="0" WHERE id= "'.$_SESSION['id'].'"' ;
			$req = mysql_query($sql);
			$sql = 'UPDATE `e_'.str_replace(" ","_",''.$entreprise.'').'_tbl` SET nbreactuel="'.$nombre.'" WHERE type= "chef"' ;
			$req = mysql_query($sql);
			}
		
		}
	else
		{
		$sql = 'SELECT nbreactuel FROM `e_'.str_replace(" ","_",''.$entreprise.'').'_tbl` WHERE poste= "'.$_SESSION['poste'].'"' ;
		$req = mysql_query($sql);
		$nombre = mysql_result($req,0,nbreactuel); 
		$nombre = $nombre - 1;
		
		$sql = 'UPDATE principal_tbl SET entreprise="Aucune" , salaire="0" , type="Aucun" , difficulte="0" , points="0" WHERE id= "'.$_SESSION['id'].'"' ;
		$req = mysql_query($sql);
		$sql = 'UPDATE `e_'.str_replace(" ","_",''.$entreprise.'').'_tbl` SET nbreactuel="'.$nombre.'" WHERE poste= "'.$_SESSION['poste'].'"' ;
		$req = mysql_query($sql);
		}
	
	if(($_SESSION['entreprise']=="Police") || ($_SESSION['entreprise']=="DI2RCO") || ($_SESSION['entreprise']=="Conseil Imp&eacute;rial"))
		{
		$sql1 = 'UPDATE principal_tbl SET Police="0" WHERE id= "'.$_SESSION['id'].'"' ;
		$req1 = mysql_query($sql1);
		}
	print('<strong><center>Votre d&eacute;mission &agrave; &eacute;t&eacute; accept&eacute;e.</center></strong>');
	}
else
	{
	print('<strong><center>Vous n\'avez pas de travail.</center></strong>');
	}

mysql_close($db);
?> 


</p>
</div>
<?php if($chat=="oui") 	{ if ((date("H")>=21) || (date("H")<=7)) { include("inc_bas_n_c.php"); } else { include("inc_bas_j_c.php"); } } else {	if ((date("H")>=21) || (date("H")<=7)) { include("inc_bas_n.php"); } else { include("inc_bas_j.php"); }	} ?>
<?php include("inc_bas_de_page.php"); ?>
