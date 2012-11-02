<?php 
session_start(); 
if($_SESSION['id']=="")
	{
	print('<meta http-equiv="refresh" content="0 ; url=http://v2.dreadcast.net/"> ');
	exit();
	}
if($_SESSION['action']=="Vacances")
	{
	print('<meta http-equiv="refresh" content="0 ; url=http://v2.dreadcast.net/ingame/engine=cryo.php"> ');
	exit();
	}
if($_SESSION['action']=="mort")
	{
	print('<meta http-equiv="refresh" content="0 ; url=http://v2.dreadcast.net/ingame/engine=mort.php"> ');
	exit();
	}
if(($_SESSION['statut']!="Administrateur") && ($_SESSION['statut']!="DÈveloppeur") && ($_SESSION['statut']!="ModÈrateur RPIG") && ($_SESSION['statut']!="ModÈrateur communication"))
	{
	print('<meta http-equiv="refresh" content="0 ; url=http://v2.dreadcast.net/ingame/engine.php"> ');
	exit();
	}

$db = mysql_connect('localhost', 'CENSURE', 'CENSURE') or die("Erreur de connexion au serveur des messages.");
mysql_select_db('CENSURE',$db) or die("Erreur de connexion a la base de donnees des messages.");

$sql = 'SELECT id,rue,num,action,alim,sante,fatigue FROM principal_tbl WHERE pseudo= "'.$_SESSION['pseudo'].'"' ;
$req = mysql_query($sql);
$_SESSION['id'] = mysql_result($req,0,id);
$_SESSION['rue'] = mysql_result($req,0,rue);
$_SESSION['num'] = mysql_result($req,0,num);
$_SESSION['action'] = mysql_result($req,0,action);
$_SESSION['alim'] = mysql_result($req,0,alim);
$_SESSION['sante'] = mysql_result($req,0,sante);
$_SESSION['fatigue'] = mysql_result($req,0,fatigue);
$_SESSION['lieu'] = strtolower($_SESSION['rue']);
$_SESSION['lieu'] = ucwords($_SESSION['lieu']);

if($_GET['id']!="")
    {
    $id = $_GET['id'];
    $etoile = ($_GET['etoile'] == "")?0:$_GET['etoile'];
    
    $sql = 'SELECT nom,prix FROM objets_tbl WHERE id = '.$id.'';
    $req = mysql_query($sql);
    $res = mysql_num_rows($req);
    
    if($res!=0)
        {
        $sql = 'UPDATE objets_tbl SET prod="1" WHERE id="'.$id.'"' ;
        mysql_query($sql);
        
        $sql = 'UPDATE recherche_plans_tbl SET etoile="'.$etoile.'" WHERE ido="'.$id.'"' ;
        mysql_query($sql);
    
        $sql2 = 'SELECT nom FROM entreprises_tbl WHERE id = (SELECT ide FROM recherche_plans_tbl WHERE ido ='.$id.')';
        $req2 = mysql_query($sql2);
        $res2 = mysql_num_rows($req2);
    
        if($res2!=0)
            {
            $sql = 'INSERT INTO stocks_tbl(id,entreprise,objet,nombre,pvente) VALUES("","'.mysql_result($req2,0,nom).'","'.mysql_result($req,0,nom).'","0","'.mysql_result($req,0,prix).'")' ;
            mysql_query($sql);
            
            $sqltmp = 'SELECT poste FROM e_'.str_replace(" ","_",mysql_result($req2,0,nom)).'_tbl WHERE type="chef"';
            $reqtmp = mysql_query($sqltmp);
            $restmp = mysql_num_rows($reqtmp);
            
            $sql3 = 'SELECT pseudo FROM principal_tbl WHERE entreprise = "'.mysql_result($req2,0,nom).'" AND type = "'.mysql_result($reqtmp,0,poste).'"';
            $req3 = mysql_query($sql3);
            $res3 = mysql_num_rows($req3);
            
            if($res3!=0)
                {
                $message = '<br /><br />Votre plan pour l\'objet '.mysql_result($req,0,nom).' a &eacute;t&eacute; valid&eacute; par l\'administration. Vous pouvez &agrave; pr&eacute;sent le commercialiser.';
                for($hop=0;$hop<$res3;$hop++)
                    {
                    $sql = 'INSERT INTO messages_tbl(id,auteur,cible,message,objet,moment,nouveau) VALUES("","Dreadcast","'.mysql_result($req3,$hop,pseudo).'","'.$message.'","Plan accept&eacute;","'.time().'","oui")';
            		mysql_query($sql);
            		}
        		}
            }
        }
    }
