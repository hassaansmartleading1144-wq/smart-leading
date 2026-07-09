<?php
/**
 * Digital Marketing page — hero section with metric cards.
 *
 * @package Smart_Leading_Net
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$metrics = sln_get_dm_page_hero_metrics();
?>

<section class="dm-page__hero" id="dm-hero" aria-labelledby="dm-hero-heading">
	<div class="sls-container dm-page__hero-inner">
		<div class="dm-page__hero-copy dm-page__reveal">
			<p class="dm-page__eyebrow"><?php esc_html_e( 'Smart Leading Solutions', 'smart-leading-net' ); ?></p>
			<h1 id="dm-hero-heading" class="dm-page__hero-title">
				<?php
				echo wp_kses(
					__( 'Let\'s Turn Your Business Challenges Into <span class="dm-page__hero-highlight">Growth Opportunities.</span>', 'smart-leading-net' ),
					array( 'span' => array( 'class' => true ) )
				);
				?>
			</h1>
			<p class="dm-page__hero-lead">
				<?php esc_html_e( 'A focused digital marketing roadmap — designed to bring your business more leads, stronger revenue, and measurable long-term growth.', 'smart-leading-net' ); ?>
			</p>
			<div class="dm-page__hero-cta">
				<?php
				sln_render_dm_page_button(
					array(
						'text'    => __( 'Book a Free Strategy Call', 'smart-leading-net' ),
						'url'     => sln_get_dm_page_strategy_cta_url(),
						'variant' => 'secondary',
						'arrow'   => true,
					)
				);
				?>
			</div>
		</div>

		<div class="dm-page__hero-visual dm-page__reveal" aria-hidden="true">
			<div class="dm-page__metric-grid">
				<?php foreach ( $metrics as $metric ) : ?>
					<article class="dm-page__metric-card">
						<p class="dm-page__metric-value dm-page__metric-value--<?php echo esc_attr( $metric['tone'] ); ?>">
							<?php echo esc_html( $metric['value'] ); ?>
						</p>
						<p class="dm-page__metric-label"><?php echo esc_html( $metric['label'] ); ?></p>
					</article>
				<?php endforeach; ?>
			</div>
		</div>
	</div>
</section>
