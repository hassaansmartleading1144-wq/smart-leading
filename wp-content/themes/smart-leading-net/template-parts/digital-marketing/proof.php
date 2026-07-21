<?php
/**
 * Digital Marketing page — proof of work (case studies).
 *
 * @package Smart_Leading_Net
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$section = sln_get_dm_proof_section();
$cases   = sln_get_dm_case_studies();

if ( empty( $cases ) ) {
	return;
}
?>

<section class="sln-dm-section sln-dm-section--tint" id="dm-proof" aria-labelledby="sln-dm-proof-heading">
	<div class="sls-container sln-dm-wrap">
		<header class="sln-dm-section__head sln-dm-animate">
			<div class="sln-dm-rule" aria-hidden="true"></div>
			<?php if ( ! empty( $section['small_heading'] ) ) : ?>
				<p class="sln-dm-eyebrow"><?php echo esc_html( $section['small_heading'] ); ?></p>
			<?php endif; ?>
			<h2 id="sln-dm-proof-heading" class="sln-dm-title">
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

		<div class="sln-dm-case-grid">
			<?php foreach ( $cases as $case ) : ?>
				<?php
				$tag   = ! empty( $case['url'] ) ? 'a' : 'article';
				$attrs = ' class="sln-dm-case sln-dm-animate"';
				if ( ! empty( $case['url'] ) ) {
					$attrs .= ' href="' . esc_url( $case['url'] ) . '"';
				}
				$metrics = is_array( $case['metrics'] ?? null ) ? $case['metrics'] : array();
				?>
				<<?php echo esc_html( $tag ); ?><?php echo $attrs; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>>
					<div class="sln-dm-case__head">
						<h3 class="sln-dm-case__name"><?php echo esc_html( $case['name'] ?? '' ); ?></h3>
						<?php if ( ! empty( $case['tag'] ) ) : ?>
							<span class="sln-dm-case__tag"><?php echo esc_html( $case['tag'] ); ?></span>
						<?php endif; ?>
					</div>

					<?php if ( ! empty( $metrics ) ) : ?>
						<div class="sln-dm-case__metrics">
							<?php foreach ( $metrics as $metric ) : ?>
								<div class="sln-dm-case__metric">
									<div class="sln-dm-case__metric-value"><?php echo esc_html( $metric['value'] ?? '' ); ?></div>
									<div class="sln-dm-case__metric-label"><?php echo esc_html( $metric['label'] ?? '' ); ?></div>
								</div>
							<?php endforeach; ?>
						</div>
					<?php endif; ?>

					<?php if ( sln_dm_plain_text( $case['quote'] ?? '' ) ) : ?>
						<blockquote class="sln-dm-case__quote">“<?php echo esc_html( sln_dm_plain_text( $case['quote'] ) ); ?>”</blockquote>
					<?php endif; ?>

					<?php if ( ! empty( $case['attribution'] ) ) : ?>
						<p class="sln-dm-case__attr"><?php echo esc_html( $case['attribution'] ); ?></p>
					<?php endif; ?>
				</<?php echo esc_html( $tag ); ?>>
			<?php endforeach; ?>
		</div>

		<?php if ( sln_dm_plain_text( $section['disclaimer'] ?? '' ) ) : ?>
			<p class="sln-dm-case__disc sln-dm-animate"><?php echo esc_html( sln_dm_plain_text( $section['disclaimer'] ) ); ?></p>
		<?php endif; ?>
	</div>
</section>
