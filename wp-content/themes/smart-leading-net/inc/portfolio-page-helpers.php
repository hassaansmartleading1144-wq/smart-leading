<?php
/**
 * Portfolio page — defaults, meta keys, and frontend data helpers.
 *
 * @package Smart_Leading_Net
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

define( 'SLN_PORTFOLIO_TEMPLATE', 'portfolio-page-template.php' );
define( 'SLN_PORTFOLIO_SECTION_META', '_sln_portfolio_section' );
define( 'SLN_PORTFOLIO_PROJECTS_META', '_sln_portfolio_projects' );

/**
 * Resolve post ID for portfolio data.
 *
 * @param int|null $post_id Optional post ID.
 * @return int
 */
function sln_portfolio_resolve_post_id( $post_id = null ) {
	if ( $post_id ) {
		return absint( $post_id );
	}

	$queried = get_queried_object();

	if ( $queried instanceof WP_Post && 'page' === $queried->post_type ) {
		return (int) $queried->ID;
	}

	return (int) get_the_ID();
}

/**
 * Whether a page uses the Portfolio template.
 *
 * @param int|null $post_id Optional post ID.
 * @return bool
 */
function sln_portfolio_uses_template( $post_id = null ) {
	$post_id = sln_portfolio_resolve_post_id( $post_id );

	if ( ! $post_id ) {
		return is_page_template( SLN_PORTFOLIO_TEMPLATE );
	}

	return SLN_PORTFOLIO_TEMPLATE === get_page_template_slug( $post_id );
}

/**
 * Read stored meta or defaults when never saved.
 *
 * @param int    $post_id  Post ID.
 * @param string $meta_key Meta key.
 * @param mixed  $default  Default value.
 * @return mixed
 */
function sln_portfolio_get_meta_or_default( $post_id, $meta_key, $default ) {
	if ( ! metadata_exists( 'post', $post_id, $meta_key ) ) {
		return $default;
	}

	return get_post_meta( $post_id, $meta_key, true );
}

/**
 * Default section header.
 *
 * @return array<string, string>
 */
function sln_portfolio_default_section() {
	return array(
		'small_heading' => __( 'Our Projects', 'smart-leading-net' ),
		'main_heading'  => __( 'Portfolio', 'smart-leading-net' ),
		'description'   => __( 'Explore digital platforms built to solve business challenges. From user journeys to conversion-focused architecture, we build funnels that drive market performance.', 'smart-leading-net' ),
	);
}

/**
 * Map a homepage-style project row to portfolio storage shape.
 *
 * @param array<string, mixed> $project Raw project row.
 * @return array<string, mixed>
 */
function sln_portfolio_normalize_project_row( $project ) {
	return array(
		'image_id'    => absint( $project['image_id'] ?? 0 ),
		'image'       => isset( $project['image'] ) ? sanitize_file_name( (string) $project['image'] ) : '',
		'title'       => sanitize_text_field( $project['title'] ?? '' ),
		'url'         => esc_url_raw( $project['url'] ?? '#' ) ?: '#',
		'new_tab'     => ! empty( $project['new_tab'] ),
		'active'      => ! array_key_exists( 'active', $project ) || ! empty( $project['active'] ),
		'alt'         => sanitize_text_field( $project['alt'] ?? '' ),
		'description' => isset( $project['description'] ) ? wp_kses_post( $project['description'] ) : '',
	);
}

/**
 * Default portfolio projects — homepage fallbacks.
 *
 * @return array<int, array<string, mixed>>
 */
