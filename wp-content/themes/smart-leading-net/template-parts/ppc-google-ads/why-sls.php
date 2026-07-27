<?php
/**
 * PPC & Google Ads page — why SLS section.
 *
 * @package Smart_Leading_Net
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

require_once __DIR__ . '/_helpers.php';

$section = sln_get_ppc_why_section();
$rows    = sln_get_ppc_why_comparison();
$badges  = sln_get_ppc_why_badges();

if ( ! sln_ppc_row_is_active( $section ) ) {
	return;
}
?>

<section id="why" class="sln-ppc-section" aria-labelledby="sln-ppc-why-heading">
	<div class="sls-container">
		<?php sln_ppc_part_render_heading( $section, 'sln-ppc-why-heading', true, 'azure' ); ?>

		<?php if ( ! empty( $rows ) ) : ?>
			<div class="sln-ppc-whycomp sln-ppc-reveal">
				<div class="sln-ppc-why-head">
					<div class="sln-ppc-why-them"><span class="sln-ppc-why-head-dot" aria-hidden="true"></span><?php echo esc_html( $section['left_heading'] ?? __( 'Typical Agency', 'smart-leading-net' ) ); ?></div>
					<div class="sln-ppc-why-us"><span class="sln-ppc-why-head-dot" aria-hidden="true"></span><?php echo esc_html( $section['right_heading'] ?? __( 'Smart Leading Solutions', 'smart-leading-net' ) ); ?></div>
				</div>

				<?php foreach ( $rows as $row ) : ?>
					<div class="sln-ppc-why-row">
						<div class="sln-ppc-why-them">
							<?php sln_ppc_part_check_icon( 'x' ); ?>
							<span><?php echo sln_ppc_format_content( $row['typical'] ?? '' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></span>
						</div>
						<div class="sln-ppc-why-us">
							<?php sln_ppc_part_check_icon( 'check' ); ?>
							<span><?php echo sln_ppc_format_content( $row['sls'] ?? '' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></span>
						</div>
					</div>
				<?php endforeach; ?>
			</div>
		<?php endif; ?>

		<?php if ( ! empty( $badges ) ) : ?>
			<div class="sln-ppc-why-badges sln-ppc-reveal">
				<?php foreach ( $badges as $badge ) : ?>
					<?php if ( empty( $badge['text'] ) ) : ?>
						<?php continue; ?>
					<?php endif; ?>
					<span class="sln-ppc-why-badge"><span class="sln-ppc-why-badge-dot" aria-hidden="true"></span><?php echo esc_html( $badge['text'] ); ?></span>
				<?php endforeach; ?>
			</div>
		<?php endif; ?>
	</div>
</section>
