<?php
/**
 * Web Development — Figma case studies with real images.
 *
 * @package Smart_Leading_Net
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$section = sln_get_wd_cases_section();
$cases   = sln_get_wd_cases();

if ( ! sln_wd_row_is_active( $section ) || empty( $cases ) ) {
	return;
}

$fallbacks = array( 'case-marble.png', 'case-bakery.png', 'case-urgent.png' );
?>

<section class="wd-cases" id="wd-cases" aria-labelledby="wd-cases-heading">
	<div class="wd-wrap">
		<header class="wd-head wd-animate">
			<?php if ( ! empty( $section['small_heading'] ) ) : ?>
				<p class="wd-eyebrow"><?php echo esc_html( $section['small_heading'] ); ?></p>
			<?php endif; ?>
			<h2 id="wd-cases-heading" class="wd-title"><?php echo esc_html( $section['main_heading'] ?? '' ); ?></h2>
			<?php if ( sln_wd_plain_text( $section['description'] ?? '' ) ) : ?>
				<div class="wd-lead"><?php echo sln_wd_format_content( $section['description'] ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></div>
			<?php endif; ?>
		</header>

		<div class="wd-cases__grid">
			<?php foreach ( $cases as $index => $case ) : ?>
				<?php
				$fallback = $fallbacks[ $index ] ?? 'placeholder-project.svg';
				$alt      = (string) ( $case['client_name'] ?? __( 'Case study project', 'smart-leading-net' ) );
				?>
				<article class="wd-cases__card wd-animate">
					<?php
					sln_wd_render_figure(
						array(
							'attachment_id' => absint( $case['image_id'] ?? 0 ),
							'fallback'      => $fallback,
							'alt'           => $alt,
							'width'         => 800,
							'height'        => 500,
							'loading'       => 'lazy',
							'class'         => 'wd-cases__figure',
						)
					);
					?>
					<div class="wd-cases__body">
						<div class="wd-cases__head">
							<h3 class="wd-cases__name"><?php echo esc_html( $case['client_name'] ?? '' ); ?></h3>
							<?php if ( ! empty( $case['industry'] ) ) : ?>
								<span class="wd-cases__industry"><?php echo esc_html( $case['industry'] ); ?></span>
							<?php endif; ?>
						</div>
						<?php if ( ! empty( $case['metric_value'] ) ) : ?>
							<div class="wd-cases__metric"><?php echo esc_html( $case['metric_value'] ); ?></div>
						<?php endif; ?>
						<?php if ( sln_wd_plain_text( $case['metric_description'] ?? '' ) ) : ?>
							<p class="wd-cases__desc"><?php echo esc_html( sln_wd_plain_text( $case['metric_description'] ) ); ?></p>
						<?php endif; ?>
						<?php if ( ! empty( $case['project_url'] ) ) : ?>
							<a class="wd-cases__link" href="<?php echo esc_url( $case['project_url'] ); ?>">
								<?php esc_html_e( 'View project', 'smart-leading-net' ); ?>
								<span aria-hidden="true">→</span>
							</a>
						<?php endif; ?>
					</div>
				</article>
			<?php endforeach; ?>
		</div>
	</div>
</section>
