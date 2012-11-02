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

$sql = 'SELECT type FROM entreprises_tbl WHERE rue= "'.$_SESSION['lieu'].'" AND num= "'.$_SESSION['num'].'"' ;
$req = mysql_query($sql);
$type = mysql_result($req,0,type);

if($type!="CIPE")
	{
	print('<meta http-equiv="refresh" content="0 ; url=http://v2.dreadcast.net/ingame/engine.php"> ');
	exit();
	}

mysql_close($db);

$typejob=(empty($_POST['typejob']))?"tous":$_POST['typejob'];
$lieujob=(empty($_POST['lieujob']))?"tous":$_POST['lieujob'];

$typejob=(empty($_GET['typejob']))?$typejob:str_replace('\'','\\\'',str_replace('\\','',$_GET['typejob']));//str_replace('&Atilde;&nbsp;','à',str_replace('&Atilde;&copy;','é',str_replace('&Atilde;&uml;','è',str_replace('&Atilde;&acute;','ô',htmlentities($_GET['typejob'])))));
$lieujob=(empty($_GET['lieujob']))?$lieujob:str_replace('\'','\\\'',str_replace('\\','',$_GET['lieujob']));//str_replace('&Atilde;&nbsp;','à',str_replace('&Atilde;&copy;','é',str_replace('&Atilde;&uml;','è',str_replace('&Atilde;&acute;','ô',htmlentities($_GET['lieujob'])))));

?>
<?php if ((date("H")>=21) || (date("H")<=7)) { include("inc_haut_n.php"); } else { include("inc_haut_j.php");} ?>

<div id="haut">
	<?php include("inc_entete.php"); ?>
	<div class="haut_aligngauche">
		<p>
		<div class="titrepage">
			Liste des emplois
		</div>
		<b class="module4ie"><a href="engine.php" class="module4"><img src="im_objets/icon_retour.gif" alt="Retour" />Retour</a></b>
	</p>
	</div>
