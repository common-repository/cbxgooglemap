<?php

/**
 * The admin-specific functionality of the plugin.
 *
 * @link       https://codeboxr.com
 * @since      1.0.0
 *
 * @package    Cbxgooglemap
 * @subpackage Cbxgooglemap/admin
 */

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    Cbxgooglemap
 * @subpackage Cbxgooglemap/admin
 * @author     Codeboxr <info@codeboxr.com>
 */
class CBXGoogleMap_Admin {

	/**
	 * The ID of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string $plugin_name The ID of this plugin.
	 */
	private $plugin_name;

	/**
	 * The version of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string $version The current version of this plugin.
	 */
	private $version;

	/**
	 * The ID of this plugin.
	 *
	 * @since    1.1.12
	 * @access   private
	 * @var      string $settings The ID of this plugin.
	 */
	public $settings;

	/**
	 * Initialize the class and set its properties.
	 *
	 *
	 * @since    1.0.0
	 */
	public function __construct() {
		$this->plugin_name = CBXGOOGLEMAP_PLUGIN_NAME;
		$this->version     = CBXGOOGLEMAP_PLUGIN_VERSION;

		//get instance of setting api
		$this->settings = new CBXGooglemapSettings();
	}//end of construtor

	/**
	 * Initialize setting
	 */
	public function setting_init() {
		//set the settings
		$this->settings->set_sections( $this->get_settings_sections() );
		$this->settings->set_fields( $this->get_settings_fields() );
		//initialize settings
		$this->settings->admin_init();
	}//end setting_init

	/**
	 * Global Setting Sections
	 *
	 *
	 * @return array
	 */
	public function get_settings_sections() {
		return apply_filters(
			'cbxgooglemap_setting_sections', [
				[
					'id'    => 'cbxgooglemap_general',
					'title' => esc_html__( 'Default Config', 'cbxgooglemap' )
				],
				[
					'id'    => 'cbxgooglemap_demo',
					'title' => esc_html__( 'Demo & Shortcodes', 'cbxgooglemap' )
				]
			]
		);
	}//end get_settings_sections

	/**
	 * Returns all the settings fields
	 *
	 * @return array settings fields
	 */
	public function get_settings_fields() {
		$all_post_types   = CBXGooglemapHelper::post_types();
		$general_settings = get_option( 'cbxgooglemap_general', [] );

		$settings_builtin_fields = [
			'cbxgooglemap_general' => [
				'mapsource'    => [
					'name'              => 'mapsource',
					'label'             => esc_html__( 'Map Source', 'cbxgooglemap' ),
					'type'              => 'radio',
					'default'           => '1',
					'options'           => [
						'1' => esc_html__( 'Google Map', 'cbxgooglemap' ),
						'0' => esc_html__( 'Openstreet Map(leafletjs)', 'cbxgooglemap' )
					],
					'inline'            => true,
					'sanitize_callback' => 'absint'
				],
				'apikey'       => [
					'name'  => 'apikey',
					'label' => esc_html__( 'Api Key', 'cbxgooglemap' ),
					'desc'  => esc_html__( 'Google map api key', 'cbxgooglemap' ),
					'type'  => 'text'

				],
				'maptype'      => [
					'name'              => 'maptype',
					'label'             => esc_html__( 'Map Type', 'cbxgooglemap' ),
					'type'              => 'select',
					'default'           => 'roadmap',
					'options'           => [
						'roadmap'   => esc_html__( 'Road Map', 'cbxgooglemap' ),
						'satellite' => esc_html__( 'Satellite Map', 'cbxgooglemap' ),
						'hybrid'    => esc_html__( 'Hybrid Map', 'cbxgooglemap' ),
						'terrain'   => esc_html__( 'Terrain Map', 'cbxgooglemap' ),
					],
					'desc'              => esc_html__( 'Google Map only', 'cbxgooglemap' ),
					'sanitize_callback' => 'sanitize_text_field'
				],
				'zoom'         => [
					'name'              => 'zoom',
					'label'             => esc_html__( 'Zoom Level', 'cbxgooglemap' ),
					'desc'              => esc_html__( 'Default Zoom Level', 'cbxgooglemap' ),
					'type'              => 'number',
					'step'              => 1,
					'default'           => 8,
					'sanitize_callback' => 'absint'

				],
				'width'        => [
					'name'    => 'with',
					'label'   => esc_html__( 'Width', 'cbxgooglemap' ),
					'desc'    => esc_html__( 'Default is 100% to make the map responsive, if you want any fixed width then don\'t put, % or just put numeric value, don\'t px with numeric value', 'cbxgooglemap' ),
					'type'    => 'text',
					'default' => '100%',

				],
				'height'       => [
					'name'              => 'height',
					'label'             => esc_html__( 'Height', 'cbxgooglemap' ),
					'desc'              => esc_html__( 'Default height 300 as px, put any numeric value.', 'cbxgooglemap' ),
					'type'              => 'number',
					'default'           => '300',
					'sanitize_callback' => 'floatval'
				],
				'scrollwheel'  => [
					'name'              => 'scrollwheel',
					'label'             => esc_html__( 'Mouse Scroll Wheel', 'cbxgooglemap' ),
					'desc'              => esc_html__( 'Enable/disable mouse scroll whell', 'cbxgooglemap' ),
					'type'              => 'radio',
					'default'           => '1',
					'options'           => [
						'1' => esc_html__( 'Enable', 'cbxgooglemap' ),
						'0' => esc_html__( 'Disable', 'cbxgooglemap' ),
					],
					'inline'            => true,
					'sanitize_callback' => 'absint'
				],
				'showinfo'     => [
					'name'              => 'showinfo',
					'label'             => esc_html__( 'Show Info window', 'cbxgooglemap' ),
					'desc'              => esc_html__( 'Show information on click of marker', 'cbxgooglemap' ),
					'type'              => 'radio',
					'default'           => '1',
					'desc_tip'          => true,
					'options'           => [
						'1' => esc_html__( 'Enable', 'cbxgooglemap' ),
						'0' => esc_html__( 'Disable', 'cbxgooglemap' ),
					],
					'inline'            => true,
					'sanitize_callback' => 'absint'
				],
				'infow_open'   => [
					'name'              => 'infow_open',
					'label'             => esc_html__( 'Info/Popup Window', 'cbxgooglemap' ),
					'type'              => 'radio',
					'default'           => '1',
					'desc_tip'          => true,
					'options'           => [
						'1' => esc_html__( 'Open(Default)', 'cbxgooglemap' ),
						'0' => esc_html__( 'On Click', 'cbxgooglemap' ),
					],
					'inline'            => true,
					'sanitize_callback' => 'absint'
				],
				'mapicon'      => [
					'name'     => 'mapicon',
					'label'    => esc_html__( 'Map Icon', 'cbxgooglemap' ),
					'type'     => 'file',
					'default'  => '',
					'desc_tip' => true,

				],
				'hide_leaflet' => [
					'name'              => 'hide_leaflet',
					'label'             => esc_html__( 'Hide Leaflet Branding', 'cbxgooglemap' ),
					'type'              => 'radio',
					'default'           => '0',
					'options'           => [
						'1' => esc_html__( 'Hide', 'cbxgooglemap' ),
						'0' => esc_html__( 'Show/Default', 'cbxgooglemap' ),
					],
					'inline'            => true,
					'sanitize_callback' => 'absint'
				],

			],
			'cbxgooglemap_demo'    => [
				'shortcode_demo' => [
					'name'              => 'shortcode_demo',
					'label'             => esc_html__( 'Shortcode & Demo', 'cbxgooglemap' ),
					'desc'              => esc_html__( 'Shortcode and demo based on default setting, please save once to check change.', 'cbxgooglemap' ),
					'type'              => 'shortcode',
					'class'             => 'cbcurrencyconverter_demo_copy',
					'default'           => CBXGooglemapHelper::shortcode_builder( $general_settings ),
					'sanitize_callback' => 'sanitize_text_field'
				]

			]
		];


		$settings_fields = []; //final setting array that will be passed to different filters
		$sections        = $this->get_settings_sections();

		foreach ( $sections as $section ) {
			if ( ! isset( $settings_builtin_fields[ $section['id'] ] ) ) {
				$settings_builtin_fields[ $section['id'] ] = [];
			}
		}


		foreach ( $sections as $section ) {
			$settings_fields[ $section['id'] ] = apply_filters( 'cbxgooglemap_global_' . $section['id'] . '_fields', $settings_builtin_fields[ $section['id'] ] );
		}

		return apply_filters( 'cbxgooglemap_global_fields', $settings_fields );
	}//end get_settings_fields

