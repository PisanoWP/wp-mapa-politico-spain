<?php

	add_shortcode( 'wpmps-map', 'wpmps_show_map' );
	function wpmps_show_map( $atts ){

		$wpmps_imagen = apply_filters('wpmps_imagen_mapa_provincias', 'mapa_base_00V2.svg');

		$metodo =  get_option( 'wpmps_metodo_recuperar_svg' );

		switch ($metodo) {
			case 'curl':
				$svg_url = plugins_url( 'wp-mapa-politico-spain/images/'.$wpmps_imagen);
				$resultado = wpmps_getUrlContent($svg_url);
				break;

			case 'file':
				$svg_url = dirname(plugin_dir_path(__FILE__)) . '/images/' . $wpmps_imagen;
				$resultado = wpmps_getFileContent($svg_url);
				break;

			default:
				// Por defecgto usamos siempre el metodo CURL
				$svg_url = plugins_url( 'wp-mapa-politico-spain/images/' . $imagen );
				$resultado = wpmps_getUrlContent($svg_url);
				break;
		}



		$pagina_inicio = $resultado['imagen'];
		if (!$pagina_inicio) {
			$mensaje = __('ERROR NO SE HA PODIDO LOCALIZAR MAPA A MOSTRAR' ,WPMPS_TEXTDOMAIN);
			return '<div class="error" data-url="'.$svg_url.'" data-httpcode="'.$resultado['httpcode'].'">'.$mensaje.'</div>';
		}

		$wpmps_mapas =  get_option( 'wpmps_plugin_mapas' );
		$mapa = $wpmps_mapas[0];

		foreach ($mapa['areas'] as $cod_area => $value){
			$pagina_inicio = apply_filters('wpmps_establecer_links_provincias', $pagina_inicio, $cod_area, $value);

		}

		$wpmps_styles = '<style>
			.wpmps-background-mar{
				fill : '.get_option('wpmps_background_color').';
				fill-opacity:1;
			}
			.provincia {
			    fill : '.get_option('wpmps_background_provincia_color').';
			    fill-opacity:1;
			 }

		  .provincia path, .provincia ellipse {
		    transition: .6s fill;
		    fill: '.get_option('wpmps_background_provincia_color').';
		    stroke:#ffffff;
		    stroke-width:0.47999001000000002;
		    stroke-linecap:square;
		    stroke-miterlimit:10;
		  }

			.provincia .has-link path, .provincia .has-link ellipse {
				fill: '.get_option('wpmps_has_link_provincia_color').';
			}

		  .provincia path:hover, .provincia ellipse:hover {
		    fill: '.get_option('wpmps_hover_provincia_color').';
			} 

			#wpmps-frame-islas-canarias{
				stroke:#646464;
				stroke-width:4;
			}';
						

		if ('S' == get_option('wpmps_show_border') ) :
			$wpmps_styles .= ' .wp-border-img-mapa{
														border:1px solid ' . get_option('wpmps_hover_provincia_color').';
													} ';
		endif;

		$wpmps_styles .= '</style>';

		$wpmps_styles = apply_filters('wpmps_map_provincias_style', $wpmps_styles);


		$pagina_inicio = $wpmps_styles.
										'<div class="wpim-wrap-mapa wp-border-img-mapa" style="background-color:'.get_option('wpmps_background_color').'">'
											.$pagina_inicio
									. '</div>';
		return $pagina_inicio;

	}


function wpmps_getUrlContent($url){
	$ua = 'Mozilla/5.0 (Windows; U; Windows NT 5.1; en-US) AppleWebKit/525.13 (KHTML, like Gecko) Chrome/0.A.B.C Safari/525.13';

	$ch = curl_init();
	curl_setopt($ch, CURLOPT_URL, $url);
	curl_setopt($ch, CURLOPT_USERAGENT, $ua);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
	curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 5);
	curl_setopt($ch, CURLOPT_TIMEOUT, 5);
	$data = curl_exec($ch);
	$httpcode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
	curl_close($ch);
	$resultado = array('imagen'=>($httpcode>=200 && $httpcode<300) ? $data : false,
										'httpcode'=>$httpcode);

	return $resultado;

}

function wpmps_getFileContent($url){
	try {
	  $lineas = file($url);
	  if ($lineas):
	    $data = false;
	    foreach ($lineas as $num_linea => $linea) {
		$data .= $linea;
	    }
	    $resultado = array('imagen'  => $data, 'httpcode'=> 200);
	  else:
	    $resultado = array('imagen'  => false, 'httpcode'=> 888);
	  endif;

	} catch (Exception  $e) {
	  $resultado = array('imagen'  => $false, 'httpcode'=> 999);

	}
	return $resultado;

}


function wpmps_establecer_links_provincias($pagina_inicio, $cod_area, $value){

	$class_has_link = false;
	if ('S' == get_option('wpmps_rellenar_provincias_con_enlace') ) :
		$class_has_link = 'has-link';

	endif;

	$pagina_inicio = str_replace('[href'.$cod_area.']', esc_url($value['href']) , $pagina_inicio);

	if ('#'!=$value['href']):
		$pagina_inicio = str_replace('[class'.$cod_area.']', $class_has_link, $pagina_inicio);

	else:
		$pagina_inicio = str_replace('[class'.$cod_area.']', ' ', $pagina_inicio);

	endif;

	$pagina_inicio = str_replace('[target'.$cod_area.']', $value['target'], $pagina_inicio);
	$pagina_inicio = str_replace('[title'.$cod_area.']', $value['title'], $pagina_inicio);

	return $pagina_inicio;
}
add_filter( 'wpmps_establecer_links_provincias', 'wpmps_establecer_links_provincias', 10, 3 );


function wpmps_provincia_link( $class, $codigo ) {
    // (maybe) modify $string
    return $string;
}
add_filter( 'wpmps_provincia_link', 'wpmps_provincia_link', 10, 2 );

function wpmps_map_provincias_style( $wpmps_styles ) {
    // (maybe) modify $string
		//return false;
    return $wpmps_styles;
}
