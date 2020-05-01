<?php
/**
 * Template part for displaying posts
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package thepaper
 */

if(have_posts()):


    ?>


    <div class="d-flex flex-column ">

        <?php

        while (have_posts()):

             the_post();


            ?>
            <div class="news" style="padding-left: 15px;padding-right: 15px">
                <div class="news_image">
                    <?php  the_post_thumbnail(); ?>
                </div>
                <div class="new-info">
                    <div class="news-title">
                        <h2><a href="<?php echo  get_permalink(); ?>" target="_blank"><?php  the_title(); ?></a></h2>
                    </div>
                    <div class="news_excerpt">
                        <?php   the_excerpt();  ?>
                    </div>
                    <div class="news-time">
                        <span><i class="fa fa-user fa-lg"></i> <?php  the_author() ?></span>
                        <span><i class="fa fa-calendar fa-lg"></i><?php echo timeago( get_gmt_from_date(get_the_time('Y-m-d G:i:s')) ); ?></span>
                        <span><i class="fa fa-eye fa-lg "></i><?php get_post_views(get_the_ID()) ?></span>
                        <span><i class="fa fa-commenting fa-lg"></i><?php echo get_comments_number(get_the_ID())?></span>
                    </div>
                </div>
            </div>
            <?php




        endwhile;


        ?>


    </div>

    <div class="row pagination">


        <?php

        the_posts_pagination(array(
            'prev_text'=> esc_html__('prev_text','thepaper') ,
            'next_text'=>esc_html__('next_text','thepaper'),
        ));

        ?>

    </div>

    <?php

endif;


?>





