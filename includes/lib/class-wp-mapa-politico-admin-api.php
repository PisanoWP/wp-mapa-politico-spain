<?php

if ( ! defined( 'ABSPATH' ) ) exit;

class WP_Mapa_Politico_Admin_API {

	/**
	 * Constructor function
	 */
	public function __construct () {
		add_action( 'save_post', array( $this, 'save_meta_boxes' ), 10, 1 );
	}

	/**
	 * Generate HTML for displaying fields
	 * @param  array   $field Field data
	 * @param  boolean $echo  Whether to echo the field HTML or return it
	 * @return void
	 */
	public function display_field ( $data = array(), $post = false, $echo = true ) {

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
		if ( $post ) {

			// Get saved field data
			$option_name .= $field['id'];
			$option = get_post_meta( $post->ID, $field['id'], true );

			// Get data to display in field
			if ( isset( $option ) ) {
				$data = $option;
			}

		} else {

			// Get saved option
			$option_name .= $field['id'];
			$option = get_option( $option_name );

			// Get data to display in field
			if ( isset( $option ) ) {
				$data = $option;
			}

		}

		// Show default data if no option saved and default is supplied
		if ( $data === false && isset( $field['default'] ) ) {
			$data = $field['default'];
		} elseif ( $data === false ) {
			$data = '';
		}

		$html = '';

		switch( $field['type'] ) {

			case 'separador':
				$html .= '<hr />' . "\n";
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

			case 'text_secret':
				$html .= '<input id="' . esc_attr( $field['id'] ) . '" type="text" name="' . esc_attr( $option_name ) . '" placeholder="' . esc_attr( $field['placeholder'] ) . '" value="" />' . "\n";
			break;

			case 'textarea':
				$html .= '<textarea id="' . esc_attr( $field['id'] ) . '" rows="5" cols="50" name="' . esc_attr( $option_name ) . '" placeholder="' . esc_attr( $field['placeholder'] ) . '">' . $data . '</textarea><br/>'. "\n";
			break;

			case 'checkbox':
				$checked = '';
				if ( $data && 'on' == $data ) {
					$checked = 'checked="checked"';
				}
				$html .= '<input id="' . esc_attr( $field['id'] ) . '" type="' . esc_attr( $field['type'] ) . '" name="' . esc_attr( $option_name ) . '" ' . $checked . '/>' . "\n";
			break;

			case 'checkbox_multi':
				foreach ( $field['options'] as $k => $v ) {
					$checked = false;
					if ( in_array( $k, $data ) ) {
						$checked = true;
					}
					$html .= '<label for="' . esc_attr( $field['id'] . '_' . $k ) . '" class="checkbox_multi"><input type="checkbox" ' . checked( $checked, true, false ) . ' name="' . esc_attr( $option_name ) . '[]" value="' . esc_attr( $k ) . '" id="' . esc_attr( $field['id'] . '_' . $k ) . '" /> ' . $v . '</label> ';
				}
			break;

			case 'radio':
				foreach ( $field['options'] as $k => $v ) {
					$checked = false;
					if ( $k == $data ) {
						$checked = true;
					}
					$html .= '<label for="' . esc_attr( $field['id'] . '_' . $k ) . '"><input type="radio" ' . checked( $checked, true, false ) . ' name="' . esc_attr( $option_name ) . '" value="' . esc_attr( $k ) . '" id="' . esc_attr( $field['id'] . '_' . $k ) . '" /> ' . $v . '</label> ';
				}
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

			case 'select_multi':
				$html .= '<select name="' . esc_attr( $option_name ) . '[]" id="' . esc_attr( $field['id'] ) . '" multiple="multiple">';
				foreach ( $field['options'] as $k => $v ) {
					$selected = false;
					if ( in_array( $k, $data ) ) {
						$selected = true;
					}
					$html .= '<option ' . selected( $selected, true, false ) . ' value="' . esc_attr( $k ) . '">' . $v . '</option>';
				}
				$html .= '</select> ';
			break;

			case 'image':
				$image_thumb = '';
				if ( $data ) {
					$image_thumb = wp_get_attachment_thumb_url( $data );
				}
				$html .= '<img id="' . $option_name . '_preview" class="image_preview" src="' . $image_thumb . '" /><br/>' . "\n";
				$html .= '<input id="' . $option_name . '_button" type="button" data-uploader_title="' . __( 'Subir una imagen' , WPMPS_TEXTDOMAIN ) . '" data-uploader_button_text="' . __( 'Usar imagen' , WPMPS_TEXTDOMAIN ) . '" class="image_upload_button button" value="'. __( 'Subir nueva imagen' , WPMPS_TEXTDOMAIN ) . '" />' . "\n";
				$html .= '<input id="' . $option_name . '_delete" type="button" class="image_delete_button button" value="'. __( 'Borrar imagen' , WPMPS_TEXTDOMAIN ) . '" />' . "\n";
				$html .= '<input id="' . $option_name . '" class="image_data_field" type="hidden" name="' . $option_name . '" value="' . $data . '"/><br/>' . "\n";
			break;

			case 'color':
				?><div class="color-picker" style="position:relative;">
			        <input type="text" name="<?php esc_attr_e( $option_name ); ?>" class="color" value="<?php esc_attr_e( $data ); ?>" />
			        <div style="position:absolute;background:#FFF;z-index:99;border-radius:100%;" class="colorpicker"></div>
			    </div>
			    <?php
			break;

			case 'inicio':
				// Información del plugin
				?>

				<div>
					<ol>
						<?php
							$url_options = get_admin_url().'options-general.php?page=wpmps_settings&tab=';

						?>
						<li><?php printf(__('Pulsa <a href="%s">aquí</a> para empezar a definir los enlaces de cada una de las provincias.', WPMPS_TEXTDOMAIN)
														, esc_url($url_options.'mapa') ); ?></li>
						<li><?php printf(__('¿Quieres cambiar el color del fondo, o de las provincias? pulsa <a href="%s">aquí.</a>', WPMPS_TEXTDOMAIN)
														, esc_url($url_options.'configuracion') ); ?> </li>
						<li><?php printf(__('Utiliza el shortcode <strong>%s</strong> en los post o páginas donde mostrar el mapa político.', WPMPS_TEXTDOMAIN)
														, '[wpmps-map]' ); ?> </li>
					</ol>
					<ul>
						<li> <strong><?php _e('AVISO', WPMPS_TEXTDOMAIN); ?></strong><?php _e('Esta nueva versión utiliza imágenes SVG, para obtener un plugin más ligero y compatible con la mayoría de los temas.', WPMPS_TEXTDOMAIN); ?></li>
					</ul>

				</div>
				<br />

        <h3><?php _e('Valora el plugin', WPMPS_TEXTDOMAIN); ?> <span class="cinco-estrellas"></span></h3>
				<p>
					<?php printf(__('¿Te gusta el plugin? ¿lo estas usando en tu página?, pues puedes valorar el plugin en <a href="%s" target="_blank">WordPress.org</a>, que te estaría muy agradecido :-)', WPMPS_TEXTDOMAIN )
					, esc_url('https://wordpress.org/support/view/plugin-reviews/wp-mapa-politico-spain?filter=5') );

					?>
				</p>


				<h3><?php _e('Invítame a un café', WPMPS_TEXTDOMAIN); ?></h3>
				<p><?php _e('Detrás de este plugins hay un montón de horas de trabajo, muchas de ellas nocturnas, así que ¿por qué no me invitas a un café? así podré seguir haciendo mejoras', WPMPS_TEXTDOMAIN); ?></p>

					<a  href="https://www.paypal.me/jcglp/1.5" title="Invitame a un café" target="_blank">
						<img src="<?php echo plugins_url( 'wp-mapa-politico-spain/images/btn_donate_LG.gif'); ?>" alt="paypal logo">
				 </a>

			 </p>

				<h3><?php _e('Soporte', WPMPS_TEXTDOMAIN); ?></h3>
				<p> <?php _e('¿Tienes dudas o sugerencias? Aquí tienes unos enlaces que te pueden ayudar.', WPMPS_TEXTDOMAIN); ?></p>
				<ul>
					<li><a target="_blank" href="https://mispinitoswp.wordpress.com/faq/"><?php _e('Preguntas frecuentes', WPMPS_TEXTDOMAIN); ?></a></li>
					<li><a target="_blank" href="https://mispinitoswp.wordpress.com/contacto/"><?php _e('Sugerir mejoras', WPMPS_TEXTDOMAIN); ?></a></li>
					<li><a target="_blank" href="https://wordpress.org/support/plugin/wp-mapa-politico-spain"><?php _e('Informar de un Bug', WPMPS_TEXTDOMAIN); ?></a></li>
				</ul>

				<?php

			break;

			case 'mapa':

				$id_mapa = $data; // Default map. España

				//$opciones = WP_Mapa_Politico_Coordenadas::get_coordenadas(0);
				$opciones =	get_option( 'wpmps_plugin_mapas' );


				$mapa = $opciones[$id_mapa];

				?>
				<h3><?php  _e ('Enlaces Asociados a Provincias', WPMPS_TEXTDOMAIN); ?></h3>

                <input name="id-mapa" type="hidden" value=<?php echo $id_mapa; ?> >
                <table>
                	<tr>
                		<th><?php _e ('Zona', WPMPS_TEXTDOMAIN ); ?></th>
                		<th><?php _e ('Href', WPMPS_TEXTDOMAIN ); ?></th>
                		<th><?php _e ('Title', WPMPS_TEXTDOMAIN ); ?></th>
                		<th><?php _e ('Target', WPMPS_TEXTDOMAIN ); ?></th>

                	</tr>

                    <?php foreach ($mapa['areas'] as $key => $zona) { ?>

                    <?php //	echo '<pre>'; echo $key; print_r($zona); echo '</pre>'; die; ?>
                    <tr>
                    	<td><?php echo $zona["desc_area"];?></td>

                    	<td><input style="width:300px;" type="text" name="<?php echo $id_mapa.'-areas-'.$key."-href"; ?>" value="<?php echo $zona["href"];?>"></td>

                    	<td><input type="text" name="<?php echo $id_mapa.'-areas-'.$key."-title"?>" value="<?php  echo $zona["title"];?>"></td>

						<td><select name="<?php echo $id_mapa.'-areas-'.$key."-target"?>" >
							<option <?php if ( $zona["target"]=='_blank') echo 'selected'; ?> value="_blank"><?php _e('Nueva Ventana', WPMPS_TEXTDOMAIN); ?></option>
							<option <?php if ( $zona["target"]=='_self') echo 'selected'; ?> value="_self"><?php _e('Misma Ventana', WPMPS_TEXTDOMAIN); ?></option>
						</select>
                    	</td>
                    </tr>
                    <?php }  ?>

				</table>
				<?php
			break;

		}

		switch( $field['type'] ) {

			case 'checkbox_multi':
			case 'radio':
			case 'select_multi':
				$html .= '<br/><span class="description">' . $field['description'] . '</span>';
			break;

			default:
				if ( ! $post ) {
					$html .= '<label for="' . esc_attr( $field['id'] ) . '">' . "\n";
				}

				$html .= '<span class="description">' . $field['description'] . '</span>' . "\n";

				if ( ! $post ) {
					$html .= '</label>' . "\n";
				}
			break;
		}

		if ( ! $echo ) {
			return $html;
		}

		echo $html;

	}

