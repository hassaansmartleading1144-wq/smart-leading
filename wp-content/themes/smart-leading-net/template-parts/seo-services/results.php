<?php
/**
 * SEO Services — results / why choose section.
 *
 * @package Smart_Leading_Net
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$section = sln_get_seo_services_results_section();
$blocks  = sln_get_seo_services_results_blocks();
?>

<section class="seo-page__section seo-page__why" id="seo-why" aria-labelledby="seo-why-heading">
	<div class="sls-container">
		<div class="seo-page__why-top">
			<div class="seo-page__reveal">
				<?php if ( ! empty( $section['small_heading'] ) ) : ?>
					<p class="seo-page__eyebrow seo-page__eyebrow--light"><?php echo esc_html( $section['small_heading'] ); ?></p>
				<?php endif; ?>
				<?php if ( ! empty( $section['main_heading'] ) || ! empty( $section['highlighted_word'] ) ) : ?>
					<h2 id="seo-why-heading" class="seo-page__section-title seo-page__section-title--light">
						<?php if ( ! empty( $section['main_heading'] ) ) : ?>
							<?php echo esc_html( $section['main_heading'] ); ?>
						<?php endif; ?>
						<?php if ( ! empty( $section['highlighted_word'] ) ) : ?>
							<?php echo ! empty( $section['main_heading'] ) ? ' ' : ''; ?>
							<span class="seo-page__hero-highlight"><?php echo esc_html( $section['highlighted_word'] ); ?></span>
						<?php endif; ?>
					</h2>
				<?php endif; ?>
			</div>
			<?php if ( sln_seo_services_plain_text( $section['description'] ) ) : ?>
				<div class="seo-page__why-lead seo-page__reveal"><?php echo sln_seo_services_format_content( $section['description'] ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></div>
			<?php endif; ?>
		</div>

		<div class="seo-page__why-grid">
			<?php foreach ( $blocks as $cell ) : ?>
				<?php
				$tag   = ! empty( $cell['url'] ) ? 'a' : 'article';
				$attrs = ! empty( $cell['url'] ) ? ' class="seo-page__why-cell seo-page__reveal" href="' . esc_url( $cell['url'] ) . '"' : ' class="seo-page__why-cell seo-page__reveal"';
				?>
				<<?php echo esc_html( $tag ); ?><?php echo $attrs; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>>
					<p class="seo-page__why-num">/ <?php echo esc_html( $cell['number'] ); ?></p>
					<h3 class="seo-page__why-title"><?php echo esc_html( $cell['label'] ); ?></h3>
					<div class="seo-page__why-text"><?php echo sln_seo_services_format_content( $cell['description'] ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></div>
				</<?php echo esc_html( $tag ); ?>>
			<?php endforeach; ?>
		</div>
	</div>
</section>
