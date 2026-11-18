<?php
/**
 * PPC sub-pages — interactive guide (replaces the Approach card list).
 *
 * Left: clickable steps. Right: panel with an animated illustration and the
 * step's copy. All panels are in the HTML (readable by search engines and
 * without JavaScript); JS turns them into tabs with autoplay and swipe.
 *
 * Copy comes from the Approach section fields; labels, icons and visuals
 * from sln_ppc_guide_steps().
 *
 * @package Smart_Leading_Net
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

require_once __DIR__ . '/_helpers.php';

$sln_args = isset( $args ) && is_array( $args ) ? $args : array();
$section  = $sln_args['section'] ?? sln_get_ppc_approach_section();
$items    = array_values( $sln_args['items'] ?? sln_get_ppc_approach_items() );
$steps    = $sln_args['steps'] ?? sln_ppc_guide_steps();
$gid      = sanitize_key( $sln_args['id'] ?? 'approach' );
$gp       = 'approach' === $gid ? 'sln-ppc-guide' : 'sln-ppc-' . $gid;

if ( ! sln_ppc_row_is_active( $section ) || empty( $items ) ) {
	return;
}

$visual_tag = __( 'Illustration', 'smart-leading-net' );
?>

<section id="<?php echo esc_attr( $gid ); ?>" class="sln-ppc-section sln-ppc-guide-section sln-ppc-guide-section--<?php echo esc_attr( $gid ); ?>" aria-labelledby="<?php echo esc_attr( $gp ); ?>-heading">
	<div class="sls-container">
		<?php sln_ppc_part_render_heading( $section, $gp . '-heading', true, 'azure' ); ?>

		<div class="sln-ppc-guide sln-ppc-reveal" data-sln-ppc-guide>
			<div class="sln-ppc-guide-tabs" role="tablist" aria-label="<?php echo esc_attr( wp_strip_all_tags( ( $section['main_heading'] ?? '' ) . ' ' . ( $section['highlighted_text'] ?? '' ) ) ); ?>">
				<?php foreach ( $items as $index => $item ) : ?>
					<?php
					$step  = $steps[ $index ] ?? array();
					$label = ! empty( $step['label'] ) ? $step['label'] : ( $item['problem'] ?? '' );
					$icon  = sln_ppc_icon( $step['icon'] ?? 'check' );
					?>
					<button
						type="button"
						class="sln-ppc-guide-tab<?php echo 0 === $index ? ' is-active' : ''; ?>"
						role="tab"
						id="<?php echo esc_attr( $gp ); ?>-tab-<?php echo esc_attr( (string) $index ); ?>"
						aria-controls="<?php echo esc_attr( $gp ); ?>-panel-<?php echo esc_attr( (string) $index ); ?>"
						aria-selected="<?php echo 0 === $index ? 'true' : 'false'; ?>"
						tabindex="<?php echo 0 === $index ? '0' : '-1'; ?>"
					>
						<span class="sln-ppc-guide-tab-icon"><?php echo $icon; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></span>
						<span class="sln-ppc-guide-tab-text">
							<span class="sln-ppc-guide-tab-num"><?php echo esc_html( sprintf( '%02d', $index + 1 ) ); ?></span>
							<span class="sln-ppc-guide-tab-label"><?php echo esc_html( $label ); ?></span>
						</span>
						<span class="sln-ppc-guide-tab-bar" aria-hidden="true"><span></span></span>
					</button>
				<?php endforeach; ?>
			</div>

			<div class="sln-ppc-guide-panels" data-sln-ppc-guide-panels>
				<?php foreach ( $items as $index => $item ) : ?>
					<?php
					$step   = $steps[ $index ] ?? array();
					$label  = ! empty( $step['label'] ) ? $step['label'] : ( $item['problem'] ?? '' );
					$visual = (string) ( $step['visual'] ?? '' );
					?>
					<div
						class="sln-ppc-guide-panel<?php echo 0 === $index ? ' is-active' : ''; ?>"
						role="tabpanel"
						id="<?php echo esc_attr( $gp ); ?>-panel-<?php echo esc_attr( (string) $index ); ?>"
						aria-labelledby="<?php echo esc_attr( $gp ); ?>-tab-<?php echo esc_attr( (string) $index ); ?>"
					>
						<?php if ( $visual ) : ?>
							<div class="sln-ppc-gv sln-ppc-gv--<?php echo esc_attr( $visual ); ?>" aria-hidden="true">
								<span class="sln-ppc-gv-tag"><?php echo esc_html( $visual_tag ); ?></span>
								<?php get_template_part( 'template-parts/ppc-google-ads/guide-visual', null, array( 'visual' => $visual ) ); ?>
							</div>
						<?php endif; ?>

						<div class="sln-ppc-guide-copy">
							<span class="sln-ppc-guide-step"><?php echo esc_html( sprintf( /* translators: %s: step number. */ __( 'Step %s', 'smart-leading-net' ), sprintf( '%02d', $index + 1 ) ) ); ?></span>
							<h3><?php echo esc_html( $label ); ?></h3>
							<?php if ( ! empty( $item['problem'] ) ) : ?>
								<p class="sln-ppc-guide-problem"><?php echo esc_html( $item['problem'] ); ?></p>
							<?php endif; ?>
							<div class="sln-ppc-guide-solution"><?php echo sln_ppc_format_content( $item['solution'] ?? '' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></div>
							<?php if ( ! empty( $step['points'] ) ) : ?>
								<ul class="sln-ppc-guide-points">
									<?php foreach ( $step['points'] as $p_i => $point ) : ?>
										<li style="--i:<?php echo (int) $p_i; ?>"><?php echo sln_ppc_icon( 'check' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?><span><?php echo esc_html( $point ); ?></span></li>
									<?php endforeach; ?>
								</ul>
							<?php endif; ?>
							<?php if ( ! empty( $step['result'] ) ) : ?>
								<div class="sln-ppc-guide-result">
									<?php echo sln_ppc_icon( 'trending-up' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
									<span><b><?php esc_html_e( 'What you get:', 'smart-leading-net' ); ?></b> <?php echo esc_html( $step['result'] ); ?></span>
								</div>
							<?php endif; ?>
						</div>
					</div>
				<?php endforeach; ?>

				<div class="sln-ppc-guide-nav" aria-hidden="true">
					<button type="button" class="sln-ppc-guide-arrow" data-dir="-1" tabindex="-1">‹</button>
					<span class="sln-ppc-guide-count"><span data-sln-ppc-guide-current>1</span> / <?php echo esc_html( (string) count( $items ) ); ?></span>
					<button type="button" class="sln-ppc-guide-arrow" data-dir="1" tabindex="-1">›</button>
				</div>
			</div>
		</div>
	</div>
</section>
