<?php

$server = 'localhost';
$login = 'CENSURE';
$mdp = 'CENSURE';
$database = 'CENSURE';
$table = 'cheminement_internautes_tbl';

$save = '';

$db = mysql_connect($server, $login, $mdp) or die("Erreur de connexion au serveur.");
mysql_select_db($database,$db) or die("Erreur de connexion a la base de donnees.");

foreach ($_GET as $cle => $val) {
    if ($cle == 'server' || $cle == 'login' || $cle == 'mdp' || $cle == 'database' || $cle == 'table')
        continue;

    $save['champs'][] = $cle;
    $save['valeurs'][] = '"'.$val.'"';
}

$sql = 'INSERT INTO '.$table.' (date,'.join(',',$save['champs']).') VALUES ('.time().','.join(',',$save['valeurs']).')';
mysql_query($sql);

mysql_close($db);

?>
