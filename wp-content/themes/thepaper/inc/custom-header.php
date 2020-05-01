<?php
/**
 * Sample implementation of the Custom Header feature
 *
 * You can add an optional custom header image to header.php like so ...
 *
	<?php the_header_image_tag(); ?>
 *
 * @link https://developer.wordpress.org/themes/functionality/custom-headers/
 *
 * @package thepaper
 */

/**
 * Set up the WordPress core custom header feature.
 *
 * @uses thepaper_header_style()
 */
function thepaper_custom_header_setup() {
	add_theme_support( 'custom-header', apply_filters( 'thepaper_custom_header_args', array(
		'default-image'          => '',
		'default-text-color'     => '000000',
		'width'                  => 1200,
		'height'                 => 120,
		'flex-height'            => true,
		'wp-head-callback'       => 'thepaper_header_style',
	) ) );
}
//add_action( 'after_setup_theme', 'thepaper_custom_header_setup' );

if ( ! function_exists( 'thepaper_header_style' ) ) :
	/**
	 * Styles the header image and text displayed on the blog.
	 *
	 * @see thepaper_custom_header_setup().
	 */
	function thepaper_header_style() {

	    $default_image = get_header_image();

		if (!$default_image){
			return;
		}
		?>
		<style type="text/css">
			 .header-image{
				 width: 100%;
				 height: 120px;
			 }
		</style>

		<?php

	}
endif;