	/**
	 * Register Custom Post Type cbxgooglemap
	 *
	 * @since    3.7.0
	 */
	public function create_post_type() {
		CBXGooglemapHelper::create_googlemap_post_type();

		// Check the option we set on activation.
		if ( get_option( 'cbxgooglemap_flush_rewrite_rules' ) == 'true' ) {
			flush_rewrite_rules();
			delete_option( 'cbxgooglemap_flush_rewrite_rules' );
		}
	}//end create_post_type


	/**
	 * Show menu page
	 */
	public function menu_pages() {
		//setting page
		$setting_page_hook = add_submenu_page( 'edit.php?post_type=cbxgooglemap', esc_html__( 'CBX WP Map Settings', 'cbxgooglemap' ), esc_html__( 'Setting', 'cbxgooglemap' ), 'manage_options', 'cbxgooglemapsettings', [
			$this,
			'menu_page_settings'
		] );
		$doc_page_hook     = add_submenu_page( 'edit.php?post_type=cbxgooglemap', esc_html__( 'Helps & Updates', 'cbxgooglemap' ), esc_html__( 'Helps & Updates', 'cbxgooglemap' ), 'manage_options', 'cbxgooglemap-help-support', [
			$this,
			'menu_page_docs'
		] );
	}//end menu_pages

	/**
	 * Show cbxeventz Setting page
	 */
	public function menu_page_settings() {
		echo cbxgooglemap_get_template_html( 'admin/settings-display.php', [ 'ref' => $this ] );
	}//end menu_page_settings

	/**
	 * Render the help & support page for this plugin.
	 *
	 * @since    1.0.0
	 */
	public function menu_page_docs() {
		echo cbxgooglemap_get_template_html( 'admin/dashboard.php' );
	}//end method menu_page_docs

	/**
	 * Add metabox for custom post type cbxfeedbackform && cbxfeedbackbtn
	 *
	 * @since    1.0.0
	 */
	public function add_meta_boxes() {
		add_meta_box( 'cbxgooglemap_metabox', esc_html__( 'Map Parameter', 'cbxgooglemap' ), [
			$this,
			'parameter_metabox_display'
		], 'cbxgooglemap', 'normal', 'high' );
		add_meta_box( 'cbxgooglemap_shortcode', esc_html__( 'Shortcode', 'cbxgooglemap' ), [
			$this,
			'shortcode_metabox_display'
		], 'cbxgooglemap', 'side', 'low' );
	}//end add_meta_boxes

