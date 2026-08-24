<?php
/**
 * AEO page — FAQ.
 *
 * @package Smart_Leading_Net
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$items  = sln_aeo_get_faq_items();
$schema = sln_aeo_get_faq_schema();
?>

<section class="sln-aeo-section" id="sln-aeo-faq" aria-labelledby="sln-aeo-faq-heading">
	<div class="sls-container sln-aeo-faq">
		<header class="sln-aeo-head sln-aeo-head--center sln-aeo-reveal">
			<p class="sln-aeo-eyebrow"><?php esc_html_e( 'Questions', 'smart-leading-net' ); ?></p>
			<h2 id="sln-aeo-faq-heading" class="sln-aeo-title"><?php esc_html_e( 'AEO, answered', 'smart-leading-net' ); ?></h2>
		</header>

		<div class="sln-aeo-faq__list sln-aeo-reveal">
			<?php foreach ( $items as $index => $item ) : ?>
				<div class="sln-aeo-faq__item<?php echo 0 === $index ? ' is-open' : ''; ?>">
					<button class="sln-aeo-faq__q" type="button" aria-expanded="<?php echo 0 === $index ? 'true' : 'false'; ?>">
						<?php echo esc_html( $item['question'] ); ?>
						<span class="sln-aeo-faq__ic" aria-hidden="true">
							<?php echo sln_aeo_icon( 'faq' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
						</span>
					</button>
					<div class="sln-aeo-faq__a">
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
