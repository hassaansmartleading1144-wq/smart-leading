<?php
/**
 * Gutenberg block patterns for Locale SEO custom blocks.
 *
 * @package Smart_Leading_Net
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Register Locale SEO patterns.
 */
function sln_locel_seo_register_block_patterns() {
	register_block_pattern_category(
		'sln-locel-seo',
		array(
			'label' => __( 'Locale SEO', 'smart-leading-net' ),
		)
	);

	register_block_pattern(
		'sln-locel-seo/full-page',
		array(
			'title'       => __( 'Locale SEO — Full Page', 'smart-leading-net' ),
			'description' => __( 'Complete Locale SEO landing page built from custom Gutenberg section blocks.', 'smart-leading-net' ),
			'categories'  => array( 'sln-locel-seo' ),
			'content'     => sln_locel_seo_full_page_pattern_content(),
		)
	);

	$sections = array(
		'hero'        => __( 'Locale SEO — Hero', 'smart-leading-net' ),
		'discovery'   => __( 'Locale SEO — Local Discovery', 'smart-leading-net' ),
		'challenges'  => __( 'Locale SEO — Customer Challenges', 'smart-leading-net' ),
		'beyond'      => __( 'Locale SEO — Beyond Traffic', 'smart-leading-net' ),
		'approach'    => __( 'Locale SEO — Our Approach', 'smart-leading-net' ),
		'ticket'      => __( 'Locale SEO — Audit Ticket', 'smart-leading-net' ),
		'case'        => __( 'Locale SEO — Case Study', 'smart-leading-net' ),
		'process'     => __( 'Locale SEO — Process', 'smart-leading-net' ),
		'reporting'   => __( 'Locale SEO — Reporting', 'smart-leading-net' ),
		'tech'        => __( 'Locale SEO — Technology', 'smart-leading-net' ),
		'working'     => __( 'Locale SEO — Working With SLS', 'smart-leading-net' ),
		'compare'     => __( 'Locale SEO — Compare Options', 'smart-leading-net' ),
		'faq'         => __( 'Locale SEO — FAQ', 'smart-leading-net' ),
		'audit'       => __( 'Locale SEO — Audit Request', 'smart-leading-net' ),
	);
	$full = sln_locel_seo_full_page_pattern_content();
	foreach ( $sections as $slug => $title ) {
		if ( ! preg_match( '/<!-- wp:smartleading\/locel-seo-' . preg_quote( $slug, '/' ) . '(?:\s+\{[^}]*\})?\s+(?:\/-->|-->.*?<!-- \/wp:smartleading\/locel-seo-' . preg_quote( $slug, '/' ) . ' -->)/s', $full, $match ) ) {
			continue;
		}
		register_block_pattern(
			'sln-locel-seo/' . $slug,
			array(
				'title'       => $title,
				'description' => $title,
				'categories'  => array( 'sln-locel-seo' ),
				'content'     => $match[0],
			)
		);
	}
}
add_action( 'init', 'sln_locel_seo_register_block_patterns' );

/**
 * Full-page starter pattern markup.
 *
 * @return string
 */
