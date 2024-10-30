<?php

/**
 * The file that defines the core plugin class
 *
 * A class definition that includes attributes and functions used across both the
 * public-facing side of the site and the admin area.
 *
 * @link       https://codeboxr.com
 * @since      1.0.0
 *
 * @package    Cbxgooglemap
 * @subpackage Cbxgooglemap/includes
 */

/**
 * The core plugin class.
 *
 * This is used to define internationalization, admin-specific hooks, and
 * public-facing site hooks.
 *
 * Also maintains the unique identifier of this plugin as well as the current
 * version of the plugin.
 *
 * @since      1.0.0
 * @package    Cbxgooglemap
 * @subpackage Cbxgooglemap/includes
 * @author     Codeboxr <info@codeboxr.com>
 */
class CBXGoogleMap {
	/**
	 * The single instance of the class.
	 *
	 * @var self
	 * @since  1.1.12
	 */
	private static $instance = null;

	/**
	 * The unique identifier of this plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      string $plugin_name The string used to uniquely identify this plugin.
	 */
	protected $plugin_name;

	/**
	 * The current version of the plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      string $version The current version of the plugin.
	 */
	protected $version;

	/**
	 * The ID of this plugin.
	 *
	 * @since    1.1.12
	 * @access   private
	 * @var      string $settings The ID of this plugin.
	 */
	private $settings;

	/**
	 * Define the core functionality of the plugin.
	 *
	 * Set the plugin name and the plugin version that can be used throughout the plugin.
	 * Load the dependencies, define the locale, and set the hooks for the admin area and
	 * the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function __construct() {

		$this->plugin_name = CBXGOOGLEMAP_PLUGIN_NAME;
		$this->version     = CBXGOOGLEMAP_PLUGIN_VERSION;

		$this->load_dependencies();



		$this->define_common_hooks();
		$this->define_admin_hooks();
		$this->define_public_hooks();
	}

	/**
	 * Singleton Instance.
	 *
	 * Ensures only one instance of cbxgooglemap is loaded or can be loaded.
	 *
	 * @return self Main instance.
	 * @see run_cbxgooglemap()
	 * @since  1.1.12
	 * @static
	 */
	public static function instance() {
		if ( is_null( self::$instance ) ) {
			self::$instance = new self();
		}

		return self::$instance;
	}//end method instance

	/**
	 * Load the required dependencies for this plugin.
	 *
	 * Include the following files that make up the plugin:
	 *
	 * - CBXGoogleMap_Loader. Orchestrates the hooks of the plugin.
	 * - CBXGoogleMap_i18n. Defines internationalization functionality.
	 * - CBXGoogleMap_Admin. Defines all hooks for the admin area.
	 * - CBXGoogleMap_Public. Defines all hooks for the public side of the site.
	 *
	 * Create an instance of the loader which will be used to register the hooks
	 * with WordPress.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function load_dependencies() {
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/cbxgooglemap-tpl-loader.php';
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-cbxgooglemap-settings.php';
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-cbxgooglemap-helper.php';
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/class-cbxgooglemap-admin.php';
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'public/class-cbxgooglemap-public.php';
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/cbxgooglemap-functions.php';
	}


	private function define_common_hooks() {
		add_action( 'plugins_loaded', [ $this, 'load_plugin_textdomain' ] );
		add_action( 'plugins_loaded', [ $this, 'house_keepings' ] );

		//upgrade process
		//add_action('admin_init', [$this, 'admin_init_upgrader_process']);
		//add_action( 'upgrader_process_complete', [ $this, 'plugin_upgrader_process_complete' ], 10, 2 );
		add_action( 'admin_notices', [ $this, 'plugin_activate_upgrade_notices' ] );

		//update manager
		add_filter( 'pre_set_site_transient_update_plugins', [ $this, 'pre_set_site_transient_update_plugins_pro_addon' ] );
		add_action( 'in_plugin_update_message-' . 'cbxgooglemappro/cbxgooglemappro.php', [ $this, 'plugin_update_message_pro_addon' ] );
	}//end method define_common_hooks

	/**
	 * Load the plugin text domain for translation.
	 *
	 * @since    1.0.0
	 */
	public function load_plugin_textdomain() {
		load_plugin_textdomain(
			'cbxgooglemap',
			false,
			CBXGOOGLEMAP_ROOT_PATH . 'languages/'
		);
	}//end method load_plugin_textdomain

