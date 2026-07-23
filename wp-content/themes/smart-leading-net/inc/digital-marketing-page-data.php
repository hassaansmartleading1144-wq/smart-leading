<?php
/**
 * Digital Marketing landing page — thin presentation helpers.
 *
 * Section defaults and meta getters live in digital-marketing-helpers.php.
 *
 * @package Smart_Leading_Net
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Digital Marketing page button — reuses homepage/SEO sls-btn CTA.
 *
 * @param array $args {
 *     @type string $text    Button label.
 *     @type string $url     Link URL.
 *     @type string $variant primary|secondary|outline|white|ghost.
 *     @type string $class   Extra classes.
 *     @type bool   $arrow   Show arrow circle (default true).
 * }
 */
function sln_render_dm_page_button( $args = array() ) {
	$args = wp_parse_args(
		$args,
		array(
			'text'    => '',
			'url'     => '#',
			'variant' => 'primary',
			'class'   => '',
			'arrow'   => true,
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
			'variant'    => $variant,
			'show_arrow' => ! empty( $args['arrow'] ),
			'class'      => trim( 'sln-dm-cta ' . $args['class'] ),
		)
	);
}

/**
 * Whether the current request uses the Digital Marketing Services page template.
 *
 * @return bool
 */
function sln_is_digital_marketing_services_page() {
	return is_page_template( 'digital-marketing-page-template.php' );
}

/**
 * Back-compat alias.
 *
 * @return bool
 */
function sln_is_digital_marketing_page() {
	return sln_is_digital_marketing_services_page();
}

/**
 * Strategy call CTA destination.
 *
 * @return string
 */
function sln_get_dm_page_strategy_cta_url() {
	return sln_get_dm_page_contact_url();
}

/**
 * Contact / CTA destination for the Digital Marketing page.
 *
 * @return string
 */
function sln_get_dm_page_contact_url() {
	$contact = get_page_by_path( 'contact-us' );

	if ( $contact instanceof WP_Post ) {
		return get_permalink( $contact );
	}

	return home_url( '/contact-us/' );
}

/**
 * Back-compat wrappers — prefer sln_get_dm_* helpers.
 *
 * @return array
 */
function sln_get_dm_page_hero_stats() {
	return sln_get_dm_hero_stats();
}

/**
 * @return array
 */
function sln_get_dm_page_pain_points() {
	return sln_get_dm_reality_cards();
}

/**
 * @return array
 */
function sln_get_dm_page_approach_items() {
	return sln_get_dm_approach_items();
}

/**
 * @return array
 */
function sln_get_dm_page_services() {
	return sln_get_dm_services_items();
}

/**
 * @return array
 */
function sln_get_dm_page_paid_channels() {
	return sln_get_dm_ads_channels();
}

/**
 * @return array
 */
function sln_get_dm_page_process_steps() {
	return sln_get_dm_process_steps();
}

/**
 * @return array
 */
function sln_get_dm_page_case_studies() {
	return sln_get_dm_case_studies();
}

/**
 * @return array
 */
function sln_get_dm_page_pricing_plans() {
	return sln_get_dm_pricing_plans();
}

/**
 * @return array
 */
function sln_get_dm_page_faq_items() {
	return sln_get_dm_faq_items();
}

/**
 * @return array
 */
function sln_get_dm_page_final_checks() {
	$cta = sln_get_dm_final_cta();
	$out = array();

	foreach ( (array) ( $cta['benefits'] ?? array() ) as $row ) {
		if ( is_array( $row ) && ! empty( $row['text'] ) ) {
			$out[] = $row['text'];
		} elseif ( is_string( $row ) && '' !== $row ) {
			$out[] = $row;
		}
	}

	return $out;
}

/**
 * Legacy reality cards alias.
 *
 * @return array
 */
function sln_get_dm_page_reality_cards() {
	return sln_get_dm_reality_cards();
}
