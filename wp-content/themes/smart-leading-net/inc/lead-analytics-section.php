<?php
/** Shared official Lead Analytics section for service pages. */
if ( ! defined( 'ABSPATH' ) ) { exit; }

function sln_is_service_dashboard_page() {
 return is_singular( 'growth_page' ) || is_page_template( array( 'ai-seo.php', 'locel-seo.php', 'aeo-page-template.php', 'digital-marketing-page-template.php', 'web-development-page-template.php', 'ppc-google-ads-page-template.php' ) );
}
function sln_lead_analytics_assets() {
 if ( ! sln_is_service_dashboard_page() ) { return; }
 wp_enqueue_style( 'sln-lead-analytics', SLN_THEME_URI . '/assets/css/lead-analytics-section.css', array( 'sln-style' ), filemtime( SLN_THEME_DIR . '/assets/css/lead-analytics-section.css' ) );
 wp_enqueue_script( 'sln-lead-analytics', SLN_THEME_URI . '/assets/js/lead-analytics-section.js', array(), filemtime( SLN_THEME_DIR . '/assets/js/lead-analytics-section.js' ), true );
}
add_action( 'wp_enqueue_scripts', 'sln_lead_analytics_assets', 30 );
function sln_render_lead_analytics_section() {
 static $rendered = false;
 if ( $rendered ) { return; }
 $rendered = true;
 get_template_part( 'template-parts/sections/lead-analytics' );
}
// Consolidate the old reporting concept and CRM feature slider into the real platform section.
function sln_lead_analytics_replace_legacy_block( $html, $block ) {
 if ( ! sln_is_service_dashboard_page() ) { return $html; }
 $html = str_replace( 'SLS Leads CRM', 'Lead Analytics', $html );
 if ( 'smartleading/locel-seo-tech' === $block['blockName'] ) { return ''; }
 if ( 'smartleading/locel-seo-reporting' !== $block['blockName'] ) { return $html; }
 ob_start(); sln_render_lead_analytics_section(); return ob_get_clean();
}
add_filter( 'render_block', 'sln_lead_analytics_replace_legacy_block', 10, 2 );