	/**
	 * Register all of the hooks related to the admin area functionality
	 * of the plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function define_admin_hooks() {
		global $wp_version;

		$plugin_admin = new CBXGoogleMap_Admin();

		//adding the setting action
		add_action( 'admin_init', [ $plugin_admin, 'setting_init' ] );


		//add new post type
		add_action( 'init', [ $plugin_admin, 'create_post_type' ], 0 );


		//create opverview menu page
		add_action( 'admin_menu', [ $plugin_admin, 'menu_pages' ] );


		//display meta fields	
		add_action( 'add_meta_boxes', [ $plugin_admin, 'add_meta_boxes' ] );


		//save meta fields
		add_action( 'save_post', [ $plugin_admin, 'metabox_save' ], 10, 3 ); //save meta


		add_filter( 'manage_edit-cbxgooglemap_columns', [ $plugin_admin, 'cbxgooglemap_add_new_columns' ] );
		add_action( 'manage_cbxgooglemap_posts_custom_column', [ $plugin_admin, 'cbxgooglemap_manage_columns' ] );

		//js and css
		add_action( 'admin_enqueue_scripts', [ $plugin_admin, 'enqueue_styles' ] );
		add_action( 'admin_enqueue_scripts', [ $plugin_admin, 'enqueue_scripts' ] );


		//add plugin row meta and actions links
		add_filter( 'plugin_action_links_' . CBXGOOGLEMAP_BASE_NAME, [ $plugin_admin, 'plugin_listing_setting_link' ] );
		add_filter( 'plugin_row_meta', [ $plugin_admin, 'custom_plugin_row_meta' ], 10, 2 );

		//gutenberg
		add_action( 'init', [ $plugin_admin, 'gutenberg_blocks' ] );

		//gutenberg blocks
		if ( version_compare( $wp_version, '5.8' ) >= 0 ) {
			add_filter( 'block_categories_all', [ $plugin_admin, 'gutenberg_block_categories' ], 10, 2 );
		} else {
			add_filter( 'block_categories', [ $plugin_admin, 'gutenberg_block_categories' ], 10, 2 );
		}

		//add_filter( 'block_categories', $plugin_admin, 'gutenberg_block_categories', 10, 2 );
		add_action( 'enqueue_block_editor_assets', [ $plugin_admin, 'enqueue_block_editor_assets' ] );
	}//end define_admin_hooks

	/**
	 * Register all of the hooks related to the public-facing functionality
	 * of the plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function define_public_hooks() {
		global $wp_version;

		$plugin_public = new CBXGoogleMap_Public();

		add_action( 'init', [ $plugin_public, 'init_shortcodes' ] );
		add_action( 'widgets_init', [ $plugin_public, 'register_widget' ] );

		//elementor
		//add_action( 'elementor/init', [$plugin_public, 'init_elementor_widgets'] );
		add_action( 'elementor/widgets/widgets_registered', [ $plugin_public, 'init_elementor_widgets' ] );
		add_action( 'elementor/elements/categories_registered', [ $plugin_public, 'add_elementor_widget_categories' ] );
		add_action( 'elementor/editor/before_enqueue_scripts', [ $plugin_public, 'elementor_icon_loader' ], 99999 );

		add_action( 'wp_enqueue_scripts', [ $plugin_public, 'enqueue_styles' ] );
		add_action( 'wp_enqueue_scripts', [ $plugin_public, 'enqueue_scripts' ] );

		add_action( 'vc_before_init', [ $plugin_public, 'vc_before_init_actions' ] );
	}//end method define_public_hooks


	/**
	 * The name of the plugin used to uniquely identify it within the context of
	 * WordPress and to define internationalization functionality.
	 *
	 * @return    string    The name of the plugin.
	 * @since     1.0.0
	 */
	public function get_plugin_name() {
		return $this->plugin_name;
	}//end method get_plugin_name

	/**
	 * Retrieve the version number of the plugin.
	 *
	 * @return    string    The version number of the plugin.
	 * @since     1.0.0
	 */
	public function get_version() {
		return $this->version;
	}//end method get_version


	public function admin_init_upgrader_process(){
		//add_action( 'upgrader_process_complete', [ $this, 'plugin_upgrader_process_complete' ], 10, 2 );
	}//end method admin_init_upgrader_process