	/**
	 * Validate form field
	 * @param  string $data Submitted value
	 * @param  string $type Type of field to validate
	 * @return string       Validated value
	 */
	public function validate_field ( $data = '', $type = 'text' ) {

		switch( $type ) {
			case 'text': $data = esc_attr( $data ); break;
			case 'url': $data = esc_url( $data ); break;
			case 'email': $data = is_email( $data ); break;
		}

		return $data;
	}

	/**
	 * Add meta box to the dashboard
	 * @param string $id            Unique ID for metabox
	 * @param string $title         Display title of metabox
	 * @param array  $post_types    Post types to which this metabox applies
	 * @param string $context       Context in which to display this metabox ('advanced' or 'side')
	 * @param string $priority      Priority of this metabox ('default', 'low' or 'high')
	 * @param array  $callback_args Any axtra arguments that will be passed to the display function for this metabox
	 * @return void
	 */
	public function add_meta_box ( $id = '', $title = '', $post_types = array(), $context = 'advanced', $priority = 'default', $callback_args = null ) {

		// Get post type(s)
		if ( ! is_array( $post_types ) ) {
			$post_types = array( $post_types );
		}

		// Generate each metabox
		foreach ( $post_types as $post_type ) {
			add_meta_box( $id, $title, array( $this, 'meta_box_content' ), $post_type, $context, $priority, $callback_args );
		}
	}

