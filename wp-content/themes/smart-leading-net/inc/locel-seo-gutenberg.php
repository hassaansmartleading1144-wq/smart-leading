<?php
/**
 * Register Locale SEO custom Gutenberg blocks from block.json metadata.
 *
 * @package Smart_Leading_Net
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

require_once SLN_THEME_DIR . '/inc/locel-seo-block-helpers.php';
require_once SLN_THEME_DIR . '/inc/locel-seo-block-renders.php';

/**
 * Register block category.
 *
 * @param array $categories Categories.
 * @return array
 */
function sln_locel_seo_block_category( $categories ) {
	foreach ( $categories as $category ) {
		if ( isset( $category['slug'] ) && 'smartleading-locel-seo' === $category['slug'] ) {
			return $categories;
		}
	}

	array_unshift(
		$categories,
		array(
			'slug'  => 'smartleading-locel-seo',
			'title' => __( 'Locale SEO', 'smart-leading-net' ),
			'icon'  => 'location-alt',
		)
	);

	return $categories;
}
add_filter( 'block_categories_all', 'sln_locel_seo_block_category' );

/**
 * Register editor assets and every Locale SEO block.
 */
function sln_locel_seo_register_gutenberg_blocks() {
	$editor_js  = SLN_THEME_DIR . '/assets/js/locel-seo-gutenberg.js';
	$editor_css = SLN_THEME_DIR . '/assets/css/locel-seo-editor.css';
	$front_css  = SLN_THEME_DIR . '/assets/css/locel-seo.css';

	wp_register_script(
		'smartleading-locel-seo-editor',
		SLN_THEME_URI . '/assets/js/locel-seo-gutenberg.js',
		array(
			'wp-blocks',
			'wp-element',
			'wp-block-editor',
			'wp-components',
			'wp-i18n',
			'wp-data',
			'wp-rich-text',
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
		'sln-locel-seo',
		SLN_THEME_URI . '/assets/css/locel-seo.css',
		array( 'sln-variables', 'sln-buttons' ),
		file_exists( $front_css ) ? (string) filemtime( $front_css ) : SLN_THEME_VERSION
	);

	wp_register_style(
		'smartleading-locel-seo-editor-style',
		SLN_THEME_URI . '/assets/css/locel-seo-editor.css',
		array( 'sln-variables', 'sln-buttons', 'sln-locel-seo' ),
		file_exists( $editor_css ) ? (string) filemtime( $editor_css ) : SLN_THEME_VERSION
	);

	$callbacks = array(
		'smartleading/locel-seo-hero'         => 'sln_render_locel_seo_hero',
		'smartleading/locel-seo-map-card'     => 'sln_render_locel_seo_map_card',
		'smartleading/locel-seo-discovery'    => 'sln_render_locel_seo_discovery',
		'smartleading/locel-seo-stop'         => 'sln_render_locel_seo_stop',
		'smartleading/locel-seo-merge-col'    => 'sln_render_locel_seo_merge_col',
		'smartleading/locel-seo-factor'       => 'sln_render_locel_seo_factor',
		'smartleading/locel-seo-challenges'   => 'sln_render_locel_seo_challenges',
		'smartleading/locel-seo-challenge'    => 'sln_render_locel_seo_challenge',
		'smartleading/locel-seo-beyond'       => 'sln_render_locel_seo_beyond',
		'smartleading/locel-seo-point'        => 'sln_render_locel_seo_point',
		'smartleading/locel-seo-approach'     => 'sln_render_locel_seo_approach',
		'smartleading/locel-seo-tile'         => 'sln_render_locel_seo_tile',
		'smartleading/locel-seo-ticket'       => 'sln_render_locel_seo_ticket',
		'smartleading/locel-seo-case'         => 'sln_render_locel_seo_case',
		'smartleading/locel-seo-story-item'   => 'sln_render_locel_seo_story_item',
		'smartleading/locel-seo-process'      => 'sln_render_locel_seo_process',
		'smartleading/locel-seo-stair'        => 'sln_render_locel_seo_stair',
		'smartleading/locel-seo-reporting'    => 'sln_render_locel_seo_reporting',
		'smartleading/locel-seo-funnel-step'  => 'sln_render_locel_seo_funnel_step',
		'smartleading/locel-seo-tech'         => 'sln_render_locel_seo_tech',
		'smartleading/locel-seo-slide'        => 'sln_render_locel_seo_slide',
		'smartleading/locel-seo-stat'         => 'sln_render_locel_seo_stat',
		'smartleading/locel-seo-working'      => 'sln_render_locel_seo_working',
		'smartleading/locel-seo-value'        => 'sln_render_locel_seo_value',
		'smartleading/locel-seo-compare'      => 'sln_render_locel_seo_compare',
		'smartleading/locel-seo-compare-row'  => 'sln_render_locel_seo_compare_row',
		'smartleading/locel-seo-faq'          => 'sln_render_locel_seo_faq',
		'smartleading/locel-seo-faq-item'     => 'sln_render_locel_seo_faq_item',
		'smartleading/locel-seo-audit'        => 'sln_render_locel_seo_audit',
	);

	$base = SLN_THEME_DIR . '/blocks/locel-seo';
	foreach ( $callbacks as $name => $callback ) {
		$slug = str_replace( 'smartleading/locel-seo-', '', $name );
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
add_action( 'init', 'sln_locel_seo_register_gutenberg_blocks' );

/**
 * Load Locale SEO CSS in the editor when the Locale SEO template is selected.
 */
function sln_locel_seo_enqueue_editor_block_styles() {
	if ( ! is_admin() ) {
		return;
	}
	wp_enqueue_style( 'sln-variables' );
	wp_enqueue_style( 'sln-buttons' );
	wp_enqueue_style( 'sln-locel-seo' );
	wp_enqueue_style( 'smartleading-locel-seo-editor-style' );
}
add_action( 'enqueue_block_editor_assets', 'sln_locel_seo_enqueue_editor_block_styles' );
add_action( 'enqueue_block_assets', 'sln_locel_seo_enqueue_editor_block_styles' );
