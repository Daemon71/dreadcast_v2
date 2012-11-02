<?php

/*
 * Fonction qui enregistre en base de donnee des informations sur les clics des utilisateurs
 * Champs :
 *      - fichier_ajax : adresse du fichier php qui sera appele pour l'enregistrement
 *      - contenu_lien : enregistre ou non le contenu du lien
 *      - id_lien : enregistre ou non l'id du lien
 *      - class_lien : enregistre ou non la class du lien
 *      - x_clic : enregistre ou non la coordonnee x du clic
 *      - y_clic : enregistre ou non la coordonnee y du clic
 *      - server : adresse du serveur ou enregistrer les donnees
 *      - login : login du compte sql
 *      - mdp : mdp du compte sql
 *      - database : bdd ou enregistrer les donnees
 *      - table : table ou enregistrer les donnees
 */
function enregistre_clics($fichier_ajax = 'fonctions/ajax_trace.php', $contenu_lien = true, $id_lien = true, $class_lien = true, $x_clic = true, $y_clic = true) {
    
    $url = explode('?',$_SERVER["REQUEST_URI"]);
    
    print('
    <script type="text/javascript"> 
    <!--

    		$(document).ready(function(){
    			$(\'a\').click(function(e) {
    				$.get(
						"'.$fichier_ajax.'",
						{
							ip: "'.$_SERVER['REMOTE_ADDR'].'",
							page_actuelle: "'.$url[0].'",
							get_actuel: "'.$url[1].'",
							destination: $(this).attr(\'href\'),
							'.(($contenu_lien)?'contenu_lien: $(this).html(),':'').'
							'.(($id_lien)?'id_lien: $(this).attr(\'id\'),':'').'
							'.(($class_lien)?'class_lien: $(this).attr(\'class\'),':'').'
							'.(($x_clic)?'x_clic: e.pageX,':'').'
							'.(($y_clic)?'y_clic: e.pageY':'y_clic: ""').'
						}
					);
					
				});
            
	            $(\'form\').submit(function(e) {
    			    $.get(
        	            "'.$fichier_ajax.'",
            	        {
                	        ip: "'.$_SERVER['REMOTE_ADDR'].'",
                    	    page_actuelle: "'.$url[0].'",
                        	get_actuel: "'.$url[1].'",
	                        destination: $(this).attr(\'action\'),
    	                    '.(($id_lien)?'id_lien: $(this).attr(\'id\'),':'').'
        	                '.(($class_lien)?'class_lien: $(this).attr(\'class\'),':'').'
            	            '.(($x_clic)?'x_clic: e.pageX,':'').'
                	        '.(($y_clic)?'y_clic: e.pageY':'y_clic: ""').'
                    	}
	                );
    	        });
			});
			
			function saveTrace (e) {
				$.get(
					"'.$fichier_ajax.'",
					{
						ip: "'.$_SERVER['REMOTE_ADDR'].'",
						page_actuelle: "'.$url[0].'",
						get_actuel: "'.$url[1].'",
						destination: $(this).attr(\'href\'),
						'.(($contenu_lien)?'contenu_lien: $(this).html(),':'').'
						'.(($id_lien)?'id_lien: $(this).attr(\'id\'),':'').'
						'.(($class_lien)?'class_lien: $(this).attr(\'class\'),':'').'
						'.(($x_clic)?'x_clic: e.pageX,':'').'
						'.(($y_clic)?'y_clic: e.pageY':'y_clic: ""').'
					}
				);
			}

    //-->
    </script>
    ');
}

?>
