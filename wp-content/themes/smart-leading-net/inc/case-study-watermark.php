<?php
/**
 * Case Studies — automatic SLS watermark on screenshots.
 *
 * Originals in the Media Library stay untouched. A watermarked copy
 * ("{name}-sls-wm.webp") is created next to the original the first time
 * an image is used in a case study (on save, or lazily on first view).
 *
 * @package Smart_Leading_Net
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/** Bump to regenerate every watermarked copy after a design change. */
define( 'SLN_CS_WATERMARK_VERSION', '1' );
define( 'SLN_CS_WATERMARK_META', '_sln_cs_watermark' );
define( 'SLN_CS_WATERMARK_MAX_WIDTH', 1800 );

/**
 * Load an image file into a GD resource.
 *
 * @param string $path File path.
 * @return GdImage|false
 */
function sln_cs_wm_load( $path ) {
	$type = wp_check_filetype( $path );
	switch ( $type['ext'] ) {
		case 'webp':
			return function_exists( 'imagecreatefromwebp' ) ? imagecreatefromwebp( $path ) : false;
		case 'png':
			return imagecreatefrompng( $path );
		case 'jpg':
		case 'jpeg':
		case 'jpe':
			return imagecreatefromjpeg( $path );
	}
	return false;
}

/**
 * Resize the logo and scale its alpha to the given opacity.
 *
 * @param GdImage $logo    Source logo (with alpha).
 * @param int     $width   Target width.
 * @param float   $opacity 0–1.
 * @return GdImage
 */
function sln_cs_wm_logo( $logo, $width, $opacity ) {
	$width  = max( 1, (int) $width );
	$height = max( 1, (int) round( imagesy( $logo ) * $width / imagesx( $logo ) ) );
	$out    = imagecreatetruecolor( $width, $height );
	imagealphablending( $out, false );
	imagesavealpha( $out, true );
	imagefill( $out, 0, 0, imagecolorallocatealpha( $out, 0, 0, 0, 127 ) );
	imagecopyresampled( $out, $logo, 0, 0, 0, 0, $width, $height, imagesx( $logo ), imagesy( $logo ) );

	for ( $x = 0; $x < $width; $x++ ) {
		for ( $y = 0; $y < $height; $y++ ) {
			$rgba  = imagecolorat( $out, $x, $y );
			$alpha = ( $rgba >> 24 ) & 0x7F;
			if ( 127 === $alpha ) {
				continue;
			}
			$new = 127 - (int) round( ( 127 - $alpha ) * $opacity );
			imagesetpixel( $out, $x, $y, imagecolorallocatealpha( $out, ( $rgba >> 16 ) & 0xFF, ( $rgba >> 8 ) & 0xFF, $rgba & 0xFF, $new ) );
		}
	}
	return $out;
}

/**
 * Filled rounded rectangle.
 */
function sln_cs_wm_rounded_rect( $img, $x1, $y1, $x2, $y2, $r, $color ) {
	imagefilledrectangle( $img, $x1 + $r, $y1, $x2 - $r, $y2, $color );
	imagefilledrectangle( $img, $x1, $y1 + $r, $x1 + $r - 1, $y2 - $r, $color );
	imagefilledrectangle( $img, $x2 - $r + 1, $y1 + $r, $x2, $y2 - $r, $color );
	foreach ( array( array( $x1 + $r, $y1 + $r ), array( $x2 - $r, $y1 + $r ), array( $x1 + $r, $y2 - $r ), array( $x2 - $r, $y2 - $r ) ) as $c ) {
		imagefilledarc( $img, $c[0], $c[1], $r * 2, $r * 2, 0, 360, $color, IMG_ARC_PIE );
	}
}

/**
 * Create the watermarked copy for an attachment.
 *
 * @param int $attachment_id Attachment ID.
 * @return array|false { file, width, height } or false.
 */
function sln_cs_generate_watermark( $attachment_id ) {
	$src_path  = get_attached_file( $attachment_id );
	$logo_path = get_template_directory() . '/assets/images/sls-watermark.png';
	if ( ! $src_path || ! file_exists( $src_path ) || ! file_exists( $logo_path ) || ! function_exists( 'imagewebp' ) ) {
		return false;
	}

	$src = sln_cs_wm_load( $src_path );
	if ( ! $src ) {
		return false;
	}

	// Normalise to a true-colour canvas, capped in width for page weight.
	$w     = imagesx( $src );
	$h     = imagesy( $src );
	$scale = $w > SLN_CS_WATERMARK_MAX_WIDTH ? SLN_CS_WATERMARK_MAX_WIDTH / $w : 1;
	$cw    = (int) round( $w * $scale );
	$ch    = (int) round( $h * $scale );
	$img   = imagecreatetruecolor( $cw, $ch );
	imagefill( $img, 0, 0, imagecolorallocate( $img, 255, 255, 255 ) );
	imagecopyresampled( $img, $src, 0, 0, 0, 0, $cw, $ch, $w, $h );
	imagealphablending( $img, true );

	$logo = imagecreatefrompng( $logo_path );
	imagesavealpha( $logo, true );

	// 1) Large faint logo in the centre: hard to crop out.
	$big_w = (int) round( min( $cw * 0.42, $ch * 0.42 * imagesx( $logo ) / imagesy( $logo ) ) );
	$big   = sln_cs_wm_logo( $logo, $big_w, 0.13 );
	imagecopy( $img, $big, (int) ( ( $cw - imagesx( $big ) ) / 2 ), (int) ( ( $ch - imagesy( $big ) ) / 2 ), 0, 0, imagesx( $big ), imagesy( $big ) );

	// 2) Clear logo badge in the bottom-right corner.
	$small_w = (int) round( max( 90, min( 220, $cw * 0.12 ) ) );
	$small   = sln_cs_wm_logo( $logo, $small_w, 1 );
	$pad     = (int) round( $small_w * 0.12 );
	$margin  = (int) round( max( 12, $cw * 0.015 ) );
	$x2      = $cw - $margin;
	$y2      = $ch - $margin;
	$x1      = $x2 - imagesx( $small ) - $pad * 2;
	$y1      = $y2 - imagesy( $small ) - $pad * 2;
	sln_cs_wm_rounded_rect( $img, $x1, $y1, $x2, $y2, (int) round( $pad * 0.9 ), imagecolorallocatealpha( $img, 255, 255, 255, 18 ) );
	imagecopy( $img, $small, $x1 + $pad, $y1 + $pad, 0, 0, imagesx( $small ), imagesy( $small ) );

	$info = pathinfo( $src_path );
	$dest = $info['dirname'] . '/' . $info['filename'] . '-sls-wm.webp';
	$ok   = imagewebp( $img, $dest, 86 );

	imagedestroy( $img );
	imagedestroy( $src );
	imagedestroy( $logo );
	imagedestroy( $big );
	imagedestroy( $small );

	if ( ! $ok ) {
		return false;
	}

	$meta = array(
		'file'    => _wp_relative_upload_path( $dest ),
		'width'   => $cw,
		'height'  => $ch,
		'version' => SLN_CS_WATERMARK_VERSION,
		'source'  => filemtime( $src_path ),
	);
	update_post_meta( $attachment_id, SLN_CS_WATERMARK_META, $meta );
	return $meta;
}

