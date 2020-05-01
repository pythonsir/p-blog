<?php
/**
 * Template part for displaying posts
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package colorlib-cn
 */

?>

<div class="row article_item" >
	<div class="col-xl-3" style="margin-bottom: 10px">
		<?php colorlib_cn_post_thumbnail(); ?>
	</div>
	<div class="col-xl-9">
		<div class="row">
			<div class="col-xl-12">
				<div class="_title">
					<h2><a href="<?= esc_url(get_permalink()); ?>"><?php  the_title(); ?></a></h2>
				</div>
				<div class="" >
					<?php  the_excerpt();?>
				</div>
				<div>
					<div class="intro"><p>阅读&nbsp;<span class="read_num"><?php get_post_views(get_the_ID()) ?></span></p> <p>评论&nbsp;<span class="comment_num"><?= get_comments_number(get_the_ID())?></span></p> <p>收藏&nbsp;<span class="collect_num">0</span></p> <p>发布于&nbsp;<span class="collect_num"><?= timeago( get_gmt_from_date(get_the_time('Y-m-d G:i:s')) ); ?></span></p> <p class="admire_num_p" style="display: none;">赞赏&nbsp;<span class="admire_num"></span></p> <div class="tags"></div></div>
				</div>
			</div>

		</div>
	</div>


</div>



