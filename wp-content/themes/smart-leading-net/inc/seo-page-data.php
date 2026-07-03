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
	return array(
		array(
			'title' => __( 'Invisible On Page One', 'smart-leading-net' ),
			'text'  => __( 'You rank below the fold — or nowhere — for the high-intent keywords your customers type when they\'re ready to buy.', 'smart-leading-net' ),
			'icon'  => 'search-minus',
		),
		array(
			'title' => __( 'Traffic That Never Converts', 'smart-leading-net' ),
			'text'  => __( 'Visitors arrive but don\'t become leads. The wrong keywords, slow pages, and weak intent leave your funnel leaking.', 'smart-leading-net' ),
			'icon'  => 'chart',
		),
		array(
			'title' => __( 'Technical Issues Holding You Back', 'smart-leading-net' ),
			'text'  => __( 'Crawl errors, slow Core Web Vitals, and broken architecture quietly cap your rankings no matter how good your content is.', 'smart-leading-net' ),
			'icon'  => 'technical',
		),
		array(
			'title' => __( 'Agencies That Report Vanity Metrics', 'smart-leading-net' ),
			'text'  => __( 'Impressions and keyword counts look nice in a deck but never tie back to pipeline, revenue, or return on investment.', 'smart-leading-net' ),
			'icon'  => 'lock',
		),
		array(
			'title' => __( 'Losing Ground To Competitors', 'smart-leading-net' ),
			'text'  => __( 'While you wait, competitors publish, earn links, and climb — compounding an advantage that gets harder to overtake.', 'smart-leading-net' ),
			'icon'  => 'clock',
		),
		array(
			'title' => __( 'Invisible To AI Search', 'smart-leading-net' ),
			'text'  => __( 'AI Overviews and answer engines now intercept clicks. If your content isn\'t structured for them, you\'re missing the new front page.', 'smart-leading-net' ),
			'icon'  => 'ai',
		),
	);
}

/**
 * SEO service cards.
 *
 * @return array<int, array<string, mixed>>
 */
function sln_get_seo_page_services() {
	return array(
		array(
			'title' => __( 'Technical SEO', 'smart-leading-net' ),
			'text'  => __( 'We clear the path for search engines and AI crawlers to find, render, and rank every page.', 'smart-leading-net' ),
			'items' => array(
				__( 'Core Web Vitals & speed', 'smart-leading-net' ),
				__( 'Crawl, index & sitemap fixes', 'smart-leading-net' ),
				__( 'Schema & structured data', 'smart-leading-net' ),
			),
		),
		array(
			'title' => __( 'Keyword & Intent Strategy', 'smart-leading-net' ),
			'text'  => __( 'We map the searches your buyers use at every stage and prioritise the ones that drive pipeline.', 'smart-leading-net' ),
			'items' => array(
				__( 'High-intent keyword research', 'smart-leading-net' ),
				__( 'Competitor gap analysis', 'smart-leading-net' ),
				__( 'Topic clusters & mapping', 'smart-leading-net' ),
			),
		),
		array(
			'title' => __( 'Content & On-Page SEO', 'smart-leading-net' ),
			'text'  => __( 'We create and optimise content that ranks, reads well, and earns the trust signals Google rewards.', 'smart-leading-net' ),
			'items' => array(
				__( 'Optimised landing pages', 'smart-leading-net' ),
				__( 'Blog & pillar content', 'smart-leading-net' ),
				__( 'Title, meta & internal links', 'smart-leading-net' ),
			),
		),
		array(
			'title' => __( 'Authority & Link Building', 'smart-leading-net' ),
			'text'  => __( 'We earn relevant, high-quality backlinks that build the domain authority rankings depend on.', 'smart-leading-net' ),
			'items' => array(
				__( 'Digital PR & outreach', 'smart-leading-net' ),
				__( 'Editorial backlinks', 'smart-leading-net' ),
				__( 'Toxic link cleanup', 'smart-leading-net' ),
			),
		),
		array(
			'title' => __( 'Local SEO', 'smart-leading-net' ),
			'text'  => __( 'We help you dominate the map pack and "near me" searches in every market you serve.', 'smart-leading-net' ),
			'items' => array(
				__( 'Google Business Profile', 'smart-leading-net' ),
				__( 'Local landing pages', 'smart-leading-net' ),
				__( 'Citations & reviews', 'smart-leading-net' ),
			),
		),
		array(
			'title' => __( 'AI & Answer Engine SEO', 'smart-leading-net' ),
			'text'  => __( 'We structure your content to win citations in AI Overviews, ChatGPT, and answer engines.', 'smart-leading-net' ),
			'items' => array(
				__( 'AI Overview optimisation', 'smart-leading-net' ),
				__( 'Entity & E-E-A-T signals', 'smart-leading-net' ),
				__( 'Answer-ready formatting', 'smart-leading-net' ),
			),
		),
	);
}

/**
 * Why choose Smart Leading cells.
 *
 * @return array<int, array<string, string>>
 */
