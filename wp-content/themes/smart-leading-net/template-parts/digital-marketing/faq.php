<?php
/**
 * Digital Marketing page — FAQ accordion.
 *
 * @package Smart_Leading_Net
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$section   = sln_get_dm_faq_section();
$faq_items = sln_get_dm_faq_items();

if ( empty( $faq_items ) ) {
	return;
}
?>

<section class="sln-dm-section sln-dm-section--tint" id="dm-faq" aria-labelledby="sln-dm-faq-heading">
	<div class="sls-container sln-dm-wrap">
		<header class="sln-dm-section__head sln-dm-animate">
			<div class="sln-dm-rule" aria-hidden="true"></div>
			<?php if ( ! empty( $section['small_heading'] ) ) : ?>
				<p class="sln-dm-eyebrow"><?php echo esc_html( $section['small_heading'] ); ?></p>
			<?php endif; ?>
			<h2 id="sln-dm-faq-heading" class="sln-dm-title">
				<?php
				echo esc_html( $section['main_heading'] ?? '' );
				if ( ! empty( $section['highlighted_text'] ) ) {
					echo ' <span class="sln-dm-hl">' . esc_html( $section['highlighted_text'] ) . '</span>';
				}
				?>
			</h2>
			<?php if ( sln_dm_plain_text( $section['description'] ?? '' ) ) : ?>
				<div class="sln-dm-lead"><?php echo sln_dm_format_content( $section['description'] ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></div>
			<?php endif; ?>
		</header>

		<div class="sln-dm-faq-grid">
			<?php foreach ( $faq_items as $index => $item ) : ?>
				<details class="sln-dm-faq-item sln-dm-animate">
					<summary>
						<span class="sln-dm-faq-item__q" aria-hidden="true">Q</span>
						<span class="sln-dm-faq-item__question"><?php echo esc_html( $item['question'] ?? '' ); ?></span>
						<span class="sln-dm-faq-item__chev" aria-hidden="true">▼</span>
					</summary>
					<div class="sln-dm-faq-item__answer" id="sln-dm-faq-answer-<?php echo esc_attr( (string) ( $index + 1 ) ); ?>">
						<?php echo sln_dm_format_content( $item['answer'] ?? '' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
					</div>
				</details>
			<?php endforeach; ?>
		</div>
	</div>
</section>
