<?php
/**
 * Template part for displaying posts.
 *
 * @link https://codex.wordpress.org/Template_Hierarchy
 *
 * @package ajaira
 */

?>

<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
	<header class="entry-header text-center">

		<?php ajaira_categories(); ?>

		<?php
		if ( is_single() ) :
			the_title( '<h1 class="entry-title">', '</h1>' );
		else :
			the_title( '<h2 class="entry-title"><a href="' . esc_url( get_permalink() ) . '" rel="bookmark">', '</a></h2>' );
		endif;

		if ( 'post' === get_post_type() ) : ?>
		<div class="entry-meta">
			<?php ajaira_posted_on(); ?>
		</div><!-- .entry-meta -->
		<?php
		endif; ?>
	</header><!-- .entry-header -->
	<?php 
		if( has_post_thumbnail() ) { ?>
			<div class="post-image">
				<a href="<?php echo esc_url( get_permalink() ); ?>" rel="bookmark" >
					<?php the_post_thumbnail( ); ?>
				</a>
			</div> <!--  .post-image -->		
	<?php	} 	?>
	<div class="entry-content">
		<?php the_excerpt(); ?>
		<?php echo ajaira_read_more_link(); ?>


	</div><!-- .entry-content -->

	<footer class="entry-footer">
		<?php ajaira_entry_footer(); ?>
	</footer><!-- .entry-footer -->
</article><!-- #post-## -->
