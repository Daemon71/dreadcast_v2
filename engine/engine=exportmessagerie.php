<?php 
session_start(); 

header('Content-Type: text/plain');
header('Content-Disposition: attachment; filename="Messagerie_' . $_SESSION['pseudo'] . '_'.date("d-m-Y").'.txt"');

$db = mysql_connect('localhost', 'CENSURE', 'CENSURE') or die("Erreur de connexion au serveur des messages.");
mysql_select_db('CENSURE',$db) or die("Erreur de connexion a la base de donnees des messages.");

$pseudo = $_SESSION['pseudo'];

$sql = 'SELECT id,auteur,cible,objet,message,moment FROM messages_tbl WHERE cible = "'.$pseudo.'" ORDER BY moment DESC';
$req = mysql_query($sql);
$res = mysql_num_rows($req);

$return = "Dreadcast.net
Messagerie de $pseudo au ".date("d/m/Y")."
Total : $res message(s)

";

while($res = mysql_fetch_assoc($req)) {
	$indent = 0;
	$titre = $res["objet"];
	if(preg_match("/^\[MASQUE\]/", $titre)) {
		$anonyme = true;
		$titre = str_replace("[MASQUE]", "", $titre);
	} else
		$anonyme = false;
	
	$message = str_replace("<br />", "\n", $res["message"]);;
	$message = str_replace("<strong>", "", $message);
	$message = str_replace("</strong>", "", $message);
	
	$auteur = $anonyme ? "-Anonyme-" : $res["auteur"];
	$return .= "________________________________________________________________________________________________________________
| Auteur : $auteur
| Titre : $titre
| Date : ".date("d/m/Y H:i", $res["moment"])."
|_______________________________________________________________________________________________________________
| 
|";
	
	$message = str_replace("[CONVERSATION]", "\n\n[CONVERSATION]", $message);
	$lignes = explode("\n", $message);
	foreach($lignes as $ligne) {
		if(ereg('\[CONVERSATION\]', $ligne)) {
			$ligne = str_replace("[CONVERSATION]", "", $ligne);
			$indent++;
		}
		
		for($j=0;$j<$indent;$j++)
			$return .= "|";
		
		$return .= " $ligne\n|";
	}
	$return .= "\n\n";
}

echo decode($return);

mysql_close($db);

function decode($texte) {
	
	$texte = str_replace("&#039;" , "'", $texte);
	$texte = str_replace("&quot;" , "\"", $texte);
	$texte = str_replace("&gt;" , ">", $texte);
	$texte = str_replace("&lt;" , "<", $texte);
	$texte = str_replace("&amp;" , "&", $texte); 
	$texte = str_replace("&deg;" , "�", $texte);
	$texte = str_replace("&sup2;" , "�", $texte);
	$texte = str_replace("&raquo;" , "�", $texte);
	$texte = str_replace("&laquo;" , "�", $texte);
	$texte = str_replace("&aelig;", "�", $texte);
	$texte = str_replace("&AElig;", "�", $texte);
	$texte = str_replace("&eacute;" , "�", $texte);
	$texte = str_replace("&Eacute;" , "�", $texte);
	$texte = str_replace("&egrave;" , "�", $texte);
	$texte = str_replace("&Egrave;" , "�", $texte);
	$texte = str_replace("&ecirc;" , "�", $texte);
	$texte = str_replace("&Ecirc;" , "�", $texte);
	$texte = str_replace("&euml;" , "�", $texte);
	$texte = str_replace("&Euml;" , "�", $texte);
	$texte = str_replace("&agrave;" , "�", $texte);
	$texte = str_replace("&Agrave;" , "�", $texte);
	$texte = str_replace("&acirc;" , "�", $texte);
	$texte = str_replace("&Acirc;" , "�", $texte);
	$texte = str_replace("&auml;" , "�", $texte);
	$texte = str_replace("&Auml;" , "�", $texte);
	$texte = str_replace("&icirc;" , "�", $texte);
	$texte = str_replace("&Icirc;" , "�", $texte);
	$texte = str_replace("&iuml;", "�", $texte);
	$texte = str_replace("&Iuml;", "�", $texte);
	$texte = str_replace("&ugrave;" , "�", $texte);
	$texte = str_replace("&ucirc;" , "�", $texte);
	$texte = str_replace("&Ucirc;" , "�", $texte);
	$texte = str_replace("&uuml;", "�", $texte);
	$texte = str_replace("&Uuml;", "�", $texte);
	$texte = str_replace("&ocirc;" , "�", $texte);
	$texte = str_replace("&Ocirc;" , "�", $texte);
	$texte = str_replace("&ouml;" , "�", $texte);
	$texte = str_replace("&Ouml;" , "�", $texte);
	$texte = str_replace("&ccedil;" , "�", $texte);
	$texte = str_replace("&Ccedil;" , "�", $texte);
	
	return $texte;
}

?>