function sln_portfolio_default_projects() {
	$preferred_titles = array(
		__( 'Badger Granite', 'smart-leading-net' ),
		__( 'NovaMed Urgent Care', 'smart-leading-net' ),
		__( 'Icon Kitchen & Bath', 'smart-leading-net' ),
	);

	$source = function_exists( 'sln_get_our_projects_settings' )
		? sln_get_our_projects_settings()['projects']
		: sln_get_default_our_projects_items();

	$matched = array();

	foreach ( $preferred_titles as $preferred_title ) {
		foreach ( $source as $project ) {
			if ( ! is_array( $project ) ) {
				continue;
			}

			if ( ( $project['title'] ?? '' ) !== $preferred_title ) {
				continue;
			}

			$matched[] = sln_portfolio_normalize_project_row(
				array_merge(
					$project,
					array(
						'new_tab'  => true,
						'active'   => true,
						'alt'      => '',
						'description' => '',
					)
				)
			);
			break;
		}
	}

	if ( count( $matched ) >= 3 ) {
		return $matched;
	}

	foreach ( $source as $project ) {
		if ( ! is_array( $project ) || empty( $project['title'] ) ) {
			continue;
		}

		$already = false;

		foreach ( $matched as $row ) {
			if ( $row['title'] === $project['title'] ) {
				$already = true;
				break;
			}
		}

		if ( $already ) {
			continue;
		}

		$matched[] = sln_portfolio_normalize_project_row(
			array_merge(
				$project,
				array(
					'new_tab'     => true,
					'active'      => true,
					'alt'         => '',
					'description' => '',
				)
			)
		);

		if ( count( $matched ) >= 3 ) {
			break;
		}
	}

	return $matched;
}

/**
 * Section header for the portfolio page.
 *
 * @param int|null $post_id Optional post ID.
 * @return array<string, string>
 */
function sln_get_portfolio_section( $post_id = null ) {
	$post_id  = sln_portfolio_resolve_post_id( $post_id );
	$defaults = sln_portfolio_default_section();

	if ( ! $post_id || ! sln_portfolio_uses_template( $post_id ) ) {
		return $defaults;
	}

	$stored = sln_portfolio_get_meta_or_default( $post_id, SLN_PORTFOLIO_SECTION_META, $defaults );

	return is_array( $stored ) ? array_merge( $defaults, $stored ) : $defaults;
}

/**
 * Active portfolio projects prepared for frontend cards.
 *
 * @param int|null $post_id Optional post ID.
 * @return array<int, array<string, mixed>>
 */
function sln_get_portfolio_projects( $post_id = null ) {
	$post_id  = sln_portfolio_resolve_post_id( $post_id );
	$defaults = sln_portfolio_default_projects();
	$stored   = $defaults;

	if ( $post_id && sln_portfolio_uses_template( $post_id ) ) {
		$meta = sln_portfolio_get_meta_or_default( $post_id, SLN_PORTFOLIO_PROJECTS_META, $defaults );
		$stored = is_array( $meta ) && ! empty( $meta ) ? $meta : $defaults;
	}

	$items = array();

	foreach ( $stored as $project ) {
		if ( ! is_array( $project ) || empty( $project['active'] ) ) {
			continue;
		}

		$title = sanitize_text_field( $project['title'] ?? '' );

		if ( '' === $title ) {
			continue;
		}

		$image_id = absint( $project['image_id'] ?? 0 );
		$legacy   = isset( $project['image'] ) ? (string) $project['image'] : '';
		$alt      = sanitize_text_field( $project['alt'] ?? '' );

		$items[] = array(
			'title'       => $title,
			'url'         => esc_url_raw( $project['url'] ?? '#' ) ?: '#',
			'new_tab'     => ! empty( $project['new_tab'] ),
			'image_url'   => function_exists( 'sln_get_project_image_webp_url' )
				? sln_get_project_image_webp_url( $image_id, $legacy )
				: '',
			'alt'         => '' !== $alt ? $alt : $title,
			'description' => isset( $project['description'] ) ? wp_kses_post( $project['description'] ) : '',
		);
	}

	if ( empty( $items ) ) {
		foreach ( $defaults as $project ) {
			$title = $project['title'] ?? '';

			if ( '' === $title ) {
				continue;
			}

			$items[] = array(
				'title'       => $title,
				'url'         => $project['url'] ?? '#',
				'new_tab'     => ! empty( $project['new_tab'] ),
				'image_url'   => sln_get_project_image_webp_url( $project['image_id'] ?? 0, $project['image'] ?? '' ),
				'alt'         => $title,
				'description' => '',
			);
		}
	}

	return $items;
}

/**
 * Format rich text content.
 *
 * @param string $content HTML content.
 * @return string
 */
function sln_portfolio_format_content( $content ) {
	if ( function_exists( 'sln_growth_page_format_wysiwyg_content' ) ) {
		return sln_growth_page_format_wysiwyg_content( $content );
	}

	return wp_kses_post( $content );
}