elseif($_POST['id']!="" AND $_POST['raison']!="")
    {
    $id = $_POST['id'];
    
    $sql = 'SELECT nom FROM objets_tbl WHERE id = '.$id.'';
    $req = mysql_query($sql);
    $res = mysql_num_rows($req);
    
    if($res!=0)
        {
        $sql = 'DELETE FROM objets_tbl WHERE id="'.$id.'"' ;
        mysql_query($sql);
        
        $sql2 = 'SELECT nom FROM entreprises_tbl WHERE id = (SELECT ide FROM recherche_plans_tbl WHERE ido ='.$id.')';
        $req2 = mysql_query($sql2);
        $res2 = mysql_num_rows($req2);
        
        if($res2!=0)
            {
            $sqltmp = 'SELECT poste FROM e_'.str_replace(" ","_",mysql_result($req2,0,nom)).'_tbl WHERE type="chef"';
            $reqtmp = mysql_query($sqltmp);
            
            $sql3 = 'SELECT pseudo FROM principal_tbl WHERE entreprise = "'.mysql_result($req2,0,nom).'" AND type = "'.mysql_result($reqtmp,0,poste).'"';
            $req3 = mysql_query($sql3);
            $res3 = mysql_num_rows($req3);
            
            if($res3!=0)
                {
                $message = '<br /><br />Votre plan pour l\'objet '.mysql_result($req,0,nom).' n\'a pas &eacute;t&eacute; valid&eacute; par l\'administration. Vous devez le cr&eacute;er de nouveau en tenant compte des remarques suivantes.<br /><br />Raisons :<br />';
                for($hop=0;$hop<$res3;$hop++)
                    {
                    $sql = 'INSERT INTO messages_tbl(id,auteur,cible,message,objet,moment,nouveau) VALUES("","Dreadcast","'.mysql_result($req3,$hop,pseudo).'","'.$message.htmlentities(stripslashes($_POST['raison'])).'","Plan refus&eacute;","'.time().'","oui")';
        		    mysql_query($sql);
        		    }
        		}
    		}
		$sql = 'DELETE FROM recherche_plans_tbl WHERE ido="'.$id.'"' ;
        mysql_query($sql);
        }
    }

$sql = 'SELECT id FROM articlesprop_tbl' ;
$req = mysql_query($sql);
$resa = mysql_num_rows($req);

mysql_close($db);
?>
<?php if ((date("H")>=21) || (date("H")<=7)) { include("inc_haut_n.php"); } else { include("inc_haut_j.php");} ?>

<div id="haut">
	<?php include("inc_entete.php"); ?>
	<div class="haut_aligngauche">
		<p>
		<div class="titrepage">
			Panneau d'administration
		</div>
		<b class="module4ie"><a href="engine=panneau.php" class="module4"><img src="im_objets/icon_retour.gif" alt="Retour" />Retour</b></a>
		</p>
	</div>
</div>
<?php include('inc_admin.php'); ?>
<div id="centre">
<p>
<div class="messagesvip">
<?php 

