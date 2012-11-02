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
			Carnet d'adresses
		</div>
		<b class="module4ie"><a <?php if($_GET['groupe']=="" AND $_GET['contact']=="" AND $_GET['adresse']=="") print('href="engine=inventaire.php"'); else print('href="engine=carnet.php?affiche='.$_GET['affiche'].'"'); ?> class="module4"><img src="im_objets/icon_retour.gif" alt="Retour" />Retour</a></b>
</p>
	</div>
</div>
<div id="centre_carnet">
<p>


<?php 
$db = mysql_connect('localhost', 'CENSURE', 'CENSURE') or die("Erreur de connexion au serveur des messages.");
mysql_select_db('CENSURE',$db) or die("Erreur de connexion a la base de donnees des messages.");

$sql = 'SELECT case1,case2,case3,case4,case5,case6 FROM principal_tbl WHERE id= "'.$_SESSION['id'].'"' ;
$req = mysql_query($sql);
$_SESSION['case1'] = mysql_result($req,0,case1);
$_SESSION['case2'] = mysql_result($req,0,case2);
$_SESSION['case3'] = mysql_result($req,0,case3);
$_SESSION['case4'] = mysql_result($req,0,case4);
$_SESSION['case5'] = mysql_result($req,0,case5);
$_SESSION['case6'] = mysql_result($req,0,case6);

