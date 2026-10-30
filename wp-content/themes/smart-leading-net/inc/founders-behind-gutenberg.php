<?php
/**
 * Register Founders Behind custom Gutenberg blocks.
 *
 * @package Smart_Leading_Net
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

require_once SLN_THEME_DIR . '/inc/founders-behind-block-helpers.php';
require_once SLN_THEME_DIR . '/inc/founders-behind-block-renders.php';

/**
 * Register block category.
 *
 * @param array $categories Categories.
 * @return array
 */
function sln_founders_block_category( $categories ) {
	foreach ( $categories as $category ) {
		if ( isset( $category['slug'] ) && 'smartleading-founders' === $category['slug'] ) {
			return $categories;
		}
	}

	array_unshift(
		$categories,
		array(
			'slug'  => 'smartleading-founders',
			'title' => __( 'Founders Behind', 'smart-leading-net' ),
			'icon'  => 'groups',
		)
	);

	return $categories;
}
add_filter( 'block_categories_all', 'sln_founders_block_category' );

/**
 * Register editor assets and Founders Behind blocks.
 */
function sln_founders_register_gutenberg_blocks() {
	$editor_js  = SLN_THEME_DIR . '/assets/js/founders-behind-gutenberg.js';
	$editor_css = SLN_THEME_DIR . '/assets/css/founders-behind-editor.css';
	$front_css  = SLN_THEME_DIR . '/assets/css/founders-behind.css';

	wp_register_script(
		'smartleading-founders-editor',
		SLN_THEME_URI . '/assets/js/founders-behind-gutenberg.js',
		array(
			'wp-blocks',
			'wp-element',
			'wp-block-editor',
			'wp-components',
			'wp-i18n',
		),
		file_exists( $editor_js ) ? (string) filemtime( $editor_js ) : SLN_THEME_VERSION,
		true
	);

	wp_register_style(
		'sln-variables',
		SLN_THEME_URI . '/assets/css/variables.css',
		array(),
		SLN_THEME_VERSION
	);
	wp_register_style(
		'sln-buttons',
		SLN_THEME_URI . '/assets/css/buttons.css',
		array( 'sln-variables' ),
		SLN_THEME_VERSION
	);
	wp_register_style(
		'sln-founders-behind',
		SLN_THEME_URI . '/assets/css/founders-behind.css',
		array( 'sln-variables', 'sln-buttons' ),
		file_exists( $front_css ) ? (string) filemtime( $front_css ) : SLN_THEME_VERSION
	);
	wp_register_style(
		'smartleading-founders-editor-style',
		SLN_THEME_URI . '/assets/css/founders-behind-editor.css',
		array( 'sln-variables', 'sln-buttons', 'sln-founders-behind' ),
		file_exists( $editor_css ) ? (string) filemtime( $editor_css ) : SLN_THEME_VERSION
	);

	$callbacks = array(
		'smartleading/founders-hero'       => 'sln_render_founders_hero',
		'smartleading/founders-profiles'   => 'sln_render_founders_profiles',
		'smartleading/founders-profile'    => 'sln_render_founders_profile',
		'smartleading/founders-origin'     => 'sln_render_founders_origin',
		'smartleading/founders-roles'      => 'sln_render_founders_roles',
		'smartleading/founders-role'       => 'sln_render_founders_role',
		'smartleading/founders-principles' => 'sln_render_founders_principles',
		'smartleading/founders-principle'  => 'sln_render_founders_principle',
		'smartleading/founders-cta'        => 'sln_render_founders_cta',
	);

	$base = SLN_THEME_DIR . '/blocks/founders-behind';
	foreach ( $callbacks as $name => $callback ) {
		$slug = str_replace( 'smartleading/founders-', '', $name );
		$dir  = $base . '/' . $slug;
		if ( ! file_exists( $dir . '/block.json' ) ) {
			continue;
		}
		register_block_type_from_metadata(
			$dir,
			array(
				'render_callback' => $callback,
			)
		);
	}
}
add_action( 'init', 'sln_founders_register_gutenberg_blocks' );
