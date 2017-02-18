<?php
/**
 * ajaira Theme Customizer.
 *
 * @package ajaira
 */

/**
 * Add postMessage support for site title and description for the Theme Customizer.
 *
 * @param WP_Customize_Manager $wp_customize Theme Customizer object.
 */
function ajaira_customize_register( $wp_customize ) {
	$wp_customize->get_setting( 'blogname' )->transport         = 'postMessage';
	$wp_customize->get_setting( 'blogdescription' )->transport  = 'postMessage';
	$wp_customize->get_setting( 'header_textcolor' )->transport = 'postMessage';


	$wp_customize->add_section( 'ajaira_topbar', array(
	     'title'    => esc_attr__( 'Header Settings', 'ajaira' ),
	     'priority' => 40,
	) );

	/*
	Start ajaira Options
	=====================================================
	*/
	$wp_customize->add_section( 'ajaira_social_options', array(
	     'title'    => esc_attr__( 'Header Social Options', 'ajaira' ),
	     'priority' => 50,
	) );
	
	/*
	Social Icons
	=====================================================
	*/
	$socialmedia = array();
	
	$socialmedia[] = array(
	'slug'=>'facebookurl', 
	'default' => '#',
	'label' => __('Facebook URL', 'ajaira')
	);
	$socialmedia[] = array(
	'slug'=>'twitterurl', 
	'default' => '#',
	'label' => __('Twitter URL', 'ajaira')
	);
	$socialmedia[] = array(
	'slug'=>'googleplusurl', 
	'default' => '#',
	'label' => __('Google Plus URL', 'ajaira')
	);
	$socialmedia[] = array(
	'slug'=>'linkedinurl', 
	'default' => '#',
	'label' => __('Linkedin URL', 'ajaira')
	);
	$socialmedia[] = array(
	'slug'=>'instagramurl', 
	'default' => '#',
	'label' => __('Instagram URL', 'ajaira')
	);
	$socialmedia[] = array(
	'slug'=>'youtubeurl', 
	'default' => '#',
	'label' => __('YouTube URL', 'ajaira')
	);
	$socialmedia[] = array(
	'slug'=>'pinteresturl', 
	'default' => '#',
	'label' => __('Pinterest URL', 'ajaira')
	);
	$socialmedia[] = array(
	'slug'=>'tumblrurl', 
	'default' => '#',
	'label' => __('Tumblr URL', 'ajaira')
	);
	$socialmedia[] = array(
	'slug'=>'githuburl', 
	'default' => '#',
	'label' => __('GitHub URL', 'ajaira')
	);
	
	foreach( $socialmedia as $ajaira_theme_options ) {
		// SETTINGS
		$wp_customize->add_setting(
			'ajaira_theme_options_' . $ajaira_theme_options['slug'], array(
				'default' => $ajaira_theme_options['default'],
				'capability'     => 'edit_theme_options',
				'sanitize_callback' => 'esc_url_raw',
				'type'     => 'theme_mod',
			)
		);
		// CONTROLS
		$wp_customize->add_control(
			$ajaira_theme_options['slug'], 
			array('label' => $ajaira_theme_options['label'], 
			'section'    => 'ajaira_social_options',
			'settings' =>'ajaira_theme_options_' . $ajaira_theme_options['slug'],
			)
		);
	}


	// Header social menu color 

	$solicallinkcolors[] = array(
			'slug'    => 'social_link_color',
			'default' => '#D9D9D9',
			'label'   => __('Social Link Color', 'ajaira')
	);

	// Header social menu hover color

	$solicallinkcolors[] = array(
			'slug'    => 'social_link_hover_color',
			'default' => '#000',
			'label'   => __('Social Link Hover Color', 'ajaira')
	);

	// Add settings and control for each color 
	foreach ( $solicallinkcolors as $social_link_color) {

			// settings 
			$wp_customize->add_setting(
				$social_link_color[ 'slug' ], array(
					'default' => $social_link_color['default'],
					'type'   => 'option',
					'sanitize_callback' => 'social_link_color'
				)
			);

			// controls 

			$wp_customize->add_control(new WP_Customize_Color_Control(
				$wp_customize,
				$social_link_color['slug'],
				array(
					'label' => $social_link_color['label'],
					'section' => 'ajaira_social_options',
					'settings' => $social_link_color['slug']
				)
			));

	}


	$wp_customize->add_setting('hide_social_menu', array(
        'default'    => '1',
        'type'       => 'theme_mod',
        'capability' => 'edit_theme_options',
		'sanitize_callback' => 'ajaira_checkbox'
    ) );
	
	$wp_customize->add_control('hide_social_menu', array(
        'label'      => __( 'Show Header Social Menu', 'ajaira' ),
        'section'    => 'ajaira_topbar',
        'settings'   => 'hide_social_menu',
        'type'       => 'checkbox',
    ) );

	$wp_customize->add_setting('hide_search_bar', array(
        'default'    => '1',
        'type'       => 'theme_mod',
        'capability' => 'edit_theme_options',
		'sanitize_callback' => 'ajaira_checkbox'
    ) );
	
	$wp_customize->add_control('hide_search_bar', array(
        'label'      => __( 'Show Search Bar', 'ajaira' ),
        'section'    => 'ajaira_topbar',
        'settings'   => 'hide_search_bar',
        'type'       => 'checkbox',
    ) );

    function ajaira_checkbox( $input ) {
		if ( $input == 1 ) {
			return 1;
		} else {
			return '';
		}
	}


}
add_action( 'customize_register', 'ajaira_customize_register' );

/**
 * Binds JS handlers to make Theme Customizer preview reload changes asynchronously.
 */
function ajaira_customize_preview_js() {
	wp_enqueue_script( 'ajaira_customizer', get_template_directory_uri() . '/js/customizer.js', array( 'customize-preview' ), '20151215', true );
}
add_action( 'customize_preview_init', 'ajaira_customize_preview_js' );




function ajaira_color_primary_register( $wp_customize ) {

}
add_action( 'customize_register', 'ajaira_color_primary_register' );


	// add style to theme 

	function ajaira_header_social_menu_color_scheme() {

		// define colors
		$social_menu_color = get_option( 'social_link_color' );
		$social_menu_hover_color = get_option( 'social_link_hover_color' );

		// add colors

		?>

		<style>
			div.header-social-menu a:link,
			div.header-social-menu a:visited{
				color:<?php echo $social_menu_color; ?>;
			}

			div.header-social-menu a:hover,
			div.header-social-menu a:active{
				color:<?php echo $social_menu_hover_color; ?>;	
			}
		</style>

	<?php }
	add_action( 'wp_head', 'ajaira_header_social_menu_color_scheme' );