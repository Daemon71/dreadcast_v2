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
?>
<?php if ((date("H")>=21) || (date("H")<=7)) { include("inc_haut_n.php"); } else { include("inc_haut_j.php");} ?>

<div id="haut">
	<?php include("inc_entete.php"); ?>
	<div class="haut_aligngauche">
		<p>
		<div class="titrepage">
			Créer un poste
		</div>
		<b class="module4ie"><a href="engine=personnel.php" class="module4"><img src="im_objets/icon_retour.gif" alt="Retour" />Retour</a></b>
</p>
	</div>
</div>
<div id="centre">
<p>

<em><strong>Nouveau poste : </strong></em>
        <form name="form2" method="post" action="engine=cpf.php">
          <p align="center">Type de poste :
            <select name="menu1" onChange="MM_jumpMenu('parent',this,0)">
              <option value="#" selected>Selectionnez ici</option>       
<?php

$db = mysql_connect('localhost', 'CENSURE', 'CENSURE') or die("Erreur de connexion au serveur des messages.");
mysql_select_db('CENSURE',$db) or die("Erreur de connexion a la base de donnees des messages.");

$sql = 'SELECT type,entreprise,points FROM principal_tbl WHERE id= "'.$_SESSION['id'].'"' ;
$req = mysql_query($sql);
$_SESSION['poste'] = mysql_result($req,0,type); 
$_SESSION['entreprise'] = mysql_result($req,0,entreprise); 
$_SESSION['points'] = mysql_result($req,0,points); 

$sql = 'SELECT type FROM entreprises_tbl WHERE nom= "'.$_SESSION['entreprise'].'"' ;
$req = mysql_query($sql);
$_SESSION['domaine'] =  mysql_result($req,0,type); 

mysql_close($db);

if($_SESSION['points']!=999) { print('<meta http-equiv="refresh" content="0 ; url=http://v2.dreadcast.net/ingame/engine.php"> '); exit(); }

