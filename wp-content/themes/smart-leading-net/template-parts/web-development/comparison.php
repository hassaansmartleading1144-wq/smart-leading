<?php
/**
 * Web Development — Figma comparison table.
 *
 * @package Smart_Leading_Net
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$section = sln_get_wd_compare_section();
$rows    = sln_get_wd_compare_rows();

if ( ! sln_wd_row_is_active( $section ) || empty( $rows ) ) {
	return;
}

$benefits_section = sln_get_wd_benefits_section();
$benefits         = sln_get_wd_benefits();
?>

<section class="wd-compare" aria-labelledby="wd-compare-heading">
	<div class="wd-wrap">
		<header class="wd-head wd-animate">
			<?php if ( ! empty( $section['small_heading'] ) ) : ?>
				<p class="wd-eyebrow wd-eyebrow--orange"><?php echo esc_html( $section['small_heading'] ); ?></p>
			<?php endif; ?>
			<h2 id="wd-compare-heading" class="wd-title">
				<?php
				echo esc_html( $section['main_heading'] ?? '' );
				if ( ! empty( $section['highlighted_text'] ) ) {
					echo ' <span class="wd-hl">' . esc_html( $section['highlighted_text'] ) . '</span>';
				}
				?>
			</h2>
			<?php if ( sln_wd_plain_text( $section['description'] ?? '' ) ) : ?>
				<div class="wd-lead wd-lead--center"><?php echo sln_wd_format_content( $section['description'] ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></div>
			<?php endif; ?>
		</header>

		<div class="wd-compare__shell wd-animate">
			<div class="wd-compare__scroll" tabindex="0" role="region" aria-label="<?php esc_attr_e( 'Comparison table', 'smart-leading-net' ); ?>">
				<table class="wd-compare__table">
					<thead>
						<tr>
							<th scope="col" class="wd-compare__col--feat"><?php echo esc_html( $section['col_features'] ?? __( 'Features', 'smart-leading-net' ) ); ?></th>
							<th scope="col" class="wd-compare__col--sl"><?php echo esc_html( $section['col_sl'] ?? __( 'Smart Leading', 'smart-leading-net' ) ); ?></th>
							<th scope="col"><?php echo esc_html( $section['col_inhouse'] ?? __( 'Doing it in-house', 'smart-leading-net' ) ); ?></th>
							<th scope="col"><?php echo esc_html( $section['col_agency'] ?? __( 'Typical agency', 'smart-leading-net' ) ); ?></th>
						</tr>
					</thead>
					<tbody>
						<?php foreach ( $rows as $row ) : ?>
							<?php
							$sl_val      = (string) ( $row['smart_leading'] ?? '' );
							$in_house    = (string) ( $row['in_house'] ?? '' );
							$agency      = (string) ( $row['agency'] ?? '' );
							$sl_positive = (bool) preg_match( '/^(always|yes|30\s*days|on\s+final)/i', trim( $sl_val ) );
							?>
							<tr>
								<th scope="row"><?php echo esc_html( $row['feature'] ?? '' ); ?></th>
								<td class="wd-compare__col--sl<?php echo $sl_positive ? ' is-positive' : ''; ?>">
									<?php if ( $sl_positive ) : ?>
										<span class="wd-compare__mark wd-compare__mark--yes" aria-hidden="true">✓</span>
									<?php endif; ?>
									<?php echo esc_html( $sl_val ); ?>
								</td>
								<td>
									<span class="wd-compare__mark wd-compare__mark--muted" aria-hidden="true">–</span>
									<?php echo esc_html( $in_house ); ?>
								</td>
								<td>
									<span class="wd-compare__mark wd-compare__mark--no" aria-hidden="true">–</span>
									<?php echo esc_html( $agency ); ?>
								</td>
							</tr>
						<?php endforeach; ?>
					</tbody>
				</table>
			</div>

			<?php if ( sln_wd_row_is_active( $benefits_section ) && ! empty( $benefits ) ) : ?>
				<div class="wd-compare__benefits">
					<?php foreach ( $benefits as $benefit ) : ?>
						<article class="wd-compare__benefit">
							<p class="wd-compare__benefit-main"><?php echo esc_html( $benefit['main_text'] ?? '' ); ?></p>
							<?php if ( ! empty( $benefit['supporting_text'] ) ) : ?>
								<p class="wd-compare__benefit-support"><?php echo esc_html( $benefit['supporting_text'] ); ?></p>
							<?php endif; ?>
						</article>
					<?php endforeach; ?>
				</div>
			<?php endif; ?>
		</div>
	</div>
</section>
