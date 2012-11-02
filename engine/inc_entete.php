<?php
$db = mysql_connect('localhost', 'CENSURE', 'CENSURE') or die("Erreur de connexion au serveur des messages.");
mysql_select_db('CENSURE',$db) or die("Erreur de connexion a la base de donnees des messages.");

$sql1 = 'SELECT id FROM messages_tbl WHERE cible= "'.$_SESSION['pseudo'].'" AND nouveau="oui"' ;
$req1 = mysql_query($sql1);
$res1 = mysql_num_rows($req1);

$sql1 = 'UPDATE principal_tbl SET connec= "oui" , dhc= "'.time().'" WHERE id= "'.$_SESSION['id'].'"' ;
$req1 = mysql_query($sql1);

if($_SESSION['lieu']!="Vacances")
	{
	if($_SESSION['num'] <= 0)
		{
		$chat = "oui";
		}
	else
		{
		$sql = 'SELECT chat FROM lieu_tbl WHERE rue= "'.$_SESSION['lieu'].'" AND num= "'.$_SESSION['num'].'"' ;
		$req = mysql_query($sql);
		$res3 = mysql_num_rows($req);
		if($res3>0) $chat = mysql_result($req,0,'chat');
		}
	}

if(($_SESSION['drogue']>0) && (time()-$_SESSION['drogue']>14400))
	{
	$sql1 = 'UPDATE principal_tbl SET sante= "1" , fatigue= "0" , drogue= "0" WHERE id= "'.$_SESSION['id'].'"' ;
	$req1 = mysql_query($sql1);
	$sql = 'INSERT INTO messages_tbl(id,auteur,cible,message,objet,moment) VALUES("","Dreadcast","'.$_SESSION['pseudo'].'","Vous venez de rechuter.<br />Votre taux de Kronatium appauvri dans le sang diminue et vous vous sentez de plus en plus faible.","Effet secondaire","'.time().'")' ;
	$req = mysql_query($sql);
	$_SESSION['drogue'] = 0;
	$_SESSION['sante'] = 1;
	$_SESSION['fatigue'] = 0;
	$res1 = $res1 + 1;
	}

$a_nouveau_message = $res1;

