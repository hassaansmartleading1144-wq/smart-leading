<?php
/**
 * PPC sub-pages — UI layer: line-icon set, interactive guide data and
 * sub-page-only assets (animations). The main PPC page is not affected.
 *
 * Icons are chosen through the existing `icon_text` field (e.g. "search-x"),
 * which the PPC admin screen already saves, so editors can change them.
 *
 * @package Smart_Leading_Net
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Inner SVG markup for each icon (24×24, stroke-based).
 *
 * @return array<string, string>
 */
function sln_ppc_icon_paths() {
	return array(
		'search-x'        => '<circle cx="11" cy="11" r="7"/><path d="m21 21-4.3-4.3"/><path d="m8.5 8.5 5 5"/><path d="m13.5 8.5-5 5"/>',
		'phone-off'       => '<path d="M10.7 13.3a16 16 0 0 0 3.4 2.6l1.3-1.3a2 2 0 0 1 2.1-.4c.8.3 1.7.5 2.6.6A2 2 0 0 1 22 16.9v3a2 2 0 0 1-2.2 2A19.8 19.8 0 0 1 4.1 4.2 2 2 0 0 1 6.1 2h3a2 2 0 0 1 2 1.7c.1.9.3 1.8.6 2.6a2 2 0 0 1-.4 2.1L10 9.7"/><path d="M22 2 2 22"/>',
		'map-pin-off'     => '<path d="M9.4 4.2A7 7 0 0 1 19 10c0 1.6-.6 3.3-1.4 4.8"/><path d="M15.4 18.6C13.6 20.6 12 22 12 22s-7-6.2-7-12c0-1 .2-2 .6-2.9"/><path d="M10.4 8.4a2.5 2.5 0 0 0 3.2 3.2"/><path d="M2 2l20 20"/>',
		'eye-off'         => '<path d="M10.7 5.1A10.9 10.9 0 0 1 12 5c7 0 10 7 10 7a13 13 0 0 1-1.7 2.7"/><path d="M6.6 6.6A13.5 13.5 0 0 0 2 12s3 7 10 7a9.7 9.7 0 0 0 5.4-1.6"/><path d="M9.9 9.9a3 3 0 0 0 4.2 4.2"/><path d="M2 2l20 20"/>',
		'trending-up'     => '<path d="m22 7-8.5 8.5-5-5L2 17"/><path d="M16 7h6v6"/>',
		'file-question'   => '<path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><path d="M14 2v6h6"/><path d="M10 12.5a2 2 0 1 1 2.8 1.8c-.5.3-.8.7-.8 1.2"/><path d="M12 18h.01"/>',
		'chef-hat'        => '<path d="M6 13.9A4 4 0 0 1 7.4 6.2a5 5 0 0 1 9.2 0A4 4 0 0 1 18 13.9V21H6z"/><path d="M6 17h12"/>',
		'hammer'          => '<path d="m15 12-8.4 8.4a2.1 2.1 0 0 1-3-3L12 9"/><path d="M17.6 15 22 10.6"/><path d="m20.9 11.7-1.3-1.2a2 2 0 0 1-.6-1.4v-.9l-2.3-2.3a5.5 5.5 0 0 0-3.9-1.6H10l.9.8a6.2 6.2 0 0 1 2 4.5V11l2 2h.9a2 2 0 0 1 1.4.6l1.2 1.2"/>',
		'sparkles'        => '<path d="M12 3l1.9 5.1L19 10l-5.1 1.9L12 17l-1.9-5.1L5 10l5.1-1.9z"/><path d="M19 3v4"/><path d="M17 5h4"/><path d="M5 17v4"/><path d="M3 19h4"/>',
		'tooth'           => '<path d="M7 3c-2.5 0-4 2-4 4.5 0 2.3 1 3.6 1.6 5.4.6 1.8.9 4.1 1.5 6.1.4 1.3 1 2 1.8 2 1.6 0 1.6-4 2.6-5.5.4-.6.9-.8 1.5-.8s1.1.2 1.5.8c1 1.5 1 5.5 2.6 5.5.8 0 1.4-.7 1.8-2 .6-2 .9-4.3 1.5-6.1.6-1.8 1.6-3.1 1.6-5.4C21 5 19.5 3 17 3c-2 0-3 1-5 1S9 3 7 3z"/>',
		'zap'             => '<path d="M13 2 3 14h9l-1 8 10-12h-9z"/>',
		'utensils'        => '<path d="M3 2v7c0 1.1.9 2 2 2h4a2 2 0 0 0 2-2V2"/><path d="M7 2v20"/><path d="M21 15V2a5 5 0 0 0-5 5v6c0 1.1.9 2 2 2h3z"/><path d="M21 15v7"/>',
		'message'         => '<path d="M21 11.5a8.4 8.4 0 0 1-.9 3.8 8.5 8.5 0 0 1-7.6 4.7 8.4 8.4 0 0 1-3.8-.9L3 21l1.9-5.7a8.4 8.4 0 0 1-.9-3.8 8.5 8.5 0 0 1 4.7-7.6 8.4 8.4 0 0 1 3.8-.9h.5a8.5 8.5 0 0 1 8 8z"/>',
		'clipboard-check' => '<rect x="8" y="2" width="8" height="4" rx="1"/><path d="M16 4h2a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2H6a2 2 0 0 1-2-2V6a2 2 0 0 1 2-2h2"/><path d="m9 14 2 2 4-4"/>',
		'rocket'          => '<path d="M4.5 16.5c-1.5 1.3-2 5-2 5s3.7-.5 5-2c.7-.8.7-2.1-.1-2.9a2.2 2.2 0 0 0-2.9-.1z"/><path d="m12 15-3-3a22 22 0 0 1 2-3.9A12.9 12.9 0 0 1 22 2c0 2.7-.8 7.5-6 11a22.4 22.4 0 0 1-4 2z"/><path d="M9 12H4s.6-3 2-4c1.6-1.1 5 0 5 0"/><path d="M12 15v5s3-.6 4-2c1.1-1.6 0-5 0-5"/>',
		'line-chart'      => '<path d="M3 3v18h18"/><path d="m7 15 4-4 3 3 5-6"/>',
		'filter-x'        => '<path d="M13.5 13 21 4H3l7 8.5V19l3 2v-8"/><path d="m16 15 5 5"/><path d="m21 15-5 5"/>',
		'target'          => '<circle cx="12" cy="12" r="9"/><circle cx="12" cy="12" r="5"/><circle cx="12" cy="12" r="1"/>',
		'megaphone'       => '<path d="m3 11 18-5v12L3 14z"/><path d="M11.6 16.8a3 3 0 1 1-5.8-1.6"/>',
		'map-pin'         => '<path d="M20 10c0 6-8 12-8 12S4 16 4 10a8 8 0 0 1 16 0z"/><circle cx="12" cy="10" r="3"/>',
		'gauge'           => '<path d="m12 14 4-4"/><path d="M3.3 19a10 10 0 1 1 17.4 0"/>',
		'layout'          => '<rect x="3" y="3" width="18" height="18" rx="2"/><path d="M3 9h18"/><path d="M9 21V9"/>',
		'shield-check'    => '<path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/><path d="m9 12 2 2 4-4"/>',
		'check'           => '<path d="M20 6 9 17l-5-5"/>',
		'key'             => '<circle cx="7.5" cy="15.5" r="4.5"/><path d="m10.7 12.3 9.3-9.3"/><path d="m16 7 3 3"/><path d="m19 4 2 2"/>',
		'refresh'         => '<path d="M21 12a9 9 0 1 1-3-6.7L21 8"/><path d="M21 3v5h-5"/>',
		'eye'             => '<path d="M2 12s3-7 10-7 10 7 10 7-3 7-10 7-10-7-10-7z"/><circle cx="12" cy="12" r="3"/>',
		'cart'            => '<circle cx="9" cy="20" r="1.5"/><circle cx="18" cy="20" r="1.5"/><path d="M2 3h3l2.7 12.4a2 2 0 0 0 2 1.6h7.8a2 2 0 0 0 2-1.5L21 8H6"/>',
		'play'            => '<rect x="2" y="4" width="20" height="16" rx="4"/><path d="m10 9 5 3-5 3z"/>',
		'users'           => '<circle cx="9" cy="8" r="4"/><path d="M2 21a7 7 0 0 1 14 0"/><path d="M16 4a4 4 0 0 1 0 8"/><path d="M22 21a7 7 0 0 0-4-6.3"/>',
		'search'          => '<circle cx="11" cy="11" r="7"/><path d="m21 21-4.3-4.3"/>',
		'layers'          => '<path d="m12 2 10 5-10 5L2 7z"/><path d="m2 12 10 5 10-5"/><path d="m2 17 10 5 10-5"/>',
		'lock'            => '<rect x="4" y="11" width="16" height="10" rx="2"/><path d="M8 11V7a4 4 0 0 1 8 0v4"/>',
	);
}

