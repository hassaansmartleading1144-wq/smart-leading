<?php
/**
 * Register AI SEO custom Gutenberg blocks from block.json metadata.
 *
 * @package Smart_Leading_Net
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

require_once SLN_THEME_DIR . '/inc/ai-seo-block-helpers.php';
require_once SLN_THEME_DIR . '/inc/ai-seo-block-renders.php';

/**
 * Register block category.
 *
 * @param array $categories Categories.
 * @return array
 */
function sln_ai_seo_block_category( $categories ) {
	$exists = false;
	foreach ( $categories as $category ) {
		if ( isset( $category['slug'] ) && 'smartleading-ai-seo' === $category['slug'] ) {
			$exists = true;
			break;
		}
	}
	if ( $exists ) {
		return $categories;
	}

	array_unshift(
		$categories,
		array(
			'slug'  => 'smartleading-ai-seo',
			'title' => __( 'AI SEO Services', 'smart-leading-net' ),
			'icon'  => 'chart-line',
		)
	);

	return $categories;
}
add_filter( 'block_categories_all', 'sln_ai_seo_block_category' );

/**
 * Register editor assets and every AI SEO block.
 */
function sln_ai_seo_register_gutenberg_blocks() {
	$editor_js  = SLN_THEME_DIR . '/assets/js/ai-seo-gutenberg.js';
	$editor_css = SLN_THEME_DIR . '/assets/css/ai-seo-editor.css';
	$front_css  = SLN_THEME_DIR . '/assets/css/ai-seo.css';

	wp_register_script(
		'smartleading-ai-seo-editor',
		SLN_THEME_URI . '/assets/js/ai-seo-gutenberg.js',
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
		'smartleading-ai-seo-editor-style',
		SLN_THEME_URI . '/assets/css/ai-seo-editor.css',
		array(),
		file_exists( $editor_css ) ? (string) filemtime( $editor_css ) : SLN_THEME_VERSION
	);

	wp_register_style(
		'sln-ai-seo',
		SLN_THEME_URI . '/assets/css/ai-seo.css',
		array(),
		file_exists( $front_css ) ? (string) filemtime( $front_css ) : SLN_THEME_VERSION
	);

	$callbacks = array(
		'smartleading/ai-seo-hero'          => 'sln_render_ai_seo_hero',
		'smartleading/ai-seo-journey-step'  => 'sln_render_ai_seo_journey_step',
		'smartleading/ai-seo-meaning'       => 'sln_render_ai_seo_meaning',
		'smartleading/ai-seo-term'          => 'sln_render_ai_seo_term',
		'smartleading/ai-seo-benefits'      => 'sln_render_ai_seo_benefits',
		'smartleading/ai-seo-benefit-card'  => 'sln_render_ai_seo_benefit_card',
		'smartleading/ai-seo-platforms'     => 'sln_render_ai_seo_platforms',
		'smartleading/ai-seo-chip'          => 'sln_render_ai_seo_chip',
		'smartleading/ai-seo-approach'      => 'sln_render_ai_seo_approach',
		'smartleading/ai-seo-card'          => 'sln_render_ai_seo_card',
		'smartleading/ai-seo-pricing'       => 'sln_render_ai_seo_pricing',
		'smartleading/ai-seo-process'       => 'sln_render_ai_seo_process',
		'smartleading/ai-seo-process-step'  => 'sln_render_ai_seo_process_step',
		'smartleading/ai-seo-measurement'   => 'sln_render_ai_seo_measurement',
		'smartleading/ai-seo-signal-row'    => 'sln_render_ai_seo_signal_row',
		'smartleading/ai-seo-principles'    => 'sln_render_ai_seo_principles',
		'smartleading/ai-seo-principle'     => 'sln_render_ai_seo_principle',
		'smartleading/ai-seo-scope'         => 'sln_render_ai_seo_scope',
		'smartleading/ai-seo-scope-item'    => 'sln_render_ai_seo_scope_item',
		'smartleading/ai-seo-banner'        => 'sln_render_ai_seo_banner',
		'smartleading/ai-seo-faq'           => 'sln_render_ai_seo_faq',
		'smartleading/ai-seo-faq-item'      => 'sln_render_ai_seo_faq_item',
		'smartleading/ai-seo-enquiry'       => 'sln_render_ai_seo_enquiry',
	);

	$base = SLN_THEME_DIR . '/blocks/ai-seo';
	foreach ( $callbacks as $name => $callback ) {
		$slug = str_replace( 'smartleading/ai-seo-', '', $name );
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
add_action( 'init', 'sln_ai_seo_register_gutenberg_blocks' );

/**
 * Load AI SEO CSS in the editor when the AI SEO template is selected.
 */
function sln_ai_seo_enqueue_editor_block_styles() {
	$post_id = isset( $_GET['post'] ) ? absint( $_GET['post'] ) : 0; // phpcs:ignore WordPress.Security.NonceVerification.Recommended
	if ( ! $post_id || SLN_AI_SEO_TEMPLATE !== get_page_template_slug( $post_id ) ) {
		return;
	}
	wp_enqueue_style(
		'sln-variables',
		SLN_THEME_URI . '/assets/css/variables.css',
		array(),
		SLN_THEME_VERSION
	);
	wp_enqueue_style(
		'sln-buttons',
		SLN_THEME_URI . '/assets/css/buttons.css',
		array( 'sln-variables' ),
		SLN_THEME_VERSION
	);
	wp_enqueue_style( 'sln-ai-seo' );
	wp_enqueue_style( 'smartleading-ai-seo-editor-style' );
}
add_action( 'enqueue_block_editor_assets', 'sln_ai_seo_enqueue_editor_block_styles' );
