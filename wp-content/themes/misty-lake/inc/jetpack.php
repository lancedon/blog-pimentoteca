<?php
/**
 * Jetpack Compatibility File
 * See: http://jetpack.me/
 *
 * @package: Misty_Lake
 */

/**
 * Add theme support for Infinite Scroll.
 * See: http://jetpack.me/support/infinite-scroll/
 */
function mistylake_jetpack_setup() {
	add_theme_support( 'infinite-scroll', array(
		'type'      => 'scroll',
		'container' => 'content',
		'footer'    => 'main',
	) );
}
add_action( 'after_setup_theme', 'mistylake_jetpack_setup' );
