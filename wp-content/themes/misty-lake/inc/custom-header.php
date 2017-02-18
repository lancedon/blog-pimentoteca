<?php
/**
 * Sample implementation of the Custom Header feature
 * http://codex.wordpress.org/Custom_Headers
 *
 * @package Misty Lake
 * @since Misty Lake 1.0
 */

/**
 * Setup the WordPress core custom header feature.
 *
 * @uses mistylake_admin_header_style()
 * @uses mistylake_admin_header_image()
 *
 * @package Misty Lake
 */
function mistylake_custom_header_setup() {
	add_theme_support( 'custom-header', apply_filters( 'mistylake_custom_header_args', array(
		'default-image'          => '%s/images/header.jpg',
		'default-text-color'     => '265e15',
		'width'                  => 1015,
		'height'                 => 276,
		'flex-height'            => true,
		'header-text'            => true,
		'wp-head-callback'       => 'mistylake_header_style',
		'admin-head-callback'    => 'mistylake_admin_header_style',
		'admin-preview-callback' => 'mistylake_admin_header_image',
	) ) );

	// Default custom headers packaged with the theme. %s is a placeholder for the theme template directory URI.
	register_default_headers( array(
		'lake' => array(
			'url'           => '%s/images/header.jpg',
			'thumbnail_url' => '%s/images/header-thumbnail.jpg',
			'description'   => _x( 'Lake', 'Header image description', 'mistylake' )
		),
	) );
}
add_action( 'after_setup_theme', 'mistylake_custom_header_setup' );

if ( ! function_exists( 'mistylake_header_style' ) ) :
/**
 * Styles the header image and text displayed on the blog
 *
 * @see mistylake_custom_header_setup().
 */
function mistylake_header_style() {
	$header_text_color = get_header_textcolor();

	// If no custom options for text are set, let's bail
	// get_header_textcolor() options: HEADER_TEXTCOLOR is default, hide text (returns 'blank') or any hex value
	if ( HEADER_TEXTCOLOR == $header_text_color ) {
		return;
	}

	// If we get this far, we have custom styles. Let's do this.
	?>
	<style type="text/css">
	<?php
		// Has the text been hidden?
		if ( 'blank' == $header_text_color ) :
	?>
		.site-title,
		.site-description {
			position: absolute;
			clip: rect(1px, 1px, 1px, 1px);
		}
	<?php
		// If the user has set a custom color for the text use that
		else :
	?>
		.site-title a,
		.site-description {
			color: #<?php echo $header_text_color; ?>;
		}
	<?php endif; ?>
	</style>
	<?php
}
endif; // mistylake_header_style

if ( ! function_exists( 'mistylake_admin_header_style' ) ) :
/**
 * Styles the header image displayed on the Appearance > Header admin panel.
 *
 * @see mistylake_custom_header_setup().
 *
 * @since Misty Lake 1.0
 */
function mistylake_admin_header_style() {
?>
	<style type="text/css">
		.appearance_page_custom-header #headimg {
			background: #f9f9f0;
			border: none;
			padding: 10px;
		}
		#headimg img {
			background-color: #fff;
			border: 1px solid rgba( 0, 0, 0, 0.1 );
			margin-top: 1.5em;
			padding: 1em;
		}
		#headimg h1,
		#desc {
		}
		#headimg h1 {
			font-family: 'Droid Serif', Georgia, 'Times New Roman', serif;
			font-size: 37px;
			font-weight: normal;
			line-height: 55.5px;
			margin: 0;
		}
		#headimg h1 a {
			text-decoration: none;
		}
		#desc {
			font-family: "Open Sans", "Helvetica Neue", Helvetica, Arial, sans-serif;
			font-size: 14px;
		}
		#headimg img {
		}
	</style>
<?php
}
endif; // mistylake_admin_header_style

if ( ! function_exists( 'mistylake_admin_header_image' ) ) :
/**
 * Custom header image markup displayed on the Appearance > Header admin panel.
 *
 * @see mistylake_custom_header_setup().
 *
 * @since Misty Lake 1.0
 */
function mistylake_admin_header_image() {
	$header_image = get_header_image();
	$style = sprintf( ' style="color:#%s;"', get_header_textcolor() );
?>
	<div id="headimg">
		<h1 class="displaying-header-text"><a id="name"<?php echo $style; ?> onclick="return false;" href="<?php echo esc_url( home_url( '/' ) ); ?>"><?php bloginfo( 'name' ); ?></a></h1>
		<div class="displaying-header-text" id="desc"<?php echo $style; ?>><?php bloginfo( 'description' ); ?></div>
		<?php if ( ! empty( $header_image ) ) : ?>
		<img src="<?php echo esc_url( $header_image ); ?>" alt="" />
		<?php endif; ?>
	</div>
<?php
}
endif; // mistylake_admin_header_image
