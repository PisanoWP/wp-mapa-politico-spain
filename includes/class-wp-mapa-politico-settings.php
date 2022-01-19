<?php

if ( ! defined( 'ABSPATH' ) ) exit;

class WP_Mapa_Politico_Settings {

	/**
	 * The single instance of WP_Mapa_Politico_Settings.
	 * @var 	object
	 * @access  private
	 * @since 	1.0.0
	 */
	private static $_instance = null;

	/**
	 * The main plugin object.
	 * @var 	object
	 * @access  public
	 * @since 	1.0.0
	 */
	public $parent = null;

	/**
	 * Prefix for plugin settings.
	 * @var     string
	 * @access  public
	 * @since   1.0.0
	 */
	public $base = '';

	/**
	 * Available settings for plugin.
	 * @var     array
	 * @access  public
	 * @since   1.0.0
	 */
	public $settings = array();

	public function __construct ( $parent ) {
		$this->parent = $parent;

		$this->base = 'wpmps_';

		// Initialise settings
		add_action( 'init', array( $this, 'init_settings' ), 11 );

		// Register plugin settings
		add_action( 'admin_init' , array( $this, 'register_settings' ) );

		// Add settings page to menu
		add_action( 'admin_menu' , array( $this, 'add_menu_item' ) );

		// Add settings link to plugins page
		add_filter( 'plugin_action_links_' . plugin_basename( $this->parent->file ) , array( $this, 'add_settings_link' ) );

	}

	/**
	 * Initialise settings
	 * @return void
	 */
	public function init_settings () {
		$this->settings = $this->settings_fields();
	}

	/**
	 * Add settings page to admin menu
	 * @return void
	 */
	public function add_menu_item () {
		$page = add_options_page( __( 'WP Mapa Politico', 'wp-mapa-politico-spain' ) , __( 'WP Mapa Politico', 'wp-mapa-politico-spain' ) , 'manage_options' , $this->parent->_token . '_settings' ,  array( $this, 'settings_page' ) );
		add_action( 'admin_print_styles-' . $page, array( $this, 'settings_assets' ) );
	}

	/**
	 * Load settings JS & CSS
	 * @return void
	 */
	public function settings_assets () {

		// We're including the farbtastic script & styles here because they're needed for the colour picker
		// If you're not including a colour picker field then you can leave these calls out as well as the farbtastic dependency for the wpt-admin-js script below
		wp_enqueue_style( 'farbtastic' );
		wp_enqueue_script( 'farbtastic' );

		wp_register_script( $this->parent->_token . '-settings-js', $this->parent->assets_url . 'js/settings' . $this->parent->script_suffix . '.js', array( 'farbtastic', 'jquery' ), '1.5.0' );
		wp_enqueue_script( $this->parent->_token . '-settings-js' );
	}

	/**
	 * Add settings link to plugin list table
	 * @param  array $links Existing links
	 * @return array 		Modified links
	 */
	public function add_settings_link ( $links ) {
		$settings_link = '<a href="options-general.php?page=' . $this->parent->_token . '_settings">' . __( 'Configuración', 'wp-mapa-politico-spain' ) . '</a>';
  		array_push( $links, $settings_link );
  		return $links;
	}

