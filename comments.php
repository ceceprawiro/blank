<?php

if ( post_password_required() )
    return;
?>

                    <div id="comments">
                        <?php if ( have_comments() ) : ?>
                            <h2 class="comments-title">
                                <?php
                                printf(
                                    _n(
                                        'One thought on &ldquo;%2$s&rdquo;',
                                        '%1$s thoughts on &ldquo;%2$s&rdquo;',
                                        get_comments_number(),
                                        THEME_TEXT_DOMAIN
                                    ),
                                    number_format_i18n( get_comments_number() ),
                                    get_the_title()
                                );
                                ?>
                            </h2>

                            <div class="comment-area">
                                <ol>
                                <?php
                                    wp_list_comments( array(
                                        'style'      => 'ol',
                                        'short_ping' => true,
                                        'avatar_size'=> 34,
                                    ) );
                                ?>
                                </ol>
                            </div>

                            <?php if ( get_comment_pages_count() > 1 && get_option( 'page_comments' ) ) : ?>
                            <nav class="comment-navigation navigation">
                                <div class="nav-previous"><?php previous_comments_link( __( '&larr; Older Comments', THEME_TEXT_DOMAIN ) ); ?></div>
                                <div class="nav-next"><?php next_comments_link( __( 'Newer Comments &rarr;', THEME_TEXT_DOMAIN ) ); ?></div>
                            </nav>
                            <?php endif; ?>

                            <?php if ( ! comments_open() ) : ?>
                                <p class="no-comments"><?php _e( 'Comments are closed.', THEME_TEXT_DOMAIN ); ?></p>
                            <?php endif; ?>
                        <?php endif; ?>

                        <?php comment_form(); ?>
                    </div>
