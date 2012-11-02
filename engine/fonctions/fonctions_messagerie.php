<?php

function message($destinataire, $message, $objet = null, $auteur = null) {
	$sql = 'INSERT INTO messages_tbl(auteur,cible,message,objet,moment) VALUES("'.(($auteur)?$auteur:'Dreadcast').'","'.$destinataire.'","'.$message.'","'.(($objet)?$objet:'').'","'.time().'")' ;
	$req = mysql_query($sql);
}

function affiche_messages($typeAffichage) {
	
	$retour = "";
	
	$type = ($_GET['type']=="")?"":$_GET['type'];
	$pseudo = ($_GET['pseudo']=="")?"tous":$_GET['pseudo'];
	$tri = ($_GET['tri']=="")?"moment":$_GET['tri'];
	$ordre = ($_GET['ordre']=="")?"desc":$_GET['ordre'];
	
	if($pseudo!="tous") $cas = 'AND auteur="'.$pseudo.'" AND objet NOT LIKE "[MASQUE]%"';
	else $cas = "";
	
	$sql = 'SELECT id,auteur,cible,objet,moment FROM messages_tbl WHERE '.$typeAffichage.' = "'.$_SESSION['pseudo'].'" AND nouveau= "non" '.$cas.' ORDER BY '.$tri.' '.$ordre ;
	$req = mysql_query($sql);
	$res = mysql_num_rows($req);
	
	$sql1 = 'SELECT id,auteur,cible,objet,moment FROM messages_tbl WHERE '.$typeAffichage.' = "'.$_SESSION['pseudo'].'" AND nouveau="oui" ORDER BY moment DESC' ;
	$req1 = mysql_query($sql1);
	$res1 = mysql_num_rows($req1);
	
	if(statut($_SESSION['statut'])>=2)
		{
		
		if(est_dans_inventaire("Carnet") OR statut($_SESSION['statut'])>=6)
			{
			$sqlgroupe = 'SELECT DISTINCT statut FROM carnets_tbl WHERE pseudo= "'.$_SESSION['pseudo'].'"' ;
			$reqgroupe = mysql_query($sqlgroupe);
			$resgroupe = mysql_num_rows($reqgroupe);
			}
			
		if($typeAffichage == "cible")
			{
			$retour .= '<form action="engine=supprmconf.php" method="POST" id="possibilites">
				Liste des actions :
				<select onChange="javascript:scriptMessagerie(\'parent\',this,0);" name="messtri" id="autreselect">
					<option value="tri">Trier par...</option>
					<option value="affiche"';if($type=="affiche")$retour .= ' selected';$retour .= '>Afficher...</option>
					<option value="suppr">Supprimer...</option>';
					if($resgroupe != 0) $retour .= '<option value="contact">Contacter...</option>';
					
				$retour .= '<option value="export">Exporter</option>
				</select>';
				
				$retour .= '<select id="trierpar" onChange="javascript:scriptMessagerie2(\'parent\',this,0);"';if($type!="")$retour .= ' style="display:none;"';$retour .= '>
					<option value="dc"';if($tri=="moment" && $ordre=="asc")$retour .= ' selected';$retour .= '>date croissante</option>
					<option value="dd"';if($tri=="moment" && $ordre=="desc")$retour .= ' selected';$retour .= '>date décroissante</option>
					<option value="ac"';if($tri=="auteur" && $ordre=="asc")$retour .= ' selected';$retour .= '>auteur croissant</option>
					<option value="ad"';if($tri=="auteur" && $ordre=="desc")$retour .= ' selected';$retour .= '>auteur décroissant</option>
					<option value="tc"';if($tri=="objet" && $ordre=="asc")$retour .= ' selected';$retour .= '>titre croissant</option>
					<option value="td"';if($tri=="objet" && $ordre=="desc")$retour .= ' selected';$retour .= '>titre décroissant</option>
				</select>';
				
				$retour .= '<select name="messagesde" id="afficherpar" onChange="javascript:scriptMessagerie3(\'parent\',this,0,\'autreselect\');"';if($type!="affiche")$retour .= 'style="display:none;"';$retour .= '>
					<option value="tous" ';if($pseudo=="")$retour .= 'selected';$retour .= '>tous les messages</option>
					<option value="Dreadcast" ';if($pseudo=="Dreadcast")$retour .= 'selected';$retour .= '>les messages officiels</option>';
				$sqltmp = 'SELECT DISTINCT auteur FROM messages_tbl WHERE cible= "'.$_SESSION['pseudo'].'" AND auteur!="Dreadcast" AND objet NOT LIKE "[MASQUE]%" ORDER BY auteur ASC' ;
				$reqtmp = mysql_query($sqltmp);
				$restmp = mysql_num_rows($reqtmp);
				for($j=0;$j<$restmp;$j++) { $retour .= '<option value="'.mysql_result($reqtmp,$j,auteur).'" ';if($pseudo==mysql_result($reqtmp,$j,auteur))$retour .= 'selected';$retour .= '>les messages de '.mysql_result($reqtmp,$j,auteur).'</option>'; }
				$retour .= '</select>';
				
				if($resgroupe!=0)
					{
					$retour .= '<select id="contactgroupe" onChange="javascript:scriptMessagerie4(\'parent\',this,0);" style="display:none;">';
					for($j=0;$j<$resgroupe;$j++) $retour .= '<option value="Groupe '.mysql_result($reqgroupe,$j,statut).'">le groupe '.mysql_result($reqgroupe,$j,statut).'</option>';
					$retour .= '</select>';
					}
					
				$retour .= '<input type="submit" name="submit" value="OK" id="submit1" style="display:none;" />';
			
			$retour .= '</form>';
			}
		
		$retour .= '<div class="messagesvip">';
		if(($res!=0) || ($res1!=0))
			{
			$retour .= '<form action="engine=supprmconf.php" method="post">
					<div id="messageriehaut">
						<div class="style1">'.(($typeAffichage == "cible")?"De":"A").'</div>
						<div class="style2">Objet</div>
						<div class="style3">Date</div>
						<div class="style4">Heure</div>
						<div class="style5">'.(($typeAffichage == "cible")?'<input type="submit" name="submit" value="" class="pointeur" />':'').'</div>
					</div>';
			
			for($i=0; $i != $res1 ; $i++) 
				{ 
				$idm = mysql_result($req1,$i,id);
				$auteur = mysql_result($req1,$i,auteur);
				$cible = mysql_result($req1,$i,cible);
				$objet = mysql_result($req1,$i,objet);
				if(preg_match('#^\[MASQUE\]#isU',$objet)) { $objet = trim(str_replace('[MASQUE]','',preg_replace('#^\[MASQUE\](.+)#isU',"$1",$objet))); $auteur = "-Anonyme-"; }
				$date = date('d/m/y',mysql_result($req1,$i,moment));
				$heure = date('H\hi',mysql_result($req1,$i,moment));
				if($objet=="")
					{
					$objet = "Aucun";
					}
				if($auteur=="Dreadcast")
					{
					$retour .= '<div style="position:relative;left:22px;top:0;width:417px;height:16px;background:url(im_objets/messagerie.gif) 0 -16px no-repeat;margin-top:1px;font-weight:bold;">
						<div class="style1"><strong>'.(($typeAffichage == "cible")?$auteur:$cible).'</strong></div>
						<div class="style2"><strong><a href="engine=voirmessage'.(($typeAffichage == "auteur")?'env':'').'.php?id='.$idm.'">'.$objet.'</a></strong></div>
						<div class="style3"><strong>'.$date.'</strong></div>
						<div class="style4"><strong>'.$heure.'</strong></div>
						<div class="style5">'.(($typeAffichage == "cible")?'<input type="checkbox" name="'.$idm.'" style="margin:0px;" />':'').'</div>
					</div>';
					}
				else
					{
					$retour .= '<div style="position:relative;left:22px;top:0;width:417px;height:16px;background:url(im_objets/messagerie.gif) 0 -16px no-repeat;margin-top:1px;font-weight:bold;">
						<div class="style1"><strong>'.(($typeAffichage == "cible")?$auteur:$cible).'</strong></div>
						<div class="style2"><strong><a href="engine=voirmessage'.(($typeAffichage == "auteur")?'env':'').'.php?id='.$idm.'">'.$objet.'</a></strong></div>
						<div class="style3"><strong>'.$date.'</strong></div>
						<div class="style4"><strong>'.$heure.'</strong></div>
						<div class="style5">'.(($typeAffichage == "cible")?'<input type="checkbox" name="'.$idm.'" style="margin:0px;" />':'').'</div>
					</div>';
					}
				}
			for($i=0; $i != $res ; $i++) 
				{ 
				$idm = mysql_result($req,$i,id);
				$auteur = mysql_result($req,$i,auteur);
				$cible = mysql_result($req,$i,cible);
				$objet = mysql_result($req,$i,objet);
				if(preg_match('#^\[MASQUE\]#isU',$objet)) { $objet = trim(str_replace('[MASQUE]','',preg_replace('#^\[MASQUE\](.+)#isU',"$1",$objet))); $auteur = "-Anonyme-"; }
				$date = date('d/m/y',mysql_result($req,$i,moment));
				$heure = date('H\hi',mysql_result($req,$i,moment));
				if($objet=="")
					{
					$objet = "Aucun";
					}
				if($auteur=="Dreadcast")
					{
					$retour .= '<div style="position:relative;left:22px;top:0;width:417px;height:16px;background:url(im_objets/messagerie.gif) 0 -32px no-repeat;margin-top:1px;">
						<div class="style1">'.(($typeAffichage == "cible")?$auteur:$cible).'</div>
						<div class="style2"><a href="engine=voirmessage'.(($typeAffichage == "auteur")?'env':'').'.php?id='.$idm.'">'.$objet.'</a></div>
						<div class="style3">'.$date.'</div>
						<div class="style4">'.$heure.'</div>
						<div class="style5">'.(($typeAffichage == "cible")?'<input type="checkbox" name="'.$idm.'" style="margin:0px;" />':'').'</div>
					</div>';
					}
				else
					{
					$retour .= '<div style="position:relative;left:22px;top:0;width:417px;height:16px;background:url(im_objets/messagerie.gif) 0 0 no-repeat;margin-top:1px;">
						<div class="style1">'.(($typeAffichage == "cible")?$auteur:$cible).'</div>
						<div class="style2"><a href="engine=voirmessage'.(($typeAffichage == "auteur")?'env':'').'.php?id='.$idm.'">'.$objet.'</a></div>
						<div class="style3">'.$date.'</div>
						<div class="style4">'.$heure.'</div>
						<div class="style5">'.(($typeAffichage == "cible")?'<input type="checkbox" name="'.$idm.'" style="margin:0px;" />':'').'</div>
					</div>';
					}
				}
			}
		else
			{
			$retour .= '<p align="center"><strong>Vous n\'avez aucun message priv&eacute;.</strong></p>';
			}
		$retour .= '</form></div>';
		}
	else
		{
		$l = 0;
		$retour .= '<div class="messagesvip" style="overflow:visible;">';
		if(($res!=0) || ($res1!=0))
			{
			$retour .= '<form action="engine=supprmconf.php" method="post">
					<div id="messageriehaut">
						<div class="style1">De</div>
						<div class="style2">Objet</div>
						<div class="style3">Date</div>
						<div class="style4">Heure</div>
						<div class="style5">'.(($typeAffichage == "cible")?'<input type="submit" name="submit" value="" class="pointeur" />':'').'</div>
					</div>';
			
			for($i=0; $i != $res1 ; $i++) 
				{ 
				if($l<14)
					{
					$l = $l + 1;
					$idm = mysql_result($req1,$i,id);
					$auteur = mysql_result($req1,$i,auteur);
					$objet = mysql_result($req1,$i,objet);
					if(preg_match('#^\[MASQUE\]#isU',$objet)) { $objet = trim(str_replace('[MASQUE]','',preg_replace('#^\[MASQUE\](.+)#isU',"$1",$objet))); $auteur = "-Anonyme-"; }
					$date = date('d/m/y',mysql_result($req1,$i,moment));
					$heure = date('H\hi',mysql_result($req1,$i,moment));
					if($objet=="")
						{
						$objet = "Aucun";
						}
					if($auteur=="Dreadcast")
						{
						$retour .= '<div style="position:relative;left:22px;top:0;width:417px;height:16px;background:url(im_objets/messagerie.gif) 0 -16px no-repeat;margin-top:1px;font-weight:bold;">
							<div class="style1"><strong>'.$auteur.'</strong></div>
							<div class="style2"><strong><a href="engine=voirmessage'.(($typeAffichage == "auteur")?'env':'').'.php?id='.$idm.'">'.$objet.'</a></strong></div>
							<div class="style3"><strong>'.$date.'</strong></div>
							<div class="style4"><strong>'.$heure.'</strong></div>
							<div class="style5">'.(($typeAffichage == "cible")?'<input type="checkbox" name="'.$idm.'" style="margin:0px;" />':'').'</div>
						</div>';
						}
					else
						{
						$retour .= '<div style="position:relative;left:22px;top:0;width:417px;height:16px;background:url(im_objets/messagerie.gif) 0 -16px no-repeat;margin-top:1px;font-weight:bold;">
							<div class="style1"><strong>'.$auteur.'</strong></div>
							<div class="style2"><strong><a href="engine=voirmessage'.(($typeAffichage == "auteur")?'env':'').'.php?id='.$idm.'">'.$objet.'</a></strong></div>
							<div class="style3"><strong>'.$date.'</strong></div>
							<div class="style4"><strong>'.$heure.'</strong></div>
							<div class="style5">'.(($typeAffichage == "cible")?'<input type="checkbox" name="'.$idm.'" style="margin:0px;" />':'').'</div>
						</div>';
						}
					}
				}
			for($i=0; $i != $res ; $i++) 
				{ 
				if($l<14)
					{
					$l = $l + 1;
					$idm = mysql_result($req,$i,id);
					$auteur = mysql_result($req,$i,auteur);
					$objet = mysql_result($req,$i,objet);
					if(preg_match('#^\[MASQUE\]#isU',$objet)) { $objet = trim(str_replace('[MASQUE]','',preg_replace('#^\[MASQUE\](.+)#isU',"$1",$objet))); $auteur = "-Anonyme-"; }
					$date = date('d/m/y',mysql_result($req,$i,moment));
					$heure = date('H\hi',mysql_result($req,$i,moment));
					if($objet=="")
						{
						$objet = "Aucun";
						}
					if($auteur=="Dreadcast")
						{
						$retour .= '<div style="position:relative;left:22px;top:0;width:417px;height:16px;background:url(im_objets/messagerie.gif) 0 -32px no-repeat;margin-top:1px;">
							<div class="style1">'.$auteur.'</div>
							<div class="style2"><a href="engine=voirmessage'.(($typeAffichage == "auteur")?'env':'').'.php?id='.$idm.'">'.$objet.'</a></div>
							<div class="style3">'.$date.'</div>
							<div class="style4">'.$heure.'</div>
							<div class="style5">'.(($typeAffichage == "cible")?'<input type="checkbox" name="'.$idm.'" style="margin:0px;" />':'').'</div>
						</div>';
						}
					else
						{
						$retour .= '<div style="position:relative;left:22px;top:0;width:417px;height:16px;background:url(im_objets/messagerie.gif) 0 0 no-repeat;margin-top:1px;">
							<div class="style1">'.$auteur.'</div>
							<div class="style2"><a href="engine=voirmessage'.(($typeAffichage == "auteur")?'env':'').'.php?id='.$idm.'">'.$objet.'</a></div>
							<div class="style3">'.$date.'</div>
							<div class="style4">'.$heure.'</div>
							<div class="style5">'.(($typeAffichage == "cible")?'<input type="checkbox" name="'.$idm.'" style="margin:0px;" />':'').'</div>
						</div>';
						}
	
					}
				}
			$retour .= '</form>';
			}
		else
			{
			$retour .= '<p style="voirmessage-align:center;"><strong>Vous n\'avez aucun message priv&eacute;.</strong></p>';
			}
			
		if($l>=14)
			{
			$retour .= '<p style="position:relative;bottom:4px;voirmessage-align:center;"><strong>Votre messagerie est pleine !</strong></p>';
			}
		$retour .= '</div>';
		}
		
	return $retour;

}

