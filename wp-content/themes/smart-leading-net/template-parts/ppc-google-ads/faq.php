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

		<?php
		$sln_groups = function_exists( 'sln_ppc_faq_groups' ) ? sln_ppc_faq_groups() : array();
		$sln_bounds = array();
		$sln_start  = 0;
		foreach ( $sln_groups as $sln_g ) {
			$sln_bounds[] = $sln_start;
			$sln_start   += (int) $sln_g[1];
		}
		?>
		<?php if ( $sln_groups ) : ?>
			<div class="sln-faq-tabs sln-ppc-reveal" role="tablist" data-sln-faq-tabs>
				<?php foreach ( $sln_groups as $sln_gi => $sln_g ) : ?>
					<button type="button" role="tab" class="sln-faq-tab<?php echo 0 === $sln_gi ? ' is-on' : ''; ?>" data-group="<?php echo (int) $sln_gi; ?>" aria-selected="<?php echo 0 === $sln_gi ? 'true' : 'false'; ?>"><?php echo esc_html( $sln_g[0] ); ?></button>
				<?php endforeach; ?>
			</div>
		<?php endif; ?>
		<?php if ( ! empty( $items ) ) : ?>
			<div class="sln-ppc-faqs">
				<?php foreach ( $items as $index => $item ) : ?>
					<?php
					$sln_group = 0;
					foreach ( $sln_bounds as $sln_bi => $sln_b ) {
						if ( $index >= $sln_b ) {
							$sln_group = $sln_bi;
						}
					}
					?>
					<details class="sln-ppc-faq sln-ppc-reveal"<?php echo $sln_groups ? ' data-group="' . (int) $sln_group . '"' : ''; ?>>
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
