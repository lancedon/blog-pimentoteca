<?php
/**
 * The template for displaying image attachments.
 *
 * @package Misty Lake
 * @since Misty Lake 1.0
 */

get_header();
?>

<div id="primary" class="content-area image-attachment full-width-page">
	<div id="content" class="site-content" role="main">

	<?php while ( have_posts() ) : the_post(); ?>

		<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
			<header class="entry-header">
				<?php the_title( '<h1 class="entry-title">', '</h1>' ); ?>

				<div class="entry-meta">
					<?php
						$metadata = wp_get_attachment_metadata();
						printf( __( 'Published <span class="entry-date"><time class="entry-date" datetime="%1$s" pubdate>%2$s</time></span> at <a href="%3$s" title="Link to full-size image">%4$s &times; %5$s</a> in <a href="%6$s" title="Return to %7$s" rel="gallery">%7$s</a>', 'mistylake' ),
							esc_attr( get_the_date( 'c' ) ),
							esc_html( get_the_date() ),
							wp_get_attachment_url(),
							$metadata['width'],
							$metadata['height'],
							get_permalink( $post->post_parent ),
							get_the_title( $post->post_parent )
						);
					?>
					<?php edit_post_link( __( 'Edit', 'mistylake' ), '<span class="sep"> | </span> <span class="edit-link">', '</span>' ); ?>
				</div><!-- .entry-meta -->

				<nav id="image-navigation" class="site-navigation">
					<span class="previous-image"><?php previous_image_link( false, __( '&larr; Previous', 'mistylake' ) ); ?></span>
					<span class="next-image"><?php next_image_link( false, __( 'Next &rarr;', 'mistylake' ) ); ?></span>
				</nav><!-- #image-navigation -->
			</header><!-- .entry-header -->

			<div class="entry-content">

				<div class="entry-attachment">
					<div class="attachment">
						<?php mistylake_the_attached_image(); ?>
					</div><!-- .attachment -->

					<?php if ( has_excerpt() ) : ?>
					<div class="entry-caption">
						<?php the_excerpt(); ?>
					</div><!-- .entry-caption -->
					<?php endif; ?>
				</div><!-- .entry-attachment -->

				<?php the_content(); ?>
				<?php wp_link_pages( array( 'before' => '<div class="page-links">' . __( 'Pages:', 'mistylake' ), 'after' => '</div>' ) ); ?>

			</div><!-- .entry-content -->

			<footer class="entry-meta">
				<?php if ( comments_open() && pings_open() ) : // Comments and trackbacks open ?>
					<?php printf( __( '<a class="comment-link" href="#respond" title="Post a comment">Post a comment</a> or leave a trackback: <a class="trackback-link" href="%s" title="Trackback URL for your post" rel="trackback">Trackback URL</a>.', 'mistylake' ), get_trackback_url() ); ?>
				<?php elseif ( ! comments_open() && pings_open() ) : // Only trackbacks open ?>
					<?php printf( __( 'Comments are closed, but you can leave a trackback: <a class="trackback-link" href="%s" title="Trackback URL for your post" rel="trackback">Trackback URL</a>.', 'mistylake' ), get_trackback_url() ); ?>
				<?php elseif ( comments_open() && ! pings_open() ) : // Only comments open ?>
					<?php _e( 'Trackbacks are closed, but you can <a class="comment-link" href="#respond" title="Post a comment">post a comment</a>.', 'mistylake' ); ?>
				<?php elseif ( ! comments_open() && ! pings_open() ) : // Comments and trackbacks closed ?>
					<?php _e( 'Both comments and trackbacks are currently closed.', 'mistylake' ); ?>
				<?php endif; ?>
				<?php edit_post_link( __( 'Edit', 'mistylake' ), ' <span class="edit-link">', '</span>' ); ?>
			</footer><!-- .entry-meta -->
		</article><!-- #post-<?php the_ID(); ?> -->

		<?php comments_template(); ?>

	<?php endwhile; // end of the loop. ?>

	</div><!-- #content .site-content -->
</div><!-- #primary .content-area .image-attachment -->

<?php get_footer(); ?>