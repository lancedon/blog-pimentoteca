<?php
/**
 * The template for displaying the footer.
 *
 * Contains the closing of the #content div and all content after.
 *
 * @link https://developer.wordpress.org/themes/basics/template-files/#template-partials
 *
 * @package ajaira
 */

?>

	</div><!-- #content -->

	<footer id="colophon" class="site-footer  container-fluid text-center"  role="contentinfo">
		<div class="row">
      <?php if( has_nav_menu( 'footer-menu' ) || has_nav_menu( 'footer-social-menu' ) ) : ?>
              <div class="footer-top">
                <?php if( has_nav_menu( 'footer-menu') ) : ?>
                  <div class="footer-menu">
                    <?php 
                      wp_nav_menu( array(
                          'theme_location' => 'footer-menu',
                          'container'      => 'ul',
                          'fallback_cb'    => false,
                          'depth'          => 1
                      ));
                    ?>
                  </div> <!--  .footer-menu -->
                <?php endif; ?>
                <?php if( has_nav_menu( 'footer-social-menu') ) : ?>
                    <div class="footer-social-menu">
                      <!-- <ul>
                          <li><a href=""><i class="fa fa-facebook"></i></a></li>
                          <li><a href=""><i class="fa fa-twitter"></i></a></li>
                          <li><a href=""><i class="fa fa fa-code-fork"></i></a></li>
                          <li><a href=""><i class="fa fa fa-feed"></i></a></li>
                          <li><a href=""><i class="fa fa fa-reply"></i></a></li>
                      </ul> -->
                      <?php 
                            wp_nav_menu( array(
                                'theme_location' => 'footer-social-menu',
                                'container'      => 'ul', 
                                'fallback_cb'    => false,
                                'depth'          => 1,
                                'link_before'     => '<span class="screen-reader-text">',
                                'link_after'      => '</span>',
                            ));
                          ?>
                      </div> <!--  .footer-social-menu -->
                <?php endif; ?>
                </div>
      <?php endif; ?>
			<div class="site-info">
				<a href="<?php echo esc_url( __( 'https://wordpress.org/', 'ajaira' ) ); ?>"><?php printf( esc_html__( 'Proudly powered by %s', 'ajaira' ), 'WordPress' ); ?></a>
				<span class="sep"> | </span>
				<?php printf( esc_html__( 'Theme: %1$s by %2$s.', 'ajaira' ), 'ajaira', '<a href="http://mrakib.me/" rel="designer">rakib</a>' ); ?>
			</div><!-- .site-info -->
		</div><!--  .row -->
	</footer><!-- #colophon -->
</div><!-- #page -->

<?php wp_footer(); ?>

</body>
</html>
