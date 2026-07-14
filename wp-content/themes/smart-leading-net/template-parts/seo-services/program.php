<?php
/**
 * SEO Services — program / services section.
 *
 * @package Smart_Leading_Net
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$section = sln_get_seo_services_program_section();
$cards   = sln_get_seo_services_program_cards();
?>

<section class="seo-page__section seo-page__services" id="seo-services" aria-labelledby="seo-services-heading">
	<div class="sls-container">
		<header class="seo-page__section-head seo-page__section-head--center seo-page__reveal">
			<?php if ( ! empty( $section['small_heading'] ) ) : ?>
				<p class="seo-page__eyebrow"><?php echo esc_html( $section['small_heading'] ); ?></p>
			<?php endif; ?>
			<?php if ( ! empty( $section['main_heading'] ) ) : ?>
				<h2 id="seo-services-heading" class="seo-page__section-title"><?php echo esc_html( $section['main_heading'] ); ?></h2>
			<?php endif; ?>
			<?php if ( sln_seo_services_plain_text( $section['description'] ) ) : ?>
				<div class="seo-page__section-desc"><?php echo sln_seo_services_format_content( $section['description'] ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></div>
			<?php endif; ?>
		</header>

		<div class="seo-page__svc-grid">
			<?php foreach ( $cards as $service ) : ?>
				<article class="seo-page__svc-card seo-page__reveal">
					<div class="seo-page__svc-icon" aria-hidden="true">
						<?php if ( ! empty( $service['icon_id'] ) ) : ?>
							<?php echo sln_get_attachment_inline_svg( absint( $service['icon_id'] ), '', true ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
						<?php else : ?>
							<svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><path d="M14 2v6h6M9 14l2 2 4-4"/></svg>
						<?php endif; ?>
					</div>
					<h3 class="seo-page__svc-title"><?php echo esc_html( $service['title'] ); ?></h3>
					<div class="seo-page__svc-text"><?php echo sln_seo_services_format_content( $service['description'] ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></div>
					<?php if ( ! empty( $service['bullets'] ) && is_array( $service['bullets'] ) ) : ?>
						<ul class="seo-page__svc-list">
							<?php foreach ( $service['bullets'] as $item ) : ?>
								<li><?php echo sln_seo_page_check_icon(); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?> <?php echo esc_html( $item ); ?></li>
							<?php endforeach; ?>
						</ul>
					<?php endif; ?>
					<?php if ( ! empty( $service['link_text'] ) && ! empty( $service['link_url'] ) ) : ?>
						<p><a href="<?php echo esc_url( $service['link_url'] ); ?>"><?php echo esc_html( $service['link_text'] ); ?></a></p>
					<?php endif; ?>
				</article>
			<?php endforeach; ?>
		</div>
	</div>
</section>
