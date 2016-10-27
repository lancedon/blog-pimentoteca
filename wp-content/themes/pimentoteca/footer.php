<?php
/**
 * The template for displaying the footer.
 *
 * Contains the closing of the #content div and all content after.
 *
 * @link https://developer.wordpress.org/themes/basics/template-files/#template-partials
 *
 * @package brood
 */

?>

	</div><!-- #content -->
	<footer id="colophon" class="site-footer" role="contentinfo">
		<?php if( is_active_sidebar('footer-left') || is_active_sidebar('footer-center') || is_active_sidebar('footer-right') ) : ?>
		<div class="foo-widgets">
			<div class="container">
				<div class="col-sm-4">
					<?php dynamic_sidebar('footer-left'); ?>
				</div>
				<div class="col-sm-4">
					<?php dynamic_sidebar('footer-center'); ?>
				</div>
				<div class="col-sm-4">
					<?php dynamic_sidebar('footer-right'); ?>
				</div>
			</div><!-- .container -->
		</div>
		<?php endif; ?>
	</footer><!-- #colophon -->
</div><!-- #page -->

<?php wp_footer(); ?>

</body>
</html>
