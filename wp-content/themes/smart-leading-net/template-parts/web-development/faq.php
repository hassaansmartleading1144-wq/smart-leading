<?php
/**
 * Web Development — Figma FAQ accordion.
 *
 * @package Smart_Leading_Net
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$section   = sln_get_wd_faq_section();
$faq_items = sln_get_wd_faq_items();

if ( ! sln_wd_row_is_active( $section ) || empty( $faq_items ) ) {
	return;
}
?>

<section class="wd-faq" id="wd-faq" aria-labelledby="wd-faq-heading">
	<div class="wd-wrap">
		<header class="wd-head wd-animate">
			<?php if ( ! empty( $section['small_heading'] ) ) : ?>
				<p class="wd-eyebrow"><?php echo esc_html( $section['small_heading'] ); ?></p>
			<?php endif; ?>
			<h2 id="wd-faq-heading" class="wd-title">
				<?php
				echo esc_html( $section['main_heading'] ?? '' );
				if ( ! empty( $section['highlighted_text'] ) ) {
					echo ' <span class="wd-hl">' . esc_html( $section['highlighted_text'] ) . '</span>';
				}
				?>
			</h2>
			<?php if ( sln_wd_plain_text( $section['description'] ?? '' ) ) : ?>
				<div class="wd-lead"><?php echo sln_wd_format_content( $section['description'] ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></div>
			<?php endif; ?>
		</header>

		<div class="wd-faq__list" data-wd-faq>
			<?php foreach ( $faq_items as $index => $item ) : ?>
				<?php
				$item_id   = 'wd-faq-item-' . ( $index + 1 );
				$button_id = $item_id . '-button';
				$panel_id  = $item_id . '-panel';
				?>
				<div class="wd-faq__item wd-animate" id="<?php echo esc_attr( $item_id ); ?>">
					<h3 class="wd-faq__heading">
						<button
							type="button"
							class="wd-faq__button"
							id="<?php echo esc_attr( $button_id ); ?>"
							aria-expanded="false"
							aria-controls="<?php echo esc_attr( $panel_id ); ?>"
						>
							<span class="wd-faq__icon" aria-hidden="true">Q</span>
							<span class="wd-faq__question"><?php echo esc_html( $item['question'] ?? '' ); ?></span>
							<span class="wd-faq__chev" aria-hidden="true"></span>
						</button>
					</h3>
					<div
						class="wd-faq__panel"
						id="<?php echo esc_attr( $panel_id ); ?>"
						role="region"
						aria-labelledby="<?php echo esc_attr( $button_id ); ?>"
						hidden
					>
						<div class="wd-faq__answer">
							<?php echo sln_wd_format_content( $item['answer'] ?? '' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
						</div>
					</div>
				</div>
			<?php endforeach; ?>
		</div>
	</div>
</section>
