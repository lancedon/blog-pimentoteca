<?php
/**
 * The header for our theme.
 *
 * This is the template that displays all of the <head> section and everything up until <div id="content">
 *
 * @link https://developer.wordpress.org/themes/basics/template-files/#template-partials
 *
 * @package ajaira
 */

?><!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
<meta charset="<?php bloginfo( 'charset' ); ?>">
<meta name="viewport" content="width=device-width, initial-scale=1">
<link rel="profile" href="http://gmpg.org/xfn/11">
<link rel="pingback" href="<?php bloginfo( 'pingback_url' ); ?>">

<?php wp_head(); ?>
</head>

<body <?php body_class(); ?>>
<div id="page" class="site">
	<a class="skip-link screen-reader-text" href="#content"><?php esc_html_e( 'Skip to content', 'ajaira' ); ?></a>

		<?php if ( get_header_image() ) : ?>

		<header id="masthead" class="site-header" style="background-image: url(<?php header_image(); ?>);background-repeat: no-repeat;background-size: cover;" role="banner">

		<?php else: // End header image check. ?>

		<header id="masthead" class="site-header" role="banner">

		<?php endif; ?>
		<div class="container">
			<div class="site-branding  row text-center">
				<h1 class="site-title"><a href="<?php echo esc_url( home_url( '/' ) ); ?>" rel="home"><?php bloginfo( 'name' ); ?></a>
				</h1>
				<?php
				$description = get_bloginfo( 'description', 'display' );
				if ( $description || is_customize_preview() ) : ?>
					<p class="site-description"><?php echo $description; /* WPCS: xss ok. */ ?></p>
				<?php
				endif; ?>


				<?php
					$hideSocialMenu = get_theme_mod( 'hide_social_menu', '1');
					$hideSearchBar = get_theme_mod( 'hide_search_bar', '1');
					$facebookURL = get_theme_mod('ajaira_theme_options_facebookurl', '#');
					$twitterURL = get_theme_mod('ajaira_theme_options_twitterurl', '#');
					$googleplusURL = get_theme_mod('ajaira_theme_options_googleplusurl', '#');
					$linkedinURL = get_theme_mod('ajaira_theme_options_linkedinurl', '#');
					$instagramURL = get_theme_mod('ajaira_theme_options_instagramurl', '#');
					$youtubeURL = get_theme_mod('ajaira_theme_options_youtubeurl', '#');
					$pinterestURL = get_theme_mod('ajaira_theme_options_pinteresturl', '#');
					$tumblrURL = get_theme_mod('ajaira_theme_options_tumblrurl', '#');
					$githubURL = get_theme_mod('ajaira_theme_options_githuburl', '');
					$emailURL = get_theme_mod('ajaira_theme_options_emailurl', '#');
				?>
		<?php if( $hideSocialMenu == 1 ) : ?>
			<div class="header-social-menu" role="navigation">
				<?php if (!empty($facebookURL)) : ?>
					<a href="<?php echo esc_url($facebookURL); ?>" title="<?php esc_attr_e( 'Facebook', 'ajaira' ); ?>" target="_blank"><i class="fa fa-facebook "><span class="screen-reader-text"><?php esc_html_e( 'Facebook', 'ajaira' ); ?></span></i></a>
				<?php endif; ?>
						
				<?php if (!empty($twitterURL)) : ?>
					<a href="<?php echo esc_url($twitterURL); ?>" title="<?php esc_attr_e( 'Twitter', 'ajaira' ); ?>" target="_blank"><i class="fa fa-twitter "><span class="screen-reader-text"><?php esc_html_e( 'Twitter', 'ajaira' ); ?></span></i></a>
				<?php endif; ?>
						
				<?php if (!empty($googleplusURL)) : ?>
					<a href="<?php echo esc_url($googleplusURL); ?>" title="<?php esc_attr_e( 'Google Plus', 'ajaira' ); ?>" target="_blank"><i class="fa fa-google-plus "><span class="screen-reader-text"><?php esc_html_e( 'Google Plus', 'ajaira' ); ?></span></i></a>
				<?php endif; ?>
						
				<?php if (!empty($linkedinURL)) : ?>
					<a href="<?php echo esc_url($linkedinURL); ?>" title="<?php esc_attr_e( 'Linkedin', 'ajaira' ); ?>" target="_blank"><i class="fa fa-linkedin "><span class="screen-reader-text"><?php esc_html_e( 'Linkedin', 'ajaira' ); ?></span></i></a>
				<?php endif; ?>
						
				<?php if (!empty($instagramURL)) : ?>
					<a href="<?php echo esc_url($instagramURL); ?>" title="<?php esc_attr_e( 'Instagram', 'ajaira' ); ?>" target="_blank"><i class="fa fa-instagram "><span class="screen-reader-text"><?php esc_html_e( 'Instagram', 'ajaira' ); ?></span></i></a>
				<?php endif; ?>
						
				<?php if (!empty($youtubeURL)) : ?>
					<a href="<?php echo esc_url($youtubeURL); ?>" title="<?php esc_attr_e( 'YouTube', 'ajaira' ); ?>" target="_blank"><i class="fa fa-youtube "><span class="screen-reader-text"><?php esc_html_e( 'YouTube', 'ajaira' ); ?></span></i></a>
				<?php endif; ?>
						
				<?php if (!empty($pinterestURL)) : ?>
					<a href="<?php echo esc_url($pinterestURL); ?>" title="<?php esc_attr_e( 'Pinterest', 'ajaira' ); ?>" target="_blank"><i class="fa fa-pinterest "><span class="screen-reader-text"><?php esc_html_e( 'Pinterest', 'ajaira' ); ?></span></i></a>
				<?php endif; ?>
				
				<?php if (!empty($tumblrURL)) : ?>
					<a href="<?php echo esc_url($tumblrURL); ?>" title="<?php esc_attr_e( 'Tumblr', 'ajaira' ); ?>" target="_blank"><i class="fa fa-tumblr "><span class="screen-reader-text"><?php esc_html_e( 'Tumblr', 'ajaira' ); ?></span></i></a>
				<?php endif; ?>
				
				<?php if (!empty($githubURL)) : ?>
					<a href="<?php echo esc_url($githubURL); ?>" title="<?php esc_attr_e( 'GitHub', 'ajaira' ); ?>" target="_blank"><i class="fa fa-github "><span class="screen-reader-text"><?php esc_html_e( 'GitHub', 'ajaira' ); ?></span></i></a>
				<?php endif; ?>

			</div> <!--  .header-social-menu -->

		<?php endif; ?>



			</div><!-- .site-branding -->
		</div>

		<div class="menu-wrapper">
			<div class="container test-menu">
				<nav id="site-navigation" class="main-navigation" role="navigation">
					<div class="menu">
						<?php wp_nav_menu( array( 
						'theme_location' => 'primary',
						'container'  => 'ul',
						'menu_id' => 'primary-menu',
						'menu_class' => 'nav-menu'
						 ) ); ?>
					</div>
				</nav><!-- #site-navigation -->
				<div class="menu-mobile"></div> <!--  .mobile menu -->
			<?php if( $hideSearchBar == 1 ) : ?>
				<div id="top-search">
					<div class="top-search-relative">
						<?php get_search_form(); ?>
						<i class="fa fa-search search-desktop"></i>
						<i class="fa fa-search search-toggle"></i>
					</div>
				</div> <!--  header search -->

				<div class="show-search">
					<?php get_search_form(); ?>
				</div> <!--  responsive search -->
			<?php endif; ?>

			</div> <!--  .container -->
		</div><!--  .menu-wrapper -->
	</header><!-- #masthead -->

	<div id="content" class="site-content container">
