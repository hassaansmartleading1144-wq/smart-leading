<?php
/**
 * Our Services section — settings, defaults, and helpers.
 *
 * @package Smart_Leading_Net
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

define( 'SLN_OUR_SERVICES_OPTION', 'sln_our_services_settings' );
define( 'SLN_OUR_SERVICES_MIN_SERVICES', 6 );
define( 'SLN_OUR_SERVICES_MIN_RESULTS', 6 );

/**
 * Default tab slugs (stable IDs for tabs/panels).
 *
 * @return array
 */
function sln_our_services_default_tab_slugs() {
	return array(
		'revenue-growth',
		'lead-generation',
		'conversion-retention',
		'brand-visibility',
		'marketing-automation',
	);
}

/**
 * Default service items.
 *
 * @return array
 */
function sln_get_default_our_services_items() {
	return array(
		array( 'icon_id' => 0, 'title' => 'Paid Ads', 'url' => '#' ),
		array( 'icon_id' => 0, 'title' => 'SEO', 'url' => '#' ),
		array( 'icon_id' => 0, 'title' => 'Google Ads', 'url' => '#' ),
		array( 'icon_id' => 0, 'title' => 'Meta Ads', 'url' => '#' ),
		array( 'icon_id' => 0, 'title' => 'SMM', 'url' => '#' ),
		array( 'icon_id' => 0, 'title' => 'Retention Marketing', 'url' => '#' ),
	);
}

/**
 * Default result blocks.
 *
 * @return array
 */
function sln_get_default_our_services_results() {
	return array(
		array(
			'type'           => 'number',
			'number_value'   => '320%',
			'number_subtext' => 'Sales Growth In 3 Months',
			'logo_id'        => 0,
			'logo_alt'       => '',
		),
		array(
			'type'           => 'logo',
			'number_value'   => '',
			'number_subtext' => '',
			'logo_id'        => 0,
			'logo_alt'       => "Sam's Flooring",
		),
		array(
			'type'           => 'logo',
			'number_value'   => '',
			'number_subtext' => '',
			'logo_id'        => 0,
			'logo_alt'       => 'Badger Granite',
		),
		array(
			'type'           => 'number',
			'number_value'   => '400%',
			'number_subtext' => 'Increase In Overall Revenue',
			'logo_id'        => 0,
			'logo_alt'       => '',
		),
		array(
			'type'           => 'number',
			'number_value'   => '$20M',
			'number_subtext' => 'Generated In Revenue',
			'logo_id'        => 0,
			'logo_alt'       => '',
		),
		array(
			'type'           => 'logo',
			'number_value'   => '',
			'number_subtext' => '',
			'logo_id'        => 0,
			'logo_alt'       => 'CheapBills',
		),
	);
}

/**
 * Build a default tab row.
 *
 * @param string $slug           Tab slug.
 * @param string $title          Tab title.
 * @param array  $content        Tab-specific content overrides.
 * @return array
 */
function sln_build_default_our_services_tab( $slug, $title, $content = array() ) {
	$tab = wp_parse_args(
		$content,
		array(
			'slug'           => $slug,
			'tab_title'      => $title,
			'tab_icon_id'    => 0,
			'icon_flip'      => false,
			'featured_label' => 'Featured Solution',
			'main_heading'   => '',
			'description'    => '',
			'bullet_1'       => '',
			'bullet_2'       => '',
			'bullet_3'       => '',
			'services'       => sln_get_default_our_services_items(),
			'results'        => sln_get_default_our_services_results(),
		)
	);

	return $tab;
}

/**
 * Default settings used on first save and as fallbacks.
 *
 * @return array
 */
