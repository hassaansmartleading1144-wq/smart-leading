<?php
/**
 * SEO Services — reality / pain points section.
 *
 * @package Smart_Leading_Net
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$section = sln_get_seo_services_reality_section();
$cards   = sln_get_seo_services_reality_cards();
?>

<section class="seo-page__section seo-page__pain" id="seo-problems" aria-labelledby="seo-problems-heading">
	<div class="sls-container">
		<header class="seo-page__section-head seo-page__section-head--center seo-page__reveal">
			<?php if ( ! empty( $section['small_heading'] ) ) : ?>
				<p class="seo-page__eyebrow"><?php echo esc_html( $section['small_heading'] ); ?></p>
			<?php endif; ?>
			<?php if ( ! empty( $section['main_heading'] ) ) : ?>
				<h2 id="seo-problems-heading" class="seo-page__section-title"><?php echo esc_html( $section['main_heading'] ); ?></h2>
			<?php endif; ?>
			<?php if ( sln_seo_services_plain_text( $section['description'] ) ) : ?>
				<div class="seo-page__section-desc"><?php echo sln_seo_services_format_content( $section['description'] ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></div>
			<?php endif; ?>
		</header>

		<div class="seo-page__pain-grid">
			<?php foreach ( $cards as $card ) : ?>
				<?php
				$tag   = ! empty( $card['url'] ) ? 'a' : 'article';
				$attrs = ! empty( $card['url'] ) ? ' class="seo-page__pain-card seo-page__reveal" href="' . esc_url( $card['url'] ) . '"' : ' class="seo-page__pain-card seo-page__reveal"';
				?>
				<<?php echo esc_html( $tag ); ?><?php echo $attrs; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>>
					<div class="seo-page__pain-icon" aria-hidden="true">
						<?php if ( ! empty( $card['icon_id'] ) ) : ?>
							<?php echo sln_get_attachment_inline_svg( absint( $card['icon_id'] ), '', true ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
						<?php else : ?>
							<?php get_template_part( 'template-parts/seo/icons/pain', null, array( 'icon' => $card['icon_slug'] ?? 'search-minus' ) ); ?>
						<?php endif; ?>
					</div>
					<h3 class="seo-page__pain-title"><?php echo esc_html( $card['title'] ); ?></h3>
					<div class="seo-page__pain-text"><?php echo sln_seo_services_format_content( $card['description'] ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></div>
				</<?php echo esc_html( $tag ); ?>>
			<?php endforeach; ?>
		</div>

		<?php if ( sln_seo_services_plain_text( $section['cta_text'] ) || ! empty( $section['cta_button_text'] ) ) : ?>
			<div class="seo-page__pain-foot seo-page__reveal">
				<?php if ( sln_seo_services_plain_text( $section['cta_text'] ) ) : ?>
					<p><?php echo sln_seo_services_format_content( $section['cta_text'] ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></p>
				<?php endif; ?>
				<?php if ( ! empty( $section['cta_button_text'] ) ) : ?>
					<?php
					sln_render_seo_page_button(
						array(
							'text'    => $section['cta_button_text'],
							'url'     => $section['cta_button_url'],
							'variant' => 'primary',
						)
					);
					?>
				<?php endif; ?>
			</div>
		<?php endif; ?>
	</div>
</section>
