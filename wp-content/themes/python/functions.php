<?php

if ( !defined( 'TZ_THEME_VERSION' ) ) define('TZ_THEME_VERSION', '0.0.1');
if ( !defined( 'TZ_THEME_PREFIX' ) ) define('TZ_THEME_PREFIX', 'python');


if ( ! function_exists( 'python_setup' ) ) :
    /**
     * Sets up theme defaults and registers support for various WordPress features.
     *
     * Note that this function is hooked into the after_setup_theme hook, which
     * runs before the init hook. The init hook is too late for some features, such
     * as indicating support for post thumbnails.
     */
    function python_setup() {


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


        register_nav_menus( array(
            'menu-main' => esc_html__( 'hearder_menu', 'python' ),
            'extra-menu' => esc_html__('extra_menu', 'python')
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
            'header-text' => array( 'site-title', 'site-description' ),
        ) );

        /**
         * Add support for all post formats.
         *
         * @link https://codex.wordpress.org/Post_Formats
         */
        add_theme_support( 'post-formats', array(
            'aside',
            'gallery',
            'link',
            'image',
            'quote',
            'status',
            'video',
            'audio',
            'chat'
        ) );
    }
endif;
add_action( 'after_setup_theme', 'python_setup' );


function timeago( $ptime ) {
    $ptime = strtotime($ptime);
    $etime = time() - $ptime;
    if($etime < 1) return '刚刚';
    $interval = array (
        12 * 30 * 24 * 60 * 60  =>  '年前 ('.date('Y-m-d', $ptime).')',
        30 * 24 * 60 * 60       =>  '个月前 ('.date('m-d', $ptime).')',
        7 * 24 * 60 * 60        =>  '周前 ('.date('m-d', $ptime).')',
        24 * 60 * 60            =>  '天前',
        60 * 60                 =>  '小时前',
        60                      =>  '分钟前',
        1                       =>  '秒前'
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
function python_scripts() {

    wp_enqueue_style( 'python-style', get_stylesheet_uri() );

    wp_enqueue_style('vuetify-main','https://cdn.jsdelivr.net/npm/vuetify/dist/vuetify.min.css',array(),TZ_THEME_VERSION);

    wp_enqueue_script('vue.js','https://cdn.jsdelivr.net/npm/vue/dist/vue.js',array(),TZ_THEME_VERSION,true);

    wp_enqueue_script('vuetify.js','https://cdn.jsdelivr.net/npm/vuetify/dist/vuetify.js',array('vue.js'),TZ_THEME_VERSION,true);

    if(is_front_page()){

        wp_enqueue_script('pmain.js',get_template_directory_uri().'/js/pmain.js',array('vue.js','vuetify.js'),TZ_THEME_VERSION,true);

    }

    if(is_single()){

        wp_enqueue_style('single',get_template_directory_uri().'/css/single.css',array('python-style'),TZ_THEME_VERSION);

        wp_enqueue_script('article.js',get_template_directory_uri().'/js/article.js',array('vue.js','vuetify.js'),TZ_THEME_VERSION,true);
    }




}
add_action( 'wp_enqueue_scripts', 'python_scripts' );