/**
 * SVG markup for an icon, or '' when the key is unknown.
 *
 * Every shape gets pathLength="1" so CSS can "draw" it in.
 *
 * @param string $key   Icon key.
 * @param string $class Extra class.
 * @return string
 */
function sln_ppc_icon( $key, $class = '' ) {
	$paths = sln_ppc_icon_paths();
	$key   = sanitize_key( (string) $key );

	if ( ! isset( $paths[ $key ] ) ) {
		return '';
	}

	$inner = preg_replace( '/<(path|circle|rect)\s/', '<$1 pathLength="1" ', $paths[ $key ] );

	return sprintf(
		'<svg class="sln-ppc-icon %1$s" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true" focusable="false">%2$s</svg>',
		esc_attr( $class ),
		$inner
	);
}

/**
 * Echo an icon when `$text` is an icon key, otherwise the text itself.
 *
 * @param string $text     icon_text value.
 * @param string $fallback Fallback glyph.
 */
function sln_ppc_icon_or_text( $text, $fallback = '•' ) {
	$text = (string) $text;
	$svg  = '' !== $text ? sln_ppc_icon( $text ) : '';

	if ( $svg ) {
		echo $svg; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
		return;
	}

	echo esc_html( '' !== $text ? $text : $fallback );
}

/**
 * Interactive guide settings per sub-page slug: short tab labels, tab icons
 * and the animated illustration shown for each approach step.
 *
 * @param int|null $post_id Optional post ID.
 * @return array<int, array{label: string, icon: string, visual: string}>
 */
