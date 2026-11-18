<?php
/**
 * Starter content for the PPC sub-pages (children of the main PPC page).
 *
 * Used once by the "PPC Sub-pages" admin tool to create each page with the
 * PPC & Google Ads template. After a page exists, edit it in WP-admin — this
 * file is not read again for that page.
 *
 * Copy source: work/ppc/<slug>/03-content.md (ppc-copywriting skill).
 * Content rules (project-context/business-facts.md): no invented results,
 * prices, timelines, reviews, guarantees or certifications. Template sections
 * that only carry such claims (stats, proof, pricing, ROI estimator, budget
 * split chart) are switched off per page.
 *
 * @package Smart_Leading_Net
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Mark every row active.
 *
 * @param array<int, array<string, mixed>> $rows Rows.
 * @return array<int, array<string, mixed>>
 */
function sln_ppc_subpage_rows( array $rows ) {
	return array_map(
		static function ( $row ) {
			return array_merge( $row, array( 'active' => true ) );
		},
		$rows
	);
}

/**
 * Meta that switches off claim-only sections on every sub-page.
 *
 * Rows stored as a single inactive row render nothing (an empty array would
 * fall back to the main page defaults).
 *
 * @return array<string, mixed>
 */
function sln_ppc_subpage_hidden_sections() {
	$off_row = array( array( 'active' => false ) );

	return array(
		SLN_PPC_STATS_META           => $off_row,
		SLN_PPC_TRUST_SECTION_META   => array( 'active' => false ),
		SLN_PPC_REALITY_BUDGET_META  => array( 'active' => false ),
		SLN_PPC_PROOF_SECTION_META   => array( 'active' => false ),
		SLN_PPC_CASE_STUDIES_META    => $off_row,
		SLN_PPC_ROI_SECTION_META     => array( 'active' => false ),
		SLN_PPC_PRICING_SECTION_META => array( 'active' => false ),
		SLN_PPC_PRICING_PLANS_META   => $off_row,
	);
}

/**
 * Sub-page definitions, in menu order.
 *
 * @return array<int, array{slug: string, title: string, meta: array<string, mixed>}>
 */
