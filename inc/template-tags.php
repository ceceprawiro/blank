<?php if ( ! defined( 'ABSPATH' ) ) die();

if ( ! function_exists( 'theme_paging_nav' ) ) :
/**
 * Display navigation to next/previous set of posts when applicable.
 *
 * @global WP_Query   $wp_query   WordPress Query object.
 * @global WP_Rewrite $wp_rewrite WordPress Rewrite object.
 */
function theme_paging_nav()
{
    global $wp_query, $wp_rewrite;

    // Don't print empty markup if there's only one page.
    if ( $wp_query->max_num_pages < 2 ) {
        return;
    }

    $paged        = get_query_var( 'paged' ) ? intval( get_query_var( 'paged' ) ) : 1;
    $pagenum_link = html_entity_decode( get_pagenum_link() );
    $query_args   = array();
    $url_parts    = explode( '?', $pagenum_link );

    if ( isset( $url_parts[1] ) ) {
        wp_parse_str( $url_parts[1], $query_args );
    }

    $pagenum_link = remove_query_arg( array_keys( $query_args ), $pagenum_link );
    $pagenum_link = trailingslashit( $pagenum_link ) . '%_%';

    $format  = $wp_rewrite->using_index_permalinks() && ! strpos( $pagenum_link, 'index.php' ) ? 'index.php/' : '';
    $format .= $wp_rewrite->using_permalinks() ? user_trailingslashit( $wp_rewrite->pagination_base . '/%#%', 'paged' ) : '?paged=%#%';

    // Set up paginated links.
    $links = paginate_links( array(
        'base'     => $pagenum_link,
        'format'   => $format,
        'total'    => $wp_query->max_num_pages,
        'current'  => $paged,
        'mid_size' => 1,
        'add_args' => array_map( 'urlencode', $query_args ),
        'prev_text' => __( '&larr; Previous', THEME_TEXT_DOMAIN ),
        'next_text' => __( 'Next &rarr;', THEME_TEXT_DOMAIN ),
    ) );

    if ( $links ) :

    ?>
    <nav class="content-pagination navigation">
        <?php echo $links; ?>
    </nav><!-- .content-pagination -->
    <?php
    endif;
}
endif;

if ( ! function_exists( 'theme_post_nav' ) ) :
/**
 * Display navigation to next/previous post when applicable.
 */
function theme_post_nav()
{
    // Don't print empty markup if there's nowhere to navigate.
    $previous = ( is_attachment() ) ? get_post( get_post()->post_parent ) : get_adjacent_post( false, '', true );
    $next     = get_adjacent_post( false, '', false );

    if ( ! $next && ! $previous ) {
        return;
    }

    ?>
    <nav class="content-navigation navigation">
        <?php
        if ( is_attachment() ) :
            previous_post_link( '%link', __( '<span class="meta-nav">Published In</span>%title', THEME_TEXT_DOMAIN ) );
        else :
            previous_post_link( '%link', __( '<span class="meta-nav">Previous Post</span>%title', THEME_TEXT_DOMAIN ) );
            next_post_link( '%link', __( '<span class="meta-nav">Next Post</span>%title', THEME_TEXT_DOMAIN ) );
        endif;
        ?>
    </nav><!-- .content-navigation -->
    <?php
}
endif;


if ( ! function_exists( 'reverse_ancients' ) ) :
/**
 * Reverse the ancestors array
 */
function reverse_ancestors()
{
    /*
    add_filter( 'get_ancestors', function( $ancestors ) use ( $ancestors ) {
        $ancestors = is_array( $ancestors ) ? array_reverse( $ancestors ) : $ancestors;

        return $ancestors;
    } );
    */

   add_filter( 'get_ancestors', function( $ancestors )
   {
        $ancestors = is_array( $ancestors ) ? array_reverse( $ancestors ) : $ancestors;

        return $ancestors;
    } );
}
endif;

/**
 * Display breadcrumb.
 *
 * @global WP_Query Object The post
 * @global WP_Post Object  The query
 */
