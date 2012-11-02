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

if ((date("H")>=21) || (date("H")<=7)) { include("inc_haut_n.php"); } else { include("inc_haut_j.php");}
$db = mysql_connect('localhost', 'CENSURE', 'CENSURE') or die("Erreur de connexion au serveur des messages.");
mysql_select_db('CENSURE',$db) or die("Erreur de connexion a la base de donnees des messages.");

$sql = 'SELECT faim,soif FROM principal_tbl WHERE id= "'.$_SESSION['id'].'"' ;
$req = mysql_query($sql);
$_SESSION['faim'] = mysql_result($req,0,faim);
$_SESSION['soif'] = mysql_result($req,0,soif);

$sql = 'SELECT nom,type FROM entreprises_tbl WHERE rue= "'.$_SESSION['lieu'].'" AND num= "'.$_SESSION['num'].'"' ;
$req = mysql_query($sql);
$type = mysql_result($req,0,type);
$noment = mysql_result($req,0,nom);

if(($type!="boutique armes") && ($type!="hopital") && ($type!="centre de recherche") && ($type!="boutique spécialisee") && ($type!="agence immobiliaire") && ($type!="bar cafe") && ($type!="ventes aux encheres"))
	{
	print('<meta http-equiv="refresh" content="0 ; url=http://v2.dreadcast.net/ingame/engine.php"> ');
	exit();
	}

if($_GET['id']!="")
	{
	$sql = 'SELECT nom,type,puissance,ecart FROM objets_tbl WHERE id= "'.$_GET['id'].'"' ;
	$req = mysql_query($sql);
	$typeo = mysql_result($req,0,type);
	$puissance = mysql_result($req,0,puissance);
	$ecart = mysql_result($req,0,ecart);
	$_SESSION['objet'] = mysql_result($req,0,nom);
	}
else
	{
	$sql = 'SELECT nom,type,puissance,ecart FROM objets_tbl WHERE nom= "'.$_SERVER['QUERY_STRING'].'"' ;
	$req = mysql_query($sql);
	$typeo = mysql_result($req,0,type);
	$puissance = mysql_result($req,0,puissance);
	$ecart = mysql_result($req,0,ecart);
	$_SESSION['objet'] = mysql_result($req,0,nom);
	}

$_SESSION['objet'] = str_replace("&eacute;","é",''.$_SESSION['objet'].'');

$sql = 'SELECT nombre,pvente FROM stocks_tbl WHERE objet= "'.$_SESSION['objet'].'" AND entreprise= "'.$noment.'"' ;
$req = mysql_query($sql);
$res = mysql_num_rows($req);
if($res==0)
	{
	print('<meta http-equiv="refresh" content="0 ; url=http://v2.dreadcast.net/ingame/engine=boutique.php"> ');
	exit();
	}

$nombre = mysql_result($req,0,nombre);
if($nombre<1)
	{
	print('<meta http-equiv="refresh" content="0 ; url=http://v2.dreadcast.net/ingame/engine=boutique.php"> ');
	exit();
	}
$pvente = mysql_result($req,0,pvente);

$sql = 'SELECT budget,benefices FROM entreprises_tbl WHERE nom= "'.$noment.'"' ;
$req = mysql_query($sql);
$budget = mysql_result($req,0,budget);
$benef = mysql_result($req,0,benefices);

$sql = 'SELECT * FROM principal_tbl WHERE id= "'.$_SESSION['id'].'"' ;
$req = mysql_query($sql);
$_SESSION['credits'] = mysql_result($req,0,credits);
$_SESSION['case1'] = mysql_result($req,0,case1);
$_SESSION['case2'] = mysql_result($req,0,case2);
$_SESSION['case3'] = mysql_result($req,0,case3);
$_SESSION['case4'] = mysql_result($req,0,case4);
$_SESSION['case5'] = mysql_result($req,0,case5);
$_SESSION['case6'] = mysql_result($req,0,case6);

