<?php
/**
 * The template for displaying comments.
 *
 * This is the template that displays the area of the page that contains both the current comments
 * and the comment form.
 *
 * @link https://codex.wordpress.org/Template_Hierarchy
 *
 * @package ajaira
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
	if ( have_comments() ) : ?>
		<h2 class="comments-title">
			<?php
				printf( // WPCS: XSS OK.
					esc_html( _nx( 'One Commnet on &ldquo;%2$s&rdquo;', '%1$s Comments on &ldquo;%2$s&rdquo;', get_comments_number(), 'comments title', 'ajaira' ) ),
					number_format_i18n( get_comments_number() ),
					'<span>' . get_the_title() . '</span>'
				);
			?>
		</h2>

		<?php if ( get_comment_pages_count() > 1 && get_option( 'page_comments' ) ) : // Are there comments to navigate through? ?>
		<nav id="comment-nav-above" class="navigation comment-navigation" role="navigation">
			<h2 class="screen-reader-text"><?php esc_html_e( 'Comment navigation', 'ajaira' ); ?></h2>
			<div class="nav-links">

				<div class="nav-previous"><?php previous_comments_link( esc_html__( 'Older Comments', 'ajaira' ) ); ?></div>
				<div class="nav-next"><?php next_comments_link( esc_html__( 'Newer Comments', 'ajaira' ) ); ?></div>

			</div><!-- .nav-links -->
		</nav><!-- #comment-nav-above -->
		<?php endif; // Check for comment navigation. ?>

		<ol class="comment-list">
			<?php
				wp_list_comments( array(
					'style'      => 'ol',
					'max_depth'  => 4,
					'short_ping' => true,
					'avatar_size' => 50,
				) );
			?>
		</ol><!-- .comment-list -->

		<?php if ( get_comment_pages_count() > 1 && get_option( 'page_comments' ) ) : // Are there comments to navigate through? ?>
		<nav id="comment-nav-below" class="navigation comment-navigation" role="navigation">
			<h2 class="screen-reader-text"><?php esc_html_e( 'Comment navigation', 'ajaira' ); ?></h2>
			<div class="nav-links">

				<div class="nav-previous"><?php previous_comments_link( esc_html__( 'Older Comments', 'ajaira' ) ); ?></div>
				<div class="nav-next"><?php next_comments_link( esc_html__( 'Newer Comments', 'ajaira' ) ); ?></div>

			</div><!-- .nav-links -->
		</nav><!-- #comment-nav-below -->
		<?php
		endif; // Check for comment navigation.

	endif; // Check for have_comments().


	// If comments are closed and there are comments, let's leave a little note, shall we?
	if ( ! comments_open() && get_comments_number() && post_type_supports( get_post_type(), 'comments' ) ) : ?>

		<p class="no-comments"><?php esc_html_e( 'Comments are closed.', 'ajaira' ); ?></p>
	<?php
	endif;

	$custom_comment_field = '<div class="form-group">
            <label for="comment">' . __( 'Comment', 'ajaira' ) . '</label>
            <textarea  required="required" aria-required="true"  class="form-control" rows="5" id="comment" name="comment"></textarea>
          </div>';
     $fields = array(
     		'author'  => '<div class="form-group">
		            <label for="author">' . __('Name: ', 'ajaira') . '<span class="required">*</span></label>
		            <input type="text"  required="required" aria-required="true"  class="form-control" id="usr" name="author">
		          </div>',
		     'email'   => '<div class="form-group">
		            <label for="email">'. __('Email ','ajaira') .'<span class="required">*</span></label>
		            <input type="email" required="required" aria-required="true" class="form-control" maxlength="100"  name="email">
		          </div>',
		     'url'     =>  '<div class="form-group">
		            <label for="url">'. __('Website','ajaira') .'</label>
		            <input type="url" name="url" class="form-control">
		          </div> '
     );

	comment_form(array(
		'fields'			  => apply_filters(
			'comment_form_default_fields', $fields),
		'comment_field'		  => $custom_comment_field,
		'class_submit'        => 'stbmit btn btn-default'
	));
	?>

</div><!-- #comments -->
