<?php
/**
 * Starter content for the SEO sub-pages (children of /seo-services/).
 *
 * Used once by the "SEO Sub-pages" admin tool to create each page with the
 * SEO Services template. After a page exists, edit it in WP-admin — this file
 * is not read again for that page.
 *
 * Content rules (project-context/business-facts.md): no invented results,
 * prices, timelines, reviews, guarantees or certifications.
 *
 * @package Smart_Leading_Net
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Hero fields shared by every sub-page (trust line uses owner-confirmed facts only).
 *
 * @param array<string, mixed> $fields Page-specific hero fields.
 * @return array<string, mixed>
 */
function sln_seo_subpage_hero( array $fields ) {
	return array_merge(
		array(
			'primary_button_url'    => '#seo-proposal',
			'secondary_button_url'  => '#seo-services',
			'hero_image_id'         => 0,
			'trust_badge_text'      => '',
			'hero_stat_value'       => 'Since 2018',
			'hero_stat_label'       => 'helping businesses grow',
			'hero_stat_2_value'     => 'USA & Australia',
			'certified_team_text'   => 'client markets',
			'google_partner_url'    => '',
			'serp_positions'        => array( 9, 6, 4, 2, 1 ),
			'serp_traffic'          => array( '+12%', '+58%', '+140%', '+255%', '+312%' ),
		),
		$fields
	);
}

/**
 * "Why Smart Leading" blocks shared by every sub-page.
 *
 * @param string $focus Sentence about this service for block 01.
 * @return array<int, array<string, mixed>>
 */
function sln_seo_subpage_results_blocks( $focus ) {
	$rows = array(
		array( 'Real Results, Honestly Reported', $focus ),
		array( 'Trackable in Lead Analytics', 'Search Console, Google Analytics and Google Business Profile data sit next to your leads in your own client workspace.' ),
		array( 'Built Around Your Customers', 'We start with your business, your buyers and your website, then shape SEO around the people you want to reach.' ),
		array( 'Budget-Friendly by Design', 'We focus the work on what your business needs now, so your budget goes into the changes most likely to bring in leads.' ),
		array( 'Easy to Start', 'Tell us about your business and share your website. We take it from there and keep you informed at every step.' ),
		array( 'Experience Across Industries', 'Since 2018 we have worked with kitchen, home improvement, energy, cleaning, dental and meal delivery businesses in the USA and Australia.' ),
	);

	$blocks = array();

	foreach ( $rows as $index => $row ) {
		$blocks[] = array(
			'number'      => sprintf( '%02d', $index + 1 ),
			'label'       => $row[0],
			'description' => $row[1],
			'icon_id'     => 0,
			'url'         => '',
			'active'      => true,
		);
	}

	return $blocks;
}

/**
 * Build card rows with the fields the template expects.
 *
 * @param array<int, array<int, mixed>> $rows [icon_slug, title, description].
 * @return array<int, array<string, mixed>>
 */
function sln_seo_subpage_reality_cards( array $rows ) {
	return array_map(
		static function ( $row ) {
			return array( 'icon_id' => 0, 'icon_slug' => $row[0], 'title' => $row[1], 'description' => $row[2], 'url' => '', 'active' => true );
		},
		$rows
	);
}

/**
 * @param array<int, array<int, mixed>> $rows [title, description, bullets[]].
 * @return array<int, array<string, mixed>>
 */
function sln_seo_subpage_program_cards( array $rows ) {
	return array_map(
		static function ( $row ) {
			return array( 'icon_id' => 0, 'title' => $row[0], 'description' => $row[1], 'bullets' => $row[2], 'link_text' => '', 'link_url' => '', 'active' => true );
		},
		$rows
	);
}

/**
 * @param array<int, array<int, string>> $rows [title, description].
 * @return array<int, array<string, mixed>>
 */
function sln_seo_subpage_process_steps( array $rows ) {
	$steps = array();

	foreach ( $rows as $index => $row ) {
		$steps[] = array( 'step_number' => (string) ( $index + 1 ), 'icon_id' => 0, 'title' => $row[0], 'description' => $row[1], 'url' => '' );
	}

	return $steps;
}

/**
 * @param array<int, array<int, string>> $rows [question, answer].
 * @return array<int, array<string, mixed>>
 */
function sln_seo_subpage_faq_items( array $rows ) {
	return array_map(
		static function ( $row ) {
			return array( 'question' => $row[0], 'answer' => $row[1], 'active' => true );
		},
		$rows
	);
}

/**
 * CTA form fields.
 *
 * @param string $heading     Section heading.
 * @param string $description Section description.
 * @param string $form        Form heading.
 * @param string $button      Button text.
 * @return array<string, string>
 */
function sln_seo_subpage_cta( $heading, $description, $form, $button ) {
	return array(
		'small_heading'       => 'Get Started',
		'main_heading'        => $heading,
		'description'         => $description,
		'form_heading'        => $form,
		'name_placeholder'    => 'Jane Doe',
		'email_placeholder'   => 'jane@company.com',
		'phone_placeholder'   => '',
		'website_placeholder' => 'https://yourbusiness.com',
		'button_text'         => $button,
		'thank_you_page_url'  => '',
	);
}

