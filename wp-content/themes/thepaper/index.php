<?php
/**
 * The main template file
 *
 * This is the most generic template file in a WordPress theme
 * and one of the two required files for a theme (the other being style.css).
 * It is used to display a page when nothing more specific matches a query.
 * E.g., it puts together the home page when no home.php file exists.
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package thepaper
 */

get_header();
?>

	<div id="primary" class="content-area">
		<main id="main" class="site-main">

			<div class="thepaper-container d-flex flex-row justify-content-between">
					<div class="thepaper-col-9" >

						<?php


						if (have_posts() ) :

							get_template_part( 'template-parts/content', get_post_type() );

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
//get_sidebar();
get_footer();
