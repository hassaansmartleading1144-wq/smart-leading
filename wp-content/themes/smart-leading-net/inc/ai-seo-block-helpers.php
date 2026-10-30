<?php
/**
 * Shared helpers for AI SEO custom Gutenberg blocks.
 *
 * @package Smart_Leading_Net
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Allowed heading tags.
 *
 * @return string[]
 */
function sln_ai_seo_heading_tags() {
	return array( 'h1', 'h2', 'h3', 'h4', 'h5', 'h6', 'p' );
}

/**
 * Sanitize a heading tag.
 *
 * @param string $tag     Requested tag.
 * @param string $default Default tag.
 * @return string
 */
function sln_ai_seo_heading_tag( $tag, $default = 'h2' ) {
	$tag = strtolower( (string) $tag );
	if ( ! in_array( $tag, sln_ai_seo_heading_tags(), true ) ) {
		return $default;
	}
	return $tag;
}

/**
 * KSES for rich headings and descriptions.
 *
 * @param string $html HTML.
 * @return string
 */
function sln_ai_seo_kses( $html ) {
	return wp_kses(
		(string) $html,
		array(
			'span'   => array(
				'class' => true,
			),
			'strong' => array(),
			'em'     => array(),
			'b'      => array(),
			'i'      => array(),
			'br'     => array(),
			'a'      => array(
				'href'   => true,
				'class'  => true,
				'target' => true,
				'rel'    => true,
			),
			'mark'   => array(
				'class' => true,
			),
		)
	);
}

/**
 * Arrow icon for primary buttons.
 *
 * @return string
 */
if ( ! function_exists( 'sln_ai_seo_btn_arrow_svg' ) ) {
	/**
	 * Arrow icon for primary buttons.
	 *
	 * @return string
	 */
	function sln_ai_seo_btn_arrow_svg() {
		return '<span class="sls-btn__arrow" aria-hidden="true"><svg viewBox="0 0 24 24" width="18" height="18" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"><path d="M5 12h14"></path><path d="M13 6l6 6-6 6"></path></svg></span>';
	}
}

/**
 * Render a design-system button.
 *
 * @param array $args Button args.
 * @return string
 */
function sln_ai_seo_render_button( $args ) {
	$label   = isset( $args['label'] ) ? (string) $args['label'] : '';
	$url     = isset( $args['url'] ) ? (string) $args['url'] : '';
	$show    = ! isset( $args['show'] ) || $args['show'];
	$new_tab = ! empty( $args['newTab'] );
	$style   = isset( $args['style'] ) ? (string) $args['style'] : 'primary-arrow';
	$is_btn  = ! empty( $args['button'] );

	if ( ! $show || '' === trim( wp_strip_all_tags( $label ) ) ) {
		return '';
	}

	$variant    = 'primary';
	$show_arrow = true;
	if ( 'secondary' === $style ) {
		$variant    = 'outline';
		$show_arrow = false;
	} elseif ( 'onblue' === $style ) {
		$variant    = 'white';
		$show_arrow = false;
	} elseif ( 'primary' === $style ) {
		$show_arrow = false;
	}

	$attributes = array();
	if ( $new_tab ) {
		$attributes['target'] = '_blank';
		$attributes['rel']    = 'noopener noreferrer';
	}

	if ( ! function_exists( 'sln_render_cta_button' ) ) {
		return '';
	}

	ob_start();
	sln_render_cta_button(
		array(
			'text'       => wp_strip_all_tags( $label ),
			'url'        => $url,
			'type'       => $is_btn ? 'submit' : 'link',
			'variant'    => $variant,
			'attributes' => $attributes,
			'show_arrow' => $show_arrow,
		)
	);
	return (string) ob_get_clean();
}

/**
 * Benefit / price decorative icons.
 *
 * @param string $key Icon key.
 * @param string $class CSS class.
 * @return string
 */
function sln_ai_seo_icon_svg( $key, $class = '' ) {
	$icons = array(
		'pie'      => '<svg class="' . esc_attr( $class ) . '" aria-hidden="true" viewBox="0 0 64 64" fill="none"><circle cx="28" cy="36" r="22" fill="#E2EEFF"></circle><path d="M28 36V14a22 22 0 0 1 22 22H28z" fill="#1F4E9E"></path><path d="M34 8a24 24 0 0 1 22 22" stroke="#F36B32" stroke-width="4" stroke-linecap="round"></path></svg>',
		'network'  => '<svg class="' . esc_attr( $class ) . '" aria-hidden="true" viewBox="0 0 64 64" fill="none"><ellipse cx="32" cy="46" rx="22" ry="12" fill="#E2EEFF"></ellipse><path d="M32 44V12M20 44V24M44 44V24M20 24l-6-6M44 24l6-6" stroke="#1F4E9E" stroke-width="3" stroke-linecap="round"></path><circle cx="32" cy="10" r="4" fill="#F36B32"></circle><circle cx="13" cy="17" r="3.5" fill="#1F4E9E"></circle><circle cx="51" cy="17" r="3.5" fill="#1F4E9E"></circle></svg>',
		'accuracy' => '<svg class="' . esc_attr( $class ) . '" aria-hidden="true" viewBox="0 0 64 64" fill="none"><path d="M10 28c0-11 9-18 21-18s21 7 21 18-9 18-21 18c-3 0-5-.3-8-1l-10 6 2-9c-3-3-5-8-5-14z" fill="#E2EEFF"></path><path d="M22 28l6 6 13-13" stroke="#1F4E9E" stroke-width="4" stroke-linecap="round" stroke-linejoin="round"></path><circle cx="50" cy="46" r="7" fill="#F36B32"></circle></svg>',
		'check'    => '<svg width="44" height="44" viewBox="0 0 48 48" fill="none" aria-hidden="true"><circle cx="20" cy="28" r="14" fill="#E2EEFF"></circle><path d="M10 34l9-9 6 6 13-15" stroke="#1F4E9E" stroke-width="3.5" stroke-linecap="round" stroke-linejoin="round"></path><path d="M30 16h8v8" stroke="#F36B32" stroke-width="3.5" stroke-linecap="round" stroke-linejoin="round"></path></svg>',
		'overlap'  => '<svg aria-hidden="true" width="40" height="28" viewBox="0 0 40 28" fill="none"><circle cx="12" cy="14" r="10.5" stroke="#1F4E9E" stroke-width="2"></circle><circle cx="20" cy="14" r="10.5" stroke="#1F4E9E" stroke-width="2"></circle><circle cx="28" cy="14" r="10.5" stroke="#F36B32" stroke-width="2"></circle></svg>',
		'info'     => '<svg aria-hidden="true" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="#1F4E9E" stroke-width="2" stroke-linecap="round"><circle cx="12" cy="12" r="10"></circle><path d="M12 11v6"></path><path d="M12 7.5v.01"></path></svg>',
		'tick'     => '<svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.6" stroke-linecap="round" stroke-linejoin="round"><path d="M5 12.5l4.5 4.5L19 7.5"></path></svg>',
	);

	return isset( $icons[ $key ] ) ? $icons[ $key ] : '';
}

/**
 * Section background class.
 *
 * @param string $bg Background key.
 * @return string
 */
function sln_ai_seo_section_class( $bg ) {
	$map = array(
		'default' => 'sls-sec',
		'tint'    => 'sls-sec sls-sec--tint',
		'warm'    => 'sls-sec sls-sec--warm',
		'blue'    => 'sls-sec sls-sec--blue',
		'banner'  => 'sls-sec sls-sec--banner',
	);
	return isset( $map[ $bg ] ) ? $map[ $bg ] : 'sls-sec';
}