	/**
	 * If we need to do something in upgrader process is completed for map plugin
	 *
	 * @param $upgrader_object
	 * @param $options
	 */
	/*public function plugin_upgrader_process_complete( $upgrader_object, $options ) {
		if ( isset( $options['plugins'] ) && $options['action'] == 'update' && $options['type'] == 'plugin' ) {
			foreach ( $options['plugins'] as $each_plugin ) {
				if ( $each_plugin == CBXGOOGLEMAP_BASE_NAME ) {


					add_option( 'cbxgooglemap_flush_rewrite_rules', 'true' );
					set_transient( 'cbxgooglemap_upgraded_notice', 1 );

					if ( in_array( 'cbxgooglemappro/cbxgooglemappro.php', apply_filters( 'active_plugins', get_option( 'active_plugins' ) ) ) || defined( 'CBXGOOGLEMAPPRO_PLUGIN_NAME' ) ) {
						//plugin is activated

						$plugin_version = CBXGOOGLEMAPPRO_PLUGIN_VERSION;

						if ( version_compare( CBXGOOGLEMAPPRO_PLUGIN_VERSION, '1.0.5', '<=' ) ) {
							deactivate_plugins( 'cbxgooglemappro/cbxgooglemappro.php' );
							set_transient( 'cbxgooglemappro_deactivated_notice', 1 );
						}
					}
					break;
				}
			}
		}
	}//end plugin_upgrader_process_complete*/

	/**
	 * Show a notice to anyone who has just installed the plugin for the first time
	 * This notice shouldn't display to anyone who has just updated this plugin
	 */
	public function plugin_activate_upgrade_notices() {
		if ( get_option( 'cbxgooglemap_flush_rewrite_rules' ) == 'true' ) {
			flush_rewrite_rules();
			delete_option( 'cbxgooglemap_flush_rewrite_rules' );
		}

		// Check the transient to see if we've just activated the plugin
		if ( get_transient( 'cbxgooglemap_activated_notice' ) ) {
			echo '<div class="notice notice-success is-dismissible" style="border-color: #2153cc !important;">';
			echo '<p>' . sprintf( __( 'Thanks for installing/deactivating <strong>CBX Map for Google Map & OpenStreetMap</strong> V%s - Codeboxr Team',
					'cbxgooglemap' ),
					CBXGOOGLEMAP_PLUGIN_VERSION ) . '</p>';
			echo '<p>' . sprintf( __( 'Check <a style="color: #6648fe !important; font-weight: bold;" href="%s" target="_blank">Documentation</a> | Create <a style="color: #6648fe !important; font-weight: bold;" href="%s" target="_blank">Map</a>',
					'cbxgooglemap' ),
					'https://codeboxr.com/product/cbx-google-map-for-wordpress/',
					admin_url( 'post-new.php?post_type=cbxgooglemap' ) ) . '</p>';
			echo '</div>';

			// Delete the transient so we don't keep displaying the activation message
			delete_transient( 'cbxgooglemap_activated_notice' );

			$this->pro_addon_compatibility_campaign();
		}

		// Check the transient to see if we've just activated the plugin
		if ( get_transient( 'cbxgooglemap_upgraded_notice' ) ) {
			echo '<div class="notice notice-success is-dismissible" style="border-color: #2153cc !important;">';
			echo '<p>' . sprintf( __( 'Thanks for upgrading <strong>CBX Map for Google Map & OpenStreetMap</strong> V%s , enjoy the new features and bug fixes - Codeboxr Team',
					'cbxgooglemap' ),
					CBXGOOGLEMAP_PLUGIN_VERSION ) . '</p>';
			echo '<p>' . sprintf( __( 'Check <a style="color: #6648fe !important; font-weight: bold;" href="%s" target="_blank">Documentation</a> | Create <a style="color: #6648fe !important; font-weight: bold;" href="%s" target="_blank">Map</a>', 'cbxgooglemap' ),
					'https://codeboxr.com/product/cbx-google-map-for-wordpress/',
					admin_url( 'post-new.php?post_type=cbxgooglemap' ) ) . '</p>';
			echo '</div>';

			// Delete the transient so we don't keep displaying the activation message
			delete_transient( 'cbxgooglemap_upgraded_notice' );

			$this->pro_addon_compatibility_campaign();
		}

		if(get_transient('cbxgooglemappro_deactivated_notice')){
			echo '<div class="notice notice-error is-dismissible" style="border-color: red !important;">';
			echo '<p>' . __( 'Currently installed <strong>CBX Map for Google Map & OpenStreetMap Pro Addon</strong> version 1.0.5(or earlier) is not compatible with the latest version of core plugin CBX Map for Google Map & OpenStreetMap V1.1.12 or later. - Codeboxr Team',
					'cbxgooglemap' ). '</p>';
			echo '</div>';

			delete_transient('cbxgooglemappro_deactivated_notice');
		}
	}//end plugin_activate_upgrade_notices

