<?php
/**
 * Case Studies — Growth Page proven results cards.
 *
 * Reuses the Home Page Case Studies markup and CSS classes.
 *
 * @package Smart_Leading_Net
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$data = sln_get_growth_page_case_studies();

if ( empty( $data['cards'] ) ) {
	return;
}

$has_header = ! empty( $data['label'] )
	|| ! empty( $data['main_heading'] )
	|| ! empty( $data['highlight_word'] )
	|| sln_growth_page_wysiwyg_has_content( $data['description'] );
?>

<section
	class="case-studies case-studies-section"
	<?php echo ( function_exists( 'sln_is_seo_services_page' ) && sln_is_seo_services_page() ) ? 'id="seo-results"' : ''; ?>
	aria-labelledby="growth-case-studies-heading"
>
	<div class="sls-container case-studies__container">
		<?php if ( $has_header ) : ?>
			<div class="case-studies__intro">
				<header class="case-studies__header">
					<?php if ( ! empty( $data['label'] ) ) : ?>
						<p class="case-studies__label"><?php echo esc_html( $data['label'] ); ?></p>
					<?php endif; ?>

					<?php if ( ! empty( $data['main_heading'] ) || ! empty( $data['highlight_word'] ) ) : ?>
						<h2 id="growth-case-studies-heading" class="case-studies__heading case-studies__title">
							<?php if ( ! empty( $data['main_heading'] ) ) : ?>
								<?php echo esc_html( $data['main_heading'] ); ?>
							<?php endif; ?>
							<?php if ( ! empty( $data['highlight_word'] ) ) : ?>
								<?php echo ! empty( $data['main_heading'] ) ? ' ' : ''; ?>
								<span class="case-studies__heading-accent"><?php echo esc_html( $data['highlight_word'] ); ?></span>
							<?php endif; ?>
						</h2>
					<?php endif; ?>

					<?php if ( sln_growth_page_wysiwyg_has_content( $data['description'] ) ) : ?>
						<div class="case-studies__description"><?php echo sln_growth_page_format_wysiwyg_content( $data['description'] ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></div>
					<?php endif; ?>
				</header>
			</div>
		<?php endif; ?>

		<div class="case-studies__cards-area">
			<div class="case-studies__grid">
				<?php if ( ! empty( $data['more_link_text'] ) && ! empty( $data['more_link_url'] ) ) : ?>
					<a class="case-studies__link" href="<?php echo esc_url( $data['more_link_url'] ); ?>">
						<?php echo esc_html( $data['more_link_text'] ); ?>
						<span aria-hidden="true">&rarr;</span>
					</a>
				<?php endif; ?>

				<?php foreach ( $data['cards'] as $card ) : ?>
					<article
						class="case-studies__card case-studies__card--custom"
						style="<?php echo esc_attr( sln_growth_page_case_studies_card_style_attr( $card['theme_color'] ) ); ?>"
					>
						<div class="case-studies__card-main">
							<div class="case-studies__card-top">
								<?php if ( ! empty( $card['title'] ) ) : ?>
									<h3 class="case-studies__card-title"><?php echo esc_html( $card['title'] ); ?></h3>
								<?php endif; ?>

								<?php if ( ! empty( $card['icon_id'] ) || ! empty( $card['icon_fallback'] ) ) : ?>
									<div class="case-studies__icon" aria-hidden="true">
										<?php
										if ( ! empty( $card['icon_id'] ) ) {
											echo sln_get_attachment_inline_svg( absint( $card['icon_id'] ), '', true ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
										} else {
											echo sln_get_upload_inline_svg( SLN_GP_CASE_STUDIES_UPLOADS . $card['icon_fallback'], '', true ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
										}
										?>
									</div>
								<?php endif; ?>
							</div>

							<div class="case-studies__card-body">
								<div class="case-studies__metric">
									<?php if ( ! empty( $card['metric_value'] ) ) : ?>
										<p class="case-studies__number"><?php echo esc_html( $card['metric_value'] ); ?></p>
									<?php endif; ?>

									<span class="case-studies__divider" aria-hidden="true"></span>

									<?php if ( ! empty( $card['metric_description'] ) ) : ?>
										<p class="case-studies__text"><?php echo esc_html( $card['metric_description'] ); ?></p>
									<?php endif; ?>
								</div>

								<?php if ( ! empty( $card['chart_file'] ) ) : ?>
									<div class="case-studies__chart" aria-hidden="true">
										<?php
										echo sln_get_upload_inline_svg( SLN_GP_CASE_STUDIES_UPLOADS . $card['chart_file'], 'case-studies__chart-svg', true ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
										?>
									</div>
								<?php endif; ?>
							</div>
						</div>

						<?php if ( ! empty( $card['tags'] ) ) : ?>
							<?php if ( 1 === count( $card['tags'] ) ) : ?>
								<div class="case-studies__footer case-studies__footer--strip">
									<p><?php echo esc_html( $card['tags'][0] ); ?></p>
								</div>
							<?php else : ?>
								<div class="case-studies__footer case-studies__footer--bullets">
									<ul class="case-studies__bullets">
										<?php foreach ( $card['tags'] as $tag ) : ?>
											<li><?php echo esc_html( $tag ); ?></li>
										<?php endforeach; ?>
									</ul>
								</div>
							<?php endif; ?>
						<?php endif; ?>
					</article>
				<?php endforeach; ?>
			</div>
		</div>
	</div>
</section>