function sln_ppc_guide_steps( $post_id = null ) {
	$post = get_post( sln_ppc_resolve_post_id( $post_id ) );
	$slug = $post instanceof WP_Post ? $post->post_name : '';

	$map = array(
		'ppc'                   => array(
			array(
				'label'  => 'Audit & Goals',
				'icon'   => 'clipboard-check',
				'visual' => 'search-terms',
				'points' => array( 'Review of your current ad accounts and tracking', 'Competitor and keyword research for your market', 'Goals set in leads and cost per lead, not clicks' ),
				'result' => 'A clear plan built around the leads you need.',
			),
			array(
				'label'  => 'Tracking First',
				'icon'   => 'line-chart',
				'visual' => 'tracking',
				'points' => array( 'Call and form tracking before any spend scales', 'Google, Local Services and Meta leads in one place', 'Duplicate leads counted once in Lead Analytics' ),
				'result' => 'You see which channel brings each lead.',
			),
			array(
				'label'  => 'The Right Channels',
				'icon'   => 'layers',
				'visual' => 'channels',
				'points' => array( 'Search and Local Services Ads for ready-to-buy demand', 'Shopping, Performance Max and YouTube where they fit', 'Meta Ads and retargeting to win people back' ),
				'result' => 'Budget goes where your customers actually are.',
			),
			array(
				'label'  => 'Ads & Landing Pages',
				'icon'   => 'layout',
				'visual' => 'landing-page',
				'points' => array( 'Ad copy and creative written for each audience', 'Landing pages that match the ad and the offer', 'Conversion rate testing on calls and forms' ),
				'result' => 'More of your clicks turn into enquiries.',
			),
			array(
				'label'  => 'Optimize Bids & Budget',
				'icon'   => 'gauge',
				'visual' => 'bidding',
				'points' => array( 'Search terms, audiences and placements reviewed continually', 'Smart bidding once conversions are tracked', 'Budget moved to the campaigns that bring leads' ),
				'result' => 'Your cost per lead keeps getting tested and tightened.',
			),
			array(
				'label'  => 'Report Real Leads',
				'icon'   => 'eye',
				'visual' => 'report',
				'points' => array( 'Leads reported by channel, not just clicks', 'Cost per lead next to every campaign', 'Honest notes on what is and isn\'t working' ),
				'result' => 'You always know what your ad spend brought in.',
			),
		),
		'ecommerce-ppc'         => array(
			array( 'label' => 'Feed & Merchant Center', 'icon' => 'clipboard-check', 'visual' => 'product-feed', 'points' => array( 'Titles and images that match searches', 'GTINs and attributes filled in', 'Disapprovals fixed' ), 'result' => 'More of your products eligible to show.' ),
			array( 'label' => 'Product Structure', 'icon' => 'layers', 'visual' => 'products', 'points' => array( 'Products grouped by margin', 'Best sellers given room to spend', 'Weak items limited or excluded' ), 'result' => 'Budget goes to the products that make money.' ),
			array( 'label' => 'Performance Max', 'icon' => 'sparkles', 'visual' => 'asset-group', 'points' => array( 'Performance Max campaigns with asset groups by category', 'Audience signals from your customers', 'Brand exclusions where they make sense' ), 'result' => 'Automation pointed at the right shoppers.' ),
			array( 'label' => 'ROAS Bidding', 'icon' => 'gauge', 'visual' => 'bidding', 'points' => array( 'Target ROAS once revenue is tracked', 'Budgets moved to profitable groups', 'Search terms reviewed in Shopping' ), 'result' => 'Spend follows revenue, not clicks.' ),
			array( 'label' => 'Revenue Tracking', 'icon' => 'line-chart', 'visual' => 'revenue', 'points' => array( 'GA4 ecommerce events', 'Purchase values sent to Google Ads', 'Sales credited to the right campaign' ), 'result' => 'Bidding works from real order values.' ),
			array( 'label' => 'Dynamic Remarketing', 'icon' => 'refresh', 'visual' => 'retarget', 'points' => array( 'Shoppers see the products they viewed', 'Cart abandoners brought back', 'Frequency kept in check' ), 'result' => 'More of your near-buyers come back and order.' ),
		),
		'free-ppc-audit'        => array(
			array( 'label' => 'Wasted Spend', 'icon' => 'filter-x', 'visual' => 'search-terms', 'points' => array( 'Search terms, audiences and placements', 'Irrelevant clicks and junk leads', 'Spend outside your area or hours' ), 'result' => 'You see exactly where budget leaks.' ),
			array( 'label' => 'Tracking', 'icon' => 'line-chart', 'visual' => 'tracking', 'points' => array( 'Conversion and call tracking', 'Meta Pixel and Conversions API', 'Reported leads vs real leads' ), 'result' => 'You know whether you can trust the numbers.' ),
			array( 'label' => 'Structure & Keywords', 'icon' => 'target', 'visual' => 'match-types', 'points' => array( 'Campaign and ad group structure', 'Keywords, match types and negatives', 'Local Services job types and areas' ), 'result' => 'An account built for the jobs you want.' ),
			array( 'label' => 'Ads & Quality Score', 'icon' => 'megaphone', 'visual' => 'ad-preview', 'points' => array( 'Ad copy, assets and creative', 'Quality Score and ad relevance', 'Creative fatigue on Meta' ), 'result' => 'Ads that earn the click at a lower cost.' ),
			array( 'label' => 'Landing Pages & Forms', 'icon' => 'layout', 'visual' => 'landing-page', 'points' => array( 'Message match from ad to page', 'Calls to action and form friction', 'Mobile experience and speed' ), 'result' => 'More of your clicks turn into enquiries.' ),
			array( 'label' => 'Budget & Bidding', 'icon' => 'gauge', 'visual' => 'bidding', 'points' => array( 'Bidding strategies', 'Budget split across campaigns', 'Channel mix: Google, LSA, Meta' ), 'result' => 'A clear plan for where each dollar should go.' ),
		),
		'facebook-ads-management' => array(
			array(
				'label'  => 'Offer & Audience',
				'icon'   => 'target',
				'visual' => 'feed',
				'points' => array( 'An offer worth stopping for', 'Location, interest and lookalike audiences', 'Facebook and Instagram placements chosen together' ),
				'result' => 'Your ads reach people who could actually become customers.',
			),
			array(
				'label'  => 'Creative',
				'icon'   => 'sparkles',
				'visual' => 'creatives',
				'points' => array( 'Designed in-house by our creative team', 'Image, video, carousel, Reels and Stories formats', 'Fresh variations before ads wear out' ),
				'result' => 'Ads people stop for, and keep stopping for.',
			),
			array(
				'label'  => 'Lead Forms',
				'icon'   => 'clipboard-check',
				'visual' => 'lead-form',
				'points' => array( 'Instant forms with qualifying questions', 'Or a landing page built to convert', 'Leads sent straight to Lead Analytics' ),
				'result' => 'Fewer junk leads, more people worth calling.',
			),
			array(
				'label'  => 'Pixel & Tracking',
				'icon'   => 'line-chart',
				'visual' => 'pixel',
				'points' => array( 'Meta Pixel and Conversions API set up', 'Leads, calls and bookings recorded', 'Meta optimizes for leads, not clicks' ),
				'result' => 'Meta learns from the right signals.',
			),
			array(
				'label'  => 'Testing & Budget',
				'icon'   => 'gauge',
				'visual' => 'ab-test',
				'points' => array( 'Audiences, creative and offers tested', 'Budget moved to the winning ads', 'Frequency watched to avoid ad fatigue' ),
				'result' => 'Your cost per lead keeps getting tested and tightened.',
			),
			array(
				'label'  => 'Retargeting',
				'icon'   => 'refresh',
				'visual' => 'retarget',
				'points' => array( 'Site visitors and video viewers brought back', 'Message matched to what they saw', 'Frequency caps so it never feels pushy' ),
				'result' => 'More of the people who almost enquired come back.',
			),
		),
		'local-service-ads'     => array(
			array(
				'label'  => 'Eligibility & Verification',
				'icon'   => 'shield-check',
				'visual' => 'verify',
				'points' => array( 'Category and service area eligibility check', 'Document checklist for Google\'s screening', 'Follow-up until Google makes its decision' ),
				'result' => 'A profile ready for Google\'s review, without the back-and-forth.',
			),
			array(
				'label'  => 'Profile Setup',
				'icon'   => 'layout',
				'visual' => 'lsa-profile',
				'points' => array( 'Job types matched to the work you want', 'Service areas you can actually reach', 'Hours, photos and a bio that build trust' ),
				'result' => 'You show up for the right jobs in the right places.',
			),
			array(
				'label'  => 'Reviews & Ranking',
				'icon'   => 'sparkles',
				'visual' => 'reviews',
				'points' => array( 'A simple routine to request reviews', 'Fast responses to calls and messages', 'Ranking factors checked regularly' ),
				'result' => 'A stronger profile that climbs the list.',
			),
			array(
				'label'  => 'Budget & Bidding',
				'icon'   => 'gauge',
				'visual' => 'lsa-budget',
				'points' => array( 'Weekly budget set to your capacity', 'Bidding mode chosen by your goals', 'Adjusted when you\'re fully booked' ),
				'result' => 'Steady leads without paying for more than you can take.',
			),
			array(
				'label'  => 'Lead Review & Disputes',
				'icon'   => 'filter-x',
				'visual' => 'disputes',
				'points' => array( 'Every lead reviewed', 'Wrong-service, out-of-area and spam leads disputed', 'Google decides on each dispute' ),
				'result' => 'Less spend on leads you could never use.',
			),
			array(
				'label'  => 'Lead Tracking',
				'icon'   => 'line-chart',
				'visual' => 'tracking',
				'points' => array( 'Calls, messages and bookings in one place', 'Cost per lead next to your other channels', 'Notes on which leads became jobs' ),
				'result' => 'You know exactly what Local Service Ads bring in.',
			),
		),
		'google-ads-management' => array(
			array(
				'label'  => 'Cut Wasted Searches',
				'icon'   => 'filter-x',
				'visual' => 'search-terms',
				'points' => array( 'Regular search terms report reviews', 'Negative keyword lists by service and location', 'Job-seeker, DIY and free-info searches blocked' ),
				'result' => 'More of your budget reaches people ready to call.',
			),
			array(
				'label'  => 'Keywords & Match Types',
				'icon'   => 'target',
				'visual' => 'match-types',
				'points' => array( 'Ad groups split by service', 'Exact and phrase match for high-intent searches', 'Broad match only with tracking and negatives in place' ),
				'result' => 'Your ads show for the jobs you actually want.',
			),
			array(
				'label'  => 'Ads & Quality Score',
				'icon'   => 'megaphone',
				'visual' => 'ad-preview',
				'points' => array( 'Responsive search ads written for each ad group', 'Call, location, sitelink and offer assets', 'Ongoing ad testing to improve click-through rate' ),
				'result' => 'Better ad relevance and a lower cost per click.',
			),
			array(
				'label'  => 'Location & Schedule',
				'icon'   => 'map-pin',
				'visual' => 'map-radius',
				'points' => array( 'Radius or postcode targeting around your service area', 'Bid adjustments by location and device', 'Ads paused when nobody can answer the phone' ),
				'result' => 'Your budget only runs where and when you can win the job.',
			),
			array(
				'label'  => 'Smart Bidding',
				'icon'   => 'gauge',
				'visual' => 'bidding',
				'points' => array( 'Conversion tracking set up before automated bidding', 'Maximize Conversions or Target CPA, chosen by your data', 'Budget moved to the campaigns that bring leads' ),
				'result' => 'Bids follow real leads, not cheap clicks.',
			),
			array(
				'label'  => 'Landing Page',
				'icon'   => 'layout',
				'visual' => 'landing-page',
				'points' => array( 'Page message matches the ad and the search', 'Clear call button and a short quote form', 'Mobile speed checks and conversion rate testing' ),
				'result' => 'More visitors turn into calls and enquiries.',
			),
		),
	);

	return $map[ $slug ] ?? array();
}

