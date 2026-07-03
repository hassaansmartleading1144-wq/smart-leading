<?php
/**
 * Our Projects section — settings, defaults, and helpers.
 *
 * @package Smart_Leading_Net
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

define( 'SLN_OUR_PROJECTS_OPTION', 'sln_our_projects_settings' );
define( 'SLN_OUR_PROJECTS_UPLOADS_DIR', '2026/06' );

/**
 * Default project rows.
 *
 * @return array
 */
function sln_get_default_our_projects_items() {
	return array(
		array(
			'image_id' => 0,
			'image'    => 'NovaMed-urgent-care',
			'title'    => 'NovaMed Urgent Care',
			'url'      => '#',
		),
		array(
			'image_id' => 0,
			'image'    => 'iconkitchenbath',
			'title'    => 'Icon Kitchen & Bath',
			'url'      => '#',
		),
		array(
			'image_id' => 0,
			'image'    => 'kitchencabinetdesign2',
			'title'    => 'Kitchen Cabinet Design',
			'url'      => '#',
		),
		array(
			'image_id' => 0,
			'image'    => 'columbuscabinetcity2',
			'title'    => 'Columbus Cabinet City',
			'url'      => '#',
		),
		array(
			'image_id' => 0,
			'image'    => 'Cypress-Towne-Dental',
			'title'    => 'Cypress Towne Dental',
			'url'      => '#',
		),
		array(
			'image_id' => 0,
			'image'    => 'badgergranite',
			'title'    => 'Badger Granite',
			'url'      => '#',
		),
	);
}

/**
 * Default settings.
 *
 * @return array
 */
function sln_get_our_projects_default_settings() {
	return array(
		'projects' => sln_get_default_our_projects_items(),
	);
}

/**
 * Resolve a project image URL preferring WEBP.
 *
 * @param int    $image_id        Attachment ID.
 * @param string $legacy_basename Legacy uploads basename without extension.
 * @return string
 */
function sln_get_project_image_webp_url( $image_id, $legacy_basename = '' ) {
	$image_id = absint( $image_id );

	if ( $image_id ) {
		$mime = get_post_mime_type( $image_id );

		if ( 'image/webp' === $mime ) {
			$src = wp_get_attachment_image_url( $image_id, 'full' );
			if ( $src ) {
				return $src;
			}
		}

		$filepath = get_attached_file( $image_id );

		if ( $filepath ) {
			$companion_webp = $filepath . '.webp';
			if ( file_exists( $companion_webp ) ) {
				$upload_dir = wp_get_upload_dir();
				return str_replace( $upload_dir['basedir'], $upload_dir['baseurl'], $companion_webp );
			}

			$basename = pathinfo( $filepath, PATHINFO_FILENAME );
			$relative = SLN_OUR_PROJECTS_UPLOADS_DIR;

			$attached_url = wp_get_attachment_url( $image_id );
			if ( $attached_url ) {
				$uploads_base = trailingslashit( content_url( '/uploads' ) );
				if ( 0 === strpos( $attached_url, $uploads_base ) ) {
					$relative = trim( str_replace( $uploads_base, '', dirname( $attached_url ) ), '/' );
				}
			}

			$resolved = sln_get_upload_webp_url( $relative, $basename );
			if ( $resolved ) {
				return $resolved;
			}
		}
	}

	if ( $legacy_basename ) {
		return sln_get_upload_webp_url( SLN_OUR_PROJECTS_UPLOADS_DIR, $legacy_basename );
	}

	return '';
}

/**
 * Sanitize project repeater rows.
 *
 * @param array $projects Raw projects.
 * @return array
 */
function sln_sanitize_our_projects_items( $projects ) {
	$output = array();

	if ( ! is_array( $projects ) ) {
		return sln_get_default_our_projects_items();
	}

	foreach ( $projects as $project ) {
		if ( ! is_array( $project ) ) {
			continue;
		}

		$title = sanitize_text_field( $project['title'] ?? '' );
		$url   = esc_url_raw( $project['url'] ?? '' );

		if ( '' === $title && empty( $project['image_id'] ) ) {
			continue;
		}

		$output[] = array(
			'image_id' => sln_sanitize_media_attachment_id( $project['image_id'] ?? 0, array( 'image/webp', 'image/png', 'image/jpeg', 'image/jpg' ) ),
			'image'    => sln_sanitize_legacy_upload_basename( $project['image'] ?? '' ),
			'title'    => $title,
			'url'      => '' !== $url ? $url : '#',
		);
	}

	if ( empty( $output ) ) {
		return sln_get_default_our_projects_items();
	}

	return $output;
}

/**
 * Normalize saved settings.
 *
 * @param array $saved Saved option data.
 * @return array
 */
function sln_normalize_our_projects_settings( $saved ) {
	$defaults = sln_get_our_projects_default_settings();
	$merged   = wp_parse_args( is_array( $saved ) ? $saved : array(), $defaults );

	$merged['projects'] = sln_sanitize_our_projects_items( $merged['projects'] ?? array() );

	return $merged;
}

/**
 * Get saved settings.
 *
 * @return array
 */
function sln_get_our_projects_settings() {
	$saved = get_option( SLN_OUR_PROJECTS_OPTION, array() );

	return sln_normalize_our_projects_settings( $saved );
}

/**
 * Prepare projects for frontend rendering.
 *
 * @return array
 */
function sln_get_our_projects_items() {
	$settings = sln_get_our_projects_settings();
	$items    = array();

	foreach ( $settings['projects'] as $project ) {
		$title = $project['title'] ?? '';

		if ( '' === $title ) {
			continue;
		}

		$items[] = array(
			'title'     => $title,
			'url'       => $project['url'] ?? '#',
			'image_url' => sln_get_project_image_webp_url( $project['image_id'] ?? 0, $project['image'] ?? '' ),
		);
	}

	return $items;
}

/**
 * Sanitize settings before saving.
 *
 * @param array $input Raw input.
 * @return array
 */
function sln_sanitize_our_projects_settings( $input ) {
	if ( ! is_array( $input ) ) {
		return sln_get_our_projects_default_settings();
	}

	return sln_normalize_our_projects_settings(
		array(
			'projects' => sln_sanitize_our_projects_items( $input['projects'] ?? array() ),
		)
	);
}

/**
 * Register settings.
 */
function sln_register_our_projects_settings() {
	register_setting(
		'sln_our_projects_settings_group',
		SLN_OUR_PROJECTS_OPTION,
		array(
			'type'              => 'array',
			'sanitize_callback' => 'sln_sanitize_our_projects_settings',
			'default'           => sln_get_our_projects_default_settings(),
		)
	);
}
add_action( 'admin_init', 'sln_register_our_projects_settings' );
