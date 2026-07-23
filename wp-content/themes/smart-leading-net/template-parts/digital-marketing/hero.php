<?php
/**
 * Digital Marketing page — hero section.
 *
 * @package Smart_Leading_Net
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$hero    = sln_get_dm_hero();
$stats   = sln_get_dm_hero_stats();
$metrics = sln_get_dm_dashboard_metrics();

if ( ! sln_dm_row_is_active( $hero ) ) {
	return;
}
?>

<section class="sln-dm-hero sln-dm-section sln-dm-section--dark" aria-labelledby="sln-dm-hero-heading">
	<span class="sln-dm-hero__blob sln-dm-hero__blob--orange" aria-hidden="true"></span>
	<span class="sln-dm-hero__blob sln-dm-hero__blob--blue" aria-hidden="true"></span>

	<div class="sls-container sln-dm-wrap">
		<div class="sln-dm-hero__inner">
			<div class="sln-dm-hero__copy sln-dm-animate">
				<?php if ( ! empty( $hero['small_heading'] ) ) : ?>
					<p class="sln-dm-eyebrow sln-dm-eyebrow--light"><?php echo esc_html( $hero['small_heading'] ); ?></p>
				<?php endif; ?>

				<h1 id="sln-dm-hero-heading" class="sln-dm-hero__title">
					<?php
					echo esc_html( $hero['main_heading'] ?? '' );
					if ( ! empty( $hero['highlighted_text'] ) ) {
						echo ' <span class="sln-dm-hl">' . esc_html( $hero['highlighted_text'] ) . '</span>';
					}
					?>
				</h1>

				<?php if ( sln_dm_plain_text( $hero['description'] ?? '' ) ) : ?>
					<div class="sln-dm-lead sln-dm-lead--light"><?php echo sln_dm_format_content( $hero['description'] ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></div>
				<?php endif; ?>

				<?php if ( ! empty( $hero['primary_button_text'] ) ) : ?>
					<div class="sln-dm-hero__cta">
						<?php
						sln_render_dm_page_button(
							array(
								'text'    => $hero['primary_button_text'],
								'url'     => $hero['primary_button_url'] ?? '#dm-contact',
								'variant' => 'primary',
								'arrow'   => true,
								'class'   => 'sln-dm-cta--hero',
							)
						);
						?>
					</div>
				<?php endif; ?>
			</div>

			<div class="sln-dm-hero__visual sln-dm-animate">
				<div class="sln-dm-dash" data-sln-dm-dash>
					<div class="sln-dm-dash__top">
						<span aria-hidden="true"></span>
						<span aria-hidden="true"></span>
						<span aria-hidden="true"></span>
						<div class="sln-dm-dash__title"><?php echo esc_html( $hero['dashboard_title'] ?? '' ); ?></div>
					</div>
					<div class="sln-dm-dash__body">
						<svg class="sln-dm-dash__chart" viewBox="0 0 300 150" xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
							<g class="sln-dm-dash__bars" fill="currentColor" opacity=".55">
								<rect class="sln-dm-dash__bar" x="10" y="90" width="26" height="50" rx="4"/>
								<rect class="sln-dm-dash__bar" x="52" y="72" width="26" height="68" rx="4"/>
								<rect class="sln-dm-dash__bar" x="94" y="80" width="26" height="60" rx="4"/>
								<rect class="sln-dm-dash__bar" x="136" y="55" width="26" height="85" rx="4"/>
								<rect class="sln-dm-dash__bar" x="178" y="40" width="26" height="100" rx="4"/>
								<rect class="sln-dm-dash__bar" x="220" y="18" width="26" height="122" rx="4"/>
							</g>
							<polyline class="sln-dm-dash__trend" points="23,95 65,78 107,86 149,60 191,46 233,22"/>
						</svg>

						<?php if ( ! empty( $metrics ) ) : ?>
							<div class="sln-dm-dash__metrics" data-sln-dm-countup>
								<?php foreach ( $metrics as $metric ) : ?>
									<?php
									$prefix   = (string) ( $metric['prefix'] ?? '' );
									$value    = (float) ( $metric['value'] ?? 0 );
									$decimals = (int) ( $metric['decimals'] ?? 0 );
									$suffix   = (string) ( $metric['suffix'] ?? '' );
									$display  = $prefix . number_format( $value, $decimals ) . $suffix;
									?>
									<div>
										<span
											class="sln-dm-dash__num sln-dm-stat__count"
											data-pre="<?php echo esc_attr( $prefix ); ?>"
											data-val="<?php echo esc_attr( (string) ( $metric['value'] ?? '0' ) ); ?>"
											data-dec="<?php echo esc_attr( (string) $decimals ); ?>"
											data-suf="<?php echo esc_attr( $suffix ); ?>"
										><?php echo esc_html( $display ); ?></span>
										<span class="sln-dm-dash__label"><?php echo esc_html( $metric['label'] ?? '' ); ?></span>
									</div>
								<?php endforeach; ?>
							</div>
						<?php endif; ?>
					</div>
				</div>

				<?php if ( ! empty( $hero['chip_1_text'] ) ) : ?>
					<div class="sln-dm-float-chip sln-dm-float-chip--a"><?php echo esc_html( $hero['chip_1_text'] ); ?></div>
				<?php endif; ?>
				<?php if ( ! empty( $hero['chip_2_text'] ) ) : ?>
					<div class="sln-dm-float-chip sln-dm-float-chip--b"><?php echo esc_html( $hero['chip_2_text'] ); ?></div>
				<?php endif; ?>
			</div>
		</div>

		<?php if ( ! empty( $stats ) ) : ?>
			<div class="sln-dm-hero__stats sln-dm-statgrid" data-sln-dm-countup>
				<?php foreach ( $stats as $stat ) : ?>
					<?php
					$prefix   = (string) ( $stat['prefix'] ?? '' );
					$value    = (float) ( $stat['number'] ?? 0 );
					$decimals = (int) ( $stat['decimals'] ?? 0 );
					$suffix   = (string) ( $stat['suffix'] ?? '' );
					$display  = $prefix . number_format( $value, $decimals ) . $suffix;
					?>
					<div class="sln-dm-stat sln-dm-animate">
						<div class="sln-dm-stat__num">
							<span
								class="sln-dm-stat__count"
								data-pre="<?php echo esc_attr( $prefix ); ?>"
								data-val="<?php echo esc_attr( (string) ( $stat['number'] ?? '0' ) ); ?>"
								data-dec="<?php echo esc_attr( (string) $decimals ); ?>"
								data-suf="<?php echo esc_attr( $suffix ); ?>"
							><?php echo esc_html( $display ); ?></span>
							<?php if ( ! empty( $stat['unit'] ) ) : ?>
								<span class="sln-dm-stat__unit"><?php echo esc_html( $stat['unit'] ); ?></span>
							<?php endif; ?>
						</div>
						<div class="sln-dm-stat__label"><?php echo esc_html( $stat['label'] ?? '' ); ?></div>
					</div>
				<?php endforeach; ?>
			</div>
		<?php endif; ?>
	</div>
</section>
