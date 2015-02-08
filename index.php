<?php
if ( ! defined( 'ABSPATH' ) ) die();

get_header();
?>

    <div id="page-main">
        <div id="sticky">
            <?php
            if ( theme_has_featured_posts() ) :
                get_template_part( 'sticky' );
            endif;
            ?>
        </div><!-- /#header-image -->

        <nav id="breadcrumb" class="clearfix">
            <?php breadcrumb( true ); ?>
        </nav><!-- /#breadcrumb -->

        <div id="content" class="clearfix">
            <div id="main-content" class="<?php if ( is_active_sidebar( 'sidebar-2' ) ) : echo '.has-sidebar'; endif; ?>">
                <?php
                if ( in_array( get_post_type(), array( 'page', 'post', 'attachment' ) ) ) :
                    $layout = 'list';
                else :
                    $layout = 'grid';
                endif;
                ?>
                <div class="entries <?php echo $layout; ?> clearfix">
                    <?php
                    if ( have_posts() ) :
                        while ( have_posts() ) : the_post();
                            get_template_part( 'content', $layout );

                            if ( is_singular() && ( comments_open() || get_comments_number() ) ) :
                                comments_template();
                            endif;
                        endwhile;
                    endif;
                    ?>
                </div><!-- /.entries.list|grid -->

                <?php theme_paging_nav(); ?>
            </div><!-- /#main-content -->

            <div id="content-sidebar">
                <div class="widget-area">
                    <?php
                    if ( is_active_sidebar( 'sidebar-2' ) ) :
                        dynamic_sidebar( 'sidebar-2' );
                    endif;
                    ?>
                </div>
            </div><!-- /#content-sidebar -->
        </div><!-- /#content -->
    </div>

<?php get_footer(); ?>