function affiche_message($id,$typeAffichage) {
	
	$retour = "";
	
	$sql = 'SELECT auteur,objet,message,nouveau,moment FROM messages_tbl WHERE '.$typeAffichage.' = "'.$_SESSION['pseudo'].'" AND id= "'.$id.'"' ;
	$req = mysql_query($sql);
	$res = mysql_num_rows($req);
	
	if($res!=0)
		{
		$auteur = mysql_result($req,0,auteur);
		$objet = mysql_result($req,0,objet);
		if(preg_match('#^\[MASQUE\]#isU',$objet)) { $objet = trim(str_replace('[MASQUE]','',preg_replace('#^\[MASQUE\](.+)#isU',"$1",$objet))); $auteur = "-Anonyme-"; }
		$datea = date('d/m/y',mysql_result($req,0,moment));
		$heure = date('H\hi',mysql_result($req,0,moment));
		$message = mysql_result($req,0,message);
		$nouveau = mysql_result($req,0,nouveau);
		
		$autredest="";
		
		if($auteur!="Dreadcast" && $auteur!="-Anonyme-")
			{
			$sql2 = 'SELECT cible FROM messages_tbl WHERE auteur="'.$auteur.'" AND objet= "'.$objet.'" AND moment= "'.mysql_result($req,0,moment).'"' ;
			$req2 = mysql_query($sql2);
			$res2 = mysql_num_rows($req2);
	
			if($res2>1)
				{
				$autredest .= mysql_result($req2,0,cible);
				for($i=1;$i<$res2;$i++) $autredest .= ", ".mysql_result($req2,$i,cible);
				}
			}
		
		
			$sql2 = 'SELECT avatar FROM principal_tbl WHERE pseudo = "'.$auteur.'"' ;
			$req2 = mysql_query($sql2);
			$res2 = mysql_num_rows($req2);
			
			$sql3 = 'SELECT id FROM messages_tbl WHERE '.$typeAffichage.' = "'.$_SESSION['pseudo'].'" AND moment>'.mysql_result($req,0,moment).' ORDER BY moment ASC LIMIT 1' ;
			$req3 = mysql_query($sql3);
			$res3 = mysql_num_rows($req3);
			
			if($res3!=0 AND statut($_SESSION['statut'])>=2) $messsuiv=mysql_result($req3,0,id);
			
			$sql3 = 'SELECT id FROM messages_tbl WHERE '.$typeAffichage.' = "'.$_SESSION['pseudo'].'" AND moment<'.mysql_result($req,0,moment).' ORDER BY moment DESC LIMIT 1' ;
			$req3 = mysql_query($sql3);
			$res3 = mysql_num_rows($req3);
			
			if($res3!=0 AND statut($_SESSION['statut'])>=2) $messprec=mysql_result($req3,0,id);
			
			if($res2>0) $avatar = mysql_result($req2,0,avatar);
			else $avatar = "interogation.jpg";
			if($auteur=="Dreadcast") $avatar = "";
			
			if($objet=="") $objet="Sans sujet";
			
			if(ereg("\[CONVERSATION\]",$message))
				{
				$convers = strstr($message,"[CONVERSATION]");
				$convers = substr($convers,14);
				$message = substr($message,0,strpos($message,"[CONVERSATION]"));
				
				$convers = '<div id="conversation" style="display:none;">'.str_replace("[CONVERSATION]","<div class=\"messagebarre\"></div>",$convers).'</div>';
				}
			
			$retour .= '<div id="messhaut" style="';if($auteur=="Dreadcast")$retour .= 'text-align:center;';$retour .= 'background:url(im_objets/voirmesshaut.gif) 0 ';if($nouveau=="non")$retour .= '-65px';else $retour .= '0'; $retour .= ' no-repeat;">';
				if($auteur!="Dreadcast")
					{
					if((ereg("http",$avatar)) OR (ereg("ftp",$avatar))) $retour .= '<img src="'.$avatar.'" border="1" width="70" height="70" />';
					else $retour .= '<img src="avatars/'.$avatar.'" border="0" width="70" height="70" />';
					}
				$retour .= '<div class="texte">
					<h3>'.$objet.'</h3>';
					if($nouveau=="oui") $retour .= 'Nouveau message';
					else $retour .= 'Message';
					if($auteur=="Dreadcast") $retour .= ' automatique le '.$datea.' &agrave; '.$heure.'';
					else $retour .= ' de <strong>'.$auteur.'</strong> le '.$datea.' &agrave; '.$heure;
				if (statut() == 7)
					$retour .= ' (<a href="engine=messages.php?nl='.$id.'">non lu<a>)';
				$retour .= '</div>';
	
				if($autredest!="")
				{
				if(strlen($autredest)>24) { $destot = $autredest; $autredest = substr($autredest,0,24)."..."; }
				$retour .= '<div id="textegroupe">
					Ce message a été envoyé à '.$autredest.'
				</div>';
				if($destot!="")
				$retour .= '<div id="deroulecontacts" onMouseOver="javascript:affiche_art(\'contactot\',true);affiche_art(\'textegroupe\',false);" onMouseOut="javascript:affiche_art(\'contactot\',false);affiche_art(\'textegroupe\',true);"></div>
				<div id="contactot" onMouseOver="javascript:affiche_art(\'contactot\',true);affiche_art(\'textegroupe\',false);" onMouseOut="javascript:affiche_art(\'contactot\',false);affiche_art(\'textegroupe\',true);" style="display:none;">Ce message a été envoyé à '.$destot.'</div>
				';
				}
	
			$retour .= '</div>
			<div id="messcontenu"';if($auteur=="Dreadcast")$retour .= ' style="background:url(im_objets/voirmessfond2.gif) 0 0 no-repeat;"';$retour .= '>
				';
				if($messprec!="" AND $messsuiv=="") $retour .= '<a href="engine=voirmessage'.(($typeAffichage == "auteur")?'env':'').'.php?id='.$messprec.'" id="messsuiv" style="top:0px;" title="Message précédent"></a>';
				elseif($messprec=="" AND $messsuiv!="") $retour .= '<a href="engine=voirmessage'.(($typeAffichage == "auteur")?'env':'').'.php?id='.$messsuiv.'" id="messprec" title="Message suivant"></a>';
				elseif($messprec!="" AND $messsuiv!="") $retour .= '<a href="engine=voirmessage'.(($typeAffichage == "auteur")?'env':'').'.php?id='.$messsuiv.'" id="messprec" title="Message suivant"></a>
				<a href="engine=voirmessage'.(($typeAffichage == "auteur")?'env':'').'.php?id='.$messprec.'" id="messsuiv" title="Message précédent"></a>';
				$retour .= $message.'
				'.$convers.'
			</div>
			<div id="messbas" style="background:url(im_objets/voirmessbas.gif) 0 ';
			if($convers!="")$retour .= '-18px';
			else $retour .= '0';
			$retour .= ' no-repeat;">
				<div class="rep">
					'; if($auteur!="Dreadcast" && $auteur!="-Anonyme-" && $typeAffichage == "cible") $retour .= '<a href="engine=contacter.php?id='.$id.'">Répondre</a> - ';
					if($auteur!="Dreadcast" && $typeAffichage == "cible") $retour .= '<a href="engine=contacter.php?transferer='.$id.'">Transférer</a> - ';
					//if($autredest!="") $retour .= '<a href="engine=contacter.php?objet='.$objet.'&id='.$id.'&idtous='.$id.'">Répondre à tous</a> - ';
					if($typeAffichage == "cible") $retour .= '<a href="engine=supprmessage.php?'.$id.'">Supprimer</a>';
				$retour .= '</div>';
	
				if($convers!="")	
				$retour .= '<div class="conv">
					<a href="javascript:affiche_art(\'conversation\',true);affiche_art(\'convfalse\',true);affiche_art(\'convtrue\',false);" id="convtrue">Afficher la conversation</a>
					<a href="javascript:affiche_art(\'conversation\',false);affiche_art(\'convfalse\',false);affiche_art(\'convtrue\',true);" id="convfalse" style="display:none;">Masquer la conversation</a>
				</div>';
			$retour .= '</div>';
			
			if($nouveau == "oui" && $typeAffichage == "cible")
				{ 	
				$sql1 = 'UPDATE messages_tbl SET nouveau= "non" WHERE id= "'.$id.'"' ;
				$req1 = mysql_query($sql1);
				}
		}
	else
		{
		print("<br /><br /><center><strong>Ce message ne peut pas s'afficher.</strong><center>");
		}
	
	return $retour;
	
}

?>