if($_SESSION['credits']>=$pvente)
	{
	if(($typeo=="objet") || ($typeo=="oa") || ($typeo=="om") || ($typeo=="armestir") || ($typeo=="tissu") || ($typeo=="soie") || ($typeo=="cristal") || ($typeo=="armesprim") || ($typeo=="acac") || ($typeo=="armesav") || ($typeo=="pharmacie") || ($typeo=="jag") || ($typeo=="modif") || ($typeo=="armtu") || ($typeo=="armcu") || ($typeo=="vetu") || ($typeo=="obju") || ($typeo=="ouu") || ($typeo=="deck") || ($typeo=="sac"))
		{
		if(($_SESSION['case1']=="Vide") || ($_SESSION['case2']=="Vide") || ($_SESSION['case3']=="Vide") || ($_SESSION['case4']=="Vide") || ($_SESSION['case5']=="Vide") || ($_SESSION['case6']=="Vide"))
			{
			for($i=1; $i != 7; $i++)
				{
				if(($_SESSION['case'.$i.'']=="Vide") && ($l!=1))
					{
					if($typeo=="sac")
						{
						//générer un id inexistant
						$nbre = 1;
						while($nbre==1)
							{
							$gen = "";
							for($p=0;$p!=10;$p++)
								{
								$rand = rand(0,15);
								if($rand==10){$rand="A";}
								elseif($rand==11){$rand="B";}
								elseif($rand==12){$rand="C";}
								elseif($rand==13){$rand="D";}
								elseif($rand==14){$rand="E";}
								elseif($rand==15){$rand="F";}
								$gen = $gen . $rand;
								}
							$sql2 = 'SELECT id FROM objets_tbl WHERE nom= "'.$_SESSION['objet'].'-'.$gen.'"';
							$req2 = mysql_query($sql2);
							$nbre = mysql_num_rows($req2);
							}
						$sql = 'SELECT puissance,image,ecart FROM objets_tbl WHERE nom= "'.$_SESSION['objet'].'"' ;
						$req = mysql_query($sql);
						if(mysql_result($req,0,ecart)==1)
							{
							$code = rand(0,9).rand(0,9).rand(0,9);
							$sql2 = 'INSERT INTO messages_tbl(id,auteur,cible,message,objet,moment,nouveau) VALUES("","Dreadcast","'.$_SESSION['pseudo'].'","Voici le code du Coffre n°'.$gen.' : '.$code.'","Coffre  '.$gen.'","'.time().'","oui")';
							$req2 = mysql_query($sql2);
							}
						else $code = 0;
						$sql = 'INSERT INTO objets_tbl(id,nom,puissance,ecart,image,url,infos,prix,type,distance) VALUES("","'.$_SESSION['objet'].'-'.$gen.'","'.mysql_result($req,0,puissance).'","'.mysql_result($req,0,ecart).'","'.mysql_result($req,0,image).'","engine=sac.php?'.$_SESSION['objet'].'-'.$gen.'","Ceci est un conteneur. Il permet de contenir '.mysql_result($req,0,puissance).' objet(s).","0","sac","'.$code.'")';
						$req = mysql_query($sql);
						$sql = 'UPDATE principal_tbl SET case'.$i.'= "'.$_SESSION['objet'].'-'.$gen.'" WHERE id= "'.$_SESSION['id'].'"' ;
						$req = mysql_query($sql);
						}
					elseif(($typeo=="armestir" || $typeo=="armesav" || $typeo=="armtu") && ($_SESSION['objet']!="Chargeur"))
						{
						//générer un id inexistant
						$nbre = 1;
						while($nbre==1)
							{
							$gen = "";
							for($p=0;$p!=10;$p++)
								{
								$rand = rand(0,15);
								if($rand==10){$rand="A";}
								elseif($rand==11){$rand="B";}
								elseif($rand==12){$rand="C";}
								elseif($rand==13){$rand="D";}
								elseif($rand==14){$rand="E";}
								elseif($rand==15){$rand="F";}
								$gen = $gen . $rand;
								}
							$sql2 = 'SELECT id FROM armes_tbl WHERE idarme= "-'.$gen.'"';
							$req2 = mysql_query($sql2);
							$nbre = mysql_num_rows($req2);
							}
						
						//ajouter l'id dans armes_tbl
						$sql2 = 'INSERT INTO armes_tbl(id,idarme) VALUES("","-'.$gen.'")';
						$req2 = mysql_query($sql2);
										
						//récupérer les infos de l'item
						$sql2 = 'SELECT * FROM objets_tbl WHERE nom= "'.$_SESSION['objet'].'"' ;
						$req2 = mysql_query($sql2);
				
						//ajouter l'item dans objets_tbl
						$sql2 = 'INSERT INTO objets_tbl(id,nom,image,url,type,prod,puissance,ecart,distance,modes) VALUES("","'.$_SESSION['objet'].'-'.$gen.'","'.mysql_result($req2,0,image).'","engine=arme.php?arme='.$_SESSION['objet'].'&id=-'.$gen.'","'.mysql_result($req2,0,type).'","0","'.mysql_result($req2,0,puissance).'","'.mysql_result($req2,0,ecart).'","'.mysql_result($req2,0,distance).'","'.mysql_result($req2,0,modes).'")';
						$req2 = mysql_query($sql2);

						$sql = 'UPDATE principal_tbl SET case'.$i.'= "'.$_SESSION['objet'].'-'.$gen.'" WHERE id= "'.$_SESSION['id'].'"' ;
						$req = mysql_query($sql);
						}
					else
						{
						$sql = 'UPDATE principal_tbl SET case'.$i.'= "'.$_SESSION['objet'].'" WHERE id= "'.$_SESSION['id'].'"' ;
						$req = mysql_query($sql);
						}
					$_SESSION['credits'] = $_SESSION['credits'] - $pvente;
					$nombre = $nombre - 1;
					$budget = $budget + $pvente;
					$benef = $benef + $pvente;
					$sql = 'UPDATE entreprises_tbl SET budget= "'.$budget.'" , benefices= "'.$benef.'" WHERE nom= "'.$noment.'"' ;
					$req = mysql_query($sql);
					$sql = 'UPDATE principal_tbl SET credits= "'.$_SESSION['credits'].'" WHERE id= "'.$_SESSION['id'].'"' ;
					$req = mysql_query($sql);
					
					enregistre($_SESSION['pseudo'],"acc_objets_achetes","+1");
					enregistre($_SESSION['pseudo'],"acc_credits_depenses","+".$pvente);
					
					$sql = 'UPDATE stocks_tbl SET nombre= "'.$nombre.'" WHERE entreprise= "'.$noment.'" AND objet= "'.$_SESSION['objet'].'"' ;
					$req = mysql_query($sql);
					$sql = 'INSERT INTO achats_tbl(id,vendeur,acheteur,objet,moment,prix) VALUES("","'.$noment.'","'.$_SESSION['pseudo'].'","'.$_SESSION['objet'].'","'.time().'","'.$pvente.'")' ;
					$req = mysql_query($sql);
					$achok = 1;
					$l = 1;
					if($_SESSION['objet']=="Cash")
						{
						$sql = 'SELECT valeur FROM donnees_tbl WHERE objet= "entreeCASH"';
						$req = mysql_query($sql);
						$valeur = mysql_result($req,0,valeur)+$pvente;
						$sql = 'UPDATE donnees_tbl SET valeur= "'.$valeur.'" WHERE objet= "entreeCASH"';
						$req = mysql_query($sql);
						}
					elseif($_SESSION['objet']=="Shanghai")
						{
						$sql = 'SELECT valeur FROM donnees_tbl WHERE objet= "entreeSHANGHAI"';
						$req = mysql_query($sql);
						$valeur = mysql_result($req,0,valeur)+$pvente;
						$sql = 'UPDATE donnees_tbl SET valeur= "'.$valeur.'" WHERE objet= "entreeSHANGHAI"';
						$req = mysql_query($sql);
						}
					}
				}
			}
		}
	if($typeo=="boissons")
		{
		if($ecart>0)
			{
			enregistre($_SESSION['pseudo'],"acc_alcool_achetes","+1");
			if($_SESSION['drogue']==0)
				{
				$_SESSION['fatigue'] = $_SESSION['fatigue'] + $puissance;
				if($_SESSION['fatigue']>$_SESSION['fatiguemax'])
					{
					$_SESSION['fatigue'] = $_SESSION['fatiguemax'];
					}
				}
			elseif($_SESSION['drogue']>0)
				{
				$_SESSION['fatigue'] = $_SESSION['fatigue'] + $puissance;
				if($_SESSION['fatigue']>drogue($_SESSION['pseudo'],$_SESSION['fatiguemax']))
					{
					$_SESSION['fatigue'] = drogue($_SESSION['pseudo'],$_SESSION['fatiguemax']);
					}
				}
			$sql = 'UPDATE principal_tbl SET alcool=alcool+'.$ecart.' WHERE id= "'.$_SESSION['id'].'"' ;
			$req = mysql_query($sql);
			}
		elseif($_SESSION['soif']<100)
			{
			enregistre($_SESSION['pseudo'],"acc_boissons_achetes","+1");
			if($_SESSION['drogue']==0)
				{
				$_SESSION['fatigue'] = $_SESSION['fatigue'] + $puissance;
				if($_SESSION['fatigue']>$_SESSION['fatiguemax'])
					{
					$_SESSION['fatigue'] = $_SESSION['fatiguemax'];
					}
				}
			elseif($_SESSION['drogue']>0)
				{
				$_SESSION['fatigue'] = $_SESSION['fatigue'] + $puissance;
				if($_SESSION['fatigue']>drogue($_SESSION['pseudo'],$_SESSION['fatiguemax']))
					{
					$_SESSION['fatigue'] = drogue($_SESSION['pseudo'],$_SESSION['fatiguemax']);
					}
				}
			}
		$_SESSION['soif'] = $_SESSION['soif'] + $puissance;
		if(possede_talent("Pilier de bar",$_SESSION['pseudo']))
            {
            $_SESSION['soif'] = 100;
            $_SESSION['faim'] = 100;
            $_SESSION['fatigue'] = $_SESSION['fatiguemax'];
            $_SESSION['sante'] = $_SESSION['santemax'];
            $sql = 'UPDATE principal_tbl SET faim= "100" , sante= "'.$_SESSION['sante'].'" WHERE id= "'.$_SESSION['id'].'"' ;
			$req = mysql_query($sql);
			}
		if($_SESSION['soif']>100) $_SESSION['soif'] = 100;
		$_SESSION['credits'] = $_SESSION['credits'] - $pvente;
		$nombre = $nombre - 1;
		$budget = $budget + $pvente;
		$benef = $benef + $pvente;
		$sql = 'UPDATE entreprises_tbl SET budget= "'.$budget.'" , benefices= "'.$benef.'" WHERE nom= "'.$noment.'"' ;
		$req = mysql_query($sql);
		$sql = 'UPDATE principal_tbl SET soif= "'.$_SESSION['soif'].'" , credits= "'.$_SESSION['credits'].'" WHERE id= "'.$_SESSION['id'].'"' ;
		$req = mysql_query($sql);
		
		enregistre($_SESSION['pseudo'],"acc_credits_depenses","+".$pvente);
		
		$sql = 'UPDATE stocks_tbl SET nombre= "'.$nombre.'" WHERE entreprise= "'.$noment.'" AND objet= "'.$_SESSION['objet'].'"' ;
		$req = mysql_query($sql);
		$sql = 'INSERT INTO achats_tbl(id,vendeur,acheteur,objet,moment,prix) VALUES("","'.$noment.'","'.$_SESSION['pseudo'].'","'.$_SESSION['objet'].'","'.time().'","'.$pvente.'")' ;
		$req = mysql_query($sql);
		$sql = 'UPDATE principal_tbl SET fatigue= "'.$_SESSION['fatigue'].'" WHERE id= "'.$_SESSION['id'].'"' ;
		$req = mysql_query($sql);
		$achok = 2;
		}
	if($typeo=="alimentation")
		{
		if($_SESSION['faim']<100)
			{
			$_SESSION['faim'] = $_SESSION['faim'] + $puissance;
			if(possede_talent("Pilier de bar",$_SESSION['pseudo']))
                {
                $_SESSION['soif'] = 100;
                $_SESSION['faim'] = 100;
                $_SESSION['fatigue'] = $_SESSION['fatiguemax'];
                $_SESSION['sante'] = $_SESSION['santemax'];
                $sql = 'UPDATE principal_tbl SET soif= "100" , sante= "'.$_SESSION['sante'].'" WHERE id= "'.$_SESSION['id'].'"' ;
				$req = mysql_query($sql);
				}
			if($_SESSION['faim']>100)
				{
				$_SESSION['faim'] = 100;
				}
			if($_SESSION['drogue']==0)
				{
				$_SESSION['fatigue'] = $_SESSION['fatigue'] + $puissance;
				if($_SESSION['fatigue']>$_SESSION['fatiguemax'])
					{
					$_SESSION['fatigue'] = $_SESSION['fatiguemax'];
					}
				$_SESSION['credits'] = $_SESSION['credits'] - $pvente;
				$nombre = $nombre - 1;
				$budget = $budget + $pvente;
				$benef = $benef + $pvente;
				$sql = 'UPDATE entreprises_tbl SET budget= "'.$budget.'" , benefices= "'.$benef.'" WHERE nom= "'.$noment.'"' ;
				$req = mysql_query($sql);
				$sql = 'UPDATE principal_tbl SET faim= "'.$_SESSION['faim'].'" , credits= "'.$_SESSION['credits'].'" WHERE id= "'.$_SESSION['id'].'"' ;
				$req = mysql_query($sql);
				
				enregistre($_SESSION['pseudo'],"acc_nourriture_achete","+1");
				enregistre($_SESSION['pseudo'],"acc_credits_depenses","+".$pvente);
				
				$sql = 'UPDATE stocks_tbl SET nombre= "'.$nombre.'" WHERE entreprise= "'.$noment.'" AND objet= "'.$_SESSION['objet'].'"' ;
				$req = mysql_query($sql);
				$sql = 'INSERT INTO achats_tbl(id,vendeur,acheteur,objet,moment,prix) VALUES("","'.$noment.'","'.$_SESSION['pseudo'].'","'.$_SESSION['objet'].'","'.time().'","'.$pvente.'")' ;
				$req = mysql_query($sql);
				$sql = 'UPDATE principal_tbl SET fatigue= "'.$_SESSION['fatigue'].'" WHERE id= "'.$_SESSION['id'].'"' ;
				$req = mysql_query($sql);
				$achok = 2;
				}
			elseif($_SESSION['drogue']>0)
				{
				$_SESSION['fatigue'] = $_SESSION['fatigue'] + $puissance;
				if($_SESSION['fatigue']>drogue($_SESSION['pseudo'],$_SESSION['fatiguemax']))
					{
					$_SESSION['fatigue'] = drogue($_SESSION['pseudo'],$_SESSION['fatiguemax']);
					}
				$_SESSION['credits'] = $_SESSION['credits'] - $pvente;
				$nombre = $nombre - 1;
				$budget = $budget + $pvente;
				$benef = $benef + $pvente;
				$sql = 'UPDATE entreprises_tbl SET budget= "'.$budget.'" , benefices= "'.$benef.'" WHERE nom= "'.$noment.'"' ;
				$req = mysql_query($sql);
				$sql = 'UPDATE principal_tbl SET faim= "'.$_SESSION['faim'].'" , credits= "'.$_SESSION['credits'].'" WHERE id= "'.$_SESSION['id'].'"' ;
				$req = mysql_query($sql);
				
				enregistre($_SESSION['pseudo'],"acc_nourriture_achete","+1");
				enregistre($_SESSION['pseudo'],"acc_credits_depenses","+".$pvente);
				
				$sql = 'UPDATE stocks_tbl SET nombre= "'.$nombre.'" WHERE entreprise= "'.$noment.'" AND objet= "'.$_SESSION['objet'].'"' ;
				$req = mysql_query($sql);
				$sql = 'INSERT INTO achats_tbl(id,vendeur,acheteur,objet,moment,prix) VALUES("","'.$noment.'","'.$_SESSION['pseudo'].'","'.$_SESSION['objet'].'","'.time().'","'.$pvente.'")' ;
				$req = mysql_query($sql);
				$sql = 'UPDATE principal_tbl SET fatigue= "'.$_SESSION['fatigue'].'" WHERE id= "'.$_SESSION['id'].'"' ;
				$req = mysql_query($sql);
				$achok = 2;
				}
			}
		}
	elseif(($typeo=="pad") || ($typeo=="gad") || ($typeo=="pmd") || ($typeo=="gmd") || ($typeo=="vd"))
		{
		
		if($_GET['sec']!="") $secteur = htmlentities($_GET['sec']);
		else {
			print('<meta http-equiv="refresh" content="0 ; url=http://v2.dreadcast.net/ingame/engine=achatmaison.php"> ');
			exit();
		}
		
		while(!($infos = recupereEmplacement($secteur,htmlentities($_POST['rue']))));
		
		$nrue = $infos['rue'];
		$nnum = $infos['num'];
		$sql = 'UPDATE carte_tbl SET type= "1" WHERE num="'.$nnum.'" AND idrue=(SELECT id FROM rues_tbl WHERE nom LIKE "'.$nrue.'")';
		$req = mysql_query($sql);
		
		$_SESSION['credits'] = $_SESSION['credits'] - $pvente;
		$nombre = $nombre - 1;
		$budget = $budget + $pvente;
		$benef = $benef + $pvente;
		$sql = 'UPDATE entreprises_tbl SET budget= "'.$budget.'" , benefices= "'.$benef.'" WHERE nom= "'.$noment.'"' ;
		$req = mysql_query($sql);
		$sql = 'UPDATE principal_tbl SET credits= "'.$_SESSION['credits'].'" WHERE id= "'.$_SESSION['id'].'"' ;
		$req = mysql_query($sql);
		$sql = 'UPDATE stocks_tbl SET nombre= "'.$nombre.'" WHERE entreprise= "'.$noment.'" AND objet= "'.$_SESSION['objet'].'"' ;
		$req = mysql_query($sql);
		$sql = 'INSERT INTO achats_tbl(id,acheteur,vendeur,objet,moment,prix) VALUES("","'.$_SESSION['pseudo'].'","'.$noment.'","'.$_SESSION['objet'].'","'.time().'","'.$pvente.'")' ;
		$req = mysql_query($sql);
		$sql = 'UPDATE principal_tbl SET numl= "'.$nnum.'" , ruel= "'.$nrue.'" WHERE id= "'.$_SESSION['id'].'"' ;
		$req = mysql_query($sql);
		$sql = 'INSERT INTO lieu_tbl(id,rue,num,nom,prix,repos,reposactuel) VALUES("","'.$nrue.'","'.$nnum.'","'.$_SESSION['objet'].'","0","'.$puissance.'","'.$puissance.'")' ;
		$req = mysql_query($sql);
		$sql = 'INSERT INTO proprietaire_tbl(id,rue,num,pseudo,location) VALUES("","'.$nrue.'","'.$nnum.'","'.$_SESSION['pseudo'].'","0")' ;
		$req = mysql_query($sql);
		
		enregistre($_SESSION['pseudo'],"logement",valeur($_SESSION['pseudo'],'logement')+1);
		enregistre($_SESSION['pseudo'],"acc_credits_depenses","+".$pvente);
		
		$achok = 3;
		}
	}

