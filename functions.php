<?php if ( ! defined( 'ABSPATH' ) ) die();

/**
 * Loading Helper
 */
require ('inc/helper.php');

/**
 * Define theme name
 */
if ( ! defined( 'THEME_TEXT_DOMAIN' ) )
    define('THEME_TEXT_DOMAIN', 'ast_default');

/**
 * This Theme only works in WordPress 3.6 or later.
 */
if ( version_compare( $GLOBALS['wp_version'], '3.6', '<' ) ) {
    require get_template_directory() . '/inc/compatibility.php';
}

if ( ! function_exists( 'theme_setup' ) ) :
/**
 * Theme setup.
 *
 * Set up theme and registers support for various WordPress features.
 *
 * Note that this function is hooked into the after_setup_theme hook, which
 * runs before the init hook. The init hook is too late for some features, such
 * as indicating support post thumbnails.
 */
function theme_setup()
{
    /*
     * Make Theme available for translation.
     *
     * Translations can be added to the /languages/ directory.
     */
    load_theme_textdomain( THEME_TEXT_DOMAIN, get_template_directory() . '/languages' );

    /**
     * This theme styles the visual editor to resemble the theme style.
     */
    add_editor_style( array( 'css/editor-style.css', theme_font_url() ) );

    /**
     * Add RSS feed links to <head> for posts and comments.
     */
    add_theme_support( 'automatic-feed-links' );

    /**
     * Enable support for Post Thumbnails.
     */
    add_theme_support( 'post-thumbnails' );

    /*
     * Switch default core markup for search form, comment form, and comments
     * to output valid HTML5.
     */
    add_theme_support( 'html5', array(
        'search-form', 'comment-form', 'comment-list',
    ) );

    /*
     * Enable support for Post Formats.
     * See http://codex.wordpress.org/Post_Formats
     */
    add_theme_support( 'post-formats', array(
        'aside', 'image', 'video', 'audio', 'quote', 'link', 'gallery',
    ) );

    /**
     * This theme allows users to set a custom background.
     */
    add_theme_support( 'custom-background',
        array(
            'default-color' => 'e7f3d3',
            'default-image' => '%1$s/asset/img/body.jpg',
        )
    );

    /**
     * This theme allows users to set a custom header.
     */
    add_theme_support( 'custom-header', apply_filters( 'theme_custom_header_args', array(
        'default-text-color'     => 'fff',
        'default-image'          => get_template_directory_uri() . '/asset/img/header.jpg',
        'width'                  => 1260,
        'height'                 => 240,
        'flex-height'            => true,
    ) ) );

    /**
     * Add support for featured content.
     */
    add_theme_support( 'featured-content', array(
        'filter'     => 'theme_get_featured_posts',
        'max_posts'  => 20,
    ) );

    /**
     * Register Menus.
     */
    register_nav_menus( array(
        'primary'   => __( 'Site menu', THEME_TEXT_DOMAIN )
    ) );
}
endif;
add_action( 'after_setup_theme', 'theme_setup' );

/**
 * Register Google font for the Theme.
 *
 * @return string
 */
function theme_font_url()
{
    $font_url = 'http://fonts.googleapis.com/css?family=Open+Sans:400,300,600,700,800';

    return $font_url;
}

/**
 * Enqueue scripts and styles for the front end.
 */
function theme_front_enqueues()
{
    wp_enqueue_style( 'theme-google-font', theme_font_url(), array(), null );
    wp_enqueue_style( 'normalize-style',   get_template_directory_uri() . '/vendor/normalize-3.0.2.min.css', array(), '3.0.2' );
    wp_enqueue_style( 'reset-style',       get_template_directory_uri() . '/asset/css/reset.css', array(), '20150101' );
    wp_enqueue_style( 'theme-style',       get_template_directory_uri() . '/asset/css/main.css', array(), '20150101' );
    wp_enqueue_script( 'modernizr',        get_template_directory_uri() . '/vendor/modernizr-custom.2.8.3.min.js', array(), '2.8.3' );
    wp_enqueue_script( 'theme-script',     get_template_directory_uri() . '/asset/js/main.js', array( 'jquery' ), '20150101', true );

    if ( theme_has_featured_posts( 2 ) ) {
        wp_enqueue_script( 'sticky', get_template_directory_uri() . '/asset/js/sticky.js', array( 'jquery' ), '20150101', true );
    }

    if ( is_singular() && comments_open() && get_option( 'thread_comments' ) ) {
        wp_enqueue_script( 'comment-reply' );
    }
}
add_action( 'wp_enqueue_scripts', 'theme_front_enqueues' );

/**
 * Enqueue scripts and styles for the admin screen.
 */
function theme_admin_enqueues()
{
    wp_enqueue_style( 'theme-google-font', theme_font_url(), array(), null );
}
add_action( 'admin_print_scripts-appearance_page_custom-header', 'theme_admin_enqueues' );

/**
 * Register widget areas.
 */
function theme_widgets_init()
{
    register_sidebar( array(
        'name'          => __( 'Primary Sidebar', THEME_TEXT_DOMAIN ),
        'id'            => 'sidebar-1',
        'description'   => __( 'Appears in the footer section of the site.', THEME_TEXT_DOMAIN ),
        'before_widget' => '<aside id="%1$s" class="widget %2$s">',
        'after_widget'  => '</aside>',
        'before_title'  => '<h3 class="widget-title">',
        'after_title'   => '</h3>',
    ) );
}
add_action( 'widgets_init', 'theme_widgets_init' );

/**
 * Getter function for Featured Content Plugin.
 *
 * @return array An array of WP_Post objects.
 */
function theme_get_featured_posts()
{
    /**
     * Filter the featured posts to return in the Theme.
     *
     * @param array|bool $posts Array of featured posts, otherwise false.
     */

    return apply_filters( 'theme_get_featured_posts', array() );
}

/**
 * A helper conditional function that returns a boolean value.
 *
 * @return bool Whether there are featured posts.
 */
function theme_has_featured_posts( $minimum = 1 )
{
    if ( is_paged() )
        return false;

    $minimum = absint( $minimum );
    $featured_posts = apply_filters( 'theme_get_featured_posts', array() );

    if ( ! is_array( $featured_posts ) )
        return false;

    if ( $minimum > count( $featured_posts ) )
        return false;

    return true;
}

/**
 * Custom template tags for this theme.
 */
require get_template_directory() . '/inc/template-tags.php';

/**
 * Add Theme Customizer functionality.
 */
require get_template_directory() . '/inc/customizer.php';

/**
 * Add Featured Content functionality.
 */
if ( ! class_exists( 'Featured_Content' ) && isset( $GLOBALS['pagenow'] ) && 'plugins.php' !== $GLOBALS['pagenow'] ) {
    require get_template_directory() . '/inc/featured-content.php';
}

/**
 * Add Theme hooks.
 */
require get_template_directory() . '/inc/hooks.php';