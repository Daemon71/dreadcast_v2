<?php 
session_start(); 

$tracage = true;

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
$_SESSION['lieu'] = mysql_result($req,0,rue);
$_SESSION['num'] = mysql_result($req,0,num);
$_SESSION['action'] = mysql_result($req,0,action);
$_SESSION['alim'] = mysql_result($req,0,alim);
$_SESSION['sante'] = mysql_result($req,0,sante);
$_SESSION['drogue'] = mysql_result($req,0,drogue);
$_SESSION['fatigue'] = mysql_result($req,0,fatigue);
$_SESSION['malade'] = mysql_result($req,0,maladie);
$_SESSION['lieu'] = strtolower($_SESSION['lieu']);
$_SESSION['lieu'] = ucwords($_SESSION['lieu']);
$_SESSION['statut'] = mysql_result($req,0,statut);
$_SESSION['allopass'] = mysql_result($req,0,allopass);
$medecine = mysql_result($req,0,medecine); 
$_SESSION['arme'] = mysql_result($req,0,arme); 
$_SESSION['vetements'] = mysql_result($req,0,vetements); 
$_SESSION['objet'] = mysql_result($req,0,objet); 
$_SESSION['credits'] = mysql_result($req,0,credits); 
$_SESSION['case1'] = mysql_result($req,0,case1); 
$_SESSION['case2'] = mysql_result($req,0,case2); 
$_SESSION['case3'] = mysql_result($req,0,case3); 
$_SESSION['case4'] = mysql_result($req,0,case4); 
$_SESSION['case5'] = mysql_result($req,0,case5); 
$_SESSION['case6'] = mysql_result($req,0,case6); 

for($i=1;$i<=6;$i++) // RATTRAPAGE DE BUG !
	{
	if($_SESSION['case'.$i]=="")
		{
		$sql = 'UPDATE principal_tbl SET case'.$i.' = "Vide" WHERE id= "'.$_SESSION['id'].'"' ;
		mysql_query($sql);
		$_SESSION['case'.$i]="Vide";
		}
	}

$sql = 'SELECT chargeur FROM armes_tbl WHERE idarme= "'.strstr($_SESSION['arme'],"-").'"' ;
$req = mysql_query($sql);
$res = mysql_num_rows($req);
if($res>0)
	{
	$_SESSION['chargeur'] = mysql_result($req,0,chargeur);
	}
else
	{
	$_SESSION['chargeur'] = 0;
	}

if(($_SESSION['case1']=="Medpack") || ($_SESSION['case2']=="Medpack") || ($_SESSION['case3']=="Medpack") || ($_SESSION['case4']=="Medpack") || ($_SESSION['case5']=="Medpack") || ($_SESSION['case6']=="Medpack"))
	{
	if(($_SESSION['case1']=="Kronatium") || ($_SESSION['case2']=="Kronatium") || ($_SESSION['case3']=="Kronatium") || ($_SESSION['case4']=="Kronatium") || ($_SESSION['case5']=="Kronatium") || ($_SESSION['case6']=="Kronatium"))
		{
		if(($_SESSION['case1']=="Injection") || ($_SESSION['case2']=="Injection") || ($_SESSION['case3']=="Injection") || ($_SESSION['case4']=="Injection") || ($_SESSION['case5']=="Injection") || ($_SESSION['case6']=="Injection"))
			{
			if($medecine>=100)
				{
				$vaccin = "ok";
				if($medecine>=120)
                    {
                    if(($_SESSION['case1']=="Caisse") || ($_SESSION['case2']=="Caisse") || ($_SESSION['case3']=="Caisse") || ($_SESSION['case4']=="Caisse") || ($_SESSION['case5']=="Caisse") || ($_SESSION['case6']=="Caisse"))
                        {
                        if(($_SESSION['case1']=="Fiole de jeunesse") || ($_SESSION['case2']=="Fiole de jeunesse") || ($_SESSION['case3']=="Fiole de jeunesse") || ($_SESSION['case4']=="Fiole de jeunesse") || ($_SESSION['case5']=="Fiole de jeunesse") || ($_SESSION['case6']=="Fiole de jeunesse"))
                            {
                            //$virus = "ok";
                            }
                        }
                    }
				}
			}
		}
	}

if(($_SESSION['case1']=="Caisse") || ($_SESSION['case2']=="Caisse") || ($_SESSION['case3']=="Caisse") || ($_SESSION['case4']=="Caisse") || ($_SESSION['case5']=="Caisse") || ($_SESSION['case6']=="Caisse"))
	{
	if(($_SESSION['case1']=="Chargeur") || ($_SESSION['case2']=="Chargeur") || ($_SESSION['case3']=="Chargeur") || ($_SESSION['case4']=="Chargeur") || ($_SESSION['case5']=="Chargeur") || ($_SESSION['case6']=="Chargeur"))
		{
		if(($_SESSION['case1']=="Feuille de papier libre") || ($_SESSION['case2']=="Feuille de papier libre") || ($_SESSION['case3']=="Feuille de papier libre") || ($_SESSION['case4']=="Feuille de papier libre") || ($_SESSION['case5']=="Feuille de papier libre") || ($_SESSION['case6']=="Feuille de papier libre"))
			{
			if(($_SESSION['case1']=="Medpack") || ($_SESSION['case2']=="Medpack") || ($_SESSION['case3']=="Medpack") || ($_SESSION['case4']=="Medpack") || ($_SESSION['case5']=="Medpack") || ($_SESSION['case6']=="Medpack"))
				{
				if($medecine>=40)
					{
					$drogue = "ok";
					}
				}
			}
		}
	}

if((($_SESSION['objet']=="Neuvopack") or ($_SESSION['objet']=="Neuvopack1") or ($_SESSION['objet']=="Neuvopack2") or ($_SESSION['objet']=="Neuvopack3") or ($_SESSION['objet']=="Neuvopack4") or ($_SESSION['objet']=="Neuvopack5") or ($_SESSION['objet']=="Neuvopack6") or ($_SESSION['objet']=="Neuvopack7") or ($_SESSION['objet']=="Neuvopack8") or ($_SESSION['objet']=="Neuvopack9")) && (strtolower($_SESSION['action'])=="aucune"))
	{
	$neuvopack = "ok";
	}

if(($_SESSION['case1']=="Feuille de papier libre") || ($_SESSION['case2']=="Feuille de papier libre") || ($_SESSION['case3']=="Feuille de papier libre") || ($_SESSION['case4']=="Feuille de papier libre") || ($_SESSION['case5']=="Feuille de papier libre") || ($_SESSION['case6']=="Feuille de papier libre"))
	{
	if(($_SESSION['case1']=="Carte") || ($_SESSION['case2']=="Carte") || ($_SESSION['case3']=="Carte") || ($_SESSION['case4']=="Carte") || ($_SESSION['case5']=="Carte") || ($_SESSION['case6']=="Carte"))
		{
		if($medecine>=20)
			{
			$cigarettes = "ok";
			}
		}
	}

if(($_SESSION['objet']=="Deck 1.1") or ($_SESSION['objet']=="Deck Premium") or ($_SESSION['objet']=="Deck Reist") or ($_SESSION['objet']=="Cyber Deck") or ($_SESSION['objet']=="Deck Transcom"))
	{
	$deck = "ok";
	}

