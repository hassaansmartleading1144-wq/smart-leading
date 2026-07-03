<?php
/**
 * SEO page — hero section with animated SERP panel.
 *
 * @package Smart_Leading_Net
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$contact = sln_get_seo_page_contact_details();
$trust   = sln_get_seo_page_hero_trust_stats();
$serp    = sln_get_seo_page_serp_data();
$start_position = ! empty( $serp['positions'][0] ) ? (int) $serp['positions'][0] : 9;
$start_traffic  = ! empty( $serp['traffic'][0] ) ? (string) $serp['traffic'][0] : '+12%';
?>

<section class="seo-page__hero" id="seo-hero" aria-labelledby="seo-hero-heading">
	<div class="seo-page__hero-grid" aria-hidden="true"></div>
	<div class="sls-container seo-page__hero-inner">
		<div class="seo-page__hero-copy seo-page__reveal">
			<p class="seo-page__eyebrow seo-page__eyebrow--light"><?php esc_html_e( 'SEO Services', 'smart-leading-net' ); ?></p>
			<h1 id="seo-hero-heading" class="seo-page__hero-title">
				<?php
				echo wp_kses(
					__( 'Own the Searches That <span class="seo-page__hero-highlight">Drive Your Revenue</span>', 'smart-leading-net' ),
					array( 'span' => array( 'class' => true ) )
				);
				?>
			</h1>
			<p class="seo-page__hero-lead">
				<?php esc_html_e( 'We build SEO programs around the keywords your buyers actually search — turning organic visibility into qualified traffic, leads, and measurable revenue you can track in your CRM.', 'smart-leading-net' ); ?>
			</p>
			<div class="seo-page__hero-cta">
				<?php
				sln_render_seo_page_button(
					array(
						'text'    => __( 'Get My Free SEO Proposal', 'smart-leading-net' ),
						'url'     => '#seo-proposal',
						'variant' => 'secondary',
						'arrow'   => true,
					)
				);
				?>
				<?php
				sln_render_seo_page_button(
					array(
						'text'    => __( 'See Client Results', 'smart-leading-net' ),
						'url'     => '#seo-results',
						'variant' => 'white',
					)
				);
				?>
			</div>
			<div class="seo-page__hero-trust">
				<a class="seo-page__gpartner" href="<?php echo esc_url( $contact['google_partner_url'] ); ?>" target="_blank" rel="noopener noreferrer">
					<span class="seo-page__gpartner-dots" aria-hidden="true">
						<i></i><i></i><i></i><i></i>
					</span>
					<span><?php esc_html_e( 'Google Partner Certified', 'smart-leading-net' ); ?></span>
				</a>
				<div class="seo-page__trust-item">
					<strong><?php echo esc_html( $trust['revenue'] ); ?></strong>
					<?php esc_html_e( 'revenue driven', 'smart-leading-net' ); ?>
				</div>
				<div class="seo-page__trust-item">
					<strong><?php echo esc_html( $trust['rating'] ); ?></strong>
					<?php esc_html_e( 'avg. client rating', 'smart-leading-net' ); ?>
				</div>
			</div>
		</div>

		<div class="seo-page__hero-visual">
		<div
			class="seo-page__serp seo-page__reveal"
			role="img"
			aria-label="<?php esc_attr_e( 'Search results panel showing a tracked keyword climbing from position nine to position one over time.', 'smart-leading-net' ); ?>"
			data-positions="<?php echo esc_attr( wp_json_encode( $serp['positions'] ) ); ?>"
			data-traffic="<?php echo esc_attr( wp_json_encode( $serp['traffic'] ) ); ?>"
		>
			<div class="seo-page__serp-top">
				<div class="seo-page__serp-query">
					<svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true"><circle cx="11" cy="11" r="7"/><path d="M21 21l-4.3-4.3"/></svg>
					<span><?php echo esc_html( $serp['keyword'] ); ?></span>
				</div>
				<span class="seo-page__serp-tag">
					<?php esc_html_e( 'RANK #', 'smart-leading-net' ); ?><span id="seo-rank-num"><?php echo esc_html( (string) $start_position ); ?></span>
				</span>
			</div>
			<div class="seo-page__serp-list" id="seo-serp-list">
				<div class="seo-page__serp-row"><span class="seo-page__serp-pos">1</span><div class="seo-page__serp-bars"><span></span><span></span></div></div>
				<div class="seo-page__serp-row"><span class="seo-page__serp-pos">2</span><div class="seo-page__serp-bars"><span></span><span></span></div></div>
				<div class="seo-page__serp-row seo-page__serp-row--you">
					<span class="seo-page__serp-pos" id="seo-you-pos"><?php echo esc_html( (string) $start_position ); ?></span>
					<div class="seo-page__serp-bars"><span></span><span></span></div>
					<span class="seo-page__serp-you-tag"><?php esc_html_e( 'YOUR SITE', 'smart-leading-net' ); ?></span>
				</div>
				<div class="seo-page__serp-row"><span class="seo-page__serp-pos">4</span><div class="seo-page__serp-bars"><span></span><span></span></div></div>
			</div>
			<div class="seo-page__serp-foot">
				<div class="seo-page__serp-metric">
					<strong id="seo-traffic-num"><?php echo esc_html( $start_traffic ); ?></strong>
					<small><?php esc_html_e( 'organic clicks', 'smart-leading-net' ); ?></small>
				</div>
				<div class="seo-page__serp-trend">
					<svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.4" aria-hidden="true"><path d="M23 6l-9.5 9.5-5-5L1 18"/><path d="M17 6h6v6"/></svg>
					<?php esc_html_e( 'climbing', 'smart-leading-net' ); ?>
				</div>
			</div>
		</div>
		</div>
	</div>
</section>