	/**
	 * Show Shortcode display metabox
	 *
	 * @param $post
	 */
	public function shortcode_metabox_display( $post ) {
		if ( isset( $post->ID ) && $post->ID > 0 ) {
			$post_id   = $post->ID;
			$post_type = $post->post_type;

			echo cbxgooglemap_get_template_html( 'admin/metabox_shortcode.php', [
				'post_id'   => $post_id,
				'post_type' => $post_type
			] );
		}
	}//end shortcode_metabox_display

	/**
	 * Render metabox
	 *
	 * @param $post
	 *
	 * since v1.0.0
	 */
	public function parameter_metabox_display( $post ) {
		global $post;
		$post_type = $post->post_type;


		$meta_fields = CBXGooglemapHelper::cbxgooglemap_meta_fields();

		$combined_field = '_' . $post_type . '_combined'; //field name for non sortable fields
		$meta_prefix    = '_' . $post_type;               //field prefix for sortable fields


		CBXGooglemapHelper::render_meta_fields( $post, $meta_fields, $combined_field, $meta_prefix, true ); //
	}//end parameter_metabox_display

	/**
	 * Save meta box for cbxeventz
	 *
	 * @param $post_id
	 */
	public function metabox_save( $post_id, $post, $update ) {

		$post_type = $post->post_type;

		// Check if our nonce is set.
		if ( ! isset( $_POST[ 'cbxmetahelper_' . $post_type . '_meta_box_nonce' ] ) ) {
			return;
		}

		// Verify that the nonce is valid.
		if ( ! wp_verify_nonce( $_POST[ 'cbxmetahelper_' . $post_type . '_meta_box_nonce' ], 'cbxmetahelper_' . $post_type . '_meta_box' ) ) {
			return;
		}

		// If this is an autosave, our form has not been submitted, so we don't want to do anything.
		if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
			return $post_id;
		}

		// Check the user's permissions.
		if ( isset( $_POST['post_type'] ) && $post_type == $_POST['post_type'] ) {

			if ( ! current_user_can( 'edit_post', $post_id ) ) {
				return;
			}
		}

		if ( method_exists( 'CBXGooglemapHelper', $post_type . '_meta_fields' ) ) {
			$meta_fields = call_user_func( 'CBXGooglemapHelper::' . $post_type . '_meta_fields', [
				$post_id,
				$post_type
			] );
		} else {
			return;
		}


		$combined_field = '_' . $post_type . '_combined'; //field name for non sortable fields
		$meta_prefix    = '_' . $post_type;               //field prefix for sortable fields

		$meta_combined = get_post_meta( $post_id, $combined_field, true ); //meta fields not sortable

		$meta_combined = ! is_array( $meta_combined ) ? [] : $meta_combined;


		$combined_arr = [];
		foreach ( $meta_fields as $section_id => $fields ) {
			foreach ( $fields as $id => $field ) {

				$field_type = $field['type'];

				$sanitize_callback = isset( $field['sanitize_callback'] ) ? $field['sanitize_callback'] : null;

				if ( isset( $_POST[ $meta_prefix . $id ] ) ) {

					if ( isset( $_POST[ $meta_prefix . $id ] ) ) {
						$updated_value = $_POST[ $meta_prefix . $id ];


						if ( $field_type == 'text' ) {
							$updated_value = wp_strip_all_tags( sanitize_text_field( $updated_value ) );
						} elseif ( $field_type == 'textarea' ) {
							$updated_value = wp_strip_all_tags( sanitize_textarea_field( $updated_value ) );
						} else {
							if ( $sanitize_callback !== null && is_callable( $sanitize_callback ) ) {
								$updated_value = call_user_func( $sanitize_callback, $updated_value );
							}
						}

						$is_sortable = isset( $field['sortable'] ) ? $field['sortable'] : false;

						if ( $is_sortable ) {
							$ret = update_post_meta( $post_id, $meta_prefix . $id, $updated_value ); //update the combined meta
						} else {
							//save as combined meta
							$meta_combined[ $meta_prefix . $id ] = $updated_value;
						}
					}

				}

			}
		}

		update_post_meta( $post_id, $combined_field, $meta_combined ); //update the combined meta