function sln_get_our_services_default_settings() {
	$tab_slugs = sln_our_services_default_tab_slugs();

	$tabs = array(
		sln_build_default_our_services_tab(
			$tab_slugs[0],
			'Revenue Growth',
			array(
				'main_heading' => 'Grow Your Revenue With Intent',
				'description'  => 'We engineer high-performance marketing ecosystems designed to transform attention into measurable equity. By synchronizing precise audience acquisition with seamless conversion optimization, we turn your digital footprint into a predictable engine for sustainable financial growth.',
				'bullet_1'     => 'Scale With Clarity, Confidence, and a plan that performs.',
				'bullet_2'     => 'Unlock new opportunities through smarter campaigns and sharper targeting.',
				'bullet_3'     => 'Turn consistent marketing into predictable business growth.',
			)
		),
		sln_build_default_our_services_tab(
			$tab_slugs[1],
			'Lead Generation',
			array(
				'icon_flip'    => true,
				'main_heading' => 'Generate Leads That Actually Convert',
				'description'  => 'We build lead generation systems that attract high-intent prospects and move them into your pipeline with clarity. From paid acquisition to search visibility, every touchpoint is designed to capture demand and deliver leads your sales team can close.',
				'bullet_1'     => 'Attract qualified prospects with campaigns built around buyer intent.',
				'bullet_2'     => 'Improve lead quality with smarter targeting and stronger offers.',
				'bullet_3'     => 'Turn traffic into pipeline with landing pages and funnels that convert.',
			)
		),
		sln_build_default_our_services_tab(
			$tab_slugs[2],
			'Conversion & Retention',
			array(
				'main_heading' => 'Turn Traffic Into Loyal Customers',
				'description'  => 'We optimize every step of the customer journey so more visitors become buyers and more buyers come back. Through conversion-focused creative, retention flows, and lifecycle marketing, we help you maximize value from every customer relationship.',
				'bullet_1'     => 'Increase conversions with messaging and UX tuned for action.',
				'bullet_2'     => 'Reduce drop-off with smarter retargeting and follow-up sequences.',
				'bullet_3'     => 'Keep customers engaged with retention campaigns that drive repeat revenue.',
			)
		),
		sln_build_default_our_services_tab(
			$tab_slugs[3],
			'Brand Visibility',
			array(
				'main_heading' => 'Build Authority That Gets You Noticed',
				'description'  => 'We help your brand stand out in crowded markets through consistent visibility, strong positioning, and content that earns attention. From organic search to social presence, we create the momentum that keeps your business top of mind.',
				'bullet_1'     => 'Strengthen brand recall with cohesive messaging across channels.',
				'bullet_2'     => 'Grow organic visibility through SEO and content that ranks.',
				'bullet_3'     => 'Expand reach with social campaigns that build trust and engagement.',
			)
		),
		sln_build_default_our_services_tab(
			$tab_slugs[4],
			'Marketing Automation',
			array(
				'main_heading' => 'Automate Growth Without Losing the Human Touch',
				'description'  => 'We design marketing automation that saves time while delivering personalized experiences at scale. From lead nurturing to retention workflows, our systems keep your audience engaged and your team focused on high-impact work.',
				'bullet_1'     => 'Automate follow-ups so no lead or customer falls through the cracks.',
				'bullet_2'     => 'Deliver personalized journeys based on behavior and intent signals.',
				'bullet_3'     => 'Scale campaigns efficiently without sacrificing performance or quality.',
			)
		),
	);

	return array(
		'section' => array(
			'label'               => 'Our Services',
			'heading_lead'        => 'Designed To Attract,',
			'heading_highlight_1' => 'Convert',
			'heading_highlight_2' => 'Scale',
			'description'         => 'From visibility to conversions, our services help businesses grow through smarter strategy, stronger campaigns, and measurable results.',
		),
		'counter' => array(
			'enabled'  => true,
			'duration' => 2000,
		),
		'tabs'    => $tabs,
	);
}

/**
 * Parse a display value into counter parts.
 *
 * @param string $display Display value e.g. 320%, $20M.
 * @return array
 */
function sln_parse_our_services_counter_value( $display ) {
	$display = trim( (string) $display );
	$parsed  = array(
		'counter_value'  => 0,
		'counter_prefix' => '',
		'counter_suffix' => '',
		'display'        => $display,
	);

	if ( '' === $display ) {
		return $parsed;
	}

	if ( preg_match( '/^([^\d]*?)([\d]+(?:\.\d+)?)([^\d]*)$/', $display, $matches ) ) {
		$parsed['counter_prefix'] = $matches[1];
		$parsed['counter_value']  = (float) $matches[2];
		$parsed['counter_suffix'] = $matches[3];
	}

	return $parsed;
}

