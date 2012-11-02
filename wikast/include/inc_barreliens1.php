				<?php
				/*
				if(statut($_SESSION['statut'])<2){
				if($_SESSION['pseudo']=="Test")
				print('<div id="pub_ads" style="position:absolute;top:115px;right:30px;width:130px;height:610px;background:url(design/panneau_pub3.gif) 0 0 no-repeat;">'.$image_pub.'
					<a href="http://www.mspixel.fr/ads.php" target="_blank" class="a1"></a>
					<a href="engine=redirectpub.php?id='.$id_pub.'" target="_blank" style="background:url('.$image_pub.') 0 0 no-repeat;" class="a3"></a>
				</div>');
				else
				print('<div id="pub_ads">
					<a href="http://www.mspixel.fr/ads.php" target="_blank" class="a1"></a>
					<a href="engine=redirectpub.php?id='.$id_pub.'" target="_blank" style="background:url('.$image_pub.') 0 0 no-repeat;" class="a2"></a>
				</div>');
				}
				*/
				?>

				<div id="barreliens">
					<p class="lien1"><a href="index.php">Retour &agrave; l'index</a></p>
					<p class="lien2">
					<?php if(empty($_SESSION['id'])) print('<a>Voir mon EDC</a></p>'); else print('<a href="edc.php">Voir mon EDC</a></p>'); ?>
					<p class="lien3"><a href="sondages=accueil.php">Voir les sondages</a></p>
					<p class="lien4"><a href="http://www.dreadcast.net/Main" onclick="window.open(this.href); return false;">Vers DreadCast</a></p>
				</div>