		do_action( 'cbxgooglemap_metabox_save', $post_id );

	}//end metabox_save

	/**
	 * Add or adjust col for cbxgooglemap post type
	 *
	 * @param $cbxpoll_columns
	 *
	 * @return array
	 *
	 */
	public function cbxgooglemap_add_new_columns( $columns ) {
		unset( $columns['date'] );
		unset( $columns['comments'] );

		$columns['lat']       = esc_attr( 'Latitude', 'cbxgooglemap' );
		$columns['lng']       = esc_attr( 'Longitude', 'cbxgooglemap' );
		$columns['zoom']      = esc_attr( 'Zoom', 'cbxgooglemap' );
		$columns['shortcode'] = esc_attr( 'Shortcode', 'cbxgooglemap' );

		return $columns;
	}//end cbxgooglemap_add_new_columns

	/**
	 * Add extra cols information for cbxgooglemap post type
	 *
	 * @param $column_name
	 *
	 *
	 * show data to poll table custom column
	 */
	public function cbxgooglemap_manage_columns( $column_name ) {
		global $wpdb, $post;

		$post_id   = intval( $post->ID );
		$post_type = $post->post_type;

		$combined_field = '_cbxgooglemap_combined'; //field name for non sortable fields
		$meta_prefix    = '_cbxgooglemap';          //field prefix for sortable fields

		$meta_combined = get_post_meta( $post_id, $combined_field, true );

		$lat = get_post_meta( $post_id, $meta_prefix . 'lat', true );
		$lat = ( $lat !== false ) ? $lat : '';

		$lng = get_post_meta( $post_id, $meta_prefix . 'lng', true );
		$lng = ( $lng !== false ) ? $lng : '';

		$zoom = ( isset( $meta_combined[ $meta_prefix . 'zoom' ] ) && intval( $meta_combined[ $meta_prefix . 'zoom' ] ) > 0 ) ? intval( $meta_combined[ $meta_prefix . 'zoom' ] ) : '';


		switch ( $column_name ) {
			case 'shortcode':
				echo '<span id="cbxgooglemapshortcode-' . intval( $post_id ) . '" class="cbxgooglemapshortcode cbxgooglemapshortcode-' . intval( $post_id ) . '">[cbxgooglemap id="' . intval( $post_id ) . '"]</span>';
				echo '<span class="cbxgooglemapshortcodecopytrigger" data-clipboard-text=\'[cbxgooglemap id="' . intval( $post_id ) . '"]\' data-success="' . esc_html__( 'Copied', 'cbxgooglemap' ) . '" title="' . esc_attr__( 'Click to copy', 'cbxgooglemap' ) . '"><img class="cbxgooglemapshortcode-copy-image" src="' . CBXGOOGLEMAP_ROOT_URL . 'assets/img/copy.svg" alt="' . esc_attr__( 'CBX GoogleMaps Shortcode Copy', 'cbxgooglemap' ) . '"/></span>';
				break;
			case 'lat':
				echo $lat;
				break;
			case 'lng':
				echo $lng;
				break;
			case 'zoom':
				echo $zoom;
				break;
			default:
				break;
		} // end switch
	}//end cbxgooglemap_manage_columns

	/**
	 * Register the stylesheets for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles( $hook ) {
		$current_page = isset( $_GET['page'] ) ? esc_attr( wp_unslash( $_GET['page'] ) ) : '';
		$version      = $this->version;

		$vendor_url = CBXGOOGLEMAP_ROOT_URL . 'assets/vendors/';
		$css_url    = CBXGOOGLEMAP_ROOT_URL . 'assets/css/';
		$js_url     = CBXGOOGLEMAP_ROOT_URL . 'assets/css/';

		global $post_type, $post;

		$api_key    = esc_attr( $this->settings->get_option( 'apikey', 'cbxgooglemap_general', '' ) );
		$map_source = intval( $this->settings->get_option( 'mapsource', 'cbxgooglemap_general', 1 ) );


		//listing mode
		if ( ( $hook == 'edit.php' ) && $post_type == 'cbxgooglemap' ) {
			wp_register_style( 'cbxgooglemap-admin', $css_url . 'cbxgooglemap-admin.css', [], $version );
			wp_enqueue_style( 'cbxgooglemap-admin' );
		}


		//add new, edit mode
		if ( ( $hook == 'post.php' || $hook == 'post-new.php' ) && $post_type == 'cbxgooglemap' ) {

			if ( $map_source == 0 ) {
				wp_register_style( 'leaflet', '//unpkg.com/leaflet@1.9.2/dist/leaflet.css', [], $version, 'all' );
				wp_register_style( 'leaflet-control-geocoder', '//unpkg.com/leaflet-control-geocoder/dist/Control.Geocoder.css', [], $version, 'all' );
			}

			wp_register_style( 'jquery-ui', $vendor_url . 'ui-lightness/jquery-ui.min.css', [], $version );
			wp_register_style( 'select2', $vendor_url . 'select2/css/select2.min.css', [], $version );
			wp_register_style( 'cbxgooglemap-admin', $css_url . 'cbxgooglemap-admin.css', [
				'select2',
				'wp-color-picker',
				'jquery-ui'
			], $version );

			wp_enqueue_style( 'select2' );
			wp_enqueue_style( 'wp-color-picker' );
			wp_enqueue_style( 'jquery-ui' );

			if ( $map_source == 0 ) {
				wp_enqueue_style( 'leaflet' );
				wp_enqueue_style( 'leaflet-control-geocoder' );
			}

			wp_enqueue_style( 'cbxgooglemap-admin' );
		}

		if ( $current_page == 'cbxgooglemapsettings' ) {

			if ( $map_source == 0 ) {
				wp_register_style( 'leaflet', '//unpkg.com/leaflet@1.9.2/dist/leaflet.css', [], $version, 'all' );
				wp_register_style( 'leaflet-control-geocoder', '//unpkg.com/leaflet-control-geocoder/dist/Control.Geocoder.css', [], $version, 'all' );
			}


			wp_register_style( 'jquery-ui', $vendor_url . 'ui-lightness/jquery-ui.min.css', [], $version );
			wp_register_style( 'select2', $vendor_url . 'select2/css/select2.min.css', [], $version );
			wp_register_style( 'cbxgooglemap-setting', $css_url . 'cbxgooglemap-setting.css', [
				'wp-color-picker',
				'select2',
				'jquery-ui'
			], $version );

			wp_enqueue_style( 'wp-color-picker' );

			wp_enqueue_style( 'select2' );
			wp_enqueue_style( 'jquery-ui' );
			wp_enqueue_style( 'cbxgooglemap-setting' );


			//for demo css
			if ( $map_source == 0 ) {
				wp_register_style( 'cbxgooglemap-public', $css_url . 'cbxgooglemap-public.css', [ 'leaflet' ], $version, 'all' );
				wp_enqueue_style( 'leaflet' );

			} else {
				wp_register_style( 'cbxgooglemap-public', $css_url . 'cbxgooglemap-public.css', [], $version, 'all' );
			}

			wp_enqueue_style( 'cbxgooglemap-public' );
			//end for demo css


		}//end setting

		//apply branding css to necessary pages
		if ( ( $hook == 'post.php' || $hook == 'post-new.php' || $hook == 'edit.php' ) && $post_type == 'cbxgooglemap' || $current_page == 'cbxgooglemapsettings' || $current_page == 'cbxgooglemap-help-support' ) {
			wp_register_style( 'cbxgooglemap-branding', $css_url . 'cbxgooglemap-branding.css', [], $version );
			wp_enqueue_style( 'cbxgooglemap-branding' );
		}
	}//end enqueue_styles

	/**
	 * Register the JavaScript for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts( $hook ) {
		$current_page = isset( $_GET['page'] ) ? esc_attr( wp_unslash( $_GET['page'] ) ) : '';
		$ver          = $this->version;

		$vendor_url = CBXGOOGLEMAP_ROOT_URL . 'assets/vendors/';
		$css_url    = CBXGOOGLEMAP_ROOT_URL . 'assets/css/';
		$js_url     = CBXGOOGLEMAP_ROOT_URL . 'assets/js/';

		$t = true;
		$f = false;


		global $post_type, $post;
		$post_id = isset( $post->ID ) ? intval( $post->ID ) : 0;

		$api_key    = esc_attr( $this->settings->get_option( 'apikey', 'cbxgooglemap_general', '' ) );
		$map_source = intval( $this->settings->get_option( 'mapsource', 'cbxgooglemap_general', 1 ) );

		$default_mapicon = esc_url( $this->settings->get_option( 'mapicon', 'cbxgooglemap_general', '' ) );

		/*if($default_mapicon == ''){
			$default_mapicon = 'https://mt.googleapis.com/vt/icon/name=icons/spotlight/spotlight-poi.png';
		}*/

		/*$zoom_level_default = intval( $this->settings->get_option( 'zoom', 'cbxgooglemap_general', '8' ) );
		if ( $zoom_level_default == 0 ) {
			$zoom_level_default = 8;
		}*/


		//maps listing mode
		if ( $hook == 'edit.php' && $post_type == 'cbxgooglemap' ) {
			wp_register_script( 'cbxgooglemap-listing', $js_url . 'cbxgooglemap-listing.js', [ 'jquery' ], $ver, $t );

			wp_enqueue_script( 'jquery' );
			wp_enqueue_script( 'cbxgooglemap-listing' );
		}

		//add new or edit mode
		if ( ( $hook == 'post.php' || $hook == 'post-new.php' ) && $post_type == 'cbxgooglemap' ) {
			wp_register_script( 'select2', $vendor_url . 'select2/js/select2.full.min.js', [ 'jquery' ], $ver, $t );


			wp_register_script( 'cbxgooglemap-events', $js_url . 'cbxgooglemap-events.js', [], $ver, $t );
			wp_enqueue_script( 'cbxgooglemap-events' );

			$meta_js_dep = [ 'jquery', 'select2' ];

			if ( $map_source == 1 && $api_key != '' ) {
				wp_register_script( 'coregooglemapapi', '//maps.googleapis.com/maps/api/js?key=' . $api_key . '&libraries=places&callback=Function.prototype', [], $ver );
				//wp_register_script( 'locationpicker-jquery', $js_url. 'locationpicker.jquery.js', [ 'coregooglemapapi', 'jquery' ], $ver );

				$meta_js_dep[] = 'coregooglemapapi';
				//$meta_js_dep[] = 'locationpicker-jquery';

				$meta_js_dep[] = 'cbxgooglemap-events';

			} elseif ( $map_source == 0 ) {
				wp_register_script( 'coregooglemapapi', '//unpkg.com/leaflet@1.9.2/dist/leaflet.js', [], $ver );
				//wp_register_script( 'leaflet-control-geocoder', '//unpkg.com/leaflet-control-geocoder/dist/Control.Geocoder.js', [ 'coregooglemapapi' ], $ver );

				$meta_js_dep[] = 'coregooglemapapi';
				//$meta_js_dep[] = 'leaflet-control-geocoder';

				$meta_js_dep[] = 'cbxgooglemap-events';
			}

			$meta_js_dep[] = 'jquery-ui-core';
			$meta_js_dep[] = 'wp-color-picker';

			wp_enqueue_media();

			//wp_enqueue_script( 'jquery' );
			//wp_enqueue_script( 'wp-color-picker' );
			//wp_enqueue_script( 'select2' );

			foreach ( $meta_js_dep as $dep ) {
				wp_enqueue_script( $dep );
			}


			//wp_enqueue_style( 'jquery-ui-core' );

			$main_marker = [];



			wp_register_script( 'cbxgooglemap-meta', $js_url . 'cbxgooglemap-meta.js', $meta_js_dep, $ver, $t );

			$cbxgooglemap_meta_js_vars = apply_filters( 'cbxgooglemap_meta_js_vars',
				[
					'please_select'    => esc_html__( 'Please Select', 'cbxgooglemap' ),
					'upload_title'     => esc_html__( 'Window Title', 'cbxgooglemap' ),
					//'copy_success'    => esc_html__( 'Shortcode copied to clipboard', 'cbxgooglemap' ),
					//'copy_fail'       => esc_html__( 'Oops, unable to copy', 'cbxgooglemap' ),
					'search_address'   => esc_html__( 'Search Address', 'cbxgooglemap' ),
					'icon_url_default' => $default_mapicon,
					'api_key'          => $api_key,
					'map_source'       => $map_source
				],
				$post_id );

			wp_localize_script( 'cbxgooglemap-meta', 'cbxgooglemap_meta', $cbxgooglemap_meta_js_vars );


			/*wp_enqueue_script( 'coregooglemapapi' );
			if ( $map_source == 0 ) {
				wp_enqueue_script( 'leaflet-control-geocoder' );
			}*/

			/*if ( $map_source == 1 && $api_key != '' ) {
				wp_enqueue_script( 'locationpicker-jquery' );
			}*/


			wp_enqueue_script( 'cbxgooglemap-meta' );
		}

		if ( $current_page == 'cbxgooglemapsettings' ) {
			wp_register_script( 'select2', $vendor_url . 'select2/js/select2.full.min.js', [ 'jquery' ], $ver, $t );
			wp_register_script( 'cbxgooglemap-setting', $js_url . 'cbxgooglemap-setting.js', [
				'jquery',
				'wp-color-picker',
				'select2'
			], $ver, $t );

			$cbxgooglemap_setting_js_vars = apply_filters( 'cbxgooglemap_setting_js_vars',
				[
					'please_select' => esc_html__( 'Please Select', 'cbxgooglemap' ),
					'upload_title'  => esc_html__( 'Window Title', 'cbxgooglemap' )
				] );
			wp_localize_script( 'cbxgooglemap-setting', 'cbxgooglemap_setting', $cbxgooglemap_setting_js_vars );

			wp_enqueue_media();
			wp_enqueue_script( 'jquery' );
			wp_enqueue_script( 'wp-color-picker' );

			wp_enqueue_script( 'select2' );
			wp_enqueue_script( 'cbxgooglemap-setting' );

			//for demo js
			if ( ( $map_source == 1 && ! empty( $api_key ) ) || $map_source == 0 ) {
				if ( $map_source == 1 ) {
					wp_register_script( 'coregooglemapapi', '//maps.googleapis.com/maps/api/js?key=' . esc_attr( $api_key ) . '&libraries=places&callback=Function.prototype', [], $ver );
					wp_register_script( 'cbxgooglemap-public', $js_url . 'cbxgooglemap-public.js', [
						'jquery',
						'coregooglemapapi'
					], $ver, $t );

					wp_enqueue_script( 'coregooglemapapi' );
				} else {
					wp_register_script( 'coregooglemapapi', '//unpkg.com/leaflet@1.9.2/dist/leaflet.js', [], $ver, true );
					wp_register_script( 'cbxgooglemap-public', $js_url . 'cbxgooglemap-public.js', [
						'jquery',
						'coregooglemapapi',
						//'leaflet-control-geocoder'
					], $ver, $t );

					wp_enqueue_script( 'coregooglemapapi' );
					//wp_enqueue_script( 'leaflet-control-geocoder' );
				}

				wp_enqueue_script( 'cbxgooglemap-public' );
			}//end for demo js

		}//end js for setting page

	}//end enqueue_scripts

	/**
	 * Add settings action link to the plugins page.
	 *
	 * @since    1.0.0
	 */
	public function plugin_listing_setting_link( $links ) {
		return array_merge( [
			'settings' => '<a style="color:#39A96B; font-weight: bold;" target="_blank" href="' . admin_url( 'edit.php?post_type=cbxgooglemap&page=cbxgooglemapsettings' ) . '">' . esc_attr__( 'Settings', 'cbxgooglemap' ) . '</a>'
		], $links );

	}//end plugin_listing_setting_link

	/**
	 * Add Pro product link in plugin listing
	 *
	 * @param $links
	 * @param $file
	 *
	 * @return array
	 */
	public function custom_plugin_row_meta( $links, $file ) {
		if ( strpos( $file, 'cbxgooglemap.php' ) !== false ) {
			$new_links = [
				'free_support' => '<a style="color:#39A96B; font-weight: bold;" href="https://wordpress.org/support/plugin/cbxgooglemap/" target="_blank">' . esc_attr__( 'Free Support', 'cbxgooglemap' ) . '</a>',
				'reviews'      => '<a style="color:#39A96B; font-weight: bold;" href="https://wordpress.org/plugins/cbxgooglemap/#reviews" target="_blank">' . esc_attr__( 'Reviews', 'cbxgooglemap' ) . '</a>',
				'pro'          => '<a style="color:#39A96B; font-weight: bold;" href="https://codeboxr.com/product/cbx-google-map-for-wordpress/" target="_blank">' . esc_attr__( 'Try Pro', 'cbxgooglemap' ) . '</a>',
				'pro_support'  => '<a style="color:#39A96B; font-weight: bold;" href="https://codeboxr.com/contact-us/" target="_blank">' . esc_attr__( 'Pro Support', 'cbxgooglemap' ) . '</a>',

			];

			$links = array_merge( $links, $new_links );
		}

		return $links;
	}//end custom_plugin_row_meta


	/**
	 * Init all gutenberg blocks
	 */
	public function gutenberg_blocks() {
		if ( ! function_exists( 'register_block_type' ) ) {
			return;
		}

		$settings         = new CBXGooglemapSettings();
		$general_settings = get_option( 'cbxgooglemap_general', [] );

		//default field values
		$zoom_default = $settings->get_field( 'zoom', $general_settings, '8' );
		if ( $zoom_default == 0 ) {
			$zoom_default = 8;
		}


		$width_default = $settings->get_field( 'width', $general_settings, '100%' );
		if ( $width_default == '' || $width_default == 0 ) {
			$width_default = '100%';
		}

		$height_default = intval( $settings->get_field( 'height', $general_settings, '300' ) );
		if ( $height_default == 0 ) {
			$height_default = 300;
		}


		$scrollwheel_default = intval( $settings->get_field( 'scrollwheel', $general_settings, 1 ) );
		$showinfo_default    = intval( $settings->get_field( 'showinfo', $general_settings, 1 ) );
		$infow_open_default  = intval( $settings->get_field( 'infow_open', $general_settings, 1 ) );
		$maptype_default     = esc_attr( $settings->get_field( 'maptype', $general_settings, 'roadmap' ) );

		$query = get_posts( [
			'post_type'      => 'cbxgooglemap',
			'orderby'        => 'date',
			'posts_per_page' => - 1,
			'post_status'    => 'publish'
		] );

		$googleMap_posts = [];

		$googleMap_posts[] = [
			'label' => esc_html__( 'Select Map', 'cbxgooglemap' ),
			'value' => '0'
		];

		foreach ( $query as $key => $data ) {
			$googleMap_posts[] = [
				'label' => esc_html( $data->post_title ),
				'value' => intval( $data->ID )
			];
		}


		wp_register_script( 'cbxgooglemap-block', plugin_dir_url( __FILE__ ) . '../assets/js/cbxgooglemap-block.js', [
			'wp-blocks',
			'wp-element',
			'wp-components',
			'wp-editor',
			//'jquery',
			//'codeboxrflexiblecountdown-public'
		], filemtime( plugin_dir_path( __FILE__ ) . '../assets/js/cbxgooglemap-block.js' ) );

		wp_register_style( 'cbxgooglemap-block', plugin_dir_url( __FILE__ ) . '../assets/css/cbxgooglemap-block.css', [], filemtime( plugin_dir_path( __FILE__ ) . '../assets/css/cbxgooglemap-block.css' ) );

		$js_vars = apply_filters( 'cbxgooglemap_block_js_vars',
			[
				'block_title'      => esc_html__( 'CBX Maps', 'cbxgooglemap' ),
				'block_category'   => 'codeboxr',
				'block_icon'       => 'universal-access-alt',
				'general_settings' => [
					'title'                 => esc_html__( 'CBX Map Settings', 'cbxgooglemap' ),
					'id'                    => esc_html__( 'Select Map', 'cbxgooglemap' ),
					'id_default'            => '',
					'id_options'            => $googleMap_posts,
					'id_note'               => esc_html__( 'Choose saved map or create from custom attributes below', 'cbxgooglemap' ),
					'custom_attribute_note' => esc_html__( 'Custom Map Attributes', 'cbxgooglemap' ),
					'maptype'               => esc_html__( 'Map Type(Google Map Only)', 'cbxgooglemap' ),
					'maptype_options'       => CBXGooglemapHelper::maptype_block_options(),
					'lat'                   => esc_html__( 'Latitude', 'cbxgooglemap' ),
					'lng'                   => esc_html__( 'Longitude', 'cbxgooglemap' ),
					'width'                 => esc_html__( 'Width(Numeric, % allowed)', 'cbxgooglemap' ),
					'height'                => esc_html__( 'Height(only Numeric)', 'cbxgooglemap' ),
					'zoom'                  => esc_html__( 'zoom', 'cbxgooglemap' ),
					'scrollwheel'           => esc_html__( 'Mouse Scroll Wheel', 'cbxgooglemap' ),
					'showinfo'              => esc_html__( 'Show Popup', 'cbxgooglemap' ),
					'infow_open'            => esc_html__( 'Popup Auto Display ', 'cbxgooglemap' ),
					'heading'               => esc_html__( 'Popup Heading', 'cbxgooglemap' ),
					'address'               => esc_html__( 'Location Address', 'cbxgooglemap' ),
					'website'               => esc_html__( 'Website url', 'cbxgooglemap' ),
					'mapicon'               => esc_html__( 'Map Icon', 'cbxgooglemap' ),
					'mapicon_select'        => esc_html__( 'Select image', 'cbxgooglemap' ),
				],
			] );

		wp_localize_script( 'cbxgooglemap-block', 'cbxgooglemap_block', $js_vars );

		register_block_type( 'codeboxr/cbxgooglemap', [
			'editor_script'   => 'cbxgooglemap-block',
			'editor_style'    => 'cbxgooglemap-block',
			'attributes'      => apply_filters( 'cbxgooglemap_block_attributes', [
				//general
				'id'      => [
					'type'    => 'integer',
					'default' => '0',
				],
				'maptype' => [
					'type'    => 'string',
					'default' => $maptype_default
				],
				'lat'     => [
					'type'    => 'string',
					'default' => ''
				],
				'lng'     => [
					'type'    => 'string',
					'default' => ''
				],
				'width'   => [
					'type'    => 'string',
					'default' => $width_default,
				],

				'height'      => [
					'type'    => 'integer',
					'default' => $height_default
				],
				'zoom'        => [
					'type'    => 'string',
					'default' => $zoom_default
				],
				'scrollwheel' => [
					'type'    => 'boolean',
					'default' => ( $scrollwheel_default ) ? true : false
				],
				'showinfo'    => [
					'type'    => 'boolean',
					'default' => ( $showinfo_default ) ? true : false
				],
				'infow_open'  => [
					'type'    => 'boolean',
					'default' => ( $infow_open_default ) ? true : false
				],
				'heading'     => [
					'type'    => 'string',
					'default' => ''
				],
				'address'     => [
					'type'    => 'string',
					'default' => ''
				],
				'website'     => [
					'type'    => 'string',
					'default' => ''
				],
				'mapicon'     => [
					'type'    => 'string',
					/*'source' => 'attribute',
					'selector' =>  'img',
					'attribute' => 'src',*/
					'default' => '',
				],
				/*'icon_id' => array(
					'type' => 'integer',
					//'default' => '',
				),*/
			] ),
			'render_callback' => [ $this, 'cbxgooglemap_block_render' ]
		] );

	}//end gutenberg_blocks

	/**
	 * Getenberg server side render
	 *
	 * @param $settings
	 *
	 * @return string
	 */
	public function cbxgooglemap_block_render( $attributes ) {
		$settings         = new CBXGooglemapSettings();
		$general_settings = get_option( 'cbxgooglemap_general', [] );

		$api_key    = $settings->get_field( 'apikey', $general_settings, '' );
		$map_source = intval( $settings->get_field( 'mapsource', $general_settings, 1 ) );

		if ( $map_source == 1 && $api_key == '' ) {
			echo '<p style="text-align: center;">' . esc_html__( 'Google Map Api Key is invalid!', 'cbxgooglemap' ) . '</p>';
		} else {
			$id = isset( $attributes['id'] ) ? intval( $attributes['id'] ) : 0;

			if ( $id > 0 ) {
				//render map from saved map
				return '[cbxgooglemap id="' . $id . '"]';
			} else {
				$attr = [];
				if ( isset( $attributes['lat'] ) ) {
					$attr['lat'] = sanitize_text_field( $attributes['lat'] );
				}
				if ( isset( $attributes['lng'] ) ) {
					$attr['lng'] = sanitize_text_field( $attributes['lng'] );
				}
				if ( isset( $attributes['width'] ) ) {
					$attr['width'] = sanitize_text_field( $attributes['width'] );
				}
				if ( isset( $attributes['height'] ) ) {
					$attr['height'] = intval( $attributes['height'] );
				}
				if ( isset( $attributes['zoom'] ) ) {
					$attr['zoom'] = sanitize_text_field( $attributes['zoom'] );
				}

				$attr['scrollwheel'] = isset( $attributes['scrollwheel'] ) ? $attributes['scrollwheel'] : 'true';
				$attr['infow_open']  = isset( $attributes['infow_open'] ) ? $attributes['infow_open'] : 'true';
				$attr['showinfo']    = isset( $attributes['showinfo'] ) ? $attributes['showinfo'] : 'true';

				$attr['scrollwheel'] = ( $attr['scrollwheel'] == 'true' ) ? 1 : 0;
				$attr['infow_open']  = ( $attr['infow_open'] == 'true' ) ? 1 : 0;
				$attr['showinfo']    = ( $attr['showinfo'] == 'true' ) ? 1 : 0;

				if ( isset( $attributes['heading'] ) ) {
					$attr['heading'] = sanitize_text_field( $attributes['heading'] );
				}
				if ( isset( $attributes['address'] ) ) {
					$attr['address'] = sanitize_text_field( $attributes['address'] );
				}
				if ( isset( $attributes['website'] ) ) {
					$attr['website'] = sanitize_text_field( $attributes['website'] );
				}
				if ( isset( $attributes['maptype'] ) ) {
					$attr['maptype'] = sanitize_text_field( $attributes['maptype'] );
				}
				if ( isset( $attributes['mapicon'] ) ) {
					$attr['mapicon'] = esc_url( $attributes['mapicon'] );
				}

				$attr = apply_filters( 'cbxgooglemap_block_shortcode_builder_attr', $attr, $attributes );

				$attr_html = '';

				foreach ( $attr as $key => $value ) {
					$attr_html .= ' ' . $key . '="' . $value . '" ';
				}

				//return do_shortcode( '[cbxgooglemap ' . $attr_html . ']' );
				return '[cbxgooglemap ' . $attr_html . ']';
			}
		}//end if api keys are ok

	}//end codeboxrflexiblecountdown_block_render

	/**
	 * Register New Gutenberg block Category if need
	 *
	 * @param $categories
	 * @param $post
	 *
	 * @return mixed
	 */
	public function gutenberg_block_categories( $categories, $post ) {
		$found = false;
		foreach ( $categories as $category ) {
			if ( $category['slug'] == 'codeboxr' ) {
				$found = true;
				break;
			}
		}

		if ( ! $found ) {
			return array_merge(
				$categories,
				[
					[
						'slug'  => 'codeboxr',
						'title' => esc_html__( 'CBX Blocks', 'cbxgooglemap' ),
					],
				]
			);
		}

		return $categories;
	}//end gutenberg_block_categories


	/**
	 * Enqueue style for block editor
	 */
	public function enqueue_block_editor_assets() {
	}//end enqueue_block_editor_assets
}//end class CBXGoogleMap_Admin