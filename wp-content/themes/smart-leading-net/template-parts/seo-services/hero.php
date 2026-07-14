<?php
/**
 * SEO Services — hero section.
 *
 * @package Smart_Leading_Net
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$hero            = sln_get_seo_services_hero();
$contact         = sln_get_seo_page_contact_details();
$serp_positions  = is_array( $hero['serp_positions'] ?? null ) ? $hero['serp_positions'] : array( 9, 6, 4, 2, 1 );
$serp_traffic    = is_array( $hero['serp_traffic'] ?? null ) ? $hero['serp_traffic'] : array( '+12%', '+58%', '+140%', '+255%', '+312%' );
$start_position  = ! empty( $serp_positions[0] ) ? (int) $serp_positions[0] : 9;
$start_traffic   = ! empty( $serp_traffic[0] ) ? (string) $serp_traffic[0] : '+12%';
$hero_image_id   = absint( $hero['hero_image_id'] ?? 0 );
$google_url      = ! empty( $hero['google_partner_url'] ) ? $hero['google_partner_url'] : $contact['google_partner_url'];
?>

<section class="seo-page__hero" id="seo-hero" aria-labelledby="seo-hero-heading">
	<div class="seo-page__hero-grid" aria-hidden="true"></div>
	<div class="sls-container seo-page__hero-inner">
		<div class="seo-page__hero-copy seo-page__reveal">
			<?php if ( ! empty( $hero['small_heading'] ) ) : ?>
				<p class="seo-page__eyebrow seo-page__eyebrow--light"><?php echo esc_html( $hero['small_heading'] ); ?></p>
			<?php endif; ?>
			<h1 id="seo-hero-heading" class="seo-page__hero-title">
				<?php
				echo esc_html( $hero['main_heading'] );
				if ( ! empty( $hero['highlighted_text'] ) ) {
					echo ' <span class="seo-page__hero-highlight">' . esc_html( $hero['highlighted_text'] ) . '</span>';
				}
				?>
			</h1>
			<?php if ( sln_seo_services_plain_text( $hero['description'] ) ) : ?>
				<div class="seo-page__hero-lead"><?php echo sln_seo_services_format_content( $hero['description'] ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></div>
			<?php endif; ?>
			<div class="seo-page__hero-cta">
				<?php if ( ! empty( $hero['primary_button_text'] ) ) : ?>
					<?php
					sln_render_seo_page_button(
						array(
							'text'    => $hero['primary_button_text'],
							'url'     => $hero['primary_button_url'],
							'variant' => 'secondary',
							'arrow'   => true,
						)
					);
					?>
				<?php endif; ?>
				<?php if ( ! empty( $hero['secondary_button_text'] ) ) : ?>
					<?php
					sln_render_seo_page_button(
						array(
							'text'    => $hero['secondary_button_text'],
							'url'     => $hero['secondary_button_url'],
							'variant' => 'white',
						)
					);
					?>
				<?php endif; ?>
			</div>
			<div class="seo-page__hero-trust">
				<?php if ( ! empty( $hero['trust_badge_text'] ) ) : ?>
					<a class="seo-page__gpartner" href="<?php echo esc_url( $google_url ); ?>" target="_blank" rel="noopener noreferrer">
						<span class="seo-page__gpartner-dots" aria-hidden="true">
							<i></i><i></i><i></i><i></i>
						</span>
						<span><?php echo esc_html( $hero['trust_badge_text'] ); ?></span>
					</a>
				<?php endif; ?>
				<?php if ( ! empty( $hero['hero_stat_value'] ) ) : ?>
					<div class="seo-page__trust-item">
						<strong><?php echo esc_html( $hero['hero_stat_value'] ); ?></strong>
						<?php echo esc_html( $hero['hero_stat_label'] ); ?>
					</div>
				<?php endif; ?>
				<?php if ( ! empty( $hero['hero_stat_2_value'] ) ) : ?>
					<div class="seo-page__trust-item">
						<strong><?php echo esc_html( $hero['hero_stat_2_value'] ); ?></strong>
						<?php echo esc_html( $hero['certified_team_text'] ); ?>
					</div>
				<?php endif; ?>
			</div>
		</div>

		<div class="seo-page__hero-visual">
			<?php if ( $hero_image_id ) : ?>
				<div class="seo-page__reveal">
					<?php echo wp_get_attachment_image( $hero_image_id, 'large', false, array( 'class' => 'seo-page__hero-image' ) ); ?>
				</div>
			<?php else : ?>
				<div
					class="seo-page__serp seo-page__reveal"
					role="img"
					aria-label="<?php esc_attr_e( 'Search results panel showing a tracked keyword climbing from position nine to position one over time.', 'smart-leading-net' ); ?>"
					data-positions="<?php echo esc_attr( wp_json_encode( $serp_positions ) ); ?>"
					data-traffic="<?php echo esc_attr( wp_json_encode( $serp_traffic ) ); ?>"
				>
					<div class="seo-page__serp-top">
						<div class="seo-page__serp-query">
							<svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true"><circle cx="11" cy="11" r="7"/><path d="M21 21l-4.3-4.3"/></svg>
							<span><?php echo esc_html( $hero['serp_keyword'] ); ?></span>
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
			<?php endif; ?>
		</div>
	</div>
</section>