if(($_SESSION['statut']=="Administrateur") || ($_SESSION['case1']=="Carnet") || ($_SESSION['case2']=="Carnet") || ($_SESSION['case3']=="Carnet") || ($_SESSION['case4']=="Carnet") || ($_SESSION['case5']=="Carnet") || ($_SESSION['case6']=="Carnet"))
	{
	
	//======================================= GESTION DES CAS =======================================//
	
	if($_POST['type']=="ajoutg" AND $_POST['nomgroupe']!="") ////////////////// AJOUT D'UN GROUPE //////////////////
		{
		$sql = 'INSERT INTO carnets_tbl(id,pseudo,contact,statut,note) VALUES("","'.$_SESSION['pseudo'].'","'.$_SESSION['pseudo'].'","'.$_POST['nomgroupe'].'","")' ;
		mysql_query($sql);
		}
		
	if($_GET['action']=="supprgr" AND $_GET['confirm']!="") ////////////////// SUPPRESSION D'UN GROUPE //////////////////
		{
		$sql = 'SELECT statut FROM carnets_tbl WHERE pseudo="'.$_SESSION['pseudo'].'" AND id="'.$_GET['confirm'].'"' ;
		$req = mysql_query($sql);
		
		$sql = 'DELETE FROM carnets_tbl WHERE pseudo="'.$_SESSION['pseudo'].'" AND statut="'.mysql_result($req,0,statut).'"' ;
		mysql_query($sql);
		}
		
	if($_GET['action']=="renomgr" AND $_GET['confirm']!="" AND $_POST['nomgroupe']!="") ////////////////// MODIFICATION DU NOM D'UN GROUPE //////////////////
		{
		$sql = 'SELECT statut FROM carnets_tbl WHERE pseudo="'.$_SESSION['pseudo'].'" AND id="'.$_GET['confirm'].'"' ;
		$req = mysql_query($sql);
		
		$sql = 'UPDATE carnets_tbl SET statut="'.$_POST['nomgroupe'].'" WHERE pseudo="'.$_SESSION['pseudo'].'" AND statut="'.mysql_result($req,0,statut).'"' ;
		mysql_query($sql);
		}
		
	if($_GET['action']=="modifgr" AND $_GET['confirm']!="" AND ($_POST['supprco']=="on" OR $_POST['deplaceco']!="")) ////////////////// MODIFICATION DU CONTENU D'UN GROUPE //////////////////
		{
		$sql = 'SELECT statut FROM carnets_tbl WHERE pseudo="'.$_SESSION['pseudo'].'" AND id="'.$_GET['confirm'].'"' ;
		$req = mysql_query($sql);
		
		$sql = 'SELECT id FROM carnets_tbl WHERE pseudo="'.$_SESSION['pseudo'].'" AND statut= "'.mysql_result($req,0,statut).'" ORDER BY id ASC' ;
		$req = mysql_query($sql);
		$res = mysql_num_rows($req);
			
		for($i=0;$i<$res;$i++)
			{
			if($_POST[mysql_result($req,$i,id)]=="on")
				{
				if($_POST['deplaceco']!="")
					{
					$sql = 'UPDATE carnets_tbl SET statut="'.stripslashes($_POST['deplaceco']).'" WHERE id="'.mysql_result($req,$i,id).'"' ;
					mysql_query($sql);
					}
				elseif($_POST['supprco']=="on")
					{
					$sql = 'DELETE FROM carnets_tbl WHERE id="'.mysql_result($req,$i,id).'"' ;
					mysql_query($sql);
					}
				}
			}
		}
		
	if($_GET['action']=="ajoutco" AND $_POST['nomcontact']!="") ////////////////// AJOUT D'UN CONTACT //////////////////
		{
		if($_GET['groupe']!="" OR $_POST['groupe']!="")
			{
			$groupe = ($_GET['groupe']!="")?$_GET['groupe']:$_POST['groupe'];
			$sql = 'SELECT statut FROM carnets_tbl WHERE pseudo="'.$_SESSION['pseudo'].'" AND id="'.$groupe.'"' ;
			$req = mysql_query($sql);
			$res = mysql_num_rows($req);
			if($res!=0) $groupe = mysql_result($req,0,statut);
			
			$sql = 'SELECT id FROM principal_tbl WHERE pseudo="'.$_POST['nomcontact'].'"' ;
			$req = mysql_query($sql);
			$res1 = mysql_num_rows($req);
			
			$sql = 'SELECT id FROM carnets_tbl WHERE pseudo="'.$_SESSION['pseudo'].'" AND contact="'.$_POST['nomcontact'].'"' ;
			$req = mysql_query($sql);
			$res2 = mysql_num_rows($req);
			
			if($res1!=0 AND $res2==0)
				{
				$sql = 'INSERT INTO carnets_tbl(id,pseudo,contact,statut,note) VALUES("","'.$_SESSION['pseudo'].'","'.$_POST['nomcontact'].'","'.$groupe.'","")' ;
				mysql_query($sql);
				}
			}
		}
		
	if($_GET['supprco']!="") ////////////////// SUPPRESSION D'UN CONTACT //////////////////
		{
		$sql = 'DELETE FROM carnets_tbl WHERE pseudo="'.$_SESSION['pseudo'].'" AND contact="'.$_GET['supprco'].'"' ;
		mysql_query($sql);
		}
		
	if((($_GET['adresse']!="" OR $_GET['contact']!="") && $_GET['action']=="ajoutnote") OR ($_GET['affiche']=="note" && $_GET['etat']=="enregistre")) ////////////////// AJOUT D'UNE NOTE //////////////////
		{
		if($_GET['affiche']!="note")
		{
		$valeur = ($_GET['contact']!="")?$_GET['contact']:$_GET['adresse'];
		$table = ($_GET['contact']!="")?"carnets_tbl":"adresses_tbl";
		$type = ($_GET['contact']!="")?"contact":"id";
		
		$sql = 'SELECT id FROM '.$table.' WHERE pseudo="'.$_SESSION['pseudo'].'" AND '.$type.' = "'.$valeur.'"' ;
		$req = mysql_query($sql);
		$res = mysql_num_rows($req);
		
		if($res!=0)
			{
			$note = htmlentities(stripslashes($_POST['note']));
			$sql = 'UPDATE '.$table.' SET note="'.$note.'" WHERE id="'.mysql_result($req,0,id).'"' ;
			mysql_query($sql);
			}
		}
		else
		{
		$sql = 'SELECT id,note FROM carnets_tbl WHERE pseudo="'.$_SESSION['pseudo'].'" AND contact="'.$_SESSION['pseudo'].'" AND statut=""' ;
		$req = mysql_query($sql);
		$res = mysql_num_rows($req);
		
		if($res!=0)
			{
			$note1 = htmlentities(stripslashes($_POST['note1']));
			$note2 = htmlentities(stripslashes($_POST['note2']));
			
			$note = $note1."[SEPARATEUR]".$note2;
			
			$sql = 'UPDATE carnets_tbl SET note="'.$note.'" WHERE id="'.mysql_result($req,0,id).'"' ;
			mysql_query($sql);
			}
		}
		}
		
	if($_GET['action']=="ajoutad" AND $_POST['nomad']!="" AND $_POST['nomnum']!="" AND $_POST['nomnum']>0 AND $_POST['nomrue']!="Rue" AND $_POST['nomrue']!="Ruelle" AND $_POST['nomrue']!="") ////////////////// AJOUT D'UNE ADRESSE //////////////////
		{
		$sql = 'SELECT id FROM lieu_tbl WHERE num="'.$_POST['nomnum'].'" AND rue="'.$_POST['nomrue'].'"' ;
		$req = mysql_query($sql);
		$res1 = mysql_num_rows($req);
			
		$sql = 'SELECT id FROM adresses_tbl WHERE pseudo="'.$_SESSION['pseudo'].'" AND nom="'.$_POST['nomad'].'"' ;
		$req = mysql_query($sql);
		$res2 = mysql_num_rows($req);
			
		if($res1!=0 AND $res2==0)
			{
			$sql = 'INSERT INTO adresses_tbl(id,pseudo,num,rue,nom,contact,note) VALUES("","'.$_SESSION['pseudo'].'","'.$_POST['nomnum'].'","'.$_POST['nomrue'].'","'.htmlentities(stripslashes($_POST['nomad'])).'","'.$_POST['lien'].'","")' ;
			mysql_query($sql);
			}
		}
		
	if($_GET['action']=="modifad" AND $_POST['id']!="" AND $_POST['nomnum']>0 AND $_POST['nomrue']!="Rue" AND $_POST['nomrue']!="Ruelle") ////////////////// MODIFICATION D'UNE ADRESSE //////////////////
		{
		$sql = 'SELECT id FROM lieu_tbl WHERE num="'.$_POST['nomnum'].'" AND rue="'.$_POST['nomrue'].'"' ;
		$req = mysql_query($sql);
		$res1 = mysql_num_rows($req);

		$sql = 'SELECT id FROM adresses_tbl WHERE pseudo="'.$_SESSION['pseudo'].'" AND id="'.$_POST['id'].'"' ;
		$req = mysql_query($sql);
		$res2 = mysql_num_rows($req);
			
		if($res1!=0 AND $res2!=0)
			{
			$sql = 'UPDATE adresses_tbl SET num="'.$_POST['nomnum'].'",rue="'.$_POST['nomrue'].'",nom="'.htmlentities(stripslashes($_POST['nomad'])).'",contact="'.$_POST['lien'].'" WHERE pseudo="'.$_SESSION['pseudo'].'" AND id="'.$_GET['adresse'].'"' ;
			mysql_query($sql);
			}
		}
	
	if($_GET['supprad']!="") ////////////////// SUPPRESSION D'UNE ADRESSE //////////////////
		{
		$sql = 'DELETE FROM adresses_tbl WHERE pseudo="'.$_SESSION['pseudo'].'" AND id="'.$_GET['supprad'].'"' ;
		mysql_query($sql);
		}
	
	$affiche = ($_GET['affiche']=="")?"contacts":$_GET['affiche'];
	
	//======================================= ENTETE PAGE 1 =======================================//
	
	if($affiche=="contacts")
		{
		print('<div id="carnet_titre1">
			<span>Mes contacts</span>
			<a href="engine=carnet.php?affiche=adresses">Mes adresses</a>
		</div>');
		}
	elseif($affiche=="adresses")
		{
		print('<div id="carnet_titre2">
			<a href="engine=carnet.php?affiche=contacts">Mes contacts</a>
			<span>Mes adresses</span>
		</div>');
		}
	elseif($affiche=="note")
		{
		print('<div id="carnet_titre1">
			<a href="engine=carnet.php?affiche=contacts">Mes contacts</a>
			<a href="engine=carnet.php?affiche=adresses">Mes adresses</a>
		</div>');
		}
		
	//======================================= CONTENU PAGE 1 =======================================//
		
	print('<div id="carnet_texte1">');
	
	if($affiche=="contacts")  //-------------------------- CONTACTS --------------------------//
		{
		
		$groupe = $_GET['groupe'];
		
		if($groupe=="")
			{
			print('<div class="titre">
				Mes groupes <span><a href="engine=carnet.php?affiche='.$affiche.'&ordre=statut">A-Z</a> - <a href="engine=carnet.php?affiche='.$affiche.'&ordre=id">Date</a></span>
				</div>
				<div class="texte">
					<table>');
			
			$ordre = ($_GET['ordre']=="")?"statut":$_GET['ordre'];
			
			$sql = 'SELECT DISTINCT statut FROM carnets_tbl WHERE pseudo= "'.$_SESSION['pseudo'].'" AND statut!="" ORDER BY '.$ordre.' ASC' ;
			$req = mysql_query($sql);
			$nbgroupe = mysql_num_rows($req);
			
			$nbcontacts = 0;
			$nbcontactsrec = 0;
			$nbcontactsco = 0;
			$grp = "";
			$grpn = 0;
			$grm = "";
			$grmn = 1000;
			
			for($i=0;$i<$nbgroupe;$i++)
				{
				$groupetmp = mysql_result($req,$i,statut);
				$sql2 = 'SELECT contact FROM carnets_tbl WHERE pseudo= "'.$_SESSION['pseudo'].'" AND statut="'.$groupetmp.'" AND contact!= "'.$_SESSION['pseudo'].'"' ;
				$req2 = mysql_query($sql2);
				if ($req2)
					$nb = mysql_num_rows($req2);
				else
					$nb = 0;
				
				$nbcontacts += $nb;
				if($nb>$grpn) { $grpn = $nb; $grp = $groupetmp; }
				if($nb<$grmn) { $grmn = $nb; $grm = $groupetmp; }
				elseif($nb==$grmn) { if($grm>$groupetmp) { $grmn = $nb; $grm = $groupetmp; } }
				
				$sql3 = 'SELECT id FROM carnets_tbl WHERE pseudo= "'.$_SESSION['pseudo'].'" AND statut="'.$groupetmp.'" AND contact= "'.$_SESSION['pseudo'].'"' ;
				$req3 = mysql_query($sql3);
				if ($req3)
					$res3 = mysql_num_rows($req3);
				else
					$res3 = 0;
				if($res3!=0) $idgroupetmp = mysql_result($req3,0,id);
				
				print('<tr>
							<td class="style1"><a href="engine=carnet.php?affiche='.$affiche.'&groupe='.$idgroupetmp.'">'.$groupetmp.'</a></td><td class="style2">'.$nb.' entr&eacute;e(s)</td>
						</tr>');
				}
			
			$sql3 = 'SELECT contact FROM carnets_tbl WHERE pseudo= "'.$_SESSION['pseudo'].'" AND contact != "'.$_SESSION['pseudo'].'"' ;
			$req3 = mysql_query($sql3);
			$res3 = mysql_num_rows($req3);
				
			for($i=0;$i<$res3;$i++)
				{
				$sql5 = 'SELECT id FROM principal_tbl WHERE pseudo= "'.mysql_result($req3,$i,contact).'" AND connec="oui"' ;
				$req5 = mysql_query($sql5);
				$nb = mysql_num_rows($req5);
				
				$nbcontactsco += $nb;
				
				$sql4 = 'SELECT id FROM carnets_tbl WHERE contact= "'.$_SESSION['pseudo'].'" AND pseudo="'.mysql_result($req3,$i,contact).'"' ;
				$req4 = mysql_query($sql4);
				$nb = mysql_num_rows($req4);
				
				$nbcontactsrec += $nb;
				}
			
			print('</table>
				</div>
				<div class="titre">
				Cr&eacute;er un nouveau groupe
				</div>
				<form action="engine=carnet.php?affiche='.$affiche.'&ordre='.$ordre.'" method="post" class="texte" style="position:relative;top:-3px;left:10px;"><input type="hidden" name="type" value="ajoutg" />
					Nom <input type="text" name="nomgroupe" width="10" class="champ" /> <input type="submit" name="submit" value="Ok" class="valid" />
				</form>');
			}
		else
			{
			
			print('<div class="titre">
				Membres<span><a href="engine=carnet.php?affiche='.$affiche.'&groupe='.$groupe.'&ordre=contact&contact='.$_GET['contact'].'">A-Z</a> - <a href="engine=carnet.php?affiche='.$affiche.'&groupe='.$groupe.'&ordre=id&contact='.$_GET['contact'].'">Date</a></span>
				</div>
				<div class="texte">');
			
			$ordre = ($_GET['ordre']=="")?"contact":$_GET['ordre'];
			
			$sql = 'SELECT statut FROM carnets_tbl WHERE pseudo= "'.$_SESSION['pseudo'].'" AND id="'.$groupe.'"' ;
			$req = mysql_query($sql);
			$res = mysql_num_rows($req);
			if($res!=0) $nomgroupe = mysql_result($req,0,statut);
			
			$sql = 'SELECT contact FROM carnets_tbl WHERE pseudo= "'.$_SESSION['pseudo'].'" AND statut="'.$nomgroupe.'" AND contact!= "'.$_SESSION['pseudo'].'" ORDER BY '.$ordre.' ASC' ;
			$req = mysql_query($sql);
			$res = mysql_num_rows($req);
			
			if($res==0)
				{
				print('Ce groupe est vide.');
				}
			else
				{
				print('<table style="width:160px;">');
				
				for($i=0;$i<$res;$i++)
					{
					$sqltmp = 'SELECT connec FROM principal_tbl WHERE pseudo= "'.mysql_result($req,$i,contact).'"';
					$reqtmp = mysql_query($sqltmp);
					$restmp = mysql_num_rows($reqtmp);
					
					if($restmp != 0 AND mysql_result($reqtmp,0,connec) == "oui") $connec = '<img src="im_objets/eclair1.gif" border="0" title="Connect&eacute;">';
					else $connec = "";
					
					print('<tr style="padding:0;">
						<td class="style1" style="padding:0;"><a href="engine=carnet.php?affiche='.$affiche.'&groupe='.$groupe.'&ordre='.$ordre.'&contact='.mysql_result($req,$i,contact).'">'.ucwords(mysql_result($req,$i,contact)).'</a></td><td class="style2" style="padding:0;">'.$connec.' <a href="engine=carnet.php?affiche='.$affiche.'&groupe='.$groupe.'&supprco='.mysql_result($req,$i,contact).'"><img src="im_objets/poubelle.gif" border="0" alt="Supprimer" /></a></td>
					</tr>');
					}
					
				print('</table>');
				}
			print('</div>
			<div class="titre">
				Ajouter un contact
				</div>
				<form action="engine=carnet.php?affiche='.$affiche.'&groupe='.$groupe.'&ordre='.$ordre.'&contact='.$_GET['contact'].'&action=ajoutco" method="post" class="texte" style="position:relative;top:-3px;left:0px;"><input type="hidden" name="type" value="ajoutc" />
					Pseudo <input type="text" name="nomcontact" width="10" class="champ" /> <input type="submit" name="submit" value="Ok" class="valid" />
				</form>');
			}
		}
	elseif($affiche=="adresses") //-------------------------- ADRESSES --------------------------//
		{
		
		$ordre = ($_GET['ordre']=="")?"nom":$_GET['ordre'];
		
		print('<div class="titre">
			Mes adresses <span><a href="engine=carnet.php?affiche='.$affiche.'&ordre=nom">A-Z</a> - <a href="engine=carnet.php?affiche='.$affiche.'&ordre=id">Date</a></span>
			</div>
			<div class="texte" style="height:180px;">');
				
		$sql = 'SELECT id,nom,num,rue FROM adresses_tbl WHERE pseudo="'.$_SESSION['pseudo'].'" ORDER BY '.$ordre.' ASC';
		$req = mysql_query($sql);
		$res = mysql_num_rows($req);
		
		if($res==0)
			{
			print('Aucune adresse enregistr&eacute;e.');
			}
		else
			{
			print('<table style="width:160px;">');
			
			for($i=0;$i<$res;$i++)
				{
				print('<tr>
					<td class="style1"><a href="engine=carnet.php?affiche='.$affiche.'&groupe='.$groupe.'&ordre='.$ordre.'&adresse='.mysql_result($req,$i,id).'">'.mysql_result($req,$i,nom).'</a></td><td class="style2"><a href="engine=go.php?num='.mysql_result($req,$i,num).'&rue='.mysql_result($req,$i,rue).'">S\'y rendre</a> <a href="engine=carnet.php?affiche='.$affiche.'&ordre='.$ordre.'&supprad='.mysql_result($req,$i,id).'"><img src="im_objets/poubelle.gif" border="0" alt="Supprimer" style="position:relative;top:2px;" /></a></td>
				</tr>');
				}
			
			print('</table>');
			}
		print('</div>');
		}
	elseif($affiche=="note") //-------------------------- NOTE --------------------------//
		{
		$sql = 'SELECT note FROM carnets_tbl WHERE pseudo="'.$_SESSION['pseudo'].'" AND contact="'.$_SESSION['pseudo'].'" AND statut=""';
		$req = mysql_query($sql);
		$res = mysql_num_rows($req);
		
		if($res==0)
			{
			$sql = 'INSERT INTO carnets_tbl(id,pseudo,contact,statut,note) VALUES("","'.$_SESSION['pseudo'].'","'.$_SESSION['pseudo'].'","","")';
			$req = mysql_query($sql);
			}
		else
			{
			$note = explode("[SEPARATEUR]",mysql_result($req,0,note));
			print('<form method="POST" action="engine=carnet.php?affiche=note&etat=enregistre">
				<textarea name="note1" onmouseover="focus();" style="position:relative;left:0;top:0;height:176px;width:185px;">'.$note[0].'</textarea><br />
				<input type="submit" name="submit" value="Enregistrer" style="position:relative;left:55px;top:4px;width:80px;background:#BBB;border:1px solid #444;" />
			');
			}
		}
	
	print('</div>');
	
	//======================================= ENTETE PAGE 2 =======================================//
	
	if($affiche=="contacts")
		{
		if($groupe=="")
			{
			print('<div id="carnet_titre3">
					<span>Informations contacts</span>
				</div>');
			}
		else
			{
			if($_GET['contact']=="")
				{
				print('<div id="carnet_titre3">
						<span>'.$nomgroupe.'</span>
					</div>');
				}
			else
				{
				print('<div id="carnet_titre3">
						<span>'.ucwords($_GET['contact']).'</span>
					</div>');
				}
			}
		}
	elseif($affiche=="adresses")
		{
		$adresseid = $_GET['adresse'];
		
		if($adresseid == "")
			{
			print('<div id="carnet_titre3">
				<span>Informations adresses</span>
			</div>');
			}
		else
			{
			
			$sql = 'SELECT nom FROM adresses_tbl WHERE pseudo="'.$_SESSION['pseudo'].'" AND id="'.$adresseid.'"';
			$req = mysql_query($sql);
			$res = mysql_num_rows($req);
		
			if($res != 0) $adresse = mysql_result($req,0,nom);
		
			print('<div id="carnet_titre3">
				<span>'.$adresse.'</span>
			</div>');
			}
		}
	elseif($affiche=="note")
		{
			print('<div id="carnet_titre3">
				<span>Mes notes</span>
			</div>');
		}
	
	//======================================= CONTENU PAGE 2 =======================================//
		
	print('<div id="carnet_texte2">');
	
	if($affiche=="contacts") //-------------------------- CONTACTS --------------------------//
		{
		if($groupe=="")
			{
			print('<div id="visible1"');if($_GET['ajoutco']!="")print(' style="display:none;"');print('>
					<div class="titre">
					Mes groupes
					</div>
					<div class="texte2">
					'.$nbgroupe.' groupe');if($nbgroupe!=1)print('s');print(' cr&eacute;&eacute;');if($nbgroupe!=1)print('s');print('<br />
					Le plus d\'entr&eacute;es : '.$grp);
					if($nbgroupe!=1)print('<br />Le moins d\'entr&eacute;es : '.$grm);
					print('</div>
					<div class="titre">
					Mes contacts
					</div>
					<div class="texte2">
					'.$nbcontacts.' contact');if($nbcontacts!=1)print('s');print(' enregistr&eacute;');if($nbcontacts!=1)print('s');print('<br />
					'.$nbcontactsrec.' contact');if($nbcontactsrec!=1)print('s');print(' r&eacute;ciproque');if($nbcontactsrec!=1)print('s');print('<br />
					'.$nbcontactsco.'  contact');if($nbcontactsco!=1)print('s');print(' connect&eacute;');if($nbcontactsco!=1)print('s');print('
					</div>
					<div class="titre">
					Actions possibles
					</div>
					<div class="texte2">
					<a href="#" onclick="javascript:affiche_art(\'visible1\',false);affiche_art(\'visible2\',true);">Ajouter un nouveau contact</a><br />
					<a href="engine=carnet.php?affiche=note">Acc&eacute;der &agrave; mes notes</a>
					</div>
				</div>
				<div id="visible2"');if($_GET['ajoutco']=="")print(' style="display:none;"');print('">
					<div class="titre">
					Nouveau contact
					</div>
					<form action="engine=carnet.php?affiche='.$affiche.'&groupe='.$groupe.'&ordre='.$ordre.'&contact='.$_GET['contact'].'&action=ajoutco" method="post" class="texte2" style="position:relative;top:-3px;left:0px;"><input type="hidden" name="type" value="ajoutc" />
					<br />Pseudo<br /><br />
					<input type="text" name="nomcontact" width="10" value="'.$_GET['ajoutco'].'" id="text" /><br /><br />
					Groupe<br /><br />
					<select id="select" name="groupe" style="position:relative;left:20px;width:130px;">');
					
					$sql2 = 'SELECT DISTINCT statut FROM carnets_tbl WHERE pseudo="'.$_SESSION['pseudo'].'" AND statut != "" ORDER BY id ASC' ;
					$req2 = mysql_query($sql2);
					$res2 = mysql_num_rows($req2);
					
					for($i=0;$i<$res2;$i++)
						{
						$groupetmp = mysql_result($req2,$i,statut);
						
						$sql3 = 'SELECT id FROM carnets_tbl WHERE pseudo= "'.$_SESSION['pseudo'].'" AND statut="'.$groupetmp.'" AND contact= "'.$_SESSION['pseudo'].'"' ;
						$req3 = mysql_query($sql3);
						$res3 = mysql_num_rows($req3);
						if($res3!=0) $idgroupetmp = mysql_result($req3,0,id);
						
						print('<option value="'.$idgroupetmp.'">'.$groupetmp.'</option>');
						}
					
					print('</select><br /><br />
					<input type="submit" name="submit" value="Enregistrer" id="valider" />
					</form>
				</div>');
			}
		else
			{
			
			$contact = $_GET['contact'];
			
			$sql2 = 'SELECT id FROM carnets_tbl WHERE pseudo="'.$_SESSION['pseudo'].'" AND contact= "'.$contact.'"' ;
			$req2 = mysql_query($sql2);
			$res2 = mysql_num_rows($req2);
			
			if($res2==0) $contact = "";
			
			if($contact=="")
				{
				if($_GET['action']=="supprgr")							// CONFIRMATION SUPPRESSION DU GROUPE //
					{
				print('<div class="titre">
					Suppression
					</div>
					<div class="texte2">
					La suppression du groupe "'.$nomgroupe.'" effacera tous les contacts qu\'il contient.<br /><br />
					Voulez-vous supprimer ce groupe ?<br /><br />
					<a href="engine=carnet.php?affiche=contacts&action=supprgr&confirm='.$groupe.'">Oui</a> - <a href="engine=carnet.php?affiche=contacts&groupe='.$groupe.'">Non</a>
					</div>
					');
					}
				elseif($_GET['action']=="renomgr")							// CONFIRMATION RENOMMER GROUPE //
					{
				print('<div class="titre">
					Changement de nom
					</div>
					<form action="engine=carnet.php?affiche=contacts&action=renomgr&confirm='.$groupe.'" method="POST" class="texte2">
					Pr&eacute;cisez le nouveau nom du groupe "'.$nomgroupe.'".<br /><br />
					<input type="text" name="nomgroupe" value="" width="80" id="text" /><br /><br />
					<input type="submit" name="submit" value="Modifier" id="valider" />
					</form>
					');
					}
				elseif($_GET['action']=="modifgr")							// CONFIRMATION MODIFIER UN GROUPE //
					{
				print('<div class="titre">
					Modification
					</div>
					<form action="engine=carnet.php?affiche=contacts&action=modifgr&confirm='.$groupe.'" method="POST" class="texte2" style="height:175px;overflow:auto;">
					Actions sur les contacts du groupe "'.$nomgroupe.'".<br /><br />');
					
					$sql = 'SELECT id,contact FROM carnets_tbl WHERE pseudo="'.$_SESSION['pseudo'].'" AND statut= "'.$nomgroupe.'" AND contact!="'.$_SESSION['pseudo'].'" ORDER BY contact ASC' ;
					$req = mysql_query($sql);
					$res = mysql_num_rows($req);
					
					if($res==0) print('Ce groupe est vide.');
					else
						{
						for($i=0;$i<$res;$i++)
							{
							print('<input type="checkbox" name="'.mysql_result($req,$i,id).'" id="'.mysql_result($req,$i,id).'" /> <label for="'.mysql_result($req,$i,id).'">'.mysql_result($req,$i,contact).'</label><br />');
							}
					
						print('<br /><input type="checkbox" name="supprco" id="suppr" /> <label for="suppr">Supprimer</label>');
						
						$sql2 = 'SELECT DISTINCT statut FROM carnets_tbl WHERE pseudo="'.$_SESSION['pseudo'].'" AND statut != "'.$nomgroupe.'" AND statut != ""' ;
						$req2 = mysql_query($sql2);
						$res2 = mysql_num_rows($req2);
						
						if($res2 != 0)
							{
							print(' ou <select name="deplaceco" id="select"><option value="" selected="selected">D&eacute;placer</option>');
							
							for($j=0;$j<$res2;$j++) print('<option value="'.mysql_result($req2,$j,statut).'">'.ucwords(mysql_result($req2,$j,statut)).'</option>');
						
							print('</select>');
							}
						}
					
					print('<br /><br /><input type="submit" name="submit" value="Modifier" id="valider" /></form>
					');
					}
				else
					{
				print('<div class="titre">
					Informations
					</div>
					<div class="texte2">
					'.$res.' entr&eacute;e');if($res!=1)print('s');print('
					</div>
					<div class="titre">
					Actions possibles
					</div>
					<div class="texte2">
					<a href="engine=carnet.php?affiche=contacts&groupe='.$groupe.'&action=modifgr">Modifier ce groupe</a><br />
					<a href="engine=carnet.php?affiche=contacts&groupe='.$groupe.'&action=renomgr">Renommer ce groupe</a><br />
					<a href="engine=carnet.php?affiche=contacts&groupe='.$groupe.'&action=supprgr">Supprimer ce groupe</a><br />
					<a href="engine=contacter.php?cible=Groupe '.$nomgroupe.'">Envoyer un message &agrave; tous</a>
					</div>
					');
					}
				}
			else
				{
				$sql = 'SELECT connec,avatar,race,sexe,num,rue FROM principal_tbl WHERE pseudo= "'.$contact.'"' ;
				$req = mysql_query($sql);
				$res = mysql_num_rows($req);
				
				if($res != 0)
					{
					if((ereg("http",mysql_result($req,0,avatar))) OR (ereg("ftp",mysql_result($req,0,avatar)))) $avatar = mysql_result($req,0,avatar);
					else $avatar = '../ingame/avatars/'.mysql_result($req,0,avatar);
					
					if(mysql_result($req,0,connec)=="oui") $situation = "En ligne";
					else $situation = "Hors ligne";
						
					$sql2 = 'SELECT id FROM carnets_tbl WHERE pseudo= "'.$contact.'" AND contact ="'.$_SESSION['pseudo'].'"' ;
					$req2 = mysql_query($sql2);
					$res2 = mysql_num_rows($req2);
					
					if($res2==0) $reciproque = "Non r&eacute;ciproque";
					else $reciproque = "R&eacute;ciproque";
					
					$race = mysql_result($req,0,race);
					$sexe = mysql_result($req,0,sexe);
					}
				
				$sql = 'SELECT nom,num,rue FROM adresses_tbl WHERE pseudo= "'.$_SESSION['pseudo'].'" AND contact= "'.$contact.'"' ;
				$req = mysql_query($sql);
				$res = mysql_num_rows($req);
				
				if($res!=0) $lieu = '<a href="engine=go.php?num='.mysql_result($req,0,num).'&rue='.mysql_result($req,0,rue).'">'.mysql_result($req,0,nom).'</a>';
				else $lieu = "Aucun lieu associ&eacute;";
				
				$sql = 'SELECT note FROM carnets_tbl WHERE pseudo= "'.$_SESSION['pseudo'].'" AND contact= "'.$contact.'"' ;
				$req = mysql_query($sql);
				$res = mysql_num_rows($req);
				
				if($res!=0) $note = mysql_result($req,0,note);
				
				print('<div class="image">
					<img src="'.$avatar.'" alt="Avatar" width="70" height="70" />
				</div>
				<div class="texte3">
					'.$reciproque.'<br />
					Race : '.$race.'<br />
					Sexe : '.$sexe.'<br />
					Etat : '.$situation.'<br />
					<a href="engine=contacter.php?cible='.$contact.'">Contacter</a>
				</div>
				<div class="titre">
					Lieu associ&eacute;
				</div>
				<div class="texte2">
					'.$lieu.'
				</div>
				<div class="titre">
					Note
				</div>
				<form action="engine=carnet.php?affiche='.$affiche.'&groupe='.$groupe.'&contact='.$contact.'&action=ajoutnote" method="post" class="texte2">
					<textarea name="note" onmouseover="focus();" id="textarea">'.$note.'</textarea><br />
					<input type="submit" name="submit" value="Enregistrer" id="valider" />
				</form>');
				}
			}
		}
	elseif($affiche=="adresses") //-------------------------- ADRESSES --------------------------//
		{
		if($adresseid=="")
			{
			$sql = 'SELECT id FROM adresses_tbl WHERE pseudo= "'.$_SESSION['pseudo'].'"';
			$req = mysql_query($sql);
			$nb = mysql_num_rows($req);
			
			print('<div id="visible1"');if($_GET['ajoutrue']!="" AND $_GET['ajoutnum']!="")print(' style="display:none;"');print('>
				<div class="titre">
					Mes adresses
				</div>
				<div class="texte2">
					'.$nb.' adresse');if($nb!=1)print('s');print(' enregistr&eacute;e');if($nb!=1)print('s');print('
				</div>
				<div class="titre">
					Actions possibles
				</div>
				<div class="texte2">
					<a href="#" onclick="javascript:affiche_art(\'visible1\',false);affiche_art(\'visible2\',true);">Ajouter une adresse</a>
				</div>
			</div>
			<div id="visible2"');if($_GET['ajoutrue']=="" AND $_GET['ajoutnum']=="")print(' style="display:none;"');print('>
				<div class="titre">
					Nouvelle adresse
				</div>
				<form action="engine=carnet.php?affiche=adresses&ordre='.$ordre.'&action=ajoutad" method="POST" class="texte2" style="position:relative;top:-3px;left:0px;">
					<br />Nom<br />
					<input type="text" name="nomad" id="text" value="'.$_GET['ajoutnom'].'" style="position:relative;top:3px;" /><br /><br />
					Adresse<br />
					<input type="text" name="nomnum" value="'.$_GET['ajoutnum'].'" id="text" style="position:relative;top:3px;width:25px;" width="3" /> <input type="text" name="nomrue" width="10" value="'.$_GET['ajoutrue'].'" id="text" style="position:relative;top:3px;width:115px;" /><br /><br />
					Li&eacute;e &agrave; (facultatif)<br />
					<select id="select" name="lien" style="position:relative;top:3px;left:20px;width:130px;">
						<option value="" selected="selected">Personne</option>');
					
					$sql2 = 'SELECT DISTINCT contact FROM carnets_tbl WHERE pseudo="'.$_SESSION['pseudo'].'" AND contact != "'.$_SESSION['pseudo'].'" ORDER BY contact ASC' ;
					$req2 = mysql_query($sql2);
					$res2 = mysql_num_rows($req2);
					
					for($i=0;$i<$res2;$i++)
						{
						$sqltmp = 'SELECT id FROM adresses_tbl WHERE pseudo="'.$_SESSION['pseudo'].'" AND contact = "'.mysql_result($req2,$i,contact).'"' ;
						$reqtmp = mysql_query($sqltmp);
						$restmp = mysql_num_rows($reqtmp);
						
						if($restmp==0) print('<option value="'.mysql_result($req2,$i,contact).'">'.mysql_result($req2,$i,contact).'</option>');
						}
					
					print('</select><br /><br />
					<input type="submit" name="submit" value="Enregistrer" id="valider" />
					</form>
			</div>');
			}
		else
			{
			$sql = 'SELECT id,num,rue,contact,note FROM adresses_tbl WHERE pseudo= "'.$_SESSION['pseudo'].'" AND id="'.$adresseid.'"';
			$req = mysql_query($sql);
			$res = mysql_num_rows($req);
			
			if($res != 0)
				{
				$id = mysql_result($req,0,id);
				$adressecontact = mysql_result($req,0,contact);
				$note = mysql_result($req,0,note);
				
				if($adressecontact!="")
					{
					$sql2 = 'SELECT statut FROM carnets_tbl WHERE pseudo= "'.$_SESSION['pseudo'].'" AND contact="'.$adressecontact.'"';
					$req2 = mysql_query($sql2);
					$res2 = mysql_num_rows($req2);
					
					if($res2 != 0) $adressegroupe = mysql_result($req2,0,statut);;
					}
				
				$num = mysql_result($req,0,num);
				$rue = mysql_result($req,0,rue);

				$sql2 = 'SELECT nom FROM lieu_tbl WHERE num= "'.$num.'" AND rue="'.$rue.'"';
				$req2 = mysql_query($sql2);
				$res2 = mysql_num_rows($req2);
				
				if($res2 != 0)
					{
					$type = mysql_result($req2,0,nom);
					if(ereg("Local",$type))
						{
						$sql2 = 'SELECT nom,logo FROM entreprises_tbl WHERE num= "'.$num.'" AND rue="'.$rue.'"';
						$req2 = mysql_query($sql2);
						$res2 = mysql_num_rows($req2);
						
						if($res2 != 0)
							{
							$type = mysql_result($req2,0,nom);
							if((ereg("http",mysql_result($req2,0,logo))) OR (ereg("ftp",mysql_result($req2,0,logo)))) $logo = mysql_result($req2,0,logo);
							else $logo = 'im_objets/'.mysql_result($req2,0,logo);
							}
						}
					elseif($type=="special")
						{
						$sql2 = 'SELECT nom,logo FROM lieux_speciaux_tbl WHERE num= "'.$num.'" AND rue="'.$rue.'"';
						$req2 = mysql_query($sql2);
						$res2 = mysql_num_rows($req2);
						
						if($res2 != 0)
							{
							$type = mysql_result($req2,0,nom);
							if((ereg("http",mysql_result($req2,0,logo))) OR (ereg("ftp",mysql_result($req2,0,logo)))) $logo = mysql_result($req2,0,logo);
							else $logo = 'im_objets/'.mysql_result($req2,0,logo);
							}
						}
					else
						{
						$sql2 = 'SELECT image FROM objets_tbl WHERE nom="'.$type.'"';
						$req2 = mysql_query($sql2);
						$res2 = mysql_num_rows($req2);
						
						if($res2 != 0)
							{
							$logo = 'im_objets/'.mysql_result($req2,0,image);
							}
						}
					}
				}
			
			if($_GET['action']!="modifad" OR $_POST['id']!="")
				{
				print('<div class="image">
					<img src="'.$logo.'" alt="Logo" width="70" height="70" />
				</div>
				<div class="texte3">
					'.$type.'<br />
					'.$num.' '.$rue.'');if($adressecontact!="")print('<br />Li&eacute;e &agrave <a href="engine=carnet.php?affiche=contacts&groupe='.$adressegroupe.'&contact='.$adressecontact.'">'.$adressecontact.'</a>');print('
				</div>
				<div class="titre" style="clear:both;">
					Actions possibles
				</div>
				<div class="texte2">
					<a href="engine=carnet.php?affiche='.$affiche.'&ordre='.$ordre.'&adresse='.$adresseid.'&action=modifad">Modifier cette adresse</a>
				</div>
				<div class="titre">
					Note
				</div>
				<form action="engine=carnet.php?affiche='.$affiche.'&ordre='.$ordre.'&adresse='.$adresseid.'&action=ajoutnote" method="post" class="texte2">
					<textarea name="note" onmouseover="focus();" id="textarea">'.$note.'</textarea><br />
					<input type="submit" name="submit" value="Enregistrer" id="valider" />
				</form>');
				}
			else
				{
				print('<div class="titre">
					Modification
				</div>
				<form action="engine=carnet.php?affiche=adresses&ordre='.$ordre.'&adresse='.$adresseid.'&action=modifad" method="POST" class="texte2" style="position:relative;top:-3px;left:0px;">
					
					<input type="hidden" name="id" value="'.$id.'" />
					
					<br />Nom<br />
					<input type="text" name="nomad" id="text" value="'.stripslashes(stripslashes(stripslashes($adresse))).'" style="position:relative;top:3px;" /><br /><br />
					Adresse<br />
					<input type="text" name="nomnum" value="'.$num.'" id="text" style="position:relative;top:3px;width:25px;" width="3" /> <input type="text" name="nomrue" width="10" value="'.$rue.'" id="text" style="position:relative;top:3px;width:115px;" /><br /><br />
					Li&eacute;e &agrave; (facultatif)<br />
					<select id="select" name="lien" style="position:relative;top:3px;left:20px;width:130px;">
						<option value=""');if($adressecontact=="")print(' selected="selected"');print('>Personne</option>');
					
					$sql2 = 'SELECT DISTINCT contact FROM carnets_tbl WHERE pseudo="'.$_SESSION['pseudo'].'" AND contact != "'.$_SESSION['pseudo'].'" ORDER BY contact ASC' ;
					$req2 = mysql_query($sql2);
					$res2 = mysql_num_rows($req2);
					
					for($i=0;$i<$res2;$i++)
						{
						$sqltmp = 'SELECT id FROM adresses_tbl WHERE pseudo="'.$_SESSION['pseudo'].'" AND contact = "'.mysql_result($req2,$i,contact).'"' ;
						$reqtmp = mysql_query($sqltmp);
						$restmp = mysql_num_rows($reqtmp);
						
						if($restmp==0) print('<option value="'.mysql_result($req2,$i,contact).'"');if($adressecontact == mysql_result($req2,$i,contact))print(' selected="selected"');print('>'.mysql_result($req2,$i,contact).'</option>');
						}
					
					print('</select><br /><br />
					<input type="submit" name="submit" value="Enregistrer" id="valider" />
					</form>');
				}
				
			}
		}
	elseif($affiche=="note") //-------------------------- NOTE --------------------------//
		{
		$sql = 'SELECT note FROM carnets_tbl WHERE pseudo="'.$_SESSION['pseudo'].'" AND contact="'.$_SESSION['pseudo'].'" AND statut=""';
		$req = mysql_query($sql);
		$res = mysql_num_rows($req);
		
		if($res==0)
			{
			$sql = 'INSERT INTO carnets_tbl(id,pseudo,contact,statut,note) VALUES("","'.$_SESSION['pseudo'].'","'.$_SESSION['pseudo'].'","","")';
			$req = mysql_query($sql);
			}
		else
			{
			$note = explode("[SEPARATEUR]",mysql_result($req,0,note));
			print('
				<textarea name="note2" onmouseover="focus();" style="position:relative;left:2px;top:0;height:200px;width:185px;">'.$note[1].'</textarea><br />
			</form>');
			}
		}
	
	print('</div>');
	
	
	}
else
	{
	print("<center>Vous n'avez pas de carnet d'adresse sur vous.</center>");
	}

mysql_close($db);

?>


</p>
</div>
<?php if($chat=="oui") 	{ if ((date("H")>=21) || (date("H")<=7)) { include("inc_bas_n_c.php"); } else { include("inc_bas_j_c.php"); } } else {	if ((date("H")>=21) || (date("H")<=7)) { include("inc_bas_n.php"); } else { include("inc_bas_j.php"); }	} ?>
<?php include("inc_bas_de_page.php"); ?>
