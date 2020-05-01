<?php
/**
 * The template for displaying search results pages
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/#search-result
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

						get_template_part( 'template-parts/content', 'search');

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
