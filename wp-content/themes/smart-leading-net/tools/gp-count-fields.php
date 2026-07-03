<?php
/**
 * Estimate Growth Page admin POST variable count from default data structures.
 */
define( 'WP_USE_THEMES', false );
require dirname( __DIR__, 4 ) . '/wp-load.php';

/**
 * Count nested array keys as PHP would in $_POST.
 *
 * @param mixed  $data   Data.
 * @param string $prefix Prefix.
 * @return int
 */
function gp_count_post_vars( $data, $prefix = '' ) {
	if ( ! is_array( $data ) ) {
		return 1;
	}

	$count = 0;
	foreach ( $data as $key => $value ) {
		$count += gp_count_post_vars( $value, $prefix . '[' . $key . ']' );
	}
	return $count;
}

$sections = array(
	'banner' => array(
		'sln_gp_banner_small_heading' => 'x',
		'sln_gp_banner_main_heading'  => 'x',
		'sln_gp_banner_highlight_word'=> 'x',
		'sln_gp_banner_description'   => 'x',
		'sln_gp_banner_primary_btn_text' => 'x',
		'sln_gp_banner_primary_btn_url'  => 'x',
		'sln_gp_banner_secondary_btn_text' => 'x',
		'sln_gp_banner_secondary_btn_url'  => 'x',
		'sln_gp_banner_image_id'      => '0',
	),
	'section_orders' => sln_get_growth_page_default_section_orders(),
	'services_section' => sln_get_growth_page_default_services_section(),
	'services_cards' => sln_get_growth_page_default_service_cards(),
	'client_story' => sln_get_growth_page_default_client_story_section(),
	'client_story_steps' => sln_get_growth_page_default_client_story_steps(),
	'client_story_results' => sln_get_growth_page_default_client_story_results(),
	'how_work_section' => sln_get_growth_page_default_how_work_section(),
	'how_work_tabs' => sln_get_growth_page_default_how_work_tabs(),
	'growth_services' => sln_get_growth_page_default_growth_services_section(),
	'growth_services_cards' => sln_get_growth_page_default_growth_services_cards(),
	'case_studies' => sln_get_growth_page_default_case_studies_section(),
	'case_studies_cards' => sln_get_growth_page_default_case_studies_cards(),
	'why_choose' => sln_get_growth_page_default_why_choose_section(),
	'why_choose_rows' => sln_get_growth_page_default_why_choose_rows(),
	'price_plan' => sln_get_growth_page_default_price_plan_section(),
	'price_plan_cards' => sln_get_growth_page_default_price_plan_cards(),
	'testimonials' => sln_get_growth_page_default_testimonials_section(),
	'testimonials_stats' => sln_get_growth_page_default_testimonials_stats(),
	'testimonials_summary' => sln_get_growth_page_default_testimonials_summary(),
	'testimonials_reviews' => sln_get_growth_page_default_testimonials_reviews(),
	'cta_banner' => sln_get_growth_page_default_cta_banner_section(),
);

$nonces = 11; // one per metabox
$core   = 90; // WP admin core fields rough estimate

$total = $nonces + $core;
foreach ( $sections as $label => $data ) {
	$n = gp_count_post_vars( $data );
	echo str_pad( $label, 24 ) . $n . "\n";
	$total += $n;
}

echo str_repeat( '-', 40 ) . "\n";
echo "Estimated metabox POST vars: " . ( $total - $core - $nonces ) . "\n";
echo "Estimated TOTAL POST vars:   {$total}\n";
echo "max_input_vars (CLI):        " . ini_get( 'max_input_vars' ) . "\n";
echo "EXCEEDS 1000 LIMIT:          " . ( $total > 1000 ? 'YES — ROOT CAUSE' : 'no' ) . "\n";
