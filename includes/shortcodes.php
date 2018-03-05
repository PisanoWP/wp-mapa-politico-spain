<?php

	add_shortcode( 'wpmps-map', 'wpmps_show_map' );
	function wpmps_show_map( $atts ){

		$pagina_inicio = wpmps_getUrlContent(plugins_url( 'wp-mapa-politico-spain/images/mapa_base_00.svg'));
		if (!$pagina_inicio) {
			$mensaje = __('ERROR NO SE HA PODIDO LOCALIZAR MAPA A MOSTRAR' ,WPMPS_TEXTDOMAIN);
			return '<div class="error">'.$mensaje.'</div>';
		}


		$wpmps_mapas =  get_option( 'wpmps_plugin_mapas' );
		$mapa = $wpmps_mapas[0];

		foreach ($mapa['areas'] as $cod_area => $value){
			$pagina_inicio = str_replace('[href'.$cod_area.']', esc_url($value['href']), $pagina_inicio);
			$pagina_inicio = str_replace('[target'.$cod_area.']', $value['target'], $pagina_inicio);
			$pagina_inicio = str_replace('[title'.$cod_area.']', $value['title'], $pagina_inicio);

		}

		$wpmps_styles = '<style>
			.provincia {
			    fill : '.get_option('wpmps_background_provincia_color').';
			    fill-opacity:1;

			 }

		  .provincia path {
		    transition: .6s fill;
		    fill: '.get_option('wpmps_background_provincia_color').';

		    stroke:#ffffff;
		    stroke-width:0.47999001000000002;
		    stroke-linecap:square;
		    stroke-miterlimit:10;

		  }

		  .provincia path:hover {
		    fill: '.get_option('wpmps_hover_provincia_color').';

		  }

			.africa {
				fill:#f4e2ba;
				stroke:#999999;
				stroke-width:0.90709335;
				stroke-miterlimit:3.86369991;
			}
			</style>';


		$pagina_inicio = $wpmps_styles.
										'<div class="wpim-wrap-mapa wp-border-img-mapa" style="background-color:'.get_option('wpmps_background_color').'">'
											.$pagina_inicio
									. '</div>';
		return $pagina_inicio;

	}


function wpmps_getUrlContent($url){
	$ch = curl_init();
	curl_setopt($ch, CURLOPT_URL, $url);
	curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/4.0 (compatible; MSIE 6.0; Windows NT 5.1; .NET CLR 1.1.4322)');
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
	curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 5);
	curl_setopt($ch, CURLOPT_TIMEOUT, 5);
	$data = curl_exec($ch);
	$httpcode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
	curl_close($ch);
	return ($httpcode>=200 && $httpcode<300) ? $data : false;

}
