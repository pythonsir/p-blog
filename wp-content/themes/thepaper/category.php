<?php
/**
 * Created by PhpStorm.
 * User: python
 * Date: 2018/11/11
 * Time: 下午8:46
 */
get_header();
?>


    <div id="primary" class="content-area">
        <main id="main" class="site-main">

            <div class="thepaper-container d-flex flex-row justify-content-between">
                <div class="thepaper-col-9" >

                    <?php


                    if (have_posts() ) :

                        get_template_part( 'template-parts/content', 'category');

                    else :

                        get_template_part( 'template-parts/content', 'none' );

                    endif;
                    ?>
                </div>
                <div class="thepaper-col-2" style="width: 202px">
                    <div class="row">
                        <?php get_sidebar(); ?>
                    </div>
                </div>
            </div>
        </main><!-- #main -->
    </div><!-- #primary -->



<?php

get_footer();
