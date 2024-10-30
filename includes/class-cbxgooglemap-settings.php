<?php
// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

/**
 * Wordpress Settings API wrapper class
 *
 * @version 1.2
 * Last update: 27.09.2016
 *
 * Initial version Forked from https://github.com/tareq1988/wordpress-settings-api-class
 *
 */
if ( ! class_exists( 'CBXGooglemapSettings' ) ):

	class CBXGooglemapSettings {

		/**
		 * settings sections array
		 *
		 * @var array
		 */
		protected $settings_sections = [];

		/**
		 * Settings fields array
		 *
		 * @var array
		 */
		protected $settings_fields = [];

		public function __construct() {

		}

		/**
		 * Set settings sections
		 *
		 * @param array $sections setting sections array
		 */
		function set_sections( $sections ) {
			$this->settings_sections = $sections;

			return $this;
		}

		/**
		 * Add a single section
		 *
		 * @param array $section
		 */
		function add_section( $section ) {
			$this->settings_sections[] = $section;

			return $this;
		}

		/**
		 * Set settings fields
		 *
		 * @param array $fields settings fields array
		 */
		function set_fields( $fields ) {
			$this->settings_fields = $fields;

			return $this;
		}

		function add_field( $section, $field ) {
			$defaults = [
				'name'  => '',
				'label' => '',
				'desc'  => '',
				'type'  => 'text'
			];

			$arg                                 = wp_parse_args( $field, $defaults );
			$this->settings_fields[ $section ][] = $arg;

			return $this;
		}

		/**
		 * Initialize and registers the settings sections and fields to WordPress
		 *
		 * Usually this should be called at `admin_init` hook.
		 *
		 * This function gets the initiated settings sections and fields. Then
		 * registers them to WordPress and ready for use.
		 */
		function admin_init() {
			//register settings sections
			foreach ( $this->settings_sections as $section ) {
				if ( false == get_option( $section['id'] ) ) {
					add_option( $section['id'] );
				}

				if ( isset( $section['desc'] ) && ! empty( $section['desc'] ) ) {
					$section['desc'] = '<div class="inside">' . $section['desc'] . '</div>';
					$callback        = create_function( '', 'echo "' . str_replace( '"', '\"', $section['desc'] ) . '";' );
				} elseif ( isset( $section['callback'] ) ) {
					$callback = $section['callback'];
				} else {
					$callback = null;
				}

				add_settings_section( $section['id'], $section['title'], $callback, $section['id'] );
			}

			//register settings fields
			foreach ( $this->settings_fields as $section => $field ) {
				foreach ( $field as $option ) {

					$name = $option['name'];
					$type = isset( $option['type'] ) ? $option['type'] : 'text';

					$args = [
						'id'                => $option['name'],
						'class'             => isset( $option['class'] ) ? $option['class'] : $name,
						'label_for'         => $args['label_for'] = "{$section}[{$option['name']}]",
						'desc'              => isset( $option['desc'] ) ? $option['desc'] : '',
						'name'              => $option['label'],
						'section'           => $section,
						'size'              => isset( $option['size'] ) ? $option['size'] : null,
						'options'           => isset( $option['options'] ) ? $option['options'] : '',
						'default'           => isset( $option['default'] ) ? $option['default'] : '',
						'sanitize_callback' => isset( $option['sanitize_callback'] ) ? $option['sanitize_callback'] : '',
						'type'              => $type,
						'placeholder'       => isset( $option['placeholder'] ) ? $option['placeholder'] : '',
						'optgroup'          => isset( $option['optgroup'] ) ? intval( $option['optgroup'] ) : 0,
						'inline'            => isset( $option['inline'] ) ? intval( $option['inline'] ) : 0,
					];

					add_settings_field( $section . '[' . $option['name'] . ']', $option['label'], [
						$this,
						'callback_' . $type
					], $section, $section, $args );
				}
			}

			// creates our settings in the options table
			foreach ( $this->settings_sections as $section ) {
				register_setting( $section['id'], $section['id'], [ $this, 'sanitize_options' ] );
			}
		}

		/**
		 * Get field description for display
		 *
		 * @param array $args settings field args
		 */
		public function get_field_description( $args ) {
			if ( ! empty( $args['desc'] ) ) {
				$desc = sprintf( '<p class="description">%s</p>', $args['desc'] );
			} else {
				$desc = '';
			}

			return $desc;
		}

		/**
		 * Displays a text field for a settings field
		 *
		 * @param array $args settings field args
		 */
		function callback_text( $args ) {


			$value = esc_attr( $this->get_option( $args['id'], $args['section'], $args['default'] ) );


			$size = isset( $args['size'] ) && ! is_null( $args['size'] ) ? $args['size'] : 'regular';
			$type = isset( $args['type'] ) ? $args['type'] : 'text';


			$html = sprintf( '<input type="%1$s" class="%2$s-text" id="%3$s[%4$s]" name="%3$s[%4$s]" value="%5$s" placeholder="%6$s"  />', $type, $size, $args['section'], $args['id'], $value, $args['placeholder'] );
			$html .= $this->get_field_description( $args );

			echo $html;
		}

		/**
		 * Displays a text field for a settings field
		 *
		 * @param array $args settings field args
		 */
		function callback_location( $args ) {


			$value = esc_attr( $this->get_option( $args['id'], $args['section'], $args['default'] ) );


			$size = isset( $args['size'] ) && ! is_null( $args['size'] ) ? $args['size'] : 'regular';
			$type = isset( $args['type'] ) ? $args['type'] : 'text';


			$html = sprintf( '<input type="%1$s" class="%2$s-text" id="%3$s[%4$s]" name="%3$s[%4$s]" value="%5$s" placeholder="%6$s"  />', $type, $size, $args['section'], $args['id'], $value, $args['placeholder'] );
			$html .= $this->get_field_description( $args );

			echo $html;
		}

		/**
		 * Displays a url field for a settings field
		 *
		 * @param array $args settings field args
		 */
		function callback_url( $args ) {
			$this->callback_text( $args );
		}

		/**
		 * Displays a number field for a settings field
		 *
		 * @param array $args settings field args
		 */
		function callback_number( $args ) {
			$this->callback_text( $args );
		}

		/**
		 * Displays a checkbox for a settings field
		 *
		 * @param array $args settings field args
		 */
		function callback_checkbox( $args ) {

			$value = esc_attr( $this->get_option( $args['id'], $args['section'], $args['default'] ) );

			$html = '<fieldset>';
			$html .= sprintf( '<label for="wpuf-%1$s[%2$s]">', $args['section'], $args['id'] );
			$html .= sprintf( '<input type="hidden" name="%1$s[%2$s]" value="off" />', $args['section'], $args['id'] );
			$html .= sprintf( '<input type="checkbox" class="checkbox" id="wpuf-%1$s[%2$s]" name="%1$s[%2$s]" value="on" %3$s />', $args['section'], $args['id'], checked( $value, 'on', false ) );
			$html .= sprintf( '%1$s</label>', $args['desc'] );
			$html .= '</fieldset>';

			echo $html;
		}


		/**
		 * Displays a multicheckbox a settings field
		 *
		 * @param array $args settings field args
		 */
		function callback_radio( $args ) {

			$value = $this->get_option( $args['id'], $args['section'], $args['default'] );

			if ( ! isset( $args['options'][ $value ] ) ) {
				$value = $args['default'];
			}


			$inline       = isset( $args['inline'] ) ? intval( $args['inline'] ) : 0;
			$inline_class = ( $inline ) ? 'cbxgooglemap-settings-label-radio-inline' : '';
			$br_html      = ( ! $inline ) ? '<br/>' : '';
			$last_element = array_key_last( $args['options'] );

			$html = '<fieldset>';

			foreach ( $args['options'] as $key => $label ) {
				$last_class = ( $key === $last_element ) ? 'label-margin-none' : '';

				$html .= sprintf( '<label class="cbxgooglemap-settings-label cbxgooglemap-settings-label-radio %4$s %5$s" for="wpuf-%1$s[%2$s][%3$s]">', $args['section'], $args['id'], $key, $inline_class, $last_class );
				$html .= sprintf( '<input type="radio" class="radio" id="wpuf-%1$s[%2$s][%3$s]" name="%1$s[%2$s]" value="%3$s" %4$s />', $args['section'], $args['id'], $key, checked( $value, $key, false ) );
				$html .= sprintf( '%1$s</label>' . $br_html, $label );
			}

			$html .= $this->get_field_description( $args );
			$html .= '</fieldset>';

			echo $html;
		}

		/**
		 * Displays a selectbox for a settings field
		 *
		 * @param array $args settings field args
		 */
		function callback_select( $args ) {

			$value = esc_attr( $this->get_option( $args['id'], $args['section'], $args['default'] ) );
			$size  = isset( $args['size'] ) && ! is_null( $args['size'] ) ? $args['size'] : 'regular selecttwo-select';
			$html  = sprintf( '<select class="%1$s" name="%2$s[%3$s]" id="%2$s[%3$s]">', $size, $args['section'], $args['id'] );

			foreach ( $args['options'] as $key => $label ) {
				$html .= sprintf( '<option value="%s"%s>%s</option>', $key, selected( $value, $key, false ), $label );
			}

			$html .= sprintf( '</select>' );
			$html .= $this->get_field_description( $args );

			echo $html;
		}

		/**
		 * Displays a multi-selectbox for a settings field
		 *
		 * @param array $args settings field args
		 */
		function callback_multiselect( $args ) {


			$value = $this->get_option( $args['id'], $args['section'], $args['default'] );

			if ( ! is_array( $value ) ) {
				$value = [];
			}

			$size = isset( $args['size'] ) && ! is_null( $args['size'] ) ? $args['size'] : 'regular selecttwo-select';

			$html = sprintf( '<input type="hidden" name="%1$s[%2$s][]" value="" />', $args['section'], $args['id'] );
			$html .= sprintf( '<select multiple class="%1$s" name="%2$s[%3$s][]" id="%2$s[%3$s]" style="min-width: 150px !important;"  placeholder="%4$s" data-placeholder="%4$s">', $size, $args['section'], $args['id'], $args['placeholder'] );


			if ( isset( $args['optgroup'] ) && $args['optgroup'] ) {
				foreach ( $args['options'] as $opt_grouplabel => $dataOpt ) {


					if ( ! is_array( $dataOpt ) ) {
						$dataOpt = [];
					}
					$data      = isset( $dataOpt['data'] ) ? $dataOpt['data'] : [];
					$opt_label = isset( $dataOpt['label'] ) ? esc_attr( $dataOpt['label'] ) : ucfirst( $opt_grouplabel );

					if ( ! is_array( $data ) ) {
						$data = [];
					}

					$html .= '<optgroup label="' . $opt_label . '">';


					foreach ( $data as $key => $val ) {
						$selected = in_array( $key, $value ) ? ' selected="selected" ' : '';
						$html     .= sprintf( '<option value="%s" ' . $selected . '>%s</option>', $key, $val );
					}
					$html .= '<optgroup>';
				}
			} else {
				foreach ( $args['options'] as $key => $val ) {
					$selected = in_array( $key, $value ) ? ' selected="selected" ' : '';
					$html     .= sprintf( '<option value="%s" ' . $selected . '>%s</option>', $key, $val );
				}
			}

			$html .= sprintf( '</select>' );
			$html .= $this->get_field_description( $args );

			echo $html;
		}

		/**
		 * Displays a multicheckbox a settings field
		 *
		 * @param array $args settings field args
		 */
		/* function callback_multicheck( $args ) {

			 $value = $this->get_option( $args['id'], $args['section'], $args['default'] );
			 $html  = '<fieldset>';

			 foreach ( $args['options'] as $key => $label ) {
				 $checked = isset( $value[$key] ) ? $value[$key] : '0';
				 $html    .= sprintf( '<label for="wpuf-%1$s[%2$s][%3$s]">', $args['section'], $args['id'], $key );
				 $html 	 .= sprintf('<input type="hidden" name="%1$s[%2$s][]" value="" />', $args['section'], $args['id']);
				 $html    .= sprintf( '<input type="checkbox" class="checkbox" id="wpuf-%1$s[%2$s][%3$s]" name="%1$s[%2$s][%3$s]" value="%3$s" %4$s />', $args['section'], $args['id'], $key, checked( $checked, $key, false ) );
				 $html    .= sprintf( '%1$s</label><br>',  $label );
			 }

			 $html .= $this->get_field_description( $args );
			 $html .= '</fieldset>';

			 echo $html;
		 }*/

		/**
		 * Displays a multicheckbox settings field
		 *
		 * @param array $args settings field args
		 */
		function callback_multicheck( $args ) {

			$value = $this->get_option( $args['id'], $args['section'], $args['default'] );
			if ( ! is_array( $value ) ) {
				$value = [];
			}

			$html = '<fieldset class="multicheck_fields">';
			foreach ( $args['options'] as $key => $label ) {

				//$checked = isset($value[$key]) ? $value[$key] : '0';
				$checked = in_array( $key, $value ) ? ' checked="checked" ' : '';

				$html .= sprintf( '<p class="multicheck_field"><!--<span class="multicheck_field_handle"><i class="dashicons dashicons-move"></i></span>--><label for="wpuf-%1$s[%2$s][%3$s]">', $args['section'], $args['id'], $key );
				$html .= sprintf( '<input type="hidden" name="%1$s[%2$s][]" value="" />', $args['section'], $args['id'] );
				$html .= sprintf( '<input type="checkbox" class="checkbox" id="wpuf-%1$s[%2$s][%3$s]" name="%1$s[%2$s][%3$s]" value="%3$s" %4$s />', $args['section'], $args['id'], $key, $checked );
				$html .= sprintf( '%1$s</label></p>', $label );
			}
			$html .= $this->get_field_description( $args );
			$html .= '</fieldset>';

			echo $html;
		}


		/**
		 * Displays a info field
		 *
		 * @param array $args settings field args
		 */
		function callback_info( $args ) {
			$html = $args['desc'];
			echo $html;
		}

		/**
		 * Displays a textarea for a settings field
		 *
		 * @param array $args settings field args
		 */
		function callback_textarea( $args ) {

			$value = esc_textarea( $this->get_option( $args['id'], $args['section'], $args['default'] ) );
			$size  = isset( $args['size'] ) && ! is_null( $args['size'] ) ? $args['size'] : 'regular';

			$html = sprintf( '<textarea rows="5" cols="55" class="%1$s-text" id="%2$s[%3$s]" name="%2$s[%3$s]">%4$s</textarea>', $size, $args['section'], $args['id'], $value );
			$html .= $this->get_field_description( $args );

			echo $html;
		}

		/**
		 * Displays a textarea for a settings field
		 *
		 * @param array $args settings field args
		 *
		 * @return string
		 */
		function callback_html( $args ) {
			echo $this->get_field_description( $args );
		}

		/**
		 * Displays a rich text textarea for a settings field
		 *
		 * @param array $args settings field args
		 */
		function callback_wysiwyg( $args ) {

			$value = $this->get_option( $args['id'], $args['section'], $args['default'] );
			$size  = isset( $args['size'] ) && ! is_null( $args['size'] ) ? $args['size'] : '500px';

			echo '<div style="max-width: ' . $size . ';">';

			$editor_settings = [
				'teeny'         => true,
				'textarea_name' => $args['section'] . '[' . $args['id'] . ']',
				'textarea_rows' => 10
			];

			if ( isset( $args['options'] ) && is_array( $args['options'] ) ) {
				$editor_settings = array_merge( $editor_settings, $args['options'] );
			}

			wp_editor( $value, $args['section'] . '-' . $args['id'], $editor_settings );

			echo '</div>';

			echo $this->get_field_description( $args );
		}

		/**
		 * Displays a file upload field for a settings field
		 *
		 * @param array $args settings field args
		 */
		function callback_file( $args ) {

			$value = esc_attr( $this->get_option( $args['id'], $args['section'], $args['default'] ) );
			$size  = isset( $args['size'] ) && ! is_null( $args['size'] ) ? $args['size'] : 'regular';
			$id    = $args['section'] . '[' . $args['id'] . ']';
			$label = isset( $args['options']['button_label'] ) ? $args['options']['button_label'] : esc_html__( 'Choose File', 'cbxgooglemap' );

			$html = sprintf( '<div class="cbxgooglemapmeta_input_file_wrap"><input type="text" class="%1$s-text wpsa-url" id="%2$s[%3$s]" name="%2$s[%3$s]" value="%4$s"/>', $size, $args['section'], $args['id'], $value );

			$icon_extra_class  = '';
			$marker_icon       = '';
			$trash_extra_class = '';
			if ( $value === '' ) {
				$icon_extra_class  = 'cbxgooglemapmeta_marker_hide';
				$file_picked_class = 'cbxgooglemapmeta_left_space';
				$trash_extra_class = 'cbxgooglemapmeta_trash_hide';
			} else {
				$marker_icon       = ' background-image: url(\'' . $value . '\') ;';
				$file_picked_class = 'cbxgooglemapmeta_filepicked';
			}
			$html .= '<span style="' . $marker_icon . '" class="cbxgooglemapmeta_marker_preview ' . esc_attr( $icon_extra_class ) . '"></span>';

			$html .= '<input type="button" class="button cbxgooglemapmeta_filepicker_btn wpsa-browse ' . $file_picked_class . '" value="' . $label . '" />';
			$html .= '<span class="cbxgooglemapmeta_trash dashicons dashicons-no-alt ' . esc_attr( $trash_extra_class ) . '"></span>';
			$html .= $this->get_field_description( $args );

			echo $html;
		}

		/**
		 * Displays a password field for a settings field
		 *
		 * @param array $args settings field args
		 */
		function callback_password( $args ) {

			$value = esc_attr( $this->get_option( $args['id'], $args['section'], $args['default'] ) );
			$size  = isset( $args['size'] ) && ! is_null( $args['size'] ) ? $args['size'] : 'regular';

			$html = sprintf( '<input type="password" class="%1$s-text" id="%2$s[%3$s]" name="%2$s[%3$s]" value="%4$s"/>', $size, $args['section'], $args['id'], $value );
			$html .= $this->get_field_description( $args );

			echo $html;
		}

		/**
		 * Displays a color picker field for a settings field
		 *
		 * @param array $args settings field args
		 */
		function callback_color( $args ) {

			$value = esc_attr( $this->get_option( $args['id'], $args['section'], $args['default'] ) );
			$size  = isset( $args['size'] ) && ! is_null( $args['size'] ) ? $args['size'] : 'regular';

			$html = sprintf( '<input type="text" class="%1$s-text wp-color-picker-field" id="%2$s[%3$s]" name="%2$s[%3$s]" value="%4$s" data-default-color="%5$s" />', $size, $args['section'], $args['id'], $value, $args['default'] );
			$html .= $this->get_field_description( $args );

			echo $html;
		}

		/**
		 * Displays a textarea for a settings field
		 *
		 * @param array $args settings field args
		 */
		function callback_shortcode( $args ) {
			$value     = $args['default'];
			$value_esc = esc_textarea( $value );
			$size      = isset( $args['size'] ) && ! is_null( $args['size'] ) ? $args['size'] : 'regular';
			$class     = isset( $args['class'] ) && ! is_null( $args['class'] ) ? $args['class'] : '';

			$required = isset( $args['required'] ) ? 'required' : '';


			$html = sprintf( '<textarea readonly rows="5" cols="55" class="%1$s-text %6$s" id="%2$s[%3$s]" name="%2$s[%3$s]" %5$s>%4$s</textarea>',
				$size,
				$args['section'],
				$args['id'],
				$value_esc,
				$required, $class );

			$html .= '<a data-target-cp="#' . $args['section'] . '\\[' . $args['id'] . '\\]' . '" class="shortcode_demo_btn" href="#">Click to copy shortcode</a>';
			$html .= $this->get_field_description( $args );


			$html .= '<div class="shortcode_demo_wrap">' . do_shortcode( $value ) . '</div>';

			echo $html;
		}

		/**
		 * Sanitize callback for Settings API
		 */
		function sanitize_options( $options ) {
			if ( ! is_array( $options ) ) {
				$options = [];
			}

			foreach ( $options as $option_slug => $option_value ) {
				$sanitize_callback = $this->get_sanitize_callback( $option_slug );

				// If callback is set, call it
				if ( $sanitize_callback ) {
					$options[ $option_slug ] = call_user_func( $sanitize_callback, $option_value );
					continue;
				}
			}

			return $options;
		}

		/**
		 * Get sanitization callback for given option slug
		 *
		 * @param string $slug option slug
		 *
		 * @return mixed string or bool false
		 */
		function get_sanitize_callback( $slug = '' ) {
			if ( empty( $slug ) ) {
				return false;
			}

			// Iterate over registered fields and see if we can find proper callback
			foreach ( $this->settings_fields as $section => $options ) {
				foreach ( $options as $option ) {
					if ( $option['name'] != $slug ) {
						continue;
					}

					if ( $option['type'] == 'multiselect' || $option['type'] == 'multicheck' ) {
						$option['sanitize_callback'] = [ $this, 'sanitize_multi_select_check' ];
					}

					// Return the callback name
					return isset( $option['sanitize_callback'] ) && is_callable( $option['sanitize_callback'] ) ? $option['sanitize_callback'] : false;
				}
			}

			return false;
		}

		/**
		 * Remove empty values from multi select fields (multi select and multi checkbox)
		 *
		 * @param $option_value
		 *
		 * @return array
		 */
		public function sanitize_multi_select_check( $option_value ) {
			if ( is_array( $option_value ) ) {
				return array_filter( $option_value );
			}

			return $option_value;
		}

		/**
		 * Get the value of a settings field
		 *
		 * @param string $option settings field name
		 * @param string $section the section name this field belongs to
		 * @param string $default default text if it's not found
		 *
		 * @return string
		 */
		function get_option( $option, $section, $default = '' ) {

			$options = get_option( $section );

			if ( isset( $options[ $option ] ) ) {
				return $options[ $option ];
			}

			return $default;
		}

		/**
		 * Get value from option data if field set, returns default if not set
		 *
		 * @param        $option
		 * @param array $section
		 * @param string $default
		 *
		 * @return mixed|string
		 */
		function get_field( $option, $section = [], $default = '' ) {
			if ( isset( $section[ $option ] ) ) {
				return $section[ $option ];
			}

			return $default;
		}//end get_field

		/**
		 * Show navigations as tab
		 *
		 * Shows all the settings section labels as tab
		 */
		function show_navigation() {
			$html = '<h2 class="nav-tab-wrapper">';

			$i = 0;
			foreach ( $this->settings_sections as $tab ) {
				$extra_tab_class = ( $i === 0 ) ? 'nav-tab-active' : '';
				$html            .= sprintf( '<a data-tabid="' . $tab['id'] . '" href="#%1$s" class="nav-tab %3$s" id="%1$s-tab">%2$s</a>', $tab['id'], $tab['title'], $extra_tab_class );
				$i ++;
			}

			$html .= '</h2>';

			echo $html;
		}

		/**
		 * Show the section settings forms
		 *
		 * This function displays every sections in a different form
		 */
		function show_forms() {
			?>
            <div class="metabox-holder">
				<?php
				$i = 0;
				foreach ( $this->settings_sections as $form ) {
					$display_style = ( $i === 0 ) ? '' : 'display: none;';
					?>
                    <div id="<?php echo $form['id']; ?>" class="cbxgooglemap_group"
                         style="<?php echo $display_style; ?>">
                        <form method="post" action="options.php">
							<?php
							do_action( 'cbxgooglemap_form_top_' . $form['id'], $form );
							settings_fields( $form['id'] );
							do_settings_sections( $form['id'] );
							do_action( 'cbxgooglemap_form_bottom_' . $form['id'], $form );
							?>
                            <div style="padding-left: 10px">
								<?php submit_button( esc_html__( 'Save Settings', 'cbxgooglemap' ), 'primary submit_cbxgooglemap', 'submit', true, [ 'id' => 'submit_' . esc_attr( $form['id'] ) ] ); ?>
                            </div>
                        </form>
                    </div>
					<?php
					$i ++;
				} ?>
            </div>
			<?php
		}
	}//end class CBXGooglemapSettings
endif;