<?php
/**
 * colorlib-cn Theme Customizer
 *
 * @package colorlib-cn
 */

/**
 * Add postMessage support for site title and description for the Theme Customizer.
 *
 * @param WP_Customize_Manager $wp_customize Theme Customizer object.
 */
function colorlib_cn_customize_register( $wp_customize ) {
	$wp_customize->get_setting( 'blogname' )->transport         = 'postMessage';
	$wp_customize->get_setting( 'blogdescription' )->transport  = 'postMessage';
	$wp_customize->get_setting( 'header_textcolor' )->transport = 'postMessage';

	if ( isset( $wp_customize->selective_refresh ) ) {
		$wp_customize->selective_refresh->add_partial( 'blogname', array(
			'selector'        => '.site-title a',
			'render_callback' => 'colorlib_cn_customize_partial_blogname',
		) );
		$wp_customize->selective_refresh->add_partial( 'blogdescription', array(
			'selector'        => '.site-description',
			'render_callback' => 'colorlib_cn_customize_partial_blogdescription',
		) );
	}


    /* Main option Settings Panel */
    $wp_customize->add_panel(
        'sparkling_main_options', array(
            'capability'     => 'edit_theme_options',
            'theme_supports' => '',
            'title'          => 'colirlib-cn设置',
            // Include html tags such as <p>.
            'priority'       => 10,
            // Mixed with top-level-section hierarchy.
        )
    );


    /* Archive pages settings */
    $wp_customize->add_section(
        'sparkling_archive_section', array(
            'title'    => esc_html__( 'Archive Pages', 'sparkling' ),
            'priority' => 50,
            'panel'    => 'sparkling_main_options',
        )
    );

    $wp_customize->add_setting(
        'sparkling[tag_title]', array(
            'default'           => '',
            'type'              => 'option',
            'sanitize_callback' => 'esc_html',
        )
    );

    $wp_customize->add_control(
        'sparkling[tag_title]', array(
            'label'       => __( 'Tag Page Title', 'sparkling' ),
            'section'     => 'sparkling_archive_section',
            'description' => __( 'The headline for your tag pages. You can use %s as a placeholder for the tag. Leave empty for default.', 'sparkling' ),
            'type'        => 'text',
        )
    );



}
add_action( 'customize_register', 'colorlib_cn_customize_register' );

/**
 * Render the site title for the selective refresh partial.
 *
 * @return void
 */
function colorlib_cn_customize_partial_blogname() {
	bloginfo( 'name' );
}

/**
 * Render the site tagline for the selective refresh partial.
 *
 * @return void
 */
function colorlib_cn_customize_partial_blogdescription() {
	bloginfo( 'description' );
}

/**
 * Binds JS handlers to make Theme Customizer preview reload changes asynchronously.
 */
function colorlib_cn_customize_preview_js() {
	wp_enqueue_script( 'colorlib-cn-customizer', get_template_directory_uri() . '/assets/js/customizer.js', array( 'customize-preview' ), '20151215', true );
}
add_action( 'customize_preview_init', 'colorlib_cn_customize_preview_js' );
