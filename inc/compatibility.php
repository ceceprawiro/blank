<?php if ( ! defined( 'ABSPATH' ) ) die();

/**
 * Define compatibility message.
 */
$theme_compatibility_message = sprintf( __( 'This theme requires at least WordPress version 3.6. You are running version %s. Please upgrade and try again.', THEME_TEXT_DOMAIN ), $GLOBALS['wp_version'] );

/**
 * Prevent switching to this Theme on old versions of WordPress.
 * Switches to the default theme.
 */
function theme_switch_theme()
{
    switch_theme( WP_DEFAULT_THEME, WP_DEFAULT_THEME );
    unset( $_GET['activated'] );
    add_action( 'admin_notices', 'theme_upgrade_notice' );
}
add_action( 'after_switch_theme', 'theme_switch_theme' );

/**
 * Add message for unsuccessful theme switch.
 *
 * Prints an update nag after an unsuccessful attempt to switch to
 * this Theme on WordPress versions prior to 3.6.
 *
 * @global string compatibility message
 */
function theme_upgrade_notice()
{
    global $theme_compatibility_message;

    printf( '<div class="error"><p>%s</p></div>', $message );
}

/**
 * Prevent the Theme Customizer from being loaded on WordPress versions prior to 3.6.
 *
 * @global string compatibility message
 */
function theme_customize()
{
    global $theme_compatibility_message;

    wp_die( $theme_compatibility_message, '', array( 'back_link' => true, ) );
}
add_action( 'load-customize.php', 'theme_customize' );

/**
 * Prevent the Theme Preview from being loaded on WordPress versions prior to 3.4.
 *
 * @global string compatibility message
 */
function theme_preview()
{
    global $theme_compatibility_message;

    if ( isset( $_GET['preview'] ) ) {
        wp_die( $theme_compatibility_message );
    }
}
add_action( 'template_redirect', 'theme_preview' );