<?php
/**
 * Restrict image uploads to WEBP only.
 *
 * @package Smart_Leading_Net
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Remove non-WEBP image MIME types from allowed uploads.
 *
 * @param array $mimes Allowed MIME types.
 * @return array
 */
function sln_allowed_upload_mimes( $mimes ) {
	$blocked_image_types = array(
		'jpg|jpeg|jpe',
		'gif',
		'png',
		'bmp',
		'tiff|tif',
		'ico',
		'svg',
		'avif',
		'heic',
		'heif',
	);

	foreach ( $blocked_image_types as $type ) {
		unset( $mimes[ $type ] );
	}

	$mimes['webp'] = 'image/webp';

	if ( current_user_can( 'manage_options' ) ) {
		$mimes['svg'] = 'image/svg+xml';
	}

	return $mimes;
}
add_filter( 'upload_mimes', 'sln_allowed_upload_mimes' );

/**
 * Block non-WEBP image uploads and show an admin error message.
 *
 * @param array $file Upload file data.
 * @return array
 */
function sln_restrict_image_uploads( $file ) {
	if ( empty( $file['tmp_name'] ) || ! is_uploaded_file( $file['tmp_name'] ) ) {
		return $file;
	}

	$file_type = wp_check_filetype( $file['name'] );
	$extension = strtolower( $file_type['ext'] ?? '' );

	$image_extensions = array(
		'jpg',
		'jpeg',
		'jpe',
		'png',
		'gif',
		'bmp',
		'svg',
		'avif',
		'tiff',
		'tif',
		'ico',
		'heic',
		'heif',
		'webp',
	);

	if ( ! in_array( $extension, $image_extensions, true ) ) {
		return $file;
	}

	if ( 'webp' === $extension ) {
		return $file;
	}

	if ( 'svg' === $extension && current_user_can( 'manage_options' ) ) {
		return $file;
	}

	if ( function_exists( 'finfo_open' ) ) {
		$finfo    = finfo_open( FILEINFO_MIME_TYPE );
		$real_mime = finfo_file( $finfo, $file['tmp_name'] );
		finfo_close( $finfo );

		if ( $real_mime && 0 === strpos( $real_mime, 'image/' ) && 'image/webp' !== $real_mime ) {
			$file['error'] = esc_html__( 'Only WEBP images are allowed.', 'smart-leading-net' );
			return $file;
		}
	}

	$file['error'] = esc_html__( 'Only WEBP images are allowed.', 'smart-leading-net' );

	return $file;
}
add_filter( 'wp_handle_upload_prefilter', 'sln_restrict_image_uploads' );
