<?php
if(@mysql_connect('213.186.59.150', 'dreadcast', 'uVKSMUEay3vrE7KX'))
	{
	$db2 = @mysql_select_db('adds');
	
	$sql = 'SELECT id,affichages,image FROM adds_campagnes_tbl WHERE etat="2" AND produits LIKE "%dreadcast%" ORDER BY RAND()';
	$req = mysql_query($sql);
	$resp = @mysql_num_rows($req);
	
	if($resp>0)
		{
		$image_pub = mysql_result($req,0,image);
		$id_pub = mysql_result($req,0,id);
		$sql1 = 'SELECT affichages FROM adds_archives_tbl WHERE id_campagne="'.mysql_result($req,0,id).'" AND timestamp="'.mktime(0,0,0,date('m'),date('d'),date('Y')).'" AND produit="dreadcast"';
		$req1 = mysql_query($sql1);
		$res1p = mysql_num_rows($req1);
		if($res1p>0)
			{
			$sql2 = 'UPDATE adds_archives_tbl SET affichages=affichages+1 WHERE id_campagne="'.mysql_result($req,0,id).'" AND timestamp="'.mktime(0,0,0,date('m'),date('d'),date('Y')).'" AND produit="dreadcast"';
			$req2 = mysql_query($sql2);
			$sql3 = 'SELECT SUM(affichages) FROM adds_archives_tbl WHERE id_campagne="'.mysql_result($req,0,id).'"';
			$req3 = mysql_query($sql3);
			if(mysql_result($req3,0,"SUM(affichages)")>=mysql_result($req,0,affichages) && mysql_result($req,0,affichages)>0)
				{
				$sql2 = 'UPDATE adds_campagnes_tbl SET etat="3" WHERE id="'.mysql_result($req,0,id).'"';
				$req2 = mysql_query($sql2);
				}
			}
		else
			{
			$sql2 = 'INSERT INTO adds_archives_tbl(id,id_campagne,timestamp,produit,affichages,clics) VALUES("","'.mysql_result($req,0,id).'","'.mktime(0,0,0,date('m'),date('d'),date('Y')).'","dreadcast","1","0")';
			$req2 = mysql_query($sql2);
			}
		}
	
	@mysql_close();
	}
?>
