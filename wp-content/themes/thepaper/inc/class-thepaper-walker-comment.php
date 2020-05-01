<?php


class Thepaper_Walker_Comment extends  Walker_Comment{


    public function  html5_comment( $comment, $depth, $args ) {

        $tag = ( 'div' === $args['style'] ) ? 'div' : 'li';
        ?>

        <<?php echo $tag; ?> id="comment-<?php comment_ID(); ?>" <?php comment_class( $this->has_children ? 'parent' : '', $comment ); ?>>
        <article id="div-comment-<?php comment_ID(); ?>" class="comment-body">
            <footer class="comment-meta">
                <div class="comment-author vcard">
                    <div class="comment-author-image">
                        <?php if ( 0 != $args['avatar_size'] ) echo get_avatar( $comment, $args['avatar_size'] ); ?>
                    </div>
                    <div class="comment-info">
                        <div class="comment-info-1">
                            <?php
                            /* translators: %s: comment author link */
                            printf( __( '%s','thepaper' ),
                                sprintf( '<b class="fn">%s</b>', get_comment_author_link( $comment ) )
                            );
                            ?>
                            <a href="<?php echo esc_url( get_comment_link( $comment, $args ) ); ?>">


                                    <?php
                                    /* translators: 1: comment date, 2: comment time */
                                    printf( __( '%1$s at %2$s','thepaper' ), get_comment_date( '', $comment ), get_comment_time() );
                                    ?>

                            </a>
                            <?php edit_comment_link( __( 'Edit','thepaper' ), '<span class="edit-link">', '</span>' ); ?>
                        </div>
                        <div class="comment-info-2">
                            <?php comment_text(); ?>
                        </div>

                    </div>

                </div><!-- .comment-author -->



                <?php if ( '0' == $comment->comment_approved ) : ?>
                    <p class="comment-awaiting-moderation"><?php _e( 'Your comment is awaiting moderation.','thepaper' ); ?></p>
                <?php endif; ?>
            </footer><!-- .comment-meta -->



            <?php
            comment_reply_link( array_merge( $args, array(
                'add_below' => 'div-comment',
                'depth'     => $depth,
                'max_depth' => $args['max_depth'],
                'before'    => '<div class="reply">',
                'after'     => '</div>'
            ) ) );
            ?>
        </article><!-- .comment-body -->


<?php

    }



}
