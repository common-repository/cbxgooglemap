<?php
// Prevent direct file access
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}


class CBXGoogleMap_WPBWidget extends WPBakeryShortCode {
	/**
	 * Constructor.
	 */
	public function __construct() {
		add_action( 'init', [ $this, 'bakery_shortcode_mapping' ], 12 );
	}// /end of constructor

	/**
	 * Element Mapping
	 */
	public function bakery_shortcode_mapping() {
		$query = get_posts( [
			'post_type'      => 'cbxgooglemap',
			'orderby'        => 'date',
			'posts_per_page' => - 1,
			'post_status'    => 'publish',
		] );

		$googleMap_posts = [];

		$googleMap_posts[''] = esc_html__( 'Choose Ready Map or Use custom params', 'cbxgooglemap' );

		foreach ( $query as $post ) :
			CBXGooglemapHelper::setup_admin_postdata( $post );
			//setup_postdata($post);
			$post_id    = intval( get_the_ID() );
			$post_title = get_the_title();

			$googleMap_posts[ esc_attr( $post_title ) ] = $post_id;


		endforeach;
		CBXGooglemapHelper::wp_reset_admin_postdata();
		//wp_reset_postdata();

		// Map the block with vc_map()
		vc_map( [
			"name"        => esc_html__( "CBX Google Map", 'cbxgooglemap' ),
			"description" => esc_html__( "CBX Google Map Widget", 'cbxgooglemap' ),
			"base"        => "cbxgooglemap",
			"icon"        => CBXGOOGLEMAP_ROOT_URL . 'assets/img/icon.png',
			"category"    => esc_html__( 'CBX Widgets', 'cbxgooglemap' ),
			"params"      => apply_filters( 'cbxgooglemap_wpbakery_params', [
					[
						'type'        => 'dropdown',
						'heading'     => esc_html__( 'Predefined Map', 'cbxgooglemap' ),
						'param_name'  => 'id',
						'admin_label' => true,
						'value'       => $googleMap_posts,
						'std'         => '',
						'description' => esc_html__( 'Choose predefined map or create from custom attributes below', 'cbxgooglemap' ),
					],
					[
						'type'        => 'dropdown',
						"class"       => "",
						'admin_label' => true,
						'heading'     => esc_html__( 'Map Type', 'cbxgooglemap' ),
						'param_name'  => 'maptype',
						'value'       => CBXGooglemapHelper::get_maptype_r(),
						'std'         => 'roadmap',
						'group'       => esc_html__( 'Custom Map Attributes', 'cbxgooglemap' ),
					],
					[
						"type"        => "textfield",
						"class"       => "",
						'admin_label' => true,
						"heading"     => esc_html__( 'Latitude', 'cbxgooglemap' ),
						"param_name"  => "lat",
						'std'         => '',
						'group'       => esc_html__( 'Custom Map Attributes', 'cbxgooglemap' ),
					],
					[
						"type"        => "textfield",
						"class"       => "",
						'admin_label' => true,
						"heading"     => esc_html__( 'Longitude', 'cbxgooglemap' ),
						"param_name"  => "lng",
						'std'         => '',
						'group'       => esc_html__( 'Custom Map Attributes', 'cbxgooglemap' ),
					],
					[
						"type"        => "textfield",
						"class"       => "",
						'admin_label' => false,
						"heading"     => esc_html__( 'Width(Numeric or with %)', 'cbxgooglemap' ),
						"param_name"  => "width",
						'std'         => '100%',
						'group'       => esc_html__( 'Custom Map Attributes', 'cbxgooglemap' ),
					],
					[
						"type"        => "textfield",
						"class"       => "",
						'admin_label' => false,
						"heading"     => esc_html__( 'Height', 'cbxgooglemap' ),
						"param_name"  => "height",
						'std'         => '300',
						'group'       => esc_html__( 'Custom Map Attributes', 'cbxgooglemap' ),
					],
					[
						"type"        => "textfield",
						"class"       => "",
						'admin_label' => false,
						"heading"     => esc_html__( 'Zoom(Numeric Value)', 'cbxgooglemap' ),
						"param_name"  => "zoom",
						'std'         => '8',
						'group'       => esc_html__( 'Custom Map Attributes', 'cbxgooglemap' ),
					],
					[
						'type'        => 'dropdown',
						"class"       => "",
						'admin_label' => false,
						'heading'     => esc_html__( 'Mouse Scroll Wheel', 'cbxgooglemap' ),
						'param_name'  => 'scrollwheel',
						'value'       => [
							esc_html__( 'Enable', 'cbxgooglemap' )  => '1',
							esc_html__( 'Disable', 'cbxgooglemap' ) => '0',
						],
						'std'         => 1,
						'group'       => esc_html__( 'Custom Map Attributes', 'cbxgooglemap' ),
					],
					[
						'type'        => 'dropdown',
						"class"       => "",
						'admin_label' => false,
						'heading'     => esc_html__( 'Show Popup', 'cbxgooglemap' ),
						'param_name'  => 'showinfo',
						'value'       => [
							esc_html__( 'Enable', 'cbxgooglemap' )  => '1',
							esc_html__( 'Disable', 'cbxgooglemap' ) => '0',
						],
						'std'         => 1,
						'group'       => esc_html__( 'Custom Map Attributes', 'cbxgooglemap' ),
					],
					[
						'type'        => 'dropdown',
						"class"       => "",
						'admin_label' => false,
						'heading'     => esc_html__( 'Info/Popup Window', 'cbxgooglemap' ),
						'param_name'  => 'infow_open',
						'value'       => [
							esc_html__( 'Open(Default)', 'cbxgooglemap' ) => '1',
							esc_html__( 'On Click', 'cbxgooglemap' )      => '0',
						],
						'std'         => 1,
						'group'       => esc_html__( 'Custom Map Attributes', 'cbxgooglemap' ),
					],
					[
						"type"        => "textfield",
						"class"       => "",
						'admin_label' => false,
						"heading"     => esc_html__( 'Popup Heading', 'cbxgooglemap' ),
						"param_name"  => "heading",
						'std'         => '',
						'group'       => esc_html__( 'Custom Map Attributes', 'cbxgooglemap' ),
					],
					[
						"type"        => "textfield",
						"class"       => "",
						'admin_label' => false,
						"heading"     => esc_html__( 'Location Address', 'cbxgooglemap' ),
						"param_name"  => "address",
						'std'         => '',
						'group'       => esc_html__( 'Custom Map Attributes', 'cbxgooglemap' ),
					],
					[
						"type"        => "vc_link",
						"class"       => "",
						'admin_label' => false,
						"heading"     => esc_html__( 'Website url', 'cbxgooglemap' ),
						"param_name"  => "website",
						'std'         => '',
						'group'       => esc_html__( 'Custom Map Attributes', 'cbxgooglemap' ),
					],
					[
						"type"        => "attach_image",
						"class"       => "",
						'admin_label' => false,
						"heading"     => esc_html__( 'Map Icon', 'cbxgooglemap' ),
						"param_name"  => "mapicon",
						'std'         => '',
						'group'       => esc_html__( 'Custom Map Attributes', 'cbxgooglemap' ),
					],
				]
			)
		] );
	}//end bakery_shortcode_mapping
}// end class CBXGoogleMap_WPBWidget