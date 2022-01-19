<?php


// If plugin is not being uninstalled, exit (do nothing)
if ( ! defined( 'WP_UNINSTALL_PLUGIN' ) ) {
	exit;
}

// Al borrar el plugin limpiamos todas las opciones grabadas
delete_option('wpmps_plugin_mapas');
delete_option('wpmps_version');

delete_option('wpmps_show_border');
delete_option('wpmps_border_color');

delete_option('wpmps_background_color');
delete_option('wpmps_background_provincia_color');

delete_option('wpmps_hover_provincia_color');

delete_option('wpmps_rellenar_provincias_con_enlace');
delete_option('wpmps_has_link_provincia_color');

delete_option('wpmps_metodo_recuperar_svg');

delete_option('wpmps_separador1');
delete_option('wpmps_separador2');
delete_option('wpmps_separador3');