function sln_get_seo_page_why_choose() {
	return array(
		array(
			'number' => '01',
			'title'  => __( 'Google Partner Certified', 'smart-leading-net' ),
			'text'   => __( 'Recognised expertise and direct access to best-practice standards — not guesswork or outdated tactics.', 'smart-leading-net' ),
		),
		array(
			'number' => '02',
			'title'  => __( 'Revenue-First Strategy', 'smart-leading-net' ),
			'text'   => __( 'We prioritise the keywords and pages that actually move pipeline, tracked through your CRM and analytics.', 'smart-leading-net' ),
		),
		array(
			'number' => '03',
			'title'  => __( 'Transparent Reporting', 'smart-leading-net' ),
			'text'   => __( 'Clear monthly reports show what we did, what it earned, and what\'s next. No vanity metrics, no jargon.', 'smart-leading-net' ),
		),
		array(
			'number' => '04',
			'title'  => __( 'A Dedicated Strategist', 'smart-leading-net' ),
			'text'   => __( 'One senior point of contact who knows your business — not a rotating cast or a ticket queue.', 'smart-leading-net' ),
		),
		array(
			'number' => '05',
			'title'  => __( 'Proven Across Industries', 'smart-leading-net' ),
			'text'   => __( 'From manufacturing to ecommerce, dental to home services — playbooks tailored to how each market searches.', 'smart-leading-net' ),
		),
		array(
			'number' => '06',
			'title'  => __( 'Try Before You Commit', 'smart-leading-net' ),
			'text'   => __( 'Start with a 7-day free trial of our strategy and process before signing on to a full SEO program.', 'smart-leading-net' ),
		),
	);
}

/**
 * SEO process steps.
 *
 * @return array<int, array<string, string>>
 */
