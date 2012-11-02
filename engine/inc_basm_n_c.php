<?php
$db = mysql_connect('localhost', 'CENSURE', 'CENSURE') or die("Erreur de connexion au serveur des messages.");
mysql_select_db('CENSURE',$db) or die("Erreur de connexion a la base de donnees des messages.");

$sql = 'SELECT id FROM principal_tbl WHERE connec= "oui"' ;
$req = mysql_query($sql);
$res = mysql_num_rows($req);
$nbreconnec = 0;
for($s=0 ; $s!=$res ; $s++)
	{
	$sql5 = 'SELECT pseudo FROM principal_tbl WHERE id= "'.mysql_result($req,$s,id).'" AND rue= "'.$_SESSION['lieu'].'" AND Num="'.$_SESSION['num'].'"';
	$req5 = mysql_query($sql5);
	$res5 =  mysql_num_rows($req5);
	for($f=0 ; $f!=$res5 ; $f++)
		{
		if(mysql_result($req5,0,pseudo)!=$_SESSION['pseudo'])
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
	$entpub = mysql_result($req,0,entreprise);
	$messagepub = mysql_result($req,0,message);
	
	if(mysql_result($req,0,lien)!="")
		{
		$lienpub = mysql_result($req,0,lien);
		$sql1 = 'SELECT rue,num FROM entreprises_tbl WHERE nom= "'.$entpub.'"' ;
		$req1 = mysql_query($sql1);
		$res1 = mysql_num_rows($req1);
		if($res1>0)
			{
			$ruepub = mysql_result($req1,0,rue);
			$numpub = mysql_result($req1,0,num);
			}
		else
			{
			$sql1 = 'SELECT rue,num FROM cerclesliste_tbl WHERE nom= "'.$entpub.'"' ;
			$req1 = mysql_query($sql1);
			$ruepub = mysql_result($req1,0,rue);
			$numpub = mysql_result($req1,0,num);
			}
		}
	else
		{
		$signpub = mysql_result($req,0,signature);
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
				</div>
				<div id="p1_3n"></div>
			</div>
			<div id="p2n">
				<div id="p2_1n"></div>
				<div id="p2_2n">
					<div id="p2_2_1n"></div>
					<div id="p2_2_2n">
						<div align="center"><Marquee>
<?php 

if($messagepub!="")
	{
	print(''.$messagepub.'');
	}

?>
        				</Marquee></div>
            			<div align="right">
              				<DIV ID="coucou" STYLE="visibility:visible;"><em><br />
<?php 

if($entpub!="")
	{
	print(''.$entpub.'');
	}

?>
							</em></DIV> 
<SCRIPT> 
function cacher_montrer() { 
if(document.getElementById('coucou').style.visibility = 'hidden') 
{ 
setTimeout("document.getElementById('coucou').style.visibility = 'visible';",700); 
} 
else 
{ 
setTimeout("document.getElementById('coucou').style.visibility = 'hidden';",700); 
} 
} 
setInterval("cacher_montrer();",1400) 
</SCRIPT>
<?php 

if($lienpub!="")
	{
	print('<a href="">'.$lienpub.'</a>');
	}
else
	{
	print(''.$signpub.'');
	}

?>
						</div>
					</div>
					<div id="p2_2_3n"></div>
				</div>
				<div id="p2_3n"></div>
				<div id="p2_4n">
					<div id="p2_4_1n"></div>
					<div id="p2_4_2salonn">
						<div align="center">
							<br />
							<a href="engine=chat.php">Acc&eacute;der au salon</a>
						</div>
						<div align="center">
						(<?php print(''.$nbreconnec.''); ?> Connectés)
						</div>
					</div> 
					<div id="p2_4_3n"></div>
				</div>
				<div id="p2_5n"></div>
			</div>
		</div>
