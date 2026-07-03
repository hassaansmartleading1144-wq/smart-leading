<?php
/**
 * SEO page — services grid.
 *
 * @package Smart_Leading_Net
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$services = sln_get_seo_page_services();
?>

<section class="seo-page__section seo-page__services" id="seo-services" aria-labelledby="seo-services-heading">
	<div class="sls-container">
		<header class="seo-page__section-head seo-page__section-head--center seo-page__reveal">
			<p class="seo-page__eyebrow"><?php esc_html_e( 'What We Do', 'smart-leading-net' ); ?></p>
			<h2 id="seo-services-heading" class="seo-page__section-title">
				<?php esc_html_e( 'A Complete SEO Program, Built To Perform', 'smart-leading-net' ); ?>
			</h2>
			<p class="seo-page__section-desc">
				<?php esc_html_e( 'Every engagement combines technical foundations, content, and authority-building into one strategy aimed at the same outcome — measurable revenue growth.', 'smart-leading-net' ); ?>
			</p>
		</header>

		<div class="seo-page__svc-grid">
			<?php foreach ( $services as $service ) : ?>
				<article class="seo-page__svc-card seo-page__reveal">
					<div class="seo-page__svc-icon" aria-hidden="true">
						<svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><path d="M14 2v6h6M9 14l2 2 4-4"/></svg>
					</div>
					<h3 class="seo-page__svc-title"><?php echo esc_html( $service['title'] ); ?></h3>
					<p class="seo-page__svc-text"><?php echo esc_html( $service['text'] ); ?></p>
					<ul class="seo-page__svc-list">
						<?php foreach ( $service['items'] as $item ) : ?>
							<li><?php echo sln_seo_page_check_icon(); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?> <?php echo esc_html( $item ); ?></li>
						<?php endforeach; ?>
					</ul>
				</article>
			<?php endforeach; ?>
		</div>
	</div>
</section>
