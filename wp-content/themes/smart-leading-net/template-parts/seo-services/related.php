<?php
/**
 * SEO Services — related SEO service pages.
 *
 * On the main SEO page this lists its sub-pages; on a sub-page it lists the
 * other SEO services. Built automatically from published pages.
 *
 * @package Smart_Leading_Net
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$sln_related = sln_seo_services_related_pages();

if ( ! $sln_related ) {
	return;
}

$sln_hub    = sln_seo_services_hub_page();
$sln_is_hub = $sln_hub instanceof WP_Post && (int) $sln_hub->ID === (int) get_queried_object_id();
?>

<section class="seo-page__section seo-page__related" id="seo-related" aria-labelledby="seo-related-heading">
	<div class="sls-container">
		<header class="seo-page__section-head seo-page__section-head--center seo-page__reveal">
			<p class="seo-page__eyebrow"><?php esc_html_e( 'SEO SERVICES', 'smart-leading-net' ); ?></p>
			<h2 id="seo-related-heading" class="seo-page__section-title">
				<?php echo $sln_is_hub ? esc_html__( 'Explore Our SEO Services', 'smart-leading-net' ) : esc_html__( 'More Ways We Can Help You Rank', 'smart-leading-net' ); ?>
			</h2>
		</header>

		<ul class="seo-page__related-grid" role="list">
			<?php foreach ( $sln_related as $sln_page ) : ?>
				<li class="seo-page__reveal">
					<a class="seo-page__related-card" href="<?php echo esc_url( get_permalink( $sln_page ) ); ?>">
						<span class="seo-page__related-title"><?php echo esc_html( get_the_title( $sln_page ) ); ?></span>
						<?php $sln_summary = sln_seo_services_related_summary( $sln_page ); ?>
						<?php if ( '' !== $sln_summary ) : ?>
							<span class="seo-page__related-desc"><?php echo esc_html( $sln_summary ); ?></span>
						<?php endif; ?>
						<span class="seo-page__related-more" aria-hidden="true">
							<?php esc_html_e( 'Learn more', 'smart-leading-net' ); ?>
							<svg viewBox="0 0 24 24" width="16" height="16" fill="none" stroke="currentColor" stroke-width="2"><path d="M5 12h14m-5-5 5 5-5 5"/></svg>
						</span>
					</a>
				</li>
			<?php endforeach; ?>
		</ul>

		<?php if ( ! $sln_is_hub && $sln_hub instanceof WP_Post ) : ?>
			<p class="seo-page__related-all seo-page__reveal">
				<a href="<?php echo esc_url( get_permalink( $sln_hub ) ); ?>"><?php esc_html_e( 'View all SEO services', 'smart-leading-net' ); ?></a>
			</p>
		<?php endif; ?>
	</div>
</section>
