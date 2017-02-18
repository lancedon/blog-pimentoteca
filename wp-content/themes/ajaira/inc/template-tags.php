<?php
/**
 * Custom template tags for this theme.
 *
 * Eventually, some of the functionality here could be replaced by core features.
 *
 * @package ajaira
 */

if ( ! function_exists( 'ajaira_categories') ) :

function ajaira_categories() {
	/* translators: used between list items, there is a space after the comma */
		$categories_list = get_the_category_list( esc_html__( ', ', 'ajaira' ) );
		if ( $categories_list && ajaira_categorized_blog() ) {
			printf( '<span class="cat-links">'. '<i class="fa fa-folder-open" aria-hidden="true"></i>' . esc_html__( '%1$s', 'ajaira' ) . '</span>', $categories_list ); // WPCS: XSS OK.
		}
}

endif;

if ( ! function_exists( 'ajaira_posted_on' ) ) :
/**
 * Prints HTML with meta information for the current post-date/time and author.
 */
function ajaira_posted_on() {
	$time_string = '<time class="entry-date published updated" datetime="%1$s">%2$s</time>';
	if ( get_the_time( 'U' ) !== get_the_modified_time( 'U' ) ) {
		$time_string = '<time class="entry-date published" datetime="%1$s">%2$s</time><time class="updated" datetime="%3$s">%4$s</time>';
	}

	$time_string = sprintf( $time_string,
		esc_attr( get_the_date( 'c' ) ),
		esc_html( get_the_date() ),
		esc_attr( get_the_modified_date( 'c' ) ),
		esc_html( get_the_modified_date() )
	);

	$posted_on = sprintf(
		esc_html_x( ' %s', 'post date', 'ajaira' ),
		'<a href="' . esc_url( get_permalink() ) . '" rel="bookmark">' . $time_string . '</a>'
	);

	$byline = sprintf(
		esc_html_x( 'by %s', 'post author', 'ajaira' ),
		'<span class="author vcard"><a class="url fn n" href="' . esc_url( get_author_posts_url( get_the_author_meta( 'ID' ) ) ) . '">' . esc_html( get_the_author() ) . '</a></span>'
	);

	echo '<span class="posted-on">' . $posted_on . '</span> | <span class="byline"> ' . $byline . '</span>'; // WPCS: XSS OK.

	if ( ! is_single() && ! post_password_required() && ( comments_open() || get_comments_number() ) ) {
		echo '    |    <span class="comments-link">';
		/* translators: %s: post title */
		comments_popup_link( sprintf( wp_kses( __( 'Leave a Comment<span class="screen-reader-text"> on %s</span>', 'ajaira' ), array( 'span' => array( 'class' => array() ) ) ), get_the_title() ) );
		echo '</span>';
	}

}
endif;

if ( ! function_exists( 'ajaira_entry_footer' ) ) :
/**
 * Prints HTML with meta information for the categories, tags and comments.
 */
function ajaira_entry_footer() {
	// Hide category and tag text for pages.
	if ( 'post' === get_post_type() ) {
		
		/* translators: used between list items, there is a space after the comma */
		$tags_list = get_the_tag_list( '', esc_html__( ', ', 'ajaira' ) );
		if ( $tags_list ) {
			printf( '<span class="tags-links">' . '<i class="fa fa-tags" aria-hidden="true"></i>'. esc_html__( '  %1$s', 'ajaira' ) . '</span>', $tags_list ); // WPCS: XSS OK.
		}
	}



	edit_post_link(
		sprintf(
			/* translators: %s: Name of current post */
			esc_html__( 'Edit %s', 'ajaira' ),
			the_title( '<span class="screen-reader-text">"', '"</span>', false )
		),
		'<span class="edit-link">',
		'</span>'
	);
}
endif;


/**
* Read More Link
*/

