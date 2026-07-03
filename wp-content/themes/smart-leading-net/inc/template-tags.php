<?php
/**
 * Template tags and helpers.
 *
 * @package Smart_Leading_Net
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Replace hardcoded SVG fill/stroke values so icons inherit CSS color.
 *
 * @param string $svg Raw SVG markup.
 * @return string
 */
function sln_inline_svg_use_current_color( $svg ) {
	$svg = preg_replace( '/\sfill=(["\'])(?!none\b)[^"\']*\1/i', ' fill="currentColor"', $svg );
	$svg = preg_replace( '/\sstroke=(["\'])(?!none\b)[^"\']*\1/i', ' stroke="currentColor"', $svg );
	$svg = preg_replace( '/\bfill\s*:\s*(?!none\b)[^;"\'}\s]+/i', 'fill:currentColor', $svg );
	$svg = preg_replace( '/\bstroke\s*:\s*(?!none\b)[^;"\'}\s]+/i', 'stroke:currentColor', $svg );

	return $svg;
}

/**
 * Output an inline SVG from the theme assets/svg directory.
 *
 * @param string $filename        SVG filename.
 * @param string $class             Optional CSS class for the SVG element.
 * @param bool   $preserve_colors   When true, keep original SVG fill/stroke colors.
 * @return string
 */
function sln_get_inline_svg( $filename, $class = '', $preserve_colors = false ) {
	$filepath = SLN_THEME_DIR . '/assets/svg/' . $filename;

	if ( ! file_exists( $filepath ) ) {
		return '';
	}

	$svg = file_get_contents( $filepath );

	if ( false === $svg ) {
		return '';
	}

	if ( ! $preserve_colors ) {
		$svg = sln_inline_svg_use_current_color( $svg );
	}

	if ( $class ) {
		if ( preg_match( '/<svg\b/', $svg ) ) {
			$svg = preg_replace( '/<svg\b/', '<svg class="' . esc_attr( $class ) . '"', $svg, 1 );
		}
	}

	return $svg;
}

/**
 * Echo an inline SVG from the theme assets/svg directory.
 *
 * @param string $filename        SVG filename.
 * @param string $class           Optional CSS class.
 * @param bool   $preserve_colors When true, keep original SVG fill/stroke colors.
 */
