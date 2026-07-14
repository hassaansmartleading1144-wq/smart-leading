<?php
/**
 * Demo package format helpers — shared by export and import.
 *
 * @package Smart_Leading_Net
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

define( 'SLN_DEMO_PACKAGE_FORMAT', 'sln-demo-package' );
define( 'SLN_DEMO_PACKAGE_VERSION', '2.0.0' );

/**
 * Theme option keys included in demo packages.
 *
 * @return array<int, string>
 */
function sln_demo_package_option_keys() {
	return array(
		SLN_OUR_SERVICES_OPTION,
		SLN_OUR_PROJECTS_OPTION,
		SLN_CREDIBILITY_OPTION,
		SLN_GHL_OPTION,
	);
}

/**
 * SEO Services page meta keys.
 *
 * @return array<int, string>
 */
function sln_demo_package_seo_meta_keys() {
	return array(
		SLN_SEO_SVC_HERO_META,
		SLN_SEO_SVC_REALITY_SECTION_META,
		SLN_SEO_SVC_REALITY_CARDS_META,
		SLN_SEO_SVC_PROGRAM_SECTION_META,
		SLN_SEO_SVC_PROGRAM_CARDS_META,
		SLN_SEO_SVC_RESULTS_SECTION_META,
		SLN_SEO_SVC_RESULTS_BLOCKS_META,
		SLN_SEO_SVC_PROCESS_SECTION_META,
		SLN_SEO_SVC_PROCESS_STEPS_META,
		SLN_SEO_SVC_CASE_STUDIES_SECTION_META,
		SLN_SEO_SVC_CASE_STUDIES_CARDS_META,
		SLN_SEO_SVC_PRICING_SECTION_META,
		SLN_SEO_SVC_PRICING_PLANS_META,
		SLN_SEO_SVC_TESTIMONIALS_SECTION_META,
		SLN_SEO_SVC_TESTIMONIALS_SUMMARY_META,
		SLN_SEO_SVC_TESTIMONIALS_REVIEWS_META,
		SLN_SEO_SVC_CTA_FORM_META,
		SLN_SEO_SVC_FAQ_SECTION_META,
		SLN_SEO_SVC_FAQ_ITEMS_META,
	);
}

/**
 * Portfolio page meta keys.
 *
 * @return array<int, string>
 */
function sln_demo_package_portfolio_meta_keys() {
	return array(
		SLN_PORTFOLIO_SECTION_META,
		SLN_PORTFOLIO_PROJECTS_META,
	);
}

/**
 * Growth Page section meta keys (non-banner).
 *
 * @return array<int, string>
 */
function sln_demo_package_growth_meta_keys() {
	return array(
		SLN_GP_GROWTH_METRICS_META,
		SLN_GP_SECTION_ORDERS_META,
		SLN_GP_SERVICES_SECTION_META,
		SLN_GP_SERVICES_CARDS_META,
		SLN_GP_CLIENT_STORY_SECTION_META,
		SLN_GP_CLIENT_STORY_STEPS_META,
		SLN_GP_CLIENT_STORY_RESULTS_META,
		SLN_GP_HOW_WORK_SECTION_META,
		SLN_GP_HOW_WORK_TABS_META,
		SLN_GP_GROWTH_SERVICES_SECTION_META,
		SLN_GP_GROWTH_SERVICES_CARDS_META,
		SLN_GP_CASE_STUDIES_SECTION_META,
		SLN_GP_CASE_STUDIES_CARDS_META,
		SLN_GP_WHY_CHOOSE_SECTION_META,
		SLN_GP_WHY_CHOOSE_ROWS_META,
		SLN_GP_PRICE_PLAN_SECTION_META,
		SLN_GP_PRICE_PLAN_CARDS_META,
		SLN_GP_TESTIMONIALS_SECTION_META,
		SLN_GP_TESTIMONIALS_STATS_META,
		SLN_GP_TESTIMONIALS_SUMMARY_META,
		SLN_GP_TESTIMONIALS_REVIEWS_META,
		SLN_GP_CTA_BANNER_SECTION_META,
	);
}

/**
 * Whether a key looks like an attachment ID field.
 *
 * @param string|int $key Array key.
 * @return bool
 */
function sln_demo_package_is_attachment_key( $key ) {
	$key = (string) $key;

	if ( in_array( $key, array( 'image_id', 'icon_id', 'logo_id', 'tab_icon_id', 'banner_image_id', 'background_image_id', 'hero_image_id' ), true ) ) {
		return true;
	}

	return (bool) preg_match( '/_(image|icon|logo|banner|background)_id$/', $key );
}

/**
 * Resolve upload-relative path for an attachment ID.
 *
 * @param int $attachment_id Attachment ID.
 * @return string
 */
function sln_demo_package_attachment_relative_path( $attachment_id ) {
	$attachment_id = absint( $attachment_id );

	if ( ! $attachment_id ) {
		return '';
	}

	$file = get_post_meta( $attachment_id, '_wp_attached_file', true );

	return is_string( $file ) ? ltrim( str_replace( '\\', '/', $file ), '/' ) : '';
}

/**
 * Collect attachment IDs found in nested data.
 *
 * @param mixed        $data         Arbitrary data.
 * @param array<int>   $collector    Collected IDs.
 */
