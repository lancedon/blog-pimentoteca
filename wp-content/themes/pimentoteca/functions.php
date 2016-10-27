<?php



function DEBUG_LOG($log){

       $msg .= "\n (DEBUG LOG) >>>".$log;
       $msg .= "\n\n";

       file_put_contents( '/var/www/log.txt', $msg, FILE_APPEND );
}


//start here the relative path funcions

//verifica se o servidor atual é o de produção
function is_prod(){
        if($_SERVER['HTTP_HOST'] == 'pimentoteca.com.br')
                return true;
        else
                return false;

}

function rw_remove_root( $url ) {

        if(is_prod())
                return;

        $url = str_replace( 'http://pimentoteca.com.br', $_SERVER['HTTP_HOST'], $url );

        return '' . ltrim( $url, '/' );
}

add_action( 'template_redirect', 'rw_relative_urls' );


function rw_relative_urls() {
    // Don't do anything if:
    // - In feed
    // - In sitemap by WordPress SEO plugin and only do that if is not a prod enviroment
    if ( is_feed() || get_query_var( 'sitemap' )  || is_prod())
        return;


    $filters = array(
        'post_link',
        'post_type_link',
        'page_link',
        'attachment_link',
        'get_shortlink',
        'post_type_archive_link',
        'get_pagenum_link',
        'get_comments_pagenum_link',
        'term_link',
        'search_link',
        'day_link',
        'month_link',
        'year_link',
        'wp_nav_menu_items',
    );

    foreach ( $filters as $filter )
    {
        add_filter( $filter, 'rw_remove_root' );
    }
}

//End here the relative path funcions



/**
** activation theme
**/
//require_once( 'wp-less/wp-less.php' );
function theme_enqueue_styles() {
	wp_enqueue_style( 'parent-style', get_template_directory_uri() . '/style.css' );
    wp_enqueue_style('theme-main', get_stylesheet_directory_uri().'/theme.less');
}
add_action( 'wp_enqueue_scripts', 'theme_enqueue_styles' );


function theme_enqueue_scripts() {
    wp_enqueue_script( 'pimentoteca', get_stylesheet_directory_uri() . '/scripts.js', array( 'jquery' ) );
}
add_action( 'wp_enqueue_scripts', 'theme_enqueue_scripts' );


/* LOAD TRANSLATION PT_BR */
function my_child_theme_setup() {
    load_theme_textdomain( 'brood', get_stylesheet_directory() . '/languages' );
    load_child_theme_textdomain( 'pimentoteca', get_stylesheet_directory() . '/languages' );
}
//add_action( 'after_setup_theme', 'my_child_theme_setup' );


/* ENABLE SHORTCODE TO WIDGET AREAS */
//add_filter('widget_text', 'do_shortcode');



/* Render Infinite Scroll */
function wpc_scroll_render() {
	get_template_part('content', 'posts');
}

function wpc_theme_support() {
	add_theme_support( 'infinite-scroll', array(
	    'type'           => 'scroll',
	    'footer_widgets' => false,
	    'footer'         => 'page',
	    'container'      => 'content',
	    'wrapper'        => true,
	    'render'         => false,
	    'posts_per_page' => false,
	    'infinite_scroll_has_footer_widgets' => false,
	) );
}
add_action('after_setup_theme','wpc_theme_support');
