<?php

/**
 * Additional code for the child theme goes in here.
 */

add_action( 'wp_enqueue_scripts', 'enqueue_child_styles', 99);
add_filter( 'wp_kses_allowed_html', 'set_custom_allowed_attributes_filter_handbook');

/**
 * Change Author links for IdeaPush pages.
 *
 * @param int $userId The user id.
 *
 * @return string
 */
function idea_push_change_author_link_callback( $userId ) {
	return get_author_posts_url($userId);
}

/**
 * Set attributes that should be allowed for posts filter
 * Adding style to the allowed tags.
 *
 * @param array $allowedposttags Default allowed tags.
 *
 * @return array
 */
function set_custom_allowed_attributes_filter_handbook( $allowedposttags ) {
	// Allow style so that ideaPush works.
	$allowedposttags['style']=[];
	$allowedposttags['div'] = [
		'class'    => true,
		'data'     => true,
		'align'    => true,
		'style'    => true,
	];

	// Allow below tags for carousel slider.
	$allowedposttags['div']['data-render']     = true;
	$allowedposttags['div']['data-attributes'] = true;

	return $allowedposttags;
}

function enqueue_child_styles() {
	$css_creation = filectime(get_stylesheet_directory() . '/style.css');

	wp_enqueue_style( 'child-style', get_stylesheet_directory_uri() . '/style.css', [], $css_creation );
}

const BLOCK_WHITELIST = [
	'post' => [
		// E.g.: 'gpnl/blockquote'.
		'core/code',
		'core/preformatted',
	],
	'page' => [
		'core/code',
		'core/preformatted',
	],
];

const BLOCK_BLACKLIST = [
	'post' => [
		// E.g.: 'planet4-blocks/gallery' or 'core/quote'.
	],
	'page' => [],
];

function set_child_theme_allowed_block_types( $allowed_block_types, $post ) {
	if ( ! empty( BLOCK_WHITELIST[ $post->post_type ] )) {
		$allowed_block_types = array_merge( $allowed_block_types, BLOCK_WHITELIST[$post->post_type] );
	}

	if ( ! empty( BLOCK_BLACKLIST[ $post->post_type ] )) {
		$allowed_block_types = array_filter( $allowed_block_types, function ( $element ) use ( $post ) {
			return !in_array( $element, BLOCK_BLACKLIST[ $post->post_type ] );
		} );
	}

	// array_values is required as array_filter removes indexes from the array.
	return array_values($allowed_block_types);
}

add_filter( 'allowed_block_types', 'set_child_theme_allowed_block_types', 15, 2 );

add_filter( 'wp_nav_menu_items', 'add_extra_items_to_nav_menu', 10, 2 );
function add_extra_items_to_nav_menu( $items, $args ) {
	if( $args->theme_location == 'primary' ){
		$items .= '<li class="nav-item donate-nav-item">';
		$items .= '<a class="btn btn-donate btn-enhanced-donate" href="https://www.greenpeace.org/aotearoa/act/donate/" data-ga-category="Menu Navigation" data-ga-action="Donate" data-ga-label="Homepage">Donate Now</a>';
		$items .= '</li>';
    	return $items;
	}
}