function sln_demo_package_collect_attachment_ids( $data, array &$collector ) {
	if ( ! is_array( $data ) ) {
		return;
	}

	foreach ( $data as $key => $value ) {
		if ( is_array( $value ) ) {
			sln_demo_package_collect_attachment_ids( $value, $collector );
			continue;
		}

		if ( sln_demo_package_is_attachment_key( $key ) ) {
			$id = absint( $value );

			if ( $id > 0 ) {
				$collector[ $id ] = $id;
			}
		}
	}
}

/**
 * Annotate nested data with image_file paths beside attachment IDs.
 *
 * @param mixed $data Data tree.
 * @return mixed
 */
function sln_demo_package_annotate_media_paths( $data ) {
	if ( ! is_array( $data ) ) {
		return $data;
	}

	$out = array();

	foreach ( $data as $key => $value ) {
		if ( is_array( $value ) ) {
			$out[ $key ] = sln_demo_package_annotate_media_paths( $value );
			continue;
		}

		$out[ $key ] = $value;

		if ( sln_demo_package_is_attachment_key( $key ) ) {
			$path = sln_demo_package_attachment_relative_path( absint( $value ) );

			if ( '' !== $path ) {
				$file_key         = preg_replace( '/_id$/', '_file', (string) $key );
				$out[ $file_key ] = $path;
			}
		}
	}

	return $out;
}

/**
 * Remap annotated media fields onto a target site using a path => ID map.
 *
 * @param mixed               $data      Data tree.
 * @param array<string, int>  $media_map Relative path => attachment ID.
 * @param array<string, int>  $basename_map Lowercase basename => attachment ID.
 * @return mixed
 */
function sln_demo_package_remap_media_ids( $data, array $media_map, array $basename_map = array() ) {
	if ( ! is_array( $data ) ) {
		return $data;
	}

	$out = array();

	foreach ( $data as $key => $value ) {
		if ( is_array( $value ) ) {
			$out[ $key ] = sln_demo_package_remap_media_ids( $value, $media_map, $basename_map );
			continue;
		}

		if ( preg_match( '/_file$/', (string) $key ) ) {
			continue;
		}

		$out[ $key ] = $value;

		if ( ! sln_demo_package_is_attachment_key( $key ) ) {
			continue;
		}

		$file_key = preg_replace( '/_id$/', '_file', (string) $key );
		$path     = '';

		if ( isset( $data[ $file_key ] ) && is_string( $data[ $file_key ] ) ) {
			$path = ltrim( str_replace( '\\', '/', $data[ $file_key ] ), '/' );
		}

		$resolved = 0;

		if ( '' !== $path && isset( $media_map[ $path ] ) ) {
			$resolved = absint( $media_map[ $path ] );
		}

		if ( ! $resolved && '' !== $path ) {
			$base = strtolower( pathinfo( $path, PATHINFO_FILENAME ) );

			if ( isset( $basename_map[ $base ] ) ) {
				$resolved = absint( $basename_map[ $base ] );
			}
		}

		if ( $resolved ) {
			$out[ $key ] = $resolved;
		} else {
			$out[ $key ] = absint( $value );
		}
	}

	return $out;
}

/**
 * Build media registry entries for given attachment IDs.
 *
 * @param array<int, int> $attachment_ids IDs.
 * @return array<int, array<string, string>>
 */
function sln_demo_package_build_media_registry( array $attachment_ids ) {
	$registry = array();

	foreach ( $attachment_ids as $attachment_id ) {
		$attachment_id = absint( $attachment_id );

		if ( ! $attachment_id ) {
			continue;
		}

		$path = sln_demo_package_attachment_relative_path( $attachment_id );

		if ( '' === $path ) {
			continue;
		}

		$registry[ (string) $attachment_id ] = array(
			'id'       => $attachment_id,
			'file'     => $path,
			'filename' => basename( $path ),
			'url'      => (string) wp_get_attachment_url( $attachment_id ),
			'mime'     => (string) get_post_mime_type( $attachment_id ),
		);
	}

	return $registry;
}

/**
 * Encode package array as pretty JSON.
 *
 * @param array<string, mixed> $package Package.
 * @return string|false
 */
function sln_demo_package_encode( array $package ) {
	return wp_json_encode( $package, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE );
}

/**
 * Decode and validate a demo package JSON string.
 *
 * @param string $json JSON.
 * @return array<string, mixed>|WP_Error
 */
function sln_demo_package_decode( $json ) {
	$data = json_decode( (string) $json, true );

	if ( ! is_array( $data ) ) {
		return new WP_Error( 'sln_demo_invalid_json', __( 'Invalid demo package JSON.', 'smart-leading-net' ) );
	}

	if ( empty( $data['format'] ) || SLN_DEMO_PACKAGE_FORMAT !== $data['format'] ) {
		// Allow legacy-ish packages that still have content/options keys.
		if ( empty( $data['content'] ) && empty( $data['options'] ) && empty( $data['pages'] ) ) {
			return new WP_Error( 'sln_demo_invalid_format', __( 'Unrecognized demo package format.', 'smart-leading-net' ) );
		}
	}

	return $data;
}