/**
 * "Why SLS" flip-card titles and icons on sub-pages (by row position).
 *
 * @return array<int, array{title: string, icon: string}>
 */
function sln_ppc_why_cards() {
	return array(
		array( 'title' => 'Your Account, Your Name', 'icon' => 'lock', 'inhouse' => 'One person holds the logins, and access leaves when they do' ),
		array( 'title' => 'No Passwords. Ever.', 'icon' => 'key', 'inhouse' => 'Shared passwords passed around the team' ),
		array( 'title' => 'Leads, Not Clicks', 'icon' => 'line-chart', 'inhouse' => 'No time to connect calls and forms to campaigns' ),
		array( 'title' => 'Never Set and Forget', 'icon' => 'refresh', 'inhouse' => 'Ads checked when someone finds a spare hour' ),
		array( 'title' => 'Honest Numbers', 'icon' => 'eye', 'inhouse' => 'Hard to judge your own campaigns objectively' ),
	);
}

/**
 * Hero USP tile icons on sub-pages (by position).
 *
 * @return array<int, string>
 */
function sln_ppc_hero_usp_icons() {
	$post = get_post( sln_ppc_resolve_post_id() );
	$slug = $post instanceof WP_Post ? $post->post_name : '';

	$map = array(
		'facebook-ads-management' => array( 'sparkles', 'zap', 'line-chart', 'lock' ),
		'free-ppc-audit'          => array( 'file-question', 'zap', 'layers', 'lock' ),
		'ecommerce-ppc'           => array( 'shield-check', 'trending-up', 'cart', 'lock' ),
	);

	return $map[ $slug ] ?? array( 'shield-check', 'trending-up', 'line-chart', 'lock' );
}

/**
 * Process step icons on sub-pages (by position).
 *
 * @return array<int, string>
 */
function sln_ppc_process_icons() {
	return array( 'message', 'clipboard-check', 'rocket', 'line-chart' );
}

/**
 * Add a modifier class to the PPC <main> on sub-pages.
 *
 * @return string
 */
function sln_ppc_main_class() {
	return function_exists( 'sln_ppc_new_design' ) && sln_ppc_new_design() ? ' sln-ppc-subpage' : '';
}

/**
 * Sub-page-only stylesheet and script.
 */
function sln_ppc_subpage_assets() {
	if ( ! is_page_template( SLN_PPC_TEMPLATE ) || ! sln_ppc_new_design() ) {
		return;
	}

	wp_enqueue_style(
		'sln-ppc-subpage',
		SLN_THEME_URI . '/assets/css/ppc-subpage.css',
		array( 'sln-ppc-google-ads-page' ),
		filemtime( SLN_THEME_DIR . '/assets/css/ppc-subpage.css' )
	);

	wp_enqueue_script(
		'sln-ppc-subpage',
		SLN_THEME_URI . '/assets/js/ppc-subpage.js',
		array( 'sln-ppc-google-ads-page' ),
		filemtime( SLN_THEME_DIR . '/assets/js/ppc-subpage.js' ),
		true
	);
}
add_action( 'wp_enqueue_scripts', 'sln_ppc_subpage_assets', 20 );

/**
 * Homepage sections reused on PPC sub-pages, with page-specific copy.
 *
 * Numbers in Accomplishments and Case Studies are owner-confirmed
 * (17 Nov 2026). Only paid-search case study cards are shown on PPC pages.
 *
 * @param string $key accomplishments | expertise | case-studies.
 */
