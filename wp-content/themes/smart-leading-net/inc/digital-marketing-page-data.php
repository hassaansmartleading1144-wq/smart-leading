<?php
/**
 * Digital Marketing Services page — section data and helpers.
 *
 * @package Smart_Leading_Net
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Digital Marketing page button helper.
 *
 * @param array $args {
 *     @type string $text    Button label.
 *     @type string $url     Link URL.
 *     @type string $variant primary|secondary|white.
 *     @type bool   $arrow   Show arrow CTA.
 *     @type string $class   Extra classes.
 *     @type string $type    link|button|submit.
 * }
 */
function sln_render_dm_page_button( $args = array() ) {
	$args = wp_parse_args(
		$args,
		array(
			'text'    => '',
			'url'     => '#',
			'variant' => 'secondary',
			'arrow'   => false,
			'class'   => '',
			'type'    => 'link',
		)
	);

	if ( '' === $args['text'] ) {
		return;
	}

	sln_render_cta_button(
		array(
			'text'       => $args['text'],
			'url'        => $args['url'],
			'type'       => $args['type'],
			'variant'    => $args['variant'],
			'show_arrow' => ! empty( $args['arrow'] ),
			'class'      => trim( 'dm-page__cta ' . $args['class'] ),
		)
	);
}

/**
 * Strategy call CTA destination.
 *
 * @return string
 */
function sln_get_dm_page_strategy_cta_url() {
	$page = get_page_by_path( 'contact-us' );

	if ( $page instanceof WP_Post ) {
		return get_permalink( $page );
	}

	return home_url( '/contact-us/' );
}

/**
 * Hero metric cards.
 *
 * @return array<int, array<string, string>>
 */
function sln_get_dm_page_hero_metrics() {
	return array(
		array(
			'value' => '3.2x',
			'label' => __( 'Average Ad ROAS', 'smart-leading-net' ),
			'tone'  => 'accent',
		),
		array(
			'value' => '+300%',
			'label' => __( 'Lead Volume Growth', 'smart-leading-net' ),
			'tone'  => 'primary',
		),
		array(
			'value' => '$50M+',
			'label' => __( 'Sales Generated', 'smart-leading-net' ),
			'tone'  => 'accent',
		),
		array(
			'value' => '+220%',
			'label' => __( 'Avg. Revenue Growth', 'smart-leading-net' ),
			'tone'  => 'primary',
		),
		array(
			'value' => __( '6 Weeks', 'smart-leading-net' ),
			'label' => __( 'To Measurable Results', 'smart-leading-net' ),
			'tone'  => 'accent',
		),
		array(
			'value' => '150+',
			'label' => __( 'Businesses Scaled', 'smart-leading-net' ),
			'tone'  => 'primary',
		),
	);
}

/**
 * Reality section challenge cards.
 *
 * @return array<int, array<string, string>>
 */
function sln_get_dm_page_reality_cards() {
	return array(
		array(
			'title' => __( 'Revenue Feels Stuck', 'smart-leading-net' ),
			'text'  => __( 'Your business works hard, but monthly revenue isn\'t growing the way it should.', 'smart-leading-net' ),
			'icon'  => 'revenue-stuck',
		),
		array(
			'title' => __( 'Sales Are Inconsistent', 'smart-leading-net' ),
			'text'  => __( 'Some months bring good inquiries; others feel slow and unpredictable.', 'smart-leading-net' ),
			'icon'  => 'sales-inconsistent',
		),
		array(
			'title' => __( 'Poor Lead Quality', 'smart-leading-net' ),
			'text'  => __( 'You get inquiries, but many just ask prices and never become serious customers.', 'smart-leading-net' ),
			'icon'  => 'poor-leads',
		),
		array(
			'title' => __( 'Leads Aren\'t Converting', 'smart-leading-net' ),
			'text'  => __( 'People show interest but don\'t take the next step — call, book, visit or buy.', 'smart-leading-net' ),
			'icon'  => 'not-converting',
		),
		array(
			'title' => __( 'Competitors Win First', 'smart-leading-net' ),
			'text'  => __( 'Even when your service is better, rivals with a stronger online presence get noticed first.', 'smart-leading-net' ),
			'icon'  => 'competitors',
		),
		array(
			'title' => __( 'Weak Brand Trust', 'smart-leading-net' ),
			'text'  => __( 'People find you online but hesitate, because your brand doesn\'t build confidence fast.', 'smart-leading-net' ),
			'icon'  => 'brand-trust',
		),
		array(
			'title' => __( 'No Clear Offer', 'smart-leading-net' ),
			'text'  => __( 'Customers don\'t instantly understand why they should choose you over someone else.', 'smart-leading-net' ),
			'icon'  => 'no-offer',
		),
		array(
			'title' => __( 'Missed Follow-Ups', 'smart-leading-net' ),
			'text'  => __( 'Interested leads slip away — there\'s no proper follow-up system to bring them back.', 'smart-leading-net' ),
			'icon'  => 'missed-followups',
		),
		array(
			'title' => __( 'Low Online Visibility', 'smart-leading-net' ),
			'text'  => __( 'Customers are searching, but you\'re not showing up strongly on Google or social.', 'smart-leading-net' ),
			'icon'  => 'low-visibility',
		),
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