	/**
	 * Build settings fields
	 * @return array Fields to be displayed on settings page
	 */
	private function settings_fields () {


		$settings['inicio'] = array(
				'title'					=> __('Inicio', 'wp-mapa-politico-spain' ),
				'description'			=> __( 'Bienvenido al WP Mapa Político España, plugin WordPress que te permitirá mostrar un mapa político de España con áreas seleccionables.', 'wp-mapa-politico-spain' ),
				'fields'				=> array(
						array(
								'id' 			=> 'inicio',
								'label'			=> '',
								'description'	=> '',
								'type'			=> 'inicio',
								'default'		=> '0',

						),
				)
		);


		$settings['mapa'] = array(
				'title'					=> __( 'Mapa', 'wp-mapa-politico-spain' ),
				'description'			=> __( 'Define los enlaces para cada una de las provincias. # es un enlace vacío, a usar sino se quiere definir enlace.', 'wp-mapa-politico-spain' ),
				'fields'				=> array(
												array(
														'id' 			=> 'mapa',
														'label'			=> '',
														'description'	=> '',
														'type'			=> 'mapa',
														'default'		=> '0',

												),
										)
								);

		$settings['configuracion'] = array(
				'title'					=> __( 'Configuración', 'wp-mapa-politico-spain' ),
				'description'			=> __( 'Configuración aspectos del plugin.', 'wp-mapa-politico-spain' ),
				'fields'				=> array(

												array(
														'id' 			=> 'show_border',
														'label'			=> __( '¿Mostrar borde del mapa?', 'wp-mapa-politico-spain' ),
														'description'	=> false,
														'type'			=> 'radio',
														'default'		=> 'S',
														'options' => array('S' => 'Mostrar',
																							'N' => 'Ocultar'),
												),
												array(
														'id' 			=> 'border_color',
														'label'			=> __( 'Color del borde', 'wp-mapa-politico-spain' ),
														'description'	=> __( 'Seleccione el color del borde.', 'wp-mapa-politico-spain' ),
														'type'			=> 'color',
														'default'		=> '#989898',
														'callback'	=> 'sanitize_hex_color'
												),

												array(
														'id' 			=> 'separador1',
														'label'			=> false,
														'description'	=> false,
														'type'			=> 'separador',
														'default'		=> false
												),

												array(
														'id' 			=> 'background_color',
														'label'			=> __( 'Color del fondo del mapa', 'wp-mapa-politico-spain' ),
														'description'	=> __( 'Seleccione el color del fondo del mapa.', 'wp-mapa-politico-spain' ),
														'type'			=> 'color',
														'default'		=> '#dde6da',
														'callback'	=> 'sanitize_hex_color'
												),
												array(
														'id' 			=> 'background_provincia_color',
														'label'			=> __( 'Color del fondo defecto provincias', 'wp-mapa-politico-spain' ),
														'description'	=> __( 'Seleccione el color del fondo de las provincias.', 'wp-mapa-politico-spain' ),
														'type'			=> 'color',
														'default'		=> '#8098a8',
														'callback'	=> 'sanitize_hex_color'
												),
												array(
														'id' 			=> 'hover_provincia_color',
														'label'			=> __( 'Color provincia activa', 'wp-mapa-politico-spain' ),
														'description'	=> __( 'Seleccione el color de activación de las provincias.', 'wp-mapa-politico-spain' ),
														'type'			=> 'color',
														'default'		=> '#265a82',
														'callback'	=> 'sanitize_hex_color'
												),

												array(
														'id' 			=> 'separador2',
														'label'			=> false,
														'description'	=> false,
														'type'			=> 'separador',
														'default'		=> false
												),

												array(
														'id' 			=> 'rellenar_provincias_con_enlace',
														'label'			=> __( '¿Colorear provincias que tienen enlace?', 'wp-mapa-politico-spain' ),
														'description'	=> 'Para pintar las provincias que tienen enlace con un color personalizado.',
														'type'			=> 'radio',
														'default'		=> 'N',
														'options' => array('S' => 'Si',
																							'N' => 'No'),
												),

												array(
														'id' 			=> 'has_link_provincia_color',
														'label'			=> __( 'Color provincia con enlace', 'wp-mapa-politico-spain' ),
														'description'	=> __( 'Seleccione el color de relleno en las provincias con enlace.', 'wp-mapa-politico-spain' ),
														'type'			=> 'color',
														'default'		=> '#989090',
														'callback'	=> 'sanitize_hex_color'
												),

												array(
														'id' 			=> 'separador3',
														'label'			=> false,
														'description'	=> false,
														'type'			=> 'separador',
														'default'		=> false
												),

												array(
														'id' 			=> 'metodo_recuperar_svg',
														'label'			=> __( 'Forma de recuperar la imagen', 'wp-mapa-politico-spain' ),
														'description'	=> 'Metodo interno para recuperar la imagen que muestra el mapa.',
														'type'			=> 'radio',
														'default'		=> 'curl',
														'options' => array('curl' => 'CURL',
																							'file' => 'FILE'),
												),

											)
								);

		$settings = apply_filters( $this->parent->_token . '_settings_fields', $settings );

		return $settings;
	}

