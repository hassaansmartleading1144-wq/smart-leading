<?php
/**
 * Founders page — editable data (defaults + saved meta).
 *
 * @package Smart_Leading_Net
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

define( 'SLN_FOUNDERS_META_KEY', '_sln_founders_page' );

/**
 * Social networks supported on founder profiles.
 *
 * @return array<string, string> key => label
 */
function sln_founders_social_networks() {
	return array(
		'linkedin'  => 'LinkedIn',
		'x'         => 'X (Twitter)',
		'facebook'  => 'Facebook',
		'instagram' => 'Instagram',
		'youtube'   => 'YouTube',
		'website'   => 'Website',
		'email'     => 'Email',
	);
}

/**
 * Empty founder record.
 *
 * @return array<string, mixed>
 */
function sln_founders_empty_founder() {
	$socials = array();
	foreach ( array_keys( sln_founders_social_networks() ) as $key ) {
		$socials[ $key ] = '';
	}

	return array(
		'name'     => '',
		'anchor'   => '',
		'role'     => '',
		'focus'    => '',
		'photo_id' => 0,
		'intro'    => '',
		'bio'      => '',
		'socials'  => $socials,
		'courses'  => array(),
	);
}

/**
 * Default page data (from the approved 13 Nov 2026 prototype).
 *
 * @return array<string, mixed>
 */
function sln_founders_default_data() {
	$aqib                       = sln_founders_empty_founder();
	$aqib['name']               = 'Aqib Khan';
	$aqib['anchor']             = 'aqib-khan';
	$aqib['role']               = 'CEO';
	$aqib['focus']              = 'Growth Hacker · Marketing Specialist';
	$aqib['intro']              = 'Aqib brings a marketing and growth perspective to Smart Leading Solutions, with an emphasis on understanding customer needs and connecting digital activity to business opportunities.';
	$aqib['socials']['linkedin'] = 'https://www.linkedin.com/in/aqib-khan-sls/';

	$farooq           = sln_founders_empty_founder();
	$farooq['name']   = 'Farooq Saleh';
	$farooq['anchor'] = 'farooq-saleh';
	$farooq['role']   = 'Director';
	$farooq['focus']  = 'Data Scientist · Executive';
	$farooq['intro']  = 'Farooq brings a data science and executive perspective to the leadership of Smart Leading Solutions.';

	return array(
		'hero'     => array(
			'label'       => 'The People Behind Smart Leading',
			'title'       => 'Meet the {founders} of Smart Leading Solutions.',
			'intro'       => 'Aqib Khan and Farooq Saleh lead Smart Leading Solutions, bringing marketing and data perspectives to a shared focus: helping businesses create more opportunities through digital channels.',
			'since'       => 'Operating since 2018.',
			'joint_photo' => 0,
		),
		'founders' => array( $aqib, $farooq ),
	);
}

/**
 * Saved data merged over defaults.
 *
 * @param int $post_id Page ID.
 * @return array<string, mixed>
 */
function sln_founders_get_data( $post_id ) {
	$defaults = sln_founders_default_data();
	$saved    = get_post_meta( $post_id, SLN_FOUNDERS_META_KEY, true );

	if ( ! is_array( $saved ) ) {
		return $defaults;
	}

	$data         = $defaults;
	$data['hero'] = array_merge( $defaults['hero'], isset( $saved['hero'] ) && is_array( $saved['hero'] ) ? $saved['hero'] : array() );

	if ( isset( $saved['founders'] ) && is_array( $saved['founders'] ) ) {
		$data['founders'] = array();
		foreach ( $saved['founders'] as $founder ) {
			$merged            = array_merge( sln_founders_empty_founder(), (array) $founder );
			$merged['socials'] = array_merge( sln_founders_empty_founder()['socials'], (array) $merged['socials'] );
			$merged['courses'] = array_values( (array) $merged['courses'] );
			$data['founders'][] = $merged;
		}
	}

	return $data;
}

/**
 * Sanitise posted page data.
 *
 * @param array $raw Raw $_POST payload.
 * @return array<string, mixed>
 */
function sln_founders_sanitize_data( $raw ) {
	$raw  = is_array( $raw ) ? wp_unslash( $raw ) : array();
	$hero = isset( $raw['hero'] ) && is_array( $raw['hero'] ) ? $raw['hero'] : array();

	$data = array(
		'hero'     => array(
			'label'       => sanitize_text_field( $hero['label'] ?? '' ),
			'title'       => sanitize_text_field( $hero['title'] ?? '' ),
			'intro'       => sanitize_textarea_field( $hero['intro'] ?? '' ),
			'since'       => sanitize_text_field( $hero['since'] ?? '' ),
			'joint_photo' => absint( $hero['joint_photo'] ?? 0 ),
		),
		'founders' => array(),
	);

	$founders = isset( $raw['founders'] ) && is_array( $raw['founders'] ) ? $raw['founders'] : array();
	foreach ( $founders as $f ) {
		if ( ! is_array( $f ) ) {
			continue;
		}
		$name = sanitize_text_field( $f['name'] ?? '' );
		if ( '' === $name ) {
			continue;
		}

		$socials = array();
		foreach ( array_keys( sln_founders_social_networks() ) as $key ) {
			$value = trim( (string) ( $f['socials'][ $key ] ?? '' ) );
			if ( 'email' === $key ) {
				$socials[ $key ] = sanitize_email( $value );
			} else {
				$socials[ $key ] = esc_url_raw( $value );
			}
		}

		$courses = array();
		if ( isset( $f['courses'] ) && is_array( $f['courses'] ) ) {
			foreach ( $f['courses'] as $c ) {
				$title = sanitize_text_field( $c['title'] ?? '' );
				if ( '' === $title ) {
					continue;
				}
				$courses[] = array(
					'title'    => $title,
					'provider' => sanitize_text_field( $c['provider'] ?? '' ),
					'year'     => sanitize_text_field( $c['year'] ?? '' ),
					'url'      => esc_url_raw( trim( (string) ( $c['url'] ?? '' ) ) ),
					'badge_id' => absint( $c['badge_id'] ?? 0 ),
					'cert_id'  => absint( $c['cert_id'] ?? 0 ),
				);
			}
		}

		$anchor = sanitize_title( $f['anchor'] ?? '' );

		$data['founders'][] = array(
			'name'     => $name,
			'anchor'   => '' !== $anchor ? $anchor : sanitize_title( $name ),
			'role'     => sanitize_text_field( $f['role'] ?? '' ),
			'focus'    => sanitize_text_field( $f['focus'] ?? '' ),
			'photo_id' => absint( $f['photo_id'] ?? 0 ),
			'intro'    => sanitize_textarea_field( $f['intro'] ?? '' ),
			'bio'      => wp_kses_post( $f['bio'] ?? '' ),
			'socials'  => $socials,
			'courses'  => $courses,
		);
	}

	return $data;
}
