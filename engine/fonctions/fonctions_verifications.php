<?php

function est_mort($pseudo) {
	if($_SESSION['pseudo'] == $pseudo && $_SESSION['action'] == 'mort') return true;
	
	$sqlmort2 = 'SELECT action FROM principal_tbl WHERE pseudo="'.$pseudo.'"';
	$reqmort2 = mysql_query($sqlmort2);
	$resmort2 = mysql_num_rows($reqmort2);
	if(mysql_result($reqmort2,0,action) == "mort")
		{
		if($_SESSION['pseudo'] == $pseudo) $_SESSION['action'] = 'mort';
		return true;
		}
	
	$sqlmort1 = 'SELECT raison FROM deces_tbl WHERE victime="'.$pseudo.'"';
	$reqmort1 = mysql_query($sqlmort1);
	$resmort1 = mysql_num_rows($reqmort1);
	if($resmort1 != 0)
		{
		$sqlmort1 = 'UPDATE principal_tbl SET action="mort",num="0",rue="'.mysql_result($reqmort1,0,raison).'" WHERE pseudo= "'.$pseudo.'"';
		mysql_query($sqlmort1);
		if($_SESSION['pseudo'] == $pseudo) $_SESSION['action'] = 'mort';
		return true;
		}
	
	return false;
}

function est_un_DI($entreprise,$poste) {
    
    $entreprise = strtolower($entreprise);
    $poste = strtolower($poste);
    
    if ($poste == "president" && $entreprise == "conseil imperial")
        return true;
    elseif ($poste == "directeur du cipe" && $entreprise == "cipe")
        return true;
    elseif ($poste == "directeur du cie" && $entreprise == "cie")
        return true;
    elseif ($poste == "directeur des organisations" && $entreprise == "doi")
        return true;
    elseif ($poste == "directeur de la prison" && $entreprise == "prison")
        return true;
    elseif ($poste == "chef de la police" && $entreprise == "police")
        return true;
    elseif ($poste == "directeur des services" && $entreprise == "services techniques de la ville")
        return true;
    elseif ($poste == "premier consul" && $entreprise == "chambre des lois")
        return true;
    elseif ($poste == "directeur du di2rco" && $entreprise == "di2rco")
        return true;
    elseif ($poste == "directeur du dc network" && $entreprise == "dc network")
        return true;
    
    return false;
}

?>