if(($_SESSION['statut']=="Administrateur") OR ($_SESSION['statut']=="ModÈrateur communication") OR ($_SESSION['statut']=="DÈveloppeur"))
	{
	$db = mysql_connect('localhost', 'CENSURE', 'CENSURE') or die("Erreur de connexion au serveur des messages.");
	mysql_select_db('CENSURE',$db) or die("Erreur de connexion a la base de donnees des messages.");
	
	$sql = 'SELECT id,nom,image,type,puissance,ecart,distance,modes,infos,prix FROM objets_tbl WHERE prod = -1 ORDER BY id';
	$req = mysql_query($sql);
	$res = mysql_num_rows($req);
	
	if($res!=0)
		{
		print('<table width="90%"  border="1" align="center" cellpadding="0" cellspacing="0">
					<tr bgcolor="#B6B6B6">
					  <th><div align="center">Image</div></th>
					  <th><div align="center">Nom</div></th>
					  <th><div align="center">Type</div></th>
					  <th><div align="center">D&eacute;tail</div></th>
					  <th><div align="center">Prix</div></th>
					  <th><div align="center">Cr&eacute;ateur</div></th>
					</tr>');
		
		for($i=0; $i != $res ; $i++) 
			{ 
			$id = mysql_result($req,$i,id);
			$image = mysql_result($req,$i,image);
			$nom = mysql_result($req,$i,nom);
			
			if(mysql_result($req,$i,type)=="armtu") $type = "Arme de tir";
			elseif(mysql_result($req,$i,type)=="armcu") $type = "Arme de corps &agrave; corps";
			elseif(mysql_result($req,$i,type)=="vetu") $type = "V&ecirc;tement";
			elseif(mysql_result($req,$i,type)=="obju") $type = "Objet";
			elseif(mysql_result($req,$i,type)=="ouu") $type = "Objet usage unique";
			
			$bonus = '';
			
			if($type=='Arme de tir' OR $type=='Arme de corps &agrave; corps')
			    {
			    if($type=='Arme de tir')
			        {
			        $modes = "<strong>Modes</strong><br />";
    			    if(ereg('s',mysql_result($req,$i,modes))) { $modes .= "s "; $mult=1; }
	    		    if(ereg('b',mysql_result($req,$i,modes))) { $modes .= "b "; $mult=3; }
		    	    if(ereg('a',mysql_result($req,$i,modes))) { $modes .= "a "; $mult=9; }
		    	    if($mult=="") $mult=1;
			        if($modes == "<strong>Modes</strong><br />") $modes .= "Aucun";
			        $modes .= "<br />";
			         
			        $degatmax = (mysql_result($req,$i,puissance)+mysql_result($req,$i,ecart))*$mult;
			        if($degatmax>=150) $degatmax = '<strong>Degats max</strong><br /><span style="color:red;">'.$degatmax.'</span><br />';
			        else $degatmax = '<strong>Degats max</strong><br />'.$degatmax.'<br />';
			        }
			    else $modes = "";
			    $bonus .= '<strong>Puissance</strong><br />'.mysql_result($req,$i,puissance).'-'.(mysql_result($req,$i,puissance)+mysql_result($req,$i,ecart)).'<br /><strong>Port&eacute;e</strong><br />'.mysql_result($req,$i,distance).'m<br />'.$modes.$degatmax;
			    }
			
			$sql2 = 'SELECT nature,bonus FROM recherche_effets_tbl WHERE ido = '.$id.'';
        	$req2 = mysql_query($sql2);
        	$res2 = mysql_num_rows($req2);
        	
        	if($res2!=0) $bonus .= '<strong>Bonus</strong><br />';
        	
        	for($j=0;$j<$res2;$j++)
        	    {
			if(mysql_result($req2,$j,bonus)==0) $bonus .= ucwords(mysql_result($req2,$j,nature)).'<br />';
			else $bonus .= ucwords(substr(mysql_result($req2,$j,nature),0,3)).'. +'.mysql_result($req2,$j,bonus).'<br />';
        	    }
			
			$prix = mysql_result($req,$i,prix);
			
			$sql2 = 'SELECT nom FROM entreprises_tbl WHERE id = (SELECT ide FROM recherche_plans_tbl WHERE ido ='.$id.')';
        	$req2 = mysql_query($sql2);
        	$res2 = mysql_num_rows($req2);
        	
        	if($res2!=0) $createur = mysql_result($req2,0,nom);
			
			$infos = mysql_result($req,$i,infos);
			
			print('
				<tr>
					<td><img src="im_objets/'.$image.'" style="position:relative;top:1px;margin:0 1px 0 1px;" /></td>
					<td><a href="#" onclick="javascript:affiche_tab(\''.$id.'\',true);">'.$nom.'</a></td>
					<td>'.$type.'</td>
					<td>'.$bonus.'</td>
				    <td>'.$prix.' Cr</td>
					<td>'.$createur.'</td>
				</tr>
				<tr id="'.$id.'" style="display:none;">
				    <td colspan="6" style="text-align:justify;padding:10px;"><strong>Infos</strong><br />'.$infos.'<br /><br /><a href="engine=planadmin.php?id='.$id.'&choix=ok">Accepter</a> - <a href="engine=planadmin.php?id='.$id.'&choix=ok&etoile=1">1 &eacute;toile</a> - <a href="engine=planadmin.php?id='.$id.'&choix=ok&etoile=2">2 &eacute;toiles</a> - <a href="engine=planadmin.php?id='.$id.'&choix=ok&etoile=3">3 &eacute;toiles</a> - <a href="#" onclick="javascript:affiche_art(\'form-'.$id.'\',true);">Refuser</a><br /><br /><form action="engine=planadmin.php" method="POST" id="form-'.$id.'" style="display:none;">Raison du refus<br /><input type="hidden" name="id" value="'.$id.'" />
			<textarea name="raison" id="textarea" style="width:350px;height:100px;"></textarea><br /><input name="refuser" type="submit" value="Envoyer" class="ok2" /><br /><br /></form><a href="#" onclick="javascript:affiche_tab(\''.$id.'\',false);">Fermer</a></td>
				</tr>');
			
			}
		print('</table>');
		}
	else
		{
		print("<strong>Il n'y a aucun plan en attente.</strong><br />");
		}
		
	mysql_close($db);
	}
else
	{
	print('<meta http-equiv="refresh" content="0 ; url=http://v2.dreadcast.net/ingame/engine.php"> ');
	exit();
	}

?>
</div>
</p>
</div>
<?php if($chat=="oui") 	{ if ((date("H")>=21) || (date("H")<=7)) { include("inc_bas_n_c.php"); } else { include("inc_bas_j_c.php"); } } else {	if ((date("H")>=21) || (date("H")<=7)) { include("inc_bas_n.php"); } else { include("inc_bas_j.php"); }	} ?>
<?php include("inc_bas_de_page.php"); ?>
