<?php

function traduct_geo($code) {'ok';
	$table = array(
		"A" => "N",
		"B" => "O",
		"C" => "P",
		"D" => "Q",
		"E" => "R",
		"F" => "S",
		"G" => "T",
		"H" => "U",
		"I" => "V",
		"J" => "W",
		"K" => "X",
		"L" => "Y",
		"M" => "Z"
	);
	$table2 = array_flip($table);
	
	for ($i=0;$i<strlen($code);$i++) {
		if (!preg_match('#[a-zA-Z]#', $code[$i])) {
			echo $code[$i];
			continue;
		}
		if (array_key_exists(strtoupper($code[$i]), $table))
			echo $table[strtoupper($code[$i])];
		else
			echo $table2[strtoupper($code[$i])];
	}
}

if ($_GET['geo'] != "")
	traduct_geo($_GET['geo']);

?>

<html>
    <head>
<script type="text/javascript" src="javascript/jQuery.js"></script>


		
    </head>
    <body>
        
        <?php
        
        include('inc_fonctions.php');
        
        $db = mysql_connect('localhost', 'CENSURE', 'CENSURE') or die("Erreur de connexion au serveur.");
        mysql_select_db('CENSURE',$db) or die("Erreur de connexion a la base de donnees.");
        
        /*$sql = "SELECT distinct pseudo FROM titres_tbl";
        $req = mysql_query($sql);
        $res = mysql_num_rows($req);
        
        for($i=0;$i<$res;$i++){
        	$sql2 = "SELECT id FROM titres_tbl WHERE pseudo = '".mysql_result($req,$i,pseudo)."' AND titre = 'Impatient'";
            $req2 = mysql_query($sql2);
        	
        	if (mysql_num_rows($req2))
        		continue;
        	
            $sql2 = "SELECT id FROM principal_tbl WHERE pseudo = '".mysql_result($req,$i,pseudo)."' AND total >= 1000000000";
            $req2 = mysql_query($sql2);
            
            if (mysql_num_rows($req2))
            	echo mysql_result($req,$i,pseudo)."<br />";
        }*/
        
        $sql = "SELECT DISTINCT nom FROM bourse_argent_tbl";
        $req = mysql_query($sql);
        $res = mysql_num_rows($req);

        for($i=0;$i<$res;$i++){
        	$nom = mysql_result($req, $i, nom);
	        $sql2 = "SELECT SUM(montant) as credits FROM bourse_argent_tbl WHERE nom = '".$nom."' AND depot=0";
	        $req2 = mysql_query($sql2);
	        
	        if (($argent = mysql_result($req2, 0, credits)) > 0) {
	        	$sql2 = "UPDATE principal_tbl SET credits = credits + " . $argent . " WHERE pseudo = '".$nom."'";
//		        $req2 = mysql_query($sql2);
		        
//		        message($nom, "Bonjour,<br /><br />La bourse TheExchanger vous rembourse la somme investie dans son entreprise, soit un total de $argent\C qui a directement été viré dans votre inventaire.<br />Nous vous remercions pour votre participation à cette experience.<br /><br />Bonne continuation sur Dreadcast");
//	        	echo "Positif ($nom) : $argent<br />";
	        }
	    }
        
        /*$pseudo = "Rectari";
        
        $sql = "DELETE FROM wikast_joueurs_tbl WHERE pseudo = '$pseudo'";
        $req = mysql_query($sql);
        
        $sql = "SELECT * FROM wikast_edc_articles_tbl WHERE auteur = '$pseudo'";
        $req = mysql_query($sql);
        $res = mysql_num_rows($req);
        
        for($i=0;$i<$res;$i++){
            $sql2 = "DELETE FROM wikast_edc_commentaires_tbl WHERE article = '".mysql_result($req,$i,id)."'";
            $req2 = mysql_query($sql2);
            
            $sql2 = "DELETE FROM wikast_edc_articles_tbl WHERE id = '".mysql_result($req,$i,id)."'";
            $req2 = mysql_query($sql2);
        }
        
        $sql = 'DELETE FROM wikast_edc_articles_tbl WHERE cible = "'.$pseudo.'"';
        $req = mysql_query($sql);*/
        
        ?>
        
        
    </body>
</html>
