				<div class="barre1">
					<?php
					if($_SESSION['id']!="")
						{
						print('<a href="http://v2.dreadcast.net/chat" onclick="window.open(this.href); return false;">IRC de Dreadcast</a> - <a href="forum=charte.php">Charte des forums et EDC</a> - <a href="http://v2.dreadcast.net/ingame/engine=contact.php">Contacter un administrateur</a> - <a href="deconnexion.php">D&eacute;connexion</a>');
						}
					else
						{
						print('<a href="http://v2.dreadcast.net/chat" onclick="window.open(this.href); return false;">IRC de Dreadcast</a> - <a href="forum=charte.php">Charte des forums et EDC</a>');
						}
					?>
				</div>