/**
 * All SEO sub-page definitions, in menu order.
 *
 * @return array<int, array<string, mixed>>
 */
function sln_seo_subpages_definitions() {
	$hidden = array( 'logos', 'case_studies', 'pricing', 'testimonials' );

	return array(

		/* ------------------------------------------------------------------ SEO Audit */
		array(
			'title' => 'SEO Audit',
			'slug'  => 'seo-audit',
			'meta'  => array(
				SLN_SEO_SVC_HERO_META             => sln_seo_subpage_hero(
					array(
						'small_heading'         => 'SEO Audit Services',
						'main_heading'          => 'Find Out What\'s Holding',
						'highlighted_text'      => 'Your Rankings Back',
						'description'           => 'A complete review of your website\'s technical health, content, backlinks and local signals, with a prioritized list of fixes tied to the leads you want more of.',
						'primary_button_text'   => 'Request My SEO Audit',
						'secondary_button_text' => 'See What We Check',
						'serp_keyword'          => '[your service] in [your city]',
					)
				),
				SLN_SEO_SVC_REALITY_SECTION_META  => array(
					'small_heading'   => 'The Problem',
					'main_heading'    => 'You Can\'t Fix What You Can\'t See',
					'description'     => 'Most ranking problems are invisible from the front of your website. These are the issues an SEO audit is built to uncover.',
					'cta_text'        => '<strong>An audit turns guesswork into a plan.</strong> You see what\'s wrong, why it matters and what to fix first.',
					'cta_button_text' => 'Request My SEO Audit',
					'cta_button_url'  => '#seo-proposal',
				),
				SLN_SEO_SVC_REALITY_CARDS_META    => sln_seo_subpage_reality_cards(
					array(
						array( 'search-minus', 'Pages Google Can\'t Find', 'Pages blocked from crawling, missing from your sitemap or left out of the index can\'t rank, however good they are.' ),
						array( 'technical', 'Slow, Unstable Pages', 'Weak Core Web Vitals and heavy pages frustrate visitors and hand an easy advantage to competitors.' ),
						array( 'chart', 'Content That Misses Search Intent', 'Pages written around the wrong keywords attract visitors who were never going to call or enquire.' ),
						array( 'lock', 'Duplicate and Thin Pages', 'Near-identical pages compete with each other and dilute the signals Google uses to rank you.' ),
						array( 'clock', 'A Weak Backlink Profile', 'Too few relevant links, or risky ones, limit how far your most important pages can climb.' ),
						array( 'ai', 'Invisible to AI Search', 'Without clear structure and schema, AI Overviews and answer engines are more likely to skip your content.' ),
					)
				),
				SLN_SEO_SVC_PROGRAM_SECTION_META  => array(
					'small_heading' => 'What We Check',
					'main_heading'  => 'A Full SEO Audit, Not a Tool Export',
					'description'   => 'We combine crawl data, Search Console and Google Analytics with a hands-on review, then explain each finding in plain language.',
				),
				SLN_SEO_SVC_PROGRAM_CARDS_META    => sln_seo_subpage_program_cards(
					array(
						array( 'Technical Health', 'Can search engines crawl, render and index the pages that matter?', array( 'Crawling and indexing', 'Site speed and Core Web Vitals', 'Mobile usability and HTTPS' ) ),
						array( 'On-Page SEO', 'Does each page clearly match what your customers are searching for?', array( 'Titles, meta descriptions and headings', 'Content quality and search intent', 'Internal linking' ) ),
						array( 'Keywords and Competitors', 'Where do you rank today, and where are competitors winning instead?', array( 'Current rankings and missed keywords', 'Competitor comparison', 'Content gap list' ) ),
						array( 'Backlink Profile', 'Is your site trusted by the right websites, and are any links a risk?', array( 'Referring domains and anchor text', 'Spammy or toxic links', 'Link opportunities' ) ),
						array( 'Local Signals', 'Are you set up to appear in Google Maps and "near me" searches?', array( 'Google Business Profile review', 'Name, address and phone consistency', 'Local landing pages' ) ),
						array( 'Tracking Setup', 'Can you see which searches and pages actually bring in leads?', array( 'Google Analytics 4 and Search Console', 'Form and conversion tracking', 'Call tracking check' ) ),
					)
				),
				SLN_SEO_SVC_RESULTS_SECTION_META  => array(
					'small_heading'    => 'Why Smart Leading',
					'main_heading'     => 'An Audit You Can Actually Act On',
					'highlighted_word' => '',
					'description'      => 'An audit is only useful if it leads to the right changes. We keep the findings clear, honest and focused on what will help your business win more leads.',
				),
				SLN_SEO_SVC_RESULTS_BLOCKS_META   => sln_seo_subpage_results_blocks( 'We report what we actually find. No inflated issue counts and no scare tactics, just the problems that matter and why.' ),
				SLN_SEO_SVC_PROCESS_SECTION_META  => array(
					'small_heading' => 'How It Works',
					'main_heading'  => 'From Audit to Action Plan',
					'description'   => 'Every audit ends with clear priorities, not a report that sits in a drawer.',
				),
				SLN_SEO_SVC_PROCESS_STEPS_META    => sln_seo_subpage_process_steps(
					array(
						array( 'Discovery', 'We learn about your business, your customers, your competitors and the leads you want more of.' ),
						array( 'Data Collection', 'We crawl your website and review Search Console, Google Analytics and your Business Profile.' ),
						array( 'Analysis', 'We check technical health, content, links and local signals against your goals.' ),
						array( 'Prioritized Findings', 'Issues are ranked by impact and effort, so you know exactly what to fix first.' ),
						array( 'Walkthrough and Next Steps', 'We talk you through the findings and your options for fixing them, in-house or with us.' ),
					)
				),
				SLN_SEO_SVC_CTA_FORM_META         => sln_seo_subpage_cta(
					'See What\'s Holding Your Website Back',
					'Tell us about your business and website, and we\'ll get in touch about your SEO audit.',
					'Request Your SEO Audit',
					'Request My Audit'
				),
				SLN_SEO_SVC_FAQ_SECTION_META      => array(
					'small_heading'   => 'FAQ',
					'main_heading'    => 'SEO Audit Questions, Answered',
					'description'     => 'What businesses usually ask before booking an SEO audit.',
					'cta_button_text' => 'Ask Us Anything',
					'cta_button_url'  => '#seo-proposal',
				),
				SLN_SEO_SVC_FAQ_ITEMS_META        => sln_seo_subpage_faq_items(
					array(
						array( 'What does an SEO audit include?', 'We review your technical health, on-page content, keyword and competitor gaps, backlinks, local signals and tracking setup. We agree the exact scope with you before we start.' ),
						array( 'Who needs an SEO audit?', 'An audit is useful when traffic or leads have stalled or dropped, before a redesign or migration, or before you invest in an ongoing SEO program and want to know where to start.' ),
						array( 'What do I get at the end?', 'A prioritized list of findings explained in plain language, with recommended next steps. We confirm the format with you before we begin.' ),
						array( 'Can you fix the issues you find?', 'Yes. We offer technical SEO, content, link building and local SEO, so we can handle the fixes for you. Your own team or developer can also work from the findings.' ),
						array( 'Do you need access to my accounts?', 'Access to Google Search Console and Google Analytics gives the clearest picture. We\'ll tell you exactly what we need and why before we ask for anything.' ),
						array( 'How long does an SEO audit take?', 'It depends on the size of your website and the scope we agree. We\'ll give you a timeline before we start.' ),
					)
				),
				SLN_SEO_SVC_HIDDEN_SECTIONS_META  => $hidden,
			),
		),

		/* ------------------------------------------------------------- Technical SEO */
		array(
			'title' => 'Technical SEO',
			'slug'  => 'technical-seo',
			'meta'  => array(
				SLN_SEO_SVC_HERO_META             => sln_seo_subpage_hero(
					array(
						'small_heading'         => 'Technical SEO Services',
						'main_heading'          => 'Fix the Foundations',
						'highlighted_text'      => 'Your Rankings Stand On',
						'description'           => 'We fix the crawling, speed, indexing and structure issues that stop search engines and AI search from finding, understanding and ranking your pages.',
						'primary_button_text'   => 'Get My Free Proposal',
						'secondary_button_text' => 'See What We Fix',
						'serp_keyword'          => 'best [your service] near me',
					)
				),
				SLN_SEO_SVC_REALITY_SECTION_META  => array(
					'small_heading'   => 'The Problem',
					'main_heading'    => 'Great Content Can\'t Rank on a Broken Website',
					'description'     => 'Technical problems sit underneath everything else. Left alone, they limit how far any page can climb.',
					'cta_text'        => '<strong>Every one of these is fixable.</strong> We start by finding what\'s actually broken on your website.',
					'cta_button_text' => 'Get My Free Proposal',
					'cta_button_url'  => '#seo-proposal',
				),
				SLN_SEO_SVC_REALITY_CARDS_META    => sln_seo_subpage_reality_cards(
					array(
						array( 'search-minus', 'Pages Missing From Google', 'Noindex tags, blocked resources and orphaned pages keep important pages out of search results.' ),
						array( 'clock', 'Slow Load Times', 'Heavy images, render-blocking scripts and slow hosting hurt both rankings and enquiries.' ),
						array( 'technical', 'Wasted Crawl Budget', 'Redirect chains, broken links and endless parameter URLs send crawlers in circles instead of to your key pages.' ),
						array( 'chart', 'Mobile Experience Issues', 'Google indexes the mobile version of your website first. Layout shifts and hard-to-tap buttons hold you back.' ),
						array( 'lock', 'Split Ranking Signals', 'HTTP and HTTPS, www and non-www, and duplicate URLs divide the signals that should point to one page.' ),
						array( 'ai', 'Not Ready for AI Search', 'Missing schema and unclear structure make it harder for AI Overviews and answer engines to understand and cite you.' ),
					)
				),
				SLN_SEO_SVC_PROGRAM_SECTION_META  => array(
					'small_heading' => 'What We Fix',
					'main_heading'  => 'Technical SEO, Done Properly',
					'description'   => 'We work through your website\'s technical foundations and fix issues in order of impact.',
				),
				SLN_SEO_SVC_PROGRAM_CARDS_META    => sln_seo_subpage_program_cards(
					array(
						array( 'Crawling and Indexing', 'Make sure search engines can reach and index the pages that matter.', array( 'robots.txt and XML sitemaps', 'Index coverage fixes', 'Canonical tags' ) ),
						array( 'Speed and Core Web Vitals', 'Faster pages for visitors and better page experience signals.', array( 'LCP, INP and CLS improvements', 'Image and script optimization', 'Caching and hosting review' ) ),
						array( 'Site Architecture', 'A clear structure that helps people and crawlers find your key pages.', array( 'URL structure', 'Internal linking', 'Redirect and 404 clean-up' ) ),
						array( 'Mobile and Rendering', 'Make sure the mobile version shows everything Google needs to see.', array( 'Mobile-first checks', 'JavaScript rendering', 'Layout stability' ) ),
						array( 'Schema and Structured Data', 'Help search engines and AI tools understand your business and pages.', array( 'Organization and LocalBusiness schema', 'Service, FAQ and Article schema', 'Rich result eligibility' ) ),
						array( 'Migrations and Redesigns', 'Protect the rankings you have when your website changes.', array( 'Pre-launch SEO checks', 'Redirect mapping', 'Post-launch monitoring' ) ),
					)
				),
				SLN_SEO_SVC_RESULTS_SECTION_META  => array(
					'small_heading'    => 'Why Smart Leading',
					'main_heading'     => 'Technical Fixes Tied to Business Goals',
					'highlighted_word' => '',
					'description'      => 'We don\'t chase perfect scores for their own sake. We fix what affects your visibility and the leads your website brings in.',
				),
				SLN_SEO_SVC_RESULTS_BLOCKS_META   => sln_seo_subpage_results_blocks( 'We explain what we fixed, why it mattered and what changed, without padding reports with issues that don\'t affect your business.' ),
				SLN_SEO_SVC_PROCESS_SECTION_META  => array(
					'small_heading' => 'How It Works',
					'main_heading'  => 'From Diagnosis to Lasting Fixes',
					'description'   => 'A clear path from finding technical issues to keeping them fixed.',
				),
				SLN_SEO_SVC_PROCESS_STEPS_META    => sln_seo_subpage_process_steps(
					array(
						array( 'Technical Audit', 'We crawl your website and review Search Console to find what\'s holding it back.' ),
						array( 'Prioritize by Impact', 'We rank each issue by how much it affects visibility, traffic and leads.' ),
						array( 'Implement Fixes', 'We make the fixes directly or work alongside your developer.' ),
						array( 'Validate', 'We confirm each fix with re-crawls and Search Console checks.' ),
						array( 'Monitor and Maintain', 'We keep watching for new issues as your website and Google change.' ),
					)
				),
				SLN_SEO_SVC_CTA_FORM_META         => sln_seo_subpage_cta(
					'Give Your Website a Stronger Foundation',
					'Tell us about your website and goals, and we\'ll get in touch about your technical SEO.',
					'Get Your Free SEO Proposal',
					'Get My Free Proposal'
				),
				SLN_SEO_SVC_FAQ_SECTION_META      => array(
					'small_heading'   => 'FAQ',
					'main_heading'    => 'Technical SEO Questions, Answered',
					'description'     => 'Common questions about the technical side of SEO.',
					'cta_button_text' => 'Ask Us Anything',
					'cta_button_url'  => '#seo-proposal',
				),
				SLN_SEO_SVC_FAQ_ITEMS_META        => sln_seo_subpage_faq_items(
					array(
						array( 'What is technical SEO?', 'Technical SEO covers everything that helps search engines crawl, render, index and understand your website: speed, structure, mobile experience, redirects, canonical tags and structured data.' ),
						array( 'How do I know if my website has technical SEO problems?', 'Common signs are important pages missing from Google, errors in Search Console, slow pages, or a drop in traffic after a redesign. An SEO audit will confirm what\'s going on.' ),
						array( 'Will technical SEO alone improve my rankings?', 'It removes the barriers that hold your pages back. Content that matches what your customers search for, and authority from links, still matter, and we can help with both.' ),
						array( 'Do you need access to my website?', 'Usually, yes, so we can make fixes directly. If you prefer, we can work alongside your developer and give them clear instructions.' ),
						array( 'Can you help with a website migration or redesign?', 'Yes. We check the new website before launch, map redirects from old URLs and monitor rankings and indexing after launch.' ),
						array( 'How soon will fixes make a difference?', 'Google has to recrawl your pages before changes count, so it depends on your website and the issues involved. We track progress in Search Console and keep you updated.' ),
					)
				),
				SLN_SEO_SVC_HIDDEN_SECTIONS_META  => $hidden,
			),
		),

		/* ------------------------------------------------------------- Ecommerce SEO */
		array(
			'title' => 'Ecommerce SEO',
			'slug'  => 'ecommerce-seo',
			'meta'  => array(
				SLN_SEO_SVC_HERO_META             => sln_seo_subpage_hero(
					array(
						'small_heading'         => 'Ecommerce SEO Services',
						'main_heading'          => 'Get Your Products Found',
						'highlighted_text'      => 'by Shoppers Ready to Buy',
						'description'           => 'We optimize your category pages, product pages and store structure so your products show up when shoppers search for what you sell.',
						'primary_button_text'   => 'Get My Free Proposal',
						'secondary_button_text' => 'See How We Help',
						'serp_keyword'          => 'buy [your product] online',
					)
				),
				SLN_SEO_SVC_REALITY_SECTION_META  => array(
					'small_heading'   => 'The Problem',
					'main_heading'    => 'Shoppers Are Searching. Are Your Products Showing Up?',
					'description'     => 'Online stores face SEO problems that other websites don\'t. These are the ones we look for first.',
					'cta_text'        => '<strong>Every one of these is fixable.</strong> We start by finding what\'s keeping your products out of search results.',
					'cta_button_text' => 'Get My Free Proposal',
					'cta_button_url'  => '#seo-proposal',
				),
				SLN_SEO_SVC_REALITY_CARDS_META    => sln_seo_subpage_reality_cards(
					array(
						array( 'search-minus', 'Thin Product Pages', 'Manufacturer descriptions copied across many stores give Google little reason to rank your page over theirs.' ),
						array( 'technical', 'Duplicate URLs From Filters', 'Filters, sorting and product variants can create thousands of near-identical URLs that waste crawl budget.' ),
						array( 'chart', 'Weak Category Pages', 'Category pages often target your most valuable searches but have little content to help them rank.' ),
						array( 'clock', 'Slow Store Pages', 'Large images, apps and scripts slow your store down and cost you both rankings and sales.' ),
						array( 'lock', 'Lost Out-of-Stock Pages', 'Deleting discontinued or out-of-stock products without a plan throws away rankings and links.' ),
						array( 'ai', 'Missing From Rich Results', 'Without product schema, your listings miss prices, availability and ratings in search and AI results.' ),
					)
				),
				SLN_SEO_SVC_PROGRAM_SECTION_META  => array(
					'small_heading' => 'What We Do',
					'main_heading'  => 'Ecommerce SEO Built Around How Shoppers Search',
					'description'   => 'From category structure to product schema, we focus on the pages and searches closest to a sale.',
				),
				SLN_SEO_SVC_PROGRAM_CARDS_META    => sln_seo_subpage_program_cards(
					array(
						array( 'Category and Collection Pages', 'Turn your category pages into strong landing pages for high-intent searches.', array( 'Category keyword targeting', 'Helpful category content', 'Links between related collections' ) ),
						array( 'Product Page Optimization', 'Make each product page useful to shoppers and clear to search engines.', array( 'Unique titles and descriptions', 'Image alt text and file names', 'Product FAQs and details' ) ),
						array( 'Technical Store SEO', 'Keep large catalogs crawlable, fast and free of duplicate pages.', array( 'Filters, variants and canonicals', 'Crawl budget for large catalogs', 'Store speed improvements' ) ),
						array( 'Product Schema', 'Help your products stand out in search results.', array( 'Product, Offer and Review schema', 'Price and availability markup', 'Rich result monitoring' ) ),
						array( 'Content for Buyers', 'Answer the questions shoppers ask before they buy.', array( 'Buying guides and comparisons', 'Seasonal and gift content', 'Pre-purchase questions' ) ),
						array( 'Analytics and Tracking', 'See which searches and pages lead to sales.', array( 'Google Analytics 4 ecommerce setup', 'Search Console product queries', 'Reporting by category' ) ),
					)
				),
				SLN_SEO_SVC_RESULTS_SECTION_META  => array(
					'small_heading'    => 'Why Smart Leading',
					'main_heading'     => 'Ecommerce SEO Focused on Sales, Not Just Traffic',
					'highlighted_word' => '',
					'description'      => 'We have worked with ecommerce clients in the USA and Australia. We prioritize the categories and products that matter most to your store.',
				),
				SLN_SEO_SVC_RESULTS_BLOCKS_META   => sln_seo_subpage_results_blocks( 'We report what search actually does for your store, and we never dress up traffic as revenue it didn\'t bring in.' ),
				SLN_SEO_SVC_PROCESS_SECTION_META  => array(
					'small_heading' => 'How It Works',
					'main_heading'  => 'From Store Audit to Steady Organic Sales',
					'description'   => 'A clear plan that starts with your most valuable products and categories.',
				),
				SLN_SEO_SVC_PROCESS_STEPS_META    => sln_seo_subpage_process_steps(
					array(
						array( 'Store Audit', 'We review your catalog, structure, speed and current rankings.' ),
						array( 'Keyword and Category Mapping', 'We match shopper searches to the right categories and products.' ),
						array( 'Technical Fixes', 'We clean up duplicate URLs, filters, speed and schema issues.' ),
						array( 'Page Optimization and Content', 'We improve category and product pages and add content shoppers need.' ),
						array( 'Measure and Expand', 'We track what\'s working and roll it out across more of your catalog.' ),
					)
				),
				SLN_SEO_SVC_CTA_FORM_META         => sln_seo_subpage_cta(
					'Turn Search Traffic Into Store Sales',
					'Tell us about your store and products, and we\'ll get in touch about your ecommerce SEO.',
					'Get Your Free SEO Proposal',
					'Get My Free Proposal'
				),
				SLN_SEO_SVC_FAQ_SECTION_META      => array(
					'small_heading'   => 'FAQ',
					'main_heading'    => 'Ecommerce SEO Questions, Answered',
					'description'     => 'What store owners usually ask us about SEO.',
					'cta_button_text' => 'Ask Us Anything',
					'cta_button_url'  => '#seo-proposal',
				),
				SLN_SEO_SVC_FAQ_ITEMS_META        => sln_seo_subpage_faq_items(
					array(
						array( 'How is ecommerce SEO different from regular SEO?', 'Stores have large catalogs, filters, product variants and stock changes, which create technical and content challenges most websites never face. Category and product pages also need a different approach from service pages.' ),
						array( 'Do I need unique descriptions for every product?', 'Ideally, yes, but you don\'t have to do it all at once. We start with your best-selling and most-searched products and categories.' ),
						array( 'What should I do with out-of-stock products?', 'It depends on whether the product is coming back. Temporarily unavailable products usually stay live; discontinued ones are often redirected to the closest alternative so you keep their rankings and links.' ),
						array( 'How do filters and variants affect SEO?', 'Each filter or variant can create a new URL. Without the right canonical tags and crawl rules, search engines waste time on duplicates instead of your key pages.' ),
						array( 'Do you work with stores outside the USA?', 'Yes. We have ecommerce clients in the USA and Australia. If you sell in several countries, our International SEO service covers that too.' ),
						array( 'How soon will I see results?', 'It depends on your store, your competition and where you\'re starting from. We set clear priorities up front and track progress so you can see what\'s changing.' ),
					)
				),
				SLN_SEO_SVC_HIDDEN_SECTIONS_META  => $hidden,
			),
		),

		/* --------------------------------------------------------- International SEO */
		array(
			'title' => 'International SEO',
			'slug'  => 'international-seo',
			'meta'  => array(
				SLN_SEO_SVC_HERO_META             => sln_seo_subpage_hero(
					array(
						'small_heading'         => 'International SEO Services',
						'main_heading'          => 'Reach Customers',
						'highlighted_text'      => 'in Every Market You Serve',
						'description'           => 'We set up the site structure, hreflang and market-specific content that help the right version of your website rank in each country and language you target.',
						'primary_button_text'   => 'Get My Free Proposal',
						'secondary_button_text' => 'See How It Works',
						'serp_keyword'          => '[your service] in [country]',
					)
				),
				SLN_SEO_SVC_REALITY_SECTION_META  => array(
					'small_heading'   => 'The Problem',
					'main_heading'    => 'Going Global Shouldn\'t Mean Starting From Zero',
					'description'     => 'Expanding into new countries brings SEO problems a single-market website never sees.',
					'cta_text'        => '<strong>Every one of these is fixable.</strong> We start by checking how search engines see each version of your website.',
					'cta_button_text' => 'Get My Free Proposal',
					'cta_button_url'  => '#seo-proposal',
				),
				SLN_SEO_SVC_REALITY_CARDS_META    => sln_seo_subpage_reality_cards(
					array(
						array( 'search-minus', 'The Wrong Page in the Wrong Country', 'Your US page shows up in Australia, with the wrong prices, spelling or contact details.' ),
						array( 'technical', 'Hreflang Errors', 'Missing return tags and wrong language or country codes confuse search engines about which page to show.' ),
						array( 'lock', 'Duplicate Regional Pages', 'Near-identical pages for each country compete with each other instead of ranking in their own market.' ),
						array( 'chart', 'Translated, Not Localized', 'Word-for-word copy misses the terms local customers actually search for.' ),
						array( 'clock', 'Slow for Overseas Visitors', 'A website hosted far from your customers loads slowly where it matters.' ),
						array( 'ai', 'Unclear Market Signals', 'Without clear country and language signals, search engines and AI tools struggle to match you to local searches.' ),
					)
				),
				SLN_SEO_SVC_PROGRAM_SECTION_META  => array(
					'small_heading' => 'What We Do',
					'main_heading'  => 'International SEO, Market by Market',
					'description'   => 'We get the technical setup right first, then build visibility in each market you want to grow in.',
				),
				SLN_SEO_SVC_PROGRAM_CARDS_META    => sln_seo_subpage_program_cards(
					array(
						array( 'Site Structure Strategy', 'Choose the right setup for your markets and growth plans.', array( 'Country domains, subfolders or subdomains', 'URL and navigation plan', 'Country targeting setup' ) ),
						array( 'Hreflang Setup', 'Show each searcher the right language and country version.', array( 'Hreflang tags and sitemaps', 'Language, country and x-default codes', 'Error checks in Search Console' ) ),
						array( 'Market Keyword Research', 'Find what customers search for in each country.', array( 'Local terms and spelling', 'Competitors in each market', 'Search intent by country' ) ),
						array( 'Content Localization', 'Make every market\'s pages feel local, not copied.', array( 'Localized landing pages', 'Currency, units and local details', 'Avoiding duplicate regional pages' ) ),
						array( 'Technical Performance', 'Keep every version fast and crawlable worldwide.', array( 'Global load times and CDN', 'Crawl and index checks by region', 'Redirect and language detection rules' ) ),
						array( 'Tracking by Market', 'See how each country is performing on its own.', array( 'Search Console by country', 'Google Analytics 4 by market', 'Reporting by region' ) ),
					)
				),
				SLN_SEO_SVC_RESULTS_SECTION_META  => array(
					'small_heading'    => 'Why Smart Leading',
					'main_heading'     => 'International SEO With Clients on Both Sides of the Pacific',
					'highlighted_word' => '',
					'description'      => 'We work with clients in the USA and Australia, including international SEO projects, so we know how differently customers can search in each market.',
				),
				SLN_SEO_SVC_RESULTS_BLOCKS_META   => sln_seo_subpage_results_blocks( 'We report performance market by market, so a strong country never hides a weak one.' ),
				SLN_SEO_SVC_PROCESS_SECTION_META  => array(
					'small_heading' => 'How It Works',
					'main_heading'  => 'From Market Plan to Local Rankings',
					'description'   => 'A step-by-step approach to growing in each country you target.',
				),
				SLN_SEO_SVC_PROCESS_STEPS_META    => sln_seo_subpage_process_steps(
					array(
						array( 'Market Discovery', 'We confirm your target countries, languages and the customers you want to reach in each.' ),
						array( 'Structure and Hreflang Plan', 'We recommend the right site setup and map every page to its market.' ),
						array( 'Technical Setup', 'We implement hreflang, targeting and performance improvements.' ),
						array( 'Localized Content', 'We adapt key pages to local search terms and customer expectations.' ),
						array( 'Measure by Market', 'We track rankings and traffic country by country and adjust.' ),
					)
				),
				SLN_SEO_SVC_CTA_FORM_META         => sln_seo_subpage_cta(
					'Grow in the Markets That Matter to You',
					'Tell us about your business and the countries you want to reach, and we\'ll get in touch about your international SEO.',
					'Get Your Free SEO Proposal',
					'Get My Free Proposal'
				),
				SLN_SEO_SVC_FAQ_SECTION_META      => array(
					'small_heading'   => 'FAQ',
					'main_heading'    => 'International SEO Questions, Answered',
					'description'     => 'What businesses ask before expanding into new markets.',
					'cta_button_text' => 'Ask Us Anything',
					'cta_button_url'  => '#seo-proposal',
				),
				SLN_SEO_SVC_FAQ_ITEMS_META        => sln_seo_subpage_faq_items(
					array(
						array( 'What is international SEO?', 'International SEO helps search engines show the right version of your website to people in each country and language you target, and helps those versions rank locally.' ),
						array( 'Should I use country domains, subfolders or subdomains?', 'Each option has trade-offs in cost, authority and local trust. We recommend a setup based on your markets, your resources and how your website is built today.' ),
						array( 'What is hreflang, and do I need it?', 'Hreflang tells search engines which language and country each page is for. If you have similar pages for different countries or languages, you almost certainly need it.' ),
						array( 'Do I need different content for each country?', 'Even in the same language, customers search differently. Spelling, terms, prices and contact details should match each market so pages don\'t compete with each other.' ),
						array( 'Is translating my website enough?', 'Translation is a start, but localization goes further, adapting keywords, examples and details to how people in each market actually search and buy.' ),
						array( 'Which markets do you work in?', 'We have clients in the USA and Australia. Tell us which countries you\'re targeting and we\'ll talk through the right approach.' ),
					)
				),
				SLN_SEO_SVC_HIDDEN_SECTIONS_META  => $hidden,
			),
		),

		/* ------------------------------------------------------------- Link Building */
		array(
			'title' => 'Link Building',
			'slug'  => 'link-building',
			'meta'  => array(
				SLN_SEO_SVC_HERO_META             => sln_seo_subpage_hero(
					array(
						'small_heading'         => 'Link Building Services',
						'main_heading'          => 'Earn the Links',
						'highlighted_text'      => 'That Build Real Authority',
						'description'           => 'We earn relevant backlinks from real websites in your industry and market, with no link farms and no shortcuts that put your website at risk.',
						'primary_button_text'   => 'Get My Free Proposal',
						'secondary_button_text' => 'See Our Approach',
						'serp_keyword'          => 'best [your service] near me',
					)
				),
				SLN_SEO_SVC_REALITY_SECTION_META  => array(
					'small_heading'   => 'The Problem',
					'main_heading'    => 'Great Pages Still Need Authority to Rank',
					'description'     => 'Links from trusted websites remain one of the strongest signals search engines use. These are the link problems we look for.',
					'cta_text'        => '<strong>Every one of these is fixable.</strong> We start by comparing your link profile with the competitors outranking you.',
					'cta_button_text' => 'Get My Free Proposal',
					'cta_button_url'  => '#seo-proposal',
				),
				SLN_SEO_SVC_REALITY_CARDS_META    => sln_seo_subpage_reality_cards(
					array(
						array( 'chart', 'Competitors Have More Authority', 'Competitors with stronger, more relevant links keep outranking you, even when your pages are better.' ),
						array( 'lock', 'Spammy or Risky Links', 'Low-quality links from unrelated or paid sources can do more harm than good.' ),
						array( 'technical', 'Links Pointing to Dead Pages', 'Backlinks to deleted or moved pages waste authority you have already earned.' ),
						array( 'search-minus', 'No Links to Your Key Pages', 'Links that only point to your homepage do little for the service and product pages that bring in leads.' ),
						array( 'clock', 'Inconsistent Local Listings', 'Missing or inconsistent directory listings weaken local trust signals.' ),
						array( 'ai', 'Few Mentions From Trusted Sources', 'Search engines and AI tools look at who talks about your brand. Few mentions means less visibility.' ),
					)
				),
				SLN_SEO_SVC_PROGRAM_SECTION_META  => array(
					'small_heading' => 'What We Do',
					'main_heading'  => 'Link Building Built for the Long Term',
					'description'   => 'We focus on relevance and quality, earning links that keep helping your rankings instead of putting them at risk.',
				),
				SLN_SEO_SVC_PROGRAM_CARDS_META    => sln_seo_subpage_program_cards(
					array(
						array( 'Link Gap Analysis', 'See where competitors earn links that you don\'t.', array( 'Competitor backlink review', 'Opportunity list', 'Priority pages to support' ) ),
						array( 'Editorial Outreach', 'Earn links from relevant websites in your industry.', array( 'Relevant industry websites', 'Personalized outreach', 'Links in context, within content' ) ),
						array( 'Linkable Content', 'Create content other websites want to reference.', array( 'Guides and resources', 'Data-led content', 'Brand mentions' ) ),
						array( 'Local Citations', 'Strengthen local trust with consistent listings.', array( 'Business directories', 'Name, address and phone consistency', 'Local partner and association links' ) ),
						array( 'Link Reclamation', 'Recover authority you have already earned.', array( 'Fix links to deleted pages', 'Unlinked brand mentions', 'Redirect clean-up' ) ),
						array( 'Link Profile Health', 'Keep your backlink profile clean and natural.', array( 'Toxic link review', 'Disavow when needed', 'Anchor text balance' ) ),
					)
				),
				SLN_SEO_SVC_RESULTS_SECTION_META  => array(
					'small_heading'    => 'Why Smart Leading',
					'main_heading'     => 'Links Earned the Right Way',
					'highlighted_word' => '',
					'description'      => 'Shortcuts can work for a while, then cost you everything. We focus on links that make sense for your business and stand up over time.',
				),
				SLN_SEO_SVC_RESULTS_BLOCKS_META   => sln_seo_subpage_results_blocks( 'We report the links we actually earn and where they come from. No padded link counts and no low-quality links passed off as wins.' ),
				SLN_SEO_SVC_PROCESS_SECTION_META  => array(
					'small_heading' => 'How It Works',
					'main_heading'  => 'From Backlink Audit to Lasting Authority',
					'description'   => 'A steady, transparent process focused on quality over volume.',
				),
				SLN_SEO_SVC_PROCESS_STEPS_META    => sln_seo_subpage_process_steps(
					array(
						array( 'Backlink Audit', 'We review your current links and compare them with the competitors outranking you.' ),
						array( 'Strategy and Targets', 'We choose the pages to support and the types of websites to approach.' ),
						array( 'Content and Outreach', 'We create linkable content and reach out to relevant websites.' ),
						array( 'Quality Review', 'Every link is checked for relevance and quality.' ),
						array( 'Report and Refine', 'We share the links earned and how your target pages are moving, then adjust.' ),
					)
				),
				SLN_SEO_SVC_CTA_FORM_META         => sln_seo_subpage_cta(
					'Build Authority That Lasts',
					'Tell us about your business and website, and we\'ll get in touch about link building.',
					'Get Your Free SEO Proposal',
					'Get My Free Proposal'
				),
				SLN_SEO_SVC_FAQ_SECTION_META      => array(
					'small_heading'   => 'FAQ',
					'main_heading'    => 'Link Building Questions, Answered',
					'description'     => 'What businesses usually ask us about backlinks.',
					'cta_button_text' => 'Ask Us Anything',
					'cta_button_url'  => '#seo-proposal',
				),
				SLN_SEO_SVC_FAQ_ITEMS_META        => sln_seo_subpage_faq_items(
					array(
						array( 'What is link building?', 'Link building is the work of earning links from other websites to yours. Relevant links from trusted websites help search engines see your pages as credible.' ),
						array( 'Do you buy links?', 'No. Buying links to manipulate rankings goes against Google\'s spam policies and can lead to penalties. We focus on links earned through outreach, content and real relationships.' ),
						array( 'How many links will I get each month?', 'We don\'t promise link counts. One relevant link from a trusted website can do more than dozens of weak ones, so we focus on quality and relevance.' ),
						array( 'Is link building safe?', 'Done properly, yes. Risk comes from spammy, paid or irrelevant links. We avoid those and review your existing profile for problems too.' ),
						array( 'Can you remove toxic links pointing to my website?', 'We review your backlink profile, ask for removal where possible and use Google\'s disavow tool when it\'s genuinely needed.' ),
						array( 'How long before links affect rankings?', 'It varies with your competition and how quickly search engines find the new links. We report the links earned and track your target pages over time.' ),
					)
				),
				SLN_SEO_SVC_HIDDEN_SECTIONS_META  => $hidden,
			),
		),
	);
}
