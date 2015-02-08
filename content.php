<?php if ( ! defined( 'ABSPATH' ) ) die(); ?>

                    <article class="post">
                        <header class="post-header">
                            <h2 class="post-title"><?php the_title(); ?></h2>
                            <?php if ( ! is_page() ) : ?>
                            <div class="post-date">
                                <?php
                                $time_datetime = get_the_time( 'Y-m-d H:i:s' );
                                $time_display  = get_the_time( 'l F j, Y' );
                                ?>
                                <time datetime="<?php echo $time_datetime; ?>"><?php echo $time_display; ?></time>
                            </div>
                            <?php endif; ?>
                        </header>

                        <div class="post-main">
                            <div class="post-thumbnail"><?php the_post_thumbnail(); ?></div>

                            <?php if ( has_excerpt() ) : ?>
                            <div class="post-excerpt">
                                <?php the_excerpt(); ?>
                            </div>
                            <?php endif; ?>

                            <div class="post-content clearfix">
                                <?php the_content(); ?>
                            </div>
                        </div>
                    </article>