</div>
<div id="centre_imperium">
<p id="selectionner">
	<form action="engine=emplois.php" method="post" name="btype" id="leform">
		<select name="typejob" id="leselect2" onchange="submit();">
			<option value="tous"<?php if($typejob=="tous")print(' selected="selected"'); ?>>Tous</option>
			<?php 
			$db = mysql_connect('localhost', 'CENSURE', 'CENSURE') or die("Erreur de connexion au serveur des messages.");
			mysql_select_db('CENSURE',$db) or die("Erreur de connexion a la base de donnees des messages.");

			if($lieujob=="tous")
				{
				$sql = 'SELECT nom FROM entreprises_tbl' ;
				$req = mysql_query($sql);
				$res = mysql_num_rows($req);
				}
			else
				{
				if($lieujob=="au_CIE") $temp="CIE";
				elseif($lieujob=="au_CIPE") $temp="CIPE";
				elseif($lieujob=="au_DI2RCO") $temp="di2rco";
				elseif($lieujob=="aux_services_techniques_de_la_ville") $temp="proprete";
				elseif($lieujob=="à_la_prison") $temp="prison";
				elseif($lieujob=="à_la_police") $temp="police";
				elseif($lieujob=="dans_une_usine_de_production") $temp="usine de production";
				elseif($lieujob=="dans_une_boutique_spécialisée") $temp="boutique spécialisee";
				elseif($lieujob=="dans_un_hall_des_enchères") $temp="ventes aux encheres";
				elseif($lieujob=="dans_une_boutique_d\'armes") $temp="boutique armes";
				elseif($lieujob=="dans_un_hôpital") $temp="hopital";
				elseif($lieujob=="dans_un_bar") $temp="bar cafe";
				elseif($lieujob=="dans_une_banque") $temp="banque";
				elseif($lieujob=="dans_une_agence_immobiliaire") $temp="agence immobiliaire";
				elseif($lieujob=="à_l\'impériale_des_jeux") $temp="jeux";
				elseif($lieujob=="à_la_DOI") $temp="DOI";
				elseif($lieujob=="à_la_Chambre_des_Lois") $temp="chambre";
				elseif($lieujob=="au_Conseil_Impérial") $temp="conseil";
				elseif($lieujob=="dans_une_entreprise_particulière") $temp="aucun";
				elseif($lieujob=="dans_un_centre_de_recherche") $temp="centre de recherche";
				elseif($lieujob=="au_DC_Network") $temp="dcn";
				else;
				
				$sql = 'SELECT nom FROM entreprises_tbl WHERE type="'.$temp.'"' ;
				$req = mysql_query($sql);
				$res = mysql_num_rows($req);
				}
			
			$tab=array();
			
			for($i=0 ; $i != $res ; $i++)
				{
				$nomr = mysql_result($req,$i,nom);
				$sql1 = 'SELECT nbreactuel,nbrepostes,type FROM `e_'.str_replace(" ","_",''.$nomr.'').'_tbl`' ;
				$req1 = mysql_query($sql1);
				$res1 = mysql_num_rows($req1);
				
				for($p=0; $p < $res1 ; $p++)
					{
					$nbrepostesr = mysql_result($req1,$p,nbrepostes);
					$nbreactuelr = mysql_result($req1,$p,nbreactuel);
					$pd = $nbrepostesr - $nbreactuelr;print($nomr.':'.$pd.';');
					if($pd>0)
						{
						$tab[$p]=mysql_result($req1,$p,type);
						if($tab[$p]=="chef")$tab[$p]="Chef";
						elseif($tab[$p]=="directeur")$tab[$p]="Directeur";
						elseif($tab[$p]=="maintenance")$tab[$p]="Maintenance";
						elseif($tab[$p]=="medecin")$tab[$p]="M&eacute;decin";
						elseif($tab[$p]=="securite")$tab[$p]="S&eacute;curit&eacute;";
						elseif($tab[$p]=="technicien")$tab[$p]="Technicien";
						elseif($tab[$p]=="serveur")$tab[$p]="Serveur";
						elseif($tab[$p]=="vendeur")$tab[$p]="Vendeur";
						elseif($tab[$p]=="autre")$tab[$p]="Autre";
						elseif($tab[$p]=="banquier")$tab[$p]="Banquier";
						elseif($tab[$p]=="profmeca"||$tab[$p]=="proftechno"||$tab[$p]=="profgestion"||$tab[$p]=="profeco"||$tab[$p]=="profconduite"||$tab[$p]=="profcombat"||$tab[$p]=="profmed"||$tab[$p]=="proftir")$tab[$p]="Professeur";
						elseif($tab[$p]=="hote")$tab[$p]="H&ocirc;te";
						elseif($tab[$p]=="reparateur")$tab[$p]="R&eacute;parateur";
						elseif($tab[$p]=="chercheur")$tab[$p]="Chercheur";
						elseif($tab[$p]=="chauffeur")$tab[$p]="Chauffeur";
						else;
						
						}
					}
				}
			sort($tab);
						
			for($i=0 ; $i != sizeof($tab) ; $i++)
				{
				if($i==0||$ancientype!=$tab[$i]) 
					{
					$ancientype=$tab[$i];
					print('<option value="'.htmlentities(str_replace(" ","_",$tab[$i])).'"');if($typejob==str_replace(" ","_",$tab[$i]))print(' selected="selected"');print('>');
					print($tab[$i]);
					print('</option>
				');
					}
				}
				mysql_close($db);
			?>
		</select>
		<select name="lieujob" id="leselect3" onchange="submit();">
			<option value="tous"<?php if($lieujob=="tous")print(' selected="selected"'); ?>>Tous</option>
			<?php 
			$db = mysql_connect('localhost', 'CENSURE', 'CENSURE') or die("Erreur de connexion au serveur des messages.");
			mysql_select_db('CENSURE',$db) or die("Erreur de connexion a la base de donnees des messages.");

			$sql = 'SELECT nom,type FROM entreprises_tbl' ;
			$req = mysql_query($sql);
			$res = mysql_num_rows($req);
			
			$tab2=array();
			
			for($i=0 ; $i != $res ; $i++)
				{
				$nomr = mysql_result($req,$i,nom);
				$sql1 = 'SELECT nbreactuel,nbrepostes,type FROM `e_'.str_replace(" ","_",''.$nomr.'').'_tbl`' ;
				$req1 = mysql_query($sql1);
				$res1 = mysql_num_rows($req1);
				for($p=0; $p != $res1 ; $p++)
					{
					$nbrepostesr = mysql_result($req1,$p,nbrepostes);
					$nbreactuelr = mysql_result($req1,$p,nbreactuel);
					$pd = $nbrepostesr - $nbreactuelr;
					if($pd>0)
						{
						if($typejob=="tous")
							{
							$tab2[$i]=mysql_result($req,$i,type);
							}
						else
							{
							$temp2=mysql_result($req1,$p,type);
							if($temp2=="chef")$temp="Chef";
							elseif($temp2=="directeur")$temp="Directeur";
							elseif($temp2=="maintenance")$temp="Maintenance";
							elseif($temp2=="medecin")$temp="M&eacute;decin";
							elseif($temp2=="securite")$temp="S&eacute;curit&eacute;";
							elseif($temp2=="technicien")$temp="Technicien";
							elseif($temp2=="serveur")$temp="Serveur";
							elseif($temp2=="vendeur")$temp="Vendeur";
							elseif($temp2=="autre")$temp="Autre";
							elseif($temp2=="banquier")$temp="Banquier";
							elseif($temp2=="profmeca"||$temp2=="proftechno"||$temp2=="profgestion"||$temp2=="profeco"||$temp2=="profconduite"||$temp2=="profcombat"||$temp2=="profmed"||$temp2=="proftir")$temp="Professeur";
							elseif($temp2=="hote")$temp="H&ocirc;te";
							elseif($temp2=="reparateur")$temp="R&eacute;parateur";
							elseif($temp2=="chercheur")$temp="Chercheur";
							elseif($temp2=="chauffeur")$temp="Chauffeur";
							else;
							
							if($typejob==$temp)
								{
								$tab2[$i]=mysql_result($req,$i,type);
								}
							}
						if($tab2[$i]=="CIE")$tab2[$i]="au CIE";
						elseif($tab2[$i]=="CIPE")$tab2[$i]="au CIPE";
						elseif($tab2[$i]=="di2rco")$tab2[$i]="au DI2RCO";
						elseif($tab2[$i]=="proprete")$tab2[$i]="aux services techniques de la ville";
						elseif($tab2[$i]=="prison")$tab2[$i]="à la prison";
						elseif($tab2[$i]=="police")$tab2[$i]="à la police";
						elseif($tab2[$i]=="usine de production")$tab2[$i]="dans une usine de production";
						elseif($tab2[$i]=="boutique spécialisee")$tab2[$i]="dans une boutique spécialisée";
						elseif($tab2[$i]=="ventes aux encheres")$tab2[$i]="dans un hall des enchères";
						elseif($tab2[$i]=="boutique armes")$tab2[$i]="dans une boutique d'armes";
						elseif($tab2[$i]=="hopital")$tab2[$i]="dans un hôpital";
						elseif($tab2[$i]=="bar cafe")$tab2[$i]="dans un bar";
						elseif($tab2[$i]=="banque")$tab2[$i]="dans une banque";
						elseif($tab2[$i]=="agence immobiliaire")$tab2[$i]="dans une agence immobiliaire";
						elseif($tab2[$i]=="jeux")$tab2[$i]="&agrave; l'impériale des jeux";
						elseif($tab2[$i]=="DOI")$tab2[$i]="à la DOI";
						elseif($tab2[$i]=="chambre")$tab2[$i]="à la Chambre des Lois";
						elseif($tab2[$i]=="conseil")$tab2[$i]="au Conseil Impérial";
						elseif($tab2[$i]=="aucun")$tab2[$i]="dans une entreprise particulière";
						elseif($tab2[$i]=="centre de recherche")$tab2[$i]="dans un centre de recherche";
						elseif($tab2[$i]=="dcn")$tab2[$i]="au DC Network";
						else;
						}
					}
				}
			sort($tab2);
				
			for($i=0 ; $i != sizeof($tab2) ; $i++)
				{
				if($i==0||$ancientype!=$tab2[$i]) 
					{
					$ancientype=$tab2[$i];
					print('<option value="'.htmlentities(str_replace(" ","_",$tab2[$i])).'"');if($tab2[$i]==stripslashes(str_replace("_"," ",$lieujob)))print(' selected="selected"');print('>');
					print(htmlentities($tab2[$i]));
					print('</option>
				');
					}
				}
				mysql_close($db);
			?>
		</select>
	</form>
</p>

<br /><br /><br /><div id="boutique">


<?php 
$db = mysql_connect('localhost', 'CENSURE', 'CENSURE') or die("Erreur de connexion au serveur.");
mysql_select_db('CENSURE',$db) or die("Erreur de connexion a la base de donnees.");

if($typejob!="choisir"&&$lieujob!="choisir")
{

if($lieujob=="tous")
	{
	$sql = 'SELECT nom,num,rue FROM entreprises_tbl' ;
	$req = mysql_query($sql);
	$res = mysql_num_rows($req);
	}
else
	{
	if($lieujob=="au_CIE") $temp="CIE";
	elseif($lieujob=="au_CIPE") $temp="CIPE";
	elseif($lieujob=="au_DI2RCO") $temp="di2rco";
	elseif($lieujob=="aux_services_techniques_de_la_ville") $temp="proprete";
	elseif($lieujob=="à_la_prison") $temp="prison";
	elseif($lieujob=="à_la_police") $temp="police";
	elseif($lieujob=="dans_une_usine_de_production") $temp="usine de production";
	elseif($lieujob=="dans_une_boutique_spécialisée") $temp="boutique spécialisee";
	elseif($lieujob=="dans_un_hall_des_enchères") $temp="ventes aux encheres";
	elseif($lieujob=="dans_une_boutique_d\'armes") $temp="boutique armes";
	elseif($lieujob=="dans_un_hôpital") $temp="hopital";
	elseif($lieujob=="dans_un_bar") $temp="bar cafe";
	elseif($lieujob=="dans_une_banque") $temp="banque";
	elseif($lieujob=="dans_une_agence_immobiliaire") $temp="agence immobiliaire";
	elseif($lieujob=="à_l\'imp&eacute;rial_des_jeux") $temp="jeux";
	elseif($lieujob=="à_la_DOI") $temp="DOI";
	elseif($lieujob=="à_la_Chambre_des_Lois") $temp="chambre";
	elseif($lieujob=="au_Conseil_Impérial") $temp="conseil";
	elseif($lieujob=="dans_une_entreprise_particulière") $temp="aucun";
	elseif($lieujob=="dans_un_centre_de_recherche") $temp="centre de recherche";
	elseif($lieujob=="au_DC_Network") $temp="dcn";
	else;
				
	$sql = 'SELECT nom,num,rue FROM entreprises_tbl WHERE type="'.$temp.'"' ;
	$req = mysql_query($sql);
	$res = mysql_num_rows($req);
	}

print('<table cellpadding="0" cellspacing="0">');

$t = 0;
for($i = 0 ; $i != $res ; $i++)
	{
	$nomr = mysql_result($req,$i,nom);
	$numr = mysql_result($req,$i,num);
	$ruer = mysql_result($req,$i,rue);
	$sql1 = 'SELECT type,poste,nbrepostes,nbreactuel,salaire,bdd,mincomp,candidature,mintrav,sinon,bonus FROM `e_'.str_replace(" ","_",''.$nomr.'').'_tbl`' ;
	$req1 = mysql_query($sql1);
	$res1 = mysql_num_rows($req1);
	$m = 0;	
	
	for($p=0; $p != $res1 ; $p++)
		{
		$poster = mysql_result($req1,$p,poste);
		$nbrepostesr = mysql_result($req1,$p,nbrepostes);
		$nbreactuelr = mysql_result($req1,$p,nbreactuel);
		
		$typer = mysql_result($req1,$p,type);
		$salairer = mysql_result($req1,$p,salaire);
		$bddr = mysql_result($req1,$p,bdd);
		$mincompr = mysql_result($req1,$p,mincomp);
		$candidaturer = mysql_result($req1,$p,candidature);
		$mintravr = mysql_result($req1,$p,mintrav);
		$sinonr = mysql_result($req1,$p,sinon);
		$bonusr = mysql_result($req1,$p,bonus);
		
		$pd = $nbrepostesr - $nbreactuelr;
		if($pd>0)
			{
			
			$typeo=&mysql_result($req1,$p,type);
	
			if($typeo=="chef")$temp="Chef";
			elseif($typeo=="directeur")$temp="Directeur";
			elseif($typeo=="maintenance")$temp="Maintenance";
			elseif($typeo=="medecin")$temp="M&eacute;decin";
			elseif($typeo=="securite")$temp="S&eacute;curit&eacute;";
			elseif($typeo=="technicien")$temp="Technicien";
			elseif($typeo=="serveur")$temp="Serveur";
			elseif($typeo=="vendeur")$temp="Vendeur";
			elseif($typeo=="autre")$temp="Autre";
			elseif($typeo=="banquier")$temp="Banquier";
			elseif($typeo=="profmeca"||$typeo=="proftechno"||$typeo=="profgestion"||$typeo=="profeco"||$typeo=="profconduite"||$typeo=="profcombat"||$typeo=="profmed"||$typeo=="proftir")$temp="Professeur";
			elseif($typeo=="hote")$temp="H&ocirc;te";
			elseif($typeo=="reparateur")$temp="R&eacute;parateur";
			elseif($typeo=="chercheur")$temp="Chercheur";
			elseif($typeo=="chauffeur")$temp="Chauffeur";
			else;
			
			if(str_replace(" ","_",$temp)==$typejob||$typejob=="tous")
			{
			$m = 1;
			if($t/2 == round($t/2))
				{
				print('<tr class="color1">');
				}
			else
				{
				print('<tr class="color2">');
				}
			print('<td style="padding:5px 0 5px 0;"><div align="center"><strong>'.$nomr.'</strong><br /><span style="color:#999;line-height:20px;">'.(($nomr != 'DI2RCO')?$numr.' '.$ruer.' [S'.secteur($numr,$ruer).']':'Secret Impérial').'</span></div></td><td><div align="center"><a href="engine=emploisid.php?entreprise='.$nomr.'&poste='.$poster.'&type='.$typeo.'&typejob='.$typejob.'&lieujob='.$lieujob.'" class="lien">'.$poster.'</a><br /><span style="color:#999;line-height:20px;">'.(($candidaturer == "can")?'Nécessite une candidature':'Sans candidature').'</span></div></td>');
			print('</tr>');
			}
			}
		}
	if($m==1)
		{
		$t = $t + 1;
		}
	}

print('</table>');
}
else print('<p id="textelse2"><b>Voici les postes &agrave; pourvoir dans les entreprises de la ville.</b><br />Choississez le type d\'emploi que vous recherchez.</p>');
mysql_close($db);

?>


</div>
</div>
<?php if($chat=="oui") 	{ if ((date("H")>=21) || (date("H")<=7)) { include("inc_bas_n_c.php"); } else { include("inc_bas_j_c.php"); } } else {	if ((date("H")>=21) || (date("H")<=7)) { include("inc_bas_n.php"); } else { include("inc_bas_j.php"); }	} ?>
<?php include("inc_bas_de_page.php"); ?>