if($_SERVER['QUERY_STRING']!="")
	{
	$act = $_SERVER['QUERY_STRING'];
	}
else
	{
	$act = "utiliser";
	}

// Calcul de Att. et des bonus (arme) //

$bonusa = "";

$arme = (ereg('-',$_SESSION['arme']))?substr($_SESSION['arme'],0,strpos($_SESSION['arme'],"-")):$_SESSION['arme'];

$sqla = 'SELECT id,type,puissance,ecart FROM objets_tbl WHERE nom= "'.$arme.'"' ;
$reqa = mysql_query($sqla);
$typearme = mysql_result($reqa,0,type);
$puissa = mysql_result($reqa,0,puissance);
$puissamax = mysql_result($reqa,0,puissance)+mysql_result($reqa,0,ecart);

$sqla = 'SELECT nature,bonus FROM recherche_effets_tbl WHERE ido = "'.mysql_result($reqa,0,id).'" AND bonus = 0' ;
$reqa = mysql_query($sqla);
$resa = mysql_num_rows($reqa);

for($i=0;$i<$resa;$i++)
	{
	$temp = ucwords(mysql_result($reqa,$i,nature));
	$bonusa .= substr($temp,0,3).',';
	}

// Calcul de Def. et des bonus (vetement + objet) //

$puissv = 0;
$puisso = 0;
$bonusv = "";
$bonuso = "";

$sqlv = 'SELECT nature,bonus FROM recherche_effets_tbl WHERE ido = (SELECT id FROM objets_tbl WHERE nom = "'.$_SESSION['vetements'].'")' ;
$reqv = mysql_query($sqlv);
$resv = mysql_num_rows($reqv);

for($i=0;$i<$resv;$i++)
	{
	if(mysql_result($reqv,$i,bonus) == 0)
		{
		$temp = ucwords(mysql_result($reqv,$i,nature));
		$bonusv .= substr($temp,0,3).',';
		}
	elseif(mysql_result($reqv,$i,nature)=="resistance") $puissv += mysql_result($reqv,$i,bonus);
	}

$sqlo = 'SELECT nature,bonus FROM recherche_effets_tbl WHERE ido = (SELECT id FROM objets_tbl WHERE nom = "'.$_SESSION['objet'].'")' ;
$reqo = mysql_query($sqlo);
$reso = mysql_num_rows($reqo);
echo '<!-- OOOOOOK '.$reso.' -->';
for($i=0;$i<$reso;$i++)
	{
	//(echo '<!-- OOOOOOK '.mysql_result($reqo,$i,nature).' = '.mysql_result($reqo,$i,bonus).' -->';
	if(mysql_result($reqo,$i,bonus) == 0)
		{
		$temp = ucwords(mysql_result($reqo,$i,nature));
		$bonuso .= substr($temp,0,3).',';
		}
	elseif(mysql_result($reqo,$i,nature)=="resistance") $puisso += mysql_result($reqo,$i,bonus);
	}

$puissdef = $puissv + $puisso;
$bonustot = $bonusa.$bonusv.$bonuso;
$bonustot = str_replace(" ",",",trim(str_replace(","," ",$bonustot)));

// Titres XP
$sql = 'SELECT titre FROM titres_tbl WHERE pseudo= "'.$_SESSION['pseudo'].'"' ;
$req = mysql_query($sql);
$res = mysql_num_rows($req);
for($i=0;$i!=$res;$i++) $titres .= mysql_result($req,$i,titre);

mysql_close($db);
?>
<?php if ((date("H")>=21) || (date("H")<=7)) { include("inc_haut_n.php"); } else { include("inc_haut_j.php");} ?>

<div id="haut">
	<?php include("inc_entete.php"); ?>
	<div class="haut_aligngauche">
		<p>
		<div class="titrepage">
			Inventaire personnel
		</div>
		<?php if(statut($_SESSION['statut'])>=2) {
			print('<b class="module1ie"><a href="engine=aitl2.php" class="module1"><img src="im_objets/icon_aitl.gif" alt="AITL" />Consulter l\'AITL 2.0</a></b>');
		} ?>
		</p>
	</div>
</div>
<div id="centre">
<p>
<div class="module2"><img src="im_objets/icon_creditcolor.gif" alt="Credits" />Cr&eacute;dits en poche : <span class="color7"><?php print($_SESSION['credits']);?></span></div>
<?php if($_SESSION['allopass']==0) { print('<div class="obtenircredit"><a href="engine=allopass.php" class="module3"><img src="im_objets/icon_creditplus.gif" alt="+ de crédits" />Obtenir plus de Cr&eacute;dits</a></div>'); } elseif($_SESSION['allopass']<5) { print('<div class="obtenircredit"><a href="engine=allopass.php" class="module3"><img src="im_objets/icon_creditplus.gif" alt="+ de crédits" />Obtenir plus de Cr&eacute;dits ( <font color="blue">'.$_SESSION['allopass'].' / 5</font> )</a></div>'); } else { print('<div class="obtenircredit"><a href="engine=roulette.php" class="module3"><img src="im_objets/icon_creditplus.gif" alt="cadeau" />Obtenir un cadeau ( <font color="green">5 / 5</font> )</a></div>'); }?>
</p>