mysql_close($db);

?>

<div id="haut">
	<?php include("inc_entete.php"); ?>
	<div class="haut_aligngauche">
		<p>
		<div class="titrepage">
			Achat
		</div>
		<b class="module4ie"><a href="engine.php" class="module4"><img src="im_objets/icon_retour.gif" alt="Retour" />Retour</a></b>
		</p>
	</div>
</div>
<div id="centre">
<p>


<?php

if($erreurlogement == 1)
	{
	print('<strong>Maintenance</strong>.<br />Achat impossible.');
	}
elseif($achok==1)
	{
	print('<meta http-equiv="refresh" content="0 ; url=http://v2.dreadcast.net/ingame/engine=inventaire.php"> ');
	}
elseif($achok==2)
	{
	print('<meta http-equiv="refresh" content="0 ; url=http://v2.dreadcast.net/ingame/engine=bar.php?typeobj='.$_GET['typeobj'].'"> ');
	}
elseif($achok==3)
	{
	print('<meta http-equiv="refresh" content="0 ; url=http://v2.dreadcast.net/ingame/engine=logement.php"> ');
	}
elseif($_SESSION['credits']<$pvente)
	{
	print('<strong>Vous n\'avez pas assez d\'argent. <br />Le prix est de '.$pvente.' Cr&eacute;dits.</strong>');
	}
elseif($typeo=="alimentation" && $_SESSION['faim']==100)
	{
	print('Vous ne pouvez pas manger car vous n\'avez plus faim.');
	}
elseif(($_SESSION['case1']!="Vide") && ($_SESSION['case2']!="Vide") && ($_SESSION['case3']!="Vide") && ($_SESSION['case4']!="Vide") && ($_SESSION['case5']!="Vide") && ($_SESSION['case6']!="Vide"))
	{
	print("<strong>Vous n'avez pas d'emplacement vide dans votre inventaire.</strong>");
	}
?>


</p>
</div>
<?php if($chat=="oui") 	{ if ((date("H")>=21) || (date("H")<=7)) { include("inc_bas_n_c.php"); } else { include("inc_bas_j_c.php"); } } else {	if ((date("H")>=21) || (date("H")<=7)) { include("inc_bas_n.php"); } else { include("inc_bas_j.php"); }	} ?>
<?php include("inc_bas_de_page.php"); ?>