/**
 * Sanitize service items array.
 *
 * @param array $services Raw services.
 * @return array
 */
function sln_sanitize_our_services_items( $services ) {
	$output = array();

	if ( ! is_array( $services ) ) {
		return sln_get_default_our_services_items();
	}

	foreach ( $services as $service ) {
		if ( ! is_array( $service ) ) {
			continue;
		}

		$title = sanitize_text_field( $service['title'] ?? '' );
		$url   = esc_url_raw( $service['url'] ?? '' );

		if ( '' === $title && empty( $service['icon_id'] ) ) {
			continue;
		}

		$output[] = array(
			'icon_id' => sln_sanitize_media_attachment_id( $service['icon_id'] ?? 0, array( 'image/svg+xml' ) ),
			'title'   => $title,
			'url'     => '' !== $url ? $url : '#',
		);
	}

	while ( count( $output ) < SLN_OUR_SERVICES_MIN_SERVICES ) {
		$defaults = sln_get_default_our_services_items();
		$output[] = $defaults[ count( $output ) % count( $defaults ) ] ?? array(
			'icon_id' => 0,
			'title'   => '',
			'url'     => '#',
		);
	}

	return $output;
}

/**
 * Sanitize result blocks array.
 *
 * @param array $results Raw results.
 * @return array
 */
function sln_sanitize_our_services_results( $results ) {
	$output   = array();
	$defaults = sln_get_default_our_services_results();

	if ( is_array( $results ) ) {
		foreach ( $results as $result ) {
			if ( ! is_array( $result ) ) {
				continue;
			}

			$type = sanitize_key( $result['type'] ?? 'number' );

			if ( ! in_array( $type, array( 'number', 'logo' ), true ) ) {
				$type = 'number';
			}

			$output[] = array(
				'type'           => $type,
				'number_value'   => sanitize_text_field( $result['number_value'] ?? '' ),
				'number_subtext' => sanitize_text_field( $result['number_subtext'] ?? '' ),
				'logo_id'        => sln_sanitize_media_attachment_id(
					$result['logo_id'] ?? 0,
					array( 'image/webp', 'image/png', 'image/jpeg', 'image/jpg', 'image/gif', 'image/svg+xml' )
				),
				'logo_alt'       => sanitize_text_field( $result['logo_alt'] ?? '' ),
			);
		}
	}

	while ( count( $output ) < SLN_OUR_SERVICES_MIN_RESULTS ) {
		$output[] = $defaults[ count( $output ) ] ?? array(
			'type'           => 'number',
			'number_value'   => '',
			'number_subtext' => '',
			'logo_id'        => 0,
			'logo_alt'       => '',
		);
	}

	return array_slice( $output, 0, SLN_OUR_SERVICES_MIN_RESULTS );
}

/**
 * Normalize saved settings and migrate legacy global grids.
 *
 * @param array $saved Saved option data.
 * @return array
 */