	/**
	 * Register plugin settings
	 * @return void
	 */
	public function register_settings () {
		if ( is_array( $this->settings ) ) {

			// Check posted/selected tab
			$current_section = '';
			if ( isset( $_POST['tab'] ) && $_POST['tab'] ) {
				$current_section = sanitize_key($_POST['tab']);
			} else {
				if ( isset( $_GET['tab'] ) && $_GET['tab'] ) {
					$current_section = sanitize_key($_GET['tab']);
				}
			}

			foreach ( $this->settings as $section => $data ) {

				if ( $current_section && $current_section != $section ) continue;

				// Add section to page
				add_settings_section( $section, $data['title'], array( $this, 'settings_section' ), $this->parent->_token . '_settings' );

				foreach ( $data['fields'] as $field ) {

					// Validation callback for field
					$validation = '';
					if ( isset( $field['callback'] ) ) {
						$validation = $field['callback'];
					}

					// Register field
					$option_name = $this->base . $field['id'];
					if (in_array( $field['type'], array('separador'))):
						// Para estos tipos no guardamos opcion en BD
						null;
					else:
						register_setting( $this->parent->_token . '_settings', $option_name, $validation );
					endif;


					// Add field to page
					add_settings_field( $field['id'], $field['label'], array( $this->parent->admin, 'display_field' ), $this->parent->_token . '_settings', $section, array( 'field' => $field, 'prefix' => $this->base ) );
				}

				if ( ! $current_section ) break;
			}
		}
	}

	public function settings_section ( $section ) { ?>
		<p><?php echo wp_kses($this->settings[ $section['id'] ]['description'], array('br'=>array(),'strong'=>array())); ?></p>

	<?php
	}

	/**
	 * Load settings page content
	 * @return void
	 */
	public function settings_page () {
		// Build page HTML ?>
		<div class="wrap" id="<?php echo esc_attr($this->parent->_token . '_settings'); ?>" >
			<h2><?php esc_html_e( 'WP Mapa Politico España' , 'wp-mapa-politico-spain' ); ?></h2>

		<?php
		$tab = false;
		if ( isset( $_GET['tab'] ) && $_GET['tab'] ) {
			$tab = sanitize_text_field($_GET['tab']);
		}

		// Show page tabs
		if ( is_array( $this->settings ) && 1 < count( $this->settings ) ) { ?>

			<h2 class="nav-tab-wrapper">

			<?php
			$c = 0;
			foreach ( $this->settings as $section => $data ) {

				// Set tab class
				$class = 'nav-tab';
				if ( !$tab ) {
					if ( 0 == $c ) {
						$class .= ' nav-tab-active';
					}
				} else {
					if (( $tab ) && ($section == $tab )) {
						$class .= ' nav-tab-active';
					}
				}

				// Set tab link
				$tab_link = add_query_arg( array( 'tab' => $section ) );
				if ( isset( $_GET['settings-updated'] ) ) {
					$tab_link = remove_query_arg( 'settings-updated', $tab_link );
				}

				// Output tab ?>
				<a href="<?php echo esc_url($tab_link);?>" class="<?php echo esc_attr( $class ); ?>">
					<?php
					echo esc_html( $data['title'] ); ?>
				</a>

				<?php
				++$c;

			} ?>

			</h2>
		<?php
		}

		if ($tab=='inicio' || $tab=='') {
			// ob_start();
			settings_fields( $this->parent->_token . '_settings' );
			$this->do_settings_sections_wmps( $this->parent->_token . '_settings' );

			// $html .= ob_get_clean();

		} else if (substr($tab, 0, 4)==='mapa') {

			if (isset($_POST['Submit'])) {

				$wpmps_mapas =  get_option( 'wpmps_plugin_mapas' );

				foreach ($_POST as $key => $valor){
					$v = explode( '-', sanitize_key($key));
					if (4 == count($v) ) {
						if ( isset( $wpmps_mapas[$v[0]][$v[1]][$v[2]][$v[3]] ) ){
							if ('href'==$v[3]):
								$valor = esc_url_raw($valor);
							else:
								$valor = sanitize_text_field($valor);
							endif;
							$wpmps_mapas[$v[0]][$v[1]][$v[2]][$v[3]] = $valor;
						}
					}
				}
				update_option( 'wpmps_plugin_mapas', $wpmps_mapas ); ?>


				<div id="setting-error-settings_updated" class="notice notice-success settings-error is-dismissible">
					<p><strong><?php esc_html_e( 'Datos guardados correctamente' , 'wp-mapa-politico-spain' ); ?></strong></p><button type="button" class="notice-dismiss"><span class="screen-reader-text">Descartar este aviso.</span></button>
				</div>

			<?php
			}
			// Formulario para capturar la informacion del mapa y guararlo como una única opcion ?>
			<form method="post" enctype="multipart/form-data">
				<?php
				// Get settings fields
				//ob_start();
				settings_fields( $this->parent->_token . '_settings' );
				$this->do_settings_sections_wmps( $this->parent->_token . '_settings' );
				//$html .= ob_get_clean(); ?>

				<p class="submit">
					<input type="hidden" name="tab" value="<?php esc_attr_e( $tab ); ?>" />
					<input name="Submit" type="submit" class="button-primary" value="<?php esc_attr_e( 'Guardar cambios' , 'wp-mapa-politico-spain' );?>" />
				</p>
			</form>

		<?php

		} else { ?>

			<form method="post" action="options.php" enctype="multipart/form-data">
			<?php
				// Get settings fields
				settings_fields( $this->parent->_token . '_settings' );
				$this->do_settings_sections_wmps( $this->parent->_token . '_settings' );  ?>

				<p class="submit">
					<input type="hidden" name="tab" value="<?php echo esc_attr( $tab ); ?>" />
					<input name="Submit" type="submit" class="button-primary" value="<?php esc_attr_e( 'Guardar cambios' , 'wp-mapa-politico-spain' ); ?> " />
				</p>
			</form>

		<?php
		} ?>

		</div>

	<?php
	}


