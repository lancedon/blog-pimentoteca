<?php
/**
 * Template part for displaying single posts.
 *
 * @link https://codex.wordpress.org/Template_Hierarchy
 *
 * @package brood
 */

?>

<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
	<header class="entry-header">
		<?php brood_entry_cat(); ?>

		<?php the_title( '<h1 class="entry-title">', '</h1>' ); ?>

		<div class="entry-meta">
			<?php brood_posted_on(); ?>
		</div><!-- .entry-meta -->
	</header><!-- .entry-header -->
	<?php
		if( has_post_thumbnail() ){
			printf( '<div class="entry-media">%s</div>', get_the_post_thumbnail() );
		}
	?>
	<div class="entry-content">
<script async src="//pagead2.googlesyndication.com/pagead/js/adsbygoogle.js"></script>
<!-- post-inside -->
<ins class="adsbygoogle"
     style="display:block"
     data-ad-client="ca-pub-3549827791244332"
     data-ad-slot="4703949200"
     data-ad-format="auto"></ins>
<script>
(adsbygoogle = window.adsbygoogle || []).push({});
</script>

		<?php the_content(); ?>
		<?php
			wp_link_pages( array(
				'before' => '<div class="page-links">' . esc_html__( 'Pages:', 'brood' ),
				'after'  => '</div>',
			) );
		?>
	</div><!-- .entry-content -->

	<footer class="entry-footer">
		<?php brood_entry_footer(); ?>
	</footer><!-- .entry-footer -->
</article><!-- #post-## -->
