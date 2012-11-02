<?php

function recup_stats($nomjoueur){
	$sql = 'SELECT discretion,combat,observation,resistance FROM principal_tbl WHERE pseudo= "'.$nomjoueur.'"';
	$req = mysql_query($sql);
	$res = mysql_num_rows($req);
	
	$discretion = ($discretion>100)?10:floor(mysql_result($req,0,discretion)/10);
	$combat = ($combat>100)?10:floor(mysql_result($req,0,combat)/10);
	$observation = ($observation>100)?10:floor(mysql_result($req,0,observation)/10);
	$resistance = ($resistance>100)?10:floor(mysql_result($req,0,resistance)/10);
	
	return array('discretion'=> $discretion, 'combat' => $combat, 'observation' => $observation, 'resistance' => $resistance);
}

?>