if($_SESSION['domaine']=="aucun")
{
print('<option value="engine=creerpostepassif.php?maintenance">Agent de maintenance (Maintenance)</option>');
print('<option value="engine=creerpostepassif.php?securite">Agent de s&eacute;curit&eacute; (Observation)</option>');
print('<option value="engine=creerposteactif.php?directeur">Directeur (Gestion)</option>');
print('<option value="engine=creerposteactif.php?autre">Autre... (aucune comp&eacute;tence requise)</option>');
}
elseif($_SESSION['domaine']=="agence immobiliaire")
{
print('<option value="engine=creerpostepassif.php?maintenance">Agent de maintenance (Maintenance)</option>');
print('<option value="engine=creerpostepassif.php?securite">Agent de s&eacute;curit&eacute; (Observation)</option>');
print('<option value="engine=creerposteactif.php?directeur">Directeur (Gestion)</option>');
print('<option value="engine=creerpostepassif.php?vendeur">Vendeur (Economie)</option>');
print('<option value="engine=creerposteactif.php?autre">Autre... (aucune comp&eacute;tence requise)</option>');
}
elseif($_SESSION['domaine']=="banque")
{
print('<option value="engine=creerpostepassif.php?maintenance">Agent de maintenance (Maintenance)</option>');
print('<option value="engine=creerpostepassif.php?securite">Agent de s&eacute;curit&eacute; (Observation)</option>');
print('<option value="engine=creerpostepassif.php?banquier">Banquier (Economie)</option>');
print('<option value="engine=creerposteactif.php?directeur">Directeur (Gestion)</option>');
print('<option value="engine=creerposteactif.php?autre">Autre... (aucune comp&eacute;tence requise)</option>');
}
elseif($_SESSION['domaine']=="bar cafe")
{
print('<option value="engine=creerpostepassif.php?maintenance">Agent de maintenance (Maintenance)</option>');
print('<option value="engine=creerpostepassif.php?securite">Agent de s&eacute;curit&eacute; (Observation)</option>');
print('<option value="engine=creerposteactif.php?directeur">Directeur (Gestion)</option>');
print('<option value="engine=creerpostepassif.php?serveur">Serveur (Service)</option>');
print('<option value="engine=creerposteactif.php?autre">Autre... (aucune comp&eacute;tence requise)</option>');
}
elseif(($_SESSION['domaine']=="boutique armes") || ($_SESSION['domaine']=="boutique spécialisee"))
{
print('<option value="engine=creerpostepassif.php?maintenance">Agent de maintenance (Maintenance)</option>');
print('<option value="engine=creerpostepassif.php?securite">Agent de s&eacute;curit&eacute; (Observation)</option>');
print('<option value="engine=creerposteactif.php?directeur">Directeur (Gestion)</option>');
print('<option value="engine=creerpostepassif.php?vendeur">Vendeur (Economie)</option>');
print('<option value="engine=creerposteactif.php?autre">Autre... (aucune comp&eacute;tence requise)</option>');
}
elseif($_SESSION['domaine']=="ventes aux encheres")
{
print('<option value="engine=creerpostepassif.php?maintenance">Agent de maintenance (Maintenance)</option>');
print('<option value="engine=creerpostepassif.php?securite">Agent de s&eacute;curit&eacute; (Observation)</option>');
print('<option value="engine=creerposteactif.php?directeur">Directeur (Gestion)</option>');
print('<option value="engine=creerpostepassif.php?vendeur">Vendeur (Economie)</option>');
print('<option value="engine=creerposteactif.php?autre">Autre... (aucune comp&eacute;tence requise)</option>');
}
elseif($_SESSION['domaine']=="centre de recherche")
{
print('<option value="engine=creerpostepassif.php?maintenance">Agent de maintenance (Maintenance)</option>');
print('<option value="engine=creerpostepassif.php?securite">Agent de s&eacute;curit&eacute; (Observation)</option>');
print('<option value="engine=creerposteactif.php?directeur">Directeur (Gestion)</option>');
print('<option value="engine=creerpostepassif.php?technicien">T&eacute;chnicien de production (M&eacute;canique)</option>');
print('<option value="engine=creerpostepassif.php?vendeur">Vendeur (Economie)</option>');
print('<option value="engine=creerposteactif.php?autre">Autre... (aucune comp&eacute;tence requise)</option>');
}
elseif($_SESSION['domaine']=="hopital")
{
print('<option value="engine=creerpostepassif.php?maintenance">Agent de maintenance (Maintenance)</option>');
print('<option value="engine=creerpostepassif.php?securite">Agent de s&eacute;curit&eacute; (Observation)</option>');
print('<option value="engine=creerposteactif.php?directeur">Directeur (Gestion)</option>');
print('<option value="engine=creerpostepassif.php?medecin">M&eacute;decin (M&eacute;decine)</option>');
print('<option value="engine=creerposteactif.php?autre">Autre... (aucune comp&eacute;tence requise)</option>');
}
elseif($_SESSION['domaine']=="usine de production")
{
print('<option value="engine=creerpostepassif.php?maintenance">Agent de maintenance (Maintenance)</option>');
print('<option value="engine=creerpostepassif.php?securite">Agent de s&eacute;curit&eacute; (Observation)</option>');
print('<option value="engine=creerposteactif.php?directeur">Directeur (Gestion)</option>');
print('<option value="engine=creerpostepassif.php?technicien">T&eacute;chnicien de production (M&eacute;canique)</option>');
print('<option value="engine=creerpostepassif.php?vendeur">Vendeur (Economie)</option>');
print('<option value="engine=creerposteactif.php?autre">Autre... (aucune comp&eacute;tence requise)</option>');
}
else
{
print('<option value="engine=creerpostepassif.php?maintenance">Agent de maintenance (Maintenance)</option>');
print('<option value="engine=creerpostepassif.php?securite">Agent de s&eacute;curit&eacute; (Observation)</option>');
print('<option value="engine=creerposteactif.php?directeur">Directeur (Gestion)</option>');
print('<option value="engine=creerposteactif.php?autre">Autre... (aucune comp&eacute;tence requise)</option>');
}

?>
  </select>     
	          </p>
          </form>		
		<p align="center"> - <a href="../info=exempletp.php" target="_blank">Plus d'informations sur les postes</a> -

</p>
</div>
<?php if($chat=="oui") 	{ if ((date("H")>=21) || (date("H")<=7)) { include("inc_bas_n_c.php"); } else { include("inc_bas_j_c.php"); } } else {	if ((date("H")>=21) || (date("H")<=7)) { include("inc_bas_n.php"); } else { include("inc_bas_j.php"); }	} ?>
<?php include("inc_bas_de_page.php"); ?>
