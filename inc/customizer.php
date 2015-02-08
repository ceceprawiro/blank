<?php if ( ! defined( 'ABSPATH' ) ) die();

/**
 * Implement Theme Customizer additions and adjustments.
 *
 * @param WP_Customize_Manager $wp_customize Theme Customizer object.
 */
function theme_customize_register( $wp_customize )
{
    // Add custom description to Colors and Background sections.
    $wp_customize->get_section( 'colors' )->description           = __( 'Background may only be visible on wide screens.', THEME_TEXT_DOMAIN );
    $wp_customize->get_section( 'background_image' )->description = __( 'Background may only be visible on wide screens.', THEME_TEXT_DOMAIN );

    // Add postMessage support for site title and description.
    $wp_customize->get_setting( 'blogname' )->transport         = 'postMessage';
    $wp_customize->get_setting( 'blogdescription' )->transport  = 'postMessage';
    $wp_customize->get_setting( 'header_textcolor' )->transport = 'postMessage';

    // Rename the label to "Site Title Color" because this only affects the site title in this theme.
    $wp_customize->get_control( 'header_textcolor' )->label = __( 'Site Title Color', THEME_TEXT_DOMAIN );

    // Rename the label to "Display Site Title & Tagline" in order to make this option extra clear.
    $wp_customize->get_control( 'display_header_text' )->label = __( 'Display Site Title &amp; Tagline', THEME_TEXT_DOMAIN );

    // Add the featured content section in case it's not already there.
    $wp_customize->add_section( 'featured_content', array(
        'title'       => __( 'Featured Content', THEME_TEXT_DOMAIN ),
        'description' => sprintf( __( 'Use a <a href="%1$s">tag</a> to feature your posts. If no posts match the tag, <a href="%2$s">sticky posts</a> will be displayed instead.', THEME_TEXT_DOMAIN ), admin_url( '/edit.php?tag=featured' ), admin_url( '/edit.php?show_sticky=1' ) ),
        'priority'    => 130,
    ) );
}
add_action( 'customize_register', 'theme_customize_register' );

/**
 * Bind JS handlers to make Theme Customizer preview reload changes asynchronously.
 */
function theme_customize_preview_js()
{
    // wp_enqueue_script( 'theme_customizer', get_template_directory_uri() . '/js/customizer-preview.js', array( 'customize-preview' ), '20140817', true );
    wp_register_script( 'theme_customizer', get_template_directory_uri() . '/js/customizer-preview.js', array( 'customize-preview' ), '20140817', true );
    wp_localize_script( 'theme_customizer', 'theme_name', THEME_TEXT_DOMAIN );
    wp_enqueue_script( 'theme_customizer' );
}
add_action( 'customize_preview_init', 'theme_customize_preview_js' );