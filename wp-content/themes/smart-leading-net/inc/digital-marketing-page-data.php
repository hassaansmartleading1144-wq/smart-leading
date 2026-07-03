<?php
/**
 * Digital Marketing landing page — section data and helpers.
 *
 * @package Smart_Leading_Net
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Whether the current request uses the Digital Marketing page template.
 *
 * @return bool
 */
function sln_is_digital_marketing_page() {
	return is_page_template( 'digital-marketing-template.php' );
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
 * Hero stat counters (count-up animation data).
 *
 * @return array<int, array<string, string>>
 */
function sln_get_dm_page_hero_stats() {
	return array(
		array(
			'prefix' => '',
			'value'  => '3.2',
			'decimals' => '1',
			'suffix' => 'x',
			'label'  => __( 'Average Ad ROAS', 'smart-leading-net' ),
		),
		array(
			'prefix' => '+',
			'value'  => '300',
			'decimals' => '0',
			'suffix' => '%',
			'label'  => __( 'Lead Volume Growth', 'smart-leading-net' ),
		),
		array(
			'prefix' => '$',
			'value'  => '50',
			'decimals' => '0',
			'suffix' => 'M+',
			'label'  => __( 'Sales Generated', 'smart-leading-net' ),
		),
		array(
			'prefix' => '+',
			'value'  => '220',
			'decimals' => '0',
			'suffix' => '%',
			'label'  => __( 'Avg. Revenue Growth', 'smart-leading-net' ),
		),
		array(
			'prefix' => '',
			'value'  => '6',
			'decimals' => '0',
			'suffix' => '',
			'unit'   => __( 'Weeks', 'smart-leading-net' ),
			'label'  => __( 'To Measurable Results', 'smart-leading-net' ),
		),
		array(
			'prefix' => '',
			'value'  => '150',
			'decimals' => '0',
			'suffix' => '+',
			'label'  => __( 'Businesses Scaled', 'smart-leading-net' ),
		),
	);
}

/**
 * Pain point cards — "The Reality" section.
 *
 * @return array<int, array<string, string>>
 */
function sln_get_dm_page_pain_points() {
	return array(
		array(
			'variant' => 'orange',
			'title'   => __( 'Revenue Feels Stuck', 'smart-leading-net' ),
			'text'    => __( 'Your business works hard, but monthly revenue isn\'t growing the way it should.', 'smart-leading-net' ),
		),
		array(
			'variant' => 'ice',
			'title'   => __( 'Sales Are Inconsistent', 'smart-leading-net' ),
			'text'    => __( 'Some months bring good inquiries; others feel slow and unpredictable.', 'smart-leading-net' ),
		),
		array(
			'variant' => 'orange',
			'title'   => __( 'Poor Lead Quality', 'smart-leading-net' ),
			'text'    => __( 'You get inquiries, but many just ask prices and never become serious customers.', 'smart-leading-net' ),
		),
		array(
			'variant' => 'orange',
			'title'   => __( 'Leads Aren\'t Converting', 'smart-leading-net' ),
			'text'    => __( 'People show interest but don\'t take the next step — call, book, visit or buy.', 'smart-leading-net' ),
		),
		array(
			'variant' => 'ice',
			'title'   => __( 'Competitors Win First', 'smart-leading-net' ),
			'text'    => __( 'Even when your service is better, rivals with a stronger presence get noticed first.', 'smart-leading-net' ),
		),
		array(
			'variant' => 'orange',
			'title'   => __( 'Weak Brand Trust', 'smart-leading-net' ),
			'text'    => __( 'People find you online but hesitate, because your brand doesn\'t build confidence fast.', 'smart-leading-net' ),
		),
		array(
			'variant' => 'orange',
			'title'   => __( 'No Clear Offer', 'smart-leading-net' ),
			'text'    => __( 'Customers don\'t instantly understand why they should choose you over someone else.', 'smart-leading-net' ),
		),
		array(
			'variant' => 'ice',
			'title'   => __( 'Missed Follow-Ups', 'smart-leading-net' ),
			'text'    => __( 'Interested leads slip away — there\'s no follow-up system to bring them back.', 'smart-leading-net' ),
		),
		array(
			'variant' => 'orange',
			'title'   => __( 'Low Online Visibility', 'smart-leading-net' ),
			'text'    => __( 'Customers are searching, but you\'re not showing up strongly on Google or social.', 'smart-leading-net' ),
		),
	);
}

/**
 * Problem → solution approach cards.
 *
 * @return array<int, array<string, string>>
 */
function sln_get_dm_page_approach_items() {
	return array(
		array(
			'problem'  => __( 'Wasting ad budget with zero leads', 'smart-leading-net' ),
			'solution' => __( 'We <b>audit, rebuild &amp; manage</b> every campaign — every dollar tracked and reported.', 'smart-leading-net' ),
		),
		array(
			'problem'  => __( 'Invisible when customers search', 'smart-leading-net' ),
			'solution' => __( '<b>Google Ads</b> puts you at the top of search the moment buyers are looking.', 'smart-leading-net' ),
		),
		array(
			'problem'  => __( 'Traffic that never converts', 'smart-leading-net' ),
			'solution' => __( 'We build <b>landing pages &amp; funnels</b> designed to turn clicks into real enquiries.', 'smart-leading-net' ),
		),
		array(
			'problem'  => __( 'Social media with no engagement', 'smart-leading-net' ),
			'solution' => __( 'A consistent, <b>on-brand content engine</b> that builds your audience and your DMs.', 'smart-leading-net' ),
		),
		array(
			'problem'  => __( 'Flying blind with no reporting', 'smart-leading-net' ),
			'solution' => __( 'Clear <b>monthly dashboards</b>: leads, cost-per-lead and ROAS in plain language.', 'smart-leading-net' ),
		),
	);
}

/**
 * Service cards — "What We Do" section.
 *
 * @return array<int, array<string, string>>
 */
function sln_get_dm_page_services() {
	return array(
		array(
			'icon'    => '◎',
			'variant' => 'orange',
			'title'   => __( 'Paid Advertising', 'smart-leading-net' ),
			'text'    => __( 'Meta, Google, YouTube & TikTok — fully managed and optimised.', 'smart-leading-net' ),
		),
		array(
			'icon'    => '◍',
			'variant' => 'ice',
			'title'   => __( 'Social Media Management', 'smart-leading-net' ),
			'text'    => __( 'Strategy, content and community that build real trust.', 'smart-leading-net' ),
		),
		array(
			'icon'    => '⌕',
			'variant' => 'orange',
			'title'   => __( 'Search Marketing', 'smart-leading-net' ),
			'text'    => __( 'Show up first the moment buyers are looking for you.', 'smart-leading-net' ),
		),
		array(
			'icon'    => '⮞',
			'variant' => 'ice',
			'title'   => __( 'Landing Pages & Funnels', 'smart-leading-net' ),
			'text'    => __( 'Pages engineered to turn clicks into booked enquiries.', 'smart-leading-net' ),
		),
		array(
			'icon'    => '✦',
			'variant' => 'orange',
			'title'   => __( 'Creative & Content', 'smart-leading-net' ),
			'text'    => __( 'Scroll-stopping graphics, video and copy that convert.', 'smart-leading-net' ),
		),
		array(
			'icon'    => '▤',
			'variant' => 'ice',
			'title'   => __( 'Analytics & Reporting', 'smart-leading-net' ),
			'text'    => __( 'Clear numbers that show exactly what\'s driving growth.', 'smart-leading-net' ),
		),
	);
}

/**
 * Paid advertising channel chips.
 *
 * @return array<int, array<string, string>>
 */
function sln_get_dm_page_paid_channels() {
	return array(
		array( 'chip' => 'f', 'name' => __( 'Meta Ads', 'smart-leading-net' ), 'desc' => __( 'Facebook & Instagram', 'smart-leading-net' ) ),
		array( 'chip' => 'G', 'name' => __( 'Google Search', 'smart-leading-net' ), 'desc' => __( 'High-intent buyers', 'smart-leading-net' ) ),
		array( 'chip' => '▷', 'name' => __( 'YouTube & Display', 'smart-leading-net' ), 'desc' => __( 'Reach & awareness', 'smart-leading-net' ) ),
		array( 'chip' => '♪', 'name' => __( 'TikTok Ads', 'smart-leading-net' ), 'desc' => __( 'Viral, younger reach', 'smart-leading-net' ) ),
		array( 'chip' => 'in', 'name' => __( 'LinkedIn Ads', 'smart-leading-net' ), 'desc' => __( 'B2B decision-makers', 'smart-leading-net' ) ),
		array( 'chip' => '↺', 'name' => __( 'Retargeting', 'smart-leading-net' ), 'desc' => __( 'Win back lost visitors', 'smart-leading-net' ) ),
		array( 'chip' => '◫', 'name' => __( 'Shopping & PMax', 'smart-leading-net' ), 'desc' => __( 'Built for e-commerce', 'smart-leading-net' ) ),
		array( 'chip' => '✎', 'name' => __( 'Lead-Gen Ads', 'smart-leading-net' ), 'desc' => __( 'Forms that fill your pipeline', 'smart-leading-net' ) ),
	);
}

/**
 * Process timeline steps.
 *
 * @return array<int, array<string, mixed>>
 */
function sln_get_dm_page_process_steps() {
	return array(
		array(
			'number' => '01',
			'title'  => __( 'Discover', 'smart-leading-net' ),
			'items'  => array(
				__( 'Free strategy call', 'smart-leading-net' ),
				__( 'Understand your goals', 'smart-leading-net' ),
				__( 'Audit your channels', 'smart-leading-net' ),
				__( 'Spot the quick wins', 'smart-leading-net' ),
			),
		),
		array(
			'number' => '02',
			'title'  => __( 'Strategise', 'smart-leading-net' ),
			'items'  => array(
				__( 'Custom 90-day plan', 'smart-leading-net' ),
				__( 'Right-fit channels', 'smart-leading-net' ),
				__( 'Smart budget split', 'smart-leading-net' ),
				__( 'Content & ad plan', 'smart-leading-net' ),
			),
		),
		array(
			'number' => '03',
			'title'  => __( 'Launch', 'smart-leading-net' ),
			'items'  => array(
				__( 'Accounts optimised', 'smart-leading-net' ),
				__( 'Campaigns go live', 'smart-leading-net' ),
				__( 'Content published', 'smart-leading-net' ),
				__( 'Tracking installed', 'smart-leading-net' ),
			),
		),
		array(
			'number' => '04',
			'title'  => __( 'Optimise', 'smart-leading-net' ),
			'items'  => array(
				__( 'Weekly reviews', 'smart-leading-net' ),
				__( 'Constant A/B testing', 'smart-leading-net' ),
				__( 'Budget reallocation', 'smart-leading-net' ),
				__( 'Monthly ROI report', 'smart-leading-net' ),
			),
		),
	);
}

/**
 * Case study proof cards.
 *
 * @return array<int, array<string, mixed>>
 */
function sln_get_dm_page_case_studies() {
	return array(
		array(
			'name'    => __( 'Home Improvement Brand', 'smart-leading-net' ),
			'tag'     => __( 'Meta Ads', 'smart-leading-net' ),
			'metrics' => array(
				array( 'value' => '+340%', 'label' => __( 'Inbound Leads / 4 Mo', 'smart-leading-net' ) ),
				array( 'value' => '−48%', 'label' => __( 'Cost Per Lead', 'smart-leading-net' ) ),
				array( 'value' => '+190%', 'label' => __( 'Revenue Growth', 'smart-leading-net' ) ),
				array( 'value' => '$4.8M', 'label' => __( 'Sales Driven', 'smart-leading-net' ) ),
			),
			'quote'  => __( 'Within two months our phone was ringing every single day — and we knew exactly where each call came from.', 'smart-leading-net' ),
			'author' => __( 'Owner, Cabinetry & Flooring Co.', 'smart-leading-net' ),
		),
		array(
			'name'    => __( 'Aesthetics Clinic', 'smart-leading-net' ),
			'tag'     => __( 'Meta + Google', 'smart-leading-net' ),
			'metrics' => array(
				array( 'value' => '+280%', 'label' => __( 'Bookings / 60 Days', 'smart-leading-net' ) ),
				array( 'value' => '4.8x', 'label' => __( 'Return on Ad Spend', 'smart-leading-net' ) ),
				array( 'value' => '+240%', 'label' => __( 'Revenue Growth', 'smart-leading-net' ) ),
				array( 'value' => '$3.1M', 'label' => __( 'Sales Driven', 'smart-leading-net' ) ),
			),
			'quote'  => __( 'They rebuilt our entire digital presence — our calendar has been fully booked for three months straight.', 'smart-leading-net' ),
			'author' => __( 'Director, Aesthetics Practice', 'smart-leading-net' ),
		),
	);
}

/**
 * Pricing plans.
 *
 * @return array<int, array<string, mixed>>
 */
function sln_get_dm_page_pricing_plans() {
	$contact_url = sln_get_dm_page_contact_url();

	return array(
		array(
			'tier'       => __( 'STARTER', 'smart-leading-net' ),
			'tagline'    => __( 'Launch & Grow', 'smart-leading-net' ),
			'price'      => '$500',
			'price_note' => __( '/mo · starting at', 'smart-leading-net' ),
			'popular'    => false,
			'features'   => array(
				__( 'Social media management (2 platforms)', 'smart-leading-net' ),
				__( '20 posts/month + captions', 'smart-leading-net' ),
				__( 'Meta Ads setup & management', 'smart-leading-net' ),
				__( 'Monthly analytics report', 'smart-leading-net' ),
				__( 'Dedicated account manager', 'smart-leading-net' ),
			),
			'cta_url'    => $contact_url,
			'cta_variant' => 'ghost',
		),
		array(
			'tier'       => __( 'GROWTH', 'smart-leading-net' ),
			'tagline'    => __( 'Accelerate Sales', 'smart-leading-net' ),
			'price'      => '$1,200',
			'price_note' => __( '/mo · starting at', 'smart-leading-net' ),
			'popular'    => true,
			'popular_label' => __( '★ MOST POPULAR', 'smart-leading-net' ),
			'features'   => array(
				__( 'Everything in Starter, plus:', 'smart-leading-net' ),
				__( 'Google Ads management', 'smart-leading-net' ),
				__( 'Retargeting campaigns', 'smart-leading-net' ),
				__( 'Landing page optimisation', 'smart-leading-net' ),
				__( 'Bi-weekly strategy calls', 'smart-leading-net' ),
				__( 'Lead tracking & CRM setup', 'smart-leading-net' ),
			),
			'cta_url'    => $contact_url,
			'cta_variant' => 'primary',
		),
		array(
			'tier'       => __( 'SCALE', 'smart-leading-net' ),
			'tagline'    => __( 'Dominate Market', 'smart-leading-net' ),
			'price'      => '$2,500',
			'price_note' => __( '/mo · starting at', 'smart-leading-net' ),
			'popular'    => false,
			'features'   => array(
				__( 'Everything in Growth, plus:', 'smart-leading-net' ),
				__( 'Full content production', 'smart-leading-net' ),
				__( 'Advanced funnel build', 'smart-leading-net' ),
				__( 'Weekly reporting dashboard', 'smart-leading-net' ),
				__( 'Priority support', 'smart-leading-net' ),
				__( 'Quarterly strategy & planning', 'smart-leading-net' ),
			),
			'cta_url'    => $contact_url,
			'cta_variant' => 'ghost',
		),
	);
}

/**
 * FAQ items.
 *
 * @return array<int, array<string, string>>
 */
function sln_get_dm_page_faq_items() {
	return array(
		array(
			'question' => __( 'How soon will I see results?', 'smart-leading-net' ),
			'answer'   => __( 'Most clients see early traction within the first 4–6 weeks, with growth compounding as campaigns are optimised each month.', 'smart-leading-net' ),
		),
		array(
			'question' => __( 'Do I need a long-term contract?', 'smart-leading-net' ),
			'answer'   => __( 'No. We work month-to-month — we earn your business with results, not lock-in clauses or hidden penalties.', 'smart-leading-net' ),
		),
		array(
			'question' => __( 'How much should I budget for ads?', 'smart-leading-net' ),
			'answer'   => __( 'It depends on your goals and market. We\'ll recommend a realistic spend on your free call — and every dollar is tracked.', 'smart-leading-net' ),
		),
		array(
			'question' => __( 'Which platforms do you advertise on?', 'smart-leading-net' ),
			'answer'   => __( 'Meta, Google, YouTube, TikTok and LinkedIn — we pick the channels where your buyers are, not all of them at once.', 'smart-leading-net' ),
		),
		array(
			'question' => __( 'How will I know it\'s working?', 'smart-leading-net' ),
			'answer'   => __( 'You get clear monthly dashboards showing leads, cost-per-lead and ROAS — in plain language, no vanity metrics.', 'smart-leading-net' ),
		),
		array(
			'question' => __( 'What if I\'ve been burned before?', 'smart-leading-net' ),
			'answer'   => __( 'Most clients come to us after exactly that. Transparent reporting and a dedicated manager mean you always know what\'s happening.', 'smart-leading-net' ),
		),
	);
}

/**
 * Final CTA checklist items.
 *
 * @return array<int, string>
 */
function sln_get_dm_page_final_checks() {
	return array(
		__( 'Revenue-focused strategy', 'smart-leading-net' ),
		__( 'Transparent reporting', 'smart-leading-net' ),
		__( 'Results from Month 1', 'smart-leading-net' ),
	);
}
