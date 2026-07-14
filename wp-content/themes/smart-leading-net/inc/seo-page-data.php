<?php
/**
 * SEO Main Page — section data and helpers.
 *
 * @package Smart_Leading_Net
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * SEO page button — plain theme buttons or arrow sls-btn CTAs.
 *
 * @param array $args {
 *     @type string $text    Button label.
 *     @type string $url     Link URL (links only).
 *     @type string $variant primary|secondary.
 *     @type bool   $arrow   Use sls-btn arrow CTA when true.
 *     @type string $class   Extra classes.
 *     @type string $type    link|button.
 * }
 */
function sln_render_seo_page_button( $args = array() ) {
	$args = wp_parse_args(
		$args,
		array(
			'text'    => '',
			'url'     => '#',
			'variant' => 'primary',
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
			'class'      => trim( 'seo-page__cta ' . $args['class'] ),
		)
	);
}

/**
 * Inline checkmark SVG used across SEO page lists.
 *
 * @return string
 */
function sln_seo_page_check_icon() {
	return '<svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" aria-hidden="true"><path d="M20 6L9 17l-5-5"/></svg>';
}

/**
 * Arrow icon for CTA buttons.
 *
 * @return string
 */
function sln_seo_page_arrow_icon() {
	return '<svg class="seo-page__btn-arrow" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" aria-hidden="true"><path d="M5 12h14M13 6l6 6-6 6"/></svg>';
}

/**
 * Site contact details reused on the SEO page.
 *
 * @return array<string, string>
 */
function sln_get_seo_page_contact_details() {
	return array(
		'phone_display' => '+1 (512) 764-7877',
		'phone_href'    => 'tel:+15127647877',
		'email'         => 'admin@smartleading.net',
		'google_partner_url' => 'https://www.google.com/partners/agency?id=7238450490',
	);
}

/**
 * Hero trust metrics aligned with homepage testimonials stats.
 *
 * @return array<string, string>
 */
function sln_get_seo_page_hero_trust_stats() {
	return array(
		'revenue' => '$50M+',
		'rating'  => '4.9★',
		'reviews' => '28K+',
	);
}

/**
 * SERP widget animation data for the hero panel.
 *
 * @return array<string, mixed>
 */
