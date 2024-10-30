<?php
// Prevent direct file access
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class CBXGoogleMap_Widget extends WP_Widget {

	/**
	 * Unique identifier for your widget.
	 *
	 *
	 * @since    1.1.7
	 *
	 * @var      string
	 */
	protected $widget_slug = 'cbxgooglemap-widget'; //main parent plugin's language file
	/*--------------------------------------------------*/
	/* Constructor
	/*--------------------------------------------------*/

	/**
	 * Specifies the classname and description, instantiates the widget,
	 * loads localization files, and includes necessary stylesheets and JavaScript.
	 */
	public function __construct() {
		parent::__construct(
			$this->get_widget_slug(),
			esc_html__( 'CBX Google Map', 'cbxgooglemap' ),
			[
				'classname'   => 'widget-cbxgooglemap',
				'description' => esc_html__( 'CBX Google Map Widget', 'cbxgooglemap' )
			]
		);

	}//end constructor

	/**
	 * Return the widget slug.
	 *
	 * @return    Plugin slug variable.
	 * @since    1.0.0
	 *
	 */
	public function get_widget_slug() {
		return $this->widget_slug;
	}

	/**
	 * Outputs the content of the widget.
	 *
	 * @param array args  The array of form elements
	 * @param array instance The current instance of the widget
	 */
	public function widget( $args, $instance ) {

		extract( $args, EXTR_SKIP );

		$widget_string = $before_widget;

		$title = apply_filters( 'widget_title',
			empty( $instance['title'] ) ? esc_html__( 'CBX Google Map',
				'cbxgooglemap' ) : $instance['title'], $instance, $this->id_base );
		// Defining the Widget Title
		if ( $title ) {
			$widget_string .= $args['before_title'] . $title . $args['after_title'];
		} else {
			$widget_string .= $args['before_title'] . $args['after_title'];
		}

		ob_start();
		$settings         = new CBXGooglemapSettings();
		$general_settings = get_option( 'cbxgooglemap_general', [] );

		$api_key    = $settings->get_field( 'apikey', $general_settings, '' );
		$map_source = intval( $settings->get_field( 'mapsource', $general_settings, 1 ) );

		if ( $map_source == 1 && $api_key == '' ) {
			echo '<p style="text-align: center;">' . esc_html__( 'Google Map Api Key is invalid!', 'cbxgooglemap' ) . '</p>';
		} else {

			$id = intval( $instance['map_id'] );

			if ( $id > 0 ) {
				//render map from saved map
				echo do_shortcode( '[cbxgooglemap id="' . $id . '"]' );
			} else {

				//render map from custom attributes
				$maptype     = sanitize_text_field( $instance['maptype'] );
				$lat         = sanitize_text_field( $instance['lat'] );
				$lng         = sanitize_text_field( $instance['lng'] );
				$width       = sanitize_text_field( $instance['width'] );
				$height      = sanitize_text_field( $instance['height'] );
				$zoom        = sanitize_text_field( $instance['zoom'] );
				$scrollwheel = intval( $instance['scrollwheel'] );
				$showinfo    = intval( $instance['showinfo'] );
				$infow_open  = intval( $instance['infow_open'] );
				$heading     = sanitize_text_field( $instance['heading'] );
				$address     = sanitize_text_field( $instance['address'] );
				$website     = sanitize_text_field( $instance['website'] );

				echo do_shortcode( '[cbxgooglemap lat="' . $lat . '" lng="' . $lng . '" zoom="' . $zoom . '" scrollwheel="' . $scrollwheel . '" showinfo="' . $showinfo . '" infow_open="' . $infow_open . '" heading="' . $heading . '" maptype="' . $maptype . '" website="' . $website . '" address="' . $address . '" width="' . $width . '" height="' . $height . '"]' );
			}
		}
		$content = ob_get_contents();
		ob_end_clean();

		$widget_string .= $content;

		$widget_string .= $after_widget;

		echo $widget_string;

	}//end of method widget


	/**
	 * Processes the widget's options to be saved.
	 *
	 * @param array $new_instance
	 * @param array $old_instance
	 *
	 * @return array
	 */
	public function update( $new_instance, $old_instance ) {

		$instance                = $old_instance;
		$instance['title']       = isset( $new_instance['title'] ) ? sanitize_text_field( $new_instance['title'] ) : '';
		$instance['map_id']      = isset( $new_instance['map_id'] ) ? intval( $new_instance['map_id'] ) : 0;
		$instance['maptype']     = isset( $new_instance['maptype'] ) ? sanitize_text_field( $new_instance['maptype'] ) : 'roadmap';
		$instance['lat']         = isset( $new_instance['lat'] ) ? sanitize_text_field( $new_instance['lat'] ) : '';
		$instance['lng']         = isset( $new_instance['lng'] ) ? sanitize_text_field( $new_instance['lng'] ) : '';
		$instance['width']       = isset( $new_instance['width'] ) ? sanitize_text_field( $new_instance['width'] ) : '100%';
		$instance['height']      = isset( $new_instance['height'] ) ? sanitize_text_field( $new_instance['height'] ) : '300';
		$instance['zoom']        = isset( $new_instance['zoom'] ) ? sanitize_text_field( $new_instance['zoom'] ) : '8';
		$instance['scrollwheel'] = isset( $new_instance['scrollwheel'] ) ? intval( $new_instance['scrollwheel'] ) : 1;
		$instance['showinfo']    = isset( $new_instance['showinfo'] ) ? intval( $new_instance['showinfo'] ) : 1;
		$instance['infow_open']  = isset( $new_instance['infow_open'] ) ? intval( $new_instance['infow_open'] ) : 1;
		$instance['heading']     = isset( $new_instance['heading'] ) ? sanitize_text_field( $new_instance['heading'] ) : '';
		$instance['address']     = isset( $new_instance['address'] ) ? sanitize_text_field( $new_instance['address'] ) : '';
		$instance['website']     = isset( $new_instance['website'] ) ? sanitize_text_field( $new_instance['website'] ) : '';

		return $instance;


	}//end of method widget

	/**
	 * Generates the administration form for the widget.
	 *
	 * @param array instance The array of keys and values for the widget.
	 */
	public function form( $instance ) {
		$cbxgooglemap_general = get_option( 'cbxgooglemap_general' );

		$map_id      = ( isset( $cbxgooglemap_general['map_id'] ) ) ? $cbxgooglemap_general['map_id'] : 0;
		$maptype     = ( isset( $cbxgooglemap_general['maptype'] ) ) ? $cbxgooglemap_general['maptype'] : 'roadmap';
		$lat         = ( isset( $cbxgooglemap_general['lat'] ) ) ? $cbxgooglemap_general['lat'] : '';
		$lng         = ( isset( $cbxgooglemap_general['lng'] ) ) ? $cbxgooglemap_general['lng'] : '';
		$width       = ( isset( $cbxgooglemap_general['width'] ) ) ? $cbxgooglemap_general['width'] : '100%';
		$height      = ( isset( $cbxgooglemap_general['height'] ) ) ? $cbxgooglemap_general['height'] : '300';
		$zoom        = ( isset( $cbxgooglemap_general['zoom'] ) ) ? $cbxgooglemap_general['zoom'] : '8';
		$scrollwheel = ( isset( $cbxgooglemap_general['scrollwheel'] ) ) ? $cbxgooglemap_general['scrollwheel'] : 1;
		$showinfo    = ( isset( $cbxgooglemap_general['showinfo'] ) ) ? $cbxgooglemap_general['showinfo'] : 1;
		$infow_open  = ( isset( $cbxgooglemap_general['infow_open'] ) ) ? $cbxgooglemap_general['infow_open'] : 1;
		$heading     = ( isset( $cbxgooglemap_general['heading'] ) ) ? $cbxgooglemap_general['heading'] : '';
		$address     = ( isset( $cbxgooglemap_general['address'] ) ) ? $cbxgooglemap_general['address'] : '';
		$website     = ( isset( $cbxgooglemap_general['website'] ) ) ? $cbxgooglemap_general['website'] : '';

		$instance    = wp_parse_args( (array) $instance,
			[
				'title'       => esc_html__( 'CBX Google Map', 'cbxgooglemap' ),
				'map_id'      => $map_id,
				'maptype'     => $maptype,
				'lat'         => $lat,
				'lng'         => $lng,
				'width'       => $width,
				'height'      => $height,
				'zoom'        => $zoom,
				'scrollwheel' => $scrollwheel,
				'showinfo'    => $showinfo,
				'infow_open'  => $infow_open,
				'heading'     => $heading,
				'address'     => $address,
				'website'     => $website,
			] );
		$title       = sanitize_text_field( $instance['title'] );
		$map_id      = intval( $instance['map_id'] );
		$maptype     = sanitize_text_field( $instance['maptype'] );
		$lat         = sanitize_text_field( $instance['lat'] );
		$lng         = sanitize_text_field( $instance['lng'] );
		$width       = sanitize_text_field( $instance['width'] );
		$height      = sanitize_text_field( $instance['height'] );
		$zoom        = sanitize_text_field( $instance['zoom'] );
		$scrollwheel = intval( $instance['scrollwheel'] );
		$showinfo    = intval( $instance['showinfo'] );
		$infow_open  = intval( $instance['infow_open'] );
		$heading     = sanitize_text_field( $instance['heading'] );
		$address     = sanitize_text_field( $instance['address'] );
		$website     = sanitize_text_field( $instance['website'] );

		// Display the admin form
		include( plugin_dir_path( __FILE__ ) . 'views/admin.php' );

	}//end of method form

}//end class CBXGoogleMap_Widget