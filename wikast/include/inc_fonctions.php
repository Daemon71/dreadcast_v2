<?php

function statut($statut) {
	if($statut=="Administrateur") $result = 7;
	elseif($statut=="Modérateur") $result = 6;
	elseif($statut=="Platinium") $result = 5;
	elseif($statut=="Gold") $result = 4;
	elseif($statut=="Silver") $result = 3;
	elseif($statut=="Compte VIP") $result = 2;
	elseif($statut=="Joueur") $result = 1;
	else $result = 0;
	return $result;
}

function transforme_texte($entree) {
	$sortie = $entree;
	
	$sortie = str_replace("&lt;br /&gt;", "",$sortie);
	
	$sortie = preg_replace("#\[g\](.+)\[/g\]#isU","<strong>$1</strong>",$sortie);
	$sortie = preg_replace("#\[i\](.+)\[/i\]#isU","<em>$1</em>",$sortie);
	$sortie = str_replace("\[quote\]\[/quote\]","",$sortie);
	$i=0;
	while(preg_match("#\[quote\]#",$sortie) && preg_match("#\[/quote\]#",$sortie))
		{
		if($i++ > 20) break;
		$sortie = preg_replace("#\[quote\](.+)\[\/quote\]#isU","<div class=\"text-quote\">$1</div>",$sortie);
		}
	$sortie = preg_replace("#\[couleur type=&lt;(.+)&gt;\](.+)\[/couleur\]#isU","<span class=\"$1\">$2</span>",$sortie);
	$sortie = preg_replace("#\[lien url=&lt;(.+)&gt;\](.+)\[/lien\]#isU","<a href=\"$1\">$2</a>",$sortie);
	$sortie = preg_replace("#\[centrer\](.+)\[/centrer\]#isU","<center>$1</center>",$sortie);
	$sortie = preg_replace("#\[centre\](.+)\[/centre\]#isU","<div style=\"text-align: center;\">$1</div>",$sortie);
	$sortie = preg_replace("#\[gauche\](.+)\[/gauche\]#isU","<div style=\"text-align: left;\">$1</div>",$sortie);
	$sortie = preg_replace("#\[droite\](.+)\[/droite\]#isU","<div style=\"text-align: right;\">$1</div>",$sortie);
	if ($_SESSION['statut'] == "Administrateur") {
//		$sortie = preg_replace("#\[invisible\](.+)\[/invisible\]#isU","<div class=\"invisible\">$1</div>",$sortie);
//		$sortie = preg_replace("#\[invisible=([^\]]+)\](.+)\[/invisible\]#isU","<span title=\"$1\">$2</span>",$sortie);
	} else {
		$sortie = preg_replace("#\[invisible\](.+)\[/invisible\]#isU","<span class=\"invisible\">$1</span>",$sortie);
		$sortie = preg_replace("#\[invisible=([^\]]+)\](.+)\[/invisible\]#isU","<span title=\"$1\">$2</span>",$sortie);
	}
	$sortie = preg_replace("#\[code\](.+)<br />(.+)\[/code\]#isU","<pre>$1$2</pre>",$sortie);
	$sortie = preg_replace("#<pre>(.+)<br />(.+)<pre>#isU","<pre>$1$2</pre>",$sortie);
	
	$sortie = str_replace(":)", "<img src=\"smileys/smiley1.gif\" alt=\"smiley\" class=\"smiley\">",$sortie);
	$sortie = str_replace(":-)", "<img src=\"smileys/smiley1.gif\" alt=\"smiley\" class=\"smiley\">",$sortie);
	$sortie = str_replace(":DD", "<img src=\"smileys/smiley10.gif\" alt=\"smiley\" class=\"smiley\">",$sortie);
	$sortie = str_replace(":D", "<img src=\"smileys/smiley2.gif\" alt=\"smiley\" class=\"smiley\">",$sortie);
	$sortie = str_replace(":-D", "<img src=\"smileys/smiley2.gif\" alt=\"smiley\" class=\"smiley\">",$sortie);
	$sortie = str_replace(":p", "<img src=\"smileys/smiley3.gif\" alt=\"smiley\" class=\"smiley\">",$sortie);
	$sortie = str_replace(":-p", "<img src=\"smileys/smiley3.gif\" alt=\"smiley\" class=\"smiley\">",$sortie);
	$sortie = str_replace(":P", "<img src=\"smileys/smiley3.gif\" alt=\"smiley\" class=\"smiley\">",$sortie);
	$sortie = str_replace(":-P", "<img src=\"smileys/smiley3.gif\" alt=\"smiley\" class=\"smiley\">",$sortie);
	$sortie = str_replace(" XD", " <img src=\"smileys/smiley4.gif\" alt=\"smiley\" class=\"smiley\">",$sortie);
	$sortie = str_replace("XD ", "<img src=\"smileys/smiley4.gif\" alt=\"smiley\" class=\"smiley\">  ",$sortie);
	$sortie = str_replace("[s]", "<img src=\"smileys/smiley5.gif\" alt=\"smiley\" class=\"smiley\">",$sortie);
	$sortie = str_replace("[paf]", "<img src=\"smileys/smiley6.gif\" alt=\"smiley\" class=\"smiley\">",$sortie);
	$sortie = str_replace(" ;)", " <img src=\"smileys/smiley7.gif\" alt=\"smiley\" class=\"smiley\">",$sortie);
	$sortie = str_replace(";) ", "<img src=\"smileys/smiley7.gif\" alt=\"smiley\" class=\"smiley\"> ",$sortie);
	$sortie = str_replace("&gt;.&lt;", "<img src=\"smileys/smiley8.gif\" alt=\"smiley\" class=\"smiley\">",$sortie);
	$sortie = str_replace("dtclp", " <img src=\"smileys/pile.gif\" alt=\"smiley\" class=\"smiley\">",$sortie);
	$sortie = str_replace("[hinhin]", "<img src=\"smileys/smiley9.gif\" alt=\"smiley\" class=\"smiley\">",$sortie);
	$sortie = str_replace("[v3]", "<img src=\"smileys/smileyv3.gif\" alt=\"smiley\" class=\"smiley\">",$sortie);
	$sortie = str_replace("[wahou]", "<img src=\"smileys/smiley11.gif\" alt=\"smiley\" class=\"smiley\">",$sortie);
	$sortie = str_replace("[baille]", "<img src=\"smileys/smileybaille.gif\" alt=\"smiley\" class=\"smiley\">",$sortie);
	
	if(preg_match("/\<pre\>/",$sortie) && preg_match("/\<\/pre\>/",$sortie))
		{
		$tmp = explode("<pre>",$sortie);
		$avant = $tmp[0].'<pre>';
		for($k=1;$k<count($tmp);$k++)
			{
			$tmp2 = explode("</pre>",$tmp[$k]);
			$apres = $tmp2[1];
			$elt = $tmp2[0];
			
			$avant .= str_replace('<br />','',$elt) . '</pre>' . $apres;
			}
		$sortie = $avant;
		}
	
	if(preg_match("/\[img url\=\&lt\;/",$sortie) && preg_match("/\&gt\; \/\]/",$sortie))
		{
		$tmp = explode("[img url=&lt;",$sortie);
		$temp3 = $tmp[0];
		for($k=1;$k<count($tmp);$k++)
			{
			$tmp2 = explode("&gt; /]",$tmp[$k]);
			if(preg_match("/\.jpg/",$tmp2[0]) OR preg_match("/\.bmp/",$tmp2[0]) OR preg_match("/\.jpeg/",$tmp2[0]) OR preg_match("/\.png/",$tmp2[0]) OR preg_match("/\.gif/",$tmp2[0]) OR preg_match("/banniere_joueur\.php/",$tmp2[0]))
				{
				$temp3 .= '[img url=&lt;';
				$temp3 .= $tmp2[0];
				$temp3 .= '&gt; /]'.$tmp2[1];
				}
			else 
				{
				$temp3 .= "Format d'image incorrect<br />".$tmp2[1];
				}
			}
		$sortie = $temp3;
		$sortie = str_replace("[img url=&lt;", "<img src=\"",$sortie);
		$sortie = str_replace("&gt; /]", "\" />",$sortie);
		}
	
	return $sortie;
}

?>