	/**
	 * Display metabox content
	 * @param  object $post Post object
	 * @param  array  $args Arguments unique to this metabox
	 * @return void
	 */
	public function meta_box_content ( $post, $args ) {

		$fields = apply_filters( $post->post_type . '_custom_fields', array(), $post->post_type );

		if ( ! is_array( $fields ) || 0 == count( $fields ) ) return;

		echo '<div class="custom-field-panel">' . "\n";

		foreach ( $fields as $field ) {

			if ( ! isset( $field['metabox'] ) ) continue;

			if ( ! is_array( $field['metabox'] ) ) {
				$field['metabox'] = array( $field['metabox'] );
			}

			if ( in_array( $args['id'], $field['metabox'] ) ) {
				$this->display_meta_box_field( $field, $post );
			}

		}

		echo '</div>' . "\n";

	}

	/**
	 * Dispay field in metabox
	 * @param  array  $field Field data
	 * @param  object $post  Post object
	 * @return void
	 */
	public function display_meta_box_field ( $field = array(), $post ) {

		if ( ! is_array( $field ) || 0 == count( $field ) ) return;

		$field = '<p class="form-field"><label for="' . $field['id'] . '">' . $field['label'] . '</label>' . $this->display_field( $field, $post, false ) . '</p>' . "\n";

		echo $field;
	}

	/**
	 * Save metabox fields
	 * @param  integer $post_id Post ID
	 * @return void
	 */
	public function save_meta_boxes ( $post_id = 0 ) {

		if ( ! $post_id ) return;

		$post_type = get_post_type( $post_id );

		$fields = apply_filters( $post_type . '_custom_fields', array(), $post_type );

		if ( ! is_array( $fields ) || 0 == count( $fields ) ) return;

		foreach ( $fields as $field ) {
			if ( isset( $_REQUEST[ $field['id'] ] ) ) {
				update_post_meta( $post_id, $field['id'], $this->validate_field( $_REQUEST[ $field['id'] ], $field['type'] ) );
			} else {
				update_post_meta( $post_id, $field['id'], '' );
			}
		}
	}

}
