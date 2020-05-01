<?php
/**
 * The template for displaying all single posts
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/#single-post
 *
 * @package colorlib-cn
 */

get_header();

set_post_views();

?>

	<div id="primary" class="content-area">
		<main id="main" class="site-main container">
			<div class="row" >
				<div class="col-xl-9 " style="margin-top: 24px">

					<?php
					if ( have_posts() ) :
						/* Start the Loop */
						while ( have_posts() ) :
							the_post();

							/*
                             * Include the Post-Type-specific template for the content.
                             * If you want to override this in a child theme, then include a file
                             * called content-___.php (where ___ is the Post Type name) and that will be used instead.
                             */
							get_template_part( 'template-parts/content','single' );

						endwhile;
						the_posts_pagination(array(
							'prev_text'=>'上一页',
							'next_text'=>'下一页'
						));
					else :

						get_template_part( 'template-parts/content', 'none' );

					endif;
					?>

				</div>
				<div class="col-xl-3" style="margin-top: 24px">
					<?php get_sidebar();?>
				</div>
			</div>

		</main><!-- #main -->
	</div><!-- #primary -->

<?php
get_sidebar();
get_footer();
