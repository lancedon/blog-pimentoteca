<?php
/**
 * The template for displaying all single posts.
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/#single-post
 *
 * @package ajaira
 */

get_header(); ?>

	<div id="primary" class="content-area col-md-9">
		<main id="main" class="site-main" role="main">

		<?php
		while ( have_posts() ) : the_post();

			get_template_part( 'template-parts/content', 'single' );

			the_post_navigation( array(
				'prev_text' => '               
							<span class="meta-nav" aria-hidden="true">' . __( 'Previous', 'ajaira') . '</span>
                              <span class="post-title">%title</span> 
                          ',
                 'next_text' => '
                              <span class="meta-nav" aria-hidden="true">' . __( 'Next', 'ajaira' ) .'</span> 
                              <span class="post-title">%title</span>
                          ',
			) );

			if ( '' !== get_the_author_meta( 'description' ) ) {
				get_template_part( 'template-parts/biography' );
			}

			// If comments are open or we have at least one comment, load up the comment template.
			if ( comments_open() || get_comments_number() ) :
				comments_template();
			endif;

			

		endwhile; // End of the loop.
		?>

		</main><!-- #main -->
	</div><!-- #primary -->

<?php
get_sidebar();
get_footer();
