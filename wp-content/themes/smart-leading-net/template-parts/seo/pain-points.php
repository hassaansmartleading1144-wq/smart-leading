<?php
/**
 * SEO page — pain points section.
 *
 * @package Smart_Leading_Net
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$pain_points = sln_get_seo_page_pain_points();
?>

<section class="seo-page__section seo-page__pain" id="seo-problems" aria-labelledby="seo-problems-heading">
	<div class="sls-container">
		<header class="seo-page__section-head seo-page__section-head--center seo-page__reveal">
			<p class="seo-page__eyebrow"><?php esc_html_e( 'The Problem', 'smart-leading-net' ); ?></p>
			<h2 id="seo-problems-heading" class="seo-page__section-title">
				<?php esc_html_e( 'Your Buyers Are Searching. Are They Finding You?', 'smart-leading-net' ); ?>
			</h2>
			<p class="seo-page__section-desc">
				<?php esc_html_e( 'If your site isn\'t showing up for the searches that matter, every click goes to a competitor instead. These are the gaps we see costing businesses revenue every month.', 'smart-leading-net' ); ?>
			</p>
		</header>

		<div class="seo-page__pain-grid">
			<?php foreach ( $pain_points as $card ) : ?>
				<article class="seo-page__pain-card seo-page__reveal">
					<div class="seo-page__pain-icon" aria-hidden="true">
						<?php get_template_part( 'template-parts/seo/icons/pain', null, array( 'icon' => $card['icon'] ) ); ?>
					</div>
					<h3 class="seo-page__pain-title"><?php echo esc_html( $card['title'] ); ?></h3>
					<p class="seo-page__pain-text"><?php echo esc_html( $card['text'] ); ?></p>
				</article>
			<?php endforeach; ?>
		</div>

		<div class="seo-page__pain-foot seo-page__reveal">
			<p>
				<strong><?php esc_html_e( 'Every one of these is fixable.', 'smart-leading-net' ); ?></strong>
				<?php esc_html_e( 'We start with a free audit that pinpoints exactly what\'s costing you traffic.', 'smart-leading-net' ); ?>
			</p>
			<?php
			sln_render_seo_page_button(
				array(
					'text'    => __( 'Get My Free Audit', 'smart-leading-net' ),
					'url'     => '#seo-proposal',
					'variant' => 'primary',
				)
			);
			?>
		</div>
	</div>
</section>
