<?php
/**
 * PPC & Google Ads page — FAQ section.
 *
 * @package Smart_Leading_Net
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

require_once __DIR__ . '/_helpers.php';

$section = sln_get_ppc_faq_section();
$items   = sln_get_ppc_faq_items();

if ( ! sln_ppc_row_is_active( $section ) ) {
	return;
}
?>

<section id="faq" class="sln-ppc-section" aria-labelledby="sln-ppc-faq-heading">
	<div class="sls-container">
		<?php sln_ppc_part_render_heading( $section, 'sln-ppc-faq-heading', true, 'orange' ); ?>

		<?php if ( ! empty( $items ) ) : ?>
			<div class="sln-ppc-faqs">
				<?php foreach ( $items as $index => $item ) : ?>
					<details class="sln-ppc-faq sln-ppc-reveal">
						<summary>
							<span class="sln-ppc-faq-question-num"><?php echo esc_html( 'Q' . ( $index + 1 ) ); ?></span>
							<span class="sln-ppc-faq-question"><?php echo esc_html( $item['question'] ?? '' ); ?></span>
							<span class="sln-ppc-faq-toggle" aria-hidden="true">+</span>
						</summary>
						<?php if ( sln_ppc_plain_text( $item['answer'] ?? '' ) ) : ?>
							<div class="sln-ppc-faq-answer"><?php echo sln_ppc_format_content( $item['answer'] ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></div>
						<?php endif; ?>
					</details>
				<?php endforeach; ?>
			</div>
		<?php endif; ?>
	</div>
</section>
