<?php
$db = mysql_connect('localhost', 'CENSURE', 'CENSURE') or die("Erreur de connexion au serveur des messages.");
mysql_select_db('CENSURE',$db) or die("Erreur de connexion a la base de donnees des messages.");

$sql = 'SELECT id FROM principal_tbl WHERE connec= "oui"' ;
$req = mysql_query($sql);
$res = mysql_num_rows($req);
$nbreconnec = 0;
for($s=0 ; $s!=$res ; $s++)
	{
	$sql5 = 'SELECT pseudo FROM principal_tbl WHERE id= "'.mysql_result($req,$s,'id').'" AND rue= "'.$_SESSION['lieu'].'" AND Num="'.$_SESSION['num'].'"';
	$req5 = mysql_query($sql5);
	$res5 =  mysql_num_rows($req5);
	for($f=0 ; $f!=$res5 ; $f++)
		{
		if(mysql_result($req5,0,'pseudo')!=$_SESSION['pseudo'])
			{
			$nbreconnec = $nbreconnec +1;
			}
		}
	}

$sql = 'SELECT * FROM pubs_tbl WHERE temps>"0" ORDER BY RAND() LIMIT 1' ;
$req = mysql_query($sql);
$res2 = mysql_num_rows($req);

if($res2>0)
	{
	$entpub = mysql_result($req,0,'entreprise');
	$messagepub = mysql_result($req,0,'message');
	
	if(mysql_result($req,0,'lien')!="")
		{
		$lienpub = mysql_result($req,0,'lien');
		$sql1 = 'SELECT rue,num FROM entreprises_tbl WHERE nom= "'.$entpub.'"' ;
		$req1 = mysql_query($sql1);
		$res1 = mysql_num_rows($req1);
		if($res1>0)
			{
			$ruepub = mysql_result($req1,0,'rue');
			$numpub = mysql_result($req1,0,'num');
			}
		else
			{
			$sql1 = 'SELECT rue,num FROM cerclesliste_tbl WHERE nom= "'.$entpub.'"' ;
			$req1 = mysql_query($sql1);
			$ruepub = mysql_result($req1,0,'rue');
			$numpub = mysql_result($req1,0,'num');
			}
		}
	else
		{
		$signpub = mysql_result($req,0,'signature');
		}
	}
else
	{
	$entpub = " ";
	$messagepub = " ";
	$signpub = " ";
	}

mysql_close($db);
?>
			</div>

			<div id="pub_int">
				<p><Marquee>
				<?php
				if($messagepub!="")
					{
					print(''.$messagepub.'');
					}
				?>
				</Marquee></p>
				<p id="clignote"><em>
				<?php 
				if($entpub!="")
					{
					print(''.$entpub.'');
					}
				?></em></p>
				<p id="lienpub">
				<?php 
				if($lienpub!="")
					{
					print('<a href="engine=go.php?num='.$numpub.'&rue='.$ruepub.'">'.$lienpub.'</a>');
					}
				else
					{
					print(''.$signpub.'');
					}
				?></p>
			</div>

			<div id="panneau_info">
				<?php 
				if(empty($_SESSION['avatar']))$_SESSION['avatar']="";
				if($_SESSION['avatar']=="interogation.jpg") 
					{ 
					print('<a href="engine=avatars.php">'); 
					} 

				if((ereg("http",$_SESSION['avatar'])) OR (ereg("ftp",$_SESSION['avatar'])))
					{
					if($_SESSION['malade']==1) print('<a href="engine=faucheur.php"><img src="avatars/faucheur.jpg" id="avatar" alt="Mon avatar" border ="0" /></a>');
					else print('<a href="engine=stats.php"><img src="'.$_SESSION['avatar'].'" id="avatar" alt="Mon avatar" border ="0" /></a>');
					}
				else
					{
					if($_SESSION['malade']==1) print('<a href="engine=faucheur.php"><img src="avatars/faucheur.php?'.$_SESSION['avatar'].'" id="avatar" alt="Mon avatar" border ="0" /></a>');
					else print('<a href="engine=stats.php"><img src="avatars/'.$_SESSION['avatar'].'" id="avatar" alt="Mon avatar" border ="0" /></a>');
					}
				 
				if ($_SESSION['avatar']=="interogation.jpg") 
					{ 
					print('</a>'); 
					} 
				?>
				<p id="argent"><img src="im_objets/argent.gif" alt="Mon argent" /><?php print($_SESSION['credits']); ?></p>
				<div id="faim"><img src="im_objets/faim.gif" alt="Faim" /><p style="background:url(im_objets/barrestats.gif) -<?php if(empty($_SESSION['faim']))$_SESSION['faim']="0";$faim=$_SESSION['faim'];if($faim%2==0)print((100-$faim)/2);else print(((100-$faim)-1)/2);?>px 0 repeat-y;;"><?php print($faim.'%'); ?></p></div>
				<div id="soif"><img src="im_objets/soif.gif" alt="Soif" /><p style="background:url(im_objets/barrestats.gif) -<?php if(empty($_SESSION['soif']))$_SESSION['soif']="100";$soif=$_SESSION['soif'];if($soif%2==0)print((100-$soif)/2);else print(((100-$soif)-1)/2);?>px 0 repeat-y;;"><?php print($soif.'%'); ?></p></div>
				<p id="texte"><?php if(($_SESSION['case1']=='AITL')||($_SESSION['case2']=='AITL')||($_SESSION['case3']=='AITL')||($_SESSION['case4']=='AITL')||($_SESSION['case5']=='AITL')||($_SESSION['case6']=='AITL')||(statut($_SESSION['statut'])>=2))print('+ <a href="engine=aitl.php">Consulter mon AITL</a><br />');?>
				+ <a href="http://v2.dreadcast.net/wikast/index.php" onclick="window.open(this.href); return false;">Acc&eacute;der au Wikast</a><br />
			</div>