function sln_ppc_render_home_section( $key ) {
	if ( ! sln_ppc_new_design() ) {
		return;
	}

	$post = get_post( sln_ppc_resolve_post_id() );
	$slug = $post instanceof WP_Post ? $post->post_name : '';

	$copy = array(
		'ecommerce-ppc'           => array(
			'accomplishments' => array(
				'description' => 'The same data-driven approach behind our ecommerce PPC work has helped businesses generate leads, increase revenue and scale with confidence.',
			),
			'expertise'       => array(
				'heading_format' => 'Our Ecommerce PPC %s',
				'heading_accent' => 'Expertise',
				'items'          => array(
					'We work as your ecommerce PPC consultant and team: product feeds, Merchant Center, Shopping campaigns and Performance Max in one plan.',
					'As a Google Partner and Google Performance Max agency, we give automation clean data: a strong feed, tracked revenue and clear product priorities.',
					'Revenue and ROAS are reported by product group, so you see which products your ads actually sell.',
				),
				'cta_text'       => 'Get My Free Shopping Audit',
				'cta_url'        => '#contact',
			),
			'case-studies'    => array(
				'heading_format' => 'Ecommerce PPC Results For %s',
				'heading_accent' => 'Growth-Focused Brands',
				'description'    => 'Results from our paid ads work, including higher return on ad spend for an ecommerce brand.',
				'more_url'       => home_url( '/case-studies/' ),
				'cards'          => array(
					array( 'theme' => 'orange', 'title' => 'Case Study: ECommerce', 'number' => '4.2X', 'text' => 'Higher Return On Ad Spend', 'icon' => 'ecommerce.svg', 'chart' => 'ad-spend-chart.svg', 'footer_strip' => 'Data-Backed Strategies. Real Business Impact.' ),
					array( 'theme' => 'teal', 'title' => 'Case Study: Manufacturing', 'number' => '30%', 'text' => 'ROI Increase From Paid Search', 'icon' => 'surface1.svg', 'chart' => 'paid-chart.svg' ),
				),
			),
		),
		'free-ppc-audit'          => array(
			'accomplishments' => array(
				'description' => 'The same data-driven approach behind every audit has helped businesses generate leads, increase revenue and scale with confidence.',
			),
			'case-studies'    => array(
				'heading_format' => 'What Fixing an Account %s',
				'heading_accent' => 'Can Look Like',
				'description'    => 'Results from our paid ads work after finding and fixing what was holding accounts back.',
				'more_url'       => home_url( '/case-studies/' ),
				'cards'          => array(
					array( 'theme' => 'teal', 'title' => 'Case Study: Manufacturing', 'number' => '30%', 'text' => 'ROI Increase From Paid Search', 'icon' => 'surface1.svg', 'chart' => 'paid-chart.svg' ),
					array( 'theme' => 'orange', 'title' => 'Case Study: ECommerce', 'number' => '4.2X', 'text' => 'Higher Return On Ad Spend', 'icon' => 'ecommerce.svg', 'chart' => 'ad-spend-chart.svg', 'footer_strip' => 'Data-Backed Strategies. Real Business Impact.' ),
				),
			),
		),
		'facebook-ads-management' => array(
			'accomplishments' => array(
				'description' => 'As a Facebook ads marketing agency, we bring the same data-driven approach that helps businesses generate leads, increase revenue and scale with confidence.',
			),
			'expertise'       => array(
				'heading_format' => 'Our Facebook Ads %s',
				'heading_accent' => 'Expertise',
				'items'          => array(
					'As a Meta ads agency, we plan and run Facebook and Instagram campaigns for local and service businesses, from offers and audiences to lead forms.',
					'As a Google Partner, we run Meta alongside Google Ads when it helps, so you build demand on social and capture it on search.',
					'Meta lead forms flow into Lead Analytics, so you can compare Facebook cost per lead with every other channel.',
				),
				'cta_text'       => 'Get My Free Meta Ads Audit',
				'cta_url'        => '#contact',
			),
			'case-studies'    => array(
				'heading_format' => 'Paid Ads Results For %s',
				'heading_accent' => 'Growth-Focused Businesses',
				'description'    => 'Results from our paid ads work: tight targeting, conversion tracking and data-backed optimization.',
				'more_url'       => home_url( '/case-studies/' ),
				'cards'          => array(
					array( 'theme' => 'teal', 'title' => 'Case Study: Manufacturing', 'number' => '30%', 'text' => 'ROI Increase From Paid Search', 'icon' => 'surface1.svg', 'chart' => 'paid-chart.svg' ),
					array( 'theme' => 'orange', 'title' => 'Case Study: ECommerce', 'number' => '4.2X', 'text' => 'Higher Return On Ad Spend', 'icon' => 'ecommerce.svg', 'chart' => 'ad-spend-chart.svg', 'footer_strip' => 'Data-Backed Strategies. Real Business Impact.' ),
				),
			),
		),
		'local-service-ads'     => array(
			'accomplishments' => array(
				'description' => 'Our Local Service Ads management is built on the same data-driven approach that helps businesses generate leads, increase revenue and scale with confidence.',
			),
			'expertise'       => array(
				'heading_format' => 'Our Local Service Ads %s',
				'heading_accent' => 'Expertise',
				'items'          => array(
					'We set up and manage Google Local Services Ads for local service businesses, from eligibility and verification to reviews, budgets and lead disputes.',
					'As a Google Partner, we run Local Service Ads alongside Google Search campaigns when it helps, so you show at the top and cover services LSA doesn\'t.',
					'Every lead goes into Lead Analytics, so you can compare Local Service Ads cost per lead with every other channel.',
				),
				'cta_text'       => 'Get My Free LSA Audit',
				'cta_url'        => '#contact',
			),
			'case-studies'    => array(
				'heading_format' => 'Paid Ads Results For %s',
				'heading_accent' => 'Growth-Focused Businesses',
				'description'    => 'Results from our paid search work: tight targeting, conversion tracking and data-backed optimization.',
				'more_url'       => home_url( '/case-studies/' ),
				'cards'          => array(
					array( 'theme' => 'teal', 'title' => 'Case Study: Manufacturing', 'number' => '30%', 'text' => 'ROI Increase From Paid Search', 'icon' => 'surface1.svg', 'chart' => 'paid-chart.svg' ),
					array( 'theme' => 'orange', 'title' => 'Case Study: ECommerce', 'number' => '4.2X', 'text' => 'Higher Return On Ad Spend', 'icon' => 'ecommerce.svg', 'chart' => 'ad-spend-chart.svg', 'footer_strip' => 'Data-Backed Strategies. Real Business Impact.' ),
				),
			),
		),
		'ppc'                   => array(
			'accomplishments' => array(
				'description' => 'As a PPC marketing agency, we build data-driven strategies that help businesses generate leads, increase revenue and scale with confidence.',
			),
			'expertise'       => array(
				'heading_format' => 'Our PPC %s',
				'heading_accent' => 'Expertise',
				'items'          => array(
					'As a performance marketing agency, we plan and manage paid campaigns across Google Ads, Local Services Ads, Shopping, YouTube and Meta, built around the leads your business needs.',
					'As a Google Partner, our team combines keyword and audience control, ad testing and conversion rate optimization to turn clicks into calls and sales.',
					'Every channel connects to Lead Analytics, so you can compare cost per lead across platforms and move budget to what works.',
				),
				'cta_text'       => 'Get My Free PPC Audit',
				'cta_url'        => '#contact',
			),
			'case-studies'    => array(
				'heading_format' => 'PPC Results For %s',
				'heading_accent' => 'Growth-Focused Businesses',
				'description'    => 'See how we turn paid media budgets into measurable growth with tight targeting, conversion tracking and data-backed optimization.',
				'more_url'       => home_url( '/case-studies/' ),
				'cards'          => array(
					array( 'theme' => 'teal', 'title' => 'Case Study: Manufacturing', 'number' => '30%', 'text' => 'ROI Increase From Paid Search', 'icon' => 'surface1.svg', 'chart' => 'paid-chart.svg' ),
					array( 'theme' => 'orange', 'title' => 'Case Study: ECommerce', 'number' => '4.2X', 'text' => 'Higher Return On Ad Spend', 'icon' => 'ecommerce.svg', 'chart' => 'ad-spend-chart.svg', 'footer_strip' => 'Data-Backed Strategies. Real Business Impact.' ),
				),
			),
		),
		'google-ads-management' => array(
			'accomplishments' => array(
				'description' => 'Our Google Ads management is built on data-driven strategies that help businesses generate leads, increase revenue and scale with confidence.',
			),
			'expertise'       => array(
				'heading_format' => 'Our Google Ads %s',
				'heading_accent' => 'Expertise',
				'items'          => array(
					'We build and manage Google Ads campaigns for local and service businesses, from Search and Performance Max to call and lead tracking.',
					'As a Google Partner, our team pairs keyword control, ad testing and conversion rate optimization so paid clicks turn into calls, forms and booked jobs.',
					'Every campaign connects to Lead Analytics, so you can see what each lead costs and which keywords bring in customers.',
				),
				'cta_text'       => 'Get My Free Google Ads Audit',
				'cta_url'        => '#contact',
			),
			'case-studies'    => array(
				'heading_format' => 'Google Ads Results For %s',
				'heading_accent' => 'Growth-Focused Businesses',
				'description'    => 'See how we turn Google Ads spend into measurable growth with tight targeting, conversion tracking and data-backed optimization.',
				'more_url'       => home_url( '/case-studies/' ),
				'cards'          => array(
					array(
						'theme'  => 'teal',
						'title'  => 'Case Study: Manufacturing',
						'number' => '30%',
						'text'   => 'ROI Increase From Paid Search',
						'icon'   => 'surface1.svg',
						'chart'  => 'paid-chart.svg',
					),
					array(
						'theme'        => 'orange',
						'title'        => 'Case Study: ECommerce',
						'number'       => '4.2X',
						'text'         => 'Higher Return On Ad Spend',
						'icon'         => 'ecommerce.svg',
						'chart'        => 'ad-spend-chart.svg',
						'footer_strip' => 'Data-Backed Strategies. Real Business Impact.',
					),
				),
			),
		),
	);

	if ( ! isset( $copy[ $slug ][ $key ] ) ) {
		return;
	}

	echo '<div class="sln-ppc-home-section sln-ppc-home-section--' . esc_attr( $key ) . '">';
	get_template_part( 'template-parts/sections/' . $key, null, $copy[ $slug ][ $key ] );
	echo '</div>';
}