/**
 * Get (creating if needed) the watermarked copy.
 *
 * @param int $attachment_id Attachment ID.
 * @return array|false { url, width, height } or false.
 */
function sln_cs_watermarked( $attachment_id ) {
	$attachment_id = absint( $attachment_id );
	if ( ! $attachment_id ) {
		return false;
	}
	$meta     = get_post_meta( $attachment_id, SLN_CS_WATERMARK_META, true );
	$src_path = get_attached_file( $attachment_id );
	$uploads  = wp_get_upload_dir();
	$fresh    = is_array( $meta )
		&& SLN_CS_WATERMARK_VERSION === ( $meta['version'] ?? '' )
		&& $src_path && file_exists( $src_path ) && (int) $meta['source'] === filemtime( $src_path )
		&& file_exists( trailingslashit( $uploads['basedir'] ) . $meta['file'] );

	if ( ! $fresh ) {
		$meta = sln_cs_generate_watermark( $attachment_id );
	}
	if ( ! $meta ) {
		return false;
	}
	return array(
		'url'    => trailingslashit( $uploads['baseurl'] ) . $meta['file'],
		'width'  => (int) $meta['width'],
		'height' => (int) $meta['height'],
	);
}

/**
 * Output a case study screenshot: watermarked copy, falling back to the original.
 *
 * @param int    $attachment_id Attachment ID.
 * @param string $alt           Alt text.
 * @param bool   $link          Wrap in a link to the full (watermarked) image.
 * @param array  $attrs         Extra attributes (class, loading).
 * @return string HTML.
 */
function sln_cs_screenshot( $attachment_id, $alt, $link = false, $attrs = array() ) {
	$wm = sln_cs_watermarked( $attachment_id );
	if ( ! $wm ) {
		$img  = wp_get_attachment_image( $attachment_id, 'large', false, array_merge( $attrs, array( 'alt' => $alt ) ) );
		$full = wp_get_attachment_url( $attachment_id );
	} else {
		$img = sprintf(
			'<img src="%1$s" width="%2$d" height="%3$d" alt="%4$s" loading="%5$s" decoding="async"%6$s>',
			esc_url( $wm['url'] ),
			$wm['width'],
			$wm['height'],
			esc_attr( $alt ),
			esc_attr( $attrs['loading'] ?? 'lazy' ),
			! empty( $attrs['class'] ) ? ' class="' . esc_attr( $attrs['class'] ) . '"' : ''
		);
		$full = $wm['url'];
	}
	if ( ! $link || ! $img ) {
		return $img;
	}
	return '<a href="' . esc_url( $full ) . '" target="_blank" rel="noopener">' . $img . '</a>';
}

/**
 * Pre-generate watermarks when a case study is saved (so the first visitor isn't slowed down).
 *
 * @param int $post_id Post ID.
 */
function sln_cs_watermark_on_save( $post_id ) {
	if ( wp_is_post_revision( $post_id ) || ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) ) {
		return;
	}
	$d   = sln_get_case_study_data( $post_id );
	$ids = array( $d['hero_image'], $d['challenge_img'] );
	foreach ( (array) $d['evidence'] as $ev ) {
		$ids[] = $ev['before'];
		$ids[] = $ev['after'];
	}
	foreach ( array_unique( array_filter( array_map( 'absint', $ids ) ) ) as $id ) {
		sln_cs_watermarked( $id );
	}
}
add_action( 'save_post_' . SLN_CASE_STUDY_POST_TYPE, 'sln_cs_watermark_on_save', 20 );

/**
 * Remove the watermarked copy when the original is deleted.
 *
 * @param int $attachment_id Attachment ID.
 */
function sln_cs_watermark_cleanup( $attachment_id ) {
	$meta = get_post_meta( $attachment_id, SLN_CS_WATERMARK_META, true );
	if ( is_array( $meta ) && ! empty( $meta['file'] ) ) {
		$uploads = wp_get_upload_dir();
		wp_delete_file( trailingslashit( $uploads['basedir'] ) . $meta['file'] );
	}
}
add_action( 'delete_attachment', 'sln_cs_watermark_cleanup' );
