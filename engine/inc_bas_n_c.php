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
			$reshop = mysql_num_rows($req1);
			if($reshop!=0)
				{
				$ruepub = mysql_result($req1,0,'rue');
				$numpub = mysql_result($req1,0,'num');
				}
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
			
			<div id="panneau_pub"<?php if($panneau_pub == "off") print(' style="display:none;"'); ?>>
				<a href="javascript:affiche_art('panneau_pub',false);affiche_art('panneau_pub_off',true);EcrireCookie('panneau_pub','off');" class="onglet_fermant"></a>
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
			</div>
			<div id="panneau_pub_off"<?php if($panneau_pub == "on") print(' style="display:none;"'); ?>>
				<a href="javascript:affiche_art('panneau_pub',true);affiche_art('panneau_pub_off',false);EcrireCookie('panneau_pub','on');" class="onglet_ouvrant"></a>
			</div>

			<div id="panneau_info"<?php if($panneau_info == "off") print(' style="display:none;"'); ?>>
				<a href="javascript:affiche_art('panneau_info',false);affiche_art('panneau_info_off',true);EcrireCookie('panneau_info','off');" class="onglet_fermant"></a>
				<?php 
				if(empty($_SESSION['avatar']))$_SESSION['avatar']="";
				if($_SESSION['avatar']=="interogation.jpg") 
					{ 
					print('<a href="engine=avatars.php">'); 
					} 

				$event = event() && estDroide() ? $_SESSION['event'] : 0;
				$virus = $event == 1 || adm() ? true : false;
				
				if((ereg("http",$_SESSION['avatar'])) OR (ereg("ftp",$_SESSION['avatar'])))
					{
					if($_SESSION['malade']==1) print('<a href="engine=faucheur.php"><img src="avatars/faucheur.jpg" id="avatar" alt="Mon avatar" border ="0" /></a>');
					elseif($virus) print('<img src="avatars/virusD.jpg" id="avatar" alt="Mon avatar" border ="0" />');
					else print('<a href="engine=stats.php"><img src="'.$_SESSION['avatar'].'" id="avatar" alt="Mon avatar" border ="0" /></a>');
					}
				else
					{
					if($_SESSION['malade']==1) print('<a href="engine=faucheur.php"><img src="avatars/faucheur.php?'.$_SESSION['avatar'].'" id="avatar" alt="Mon avatar" border ="0" /></a>');
					elseif($virus) print('<img src="avatars/faucheur.php?img=virusD.jpg&url='.$_SESSION['avatar'].'" id="avatar" alt="Mon avatar" border ="0" />');
					else print('<a href="engine=stats.php"><img src="avatars/'.$_SESSION['avatar'].'" id="avatar" alt="Mon avatar" border ="0" /></a>');
					}
				 
				if ($_SESSION['avatar']=="interogation.jpg") 
					{ 
					print('</a>'); 
					} 
				$db = mysql_connect('localhost', 'CENSURE', 'CENSURE') or die("Erreur de connexion au serveur des messages.");
				mysql_select_db('CENSURE',$db) or die("Erreur de connexion a la base de donnees des messages.");
				$mon_niveau = niveau($_SESSION['pseudo']);
				mysql_close($db);
				?>
				<p id="pseudo" title="<?php print($_SESSION['race']); ?> niveau <?php print($mon_niveau); ?>"><img src="im_objets/info_race.php<?php print('?race='.$_SESSION['race'].'&niv='.$mon_niveau); ?>" alt="<?php print($_SESSION['race']); ?> niveau <?php print(strlen($_SESSION['exp'])); ?>" /><?php print($_SESSION['pseudo']); ?></p>
				<div id="argent2" title="Mon argent"><div id="info_argent"></div><p><?php print($_SESSION['credits']); ?></p></div>
				<div id="faim2" title="Faim"><div id="info_faim"></div><p style="background:url(im_objets/barrestats.gif) -<?php if(empty($_SESSION['faim']))$_SESSION['faim']=0;$faim=$_SESSION['faim'];if($faim%2==0)print((100-$faim)/2);else print(((100-$faim)-1)/2);?>px 0 repeat-y;line-height:9px;"><?php print($faim.'%'); ?></p></div>
				<div id="soif2" title="Soif"><div id="info_soif"></div><p style="background:url(im_objets/barrestats.gif) -<?php if(empty($_SESSION['soif']))$_SESSION['soif']=0;$soif=$_SESSION['soif'];if($soif%2==0)print((100-$soif)/2);else print(((100-$soif)-1)/2);?>px 0 repeat-y;line-height:9px;"><?php print($soif.'%'); ?></p></div>
			</div>
			<div id="panneau_info_off"<?php if($panneau_info == "on") print(' style="display:none;"'); ?>>
				<a href="javascript:affiche_art('panneau_info',true);affiche_art('panneau_info_off',false);EcrireCookie('panneau_info','on');" class="onglet_ouvrant"></a>
			</div>
			
			<div id="panneau_chat"<?php if($panneau_chat == "off") print(' style="display:none;"'); ?>>
				<a href="javascript:affiche_art('panneau_chat',false);affiche_art('panneau_chat_off',true);EcrireCookie('panneau_chat','off');" class="onglet_fermant"></a>
				<div id="zone_chat">
				<p>
				<?php
					$db = mysql_connect('localhost', 'CENSURE', 'CENSURE') or die("Erreur de connexion au serveur des messages.");
					mysql_select_db('CENSURE',$db) or die("Erreur de connexion a la base de donnees des messages.");
					
					$sql = 'SELECT num, rue FROM principal_tbl WHERE id='.$_SESSION['id'];
					$req = mysql_query($sql);
					
					if(mysql_num_rows($req)){
						$num = mysql_result($req,0,num);
						$rue = mysql_result($req,0,rue);
						if($num <= 0) {
							$rueok = 'rue';
							$secteur = secteur($num,$rue);
						}
					}
					
					// APERCU MESSAGES LIEU ACTUEL OU RUE GLOBAL SI ON EST DANS LA RUE
					$sql = 'SELECT posteur,message FROM chat WHERE num="'.(($rueok != "")?'0':$num).'" AND rue="'.(($rueok != "")?'Rue':$rue).'" ORDER BY ids DESC' ;
					$req = mysql_query($sql);
					$res = mysql_num_rows($req);
					$res = ($res<11)?$res:11;
					
					$messagelieu = "";
					
					for($i=$res-1;$i>=0;$i--)
						{
						if(!ereg("/me ",mysql_result($req,$i,message))) $messagelieu .= '<strong>'.mysql_result($req,$i,posteur).':</strong> '.mysql_result($req,$i,message).'<br />';
						else $messagelieu .= '<em>'.mysql_result($req,$i,posteur).' '.str_replace("/me ","",mysql_result($req,$i,message)).'</em><br />';
						}
					
					// APERCU MESSAGES SECTEUR
					if($secteur > 0)
						{
						$sql = 'SELECT posteur,message FROM chat WHERE num="0" AND rue="Secteur '.$secteur.'" ORDER BY ids DESC' ;
						$req = mysql_query($sql);
						$res = mysql_num_rows($req);
						$res = ($res<11)?$res:11;
						
						$messagesecteur = "";
						
						for($i=$res-1;$i>=0;$i--)
							{
							if(!ereg("/me ",mysql_result($req,$i,message))) $messagesecteur .= '<strong>'.mysql_result($req,$i,posteur).':</strong> '.mysql_result($req,$i,message).'<br />';
							else $messagesecteur .= '<em>'.mysql_result($req,$i,posteur).' '.str_replace("/me ","",mysql_result($req,$i,message)).'</em><br />';
							}
						}
					
					print($messagelieu);
					
					
					if($_SESSION['num'] <= 0) {$condition = 'num<=0';}
					else $condition = 'num="'.$_SESSION['num'].'" AND rue="'.$_SESSION['rue'].'"';
					$sql = 'SELECT id FROM principal_tbl WHERE statut != "Debutant" AND connec="oui" AND action != "mort" AND '.$condition;
					$req = mysql_query($sql);
					$nb_co = mysql_num_rows($req);
						
					mysql_close($db);
					
					print('</p>
				</div>
				<div id="nb_co">'.$nb_co.'</div><a href="engine=chat'.$rueok.'.php" class="lien"></a>');
				?>
			</div>
			<div id="panneau_chat_off"<?php if($panneau_chat == "on") print(' style="display:none;"'); ?>>
				<a href="javascript:affiche_art('panneau_chat',true);affiche_art('panneau_chat_off',false);EcrireCookie('panneau_chat','on');" class="onglet_ouvrant"></a>
			</div>
			
			<script type="text/javascript" language="JavaScript">
			<!--
				function EcrireCookie(nom, valeur){
					var argv=EcrireCookie.arguments;var argc=EcrireCookie.arguments.length;var expires=(argc > 2) ? argv[2] : null;var path=(argc > 3) ? argv[3] : null;var domain=(argc > 4) ? argv[4] : null;var secure=(argc > 5) ? argv[5] : false;document.cookie=nom+"="+escape(valeur)+((expires==null) ? "" : ("; expires="+expires.toGMTString()))+((path==null) ? "" : ("; path="+path))+((domain==null) ? "" : ("; domain="+domain))+((secure==true) ? "; secure" : "");
				}
			//-->
			</script> 
