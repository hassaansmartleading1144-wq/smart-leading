<?php
/**
 * Growth Pages — section registry, ordering, and render functions.
 *
 * @package Smart_Leading_Net
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

define( 'SLN_GP_SECTION_ORDERS_META', '_sln_gp_section_orders' );

/**
 * Registered growth page sections.
 *
 * @return array<string, array<string, string>>
 */
function sln_get_growth_page_section_registry() {
	return array(
		'banner'          => array(
			'label'    => __( 'Banner', 'smart-leading-net' ),
			'callback' => 'growth_page_banner',
		),
		'client_story'    => array(
			'label'    => __( 'Client Story', 'smart-leading-net' ),
			'callback' => 'growth_page_client_story',
		),
		'services'        => array(
			'label'    => __( 'Services', 'smart-leading-net' ),
			'callback' => 'growth_page_services',
		),
		'how_work'        => array(
			'label'    => __( 'How Work', 'smart-leading-net' ),
			'callback' => 'growth_page_how_work',
		),
		'growth_services' => array(
			'label'    => __( 'Growth Services', 'smart-leading-net' ),
			'callback' => 'growth_page_growth_services',
		),
		'case_studies'    => array(
			'label'    => __( 'Case Studies', 'smart-leading-net' ),
			'callback' => 'growth_page_case_studies',
		),
		'why_choose'      => array(
			'label'    => __( 'Why Choose', 'smart-leading-net' ),
			'callback' => 'growth_page_why_choose',
		),
		'price_plan'      => array(
			'label'    => __( 'Price Plan', 'smart-leading-net' ),
			'callback' => 'growth_page_price_plan',
		),
		'testimonials'    => array(
			'label'    => __( 'Testimonials', 'smart-leading-net' ),
			'callback' => 'growth_page_testimonials',
		),
		'cta_banner'      => array(
			'label'    => __( 'CTA Banner', 'smart-leading-net' ),
			'callback' => 'growth_page_cta_banner',
		),
	);
}

/**
 * Default section display order.
 *
 * @return array<string, int>
 */
function sln_get_growth_page_default_section_orders() {
	return array(
		'banner'          => 1,
		'client_story'    => 2,
		'services'        => 3,
		'how_work'        => 4,
		'growth_services' => 5,
		'case_studies'    => 6,
		'why_choose'      => 7,
		'price_plan'      => 8,
		'testimonials'    => 9,
		'cta_banner'      => 10,
	);
}

/**
 * Get section order values for a Growth Page.
 *
 * @param int|null $post_id Post ID.
 * @return array<string, int>
 */
function sln_get_growth_page_section_orders( $post_id = null ) {
	$post_id = $post_id ? absint( $post_id ) : get_the_ID();

	if ( ! $post_id ) {
		return sln_get_growth_page_default_section_orders();
	}

	$stored   = get_post_meta( $post_id, SLN_GP_SECTION_ORDERS_META, true );
	$defaults = sln_get_growth_page_default_section_orders();
	$stored   = is_array( $stored ) ? $stored : array();
	$orders   = array();

	foreach ( $defaults as $section => $default_order ) {
		$orders[ $section ] = isset( $stored[ $section ] ) ? absint( $stored[ $section ] ) : $default_order;
	}

	return $orders;
}

/**
 * Render all Growth Page sections sorted by admin order.
 *
 * @param int|null $post_id Post ID.
 */
function sln_render_growth_page_sections( $post_id = null ) {
	$post_id  = $post_id ? absint( $post_id ) : get_the_ID();
	$registry = sln_get_growth_page_section_registry();
	$orders   = sln_get_growth_page_section_orders( $post_id );

	asort( $orders, SORT_NUMERIC );

	foreach ( $orders as $section_key => $order ) {
		if ( ! isset( $registry[ $section_key ]['callback'] ) ) {
			continue;
		}

		$callback = $registry[ $section_key ]['callback'];

		if ( is_callable( $callback ) ) {
			call_user_func( $callback, $post_id );
		}
	}
}

/**
 * Render the Growth Page hero banner section.
 *
 * @param int|null $post_id Post ID.
 */
function growth_page_banner( $post_id = null ) {
	get_template_part( 'template-parts/growth-pages/hero', 'banner' );
}

/**
 * Render the Growth Page services section.
 *
 * @param int|null $post_id Post ID.
 */
function growth_page_services( $post_id = null ) {
	$services = sln_get_growth_page_services( $post_id );

	if ( empty( $services['cards'] ) ) {
		return;
	}

	get_template_part( 'template-parts/growth-pages/convert', 'scale' );
}

/**
 * Render the Growth Page client story section.
 *
 * @param int|null $post_id Post ID.
 */
function growth_page_client_story( $post_id = null ) {
	if ( ! sln_growth_page_client_story_has_content( $post_id ) ) {
		return;
	}

	get_template_part( 'template-parts/growth-pages/client', 'story' );
}

/**
 * Render the Growth Page how work section.
 *
 * @param int|null $post_id Post ID.
 */
function growth_page_how_work( $post_id = null ) {
	if ( ! sln_growth_page_how_work_has_content( $post_id ) ) {
		return;
	}

	get_template_part( 'template-parts/growth-pages/how', 'work' );
}

/**
 * Render the Growth Page growth services section.
 *
 * @param int|null $post_id Post ID.
 */
function growth_page_growth_services( $post_id = null ) {
	if ( ! sln_growth_page_growth_services_has_content( $post_id ) ) {
		return;
	}

	get_template_part( 'template-parts/growth-pages/growth', 'services' );
}

/**
 * Render the Growth Page case studies section.
 *
 * @param int|null $post_id Post ID.
 */
function growth_page_case_studies( $post_id = null ) {
	if ( ! sln_growth_page_case_studies_has_content( $post_id ) ) {
		return;
	}

	get_template_part( 'template-parts/growth-pages/case', 'studies' );
}

/**
 * Render the Growth Page why choose section.
 *
 * @param int|null $post_id Post ID.
 */
function growth_page_why_choose( $post_id = null ) {
	if ( ! sln_growth_page_why_choose_has_content( $post_id ) ) {
		return;
	}

	get_template_part( 'template-parts/growth-pages/why', 'choose' );
}

/**
 * Render the Growth Page price plan section.
 *
 * @param int|null $post_id Post ID.
 */
function growth_page_price_plan( $post_id = null ) {
	if ( ! sln_growth_page_price_plan_has_content( $post_id ) ) {
		return;
	}

	get_template_part( 'template-parts/growth-pages/price', 'plan' );
}

/**
 * Render the Growth Page testimonials section.
 *
 * @param int|null $post_id Post ID.
 */
function growth_page_testimonials( $post_id = null ) {
	if ( ! sln_growth_page_testimonials_has_content( $post_id ) ) {
		return;
	}

	get_template_part( 'template-parts/growth-pages/testimonials' );
}

/**
 * Render the Growth Page CTA Banner section.
 *
 * @param int|null $post_id Post ID.
 */
function growth_page_cta_banner( $post_id = null ) {
	if ( ! sln_growth_page_cta_banner_has_content( $post_id ) ) {
		return;
	}

	get_template_part( 'template-parts/growth-pages/cta', 'banner' );
}
