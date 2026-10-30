<?php
/**
 * Gutenberg block patterns for Founders Behind.
 *
 * @package Smart_Leading_Net
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Register Founders Behind patterns.
 */
function sln_founders_register_block_patterns() {
	register_block_pattern_category(
		'sln-founders-behind',
		array(
			'label' => __( 'Founders Behind', 'smart-leading-net' ),
		)
	);

	$full = sln_founders_full_page_pattern_content();

	register_block_pattern(
		'sln-founders-behind/full-page',
		array(
			'title'       => __( 'Founders Behind — Full Page', 'smart-leading-net' ),
			'description' => __( 'Complete Founders Behind page with approved sections live and pending sections editable but unpublished.', 'smart-leading-net' ),
			'categories'  => array( 'sln-founders-behind' ),
			'content'     => $full,
		)
	);

	$sections = array(
		'hero'       => __( 'Founders Behind — Hero', 'smart-leading-net' ),
		'profiles'   => __( 'Founders Behind — Founder Profiles', 'smart-leading-net' ),
		'origin'     => __( 'Founders Behind — Origin Story', 'smart-leading-net' ),
		'roles'      => __( 'Founders Behind — Complementary Roles', 'smart-leading-net' ),
		'principles' => __( 'Founders Behind — Principles', 'smart-leading-net' ),
		'cta'        => __( 'Founders Behind — Closing CTA', 'smart-leading-net' ),
	);

	foreach ( $sections as $slug => $title ) {
		if ( ! preg_match( '/<!-- wp:smartleading\/founders-' . preg_quote( $slug, '/' ) . '(?:\s+\{[^}]*\})?\s+(?:\/-->|-->.*?<!-- \/wp:smartleading\/founders-' . preg_quote( $slug, '/' ) . ' -->)/s', $full, $match ) ) {
			continue;
		}
		register_block_pattern(
			'sln-founders-behind/' . $slug,
			array(
				'title'       => $title,
				'description' => $title,
				'categories'  => array( 'sln-founders-behind' ),
				'content'     => $match[0],
			)
		);
	}
}
add_action( 'init', 'sln_founders_register_block_patterns' );

/**
 * Full-page starter pattern markup.
 *
 * @return string
 */
function sln_founders_full_page_pattern_content() {
	$aqib_bio    = 'Aqib Khan is the CEO of Smart Leading Solutions, a growth hacker and marketing specialist. His approach brings audience understanding, digital presence and advertising strategy together around a clear business direction. In his professional writing, he emphasizes consistent engagement, performance measurement and continuous testing as foundations for sustainable growth.';
	$farooq_bio  = 'Farooq Saleh is a Director at Smart Leading Solutions, with a background in data science and executive leadership. His work connects analytical thinking with business priorities, helping translate complex information into a clearer direction for the company.';
	$aqib_json   = wp_json_encode(
		array(
			'name'         => 'Aqib Khan',
			'role1'        => 'CEO',
			'role2'        => 'Growth Hacker',
			'role3'        => 'Marketing Specialist',
			'bio'          => $aqib_bio,
			'socialUrl'    => 'https://www.linkedin.com/in/aqib-khan-sls/',
			'socialLabel'  => 'Connect with Aqib on LinkedIn',
			'socialNewTab' => true,
			'reverse'      => false,
		)
	);
	$farooq_json = wp_json_encode(
		array(
			'name'    => 'Farooq Saleh',
			'role1'   => 'Director',
			'role2'   => 'Data Scientist',
			'role3'   => 'Executive',
			'bio'     => $farooq_bio,
			'reverse' => true,
		)
	);
	$aqib_role   = wp_json_encode(
		array(
			'name' => 'Aqib Khan',
			'role' => 'CEO · Growth Hacker · Marketing Specialist',
			'text' => '',
		)
	);
	$farooq_role = wp_json_encode(
		array(
			'name' => 'Farooq Saleh',
			'role' => 'Director · Data Scientist · Executive',
			'text' => '',
		)
	);

	return '<!-- wp:smartleading/founders-hero /-->

<!-- wp:smartleading/founders-profiles -->
<!-- wp:smartleading/founders-profile ' . $aqib_json . ' /-->
<!-- wp:smartleading/founders-profile ' . $farooq_json . ' /-->
<!-- /wp:smartleading/founders-profiles -->

<!-- wp:smartleading/founders-origin {"isPublished":false} /-->

<!-- wp:smartleading/founders-roles {"isPublished":false} -->
<!-- wp:smartleading/founders-role ' . $aqib_role . ' /-->
<!-- wp:smartleading/founders-role ' . $farooq_role . ' /-->
<!-- /wp:smartleading/founders-roles -->

<!-- wp:smartleading/founders-principles {"isPublished":false} -->
<!-- wp:smartleading/founders-principle {"heading":"Understand the business first.","text":""} /-->
<!-- wp:smartleading/founders-principle {"heading":"Measure what matters.","text":""} /-->
<!-- wp:smartleading/founders-principle {"heading":"Make decisions clear.","text":""} /-->
<!-- /wp:smartleading/founders-principles -->

<!-- wp:smartleading/founders-cta /-->
';
}
