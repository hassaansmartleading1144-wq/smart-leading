<?php
/**
 * SEO Services — FAQ section.
 *
 * @package Smart_Leading_Net
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$section   = sln_get_seo_services_faq_section();
$faq_items = sln_get_seo_services_faq_items();
$schema    = sln_get_seo_services_faq_schema();
?>

<section class="seo-page__section seo-page__faq" id="seo-faq" aria-labelledby="seo-faq-heading">
	<div class="sls-container seo-page__faq-wrap">
		<div class="seo-page__faq-aside seo-page__reveal">
			<?php if ( ! empty( $section['small_heading'] ) ) : ?>
				<p class="seo-page__eyebrow"><?php echo esc_html( $section['small_heading'] ); ?></p>
			<?php endif; ?>
			<?php if ( ! empty( $section['main_heading'] ) ) : ?>
				<h2 id="seo-faq-heading" class="seo-page__section-title"><?php echo esc_html( $section['main_heading'] ); ?></h2>
			<?php endif; ?>
			<?php if ( sln_seo_services_plain_text( $section['description'] ) ) : ?>
				<div class="seo-page__faq-lead"><?php echo sln_seo_services_format_content( $section['description'] ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></div>
			<?php endif; ?>
			<?php if ( ! empty( $section['cta_button_text'] ) ) : ?>
				<?php
				sln_render_seo_page_button(
					array(
						'text'    => $section['cta_button_text'],
						'url'     => $section['cta_button_url'],
						'variant' => 'primary',
						'class'   => 'seo-page__faq-cta',
					)
				);
				?>
			<?php endif; ?>
		</div>

		<div class="seo-page__faq-list">
			<?php foreach ( $faq_items as $item ) : ?>
				<div class="seo-page__faq-item">
					<button class="seo-page__faq-q" type="button" aria-expanded="false">
						<?php echo esc_html( $item['question'] ); ?>
						<span class="seo-page__faq-ic" aria-hidden="true">
							<svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M12 5v14M5 12h14"/></svg>
						</span>
					</button>
					<div class="seo-page__faq-a">
						<div><?php echo sln_seo_services_format_content( $item['answer'] ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></div>
					</div>
				</div>
			<?php endforeach; ?>
		</div>
	</div>
</section>

<script type="application/ld+json">
<?php echo wp_json_encode( $schema, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES ); ?>
</script>