function sln_ppc_subpages_definitions() {
	$audit_url = '#contact'; // Switch to the Free PPC Audit page once it exists.

	return array(
		array(
			'slug'  => 'google-ads-management',
			'title' => 'Google Ads Management',
			'meta'  => array_merge(
				sln_ppc_subpage_hidden_sections(),
				array(
					'_sln_seo_title'       => 'Google Ads Management Services for More Leads | Smart Leading',
					'_sln_focus_keyword'   => 'google ads management services',
					'_sln_seo_description' => 'Google Ads management services that cut wasted spend and track every call and form. You keep your account. Get a free Google Ads audit from Smart Leading.',
					'_sln_service_type'    => 'Google Ads management',

					SLN_PPC_HERO_META => array(
						'small_heading'             => '', // Breadcrumb already names the page.
						'main_heading'              => 'Google Ads Management Services That',
						'highlighted_text'          => 'Turn Clicks Into Calls.',
						'description'               => 'Paying for Google Ads but not getting the calls? Our Google Ads management services find where your budget leaks, fix it, and track every call and form back to the ad that brought it in. Your account stays in your name.',
						'primary_button_text'       => 'Get My Free Google Ads Audit',
						'primary_button_url'        => $audit_url,
						'secondary_button_text'     => 'See how we manage Google Ads',
						'secondary_button_url'      => '#approach',
						'search_label'              => 'When your customers search, your ad shows up',
						'search_result_ad_label'    => 'Ad',
						'search_result_url'         => 'www.smartleading.net',
						'search_result_title'       => 'Google Ads Management | Smart Leading Solutions',
						'search_result_description' => 'Cut wasted spend. Track every call and form. Your account stays in your name.',
						'dashboard_title'           => 'Why Business Owners Trust Us',
						'chart_label'               => 'ILLUSTRATIVE · LEADS BY WEEK',
						'live_label'                => 'SINCE 2018',
						'active'                    => true,
					),
					SLN_PPC_HERO_SEARCH_QUERIES_META => sln_ppc_subpage_rows(
						array(
							array( 'query' => 'kitchen remodel near me' ),
							array( 'query' => 'house cleaning service' ),
							array( 'query' => 'emergency dentist near me' ),
							array( 'query' => 'bathroom renovation quote' ),
							array( 'query' => 'solar installation cost' ),
						)
					),
					// Hero USP tiles (owner-confirmed 17 Nov 2026: Google Partner, CRO).
					SLN_PPC_HERO_METRICS_META => sln_ppc_subpage_rows(
						array(
							array( 'display_value' => 'Google Partner', 'label' => 'Part of Google\'s Partners program', 'visual_style' => 'azure' ),
							array( 'display_value' => 'CRO Focused', 'label' => 'Pages built to turn clicks into calls', 'visual_style' => 'orange' ),
							array( 'display_value' => 'Every Lead Tracked', 'label' => 'Calls and forms in Lead Analytics', 'visual_style' => 'up' ),
							array( 'display_value' => 'Your Account', 'label' => 'In your name. No passwords asked', 'visual_style' => 'azure' ),
						)
					),
					SLN_PPC_KEYWORD_MARQUEE_META => sln_ppc_subpage_rows(
						array(
							array( 'keyword' => 'kitchen remodel near me', 'icon_text' => '⌕' ),
							array( 'keyword' => 'house cleaning service', 'icon_text' => '⌕' ),
							array( 'keyword' => 'emergency dentist near me', 'icon_text' => '⌕' ),
							array( 'keyword' => 'home renovation contractor', 'icon_text' => '⌕' ),
							array( 'keyword' => 'solar panels quote', 'icon_text' => '⌕' ),
							array( 'keyword' => 'meal delivery service', 'icon_text' => '⌕' ),
							array( 'keyword' => 'end of lease cleaning', 'icon_text' => '⌕' ),
							array( 'keyword' => 'dental implants consultation', 'icon_text' => '⌕' ),
						)
					),
					SLN_PPC_REALITY_SECTION_META => array(
						'small_heading'    => 'The Problem',
						'main_heading'     => 'Why Your Google Ads Spend',
						'highlighted_text' => 'Isn\'t Bringing Customers.',
						'description'      => 'Most Google Ads accounts don\'t fail because Google Ads doesn\'t work. They fail because nobody is watching the details that decide where your money goes.',
						'bottom_note'      => 'If even one of these sounds like your account, <b>it needs management, not more budget.</b>',
						'active'           => true,
					),
					SLN_PPC_REALITY_CHALLENGES_META => sln_ppc_subpage_rows(
						array(
							array( 'icon_text' => 'search-x', 'title' => 'Paying for the Wrong Searches', 'description' => 'Your ads show for "free", "jobs" or "DIY" searches. Every one of those clicks costs you and none of them will ever book.', 'impact' => 'WASTED SPEND' ),
							array( 'icon_text' => 'phone-off', 'title' => 'Clicks, but the Phone Is Quiet', 'description' => 'Traffic looks fine in the report, but calls and quote requests haven\'t moved.', 'impact' => 'NO LEADS' ),
							array( 'icon_text' => 'map-pin-off', 'title' => 'Ads in the Wrong Places', 'description' => 'Broad location settings show your ads outside your service area, or at hours you can\'t answer the phone.', 'impact' => 'BUDGET DRAIN' ),
							array( 'icon_text' => 'eye-off', 'title' => 'No Idea Which Ads Bring Leads', 'description' => 'Without call and form tracking, you can\'t tell a keyword that brings jobs from one that only burns budget.', 'impact' => 'ZERO CLARITY' ),
							array( 'icon_text' => 'trending-up', 'title' => 'Costs Creep Up Every Month', 'description' => 'Low ad relevance and weak landing pages push your cost per click up while results stay flat.', 'impact' => 'RISING COSTS' ),
							array( 'icon_text' => 'file-question', 'title' => 'Reports You Can\'t Read', 'description' => 'You get impressions and CTR. You wanted to know how many customers you got and what each one cost.', 'impact' => 'NO TRANSPARENCY' ),
						)
					),

					SLN_PPC_APPROACH_SECTION_META => array(
						'small_heading'    => 'Our Approach',
						'main_heading'     => 'How We Handle Google Ads',
						'highlighted_text' => 'Campaign Management.',
						'description'      => 'As a Google Ads management agency, we start with the same question you ask: which clicks turned into customers? Every change we make to your account comes back to that number.',
						'active'           => true,
					),
					SLN_PPC_APPROACH_ITEMS_META => sln_ppc_subpage_rows(
						array(
							array( 'problem' => 'Paying for searches that never buy', 'solution' => 'We review your <b>search terms report</b> and add <b>negative keywords</b>, so you stop paying for clicks that were never going to buy.' ),
							array( 'problem' => 'Keywords that bring the wrong people', 'solution' => 'We group keywords by service and use <b>match types</b> that bring the searches you want without the noise.' ),
							array( 'problem' => 'Ads nobody clicks', 'solution' => 'We write <b>responsive search ads and assets</b> (calls, locations, offers) that match what each searcher typed. That lifts Quality Score and lowers cost.' ),
							array( 'problem' => 'Budget spent outside your area', 'solution' => 'We set <b>location, radius and ad schedule</b> so your budget only runs where you work and when someone can answer.' ),
							array( 'problem' => 'Bids that chase clicks, not leads', 'solution' => 'We use <b>bidding strategies</b> such as Maximize Conversions or Target CPA once tracking proves which leads count.' ),
							array( 'problem' => 'Visitors who leave without calling', 'solution' => 'We check that each ad lands on a <b>page built to turn visitors into calls</b> and enquiries.' ),
						)
					),

					SLN_PPC_TRUTH_SECTION_META => array(
						'statement'        => 'What Is Google Ads',
						'highlighted_text' => 'Management?',
						'body'             => 'Google Ads management is the ongoing work of planning, running and improving your Google Ads campaigns so your budget brings in leads. That covers keyword research, negative keywords, ad copy, bidding, location targeting, conversion tracking and landing pages. Each one gets reviewed and adjusted continually, not set up once and left running.<br><br>Good Google PPC management is mostly about saying no: no to irrelevant searches, no to areas you don\'t serve, no to hours nobody answers. Whatever budget is left goes to the searches most likely to call you.',
						'quote'            => 'Clicks were never the goal.',
						'quote_highlight'  => 'Customers are.',
						'attribution'      => '',
						'button_text'      => 'Get My Free Google Ads Audit',
						'button_url'       => $audit_url,
						'active'           => true,
					),

					SLN_PPC_WHY_SECTION_META => array(
						'small_heading'    => 'Why SLS',
						'main_heading'     => 'A Google Ads Agency That',
						'highlighted_text' => 'Reports Leads, Not Clicks.',
						'description'      => 'Many business owners come to us after an agency that sent nice-looking reports and couldn\'t explain where the leads were. Smart Leading Solutions LLC has worked with business owners in the USA and Australia since 2018. Here\'s how we do it differently.',
						'left_heading'     => 'Typical Agency',
						'right_heading'    => 'Smart Leading Solutions',
						'active'           => true,
					),
					SLN_PPC_WHY_COMPARISON_META => sln_ppc_subpage_rows(
						array(
							array( 'typical' => 'Account set up under the agency', 'sls' => 'Account in <b>your business name</b>. We\'re added through our manager account (MCC), never as owner' ),
							array( 'typical' => 'Asks for your login details', 'sls' => 'We <b>never ask for your password</b>, and you can remove our access any time' ),
							array( 'typical' => 'Reports clicks and impressions', 'sls' => 'Reports <b>calls, forms and cost per lead</b>' ),
							array( 'typical' => 'Set it and forget it', 'sls' => 'Search terms, negatives and bids <b>reviewed continually</b>' ),
							array( 'typical' => 'Hides what isn\'t working', 'sls' => '<b>Real numbers, even when they\'re bad.</b> If something isn\'t working, we tell you and change it' ),
						)
					),
					SLN_PPC_WHY_BADGES_META => sln_ppc_subpage_rows(
						array(
							array( 'text' => 'Google Partner' ),
							array( 'text' => 'Your account, your data' ),
							array( 'text' => 'Every lead tracked' ),
							array( 'text' => 'Since 2018' ),
						)
					),

					SLN_PPC_SERVICES_SECTION_META => array(
						'small_heading'    => 'What\'s Included',
						'main_heading'     => 'What\'s Included in Our Google Ads',
						'highlighted_text' => 'Campaign Management Services.',
						'description'      => 'Our AdWords management services, now Google Ads, cover the full account. You don\'t have to piece it together from different freelancers. Part of our PPC management services.',
						'active'           => true,
					),
					SLN_PPC_SERVICES_ITEMS_META => sln_ppc_subpage_rows(
						array(
							array( 'icon_key' => 'data', 'icon_text' => '▥', 'icon_style' => 'blue', 'tag' => 'AUDIT', 'title' => 'Account Audit and Setup', 'description' => 'A full review of your current account. If you don\'t have one, we create it in your business name.' ),
							array( 'icon_key' => 'search', 'icon_text' => '⌕', 'icon_style' => 'blue', 'tag' => 'KEYWORDS', 'title' => 'Keyword Research', 'description' => 'Keywords picked by intent: people ready to call, book or ask for a quote.' ),
							array( 'icon_key' => 'search', 'icon_text' => '⌕', 'icon_style' => 'blue', 'tag' => 'SEARCH', 'title' => 'Search Campaigns', 'description' => 'Campaigns built around your services and service areas.' ),
							array( 'icon_key' => 'ai', 'icon_text' => '✦', 'icon_style' => 'blue', 'tag' => 'ADS', 'title' => 'Ads and Assets', 'description' => 'Responsive search ads, call assets and location assets, tested and refreshed.' ),
							array( 'icon_key' => 'data', 'icon_text' => '▥', 'icon_style' => 'blue', 'tag' => 'TRACKING', 'title' => 'Conversion Tracking', 'description' => 'Calls, forms and quote requests tracked with Google Tag Manager and GA4.' ),
							array( 'icon_key' => 'cro', 'icon_text' => '▤', 'icon_style' => 'blue', 'tag' => 'OPTIMIZE', 'title' => 'Ongoing Optimization', 'description' => 'Negative keywords, bids, budgets and schedules adjusted continually.' ),
							array( 'icon_key' => 'pmax', 'icon_text' => '▣', 'icon_style' => 'blue', 'tag' => 'PMAX', 'title' => 'Performance Max', 'description' => 'When it fits: for businesses with products or a catalogue.' ),
							array( 'icon_key' => 'retarget', 'icon_text' => '↺', 'icon_style' => 'blue', 'tag' => 'REMARKETING', 'title' => 'Remarketing', 'description' => 'Add-on: bring back visitors who left without calling.' ),
							array( 'icon_key' => 'lsa', 'icon_text' => '●', 'icon_style' => 'blue', 'tag' => 'LSA', 'title' => 'Local Services Ads', 'description' => 'Alongside Search: pay-per-lead placement above regular ads for eligible service businesses.' ),
						)
					),

					SLN_PPC_PROCESS_SECTION_META => array(
						'small_heading'    => 'How It Starts',
						'main_heading'     => 'How Our Google Ads Management',
						'highlighted_text' => 'Company Gets Started.',
						'description'      => 'Starting Google Ads PPC management with us is simple. Here\'s what happens after you get in touch.',
						'bottom_note'      => 'You always know what\'s running, what it costs, and which leads it brought in.',
						'active'           => true,
					),
					SLN_PPC_PROCESS_STEPS_META => sln_ppc_subpage_rows(
						array(
							array( 'number' => '01', 'title' => 'Discovery Call', 'bullets' => array( 'Your services and service area', 'The customers you want', 'Your budget and goals' ) ),
							array( 'number' => '02', 'title' => 'Account Audit', 'bullets' => array( 'Review your current account', 'Or plan a new one', 'Show where money is wasted' ) ),
							array( 'number' => '03', 'title' => 'Rebuild and Launch', 'bullets' => array( 'Fix structure and keywords', 'New ads and tracking', 'Launch with your approval' ) ),
							array( 'number' => '04', 'title' => 'Optimize and Report', 'bullets' => array( 'Refine searches and bids', 'Test ads continually', 'Report the leads it brings' ) ),
						)
					),

					SLN_PPC_MID_CTA_META => array(
						'heading'     => 'Not Sure Your Google Ads Are Working?',
						'description' => 'Get a free review of your Google Ads account. We\'ll show you what\'s working, what\'s wasting money, and what we\'d change first.',
						'button_text' => 'Get My Free Google Ads Audit',
						'button_url'  => $audit_url,
						'active'      => true,
					),

					SLN_PPC_INDUSTRIES_SECTION_META => array(
						'small_heading'    => 'Industries',
						'main_heading'     => 'Google Ads for Local and',
						'highlighted_text' => 'Service Businesses.',
						'description'      => 'If your customers search before they call, Google Ads can bring them to you. These are businesses we work with:',
						'active'           => true,
					),
					SLN_PPC_INDUSTRIES_ITEMS_META => sln_ppc_subpage_rows(
						array(
							array( 'icon_text' => 'chef-hat', 'title' => 'Kitchen Businesses', 'description' => 'Quote requests from homeowners planning a remodel in your area.' ),
							array( 'icon_text' => 'hammer', 'title' => 'Home Improvement', 'description' => 'Calls for jobs you actually do, not tire-kickers outside your area.' ),
							array( 'icon_text' => 'sparkles', 'title' => 'Cleaning', 'description' => 'Steady enquiries for one-off and recurring cleans.' ),
							array( 'icon_text' => 'tooth', 'title' => 'Dentistry', 'description' => 'New-patient calls from people searching for a dentist nearby.' ),
							array( 'icon_text' => 'zap', 'title' => 'Energy', 'description' => 'Enquiries from homeowners and businesses comparing energy options.' ),
							array( 'icon_text' => 'utensils', 'title' => 'Meal Delivery', 'description' => 'Orders and sign-ups from people ready to try a new service.' ),
						)
					),

					SLN_PPC_FAQ_SECTION_META => array(
						'small_heading'    => 'Common Questions',
						'main_heading'     => 'Google Ads Management',
						'highlighted_text' => 'FAQs.',
						'description'      => 'Straight answers before we touch your ad account.',
						'active'           => true,
					),
					SLN_PPC_FAQ_ITEMS_META => sln_ppc_subpage_rows(
						array(
							array( 'question' => 'How much does Google Ads management cost?', 'answer' => 'You pay two separate costs: your ad spend, which goes directly to Google, and a management fee for running the account. The fee depends on how many campaigns and services we manage.' ),
							array( 'question' => 'Who owns the Google Ads account?', 'answer' => 'You do. We\'re added as a manager, never as the owner, and we never ask for your password. If you don\'t have an account, we create it in your business name. You can remove our access whenever you choose.' ),
							array( 'question' => 'What should I expect from a Google Ads management agency?', 'answer' => 'Clear answers on three things: which searches your ads show for, how many calls and forms they bring in, and what each lead costs. Expect regular changes to keywords, ads and bids, and honest reporting when something isn\'t working.' ),
							array( 'question' => 'How do I choose a Google Ads management agency?', 'answer' => 'Ask who will own the account, how leads are tracked, and what the report will show. Be wary of anyone who only reports clicks and impressions, or wants your account set up in their name. A good agency should explain its changes in plain English.' ),
							array( 'question' => 'Can I manage Google Ads on a small budget?', 'answer' => 'Yes. A smaller budget makes tight control more important, not less. We narrow keywords, locations and schedules so a limited budget goes to the searches most likely to turn into customers, instead of spreading thin.' ),
							array( 'question' => 'Is an agency better than managing Google Ads in-house?', 'answer' => 'If someone on your team has the time and experience to review search terms, bids and tracking every week, in-house can work. Most business owners don\'t have that time. An agency brings a process and someone accountable for the results.' ),
							array( 'question' => 'How long before I see results from Google Ads?', 'answer' => 'Ads can start getting clicks soon after launch. Turning those clicks into steady, affordable leads takes ongoing testing of keywords, ads and pages.' ),
							array( 'question' => 'Do you also run Local Services Ads and Performance Max?', 'answer' => 'Yes. Depending on your business, we may recommend Local Services Ads for pay-per-lead calls or Performance Max if you sell products, alongside your Search campaigns.' ),
						)
					),

					SLN_PPC_FINAL_CTA_META => array(
						'small_heading'         => 'Your Next Step',
						'main_heading'          => 'Get Google Ads Management Services',
						'highlighted_text'      => 'That Show You the Leads.',
						'description'           => 'Tell us about your business and share your website. We\'ll review your Google Ads and show you where your budget is going.',
						'primary_button_text'   => 'Get My Free Google Ads Audit',
						'primary_button_url'    => home_url( '/ppc/free-ppc-audit/' ),
						'secondary_button_text' => 'See how we work',
						'secondary_button_url'  => '#process',
						'website_label'         => '',
						'website_text'          => '',
						'website_url'           => '',
						'bottom_note'           => 'Your account stays yours. No passwords needed.',
						'active'                => true,
					),
				)
			),
		),
		array(
			'slug'  => 'local-service-ads',
			'title' => 'Local Service Ads Management',
			'meta'  => array_merge(
				sln_ppc_subpage_hidden_sections(),
				array(
					'_sln_seo_title'       => 'Local Service Ads Management for More Calls | Smart Leading',
					'_sln_focus_keyword'   => 'local service ads management',
					'_sln_seo_description' => 'Google Local Service Ads management: setup, Google Guaranteed verification, reviews and lead disputes, with every call tracked. Get a free audit.',
					'_sln_service_type'    => 'Google Local Services Ads management',

					SLN_PPC_HERO_META => array(
						'small_heading'             => '',
						'main_heading'              => 'Google Local Service Ads Management',
						'highlighted_text'          => 'That Puts You at the Top.',
						'description'               => 'Want your business above every other result on Google, with a verified badge next to your name? Our local service ads management covers setup, Google Guaranteed verification, reviews, budget and lead disputes, so you pay for real leads and see which ones become jobs.',
						'primary_button_text'       => 'Get My Free LSA Audit',
						'primary_button_url'        => '#contact',
						'secondary_button_text'     => 'See how it works',
						'secondary_button_url'      => '#approach',
						'search_label'              => 'When locals search for your service, you show first',
						'search_result_ad_label'    => 'Sponsored',
						'search_result_url'         => 'Google Guaranteed',
						'search_result_title'       => 'Your Business · Open now · Serves your area',
						'search_result_description' => 'Call, message or request a booking straight from the top of Google.',
						'dashboard_title'           => 'Why Business Owners Trust Us',
						'chart_label'               => '',
						'live_label'                => 'SINCE 2018',
						'active'                    => true,
					),
					SLN_PPC_HERO_SEARCH_QUERIES_META => sln_ppc_subpage_rows(
						array(
							array( 'query' => 'house cleaning near me' ),
							array( 'query' => 'kitchen remodeler near me' ),
							array( 'query' => 'handyman near me' ),
							array( 'query' => 'solar installer near me' ),
							array( 'query' => 'carpet cleaning service' ),
						)
					),
					SLN_PPC_HERO_METRICS_META => sln_ppc_subpage_rows(
						array(
							array( 'display_value' => 'Google Partner', 'label' => 'Part of Google\'s Partners program', 'visual_style' => 'azure' ),
							array( 'display_value' => 'CRO Focused', 'label' => 'Leads followed through to booked jobs', 'visual_style' => 'orange' ),
							array( 'display_value' => 'Every Lead Tracked', 'label' => 'Calls and messages in Lead Analytics', 'visual_style' => 'up' ),
							array( 'display_value' => 'Your Account', 'label' => 'In your name. No passwords asked', 'visual_style' => 'azure' ),
						)
					),
					SLN_PPC_KEYWORD_MARQUEE_META => sln_ppc_subpage_rows(
						array(
							array( 'keyword' => 'house cleaning near me', 'icon_text' => '⌕' ),
							array( 'keyword' => 'kitchen remodeler near me', 'icon_text' => '⌕' ),
							array( 'keyword' => 'handyman near me', 'icon_text' => '⌕' ),
							array( 'keyword' => 'solar installer near me', 'icon_text' => '⌕' ),
							array( 'keyword' => 'deep cleaning service', 'icon_text' => '⌕' ),
							array( 'keyword' => 'bathroom remodel contractor', 'icon_text' => '⌕' ),
							array( 'keyword' => 'move out cleaning', 'icon_text' => '⌕' ),
							array( 'keyword' => 'home renovation contractor', 'icon_text' => '⌕' ),
						)
					),

					SLN_PPC_REALITY_SECTION_META => array(
						'small_heading'    => 'The Problem',
						'main_heading'     => 'Why Your Local Service Ads',
						'highlighted_text' => 'Aren\'t Bringing Booked Jobs.',
						'description'      => 'Local Service Ads can be the best leads you get from Google. They can also go quiet, or fill up with leads you never wanted.',
						'bottom_note'      => 'If any of these sound familiar, <b>your profile needs management, not just a bigger budget.</b>',
						'active'           => true,
					),
					SLN_PPC_REALITY_CHALLENGES_META => sln_ppc_subpage_rows(
						array(
							array( 'icon_text' => 'shield-check', 'title' => 'Competitors Show Above You', 'description' => 'Other businesses sit at the very top with a badge, and the calls go to them first.', 'impact' => 'MISSED CALLS' ),
							array( 'icon_text' => 'clipboard-check', 'title' => 'Stuck in Verification', 'description' => 'Background checks, licenses and insurance documents stall, and your profile never goes live.', 'impact' => 'NO VISIBILITY' ),
							array( 'icon_text' => 'phone-off', 'title' => 'Paying for Junk Leads', 'description' => 'Wrong services, wrong areas and spam calls, all charged as leads.', 'impact' => 'WASTED SPEND' ),
							array( 'icon_text' => 'users', 'title' => 'Too Few Reviews to Rank', 'description' => 'Ranking leans on reviews, so a thin profile slides down the list.', 'impact' => 'LOW RANKING' ),
							array( 'icon_text' => 'message', 'title' => 'Slow to Respond', 'description' => 'Missed calls and late replies hurt your ranking and lose the job.', 'impact' => 'LOST JOBS' ),
							array( 'icon_text' => 'eye-off', 'title' => 'No Idea Which Leads Paid Off', 'description' => 'You see leads in the app but not which ones became booked jobs.', 'impact' => 'ZERO CLARITY' ),
						)
					),

					SLN_PPC_APPROACH_SECTION_META => array(
						'small_heading'    => 'Our Approach',
						'main_heading'     => 'How We Optimize Google',
						'highlighted_text' => 'Local Service Ads.',
						'description'      => 'Our Google local service ads management focuses on the things that decide your position and your lead quality. That\'s how to generate leads in Google Local Services Ads that turn into jobs.',
						'active'           => true,
					),
					SLN_PPC_APPROACH_ITEMS_META => sln_ppc_subpage_rows(
						array(
							array( 'problem' => 'Stuck in Google\'s verification', 'solution' => 'We check that your category and service area are eligible, then guide you through Google\'s <b>background, license and insurance checks</b> so your profile can go live.' ),
							array( 'problem' => 'A profile that shows for the wrong jobs', 'solution' => 'We build a <b>complete profile</b>: job types, service areas, business hours, photos and a clear bio, so you show for the jobs you want.' ),
							array( 'problem' => 'Sliding down the list', 'solution' => 'Ranking depends heavily on <b>review score, review count and responsiveness</b>. We set up a simple routine to ask happy customers for reviews.' ),
							array( 'problem' => 'Budget that runs out or sits unused', 'solution' => 'We set a <b>weekly budget</b> and bidding mode that match how many jobs you can take, and adjust when you\'re fully booked.' ),
							array( 'problem' => 'Paying for leads you can\'t use', 'solution' => 'We review every lead and <b>dispute invalid ones</b>: wrong service, outside your area or spam. Google decides on each dispute.' ),
							array( 'problem' => 'Leads you can\'t follow up', 'solution' => 'Every call, message and booking goes into <b>Lead Analytics</b> next to your other channels, so you see cost per lead and which leads became jobs.' ),
						)
					),

					SLN_PPC_TRUTH_SECTION_META => array(
						'statement'        => 'What Are Local',
						'highlighted_text' => 'Service Ads?',
						'body'             => 'Local Service Ads are Google ads for local service businesses that appear at the very top of search results, above regular ads. Google calls them Google Local Services Ads. You pay per lead, such as a call, message or booking request, not per click, and verified businesses show a Google Guaranteed or Google Screened badge.<br><br>Your position depends on how close you are to the customer, your reviews, how quickly you respond and your business hours, not only your budget.',
						'quote'            => 'Pay for leads.',
						'quote_highlight'  => 'Not for clicks.',
						'attribution'      => '',
						'button_text'      => 'Get My Free LSA Audit',
						'button_url'       => '#contact',
						'active'           => true,
					),

					SLN_PPC_WHY_SECTION_META => array(
						'small_heading'    => 'Why SLS',
						'main_heading'     => 'A Local Service Ads Agency',
						'highlighted_text' => 'That Tracks Every Lead.',
						'description'      => 'Smart Leading Solutions LLC has worked with business owners in the USA and Australia since 2018. Here\'s how we manage Local Service Ads differently.',
						'left_heading'     => 'Typical Agency',
						'right_heading'    => 'Smart Leading Solutions',
						'active'           => true,
					),
					SLN_PPC_WHY_COMPARISON_META => sln_ppc_subpage_rows(
						array(
							array( 'typical' => 'Profile set up under the agency', 'sls' => 'Your Local Services account in <b>your business name</b>. We\'re added as a manager, never as owner' ),
							array( 'typical' => 'Asks for your login details', 'sls' => 'We <b>never ask for your password</b>, and you can remove our access any time' ),
							array( 'typical' => 'Reports leads charged, nothing more', 'sls' => 'Reports <b>leads, disputes and cost per lead</b> next to your other channels' ),
							array( 'typical' => 'Lets junk leads slide', 'sls' => 'Every lead <b>reviewed</b>, and invalid ones disputed' ),
							array( 'typical' => 'Hides what isn\'t working', 'sls' => '<b>Real numbers, even when they\'re bad.</b> If something isn\'t working, we tell you and change it' ),
						)
					),
					SLN_PPC_WHY_BADGES_META => sln_ppc_subpage_rows(
						array(
							array( 'text' => 'Google Partner' ),
							array( 'text' => 'Your account, your data' ),
							array( 'text' => 'Every lead tracked' ),
							array( 'text' => 'Since 2018' ),
						)
					),

					SLN_PPC_SERVICES_SECTION_META => array(
						'small_heading'    => 'What\'s Included',
						'main_heading'     => 'What\'s Included When We',
						'highlighted_text' => 'Run Your Local Service Ads.',
						'description'      => 'Everything your Local Services profile needs, from first setup to monthly lead reports. Part of our PPC management services.',
						'active'           => true,
					),
					SLN_PPC_SERVICES_ITEMS_META => sln_ppc_subpage_rows(
						array(
							array( 'icon_key' => 'lsa', 'icon_text' => '●', 'icon_style' => 'blue', 'tag' => 'ELIGIBILITY', 'title' => 'Eligibility and Verification', 'description' => 'Category and area check, then help through Google\'s screening steps.' ),
							array( 'icon_key' => 'profile', 'icon_text' => '⌂', 'icon_style' => 'blue', 'tag' => 'PROFILE', 'title' => 'Profile Setup', 'description' => 'Business details, bio, photos and hours that build trust.' ),
							array( 'icon_key' => 'search', 'icon_text' => '⌕', 'icon_style' => 'blue', 'tag' => 'TARGETING', 'title' => 'Job Types and Service Areas', 'description' => 'Only the jobs you want, only where you work.' ),
							array( 'icon_key' => 'ai', 'icon_text' => '✦', 'icon_style' => 'blue', 'tag' => 'REVIEWS', 'title' => 'Review Strategy', 'description' => 'A simple routine to get more reviews from happy customers.' ),
							array( 'icon_key' => 'data', 'icon_text' => '▥', 'icon_style' => 'blue', 'tag' => 'BUDGET', 'title' => 'Budget and Bidding', 'description' => 'Weekly budget and bidding mode matched to your capacity.' ),
							array( 'icon_key' => 'retarget', 'icon_text' => '↺', 'icon_style' => 'blue', 'tag' => 'DISPUTES', 'title' => 'Lead Review and Disputes', 'description' => 'Invalid leads disputed so you don\'t pay for them.' ),
							array( 'icon_key' => 'data', 'icon_text' => '▥', 'icon_style' => 'blue', 'tag' => 'TRACKING', 'title' => 'Call and Lead Tracking', 'description' => 'Calls, messages and bookings in Lead Analytics.' ),
							array( 'icon_key' => 'search', 'icon_text' => '⌕', 'icon_style' => 'blue', 'tag' => 'SEARCH', 'title' => 'Google Ads Alongside', 'description' => 'Search campaigns for services LSA doesn\'t cover.', 'url' => '' ),
						)
					),

					SLN_PPC_PROCESS_SECTION_META => array(
						'small_heading'    => 'How It Starts',
						'main_heading'     => 'From First Call to',
						'highlighted_text' => 'Your First Leads.',
						'description'      => 'Starting with us is simple. Here\'s what happens after you get in touch.',
						'bottom_note'      => 'You always know what\'s running, what it costs and which leads it brought in.',
						'active'           => true,
					),
					SLN_PPC_PROCESS_STEPS_META => sln_ppc_subpage_rows(
						array(
							array( 'number' => '01', 'title' => 'Discovery Call', 'bullets' => array( 'Your services and areas', 'Jobs you want more of', 'Weekly capacity and budget' ) ),
							array( 'number' => '02', 'title' => 'Eligibility Check', 'bullets' => array( 'Category and area check', 'Current profile review', 'Documents you\'ll need' ) ),
							array( 'number' => '03', 'title' => 'Setup and Launch', 'bullets' => array( 'Verification support', 'Profile and job types', 'Tracking connected' ) ),
							array( 'number' => '04', 'title' => 'Optimize and Report', 'bullets' => array( 'Reviews and responsiveness', 'Lead disputes', 'Leads and cost per lead' ) ),
						)
					),

					SLN_PPC_MID_CTA_META => array(
						'heading'     => 'Is Your Local Service Ads Profile Losing Leads?',
						'description' => 'Get a free review of your Local Services profile. We\'ll show you what\'s holding your ranking back and what we\'d change first.',
						'button_text' => 'Get My Free LSA Audit',
						'button_url'  => '#contact',
						'active'      => true,
					),

					SLN_PPC_INDUSTRIES_SECTION_META => array(
						'small_heading'    => 'Industries',
						'main_heading'     => 'Local Service Ads Categories',
						'highlighted_text' => 'We Work With.',
						'description'      => 'Local Services Ads are available for specific business categories, and availability varies by location. These are the businesses we work with:',
						'active'           => true,
					),
					SLN_PPC_INDUSTRIES_ITEMS_META => sln_ppc_subpage_rows(
						array(
							array( 'icon_text' => 'sparkles', 'title' => 'Cleaning', 'description' => 'House, deep and move-out cleaning leads from people nearby.' ),
							array( 'icon_text' => 'hammer', 'title' => 'Home Improvement', 'description' => 'Calls from homeowners planning repairs and renovations.' ),
							array( 'icon_text' => 'chef-hat', 'title' => 'Kitchen Businesses', 'description' => 'Remodeling enquiries from homeowners in your service area.' ),
							array( 'icon_text' => 'zap', 'title' => 'Energy', 'description' => 'Enquiries from homeowners comparing energy installers, where eligible.' ),
							array( 'icon_text' => 'tooth', 'title' => 'Dentistry', 'description' => 'Patient calls in areas where Google offers this category.' ),
							array( 'icon_text' => 'search', 'title' => 'Not Sure You\'re Eligible?', 'description' => 'We\'ll check your category and area in a free audit.' ),
						)
					),

					SLN_PPC_FAQ_SECTION_META => array(
						'small_heading'    => 'Common Questions',
						'main_heading'     => 'Local Service Ads',
						'highlighted_text' => 'FAQs.',
						'description'      => 'Straight answers about Google Local Service Ads and how we manage them.',
						'active'           => true,
					),
					SLN_PPC_FAQ_ITEMS_META => sln_ppc_subpage_rows(
						array(
							array( 'question' => 'What are local service ads?', 'answer' => 'Local Service Ads (Google Local Services Ads) are pay-per-lead ads for local service businesses. They appear at the very top of Google search results, show a Google Guaranteed or Google Screened badge, and let customers call, message or request a booking directly.' ),
							array( 'question' => 'How much do Google local service ads cost?', 'answer' => 'You pay Google per lead, not per click. The cost per lead depends on your category, your area and competition. Our management fee is separate from what you pay Google.' ),
							array( 'question' => 'What is the difference between Google Guaranteed and Google Screened?', 'answer' => 'Google Guaranteed is the badge for home service businesses, such as cleaners and contractors. Google Screened is for professional services, such as legal, financial or real estate businesses. Both require passing Google\'s screening.' ),
							array( 'question' => 'How do I get Google Guaranteed?', 'answer' => 'Apply through a Local Services Ads account in an eligible category and area, then pass Google\'s checks, which can include a background check and license and insurance verification. We guide you through each step; Google makes the final decision.' ),
							array( 'question' => 'Where do local service ads appear?', 'answer' => 'At the top of Google search results, above regular Google Ads, mainly for searches like "service near me". They can also appear in Google Maps.' ),
							array( 'question' => 'Can I dispute a bad lead?', 'answer' => 'Yes. Leads that are outside your service area, for a service you don\'t offer, or spam can be disputed in your account. Google reviews each dispute and decides whether to credit it. We review and dispute leads for you.' ),
							array( 'question' => 'Are local service ads worth it?', 'answer' => 'For many local service businesses, yes, because you pay only for leads and appear above everything else. They work best when your profile is complete, you have reviews, and you answer calls quickly.' ),
							array( 'question' => 'Should I run Local Service Ads or Google Ads?', 'answer' => 'Local Service Ads bring calls at the top of Google at a cost per lead. Google Ads gives you more control over keywords, ads and landing pages, and covers services LSA doesn\'t. Many businesses run both, tracked together.' ),
						)
					),

					SLN_PPC_FINAL_CTA_META => array(
						'small_heading'         => 'Your Next Step',
						'main_heading'          => 'Get Local Service Ads Management',
						'highlighted_text'      => 'That Brings Booked Jobs.',
						'description'           => 'Tell us about your business and share your website. We\'ll check your eligibility and review your Local Services profile.',
						'primary_button_text'   => 'Get My Free LSA Audit',
						'primary_button_url'    => home_url( '/ppc/free-ppc-audit/' ),
						'secondary_button_text' => 'See how it works',
						'secondary_button_url'  => '#approach',
						'website_label'         => '',
						'website_text'          => '',
						'website_url'           => '',
						'bottom_note'           => 'Your account stays yours. No passwords needed.',
						'active'                => true,
					),
				)
			),
		),
		array(
			'slug'  => 'facebook-ads-management',
			'title' => 'Facebook & Instagram Ads Management',
			'meta'  => array_merge(
				sln_ppc_subpage_hidden_sections(),
				array(
					'_sln_seo_title'       => 'Facebook Ads Agency for More Leads | Smart Leading',
					'_sln_focus_keyword'   => 'facebook ads agency',
					'_sln_seo_description' => 'Facebook ads agency for Facebook and Instagram: offers, creative, lead forms and tracking that turn scrolls into leads. Get a free Meta ads audit.',
					'_sln_service_type'    => 'Facebook and Instagram ads management',

					SLN_PPC_HERO_META => array(
						'small_heading'             => '',
						'main_heading'              => 'Facebook Ads Agency That Turns',
						'highlighted_text'          => 'Scrolls Into Leads.',
						'description'               => 'Boosting posts brings likes, not customers. As a Facebook ads agency, we plan the offer and the audience, our design team creates the ads, and we track every Facebook and Instagram lead in Lead Analytics, so you see what each enquiry costs.',
						'primary_button_text'       => 'Get My Free Meta Ads Audit',
						'primary_button_url'        => '#contact',
						'secondary_button_text'     => 'See how it works',
						'secondary_button_url'      => '#approach',
						'search_label'              => 'Reach people before they even search',
						'search_result_ad_label'    => 'Sponsored',
						'search_result_url'         => 'Facebook & Instagram',
						'search_result_title'       => 'Free design consultation this month',
						'search_result_description' => 'See real kitchen projects near you. Tap to book a consultation.',
						'dashboard_title'           => 'Why Business Owners Trust Us',
						'chart_label'               => '',
						'live_label'                => 'SINCE 2018',
						'active'                    => true,
					),
					SLN_PPC_HERO_SEARCH_QUERIES_META => sln_ppc_subpage_rows(
						array(
							array( 'query' => 'homeowners planning a kitchen remodel' ),
							array( 'query' => 'families near your cleaning service' ),
							array( 'query' => 'adults looking for a new dentist' ),
							array( 'query' => 'busy people trying meal delivery' ),
							array( 'query' => 'homeowners comparing solar' ),
						)
					),
					SLN_PPC_HERO_METRICS_META => sln_ppc_subpage_rows(
						array(
							array( 'display_value' => 'In-House Creative Team', 'label' => 'Designers for ads, video and Reels', 'visual_style' => 'azure' ),
							array( 'display_value' => 'Pixel + Conversions API', 'label' => 'Meta tracking set up for you', 'visual_style' => 'orange' ),
							array( 'display_value' => 'Every Lead Tracked', 'label' => 'Meta lead forms in Lead Analytics', 'visual_style' => 'up' ),
							array( 'display_value' => 'Your Ad Account', 'label' => 'In your name. No passwords asked', 'visual_style' => 'azure' ),
						)
					),
					SLN_PPC_KEYWORD_MARQUEE_META => sln_ppc_subpage_rows(
						array(
							array( 'keyword' => 'lead ads', 'icon_text' => '⌕' ),
							array( 'keyword' => 'instagram reels ads', 'icon_text' => '⌕' ),
							array( 'keyword' => 'lookalike audiences', 'icon_text' => '⌕' ),
							array( 'keyword' => 'retargeting', 'icon_text' => '⌕' ),
							array( 'keyword' => 'carousel ads', 'icon_text' => '⌕' ),
							array( 'keyword' => 'video ads', 'icon_text' => '⌕' ),
							array( 'keyword' => 'meta pixel', 'icon_text' => '⌕' ),
							array( 'keyword' => 'instant forms', 'icon_text' => '⌕' ),
						)
					),

					SLN_PPC_REALITY_SECTION_META => array(
						'small_heading'    => 'The Problem',
						'main_heading'     => 'Why Boosting Posts',
						'highlighted_text' => 'Isn\'t Bringing Customers.',
						'description'      => 'Facebook and Instagram can fill your calendar. Without a plan, they mostly fill your notifications.',
						'bottom_note'      => 'If any of these sound familiar, <b>your ads need a system, not another boost.</b>',
						'active'           => true,
					),
					SLN_PPC_REALITY_CHALLENGES_META => sln_ppc_subpage_rows(
						array(
							array( 'icon_text' => 'eye', 'title' => 'Likes, Not Leads', 'description' => 'Boosted posts get reactions from people who were never going to buy.', 'impact' => 'NO LEADS' ),
							array( 'icon_text' => 'file-question', 'title' => 'Fake or Junk Form Leads', 'description' => 'Forms fill up with wrong numbers and people who don\'t remember filling them in.', 'impact' => 'WASTED TIME' ),
							array( 'icon_text' => 'users', 'title' => 'The Wrong Audience', 'description' => 'Ads reach people outside your area or nowhere near ready to buy.', 'impact' => 'WASTED SPEND' ),
							array( 'icon_text' => 'refresh', 'title' => 'Ads Wear Out Fast', 'description' => 'The same image for months, so costs climb and results drop.', 'impact' => 'AD FATIGUE' ),
							array( 'icon_text' => 'eye-off', 'title' => 'Tracking That Doesn\'t Work', 'description' => 'Without the Pixel and conversion events, Meta optimizes for the wrong people.', 'impact' => 'ZERO CLARITY' ),
							array( 'icon_text' => 'trending-up', 'title' => 'Costs Keep Rising', 'description' => 'Spend goes up every month with no clear idea of what a customer costs.', 'impact' => 'RISING COSTS' ),
						)
					),

					SLN_PPC_APPROACH_SECTION_META => array(
						'small_heading'    => 'Our Approach',
						'main_heading'     => 'How We Run Facebook',
						'highlighted_text' => 'and Instagram Ads.',
						'description'      => 'Our Facebook ads management starts with an offer worth clicking and ends with leads you can call back, tracked from the first view to the booked job.',
						'active'           => true,
					),
					SLN_PPC_APPROACH_ITEMS_META => sln_ppc_subpage_rows(
						array(
							array( 'problem' => 'Ads with nothing to say', 'solution' => 'We start with <b>the offer and the audience</b>: who you want, where they are and what will make them stop scrolling.' ),
							array( 'problem' => 'One tired image for months', 'solution' => 'Our <b>in-house design team creates your ads</b> (images, video, carousels, Reels and Stories) and keeps fresh variations in rotation.' ),
							array( 'problem' => 'Forms full of junk', 'solution' => 'We build <b>lead forms with qualifying questions</b>, or send people to a landing page, so the leads you get are worth calling.' ),
							array( 'problem' => 'Meta learning from the wrong data', 'solution' => 'We set up your <b>Meta Pixel and Conversions API</b>, so Meta gets reliable lead data and optimizes for leads, not clicks.' ),
							array( 'problem' => 'Guessing what works', 'solution' => 'We <b>test audiences, creative and offers</b> against each other and move budget to the winners.' ),
							array( 'problem' => 'Visitors who never come back', 'solution' => 'We <b>retarget</b> people who visited your site or watched your videos, with a message that matches what they saw.' ),
						)
					),

					SLN_PPC_TRUTH_SECTION_META => array(
						'statement'        => 'How Do Facebook',
						'highlighted_text' => 'Ads Work?',
						'body'             => 'Facebook ads are paid ads that Meta shows on Facebook and Instagram, in feeds, Stories, Reels and Messenger. You choose who sees them (by location, interests, behavior or people similar to your customers), set a budget, and Meta shows your ads to the people most likely to take the action you\'re optimizing for.<br><br>Unlike Google search, people aren\'t looking for you yet. That\'s why the offer and the creative matter as much as the targeting.',
						'quote'            => 'Stop the scroll.',
						'quote_highlight'  => 'Start the conversation.',
						'attribution'      => '',
						'button_text'      => 'Get My Free Meta Ads Audit',
						'button_url'       => '#contact',
						'active'           => true,
					),

					SLN_PPC_WHY_SECTION_META => array(
						'small_heading'    => 'Why SLS',
						'main_heading'     => 'A Facebook Ad Agency That',
						'highlighted_text' => 'Reports Leads, Not Likes.',
						'description'      => 'Smart Leading Solutions LLC has worked with business owners in the USA and Australia since 2018. Here\'s how we run Meta ads differently.',
						'left_heading'     => 'Typical Agency',
						'right_heading'    => 'Smart Leading Solutions',
						'active'           => true,
					),
					SLN_PPC_WHY_COMPARISON_META => sln_ppc_subpage_rows(
						array(
							array( 'typical' => 'Ad account set up under the agency', 'sls' => 'Ad account and Business Manager in <b>your business name</b>. We\'re added as a partner, never as owner' ),
							array( 'typical' => 'Asks for your Facebook login', 'sls' => 'We <b>never ask for your password</b>, and you can remove our access any time' ),
							array( 'typical' => 'Reports reach, likes and clicks', 'sls' => 'Reports <b>leads and cost per lead</b>, with Meta leads in Lead Analytics' ),
							array( 'typical' => 'Same ads for months', 'sls' => 'Creative and audiences <b>tested and refreshed</b> continually' ),
							array( 'typical' => 'Hides what isn\'t working', 'sls' => '<b>Real numbers, even when they\'re bad.</b> If something isn\'t working, we tell you and change it' ),
						)
					),
					SLN_PPC_WHY_BADGES_META => sln_ppc_subpage_rows(
						array(
							array( 'text' => 'Google Partner' ),
							array( 'text' => 'Your account, your data' ),
							array( 'text' => 'Every lead tracked' ),
							array( 'text' => 'Since 2018' ),
						)
					),

					SLN_PPC_SERVICES_SECTION_META => array(
						'small_heading'    => 'What\'s Included',
						'main_heading'     => 'What\'s Included in Our Facebook',
						'highlighted_text' => 'Ads Management Services.',
						'description'      => 'Our Facebook ad management services cover Facebook and Instagram from strategy to lead reporting. Part of our PPC management services.',
						'active'           => true,
					),
					SLN_PPC_SERVICES_ITEMS_META => sln_ppc_subpage_rows(
						array(
							array( 'icon_key' => 'data', 'icon_text' => '▥', 'icon_style' => 'blue', 'tag' => 'STRATEGY', 'title' => 'Offer and Audience Strategy', 'description' => 'Who to reach, where, and what will make them act.' ),
							array( 'icon_key' => 'ai', 'icon_text' => '✦', 'icon_style' => 'blue', 'tag' => 'CREATIVE', 'title' => 'Ad Creative by Our Design Team', 'description' => 'Images, video, carousels and Reels made in-house, with ad copy.' ),
							array( 'icon_key' => 'search', 'icon_text' => '⌕', 'icon_style' => 'blue', 'tag' => 'TARGETING', 'title' => 'Audience Targeting', 'description' => 'Location, interests, custom and lookalike audiences.' ),
							array( 'icon_key' => 'cro', 'icon_text' => '▤', 'icon_style' => 'blue', 'tag' => 'LEAD FORMS', 'title' => 'Lead Forms and Landing Pages', 'description' => 'Qualifying questions so leads are worth calling.' ),
							array( 'icon_key' => 'data', 'icon_text' => '▥', 'icon_style' => 'blue', 'tag' => 'TRACKING', 'title' => 'Meta Pixel and Conversions API', 'description' => 'Set up so Meta optimizes for real leads.' ),
							array( 'icon_key' => 'pmax', 'icon_text' => '▣', 'icon_style' => 'blue', 'tag' => 'TESTING', 'title' => 'A/B Testing', 'description' => 'Audiences, creative and offers tested against each other.' ),
							array( 'icon_key' => 'retarget', 'icon_text' => '↺', 'icon_style' => 'blue', 'tag' => 'RETARGETING', 'title' => 'Retargeting', 'description' => 'Win back site visitors and video viewers.' ),
							array( 'icon_key' => 'data', 'icon_text' => '▥', 'icon_style' => 'blue', 'tag' => 'REPORTING', 'title' => 'Lead Reporting', 'description' => 'Meta leads and cost per lead in Lead Analytics.' ),
						)
					),

					SLN_PPC_PROCESS_SECTION_META => array(
						'small_heading'    => 'How It Starts',
						'main_heading'     => 'How Our Facebook Ads',
						'highlighted_text' => 'Management Agency Gets Started.',
						'description'      => 'Starting with us is simple. Here\'s what happens after you get in touch.',
						'bottom_note'      => 'You always know what\'s running, what it costs and which leads it brought in.',
						'active'           => true,
					),
					SLN_PPC_PROCESS_STEPS_META => sln_ppc_subpage_rows(
						array(
							array( 'number' => '01', 'title' => 'Discovery Call', 'bullets' => array( 'Your offer and customers', 'Areas you serve', 'Budget and goals' ) ),
							array( 'number' => '02', 'title' => 'Account Audit', 'bullets' => array( 'Current ads and audiences', 'Pixel and tracking check', 'Quick wins' ) ),
							array( 'number' => '03', 'title' => 'Build and Launch', 'bullets' => array( 'Ads designed in-house', 'Pixel, CAPI and lead forms', 'Launch with your approval' ) ),
							array( 'number' => '04', 'title' => 'Test and Report', 'bullets' => array( 'Creative and audience tests', 'Budget to the winners', 'Leads and cost per lead' ) ),
						)
					),

					SLN_PPC_MID_CTA_META => array(
						'heading'     => 'Spending on Facebook Ads Without Getting Leads?',
						'description' => 'Get a free review of your Meta ads account. We\'ll show you what\'s working, what\'s wasting money and what we\'d test first.',
						'button_text' => 'Get My Free Meta Ads Audit',
						'button_url'  => '#contact',
						'active'      => true,
					),

					SLN_PPC_INDUSTRIES_SECTION_META => array(
						'small_heading'    => 'Industries',
						'main_heading'     => 'Your Home Services',
						'highlighted_text' => 'Facebook Ads Agency.',
						'description'      => 'Facebook and Instagram work best when people can picture the result. These are businesses we work with:',
						'active'           => true,
					),
					SLN_PPC_INDUSTRIES_ITEMS_META => sln_ppc_subpage_rows(
						array(
							array( 'icon_text' => 'chef-hat', 'title' => 'Kitchen Businesses', 'description' => 'Before-and-after projects that start design consultations.' ),
							array( 'icon_text' => 'hammer', 'title' => 'Home Improvement', 'description' => 'Local homeowners planning their next project.' ),
							array( 'icon_text' => 'sparkles', 'title' => 'Cleaning', 'description' => 'First-clean offers that turn into recurring customers.' ),
							array( 'icon_text' => 'tooth', 'title' => 'Dentistry', 'description' => 'New-patient offers for people nearby.' ),
							array( 'icon_text' => 'utensils', 'title' => 'Meal Delivery', 'description' => 'Trial offers and sign-ups from the right audience.' ),
							array( 'icon_text' => 'zap', 'title' => 'Energy', 'description' => 'Homeowners comparing energy options in your area.' ),
						)
					),

					SLN_PPC_FAQ_SECTION_META => array(
						'small_heading'    => 'Common Questions',
						'main_heading'     => 'Facebook Ads',
						'highlighted_text' => 'FAQs.',
						'description'      => 'Straight answers about Facebook and Instagram ads and how we manage them.',
						'active'           => true,
					),
					SLN_PPC_FAQ_ITEMS_META => sln_ppc_subpage_rows(
						array(
							array( 'question' => 'How much do Facebook ads cost?', 'answer' => 'You set the budget. What you pay per lead depends on your offer, audience, area and creative. Your ad spend goes to Meta; our management fee is separate and depends on how much we manage.' ),
							array( 'question' => 'How do Facebook ads work?', 'answer' => 'Meta shows your ads on Facebook and Instagram to the audience you choose, and optimizes delivery toward the action you want, such as a lead form or website visit. You pay based on how your ads are delivered, within your budget.' ),
							array( 'question' => 'Can I run Facebook ads myself?', 'answer' => 'Yes, and some businesses do. The hard parts are the offer, fresh creative, lead quality and tracking. An agency brings a testing process and the time to check results every week.' ),
							array( 'question' => 'Do you create the ads?', 'answer' => 'Yes. Our in-house design team creates the ad creative (images, video, carousels and Reels) and we write the ad copy, so you don\'t need to supply finished ads.' ),
							array( 'question' => 'Do you run Instagram ads too?', 'answer' => 'Yes. Facebook and Instagram ads run from the same Meta account, so we plan both together and use the placements (Feed, Stories, Reels) that work best for your audience.' ),
							array( 'question' => 'How do you improve lead quality?', 'answer' => 'We add qualifying questions to lead forms, match the offer to the right audience, check that tracking reports real leads, and review leads in Lead Analytics so we can cut what doesn\'t work.' ),
							array( 'question' => 'How do I choose the best Facebook ads agency?', 'answer' => 'Ask who owns the ad account, how leads are tracked and what the reports show. The right agency reports leads and cost per lead, tests creative regularly and keeps your account in your business name.' ),
							array( 'question' => 'How long do Facebook ads take to work?', 'answer' => 'Ads can start showing soon after launch. Finding the offers, audiences and creative that bring steady, affordable leads takes ongoing testing.' ),
							array( 'question' => 'Should I use Facebook Ads or Google Ads?', 'answer' => 'Google Ads reaches people already searching for your service. Facebook and Instagram reach people before they search. Many businesses use both: Google for ready-to-buy demand, Meta to build it.' ),
						)
					),

					SLN_PPC_FINAL_CTA_META => array(
						'small_heading'         => 'Your Next Step',
						'main_heading'          => 'Work With a Facebook Ads Agency',
						'highlighted_text'      => 'That Shows You the Leads.',
						'description'           => 'Tell us about your business and share your website. We\'ll review your Meta ads and show you what we\'d change first.',
						'primary_button_text'   => 'Get My Free Meta Ads Audit',
						'primary_button_url'    => home_url( '/ppc/free-ppc-audit/' ),
						'secondary_button_text' => 'See how it works',
						'secondary_button_url'  => '#approach',
						'website_label'         => '',
						'website_text'          => '',
						'website_url'           => '',
						'bottom_note'           => 'Your accounts stay yours. No passwords needed.',
						'active'                => true,
					),
				)
			),
		),
		array(
			'slug'  => 'free-ppc-audit',
			'title' => 'Free PPC Audit',
			'meta'  => array_merge(
				sln_ppc_subpage_hidden_sections(),
				array(
					'_sln_seo_title'       => 'Free PPC Audit: Google Ads, LSA & Meta | Smart Leading',
					'_sln_focus_keyword'   => 'ppc audit',
					'_sln_seo_description' => 'Get a free PPC audit of your Google Ads, Local Service Ads or Meta account: PDF report, video walkthrough and a call within 2–3 business days.',
					'_sln_service_type'    => 'PPC audit',

					SLN_PPC_HERO_META => array(
						'small_heading'             => '',
						'main_heading'              => 'Free PPC Audit:',
						'highlighted_text'          => 'Find Where Your Ad Budget Is Leaking.',
						'description'               => 'Get a free PPC audit of your Google Ads, Local Service Ads or Meta account. Our PPC audit services show what\'s wasting money, what\'s working and what we\'d fix first, delivered as a PDF report, a video walkthrough and a call within 2–3 business days.',
						'primary_button_text'       => 'Request My Free Audit',
						'primary_button_url'        => '#contact',
						'secondary_button_text'     => 'See what we check',
						'secondary_button_url'      => '#approach',
						'search_label'              => 'Sound like your account?',
						'search_result_ad_label'    => 'Free',
						'search_result_url'         => 'smartleading.net/ppc/free-ppc-audit',
						'search_result_title'       => 'Free PPC Audit | PDF Report + Video + Call',
						'search_result_description' => 'Google Ads, Local Service Ads and Meta. Read-only access. Delivered in 2–3 business days.',
						'dashboard_title'           => 'What You Get, Free',
						'chart_label'               => '',
						'live_label'                => 'NO OBLIGATION',
						'active'                    => true,
					),
					SLN_PPC_HERO_SEARCH_QUERIES_META => sln_ppc_subpage_rows(
						array(
							array( 'query' => 'why are my google ads not working' ),
							array( 'query' => 'facebook ads not getting leads' ),
							array( 'query' => 'local service ads not getting calls' ),
							array( 'query' => 'how to lower cost per lead' ),
							array( 'query' => 'is my ppc agency doing a good job' ),
						)
					),
					SLN_PPC_HERO_METRICS_META => sln_ppc_subpage_rows(
						array(
							array( 'display_value' => 'PDF + Video + Call', 'label' => 'Findings you can act on', 'visual_style' => 'azure' ),
							array( 'display_value' => '2–3 Business Days', 'label' => 'From access to report', 'visual_style' => 'orange' ),
							array( 'display_value' => 'Google, LSA & Meta', 'label' => 'All your paid channels', 'visual_style' => 'up' ),
							array( 'display_value' => 'Read-Only Access', 'label' => 'Never your password', 'visual_style' => 'azure' ),
						)
					),
					SLN_PPC_KEYWORD_MARQUEE_META => sln_ppc_subpage_rows(
						array(
							array( 'keyword' => 'wasted spend', 'icon_text' => '⌕' ),
							array( 'keyword' => 'search terms', 'icon_text' => '⌕' ),
							array( 'keyword' => 'negative keywords', 'icon_text' => '⌕' ),
							array( 'keyword' => 'conversion tracking', 'icon_text' => '⌕' ),
							array( 'keyword' => 'quality score', 'icon_text' => '⌕' ),
							array( 'keyword' => 'meta pixel', 'icon_text' => '⌕' ),
							array( 'keyword' => 'lead disputes', 'icon_text' => '⌕' ),
							array( 'keyword' => 'cost per lead', 'icon_text' => '⌕' ),
						)
					),

					SLN_PPC_REALITY_SECTION_META => array(
						'small_heading'    => 'Warning Signs',
						'main_heading'     => 'Signs Your PPC Account',
						'highlighted_text' => 'Needs an Audit.',
						'description'      => 'Most accounts don\'t break overnight. Budget leaks slowly, and nobody notices until the leads dry up.',
						'bottom_note'      => 'If even one of these sounds familiar, <b>a free audit will show you where the money is going.</b>',
						'active'           => true,
					),
					SLN_PPC_REALITY_CHALLENGES_META => sln_ppc_subpage_rows(
						array(
							array( 'icon_text' => 'trending-up', 'title' => 'Cost per Lead Keeps Rising', 'description' => 'You\'re paying more each month for the same number of enquiries.', 'impact' => 'RISING COSTS' ),
							array( 'icon_text' => 'phone-off', 'title' => 'Leads but No Customers', 'description' => 'Forms and calls come in, but few turn into booked jobs.', 'impact' => 'LOW QUALITY' ),
							array( 'icon_text' => 'eye-off', 'title' => 'Tracking You Don\'t Trust', 'description' => 'The platform says one thing, your phone says another.', 'impact' => 'ZERO CLARITY' ),
							array( 'icon_text' => 'file-question', 'title' => 'An Agency You Can\'t Question', 'description' => 'Reports full of impressions and no straight answers.', 'impact' => 'NO TRANSPARENCY' ),
							array( 'icon_text' => 'gauge', 'title' => 'Budget Gone by Mid-Month', 'description' => 'Spend runs out early, or lands on searches that never buy.', 'impact' => 'WASTED SPEND' ),
							array( 'icon_text' => 'refresh', 'title' => 'Nobody\'s Looked in Months', 'description' => 'Same keywords, same ads, same settings since launch.', 'impact' => 'SET AND FORGET' ),
						)
					),

					SLN_PPC_APPROACH_SECTION_META => array(
						'small_heading'    => 'What We Check',
						'main_heading'     => 'Our PPC Audit',
						'highlighted_text' => 'Checklist.',
						'description'      => 'Every PPC campaign audit covers the same six areas, so nothing important gets missed. A person reviews your account, not just a tool.',
						'active'           => true,
					),
					SLN_PPC_APPROACH_ITEMS_META => sln_ppc_subpage_rows(
						array(
							array( 'problem' => 'Where is the budget leaking?', 'solution' => 'We go through your <b>search terms, audiences and placements</b> to find spend on people who were never going to buy.' ),
							array( 'problem' => 'Can you trust the numbers?', 'solution' => 'We check <b>conversion tracking, call tracking, the Meta Pixel and Conversions API</b>, so you know whether the reported leads are real.' ),
							array( 'problem' => 'Is the account built right?', 'solution' => 'We review <b>campaign structure, keywords, match types and negatives</b>, or your Local Services job types and areas.' ),
							array( 'problem' => 'Are the ads pulling their weight?', 'solution' => 'We look at <b>ad copy, assets, creative and Quality Score</b>, and where weak ads push your costs up.' ),
							array( 'problem' => 'Do clicks turn into enquiries?', 'solution' => 'We review the <b>landing pages and lead forms</b> your ads send people to, from the visitor\'s side.' ),
							array( 'problem' => 'Is the money in the right place?', 'solution' => 'We check <b>budgets, bidding strategies and channel mix</b>, and where moving spend would bring more leads.' ),
						)
					),

					SLN_PPC_TRUTH_SECTION_META => array(
						'statement'        => 'What Is a',
						'highlighted_text' => 'PPC Audit?',
						'body'             => 'A PPC audit is a structured review of your paid ad accounts (Google Ads, Local Service Ads or Meta) to find wasted spend, tracking problems and missed opportunities. It shows what\'s working, what isn\'t, and what to change first.<br><br>Ours is done by a person, not just a tool, and you get the findings as a PDF report, a video walkthrough and a call.',
						'quote'            => 'Find the leaks.',
						'quote_highlight'  => 'Then fix them.',
						'attribution'      => '',
						'button_text'      => 'Request My Free Audit',
						'button_url'       => '#contact',
						'active'           => true,
					),

					SLN_PPC_WHY_SECTION_META => array(
						'small_heading'    => 'Why SLS',
						'main_heading'     => 'A PPC Audit Agency',
						'highlighted_text' => 'That Tells You the Truth.',
						'description'      => 'Smart Leading Solutions LLC has worked with business owners in the USA and Australia since 2018. Here\'s what makes our audit different.',
						'left_heading'     => 'Typical Agency',
						'right_heading'    => 'Smart Leading Solutions',
						'active'           => true,
					),
					SLN_PPC_WHY_COMPARISON_META => sln_ppc_subpage_rows(
						array(
							array( 'typical' => 'A sales pitch dressed up as an audit', 'sls' => '<b>Real findings</b>, even when the answer is "your account is in good shape"' ),
							array( 'typical' => 'Asks for your login details', 'sls' => '<b>Read-only access only.</b> We never ask for your password' ),
							array( 'typical' => 'An automated score from a tool', 'sls' => 'Your account <b>reviewed by a person</b>, platform by platform' ),
							array( 'typical' => 'Findings you can\'t act on', 'sls' => 'An <b>action plan</b> you can use with us, your team or another agency' ),
							array( 'typical' => 'A hard sell afterwards', 'sls' => '<b>No obligation.</b> If we\'re not the right fit, we\'ll say so' ),
						)
					),
					SLN_PPC_WHY_BADGES_META => sln_ppc_subpage_rows(
						array(
							array( 'text' => 'Google Partner' ),
							array( 'text' => 'Read-only access' ),
							array( 'text' => 'No obligation' ),
							array( 'text' => 'Since 2018' ),
						)
					),

					SLN_PPC_SERVICES_SECTION_META => array( 'active' => false ),
					SLN_PPC_INDUSTRIES_SECTION_META => array( 'active' => false ),

					SLN_PPC_PROCESS_SECTION_META => array(
						'small_heading'    => 'How It Works',
						'main_heading'     => 'Your PPC Account Audit',
						'highlighted_text' => 'in Four Steps.',
						'description'      => 'From request to action plan in 2–3 business days after we get access.',
						'bottom_note'      => 'For businesses currently spending on Google Ads, Local Service Ads or Meta Ads.',
						'active'           => true,
					),
					SLN_PPC_PROCESS_STEPS_META => sln_ppc_subpage_rows(
						array(
							array( 'number' => '01', 'title' => 'Request', 'bullets' => array( 'Fill in the short form', 'Tell us which platforms', 'Share your website' ) ),
							array( 'number' => '02', 'title' => 'Grant Access', 'bullets' => array( 'Read-only or manager access', 'We send simple steps', 'Never your password' ) ),
							array( 'number' => '03', 'title' => 'We Review', 'bullets' => array( 'Six-area checklist', 'Every platform you use', 'Done by a person' ) ),
							array( 'number' => '04', 'title' => 'Walkthrough', 'bullets' => array( 'PDF report', 'Video walkthrough', 'Call to go through it' ) ),
						)
					),

					SLN_PPC_MID_CTA_META => array(
						'heading'     => 'Not Sure Your Ads Are Working? Find Out for Free.',
						'description' => 'Request your free PPC audit and get a clear answer in 2–3 business days.',
						'button_text' => 'Request My Free Audit',
						'button_url'  => '#contact',
						'active'      => true,
					),

					SLN_PPC_FAQ_SECTION_META => array(
						'small_heading'    => 'Common Questions',
						'main_heading'     => 'PPC Audit',
						'highlighted_text' => 'FAQs.',
						'description'      => 'Everything you want to know before requesting your audit.',
						'active'           => true,
					),
					SLN_PPC_FAQ_ITEMS_META => sln_ppc_subpage_rows(
						array(
							array( 'question' => 'Is the PPC audit really free?', 'answer' => 'Yes. There\'s no cost and no obligation to work with us. You keep the report and action plan either way.' ),
							array( 'question' => 'What do I get?', 'answer' => 'A PDF audit report with findings and an action plan, a video walkthrough of your account, and a call to go through it together and answer your questions.' ),
							array( 'question' => 'How long does the audit take?', 'answer' => 'We deliver the report, video and call within 2–3 business days of getting access to your accounts.' ),
							array( 'question' => 'What access do you need?', 'answer' => 'Read-only or manager access to the ad accounts you want reviewed. We send simple steps after you request the audit. We never ask for your password.' ),
							array( 'question' => 'Which platforms do you audit?', 'answer' => 'Google Ads (including Search, Shopping, Performance Max and YouTube), Google Local Service Ads, and Meta Ads on Facebook and Instagram. You can choose one or all of them.' ),
							array( 'question' => 'Who is the free audit for?', 'answer' => 'Businesses currently spending on Google Ads, Local Service Ads or Meta Ads. If you haven\'t started advertising yet, request a proposal instead and we\'ll plan your first campaigns.' ),
							array( 'question' => 'Can I audit my own PPC account?', 'answer' => 'You can start with the search terms report, your conversion tracking and your cost per lead by campaign. A second pair of experienced eyes usually finds issues that are easy to miss when you manage the account yourself.' ),
							array( 'question' => 'Do I have to hire you afterwards?', 'answer' => 'No. The action plan is yours to use with your own team, your current agency or us. If your account is already in good shape, we\'ll tell you.' ),
						)
					),

					SLN_PPC_FINAL_CTA_META => array( 'active' => false ),
				)
			),
		),
		array(
			'slug'  => 'ecommerce-ppc',
			'title' => 'Ecommerce PPC Management',
			'meta'  => array_merge(
				sln_ppc_subpage_hidden_sections(),
				array(
					'_sln_seo_title'       => 'Ecommerce PPC Agency: Shopping & PMax | Smart Leading',
					'_sln_focus_keyword'   => 'ecommerce ppc agency',
					'_sln_seo_description' => 'Ecommerce PPC agency for Google Shopping and Performance Max: feed fixes, product structure and ROAS bidding, with every sale tracked. Get a free audit.',
					'_sln_service_type'    => 'Ecommerce PPC management (Google Shopping and Performance Max)',

					SLN_PPC_HERO_META => array(
						'small_heading'             => '',
						'main_heading'              => 'Ecommerce PPC Agency for Google Shopping',
						'highlighted_text'          => '& Performance Max.',
						'description'               => 'Performance Max spending money and you can\'t see why? As an ecommerce PPC agency, we fix your product feed, structure your products by margin and bid toward return on ad spend, with every sale tracked back to the campaign that drove it.',
						'primary_button_text'       => 'Get My Free Shopping Audit',
						'primary_button_url'        => '#contact',
						'secondary_button_text'     => 'See how it works',
						'secondary_button_url'      => '#approach',
						'search_label'              => 'When shoppers search, your products show up',
						'search_result_ad_label'    => 'Sponsored',
						'search_result_url'         => 'Google Shopping',
						'search_result_title'       => 'Your Product · Free shipping · In stock',
						'search_result_description' => 'Product image, price and reviews, right where shoppers compare.',
						'dashboard_title'           => 'Why Store Owners Trust Us',
						'chart_label'               => '',
						'live_label'                => 'SINCE 2018',
						'active'                    => true,
					),
					SLN_PPC_HERO_SEARCH_QUERIES_META => sln_ppc_subpage_rows(
						array(
							array( 'query' => 'healthy meal delivery plans' ),
							array( 'query' => 'buy kitchen knife set' ),
							array( 'query' => 'organic skincare gift set' ),
							array( 'query' => 'running shoes for women' ),
							array( 'query' => 'standing desk for home office' ),
						)
					),
					SLN_PPC_HERO_METRICS_META => sln_ppc_subpage_rows(
						array(
							array( 'display_value' => 'Google Partner', 'label' => 'Shopping and Performance Max', 'visual_style' => 'azure' ),
							array( 'display_value' => 'ROAS Focused', 'label' => 'Bids follow revenue, not clicks', 'visual_style' => 'orange' ),
							array( 'display_value' => 'Every Sale Tracked', 'label' => 'Purchases and revenue in GA4', 'visual_style' => 'up' ),
							array( 'display_value' => 'Your Account', 'label' => 'Ads and Merchant Center in your name', 'visual_style' => 'azure' ),
						)
					),
					SLN_PPC_KEYWORD_MARQUEE_META => sln_ppc_subpage_rows(
						array(
							array( 'keyword' => 'merchant center', 'icon_text' => '⌕' ),
							array( 'keyword' => 'product feed', 'icon_text' => '⌕' ),
							array( 'keyword' => 'performance max', 'icon_text' => '⌕' ),
							array( 'keyword' => 'standard shopping', 'icon_text' => '⌕' ),
							array( 'keyword' => 'target roas', 'icon_text' => '⌕' ),
							array( 'keyword' => 'asset groups', 'icon_text' => '⌕' ),
							array( 'keyword' => 'dynamic remarketing', 'icon_text' => '⌕' ),
							array( 'keyword' => 'revenue tracking', 'icon_text' => '⌕' ),
						)
					),

					SLN_PPC_REALITY_SECTION_META => array(
						'small_heading'    => 'The Problem',
						'main_heading'     => 'Why Your Ecommerce PPC',
						'highlighted_text' => 'Isn\'t Selling More.',
						'description'      => 'Shopping and Performance Max can be the most profitable ads a store runs. Left on autopilot, they spend on the wrong products.',
						'bottom_note'      => 'If any of these sound familiar, <b>your store needs ecommerce PPC management, not a bigger budget.</b>',
						'active'           => true,
					),
					SLN_PPC_REALITY_CHALLENGES_META => sln_ppc_subpage_rows(
						array(
							array( 'icon_text' => 'file-question', 'title' => 'Products Disapproved', 'description' => 'Feed errors and policy issues keep your best products out of Shopping.', 'impact' => 'LOST SALES' ),
							array( 'icon_text' => 'eye-off', 'title' => 'Performance Max Black Box', 'description' => 'Spend goes up, sales stay flat, and you can\'t see where the money went.', 'impact' => 'ZERO CLARITY' ),
							array( 'icon_text' => 'trending-up', 'title' => 'Spend on Low-Margin Items', 'description' => 'Budget flows to cheap, low-profit products instead of your winners.', 'impact' => 'THIN MARGINS' ),
							array( 'icon_text' => 'search-x', 'title' => 'Paying for Your Own Brand', 'description' => 'PMax takes credit for brand searches you would have won anyway.', 'impact' => 'WASTED SPEND' ),
							array( 'icon_text' => 'line-chart', 'title' => 'Revenue Tracking Broken', 'description' => 'Purchases and order values don\'t reach Google, so bidding guesses.', 'impact' => 'BAD BIDDING' ),
							array( 'icon_text' => 'refresh', 'title' => 'Shoppers Never Return', 'description' => 'Visitors view products and leave, with nothing bringing them back.', 'impact' => 'LOST CARTS' ),
						)
					),

					SLN_PPC_APPROACH_SECTION_META => array(
						'small_heading'    => 'Our Approach',
						'main_heading'     => 'How We Run Google Shopping',
						'highlighted_text' => 'and Performance Max.',
						'description'      => 'Our Google Shopping ads management starts with a clean product feed and ends with bidding on real revenue. That\'s also how to optimize Google Shopping ads that have stalled.',
						'active'           => true,
					),
					SLN_PPC_APPROACH_ITEMS_META => sln_ppc_subpage_rows(
						array(
							array( 'problem' => 'Products that never show', 'solution' => 'We review and optimize your <b>product feed and Google Merchant Center</b>: titles, images, GTINs and disapprovals.' ),
							array( 'problem' => 'Every product treated the same', 'solution' => 'We <b>group products by margin and performance</b>, so best sellers get budget and weak items don\'t drain it.' ),
							array( 'problem' => 'A Performance Max black box', 'solution' => 'We build <b>asset groups and audience signals</b> around your categories, and exclude brand searches where it makes sense.' ),
							array( 'problem' => 'Bids chasing cheap clicks', 'solution' => 'We bid toward <b>return on ad spend (ROAS)</b>, using real order values, not clicks.' ),
							array( 'problem' => 'Sales Google can\'t see', 'solution' => 'We set up <b>purchase and revenue tracking</b> with GA4 ecommerce events, so every sale is credited to the right campaign.' ),
							array( 'problem' => 'Carts left behind', 'solution' => 'We run <b>dynamic remarketing</b> that shows shoppers the exact products they viewed.' ),
						)
					),

					SLN_PPC_TRUTH_SECTION_META => array(
						'statement'        => 'What Is',
						'highlighted_text' => 'Performance Max?',
						'body'             => 'Performance Max is a Google Ads campaign type that uses your product feed, images, videos and text to show ads across Search, Shopping, YouTube, Display, Gmail and Maps from one campaign. Google\'s automation decides where and when to show each ad, based on your goal.<br><br>Google Shopping ads (product listing ads) show a product image, price and store name when people search for products. Performance Max includes Shopping placements, which is why a clean feed and good revenue tracking matter so much for PPC for ecommerce.',
						'quote'            => 'Automation needs direction.',
						'quote_highlight'  => 'We give it the right data.',
						'attribution'      => '',
						'button_text'      => 'Get My Free Shopping Audit',
						'button_url'       => '#contact',
						'active'           => true,
					),

					SLN_PPC_WHY_SECTION_META => array(
						'small_heading'    => 'Why SLS',
						'main_heading'     => 'A Google Shopping Ads Agency',
						'highlighted_text' => 'That Tracks Sales, Not Clicks.',
						'description'      => 'Smart Leading Solutions LLC has worked with business owners in the USA and Australia since 2018. Here\'s how we run ecommerce PPC differently.',
						'left_heading'     => 'Typical Agency',
						'right_heading'    => 'Smart Leading Solutions',
						'active'           => true,
					),
					SLN_PPC_WHY_COMPARISON_META => sln_ppc_subpage_rows(
						array(
							array( 'typical' => 'Ads and Merchant Center under the agency', 'sls' => 'Google Ads and Merchant Center in <b>your business name</b>. We\'re added as a manager' ),
							array( 'typical' => 'Asks for your login details', 'sls' => 'We <b>never ask for your password</b>, and you can remove our access any time' ),
							array( 'typical' => 'Reports clicks and impressions', 'sls' => 'Reports <b>revenue, ROAS and cost per sale</b> by product group' ),
							array( 'typical' => 'Leaves Performance Max on autopilot', 'sls' => 'Feed, asset groups and exclusions <b>reviewed continually</b>' ),
							array( 'typical' => 'Hides what isn\'t working', 'sls' => '<b>Real numbers, even when they\'re bad.</b> If something isn\'t working, we tell you and change it' ),
						)
					),
					SLN_PPC_WHY_BADGES_META => sln_ppc_subpage_rows(
						array(
							array( 'text' => 'Google Partner' ),
							array( 'text' => 'Your account, your data' ),
							array( 'text' => 'Every sale tracked' ),
							array( 'text' => 'Since 2018' ),
						)
					),

					SLN_PPC_SERVICES_SECTION_META => array(
						'small_heading'    => 'What\'s Included',
						'main_heading'     => 'What\'s Included in Our',
						'highlighted_text' => 'Ecommerce PPC Services.',
						'description'      => 'Our ecommerce PPC management services cover your feed, campaigns and tracking. Part of our PPC management services.',
						'active'           => true,
					),
					SLN_PPC_SERVICES_ITEMS_META => sln_ppc_subpage_rows(
						array(
							array( 'icon_key' => 'data', 'icon_text' => '▥', 'icon_style' => 'blue', 'tag' => 'FEED', 'title' => 'Feed and Merchant Center', 'description' => 'Titles, images, GTINs and disapprovals fixed.' ),
							array( 'icon_key' => 'pmax', 'icon_text' => '▣', 'icon_style' => 'blue', 'tag' => 'SHOPPING', 'title' => 'Shopping Campaigns', 'description' => 'Standard Shopping where you need control.' ),
							array( 'icon_key' => 'ai', 'icon_text' => '✦', 'icon_style' => 'blue', 'tag' => 'PMAX', 'title' => 'Performance Max', 'description' => 'Asset groups, signals and exclusions set up properly.' ),
							array( 'icon_key' => 'search', 'icon_text' => '⌕', 'icon_style' => 'blue', 'tag' => 'STRUCTURE', 'title' => 'Product Grouping', 'description' => 'Products grouped by margin and performance.' ),
							array( 'icon_key' => 'cro', 'icon_text' => '▤', 'icon_style' => 'blue', 'tag' => 'ROAS', 'title' => 'ROAS Bidding', 'description' => 'Bids set toward revenue, not clicks.' ),
							array( 'icon_key' => 'data', 'icon_text' => '▥', 'icon_style' => 'blue', 'tag' => 'TRACKING', 'title' => 'Revenue Tracking', 'description' => 'GA4 ecommerce events and purchase values.' ),
							array( 'icon_key' => 'retarget', 'icon_text' => '↺', 'icon_style' => 'blue', 'tag' => 'REMARKETING', 'title' => 'Dynamic Remarketing', 'description' => 'Shoppers see the products they viewed.' ),
							array( 'icon_key' => 'data', 'icon_text' => '▥', 'icon_style' => 'blue', 'tag' => 'REPORTING', 'title' => 'Revenue Reporting', 'description' => 'Sales, ROAS and cost per sale by product group.' ),
						)
					),

					SLN_PPC_PROCESS_SECTION_META => array(
						'small_heading'    => 'How It Starts',
						'main_heading'     => 'How Our Ecommerce PPC',
						'highlighted_text' => 'Company Gets Started.',
						'description'      => 'Starting with us is simple. Here\'s what happens after you get in touch.',
						'bottom_note'      => 'You always know what\'s running, what it costs and what it sold.',
						'active'           => true,
					),
					SLN_PPC_PROCESS_STEPS_META => sln_ppc_subpage_rows(
						array(
							array( 'number' => '01', 'title' => 'Discovery Call', 'bullets' => array( 'Your products and margins', 'Best sellers and goals', 'Budget and ROAS target' ) ),
							array( 'number' => '02', 'title' => 'Account and Feed Audit', 'bullets' => array( 'Merchant Center check', 'Campaign review', 'Tracking check' ) ),
							array( 'number' => '03', 'title' => 'Rebuild and Launch', 'bullets' => array( 'Feed fixes', 'Shopping and PMax structure', 'Launch with your approval' ) ),
							array( 'number' => '04', 'title' => 'Optimize and Report', 'bullets' => array( 'ROAS bidding', 'Product group changes', 'Revenue reporting' ) ),
						)
					),

					SLN_PPC_MID_CTA_META => array(
						'heading'     => 'Not Sure Where Your Shopping Budget Goes?',
						'description' => 'Get a free review of your Google Ads and Merchant Center. We\'ll show you what\'s selling, what\'s wasting money and what we\'d fix first.',
						'button_text' => 'Get My Free Shopping Audit',
						'button_url'  => '#contact',
						'active'      => true,
					),

					SLN_PPC_INDUSTRIES_SECTION_META => array(
						'small_heading'    => 'Who It\'s For',
						'main_heading'     => 'PPC for Ecommerce',
						'highlighted_text' => 'Brands and Stores.',
						'description'      => 'Google Shopping and Performance Max work best for stores with a clear product catalog and tracked sales:',
						'active'           => true,
					),
					SLN_PPC_INDUSTRIES_ITEMS_META => sln_ppc_subpage_rows(
						array(
							array( 'icon_text' => 'utensils', 'title' => 'Meal Delivery', 'description' => 'Plans and trial offers for people ready to order, including meal delivery brands we work with.' ),
							array( 'icon_text' => 'cart', 'title' => 'Online Stores', 'description' => 'Catalogs where shoppers compare products and prices.' ),
							array( 'icon_text' => 'chef-hat', 'title' => 'Home and Kitchen', 'description' => 'Products people search for by name, size and price.' ),
							array( 'icon_text' => 'sparkles', 'title' => 'Health and Beauty', 'description' => 'Repeat-purchase products that reward remarketing.' ),
							array( 'icon_text' => 'layers', 'title' => 'Growing Catalogs', 'description' => 'Stores adding products that need structure early.' ),
							array( 'icon_text' => 'search', 'title' => 'Not Sure It Fits?', 'description' => 'We\'ll check your feed and catalog in a free audit.' ),
						)
					),

					SLN_PPC_FAQ_SECTION_META => array(
						'small_heading'    => 'Common Questions',
						'main_heading'     => 'Ecommerce PPC',
						'highlighted_text' => 'FAQs.',
						'description'      => 'Straight answers about Google Shopping, Performance Max and how we manage them.',
						'active'           => true,
					),
					SLN_PPC_FAQ_ITEMS_META => sln_ppc_subpage_rows(
						array(
							array( 'question' => 'What are Google Shopping ads?', 'answer' => 'Google Shopping ads show a product image, price and store name when people search for products on Google. They come from your product feed in Google Merchant Center, not from keywords you write.' ),
							array( 'question' => 'What is Performance Max?', 'answer' => 'Performance Max is a Google Ads campaign type that runs across Search, Shopping, YouTube, Display, Gmail and Maps from one campaign, using Google\'s automation, your feed and your creative assets.' ),
							array( 'question' => 'Should I use Performance Max or Standard Shopping?', 'answer' => 'Performance Max reaches more placements with less manual control. Standard Shopping gives you more control over products and search terms. Many stores run both, with clear rules about which products go where.' ),
							array( 'question' => 'How much do Google Shopping ads cost?', 'answer' => 'You pay per click and set the budget. What a sale costs depends on your products, prices, margins and competition. Our management fee is separate from what you pay Google.' ),
							array( 'question' => 'How do you optimize Google Shopping ads?', 'answer' => 'We start with the feed (titles, images, attributes, disapprovals), group products by margin and performance, bid toward ROAS using real revenue, and keep reviewing search terms and product results.' ),
							array( 'question' => 'Do you fix product feed and Merchant Center issues?', 'answer' => 'Yes. We review your product feed and Google Merchant Center account for disapprovals, missing attributes and weak titles, and fix what is holding products back.' ),
							array( 'question' => 'How do you track ecommerce sales?', 'answer' => 'We set up purchase and revenue tracking with GA4 ecommerce events and Google Ads conversions, so each sale and its order value is credited to the campaign and product group that drove it.' ),
							array( 'question' => 'Do ecommerce sites need SEO and PPC?', 'answer' => 'Most do best with both. PPC brings sales quickly and shows which products convert; SEO builds traffic that doesn\'t cost per click over time.' ),
						)
					),

					SLN_PPC_FINAL_CTA_META => array(
						'small_heading'         => 'Your Next Step',
						'main_heading'          => 'Work With an Ecommerce PPC Agency',
						'highlighted_text'      => 'That Shows You the Revenue.',
						'description'           => 'Tell us about your store. We\'ll review your Shopping, Performance Max and Merchant Center setup and show you what we\'d change first.',
						'primary_button_text'   => 'Get My Free Shopping Audit',
						'primary_button_url'    => home_url( '/ppc/free-ppc-audit/' ),
						'secondary_button_text' => 'See how it works',
						'secondary_button_url'  => '#approach',
						'website_label'         => '',
						'website_text'          => '',
						'website_url'           => '',
						'bottom_note'           => 'Your accounts stay yours. No passwords needed.',
						'active'                => true,
					),
				)
			),
		),
	);
}

