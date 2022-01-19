<?php

if ( ! defined( 'ABSPATH' ) ) exit;

class WP_Mapa_Politico {

	/**
	 * The single instance of WP_Mapa_Politico.
	 * @var 	object
	 * @access  private
	 * @since 	1.0.0
	 */
	private static $_instance = null;

	/**
	 * Settings class object
	 * @var     object
	 * @access  public
	 * @since   1.0.0
	 */
	public $settings = null;

	/**
	 * The version number.
	 * @var     string
	 * @access  public
	 * @since   1.0.0
	 */
	public $_version;

	/**
	 * The token.
	 * @var     string
	 * @access  public
	 * @since   1.0.0
	 */
	public $_token;

	/**
	 * The main plugin file.
	 * @var     string
	 * @access  public
	 * @since   1.0.0
	 */
	public $file;

	/**
	 * The main plugin directory.
	 * @var     string
	 * @access  public
	 * @since   1.0.0
	 */
	public $dir;

	/**
	 * The plugin assets directory.
	 * @var     string
	 * @access  public
	 * @since   1.0.0
	 */
	public $assets_dir;

	/**
	 * The plugin assets URL.
	 * @var     string
	 * @access  public
	 * @since   1.0.0
	 */
	public $assets_url;

	/**
	 * Suffix for Javascripts.
	 * @var     string
	 * @access  public
	 * @since   1.0.0
	 */
	public $script_suffix;

	/**
	 * Constructor function.
	 * @access  public
	 * @since   1.0.0
	 * @return  void
	 */
	public function __construct ( $file = '', $version = '1.0.0' ) {
		$this->_version = $version;
		$this->_token = 'wpmps';

		// Load plugin environment variables
		$this->file = $file;
		$this->dir = dirname( $this->file );
		$this->assets_dir = trailingslashit( $this->dir ) . 'assets';
		$this->assets_url = esc_url( trailingslashit( plugins_url( '/assets/', $this->file ) ) );

		$this->script_suffix =  WPMPS_SCRIPT_DEBUG ? '' : '.min';

		register_activation_hook( $this->file, array( $this, 'install' ) );

		// Load admin JS & CSS
		add_action( 'admin_enqueue_scripts', array( $this, 'admin_enqueue_scripts' ), 10, 1 );
		add_action( 'admin_enqueue_scripts', array( $this, 'admin_enqueue_styles' ), 10, 1 );

		// Load API for generic admin functions
		if ( is_admin() ) {
			$this->admin = new WP_Mapa_Politico_Admin_API();
		}


		// Link para donar en la lista de plugins
		add_filter( 'plugin_row_meta', array( $this, 'donate_link'), 10, 2 );

		$this->load_plugin_textdomain();

		//add_action( 'plugins_loaded', array( $this, 'wpmps_load_plugin_textdomain' ) );


	} // End __construct ()


	public function donate_link($links, $file) {
	    if ( dirname( $file ) == plugin_basename($this->dir) ) {
					$links[] = '<a href="https://www.paypal.me/jcglp/1.5" target="_blank">' . __('Donar', 'wp-mapa-politico-spain') . '</a>';
	    }

	    return $links;
	} // donate_link()


	/**
	 * Load admin CSS.
	 * @access  public
	 * @since   1.0.0
	 * @return  void
	 */
	public function admin_enqueue_styles ( $hook = '' ) {
		wp_register_style( $this->_token . '-admin', esc_url( $this->assets_url ) . 'css/admin.css', array(), $this->_version );
		wp_register_style( $this->_token . '-admin', get_stylesheet_uri(), array( 'dashicons' ), array(), $this->_version );

		wp_enqueue_style( $this->_token . '-admin' );

	} // End admin_enqueue_styles ()

	/**
	 * Load admin Javascript.
	 * @access  public
	 * @since   1.0.0
	 * @return  void
	 */
	public function admin_enqueue_scripts ( $hook = '' ) {
		wp_register_script( $this->_token . '-admin', esc_url( $this->assets_url ) . 'js/admin' . $this->script_suffix . '.js', array( 'jquery' ), $this->_version );
		wp_enqueue_script( $this->_token . '-admin' );
	} // End admin_enqueue_scripts ()


	/**
	 * Load plugin textdomain
	 * @access  public
	 * @since   1.0.0
	 * @return  void
	 */
	public function load_plugin_textdomain () {

	    $locale = apply_filters( 'plugin_locale', get_locale(), 'wp-mapa-politico-spain' );

	    load_textdomain( 'wp-mapa-politico-spain', WP_LANG_DIR . '/' . 'wp-mapa-politico-spain' . '/' . 'wp-mapa-politico-spain' . '-' . $locale . '.mo' );

	    load_plugin_textdomain( 'wp-mapa-politico-spain', false, dirname( plugin_basename( $this->file ) ) . '/lang/' );

	} // End load_plugin_textdomain ()

	/**
	 * Main WP_Mapa_Politico Instance
	 *
	 * Ensures only one instance of WP_Mapa_Politico is loaded or can be loaded.
	 *
	 * @since 1.0.0
	 * @static
	 * @see WP_Mapa_Politicoe()
	 * @return Main WP_Mapa_Politico instance
	 */
	public static function instance ( $file = '', $version = '1.0.0' ) {
		if ( is_null( self::$_instance ) ) {
			self::$_instance = new self( $file, $version );
		}
		return self::$_instance;
	} // End instance ()

	/**
	 * Cloning is forbidden.
	 *
	 * @since 1.0.0
	 */
	public function __clone () {
		_doing_it_wrong( __FUNCTION__, __( 'Cheatin&#8217; huh?' ), $this->_version );
	} // End __clone ()

	/**
	 * Unserializing instances of this class is forbidden.
	 *
	 * @since 1.0.0
	 */
	public function __wakeup () {
		_doing_it_wrong( __FUNCTION__, __( 'Cheatin&#8217; huh?' ), $this->_version );
	} // End __wakeup ()

	/**
	 * Installation. Runs on activation.
	 * @access  public
	 * @since   1.0.0
	 * @return  void
	 */
	public function install () {

		WP_Mapa_Politico_Coordenadas::set_default_map();
		$this->_log_version_number();

		// Valores por defecto configuración
		update_option( $this->_token . '_show_border', 'S');
		update_option( $this->_token . '_border_color', '#989898');

		update_option( $this->_token . '_background_color', '#dde6da');
		update_option( $this->_token . '_background_provincia_color', '#8098a8');

		update_option( $this->_token . '_hover_provincia_color', '#265a82');

		update_option( $this->_token . '_rellenar_provincias_con_enlace', 'N');
		update_option( $this->_token . '_has_link_provincia_color', '#989090');

		update_option( $this->_token . '_metodo_recuperar_svg', 'curl' );


	} // End install ()

	/**
	 * Log the plugin version number.
	 * @access  public
	 * @since   1.0.0
	 * @return  void
	 */
	private function _log_version_number () {
		update_option( $this->_token . '_version', $this->_version );
	} // End _log_version_number ()

}
