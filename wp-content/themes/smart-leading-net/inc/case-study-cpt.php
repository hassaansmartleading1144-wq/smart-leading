<?php
/**
 * Case Studies — custom post type, fields schema and data helpers.
 *
 * Each case study is a "sls_case_study" post served at /case-studies/{slug}/.
 * The listing hub stays the existing "Case Studies" page (/case-studies/).
 *
 * @package Smart_Leading_Net
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

define( 'SLN_CASE_STUDY_POST_TYPE', 'sls_case_study' );
define( 'SLN_CASE_STUDY_META', '_sln_case_study' );
define( 'SLN_CASE_STUDY_CPT_VERSION', '1.0.0' );

/**
 * Register the Case Study post type.
 */
function sln_register_case_study_post_type() {
	$labels = array(
		'name'               => __( 'Case Studies', 'smart-leading-net' ),
		'singular_name'      => __( 'Case Study', 'smart-leading-net' ),
		'menu_name'          => __( 'Case Studies', 'smart-leading-net' ),
		'name_admin_bar'     => __( 'Case Study', 'smart-leading-net' ),
		'add_new'            => __( 'Add New', 'smart-leading-net' ),
		'add_new_item'       => __( 'Add New Case Study', 'smart-leading-net' ),
		'new_item'           => __( 'New Case Study', 'smart-leading-net' ),
		'edit_item'          => __( 'Edit Case Study', 'smart-leading-net' ),
		'view_item'          => __( 'View Case Study', 'smart-leading-net' ),
		'all_items'          => __( 'All Case Studies', 'smart-leading-net' ),
		'search_items'       => __( 'Search Case Studies', 'smart-leading-net' ),
		'not_found'          => __( 'No case studies found.', 'smart-leading-net' ),
		'not_found_in_trash' => __( 'No case studies found in Trash.', 'smart-leading-net' ),
		'featured_image'     => __( 'Client logo', 'smart-leading-net' ),
		'set_featured_image' => __( 'Set client logo', 'smart-leading-net' ),
	);

	register_post_type(
		SLN_CASE_STUDY_POST_TYPE,
		array(
			'labels'          => $labels,
			'public'          => true,
			'show_ui'         => true,
			'show_in_menu'    => true,
			'menu_icon'       => 'dashicons-portfolio',
			'menu_position'   => 22,
			'capability_type' => 'post',
			'map_meta_cap'    => true,
			'hierarchical'    => false,
			// Content lives in the structured meta box, not the block editor.
			'supports'        => array( 'title', 'thumbnail', 'excerpt', 'revisions' ),
			'has_archive'     => false,
			'rewrite'         => array(
				'slug'       => 'case-studies',
				'with_front' => false,
			),
			'show_in_rest'    => false,
			'can_export'      => true,
		)
	);
}
add_action( 'init', 'sln_register_case_study_post_type', 5 );

/**
 * Flush rewrite rules once when the CPT version changes.
 */
function sln_case_study_maybe_flush_rewrites() {
	if ( get_option( 'sln_case_study_cpt_version' ) !== SLN_CASE_STUDY_CPT_VERSION ) {
		flush_rewrite_rules( false );
		update_option( 'sln_case_study_cpt_version', SLN_CASE_STUDY_CPT_VERSION );
	}
}
add_action( 'init', 'sln_case_study_maybe_flush_rewrites', 99 );

/**
 * Services a case study can be tagged with.
 *
 * @return array<string, string>
 */
function sln_case_study_services() {
	return array(
		'seo'       => 'SEO',
		'local-seo' => 'Local SEO',
		'ppc'       => 'PPC Ads',
		'web'       => 'Web Development',
		'social'    => 'Social Media',
		'uiux'      => 'UI/UX & Graphics',
	);
}

/**
 * Default (empty) data shape. Every section is optional; empty sections are not rendered.
 *
 * @return array<string, mixed>
 */
function sln_case_study_defaults() {
	return array(
		'headline'      => '',
		'summary'       => '',
		'hero_image'    => 0,
		'industry'      => '',
		'location'      => '',
		'services'      => array(),
		'period'        => '',
		'website'       => '',
		'results_title' => 'Key results',
		'results'       => array(),
		'results_note'  => '',
		'about'         => '',
		'challenge'     => '',
		'challenge_img' => 0,
		'goals'         => array(),
		'scope'         => array(),
		'approach'      => array(),
		'milestones'    => array(),
		'evidence'      => array(),
		'takeaways'     => array(),
		'quote'         => '',
		'quote_name'    => '',
		'quote_role'    => '',
		'cta_title'     => 'Want results like these for your business?',
		'cta_text'      => 'Tell us about your website and goals. We will look at where your leads can grow.',
		'cta_label'     => 'Discuss My Project',
		'cta_url'       => '',
	);
}

/**
 * Get merged case study data for a post.
 *
 * @param int $post_id Post ID.
 * @return array<string, mixed>
 */
function sln_get_case_study_data( $post_id ) {
	$saved = get_post_meta( $post_id, SLN_CASE_STUDY_META, true );
	return wp_parse_args( is_array( $saved ) ? $saved : array(), sln_case_study_defaults() );
}

/**
 * Sanitize submitted data.
 *
 * @param array $raw Raw $_POST data.
 * @return array<string, mixed>
 */
