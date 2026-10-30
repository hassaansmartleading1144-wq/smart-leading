<?php
/**
 * Shared helpers for Founders Behind Gutenberg blocks.
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
function sln_founders_heading_tags() {
	return array( 'h1', 'h2', 'h3', 'h4', 'h5', 'h6', 'p' );
}

/**
 * Sanitize a heading tag.
 *
 * @param string $tag     Requested tag.
 * @param string $default Default tag.
 * @return string
 */
function sln_founders_heading_tag( $tag, $default = 'h2' ) {
	$tag = strtolower( (string) $tag );
	if ( ! in_array( $tag, sln_founders_heading_tags(), true ) ) {
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
function sln_founders_kses( $html ) {
	$html = (string) $html;
	$html = str_replace( array( '\\u0026', 'u0026' ), '&amp;', $html );
	return wp_kses(
		$html,
		array(
			'span'   => array( 'class' => true ),
			'strong' => array(),
			'em'     => array(),
			'b'      => array(),
			'i'      => array(),
			'br'     => array(),
			'p'      => array(),
			'a'      => array(
				'href'   => true,
				'class'  => true,
				'target' => true,
				'rel'    => true,
			),
		)
	);
}

/**
 * Whether a section should render on the public page.
 *
 * @param array $attrs Attributes.
 * @return bool
 */
function sln_founders_is_public( $attrs ) {
	if ( ! array_key_exists( 'isPublished', $attrs ) ) {
		return true;
	}
	return ! empty( $attrs['isPublished'] );
}

/**
 * Theme CTA button.
 *
 * @param array $args Button args.
 * @return string
 */
function sln_founders_render_button( $args ) {
	$label   = isset( $args['label'] ) ? (string) $args['label'] : '';
	$url     = isset( $args['url'] ) ? (string) $args['url'] : '';
	$show    = ! isset( $args['show'] ) || $args['show'];
	$new_tab = ! empty( $args['newTab'] );
	$style   = isset( $args['style'] ) ? (string) $args['style'] : 'primary';

	if ( ! $show || '' === trim( wp_strip_all_tags( $label ) ) ) {
		return '';
	}

	if ( ! function_exists( 'sln_render_cta_button' ) ) {
		return '';
	}

	$variant = 'primary';
	$arrow   = true;
	if ( 'outline' === $style ) {
		$variant = 'outline';
		$arrow   = false;
	} elseif ( 'light' === $style ) {
		$variant = 'white';
	}

	$attributes = array();
	if ( $new_tab ) {
		$attributes['target'] = '_blank';
		$attributes['rel']    = 'noopener noreferrer';
	}

	ob_start();
	sln_render_cta_button(
		array(
			'text'       => wp_strip_all_tags( $label ),
			'url'        => $url,
			'type'       => 'link',
			'variant'    => $variant,
			'class'      => '',
			'attributes' => $attributes,
			'show_arrow' => $arrow,
		)
	);
	return (string) ob_get_clean();
}

/**
 * Inner blocks iterator.
 *
 * @param WP_Block $block Parent.
 * @return WP_Block[]
 */
function sln_founders_iter_inner_blocks( $block ) {
	$inners = array();
	if ( $block instanceof WP_Block && ! empty( $block->inner_blocks ) ) {
		foreach ( $block->inner_blocks as $inner ) {
			$inners[] = $inner;
		}
		return $inners;
	}
	if ( $block instanceof WP_Block && ! empty( $block->parsed_block['innerBlocks'] ) && is_array( $block->parsed_block['innerBlocks'] ) ) {
		foreach ( $block->parsed_block['innerBlocks'] as $parsed ) {
			$inners[] = new WP_Block( $parsed );
		}
	}
	return $inners;
}

/**
 * Render named inner blocks.
 *
 * @param WP_Block $block Parent.
 * @param string   $name  Block name.
 * @return string
 */
function sln_founders_render_named_inners( $block, $name ) {
	$html = '';
	foreach ( sln_founders_iter_inner_blocks( $block ) as $inner ) {
		if ( $inner->name === $name ) {
			$html .= $inner->render();
		}
	}
	return $html;
}

/**
 * Responsive image from media library. Empty when no approved asset exists.
 *
 * @param array  $attrs Attributes.
 * @param string $size  Size.
 * @param array  $img_attrs Extra img attributes.
 * @return string
 */
function sln_founders_image( $attrs, $size = 'large', $img_attrs = array() ) {
	$id  = isset( $attrs['imageId'] ) ? absint( $attrs['imageId'] ) : 0;
	$alt = isset( $attrs['imageAlt'] ) ? (string) $attrs['imageAlt'] : '';
	if ( $id ) {
		$img_attrs['alt']      = $alt;
		$img_attrs['loading']  = $img_attrs['loading'] ?? 'lazy';
		$img_attrs['decoding'] = $img_attrs['decoding'] ?? 'async';
		return wp_get_attachment_image( $id, $size, false, $img_attrs );
	}
	return '';
}

/**
 * LinkedIn icon.
 *
 * @return string
 */
function sln_founders_linkedin_icon() {
	return '<svg viewBox="0 0 24 24" fill="currentColor" aria-hidden="true"><path d="M20.45 20.45h-3.56v-5.57c0-1.33-.02-3.04-1.85-3.04-1.85 0-2.14 1.45-2.14 2.94v5.67H9.35V9h3.41v1.56h.05c.48-.9 1.64-1.85 3.37-1.85 3.6 0 4.27 2.37 4.27 5.46v6.28zM5.34 7.43a2.06 2.06 0 1 1 0-4.13 2.06 2.06 0 0 1 0 4.13zM7.12 20.45H3.56V9h3.56v11.45zM22.22 0H1.77C.79 0 0 .77 0 1.73v20.54C0 23.23.79 24 1.77 24h20.45c.98 0 1.78-.77 1.78-1.73V1.73C24 .77 23.2 0 22.22 0z"/></svg>';
}