/**
 * Draft content for the PPC hub (/ppc/). Design-stage copy — final copy comes
 * from the ppc-copywriting skill after keyword research (work/ppc/hub/).
 *
 * @return array<string, mixed>
 */
function sln_ppc_hub_definition() {
	return array_merge(
		sln_ppc_subpage_hidden_sections(),
		array(
			'_sln_seo_title'       => 'PPC Management Services for More Leads | Smart Leading',
					'_sln_focus_keyword'   => 'ppc management services',
			'_sln_seo_description' => 'PPC management services from a Google Partner team: Google Ads, Local Services Ads, Meta Ads and more, with every call and form tracked. Get a free PPC audit.',
			'_sln_service_type'    => 'PPC management',

			SLN_PPC_HERO_META => array(
				'small_heading'             => '',
				'main_heading'              => 'PPC Management Services That Turn',
				'highlighted_text'          => 'Ad Spend Into Leads.',
				'description'               => 'Our PPC management covers Google Ads, Local Services Ads, Meta Ads and more, run by a Google Partner team that tracks every call and form back to the ad that brought it in. You keep your accounts, and you see what every lead costs.',
				'primary_button_text'       => 'Get My Free PPC Audit',
				'primary_button_url'        => '#contact',
				'secondary_button_text'     => 'Explore PPC services',
				'secondary_button_url'      => '#ppc-services',
				'search_label'              => 'When your customers search, your ad shows up',
				'search_result_ad_label'    => 'Ad',
				'search_result_url'         => 'www.smartleading.net',
				'search_result_title'       => 'PPC Management Services | Smart Leading Solutions',
				'search_result_description' => 'Google Partner team. Every lead tracked. Your accounts stay in your name.',
				'dashboard_title'           => 'Why Business Owners Trust Us',
				'chart_label'               => '',
				'live_label'                => 'SINCE 2018',
				'active'                    => true,
			),
			SLN_PPC_HERO_SEARCH_QUERIES_META => sln_ppc_subpage_rows(
				array(
					array( 'query' => 'kitchen remodel near me' ),
					array( 'query' => 'emergency dentist near me' ),
					array( 'query' => 'house cleaning service' ),
					array( 'query' => 'meal delivery service' ),
					array( 'query' => 'solar installation quote' ),
				)
			),
			SLN_PPC_HERO_METRICS_META => sln_ppc_subpage_rows(
				array(
					array( 'display_value' => 'Google Partner', 'label' => 'Part of Google\'s Partners program', 'visual_style' => 'azure' ),
					array( 'display_value' => 'CRO Focused', 'label' => 'Pages built to turn clicks into calls', 'visual_style' => 'orange' ),
					array( 'display_value' => 'Every Lead Tracked', 'label' => 'All channels in Lead Analytics', 'visual_style' => 'up' ),
					array( 'display_value' => 'Your Accounts', 'label' => 'In your name. No passwords asked', 'visual_style' => 'azure' ),
				)
			),
			SLN_PPC_KEYWORD_MARQUEE_META => sln_ppc_subpage_rows(
				array(
					array( 'keyword' => 'google ads', 'icon_text' => '⌕' ),
					array( 'keyword' => 'local services ads', 'icon_text' => '⌕' ),
					array( 'keyword' => 'facebook & instagram ads', 'icon_text' => '⌕' ),
					array( 'keyword' => 'google shopping', 'icon_text' => '⌕' ),
					array( 'keyword' => 'performance max', 'icon_text' => '⌕' ),
					array( 'keyword' => 'youtube ads', 'icon_text' => '⌕' ),
					array( 'keyword' => 'retargeting', 'icon_text' => '⌕' ),
					array( 'keyword' => 'call tracking', 'icon_text' => '⌕' ),
				)
			),

			SLN_PPC_REALITY_SECTION_META => array(
				'small_heading'    => 'The Problem',
				'main_heading'     => 'Why Most PPC Budgets',
				'highlighted_text' => 'Don\'t Turn Into Leads.',
				'description'      => 'Paid ads can bring customers fast. Without the right setup, they also burn budget fast.',
				'bottom_note'      => 'If even one of these sounds familiar, <b>your ads need management, not more budget.</b>',
				'active'           => true,
			),
			SLN_PPC_REALITY_CHALLENGES_META => sln_ppc_subpage_rows(
				array(
					array( 'icon_text' => 'search-x', 'title' => 'Paying for the Wrong Clicks', 'description' => 'Ads show for searches and audiences that were never going to buy.', 'impact' => 'WASTED SPEND' ),
					array( 'icon_text' => 'layers', 'title' => 'Budget on the Wrong Channel', 'description' => 'Money goes where the agency is comfortable, not where your customers are.', 'impact' => 'MISSED LEADS' ),
					array( 'icon_text' => 'eye-off', 'title' => 'No Lead Tracking', 'description' => 'Nobody can say which campaign brought which call or form.', 'impact' => 'ZERO CLARITY' ),
					array( 'icon_text' => 'trending-up', 'title' => 'Costs Rise Every Month', 'description' => 'Weak ads and landing pages push up what you pay for every click.', 'impact' => 'RISING COSTS' ),
					array( 'icon_text' => 'lock', 'title' => 'Agency Owns Your Account', 'description' => 'Your ad history and data live in an account you don\'t control.', 'impact' => 'NO CONTROL' ),
					array( 'icon_text' => 'file-question', 'title' => 'Reports You Can\'t Use', 'description' => 'Impressions and CTR, but no answer to "how many customers did we get?"', 'impact' => 'NO TRANSPARENCY' ),
				)
			),

			SLN_PPC_APPROACH_SECTION_META => array(
				'small_heading'    => 'How We Work',
				'main_heading'     => 'How We Handle PPC',
				'highlighted_text' => 'Campaign Management.',
				'description'      => 'Our PPC campaign management services follow one process on every platform: start with the leads you need, track everything, then spend where it works.',
				'active'           => true,
			),
			SLN_PPC_APPROACH_ITEMS_META => sln_ppc_subpage_rows(
				array(
					array( 'problem' => 'Campaigns built on guesses', 'solution' => 'We start with an <b>audit and goals</b>: what you sell, where you work and what a lead is worth to you.' ),
					array( 'problem' => 'Spending before tracking', 'solution' => 'We set up <b>call and form tracking</b> first, so every channel is judged on real leads.' ),
					array( 'problem' => 'One channel for everything', 'solution' => 'We pick <b>the channels that fit</b> how your customers search and buy.' ),
					array( 'problem' => 'Clicks that never convert', 'solution' => 'We write the <b>ads and landing pages</b> together, so the click turns into a call or form.' ),
					array( 'problem' => 'Set-and-forget accounts', 'solution' => 'We keep <b>optimizing bids, budgets and audiences</b> based on cost per lead.' ),
					array( 'problem' => 'Reports full of vanity metrics', 'solution' => 'We <b>report leads by channel</b>, with honest notes on what is and isn\'t working.' ),
				)
			),

			SLN_PPC_WHY_SECTION_META => array(
				'small_heading'    => 'Why SLS',
				'main_heading'     => 'A PPC Agency That',
				'highlighted_text' => 'Reports Leads, Not Clicks.',
				'description'      => 'Compare how Smart Leading Solutions LLC manages paid media with an in-house setup or a typical agency. We\'ve worked with business owners in the USA and Australia since 2018.',
				'left_heading'     => 'Typical Agency',
				'right_heading'    => 'Smart Leading Solutions',
				'active'           => true,
			),
			SLN_PPC_WHY_COMPARISON_META => sln_ppc_subpage_rows(
				array(
					array( 'typical' => 'Account set up under the agency', 'sls' => 'Accounts in <b>your business name</b>. We\'re added as a manager, never as owner' ),
					array( 'typical' => 'Asks for your login details', 'sls' => 'We <b>never ask for your password</b>, and you can remove our access any time' ),
					array( 'typical' => 'Reports clicks and impressions', 'sls' => 'Reports <b>calls, forms and cost per lead</b> for every channel' ),
					array( 'typical' => 'Set it and forget it', 'sls' => 'Search terms, audiences and bids <b>reviewed continually</b>' ),
					array( 'typical' => 'Hides what isn\'t working', 'sls' => '<b>Real numbers, even when they\'re bad.</b> If something isn\'t working, we tell you and change it' ),
				)
			),
			SLN_PPC_WHY_BADGES_META => sln_ppc_subpage_rows(
				array(
					array( 'text' => 'Google Partner' ),
					array( 'text' => 'Your accounts, your data' ),
					array( 'text' => 'Every lead tracked' ),
					array( 'text' => 'Since 2018' ),
				)
			),

			SLN_PPC_SERVICES_SECTION_META => array(
				'small_heading'    => 'What\'s Included',
				'main_heading'     => 'What\'s Included in Our',
				'highlighted_text' => 'PPC Advertising Services.',
				'description'      => 'Whichever channels you run, our PPC marketing services cover the full account, from strategy to lead reporting.',
				'active'           => true,
			),
			SLN_PPC_SERVICES_ITEMS_META => sln_ppc_subpage_rows(
				array(
					array( 'icon_key' => 'data', 'icon_text' => '▥', 'icon_style' => 'blue', 'tag' => 'STRATEGY', 'title' => 'Audit and Strategy', 'description' => 'A review of your accounts, market and goals, turned into a channel plan.' ),
					array( 'icon_key' => 'search', 'icon_text' => '⌕', 'icon_style' => 'blue', 'tag' => 'RESEARCH', 'title' => 'Keyword and Audience Research', 'description' => 'The searches and audiences most likely to become customers.' ),
					array( 'icon_key' => 'pmax', 'icon_text' => '▣', 'icon_style' => 'blue', 'tag' => 'BUILD', 'title' => 'Campaign Build', 'description' => 'Campaigns structured by service, location and goal.' ),
					array( 'icon_key' => 'ai', 'icon_text' => '✦', 'icon_style' => 'blue', 'tag' => 'CREATIVE', 'title' => 'Ad Copy and Creative', 'description' => 'Ads written and tested for each platform and audience.' ),
					array( 'icon_key' => 'data', 'icon_text' => '▥', 'icon_style' => 'blue', 'tag' => 'TRACKING', 'title' => 'Call and Form Tracking', 'description' => 'Every lead tracked back to the campaign that brought it.' ),
					array( 'icon_key' => 'cro', 'icon_text' => '▤', 'icon_style' => 'blue', 'tag' => 'CRO', 'title' => 'Landing Page CRO', 'description' => 'Pages that match the ad and make it easy to call or enquire.' ),
					array( 'icon_key' => 'retarget', 'icon_text' => '↺', 'icon_style' => 'blue', 'tag' => 'OPTIMIZE', 'title' => 'Ongoing Optimization', 'description' => 'Bids, budgets, keywords and audiences adjusted continually.' ),
					array( 'icon_key' => 'data', 'icon_text' => '▥', 'icon_style' => 'blue', 'tag' => 'REPORTING', 'title' => 'Lead Reporting', 'description' => 'Leads and cost per lead by channel in Lead Analytics.' ),
				)
			),

			SLN_PPC_PROCESS_SECTION_META => array(
				'small_heading'    => 'How It Starts',
				'main_heading'     => 'How Our PPC Company',
				'highlighted_text' => 'Gets You Started.',
				'description'      => 'Starting PPC management with us is simple. Here\'s what happens after you get in touch.',
				'bottom_note'      => 'You always know what\'s running, what it costs and which leads it brought in.',
				'active'           => true,
			),
			SLN_PPC_PROCESS_STEPS_META => sln_ppc_subpage_rows(
				array(
					array( 'number' => '01', 'title' => 'Discovery Call', 'bullets' => array( 'Your services and markets', 'The customers you want', 'Budget and goals' ) ),
					array( 'number' => '02', 'title' => 'Audit and Plan', 'bullets' => array( 'Review current accounts', 'Pick the right channels', 'Show where money is wasted' ) ),
					array( 'number' => '03', 'title' => 'Build and Launch', 'bullets' => array( 'Tracking set up first', 'Campaigns and ads built', 'Launch with your approval' ) ),
					array( 'number' => '04', 'title' => 'Optimize and Report', 'bullets' => array( 'Refine bids and budgets', 'Test ads and pages', 'Report leads by channel' ) ),
				)
			),

			SLN_PPC_MID_CTA_META => array(
				'heading'     => 'Not Sure Your PPC Budget Is Working?',
				'description' => 'Get a free review of your ad accounts. We\'ll show you what\'s working, what\'s wasting money and what we\'d change first.',
				'button_text' => 'Get My Free PPC Audit',
				'button_url'  => '#contact',
				'active'      => true,
			),

			SLN_PPC_INDUSTRIES_SECTION_META => array(
				'small_heading'    => 'Industries',
				'main_heading'     => 'Local PPC for',
				'highlighted_text' => 'Service Businesses.',
				'description'      => 'If your customers search or scroll before they buy, paid ads can bring them to you. These are businesses we work with:',
				'active'           => true,
			),
			SLN_PPC_INDUSTRIES_ITEMS_META => sln_ppc_subpage_rows(
				array(
					array( 'icon_text' => 'chef-hat', 'title' => 'Kitchen Businesses', 'description' => 'Quote requests from homeowners planning a remodel in your area.' ),
					array( 'icon_text' => 'hammer', 'title' => 'Home Improvement', 'description' => 'Calls for jobs you actually do, from people in your service area.' ),
					array( 'icon_text' => 'sparkles', 'title' => 'Cleaning', 'description' => 'Steady enquiries for one-off and recurring cleans.' ),
					array( 'icon_text' => 'tooth', 'title' => 'Dentistry', 'description' => 'New-patient calls and bookings from people nearby.' ),
					array( 'icon_text' => 'zap', 'title' => 'Energy', 'description' => 'Enquiries from homeowners and businesses comparing energy options.' ),
					array( 'icon_text' => 'utensils', 'title' => 'Meal Delivery', 'description' => 'Orders and sign-ups from people ready to try a new service.' ),
				)
			),

			SLN_PPC_FAQ_SECTION_META => array(
				'small_heading'    => 'Common Questions',
				'main_heading'     => 'PPC Management',
				'highlighted_text' => 'FAQs.',
				'description'      => 'Straight answers about our PPC services, and about PPC in general.',
				'active'           => true,
			),
			// First 6 = "About Our PPC Services", next 6 = "About PPC" (see sln_ppc_faq_groups()).
			SLN_PPC_FAQ_ITEMS_META => sln_ppc_subpage_rows(
				array(
					array( 'question' => 'Who owns my ad accounts?', 'answer' => 'You do. We\'re added as a manager, never as the owner, and we never ask for your password. If you don\'t have an account, we create it in your business name. You can remove our access whenever you choose.' ),
					array( 'question' => 'Are you a Google Partner?', 'answer' => 'Yes. Smart Leading Solutions is a Google Partner, part of Google\'s Partners program for agencies that manage Google Ads.' ),
					array( 'question' => 'Which platforms do your PPC services cover?', 'answer' => 'Google Ads (Search, Shopping, Performance Max and YouTube), Google Local Services Ads, Meta Ads on Facebook and Instagram, and retargeting. We recommend the mix that fits your business, not every channel by default.' ),
					array( 'question' => 'How much do PPC management services cost?', 'answer' => 'There are two costs: your ad spend, paid directly to the platforms, and a management fee for running the accounts. The fee depends on how many channels and campaigns we manage.' ),
					array( 'question' => 'How do you track PPC results?', 'answer' => 'Calls, website forms and platform lead forms are tracked and brought together in Lead Analytics, with duplicates counted once. You see leads and cost per lead by channel, not just clicks.' ),
					array( 'question' => 'How do I choose a PPC management company?', 'answer' => 'Ask who will own the ad accounts, how leads are tracked and what the reports will show. The best PPC agency for you explains its changes in plain English, reports cost per lead, and keeps your accounts in your business name.' ),
					array( 'question' => 'What is PPC marketing?', 'answer' => 'PPC (pay-per-click) marketing is advertising where you pay each time someone clicks your ad. Ads can appear on Google search results, Google Maps, YouTube, Facebook and Instagram, targeted by what people search for, where they are and who they are.' ),
					array( 'question' => 'What is PPC management?', 'answer' => 'PPC management is the ongoing work of planning, building and improving your paid campaigns: keyword and audience research, ad copy, bidding, budgets, conversion tracking and landing pages, all adjusted so your budget brings in leads.' ),
					array( 'question' => 'How much should I spend on PPC?', 'answer' => 'Start from what a lead is worth to you and how many you need. A useful budget covers enough clicks in your area to test keywords and ads properly. We recommend a starting budget after reviewing your market and goals.' ),
					array( 'question' => 'How long does PPC take to work?', 'answer' => 'Ads can start showing soon after campaigns go live. Turning clicks into steady, affordable leads takes ongoing testing of keywords, audiences, ads and landing pages.' ),
					array( 'question' => 'How do PPC and SEO work together?', 'answer' => 'PPC brings leads quickly while SEO builds long-term visibility. Search terms data from your ads shows which topics convert, which guides SEO content, and showing in both paid and organic results gives you more of the page.' ),
					array( 'question' => 'Is PPC good for small businesses?', 'answer' => 'Yes. You control the budget, can target a small service area and can start or pause at any time. With good tracking, even a modest budget shows clearly whether it brings customers.' ),
				)
			),

			SLN_PPC_FINAL_CTA_META => array(
				'small_heading'         => 'Your Next Step',
				'main_heading'          => 'Get PPC Management Services',
				'highlighted_text'      => 'That Show You the Leads.',
				'description'           => 'Tell us about your business and share your website. We\'ll review your ad accounts and show you where your budget is going.',
				'primary_button_text'   => 'Get My Free PPC Audit',
				'primary_button_url'    => home_url( '/ppc/free-ppc-audit/' ),
				'secondary_button_text' => 'Explore PPC services',
				'secondary_button_url'  => '#ppc-services',
				'website_label'         => '',
				'website_text'          => '',
				'website_url'           => '',
				'bottom_note'           => 'Your accounts stay yours. No passwords needed.',
				'active'                => true,
			),
		)
	);
}

/**
 * Apply the hub content to the main PPC page. Only adds fields the page
 * doesn't already have, unless $overwrite is true.
 *
 * @param bool $overwrite Replace existing values.
 * @return int|false Number of fields written, or false when the hub is missing.
 */
function sln_ppc_hub_apply( $overwrite = false ) {
	$hub = sln_ppc_subpages_hub();

	if ( ! $hub ) {
		return false;
	}

	$written = 0;

	foreach ( sln_ppc_hub_definition() as $key => $value ) {
		if ( ! $overwrite && metadata_exists( 'post', $hub->ID, $key ) ) {
			continue;
		}

		update_post_meta( $hub->ID, $key, wp_slash( $value ) );
		++$written;
	}

	return $written;
}