function sln_get_seo_page_serp_data() {
	return array(
		'keyword'   => __( 'best [your service] near me
', 'smart-leading-net' ),
		'positions' => array( 9, 6, 4, 2, 1 ),
		'traffic'   => array( '+12%', '+58%', '+140%', '+255%', '+312%' ),
	);
}

/**
 * Client names for the logos strip (from credibility roster).
 *
 * @return array<int, string>
 */
function sln_get_seo_page_client_names() {
	$map = array(
		'badger-granite-logo'              => 'Badger Granite',
		'noveMed-urgent-logo'              => 'NovaMed',
		'cypress-dental-logo'              => 'Cypress Dental',
		'anatolia_logo'                    => 'Anatolia',
		'keystone-logo'                    => 'Keystone',
		'cabinetsmke_logo'                 => 'Icon Kitchen & Bath',
		'clarksville_standard_logo_bold_v2' => 'Clarksville Standard',
		'glowup-dentistry'                 => 'GlowUp Dentistry',
	);

	$names = array();

	foreach ( sln_get_credibility_logos() as $logo ) {
		$basename = isset( $logo['image'] ) ? (string) $logo['image'] : '';
		$key      = strtolower( $basename );

		if ( isset( $map[ $basename ] ) ) {
			$names[] = $map[ $basename ];
		} elseif ( isset( $map[ $key ] ) ) {
			$names[] = $map[ $key ];
		} elseif ( $basename ) {
			$names[] = ucwords( str_replace( array( '-', '_' ), ' ', $basename ) );
		}

		if ( count( $names ) >= 6 ) {
			break;
		}
	}

	if ( empty( $names ) ) {
		return array_values( $map );
	}

	return array_slice( array_unique( $names ), 0, 6 );
}

/**
 * Pain point cards.
 *
 * @return array<int, array<string, string>>
 */
function sln_get_seo_page_pain_points() {
	$cards = sln_get_seo_services_reality_cards();

	return array_map(
		static function ( $card ) {
			return array(
				'title' => $card['title'] ?? '',
				'text'  => sln_seo_services_plain_text( $card['description'] ?? '' ),
				'icon'  => $card['icon_slug'] ?? 'search-minus',
			);
		},
		$cards
	);
}

/**
 * SEO service cards.
 *
 * @return array<int, array<string, mixed>>
 */
function sln_get_seo_page_services() {
	$cards = sln_get_seo_services_program_cards();

	return array_map(
		static function ( $card ) {
			return array(
				'title' => $card['title'] ?? '',
				'text'  => sln_seo_services_plain_text( $card['description'] ?? '' ),
				'items' => is_array( $card['bullets'] ?? null ) ? $card['bullets'] : array(),
			);
		},
		$cards
	);
}

/**
 * Why choose Smart Leading cells.
 *
 * @return array<int, array<string, string>>
 */
function sln_get_seo_page_why_choose() {
	$blocks = sln_get_seo_services_results_blocks();

	return array_map(
		static function ( $block ) {
			return array(
				'number' => $block['number'] ?? '',
				'title'  => $block['label'] ?? '',
				'text'   => sln_seo_services_plain_text( $block['description'] ?? '' ),
			);
		},
		$blocks
	);
}

/**
 * SEO process steps.
 *
 * @return array<int, array<string, string>>
 */
function sln_get_seo_page_process_steps() {
	$steps = sln_get_seo_services_process_steps();

	return array_map(
		static function ( $step ) {
			return array(
				'title' => $step['title'] ?? '',
				'text'  => sln_seo_services_plain_text( $step['description'] ?? '' ),
			);
		},
		$steps
	);
}

/**
 * Whether the current request is the SEO Services page template.
 *
 * @return bool
 */
function sln_is_seo_services_page() {
	return is_page_template( 'seo-page-template.php' );
}

/**
 * Case Studies section header — edit here for the SEO page.
 *
 * @return array<string, string>
 */
function sln_get_seo_page_case_studies_section_settings() {
	return array(
		'label'          => __( 'Case Studies', 'smart-leading-net' ),
		'main_heading'   => __( 'Proven Results For', 'smart-leading-net' ),
		'highlight_word' => __( 'Growth-Focused Businesses', 'smart-leading-net' ),
		'description'    => __( 'See how we help brands turn strategy, paid media, websites, and optimisation into measurable growth through smart execution and data-backed decisions.', 'smart-leading-net' ),
		'more_link_text' => '',
		'more_link_url'  => '',
	);
}

/**
 * Case Studies cards — edit here for the SEO page.
 *
 * @return array<int, array<string, mixed>>
 */
function sln_get_seo_page_case_studies_cards_raw() {
	$icon_files = sln_get_growth_page_case_studies_default_icon_files();

	return array(
		array(
			'title'              => __( 'Case Study: Manufacturing', 'smart-leading-net' ),
			'metric_value'       => '30%',
			'metric_description' => __( 'ROI Increase From Paid Search', 'smart-leading-net' ),
			'icon_id'            => 0,
			'icon_fallback'      => $icon_files[0],
			'theme_color'        => '#12b8b8',
			'tags'               => array(),
			'active'             => true,
		),
		array(
			'title'              => __( 'Case Study: ECommerce', 'smart-leading-net' ),
			'metric_value'       => '4.2X',
			'metric_description' => __( 'Higher Return On Ad Spend', 'smart-leading-net' ),
			'icon_id'            => 0,
			'icon_fallback'      => $icon_files[1],
			'theme_color'        => '#f36b32',
			'tags'               => array(
				__( 'Data-Backed Strategies. Real Business Impact.', 'smart-leading-net' ),
			),
			'active'             => true,
		),
		array(
			'title'              => __( 'Case Study: Hospitality', 'smart-leading-net' ),
			'metric_value'       => '260%',
			'metric_description' => __( 'Increase In Organic Revenue', 'smart-leading-net' ),
			'icon_id'            => 0,
			'icon_fallback'      => $icon_files[2],
			'theme_color'        => '#1f4e9e',
			'tags'               => array(
				__( 'More Traffic', 'smart-leading-net' ),
				__( 'Higher Rankings', 'smart-leading-net' ),
				__( 'More Revenue', 'smart-leading-net' ),
			),
			'active'             => true,
		),
	);
}

/**
 * Case Studies data formatted for the shared growth-page template.
 *
 * @return array<string, mixed>
 */
function sln_get_seo_page_case_studies_data() {
	return sln_get_seo_services_case_studies_data();
}

/**
 * Price Plan section header — edit here for the SEO page.
 *
 * @return array<string, string>
 */
function sln_get_seo_page_price_plan_section_settings() {
	return array(
		'label'          => __( 'Pricing Plans', 'smart-leading-net' ),
		'heading_lead'   => __( 'Transparent', 'smart-leading-net' ),
		'highlight_word' => __( 'Revenue Growth Pricing', 'smart-leading-net' ),
		'heading_trail'  => '',
		'description'    => __( 'Tailored to your business goals. Whether you\'re just starting or ready to scale aggressively, we have a plan designed to turn your marketing spend into predictable revenue.', 'smart-leading-net' ),
	);
}

/**
 * Price Plan cards — edit here for the SEO page.
 *
 * @return array<int, array<string, mixed>>
 */
function sln_get_seo_page_price_plan_cards_raw() {
	return array(
		array(
			'plan_name'    => __( 'BASIC', 'smart-leading-net' ),
			'price'        => '$999',
			'price_suffix' => __( '/ month', 'smart-leading-net' ),
			'description'  => __( 'Essential paid media management for businesses ready to start generating consistent revenue from search and social.', 'smart-leading-net' ),
			'features'     => array(
				__( 'Google Ads campaign management', 'smart-leading-net' ),
				__( 'Monthly performance reporting', 'smart-leading-net' ),
				__( 'Landing page recommendations', 'smart-leading-net' ),
				__( 'Email support', 'smart-leading-net' ),
			),
			'button_text'  => __( 'Get Started', 'smart-leading-net' ),
			'button_url'   => '#seo-proposal',
			'is_popular'   => false,
			'badge_text'   => __( 'MOST POPULAR', 'smart-leading-net' ),
			'active'       => true,
		),
		array(
			'plan_name'    => __( 'GROWTH', 'smart-leading-net' ),
			'price'        => '$2,499',
			'price_suffix' => __( '/ month', 'smart-leading-net' ),
			'description'  => __( 'Full-funnel growth for brands scaling paid acquisition with optimization across channels.', 'smart-leading-net' ),
			'features'     => array(
				__( 'Everything in Basic', 'smart-leading-net' ),
				__( 'Meta Ads management', 'smart-leading-net' ),
				__( 'Conversion rate optimization', 'smart-leading-net' ),
				__( 'Bi-weekly strategy calls', 'smart-leading-net' ),
				__( 'Dedicated account manager', 'smart-leading-net' ),
			),
			'button_text'  => __( 'Get Started', 'smart-leading-net' ),
			'button_url'   => '#seo-proposal',
			'is_popular'   => true,
			'badge_text'   => __( 'MOST POPULAR', 'smart-leading-net' ),
			'active'       => true,
		),
		array(
			'plan_name'    => __( 'PRO', 'smart-leading-net' ),
			'price'        => __( 'Custom pricing', 'smart-leading-net' ),
			'price_suffix' => '',
			'description'  => __( 'Enterprise-level revenue growth partnerships with custom strategy, integrations, and dedicated support.', 'smart-leading-net' ),
			'features'     => array(
				__( 'Full-funnel revenue strategy', 'smart-leading-net' ),
				__( 'Multi-channel campaign management', 'smart-leading-net' ),
				__( 'Custom analytics & attribution', 'smart-leading-net' ),
				__( 'Priority support & consulting', 'smart-leading-net' ),
				__( 'Custom CRM & platform integrations', 'smart-leading-net' ),
			),
			'button_text'  => __( 'Get a Custom Quote', 'smart-leading-net' ),
			'button_url'   => '#seo-proposal',
			'is_popular'   => false,
			'badge_text'   => __( 'MOST POPULAR', 'smart-leading-net' ),
			'active'       => true,
		),
	);
}

/**
 * Price Plan data formatted for the shared growth-page template.
 *
 * @return array<string, mixed>
 */
function sln_get_seo_page_price_plan_data() {
	return sln_get_seo_services_pricing_data();
}

/**
 * Testimonials section header — edit here for the SEO page.
 *
 * @return array<string, string>
 */
function sln_get_seo_page_testimonials_section_settings() {
	return array(
		'label'          => __( 'Testimonials', 'smart-leading-net' ),
		'heading_lead'   => __( 'Trusted Partnerships Built On', 'smart-leading-net' ),
		'highlight_word' => __( 'Results', 'smart-leading-net' ),
	);
}

/**
 * Testimonials summary footer — edit here for the SEO page.
 *
 * @return array<string, mixed>
 */
function sln_get_seo_page_testimonials_summary_settings() {
	return array(
		'review_title'  => __( '28k+ Client Reviews', 'smart-leading-net' ),
		'star_rating'   => 5,
		'verified_text' => __( 'Verified', 'smart-leading-net' ),
	);
}

/**
 * Testimonials stats — edit here for the SEO page.
 *
 * @return array<int, array<string, mixed>>
 */
function sln_get_seo_page_testimonials_stats_raw() {
	$icons = sln_get_growth_page_testimonials_default_stat_icons();

	return array(
		array(
			'icon_id'          => 0,
			'icon_fallback'    => $icons[0],
			'counter_value'    => '28',
			'counter_prefix'   => '',
			'counter_suffix'   => 'K+',
			'counter_decimals' => 0,
			'label'            => __( 'Client Reviews', 'smart-leading-net' ),
		),
		array(
			'icon_id'          => 0,
			'icon_fallback'    => $icons[1],
			'counter_value'    => '4.9',
			'counter_prefix'   => '',
			'counter_suffix'   => '★',
			'counter_decimals' => 1,
			'label'            => __( 'Average Rating', 'smart-leading-net' ),
		),
		array(
			'icon_id'          => 0,
			'icon_fallback'    => $icons[2],
			'counter_value'    => '200',
			'counter_prefix'   => '',
			'counter_suffix'   => '+',
			'counter_decimals' => 0,
			'label'            => __( 'Website Build', 'smart-leading-net' ),
		),
		array(
			'icon_id'          => 0,
			'icon_fallback'    => $icons[3],
			'counter_value'    => '50',
			'counter_prefix'   => '$',
			'counter_suffix'   => 'M+',
			'counter_decimals' => 0,
			'label'            => __( 'Revenue Generated', 'smart-leading-net' ),
		),
	);
}

/**
 * Testimonial reviews — edit here for the SEO page.
 *
 * @return array<int, array<string, mixed>>
 */
function sln_get_seo_page_testimonials_reviews_raw() {
	return array(
		array(
			'rating'          => 5,
			'text'            => __( 'Highly cooperative and honest with their work. They developed my business website and I am super happy with them. On time delivery and 24hrs support. They also manage our social media — they know what they\'re doing.', 'smart-leading-net' ),
			'author_initials' => 'SM',
			'author_name'     => __( 'Sarah Mitchell', 'smart-leading-net' ),
			'author_title'    => __( 'Growth Labs', 'smart-leading-net' ),
			'active'          => true,
		),
		array(
			'rating'          => 5,
			'text'            => __( 'Before working with Smart Leading, our campaigns lacked direction. They helped us build a clear strategy, improve conversions, and understand exactly where our growth was coming from.', 'smart-leading-net' ),
			'author_initials' => 'JC',
			'author_name'     => __( 'James Carter', 'smart-leading-net' ),
			'author_title'    => __( 'Managing Director', 'smart-leading-net' ),
			'active'          => true,
		),
	);
}

/**
 * Testimonials data formatted for the shared growth-page template.
 *
 * @return array<string, mixed>
 */
function sln_get_seo_page_testimonials_data() {
	return sln_get_seo_services_testimonials_data();
}

/**
 * FAQ items for accordion and JSON-LD.
 *
 * @return array<int, array<string, string>>
 */
function sln_get_seo_page_faq_items() {
	return sln_get_seo_services_faq_items();
}

/**
 * FAQ JSON-LD schema.
 *
 * @return array<string, mixed>
 */
function sln_get_seo_page_faq_schema() {
	return sln_get_seo_services_faq_schema();
}