function ajaira_read_more_link() {
	$read_more_link = sprintf(
		/* translators: %s: Name of current post. */
		wp_kses( __( '<span class="continue-reading"> Continue  reading%s </span> ', 'ajaira' ), array( 'span' => array( 'class' => array() ) ) ),
		the_title( ' <span class="screen-reader-text">"', '"</span>', false )
	);
	$read_more_string =
	'<div class="continue-reading">
		<a class="more-link" href="' . esc_url(get_permalink()) . '" rel="bookmark">' . $read_more_link . '</a>
	</div>';

	return $read_more_string;

}
add_filter( 'the_content_more_link', 'ajaira_read_more_link');

function ajaira_excerpt_more( $more ) {
	return " ...";
}
add_filter('excerpt_more', 'ajaira_excerpt_more');


/**
 * Returns true if a blog has more than 1 category.
 *
 * @return bool
 */
function ajaira_categorized_blog() {
	if ( false === ( $all_the_cool_cats = get_transient( 'ajaira_categories' ) ) ) {
		// Create an array of all the categories that are attached to posts.
		$all_the_cool_cats = get_categories( array(
			'fields'     => 'ids',
			'hide_empty' => 1,
			// We only need to know if there is more than one category.
			'number'     => 2,
		) );

		// Count the number of categories that are attached to the posts.
		$all_the_cool_cats = count( $all_the_cool_cats );

		set_transient( 'ajaira_categories', $all_the_cool_cats );
	}

	if ( $all_the_cool_cats > 1 ) {
		// This blog has more than 1 category so ajaira_categorized_blog should return true.
		return true;
	} else {
		// This blog has only 1 category so ajaira_categorized_blog should return false.
		return false;
	}
}

/**
 * Flush out the transients used in ajaira_categorized_blog.
 */
function ajaira_category_transient_flusher() {
	if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
		return;
	}
	// Like, beat it. Dig?
	delete_transient( 'ajaira_categories' );
}
add_action( 'edit_category', 'ajaira_category_transient_flusher' );
add_action( 'save_post',     'ajaira_category_transient_flusher' );


if ( ! function_exists( 'ajaira_paging_nav' ) ) :
/**
 * Display navigation to next/previous set of posts when applicable.
 * Based on paging nav function from Twenty Fourteen
 */

function ajaira_paging_nav() {
	// Don't print empty markup if there's only one page.
	if ( $GLOBALS['wp_query']->max_num_pages < 2 ) {
		return;
	}

	$paged        = get_query_var( 'paged' ) ? intval( get_query_var( 'paged' ) ) : 1;
	$pagenum_link = html_entity_decode( get_pagenum_link() );
	$query_args   = array();
	$url_parts    = explode( '?', $pagenum_link );

	if ( isset( $url_parts[1] ) ) {
		wp_parse_str( $url_parts[1], $query_args );
	}

	$pagenum_link = remove_query_arg( array_keys( $query_args ), $pagenum_link );
	$pagenum_link = trailingslashit( $pagenum_link ) . '%_%';

	$format  = $GLOBALS['wp_rewrite']->using_index_permalinks() && ! strpos( $pagenum_link, 'index.php' ) ? 'index.php/' : '';
	$format .= $GLOBALS['wp_rewrite']->using_permalinks() ? user_trailingslashit( 'page/%#%', 'paged' ) : '?paged=%#%';

	// Set up paginated links.
	$links = paginate_links( array(
		'base'     => $pagenum_link,
		'format'   => $format,
		'total'    => $GLOBALS['wp_query']->max_num_pages,
		'current'  => $paged,
		'mid_size' => 1,
		'add_args' => array_map( 'urlencode', $query_args ),
		'prev_text' => __( 'Previous', 'ajaira' ),
		'next_text' => __( 'Next', 'ajaira' ),
		'type'      => 'array',
	) );

	if ( $links ) :

		?>
		<nav class="navigation paging-navigation text-center" role="navigation">
			<h1 class="screen-reader-text"><?php _e( 'Posts navigation', 'ajaira' ); ?></h1>

			<ul class="page-numbers pagination pagination-lg">
				
					<?php
						foreach ( $links as $pgl ) {
			        		echo "<li>$pgl</li>";
				    	}
				    ?>

			</ul>

		</nav><!-- .navigation -->
		<?php
	endif;


	}
endif;