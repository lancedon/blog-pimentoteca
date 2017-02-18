<?php
/**
 * The template used for displaying page content in page.php
 *
 * @package Misty Lake
 * @since Misty Lake 1.0
 */
?>

<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
	<header class="entry-header">
		<?php the_title( '<h1 class="entry-title">', '</h1>' ); ?>
	</header><!-- .entry-header -->

	<div class="entry-content">
		<?php the_content(); ?>
		<?php wp_link_pages( array( 'before' => '<div class="page-links">' . __( 'Pages:', 'mistylake' ), 'after' => '</div>' ) ); ?>

		<?php
			if ( get_theme_mod( 'mistylake_show_subpages' ) ) :

				$sub_pages = wp_list_pages( 'sort_column=menu_order&depth=1&title_li=&echo=0&child_of=' . $id );

				if ( ! empty( $sub_pages ) ) :
		?>
		<div class="sub-pages">
			<p class="sub-page-heading"><?php _e( 'This page has the following sub pages.', 'mistylake' ); ?></p>
			<ul class="sub-page-list"><?php echo $sub_pages; ?></ul>
		</div>
		<?php
				endif;
			endif;
		?>

		<?php edit_post_link( __( 'Edit', 'mistylake' ), '<footer class="entry-meta"><span class="edit-link">', '</span></footer>' ); ?>
	</div><!-- .entry-content -->
</article><!-- #post-## -->
