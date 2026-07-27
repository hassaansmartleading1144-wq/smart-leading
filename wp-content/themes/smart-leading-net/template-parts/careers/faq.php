<?php
/**
 * Careers — FAQ accordion.
 *
 * @package Smart_Leading_Net
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$faqs = sln_get_careers_faq();
?>

<section class="careers-page__section careers-page__faq" id="careers-faq" aria-labelledby="careers-faq-heading">
	<div class="sls-container careers-page__faq-wrap">
		<div class="careers-page__faq-aside careers-page__reveal">
			<p class="careers-page__eyebrow"><?php esc_html_e( 'FAQ', 'smart-leading-net' ); ?></p>
			<h2 id="careers-faq-heading" class="careers-page__section-title">
				<?php esc_html_e( 'Questions About Working Here', 'smart-leading-net' ); ?>
			</h2>
			<p class="careers-page__faq-lead">
				<?php esc_html_e( 'Still curious? Browse common answers or reach out to our people team through the application form.', 'smart-leading-net' ); ?>
			</p>
			<?php
			sln_render_careers_page_button(
				array(
					'text'    => __( 'Apply Now', 'smart-leading-net' ),
					'url'     => '#careers-apply',
					'variant' => 'primary',
					'class'   => 'careers-page__faq-cta',
				)
			);
			?>
		</div>

		<div class="careers-page__faq-list">
			<?php foreach ( $faqs as $index => $faq ) : ?>
				<div class="careers-page__faq-item">
					<button class="careers-page__faq-q" type="button" aria-expanded="false">
						<span><?php echo esc_html( $faq['question'] ); ?></span>
						<span class="careers-page__faq-ic" aria-hidden="true">
							<svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M12 5v14M5 12h14"/></svg>
						</span>
					</button>
					<div class="careers-page__faq-a">
						<p><?php echo esc_html( $faq['answer'] ); ?></p>
					</div>
				</div>
			<?php endforeach; ?>
		</div>
	</div>

	<?php
	$faq_schema = array(
		'@context'   => 'https://schema.org',
		'@type'      => 'FAQPage',
		'mainEntity' => array(),
	);

	foreach ( $faqs as $faq ) {
		$faq_schema['mainEntity'][] = array(
			'@type'          => 'Question',
			'name'           => $faq['question'],
			'acceptedAnswer' => array(
				'@type' => 'Answer',
				'text'  => $faq['answer'],
			),
		);
	}
	?>
	<script type="application/ld+json"><?php echo wp_json_encode( $faq_schema, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES ); ?></script>
</section>