/**
 * Styles/scripts for the reused homepage sections on PPC sub-pages.
 */
function sln_ppc_home_section_assets() {
	if ( ! is_page_template( SLN_PPC_TEMPLATE ) || ! sln_ppc_new_design() ) {
		return;
	}

	foreach ( array( 'accomplishments', 'expertise', 'case-studies' ) as $name ) {
		wp_enqueue_style( 'sln-' . $name, SLN_THEME_URI . '/assets/css/' . $name . '.css', array( 'sln-main' ), SLN_THEME_VERSION );
	}

	wp_enqueue_script( 'sln-accomplishments', SLN_THEME_URI . '/assets/js/accomplishments.js', array(), SLN_THEME_VERSION, true );
}
add_action( 'wp_enqueue_scripts', 'sln_ppc_home_section_assets', 25 );

/**
 * Whether the current page is the PPC hub (top-level page on the PPC template).
 *
 * @param int|null $post_id Optional post ID.
 * @return bool
 */
function sln_ppc_is_hub( $post_id = null ) {
	$post_id = sln_ppc_resolve_post_id( $post_id );
	$post    = $post_id ? get_post( $post_id ) : null;

	return $post instanceof WP_Post && ! $post->post_parent && sln_ppc_uses_template( $post_id );
}

/**
 * PPC services grid on the hub. Cards link to a sub-page once it exists;
 * until then they point to the audit form.
 *
 * @return array<int, array<string, mixed>>
 */
function sln_ppc_hub_services() {
	$hub   = sln_ppc_subpages_hub();
	$cards = array(
		array( 'slug' => 'google-ads-management', 'icon' => 'search', 'title' => 'Google Ads Management', 'text' => 'Search campaigns that reach people the moment they look for your service.', 'points' => array( 'Keyword and negative keyword control', 'Responsive ads and call assets', 'Call and form tracking' ) ),
		array( 'slug' => 'local-service-ads', 'icon' => 'shield-check', 'title' => 'Local Service Ads', 'text' => 'Pay-per-lead placement at the top of Google for eligible local businesses.', 'points' => array( 'Profile setup and optimization', 'Lead review and disputes', 'Leads tracked next to other channels' ) ),
		array( 'slug' => 'facebook-ads-management', 'icon' => 'users', 'title' => 'Facebook & Instagram Ads', 'text' => 'Meta ads on Facebook and Instagram that turn attention into enquiries.', 'points' => array( 'Audience and offer testing', 'Lead forms with qualifying questions', 'Meta leads in Lead Analytics' ) ),
		array( 'slug' => 'ecommerce-ppc', 'icon' => 'cart', 'title' => 'Ecommerce PPC', 'text' => 'Google Shopping and Performance Max for businesses that sell online.', 'points' => array( 'Product feed and Merchant Center', 'Product and asset group structure', 'Sales and revenue tracking' ) ),
		array( 'slug' => 'youtube-ads', 'icon' => 'play', 'title' => 'YouTube Ads', 'text' => 'Video ads that build demand before people search.', 'points' => array( 'Audience and intent targeting', 'Short ad formats', 'Viewers retargeted on search' ) ),
		array( 'slug' => 'retargeting-ads', 'icon' => 'refresh', 'title' => 'Retargeting Ads', 'text' => 'Bring back visitors who left without calling or buying.', 'points' => array( 'Audiences by page visited', 'Frequency caps', 'Message matched to the visit' ) ),
		array( 'slug' => 'free-ppc-audit', 'icon' => 'clipboard-check', 'title' => 'Free PPC Audit', 'text' => 'A review of your current ad accounts and where budget is leaking.', 'points' => array( 'Wasted spend check', 'Tracking check', 'Clear next steps' ) ),
	);

	foreach ( $cards as &$card ) {
		$page = $hub ? get_page_by_path( get_page_uri( $hub ) . '/' . $card['slug'], OBJECT, 'page' ) : null;
		$live = $page instanceof WP_Post && 'publish' === $page->post_status;

		$card['url']  = $live ? get_permalink( $page ) : '#contact';
		$card['live'] = $live;
	}
	unset( $card );

	return $cards;
}

/**
 * "PPC 101" explainer on the hub — reuses the interactive guide.
 *
 * @return array<string, mixed>
 */
