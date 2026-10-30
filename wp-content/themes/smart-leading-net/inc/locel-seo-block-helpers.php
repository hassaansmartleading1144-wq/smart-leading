<?php
/**
 * Shared helpers for Locale SEO Gutenberg blocks.
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
function sln_locel_heading_tags() {
	return array( 'h1', 'h2', 'h3', 'h4', 'h5', 'h6', 'p' );
}

/**
 * Sanitize a heading tag.
 *
 * @param string $tag     Requested tag.
 * @param string $default Default tag.
 * @return string
 */
function sln_locel_heading_tag( $tag, $default = 'h2' ) {
	$tag = strtolower( (string) $tag );
	if ( ! in_array( $tag, sln_locel_heading_tags(), true ) ) {
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
function sln_locel_kses( $html ) {
	$html = (string) $html;
	$html = str_replace( array( '\\u0026', 'u0026' ), '&amp;', $html );
	return wp_kses(
		$html,
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
 * Render a theme design-system CTA (same component as homepage / AI SEO).
 *
 * @param array $args Button args.
 * @return string
 */
function sln_locel_render_button( $args ) {
	$label   = isset( $args['label'] ) ? (string) $args['label'] : '';
	$url     = isset( $args['url'] ) ? (string) $args['url'] : '';
	$show    = ! isset( $args['show'] ) || $args['show'];
	$new_tab = ! empty( $args['newTab'] );
	$style   = isset( $args['style'] ) ? (string) $args['style'] : 'primary';
	$is_btn  = ! empty( $args['button'] );
	$block   = ! empty( $args['block'] );
	$arrow   = ! isset( $args['arrow'] ) || $args['arrow'];

	if ( ! $show || '' === trim( wp_strip_all_tags( $label ) ) ) {
		return '';
	}

	if ( ! function_exists( 'sln_render_cta_button' ) ) {
		return '';
	}

	$variant = 'primary';
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
			'type'       => $is_btn ? 'submit' : 'link',
			'variant'    => $variant,
			'class'      => $block ? 'lseo-cta--block' : '',
			'attributes' => $attributes,
			'show_arrow' => $arrow,
		)
	);
	return (string) ob_get_clean();
}

/**
 * Stroke icon helper.
 *
 * @param string $paths Inner SVG markup.
 * @return string
 */
function sln_locel_ic( $paths ) {
	return '<svg class="ic" viewBox="0 0 24 24" aria-hidden="true">' . $paths . '</svg>';
}

/**
 * Named icons used across the page.
 *
 * @param string $key Icon key.
 * @return string
 */
function sln_locel_icon( $key ) {
	$map = array(
		'lock'       => sln_locel_ic( '<rect x="5" y="11" width="14" height="10" rx="2"/><path class="ac" d="M8 11V8a4 4 0 0 1 8 0v3"/>' ),
		'search'     => sln_locel_ic( '<circle cx="11" cy="11" r="7"/><path class="ac" d="m20 20-3.5-3.5"/>' ),
		'pin'        => sln_locel_ic( '<path d="M12 21s-7-6.2-7-11.5A7 7 0 0 1 19 9.5C19 14.8 12 21 12 21z"/><circle class="ac" cx="12" cy="9.5" r="2.5"/>' ),
		'web'        => sln_locel_ic( '<rect x="3" y="4" width="18" height="16" rx="2"/><path d="M3 9h18"/><path class="ac" d="M7 13h6M7 16h4"/>' ),
		'phone'      => sln_locel_ic( '<path d="M5 4h4l2 5-2.5 1.5a11 11 0 0 0 5 5L15 13l5 2v4a2 2 0 0 1-2 2A16 16 0 0 1 3 6a2 2 0 0 1 2-2z"/><path class="ac" d="M15 3a6 6 0 0 1 6 6M15 7a2 2 0 0 1 2 2"/>' ),
		'link'       => sln_locel_ic( '<path d="M10 14a4 4 0 0 0 5.7 0l3-3a4 4 0 0 0-5.7-5.7l-1 1"/><path class="ac" d="M14 10a4 4 0 0 0-5.7 0l-3 3a4 4 0 0 0 5.7 5.7l1-1"/>' ),
		'target'     => sln_locel_ic( '<circle cx="12" cy="12" r="9"/><circle cx="12" cy="12" r="5"/><circle class="acf" cx="12" cy="12" r="2"/>' ),
		'tick'       => sln_locel_ic( '<path d="m5 12.5 4.5 4.5L19 7.5"/>' ),
		'searchx'    => sln_locel_ic( '<circle cx="11" cy="11" r="7"/><path d="m20 20-3.5-3.5"/><path class="ac" d="M8.8 8.8l4.4 4.4M13.2 8.8l-4.4 4.4"/>' ),
		'pinq'       => sln_locel_ic( '<path d="M12 21s-7-6.2-7-11.5A7 7 0 0 1 19 9.5C19 14.8 12 21 12 21z"/><path class="ac" d="M10.2 8a1.9 1.9 0 1 1 2.6 1.8c-.5.2-.8.6-.8 1.2"/><circle class="acf" cx="12" cy="13.4" r=".9"/>' ),
		'pointer'    => sln_locel_ic( '<path d="M5 3l14 6-6 2-2 6z"/><path class="ac" d="m13.5 13.5 5 5"/>' ),
		'chartq'     => sln_locel_ic( '<path d="M4 20h16"/><path d="M7 16v-5M12 16V8"/><path class="ac" d="M15.6 6.8a2 2 0 1 1 2.7 1.9c-.4.2-.7.5-.7 1"/><circle class="acf" cx="17.6" cy="12.6" r=".9"/>' ),
		'play'       => sln_locel_ic( '<path d="M8 5v14l11-7z" fill="currentColor" stroke="none"/>' ),
		'shop'       => sln_locel_ic( '<path d="M4 9l1.5-5h13L20 9"/><path d="M4 9v11h16V9"/><path d="M4 9a2.7 2.7 0 0 0 5.3 0 2.7 2.7 0 0 0 5.4 0 2.7 2.7 0 0 0 5.3 0"/><path class="ac" d="M10 20v-5h4v5"/>' ),
		'searchplus' => sln_locel_ic( '<circle cx="11" cy="11" r="7"/><path d="m20 20-3.5-3.5"/><path class="ac" d="M8 11h6M11 8v6"/>' ),
		'doc'        => sln_locel_ic( '<path d="M14 3H6a1 1 0 0 0-1 1v16a1 1 0 0 0 1 1h12a1 1 0 0 0 1-1V8z"/><path d="M14 3v5h5"/><path class="ac" d="M9 13h6M9 17h6"/>' ),
		'wrench'     => sln_locel_ic( '<path d="M14.7 6.3a4 4 0 0 0-5.4 5.2L3 17.8V21h3.2l6.3-6.3a4 4 0 0 0 5.2-5.4l-2.6 2.6-2.4-.6-.6-2.4z"/><circle class="acf" cx="19" cy="19" r="2"/>' ),
		'list'       => sln_locel_ic( '<path d="M10 6h10M10 12h10M10 18h10"/><path class="ac" d="m3.5 6 1.5 1.5L7.5 5M3.5 12l1.5 1.5L7.5 11M3.5 18l1.5 1.5L7.5 17"/>' ),
		'chat'       => sln_locel_ic( '<path d="M21 12a8 8 0 0 1-11.8 7L4 20l1.1-4.6A8 8 0 1 1 21 12z"/><path class="ac" d="M8.5 10.5h7M8.5 14h4"/>' ),
		'audit'      => sln_locel_ic( '<rect x="4" y="3" width="13" height="17" rx="2"/><path d="M8 8h5M8 12h3"/><circle class="ac" cx="16" cy="16" r="3.5"/><path class="ac" d="m18.6 18.6 2.4 2.4"/>' ),
		'warn'       => sln_locel_ic( '<path d="M12 3.5 2.5 20h19z"/><path d="M12 10v4.5"/><circle cx="12" cy="17.3" r=".7" fill="currentColor"/>' ),
		'clock'      => sln_locel_ic( '<circle cx="12" cy="12" r="9"/><path class="ac" d="M12 7v5l3 2"/>' ),
		'eye'        => sln_locel_ic( '<path d="M2 12s3.6-7 10-7 10 7 10 7-3.6 7-10 7S2 12 2 12z"/><circle class="ac" cx="12" cy="12" r="3"/>' ),
		'form'       => sln_locel_ic( '<rect x="3" y="4" width="18" height="16" rx="2"/><path d="M7 9h10M7 13h10"/><path class="ac" d="M7 17h6"/>' ),
		'checksq'    => sln_locel_ic( '<rect x="3" y="3" width="18" height="18" rx="4"/><path class="ac" d="m8 12.5 3 3 5-6"/>' ),
		'arrow'      => sln_locel_ic( '<path d="M5 12h14M13 6l6 6-6 6"/>' ),
		'priorities' => sln_locel_ic( '<path class="ac" d="M4 6h16"/><path d="M4 12h11M4 18h6"/>' ),
		'clip'       => sln_locel_ic( '<rect x="5" y="4" width="14" height="17" rx="2"/><path d="M9 4V3h6v1"/><path class="ac" d="M9 10h6M9 14h6M9 18h3"/>' ),
		'bars'       => sln_locel_ic( '<path d="M4 20h16"/><path d="M7 16v-4M12 16V8"/><path class="ac" d="M17 16V5"/>' ),
		'plus'       => '<svg viewBox="0 0 14 14" fill="none" aria-hidden="true"><path d="M7 1v12M1 7h12" stroke="currentColor" stroke-width="2.2" stroke-linecap="round"/></svg>',
		'prev'       => sln_locel_ic( '<path d="M19 12H5M11 6l-6 6 6 6"/>' ),
		'next'       => sln_locel_ic( '<path d="M5 12h14M13 6l6 6-6 6"/>' ),
		'dot'        => sln_locel_ic( '<circle cx="12" cy="12" r="3" fill="currentColor" stroke="none"/>' ),
		'info'       => sln_locel_ic( '<circle cx="12" cy="12" r="9"/><path d="M12 11v5"/><circle cx="12" cy="7.8" r=".7" fill="currentColor"/>' ),
	);
	return isset( $map[ $key ] ) ? $map[ $key ] : sln_locel_icon( 'search' );
}

/**
 * Decorative map illustration for the hero.
 *
 * @return string
 */
function sln_locel_map_decoration() {
	return '<svg class="map__streets" viewBox="0 0 600 600" preserveAspectRatio="xMidYMid slice" aria-hidden="true">
			<rect width="600" height="600" fill="#E6EEFA"></rect>
			<g fill="#DBE5F6"><rect x="16" y="76" width="90" height="62" rx="8"></rect><rect x="214" y="10" width="92" height="48" rx="8"></rect><rect x="460" y="166" width="66" height="96" rx="8"></rect><rect x="96" y="300" width="88" height="104" rx="8"></rect><rect x="214" y="436" width="94" height="68" rx="8"></rect><rect x="556" y="296" width="60" height="112" rx="8"></rect></g>
			<rect x="334" y="440" width="94" height="148" rx="16" fill="#DCEFE5"></rect>
			<g fill="none" stroke="#fff" stroke-linecap="round">
				<path d="M-20 64H620M-20 286H620M-20 524H620M86 -20V620M322 -20V620M548 -20V620" stroke-width="8"></path>
				<path d="M-20 152H620M-20 420H620M200 -20V620M444 -20V620" stroke-width="18"></path>
				<path d="M-40 610 640 40" stroke-width="24"></path>
			</g>
			<g fill="none" stroke="#D3DEF2" stroke-width="1.5" stroke-dasharray="10 10"><path d="M-20 152H620M-20 420H620M200 -20V620M444 -20V620M-40 610 640 40"></path></g>
		</svg>
		<svg class="map__route" viewBox="0 0 100 100" preserveAspectRatio="none" aria-hidden="true"><path d="M30 19 C 30 32, 52 30, 58 40 M30 19 C 22 34, 18 38, 24 47 M60 52 C 66 58, 72 58, 73 62 M38 70 C 46 76, 58 70, 72 64 M73 64 L 66 82"></path></svg>
		<div class="map__pin" aria-hidden="true"><i></i><i></i><i></i><svg viewBox="0 0 34 44"><path d="M17 43s15-14.6 15-26A15 15 0 0 0 2 17c0 11.4 15 26 15 26z" fill="#F36B32"></path><circle cx="17" cy="17" r="6" fill="#fff"></circle></svg></div>';
}

/**
 * Factor decorative SVGs.
 *
 * @param string $key relevance|distance|prominence.
 * @return string
 */
function sln_locel_factor_art( $key ) {
	if ( 'distance' === $key ) {
		return '<svg viewBox="0 0 200 64" aria-hidden="true"><circle cx="80" cy="32" r="29" fill="none" stroke="#1F4E9E" stroke-opacity=".3" stroke-width="1.5" stroke-dasharray="4 4"/><circle cx="80" cy="32" r="17" fill="#ECF2FC" stroke="#1F4E9E" stroke-opacity=".4" stroke-width="1.5" stroke-dasharray="4 4"/><path d="M80 40s7-6.6 7-11.6a7 7 0 0 0-14 0C73 33.4 80 40 80 40z" fill="#F36B32"/><circle cx="80" cy="28.5" r="2.6" fill="#fff"/><path d="M96 32h62" stroke="#F36B32" stroke-width="2" stroke-dasharray="2 6" stroke-linecap="round"/><circle cx="170" cy="25" r="6" fill="#1F4E9E"/><path d="M160 44a10 10 0 0 1 20 0" fill="#1F4E9E"/></svg>';
	}
	if ( 'prominence' === $key ) {
		return '<svg viewBox="0 0 200 64" aria-hidden="true"><g fill="#F36B32"><path transform="translate(8 6)" d="M11 0l3.2 7.3 7.8.7-5.9 5.2 1.8 7.7L11 16.8 4.1 20.9 5.9 13.2 0 8l7.8-.7z"/><path transform="translate(38 6)" d="M11 0l3.2 7.3 7.8.7-5.9 5.2 1.8 7.7L11 16.8 4.1 20.9 5.9 13.2 0 8l7.8-.7z"/><path transform="translate(68 6)" d="M11 0l3.2 7.3 7.8.7-5.9 5.2 1.8 7.7L11 16.8 4.1 20.9 5.9 13.2 0 8l7.8-.7z"/><path transform="translate(98 6)" d="M11 0l3.2 7.3 7.8.7-5.9 5.2 1.8 7.7L11 16.8 4.1 20.9 5.9 13.2 0 8l7.8-.7z"/><path transform="translate(128 6)" d="M11 0l3.2 7.3 7.8.7-5.9 5.2 1.8 7.7L11 16.8 4.1 20.9 5.9 13.2 0 8l7.8-.7z"/></g><rect x="8" y="38" width="150" height="7" rx="3.5" fill="#E2EEFF"/><rect x="8" y="51" width="104" height="7" rx="3.5" fill="#E2EEFF"/></svg>';
	}
	return '<svg viewBox="0 0 200 64" aria-hidden="true"><rect x="6" y="14" width="152" height="36" rx="18" fill="#F4F7FF" stroke="#E2EEFF" stroke-width="1.5"/><circle cx="30" cy="31" r="7" fill="none" stroke="#1F4E9E" stroke-width="2"/><path d="m35 36 5 5" stroke="#F36B32" stroke-width="2" stroke-linecap="round"/><rect x="50" y="28" width="78" height="8" rx="4" fill="#E2EEFF"/><circle cx="178" cy="32" r="15" fill="#19B36B"/><path d="m171 32 5 5 9-10" fill="none" stroke="#fff" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"/></svg>';
}

/**
 * Tile mock illustration.
 *
 * @param string $type profile|meters|listings.
 * @return string
 */
function sln_locel_tile_mock( $type ) {
	if ( 'meters' === $type ) {
		return '<div class="mock" aria-hidden="true"><div class="mock__meter"><i></i><b style="--v:86%"></b></div><div class="mock__meter"><i></i><b style="--v:64%"></b></div><div class="mock__meter"><i></i><b style="--v:92%"></b></div><div class="mock__meter"><i></i><b style="--v:72%"></b></div></div>';
	}
	if ( 'listings' === $type ) {
		return '<div class="mock" aria-hidden="true"><div class="mock__row"><i class="mock__sq"></i><div class="mock__bars"><i></i><i></i></div><i class="mock__ok"></i></div><div class="mock__row"><i class="mock__sq"></i><div class="mock__bars"><i></i><i></i></div><i class="mock__ok"></i></div><div class="mock__row"><i class="mock__sq"></i><div class="mock__bars"><i></i><i></i></div><i class="mock__ok"></i></div></div>';
	}
	return '<div class="mock" aria-hidden="true"><div class="mock__row"><i class="mock__sq"></i><div class="mock__bars"><i></i><i></i></div></div><div class="mock__stars"><i></i><i></i><i></i><i></i><i></i></div><div class="mock__bars"><i></i><i></i></div><div class="mock__pills"><i></i><i></i><i></i></div></div>';
}

/**
 * Collect inner WP_Block instances from a parent, with parsed-block fallback.
 *
 * @param WP_Block $block Parent block.
 * @return WP_Block[]
 */
function sln_locel_iter_inner_blocks( $block ) {
	$inners = array();
	if ( ! $block instanceof WP_Block ) {
		return $inners;
	}
	if ( ! empty( $block->inner_blocks ) ) {
		foreach ( $block->inner_blocks as $inner ) {
			if ( $inner instanceof WP_Block ) {
				$inners[] = $inner;
			}
		}
	}
	if ( ! $inners && ! empty( $block->parsed_block['innerBlocks'] ) && is_array( $block->parsed_block['innerBlocks'] ) ) {
		foreach ( $block->parsed_block['innerBlocks'] as $parsed ) {
			$inners[] = new WP_Block( $parsed );
		}
	}
	return $inners;
}

/**
 * Render inner blocks of a given name from a parent block.
 *
 * @param WP_Block $block Parent block.
 * @param string   $name  Inner block name.
 * @return string
 */
function sln_locel_render_named_inners( $block, $name ) {
	$html = '';
	foreach ( sln_locel_iter_inner_blocks( $block ) as $inner ) {
		if ( $inner->name === $name ) {
			$html .= $inner->render();
		}
	}
	return $html;
}

/**
 * Default Beyond Traffic Reel URL.
 *
 * @return string
 */
function sln_locel_default_video_url() {
	return 'https://www.instagram.com/reels/DcTFVBJjw_Y/';
}

/**
 * Instagram shortcode from a Reel, post or TV URL.
 *
 * @param string $url URL.
 * @return string
 */
function sln_locel_instagram_code( $url ) {
	if ( preg_match( '#instagram\.com/(?:reels?|p|tv)/([A-Za-z0-9_-]+)#i', (string) $url, $m ) ) {
		return $m[1];
	}
	return '';
}

/**
 * Canonical Instagram permalink used by the official embed.
 *
 * @param string $code Shortcode.
 * @return string
 */
function sln_locel_instagram_permalink( $code ) {
	return 'https://www.instagram.com/reel/' . rawurlencode( $code ) . '/';
}

/**
 * Convert a video URL into a safe embed or empty string.
 *
 * @param string $url Video URL.
 * @return string
 */
function sln_locel_video_embed( $url ) {
	$url = trim( (string) $url );
	if ( '' === $url ) {
		return '';
	}

	if ( preg_match( '#(?:youtube\.com/watch\?v=|youtu\.be/)([A-Za-z0-9_-]{6,})#', $url, $m ) ) {
		$src = 'https://www.youtube-nocookie.com/embed/' . rawurlencode( $m[1] ) . '?rel=0';
		return '<iframe class="lseo-video__embed" src="' . esc_url( $src ) . '" title="' . esc_attr__( 'Local SEO approach video', 'smart-leading-net' ) . '" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen loading="lazy"></iframe>';
	}

	if ( preg_match( '#vimeo\.com/(?:video/)?(\d+)#', $url, $m ) ) {
		$src = 'https://player.vimeo.com/video/' . rawurlencode( $m[1] );
		return '<iframe class="lseo-video__embed" src="' . esc_url( $src ) . '" title="' . esc_attr__( 'Local SEO approach video', 'smart-leading-net' ) . '" allow="autoplay; fullscreen; picture-in-picture" allowfullscreen loading="lazy"></iframe>';
	}

	if ( preg_match( '/\.(mp4|webm|ogg)(\?|$)/i', $url ) ) {
		return '<video class="lseo-video__embed" controls preload="metadata" src="' . esc_url( $url ) . '"></video>';
	}

	return '';
}