function breadcrumb($display_at_home = false)
{
    if ( is_home() && ! $display_at_home )
        return;

    reverse_ancestors();

    $breadcrumb = '<ul class="breadcrumb">';
    $breadcrumb .= '<li><a href="'.get_bloginfo( 'url' ).'">'.__( 'Home', THEME_TEXT_DOMAIN ).'</a></li>';

    if ( is_singular() ) {
        global $post;

        if ( is_page() ) {
            $parents = get_ancestors( $post->ID, 'page' );

            foreach ( $parents as $p ) {
                $parent = get_post( $p );
                $parent_link = get_permalink( $p->ID );
                $breadcrumb .= '<li><a href="'.esc_url( $parent_link ).'">'.$parent->post_title.'</a></li>';
            }

            $breadcrumb .= '<li class="active">'.$post->post_title.'</li>';

        } else {
            // get categories and it's parents
            $taxonomy = get_object_taxonomies( $post->post_type )[0];
            $terms = in_array( 'category', array( $taxonomy ) ) ? get_the_category( $post->ID ) : get_the_terms( $post->ID, $taxonomy );
            if ( is_array( $terms ) && count( $terms > 0) ) {
                $term = array_values($terms)[0];
                $parents = get_ancestors( $term->term_id, $term->taxonomy );

                // display parent categories
                foreach ( $parents as $parent ) {
                    $parent_term = get_term( $parent, $taxonomy );
                    $term_link = get_term_link( $parent_term->term_id, $parent_term->taxonomy );
                    $breadcrumb .= '<li><a href="'.esc_url( $term_link ).'">'.$parent_term->name.'</a></li>';
                }

                // display category
                $term_link = get_term_link( $term->term_id, $term->taxonomy );
                $breadcrumb .= '<li><a href="'.esc_url( $term_link ).'">'.$term->name.'</a></li>';
            }

            if ( is_attachment() ) {
                // get post parent
                $parent = get_post( $post->post_parent );
                $parent_link = get_permalink( $parent->ID );
                $breadcrumb .= '<li><a href="'.esc_url( $parent_link ).'">'.$parent->post_title.'</a></li>';
            }

            // display post title
            $breadcrumb .= '<li class="active">'.$post->post_title.'</li>';
        }
    }

    if ( is_category() || is_tax() ) {
        // get parents
        if ( is_category() ) {
            $taxonomy = 'category';
            $term_id = intval( get_query_var( 'cat' ) );
            $term = get_term( $term_id, $taxonomy );

        } else {
            global $wp_query;

            $term = $wp_query->get_queried_object();
            $taxonomy = $term->taxonomy;
            $term_id = $term->term_id;
        }
        $parents = get_ancestors( $term->term_id, $taxonomy );

        // display parents
        foreach ( $parents as $parent ) {
            $parent_term = get_term( $parent, $taxonomy );
            $term_link = get_term_link( $parent_term->term_id, $parent_term->taxonomy );
            $breadcrumb .= '<li><a href="'.esc_url( $term_link ).'">'.$parent_term->name.'</a></li>';
        }

        // display current term
        // $term_link = get_term_link( $term->term_id, $taxonomy );
        $term_link = '';
        $breadcrumb .= is_search() ? '<li><a href="'.esc_url( $term_link ).'">'.$term->name.'</a></li>' : '<li>'.$term->name.'</li>';
    }

    if ( is_year() || is_month() || is_day() ) {
        // $breadcrumb .= is_search() ? // : //
        if ( is_day() ) {
            $breadcrumb .= '<li><a href="'.esc_url( get_year_link( get_the_date( 'Y' ) ) ).'">'.get_the_date( 'Y' ).'</a></li>';
            $breadcrumb .= '<li><a href="'.esc_url( get_month_link( get_the_date( 'Y' ), get_the_date( 'm' ) ) ).'">'.get_the_date( 'm' ).'</a></li>';
            $breadcrumb .= is_search() ? '<li><a href="'.esc_url( get_day_link( get_the_date( 'Y' ), get_the_date( 'm' ), get_the_date( 'd' ) ) ).'">'.get_the_date( 'd' ).'</a></li>' : '<li class="active">'.get_the_date( 'd' ).'</li>';
        }
        if ( is_month() ) {
            $breadcrumb .= '<li><a href="'.esc_url( get_year_link( get_the_date( 'Y' ) ) ).'">'.get_the_date( 'Y' ).'</a></li>';
            $breadcrumb .= is_search() ? '<li><a href="'.esc_url( get_month_link( get_the_date( 'Y' ), get_the_date( 'm' ) ) ).'">'.get_the_date( 'm' ).'</a></li>' : '<li class="active">'.get_the_date( 'm' ).'</li>';
        }
        if ( is_year() ) {
            $breadcrumb .= is_search() ? '<li><a href="'.esc_url( get_year_link( get_the_date( 'Y' ) ) ).'">'.get_the_date( 'Y' ).'</a></li>' : '<li>'.get_the_date( 'Y' ).'</li>';
        }
    }

    if ( is_author() ) {
        $breadcrumb .= is_search() ? '<li><a href="'.esc_url( get_author_posts_url( get_the_author_meta( 'ID' ) ) ).'">'.get_the_author().'</a></li>' : '<li class="active">'.get_the_author().'</li>';
    }

    if ( is_search() ) {
        $keyword = get_query_var( 's' );
        $breadcrumb .= '<li class="active">'.__( 'Search for:', THEME_TEXT_DOMAIN )." $keyword</li>";
    }

    $breadcrumb .= '</ul>';

    echo $breadcrumb;
}

/**
 * Displays a string of linked terms for a post.
 *
 * @param string $separator Text or character to display between each tag link.
 * @param string $before    Text to display before the actual tags are displayed.
 * @param string $before    Text to display after the last tag.
 * @global WP_Post Object The Post itself
 */
function the_cat( $separator, $before = '', $before = '' )
{
    global $post;

    $taxonomy = get_object_taxonomies( $post->post_type )[0];
    the_terms( $post->ID, $taxonomy, $before, $separator, $after );
}