function sln_ppc_explainer() {
	return array(
		'id'      => 'ppc-101',
		'section' => array(
			'small_heading'    => 'PPC 101',
			'main_heading'     => 'How Pay-Per-Click',
			'highlighted_text' => 'Advertising Works.',
			'description'      => 'Before you choose anyone for pay-per-click management, here is how PPC works, in plain English.',
			'active'           => true,
		),
		'items'   => array(
			array( 'problem' => 'You pay only when someone clicks', 'solution' => 'PPC (pay-per-click) advertising places your ads on Google, YouTube, Facebook and Instagram, and you pay when someone <b>clicks</b>, not when they see the ad. You set the budget, the audience and where the ads show. PPC marketing is the work of planning and improving those ads so the clicks turn into customers.' ),
			array( 'problem' => 'Higher bids alone don\'t win', 'solution' => 'Every search runs a quick <b>ad auction</b>. Google ranks ads by your bid and how relevant and useful your ad and landing page are, so a better ad can show above a bigger budget.' ),
			array( 'problem' => 'Relevance lowers your costs', 'solution' => '<b>Quality Score</b> rates your keywords from 1 to 10 based on expected click-through rate, ad relevance and landing page experience. Higher quality usually means lower cost per click.' ),
			array( 'problem' => 'Different goals, different ad types', 'solution' => 'Search ads catch people ready to buy. <b>Local Services Ads</b> bring calls for local services. Shopping and Performance Max sell products. YouTube and Meta build demand, and retargeting brings visitors back.' ),
		),
		'steps'   => array(
			array( 'label' => 'What Is PPC?', 'icon' => 'search', 'visual' => 'serp', 'points' => array( 'Ads above and beside search results', 'Pay per click, with a budget you set', 'Targeting by search, location and audience' ) ),
			array( 'label' => 'The Ad Auction', 'icon' => 'gauge', 'visual' => 'auction', 'points' => array( 'Runs on every eligible search', 'Bid plus ad quality decides position', 'Better ads can pay less per click' ) ),
			array( 'label' => 'Quality Score', 'icon' => 'target', 'visual' => 'quality-score', 'points' => array( 'Expected click-through rate', 'Ad relevance to the search', 'Landing page experience' ) ),
			array( 'label' => 'Types of PPC Ads', 'icon' => 'layers', 'visual' => 'ad-types', 'points' => array( 'Search and Local Services Ads', 'Shopping, Performance Max and YouTube', 'Meta Ads and retargeting' ) ),
		),
	);
}

/**
 * SEO vs PPC comparison on the hub.
 *
 * @return array<string, mixed>
 */
function sln_ppc_seo_vs_ppc() {
	$rows = array( 'Speed', 'Cost', 'Traffic', 'Best for' );

	return array(
		'rows'  => $rows,
		'sides' => array(
			'ppc'  => array(
				'label'  => 'PPC',
				'icon'   => 'zap',
				'title'  => 'Pay-Per-Click',
				'values' => array( 'Ads can show as soon as campaigns go live', 'You pay for each click, on a budget you control', 'Stops when the budget stops', 'Fast leads, new offers, seasonal pushes, testing' ),
			),
			'seo'  => array(
				'label'  => 'SEO',
				'icon'   => 'trending-up',
				'title'  => 'Search Engine Optimization',
				'values' => array( 'Builds over months', 'No cost per click; you invest in content and optimization', 'Keeps coming once rankings are earned', 'Long-term visibility and lower cost per lead over time' ),
			),
			'both' => array(
				'label'  => 'Both',
				'icon'   => 'layers',
				'title'  => 'PPC + SEO Together',
				'values' => array( 'Leads from PPC now while SEO builds', 'PPC spend can ease as organic traffic grows', 'Paid and organic results on the same page, with ad search data guiding SEO', 'Businesses that want leads now and lower costs later' ),
			),
		),
		'seo_url' => home_url( '/seo-services/' ),
	);
}

/**
 * FAQ tabs per page slug: [ label, number of items ] in order.
 *
 * @return array<int, array{0: string, 1: int}>
 */
function sln_ppc_faq_groups() {
	$post = get_post( sln_ppc_resolve_post_id() );
	$slug = $post instanceof WP_Post ? $post->post_name : '';

	$map = array(
		'ppc' => array( array( 'About Our PPC Services', 6 ), array( 'About PPC', 6 ) ),
	);

	return $map[ $slug ] ?? array();
}

/**
 * Extra page-specific sections, rendered after the quick-truth section.
 *
 * @return array<int, array{0: string, 1: array<string, mixed>}> [ template slug, args ].
 */
