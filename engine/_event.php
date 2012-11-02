<?php
	session_start();
	include('inc_fonctions.php');
        
    $db = mysql_connect('localhost', 'CENSURE', 'CENSURE') or die("Erreur de connexion au serveur.");
    mysql_select_db('CENSURE',$db) or die("Erreur de connexion a la base de donnees.");
	
	/*if ($_GET['etape'] == 1) {
		$sql = 'UPDATE principal_tbl SET event = "0"';
		mysql_query($sql);
	}*/
	
	if ($_SESSION['pseudo'] == 'Paracelse') {
		switch ($_GET['etape']) {
			case 2:
				$_SESSION['sante'] = 10000;
				$_SESSION['fatigue'] = 10000;
				$sql = 'UPDATE principal_tbl SET sante = "'.$_SESSION['sante'].'", fatigue = "'.$_SESSION['fatigue'].'" WHERE id = ' . $_SESSION['id'];
				mysql_query($sql);
				break;
			default:
				break;
		}
	}
	
	mysql_close();
?>