	/**
	 * Check plugin compatibility and pro addon install campaign
	 */
	public function pro_addon_compatibility_campaign() {
		if ( ! function_exists( 'is_plugin_active' ) ) {
			include_once( ABSPATH . 'wp-admin/includes/plugin.php' );
		}

		//if the pro addon is active or installed
		if (defined( 'CBXGOOGLEMAPPRO_PLUGIN_NAME' ) || in_array( 'cbxgooglemappro/cbxgooglemappro.php', apply_filters( 'active_plugins', get_option( 'active_plugins' ) ) )) {
			//plugin is activated

			$plugin_version = CBXGOOGLEMAPPRO_PLUGIN_VERSION;

		} else {
			echo '<div class="notice notice-success is-dismissible" style="border-color: #2153cc !important;"><p>' . sprintf( __( 'CBX Map for Google Map & OpenStreetMap Pro has extended features and more controls, <a style="color: #6648fe !important; font-weight: bold;" target="_blank" href="%s">try it</a>  - Codeboxr Team',
					'cbxgooglemap' ), 'https://codeboxr.com/product/cbx-google-map-for-wordpress/#downloadarea/' ) . '</p></div>';
		}
	}//end pro_addon_compatibility_campaign

	/**
	 * Add our self-hosted autoupdate plugin to the filter transient
	 *
	 * @param $transient
	 *
	 * @return object $ transient
	 */
	public function pre_set_site_transient_update_plugins_pro_addon( $transient ) {
		// Extra check for 3rd plugins
		if ( isset( $transient->response['cbxgooglemappro/cbxgooglemappro.php'] ) ) {
			return $transient;
		}

		if ( ! function_exists( 'get_plugins' ) ) {
			require_once ABSPATH . 'wp-admin/includes/plugin.php';
		}

		$plugin_info = [];
		$all_plugins = get_plugins();
		if ( ! isset( $all_plugins['cbxgooglemappro/cbxgooglemappro.php'] ) ) {
			return $transient;
		} else {
			$plugin_info = $all_plugins['cbxgooglemappro/cbxgooglemappro.php'];
		}


		$remote_version = '1.0.6';

		if ( version_compare( $plugin_info['Version'], $remote_version, '<' ) ) {
			$obj                                                        = new stdClass();
			$obj->slug                                                  = 'cbxgooglemappro';
			$obj->new_version                                           = $remote_version;
			$obj->plugin                                                = 'cbxgooglemappro/cbxgooglemappro.php';
			$obj->url                                                   = '';
			$obj->package                                               = false;
			$obj->name                                                  = 'CBX Map for Google Map & OpenStreetMap Pro Addon';
			$transient->response['cbxgooglemappro/cbxgooglemappro.php'] = $obj;
		}

		return $transient;
	}//end pre_set_site_transient_update_plugins_pro_addon

	/**
	 * Pro Addon update message
	 */
	public function plugin_update_message_pro_addon() {
		echo ' ' . sprintf( __( 'Check how to <a style="color:#9c27b0 !important; font-weight: bold;" href="%s"><strong>Update manually</strong></a> , download the latest version from <a style="color:#9c27b0 !important; font-weight: bold;" href="%s"><strong>My Account</strong></a> section of Codeboxr.com', 'cbxgooglemap' ), 'https://codeboxr.com/manual-update-pro-addon/', 'https://codeboxr.com/my-account/' );
	}//end plugin_update_message_pro_addon


	/**
	 * Do some housekeeping
	 *
	 * @return void
	 */
	public function house_keepings(){
		if ( ! function_exists( 'is_plugin_active' ) ) {
			include_once( ABSPATH . 'wp-admin/includes/plugin.php' );
		}

		if (defined( 'CBXGOOGLEMAPPRO_PLUGIN_NAME' ) || in_array( 'cbxgooglemappro/cbxgooglemappro.php', apply_filters( 'active_plugins', get_option( 'active_plugins' ) ) )  ) {
			//plugin is activated

			$plugin_version = CBXGOOGLEMAPPRO_PLUGIN_VERSION;

			if ( version_compare( $plugin_version, '1.0.5', '<=' ) ) {
				deactivate_plugins( 'cbxgooglemappro/cbxgooglemappro.php' );
				set_transient( 'cbxgooglemappro_deactivated_notice', 1 );
			}
		}
	}//end method house_keepings
}//end class CBXGoogleMap