function sln_ppc_page_extras() {
	$post = get_post( sln_ppc_resolve_post_id() );
	$slug = $post instanceof WP_Post ? $post->post_name : '';

	$hub    = sln_ppc_subpages_hub();
	$gads   = $hub ? get_page_by_path( get_page_uri( $hub ) . '/google-ads-management', OBJECT, 'page' ) : null;
	$gads_u = $gads instanceof WP_Post ? get_permalink( $gads ) : '';

	if ( 'facebook-ads-management' === $slug ) {
		return array(
			array(
				'seo-vs-ppc',
				array(
					'id'      => 'facebook-vs-google-ads',
					'order'   => array( 'meta', 'gads', 'both' ),
					'section' => array(
						'small_heading'    => 'Meta vs Google',
						'main_heading'     => 'Facebook Ads vs Google Ads:',
						'highlighted_text' => 'Which Should You Run?',
						'description'      => 'One builds demand, the other captures it. Switch between them to compare.',
					),
					'data'    => array(
						'rows'       => array( 'Reaches', 'Best for', 'Ad format', 'You pay' ),
						'sides'      => array(
							'meta' => array( 'label' => 'Facebook', 'icon' => 'users', 'title' => 'Facebook & Instagram Ads', 'values' => array( 'People before they search, by location and interests', 'Visual offers, new customers and building demand', 'Images, video, carousels, Reels and Stories', 'Based on delivery, within your budget' ) ),
							'gads' => array( 'label' => 'Google Ads', 'icon' => 'search', 'title' => 'Google Search Ads', 'values' => array( 'People already searching for your service', 'Ready-to-buy demand and urgent needs', 'Text ads with calls, locations and sitelinks', 'Per click, on a budget you control' ) ),
							'both' => array( 'label' => 'Both', 'icon' => 'layers', 'title' => 'Facebook + Google Together', 'values' => array( 'People before and while they search', 'Building demand on Meta and capturing it on Google', 'Retargeting site visitors on Facebook and Instagram', 'One budget, tracked in one Lead Analytics view' ) ),
						),
						'link_label' => 'Explore Google Ads management',
						'link_url'   => $gads_u,
					),
				),
			),
		);
	}

	if ( 'ecommerce-ppc' === $slug ) {
		return array(
			array(
				'seo-vs-ppc',
				array(
					'id'      => 'shopping-vs-pmax',
					'order'   => array( 'shop', 'pmax', 'both' ),
					'section' => array(
						'small_heading'    => 'Shopping vs PMax',
						'main_heading'     => 'Performance Max vs Standard Shopping:',
						'highlighted_text' => 'Which Should You Run?',
						'description'      => 'Both show your products on Google. They give you very different levels of control.',
					),
					'data'    => array(
						'rows'       => array( 'Shows on', 'Control', 'Needs', 'Best for' ),
						'sides'      => array(
							'shop' => array( 'label' => 'Shopping', 'icon' => 'cart', 'title' => 'Standard Shopping', 'values' => array( 'Google Shopping results and the Shopping tab', 'Products, bids and search terms you can see', 'A clean feed and manual structure', 'Tight control, key products and testing' ) ),
							'pmax' => array( 'label' => 'PMax', 'icon' => 'sparkles', 'title' => 'Performance Max', 'values' => array( 'Search, Shopping, YouTube, Display, Gmail and Maps', 'Google\'s automation, guided by signals', 'Strong feed, creative assets and tracked revenue', 'Reach and scale across every Google placement' ) ),
							'both' => array( 'label' => 'Both', 'icon' => 'layers', 'title' => 'Shopping + Performance Max', 'values' => array( 'Every Google placement, with control where it counts', 'Clear rules for which products go where', 'One feed, one tracking setup', 'Most growing stores' ) ),
						),
						'link_label' => '',
						'link_url'   => '',
					),
				),
			),
		);
	}

	if ( 'free-ppc-audit' === $slug ) {
		return array(
			array( 'audit-platforms', array() ),
			array( 'audit-report', array() ),
		);
	}

	if ( 'local-service-ads' !== $slug ) {
		return array();
	}

	return array(
		array(
			'badge-explainer',
			array(
				'section'     => array(
					'small_heading'    => 'Google Guaranteed',
					'main_heading'     => 'Google Guaranteed:',
					'highlighted_text' => 'What the Badge Means.',
					'description'      => 'The badge next to your name tells customers Google has screened your business. Here\'s what each badge is and how to get Google Guaranteed.',
				),
				'badges'      => array(
					array(
						'title'  => 'Google Guaranteed',
						'for'    => 'For home service businesses, such as cleaners, contractors and remodelers.',
						'points' => array( 'Green checkmark next to your business name', 'Shows customers you passed Google\'s screening', 'Backed by Google\'s customer guarantee, under Google\'s terms' ),
					),
					array(
						'title'  => 'Google Screened',
						'for'    => 'For professional services, such as legal, financial or real estate businesses.',
						'points' => array( 'Badge shown next to your listing', 'Business and professional checks', 'Same pay-per-lead ads at the top of Google' ),
					),
				),
				'steps_title' => 'How to get Google Guaranteed',
				'steps'       => array(
					array( 'Check eligibility', ' Your category and service area must be supported.' ),
					array( 'Build your profile', ' Business details, job types, areas and hours.' ),
					array( 'Pass screening', ' Background check plus license and insurance checks, where required.' ),
					array( 'Get reviews', ' Reviews help your profile and your ranking.' ),
					array( 'Go live', ' Your badge shows once Google approves the profile.' ),
				),
				'note'        => 'We guide you through every step. Google runs the checks and makes the final decision.',
			),
		),
		array(
			'seo-vs-ppc',
			array(
				'id'      => 'lsa-vs-google-ads',
				'order'   => array( 'lsa', 'gads', 'both' ),
				'section' => array(
					'small_heading'    => 'LSA vs Google Ads',
					'main_heading'     => 'Local Service Ads vs Google Ads:',
					'highlighted_text' => 'Which Should You Run?',
					'description'      => 'Both put you at the top of Google. They charge differently and give you different levels of control.',
				),
				'data'    => array(
					'rows'       => array( 'You pay', 'Placement', 'Control', 'Best for' ),
					'sides'      => array(
						'lsa'  => array( 'label' => 'LSA', 'icon' => 'shield-check', 'title' => 'Local Service Ads', 'values' => array( 'Per lead: a call, message or booking', 'Very top of Google, above regular ads, with a badge', 'Job types, areas, hours and budget', 'Local service businesses that want calls' ) ),
						'gads' => array( 'label' => 'Google Ads', 'icon' => 'search', 'title' => 'Google Search Ads', 'values' => array( 'Per click, on a budget you control', 'Sponsored results below Local Service Ads', 'Keywords, ads, bids and landing pages', 'Services LSA doesn\'t cover, and more control' ) ),
						'both' => array( 'label' => 'Both', 'icon' => 'layers', 'title' => 'LSA + Google Ads Together', 'values' => array( 'Leads from LSA, clicks from Search', 'Two spots at the top of the same page', 'LSA for core jobs, Search for everything else', 'Businesses that want the most calls from Google' ) ),
					),
					'link_label' => 'Explore Google Ads management',
					'link_url'   => $gads_u,
				),
			),
		),
	);
}

/**
 * Free PPC Audit request form — rendered on the audit page in place of the
 * final CTA. Submits to sln_ppc_audit_form_handler() (GoHighLevel).
 */
function sln_ppc_render_audit_form() {
	$post = get_post( sln_ppc_resolve_post_id() );

	if ( ! $post instanceof WP_Post || 'free-ppc-audit' !== $post->post_name ) {
		return;
	}

	get_template_part( 'template-parts/ppc-google-ads/audit-form' );
}

/**
 * Hero stat cards on all PPC pages. Owner-confirmed figures only, chosen so
 * they don't repeat the Accomplishments section (100k+ · 3X · $50M+ · 200%).
 *
 * @return array<int, array<string, mixed>>
 */
function sln_ppc_hero_stats() {
	return array(
		array( 'icon' => 'trending-up', 'prefix' => '', 'value' => '4.2', 'decimals' => '1', 'suffix' => 'X', 'label' => 'Higher ROAS, ecommerce client', 'active' => true ),
		array( 'icon' => 'line-chart', 'prefix' => '', 'value' => '30', 'decimals' => '0', 'suffix' => '%', 'label' => 'ROI increase, paid search', 'active' => true ),
		array( 'icon' => 'layers', 'prefix' => '', 'value' => '47', 'decimals' => '0', 'suffix' => '+', 'label' => 'Client campaigns in 24 months', 'active' => true ),
		array( 'icon' => 'shield-check', 'prefix' => '', 'value' => '8', 'decimals' => '0', 'suffix' => '+', 'label' => 'Years in business, since 2018', 'active' => true ),
	);
}

/**
 * Platform logos in the hero panel. Only platforms SLS runs; the Google
 * Partner badge is owner-confirmed. (No Microsoft/Bing: not confirmed.)
 *
 * @return array<int, array{name: string, src: string, w: int, h: int, caption: string}>
 */
function sln_ppc_hero_logos() {
	$base = SLN_THEME_URI . '/assets/images/';

	return array(
		array( 'name' => 'Google Partner', 'src' => $base . 'Google-Partner.webp', 'w' => 120, 'h' => 40, 'caption' => '' ),
		array( 'name' => 'Google Ads', 'src' => $base . 'lead-analytics/google-ads.webp', 'w' => 36, 'h' => 36, 'caption' => 'Google Ads' ),
		array( 'name' => 'Meta Ads', 'src' => $base . 'lead-analytics/meta.webp', 'w' => 36, 'h' => 36, 'caption' => 'Meta Ads' ),
		array( 'name' => 'Google Local Services', 'src' => $base . 'lead-analytics/google-business-profile.webp', 'w' => 36, 'h' => 36, 'caption' => 'Local Services' ),
	);
}
