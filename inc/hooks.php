<?php if ( ! defined( 'ABSPATH' ) ) die();

if ( ! function_exists( 'theme_wp_title' ) ) :
/**
 * Create a nicely formatted and more specific title element text for output
 * in head of document, based on current view.
 *
 * @param string $title Title text for current view.
 * @param string $sep   Optional separator.
 * @return string       The filtered title.
 */
function theme_wp_title( $title, $sep )
{
    global $paged, $page;

    if ( is_feed() ) {
        return $title;
    }

    // Add the site name.
    $title .= get_bloginfo( 'name', 'display' );

    // Add the site description for the home/front page.
    $site_description = get_bloginfo( 'description', 'display' );
    if ( $site_description && ( is_home() || is_front_page() ) ) {
        $title = "$title $sep $site_description";
    }

    // Add a page number if necessary.
    if ( $paged >= 2 || $page >= 2 ) {
        $title = "$title $sep " . sprintf( __( 'Page %s', THEME_TEXT_DOMAIN ), max( $paged, $page ) );
    }

    return $title;
}
add_filter( 'wp_title', 'theme_wp_title', 10, 2 );
endif;

/** Add home to menu */
if ( ! function_exists( 'theme_show_home' ) ) :
function theme_show_home( $args )
{
    $args['show_home'] = true;
    return $args;
}
add_filter( 'wp_page_menu_args', 'theme_show_home' );
endif;

/**
 * Replaces "[...]" (appended to automatically generated excerpts) with something else
 */
if ( ! function_exists( 'theme_excerpt_more' ) && ! is_admin() ) :
function theme_excerpt_more( $more )
{
    $link = sprintf(
        '<a href="%1$s">%2$s</a>',
        esc_url( get_permalink( get_the_ID() ) ),
        __( 'Read more &raquo;', THEME_TEXT_DOMAIN )
    );

    return ' &hellip; ' . $link;
}
add_filter( 'excerpt_more', 'theme_excerpt_more' );
endif;