<div id="centre_inventaire">
	<div class="infos">
		<?php
		$db = mysql_connect('localhost', 'CENSURE', 'CENSURE') or die("Erreur de connexion au serveur des messages.");
		mysql_select_db('CENSURE',$db) or die("Erreur de connexion a la base de donnees des messages.");
		
		print('<p id="att">Att. <em class="alignleft">'.$puissa.'-'.$puissamax.'</em></p>');
		
		// D&eacute;finit les puissances relatives des diff&eacute;rentes armes de l'inventaire //

		for($i=1;$i<7;$i++)
			{
			if($_SESSION['case'.$i.'']!="Vide")
				{
				$sql = 'SELECT id,type,puissance,ecart FROM objets_tbl WHERE nom= "'.$_SESSION['case'.$i.''].'"' ;
				$req = mysql_query($sql);

				$typec = mysql_result($req,0,type);
				${'typecase'.$i} = $typec;

				if(($typec=="armestir"||$typec=="armesav"||$typec=="armesprim"||$typec=="acac"||$typec=="armtu"||$typec=="armcu")&&$_SESSION['case'.$i.'']!="Chargeur") 
					{
						$npuissa = mysql_result($req,0,puissance);
						$npuissamax = mysql_result($req,0,puissance)+mysql_result($req,0,ecart);
						if($puissa+$puissamax<$npuissa+$npuissamax) print('<p id="possible'.$i.'" style="display:none;">Att. <em class="alignleft" style="color:blue;">'.$npuissa.'-'.$npuissamax.'</em></p>');
						if($puissa+$puissamax>$npuissa+$npuissamax) print('<p id="possible'.$i.'" style="display:none;">Att. <em class="alignleft" style="color:red;">'.$npuissa.'-'.$npuissamax.'</em></p>');
						else print('<p id="possible'.$i.'" style="display:none;">Att. <em class="alignleft">'.$puissa.'-'.$puissamax.'</em></p>');
					}
				else print('<p id="possible'.$i.'" style="display:none;">Att. <em class="alignleft">'.$puissa.'-'.$puissamax.'</em></p>');
				}
			}
		
		print('<p id="pasdarme" style="display:none;">Att. <em class="alignleft" style="color:red;">1-4</em></p>');
		
		
		
		
		print('<p id="def">D&eacute;f. <em class="alignleft">'.$puissdef.'</em></p>');
		
		// D&eacute;finit les puissances relatives des diff&eacute;rents vetements de l'inventaire //
		
		for($i=1;$i<7;$i++)
			{
			
			if($_SESSION['case'.$i.'']!="Vide")
				{
					$sqlv = 'SELECT id,url FROM objets_tbl WHERE nom = "'.$_SESSION['case'.$i].'"' ;
					$reqv = mysql_query($sqlv);

					$url = mysql_result($reqv,0,url);
					
					if(${'typecase'.$i}!="vetu" AND ${'typecase'.$i}!="tissu" AND ${'typecase'.$i}!="soie" AND ${'typecase'.$i}!="cristal") $temp = $puissdef;
					else $temp = 0;

					$sqlv = 'SELECT bonus FROM recherche_effets_tbl WHERE ido = "'.mysql_result($reqv,0,id).'" AND nature = "resistance"' ;
					$reqv = mysql_query($sqlv);
					$resv = mysql_num_rows($reqv);

					if($resv != 0)
						{
						if(${'typecase'.$i}=="vetu" OR ${'typecase'.$i}=="tissu" OR ${'typecase'.$i}=="soie" OR ${'typecase'.$i}=="cristal")
							{
							$temp = $puisso + mysql_result($reqv,0,bonus);
							}
						if(ereg("engine=equipo.php",$url))
							{
							$temp = $puissv + mysql_result($reqv,0,bonus);
							}
						}
					
					/*
					if($_SESSION['case'.$i.'']=="Alliance"||$_SESSION['case'.$i.'']=="Viseur"||$_SESSION['case'.$i.'']=="Lunettes de soleil"||$_SESSION['case'.$i.'']=="Anneau de puissance"||$_SESSION['case'.$i.'']=="Neuvopack"||$_SESSION['case'.$i.'']=="Neuvopack1"||$_SESSION['case'.$i.'']=="Neuvopack2"||$_SESSION['case'.$i.'']=="Neuvopack3"||$_SESSION['case'.$i.'']=="Neuvopack4"||$_SESSION['case'.$i.'']=="Neuvopack5"||$_SESSION['case'.$i.'']=="Neuvopack6"||$_SESSION['case'.$i.'']=="Neuvopack7"||$_SESSION['case'.$i.'']=="Neuvopack8"||$_SESSION['case'.$i.'']=="Neuvopack9"||$_SESSION['case'.$i.'']=="Neuvopack10"){ $temp+=0;if($_SESSION['vetements']=="Kevlar")$temp+=30;}
					if($_SESSION['case'.$i.'']=="Veste"){ $temp+=0;if($_SESSION['objet']=="Casque")$temp+=10;}
					if($_SESSION['case'.$i.'']=="De cristal"){ $temp+=0;if($_SESSION['objet']=="Casque")$temp+=10;}
					if($_SESSION['case'.$i.'']=="Casque"){ $temp+=10;if($_SESSION['vetements']=="Kevlar")$temp+=30;}
					if($_SESSION['case'.$i.'']=="Kevlar"){ $temp+=30;if($_SESSION['objet']=="Casque")$temp+=10;}
					if($_SESSION['case'.$i.'']!="Casque"&&$_SESSION['case'.$i.'']!="Kevlar"&&$_SESSION['case'.$i.'']!="Veste"&&$_SESSION['case'.$i.'']!="De cristal"&&$_SESSION['case'.$i.'']!="Alliance"&&$_SESSION['case'.$i.'']!="Viseur"&&$_SESSION['case'.$i.'']!="Lunettes de soleil"&&$_SESSION['case'.$i.'']!="Anneau de puissance"&&$_SESSION['case'.$i.'']!="Neuvopack"&&$_SESSION['case'.$i.'']!="Neuvopack1"&&$_SESSION['case'.$i.'']!="Neuvopack2"&&$_SESSION['case'.$i.'']!="Neuvopack3"&&$_SESSION['case'.$i.'']!="Neuvopack4"&&$_SESSION['case'.$i.'']!="Neuvopack5"&&$_SESSION['case'.$i.'']!="Neuvopack6"&&$_SESSION['case'.$i.'']!="Neuvopack7"&&$_SESSION['case'.$i.'']!="Neuvopack8"&&$_SESSION['case'.$i.'']!="Neuvopack9"&&$_SESSION['case'.$i.'']!="Neuvopack10") $temp = $puissdef;
					*/
					if($temp>$puissdef) print('<p id="possdef'.$i.'" style="display:none;">D&eacute;f. <em class="alignleft" style="color:blue;">'.$temp.'</em></p>');
					elseif($temp<$puissdef) print('<p id="possdef'.$i.'" style="display:none;">D&eacute;f. <em class="alignleft" style="color:red;">'.$temp.'</em></p>');
					else print('<p id="possdef'.$i.'" style="display:none;">D&eacute;f. <em class="alignleft">'.$puissdef.'</em></p>');
				}
			else print('<p id="possdef'.$i.'" style="display:none;">D&eacute;f. <em class="alignleft">'.$puissdef.'</em></p>');
			}
		
		if($puisso!=0) print('<p id="pasddef" style="display:none;">D&eacute;f. <em class="alignleft" style="color:red;">'.$puissv.'</em></p>');
		else print('<p id="pasddef" style="display:none;">D&eacute;f. <em class="alignleft">'.$puissdef.'</em></p>');
		?>
		
		<p>Effet(s)
		<?php

		// Definition des effets (vetement + objet) //

		print('<span id="effet">');

		if($bonustot=="") print('<em class="chiant">Aucun</em>');
		else print('<em class="chiant">'.str_replace(",",". ",$bonustot).'.</em>');

		print('</span>');

		for($i=1;$i<7;$i++)
			{
			$temp = "";
			
			if($_SESSION['case'.$i.'']!="Vide")
				{
				
				$case = (ereg('-',$_SESSION['case'.$i]))?substr($_SESSION['case'.$i],0,strpos($_SESSION['case'.$i],"-")):$_SESSION['case'.$i];
				
				$sqlv = 'SELECT id,url FROM objets_tbl WHERE nom LIKE "'.$case.'"' ;
				$reqv = mysql_query($sqlv);
				
				$url = mysql_result($reqv,0,url);
				
				$sqlv = 'SELECT nature FROM recherche_effets_tbl WHERE ido = "'.mysql_result($reqv,0,id).'" AND bonus = "0"' ;
				$reqv = mysql_query($sqlv);
				$resv = mysql_num_rows($reqv);

				for($v=0;$v<$resv;$v++)
					{
					$hop = ucwords(mysql_result($reqv,0,nature));
					
					if(${'typecase'.$i}=="armtu" OR ${'typecase'.$i}=="armcu" OR ${'typecase'.$i}=="armestir" OR ${'typecase'.$i}=="armesav" OR ${'typecase'.$i}=="armesprim" OR ${'typecase'.$i}=="acac")
						{
						if(substr($hop,0,3)!="" AND !ereg(substr($hop,0,3),$temp) AND !ereg(substr($hop,0,3),$bonusv) AND !ereg(substr($hop,0,3),$bonuso))
							{
							$temp .= substr($hop,0,3).',';
							}
						}
					elseif(ereg("engine=equip.php",$url))
						{
						if(substr($hop,0,3)!="" AND !ereg(substr($hop,0,3),$temp) AND !ereg(substr($hop,0,3),$bonusa) AND !ereg(substr($hop,0,3),$bonuso))
							{
							$temp .= substr($hop,0,3).',';
							}
						}
					elseif(ereg("engine=equipo.php",$url))
						{
						if(substr($hop,0,3)!="" AND !ereg(substr($hop,0,3),$temp) AND !ereg(substr($hop,0,3),$bonusa) AND !ereg(substr($hop,0,3),$bonusv))
							{
							$temp .= substr($hop,0,3).',';
							}
						}
					}
				
				if(${'typecase'.$i}=="armtu" OR ${'typecase'.$i}=="armcu" OR ${'typecase'.$i}=="armestir" OR ${'typecase'.$i}=="armesav" OR ${'typecase'.$i}=="armesprim" OR ${'typecase'.$i}=="acac") $temp = $temp.$bonusv.$bonuso;
				elseif(ereg("engine=equip.php",$url)) $temp = $bonusa.$temp.$bonuso;
				elseif(ereg("engine=equipo.php",$url)) $temp = $bonusa.$bonusv.$temp;
				

				if(count(explode(",",$temp))>count(explode(",",$bonustot.','))) print('<span id="posseffet'.$i.'" style="display:none;margin:0;padding:0;"><em class="chiant" style="color:blue;">'.str_replace(",",". ",$temp).'.</em></span>');
				elseif(count(explode(",",$temp))>count(explode(",",$bonustot.','))) print('<span id="posseffet'.$i.'" style="display:none;margin:0;padding:0;"><em class="chiant" style="color:red;">'.str_replace(",",". ",$temp).'.</em></span>');
				elseif($bonustot != "") print('<span id="posseffet'.$i.'" style="display:none;margin:0;padding:0;"><em class="chiant">'.str_replace(",",". ",$bonustot).'.</em></span>');
				else print('<span id="posseffet'.$i.'" style="display:none;margin:0;padding:0;"><em class="chiant">Aucun</em></span>');
				
				}
			}
		
		if($bonusv != "" OR $bonuso != "") print('<span id="pasdobjeta" style="display:none;margin:0;padding:0;"><em class="chiant">'.str_replace(" ",". ",trim(str_replace(","," ",$bonusv.$bonuso))).'.</em></span>');
		else print('<span id="pasdobjeta" style="display:none;margin:0;padding:0;"><em class="chiant">Aucun</em></span>');
		if($bonusa != "" OR $bonuso != "") print('<span id="pasdobjetv" style="display:none;margin:0;padding:0;"><em class="chiant">'.str_replace(" ",". ",trim(str_replace(","," ",$bonusa.$bonuso))).'.</em></span>');
		else print('<span id="pasdobjetv" style="display:none;margin:0;padding:0;"><em class="chiant">Aucun</em></span>');
		if($bonusa != "" OR $bonusv != "") print('<span id="pasdobjeto" style="display:none;margin:0;padding:0;"><em class="chiant">'.str_replace(" ",". ",trim(str_replace(","," ",$bonusa.$bonusv))).'.</em></span>');
		else print('<span id="pasdobjeto" style="display:none;margin:0;padding:0;"><em class="chiant">Aucun</em></span>');
		
		mysql_close($db);
		
		?>
		</p>
	</div>
	
	<div class="infosaction">
		<p id="infoa" style="display:none;">
		<?php
		if($act=="utiliser"&&$_SESSION['arme']!="Aucune"&&$typearme!="armesprim"&&$typearme!="acac"&&$typearme!="armtu"&&$typearme!="armcu") print('Regarder la fiche');
		if($act=="utiliser"&&$_SESSION['arme']!="Aucune"&&($typearme=="armesprim" OR $typearme=="acac" OR $typearme=="armtu" OR $typearme=="armcu")) print('Retirer cette arme');
		elseif($act=="envoyer"&&$_SESSION['arme']!="Aucune") print('D&eacute;poser mon arme ici');
		elseif($act=="infos"&&$_SESSION['arme']!="Aucune") print('Se renseigner sur mon arme');
		elseif($act=="det"&&$_SESSION['arme']!="Aucune") print('D&eacute;truire mon arme');
		?>
		</p>
	
		<p id="infov" style="display:none;">
		<?php
		if($act=="infos") print('Se renseigner sur mon habit');
		?>
		</p>
		
		<p id="infoo" style="display:none;">
		<?php
		if($act=="utiliser"&&$_SESSION['objet']!="Aucun") print('Retirer mon objet');
		elseif($act=="envoyer"&&$_SESSION['objet']!="Aucun") print('D&eacute;poser mon objet ici');
		elseif($act=="infos"&&$_SESSION['objet']!="Aucun") print('Se renseigner sur mon objet');
		elseif($act=="det"&&$_SESSION['objet']!="Aucun") print('D&eacute;truire mon objet');
		?>
		</p>

		<?php
		$db = mysql_connect('localhost', 'CENSURE', 'CENSURE') or die("Erreur de connexion au serveur des messages.");
		mysql_select_db('CENSURE',$db) or die("Erreur de connexion a la base de donnees des messages.");
		
		for($i=1;$i<7;$i++)
		{
			print('<p id="info'.$i.'" style="display:none;">
			');
			if($act=="utiliser"&&$_SESSION['case'.$i.'']!="Vide")
				{
				$sql = 'SELECT type,url FROM objets_tbl WHERE nom= "'.$_SESSION['case'.$i.''].'"' ;
				$req = mysql_query($sql);
				$typec = mysql_result($req,0,type);
				$urlc = mysql_result($req,0,url);
				if($_SESSION['case'.$i.'']=="Chargeur") print('Recharger mon arme');
				elseif($typec=="armestir"||$typec=="armesav") print('Regarder la fiche');
				elseif($typec=="ouu") print('Consommer cet objet');
				elseif($typec=="acac"||$typec=="armesprim"||$typec=="armtu"||$typec=="armcu") print('&Eacute;quiper cette arme');
				elseif(ereg("engine=equip.php",$urlc)) print('Enfiler cet habit');
				elseif($_SESSION['case'.$i.'']=="Carnet") print('Consulter mon carnet');
				elseif($_SESSION['case'.$i.'']=="Neuvopack"||$_SESSION['case'.$i.'']=="Neuvopack1"||$_SESSION['case'.$i.'']=="Neuvopack2"||$_SESSION['case'.$i.'']=="Neuvopack3"||$_SESSION['case'.$i.'']=="Neuvopack4"||$_SESSION['case'.$i.'']=="Neuvopack5"||$_SESSION['case'.$i.'']=="Neuvopack6"||$_SESSION['case'.$i.'']=="Neuvopack7"||$_SESSION['case'.$i.'']=="Neuvopack8"||$_SESSION['case'.$i.'']=="Neuvopack9"||$_SESSION['case'.$i.'']=="Neuvopack10") print('Equiper le Neuvopack');
				elseif($_SESSION['case'.$i.'']=="Refrigerateur"||$_SESSION['case'.$i.'']=="FR Americain") print('Installer mon r&eacute;frig&eacute;rateur ici');
				elseif($_SESSION['case'.$i.'']=="Carte") print('Regarder ma carte');
				elseif($_SESSION['case'.$i.'']=="Medpack") print('Remonter ma sant&eacute;');
				elseif($_SESSION['case'.$i.'']=="Paquet de cigarettes") print('Remonter ma forme');
				elseif($_SESSION['case'.$i.'']=="Kronatium") print('Consommer une dose');
				elseif($typec=="jag") print('Gratter le ticket');
				elseif($_SESSION['case'.$i.'']=="livre") print('Se mettre &grave; lire');
				elseif($_SESSION['case'.$i.'']=="injection") print('S\'injecter la substance');
				elseif($_SESSION['case'.$i.'']=="Camera de police"||$_SESSION['case'.$i.'']=="Camera de surveillance") print('Installer la camera ici');
				elseif($_SESSION['case'.$i.'']=="Deck 1.1"||$_SESSION['case'.$i.'']=="Deck Premium"||$_SESSION['case'.$i.'']=="Deck Reist"||$_SESSION['case'.$i.'']=="Cyber Deck"||$_SESSION['case'.$i.'']=="Deck Transcom") print('Se placer devant le deck');
				elseif($_SESSION['case'.$i.'']=="Fiole de jeunesse") print('Boire son contenu');
				elseif($_SESSION['case'.$i.'']=="AITL") print('Consulter mon AITL');
				elseif($_SESSION['case'.$i.'']=="AITL 2.0") print('Consulter mon AITL 2.0');
				elseif($_SESSION['case'.$i.'']=="Digicode") print('Installer le digicode ici');
				elseif($_SESSION['case'.$i.'']=="Recueil de signatures"||($typec=="feuille"&&$_SESSION['case'.$i.''][0]=="R")) print('Signer le recueil');
				elseif($_SESSION['case'.$i.'']=="Feuille de papier libre"||($typec=="feuille"&&$_SESSION['case'.$i.''][0]=="F")) print('Ecrire sur la feuille');
				elseif($_SESSION['case'.$i.'']=="Alcootest") print('Tester mon taux actuel');
				elseif($_SESSION['case'.$i.'']=="Petit panier de courses"||$_SESSION['case'.$i.'']=="Caisse de nourriture") print('D&eacute;poser dans mon frigo');
				elseif($_SESSION['case'.$i.'']=="Alliance") print('Enfiler l\'alliance');
				elseif($_SESSION['case'.$i.'']=="Injection") print('Tester l\'injection');
				elseif($_SESSION['case'.$i.'']=="Vaccin") print('Se vacciner');
				elseif(ereg("DreadCast News ",$_SESSION['case'.$i.''])) print('Lire le DCN');
				elseif(ereg("sac",$typec)) print('Regarder dedans');
				elseif($typec=="tracte") print('Lire ce tracte');
				elseif($typec=="modif") print('Installer l\'am&eacute;lioration');
				elseif(ereg("engine=equipo.php",$urlc)) print('Equiper cet objet');
				}
			elseif($act=="envoyer"&&$_SESSION['case1']!="Aucun") print('D&eacute;poser cet objet ici');
			elseif($act=="infos"&&$_SESSION['case1']!="Aucun") print('Se renseigner sur cet objet');
			elseif($act=="det"&&$_SESSION['case1']!="Aucun") print('D&eacute;truire cet objet');
			elseif(ereg("sac",$act)&&$_SESSION['case1']!="Aucun") print('Ranger cet objet');
			print('
			</p>
			');
		}
		
		mysql_close($db);
		?>
		</p>
	</div>
	
	<form name="form1" id="maforme">
            <select name="menu1" id="leselect" onChange="MM_jumpMenu('parent',this,0)">
                <option value="engine=inventaire.php?utiliser" <?php if($act=="utiliser") {print('selected');} ?>>Utiliser</option>
				<?php 
				if($_SESSION['num'] > 0)
					{
					if($act=="envoyer")
						{
						print('<option value="engine=inventaire.php?envoyer" selected > Envoyer vers '.$_SESSION['num'].' '.ucwords($_SESSION['lieu']).'</option>');
						}
					else
						{
						print('<option value="engine=inventaire.php?envoyer" > Envoyer vers '.$_SESSION['num'].' '.ucwords($_SESSION['lieu']).'</option>');
						}
					}
		
				$db = mysql_connect('localhost', 'CENSURE', 'CENSURE') or die("Erreur de connexion au serveur des messages.");
				mysql_select_db('CENSURE',$db) or die("Erreur de connexion a la base de donnees des messages.");
		
				for($i=1;$i<7;$i++)
					{
					list($obj,$idobj)=explode("-", $_SESSION['case'.$i]);
					
					$sql = 'SELECT type FROM objets_tbl WHERE nom LIKE "'.$obj.'%"' ;
					$req = mysql_query($sql);
					$res = mysql_num_rows($req);
					
					if($res != 0 AND mysql_result($req,0,type)=="sac")
						{
						if(ereg("sac",$act))
						print('<option value="engine=inventaire.php?sac'.$i.'" selected> Mettre dans le '.$obj.' (Case '.$i.')</option>');
						else
						print('<option value="engine=inventaire.php?sac'.$i.'"> Mettre dans le '.$obj.' (Case '.$i.')</option>');
						}
						
					}
					
				mysql_close($db);
				
				?>
                <option value="engine=inventaire.php?infos" <?php if($act=="infos") {print('selected');} ?>>Obtenir des informations</option>
                <option value="engine=inventaire.php?det" <?php if($act=="det") {print('selected');} ?>>D&eacute;truire</option>
				<?php
				if(($drogue=="ok") OR ($cigarettes=="ok") OR ($deck=="ok") OR ($neuvopack=="ok") OR ($vaccin=="ok") OR ($virus=="ok"))
					{
					print('<option value="#"> ---</option>');
					}
				if($drogue=="ok")
					{
					print('<option value="engine=fabriquer.php">Produire du Kronatium</option>');
					}
				if($cigarettes=="ok")
					{
					print('<option value="engine=fabriquercig.php">Fabriquer un paquet de cigarettes</option>');
					}
				if($deck=="ok")
					{
					print('<option value="engine=cyberdeck.php">Allumer votre Deck</option>');
					}
				if($neuvopack=="ok")
					{
					print('<option value="engine=recherchec.php">Rechercher des cristaux</option>');
					}
				if($vaccin=="ok")
					{
					print('<option value="engine=fabriquervaccin.php">Produire un vaccin</option>');
					}
				if($virus=="ok")
					{
					print('<option value="engine=fabriquervirus.php">Produire un virus</option>');
					}
				?>
            </select>
        </form>

<?php 
$db = mysql_connect('localhost', 'CENSURE', 'CENSURE') or die("Erreur de connexion au serveur des messages.");
mysql_select_db('CENSURE',$db) or die("Erreur de connexion a la base de donnees des messages.");

$sql = 'SELECT image,type,url FROM objets_tbl WHERE nom= "'.$_SESSION['arme'].'"' ;
$req = mysql_query($sql);
$image = mysql_result($req,0,image); 
$typea = mysql_result($req,0,type);
$urla = mysql_result($req,0,url); 

if((($typea=="armestir") || ($typea=="armesav")) && (substr($_SESSION['arme'],0,strpos($_SESSION['arme'],"-"))!="Lance roquette") && (substr($_SESSION['arme'],0,strpos($_SESSION['arme'],"-"))!="Lance flammes"))
	{
	if($act=="utiliser")
		{
		if($_SESSION['arme']!="Aucune")
			{
			if($typea=="armestir" || $typea=="armesav")
				{
				print('<p id="arme"><a href="'.$urla.'" onmouseover="affiche_art(\'infoa\',true);affiche_art(\'att\',false);affiche_art(\'pasdarme\',true);" onmouseout="affiche_art(\'infoa\',false);affiche_art(\'att\',true);affiche_art(\'pasdarme\',false);"><img border="0" src="http://v2.dreadcast.net/ingame/im_objets/chargeur5.php?arme='.$image.'&balles='.$_SESSION['chargeur'].'"'); if(ereg("centre/",$image)) print('width="73" height="73" style="border:1px solid #000;"'); print('></a></p>');
				}
			else
				{
				print('<p id="arme"><a href="engine=desequip.php?'.$_SESSION['arme'].'" onmouseover="affiche_art(\'infoa\',true);" onmouseout="affiche_art(\'infoa\',false);"><img border="0" src="http://v2.dreadcast.net/ingame/im_objets/chargeur5.php?arme='.$image.'&balles='.$_SESSION['chargeur'].'"'); if(ereg("centre/",$image)) print('width="73" height="73" style="border:1px solid #000;"'); print('></a></p>');
				}
			}
		else
			{
			print('<p id="arme"><img border="0" src="http://v2.dreadcast.net/ingame/im_objets/chargeur5.php?arme='.$image.'&balles='.$_SESSION['chargeur'].'"></p>');
			}
		}
	elseif($act=="envoyer")
		{
		if($_SESSION['arme']!="Aucune")
			{
			print('<p id="arme"><a href="engine=inv-lieu.php?arme" onmouseover="affiche_art(\'infoa\',true);affiche_art(\'att\',false);affiche_art(\'pasdarme\',true);" onmouseout="affiche_art(\'infoa\',false);affiche_art(\'att\',true);affiche_art(\'pasdarme\',false);"><img border="0" src="http://v2.dreadcast.net/ingame/im_objets/chargeur5.php?arme='.$image.'&balles='.$_SESSION['chargeur'].'"></a></p>');
			}
		else
			{
			print('<p id="arme"><img border="0" src="http://v2.dreadcast.net/ingame/im_objets/chargeur5.php?arme='.$image.'&balles='.$_SESSION['chargeur'].'"></p>');
			}
		}
	elseif($act=="infos")
		{
		print('<p id="arme"><a href="http://v2.dreadcast.net/info=objet.php?'.strtolower($_SESSION['arme']).'" target="_blank" onmouseover="affiche_art(\'infoa\',true);" onmouseout="affiche_art(\'infoa\',false);"><img border="0" src="http://v2.dreadcast.net/ingame/im_objets/chargeur5.php?arme='.$image.'&balles='.$_SESSION['chargeur'].'"></a></p>');
		}
	elseif($act=="det")
		{
		if($_SESSION['arme']!="Aucune")
			{
			print('<p id="arme"><a href="engine=det.php?arme" onmouseover="affiche_art(\'infoa\',true);affiche_art(\'att\',false);affiche_art(\'pasdarme\',true);" onmouseout="affiche_art(\'infoa\',false);affiche_art(\'att\',true);affiche_art(\'pasdarme\',false);"><img border="0" src="http://v2.dreadcast.net/ingame/im_objets/chargeur5.php?arme='.$image.'&balles='.$_SESSION['chargeur'].'"></a></p>');
			}
		else
			{
			print('<p id="arme"><img border="0" src="http://v2.dreadcast.net/ingame/im_objets/chargeur5.php?arme='.$image.'&balles='.$_SESSION['chargeur'].'"></p>');
			}
		}
	}
else
	{
	if($act=="utiliser")
		{
		if($_SESSION['arme']!="Aucune")
			{
			print('<p id="arme"><a href="engine=desequip.php?'.$_SESSION['arme'].'" onmouseover="affiche_art(\'infoa\',true);" onmouseout="affiche_art(\'infoa\',false);"><img border="0" src="http://v2.dreadcast.net/ingame/im_objets/'.$image.'"'); if(ereg("centre/",$image)) print('width="73" height="73" style="border:1px solid #000;"'); print('></a></p>');
			}
		else
			{
			print('<p id="arme"><img border="0" src="http://v2.dreadcast.net/ingame/im_objets/'.$image.'"'); if(ereg("centre/",$image)) print('width="73" height="73" style="border:1px solid #000;"'); print('></p>');
			}
		}
	elseif($act=="envoyer")
		{
		if($_SESSION['arme']!="Aucune")
			{
			print('<p id="arme"><a href="engine=inv-lieu.php?arme" onmouseover="affiche_art(\'infoa\',true);" onmouseout="affiche_art(\'infoa\',false);"><img border="0" src="http://v2.dreadcast.net/ingame/im_objets/'.$image.'"'); if(ereg("centre/",$image)) print('width="73" height="73" style="border:1px solid #000;"'); print('></a></p>');
			}
		else
			{
			print('<p id="arme"><img border="0" src="http://v2.dreadcast.net/ingame/im_objets/'.$image.'"'); if(ereg("centre/",$image)) print('width="73" height="73" style="border:1px solid #000;"'); print('></p>');
			}
		}
	elseif($act=="infos")
		{
		print('<p id="arme"><a href="http://v2.dreadcast.net/info=objet.php?'.strtolower($_SESSION['arme']).'" target="_blank"onmouseover="affiche_art(\'infoa\',true);" onmouseout="affiche_art(\'infoa\',false);"><img border="0" src="http://v2.dreadcast.net/ingame/im_objets/'.$image.'"'); if(ereg("centre/",$image)) print('width="73" height="73" style="border:1px solid #000;"'); print('></a></p>');
		}
	elseif($act=="det")
		{
		if($_SESSION['arme']!="Aucune")
			{
			print('<p id="arme"><a href="engine=det.php?arme"><img border="0" src="http://v2.dreadcast.net/ingame/im_objets/'.$image.'" onmouseover="affiche_art(\'infoa\',true);" onmouseout="affiche_art(\'infoa\',false);"'); if(ereg("centre/",$image)) print('width="73" height="73" style="border:1px solid #000;"'); print('></a></p>');
			}
		else
			{
			print('<p id="arme"><img border="0" src="http://v2.dreadcast.net/ingame/im_objets/'.$image.'"'); if(ereg("centre/",$image)) print('width="73" height="73" style="border:1px solid #000;"'); print('></p>');
			}
		}
	}

$sql = 'SELECT image FROM objets_tbl WHERE nom= "'.$_SESSION['vetements'].'"' ;
$req = mysql_query($sql);
$image = mysql_result($req,0,image); 

if($act=="infos") { print('<p id="vetement"><a href="http://v2.dreadcast.net/info=objet.php?'.strtolower($_SESSION['vetements']).'" target="_blank" onmouseover="affiche_art(\'infov\',true);" onmouseout="affiche_art(\'infov\',false);"><img border="0" src="http://v2.dreadcast.net/ingame/im_objets/'.$image.'" '); if(ereg("centre/",$image)) print('width="73" height="73" style="border:1px solid #000;"'); print('></a></p>'); }
else { print('<p id="vetement"><img border="0" src="http://v2.dreadcast.net/ingame/im_objets/'.$image.'" '); if(ereg("centre/",$image)) print('width="73" height="73" style="border:1px solid #000;"'); print('></p>'); }

$sql = 'SELECT image FROM objets_tbl WHERE nom= "'.$_SESSION['objet'].'"' ;
$req = mysql_query($sql);
$image = mysql_result($req,0,image); 

if($act=="utiliser"&&$_SESSION['objet']!="Aucun") { print('<p id="objet"><a href="engine=desequipo.php?'.$_SESSION['objet'].'" onmouseover="affiche_art(\'infoo\',true);affiche_art(\'effet\',false);affiche_art(\'pasdobjeto\',true);affiche_art(\'def\',false);affiche_art(\'pasddef\',true);" onmouseout="affiche_art(\'infoo\',false);affiche_art(\'effet\',true);affiche_art(\'pasdobjeto\',false);affiche_art(\'def\',true);affiche_art(\'pasddef\',false);"><img border="0" src="http://v2.dreadcast.net/ingame/im_objets/'.$image.'"'); if(ereg("centre/",$image)) print('width="73" height="73" style="border:1px solid #000;"'); print('></a></p>'); }
elseif($act=="envoyer"&&$_SESSION['objet']!="Aucun") { print('<p id="objet"><a href="engine=inv-lieu.php?objet" onmouseover="affiche_art(\'infoo\',true);affiche_art(\'effet\',false);affiche_art(\'pasdobjeto\',true);affiche_art(\'def\',false);affiche_art(\'pasddef\',true);" onmouseout="affiche_art(\'infoo\',false);affiche_art(\'effet\',true);affiche_art(\'pasdobjeto\',false);affiche_art(\'def\',true);affiche_art(\'pasddef\',false);"><img border="0" src="http://v2.dreadcast.net/ingame/im_objets/'.$image.'"'); if(ereg("centre/",$image)) print('width="73" height="73" style="border:1px solid #000;"'); print('></a></p>'); }
elseif($act=="infos"&&$_SESSION['objet']!="Aucun") { print('<p id="objet"><a href="http://v2.dreadcast.net/info=objet.php?'.strtolower($_SESSION['objet']).'" target="_blank" onmouseover="affiche_art(\'infoo\',true);" onmouseout="affiche_art(\'infoo\',false);"><img border="0" src="http://v2.dreadcast.net/ingame/im_objets/'.$image.'"'); if(ereg("centre/",$image)) print('width="73" height="73" style="border:1px solid #000;"'); print('></a></p>'); }
elseif($act=="det"&&$_SESSION['objet']!="Aucun") { print('<p id="objet"><a href="engine=det.php?objet" onmouseover="affiche_art(\'infoo\',true);affiche_art(\'effet\',false);affiche_art(\'pasdobjeto\',true);affiche_art(\'def\',false);affiche_art(\'pasddef\',true);" onmouseout="affiche_art(\'infoo\',false);affiche_art(\'effet\',true);affiche_art(\'pasdobjeto\',false);affiche_art(\'def\',true);affiche_art(\'pasddef\',false);"><img border="0" src="http://v2.dreadcast.net/ingame/im_objets/'.$image.'"'); if(ereg("centre/",$image)) print('width="73" height="73" style="border:1px solid #000;"'); print('></a></p>'); }

for($i=1; $i != 7 ; $i++) 
	{
	$sql = 'SELECT image,url,nom FROM objets_tbl WHERE nom= "'.$_SESSION['case'.$i.''].'"' ;
	$req = mysql_query($sql);
	if(mysql_num_rows($req) == 0) print('Une erreur s\'est produite. Veuillez contacter un administrateur, en précisant le libéllé suivant : Objet inventaire "'.$_SESSION['case'.$i.''].'" sur '.$_SERVER["HTTP_REFERER"]);
	else
		{
		$image = mysql_result($req,0,image); 
		$url = mysql_result($req,0,url);
		}
	
	if($act=="utiliser")
		{
		if(($url!="") && ($url!=".php"))
			{
			print('<p id="emp'.$i.'"><a href="'.$url.'" onmouseover="affiche_art(\'info'.$i.'\',true);affiche_art(\'att\',false);affiche_art(\'possible'.$i.'\',true);affiche_art(\'effet\',false);affiche_art(\'posseffet'.$i.'\',true);affiche_art(\'def\',false);affiche_art(\'possdef'.$i.'\',true);" onmouseout="affiche_art(\'info'.$i.'\',false);affiche_art(\'att\',true);affiche_art(\'possible'.$i.'\',false);affiche_art(\'effet\',true);affiche_art(\'posseffet'.$i.'\',false);affiche_art(\'def\',true);affiche_art(\'possdef'.$i.'\',false);"><img border="0" src="http://v2.dreadcast.net/ingame/im_objets/'.$image.'"'); if(ereg("centre/",$image)) print('width="73" height="73" style="border:1px solid black;"'); print('></a></p>');
			}
		if(($url=="") || ($url==".php"))
			{
			if($_SESSION['case'.$i.'']=="Montre")
				{
				if ((event(2) || adm())) {
					$sql2 = 'SELECT id FROM objets_repares_tbl WHERE id_cible = '.$_SESSION['id'];
					$req2 = mysql_query($sql2);
					if (!mysql_num_rows($req2)) {
						$noWay = true;
					}
				}
				print('<p id="emp'.$i.'"><img border="0" src="http://v2.dreadcast.net/ingame/im_objets/montre.php?nw='.$noWay.'"></p>');
				}
			elseif($_SESSION['case'.$i.'']=="Machine")
				{
				print('<p id="emp'.$i.'"><img border="0" src="http://v2.dreadcast.net/ingame/im_objets/malette.gif"></p>');
				}
			else
				{
				print('<p id="emp'.$i.'"><img border="0" src="http://v2.dreadcast.net/ingame/im_objets/'.$image.'"'); if(ereg("centre/",$image)) print('width="73" height="73" style="border:1px solid black;"'); print('></p>');
				}
			}
		}
	elseif($act=="envoyer")
		{
		if($_SESSION['case'.$i.'']!="Vide")
			{
			if($_SESSION['case'.$i.'']=="Montre")
				{
				print('<p id="emp'.$i.'"><a href="engine=inv-lieu.php?'.$i.'" onmouseover="affiche_art(\'info'.$i.'\',true);" onmouseout="affiche_art(\'info'.$i.'\',false);"><img border="0" src="http://v2.dreadcast.net/ingame/im_objets/montre.php"></a></p>');
				}
			elseif($_SESSION['case'.$i.'']=="Machine")
				{
				print('<p id="emp'.$i.'"><a href="engine=inv-lieu.php?'.$i.'" onmouseover="affiche_art(\'info'.$i.'\',true);" onmouseout="affiche_art(\'info'.$i.'\',false);"><img border="0" src="http://v2.dreadcast.net/ingame/im_objets/malette.gif"></a></p>');
				}
			elseif($_SESSION['case'.$i.'']=="Bague de Haut Dignitaire")
				{
				print('<p id="emp'.$i.'"><img border="0" src="http://v2.dreadcast.net/ingame/im_objets/'.$image.'"'); if(ereg("centre/",$image)) print('width="73" height="73" style="border:1px solid black;"'); print('></p>');
				}
			else
				{
				print('<p id="emp'.$i.'"><a href="engine=inv-lieu.php?'.$i.'" onmouseover="affiche_art(\'info'.$i.'\',true);" onmouseout="affiche_art(\'info'.$i.'\',false);"><img border="0" src="http://v2.dreadcast.net/ingame/im_objets/'.$image.'"'); if(ereg("centre/",$image)) print('width="73" height="73" style="border:1px solid black;"'); print('></a></p>');
				}
			}
		else
			{
			print('<p id="emp'.$i.'"><img border="0" src="http://v2.dreadcast.net/ingame/im_objets/'.$image.'"'); if(ereg("centre/",$image)) print('width="73" height="73" style="border:1px solid black;"'); print('></p>');
			}
		}
	elseif($act=="infos")
		{
		if($_SESSION['case'.$i.'']=="Montre")
			{
			print('<p id="emp'.$i.'"><a href="http://v2.dreadcast.net/info=objet.php?'.strtolower($_SESSION['case'.$i.'']).'" target="_blank" onmouseover="affiche_art(\'info'.$i.'\',true);" onmouseout="affiche_art(\'info'.$i.'\',false);"><img border="0" src="http://v2.dreadcast.net/ingame/im_objets/montre.php"></a></p>');
			}
		elseif($_SESSION['case'.$i.'']=="Machine")
			{
			print('<p id="emp'.$i.'"><a href="http://v2.dreadcast.net/info=objet.php?'.strtolower($_SESSION['case'.$i.'']).'" target="_blank" onmouseover="affiche_art(\'info'.$i.'\',true);" onmouseout="affiche_art(\'info'.$i.'\',false);"><img border="0" src="http://v2.dreadcast.net/ingame/im_objets/malette.gif"></a></p>');
			}
		else
			{
			print('<p id="emp'.$i.'"><a href="http://v2.dreadcast.net/info=objet.php?'.strtolower($_SESSION['case'.$i.'']).'" target="_blank" onmouseover="affiche_art(\'info'.$i.'\',true);" onmouseout="affiche_art(\'info'.$i.'\',false);"><img border="0" src="http://v2.dreadcast.net/ingame/im_objets/'.$image.'"'); if(ereg("centre/",$image)) print('width="73" height="73" style="border:1px solid black;"'); print('></a></p>');
			}
		}
	elseif($act=="det")
		{
		if($_SESSION['case'.$i.'']!="Vide")
			{
			if($_SESSION['case'.$i.'']=="Montre")
				{
				print('<p id="emp'.$i.'"><a href="engine=det.php?'.$i.'" onmouseover="affiche_art(\'info'.$i.'\',true);" onmouseout="affiche_art(\'info'.$i.'\',false);"><img border="0" src="http://v2.dreadcast.net/ingame/im_objets/montre.php"></a></p>');
				}
			elseif($_SESSION['case'.$i.'']=="Machine")
				{
				print('<p id="emp'.$i.'"><a href="engine=det.php?'.$i.'" onmouseover="affiche_art(\'info'.$i.'\',true);" onmouseout="affiche_art(\'info'.$i.'\',false);"><img border="0" src="http://v2.dreadcast.net/ingame/im_objets/malette.gif"></a></p>');
				}
			else
				{
				print('<p id="emp'.$i.'"><a href="engine=det.php?'.$i.'" onmouseover="affiche_art(\'info'.$i.'\',true);" onmouseout="affiche_art(\'info'.$i.'\',false);"><img border="0" src="http://v2.dreadcast.net/ingame/im_objets/'.$image.'"'); if(ereg("centre/",$image)) print('width="73" height="73" style="border:1px solid black;"'); print('></a></p>');
				}
			}
		else
			{
			print('<p id="emp'.$i.'"><img border="0" src="http://v2.dreadcast.net/ingame/im_objets/'.$image.'"'); if(ereg("centre/",$image)) print('width="73" height="73" style="border:1px solid black;"'); print('></p>');
			}
		}
	elseif(ereg("sac",$act))
		{
		if($_SESSION['case'.$i.'']!="Vide")
			{
			if($_SESSION['case'.$i.'']=="Montre")
				{
				print('<p id="emp'.$i.'"><a href="engine=sactis.php?idc='.$i.'&ido='.$_SESSION['case'.$act[3]].'" onmouseover="affiche_art(\'info'.$i.'\',true);" onmouseout="affiche_art(\'info'.$i.'\',false);"><img border="0" src="http://v2.dreadcast.net/ingame/im_objets/montre.php"></a></p>');
				}
			elseif($_SESSION['case'.$i.'']=="Machine")
				{
				print('<p id="emp'.$i.'"><a href="engine=sactis.php?idc='.$i.'&ido='.$_SESSION['case'.$act[3]].'" onmouseover="affiche_art(\'info'.$i.'\',true);" onmouseout="affiche_art(\'info'.$i.'\',false);"><img border="0" src="http://v2.dreadcast.net/ingame/im_objets/malette.gif"></a></p>');
				}
			else
				{
				print('<p id="emp'.$i.'"><a href="engine=sactis.php?idc='.$i.'&ido='.$_SESSION['case'.$act[3]].'" onmouseover="affiche_art(\'info'.$i.'\',true);" onmouseout="affiche_art(\'info'.$i.'\',false);"><img border="0" src="http://v2.dreadcast.net/ingame/im_objets/'.$image.'"'); if(ereg("centre/",$image)) print('width="73" height="73" style="border:1px solid black;"'); print('></a></p>');
				}
			}
		else
			{
			print('<p id="emp'.$i.'"><img border="0" src="http://v2.dreadcast.net/ingame/im_objets/'.$image.'"'); if(ereg("centre/",$image)) print('width="73" height="73" style="border:1px solid black;"'); print('></p>');
			}
		}
	}
mysql_close($db);
?>
	
		</div>		  
		  

</div>
<?php if($chat=="oui") 	{ if ((date("H")>=21) || (date("H")<=7)) { include("inc_bas_n_c.php"); } else { include("inc_bas_j_c.php"); } } else {	if ((date("H")>=21) || (date("H")<=7)) { include("inc_bas_n.php"); } else { include("inc_bas_j.php"); }	} ?>
<?php include("inc_bas_de_page.php"); ?>