print('<div class="haut_aligndroite">
<p>
<strong>Lieu</strong>');
if($_SESSION['lieu']=="Vacances")
	{
	print('<span class="decale"><i>Centre de cryogénisation</i></span>'); 
	}
elseif($_SESSION['lieu']=="Rue" || $_SESSION['lieu']=="Ruelle")
	{
	print('<span class="decale"><span style="color:#777;position:absolute;left:-27px;">[S'.secteur($_SESSION['num'], $_SESSION['lieu']).']</span> <i>'.$_SESSION['lieu'].'</i></span>'); 
	//print('<span class="decale">[s'.secteur($_SESSION['num'], $_SESSION['lieu']).'] <i>'.$_SESSION['lieu'].'</i></span>'); 
	}
elseif($_SESSION['num'] < 0)
	{
    print('<span class="decale"><span style="color:#777;position:absolute;left:-27px;">[S'.secteur($_SESSION['num'], $_SESSION['lieu']).']</span> <i>'.$_SESSION['rue'].'</i></span>'); 
    //print('<span class="decale">[s'.secteur($_SESSION['num'], $_SESSION['lieu']).'] <i>'.$_SESSION['rue'].'</i></span>'); 
	}
	
	/*
elseif($_SESSION['lieu']=="Secteur")
	{
	print('<span class="decale">Secteur '.$_SESSION['num'].'</span>'); 
	}*/

else
	{
	if(strlen($_SESSION['lieu'])>22)
	   $lieu2 = substr($_SESSION['lieu'],0,18).'.';
    else
        $lieu2 = $_SESSION['lieu'];
    
    print('<span class="decale"><span style="color:#777;position:absolute;left:-27px;">[S'.secteur($_SESSION['num'], $_SESSION['lieu']).']</span> <i>'.$_SESSION['num'].' '.$lieu2.'</i></span>'); 
	//print('<span class="decale">[s'.secteur($_SESSION['num'], $_SESSION['lieu']).'] <i>'.$_SESSION['num'].' '.$lieu2.'</i></span>'); 
	}
print('<br />');
if($_SESSION['action']=="protection")
	{
	print('<strong>Action</strong> <i><span class="decale">Protégé par la Police</span></i><br/>');
	}
elseif($_SESSION['action']=="Vacances")
	{
	print('<strong>Action</strong> <i><span class="decale">En stase</span></i><br/>');
	}
else
	{
	print('<strong>Action</strong> <i><span class="decale">'.ucwords($_SESSION['action']).'</span></i><br/>');
	}

if($_SESSION['drogue']==0) $sante_max = $_SESSION['santemax'];
else $sante_max = drogue($_SESSION['pseudo'],$_SESSION['santemax']);

if($_SESSION['drogue']==0) $fatigue_max = $_SESSION['fatiguemax'];
else $fatigue_max = drogue($_SESSION['pseudo'],$_SESSION['fatiguemax']);

$pourcentage_sante = $_SESSION['sante']*100/$sante_max;
$deplacement1 = ($pourcentage_sante <= 100)?(150-$pourcentage_sante*1.5):0;
$deplacement2 = floor($pourcentage_sante/10)*12;
$deplacement2 = ($pourcentage_sante == 150)?14*12:$deplacement2;
$deplacement2 = ($_SESSION['drogue']!=0)?168:$deplacement2;
print('<strong>Santé</strong>
	<span class="decale" onmouseover="javascript:$(\'#info_sante\').fadeIn();" onmouseout="javascript:$(\'#info_sante\').fadeOut();" style="color:#333;margin-top:2px;width:148px;height:10px;border:1px solid #999;background:url(im_objets/jauge.gif) -'.$deplacement1.'px -'.$deplacement2.'px no-repeat;"></span>
	<span id="info_sante" onmouseover="javascript:$(\'#info_sante\').show();" style="display:none;" class="color6">'.$_SESSION['sante'].'/'.$sante_max.'</span>'); 

$pourcentage_fatigue = $_SESSION['fatigue']*100/$fatigue_max;
$deplacement1 = ($pourcentage_fatigue <= 100)?(150-$pourcentage_fatigue*1.5):0;
$deplacement2 = floor($pourcentage_fatigue/10)*12;
$deplacement2 = ($pourcentage_fatigue==150)?14*12:$deplacement2;
$deplacement2 = ($_SESSION['drogue']!=0)?168:$deplacement2;
print('<br />
	<strong>Forme</strong>
	<span class="decale" onmouseover="javascript:$(\'#info_fatigue\').fadeIn();" onmouseout="javascript:$(\'#info_fatigue\').fadeOut();" style="color:#333;margin-top:2px;width:148px;height:10px;border:1px solid #999;background:url(im_objets/jauge.gif) -'.$deplacement1.'px -'.$deplacement2.'px no-repeat;"></span>
	<span id="info_fatigue" onmouseover="javascript:$(\'#info_fatigue\').show();" style="display:none;" class="color6">'.$_SESSION['fatigue'].'/'.$fatigue_max.'</span>');

if(strlen($_SESSION['exp'])>1) $exp = $_SESSION['exp']-pow(10,strlen($_SESSION['exp'])-1);
else $exp = $_SESSION['exp'];
if(strlen($_SESSION['exp'])>1)  $exp_max = $_SESSION['expmax']-pow(10,strlen($_SESSION['exp'])-1);
else $exp_max = $_SESSION['expmax'];

$niveau = niveau($_SESSION['pseudo']);
$pourcentage_exp = $exp*100/$exp_max;
$deplacement1 = ($pourcentage_exp <= 100)?(150-$pourcentage_exp*1.5):0;
print('<br />
	<strong>Exp.</strong>
	<span class="decale" onmouseover="javascript:$(\'#info_exp\').fadeIn();" onmouseout="javascript:$(\'#info_exp\').fadeOut();" style="color:#333;margin-top:2px;width:148px;height:10px;border:1px solid #999;background:url(im_objets/jauge.gif) -'.(($niveau<10)?$deplacement1:'0').'px -180px no-repeat;"></span>
	<span id="info_exp" onmouseover="javascript:$(\'#info_exp\').show();" style="display:none;" class="color6">'.(($niveau<10)?$exp.'/'.$exp_max:'Maximum').'</span>
</p>
</div> ');

mysql_close($db);

?>



