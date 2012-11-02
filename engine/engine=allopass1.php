<?php 
session_start(); 

$tracage = true;

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
?>
<?php if ((date("H")>=21) || (date("H")<=7)) { include("inc_haut_n.php"); } else { include("inc_haut_j.php");} ?>

<div id="haut">
	<?php include("inc_entete.php"); ?>
	<div class="haut_aligngauche">
		<p>
		<div class="titrepage">
			Allopass
		</div>
		<b class="module4ie"><a href="engine=allopass.php" class="module4"><img src="im_objets/icon_retour.gif" alt="Retour" />Retour</a></b>
</p>
	</div>
</div>
<div id="centreallopass2">

	<div id="texte">
		<p>Pour devenir propri&eacute;taire des <strong>1300 cr&eacute;dits</strong> de cette malette, cliquez sur votre pays.<br />
		Cette op&eacute;ration vous co&ucirc;tera <strong>2€</strong> par CB ou bien 1€34 l'appel + 0€34 la minute, soit moins de <strong>1€80</strong>.</p>
	</div>
	<img src="http://www.allopass.com/imgweb/script/fr_uk/acces_title.jpg" width="300" height="25" align="center" alt="Logo" id="logo">
	<div id="drapeaux">
		<a id="dr1" href="javascript:;" onClick="javascript:window.open('CENSURE','phone','toolbar=0,location=0,directories=0,status=0,scrollbars=0,resizable=0,copyhistory=0,menuBar=0,width=300,height=320');"><img border="0" src="http://www.allopass.com/imgweb/common/flag_fr.gif" width="35" alt="" /></a>
		<a id="dr2" href="javascript:;" onClick="javascript:window.open('CENSURE','phone','toolbar=0,location=0,directories=0,status=0,scrollbars=0,resizable=0,copyhistory=0,menuBar=0,width=300,height=320');"><img border="0" src="http://www.allopass.com/imgweb/common/flag_it.gif" width="35" alt="" /></a>
		<a id="dr3" href="javascript:;" onClick="javascript:window.open('CENSURE','phone','toolbar=0,location=0,directories=0,status=0,scrollbars=0,resizable=0,copyhistory=0,menuBar=0,width=300,height=320');"><img border="0" src="http://www.allopass.com/imgweb/common/flag_es.gif" width="35" alt="" /></a>
		<a id="dr4" href="javascript:;" onClick="javascript:window.open('CENSURE','phone','toolbar=0,location=0,directories=0,status=0,scrollbars=0,resizable=0,copyhistory=0,menuBar=0,width=300,height=320');"><img border="0" src="http://www.allopass.com/imgweb/common/flag_uk.gif" width="35" alt="" /></a>
		<a id="dr5" href="javascript:;" onClick="javascript:window.open('CENSURE','phone','toolbar=0,location=0,directories=0,status=0,scrollbars=0,resizable=0,copyhistory=0,menuBar=0,width=300,height=320');"><img border="0" src="http://www.allopass.com/imgweb/common/flag_be.gif" width="35" alt="" /></a>
		<a id="dr6" href="javascript:;" onClick="javascript:window.open('CENSURE','phone','toolbar=0,location=0,directories=0,status=0,scrollbars=0,resizable=0,copyhistory=0,menuBar=0,width=300,height=320');"><img border="0" src="http://www.allopass.com/imgweb/common/flag_ch.gif" width="35" alt="" /></a>
		<a id="dr7" href="javascript:;" onClick="javascript:window.open('CENSURE','phone','toolbar=0,location=0,directories=0,status=0,scrollbars=0,resizable=0,copyhistory=0,menuBar=0,width=300,height=320');"><img border="0" src="http://www.allopass.com/imgweb/common/flag_lu.gif" width="35" alt="" /></a>
		<a id="dr8" href="javascript:;" onClick="javascript:window.open('CENSURE','phone','toolbar=0,location=0,directories=0,status=0,scrollbars=0,resizable=0,copyhistory=0,menuBar=0,width=300,height=320');"><img border="0" src="http://www.allopass.com/imgweb/common/flag_ca.gif" width="35" alt="" /></a>
		<a id="dr9" href="javascript:;" onClick="javascript:window.open('CENSURE','phone','toolbar=0,location=0,directories=0,status=0,scrollbars=0,resizable=0,copyhistory=0,menuBar=0,width=300,height=320');"><img border="0" src="http://www.allopass.com/imgweb/common/flag_ie.gif" width="35" alt="" /></a>
		<a id="dr10" href="javascript:;" onClick="javascript:window.open('CENSURE','phone','toolbar=0,location=0,directories=0,status=0,scrollbars=0,resizable=0,copyhistory=0,menuBar=0,width=300,height=320');"><img border="0" src="http://www.allopass.com/imgweb/common/flag_de.gif" width="35" alt="" /></a>
		<a id="dr11" href="javascript:;" onClick="javascript:window.open('CENSURE','phone','toolbar=0,location=0,directories=0,status=0,scrollbars=0,resizable=0,copyhistory=0,menuBar=0,width=300,height=320');"><img border="0" src="http://www.allopass.com/imgweb/common/flag_at.gif" width="35" alt="" /></a>
		<a id="dr12" href="javascript:;" onClick="javascript:window.open('CENSURE','phone','toolbar=0,location=0,directories=0,status=0,scrollbars=0,resizable=0,copyhistory=0,menuBar=0,width=300,height=320');"><img border="0" src="http://www.allopass.com/imgweb/common/flag_nl.gif" width="35" alt="" /></a>
		<a id="dr14" href="javascript:;" onClick="javascript:window.open('CENSURE','phone','toolbar=0,location=0,directories=0,status=0,scrollbars=0,resizable=0,copyhistory=0,menuBar=0,width=300,height=320');"><img border="0" src="http://www.allopass.com/imgweb/common/flag_no.gif" width="35" alt="" /></a>
	</div>
	<div id="CB">
		<a href="#null" onclick="window.open('CENSURE','ccard','toolbar=0,location=0,directories=0,status=1,scrollbars=1,resizable=1,copyhistory=0,menuBar=0,width=550,height=575');">
			<img src="http://www.allopass.com/imgweb/script/fr_uk/achat_cc.jpg" border="0" alt="If your country is not listed, click here">
		</a>
	</div>
	<form name="APform" action="CENSURE" method="post" id="alloform">
	    <input type="hidden" name="SITE_ID" value="110771">
	    <input type="hidden" name="DOC_ID" value="293667">
	    <input type="hidden" name="LG_SCRIPT" value="fr_uk">
	    <input type="hidden" name="RECALL" value="1">
	    <input type="hidden" name="DATAS" value="<?php print($_SESSION['id']); ?>">
	    <p><font color="#707070">Propri&eacute;taire</font> <div id="fauxchamp"><?php print $_SESSION['pseudo']; ?></div></p>
		<p><font color="#707070">Code d'acc&egrave;s</font> <input type="text" size="8" maxlength="10" value="" name="CODE0" id="acces">
		<input type="button" name="APsub" value="Ok" onclick=" this.form.submit(); this.form.APsub.disabled=true;" id="ok"></p>
	</form>
</div>
<?php if($chat=="oui") 	{ if ((date("H")>=21) || (date("H")<=7)) { include("inc_bas_n_c.php"); } else { include("inc_bas_j_c.php"); } } else {	if ((date("H")>=21) || (date("H")<=7)) { include("inc_bas_n.php"); } else { include("inc_bas_j.php"); }	} ?>
<?php include("inc_bas_de_page.php"); ?>