function sln_inline_svg( $filename, $class = '', $preserve_colors = false ) {
	echo sln_get_inline_svg( $filename, $class, $preserve_colors ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
}

/**
 * Resolve a theme image URL from assets/images.
 *
 * @param string $filename Image filename (e.g. good-job.webp).
 * @return string
 */
function sln_get_theme_image_uri( $filename ) {
	$filename = ltrim( (string) $filename, '/' );

	if ( '' === $filename ) {
		return '';
	}

	return get_template_directory_uri() . '/assets/images/' . $filename;
}

/**
 * Page templates that share the About Us layout and assets.
 *
 * @return string[]
 */
function sln_get_about_page_templates() {
	return array(
		'about-us-template.php',
		'about-template.php',
	);
}

/**
 * Whether the current page uses the New About For Test template.
 *
 * @return bool
 */
function sln_is_new_about_test_page() {
	return is_page_template( 'new-about-test-template.php' );
}

/**
 * Resolve a published page permalink by slug.
 *
 * @param string $slug Page slug.
 * @return string
 */
function sln_get_page_url_by_slug( $slug ) {
	$page = get_page_by_path( sanitize_title( $slug ), OBJECT, 'page' );

	if ( $page instanceof WP_Post && 'publish' === $page->post_status ) {
		return get_permalink( $page );
	}

	return home_url( '/' . sanitize_title( $slug ) . '/' );
}

/**
 * Whether the current page uses an About-style template.
 *
 * @return bool
 */
function sln_is_about_page_template() {
	return is_page_template( sln_get_about_page_templates() );
}

/**
 * Homepage hero LCP image variants (uploads dir).
 *
 * @return array<int, array{url: string, width: int}>
 */
function sln_get_hero_lcp_image_variants() {
	static $variants = null;

	if ( null !== $variants ) {
		return $variants;
	}

	$variants  = array();
	$dir       = WP_CONTENT_DIR . '/uploads/2026/05';
	$url_base  = trailingslashit( content_url( '/uploads/2026/05' ) );
	$candidates = array(
		'banner_image.webp',
		'banner_image-1536x1297.webp',
		'banner_image-1024x865.webp',
		'banner_image-768x649.webp',
		'banner_image-300x253.webp',
	);

	foreach ( $candidates as $file ) {
		$path = $dir . '/' . $file;

		if ( ! file_exists( $path ) ) {
			continue;
		}

		$width = 860;

		if ( preg_match( '/banner_image-(\d+)x\d+\.webp$/', $file, $matches ) ) {
			$width = (int) $matches[1];
		} else {
			$size = @getimagesize( $path ); // phpcs:ignore WordPress.PHP.NoSilencedErrors.Discouraged

			if ( $size ) {
				$width = (int) $size[0];
			}
		}

		$variants[] = array(
			'url'   => $url_base . rawurlencode( $file ),
			'width' => $width,
		);
	}

	usort(
		$variants,
		static function ( $a, $b ) {
			return $b['width'] <=> $a['width'];
		}
	);

	return $variants;
}

/**
 * Homepage hero LCP image URL (sensible default, not always full-size).
 *
 * @return string
 */
function sln_get_hero_lcp_image_url() {
	$variants = sln_get_hero_lcp_image_variants();

	if ( ! empty( $variants ) ) {
		foreach ( $variants as $variant ) {
			if ( 1024 === $variant['width'] ) {
				return $variant['url'];
			}
		}

		return $variants[0]['url'];
	}

	return trailingslashit( content_url( '/uploads/2026/05' ) ) . rawurlencode( 'banner_image.webp' );
}

/**
 * Homepage hero responsive srcset string.
 *
 * @return string
 */
function sln_get_hero_lcp_image_srcset() {
	$variants = sln_get_hero_lcp_image_variants();

	if ( empty( $variants ) ) {
		return esc_url( sln_get_hero_lcp_image_url() ) . ' 860w';
	}

	$parts = array();

	foreach ( $variants as $variant ) {
		$parts[] = esc_url( $variant['url'] ) . ' ' . $variant['width'] . 'w';
	}

	return implode( ', ', $parts );
}

/**
 * Homepage hero image sizes attribute.
 *
 * @return string
 */
function sln_get_hero_lcp_image_sizes() {
	return '(max-width: 991px) 92vw, 44vw';
}

/**
 * Allowed international dial codes for contact forms.
 *
 * @return array<int, array{code: string, label: string, flag: string}>
 */
function sln_get_contact_country_codes() {
	return array(
		array(
			'code'  => '+1',
			'label' => __( 'USA', 'smart-leading-net' ),
			'flag'  => '🇺🇸',
		),
		array(
			'code'  => '+44',
			'label' => __( 'UK', 'smart-leading-net' ),
			'flag'  => '🇬🇧',
		),
		array(
			'code'  => '+61',
			'label' => __( 'Australia', 'smart-leading-net' ),
			'flag'  => '🇦🇺',
		),
		array(
			'code'  => '+47',
			'label' => __( 'Norway', 'smart-leading-net' ),
			'flag'  => '🇳🇴',
		),
		array(
			'code'  => '+358',
			'label' => __( 'Finland', 'smart-leading-net' ),
			'flag'  => '🇫🇮',
		),
	);
}

/**
 * Thank You page URL after contact form submission.
 *
 * @return string
 */
function sln_get_thank_you_page_url() {
	$page = get_page_by_path( 'thank-you' );

	if ( $page instanceof WP_Post ) {
		return get_permalink( $page );
	}

	return trailingslashit( home_url( '/thank-you/' ) );
}

/**
 * Workflow section background image URL (uploads, with theme fallback).
 *
 * @return string
 */
function sln_get_workflow_background_url() {
	$upload_relative = '2026/05/case_studies_bg_1.webp';
	$upload_path     = WP_CONTENT_DIR . '/uploads/' . $upload_relative;

	if ( file_exists( $upload_path ) ) {
		return content_url( '/uploads/' . $upload_relative );
	}

	$theme_relative = 'case-studies-bg.webp';
	$theme_path     = SLN_THEME_DIR . '/assets/images/' . $theme_relative;

	if ( file_exists( $theme_path ) ) {
		return sln_get_theme_image_uri( $theme_relative );
	}

	return content_url( '/uploads/' . $upload_relative );
}

/**
 * Critical workflow CSS — inlined in HTML and on sln-main so layout never waits on workflow.css.
 *
 * @return string
 */
function sln_get_workflow_critical_css() {
	return '.workflow{position:relative;overflow:hidden;padding:90px 0;background-color:#ecf2fc;content-visibility:visible!important;contain:none!important}'
		. '.workflow__container{position:relative;z-index:1;width:100%}'
		. '.workflow__header{margin:0 auto;text-align:center}'
		. '.workflow__grid{display:grid;grid-template-columns:repeat(4,minmax(0,1fr));gap:28px;margin-top:56px;padding-top:50px}'
		. '.workflow__card-body{width:100%;min-height:320px;border-radius:15px;background:linear-gradient(135deg,#316cd0 0%,#1f4e9e 100%)}'
		. '.workflow__card-content{display:flex;flex-direction:column;align-items:center;justify-content:center;min-height:320px;padding:28px;text-align:center;color:#fff}'
		. '.workflow__card-title{margin:0 0 12px;font-size:24px;font-weight:700;line-height:1.2;color:#fff}'
		. '.workflow__card-text{margin:0;font-size:14px;line-height:1.35;color:rgba(255,255,255,.95)}'
		. '.workflow,.workflow *,.workflow__header,.workflow__grid,.workflow__card,.workflow__card-body,.workflow__card-content{opacity:1!important;visibility:visible!important;transform:none!important;animation:none!important;content-visibility:visible!important;contain:none!important}'
		. '@media(max-width:991.98px){.workflow{padding:72px 0}.workflow__grid{grid-template-columns:repeat(2,minmax(0,1fr));gap:20px}}'
		. '@media(max-width:767.98px){.workflow{padding:64px 0}.workflow__grid{grid-template-columns:minmax(0,1fr);gap:32px}}';
}

/**
 * Output an inline SVG from the WordPress uploads directory.
 *
 * @param string $relative_path   Path relative to wp-content/uploads (e.g. 2026/05/icon.svg).
 * @param string $class           Optional CSS class for the SVG element.
 * @param bool   $preserve_colors When true, keep original SVG fill/stroke colors.
 * @return string
 */
function sln_get_upload_inline_svg( $relative_path, $class = '', $preserve_colors = false ) {
	$filepath = WP_CONTENT_DIR . '/uploads/' . ltrim( $relative_path, '/' );

	if ( ! file_exists( $filepath ) ) {
		return '';
	}

	$svg = file_get_contents( $filepath );

	if ( false === $svg ) {
		return '';
	}

	if ( ! $preserve_colors ) {
		$svg = sln_inline_svg_use_current_color( $svg );
	}

	if ( $class && preg_match( '/<svg\b/', $svg ) ) {
		$svg = preg_replace( '/<svg\b/', '<svg class="' . esc_attr( $class ) . '"', $svg, 1 );
	}

	return $svg;
}

/**
 * Output inline SVG from a media library attachment.
 *
 * @param int    $attachment_id     Attachment ID.
 * @param string $class             Optional CSS class for the SVG element.
 * @param bool   $preserve_colors   When true, keep original SVG fill/stroke colors.
 * @return string
 */
function sln_get_attachment_inline_svg( $attachment_id, $class = '', $preserve_colors = false ) {
	$attachment_id = absint( $attachment_id );

	if ( ! $attachment_id ) {
		return '';
	}

	$mime = get_post_mime_type( $attachment_id );

	if ( 'image/svg+xml' !== $mime ) {
		return '';
	}

	$filepath = get_attached_file( $attachment_id );

	if ( ! $filepath || ! file_exists( $filepath ) ) {
		return '';
	}

	$svg = file_get_contents( $filepath );

	if ( false === $svg ) {
		return '';
	}

	if ( ! $preserve_colors ) {
		$svg = sln_inline_svg_use_current_color( $svg );
	}

	if ( $class && preg_match( '/<svg\b/', $svg ) ) {
		$svg = preg_replace( '/<svg\b/', '<svg class="' . esc_attr( $class ) . '"', $svg, 1 );
	}

	return $svg;
}

/**
 * Resolve an uploads image URL preferring WEBP over JPG/PNG sources.
 *
 * @param string $relative_subdir Path under wp-content/uploads (e.g. 2026/06).
 * @param string $basename        Filename without extension.
 * @return string Empty string when no WEBP source can be resolved.
 */
function sln_get_upload_webp_url( $relative_subdir, $basename ) {
	$relative_subdir = trim( $relative_subdir, '/' );
	$basename        = trim( $basename );

	if ( '' === $relative_subdir || '' === $basename ) {
		return '';
	}

	$upload_dir_path = WP_CONTENT_DIR . '/uploads/' . $relative_subdir;
	$upload_dir_url  = trailingslashit( content_url( '/uploads/' . $relative_subdir ) );

	if ( ! is_dir( $upload_dir_path ) ) {
		return '';
	}

	$standalone_webp = $upload_dir_path . '/' . $basename . '.webp';

	if ( file_exists( $standalone_webp ) ) {
		return $upload_dir_url . rawurlencode( $basename . '.webp' );
	}

	$source_file = sln_find_upload_source_file( $upload_dir_path, $basename );

	if ( $source_file ) {
		$source_name = basename( $source_file );

		$companion_webp = $source_file . '.webp';
		if ( file_exists( $companion_webp ) ) {
			return $upload_dir_url . rawurlencode( $source_name . '.webp' );
		}

		$source_webp = $upload_dir_path . '/' . pathinfo( $source_name, PATHINFO_FILENAME ) . '.webp';
		if ( file_exists( $source_webp ) ) {
			return $upload_dir_url . rawurlencode( basename( $source_webp ) );
		}

		$attachment_id = attachment_url_to_postid( $upload_dir_url . rawurlencode( $source_name ) );
		if ( $attachment_id ) {
			foreach ( array( 'large', 'full' ) as $size ) {
				$src = wp_get_attachment_image_url( $attachment_id, $size );
				if ( $src && preg_match( '/\.webp(?:\?|$)/i', $src ) ) {
					return $src;
				}
			}
		}
	}

	$webp_matches = glob( $upload_dir_path . '/' . $basename . '*.webp' );
	if ( ! empty( $webp_matches ) ) {
		usort(
			$webp_matches,
			static function ( $a, $b ) {
				return filesize( $b ) <=> filesize( $a );
			}
		);

		return $upload_dir_url . rawurlencode( basename( $webp_matches[0] ) );
	}

	return '';
}

/**
 * Diagonal arrow SVG used inside CTA buttons.
 *
 * @return string
 */
function sln_get_cta_arrow_svg() {
	return '<svg width="18" height="18" viewBox="0 0 18 18" fill="none" xmlns="http://www.w3.org/2000/svg" aria-hidden="true"><path d="M4 14L14 4M14 4H6M14 4V12" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/></svg>';
}

/**
 * Render the compact card arrow CTA (same diagonal swap animation as .sls-btn).
 *
 * @param string $extra_class Optional additional class names.
 */
function sln_render_card_arrow( $extra_class = '' ) {
	$arrow   = sln_get_cta_arrow_svg();
	$classes = trim( 'sln-card-arrow ' . $extra_class );

	// phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- SVG is a trusted theme string.
	printf(
		'<span class="%1$s" aria-hidden="true"><span class="sln-card-arrow__item sln-card-arrow__item--primary">%2$s</span><span class="sln-card-arrow__item sln-card-arrow__item--hover">%2$s</span></span>',
		esc_attr( $classes ),
		$arrow
	);
}

/**
 * Four bubble spans for sls-btn wave hover.
 *
 * @return string
 */
function sln_get_sls_btn_bubbles() {
	return '<span class="sls-btn__bubble" aria-hidden="true"></span>'
		. '<span class="sls-btn__bubble" aria-hidden="true"></span>'
		. '<span class="sls-btn__bubble" aria-hidden="true"></span>'
		. '<span class="sls-btn__bubble" aria-hidden="true"></span>';
}

/**
 * Map CTA variant slug to button class.
 *
 * @param string $variant primary|secondary|outline|white.
 * @return string
 */
function sln_get_cta_button_variant_class( $variant ) {
	$map = array(
		'secondary' => 'btn-secondary-custom',
		'outline'   => 'btn-outline-custom',
		'white'     => 'btn-white-custom',
		'primary'   => 'btn-primary-custom',
	);

	return $map[ $variant ] ?? 'btn-primary-custom';
}

/**
 * Render the global primary/secondary CTA button with arrow hover animation.
 *
 * @param array<string, mixed> $args {
 *     @type string $text       Button label.
 *     @type string $url        Link URL (link type only).
 *     @type string $type       "link", "button", or "submit".
 *     @type string $variant    primary|secondary|outline|white.
 *     @type string $class       Additional CSS classes.
 *     @type array  $attributes  Extra HTML attributes.
 *     @type bool   $show_arrow  Whether to render the arrow icon (default true).
 * }
 */
function sln_render_cta_button( $args = array() ) {
	$args = wp_parse_args(
		$args,
		array(
			'text'       => '',
			'url'        => '',
			'type'       => 'link',
			'variant'    => 'primary',
			'class'      => '',
			'attributes' => array(),
			'show_arrow' => true,
		)
	);

	if ( '' === $args['text'] ) {
		return;
	}

	$variant_class = sln_get_cta_button_variant_class( $args['variant'] );
	$classes       = trim( $variant_class . ' sls-btn ' . $args['class'] );

	if ( empty( $args['show_arrow'] ) ) {
		$classes .= ' sls-btn--text-only';
	}

	$attr_string = '';
	foreach ( $args['attributes'] as $key => $value ) {
		$attr_string .= sprintf( ' %s="%s"', esc_attr( (string) $key ), esc_attr( (string) $value ) );
	}

	$bubbles = sln_get_sls_btn_bubbles();

	if ( ! empty( $args['show_arrow'] ) ) {
		$arrow = sln_get_cta_arrow_svg();
		$inner = sprintf(
			'%1$s<span class="sls-btn__text">%2$s</span><span class="sls-btn__icon" aria-hidden="true"><span class="sls-btn__arrow">%3$s</span><span class="sls-btn__arrow-copy">%4$s</span></span>',
			$bubbles,
			esc_html( $args['text'] ),
			$arrow,
			$arrow
		);
	} else {
		$inner = sprintf( '%s<span class="sls-btn__text">%s</span>', $bubbles, esc_html( $args['text'] ) );
	}

	if ( in_array( $args['type'], array( 'button', 'submit' ), true ) ) {
		// phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- $inner is escaped above.
		printf( '<button type="submit" class="%1$s"%2$s>%3$s</button>', esc_attr( $classes ), $attr_string, $inner );
		return;
	}

	// phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- $inner is escaped above.
	printf(
		'<a href="%1$s" class="%2$s"%3$s>%4$s</a>',
		esc_url( $args['url'] ? $args['url'] : '#' ),
		esc_attr( $classes ),
		$attr_string,
		$inner
	);
}

/**
 * Find a source JPG/PNG file in uploads matching a basename prefix.
 *
 * @param string $upload_dir_path Absolute directory path.
 * @param string $basename        Filename without extension.
 * @return string Absolute file path or empty string.
 */
function sln_find_upload_source_file( $upload_dir_path, $basename ) {
	$extensions = array( 'jpg', 'jpeg', 'png', 'JPG', 'JPEG', 'PNG' );

	foreach ( $extensions as $extension ) {
		$candidate = $upload_dir_path . '/' . $basename . '.' . $extension;
		if ( file_exists( $candidate ) ) {
			return $candidate;
		}
	}

	$matches = glob( $upload_dir_path . '/' . $basename . '*.*' );
	if ( empty( $matches ) ) {
		return '';
	}

	foreach ( $matches as $match ) {
		if ( preg_match( '/\.(jpe?g|png)$/i', $match ) ) {
			return $match;
		}
	}

	return '';
}

/**
 * Render the reusable inner page hero banner.
 *
 * @param array<string, string> $args {
 *     @type string $title            Page heading.
 *     @type string $breadcrumb_label Breadcrumb current item label.
 *     @type string $heading_id       Unique heading ID for aria-labelledby.
 * }
 */
function sln_render_page_banner( $args = array() ) {
	get_template_part( 'template-parts/global/page', 'banner', $args );
}
