<?php
/**
 * Portfolio page — Contact-style hero banner + intro.
 *
 * @package Smart_Leading_Net
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$section      = sln_get_portfolio_section();
$banner_title = trim( $section['main_heading'] ?? '' );

if ( '' === $banner_title ) {
	$banner_title = get_the_title();
}

$breadcrumb_label = get_the_title();

if ( '' === trim( $breadcrumb_label ) ) {
	$breadcrumb_label = __( 'Portfolio', 'smart-leading-net' );
}

sln_render_page_banner(
	array(
		'title'            => $banner_title,
		'breadcrumb_label' => $breadcrumb_label,
		'heading_id'       => 'portfolio-page-hero-heading',
	)
);
?>

<?php if ( ! empty( $section['small_heading'] ) || ! empty( $section['description'] ) ) : ?>
	<section class="contact-page__intro portfolio-page__intro" aria-labelledby="portfolio-page-intro-heading">
		<div class="contact-page__intro-inner sls-container">
			<?php if ( ! empty( $section['small_heading'] ) ) : ?>
				<h2 id="portfolio-page-intro-heading" class="contact-page__intro-title">
					<?php echo esc_html( $section['small_heading'] ); ?>
				</h2>
			<?php endif; ?>

			<?php if ( ! empty( $section['description'] ) ) : ?>
				<div class="contact-page__intro-text">
					<?php echo sln_portfolio_format_content( $section['description'] ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
				</div>
			<?php endif; ?>
		</div>
	</section>
<?php endif; ?>
