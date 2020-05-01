<?php
/**
 * thepaper functions and definitions
 *
 * @link https://developer.wordpress.org/themes/basics/theme-functions/
 *
 * @package thepaper
 */

if ( ! function_exists( 'thepaper_setup' ) ) :
	/**
	 * Sets up theme defaults and registers support for various WordPress features.
	 *
	 * Note that this function is hooked into the after_setup_theme hook, which
	 * runs before the init hook. The init hook is too late for some features, such
	 * as indicating support for post thumbnails.
	 */
	function thepaper_setup() {
		/*
		 * Make theme available for translation.
		 * Translations can be filed in the /languages/ directory.
		 * If you're building a theme based on thepaper, use a find and replace
		 * to change 'thepaper' to the name of your theme in all the template files.
		 */
		load_theme_textdomain( 'thepaper', get_template_directory() . '/languages' );

		// Add default posts and comments RSS feed links to head.
		add_theme_support( 'automatic-feed-links' );

		/*
		 * Let WordPress manage the document title.
		 * By adding theme support, we declare that this theme does not use a
		 * hard-coded <title> tag in the document head, and expect WordPress to
		 * provide it for us.
		 */
		add_theme_support( 'title-tag' );

		/*
		 * Enable support for Post Thumbnails on posts and pages.
		 *
		 * @link https://developer.wordpress.org/themes/functionality/featured-images-post-thumbnails/
		 */
		add_theme_support( 'post-thumbnails' );

		// This theme uses wp_nav_menu() in one location.
		register_nav_menus( array(
			'menu-1' => esc_html__( 'top_menu', 'thepaper' ),
		) );

		/*
		 * Switch default core markup for search form, comment form, and comments
		 * to output valid HTML5.
		 */
		add_theme_support( 'html5', array(
			'search-form',
			'comment-form',
			'comment-list',
			'gallery',
			'caption',
		) );


        add_editor_style('assets/css/editor-style.css');


		// Add theme support for selective refresh for widgets.
		add_theme_support( 'customize-selective-refresh-widgets' );


		/**
		 * Add support for core custom logo.
		 *
		 * @link https://codex.wordpress.org/Theme_Logo
		 */
		add_theme_support( 'custom-logo', array(
			'height'      => 250,
			'width'       => 250,
			'flex-width'  => true,
			'flex-height' => true,
		) );
	}
endif;
add_action( 'after_setup_theme', 'thepaper_setup' );

/**
 * Set the content width in pixels, based on the theme's design and stylesheet.
 *
 * Priority 0 to make it available to lower priority callbacks.
 *
 * @global int $content_width
 */
function thepaper_content_width() {
	// This variable is intended to be overruled from themes.
	// Open WPCS issue: {@link https://github.com/WordPress-Coding-Standards/WordPress-Coding-Standards/issues/1043}.
	// phpcs:ignore WordPress.NamingConventions.PrefixAllGlobals.NonPrefixedVariableFound
	$GLOBALS['content_width'] = apply_filters( 'thepaper_content_width', 640 );
}
add_action( 'after_setup_theme', 'thepaper_content_width', 0 );


function get_post_views ($post_id) {
    $count_key = 'views';
    $count = get_post_meta($post_id, $count_key, true);
    if ($count == '') {
        delete_post_meta($post_id, $count_key);
        add_post_meta($post_id, $count_key, '0');
        $count = '0';
    }
    echo $count;
}
function set_post_views () {
    global $post;
    $post_id = $post -> ID;
    $count_key = 'views';
    $count = get_post_meta($post_id, $count_key, true);
    if (is_single() || is_page()) {
        if ($count == '') {
            delete_post_meta($post_id, $count_key);
            add_post_meta($post_id, $count_key, '0');
        } else {
            update_post_meta($post_id, $count_key, $count + 1);
        }
    }
}


/**
 * Register widget area.
 *
 * @link https://developer.wordpress.org/themes/functionality/sidebars/#registering-a-sidebar
 */
function thepaper_widgets_init() {
	register_sidebar( array(
		'name'          =>  esc_html__('Sidebar','thepaper'),
		'id'            => 'sidebar-1',
		'description'   =>  esc_html__('Sidebar_description','thepaper'),
		'before_widget' => '<div id="%1$s" class="widget %2$s">',
		'after_widget'  => '</div>',
		'before_title'  => '<h2 class="widget-title">',
		'after_title'   => '</h2>',
	) );

    register_widget('Thepaper_popular_posts');

}
add_action( 'widgets_init', 'thepaper_widgets_init' );



function custom_excerpt_more( $more ) {
    return '...';
}
add_filter( 'excerpt_more', 'custom_excerpt_more' );


function diy_navigation_markup_template($template, $class ){

    if(is_single()){
        $template = '<nav class="navigation %1$s" role="navigation"><div class="nav-links">%3$s</div>
	      </nav>';
    }else{
        $template = '<nav class="navigation %1$s" role="navigation"><div class="thepager-nav-links">%3$s</div>
	      </nav>';
    }

    return $template;
}
add_filter('navigation_markup_template','diy_navigation_markup_template',10,2);


