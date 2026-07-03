<?php
/**
 * New About For Test — brand pillars grid.
 *
 * @package Smart_Leading_Net
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$pillars = array(
	array(
		'icon'  => 'strategy.svg',
		'title' => __( 'Strategy First', 'smart-leading-net' ),
		'text'  => __( 'Every campaign starts with your goals, audience, and revenue targets — then we build a roadmap that connects marketing activity to business outcomes.', 'smart-leading-net' ),
	),
	array(
		'icon'  => 'paid-chart.svg',
		'title' => __( 'Performance Marketing', 'smart-leading-net' ),
		'text'  => __( 'SEO, paid media, and conversion optimization work together so your budget drives qualified traffic, stronger pipelines, and measurable ROI.', 'smart-leading-net' ),
	),
	array(
		'icon'  => 'focused.svg',
		'title' => __( 'Marketing Technology', 'smart-leading-net' ),
		'text'  => __( 'In-house tools and automation — backed by RevenueCloudFX — give you transparent reporting and smarter decisions at every stage.', 'smart-leading-net' ),
	),
	array(
		'icon'  => 'client-reviews.svg',
		'title' => __( 'Partnership Model', 'smart-leading-net' ),
		'text'  => __( 'We operate as an extension of your team with dedicated strategists, clear communication, and a shared focus on long-term growth.', 'smart-leading-net' ),
	),
);
?>

<section class="nat-pillars section-padding" aria-labelledby="nat-pillars-heading">
	<div class="nat-pillars__container sls-container">
		<div class="nat-pillars__header">
			<p class="nat-pillars__label"><?php esc_html_e( 'How We Work', 'smart-leading-net' ); ?></p>
			<h2 id="nat-pillars-heading" class="nat-pillars__title section-title">
				<?php esc_html_e( 'The Smart Leading Approach', 'smart-leading-net' ); ?>
			</h2>
			<p class="nat-pillars__description section-description">
				<?php esc_html_e( 'A full-service model designed around the way modern businesses grow — with data, speed, and accountability built in from day one.', 'smart-leading-net' ); ?>
			</p>
		</div>

		<div class="nat-pillars__grid">
			<?php foreach ( $pillars as $pillar ) : ?>
				<article class="nat-pillars__card card-custom">
					<div class="nat-pillars__icon" aria-hidden="true">
						<?php
						echo sln_get_upload_inline_svg( '2026/05/' . $pillar['icon'] ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
						?>
					</div>
					<h3 class="nat-pillars__card-title"><?php echo esc_html( $pillar['title'] ); ?></h3>
					<p class="nat-pillars__card-text"><?php echo esc_html( $pillar['text'] ); ?></p>
				</article>
			<?php endforeach; ?>
		</div>
	</div>
</section>
