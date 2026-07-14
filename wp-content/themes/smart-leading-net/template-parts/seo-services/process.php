<?php
/**
 * SEO Services — process section.
 *
 * @package Smart_Leading_Net
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$section = sln_get_seo_services_process_section();
$steps   = sln_get_seo_services_process_steps();
?>

<section class="seo-page__section seo-page__process" id="seo-process" aria-labelledby="seo-process-heading">
	<div class="sls-container">
		<header class="seo-page__section-head seo-page__section-head--center seo-page__reveal">
			<?php if ( ! empty( $section['small_heading'] ) ) : ?>
				<p class="seo-page__eyebrow"><?php echo esc_html( $section['small_heading'] ); ?></p>
			<?php endif; ?>
			<?php if ( ! empty( $section['main_heading'] ) ) : ?>
				<h2 id="seo-process-heading" class="seo-page__section-title"><?php echo esc_html( $section['main_heading'] ); ?></h2>
			<?php endif; ?>
			<?php if ( sln_seo_services_plain_text( $section['description'] ) ) : ?>
				<div class="seo-page__section-desc"><?php echo sln_seo_services_format_content( $section['description'] ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></div>
			<?php endif; ?>
		</header>

		<div class="seo-page__proc-wrap">
			<div class="seo-page__proc-line" aria-hidden="true"></div>
			<?php foreach ( $steps as $index => $step ) : ?>
				<?php
				$step_number = '' !== (string) ( $step['step_number'] ?? '' ) ? (string) $step['step_number'] : (string) ( $index + 1 );
				$tag         = ! empty( $step['url'] ) ? 'a' : 'article';
				$attrs       = ! empty( $step['url'] ) ? ' class="seo-page__proc-step seo-page__reveal" href="' . esc_url( $step['url'] ) . '"' : ' class="seo-page__proc-step seo-page__reveal"';
				?>
				<<?php echo esc_html( $tag ); ?><?php echo $attrs; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>>
					<div class="seo-page__proc-num"><?php echo esc_html( $step_number ); ?></div>
					<h3 class="seo-page__proc-title"><?php echo esc_html( $step['title'] ); ?></h3>
					<div class="seo-page__proc-text"><?php echo sln_seo_services_format_content( $step['description'] ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></div>
				</<?php echo esc_html( $tag ); ?>>
			<?php endforeach; ?>
		</div>
	</div>
</section>
