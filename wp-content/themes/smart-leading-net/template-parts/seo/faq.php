<?php
/**
 * SEO page — FAQ accordion (last section).
 *
 * @package Smart_Leading_Net
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$faq_items = sln_get_seo_page_faq_items();
$schema    = sln_get_seo_page_faq_schema();
?>

<section class="seo-page__section seo-page__faq" id="seo-faq" aria-labelledby="seo-faq-heading">
	<div class="sls-container seo-page__faq-wrap">
		<div class="seo-page__faq-aside seo-page__reveal">
			<p class="seo-page__eyebrow"><?php esc_html_e( 'FAQ', 'smart-leading-net' ); ?></p>
			<h2 id="seo-faq-heading" class="seo-page__section-title">
				<?php esc_html_e( 'SEO Questions, Answered', 'smart-leading-net' ); ?>
			</h2>
			<p class="seo-page__faq-lead">
				<?php esc_html_e( 'Still deciding? Here are the things businesses ask us most before getting started.', 'smart-leading-net' ); ?>
			</p>
			<?php
			sln_render_seo_page_button(
				array(
					'text'    => __( 'Ask Us Anything', 'smart-leading-net' ),
					'url'     => '#seo-proposal',
					'variant' => 'primary',
					'class'   => 'seo-page__faq-cta',
				)
			);
			?>
		</div>

		<div class="seo-page__faq-list">
			<?php foreach ( $faq_items as $index => $item ) : ?>
				<div class="seo-page__faq-item<?php echo 0 === $index ? '' : ''; ?>">
					<button class="seo-page__faq-q" type="button" aria-expanded="false">
						<?php echo esc_html( $item['question'] ); ?>
						<span class="seo-page__faq-ic" aria-hidden="true">
							<svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M12 5v14M5 12h14"/></svg>
						</span>
					</button>
					<div class="seo-page__faq-a">
						<p><?php echo esc_html( $item['answer'] ); ?></p>
					</div>
				</div>
			<?php endforeach; ?>
		</div>
	</div>
</section>

<script type="application/ld+json">
<?php echo wp_json_encode( $schema, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES ); ?>
</script>
