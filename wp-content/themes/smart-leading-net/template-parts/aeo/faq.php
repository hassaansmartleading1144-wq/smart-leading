<?php
/**
 * AEO page — FAQ.
 *
 * @package Smart_Leading_Net
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$section = sln_aeo_get_faq_section();
$items   = sln_aeo_get_faq_items();
$schema  = sln_aeo_get_faq_schema();
?>

<section class="sln-aeo-section" id="sln-aeo-faq" aria-labelledby="sln-aeo-faq-heading">
	<div class="sls-container sln-aeo-faq">
		<header class="sln-aeo-head sln-aeo-head--center sln-aeo-reveal">
			<p class="sln-aeo-eyebrow"><?php echo esc_html( $section['eyebrow'] ); ?></p>
			<h2 id="sln-aeo-faq-heading" class="sln-aeo-title"><?php echo esc_html( $section['heading'] ); ?></h2>
		</header>

		<div class="sln-aeo-faq__list sln-aeo-reveal">
			<?php foreach ( $items as $index => $item ) : ?>
				<div class="sln-aeo-faq__item<?php echo 0 === $index ? ' is-open' : ''; ?>">
					<button class="sln-aeo-faq__q" type="button" aria-expanded="<?php echo 0 === $index ? 'true' : 'false'; ?>">
						<?php echo esc_html( $item['question'] ?? '' ); ?>
						<span class="sln-aeo-faq__ic" aria-hidden="true">
							<?php echo sln_aeo_icon( 'faq' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
						</span>
					</button>
					<div class="sln-aeo-faq__a">
						<p><?php echo esc_html( sln_aeo_plain_text( $item['answer'] ?? '' ) ); ?></p>
					</div>
				</div>
			<?php endforeach; ?>
		</div>
	</div>
</section>

<?php if ( ! empty( $items ) ) : ?>
<script type="application/ld+json">
<?php echo wp_json_encode( $schema, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES ); ?>
</script>
<?php endif; ?>