function sln_normalize_our_services_settings( $saved ) {
	$defaults = sln_get_our_services_default_settings();
	$merged   = array_replace_recursive( $defaults, is_array( $saved ) ? $saved : array() );

	$legacy_services = array();
	$legacy_results  = array();

	if ( is_array( $saved ) && ! empty( $saved['services'] ) && is_array( $saved['services'] ) ) {
		$legacy_services = sln_sanitize_our_services_items( $saved['services'] );
	}

	if ( is_array( $saved ) && ! empty( $saved['results'] ) && is_array( $saved['results'] ) ) {
		$legacy_results = sln_sanitize_our_services_results( $saved['results'] );
	}

	unset( $merged['services'], $merged['results'] );

	$should_migrate_legacy = is_array( $saved )
		&& ( ! empty( $legacy_services ) || ! empty( $legacy_results ) )
		&& empty( $saved['tabs'][0]['services'] );

	if ( $should_migrate_legacy ) {
		foreach ( $merged['tabs'] as $index => $tab ) {
			if ( ! empty( $legacy_services ) ) {
				$merged['tabs'][ $index ]['services'] = $legacy_services;
			}

			if ( ! empty( $legacy_results ) ) {
				$merged['tabs'][ $index ]['results'] = $legacy_results;
			}
		}
	}

	foreach ( $merged['tabs'] as $index => $tab ) {
		if ( empty( $tab['services'] ) || ! is_array( $tab['services'] ) ) {
			$merged['tabs'][ $index ]['services'] = sln_get_default_our_services_items();
		}

		if ( empty( $tab['results'] ) || ! is_array( $tab['results'] ) ) {
			$merged['tabs'][ $index ]['results'] = sln_get_default_our_services_results();
		}

		$merged['tabs'][ $index ]['services'] = sln_sanitize_our_services_items( $merged['tabs'][ $index ]['services'] );
		$merged['tabs'][ $index ]['results']  = sln_sanitize_our_services_results( $merged['tabs'][ $index ]['results'] );
	}

	while ( count( $merged['tabs'] ) < 5 ) {
		$index    = count( $merged['tabs'] );
		$slugs    = sln_our_services_default_tab_slugs();
		$fallback = $defaults['tabs'][ $index ] ?? sln_build_default_our_services_tab(
			$slugs[ $index ] ?? sanitize_title( 'tab-' . ( $index + 1 ) ),
			'Tab ' . ( $index + 1 )
		);
		$merged['tabs'][] = $fallback;
	}

	$merged['tabs'] = array_slice( $merged['tabs'], 0, 5 );

	return $merged;
}

/**
 * Merge saved settings with defaults.
 *
 * @return array
 */
function sln_get_our_services_settings() {
	$saved = get_option( SLN_OUR_SERVICES_OPTION, array() );

	return sln_normalize_our_services_settings( $saved );
}

/**
 * Sanitize settings before saving.
 *
 * @param array $input Raw input.
 * @return array
 */
function sln_sanitize_our_services_settings( $input ) {
	$defaults = sln_get_our_services_default_settings();
	$output   = $defaults;

	if ( ! is_array( $input ) ) {
		return sln_normalize_our_services_settings( $output );
	}

	if ( isset( $input['section'] ) && is_array( $input['section'] ) ) {
		$output['section']['label']               = sanitize_text_field( $input['section']['label'] ?? '' );
		$output['section']['heading_lead']        = sanitize_text_field( $input['section']['heading_lead'] ?? '' );
		$output['section']['heading_highlight_1'] = sanitize_text_field( $input['section']['heading_highlight_1'] ?? '' );
		$output['section']['heading_highlight_2'] = sanitize_text_field( $input['section']['heading_highlight_2'] ?? '' );
		$output['section']['description']         = sanitize_textarea_field( $input['section']['description'] ?? '' );
	}

	if ( isset( $input['counter'] ) && is_array( $input['counter'] ) ) {
		$output['counter']['enabled']  = ! empty( $input['counter']['enabled'] );
		$output['counter']['duration'] = absint( $input['counter']['duration'] ?? 2000 );

		if ( $output['counter']['duration'] < 500 ) {
			$output['counter']['duration'] = 500;
		}

		if ( $output['counter']['duration'] > 10000 ) {
			$output['counter']['duration'] = 10000;
		}
	}

	if ( isset( $input['tabs'] ) && is_array( $input['tabs'] ) ) {
		$output['tabs'] = array();
		$default_slugs  = sln_our_services_default_tab_slugs();

		foreach ( $input['tabs'] as $index => $tab ) {
			if ( ! is_array( $tab ) ) {
				continue;
			}

			$slug = sanitize_title( $tab['slug'] ?? '' );

			if ( '' === $slug && isset( $default_slugs[ $index ] ) ) {
				$slug = $default_slugs[ $index ];
			}

			if ( '' === $slug ) {
				$slug = sanitize_title( $tab['tab_title'] ?? 'tab-' . ( $index + 1 ) );
			}

			$output['tabs'][] = array(
				'slug'           => $slug,
				'tab_title'      => sanitize_text_field( $tab['tab_title'] ?? '' ),
				'tab_icon_id'    => sln_sanitize_media_attachment_id( $tab['tab_icon_id'] ?? 0, array( 'image/svg+xml' ) ),
				'icon_flip'      => ! empty( $tab['icon_flip'] ),
				'featured_label' => sanitize_text_field( $tab['featured_label'] ?? '' ),
				'main_heading'   => sanitize_text_field( $tab['main_heading'] ?? '' ),
				'description'    => sanitize_textarea_field( $tab['description'] ?? '' ),
				'bullet_1'       => sanitize_text_field( $tab['bullet_1'] ?? '' ),
				'bullet_2'       => sanitize_text_field( $tab['bullet_2'] ?? '' ),
				'bullet_3'       => sanitize_text_field( $tab['bullet_3'] ?? '' ),
				'services'       => sln_sanitize_our_services_items( $tab['services'] ?? array() ),
				'results'        => sln_sanitize_our_services_results( $tab['results'] ?? array() ),
			);
		}

		while ( count( $output['tabs'] ) < 5 ) {
			$tab_index        = count( $output['tabs'] );
			$fallback         = $defaults['tabs'][ $tab_index ] ?? array();
			$fallback['slug'] = $default_slugs[ $tab_index ] ?? sanitize_title( 'tab-' . ( $tab_index + 1 ) );
			$output['tabs'][] = $fallback;
		}

		$output['tabs'] = array_slice( $output['tabs'], 0, 5 );
	}

	return sln_normalize_our_services_settings( $output );
}

