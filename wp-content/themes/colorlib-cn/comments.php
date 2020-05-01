<?php
/**
 * The template for displaying comments.
 *
 * The area of the page that contains both current comments
 * and the comment form.
 *
 * @package sparkling
 */

/*
 * If the current post is protected by a password and
 * the visitor has not yet entered the password we will
 * return early without loading the comments.
 */
if ( post_password_required() ) {
	return;
}
?>

<div id="comments" class="comments-area">

	<?php
	// You can start editing here -- including this comment!
	if ( have_comments() ) :
		?>
		<div class="news_tit3">
			<h2 >

				<?php
				$comments_number = get_comments_number();
				?>
				评论 <span>(<?= $comments_number ?>)</span>
			</h2>
		</div>


		<?php if ( get_comment_pages_count() > 1 && get_option( 'page_comments' ) ) : // Are there comments to navigate through? ?>
		<nav id="comment-nav-above" class="navigation comment-navigation" role="navigation">
			<h2 class="screen-reader-text"><?php esc_html_e( 'Comment navigation', 'sparkling' ); ?></h2>
			<div class="nav-links">

				<div class="nav-previous"><?php previous_comments_link( esc_html__( 'Older Comments', 'sparkling' ) ); ?></div>
				<div class="nav-next"><?php next_comments_link( esc_html__( 'Newer Comments', 'sparkling' ) ); ?></div>

			</div><!-- .nav-links -->
		</nav><!-- #comment-nav-above -->
	<?php endif; // Check for comment navigation. ?>

		<ol class="comment-list">
			<?php
			wp_list_comments(
				array(
					'walker' => new Colorlib_Walker_Comment(),
					'style'       => 'ol',
					'short_ping'  => true,
					'avatar_size' => 50,
				)
			);
			?>
		</ol><!-- .comment-list -->

		<?php if ( get_comment_pages_count() > 1 && get_option( 'page_comments' ) ) : // Are there comments to navigate through? ?>
		<nav id="comment-nav-below" class="navigation comment-navigation" role="navigation">
			<h2 class="screen-reader-text"><?php esc_html_e( 'Comment navigation', 'sparkling' ); ?></h2>
			<div class="nav-links">

				<div class="nav-previous"><?php previous_comments_link( esc_html__( 'Older Comments', 'sparkling' ) ); ?></div>
				<div class="nav-next"><?php next_comments_link( esc_html__( 'Newer Comments', 'sparkling' ) ); ?></div>

			</div><!-- .nav-links -->
		</nav><!-- #comment-nav-below -->
		<?php
	endif; // Check for comment navigation.

	endif; // Check for have_comments().


	// If comments are closed and there are comments, let's leave a little note, shall we?
	if ( ! comments_open() && get_comments_number() && post_type_supports( get_post_type(), 'comments' ) ) :
		?>

		<p class="no-comments"><?php esc_html_e( 'Comments are closed.', 'sparkling' ); ?></p>
		<?php
	endif;

	comment_form(array(
		'submit_button'=>'<input name="%1$s" type="submit" id="%2$s" class="%3$s" value="%4$s" />',
		'comment_field'=>'<p class="comment-form-comment"><label for="comment">' . _x( 'Comment', 'noun' ) . '<span class="required"> *</span></label> <textarea id="comment" name="comment" cols="45" rows="4" maxlength="65525" required="required"></textarea></p>'
	));
	?>

</div><!-- #comments -->