	function do_settings_sections_wmps( $page ) {
		global $wp_settings_sections, $wp_settings_fields;

		if ( ! isset( $wp_settings_sections[$page] ) )
			return;

		foreach ( (array) $wp_settings_sections[$page] as $section ) {
			if ( $section['title'] ): ?>
				<h3><?php echo esc_html($section['title']); ?></h3>
			<?php
			endif;

			if ( $section['callback'] )
				call_user_func( $section['callback'], $section );

			if ( ! isset( $wp_settings_fields ) || !isset( $wp_settings_fields[$page] ) || !isset( $wp_settings_fields[$page][$section['id']] ) )
				continue;

			if ('inicio' != $section['id']): ?>
				<input name="Submit" type="submit" class="button-primary" value="<?php esc_attr_e( 'Guardar cambios' , 'wp-mapa-politico-spain' ); ?>" />
			<?php
			endif; ?>

			<table class="wpmps-form-table">
				<?php
				$this->do_settings_fields_wpms( $page, $section['id'] ); ?>
			</table>
		<?php
		}
	}


	function do_settings_fields_wpms($page, $section) {
		global $wp_settings_fields;

		if ( ! isset( $wp_settings_fields[$page][$section] ) )
			return;

		foreach ( (array) $wp_settings_fields[$page][$section] as $field ) {

			$class = false;
			if ( !empty( $field['args']['class'] ) ):
				$class =  $field['args']['class'];
			endif; ?>

			<tr class="<?php echo esc_attr($class); ?>">

			<?php
				$label_for = false;
				if (!empty( $field['args']['label_for'] ) ):
					$label_for = $field['args']['label_for'];
				endif;

				$title = false;
				if (!empty ($field['title']) ):
					$title = $field['title'];
				endif; ?>

				<th scope="col">
					<?php
					if ($label_for): ?>
						<label for="<?php echo esc_attr( $label_for ); ?>">
							<?php echo esc_html($title); ?>
						</label>

					<?php
					else:
						echo esc_html($title);
					endif; ?>
				</th>

			</tr>

			<tr class="<?php echo esc_attr($class); ?>">
				<td>
					<?php
					call_user_func($field['callback'], $field['args']); ?>
				</td>
			</tr>

		<?php
		}
	}

	/**
	 * Main WP_Mapa_Politico_Settings Instance
	 *
	 * Ensures only one instance of WP_Mapa_Politico_Settings is loaded or can be loaded.
	 *
	 * @since 1.0.0
	 * @static
	 * @see WP_Mapa_Politico()
	 * @return Main WP_Mapa_Politico_Settings instance
	 */
	public static function instance ( $parent ) {
		if ( is_null( self::$_instance ) ) {
			self::$_instance = new self( $parent );
		}
		return self::$_instance;
	} // End instance()

	/**
	 * Cloning is forbidden.
	 *
	 * @since 1.0.0
	 */
	public function __clone () {
		_doing_it_wrong( __FUNCTION__, __( 'Cheatin&#8217; huh?' ), $this->parent->_version );
	} // End __clone()

	/**
	 * Unserializing instances of this class is forbidden.
	 *
	 * @since 1.0.0
	 */
	public function __wakeup () {
		_doing_it_wrong( __FUNCTION__, __( 'Cheatin&#8217; huh?' ), $this->parent->_version );
	} // End __wakeup()

}