function sln_get_seo_page_process_steps() {
	return array(
		array(
			'title' => __( 'Audit & Discovery', 'smart-leading-net' ),
			'text'  => __( 'We dig into your site, rankings, competitors, and goals to find the highest-impact opportunities.', 'smart-leading-net' ),
		),
		array(
			'title' => __( 'Strategy & Roadmap', 'smart-leading-net' ),
			'text'  => __( 'We build a prioritised 90-day plan tied to the keywords and pages that drive revenue.', 'smart-leading-net' ),
		),
		array(
			'title' => __( 'Optimise & Build', 'smart-leading-net' ),
			'text'  => __( 'We fix technical issues, optimise pages, publish content, and earn authority links.', 'smart-leading-net' ),
		),
		array(
			'title' => __( 'Measure & Report', 'smart-leading-net' ),
			'text'  => __( 'We track rankings, traffic, and conversions, reporting results against your goals monthly.', 'smart-leading-net' ),
		),
		array(
			'title' => __( 'Refine & Scale', 'smart-leading-net' ),
			'text'  => __( 'We double down on what\'s working and adapt to every algorithm and market shift.', 'smart-leading-net' ),
		),
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
	$section     = sln_get_seo_page_case_studies_section_settings();
	$cards       = sln_get_seo_page_case_studies_cards_raw();
	$chart_files = sln_get_growth_page_case_studies_chart_files();
	$active_cards = array();
	$chart_index  = 0;

	foreach ( $cards as $card ) {
		if ( empty( $card['active'] ) ) {
			continue;
		}

		$active_cards[] = array(
			'title'              => $card['title'],
			'metric_value'       => $card['metric_value'],
			'metric_description' => $card['metric_description'],
			'icon_id'            => absint( $card['icon_id'] ?? 0 ),
			'icon_fallback'      => $card['icon_fallback'] ?? '',
			'theme_color'        => $card['theme_color'] ?? '#1f4e9e',
			'tags'               => is_array( $card['tags'] ?? null ) ? $card['tags'] : array(),
			'chart_file'         => $chart_files[ $chart_index % count( $chart_files ) ],
		);

		++$chart_index;
	}

	return array(
		'label'          => $section['label'],
		'main_heading'   => $section['main_heading'],
		'highlight_word' => $section['highlight_word'],
		'description'    => $section['description'],
		'more_link_text' => $section['more_link_text'],
		'more_link_url'  => $section['more_link_url'],
		'cards'          => $active_cards,
	);
}

/**
 * Price Plan section header — edit here for the SEO page.
 *
 * @return array<string, string>
 */
function sln_get_seo_page_price_plan_section_settings() {
	return array(
		'label'          => __( 'PRICING PLANS', 'smart-leading-net' ),
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
	$section = sln_get_seo_page_price_plan_section_settings();
	$cards   = sln_get_seo_page_price_plan_cards_raw();
	$active_cards = array();

	foreach ( $cards as $card ) {
		if ( empty( $card['active'] ) ) {
			continue;
		}

		$active_cards[] = sln_sanitize_growth_page_price_plan_card( $card );
	}

	return array(
		'label'          => $section['label'],
		'heading_lead'   => $section['heading_lead'],
		'highlight_word' => $section['highlight_word'],
		'heading_trail'  => $section['heading_trail'],
		'description'    => $section['description'],
		'cards'          => $active_cards,
	);
}

/**
 * Testimonials section header — edit here for the SEO page.
 *
 * @return array<string, string>
 */
function sln_get_seo_page_testimonials_section_settings() {
	return array(
		'label'          => __( 'TESTIMONIALS', 'smart-leading-net' ),
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
	$section = sln_get_seo_page_testimonials_section_settings();
	$summary = sln_get_seo_page_testimonials_summary_settings();
	$stats   = sln_get_seo_page_testimonials_stats_raw();
	$reviews = sln_get_seo_page_testimonials_reviews_raw();

	$active_stats = array();

	foreach ( $stats as $stat ) {
		$sanitized = sln_sanitize_growth_page_testimonials_stat( $stat );

		if ( '' === trim( $sanitized['counter_value'] ) && '' === trim( $sanitized['label'] ) ) {
			continue;
		}

		$icon_fallback = $stat['icon_fallback'] ?? '';

		$active_stats[] = array(
			'icon_id'          => $sanitized['icon_id'],
			'icon_fallback'    => $icon_fallback,
			'counter_value'    => $sanitized['counter_value'],
			'counter_prefix'   => $sanitized['counter_prefix'],
			'counter_suffix'   => $sanitized['counter_suffix'],
			'counter_decimals' => $sanitized['counter_decimals'],
			'label'            => $sanitized['label'],
			'display_number'   => sln_growth_page_testimonials_format_stat_number( $sanitized ),
		);
	}

	$active_reviews = array();

	foreach ( $reviews as $review ) {
		$sanitized = sln_sanitize_growth_page_testimonials_review( $review );

		if ( empty( $sanitized['active'] ) ) {
			continue;
		}

		$active_reviews[] = $sanitized;
	}

	$uploads_url = trailingslashit( content_url( '/uploads/' . SLN_GP_TESTIMONIALS_UPLOADS ) );
	$bg_file     = WP_CONTENT_DIR . '/uploads/' . SLN_GP_TESTIMONIALS_UPLOADS . 'testimonials-bg.webp';

	if ( file_exists( $bg_file ) ) {
		$background_url = $uploads_url . rawurlencode( 'testimonials-bg.webp' );
	} else {
		$background_url = $uploads_url . 'case-studies-bg.webp';
	}

	return array(
		'label'          => $section['label'],
		'heading_lead'   => $section['heading_lead'],
		'highlight_word' => $section['highlight_word'],
		'background_url' => $background_url,
		'stats'          => $active_stats,
		'summary'        => sln_sanitize_growth_page_testimonials_summary( $summary ),
		'reviews'        => $active_reviews,
	);
}

/**
 * FAQ items for accordion and JSON-LD.
 *
 * @return array<int, array<string, string>>
 */
function sln_get_seo_page_faq_items() {
	return array(
		array(
			'question' => __( 'How long until I see SEO results?', 'smart-leading-net' ),
			'answer'   => __( 'Most clients see early movement in 60–90 days, with meaningful traffic and lead growth between months 4 and 6. SEO compounds — the gains build and become more durable the longer we work together. Your roadmap sets clear expectations for each phase.', 'smart-leading-net' ),
		),
		array(
			'question' => __( 'How do you measure success?', 'smart-leading-net' ),
			'answer'   => __( 'We tie SEO to business outcomes: organic traffic, keyword rankings, qualified leads, conversions, and revenue tracked through your analytics and CRM. You get a transparent monthly report showing exactly what we did and what it earned.', 'smart-leading-net' ),
		),
		array(
			'question' => __( 'Are there long-term contracts?', 'smart-leading-net' ),
			'answer'   => __( 'No. Our SEO plans are month-to-month, and you can start with a 7-day free trial of our strategy and process. We earn your business with results, not lock-in clauses.', 'smart-leading-net' ),
		),
		array(
			'question' => __( 'Do you optimise for AI search and Google AI Overviews?', 'smart-leading-net' ),
			'answer'   => __( 'Yes. We structure your content and entities to earn citations in AI Overviews and answer engines like ChatGPT and Perplexity, alongside traditional rankings — so you stay visible as search evolves.', 'smart-leading-net' ),
		),
		array(
			'question' => __( 'Who will manage my account?', 'smart-leading-net' ),
			'answer'   => __( 'You\'ll have a dedicated senior strategist as your main point of contact, supported by specialists in technical SEO, content, and link building. No rotating reps and no ticket queues.', 'smart-leading-net' ),
		),
		array(
			'question' => __( 'Will SEO work for my industry?', 'smart-leading-net' ),
			'answer'   => __( 'We\'ve driven results across manufacturing, ecommerce, hospitality, dental, home services, and more. Your free audit will show the specific organic opportunity in your market before you commit to anything.', 'smart-leading-net' ),
		),
	);
}

/**
 * FAQ JSON-LD schema.
 *
 * @return array<string, mixed>
 */
function sln_get_seo_page_faq_schema() {
	$entities = array();

	foreach ( sln_get_seo_page_faq_items() as $item ) {
		$entities[] = array(
			'@type'          => 'Question',
			'name'           => $item['question'],
			'acceptedAnswer' => array(
				'@type' => 'Answer',
				'text'  => $item['answer'],
			),
		);
	}

	return array(
		'@context'   => 'https://schema.org',
		'@type'      => 'FAQPage',
		'mainEntity' => $entities,
	);
}
