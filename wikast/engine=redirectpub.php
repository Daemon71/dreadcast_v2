<?php
if(@mysql_connect('213.186.59.150', 'dreadcast', 'uVKSMUEay3vrE7KX'))
	{
	mysql_select_db('adds') or die("Erreur de connexion a la base de donnees.");
	
	$sql = 'SELECT id FROM adds_clics_tbl WHERE id_campagne="'.$_GET['id'].'" AND IP= "'.$_SERVER['REMOTE_ADDR'].'"';
	$req = mysql_query($sql);
	$res = mysql_num_rows($req);
	if($res==0)
		{
		$sql = 'UPDATE adds_archives_tbl SET clics=clics+1 WHERE id_campagne="'.$_GET['id'].'" AND timestamp="'.mktime(0,0,0,date('m'),date('d'),date('Y')).'" AND produit="wikast"';
		$req = mysql_query($sql);
		$sql = 'INSERT INTO adds_clics_tbl(id,id_campagne,IP) VALUES("","'.$_GET['id'].'","'.$_SERVER['REMOTE_ADDR'].'")';
		$req = mysql_query($sql);
		$sql2 = 'SELECT clics FROM adds_campagnes_tbl WHERE id="'.$_GET['id'].'"';
		$req2 = mysql_query($sql2);
		$sql3 = 'SELECT SUM(clics) FROM adds_archives_tbl WHERE id_campagne="'.$_GET['id'].'"';
		$req3 = mysql_query($sql3);
		if(mysql_result($req3,0,"SUM(clics)")>=mysql_result($req2,0,clics) && mysql_result($req2,0,clics)>0)
			{
			$sql4 = 'UPDATE adds_campagnes_tbl SET etat="3" WHERE id="'.$_GET['id'].'"';
			$req4 = mysql_query($sql4);
			}
		}
	
	$sql = 'SELECT lien FROM adds_campagnes_tbl WHERE id="'.$_GET['id'].'"';
	$req = mysql_query($sql);
	$res = mysql_num_rows($req);
	mysql_close();
	if($res>0)
		{
		print('<meta http-equiv="refresh" content="0 ; url='.mysql_result($req,0,lien).'">');
		}
	else
		{
		print('Erreur de redirection.<br />Merci de contacter l\'administration.');
		}
	}
else
	{
	print('Erreur de connexion au serveur publicitaire.');
	}
?>
