					<?php
					$db = mysql_connect('localhost', 'CENSURE', 'CENSURE') or die("Erreur de connexion au serveur des messages.");
					mysql_select_db('CENSURE',$db) or die("Erreur de connexion a la base de donnees des messages.");

					$sql1 = 'UPDATE principal_tbl SET connec= "oui" , dhc= "'.time().'" WHERE id= "'.$_SESSION['id'].'"' ;
					mysql_query($sql1);
					
					$sql = 'SELECT num,rue,sante,sante_max FROM principal_tbl WHERE id= "'.$_SESSION['id'].'"' ;
					$req = mysql_query($sql);
					
					mysql_close($db);
					
					$action = ucfirst($_SESSION['action']);
					$num = mysql_result($req,0,num);
					$rue = ucfirst(mysql_result($req,0,rue));
					$etat = mysql_result($req,0,sante)*100/mysql_result($req,0,sante_max);
					
					if($etat>100) $etat="Hyperactif";
					elseif($etat > 70) $etat="En forme";
					elseif($etat > 40) $etat="Bless&eacute;";
					elseif($etat > 0) $etat="Chancelant";
					elseif($etat==0) $etat="Inconscient";
					else $etat="Agonisant";
					
					if($rue=="Rue" OR $rue=="Ruelle") $lieu = $rue;
					elseif($num<0) $lieu = $rue;
					else $lieu = $num.' '.$rue;
					
					print('
						<div class="fiche">
							<p class="titre">Situation actuelle</p>
							<p class="style1">Action : <span class="style2">'.$action.'</span><br />
							Lieu : <span class="style2">'.$lieu.'</span><br />
							Sant&eacute; : <span class="style2">'.$etat.'</span>
							</p>
						</div>');
					?>
					
	<script type="text/javascript">
var gaJsHost = (("https:" == document.location.protocol) ? "https://ssl." : "http://www.");
document.write("\<script src='" + gaJsHost + "google-analytics.com/ga.js' type='text/javascript'>\<\/script>" );
</script>
<script type="text/javascript">
var pageTracker = _gat._getTracker("UA-1893740-1");
pageTracker._initData();
pageTracker._trackPageview();
</script>
