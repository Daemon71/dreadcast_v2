				<div class="barre1">
					<?php
					if($_SESSION['id']!="")
						{
						print('<a href="edc=aide.php">Aide</a> - <a href="forum=charte.php">Charte des forums et EDC</a> - <a href="http://v2.dreadcast.net/ingame/engine=contact.php">Contacter un administrateur</a> - <a href="http://v2.dreadcast.net/deconnexion.php">D&eacute;connexion</a>');
						}
					else
						{
						print('<a href="edc=aide.php">Aide</a> - <a href="forum=charte.php">Charte des forums et EDC</a>');
						}
					?>
				</div>
