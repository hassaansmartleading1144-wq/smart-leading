<?php
/**
 * Web Development Services landing page — thin presentation helpers.
 *
 * Section defaults and meta getters live in web-development-helpers.php.
 *
 * @package Smart_Leading_Net
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Web Development page button — reuses homepage/SEO sls-btn CTA.
 *
 * @param array $args {
 *     @type string $text    Button label.
 *     @type string $url     Link URL.
 *     @type string $variant primary|secondary|outline|white|ghost.
 *     @type string $class   Extra classes.
 *     @type bool   $arrow   Show arrow circle (default true).
 *     @type string $type    link|button|submit.
 * }
 */
function sln_render_wd_page_button( $args = array() ) {
	$args = wp_parse_args(
		$args,
		array(
			'text'    => '',
			'url'     => '#',
			'variant' => 'primary',
			'class'   => '',
			'arrow'   => true,
			'type'    => 'link',
		)
	);

	if ( '' === $args['text'] ) {
		return;
	}

	$variant = $args['variant'];

	if ( 'ghost' === $variant ) {
		$variant = 'outline';
	}

	if ( ! function_exists( 'sln_render_cta_button' ) ) {
		return;
	}

	sln_render_cta_button(
		array(
			'text'       => $args['text'],
			'url'        => $args['url'],
			'type'       => $args['type'],
			'variant'    => $variant,
			'show_arrow' => ! empty( $args['arrow'] ),
			'class'      => trim( 'web-development-cta ' . $args['class'] ),
		)
	);
}

/**
 * Inline SVG icon for Web Development cards when no media icon is set.
 *
 * @param string $key Icon key.
 * @return string
 */
function sln_wd_fallback_icon( $key = 'generic' ) {
	$icons = array(
		'generic'   => '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" aria-hidden="true"><rect x="3" y="4" width="18" height="14" rx="2"/><path d="M3 10h18"/></svg>',
		'wordpress' => '<svg viewBox="0 0 24 24" fill="currentColor" aria-hidden="true"><path d="M12 2a10 10 0 100 20 10 10 0 000-20zm-1.1 15.6L7.4 9.1h1.9l2.1 5.9 2-5.9h1.8l-3.5 8.5h-1.8zm7.9-1.3A8 8 0 014.8 7.2l3.2 8.8a8 8 0 0010.8.3z"/></svg>',
		'chart'     => '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" aria-hidden="true"><path d="M4 19V5"/><path d="M4 19h16"/><path d="M8 15v-4"/><path d="M12 15V8"/><path d="M16 15v-6"/></svg>',
		'mobile'    => '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" aria-hidden="true"><rect x="7" y="3" width="10" height="18" rx="2"/><path d="M11 18h2"/></svg>',
		'report'    => '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" aria-hidden="true"><path d="M7 3h7l4 4v14H7z"/><path d="M14 3v4h4"/><path d="M10 13h6"/><path d="M10 17h4"/></svg>',
		'personal'  => '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" aria-hidden="true"><circle cx="12" cy="8" r="3.2"/><path d="M5 19c1.5-3.2 4-4.8 7-4.8S17.5 15.8 19 19"/></svg>',
		'integrate' => '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" aria-hidden="true"><circle cx="6" cy="12" r="2.5"/><circle cx="18" cy="7" r="2.5"/><circle cx="18" cy="17" r="2.5"/><path d="M8.3 11.2l7.2-3.4"/><path d="M8.3 12.8l7.2 3.4"/></svg>',
		'search'    => '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" aria-hidden="true"><circle cx="11" cy="11" r="6"/><path d="M20 20l-3.5-3.5"/></svg>',
		'speed'     => '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" aria-hidden="true"><path d="M12 19a8 8 0 10-8-8"/><path d="M12 12l4-2"/></svg>',
		'convert'   => '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" aria-hidden="true"><path d="M4 12h12"/><path d="M12 6l6 6-6 6"/></svg>',
		'star'      => '<svg viewBox="0 0 24 24" fill="currentColor" aria-hidden="true"><path d="M12 3.5l2.6 5.3 5.9.9-4.3 4.1 1 5.8L12 16.8 6.8 19.6l1-5.8L3.5 9.7l5.9-.9L12 3.5z"/></svg>',
		'pen'       => '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" aria-hidden="true"><path d="M12 20h9"/><path d="M16.5 3.5a2.1 2.1 0 013 3L8 18l-4 1 1-4 11.5-11.5z"/></svg>',
		'cart'      => '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" aria-hidden="true"><circle cx="9" cy="20" r="1.5"/><circle cx="18" cy="20" r="1.5"/><path d="M3 4h2l2.4 11.2a2 2 0 001.9 1.5H18a2 2 0 001.9-1.4L22 8H7"/></svg>',
		'puzzle'    => '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" aria-hidden="true"><path d="M12 3a2.5 2.5 0 00-2.5 2.5V7H7a2 2 0 00-2 2v2.5H3.5a2.5 2.5 0 100 5H5V19a2 2 0 002 2h2.5v1.5a2.5 2.5 0 105 0V21H17a2 2 0 002-2v-2.5h1.5a2.5 2.5 0 100-5H19V9a2 2 0 00-2-2h-2.5V5.5A2.5 2.5 0 0012 3z"/></svg>',
	);

	return $icons[ $key ] ?? $icons['generic'];
}

