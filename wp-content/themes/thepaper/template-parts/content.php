<?php
/**
 * Template part for displaying posts
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package thepaper
 */

?>

		<?php

		// The Query
		$the_query = new WP_Query('meta_key=type&meta_value=index_hot' );



		// The Loop
		if ( $the_query->have_posts() ) {

			while ( $the_query->have_posts() ) {
				$the_query->the_post();

				$index_hot_id = get_the_ID();

				?>
		<div class="d-flex flex-row index_hot">

			<div class="hot-image">
				<?php  the_post_thumbnail(); ?>
			</div>
			<div class="d-flex flex-column hot-info">
				<div class="hot-title">
					<h2><a href="<?php echo get_permalink(); ?>" target="_blank"><?php the_title(); ?></a></h2>
					<p>
						<?php  the_excerpt(); ?>
					</p>
				</div>
				<div class="hot-info-1">
					<span><i class="fa fa-user fa-lg"></i> <?php  the_author() ?></span>
					<span><i class="fa fa-calendar fa-lg"></i><?php echo timeago( get_gmt_from_date(get_the_time('Y-m-d G:i:s')) ); ?></span>
					<span><i class="fa fa-eye fa-lg "></i><?php get_post_views(get_the_ID()) ?></span>
					<span><i class="fa fa-commenting fa-lg"></i><?php echo get_comments_number(get_the_ID())?></span>
					<div class="hot-info-tag" style="background: url('<?php echo get_template_directory_uri(). '/assets/image/sybg.png' ?>') no-repeat right center;"><?php  esc_html__('index hot','thepaper') ?></div>
				</div>

			</div>

		</div>



		<?php

			}
			wp_reset_postdata();
			wp_reset_query();
		}

		?>





	<?php

	$current_page = max(1, get_query_var('paged'));

	$query = new WP_Query(array('post_type' => 'post', 'posts_per_page'=>'-1' ,'post__not_in' => array( $index_hot_id ),'paged' => $current_page ) );

	$total_pages = $query->max_num_pages;

	if($query->have_posts()):


		?>


		<div class="d-flex flex-column news-content">

			<?php

		while ($query->have_posts()):

			$query -> the_post();


				?>
				<div class="news" style="padding-left: 15px;padding-right: 15px">
					<div class="news_image">
						<?php  the_post_thumbnail(); ?>
					</div>
					<div class="new-info">
						<div class="news-title">
							<h2><a href="<?php echo get_permalink(); ?>" target="_blank"><?php  the_title(); ?></a></h2>
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
					'total' =>$total_pages,
					'current' => $current_page
				));

			?>

		</div>

		<?php

			endif;

			wp_reset_postdata();

	    ?>





