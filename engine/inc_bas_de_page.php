		<div class="basdepage">
	<?php 

	if(statut($_SESSION['statut'])==7) print('<div id="glink">
			<a href="engine=panneau.php" onmouseover="ehandler(event,menuitem1);">Panneau d\'administration</a> ');
	else print('<div id="glink">
			<a href="engine=rbug.php" onmouseover="ehandler(event,menuitem1);">Aide en ligne</a> ');
			
		print('- <a href="http://v2.dreadcast.net/deconnexion.php">D&eacute;connexion</a><br />
		<a href="../wikast/" onclick="window.open(this.href); return false;">Accéder au Wikast</a>
		</div>
	</div>');
	
	
	
	$temps_fin = microtime(true);
	print('<!-- Temps d\'execution de la page : '.round($temps_fin - $temps_debut, 4).'s -->');
	
	?>
	
	<!--<script type="text/javascript">
		var gaJsHost = (("https:" == document.location.protocol) ? "https://ssl." : "http://www.");
		document.write(unescape("%3Cscript src='" + gaJsHost + "google-analytics.com/ga.js' type='text/javascript'%3E%3C/script%3E"));
	</script>
	<script type="text/javascript">
		try {
			var pageTracker = _gat._getTracker("UA-1893740-8");
			pageTracker._trackPageview();
		} catch(err) {}
	</script>-->
	
		<script type="text/javascript">
			var gaJsHost = (("https:" == document.location.protocol) ? "https://ssl." : "http://www.");
			document.write("\<script src='" + gaJsHost + "google-analytics.com/ga.js' type='text/javascript'>\<\/script>" );
		</script>
		<script type="text/javascript">
			var pageTracker = _gat._getTracker("UA-1893740-1");
			pageTracker._initData();
			pageTracker._trackPageview();
		</script>
	
	
	</body>

</html>