function sln_sanitize_case_study_data( $raw ) {
	$raw  = is_array( $raw ) ? wp_unslash( $raw ) : array();
	$text = static function ( $key ) use ( $raw ) {
		return isset( $raw[ $key ] ) ? sanitize_text_field( $raw[ $key ] ) : '';
	};
	$area = static function ( $key ) use ( $raw ) {
		return isset( $raw[ $key ] ) ? sanitize_textarea_field( $raw[ $key ] ) : '';
	};
	$rows = static function ( $key, $fields ) use ( $raw ) {
		$out = array();
		if ( empty( $raw[ $key ] ) || ! is_array( $raw[ $key ] ) ) {
			return $out;
		}
		foreach ( $raw[ $key ] as $row ) {
			if ( ! is_array( $row ) ) {
				continue;
			}
			$clean = array();
			foreach ( $fields as $field => $type ) {
				$value = isset( $row[ $field ] ) ? $row[ $field ] : '';
				if ( 'int' === $type ) {
					$clean[ $field ] = absint( $value );
				} elseif ( 'area' === $type ) {
					$clean[ $field ] = sanitize_textarea_field( $value );
				} else {
					$clean[ $field ] = sanitize_text_field( $value );
				}
			}
			if ( implode( '', array_map( 'strval', $clean ) ) !== '' && array_filter( $clean ) ) {
				$out[] = $clean;
			}
		}
		return $out;
	};

	$services = array();
	if ( ! empty( $raw['services'] ) && is_array( $raw['services'] ) ) {
		$services = array_values( array_intersect( array_map( 'sanitize_key', $raw['services'] ), array_keys( sln_case_study_services() ) ) );
	}

	$goals = array();
	foreach ( $rows( 'goals', array( 'text' => 'text' ) ) as $goal ) {
		$goals[] = $goal['text'];
	}

	return array(
		'headline'      => $text( 'headline' ),
		'summary'       => $area( 'summary' ),
		'hero_image'    => isset( $raw['hero_image'] ) ? absint( $raw['hero_image'] ) : 0,
		'industry'      => $text( 'industry' ),
		'location'      => $text( 'location' ),
		'services'      => $services,
		'period'        => $text( 'period' ),
		'website'       => isset( $raw['website'] ) ? esc_url_raw( $raw['website'] ) : '',
		'results_title' => $text( 'results_title' ),
		'results'       => $rows(
			'results',
			array(
				'label'  => 'text',
				'before' => 'text',
				'after'  => 'text',
				'change' => 'text',
				'period' => 'text',
				'source' => 'text',
			)
		),
		'results_note'  => $area( 'results_note' ),
		'about'         => $area( 'about' ),
		'challenge'     => $area( 'challenge' ),
		'challenge_img' => isset( $raw['challenge_img'] ) ? absint( $raw['challenge_img'] ) : 0,
		'goals'         => $goals,
		'scope'         => $rows(
			'scope',
			array(
				'title' => 'text',
				'text'  => 'area',
			)
		),
		'milestones'    => $rows(
			'milestones',
			array(
				'label' => 'text',
				'title' => 'text',
				'text'  => 'area',
				'image' => 'int',
			)
		),
		'approach'      => $rows(
			'approach',
			array(
				'title' => 'text',
				'text'  => 'area',
			)
		),
		'evidence'      => $rows(
			'evidence',
			array(
				'title'  => 'text',
				'before' => 'int',
				'after'  => 'int',
				'text'   => 'area',
			)
		),
		'takeaways'     => $rows(
			'takeaways',
			array(
				'title' => 'text',
				'text'  => 'area',
			)
		),
		'quote'         => $area( 'quote' ),
		'quote_name'    => $text( 'quote_name' ),
		'quote_role'    => $text( 'quote_role' ),
		'cta_title'     => $text( 'cta_title' ),
		'cta_text'      => $area( 'cta_text' ),
		'cta_label'     => $text( 'cta_label' ),
		'cta_url'       => isset( $raw['cta_url'] ) ? esc_url_raw( $raw['cta_url'] ) : '',
	);
}

/**
 * Service labels for a case study.
 *
 * @param array $data Case study data.
 * @return string[]
 */
function sln_case_study_service_labels( $data ) {
	$all    = sln_case_study_services();
	$labels = array();
	foreach ( (array) $data['services'] as $key ) {
		if ( isset( $all[ $key ] ) ) {
			$labels[] = $all[ $key ];
		}
	}
	return $labels;
}

/**
 * CTA URL with contact-page fallback.
 *
 * @param array $data Case study data.
 * @return string
 */
function sln_case_study_cta_url( $data ) {
	if ( ! empty( $data['cta_url'] ) ) {
		return $data['cta_url'];
	}
	$contact = get_page_by_path( 'contact-us' );
	return $contact ? get_permalink( $contact ) : home_url( '/contact-us/' );
}

/**
 * Up to three related published case studies, preferring shared services.
 *
 * @param int   $post_id Current post ID.
 * @param array $data    Current data.
 * @return WP_Post[]
 */
function sln_case_study_related( $post_id, $data ) {
	$posts = get_posts(
		array(
			'post_type'      => SLN_CASE_STUDY_POST_TYPE,
			'post_status'    => 'publish',
			'posts_per_page' => 12,
			'post__not_in'   => array( $post_id ),
			'no_found_rows'  => true,
		)
	);
	$mine  = (array) $data['services'];
	usort(
		$posts,
		static function ( $a, $b ) use ( $mine ) {
			$sa = count( array_intersect( $mine, (array) sln_get_case_study_data( $a->ID )['services'] ) );
			$sb = count( array_intersect( $mine, (array) sln_get_case_study_data( $b->ID )['services'] ) );
			return $sb <=> $sa;
		}
	);
	return array_slice( $posts, 0, 3 );
}