function sln_locel_seo_full_page_pattern_content() {
	return '<!-- wp:smartleading/locel-seo-hero -->
<!-- wp:smartleading/locel-seo-map-card {"variant":"search","number":"1","label":"A nearby customer searches","searchText":"[your service] near me"} /-->
<!-- wp:smartleading/locel-seo-map-card {"variant":"profile","number":"2","label":"Business Profile","title":"Your Business","meta":"Category · Service area · Hours","chips":"Call|Directions|Website"} /-->
<!-- wp:smartleading/locel-seo-map-card {"variant":"site","number":"2","label":"Website","title":"Service & location page","chips":"Clear details|Next step"} /-->
<!-- wp:smartleading/locel-seo-map-card {"variant":"goal","number":"3","label":"They take the next step","chips":"Call|Quote request|Appointment|Visit"} /-->
<!-- /wp:smartleading/locel-seo-hero -->

<!-- wp:smartleading/locel-seo-discovery -->
<!-- wp:smartleading/locel-seo-stop {"icon":"pin","title":"Profile discovery","text":"An accurate, complete Business Profile helps you appear for searches that genuinely match what you do."} /-->
<!-- wp:smartleading/locel-seo-stop {"icon":"web","title":"Relevant website information","text":"Clear service and location pages answer the questions people have before they decide to contact you."} /-->
<!-- wp:smartleading/locel-seo-stop {"icon":"phone","title":"A clear call, booking or quote path","text":"Phone numbers, forms and booking links that are easy to find make it simple to take the next step."} /-->
<!-- wp:smartleading/locel-seo-merge-col {"title":"Map and Business Profile visibility","text":"How your business shows up in map results and local listings.","tick1":"Profile categories, services and details","tick2":"Consistent business information across listings","tick3":"Genuine customer reviews"} /-->
<!-- wp:smartleading/locel-seo-merge-col {"title":"Website performance","text":"How well your site is found, understood and used once people arrive.","tick1":"Service and location content","tick2":"Technical health and page structure","tick3":"Easy-to-find contact options"} /-->
<!-- wp:smartleading/locel-seo-factor {"art":"relevance","title":"Relevance","text":"How well your profile and information match what someone is searching for."} /-->
<!-- wp:smartleading/locel-seo-factor {"art":"distance","title":"Distance","text":"How far your business is from the searcher or the location they search. This is not something any agency controls."} /-->
<!-- wp:smartleading/locel-seo-factor {"art":"prominence","title":"Prominence","text":"How well known your business is, including reviews and information about you across the web."} /-->
<!-- /wp:smartleading/locel-seo-discovery -->

<!-- wp:smartleading/locel-seo-challenges -->
<!-- wp:smartleading/locel-seo-challenge {"icon":"searchx","title":"Are you hard to find for the services you actually offer?","text":"Your profile or pages may not clearly reflect the work you do, so you may not appear for relevant searches.","sign":"Possible sign: few profile views for core services"} /-->
<!-- wp:smartleading/locel-seo-challenge {"icon":"pinq","title":"Is your location and service-area information unclear?","text":"Inconsistent addresses, service areas or hours can confuse customers and search engines alike.","sign":"Possible sign: details differ between listings"} /-->
<!-- wp:smartleading/locel-seo-challenge {"icon":"pointer","title":"Are people visiting without getting in touch?","text":"Visitors may arrive but not find what they need, or not see an easy way to call, book or ask for a quote.","sign":"Possible sign: visits, but few calls or forms"} /-->
<!-- wp:smartleading/locel-seo-challenge {"icon":"chartq","title":"Are you unsure what your marketing is actually producing?","text":"Reports full of rankings and traffic can make it hard to see whether real enquiries are coming in.","sign":"Possible sign: reports you can’t act on"} /-->
<!-- /wp:smartleading/locel-seo-challenges -->

<!-- wp:smartleading/locel-seo-beyond {"videoUrl":"https://www.instagram.com/reels/DcTFVBJjw_Y/"} -->
<!-- wp:smartleading/locel-seo-point {"number":"1","title":"Start from your business goals","text":"The services, areas and customers that matter most to you set the priorities."} /-->
<!-- wp:smartleading/locel-seo-point {"number":"2","title":"Remove what stands between searches and enquiries","text":"Profile, listing, content and website issues are tackled in order of likely impact."} /-->
<!-- wp:smartleading/locel-seo-point {"number":"3","title":"Measure enquiries, not just rankings","text":"Reporting looks at recorded enquiries and, where you can share them, real outcomes."} /-->
<!-- /wp:smartleading/locel-seo-beyond -->

<!-- wp:smartleading/locel-seo-approach -->
<!-- wp:smartleading/locel-seo-tile {"icon":"shop","wide":true,"blue":true,"mock":"profile","title":"Business Profile review & optimization","text":"Check categories, services, hours, photos and details so your profile reflects the real business accurately.","focus":"relevance and completeness"} /-->
<!-- wp:smartleading/locel-seo-tile {"icon":"searchplus","title":"Local search & competitor research","text":"Understand which searches matter in your service area and how nearby competitors present themselves.","focus":"realistic local opportunities"} /-->
<!-- wp:smartleading/locel-seo-tile {"icon":"doc","title":"Service & location content","text":"Pages that explain what you do and where you do it, built around places you genuinely serve.","focus":"answering customer questions"} /-->
<!-- wp:smartleading/locel-seo-tile {"icon":"wrench","wide":true,"mock":"meters","title":"Technical & on-page improvement","text":"Address crawl, speed, structure and on-page issues that make your website harder to find and use.","focus":"a site search engines can understand"} /-->
<!-- wp:smartleading/locel-seo-tile {"icon":"list","wide":true,"mock":"listings","title":"Listing accuracy","text":"Keep your business name, address, phone number and key details consistent across the listings that matter.","focus":"consistent, trustworthy information"} /-->
<!-- wp:smartleading/locel-seo-tile {"icon":"chat","title":"Review-process guidance","text":"A simple, guideline-friendly way to ask real customers for feedback. No fake, bought or filtered reviews.","focus":"genuine customer proof"} /-->
<!-- /wp:smartleading/locel-seo-approach -->

<!-- wp:smartleading/locel-seo-ticket /-->

<!-- wp:smartleading/locel-seo-case -->
<!-- wp:smartleading/locel-seo-story-item {"label":"Challenge — sample","text":"[Verified summary of the business’s starting situation and what was holding back local enquiries. Two to three sentences.]"} /-->
<!-- wp:smartleading/locel-seo-story-item {"label":"Work — sample","text":"[Verified list of the Local SEO work actually carried out, e.g. profile, content or technical changes. Two to four short points.]"} /-->
<!-- wp:smartleading/locel-seo-story-item {"label":"Outcome — awaiting verified results","text":"[Verified outcome, measurement method and period, published only with client permission.]"} /-->
<!-- /wp:smartleading/locel-seo-case -->

<!-- wp:smartleading/locel-seo-process -->
<!-- wp:smartleading/locel-seo-stair {"number":"01","title":"Understand your business","text":"Your services, the areas you serve and the kinds of enquiries that matter most to you."} /-->
<!-- wp:smartleading/locel-seo-stair {"number":"02","title":"Assess opportunities","text":"Review your profile, website and local search landscape to see where the gaps are."} /-->
<!-- wp:smartleading/locel-seo-stair {"number":"03","title":"Agree priorities","text":"Decide together what to tackle first, based on likely impact and what’s realistic."} /-->
<!-- wp:smartleading/locel-seo-stair {"number":"04","title":"Implement and review","text":"Carry out the agreed work, then review what changed and decide what comes next."} /-->
<!-- /wp:smartleading/locel-seo-process -->

<!-- wp:smartleading/locel-seo-reporting -->
<!-- wp:smartleading/locel-seo-funnel-step {"icon":"eye","title":"Search visibility","text":"Where and how often your business appears in local searches.","question":"Are people seeing you?"} /-->
<!-- wp:smartleading/locel-seo-funnel-step {"icon":"form","title":"Recorded enquiries","text":"Calls, forms and requests that can be tracked. Not every interaction is a lead.","question":"Did they get in touch?"} /-->
<!-- wp:smartleading/locel-seo-funnel-step {"icon":"checksq","title":"Qualified sales outcomes","text":"Which enquiries were genuine opportunities, confirmed with your own records.","question":"Did it turn into real business?"} /-->
<!-- /wp:smartleading/locel-seo-reporting -->

<!-- wp:smartleading/locel-seo-tech -->
<!-- wp:smartleading/locel-seo-slide {"title":"Bring lead sources together","text":"See calls, form submissions and other recorded enquiries in one place instead of several tools.","item1":"Source shown for each enquiry","item2":"Calls and forms side by side","item3":"One view for your team"} /-->
<!-- wp:smartleading/locel-seo-slide {"title":"Track enquiry status","text":"Mark enquiries as new, reviewed, qualified or not a fit, so reports reflect real opportunities.","item1":"Simple status labels","item2":"Separate genuine enquiries from noise","item3":"Notes for follow-up"} /-->
<!-- wp:smartleading/locel-seo-slide {"title":"Report on outcomes","text":"Connect visibility, recorded enquiries and qualified outcomes in one reporting view.","item1":"Three clear reporting levels","item2":"Trends over agreed periods","item3":"No claim of perfect attribution"} /-->
<!-- wp:smartleading/locel-seo-slide {"title":"Share progress clearly","text":"Give owners a plain view of what changed, what it led to and what comes next.","item1":"Plain-language summaries","item2":"Agreed priorities alongside results","item3":"Easy to share with your team"} /-->
<!-- wp:smartleading/locel-seo-stat /-->
<!-- wp:smartleading/locel-seo-stat /-->
<!-- wp:smartleading/locel-seo-stat /-->
<!-- /wp:smartleading/locel-seo-tech -->

<!-- wp:smartleading/locel-seo-working -->
<!-- wp:smartleading/locel-seo-value {"icon":"priorities","title":"Clear priorities","text":"Work is ordered by what is most likely to help, so effort isn’t spread across tasks that don’t matter yet."} /-->
<!-- wp:smartleading/locel-seo-value {"icon":"clip","title":"Explicit scope","text":"You know what is included before work begins, and what isn’t."} /-->
<!-- wp:smartleading/locel-seo-value {"icon":"bars","title":"Meaningful reporting","text":"Reports focused on enquiries and progress you can act on, not just a list of rankings."} /-->
<!-- /wp:smartleading/locel-seo-working -->

<!-- wp:smartleading/locel-seo-compare -->
<!-- wp:smartleading/locel-seo-compare-row {"label":"Who does the work","sls":"The SLS team carries out the agreed work","agency":"Some advise, some implement — ask before you sign","diy":"Your team, usually alongside other duties"} /-->
<!-- wp:smartleading/locel-seo-compare-row {"label":"Local SEO focus","sls":"Local SEO is a core service, across many industries","agency":"Depends on the agency’s specialism","diy":"You know your business best; SEO depth varies"} /-->
<!-- wp:smartleading/locel-seo-compare-row {"label":"Tools","sls":"Agency tools plus SLS Leads CRM for lead data","agency":"Varies — check what’s included in the fee","diy":"You choose, learn and pay for your own tools"} /-->
<!-- wp:smartleading/locel-seo-compare-row {"label":"Reporting focus","sls":"Enquiries and progress, not rankings alone","agency":"Ask whether reports go beyond rankings and traffic","diy":"As detailed as your team has time to make it"} /-->
<!-- wp:smartleading/locel-seo-compare-row {"label":"A good fit when","sls":"You want an outside team with a clear scope","agency":"Their scope and reporting match what you need","diy":"You have the time, skills and steady capacity"} /-->
<!-- /wp:smartleading/locel-seo-compare -->

<!-- wp:smartleading/locel-seo-faq -->
<!-- wp:smartleading/locel-seo-faq-item {"open":true,"question":"What do local SEO services include?","answer":"Local SEO usually covers your Business Profile, listing consistency, service and location content, technical and on-page website improvements, and guidance on earning genuine reviews. The right mix depends on where your business is starting from."} /-->
<!-- wp:smartleading/locel-seo-faq-item {"question":"Is Local SEO the same as Business Profile management?","answer":"No. Managing your Business Profile is one part of Local SEO. Your website, listings elsewhere and reviews also affect how relevant and trustworthy your business appears, and whether visitors get in touch."} /-->
<!-- wp:smartleading/locel-seo-faq-item {"question":"What affects the time needed to see progress?","answer":"It depends on your starting point, how competitive your market is, how much needs fixing, and how quickly changes can be made. Some fixes are quick; building content and reputation takes longer."} /-->
<!-- wp:smartleading/locel-seo-faq-item {"question":"How do I choose the best local SEO company for my business?","answer":"Look for a local SEO agency that explains what it will do and why, sets a clear scope, avoids guarantees about rankings, and reports on enquiries rather than rankings alone. Ask who owns your accounts and data before you start."} /-->
<!-- wp:smartleading/locel-seo-faq-item {"question":"Can tools replace a local SEO service?","answer":"Local SEO tools are useful for tracking listings, checking technical issues and monitoring reviews. They show you data; they don’t decide priorities, write content or make the fixes. Many businesses use tools alongside an expert."} /-->
<!-- wp:smartleading/locel-seo-faq-item {"question":"What does the free audit include?","answer":"[Answer pending owner confirmation of audit scope]"} /-->
<!-- wp:smartleading/locel-seo-faq-item {"question":"How are enquiries measured?","answer":"In general, enquiries are measured by tracking actions that can be recorded, such as calls, form submissions and booking requests, then reviewing which ones were genuine. No tracking setup captures every interaction perfectly. [SLS measurement method pending owner confirmation]"} /-->
<!-- /wp:smartleading/locel-seo-faq -->

<!-- wp:smartleading/locel-seo-audit /-->';
}
