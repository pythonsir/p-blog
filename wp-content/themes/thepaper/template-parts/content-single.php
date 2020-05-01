<?php

 while (have_posts()):
    the_post();


?>


<div class="newscontent">
    <h1 class="news_title"><?php  the_title(); ?></h1>
    <div class="news_about">
        <p style="float: left"><?php  the_author(); ?></p>
        <p style="float: right"><?php  the_time('Y-m-d H:i:s'); ?></p>
    </div>
    <div class="news_txt">
        <?php the_content();?>
    </div>
    <div class="news_keyword">
        <?php

        if(has_tag()):
            the_tags(null,"","") ;
            endif;

        ?>
    </div>
    <?php


      $categorys =  get_the_category();

      $catids = [];



      foreach ($categorys as $cat){

          $catids[] = $cat->term_id;
      }


        $relevantr = new WP_Query(array(
            'category__in' => $catids,
            'posts_per_page' => 3,
            'post__not_in' => array(get_the_ID())
        ));

        if($relevantr->have_posts()):


    ?>
    <div class="news_tit2">
        <h2><?php  esc_html__('related suggestion','thepaper'); ?> </h2>
    </div>
    <div class="ctread_bd">

        <?php

            while ($relevantr->have_posts()):

                $relevantr -> the_post();

            ?>

            <div class="ctread_li">
                <div class="ctread_img">
                    <a href="<?php get_permalink()  ?>" target="_blank">
                        <?php  the_post_thumbnail(); ?>
                    </a>
                </div>
                <div class="ctread_name">
                    <a href="<?php get_permalink()  ?>" target="_blank">
                        <?php  the_title(); ?>
                    </a>
                </div>
            </div>

                <?php
            endwhile;

        ?>

    </div>

    <?php

        endif;

        wp_reset_query();

    comments_template();

    ?>



</div>

     <?php
 endwhile;
?>