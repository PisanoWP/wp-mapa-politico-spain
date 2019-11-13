<?php
/*
 * Plugin Name: WP Mapa Politico España
 * Version: 3.2.0
 * Plugin URI: https://mispinitoswp.wordpress.com/
 * Description: Este plugin permite definir para cada una de las provincias de un mapa politico de España un enlace.
 * Author: Juan Carlos Gomez-Lobo
 * Author URI: https://mispinitoswp.wordpress.com/
 *
 * Text Domain: wp-mapa-politico-spain
 * Domain Path: /lang/
 *
 */

define('WPMPS_TEXTDOMAIN', 'wp-mapa-politico-spain');
define('WPMPS_VERSION', '3.2.0');


if ( ! defined( 'ABSPATH' ) ) exit;


// Load plugin class files
require_once( 'includes/class-wp-mapa-politico.php' );
require_once( 'includes/class-wp-mapa-politico-settings.php' );

// Load plugin libraries
require_once( 'includes/lib/class-wp-mapa-politico-admin-api.php' );
require_once( 'includes/lib/class-wp-mapa-politico-coordenadas.php' );

// shortcodes
require_once( 'includes/shortcodes.php' );


/**
 * Returns the main instance of WP_Mapa_Politico to prevent the need to use globals.
 *
 * @since  1.0.0
 * @return object WP_Mapa_Politico
 */
function WP_Mapa_Politico () {

	$instance = WP_Mapa_Politico::instance( __FILE__, WPMPS_VERSION );

	if ( is_null( $instance->settings ) ) {
		$instance->settings = WP_Mapa_Politico_Settings::instance( $instance );
	}

	return $instance;
}

WP_Mapa_Politico();
