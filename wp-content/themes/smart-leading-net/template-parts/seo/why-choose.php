<?php
/**
 * SEO page — why choose us section.
 *
 * @package Smart_Leading_Net
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$cells = sln_get_seo_page_why_choose();
?>

<section class="seo-page__section seo-page__why" id="seo-why" aria-labelledby="seo-why-heading">
	<div class="sls-container">
		<div class="seo-page__why-top">
			<div class="seo-page__reveal">
				<p class="seo-page__eyebrow seo-page__eyebrow--light"><?php esc_html_e( 'Why Smart Leading', 'smart-leading-net' ); ?></p>
				<h2 id="seo-why-heading" class="seo-page__section-title seo-page__section-title--light">
					<?php esc_html_e( 'SEO That\'s Measured in Revenue, Not Rankings Alone', 'smart-leading-net' ); ?>
				</h2>
			</div>
			<p class="seo-page__why-lead seo-page__reveal">
				<?php esc_html_e( 'Plenty of agencies can move a keyword. We tie every action back to traffic, leads, and revenue you can see in your numbers — and we report on it transparently every month.', 'smart-leading-net' ); ?>
			</p>
		</div>

		<div class="seo-page__why-grid">
			<?php foreach ( $cells as $cell ) : ?>
				<article class="seo-page__why-cell seo-page__reveal">
					<p class="seo-page__why-num">/ <?php echo esc_html( $cell['number'] ); ?></p>
					<h3 class="seo-page__why-title"><?php echo esc_html( $cell['title'] ); ?></h3>
					<p class="seo-page__why-text"><?php echo esc_html( $cell['text'] ); ?></p>
				</article>
			<?php endforeach; ?>
		</div>
	</div>
</section>
