<?php
/**
 * SEO page — process steps.
 *
 * @package Smart_Leading_Net
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$steps = sln_get_seo_page_process_steps();
?>

<section class="seo-page__section seo-page__process" id="seo-process" aria-labelledby="seo-process-heading">
	<div class="sls-container">
		<header class="seo-page__section-head seo-page__section-head--center seo-page__reveal">
			<p class="seo-page__eyebrow"><?php esc_html_e( 'How It Works', 'smart-leading-net' ); ?></p>
			<h2 id="seo-process-heading" class="seo-page__section-title">
				<?php esc_html_e( 'From Discovery to Durable Growth', 'smart-leading-net' ); ?>
			</h2>
			<p class="seo-page__section-desc">
				<?php esc_html_e( 'A clear, proven path that turns an SEO audit into rankings, traffic, and revenue you can rely on month over month.', 'smart-leading-net' ); ?>
			</p>
		</header>

		<div class="seo-page__proc-wrap">
			<div class="seo-page__proc-line" aria-hidden="true"></div>
			<?php foreach ( $steps as $index => $step ) : ?>
				<article class="seo-page__proc-step seo-page__reveal">
					<div class="seo-page__proc-num">
						<?php echo esc_html( (string) ( $index + 1 ) ); ?>
					</div>
					<h3 class="seo-page__proc-title"><?php echo esc_html( $step['title'] ); ?></h3>
					<p class="seo-page__proc-text"><?php echo esc_html( $step['text'] ); ?></p>
				</article>
			<?php endforeach; ?>
		</div>
	</div>
</section>
