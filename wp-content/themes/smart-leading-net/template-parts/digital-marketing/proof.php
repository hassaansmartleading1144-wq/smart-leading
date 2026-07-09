<?php
/**
 * Digital Marketing page — proof of work (case studies).
 *
 * @package Smart_Leading_Net
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$cases = sln_get_dm_page_case_studies();
?>

<section class="dm-page__section dm-page__section--tint" aria-labelledby="dm-proof-heading">
	<div class="sls-container">
		<p class="dm-page__eyebrow dm-page__reveal"><?php esc_html_e( 'Proof of Work', 'smart-leading-net' ); ?></p>
		<h2 id="dm-proof-heading" class="dm-page__section-title dm-page__reveal">
			<?php
			echo wp_kses(
				__( 'Real Clients. <span class="dm-page__hl">Real Numbers.</span>', 'smart-leading-net' ),
				array( 'span' => array( 'class' => true ) )
			);
			?>
		</h2>
		<p class="dm-page__lead dm-page__reveal">
			<?php esc_html_e( 'These aren\'t projections. They\'re outcomes from businesses just like yours.', 'smart-leading-net' ); ?>
		</p>
		<div class="dm-page__cases">
			<?php foreach ( $cases as $case ) : ?>
				<article class="dm-page__case dm-page__reveal">
					<div class="dm-page__case-head">
						<h3 class="dm-page__case-name"><?php echo esc_html( $case['name'] ); ?></h3>
						<span class="dm-page__case-tag"><?php echo esc_html( $case['tag'] ); ?></span>
					</div>
					<div class="dm-page__metrics">
						<?php foreach ( $case['metrics'] as $metric ) : ?>
							<div class="dm-page__metric">
								<div class="dm-page__metric-value"><?php echo esc_html( $metric['value'] ); ?></div>
								<div class="dm-page__metric-label"><?php echo esc_html( $metric['label'] ); ?></div>
							</div>
						<?php endforeach; ?>
					</div>
					<blockquote class="dm-page__case-quote">“<?php echo esc_html( $case['quote'] ); ?>”</blockquote>
					<p class="dm-page__case-attr">— <?php echo esc_html( $case['author'] ); ?></p>
				</article>
			<?php endforeach; ?>
		</div>
		<p class="dm-page__disc dm-page__reveal">
			<?php esc_html_e( 'Real client outcomes. Individual results vary by industry, budget and market.', 'smart-leading-net' ); ?>
		</p>
	</div>
</section>