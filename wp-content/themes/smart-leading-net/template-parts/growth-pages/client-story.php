<?php
/**
 * Client Story — Growth Page success story section.
 *
 * @package Smart_Leading_Net
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$data    = sln_get_growth_page_client_story();
$section = $data['section'];
$steps   = $data['steps'];
$results = $data['results'];

if ( ! sln_growth_page_client_story_has_content() ) {
	return;
}

$has_header = ! empty( $section['label'] )
	|| ! empty( $section['heading_lead'] )
	|| ! empty( $section['heading_highlight'] )
	|| sln_growth_page_wysiwyg_has_content( $section['description'] );
?>

<section class="client-story" aria-labelledby="client-story-heading">
	<div class="client-story__container sls-container">
		<?php if ( $has_header ) : ?>
			<header class="client-story__header">
				<?php if ( ! empty( $section['label'] ) ) : ?>
					<p class="client-story__label"><?php echo esc_html( $section['label'] ); ?></p>
				<?php endif; ?>

				<?php if ( ! empty( $section['heading_lead'] ) || ! empty( $section['heading_highlight'] ) || ! empty( $section['heading_trail'] ) ) : ?>
					<h2 id="client-story-heading" class="client-story__heading client-story__title">
						<?php if ( ! empty( $section['heading_lead'] ) ) : ?>
							<?php echo esc_html( $section['heading_lead'] ); ?>
						<?php endif; ?>
						<?php if ( ! empty( $section['heading_highlight'] ) ) : ?>
							<span class="client-story__accent"><?php echo esc_html( $section['heading_highlight'] ); ?></span>
						<?php endif; ?>
						<?php if ( ! empty( $section['heading_trail'] ) ) : ?>
							<?php echo ' ' . esc_html( $section['heading_trail'] ); ?>
						<?php endif; ?>
					</h2>
				<?php endif; ?>

				<?php if ( sln_growth_page_wysiwyg_has_content( $section['description'] ) ) : ?>
					<div class="client-story__description client-story__subheading"><?php echo sln_growth_page_format_wysiwyg_content( $section['description'] ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></div>
				<?php endif; ?>
			</header>
		<?php endif; ?>

		<div class="client-story__layout">
			<div class="client-story__main">
				<?php if ( ! empty( $section['challenge_label'] ) || ! empty( $section['challenge_heading'] ) || sln_growth_page_wysiwyg_has_content( $section['challenge_description'] ) ) : ?>
					<div class="client-story__block client-story__block--challenge">
						<?php if ( ! empty( $section['challenge_label'] ) ) : ?>
							<p class="client-story__eyebrow client-story__eyebrow--challenge">
								<span class="client-story__eyebrow-icon" aria-hidden="true">
									<svg width="14" height="14" viewBox="0 0 14 14" fill="none" xmlns="http://www.w3.org/2000/svg">
										<path d="M7 1.5L1.5 12.5H12.5L7 1.5Z" stroke="currentColor" stroke-width="1.5" stroke-linejoin="round"/>
										<path d="M7 5.5V8.5" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"/>
										<circle cx="7" cy="10.5" r="0.75" fill="currentColor"/>
									</svg>
								</span>
								<?php echo esc_html( $section['challenge_label'] ); ?>
							</p>
						<?php endif; ?>

						<?php if ( ! empty( $section['challenge_heading'] ) ) : ?>
							<h3 class="client-story__block-title client-story__subheading"><?php echo esc_html( $section['challenge_heading'] ); ?></h3>
						<?php endif; ?>

						<?php if ( sln_growth_page_wysiwyg_has_content( $section['challenge_description'] ) ) : ?>
							<div class="client-story__block-text client-story__subheading"><?php echo sln_growth_page_format_wysiwyg_content( $section['challenge_description'] ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></div>
						<?php endif; ?>
					</div>
				<?php endif; ?>

				<?php if ( ! empty( $section['strategy_label'] ) || ! empty( $section['strategy_heading'] ) || ! empty( $steps ) ) : ?>
					<div class="client-story__block client-story__block--strategy">
						<?php if ( ! empty( $section['strategy_label'] ) ) : ?>
							<p class="client-story__eyebrow client-story__eyebrow--strategy">
								<span class="client-story__eyebrow-icon" aria-hidden="true">
									<svg width="14" height="14" viewBox="0 0 14 14" fill="none" xmlns="http://www.w3.org/2000/svg">
										<path d="M2 3.5H9.5L12 6V11.5H2V3.5Z" stroke="currentColor" stroke-width="1.5" stroke-linejoin="round"/>
										<path d="M9.5 3.5V6H12" stroke="currentColor" stroke-width="1.5" stroke-linejoin="round"/>
									</svg>
								</span>
								<?php echo esc_html( $section['strategy_label'] ); ?>
							</p>
						<?php endif; ?>

						<?php if ( ! empty( $section['strategy_heading'] ) ) : ?>
							<h3 class="client-story__block-title client-story__subheading"><?php echo esc_html( $section['strategy_heading'] ); ?></h3>
						<?php endif; ?>

						<?php if ( ! empty( $steps ) ) : ?>
							<ul class="client-story__steps">
								<?php foreach ( $steps as $step ) : ?>
									<?php
									$has_step_title = ! empty( $step['title'] );
									$has_step_text  = sln_growth_page_wysiwyg_has_content( $step['description'] );

									if ( ! $has_step_title && ! $has_step_text ) {
										continue;
									}
									?>
									<li class="client-story__step">
										<span class="client-story__step-check" aria-hidden="true">
											<svg width="12" height="12" viewBox="0 0 12 12" fill="none" xmlns="http://www.w3.org/2000/svg">
												<path d="M2.5 6L5 8.5L9.5 3.5" stroke="currentColor" stroke-width="1.75" stroke-linecap="round" stroke-linejoin="round"/>
											</svg>
										</span>
										<div class="client-story__step-content">
											<?php if ( $has_step_title ) : ?>
												<strong class="client-story__step-title"><?php echo esc_html( $step['title'] ); ?>:</strong>
											<?php endif; ?>
											<?php if ( $has_step_text ) : ?>
												<span class="client-story__step-text"><?php echo sln_growth_page_format_inline_wysiwyg_content( $step['description'] ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></span>
											<?php endif; ?>
										</div>
									</li>
								<?php endforeach; ?>
							</ul>
						<?php endif; ?>
					</div>
				<?php endif; ?>
			</div>

			<?php if ( ! empty( $results ) || ! empty( $section['results_title'] ) || ! empty( $section['revenue_number'] ) ) : ?>
				<aside class="client-story__results" aria-label="<?php esc_attr_e( 'Client results', 'smart-leading-net' ); ?>">
					<div class="client-story__results-card">
						<?php if ( ! empty( $section['results_title'] ) ) : ?>
							<h3 class="client-story__results-title">
								<span class="client-story__results-icon" aria-hidden="true">
									<svg width="20" height="20" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg">
										<path d="M6.5 3H13.5L15 6.5V16.5H5V6.5L6.5 3Z" stroke="currentColor" stroke-width="1.5" stroke-linejoin="round"/>
										<path d="M8 10.5L9.5 12L12 9" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
										<path d="M5 6.5H15" stroke="currentColor" stroke-width="1.5"/>
									</svg>
								</span>
								<?php echo esc_html( $section['results_title'] ); ?>
							</h3>
						<?php endif; ?>

						<?php if ( ! empty( $results ) ) : ?>
							<div class="client-story__table-wrap">
								<table class="client-story__table">
									<thead>
										<tr>
											<th scope="col"><?php esc_html_e( 'Metric', 'smart-leading-net' ); ?></th>
											<th scope="col"><?php esc_html_e( 'Before SLS', 'smart-leading-net' ); ?></th>
											<th scope="col"><?php esc_html_e( 'After SLS', 'smart-leading-net' ); ?></th>
										</tr>
									</thead>
									<tbody>
										<?php foreach ( $results as $row ) : ?>
											<tr>
												<th scope="row"><?php echo esc_html( $row['metric'] ); ?></th>
												<td class="client-story__table-before"><?php echo esc_html( $row['before'] ); ?></td>
												<td class="client-story__table-after">
													<span class="client-story__table-check" aria-hidden="true">✓</span>
													<?php echo esc_html( $row['after'] ); ?>
												</td>
											</tr>
										<?php endforeach; ?>
									</tbody>
								</table>
							</div>
						<?php endif; ?>

						<?php if ( ! empty( $section['revenue_number'] ) || ! empty( $section['revenue_label'] ) || sln_growth_page_wysiwyg_has_content( $section['quote'] ) ) : ?>
							<div class="client-story__highlight">
								<?php if ( ! empty( $section['revenue_number'] ) ) : ?>
									<p class="client-story__highlight-number"><?php echo esc_html( $section['revenue_number'] ); ?></p>
								<?php endif; ?>
								<?php if ( ! empty( $section['revenue_label'] ) ) : ?>
									<p class="client-story__highlight-label"><?php echo esc_html( $section['revenue_label'] ); ?></p>
								<?php endif; ?>
								<?php if ( sln_growth_page_wysiwyg_has_content( $section['quote'] ) ) : ?>
									<blockquote class="client-story__quote">
										<?php echo sln_growth_page_format_wysiwyg_content( $section['quote'] ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
									</blockquote>
								<?php endif; ?>
							</div>
						<?php endif; ?>
					</div>
				</aside>
			<?php endif; ?>
		</div>
	</div>
</section>
