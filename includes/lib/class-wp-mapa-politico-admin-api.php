<?php

if ( ! defined( 'ABSPATH' ) ) exit;

class WP_Mapa_Politico_Admin_API {

	/**
	 * Constructor function
	 */
	public function __construct () {
	}

	/**
	 * Generate HTML for displaying fields
	 * @param  array   $field Field data
	 * @param  boolean $echo  Whether to echo the field HTML or return it
	 * @return void
	 */
	public function display_field ( $data = array() ) {

		// Get field info
		if ( isset( $data['field'] ) ) {
			$field = $data['field'];
		} else {
			$field = $data;
		}

		// Check for prefix on option name
		$option_name = '';
		if ( isset( $data['prefix'] ) ) {
			$option_name = $data['prefix'];
		}

		// Get saved data
		$data = '';

		// Get saved option
		$option_name .= $field['id'];
		$option = get_option( $option_name );

		// Get data to display in field
		if ( isset( $option ) ) {
			$data = $option;
		}

		// Show default data if no option saved and default is supplied
		if ( $data === false && isset( $field['default'] ) ) {
			$data = $field['default'];
		} elseif ( $data === false ) {
			$data = '';
		}

		$html = '';

		switch( $field['type'] ) {

			case 'separador': ?>
				<hr />
			<?php
			break;


			case 'text':
			case 'url':
			case 'email':
				$html .= '<input id="' . esc_attr( $field['id'] ) . '" type="text" name="' . esc_attr( $option_name ) . '" placeholder="' . esc_attr( $field['placeholder'] ) . '" value="' . esc_attr( $data ) . '" />' . "\n";
			break;

			case 'password':
			case 'number':
			case 'hidden':
				$min = '';
				if ( isset( $field['min'] ) ) {
					$min = ' min="' . esc_attr( $field['min'] ) . '"';
				}

				$max = '';
				if ( isset( $field['max'] ) ) {
					$max = ' max="' . esc_attr( $field['max'] ) . '"';
				}
				$html .= '<input id="' . esc_attr( $field['id'] ) . '" type="' . esc_attr( $field['type'] ) . '" name="' . esc_attr( $option_name ) . '" placeholder="' . esc_attr( $field['placeholder'] ) . '" value="' . esc_attr( $data ) . '"' . $min . '' . $max . '/>' . "\n";
			break;



			case 'radio':
				foreach ( $field['options'] as $k => $v ) {
					$checked = false;
					if ( $k == $data ) {
						$checked = true;
					} ?>
					<label for="<?php echo esc_attr( $field['id'] . '_' . $k ); ?>">
						<input type="radio" <?php echo esc_attr(checked( $checked, true, false )); ?>
									 name="<?php echo esc_attr( $option_name ); ?>"
									 value="<?php echo esc_attr( $k ); ?>"
									 id="<?php echo esc_attr( $field['id'] . '_' . $k ); ?>" />
						<?php echo esc_html($v);?>
					</label>
				<?php
				} ?>
				<br>
				<?php
			break;

			case 'select':
				$html .= '<select name="' . esc_attr( $option_name ) . '" id="' . esc_attr( $field['id'] ) . '">';
				foreach ( $field['options'] as $k => $v ) {
					$selected = false;
					if ( $k == $data ) {
						$selected = true;
					}
					$html .= '<option ' . selected( $selected, true, false ) . ' value="' . esc_attr( $k ) . '">' . $v . '</option>';
				}
				$html .= '</select> ';
			break;


			case 'color':
				// Para los colores, sino tengo valor, le pongo un po por defecto
				if (empty($data)):
					switch ($option_name) {
						case 'wpmps_border_color': 	$data = '#989898'; break;
						case 'wpmps_background_color': 	$data = '#dde6da'; break;
						case 'wpmps_background_provincia_color': 	$data = '#8098a8'; break;
						case 'wpmps_hover_provincia_color': 	$data = '#265a82'; break;
						case 'wpmps_has_link_provincia_color': 	$data = '#989090'; break;
						default: $data ='#8098a8'; break;
					}
				endif;
				?>
				<div class="color-picker" style="position:relative;">
		        <input type="text" name="<?php esc_attr_e( $option_name ); ?>" class="color" value="<?php esc_attr_e( $data ); ?>" />
		        <div style="position:absolute;background:#FFF;z-index:99;border-radius:100%;" class="colorpicker"></div>
		    </div>
			<?php
			break;

			case 'inicio':
				// Información del plugin ?>
				<div>
					<ol>
						<?php
						$url_options = get_admin_url().'options-general.php?page=wpmps_settings&tab='; ?>
						<li><?php printf(__('Pulsa <a href="%s">aquí</a> para empezar a definir los enlaces de cada una de las provincias.', 'wp-mapa-politico-spain')
														, esc_url($url_options.'mapa') ); ?></li>
						<li><?php printf(__('¿Quieres cambiar el color del fondo, o de las provincias? pulsa <a href="%s">aquí.</a>', 'wp-mapa-politico-spain')
														, esc_url($url_options.'configuracion') ); ?> </li>
						<li><?php printf(__('Utiliza el shortcode <strong>%s</strong> en los post o páginas donde mostrar el mapa político.', 'wp-mapa-politico-spain')
														, '[wpmps-map]' ); ?> </li>
					</ol>
				</div>

				<?php
				do_action('wpms_show_modules_info');  ?>

        <h3><?php esc_html_e('Valora el plugin', 'wp-mapa-politico-spain'); ?> <span class="cinco-estrellas"></span></h3>
				<p>
					<?php printf(__('¿Te gusta el plugin? ¿lo estas usando en tu página?, pues puedes valorar el plugin en <a href="%s" target="_blank">WordPress.org</a>, que te estaría muy agradecido :-)', 'wp-mapa-politico-spain' )
					, esc_url('https://wordpress.org/support/view/plugin-reviews/wp-mapa-politico-spain?filter=5') );

					?>
				</p>

				<div class="" style="display:flex;flex-direction:row;flex-grow:1">

					<div class="" style="width:40%;">
						<h3><?php esc_html_e('Invítame a un café', 'wp-mapa-politico-spain'); ?></h3>
						<p><?php esc_html_e('Detrás de este plugins hay un montón de horas de trabajo, muchas de ellas nocturnas, así que ¿por qué no me invitas a un café? así podré seguir haciendo mejoras', 'wp-mapa-politico-spain'); ?></p>
						<p style="text-align:center;">						
							<a  href="https://www.paypal.me/jcglp/1.5" title="Invitame a un café" target="_blank" >

								<img src="<?php echo esc_url(WPMPS_URL . '/images/btn_donate_LG.gif'); ?>" alt="paypal logo">
							</a>
						</p>
					</div>

					<div class="" style="width:40%;border-left:solid 1px black;padding-left:10px;">
						<h3><?php esc_html_e('Soporte', 'wp-mapa-politico-spain'); ?></h3>
						<p> <?php esc_html_e('¿Tienes dudas o sugerencias? Aquí tienes unos enlaces que te pueden ayudar.', 'wp-mapa-politico-spain'); ?></p>
						<ul>
							<li><a target="_blank" href="https://mispinitoswp.wordpress.com/wp-mapa-politico-espana-faq/"><?php esc_html_e('Preguntas frecuentes', 'wp-mapa-politico-spain'); ?></a></li>
							<li><a target="_blank" href="https://mispinitoswp.wordpress.com/contacto/"><?php esc_html_e('Sugerir mejoras', 'wp-mapa-politico-spain'); ?></a></li>
							<li><a target="_blank" href="https://wordpress.org/support/plugin/wp-mapa-politico-spain"><?php esc_html_e('Informar de un Bug', 'wp-mapa-politico-spain'); ?></a></li>
						</ul>
			 		</div>

				</div>

				<?php

			break;

			case 'mapa':

				$id_mapa = $field['default']; // Default map. 0 España
				$opciones =	get_option( 'wpmps_plugin_mapas' );
				$mapa = $opciones[$id_mapa]; ?>
				<h3>
					<?php esc_html_e('Enlaces Asociados a Provincias', 'wp-mapa-politico-spain'); ?></h3>
          <input name="id-mapa" type="hidden" value=<?php echo esc_attr($id_mapa); ?> >
          <table>
					<tr>
						<th><?php esc_html_e('Zona', 'wp-mapa-politico-spain' ); ?></th>
						<th><?php esc_html_e('Href', 'wp-mapa-politico-spain' ); ?></th>
						<th><?php esc_html_e('Title', 'wp-mapa-politico-spain' ); ?></th>
						<th><?php esc_html_e('Target', 'wp-mapa-politico-spain' ); ?></th>
					</tr>

					<?php
					foreach ($mapa['areas'] as $key => $zona) { ?>
					<tr>
						<td><?php echo esc_html($zona["desc_area"]);?></td>
						<td>
							<input style="width:300px;"
										type="text"
										name="<?php echo esc_attr($id_mapa.'-areas-'.$key."-href"); ?>"
										value="<?php echo esc_url($zona["href"]);?>">
						</td>
						<td>
							<input type="text"
										name="<?php echo esc_attr($id_mapa.'-areas-'.$key."-title"); ?>"
										value="<?php  echo esc_html($zona["title"]);?>">
						</td>
						<td>
							<select name="<?php echo esc_attr($id_mapa.'-areas-'.$key."-target"); ?>" >
								<option <?php if ( $zona["target"]=='_blank') echo esc_attr('selected'); ?> value="_blank"><?php esc_html_e('Nueva Ventana', 'wp-mapa-politico-spain'); ?></option>
								<option <?php if ( $zona["target"]=='_self') echo esc_attr('selected'); ?> value="_self"><?php esc_html_e('Misma Ventana', 'wp-mapa-politico-spain'); ?></option>
							</select>
						</td>
					</tr>
					<?php
					}  ?>
				</table>
				<?php
				break;
		}

		switch( $field['type'] ) {

			case 'checkbox_multi':
			case 'radio':
			case 'select_multi': ?>
				<span class="description"><?php echo esc_html($field['description']); ?></span>
			<?php
			break;

			default: ?>
				<label for="<?php echo esc_attr( $field['id'] ); ?>">
					<span class="description"><?php echo esc_html($field['description']); ?></span>
				</label>
			<?php
			break;
		}

	}

}
