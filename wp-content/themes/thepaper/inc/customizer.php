<?php
/**
 * thepaper Theme Customizer
 *
 * @package thepaper
 */

/**
 * Add postMessage support for site title and description for the Theme Customizer.
 *
 * @param WP_Customize_Manager $wp_customize Theme Customizer object.
 */
function thepaper_customize_register( $wp_customize ) {
	$wp_customize->get_setting( 'blogname' )->transport         = 'postMessage';
	$wp_customize->get_setting( 'blogdescription' )->transport  = 'postMessage';
	$wp_customize->get_setting( 'header_textcolor' )->transport = 'postMessage';


	if ( isset( $wp_customize->selective_refresh ) ) {
		$wp_customize->selective_refresh->add_partial( 'blogname', array(
			'selector'        => '.site-title a',
			'render_callback' => 'thepaper_customize_partial_blogname',
		) );
		$wp_customize->selective_refresh->add_partial( 'blogdescription', array(
			'selector'        => '.site-description',
			'render_callback' => 'thepaper_customize_partial_blogdescription',
		) );
	}

    $wp_customize->remove_section('static_front_page');

	$wp_customize->add_panel('thepaper_main_options',array(
        'capability'     => 'edit_theme_options',
        'theme_supports' => '',
        'title'          =>  esc_html__('thepaper_theme_setting','thepaper'),
        'description'    => esc_html__('thepaper_theme_setting','thepaper'),
        // Include html tags such as <p>.
        'priority'       => 10,
    ));

    $wp_customize->add_section('thepaper_menu_option',array(
        'title'    => esc_html__('home_menu_setting','thepaper'),
        'priority' => 50,
        'panel'    => 'thepaper_main_options',

    ));

    // add setting for excerpts/full posts toggle
    $wp_customize->add_setting(
        'thepaper_menu_control', array(
            'default'           => 0,
            'sanitize_callback' => 'thepaper_menu_control_js_callback'
        )
    );


    $wp_customize->add_control(

        new Epsilon_Control_Toggle(
            $wp_customize, 'thepaper_menu_control', array(
                'label'    => esc_html__('menu_float','thepaper') ,
                'section'  => 'thepaper_menu_option',
                'priority' => 10,
                'type'     => 'epsilon-toggle',
            )
        )


    );




}
add_action( 'customize_register', 'thepaper_customize_register' );

/**
 * Render the site title for the selective refresh partial.
 *
 * @return void
 */
function thepaper_customize_partial_blogname() {
	bloginfo( 'name' );
}

/**
 * Render the site tagline for the selective refresh partial.
 *
 * @return void
 */
function thepaper_customize_partial_blogdescription() {
	bloginfo( 'description' );
}



function thepaper_menu_control_js_callback($input){

    if($input){
        return 1;
    }else{
        return 0;
    }

}



/**
 * Binds JS handlers to make Theme Customizer preview reload changes asynchronously.
 */
function thepaper_customize_preview_js() {
	wp_enqueue_script( 'thepaper-customizer', get_template_directory_uri() . '/assets/js/customizer.js', array( 'customize-preview' ), '20151215', true );
}
add_action( 'customize_preview_init', 'thepaper_customize_preview_js' );

