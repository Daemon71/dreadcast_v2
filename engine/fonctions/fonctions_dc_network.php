<?php

function affiche_DCN($texte) {
	
	$texte = nl2br($texte);
	$retour = "";
	
	// TITRE
	preg_match('#\[TITRE\](.+)\[/TITRE\]#isU',$texte,$tableTexte);
	$tableTexte[1] = preg_replace('#^<br />(.+)<br />$#isU','$1',$tableTexte[1]);
	$retour .= '<h2 id="titre">'.$tableTexte[1].'</h2>
	';
	
	// INTRODUCTION
	preg_match('#\[INTRODUCTION\](.+)\[/INTRODUCTION\]#isU',$texte,$tableTexte);
	$tableTexte[1] = preg_replace('#^<br />(.+)<br />$#isU','$1',$tableTexte[1]);
	$tableTexte[1] = preg_replace('#\[IMAGE\](.+)\[/IMAGE\]#isU','<div class="image"><a href="$1" onclick="window.open(this.href); return false;"><img src="$1" alt="Image" /></a></div>',$tableTexte[1]);
	$tableTexte[1] = preg_replace('#</div><br />#isU','</div>',$tableTexte[1]);
	$tableTexte[1] = preg_replace('#\[TEXTE\](.+)\[/TEXTE\]#isU','<div class="texte">$1</div>',$tableTexte[1]);
	$tableTexte[1] = preg_replace('#<div class="texte"><br />#isU','<div class="texte">',$tableTexte[1]);
	$tableTexte[1] = preg_replace('#<br />.{0,2}</div>#isU','
		</div>',$tableTexte[1]);
	$retour .= '<div id="introduction">'.$tableTexte[1].'</div>
	';
	
	// SOMMAIRE
	$retour .= '<div id="sommaire">
		<ul>';
	$i=1;
	for($i;preg_match('#\[PARTIE '.$i.'\](.+)\[/PARTIE '.$i.'\]#isU',$texte,$tmp);$i++)
		{
		preg_match('#\[TITRE\](.+)\[/TITRE\]#isU',$tmp[1],$tmp2);
		$tmp2[1] = preg_replace('#^<br />(.+)#isU','$1',$tmp2[1]);
		$tmp2[1] = preg_replace('#(.+)<br />$#isU','$1',$tmp2[1]);
		preg_match('#\[PRESENTATION\](.+)\[/PRESENTATION\]#isU',$tmp[1],$tmp3);
		$tmp3[1] = preg_replace('#^.{0,2}<br />(.+)#isU','$1',$tmp3[1]);
		$tmp3[1] = preg_replace('#(.+)<br />.{0,2}$#isU','$1',$tmp3[1]);
		
		if(ereg("aitl2",$_SERVER['PHP_SELF']))
		$retour .= '
			<li>
				<a href="#" class="commelien" onclick="javascript:$(\'#'.$i.'\').fadeIn(\'slow\');$(\'#titre\').fadeOut(\'slow\');$(\'#introduction\').fadeOut(\'slow\');$(\'#sommaire\').fadeOut(\'slow\',reinitialiseScrollPane);">'.$tmp2[1].'</a>
				<div class="presentation">'.$tmp3[1].'</div>
			</li>';
		else
			$retour .= '
			<li>
				<a href="#" class="commelien" onclick="javascript:$(\'#'.$i.'\').fadeIn(\'slow\');$(\'#titre\').fadeOut(\'slow\');$(\'#introduction\').fadeOut(\'slow\');$(\'#sommaire\').fadeOut(\'slow\');$(\'.scrollDCN\').animate({scrollTop: 0}, 1000);">'.$tmp2[1].'</a>
				<div class="presentation">'.$tmp3[1].'</div>
			</li>';
		}
	$retour .= '
		</ul>
	</div>
	';
	
	// CONTENU
	$retour .= '<div id="contenu">';
	$i=1;
	for($i;preg_match('#\[PARTIE '.$i.'\](.+)\[/PARTIE '.$i.'\]#isU',$texte,$tmp);$i++)
		{
		preg_match('#\[TITRE\](.+)\[/TITRE\]#isU',$tmp[1],$tmp2);
		$tmp2[1] = preg_replace('#^.{0,2}<br />(.+)#isU','$1',$tmp2[1]);
		$tmp2[1] = preg_replace('#(.+)<br />.{0,2}$#isU','$1',$tmp2[1]);
		preg_match('#\[AUTEUR\](.+)\[/AUTEUR\]#isU',$tmp[1],$tmp3);
		$tmp3[1] = preg_replace('#^.{0,2}<br />(.+)#isU','$1',$tmp3[1]);
		$tmp3[1] = preg_replace('#(.+)<br />.{0,2}$#isU','$1',$tmp3[1]);
		preg_match('#\[IMAGE\](.+)\[/IMAGE\]#isU',$tmp[1],$tmp5);
		$tmp5[1] = preg_replace('#^.{0,2}<br />(.+)#isU','$1',$tmp5[1]);
		$tmp5[1] = preg_replace('#(.+)<br />.{0,2}$#isU','$1',$tmp5[1]);
		preg_match('#\[TEXTE\](.+)\[/TEXTE\]#isU',$tmp[1],$tmp6);
		$tmp6[1] = preg_replace('#^.{0,2}<br />(.+)#isU','$1',$tmp6[1]);
		$tmp6[1] = preg_replace('#(.+)<br />.{0,2}$#isU','$1',$tmp6[1]);
		
		if(ereg("aitl2",$_SERVER['PHP_SELF']))
		$retour .= '
		<div id="'.$i.'" style="display:none;position:relative;">
			<div class="titre">'.$tmp2[1].' <span>par '.$tmp3[1].'</span></div>
			'.(($tmp5[1] != "")?'<div class="image"><a href="'.$tmp5[1].'" onclick="window.open(this.href); return false;"><img src="'.$tmp5[1].'" alt="Image'.$i.'" /></a></div>':'').'
			<div class="texte">'.$tmp6[1].'</div>
			<div class="liens">
			'.(($i!=1)?'<a href="#" class="commelien" onclick="javascript:$(\'#'.$i.'\').fadeOut(\'slow\');
$(\'#'.($i-1).'\').fadeIn(\'slow\',reinitialiseScrollPane);">Article précédent</a> - ':"Article précédent - ").'<a href="#" div class="commelien" onclick="javascript:$(\'#'.$i.'\').fadeOut(\'slow\');$(\'#titre\').fadeIn(\'slow\');$(\'#introduction\').fadeIn(\'slow\');$(\'#sommaire\').fadeIn(\'slow\',reinitialiseScrollPane);">Sommaire</a>'.((ereg('\[PARTIE '.($i+1).'\]',$texte))?' - <a href="#" class="commelien" onclick="javascript:$(\'#'.$i.'\').fadeOut(\'slow\');$(\'#'.($i+1).'\').fadeIn(\'slow\',reinitialiseScrollPane);">Article suivant</a>':" - Article suivant").'
			</div>
		</div>';
		else
		$retour .= '
		<div id="'.$i.'" style="display:none;position:absolute;top:0;margin-top:10px;">
			<div class="titre">'.$tmp2[1].' <span>par '.$tmp3[1].'</span></div>
			'.(($tmp5[1] != "")?'<div class="image"><a href="'.$tmp5[1].'" onclick="window.open(this.href); return false;"><img src="'.$tmp5[1].'" alt="Image'.$i.'" /></a></div>':'').'
			<div class="texte">'.$tmp6[1].'</div>
			<div class="liens">
			'.(($i!=1)?'<a href="#" class="commelien" onclick="javascript:$(\'#'.$i.'\').fadeOut(\'slow\');
$(\'#'.($i-1).'\').fadeIn(\'slow\');$(\'.scrollDCN\').animate({scrollTop: 0}, 1000);">Article précédent</a> - ':"Article précédent - ").'<a href="#" div class="commelien" onclick="javascript:$(\'#'.$i.'\').fadeOut(\'slow\');$(\'#titre\').fadeIn(\'slow\');$(\'#introduction\').fadeIn(\'slow\');$(\'#sommaire\').fadeIn(\'slow\');$(\'.scrollDCN\').animate({scrollTop: 0}, 1000);">Sommaire</a>'.((ereg('\[PARTIE '.($i+1).'\]',$texte))?' - <a href="#" class="commelien" onclick="javascript:$(\'#'.$i.'\').fadeOut(\'slow\');$(\'#'.($i+1).'\').fadeIn(\'slow\');$(\'.scrollDCN\').animate({scrollTop: 0}, 1000);">Article suivant</a>':" - Article suivant").'
			</div>
		</div>';
		}
	$retour .= '
	</div>
	';
	
	return $retour;
}

function affiche_DCN_titre($texte) {

	$texte = nl2br($texte);
	$retour = "";
	
	preg_match('#\[TITRE\](.+)\[/TITRE\]#isU',$texte,$tableTexte);
	$tableTexte[1] = preg_replace('#^.{0,2}<br />(.+)<br />.{0,2}$#isU','$1',$tableTexte[1]);
	$retour .= $tableTexte[1];
	
	return $retour;
}

function affiche_DCN_image($texte,$numero) {

	$texte = nl2br($texte);
	$retour = "";
	
	if($numero == 0)
		{
		preg_match('#\[INTRODUCTION\](.+)\[/INTRODUCTION\]#isU',$texte,$tableTexte);
		$tableTexte[1] = preg_replace('#^.{0,2}<br />(.+)<br />.{0,2}$#isU','$1',$tableTexte[1]);
		preg_match('#\[IMAGE\](.+)\[/IMAGE\]#isU',$tableTexte[1],$retour);
		}
	
	return $retour[1];
}

function affiche_DCN_contenu($texte,$numero) {

	$texte = nl2br($texte);
	$retour = "";
	
	if($numero == 0)
		{
		preg_match('#\[INTRODUCTION\](.+)\[/INTRODUCTION\]#isU',$texte,$tableTexte);
		$tableTexte[1] = preg_replace('#^.{0,2}<br />(.+)<br />.{0,2}$#isU','$1',$tableTexte[1]);
		preg_match('#\[TEXTE\](.+)\[/TEXTE\]#isU',$tableTexte[1],$retour);
		}
	
	return str_replace('<br />','',$retour[1]);
}

function affiche_DCTV_programme($timestamp) {

	$un_jour = 86400;
	
	$aujourd_hui = mktime(00,00,00,date(m,$timestamp),date(d,$timestamp),date(Y,$timestamp));
	
	$samedi = (date('D',$aujourd_hui) == "Sat")?$aujourd_hui:(
			  (date('D',$aujourd_hui) == "Sun")?($aujourd_hui-1*$un_jour):(
			  (date('D',$aujourd_hui) == "Mon")?($aujourd_hui-2*$un_jour):(
			  (date('D',$aujourd_hui) == "Tue")?($aujourd_hui-3*$un_jour):(
			  (date('D',$aujourd_hui) == "Wed")?($aujourd_hui-4*$un_jour):(
			  (date('D',$aujourd_hui) == "Thu")?($aujourd_hui-5*$un_jour):(
			  $aujourd_hui-6*$un_jour))))));
	
	$vendredi = $samedi+6*$un_jour;
	
	$retour = "";
	$survol = "";
	
	$retour .= '<table id="titre_programmation">
		<tr>
			<td>Sam.</td>
			<td>Dim.</td>
			<td>Lun.</td>
			<td>Mar.</td>
			<td>Mer.</td>
			<td>Jeu.</td>
			<td>Ven.</td>
		</tr>
	</table>
	<table id="programmation">
		';
	
	$jour = $samedi;
	$heure = 0;
	
	for($i=0;$i<24;$i++)
		{
		$retour .= '<tr>
			<td class="tdheures"><div style="position:relative;"><span class="heures">'.(($i<10)?'0'.$i:$i).'h</span></div></td>
			';
		
		for($j=0;$j<7;$j++)
			{
			$sql = 'SELECT id,duree,clip,date_debut FROM DCN_programmation_tbl WHERE date_debut = '.($jour+$heure*3600);
			$req = mysql_query($sql);
			
			if($table[$jour] != 0)
				{
				$table[$jour]--;
				}
			elseif(mysql_num_rows($req) != 0)
				{
				$t_debut = mysql_result($req,0,date_debut);
				$t_fin = $t_debut + mysql_result($req,0,duree)*3600;
				
				if($timestamp >= $t_debut && $timestamp < $t_fin) $couleur_rouge = ' style="color:#a52727;" ';
				
				$sql2 = 'SELECT nom,synopsis FROM DCN_clips_tbl WHERE id = '.mysql_result($req,0,clip);
				$req2 = mysql_query($sql2);
				$table[$jour] = mysql_result($req,0,duree) - 1;
				$retour .= '<td onclick="javascript:$(\'#'.mysql_result($req,0,id).'\').fadeIn();" onmouseout="javascript:$(\'#'.mysql_result($req,0,id).'\').hide();" class="'.(((($k[$jour]++)%2==0))?'programme1':'programme2').'" rowspan="'.mysql_result($req,0,duree).'" '.$couleur_rouge.'>
				';
				
				$survol .= '<div onmouseover="javascript:$(\'#'.mysql_result($req,0,id).'\').show();" onclick="javascript:$(\'#'.mysql_result($req,0,id).'\').fadeOut();" onmouseout="javascript:$(\'#'.mysql_result($req,0,id).'\').hide();" class="survol" id="'.mysql_result($req,0,id).'" style="z-index:200;position:absolute;top:10px;left:50%;width:200px;margin-left:-100px;padding:10px;background:#fff;border:1px solid #ccc;display:none;color:black;text-align:justify;">
				<h3 style="text-align:center;margin-bottom:10px;">'.mysql_result($req2,0,nom).'</h3>
				Horaires : de '.date('H\hi',mysql_result($req,0,date_debut)).' à '.(date(H,mysql_result($req,0,date_debut))+mysql_result($req,0,duree)).'h00';
				
				if(mysql_result($req2,0,synopsis) != "") $survol .= '<br /><br /><h4>Synopsis</h4>'.mysql_result($req2,0,synopsis);
				
				$survol .= '</div>';
				
				$retour .= '</td>
				';
				}
			else
				{
				$retour .= '<td></td>
				';
				}
			
			if($jour == $vendredi) $jour = $samedi;
			else $jour += $un_jour;
			}
			
		$retour .= '</tr>
		';
		$heure++;
		}
		
	$retour .= '</table>';
	
	$retour .= $survol;
	
	return $retour;

}

function affiche_DCTV_visio($lien) {
	return '<object width="247" height="200">
				<param name="movie" value="'.$lien.'"></param>
				<param name="allowFullScreen" value="true"></param>
				<param name="allowScriptAccess" value="always"></param>
				<embed src="'.$lien.'" type="application/x-shockwave-flash" width="247" height="200" allowFullScreen="true" allowScriptAccess="always"></embed>
			</object>';
}

function timestamp_jour($jour) { // Renvoi le timestamp du jour indiqué de la semaine prochaine
	
	$time = time()+(60*60*24*7);
	$time = (date(w,$time) == 6)?$time:(
			(date(w,$time) == 0)?$time-1*60*60*24:(
			(date(w,$time) == 1)?$time-2*60*60*24:(
			(date(w,$time) == 2)?$time-3*60*60*24:(
			(date(w,$time) == 3)?$time-4*60*60*24:(
			(date(w,$time) == 4)?$time-5*60*60*24:(
			(date(w,$time) == 5)?$time-6*60*60*24:''
			))))));
	
	$time = mktime(0,0,0,date(m,$time),date(d,$time),date(y,$time));
	
	if($jour == "Samedi")
		return $time;
	elseif($jour == "Dimanche")
		return $time+1*60*60*24;
	elseif($jour == "Lundi")
		return $time+2*60*60*24;
	elseif($jour == "Mardi")
		return $time+3*60*60*24;
	elseif($jour == "Mercredi")
		return $time+4*60*60*24;
	elseif($jour == "Jeudi")
		return $time+5*60*60*24;
	elseif($jour == "Vendredi")
		return $time+6*60*60*24;

}

function affiche_infos_programmation($jour) {
	
	if($jour == "Samedi")
		{
		$sql = 'SELECT P.date_debut,P.duree,P.clip,P.id,C.nom,C.auteur,C.synopsis FROM DCN_programmation_tbl P, DCN_clips_tbl C WHERE P.clip = C.id AND P.date_debut >= '.timestamp_jour("Samedi").' AND P.date_debut < '.timestamp_jour("Dimanche").' ORDER BY date_debut';
		$clipjour = "clipsSam";
		$horairejour = "horairesSam";
		}
	elseif($jour == "Dimanche")
		{
		$sql = 'SELECT P.date_debut,P.duree,P.clip,P.id,C.nom,C.auteur,C.synopsis FROM DCN_programmation_tbl P, DCN_clips_tbl C WHERE P.clip = C.id AND P.date_debut >= '.timestamp_jour("Dimanche").' AND P.date_debut < '.timestamp_jour("Lundi").' ORDER BY date_debut';
		$clipjour = "clipsDim";
		$horairejour = "horairesDim";
		}
	elseif($jour == "Lundi")
		{
		$sql = 'SELECT P.date_debut,P.duree,P.clip,P.id,C.nom,C.auteur,C.synopsis FROM DCN_programmation_tbl P, DCN_clips_tbl C WHERE P.clip = C.id AND P.date_debut >= '.timestamp_jour("Lundi").' AND P.date_debut < '.timestamp_jour("Mardi").' ORDER BY date_debut';
		$clipjour = "clipsLun";
		$horairejour = "horairesLun";
		}
	elseif($jour == "Mardi")
		{
		$sql = 'SELECT P.date_debut,P.duree,P.clip,P.id,C.nom,C.auteur,C.synopsis FROM DCN_programmation_tbl P, DCN_clips_tbl C WHERE P.clip = C.id AND P.date_debut >= '.timestamp_jour("Mardi").' AND P.date_debut < '.timestamp_jour("Mercredi").' ORDER BY date_debut';
		$clipjour = "clipsMar";
		$horairejour = "horairesMar";
		}
	elseif($jour == "Mercredi")
		{
		$sql = 'SELECT P.date_debut,P.duree,P.clip,P.id,C.nom,C.auteur,C.synopsis FROM DCN_programmation_tbl P, DCN_clips_tbl C WHERE P.clip = C.id AND P.date_debut >= '.timestamp_jour("Mercredi").' AND P.date_debut < '.timestamp_jour("Jeudi").' ORDER BY date_debut';
		$clipjour = "clipsMer";
		$horairejour = "horairesMer";
		}
	elseif($jour == "Jeudi")
		{
		$sql = 'SELECT P.date_debut,P.duree,P.clip,P.id,C.nom,C.auteur,C.synopsis FROM DCN_programmation_tbl P, DCN_clips_tbl C WHERE P.clip = C.id AND P.date_debut >= '.timestamp_jour("Jeudi").' AND P.date_debut < '.timestamp_jour("Vendredi").' ORDER BY date_debut';
		$clipjour = "clipsJeu";
		$horairejour = "horairesJeu";
		}
	elseif($jour == "Vendredi")
		{
		$sql = 'SELECT P.date_debut,P.duree,P.clip,P.id,C.nom,C.auteur,C.synopsis FROM DCN_programmation_tbl P, DCN_clips_tbl C WHERE P.clip = C.id AND P.date_debut >= '.timestamp_jour("Vendredi").' AND P.date_debut < '.(timestamp_jour("Samedi")+60*60*24*7).' ORDER BY date_debut';
		$clipjour = "clipsVen";
		$horairejour = "horairesVen";
		}
	
	$req = mysql_query($sql);
	$res = mysql_num_rows($req);
	
	$tableauHeures =  array();
	$compteur=0;
	$verif=0;
	
	for($i=0;$i<$res;$i++)
		{
		$date_debut = mysql_result($req,$i,date_debut);
		$date_fin = $date_debut+mysql_result($req,$i,duree)*60*60;
		$heure_debut = date(G,$date_debut);
		$heure_fin = date(G,$date_fin);
		$heure_fin = ($heure_fin == 0)?24:$heure_fin;
		
		if($verif != $heure_debut) // Vérifie s'il y a un écart entre la fin d'un clip et le début d'un autre
			{
			$tableauHeures[$compteur][0] = $verif;
			$tableauHeures[$compteur][1] = $heure_debut;
			$tableauHeures[$compteur][2] = "Libre";
			$compteur++;
			}
		
		$tableauHeures[$compteur][0] = $heure_debut;
		$tableauHeures[$compteur][1] = $heure_fin;
		$tableauHeures[$compteur][2] = mysql_result($req,$i,clip);
		$tableauHeures[$compteur][3] = mysql_result($req,$i,nom);
		$tableauHeures[$compteur][4] = mysql_result($req,$i,id);
			
		$verif = $heure_fin;
		$compteur++;
		}
	
	if($verif != 24) // Vérifie la fin...
			{
			$tableauHeures[$compteur][0] = $verif;
			$tableauHeures[$compteur][1] = 24;
			$tableauHeures[$compteur][2] = "Libre";
			}
	
	$temp=0;
	$temp2=0;
	$horaires = "";
	for($i=0;$i<count($tableauHeures);$i++)
		{
		if($tableauHeures[$i][2] != "Libre" && $temp != 1) { print('<tr><td colspan="2">'); $temp=1; }
		
		if($tableauHeures[$i][2] != "Libre")
			{
			print('<form action="engine=services.php?lieu=dctv&action=maquette&id='.$tableauHeures[$i][4].'" method="post">');
			print($tableauHeures[$i][0].':00-'.$tableauHeures[$i][1].':00 <strong><a href="engine=services.php?lieu=dctv&action=voirclip&clip='.$tableauHeures[$i][2].'">'.$tableauHeures[$i][3].'</a></strong> - <input type="submit" name="submit" value="Retirer" style="border:none;background:none;" class="commelien" />'.(($_SESSION['poste'] == "Responsable de la DC TV" OR $_SESSION['statut'] == "Administrateur")?' - <input type="submit" name="submit" value="Supprimer" style="border:none;background:none;" class="commelien" />':''));
			print('</form>');
			}
		else
			{
			if($temp2!=1) { $temp2=1; $horaires .= '<br />De';}
			else $horaires .= '<br /><span style="line-height:20px;">ou</span><br />de';
			$horaires .= ' <select name="heure_debut-'.$i.'">';
			for($j=$tableauHeures[$i][0];$j<$tableauHeures[$i][1];$j++) $horaires .= '<option value="'.(($j<10)?'0'.$j:$j).'">'.(($j<10)?'0'.$j:$j).'h</option>';
			$horaires .= '</select> à  <select name="heure_fin-'.$i.'">';
			for($j=$tableauHeures[$i][0]+1;$j<$tableauHeures[$i][1]+1;$j++) $horaires .= '<option value="'.(($j<10)?'0'.$j:$j).'">'.(($j<10)?'0'.$j:$j).'h</option>';
			$horaires .= '</select> - <input type="submit" name="submit-'.$i.'" value="Valider" style="border:none;background:none;" class="commelien" />';
			}
		}
	
	if($temp == 1) print('</td></tr>');
	
	if($compteur != 24)
		{
		print('<tr>
			<td colspan="2"><a href="javascript:affiche_art(\''.$clipjour.'\',true);">Ajouter un nouveau clip</a>
			<div id="'.$clipjour.'" style="display:none;">');
			
			$sql2 = 'SELECT DISTINCT C.id,C.nom FROM DCN_programmation_tbl P, DCN_clips_tbl C WHERE P.clip = C.id AND ((P.date_debut > '.timestamp_jour("Samedi").' AND P.date_debut < '.(timestamp_jour("Samedi")+60*60*24*7).') OR P.date_debut = 0)' ;
			$req2 = mysql_query($sql2);
			$res2 = mysql_num_rows($req2);
			
			if($res2 == 0) print('<br />Aucun clip disponible');
			else print('<br />
			<form action="engine=services.php?lieu=dctv&action=maquette" method="post" style="display:inline;">
				<input type="hidden" name="jour" value="'.$jour.'" />
				<select name="evt" onchange="javascript:affiche_art(\''.$horairejour.'\',true);"><option value="#">Sélectionner</option>');
			for($i=0;$i<$res2;$i++) print('<option value="'.mysql_result($req2,$i,id).'">'.mysql_result($req2,$i,nom).'</option>');
			if($res2 != 0) print('</select>');
			
			print('</div>
			<div id="'.$horairejour.'" style="display:none;">
				'.$horaires.'
			</div>
			</form>
			</td>
		</tr>');
		}
}

function jour($time) {
	$jour = date(N,$time);
	if($jour == 1) return 'Lundi';
	elseif($jour == 2) return 'Mardi';
	elseif($jour == 3) return 'Mercredi';
	elseif($jour == 4) return 'Jeudi';
	elseif($jour == 5) return 'Vendredi';
	elseif($jour == 6) return 'Samedi';
	elseif($jour == 7) return 'Dimanche';
}

function heure($time) {
	return date(H,$time);
}

?>