/**
 * Render media icon or fallback SVG.
 *
 * @param int    $attachment_id Attachment ID.
 * @param string $fallback_key  Fallback icon key.
 */
function sln_wd_render_icon( $attachment_id = 0, $fallback_key = 'generic' ) {
	$attachment_id = absint( $attachment_id );

	if ( $attachment_id && function_exists( 'sln_get_attachment_inline_svg' ) ) {
		$svg = sln_get_attachment_inline_svg( $attachment_id, '', true );
		if ( $svg ) {
			echo $svg; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
			return;
		}
	}

	echo sln_wd_fallback_icon( $fallback_key ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
}

/**
 * Theme URI for Web Development placeholder images.
 *
 * @param string $filename File under assets/images/web-development/.
 * @return string
 */
function sln_wd_placeholder_url( $filename ) {
	$url = trailingslashit( SLN_THEME_URI ) . 'assets/images/web-development/' . ltrim( $filename, '/' );

	// Bust browser cache when placeholder assets are replaced in the theme.
	$path = trailingslashit( SLN_THEME_DIR ) . 'assets/images/web-development/' . ltrim( $filename, '/' );
	if ( is_readable( $path ) ) {
		$url = add_query_arg( 'v', (string) filemtime( $path ), $url );
	}

	return $url;
}

/**
 * Resolve attachment URL or placeholder.
 *
 * @param int    $attachment_id Attachment ID.
 * @param string $fallback      Placeholder filename.
 * @param string $size          Image size.
 * @return string
 */
function sln_wd_image_url( $attachment_id, $fallback, $size = 'large' ) {
	$attachment_id = absint( $attachment_id );

	if ( $attachment_id ) {
		$url = wp_get_attachment_image_url( $attachment_id, $size );
		if ( $url ) {
			return $url;
		}
	}

	return sln_wd_placeholder_url( $fallback );
}

/**
 * Render a figure + img for Figma visual areas.
 *
 * @param array $args {
 *     @type int    $attachment_id Media ID.
 *     @type string $fallback      Placeholder filename.
 *     @type string $alt           Alt text.
 *     @type int    $width         Width attribute.
 *     @type int    $height        Height attribute.
 *     @type string $loading       lazy|eager.
 *     @type string $class         Extra figure class.
 *     @type string $img_class     Extra img class.
 * }
 */
function sln_wd_render_figure( $args = array() ) {
	$args = wp_parse_args(
		$args,
		array(
			'attachment_id' => 0,
			'fallback'      => 'placeholder-project.svg',
			'alt'           => '',
			'width'         => 800,
			'height'        => 500,
			'loading'       => 'lazy',
			'class'         => '',
			'img_class'     => '',
		)
	);

	$url = sln_wd_image_url( $args['attachment_id'], $args['fallback'] );
	$figure_class = trim( 'web-development-image ' . $args['class'] );
	$img_class    = trim( 'web-development-image__img ' . $args['img_class'] );
	?>
	<figure class="<?php echo esc_attr( $figure_class ); ?>">
		<img
			src="<?php echo esc_url( $url ); ?>"
			alt="<?php echo esc_attr( $args['alt'] ); ?>"
			loading="<?php echo esc_attr( $args['loading'] ); ?>"
			width="<?php echo esc_attr( (string) $args['width'] ); ?>"
			height="<?php echo esc_attr( (string) $args['height'] ); ?>"
			class="<?php echo esc_attr( $img_class ); ?>"
			decoding="async"
		>
	</figure>
	<?php
}

/**
 * Whether the current request uses the Web Development Services page template.
 *
 * @return bool
 */
function sln_is_web_development_services_page() {
	return is_page_template( 'web-development-page-template.php' );
}

/**
 * Back-compat alias.
 *
 * @return bool
 */
function sln_is_web_development_page() {
	return sln_is_web_development_services_page();
}