/**
 * Prepare service items for frontend rendering.
 *
 * @param array $services Service settings rows.
 * @return array
 */
function sln_prepare_our_services_items( $services ) {
	if ( ! is_array( $services ) ) {
		return array();
	}

	return array_values(
		array_filter(
			$services,
			function ( $service ) {
				return ! empty( $service['title'] );
			}
		)
	);
}

/**
 * Prepare result blocks for frontend rendering.
 *
 * @param array $results Result settings rows.
 * @return array
 */
function sln_prepare_our_services_results( $results ) {
	if ( ! is_array( $results ) ) {
		return array();
	}

	return array_values(
		array_filter(
			array_map( 'sln_prepare_our_services_result', $results )
		)
	);
}

/**
 * Prepare result block for frontend rendering.
 *
 * @param array $result Result settings row.
 * @return array|null
 */
function sln_prepare_our_services_result( $result ) {
	if ( ! is_array( $result ) ) {
		return null;
	}

	if ( 'logo' === ( $result['type'] ?? '' ) ) {
		$logo_id = absint( $result['logo_id'] ?? 0 );

		if ( ! $logo_id ) {
			return null;
		}

		$src = wp_get_attachment_image_url( $logo_id, 'full' );

		if ( ! $src ) {
			return null;
		}

		return array(
			'type' => 'logo',
			'src'  => $src,
			'alt'  => $result['logo_alt'] ?? '',
		);
	}

	$parsed = sln_parse_our_services_counter_value( $result['number_value'] ?? '' );

	if ( '' === $parsed['display'] ) {
		return null;
	}

	return array(
		'type'           => 'stat',
		'value'          => $parsed['display'],
		'counter_value'  => $parsed['counter_value'],
		'counter_prefix' => $parsed['counter_prefix'],
		'counter_suffix' => $parsed['counter_suffix'],
		'label'          => $result['number_subtext'] ?? '',
	);
}

/**
 * Register settings.
 */
function sln_register_our_services_settings() {
	register_setting(
		'sln_our_services_settings_group',
		SLN_OUR_SERVICES_OPTION,
		array(
			'type'              => 'array',
			'sanitize_callback' => 'sln_sanitize_our_services_settings',
			'default'           => sln_get_our_services_default_settings(),
		)
	);
}
add_action( 'admin_init', 'sln_register_our_services_settings' );