/**
 *
 * remove url of fields from comment
 * @param $fields
 * @return mixed
 */
function delete_comment_url($fields){

    unset($fields['url']);

    return $fields;
}

add_filter('comment_form_default_fields','delete_comment_url');


function timeago( $ptime ) {
    $ptime = strtotime($ptime);
    $etime = time() - $ptime;
    if($etime < 1) return esc_html__('right_now','thepaper');
    $interval = array (
        12 * 30 * 24 * 60 * 60  => esc_html__('_year','thepaper'),
        30 * 24 * 60 * 60       => esc_html__('_month','thepaper'),
        7 * 24 * 60 * 60        => esc_html__('_week','thepaper'),
        24 * 60 * 60            => esc_html__('_day','thepaper'),
        60 * 60                 => esc_html__('_hour','thepaper'),
        60                      => esc_html__('_minute','thepaper'),
        1                       => esc_html__('_second','thepaper')
    );
    foreach ($interval as $secs => $str) {
        $d = $etime / $secs;
        if ($d >= 1) {
            $r = round($d);
            return $r . $str;
        }
    };
}




/**
 * Enqueue scripts and styles.
 */
function thepaper_scripts() {
	wp_enqueue_style( 'thepaper-style', get_stylesheet_uri(),array('bootstrap'));

    wp_enqueue_style( 'thepaper.css', get_template_directory_uri() . '/assets/css/thepaper.css',array('thepaper-style'),'20191019');

    wp_enqueue_style('bootstrap',get_template_directory_uri() . '/assets/css/bootstrap.css',array(),'4.1.3');

    wp_enqueue_style('font-awesome-4.7.0',get_template_directory_uri() . '/assets/font-awesome/css/font-awesome.min.css',array(),'4.7.0');


    if(!is_customize_preview()){
        wp_enqueue_script( 'jquery.min.js', get_template_directory_uri() . '/assets/js/jquery-3.3.1.min.js', array(), '3.3.1', true );
    }

    wp_enqueue_script( 'popper.js', get_template_directory_uri() . '/assets/js/vendor/popper.min.js', array(), '20181019', true );

    wp_enqueue_script( 'bootstrap.min.js', get_template_directory_uri() . '/assets/js/vendor/bootstrap.js', array('jquery.min.js','popper.js'), '4.1.3', true );

    wp_enqueue_script( 'layer.js', get_template_directory_uri() . '/assets/js/layer/layer.js', array(), '3.3.1', true );

    wp_enqueue_script('util.js', get_template_directory_uri() . '/assets/js/util.js', array('jquery.min.js'), '0.1', true);


	wp_enqueue_script( 'thepaper-skip-link-focus-fix', get_template_directory_uri() . '/assets/js/skip-link-focus-fix.js', array(), '20151215', true );

	if ( is_singular() && comments_open() && get_option( 'thread_comments' ) ) {
		wp_enqueue_script( 'comment-reply' );
	}

    do_action('pass-parameter-to-util');

}
add_action( 'wp_enqueue_scripts', 'thepaper_scripts' );


add_action('pass-parameter-to-util','theme_mod_setting_js_calback');

function theme_mod_setting_js_calback(){

    $va = get_theme_mod('thepaper_menu_control',0);
    wp_localize_script('util.js','thepaper_menu_control',array('flag'=>$va));

}



/**
 * Implement the Custom Header feature.
 */
require get_template_directory() . '/inc/custom-header.php';

/**
 * Custom template tags for this theme.
 */
require get_template_directory() . '/inc/template-tags.php';

/**
 * Functions which enhance the theme by hooking into WordPress.
 */
require get_template_directory() . '/inc/template-functions.php';

/**
 * Customizer additions.
 */
require get_template_directory() . '/inc/customizer.php';

/**
 * Customizer widgets
 *
 */
require  get_template_directory() . '/inc/widgets/class-thepaper-popular-posts.php';

/**
 * Load Jetpack compatibility file.
 */
if ( defined( 'JETPACK__VERSION' ) ) {
	require get_template_directory() . '/inc/jetpack.php';
}

/**
 * Customizer nav menu
 */
require get_template_directory() . '/inc/class-thepaper-walker-nav-menu.php';

/**
 * Customizer
 */
require  get_template_directory() . '/inc/class-thepaper-walker-comment.php';


// Add epsilon framework
require get_template_directory() . '/inc/library/epsilon-framework/class-epsilon-autoloader.php';
$epsilon_framework_settings = array(
    'path' => '/inc/library',
    'controls' => array( 'toggle' ), // array of controls to load
    'sections' => array( 'recommended-actions', 'pro' ), // array of sections to load
);

function show_epsilon_quickie_bar(){
    return false;
}
add_filter('show_epsilon_quickie_bar','show_epsilon_quickie_bar');

new Epsilon_Framework( $epsilon_framework_settings );